@extends('layouts.app')
@section('title', __t('shop.meta_title', 'Products & Sourcing — MST Import & Export Sdn. Bhd.'))
@section('meta_description', __t('shop.meta_desc', 'Explore selected frozen seafood, meat, food ingredients and specialty products from MST. Customised sourcing available based on product specification, origin, pack size and supply requirements.'))

@section('content')
<!-- Page Header / Hero Section (MST Deep Navy Brand Gradient) -->
<div class="products-hero-section">
    <div class="products-hero-grid-pattern"></div>
    <div class="container products-hero-inner">
        <div class="hero-content-left">
            <div class="hero-breadcrumb">
                <a href="{{ route('home') }}" class="hero-crumb-link">
                    🏠 @t('nav.home', 'Home')
                </a>
                <span class="hero-crumb-sep">›</span>
                <span class="hero-crumb-current">@t('shop.breadcrumb_products_sourcing', 'Products & Sourcing')</span>
            </div>

            <h1 class="products-hero-title">
                @t('shop.hero_title', 'Products & Sourcing')
            </h1>
            
            <p class="products-hero-subtitle">
                @t('shop.hero_desc', 'Explore selected frozen seafood, meat, food ingredients and specialty products from MST.')
            </p>
            <p class="products-hero-subtitle" style="margin-top:6px;font-size:0.9rem;opacity:0.92">
                @t('shop.hero_subdesc', 'We also provide customised sourcing based on product specification, origin, pack size, brand and supply requirements.')
            </p>
        </div>

        <div class="hero-content-right">
            @auth
                <div class="user-tier-pill">
                    ⭐ {{ match($group) {
                        'retail' => __t('shop.retail_tier', 'Retail / Walk-in Tier'),
                        'walkin' => __t('shop.walkin_tier', 'Walk-in Retail Tier'),
                        'wholesale' => __t('shop.wholesale_tier', 'Wholesale Tier'),
                        'trading' => __t('shop.trading_tier', 'Trading Partner Tier'),
                        default => __t('shop.' . $group . '_tier', ucfirst($group) . ' Tier'),
                    } }}
                </div>
            @else
                <div class="brand-slogan-pill">
                    @t('about.motto', 'Flow with Integrity, Grow with Strength.')
                </div>
            @endauth
        </div>
    </div>
</div>

@php
    $currentCustomerType = $customerType ?? request('customer_type', (auth()->check() && in_array($group, ['wholesale', 'trading'])) ? 'wholesale' : 'retail');
@endphp

