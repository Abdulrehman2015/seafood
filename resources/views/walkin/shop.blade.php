@extends('layouts.app')
@section('title', __t('walkin.page_title', 'Walk-in Express — In-Store Express Menu') . ' — ' . ($settings['store_name'] ?? 'MST Import & Export Sdn. Bhd.'))

@section('content')
<!-- Walk-in Ocean Hero Header -->
<div class="walkin-hero-section">
    <div class="hero-bg-pattern"></div>
    <div class="hero-bg-glow"></div>

    <div class="container" style="position:relative;z-index:2">
        <!-- Breadcrumb & Mode Tag -->
        <div class="walkin-top-meta">
            <div class="breadcrumb" style="margin:0">
                <a href="{{ route('home') }}" class="breadcrumb-home">🏠 @t('nav.home', 'Home')</a>
                <span class="breadcrumb-sep">›</span>
                <span class="breadcrumb-current">@t('walkin.title', 'Walk-in Express')</span>
            </div>
            
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                <span class="walkin-live-badge">
                    <span class="pulse-dot"></span>
                    @t('walkin.menu_subtitle', 'In-Store Express Menu')
                </span>
                <span class="walkin-public-price-badge">
                    <span>🏷️</span>
                    <span>@t('walkin.public_pricing', 'Public / Walk-in Pricing')</span>
                </span>
            </div>
        </div>

        <!-- Hero Header Content Grid -->
        <div class="walkin-header-grid">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;flex-wrap:wrap">
                    <span class="walkin-store-tag">
                        🏬 @t('walkin.store_location', 'MST Counter 2 · SILC Industrial Park, Iskandar Puteri')
                    </span>
                    <span class="walkin-tag-sub">⚡ @t('walkin.express_pickup_tag', 'Express Counter 2 Collection')</span>
                </div>
                <h1 class="walkin-hero-title">
                    @t('walkin.title', 'Walk-in Express')
                </h1>
                <p class="walkin-hero-subtitle">
                    @t('walkin.service_desc', 'Browse available products, select your items, complete payment on your phone, and collect your packed order at MST Counter 2.')
                </p>
            </div>

            <!-- Top Hero Cart Pill & Quick Checkout -->
            <div class="walkin-hero-actions">
                <a href="{{ route('cart.index') }}" class="walkin-hero-cart-pill" title="@t('walkin.cart_title', 'Walk-in Express Cart')">
                    <div class="cart-pill-icon">🛒</div>
                    <div class="cart-pill-text">
                        <span class="cart-pill-label">@t('walkin.cart_title', 'Walk-in Express Cart')</span>
                        <span class="cart-pill-value"><span id="walkinCartCount">0</span> @t('walkin.items', 'items')</span>
                    </div>
                </a>
                <a href="{{ route('walkin.checkout') }}" class="btn-walkin-hero-checkout">
                    <span>@t('walkin.pay_and_collect', 'Pay & Collect')</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </div>
        </div>

        <!-- 4-Step Interactive Process Flow -->
        <div class="walkin-stepper-wrap">
            <div class="walkin-stepper-title">
                <span>⚡ @t('walkin.how_it_works', 'How Walk-in Express Works')</span>
                <span class="stepper-current-pill">@t('walkin.step_indicator', 'Step 2: Select Items')</span>
            </div>
            <div class="walkin-stepper">
                <div class="step-item step-completed">
                    <div class="step-circle">
                        <span class="step-num-icon">✓</span>
                    </div>
                    <div class="step-info">
                        <span class="step-name">@t('walkin.step_1_name', 'Browse')</span>
                        <span class="step-sub">@t('walkin.step_1_desc', 'Explore menu')</span>
                    </div>
                </div>
                <div class="step-divider active"></div>

                <div class="step-item step-active">
                    <div class="step-circle">
                        <span class="step-num-icon">2</span>
                    </div>
                    <div class="step-info">
                        <span class="step-name">@t('walkin.step_2_name', 'Select')</span>
                        <span class="step-sub">@t('walkin.step_2_desc', 'Choose items')</span>
                    </div>
                </div>
                <div class="step-divider"></div>

                <div class="step-item">
                    <div class="step-circle">
                        <span class="step-num-icon">3</span>
                    </div>
                    <div class="step-info">
                        <span class="step-name">@t('walkin.step_3_name', 'Pay')</span>
                        <span class="step-sub">@t('walkin.step_3_desc', 'Online payment')</span>
                    </div>
                </div>
                <div class="step-divider"></div>

                <div class="step-item">
                    <div class="step-circle">
                        <span class="step-num-icon">4</span>
                    </div>
                    <div class="step-info">
                        <span class="step-name">@t('walkin.step_4_name', 'Collect')</span>
                        <span class="step-sub">@t('walkin.step_4_desc', 'Counter 2')</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Wholesale Separation Notice Banner -->
<div class="walkin-b2b-notice-bar">
    <div class="container">
        <div class="b2b-notice-content">
            <div class="b2b-notice-left">
                <span class="b2b-notice-icon">ℹ️</span>
                <span>@t('walkin.wholesale_note', 'Walk-in Express is intended for retail / individual purchases and Counter 2 collection.')</span>
            </div>
            <a href="{{ route('register') }}" class="b2b-notice-link">
                <span>@t('walkin.wholesale_link', 'Looking for wholesale or regular supply? Request a Business Account')</span>
                <span class="b2b-arrow">→</span>
            </a>
        </div>
    </div>
</div>

@php
    $activeCat = $categories->firstWhere('slug', request('category'));
    $activeCatName = $activeCat ? $activeCat->name : __t('shop.all_categories', 'All Categories');
    $allCatCount = $categories->sum('products_count') ?: $categories->sum(fn($c) => $c->products()->walkinAvailable()->count());
    $hasFilters = request('category') || request('search') || (request('sort') && request('sort') !== 'sort_order');

    $catIconMap = [
        'fish' => '🐟',
        'fish-fillet' => '🔪',
        'prawns-shrimps' => '🦐',
        'squid' => '🦑',
        'crab' => '🦀',
        'shellfish' => '🦪',
        'seafood-products' => '🍥',
        'other-frozen-seafood' => '🍱',
        'steamboat' => '🍲',
        'meat-beef' => '🥩',
        'meat-lamb' => '🍖',
        'meat-chicken' => '🍗',
        'meat-duck' => '🦆',
        'frozen-product-food' => '📦',
        'dimsum' => '🥟',
        'ready-to-eat' => '🥗',
        'snack-food' => '🍤',
        'dessert' => '🍡',
        'food-ingredients' => '🧂',
        'cuisine-ingredients' => '🥢',
        'frozen-foods' => '🧊',
        'specialty-other' => '⭐',
    ];
@endphp

