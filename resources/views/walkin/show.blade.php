@extends('layouts.app')
@section('title', $product->name . ' (' . __t('walkin.walkin_title', 'Walk-in') . ') — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd'))

@section('content')
<!-- Walk-in Ocean Header Banner -->
<div class="walkin-hero-section" style="position:relative;background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-top:calc(78px + 28px);padding-bottom:var(--space-6);overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.08;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:24px 24px;pointer-events:none"></div>
    
    <div class="container" style="position:relative;z-index:2">
        <div class="walkin-show-top-nav" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div class="breadcrumb" style="margin-bottom:6px">
                    <a href="{{ route('home') }}" style="color:#bae6fd;text-decoration:none;font-size:0.85rem">🏠 @t('nav.home', 'Home')</a>
                    <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
                    <a href="{{ route('walkin.shop') }}" style="color:#bae6fd;text-decoration:none;font-size:0.85rem">@t('walkin.catalogue_title', 'Walk-in Express')</a>
                    <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
                    <span style="font-weight:600;color:#ffffff;font-size:0.85rem">{{ $product->name }}</span>
                </div>
                <h1 style="font-family:var(--font-heading);font-size:clamp(1.4rem, 2.5vw, 1.8rem);font-weight:800;color:#ffffff;margin:0">
                    {{ $product->name }}
                </h1>
            </div>

            <div style="display:flex;gap:10px;align-items:center">
                <a href="{{ route('walkin.shop') }}" class="btn-walkin-back">
                    ← @t('walkin.back_to_catalogue', 'Back to Catalogue')
                </a>
                <a href="{{ route('walkin.checkout') }}" class="btn-walkin-hero-checkout" style="padding:8px 16px;font-size:0.85rem">
                    <span>@t('walkin.checkout', 'Walk-in Checkout')</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top:var(--space-8);padding-bottom:var(--space-16)">
    <div class="walkin-detail-grid">
        <!-- Product Images Column -->
        <div class="walkin-media-col">
            <div class="walkin-main-img-card">
                @if($product->thumbnail)
                    <img id="mainImage" src="{{ cdn_storage($product->thumbnail) }}" alt="{{ $product->name }}">
                @else
                    <div class="walkin-img-placeholder" style="font-size:5rem">🐟</div>
                @endif
                <span class="badge-walkin-main-tag">🏪 @t('walkin.in_store_exclusive', 'In-Store Exclusive')</span>
            </div>

            @if(!empty($product->images) && is_array($product->images))
                <div class="walkin-thumbs-row">
                    @if($product->thumbnail)
                        <img src="{{ cdn_storage($product->thumbnail) }}" alt="Thumb" class="walkin-thumb active" onclick="switchMainImage(this)">
                    @endif
                    @foreach($product->images as $img)
                        <img src="{{ cdn_storage($img) }}" alt="Gallery" class="walkin-thumb" onclick="switchMainImage(this)">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info Column -->
        <div class="walkin-info-col">
            <div class="walkin-info-badges">
                <span class="badge-tag-blue">🏪 @t('walkin.counter_item', 'Walk-in Counter Item')</span>
                @if($product->category)
                    <span class="badge-tag-gray">{{ $product->category->name }}</span>
                @endif
                @if($product->storage_temp)
                    @php
                        $tempLower = strtolower($product->storage_temp);
                        $isLive = str_contains($tempLower, 'live');
                        $isChilled = str_contains($tempLower, 'chilled');
                        $badgeIcon = $product->getStorageIcon();
                        $badgeSuffix = ($isLive || $isChilled) ? '' : ' IQF';
                    @endphp
                    <span class="badge-tag-cyan">{{ $badgeIcon }} {{ $product->storage_temp }}{{ $badgeSuffix }}</span>
                @endif
            </div>

            <h2 class="walkin-detail-title">{{ $product->name }}</h2>

            @if($product->sku)
                <div class="walkin-detail-sku">SKU: <strong>{{ $product->sku }}</strong></div>
            @endif

            <!-- In-Store Price Box -->
            <div class="walkin-price-card">
                <div class="price-card-label">@t('walkin.in_store_price', 'In-Store Walk-in Price')</div>
                
                <!-- Unlocked Price Display -->
                <div class="price-card-value-row js-currency-price walkin-price-unlocked-block"
                     data-base-rm="{{ $price ?? 0 }}"
                     data-manual-sgd="{{ $product->price_sgd ?? '' }}"
                     data-manual-usd="{{ $product->price_usd ?? '' }}"
                     style="display:none">
                    @php
                        $displayWalkin = isset($currencyService) 
                            ? $currencyService->getProductPrice($product, 'walkin', $currentCurrency) 
                            : ['formatted' => 'RM ' . number_format($price, 2), 'base_rm' => null];
                    @endphp
                    <div class="price-stack">
                        <div class="price-main-line" style="display:flex;align-items:baseline;gap:4px">
                            <span class="price-amount price-num price-val" style="color:#1e40af;font-size:1.65rem;font-weight:900">{{ $displayWalkin['formatted'] }}</span>
                            <span class="price-unit" style="font-size:0.88rem;color:#64748b;font-weight:600">/ {{ $product->unit ?? 'pack' }}</span>
                        </div>
                        <span class="price-base-rm price-sub-myr" style="display:{{ ($currentCurrency !== 'MYR' && !empty($displayWalkin['base_rm'])) ? 'block' : 'none' }};font-size:0.85rem;font-weight:600;color:#64748b;margin-top:3px">
                            RM {{ number_format($price, 2) }}
                        </span>
                    </div>
                </div>

                <!-- Locked Price Display -->
                <div class="walkin-price-locked-block" style="margin-bottom:10px">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                        <span style="background:#ffffff;border:1.5px solid #cbd5e1;color:#1e293b;font-size:0.95rem;font-weight:800;padding:6px 14px;border-radius:10px;display:inline-flex;align-items:center;gap:6px">
                            <span>🔒</span>
                            <span>@t('walkin.in_store_rate', 'In-Store Special Rate')</span>
                        </span>
                    </div>
                    <button type="button" onclick="openGetPriceModal({{ json_encode($product->name) }}, {{ json_encode($product->sku ?? '') }}, '{{ $displayWalkin['formatted'] }}', '{{ $product->unit ?? 'pack' }}', '{{ $product->id }}')" 
                            style="width:100%;padding:10px 16px;background:linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);color:#ffffff;border:none;border-radius:10px;font-weight:700;font-size:0.9rem;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:6px;box-shadow:0 3px 10px rgba(37,99,235,0.25)">
                        <span>🏷️ @t('walkin.view_price', 'View Price / Unlock In-Store Rate')</span>
                    </button>
                </div>

                <div class="price-card-hint">
                    📍 @t('walkin.pickup_hint', 'Pay from your phone and immediately collect at SILC Retail Frozen Counter 2.')
                </div>
            </div>

            @if($product->short_description)
                <div class="walkin-short-desc">
                    {{ $product->short_description }}
                </div>
            @endif

            <!-- Add to Walk-in Cart Form (Visible when unlocked) -->
            <form action="{{ route('cart.add') }}" method="POST" class="walkin-add-form walkin-price-unlocked-block" style="display:none">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="qty-and-add-row">
                    <div class="qty-picker-wrap">
                        <label class="qty-picker-label">@t('walkin.quantity', 'Quantity')</label>
                        <div class="qty-picker">
                            <button type="button" class="qty-btn" onclick="const q=document.getElementById('qty');if(q.value>1)q.value--">−</button>
                            <input type="number" id="qty" name="quantity" value="1" min="1" readonly>
                            <button type="button" class="qty-btn" onclick="const q=document.getElementById('qty');q.value++">+</button>
                        </div>
                    </div>

                    <button type="submit" class="btn-walkin-add-main">
                        <span>🛒 @t('walkin.add_to_cart_btn', 'Add to Walk-in Cart')</span>
                    </button>
                </div>
            </form>

            <!-- Specifications Table Card -->
            <div class="walkin-specs-card">
                <h4 class="specs-card-title">📋 @t('walkin.specifications', 'Product Specifications')</h4>
                <div class="specs-grid">
                    @if($product->weight)
                        <div class="spec-name">@t('walkin.weight_spec', 'Weight / Spec')</div>
                        <div class="spec-val">{{ $product->weight }}</div>
                    @endif
                    @if($product->origin)
                        <div class="spec-name">@t('walkin.origin', 'Origin')</div>
                        <div class="spec-val">{{ $product->origin }}</div>
                    @endif
                    @if($product->storage_temp)
                        <div class="spec-name">@t('walkin.storage_temp', 'Storage Temperature')</div>
                        <div class="spec-val">{{ $product->getStorageIcon() }} {{ $product->storage_temp }}</div>
                    @endif
                    @if($product->brand)
                        <div class="spec-name">@t('walkin.brand', 'Brand')</div>
                        <div class="spec-val">{{ $product->brand }}</div>
                    @endif
                    <div class="spec-name">@t('walkin.fulfillment', 'Fulfillment')</div>
                    <div class="spec-val text-cyan" style="color:#0284c7;font-weight:700">🏬 @t('walkin.instant_counter', 'In-Store Counter 2 Pickup')</div>
                </div>
            </div>

            @if($product->description)
                <div class="walkin-long-desc">
                    <h4 class="long-desc-title">@t('walkin.description', 'Product Details')</h4>
                    <div class="long-desc-body">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ─── Get Price / In-Store Rate Unlock Modal ───────────────────────── -->