<div class="container shop-container" id="shopMasterContainer" data-customer-mode="{{ $currentCustomerType }}" style="padding-top:16px;padding-bottom:60px">

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
        
        $originFlags = [
            'norway'        => '🇳🇴',
            'malaysia'      => '🇲🇾',
            'indonesia'     => '🇮🇩',
            'japan'         => '🇯🇵',
            'australia'     => '🇦🇺',
            'new zealand'   => '🇳🇿',
            'chile'         => '🇨🇱',
            'china'         => '🇨🇳',
            'india'         => '🇮🇳',
            'thailand'      => '🇹🇭',
            'vietnam'       => '🇻🇳',
            'myanmar'       => '🇲🇲',
            'usa'           => '🇺🇸',
            'united states' => '🇺🇸',
            'canada'        => '🇨🇦',
            'taiwan'        => '🇹🇼',
            'korea'         => '🇰🇷',
            'singapore'     => '🇸🇬',
            'local'         => '🇲🇾',
        ];

        $getOriginFlag = function($orig) use ($originFlags) {
            if (!$orig) return '🌍';
            $lower = strtolower(trim($orig));
            foreach ($originFlags as $k => $flag) {
                if (str_contains($lower, $k)) return $flag;
            }
            return '🌍';
        };

        $catIcons = [
            'seafood'             => '🦐',
            'fish'                => '🐟',
            'salmon'              => '🐟',
            'crab'                => '🦀',
            'crustacean'          => '🦀',
            'prawn'               => '🦐',
            'shrimp'              => '🦐',
            'squid'               => '🦑',
            'shellfish'           => '🦪',
            'meat'                => '🥩',
            'beef'                => '🥩',
            'chicken'             => '🍗',
            'pork'                => '🥓',
            'poultry'             => '🍗',
            'frozen-food'         => '🥟',
            'dim-sum'             => '🥟',
            'food-ingredients'    => '🧂',
            'cuisine-ingredients' => '🍳',
            'desserts'            => '🍡',
            'hotpot'              => '🍲',
            'soup'                => '🍲',
            'specialty'           => '⭐',
        ];

        $getCatIcon = function($cat) use ($catIcons) {
            if (!$cat) return '📁';
            if (is_object($cat) && !empty($cat->icon)) {
                return $cat->icon;
            }
            $slug = is_object($cat) ? ($cat->slug ?? '') : strval($cat);
            $name = is_object($cat) ? ($cat->name ?? '') : strval($cat);
            $combined = strtolower($slug . ' ' . $name);
            foreach ($catIcons as $k => $icon) {
                if (str_contains($combined, $k)) return $icon;
            }
            return '📁';
        };

        $currentCustomerType = request('customer_type', (auth()->check() && in_array($group, ['wholesale', 'trading'])) ? 'wholesale' : 'retail');
        $hasAnyFilter = request('search') || request('category') || request('subcategory') || request('origin') || request('brand') || request('pack_size') || request('availability') || (request('sort') && request('sort') !== 'sort_order');
        
        $activeFilterCount = 0;
        if (!empty($selectedCatSlug)) $activeFilterCount++;
        if (!empty($selectedSubSlug)) $activeFilterCount++;
        if (!empty(request('origin'))) $activeFilterCount++;
        if (!empty(request('brand'))) $activeFilterCount++;
        if (!empty(request('pack_size'))) $activeFilterCount++;
        if (!empty(request('availability'))) $activeFilterCount++;

        $currentCurrency = app(\App\Services\CurrencyService::class)->getCurrentCurrency();
    @endphp

    <!-- ─── Search & Multi-Filter Bar ─── -->
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

            <!-- Search input & Customer Mode Toggle & Mobile Filter Toggle Row -->
            <div class="shop-search-filter-controls">
                <div class="shop-search-main-row">
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

                <!-- Shopping Mode Toggle -->
                <div class="customer-type-inline-wrap">
                    <span class="cust-mode-label">@t('shop.shopping_for', 'Shopping for:')</span>
                    <div class="customer-type-toggle">
                        <button type="button" 
                           onclick="setCustomerType('retail')" 
                           data-type="retail"
                           class="cust-type-btn {{ $currentCustomerType === 'retail' ? 'active' : '' }}"
                           title="@t('shop.type_retail_desc', 'Public browsing · Retail pricing · No login required')">
                            <span>🛍️ @t('shop.type_retail', 'Retail / Walk-in')</span>
                        </button>
                        <button type="button" 
                           onclick="setCustomerType('wholesale')" 
                           data-type="wholesale"
                           class="cust-type-btn {{ $currentCustomerType === 'wholesale' ? 'active' : '' }}"
                           title="@t('shop.type_wholesale_desc', 'Business verification required · Wholesale pricing')">
                            <span>🏢 @t('shop.type_wholesale', 'Wholesale / Business')</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Customer Mode Description Caption -->
            <div class="customer-mode-caption" id="custModeCaption">
                @if($currentCustomerType === 'retail')
                    <span class="mode-caption-tag">🛍️ @t('shop.type_retail', 'Retail / Walk-in'):</span>
                    <span class="mode-caption-text">@t('shop.type_retail_desc', 'Public browsing · Retail pricing · No login required')</span>
                @else
                    <span class="mode-caption-tag">🏢 @t('shop.type_wholesale', 'Wholesale / Business'):</span>
                    <span class="mode-caption-text">@t('shop.type_wholesale_desc', 'Business verification required · Wholesale pricing')</span>
                @endif
            </div>

            <!-- 6 Searchable Dropdown Filters -->
            <div class="shop-filters-row" id="shopFiltersRow">
                <!-- 1. Parent Category Dropdown -->
                <div class="searchable-dropdown" id="dropdown-category" data-name="category">
                    <button type="button" class="searchable-dropdown-trigger {{ $selectedCatSlug ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('category')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon" id="icon-category">{{ $activeParentCat ? $getCatIcon($activeParentCat) : '📁' }}</span>
                            <span class="dropdown-trigger-text" id="label-category">{{ $activeParentCat ? $activeParentCat->name : __t('shop.filter_parent_cat', 'Parent Category') }}</span>
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
                            <input type="text" class="dropdown-search-input" placeholder="@t('shop.search_parent_cat', 'Search parent category...')" oninput="filterDropdownOptions('category', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-category">
                            <button type="button" class="dropdown-option-item {{ empty($selectedCatSlug) ? 'selected' : '' }}" data-value="" data-label="@t('shop.all_parent_cats', 'All Parent Categories')" onclick="selectDropdownOption('category', '', '{{ addslashes(__t('shop.filter_parent_cat', 'Parent Category')) }}', '📁')">
                                <span class="option-name">🌟 @t('shop.all_parent_cats', 'All Parent Categories')</span>
                                @if(empty($selectedCatSlug)) <span class="option-check">✓</span> @endif
                            </button>
                            @foreach($parentCategories as $pCat)
                                @php $pIcon = $getCatIcon($pCat); @endphp
                                <button type="button" class="dropdown-option-item {{ $selectedCatSlug === $pCat->slug ? 'selected' : '' }}" data-value="{{ $pCat->slug }}" data-label="{{ $pCat->name }}" onclick="selectDropdownOption('category', '{{ $pCat->slug }}', '{{ addslashes($pCat->name) }}', '{{ $pIcon }}')">
                                    <span class="option-name">{{ $pIcon }} {{ $pCat->name }}</span>
                                    @if($selectedCatSlug === $pCat->slug) <span class="option-check">✓</span> @endif
                                </button>
                            @endforeach
                            <div class="dropdown-no-results" style="display:none;"><span>@t('shop.no_results', 'No results found')</span></div>
                        </div>
                    </div>
                </div>

                <!-- 2. Child Category Dropdown -->
                <div class="searchable-dropdown" id="dropdown-subcategory" data-name="subcategory">
                    <button type="button" class="searchable-dropdown-trigger {{ $selectedSubSlug ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('subcategory')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon" id="icon-subcategory">{{ $activeSubCat ? $getCatIcon($activeSubCat) : '📂' }}</span>
                            <span class="dropdown-trigger-text" id="label-subcategory">{{ $activeSubCat ? $activeSubCat->name : __t('shop.filter_child_cat', 'Child category') }}</span>
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
                            <input type="text" class="dropdown-search-input" placeholder="@t('shop.search_child_cat', 'Search child category...')" oninput="filterDropdownOptions('subcategory', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-subcategory">
                            <button type="button" class="dropdown-option-item {{ empty($selectedSubSlug) ? 'selected' : '' }}" data-value="" data-label="@t('shop.all_child_cats', 'All Child Categories')" onclick="selectDropdownOption('subcategory', '', '{{ addslashes(__t('shop.filter_child_cat', 'Child category')) }}', '📂')">
                                <span class="option-name">📂 @t('shop.all_child_cats', 'All Child Categories')</span>
                                @if(empty($selectedSubSlug)) <span class="option-check">✓</span> @endif
                            </button>
                            @if($activeParentCat && $activeParentCat->children->count())
                                @foreach($activeParentCat->children as $cCat)
                                    @php $cIcon = $getCatIcon($cCat); @endphp
                                    <button type="button" class="dropdown-option-item {{ $selectedSubSlug === $cCat->slug ? 'selected' : '' }}" data-value="{{ $cCat->slug }}" data-label="{{ $cCat->name }}" onclick="selectDropdownOption('subcategory', '{{ $cCat->slug }}', '{{ addslashes($cCat->name) }}', '{{ $cIcon }}')">
                                        <span class="option-name">{{ $cIcon }} {{ $cCat->name }}</span>
                                        @if($selectedSubSlug === $cCat->slug) <span class="option-check">✓</span> @endif
                                    </button>
                                @endforeach
                            @else
                                @foreach($subcategories as $sCat)
                                    @php $sIcon = $getCatIcon($sCat); @endphp
                                    <button type="button" class="dropdown-option-item {{ $selectedSubSlug === $sCat->slug ? 'selected' : '' }}" data-value="{{ $sCat->slug }}" data-label="{{ $sCat->name }} {{ $sCat->parent?->name }}" onclick="selectDropdownOption('subcategory', '{{ $sCat->slug }}', '{{ addslashes($sCat->name) }}', '{{ $sIcon }}')">
                                        <span class="option-name">{{ $sIcon }} {{ $sCat->name }} <small style="color:#94a3b8">({{ $sCat->parent?->name ?? 'Cat' }})</small></span>
                                        @if($selectedSubSlug === $sCat->slug) <span class="option-check">✓</span> @endif
                                    </button>
                                @endforeach
                            @endif
                            <div class="dropdown-no-results" style="display:none;"><span>@t('shop.no_results', 'No results found')</span></div>
                        </div>
                    </div>
                </div>

                <!-- 3. Origin Dropdown -->
                <div class="searchable-dropdown" id="dropdown-origin" data-name="origin">
                    <button type="button" class="searchable-dropdown-trigger {{ request('origin') ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('origin')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon" id="icon-origin">{{ request('origin') ? $getOriginFlag(request('origin')) : '🌍' }}</span>
                            <span class="dropdown-trigger-text" id="label-origin">{{ request('origin') ?: __t('shop.filter_origin', 'Origin') }}</span>
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
                            <input type="text" class="dropdown-search-input" placeholder="@t('shop.search_origin', 'Search origin...')" oninput="filterDropdownOptions('origin', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-origin">
                            <button type="button" class="dropdown-option-item {{ empty(request('origin')) ? 'selected' : '' }}" data-value="" data-label="@t('shop.all_origins', 'All Origins')" onclick="selectDropdownOption('origin', '', '{{ addslashes(__t('shop.filter_origin', 'Origin')) }}', '🌍')">
                                <span class="option-name">🌍 @t('shop.all_origins', 'All Origins')</span>
                                @if(empty(request('origin'))) <span class="option-check">✓</span> @endif
                            </button>
                            @foreach($availableOrigins as $orig)
                                @php $origFlag = $getOriginFlag($orig); @endphp
                                <button type="button" class="dropdown-option-item {{ request('origin') === $orig ? 'selected' : '' }}" data-value="{{ $orig }}" data-label="{{ $orig }}" onclick="selectDropdownOption('origin', '{{ addslashes($orig) }}', '{{ addslashes($orig) }}', '{{ $origFlag }}')">
                                    <span class="option-name">{{ $origFlag }} {{ $orig }}</span>
                                    @if(request('origin') === $orig) <span class="option-check">✓</span> @endif
                                </button>
                            @endforeach
                            <div class="dropdown-no-results" style="display:none;"><span>@t('shop.no_results', 'No results found')</span></div>
                        </div>
                    </div>
                </div>

                <!-- 4. Brand Dropdown -->
                <div class="searchable-dropdown" id="dropdown-brand" data-name="brand">
                    <button type="button" class="searchable-dropdown-trigger {{ request('brand') ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('brand')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon" id="icon-brand">🏷️</span>
                            <span class="dropdown-trigger-text" id="label-brand">{{ request('brand') ?: __t('shop.filter_brand', 'Brand') }}</span>
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
                            <input type="text" class="dropdown-search-input" placeholder="@t('shop.search_brand', 'Search brand...')" oninput="filterDropdownOptions('brand', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-brand">
                            <button type="button" class="dropdown-option-item {{ empty(request('brand')) ? 'selected' : '' }}" data-value="" data-label="@t('shop.all_brands', 'All Brands')" onclick="selectDropdownOption('brand', '', '{{ addslashes(__t('shop.filter_brand', 'Brand')) }}', '🏷️')">
                                <span class="option-name">🏷️ @t('shop.all_brands', 'All Brands')</span>
                                @if(empty(request('brand'))) <span class="option-check">✓</span> @endif
                            </button>
                            @foreach($availableBrands as $br)
                                <button type="button" class="dropdown-option-item {{ request('brand') === $br ? 'selected' : '' }}" data-value="{{ $br }}" data-label="{{ $br }}" onclick="selectDropdownOption('brand', '{{ addslashes($br) }}', '{{ addslashes($br) }}', '🏷️')">
                                    <span class="option-name">🏷️ {{ $br }}</span>
                                    @if(request('brand') === $br) <span class="option-check">✓</span> @endif
                                </button>
                            @endforeach
                            <div class="dropdown-no-results" style="display:none;"><span>@t('shop.no_results', 'No results found')</span></div>
                        </div>
                    </div>
                </div>

                <!-- 5. Pack Size Dropdown -->
                <div class="searchable-dropdown" id="dropdown-pack_size" data-name="pack_size">
                    <button type="button" class="searchable-dropdown-trigger {{ request('pack_size') ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('pack_size')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon" id="icon-pack_size">⚖️</span>
                            <span class="dropdown-trigger-text" id="label-pack_size">{{ request('pack_size') ?: __t('shop.filter_pack_size', 'Pack Size') }}</span>
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
                            <input type="text" class="dropdown-search-input" placeholder="@t('shop.search_pack_size', 'Search pack size...')" oninput="filterDropdownOptions('pack_size', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-pack_size">
                            <button type="button" class="dropdown-option-item {{ empty(request('pack_size')) ? 'selected' : '' }}" data-value="" data-label="@t('shop.all_pack_sizes', 'All Pack Sizes')" onclick="selectDropdownOption('pack_size', '', '{{ addslashes(__t('shop.filter_pack_size', 'Pack Size')) }}', '⚖️')">
                                <span class="option-name">⚖️ @t('shop.all_pack_sizes', 'All Pack Sizes')</span>
                                @if(empty(request('pack_size'))) <span class="option-check">✓</span> @endif
                            </button>
                            @foreach($availablePackSizes as $ps)
                                <button type="button" class="dropdown-option-item {{ request('pack_size') === $ps ? 'selected' : '' }}" data-value="{{ $ps }}" data-label="{{ $ps }}" onclick="selectDropdownOption('pack_size', '{{ addslashes($ps) }}', '{{ addslashes($ps) }}', '⚖️')">
                                    <span class="option-name">⚖️ {{ $ps }}</span>
                                    @if(request('pack_size') === $ps) <span class="option-check">✓</span> @endif
                                </button>
                            @endforeach
                            <div class="dropdown-no-results" style="display:none;"><span>@t('shop.no_results', 'No results found')</span></div>
                        </div>
                    </div>
                </div>

                <!-- 6. Availability Dropdown -->
                <div class="searchable-dropdown" id="dropdown-availability" data-name="availability">
                    <button type="button" class="searchable-dropdown-trigger {{ request('availability') ? 'has-value' : '' }}" onclick="toggleSearchableDropdown('availability')">
                        <span class="dropdown-trigger-content">
                            <span class="dropdown-trigger-icon" id="icon-availability">{{ request('availability') === 'pre_order' ? '📦' : (request('availability') === 'in_stock' ? '🟢' : '⚡') }}</span>
                            <span class="dropdown-trigger-text" id="label-availability">
                                {{ request('availability') === 'in_stock' ? __t('shop.in_stock', 'In Stock') : (request('availability') === 'pre_order' ? __t('shop.pre_order', 'Pre-Order / Sourcing Available') : __t('shop.filter_availability', 'Availability')) }}
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
                            <input type="text" class="dropdown-search-input" placeholder="@t('shop.search_availability', 'Search availability...')" oninput="filterDropdownOptions('availability', this.value)" autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" id="list-availability">
                            <button type="button" class="dropdown-option-item {{ empty(request('availability')) ? 'selected' : '' }}" data-value="" data-label="@t('shop.all_availability', 'All Availability')" onclick="selectDropdownOption('availability', '', '{{ addslashes(__t('shop.filter_availability', 'Availability')) }}', '⚡')">
                                <span class="option-name">⚡ @t('shop.all_availability', 'All Availability')</span>
                                @if(empty(request('availability'))) <span class="option-check">✓</span> @endif
                            </button>
                            <button type="button" class="dropdown-option-item {{ request('availability') === 'in_stock' ? 'selected' : '' }}" data-value="in_stock" data-label="@t('shop.in_stock', 'In Stock')" onclick="selectDropdownOption('availability', 'in_stock', '{{ addslashes(__t('shop.in_stock', 'In Stock')) }}', '🟢')">
                                <span class="option-name">🟢 @t('shop.in_stock', 'In Stock')</span>
                                @if(request('availability') === 'in_stock') <span class="option-check">✓</span> @endif
                            </button>
                            <button type="button" class="dropdown-option-item {{ request('availability') === 'pre_order' ? 'selected' : '' }}" data-value="pre_order" data-label="@t('shop.pre_order', 'Pre-Order / Sourcing Available')" onclick="selectDropdownOption('availability', 'pre_order', '{{ addslashes(__t('shop.pre_order', 'Pre-Order / Sourcing Available')) }}', '📦')">
                                <span class="option-name">📦 @t('shop.pre_order', 'Pre-Order / Sourcing Available')</span>
                                @if(request('availability') === 'pre_order') <span class="option-check">✓</span> @endif
                            </button>
                            <div class="dropdown-no-results" style="display:none;"><span>@t('shop.no_results', 'No options found')</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Business Pricing Sign-in Callout if not logged in as wholesale -->
    @if(!auth()->check() || !in_array($group, ['wholesale', 'trading']))
        <div class="business-pricing-banner" id="businessPricingBanner" style="{{ $currentCustomerType === 'wholesale' ? 'display:flex;' : 'display:none;' }}">
            <div class="business-banner-left">
                <div class="business-banner-icon">💼</div>
                <div>
                    <h3 class="business-banner-title">
                        @t('shop.business_banner_title', 'Buying for Business? Business Verification Required for Wholesale Pricing.')
                    </h3>
                    <p class="business-banner-sub">
                        @t('shop.business_banner_desc', 'Verified commercial accounts access wholesale volume rates, flexible MOQ, consolidated cold-chain delivery and invoice support.')
                    </p>
                </div>
            </div>
            <div class="business-banner-actions">
                <a href="{{ route('login') }}" class="btn-biz-signin">
                    @t('auth.sign_in', 'Sign In')
                </a>
                <a href="{{ route('register', ['type' => 'wholesale']) }}" class="btn-biz-access">
                    @t('shop.request_wholesale_access', 'Request Wholesale Access →')
                </a>
            </div>
        </div>
    @endif

    <!-- ─── Main Shop Content (AJAX Updated) ─── -->
    <div id="shopMainContent" style="position:relative">
        <!-- Modern Page / Tab Switching Loader Bar -->
        <div class="shop-tab-loader" id="shopTabLoader">
            <div class="tab-loader-bar"></div>
        </div>
        <div class="filter-loading-bar"></div>

        <!-- Active Filters Row & Results Header with Sort By -->
        <div class="shop-results-header" id="shopResultsHeader">
            <div class="results-header-left">
                <div class="results-count-text">
                    <span>@t('shop.showing_selected', 'Showing') <strong>{{ $products->total() }}</strong> @t('shop.selected_products_count', 'selected products')</span>
                    @if(request('search'))
                        <span style="color:#64748b">@t('shop.for_keyword', 'for') "<strong>{{ request('search') }}</strong>"</span>
                    @endif
                    @if($activeParentCat)
                        <span style="color:#2563eb;font-weight:700">· {{ $activeParentCat->name }} @if($activeSubCat) &rsaquo; {{ $activeSubCat->name }} @endif</span>
                    @endif
                </div>
                <div class="sourcing-catalog-note">
                    @t('shop.sourcing_catalog_note', 'Need something else? MST can source products beyond our online catalogue.')
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
                                <span>Availability: {{ request('availability') === 'in_stock' ? 'In Stock' : 'Pre-Order / Sourcing' }}</span>
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
                            <span class="dropdown-trigger-icon" id="icon-sort">
                                @switch(request('sort', 'sort_order'))
                                    @case('price_asc') 💵 @break
                                    @case('price_desc') 💎 @break
                                    @case('name') 🔤 @break
                                    @default ✨
                                @endswitch
                            </span>
                            <span class="dropdown-trigger-text" id="label-sort">
                                @switch(request('sort', 'sort_order'))
                                    @case('price_asc')
                                        @t('shop.sort_price_low', 'Price: Low to High')
                                        @break
                                    @case('price_desc')
                                        @t('shop.sort_price_high', 'Price: High to Low')
                                        @break
                                    @case('name')
                                        @t('shop.sort_name_az', 'Name A–Z')
                                        @break
                                    @default
                                        @t('shop.sort_featured', 'Sort: Featured')
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
                            <button type="button" class="dropdown-option-item {{ request('sort', 'sort_order') === 'sort_order' ? 'selected' : '' }}" data-value="sort_order" data-label="@t('shop.sort_featured', 'Sort: Featured')" onclick="selectDropdownOption('sort', 'sort_order', '{{ addslashes(__t('shop.sort_featured', 'Sort: Featured')) }}', '✨')">
                                <span class="option-name">✨ @t('shop.sort_featured_opt', 'Featured Products')</span>
                                @if(request('sort', 'sort_order') === 'sort_order') <span class="option-check">✓</span> @endif
                            </button>
                            <button type="button" class="dropdown-option-item {{ request('sort') === 'price_asc' ? 'selected' : '' }}" data-value="price_asc" data-label="@t('shop.sort_price_low', 'Price: Low to High')" onclick="selectDropdownOption('sort', 'price_asc', '{{ addslashes(__t('shop.sort_price_low', 'Price: Low to High')) }}', '💵')">
                                <span class="option-name">💵 @t('shop.sort_price_low', 'Price: Low to High')</span>
                                @if(request('sort') === 'price_asc') <span class="option-check">✓</span> @endif
                            </button>
                            <button type="button" class="dropdown-option-item {{ request('sort') === 'price_desc' ? 'selected' : '' }}" data-value="price_desc" data-label="@t('shop.sort_price_high', 'Price: High to Low')" onclick="selectDropdownOption('sort', 'price_desc', '{{ addslashes(__t('shop.sort_price_high', 'Price: High to Low')) }}', '💎')">
                                <span class="option-name">💎 @t('shop.sort_price_high', 'Price: High to Low')</span>
                                @if(request('sort') === 'price_desc') <span class="option-check">✓</span> @endif
                            </button>
                            <button type="button" class="dropdown-option-item {{ request('sort') === 'name' ? 'selected' : '' }}" data-value="name" data-label="@t('shop.sort_name_az', 'Name A–Z')" onclick="selectDropdownOption('sort', 'name', '{{ addslashes(__t('shop.sort_name_az', 'Name A–Z')) }}', '🔤')">
                                <span class="option-name">🔤 @t('shop.sort_name_az', 'Name A–Z')</span>
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
                            $isApprovedWholesale = auth()->check() && in_array($group, ['wholesale', 'trading']);
                            
                            $tempLower = strtolower($product->storage_temp ?? '');
                            $prodNameLower = strtolower($product->name ?? '');
                            $isLive = str_contains($tempLower, 'live') || str_contains($prodNameLower, 'live');
                            $isChilled = str_contains($tempLower, 'chilled');
                            $isIqf = str_contains($prodNameLower, 'iqf') || str_contains($tempLower, 'iqf');

                            if ($isLive) {
                                $storageLabel = '🦀 Live / Chilled';
                            } elseif ($isChilled) {
                                $storageLabel = '🧊 0°C to 4°C Chilled';
                            } elseif ($isIqf) {
                                $storageLabel = '❄️ -18°C · IQF';
                            } elseif ($product->storage_temp) {
                                $storageLabel = '❄️ Frozen · ' . $product->storage_temp;
                            } else {
                                $storageLabel = '❄️ Frozen · -18°C';
                            }

                            // Availability status text
                            $availabilityLabel = 'Available';
                            $availabilityClass = 'avail-in-stock';
                            if ($product->is_rfq_only) {
                                $availabilityLabel = 'Pre-Order / Sourcing Available';
                                $availabilityClass = 'avail-pre-order';
                            } elseif ($product->track_stock && $product->stock_quantity <= 5) {
                                $availabilityLabel = 'Limited Availability';
                                $availabilityClass = 'avail-limited';
                            }

                            // Price calculation for current view
                            $displayPrice = $product->getDisplayPrice($group);
                            $priceFormatted = $displayPrice['formatted'];
                            $basePriceAmount = $product->getPriceForGroup($group);

                            // Sensitive or RFQ product check
                            $isSensitiveOrRfq = $product->is_rfq_only || $product->retail_price === null;
                        @endphp

                        <div class="product-item-card {{ $currentCustomerType === 'wholesale' && !$isApprovedWholesale ? 'card-wholesale-preview' : '' }}">
                            <!-- Product Image Container -->
                            <div class="product-img-box">
                                <a href="{{ route('shop.show', $product) }}" class="product-img-link" title="{{ $product->name }}">
                                    @if($product->thumbnail)
                                        <img src="{{ cdn_storage($product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy" class="card-product-img">
                                    @else
                                        <div class="product-placeholder-icon">📦</div>
                                    @endif
                                </a>

                                <!-- Floating Badges Top Left -->
                                <div class="card-badge-cluster">
                                    @if($product->is_featured)
                                        <span class="badge-featured">⭐ @t('shop.badge_featured', 'Featured')</span>
                                    @endif
                                    @if($product->origin)
                                        <span class="badge-origin">{{ $getOriginFlag($product->origin) }} {{ $product->origin }}</span>
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
                                    data-category="{{ $product->category?->name ?? 'Products' }}"
                                    data-sku="{{ $product->sku ?? 'N/A' }}"
                                    data-origin="{{ $product->origin ?? '' }}"
                                    data-weight="{{ $product->weight ?? '' }}"
                                    data-unit="{{ $product->unit ?? 'pack' }}"
                                    data-storage="{{ $storageLabel }}"
                                    data-availability="{{ $availabilityLabel }}"
                                    data-is-rfq="{{ $isSensitiveOrRfq ? '1' : '0' }}"
                                    data-customer-type="{{ $currentCustomerType }}"
                                    data-price-formatted="{{ $priceFormatted }}"
                                    data-base-rm="{{ ($isApprovedWholesale || $currentCustomerType === 'retail') && !$isSensitiveOrRfq ? ($basePriceAmount ?? 0) : 0 }}"
                                    data-manual-sgd="{{ (!$isSensitiveOrRfq && ($currentCustomerType === 'retail' || $isApprovedWholesale)) ? ($product->price_sgd ?? '') : '' }}"
                                    data-manual-usd="{{ (!$isSensitiveOrRfq && ($currentCustomerType === 'retail' || $isApprovedWholesale)) ? ($product->price_usd ?? '') : '' }}"
                                    @if($isApprovedWholesale)
                                    data-manual-wholesale-sgd="{{ $product->wholesale_price_sgd ?? '' }}"
                                    data-manual-wholesale-usd="{{ $product->wholesale_price_usd ?? '' }}"
                                    @endif
                                    data-group="{{ $group }}"
                                    data-moq="{{ $isApprovedWholesale && $product->getMoqForGroup($group) > 1 ? ($product->getMoqForGroup($group) . ' ' . $product->unit) : '' }}"
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
                                    <span class="card-cat-name">{{ $product->category?->name ?? 'Products & Sourcing' }}</span>
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

                                <!-- Pack Size / Brand / Availability Row -->
                                <div class="card-spec-row">
                                    @if($product->weight)
                                        <span class="spec-chip">📦 {{ $product->weight }}</span>
                                    @endif
                                    @if($product->brand && !str_contains($product->brand, 'SDN BHD'))
                                        <span class="spec-chip spec-brand">🏷️ {{ $product->brand }}</span>
                                    @endif
                                    <span class="spec-chip {{ $availabilityClass }}">{{ $availabilityLabel }}</span>
                                </div>

                                <!-- Pricing Block (Strict Server-Side Access Control) -->
                                <div class="card-pricing-block">
                                    <!-- Retail Pricing Mode Container -->
                                    <div class="pricing-mode-retail">
                                        @if($isSensitiveOrRfq)
                                            <div class="price-on-request-box">
                                                <span class="price-req-title">@t('shop.price_on_request', 'Price available upon request')</span>
                                                <span class="price-req-sub">@t('shop.availability_subject_confirmation', 'Availability subject to stock and supply confirmation.')</span>
                                            </div>
                                        @else
                                            <div class="product-card-price js-currency-price"
                                                 data-base-rm="{{ $product->retail_price }}"
                                                 data-manual-sgd="{{ $product->price_sgd ?? '' }}"
                                                 data-manual-usd="{{ $product->price_usd ?? '' }}"
                                            >
                                                <span class="price-prefix-from">@t('shop.from_price', 'From')</span>
                                                <span class="price-amount price-val">{{ $priceFormatted }}</span>
                                                <span class="price-base-rm price-sub-myr" style="display:{{ ($currentCurrency !== 'MYR' && !empty($displayPrice['base_rm'])) ? 'block' : 'none' }}">
                                                    RM {{ number_format($product->retail_price, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Wholesale Pricing Mode Container -->
                                    <div class="pricing-mode-wholesale">
                                        @if($isApprovedWholesale)
                                            @if($basePriceAmount !== null && !$product->is_rfq_only)
                                                <div class="product-card-price js-currency-price"
                                                     data-base-rm="{{ $basePriceAmount }}"
                                                     data-manual-sgd="{{ $product->price_sgd ?? '' }}"
                                                     data-manual-usd="{{ $product->price_usd ?? '' }}"
                                                     data-manual-wholesale-sgd="{{ $product->wholesale_price_sgd ?? '' }}"
                                                     data-manual-wholesale-usd="{{ $product->wholesale_price_usd ?? '' }}"
                                                     data-group="{{ $group }}"
                                                >
                                                    <span class="price-tier-tag">🏢 {{ ucfirst($group) }}</span>
                                                    <span class="price-amount price-val">{{ $priceFormatted }}</span>
                                                    <span class="price-base-rm price-sub-myr" style="display:{{ ($currentCurrency !== 'MYR' && !empty($displayPrice['base_rm'])) ? 'block' : 'none' }}">
                                                        RM {{ number_format($basePriceAmount, 2) }}
                                                    </span>
                                                </div>
                                                @if($product->getMoqForGroup($group) > 1)
                                                    <div class="moq-badge">MOQ: {{ $product->getMoqForGroup($group) }} {{ $product->unit }}</div>
                                                @endif
                                            @else
                                                <div class="price-on-request-box">
                                                    <span class="price-req-title">@t('shop.negotiated_pricing', 'Negotiated Pricing')</span>
                                                    <span class="price-req-sub">@t('shop.availability_subject_confirmation', 'Availability subject to stock and supply confirmation.')</span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="wholesale-locked-box">
                                                <div class="wholesale-locked-title">🏢 @t('shop.wholesale_pricing_locked', 'Wholesale Pricing')</div>
                                                <div class="wholesale-locked-sub">@t('shop.business_account_required', 'Business account required')</div>
                                                <a href="{{ route('register', ['type' => 'wholesale']) }}" class="btn-request-wholesale-link">
                                                    @t('shop.request_wholesale_access', 'Request Wholesale Access →')
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Card Action Buttons -->
                                <div class="card-action-btns">
                                    <!-- Retail Actions -->
                                    <div class="card-action-row actions-mode-retail">
                                        @if(!$isSensitiveOrRfq && $product->retail_price !== null)
                                            <form action="{{ route('cart.add') }}" method="POST" class="product-cart-form" style="flex:1;margin:0;">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn-card-add">
                                                    🛒 @t('shop.add_to_cart', 'Add to Cart')
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('quotations.create', ['product' => $product->id]) }}" class="btn-card-rfq" style="flex:1">
                                                📋 @t('shop.request_a_quote', 'Request a Quote →')
                                            </a>
                                        @endif

                                        <button type="button" class="btn-card-quickview-mobile js-quickview-btn"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-category="{{ $product->category?->name ?? 'Products' }}"
                                            data-sku="{{ $product->sku ?? 'N/A' }}"
                                            data-origin="{{ $product->origin ?? '' }}"
                                            data-weight="{{ $product->weight ?? '' }}"
                                            data-unit="{{ $product->unit ?? 'pack' }}"
                                            data-storage="{{ $storageLabel }}"
                                            data-availability="{{ $availabilityLabel }}"
                                            data-is-rfq="{{ $isSensitiveOrRfq ? '1' : '0' }}"
                                            data-customer-type="retail"
                                            data-price-formatted="{{ $priceFormatted }}"
                                            data-base-rm="{{ !$isSensitiveOrRfq ? ($product->retail_price ?? 0) : 0 }}"
                                            data-manual-sgd="{{ !$isSensitiveOrRfq ? ($product->price_sgd ?? '') : '' }}"
                                            data-manual-usd="{{ !$isSensitiveOrRfq ? ($product->price_usd ?? '') : '' }}"
                                            data-group="{{ $group }}"
                                            data-moq=""
                                            data-desc="{{ $product->short_description ?? $product->description ?? '' }}"
                                            data-image="{{ $product->thumbnail ? cdn_storage($product->thumbnail) : '' }}"
                                            data-url="{{ route('shop.show', $product) }}"
                                            data-rfq-url="{{ route('quotations.create', ['product' => $product->id]) }}"
                                            title="Quick View"
                                        >
                                            👁️
                                        </button>
                                    </div>

                                    @if(!$isSensitiveOrRfq && $product->retail_price !== null)
                                        <a href="javascript:void(0)" onclick="setCustomerType('wholesale')" class="card-wholesale-inquiry-link actions-mode-retail" title="Wholesale / Business Pricing">
                                            @t('shop.wholesale_business_pricing_link', 'Wholesale / Business Pricing →')
                                        </a>
                                    @endif

                                    <!-- Wholesale Actions -->
                                    <div class="card-action-row actions-mode-wholesale">
                                        @if($isApprovedWholesale && $basePriceAmount !== null && !$product->is_rfq_only)
                                            <form action="{{ route('cart.add') }}" method="POST" class="product-cart-form" style="flex:1;margin:0;">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="{{ $product->getMoqForGroup($group) }}">
                                                <button type="submit" class="btn-card-add">
                                                    🛒 @t('shop.add_to_cart', 'Add to Cart')
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('quotations.create', ['product' => $product->id]) }}" class="btn-card-rfq" style="flex:1">
                                                📋 @t('shop.request_a_quote', 'Request a Quote →')
                                            </a>
                                        @endif

                                        <button type="button" class="btn-card-quickview-mobile js-quickview-btn"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-category="{{ $product->category?->name ?? 'Products' }}"
                                            data-sku="{{ $product->sku ?? 'N/A' }}"
                                            data-origin="{{ $product->origin ?? '' }}"
                                            data-weight="{{ $product->weight ?? '' }}"
                                            data-unit="{{ $product->unit ?? 'pack' }}"
                                            data-storage="{{ $storageLabel }}"
                                            data-availability="{{ $availabilityLabel }}"
                                            data-is-rfq="{{ $isSensitiveOrRfq ? '1' : '0' }}"
                                            data-customer-type="wholesale"
                                            data-price-formatted="{{ $priceFormatted }}"
                                            data-base-rm="{{ $isApprovedWholesale && !$isSensitiveOrRfq ? ($basePriceAmount ?? 0) : 0 }}"
                                            data-manual-sgd="{{ $isApprovedWholesale && !$isSensitiveOrRfq ? ($product->price_sgd ?? '') : '' }}"
                                            data-manual-usd="{{ $isApprovedWholesale && !$isSensitiveOrRfq ? ($product->price_usd ?? '') : '' }}"
                                            @if($isApprovedWholesale)
                                            data-manual-wholesale-sgd="{{ $product->wholesale_price_sgd ?? '' }}"
                                            data-manual-wholesale-usd="{{ $product->wholesale_price_usd ?? '' }}"
                                            @endif
                                            data-group="{{ $group }}"
                                            data-moq="{{ $isApprovedWholesale && $product->getMoqForGroup($group) > 1 ? ($product->getMoqForGroup($group) . ' ' . $product->unit) : '' }}"
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
                        </div>
                    @endforeach
                </div>

                <!-- ─── Pricing & Quotation Disclaimers ─── -->
                <div class="shop-disclaimer-strip">
                    <span class="disclaimer-icon">ℹ️</span>
                    <div class="disclaimer-texts">
                        <p class="disclaimer-line">
                            <strong>@t('shop.pricing_disclaimer_head', 'Pricing & Availability Notice:')</strong>
                            @t('shop.pricing_disclaimer', 'Prices and availability are subject to change without prior notice and may vary according to order volume, product specification, market conditions and supply availability.')
                        </p>
                        <p class="disclaimer-line disclaimer-wholesale-note">
                            @t('shop.wholesale_trading_disclaimer', 'Wholesale and trading prices are quotation-based and may vary according to product specification, quantity, origin and market conditions.')
                        </p>
                        @if($currentCurrency !== 'MYR')
                            <p class="disclaimer-line disclaimer-currency-note">
                                @t('shop.currency_indicative_disclaimer', 'Currency conversion is indicative only. Final pricing may vary according to the applicable exchange rate.')
                            </p>
                        @endif
                    </div>
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
                        @t('shop.sourcing_brief_desc', "Tell us your preferred product, specification, origin, pack size or brand. Our sourcing team can help coordinate availability and quotation.")
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

    <!-- ─── 6. Custom Sourcing Section (Prominent) ─── -->
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
                @t('shop.sourcing_brief_desc', "Tell us your preferred product, specification, origin, pack size or brand. Our sourcing team can help coordinate availability and quotation.")
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
                @t('shop.regular_supply_brief_desc', 'For restaurants, hotels, caterers, retailers, traders and other commercial buyers, MST provides wholesale supply, bulk ordering and customised sourcing support.')
            </p>
        </div>
        <div class="wholesale-cta-btns">
            <a href="{{ route('register', ['type' => 'wholesale']) }}" class="btn-wholesale-access">
                @t('shop.request_wholesale_access_btn', 'Request Wholesale Access →')
            </a>
            <a href="{{ route('quotations.create') }}" class="btn-wholesale-quote">
                @t('shop.request_a_quote', 'Request a Quote →')
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
                <div id="qvImgPlaceholder" style="display:none;width:100%;height:100%;align-items:center;justify-content:center;font-size:3rem;background:#f1f5f9;color:#94a3b8">📦</div>
                <span class="quickview-origin-tag" id="qvOriginTag" style="display:none"></span>
            </div>
            <div class="quickview-details">
                <div class="quickview-category" id="qvCategory"></div>
                <h2 class="quickview-title" id="qvTitle"></h2>
                <div class="quickview-sku-bar">
                    <span>@t('shop.sku_label', 'SKU:') <strong id="qvSku"></strong></span>
                    <span>@t('shop.storage_label', 'Storage:') <strong id="qvStorage"></strong></span>
                    <span>@t('shop.status_label', 'Status:') <strong id="qvAvail"></strong></span>
                </div>
                
                <div class="quickview-price-box" id="qvPriceBox">
                    <div style="display:flex;flex-direction:column">
                        <span class="quickview-price-prefix" id="qvPricePrefix" style="display:none;font-size:0.85rem;color:#64748b;font-weight:600">From</span>
                        <span class="quickview-price" id="qvPrice"></span>
                        <span class="quickview-base-rm" id="qvBaseRm" style="font-size:0.8rem;color:#64748b;font-weight:500"></span>
                    </div>
                    <div id="qvMoqBadge" style="display:none;background:#eff6ff;color:#1d4ed8;font-size:0.75rem;font-weight:700;padding:3px 8px;border-radius:6px"></div>
                </div>

                <div id="qvWholesaleLockedBox" class="wholesale-locked-box" style="display:none;margin-bottom:16px;">
                    <div class="wholesale-locked-title">🏢 @t('shop.wholesale_pricing_locked', 'Wholesale Pricing')</div>
                    <div class="wholesale-locked-sub">@t('shop.business_account_required', 'Business account required')</div>
                    <a href="{{ route('register', ['type' => 'wholesale']) }}" class="btn-request-wholesale-link">
                        @t('shop.request_wholesale_access', 'Request Wholesale Access →')
                    </a>
                </div>

                <p class="quickview-desc" id="qvDesc"></p>
                <div class="quickview-actions">
                    <form action="{{ route('cart.add') }}" method="POST" id="qvCartForm" style="flex:1">
                        @csrf
                        <input type="hidden" name="product_id" id="qvProductId">
                        <div style="display:flex;gap:10px">
                            <input type="number" name="quantity" id="qvQty" value="1" min="1" class="quickview-qty-input" style="width:70px;height:44px;border:1.5px solid #cbd5e1;border-radius:10px;text-align:center;font-weight:700;font-size:0.95rem">
                            <button type="submit" class="btn btn-primary" style="flex:1;height:44px;background:#2563eb;border:none;color:#ffffff !important;font-weight:700;border-radius:10px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:6px;box-shadow:0 3px 10px rgba(37,99,235,0.3)">
                                🛒 <span style="color:#ffffff !important">@t('shop.add_to_cart', 'Add to Cart')</span>
                            </button>
                        </div>
                    </form>
                    <a href="#" id="qvRfqLink" class="btn btn-primary" onclick="closeQuickViewModal()" style="display:none;flex:1;height:44px;background:#2563eb;color:#ffffff !important;font-weight:700;border-radius:10px;text-decoration:none;align-items:center;justify-content:center;gap:6px">
                        📋 <span style="color:#ffffff !important">@t('shop.request_a_quote', 'Request a Quote →')</span>
                    </a>
                    <a href="#" id="qvDetailsLink" class="btn btn-secondary" onclick="closeQuickViewModal()" style="height:44px;display:inline-flex;align-items:center;justify-content:center;padding:0 16px;font-weight:700;border-radius:10px;text-decoration:none">
                        @t('shop.details', 'Details')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ─── Hero Section (MST Deep Navy Gradient) ─── */