<!-- Main Container -->
<div class="container" style="padding-top:var(--space-6);padding-bottom:120px">

    <!-- Mobile & Tablet Category Filter Button (Triggers Popup Modal) -->
    <div class="mobile-cat-trigger-bar">
        <button type="button" class="mobile-cat-select-btn" onclick="openCategoryModal()">
            <div class="mobile-cat-btn-left">
                <span class="cat-btn-icon">🏷️</span>
                <div class="cat-btn-text">
                    <span class="cat-btn-sub">@t('shop.category_filter', 'Category Filter')</span>
                    <span class="cat-btn-main">{{ $activeCatName }}</span>
                </div>
            </div>
            <div class="mobile-cat-btn-right">
                <span class="cat-count-pill">{{ request('category') ? ($activeCat ? ($activeCat->products_count ?? $activeCat->products()->walkinAvailable()->count()) : 0) : $allCatCount }} @t('shop.items_count', 'items')</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </button>
    </div>

    <!-- Mobile & Tablet Search and Sort Bar -->
    <div class="mobile-filter-bar">
        <form method="GET" action="{{ route('walkin.shop') }}" class="mobile-search-form">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            <span class="shop-search-icon">🔍</span>
            <input type="text" name="search" class="shop-search-input" placeholder="@t('walkin.search_placeholder_short', 'Search products...')" value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('walkin.shop', request()->except('search', 'page')) }}" class="shop-search-clear">✕</a>
            @endif
        </form>

        <div class="mobile-sort-wrapper">
            <select class="shop-sort-select mobile-sort-select" onchange="location.href=this.value" aria-label="Sort products">
                <option value="{{ route('walkin.shop', array_merge(request()->except('page'), ['sort' => 'sort_order'])) }}" {{ request('sort','sort_order')=='sort_order' ? 'selected' : '' }}>@t('shop.sort_featured_short', 'Featured')</option>
                <option value="{{ route('walkin.shop', array_merge(request()->except('page'), ['sort' => 'price_asc'])) }}" {{ request('sort')=='price_asc' ? 'selected' : '' }}>@t('shop.sort_price_low_short', 'Price: Low')</option>
                <option value="{{ route('walkin.shop', array_merge(request()->except('page'), ['sort' => 'price_desc'])) }}" {{ request('sort')=='price_desc' ? 'selected' : '' }}>@t('shop.sort_price_high_short', 'Price: High')</option>
                <option value="{{ route('walkin.shop', array_merge(request()->except('page'), ['sort' => 'name'])) }}" {{ request('sort')=='name' ? 'selected' : '' }}>@t('shop.sort_name_az', 'Name A–Z')</option>
            </select>
        </div>
    </div>

    <!-- Two-Column Layout (Desktop Sidebar + Main Products) -->
    <div class="walkin-layout">

        <!-- ─── Desktop Left Sidebar ───────────────────────────────────── -->
        <aside class="shop-sidebar">
            <div class="shop-sidebar-header">
                <div class="shop-sidebar-title">
                    <span>🏷️ @t('shop.categories', 'Categories')</span>
                    <span class="sidebar-cat-badge">{{ $categories->where('products_count', '>', 0)->count() }}</span>
                </div>
                @if($hasFilters)
                    <a href="{{ route('walkin.shop') }}" class="shop-sidebar-clear">@t('shop.clear_all', 'Clear all')</a>
                @endif
            </div>

            <form method="GET" action="{{ route('walkin.shop') }}" id="sidebarFilterForm">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <!-- Search Box Filter -->
                <div class="filter-section">
                    <div class="filter-title">@t('shop.search_products', 'Search Products')</div>
                    <div class="shop-search-box">
                        <span class="shop-search-icon">🔍</span>
                        <input type="text" name="search" class="shop-search-input" placeholder="@t('walkin.search_placeholder', 'Search products by name, category, brand or product code...')" value="{{ request('search') }}" autocomplete="off">
                        @if(request('search'))
                            <a href="{{ route('walkin.shop', request()->except('search', 'page')) }}" class="shop-search-clear" title="@t('shop.clear_all', 'Clear')">✕</a>
                        @endif
                    </div>
                </div>

                <!-- Category List Filter -->
                <div class="filter-section">
                    <div class="shop-cat-list">
                        <a href="{{ route('walkin.shop', request()->except('category', 'page')) }}" class="shop-cat-item {{ !request('category') ? 'active' : '' }}">
                            <span>🏷️ @t('shop.all_categories', 'All Categories')</span>
                            <span class="shop-cat-count">{{ $allCatCount }}</span>
                        </a>
                        @foreach($categories as $cat)
                            @php $count = $cat->products_count ?? $cat->products()->walkinAvailable()->count(); @endphp
                            @if($count > 0)
                                <a href="{{ route('walkin.shop', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" class="shop-cat-item {{ request('category') == $cat->slug ? 'active' : '' }}">
                                    <span>{{ $catIconMap[$cat->slug] ?? '📦' }} {{ $cat->name }}</span>
                                    <span class="shop-cat-count">{{ $count }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Sort Filter -->
                <div class="filter-section">
                    <div class="filter-title">@t('shop.sort_by', 'Sort By')</div>
                    <select name="sort" class="shop-sort-select" onchange="this.form.submit()">
                        <option value="sort_order" {{ request('sort','sort_order')=='sort_order' ? 'selected' : '' }}>@t('shop.sort_featured', 'Featured Catches')</option>
                        <option value="price_asc"  {{ request('sort')=='price_asc' ? 'selected' : '' }}>@t('shop.sort_price_low', 'Price: Low to High')</option>
                        <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected' : '' }}>@t('shop.sort_price_high', 'Price: High to Low')</option>
                        <option value="name"       {{ request('sort')=='name' ? 'selected' : '' }}>@t('shop.sort_name_az', 'Name A–Z')</option>
                    </select>
                </div>
            </form>

            <!-- Counter 2 Collection Info Card -->
            <div class="sidebar-instore-box">
                <div class="instore-box-title">🏬 @t('walkin.counter_title', 'Counter 2 Collection')</div>
                <p class="instore-box-desc">@t('walkin.counter_desc', 'Orders are prepared for Counter 2 collection after payment confirmation. Orders are packed appropriately for collection and transport.')</p>
                <div class="instore-box-note">📋 @t('walkin.counter_note', 'Please present your order reference / payment confirmation when collecting your order.')</div>
            </div>
        </aside>

        <!-- ─── Main Products Content Area ─────────────────────────────── -->
        <div class="walkin-main-content">
            <!-- Desktop Toolbar -->
            <div class="shop-toolbar">
                <div class="shop-toolbar-info">
                    <span><strong>{{ $products->total() }}</strong> @t('walkin.products_count_label', 'selected products available for Walk-in / Counter Collection')</span>
                    @if(request('search'))
                        <span class="text-muted">@t('shop.for_keyword', 'for') "<strong>{{ request('search') }}</strong>"</span>
                    @endif
                    @if(request('category'))
                        <span class="shop-active-cat-badge">{{ $activeCatName }}</span>
                    @endif
                </div>

                <div class="desktop-sort-actions">
                    <div style="display:flex;align-items:center;gap:8px">
                        <label for="topSortSelect" style="font-size:0.82rem;font-weight:600;color:var(--gray-600);white-space:nowrap">@t('shop.sort_label', 'Sort:')</label>
                        <select id="topSortSelect" class="shop-sort-select" style="padding:6px 28px 6px 10px;font-size:0.82rem;width:auto;min-width:140px" onchange="location.href=this.value">
                            <option value="{{ route('walkin.shop', array_merge(request()->except('page'), ['sort' => 'sort_order'])) }}" {{ request('sort','sort_order')=='sort_order' ? 'selected' : '' }}>@t('shop.sort_featured_short', 'Featured')</option>
                            <option value="{{ route('walkin.shop', array_merge(request()->except('page'), ['sort' => 'price_asc'])) }}" {{ request('sort')=='price_asc' ? 'selected' : '' }}>@t('shop.sort_price_low_short', 'Price: Low')</option>
                            <option value="{{ route('walkin.shop', array_merge(request()->except('page'), ['sort' => 'price_desc'])) }}" {{ request('sort')=='price_desc' ? 'selected' : '' }}>@t('shop.sort_price_high_short', 'Price: High')</option>
                            <option value="{{ route('walkin.shop', array_merge(request()->except('page'), ['sort' => 'name'])) }}" {{ request('sort')=='name' ? 'selected' : '' }}>@t('shop.sort_name_az', 'Name A–Z')</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Active Filters Chips -->
            @if(request('category') || request('search'))
            <div class="shop-active-filters">
                <span style="font-size:0.78rem;font-weight:700;color:var(--gray-600);margin-right:2px">@t('shop.filters_label', 'Filters:')</span>
                @if(request('category'))
                    <a href="{{ route('walkin.shop', request()->except('category', 'page')) }}" class="shop-active-chip">
                        <span>@t('shop.filter_category', 'Category:') <strong>{{ $activeCatName }}</strong></span>
                        <span class="chip-remove">✕</span>
                    </a>
                @endif
                @if(request('search'))
                    <a href="{{ route('walkin.shop', request()->except('search', 'page')) }}" class="shop-active-chip">
                        <span>@t('shop.filter_search', 'Search:') "<strong>{{ request('search') }}</strong>"</span>
                        <span class="chip-remove">✕</span>
                    </a>
                @endif
                <a href="{{ route('walkin.shop') }}" class="shop-clear-all-chip">@t('shop.reset_all', 'Reset all')</a>
            </div>
            @endif

            <!-- Products Grid -->
            @if($products->count())
                <div class="products-grid">
                    @foreach($products as $product)
                    <div class="product-card" id="product-card-{{ $product->id }}">
                        <div class="product-card-img">
                            <a href="{{ route('walkin.show', $product) }}" style="display:block;width:100%;height:100%">
                                @if($product->thumbnail)
                                    <img src="{{ cdn_storage($product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy">
                                @else
                                    <div class="product-img-placeholder">🐟</div>
                                @endif
                            </a>

                            <!-- Badges Container -->
                            @if($product->origin)
                                <div class="card-badges-top">
                                    <span class="product-badge badge-origin">🌍 {{ $product->origin }}</span>
                                </div>
                            @endif

                            @if($product->storage_temp)
                                @php
                                    $tempLower = strtolower($product->storage_temp);
                                    $isLive = str_contains($tempLower, 'live');
                                    $isChilled = str_contains($tempLower, 'chilled');
                                    $isIqf = str_contains($tempLower, 'iqf');
                                    $badgeIcon = $product->getStorageIcon();
                                    $badgeSuffix = ($isIqf) ? ' · IQF' : '';
                                @endphp
                                <span class="product-badge-temp">{{ $badgeIcon }} {{ $product->storage_temp }}{{ $badgeSuffix }}</span>
                            @endif
                        </div>

                        <div class="product-card-body">
                            <div class="product-card-top-meta">
                                <div class="product-category">{{ $product->category?->name ?? 'Seafood' }}</div>
                                @if($product->sku)
                                    <span class="product-sku">{{ $product->sku }}</span>
                                @endif
                            </div>

                            <h3 class="product-name">
                                <a href="{{ route('walkin.show', $product) }}" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            <div class="product-meta">
                                @if($product->weight)
                                    <span class="product-meta-item product-meta-weight">⚖️ {{ $product->weight }}</span>
                                @endif
                                @if($product->brand)
                                    <span class="product-meta-item product-meta-brand" title="{{ $product->brand }}">🏷️ {{ $product->brand }}</span>
                                @endif
                            </div>

                            @php
                                $price = $product->walkin_price ?? $product->retail_price ?? 0;
                            @endphp

                            <div class="product-price-row">
                                @if($price > 0)
                                    <div class="product-price">
                                        <div class="price-stack">
                                            <div class="price-main-line">
                                                <span class="price-amount price-val">RM {{ number_format($price, 2) }}</span>
                                                <span class="price-unit-sub">/ {{ $product->unit ?? 'pack' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge-walkin-pill">@t('walkin.walkin_rate_tag', 'Walk-in')</span>
                                @else
                                    <div class="product-price-quote-only">
                                        <span class="quote-only-text">@t('walkin.price_upon_request', 'Price Available Upon Request')</span>
                                    </div>
                                @endif
                            </div>

                            <div class="product-card-actions">
                                <a href="{{ route('walkin.show', $product) }}" class="btn-card-details" title="@t('walkin.view_details', 'View Details')">
                                    @t('walkin.info', 'Info')
                                </a>
                                @if($price > 0)
                                    <button type="button" class="btn-card-add-cart btn-add-ajax" 
                                            data-product-id="{{ $product->id }}" 
                                            aria-label="Add {{ $product->name }} to walk-in cart">
                                        <span>+ @t('walkin.add_to_cart', 'Add')</span>
                                    </button>
                                @else
                                    <a href="{{ route('quotations.create', ['product' => $product->id]) }}" class="btn-card-rfq" title="@t('walkin.request_quote', 'Request Quote')">
                                        <span>@t('walkin.request_quote', 'Request Quote')</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($products->hasPages())
                <div class="shop-pagination-wrap">
                    {{ $products->links('vendor.pagination.custom') }}
                </div>
                @endif

            @else
                <div class="walkin-empty-card">
                    <div style="font-size:3.2rem;margin-bottom:12px">🐟</div>
                    <h3 class="empty-title">@t('walkin.no_products_found', 'No Walk-in Products Found')</h3>
                    <p class="empty-desc">@t('walkin.empty_search_desc', 'We could not find any walk-in available items matching your current filters.')</p>
                    <a href="{{ route('walkin.shop') }}" class="btn btn-primary" style="padding:10px 22px;border-radius:10px;font-weight:700">
                        @t('walkin.view_all_products', 'View All Products')
                    </a>
                </div>
            @endif

            <!-- Custom Sourcing CTA Block at Bottom of Product Listing -->
            <div class="walkin-custom-sourcing-section">
                <div class="sourcing-content">
                    <div class="sourcing-icon-badge">📦</div>
                    <div class="sourcing-text">
                        <h2 class="sourcing-title">@t('walkin.custom_sourcing_title', "Can't Find What You Need?")</h2>
                        <p class="sourcing-desc">@t('walkin.custom_sourcing_desc', 'MST also provides customised sourcing for products, specifications and pack sizes not currently listed online.')</p>
                    </div>
                    <div class="sourcing-action">
                        <a href="{{ route('quotations.create') }}" class="btn-sourcing-cta">
                            <span>@t('walkin.request_custom_sourcing', 'Request Custom Sourcing')</span>
                            <span class="cta-arrow">→</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- ─── Category Selection Popup Modal (Mobile & Tablet) ────────────────── -->
<div class="cat-modal-backdrop" id="catModalBackdrop" onclick="handleCatModalBackdropClick(event)">
    <div class="cat-modal-dialog" id="catModalDialog" role="dialog" aria-modal="true" aria-labelledby="catModalTitle">
        <div class="cat-modal-handle"></div>
        <div class="cat-modal-header">
            <div class="cat-modal-title" id="catModalTitle">
                <span>🏷️ @t('shop.select_category', 'Select Category')</span>
            </div>
            <button type="button" class="cat-modal-close-btn" onclick="closeCategoryModal()" aria-label="Close modal">✕</button>
        </div>

        <div class="cat-modal-search">
            <input type="text" class="cat-modal-search-input" id="catModalSearchInput" placeholder="@t('shop.filter_categories_placeholder', 'Filter categories...')" oninput="filterCategoryModalList(this.value)" autocomplete="off">
        </div>

        <div class="cat-modal-body" id="catModalBody">
            <a href="{{ route('walkin.shop', request()->except('category', 'page')) }}" class="cat-modal-item {{ !request('category') ? 'active' : '' }}">
                <div class="cat-modal-item-left">
                    <span style="font-size:1.15rem">🏷️</span>
                    <span>@t('shop.all_categories', 'All Categories')</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px">
                    <span class="cat-modal-item-count">{{ $allCatCount }}</span>
                    @if(!request('category'))
                        <span class="cat-modal-check">✓</span>
                    @endif
                </div>
            </a>
            @foreach($categories as $cat)
                @php $count = $cat->products_count ?? $cat->products()->walkinAvailable()->count(); @endphp
                @if($count > 0)
                    <a href="{{ route('walkin.shop', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" class="cat-modal-item {{ request('category') == $cat->slug ? 'active' : '' }}" data-cat-name="{{ strtolower($cat->name) }}">
                        <div class="cat-modal-item-left">
                            <span style="font-size:1.15rem">{{ $catIconMap[$cat->slug] ?? '📦' }}</span>
                            <span>{{ $cat->name }}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px">
                            <span class="cat-modal-item-count">{{ $count }}</span>
                            @if(request('category') == $cat->slug)
                                <span class="cat-modal-check">✓</span>
                            @endif
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>

<!-- ─── Sticky Floating Bottom Checkout Dock ───────────────────────────── -->
<div id="stickyCheckoutBar" class="walkin-bottom-dock">
    <div class="container">
        <div class="walkin-bottom-dock-inner">
            <div class="dock-left">
                <div class="dock-cart-icon">🛒</div>
                <div class="dock-cart-info">
                    <div class="dock-cart-label">@t('walkin.cart_title', 'Walk-in Express Cart')</div>
                    <div class="dock-cart-numbers">
                        <span id="bottomBarCount" class="dock-count">0</span> @t('walkin.items', 'items')
                        <span class="dock-sep">·</span>
                        <span id="bottomBarTotal" class="dock-total">RM 0.00</span>
                    </div>
                </div>
            </div>
            
            <div class="dock-right">
                <a href="{{ route('cart.index') }}" class="btn-dock-cart" title="View Cart">
                    @t('walkin.view_cart', 'View Cart')
                </a>
                <a href="{{ route('walkin.checkout') }}" class="btn-dock-checkout">
                    <span>@t('walkin.pay_and_collect', 'Pay & Collect')</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
/* ─── Hero Section Styling ─── */
.walkin-hero-section {
    position: relative;
    background: linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);
    color: #ffffff;
    border-bottom: 1px solid #1e3a8a;
    padding-top: calc(78px + 28px);
    padding-bottom: var(--space-8);
    overflow: hidden;
}
.hero-bg-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.08;
    background-image: radial-gradient(#38bdf8 1px, transparent 1px);
    background-size: 24px 24px;
    pointer-events: none;
}
.hero-bg-glow {
    position: absolute;
    top: -40%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(56,189,248,0.18) 0%, rgba(30,58,138,0) 70%);
    border-radius: 50%;
    pointer-events: none;
    filter: blur(40px);
}

.walkin-top-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: var(--space-4);
}
.breadcrumb-home {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #bae6fd;
    text-decoration: none;
    font-size: 0.85rem;
}
.breadcrumb-sep {
    color: #60a5fa;
    margin: 0 4px;
}
.breadcrumb-current {
    font-weight: 600;
    color: #ffffff;
    font-size: 0.85rem;
}

