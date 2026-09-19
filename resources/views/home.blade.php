@extends('layouts.app')

@section('title', 'MST Import and Export Sdn Bhd — Frozen Food Sourcing, Trading & Distribution')
@section('meta_description', 'MST Import and Export Sdn Bhd provides frozen food sourcing, trading and distribution solutions for restaurants, food businesses, retailers, wholesalers and trading partners.')

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
                🏆 ESTABLISHED IN 2014 · JOHOR BAHRU, MALAYSIA
            </div>

            <h1 class="mika-hero-h1">
                MORE THAN A SUPPLIER.<br>
                <span class="mika-hero-gradient">YOUR SOURCING &amp;</span><br>
                <span class="mika-hero-gradient">SUPPLY PARTNER.</span>
            </h1>

            <div style="font-size:1.05rem;font-weight:700;color:#93c5fd;margin-bottom:12px;letter-spacing:0.02em;">
                Frozen Seafood · Meat · Frozen Food
            </div>

            <p class="mika-hero-sub">
                MST Import and Export Sdn Bhd provides frozen food sourcing, trading and distribution solutions for restaurants, food businesses, retailers, wholesalers and trading partners. Can't find what you need? We source it. With our sourcing network, cold-chain infrastructure and supply capabilities, we help customers find the right products, manage supply requirements and build reliable long-term partnerships across regional and international markets.
            </p>

            {{-- Customer type pills --}}
            <div class="mika-type-pills">
                <span class="mika-pill">🛒 Retail</span>
                <span class="mika-pill">🏪 Walk-in</span>
                <span class="mika-pill">🏭 Wholesale</span>
                <span class="mika-pill">📦 Trading</span>
                <span class="mika-pill">🌏 International</span>
            </div>

            {{-- CTAs --}}
            <div class="mika-hero-cta">
                <a href="{{ route('shop.index') }}" class="mika-btn-primary">
                    Explore Our Products
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <a href="{{ route('contact') }}#quote" class="mika-btn-ghost">Request a Quote</a>
            </div>

        </div>

        {{-- RIGHT: Hero Image (Vertically and horizontally centered) --}}
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

    {{-- BOTTOM: Full Width Centered Stats Bar & Trust Row --}}
    <div class="container mika-hero-bottom-container">
        <div class="mika-stats-bar">
            <div class="mika-stat-item">
                <div class="mika-stat-num">2014</div>
                <div class="mika-stat-lbl">Established</div>
            </div>
            <div class="mika-stat-divider"></div>
            <div class="mika-stat-item">
                <div class="mika-stat-num" style="font-size:1.35rem;">CUSTOMISED</div>
                <div class="mika-stat-lbl">Sourcing</div>
            </div>
            <div class="mika-stat-divider"></div>
            <div class="mika-stat-item">
                <div class="mika-stat-num" style="font-size:1.35rem;">COLD-CHAIN</div>
                <div class="mika-stat-lbl">Storage &amp; Handling</div>
            </div>
            <div class="mika-stat-divider"></div>
            <div class="mika-stat-item">
                <div class="mika-stat-num">B2B</div>
                <div class="mika-stat-lbl">Supply &amp; Distribution</div>
            </div>
        </div>

        {{-- Centered trust badges --}}
        <div class="mika-trust-row">
            <div class="mika-trust-badge">✅ Quality Assured</div>
            <div class="mika-trust-badge">🔬 HACCP &amp; GMP Aligned</div>
            <div class="mika-trust-badge">❄️ -18°C to -25°C Cold Chain</div>
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
    margin-bottom: 10px;
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
    background: linear-gradient(135deg, #0a2540 0%, #1e3a8a 100%);
    border-radius: 16px;
    padding: 26px 28px;
    color: #ffffff;
    box-shadow: 0 8px 24px rgba(10,37,64,0.15);
    grid-column: 1 / -1;
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
    .seafood-tips-section {
        padding-top: var(--space-10) !important;
        padding-bottom: var(--space-10) !important;
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
</style>



<!-- ─── Category Section (6 Categories + Custom Sourcing) ───────────────── -->
<section class="section category-section" style="padding-top: var(--space-16); padding-bottom: var(--space-12);">
    <div class="container">
        <div class="section-header" style="text-align:center;max-width:760px;margin:0 auto var(--space-10);">
            <div class="section-eyebrow" style="color:var(--seagreen-700);font-weight:700;margin-bottom:8px">BROWSE BY CATEGORY</div>
            <h2 class="section-title" style="font-size:clamp(1.6rem, 3.5vw, 2.25rem);margin-bottom:10px;">What Are You Looking For?</h2>
            <p class="section-subtitle" style="font-size:0.95rem;color:var(--text-muted);line-height:1.6;margin:0 auto;text-align:center;">
                From seafood and meat to frozen food, food ingredients and cuisine-specific products, explore our growing range of products for restaurants, food businesses, retailers, wholesalers and trading partners.
            </p>
        </div>

        <div class="categories-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:20px;">
            <!-- 1. Seafood -->
            <a href="{{ route('shop.index', ['category' => 'seafood-products']) }}" class="category-card" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 20px;text-align:center;text-decoration:none;transition:all 0.25s ease;display:flex;flex-direction:column;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='#93c5fd';this.style.boxShadow='0 12px 24px rgba(37,99,235,0.08)'" onmouseout="this.style.transform='none';this.style.borderColor='#e2e8f0';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)'">
                <span class="category-icon" style="font-size:2.4rem;margin-bottom:10px;display:inline-block;">🐟</span>
                <div class="category-name" style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">SEAFOOD</div>
                <div style="font-size:0.82rem;color:#64748b;line-height:1.5;">Fish · Prawns · Squid · Crab · Shellfish &amp; More</div>
            </a>

            <!-- 2. Meat -->
            <a href="{{ route('shop.index', ['category' => 'meat-chicken']) }}" class="category-card" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 20px;text-align:center;text-decoration:none;transition:all 0.25s ease;display:flex;flex-direction:column;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='#93c5fd';this.style.boxShadow='0 12px 24px rgba(37,99,235,0.08)'" onmouseout="this.style.transform='none';this.style.borderColor='#e2e8f0';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)'">
                <span class="category-icon" style="font-size:2.4rem;margin-bottom:10px;display:inline-block;">🥩</span>
                <div class="category-name" style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">MEAT</div>
                <div style="font-size:0.82rem;color:#64748b;line-height:1.5;">Chicken · Beef · Pork · Other Frozen Meat Products</div>
            </a>

            <!-- 3. Frozen Food -->
            <a href="{{ route('shop.index', ['category' => 'frozen-product-food']) }}" class="category-card" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 20px;text-align:center;text-decoration:none;transition:all 0.25s ease;display:flex;flex-direction:column;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='#93c5fd';this.style.boxShadow='0 12px 24px rgba(37,99,235,0.08)'" onmouseout="this.style.transform='none';this.style.borderColor='#e2e8f0';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)'">
                <span class="category-icon" style="font-size:2.4rem;margin-bottom:10px;display:inline-block;">❄️</span>
                <div class="category-name" style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">FROZEN FOOD</div>
                <div style="font-size:0.82rem;color:#64748b;line-height:1.5;">Processed Foods · Ready-to-Cook · Snacks · Foodservice Products</div>
            </a>

            <!-- 4. Food Ingredients -->
            <a href="{{ route('shop.index', ['category' => 'steamboat']) }}" class="category-card" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 20px;text-align:center;text-decoration:none;transition:all 0.25s ease;display:flex;flex-direction:column;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='#93c5fd';this.style.boxShadow='0 12px 24px rgba(37,99,235,0.08)'" onmouseout="this.style.transform='none';this.style.borderColor='#e2e8f0';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)'">
                <span class="category-icon" style="font-size:2.4rem;margin-bottom:10px;display:inline-block;">🧂</span>
                <div class="category-name" style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">FOOD INGREDIENTS</div>
                <div style="font-size:0.82rem;color:#64748b;line-height:1.5;">Raw Materials · Fish Paste · Sauces · Condiments · Cooking Ingredients</div>
            </a>

            <!-- 5. Cuisine Ingredients -->
            <a href="{{ route('shop.index', ['category' => 'dimsum']) }}" class="category-card" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 20px;text-align:center;text-decoration:none;transition:all 0.25s ease;display:flex;flex-direction:column;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='#93c5fd';this.style.boxShadow='0 12px 24px rgba(37,99,235,0.08)'" onmouseout="this.style.transform='none';this.style.borderColor='#e2e8f0';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)'">
                <span class="category-icon" style="font-size:2.4rem;margin-bottom:10px;display:inline-block;">🌏</span>
                <div class="category-name" style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">CUISINE INGREDIENTS</div>
                <div style="font-size:0.82rem;color:#64748b;line-height:1.5;">Japanese · Korean · Chinese · Western Cuisine Ingredients</div>
            </a>

            <!-- 6. Desserts & Sweet Treats -->
            <a href="{{ route('shop.index', ['category' => 'dessert']) }}" class="category-card" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 20px;text-align:center;text-decoration:none;transition:all 0.25s ease;display:flex;flex-direction:column;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='#93c5fd';this.style.boxShadow='0 12px 24px rgba(37,99,235,0.08)'" onmouseout="this.style.transform='none';this.style.borderColor='#e2e8f0';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)'">
                <span class="category-icon" style="font-size:2.4rem;margin-bottom:10px;display:inline-block;">🍰</span>
                <div class="category-name" style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">DESSERTS &amp; SWEET TREATS</div>
                <div style="font-size:0.82rem;color:#64748b;line-height:1.5;">Frozen Desserts · Pastries · Cakes · Sweet Products &amp; More</div>
            </a>

            <!-- 7. Customised Sourcing (Special Card) -->
            <div class="category-card-sourcing">
                <div class="sourcing-card-inner">
                    <div class="sourcing-card-content">
                        <span style="font-size:2.8rem;flex-shrink:0;">📦</span>
                        <div>
                            <div style="font-size:0.75rem;font-weight:700;color:#93c5fd;letter-spacing:0.08em;text-transform:uppercase;">YOU NEED IT. WE SOURCE IT.</div>
                            <div style="font-size:1.3rem;font-weight:800;color:#ffffff;margin:2px 0 4px;">CUSTOMISED SOURCING SOLUTIONS</div>
                            <p style="color:#dbeafe;font-size:0.92rem;margin:0;max-width:720px;line-height:1.5;">
                                Can't find what you need? Tell us what you're looking for. We work with our sourcing network to identify suitable products and supply options based on your requirements.
                            </p>
                        </div>
                    </div>
                    <div class="sourcing-card-actions">
                        <a href="{{ route('shop.index') }}" class="btn btn-secondary" style="background:rgba(255,255,255,0.15);color:white;border-color:rgba(255,255,255,0.3);padding:10px 18px;font-size:0.88rem;font-weight:700;">
                            EXPLORE PRODUCTS →
                        </a>
                        <a href="{{ route('contact') }}#quote" class="btn btn-primary" style="background:#3b82f6;border-color:#3b82f6;color:white;padding:10px 20px;font-size:0.88rem;font-weight:700;">
                            REQUEST A QUOTE
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── Featured Products ─────────────────────────────────────────────────── -->
@if($featuredProducts->count())
<section class="section featured-products-section" style="padding: var(--space-16) 0;">
    <div class="container">

        {{-- Section Header with inline "View All" on desktop --}}
        <div style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom: var(--space-8);">
            <div>
                <div class="section-eyebrow" style="margin-bottom:8px;">
                    ⭐ FEATURED PRODUCTS
                </div>
                <h2 style="font-family:var(--font-heading);font-size:clamp(1.6rem,4vw,2.25rem);font-weight:800;color:var(--text-primary);margin:0 0 8px;">
                    PRODUCT HIGHLIGHTS
                </h2>
                <p style="color:var(--text-muted);font-size:0.95rem;margin:0;max-width:760px;line-height:1.5;">
                    Explore a selection of products from our current range of frozen seafood, meat and frozen food.<br>
                    <span style="font-size:0.88rem;color:#64748b;">Retail prices are displayed for reference. Wholesale and trading customers can <a href="{{ route('register') }}" style="color:#1d4ed8;font-weight:600;">register</a> for account-based pricing or contact our team for customised quotations.</span>
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

