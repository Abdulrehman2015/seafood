@extends('layouts.admin')
@section('title', 'Products')

@section('content')
<div class="products-page-container">
    <!-- Top Bar -->
    <div class="admin-topbar products-topbar">
        <div class="topbar-left">
            <div class="title-with-badge">
                <h1 class="admin-page-title">Products</h1>
                <span class="total-count-pill">{{ $stats['total'] }} total</span>
            </div>
            <p class="admin-page-subtitle">Manage multi-tier pricing, stock inventory, and multi-channel availability.</p>
        </div>
        <div class="topbar-right">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-add-product">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="product-stats-grid">
        <a href="{{ route('admin.products.index') }}" class="stat-card {{ !request('status') && !request('stock') ? 'active-filter' : '' }}">
            <div class="stat-icon-wrap icon-blue">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ $stats['total'] }}</span>
                <span class="stat-label">All Products</span>
            </div>
        </a>

        <a href="{{ route('admin.products.index', ['status' => 'active']) }}" class="stat-card {{ request('status') === 'active' ? 'active-filter' : '' }}">
            <div class="stat-icon-wrap icon-green">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ $stats['active'] }}</span>
                <span class="stat-label">Active / Live</span>
            </div>
        </a>

        <a href="{{ route('admin.products.index', ['stock' => 'low_stock']) }}" class="stat-card {{ request('stock') === 'low_stock' ? 'active-filter' : '' }}">
            <div class="stat-icon-wrap icon-amber">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ $stats['low_stock'] + $stats['out_of_stock'] }}</span>
                <span class="stat-label">Low / Out of Stock</span>
            </div>
        </a>

        <a href="{{ route('admin.products.index', ['status' => 'archived']) }}" class="stat-card {{ request('status') === 'archived' ? 'active-filter' : '' }}">
            <div class="stat-icon-wrap icon-slate">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="21 8 21 21 3 21 3 8"></polyline>
                    <rect x="1" y="3" width="22" height="5"></rect>
                    <line x1="10" y1="12" x2="14" y2="12"></line>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ $stats['archived'] }}</span>
                <span class="stat-label">Archived / Trashed</span>
            </div>
        </a>
    </div>

    <!-- Search & Filters Panel -->
    <div class="card filter-card mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="product-filters-form">
            <div class="filter-field filter-search">
                <label class="form-label">Search</label>
                <div class="search-input-wrap">
                    <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="search" class="form-control filter-search-input" placeholder="Search name, SKU, tags..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="filter-field filter-category">
                <label class="form-label">Category</label>
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-field filter-status">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="active"   {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived / Trashed</option>
                </select>
            </div>

            <div class="filter-field filter-stock">
                <label class="form-label">Stock Level</label>
                <select name="stock" class="form-control">
                    <option value="">All Stock Levels</option>
                    <option value="in_stock"     {{ request('stock') === 'in_stock' ? 'selected' : '' }}>In Stock (>5)</option>
                    <option value="low_stock"    {{ request('stock') === 'low_stock' ? 'selected' : '' }}>Low Stock (≤5)</option>
                    <option value="out_of_stock" {{ request('stock') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock (0)</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-secondary filter-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['search', 'category', 'status', 'stock']))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary clear-btn" title="Clear all filters">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        <span>Clear</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Active Filter Summary & Result Count -->
    <div class="results-summary-bar">
        <span class="results-text">Showing <strong>{{ $products->total() }}</strong> {{ Str::plural('product', $products->total()) }}</span>
        @if(request()->hasAny(['search', 'category', 'status', 'stock']))
            <span class="filtered-badge">Filtered</span>
        @endif
    </div>

    <!-- ─── Desktop Table View (Hidden on mobile ≤900px) ─────────────────────── -->
    <div class="card products-desktop-card">
        <div class="table-wrapper products-table-wrapper">
            <table class="table products-table">
                <thead>
                    <tr>
                        <th class="col-product">Product</th>
                        <th class="col-price">Retail</th>
                        <th class="col-price">Walk-in</th>
                        <th class="col-price">Wholesale</th>
                        <th class="col-price">Trading</th>
                        <th class="col-stock">Stock</th>
                        <th class="col-status">Status</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="{{ $product->trashed() ? 'row-trashed' : '' }}">
                        <!-- Product Info -->
                        <td class="col-product">
                            <div class="product-cell">
                                <div class="product-thumb-wrap">
                                    @if($product->thumbnail)
                                        <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="{{ $product->name }}" class="product-thumb">
                                    @else
                                        <div class="product-thumb-placeholder">🐟</div>
                                    @endif
                                </div>
                                <div class="product-meta">
                                    <div class="product-name-row">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="product-name-link" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </a>
                                        @if($product->trashed())
                                            <span class="badge badge-danger badge-sm">Archived</span>
                                        @endif
                                    </div>
                                    <div class="product-subtext">
                                        @if($product->category)
                                            <span class="cat-pill">{{ $product->category->name }}</span>
                                        @endif
                                        @if($product->sku)
                                            <span class="sku-pill">#{{ $product->sku }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Retail Price -->
                        <td class="col-price">
                            <div class="price-val">RM {{ number_format($product->retail_price, 2) }}</div>
                            <div class="price-unit">per {{ $product->unit ?? 'unit' }}</div>
                        </td>

                        <!-- Walk-in Price -->
                        <td class="col-price">
                            @if($product->is_walkin_available && $product->walkin_price > 0)
                                <div class="price-val text-teal">RM {{ number_format($product->walkin_price, 2) }}</div>
                                <div class="price-tag tag-walkin">🏪 Walk-in</div>
                            @else
                                <span class="text-muted text-xs">Online only</span>
                            @endif
                        </td>

                        <!-- Wholesale Price -->
                        <td class="col-price">
                            @if($product->wholesale_price > 0)
                                <div class="price-val">RM {{ number_format($product->wholesale_price, 2) }}</div>
                                <div class="price-unit">B2B Tier</div>
                            @else
                                <span class="text-muted text-xs">—</span>
                            @endif
                        </td>

                        <!-- Trading / RFQ Price -->
                        <td class="col-price">
                            @if($product->is_rfq_only)
                                <span class="badge badge-warning text-xs">RFQ Only</span>
                            @elseif($product->trading_price > 0)
                                <div class="price-val">RM {{ number_format($product->trading_price, 2) }}</div>
                                <div class="price-unit">Bulk Tier</div>
                            @else
                                <span class="text-muted text-xs">—</span>
                            @endif
                        </td>

                        <!-- Stock Level -->
                        <td class="col-stock">
                            @if(!$product->track_stock)
                                <span class="badge badge-success badge-subtle">● Unlimited</span>
                            @elseif($product->stock_quantity <= 0)
                                <span class="badge badge-danger badge-subtle">● Out of Stock</span>
                            @elseif($product->stock_quantity <= 5)
                                <span class="badge badge-warning badge-subtle">⚠️ Low: {{ $product->stock_quantity }}</span>
                            @else
                                <span class="badge badge-success badge-subtle">● {{ $product->stock_quantity }}</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="col-status">
                            @if($product->trashed())
                                <span class="badge badge-danger">Archived</span>
                            @elseif($product->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="col-actions">
                            <div class="actions-group">
                                <a href="{{ route('shop.show', $product->slug) }}" target="_blank" class="btn-action btn-action-view" title="View on storefront">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                </a>

                                @if(!$product->trashed())
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-action btn-action-edit" title="Edit product">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ addslashes($product->name) }}?');" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="Archive product">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn-action btn-action-restore" title="Restore product">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="1 4 1 10 7 10"></polyline>
                                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-table-cell">
                            <div class="empty-state">
                                <div class="empty-state-icon">🐟</div>
                                <h3 class="empty-state-title">No products found</h3>
                                <p class="empty-state-desc">Try modifying your search keywords or clearing applied filters.</p>
                                @if(request()->hasAny(['search', 'category', 'status', 'stock']))
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm" style="margin-top:10px">Clear Filters</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ─── Mobile Cards View (Displayed on mobile ≤900px) ───────────────────── -->
    <div class="products-mobile-list">
        @forelse($products as $product)
        <div class="product-card-mobile {{ $product->trashed() ? 'card-trashed' : '' }}">
            <!-- Header Row: Image, Name, Status -->
            <div class="mobile-card-header">
                <div class="mobile-thumb-wrap">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="{{ $product->name }}" class="mobile-thumb">
                    @else
                        <div class="mobile-thumb-placeholder">🐟</div>
                    @endif
                </div>
                <div class="mobile-header-info">
                    <div class="mobile-title-row">
                        <a href="{{ route('admin.products.edit', $product) }}" class="mobile-product-title">
                            {{ $product->name }}
                        </a>
                        <div class="mobile-status-badge">
                            @if($product->trashed())
                                <span class="badge badge-danger">Archived</span>
                            @elseif($product->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div class="mobile-tags-row">
                        @if($product->category)
                            <span class="cat-pill">{{ $product->category->name }}</span>
                        @endif
                        @if($product->sku)
                            <span class="sku-pill">#{{ $product->sku }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Price Tier Grid (4 Tiers) -->
            <div class="mobile-pricing-grid">
                <div class="price-tile">
                    <span class="tile-label">Retail</span>
                    <span class="tile-val font-semibold">RM {{ number_format($product->retail_price, 2) }}</span>
                </div>
                <div class="price-tile">
                    <span class="tile-label">Walk-in</span>
                    <span class="tile-val font-semibold {{ $product->is_walkin_available ? 'text-teal' : 'text-muted' }}">
                        {{ $product->is_walkin_available && $product->walkin_price > 0 ? 'RM '.number_format($product->walkin_price, 2) : '—' }}
                    </span>
                </div>
                <div class="price-tile">
                    <span class="tile-label">Wholesale</span>
                    <span class="tile-val font-semibold">
                        {{ $product->wholesale_price > 0 ? 'RM '.number_format($product->wholesale_price, 2) : '—' }}
                    </span>
                </div>
                <div class="price-tile">
                    <span class="tile-label">Trading</span>
                    <span class="tile-val font-semibold">
                        @if($product->is_rfq_only)
                            <span class="badge badge-warning text-xxs" style="padding:2px 6px">RFQ</span>
                        @elseif($product->trading_price > 0)
                            RM {{ number_format($product->trading_price, 2) }}
                        @else
                            —
                        @endif
                    </span>
                </div>
            </div>

            <!-- Stock & Channel Info -->
            <div class="mobile-stock-row">
                <div class="mobile-stock-badge">
                    @if(!$product->track_stock)
                        <span class="badge badge-success badge-subtle">● Unlimited Stock</span>
                    @elseif($product->stock_quantity <= 0)
                        <span class="badge badge-danger badge-subtle">● Out of Stock</span>
                    @elseif($product->stock_quantity <= 5)
                        <span class="badge badge-warning badge-subtle">⚠️ Low Stock: {{ $product->stock_quantity }}</span>
                    @else
                        <span class="badge badge-success badge-subtle">● In Stock: {{ $product->stock_quantity }}</span>
                    @endif
                </div>
                <div class="mobile-channel-badge">
                    @if($product->is_walkin_available)
                        <span class="channel-pill channel-walkin">🏪 Walk-in Store</span>
                    @else
                        <span class="channel-pill channel-online">🌐 Online Only</span>
                    @endif
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="mobile-card-actions">
                <a href="{{ route('shop.show', $product->slug) }}" target="_blank" class="btn btn-secondary mobile-act-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                    <span>Store</span>
                </a>

                @if(!$product->trashed())
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary mobile-act-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Edit</span>
                    </a>

                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ addslashes($product->name) }}?');" class="mobile-delete-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger mobile-act-btn">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            <span>Delete</span>
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="mobile-delete-form">
                        @csrf
                        <button type="submit" class="btn btn-success mobile-act-btn">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                            </svg>
                            <span>Restore</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @empty
        <div class="card empty-mobile-card">
            <div class="empty-state">
                <div class="empty-state-icon">🐟</div>
                <h3 class="empty-state-title">No products found</h3>
                <p class="empty-state-desc">Try modifying your search keywords or clearing applied filters.</p>
                @if(request()->hasAny(['search', 'category', 'status', 'stock']))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm" style="margin-top:10px">Clear Filters</a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="products-pagination-wrap">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* ─── Admin Products Page Scoped Modern Styles ───────────────────────────── */
