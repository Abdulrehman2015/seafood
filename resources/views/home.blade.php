@extends('layouts.app')

@section('title', 'MST Import and Export Sdn Bhd — Premium Frozen Seafood Malaysia & Singapore')
@section('meta_description', 'Shop premium frozen seafood online. Retail, wholesale, and trading accounts available. Salmon, prawns, crabs, squid and more.')

@section('content')

<!-- ─── Hero Section ────────────────────────────────────────────────────── -->
<section class="mika-hero">

    {{-- Animated Background --}}
    <div class="mika-hero-bg">
        <div class="mika-orb mika-orb-1"></div>
        <div class="mika-orb mika-orb-2"></div>
        <div class="mika-orb mika-orb-3"></div>
        <div class="mika-grid-overlay"></div>
    </div>

    <div class="container mika-hero-container">

        {{-- LEFT: Text Content --}}
        <div class="mika-hero-left">

            {{-- Live badge --}}
            <div class="mika-live-badge">
                <span class="mika-live-dot"></span>
                Malaysia's Trusted Seafood Importer &amp; Exporter
            </div>

            <h1 class="mika-hero-h1">
                Premium Frozen<br>
                <span class="mika-hero-gradient">Seafood, Direct</span><br>
                <span class="mika-hero-gradient">To Your Door</span>
            </h1>

            <p class="mika-hero-sub">
                Wholesale, retail &amp; trading accounts on one platform.
                IQF frozen seafood — Halal &amp; HACCP certified — delivered
                across Malaysia.
            </p>

            {{-- Customer type pills --}}
            <div class="mika-type-pills">
                <span class="mika-pill">🛒 Retail</span>
                <span class="mika-pill">🏪 Walk-in</span>
                <span class="mika-pill">🏭 Wholesale</span>
                <span class="mika-pill">📦 Trading</span>
            </div>

            {{-- CTAs --}}
            <div class="mika-hero-cta">
                <a href="{{ route('shop.index') }}" class="mika-btn-primary">
                    Shop Now
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                @guest
                    <a href="{{ route('register') }}" class="mika-btn-ghost">Register Account</a>
                @else
                    <a href="{{ route('account.dashboard') }}" class="mika-btn-ghost">My Dashboard</a>
                @endguest
            </div>

            {{-- Stats bar --}}
            <div class="mika-stats-bar">
                <div class="mika-stat-item">
                    <div class="mika-stat-num">200+</div>
                    <div class="mika-stat-lbl">Products</div>
                </div>
                <div class="mika-stat-divider"></div>
                <div class="mika-stat-item">
                    <div class="mika-stat-num">4</div>
                    <div class="mika-stat-lbl">Customer Tiers</div>
                </div>
                <div class="mika-stat-divider"></div>
                <div class="mika-stat-item">
                    <div class="mika-stat-num">IQF</div>
                    <div class="mika-stat-lbl">Frozen Quality</div>
                </div>
                <div class="mika-stat-divider"></div>
                <div class="mika-stat-item">
                    <div class="mika-stat-num">B2B</div>
                    <div class="mika-stat-lbl">&amp; B2C</div>
                </div>
            </div>

            {{-- Trust badges --}}
            <div class="mika-trust-row">
                <div class="mika-trust-badge">✅ Halal Certified</div>
                <div class="mika-trust-badge">🔬 HACCP Compliant</div>
                <div class="mika-trust-badge">❄️ Cold-Chain Guaranteed</div>
            </div>
        </div>

        {{-- RIGHT: Hero Image --}}
        <div class="mika-hero-right">
            <div class="mika-image-frame">
                <img src="{{ asset('images/hero-banner.jpg') }}"
                     alt="Premium frozen seafood — MST Import and Export Sdn Bhd"
                     class="mika-hero-img">
                {{-- Floating feature cards --}}
                <div class="mika-float-card mika-float-top">
                    <span style="font-size:1.4rem">🐟</span>
                    <div>
                        <div style="font-weight:700;font-size:0.8rem;color:#0f172a">Fresh Catch Daily</div>
                        <div style="font-size:0.7rem;color:#64748b">IQF Frozen at Source</div>
                    </div>
                </div>
                <div class="mika-float-card mika-float-bottom">
                    <span style="font-size:1.4rem">❄️</span>
                    <div>
                        <div style="font-weight:700;font-size:0.8rem;color:#0f172a">-18°C Cold Chain</div>
                        <div style="font-size:0.7rem;color:#64748b">Maintained end-to-end</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Scroll indicator --}}
    <div class="mika-scroll-hint">
        <div class="mika-scroll-line"></div>
        <span>Scroll</span>
    </div>

