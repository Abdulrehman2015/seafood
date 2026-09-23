@extends('layouts.app')
@section('title', __t('cart.title', 'Shopping Cart') . ' — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd'))

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 @t('nav.home', 'Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('cart.title', 'Shopping Cart')</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        🛒 @t('cart.secure_cold_chain_cart', 'Secure Cold-Chain Cart')
                    </span>
                    <span style="color:#bae6fd;font-size:0.78rem">@t('cart.cold_chain_protected', 'Continuous -18°C Cold Chain Protected')</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,1.95rem);margin-bottom:4px;letter-spacing:-0.02em">
                    @t('cart.your_shopping_cart', 'Your Shopping Cart')
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.88rem;max-width:680px;line-height:1.4;margin:0">
                    @t('cart.header_subtitle', 'Review your selected frozen catches, adjust carton quantities, and proceed to encrypted checkout.')
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div style="font-size:0.8rem;padding:5px 12px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb">
                    🔒 @t('cart.encrypted_256', '256-Bit Encrypted')
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cart-page-wrap">
    <div class="container">
        <!-- Header bar -->
        <div class="cart-page-header" id="cartPageHeaderBar" style="{{ $items->count() ? '' : 'display:none' }}">
            <div>
                <span style="font-size:1.05rem;font-weight:700;color:#0f274a">
                    @if(current_locale() === 'zh')
                        您的购物车中共有 <span id="cartHeaderCount">{{ $totals['count'] }}</span> 件商品
                    @elseif(current_locale() === 'bm')
                        <span id="cartHeaderCount">{{ $totals['count'] }}</span> item dalam troli anda
                    @else
                        <span id="cartHeaderCount">{{ $totals['count'] }}</span> {{ Str::plural('item', $totals['count']) }} in your cart
                    @endif
                </span>
                @if(session('walkin_session'))
                    · <span class="badge" style="background:#eff6ff;color:#1d4ed8;font-weight:700;border:1px solid #bfdbfe">🏪 @t('cart.walkin_mode', 'In-Store Walk-in Mode')</span>
                @endif
            </div>
            @if($items->count())
                <a href="{{ route('shop.index') }}" class="btn-continue-shopping" id="continueShopBtn">
                    ← @t('cart.add_more_products', 'Add More Products')
                </a>
            @endif
        </div>

        <!-- Notification Toast (In-place feedback) -->
        <div id="cartToast" class="cart-toast" role="status" aria-live="polite"></div>

        <div id="cartContentWrapper" style="{{ $items->count() ? '' : 'display:none' }}">
            <div class="cart-layout">
                
                <!-- Left: Cart Items List -->
                <div id="cartItemsContainer" class="cart-items-col">
                    @foreach($items as $item)
                        @php 
                            $itemPrice = $item->product?->getPriceForGroup($item->customer_group) ?? 0;
                            $moq = $item->product?->getMoqForGroup($item->customer_group) ?? 1;
                            $maxStock = $item->product?->track_stock ? $item->product->stock_quantity : 999;
                            $displayPrice = $item->product ? $item->product->getDisplayPrice($item->customer_group) : null;
                            $curAmount = $displayPrice['amount'] ?? $itemPrice;
                        @endphp
                        <div class="cart-item-card" id="cart-item-{{ $item->id }}" data-item-id="{{ $item->id }}"
                             data-base-rm="{{ $itemPrice }}"
                             data-manual-sgd="{{ $item->product?->price_sgd ?? '' }}"
                             data-manual-usd="{{ $item->product?->price_usd ?? '' }}"
                             @if(in_array($item->customer_group, ['wholesale','trading']))
                             data-manual-wholesale-sgd="{{ $item->product?->wholesale_price_sgd ?? '' }}"
                             data-manual-wholesale-usd="{{ $item->product?->wholesale_price_usd ?? '' }}"
                             data-group="{{ $item->customer_group }}"
                             @endif
                        >
                            
                            <!-- Thumbnail -->
                            <div class="cart-item-thumb">
                                @if($item->product?->thumbnail)
                                    <a href="{{ route('shop.show', $item->product) }}">
                                        <img src="{{ asset('storage/'.$item->product->thumbnail) }}" alt="{{ $item->product->name }}" loading="lazy">
                                    </a>
                                @else
                                    <div class="cart-thumb-placeholder">🐟</div>
                                @endif
                            </div>

                            <!-- Product Info & Actions -->
                            <div class="cart-item-main">
                                <div class="cart-item-header">
                                    <div class="cart-item-cat">
                                        {{ $item->product?->category?->name ?? __t('cart.default_category', 'Seafood') }}
                                    </div>
                                    <h3 class="cart-item-name">
                                        <a href="{{ $item->product ? route('shop.show', $item->product) : '#' }}">
                                            {{ $item->product?->name ?? __t('cart.product_unavailable', 'Product Unavailable') }}
                                        </a>
                                    </h3>
                                    <div class="cart-item-unit-price">
                                        <span class="price-val js-cart-item-price"
                                              data-base-rm="{{ $itemPrice }}"
                                              data-manual-sgd="{{ $item->product?->price_sgd ?? '' }}"
                                              data-manual-usd="{{ $item->product?->price_usd ?? '' }}"
                                        >{{ $currencySymbol }} {{ number_format($curAmount, 2) }}</span>
                                        <span class="price-base-rm" style="display:{{ $currentCurrency !== 'MYR' ? 'inline' : 'none' }};font-size:0.75rem;color:#64748b;margin-left:3px">(RM {{ number_format($itemPrice, 2) }})</span>
                                        <span class="unit-val">/ {{ $item->product?->unit ?? __t('cart.default_unit', 'unit') }}</span>
                                        @if($item->product?->weight)
                                            <span class="meta-sep">·</span>
                                            <span class="meta-weight">{{ $item->product->weight }}</span>
                                        @endif
                                        @if($moq > 1)
                                            <span class="meta-sep">·</span>
                                            <span class="meta-moq">@t('shop.moq_label', 'MOQ:') {{ $moq }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions & Stepper Bar -->
                                <div class="cart-item-actions-bar">
                                    <div class="cart-item-controls">
                                        <!-- Editable Stepper -->
                                        <div class="cart-qty-stepper" id="stepper-{{ $item->id }}">
                                            <button type="button" 
                                                    class="qty-btn qty-btn-minus" 
                                                    onclick="stepCartQty({{ $item->id }}, -1)" 
                                                    aria-label="Decrease quantity">−</button>
                                            <input type="number" 
                                                   id="qty-input-{{ $item->id }}" 
                                                   class="cart-qty-input"
                                                   value="{{ $item->quantity }}" 
                                                   min="{{ $moq }}" 
                                                   max="{{ $maxStock }}"
                                                   data-current="{{ $item->quantity }}"
                                                   data-moq="{{ $moq }}"
                                                   data-max="{{ $maxStock }}"
                                                   inputmode="numeric"
                                                   pattern="[0-9]*"
                                                   onchange="handleQtyInputChange({{ $item->id }})"
                                                   onkeydown="if(event.key==='Enter'){this.blur();}"
                                                   aria-label="Product quantity">
                                            <button type="button" 
                                                    class="qty-btn qty-btn-plus" 
                                                    onclick="stepCartQty({{ $item->id }}, 1)" 
                                                    aria-label="Increase quantity">+</button>
                                        </div>

                                        <!-- Red Remove Button with White Text -->
                                        <button type="button" 
                                                class="btn-cart-remove" 
                                                onclick="confirmRemoveCartItem({{ $item->id }})" 
                                                title="@t('cart.remove', 'Remove')">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                            <span>@t('cart.remove', 'Remove')</span>
                                        </button>
                                    </div>

                                    <!-- Item Subtotal on Mobile & Desktop -->
                                    <div class="cart-item-subtotal-box">
                                        <div class="subtotal-label">@t('cart.subtotal', 'Subtotal')</div>
                                        <div id="subtotal-{{ $item->id }}" class="subtotal-val js-cart-item-subtotal"
                                             data-qty="{{ $item->quantity }}"
                                             data-base-rm="{{ $itemPrice * $item->quantity }}"
                                        >
                                            {{ $currencySymbol }} {{ number_format($curAmount * $item->quantity, 2) }}
                                            @if($currentCurrency !== 'MYR')
                                                <div style="font-size:0.75rem;color:#64748b;font-weight:normal">RM {{ number_format($itemPrice * $item->quantity, 2) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Right: Order Summary Card -->
                <div class="cart-summary-col">
                    <div class="cart-summary-card">
                        <div class="summary-header">
                            <span>@t('cart.order_summary', 'Order Summary')</span>
                        </div>

                        <div class="summary-line">
                            <span>@t('cart.items_subtotal', 'Items Subtotal') (<span id="summaryCount">{{ $totals['count'] }}</span> @t('cart.items_count_label', 'items'))</span>
                            <span id="summarySubtotal" class="summary-val js-cart-summary-subtotal" data-base-subtotal="{{ $totals['subtotal'] }}">
                                {{ $currencySymbol }} {{ number_format($currencyService->convert($totals['subtotal'], $currentCurrency), 2) }}
                                @if($currentCurrency !== 'MYR')
                                    <span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal">RM {{ number_format($totals['subtotal'], 2) }}</span>
                                @endif
                            </span>
                        </div>

                        <div class="summary-line">
                            <span>@t('cart.fulfillment', 'Fulfillment')</span>
                            @if(session('walkin_session'))
                                <span style="color:#1d4ed8;font-weight:700">@t('cart.counter_pickup_free', 'Counter Pickup (FREE)')</span>
                            @else
                                <span style="font-size:0.8rem;color:#64748b">@t('cart.calculated_at_checkout', 'Calculated at checkout')</span>
                            @endif
                        </div>

                        <div class="summary-total-line">
                            <span class="total-label">@t('cart.estimated_total', 'Estimated Total')</span>
                            <span id="summaryTotal" class="total-val js-cart-summary-total" data-base-total="{{ $totals['total'] }}">
                                {{ $currencySymbol }} {{ number_format($currencyService->convert($totals['total'], $currentCurrency), 2) }}
                                @if($currentCurrency !== 'MYR')
                                    <span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal;margin-top:2px">Base: RM {{ number_format($totals['total'], 2) }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="js-cart-currency-note" style="{{ $currentCurrency !== 'MYR' ? '' : 'display:none' }};font-size:0.75rem;color:#64748b;margin:8px 0 12px 0;background:#f8fafc;padding:8px 10px;border-radius:8px;border:1px solid #e2e8f0;line-height:1.4">
                            ℹ️ @t('cart.currency_note', 'Prices displayed in :currency for reference. Final payment will be processed in MYR at checkout.', ['currency' => '<strong class="js-cart-currency-code">' . $currentCurrency . '</strong>'])
                        </div>

                        <!-- Checkout Buttons -->
                        @if(session('walkin_session'))
                            <a href="{{ route('walkin.checkout') }}" class="btn-checkout">
                                🏪 @t('cart.walkin_checkout', 'Walk-in Express Checkout') →
                            </a>
                            <div style="text-align:center;margin-top:8px;font-size:0.75rem;color:#64748b">
                                @t('cart.silc_counter_desc', 'Johor Bahru (SILC) Counter · Immediate Collection Token')
                            </div>
                        @else
                            @auth
                                @if(auth()->user()->needsApproval())
                                    <div class="alert alert-warning" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;font-size:0.85rem;padding:10px;border-radius:10px">
                                        ⏳ @t('cart.pending_approval_alert', 'Your B2B account is pending approval before you can place wholesale orders.')
                                    </div>
                                @else
                                    <a href="{{ route('checkout.index') }}" class="btn-checkout">
                                        🔒 @t('cart.checkout_btn', 'Proceed to Checkout') →
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('checkout.index') }}" class="btn-checkout">
                                    🔒 @t('cart.checkout_btn', 'Proceed to Checkout') →
                                </a>
                                <div style="text-align:center;margin-top:10px;font-size:0.82rem;color:#64748b">
                                    @t('cart.have_wholesale_account', 'Have a Wholesale Account?') <a href="{{ route('login') }}" style="color:#1d4ed8;font-weight:700;text-decoration:underline">@t('cart.sign_in', 'Sign in')</a>
                                </div>
                            @endauth
                        @endif

                        <div class="summary-trust-badges">
                            <div>🔒 @t('cart.trust_encrypted', '256-bit Encrypted Secure Payment')</div>
                            <div>❄️ @t('cart.trust_cold_chain', '100% Cold Chain Guaranteed Freshness')</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Empty Cart State -->
        <div id="emptyCartContainer" class="empty-cart-card" style="{{ $items->count() ? 'display:none' : '' }}">
            <div class="empty-cart-icon">🛒</div>
            <h2 class="empty-cart-title">@t('cart.empty_title', 'Your Cart is Currently Empty')</h2>
            <p class="empty-cart-desc">
                @t('cart.empty_desc', 'Looks like you haven\'t added any fresh seafood items to your cart yet.')
            </p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                <a href="{{ route('shop.index') }}" class="btn-browse-seafood">
                    @t('cart.continue_shopping', 'Browse Fresh Seafood') →
                </a>
                @if(session('walkin_session'))
                    <a href="{{ route('walkin.shop') }}" class="btn-browse-walkin">
                        @t('cart.walkin_catalogue', 'Walk-in Catalogue')
                    </a>
                @endif
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
        <p class="cart-confirm-modal-desc">@t('cart.remove_modal_desc', 'Are you sure you want to remove this catch from your shopping cart?')</p>

        <div class="cart-confirm-item-preview">
            <div class="cart-confirm-item-thumb" id="removeModalThumb"></div>
            <div class="cart-confirm-item-info">
                <div class="cart-confirm-item-name" id="removeModalName">@t('cart.default_product_name', 'Seafood Product')</div>
                <div class="cart-confirm-item-meta" id="removeModalMeta">@t('cart.quantity_label', 'Quantity:') 1</div>
            </div>
        </div>

        <div class="cart-confirm-modal-actions">
            <button type="button" class="btn-modal-cancel" onclick="closeRemoveModal()">
                @t('cart.keep_in_cart', 'Keep in Cart')
            </button>
            <button type="button" class="btn-modal-delete" id="btnConfirmDelete" onclick="executeRemoveCartItem()">
                <span id="btnConfirmDeleteText">@t('cart.yes_remove', 'Yes, Remove')</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ─── Cart Page Scoped Styles ────────────────────────────────────────────── */
.cart-page-wrap {
    padding-top: 24px;
    padding-bottom: 24px;
    background: #f8fafc;
    min-height: calc(100vh - 220px);
    display: flex;
    flex-direction: column;
}

.cart-page-wrap > .container {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.cart-page-header {
    margin-top: 0;
    margin-bottom: 16px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 14px;
}
.cart-title {
    font-family: var(--font-heading);
    margin: 0 0 4px 0;
    font-size: clamp(1.6rem, 3vw, 2.1rem);
    color: #0f172a;
    letter-spacing: -0.02em;
}
.cart-subtitle {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0;
}

.btn-continue-shopping {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    color: #334155;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
}
.btn-continue-shopping:hover {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
    transform: translateY(-1px);
}

/* Toast Message */
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
@keyframes toastPop {
    from { opacity: 0; transform: translateY(12px) scale(0.96); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

/* Layout Grid */
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

/* Individual Item Card */
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
    position: relative;
}
.cart-item-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
}
.cart-item-card.updating {
    opacity: 0.6;
    pointer-events: none;
}

/* Thumbnail */
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

/* Main Content */
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
.cart-item-unit-price .meta-moq {
    color: #d97706;
    font-weight: 700;
    background: #fef3c7;
    padding: 1px 6px;
    border-radius: 4px;
    font-size: 0.75rem;
}

/* Actions Bar */
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

/* Tactile Stepper with Direct Number Input */
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

/* Remove Button: Red Background, White Text */
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
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(185, 28, 28, 0.35) !important;
}
.btn-cart-remove:active {
    transform: translateY(0);
}

/* Subtotal display */
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
.subtotal-val.price-pop {
    transform: scale(1.08);
    color: #1d4ed8;
}

/* Right Summary Card */
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
    margin-bottom: 20px;
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
    width: 100%;
    padding: 14px;
    background: #1d4ed8;
    color: #ffffff !important;
    font-weight: 800;
    font-size: 1rem;
    border-radius: 12px;
    text-decoration: none;
    border: none;
    box-shadow: 0 4px 14px rgba(29, 78, 216, 0.28);
    transition: all 0.15s ease;
    cursor: pointer;
}
.btn-checkout:hover {
    background: #1e40af;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(29, 78, 216, 0.38);
}

