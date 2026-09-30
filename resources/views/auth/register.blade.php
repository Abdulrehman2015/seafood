@extends('layouts.app')

@php
    $selectedGroup = old('customer_group', $selectedType ?? request('type', 'retail'));
    if (!in_array($selectedGroup, ['retail', 'wholesale', 'trading', 'general_retail'])) {
        $selectedGroup = 'retail';
    }
    if ($selectedGroup === 'general_retail') {
        $selectedGroup = 'retail';
    }

    $metaTitle = match($selectedGroup) {
        'wholesale' => __t('auth.register_wholesale_meta_title', 'Wholesale Registration — MST Import and Export Sdn. Bhd.'),
        'trading'   => __t('auth.register_trading_meta_title', 'Trading Registration — MST Import and Export Sdn. Bhd.'),
        default     => __t('auth.register_meta_title', 'Create Account — MST Import and Export Sdn. Bhd.')
    };
@endphp

@section('title', $metaTitle)

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
                @t('auth.register_header_subtitle', 'Select the account type that matches your purchasing requirements.')
            </p>
        </div>
    </div>
</div>

<div class="register-page-wrapper">
    <div class="register-container">
        


        <!-- ════════════════════════════════════════════════════════════════════ -->
        <!-- THREE CLEAR ACCOUNT TYPE CHOICES (CUSTOMER FLOW)                   -->
        <!-- 1. General / Retail | 2. Wholesale | 3. Trading                     -->
        <!-- ════════════════════════════════════════════════════════════════════ -->
        <div class="account-selection-wrapper" style="margin-bottom:20px">
            <div class="account-types-grid">
                
                <!-- 1. General / Retail -->
                <div class="atype-card {{ $selectedGroup === 'retail' ? 'active' : '' }}" id="card_retail" onclick="selectAccountType('retail')">
                    <div class="atype-badge">@t('auth.account_type_retail_badge', '🛍️ General / Retail')</div>
                    <h3 class="atype-title">@t('auth.account_type_retail_title', 'General / Retail Account')</h3>
                    <p class="atype-desc">@t('auth.account_type_retail_desc', 'For personal / normal retail purchasing.')</p>
                    <button type="button" class="atype-btn {{ $selectedGroup === 'retail' ? 'btn-active' : '' }}" id="btn_select_retail">
                        @t('auth.account_type_retail_btn', 'Register as General / Retail')
                    </button>
                </div>

                <!-- 2. Wholesale -->
                <div class="atype-card {{ $selectedGroup === 'wholesale' ? 'active' : '' }}" id="card_wholesale" onclick="selectAccountType('wholesale')">
                    <div class="atype-badge">@t('auth.account_type_wholesale_badge', '📦 Wholesale')</div>
                    <h3 class="atype-title">@t('auth.account_type_wholesale_title', 'Wholesale Account')</h3>
                    <p class="atype-desc">@t('auth.account_type_wholesale_desc', 'For restaurants, hotels, retailers, food businesses and regular wholesale purchasing.')</p>
                    <button type="button" class="atype-btn {{ $selectedGroup === 'wholesale' ? 'btn-active' : '' }}" id="btn_select_wholesale">
                        @t('auth.account_type_wholesale_btn', 'Register as Wholesale')
                    </button>
                </div>

                <!-- 3. Trading -->
                <div class="atype-card {{ $selectedGroup === 'trading' ? 'active' : '' }}" id="card_trading" onclick="selectAccountType('trading')">
                    <div class="atype-badge">@t('auth.account_type_trading_badge', '🌏 Trading')</div>
                    <h3 class="atype-title">@t('auth.account_type_trading_title', 'Trading Account')</h3>
                    <p class="atype-desc">@t('auth.account_type_trading_desc', 'For traders, importers, exporters, distributors, cross-border purchasing and customised trade requirements.')</p>
                    <button type="button" class="atype-btn {{ $selectedGroup === 'trading' ? 'btn-active' : '' }}" id="btn_select_trading">
                        @t('auth.account_type_trading_btn', 'Register as Trading')
                    </button>
                </div>

            </div>
        </div>

        <div class="register-card">
            
            <!-- Walk-in Supporting Note -->
            <div style="margin-bottom:20px;font-size:0.84rem;color:#475569;line-height:1.45;padding:12px 16px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                <div style="display:flex;align-items:center;gap:10px">
                    <span style="font-size:1.1rem">🛍️</span>
                    <span>@t('auth.walkin_supporting_note', 'Just shopping through our Walk-in Menu? You do not need an account to browse or place an order for self-collection.')</span>
                </div>
                <a href="{{ route('walkin.shop') }}" style="color:#2563eb;font-weight:700;text-decoration:none;font-size:0.84rem;white-space:nowrap;display:inline-flex;align-items:center;gap:4px">
                    @t('auth.btn_browse_walkin_menu', 'Browse Walk-in Menu →')
                </a>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf

                <!-- Selected Account Type (Hidden Form Input) -->
                <input type="hidden" name="customer_group" id="customer_group_input" value="{{ $selectedGroup }}">

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- 1. PERSONAL DETAILS (ALL THREE ACCOUNT TYPES)                       -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div class="section-divider" style="margin-top:8px">
                    <span>@t('auth.section_personal_details', 'Personal Details')</span>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="name">
                            @t('auth.field_fullname', 'Full Name') <span class="required" style="color:#ef4444">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name') }}" placeholder="{{ __t('auth.placeholder_fullname', 'e.g. John Doe') }}" required autocomplete="name">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">
                            @t('auth.field_phone', 'Phone Number') <span class="required" style="color:#ef4444">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                               value="{{ old('phone') }}" placeholder="+60 12-345 6789" required autocomplete="tel">
                        @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Email Address (1 account per email) -->
                <div class="form-group">
                    <label class="form-label" for="email">
                        <span>@t('auth.field_email', 'Email Address') <span class="required" style="color:#ef4444">*</span></span>
                        <span class="field-hint-tag">@t('auth.email_unique_note', '1 account per email')</span>
                    </label>
                    <div class="input-with-status">
                        <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email') }}" placeholder="you@company.com" required autocomplete="email">
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

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- 2. SECURITY (ALL THREE ACCOUNT TYPES)                               -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div class="section-divider">
                    <span>@t('auth.section_security', 'Security')</span>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="password">
                            @t('auth.field_password', 'Password') <span class="required" style="color:#ef4444">*</span>
                        </label>
                        <div class="password-field-wrapper">
                            <input type="password" name="password" id="password" class="form-control password-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="{{ __t('auth.placeholder_min_chars', 'Min. 8 characters') }}" required autocomplete="new-password">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" aria-label="Toggle password visibility" tabindex="-1">
                                <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">
                            @t('auth.field_confirm_password', 'Confirm Password') <span class="required" style="color:#ef4444">*</span>
                        </label>
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
                <!-- 3. COMPANY INFORMATION (WHOLESALE & TRADING ONLY)                   -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- 1. COMPANY / BUSINESS INFORMATION (WHOLESALE & TRADING ONLY)         -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div id="companySection" style="display: {{ in_array($selectedGroup, ['wholesale', 'trading']) ? 'block' : 'none' }}">
                    <div class="section-divider">
                        <span>@t('auth.section_company_info', '1. Company / Business Information')</span>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="company_name">
                                @t('auth.field_company_name', 'Company Name') <span class="required" style="color:#ef4444">*</span>
                            </label>
                            <input type="text" name="company_name" id="company_name" class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}"
                                   value="{{ old('company_name') }}" placeholder="{{ __t('auth.placeholder_company_name', 'e.g. Ocean Blue Restaurant Sdn Bhd') }}">
                            @error('company_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="company_reg_no">
                                <span>@t('auth.field_company_ssm_required', 'Company Registration No. / SSM / UEN / Other') <span class="required" style="color:#ef4444">*</span></span>
                            </label>
                            <input type="text" name="company_reg_no" id="company_reg_no" class="form-control {{ $errors->has('company_reg_no') ? 'is-invalid' : '' }}"
                                   value="{{ old('company_reg_no') }}" placeholder="202301012345 (1234567-X) / UEN / Reg No">
                            @error('company_reg_no')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Business Nature / Type (Wholesale: 9 options vs Trading: 10 options) -->
                    <div class="form-group">
                        <label class="form-label" for="business_type">
                            @t('auth.field_business_nature', 'Business Nature / Type') <span class="required" style="color:#ef4444">*</span>
                        </label>
                        
                        <!-- Wholesale Business Types (9 options) -->
                        <div id="wholesaleBtypeWrapper" style="display: {{ $selectedGroup === 'wholesale' ? 'block' : 'none' }}">
                            <div class="custom-select-wrapper">
                                <select name="business_type_wholesale" id="business_type_wholesale" class="form-control custom-select" onchange="syncBusinessType(this.value)">
                                    <option value="">@t('auth.select_business_type', 'Select business type...')</option>
                                    <option value="Restoran & Katering" {{ old('business_type') == 'Restoran & Katering' || old('business_type') == 'Restaurant & Catering' ? 'selected' : '' }}>@t('auth.btype_restaurant', 'Restoran & Katering')</option>
                                    <option value="Kafe / Bakeri" {{ old('business_type') == 'Kafe / Bakeri' || old('business_type') == 'Cafe / Bakery' ? 'selected' : '' }}>@t('auth.btype_cafe_bakery', 'Kafe / Bakeri')</option>
                                    <option value="Hotel / Resort" {{ old('business_type') == 'Hotel / Resort' ? 'selected' : '' }}>@t('auth.btype_hotel', 'Hotel / Resort')</option>
                                    <option value="Pasar Raya / Kedai Runcit" {{ old('business_type') == 'Pasar Raya / Kedai Runcit' || old('business_type') == 'Supermarket / Grocery Store' ? 'selected' : '' }}>@t('auth.btype_supermarket_grocery', 'Pasar Raya / Kedai Runcit')</option>
                                    <option value="Peruncit Makanan Laut" {{ old('business_type') == 'Peruncit Makanan Laut' || old('business_type') == 'Seafood Retailer' ? 'selected' : '' }}>@t('auth.btype_seafood_retailer', 'Peruncit Makanan Laut')</option>
                                    <option value="Pengilang Makanan / Dapur Pusat" {{ old('business_type') == 'Pengilang Makanan / Dapur Pusat' || old('business_type') == 'Food Manufacturer / Central Kitchen' ? 'selected' : '' }}>@t('auth.btype_manufacturer', 'Pengilang Makanan / Dapur Pusat')</option>
                                    <option value="Pemborong / Pengedar Makanan" {{ old('business_type') == 'Pemborong / Pengedar Makanan' || old('business_type') == 'Food Wholesaler / Distributor' ? 'selected' : '' }}>@t('auth.btype_distributor', 'Pemborong / Pengedar Makanan')</option>
                                    <option value="Pedagang Makanan" {{ old('business_type') == 'Pedagang Makanan' || old('business_type') == 'Food Trader' ? 'selected' : '' }}>@t('auth.btype_food_trader', 'Pedagang Makanan')</option>
                                    <option value="Lain-lain Perniagaan" {{ old('business_type') == 'Lain-lain Perniagaan' || old('business_type') == 'Other Business' ? 'selected' : '' }}>@t('auth.btype_other_business', 'Lain-lain Perniagaan')</option>
                                </select>
                            </div>
                        </div>

                        <!-- Trading Business Types (10 options) -->
                        <div id="tradingBtypeWrapper" style="display: {{ $selectedGroup === 'trading' ? 'block' : 'none' }}">
                            <div class="custom-select-wrapper">
                                <select name="business_type_trading" id="business_type_trading" class="form-control custom-select" onchange="syncBusinessType(this.value)">
                                    <option value="">@t('auth.select_business_type', 'Select business type...')</option>
                                    <option value="Restaurant & Catering" {{ old('business_type') == 'Restaurant & Catering' ? 'selected' : '' }}>@t('auth.btype_restaurant', 'Restaurant & Catering')</option>
                                    <option value="Seafood Retailer" {{ old('business_type') == 'Seafood Retailer' ? 'selected' : '' }}>@t('auth.btype_seafood_retailer', 'Seafood Retailer')</option>
                                    <option value="Food Retailer" {{ old('business_type') == 'Food Retailer' ? 'selected' : '' }}>@t('auth.btype_food_retailer', 'Food Retailer')</option>
                                    <option value="Seafood Importer" {{ old('business_type') == 'Seafood Importer' ? 'selected' : '' }}>@t('auth.btype_seafood_importer', 'Seafood Importer')</option>
                                    <option value="Seafood Exporter" {{ old('business_type') == 'Seafood Exporter' ? 'selected' : '' }}>@t('auth.btype_seafood_exporter', 'Seafood Exporter')</option>
                                    <option value="Food Manufacturer / Central Kitchen" {{ old('business_type') == 'Food Manufacturer / Central Kitchen' ? 'selected' : '' }}>@t('auth.btype_manufacturer', 'Food Manufacturer / Central Kitchen')</option>
                                    <option value="Hotel / Resort" {{ old('business_type') == 'Hotel / Resort' ? 'selected' : '' }}>@t('auth.btype_hotel', 'Hotel / Resort')</option>
                                    <option value="Food Wholesaler / Distributor" {{ old('business_type') == 'Food Wholesaler / Distributor' ? 'selected' : '' }}>@t('auth.btype_distributor', 'Food Wholesaler / Distributor')</option>
                                    <option value="Food Trader" {{ old('business_type') == 'Food Trader' ? 'selected' : '' }}>@t('auth.btype_food_trader', 'Food Trader')</option>
                                    <option value="Other Business" {{ old('business_type') == 'Other Business' ? 'selected' : '' }}>@t('auth.btype_other_business', 'Other Business')</option>
                                </select>
                            </div>
                        </div>

                        <!-- Actual Submitted Business Type -->
                        <input type="hidden" name="business_type" id="business_type_actual" value="{{ old('business_type') }}">
                        @error('business_type')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- 2. BUSINESS ADDRESS (WHOLESALE & TRADING ONLY)                      -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div id="businessAddressSection" style="display: {{ in_array($selectedGroup, ['wholesale', 'trading']) ? 'block' : 'none' }}">
                    <div class="section-divider">
                        <span>@t('auth.section_business_address', '2. Business Address')</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="business_address">
                            <span>@t('auth.field_business_address', 'Business Address') <span class="required" style="color:#ef4444">*</span></span>
                        </label>
                        <input type="text" name="business_address" id="business_address" class="form-control {{ $errors->has('business_address') ? 'is-invalid' : '' }}"
                               value="{{ old('business_address') }}" placeholder="{{ __t('auth.placeholder_business_address', 'Unit / Building / Street address of registered or operating business') }}">
                        @error('business_address')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="register-address-grid">
                        <div class="form-group mb-0 grid-state-col">
                            <label class="form-label" for="business_state">
                                <span>@t('auth.field_state', 'State') <span class="required" style="color:#ef4444">*</span></span>
                            </label>
                            <input type="text" name="business_state" id="business_state" class="form-control {{ $errors->has('business_state') ? 'is-invalid' : '' }}"
                                   value="{{ old('business_state') }}" placeholder="{{ __t('auth.placeholder_state', 'e.g. Johor') }}">
                            @error('business_state')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group mb-0 grid-city-col">
                            <label class="form-label" for="business_city">
                                <span>@t('auth.field_city', 'City') <span class="required" style="color:#ef4444">*</span></span>
                            </label>
                            <input type="text" name="business_city" id="business_city" class="form-control {{ $errors->has('business_city') ? 'is-invalid' : '' }}"
                                   value="{{ old('business_city') }}" placeholder="{{ __t('auth.placeholder_city', 'e.g. Iskandar Puteri') }}">
                            @error('business_city')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group mb-0 grid-postcode-col">
                            <label class="form-label" for="business_postcode">
                                <span>@t('auth.field_postcode', 'Postcode') <span class="required" style="color:#ef4444">*</span></span>
                            </label>
                            <input type="text" name="business_postcode" id="business_postcode" class="form-control {{ $errors->has('business_postcode') ? 'is-invalid' : '' }}"
                                   value="{{ old('business_postcode') }}" placeholder="79200" maxlength="10">
                            @error('business_postcode')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:12px">
                        <label class="form-label" for="business_country">
                            <span>@t('auth.field_business_country', 'Country (where applicable)')</span>
                            <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                        </label>
                        <input type="text" name="business_country" id="business_country" class="form-control {{ $errors->has('business_country') ? 'is-invalid' : '' }}"
                               value="{{ old('business_country') }}" placeholder="{{ __t('auth.placeholder_business_country', 'e.g. Malaysia, Singapore, China, etc.') }}">
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- 3. TARGET MARKET & 4. FINAL DESTINATION (TRADING ONLY)              -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div id="tradingMarketSection" style="display: {{ $selectedGroup === 'trading' ? 'block' : 'none' }}">
                    <div class="section-divider">
                        <span>@t('auth.section_target_market', '3. Target Market & Destination')</span>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="country_market">
                                @t('auth.field_country_market', 'Country / Target Market') <span class="required" style="color:#ef4444">*</span>
                            </label>
                            <input type="text" name="country_market" id="country_market" class="form-control {{ $errors->has('country_market') ? 'is-invalid' : '' }}"
                                   value="{{ old('country_market') }}" placeholder="{{ __t('auth.placeholder_country_market', 'e.g. Malaysia / Singapore / China / Other Markets') }}">
                            @error('country_market')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="destination_country">
                                @t('auth.field_delivery_destination', 'Delivery / Final Destination Location')
                            </label>
                            <input type="text" name="destination_country" id="destination_country" class="form-control"
                                   value="{{ old('destination_country', old('destination_market')) }}" placeholder="{{ __t('auth.placeholder_delivery_destination', 'e.g. Johor Bahru / Kuala Lumpur / Singapore / Port Klang') }}">
                        </div>
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- 5. TRADING REQUIREMENTS (TRADING ONLY)                              -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div id="tradingRequirementsSection" style="display: {{ $selectedGroup === 'trading' ? 'block' : 'none' }}">
                    <div class="section-divider">
                        <span>@t('auth.section_trading_requirements', '4. Trading Requirements')</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span>@t('auth.field_trading_requirements', 'Trading Requirements') <span class="required" style="color:#ef4444">*</span></span>
                        </label>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(130px, 1fr));gap:8px;margin-top:6px">
                            @php
                                $reqOptions = [
                                    'Import' => ['key' => 'auth.treq_import', 'def' => 'Import', 'icon' => '🚢'],
                                    'Export' => ['key' => 'auth.treq_export', 'def' => 'Export', 'icon' => '✈️'],
                                    'Distribution' => ['key' => 'auth.treq_distribution', 'def' => 'Distribution', 'icon' => '🏬'],
                                    'Bulk Purchasing' => ['key' => 'auth.treq_bulk_purchasing', 'def' => 'Bulk Purchasing', 'icon' => '📦'],
                                    'Customised Sourcing' => ['key' => 'auth.treq_customised_sourcing', 'def' => 'Customised Sourcing', 'icon' => '🔍'],
                                    'Other' => ['key' => 'auth.treq_other', 'def' => 'Other', 'icon' => '🌐'],
                                ];
                                $oldReqs = (array) old('trading_requirements', []);
                            @endphp
                            @foreach($reqOptions as $rVal => $rMeta)
                                <label style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;cursor:pointer;font-size:0.84rem;color:#334155;transition:all 0.15s ease">
                                    <input type="checkbox" name="trading_requirements[]" value="{{ $rVal }}" {{ in_array($rVal, $oldReqs) ? 'checked' : '' }} style="accent-color:#2563eb;width:16px;height:16px">
                                    <span>{{ $rMeta['icon'] }} @t($rMeta['key'], $rMeta['def'])</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- 6. PRODUCT REQUIREMENTS (WHOLESALE & TRADING ONLY — SINGLE SECTION)  -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div id="productInterestSection" style="display: {{ in_array($selectedGroup, ['wholesale', 'trading']) ? 'block' : 'none' }}">
                    <div class="section-divider">
                        <span><span id="productSectionNum">{{ $selectedGroup === 'trading' ? '5.' : ($selectedGroup === 'wholesale' ? '3.' : '6.') }}</span> @t('auth.section_product_requirements_title', 'Product Requirements')</span>
                    </div>

                    <!-- Single Product / Category Interest Selection -->
                    <div class="form-group">
                        <label class="form-label">
                            <span>@t('auth.field_product_interest', 'Product / Category Interest') <span class="required" style="color:#ef4444">*</span></span>
                        </label>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(130px, 1fr));gap:8px;margin-top:6px">
                            @php
                                $categoriesList = [
                                    'Frozen Seafood'    => ['key' => 'auth.cat_seafood', 'def' => 'Seafood'],
                                    'Frozen Meat'       => ['key' => 'auth.cat_meat', 'def' => 'Meat'],
                                    'Frozen Food'       => ['key' => 'auth.cat_frozen_food', 'def' => 'Frozen Food'],
                                    'Food Ingredients'  => ['key' => 'auth.cat_food_ingredients', 'def' => 'Food Ingredients'],
                                    'Japanese Products' => ['key' => 'auth.cat_japanese', 'def' => 'Japanese Products'],
                                    'Korean Products'   => ['key' => 'auth.cat_korean', 'def' => 'Korean Products'],
                                    'Chinese Products'  => ['key' => 'auth.cat_chinese', 'def' => 'Chinese Products'],
                                    'Western Products'  => ['key' => 'auth.cat_western', 'def' => 'Western Products'],
                                    'Other'             => ['key' => 'auth.cat_other', 'def' => 'Other'],
                                ];
                                $oldCats = (array) old('product_interest', []);
                            @endphp
                            @foreach($categoriesList as $cVal => $cMeta)
                                <label style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;cursor:pointer;font-size:0.84rem;color:#334155;transition:all 0.15s ease">
                                    <input type="checkbox" name="product_interest[]" value="{{ $cVal }}" {{ in_array($cVal, $oldCats) ? 'checked' : '' }} style="accent-color:#2563eb;width:16px;height:16px">
                                    <span>@t($cMeta['key'], $cMeta['def'])</span>
                                </label>
                            @endforeach
                        </div>
                        @error('product_interest')<div class="form-error" style="margin-top:4px">{{ $message }}</div>@enderror
                    </div>

                    <!-- Product Specifications / Requirements (Optional) -->
                    <div class="form-group">
                        <label class="form-label" for="import_requirements">
                            @t('auth.field_product_specifications', 'Product Specifications / Requirements (Optional)')
                        </label>
                        <textarea name="import_requirements" id="import_requirements" class="form-control" rows="2" style="height:auto;padding:10px 14px"
                                  placeholder="{{ __t('auth.placeholder_product_specifications', 'Specify product type, size/grade, packaging, brand, origin, quantity or other specifications') }}">{{ old('import_requirements') }}</textarea>
                    </div>

                    <!-- Estimated Order Volume (Optional) -->
                    <div class="form-group">
                        <label class="form-label" for="estimated_order_volume">
                            @t('auth.field_est_volume', 'Estimated Order Volume (Optional)')
                        </label>
                        <input type="text" name="estimated_order_volume" id="estimated_order_volume" class="form-control"
                               value="{{ old('estimated_order_volume') }}" placeholder="{{ __t('auth.placeholder_est_volume', 'e.g. 500kg / month, 20 cartons / week') }}">
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- 7. ADDITIONAL REQUIREMENTS & HISTORY (WHOLESALE & TRADING ONLY)     -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div id="additionalRequirementsSection" style="display: {{ in_array($selectedGroup, ['wholesale', 'trading']) ? 'block' : 'none' }}">
                    <div class="section-divider">
                        <span><span id="addlSectionNum">{{ $selectedGroup === 'trading' ? '6.' : ($selectedGroup === 'wholesale' ? '4.' : '7.') }}</span> @t('auth.section_additional_requirements_title', 'Additional Requirements')</span>
                    </div>

                    <!-- Additional Message (Optional) -->
                    <div class="form-group">
                        <label class="form-label" for="additional_message">
                            @t('auth.field_additional_message', 'Additional Requirements / Message (Optional)')
                        </label>
                        <textarea name="additional_message" id="additional_message" class="form-control" rows="2" style="height:auto;padding:10px 14px"
                                  placeholder="{{ __t('auth.placeholder_additional_message', 'Any specific commercial terms, schedules, or special requests') }}">{{ old('additional_message') }}</textarea>
                    </div>

                    <!-- Existing Customer Check -->
                    <div class="form-group" style="padding:14px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;margin-top:6px">
                        <label class="form-label" style="margin-bottom:6px">@t('auth.field_existing_customer_question', 'Existing MST Customer: Yes / No')</label>
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

                        <div id="existingRefBlock" style="{{ old('existing_mst_customer') == 'yes' ? 'display:block' : 'display:none' }};margin-top:10px">
                            <label class="form-label" for="existing_customer_ref" style="font-size:0.80rem">
                                @t('auth.field_existing_ref', 'Existing Customer / Account Reference (Optional)')
                            </label>
                            <input type="text" name="existing_customer_ref" id="existing_customer_ref" class="form-control" style="height:38px;font-size:0.85rem"
                                   value="{{ old('existing_customer_ref') }}" placeholder="{{ __t('auth.placeholder_existing_ref', 'e.g. Account No., Invoice No., or Company Name on file') }}">
                        </div>
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- DELIVERY / COLLECTION INFORMATION (ALL THREE ACCOUNT TYPES)          -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div class="section-divider">
                    <span>@t('auth.section_delivery', 'Delivery / Collection Information')</span>
                </div>

                <!-- Same as Business Address Toggle for Wholesale / Trading -->
                <div id="sameAddressToggle" style="display: {{ in_array($selectedGroup, ['wholesale', 'trading']) ? 'block' : 'none' }}; margin-bottom:12px; padding:10px 14px; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0">
                    <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;font-size:0.85rem;color:#334155;margin:0">
                        <input type="checkbox" id="same_as_business" onchange="copyBusinessToDelivery(this.checked)" style="accent-color:#2563eb;width:16px;height:16px">
                        <span>@t('auth.same_as_business_address', 'Delivery address is the same as Business Address')</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label" for="address">
                        <span>@t('auth.field_street_address', 'Street Address')</span>
                        <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                    </label>
                    <input type="text" name="address" id="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" 
                           value="{{ old('address') }}" placeholder="{{ __t('auth.placeholder_street_address', 'Unit / Street address, Taman / Area') }}" autocomplete="street-address">
                    @error('address')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="register-address-grid">
                    <div class="form-group mb-0 grid-state-col">
                        <label class="form-label" for="state">
                            <span>@t('auth.field_state', 'State')</span>
                            <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                        </label>
                        <input type="text" name="state" id="state" class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" 
                               value="{{ old('state') }}" placeholder="{{ __t('auth.placeholder_state', 'e.g. Johor') }}" autocomplete="address-level1">
                        @error('state')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-0 grid-city-col">
                        <label class="form-label" for="city">
                            <span>@t('auth.field_city', 'City')</span>
                            <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                        </label>
                        <input type="text" name="city" id="city" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" 
                               value="{{ old('city') }}" placeholder="{{ __t('auth.placeholder_city', 'e.g. Iskandar Puteri') }}" autocomplete="address-level2">
                        @error('city')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-0 grid-postcode-col">
                        <label class="form-label" for="postcode">
                            <span>@t('auth.field_postcode', 'Postcode')</span>
                            <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                        </label>
                        <input type="text" name="postcode" id="postcode" class="form-control {{ $errors->has('postcode') ? 'is-invalid' : '' }}" 
                               value="{{ old('postcode') }}" placeholder="79200" maxlength="10" autocomplete="postal-code">
                        @error('postcode')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Preferred Fulfilment Method -->
                <div class="form-group" style="margin-top:16px">
                    <label class="form-label">@t('auth.section_fulfilment', 'Preferred Fulfilment Method')</label>
                    <div style="display:flex;gap:18px;align-items:center;flex-wrap:wrap;padding:4px 0">
                        <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.86rem;color:#334155">
                            <input type="radio" name="preferred_fulfilment" value="walkin" {{ old('preferred_fulfilment', 'walkin') == 'walkin' ? 'checked' : '' }} style="accent-color:#2563eb">
                            <span>@t('auth.fulfilment_walkin', '🏬 Self-Collection')</span>
                        </label>
                        <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.86rem;color:#334155">
                            <input type="radio" name="preferred_fulfilment" value="delivery" {{ old('preferred_fulfilment') == 'delivery' ? 'checked' : '' }} style="accent-color:#2563eb">
                            <span>@t('auth.fulfilment_delivery', '🚚 Delivery')</span>
                        </label>
                        <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.86rem;color:#334155">
                            <input type="radio" name="preferred_fulfilment" value="not_sure" {{ old('preferred_fulfilment') == 'not_sure' ? 'checked' : '' }} style="accent-color:#2563eb">
                            <span>@t('auth.fulfilment_not_sure', '❓ Not Sure Yet')</span>
                        </label>
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- NOTICES & DISCLAIMERS                                              -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                
                <!-- Wholesale Notice Box -->
                <div id="wholesaleNoticeBox" style="display: {{ $selectedGroup === 'wholesale' ? 'block' : 'none' }}; margin-top:14px;font-size:0.80rem;color:#065f46;line-height:1.45;padding:12px 14px;background:#ecfdf5;border-radius:10px;border-left:3px solid #10b981">
                    @t('auth.wholesale_pricing_disclaimer', 'Wholesale pricing and commercial terms are subject to MST review and approval, product availability, order volume and applicable MST requirements.')
                </div>

                <!-- Trading Notice Box (Section 17) -->
                <div id="tradingNoticeBox" style="display: {{ $selectedGroup === 'trading' ? 'block' : 'none' }}; margin-top:14px;font-size:0.80rem;color:#1e40af;line-height:1.45;padding:12px 14px;background:#eff6ff;border-radius:10px;border-left:3px solid #2563eb">
                    @t('auth.trading_pricing_disclaimer', 'Trading pricing and supply arrangements are subject to MST review and approval, product availability, specifications, order volume, destination and applicable trading requirements.')
                </div>

                <!-- ════════════════════════════════════════════════════════════════════ -->
                <!-- MARKETING & LEGAL CONSENT (SECTION 18 — INDEPENDENT CONTROLS)      -->
                <!-- ════════════════════════════════════════════════════════════════════ -->
                <div class="consent-block" style="margin: 20px 0 16px 0; display: flex; flex-direction: column; gap: 12px; background: #f8fafc; padding: 18px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    
                    <div style="font-size: 0.86rem; font-weight: 700; color: #0f274a; display: flex; align-items: center; gap: 6px;">
                        <span>🎁</span>
                        <span>@t('auth.marketing_consent_title', 'Marketing Updates & Communications (Optional)')</span>
                    </div>

                    <!-- 1. Independent WhatsApp Marketing Consent -->
                    <label class="consent-item" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 0.84rem; color: #334155; margin:0">
                        <input type="checkbox" name="marketing_whatsapp" id="marketing_whatsapp" value="1" style="margin-top: 2px; width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer;" {{ old('marketing_whatsapp') ? 'checked' : '' }}>
                        <span>@t('auth.consent_whatsapp', 'I agree to receive MST updates via WhatsApp.')</span>
                    </label>

                    <!-- 2. Independent Email Marketing Consent -->
                    <label class="consent-item" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 0.84rem; color: #334155; margin:0">
                        <input type="checkbox" name="marketing_email" id="marketing_email" value="1" style="margin-top: 2px; width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer;" {{ old('marketing_email') ? 'checked' : '' }}>
                        <span>@t('auth.consent_email', 'I agree to receive MST updates via Email.')</span>
                    </label>

                    <div style="border-top:1px solid #e2e8f0; margin: 4px 0;"></div>

                    <!-- 3. Mandatory Terms & Privacy Policy Consent (Strictly Separate) -->
                    <label class="consent-item" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 0.86rem; color: #334155; margin:0">
                        <input type="checkbox" name="terms_consent" id="terms_consent" value="1" required style="margin-top: 3px; width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer;" {{ old('terms_consent') ? 'checked' : '' }}>
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

                <!-- Submit Button -->
                <div class="submit-section">
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-lg btn-block register-submit-btn">
                        <span id="submitBtnText">
                            @if($selectedGroup === 'wholesale')
                                @t('auth.btn_apply_wholesale_account', 'Apply for Wholesale Account')
                            @elseif($selectedGroup === 'trading')
                                @t('auth.btn_apply_trading_account', 'Apply for Trading Account')
                            @else
                                @t('auth.btn_create_account', 'Create Account')
                            @endif
                        </span>
                    </button>
                </div>

                <!-- Sign In Link -->
                <div class="register-footer-links text-center">
                    <p class="text-sm text-muted">
                        @t('auth.already_have_account', 'Already have an account?') <a href="{{ route('login') }}" class="signin-link">@t('auth.signin_link', 'Sign In')</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Walk-in Bottom Notice Card -->
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
                    <span>@t('auth.btn_browse_walkin_menu', 'Browse Walk-in Menu →')</span>
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
    max-width: 720px;
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
    max-width: 560px;
    margin: 0 auto;
    line-height: 1.45;
}

