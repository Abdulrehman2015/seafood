@extends('layouts.app')
@section('title', 'Create Account — MST Import and Export Sdn Bhd')

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 Home</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">Create Account</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:3px 10px;border-radius:999px;font-size:0.72rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        ⭐ Exclusive Partner Tiers
                    </span>
                    <span style="color:#bae6fd;font-size:0.8rem">Retail · Wholesale · Trading</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.4rem);margin-bottom:6px;letter-spacing:-0.02em">
                    Create Your MST Account
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:680px;line-height:1.5;margin:0">
                    Select your customer category to unlock tailored wholesale pricing and seamless cold-chain delivery.
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div style="font-size:0.85rem;padding:6px 14px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb">
                    ⚡ Instant Tier Access
                </div>
            </div>
        </div>
    </div>
</div>

<div class="register-page-wrapper">
    <div class="register-container">
        <!-- Header -->
        <div class="register-header text-center">
            <div class="brand-badge">
                <span style="font-size:1.75rem">🌊</span>
            </div>
            <h2 class="register-title" style="font-size:1.5rem">Account Registration</h2>
            <p class="register-subtitle">Choose your customer category below</p>
        </div>

        <div class="register-card">
            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf

                <!-- Customer Type Selection -->
                <div class="form-section-block">
                    <label class="form-label font-semibold">Customer Type <span class="required">*</span></label>
                    <div class="customer-types-grid">
                        @foreach([
                            ['value'=>'retail','label'=>'Retail','icon'=>'🛒','desc'=>'General public, instant access'],
                            ['value'=>'wholesale','label'=>'Wholesale','icon'=>'🏭','desc'=>'Business & verified discounts'],
                            ['value'=>'trading','label'=>'Trading','icon'=>'📦','desc'=>'Bulk orders & RFQ pricing'],
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
                    <span>Personal Details</span>
                </div>

                <!-- Personal Info -->
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name <span class="required">*</span></label>
                        <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name') }}" placeholder="e.g. Ahmad bin Ali" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" name="phone" id="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                               value="{{ old('phone') }}" placeholder="+60 12-345 6789" required>
                        @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address <span class="required">*</span></label>
                    <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}" placeholder="you@example.com" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="section-divider">
                    <span>Security</span>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="password">Password <span class="required">*</span></label>
                        <div class="password-field-wrapper">
                            <input type="password" name="password" id="password" class="form-control password-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="Min. 8 characters" required autocomplete="new-password">
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
                        <label class="form-label" for="password_confirmation">Confirm Password <span class="required">*</span></label>
                        <div class="password-field-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control password-input"
                                   placeholder="Repeat password" required autocomplete="new-password">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirmation', this)" aria-label="Toggle password visibility" tabindex="-1">
                                <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Company Fields (wholesale/trading only) -->
                <div id="companyFields" style="{{ in_array(old('customer_group', request('type', 'retail')), ['wholesale','trading']) ? '' : 'display:none' }}">
                    <div class="section-divider">
                        <span>Company Information</span>
                    </div>
                    <div class="register-company-note">
                        <span class="note-icon">🏢</span>
                        <span>Your account will be verified by our team for access to wholesale/trading tier prices.</span>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="company_name">Company Name <span class="required">*</span></label>
                            <input type="text" name="company_name" id="company_name" class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}"
                                   value="{{ old('company_name') }}" placeholder="e.g. MST Seafood Trading Sdn Bhd">
                            @error('company_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="company_reg_no">Company Reg. No. (SSM) <span class="required">*</span></label>
                            <input type="text" name="company_reg_no" id="company_reg_no" class="form-control {{ $errors->has('company_reg_no') ? 'is-invalid' : '' }}"
                                   value="{{ old('company_reg_no') }}" placeholder="202301012345 (1234567-X)">
                            @error('company_reg_no')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="business_type">Business Nature / Type <span class="required">*</span></label>
                        <div class="custom-select-wrapper">
                            <select name="business_type" id="business_type" class="form-control custom-select {{ $errors->has('business_type') ? 'is-invalid' : '' }}">
                                <option value="">Select business type...</option>
                                <option value="Restaurant & Catering"   {{ old('business_type')=='Restaurant & Catering'?'selected':'' }}>Restaurant & Catering</option>
                                <option value="Seafood Retailer"        {{ old('business_type')=='Seafood Retailer'?'selected':'' }}>Seafood Retailer</option>
                                <option value="Seafood Importer"        {{ old('business_type')=='Seafood Importer'?'selected':'' }}>Seafood Importer</option>
                                <option value="Seafood Exporter"        {{ old('business_type')=='Seafood Exporter'?'selected':'' }}>Seafood Exporter</option>
                                <option value="Food Manufacturer"       {{ old('business_type')=='Food Manufacturer'?'selected':'' }}>Food Manufacturer</option>
                                <option value="Hotel / Resort"          {{ old('business_type')=='Hotel / Resort'?'selected':'' }}>Hotel / Resort</option>
                                <option value="Other"                   {{ old('business_type')=='Other'?'selected':'' }}>Other</option>
                            </select>
                        </div>
                        @error('business_type')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Address Section -->
                <div class="section-divider">
                    <span>Delivery Address</span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="address">Street Address <span class="required">*</span></label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" placeholder="Unit / Street address, Taman / Area" required>
                    @error('address')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <!-- Responsive Address Grid: 3 cols on desktop, state full-width + city/postcode 2-col on mobile -->
                <div class="register-address-grid">
                    <div class="form-group mb-0 grid-state-col">
                        <label class="form-label" for="state">State <span class="required">*</span></label>
                        <input type="text" name="state" id="state" class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" value="{{ old('state') }}" placeholder="e.g. Selangor" required>
                        @error('state')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-0 grid-city-col">
                        <label class="form-label" for="city">City <span class="required">*</span></label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}" placeholder="e.g. Petaling Jaya" required>
                        @error('city')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-0 grid-postcode-col">
                        <label class="form-label" for="postcode">Postcode <span class="required">*</span></label>
                        <input type="text" name="postcode" id="postcode" class="form-control" value="{{ old('postcode') }}" placeholder="47301" maxlength="5" pattern="[0-9]*" inputmode="numeric" required>
                        @error('postcode')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="submit-section">
                    <button type="submit" class="btn btn-primary btn-lg btn-block register-submit-btn">
                        <span>Create Account</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:8px">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>

                <div class="register-footer-links text-center">
                    <p class="text-sm text-muted">
                        Already have an account? <a href="{{ route('login') }}" class="signin-link">Sign In</a>
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
    padding: var(--space-8) 16px var(--space-16) 16px;
    background: radial-gradient(circle at top, rgba(240, 253, 250, 0.9) 0%, #f8fafc 70%, #eff6ff 100%);
    box-sizing: border-box;
}