<div class="get-price-modal-backdrop" id="getPriceModalBackdrop" onclick="handleGetPriceModalBackdropClick(event)">
    <div class="get-price-modal-dialog" id="getPriceModalDialog" role="dialog" aria-modal="true" aria-labelledby="getPriceModalTitle">
        <div class="get-price-modal-header">
            <div class="get-price-modal-title" id="getPriceModalTitle">
                <span>🏷️ @t('walkin.view_price_title', 'In-Store Special Rate')</span>
            </div>
            <button type="button" class="get-price-modal-close-btn" onclick="closeGetPriceModal()" aria-label="Close modal">✕</button>
        </div>

        <div class="get-price-modal-body">
            <div class="get-price-product-info">
                <div class="get-price-product-name" id="modalProductName">{{ $product->name }}</div>
                <div class="get-price-product-sku" id="modalProductSku">{{ $product->sku ? 'SKU: ' . $product->sku : '' }}</div>
            </div>

            <!-- Price Reveal Box -->
            <div class="get-price-revealed-card" id="modalPriceRevealedCard">
                <div class="get-price-card-label">@t('walkin.counter_rate_revealed', 'Official In-Store Rate')</div>
                <div class="get-price-card-amount">
                    <span id="modalProductPrice" class="modal-price-num">{{ $displayWalkin['formatted'] }}</span>
                    <span id="modalProductUnit" class="modal-price-unit">/ {{ $product->unit ?? 'pack' }}</span>
                </div>
                <div class="get-price-card-hint">
                    📍 @t('walkin.counter_pickup_ready', 'Available for immediate walk-in purchase & packing at SILC Counter 2.')
                </div>
            </div>

            <div class="get-price-actions-grid">
                <button type="button" class="btn-unlock-all-prices" id="btnUnlockPrices" onclick="unlockWalkinPrices()">
                    <span style="font-size:1.1rem">🔓</span>
                    <span>@t('walkin.unlock_all_prices', 'Unlock All In-Store Prices')</span>
                </button>
                @php
                    $waMsg = urlencode("Hi MST Import & Export, I would like to get the latest in-store price & availability for: " . $product->name . ($product->sku ? ' (' . $product->sku . ')' : ''));
                @endphp
                <a href="https://wa.me/923176121524?text={{ $waMsg }}" id="modalWhatsAppBtn" target="_blank" rel="noopener noreferrer" class="btn-whatsapp-inquire">
                    <span style="font-size:1.1rem">💬</span>
                    <span>@t('walkin.inquire_whatsapp', 'Enquire on WhatsApp')</span>
                </a>
            </div>

            <div class="get-price-guarantee-note">
                🔒 @t('walkin.price_flow_note', 'Walk-in pricing is reserved for verified retail visitors and registered walk-in guests.')
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.btn-walkin-back {
    display: inline-flex;
    align-items: center;
    padding: 8px 14px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.15s ease;
}
.btn-walkin-back:hover {
    background: rgba(255, 255, 255, 0.2);
}