.products-hero-section {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #07152b 0%, #0c234b 45%, #1d4ed8 100%);
    color: #ffffff;
    border-bottom: 1px solid rgba(37, 99, 235, 0.35);
    padding-top: calc(78px + 28px);
    padding-bottom: 32px;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.products-hero-grid-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.08;
    background-image: radial-gradient(#38bdf8 1.5px, transparent 1.5px);
    background-size: 24px 24px;
    pointer-events: none;
}
.products-hero-inner {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}
.hero-content-left {
    max-width: 820px;
}
.hero-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.82rem;
    margin-bottom: 8px;
}
.hero-crumb-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #bae6fd;
    text-decoration: none;
    font-weight: 500;
}
.hero-crumb-link:hover {
    color: #ffffff;
    text-decoration: underline;
}
.hero-crumb-sep {
    color: #60a5fa;
    margin: 0 2px;
}
.hero-crumb-current {
    color: #ffffff;
    font-weight: 600;
}
.products-hero-title {
    font-family: var(--font-heading, 'Outfit', sans-serif);
    font-size: clamp(1.75rem, 3.5vw, 2.4rem);
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 6px;
    letter-spacing: -0.02em;
    line-height: 1.2;
}
.products-hero-subtitle {
    font-size: 0.96rem;
    color: #e0f2fe;
    line-height: 1.55;
    margin: 0;
    font-weight: 400;
    max-width: 760px;
}
.brand-slogan-pill {
    font-size: 0.85rem;
    padding: 6px 14px;
    border-radius: 999px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    background: #091a36;
    color: #7dd3fc;
    border: 1px solid #2563eb;
    font-weight: 600;
    white-space: nowrap;
}
.user-tier-pill {
    font-size: 0.85rem;
    padding: 6px 14px;
    border-radius: 999px;
    background: #091a36;
    color: #7dd3fc;
    border: 1px solid #2563eb;
    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
}

