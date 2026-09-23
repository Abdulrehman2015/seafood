@extends('layouts.app')
@section('title', __t('auth.register_meta_title', 'Create Account — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd')))

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">@t('auth.breadcrumb_home', '🏠 Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('auth.breadcrumb_register', 'Create Account')</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:3px 10px;border-radius:999px;font-size:0.72rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        @t('auth.register_tiers_badge', '⭐ Exclusive Partner Tiers')
                    </span>
                    <span style="color:#bae6fd;font-size:0.8rem">@t('auth.register_tiers_sub', 'Retail · Wholesale · Trading')</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.4rem);margin-bottom:6px;letter-spacing:-0.02em">
                    @t('auth.register_header_title', 'Create Your MST Account')
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:680px;line-height:1.5;margin:0">
                    @t('auth.register_header_subtitle', 'Select your customer category to unlock tailored wholesale pricing and seamless cold-chain delivery.')
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div style="font-size:0.85rem;padding:6px 14px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb">
                    @t('auth.register_instant_access_badge', '⚡ Instant Tier Access')
                </div>
            </div>
        </div>
    </div>
</div>

<div class="register-page-wrapper">
    <div class="register-container">
        <!-- Header -->
        <div class="register-header text-center">
            <h2 class="register-title">@t('auth.register_form_title', 'Account Registration')</h2>
            <p class="register-subtitle">@t('auth.register_form_subtitle', 'Choose your customer category below')</p>
        </div>

        <div class="register-card">
            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf

                <!-- Customer Type Selection (Retail / Wholesale / Trading) -->
                <div class="form-section-block">
                    <label class="form-label font-semibold">@t('auth.customer_type_label', 'Customer Type') <span class="required">*</span></label>
                    <div class="customer-types-grid">
                        @foreach([
                            ['value'=>'retail','label'=>__t('auth.type_retail_label', 'Retail'),'icon'=>'🛒','desc'=>__t('auth.type_retail_desc', 'General public & instant checkout')],
                            ['value'=>'wholesale','label'=>__t('auth.type_wholesale_label', 'Wholesale'),'icon'=>'🏭','desc'=>__t('auth.type_wholesale_desc', 'F&B business & verified tiers')],
                            ['value'=>'trading','label'=>__t('auth.type_trading_label', 'Trading'),'icon'=>'📦','desc'=>__t('auth.type_trading_desc', 'Bulk volume & container RFQ')],
                        ] as $type)
                        <label class="ctype-radio {{ old('customer_group', request('type', 'retail')) == $type['value'] ? 'selected' : '' }}"
                               for="type_{{ $type['value'] }}" id="label_{{ $type['value'] }}">
                            <input type="radio" name="customer_group" id="type_{{ $type['value'] }}"
                                   value="{{ $type['value'] }}"
                                   {{ old('customer_group', request('type', 'retail')) == $type['value'] ? 'checked' : '' }}
                                   onchange="onTypeChange('{{ $type['value'] }}')">
                            <div class="ctype-content">
                                <span class="ctype-icon">{{ $type['icon'] }}</span>
                                <span class="ctype-label">{{ $type['label'] }}</span>
                                <span class="ctype-desc">{{ $type['desc'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('customer_group')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="section-divider">
                    <span>@t('auth.section_personal_details', 'Personal Details')</span>
                </div>

                <!-- Personal Info -->
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="name">@t('auth.field_fullname', 'Full Name') <span class="required">*</span></label>
                        <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name') }}" placeholder="{{ __t('auth.placeholder_fullname', 'e.g. Ahmad bin Ali') }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">@t('auth.field_phone', 'Phone Number') <span class="required">*</span></label>
                        <input type="tel" name="phone" id="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                               value="{{ old('phone') }}" placeholder="+60 12-345 6789" required>
                        @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Email (Strict Uniqueness: One email = One account) -->
                <div class="form-group">
                    <label class="form-label" for="email">
                        @t('auth.field_email', 'Email Address') <span class="required">*</span>
                        <span class="field-hint-tag">@t('auth.email_unique_note', '1 account per email')</span>
                    </label>
                    <div class="input-with-status">
                        <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
                        <span id="emailSpinner" class="field-spinner" style="display:none"></span>
                    </div>
                    <div id="emailFeedback" class="field-live-feedback" style="display:none"></div>
                    @error('email')<div class="form-error" id="emailServerError">{{ $message }}</div>@enderror
                </div>

                <div class="section-divider">
                    <span>@t('auth.section_security', 'Security')</span>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="password">@t('auth.field_password', 'Password') <span class="required">*</span></label>
                        <div class="password-field-wrapper">
                            <input type="password" name="password" id="password" class="form-control password-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="{{ __t('auth.placeholder_min_chars', 'Min. 8 characters') }}" required autocomplete="new-password">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" aria-label="Toggle password visibility" tabindex="-1">
                                <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">@t('auth.field_confirm_password', 'Confirm Password') <span class="required">*</span></label>
                        <div class="password-field-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control password-input"
                                   placeholder="{{ __t('auth.placeholder_repeat_password', 'Repeat password') }}" required autocomplete="new-password">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirmation', this)" aria-label="Toggle password visibility" tabindex="-1">
                                <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Company Fields (Wholesale & Trading Accounts only) -->
                <div id="companyFields" style="{{ in_array(old('customer_group', request('type', 'retail')), ['wholesale','trading']) ? '' : 'display:none' }}">
                    <div class="section-divider">
                        <span>@t('auth.section_company_info', 'Company Information')</span>
                    </div>
                    <div class="register-company-note">
                        <span class="note-icon">🏢</span>
                        <span>@t('auth.company_verification_note', 'Your account will be verified by our team for access to wholesale/trading tier prices.')</span>
                    </div>

                    <div class="form-grid-2">
                        <!-- Company Name with Live Similarity Check (Non-blocking alert) -->
                        <div class="form-group">
                            <label class="form-label" for="company_name">
                                @t('auth.field_company_name', 'Company Name') <span class="required">*</span>
                            </label>
                            <div class="input-with-status">
                                <input type="text" name="company_name" id="company_name" class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}"
                                       value="{{ old('company_name') }}" placeholder="{{ __t('auth.placeholder_company_name', 'e.g. MST Seafood Trading Sdn Bhd') }}">
                                <span id="companySpinner" class="field-spinner" style="display:none"></span>
                            </div>
                            
                            <!-- Non-blocking Company Similarity Warning Box -->
                            <div id="companySimilarityAlert" class="company-warning-box" style="{{ session('company_similarity_warning') ? 'display:flex' : 'display:none' }}">
                                <span class="warning-box-icon">⚠️</span>
                                <div class="warning-box-content">
                                    <div class="warning-box-title" id="companyWarningText">
                                        {{ session('company_similarity_warning') ?? 'This company may already be registered. Please check if your company already has an account or contact MST.' }}
                                    </div>
                                    <div class="warning-box-sub">
                                        @t('auth.company_warning_note', 'You can still proceed with registration if you are a branch, department, or authorized representative.')
                                    </div>
                                </div>
                            </div>
                            @error('company_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <!-- Company Reg No. / SSM No. (Strict Uniqueness Check) -->
                        <div class="form-group">
                            <label class="form-label" for="company_reg_no">
                                @t('auth.field_company_ssm', 'Company Reg. No. (SSM)') <span class="required">*</span>
                                <span class="field-hint-tag">@t('auth.ssm_unique_note', 'Unique ID')</span>
                            </label>
                            <div class="input-with-status">
                                <input type="text" name="company_reg_no" id="company_reg_no" class="form-control {{ $errors->has('company_reg_no') ? 'is-invalid' : '' }}"
                                       value="{{ old('company_reg_no') }}" placeholder="202301012345 (1234567-X)">
                                <span id="ssmSpinner" class="field-spinner" style="display:none"></span>
                            </div>
                            <div id="ssmFeedback" class="field-live-feedback" style="display:none"></div>
                            @error('company_reg_no')<div class="form-error" id="ssmServerError">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="business_type">@t('auth.field_business_nature', 'Business Nature / Type') <span class="required">*</span></label>
                        <div class="custom-select-wrapper">
                            <select name="business_type" id="business_type" class="form-control custom-select {{ $errors->has('business_type') ? 'is-invalid' : '' }}">
                                <option value="">@t('auth.select_business_type', 'Select business type...')</option>
                                <option value="Restaurant & Catering"   {{ old('business_type')=='Restaurant & Catering'?'selected':'' }}>@t('auth.btype_restaurant', 'Restaurant & Catering')</option>
                                <option value="Seafood Retailer"        {{ old('business_type')=='Seafood Retailer'?'selected':'' }}>@t('auth.btype_retailer', 'Seafood Retailer')</option>
                                <option value="Seafood Importer"        {{ old('business_type')=='Seafood Importer'?'selected':'' }}>@t('auth.btype_importer', 'Seafood Importer')</option>
                                <option value="Seafood Exporter"        {{ old('business_type')=='Seafood Exporter'?'selected':'' }}>@t('auth.btype_exporter', 'Seafood Exporter')</option>
                                <option value="Food Manufacturer"       {{ old('business_type')=='Food Manufacturer'?'selected':'' }}>@t('auth.btype_manufacturer', 'Food Manufacturer')</option>
                                <option value="Hotel / Resort"          {{ old('business_type')=='Hotel / Resort'?'selected':'' }}>@t('auth.btype_hotel', 'Hotel / Resort')</option>
                                <option value="Other"                   {{ old('business_type')=='Other'?'selected':'' }}>@t('auth.btype_other', 'Other')</option>
                            </select>
                        </div>
                        @error('business_type')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Address Section -->
                <div class="section-divider">
                    <span>@t('auth.section_delivery_address', 'Delivery Address')</span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="address">@t('auth.field_street_address', 'Street Address') <span class="required">*</span></label>
                    <input type="text" name="address" id="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ old('address') }}" placeholder="{{ __t('auth.placeholder_street_address', 'Unit / Street address, Taman / Area') }}" required>
                    @error('address')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <!-- Responsive Address Grid -->
                <div class="register-address-grid">
                    <div class="form-group mb-0 grid-state-col">
                        <label class="form-label" for="state">@t('auth.field_state', 'State') <span class="required">*</span></label>
                        <input type="text" name="state" id="state" class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" value="{{ old('state') }}" placeholder="{{ __t('auth.placeholder_state', 'e.g. Johor') }}" required>
                        @error('state')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-0 grid-city-col">
                        <label class="form-label" for="city">@t('auth.field_city', 'City') <span class="required">*</span></label>
                        <input type="text" name="city" id="city" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" value="{{ old('city') }}" placeholder="{{ __t('auth.placeholder_city', 'e.g. Iskandar Puteri') }}" required>
                        @error('city')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-0 grid-postcode-col">
                        <label class="form-label" for="postcode">@t('auth.field_postcode', 'Postcode') <span class="required">*</span></label>
                        <input type="text" name="postcode" id="postcode" class="form-control {{ $errors->has('postcode') ? 'is-invalid' : '' }}" value="{{ old('postcode') }}" placeholder="79200" maxlength="5" pattern="[0-9]*" inputmode="numeric" required>
                        @error('postcode')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                <!-- Consent Checkboxes (Separated Terms and Optional Marketing) -->
                <div class="consent-block" style="margin: 20px 0; display: flex; flex-direction: column; gap: 12px; background: #f8fafc; padding: 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                    <!-- 1. Mandatory Terms & Privacy Policy Consent -->
                    <label class="consent-item" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 0.88rem; color: #334155;">
                        <input type="checkbox" name="terms_consent" value="1" required style="margin-top: 3px; width: 18px; height: 18px; accent-color: #2563eb; cursor: pointer;" {{ old('terms_consent') ? 'checked' : '' }}>
                        <span>
                            @t('auth.i_agree_to', 'I agree to the') 
                            <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => 'terms-and-conditions']) }}" target="_blank" style="color:#2563eb;text-decoration:underline">@t('nav.terms_and_conditions', 'Terms & Conditions')</a> 
                            @t('common.and', 'and') 
                            <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => 'privacy-policy']) }}" target="_blank" style="color:#2563eb;text-decoration:underline">@t('nav.privacy_policy', 'Privacy Policy')</a>. 
                            <span class="required" style="color:#ef4444">*</span>
                        </span>
                    </label>
                    @error('terms_consent')<div class="form-error" style="margin-top:-6px">{{ $message }}</div>@enderror

                    <!-- 2. Optional Separate Marketing Opt-in -->
                    <label class="consent-item" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 0.88rem; color: #334155;">
                        <input type="checkbox" name="marketing_opt_in" value="1" style="margin-top: 3px; width: 18px; height: 18px; accent-color: #2563eb; cursor: pointer;" {{ old('marketing_opt_in', true) ? 'checked' : '' }}>
                        <span>
                            <strong>@t('auth.marketing_opt_in_title', '🎁 Exclusive Offers & Updates (Optional)')</strong><br>
                            <span style="color: #64748b; font-size: 0.82rem;">
                                @t('auth.marketing_opt_in_desc', 'Yes, send me MST product updates, seasonal catch arrivals, promotions and special wholesale offers via WhatsApp & Email.')
                            </span>
                        </span>
                    </label>
                </div>

                <x-recaptcha context="register" />

                <div class="submit-section">
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-lg btn-block register-submit-btn">
                        <span>@t('auth.btn_create_account', 'Create Account')</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:8px">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>

                <div class="register-footer-links text-center">
                    <p class="text-sm text-muted">
                        @t('auth.already_have_account', 'Already have an account?') <a href="{{ route('login') }}" class="signin-link">@t('auth.signin_link', 'Sign In')</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ─── Registration Page Scoped Styles ─────────────────────────────────────── */
