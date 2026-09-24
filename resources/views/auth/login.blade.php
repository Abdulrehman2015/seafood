@extends('layouts.app')
@section('title', __t('auth.login_meta_title', 'Sign In — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd')))

@section('content')
<!-- Page Header / Hero Section -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">@t('auth.breadcrumb_home', '🏠 Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('auth.breadcrumb_signin', 'Sign In')</span>
        </div>
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap">
                <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:3px 10px;border-radius:999px;font-size:0.75rem;font-weight:700;color:#7dd3fc;letter-spacing:0.03em">
                    @t('auth.login_badge', '🔐 Customer Account')
                </span>
                <span style="color:#bae6fd;font-size:0.84rem">
                    @t('auth.login_hero_sub', 'Sign in to manage your MST account, orders and business access.')
                </span>
            </div>
            <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.35rem);margin-bottom:6px;letter-spacing:-0.02em">
                @t('auth.login_header_title', 'Sign In to Your Account')
            </h1>
            <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:720px;line-height:1.55;margin:0">
                @t('auth.login_header_subtitle', 'Sign in to manage your account, view your orders and access features available to your customer account.')
            </p>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="login-page-wrapper" style="min-height:calc(100vh - 360px);padding:44px 16px 72px 16px;background:#f8fafc">
    <div style="width:100%;max-width:480px;margin:0 auto;display:flex;flex-direction:column;gap:20px">
        
        <!-- Primary Login Card -->
        <div class="card" style="box-shadow:0 12px 30px -6px rgba(0,0,0,0.07), 0 6px 12px -4px rgba(0,0,0,0.03);border:1px solid #e2e8f0;border-radius:20px;padding:34px 28px;background:#ffffff">
            <div class="text-center" style="margin-bottom:22px">
                <h2 style="font-family:var(--font-heading);font-size:1.45rem;font-weight:800;color:#0f274a;margin:0 0 6px">
                    @t('auth.login_card_title', 'Welcome Back')
                </h2>
                <p style="color:#64748b;font-size:0.88rem;margin:0">
                    @t('auth.login_card_subtitle', 'Enter your email and password to continue.')
                </p>
            </div>

            @if(session('status'))
                <div class="alert alert-success mb-4" style="border-radius:10px;font-size:0.88rem">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label" for="email" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;font-size:0.88rem">
                        @t('auth.field_email', 'Email Address') <span style="color:#ef4444">*</span>
                    </label>
                    <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}" placeholder="you@example.com" required autofocus 
                           style="border-radius:10px;padding:11px 14px;border:1.5px solid #cbd5e1;font-size:0.92rem;width:100%;box-sizing:border-box">
                    @error('email')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:18px">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                        <label class="form-label mb-0" for="password" style="font-weight:600;color:#334155;font-size:0.88rem">
                            @t('auth.field_password', 'Password') <span style="color:#ef4444">*</span>
                        </label>
                        <a href="{{ route('password.request') }}" style="color:#2563eb;font-size:0.82rem;font-weight:600;text-decoration:none">
                            @t('auth.forgot_password', 'Forgot password?')
                        </a>
                    </div>
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="{{ __t('auth.placeholder_password', 'Your password') }}" required 
                           style="border-radius:10px;padding:11px 14px;border:1.5px solid #cbd5e1;font-size:0.92rem;width:100%;box-sizing:border-box">
                </div>

                <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px">
                    <input type="checkbox" name="remember" id="remember" style="accent-color:#2563eb;width:16px;height:16px;cursor:pointer">
                    <label for="remember" style="color:#64748b;font-size:0.85rem;cursor:pointer;margin:0;user-select:none">
                        @t('auth.remember_me', 'Remember me for 30 days')
                    </label>
                </div>

                <!-- Google reCAPTCHA Protection -->
                <x-recaptcha context="login" align="center" />

                <button type="submit" class="btn btn-primary btn-lg btn-block" style="background:linear-gradient(135deg, #1d4ed8 0%, #0f274a 100%);border:none;font-weight:700;border-radius:10px;padding:12px;width:100%;font-size:0.95rem;cursor:pointer;box-shadow:0 4px 12px rgba(29,78,216,0.25);transition:transform 0.15s ease;">
                    @t('auth.btn_signin', 'Sign In')
                </button>
            </form>

            <div class="divider" style="margin:22px 0;border-top:1px solid #e2e8f0"></div>

            <p class="text-center" style="font-size:0.88rem;color:#64748b;margin:0">
                @t('auth.no_account', "Don't have an account?") 
                <a href="{{ route('register') }}" style="color:#2563eb;font-weight:700;text-decoration:none">
                    @t('auth.register_here', 'Register here.')
                </a>
            </p>
        </div>

        <!-- Section 12: Business Access Card -->
        <div class="card" style="box-shadow:0 6px 18px -4px rgba(0,0,0,0.04);border:1.5px solid #bfdbfe;border-radius:18px;padding:24px 24px;background:linear-gradient(180deg, #eff6ff 0%, #f8fafc 100%)">
            <div style="display:flex;align-items:flex-start;gap:14px">
                <div style="width:40px;height:40px;border-radius:10px;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0">
                    🏢
                </div>
                <div style="flex:1">
                    <h2 style="font-family:var(--font-heading);font-size:1.12rem;font-weight:800;color:#0f274a;margin:0 0 6px;line-height:1.3">
                        @t('auth.biz_access_title', 'Need Wholesale or Trading Access?')
                    </h2>
                    <p style="color:#475569;font-size:0.84rem;line-height:1.5;margin:0 0 14px">
                        @t('auth.biz_access_desc', 'If you are purchasing for a restaurant, retailer, distributor, trader or other business, you can register for a business account and submit your business information for verification.')
                    </p>
                    <a href="{{ route('register', ['type' => 'wholesale']) }}" 
                       style="display:inline-flex;align-items:center;justify-content:center;gap:6px;background:#1d4ed8;color:#ffffff;font-weight:700;font-size:0.84rem;padding:9px 16px;border-radius:9px;text-decoration:none;transition:background 0.15s ease;box-shadow:0 2px 6px rgba(29,78,216,0.25);">
                        <span>@t('auth.btn_request_biz_access', 'Request Business Access →')</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Section 13: Walk-in / Retail Card -->
        <div class="card" style="box-shadow:0 6px 18px -4px rgba(0,0,0,0.04);border:1px solid #e2e8f0;border-radius:18px;padding:22px 24px;background:#ffffff">
            <div style="display:flex;align-items:flex-start;gap:14px">
                <div style="width:40px;height:40px;border-radius:10px;background:#f1f5f9;color:#0f172a;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0">
                    🛍️
                </div>
                <div style="flex:1">
                    <h3 style="font-family:var(--font-heading);font-size:1.05rem;font-weight:800;color:#0f274a;margin:0 0 4px;line-height:1.3">
                        @t('auth.walkin_access_title', 'Shopping for Walk-in / Retail?')
                    </h3>
                    <p style="color:#64748b;font-size:0.84rem;line-height:1.45;margin:0 0 12px">
                        @t('auth.walkin_access_desc', 'You can browse our Walk-in Menu without creating an account.')
                    </p>
                    <a href="{{ route('walkin.shop') }}" 
                       style="display:inline-flex;align-items:center;justify-content:center;gap:6px;background:#f8fafc;color:#0f274a;border:1.5px solid #cbd5e1;font-weight:700;font-size:0.84rem;padding:8px 16px;border-radius:9px;text-decoration:none;transition:all 0.15s ease;">
                        <span>@t('auth.btn_browse_walkin', 'Browse Walk-in Menu →')</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Section 14: Business Account Information Notice -->
        <div style="background:rgba(241,245,249,0.7);border:1px dashed #cbd5e1;border-radius:14px;padding:16px 20px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                <span style="font-size:0.95rem">ℹ️</span>
                <h3 style="font-family:var(--font-heading);font-size:0.86rem;font-weight:700;color:#334155;margin:0;letter-spacing:0.01em">
                    @t('auth.biz_info_title', 'Business Account Information')
                </h3>
            </div>
            <p style="color:#64748b;font-size:0.79rem;line-height:1.5;margin:0">
                @t('auth.biz_info_desc', 'Business accounts may receive access to business-specific pricing, product availability and quotation features according to account status and order requirements.')
            </p>
        </div>

    </div>
</div>
@endsection
