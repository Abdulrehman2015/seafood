@extends('layouts.app')
@section('title', __t('auth.register_meta_title', 'Create Account — MST Import & Export Sdn. Bhd.'))

@section('content')
<!-- Page Header / Hero Section -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">@t('auth.breadcrumb_home', '🏠 Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('auth.breadcrumb_register', 'Create Account')</span>
        </div>
        <div>
            <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.35rem);margin-bottom:6px;letter-spacing:-0.02em">
                @t('auth.register_header_title', 'Create Your MST Account')
            </h1>
            <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:720px;line-height:1.55;margin:0">
                @t('auth.register_header_subtitle', 'Create an MST account to manage your orders and access features available to your customer category.')
            </p>
        </div>
    </div>
</div>

<div class="register-page-wrapper">
    <div class="register-container">
        
        <!-- Header -->
        <div class="register-header text-center">
            <h2 class="register-title">@t('auth.register_header_title', 'Create Your MST Account')</h2>
            <p class="register-subtitle">@t('auth.register_header_instruction', 'Choose the account type that best matches how you purchase from MST.')</p>
        </div>

        <div class="register-card">
            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf

                <!-- Section: Customer Type Selector -->
                <div class="form-section-block">
                    <label class="form-label font-semibold" style="margin-bottom:8px">
                        @t('auth.customer_type_label', 'Customer Type') <span class="required">*</span>
                    </label>
                    <div class="customer-types-grid">
                        @php
                            $selectedGroup = old('customer_group', request('type', 'retail'));
                            if (!in_array($selectedGroup, ['retail', 'wholesale', 'trading'])) {
                                $selectedGroup = 'retail';
                            }
                        @endphp

                        <!-- 1. Retail / Personal -->
                        <label class="ctype-radio {{ $selectedGroup == 'retail' ? 'selected' : '' }}" for="type_retail" id="label_retail">
                            <input type="radio" name="customer_group" id="type_retail" value="retail"
                                   {{ $selectedGroup == 'retail' ? 'checked' : '' }}
                                   onchange="onTypeChange('retail')">
                            <div class="ctype-content">
                                <span class="ctype-icon">🛒</span>
                                <span class="ctype-label">@t('auth.type_retail_label', 'Retail / Personal')</span>
                                <span class="ctype-desc">@t('auth.type_retail_desc', 'For personal shoppers and retail customers.')</span>
                            </div>
                        </label>

                        <!-- 2. Wholesale -->
                        <label class="ctype-radio {{ $selectedGroup == 'wholesale' ? 'selected' : '' }}" for="type_wholesale" id="label_wholesale">
                            <input type="radio" name="customer_group" id="type_wholesale" value="wholesale"
                                   {{ $selectedGroup == 'wholesale' ? 'checked' : '' }}
                                   onchange="onTypeChange('wholesale')">
                            <div class="ctype-content">
                                <span class="ctype-icon">🏢</span>
                                <span class="ctype-label">@t('auth.type_wholesale_label', 'Wholesale')</span>
                                <span class="ctype-desc">@t('auth.type_wholesale_desc', 'For restaurants, retailers, hotels, caterers, food businesses and other businesses purchasing in volume.')</span>
                            </div>
                        </label>

                        <!-- 3. Trading / Import & Distribution -->
                        <label class="ctype-radio {{ $selectedGroup == 'trading' ? 'selected' : '' }}" for="type_trading" id="label_trading">
                            <input type="radio" name="customer_group" id="type_trading" value="trading"
                                   {{ $selectedGroup == 'trading' ? 'checked' : '' }}
                                   onchange="onTypeChange('trading')">
                            <div class="ctype-content">
                                <span class="ctype-icon">📦</span>
                                <span class="ctype-label">@t('auth.type_trading_label', 'Trading / Import & Distribution')</span>
                                <span class="ctype-desc">@t('auth.type_trading_desc', 'For traders, importers, distributors and businesses with larger or regional supply requirements.')</span>
                            </div>
                        </label>
                    </div>
                    @error('customer_group')<div class="form-error">{{ $message }}</div>@enderror

                    <!-- Walk-in Supporting Note -->
                    <div style="margin-top:12px;font-size:0.82rem;color:#475569;line-height:1.45;padding:10px 14px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                        <div style="display:flex;align-items:center;gap:8px">
                            <span>🛍️</span>
                            <span>@t('auth.walkin_supporting_note', 'Just shopping through our Walk-in Menu? You do not need an account to browse or place a Walk-in / Counter Collection order.')</span>
                        </div>
                        <a href="{{ route('walkin.shop') }}" style="color:#2563eb;font-weight:700;text-decoration:none;font-size:0.82rem;white-space:nowrap">
                            @t('auth.btn_open_walkin', 'Walk-in Menu →')
                        </a>
                    </div>
                </div>

                <!-- Section: Personal Details (Always Displayed) -->
                <div class="section-divider">
                    <span>@t('auth.section_personal_details', 'Personal Details')</span>
                </div>

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

                <!-- Email (Strict Uniqueness: One account per email address) -->
                <div class="form-group">
                    <label class="form-label" for="email">
                        <span>@t('auth.field_email', 'Email Address') <span class="required">*</span></span>
                        <span class="field-hint-tag">@t('auth.email_unique_note', 'One account per email address.')</span>
                    </label>
                    <div class="input-with-status">
                        <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
                        <span id="emailSpinner" class="field-spinner" style="display:none"></span>
                    </div>
                    <div id="emailFeedback" class="field-live-feedback" style="display:none"></div>
                    @if($errors->has('email'))
                        <div class="form-error" id="emailServerError" style="margin-top:6px;padding:8px 12px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#991b1b;font-size:0.82rem;line-height:1.4">
                            <span>@t('auth.email_exists_message', 'An account with this email already exists.')</span>
                            <div style="margin-top:4px;display:flex;gap:12px;align-items:center">
                                <a href="{{ route('login') }}" style="color:#2563eb;font-weight:700;text-decoration:underline">@t('auth.signin_link', 'Sign In')</a>
                                <span style="color:#cbd5e1">•</span>
                                <a href="{{ route('password.request') }}" style="color:#2563eb;font-weight:700;text-decoration:underline">@t('auth.reset_password_link', 'Reset Password')</a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Security / Password -->
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
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- WHOLESALE SPECIFIC FIELDS -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div id="businessFields" style="{{ $selectedGroup === 'wholesale' ? 'display:block' : 'display:none' }}">
                    <div class="section-divider">
                        <span>@t('auth.section_company_info', 'Company Information')</span>
                    </div>

                    <div class="form-grid-2">
                        <!-- Company Name -->
                        <div class="form-group">
                            <label class="form-label" for="company_name">
                                @t('auth.field_company_name', 'Company Name') <span class="required">*</span>
                            </label>
                            <div class="input-with-status">
                                <input type="text" name="company_name" id="company_name" class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}"
                                       value="{{ old('company_name') }}" placeholder="{{ __t('auth.placeholder_company_name', 'e.g. Ocean Blue Restaurant Sdn Bhd') }}">
                                <span id="companySpinner" class="field-spinner" style="display:none"></span>
                            </div>
                            
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

                        <!-- Company Registration No. (SSM) (Non-mandatory) -->
                        <div class="form-group">
                            <label class="form-label" for="company_reg_no">
                                <span>@t('auth.field_company_ssm', 'Company Registration No. (SSM)')</span>
                                <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
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

                    <div class="form-grid-2">
                        <!-- Business Nature / Type -->
                        <div class="form-group">
                            <label class="form-label" for="business_type">@t('auth.field_business_nature', 'Business Nature / Type') <span class="required">*</span></label>
                            <div class="custom-select-wrapper">
                                <select name="business_type" id="business_type" class="form-control custom-select {{ $errors->has('business_type') ? 'is-invalid' : '' }}">
                                    <option value="">@t('auth.select_business_type', 'Select business type...')</option>
                                    <option value="Restaurant & Catering" {{ old('business_type') == 'Restaurant & Catering' ? 'selected' : '' }}>@t('auth.btype_restaurant', 'Restaurant & Catering')</option>
                                    <option value="Seafood Retailer" {{ old('business_type') == 'Seafood Retailer' ? 'selected' : '' }}>@t('auth.btype_retailer', 'Seafood Retailer')</option>
                                    <option value="Food Retailer" {{ old('business_type') == 'Food Retailer' ? 'selected' : '' }}>@t('auth.btype_food_retailer', 'Food Retailer')</option>
                                    <option value="Seafood Importer" {{ old('business_type') == 'Seafood Importer' ? 'selected' : '' }}>@t('auth.btype_importer', 'Seafood Importer')</option>
                                    <option value="Seafood Exporter" {{ old('business_type') == 'Seafood Exporter' ? 'selected' : '' }}>@t('auth.btype_exporter', 'Seafood Exporter')</option>
                                    <option value="Food Manufacturer" {{ old('business_type') == 'Food Manufacturer' ? 'selected' : '' }}>@t('auth.btype_manufacturer', 'Food Manufacturer')</option>
                                    <option value="Hotel / Resort" {{ old('business_type') == 'Hotel / Resort' ? 'selected' : '' }}>@t('auth.btype_hotel', 'Hotel / Resort')</option>
                                    <option value="Distributor" {{ old('business_type') == 'Distributor' ? 'selected' : '' }}>@t('auth.btype_distributor', 'Distributor')</option>
                                    <option value="Other" {{ old('business_type') == 'Other' ? 'selected' : '' }}>@t('auth.btype_other', 'Other')</option>
                                </select>
                            </div>
                            @error('business_type')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <!-- Estimated Order Volume (Optional) -->
                        <div class="form-group">
                            <label class="form-label" for="estimated_order_volume">
                                <span>@t('auth.field_est_volume', 'Estimated Order Volume')</span>
                                <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                            </label>
                            <input type="text" name="estimated_order_volume" id="estimated_order_volume" class="form-control"
                                   value="{{ old('estimated_order_volume') }}" placeholder="e.g. 500kg / month, 20 cartons / week">
                        </div>
                    </div>

                    <!-- Existing MST Customer Check for Wholesale -->
                    <div class="form-group" style="padding:14px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;margin-top:6px">
                        <label class="form-label" style="margin-bottom:6px">@t('auth.field_existing_customer_question', 'Are you an existing MST customer?')</label>
                        <div style="display:flex;gap:20px;align-items:center;padding:4px 0">
                            <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.88rem;color:#334155">
                                <input type="radio" name="existing_mst_customer" value="yes" {{ old('existing_mst_customer') == 'yes' ? 'checked' : '' }} onchange="toggleExistingRef(this.value)" style="accent-color:#2563eb">
                                <span>@t('common.yes', 'Yes')</span>
                            </label>
                            <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.88rem;color:#334155">
                                <input type="radio" name="existing_mst_customer" value="no" {{ old('existing_mst_customer', 'no') == 'no' ? 'checked' : '' }} onchange="toggleExistingRef(this.value)" style="accent-color:#2563eb">
                                <span>@t('common.no', 'No')</span>
                            </label>
                        </div>

                        <!-- Existing Customer Reference Input -->
                        <div id="existingRefBlock" style="{{ old('existing_mst_customer') == 'yes' ? 'display:block' : 'display:none' }};margin-top:10px">
                            <label class="form-label" for="existing_customer_ref" style="font-size:0.80rem">
                                <span>@t('auth.field_existing_ref', 'Existing Customer / Account Reference')</span>
                                <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                            </label>
                            <input type="text" name="existing_customer_ref" id="existing_customer_ref" class="form-control" style="height:38px;font-size:0.85rem"
                                   value="{{ old('existing_customer_ref') }}" placeholder="e.g. Account No., Invoice No., or Company Name on file">
                            <p style="font-size:0.75rem;color:#64748b;margin:4px 0 0">
                                @t('auth.existing_ref_hint', 'This helps our team match your registration with existing MST business records.')
                            </p>
                        </div>
                    </div>

                    <!-- Wholesale Pricing Disclaimer Notice -->
                    <div style="margin-top:12px;font-size:0.80rem;color:#1e40af;line-height:1.45;padding:10px 12px;background:#eff6ff;border-radius:8px;border-left:3px solid #2563eb">
                        ℹ️ @t('auth.wholesale_pricing_disclaimer', 'Wholesale pricing and commercial terms are subject to business verification, product availability, order volume and applicable MST requirements.')
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- TRADING / IMPORT & DISTRIBUTION SPECIFIC FIELDS -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div id="tradingFields" style="{{ $selectedGroup === 'trading' ? 'display:block' : 'display:none' }}">
                    <div class="section-divider">
                        <span>@t('auth.section_trading_company_info', 'Company Information')</span>
                    </div>

                    <div class="form-grid-2">
                        <!-- Company Name -->
                        <div class="form-group">
                            <label class="form-label" for="trading_company_name">
                                @t('auth.field_company_name', 'Company Name') <span class="required">*</span>
                            </label>
                            <input type="text" name="trading_company_name" id="trading_company_name" class="form-control"
                                   value="{{ old('trading_company_name', old('company_name')) }}" placeholder="{{ __t('auth.placeholder_company_name', 'e.g. Global Foods Trading Ltd') }}">
                        </div>

                        <!-- Business Registration No. -->
                        <div class="form-group">
                            <label class="form-label" for="trading_company_reg_no">
                                <span>@t('auth.field_trading_reg_no', 'Company Registration No. / Business Registration No.')</span>
                                <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                            </label>
                            <input type="text" name="trading_company_reg_no" id="trading_company_reg_no" class="form-control"
                                   value="{{ old('trading_company_reg_no', old('company_reg_no')) }}" placeholder="Registration Number">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <!-- Business Nature / Type -->
                        <div class="form-group">
                            <label class="form-label" for="trading_business_type">
                                @t('auth.field_business_nature', 'Business Nature / Type') <span class="required">*</span>
                            </label>
                            <input type="text" name="trading_business_type" id="trading_business_type" class="form-control"
                                   value="{{ old('trading_business_type', old('business_type', 'Importer / Distributor / Trader')) }}" placeholder="e.g. Seafood Importer, Regional Distributor">
                        </div>

                        <!-- Country / Market -->
                        <div class="form-group">
                            <label class="form-label" for="destination_market">
                                @t('auth.field_country_market', 'Country / Market')
                            </label>
                            <input type="text" name="destination_market" id="destination_market" class="form-control"
                                   value="{{ old('destination_market') }}" placeholder="e.g. Malaysia, Singapore, Hong Kong, Regional Export">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="trading_business_location">
                            @t('auth.field_delivery_destination', 'Delivery / Destination Location')
                        </label>
                        <input type="text" name="trading_business_location" id="trading_business_location" class="form-control"
                               value="{{ old('trading_business_location', old('business_location')) }}" placeholder="e.g. Port Klang, Pasir Gudang, Jurong Port, etc.">
                    </div>

                    <div class="section-divider">
                        <span>@t('auth.section_trading_requirements', 'Trading Requirements')</span>
                    </div>

                    <div class="form-grid-2">
                        <!-- Product / Category Interest -->
                        <div class="form-group">
                            <label class="form-label" for="trading_product_interest">
                                @t('auth.field_product_interest', 'Product / Category Interest')
                            </label>
                            <input type="text" name="trading_product_interest" id="trading_product_interest" class="form-control"
                                   value="{{ old('trading_product_interest', old('product_interest')) }}" placeholder="e.g. Frozen Fish, Squid, Shrimp, Custom Procurement">
                        </div>

                        <!-- Estimated Order Volume -->
                        <div class="form-group">
                            <label class="form-label" for="trading_estimated_order_volume">
                                @t('auth.field_est_volume', 'Estimated Order Volume')
                            </label>
                            <input type="text" name="trading_estimated_order_volume" id="trading_estimated_order_volume" class="form-control"
                                   value="{{ old('trading_estimated_order_volume', old('estimated_order_volume')) }}" placeholder="e.g. 20ft / 40ft Container, FCL / LCL">
                        </div>
                    </div>

                    <!-- Import / Distribution Requirements -->
                    <div class="form-group">
                        <label class="form-label" for="import_requirements">
                            @t('auth.field_import_reqs', 'Import / Distribution Requirements')
                        </label>
                        <textarea name="import_requirements" id="import_requirements" class="form-control" rows="2" style="height:auto;padding:10px 14px"
                                  placeholder="Specify any port of discharge, cold-chain specifications, packaging or certification requirements">{{ old('import_requirements') }}</textarea>
                    </div>

                    <!-- Additional Requirements / Message -->
                    <div class="form-group">
                        <label class="form-label" for="additional_message">
                            @t('auth.field_additional_requirements', 'Additional Requirements / Message')
                        </label>
                        <textarea name="additional_message" id="additional_message" class="form-control" rows="2" style="height:auto;padding:10px 14px"
                                  placeholder="Any specific commercial terms, schedules, or special requests">{{ old('additional_message') }}</textarea>
                    </div>

                    <!-- Existing MST Customer Check for Trading -->
                    <div class="form-group" style="padding:14px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;margin-top:6px">
                        <label class="form-label" style="margin-bottom:6px">@t('auth.field_existing_customer_question', 'Are you an existing MST customer?')</label>
                        <div style="display:flex;gap:20px;align-items:center;padding:4px 0">
                            <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.88rem;color:#334155">
                                <input type="radio" name="trading_existing_mst_customer" value="yes" {{ old('trading_existing_mst_customer', old('existing_mst_customer')) == 'yes' ? 'checked' : '' }} onchange="toggleTradingExistingRef(this.value)" style="accent-color:#2563eb">
                                <span>@t('common.yes', 'Yes')</span>
                            </label>
                            <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.88rem;color:#334155">
                                <input type="radio" name="trading_existing_mst_customer" value="no" {{ old('trading_existing_mst_customer', old('existing_mst_customer', 'no')) == 'no' ? 'checked' : '' }} onchange="toggleTradingExistingRef(this.value)" style="accent-color:#2563eb">
                                <span>@t('common.no', 'No')</span>
                            </label>
                        </div>

                        <div id="tradingExistingRefBlock" style="{{ old('trading_existing_mst_customer', old('existing_mst_customer')) == 'yes' ? 'display:block' : 'display:none' }};margin-top:10px">
                            <label class="form-label" for="trading_existing_customer_ref" style="font-size:0.80rem">
                                <span>@t('auth.field_existing_ref', 'Existing Customer / Account Reference')</span>
                                <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                            </label>
                            <input type="text" name="trading_existing_customer_ref" id="trading_existing_customer_ref" class="form-control" style="height:38px;font-size:0.85rem"
                                   value="{{ old('trading_existing_customer_ref', old('existing_customer_ref')) }}" placeholder="e.g. Trading Reference or Company Name">
                        </div>
                    </div>

                    <!-- Trading Pricing Disclaimer Notice -->
                    <div style="margin-top:12px;font-size:0.80rem;color:#4338ca;line-height:1.45;padding:10px 12px;background:#eef2ff;border-radius:8px;border-left:3px solid #6366f1">
                        📦 @t('auth.trading_pricing_disclaimer', 'Trading and import/distribution enquiries may require additional review and quotation.')
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- DELIVERY / ADDRESS & FULFILMENT (FOR RETAIL & WHOLESALE) -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div id="generalDeliveryFields" style="{{ $selectedGroup !== 'trading' ? 'display:block' : 'display:none' }}">
                    <div class="section-divider">
                        <span>@t('auth.section_delivery', 'Delivery / Collection Information')</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">
                            <span>@t('auth.field_street_address', 'Street Address')</span>
                            <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                        </label>
                        <input type="text" name="address" id="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" 
                               value="{{ old('address') }}" placeholder="{{ __t('auth.placeholder_street_address', 'Unit / Street address, Taman / Area') }}">
                        @error('address')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="register-address-grid">
                        <div class="form-group mb-0 grid-state-col">
                            <label class="form-label" for="state">
                                <span>@t('auth.field_state', 'State')</span>
                                <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                            </label>
                            <input type="text" name="state" id="state" class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" 
                                   value="{{ old('state') }}" placeholder="{{ __t('auth.placeholder_state', 'e.g. Johor') }}">
                            @error('state')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group mb-0 grid-city-col">
                            <label class="form-label" for="city">
                                <span>@t('auth.field_city', 'City')</span>
                                <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                            </label>
                            <input type="text" name="city" id="city" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" 
                                   value="{{ old('city') }}" placeholder="{{ __t('auth.placeholder_city', 'e.g. Iskandar Puteri') }}">
                            @error('city')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group mb-0 grid-postcode-col">
                            <label class="form-label" for="postcode">
                                <span>@t('auth.field_postcode', 'Postcode')</span>
                                <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                            </label>
                            <input type="text" name="postcode" id="postcode" class="form-control {{ $errors->has('postcode') ? 'is-invalid' : '' }}" 
                                   value="{{ old('postcode') }}" placeholder="79200" maxlength="10">
                            @error('postcode')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Preferred Fulfilment Method -->
                    <div class="form-group" style="margin-top:16px">
                        <label class="form-label">@t('auth.section_fulfilment', 'Preferred Fulfilment Method')</label>
                        <div style="display:flex;gap:18px;align-items:center;flex-wrap:wrap;padding:4px 0">
                            <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.86rem;color:#334155">
                                <input type="radio" name="preferred_fulfilment" value="walkin" {{ old('preferred_fulfilment', 'walkin') == 'walkin' ? 'checked' : '' }} style="accent-color:#2563eb">
                                <span>🏬 @t('auth.fulfilment_walkin', 'Walk-in / Counter Collection')</span>
                            </label>
                            <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.86rem;color:#334155">
                                <input type="radio" name="preferred_fulfilment" value="delivery" {{ old('preferred_fulfilment') == 'delivery' ? 'checked' : '' }} style="accent-color:#2563eb">
                                <span>🚚 @t('auth.fulfilment_delivery', 'Delivery')</span>
                            </label>
                            <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.86rem;color:#334155">
                                <input type="radio" name="preferred_fulfilment" value="not_sure" {{ old('preferred_fulfilment') == 'not_sure' ? 'checked' : '' }} style="accent-color:#2563eb">
                                <span>❓ @t('auth.fulfilment_not_sure', 'Not Sure')</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- CONSENT & MARKETING BLOCK -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div class="consent-block" style="margin: 24px 0 16px 0; display: flex; flex-direction: column; gap: 14px; background: #f8fafc; padding: 18px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    
                    <!-- 14. Optional Marketing Consent (Separate & Unchecked by default) -->
                    <label class="consent-item" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 0.86rem; color: #334155; margin:0">
                        <input type="checkbox" name="marketing_opt_in" value="1" style="margin-top: 3px; width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer;" {{ old('marketing_opt_in') ? 'checked' : '' }}>
                        <span>
                            <strong style="color:#0f274a;">🎁 @t('auth.marketing_opt_in_title', 'MST Updates & Offers (Optional)')</strong><br>
                            <span style="color: #64748b; font-size: 0.82rem; line-height:1.45; display:block; margin-top:2px">
                                @t('auth.marketing_consent_exact', 'Yes, send me MST product updates, new arrivals, promotions and relevant wholesale offers via WhatsApp and Email.')
                            </span>
                        </span>
                    </label>

                    <div style="border-top:1px solid #e2e8f0"></div>

                    <!-- 15. Mandatory Terms & Privacy Policy Consent -->
                    <label class="consent-item" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 0.86rem; color: #334155; margin:0">
                        <input type="checkbox" name="terms_consent" value="1" required style="margin-top: 3px; width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer;" {{ old('terms_consent') ? 'checked' : '' }}>
                        <span>
                            @t('auth.i_agree_to', 'I agree to the') 
                            <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => 'terms-and-conditions']) }}" target="_blank" style="color:#2563eb;text-decoration:underline;font-weight:600">@t('nav.terms_and_conditions', 'Terms & Conditions')</a> 
                            @t('common.and', 'and') 
                            <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => 'privacy-policy']) }}" target="_blank" style="color:#2563eb;text-decoration:underline;font-weight:600">@t('nav.privacy_policy', 'Privacy Policy')</a>. 
                            <span class="required" style="color:#ef4444">*</span>
                        </span>
                    </label>
                    @error('terms_consent')<div class="form-error" style="margin-top:-6px">{{ $message }}</div>@enderror
                </div>

                <x-recaptcha context="register" />

                <!-- 21. Create Account Button -->
                <div class="submit-section">
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-lg btn-block register-submit-btn">
                        <span>@t('auth.btn_create_account', 'Create Account')</span>
                    </button>
                </div>

                <!-- 23. Sign In Link -->
                <div class="register-footer-links text-center">
                    <p class="text-sm text-muted">
                        @t('auth.already_have_account', 'Already have an account?') <a href="{{ route('login') }}" class="signin-link">@t('auth.signin_link', 'Sign In')</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- 24. Walk-in Bottom Notice Card -->
        <div class="card" style="margin-top:20px;box-shadow:0 6px 18px -4px rgba(0,0,0,0.04);border:1px solid #e2e8f0;border-radius:18px;padding:20px 24px;background:#ffffff">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px">
                <div style="display:flex;align-items:center;gap:12px">
                    <div style="width:38px;height:38px;border-radius:10px;background:#f1f5f9;color:#0f172a;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0">
                        🛍️
                    </div>
                    <div>
                        <h3 style="font-family:var(--font-heading);font-size:1.02rem;font-weight:800;color:#0f274a;margin:0 0 2px;line-height:1.3">
                            @t('auth.walkin_bottom_title', 'Shopping for Walk-in / Retail?')
                        </h3>
                        <p style="color:#64748b;font-size:0.83rem;line-height:1.4;margin:0">
                            @t('auth.walkin_bottom_desc', 'You can browse our Walk-in Menu without creating an account.')
                        </p>
                    </div>
                </div>
                <a href="{{ route('walkin.shop') }}" 
                   style="display:inline-flex;align-items:center;justify-content:center;gap:6px;background:#f8fafc;color:#0f274a;border:1.5px solid #cbd5e1;font-weight:700;font-size:0.84rem;padding:8px 16px;border-radius:9px;text-decoration:none;transition:all 0.15s ease;white-space:nowrap">
                    <span>@t('auth.btn_browse_walkin_menu', 'Walk-in Menu →')</span>
                </a>
            </div>
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
    padding: 32px 16px 64px 16px;
    background: #f8fafc;
    box-sizing: border-box;
}