.register-page-wrapper {
    min-height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px 16px 48px 16px;
    background: #f8fafc;
    box-sizing: border-box;
}

.register-container {
    width: 100%;
    max-width: 640px;
    margin: 0 auto;
    box-sizing: border-box;
}

.register-header {
    margin-bottom: 14px;
}

.register-title {
    font-family: var(--font-heading);
    font-size: 1.6rem;
    font-weight: 800;
    color: #0f274a;
    margin: 0 0 4px;
    letter-spacing: -0.02em;
}

.register-subtitle {
    font-size: 0.88rem;
    color: var(--text-muted);
    max-width: 440px;
    margin: 0 auto;
    line-height: 1.4;
}

/* Card Styling (matching login card elevation) */
.register-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 34px 28px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.06), 0 8px 10px -6px rgba(0,0,0,0.04);
    box-sizing: border-box;
    width: 100%;
}

/* Customer Type Radio Cards (3 Grid Columns) */
.customer-types-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-top: var(--space-2);
}

.ctype-radio {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 16px 10px;
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    user-select: none;
    box-sizing: border-box;
    height: 100%;
}

.ctype-radio input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.ctype-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    width: 100%;
}

.ctype-icon {
    font-size: 1.6rem;
    line-height: 1;
    margin-bottom: 6px;
    display: block;
    transition: transform 0.2s ease;
}