/* ─── Shopping Mode Switcher ─── */
.customer-type-inline-wrap {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    flex-wrap: wrap;
}
.cust-mode-label {
    font-size: 0.82rem;
    font-weight: 600;
    color: #475569;
    white-space: nowrap;
}
.customer-type-toggle {
    display: inline-flex;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 10px;
    gap: 3px;
    border: 1px solid #e2e8f0;
}
.cust-type-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 7px;
    font-size: 0.78rem;
    font-weight: 600;
    border: none;
    background: transparent;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
}
.cust-type-btn:hover {
    color: #0c234b;
}
.cust-type-btn.active {
    background: #2563eb;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
}
.customer-mode-caption {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.mode-caption-tag {
    font-weight: 700;
    color: #1e40af;
}
.mode-caption-text {
    color: #475569;
}

/* On mobile, make the search and customer toggle stack nicely */
@media(max-width: 640px) {
    .shop-search-filter-controls {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    .shop-search-main-row {
        min-width: 0;
    }
    .customer-type-inline-wrap {
        justify-content: flex-start;
        width: 100%;
    }
    .customer-type-toggle {
        flex: 1;
    }
    .cust-type-btn {
        flex: 1;
        justify-content: center;
        font-size: 0.74rem;
        padding: 5px 6px;
    }
    .customer-mode-caption {
        font-size: 0.74rem;
        margin-bottom: 6px;
    }
}

/* ─── Business Banner ─── */
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

/* ─── Search & Filter Bar ─── */
.shop-filter-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 14px;
    margin-bottom: 14px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
}
.shop-search-filter-controls {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}
.shop-search-main-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 260px;
}
.shop-search-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1;
    min-width: 200px;
    margin-bottom: 0;
}
.search-svg {
    position: absolute;
    left: 12px;
    color: #64748b;
    pointer-events: none;
}
.shop-main-search-input {
    width: 100%;
    height: 38px;
    padding: 0 36px 0 38px;
    border: 1px solid #cbd5e1;
    border-radius: 9px;
    font-size: 0.88rem;
    color: #0f172a;
    background: #ffffff;
    outline: none;
    transition: all 0.15s ease;
    box-sizing: border-box;
}
.shop-main-search-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
.search-clear-btn {
    position: absolute;
    right: 12px;
    color: #94a3b8;
    background: none;
    border: none;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.85rem;
}
.shop-filters-row {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 8px;
    position: relative;
}
@media(max-width:1024px) {
    .shop-filters-row { grid-template-columns: repeat(3, 1fr); }
}
@media(max-width:640px) {
    .shop-filters-row { display: none; }
    .shop-filters-row.show-mobile { display: grid; grid-template-columns: 1fr; }
}
.searchable-dropdown {
    position: relative;
    min-width: 0;
}
.searchable-dropdown-trigger {
    width: 100%;
    height: 36px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0 10px;
    font-size: 0.8rem;
    font-weight: 500;
    color: #334155;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    transition: all 0.15s ease;
    box-sizing: border-box;
    text-align: left;
}
.searchable-dropdown-trigger:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}
.searchable-dropdown-trigger.has-value {
    border-color: #3b82f6;
    background: #eff6ff;
    color: #1e40af;
    font-weight: 600;
}
.dropdown-trigger-content {
    display: flex;
    align-items: center;
    gap: 6px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.dropdown-trigger-icon {
    font-size: 0.9rem;
    flex-shrink: 0;
}
.dropdown-trigger-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.dropdown-trigger-arrows {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
    color: #94a3b8;
}
.dropdown-clear-btn {
    font-size: 0.75rem;
    color: #94a3b8;
    padding: 0 2px;
}
.dropdown-clear-btn:hover {
    color: #ef4444;
}
.searchable-dropdown-menu {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    min-width: 220px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    z-index: 50;
    display: none;
    max-height: 280px;
    flex-direction: column;
    overflow: hidden;
}
.searchable-dropdown-menu.active {
    display: flex;
}
.dropdown-search-header {
    display: flex;
    align-items: center;
    padding: 6px 10px;
    border-bottom: 1px solid #f1f5f9;
    gap: 6px;
    background: #f8fafc;
}
.dropdown-search-icon {
    color: #94a3b8;
    flex-shrink: 0;
}
.dropdown-search-input {
    width: 100%;
    border: none;
    background: transparent;
    font-size: 0.78rem;
    outline: none;
    color: #0f172a;
}
.dropdown-options-list {
    overflow-y: auto;
    padding: 4px;
}
.dropdown-option-item {
    width: 100%;
    padding: 7px 10px;
    font-size: 0.78rem;
    color: #334155;
    background: transparent;
    border: none;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    text-align: left;
    transition: background 0.1s ease;
}
.dropdown-option-item:hover {
    background: #f1f5f9;
    color: #0f172a;
}
.dropdown-option-item.selected {
    background: #eff6ff;
    color: #1e40af;
    font-weight: 600;
}
.option-check {
    color: #2563eb;
    font-weight: 700;
}
.dropdown-no-results {
    padding: 12px;
    text-align: center;
    color: #94a3b8;
    font-size: 0.78rem;
}

/* ─── Results Header & Chips ─── */
.shop-results-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 16px;
    gap: 16px;
    flex-wrap: wrap;
}
.results-header-left {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.results-count-text {
    font-size: 0.92rem;
    color: #334155;
}
.sourcing-catalog-note {
    font-size: 0.8rem;
    color: #0369a1;
    font-weight: 500;
}
.active-chips-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 6px;
}
.active-filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 3px 8px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    font-size: 0.74rem;
    color: #1e40af;
    text-decoration: none;
    font-weight: 500;
}
.badge-x {
    font-size: 0.7rem;
    opacity: 0.7;
}
.clear-all-link {
    font-size: 0.74rem;
    color: #ef4444;
    text-decoration: underline;
    align-self: center;
    margin-left: 4px;
}

