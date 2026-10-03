@extends('layouts.app')
@section('title', __t('walkin.cart_page_title', 'Walk-in Cart — Self-Collection') . ' — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn. Bhd.'))

@section('content')
<!-- Walk-in Ocean Hero Header -->
<div class="walkin-hero-section">
    <div class="hero-bg-pattern"></div>
    <div class="hero-bg-glow"></div>

    <div class="container" style="position:relative;z-index:2">
        <div class="walkin-top-meta">
            <div class="breadcrumb" style="margin:0">
                <a href="{{ route('home') }}" class="breadcrumb-home">🏠 @t('nav.home', 'Home')</a>
                <span class="breadcrumb-sep">›</span>
                <a href="{{ route('walkin.shop') }}" class="breadcrumb-link">@t('walkin.title', 'Walk-in Express')</a>
                <span class="breadcrumb-sep">›</span>
                <span class="breadcrumb-current">@t('walkin.cart_title', 'Walk-in Cart')</span>
            </div>

            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                <span class="walkin-live-badge">
                    <span class="pulse-dot"></span>
                    @t('walkin.menu_subtitle', 'In-Store Express Menu')
                </span>
                <span class="walkin-public-price-badge">
                    <span>🏬</span>
                    <span>@t('walkin.self_collection_only', 'Self-Collection Only · No Delivery')</span>
                </span>
            </div>
        </div>

        <div class="walkin-header-grid">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;flex-wrap:wrap">
                    <span class="walkin-store-tag">
                        🏬 @t('walkin.facility_location', 'MST Cold-Chain Facility · 7 Jalan SILC 2/18, Iskandar Puteri, Johor')
                    </span>
                    <span class="walkin-tag-sub">⚡ @t('walkin.express_pickup_tag', 'Self-collection only · No delivery')</span>
                </div>
                <h1 class="walkin-hero-title">
                    @t('walkin.cart_hero_title', 'Your Walk-in Cart')
                </h1>
                <p class="walkin-hero-subtitle">
                    @t('walkin.cart_hero_subtitle', 'Review your selected items for in-store pickup before proceeding to payment and token collection.')
                </p>
            </div>

            <div class="walkin-hero-actions">
                <a href="{{ route('walkin.shop') }}" class="btn-walkin-back">
                    ← @t('walkin.add_more_products', 'Add More Products')
                </a>
                @if($items->count())
                    <a href="{{ route('walkin.checkout') }}" class="btn-walkin-hero-checkout">
                        <span>@t('walkin.pay_and_collect', 'Pay & Collect')</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="walkin-cart-page-wrap">
    <div class="container">

        <!-- Store Pickup Notice Bar -->
        <div class="walkin-pickup-banner" style="background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:14px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap">
            <div style="display:flex;align-items:center;gap:12px">
                <span style="font-size:1.5rem;flex-shrink:0">🏬</span>
                <div>
                    <div style="font-weight:700;font-size:0.88rem;color:#1e3a8a">
                        @t('walkin.pickup_at_facility', 'Self-Collection Location: MST Cold-Chain Facility (Counter 2)')
                    </div>
                    <div style="font-size:0.8rem;color:#1d4ed8;margin-top:2px">
                        @t('common.store_address_silc', '7 Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor') · <em>@t('walkin.no_delivery_charges', 'No delivery charges applied')</em>
                    </div>
                </div>
            </div>
            <a href="{{ route('walkin.shop') }}" class="btn-continue-walkin">
                ← @t('walkin.browse_menu', 'Browse Menu')
            </a>
        </div>

        <!-- Notification Toast -->
        <div id="cartToast" class="cart-toast" role="status" aria-live="polite"></div>

        <!-- Cart Content Wrapper -->
        <div id="cartContentWrapper" style="{{ $items->count() ? '' : 'display:none' }}">
            <div class="cart-layout">

                <!-- Left Column: Items List -->
                <div id="cartItemsContainer" class="cart-items-col">
                    @foreach($items as $item)
                        @php
                            $itemPrice = $item->product?->walkin_price ?? $item->product?->retail_price ?? 0;
                            $moq = 1;
                            $maxStock = $item->product?->track_stock ? $item->product->stock_quantity : 999;
                        @endphp
                        <div class="cart-item-card" id="cart-item-{{ $item->id }}" data-item-id="{{ $item->id }}"
                             data-base-rm="{{ $itemPrice }}"
                             data-group="walkin"
                        >
                            <!-- Thumbnail -->
                            <div class="cart-item-thumb">
                                @php
                                    $itemThumb = $item->product?->thumbnail ?? ($item->product?->images[0] ?? null);
                                @endphp
                                @if($itemThumb)
                                    <a href="{{ $item->product ? route('walkin.show', $item->product) : '#' }}">
                                        <img src="{{ cdn_storage($itemThumb) }}" 
                                             alt="{{ $item->product?->name ?? 'Product' }}" 
                                             loading="lazy"
                                             onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'cart-thumb-placeholder\'>📦</div>';">
                                    </a>
                                @else
                                    <div class="cart-thumb-placeholder">📦</div>
                                @endif
                            </div>

                            <!-- Info & Controls -->
                            <div class="cart-item-main">
                                <div class="cart-item-header">
                                    <div class="cart-item-cat">
                                        {{ $item->product?->category?->name ?? 'Seafood' }}
                                    </div>
                                    <h3 class="cart-item-name">
                                        <a href="{{ $item->product ? route('walkin.show', $item->product) : '#' }}">
                                            {{ $item->product?->name ?? 'Product Unavailable' }}
                                        </a>
                                    </h3>
                                    <div class="cart-item-unit-price">
                                        <span class="price-val js-cart-item-price" data-base-rm="{{ $itemPrice }}">RM {{ number_format($itemPrice, 2) }}</span>
                                        <span class="unit-val">/ {{ $item->product?->unit ?? 'pack' }}</span>
                                        @if($item->product?->weight)
                                            <span class="meta-sep">·</span>
                                            <span class="meta-weight">{{ $item->product->weight }}</span>
                                        @endif
                                        <span class="badge-walkin-pill" style="background:#eff6ff;color:#1d4ed8;padding:1px 6px;border-radius:4px;font-size:0.72rem;font-weight:700;border:1px solid #bfdbfe">
                                            @t('walkin.walkin_rate_tag', 'Walk-in Rate')
                                        </span>
                                    </div>
                                    @if($item->product?->isVariableWeight())
                                        <div style="margin-top:6px;display:flex;align-items:flex-start;gap:6px;background:#fef3c7;color:#92400e;border:1px solid #fde68a;border-radius:6px;padding:4px 8px;font-size:0.75rem;line-height:1.4">
                                            <span>⚖️</span>
                                            <div>
                                                <strong>@t('shop.reference_estimated_weight', 'Reference Weight'):</strong> {{ $item->product->getReferenceWeight() }}
                                                <div style="font-weight:normal;color:#b45309">
                                                    @t('shop.variable_weight_cart_note', 'Final billing based on actual weighed amount.')
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions Bar -->
                                <div class="cart-item-actions-bar">
                                    <div class="cart-item-controls">
                                        <!-- Tactile Stepper -->
                                        <div class="cart-qty-stepper" id="stepper-{{ $item->id }}">
                                            <button type="button" 
                                                    class="qty-btn qty-btn-minus" 
                                                    onclick="stepWalkinQty({{ $item->id }}, -1)" 
                                                    aria-label="Decrease quantity">−</button>
                                            <input type="number" 
                                                   id="qty-input-{{ $item->id }}" 
                                                   class="cart-qty-input"
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $maxStock }}"
                                                   data-current="{{ $item->quantity }}"
                                                   data-moq="1"
                                                   data-max="{{ $maxStock }}"
                                                   inputmode="numeric"
                                                   pattern="[0-9]*"
                                                   onchange="handleWalkinQtyChange({{ $item->id }})"
                                                   onkeydown="if(event.key==='Enter'){this.blur();}"
                                                   aria-label="Product quantity">
                                            <button type="button" 
                                                    class="qty-btn qty-btn-plus" 
                                                    onclick="stepWalkinQty({{ $item->id }}, 1)" 
                                                    aria-label="Increase quantity">+</button>
                                        </div>

                                        <!-- Red Remove Button -->
                                        <button type="button" 
                                                class="btn-cart-remove" 
                                                onclick="confirmRemoveWalkinItem({{ $item->id }})" 
                                                title="@t('cart.remove', 'Remove')">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                            <span>@t('cart.remove', 'Remove')</span>
                                        </button>
                                    </div>

                                    <!-- Item Subtotal -->
                                    <div class="cart-item-subtotal-box">
                                        <div class="subtotal-label">@t('cart.subtotal', 'Subtotal')</div>
                                        <div id="subtotal-{{ $item->id }}" class="subtotal-val js-cart-item-subtotal"
                                             data-qty="{{ $item->quantity }}"
                                             data-base-rm="{{ $itemPrice * $item->quantity }}"
                                        >
                                            RM {{ number_format($itemPrice * $item->quantity, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right Column: Summary Card -->
                <div class="cart-summary-col">
                    <div class="cart-summary-card">
                        <div class="summary-header" style="display:flex;align-items:center;justify-content:space-between">
                            <span>@t('walkin.order_summary', 'Walk-in Summary')</span>
                            <span class="badge" style="background:#eff6ff;color:#1d4ed8;font-size:0.75rem;font-weight:700;padding:3px 8px;border-radius:6px;border:1px solid #bfdbfe">
                                🏬 @t('walkin.self_collection', 'Self-Collection')
                            </span>
                        </div>

                        <div class="summary-line">
                            <span>@t('cart.items_subtotal', 'Items Subtotal') (<span id="summaryCount">{{ $totals['count'] }}</span> @t('cart.items_count_label', 'items'))</span>
                            <span id="summarySubtotal" class="summary-val js-cart-summary-subtotal" data-base-subtotal="{{ $totals['subtotal'] }}">
                                RM {{ number_format($totals['subtotal'], 2) }}
                            </span>
                        </div>

                        <div class="summary-line">
                            <span>@t('cart.fulfillment', 'Fulfillment')</span>
                            <span style="color:#059669;font-weight:700">@t('walkin.self_collection_free', 'Self-Collection (RM 0.00)')</span>
                        </div>

                        <div class="summary-total-line">
                            <span class="total-label">@t('cart.total_to_pay', 'Total to Pay')</span>
                            <span id="summaryTotal" class="total-val js-cart-summary-total" data-base-total="{{ $totals['total'] }}">
                                RM {{ number_format($totals['total'], 2) }}
                            </span>
                        </div>

                        <!-- SILC Counter pickup note -->
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;margin-bottom:16px;font-size:0.76rem;color:#475569;line-height:1.45">
                            📍 <strong>@t('walkin.pickup_point', 'Collection Point'):</strong> @t('walkin.pickup_point_desc', 'Counter 2 · MST Cold-Chain Facility, Iskandar Puteri, Johor.')<br>
                            ⚡ <em>@t('walkin.collection_token_note', 'Immediate sequential collection token issued upon checkout.')</em>
                        </div>

                        <!-- Checkout Button -->
                        <a href="{{ route('walkin.checkout') }}" class="btn-checkout" style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%);color:#091a36;font-weight:800;border:1px solid #fde68a;box-shadow:0 4px 14px rgba(245,158,11,0.4)">
                            🏪 @t('walkin.pay_and_collect', 'Proceed to Pay & Collect') →
                        </a>

                        <div style="text-align:center;margin-top:10px">
                            <a href="{{ route('walkin.shop') }}" style="font-size:0.82rem;color:#1d4ed8;font-weight:700;text-decoration:none">
                                ← @t('walkin.continue_shopping', 'Add More Walk-in Items')
                            </a>
                        </div>

                        <div class="summary-trust-badges" style="margin-top:16px;border-top:1px solid #f1f5f9;padding-top:12px;display:flex;flex-direction:column;gap:6px;font-size:0.75rem;color:#64748b">
                            <div>🔒 <strong>@t('walkin.secure_payment', 'Cash upon pickup or online card payment')</strong></div>
                            <div>🏬 <strong>@t('walkin.packed_pickup', 'Orders packed appropriately for cold-chain transport')</strong></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Empty Cart State -->
        <div id="emptyCartContainer" class="empty-cart-card" style="{{ $items->count() ? 'display:none' : '' }}">
            <div class="empty-cart-icon">🛒</div>
            <h2 class="empty-cart-title">@t('walkin.cart_empty_title', 'Your walk-in cart is empty')</h2>
            <p class="empty-cart-desc">
                @t('walkin.cart_empty_desc', 'You have not added any walk-in items to your cart yet. Browse our walk-in menu to select fresh catches for in-store collection.')
            </p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                <a href="{{ route('walkin.shop') }}" class="btn-browse-walkin-primary">
                    @t('walkin.browse_menu_btn', 'Browse Walk-in Menu →')
                </a>
                <a href="{{ route('home') }}" class="btn-browse-home">
                    @t('nav.home', 'Back to Home')
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Custom Confirmation Modal for Removing Cart Items -->
<div class="cart-confirm-modal-backdrop" id="cartRemoveModal" onclick="handleRemoveModalBackdropClick(event)" role="dialog" aria-modal="true" aria-labelledby="removeModalTitle">
    <div class="cart-confirm-modal-card">
        <button type="button" class="cart-confirm-modal-close" onclick="closeRemoveModal()" aria-label="Close modal">✕</button>
        
        <div class="cart-confirm-modal-icon-wrap">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
        </div>

        <h3 class="cart-confirm-modal-title" id="removeModalTitle">@t('cart.remove_modal_title', 'Remove Item from Cart?')</h3>
        <p class="cart-confirm-modal-desc">@t('cart.remove_modal_desc', 'Are you sure you want to remove this product from your walk-in cart?')</p>

        <div class="cart-confirm-item-preview">
            <div class="cart-confirm-item-thumb" id="removeModalThumb"></div>
            <div class="cart-confirm-item-info">
                <div class="cart-confirm-item-name" id="removeModalName">@t('cart.default_product_name', 'Product')</div>
                <div class="cart-confirm-item-meta" id="removeModalMeta">@t('cart.quantity_label', 'Quantity:') 1</div>
            </div>
        </div>

        <div class="cart-confirm-modal-actions">
            <button type="button" class="btn-modal-cancel" onclick="closeRemoveModal()">
                @t('cart.keep_in_cart', 'Keep in Cart')
            </button>
            <button type="button" class="btn-modal-delete" id="btnConfirmDelete" onclick="executeRemoveWalkinItem()">
                <span id="btnConfirmDeleteText">@t('cart.yes_remove', 'Yes, Remove')</span>
            </button>
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
    padding-bottom: var(--space-6);
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
    margin-bottom: var(--space-3);
}
.breadcrumb-home, .breadcrumb-link {
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
    gap: 20px;
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
    font-size: clamp(1.5rem, 3vw, 2.1rem);
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.02em;
    margin: 4px 0 6px;
    line-height: 1.2;
}
.walkin-hero-subtitle {
    color: #bae6fd;
    font-size: 0.9rem;
    max-width: 640px;
    line-height: 1.45;
    margin: 0;
}

.walkin-hero-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.btn-walkin-back {
    display: inline-flex;
    align-items: center;
    padding: 9px 16px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.15s ease;
}
.btn-walkin-back:hover { background: rgba(255, 255, 255, 0.2); }

.btn-walkin-hero-checkout {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #091a36;
    padding: 9px 18px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.88rem;
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

/* ─── Page Wrap & Layout ─── */
.walkin-cart-page-wrap {
    padding-top: 24px;
    padding-bottom: 40px;
    background: #f8fafc;
    min-height: calc(100vh - 240px);
}

.btn-continue-walkin {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    border: 1.5px solid #bfdbfe;
    color: #1d4ed8;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.15s ease;
}
.btn-continue-walkin:hover {
    background: #1d4ed8;
    color: #ffffff;
    border-color: #1d4ed8;
}

.cart-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 28px;
    align-items: start;
}

.cart-items-col {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* Item Card */
.cart-item-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 20px;
    display: flex;
    gap: 18px;
    align-items: center;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    transition: border-color 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
}
.cart-item-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
}
.cart-item-card.updating {
    opacity: 0.6;
    pointer-events: none;
}

