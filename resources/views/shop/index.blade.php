@extends('layouts.app')
@section('title', __t('shop.meta_title', 'Products Catalogue — MST Import & Export Sdn. Bhd.'))
@section('meta_description', __t('shop.meta_desc', 'Explore seafood, meat, frozen foods, food ingredients and specialty products — with customised sourcing available across Malaysia, Singapore and regional markets.'))

@section('content')
<!-- Page Header / Hero -->
<div class="products-hero-section">
    <div class="products-hero-pattern" aria-hidden="true"></div>
    <div class="container" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:12px">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none;font-size:0.85rem">
                🏠 @t('nav.home', 'Home')
            </a>
            <span class="breadcrumb-sep" style="color:#60a5fa;margin:0 6px">›</span>
            <span style="font-weight:600;color:#ffffff;font-size:0.85rem">@t('shop.catalogue_title', 'Products Catalogue')</span>
        </div>

        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:20px">
            <div style="max-width:780px">
                <!-- Top USPs -->
                <div class="products-hero-usps">
                    <span class="hero-usp-pill">
                        ❄️ @t('shop.usp_frozen', 'Temperature-Controlled Frozen Supply')
                    </span>
                    <span class="hero-usp-pill">
                        🌏 @t('shop.usp_sourcing', 'Local & International Sourcing')
                    </span>
                </div>

                <h1 class="products-hero-title">
                    @t('shop.catalogue_title', 'Products Catalogue')
                </h1>
                <p class="products-hero-subtitle">
                    @t('shop.catalogue_subtitle', 'Explore seafood, meat, frozen foods, food ingredients and selected specialty products — with customised sourcing available for items not currently listed.')
                </p>
            </div>

            <!-- Customer Group Status Badge -->
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                @auth
                    <div class="user-tier-pill">
                        ⭐ {{ match($group) {
                            'retail' => __t('shop.retail_tier', 'Retail / Walk-in Tier'),
                            'wholesale' => __t('shop.wholesale_tier', 'Wholesale Tier'),
                            'trading' => __t('shop.trading_tier', 'Trading Partner Tier'),
                            default => ucfirst($group) . ' Tier',
                        } }}
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top:28px;padding-bottom:72px">

    @php
        $selectedCatSlug = request('category');
        $selectedSubSlug = request('subcategory');

        // Resolve active parent category
        $activeParentCat = null;
        if ($selectedCatSlug) {
            $activeParentCat = $parentCategories->firstWhere('slug', $selectedCatSlug);
            if (!$activeParentCat) {
                // Check if it was a subcategory slug passed in category
                foreach ($parentCategories as $pCat) {
                    if ($pCat->children->contains('slug', $selectedCatSlug)) {
                        $activeParentCat = $pCat;
                        $selectedSubSlug = $selectedCatSlug;
                        break;
                    }
                }
            }
        } elseif ($selectedSubSlug) {
            foreach ($parentCategories as $pCat) {
                if ($pCat->children->contains('slug', $selectedSubSlug)) {
                    $activeParentCat = $pCat;
                    break;
                }
            }
        }

        $activeSubCat = $activeParentCat && $selectedSubSlug ? $activeParentCat->children->firstWhere('slug', $selectedSubSlug) : null;
        
        $catIcons = [
            'seafood' => '🦐',
            'meat' => '🥩',
            'frozen-food' => '🥟',
            'food-ingredients' => '🧂',
            'cuisine-ingredients' => '🍳',
            'desserts' => '🍡',
        ];

        $currentCustomerType = request('customer_type', (auth()->check() && in_array($group, ['wholesale', 'trading'])) ? 'wholesale' : 'retail');
        $hasAnyFilter = request('search') || request('category') || request('subcategory') || request('origin') || request('brand') || request('pack_size') || request('availability') || (request('sort') && request('sort') !== 'sort_order');
        
        $activeFilterCount = 0;
        if (!empty($selectedCatSlug)) $activeFilterCount++;
        if (!empty($selectedSubSlug)) $activeFilterCount++;
        if (!empty(request('origin'))) $activeFilterCount++;
        if (!empty(request('brand'))) $activeFilterCount++;
        if (!empty(request('pack_size'))) $activeFilterCount++;
        if (!empty(request('availability'))) $activeFilterCount++;
    @endphp

    <!-- ─── 1. Customer Type Selector & Business Pricing Banner ─── -->
    <div class="customer-type-bar">
        <div class="customer-type-label">
            <span>@t('shop.shopping_for', 'Shopping for:')</span>
        </div>
        <div class="customer-type-toggle">
            <button type="button" 
               onclick="setCustomerType('retail')" 
               class="cust-type-btn {{ $currentCustomerType === 'retail' ? 'active' : '' }}">
                <span>🛍️ @t('shop.type_retail', 'Retail / Walk-in')</span>
            </button>
            <button type="button" 
               onclick="setCustomerType('wholesale')" 
               class="cust-type-btn {{ $currentCustomerType === 'wholesale' ? 'active' : '' }}">
                <span>🏢 @t('shop.type_wholesale', 'Wholesale / Business')</span>
            </button>
        </div>
    </div>

    <!-- Business Pricing Sign-in Callout if Wholesale selected & not logged in as wholesale -->
    @if($currentCustomerType === 'wholesale' && (!auth()->check() || $group === 'retail'))
        <div class="business-pricing-banner" id="businessPricingBanner">
            <div class="business-banner-left">
                <div class="business-banner-icon">💼</div>
                <div>
                    <h3 class="business-banner-title">
                        @t('shop.business_banner_title', 'Buying for Business? Sign in for Wholesale & Trading Pricing.')
                    </h3>
                    <p class="business-banner-sub">
                        @t('shop.business_banner_desc', 'Verified commercial accounts access volume tier pricing, flexible MOQ, consolidated cold-chain delivery and invoice terms.')
                    </p>
                </div>
            </div>
            <div class="business-banner-actions">
                <a href="{{ route('login') }}" class="btn-biz-signin">
                    @t('auth.sign_in', 'Sign In')
                </a>
                <a href="{{ route('quotations.create') }}" class="btn-biz-access">
                    @t('shop.request_wholesale_access', 'Request Wholesale Access →')
                </a>
            </div>
        </div>
    @endif

    <!-- ─── 2. Search & Multi-Filter Bar ─── -->
    <div class="shop-filter-bar">
        <form method="GET" action="{{ route('shop.index') }}" class="shop-filter-form" id="shopFilterForm" onsubmit="event.preventDefault(); applyShopFilters();">
            <input type="hidden" name="customer_type" id="hidden_customer_type" value="{{ $currentCustomerType }}">
            <input type="hidden" name="category" id="hidden_category" value="{{ $selectedCatSlug }}">
            <input type="hidden" name="subcategory" id="hidden_subcategory" value="{{ $selectedSubSlug }}">
            <input type="hidden" name="origin" id="hidden_origin" value="{{ request('origin') }}">
            <input type="hidden" name="brand" id="hidden_brand" value="{{ request('brand') }}">
            <input type="hidden" name="pack_size" id="hidden_pack_size" value="{{ request('pack_size') }}">
            <input type="hidden" name="availability" id="hidden_availability" value="{{ request('availability') }}">
            <input type="hidden" name="sort" id="hiddenSortInput" value="{{ request('sort', 'sort_order') }}">

            <!-- Search input & Mobile Filter Toggle Row -->
            <div class="shop-search-filter-controls">
                <div class="shop-search-wrapper">
                    <svg class="search-svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="search" id="shopMainSearchInput" class="shop-main-search-input" 
                           placeholder="@t('shop.search_placeholder_master', 'Search by product name, category, brand or item code...')" 
                           value="{{ request('search') }}" autocomplete="off">
                    <button type="button" class="search-clear-btn" id="searchClearBtn" onclick="clearSearchInput(event)" style="{{ request('search') ? '' : 'display:none;' }}" title="Clear">✕</button>
                </div>

                <!-- Mobile Filter Toggle Button -->
                <button type="button" class="btn-mobile-filter-toggle {{ $activeFilterCount > 0 ? 'has-active-filters' : '' }}" id="mobileFilterToggleBtn" onclick="toggleMobileFilters()" aria-label="Toggle Filters">
                    <span class="btn-filter-content">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="4" y1="21" x2="4" y2="14"></line>
                            <line x1="4" y1="10" x2="4" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12" y2="3"></line>
                            <line x1="20" y1="21" x2="20" y2="16"></line>
                            <line x1="20" y1="12" x2="20" y2="3"></line>
                            <line x1="1" y1="14" x2="7" y2="14"></line>
                            <line x1="9" y1="8" x2="15" y2="8"></line>
                            <line x1="17" y1="16" x2="23" y2="16"></line>
                        </svg>
                        <span class="btn-filter-text">@t('shop.filters_btn', 'Filters')</span>
                        <span class="mobile-filter-count-badge" id="mobileFilterCountBadge" style="{{ $activeFilterCount > 0 ? 'display:inline-flex;' : 'display:none;' }}">{{ $activeFilterCount }}</span>
                    </span>
                    <svg class="mobile-filter-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
            </div>

            <!-- 6 Searchable Dropdown Filters -->
            <div class="shop-filters-row" id="shopFiltersRow">
                <!-- 1. Parent Category Dropdown -->
                <div class="searchable-dropdown" id="dropdown-category" data-name="category">
                    <button type="button" class="searchable-dropdown-trigger {{ $selectedCatSlug ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('category')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon">{{ $activeParentCat ? ($catIcons[$activeParentCat->slug] ?? '📁') : '📁' }}</span>
                            <span class="dropdown-trigger-text" id="label-category">{{ $activeParentCat ? $activeParentCat->name : 'Parent Category' }}</span>
                        </span>
                        <span class="dropdown-trigger-arrows">
                            @if($selectedCatSlug)
                                <span class="dropdown-clear-btn" onclick="event.stopPropagation(); clearDropdownValue('category')" title="Clear">✕</span>
                            @endif
                            <svg class="dropdown-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                    </button>
                    <div class="searchable-dropdown-menu" id="menu-category">
                        <div class="dropdown-search-header">
                            <svg class="dropdown-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" class="dropdown-search-input" placeholder="Search parent category..." oninput="filterDropdownOptions('category', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-category">
                            <button type="button" class="dropdown-option-item {{ empty($selectedCatSlug) ? 'selected' : '' }}" data-value="" data-label="All Parent Categories" onclick="selectDropdownOption('category', '', 'Parent Category')">
                                <span class="option-name">🌟 All Parent Categories</span>
                                @if(empty($selectedCatSlug)) <span class="option-check">✓</span> @endif
                            </button>
                            @foreach($parentCategories as $pCat)
                                <button type="button" class="dropdown-option-item {{ $selectedCatSlug === $pCat->slug ? 'selected' : '' }}" data-value="{{ $pCat->slug }}" data-label="{{ $pCat->name }}" onclick="selectDropdownOption('category', '{{ $pCat->slug }}', '{{ addslashes($pCat->name) }}')">
                                    <span class="option-name">{{ $catIcons[$pCat->slug] ?? '📦' }} {{ $pCat->name }}</span>
                                    @if($selectedCatSlug === $pCat->slug) <span class="option-check">✓</span> @endif
                                </button>
                            @endforeach
                            <div class="dropdown-no-results" style="display:none;"><span>No categories found</span></div>
                        </div>
                    </div>
                </div>

                <!-- 2. Child Category Dropdown -->
                <div class="searchable-dropdown" id="dropdown-subcategory" data-name="subcategory">
                    <button type="button" class="searchable-dropdown-trigger {{ $selectedSubSlug ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('subcategory')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon">📂</span>
                            <span class="dropdown-trigger-text" id="label-subcategory">{{ $activeSubCat ? $activeSubCat->name : 'Child category' }}</span>
                        </span>
                        <span class="dropdown-trigger-arrows">
                            @if($selectedSubSlug)
                                <span class="dropdown-clear-btn" onclick="event.stopPropagation(); clearDropdownValue('subcategory')" title="Clear">✕</span>
                            @endif
                            <svg class="dropdown-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                    </button>
                    <div class="searchable-dropdown-menu" id="menu-subcategory">
                        <div class="dropdown-search-header">
                            <svg class="dropdown-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" class="dropdown-search-input" placeholder="Search child category..." oninput="filterDropdownOptions('subcategory', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-subcategory">
                            <button type="button" class="dropdown-option-item {{ empty($selectedSubSlug) ? 'selected' : '' }}" data-value="" data-label="All Child Categories" onclick="selectDropdownOption('subcategory', '', 'Child category')">
                                <span class="option-name">📂 All Child Categories</span>
                                @if(empty($selectedSubSlug)) <span class="option-check">✓</span> @endif
                            </button>
                            @if($activeParentCat && $activeParentCat->children->count())
                                @foreach($activeParentCat->children as $cCat)
                                    <button type="button" class="dropdown-option-item {{ $selectedSubSlug === $cCat->slug ? 'selected' : '' }}" data-value="{{ $cCat->slug }}" data-label="{{ $cCat->name }}" onclick="selectDropdownOption('subcategory', '{{ $cCat->slug }}', '{{ addslashes($cCat->name) }}')">
                                        <span class="option-name">{{ $cCat->name }}</span>
                                        @if($selectedSubSlug === $cCat->slug) <span class="option-check">✓</span> @endif
                                    </button>
                                @endforeach
                            @else
                                @foreach($subcategories as $sCat)
                                    <button type="button" class="dropdown-option-item {{ $selectedSubSlug === $sCat->slug ? 'selected' : '' }}" data-value="{{ $sCat->slug }}" data-label="{{ $sCat->name }} {{ $sCat->parent?->name }}" onclick="selectDropdownOption('subcategory', '{{ $sCat->slug }}', '{{ addslashes($sCat->name) }}')">
                                        <span class="option-name">{{ $sCat->name }} <small style="color:#94a3b8">({{ $sCat->parent?->name ?? 'Cat' }})</small></span>
                                        @if($selectedSubSlug === $sCat->slug) <span class="option-check">✓</span> @endif
                                    </button>
                                @endforeach
                            @endif
                            <div class="dropdown-no-results" style="display:none;"><span>No subcategories found</span></div>
                        </div>
                    </div>
                </div>

                <!-- 3. Origin Dropdown -->
                <div class="searchable-dropdown" id="dropdown-origin" data-name="origin">
                    <button type="button" class="searchable-dropdown-trigger {{ request('origin') ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('origin')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon">🌍</span>
                            <span class="dropdown-trigger-text" id="label-origin">{{ request('origin') ?: 'Origin' }}</span>
                        </span>
                        <span class="dropdown-trigger-arrows">
                            @if(request('origin'))
                                <span class="dropdown-clear-btn" onclick="event.stopPropagation(); clearDropdownValue('origin')" title="Clear">✕</span>
                            @endif
                            <svg class="dropdown-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                    </button>
                    <div class="searchable-dropdown-menu" id="menu-origin">
                        <div class="dropdown-search-header">
                            <svg class="dropdown-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" class="dropdown-search-input" placeholder="Search origin..." oninput="filterDropdownOptions('origin', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-origin">
                            <button type="button" class="dropdown-option-item {{ empty(request('origin')) ? 'selected' : '' }}" data-value="" data-label="All Origins" onclick="selectDropdownOption('origin', '', 'Origin')">
                                <span class="option-name">🌍 All Origins</span>
                                @if(empty(request('origin'))) <span class="option-check">✓</span> @endif
                            </button>
                            @foreach($availableOrigins as $orig)
                                <button type="button" class="dropdown-option-item {{ request('origin') === $orig ? 'selected' : '' }}" data-value="{{ $orig }}" data-label="{{ $orig }}" onclick="selectDropdownOption('origin', '{{ addslashes($orig) }}', '{{ addslashes($orig) }}')">
                                    <span class="option-name">{{ $orig }}</span>
                                    @if(request('origin') === $orig) <span class="option-check">✓</span> @endif
                                </button>
                            @endforeach
                            <div class="dropdown-no-results" style="display:none;"><span>No origins found</span></div>
                        </div>
                    </div>
                </div>

                <!-- 4. Brand Dropdown -->
                <div class="searchable-dropdown" id="dropdown-brand" data-name="brand">
                    <button type="button" class="searchable-dropdown-trigger {{ request('brand') ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('brand')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon">🏷️</span>
                            <span class="dropdown-trigger-text" id="label-brand">{{ request('brand') ?: 'Brand' }}</span>
                        </span>
                        <span class="dropdown-trigger-arrows">
                            @if(request('brand'))
                                <span class="dropdown-clear-btn" onclick="event.stopPropagation(); clearDropdownValue('brand')" title="Clear">✕</span>
                            @endif
                            <svg class="dropdown-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                    </button>
                    <div class="searchable-dropdown-menu" id="menu-brand">
                        <div class="dropdown-search-header">
                            <svg class="dropdown-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" class="dropdown-search-input" placeholder="Search brand..." oninput="filterDropdownOptions('brand', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-brand">
                            <button type="button" class="dropdown-option-item {{ empty(request('brand')) ? 'selected' : '' }}" data-value="" data-label="All Brands" onclick="selectDropdownOption('brand', '', 'Brand')">
                                <span class="option-name">🏷️ All Brands</span>
                                @if(empty(request('brand'))) <span class="option-check">✓</span> @endif
                            </button>
                            @foreach($availableBrands as $br)
                                <button type="button" class="dropdown-option-item {{ request('brand') === $br ? 'selected' : '' }}" data-value="{{ $br }}" data-label="{{ $br }}" onclick="selectDropdownOption('brand', '{{ addslashes($br) }}', '{{ addslashes($br) }}')">
                                    <span class="option-name">{{ $br }}</span>
                                    @if(request('brand') === $br) <span class="option-check">✓</span> @endif
                                </button>
                            @endforeach
                            <div class="dropdown-no-results" style="display:none;"><span>No brands found</span></div>
                        </div>
                    </div>
                </div>

                <!-- 5. Pack Size Dropdown -->
                <div class="searchable-dropdown" id="dropdown-pack_size" data-name="pack_size">
                    <button type="button" class="searchable-dropdown-trigger {{ request('pack_size') ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('pack_size')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon">⚖️</span>
                            <span class="dropdown-trigger-text" id="label-pack_size">{{ request('pack_size') ?: 'Pack Size' }}</span>
                        </span>
                        <span class="dropdown-trigger-arrows">
                            @if(request('pack_size'))
                                <span class="dropdown-clear-btn" onclick="event.stopPropagation(); clearDropdownValue('pack_size')" title="Clear">✕</span>
                            @endif
                            <svg class="dropdown-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                    </button>
                    <div class="searchable-dropdown-menu" id="menu-pack_size">
                        <div class="dropdown-search-header">
                            <svg class="dropdown-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" class="dropdown-search-input" placeholder="Search pack size..." oninput="filterDropdownOptions('pack_size', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-pack_size">
                            <button type="button" class="dropdown-option-item {{ empty(request('pack_size')) ? 'selected' : '' }}" data-value="" data-label="All Pack Sizes" onclick="selectDropdownOption('pack_size', '', 'Pack Size')">
                                <span class="option-name">⚖️ All Pack Sizes</span>
                                @if(empty(request('pack_size'))) <span class="option-check">✓</span> @endif
                            </button>
                            @foreach($availablePackSizes as $ps)
                                <button type="button" class="dropdown-option-item {{ request('pack_size') === $ps ? 'selected' : '' }}" data-value="{{ $ps }}" data-label="{{ $ps }}" onclick="selectDropdownOption('pack_size', '{{ addslashes($ps) }}', '{{ addslashes($ps) }}')">
                                    <span class="option-name">{{ $ps }}</span>
                                    @if(request('pack_size') === $ps) <span class="option-check">✓</span> @endif
                                </button>
                            @endforeach
                            <div class="dropdown-no-results" style="display:none;"><span>No pack sizes found</span></div>
                        </div>
                    </div>
                </div>

                <!-- 6. Availability Dropdown -->
                <div class="searchable-dropdown" id="dropdown-availability" data-name="availability">
                    <button type="button" class="searchable-dropdown-trigger {{ request('availability') ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('availability')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon">{{ request('availability') === 'pre_order' ? '📦' : '🟢' }}</span>
                            <span class="dropdown-trigger-text" id="label-availability">
                                {{ request('availability') === 'in_stock' ? 'In Stock' : (request('availability') === 'pre_order' ? 'Pre-Order' : 'Availability') }}
                            </span>
                        </span>
                        <span class="dropdown-trigger-arrows">
                            @if(request('availability'))
                                <span class="dropdown-clear-btn" onclick="event.stopPropagation(); clearDropdownValue('availability')" title="Clear">✕</span>
                            @endif
                            <svg class="dropdown-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                    </button>
                    <div class="searchable-dropdown-menu" id="menu-availability">
                        <div class="dropdown-search-header">
                            <svg class="dropdown-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" class="dropdown-search-input" placeholder="Search availability..." oninput="filterDropdownOptions('availability', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-availability">
                            <button type="button" class="dropdown-option-item {{ empty(request('availability')) ? 'selected' : '' }}" data-value="" data-label="All Availability" onclick="selectDropdownOption('availability', '', 'Availability')">
                                <span class="option-name">⚡ All Availability</span>
                                @if(empty(request('availability'))) <span class="option-check">✓</span> @endif
                            </button>
                            <button type="button" class="dropdown-option-item {{ request('availability') === 'in_stock' ? 'selected' : '' }}" data-value="in_stock" data-label="In Stock" onclick="selectDropdownOption('availability', 'in_stock', 'In Stock')">
                                <span class="option-name">🟢 In Stock</span>
                                @if(request('availability') === 'in_stock') <span class="option-check">✓</span> @endif
                            </button>
                            <button type="button" class="dropdown-option-item {{ request('availability') === 'pre_order' ? 'selected' : '' }}" data-value="pre_order" data-label="Pre-Order / Custom Sourcing" onclick="selectDropdownOption('availability', 'pre_order', 'Pre-Order / Custom Sourcing')">
                                <span class="option-name">📦 Pre-Order / Custom Sourcing</span>
                                @if(request('availability') === 'pre_order') <span class="option-check">✓</span> @endif
                            </button>
                            <div class="dropdown-no-results" style="display:none;"><span>No options found</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ─── Main Shop Content (AJAX Updated) ─── -->
    <div id="shopMainContent">
        <div class="filter-loading-bar"></div>

        <!-- Active Filters Row & Results Header with Sort By -->
        <div class="shop-results-header" id="shopResultsHeader">
            <div class="results-header-left">
                <div class="results-count-text">
                    <span>@t('shop.showing', 'Showing') <strong>{{ $products->total() }}</strong> @t('shop.products_count', 'products')</span>
                    @if(request('search'))
                        <span style="color:#64748b">@t('shop.for_keyword', 'for') "<strong>{{ request('search') }}</strong>"</span>
                    @endif
                    @if($activeParentCat)
                        <span style="color:#2563eb;font-weight:700">· {{ $activeParentCat->name }} @if($activeSubCat) &rsaquo; {{ $activeSubCat->name }} @endif</span>
                    @endif
                </div>

                @if($hasAnyFilter)
                    <div class="active-chips-list">
                        @if(request('search'))
                            <a href="{{ route('shop.index', request()->except('search', 'page')) }}" class="active-filter-badge">
                                <span>Keyword: "{{ request('search') }}"</span>
                                <span class="badge-x">✕</span>
                            </a>
                        @endif
                        @if($selectedCatSlug)
                            <a href="{{ route('shop.index', request()->except('category', 'subcategory', 'page')) }}" class="active-filter-badge">
                                <span>Parent: {{ $activeParentCat?->name ?? $selectedCatSlug }}</span>
                                <span class="badge-x">✕</span>
                            </a>
                        @endif
                        @if($selectedSubSlug)
                            <a href="{{ route('shop.index', request()->except('subcategory', 'page')) }}" class="active-filter-badge">
                                <span>Child: {{ $activeSubCat?->name ?? $selectedSubSlug }}</span>
                                <span class="badge-x">✕</span>
                            </a>
                        @endif
                        @if(request('origin'))
                            <a href="{{ route('shop.index', request()->except('origin', 'page')) }}" class="active-filter-badge">
                                <span>Origin: {{ request('origin') }}</span>
                                <span class="badge-x">✕</span>
                            </a>
                        @endif
                        @if(request('brand'))
                            <a href="{{ route('shop.index', request()->except('brand', 'page')) }}" class="active-filter-badge">
                                <span>Brand: {{ request('brand') }}</span>
                                <span class="badge-x">✕</span>
                            </a>
                        @endif
                        @if(request('pack_size'))
                            <a href="{{ route('shop.index', request()->except('pack_size', 'page')) }}" class="active-filter-badge">
                                <span>Pack: {{ request('pack_size') }}</span>
                                <span class="badge-x">✕</span>
                            </a>
                        @endif
                        @if(request('availability'))
                            <a href="{{ route('shop.index', request()->except('availability', 'page')) }}" class="active-filter-badge">
                                <span>Availability: {{ request('availability') === 'in_stock' ? 'In Stock' : 'Pre-Order' }}</span>
                                <span class="badge-x">✕</span>
                            </a>
                        @endif
                        <a href="{{ route('shop.index', request()->only('customer_type')) }}" class="clear-all-link">@t('shop.clear_all', 'Reset All')</a>
                    </div>
                @endif
            </div>

            <div class="results-header-right">
                <!-- Custom Styled Sort By Dropdown -->
                <div class="searchable-dropdown sort-searchable-dropdown" id="dropdown-sort" data-name="sort">
                    <button type="button" class="searchable-dropdown-trigger sort-dropdown-trigger {{ request('sort') && request('sort') !== 'sort_order' ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('sort')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon">✨</span>
                            <span class="dropdown-trigger-text" id="label-sort">
                                @switch(request('sort', 'sort_order'))
                                    @case('price_asc')
                                        Price: Low to High
                                        @break
                                    @case('price_desc')
                                        Price: High to Low
                                        @break
                                    @case('name')
                                        Name A–Z
                                        @break
                                    @default
                                        Sort: Featured
                                @endswitch
                            </span>
                        </span>
                        <span class="dropdown-trigger-arrows">
                            <svg class="dropdown-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                    </button>
                    <div class="searchable-dropdown-menu sort-dropdown-menu" id="menu-sort">
                        <div class="dropdown-options-list" id="list-sort">
                            <button type="button" class="dropdown-option-item {{ request('sort', 'sort_order') === 'sort_order' ? 'selected' : '' }}" data-value="sort_order" data-label="Featured" onclick="selectDropdownOption('sort', 'sort_order', 'Sort: Featured')">
                                <span class="option-name">✨ Featured</span>
                                @if(request('sort', 'sort_order') === 'sort_order') <span class="option-check">✓</span> @endif
                            </button>
                            <button type="button" class="dropdown-option-item {{ request('sort') === 'price_asc' ? 'selected' : '' }}" data-value="price_asc" data-label="Price: Low to High" onclick="selectDropdownOption('sort', 'price_asc', 'Price: Low to High')">
                                <span class="option-name">💵 Price: Low to High</span>
                                @if(request('sort') === 'price_asc') <span class="option-check">✓</span> @endif
                            </button>
                            <button type="button" class="dropdown-option-item {{ request('sort') === 'price_desc' ? 'selected' : '' }}" data-value="price_desc" data-label="Price: High to Low" onclick="selectDropdownOption('sort', 'price_desc', 'Price: High to Low')">
                                <span class="option-name">💎 Price: High to Low</span>
                                @if(request('sort') === 'price_desc') <span class="option-check">✓</span> @endif
                            </button>
                            <button type="button" class="dropdown-option-item {{ request('sort') === 'name' ? 'selected' : '' }}" data-value="name" data-label="Name A–Z" onclick="selectDropdownOption('sort', 'name', 'Name A–Z')">
                                <span class="option-name">🔤 Name A–Z</span>
                                @if(request('sort') === 'name') <span class="option-check">✓</span> @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Product Cards Grid / Empty State Area -->
        <div id="shopProductsArea">
            @if($products->count())
                <div class="products-catalogue-grid">
                    @foreach($products as $product)
                        @php
                            $displayPrice = $product->getDisplayPrice($group);
                            $price = $displayPrice['amount'];
                            $priceFormatted = $displayPrice['formatted'];
                            
                            $tempLower = strtolower($product->storage_temp ?? '');
                            $isLive = str_contains($tempLower, 'live') || str_contains(strtolower($product->name), 'live');
                            $isChilled = str_contains($tempLower, 'chilled');
                            
                            $storageLabel = '❄️ -18°C Frozen';
                            if ($isLive) {
                                $storageLabel = '🦀 Live / Chilled';
                            } elseif ($isChilled) {
                                $storageLabel = '🧊 0°C to 4°C Chilled';
                            } elseif (str_contains(strtolower($product->name), 'iqf') || str_contains($tempLower, 'iqf')) {
                                $storageLabel = '❄️ -18°C IQF';
                            } elseif ($product->storage_temp) {
                                $storageLabel = '❄️ ' . $product->storage_temp;
                            }
                        @endphp

                        <div class="product-item-card">
                            <!-- Product Image Container -->
                            <div class="product-img-box">
                                <a href="{{ route('shop.show', $product) }}" class="product-img-link" title="{{ $product->name }}">
                                    @if($product->thumbnail)
                                        <img src="{{ cdn_storage($product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy" class="card-product-img">
                                    @else
                                        <div class="product-placeholder-icon">🐟</div>
                                    @endif
                                </a>

                                <!-- Floating Badges Top Left -->
                                <div class="card-badge-cluster">
                                    @if($product->is_featured)
                                        <span class="badge-featured">⭐ @t('shop.badge_featured', 'Featured')</span>
                                    @endif
                                    @if($product->origin)
                                        <span class="badge-origin">🌍 {{ $product->origin }}</span>
                                    @endif
                                </div>

                                <!-- Storage Condition Badge Bottom Left -->
                                <div class="card-storage-badge {{ $isLive ? 'storage-live' : '' }}">
                                    {{ $storageLabel }}
                                </div>

                                <!-- Quick View Button (Desktop Hover) -->
                                <button type="button" class="btn-card-quickview js-quickview-btn"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-category="{{ $product->category?->name ?? 'Seafood' }}"
                                    data-sku="{{ $product->sku ?? 'N/A' }}"
                                    data-origin="{{ $product->origin ?? '' }}"
                                    data-weight="{{ $product->weight ?? '' }}"
                                    data-unit="{{ $product->unit ?? '' }}"
                                    data-storage="{{ $storageLabel }}"
                                    data-price-formatted="{{ $priceFormatted }}"
                                    data-base-rm="{{ $product->getPriceForGroup($group) ?? 0 }}"
                                    data-manual-sgd="{{ $product->price_sgd ?? '' }}"
                                    data-manual-usd="{{ $product->price_usd ?? '' }}"
                                    data-manual-wholesale-sgd="{{ $product->wholesale_price_sgd ?? '' }}"
                                    data-manual-wholesale-usd="{{ $product->wholesale_price_usd ?? '' }}"
                                    data-group="{{ $group }}"
                                    data-moq="{{ $product->getMoqForGroup($group) > 1 ? ($product->getMoqForGroup($group) . ' ' . $product->unit) : '' }}"
                                    data-desc="{{ $product->short_description ?? $product->description ?? '' }}"
                                    data-image="{{ $product->thumbnail ? cdn_storage($product->thumbnail) : '' }}"
                                    data-url="{{ route('shop.show', $product) }}"
                                    data-rfq-url="{{ route('quotations.create', ['product' => $product->id]) }}"
                                >
                                    👁️ @t('shop.quick_view', 'Quick View')
                                </button>
                            </div>

                            <!-- Product Card Content -->
                            <div class="product-item-body">
                                <!-- Category Tag & SKU -->
                                <div class="card-meta-bar">
                                    <span class="card-cat-name">{{ $product->category?->name ?? 'General' }}</span>
                                    @if($product->sku)
                                        <span class="card-sku">{{ $product->sku }}</span>
                                    @endif
                                </div>

                                <!-- Product Title -->
                                <h3 class="product-item-title">
                                    <a href="{{ route('shop.show', $product) }}" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>

                                <!-- Pack Size / Weight -->
                                <div class="card-spec-row">
                                    @if($product->weight)
                                        <span class="spec-chip">📦 {{ $product->weight }}</span>
                                    @endif
                                    @if($product->brand && !str_contains($product->brand, 'SDN BHD'))
                                        <span class="spec-chip">🏷️ {{ $product->brand }}</span>
                                    @endif
                                </div>

                                <!-- Single Clean Price Display -->
                                <div class="card-pricing-block">
                                    @if($currentCustomerType === 'wholesale' && !auth()->check())
                                        <div class="wholesale-prompt-price">
                                            <span class="prompt-text">@t('shop.login_for_wholesale', 'Login for Wholesale Pricing')</span>
                                            <a href="{{ route('login') }}" class="prompt-link">@t('auth.sign_in', 'Sign In') &rarr;</a>
                                        </div>
                                    @elseif($price !== null)
                                        <div class="product-card-price js-currency-price"
                                             data-base-rm="{{ $product->getPriceForGroup($group) ?? 0 }}"
                                             data-manual-sgd="{{ $product->price_sgd ?? '' }}"
                                             data-manual-usd="{{ $product->price_usd ?? '' }}"
                                             @if(in_array($group, ['wholesale','trading']))
                                             data-manual-wholesale-sgd="{{ $product->wholesale_price_sgd ?? '' }}"
                                             data-manual-wholesale-usd="{{ $product->wholesale_price_usd ?? '' }}"
                                             data-group="{{ $group }}"
                                             @endif
                                        >
                                            <span class="price-amount price-val">{{ $priceFormatted }}</span>
                                            <span class="price-base-rm price-sub-myr" style="display:{{ ($currentCurrency !== 'MYR' && !empty($displayPrice['base_rm'])) ? 'block' : 'none' }}">
                                                RM {{ number_format($product->getPriceForGroup($group), 2) }}
                                            </span>
                                        </div>
                                        @if(in_array($group, ['wholesale','trading']) && $product->getMoqForGroup($group) > 1)
                                            <div class="moq-badge">MOQ: {{ $product->getMoqForGroup($group) }} {{ $product->unit }}</div>
                                        @endif
                                    @else
                                        <div class="price-on-request">
                                            @t('shop.price_on_request', 'Price on Request (RFQ)')
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Action Buttons -->
                                <div class="card-action-btns">
                                    @if($price !== null)
                                        <form action="{{ route('cart.add') }}" method="POST" class="product-cart-form" style="flex:1">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="{{ $product->getMoqForGroup($group) }}">
                                            <button type="submit" class="btn-card-add">
                                                🛒 @t('shop.add_to_cart', 'Add to Cart')
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('quotations.create', ['product' => $product->id]) }}" class="btn-card-rfq" style="flex:1">
                                            📋 @t('shop.request_quote', 'Request a Quote')
                                        </a>
                                    @endif

                                    <button type="button" class="btn-card-quickview-mobile js-quickview-btn"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-category="{{ $product->category?->name ?? 'Seafood' }}"
                                        data-sku="{{ $product->sku ?? 'N/A' }}"
                                        data-origin="{{ $product->origin ?? '' }}"
                                        data-weight="{{ $product->weight ?? '' }}"
                                        data-unit="{{ $product->unit ?? '' }}"
                                        data-storage="{{ $storageLabel }}"
                                        data-price-formatted="{{ $priceFormatted }}"
                                        data-base-rm="{{ $product->getPriceForGroup($group) ?? 0 }}"
                                        data-manual-sgd="{{ $product->price_sgd ?? '' }}"
                                        data-manual-usd="{{ $product->price_usd ?? '' }}"
                                        data-manual-wholesale-sgd="{{ $product->wholesale_price_sgd ?? '' }}"
                                        data-manual-wholesale-usd="{{ $product->wholesale_price_usd ?? '' }}"
                                        data-group="{{ $group }}"
                                        data-moq="{{ $product->getMoqForGroup($group) > 1 ? ($product->getMoqForGroup($group) . ' ' . $product->unit) : '' }}"
                                        data-desc="{{ $product->short_description ?? $product->description ?? '' }}"
                                        data-image="{{ $product->thumbnail ? cdn_storage($product->thumbnail) : '' }}"
                                        data-url="{{ route('shop.show', $product) }}"
                                        data-rfq-url="{{ route('quotations.create', ['product' => $product->id]) }}"
                                        title="Quick View"
                                    >
                                        👁️
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="shop-pagination-wrap" style="margin-top:40px;display:flex;justify-content:center">
                        {{ $products->links('vendor.pagination.custom') }}
                    </div>
                @endif

            @else
                <!-- ─── 5. Empty State With Custom Sourcing Prompt ─── -->
                <div class="empty-sourcing-box">
                    <div class="empty-icon-wrap">🔍</div>
                    <h2 class="empty-title">
                        @t('shop.cant_find_head', "Can't Find What You Need?")
                    </h2>
                    <h3 class="empty-subtitle">
                        @t('shop.we_can_source', 'We Can Source It For You.')
                    </h3>
                    <p class="empty-desc">
                        @t('shop.sourcing_empty_desc', "Can't find the product, size, origin or specification you're looking for? Tell us what you need and our sourcing team can help coordinate availability and quotation.")
                    </p>
                    <div class="empty-actions">
                        <a href="{{ route('contact') }}#quote" class="btn-sourcing-primary">
                            @t('shop.request_custom_sourcing', 'Request Custom Sourcing →')
                        </a>
                        <a href="{{ route('shop.index') }}" class="btn-sourcing-secondary clear-all-link">
                            @t('shop.view_all_products', 'View All Products')
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- ─── 6. Custom Sourcing Section (Always visible) ─── -->
    <div class="custom-sourcing-banner">
        <div class="sourcing-banner-content">
            <div class="sourcing-pill-tag">
                ✨ @t('shop.sourcing_tag', 'SOURCING & SUPPLY PARTNER')
            </div>
            <h2 class="sourcing-banner-head">
                @t('shop.cant_find_head', "Can't Find What You Need?")
            </h2>
            <h3 class="sourcing-banner-subhead">
                @t('shop.we_can_source', 'We Can Source It For You.')
            </h3>
            <p class="sourcing-banner-p">
                @t('shop.sourcing_empty_desc', "Can't find the product, size, origin or specification you're looking for? Tell us what you need and our sourcing team can help coordinate availability and quotation.")
            </p>
        </div>
        <div class="sourcing-banner-btn-wrap">
            <a href="{{ route('contact') }}#quote" class="btn-sourcing-action">
                @t('shop.request_custom_sourcing', 'Request Custom Sourcing →')
            </a>
        </div>
    </div>

    <!-- ─── 7. Wholesale / Business CTA (Need Regular Supply?) ─── -->
    <div class="wholesale-supply-cta">
        <div class="wholesale-cta-content">
            <h2 class="wholesale-cta-title">
                @t('shop.need_regular_supply', 'Need Regular Supply?')
            </h2>
            <p class="wholesale-cta-desc">
                @t('shop.regular_supply_desc', 'For restaurants, hotels, caterers, retailers, traders and other commercial buyers, MST provides wholesale supply, bulk ordering and customised sourcing support.')
            </p>
        </div>
        <div class="wholesale-cta-btns">
            <a href="{{ route('register') }}" class="btn-wholesale-access">
                @t('shop.request_wholesale_access_btn', 'Request Wholesale Access')
            </a>
            <a href="{{ route('quotations.create') }}" class="btn-wholesale-quote">
                @t('shop.request_a_quote', 'Request a Quote')
            </a>
        </div>
    </div>

