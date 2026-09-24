@extends('layouts.app')
@section('title', __t('walkin.checkout_title', 'Walk-in Express Checkout') . ' — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd'))

@section('content')
<!-- Walk-in Ocean Header Banner & Process Stepper -->
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
                <span class="breadcrumb-current">@t('walkin.checkout_step', 'Express Checkout')</span>
            </div>
            
            <div style="display:flex;align-items:center;gap:10px">
                <span class="walkin-live-badge">
                    <span class="pulse-dot"></span>
                    @t('walkin.menu_subtitle', 'In-Store Express Menu')
                </span>
                <a href="{{ route('walkin.shop') }}" class="btn-walkin-back">
                    ← @t('walkin.back_to_menu', 'Back to Walk-in Menu')
                </a>
            </div>
        </div>

        <div class="walkin-hero-heading-box">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap">
                <span class="walkin-store-tag" style="background:rgba(30,58,138,0.6);border:1px solid rgba(56,189,248,0.35);padding:3px 10px;border-radius:8px;font-size:0.75rem;font-weight:700;color:#e0f2fe">
                    🏬 @t('walkin.store_location', 'MST Counter 2 · SILC Industrial Park, Iskandar Puteri')
                </span>
                <span class="walkin-tag-sub">⚡ @t('walkin.express_pickup_tag', 'Express Counter 2 Collection')</span>
            </div>
            <h1 class="walkin-hero-title">
                @t('walkin.checkout_title', 'Walk-in Express Checkout')
            </h1>
            <p class="walkin-hero-subtitle">
                @t('walkin.checkout_subtitle', 'Please confirm your order and collection location before payment. Collect your packed order at MST Counter 2.')
            </p>
        </div>

        <!-- 4-Step Interactive Process Flow (Step 3 Active) -->
        <div class="walkin-stepper-wrap">
            <div class="walkin-stepper">
                <a href="{{ route('walkin.shop') }}" class="step-item step-completed" style="text-decoration:none">
                    <div class="step-icon">1</div>
                    <div class="step-info">
                        <span class="step-num">Step 1 — @t('walkin.step_1_name', 'Browse')</span>
                        <span class="step-label">@t('walkin.step_1_desc', 'View available products on your phone.')</span>
                    </div>
                </a>
                <div class="step-divider active"></div>

                <a href="{{ route('walkin.shop') }}" class="step-item step-completed" style="text-decoration:none">
                    <div class="step-icon">2</div>
                    <div class="step-info">
                        <span class="step-num">Step 2 — @t('walkin.step_2_name', 'Select')</span>
                        <span class="step-label">@t('walkin.step_2_desc', 'Choose your products and quantities.')</span>
                    </div>
                </a>
                <div class="step-divider active"></div>

                <div class="step-item step-active">
                    <div class="step-icon">3</div>
                    <div class="step-info">
                        <span class="step-num">Step 3 — @t('walkin.step_3_name', 'Pay')</span>
                        <span class="step-label">@t('walkin.step_3_desc', 'Complete payment securely on your phone.')</span>
                    </div>
                </div>
                <div class="step-divider"></div>

                <div class="step-item">
                    <div class="step-icon">4</div>
                    <div class="step-info">
                        <span class="step-num">Step 4 — @t('walkin.step_4_name', 'Collect')</span>
                        <span class="step-label">@t('walkin.step_4_desc', 'Collect your packed order at Counter 2.')</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top:var(--space-8);padding-bottom:var(--space-16)">

    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
        @csrf
        <input type="hidden" name="fulfillment_type" value="self_collection">

        @if(session('error'))
            <div class="alert alert-danger" style="background:#fee2e2;border:1px solid #ef4444;color:#991b1b;padding:14px 18px;border-radius:12px;margin-bottom:20px;display:flex;align-items:center;gap:12px">
                <span style="font-size:1.2rem">⚠️</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Mobile Collapsible Order Summary Banner (< 992px) - OPEN BY DEFAULT -->
        <div class="mobile-order-summary-card d-lg-none" onclick="toggleMobileSummary()">
            <div class="mobile-summary-bar">
                <div class="mobile-summary-left">
                    <span class="mobile-summary-icon">🛍️</span>
                    <span class="mobile-summary-title">
                        <span id="mobileSummaryText">@t('walkin.hide_order_summary', 'Hide Order Summary')</span>
                        <span class="mobile-summary-count-badge">({{ $items->count() }})</span>
                    </span>
                    <span id="mobileSummaryChevron" class="mobile-summary-chevron open">▼</span>
                </div>
                <div class="mobile-summary-right">
                    RM {{ number_format($totals['total'], 2) }}
                </div>
            </div>

            <!-- Collapsible Content (Open by default) -->
            <div id="mobileSummaryCollapse" class="mobile-summary-collapse" onclick="event.stopPropagation()">
                <div class="mobile-summary-items">
                    @foreach($items as $item)
                        @php $price = $item->product?->walkin_price ?? $item->product?->retail_price ?? 0; @endphp
                        <div class="mobile-summary-item-row">
                            <div class="mobile-item-thumb">
                                @if($item->product?->thumbnail)
                                    <img src="{{ cdn_storage($item->product->thumbnail) }}" alt="{{ $item->product->name }}">
                                @else
                                    <span class="thumb-emoji">🐟</span>
                                @endif
                                <span class="qty-badge">{{ $item->quantity }}</span>
                            </div>
                            <div class="mobile-item-details">
                                <div class="mobile-item-name">{{ $item->product?->name }}</div>
                                <div class="mobile-item-meta">{{ $item->product?->sku ?? 'ITEM' }} · {{ $item->quantity }} × RM {{ number_format($price, 2) }}</div>
                            </div>
                            <div class="mobile-item-price">
                                RM {{ number_format($price * $item->quantity, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mobile-summary-totals">
                    <div class="summary-line">
                        <span>@t('walkin.subtotal', 'Subtotal')</span>
                        <span class="summary-val-dark">RM {{ number_format($totals['subtotal'], 2) }}</span>
                    </div>
                    <div class="summary-line">
                        <span>@t('walkin.fulfillment', 'Fulfillment')</span>
                        <span class="summary-val-free">✓ @t('walkin.counter_2_pickup', 'MST Counter 2 Collection (FREE)')</span>
                    </div>
                    
                    <div class="mobile-summary-grand-box">
                        <span class="grand-label">@t('walkin.total_to_pay', 'Total to Pay')</span>
                        <span class="grand-amount">RM {{ number_format($totals['total'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="walkin-checkout-grid">

            <!-- Left: Customer Info & Payment -->
            <div class="walkin-checkout-main">
                <!-- Store Counter Pickup Notice -->
                <div class="walkin-pickup-notice-card" style="background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:14px;padding:16px 20px;display:flex;gap:14px;align-items:flex-start;margin-bottom:20px">
                    <div class="notice-icon" style="font-size:1.8rem">🏬</div>
                    <div>
                        <div class="notice-title" style="font-weight:800;font-size:0.95rem;color:#1e3a8a;margin-bottom:4px">
                            @t('walkin.confirm_pickup_title', 'Please confirm your order and collection location before payment.')
                        </div>
                        <div class="notice-desc" style="font-size:0.85rem;color:#1d4ed8;line-height:1.45;margin-bottom:8px">
                            📍 <strong>@t('walkin.store_location', 'MST Counter 2 · SILC Industrial Park, Iskandar Puteri')</strong><br>
                            @t('walkin.counter_desc', 'Orders are prepared for Counter 2 collection after payment confirmation. Orders are packed appropriately for collection and transport.')
                        </div>
                        <div class="notice-badge" style="font-size:0.75rem;font-weight:700;color:#1e40af;background:#ffffff;padding:3px 10px;border-radius:6px;display:inline-block;border:1px solid #bfdbfe">
                            📋 @t('walkin.counter_note', 'Please present your order reference / payment confirmation when collecting your order.')
                        </div>
                    </div>
                </div>

                <!-- Customer Details Card -->
                <div class="walkin-form-card">
                    <div class="form-card-header">
                        <h3 class="form-card-title">
                            <span class="title-icon">👤</span>
                            <span>@t('walkin.who_is_collecting', '1. Customer Details (Who is Collecting?)')</span>
                        </h3>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">@t('walkin.your_name', 'Full Name') <span class="required" style="color:#ef4444">*</span></label>
                            <input type="text" name="customer_name" class="form-control walkin-input" 
                                   placeholder="e.g. John Tan" value="{{ old('customer_name', auth()->user()?->name) }}" required>
                            @error('customer_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">@t('walkin.mobile_phone', 'Mobile Phone') <span class="required" style="color:#ef4444">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control walkin-input" 
                                   placeholder="e.g. 012-3456789" value="{{ old('customer_phone', auth()->user()?->phone) }}" required>
                            @error('customer_phone')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label" style="color:#64748b">@t('walkin.email_optional', 'Email for PDF e-receipt (Optional)')</label>
                        <input type="email" name="customer_email" class="form-control walkin-input" 
                                placeholder="e.g. john@example.com" value="{{ old('customer_email', auth()->user()?->email) }}">
                    </div>
                </div>

                <!-- Special Packaging Request -->
                <div class="walkin-form-card">
                    <div class="form-card-header">
                        <h3 class="form-card-title">
                            <span class="title-icon">📝</span>
                            <span>@t('walkin.special_packaging', '2. Special Packaging / Instructions (Optional)')</span>
                        </h3>
                    </div>
                    <textarea name="customer_notes" class="form-control walkin-input" rows="2" 
                              placeholder="@t('walkin.packaging_placeholder', 'e.g. Extra ice bag requested, separate packing, cooler box...')">{{ old('customer_notes') }}</textarea>
                </div>

                <!-- Payment Section Card -->
                <div class="walkin-form-card">
                    <div class="form-card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                        <h3 class="form-card-title">
                            <span class="title-icon">💳</span>
                            <span>@t('walkin.select_payment_method', '3. Select Payment Method')</span>
                        </h3>
                        <div class="payment-shield-pill">
                            🔒 @t('walkin.ssl_badge', '256-bit SSL Encrypted')
                        </div>
                    </div>

                    <!-- Payment Selection Grid -->
                    <div class="walkin-payment-options-grid">
                        <!-- Option 1: Cash at Counter -->
                        <label class="walkin-pay-tile {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}" id="payTileCash" onclick="onWalkinPaymentMethodChange('cash')">
                            <input type="radio" name="payment_method" value="cash" id="radioCash" 
                                   {{ old('payment_method', 'cash') === 'cash' ? 'checked' : '' }} 
                                   onchange="onWalkinPaymentMethodChange('cash')">
                            <div class="pay-tile-check">✓</div>
                            <div class="pay-tile-icon">💵</div>
                            <div class="pay-tile-content">
                                <div class="pay-tile-title">@t('walkin.pay_cash_title', 'Cash at Counter 2')</div>
                                <div class="pay-tile-desc">@t('walkin.pay_cash_desc', 'Pay cash directly at SILC Counter 2 upon collecting your packed order.')</div>
                                <div class="pay-tile-badge badge-cash">@t('walkin.badge_pay_on_pickup', 'Pay on Collection')</div>
                            </div>
                        </label>

                        <!-- Option 2: Online Payment via Stripe -->
                        <label class="walkin-pay-tile {{ old('payment_method') === 'stripe' ? 'selected' : '' }}" id="payTileStripe" onclick="onWalkinPaymentMethodChange('stripe')">
                            <input type="radio" name="payment_method" value="stripe" id="radioStripe" 
                                   {{ old('payment_method') === 'stripe' ? 'checked' : '' }} 
                                   onchange="onWalkinPaymentMethodChange('stripe')">
                            <div class="pay-tile-check">✓</div>
                            <div class="pay-tile-icon">💳</div>
                            <div class="pay-tile-content">
                                <div class="pay-tile-title">@t('walkin.pay_online_title', 'Online Payment (Phone Pay)')</div>
                                <div class="pay-tile-desc">@t('walkin.pay_online_desc', 'Credit / Debit Card, Apple Pay, Google Pay, or FPX Online Banking.')</div>
                                <div class="pay-tile-badge badge-stripe">@t('walkin.badge_official_stripe', 'Official Stripe Hosted')</div>
                            </div>
                        </label>
                    </div>

                    <!-- Detail Info Box: Cash -->
                    <div id="cashInfoBox" class="walkin-pay-info-box cash-box" style="{{ old('payment_method', 'cash') === 'cash' ? '' : 'display:none' }}">
                        <div class="info-box-header">
                            <span class="info-icon">💵</span>
                            <strong>@t('walkin.cash_info_title', 'Pay Cash at Counter 2:')</strong>
                        </div>
                        <p class="info-desc">
                            @t('walkin.cash_info_desc', 'Your order will be registered and queued. Present your Order Reference at Counter 2 to complete payment and collect your packed order.')
                        </p>
                    </div>

                    <!-- Detail Info Box: Online (Stripe) -->
                    <div id="stripeInfoBox" class="walkin-pay-info-box stripe-box" style="{{ old('payment_method') === 'stripe' ? '' : 'display:none' }}">
                        <div class="info-box-header">
                            <span class="info-icon">🛡️</span>
                            <strong>@t('walkin.stripe_info_title', 'Secure Phone Payment:')</strong>
                        </div>
                        <p class="info-desc">
                            @t('walkin.stripe_info_desc', 'You will be securely redirected to Stripe checkout. Upon payment confirmation, your order will be prepared for Counter 2 collection and you will receive your collection reference.')
                        </p>
                        <div class="payment-methods-badges-row">
                            <span class="pay-chip">💳 Visa</span>
                            <span class="pay-chip">💳 Mastercard</span>
                            <span class="pay-chip">🍎 Apple Pay</span>
                            <span class="pay-chip">🌐 Google Pay</span>
                        </div>
                    </div>
                </div>

                <!-- Mobile & Tablet Order Submission Card (< 992px) -->
                <div class="walkin-mobile-submit-card d-lg-none">
                    <div class="mobile-submit-total-row">
                        <div class="total-breakdown">
                            <span class="sub-label">@t('walkin.total_to_pay', 'Total to Pay')</span>
                            <span class="sub-free-badge">✓ @t('walkin.counter_2_pickup', 'MST Counter 2 Collection (FREE)')</span>
                        </div>
                        <div class="total-price-val">
                            RM {{ number_format($totals['total'], 2) }}
                        </div>
                    </div>

                    <button type="submit" class="btn-walkin-pay-submit btn-mobile-submit" id="mobileSubmitBtn">
                        <span id="mobileSubmitBtnIcon">{{ old('payment_method', 'cash') === 'cash' ? '💵' : '🔒' }}</span>
                        <span id="mobileSubmitBtnText">
                            @if(old('payment_method', 'cash') === 'cash')
                                @t('walkin.confirm_cash_order', 'Confirm Order & Proceed to Counter 2')
                            @else
                                @t('walkin.proceed_to_payment', 'Proceed to Secure Payment →')
                            @endif
                        </span>
                    </button>

                    <div class="mobile-submit-trust-row">
                        <span>🏬 @t('walkin.store_short_loc', 'MST Counter 2 · SILC')</span>
                        <span>•</span>
                        <span>📦 @t('walkin.packed_appropriate', 'Appropriately Packed')</span>
                    </div>
                </div>
            </div>

            <!-- Right: Sticky Order Summary (Desktop >= 992px) -->
            <div class="walkin-checkout-sidebar">
                <div class="walkin-order-summary-card">
                    <div class="summary-card-header">
                        <h3 class="summary-card-title">@t('walkin.order_summary', 'Order Summary')</h3>
                        <span class="summary-count-badge">{{ $items->count() }} @t('walkin.items', 'items')</span>
                    </div>

                    <div class="summary-items-list">
                        @foreach($items as $item)
                            @php $price = $item->product?->walkin_price ?? $item->product?->retail_price ?? 0; @endphp
                            <div class="summary-item-row">
                                <div class="summary-item-thumb-box">
                                    @if($item->product?->thumbnail)
                                        <img src="{{ cdn_storage($item->product->thumbnail) }}" alt="{{ $item->product->name }}">
                                    @else
                                        <span class="thumb-emoji">🐟</span>
                                    @endif
                                    <span class="qty-badge">{{ $item->quantity }}</span>
                                </div>
                                <div class="summary-item-details">
                                    <div class="summary-item-name">{{ $item->product?->name }}</div>
                                    <div class="summary-item-meta">{{ $item->quantity }} × RM {{ number_format($price, 2) }}</div>
                                </div>
                                <div class="summary-item-total">
                                    RM {{ number_format($price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-calc-row">
                        <span>@t('walkin.subtotal', 'Subtotal')</span>
                        <span style="font-weight:600;color:#0f172a">RM {{ number_format($totals['subtotal'], 2) }}</span>
                    </div>
                    <div class="summary-calc-row">
                        <span>@t('walkin.fulfillment', 'Fulfillment')</span>
                        <span style="color:#059669;font-weight:700">@t('walkin.counter_2_pickup', 'MST Counter 2 Collection (FREE)')</span>
                    </div>

                    <div class="summary-total-row">
                        <span class="total-label">@t('walkin.total_to_pay', 'Total to Pay')</span>
                        <span class="total-amount">RM {{ number_format($totals['total'], 2) }}</span>
                    </div>

                    <button type="submit" class="btn-walkin-pay-submit" id="submitBtn">
                        <span id="submitBtnIcon">{{ old('payment_method', 'cash') === 'cash' ? '💵' : '🔒' }}</span>
                        <span id="submitBtnText">
                            @if(old('payment_method', 'cash') === 'cash')
                                @t('walkin.confirm_cash_order', 'Confirm Order & Proceed to Counter 2')
                            @else
                                @t('walkin.proceed_to_payment', 'Proceed to Secure Payment →')
                            @endif
                        </span>
                    </button>

                    <div class="summary-footer-trust">
                        <div class="trust-line">🏬 <strong>@t('walkin.store_location', 'MST Counter 2 · SILC Industrial Park, Iskandar Puteri')</strong></div>
                        <div class="trust-line">⚡ <strong>@t('walkin.prepared_promptly', 'Orders prepared for Counter 2 collection after payment confirmation')</strong></div>
                        <div class="trust-line">📦 <strong>@t('walkin.packed_appropriately', 'Orders are packed appropriately for collection and transport')</strong></div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
/* ─── Hero Section ─── */
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

.btn-walkin-back {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-weight: 600;
    font-size: 0.82rem;
    text-decoration: none;
    transition: all 0.15s ease;
}
.btn-walkin-back:hover {
    background: rgba(255, 255, 255, 0.2);
}

.walkin-hero-heading-box {
    margin-bottom: 18px;
}
.walkin-security-tag {
    background: rgba(30, 58, 138, 0.6);
    border: 1px solid rgba(56, 189, 248, 0.35);
    padding: 3px 10px;
    border-radius: 8px;
    font-size: 0.72rem;
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
    font-size: clamp(1.5rem, 2.8vw, 2rem);
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
    line-height: 1.5;
    margin: 0;
}

/* ─── 4-Step Stepper ─── */
.walkin-stepper-wrap {
    background: rgba(6, 21, 43, 0.6);
    border: 1px solid rgba(56, 189, 248, 0.25);
    border-radius: 16px;
    padding: 12px 18px;
    backdrop-filter: blur(12px);
    margin-top: 10px;
}
.walkin-stepper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    overflow-x: auto;
    scrollbar-width: none;
}
.walkin-stepper::-webkit-scrollbar { display: none; }
.step-item {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    opacity: 0.65;
    transition: all 0.2s ease;
}
.step-icon {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 800;
    background: rgba(255, 255, 255, 0.1);
    color: #93c5fd;
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.step-info { display: flex; flex-direction: column; }
.step-num { font-size: 0.68rem; color: #7dd3fc; text-transform: uppercase; font-weight: 700; line-height: 1; }
.step-label { font-size: 0.85rem; color: #e2e8f0; font-weight: 600; white-space: nowrap; }

.step-item.step-completed { opacity: 0.9; }
.step-item.step-completed .step-icon {
    background: #059669;
    color: #ffffff;
    border-color: #34d399;
}
.step-item.step-active {
    opacity: 1;
    background: rgba(56, 189, 248, 0.15);
    padding: 6px 14px;
    border-radius: 12px;
    border: 1px solid rgba(56, 189, 248, 0.4);
}
.step-item.step-active .step-icon {
    background: #2563eb;
    color: #ffffff;
    border-color: #60a5fa;
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.6);
}
.step-item.step-active .step-label {
    color: #ffffff;
    font-weight: 800;
}
.step-divider {
    flex: 1;
    height: 2px;
    background: rgba(255, 255, 255, 0.15);
    min-width: 16px;
}
.step-divider.active {
    background: linear-gradient(90deg, #059669 0%, #2563eb 100%);
}

/* ─── Mobile Collapsible Summary Bar (< 992px) ─── */
.mobile-order-summary-card {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 16px;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
    cursor: pointer;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.mobile-order-summary-card:hover {
    border-color: #93c5fd;
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.08);
}
.mobile-summary-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 18px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    gap: 12px;
    user-select: none;
}
.mobile-summary-left {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 0;
    flex: 1;
}
.mobile-summary-icon {
    font-size: 1.15rem;
    flex-shrink: 0;
}
.mobile-summary-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    min-width: 0;
}
.mobile-summary-count-badge {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    font-size: 0.74rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 999px;
    flex-shrink: 0;
}
.mobile-summary-chevron {
    font-size: 0.7rem;
    color: #2563eb;
    transition: transform 0.2s ease;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #eff6ff;
    border: 1px solid #dbeafe;
}
.mobile-summary-chevron.open {
    transform: rotate(180deg);
}
.mobile-summary-right {
    font-family: var(--font-heading);
    font-size: 1.15rem;
    font-weight: 800;
    color: #1e40af;
    white-space: nowrap;
    flex-shrink: 0;
}

.mobile-summary-collapse {
    padding: 16px 18px 20px;
    background: #ffffff;
}
.mobile-summary-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 14px;
    max-height: 240px;
    overflow-y: auto;
}
.mobile-summary-item-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}
.mobile-item-thumb {
    position: relative;
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.mobile-item-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.qty-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    background: #1d4ed8;
    color: #ffffff;
    font-size: 0.65rem;
    font-weight: 800;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ffffff;
}
.mobile-item-details { flex: 1; min-width: 0; }
.mobile-item-name {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 2px;
}
.mobile-item-meta { font-size: 0.75rem; color: #64748b; }
.mobile-item-price { font-size: 0.92rem; font-weight: 800; color: #0f172a; white-space: nowrap; }

.mobile-summary-totals {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding-top: 4px;
    font-size: 0.88rem;
}
.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #64748b;
    font-size: 0.88rem;
    padding: 3px 0;
}
.summary-val-dark { font-weight: 700; color: #0f172a; }
.summary-val-free { font-weight: 700; color: #059669; }

.mobile-summary-grand-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    margin-top: 10px;
}
.mobile-summary-grand-box .grand-label {
    font-weight: 800;
    font-size: 0.95rem;
    color: #0f172a;
}
.mobile-summary-grand-box .grand-amount {
    font-family: var(--font-heading);
    font-size: 1.32rem;
    font-weight: 900;
    color: #1e40af;
}

/* ─── Main Checkout Grid ─── */
.walkin-checkout-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 28px;
    align-items: start;
}

/* Pickup Notice Card */
.walkin-pickup-notice-card {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border: 1.5px solid #bfdbfe;
    border-radius: 16px;
    padding: 18px 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(30, 64, 175, 0.05);
}
.notice-icon { font-size: 2.2rem; line-height: 1; }
.notice-title { font-weight: 800; font-size: 1rem; color: #1e3a8a; margin-bottom: 4px; }
.notice-desc { font-size: 0.86rem; color: #1d4ed8; line-height: 1.45; margin-bottom: 8px; }
.notice-badge {
    display: inline-block;
    background: #ffffff;
    color: #1e40af;
    border: 1px solid #bfdbfe;
    padding: 3px 10px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
}

/* Form Cards */
.walkin-form-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
}
.form-card-header {
    padding-bottom: 12px;
    margin-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
}
.form-card-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.title-icon { font-size: 1.15rem; }

.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 14px;
}
.form-group {
    margin-bottom: 14px;
}
.form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}
.walkin-input {
    width: 100%;
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    font-size: 0.9rem;
    padding: 10px 14px;
    background: #f8fafc;
    transition: all 0.2s ease;
}
.walkin-input:focus {
    outline: none;
    border-color: #2563eb;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

.payment-shield-pill {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
}
.walkin-payment-alert {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.85rem;
    line-height: 1.45;
    margin-bottom: 14px;
}

.payment-methods-badges-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}
.pay-chip {
    font-size: 0.75rem;
    font-weight: 700;
    color: #334155;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    padding: 3px 8px;
    border-radius: 6px;
}

.walkin-payment-box {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    min-height: 80px;
}

/* ─── Right: Sticky Sidebar Order Summary ─── */
.walkin-checkout-sidebar {
    position: sticky;
    top: 90px;
}
.walkin-order-summary-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 22px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
}
.summary-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 14px;
}
.summary-card-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}
.summary-count-badge {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 999px;
}

.summary-items-list {
    max-height: 260px;
    overflow-y: auto;
    margin-bottom: 14px;
    padding-right: 4px;
    scrollbar-width: thin;
}
.summary-item-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
}
.summary-item-thumb-box {
    position: relative;
    width: 44px;
    height: 44px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.summary-item-thumb-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.summary-item-details { flex: 1; min-width: 0; }
.summary-item-name {
    font-size: 0.85rem;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.summary-item-meta { font-size: 0.75rem; color: #64748b; }
.summary-item-total {
    font-weight: 700;
    color: #1e3a8a;
    font-size: 0.88rem;
    white-space: nowrap;
}

.summary-calc-row {
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
    font-size: 0.88rem;
    color: #475569;
}
.summary-total-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-top: 10px;
    padding-top: 12px;
    border-top: 2px dashed #cbd5e1;
}
.total-label { font-weight: 800; font-size: 1.05rem; color: #0f172a; }
.total-amount { font-family: var(--font-heading); font-size: 1.45rem; font-weight: 800; color: #1e3a8a; }

.btn-walkin-pay-submit {
    width: 100%;
    margin-top: 16px;
    padding: 14px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #091a36;
    border: 1px solid #fde68a;
    border-radius: 12px;
    font-weight: 800;
    font-size: 1rem;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.btn-walkin-pay-submit:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.55);
    color: #091a36;
}
.btn-walkin-pay-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.summary-footer-trust {
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 0.75rem;
    color: #64748b;
}
.trust-line { display: flex; align-items: center; gap: 4px; }

/* ─── Mobile & Tablet Order Submission Card (< 992px) ─── */
.walkin-mobile-submit-card {
    display: none;
    background: #ffffff;
    border: 1.5px solid #bfdbfe;
    border-radius: 16px;
    padding: 20px 18px;
    margin-top: 22px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
}
.mobile-submit-total-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px dashed #e2e8f0;
}
.total-breakdown {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.total-breakdown .sub-label {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.04em;
}
.total-breakdown .sub-free-badge {
    font-size: 0.75rem;
    color: #059669;
    font-weight: 700;
}
.total-price-val {
    font-family: var(--font-heading);
    font-size: 1.45rem;
    font-weight: 900;
    color: #1e40af;
}
.btn-mobile-submit {
    margin-top: 0 !important;
    margin-bottom: 12px !important;
}
.mobile-submit-trust-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 0.74rem;
    color: #64748b;
    font-weight: 600;
    flex-wrap: wrap;
}

/* ─── Responsive Breakpoints (Tablet & Mobile) ─── */
@media (max-width: 991px) {
    .walkin-checkout-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .walkin-checkout-sidebar {
        display: none !important; /* Summary is shown in mobile collapsible bar */
    }
    .walkin-mobile-submit-card {
        display: block !important;
    }
}

@media (max-width: 640px) {
    .walkin-hero-section {
        padding-top: calc(78px + 32px);
        padding-bottom: var(--space-5);
    }
    .walkin-hero-title {
        font-size: 1.4rem;
    }
    .walkin-hero-subtitle {
        font-size: 0.82rem;
    }
    .walkin-stepper-wrap {
        padding: 8px 12px !important;
        margin-top: 8px !important;
    }
    .walkin-stepper {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 6px !important;
        overflow: visible !important;
    }
    .step-item:not(.step-active) .step-info {
        display: none !important;
    }
    .step-item:not(.step-active) {
        padding: 0 !important;
        gap: 0 !important;
        background: transparent !important;
        border: none !important;
        opacity: 0.65 !important;
    }
    .step-item.step-completed:not(.step-active) {
        opacity: 0.9 !important;
    }
    .step-item:not(.step-active) .step-icon {
        width: 24px !important;
        height: 24px !important;
        font-size: 0.72rem !important;
    }
    .step-item.step-active {
        display: inline-flex !important;
        align-items: center !important;
        padding: 5px 12px !important;
        gap: 8px !important;
        border-radius: 999px !important;
        background: rgba(56, 189, 248, 0.22) !important;
        border: 1.5px solid #38bdf8 !important;
        box-shadow: 0 0 12px rgba(56, 189, 248, 0.35) !important;
        flex-shrink: 0 !important;
    }
    .step-item.step-active .step-icon {
        width: 26px !important;
        height: 26px !important;
        font-size: 0.78rem !important;
    }
    .step-item.step-active .step-info {
        display: flex !important;
        flex-direction: column !important;
    }
    .step-item.step-active .step-num {
        font-size: 0.6rem !important;
        color: #7dd3fc !important;
        text-transform: uppercase !important;
        font-weight: 700 !important;
        line-height: 1 !important;
    }
    .step-item.step-active .step-label {
        font-size: 0.78rem !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        white-space: nowrap !important;
        line-height: 1.15 !important;
    }
    .step-divider {
        flex: 1 1 auto !important;
        min-width: 8px !important;
        height: 2px !important;
    }
    .form-grid-2 {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    .walkin-pickup-notice-card {
        padding: 14px;
    }
    .notice-icon {
        font-size: 1.8rem;
    }
    .walkin-form-card {
        padding: 16px;
    }
}

/* ─── Walk-in Payment Method Selection Tiles ─── */
.walkin-payment-options-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
    margin-bottom: 16px;
}
@media (max-width: 640px) {
    .walkin-payment-options-grid {
        grid-template-columns: 1fr;
    }
}
.walkin-pay-tile {
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
.walkin-pay-tile:hover {
    border-color: #93c5fd;
    background: #f8fafc;
}
.walkin-pay-tile.selected {
    border-color: #2563eb;
    background: #eff6ff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
}
.walkin-pay-tile input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.pay-tile-check {
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
.walkin-pay-tile.selected .pay-tile-check {
    border-color: #2563eb;
    background: #2563eb;
    color: #ffffff;
}
.pay-tile-icon {
    font-size: 1.8rem;
    line-height: 1;
    flex-shrink: 0;
}
.pay-tile-content {
    flex: 1;
    min-width: 0;
}
.pay-tile-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 4px;
}
.pay-tile-desc {
    font-size: 0.8rem;
    color: #64748b;
    line-height: 1.35;
    margin-bottom: 8px;
}
.pay-tile-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 700;
}
.pay-tile-badge.badge-cash {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}
.pay-tile-badge.badge-stripe {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}
.walkin-pay-info-box {
    border-radius: 12px;
    padding: 14px 16px;
    margin-top: 4px;
    animation: fadeIn 0.25s ease;
}
.walkin-pay-info-box.cash-box {
    background: #fefce8;
    border: 1.5px solid #fef08a;
}
.walkin-pay-info-box.stripe-box {
    background: #eff6ff;
    border: 1.5px solid #bfdbfe;
}
.walkin-pay-info-box .info-box-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: #0f172a;
    margin-bottom: 6px;
}
.walkin-pay-info-box .info-desc {
    font-size: 0.82rem;
    color: #475569;
    line-height: 1.4;
    margin: 0 0 10px 0;
}
.walkin-pay-info-box .info-highlight-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fef3c7;
    color: #92400e;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 700;
}
</style>
@endpush

@push('scripts')
<script>
// Mobile Collapsible Order Summary Toggle
function toggleMobileSummary() {
    const collapse = document.getElementById('mobileSummaryCollapse');
    const chevron = document.getElementById('mobileSummaryChevron');
    const text = document.getElementById('mobileSummaryText');

    if (!collapse) return;

    const isHidden = window.getComputedStyle(collapse).display === 'none';
    if (isHidden) {
        collapse.style.display = 'block';
        if (chevron) chevron.classList.add('open');
        if (text) text.textContent = @json(__t('walkin.hide_order_summary', 'Hide Order Summary'));
    } else {
        collapse.style.display = 'none';
        if (chevron) chevron.classList.remove('open');
        if (text) text.textContent = @json(__t('walkin.show_order_summary', 'Show Order Summary'));
    }
}

// Payment method selection handler
function onWalkinPaymentMethodChange(method) {
    const radioCash = document.getElementById('radioCash');
    const radioStripe = document.getElementById('radioStripe');
    const payTileCash = document.getElementById('payTileCash');
    const payTileStripe = document.getElementById('payTileStripe');
    const cashInfoBox = document.getElementById('cashInfoBox');
    const stripeInfoBox = document.getElementById('stripeInfoBox');
    const btnIcon = document.getElementById('submitBtnIcon');
    const btnText = document.getElementById('submitBtnText');
    const mobileBtnIcon = document.getElementById('mobileSubmitBtnIcon');
    const mobileBtnText = document.getElementById('mobileSubmitBtnText');

    const cashLabel = @json(__t('walkin.confirm_cash_order', 'Confirm Order & Get Collection Token'));
    const stripeLabel = @json(__t('walkin.proceed_to_stripe', 'Proceed to Stripe Official Checkout →'));

    if (method === 'cash') {
        if (radioCash) radioCash.checked = true;
        payTileCash?.classList.add('selected');
        payTileStripe?.classList.remove('selected');
        if (cashInfoBox) cashInfoBox.style.display = 'block';
        if (stripeInfoBox) stripeInfoBox.style.display = 'none';
        if (btnIcon) btnIcon.textContent = '💵';
        if (btnText) btnText.textContent = cashLabel;
        if (mobileBtnIcon) mobileBtnIcon.textContent = '💵';
        if (mobileBtnText) mobileBtnText.textContent = cashLabel;
    } else {
        if (radioStripe) radioStripe.checked = true;
        payTileCash?.classList.remove('selected');
        payTileStripe?.classList.add('selected');
        if (cashInfoBox) cashInfoBox.style.display = 'none';
        if (stripeInfoBox) stripeInfoBox.style.display = 'block';
        if (btnIcon) btnIcon.textContent = '🔒';
        if (btnText) btnText.textContent = stripeLabel;
        if (mobileBtnIcon) mobileBtnIcon.textContent = '🔒';
        if (mobileBtnText) mobileBtnText.textContent = stripeLabel;
    }
}

// Form submission handler
document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    const mobileBtn = document.getElementById('mobileSubmitBtn');
    const selected = document.querySelector('input[name="payment_method"]:checked')?.value || 'cash';
    const loadingHtml = selected === 'cash'
        ? '<span>⏳</span> <span>' + @json(__t('walkin.generating_token', 'Generating Collection Token...')) + '</span>'
        : '<span>⏳</span> <span>' + @json(__t('walkin.redirecting_stripe', 'Redirecting to Stripe...')) + '</span>';

    [btn, mobileBtn].forEach(b => {
        if (b) {
            b.style.pointerEvents = 'none';
            b.style.opacity = '0.85';
            b.innerHTML = loadingHtml;
        }
    });
});
</script>
@endpush