<!-- ─── 4.4 How It Works ────────────────────────────────────────────────── -->
<section class="section" style="background: linear-gradient(180deg, #f8fafc, #ffffff); padding: var(--space-16) 0;">
    <div class="container">
        <div class="section-header" style="text-align:center;max-width:720px;margin:0 auto var(--space-10);">
            <div class="section-eyebrow" style="color:var(--seagreen-700);font-weight:700;margin-bottom:8px">HOW IT WORKS</div>
            <h2 class="section-title" style="font-size:clamp(1.6rem, 3.5vw, 2.25rem);margin-bottom:8px;">A Smarter Way to Buy</h2>
            <p class="section-subtitle" style="font-size:0.95rem;color:var(--text-muted);margin:0 auto;line-height:1.6;text-align:center;">
                Different customers. Different needs. One platform.<br>
                Choose the account type that fits your business and access the products, pricing and purchasing options designed for you.
            </p>
        </div>
        <div class="how-it-works-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:22px;">
            <div class="glass-card p-6" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:28px 22px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
                <div style="font-size:2.6rem;margin-bottom:var(--space-3)">🛒</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:8px;font-size:1.15rem;font-weight:800;color:#0f172a;">RETAIL</h3>
                <p class="text-sm text-muted" style="line-height:1.6;font-size:0.88rem;color:#64748b;margin-bottom:8px;">
                    Register for free, browse retail prices, add products to your cart and pay online.
                </p>
                <div style="font-size:0.8rem;font-weight:600;color:#1d4ed8;">Delivery or self-collection available.</div>
            </div>
            <div class="glass-card p-6" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:28px 22px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
                <div style="font-size:2.6rem;margin-bottom:var(--space-3)">📱</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:8px;font-size:1.15rem;font-weight:800;color:#0f172a;">WALK-IN</h3>
                <p class="text-sm text-muted" style="line-height:1.6;font-size:0.88rem;color:#64748b;margin-bottom:8px;">
                    Scan our QR code in-store to access walk-in pricing.
                </p>
                <div style="font-size:0.8rem;font-weight:600;color:#059669;">No registration required. Pay &amp; self-collect.</div>
            </div>
            <div class="glass-card p-6" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:28px 22px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
                <div style="font-size:2.6rem;margin-bottom:var(--space-3)">🏭</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:8px;font-size:1.15rem;font-weight:800;color:#0f172a;">WHOLESALE</h3>
                <p class="text-sm text-muted" style="line-height:1.6;font-size:0.88rem;color:#64748b;margin-bottom:8px;">
                    Register for a wholesale account. Once approved, access exclusive wholesale pricing and applicable MOQ requirements.
                </p>
                <div style="font-size:0.8rem;font-weight:600;color:#d97706;">Built for restaurants, retailers &amp; bulk buyers.</div>
            </div>
            <div class="glass-card p-6" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:28px 22px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
                <div style="font-size:2.6rem;margin-bottom:var(--space-3)">📦</div>
                <h3 style="font-family:var(--font-heading);margin-bottom:8px;font-size:1.15rem;font-weight:800;color:#0f172a;">TRADING</h3>
                <p class="text-sm text-muted" style="line-height:1.6;font-size:0.88rem;color:#64748b;margin-bottom:8px;">
                    Register for a trading account to access trading prices, bulk purchasing options and Request for Quotation (RFQ) for market-priced products.
                </p>
                <div style="font-size:0.8rem;font-weight:600;color:#7c3aed;">For distributors, traders &amp; bulk volume.</div>
            </div>
        </div>
    </div>