</section>

<style>
/* ════════════════════════════════════════
   MIKA HERO — Premium Banner
   Color: Royal Blue / Deep Navy
   ════════════════════════════════════════ */

.mika-hero {
    position: relative;
    min-height: calc(100vh - 80px);
    display: flex;
    align-items: center;
    overflow: hidden;
    background: linear-gradient(135deg, #06152b 0%, #0c2146 40%, #14356b 75%, #1d4ed8 100%);
    padding-top: 105px;
    padding-bottom: 40px;
    box-sizing: border-box;
}

/* ── Animated background glows ── */
.mika-hero-bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
}

.mika-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    opacity: 0.45;
}

.mika-orb-1 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.55) 0%, transparent 70%);
    top: -150px; right: -100px;
    animation: mikaFloat1 10s ease-in-out infinite;
}

.mika-orb-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(96, 165, 250, 0.40) 0%, transparent 70%);
    bottom: 50px; left: -80px;
    animation: mikaFloat2 13s ease-in-out infinite;
}

.mika-orb-3 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(29, 78, 216, 0.35) 0%, transparent 70%);
    top: 40%; left: 35%;
    animation: mikaFloat3 16s ease-in-out infinite;
}

.mika-grid-overlay {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}

@keyframes mikaFloat1 { 0%,100%{transform:translate(0,0) scale(1)} 50%{transform:translate(-30px,30px) scale(1.05)} }
@keyframes mikaFloat2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(25px,-25px)} }
@keyframes mikaFloat3 { 0%,100%{transform:translate(0,0) scale(1)} 50%{transform:translate(-15px,20px) scale(1.08)} }

/* ── Layout ── */
.mika-hero-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    position: relative;
    z-index: 2;
    padding-top: 40px;
    padding-bottom: 80px;
}

/* ── Left: Text ── */
.mika-hero-left {
    color: #ffffff;
}

/* Live badge */
.mika-live-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.18);
    backdrop-filter: blur(12px);
    border-radius: 999px;
    padding: 6px 16px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #93c5fd;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    margin-bottom: 24px;
}

.mika-live-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22d3ee;
    box-shadow: 0 0 0 0 rgba(34, 211, 238, 0.6);
    animation: mikaPulse 2s infinite;
    flex-shrink: 0;
}

@keyframes mikaPulse {
    0% { box-shadow: 0 0 0 0 rgba(34, 211, 238, 0.6); }
    70% { box-shadow: 0 0 0 10px rgba(34, 211, 238, 0); }
    100% { box-shadow: 0 0 0 0 rgba(34, 211, 238, 0); }
}

/* H1 */
.mika-hero-h1 {
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: clamp(2.2rem, 4vw, 3.8rem);
    font-weight: 800;
    line-height: 1.12;
    color: #ffffff;
    margin-bottom: 20px;
    letter-spacing: -0.03em;
}

