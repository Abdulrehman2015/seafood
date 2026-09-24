@extends('layouts.app')

@section('title', 'MST Import & Export | Frozen Food Sourcing & Trading')
@section('meta_description', 'MST Import and Export provides frozen food sourcing, wholesale trading and cold-chain distribution for restaurants, retailers and global partners.')

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

    {{-- TOP: 2-Column Hero Content Grid (Text Left, Image Right) --}}
    <div class="container mika-hero-container">

        {{-- LEFT: Text Content --}}
        <div class="mika-hero-left">

            {{-- Live badge --}}
            <div class="mika-live-badge">
                <span class="mika-live-dot"></span>
                @t('home.hero_badge', 'Frozen Food Sourcing & Cold-Chain Supply')
            </div>

            <h1 class="mika-hero-h1">
                @if(current_locale() === 'zh')
                    不止是供货商。<br>
                    <span class="mika-hero-gradient">更是您值得信赖的</span><br>
                    <span class="mika-hero-gradient">采购与供应链战略伙伴。</span>
                @elseif(current_locale() === 'bm')
                    LEBIH DARIPADA PEMBEKAL.<br>
                    <span class="mika-hero-gradient">RAKAN STRATEGIK</span><br>
                    <span class="mika-hero-gradient">PEROLEHAN &amp; BEKALAN ANDA.</span>
                @else
                    MORE THAN A SUPPLIER.<br>
                    <span class="mika-hero-gradient">YOUR SOURCING &amp;</span><br>
                    <span class="mika-hero-gradient">SUPPLY PARTNER.</span>
                @endif
            </h1>

            <div style="font-size:1.05rem;font-weight:700;color:#93c5fd;margin-bottom:12px;letter-spacing:0.02em;">
                @t('home.hero_categories_highlight', 'Seafood · Meat · Frozen Food · Food Ingredients · Customised Sourcing')
            </div>

            <p class="mika-hero-sub">
                @t('home.hero_subtitle', 'Serving restaurants, food businesses, retailers, wholesalers and trading partners across Malaysia and Singapore with frozen seafood, food products and customised sourcing solutions.')
            </p>

            {{-- Customer type pills --}}
            <div class="mika-type-pills">
                <span class="mika-pill">🛒 @t('common.retail', 'Retail')</span>
                <span class="mika-pill">🏪 @t('nav.walkin_mode', 'Walk-in')</span>
                <span class="mika-pill">🏭 @t('home.pill_wholesale_b2b', 'Wholesale & B2B')</span>
                <span class="mika-pill">📦 @t('home.pill_trading_supply', 'Trading Supply')</span>
                <span class="mika-pill">🌏 @t('home.regional_international_pill', 'Regional & International')</span>
            </div>

            {{-- CTAs --}}
            <div class="mika-hero-cta">
                <a href="{{ route('shop.index') }}" class="mika-btn-primary">
                    @t('home.hero_cta_shop', 'Explore Catalogue')
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <a href="{{ route('contact') }}#quote" class="mika-btn-ghost">@t('home.hero_cta_rfq', 'Request Quotation (RFQ)')</a>
            </div>

        </div>

        {{-- RIGHT: Hero Image (Vertically and horizontally centered) --}}
        <div class="mika-hero-right">
            <div class="mika-image-frame">
                <picture>
                    <source srcset="{{ cdn_img('hero-banner.webp') }}" type="image/webp">
                    <img src="{{ cdn_img('hero-banner.jpg') }}"
                         alt="Quality frozen seafood & food products — MST Import and Export Sdn Bhd"
                         class="mika-hero-img"
                         width="1376"
                         height="768"
                         fetchpriority="high"
                         decoding="async">
                </picture>
                {{-- Floating feature cards --}}
                <div class="mika-float-card mika-float-top">
                    <span style="font-size:1.4rem">❄️</span>
                    <div>
                        <div style="font-weight:700;font-size:0.8rem;color:#0f172a">@t('home.float_fresh_catch', 'QUALITY FROZEN PRODUCTS')</div>
                        <div style="font-size:0.7rem;color:#64748b">@t('home.float_iqf_frozen', 'IQF Frozen at Source')</div>
                    </div>
                </div>
                <div class="mika-float-card mika-float-bottom">
                    <span style="font-size:1.4rem">❄️</span>
                    <div>
                        <div style="font-weight:700;font-size:0.8rem;color:#0f172a">@t('home.float_cold_chain', '-18°C Cold Chain')</div>
                        <div style="font-size:0.7rem;color:#64748b">@t('home.float_maintained', 'Temperature-controlled handling')</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- BOTTOM: Full Width Centered Stats Bar & Trust Row --}}
    <div class="container mika-hero-bottom-container">
        <div class="mika-stats-bar">
            <div class="mika-stat-item">
                <div class="mika-stat-num">2014</div>
                <div class="mika-stat-lbl">@t('home.stat_established', 'Established')</div>
            </div>
            <div class="mika-stat-divider"></div>
            <div class="mika-stat-item">
                <div class="mika-stat-num" style="font-size:1.35rem;">@t('home.stat_customised', 'CUSTOMISED')</div>
                <div class="mika-stat-lbl">@t('home.stat_sourcing', 'Sourcing')</div>
            </div>
            <div class="mika-stat-divider"></div>
            <div class="mika-stat-item">
                <div class="mika-stat-num" style="font-size:1.35rem;">@t('home.stat_coldchain', 'COLD-CHAIN')</div>
                <div class="mika-stat-lbl">@t('home.stat_storage_handling', 'Storage & Handling')</div>
            </div>
            <div class="mika-stat-divider"></div>
            <div class="mika-stat-item">
                <div class="mika-stat-num">@t('home.stat_b2b', 'B2B')</div>
                <div class="mika-stat-lbl">@t('home.stat_supply_dist', 'Supply & Distribution')</div>
            </div>
        </div>

        {{-- Centered trust badges --}}
        <div class="mika-trust-row">
            <div class="mika-trust-badge">✅ @t('home.trust_quality', 'Quality Assured')</div>
            <div class="mika-trust-badge">🔬 @t('home.trust_haccp', 'HACCP & GMP Principles')</div>
            <div class="mika-trust-badge">❄️ @t('home.trust_coldchain', '-18°C to -25°C Frozen Storage')</div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="mika-scroll-hint">
        <div class="mika-scroll-line"></div>
        <span>@t('home.scroll', 'Scroll')</span>
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
    flex-direction: column;
    justify-content: center;
    align-items: stretch;
    overflow: hidden;
    background: linear-gradient(135deg, #06152b 0%, #0c2146 40%, #14356b 75%, #1d4ed8 100%);
    padding-top: 110px;
    padding-bottom: 50px;
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
    width: 100%;
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 48px;
    align-items: center;
    position: relative;
    z-index: 2;
    padding-top: 10px;
    padding-bottom: 30px;
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
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #fef08a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Subtitle */
.mika-hero-sub {
    font-size: 1.05rem;
    color: rgba(255,255,255,0.85);
    line-height: 1.75;
    max-width: 540px;
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
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 600;
    color: rgba(255,255,255,0.95);
    backdrop-filter: blur(8px);
    transition: all 0.2s ease;
    cursor: default;
}

.mika-pill:hover {
    background: rgba(245,158,11,0.15);
    border-color: #f59e0b;
    color: #fef08a;
    transform: translateY(-1px);
}

/* CTA Buttons */
.mika-hero-cta {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.mika-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #091a36;
    font-weight: 800;
    font-size: 0.95rem;
    border-radius: 12px;
    text-decoration: none;
    box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4), 0 2px 6px rgba(0,0,0,0.2);
    transition: all 0.25s ease;
    border: 1px solid #f59e0b;
}