</div>

<!-- ─── Quick View Modal ─── -->
<div class="quickview-modal-backdrop" id="quickViewBackdrop" onclick="handleQuickViewBackdropClick(event)">
    <div class="quickview-modal-card" id="quickViewCard" role="dialog" aria-modal="true">
        <button type="button" class="quickview-close-btn" onclick="closeQuickViewModal()" aria-label="Close Quick View">✕</button>
        <div class="quickview-grid">
            <div class="quickview-media">
                <img src="" id="qvImg" alt="Product Image" style="display:none;width:100%;height:100%;object-fit:cover;">
                <div id="qvImgPlaceholder" style="display:none;width:100%;height:100%;align-items:center;justify-content:center;font-size:3rem;background:#f1f5f9;color:#94a3b8">🐟</div>
                <span class="quickview-origin-tag" id="qvOriginTag" style="display:none"></span>
            </div>
            <div class="quickview-details">
                <div class="quickview-category" id="qvCategory"></div>
                <h2 class="quickview-title" id="qvTitle"></h2>
                <div class="quickview-sku-bar">
                    <span>@t('shop.sku_label', 'SKU:') <strong id="qvSku"></strong></span>
                    <span>@t('shop.storage_label', 'Storage:') <strong id="qvStorage"></strong></span>
                </div>
                <div class="quickview-price-box">
                    <div style="display:flex;flex-direction:column">
                        <span class="quickview-price" id="qvPrice"></span>
                        <span class="quickview-base-rm" id="qvBaseRm" style="font-size:0.8rem;color:#64748b;font-weight:500"></span>
                    </div>
                    <div id="qvMoqBadge" style="display:none;background:#eff6ff;color:#1d4ed8;font-size:0.75rem;font-weight:700;padding:3px 8px;border-radius:6px"></div>
                </div>
                <p class="quickview-desc" id="qvDesc"></p>
                <div class="quickview-actions">
                    <form action="{{ route('cart.add') }}" method="POST" id="qvCartForm" style="flex:1">
                        @csrf
                        <input type="hidden" name="product_id" id="qvProductId">
                        <div style="display:flex;gap:10px">
                            <input type="number" name="quantity" id="qvQty" value="1" min="1" class="quickview-qty-input" style="width:70px;height:42px;border:1px solid #cbd5e1;border-radius:8px;text-align:center;font-weight:600">
                            <button type="submit" class="btn btn-primary" style="flex:1;height:42px;background:#2563eb;border-color:#2563eb;font-weight:600;border-radius:8px;cursor:pointer">
                                🛒 @t('shop.add_to_cart', 'Add to Cart')
                            </button>
                        </div>
                    </form>
                    <a href="#" id="qvRfqLink" class="btn btn-primary" onclick="closeQuickViewModal()" style="display:none;flex:1;height:42px;background:#2563eb;color:#fff;font-weight:600;border-radius:8px;text-decoration:none;align-items:center;justify-content:center">
                        📋 @t('shop.request_quote', 'Request a Quote')
                    </a>
                    <a href="#" id="qvDetailsLink" class="btn btn-secondary" onclick="closeQuickViewModal()" style="height:42px;display:inline-flex;align-items:center;justify-content:center;padding:0 16px;font-weight:600;border-radius:8px;text-decoration:none">
                        @t('shop.details', 'Full Details →')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ─── Quick View Modal Overlay & Card ───────────────────────────── */
