@extends('layouts.app')
@section('title', __t('auth.login_meta_title', 'Sign In — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd')))

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">@t('auth.breadcrumb_home', '🏠 Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('auth.breadcrumb_signin', 'Sign In')</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:3px 10px;border-radius:999px;font-size:0.72rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        @t('auth.login_portal_badge', '🔐 Secure Customer Portal')
                    </span>
                    <span style="color:#bae6fd;font-size:0.8rem">@t('auth.login_portal_tag', 'Authorized Access & Tier Pricing')</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.4rem);margin-bottom:6px;letter-spacing:-0.02em">
                    @t('auth.login_header_title', 'Sign In to Your Account')
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:680px;line-height:1.5;margin:0">
                    @t('auth.login_header_subtitle', 'Sign in to access your custom pricing tier, manage order history, and track refrigerated deliveries.')
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div style="font-size:0.85rem;padding:6px 14px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb">
                    @t('auth.login_cold_chain_badge', '❄️ Cold-Chain Portal')
                </div>
            </div>
        </div>
    </div>
</div>

<div class="login-page-wrapper" style="min-height:calc(100vh - 360px);display:flex;align-items:center;justify-content:center;padding:40px 16px 60px 16px;background:#f8fafc">
    <div style="width:100%;max-width:460px;margin:0 auto">
        <div class="card" style="box-shadow:0 10px 25px -5px rgba(0,0,0,0.06), 0 8px 10px -6px rgba(0,0,0,0.04);border:1px solid #e2e8f0;border-radius:18px;padding:32px 28px;background:#ffffff">
            <div class="text-center" style="margin-bottom:18px">
                <h2 style="font-family:var(--font-heading);font-size:1.45rem;font-weight:800;color:#0f274a;margin:0 0 6px">@t('auth.login_card_title', 'Welcome Back')</h2>
                <p style="color:#64748b;font-size:0.88rem;margin:0">@t('auth.login_card_subtitle', 'Enter your credentials to access your account')</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success mb-4">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label" for="email" style="font-weight:600;color:#334155;margin-bottom:6px;display:block">@t('auth.field_email', 'Email Address')</label>
                    <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}" placeholder="you@example.com" required autofocus style="border-radius:10px;padding:10px 14px">
                    @error('email')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:18px">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                        <label class="form-label mb-0" for="password" style="font-weight:600;color:#334155">@t('auth.field_password', 'Password')</label>
                        <a href="{{ route('password.request') }}" style="color:#2563eb;font-size:0.82rem;font-weight:600;text-decoration:none">@t('auth.forgot_password', 'Forgot password?')</a>
                    </div>
                    <input type="password" name="password" id="password" class="form-control" placeholder="{{ __t('auth.placeholder_password', 'Your password') }}" required style="border-radius:10px;padding:10px 14px">
                </div>

                <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px">
                    <input type="checkbox" name="remember" id="remember" style="accent-color:#2563eb;width:16px;height:16px;cursor:pointer">
                    <label for="remember" style="color:#64748b;font-size:0.85rem;cursor:pointer;margin:0">@t('auth.remember_me', 'Remember me for 30 days')</label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block" style="background:linear-gradient(135deg, #1d4ed8, #0f274a);border:none;font-weight:700;border-radius:10px;padding:12px">@t('auth.btn_signin', 'Sign In')</button>
            </form>

            <div class="divider" style="margin:22px 0;border-top:1px solid #e2e8f0"></div>

            <p class="text-center" style="font-size:0.88rem;color:#64748b;margin:0">
                @t('auth.no_account', "Don't have an account?") <a href="{{ route('register') }}" style="color:#2563eb;font-weight:700;text-decoration:none">@t('auth.register_here', 'Register here')</a>
            </p>
        </div>
    </div>
</div>
@endsection