.cart-item-thumb {
    width: 88px;
    height: 88px;
    border-radius: 12px;
    overflow: hidden;
    background: #f8fafc;
    flex-shrink: 0;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cart-item-thumb a {
    display: block;
    width: 100%;
    height: 100%;
}
.cart-item-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.25s ease;
}
.cart-item-card:hover .cart-item-thumb img {
    transform: scale(1.05);
}
.cart-thumb-placeholder {
    font-size: 2.2rem;
    color: #94a3b8;
}

.cart-item-main {
    flex: 1;
    min-width: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.cart-item-header {
    flex: 1;
    min-width: 200px;
}
.cart-item-cat {
    font-size: 0.72rem;
    font-weight: 700;
    color: #0284c7;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 2px;
}
.cart-item-name {
    font-size: 1.05rem;
    font-weight: 700;
    margin: 0 0 6px 0;
    line-height: 1.35;
}
.cart-item-name a {
    color: #0f172a;
    text-decoration: none;
    transition: color 0.15s ease;
}
.cart-item-name a:hover {
    color: #1d4ed8;
}

.cart-item-unit-price {
    font-size: 0.88rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.cart-item-unit-price .price-val {
    color: #1e40af;
    font-weight: 800;
}
.cart-item-unit-price .meta-sep {
    color: #cbd5e1;
}

.cart-item-actions-bar {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-shrink: 0;
}
.cart-item-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Stepper */
.cart-qty-stepper {
    display: inline-flex;
    align-items: center;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    background: #ffffff;
    overflow: hidden;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    height: 36px;
}
.cart-qty-stepper:focus-within {
    border-color: #1d4ed8;
    box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12);
}
.qty-btn {
    width: 34px;
    height: 100%;
    background: #f8fafc;
    border: none;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    color: #334155;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s ease, color 0.15s ease;
    user-select: none;
}
.qty-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.qty-btn:active {
    background: #cbd5e1;
}
.cart-qty-input {
    width: 48px;
    height: 100%;
    border: none;
    border-left: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    text-align: center;
    font-weight: 700;
    font-size: 0.95rem;
    color: #0f172a;
    background: #ffffff;
    outline: none;
    -moz-appearance: textfield;
    padding: 0;
}
.cart-qty-input::-webkit-outer-spin-button,
.cart-qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Remove button */
.btn-cart-remove {
    background: #dc2626 !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 9px !important;
    padding: 7px 12px !important;
    font-size: 0.8rem !important;
    font-weight: 700 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 5px !important;
    cursor: pointer !important;
    transition: all 0.15s ease !important;
    box-shadow: 0 2px 6px rgba(220, 38, 38, 0.22) !important;
    text-decoration: none !important;
    height: 36px;
    box-sizing: border-box;
}
.btn-cart-remove:hover {
    background: #b91c1c !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(185, 28, 28, 0.35) !important;
}

.cart-item-subtotal-box {
    text-align: right;
    min-width: 105px;
}
.subtotal-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 2px;
}
.subtotal-val {
    font-size: 1.15rem;
    font-weight: 800;
    color: #1e40af;
    font-family: var(--font-heading);
    transition: transform 0.2s ease, color 0.2s ease;
}