.walkin-live-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(56, 189, 248, 0.15);
    border: 1px solid rgba(56, 189, 248, 0.4);
    color: #7dd3fc;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    backdrop-filter: blur(8px);
}
.walkin-public-price-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(16, 185, 129, 0.18);
    border: 1px solid rgba(52, 211, 153, 0.4);
    color: #a7f3d0;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    backdrop-filter: blur(8px);
}
.pulse-dot {
    width: 8px;
    height: 8px;
    background-color: #38bdf8;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 rgba(56, 189, 248, 0.6);
    animation: pulseGlow 1.8s infinite;
}
@keyframes pulseGlow {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(56, 189, 248, 0.7); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(56, 189, 248, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(56, 189, 248, 0); }
}

.walkin-header-grid {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 24px;
    margin-bottom: 24px;
}
.walkin-store-tag {
    background: rgba(30, 58, 138, 0.6);
    border: 1px solid rgba(56, 189, 248, 0.35);
    padding: 4px 12px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #e0f2fe;
}
.walkin-tag-sub {
    color: #7dd3fc;
    font-size: 0.8rem;
    font-weight: 600;
}
.walkin-hero-title {
    font-family: var(--font-heading);
    font-size: clamp(1.6rem, 3.2vw, 2.3rem);
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.02em;
    margin: 4px 0 8px;
    line-height: 1.2;
}
.walkin-hero-subtitle {
    color: #bae6fd;
    font-size: 0.92rem;
    max-width: 640px;
    line-height: 1.5;
    margin: 0;
}