.mika-hero-gradient {
    background: linear-gradient(135deg, #60a5fa 0%, #93c5fd 50%, #bfdbfe 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Subtitle */
.mika-hero-sub {
    font-size: 1.05rem;
    color: rgba(255,255,255,0.75);
    line-height: 1.75;
    max-width: 520px;
    margin-bottom: 28px;
}

/* Type pills */
.mika-type-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 32px;
}

.mika-pill {
    padding: 5px 14px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 600;
    color: rgba(255,255,255,0.9);
    backdrop-filter: blur(8px);
    transition: all 0.2s ease;
    cursor: default;
}

.mika-pill:hover {
    background: rgba(255,255,255,0.15);
    border-color: rgba(255,255,255,0.3);
    transform: translateY(-1px);
}

/* CTA Buttons */
.mika-hero-cta {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 40px;
}

.mika-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #ffffff;
    font-weight: 700;
    font-size: 0.95rem;
    border-radius: 12px;
    text-decoration: none;
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.45), 0 2px 6px rgba(0,0,0,0.2);
    transition: all 0.25s ease;
    border: 1px solid rgba(255,255,255,0.15);
}

.mika-btn-primary:hover {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(37, 99, 235, 0.55);
    color: #ffffff;
}

.mika-btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.9);
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 12px;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(10px);
    transition: all 0.25s ease;
}

.mika-btn-ghost:hover {
    background: rgba(255,255,255,0.15);
    border-color: rgba(255,255,255,0.45);
    transform: translateY(-2px);
    color: #ffffff;
}

/* Stats bar */
.mika-stats-bar {
    display: flex;
    gap: 0;
    padding: 20px 0;
    border-top: 1px solid rgba(255,255,255,0.12);
    margin-bottom: 20px;
}

.mika-stat-item {
    flex: 1;
    padding: 0 20px 0 0;
}

.mika-stat-num {
    font-family: 'Outfit', sans-serif;
    font-size: 1.7rem;
    font-weight: 800;
    color: #60a5fa;
    line-height: 1;
    margin-bottom: 4px;
}

.mika-stat-lbl {
    font-size: 0.72rem;
    color: rgba(255,255,255,0.5);
    font-weight: 500;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.mika-stat-divider {
    width: 1px;
    background: rgba(255,255,255,0.12);
    margin: 0 20px;
    align-self: stretch;
}

/* Trust badges */
.mika-trust-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.mika-trust-badge {
    font-size: 0.73rem;
    font-weight: 600;
    color: rgba(255,255,255,0.65);
    padding: 4px 10px;
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 6px;
    background: rgba(255,255,255,0.04);
}

/* ── Right: Image ── */
.mika-hero-right {
    display: flex;
    justify-content: center;
    align-items: center;
}

.mika-image-frame {
    position: relative;
    width: 100%;
    max-width: 560px;
    margin: 0 auto;
}

.mika-hero-img {
    width: 100%;
    height: 520px;
    object-fit: cover;
    border-radius: 24px;
    display: block;
    box-shadow:
        0 32px 80px rgba(0,0,0,0.5),
        0 8px 24px rgba(0,0,0,0.3),
        inset 0 1px 0 rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.12);
    animation: mikaImgFloat 8s ease-in-out infinite;
}

@keyframes mikaImgFloat {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
}

/* Floating cards on image */
.mika-float-card {
    position: absolute;
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(16px);
    border-radius: 14px;
    padding: 10px 16px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    border: 1px solid rgba(255,255,255,0.85);
    animation: mikaImgFloat 8s ease-in-out infinite;
    z-index: 5;
    pointer-events: none;
}

.mika-float-top {
    top: 24px;
    left: -16px;
    animation-delay: -2s;
}

.mika-float-bottom {
    bottom: 24px;
    right: -16px;
    animation-delay: -4s;
}

/* ── Scroll indicator ── */
.mika-scroll-hint {
    position: absolute;
    bottom: 28px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    color: rgba(255,255,255,0.4);
    font-size: 0.65rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    animation: mikaScrollFade 3s ease-in-out infinite;
}

.mika-scroll-line {
    width: 1px;
    height: 36px;
    background: linear-gradient(180deg, rgba(255,255,255,0.5) 0%, transparent 100%);
}

@keyframes mikaScrollFade {
    0%, 100% { opacity: 0.4; transform: translateX(-50%) translateY(0); }
    50% { opacity: 0.8; transform: translateX(-50%) translateY(4px); }
}

