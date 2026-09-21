@extends('layouts.app')
@section('title', $product->name . ' (' . __t('walkin.walkin_title', 'Walk-in') . ') — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd'))

@section('content')
<!-- Walk-in Ocean Header Banner -->
<div class="walkin-hero-section" style="position:relative;background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-top:calc(75px + var(--space-6));padding-bottom:var(--space-6);overflow:hidden">
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
                    <span class="badge-tag-cyan">❄️ {{ $product->storage_temp }} IQF</span>
                @endif
            </div>

            <h2 class="walkin-detail-title">{{ $product->name }}</h2>

            @if($product->sku)
                <div class="walkin-detail-sku">SKU: <strong>{{ $product->sku }}</strong></div>
            @endif

            <!-- In-Store Price Box -->
            <div class="walkin-price-card">
                <div class="price-card-label">@t('walkin.in_store_price', 'In-Store Walk-in Price')</div>
                <div class="price-card-value-row">
                    <span class="price-num">RM {{ number_format($price, 2) }}</span>
                    <span class="price-unit">/ {{ $product->unit ?? 'pack' }}</span>
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

            <!-- Add to Walk-in Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST" class="walkin-add-form">
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
                        <div class="spec-val">{{ $product->storage_temp }}</div>
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
    align-items: baseline;
    gap: 8px;
    margin-bottom: 6px;
}
.price-num {
    font-family: var(--font-heading);
    font-size: 2.3rem;
    font-weight: 800;
    color: #1e3a8a;
}
.price-unit {
    font-size: 0.95rem;
    color: #475569;
    font-weight: 600;
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
</style>
@endpush

@push('scripts')
<script>
function switchMainImage(thumb) {
    document.getElementById('mainImage').src = thumb.src;
    document.querySelectorAll('.walkin-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
</script>
@endpush