/* Hero Cart & Checkout Pill */
.walkin-hero-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.walkin-hero-cart-pill {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 8px 16px;
    border-radius: 12px;
    color: #ffffff;
    text-decoration: none;
    backdrop-filter: blur(10px);
    transition: all 0.2s ease;
}
.walkin-hero-cart-pill:hover {
    background: rgba(255, 255, 255, 0.16);
    border-color: rgba(56, 189, 248, 0.5);
    transform: translateY(-2px);
}
.cart-pill-icon { font-size: 1.35rem; }
.cart-pill-text { display: flex; flex-direction: column; }
.cart-pill-label { font-size: 0.7rem; color: #93c5fd; text-transform: uppercase; font-weight: 700; line-height: 1.1; }
.cart-pill-value { font-size: 0.95rem; font-weight: 800; color: #ffffff; }

.btn-walkin-hero-checkout {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #091a36;
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.92rem;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4);
    border: 1px solid #fde68a;
    transition: all 0.2s ease;
}
.btn-walkin-hero-checkout:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.55);
    color: #091a36;
}

/* ─── 4-Step Stepper ─── */
.walkin-stepper-wrap {
    background: rgba(6, 21, 43, 0.65);
    border: 1px solid rgba(56, 189, 248, 0.25);
    border-radius: 16px;
    padding: 14px 20px;
    backdrop-filter: blur(12px);
    margin-top: 12px;
}
.walkin-stepper-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.78rem;
    font-weight: 800;
    color: #7dd3fc;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
}
.stepper-current-pill {
    background: rgba(56, 189, 248, 0.18);
    color: #38bdf8;
    border: 1px solid rgba(56, 189, 248, 0.35);
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: none;
    letter-spacing: normal;
}
.walkin-stepper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    gap: 8px;
    width: 100%;
}
.step-item {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    opacity: 0.75;
    transition: all 0.2s ease;
    z-index: 2;
}
.step-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.82rem;
    font-weight: 800;
    background: rgba(255, 255, 255, 0.08);
    color: #93c5fd;
    border: 1.5px solid rgba(255, 255, 255, 0.2);
    flex-shrink: 0;
    transition: all 0.2s ease;
}
.step-num-icon {
    line-height: 1;
}
.step-info {
    display: flex;
    flex-direction: column;
}
.step-name {
    font-size: 0.85rem;
    color: #ffffff;
    font-weight: 700;
    line-height: 1.2;
}
.step-sub {
    font-size: 0.72rem;
    color: #94a3b8;
    line-height: 1.1;
    margin-top: 2px;
}

