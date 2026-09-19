@extends('layouts.app')
@section('title', 'Shop — MST Import and Export Sdn Bhd')

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 Home</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">Product Catalogue</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:3px 10px;border-radius:999px;font-size:0.72rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        ❄️ -18°C IQF Certified
                    </span>
                    <span style="color:#bae6fd;font-size:0.8rem">Direct Port Import</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.4rem);margin-bottom:6px;letter-spacing:-0.02em">
                    Seafood &amp; Frozen Food Catalogue
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:680px;line-height:1.5;margin:0">
                    @auth
                        Showing live <strong style="color:#ffffff;text-decoration:underline">{{ ucfirst($group) }} tier prices</strong> for your verified account.
                    @else
                        Explore our full range of ocean catches, Meltique beef, dim sum &amp; steamboat goods. <a href="{{ route('login') }}" style="color:#7dd3fc;font-weight:700;text-decoration:underline">Sign in</a> for wholesale carton &amp; trading pricing.
                    @endguest
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                @auth
                    <div class="group-badge group-{{ $group }}" style="font-size:0.85rem;padding:6px 14px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb">
                        ⭐ {{ ucfirst($group) }} Tier
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top:var(--space-6);padding-bottom:var(--space-16)">

    @php
        $activeCat = $categories->firstWhere('slug', request('category'));
        $activeCatName = $activeCat ? $activeCat->name : 'All Categories';
        $allCatCount = $categories->sum(fn($c) => $c->products()->active()->count());
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
        ];
    @endphp

    <!-- Mobile & Tablet Category Button (Opens Category Popup Modal) -->
    <div class="mobile-cat-trigger-bar">
        <button type="button" class="mobile-cat-select-btn" onclick="openCategoryModal()">
            <div class="mobile-cat-btn-left">
                <span class="cat-btn-icon">🏷️</span>
                <div class="cat-btn-text">
                    <span class="cat-btn-sub">Category Filter</span>
                    <span class="cat-btn-main">{{ $activeCatName }}</span>
                </div>
            </div>
            <div class="mobile-cat-btn-right">
                <span class="cat-count-pill">{{ request('category') ? ($activeCat ? $activeCat->products()->active()->count() : 0) : $allCatCount }} items</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </button>
    </div>

    <!-- Mobile & Tablet Search and Filter Bar -->
    <div class="mobile-filter-bar">
        <form method="GET" action="{{ route('shop.index') }}" class="mobile-search-form" style="flex:1;position:relative;display:flex;align-items:center">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            <span class="shop-search-icon">🔍</span>
            <input type="text" name="search" class="shop-search-input" placeholder="Search seafood..." value="{{ request('search') }}" style="width:100%">
            @if(request('search'))
                <a href="{{ route('shop.index', request()->except('search', 'page')) }}" class="shop-search-clear">✕</a>
            @endif
        </form>

        <div class="mobile-sort-wrapper">
            <select class="shop-sort-select mobile-sort-select" onchange="location.href=this.value" aria-label="Sort products">
                <option value="{{ route('shop.index', array_merge(request()->except('page'), ['sort' => 'sort_order'])) }}" {{ request('sort','sort_order')=='sort_order' ? 'selected' : '' }}>Featured</option>
                <option value="{{ route('shop.index', array_merge(request()->except('page'), ['sort' => 'price_asc'])) }}" {{ request('sort')=='price_asc' ? 'selected' : '' }}>Price: Low</option>
                <option value="{{ route('shop.index', array_merge(request()->except('page'), ['sort' => 'price_desc'])) }}" {{ request('sort')=='price_desc' ? 'selected' : '' }}>Price: High</option>
                <option value="{{ route('shop.index', array_merge(request()->except('page'), ['sort' => 'name'])) }}" {{ request('sort')=='name' ? 'selected' : '' }}>Name A–Z</option>
            </select>
        </div>
    </div>

    <div class="shop-layout">

        <!-- ─── Sidebar Filter (Desktop) ────────────────────────────────── -->
        <aside class="shop-sidebar">
            <div class="shop-sidebar-header">
                <div class="shop-sidebar-title">
                    <span>Categories</span>
                    <span style="font-size:0.75rem;font-weight:700;color:#1e40af;background:#dbeafe;padding:2px 8px;border-radius:999px;margin-left:6px">{{ $categories->count() }}</span>
                </div>
                @if($hasFilters)
                    <a href="{{ route('shop.index') }}" class="shop-sidebar-clear">Clear all</a>
                @endif
            </div>

            <form method="GET" action="{{ route('shop.index') }}" id="filterForm">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <!-- Search Filter -->
                <div class="filter-section">
                    <div class="filter-title">Search Products</div>
                    <div class="shop-search-box">
                        <span class="shop-search-icon">🔍</span>
                        <input type="text" name="search" class="shop-search-input" placeholder="Keyword, e.g. Salmon, Meltique..." value="{{ request('search') }}" autocomplete="off">
                        @if(request('search'))
                            <a href="{{ route('shop.index', request()->except('search', 'page')) }}" class="shop-search-clear" title="Clear search">✕</a>
                        @endif
                    </div>
                </div>

                <!-- Category Filter (Desktop Sidebar) -->
                <div class="filter-section">
                    <div class="shop-cat-list">
                        <a href="{{ route('shop.index', request()->except('category', 'page')) }}" class="shop-cat-item {{ !request('category') ? 'active' : '' }}">
                            <span>🌊 All Categories</span>
                            <span class="shop-cat-count">{{ $allCatCount }}</span>
                        </a>
                        @foreach($categories as $cat)
                            @php $count = $cat->products()->active()->count(); @endphp
                            @if($count > 0)
                                <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" class="shop-cat-item {{ request('category') == $cat->slug ? 'active' : '' }}">
                                    <span>{{ $catIconMap[$cat->slug] ?? '📦' }} {{ $cat->name }}</span>
                                    <span class="shop-cat-count">{{ $count }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Sort Filter -->
                <div class="filter-section">
                    <div class="filter-title">Sort By</div>
                    <select name="sort" class="shop-sort-select" onchange="this.form.submit()">
                        <option value="sort_order" {{ request('sort','sort_order')=='sort_order' ? 'selected' : '' }}>Featured Catches</option>
                        <option value="price_asc"  {{ request('sort')=='price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name"       {{ request('sort')=='name' ? 'selected' : '' }}>Name A–Z</option>
                    </select>
                </div>
            </form>

            @if(auth()->check() && auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
            <!-- Trader RFQ Desk -->
            <div style="margin-top:16px;padding:14px;background:linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%);border-radius:14px;border:1px solid #bfdbfe">
                <div style="font-weight:700;font-size:0.85rem;color:#1e3a8a;margin-bottom:4px">📋 Trading Partner Desk</div>
                <p style="font-size:0.78rem;color:#1d4ed8;line-height:1.4;margin:0 0 10px 0">Need container pricing or FCL bulk export quotation?</p>
                <a href="{{ route('quotations.create') }}" class="btn btn-sm" style="background:#1e40af;color:white;width:100%;text-align:center;font-weight:700;border-radius:8px;padding:7px 12px;font-size:0.8rem;display:block;text-decoration:none">
                    Submit Bulk RFQ
                </a>
            </div>
            @endif

            <!-- Cold Chain Trust -->
            <div style="margin-top:14px;padding:12px;background:#eff6ff;border-radius:12px;border:1px solid #bfdbfe;font-size:0.78rem;color:#1e40af;line-height:1.5">
                ❄️ <strong>Cold-Chain Assured:</strong> Continuous -18°C temperature logs from SILC Iskandar Puteri to your freezer.
            </div>
        </aside>

        <!-- ─── Main Content ─────────────────────────────────────────────── -->
        <div>
            <!-- Shop Toolbar -->
            <div class="shop-toolbar">
                <div class="shop-toolbar-info">
                    <span>Showing <strong>{{ $products->total() }}</strong> {{ Str::plural('product', $products->total()) }}</span>
                    @if(request('search'))
                        <span class="text-muted">for "<strong>{{ request('search') }}</strong>"</span>
                    @endif
                    @if(request('category'))
                        <span style="font-size:0.8rem;color:#1e40af;font-weight:600">in {{ $activeCatName }}</span>
                    @endif
                </div>

                <!-- Sort Select (Desktop) -->
                <div class="desktop-sort-actions">
                    <div style="display:flex;align-items:center;gap:8px">
                        <label for="topSortSelect" style="font-size:0.82rem;font-weight:600;color:var(--gray-600);white-space:nowrap">Sort:</label>
                        <select id="topSortSelect" class="shop-sort-select" style="padding:6px 28px 6px 10px;font-size:0.82rem;width:auto;min-width:140px" onchange="location.href=this.value">
                            <option value="{{ route('shop.index', array_merge(request()->except('page'), ['sort' => 'sort_order'])) }}" {{ request('sort','sort_order')=='sort_order' ? 'selected' : '' }}>Featured</option>
                            <option value="{{ route('shop.index', array_merge(request()->except('page'), ['sort' => 'price_asc'])) }}" {{ request('sort')=='price_asc' ? 'selected' : '' }}>Price: Low</option>
                            <option value="{{ route('shop.index', array_merge(request()->except('page'), ['sort' => 'price_desc'])) }}" {{ request('sort')=='price_desc' ? 'selected' : '' }}>Price: High</option>
                            <option value="{{ route('shop.index', array_merge(request()->except('page'), ['sort' => 'name'])) }}" {{ request('sort')=='name' ? 'selected' : '' }}>Name A–Z</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Active Filter Badges -->
            @if(request('category') || request('search'))
            <div class="shop-active-filters">
                <span style="font-size:0.78rem;font-weight:700;color:var(--gray-600);margin-right:2px">Filters:</span>
                @if(request('category'))
                    <a href="{{ route('shop.index', request()->except('category', 'page')) }}" class="shop-active-chip">
                        <span>Category: <strong>{{ $activeCatName }}</strong></span>
                        <span class="chip-remove">✕</span>
                    </a>
                @endif
                @if(request('search'))
                    <a href="{{ route('shop.index', request()->except('search', 'page')) }}" class="shop-active-chip">
                        <span>Search: "<strong>{{ request('search') }}</strong>"</span>
                        <span class="chip-remove">✕</span>
                    </a>
                @endif
                <a href="{{ route('shop.index') }}" class="shop-clear-all-chip">Reset all</a>
            </div>
            @endif

            <!-- Products Content Area -->
            @if($products->count())

                <!-- 1. Visual Card Grid View -->
                <div class="products-grid" id="catalogGridView">
                    @foreach($products as $product)
                        @php
                            $displayPrice = $product->getDisplayPrice($group);
                            $price = $displayPrice['amount'];
                            $priceFormatted = $displayPrice['formatted'];
                            $waMsg = 'Hello MST Import & Export, I would like to inquire about ' . $product->name . ' (SKU: ' . ($product->sku ?? 'N/A') . ') for wholesale supply.';
                        @endphp
                        <div class="product-card" data-product-id="{{ $product->id }}">
                            <div class="product-card-img">
                                <a href="{{ route('shop.show', $product) }}" style="display:block;width:100%;height:100%">
                                    @if($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy">
                                    @else
                                        <div class="product-img-placeholder">🐟</div>
                                    @endif
                                </a>

                                <!-- Top Badges Container: stacks vertically on mobile so badges never collide -->
                                <div class="card-badges-top">
                                    @if($product->is_featured)
                                        <span class="product-badge badge-featured">⭐ Featured</span>
                                    @endif
                                    @if($product->origin)
                                        <span class="product-badge badge-origin">🌍 {{ $product->origin }}</span>
                                    @endif
                                </div>

                                @if($product->storage_temp)
                                    <span class="product-badge-temp">❄️ {{ $product->storage_temp }} IQF</span>
                                @endif

                                <!-- Quick View Hover Overlay Button (Desktop Only) -->
                                <button type="button" class="btn-card-quickview" onclick="openQuickViewModal({{ json_encode([
                                    'id' => $product->id,
                                    'name' => $product->name,
                                    'category' => $product->category?->name ?? 'Seafood',
                                    'sku' => $product->sku,
                                    'origin' => $product->origin,
                                    'weight' => $product->weight,
                                    'unit' => $product->unit,
                                    'storage_temp' => $product->storage_temp,
                                    'price_formatted' => $priceFormatted,
                                    'moq' => $product->getMoqForGroup($group) > 1 ? ($product->getMoqForGroup($group) . ' ' . $product->unit) : null,
                                    'short_desc' => $product->short_description ?? $product->description,
                                    'image' => $product->thumbnail ? asset('storage/' . $product->thumbnail) : null,
                                    'url' => route('shop.show', $product),
                                    'rfq_url' => route('quotations.create', ['product' => $product->id]),
                                ]) }})">
                                    👁️ Quick View
                                </button>
                            </div>
                            <div class="product-card-body">
                                <div class="product-card-top-meta">
                                    <div class="product-category">{{ $product->category?->name ?? 'Seafood' }}</div>
                                    @if($product->sku)
                                        <span class="product-sku">{{ $product->sku }}</span>
                                    @endif
                                </div>
                                <h3 class="product-name">
                                    <a href="{{ route('shop.show', $product) }}" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <div class="product-meta">
                                    @if($product->weight)<span class="product-meta-item">⚖️ {{ $product->weight }}</span>@endif
                                    @if($product->storage_temp)<span class="product-meta-item">❄️ {{ $product->storage_temp }}</span>@endif
                                </div>
                                <div class="product-price-row">
                                    @if($price !== null)
                                        <div class="product-price js-currency-price"
                                             data-base-rm="{{ $product->getPriceForGroup($group) ?? 0 }}"
                                             data-manual-sgd="{{ $product->price_sgd ?? '' }}"
                                             data-manual-usd="{{ $product->price_usd ?? '' }}"
                                             @if(in_array($group, ['wholesale','trading']))
                                             data-manual-wholesale-sgd="{{ $product->wholesale_price_sgd ?? '' }}"
                                             data-manual-wholesale-usd="{{ $product->wholesale_price_usd ?? '' }}"
                                             data-group="{{ $group }}"
                                             @endif
                                        >
                                            <span class="price-amount">{{ $priceFormatted }}</span>
                                            <span class="price-base-rm" style="{{ $currentCurrency !== 'MYR' && !empty($displayPrice['base_rm']) ? '' : 'display:none' }};font-size:0.75rem;font-weight:500;color:#64748b;display:block">
                                                RM {{ number_format($product->getPriceForGroup($group), 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="product-price rfq">Price on Request</div>
                                    @endif
                                    @if(in_array($group, ['wholesale','trading']) && $product->getMoqForGroup($group) > 1)
                                        <div class="product-moq">MOQ: {{ $product->getMoqForGroup($group) }}</div>
                                    @endif
                                </div>
                                <div class="product-card-actions">
                                    @if($price !== null)
                                        <form action="{{ route('cart.add') }}" method="POST" class="product-cart-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="{{ $product->getMoqForGroup($group) }}">
                                            <button type="submit" class="btn-card-add-cart">
                                                Add to Cart
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('shop.show', $product) }}" class="btn-card-details">
                                            Details
                                        </a>
                                    @endif
                                    @if(auth()->check() && auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                                        <a href="{{ route('quotations.create', ['product' => $product->id]) }}" class="btn-card-rfq" title="Request For Quotation (RFQ)">
                                            📋 RFQ
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="shop-pagination-wrap">
                    {{ $products->links('vendor.pagination.custom') }}
                </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="card" style="padding:var(--space-12) var(--space-6);text-align:center;border-radius:18px;border:1px dashed #cbd5e1;background:#f8fafc">
                    <div style="font-size:3rem;margin-bottom:12px">🔍</div>
                    <h2 style="font-family:var(--font-heading);font-size:1.4rem;color:var(--gray-900);margin-bottom:8px">
                        No Products Found
                    </h2>
                    <p style="color:var(--gray-600);max-width:440px;margin:0 auto 20px;font-size:0.92rem;line-height:1.5">
                        We couldn't find any products matching your current filters. Try changing your search keywords or browsing our departments.
                    </p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary" style="padding:10px 22px;border-radius:10px;font-weight:700">
                        View All Products
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>

<!-- ─── Quick View Modal ──────────────────────────────────────────────────── -->
<div class="quickview-modal-backdrop" id="quickViewBackdrop" onclick="handleQuickViewBackdropClick(event)">
    <div class="quickview-modal-card" id="quickViewCard" role="dialog" aria-modal="true">
        <button type="button" class="quickview-close-btn" onclick="closeQuickViewModal()" aria-label="Close Quick View">✕</button>
        <div class="quickview-grid">
            <div class="quickview-media">
                <img src="" id="qvImg" alt="Product Image">
                <span class="quickview-origin-tag" id="qvOriginTag"></span>
            </div>
            <div class="quickview-details">
                <div class="quickview-category" id="qvCategory"></div>
                <h2 class="quickview-title" id="qvTitle"></h2>
                <div class="quickview-sku-bar">
                    <span>SKU: <strong id="qvSku"></strong></span>
                    <span>Storage: <strong id="qvStorage"></strong></span>
                </div>
                <div class="quickview-price-box">
                    <div class="qv-current-price" id="qvPrice"></div>
                    <div class="qv-weight" id="qvWeight"></div>
                </div>

                <p class="quickview-desc" id="qvDesc"></p>

                <!-- Approved Customer Group Unit Pricing (Strict Tier Privacy) -->
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px 16px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between">
                    <div>
                        <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#1e40af">Approved Customer Tier</span>
                        <div style="font-weight:800;color:#0f172a;font-size:0.95rem">{{ ucfirst($group) }} Rate</div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:0.75rem;color:#64748b" id="qvMoqNotice"></div>
                        <div style="font-weight:800;color:#1e40af;font-size:0.85rem">Authorized Price Only</div>
                    </div>
                </div>

                <div class="quickview-actions">
                    <a href="#" id="qvViewLink" class="btn btn-primary" style="flex:1;text-align:center;padding:12px;font-weight:700">
                        View Full Specs &amp; Order
                    </a>
                    @if(auth()->check() && auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                    <a href="#" id="qvRfqLink" class="btn" style="background:#1e40af;color:white;font-weight:700;padding:12px 18px;border-radius:10px;display:inline-flex;align-items:center;gap:6px;text-decoration:none">
                        📋 Request RFQ
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ─── Category Selection Popup Modal (Mobile) ───────────────────────────── -->
<div class="cat-modal-backdrop" id="catModalBackdrop" onclick="handleCatModalBackdropClick(event)">
    <div class="cat-modal-dialog" id="catModalDialog" role="dialog" aria-modal="true" aria-labelledby="catModalTitle">
        <div class="cat-modal-handle"></div>
        <div class="cat-modal-header">
            <div class="cat-modal-title" id="catModalTitle">
                <span>🏷️ Select Category</span>
            </div>
            <button type="button" class="cat-modal-close-btn" onclick="closeCategoryModal()" aria-label="Close modal">✕</button>
        </div>

        <div class="cat-modal-search">
            <input type="text" class="cat-modal-search-input" id="catModalSearchInput" placeholder="Filter categories..." oninput="filterCategoryModalList(this.value)" autocomplete="off">
        </div>

        <div class="cat-modal-body" id="catModalBody">
            <a href="{{ route('shop.index', request()->except('category', 'page')) }}" class="cat-modal-item {{ !request('category') ? 'active' : '' }}">
                <div class="cat-modal-item-left">
                    <span style="font-size:1.15rem">🌊</span>
                    <span>All Categories</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px">
                    <span class="cat-modal-item-count">{{ $allCatCount }}</span>
                    @if(!request('category'))
                        <span class="cat-modal-check">✓</span>
                    @endif
                </div>
            </a>
            @foreach($categories as $cat)
                @php $count = $cat->products()->active()->count(); @endphp
                <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" class="cat-modal-item {{ request('category') == $cat->slug ? 'active' : '' }}" data-cat-name="{{ strtolower($cat->name) }}">
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
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ─── Shop Layout & Responsive Grid System ─── */
.shop-layout {
    display: grid;
    grid-template-columns: 270px 1fr;
    gap: 28px;
    align-items: start;
}

/* Desktop Sticky Sidebar */
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
.shop-sidebar::-webkit-scrollbar {
    width: 5px;
}
.shop-sidebar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 999px;
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

/* Sidebar Category List */
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

/* Shop Toolbar */
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
    margin-bottom: 22px;
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

/* Responsive Products Grid */
.shop-layout .products-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 22px;
}

/* Product Cards & Badges */
.product-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
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
    transition: transform 0.3s ease;
}
.product-card:hover .product-card-img img {
    transform: scale(1.05);
}

/* Card Badges: Flex group so badges never collide */
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
.card-badges-top .product-badge {
    position: static !important;
    top: auto !important;
    left: auto !important;
    right: auto !important;
    bottom: auto !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
}
.card-badges-top .badge-featured {
    background: rgba(254, 243, 199, 0.95);
    color: #92400e;
    border: 1px solid #fde68a;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 999px;
    white-space: nowrap;
    backdrop-filter: blur(4px);
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.card-badges-top .badge-origin {
    margin-left: auto;
    background: rgba(255, 255, 255, 0.94);
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
.btn-card-quickview {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.9);
    opacity: 0;
    pointer-events: none;
    background: rgba(10, 25, 47, 0.88);
    backdrop-filter: blur(6px);
    color: #ffffff;
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 999px;
    padding: 8px 16px;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    z-index: 4;
}
.product-card:hover .btn-card-quickview {
    opacity: 1;
    pointer-events: auto;
    transform: translate(-50%, -50%) scale(1);
}
.btn-card-quickview:hover {
    background: #1e40af;
    border-color: #3b82f6;
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

/* Title strictly clamped to 2 lines so cards stay 100% equal height */
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

/* Metadata Chips */
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

/* Price Row — ONLY element with margin-top: auto to guarantee bottom pinning */
.product-price-row {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    margin-top: auto !important;
    margin-bottom: 10px;
    padding-top: 8px;
    border-top: 1px solid #f1f5f9;
}
.product-price {
    font-family: var(--font-heading);
    font-size: 1.25rem;
    font-weight: 800;
    color: #1e40af;
}
.product-price.rfq {
    font-size: 0.9rem;
    color: #d97706;
}
.product-moq {
    font-size: 0.72rem;
    font-weight: 700;
    color: #475569;
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
}

/* Full Width Actions */
.product-card-actions {
    display: flex;
    gap: 6px;
    align-items: stretch;
    width: 100%;
    margin-top: 0 !important;
}
.product-cart-form {
    flex: 1;
    width: 100%;
    display: flex;
    margin: 0;
}
.btn-card-add-cart {
    width: 100%;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #1d4ed8;
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.85rem;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(29, 78, 216, 0.2);
    transition: all 0.15s ease;
}
.btn-card-add-cart:hover {
    background: #1e40af;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(29, 78, 216, 0.35);
}
.btn-card-details {
    flex: 1;
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
.btn-card-rfq {
    background: #1e40af;
    color: #ffffff;
    border-radius: 10px;
    height: 38px;
    padding: 0 12px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}

/* Quick View Modal Card */
.quickview-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(10, 25, 47, 0.75);
    backdrop-filter: blur(6px);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.quickview-modal-backdrop.show {
    display: flex;
}
.quickview-modal-card {
    background: #ffffff;
    border-radius: 20px;
    max-width: 820px;
    width: 100%;
    overflow: hidden;
    position: relative;
    box-shadow: 0 24px 48px rgba(10, 25, 47, 0.25);
    animation: qvPop 0.25s ease-out;
}
@keyframes qvPop {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.quickview-close-btn {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f1f5f9;
    border: none;
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
    background: #e2e8f0;
    color: #0f172a;
}
.quickview-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
}
@media(max-width:768px) {
    .quickview-grid { grid-template-columns: 1fr; }
    .quickview-media { height: 240px !important; }
}
.quickview-media {
    position: relative;
    background: #f8fafc;
    height: 100%;
    min-height: 360px;
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
    font-weight: 700;
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
    font-weight: 700;
    text-transform: uppercase;
    color: #1d4ed8;
    letter-spacing: 0.05em;
    margin-bottom: 4px;
}
.quickview-title {
    font-family: var(--font-heading);
    font-size: 1.45rem;
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
    gap: 10px;
    margin-bottom: 12px;
}
.qv-current-price {
    font-size: 1.7rem;
    font-weight: 800;
    color: #1e40af;
}
.qv-weight {
    font-size: 0.85rem;
    color: #64748b;
}
.quickview-desc {
    font-size: 0.85rem;
    color: #475569;
    line-height: 1.5;
    margin-bottom: 18px;
}
.quickview-actions {
    display: flex;
    gap: 10px;
    margin-top: auto;
}

/* ─── Modern Custom Pagination ─── */
.shop-pagination-wrap {
    margin-top: 36px;
    width: 100%;
}

.custom-pagination-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 20px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
}

.custom-pagination-info {
    font-size: 0.88rem;
    color: #64748b;
    font-weight: 500;
}

.custom-pagination-info .fw-bold {
    font-weight: 700;
    color: #0f172a;
}

.custom-pagination-pages {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.pagination-btn,
.pagination-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    padding: 0 14px;
    border-radius: 10px;
    font-size: 0.86rem;
    font-weight: 600;
    text-decoration: none;
    background: #ffffff;
    color: #334155;
    border: 1px solid #e2e8f0;
    transition: all 0.15s ease;
    user-select: none;
    box-sizing: border-box;
}

.pagination-number {
    padding: 0;
    width: 38px;
}

.pagination-btn:hover:not(.disabled),
.pagination-number:hover:not(.active) {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #bfdbfe;
    transform: translateY(-1px);
}

.pagination-number.active {
    background: #1e40af !important;
    color: #ffffff !important;
    border-color: #1e40af !important;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(30, 64, 175, 0.25);
}

.pagination-btn.disabled {
    background: #f8fafc;
    color: #94a3b8;
    border-color: #f1f5f9;
    cursor: not-allowed;
    opacity: 0.7;
    pointer-events: none;
}

.pagination-dots {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 38px;
    color: #94a3b8;
    font-weight: 700;
}

@media (max-width: 640px) {
    .custom-pagination-nav {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 14px;
        gap: 12px;
    }
    .custom-pagination-pages {
        justify-content: center;
        width: 100%;
    }
    .pagination-btn {
        padding: 0 10px;
        font-size: 0.82rem;
    }
    .pagination-number {
        width: 34px;
        height: 34px;
        font-size: 0.82rem;
    }
}

/* ─── Responsive Breakpoints ─── */
@media (max-width: 1180px) {
    .shop-layout {
        grid-template-columns: 240px 1fr;
        gap: 20px;
    }
    .shop-layout .products-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }
}

@media (max-width: 860px) {
    .shop-layout {
        display: block;
    }
    .shop-sidebar {
        display: none !important;
    }
    .mobile-cat-trigger-bar {
        display: block !important;
        margin-bottom: 12px;
    }
    .mobile-filter-bar {
        display: flex !important;
        gap: 8px;
        margin-bottom: 16px;
    }
    .shop-toolbar {
        display: none !important;
    }
    .shop-layout .products-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 12px !important;
    }
    .product-card-body {
        padding: 10px 12px !important;
    }
    .product-name {
        font-size: 0.88rem !important;
        height: 2.6em !important;
        margin-bottom: 4px !important;
    }
    .product-price {
        font-size: 1.12rem !important;
    }
    .btn-card-add-cart,
    .btn-card-details {
        height: 35px !important;
        font-size: 0.8rem !important;
        border-radius: 8px !important;
    }
    .card-badges-top {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 4px !important;
        right: auto !important;
    }
    .card-badges-top .badge-featured,
    .card-badges-top .badge-origin {
        font-size: 0.62rem !important;
        padding: 2px 6px !important;
        margin-left: 0 !important;
    }
    .product-badge-temp {
        font-size: 0.62rem !important;
        padding: 2px 6px !important;
        bottom: 6px !important;
        left: 6px !important;
    }
    .btn-card-quickview {
        display: none !important;
    }
}

@media (max-width: 480px) {
    .shop-layout .products-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
    }
    .product-card-body {
        padding: 8px 10px !important;
    }
    .product-name {
        font-size: 0.82rem !important;
    }
    .product-price {
        font-size: 1.05rem !important;
    }
    .btn-card-add-cart,
    .btn-card-details {
        height: 33px !important;
        font-size: 0.78rem !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Clear any obsolete table view mode preference
try {
    localStorage.removeItem('mika_catalog_view');
} catch(e) {}

// Quick View Modal
function openQuickViewModal(data) {
    const backdrop = document.getElementById('quickViewBackdrop');
    if (!backdrop) return;

    document.getElementById('qvTitle').textContent = data.name;
    document.getElementById('qvCategory').textContent = data.category;
    document.getElementById('qvSku').textContent = data.sku || 'N/A';
    document.getElementById('qvStorage').textContent = (data.storage_temp || '-18°C') + ' IQF';
    document.getElementById('qvPrice').textContent = data.price_formatted;
    document.getElementById('qvWeight').textContent = data.weight ? '(' + data.weight + ' / ' + data.unit + ')' : '';
    document.getElementById('qvDesc').textContent = data.short_desc || '';
    document.getElementById('qvOriginTag').textContent = '🌍 ' + (data.origin || 'Imported');

    const moqNotice = document.getElementById('qvMoqNotice');
    if (moqNotice) {
        moqNotice.textContent = data.moq ? 'MOQ: ' + data.moq : '';
    }

    const img = document.getElementById('qvImg');
    if (img && data.image) {
        img.src = data.image;
        img.alt = data.name;
    }

    const viewLink = document.getElementById('qvViewLink');
    if (viewLink) viewLink.href = data.url;

    const rfqLink = document.getElementById('qvRfqLink');
    if (rfqLink && data.rfq_url) {
        rfqLink.href = data.rfq_url;
    }

    backdrop.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeQuickViewModal() {
    const backdrop = document.getElementById('quickViewBackdrop');
    if (backdrop) {
        backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }
}

function handleQuickViewBackdropClick(e) {
    if (e.target === document.getElementById('quickViewBackdrop')) {
        closeQuickViewModal();
    }
}

// Category Modal for Mobile
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
        closeQuickViewModal();
        closeCategoryModal();
    }
});
</script>
@endpush