.register-container {
    width: 100%;
    max-width: 620px;
    margin: 0 auto;
    box-sizing: border-box;
}

.register-header {
    margin-bottom: var(--space-6);
}

.brand-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 58px;
    height: 58px;
    background: #ffffff;
    border: 1px solid rgba(13, 148, 136, 0.2);
    border-radius: 50%;
    box-shadow: 0 8px 16px -4px rgba(13, 148, 136, 0.15);
    margin-bottom: var(--space-3);
}

.register-title {
    font-family: var(--font-heading);
    font-size: 1.85rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 var(--space-2);
    letter-spacing: -0.02em;
}

.register-subtitle {
    font-size: 0.92rem;
    color: var(--text-muted);
    max-width: 440px;
    margin: 0 auto;
    line-height: 1.45;
}

/* Card Styling */
.register-card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.95);
    border-radius: 20px;
    padding: 34px 30px;
    box-shadow: 0 16px 36px -8px rgba(15, 23, 42, 0.07), 0 2px 6px rgba(0, 0, 0, 0.02);
    box-sizing: border-box;
    width: 100%;
}

/* Customer Type Radio Cards */
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
    margin-bottom: 8px;
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
    margin-top: 4px;
    line-height: 1.3;
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

/* Subtle Section Dividers */
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

/* Form Controls & Floating feeling */
.form-label {
    display: block;
    font-size: 0.86rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}

.form-label .required {
    color: #ef4444;
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
    border-color: #ef4444;
    background: #fef2f2;
}

/* Password Toggle Wrapper */
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

/* Custom Select Dropdowns */
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
    background: linear-gradient(135deg, var(--seagreen-600) 0%, #0284c7 100%);
    border: none;
    border-radius: 12px;
    box-shadow: 0 6px 18px -2px rgba(13, 148, 136, 0.35);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    color: #ffffff;
    cursor: pointer;
    width: 100%;
}

.register-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px -4px rgba(13, 148, 136, 0.45);
    filter: brightness(1.05);
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
        padding: 95px 12px 40px 12px;
        align-items: flex-start;
    }

    .register-card {
        padding: 22px 16px;
        border-radius: 16px;
        box-shadow: 0 8px 24px -4px rgba(15, 23, 42, 0.06);
    }

    .register-title {
        font-size: 1.5rem;
    }

    .register-subtitle {
        font-size: 0.85rem;
    }

    /* Customer types in compact 3-col on mobile */
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

    /* iOS 16px font-size to prevent automatic Safari zooming */
    .form-control {
        font-size: 16px !important;
        height: 46px;
    }

    /* Address Grid: State takes full width, City & Postcode side-by-side */
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

@media (max-width: 360px) {
    .register-card {
        padding: 18px 12px;
    }
    
    .ctype-desc {
        display: none; /* Hide description on extremely tiny 320px screens for perfect fit */
    }

    .ctype-radio {
        padding: 8px 2px;
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
        // Show slash-eye (hide password icon)
        btn.innerHTML = `
            <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            </svg>
        `;
        btn.setAttribute('aria-label', 'Hide password');
    } else {
        // Show eye icon
        btn.innerHTML = `
            <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        `;
        btn.setAttribute('aria-label', 'Show password');
    }
}
</script>
@endpush