.walkin-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1.15fr;
    gap: 40px;
    align-items: start;
}

/* Images Column */
.walkin-main-img-card {
    position: relative;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    overflow: hidden;
    aspect-ratio: 4 / 3;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
}
.walkin-main-img-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.badge-walkin-main-tag {
    position: absolute;
    top: 14px;
    left: 14px;
    background: linear-gradient(135deg, rgba(6, 21, 43, 0.92) 0%, rgba(30, 58, 138, 0.92) 100%);
    backdrop-filter: blur(4px);
    color: #7dd3fc;
    border: 1px solid rgba(56, 189, 248, 0.4);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 999px;
}

.walkin-thumbs-row {
    display: flex;
    gap: 10px;
    margin-top: 12px;
    overflow-x: auto;
    padding-bottom: 4px;
}
.walkin-thumb {
    width: 76px;
    height: 76px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid transparent;
    cursor: pointer;
    background: #f8fafc;
    transition: all 0.15s ease;
}
.walkin-thumb:hover {
    border-color: #93c5fd;
}
.walkin-thumb.active {
    border-color: #2563eb;
    box-shadow: 0 0 8px rgba(37, 99, 235, 0.35);
}

/* Info Column */
.walkin-info-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}
.badge-tag-blue {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 999px;
}
.badge-tag-gray {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 999px;
}
.badge-tag-cyan {
    background: #091a36;
    color: #7dd3fc;
    border: 1px solid rgba(56, 189, 248, 0.4);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 999px;
}