.ctype-label {
    font-weight: 700;
    font-size: 0.92rem;
    color: var(--text-primary);
    line-height: 1.2;
    display: block;
}

.ctype-desc {
    font-size: 0.72rem;
    color: var(--text-muted);
    margin-top: 3px;
    line-height: 1.25;
    display: block;
}

.ctype-radio:hover {
    border-color: #5eead4;
    background: #f0fdfa;
    transform: translateY(-1px);
}

.ctype-radio:hover .ctype-icon {
    transform: scale(1.1);
}

.ctype-radio.selected {
    border-color: var(--seagreen-600);
    background: #f0fdfa;
    box-shadow: 0 4px 14px rgba(13, 148, 136, 0.12);
}

.ctype-radio.selected .ctype-label {
    color: var(--seagreen-700);
}

/* Section Dividers */
.section-divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 24px 0 18px 0;
}

.section-divider::before,
.section-divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #e2e8f0;
}

.section-divider span {
    padding: 0 12px;
    font-size: 0.76rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94a3b8;
}

/* Company Notice */
.register-company-note {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: var(--radius-md);
    font-size: 0.84rem;
    color: #1e40af;
    line-height: 1.4;
    margin-bottom: var(--space-4);
}

.note-icon {
    font-size: 1.2rem;
    flex-shrink: 0;
}