.mika-btn-primary:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(245, 158, 11, 0.55);
    color: #091a36;
    border-color: #d97706;
}

.mika-btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: rgba(255,255,255,0.08);
    color: #ffffff;
    font-weight: 700;
    font-size: 0.95rem;
    border-radius: 12px;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,0.35);
    backdrop-filter: blur(10px);
    transition: all 0.25s ease;
}

.mika-btn-ghost:hover {
    background: rgba(255,255,255,0.18);
    border-color: #fbbf24;
    color: #fbbf24;
    transform: translateY(-2px);
}

/* ── Bottom Section: Full-Width Centered Stats Bar & Trust Badges ── */
.mika-hero-bottom-container {
    width: 100%;
    position: relative;
    z-index: 2;
    margin-top: 10px;
    padding-bottom: 20px;
}

.mika-stats-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0;
    width: 100%;
    box-sizing: border-box;
    padding: 20px 32px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 18px;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.25);
    margin-bottom: 18px;
}

.mika-stat-item {
    flex: 1;
    text-align: center;
    padding: 0 16px;
}

.mika-stat-num {
    font-family: 'Outfit', sans-serif;
    font-size: 1.75rem;
    font-weight: 800;
    color: #60a5fa;
    line-height: 1.1;
    margin-bottom: 4px;
    letter-spacing: 0.02em;
}

.mika-stat-lbl {
    font-size: 0.74rem;
    color: rgba(255, 255, 255, 0.75);
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.mika-stat-divider {
    width: 1px;
    height: 42px;
    background: rgba(255, 255, 255, 0.15);
    margin: 0;
    flex-shrink: 0;
}

/* Centered Trust badges */
.mika-trust-row {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.mika-trust-badge {
    font-size: 0.76rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.85);
    padding: 6px 14px;
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(8px);
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
        padding: 14px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 14px;
        margin-bottom: 16px;
    }

    .mika-stat-divider {
        display: none;
    }

    .mika-stat-item {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 10px 8px;
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
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .mika-trust-badge {
        font-size: 0.7rem;
        padding: 4px 8px;
    }
}

/* ════════════════════════════════════════
   HOMEPAGE SECTIONS — RESPONSIVE STYLES
   ════════════════════════════════════════ */

/* Category Sourcing Card */
.category-card-sourcing {
    background: linear-gradient(135deg, #f0f7ff 0%, #e8f0fe 100%);
    border-radius: 16px;
    padding: 26px 28px;
    color: #0f172a;
    box-shadow: 0 8px 24px rgba(37,99,235,0.08);
    grid-column: 1 / -1;
    border: 1px solid #bfdbfe;
}

.sourcing-card-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 18px;
}

.sourcing-card-content {
    display: flex;
    align-items: center;
    gap: 18px;
}

.sourcing-card-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

/* Seafood Tips & Newsletter */
.culinary-advisory-grid {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 40px;
    align-items: center;
}

.tips-cards-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.tip-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.tip-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.04);
}

.newsletter-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 32px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.04);
}

/* Responsive Media Queries for All Sections */
@media (max-width: 960px) {
    .culinary-advisory-grid {
        grid-template-columns: 1fr;
        gap: 32px;
    }
}

@media (max-width: 768px) {
    .sourcing-card-inner {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }

    .sourcing-card-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .sourcing-card-actions {
        width: 100%;
        flex-direction: column;
        gap: 10px;
    }

    .sourcing-card-actions .btn {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}

@media (max-width: 640px) {
    .category-section,
    .featured-products-section,
    .local-delivery-section,
    .seafood-tips-section {
        padding-top: var(--space-12) !important;
        padding-bottom: var(--space-12) !important;
    }

    .tips-cards-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .newsletter-box {
        padding: 22px 18px !important;
        border-radius: 14px !important;
    }

    .tips-view-btn {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}

/* ════════════════════════════════════════
   FEATURED PRODUCTS CARD (HOMEPAGE)
   Matches Shop Page Cards Exactly
   ════════════════════════════════════════ */
.featured-products-section .products-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 22px;
}

/* Product Cards & Badges */
.featured-products-section .product-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.featured-products-section .product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.08), 0 4px 8px -2px rgba(15, 23, 42, 0.04);
    border-color: #bfdbfe;
}
.featured-products-section .product-card-img {
    position: relative;
    aspect-ratio: 4 / 3;
    width: 100%;
    overflow: hidden;
    background: #f8fafc;
    flex-shrink: 0;
}
.featured-products-section .product-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}
.featured-products-section .product-card:hover .product-card-img img {
    transform: scale(1.05);
}

.featured-products-section .product-img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    background: #f1f5f9;
}