</section>

<!-- ─── 4.5 Our Commitment to Quality ──────────────────────────────────── -->
<section class="section" style="padding:var(--space-16) 0;background:#ffffff;border-top:1px solid #e2e8f0;">
    <div class="container">
        <div class="section-header" style="text-align:center;max-width:760px;margin:0 auto var(--space-10);">
            <div class="section-eyebrow" style="color:var(--seagreen-700);font-weight:700;margin-bottom:8px">OUR COMMITMENT TO QUALITY</div>
            <h2 class="section-title" style="font-size:clamp(1.6rem, 3.5vw, 2.25rem);margin-bottom:10px;">QUALITY. INTEGRITY. RELIABLE COLD-CHAIN.</h2>
            <p class="section-subtitle" style="font-size:0.95rem;color:var(--text-muted);line-height:1.6;margin:0 auto;text-align:center;">
                At MST, product quality begins with responsible sourcing and continues through proper handling, temperature-controlled storage and reliable supply. We focus on maintaining product integrity from receiving and storage to order preparation and dispatch.
            </p>
        </div>

        <div class="cold-chain-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:22px;">
            <div class="card" style="padding:28px 24px;border-radius:16px;border:1px solid #e2e8f0;background:#f8fafc;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#059669">
                    ❄️
                </div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin-bottom:8px">TEMPERATURE-CONTROLLED STORAGE</h3>
                <p style="font-size:0.875rem;color:#64748b;line-height:1.65;margin:0">
                    Our cold storage facilities are designed to maintain appropriate frozen temperatures (-18°C to -25°C) and protect product quality throughout storage.
                </p>
            </div>

            <div class="card" style="padding:28px 24px;border-radius:16px;border:1px solid #e2e8f0;background:#f8fafc;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#2563eb">
                    🔍
                </div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin-bottom:8px">CAREFUL PRODUCT SOURCING</h3>
                <p style="font-size:0.875rem;color:#64748b;line-height:1.65;margin:0">
                    We work with our sourcing network to identify suitable seafood, meat and frozen food products according to customer requirements, specifications and supply needs.
                </p>
            </div>

            <div class="card" style="padding:28px 24px;border-radius:16px;border:1px solid #e2e8f0;background:#f8fafc;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#d97706">
                    📦
                </div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin-bottom:8px">HYGIENIC HANDLING &amp; PACKING</h3>
                <p style="font-size:0.875rem;color:#64748b;line-height:1.65;margin:0">
                    Structured receiving, handling, packing and order preparation processes help maintain product quality and operational consistency (following HACCP &amp; GMP principles).
                </p>
            </div>

            <div class="card" style="padding:28px 24px;border-radius:16px;border:1px solid #e2e8f0;background:#f8fafc;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;border-radius:12px;background:#f5f3ff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:var(--space-4);color:#7c3aed">
                    🚚
                </div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin-bottom:8px">RELIABLE SUPPLY</h3>
                <p style="font-size:0.875rem;color:#64748b;line-height:1.65;margin:0">
                    From storage and order preparation to dispatch, our operations are designed to support consistent supply for retail, wholesale, trading and commercial customers.
                </p>
            </div>
        </div>

        <div style="margin-top:32px;text-align:center;padding:18px 24px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
            <div style="font-size:1rem;font-weight:800;color:#0f172a;margin-bottom:4px;">
                BUILT ON INTEGRITY. DELIVERED WITH CONSISTENCY.
            </div>
            <div style="font-size:0.85rem;color:#1d4ed8;font-weight:700;">
                MST Import and Export Sdn Bhd · Flow with Integrity, Grow with Strength.
            </div>
        </div>
    </div>