/* Form Controls */
.form-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.86rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}

.form-label .required {
    color: #ef4444;
}

.field-hint-tag {
    font-size: 0.70rem;
    font-weight: 600;
    color: #0284c7;
    background: #e0f2fe;
    padding: 1px 6px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.input-with-status {
    position: relative;
    width: 100%;
}

.form-control {
    width: 100%;
    height: 44px;
    padding: 9px 14px;
    font-size: 0.92rem;
    color: var(--text-primary);
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    transition: all 0.2s ease;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: var(--seagreen-500);
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
    background: #ffffff;
}

.form-control::placeholder {
    color: #94a3b8;
    font-size: 0.88rem;
}

.form-control.is-invalid {
    border-color: #ef4444 !important;
    background: #fef2f2 !important;
}

.form-control.is-valid {
    border-color: #10b981;
}

/* Field Spinner */
.field-spinner {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    border: 2px solid #cbd5e1;
    border-top-color: var(--seagreen-600);
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
    pointer-events: none;
}

@keyframes spin {
    to { transform: translateY(-50%) rotate(360deg); }
}

/* Live Field Feedback */
.field-live-feedback {
    font-size: 0.82rem;
    line-height: 1.35;
    margin-top: 5px;
    padding: 4px 8px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.field-live-feedback.error {
    color: #b91c1c;
    background: #fee2e2;
    border: 1px solid #fca5a5;
}

.field-live-feedback.success {
    color: #065f46;
    background: #d1fae5;
    border: 1px solid #a7f3d0;
}

/* Non-blocking Company Similarity Warning Box */
.company-warning-box {
    margin-top: 8px;
    padding: 10px 12px;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border: 1.5px solid #f59e0b;
    border-radius: 10px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    box-shadow: 0 4px 12px -2px rgba(245, 158, 11, 0.12);
    animation: fadeInSlide 0.25s ease-out;
}

@keyframes fadeInSlide {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}

.warning-box-icon {
    font-size: 1.25rem;
    line-height: 1.2;
    flex-shrink: 0;
}

.warning-box-content {
    flex: 1;
}

.warning-box-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: #92400e;
    line-height: 1.35;
    margin-bottom: 2px;
}

.warning-box-sub {
    font-size: 0.76rem;
    color: #b45309;
    line-height: 1.3;
}

/* Password Toggle */
.password-field-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.password-input {
    padding-right: 44px !important;
}

.password-toggle-btn {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    padding: 6px 8px;
    cursor: pointer;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: color 0.15s ease, background-color 0.15s ease;
}

.password-toggle-btn:hover {
    color: #334155;
    background: #f1f5f9;
}

/* Select */
.custom-select-wrapper {
    position: relative;
    width: 100%;
}

.custom-select {
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 16px;
    padding-right: 36px;
    cursor: pointer;
}

/* Address Grid */
.register-address-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 12px;
}

