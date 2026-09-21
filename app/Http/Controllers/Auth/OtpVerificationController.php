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
            return redirect()->route('login', ['locale' => current_locale()])->with('error', __t('auth.session_expired', 'Session expired. Please sign in or register.'));
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
                return redirect()->route('approval.pending', ['locale' => current_locale()]);
            }
            return redirect()->route('account.dashboard', ['locale' => current_locale()])->with('success', __t('auth.account_verified_approved', 'Your account has been verified and approved.'));
        }

        $isBlocked = $user->isOtpBlocked();
        $hasUsedResend = $user->hasUsedOtpResend();
        $isLocked = $user->hasExceededOtpAttempts() || $isBlocked;

        // If no OTP exists yet, or if it expired, and user is not locked, generate fresh one
        if ((empty($user->email_otp_code) || $user->isOtpExpired()) && !$isLocked) {
            $otp = $user->generateEmailOtp(false);
            Setting::configureMailer();
            try {
                Mail::to($user->email)->send(new SendEmailOtp($user, $otp, $user->preferred_locale ?? current_locale()));
            } catch (\Throwable $e) {
                Log::error("Failed to dispatch initial OTP to {$user->email}: " . $e->getMessage());
            }
            $user->refresh();
        }

        $attemptsUsed = (int) $user->email_otp_attempts;
        $attemptsRemaining = max(0, 3 - $attemptsUsed);
        $isExpired = $user->isOtpExpired();

        $cooldownRemaining = 0;
        if ($user->email_otp_sent_at && !$isExpired) {
            $elapsed = max(0, now()->timestamp - \Carbon\Carbon::parse($user->email_otp_sent_at)->timestamp);
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
            return redirect()->route('login', ['locale' => current_locale()])->with('error', __t('auth.session_expired', 'Session expired. Please sign in or register.'));
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
                return redirect()->route('approval.pending', ['locale' => current_locale()]);
            }
            return redirect()->route('account.dashboard', ['locale' => current_locale()])->with('success', __t('auth.account_verified_approved', 'Your account has been verified and approved.'));
        }

        // Check if account is blocked
        if ($user->isOtpBlocked()) {
            return back()->withErrors([
                'otp' => __t('auth.otp_error_blocked', 'Your account is blocked. Please contact support.'),
            ]);
        }

        // Check if brute force lockout is active
        if ($user->hasExceededOtpAttempts()) {
            if ($user->hasUsedOtpResend()) {
                $user->blockUserForOtpFailure();
                return back()->withErrors([
                    'otp' => __t('auth.otp_error_blocked_attempts', 'Your account has been blocked due to multiple failed verification attempts. Please contact support.'),
                ]);
            }

            return back()->withErrors([
                'otp' => __t('auth.otp_error_max_attempts', 'Maximum attempts reached (3/3). This code has been deactivated. Please click "Send Me a New Code" below.'),
            ]);
        }

        // Validate 6 digits format
        $request->validate([
            'otp' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
        ], [
            'otp.required' => __t('auth.otp_error_required', 'Please enter the 6-digit verification code.'),
            'otp.regex'    => __t('auth.otp_error_regex', 'The verification code must be exactly 6 numeric digits.'),
        ]);

        $inputOtp = trim($request->otp);

        // Check code expiration
        if ($user->isOtpExpired()) {
            return back()->withErrors([
                'otp' => __t('auth.otp_error_expired', 'This verification code has expired (valid for 10 minutes). Please request a new code below.'),
            ]);
        }

        // Verify match
        if (empty($user->email_otp_code) || $inputOtp !== (string) $user->email_otp_code) {
            $remaining = $user->recordFailedOtpAttempt();
            $user->refresh();

            // If account got blocked (failed on the resent OTP)
            if ($user->isOtpBlocked()) {
                return back()->withErrors([
                    'otp' => __t('auth.otp_error_blocked_attempts', 'Your account has been blocked due to multiple failed verification attempts. Please contact support.'),
                ]);
            }

            if ($remaining === 0) {
                return back()->withErrors([
                    'otp' => __t('auth.otp_error_max_attempts', 'Maximum attempts reached (3/3). This code has been deactivated. Please click "Send Me a New Code" below.'),
                ]);
            }

            return back()->withErrors([
                'otp' => __t('auth.otp_error_incorrect', "Incorrect verification code. :remaining attempt(s) remaining.", ['remaining' => $remaining]),
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
            Mail::to($user->email)->send(new UserRegistered($user, $user->preferred_locale ?? current_locale()));
            Log::info("Registration welcome email dispatched to verified user: {$user->email} in locale: " . ($user->preferred_locale ?? current_locale()));
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
            return redirect()->route('approval.pending', ['locale' => current_locale()])->with('new_registration', true);
        }

        // Retail customer: direct to shop with confirmation
        return redirect()->route('shop.index', ['locale' => current_locale()])->with('success', __t('auth.email_verified_welcome', 'Email verified successfully! Welcome to MST Seafood.'));
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
            return redirect()->route('shop.index', ['locale' => current_locale()]);
        }

        // Check if account is blocked
        if ($user->isOtpBlocked()) {
            return back()->withErrors([
                'resend' => __t('auth.otp_error_blocked_attempts', 'Your account has been blocked due to failed verification attempts. Please contact support.'),
            ]);
        }

        // If code has expired, reset resend counter so user can get a new code cycle
        if ($user->isOtpExpired()) {
            $user->email_otp_resends = 0;
            $user->email_otp_attempts = 0;
            $user->save();
        } elseif ($user->hasUsedOtpResend()) {
            return back()->withErrors([
                'resend' => __t('auth.otp_resend_limit_reached', 'You have already used your 1 allowed code resend. The resend option is now disabled.'),
            ]);
        }

        // Enforce cooldown only if user is not in a locked state and code has not expired
        if (!$user->hasExceededOtpAttempts() && !$user->isOtpExpired() && $user->email_otp_sent_at) {
            $elapsed = max(0, now()->timestamp - \Carbon\Carbon::parse($user->email_otp_sent_at)->timestamp);
            if ($elapsed < 30) {
                $wait = 30 - $elapsed;
                return back()->withErrors([
                    'resend' => __t('auth.otp_resend_cooldown', "Please wait :seconds second(s) before requesting another verification code.", ['seconds' => $wait]),
                ]);
            }
        }

        // Generate brand new OTP & increment resend count (isResend = true)
        $otp = $user->generateEmailOtp(true);

        Setting::configureMailer();
        try {
            Mail::to($user->email)->send(new SendEmailOtp($user, $otp, $user->preferred_locale ?? current_locale()));
            Log::info("Fresh OTP code (resend 1/1) dispatched to user {$user->email} in locale: " . ($user->preferred_locale ?? current_locale()));
        } catch (\Throwable $e) {
            Log::error("Failed to resend OTP to {$user->email}: " . $e->getMessage());
            return back()->with('error', __t('auth.otp_email_send_failed', 'Unable to send email right now. Please check your SMTP settings or try again shortly.'));
        }

        return back()->with('status', __t('auth.otp_success_resent', 'A fresh 6-digit verification code has been dispatched to your email.'));
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
                ? ($user->isAdmin() ? route('admin.dashboard') : ($user->isPending() ? route('approval.pending', ['locale' => current_locale()]) : route('account.dashboard', ['locale' => current_locale()])))
                : null,
        ]);
    }
}