.walkin-detail-title {
    font-family: var(--font-heading);
    font-size: 1.85rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.25;
    margin: 0 0 6px 0;
}
.walkin-detail-sku {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 18px;
}

/* Price Card */
.walkin-price-card {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border: 1.5px solid #bfdbfe;
    border-radius: 16px;
    padding: 18px 22px;
    margin-bottom: 22px;
}
.price-card-label {
    font-size: 0.78rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #1d4ed8;
    margin-bottom: 4px;
}
.price-card-value-row {
    display: flex;
    flex-direction: column;
    margin-bottom: 6px;
}
.price-stack {
    display: flex;
    flex-direction: column;
}
.price-main-line {
    display: flex;
    align-items: baseline;
    gap: 4px;
}
.price-num {
    font-family: var(--font-heading);
    font-size: 2.3rem;
    font-weight: 800;
    color: #1e3a8a;
    line-height: 1.1;
}
.price-unit {
    font-size: 0.95rem;
    color: #475569;
    font-weight: 600;
}
.walkin-price-card .price-base-rm {
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    margin-top: 2px;
}
.price-card-hint {
    font-size: 0.85rem;
    color: #1e40af;
    line-height: 1.4;
    font-weight: 500;
}

.walkin-short-desc {
    font-size: 0.92rem;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 22px;
}

/* Quantity & Add to Cart */
.walkin-add-form {
    margin-bottom: 26px;
}
.qty-and-add-row {
    display: flex;
    gap: 16px;
    align-items: flex-end;
    flex-wrap: wrap;
}
.qty-picker-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.qty-picker-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    font-weight: 700;
    color: #64748b;
}
.qty-picker {
    display: inline-flex;
    align-items: center;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    overflow: hidden;
    height: 48px;
}
.qty-btn {
    background: none;
    border: none;
    color: #0f172a;
    font-size: 1.25rem;
    font-weight: 700;
    width: 44px;
    height: 100%;
    cursor: pointer;
    transition: background 0.15s;
}
.qty-btn:hover {
    background: #f1f5f9;
}
.qty-picker input {
    width: 50px;
    text-align: center;
    border: none;
    background: none;
    font-weight: 800;
    font-size: 1.1rem;
    color: #0f172a;
}
.qty-picker input:focus { outline: none; }

.btn-walkin-add-main {
    flex: 1;
    min-width: 200px;
    height: 48px;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    transition: all 0.2s ease;
}
.btn-walkin-add-main:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
}

/* Specs Card */
.walkin-specs-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
}
.specs-card-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 14px 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
}
.specs-grid {
    display: grid;
    grid-template-columns: 140px 1fr;
    row-gap: 10px;
    column-gap: 16px;
    font-size: 0.88rem;
}
.spec-name {
    color: #64748b;
    font-weight: 500;
}
.spec-val {
    color: #0f172a;
    font-weight: 600;
}

/* Long Description */
.walkin-long-desc {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
}
.long-desc-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 12px 0;
}
.long-desc-body {
    font-size: 0.9rem;
    line-height: 1.7;
    color: #475569;
}

/* Responsive */
@media (max-width: 860px) {
    .walkin-detail-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
}