.products-page-container {
    max-width: 1440px;
    margin: 0 auto;
}

/* Topbar Styling */
.products-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 18px;
    margin-bottom: 20px;
}

.title-with-badge {
    display: flex;
    align-items: center;
    gap: 12px;
}

.admin-page-title {
    font-size: 1.45rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.02em;
}

.total-count-pill {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 9999px;
    font-size: 0.76rem;
    font-weight: 600;
}

.admin-page-subtitle {
    font-size: 0.85rem;
    color: #64748b;
    margin: 4px 0 0 0;
}

.btn-add-product {
    font-weight: 600;
    padding: 9px 16px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(29, 78, 216, 0.2);
}

/* Quick KPI Stat Cards */
.product-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 20px;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 18px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    transition: all 0.2s ease;
}

.stat-card:hover {
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.stat-card.active-filter {
    border-color: #3b82f6;
    background: #f8faff;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
}

.stat-icon-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 10px;
    flex-shrink: 0;
}

.icon-blue { background: #eff6ff; color: #2563eb; }
.icon-green { background: #ecfdf5; color: #059669; }
.icon-amber { background: #fffbeb; color: #d97706; }
.icon-slate { background: #f1f5f9; color: #64748b; }

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 1.35rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.15;
}

.stat-label {
    font-size: 0.78rem;
    font-weight: 500;
    color: #64748b;
    margin-top: 2px;
}

/* Filter Card */
.filter-card {
    padding: 16px 20px;
    margin-bottom: 16px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
}

.product-filters-form {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    flex-wrap: wrap;
}

.filter-field {
    display: flex;
    flex-direction: column;
}

.filter-search {
    flex: 2;
    min-width: 220px;
}

.filter-category,
.filter-status,
.filter-stock {
    flex: 1;
    min-width: 140px;
}

.search-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.search-icon {
    position: absolute;
    left: 12px;
    color: #94a3b8;
    pointer-events: none;
    z-index: 2;
}

.search-input-wrap input.filter-search-input {
    padding-left: 38px !important;
}

.filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: auto;
}

.filter-btn {
    font-weight: 600;
    background: #f8fafc;
}

.filter-btn:hover {
    background: #f1f5f9;
}

.clear-btn {
    color: #64748b;
}

/* Results Summary Bar */
.results-summary-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
    padding: 0 4px;
}

.results-text {
    font-size: 0.84rem;
    color: #64748b;
}

.filtered-badge {
    font-size: 0.72rem;
    background: #e2e8f0;
    color: #475569;
    padding: 2px 8px;
    border-radius: 6px;
    font-weight: 600;
}

/* Desktop Table Styling */
.products-desktop-card {
    padding: 0 !important;
    overflow: hidden;
    border-radius: 12px;
}

.products-table-wrapper {
    overflow-x: auto;
    width: 100%;
}

.products-table {
    width: 100%;
    table-layout: fixed;
    margin-bottom: 0;
    border-collapse: collapse;
}

.products-table thead th {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 12px 8px !important;
    white-space: nowrap;
}

.products-table tbody td {
    padding: 12px 8px !important;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.products-table tbody tr:hover td {
    background: #f8fafc;
}

.products-table tbody tr.row-trashed td {
    background: #fffafa;
    opacity: 0.8;
}

/* Proportional Column Widths to guarantee 100% fit on 1280px laptops with no overflow */
.col-product {
    width: 25%;
}

.col-price {
    width: 10.5%;
    white-space: nowrap;
}

.col-stock {
    width: 12.5%;
    white-space: nowrap;
}

.col-status {
    width: 9%;
    white-space: nowrap;
}

.col-actions {
    width: 11%;
    text-align: right;
    white-space: nowrap;
}

/* Product Info Cell */
.product-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.product-thumb-wrap {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    overflow: hidden;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    flex-shrink: 0;
}

.product-thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-thumb-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.product-meta {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
    overflow: hidden;
}

.product-name-row {
    display: flex;
    align-items: center;
    gap: 6px;
    overflow: hidden;
}

.product-name-link {
    font-size: 0.86rem;
    font-weight: 600;
    color: #0f172a;
    text-decoration: none;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: inline-block;
    transition: color 0.15s;
}

.product-name-link:hover {
    color: #2563eb;
    text-decoration: underline;
}

.product-subtext {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-wrap: wrap;
}

.cat-pill {
    font-size: 0.68rem;
    background: #f1f5f9;
    color: #475569;
    padding: 1px 5px;
    border-radius: 4px;
    font-weight: 500;
}

.sku-pill {
    font-size: 0.68rem;
    color: #94a3b8;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
}

/* Price Cells */
.price-val {
    font-size: 0.85rem;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.2;
}

.price-unit {
    font-size: 0.68rem;
    color: #94a3b8;
    margin-top: 1px;
}

.price-tag.tag-walkin {
    font-size: 0.68rem;
    color: #0d9488;
    font-weight: 600;
    margin-top: 2px;
}

.text-teal {
    color: #0d9488 !important;
}

/* Badge Enhancements */
.badge-subtle {
    font-size: 0.7rem;
    padding: 3px 8px;
    font-weight: 600;
}

.badge-sm {
    font-size: 0.65rem;
    padding: 1px 5px;
}

.badge-warning {
    background: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
}

/* Action Buttons Group */
.actions-group {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
    text-decoration: none;
}

.btn-action:hover {
    border-color: #cbd5e1;
    transform: translateY(-1px);
}

.btn-action-view:hover {
    color: #2563eb;
    background: #eff6ff;
    border-color: #bfdbfe;
}

.btn-action-edit:hover {
    color: #0d9488;
    background: #f0fdfa;
    border-color: #99f6e4;
}

.btn-action-delete:hover {
    color: #dc2626;
    background: #fef2f2;
    border-color: #fecaca;
}

.btn-action-restore:hover {
    color: #059669;
    background: #ecfdf5;
    border-color: #a7f3d0;
}

/* Empty State */
.empty-table-cell {
    padding: 48px 16px !important;
    text-align: center;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.empty-state-icon {
    font-size: 2.5rem;
    margin-bottom: 8px;
}

.empty-state-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 4px 0;
}

.empty-state-desc {
    font-size: 0.85rem;
    color: #64748b;
    margin: 0;
}

/* Pagination */
.products-pagination-wrap {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

/* ─── Mobile View Styling (≤900px) ─────────────────────────────────────────── */
.products-mobile-list {
    display: none;
}

@media (max-width: 900px) {
    /* Hide desktop table, display mobile cards */
    .products-desktop-card {
        display: none !important;
    }

    .products-mobile-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* Topbar mobile */
    .products-topbar {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        margin-bottom: 16px;
    }

    .topbar-right .btn-add-product {
        width: 100%;
        justify-content: center;
    }

    /* Stats 2x2 on mobile */
    .product-stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 16px;
    }

    .stat-card {
        padding: 12px;
        gap: 10px;
    }

    .stat-icon-wrap {
        width: 38px;
        height: 38px;
    }

    .stat-value {
        font-size: 1.15rem;
    }

    .stat-label {
        font-size: 0.72rem;
    }

    /* Compact 2-column Filter Form on Mobile */
    .filter-card {
        padding: 14px;
        margin-bottom: 14px;
    }

    .product-filters-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .filter-search {
        grid-column: 1 / -1;
        width: 100%;
        min-width: 0;
    }

    .filter-category,
    .filter-status {
        width: 100%;
        min-width: 0;
    }

    .filter-stock {
        grid-column: 1 / -1;
        width: 100%;
        min-width: 0;
    }

    .filter-actions {
        grid-column: 1 / -1;
        width: 100%;
        display: flex;
        gap: 8px;
        margin-top: 4px;
    }

    .filter-actions .btn {
        flex: 1;
        justify-content: center;
        height: 40px;
    }

    /* Mobile Product Card */
    .product-card-mobile {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .product-card-mobile.card-trashed {
        background: #fffafa;
        border-color: #fecaca;
    }

    .mobile-card-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .mobile-thumb-wrap {
        width: 52px;
        height: 52px;
        border-radius: 8px;
        overflow: hidden;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .mobile-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .mobile-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .mobile-header-info {
        flex: 1;
        min-width: 0;
    }

    .mobile-title-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
    }

    .mobile-product-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        text-decoration: none;
        line-height: 1.3;
    }

    .mobile-tags-row {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 4px;
        flex-wrap: wrap;
    }

    /* Mobile Pricing Grid */
    .mobile-pricing-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 8px 6px;
    }

    .price-tile {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .tile-label {
        font-size: 0.66rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .tile-val {
        font-size: 0.8rem;
        color: #0f172a;
        line-height: 1.2;
    }

    /* Mobile Stock & Channel */
    .mobile-stock-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding-top: 2px;
    }

    .channel-pill {
        font-size: 0.72rem;
        font-weight: 500;
    }

    .channel-walkin {
        color: #0d9488;
    }

    .channel-online {
        color: #64748b;
    }

    /* Mobile Card Actions */
    .mobile-card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 8px;
        border-top: 1px solid #f1f5f9;
        padding-top: 10px;
    }

    .mobile-act-btn {
        width: 100%;
        padding: 7px 4px !important;
        font-size: 0.8rem !important;
        justify-content: center !important;
        border-radius: 8px !important;
    }

    .mobile-delete-form {
        display: contents;
    }

    .empty-mobile-card {
        padding: 36px 16px !important;
        text-align: center;
    }
}
</style>
@endpush