.register-container {
    width: 100%;
    max-width: 680px;
    margin: 0 auto;
    box-sizing: border-box;
}

.register-header {
    margin-bottom: 20px;
}

.register-title {
    font-family: var(--font-heading);
    font-size: 1.65rem;
    font-weight: 800;
    color: #0f274a;
    margin: 0 0 4px;
    letter-spacing: -0.02em;
}

.register-subtitle {
    font-size: 0.90rem;
    color: #64748b;
    max-width: 520px;
    margin: 0 auto;
    line-height: 1.45;
}

/* Card Styling */
.register-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
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
    margin-top: 8px;
}

.ctype-radio {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 16px 10px;
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    user-select: none;
    box-sizing: border-box;
    min-height: 140px;
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
    font-size: 0.88rem;
    color: #0f274a;
    line-height: 1.25;
    display: block;
}

.ctype-desc {
    font-size: 0.72rem;
    color: #64748b;
    margin-top: 5px;
    line-height: 1.35;
    display: block;
}

.ctype-radio:hover {
    border-color: #93c5fd;
    background: #f0f9ff;
    transform: translateY(-1px);
}

.ctype-radio:hover .ctype-icon {
    transform: scale(1.08);
}

.ctype-radio.selected {
    border-color: #2563eb;
    background: #eff6ff;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.12);
}