/* Card Badges: Flex group so badges never collide */
.featured-products-section .card-badges-top {
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
.featured-products-section .card-badges-top .product-badge {
    position: static !important;
    top: auto !important;
    left: auto !important;
    right: auto !important;
    bottom: auto !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
}
.featured-products-section .card-badges-top .badge-featured {
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
.featured-products-section .card-badges-top .badge-origin {
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
.featured-products-section .product-badge-temp {
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
.featured-products-section .btn-card-quickview {
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
.featured-products-section .product-card:hover .btn-card-quickview {
    opacity: 1;
    pointer-events: auto;
    transform: translate(-50%, -50%) scale(1);
}
.featured-products-section .btn-card-quickview:hover {
    background: #1e40af;
    border-color: #3b82f6;
}

/* Card Body */
.featured-products-section .product-card-body {
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
    background: #ffffff;
}

.featured-products-section .product-card-top-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    margin-bottom: 3px;
}
.featured-products-section .product-category {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #0284c7;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.featured-products-section .product-sku {
    font-size: 0.68rem;
    color: #94a3b8;
    font-family: monospace;
    white-space: nowrap;
}

/* Title strictly clamped to 2 lines so cards stay 100% equal height */
.featured-products-section .product-name {
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
.featured-products-section .product-name a {
    color: #0f172a;
    text-decoration: none;
    transition: color 0.15s ease;
}
.featured-products-section .product-card:hover .product-name a {
    color: #1d4ed8;
}

/* Metadata Chips */
.featured-products-section .product-meta {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
    margin-bottom: 8px;
    min-height: 22px;
}
.featured-products-section .product-meta-item {
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
.featured-products-section .product-price-row {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    margin-top: auto !important;
    margin-bottom: 10px;
    padding-top: 8px;
    border-top: 1px solid #f1f5f9;
}
.featured-products-section .product-price {
    font-family: var(--font-heading);
    font-size: 1.25rem;
    font-weight: 800;
    color: #1e40af;
}
.featured-products-section .product-price.rfq {
    font-size: 0.9rem;
    color: #d97706;
}
.featured-products-section .product-moq {
    font-size: 0.72rem;
    font-weight: 700;
    color: #475569;
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
}

/* Full Width Actions */
.featured-products-section .product-card-actions {
    display: flex;
    gap: 6px;
    align-items: stretch;
    width: 100%;
    margin-top: 0 !important;
}
.featured-products-section .product-cart-form {
    flex: 1;
    width: 100%;
    display: flex;
    margin: 0;
}
.featured-products-section .btn-card-add-cart {
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
.featured-products-section .btn-card-add-cart:hover {
    background: #1e40af;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(29, 78, 216, 0.35);
}
.featured-products-section .btn-card-details {
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
.featured-products-section .btn-card-details:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.featured-products-section .btn-card-rfq {
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

/* ─── Responsive Breakpoints ─── */
@media (max-width: 1180px) {
    .featured-products-section .products-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }
}

@media (max-width: 860px) {
    .featured-products-section .products-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 12px !important;
    }
    .featured-products-section .product-card-body {
        padding: 10px 12px !important;
    }
    .featured-products-section .product-name {
        font-size: 0.88rem !important;
        height: 2.6em !important;
        margin-bottom: 4px !important;
    }
    .featured-products-section .product-price {
        font-size: 1.12rem !important;
    }
    .featured-products-section .btn-card-add-cart,
    .featured-products-section .btn-card-details {
        height: 35px !important;
        font-size: 0.8rem !important;
        border-radius: 8px !important;
    }
    .featured-products-section .card-badges-top {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 4px !important;
        right: auto !important;
    }
    .featured-products-section .card-badges-top .badge-featured,
    .featured-products-section .card-badges-top .badge-origin {
        font-size: 0.62rem !important;
        padding: 2px 6px !important;
        margin-left: 0 !important;
    }
    .featured-products-section .product-badge-temp {
        font-size: 0.62rem !important;
        padding: 2px 6px !important;
        bottom: 6px !important;
        left: 6px !important;
    }
    .featured-products-section .btn-card-quickview {
        display: none !important;
    }
}

@media (max-width: 480px) {
    .featured-products-section .products-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
    }
    .featured-products-section .product-card-body {
        padding: 8px 10px !important;
    }
    .featured-products-section .product-name {
        font-size: 0.82rem !important;
    }
    .featured-products-section .product-price {
        font-size: 1.05rem !important;
    }
    .featured-products-section .btn-card-add-cart,
    .featured-products-section .btn-card-details {
        height: 33px !important;
        font-size: 0.78rem !important;
    }
}
</style>



<!-- ─── Category Section (Featured Categories from Admin + Custom Sourcing) ─── -->
@if($categories->count())
<section class="section category-section" style="padding-top: var(--space-16); padding-bottom: var(--space-12);">
    <div class="container">
        <div class="section-header" style="text-align:center;max-width:760px;margin:0 auto var(--space-10);">
            <div style="margin-bottom:12px">
                <span class="badge" style="background:#eff6ff;color:#2563eb;font-size:0.75rem;padding:6px 16px;border-radius:999px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;display:inline-block;border:1px solid #dbeafe;">
                    @t('home.categories_eyebrow', 'PRODUCT RANGE')
                </span>
            </div>
            <h2 class="section-title" style="font-size:clamp(1.75rem, 3.5vw, 2.35rem);font-weight:800;color:#0f172a;margin-bottom:10px;">@t('home.popular_categories_title', 'EXPLORE OUR PRODUCT CATEGORIES')</h2>
            <p class="section-subtitle" style="font-size:0.95rem;color:#64748b;line-height:1.6;margin:0 auto;text-align:center;max-width:680px;">
                @t('home.popular_categories_desc', 'Browse our premium selection of seafood, meat, frozen food and specialty food ingredients.')
            </p>
        </div>

        <div class="categories-grid" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(240px, 1fr));gap:20px;">
            @foreach($categories as $category)
                <a href="{{ $category->url }}" class="category-card" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 20px;text-align:center;text-decoration:none;transition:all 0.25s ease;display:flex;flex-direction:column;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='#93c5fd';this.style.boxShadow='0 12px 24px rgba(37,99,235,0.08)'" onmouseout="this.style.transform='none';this.style.borderColor='#e2e8f0';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)'">
                    @if($category->image)
                        <img src="{{ str_starts_with($category->image, 'http') ? $category->image : (str_starts_with($category->image, 'images/') ? asset($category->image) : asset('storage/'.$category->image)) }}" alt="{{ $category->name }}" style="width:48px;height:48px;object-fit:contain;margin-bottom:10px;">
                    @else
                        <span class="category-icon" style="font-size:2.4rem;margin-bottom:10px;display:inline-block;">{{ $category->icon ?: '📦' }}</span>
                    @endif
                    <div class="category-name" style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">{{ strtoupper($category->name) }}</div>
                    @if($category->description)
                        <div style="font-size:0.82rem;color:#64748b;line-height:1.5;">{{ $category->description }}</div>
                    @endif
                </a>
            @endforeach

            <!-- Customised Sourcing (Special Card) -->
            <div class="category-card-sourcing">
                <div class="sourcing-card-inner">
                    <div class="sourcing-card-content">
                        <span style="font-size:2.8rem;flex-shrink:0;">📦</span>
                        <div>
                            <div style="font-size:0.75rem;font-weight:700;color:#1d4ed8;letter-spacing:0.08em;text-transform:uppercase;">@t('home.sourcing_tag', 'YOU NEED IT. WE SOURCE IT.')</div>
                            <div style="font-size:1.3rem;font-weight:800;color:#091a36;margin:2px 0 4px;">@t('home.sourcing_title', 'CUSTOMISED SOURCING SOLUTIONS')</div>
                            <p style="color:#475569;font-size:0.92rem;margin:0;max-width:720px;line-height:1.5;">
                                @t('home.sourcing_desc', 'Can\'t find what you need? Tell us what you\'re looking for. We work with our sourcing network to identify suitable products and supply options based on your requirements. Product availability, specifications, MOQ and pricing are subject to supplier confirmation.')
                            </p>
                        </div>
                    </div>
                    <div class="sourcing-card-actions">
                        <a href="{{ route('shop.index') }}" class="btn btn-secondary" style="background:#ffffff;color:#0f172a;border-color:#cbd5e1;padding:10px 18px;font-size:0.88rem;font-weight:700;">
                            @t('home.explore_products_btn', 'EXPLORE PRODUCTS →')
                        </a>
                        <a href="{{ route('contact') }}#quote" class="btn btn-primary" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);border:1px solid #f59e0b;color:#091a36;padding:10px 20px;font-size:0.88rem;font-weight:800;box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                            @t('home.request_quote_btn', 'REQUEST A QUOTE')
                        </a>
                    </div>
                </div>
            </div>
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
                    ⭐ @t('home.featured_eyebrow', 'FEATURED PRODUCTS')
                </div>
                <h2 style="font-family:var(--font-heading);font-size:clamp(1.6rem,4vw,2.25rem);font-weight:800;color:var(--text-primary);margin:0 0 8px;">
                    @t('home.featured_title', 'PRODUCT HIGHLIGHTS')
                </h2>
                <p style="color:var(--text-muted);font-size:0.95rem;margin:0;max-width:760px;line-height:1.5;">
                    @t('home.featured_desc', 'Explore a selection of products from our current range of frozen seafood, meat and frozen food.')<br>
                    <span style="font-size:0.88rem;color:#64748b;">@t('home.featured_notice', 'Retail prices are displayed for reference. Wholesale and trading customers may register for account-based pricing or contact our team for customised quotations. Prices are subject to availability and may change without prior notice.')</span>
                </p>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-secondary" style="white-space:nowrap;flex-shrink:0;">
                @t('common.view_all_products', 'View All Products') →
            </a>
        </div>

        <div class="products-grid">
            @php
                $currentCurrency = session('currency', 'MYR');
                $group = $group ?? (auth()->user()?->customer_group ?? 'retail');
            @endphp
            @foreach($featuredProducts as $product)
                @php
                    $displayPrice = $product->getDisplayPrice($group);
                    $price = $displayPrice['amount'];
                    $priceFormatted = $displayPrice['formatted'];
                @endphp
                <div class="product-card" data-product-id="{{ $product->id }}">
                    <div class="product-card-img">
                        <a href="{{ route('shop.show', $product) }}" style="display:block;width:100%;height:100%">
                            @if($product->thumbnail)
                                <img src="{{ cdn_storage($product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy">
                            @else
                                <div class="product-img-placeholder">🐟</div>
                            @endif
                        </a>

                        <!-- Top Badges Container: stacks vertically on mobile so badges never collide -->
                        <div class="card-badges-top">
                            @if($product->is_featured)
                                <span class="product-badge badge-featured">⭐ @t('shop.badge_featured', 'Featured')</span>
                            @endif
                            @if($product->origin)
                                <span class="product-badge badge-origin">🌍 {{ $product->origin }}</span>
                            @endif
                        </div>

                        @if($product->storage_temp)
                            @php
                                $tempLower = strtolower($product->storage_temp);
                                $isLive = str_contains($tempLower, 'live');
                                $isChilled = str_contains($tempLower, 'chilled');
                                $badgeIcon = $product->getStorageIcon();
                                $badgeSuffix = ($isLive || $isChilled) ? '' : ' IQF';
                            @endphp
                            <span class="product-badge-temp">{{ $badgeIcon }} {{ $product->storage_temp }}{{ $badgeSuffix }}</span>
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
                            'storage_icon' => $product->getStorageIcon(),
                            'price_formatted' => $priceFormatted,
                            'base_rm' => $product->getPriceForGroup($group),
                            'manual_sgd' => $product->price_sgd ?? '',
                            'manual_usd' => $product->price_usd ?? '',
                            'manual_wholesale_sgd' => $product->wholesale_price_sgd ?? '',
                            'manual_wholesale_usd' => $product->wholesale_price_usd ?? '',
                            'group' => $group,
                            'moq' => $product->getMoqForGroup($group) > 1 ? ($product->getMoqForGroup($group) . ' ' . $product->unit) : null,
                            'short_desc' => $product->short_description ?? $product->description,
                            'image' => $product->thumbnail ? cdn_storage($product->thumbnail) : null,
                            'url' => route('shop.show', $product),
                            'rfq_url' => route('quotations.create', ['product' => $product->id]),
                        ]) }})">
                            👁️ @t('shop.quick_view', 'Quick View')
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
                            @if($product->storage_temp)
                                <span class="product-meta-item">{{ $product->getStorageIcon() }} {{ $product->storage_temp }}</span>
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
                                    <span class="price-amount">{{ $priceFormatted }}</span>
                                    <span class="price-base-rm" style="display:{{ ($currentCurrency !== 'MYR' && !empty($displayPrice['base_rm'])) ? 'block' : 'none' }};font-size:0.75rem;font-weight:500;color:#64748b;margin-top:2px">
                                        RM {{ number_format($product->getPriceForGroup($group), 2) }}
                                    </span>
                                </div>
                            @else
                                <div class="product-price rfq">@t('shop.price_on_request', 'Price on Request')</div>
                            @endif
                            @if(in_array($group, ['wholesale','trading']) && $product->getMoqForGroup($group) > 1)
                                <div class="product-moq">@t('shop.moq_label', 'MOQ:') {{ $product->getMoqForGroup($group) }}</div>
                            @endif
                        </div>
                        <div class="product-card-actions">
                            @if($price !== null)
                                <form action="{{ route('cart.add') }}" method="POST" class="product-cart-form">
                                     @csrf
                                     <input type="hidden" name="product_id" value="{{ $product->id }}">
                                     <input type="hidden" name="quantity" value="{{ $product->getMoqForGroup($group) }}">
                                     <button type="submit" class="btn-card-add-cart">
                                         @t('shop.add_to_cart', 'Add to Cart')
                                     </button>
                                 </form>
                            @else
                                <a href="{{ route('shop.show', $product) }}" class="btn-card-details">
                                    @t('shop.details', 'Details')
                                </a>
                            @endif
                            @if(auth()->check() && auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                                <a href="{{ route('quotations.create', ['product' => $product->id]) }}" class="btn-card-rfq" title="@t('shop.rfq', 'Request For Quotation')">
                                    📋 @t('shop.rfq', 'RFQ')
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Mobile-only bottom link --}}
        <div class="mobile-view-all" style="display:none;text-align:center;margin-top:20px;">
            <a href="{{ route('shop.index') }}" class="btn btn-secondary" style="width:100%;max-width:320px;">
                @t('common.view_all_products', 'View All Products') →
            </a>
        </div>
    </div>