/* ─── Product Card Grid ─── */
.products-catalogue-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}
@media(max-width:1200px) {
    .products-catalogue-grid { grid-template-columns: repeat(3, 1fr); }
}
@media(max-width:768px) {
    .products-catalogue-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
}
@media(max-width:480px) {
    /* Keep 2 columns on mobile — never collapse to 1 */
    .products-catalogue-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
}

.product-item-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
}
.product-item-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    border-color: #cbd5e1;
}

/* Product Card Media Box */
.product-img-box {
    position: relative;
    width: 100%;
    padding-top: 75%;
    background: #f8fafc;
    overflow: hidden;
}
.product-img-link {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.card-product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.product-item-card:hover .card-product-img {
    transform: scale(1.04);
}
.product-placeholder-icon {
    font-size: 3rem;
    color: #94a3b8;
}

/* Badges */
.card-badge-cluster {
    position: absolute;
    top: 8px;
    left: 8px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    z-index: 2;
}
.badge-featured {
    background: rgba(254, 240, 138, 0.95);
    color: #854d0e;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 6px;
    backdrop-filter: blur(4px);
    border: 1px solid #fef08a;
}
.badge-origin {
    background: rgba(255, 255, 255, 0.92);
    color: #0f172a;
    font-size: 0.68rem;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 6px;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(226, 232, 240, 0.8);
}
.card-storage-badge {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background: rgba(10, 25, 47, 0.85);
    backdrop-filter: blur(4px);
    color: #ffffff;
    font-size: 0.68rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 6px;
    z-index: 2;
    border: 1px solid rgba(255, 255, 255, 0.15);
}
.card-storage-badge.storage-live {
    background: rgba(180, 83, 9, 0.9);
}

/* Quick View Hover Button */
.btn-card-quickview {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #cbd5e1;
    color: #0f172a;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 4px 9px;
    border-radius: 6px;
    cursor: pointer;
    z-index: 2;
    opacity: 0;
    transform: translateY(4px);
    transition: all 0.18s ease;
    backdrop-filter: blur(4px);
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
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.card-meta-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.72rem;
    color: #64748b;
    margin-bottom: 4px;
}
.card-cat-name {
    color: #2563eb;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}
.card-sku {
    color: #94a3b8;
    font-family: monospace;
}
.product-item-title {
    font-size: 0.94rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.35;
    margin: 0 0 8px;
}
.product-item-title a {
    color: inherit;
    text-decoration: none;
}
.product-item-title a:hover {
    color: #2563eb;
}

/* Card Spec Row */
.card-spec-row {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-bottom: 10px;
}
.spec-chip {
    font-size: 0.68rem;
    padding: 2px 6px;
    border-radius: 5px;
    background: #f1f5f9;
    color: #475569;
    font-weight: 500;
}
.spec-chip.avail-in-stock {
    background: #ecfdf5;
    color: #065f46;
}
.spec-chip.avail-limited {
    background: #fffbeb;
    color: #92400e;
}
.spec-chip.avail-pre-order {
    background: #eff6ff;
    color: #1e40af;
}

/* Pricing States */
.card-pricing-block {
    margin-top: auto;
    padding-top: 8px;
    border-top: 1px solid #f1f5f9;
    min-height: 48px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.product-card-price {
    display: flex;
    align-items: baseline;
    gap: 6px;
    flex-wrap: wrap;
}
.price-prefix-from {
    font-size: 0.78rem;
    color: #64748b;
    font-weight: 600;
}
.price-tier-tag {
    font-size: 0.68rem;
    background: #eff6ff;
    color: #1e40af;
    padding: 1px 5px;
    border-radius: 4px;
    font-weight: 700;
}
.price-amount {
    font-size: 1.25rem;
    font-weight: 800;
    color: #1d4ed8;
    font-family: var(--font-heading, sans-serif);
}
.price-base-rm {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 500;
}
.moq-badge {
    font-size: 0.72rem;
    font-weight: 700;
    color: #0284c7;
    margin-top: 2px;
}
.wholesale-locked-box {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    padding: 6px 8px;
    text-align: center;
}
.wholesale-locked-title {
    font-size: 0.82rem;
    font-weight: 700;
    color: #1e40af;
}
.wholesale-locked-sub {
    font-size: 0.7rem;
    color: #64748b;
    margin-bottom: 4px;
}
.btn-request-wholesale-link {
    font-size: 0.72rem;
    color: #2563eb;
    font-weight: 700;
    text-decoration: underline;
    display: inline-block;
}
.price-on-request-box {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.price-req-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0369a1;
}
.price-req-sub {
    font-size: 0.68rem;
    color: #64748b;
    line-height: 1.3;
}

/* Action Buttons */
.card-action-btns {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-top: auto;
    padding-top: 10px;
    width: 100%;
}
.card-action-row {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
}
.product-cart-form {
    display: flex;
    flex: 1;
    margin: 0;
    min-width: 0;
}
.btn-card-add {
    width: 100%;
    height: 38px;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border: none;
    border-radius: 9px;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(37,99,235,0.2);
    white-space: nowrap;
    padding: 0 10px;
    overflow: hidden;
    text-overflow: ellipsis;
}
.btn-card-add:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
}
.card-wholesale-inquiry-link {
    font-size: 0.72rem;
    color: #0369a1;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    padding: 2px 0 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
    width: 100%;
    line-height: 1.3;
}
.card-wholesale-inquiry-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}
.btn-card-rfq {
    height: 38px;
    background: linear-gradient(135deg, #0f766e 0%, #0d6360 100%);
    color: #ffffff;
    border-radius: 9px;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.15s ease;
    padding: 0 10px;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(15,118,110,0.2);
}
.btn-card-rfq:hover {
    background: linear-gradient(135deg, #115e59 0%, #0d4f4c 100%);
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15,118,110,0.3);
}
.btn-card-quickview-mobile {
    width: 38px;
    height: 38px;
    min-width: 38px;
    background: #f1f5f9;
    border: 1.5px solid #e2e8f0;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    cursor: pointer;
    color: #475569;
    flex-shrink: 0;
    transition: all 0.15s ease;
    padding: 0;
    box-sizing: border-box;
}
.btn-card-quickview-mobile:hover {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
    transform: translateY(-1px);
}

/* Mobile card adjustments */
@media(max-width: 640px) {
    .product-item-body {
        padding: 8px 10px 10px;
    }
    .product-item-title {
        font-size: 0.82rem;
        margin-bottom: 5px;
    }
    .card-meta-bar {
        font-size: 0.65rem;
    }
    .card-sku {
        display: none; /* hide SKU on very small cards */
    }
    .card-spec-row {
        margin-bottom: 6px;
    }
    .spec-chip {
        font-size: 0.62rem;
        padding: 1px 5px;
    }
    .price-amount {
        font-size: 1.05rem;
    }
    .card-pricing-block {
        min-height: 36px;
    }
    .btn-card-add {
        height: 34px;
        font-size: 0.75rem;
        gap: 4px;
    }
    .btn-card-rfq {
        height: 34px;
        font-size: 0.75rem;
        padding: 0 7px;
    }
    .btn-card-quickview-mobile {
        width: 34px;
        height: 34px;
        min-width: 34px;
    }
    .card-wholesale-inquiry-link {
        font-size: 0.65rem;
    }
    .card-action-btns {
        gap: 4px;
        margin-top: auto;
        padding-top: 6px;
    }
    .card-action-row {
        gap: 5px;
    }
    .badge-featured, .badge-origin {
        font-size: 0.6rem;
        padding: 1px 5px;
    }
    .card-storage-badge {
        font-size: 0.6rem;
        padding: 1px 6px;
    }
}

/* ─── Disclaimers Strip ─── */
.shop-disclaimer-strip {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 18px;
    margin-top: 32px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
}
.disclaimer-icon {
    font-size: 1.1rem;
    flex-shrink: 0;
    margin-top: 1px;
}
.disclaimer-texts {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.disclaimer-line {
    font-size: 0.78rem;
    color: #64748b;
    line-height: 1.45;
    margin: 0;
}
.disclaimer-wholesale-note {
    color: #475569;
}
.disclaimer-currency-note {
    color: #0369a1;
    font-weight: 500;
}

/* ─── Custom Sourcing Banner (Section 28) ─── */
.custom-sourcing-banner {
    background: linear-gradient(135deg, #091a36 0%, #0f2b5c 50%, #1e40af 100%);
    color: #ffffff;
    border-radius: 18px;
    padding: 28px 32px;
    margin-top: 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    border: 1px solid rgba(59, 130, 246, 0.35);
    box-shadow: 0 10px 30px rgba(9, 26, 54, 0.15);
}
.sourcing-pill-tag {
    display: inline-block;
    background: rgba(56, 189, 248, 0.2);
    border: 1px solid rgba(186, 230, 253, 0.4);
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #7dd3fc;
    letter-spacing: 0.04em;
    margin-bottom: 8px;
}
.sourcing-banner-head {
    font-size: clamp(1.3rem, 2.5vw, 1.75rem);
    font-weight: 800;
    margin: 0 0 2px;
    color: #ffffff;
}
.sourcing-banner-subhead {
    font-size: clamp(1.05rem, 2vw, 1.3rem);
    font-weight: 700;
    color: #38bdf8;
    margin: 0 0 8px;
}
.sourcing-banner-p {
    font-size: 0.9rem;
    color: #cbd5e1;
    margin: 0;
    max-width: 680px;
    line-height: 1.5;
}
.btn-sourcing-action {
    background: #38bdf8;
    color: #0f172a;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.92rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.15s ease;
    box-shadow: 0 4px 14px rgba(56, 189, 248, 0.35);
    white-space: nowrap;
}
.btn-sourcing-action:hover {
    background: #7dd3fc;
    transform: translateY(-2px);
}

/* ─── Wholesale Supply CTA (Section 29) ─── */
.wholesale-supply-cta {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 28px 32px;
    margin-top: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
}
.wholesale-cta-title {
    font-size: clamp(1.2rem, 2.2vw, 1.5rem);
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 6px;
}
.wholesale-cta-desc {
    font-size: 0.88rem;
    color: #475569;
    margin: 0;
    max-width: 680px;
    line-height: 1.5;
}
.wholesale-cta-btns {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.btn-wholesale-access {
    background: #2563eb;
    color: #ffffff;
    padding: 11px 22px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.88rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    white-space: nowrap;
}
.btn-wholesale-quote {
    background: #f1f5f9;
    color: #1e293b;
    padding: 11px 22px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.88rem;
    text-decoration: none;
    border: 1px solid #cbd5e1;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
}

/* ─── Empty State ─── */
.empty-sourcing-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 48px 24px;
    text-align: center;
    margin: 20px 0;
}
.empty-icon-wrap {
    font-size: 3rem;
    margin-bottom: 12px;
}
.empty-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 4px;
}
.empty-subtitle {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2563eb;
    margin: 0 0 10px;
}
.empty-desc {
    font-size: 0.9rem;
    color: #64748b;
    max-width: 540px;
    margin: 0 auto 20px;
    line-height: 1.5;
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
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.88rem;
    text-decoration: none;
}
.btn-sourcing-secondary {
    background: #f1f5f9;
    color: #334155;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.88rem;
    text-decoration: none;
}

/* ─── Quick View Modal ─── */
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
    flex-wrap: wrap;
    gap: 12px;
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
    background: #2563eb;
    color: #ffffff !important;
    border: none;
    font-weight: 700;
    border-radius: 10px;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.quickview-actions .btn-primary:hover {
    background: #1d4ed8;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
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

/* ─── Mobile Filter Toggle Button ─── */
.btn-mobile-filter-toggle {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 38px;
    padding: 0 14px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 9px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
    flex-shrink: 0;
}
.btn-mobile-filter-toggle:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}
.btn-mobile-filter-toggle.has-active-filters {
    border-color: #3b82f6;
    background: #eff6ff;
    color: #1e40af;
}
.btn-filter-content {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.btn-filter-text {
    font-size: 0.82rem;
}
.mobile-filter-count-badge {
    background: #2563eb;
    color: #ffffff;
    font-size: 0.68rem;
    font-weight: 700;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.mobile-filter-chevron {
    color: #94a3b8;
    transition: transform 0.15s ease;
}

/* ─── Tablet (769px – 1024px) ─── */
@media(max-width: 1024px) and (min-width: 769px) {
    .products-catalogue-grid { grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .product-item-title { font-size: 0.9rem; }
    .shop-filter-bar { padding: 10px 12px; }
}

/* ─── Small Tablet (641px – 768px) ─── */
@media(max-width: 768px) and (min-width: 641px) {
    .products-catalogue-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .shop-search-main-row { min-width: 0; }
}

/* ─── Mobile general shop improvements ─── */
@media(max-width: 640px) {
    .shop-filter-bar {
        padding: 10px 12px;
        border-radius: 12px;
    }
    .shop-results-header {
        flex-direction: column;
        gap: 8px;
    }
    .results-header-right {
        align-self: flex-start;
    }
    .custom-sourcing-banner {
        padding: 20px 18px;
        border-radius: 14px;
    }
    .wholesale-supply-cta {
        padding: 20px 18px;
        border-radius: 14px;
    }
    .wholesale-cta-btns {
        width: 100%;
    }
    .btn-wholesale-access,
    .btn-wholesale-quote {
        flex: 1;
        justify-content: center;
    }
}

/* ─── Instant Customer Mode Switching & Page Loader Bar ─── */
.shop-tab-loader {
    position: absolute;
    top: -6px;
    left: 0;
    right: 0;
    height: 3px;
    background: rgba(226, 232, 240, 0.4);
    overflow: hidden;
    border-radius: 999px;
    z-index: 20;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.15s ease;
}
.shop-tab-loader.active {
    opacity: 1;
}
.tab-loader-bar {
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, #38bdf8 0%, #2563eb 50%, #f59e0b 100%);
    background-size: 200% 100%;
    transform-origin: left;
    animation: tabLoaderProgress 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

@keyframes tabLoaderProgress {
    0% { transform: scaleX(0); }
    50% { transform: scaleX(0.75); }
    100% { transform: scaleX(1); }
}

.products-catalogue-grid {
    transition: opacity 0.15s ease, transform 0.15s ease;
}
.products-catalogue-grid.mode-switching {
    opacity: 0.6;
    transform: translateY(2px);
}

.shop-container[data-customer-mode="retail"] .pricing-mode-wholesale,
.shop-container[data-customer-mode="retail"] .actions-mode-wholesale,
.shop-container[data-customer-mode="retail"] #businessPricingBanner {
    display: none !important;
}
.shop-container[data-customer-mode="retail"] .pricing-mode-retail {
    display: block !important;
}
.shop-container[data-customer-mode="retail"] .card-action-row.actions-mode-retail {
    display: flex !important;
}
.shop-container[data-customer-mode="retail"] .card-wholesale-inquiry-link.actions-mode-retail {
    display: block !important;
}

.shop-container[data-customer-mode="wholesale"] .pricing-mode-retail,
.shop-container[data-customer-mode="wholesale"] .actions-mode-retail {
    display: none !important;
}
.shop-container[data-customer-mode="wholesale"] .pricing-mode-wholesale {
    display: block !important;
}
.shop-container[data-customer-mode="wholesale"] .card-action-row.actions-mode-wholesale {
    display: flex !important;
}
.shop-container[data-customer-mode="wholesale"] #businessPricingBanner {
    display: flex !important;
}
</style>

<script>
// ─── Search & Dropdown Filters Engine ───
window.IS_APPROVED_WHOLESALE = {{ (auth()->check() && in_array($group, ['wholesale', 'trading'])) ? 'true' : 'false' }};
let searchDebounceTimer = null;

function applyShopFilters() {
    const form = document.getElementById('shopFilterForm');
    if (!form) return;

    const contentArea = document.getElementById('shopMainContent');
    const loader = document.getElementById('shopTabLoader');
    if (contentArea) {
        contentArea.classList.add('loading');
    }
    if (loader) {
        loader.classList.add('active');
    }

    const formData = new FormData(form);
    const params = new URLSearchParams();

    for (let [k, v] of formData.entries()) {
        if (v && v.trim() !== '') {
            params.append(k, v.trim());
        }
    }

    const url = form.getAttribute('action') + '?' + params.toString();

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(r => r.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.getElementById('shopMainContent');
        if (newContent && contentArea) {
            contentArea.innerHTML = newContent.innerHTML;
            contentArea.classList.remove('loading');
            
            // Maintain current customer mode on newly injected cards
            const currentMode = document.getElementById('hidden_customer_type')?.value || 'retail';
            const shopContainer = document.getElementById('shopMasterContainer');
            if (shopContainer) {
                shopContainer.setAttribute('data-customer-mode', currentMode);
            }

            // Re-bind currency price updates
            if (typeof updateAllDynamicPrices === 'function') {
                updateAllDynamicPrices();
            }
        } else {
            window.location.href = url;
        }

        if (loader) loader.classList.remove('active');

        // Update browser URL without reload
        window.history.replaceState({}, '', url);
    })
    .catch(() => {
        window.location.href = url;
    });
}

function setCustomerType(type) {
    const hidden = document.getElementById('hidden_customer_type');
    if (hidden) hidden.value = type;

    // 1. Instant Active Toggle Buttons
    const btns = document.querySelectorAll('.cust-type-btn');
    btns.forEach(b => {
        b.classList.toggle('active', b.getAttribute('data-type') === type);
    });

    // 2. Trigger Page Loader & Grid Transition Effect
    const loader = document.getElementById('shopTabLoader');
    const grid = document.querySelector('.products-catalogue-grid');
    if (loader) {
        loader.classList.remove('active');
        void loader.offsetWidth; // force reflow for re-animation
        loader.classList.add('active');
    }
    if (grid) {
        grid.classList.add('mode-switching');
    }

    // 3. Instant Master Container Customer Mode attribute update (CSS toggles all cards immediately)
    const shopContainer = document.getElementById('shopMasterContainer');
    if (shopContainer) {
        shopContainer.setAttribute('data-customer-mode', type);
    }

    // 4. Instant Caption Update
    const caption = document.getElementById('custModeCaption');
    if (caption) {
        if (type === 'retail') {
            caption.innerHTML = '<span class="mode-caption-tag">🛍️ Retail / Walk-in:</span> <span class="mode-caption-text">Public browsing · Retail pricing · No login required</span>';
        } else {
            caption.innerHTML = '<span class="mode-caption-tag">🏢 Wholesale / Business:</span> <span class="mode-caption-text">Business verification required · Wholesale pricing</span>';
        }
    }

    // 5. Instant Business Pricing Banner Display Toggle
    const banner = document.getElementById('businessPricingBanner');
    if (banner) {
        banner.style.display = (type === 'wholesale' && !window.IS_APPROVED_WHOLESALE) ? 'flex' : 'none';
    }

    // 6. Update URL parameter in browser without reload
    try {
        const url = new URL(window.location.href);
        url.searchParams.set('customer_type', type);
        window.history.replaceState({}, '', url.toString());
    } catch(e) {}

    // 7. Update dynamic currency prices for visible price elements
    if (typeof updateAllDynamicPrices === 'function') {
        updateAllDynamicPrices();
    }

    // 8. Smoothly complete transition animation
    setTimeout(() => {
        if (loader) loader.classList.remove('active');
        if (grid) grid.classList.remove('mode-switching');
    }, 200);
}

function clearSearchInput(e) {
    if (e) e.preventDefault();
    const input = document.getElementById('shopMainSearchInput');
    const clearBtn = document.getElementById('searchClearBtn');
    if (input) {
        input.value = '';
        if (clearBtn) clearBtn.style.display = 'none';
        applyShopFilters();
    }
}

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
            }, 350);
        });
    }

    // Close dropdown menus when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.searchable-dropdown')) {
            document.querySelectorAll('.searchable-dropdown-menu.active').forEach(m => {
                m.classList.remove('active');
            });
        }
    });
});