.summary-trust-badges {
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
    font-size: 0.78rem;
    color: #64748b;
    line-height: 1.6;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

/* Empty State Card */
.empty-cart-card {
    text-align: center;
    padding: 42px 28px;
    background: #ffffff;
    border-radius: 20px;
    border: 1px dashed #cbd5e1;
    max-width: 520px;
    width: 100%;
    margin: auto auto;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
}
.empty-cart-icon {
    font-size: 3.8rem;
    margin-bottom: 12px;
}
.empty-cart-title {
    font-family: var(--font-heading);
    color: #0f172a;
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0 0 8px 0;
}
.empty-cart-desc {
    color: #64748b;
    font-size: 0.95rem;
    max-width: 420px;
    margin: 0 auto 24px auto;
    line-height: 1.5;
}
.btn-browse-seafood {
    background: #1d4ed8;
    color: #ffffff;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    transition: all 0.15s ease;
}
.btn-browse-seafood:hover {
    background: #1e40af;
    transform: translateY(-1px);
}
.btn-browse-walkin {
    background: #f1f5f9;
    color: #334155;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    transition: all 0.15s ease;
}
.btn-browse-walkin:hover {
    background: #e2e8f0;
}

/* ─── Responsive Media Queries ───────────────────────────────────────────── */
@media (max-width: 960px) {
    .cart-layout {
        grid-template-columns: 1fr;
        gap: 22px;
    }
    .cart-summary-col {
        position: static;
    }
}

@media (max-width: 640px) {
    .cart-page-wrap {
        padding-top: 16px;
        padding-bottom: 16px;
    }
    .cart-item-card {
        padding: 12px 14px;
        gap: 12px;
        align-items: flex-start;
    }
    .cart-item-thumb {
        width: 72px;
        height: 72px;
        border-radius: 10px;
    }
    .cart-item-main {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    .cart-item-header {
        min-width: unset;
    }
    .cart-item-name {
        font-size: 0.95rem;
        margin-bottom: 4px;
    }
    .cart-item-unit-price {
        font-size: 0.82rem;
    }
    .cart-item-actions-bar {
        width: 100%;
        justify-content: space-between;
        gap: 10px;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
    }
    .cart-qty-stepper {
        height: 32px;
    }
    .qty-btn {
        width: 30px;
        font-size: 1rem;
    }
    .cart-qty-input {
        width: 42px;
        font-size: 0.88rem;
    }
    .btn-cart-remove {
        height: 32px;
        padding: 4px 10px !important;
        font-size: 0.75rem !important;
    }
    .subtotal-val {
        font-size: 1.05rem;
    }
    .cart-summary-card {
        padding: 18px 16px;
    }
    .total-val {
        font-size: 1.35rem;
    }
}

@media (max-width: 400px) {
    .cart-item-actions-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    .cart-item-subtotal-box {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 4px;
    }
}

/* ─── Custom Cart Remove Confirmation Modal ─────────────────────────────── */
.cart-confirm-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(6, 21, 43, 0.65);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 1200;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 18px;
    opacity: 0;
    transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.cart-confirm-modal-backdrop.show {
    display: flex;
    opacity: 1;
}

.cart-confirm-modal-card {
    background: #ffffff;
    border-radius: 20px;
    max-width: 430px;
    width: 100%;
    padding: 28px 24px 24px;
    box-shadow: 0 24px 48px rgba(6, 21, 43, 0.22), 0 8px 18px rgba(6, 21, 43, 0.08);
    position: relative;
    border: 1px solid #e2e8f0;
    transform: scale(0.92) translateY(8px);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    text-align: center;
}

.cart-confirm-modal-backdrop.show .cart-confirm-modal-card {
    transform: scale(1) translateY(0);
}

.cart-confirm-modal-close {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #64748b;
    border: none;
    font-size: 0.95rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
}

.cart-confirm-modal-close:hover {
    background: #e2e8f0;
    color: #0f172a;
    transform: scale(1.05);
}

.cart-confirm-modal-icon-wrap {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: #fef2f2;
    border: 1.5px solid #fee2e2;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.12);
}

.cart-confirm-modal-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 6px 0;
    font-family: var(--font-heading, inherit);
    letter-spacing: -0.01em;
}

.cart-confirm-modal-desc {
    font-size: 0.88rem;
    color: #64748b;
    margin: 0 0 18px 0;
    line-height: 1.5;
}

.cart-confirm-item-preview {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    text-align: left;
    margin-bottom: 22px;
}

.cart-confirm-item-thumb {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    overflow: hidden;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.2rem;
}

.cart-confirm-item-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-confirm-item-info {
    flex: 1;
    min-width: 0;
}

.cart-confirm-item-name {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}

.cart-confirm-item-meta {
    font-size: 0.76rem;
    color: #64748b;
    margin-top: 2px;
}

.cart-confirm-modal-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn-modal-cancel {
    flex: 1;
    padding: 11px 16px;
    border-radius: 12px;
    background: #f1f5f9;
    color: #334155;
    font-weight: 700;
    font-size: 0.88rem;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.15s ease;
    outline: none;
}

.btn-modal-cancel:hover {
    background: #e2e8f0;
    color: #0f172a;
}

.btn-modal-delete {
    flex: 1;
    padding: 11px 16px;
    border-radius: 12px;
    background: #ef4444;
    color: #ffffff;
    font-weight: 700;
    font-size: 0.88rem;
    border: 1px solid #dc2626;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.24);
    cursor: pointer;
    transition: all 0.15s ease;
    outline: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-modal-delete:hover {
    background: #dc2626;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
    transform: translateY(-1px);
}

.btn-modal-delete:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
}
</style>
@endpush

@push('scripts')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

const cartI18n = {
    cartUpdated: @json(__t('cart.cart_updated', 'Cart updated')),
    itemRemoved: @json(__t('cart.item_removed', 'Item removed from cart')),
    networkError: @json(__t('cart.network_error', 'Network error updating cart.')),
    errorRemoving: @json(__t('cart.error_removing', 'Error removing item.')),
    couldNotUpdate: @json(__t('cart.could_not_update', 'Could not update quantity')),
    couldNotRemove: @json(__t('cart.could_not_remove', 'Could not remove item')),
    minOrderQty: @json(__t('cart.min_order_qty', 'Minimum order quantity is :moq')),
    maxStock: @json(__t('cart.max_stock', 'Maximum available stock is :max')),
    removing: @json(__t('cart.removing', 'Removing...')),
    yesRemove: @json(__t('cart.yes_remove', 'Yes, Remove')),
    qtyLabel: @json(__t('cart.quantity_label', 'Quantity:')),
    subtotalLabel: @json(__t('cart.subtotal', 'Subtotal')),
    defaultProductName: @json(__t('cart.default_product_name', 'Seafood Product')),
};

// Show toast message
function showToast(msg) {
    const toast = document.getElementById('cartToast');
    if (!toast) return;
    toast.textContent = msg;
    toast.classList.add('show');
    clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(() => toast.classList.remove('show'), 2500);
}

// Stepper click handler (+ / -)
function stepCartQty(cartId, delta) {
    const input = document.getElementById('qty-input-' + cartId);
    if (!input) return;

    const currentQty = parseInt(input.value) || 1;
    const moq = parseInt(input.dataset.moq) || 1;
    const maxStock = parseInt(input.dataset.max) || 999;
    const newQty = currentQty + delta;

    if (newQty < moq) {
        showToast(cartI18n.minOrderQty.replace(':moq', moq));
        return;
    }
    if (newQty > maxStock) {
        showToast(cartI18n.maxStock.replace(':max', maxStock));
        return;
    }

    input.value = newQty;
    sendCartUpdate(cartId, newQty);
}

// Direct numeric input change handler
function handleQtyInputChange(cartId) {
    const input = document.getElementById('qty-input-' + cartId);
    if (!input) return;

    let enteredQty = parseInt(input.value);
    const moq = parseInt(input.dataset.moq) || 1;
    const maxStock = parseInt(input.dataset.max) || 999;
    const prevQty = parseInt(input.dataset.current) || moq;

    if (isNaN(enteredQty) || enteredQty < moq) {
        showToast(cartI18n.minOrderQty.replace(':moq', moq));
        enteredQty = moq;
        input.value = moq;
    } else if (enteredQty > maxStock) {
        showToast(cartI18n.maxStock.replace(':max', maxStock));
        enteredQty = maxStock;
        input.value = maxStock;
    }

    if (enteredQty === prevQty) return;

    sendCartUpdate(cartId, enteredQty);
}

// AJAX update quantity in-place (no page reload)
async function sendCartUpdate(cartId, quantity) {
    const input = document.getElementById('qty-input-' + cartId);
    const prevQty = parseInt(input.dataset.current) || 1;
    const card = document.getElementById('cart-item-' + cartId);

    if (card) card.classList.add('updating');

    try {
        const response = await fetch('/cart/' + cartId, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ quantity: quantity })
        });

        const data = await response.json();

        if (data.success) {
            // Update item row
            input.dataset.current = quantity;
            input.value = quantity;

            const subtotalEl = document.getElementById('subtotal-' + cartId);
            if (subtotalEl) {
                subtotalEl.setAttribute('data-base-item-subtotal', data.item_subtotal);
                const curCode = (window.AppCurrency && window.AppCurrency.current) || data.currency || 'MYR';
                const curSymbol = (window.AppCurrency && window.AppCurrency.symbol) || data.currency_symbol || 'RM';

                if (data.currency === curCode && data.item_currency_subtotal_formatted) {
                    subtotalEl.innerHTML = data.item_currency_subtotal_formatted + (curCode !== 'MYR' ? '<span style="font-size:0.75rem;color:#64748b;display:block;font-weight:normal">RM ' + parseFloat(data.item_subtotal).toFixed(2) + '</span>' : '');
                } else {
                    const rate = (window.AppCurrency && window.AppCurrency.rates && window.AppCurrency.rates[curCode]) ? parseFloat(window.AppCurrency.rates[curCode]) : 1;
                    const convertedItemSub = curCode === 'MYR' ? data.item_subtotal : (Math.round(data.item_subtotal * rate * 100) / 100);
                    subtotalEl.innerHTML = curSymbol + ' ' + convertedItemSub.toFixed(2) + (curCode !== 'MYR' ? '<span style="font-size:0.75rem;color:#64748b;display:block;font-weight:normal">RM ' + parseFloat(data.item_subtotal).toFixed(2) + '</span>' : '');
                }
            }

            // Update summary counts and totals
            if (data.count !== undefined) {
                const headerCount = document.getElementById('cartHeaderCount');
                const summaryCount = document.getElementById('summaryCount');
                if (headerCount) headerCount.textContent = data.count;
                if (summaryCount) summaryCount.textContent = data.count;
            }

            if (data.total !== undefined) {
                const curCode = (window.AppCurrency && window.AppCurrency.current) || data.currency || 'MYR';
                const curSymbol = (window.AppCurrency && window.AppCurrency.symbol) || data.currency_symbol || 'RM';

                let displaySubtotal = '';
                let displayTotal = '';
                if (data.currency === curCode && data.currency_subtotal_formatted) {
                    displaySubtotal = data.currency_subtotal_formatted;
                    displayTotal = data.currency_total_formatted;
                } else {
                    const rate = (window.AppCurrency && window.AppCurrency.rates && window.AppCurrency.rates[curCode]) ? parseFloat(window.AppCurrency.rates[curCode]) : 1;
                    const convertedSubtotal = curCode === 'MYR' ? data.subtotal : (Math.round(data.subtotal * rate * 100) / 100);
                    const convertedTotal = curCode === 'MYR' ? data.total : (Math.round(data.total * rate * 100) / 100);
                    displaySubtotal = curSymbol + ' ' + convertedSubtotal.toFixed(2);
                    displayTotal = curSymbol + ' ' + convertedTotal.toFixed(2);
                }

                const summarySubtotal = document.getElementById('summarySubtotal');
                const summaryTotal = document.getElementById('summaryTotal');
                if (summarySubtotal) {
                    summarySubtotal.setAttribute('data-base-subtotal', data.subtotal);
                    if (curCode !== 'MYR') {
                        summarySubtotal.innerHTML = displaySubtotal + '<span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal">RM ' + parseFloat(data.subtotal).toFixed(2) + '</span>';
                    } else {
                        summarySubtotal.textContent = 'RM ' + parseFloat(data.subtotal).toFixed(2);
                    }
                }
                if (summaryTotal) {
                    summaryTotal.setAttribute('data-base-total', data.total);
                    if (curCode !== 'MYR') {
                        summaryTotal.innerHTML = displayTotal + '<span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal;margin-top:2px">Base: RM ' + parseFloat(data.total).toFixed(2) + '</span>';
                    } else {
                        summaryTotal.textContent = 'RM ' + parseFloat(data.total).toFixed(2);
                    }
                }
            }

            // Update navbar cart badge
            if (typeof updateCartCount === 'function') updateCartCount();

            showToast(cartI18n.cartUpdated);
        } else {
            showToast(data.message || cartI18n.couldNotUpdate);
            input.value = prevQty;
        }
    } catch (err) {
        console.error(err);
        showToast(cartI18n.networkError);
        input.value = prevQty;
    } finally {
        if (card) card.classList.remove('updating');
    }
}