/* ── Responsive ── */
@media (max-width: 1024px) {
    .mika-hero {
        min-height: auto;
        padding-top: 105px;
        padding-bottom: 50px;
    }

    .mika-hero-container {
        grid-template-columns: 1fr;
        gap: 36px;
        padding-top: 16px;
        padding-bottom: 30px;
    }

    .mika-hero-right {
        order: -1;
    }

    .mika-image-frame {
        max-width: 500px;
    }

    .mika-hero-img {
        height: 340px;
    }

    .mika-float-top {
        left: 16px;
        top: 16px;
    }

    .mika-float-bottom {
        right: 16px;
        bottom: 16px;
    }

    .mika-scroll-hint {
        display: none;
    }
}

@media (max-width: 640px) {
    .mika-hero {
        min-height: auto;
        padding-top: 98px;
        padding-bottom: 36px;
    }

    .mika-hero-container {
        gap: 24px;
        padding-top: 12px;
        padding-bottom: 16px;
    }

    .mika-hero-h1 {
        font-size: clamp(1.85rem, 6.5vw, 2.35rem);
        line-height: 1.15;
        margin-bottom: 14px;
    }

    .mika-hero-sub {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .mika-image-frame {
        max-width: 100%;
    }

    .mika-hero-img {
        height: 240px;
        border-radius: 18px;
    }

    .mika-float-card {
        padding: 8px 12px;
        border-radius: 10px;
        gap: 8px;
    }

    .mika-float-card span {
        font-size: 1.15rem !important;
    }

    .mika-float-top {
        top: 10px;
        left: 10px;
    }

    .mika-float-bottom {
        bottom: 10px;
        right: 10px;
    }

    .mika-type-pills {
        gap: 6px;
        margin-bottom: 22px;
    }

    .mika-pill {
        padding: 4px 10px;
        font-size: 0.72rem;
    }

    .mika-hero-cta {
        flex-direction: column;
        gap: 10px;
        margin-bottom: 24px;
    }

    .mika-btn-primary,
    .mika-btn-ghost {
        width: 100%;
        justify-content: center;
        text-align: center;
        padding: 12px 18px;
        font-size: 0.92rem;
    }

    .mika-stats-bar {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        padding: 14px 0;
        border-top: 1px solid rgba(255,255,255,0.12);
        margin-bottom: 16px;
    }

    .mika-stat-divider {
        display: none;
    }

    .mika-stat-item {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 12px;
        padding: 10px 12px;
    }

    .mika-stat-num {
        font-size: 1.4rem;
        margin-bottom: 2px;
    }

    .mika-stat-lbl {
        font-size: 0.68rem;
    }

    .mika-trust-row {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .mika-trust-badge {
        font-size: 0.7rem;
        padding: 4px 8px;
    }
}
</style>



<!-- ─── Category Section ─────────────────────────────────────────────────── -->
@if($categories->count())
<section class="section category-section" style="padding-top: var(--space-16); padding-bottom: var(--space-12);">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">Browse by Category</div>
            <h2 class="section-title">What Are You Looking For?</h2>
            <p class="section-subtitle">From premium salmon to sea-caught prawns — explore our comprehensive selection of IQF frozen seafood.</p>
        </div>

        <div class="categories-grid">
            @php
                $icons = [
                    'fish' => '🐟',
                    'prawns-shrimps' => '🦐',
                    'prawns' => '🦐',
                    'squid' => '🦑',
                    'crab' => '🦀',
                    'shellfish' => '🦪',
                    'seafood-products' => '🦞',
                    'fish-fillet' => '🐠',
                    'other-frozen-seafood' => '🌊',
                    'steamboat' => '🍲',
                    'meat-chicken' => '🍗',
                    'meat-lamb' => '🥩',
                    'meat-beef' => '🥩',
                    'meat-duck' => '🦆',
                    'frozen-product-food' => '🥟',
                    'dimsum' => '🥟',
                    'ready-to-eat' => '🍱',
                    'snack-food' => '🍿',
                    'dessert' => '🍦',
                ];
            @endphp
            @foreach($categories as $cat)
            <a href="{{ $cat->url }}" class="category-card">
                @if($cat->image)
                    <div class="category-img-wrap">
                        <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}">
                    </div>
                @else
                    <span class="category-icon">{{ $icons[$cat->slug] ?? '🌊' }}</span>
                @endif
                <div class="category-name">{{ $cat->name }}</div>
                <span class="category-count">{{ $cat->products()->active()->count() }} products</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ─── Featured Products ─────────────────────────────────────────────────── -->
@if($featuredProducts->count())
<section class="section featured-products-section" style="padding: var(--space-16) 0;">
    <div class="container">

        {{-- Section Header with inline "View All" on desktop --}}
        <div style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom: var(--space-8);">
            <div>
                <div class="section-eyebrow" style="margin-bottom:8px;">
                    ⭐ Featured Products
                </div>
                <h2 style="font-family:var(--font-heading);font-size:clamp(1.6rem,4vw,2.25rem);font-weight:800;color:var(--text-primary);margin:0 0 8px;">
                    Our Best Sellers
                </h2>
                <p style="color:var(--text-muted);font-size:0.95rem;margin:0;">
                    @if(auth()->check())
                        Showing <strong style="color:#1d4ed8;">{{ ucfirst($group) }} prices</strong> for your account.
                    @else
                        Retail prices shown. <a href="{{ route('register') }}" style="color:#1d4ed8;font-weight:600;">Register</a> for wholesale/trading rates.
                    @endif
                </p>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-secondary" style="white-space:nowrap;flex-shrink:0;">
                View All Products →
            </a>
        </div>

        <div class="products-grid">
            @foreach($featuredProducts as $product)
                @php
                    $displayPrice = $product->getDisplayPrice($group);
                    $price = $displayPrice['amount'];
                @endphp
                <div class="product-card">
                    <div class="product-card-img">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy">
                        @else
                            <div class="product-img-placeholder">🐟</div>
                        @endif
                        @if($product->is_featured)
                            <span class="product-badge badge-featured">⭐ Featured</span>
                        @endif
                        @if($product->stock_quantity <= 5 && $product->track_stock)
                            <span class="product-badge badge-low-stock" style="top:auto;bottom:10px;left:10px;">Low Stock</span>
                        @endif
                    </div>
                    <div class="product-card-body">
                        <div class="product-category">{{ $product->category?->name ?? 'Seafood' }}</div>
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <div class="product-meta">
                            @if($product->weight)
                                <span class="product-meta-item">⚖ {{ $product->weight }}</span>
                            @endif
                            @if($product->origin)
                                <span class="product-meta-item">🌍 {{ $product->origin }}</span>
                            @endif
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
                                    <span class="price-amount">{{ $displayPrice['formatted'] }}</span>
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
                            <a href="{{ route('shop.show', $product) }}" class="btn btn-secondary btn-sm action-btn-view">View</a>
                            @if($price !== null)
                                <form action="{{ route('cart.add') }}" method="POST" class="action-form-cart">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="{{ $product->getMoqForGroup($group) }}">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Add to Cart</button>
                                </form>
                            @elseif(auth()->check() && auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                                <a href="{{ route('quotations.create', ['product' => $product->id]) }}" class="btn btn-primary btn-sm action-btn-quote">Request RFQ</a>
                            @else
                                <a href="{{ route('shop.show', $product) }}" class="btn btn-secondary btn-sm action-btn-quote">View Product</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Mobile-only bottom link --}}
        <div class="mobile-view-all" style="display:none;text-align:center;margin-top:20px;">
            <a href="{{ route('shop.index') }}" class="btn btn-secondary" style="width:100%;max-width:320px;">
                View All Products →
            </a>
        </div>
    </div>
