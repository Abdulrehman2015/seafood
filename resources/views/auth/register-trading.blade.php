@extends('layouts.app')
@section('title', __t('auth.trading_meta_title', 'Create Your Trading Account — MST Import & Export Sdn. Bhd.'))

@section('content')
<!-- Page Header / Hero Section -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <!-- 3. Breadcrumb -->
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">@t('auth.breadcrumb_home', '🏠 Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <a href="{{ route('register') }}" style="color:#bae6fd;text-decoration:none">@t('auth.breadcrumb_register', 'Create Account')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('auth.breadcrumb_trading', 'Trading / Import & Distribution')</span>
        </div>
        <!-- 4. Page Header -->
        <div>
            <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.35rem);margin-bottom:6px;letter-spacing:-0.02em">
                @t('auth.trading_page_title', 'Create Your Trading Account')
            </h1>
            <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:760px;line-height:1.55;margin:0">
                @t('auth.trading_page_subtitle', 'For traders, importers, distributors and regional buyers seeking frozen seafood, meat, food ingredients and customized sourcing from MST.')
            </p>
        </div>
    </div>
</div>

<div class="register-page-wrapper">
    <div class="register-container" style="max-width:720px">
        
        <!-- Header Card Title -->
        <div class="register-header text-center">
            <h2 class="register-title">@t('auth.trading_page_title', 'Create Your Trading Account')</h2>
            <p class="register-subtitle">@t('auth.trading_page_subtitle', 'For traders, importers, distributors and regional buyers seeking frozen seafood, meat, food ingredients and customized sourcing from MST.')</p>
        </div>

        <div class="register-card">
            <form method="POST" action="{{ route('register') }}" id="tradingRegisterForm" novalidate>
                @csrf
                <input type="hidden" name="customer_group" value="trading">

                <!-- 5. Account Type Selector -->
                <div class="form-section-block">
                    <h2 class="form-section-heading" style="font-size:0.95rem;font-weight:700;color:#0f274a;margin:0 0 10px;display:flex;align-items:center;justify-content:space-between">
                        <span>@t('auth.customer_type_label', 'Customer Type') <span class="required" style="color:#ef4444">*</span></span>
                    </h2>
                    <div class="customer-types-grid">
                        <!-- Retail Option (Links to main register) -->
                        <a href="{{ route('register', ['type' => 'retail']) }}" class="ctype-radio" style="text-decoration:none">
                            <div class="ctype-content">
                                <span class="ctype-icon">🛒</span>
                                <span class="ctype-label">@t('auth.type_retail_label', 'Retail / Personal')</span>
                                <span class="ctype-desc">@t('auth.type_retail_desc', 'For personal shoppers and retail customers.')</span>
                            </div>
                        </a>

                        <!-- Wholesale Option (Links to wholesale register) -->
                        <a href="{{ route('register', ['type' => 'wholesale']) }}" class="ctype-radio" style="text-decoration:none">
                            <div class="ctype-content">
                                <span class="ctype-icon">🏢</span>
                                <span class="ctype-label">@t('auth.type_wholesale_label', 'Wholesale')</span>
                                <span class="ctype-desc">@t('auth.type_wholesale_desc', 'For restaurants, retailers, hotels, caterers and businesses.')</span>
                            </div>
                        </a>

                        <!-- Trading Option (Selected by default) -->
                        <div class="ctype-radio selected" style="cursor:default">
                            <div class="ctype-content">
                                <span class="ctype-icon">📦</span>
                                <span class="ctype-label" style="color:#1d4ed8">@t('auth.type_trading_label', 'Trading / Import & Distribution') ✓</span>
                                <span class="ctype-desc">@t('auth.type_trading_desc', 'For traders, importers, distributors and regional supply requirements.')</span>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Explanatory Statement -->
                    <div style="margin-top:12px;font-size:0.82rem;color:#334155;line-height:1.5;padding:12px 14px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;border-left:3px solid #3b82f6">
                        ℹ️ @t('auth.trading_review_note', 'Trading and import/distribution enquiries are subject to review, product availability, destination requirements and applicable commercial terms. Pricing is generally quotation-based.')
                    </div>
                </div>

                <!-- 6. Business Information Section -->
                <div class="section-divider">
                    <span>@t('auth.section_business_info', 'Business Information')</span>
                </div>

                <div class="form-grid-2">
                    <!-- Company Name -->
                    <div class="form-group">
                        <label class="form-label" for="company_name">
                            @t('auth.field_company_name', 'Company Name') <span class="required">*</span>
                        </label>
                        <input type="text" name="company_name" id="company_name" class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}"
                               value="{{ old('company_name') }}" placeholder="{{ __t('auth.placeholder_trading_company_name', 'ABC Trading Pte. Ltd.') }}" required>
                        @error('company_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- Business Registration No. (International friendly) -->
                    <div class="form-group">
                        <label class="form-label" for="company_reg_no">
                            <span>@t('auth.field_trading_reg_no', 'Company Registration / Business Registration No.')</span>
                            <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                        </label>
                        <input type="text" name="company_reg_no" id="company_reg_no" class="form-control {{ $errors->has('company_reg_no') ? 'is-invalid' : '' }}"
                               value="{{ old('company_reg_no') }}" placeholder="{{ __t('auth.placeholder_trading_reg_no', 'UEN / SSM / Company Registration Number') }}">
                        @error('company_reg_no')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-grid-2">
                    <!-- Business Nature / Type -->
                    <div class="form-group">
                        <label class="form-label" for="business_type">
                            @t('auth.field_business_nature', 'Business Nature / Type') <span class="required">*</span>
                        </label>
                        <div class="custom-select-wrapper">
                            <select name="business_type" id="business_type" class="form-control custom-select {{ $errors->has('business_type') ? 'is-invalid' : '' }}" required>
                                <option value="">@t('auth.select_business_type', 'Select business type...')</option>
                                <option value="Importer" {{ old('business_type') == 'Importer' ? 'selected' : '' }}>@t('auth.btype_importer_only', 'Importer')</option>
                                <option value="Exporter" {{ old('business_type') == 'Exporter' ? 'selected' : '' }}>@t('auth.btype_exporter_only', 'Exporter')</option>
                                <option value="Distributor" {{ old('business_type') == 'Distributor' ? 'selected' : '' }}>@t('auth.btype_distributor', 'Distributor')</option>
                                <option value="Food Trader" {{ old('business_type') == 'Food Trader' ? 'selected' : '' }}>@t('auth.btype_food_trader', 'Food Trader')</option>
                                <option value="Seafood Trader" {{ old('business_type') == 'Seafood Trader' ? 'selected' : '' }}>@t('auth.btype_seafood_trader', 'Seafood Trader')</option>
                                <option value="Foodservice Supplier" {{ old('business_type') == 'Foodservice Supplier' ? 'selected' : '' }}>@t('auth.btype_foodservice_supplier', 'Foodservice Supplier')</option>
                                <option value="Retail Distributor" {{ old('business_type') == 'Retail Distributor' ? 'selected' : '' }}>@t('auth.btype_retail_distributor', 'Retail Distributor')</option>
                                <option value="Regional Trading Company" {{ old('business_type') == 'Regional Trading Company' ? 'selected' : '' }}>@t('auth.btype_regional_trading', 'Regional Trading Company')</option>
                                <option value="Other" {{ old('business_type') == 'Other' ? 'selected' : '' }}>@t('auth.btype_other', 'Other')</option>
                            </select>
                        </div>
                        @error('business_type')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- Country / Market -->
                    <div class="form-group">
                        <label class="form-label" for="country_market">
                            @t('auth.field_country_market_label', 'Country / Market') <span class="required">*</span>
                        </label>
                        <input type="text" name="country_market" id="country_market" class="form-control {{ $errors->has('country_market') ? 'is-invalid' : '' }}"
                               value="{{ old('country_market') }}" placeholder="{{ __t('auth.placeholder_country_market', 'Malaysia / Singapore / Indonesia') }}" required>
                        @error('country_market')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="destination_country">
                        @t('auth.field_destination_delivery_country', 'Destination / Delivery Country') <span class="required">*</span>
                    </label>
                    <input type="text" name="destination_country" id="destination_country" class="form-control {{ $errors->has('destination_country') ? 'is-invalid' : '' }}"
                           value="{{ old('destination_country', old('destination_market')) }}" placeholder="{{ __t('auth.placeholder_destination_country', 'Singapore') }}" required>
                    @error('destination_country')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <!-- 7. Contact Person Section -->
                <div class="section-divider">
                    <span>@t('auth.section_contact_person', 'Contact Person')</span>
                </div>

                <div class="form-grid-2">
                    <!-- Full Name -->
                    <div class="form-group">
                        <label class="form-label" for="name">
                            @t('auth.field_fullname', 'Full Name') <span class="required">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name') }}" placeholder="{{ __t('auth.placeholder_fullname', 'e.g. Ahmad bin Ali') }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- Position / Role -->
                    <div class="form-group">
                        <label class="form-label" for="position_role">
                            <span>@t('auth.field_position_role', 'Position / Role')</span>
                            <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                        </label>
                        <input type="text" name="position_role" id="position_role" class="form-control"
                               value="{{ old('position_role') }}" placeholder="{{ __t('auth.placeholder_position_role', 'Purchasing Manager / Director / Buyer') }}">
                    </div>
                </div>

                <div class="form-grid-2">
                    <!-- Phone / WhatsApp -->
                    <div class="form-group">
                        <label class="form-label" for="phone">
                            @t('auth.field_phone_whatsapp', 'Phone / WhatsApp') <span class="required">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                               value="{{ old('phone') }}" placeholder="+60 12-345 6789" required>
                        @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- Email Address (One account per email) -->
                    <div class="form-group">
                        <label class="form-label" for="email">
                            <span>@t('auth.field_email', 'Email Address') <span class="required">*</span></span>
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
                                <span>@t('auth.email_exists_message', 'An account with this email already exists. Please Sign In or reset your password.')</span>
                                <div style="margin-top:4px;display:flex;gap:12px;align-items:center">
                                    <a href="{{ route('login') }}" style="color:#2563eb;font-weight:700;text-decoration:underline">@t('auth.signin_link', 'Sign In')</a>
                                    <span style="color:#cbd5e1">•</span>
                                    <a href="{{ route('password.request') }}" style="color:#2563eb;font-weight:700;text-decoration:underline">@t('auth.reset_password_link', 'Reset Password')</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Password & Confirm Password -->
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="password">@t('auth.field_password', 'Password') <span class="required">*</span></label>
                        <div class="password-field-wrapper">
                            <input type="password" name="password" id="password" class="form-control password-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="{{ __t('auth.placeholder_min_chars', 'Min. 8 characters') }}" required autocomplete="new-password">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" aria-label="Toggle password visibility" tabindex="-1">
                                <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
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
                                <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 8. Product & Sourcing Requirements Section -->
                <div class="section-divider">
                    <span>@t('auth.section_product_sourcing_reqs', 'Product & Sourcing Requirements')</span>
                </div>

                <!-- Product / Category Interest (Multi-select) -->
                <div class="form-group">
                    <label class="form-label">
                        <span>@t('auth.field_product_category_interest', 'Product / Category Interest') <span class="required">*</span></span>
                        <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('auth.select_all_applicable', 'Select all that apply'))</span>
                    </label>
                    <div class="category-interest-grid" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(170px, 1fr));gap:8px;margin-top:6px">
                        @php
                            $categories = [
                                'Frozen Seafood', 'Frozen Meat', 'Food Ingredients', 
                                'Japanese Products', 'Korean Products', 'Chinese Food Ingredients', 
                                'Western Products', 'Snacks', 'Sauces', 'Desserts', 'Other'
                            ];
                            $oldInterests = (array) old('product_interest', []);
                        @endphp
                        @foreach($categories as $cat)
                            <label class="category-checkbox-chip" style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;cursor:pointer;font-size:0.83rem;color:#334155;transition:all 0.15s ease">
                                <input type="checkbox" name="product_interest[]" value="{{ $cat }}" {{ in_array($cat, $oldInterests) ? 'checked' : '' }} style="accent-color:#2563eb;width:16px;height:16px">
                                <span>{{ $cat }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('product_interest')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <!-- Estimated Order Volume Dropdown -->
                <div class="form-group">
                    <label class="form-label" for="estimated_order_volume">
                        <span>@t('auth.field_est_volume', 'Estimated Order Volume')</span>
                        <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                    </label>
                    <div class="custom-select-wrapper">
                        <select name="estimated_order_volume" id="estimated_order_volume" class="form-control custom-select">
                            <option value="">@t('auth.select_order_volume', 'Select volume range...')</option>
                            <option value="Small / Trial Order" {{ old('estimated_order_volume') == 'Small / Trial Order' ? 'selected' : '' }}>Small / Trial Order</option>
                            <option value="Regular Commercial Order" {{ old('estimated_order_volume') == 'Regular Commercial Order' ? 'selected' : '' }}>Regular Commercial Order</option>
                            <option value="Bulk / Container" {{ old('estimated_order_volume') == 'Bulk / Container' ? 'selected' : '' }}>Bulk / Container</option>
                            <option value="Not Sure Yet" {{ old('estimated_order_volume') == 'Not Sure Yet' ? 'selected' : '' }}>Not Sure Yet</option>
                        </select>
                    </div>
                </div>

                <!-- Import / Distribution Requirements (Large Textarea) -->
                <div class="form-group">
                    <label class="form-label" for="import_requirements">
                        <span>@t('auth.field_import_distribution_reqs', 'Import / Distribution Requirements')</span>
                    </label>
                    <p style="font-size:0.78rem;color:#64748b;margin:0 0 6px">
                        @t('auth.import_reqs_helper', 'Please tell us about your destination market, preferred products, specifications, packaging, brand, origin or other requirements.')
                    </p>
                    <textarea name="import_requirements" id="import_requirements" class="form-control" rows="4" style="height:auto;padding:12px 14px;line-height:1.5"
                              placeholder="{{ __t('auth.placeholder_import_reqs', 'e.g. Product specification, origin, pack size, brand, estimated quantity, destination market, required documentation, etc.') }}">{{ old('import_requirements') }}</textarea>
                </div>

                <!-- 9. Delivery / Logistics Information Section -->
                <div class="section-divider">
                    <span>@t('auth.section_delivery_logistics', 'Delivery / Logistics Information')</span>
                </div>

                <!-- Preferred Supply Arrangement -->
                <div class="form-group">
                    <label class="form-label">
                        <span>@t('auth.field_preferred_supply_arrangement', 'Preferred Supply Arrangement')</span>
                    </label>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;padding:4px 0">
                        @php
                            $arrangements = [
                                'MST Delivery' => '🚚 MST Delivery',
                                'Customer Collection' => '🏬 Customer Collection',
                                'Third-Party Logistics' => '🚢 Third-Party Logistics',
                                'Export / Cross-Border Shipment' => '✈️ Export / Cross-Border Shipment',
                                'Not Sure — Please Advise' => '❓ Not Sure — Please Advise',
                            ];
                        @endphp
                        @foreach($arrangements as $val => $lbl)
                            <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.85rem;color:#334155;background:#f8fafc;border:1px solid #e2e8f0;padding:8px 12px;border-radius:8px">
                                <input type="radio" name="supply_arrangement" value="{{ $val }}" {{ old('supply_arrangement', 'Export / Cross-Border Shipment') == $val ? 'checked' : '' }} style="accent-color:#2563eb">
                                <span>{{ $lbl }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Delivery / Destination Address (Optional) -->
                <div class="form-group">
                    <label class="form-label" for="address">
                        <span>@t('auth.field_destination_address_opt', 'Delivery / Destination Address')</span>
                        <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                    </label>
                    <input type="text" name="address" id="address" class="form-control"
                           value="{{ old('address') }}" placeholder="{{ __t('auth.placeholder_destination_address', 'Port / Warehouse / Destination Street Address') }}">
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="city">
                            <span>@t('auth.field_city', 'City / Port')</span>
                            <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                        </label>
                        <input type="text" name="city" id="city" class="form-control"
                               value="{{ old('city') }}" placeholder="{{ __t('auth.placeholder_city_port', 'e.g. Singapore, Hong Kong, Jurong Port') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="state">
                            <span>@t('auth.field_state_province', 'State / Province / Region')</span>
                            <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                        </label>
                        <input type="text" name="state" id="state" class="form-control"
                               value="{{ old('state') }}" placeholder="{{ __t('auth.placeholder_region', 'e.g. Western Province / State') }}">
                    </div>
                </div>

                <!-- 10. Existing MST Customer Section -->
                <div class="form-group" style="padding:16px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;margin-top:14px">
                    <label class="form-label" style="margin-bottom:8px">
                        @t('auth.field_existing_customer_question', 'Are you an existing MST customer?')
                    </label>
                    <div style="display:flex;gap:20px;align-items:center;padding:4px 0">
                        <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.88rem;color:#334155">
                            <input type="radio" name="existing_mst_customer" value="yes" {{ old('existing_mst_customer') == 'yes' ? 'checked' : '' }} onchange="toggleTradingExistingRef(this.value)" style="accent-color:#2563eb">
                            <span>@t('common.yes', 'Yes')</span>
                        </label>
                        <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.88rem;color:#334155">
                            <input type="radio" name="existing_mst_customer" value="no" {{ old('existing_mst_customer', 'no') == 'no' ? 'checked' : '' }} onchange="toggleTradingExistingRef(this.value)" style="accent-color:#2563eb">
                            <span>@t('common.no', 'No')</span>
                        </label>
                        <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:0.88rem;color:#334155">
                            <input type="radio" name="existing_mst_customer" value="not_sure" {{ old('existing_mst_customer') == 'not_sure' ? 'checked' : '' }} onchange="toggleTradingExistingRef(this.value)" style="accent-color:#2563eb">
                            <span>@t('common.not_sure', 'Not Sure')</span>
                        </label>
                    </div>

                    <!-- Existing Customer Reference Input -->
                    <div id="tradingExistingRefBlock" style="{{ old('existing_mst_customer') == 'yes' ? 'display:block' : 'display:none' }};margin-top:12px">
                        <label class="form-label" for="existing_customer_ref" style="font-size:0.82rem">
                            <span>@t('auth.field_existing_customer_name_ref', 'Existing Customer Name / Reference')</span>
                            <span style="font-weight:400;color:#64748b;font-size:0.75rem">(@t('common.optional', 'Optional'))</span>
                        </label>
                        <input type="text" name="existing_customer_ref" id="existing_customer_ref" class="form-control" style="height:40px;font-size:0.86rem"
                               value="{{ old('existing_customer_ref') }}" placeholder="{{ __t('auth.placeholder_existing_trading_ref', 'e.g. Account Number, previous invoice, or existing company name on file') }}">
                    </div>
                </div>

                <!-- 11. Marketing Consent Section (Optional, Unchecked by default) -->
                <div class="consent-block" style="margin: 22px 0 16px 0; display: flex; flex-direction: column; gap: 14px; background: #f8fafc; padding: 18px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <label class="consent-item" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 0.86rem; color: #334155; margin:0">
                        <input type="checkbox" name="marketing_opt_in" value="1" style="margin-top: 3px; width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer;" {{ old('marketing_opt_in') ? 'checked' : '' }}>
                        <span>
                            <strong style="color:#0f274a;">🎁 @t('auth.section_trading_marketing_title', 'Product Updates & Commercial Offers — Optional')</strong><br>
                            <span style="color: #64748b; font-size: 0.82rem; line-height:1.45; display:block; margin-top:2px">
                                @t('auth.trading_marketing_consent_exact', 'Yes, send me MST product updates, new arrivals, promotions, sourcing opportunities and commercial offers via WhatsApp & Email.')
                            </span>
                        </span>
                    </label>

                    <div style="border-top:1px solid #e2e8f0"></div>

                    <!-- 12. Terms & Privacy Section (Mandatory) -->
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

                <!-- 13. Submit Button: Request Trading Account -->
                <div class="submit-section">
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-lg btn-block register-submit-btn">
                        <span>@t('auth.btn_request_trading_account', 'Request Trading Account')</span>
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

    </div>
</div>
@endsection

@push('styles')
<style>
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
    max-width: 580px;
    margin: 0 auto;
    line-height: 1.45;
}
.register-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 34px 28px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.06), 0 8px 10px -6px rgba(0,0,0,0.04);
    box-sizing: border-box;
    width: 100%;
}
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
    box-sizing: border-box;
    min-height: 140px;
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
.ctype-radio.selected {
    border-color: #2563eb;
    background: #eff6ff;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.12);
}
.section-divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 26px 0 18px 0;
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
}
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
}
.signin-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}
@media (max-width: 640px) {
    .register-page-wrapper {
        padding: 16px 12px 36px 12px;
    }
    .register-card {
        padding: 20px 16px;
        border-radius: 16px;
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
    .form-grid-2 {
        grid-template-columns: 1fr;
        gap: 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function toggleTradingExistingRef(val) {
    const refEl = document.getElementById('tradingExistingRefBlock');
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
            <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
        `;
    } else {
        btn.innerHTML = `
            <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
        `;
    }
}

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
    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('emailFeedback');
    const emailServerError = document.getElementById('emailServerError');

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