// AJAX remove cart item in-place
async function removeCartItemAjax(cartId) {
    const card = document.getElementById('cart-item-' + cartId);
    if (card) card.classList.add('updating');

    try {
        const res = await fetch('/cart/' + cartId, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        });

        const data = await res.json();

        if (data.success) {
            if (card) {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.92)';
                card.style.maxHeight = card.offsetHeight + 'px';
                setTimeout(() => {
                    card.style.maxHeight = '0px';
                    card.style.padding = '0px';
                    card.style.margin = '0px';
                    card.style.overflow = 'hidden';
                    setTimeout(() => {
                        card.remove();
                        if (data.is_empty || data.count === 0) {
                            showEmptyCartState();
                        }
                    }, 300);
                }, 100);
            }

            // Update summary
            if (data.count !== undefined) {
                const headerCount = document.getElementById('cartHeaderCount');
                const summaryCount = document.getElementById('summaryCount');
                if (headerCount) headerCount.textContent = data.count;
                if (summaryCount) summaryCount.textContent = data.count;
            }

            if (data.total !== undefined) {
                const curCode = (window.AppCurrency && window.AppCurrency.current) || data.currency || 'MYR';
                const curSymbol = (window.AppCurrency && window.AppCurrency.symbol) || data.currency_symbol || 'RM';

                let displaySubtotal = '';
                let displayTotal = '';
                if (data.currency === curCode && data.currency_subtotal_formatted) {
                    displaySubtotal = data.currency_subtotal_formatted;
                    displayTotal = data.currency_total_formatted;
                } else {
                    const rate = (window.AppCurrency && window.AppCurrency.rates && window.AppCurrency.rates[curCode]) ? parseFloat(window.AppCurrency.rates[curCode]) : 1;
                    const convertedSubtotal = curCode === 'MYR' ? data.subtotal : (Math.round(data.subtotal * rate * 100) / 100);
                    const convertedTotal = curCode === 'MYR' ? data.total : (Math.round(data.total * rate * 100) / 100);
                    displaySubtotal = curSymbol + ' ' + convertedSubtotal.toFixed(2);
                    displayTotal = curSymbol + ' ' + convertedTotal.toFixed(2);
                }

                const summarySubtotal = document.getElementById('summarySubtotal');
                const summaryTotal = document.getElementById('summaryTotal');
                if (summarySubtotal) {
                    summarySubtotal.setAttribute('data-base-subtotal', data.subtotal);
                    if (curCode !== 'MYR') {
                        summarySubtotal.innerHTML = displaySubtotal + '<span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal">RM ' + parseFloat(data.subtotal).toFixed(2) + '</span>';
                    } else {
                        summarySubtotal.textContent = 'RM ' + parseFloat(data.subtotal).toFixed(2);
                    }
                }
                if (summaryTotal) {
                    summaryTotal.setAttribute('data-base-total', data.total);
                    if (curCode !== 'MYR') {
                        summaryTotal.innerHTML = displayTotal + '<span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal;margin-top:2px">Base: RM ' + parseFloat(data.total).toFixed(2) + '</span>';
                    } else {
                        summaryTotal.textContent = 'RM ' + parseFloat(data.total).toFixed(2);
                    }
                }
            }

            if (typeof updateCartCount === 'function') updateCartCount();

            showToast(cartI18n.itemRemoved);
        } else {
            showToast(data.message || cartI18n.couldNotRemove);
            if (card) card.classList.remove('updating');
        }
    } catch (err) {
        console.error(err);
        showToast(cartI18n.errorRemoving);
        if (card) card.classList.remove('updating');
    }
}