/* ─── Get Price Modal Styling ─── */
.get-price-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    padding: 16px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.25s ease;
}
.get-price-modal-backdrop.show {
    opacity: 1;
    pointer-events: auto;
}
.get-price-modal-dialog {
    background: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 460px;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.25);
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transform: scale(0.95);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.get-price-modal-backdrop.show .get-price-modal-dialog {
    transform: scale(1);
}
.get-price-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: linear-gradient(135deg, #091a36 0%, #1e3a8a 100%);
    color: #ffffff;
}
.get-price-modal-title {
    font-size: 1rem;
    font-weight: 800;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 6px;
}
.get-price-modal-close-btn {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #ffffff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 700;
    transition: background 0.15s;
}
.get-price-modal-close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}
.get-price-modal-body {
    padding: 22px;
}
.get-price-product-info {
    margin-bottom: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
}
.get-price-product-name {
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.3;
}
.get-price-product-sku {
    font-size: 0.78rem;
    color: #64748b;
    margin-top: 4px;
    font-weight: 600;
}
.get-price-revealed-card {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border: 1.5px solid #bfdbfe;
    border-radius: 14px;
    padding: 16px 18px;
    margin-bottom: 18px;
}
.get-price-card-label {
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #1d4ed8;
    margin-bottom: 4px;
}
.get-price-card-amount {
    display: flex;
    align-items: baseline;
    gap: 6px;
    margin-bottom: 6px;
}
.modal-price-num {
    font-family: var(--font-heading);
    font-size: 1.85rem;
    font-weight: 900;
    color: #1e3a8a;
    line-height: 1.1;
}
.modal-price-unit {
    font-size: 0.9rem;
    font-weight: 600;
    color: #475569;
}
.get-price-card-hint {
    font-size: 0.8rem;
    color: #1e40af;
    line-height: 1.4;
}
.get-price-actions-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 14px;
}
.btn-unlock-all-prices {
    width: 100%;
    padding: 12px 18px;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.92rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    transition: transform 0.15s, box-shadow 0.15s;
}
.btn-unlock-all-prices:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.45);
}
.btn-whatsapp-inquire {
    width: 100%;
    padding: 12px 18px;
    background: #25d366;
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.92rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
    transition: transform 0.15s, filter 0.15s;
}
.btn-whatsapp-inquire:hover {
    filter: brightness(1.05);
    transform: translateY(-1px);
    color: #ffffff;
}
.get-price-guarantee-note {
    font-size: 0.75rem;
    color: #64748b;
    text-align: center;
    line-height: 1.4;
}
</style>
@endpush

@push('scripts')
<script>
function switchMainImage(thumb) {
    document.getElementById('mainImage').src = thumb.src;
    document.querySelectorAll('.walkin-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}

// ─── Walk-in Price Unlock Flow Controller ───
const WALKIN_STORAGE_KEY = 'mst_walkin_price_unlocked';

function isWalkinUnlocked() {
    return localStorage.getItem(WALKIN_STORAGE_KEY) === '1';
}

function applyWalkinUnlockState() {
    const unlocked = isWalkinUnlocked();
    const unlockedEls = document.querySelectorAll('.walkin-price-unlocked-block');
    const lockedEls = document.querySelectorAll('.walkin-price-locked-block');

    if (unlocked) {
        unlockedEls.forEach(el => {
            el.style.setProperty('display', el.classList.contains('walkin-add-form') ? 'block' : 'flex', 'important');
        });
        lockedEls.forEach(el => el.style.setProperty('display', 'none', 'important'));
    } else {
        unlockedEls.forEach(el => el.style.setProperty('display', 'none', 'important'));
        lockedEls.forEach(el => el.style.setProperty('display', 'block', 'important'));
    }
}

function openGetPriceModal(name, sku, price, unit, productId) {
    const backdrop = document.getElementById('getPriceModalBackdrop');
    if (backdrop) {
        backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeGetPriceModal() {
    const backdrop = document.getElementById('getPriceModalBackdrop');
    if (backdrop) {
        backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }
}

function handleGetPriceModalBackdropClick(event) {
    if (event.target === document.getElementById('getPriceModalBackdrop')) {
        closeGetPriceModal();
    }
}

function unlockWalkinPrices() {
    localStorage.setItem(WALKIN_STORAGE_KEY, '1');
    applyWalkinUnlockState();
    closeGetPriceModal();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGetPriceModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    applyWalkinUnlockState();
});
applyWalkinUnlockState();
</script>
@endpush