</section>
@endif

<!-- ─── How It Works ────────────────────────────────────────────────────── -->
<section class="section" style="background: linear-gradient(180deg, transparent, rgba(20,160,165,0.05))">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">How It Works</div>
            <h2 class="section-title">Built for Every Customer</h2>
        </div>
        <div class="how-it-works-grid">
            <div class="glass-card p-6" style="text-align:center">
                <div style="font-size:2.5rem;margin-bottom:var(--space-4)">🛒</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:var(--space-3);font-size:1.1rem">Retail</h3>
                <p class="text-sm text-muted">Register free, browse at retail prices, add to cart and pay online. Delivery or self-collection.</p>
            </div>
            <div class="glass-card p-6" style="text-align:center">
                <div style="font-size:2.5rem;margin-bottom:var(--space-4)">📱</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:var(--space-3);font-size:1.1rem">Walk-in</h3>
                <p class="text-sm text-muted">Scan our QR code in-store to access walk-in prices. Pay and self-collect — no registration needed.</p>
            </div>
            <div class="glass-card p-6" style="text-align:center">
                <div style="font-size:2.5rem;margin-bottom:var(--space-4)">🏭</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:var(--space-3);font-size:1.1rem">Wholesale</h3>
                <p class="text-sm text-muted">Register a wholesale account. After approval, log in to see exclusive wholesale prices and MOQ.</p>
            </div>
            <div class="glass-card p-6" style="text-align:center">
                <div style="font-size:2.5rem;margin-bottom:var(--space-4)">📦</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:var(--space-3);font-size:1.1rem">Trading</h3>
                <p class="text-sm text-muted">Register a trading account. Get trading prices, bulk orders, and Request for Quotation for market-priced items.</p>
            </div>
        </div>
    </div>