</section>

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
                    <span>@t('shop.sku_label', 'SKU:') <strong id="qvSku"></strong></span>
                    <span>@t('shop.storage_label', 'Storage:') <strong id="qvStorage"></strong></span>
                </div>
                <div class="quickview-price-box">
                    <div style="display:flex;flex-direction:column">
                        <div class="qv-current-price" id="qvPrice"></div>
                        <div class="qv-base-rm" id="qvBaseRm" style="display:none;font-size:0.8rem;color:#64748b;font-weight:500;margin-top:2px"></div>
                    </div>
                    <div class="qv-weight" id="qvWeight"></div>
                </div>

                <p class="quickview-desc" id="qvDesc"></p>

                <!-- Approved Customer Group Unit Pricing (Strict Tier Privacy) -->
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px 16px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between">
                    <div>
                        <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#1e40af">@t('shop.approved_tier_title', 'Approved Customer Tier')</span>
                        <div style="font-weight:800;color:#0f172a;font-size:0.95rem">{{ match($group ?? 'retail') {
                            'retail' => __t('shop.retail_tier', 'Retail Tier'),
                            'wholesale' => __t('shop.wholesale_tier', 'Wholesale Tier'),
                            'trading' => __t('shop.trading_tier', 'Trading Tier'),
                            default => ucfirst($group ?? 'retail') . ' Rate',
                        } }}</div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:0.75rem;color:#64748b" id="qvMoqNotice"></div>
                        <div style="font-weight:800;color:#1e40af;font-size:0.85rem">@t('shop.authorized_price_only', 'Authorized Price Only')</div>
                    </div>
                </div>

                <div class="quickview-actions">
                    <a href="#" id="qvViewLink" class="btn btn-primary" style="flex:1;text-align:center;padding:10px 18px;border-radius:10px;font-weight:700">
                        @t('shop.view_full_details', 'View Full Details') →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ─── 4.4 How It Works ────────────────────────────────────────────────── -->