/* Summary Card */
.cart-summary-col {
    position: sticky;
    top: 95px;
}
.cart-summary-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 22px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
}
.summary-header {
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f172a;
    padding-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 16px;
}
.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 0.9rem;
    color: #64748b;
}
.summary-val {
    font-weight: 700;
    color: #1e293b;
}
.summary-total-line {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding-top: 14px;
    border-top: 2px dashed #e2e8f0;
    margin-bottom: 16px;
}
.total-label {
    font-weight: 800;
    font-size: 1.1rem;
    color: #0f172a;
}
.total-val {
    font-size: 1.55rem;
    font-weight: 900;
    color: #1e40af;
    font-family: var(--font-heading);
}

.btn-checkout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 13px 20px;
    border-radius: 12px;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    border: none;
}
.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(245, 158, 11, 0.55);
}

/* Empty State */
.empty-cart-card {
    background: #ffffff;
    border: 1px dashed #cbd5e1;
    border-radius: 20px;
    padding: 60px 24px;
    text-align: center;
    max-width: 580px;
    margin: 30px auto;
}
.empty-cart-icon {
    font-size: 3.5rem;
    margin-bottom: 12px;
}
.empty-cart-title {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 8px;
}
.empty-cart-desc {
    color: #64748b;
    font-size: 0.92rem;
    line-height: 1.55;
    margin-bottom: 24px;
}
.btn-browse-walkin-primary {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #091a36;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
}
.btn-browse-walkin-primary:hover {
    transform: translateY(-2px);
}
.btn-browse-home {
    background: #f1f5f9;
    color: #334155;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 700;
    text-decoration: none;
}

