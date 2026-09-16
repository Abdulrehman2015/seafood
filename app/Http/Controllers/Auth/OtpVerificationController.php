<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminNewUserRegistered;
use App\Mail\SendEmailOtp;
use App\Mail\UserRegistered;
use App\Models\Setting;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Resolve the target unverified user from session or authenticated user.
     */
    protected function resolveTargetUser(Request $request): ?User
    {
        $userId = $request->session()->get('otp_verify_user_id');
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                return $user;
            }
        }

        if (Auth::check()) {
            return Auth::user();
        }

        return null;
    }

    /**
     * Mask an email address for safe display (e.g. j***@example.com).
     */
    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }
        $name = $parts[0];
        $domain = $parts[1];

        if (strlen($name) <= 2) {
            $maskedName = substr($name, 0, 1) . '***';
        } else {
            $maskedName = substr($name, 0, 2) . str_repeat('*', max(3, strlen($name) - 3)) . substr($name, -1);
        }

        return $maskedName . '@' . $domain;
    }

    /**
     * Display the OTP verification screen.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $this->resolveTargetUser($request);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Session expired. Please sign in or register.');
        }

        if ($user->isEmailVerified()) {
            if (!Auth::check()) {
                Auth::guard('web')->login($user);
                $request->session()->regenerate();
            }
            $request->session()->forget('otp_verify_user_id');

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->isPending()) {
                return redirect()->route('approval.pending');
            }
            return redirect()->route('account.dashboard')->with('success', 'Your account has been verified and approved.');
        }

        $isBlocked = $user->isOtpBlocked();
        $hasUsedResend = $user->hasUsedOtpResend();
        $isLocked = $user->hasExceededOtpAttempts() || $isBlocked;

        // If no OTP exists yet, not blocked, and not locked, generate initial one
        if (empty($user->email_otp_code) && !$isLocked) {
            $otp = $user->generateEmailOtp(false);
            Setting::configureMailer();
            try {
                Mail::to($user->email)->send(new SendEmailOtp($user, $otp));
            } catch (\Throwable $e) {
                Log::error("Failed to dispatch initial OTP to {$user->email}: " . $e->getMessage());
            }
        }

        $attemptsUsed = (int) $user->email_otp_attempts;
        $attemptsRemaining = max(0, 3 - $attemptsUsed);
        $isExpired = $user->isOtpExpired();

        $cooldownRemaining = 0;
        if ($user->email_otp_sent_at) {
            $elapsed = now()->diffInSeconds($user->email_otp_sent_at);
            if ($elapsed < 60) {
                $cooldownRemaining = 60 - $elapsed;
            }
        }

        return view('auth.verify-otp', [
            'user'              => $user,
            'maskedEmail'       => $this->maskEmail($user->email),
            'attemptsUsed'      => $attemptsUsed,
            'attemptsRemaining' => $attemptsRemaining,
            'isLocked'          => $isLocked,
            'isBlocked'         => $isBlocked,
            'hasUsedResend'     => $hasUsedResend,
            'isExpired'         => $isExpired,
            'cooldownRemaining' => $cooldownRemaining,
        ]);
    }

    /**
     * Verify the submitted OTP code.
     */
    public function verify(Request $request): RedirectResponse
    {
        $user = $this->resolveTargetUser($request);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Session expired. Please sign in or register.');
        }

        if ($user->isEmailVerified()) {
            if (!Auth::check()) {
                Auth::guard('web')->login($user);
                $request->session()->regenerate();
            }
            $request->session()->forget('otp_verify_user_id');

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->isPending()) {
                return redirect()->route('approval.pending');
            }
            return redirect()->route('account.dashboard')->with('success', 'Your account has been verified and approved.');
        }

        // Check if account is blocked
        if ($user->isOtpBlocked()) {
            return back()->withErrors([
                'otp' => 'Your account is blocked. Please contact support.',
            ]);
        }

        // Check if brute force lockout is active
        if ($user->hasExceededOtpAttempts()) {
            if ($user->hasUsedOtpResend()) {
                $user->blockUserForOtpFailure();
                return back()->withErrors([
                    'otp' => 'Your account has been blocked due to multiple failed verification attempts. Please contact support.',
                ]);
            }

            return back()->withErrors([
                'otp' => 'Maximum attempts reached (3/3). This code has been deactivated. Please click "Send Me a New Code" below.',
            ]);
        }

        // Validate 6 digits format
        $request->validate([
            'otp' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
        ], [
            'otp.required' => 'Please enter the 6-digit verification code.',
            'otp.regex'    => 'The verification code must be exactly 6 numeric digits.',
        ]);

        $inputOtp = trim($request->otp);

        // Check code expiration
        if ($user->isOtpExpired()) {
            if ($user->hasUsedOtpResend()) {
                $user->blockUserForOtpFailure();
                return back()->withErrors([
                    'otp' => 'This verification code has expired and your allowed resend limit has been reached. Your account has been blocked. Please contact support.',
                ]);
            }

            return back()->withErrors([
                'otp' => 'This verification code has expired (valid for 10 minutes). Please request a new code below.',
            ]);
        }

        // Verify match
        if (empty($user->email_otp_code) || $inputOtp !== (string) $user->email_otp_code) {
            $remaining = $user->recordFailedOtpAttempt();
            $user->refresh();

            // If account got blocked (failed on the resent OTP)
            if ($user->isOtpBlocked()) {
                return back()->withErrors([
                    'otp' => 'Your account has been blocked due to multiple failed verification attempts. Please contact support.',
                ]);
            }

            if ($remaining === 0) {
                return back()->withErrors([
                    'otp' => 'Incorrect verification code. You have reached the maximum of 3 failed attempts. Please click "Send Me a New Code" below.',
                ]);
            }

            return back()->withErrors([
                'otp' => "Incorrect verification code. You have {$remaining} attempt(s) remaining.",
            ]);
        }

        // Verification successful: Mark email as verified and clear OTP fields
        $user->email_verified_at = now();
        $user->clearEmailOtp();
        $user->save();

        // Clear verification session key
        $request->session()->forget('otp_verify_user_id');

        // Log the verified user in
        Auth::login($user);
        $request->session()->regenerate();

        // Migrate guest cart items to user
        try {
            app(CartService::class)->migrateToUser($user->id);
        } catch (\Throwable $e) {
            Log::warning("Cart migration failed for user {$user->id}: " . $e->getMessage());
        }

        // Dispatch Welcome Email & Admin Notification
        Setting::configureMailer();
        try {
            Mail::to($user->email)->send(new UserRegistered($user));
            Log::info("Registration welcome email dispatched to verified user: {$user->email}");
        } catch (\Throwable $e) {
            Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
        }

        try {
            $adminEmails = Setting::getAdminNotificationEmails();
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new AdminNewUserRegistered($user));
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send admin registration alert for {$user->email}: " . $e->getMessage());
        }

        // If commercial customer pending review, direct to approval pending
        if ($user->isPending()) {
            return redirect()->route('approval.pending')->with('new_registration', true);
        }

        // Retail customer: direct to shop with confirmation
        return redirect()->route('shop.index')->with('success', 'Email verified successfully! Welcome to MST Seafood.');
    }

    /**
     * Resend a fresh OTP to the user's email address (Limit: 1 time only).
     */
    public function resend(Request $request): RedirectResponse
    {
        $user = $this->resolveTargetUser($request);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Session expired. Please sign in or register.');
        }

        if ($user->isEmailVerified()) {
            return redirect()->route('shop.index');
        }

        // Check if account is blocked
        if ($user->isOtpBlocked()) {
            return back()->withErrors([
                'resend' => 'Your account has been blocked due to failed verification attempts. Please contact support.',
            ]);
        }

        // Rule 1: User can click "Send me a new code" only 1 time
        if ($user->hasUsedOtpResend()) {
            return back()->withErrors([
                'resend' => 'You have already used your 1 allowed code resend. The resend option is now disabled.',
            ]);
        }

        // Enforce cooldown only if user is not in a locked state
        if (!$user->hasExceededOtpAttempts() && $user->email_otp_sent_at) {
            $elapsed = now()->diffInSeconds($user->email_otp_sent_at);
            if ($elapsed < 30) {
                $wait = 30 - $elapsed;
                return back()->withErrors([
                    'resend' => "Please wait {$wait} second(s) before requesting another verification code.",
                ]);
            }
        }

        // Generate brand new OTP & increment resend count (isResend = true)
        $otp = $user->generateEmailOtp(true);

        Setting::configureMailer();
        try {
            Mail::to($user->email)->send(new SendEmailOtp($user, $otp));
            Log::info("Fresh OTP code (resend 1/1) dispatched to user {$user->email}");
        } catch (\Throwable $e) {
            Log::error("Failed to resend OTP to {$user->email}: " . $e->getMessage());
            return back()->with('error', 'Unable to send email right now. Please check your SMTP settings or try again shortly.');
        }

        return back()->with('status', 'A new 6-digit verification code has been sent to your email. You have 3 attempts to verify. Note: this was your 1 allowed resend.');
    }

    /**
     * Polling endpoint to check live verification & block status.
     */
    public function checkStatus(Request $request): JsonResponse
    {
        $user = $this->resolveTargetUser($request);

        if (!$user) {
            return response()->json(['valid' => false]);
        }

        $user->refresh();

        return response()->json([
            'valid'    => true,
            'blocked'  => $user->isOtpBlocked(),
            'verified' => $user->isEmailVerified(),
            'approved' => $user->isApproved(),
            'status'   => $user->approval_status,
            'redirect' => $user->isEmailVerified()
                ? ($user->isAdmin() ? route('admin.dashboard') : ($user->isPending() ? route('approval.pending') : route('account.dashboard')))
                : null,
        ]);
    }
}