<section class="section" style="background: linear-gradient(180deg, #f8fafc, #ffffff); padding: var(--space-16) 0;">
    <div class="container">
        <div class="section-header" style="text-align:center;max-width:720px;margin:0 auto var(--space-10);">
            <div class="section-eyebrow" style="color:#1d4ed8;font-weight:700;margin-bottom:8px">@t('home.how_it_works_eyebrow', 'HOW IT WORKS')</div>
            <h2 class="section-title" style="font-size:clamp(1.6rem, 3.5vw, 2.25rem);margin-bottom:8px;">@t('home.how_it_works_title', 'A Smarter Way to Buy')</h2>
            <p class="section-subtitle" style="font-size:0.95rem;color:var(--text-muted);margin:0 auto;line-height:1.6;text-align:center;">
                @t('home.how_it_works_subtitle', 'Different customers. Different needs. One platform. Choose the account type that fits your business and access the products, pricing and purchasing options designed for you.')
            </p>
        </div>
        <div class="how-it-works-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:22px;">
            <div class="glass-card p-6" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:28px 22px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
                <div style="font-size:2.6rem;margin-bottom:var(--space-3)">🛒</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:8px;font-size:1.15rem;font-weight:800;color:#0f172a;">@t('home.how_retail_title', 'RETAIL')</h3>
                <p class="text-sm text-muted" style="line-height:1.6;font-size:0.88rem;color:#64748b;margin-bottom:8px;">
                    @t('home.how_retail_desc', 'Register for free, browse retail prices, add products to your cart and pay online.')
                </p>
                <div style="font-size:0.8rem;font-weight:600;color:#1d4ed8;">@t('home.how_retail_foot', 'Delivery or self-collection available.')</div>
            </div>
            <div class="glass-card p-6" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:28px 22px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
                <div style="font-size:2.6rem;margin-bottom:var(--space-3)">📱</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:8px;font-size:1.15rem;font-weight:800;color:#0f172a;">@t('home.how_walkin_title', 'WALK-IN')</h3>
                <p class="text-sm text-muted" style="line-height:1.6;font-size:0.88rem;color:#64748b;margin-bottom:8px;">
                    @t('home.how_walkin_desc', 'Scan our QR code in-store to access walk-in pricing.')
                </p>
                <div style="font-size:0.8rem;font-weight:600;color:#059669;">@t('home.how_walkin_foot', 'No registration required. Pay & self-collect.')</div>
            </div>
            <div class="glass-card p-6" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:28px 22px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
                <div style="font-size:2.6rem;margin-bottom:var(--space-3)">🏭</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:8px;font-size:1.15rem;font-weight:800;color:#0f172a;">@t('home.how_wholesale_title', 'WHOLESALE')</h3>
                <p class="text-sm text-muted" style="line-height:1.6;font-size:0.88rem;color:#64748b;margin-bottom:8px;">
                    @t('home.how_wholesale_desc', 'Register for a wholesale account. Once approved, access applicable wholesale pricing and MOQ requirements.')
                </p>
                <div style="font-size:0.8rem;font-weight:600;color:#d97706;">@t('home.how_wholesale_foot', 'Built for restaurants, retailers & bulk buyers.')</div>
            </div>
            <div class="glass-card p-6" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:28px 22px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
                <div style="font-size:2.6rem;margin-bottom:var(--space-3)">📦</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:8px;font-size:1.15rem;font-weight:800;color:#0f172a;">@t('home.how_trading_title', 'TRADING')</h3>
                <p class="text-sm text-muted" style="line-height:1.6;font-size:0.88rem;color:#64748b;margin-bottom:8px;">
                    @t('home.how_trading_desc', 'Register for a trading account to access applicable trading prices, bulk purchasing options and Request for Quotation (RFQ).')
                </p>
                <div style="font-size:0.8rem;font-weight:600;color:#7c3aed;">@t('home.how_trading_foot', 'For distributors, traders & bulk volume.')</div>
            </div>
        </div>
    </div>
</section>

<!-- ─── 4.4B Local Delivery Section ──────────────────────────────────────── -->
<section class="section local-delivery-section" style="padding: 100px 0; background: #ffffff; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
    <div class="container">
        <div class="section-header" style="text-align:center;max-width:760px;margin:0 auto 48px;">
            <div style="margin-bottom:8px">
                <span class="badge" style="background:#f0fdf4;color:#15803d;font-size:0.75rem;padding:6px 16px;border-radius:999px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;display:inline-block;border:1px solid #bbf7d0;">
                    🚚 @t('home.delivery_eyebrow', 'LOCAL DELIVERY')
                </span>
            </div>
            <h2 class="section-title" style="font-size:clamp(1.6rem, 3.5vw, 2.25rem);margin-bottom:8px;font-weight:800;color:#0f172a;">
                @t('home.delivery_title', 'Convenient Door-to-Door Delivery')
            </h2>
            <p class="section-subtitle" style="font-size:0.95rem;color:#64748b;margin:0 auto;line-height:1.6;text-align:center;">
                @t('home.delivery_subtitle', 'Temperature-controlled local delivery service supporting both residential retail orders and commercial wholesale operations.')
            </p>
        </div>

        <div class="delivery-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:20px;margin-bottom:24px;">
            <!-- B2C / Retail -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:24px 22px;display:flex;flex-direction:column;gap:10px;transition:all 0.2s ease;" onmouseover="this.style.borderColor='#93c5fd';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='none'">
                <div style="display:flex;align-items:center;gap:12px">
                    <span style="font-size:2rem">🛒</span>
                    <div>
                        <div style="font-size:0.78rem;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:0.05em">@t('common.retail', 'Retail')</div>
                        <h3 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0">B2C / Retail</h3>
                    </div>
                </div>
                <div style="background:#ffffff;border:1px solid #dbeafe;border-radius:10px;padding:10px 14px;margin-top:4px">
                    <div style="font-size:0.75rem;color:#64748b;font-weight:600">@t('home.delivery_min_order', 'Minimum Order')</div>
                    <div style="font-size:1.3rem;font-weight:800;color:#1d4ed8">RM 100</div>
                </div>
                <p style="font-size:0.85rem;color:#64748b;line-height:1.5;margin:0">
                    @t('home.delivery_b2c_desc', 'Convenient home and retail door-to-door delivery for personal dining and small-batch orders.')
                </p>
            </div>

            <!-- B2B / Wholesale -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:24px 22px;display:flex;flex-direction:column;gap:10px;transition:all 0.2s ease;" onmouseover="this.style.borderColor='#93c5fd';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='none'">
                <div style="display:flex;align-items:center;gap:12px">
                    <span style="font-size:2rem">🏭</span>
                    <div>
                        <div style="font-size:0.78rem;font-weight:700;color:#d97706;text-transform:uppercase;letter-spacing:0.05em">@t('home.how_wholesale_title', 'Wholesale')</div>
                        <h3 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0">B2B / Wholesale</h3>
                    </div>
                </div>
                <div style="background:#ffffff;border:1px solid #fef3c7;border-radius:10px;padding:10px 14px;margin-top:4px">
                    <div style="font-size:0.75rem;color:#64748b;font-weight:600">@t('home.delivery_min_order', 'Minimum Order')</div>
                    <div style="font-size:1.3rem;font-weight:800;color:#b45309">RM 350</div>
                </div>
                <p style="font-size:0.85rem;color:#64748b;line-height:1.5;margin:0">
                    @t('home.delivery_b2b_desc', 'Scheduled cold-chain dispatch directly to restaurants, foodservice operators and commercial kitchens.')
                </p>
            </div>

            <!-- Coverage Area -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:24px 22px;display:flex;flex-direction:column;gap:10px;transition:all 0.2s ease;" onmouseover="this.style.borderColor='#93c5fd';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='none'">
                <div style="display:flex;align-items:center;gap:12px">
                    <span style="font-size:2rem">📍</span>
                    <div>
                        <div style="font-size:0.78rem;font-weight:700;color:#059669;text-transform:uppercase;letter-spacing:0.05em">@t('home.delivery_area_badge', 'Coverage')</div>
                        <h3 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0">@t('home.delivery_coverage_title', 'Local Delivery Area')</h3>
                    </div>
                </div>
                <div style="background:#ffffff;border:1px solid #bbf7d0;border-radius:10px;padding:10px 14px;margin-top:4px">
                    <div style="font-size:0.75rem;color:#64748b;font-weight:600">@t('home.delivery_available_areas', 'Available for selected areas within')</div>
                    <div style="font-size:1.05rem;font-weight:800;color:#15803d;line-height:1.3;margin-top:2px">Johor Bahru & Nusajaya / Iskandar Puteri</div>
                </div>
                <p style="font-size:0.85rem;color:#64748b;line-height:1.5;margin:0">
                    @t('home.delivery_coverage_desc', 'Direct cold-chain dispatch from our SiLC Iskandar Puteri distribution hub.')
                </p>
            </div>
        </div>

        <!-- Additional Policy Notes Banner & Action Button -->
        <div style="background:#f1f5f9;border:1px solid #cbd5e1;border-radius:14px;padding:18px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div style="max-width:760px">
                <div style="font-size:0.88rem;color:#334155;line-height:1.6;font-weight:500;">
                    <span>ℹ️ @t('home.delivery_note_below_min', 'Orders below the applicable minimum may still be accepted with delivery charges.')</span>
                    <span style="display:block;margin-top:2px">🌐 @t('home.delivery_note_outside', 'For locations outside our local delivery area, transportation charges apply.')</span>
                </div>
            </div>
            <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => 'shipping-policy']) }}" class="btn btn-secondary" style="background:#ffffff;color:#0f172a;border-color:#cbd5e1;font-weight:700;font-size:0.85rem;padding:8px 18px;white-space:nowrap;">
                @t('home.delivery_policy_btn', 'View Delivery Policy →')
            </a>
        </div>
    </div>