/* ─── 3 Clear Account Types Grid ─────────────────────────────────────────── */
.account-types-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.atype-card {
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px 14px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
}

.atype-card:hover {
    border-color: #93c5fd;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px -4px rgba(37, 99, 235, 0.10);
}

.atype-card.active {
    border-color: #2563eb;
    background: #eff6ff;
    box-shadow: 0 8px 20px -4px rgba(37, 99, 235, 0.16);
}

.atype-badge {
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f274a;
    margin-bottom: 4px;
}

.atype-title {
    font-family: var(--font-heading);
    font-size: 0.88rem;
    font-weight: 700;
    color: #0f274a;
    margin: 0 0 4px;
    line-height: 1.3;
}

.atype-card.active .atype-title {
    color: #1d4ed8;
}

.atype-desc {
    font-size: 0.74rem;
    color: #64748b;
    margin: 0 0 12px;
    line-height: 1.35;
    flex: 1;
}

.atype-btn {
    width: 100%;
    padding: 7px 10px;
    font-size: 0.78rem;
    font-weight: 700;
    border-radius: 8px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    cursor: pointer;
    transition: all 0.15s ease;
    text-align: center;
}

.atype-card.active .atype-btn {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
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

/* Custom Select */
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

    .account-types-grid {
        grid-template-columns: 1fr;
        gap: 10px;
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
const buttonTexts = {
    retail: @json(__t('auth.btn_create_account', 'Create Account')),
    wholesale: @json(__t('auth.btn_apply_wholesale_account', 'Apply for Wholesale Account')),
    trading: @json(__t('auth.btn_apply_trading_account', 'Apply for Trading Account'))
};

function selectAccountType(type) {
    // 1. Update hidden customer group input
    const input = document.getElementById('customer_group_input');
    if (input) input.value = type;

    // 2. Update visual active state of the 3 cards
    ['retail', 'wholesale', 'trading'].forEach(t => {
        const card = document.getElementById('card_' + t);
        const btn = document.getElementById('btn_select_' + t);
        if (card) {
            if (t === type) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        }
        if (btn) {
            if (t === type) {
                btn.classList.add('btn-active');
            } else {
                btn.classList.remove('btn-active');
            }
        }
    });

    // 3. Dynamic Section Display
    const compSec = document.getElementById('companySection');
    const bizAddrSec = document.getElementById('businessAddressSection');
    const wsBtype = document.getElementById('wholesaleBtypeWrapper');
    const trBtype = document.getElementById('tradingBtypeWrapper');
    const trMarketSec = document.getElementById('tradingMarketSection');
    const trReqsSec = document.getElementById('tradingRequirementsSection');
    const prodIntSec = document.getElementById('productInterestSection');
    const addlReqsSec = document.getElementById('additionalRequirementsSection');
    const sameAddrToggle = document.getElementById('sameAddressToggle');
    const wsNotice = document.getElementById('wholesaleNoticeBox');
    const trNotice = document.getElementById('tradingNoticeBox');

    if (compSec) {
        compSec.style.display = (type === 'wholesale' || type === 'trading') ? 'block' : 'none';
    }
    if (bizAddrSec) {
        bizAddrSec.style.display = (type === 'wholesale' || type === 'trading') ? 'block' : 'none';
    }
    if (wsBtype) {
        wsBtype.style.display = (type === 'wholesale') ? 'block' : 'none';
    }
    if (trBtype) {
        trBtype.style.display = (type === 'trading') ? 'block' : 'none';
    }
    if (trMarketSec) {
        trMarketSec.style.display = (type === 'trading') ? 'block' : 'none';
    }
    if (trReqsSec) {
        trReqsSec.style.display = (type === 'trading') ? 'block' : 'none';
    }
    if (prodIntSec) {
        prodIntSec.style.display = (type === 'wholesale' || type === 'trading') ? 'block' : 'none';
        const prodNum = document.getElementById('productSectionNum');
        if (prodNum) prodNum.textContent = (type === 'trading') ? '5.' : (type === 'wholesale' ? '3.' : '6.');
    }
    if (addlReqsSec) {
        addlReqsSec.style.display = (type === 'wholesale' || type === 'trading') ? 'block' : 'none';
        const addlNum = document.getElementById('addlSectionNum');
        if (addlNum) addlNum.textContent = (type === 'trading') ? '6.' : (type === 'wholesale' ? '4.' : '7.');
    }
    if (sameAddrToggle) {
        sameAddrToggle.style.display = (type === 'wholesale' || type === 'trading') ? 'block' : 'none';
    }
    if (wsNotice) {
        wsNotice.style.display = (type === 'wholesale') ? 'block' : 'none';
    }
    if (trNotice) {
        trNotice.style.display = (type === 'trading') ? 'block' : 'none';
    }

    // 4. Update Business Type actual input from relevant select
    if (type === 'wholesale') {
        const wsSel = document.getElementById('business_type_wholesale');
        if (wsSel) syncBusinessType(wsSel.value);
    } else if (type === 'trading') {
        const trSel = document.getElementById('business_type_trading');
        if (trSel) syncBusinessType(trSel.value);
    } else {
        syncBusinessType('');
    }

    // 5. Update Submit Button Text
    const submitBtnText = document.getElementById('submitBtnText');
    if (submitBtnText && buttonTexts[type]) {
        submitBtnText.textContent = buttonTexts[type];
    }

    // 6. Update URL query without page reload
    if (window.history && window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.set('type', type);
        window.history.replaceState({}, '', url);
    }
}

function copyBusinessToDelivery(isChecked) {
    if (isChecked) {
        const bAddr = document.getElementById('business_address')?.value || '';
        const bState = document.getElementById('business_state')?.value || '';
        const bCity = document.getElementById('business_city')?.value || '';
        const bPostcode = document.getElementById('business_postcode')?.value || '';

        if (bAddr) document.getElementById('address').value = bAddr;
        if (bState) document.getElementById('state').value = bState;
        if (bCity) document.getElementById('city').value = bCity;
        if (bPostcode) document.getElementById('postcode').value = bPostcode;
    }
}

function syncBusinessType(val) {
    const act = document.getElementById('business_type_actual');
    if (act) act.value = val;
}

function toggleExistingRef(val) {
    const refEl = document.getElementById('existingRefBlock');
    if (refEl) {
        refEl.style.display = (val === 'yes') ? 'block' : 'none';
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
    // Initialize initial state based on hidden input
    const initialType = document.getElementById('customer_group_input')?.value || 'retail';
    selectAccountType(initialType);

    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('emailFeedback');
    const emailServerError = document.getElementById('emailServerError');

    // Live Email Check
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
});
</script>
@endpush