</section>

<!-- ─── 4.6 Customised Sourcing Spotlight ───────────────────────────────── -->
<section class="section" style="padding:var(--space-16) 0;background:linear-gradient(145deg, #091a36 0%, #173b75 100%);color:#ffffff;">
    <div class="container">
        <div style="max-width:820px;margin:0 auto 40px;text-align:center;">
            <span style="background:rgba(255,255,255,0.15);color:#93c5fd;font-size:0.75rem;font-weight:700;padding:4px 14px;border-radius:999px;text-transform:uppercase;letter-spacing:0.08em;display:inline-block;margin-bottom:12px;">
                ✨ CUSTOMISED SOURCING
            </span>
            <h2 style="font-family:var(--font-heading);font-size:clamp(1.6rem, 4vw, 2.3rem);color:#ffffff;margin-bottom:6px;line-height:1.25;">
                CAN'T FIND WHAT YOU NEED?
            </h2>
            <div style="font-size:1.25rem;font-weight:800;color:#60a5fa;margin-bottom:16px;">
                YOU NEED IT. WE SOURCE IT.
            </div>
            <p style="color:#dbeafe;font-size:0.95rem;line-height:1.7;margin:0 0 24px;">
                Looking for a specific seafood, meat or frozen food product that is not currently listed in our catalogue? Tell us what you need — from product type and specifications to pack size, origin and quantity. Our team can work with our sourcing network to identify suitable products and supply options for your business. Whether you are a restaurant, food business, retailer, wholesaler or trading partner, we help simplify the sourcing process through one reliable supply partner.
            </p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('contact') }}#quote" class="btn btn-primary" style="background:#2563eb;border-color:#2563eb;font-weight:700;padding:12px 24px;">
                    REQUEST A QUOTE →
                </a>
                <a href="{{ route('contact') }}" class="btn btn-secondary" style="background:rgba(255,255,255,0.15);color:white;border-color:rgba(255,255,255,0.3);font-weight:700;padding:12px 22px;">
                    TELL US WHAT YOU NEED →
                </a>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:20px;">
            <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:16px;padding:24px 20px;backdrop-filter:blur(8px);">
                <div style="font-size:2rem;margin-bottom:12px;">🔍</div>
                <h4 style="font-size:1rem;font-weight:800;color:#ffffff;margin-bottom:6px;">PRODUCT-SPECIFIC SOURCING</h4>
                <p style="font-size:0.85rem;color:#bfdbfe;line-height:1.6;margin:0;">
                    Looking for a particular product or specification? Tell us your requirements.
                </p>
            </div>
            <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:16px;padding:24px 20px;backdrop-filter:blur(8px);">
                <div style="font-size:2rem;margin-bottom:12px;">🌏</div>
                <h4 style="font-size:1rem;font-weight:800;color:#ffffff;margin-bottom:6px;">MULTI-SOURCE NETWORK</h4>
                <p style="font-size:0.85rem;color:#bfdbfe;line-height:1.6;margin:0;">
                    We work with sourcing partners to identify suitable products and supply options.
                </p>
            </div>
            <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:16px;padding:24px 20px;backdrop-filter:blur(8px);">
                <div style="font-size:2rem;margin-bottom:12px;">📦</div>
                <h4 style="font-size:1rem;font-weight:800;color:#ffffff;margin-bottom:6px;">FLEXIBLE QUANTITY</h4>
                <p style="font-size:0.85rem;color:#bfdbfe;line-height:1.6;margin:0;">
                    From regular supply to specific business requirements, we work around your needs.
                </p>
            </div>
            <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:16px;padding:24px 20px;backdrop-filter:blur(8px);">
                <div style="font-size:2rem;margin-bottom:12px;">🤝</div>
                <h4 style="font-size:1rem;font-weight:800;color:#ffffff;margin-bottom:6px;">ONE RELIABLE PARTNER</h4>
                <p style="font-size:0.85rem;color:#bfdbfe;line-height:1.6;margin:0;">
                    Source, coordinate and supply through one streamlined business relationship.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ─── 4.7 Customer Testimonials (Professional B2B Focus) ─────────────── -->