.ctype-radio.selected .ctype-label {
    color: #1d4ed8;
}

/* Section Dividers */
.section-divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 24px 0 16px 0;
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

/* Form Controls */
.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
}

.form-group {
    margin-bottom: 14px;
}

.form-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.85rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 5px;
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
    font-size: 0.90rem;
    color: #0f172a;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    transition: all 0.2s ease;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    background: #ffffff;
}

.form-control::placeholder {
    color: #94a3b8;
    font-size: 0.86rem;
}

.form-control.is-invalid {
    border-color: #ef4444 !important;
    background: #fef2f2 !important;
}

.form-control.is-valid {
    border-color: #10b981;
}

.form-error {
    color: #ef4444;
    font-size: 0.78rem;
    margin-top: 4px;
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
    border-top-color: #2563eb;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
    pointer-events: none;
}

@keyframes spin {
    to { transform: translateY(-50%) rotate(360deg); }
}

/* Live Field Feedback */
.field-live-feedback {
    font-size: 0.80rem;
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
}

.warning-box-icon {
    font-size: 1.2rem;
    line-height: 1.2;
    flex-shrink: 0;
}

.warning-box-content {
    flex: 1;
}

.warning-box-title {
    font-size: 0.82rem;
    font-weight: 700;
    color: #92400e;
    line-height: 1.35;
    margin-bottom: 2px;
}