/* Step States */
.step-item.step-completed {
    opacity: 0.95;
}
.step-item.step-completed .step-circle {
    background: #059669;
    color: #ffffff;
    border-color: #34d399;
    box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);
}
.step-item.step-completed .step-name {
    color: #a7f3d0;
}

.step-item.step-active {
    opacity: 1;
    background: rgba(56, 189, 248, 0.12);
    padding: 6px 12px;
    border-radius: 12px;
    border: 1px solid rgba(56, 189, 248, 0.35);
}
.step-item.step-active .step-circle {
    background: #2563eb;
    color: #ffffff;
    border-color: #60a5fa;
    box-shadow: 0 0 12px rgba(56, 189, 248, 0.6);
}
.step-item.step-active .step-name {
    color: #38bdf8;
    font-weight: 800;
}
.step-item.step-active .step-sub {
    color: #e2e8f0;
}

.step-divider {
    flex: 1 1 0%;
    height: 2px;
    background: rgba(255, 255, 255, 0.15);
    min-width: 12px;
    border-radius: 2px;
}
.step-divider.active {
    background: linear-gradient(90deg, #059669 0%, #2563eb 100%);
}

/* ─── Wholesale Separation Notice Banner ─── */
.walkin-b2b-notice-bar {
    background: #eff6ff;
    border-bottom: 1px solid #bfdbfe;
    padding: 10px 0;
}
.b2b-notice-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 0.85rem;
}
.b2b-notice-left {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #1e3a8a;
    font-weight: 600;
}
.b2b-notice-icon { font-size: 1rem; }
.b2b-notice-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #2563eb;
    font-weight: 700;
    text-decoration: none;
    padding: 4px 10px;
    border-radius: 8px;
    background: #ffffff;
    border: 1px solid #93c5fd;
    transition: all 0.15s ease;
}
.b2b-notice-link:hover {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
}
.b2b-arrow { font-size: 0.95rem; }

/* ─── Layout & Sidebar Grid ─── */
.walkin-layout {
    display: grid;
    grid-template-columns: 270px 1fr;
    gap: 28px;
    align-items: start;
}

/* Desktop Sidebar */
.shop-sidebar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    position: sticky;
    top: 90px;
    max-height: calc(100vh - 110px);
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
}
.shop-sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 12px;
    margin-bottom: 16px;
    border-bottom: 1.5px solid #f1f5f9;
}
.shop-sidebar-title {
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
}
.sidebar-cat-badge {
    font-size: 0.75rem;
    font-weight: 700;
    color: #1e40af;
    background: #dbeafe;
    padding: 2px 8px;
    border-radius: 999px;
    margin-left: 6px;
}
.shop-sidebar-clear {
    font-size: 0.78rem;
    color: #ef4444;
    font-weight: 600;
    text-decoration: none;
}
.shop-sidebar-clear:hover { text-decoration: underline; }

.filter-section {
    margin-bottom: 18px;
}
.filter-title {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #64748b;
    margin-bottom: 8px;
}

.shop-search-box {
    position: relative;
    display: flex;
    align-items: center;
}
.shop-search-icon {
    position: absolute;
    left: 12px;
    font-size: 0.95rem;
    color: #64748b;
    pointer-events: none;
}
.shop-search-input {
    width: 100%;
    height: 40px;
    padding: 0 34px 0 36px;
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    background: #f8fafc;
    font-size: 0.85rem;
    color: #0f172a;
    transition: all 0.2s ease;
}
.shop-search-input:focus {
    outline: none;
    border-color: #2563eb;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}
.shop-search-clear {
    position: absolute;
    right: 10px;
    color: #94a3b8;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.82rem;
}
.shop-search-clear:hover { color: #0f172a; }

.shop-cat-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.shop-cat-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 9px 12px;
    border-radius: 9px;
    color: #334155;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 500;
    transition: all 0.15s ease;
}
.shop-cat-item:hover {
    background: #eff6ff;
    color: #1d4ed8;
}
.shop-cat-item.active {
    background: #1e40af;
    color: #ffffff;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(30, 64, 175, 0.2);
}
.shop-cat-count {
    font-size: 0.75rem;
    padding: 2px 8px;
    border-radius: 999px;
    background: #f1f5f9;
    color: #64748b;
    font-weight: 700;
}
.shop-cat-item.active .shop-cat-count {
    background: rgba(255, 255, 255, 0.25);
    color: #ffffff;
}

.shop-sort-select {
    width: 100%;
    height: 40px;
    padding: 0 32px 0 12px;
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
    font-size: 0.85rem;
    font-weight: 600;
    color: #334155;
    appearance: none;
    cursor: pointer;
}
.shop-sort-select:focus {
    outline: none;
    border-color: #2563eb;
    background-color: #ffffff;
}

.sidebar-instore-box {
    margin-top: 14px;
    padding: 16px;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-radius: 14px;
    border: 1.5px solid #bfdbfe;
}
.instore-box-title {
    font-weight: 800;
    font-size: 0.9rem;
    color: #1e3a8a;
    margin-bottom: 6px;
}
.instore-box-desc {
    font-size: 0.8rem;
    color: #1e40af;
    line-height: 1.45;
    margin: 0 0 8px 0;
}
.instore-box-note {
    font-size: 0.74rem;
    color: #475569;
    line-height: 1.4;
    border-top: 1px dashed #93c5fd;
    padding-top: 6px;
}

/* ─── Mobile Category Button & Filter Bar ─── */
.mobile-cat-trigger-bar {
    display: none;
    margin-bottom: 12px;
}
.mobile-cat-select-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    cursor: pointer;
    text-align: left;
    transition: all 0.15s ease;
}
.mobile-cat-select-btn:active {
    background: #f8fafc;
    border-color: #2563eb;
    transform: scale(0.99);
}
.mobile-cat-btn-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.cat-btn-icon {
    font-size: 1.25rem;
}
.cat-btn-text {
    display: flex;
    flex-direction: column;
}
.cat-btn-sub {
    font-size: 0.7rem;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.04em;
    color: #64748b;
    line-height: 1.1;
}
.cat-btn-main {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0f172a;
}
.mobile-cat-btn-right {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
}
.cat-count-pill {
    font-size: 0.75rem;
    font-weight: 700;
    background: #eff6ff;
    color: #1d4ed8;
    padding: 3px 10px;
    border-radius: 999px;
    border: 1px solid #bfdbfe;
}

.mobile-filter-bar {
    display: none;
    gap: 8px;
    margin-bottom: 16px;
}
.mobile-search-form {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
}
.mobile-sort-wrapper {
    width: 140px;
    flex-shrink: 0;
}

