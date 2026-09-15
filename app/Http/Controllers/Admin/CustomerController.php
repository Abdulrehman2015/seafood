<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('customer_group', '!=', 'admin');

        if ($request->filled('group')) {
            $query->where('customer_group', $request->group);
        }

        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->get('sort') === 'name') {
            $query->orderBy('name', 'asc');
        } elseif ($request->get('sort') === 'company') {
            $query->orderBy('company_name', 'asc');
        } elseif ($request->get('sort') === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $customers = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => User::where('customer_group', '!=', 'admin')->count(),
            'pending'   => User::where('customer_group', '!=', 'admin')->where('approval_status', 'pending')->count(),
            'approved'  => User::where('customer_group', '!=', 'admin')->where('approval_status', 'approved')->count(),
            'wholesale' => User::whereIn('customer_group', ['wholesale', 'trading'])->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['orders.items', 'quotations.items']);

        $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('total');
        $ordersCount = $user->orders()->count();
        $quotationsCount = $user->quotations()->count();
        $lastOrder = $user->orders()->latest()->first();

        return view('admin.customers.show', compact('user', 'totalSpent', 'ordersCount', 'quotationsCount', 'lastOrder'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'phone'           => 'nullable|string|max:30',
            'customer_group'  => 'required|in:retail,wholesale,trading',
            'company_name'    => 'nullable|string|max:255',
            'company_reg_no'  => 'nullable|string|max:100',
            'business_type'   => 'nullable|string|max:100',
            'address'         => 'nullable|string|max:500',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'postcode'        => 'nullable|string|max:20',
            'approval_status' => 'required|in:pending,approved,rejected',
        ]);

        if ($validated['approval_status'] === 'approved' && $user->approval_status !== 'approved') {
            $validated['approved_at'] = now();
            $validated['approved_by'] = auth()->id();
            $validated['rejection_reason'] = null;
        }

        $user->update($validated);

        return back()->with('success', "Customer profile for {$user->name} has been updated.");
    }

    public function approve(User $user)
    {
        if (!in_array($user->customer_group, ['wholesale', 'trading'])) {
            return back()->with('error', 'Only wholesale/trading accounts require approval.');
        }

        $user->update([
            'approval_status'  => 'approved',
            'approved_at'      => now(),
            'approved_by'      => auth()->id(),
            'rejection_reason' => null,
        ]);

        // Send approval email
        $mailSent = false;
        $mailError = null;
        try {
            \App\Models\Setting::configureMailer();
            Mail::to($user->email)->send(new \App\Mail\AccountApproved($user));
            $mailSent = true;
            \Illuminate\Support\Facades\Log::info("Account approval email sent to {$user->email}");
        } catch (\Throwable $e) {
            $mailError = $e->getMessage();
            \Illuminate\Support\Facades\Log::error("Account approval email failed for {$user->email}: " . $mailError);
        }

        if ($mailSent) {
            return back()->with('success', "Account for {$user->name} has been approved and confirmation email sent to {$user->email}.");
        }

        return back()->with('warning', "Account for {$user->name} has been approved in database, but confirmation email could not be delivered to {$user->email} ({$mailError}).");
    }

    public function reject(Request $request, User $user)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $reason = $request->reason ?? 'Your application did not meet our verification requirements.';

        $user->update([
            'approval_status'  => 'rejected',
            'rejection_reason' => $reason,
        ]);

        // Send rejection email
        $mailSent = false;
        $mailError = null;
        try {
            \App\Models\Setting::configureMailer();
            Mail::to($user->email)->send(new \App\Mail\AccountRejected($user));
            $mailSent = true;
            \Illuminate\Support\Facades\Log::info("Account rejection email sent to {$user->email}");
        } catch (\Throwable $e) {
            $mailError = $e->getMessage();
            \Illuminate\Support\Facades\Log::error("Account rejection email failed for {$user->email}: " . $mailError);
        }

        if ($mailSent) {
            return back()->with('info', "Account for {$user->name} has been rejected and notice sent to {$user->email}.");
        }

        return back()->with('warning', "Account for {$user->name} has been rejected in database, but notification email could not be delivered to {$user->email} ({$mailError}).");
    }
}