.warning-box-sub {
    font-size: 0.74rem;
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
    margin-top: 24px;
}

.register-submit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 48px;
    font-size: 0.96rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    background: linear-gradient(135deg, #1d4ed8 0%, #0f274a 100%);
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

.register-footer-links {
    margin-top: 18px;
}

.signin-link {
    color: #2563eb;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.15s ease;
}

.signin-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* ─── Mobile Responsiveness ────────────────────────────────────────────────── */
@media (max-width: 640px) {
    .register-page-wrapper {
        padding: 16px 12px 36px 12px;
        align-items: flex-start;
    }

    .register-header {
        margin-bottom: 12px;
    }

    .register-card {
        padding: 20px 16px;
        border-radius: 16px;
    }

    .register-title {
        font-size: 1.35rem;
    }

    .register-subtitle {
        font-size: 0.84rem;
    }

    .customer-types-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }

    .ctype-radio {
        min-height: auto;
        padding: 12px 10px;
        flex-direction: row;
        gap: 12px;
        align-items: center;
        text-align: left;
    }

    .ctype-content {
        align-items: flex-start;
        text-align: left;
    }

    .ctype-icon {
        font-size: 1.4rem;
        margin-bottom: 0;
    }

    .ctype-label {
        font-size: 0.84rem;
    }

    .ctype-desc {
        font-size: 0.72rem;
        margin-top: 2px;
    }

    .form-grid-2 {
        grid-template-columns: 1fr;
        gap: 12px;
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
    const bizEl = document.getElementById('businessFields');
    const tradingEl = document.getElementById('tradingFields');
    const deliveryEl = document.getElementById('generalDeliveryFields');

    if (bizEl) {
        bizEl.style.display = (type === 'wholesale') ? 'block' : 'none';
    }
    if (tradingEl) {
        tradingEl.style.display = (type === 'trading') ? 'block' : 'none';
    }
    if (deliveryEl) {
        deliveryEl.style.display = (type !== 'trading') ? 'block' : 'none';
    }

    // Sync trading inputs with main form inputs if needed
    if (type === 'trading') {
        const trComp = document.getElementById('trading_company_name');
        const mainComp = document.getElementById('company_name');
        if (trComp && mainComp && trComp.value) mainComp.value = trComp.value;

        const trBt = document.getElementById('trading_business_type');
        const mainBt = document.getElementById('business_type');
        if (trBt && mainBt && trBt.value) mainBt.value = trBt.value;
    }

    document.querySelectorAll('.ctype-radio').forEach(l => l.classList.remove('selected'));
    const selectedLabel = document.getElementById('label_' + type);
    if (selectedLabel) {
        selectedLabel.classList.add('selected');
    }
}

function toggleExistingRef(val) {
    const refEl = document.getElementById('existingRefBlock');
    if (refEl) {
        refEl.style.display = (val === 'yes') ? 'block' : 'none';
    }
}

function toggleTradingExistingRef(val) {
    const refEl = document.getElementById('tradingExistingRefBlock');
    if (refEl) {
        refEl.style.display = (val === 'yes') ? 'block' : 'none';
    }
}

// Before submitting, synchronize trading fields to main names if trading is selected
document.getElementById('registerForm')?.addEventListener('submit', function () {
    const activeType = document.querySelector('input[name="customer_group"]:checked')?.value;
    if (activeType === 'trading') {
        const trComp = document.getElementById('trading_company_name');
        const mainComp = document.getElementById('company_name');
        if (trComp && mainComp && trComp.value) mainComp.value = trComp.value;

        const trReg = document.getElementById('trading_company_reg_no');
        const mainReg = document.getElementById('company_reg_no');
        if (trReg && mainReg && trReg.value) mainReg.value = trReg.value;

        const trBt = document.getElementById('trading_business_type');
        const mainBt = document.getElementById('business_type');
        if (trBt && mainBt && trBt.value) {
            // If main select doesn't have it, create option or sync
            let exists = false;
            for (let opt of mainBt.options) {
                if (opt.value === trBt.value) { exists = true; break; }
            }
            if (!exists) {
                const newOpt = new Option(trBt.value, trBt.value, true, true);
                mainBt.add(newOpt);
            }
            mainBt.value = trBt.value;
        }

        const trLoc = document.getElementById('trading_business_location');
        const mainLoc = document.getElementById('business_location');
        if (trLoc && mainLoc && trLoc.value) mainLoc.value = trLoc.value;

        const trVol = document.getElementById('trading_estimated_order_volume');
        const mainVol = document.getElementById('estimated_order_volume');
        if (trVol && mainVol && trVol.value) mainVol.value = trVol.value;

        const trProd = document.getElementById('trading_product_interest');
        const mainProd = document.getElementById('product_interest');
        if (trProd && mainProd && trProd.value) mainProd.value = trProd.value;

        const trExist = document.querySelector('input[name="trading_existing_mst_customer"]:checked');
        if (trExist) {
            const mainExist = document.querySelector(`input[name="existing_mst_customer"][value="${trExist.value}"]`);
            if (mainExist) mainExist.checked = true;
        }

        const trRef = document.getElementById('trading_existing_customer_ref');
        const mainRef = document.getElementById('existing_customer_ref');
        if (trRef && mainRef && trRef.value) mainRef.value = trRef.value;
    }
});

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
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        `;
        btn.setAttribute('aria-label', 'Show password');
    }
}

// ─── Debounced Live Field Verification & Translations ─────────────────────────
const regI18n = {
    emailTaken: @json(__t('auth.email_already_registered', 'An account with this email already exists. Please Sign In or reset your password.')),
    emailAvailable: @json(__t('auth.email_available', 'Email address is available')),
    companyWarning: @json(__t('auth.company_similarity_warning', 'This company may already be registered. Please check if your company already has an account or contact MST.')),
    ssmDuplicate: @json(__t('auth.ssm_duplicate_error', 'An account with this Company Registration Number (SSM) is already registered.')),
    ssmAvailable: @json(__t('auth.ssm_available', 'SSM Number is available')),
    signInText: @json(__t('auth.signin_link', 'Sign In')),
    resetPasswordText: @json(__t('auth.reset_password_link', 'Reset Password')),
    loginUrl: @json(route('login')),
    resetPasswordUrl: @json(route('password.request'))
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
                        emailFeedback.innerHTML = `⚠️ <span>${res.message || regI18n.emailTaken}</span> <span style="margin-left:8px"><a href="${regI18n.loginUrl}" style="color:#2563eb;font-weight:700;text-decoration:underline">${regI18n.signInText}</a> | <a href="${regI18n.resetPasswordUrl}" style="color:#2563eb;font-weight:700;text-decoration:underline">${regI18n.resetPasswordText}</a></span>`;
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

    // 2. Live Company Name Similarity Check
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

    // 3. Live SSM Number Uniqueness Check
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
