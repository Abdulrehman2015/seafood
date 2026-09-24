@extends('layouts.app')
@section('title', app(\App\Services\TranslationService::class)->translate('auth.otp_page_title', 'Verify Email Code — MST Import and Export Sdn Bhd'))

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 @t('nav.home', 'Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('auth.otp_breadcrumb', 'Email Verification')</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:3px 10px;border-radius:999px;font-size:0.72rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        🔐 @t('auth.otp_badge_2fa', 'Two-Factor Security')
                    </span>
                    <span style="color:#bae6fd;font-size:0.8rem">@t('auth.otp_badge_bruteforce', 'Brute-Force Protected')</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.3rem);margin-bottom:6px;letter-spacing:-0.02em">
                    @t('auth.otp_header_title', 'Verify Your Email Address')
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:640px;line-height:1.5;margin:0">
                    @t('auth.otp_header_subtitle', 'A secure 6-digit verification code has been dispatched to your email address to confirm ownership and activate your customer portal.')
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div style="font-size:0.85rem;padding:6px 14px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb">
                    🛡️ @t('auth.otp_badge_safe', 'Safe Onboarding')
                </div>
            </div>
        </div>
    </div>
</div>

<div style="min-height:65vh;padding:var(--space-10) var(--space-4) var(--space-16);display:flex;justify-content:center;background:#f8fafc">
    <div style="width:100%;max-width:480px">
        <div class="card" style="box-shadow:0 12px 30px -5px rgba(0,0,0,0.08), 0 8px 12px -6px rgba(0,0,0,0.04);border:1px solid #e2e8f0;border-radius:20px;padding:36px 30px;background:#ffffff">
            
            <div class="text-center" style="margin-bottom:24px">
                <div style="display:inline-flex;align-items:center;justify-content:center;width:58px;height:58px;background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:50%;margin-bottom:12px;font-size:1.8rem">
                    ✉️
                </div>
                <h2 style="font-family:var(--font-heading);font-size:1.4rem;font-weight:800;color:#0f274a;margin:0 0 6px">@t('auth.otp_check_email_title', 'Check Your Email')</h2>
                <p style="color:#64748b;font-size:0.9rem;margin:0;line-height:1.5">
                    @t('auth.otp_sent_to', 'We sent a 6-digit code to') <br>
                    <strong style="color:#0f172a;font-family:monospace;font-size:1rem">{{ $maskedEmail }}</strong>
                </p>
            </div>

            {{-- Status Flash Notice --}}
            @if(session('status'))
                <div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;padding:12px 14px;border-radius:10px;font-size:0.85rem;margin-bottom:20px;display:flex;align-items:center;gap:8px">
                    <span>✓</span>
                    <span>{{ __t(session('status'), session('status')) }}</span>
                </div>
            @endif

            {{-- General Errors --}}
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:12px 14px;border-radius:10px;font-size:0.85rem;margin-bottom:20px;display:flex;align-items:center;gap:8px">
                    <span>⚠️</span>
                    <span>{{ __t(session('error'), session('error')) }}</span>
                </div>
            @endif

            {{-- Blocked Account State --}}
            @if($isBlocked)
                <div style="background:#fef2f2;border:2px solid #ef4444;color:#991b1b;padding:26px 20px;border-radius:16px;margin-bottom:20px;text-align:center">
                    <div style="width:60px;height:60px;border-radius:50%;background:#fee2e2;color:#ef4444;font-size:1.8rem;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px">
                        🛑
                    </div>
                    <h3 style="font-size:1.3rem;font-weight:800;color:#991b1b;margin:0 0 8px">@t('auth.otp_blocked_title', 'Your Account Is Blocked')</h3>
                    <p style="font-size:0.92rem;line-height:1.6;margin:0 0 16px;color:#7f1d1d;font-weight:600">
                        @t('auth.otp_blocked_subtitle', 'Your account is blocked. Please contact support.')
                    </p>
                    <p style="font-size:0.85rem;line-height:1.5;margin:0 0 16px;color:#991b1b">
                        @t('auth.otp_blocked_desc', 'You have failed to verify your email address after requesting a new code. To protect customer security and prevent brute-force attacks, your account has been blocked.')
                    </p>
                    <div style="background:#ffffff;border:1px solid #fecaca;border-radius:10px;padding:14px;margin-bottom:18px;font-size:0.86rem;color:#64748b">
                        @t('auth.otp_support_label', 'Customer Support:')
                        <div style="margin-top:6px;font-weight:700;color:#0f172a;font-size:0.95rem">
                            ✉️ <a href="mailto:info@mst.my" style="color:#2563eb">info@mst.my</a> &nbsp;•&nbsp; 📞 +60 3-8958 2888
                        </div>
                    </div>
                    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
                        <a href="{{ route('contact') }}" class="btn btn-primary" style="background:#2563eb;border-color:#2563eb;padding:12px 24px;font-weight:700;font-size:0.92rem;border-radius:10px;text-decoration:none;display:inline-flex;align-items:center;gap:8px">
                            <span>💬 @t('auth.otp_btn_contact_support', 'Contact Support')</span>
                        </a>
                        <button type="button" onclick="window.location.reload();" class="btn btn-secondary" style="background:#f8fafc;border:1.5px solid #cbd5e1;color:#1e293b;padding:12px 20px;font-weight:700;font-size:0.92rem;border-radius:10px;display:inline-flex;align-items:center;gap:6px;cursor:pointer">
                            <span>🔄 @t('auth.otp_btn_check_status', 'Check Approval Status')</span>
                        </button>
                    </div>
                </div>
                <div style="text-align:center;margin-top:16px;font-size:0.85rem">
                    <a href="{{ route('login') }}" style="color:#64748b;text-decoration:underline">@t('auth.otp_return_to_signin', 'Return to Sign In')</a>
                </div>
            @else
                {{-- Attempt Status & Brute-Force Alert --}}
                @if($isLocked)
                    <div style="background:#fef2f2;border:1.5px solid #ef4444;color:#991b1b;padding:18px;border-radius:12px;margin-bottom:22px;text-align:center">
                        <div style="font-weight:800;font-size:0.95rem;margin-bottom:6px;display:flex;align-items:center;justify-content:center;gap:6px">
                            <span>🛑</span> @t('auth.otp_locked_title', 'Verification Code Locked')
                        </div>
                        <p style="font-size:0.84rem;line-height:1.5;margin:0 0 14px;color:#7f1d1d">
                            @t('auth.otp_locked_desc', 'You have exceeded the maximum of 3 attempts. To protect your account from brute-force access, this code has been deactivated. Please request a new code below.', ['max' => 3])
                        </p>
                        @if(!$hasUsedResend)
                            <form method="POST" action="{{ route('otp.resend') }}" style="margin:0" onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerText='{{ app(\App\Services\TranslationService::class)->translate('auth.otp_sending_new_code', 'Sending New Code...') }}';">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="background:#dc2626;border-color:#dc2626;width:100%;padding:11px;font-weight:700;font-size:0.9rem;border-radius:8px">
                                    @t('auth.otp_btn_send_new_code', 'Send Me a New Code')
                                </button>
                            </form>
                        @else
                            <button type="button" disabled class="btn" style="background:#cbd5e1;color:#64748b;border:1px solid #94a3b8;width:100%;padding:11px;font-weight:700;font-size:0.9rem;border-radius:8px;cursor:not-allowed">
                                @t('auth.otp_btn_send_disabled', 'Send Me a New Code (Disabled)')
                            </button>
                        @endif
                    </div>
                @elseif($errors->has('otp'))
                    <div style="background:#fffbeb;border:1.5px solid #f59e0b;color:#92400e;padding:14px;border-radius:12px;margin-bottom:22px">
                        <div style="font-weight:700;font-size:0.88rem;margin-bottom:4px;display:flex;align-items:center;gap:6px">
                            <span>⚠️</span> {{ __t($errors->first('otp'), $errors->first('otp')) }}
                        </div>
                        @if($hasUsedResend)
                            <div style="font-size:0.8rem;color:#b45309;font-weight:700;margin-top:4px">
                                ⚠️ @t('auth.otp_final_warning', 'Final warning: This is your resent code. If you exhaust your 3 attempts, your account will be blocked.')
                            </div>
                        @else
                            <div style="font-size:0.78rem;color:#b45309;margin-top:4px">
                                @t('auth.otp_security_notice', 'Security Notice: Exactly 3 attempts are allowed before a new code is enforced.')
                            </div>
                        @endif
                    </div>
                @else
                    @if($hasUsedResend)
                        <div style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;padding:10px 14px;border-radius:10px;font-size:0.82rem;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between">
                            <span>⚠️ @t('auth.otp_resent_active', 'Resent Code Active (Final Cycle)')</span>
                            <span style="font-weight:700;background:#fef3c7;padding:2px 8px;border-radius:6px">
                                @t('auth.otp_attempts_left', ':remaining of 3 attempts left', ['remaining' => $attemptsRemaining])
                            </span>
                        </div>
                    @else
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:10px 14px;border-radius:10px;font-size:0.82rem;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between">
                            <span>🛡️ @t('auth.otp_code_valid_10m', 'Code Valid for 10 minutes')</span>
                            <span style="font-weight:700;background:#dcfce7;padding:2px 8px;border-radius:6px">
                                @t('auth.otp_attempts_left', ':remaining of 3 attempts left', ['remaining' => $attemptsRemaining])
                            </span>
                        </div>
                    @endif
                @endif

                {{-- OTP Verification Form --}}
                <form method="POST" action="{{ route('otp.check') }}" id="otpForm">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="otp" id="fullOtpInput">

                    <div style="margin-bottom:24px">
                        <label style="display:block;text-align:center;font-weight:700;color:#334155;font-size:0.88rem;margin-bottom:12px">
                            @t('auth.otp_enter_code', 'Enter 6-Digit Code')
                        </label>

                        {{-- 6 Digit Inputs --}}
                        <div style="display:flex;justify-content:center;gap:8px" id="otpInputsContainer">
                            @for($i = 0; $i < 6; $i++)
                            <input type="text"
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   maxlength="1"
                                   class="otp-digit-box"
                                   data-index="{{ $i }}"
                                   {{ $isLocked ? 'disabled' : '' }}
                                   style="width:48px;height:56px;font-size:1.6rem;font-weight:800;font-family:monospace;text-align:center;border-radius:10px;border:2px solid {{ $errors->has('otp') ? '#f87171' : '#cbd5e1' }};background:{{ $isLocked ? '#f1f5f9' : '#ffffff' }};color:#0f172a;outline:none;transition:all 0.15s ease"
                                   autocomplete="off">
                            @endfor
                        </div>
                        <div style="text-align:center;margin-top:8px;font-size:0.75rem;color:#94a3b8">
                            @t('auth.otp_paste_tip', 'Tip: You can paste the full 6-digit code directly')
                        </div>
                    </div>

                    <button type="submit"
                            id="verifySubmitBtn"
                            class="btn btn-primary"
                            {{ $isLocked ? 'disabled' : '' }}
                            style="width:100%;padding:13px;font-size:0.95rem;font-weight:700;border-radius:10px;background:{{ $isLocked ? '#94a3b8' : '#2563eb' }};border-color:{{ $isLocked ? '#94a3b8' : '#2563eb' }};box-shadow:0 4px 14px rgba(37,99,235,0.25);display:flex;align-items:center;justify-content:center;gap:8px;cursor:{{ $isLocked ? 'not-allowed' : 'pointer' }}">
                        <span>@t('auth.otp_btn_verify_activate', 'Verify & Activate Account')</span>
                        <span>→</span>
                    </button>
                </form>

                {{-- Resend & Auxiliary Options --}}
                @if(!$isLocked)
                <div style="margin-top:24px;padding-top:20px;border-top:1px solid #f1f5f9;display:flex;flex-direction:column;align-items:center;gap:12px">
                    @if(!$hasUsedResend)
                    <form method="POST" action="{{ route('otp.resend') }}" id="resendForm" style="margin:0" onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerText='{{ app(\App\Services\TranslationService::class)->translate('auth.otp_sending', 'Sending...') }}';">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <div style="font-size:0.86rem;color:#64748b;display:flex;align-items:center;gap:6px">
                            <span>@t('auth.otp_didnt_receive', "Didn't receive code?")</span>
                            <button type="submit"
                                    id="resendBtn"
                                    style="background:transparent;border:none;color:#2563eb;font-weight:700;cursor:pointer;padding:0;text-decoration:underline;font-size:0.86rem"
                                    {{ $cooldownRemaining > 0 ? 'disabled' : '' }}>
                                @t('auth.otp_resend_link', 'Send me a new code')
                            </button>
                            <span id="cooldownLabel" style="font-size:0.8rem;color:#94a3b8;display:{{ $cooldownRemaining > 0 ? 'inline' : 'none' }}">
                                (<span id="cooldownSeconds">{{ (int) $cooldownRemaining }}</span>@t('auth.otp_sec_unit', 's'))
                            </span>
                        </div>
                        @error('resend')
                            <div style="color:#ef4444;font-size:0.8rem;text-align:center;margin-top:4px">{{ __t($message, $message) }}</div>
                        @enderror
                    </form>
                    @else
                        <div style="font-size:0.84rem;color:#94a3b8;display:flex;align-items:center;gap:6px">
                            <span>@t('auth.otp_didnt_receive', "Didn't receive code?")</span>
                            <button type="button" disabled style="background:transparent;border:none;color:#94a3b8;font-weight:600;cursor:not-allowed;padding:0;text-decoration:none;font-size:0.84rem">
                                @t('auth.otp_btn_send_disabled', 'Send me a new code (Disabled)')
                            </button>
                        </div>
                    @endif

                    <div style="font-size:0.8rem;color:#94a3b8">
                        @t('auth.otp_wrong_email', 'Wrong email?') <a href="{{ route('register') }}" style="color:#64748b;text-decoration:underline">@t('auth.otp_register_another', 'Register with another email')</a>
                    </div>
                </div>
                @endif
            @endif

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-digit-box');
    const fullOtpInput = document.getElementById('fullOtpInput');
    const otpForm = document.getElementById('otpForm');
    const isLocked = {{ $isLocked ? 'true' : 'false' }};

    if (!isLocked && inputs.length > 0) {
        // Auto-focus first input
        inputs[0].focus();

        inputs.forEach((input, idx) => {
            input.addEventListener('input', (e) => {
                const val = e.target.value;

                // Enforce numeric only
                if (!/^[0-9]$/.test(val)) {
                    input.value = '';
                    return;
                }

                // Focus next
                if (val && idx < inputs.length - 1) {
                    inputs[idx + 1].focus();
                }

                updateFullOtp();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace') {
                    if (!input.value && idx > 0) {
                        inputs[idx - 1].focus();
                    }
                } else if (e.key === 'ArrowLeft' && idx > 0) {
                    inputs[idx - 1].focus();
                } else if (e.key === 'ArrowRight' && idx < inputs.length - 1) {
                    inputs[idx + 1].focus();
                }
            });

            // Focus styling
            input.addEventListener('focus', () => {
                input.style.borderColor = '#2563eb';
                input.style.boxShadow = '0 0 0 3px rgba(37,99,235,0.15)';
            });
            input.addEventListener('blur', () => {
                input.style.borderColor = '{{ $errors->has('otp') ? '#f87171' : '#cbd5e1' }}';
                input.style.boxShadow = 'none';
            });
        });

        // Paste full code handler
        inputs[0].addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedData = (e.clipboardData || window.clipboardData).getData('text').trim();
            if (/^[0-9]{6}$/.test(pastedData)) {
                inputs.forEach((inp, i) => {
                    inp.value = pastedData[i] || '';
                });
                inputs[inputs.length - 1].focus();
                updateFullOtp();
                // Optionally auto-submit
                setTimeout(() => {
                    if (otpForm) otpForm.submit();
                }, 150);
            }
        });
    }

    function updateFullOtp() {
        let otpStr = '';
        inputs.forEach(inp => {
            otpStr += inp.value;
        });
        if (fullOtpInput) fullOtpInput.value = otpStr;
    }

    if (otpForm) {
        otpForm.addEventListener('submit', (e) => {
            updateFullOtp();
            if (fullOtpInput && fullOtpInput.value.length !== 6) {
                e.preventDefault();
                alert('{{ app(\App\Services\TranslationService::class)->translate('auth.otp_alert_enter_all_digits', 'Please enter all 6 digits of the verification code.') }}');
            }
        });
    }

    // Cooldown Timer
    let cooldown = parseInt({{ (int) $cooldownRemaining }}) || 0;
    const resendBtn = document.getElementById('resendBtn');
    const cooldownLabel = document.getElementById('cooldownLabel');
    const cooldownSeconds = document.getElementById('cooldownSeconds');

    if (cooldown > 0 && resendBtn && cooldownSeconds) {
        const timer = setInterval(() => {
            cooldown--;
            cooldownSeconds.textContent = Math.max(0, cooldown);
            if (cooldown <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
                resendBtn.style.opacity = '1';
                resendBtn.style.cursor = 'pointer';
                if (cooldownLabel) cooldownLabel.style.display = 'none';
            }
        }, 1000);
    }

    @if($isBlocked)
    // Live polling: Check status every 5 seconds. If unblocked/approved by admin, automatically redirect to Dashboard!
    const pollInterval = setInterval(() => {
        fetch('{{ route("otp.check_status") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data && (data.verified || !data.blocked)) {
                clearInterval(pollInterval);
                window.location.href = data.redirect || '{{ route("account.dashboard") }}';
            }
        })
        .catch(() => {});
    }, 5000);
    @endif
});
</script>
@endpush
@endsection