</section>

<!-- ─── Cold-Chain & Quality Guarantee ─────────────────────────────────── -->
<section class="section" style="padding:var(--space-16) 0;background:#ffffff">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow" style="color:var(--seagreen-700)">OUR COMMITMENT TO QUALITY</div>
            <h2 class="section-title">The Mika Cold-Chain Promise</h2>
            <p class="section-subtitle">From point of catch to your doorstep, our uncompromising cold-chain integrity ensures peak freshness and taste.</p>
        </div>

        <div class="cold-chain-grid">
            <div class="card" style="padding:var(--space-6);border-radius:14px;border:1px solid #e2e8f0;background:#f8fafc;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#059669">
                    ⚡
                </div>
                <h3 style="font-size:1.15rem;font-weight:700;color:var(--gray-900);margin-bottom:8px">Ultra-Rapid IQF Freezing</h3>
                <p style="font-size:0.875rem;color:var(--gray-600);line-height:1.6;margin:0">
                    Individual Quick Freezing locks in moisture, nutrients, and cell structure within minutes of harvest, preventing icicle crystallization.
                </p>
            </div>

            <div class="card" style="padding:var(--space-6);border-radius:14px;border:1px solid #e2e8f0;background:#f8fafc;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#2563eb">
                    🚚
                </div>
                <h3 style="font-size:1.15rem;font-weight:700;color:var(--gray-900);margin-bottom:8px">Strict -18°C Logistics</h3>
                <p style="font-size:0.875rem;color:var(--gray-600);line-height:1.6;margin:0">
                    Our dedicated temperature-monitored refrigerated fleet guarantees zero thermal shock during transit throughout Klang Valley and beyond.
                </p>
            </div>

            <div class="card" style="padding:var(--space-6);border-radius:14px;border:1px solid #e2e8f0;background:#f8fafc;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#d97706">
                    🛡️
                </div>
                <h3 style="font-size:1.15rem;font-weight:700;color:var(--gray-900);margin-bottom:8px">Halal & HACCP Sourcing</h3>
                <p style="font-size:0.875rem;color:var(--gray-600);line-height:1.6;margin:0">
                    Strict adherence to international food safety hygiene, certified Halal slaughtering standards, and full regulatory traceability.
                </p>
            </div>

            <div class="card" style="padding:var(--space-6);border-radius:14px;border:1px solid #e2e8f0;background:#f8fafc;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#f5f3ff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#7c3aed">
                    🏪
                </div>
                <h3 style="font-size:1.15rem;font-weight:700;color:var(--gray-900);margin-bottom:8px">Iskandar Puteri Showroom</h3>
                <p style="font-size:0.875rem;color:var(--gray-600);line-height:1.6;margin:0">
                    Visit our central facility in SILC Industrial Area, Iskandar Puteri, Johor for immediate counter pickups, wholesale inspection, and expert culinary recommendations from our team.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ─── Fresh Catch of the Week Banner ──────────────────────────────────── -->