// Show empty state dynamically
function showEmptyCartState() {
    const content = document.getElementById('cartContentWrapper');
    const empty = document.getElementById('emptyCartContainer');
    const continueBtn = document.getElementById('continueShopBtn');
    const headerBar = document.getElementById('cartPageHeaderBar');
    if (content) content.style.display = 'none';
    if (continueBtn) continueBtn.style.display = 'none';
    if (headerBar) headerBar.style.display = 'none';
    if (empty) empty.style.display = 'block';
}

// ─── Custom Remove Confirmation Modal Logic ─────────────────────────────
let pendingRemoveCartId = null;

function confirmRemoveCartItem(cartId) {
    pendingRemoveCartId = cartId;
    const card = document.getElementById('cart-item-' + cartId);
    
    let thumbHtml = '🐟';
    let nameText = cartI18n.defaultProductName;
    let metaText = '';

    if (card) {
        const img = card.querySelector('.cart-item-thumb img');
        if (img) {
            thumbHtml = `<img src="${img.src}" alt="${img.alt || 'Product'}">`;
        }
        const nameEl = card.querySelector('.cart-item-name a') || card.querySelector('.cart-item-name');
        if (nameEl) {
            nameText = nameEl.textContent.trim();
        }
        const qtyInput = document.getElementById('qty-input-' + cartId);
        const qtyVal = qtyInput ? qtyInput.value : '1';
        const subtotalEl = document.getElementById('subtotal-' + cartId);
        const priceVal = subtotalEl ? subtotalEl.textContent.trim().split('\n')[0] : '';
        metaText = `${cartI18n.qtyLabel} ${qtyVal}` + (priceVal ? ` · ${cartI18n.subtotalLabel}: ${priceVal}` : '');
    }

    const thumbContainer = document.getElementById('removeModalThumb');
    const nameContainer = document.getElementById('removeModalName');
    const metaContainer = document.getElementById('removeModalMeta');
    const modal = document.getElementById('cartRemoveModal');

    if (thumbContainer) thumbContainer.innerHTML = thumbHtml;
    if (nameContainer) nameContainer.textContent = nameText;
    if (metaContainer) metaContainer.textContent = metaText;

    if (modal) {
        modal.style.display = 'flex';
        void modal.offsetWidth; // trigger reflow
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeRemoveModal() {
    pendingRemoveCartId = null;
    const modal = document.getElementById('cartRemoveModal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => {
            if (!modal.classList.contains('show')) {
                modal.style.display = 'none';
            }
        }, 220);
    }
    document.body.style.overflow = '';
}

function handleRemoveModalBackdropClick(event) {
    if (event.target === document.getElementById('cartRemoveModal')) {
        closeRemoveModal();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('cartRemoveModal')?.classList.contains('show')) {
        closeRemoveModal();
    }
});

async function executeRemoveCartItem() {
    if (!pendingRemoveCartId) return;
    const cartId = pendingRemoveCartId;
    const btn = document.getElementById('btnConfirmDelete');
    const btnText = document.getElementById('btnConfirmDeleteText');

    if (btn) btn.disabled = true;
    if (btnText) btnText.textContent = cartI18n.removing;

    try {
        await removeCartItemAjax(cartId);
    } finally {
        if (btn) btn.disabled = false;
        if (btnText) btnText.textContent = cartI18n.yesRemove;
        closeRemoveModal();
    }
}
</script>
@endpush