/* Confirmation Modal */
.cart-confirm-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    z-index: 1100;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
}
.cart-confirm-modal-backdrop.show { display: flex; }
.cart-confirm-modal-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 24px;
    width: 100%;
    max-width: 440px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    position: relative;
    animation: modalPop 0.2s ease-out;
}
@keyframes modalPop {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.cart-confirm-modal-close {
    position: absolute;
    top: 14px;
    right: 14px;
    background: #f1f5f9;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    font-weight: 700;
}
.cart-confirm-modal-icon-wrap {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #fee2e2;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
}
.cart-confirm-modal-title {
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 6px 0;
}
.cart-confirm-modal-desc {
    font-size: 0.85rem;
    color: #64748b;
    margin: 0 0 16px 0;
}
.cart-confirm-item-preview {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 12px;
    margin-bottom: 18px;
}
.cart-confirm-item-thumb {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    overflow: hidden;
    background: #ffffff;
    flex-shrink: 0;
}
.cart-confirm-item-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cart-confirm-item-name {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0f172a;
}
.cart-confirm-item-meta {
    font-size: 0.75rem;
    color: #64748b;
}
.cart-confirm-modal-actions {
    display: flex;
    gap: 10px;
}
.btn-modal-cancel {
    flex: 1;
    padding: 10px;
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    font-weight: 700;
    cursor: pointer;
}
.btn-modal-delete {
    flex: 1;
    padding: 10px;
    border-radius: 10px;
    border: none;
    background: #dc2626;
    color: #ffffff;
    font-weight: 700;
    cursor: pointer;
}

/* Toast */
.cart-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #0f172a;
    color: #ffffff;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 600;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.25);
    z-index: 1000;
    display: none;
    align-items: center;
    gap: 8px;
    animation: toastPop 0.25s ease-out;
}
.cart-toast.show { display: flex; }