<section class="section" style="padding:var(--space-16) 0;background:#ffffff;border-top:1px solid #e2e8f0;">
    <div class="container">
        <div class="section-header" style="text-align:center;max-width:760px;margin:0 auto var(--space-10);">
            <div class="section-eyebrow" style="color:var(--seagreen-700);font-weight:700;margin-bottom:8px">CUSTOMER TESTIMONIALS</div>
            <h2 class="section-title" style="font-size:clamp(1.6rem, 3.5vw, 2.25rem);margin-bottom:10px;">What Our Customers Say</h2>
            <p class="section-subtitle" style="font-size:0.95rem;color:var(--text-muted);line-height:1.6;margin:0 auto;text-align:center;">
                Long-term relationships are at the heart of MST. From restaurants and food businesses to retailers, wholesalers and trading partners, we value the trust our customers place in us to support their day-to-day supply needs.
            </p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:24px;margin-bottom:36px;">
            <!-- Review 1 -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:18px;padding:30px 26px;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 2px 10px rgba(0,0,0,0.02);">
                <div>
                    <div style="color:#eab308;font-size:1.1rem;margin-bottom:12px;">★★★★★</div>
                    <blockquote style="margin:0 0 16px;color:#334155;font-size:0.95rem;line-height:1.7;font-style:italic;">
                        “MST has been reliable in helping us source and supply the frozen food products we need. Their team is responsive and easy to work with.”
                    </blockquote>
                </div>
                <div style="font-weight:700;color:#0f172a;font-size:0.88rem;border-top:1px solid #e2e8f0;padding-top:12px;">
                    — Restaurant Customer
                </div>
            </div>

            <!-- Review 2 -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:18px;padding:30px 26px;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 2px 10px rgba(0,0,0,0.02);">
                <div>
                    <div style="color:#eab308;font-size:1.1rem;margin-bottom:12px;">★★★★★</div>
                    <blockquote style="margin:0 0 16px;color:#334155;font-size:0.95rem;line-height:1.7;font-style:italic;">
                        “We appreciate the flexibility of their sourcing service. When we need a product that is not in the regular range, the team helps us look for suitable options.”
                    </blockquote>
                </div>
                <div style="font-weight:700;color:#0f172a;font-size:0.88rem;border-top:1px solid #e2e8f0;padding-top:12px;">
                    — F&amp;B Customer
                </div>
            </div>

            <!-- Review 3 -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:18px;padding:30px 26px;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 2px 10px rgba(0,0,0,0.02);">
                <div>
                    <div style="color:#eab308;font-size:1.1rem;margin-bottom:12px;">★★★★★</div>
                    <blockquote style="margin:0 0 16px;color:#334155;font-size:0.95rem;line-height:1.7;font-style:italic;">
                        “Good communication, reliable supply and straightforward service. MST has become one of our regular suppliers.”
                    </blockquote>
                </div>
                <div style="font-weight:700;color:#0f172a;font-size:0.88rem;border-top:1px solid #e2e8f0;padding-top:12px;">
                    — Wholesale Customer
                </div>
            </div>
        </div>

        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:14px;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div>
                <div style="font-weight:800;color:#1e3a8a;font-size:1rem;margin-bottom:2px;">BUILDING LONG-TERM BUSINESS RELATIONSHIPS</div>
                <div style="font-size:0.85rem;color:#3b82f6;font-weight:600;">Reliable Supply · Responsive Service · Flexible Sourcing</div>
            </div>
            <a href="{{ route('register') }}" class="btn btn-primary" style="font-weight:700;padding:10px 22px;">
                BECOME A CUSTOMER →
            </a>
        </div>
    </div>