/* Submit Button */
.submit-section {
    margin-top: 28px;
}

.register-submit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 48px;
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    background: linear-gradient(135deg, #1d4ed8 0%, #0c234b 100%);
    border: none;
    border-radius: 12px;
    box-shadow: 0 6px 18px -2px rgba(29, 78, 216, 0.35);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    color: #ffffff;
    cursor: pointer;
    width: 100%;
}

.register-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px -4px rgba(29, 78, 216, 0.45);
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
}

.register-submit-btn:active {
    transform: translateY(0);
}

.register-footer-links {
    margin-top: 20px;
}

.signin-link {
    color: var(--seagreen-600);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.15s ease;
}

.signin-link:hover {
    color: var(--seagreen-800);
    text-decoration: underline;
}

/* ─── Mobile Responsiveness ────────────────────────────────────────────────── */
@media (max-width: 640px) {
    .register-page-wrapper {
        padding: 16px 12px 36px 12px;
        align-items: flex-start;
    }

    .register-header {
        margin-bottom: 10px;
    }

    .register-card {
        padding: 20px 14px;
        border-radius: 16px;
        box-shadow: 0 8px 24px -4px rgba(15, 23, 42, 0.06);
    }

    .register-title {
        font-size: 1.35rem;
    }

    .register-subtitle {
        font-size: 0.82rem;
    }

    /* Customer types 3-col on mobile */
    .customer-types-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
    }

    .ctype-radio {
        padding: 10px 4px;
        border-radius: 10px;
    }

    .ctype-icon {
        font-size: 1.3rem;
        margin-bottom: 4px;
    }

    .ctype-label {
        font-size: 0.8rem;
    }

    .ctype-desc {
        font-size: 0.65rem;
        margin-top: 2px;
        line-height: 1.2;
    }

    .form-control {
        font-size: 16px !important;
        height: 46px;
    }

    .register-address-grid {
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .register-address-grid .grid-state-col {
        grid-column: 1 / -1;
    }

    .register-address-grid .grid-city-col {
        grid-column: 1 / 2;
    }

    .register-address-grid .grid-postcode-col {
        grid-column: 2 / 3;
    }

    .section-divider {
        margin: 20px 0 14px 0;
    }
}
</style>
@endpush