@media (max-width: 900px) {
    .cart-layout {
        grid-template-columns: 1fr;
    }
    .cart-summary-col {
        position: static;
    }
    .walkin-header-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
let pendingRemoveId = null;
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

function showToast(msg) {
    const toast = document.getElementById('cartToast');
    if (!toast) return;
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// Stepper adjustment
function stepWalkinQty(cartId, delta) {
    const input = document.getElementById('qty-input-' + cartId);
    if (!input) return;
    let current = parseInt(input.value, 10) || 1;
    let target = current + delta;
    if (target < 1) {
        confirmRemoveWalkinItem(cartId);
        return;
    }
    input.value = target;
    handleWalkinQtyChange(cartId);
}

// Input change handler
async function handleWalkinQtyChange(cartId) {
    const input = document.getElementById('qty-input-' + cartId);
    const card = document.getElementById('cart-item-' + cartId);
    if (!input || !card) return;

    let targetQty = parseInt(input.value, 10);
    if (isNaN(targetQty) || targetQty < 1) {
        targetQty = 1;
        input.value = 1;
    }

    card.classList.add('updating');

    try {
        const res = await fetch('/cart/' + cartId, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                quantity: targetQty,
                group: 'walkin'
            })
        });

        const data = await res.json();

        if (data.success) {
            input.value = targetQty;
            input.setAttribute('data-current', targetQty);

            // Update item subtotal
            const subtotalEl = document.getElementById('subtotal-' + cartId);
            if (subtotalEl) {
                subtotalEl.textContent = 'RM ' + data.item_subtotal_formatted;
                subtotalEl.setAttribute('data-qty', targetQty);
            }

            // Update order summary
            updateWalkinSummary(data.count, data.subtotal_formatted, data.total_formatted);
            showToast('✓ Walk-in cart updated');
        } else {
            alert(data.message || 'Error updating quantity');
            input.value = input.getAttribute('data-current') || 1;
        }
    } catch(err) {
        alert('Network error updating cart. Please try again.');
        input.value = input.getAttribute('data-current') || 1;
    } finally {
        card.classList.remove('updating');
    }
}