</section>

<!-- ─── 4.5 Our Commitment to Quality ──────────────────────────────────── -->
<section class="section" style="padding:var(--space-16) 0;background:#ffffff;border-top:1px solid #e2e8f0;">
    <div class="container">
        <div class="section-header" style="text-align:center;max-width:760px;margin:0 auto var(--space-10);">
            <div class="section-eyebrow" style="color:#1d4ed8;font-weight:700;margin-bottom:8px">@t('home.quality_eyebrow', 'OUR COMMITMENT TO QUALITY')</div>
            <h2 class="section-title" style="font-size:clamp(1.6rem, 3.5vw, 2.25rem);margin-bottom:10px;">@t('home.quality_title', 'QUALITY. INTEGRITY. RELIABLE COLD-CHAIN.')</h2>
            <p class="section-subtitle" style="font-size:0.95rem;color:var(--text-muted);line-height:1.6;margin:0 auto;text-align:center;">
                @t('home.quality_subtitle', 'At MST, product quality begins with responsible sourcing and continues through proper handling, temperature-controlled storage and reliable supply. We focus on maintaining product integrity from receiving and storage to order preparation and dispatch.')
            </p>
        </div>

        <div class="cold-chain-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:22px;">
            <div class="card" style="padding:28px 24px;border-radius:16px;border:1px solid #dbeafe;background:#f0f7ff;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(37,99,235,0.08)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#1d4ed8">
                    ❄️
                </div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin-bottom:8px">@t('home.quality_c1_title', 'TEMPERATURE-CONTROLLED STORAGE')</h3>
                <p style="font-size:0.875rem;color:#64748b;line-height:1.65;margin:0">
                    @t('home.quality_c1_desc', 'Our cold storage facilities are designed to maintain appropriate frozen temperatures (-18°C to -25°C) and protect product quality throughout storage.')
                </p>
            </div>

            <div class="card" style="padding:28px 24px;border-radius:16px;border:1px solid #dbeafe;background:#f0f7ff;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(37,99,235,0.08)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#2563eb">
                    🔍
                </div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin-bottom:8px">@t('home.quality_c2_title', 'CAREFUL PRODUCT SOURCING')</h3>
                <p style="font-size:0.875rem;color:#64748b;line-height:1.65;margin:0">
                    @t('home.quality_c2_desc', 'We work with our sourcing network to identify suitable seafood, meat and frozen food products according to customer requirements, specifications and supply needs.')
                </p>
            </div>

            <div class="card" style="padding:28px 24px;border-radius:16px;border:1px solid #fde68a;background:#fffbeb;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(245,158,11,0.1)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#d97706">
                    📦
                </div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin-bottom:8px">@t('home.quality_c3_title', 'HYGIENIC HANDLING & PACKING')</h3>
                <p style="font-size:0.875rem;color:#64748b;line-height:1.65;margin:0">
                    @t('home.quality_c3_desc', 'Structured receiving, handling, packing and order preparation processes help maintain product quality and operational consistency (based on HACCP & GMP principles).')
                </p>
            </div>

            <div class="card" style="padding:28px 24px;border-radius:16px;border:1px solid #dbeafe;background:#f0f7ff;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(37,99,235,0.08)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#1d4ed8">
                    🚚
                </div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin-bottom:8px">@t('home.quality_c4_title', 'RELIABLE SUPPLY')</h3>
                <p style="font-size:0.875rem;color:#64748b;line-height:1.65;margin:0">
                    @t('home.quality_c4_desc', 'From storage and order preparation to dispatch, our operations are designed to support consistent supply for retail, wholesale, trading and commercial customers.')
                </p>
            </div>
        </div>

        <div style="margin-top:32px;text-align:center;padding:18px 24px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
            <div style="font-size:1rem;font-weight:800;color:#0f172a;margin-bottom:4px;">
                @t('home.quality_banner_tag', 'BUILT ON INTEGRITY. DELIVERED WITH CONSISTENCY.')
            </div>
            <div style="font-size:0.85rem;color:#1d4ed8;font-weight:700;">
                @t('home.quality_banner_sub', 'MST Import and Export Sdn Bhd · Flow with Integrity, Grow with Strength.')
            </div>
        </div>
    </div>
</section>