.quickview-modal-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(10, 25, 47, 0.75) !important;
    backdrop-filter: blur(8px) !important;
    -webkit-backdrop-filter: blur(8px) !important;
    z-index: 99999999 !important;
    display: none !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 20px !important;
    opacity: 0;
    transition: opacity 0.22s ease;
    box-sizing: border-box !important;
    overflow-y: auto !important;
}
.quickview-modal-backdrop.active,
.quickview-modal-backdrop.show {
    display: flex !important;
    opacity: 1 !important;
}
.quickview-modal-card {
    background: #ffffff !important;
    border-radius: 20px !important;
    max-width: 840px !important;
    width: 100% !important;
    max-height: 90vh !important;
    overflow-y: auto !important;
    position: relative !important;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45) !important;
    animation: qvPop 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
    border: 1px solid #e2e8f0 !important;
    box-sizing: border-box !important;
    margin: auto !important;
    z-index: 100000000 !important;
}
@keyframes qvPop {
    from { opacity: 0; transform: scale(0.95) translateY(12px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.quickview-close-btn {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    font-size: 1.1rem;
    cursor: pointer;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #475569;
    transition: all 0.15s ease;
}
.quickview-close-btn:hover {
    background: #fee2e2;
    color: #ef4444;
    border-color: #fecaca;
}
.quickview-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
}
@media(max-width:768px) {
    .quickview-grid { grid-template-columns: 1fr; }
    .quickview-media { height: 240px !important; min-height: 240px !important; }
    .quickview-details { padding: 18px !important; }
}
.quickview-media {
    position: relative;
    background: #f8fafc;
    height: 100%;
    min-height: 380px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.quickview-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.quickview-origin-tag {
    position: absolute;
    bottom: 14px;
    left: 14px;
    background: rgba(10, 25, 47, 0.88);
    backdrop-filter: blur(4px);
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.15);
}
.quickview-details {
    padding: 28px;
    display: flex;
    flex-direction: column;
}
.quickview-category {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #2563eb;
    letter-spacing: 0.04em;
    margin-bottom: 4px;
}
.quickview-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.25;
    margin: 0 0 10px 0;
}
.quickview-sku-bar {
    display: flex;
    gap: 14px;
    font-size: 0.78rem;
    color: #64748b;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}
.quickview-price-box {
    display: flex;
    align-items: baseline;
    gap: 12px;
    margin-bottom: 14px;
}
.quickview-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1d4ed8;
}
.quickview-base-rm {
    font-size: 0.82rem;
    color: #64748b;
    font-weight: 500;
}
.quickview-desc {
    font-size: 0.88rem;
    color: #475569;
    line-height: 1.55;
    margin-bottom: 20px;
}
.quickview-actions {
    display: flex;
    gap: 10px;
    margin-top: auto;
    align-items: center;
}
.quickview-actions .btn-primary {
    background: #f59e0b;
    color: #091a36;
    border: none;
    font-weight: 700;
    border-radius: 10px;
    transition: all 0.15s ease;
}
.quickview-actions .btn-primary:hover {
    background: #d97706;
    color: #091a36;
}
.quickview-actions .btn-secondary {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.15s ease;
}
.quickview-actions .btn-secondary:hover {
    background: #e2e8f0;
    color: #0f172a;
}
/* ─── Hero Section ─────────────────────────────────────────────── */
.products-hero-section {
    position: relative;
    background: linear-gradient(135deg, #07152b 0%, #0c234b 45%, #1d4ed8 100%);
    color: #ffffff;
    border-bottom: 1px solid rgba(37, 99, 235, 0.35);
    padding-top: calc(78px + 28px);
    padding-bottom: 36px;
    overflow: hidden;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.products-hero-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.08;
    background-image: radial-gradient(#38bdf8 1.5px, transparent 1.5px);
    background-size: 24px 24px;
    pointer-events: none;
}
.products-hero-usps {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}
.hero-usp-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(56, 189, 248, 0.16);
    border: 1px solid rgba(186, 230, 253, 0.35);
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.76rem;
    font-weight: 500;
    color: #7dd3fc;
    letter-spacing: 0.02em;
}
.products-hero-title {
    font-family: var(--font-heading, inherit);
    font-size: clamp(1.85rem, 3.8vw, 2.5rem);
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 8px;
    letter-spacing: -0.02em;
    line-height: 1.2;
}
.products-hero-subtitle {
    font-size: 0.96rem;
    color: #e0f2fe;
    line-height: 1.6;
    margin: 0;
    font-weight: 400;
}
.user-tier-pill {
    font-size: 0.84rem;
    padding: 7px 16px;
    border-radius: 999px;
    background: rgba(9, 26, 54, 0.85);
    color: #7dd3fc;
    border: 1px solid rgba(59, 130, 246, 0.6);
    backdrop-filter: blur(8px);
    font-weight: 600;
}

/* ─── 1. Customer Type Bar ───────────────────────────────────────── */
.customer-type-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    background: #ffffff;
    padding: 12px 20px;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    margin-bottom: 20px;
    -webkit-font-smoothing: antialiased;
}
.customer-type-label {
    font-size: 0.88rem;
    font-weight: 600;
    color: #0f172a;
    letter-spacing: 0.01em;
}
.customer-type-toggle {
    display: inline-flex;
    background: #f1f5f9;
    padding: 4px;
    border-radius: 12px;
    gap: 4px;
}
.cust-type-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 9px;
    font-size: 0.82rem;
    font-weight: 500;
    text-decoration: none;
    color: #475569;
    transition: all 0.18s ease;
}
.cust-type-btn.active {
    background: #2563eb;
    color: #ffffff;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35);
}