<section class="section" style="padding:var(--space-8) 0 var(--space-14)">
    <div class="container">
        <div class="fresh-catch-card">
            <div style="position:absolute;right:-50px;bottom:-50px;width:300px;height:300px;background:rgba(255,255,255,0.04);border-radius:50%;pointer-events:none"></div>
            
            <div class="fresh-catch-grid">
                <div>
                    <span style="background:rgba(255,255,255,0.18);color:#dbeafe;font-size:0.75rem;font-weight:700;padding:4px 12px;border-radius:9999px;text-transform:uppercase;letter-spacing:0.06em;display:inline-block;margin-bottom:12px">
                        ✨ Premium Sourcing Highlight
                    </span>
                    <h2 style="font-family:var(--font-heading);font-size:clamp(1.4rem, 3.5vw, 2rem);color:white;margin-bottom:12px;line-height:1.25">
                        Restaurant-Grade Salmon, Prawns & Scallops Ready for Delivery
                    </h2>
                    <p style="color:#dbeafe;font-size:1rem;line-height:1.6;max-width:560px;margin-bottom:var(--space-6)">
                        Whether preparing an intimate weekend family dinner or stocking a high-volume commercial kitchen, our catalog delivers pristine seafood with guaranteed cold-chain arrival.
                    </p>
                    <div class="fresh-catch-actions">
                        <a href="{{ route('shop.index') }}" class="btn btn-primary" style="background:#ffffff;border-color:#ffffff;color:#1d4ed8;font-weight:700;padding:12px 24px">
                            Explore Full Catalogue →
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-secondary" style="background:rgba(255,255,255,0.15);color:white;border-color:rgba(255,255,255,0.3);padding:12px 20px">
                            Speak to Our Specialists
                        </a>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:12px">
                    <div style="background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:14px">
                        <span style="font-size:1.8rem">🐟</span>
                        <div>
                            <div style="font-weight:700;font-size:0.95rem">Sashimi-Grade Yellowfin & Salmon</div>
                            <div style="font-size:0.8rem;color:#bfdbfe">Ultra-fresh vacuum packed portions</div>
                        </div>
                    </div>
                    <div style="background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:14px">
                        <span style="font-size:1.8rem">🦐</span>
                        <div>
                            <div style="font-weight:700;font-size:0.95rem">Sea-Caught Black Tiger Prawns</div>
                            <div style="font-size:0.8rem;color:#bfdbfe">Zero preservatives, crisp natural texture</div>
                        </div>
                    </div>
                    <div style="background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:14px">
                        <span style="font-size:1.8rem">📦</span>
                        <div>
                            <div style="font-weight:700;font-size:0.95rem">Wholesale Pallet & Master Cartons</div>
                            <div style="font-size:0.8rem;color:#bfdbfe">Tiered wholesale discounts for F&B operators</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── Dynamic Customer Reviews Section ─────────────────────────────────── -->
<x-reviews-section :reviews="$reviews" />

