@extends('layouts.app')
@section('title', 'Checkout — MST Import and Export Sdn Bhd')

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:4px">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 Home</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <a href="{{ route('cart.index') }}" style="color:#bae6fd;text-decoration:none">Shopping Cart</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">Checkout &amp; Payment</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        🔒 256-Bit Encrypted Secure Checkout
                    </span>
                    <span style="color:#bae6fd;font-size:0.78rem">Guaranteed Cold-Chain Dispatch</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,1.95rem);margin-bottom:4px;letter-spacing:-0.02em">
                    Checkout &amp; Payment
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.88rem;max-width:680px;line-height:1.4;margin:0">
                    Review your order items, confirm delivery address, and proceed to Stripe's encrypted payment gateway.
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                @if($group === 'wholesale')
                <div style="font-size:0.8rem;padding:5px 12px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb;font-weight:600">
                    🏢 Wholesale Partner Tier
                </div>
                @elseif($group === 'walkin')
                <div style="font-size:0.8rem;padding:5px 12px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb;font-weight:600">
                    🏪 In-Store Walk-in Express
                </div>
                @else
                <div style="font-size:0.8rem;padding:5px 12px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb;font-weight:600">
                    🛒 Retail Customer Order
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="checkout-page-wrapper">
    <div class="container checkout-container">

        {{-- Stepper Progress Bar --}}
        <div class="checkout-stepper-bar">
            <a href="{{ route('cart.index') }}" class="stepper-item step-completed">
                <div class="step-num">✓</div>
                <div class="step-label">
                    <span class="label-full">Shopping Cart</span>
                    <span class="label-short">Cart</span>
                </div>
            </a>
            <div class="stepper-divider"></div>
            <div class="stepper-item step-active">
                <div class="step-num">2</div>
                <div class="step-label">
                    <span class="label-full">Checkout &amp; Payment</span>
                    <span class="label-short">Payment</span>
                </div>
            </div>
            <div class="stepper-divider"></div>
            <div class="stepper-item step-disabled">
                <div class="step-num">3</div>
                <div class="step-label">
                    <span class="label-full">Order Confirmation</span>
                    <span class="label-short">Done</span>
                </div>
            </div>
        </div>

        {{-- Mobile Collapsible Order Summary Banner (< 992px) --}}
        <div class="mobile-order-summary-card d-lg-none" onclick="toggleMobileSummary()">
            <div class="mobile-summary-bar">
                <div class="mobile-summary-left">
                    <span class="mobile-summary-icon">🛍️</span>
                    <span class="mobile-summary-text">
                        <span id="mobileSummaryText">Show Order Summary</span>
                        <span class="mobile-summary-count">({{ $items->count() }} {{ Str::plural('item', $items->count()) }})</span>
                    </span>
                    <span id="mobileSummaryChevron" class="mobile-summary-chevron">▼</span>
                </div>
                <div class="mobile-summary-right">
                    @if($currentCurrency !== 'MYR')
                        {{ $currencySymbol }} {{ number_format($currencyService->convert($totals['total'], $currentCurrency), 2) }}
                    @else
                        RM {{ number_format($totals['total'], 2) }}
                    @endif
                </div>
            </div>

            {{-- Collapsible Content --}}
            <div id="mobileSummaryCollapse" class="mobile-summary-collapse" style="display:none" onclick="event.stopPropagation()">
                <div class="mobile-summary-items">
                    @foreach($items as $item)
                        @php $price = $item->product?->getPriceForGroup($item->customer_group) ?? 0; @endphp
                        <div class="mobile-summary-item-row">
                            <div class="mobile-item-thumb">
                                @if($item->product?->image)
                                    <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product->name }}">
                                @else
                                    <span class="thumb-emoji">🦐</span>
                                @endif
                                <span class="qty-badge">{{ $item->quantity }}</span>
                            </div>
                            <div class="mobile-item-details">
                                <div class="mobile-item-name">{{ $item->product?->name }}</div>
                                <div class="mobile-item-meta">{{ $item->product?->sku ?? 'SEA-ITEM' }}</div>
                            </div>
                            <div class="mobile-item-price">
                                @if($currentCurrency !== 'MYR')
                                    {{ $currencySymbol }} {{ number_format($currencyService->convert($price * $item->quantity, $currentCurrency), 2) }}
                                    <span style="font-size:0.75rem;color:#64748b;display:block">RM {{ number_format($price * $item->quantity, 2) }}</span>
                                @else
                                    RM {{ number_format($price * $item->quantity, 2) }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mobile-summary-totals">
                    <div class="summary-line">
                        <span>Subtotal</span>
                        <span>
                            @if($currentCurrency !== 'MYR')
                                {{ $currencySymbol }} {{ number_format($currencyService->convert($totals['subtotal'], $currentCurrency), 2) }}
                                <span style="font-size:0.75rem;color:#64748b;display:block">RM {{ number_format($totals['subtotal'], 2) }}</span>
                            @else
                                RM {{ number_format($totals['subtotal'], 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="summary-line">
                        <span>Shipping &amp; Cold-Chain</span>
                        <span class="val-green" id="mobileShippingDisplay">Free (Self-collection)</span>
                    </div>
                    <div class="summary-line summary-grand-total">
                        <span class="total-label">Grand Total</span>
                        <span class="total-val">
                            @if($currentCurrency !== 'MYR')
                                {{ $currencySymbol }} {{ number_format($currencyService->convert($totals['total'], $currentCurrency), 2) }}
                                <span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal">Base: RM {{ number_format($totals['total'], 2) }}</span>
                            @else
                                RM {{ number_format($totals['total'], 2) }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
            @csrf

            <div class="checkout-main-grid">

                <!-- Left Column: Order Forms -->
                <div class="checkout-form-column">

                    <!-- Fulfillment Method Selection -->
                    <div class="card checkout-card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-title-icon">📦</span>
                                <span>1. Select Fulfillment Method</span>
                            </div>
                        </div>

                        <div class="fulfillment-options-grid">
                            @if($group !== 'walkin')
                            <label class="fulfillment-tile {{ old('fulfillment_type','delivery')=='delivery'?'selected':'' }}" id="label_delivery" for="delivery">
                                <input type="radio" name="fulfillment_type" id="delivery" value="delivery"
                                       {{ old('fulfillment_type','delivery')=='delivery'?'checked':'' }}
                                       onchange="onFulfillment('delivery')">
                                <div class="tile-check-indicator">✓</div>
                                <div class="tile-icon">🚚</div>
                                <div class="tile-content">
                                    <div class="tile-title">Cold-Chain Delivery</div>
                                    <div class="tile-desc">Direct refrigerated delivery across Klang Valley and West Malaysia.</div>
                                    <div class="tile-badge badge-blue">Refrigerated Logistics</div>
                                </div>
                            </label>
                            @endif

                            <label class="fulfillment-tile {{ ($group==='walkin'||old('fulfillment_type')=='self_collection')?'selected':'' }}" id="label_self_collection" for="self_collection">
                                <input type="radio" name="fulfillment_type" id="self_collection" value="self_collection"
                                       {{ ($group==='walkin'||old('fulfillment_type')=='self_collection')?'checked':'' }}
                                       onchange="onFulfillment('self_collection')">
                                <div class="tile-check-indicator">✓</div>
                                <div class="tile-icon">🏪</div>
                                <div class="tile-content">
                                    <div class="tile-title">Store Self-Collection</div>
                                    <div class="tile-desc">Collect directly at our SILC Cold-Chain facility in Iskandar Puteri, Johor Bahru.</div>
                                    <div class="tile-badge badge-green">Free Pickup</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Walk-in Customer Details -->
                    @if($group === 'walkin')
                    <div class="card checkout-card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-title-icon">👤</span>
                                <span>2. Customer Information</span>
                            </div>
                        </div>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Full Name <span class="required">*</span></label>
                                <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" placeholder="e.g. John Tan" required>
                                @error('customer_name')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number <span class="required">*</span></label>
                                <input type="tel" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}" placeholder="e.g. 012-345 6789" required>
                                @error('customer_phone')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Delivery Address Card -->
                    @if($group !== 'walkin')
                    <div class="card checkout-card" id="addressCard">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-title-icon">📍</span>
                                <span>2. Delivery Address</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Street Address <span class="required">*</span></label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', auth()->user()?->address) }}" placeholder="Unit / House No, Street, Taman...">
                            @error('address')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="address-grid-responsive">
                            <div class="form-group">
                                <label class="form-label">City <span class="required">*</span></label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', auth()->user()?->city) }}" placeholder="e.g. Kuala Lumpur / JB">
                                @error('city')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">State <span class="required">*</span></label>
                                <input type="text" name="state" class="form-control" value="{{ old('state', auth()->user()?->state ?? 'Selangor') }}" placeholder="e.g. Selangor">
                                @error('state')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Postcode <span class="required">*</span></label>
                                <input type="text" name="postcode" class="form-control" value="{{ old('postcode', auth()->user()?->postcode) }}" placeholder="68100" maxlength="5">
                                @error('postcode')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Order Notes Card -->
                    <div class="card checkout-card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-title-icon">📝</span>
                                <span>3. Special Instructions &amp; Notes</span>
                            </div>
                        </div>
                        <textarea name="customer_notes" class="form-control" rows="3" placeholder="Add specific delivery timing, packing instructions, or gate codes (optional)...">{{ old('customer_notes') }}</textarea>
                    </div>

                    <!-- Payment Method Card -->
                    <div class="card checkout-card">
                        <div class="card-header flex-wrap-mobile">
                            <div class="card-title">
                                <span class="card-title-icon">💳</span>
                                <span>4. Payment via Stripe</span>
                            </div>
                            <div class="payment-shield-badge">
                                🔒 256-bit SSL Encrypted
                            </div>
                        </div>

                        <div class="stripe-secure-banner">
                            <div class="banner-icon">🛡️</div>
                            <div class="banner-text">
                                <strong>Stripe Official Hosted Checkout:</strong> When you click below, you will be securely redirected to Stripe's payment page (<code>checkout.stripe.com</code>) to complete your card or online banking payment.
                            </div>
                        </div>

                        <div class="stripe-hosted-box">
                            <div class="stripe-hosted-top">
                                <div class="stripe-gateway-brand">
                                    <span class="stripe-logo-icon">💳</span>
                                    <div>
                                        <div class="stripe-brand-title">Stripe Official Checkout</div>
                                        <div class="stripe-brand-subtitle">Credit / Debit Card, Apple Pay &amp; FPX</div>
                                    </div>
                                </div>
                                <span class="stripe-badge-pill">Secure Gateway</span>
                            </div>

                            <div class="stripe-payment-methods-grid">
                                <span class="pay-method-chip">💳 Visa</span>
                                <span class="pay-method-chip">💳 Mastercard</span>
                                <span class="pay-method-chip">🍎 Apple Pay</span>
                                <span class="pay-method-chip">🌐 Google Pay</span>
                                <span class="pay-method-chip">🏦 FPX Online Banking</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Order Summary (Desktop Sticky) -->
                <div class="checkout-summary-column">
                    <div class="card checkout-summary-card">
                        <div class="card-header summary-header">
                            <div class="card-title">Order Summary</div>
                            <span class="summary-count-badge">{{ $items->count() }} {{ Str::plural('item', $items->count()) }}</span>
                        </div>

                        <div class="summary-items-scroll">
                            @foreach($items as $item)
                                @php $price = $item->product?->getPriceForGroup($item->customer_group) ?? 0; @endphp
                                <div class="summary-item-row">
                                    <div class="summary-item-left">
                                        <div class="summary-item-thumb">
                                            @if($item->product?->image)
                                                <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product->name }}">
                                            @else
                                                <span class="thumb-emoji">🦐</span>
                                            @endif
                                            <span class="qty-badge">{{ $item->quantity }}</span>
                                        </div>
                                        <div class="summary-item-details">
                                            <div class="summary-item-name">{{ $item->product?->name }}</div>
                                            <div class="summary-item-meta">{{ $item->product?->sku ?? 'SEA-ITEM' }}</div>
                                        </div>
                                    </div>
                                    <div class="summary-item-price">
                                        @if($currentCurrency !== 'MYR')
                                            {{ $currencySymbol }} {{ number_format($currencyService->convert($price * $item->quantity, $currentCurrency), 2) }}
                                            <span style="font-size:0.75rem;color:#64748b;display:block">RM {{ number_format($price * $item->quantity, 2) }}</span>
                                        @else
                                            RM {{ number_format($price * $item->quantity, 2) }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="summary-totals-box">
                            <div class="summary-line">
                                <span class="line-label">Subtotal</span>
                                <span class="line-val">
                                    @if($currentCurrency !== 'MYR')
                                        {{ $currencySymbol }} {{ number_format($currencyService->convert($totals['subtotal'], $currentCurrency), 2) }}
                                        <span style="font-size:0.75rem;color:#64748b;display:block">RM {{ number_format($totals['subtotal'], 2) }}</span>
                                    @else
                                        RM {{ number_format($totals['subtotal'], 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="summary-line">
                                <span class="line-label">Shipping &amp; Logistics</span>
                                <span class="line-val val-green" id="shippingDisplay">Free (Self-collection)</span>
                            </div>
                            <div class="summary-line summary-grand-total">
                                <span class="total-label">Grand Total</span>
                                <span class="total-val">
                                    @if($currentCurrency !== 'MYR')
                                        {{ $currencySymbol }} {{ number_format($currencyService->convert($totals['total'], $currentCurrency), 2) }}
                                        <span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal">Base: RM {{ number_format($totals['total'], 2) }}</span>
                                    @else
                                        RM {{ number_format($totals['total'], 2) }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="checkout-submit-btn" id="submitBtn">
                            <span class="btn-main-text">🔒 Proceed to checkout</span>
                            <span class="btn-amount-badge">
                                @if($currentCurrency !== 'MYR')
                                    {{ $currencySymbol }} {{ number_format($currencyService->convert($totals['total'], $currentCurrency), 2) }}
                                @else
                                    RM {{ number_format($totals['total'], 2) }}
                                @endif
                            </span>
                        </button>

                        <div class="checkout-trust-badges">
                            <div class="trust-item">
                                <span class="trust-icon">🔒</span>
                                <span>SSL Encrypted</span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">❄️</span>
                                <span>Cold-Chain</span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">⚡</span>
                                <span>Instant Confirm</span>
                            </div>
                        </div>

                        @if($currentCurrency !== 'MYR')
                            <div style="font-size:0.75rem;color:#64748b;margin:10px 0 12px;background:#f8fafc;padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;line-height:1.4">
                                ℹ️ Prices displayed in <strong>{{ $currentCurrency }}</strong> for reference. Final payment will be processed in <strong>MYR {{ number_format($totals['total'], 2) }}</strong> at checkout.
                            </div>
                        @endif

                        <p class="checkout-terms-note">
                            By clicking proceed, you will be redirected to Stripe to securely finalize your payment.
                        </p>
                    </div>
                </div>

            </div>
        </form>

    </div>

    {{-- Floating Sticky Mobile Pay Bar (Visible <= 768px) --}}
    <div class="mobile-sticky-footer-bar d-lg-none">
        <div class="mobile-footer-inner">
            <div class="mobile-footer-price-col">
                <span class="mobile-footer-label">Grand Total</span>
                <span class="mobile-footer-amount">
                    @if($currentCurrency !== 'MYR')
                        {{ $currencySymbol }} {{ number_format($currencyService->convert($totals['total'], $currentCurrency), 2) }}
                    @else
                        RM {{ number_format($totals['total'], 2) }}
                    @endif
                </span>
            </div>
            <button type="button" onclick="submitCheckoutForm()" class="mobile-footer-pay-btn" id="mobilePayBtn">
                <span>🔒 Proceed to checkout</span>
            </button>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
/* ===== CHECKOUT PAGE PREMIUM RESPONSIVE DESIGN SYSTEM ===== */

.checkout-page-wrapper {
    padding-top: 2rem; /* var(--space-8) */
    padding-bottom: 4rem; /* var(--space-16) */
    background: #f8fafc;
    min-height: auto;
}

.checkout-container {
    max-width: 1240px;
    margin: 0 auto;
    padding-left: clamp(12px, 3vw, 24px);
    padding-right: clamp(12px, 3vw, 24px);
}

/* Stepper Progress Bar */
.checkout-stepper-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: clamp(8px, 2vw, 16px);
    margin-bottom: 28px;
    flex-wrap: nowrap;
    overflow-x: auto;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
    padding: 4px 2px;
}
.checkout-stepper-bar::-webkit-scrollbar { display: none; }

.stepper-item {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
}

.stepper-item.step-completed { color: #059669; }
.stepper-item.step-active { color: #2563eb; }
.stepper-item.step-disabled { color: #94a3b8; }

.step-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 800;
    flex-shrink: 0;
}

.step-completed .step-num { background: #dcfce7; color: #059669; border: 2px solid #86efac; }
.step-active .step-num { background: #2563eb; color: #ffffff; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2); }
.step-disabled .step-num { background: #e2e8f0; color: #64748b; }

.stepper-divider {
    width: clamp(14px, 3vw, 36px);
    height: 2px;
    background: #cbd5e1;
    flex-shrink: 0;
}

.label-short { display: none; }
.label-full { display: inline; }

/* Header Box */
.checkout-header-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 24px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e2e8f0;
}

.checkout-page-title {
    font-size: clamp(1.4rem, 2.5vw, 1.85rem);
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 4px 0;
    letter-spacing: -0.02em;
    line-height: 1.25;
}

.checkout-page-sub {
    font-size: clamp(0.82rem, 1.5vw, 0.9rem);
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}

.tier-badge-pill {
    font-size: 0.8rem;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.tier-wholesale { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.tier-walkin { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
.tier-retail { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }

/* Mobile Order Summary Accordion Card */
.mobile-order-summary-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}

.mobile-summary-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    background: #f8fafc;
    cursor: pointer;
    user-select: none;
}

.mobile-summary-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.88rem;
    font-weight: 700;
    color: #1e293b;
}

.mobile-summary-icon { font-size: 1.1rem; }
.mobile-summary-count { color: #64748b; font-weight: 500; font-size: 0.8rem; }
.mobile-summary-chevron { font-size: 0.75rem; color: #2563eb; transition: transform 0.25s ease; }
.mobile-summary-chevron.open { transform: rotate(180deg); }

.mobile-summary-right {
    font-size: 1rem;
    font-weight: 800;
    color: #2563eb;
}

.mobile-summary-collapse {
    border-top: 1px solid #e2e8f0;
    padding: 16px;
    background: #ffffff;
}

.mobile-summary-items {
    max-height: 240px;
    overflow-y: auto;
    margin-bottom: 14px;
}

.mobile-summary-item-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    border-bottom: 1px dashed #f1f5f9;
}

.mobile-item-thumb {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #f1f5f9;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.mobile-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
.mobile-item-details { flex: 1; min-width: 0; }
.mobile-item-name { font-size: 0.82rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.mobile-item-meta { font-size: 0.72rem; color: #94a3b8; }
.mobile-item-price { font-size: 0.85rem; font-weight: 800; color: #059669; }

/* Grid Layout */
.checkout-main-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px;
    align-items: start;
}

.checkout-form-column {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.checkout-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: clamp(16px, 3vw, 24px);
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}

.checkout-card .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 14px;
    margin-bottom: 18px;
    border-bottom: 1px solid #f1f5f9;
    gap: 10px;
}

.checkout-card .card-title {
    font-size: clamp(0.98rem, 1.8vw, 1.1rem);
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}

.card-title-icon { font-size: 1.15rem; }

/* Fulfillment Tiles */
.fulfillment-options-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}

.fulfillment-tile {
    position: relative;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px;
    cursor: pointer;
    background: #ffffff;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.fulfillment-tile:hover {
    border-color: #93c5fd;
    background: #f8fafc;
}

.fulfillment-tile.selected {
    border-color: #2563eb;
    background: #eff6ff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
}

.fulfillment-tile input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.tile-check-indicator {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #cbd5e1;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    font-weight: 800;
    color: transparent;
    transition: all 0.15s ease;
}

.fulfillment-tile.selected .tile-check-indicator {
    border-color: #2563eb;
    background: #2563eb;
    color: #ffffff;
}

.tile-icon {
    font-size: 1.75rem;
    line-height: 1;
    flex-shrink: 0;
}

.tile-content { flex: 1; min-width: 0; }
.tile-title { font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 3px; }
.tile-desc { font-size: 0.78rem; color: #64748b; line-height: 1.35; margin-bottom: 8px; }

.tile-badge {
    display: inline-block;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
}
.badge-blue { background: #dbeafe; color: #1e40af; }
.badge-green { background: #dcfce7; color: #15803d; }

/* Responsive Address Grid */
.address-grid-responsive {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 14px;
}

.form-group {
    margin-bottom: 14px;
}

.form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}

.form-control {
    width: 100%;
    height: 44px;
    font-size: 16px; /* Prevents auto-zoom in iOS Safari */
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 0 14px;
    color: #0f172a;
    background: #ffffff;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    box-sizing: border-box;
}

textarea.form-control {
    height: auto;
    padding: 10px 14px;
    min-height: 80px;
    line-height: 1.5;
}

.form-control:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

/* Payment Section Styling */
.payment-shield-badge {
    font-size: 0.74rem;
    font-weight: 700;
    color: #059669;
    background: #ecfdf5;
    padding: 4px 10px;
    border-radius: 20px;
    border: 1px solid #a7f3d0;
    white-space: nowrap;
}

.stripe-secure-banner {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 18px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.banner-icon { font-size: 1.25rem; flex-shrink: 0; line-height: 1; }
.banner-text { font-size: 0.82rem; color: #1e40af; line-height: 1.45; }
.banner-text code { background: #dbeafe; padding: 1px 4px; border-radius: 4px; font-weight: 700; }

.stripe-hosted-box {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #ffffff;
    padding: 18px;
    border-radius: 14px;
    border: 1px solid #334155;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.stripe-hosted-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}

.stripe-gateway-brand {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stripe-logo-icon { font-size: 1.5rem; line-height: 1; }
.stripe-brand-title { font-weight: 800; font-size: 0.96rem; color: #f8fafc; }
.stripe-brand-subtitle { font-size: 0.78rem; color: #94a3b8; }

.stripe-badge-pill {
    background: rgba(37, 99, 235, 0.3);
    color: #93c5fd;
    border: 1px solid #3b82f6;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
}

.stripe-payment-methods-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding-top: 8px;
    border-top: 1px solid #334155;
}

.pay-method-chip {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #f1f5f9;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.76rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Order Summary Column */
.checkout-summary-column {
    position: sticky;
    top: 96px;
}

.checkout-summary-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: clamp(16px, 3vw, 24px);
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
}

.summary-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 14px;
    margin-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
}

.summary-header .card-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}

.summary-count-badge {
    background: #f1f5f9;
    color: #475569;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
}

.summary-items-scroll {
    max-height: 260px;
    overflow-y: auto;
    margin-bottom: 18px;
    padding-right: 2px;
}

.summary-item-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px dashed #f1f5f9;
}

.summary-item-left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
}

.summary-item-thumb {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    flex-shrink: 0;
    overflow: hidden;
}

.summary-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
.thumb-emoji { font-size: 1.15rem; }

.qty-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #0f172a;
    color: #ffffff;
    font-size: 0.65rem;
    font-weight: 800;
    width: 17px;
    height: 17px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.summary-item-details { flex: 1; min-width: 0; }
.summary-item-name {
    font-size: 0.84rem;
    font-weight: 700;
    color: #0f172a;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.summary-item-meta { font-size: 0.7rem; color: #94a3b8; margin-top: 1px; }
.summary-item-price { font-size: 0.88rem; font-weight: 800; color: #059669; flex-shrink: 0; }

.summary-totals-box {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 18px;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.86rem;
    color: #475569;
    margin-bottom: 8px;
}

.val-green { color: #059669; font-weight: 700; }

.summary-grand-total {
    margin-bottom: 0;
    padding-top: 10px;
    border-top: 1px solid #e2e8f0;
    font-size: 1.05rem;
}

.total-label { font-weight: 800; color: #0f172a; }
.total-val { font-weight: 900; color: #2563eb; font-size: 1.25rem; }

/* Enhanced Submit Button with Amount Pill */
.checkout-submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border: none;
    color: #ffffff;
    font-weight: 700;
    padding: 13px 18px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    box-sizing: border-box;
    font-family: inherit;
    text-decoration: none;
}

.checkout-submit-btn:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
}

.checkout-submit-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
}

.checkout-submit-btn:disabled {
    opacity: 0.75;
    cursor: not-allowed;
    transform: none;
}

.btn-main-text {
    font-size: clamp(0.85rem, 1.4vw, 0.96rem);
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.btn-amount-badge {
    background: rgba(255, 255, 255, 0.22);
    border: 1px solid rgba(255, 255, 255, 0.28);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: clamp(0.82rem, 1.3vw, 0.9rem);
    font-weight: 800;
    white-space: nowrap;
    letter-spacing: 0.2px;
}

.checkout-trust-badges {
    display: flex;
    align-items: center;
    justify-content: space-around;
    gap: 6px;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.7rem;
    font-weight: 700;
    color: #64748b;
    white-space: nowrap;
}

.checkout-terms-note {
    font-size: 0.72rem;
    color: #94a3b8;
    text-align: center;
    margin: 12px 0 0 0;
    line-height: 1.4;
}

/* Floating Sticky Mobile Bottom Checkout Bar */
.mobile-sticky-footer-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-top: 1px solid #e2e8f0;
    padding: 10px 16px max(10px, env(safe-area-inset-bottom));
    z-index: 999;
    box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.08);
}

.mobile-footer-inner {
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.mobile-footer-price-col {
    display: flex;
    flex-direction: column;
}

.mobile-footer-label {
    font-size: 0.72rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
}

.mobile-footer-amount {
    font-size: 1.2rem;
    font-weight: 900;
    color: #2563eb;
    line-height: 1.1;
}

.mobile-footer-pay-btn {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border: none;
    color: white;
    font-weight: 700;
    font-size: clamp(0.82rem, 3.2vw, 0.92rem);
    padding: clamp(10px, 2.5vw, 12px) clamp(12px, 3.2vw, 18px);
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-shrink: 0;
    transition: all 0.2s ease;
    font-family: inherit;
}

.mobile-footer-pay-btn:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
}

.mobile-footer-pay-btn:active {
    transform: scale(0.98);
}

.mobile-footer-pay-btn:disabled {
    opacity: 0.75;
    cursor: not-allowed;
}

/* ===== RESPONSIVE MEDIA QUERIES ===== */

@media (min-width: 993px) {
    .d-lg-none {
        display: none !important;
    }
}

@media (max-width: 992px) {
    .checkout-main-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .checkout-summary-column {
        position: static;
    }
}

@media (max-width: 640px) {
    .checkout-page-wrapper {
        padding-top: calc(60px + 14px);
        padding-bottom: 110px; /* Room for mobile sticky bottom footer */
    }

    .label-full { display: none; }
    .label-short { display: inline; }

    .checkout-stepper-bar {
        gap: 6px;
        margin-bottom: 20px;
    }

    .stepper-item {
        font-size: 0.78rem;
        gap: 5px;
    }

    .step-num {
        width: 24px;
        height: 24px;
        font-size: 0.72rem;
    }

    .stepper-divider {
        width: 14px;
    }

    .checkout-header-box {
        margin-bottom: 16px;
        padding-bottom: 14px;
    }

    .fulfillment-options-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .address-grid-responsive {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .flex-wrap-mobile {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 8px;
    }

    .checkout-card {
        border-radius: 14px;
    }

    .checkout-submit-btn {
        padding: 12px 14px;
    }

    .checkout-trust-badges {
        flex-wrap: wrap;
        gap: 10px;
    }
}

@media (max-width: 420px) {
    .checkout-submit-btn {
        flex-wrap: wrap;
        justify-content: center;
        text-align: center;
        padding: 12px 10px;
        gap: 6px;
    }

    .btn-main-text {
        font-size: 0.88rem;
        justify-content: center;
    }

    .btn-amount-badge {
        font-size: 0.82rem;
        padding: 3px 8px;
    }

    .mobile-sticky-footer-bar {
        padding: 10px 12px max(10px, env(safe-area-inset-bottom));
    }

    .mobile-footer-inner {
        gap: 8px;
    }

    .mobile-footer-amount {
        font-size: 1.1rem;
    }

    .mobile-footer-pay-btn {
        padding: 10px 12px;
        font-size: 0.82rem;
        gap: 4px;
    }
}

@media (max-width: 350px) {
    .mobile-footer-inner {
        gap: 6px;
    }

    .mobile-footer-label {
        font-size: 0.65rem;
    }

    .mobile-footer-amount {
        font-size: 1rem;
    }

    .mobile-footer-pay-btn {
        padding: 8px 10px;
        font-size: 0.78rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function onFulfillment(type) {
    document.querySelectorAll('.fulfillment-tile').forEach(l => l.classList.remove('selected'));
    const labelEl = document.getElementById('label_' + type);
    if (labelEl) labelEl.classList.add('selected');

    const addressCard = document.getElementById('addressCard');
    if (addressCard) {
        addressCard.style.display = type === 'delivery' ? 'block' : 'none';
    }

    const shipText = type === 'delivery' ? 'Calculated by admin' : 'Free (Self-collection)';
    const desktopShip = document.getElementById('shippingDisplay');
    if (desktopShip) desktopShip.textContent = shipText;

    const mobileShip = document.getElementById('mobileShippingDisplay');
    if (mobileShip) mobileShip.textContent = shipText;
}

function toggleMobileSummary() {
    const box = document.getElementById('mobileSummaryCollapse');
    const chevron = document.getElementById('mobileSummaryChevron');
    const text = document.getElementById('mobileSummaryText');
    if (!box) return;

    if (box.style.display === 'none' || box.style.display === '') {
        box.style.display = 'block';
        if (chevron) chevron.classList.add('open');
        if (text) text.textContent = 'Hide Order Summary';
    } else {
        box.style.display = 'none';
        if (chevron) chevron.classList.remove('open');
        if (text) text.textContent = 'Show Order Summary';
    }
}

function submitCheckoutForm() {
    const form = document.getElementById('checkoutForm');
    if (!form) return;

    // Check HTML5 validity
    if (!form.reportValidity()) {
        // Scroll to first invalid input
        const firstInvalid = form.querySelector(':invalid');
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
        return;
    }

    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="btn-main-text">⏳ Proceeding to checkout...</span>';
    }

    const mobileBtn = document.getElementById('mobilePayBtn');
    if (mobileBtn) {
        mobileBtn.disabled = true;
        mobileBtn.innerHTML = '<span>⏳ Proceeding...</span>';
    }

    form.submit();
}

document.getElementById('checkoutForm').addEventListener('submit', function (e) {
    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="btn-main-text">⏳ Proceeding to checkout...</span>';
    }

    const mobileBtn = document.getElementById('mobilePayBtn');
    if (mobileBtn) {
        mobileBtn.disabled = true;
        mobileBtn.innerHTML = '<span>⏳ Proceeding...</span>';
    }
});
</script>
@endpush