/* ─── Business Banner ───────────────────────────────────────────── */
.business-pricing-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    background: linear-gradient(135deg, #091a36 0%, #173873 100%);
    color: #ffffff;
    padding: 18px 24px;
    border-radius: 16px;
    margin-bottom: 24px;
    border: 1px solid rgba(59, 130, 246, 0.4);
    box-shadow: 0 4px 16px rgba(9, 26, 54, 0.12);
}
.business-banner-left {
    display: flex;
    align-items: center;
    gap: 16px;
    max-width: 750px;
}
.business-banner-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(56, 189, 248, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}
.business-banner-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 4px;
}
.business-banner-sub {
    font-size: 0.84rem;
    color: #cbd5e1;
    margin: 0;
    line-height: 1.45;
}
.business-banner-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.btn-biz-signin {
    background: #ffffff;
    color: #0f172a;
    padding: 8px 18px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.84rem;
    text-decoration: none;
    transition: background 0.15s ease;
}
.btn-biz-signin:hover {
    background: #f1f5f9;
}
.btn-biz-access {
    background: #2563eb;
    color: #ffffff;
    padding: 8px 18px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.84rem;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4);
}

/* ─── 2. Search & Filter Bar ────────────────────────────────────── */
.shop-filter-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 24px;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);
}
.shop-search-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}
.search-svg {
    position: absolute;
    left: 14px;
    color: #64748b;
    pointer-events: none;
}
.shop-main-search-input {
    width: 100%;
    height: 46px;
    padding: 0 40px 0 42px;
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    font-size: 0.92rem;
    color: #0f172a;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    box-sizing: border-box;
}
.shop-main-search-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}
.search-clear-btn {
    position: absolute;
    right: 14px;
    color: #94a3b8;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.9rem;
}
.shop-filters-row {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 8px;
    position: relative;
}
.searchable-dropdown {
    position: relative;
    min-width: 0;
}
.searchable-dropdown-trigger {
    width: 100%;
    height: 42px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 0 10px;
    font-size: 0.82rem;
    font-weight: 500;
    color: #1e293b;
    background: #f8fafc;
    outline: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    transition: all 0.15s ease;
    text-align: left;
    box-sizing: border-box;
    -webkit-appearance: none;
    appearance: none;
}
.searchable-dropdown-trigger:hover {
    border-color: #3b82f6;
    background: #ffffff;
}
.searchable-dropdown-trigger.open {
    border-color: #2563eb;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}
.searchable-dropdown-trigger.has-value {
    border-color: #2563eb;
    background: #eff6ff;
    color: #1d4ed8;
}
.dropdown-trigger-content {
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 0;
    overflow: hidden;
}
.dropdown-trigger-icon {
    font-size: 0.95rem;
    flex-shrink: 0;
}
.dropdown-trigger-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 0.81rem;
}
.dropdown-trigger-arrows {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
}
.dropdown-clear-btn {
    font-size: 0.72rem;
    color: #64748b;
    padding: 2px 4px;
    border-radius: 4px;
    line-height: 1;
    transition: all 0.15s ease;
}
.dropdown-clear-btn:hover {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
}
.dropdown-chevron {
    color: #64748b;
    transition: transform 0.2s ease;
}
.searchable-dropdown-trigger.open .dropdown-chevron {
    transform: rotate(180deg);
}

