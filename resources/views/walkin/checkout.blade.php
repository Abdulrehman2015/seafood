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
                <a href="{{ route('walkin.shop') }}" class="breadcrumb-link">@t('walkin.catalogue_title', 'Walk-in Express')</a>
                <span class="breadcrumb-sep">›</span>
                <span class="breadcrumb-current">@t('walkin.checkout_step', 'Express Checkout')</span>
            </div>
            
            <div style="display:flex;align-items:center;gap:10px">
                <span class="walkin-live-badge">
                    <span class="pulse-dot"></span>
                    @t('walkin.tier_badge', 'In-Store Walk-in Express')
                </span>
                <a href="{{ route('walkin.shop') }}" class="btn-walkin-back">
                    ← @t('walkin.back_to_catalogue', 'Back to Catalogue')
                </a>
            </div>
        </div>

        <div class="walkin-hero-heading-box">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap">
                <span class="walkin-security-tag">
                    🔒 @t('walkin.secure_256', '256-Bit Encrypted Instant Checkout')
                </span>
                <span class="walkin-tag-sub">⚡ @t('walkin.counter_pickup_ready', 'Counter 2 Express Pickup')</span>
            </div>
            <h1 class="walkin-hero-title">
                @t('walkin.checkout_title', 'Walk-in Express Checkout')
            </h1>
            <p class="walkin-hero-subtitle">
                @t('walkin.checkout_subtitle', 'Fast mobile checkout for immediate self-collection at SILC Iskandar Puteri Retail Counter 2.')
            </p>
        </div>

        <!-- 4-Step Interactive Process Flow (Step 3 Active) -->
        <div class="walkin-stepper-wrap">
            <div class="walkin-stepper">
                <a href="{{ route('walkin.entry') }}" class="step-item step-completed" style="text-decoration:none">
                    <div class="step-icon">✓</div>
                    <div class="step-info">
                        <span class="step-num">Step 1</span>
                        <span class="step-label">@t('walkin.step_qr', 'Scan QR Code')</span>
                    </div>
                </a>
                <div class="step-divider active"></div>

                <a href="{{ route('walkin.shop') }}" class="step-item step-completed" style="text-decoration:none">
                    <div class="step-icon">✓</div>
                    <div class="step-info">
                        <span class="step-num">Step 2</span>
                        <span class="step-label">@t('walkin.step_pick', 'Pick Seafood')</span>
                    </div>
                </a>
                <div class="step-divider active"></div>

                <div class="step-item step-active">
                    <div class="step-icon">3</div>
                    <div class="step-info">
                        <span class="step-num">Step 3</span>
                        <span class="step-label">@t('walkin.step_pay', 'Fast Phone Pay')</span>
                    </div>
                </div>
                <div class="step-divider"></div>

                <div class="step-item">
                    <div class="step-icon">4</div>
                    <div class="step-info">
                        <span class="step-num">Step 4</span>
                        <span class="step-label">@t('walkin.step_collect', 'Collection Token')</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top:var(--space-8);padding-bottom:var(--space-16)">

    <!-- Mobile Collapsible Order Summary Banner (< 992px) -->
    <div class="mobile-order-summary-card d-lg-none" onclick="toggleMobileSummary()">
        <div class="mobile-summary-bar">
            <div class="mobile-summary-left">
                <span class="mobile-summary-icon">🛍️</span>
                <div class="mobile-summary-text">
                    <span id="mobileSummaryText">@t('walkin.show_order_summary', 'Show Order Summary')</span>
                    <span class="mobile-summary-count">({{ $items->count() }} @t('walkin.items', 'items'))</span>
                </div>
                <span id="mobileSummaryChevron" class="mobile-summary-chevron">▼</span>
            </div>
            <div class="mobile-summary-right">
                RM {{ number_format($totals['total'], 2) }}
            </div>
        </div>

        <!-- Collapsible Content -->
        <div id="mobileSummaryCollapse" class="mobile-summary-collapse" style="display:none" onclick="event.stopPropagation()">
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
                            <div class="mobile-item-meta">{{ $item->product?->sku ?? 'IN-STORE' }} · {{ $item->quantity }} × RM {{ number_format($price, 2) }}</div>
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
                    <span>RM {{ number_format($totals['subtotal'], 2) }}</span>
                </div>
                <div class="summary-line">
                    <span>@t('walkin.fulfillment', 'Fulfillment')</span>
                    <span style="color:#059669;font-weight:700">@t('walkin.free_counter_pickup', 'Counter Self-Collection (FREE)')</span>
                </div>
                <div class="summary-line summary-grand-total">
                    <span class="total-label">@t('walkin.total_to_pay', 'Total to Pay')</span>
                    <span class="total-val">RM {{ number_format($totals['total'], 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
        @csrf
        <input type="hidden" name="fulfillment_type" value="self_collection">

        <div class="walkin-checkout-grid">

            <!-- Left: Customer Info & Payment -->
            <div class="walkin-checkout-main">
                <!-- Store Counter Pickup Notice -->
                <div class="walkin-pickup-notice-card">
                    <div class="notice-icon">🏬</div>
                    <div>
                        <div class="notice-title">@t('walkin.pickup_station_title', 'Johor Bahru (SILC) Retail Counter Self-Collection')</div>
                        <div class="notice-desc">@t('walkin.pickup_station_desc', 'Your seafood order will be immediately packed with ice gel packs and waiting at Counter 2 once your payment is confirmed.')</div>
                        <div class="notice-badge">⚡ @t('walkin.instant_token_badge', 'Instant Collection Token Generated on Completion')</div>
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
                            <span>@t('walkin.secure_payment', '3. Secure In-Store Payment')</span>
                        </h3>
                        <div class="payment-shield-pill">
                            🔒 @t('walkin.ssl_badge', '256-bit SSL Encrypted')
                        </div>
                    </div>
                    
                    <div class="walkin-payment-alert">
                        <strong>🔒 @t('walkin.instant_pay_title', 'Instant Phone Payment:')</strong> @t('walkin.payment_hint', 'Pay securely with Credit/Debit Card, Apple Pay, Google Pay, or FPX. You will immediately receive your Counter Collection Token.')
                    </div>

                    <div class="payment-methods-badges-row">
                        <span class="pay-chip">💳 Visa</span>
                        <span class="pay-chip">💳 Mastercard</span>
                        <span class="pay-chip">🍎 Apple Pay</span>
                        <span class="pay-chip">🌐 Google Pay</span>
                        <span class="pay-chip">🏦 FPX Online Banking</span>
                    </div>

                    <!-- Payment Element Container -->
                    <div id="payment-element" class="walkin-payment-box">
                        <p class="text-muted text-sm text-center" style="padding:var(--space-3);color:#64748b">Connecting to secure gateway...</p>
                    </div>
                    <div id="payment-message" class="alert alert-danger mt-3" style="display:none;font-size:0.85rem"></div>
                    <input type="hidden" name="payment_intent_id" id="paymentIntentId">
                </div>
            </div>

            <!-- Right: Sticky Order Summary -->
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
                        <span style="color:#059669;font-weight:700">@t('walkin.free_counter_pickup', 'Counter Self-Collection (FREE)')</span>
                    </div>

                    <div class="summary-total-row">
                        <span class="total-label">@t('walkin.total_to_pay', 'Total to Pay')</span>
                        <span class="total-amount">RM {{ number_format($totals['total'], 2) }}</span>
                    </div>

                    <button type="submit" class="btn-walkin-pay-submit" id="submitBtn">
                        🔒 @t('walkin.pay_and_get_token', 'Pay & Get Collection Token')
                    </button>

                    <div class="summary-footer-trust">
                        <div class="trust-line">⚡ <strong>@t('walkin.instant_token', 'Instant Token Generation')</strong></div>
                        <div class="trust-line">🏬 <strong>@t('walkin.counter_silc', 'SILC Iskandar Puteri Counter 2')</strong></div>
                        <div class="trust-line">❄️ <strong>@t('walkin.cold_chain_packed', '-18°C IQF Ice Gel Packed')</strong></div>
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
    padding-top: calc(75px + var(--space-6));
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
    border: 1.5px solid #bfdbfe;
    border-radius: 14px;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    cursor: pointer;
}
.mobile-summary-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    background: #eff6ff;
}
.mobile-summary-left {
    display: flex;
    align-items: center;
    gap: 8px;
}
.mobile-summary-icon { font-size: 1.25rem; }
.mobile-summary-text {
    font-size: 0.88rem;
    font-weight: 700;
    color: #1e3a8a;
}
.mobile-summary-count {
    color: #64748b;
    font-size: 0.78rem;
    font-weight: normal;
    margin-left: 2px;
}
.mobile-summary-chevron {
    font-size: 0.75rem;
    color: #2563eb;
    transition: transform 0.2s ease;
}
.mobile-summary-chevron.open {
    transform: rotate(180deg);
}
.mobile-summary-right {
    font-family: var(--font-heading);
    font-size: 1.15rem;
    font-weight: 800;
    color: #1e3a8a;
}

.mobile-summary-collapse {
    padding: 16px 18px;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
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
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
}
.mobile-item-thumb {
    position: relative;
    width: 48px;
    height: 48px;
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
    top: -4px;
    right: -4px;
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
    font-size: 0.85rem;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.mobile-item-meta { font-size: 0.75rem; color: #64748b; }
.mobile-item-price { font-size: 0.88rem; font-weight: 700; color: #1e3a8a; }

.mobile-summary-totals {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding-top: 10px;
    border-top: 1px solid #e2e8f0;
    font-size: 0.88rem;
}
.summary-line {
    display: flex;
    justify-content: space-between;
    color: #475569;
}
.summary-grand-total {
    margin-top: 6px;
    padding-top: 8px;
    border-top: 2px dashed #cbd5e1;
    font-size: 1.1rem;
}
.summary-grand-total .total-label { font-weight: 800; color: #0f172a; }
.summary-grand-total .total-val { font-family: var(--font-heading); font-weight: 800; color: #1e3a8a; }

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
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-weight: 800;
    font-size: 0.98rem;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    transition: all 0.2s ease;
}
.btn-walkin-pay-submit:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
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

/* ─── Responsive Breakpoints (Tablet & Mobile) ─── */
@media (max-width: 991px) {
    .walkin-checkout-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .walkin-checkout-sidebar {
        display: none; /* Summary is shown in mobile collapsible bar */
    }
}

@media (max-width: 600px) {
    .walkin-hero-section {
        padding-top: calc(65px + var(--space-4));
        padding-bottom: var(--space-5);
    }
    .walkin-hero-title {
        font-size: 1.4rem;
    }
    .walkin-hero-subtitle {
        font-size: 0.82rem;
    }
    .walkin-stepper-wrap {
        padding: 8px 10px;
    }
    .step-item {
        gap: 6px;
    }
    .step-label {
        font-size: 0.72rem;
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
</style>
@endpush

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
// Mobile Collapsible Order Summary Toggle
function toggleMobileSummary() {
    const collapse = document.getElementById('mobileSummaryCollapse');
    const chevron = document.getElementById('mobileSummaryChevron');
    const text = document.getElementById('mobileSummaryText');

    if (collapse.style.display === 'none') {
        collapse.style.display = 'block';
        chevron.classList.add('open');
        text.textContent = 'Hide Order Summary';
    } else {
        collapse.style.display = 'none';
        chevron.classList.remove('open');
        text.textContent = 'Show Order Summary';
    }
}

let stripe, elements, paymentElement;
const stripeKey = '{{ config("services.stripe.key") }}';

async function initStripe() {
    const isPlaceholder = !stripeKey || stripeKey.includes('YOUR_PUBLISHABLE_KEY');

    if (isPlaceholder) {
        showMockPaymentUI();
        return;
    }

    try {
        stripe = Stripe(stripeKey);
        const res = await fetch('{{ route("checkout.paymentIntent") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await res.json();
        if (data.clientSecret) {
            elements = stripe.elements({
                clientSecret: data.clientSecret,
                appearance: {
                    theme: 'stripe',
                    variables: { colorPrimary: '#1d4ed8', fontFamily: 'Inter, sans-serif' }
                }
            });
            paymentElement = elements.create('payment');
            paymentElement.mount('#payment-element');
        } else {
            showMockPaymentUI();
        }
    } catch (err) {
        showMockPaymentUI();
    }
}

function showMockPaymentUI() {
    document.getElementById('payment-element').innerHTML = `
        <div style="text-align:center;padding:12px;background:#eff6ff;border-radius:10px;border:1px solid #bfdbfe">
            <div style="font-weight:700;color:#1e3a8a;font-size:0.92rem;margin-bottom:4px">
                ⚡ Express Demo Payment Mode Active
            </div>
            <p class="text-xs text-muted" style="margin-bottom:12px;color:#1d4ed8">
                Live sandbox enabled. Tap below to simulate instant in-store payment and generate your collection token.
            </p>
            <button type="button" class="btn btn-primary btn-sm" onclick="simulateTestPayment()" style="background:#1d4ed8;border:none;border-radius:8px;padding:8px 18px;font-weight:700">
                ⚡ Simulate Instant Payment (One-Touch)
            </button>
        </div>
    `;
}

function simulateTestPayment() {
    document.getElementById('paymentIntentId').value = 'pi_test_walkin_' + Math.random().toString(36).substring(2, 12);
    document.getElementById('payment-element').innerHTML = `
        <div style="background:#ecfdf5;color:#065f46;padding:12px;border-radius:10px;text-align:center;font-weight:700;font-size:0.9rem;border:1.5px solid #a7f3d0">
            ✓ Payment Authorized (Simulated Test Mode)
        </div>
    `;
    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = '🔒 Complete & Get Collection Token →';
    }
}

initStripe();

document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');

    // If simulated payment has been authorized
    if (document.getElementById('paymentIntentId').value) {
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = 'Generating Collection Token...';
        }
        e.target.submit();
        return;
    }

    // If Stripe is not loaded or in mock mode
    if (!stripe || !elements) {
        simulateTestPayment();
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = 'Generating Collection Token...';
        }
        e.target.submit();
        return;
    }

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = 'Processing Payment...';
    }

    const { error, paymentIntent } = await stripe.confirmPayment({
        elements,
        redirect: 'if_required'
    });

    if (error) {
        const msgEl = document.getElementById('payment-message');
        msgEl.style.display = 'block';
        msgEl.textContent = error.message;
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '🔒 Pay & Get Collection Token';
        }
        return;
    }

    document.getElementById('paymentIntentId').value = paymentIntent.id;
    e.target.submit();
});
</script>
@endpush