<!-- ─── 4.6 Customised Sourcing Spotlight ───────────────────────────────── -->
<section class="section" style="padding:var(--space-16) 0;background:linear-gradient(180deg, #f0f7ff 0%, #ffffff 100%);border-top:1px solid #bfdbfe;border-bottom:1px solid #e2e8f0;color:#0f172a;" id="sourcing-spotlight">
    <div class="container">
        <div style="max-width:820px;margin:0 auto 40px;text-align:center;">
            <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:0.75rem;font-weight:700;padding:5px 16px;border-radius:999px;text-transform:uppercase;letter-spacing:0.08em;display:inline-block;margin-bottom:12px;">
                ✨ @t('home.spotlight_eyebrow', 'CUSTOMISED SOURCING')
            </span>
            <h2 style="font-family:var(--font-heading);font-size:clamp(1.6rem, 4vw, 2.3rem);color:#091a36;margin-bottom:6px;line-height:1.25;font-weight:800;">
                @t('home.spotlight_title', "CAN'T FIND WHAT YOU NEED?")
            </h2>
            <div style="font-size:1.25rem;font-weight:800;color:#1d4ed8;margin-bottom:16px;">
                @t('home.spotlight_sub', 'YOU NEED IT. WE SOURCE IT.')
            </div>
            <p style="color:#475569;font-size:0.95rem;line-height:1.7;margin:0 0 24px;">
                @t('home.spotlight_desc', 'Looking for a specific seafood, meat or frozen food product that is not currently listed in our catalogue? Tell us what you need — from product type and specifications to pack size, origin and quantity. Our team can work with our sourcing network to identify suitable products and supply options for your business. Whether you are a restaurant, food business, retailer, wholesaler or trading partner, we help simplify the sourcing process through one reliable supply partner. Product availability, specifications, MOQ and pricing are subject to supplier confirmation.')
            </p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('contact') }}#quote" class="btn btn-primary" style="background:linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);color:#091a36;border:1px solid #f59e0b;font-weight:800;padding:12px 26px;box-shadow:0 4px 14px rgba(245,158,11,0.35);">
                    @t('home.spotlight_btn_quote', 'REQUEST A QUOTE / SOURCING →')
                </a>
                <a href="{{ route('contact') }}" class="btn btn-secondary" style="background:linear-gradient(135deg, #2563eb, #1d4ed8);color:#ffffff;border:1px solid #1d4ed8;font-weight:700;padding:12px 22px;">
                    @t('home.spotlight_btn_tell', 'TELL US WHAT YOU NEED →')
                </a>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:20px;">
            <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 20px;box-shadow:0 2px 10px rgba(0,0,0,0.02);transition:all 0.2s ease;" onmouseover="this.style.borderColor='#93c5fd';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='none'">
                <div style="font-size:2rem;margin-bottom:12px;">🔍</div>
                <h4 style="font-size:1rem;font-weight:800;color:#091a36;margin-bottom:6px;">@t('home.spotlight_f1_title', 'PRODUCT-SPECIFIC SOURCING')</h4>
                <p style="font-size:0.85rem;color:#64748b;line-height:1.6;margin:0;">
                    @t('home.spotlight_f1_desc', 'Looking for a particular product or specification? Tell us your requirements.')
                </p>
            </div>
            <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 20px;box-shadow:0 2px 10px rgba(0,0,0,0.02);transition:all 0.2s ease;" onmouseover="this.style.borderColor='#93c5fd';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='none'">
                <div style="font-size:2rem;margin-bottom:12px;">🌏</div>
                <h4 style="font-size:1rem;font-weight:800;color:#091a36;margin-bottom:6px;">@t('home.spotlight_f2_title', 'MULTI-SOURCE NETWORK')</h4>
                <p style="font-size:0.85rem;color:#64748b;line-height:1.6;margin:0;">
                    @t('home.spotlight_f2_desc', 'We work with sourcing partners to identify suitable products and supply options.')
                </p>
            </div>
            <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 20px;box-shadow:0 2px 10px rgba(0,0,0,0.02);transition:all 0.2s ease;" onmouseover="this.style.borderColor='#93c5fd';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='none'">
                <div style="font-size:2rem;margin-bottom:12px;">📦</div>
                <h4 style="font-size:1rem;font-weight:800;color:#091a36;margin-bottom:6px;">@t('home.spotlight_f3_title', 'FLEXIBLE QUANTITY')</h4>
                <p style="font-size:0.85rem;color:#64748b;line-height:1.6;margin:0;">
                    @t('home.spotlight_f3_desc', 'From regular supply to specific business requirements, we work with you to identify suitable quantities and supply options.')
                </p>
            </div>
            <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 20px;box-shadow:0 2px 10px rgba(0,0,0,0.02);transition:all 0.2s ease;" onmouseover="this.style.borderColor='#93c5fd';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='none'">
                <div style="font-size:2rem;margin-bottom:12px;">🤝</div>
                <h4 style="font-size:1rem;font-weight:800;color:#091a36;margin-bottom:6px;">@t('home.spotlight_f4_title', 'ONE RELIABLE PARTNER')</h4>
                <p style="font-size:0.85rem;color:#64748b;line-height:1.6;margin:0;">
                    @t('home.spotlight_f4_desc', 'Source, coordinate and supply through one streamlined business relationship.')
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ─── 4.7 B2B Supply & Sourcing Partnership ─────────────── -->
<section class="section" style="padding:var(--space-16) 0;background:linear-gradient(145deg, #091a36 0%, #0c2146 50%, #1e3a8a 100%);color:#ffffff;">
    <div class="container">
        <div class="section-header" style="text-align:center;max-width:820px;margin:0 auto var(--space-10);">
            <div class="section-eyebrow" style="background:rgba(245,158,11,0.18);color:#fbbf24;border:1px solid rgba(245,158,11,0.4);font-weight:700;margin-bottom:8px;padding:4px 14px;border-radius:999px;display:inline-block;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;">@t('home.partnership_eyebrow', 'TRUSTED SUPPLY & SOURCING PARTNER')</div>
            <h2 class="section-title" style="font-size:clamp(1.6rem, 3.5vw, 2.25rem);margin-bottom:10px;color:#ffffff;font-weight:800;">@t('home.partnership_title', 'Supporting Businesses Across Malaysia & Singapore')</h2>
            <p class="section-subtitle" style="font-size:0.95rem;color:#cbd5e1;line-height:1.6;margin:0 auto;text-align:center;">
                @t('home.partnership_subtitle', 'From independent restaurants and food businesses to supermarkets, wholesalers and regional trading partners, we provide consistent supply, temperature-controlled handling and customized sourcing solutions.')
            </p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:24px;margin-bottom:36px;">
            <!-- Pillar 1 -->
            <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.14);border-radius:18px;padding:28px 24px;display:flex;flex-direction:column;gap:12px;backdrop-filter:blur(10px);">
                <div style="font-size:2.2rem;">❄️</div>
                <div style="font-weight:800;color:#ffffff;font-size:1.08rem;">@t('home.pillar_coldchain_title', 'Temperature-Controlled Cold Chain')</div>
                <p style="margin:0;color:#bfdbfe;font-size:0.9rem;line-height:1.6;">
                    @t('home.pillar_coldchain_desc', 'Temperature-controlled handling across sourcing, storage and dispatch to help maintain product quality.')
                </p>
            </div>

            <!-- Pillar 2 -->
            <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.14);border-radius:18px;padding:28px 24px;display:flex;flex-direction:column;gap:12px;backdrop-filter:blur(10px);">
                <div style="font-size:2.2rem;">🔍</div>
                <div style="font-weight:800;color:#ffffff;font-size:1.08rem;">@t('home.pillar_sourcing_title', 'Customised Sourcing Network')</div>
                <p style="margin:0;color:#bfdbfe;font-size:0.9rem;line-height:1.6;">
                    @t('home.pillar_sourcing_desc', 'Looking for specific cuts, origins, packaging, or hard-to-find ingredients? We leverage our regional supplier network to source products tailored to your needs.')
                </p>
            </div>

            <!-- Pillar 3 -->
            <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.14);border-radius:18px;padding:28px 24px;display:flex;flex-direction:column;gap:12px;backdrop-filter:blur(10px);">
                <div style="font-size:2.2rem;">🤝</div>
                <div style="font-weight:800;color:#ffffff;font-size:1.08rem;">@t('home.pillar_pricing_title', 'Flexible Volume & Trade Pricing')</div>
                <p style="margin:0;color:#bfdbfe;font-size:0.9rem;line-height:1.6;">
                    @t('home.pillar_pricing_desc', 'Pricing options designed for retail, foodservice, wholesale and trading requirements, with RFQ support for larger or customised orders.')
                </p>
            </div>
        </div>

        <div style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.2);border-radius:14px;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;backdrop-filter:blur(12px);">
            <div>
                <div style="font-weight:800;color:#ffffff;font-size:1rem;margin-bottom:2px;">@t('home.testi_cta_title', 'BUILDING LONG-TERM BUSINESS RELATIONSHIPS')</div>
                <div style="font-size:0.85rem;color:#93c5fd;font-weight:600;">@t('home.testi_cta_sub', 'Reliable Supply · Responsive Service · Flexible Sourcing')</div>
            </div>
            <a href="{{ route('register') }}" class="btn btn-primary" style="background:linear-gradient(135deg, #fbbf24, #f59e0b);color:#091a36;font-weight:800;border:1px solid #f59e0b;padding:10px 22px;">
                @t('home.testi_cta_btn', 'BECOME A CUSTOMER →')
            </a>
        </div>
    </div>