/* Dropdown Menu Popup */
.searchable-dropdown-menu {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    min-width: 220px;
    max-width: 300px;
    width: 100%;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    box-shadow: 0 14px 35px -4px rgba(15, 23, 42, 0.16), 0 4px 12px rgba(0, 0, 0, 0.06);
    z-index: 1050;
    padding: 6px;
    display: none;
    opacity: 0;
    transform: translateY(-4px);
    transition: opacity 0.15s ease, transform 0.15s ease;
}
.searchable-dropdown-menu.show {
    display: block;
    opacity: 1;
    transform: translateY(0);
}
.searchable-dropdown:nth-child(n+5) .searchable-dropdown-menu {
    left: auto;
    right: 0;
}

.dropdown-search-header {
    position: relative;
    display: flex;
    align-items: center;
    margin-bottom: 6px;
    padding-bottom: 6px;
    border-bottom: 1px solid #f1f5f9;
}
.dropdown-search-icon {
    position: absolute;
    left: 10px;
    color: #94a3b8;
    pointer-events: none;
}
.dropdown-search-input {
    width: 100%;
    height: 34px;
    padding: 0 10px 0 30px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.8rem;
    color: #0f172a;
    background: #f8fafc;
    outline: none;
    box-sizing: border-box;
}
.dropdown-search-input:focus {
    border-color: #2563eb;
    background: #ffffff;
}

.dropdown-options-list {
    max-height: 220px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 2px;
    scrollbar-width: thin;
}
.dropdown-options-list::-webkit-scrollbar {
    width: 5px;
}
.dropdown-options-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.dropdown-option-item {
    width: 100%;
    border: none;
    background: transparent;
    padding: 8px 10px;
    border-radius: 8px;
    font-size: 0.81rem;
    font-weight: 400;
    color: #334155;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    text-align: left;
    transition: all 0.12s ease;
}
.dropdown-option-item:hover {
    background: #eff6ff;
    color: #1d4ed8;
    font-weight: 500;
}
.dropdown-option-item.selected {
    background: #dbeafe;
    color: #1e40af;
    font-weight: 600;
}
.option-name {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.option-check {
    font-size: 0.8rem;
    color: #2563eb;
    font-weight: 700;
    flex-shrink: 0;
}
.dropdown-no-results {
    padding: 12px;
    text-align: center;
    font-size: 0.78rem;
    color: #94a3b8;
}

/* Results Header & Sort */
.shop-results-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
}
.results-header-left {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.sort-searchable-dropdown {
    min-width: 190px;
}
.sort-dropdown-trigger {
    height: 38px;
    padding: 0 12px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 500;
    color: #334155;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    -webkit-appearance: none;
    appearance: none;
}
.sort-dropdown-trigger:hover {
    border-color: #3b82f6;
    color: #1d4ed8;
}
.sort-dropdown-menu {
    right: 0 !important;
    left: auto !important;
    min-width: 190px;
    max-width: 220px;
    top: calc(100% + 4px);
}

/* AJAX Smooth Loading Animation */
#shopMainContent {
    position: relative;
    transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
#shopMainContent.is-loading {
    opacity: 0.45;
    pointer-events: none;
}
.filter-loading-bar {
    position: absolute;
    top: -8px;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #2563eb, #38bdf8, #2563eb);
    background-size: 200% 100%;
    animation: loadingShimmer 1.2s infinite linear;
    border-radius: 999px;
    opacity: 0;
    transition: opacity 0.2s ease;
    pointer-events: none;
    z-index: 50;
}
#shopMainContent.is-loading .filter-loading-bar {
    opacity: 1;
}
@keyframes loadingShimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ─── 3. Main 7 Categories Tabs ──────────────────────────────────── */
.main-categories-nav {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 6px;
    margin-bottom: 16px;
    scrollbar-width: thin;
}
.main-categories-nav::-webkit-scrollbar {
    height: 4px;
}
.main-categories-nav::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.main-cat-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    color: #334155;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 500;
    white-space: nowrap;
    transition: all 0.18s ease;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);
}
.main-cat-tab:hover {
    border-color: #93c5fd;
    color: #1d4ed8;
    transform: translateY(-1px);
}
.main-cat-tab.active {
    background: #0f274a;
    border-color: #0f274a;
    color: #ffffff;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(15, 39, 74, 0.25);
}
.main-cat-icon {
    font-size: 1.1rem;
    line-height: 1;
}