/* ─── Main Shop Toolbar & Active Chips ─── */
.shop-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    background: #ffffff;
    padding: 12px 18px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    margin-bottom: 20px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
}
.shop-toolbar-info {
    font-size: 0.9rem;
    color: #475569;
}
.shop-active-cat-badge {
    background: #eff6ff;
    color: #1e40af;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    border: 1px solid #bfdbfe;
    margin-left: 6px;
}

.shop-active-filters {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 16px;
    margin-bottom: 18px;
}
.shop-active-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.8rem;
    text-decoration: none;
    font-weight: 600;
}
.shop-active-chip:hover { background: #dbeafe; }
.chip-remove { font-size: 0.75rem; font-weight: 800; }
.shop-clear-all-chip {
    margin-left: auto;
    font-size: 0.8rem;
    font-weight: 700;
    color: #ef4444;
    text-decoration: none;
}
.shop-clear-all-chip:hover { text-decoration: underline; }

/* ─── Product Card & Grid System ─── */
.walkin-layout .products-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 22px;
}

.product-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
}
.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.08), 0 4px 8px -2px rgba(15, 23, 42, 0.04);
    border-color: #bfdbfe;
}

.product-card-img {
    position: relative;
    aspect-ratio: 4 / 3;
    width: 100%;
    overflow: hidden;
    background: #f8fafc;
    flex-shrink: 0;
}
.product-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease;
}
.product-card:hover .product-card-img img {
    transform: scale(1.05);
}
.product-img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.8rem;
    background: #eff6ff;
}

/* Card Badges */
.card-badges-top {
    position: absolute;
    top: 8px;
    left: 8px;
    right: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    z-index: 3;
    pointer-events: none;
}
.card-badges-top .badge-origin {
    margin-left: auto;
    background: rgba(255, 255, 255, 0.95);
    color: #0f172a;
    border: 1px solid #e2e8f0;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 999px;
    white-space: nowrap;
    backdrop-filter: blur(4px);
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.product-badge-temp {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background: rgba(10, 25, 47, 0.88);
    backdrop-filter: blur(4px);
    color: #7dd3fc;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    border: 1px solid rgba(56, 189, 248, 0.35);
    z-index: 2;
    pointer-events: none;
    white-space: nowrap;
}

/* Card Body */
.product-card-body {
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
    background: #ffffff;
}
.product-card-top-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    margin-bottom: 3px;
}
.product-category {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #0284c7;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.product-sku {
    font-size: 0.68rem;
    color: #94a3b8;
    font-family: monospace;
    white-space: nowrap;
}

.product-name {
    margin: 2px 0 6px 0;
    font-size: 0.95rem;
    line-height: 1.35;
    font-weight: 700;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 2.7em;
}
.product-name a {
    color: #0f172a;
    text-decoration: none;
    transition: color 0.15s ease;
}
.product-card:hover .product-name a {
    color: #1d4ed8;
}

.product-meta {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
    margin-bottom: 8px;
    min-height: 22px;
}
.product-meta-item {
    font-size: 0.7rem;
    font-weight: 600;
    color: #475569;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    padding: 2px 6px;
    border-radius: 6px;
    white-space: nowrap;
}
.product-meta-brand {
    max-width: 130px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-price-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-top: auto !important;
    margin-bottom: 10px;
    padding-top: 8px;
    border-top: 1px solid #f1f5f9;
    gap: 8px;
}
.product-price {
    display: flex;
    flex-direction: column;
}
.price-stack {
    display: flex;
    flex-direction: column;
}
.price-main-line {
    display: flex;
    align-items: baseline;
    gap: 3px;
}
.product-price .price-amount,
.product-price .price-val {
    font-family: var(--font-heading);
    font-size: 1.25rem;
    font-weight: 800;
    color: #1e40af;
    line-height: 1.2;
}
.price-unit-sub {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 500;
}
.badge-walkin-pill {
    font-size: 0.7rem;
    font-weight: 700;
    color: #0369a1;
    background: #e0f2fe;
    padding: 2px 6px;
    border-radius: 4px;
    align-self: flex-start;
    margin-top: 2px;
    white-space: nowrap;
}
.product-price-quote-only {
    padding: 4px 0;
}
.quote-only-text {
    font-size: 0.82rem;
    font-weight: 700;
    color: #0284c7;
}

/* Card Actions */
.product-card-actions {
    display: flex;
    gap: 6px;
    align-items: stretch;
    width: 100%;
}
.btn-card-details {
    padding: 0 14px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.15s ease;
}
.btn-card-details:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.btn-card-add-cart {
    flex: 1;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #091a36;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.85rem;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(245, 158, 11, 0.3);
    transition: all 0.15s ease;
}
.btn-card-add-cart:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.45);
    color: #091a36;
}
.btn-card-rfq {
    flex: 1;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #eff6ff;
    color: #1e40af;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.82rem;
    text-decoration: none;
    transition: all 0.15s ease;
}
.btn-card-rfq:hover {
    background: #dbeafe;
    color: #1d4ed8;
}

/* ─── Empty Card ─── */
.walkin-empty-card {
    padding: 60px 20px;
    text-align: center;
    border-radius: 18px;
    border: 1px dashed #cbd5e1;
    background: #f8fafc;
}
.empty-title {
    font-family: var(--font-heading);
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 6px;
}
.empty-desc {
    color: #64748b;
    max-width: 440px;
    margin: 0 auto 20px;
    font-size: 0.92rem;
    line-height: 1.5;
}

/* ─── Custom Sourcing CTA Section ─── */
.walkin-custom-sourcing-section {
    margin-top: 40px;
    background: linear-gradient(135deg, #091a36 0%, #1e3a8a 100%);
    border: 1px solid rgba(56, 189, 248, 0.3);
    border-radius: 18px;
    padding: 24px 28px;
    color: #ffffff;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
}
.sourcing-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}
.sourcing-icon-badge {
    font-size: 2.2rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    width: 54px;
    height: 54px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.sourcing-text {
    flex: 1;
    min-width: 260px;
}
.sourcing-title {
    font-family: var(--font-heading);
    font-size: 1.2rem;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 4px 0;
}
.sourcing-desc {
    color: #bae6fd;
    font-size: 0.88rem;
    line-height: 1.45;
    margin: 0;
}
.btn-sourcing-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #091a36;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.88rem;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
    white-space: nowrap;
    transition: all 0.15s ease;
}
.btn-sourcing-cta:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    transform: translateY(-2px);
    color: #091a36;
}

/* ─── Category Selection Modal (Bottom Sheet / Dialog) ─── */
.cat-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(10, 25, 47, 0.7);
    backdrop-filter: blur(6px);
    z-index: 1000;
    display: none;
    align-items: flex-end;
    justify-content: center;
}
.cat-modal-backdrop.show {
    display: flex;
}
.cat-modal-dialog {
    background: #ffffff;
    border-radius: 24px 24px 0 0;
    width: 100%;
    max-width: 560px;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.25);
    animation: modalSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes modalSlideUp {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}