@push('scripts')
<script>
function onTypeChange(type) {
    const companyFields = ['wholesale', 'trading'];
    const el = document.getElementById('companyFields');
    if (el) {
        el.style.display = companyFields.includes(type) ? 'block' : 'none';
    }

    document.querySelectorAll('.ctype-radio').forEach(l => l.classList.remove('selected'));
    const selectedLabel = document.getElementById('label_' + type);
    if (selectedLabel) {
        selectedLabel.classList.add('selected');
    }
}

function togglePasswordVisibility(fieldId, btn) {
    const input = document.getElementById(fieldId);
    if (!input) return;

    const isCurrentlyPassword = input.type === 'password';
    input.type = isCurrentlyPassword ? 'text' : 'password';

    if (isCurrentlyPassword) {
        btn.innerHTML = `
            <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            </svg>
        `;
        btn.setAttribute('aria-label', 'Hide password');
    } else {
        btn.innerHTML = `
            <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        `;
        btn.setAttribute('aria-label', 'Show password');
    }
}

// ─── Debounced Live Field Verification & Translations ─────────────────────────
const regI18n = {
    emailTaken: @json(__t('auth.email_already_registered', 'This email address is already registered. One email can only register one account.')),
    emailAvailable: @json(__t('auth.email_available', 'Email address is available')),
    companyWarning: @json(__t('auth.company_similarity_warning', 'This company may already be registered. Please check if your company already has an account or contact MST.')),
    ssmDuplicate: @json(__t('auth.ssm_duplicate_error', 'An account with this Company Registration Number (SSM) is already registered.')),
    ssmAvailable: @json(__t('auth.ssm_available', 'SSM Number is available'))
};

function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