/* Subcategories Pill Chips */
.subcategories-chips-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #f1f5f9;
    padding: 10px 16px;
    border-radius: 14px;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
    overflow-x: auto;
}
.subcategories-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #0f172a;
    white-space: nowrap;
}
.subcategories-scroll {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    scrollbar-width: none;
}
.subcategories-scroll::-webkit-scrollbar {
    display: none;
}
.subcat-chip {
    padding: 5px 14px;
    border-radius: 999px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #475569;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.15s ease;
}
.subcat-chip:hover {
    border-color: #2563eb;
    color: #2563eb;
}
.subcat-chip.active {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
    font-weight: 600;
}

/* ─── Results Header ─────────────────────────────────────────────── */
.shop-results-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e2e8f0;
}
.results-count-text {
    font-size: 0.88rem;
    color: #334155;
    font-weight: 400;
}
.active-chips-list {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}
.active-filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1d4ed8;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
}
.badge-x {
    font-size: 0.7rem;
    color: #60a5fa;
}
.clear-all-link {
    font-size: 0.78rem;
    font-weight: 600;
    color: #ef4444;
    text-decoration: none;
}

/* ─── 4. Product Cards Grid ──────────────────────────────────────── */
.products-catalogue-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}
.product-item-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    position: relative;
}
.product-item-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.09);
    border-color: #93c5fd;
}
.product-img-box {
    position: relative;
    width: 100%;
    aspect-ratio: 4/3;
    background: #f8fafc;
    overflow: hidden;
}
.product-img-link {
    display: block;
    width: 100%;
    height: 100%;
}
.card-product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}
.product-item-card:hover .card-product-img {
    transform: scale(1.06);
}
.product-placeholder-icon {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    background: #f1f5f9;
}
.card-badge-cluster {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    z-index: 2;
}
.badge-featured {
    background: rgba(245, 158, 11, 0.95);
    color: #ffffff;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(245, 158, 11, 0.3);
}
.badge-origin {
    background: rgba(15, 23, 42, 0.85);
    color: #f8fafc;
    font-size: 0.7rem;
    font-weight: 500;
    padding: 3px 8px;
    border-radius: 6px;
    backdrop-filter: blur(4px);
}
.card-storage-badge {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: rgba(15, 39, 74, 0.88);
    color: #7dd3fc;
    border: 1px solid rgba(56, 189, 248, 0.4);
    font-size: 0.7rem;
    font-weight: 500;
    padding: 3px 8px;
    border-radius: 6px;
    backdrop-filter: blur(4px);
    z-index: 2;
}
.card-storage-badge.storage-live {
    background: rgba(180, 83, 9, 0.88);
    color: #fef08a;
    border-color: rgba(251, 191, 36, 0.5);
}

.btn-card-quickview {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(255, 255, 255, 0.92);
    color: #0f172a;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 5px 10px;
    font-size: 0.74rem;
    font-weight: 600;
    cursor: pointer;
    opacity: 0;
    transform: translateY(4px);
    transition: all 0.2s ease;
    z-index: 3;
}
.product-item-card:hover .btn-card-quickview {
    opacity: 1;
    transform: translateY(0);
}
.btn-card-quickview:hover {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
}

/* Card Body */
.product-item-body {
    padding: 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.card-meta-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
}
.card-cat-name {
    font-size: 0.72rem;
    font-weight: 600;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.card-sku {
    font-size: 0.7rem;
    font-weight: 400;
    color: #94a3b8;
}
.product-item-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #0f172a;
    margin: 0 0 8px;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.7em;
}
.product-item-title a {
    color: #0f172a;
    text-decoration: none;
    transition: color 0.15s ease;
}
.product-item-title a:hover {
    color: #2563eb;
}
.card-spec-row {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}
.spec-chip {
    font-size: 0.72rem;
    font-weight: 400;
    color: #475569;
    background: #f1f5f9;
    padding: 2px 7px;
    border-radius: 5px;
}
.card-pricing-block {
    margin-top: auto;
    margin-bottom: 14px;
    min-height: 38px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.product-card-price {
    display: flex;
    flex-direction: column;
}
.product-card-price .price-amount,
.product-card-price .price-val {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1d4ed8;
    line-height: 1.25;
}
.product-card-price .price-base-rm,
.product-card-price .price-sub-myr {
    font-size: 0.76rem;
    font-weight: 500;
    color: #64748b;
    margin-top: 2px;
    line-height: 1.2;
}
.moq-badge {
    font-size: 0.7rem;
    font-weight: 600;
    color: #2563eb;
    margin-top: 2px;
}
.price-on-request {
    font-size: 0.86rem;
    font-weight: 600;
    color: #0284c7;
}
.wholesale-prompt-price {
    display: flex;
    flex-direction: column;
}
.prompt-text {
    font-size: 0.76rem;
    font-weight: 500;
    color: #64748b;
}
.prompt-link {
    font-size: 0.8rem;
    font-weight: 600;
    color: #2563eb;
    text-decoration: none;
}

.card-action-btns {
    display: flex;
    gap: 8px;
    align-items: center;
}
.btn-card-add {
    width: 100%;
    background: #f59e0b;
    color: #091a36;
    border: none;
    border-radius: 10px;
    padding: 9px 12px;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.18s ease;
    box-shadow: 0 2px 6px rgba(245, 158, 11, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.btn-card-add:hover {
    background: #d97706;
    color: #091a36;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(245, 158, 11, 0.4);
}
.btn-card-rfq {
    background: #0f172a;
    color: #ffffff;
    border-radius: 10px;
    padding: 9px 12px;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    text-align: center;
    display: block;
}
.btn-card-quickview-mobile {
    display: none;
    width: 38px;
    height: 38px;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
}

/* ─── 5. Empty Sourcing Box ─────────────────────────────────────── */
.empty-sourcing-box {
    background: #ffffff;
    border: 1.5px dashed #cbd5e1;
    border-radius: 20px;
    padding: 48px 24px;
    text-align: center;
    margin: 20px 0 40px;
}
.empty-icon-wrap {
    font-size: 2.8rem;
    margin-bottom: 12px;
}
.empty-title {
    font-size: 1.45rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 6px;
}
.empty-subtitle {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2563eb;
    margin: 0 0 12px;
}
.empty-desc {
    font-size: 0.92rem;
    color: #64748b;
    max-width: 580px;
    margin: 0 auto 24px;
    line-height: 1.6;
}
.empty-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
}
.btn-sourcing-primary {
    background: #2563eb;
    color: #ffffff;
    padding: 11px 24px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.9rem;
    text-decoration: none;
    box-shadow: 0 3px 10px rgba(37, 99, 235, 0.3);
}
.btn-sourcing-secondary {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
    padding: 11px 20px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.9rem;
    text-decoration: none;
}

/* ─── 6. Custom Sourcing Banner ─────────────────────────────────── */
.custom-sourcing-banner {
    margin-top: 48px;
    background: linear-gradient(135deg, #06152b 0%, #0c2146 45%, #1d4ed8 100%);
    border-radius: 20px;
    padding: 36px 32px;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 12px 32px rgba(6, 21, 43, 0.12);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 24px;
}
.sourcing-pill-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.25);
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #93c5fd;
    letter-spacing: 0.06em;
    margin-bottom: 10px;
}
.sourcing-banner-head {
    font-size: 1.45rem;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 4px;
}
.sourcing-banner-subhead {
    font-size: 1.15rem;
    font-weight: 700;
    color: #7dd3fc;
    margin: 0 0 10px;
}
.sourcing-banner-p {
    font-size: 0.92rem;
    color: #e0f2fe;
    line-height: 1.6;
    max-width: 680px;
    margin: 0;
}
.btn-sourcing-action {
    background: #2563eb;
    color: #ffffff;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.92rem;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
    display: inline-block;
    white-space: nowrap;
}

/* ─── 7. Wholesale Supply CTA ────────────────────────────────────── */
.wholesale-supply-cta {
    margin-top: 24px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}
.wholesale-cta-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px;
}
.wholesale-cta-desc {
    font-size: 0.9rem;
    color: #475569;
    max-width: 650px;
    line-height: 1.6;
    margin: 0;
}
.wholesale-cta-btns {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.btn-wholesale-access {
    background: #0f172a;
    color: #ffffff;
    padding: 11px 22px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.88rem;
    text-decoration: none;
    transition: background 0.15s ease;
}
.btn-wholesale-access:hover {
    background: #1e293b;
}
.btn-wholesale-quote {
    background: #2563eb;
    color: #ffffff;
    padding: 11px 22px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.88rem;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
}

/* ─── Search & Mobile Filter Controls ─────────────────────────────── */
.shop-search-filter-controls {
    display: block;
    margin-bottom: 12px;
}
.btn-mobile-filter-toggle {
    display: none;
}

/* ─── Responsive Adjustments ────────────────────────────────────── */
@media (max-width: 1200px) {
    .products-catalogue-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }
    .shop-filters-row {
        grid-template-columns: repeat(3, 1fr);
    }
    .searchable-dropdown:nth-child(n+4) .searchable-dropdown-menu {
        left: auto;
        right: 0;
    }
}