.cat-modal-handle {
    width: 40px;
    height: 4px;
    background: #cbd5e1;
    border-radius: 999px;
    margin: 10px auto 4px;
}
.cat-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px 10px;
    border-bottom: 1px solid #f1f5f9;
}
.cat-modal-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #0f172a;
    font-family: var(--font-heading);
}
.cat-modal-close-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f1f5f9;
    border: none;
    font-size: 0.95rem;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cat-modal-close-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.cat-modal-search {
    padding: 10px 20px;
    border-bottom: 1px solid #f1f5f9;
}
.cat-modal-search-input {
    width: 100%;
    height: 40px;
    padding: 0 14px;
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    background: #f8fafc;
    font-size: 0.88rem;
    color: #0f172a;
}
.cat-modal-search-input:focus {
    outline: none;
    border-color: #2563eb;
    background: #ffffff;
}
.cat-modal-body {
    padding: 10px 16px 30px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 4px;
    max-height: calc(85vh - 150px);
}
.cat-modal-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    border-radius: 12px;
    color: #334155;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.92rem;
    transition: all 0.15s ease;
}
.cat-modal-item:hover {
    background: #eff6ff;
    color: #1d4ed8;
}
.cat-modal-item.active {
    background: #eff6ff;
    color: #1d4ed8;
    font-weight: 800;
    border: 1.5px solid #bfdbfe;
}
.cat-modal-item-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.cat-modal-item-count {
    font-size: 0.78rem;
    font-weight: 700;
    color: #64748b;
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 999px;
}
.cat-modal-item.active .cat-modal-item-count {
    background: #dbeafe;
    color: #1e40af;
}
.cat-modal-check {
    font-size: 1rem;
    font-weight: 800;
    color: #2563eb;
}

/* ─── Floating Bottom Checkout Dock ─── */
.walkin-bottom-dock {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(6, 21, 43, 0.95);
    backdrop-filter: blur(14px);
    border-top: 2px solid #2563eb;
    padding: 12px 0;
    box-shadow: 0 -8px 24px rgba(6, 21, 43, 0.35);
    z-index: 100;
    transform: translateY(120%);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.walkin-bottom-dock-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}
.dock-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.dock-cart-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(56, 189, 248, 0.18);
    border: 1px solid rgba(56, 189, 248, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}
.dock-cart-label {
    font-size: 0.72rem;
    color: #93c5fd;
    text-transform: uppercase;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 2px;
}
.dock-cart-numbers {
    font-size: 1.15rem;
    font-weight: 800;
    color: #ffffff;
}
.dock-count { color: #7dd3fc; }
.dock-sep { margin: 0 4px; color: #64748b; }
.dock-total { color: #38bdf8; font-family: var(--font-heading); }

.dock-right {
    display: flex;
    align-items: center;
    gap: 10px;
}
.btn-dock-cart {
    padding: 10px 16px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.15s ease;
}
.btn-dock-cart:hover { background: rgba(255, 255, 255, 0.2); }
.btn-dock-checkout {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #091a36;
    padding: 10px 22px;
    border-radius: 10px;
    font-weight: 800;
    font-size: 0.92rem;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4);
    border: 1px solid #fde68a;
    transition: all 0.2s ease;
}
.btn-dock-checkout:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.55);
    color: #091a36;
}

/* ─── Responsive Breakpoints ─── */
@media (max-width: 1180px) {
    .walkin-layout {
        grid-template-columns: 240px 1fr;
        gap: 20px;
    }
    .walkin-layout .products-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }
}

@media (max-width: 860px) {
    .walkin-hero-section {
        padding-top: calc(78px + 24px);
        padding-bottom: var(--space-5);
    }
    .walkin-header-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    .walkin-hero-actions {
        width: 100%;
        justify-content: flex-start;
    }
    .walkin-layout {
        display: block;
    }
    .shop-sidebar {
        display: none !important;
    }
    .mobile-cat-trigger-bar {
        display: block !important;
    }
    .mobile-filter-bar {
        display: flex !important;
    }
    .shop-toolbar {
        display: none !important;
    }
    
    /* ─── Responsive Stepper for <= 860px ─── */
    .walkin-stepper-wrap {
        padding: 12px 14px !important;
        margin-top: 10px !important;
        border-radius: 14px !important;
    }
    .walkin-stepper-title {
        font-size: 0.72rem !important;
        margin-bottom: 10px !important;
    }
    .stepper-current-pill {
        font-size: 0.65rem !important;
        padding: 2px 7px !important;
    }
    .walkin-stepper {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 4px !important;
        position: relative !important;
        align-items: start !important;
    }
    .step-divider {
        display: none !important;
    }
    .step-item {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        gap: 4px !important;
        padding: 4px 2px !important;
        border-radius: 8px !important;
        background: transparent !important;
        border: none !important;
        width: 100% !important;
        opacity: 0.7 !important;
        box-sizing: border-box !important;
    }
    .step-circle {
        width: 26px !important;
        height: 26px !important;
        font-size: 0.72rem !important;
    }
    .step-info {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        width: 100% !important;
    }
    .step-name {
        font-size: 0.72rem !important;
        font-weight: 700 !important;
        white-space: nowrap !important;
        line-height: 1.15 !important;
        color: #e2e8f0 !important;
    }
    .step-sub {
        display: none !important;
    }
    .step-item.step-active {
        opacity: 1 !important;
        background: rgba(56, 189, 248, 0.15) !important;
        border: 1px solid rgba(56, 189, 248, 0.35) !important;
        border-radius: 8px !important;
        padding: 4px 2px !important;
    }
    .step-item.step-active .step-circle {
        width: 26px !important;
        height: 26px !important;
        box-shadow: 0 0 8px rgba(56, 189, 248, 0.5) !important;
    }
    .step-item.step-active .step-name {
        color: #38bdf8 !important;
        font-weight: 800 !important;
    }
    .step-item.step-completed {
        opacity: 0.9 !important;
    }
    .step-item.step-completed .step-name {
        color: #a7f3d0 !important;
    }

    /* ─── Responsive Products Grid for <= 860px ─── */
    .walkin-layout .products-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 12px !important;
    }
    .product-card {
        border-radius: 14px !important;
        display: flex !important;
        flex-direction: column !important;
        height: 100% !important;
    }
    .product-card-body {
        padding: 10px 10px 12px !important;
        display: flex !important;
        flex-direction: column !important;
        flex: 1 !important;
    }
    .product-card-top-meta {
        margin-bottom: 2px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
    }
    .product-category {
        font-size: 0.65rem !important;
    }
    .product-sku {
        font-size: 0.62rem !important;
    }
    .product-name {
        font-size: 0.84rem !important;
        line-height: 1.3 !important;
        height: 2.6em !important;
        margin: 2px 0 4px !important;
        font-weight: 700 !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
    }
    .product-meta {
        display: flex !important;
        align-items: center !important;
        gap: 4px !important;
        margin-bottom: 6px !important;
        min-height: 20px !important;
        flex-wrap: nowrap !important;
        overflow: hidden !important;
    }
    .product-meta-item {
        font-size: 0.62rem !important;
        padding: 2px 5px !important;
        border-radius: 4px !important;
        white-space: nowrap !important;
    }
    .product-meta-brand {
        max-width: 85px !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }
    .product-price-row {
        display: flex !important;
        flex-direction: row !important;
        align-items: baseline !important;
        justify-content: space-between !important;
        margin-top: auto !important;
        margin-bottom: 8px !important;
        padding-top: 6px !important;
        border-top: 1px solid #f1f5f9 !important;
        gap: 4px !important;
    }
    .product-price {
        font-size: 1.08rem !important;
        display: flex !important;
        align-items: baseline !important;
        gap: 3px !important;
        white-space: nowrap !important;
    }
    .product-price .price-amount,
    .product-price .price-val {
        font-size: 1.08rem !important;
    }
    .price-unit-sub {
        font-size: 0.68rem !important;
        color: #64748b !important;
    }
    .badge-walkin-pill {
        display: none !important;
    }
    .product-card-actions {
        display: flex !important;
        flex-direction: row !important;
        gap: 5px !important;
        align-items: center !important;
        width: 100% !important;
        margin-top: auto !important;
    }
    .btn-card-details {
        flex: 0 0 auto !important;
        width: auto !important;
        min-width: 40px !important;
        padding: 0 8px !important;
        height: 34px !important;
        font-size: 0.78rem !important;
        border-radius: 8px !important;
        white-space: nowrap !important;
    }
    .btn-card-add-cart {
        flex: 1 1 auto !important;
        width: auto !important;
        height: 34px !important;
        font-size: 0.8rem !important;
        border-radius: 8px !important;
        white-space: nowrap !important;
    }
    .card-badges-top {
        position: absolute !important;
        top: 6px !important;
        left: 6px !important;
        right: 6px !important;
        display: flex !important;
        justify-content: flex-end !important;
        z-index: 3 !important;
    }
    .card-badges-top .badge-origin {
        font-size: 0.64rem !important;
        padding: 2px 6px !important;
        background: rgba(255, 255, 255, 0.95) !important;
        color: #0f172a !important;
        border-radius: 6px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
    }
    .product-badge-temp {
        font-size: 0.64rem !important;
        padding: 2px 6px !important;
    }
    .walkin-custom-sourcing-section {
        padding: 18px !important;
    }
    .sourcing-content {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 14px !important;
    }
    .sourcing-action {
        width: 100% !important;
    }
    .btn-sourcing-cta {
        width: 100% !important;
        justify-content: center !important;
    }
}