</section>

<!-- ─── 4.8 Seafood Tips & Insights + Newsletter ────────────────────────── -->
<section class="section seafood-tips-section" style="padding:var(--space-16) 0;background:#f8fafc;border-top:1px solid #e2e8f0">
    <div class="container">
        <div class="culinary-advisory-grid">
            <div class="tips-left-content">
                <span class="section-eyebrow" style="color:var(--seagreen-700);font-weight:700;">SEAFOOD TIPS &amp; INSIGHTS</span>
                <h2 style="font-family:var(--font-heading);font-size:clamp(1.5rem, 3.5vw, 2rem);color:var(--gray-900);margin:8px 0 12px">
                    KNOW YOUR PRODUCT. BUY WITH CONFIDENCE.
                </h2>
                <p style="color:var(--gray-600);line-height:1.7;margin-bottom:24px;font-size:0.95rem;">
                    Useful information to help our customers make better decisions when sourcing, storing and handling frozen seafood and food products.
                </p>

                <div class="tips-cards-grid">
                    <div class="tip-card">
                        <div style="font-size:1.5rem;margin-bottom:6px;">❄️</div>
                        <h4 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:4px;">Frozen Food Handling</h4>
                        <p style="font-size:0.8rem;color:#64748b;line-height:1.5;margin:0;">
                            Learn practical tips for proper storage, thawing and handling of frozen seafood and other food products.
                        </p>
                    </div>
                    <div class="tip-card">
                        <div style="font-size:1.5rem;margin-bottom:6px;">📦</div>
                        <h4 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:4px;">Product Knowledge</h4>
                        <p style="font-size:0.8rem;color:#64748b;line-height:1.5;margin:0;">
                            Understand product specifications, pack sizes, origins, grades and details that matter for your business.
                        </p>
                    </div>
                    <div class="tip-card">
                        <div style="font-size:1.5rem;margin-bottom:6px;">🔍</div>
                        <h4 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:4px;">Sourcing Insights</h4>
                        <p style="font-size:0.8rem;color:#64748b;line-height:1.5;margin:0;">
                            Discover useful information about sourcing, product availability, market requirements and supply options.
                        </p>
                    </div>
                    <div class="tip-card">
                        <div style="font-size:1.5rem;margin-bottom:6px;">🚚</div>
                        <h4 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:4px;">Cold-Chain Insights</h4>
                        <p style="font-size:0.8rem;color:#64748b;line-height:1.5;margin:0;">
                            Learn more about temperature-controlled storage, handling and distribution, and why cold-chain integrity matters.
                        </p>
                    </div>
                </div>

                <a href="{{ route('about') }}" class="btn btn-secondary tips-view-btn" style="font-weight:700;padding:10px 20px;">
                    VIEW ALL INSIGHTS →
                </a>
            </div>

            <!-- Newsletter Box -->
            <div class="newsletter-box">
                <div style="font-size:2rem;margin-bottom:10px">✉️</div>
                <h3 style="font-size:1.3rem;font-weight:800;color:var(--gray-900);margin-bottom:6px">STAY CONNECTED</h3>
                <p style="font-size:0.88rem;color:var(--gray-500);line-height:1.6;margin-bottom:18px">
                    Receive product updates, sourcing opportunities, new product announcements and selected business insights from MST.
                </p>
                
                <form id="newsletterForm" onsubmit="handleNewsletterSubmit(event)" style="display:flex;flex-direction:column;gap:10px">
                    @csrf
                    <div style="position:relative">
                        <input type="email" id="newsletterEmail" name="email" placeholder="Enter your email address..." required class="form-control" style="border-radius:8px;height:44px;font-size:0.9rem;width:100%">
                    </div>
                    <button type="submit" id="newsletterBtn" class="btn btn-primary" style="height:44px;font-weight:700;border-radius:8px">
                        SUBSCRIBE FOR UPDATES
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