@media (max-width: 900px) {
    .shop-search-filter-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 0;
    }
    .shop-search-wrapper {
        flex: 1;
        margin-bottom: 0 !important;
    }
    .btn-mobile-filter-toggle {
        display: inline-flex !important;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        height: 46px;
        padding: 0 16px;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
        white-space: nowrap;
        flex-shrink: 0;
        transition: all 0.18s ease;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    }
    .btn-mobile-filter-toggle:hover {
        border-color: #3b82f6;
        background: #f8fafc;
    }
    .btn-mobile-filter-toggle.is-open {
        background: #eff6ff;
        border-color: #2563eb;
        color: #1d4ed8;
    }
    .btn-mobile-filter-toggle.has-active-filters {
        background: #eff6ff;
        border-color: #2563eb;
        color: #1d4ed8;
    }
    .btn-filter-content {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mobile-filter-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        border-radius: 999px;
        background: #2563eb;
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 700;
        line-height: 1;
    }
    .mobile-filter-chevron {
        transition: transform 0.2s ease;
    }
    .btn-mobile-filter-toggle.is-open .mobile-filter-chevron {
        transform: rotate(180deg);
    }
    .shop-filters-row {
        display: none;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        padding-top: 14px;
        margin-top: 12px;
        border-top: 1px dashed #e2e8f0;
        animation: filterSlideDown 0.2s ease-out;
    }
    .shop-filters-row.show-mobile {
        display: grid !important;
    }
    @keyframes filterSlideDown {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }
}

@media (max-width: 768px) {
    .products-catalogue-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px !important;
    }
    .product-item-card {
        border-radius: 14px !important;
    }
    .product-item-body {
        padding: 12px 10px !important;
    }
    .product-item-title {
        font-size: 0.88rem !important;
        line-height: 1.3 !important;
        min-height: 2.6em !important;
        margin-bottom: 6px !important;
    }
    .card-spec-row {
        margin-bottom: 8px !important;
        gap: 4px !important;
    }
    .spec-chip {
        font-size: 0.68rem !important;
        padding: 2px 6px !important;
    }
    .product-card-price .price-amount,
    .product-card-price .price-val {
        font-size: 1.05rem !important;
    }
    .card-pricing-block {
        min-height: 32px !important;
        margin-bottom: 10px !important;
    }
    .btn-card-add {
        padding: 8px 10px !important;
        font-size: 0.78rem !important;
    }
    .btn-card-rfq {
        padding: 8px 10px !important;
        font-size: 0.78rem !important;
    }
    .btn-card-quickview {
        display: none !important;
    }
    .btn-card-quickview-mobile {
        display: flex !important;
        width: 36px !important;
        height: 36px !important;
    }
    .card-storage-badge {
        font-size: 0.65rem !important;
        padding: 2px 6px !important;
    }
    .badge-featured, .badge-origin {
        font-size: 0.65rem !important;
        padding: 2px 6px !important;
    }
    .searchable-dropdown:nth-child(even) .searchable-dropdown-menu {
        left: auto;
        right: 0;
    }
    .searchable-dropdown:nth-child(odd) .searchable-dropdown-menu {
        left: 0;
        right: auto;
    }
    .custom-sourcing-banner,
    .wholesale-supply-cta {
        padding: 24px 20px;
    }
}

@media (max-width: 540px) {
    .products-catalogue-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
    }
    .shop-filter-bar {
        padding: 12px !important;
        border-radius: 14px !important;
    }
    .btn-mobile-filter-toggle {
        padding: 0 12px !important;
        font-size: 0.82rem !important;
    }
    .shop-filters-row {
        grid-template-columns: 1fr 1fr !important;
        gap: 8px !important;
    }
    .searchable-dropdown .searchable-dropdown-menu {
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
    }
    .customer-type-bar {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px 14px;
    }
    .customer-type-toggle {
        width: 100%;
    }
    .cust-type-btn {
        flex: 1;
        justify-content: center;
        font-size: 0.78rem;
        padding: 6px 10px;
    }
    .shop-results-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    .results-header-right {
        width: 100%;
    }
    .sort-searchable-dropdown {
        width: 100%;
    }
    .sort-dropdown-trigger {
        width: 100%;
    }
    .sort-dropdown-menu {
        width: 100% !important;
        max-width: 100% !important;
        left: 0 !important;
        right: 0 !important;
    }
    .product-item-card {
        border-radius: 12px !important;
    }
    .product-item-body {
        padding: 10px 8px !important;
    }
    .product-item-title {
        font-size: 0.82rem !important;
        line-height: 1.25 !important;
        min-height: 2.5em !important;
    }
    .product-card-price .price-amount,
    .product-card-price .price-val {
        font-size: 0.95rem !important;
    }
    .btn-card-add {
        padding: 7px 6px !important;
        font-size: 0.74rem !important;
        border-radius: 8px !important;
    }
    .btn-card-quickview-mobile {
        width: 32px !important;
        height: 32px !important;
        border-radius: 8px !important;
        font-size: 0.85rem !important;
    }
}
</style>

<script>
let searchDebounceTimer = null;
let filterAbortController = null;

function toggleMobileFilters() {
    const filtersRow = document.getElementById('shopFiltersRow');
    const toggleBtn = document.getElementById('mobileFilterToggleBtn');
    if (!filtersRow) return;
    const isShown = filtersRow.classList.toggle('show-mobile');
    if (toggleBtn) {
        toggleBtn.classList.toggle('is-open', isShown);
    }
}

// Search bar input live filter
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('shopMainSearchInput');
    const clearBtn = document.getElementById('searchClearBtn');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (clearBtn) {
                clearBtn.style.display = this.value.trim() ? 'block' : 'none';
            }
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(() => {
                applyShopFilters();
            }, 300);
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchDebounceTimer);
                applyShopFilters();
            }
        });
    }
});

function clearSearchInput(e) {
    if (e) e.preventDefault();
    const searchInput = document.getElementById('shopMainSearchInput');
    const clearBtn = document.getElementById('searchClearBtn');
    if (searchInput) searchInput.value = '';
    if (clearBtn) clearBtn.style.display = 'none';
    applyShopFilters();
}

function setCustomerType(type) {
    const hiddenInput = document.getElementById('hidden_customer_type');
    if (hiddenInput) {
        hiddenInput.value = type;
    }
    document.querySelectorAll('.cust-type-btn').forEach(btn => btn.classList.remove('active'));
    if (type === 'retail') {
        document.querySelector('.cust-type-btn:first-child')?.classList.add('active');
    } else {
        document.querySelector('.cust-type-btn:last-child')?.classList.add('active');
    }
    applyShopFilters();
}

function toggleSearchableDropdown(name) {
    const currentMenu = document.getElementById('menu-' + name);
    const currentTrigger = document.querySelector('#dropdown-' + name + ' .searchable-dropdown-trigger');
    if (!currentMenu) return;
    const isOpen = currentMenu.classList.contains('show');

    // Close all other dropdowns
    document.querySelectorAll('.searchable-dropdown-menu').forEach(menu => {
        menu.classList.remove('show');
    });
    document.querySelectorAll('.searchable-dropdown-trigger').forEach(trigger => {
        trigger.classList.remove('open');
    });

    if (!isOpen) {
        currentMenu.classList.add('show');
        if (currentTrigger) currentTrigger.classList.add('open');
        const searchInput = currentMenu.querySelector('.dropdown-search-input');
        if (searchInput) {
            searchInput.value = '';
            filterDropdownOptions(name, '');
            setTimeout(() => searchInput.focus(), 50);
        }
    }
}

function filterDropdownOptions(name, query) {
    const list = document.getElementById('list-' + name);
    if (!list) return;
    const items = list.querySelectorAll('.dropdown-option-item');
    const noResults = list.querySelector('.dropdown-no-results');
    const q = (query || '').trim().toLowerCase();
    let matches = 0;

    items.forEach(item => {
        const text = (item.getAttribute('data-label') || item.textContent).toLowerCase();
        if (!q || text.includes(q)) {
            item.style.display = 'flex';
            matches++;
        } else {
            item.style.display = 'none';
        }
    });

    if (noResults) {
        noResults.style.display = matches === 0 ? 'block' : 'none';
    }
}

function selectDropdownOption(name, value, label) {
    if (name === 'sort') {
        const sortInput = document.getElementById('hiddenSortInput');
        if (sortInput) sortInput.value = value;
    } else {
        const hiddenInput = document.getElementById('hidden_' + name);
        if (hiddenInput) {
            hiddenInput.value = value;
        }
        
        // If parent category changes, reset child category
        if (name === 'category') {
            const subInput = document.getElementById('hidden_subcategory');
            if (subInput) subInput.value = '';
        }
    }

    // Close open dropdowns
    document.querySelectorAll('.searchable-dropdown-menu').forEach(menu => {
        menu.classList.remove('show');
    });
    document.querySelectorAll('.searchable-dropdown-trigger').forEach(trigger => {
        trigger.classList.remove('open');
    });

    applyShopFilters();
}

function clearDropdownValue(name) {
    const hiddenInput = document.getElementById('hidden_' + name);
    if (hiddenInput) {
        hiddenInput.value = '';
    }
    if (name === 'category') {
        const subInput = document.getElementById('hidden_subcategory');
        if (subInput) subInput.value = '';
    }
    applyShopFilters();
}

