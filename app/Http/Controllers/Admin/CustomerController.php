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

        if ($request->filled('commercial_access')) {
            $query->where('commercial_access', $request->commercial_access);
        }

        if ($request->filled('country_market')) {
            $query->where('country_market', 'like', '%' . $request->country_market . '%');
        }

        if ($request->filled('destination_country')) {
            $query->where('destination_country', 'like', '%' . $request->destination_country . '%');
        }

        if ($request->filled('is_existing_customer')) {
            $query->where('is_existing_customer', $request->is_existing_customer);
        }

        if ($request->boolean('duplicates')) {
            $query->where(function ($q) {
                $q->whereIn('phone', function ($sub) {
                    $sub->select('phone')->from('users')->whereNotNull('phone')->where('phone', '!=', '')->groupBy('phone')->havingRaw('count(*) > 1');
                })->orWhereIn('company_reg_no', function ($sub) {
                    $sub->select('company_reg_no')->from('users')->whereNotNull('company_reg_no')->where('company_reg_no', '!=', '')->groupBy('company_reg_no')->havingRaw('count(*) > 1');
                })->orWhereIn('company_name', function ($sub) {
                    $sub->select('company_name')->from('users')->whereNotNull('company_name')->where('company_name', '!=', '')->groupBy('company_name')->havingRaw('count(*) > 1');
                });
            });
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

        // Identify duplicate attributes across current page
        $phones = $customers->pluck('phone')->filter()->toArray();
        $regNos = $customers->pluck('company_reg_no')->filter()->toArray();
        $companies = $customers->pluck('company_name')->filter()->toArray();

        $dupPhones = !empty($phones) ? User::whereIn('phone', $phones)->groupBy('phone')->havingRaw('count(*) > 1')->pluck('phone')->toArray() : [];
        $dupRegNos = !empty($regNos) ? User::whereIn('company_reg_no', $regNos)->groupBy('company_reg_no')->havingRaw('count(*) > 1')->pluck('company_reg_no')->toArray() : [];
        $dupCompanies = !empty($companies) ? User::whereIn('company_name', $companies)->groupBy('company_name')->havingRaw('count(*) > 1')->pluck('company_name')->toArray() : [];

        $duplicatesCount = User::where('customer_group', '!=', 'admin')
            ->where(function ($q) {
                $q->whereIn('phone', function ($sub) {
                    $sub->select('phone')->from('users')->whereNotNull('phone')->where('phone', '!=', '')->groupBy('phone')->havingRaw('count(*) > 1');
                })->orWhereIn('company_reg_no', function ($sub) {
                    $sub->select('company_reg_no')->from('users')->whereNotNull('company_reg_no')->where('company_reg_no', '!=', '')->groupBy('company_reg_no')->havingRaw('count(*) > 1');
                })->orWhereIn('company_name', function ($sub) {
                    $sub->select('company_name')->from('users')->whereNotNull('company_name')->where('company_name', '!=', '')->groupBy('company_name')->havingRaw('count(*) > 1');
                });
            })->count();

        $stats = [
            'total'      => User::where('customer_group', '!=', 'admin')->count(),
            'pending'    => User::where('customer_group', '!=', 'admin')->where('approval_status', 'pending')->count(),
            'approved'   => User::where('customer_group', '!=', 'admin')->where('approval_status', 'approved')->count(),
            'wholesale'  => User::whereIn('customer_group', ['wholesale', 'trading'])->count(),
            'duplicates' => $duplicatesCount,
        ];

        return view('admin.customers.index', compact('customers', 'stats', 'dupPhones', 'dupRegNos', 'dupCompanies'));
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
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'phone'                 => 'nullable|string|max:30',
            'customer_group'        => 'required|in:retail,walkin,wholesale,trading',
            'company_name'          => 'nullable|string|max:255',
            'company_reg_no'        => 'nullable|string|max:100',
            'business_type'         => 'nullable|string|max:100',
            'position_role'         => 'nullable|string|max:150',
            'country_market'        => 'nullable|string|max:100',
            'destination_country'   => 'nullable|string|max:100',
            'supply_arrangement'    => 'nullable|string|max:100',
            'commercial_access'     => 'nullable|string|max:50',
            'is_existing_customer'  => 'nullable|in:yes,no,not_sure',
            'existing_customer_ref' => 'nullable|string|max:255',
            'product_interest'      => 'nullable|string|max:500',
            'order_volume'          => 'nullable|string|max:100',
            'sourcing_requirements' => 'nullable|string|max:2000',
            'address'               => 'nullable|string|max:500',
            'city'                  => 'nullable|string|max:100',
            'state'                 => 'nullable|string|max:100',
            'postcode'              => 'nullable|string|max:20',
            'approval_status'       => 'required|in:pending,approved,rejected',
            'marketing_opt_in'      => 'nullable|boolean',
        ]);

        $validated['marketing_opt_in'] = $request->boolean('marketing_opt_in');

        if ($validated['approval_status'] === 'approved') {
            if ($user->approval_status !== 'approved') {
                $validated['approved_at'] = now();
                $validated['approved_by'] = auth()->id();
            }
            $validated['rejection_reason'] = null;
            // Always unblock OTP, reset attempts and verify email when admin approves or saves approved status
            $validated['email_otp_blocked_at'] = null;
            $validated['email_otp_attempts']   = 0;
            $validated['email_otp_resends']    = 0;
            $validated['email_otp_code']       = null;
            $validated['email_otp_expires_at'] = null;
            $validated['email_otp_sent_at']    = null;
            if (!$user->email_verified_at) {
                $validated['email_verified_at'] = now();
            }
        } elseif ($validated['approval_status'] === 'rejected' && $user->approval_status !== 'rejected') {
            $validated['approved_at'] = null;
            $validated['approved_by'] = null;
        }

        $user->update($validated);

        return back()->with('success', "Customer profile for {$user->name} has been updated.");
    }

    public function approve(User $user)
    {
        $user->unblockAndVerifyFromAdmin();

        // Send approval email
        $mailSent = false;
        $mailError = null;
        try {
            \App\Models\Setting::configureMailer();
            Mail::to($user->email)->send(new \App\Mail\AccountApproved($user, $user->preferred_locale));
            $mailSent = true;
            \Illuminate\Support\Facades\Log::info("Account approval email sent to {$user->email} in locale: " . ($user->preferred_locale ?? 'en'));
        } catch (\Throwable $e) {
            $mailError = $e->getMessage();
            \Illuminate\Support\Facades\Log::error("Account approval email failed for {$user->email}: " . $mailError);
        }

        if ($mailSent) {
            return back()->with('success', "Account for {$user->name} has been approved, unblocked, and confirmation email sent to {$user->email}.");
        }

        return back()->with('success', "Account for {$user->name} has been approved and unblocked.");
    }

    public function unblock(User $user)
    {
        $user->unblockAndVerifyFromAdmin();

        return back()->with('success', "Customer {$user->name} has been successfully unblocked and email verified. They can now log in and access their dashboard.");
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
            Mail::to($user->email)->send(new \App\Mail\AccountRejected($user, $user->preferred_locale));
            $mailSent = true;
            \Illuminate\Support\Facades\Log::info("Account rejection email sent to {$user->email} in locale: " . ($user->preferred_locale ?? 'en'));
        } catch (\Throwable $e) {
            $mailError = $e->getMessage();
            \Illuminate\Support\Facades\Log::error("Account rejection email failed for {$user->email}: " . $mailError);
        }

        if ($mailSent) {
            return back()->with('info', "Account for {$user->name} has been rejected and notice sent to {$user->email}.");
        }

        return back()->with('warning', "Account for {$user->name} has been rejected in database, but notification email could not be delivered to {$user->email} ({$mailError}).");
    }

    /**
     * Delete a customer account.
     */
    public function destroy(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Administrator accounts cannot be deleted.',
                ], 403);
            }
            return back()->with('error', 'Administrator accounts cannot be deleted.');
        }

        $userName = $user->name;

        // Delete customer avatar if exists
        if ($user->avatar && file_exists(public_path($user->avatar))) {
            @unlink(public_path($user->avatar));
        }

        $user->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Customer '{$userName}' has been deleted successfully.",
            ]);
        }

        return back()->with('success', "Customer '{$userName}' has been deleted successfully.");
    }
}