function updateWalkinSummary(count, subtotalFormatted, totalFormatted) {
    const summaryCount = document.getElementById('summaryCount');
    const summarySubtotal = document.getElementById('summarySubtotal');
    const summaryTotal = document.getElementById('summaryTotal');

    if (summaryCount) summaryCount.textContent = count;
    if (summarySubtotal) summarySubtotal.textContent = 'RM ' + subtotalFormatted;
    if (summaryTotal) summaryTotal.textContent = 'RM ' + totalFormatted;
}

// Remove Confirmation Modal
function confirmRemoveWalkinItem(cartId) {
    pendingRemoveId = cartId;
    const card = document.getElementById('cart-item-' + cartId);
    const modal = document.getElementById('cartRemoveModal');

    if (card && modal) {
        const nameEl = card.querySelector('.cart-item-name a');
        const thumbEl = card.querySelector('.cart-item-thumb img');
        const qtyEl = document.getElementById('qty-input-' + cartId);

        document.getElementById('removeModalName').textContent = nameEl ? nameEl.textContent : 'Product';
        document.getElementById('removeModalMeta').textContent = 'Quantity: ' + (qtyEl ? qtyEl.value : 1);
        
        const previewThumb = document.getElementById('removeModalThumb');
        if (thumbEl && previewThumb) {
            previewThumb.innerHTML = '<img src="' + thumbEl.src + '" style="width:100%;height:100%;object-fit:cover">';
        } else if (previewThumb) {
            previewThumb.innerHTML = '<span style="font-size:1.5rem">📦</span>';
        }

        modal.classList.add('show');
    }
}