async function verifyField(fieldName, val, spinnerId, callback) {
    const spinner = document.getElementById(spinnerId);
    if (spinner) spinner.style.display = 'inline-block';

    try {
        const response = await fetch(`{{ route('register.verify_field') }}?field=${encodeURIComponent(fieldName)}&value=${encodeURIComponent(val)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        callback(data);
    } catch (e) {
        console.error('Field verification error:', e);
    } finally {
        if (spinner) spinner.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('emailFeedback');
    const emailServerError = document.getElementById('emailServerError');

    const companyInput = document.getElementById('company_name');
    const companyAlert = document.getElementById('companySimilarityAlert');
    const companyWarningText = document.getElementById('companyWarningText');

    const ssmInput = document.getElementById('company_reg_no');
    const ssmFeedback = document.getElementById('ssmFeedback');
    const ssmServerError = document.getElementById('ssmServerError');

    // 1. Live Email Check
    if (emailInput) {
        const checkEmail = debounce(function () {
            const val = emailInput.value.trim();
            if (!val || !val.includes('@') || val.length < 5) {
                if (emailFeedback) emailFeedback.style.display = 'none';
                emailInput.classList.remove('is-invalid', 'is-valid');
                return;
            }

            verifyField('email', val, 'emailSpinner', function (res) {
                if (emailServerError) emailServerError.style.display = 'none';

                if (res.is_taken) {
                    emailInput.classList.add('is-invalid');
                    emailInput.classList.remove('is-valid');
                    if (emailFeedback) {
                        emailFeedback.className = 'field-live-feedback error';
                        emailFeedback.innerHTML = `⚠️ ${res.message || regI18n.emailTaken}`;
                        emailFeedback.style.display = 'flex';
                    }
                } else {
                    emailInput.classList.remove('is-invalid');
                    emailInput.classList.add('is-valid');
                    if (emailFeedback) {
                        emailFeedback.className = 'field-live-feedback success';
                        emailFeedback.innerHTML = `✓ ${regI18n.emailAvailable}`;
                        emailFeedback.style.display = 'flex';
                    }
                }
            });
        }, 400);

        emailInput.addEventListener('input', checkEmail);
        emailInput.addEventListener('blur', checkEmail);
    }

    // 2. Live Company Name Similarity Check (Non-blocking warning)
    if (companyInput) {
        const checkCompany = debounce(function () {
            const val = companyInput.value.trim();
            if (!val || val.length < 3) {
                if (companyAlert) companyAlert.style.display = 'none';
                return;
            }

            verifyField('company_name', val, 'companySpinner', function (res) {
                if (res.has_warning) {
                    if (companyWarningText) {
                        companyWarningText.textContent = res.message || regI18n.companyWarning;
                    }
                    if (companyAlert) {
                        companyAlert.style.display = 'flex';
                    }
                } else {
                    if (companyAlert) {
                        companyAlert.style.display = 'none';
                    }
                }
            });
        }, 450);

        companyInput.addEventListener('input', checkCompany);
        companyInput.addEventListener('blur', checkCompany);
    }

    // 3. Live SSM Number Uniqueness Check (Strict Blocking warning/error)
    if (ssmInput) {
        const checkSsm = debounce(function () {
            const val = ssmInput.value.trim();
            if (!val || val.length < 3) {
                if (ssmFeedback) ssmFeedback.style.display = 'none';
                ssmInput.classList.remove('is-invalid', 'is-valid');
                return;
            }

            verifyField('company_reg_no', val, 'ssmSpinner', function (res) {
                if (ssmServerError) ssmServerError.style.display = 'none';

                if (res.is_duplicate) {
                    ssmInput.classList.add('is-invalid');
                    ssmInput.classList.remove('is-valid');
                    if (ssmFeedback) {
                        ssmFeedback.className = 'field-live-feedback error';
                        ssmFeedback.innerHTML = `⚠️ ${res.message || regI18n.ssmDuplicate}`;
                        ssmFeedback.style.display = 'flex';
                    }
                } else {
                    ssmInput.classList.remove('is-invalid');
                    ssmInput.classList.add('is-valid');
                    if (ssmFeedback) {
                        ssmFeedback.className = 'field-live-feedback success';
                        ssmFeedback.innerHTML = `✓ ${regI18n.ssmAvailable}`;
                        ssmFeedback.style.display = 'flex';
                    }
                }
            });
        }, 400);

        ssmInput.addEventListener('input', checkSsm);
        ssmInput.addEventListener('blur', checkSsm);
    }
});
</script>
@endpush