<!-- ─── Culinary Advisory & Newsletter Section ───────────────────────────── -->
<section class="section" style="padding:var(--space-16) 0;background:#f8fafc;border-top:1px solid #e2e8f0">
    <div class="container">
        <div class="culinary-advisory-grid">
            <div>
                <span class="section-eyebrow" style="color:var(--seagreen-700)">SEAFOOD TIPS & INSIGHTS</span>
                <h2 style="font-family:var(--font-heading);font-size:1.85rem;color:var(--gray-900);margin-bottom:14px">
                    Mastering Frozen Seafood Preparation
                </h2>
                <p style="color:var(--gray-600);line-height:1.7;margin-bottom:18px">
                    Freezing seals freshness at peak moment. For the best culinary results, thaw slowly in your refrigerator overnight (0°C to 4°C) to allow moisture re-absorption. Pat dry thoroughly with paper towels before searing or grilling to achieve that golden crust.
                </p>
                <div style="display:flex;gap:16px;flex-wrap:wrap">
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.85rem;color:var(--gray-700);font-weight:600">
                        <span style="color:#059669">✓</span> Zero Chemical Preservatives
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.85rem;color:var(--gray-700);font-weight:600">
                        <span style="color:#059669">✓</span> 100% Net Weight Guaranteed
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.85rem;color:var(--gray-700);font-weight:600">
                        <span style="color:#059669">✓</span> Food-Grade Sealed Packaging
                    </div>
                </div>
            </div>

            <!-- Newsletter Box -->
            <div class="card" style="background:white;border:1px solid #e2e8f0;border-radius:18px;padding:32px;box-shadow:0 8px 30px rgba(0,0,0,0.04)">
                <div style="font-size:2rem;margin-bottom:10px">✉️</div>
                <h3 style="font-size:1.3rem;font-weight:700;color:var(--gray-900);margin-bottom:6px">Join Our Catch Updates</h3>
                <p style="font-size:0.85rem;color:var(--gray-500);line-height:1.5;margin-bottom:18px">
                    Subscribe for seasonal catch alerts, wholesale promotions, and exclusive recipes from our culinary partners.
                </p>
                
                <form id="newsletterForm" onsubmit="handleNewsletterSubmit(event)" style="display:flex;flex-direction:column;gap:10px">
                    @csrf
                    <div style="position:relative">
                        <input type="email" id="newsletterEmail" name="email" placeholder="Enter your email address..." required class="form-control" style="border-radius:8px;height:44px;font-size:0.9rem;width:100%">
                    </div>
                    <button type="submit" id="newsletterBtn" class="btn btn-primary" style="height:44px;font-weight:700;border-radius:8px">
                        Subscribe for Updates
                    </button>
                    <div id="newsletterMsg" style="display:none;font-size:0.85rem;padding:8px 12px;border-radius:6px;text-align:center"></div>
                    <span style="font-size:0.75rem;color:var(--gray-400);text-align:center">We respect your privacy. Unsubscribe at any time.</span>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
async function handleNewsletterSubmit(e) {
    e.preventDefault();
    const emailInput = document.getElementById('newsletterEmail');
    const submitBtn = document.getElementById('newsletterBtn');
    const msgDiv = document.getElementById('newsletterMsg');
    const email = emailInput.value.trim();

    if (!email) return;

    submitBtn.disabled = true;
    const origText = submitBtn.innerHTML;
    submitBtn.innerHTML = '⏳ Subscribing...';
    msgDiv.style.display = 'none';

    try {
        const res = await fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        });

        const data = await res.json();

        if (res.ok && data.success) {
            msgDiv.style.display = 'block';
            msgDiv.style.background = '#dcfce7';
            msgDiv.style.color = '#15803d';
            msgDiv.style.border = '1px solid #bbf7d0';
            msgDiv.innerHTML = '✓ ' + data.message;
            emailInput.value = '';
            submitBtn.style.background = '#059669';
            submitBtn.innerHTML = '✓ Subscribed!';
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.style.background = '';
                submitBtn.innerHTML = origText;
            }, 3000);
        } else {
            msgDiv.style.display = 'block';
            msgDiv.style.background = '#fee2e2';
            msgDiv.style.color = '#b91c1c';
            msgDiv.style.border = '1px solid #fecaca';
            msgDiv.innerHTML = '⚠ ' + (data.message || 'Could not process subscription. Please check your email.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = origText;
        }
    } catch(err) {
        msgDiv.style.display = 'block';
        msgDiv.style.background = '#fee2e2';
        msgDiv.style.color = '#b91c1c';
        msgDiv.style.border = '1px solid #fecaca';
        msgDiv.innerHTML = '⚠ An error occurred. Please try again.';
        submitBtn.disabled = false;
        submitBtn.innerHTML = origText;
    }
}
</script>
@endpush