</section>

<!-- ─── 4.8 Food Sourcing & Supply Insights + Newsletter ────────────────────────── -->
<section class="section seafood-tips-section" style="padding:var(--space-16) 0;background:#f8fafc;border-top:1px solid #e2e8f0">
    <div class="container">
        <div class="culinary-advisory-grid">
            <div class="tips-left-content">
                <span class="section-eyebrow" style="color:#1d4ed8;font-weight:700;">@t('home.tips_eyebrow', 'FOOD SOURCING & SUPPLY INSIGHTS')</span>
                <h2 style="font-family:var(--font-heading);font-size:clamp(1.5rem, 3.5vw, 2rem);color:var(--gray-900);margin:8px 0 12px">
                    @t('home.tips_title', 'KNOW YOUR PRODUCT. BUY WITH CONFIDENCE.')
                </h2>
                <p style="color:var(--gray-600);line-height:1.7;margin-bottom:24px;font-size:0.95rem;">
                    @t('home.tips_subtitle', 'Useful information to help our customers make better decisions when sourcing, storing and handling frozen seafood and food products.')
                </p>

                <div class="tips-cards-grid">
                    <div class="tip-card">
                        <div style="font-size:1.5rem;margin-bottom:6px;">❄️</div>
                        <h4 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:4px;">@t('home.tip1_title', 'Frozen Food Handling')</h4>
                        <p style="font-size:0.8rem;color:#64748b;line-height:1.5;margin:0;">
                            @t('home.tip1_desc', 'Learn practical tips for proper storage, thawing and handling of frozen seafood and other food products.')
                        </p>
                    </div>
                    <div class="tip-card">
                        <div style="font-size:1.5rem;margin-bottom:6px;">📦</div>
                        <h4 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:4px;">@t('home.tip2_title', 'Product Knowledge')</h4>
                        <p style="font-size:0.8rem;color:#64748b;line-height:1.5;margin:0;">
                            @t('home.tip2_desc', 'Understand product specifications, pack sizes, origins, grades and details that matter for your business.')
                        </p>
                    </div>
                    <div class="tip-card">
                        <div style="font-size:1.5rem;margin-bottom:6px;">🔍</div>
                        <h4 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:4px;">@t('home.tip3_title', 'Sourcing Insights')</h4>
                        <p style="font-size:0.8rem;color:#64748b;line-height:1.5;margin:0;">
                            @t('home.tip3_desc', 'Discover useful information about sourcing, product availability, market requirements and supply options.')
                        </p>
                    </div>
                    <div class="tip-card">
                        <div style="font-size:1.5rem;margin-bottom:6px;">🚚</div>
                        <h4 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:4px;">@t('home.tip4_title', 'Cold-Chain Insights')</h4>
                        <p style="font-size:0.8rem;color:#64748b;line-height:1.5;margin:0;">
                            @t('home.tip4_desc', 'Learn more about temperature-controlled storage, handling and distribution, and why cold-chain integrity matters.')
                        </p>
                    </div>
                </div>

                <a href="{{ route('about') }}" class="btn btn-secondary tips-view-btn" style="font-weight:700;padding:10px 20px;">
                    @t('home.tips_view_all', 'VIEW ALL INSIGHTS →')
                </a>
            </div>

            <!-- Newsletter Box -->
            <div class="newsletter-box">
                <div style="font-size:2rem;margin-bottom:10px">✉️</div>
                <h3 style="font-size:1.3rem;font-weight:800;color:var(--gray-900);margin-bottom:6px">@t('home.news_title', 'STAY CONNECTED')</h3>
                <p style="font-size:0.88rem;color:var(--gray-500);line-height:1.6;margin-bottom:18px">
                    @t('home.news_sub', 'Receive product updates, new product announcements, sourcing opportunities and selected business insights from MST.')
                </p>
                
                <form id="newsletterForm" onsubmit="handleNewsletterSubmit(event)" style="display:flex;flex-direction:column;gap:10px">
                    @csrf
                    <div style="position:relative">
                        <input type="email" id="newsletterEmail" name="email" placeholder="@t('home.news_placeholder', 'Enter your email address...')" required class="form-control" style="border-radius:8px;height:44px;font-size:0.9rem;width:100%">
                    </div>
                    <button type="submit" id="newsletterBtn" class="btn btn-primary" style="height:44px;font-weight:700;border-radius:8px">
                        @t('home.news_btn', 'SUBSCRIBE FOR UPDATES')
                    </button>
                    <div id="newsletterMsg" style="display:none;font-size:0.85rem;padding:8px 12px;border-radius:6px;text-align:center"></div>
                    <span style="font-size:0.75rem;color:var(--gray-400);text-align:center">@t('home.news_privacy', 'We respect your privacy. Unsubscribe at any time.')</span>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Quick View Modal
function openQuickViewModal(data) {
    const backdrop = document.getElementById('quickViewBackdrop');
    if (!backdrop) return;

    document.getElementById('qvTitle').textContent = data.name;
    document.getElementById('qvCategory').textContent = data.category;
    document.getElementById('qvSku').textContent = data.sku || 'N/A';
    const st = data.storage_temp || '-18°C';
    const isLiveOrChilled = /live|chilled/i.test(st);
    const ico = data.storage_icon || (isLiveOrChilled ? '🦀' : '❄️');
    document.getElementById('qvStorage').textContent = ico + ' ' + st + (isLiveOrChilled ? '' : ' IQF');
    
    // Resolve dynamic currency price & RM comparison for Quick View
    const cur = window.AppCurrency?.current || 'MYR';
    const qvPriceEl = document.getElementById('qvPrice');
    const qvBaseRmEl = document.getElementById('qvBaseRm');
    if (typeof calculatePriceForElement === 'function' && data.base_rm) {
        const dummyEl = document.createElement('div');
        dummyEl.setAttribute('data-base-rm', data.base_rm);
        if (data.manual_sgd) dummyEl.setAttribute('data-manual-sgd', data.manual_sgd);
        if (data.manual_usd) dummyEl.setAttribute('data-manual-usd', data.manual_usd);
        if (data.manual_wholesale_sgd) dummyEl.setAttribute('data-manual-wholesale-sgd', data.manual_wholesale_sgd);
        if (data.manual_wholesale_usd) dummyEl.setAttribute('data-manual-wholesale-usd', data.manual_wholesale_usd);
        if (data.group) dummyEl.setAttribute('data-group', data.group);
        const res = calculatePriceForElement(dummyEl, cur);
        if (qvPriceEl) qvPriceEl.textContent = res.formatted;
    } else if (qvPriceEl) {
        qvPriceEl.textContent = data.price_formatted;
    }

    if (qvBaseRmEl) {
        if (cur !== 'MYR' && data.base_rm) {
            qvBaseRmEl.textContent = 'RM ' + parseFloat(data.base_rm).toFixed(2);
            qvBaseRmEl.style.display = 'block';
        } else {
            qvBaseRmEl.style.display = 'none';
        }
    }

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

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeQuickViewModal();
    }
});

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