function toggleSearchableDropdown(name) {
    const menu = document.getElementById('menu-' + name);
    const wasActive = menu && menu.classList.contains('active');

    document.querySelectorAll('.searchable-dropdown-menu.active').forEach(m => {
        m.classList.remove('active');
    });

    if (menu && !wasActive) {
        menu.classList.add('active');
        const input = menu.querySelector('.dropdown-search-input');
        if (input) {
            input.value = '';
            input.focus();
            filterDropdownOptions(name, '');
        }
    }
}

function filterDropdownOptions(name, query) {
    const list = document.getElementById('list-' + name);
    if (!list) return;
    const items = list.querySelectorAll('.dropdown-option-item');
    const noRes = list.querySelector('.dropdown-no-results');
    let visibleCount = 0;
    const q = query.toLowerCase().trim();

    items.forEach(item => {
        const label = (item.getAttribute('data-label') || '').toLowerCase();
        if (!q || label.includes(q)) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    if (noRes) {
        noRes.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

function selectDropdownOption(name, value, label, icon) {
    const hidden = document.getElementById('hidden_' + name);
    if (hidden) hidden.value = value;

    const trigger = document.querySelector('#dropdown-' + name + ' .searchable-dropdown-trigger');
    const labelEl = document.getElementById('label-' + name);
    const iconEl = document.getElementById('icon-' + name);

    if (labelEl) labelEl.textContent = value ? label : (name === 'category' ? 'Parent Category' : (name === 'subcategory' ? 'Child Category' : (name === 'origin' ? 'Origin' : (name === 'brand' ? 'Brand' : (name === 'pack_size' ? 'Pack Size' : (name === 'availability' ? 'Availability' : label))))));
    if (iconEl) iconEl.textContent = icon;

    if (trigger) {
        trigger.classList.toggle('has-value', !!value);
    }

    const menu = document.getElementById('menu-' + name);
    if (menu) menu.classList.remove('active');

    // If changing parent category, reset subcategory
    if (name === 'category') {
        const subHidden = document.getElementById('hidden_subcategory');
        if (subHidden) subHidden.value = '';
        const subLabel = document.getElementById('label-subcategory');
        if (subLabel) subLabel.textContent = 'Child Category';
        const subTrigger = document.querySelector('#dropdown-subcategory .searchable-dropdown-trigger');
        if (subTrigger) subTrigger.classList.remove('has-value');
    }

    applyShopFilters();
}

function clearDropdownValue(name) {
    const hidden = document.getElementById('hidden_' + name);
    if (hidden) hidden.value = '';

    const trigger = document.querySelector('#dropdown-' + name + ' .searchable-dropdown-trigger');
    if (trigger) trigger.classList.remove('has-value');

    const labelEl = document.getElementById('label-' + name);
    const iconEl = document.getElementById('icon-' + name);

    if (labelEl) labelEl.textContent = name === 'category' ? 'Parent Category' : (name === 'subcategory' ? 'Child Category' : (name === 'origin' ? 'Origin' : (name === 'brand' ? 'Brand' : (name === 'pack_size' ? 'Pack Size' : 'Availability'))));
    if (iconEl) iconEl.textContent = name === 'category' ? '📁' : (name === 'subcategory' ? '📂' : (name === 'origin' ? '🌍' : (name === 'brand' ? '🏷️' : (name === 'pack_size' ? '⚖️' : '⚡'))));

    applyShopFilters();
}

function toggleMobileFilters() {
    const row = document.getElementById('shopFiltersRow');
    if (row) row.classList.toggle('show-mobile');
}

// ─── Quick View Modal Engine ───
document.addEventListener('click', function(e) {
    const qvBtn = e.target.closest('.js-quickview-btn');
    if (qvBtn) {
        e.preventDefault();
        e.stopPropagation();
        const data = {
            id: qvBtn.getAttribute('data-id') || '',
            name: qvBtn.getAttribute('data-name') || '',
            category: qvBtn.getAttribute('data-category') || 'Products',
            sku: qvBtn.getAttribute('data-sku') || 'N/A',
            origin: qvBtn.getAttribute('data-origin') || '',
            weight: qvBtn.getAttribute('data-weight') || '',
            unit: qvBtn.getAttribute('data-unit') || 'pack',
            storage_temp: qvBtn.getAttribute('data-storage') || 'Cold-Chain',
            availability: qvBtn.getAttribute('data-availability') || 'Available',
            is_rfq: qvBtn.getAttribute('data-is-rfq') === '1',
            customer_type: qvBtn.getAttribute('data-customer-type') || 'retail',
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
    const avEl = document.getElementById('qvAvail');
    if (avEl) avEl.textContent = product.availability || 'Available';

    const currentCurrency = (window.AppCurrency && window.AppCurrency.current) || 'MYR';
    let formattedPrice = product.price_formatted;
    let baseRmText = '';

    const isWholesaleUnlocked = (product.group === 'wholesale' || product.group === 'trading');
    const isWholesalePreview = (product.customer_type === 'wholesale' && !isWholesaleUnlocked);

    const qvWholesaleLocked = document.getElementById('qvWholesaleLockedBox');
    const qvPriceBox = document.getElementById('qvPriceBox');
    const qvPrefix = document.getElementById('qvPricePrefix');
    const cartForm = document.getElementById('qvCartForm');
    const rfqLink = document.getElementById('qvRfqLink');

    if (isWholesalePreview) {
        // Public user in wholesale mode: Lock pricing
        if (qvPriceBox) qvPriceBox.style.display = 'none';
        if (qvWholesaleLocked) qvWholesaleLocked.style.display = 'block';
        if (cartForm) cartForm.style.display = 'none';
        if (rfqLink) {
            rfqLink.href = product.rfq_url || product.url;
            rfqLink.style.display = 'inline-flex';
        }
    } else if (product.is_rfq || product.base_rm <= 0) {
        // Sensitive / RFQ only
        if (qvWholesaleLocked) qvWholesaleLocked.style.display = 'none';
        if (qvPriceBox) qvPriceBox.style.display = 'flex';
        if (qvPrefix) qvPrefix.style.display = 'none';
        const priceEl = document.getElementById('qvPrice');
        if (priceEl) priceEl.textContent = isWholesaleUnlocked ? 'Negotiated Pricing' : 'Price available upon request';
        const qvBaseRm = document.getElementById('qvBaseRm');
        if (qvBaseRm) qvBaseRm.style.display = 'none';
        if (cartForm) cartForm.style.display = 'none';
        if (rfqLink) {
            rfqLink.href = product.rfq_url || product.url;
            rfqLink.style.display = 'inline-flex';
        }
    } else {
        // Active pricing visible
        if (qvWholesaleLocked) qvWholesaleLocked.style.display = 'none';
        if (qvPriceBox) qvPriceBox.style.display = 'flex';
        if (qvPrefix) qvPrefix.style.display = product.group === 'retail' ? 'inline-block' : 'none';

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

        if (cartForm) cartForm.style.display = 'block';
        if (rfqLink) rfqLink.style.display = 'none';
    }

    const descEl = document.getElementById('qvDesc');
    if (descEl) descEl.textContent = product.short_desc || '';
    const prodIdEl = document.getElementById('qvProductId');
    if (prodIdEl) prodIdEl.value = product.id;
    const detailsLink = document.getElementById('qvDetailsLink');
    if (detailsLink) detailsLink.href = product.url;

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
window.setCustomerType = setCustomerType;
window.applyShopFilters = applyShopFilters;
window.toggleSearchableDropdown = toggleSearchableDropdown;
window.selectDropdownOption = selectDropdownOption;
window.clearDropdownValue = clearDropdownValue;
window.clearSearchInput = clearSearchInput;
window.toggleMobileFilters = toggleMobileFilters;
</script>
@endsection