@media (max-width: 500px) {
    .walkin-hero-section {
        padding-top: calc(78px + 20px);
        padding-bottom: var(--space-4);
    }
    .walkin-hero-title {
        font-size: 1.35rem;
    }
    .walkin-hero-subtitle {
        font-size: 0.8rem;
    }
    .walkin-stepper-wrap {
        padding: 8px 10px !important;
    }
    .step-name {
        font-size: 0.68rem !important;
    }
    .step-circle {
        width: 24px !important;
        height: 24px !important;
        font-size: 0.68rem !important;
    }
    .walkin-layout .products-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 8px !important;
    }
    .product-card-body {
        padding: 8px 8px 10px !important;
    }
    .product-name {
        font-size: 0.78rem !important;
        line-height: 1.25 !important;
        height: 2.5em !important;
    }
    .product-meta-brand {
        max-width: 65px !important;
    }
    .product-price {
        font-size: 1rem !important;
    }
    .product-price .price-amount,
    .product-price .price-val {
        font-size: 1rem !important;
    }
    .btn-card-add-cart,
    .btn-card-details {
        height: 32px !important;
        font-size: 0.76rem !important;
        border-radius: 7px !important;
    }
    .walkin-bottom-dock {
        padding: 10px 0;
    }
    .dock-cart-icon {
        width: 38px;
        height: 38px;
        font-size: 1.1rem;
    }
    .dock-cart-numbers {
        font-size: 0.95rem;
    }
    .btn-dock-cart {
        display: none;
    }
    .btn-dock-checkout {
        padding: 8px 16px;
        font-size: 0.85rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

// Update UI with fresh cart data
function refreshCartDisplay(count, totalFormatted) {
    const elWalkinCount = document.getElementById('walkinCartCount');
    const elBottomCount = document.getElementById('bottomBarCount');
    const elBottomTotal = document.getElementById('bottomBarTotal');
    const stickyBar = document.getElementById('stickyCheckoutBar');

    if (elWalkinCount) elWalkinCount.textContent = count;
    if (elBottomCount) elBottomCount.textContent = count;
    if (elBottomTotal && totalFormatted !== undefined && totalFormatted !== null) {
        elBottomTotal.textContent = 'RM ' + totalFormatted;
    }

    if (stickyBar) {
        if (count > 0) {
            stickyBar.style.transform = 'translateY(0)';
            stickyBar.style.opacity = '1';
            stickyBar.style.visibility = 'visible';
        } else {
            stickyBar.style.transform = 'translateY(120%)';
            stickyBar.style.opacity = '0';
        }
    }
}

// Fetch initial count & total
async function initCart() {
    try {
        const res = await fetch('{{ route("cart.count") }}');
        if (res.ok) {
            const data = await res.json();
            refreshCartDisplay(data.count, data.total_formatted);
        }
    } catch(e) {}
}

// Master Walk-in Page Initializer (runs on page load & after dynamic language switch)
window.initWalkinPage = function() {
    initCart();

    // AJAX 1-Tap Add-to-Cart with Microfeedback
    document.querySelectorAll('.btn-add-ajax').forEach(button => {
        if (button.dataset.bound === 'true') return;
        button.dataset.bound = 'true';

        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            const origHtml = this.innerHTML;

            this.disabled = true;
            this.innerHTML = '<span>⏳ Adding...</span>';

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.content || csrfToken;
                const res = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        product_id: parseInt(productId),
                        quantity: 1
                    })
                });

                const data = await res.json();

                if (data.success) {
                    this.style.background = '#059669';
                    this.innerHTML = '<span>✓ Added!</span>';
                    refreshCartDisplay(data.count, data.total_formatted);

                    // Show floating dock with animation
                    const dock = document.getElementById('stickyCheckoutBar');
                    if (dock) {
                        dock.style.transform = 'translateY(0)';
                        dock.style.opacity = '1';
                        dock.style.visibility = 'visible';
                    }

                    setTimeout(() => {
                        this.disabled = false;
                        this.style.background = '';
                        this.innerHTML = origHtml;
                    }, 1200);
                } else {
                    alert(data.message || 'Could not add to cart');
                    this.disabled = false;
                    this.innerHTML = origHtml;
                }
            } catch(err) {
                alert('Error adding item to cart. Please try again.');
                this.disabled = false;
                this.innerHTML = origHtml;
            }
        });
    });
};

// Category Selection Modal Functions
function openCategoryModal() {
    const backdrop = document.getElementById('catModalBackdrop');
    const searchInput = document.getElementById('catModalSearchInput');
    if (backdrop) {
        backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
        if (searchInput) {
            searchInput.value = '';
            filterCategoryModalList('');
            setTimeout(() => searchInput.focus(), 300);
        }
    }
}

function closeCategoryModal() {
    const backdrop = document.getElementById('catModalBackdrop');
    if (backdrop) {
        backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }
}

function handleCatModalBackdropClick(event) {
    if (event.target === document.getElementById('catModalBackdrop')) {
        closeCategoryModal();
    }
}

function filterCategoryModalList(query) {
    const q = (query || '').toLowerCase().trim();
    const items = document.querySelectorAll('#catModalBody .cat-modal-item[data-cat-name]');
    items.forEach(item => {
        const name = item.getAttribute('data-cat-name') || '';
        if (!q || name.includes(q)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCategoryModal();
    }
});

// Run initialization
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', window.initWalkinPage);
} else {
    window.initWalkinPage();
}

// Re-initialize when language changes dynamically
window.addEventListener('app:locale-changed', function() {
    if (typeof window.initWalkinPage === 'function') {
        setTimeout(window.initWalkinPage, 50);
    }
});
</script>
@endpush