async function applyShopFilters(targetUrl = null, pushState = true) {
    const form = document.getElementById('shopFilterForm');
    if (!form) return;

    let fetchUrl = targetUrl;
    if (!fetchUrl) {
        const formData = new FormData(form);
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value && value.trim() !== '') {
                params.set(key, value.trim());
            }
        }
        fetchUrl = form.action + (params.toString() ? '?' + params.toString() : '');
    }

    if (filterAbortController) {
        filterAbortController.abort();
    }
    filterAbortController = new AbortController();

    const mainContent = document.getElementById('shopMainContent');
    if (mainContent) {
        mainContent.classList.add('is-loading');
    }

    try {
        const response = await fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            signal: filterAbortController.signal
        });

        if (!response.ok) throw new Error('Failed to load filtered results');

        const htmlText = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlText, 'text/html');

        // 1. Update Results Header
        const newResultsHeader = doc.getElementById('shopResultsHeader');
        const currentResultsHeader = document.getElementById('shopResultsHeader');
        if (newResultsHeader && currentResultsHeader) {
            currentResultsHeader.innerHTML = newResultsHeader.innerHTML;
        }

        // 2. Update Products Area
        const newProductsArea = doc.getElementById('shopProductsArea');
        const currentProductsArea = document.getElementById('shopProductsArea');
        if (newProductsArea && currentProductsArea) {
            currentProductsArea.innerHTML = newProductsArea.innerHTML;
        }

        // 3. Update Subcategory Options List (if parent category changed)
        const newSubcatList = doc.getElementById('list-subcategory');
        const currentSubcatList = document.getElementById('list-subcategory');
        if (newSubcatList && currentSubcatList) {
            currentSubcatList.innerHTML = newSubcatList.innerHTML;
        }

        // 4. Update all dropdown triggers & hidden inputs
        ['category', 'subcategory', 'origin', 'brand', 'pack_size', 'availability', 'sort'].forEach(name => {
            const newTrigger = doc.querySelector('#dropdown-' + name + ' .searchable-dropdown-trigger');
            const currentTrigger = document.querySelector('#dropdown-' + name + ' .searchable-dropdown-trigger');
            if (newTrigger && currentTrigger) {
                currentTrigger.className = newTrigger.className;
                const newLabel = doc.getElementById('label-' + name);
                const currentLabel = document.getElementById('label-' + name);
                if (newLabel && currentLabel) {
                    currentLabel.textContent = newLabel.textContent;
                }
                const newArrows = newTrigger.querySelector('.dropdown-trigger-arrows');
                const currentArrows = currentTrigger.querySelector('.dropdown-trigger-arrows');
                if (newArrows && currentArrows) {
                    currentArrows.innerHTML = newArrows.innerHTML;
                }
            }

            const newHidden = doc.getElementById('hidden_' + name);
            const currentHidden = document.getElementById('hidden_' + name);
            if (newHidden && currentHidden) {
                currentHidden.value = newHidden.value;
            }
        });

        // Update mobile filter active count & button state
        let activeCount = 0;
        ['category', 'subcategory', 'origin', 'brand', 'pack_size', 'availability'].forEach(name => {
            const hidden = document.getElementById('hidden_' + name);
            if (hidden && hidden.value) activeCount++;
        });
        const countBadge = document.getElementById('mobileFilterCountBadge');
        const toggleBtn = document.getElementById('mobileFilterToggleBtn');
        if (countBadge) {
            countBadge.textContent = activeCount;
            countBadge.style.display = activeCount > 0 ? 'inline-flex' : 'none';
        }
        if (toggleBtn) {
            toggleBtn.classList.toggle('has-active-filters', activeCount > 0);
        }

        // 5. Update Business Pricing Banner if wholesale toggle changed
        const newBizBanner = doc.getElementById('businessPricingBanner');
        const currentBizBanner = document.getElementById('businessPricingBanner');
        const customerBar = document.querySelector('.customer-type-bar');
        if (newBizBanner && !currentBizBanner && customerBar) {
            customerBar.insertAdjacentElement('afterend', newBizBanner);
        } else if (!newBizBanner && currentBizBanner) {
            currentBizBanner.remove();
        }

        // 6. Update search clear button state
        const searchInput = document.getElementById('shopMainSearchInput');
        const clearBtn = document.getElementById('searchClearBtn');
        if (searchInput && clearBtn) {
            clearBtn.style.display = searchInput.value.trim() ? 'block' : 'none';
        }

        // 7. Update browser history URL
        if (pushState && window.location.href !== fetchUrl) {
            window.history.pushState({ url: fetchUrl }, '', fetchUrl);
        }

    } catch (err) {
        if (err.name !== 'AbortError') {
            console.error('Filter AJAX error:', err);
        }
    } finally {
        if (mainContent) {
            mainContent.classList.remove('is-loading');
        }
    }
}

// Global outside click listener
document.addEventListener('click', function(e) {
    if (!e.target.closest('.searchable-dropdown')) {
        document.querySelectorAll('.searchable-dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
        document.querySelectorAll('.searchable-dropdown-trigger').forEach(trigger => {
            trigger.classList.remove('open');
        });
    }

    // Intercept active filter badge clicks
    const filterBadge = e.target.closest('.active-filter-badge');
    if (filterBadge && filterBadge.href) {
        e.preventDefault();
        applyShopFilters(filterBadge.href);
        return;
    }

    // Intercept clear-all link
    const clearAllLink = e.target.closest('.clear-all-link');
    if (clearAllLink && clearAllLink.href) {
        e.preventDefault();
        const searchInput = document.getElementById('shopMainSearchInput');
        if (searchInput) searchInput.value = '';
        ['category', 'subcategory', 'origin', 'brand', 'pack_size', 'availability'].forEach(name => {
            const input = document.getElementById('hidden_' + name);
            if (input) input.value = '';
        });
        applyShopFilters(clearAllLink.href);
        return;
    }

    // Intercept pagination clicks
    const paginationLink = e.target.closest('.shop-pagination-wrap a');
    if (paginationLink && paginationLink.href) {
        e.preventDefault();
        applyShopFilters(paginationLink.href);
        window.scrollTo({ top: document.querySelector('.shop-filter-bar')?.offsetTop - 80 || 200, behavior: 'smooth' });
        return;
    }
});

// Escape key listener
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.searchable-dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
        document.querySelectorAll('.searchable-dropdown-trigger').forEach(trigger => {
            trigger.classList.remove('open');
        });
    }
});

// Browser back/forward navigation listener
window.addEventListener('popstate', function(e) {
    applyShopFilters(window.location.href, false);
});

// Delegated click handler for Quick View buttons (handles both desktop & mobile, on initial load & after AJAX filter/sort updates)
document.addEventListener('click', function(e) {
    const qvBtn = e.target.closest('.js-quickview-btn');
    if (qvBtn) {
        e.preventDefault();
        e.stopPropagation();
        const data = {
            id: qvBtn.getAttribute('data-id') || '',
            name: qvBtn.getAttribute('data-name') || '',
            category: qvBtn.getAttribute('data-category') || 'Seafood',
            sku: qvBtn.getAttribute('data-sku') || 'N/A',
            origin: qvBtn.getAttribute('data-origin') || '',
            weight: qvBtn.getAttribute('data-weight') || '',
            unit: qvBtn.getAttribute('data-unit') || '',
            storage_temp: qvBtn.getAttribute('data-storage') || 'Cold-Chain',
            price_formatted: qvBtn.getAttribute('data-price-formatted') || '',
            base_rm: parseFloat(qvBtn.getAttribute('data-base-rm') || '0'),
            manual_sgd: qvBtn.getAttribute('data-manual-sgd') || '',
            manual_usd: qvBtn.getAttribute('data-manual-usd') || '',
            manual_wholesale_sgd: qvBtn.getAttribute('data-manual-wholesale-sgd') || '',
            manual_wholesale_usd: qvBtn.getAttribute('data-manual-wholesale-usd') || '',
            group: qvBtn.getAttribute('data-group') || 'retail',
            moq: qvBtn.getAttribute('data-moq') || '',
            short_desc: qvBtn.getAttribute('data-desc') || '',
            image: qvBtn.getAttribute('data-image') || '',
            url: qvBtn.getAttribute('data-url') || '#',
            rfq_url: qvBtn.getAttribute('data-rfq-url') || '#'
        };
        openQuickViewModal(data);
    }
});

function openQuickViewModal(product) {
    if (!product) return;
    const titleEl = document.getElementById('qvTitle');
    if (titleEl) titleEl.textContent = product.name || '';
    const catEl = document.getElementById('qvCategory');
    if (catEl) catEl.textContent = product.category || '';
    const skuEl = document.getElementById('qvSku');
    if (skuEl) skuEl.textContent = product.sku || 'N/A';
    const stEl = document.getElementById('qvStorage');
    if (stEl) stEl.textContent = product.storage_temp || 'Cold-Chain';

    // Dynamically calculate price based on active currency
    const currentCurrency = (window.AppCurrency && window.AppCurrency.current) || 'MYR';
    let formattedPrice = product.price_formatted;
    let baseRmText = '';

    if (product.base_rm && parseFloat(product.base_rm) > 0) {
        const dummyEl = document.createElement('div');
        dummyEl.setAttribute('data-base-rm', product.base_rm);
        if (product.manual_sgd) dummyEl.setAttribute('data-manual-sgd', product.manual_sgd);
        if (product.manual_usd) dummyEl.setAttribute('data-manual-usd', product.manual_usd);
        if (product.manual_wholesale_sgd) dummyEl.setAttribute('data-manual-wholesale-sgd', product.manual_wholesale_sgd);
        if (product.manual_wholesale_usd) dummyEl.setAttribute('data-manual-wholesale-usd', product.manual_wholesale_usd);
        if (product.group) dummyEl.setAttribute('data-group', product.group);

        if (typeof calculatePriceForElement === 'function') {
            const res = calculatePriceForElement(dummyEl, currentCurrency);
            formattedPrice = res.formatted;
            if (currentCurrency !== 'MYR') {
                baseRmText = 'RM ' + parseFloat(product.base_rm).toFixed(2);
            }
        }
    }

    const priceEl = document.getElementById('qvPrice');
    if (priceEl) priceEl.textContent = formattedPrice;
    const qvBaseRm = document.getElementById('qvBaseRm');
    if (qvBaseRm) {
        qvBaseRm.textContent = baseRmText;
        qvBaseRm.style.display = baseRmText ? 'block' : 'none';
    }

    const descEl = document.getElementById('qvDesc');
    if (descEl) descEl.textContent = product.short_desc || '';
    const prodIdEl = document.getElementById('qvProductId');
    if (prodIdEl) prodIdEl.value = product.id;
    const detailsLink = document.getElementById('qvDetailsLink');
    if (detailsLink) detailsLink.href = product.url;

    // Toggle Cart form vs RFQ button
    const cartForm = document.getElementById('qvCartForm');
    const rfqLink = document.getElementById('qvRfqLink');
    if (product.base_rm && parseFloat(product.base_rm) > 0) {
        if (cartForm) cartForm.style.display = 'block';
        if (rfqLink) rfqLink.style.display = 'none';
    } else {
        if (cartForm) cartForm.style.display = 'none';
        if (rfqLink) {
            rfqLink.href = product.rfq_url || product.url;
            rfqLink.style.display = 'inline-flex';
        }
    }

    const imgEl = document.getElementById('qvImg');
    const imgPlaceholder = document.getElementById('qvImgPlaceholder');
    if (imgEl) {
        if (product.image) {
            imgEl.src = product.image;
            imgEl.style.display = 'block';
            if (imgPlaceholder) imgPlaceholder.style.display = 'none';
        } else {
            imgEl.style.display = 'none';
            if (imgPlaceholder) imgPlaceholder.style.display = 'flex';
        }
    }

    const originEl = document.getElementById('qvOriginTag');
    if (originEl) {
        if (product.origin) {
            originEl.textContent = '🌍 ' + product.origin;
            originEl.style.display = 'inline-block';
        } else {
            originEl.style.display = 'none';
        }
    }

    const moqEl = document.getElementById('qvMoqBadge');
    if (moqEl) {
        if (product.moq) {
            moqEl.textContent = 'MOQ: ' + product.moq;
            moqEl.style.display = 'inline-block';
        } else {
            moqEl.style.display = 'none';
        }
    }

    const backdrop = document.getElementById('quickViewBackdrop');
    if (backdrop) {
        if (backdrop.parentElement !== document.body) {
            document.body.appendChild(backdrop);
        }
        backdrop.classList.add('active');
        backdrop.classList.add('show');
    }
    document.body.style.overflow = 'hidden';
}

function closeQuickViewModal() {
    const backdrop = document.getElementById('quickViewBackdrop');
    if (backdrop) {
        backdrop.classList.remove('active');
        backdrop.classList.remove('show');
    }
    document.body.style.overflow = '';
}

function handleQuickViewBackdropClick(e) {
    if (e.target.id === 'quickViewBackdrop') {
        closeQuickViewModal();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const backdrop = document.getElementById('quickViewBackdrop');
    if (backdrop && backdrop.parentElement !== document.body) {
        document.body.appendChild(backdrop);
    }
});

window.openQuickViewModal = openQuickViewModal;
window.closeQuickViewModal = closeQuickViewModal;
window.handleQuickViewBackdropClick = handleQuickViewBackdropClick;
</script>
@endsection