function closeRemoveModal() {
    pendingRemoveId = null;
    const modal = document.getElementById('cartRemoveModal');
    if (modal) modal.classList.remove('show');
}

function handleRemoveModalBackdropClick(e) {
    if (e.target.id === 'cartRemoveModal') {
        closeRemoveModal();
    }
}

async function executeRemoveWalkinItem() {
    if (!pendingRemoveId) return;
    const cartId = pendingRemoveId;
    const btn = document.getElementById('btnConfirmDelete');
    const btnText = document.getElementById('btnConfirmDeleteText');

    if (btn) btn.disabled = true;
    if (btnText) btnText.textContent = 'Removing...';

    try {
        const res = await fetch('/cart/' + cartId, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const data = await res.json();

        if (data.success) {
            closeRemoveModal();
            const card = document.getElementById('cart-item-' + cartId);
            if (card) {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.remove();
                    if (data.is_empty) {
                        document.getElementById('cartContentWrapper').style.display = 'none';
                        document.getElementById('emptyCartContainer').style.display = 'block';
                    }
                }, 300);
            }

            updateWalkinSummary(data.count, data.subtotal_formatted, data.total_formatted);
            showToast('✓ Item removed from walk-in cart');
        } else {
            alert(data.message || 'Error removing item');
        }
    } catch(err) {
        alert('Network error removing item.');
    } finally {
        if (btn) btn.disabled = false;
        if (btnText) btnText.textContent = 'Yes, Remove';
    }
}
</script>
@endpush
