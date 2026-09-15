<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'        => ['required', 'confirmed', Rules\Password::defaults()],
            'customer_group'  => ['required', 'in:retail,wholesale,trading'],
            'phone'           => ['required', 'string', 'max:20'],
            'company_name'    => ['required_if:customer_group,wholesale', 'required_if:customer_group,trading', 'nullable', 'string', 'max:255'],
            'company_reg_no'  => ['nullable', 'string', 'max:100'],
            'business_type'   => ['nullable', 'string', 'max:255'],
            'address'         => ['required', 'string', 'max:500'],
            'city'            => ['required', 'string', 'max:100'],
            'state'           => ['required', 'string', 'max:100'],
            'postcode'        => ['required', 'string', 'max:10'],
        ]);

        // Retail customers are auto-approved; wholesale/trading need approval
        $approvalStatus = match ($request->customer_group) {
            'wholesale', 'trading' => 'pending',
            default                => 'approved',
        };

        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'customer_group'  => $request->customer_group,
            'approval_status' => $approvalStatus,
            'phone'           => $request->phone,
            'company_name'    => $request->company_name,
            'company_reg_no'  => $request->company_reg_no,
            'business_type'   => $request->business_type,
            'address'         => $request->address,
            'city'            => $request->city,
            'state'           => $request->state,
            'postcode'        => $request->postcode,
        ]);

        event(new Registered($user));

        // Dispatch Welcome / Application Email & Admin Notification
        \App\Models\Setting::configureMailer();

        // 1. Dispatch Customer Welcome / Application Received Email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserRegistered($user));
            \Illuminate\Support\Facades\Log::info("Registration email sent to user: {$user->email}");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Registration email dispatch error for user {$user->email}: " . $e->getMessage());
        }

        // 2. Dispatch Admin Notification to configured store recipients
        try {
            $adminEmails = \App\Models\Setting::getAdminNotificationEmails();
            if (!empty($adminEmails)) {
                \Illuminate\Support\Facades\Mail::to($adminEmails)->send(new \App\Mail\AdminNewUserRegistered($user));
                \Illuminate\Support\Facades\Log::info("Admin registration alert sent to: " . implode(', ', $adminEmails));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Admin registration alert dispatch error for {$user->email}: " . $e->getMessage());
        }

        // If pending, log in user and redirect to pending approval page
        if ($user->isPending()) {
            Auth::login($user);
            return redirect()->route('approval.pending')->with('new_registration', true);
        }

        // Retail: log in immediately and migrate cart
        Auth::login($user);
        app(CartService::class)->migrateToUser($user->id);

        return redirect(route('shop.index'));
    }
}
