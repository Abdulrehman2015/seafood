@extends('layouts.app')
@section('title', __t('about.meta_title', 'About Us — MST Import and Export Sdn Bhd'))
@section('meta_description', __t('about.meta_desc', 'Learn about MST Import and Export Sdn Bhd. Sourcing, trading and distribution of seafood, meat, frozen food and food ingredients across Malaysia, Singapore and regional markets.'))

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 @t('nav.home', 'Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('nav.about', 'About Us')</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap;">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:3px 10px;border-radius:999px;font-size:0.72rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        @t('about.est_badge', '🏆 Established in 2014 · Johor Bahru, Malaysia')
                    </span>
                    <span style="color:#bae6fd;font-size:0.8rem">@t('about.cold_chain_supply', 'Regional & International Cold-Chain Supply')</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.4rem);margin-bottom:6px;letter-spacing:-0.02em">
                    @t('about.header_title', 'About Us — MST Import and Export')
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.98rem;max-width:760px;line-height:1.55;margin:0">
                    <strong>@t('about.header_subtitle_strong', 'More Than a Supplier. Your Sourcing & Supply Partner.')</strong><br>
                    @t('about.header_subtitle_text', 'Supplying seafood, meat, frozen food and selected food ingredients to commercial customers across Malaysia, Singapore and growing regional and international markets.')
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div style="font-size:0.85rem;padding:6px 14px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb;font-weight:600">
                    @t('about.motto', 'Flow with Integrity, Grow with Strength.')
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container about-page-container">

    <!-- ─── Section 1: About MST — Who We Are ───────────────────────────────── -->
    <section class="about-section">
        <div class="about-who-grid">
            <div>
                <div class="section-eyebrow" style="color:#1d4ed8;font-weight:700;margin-bottom:8px">@t('about.section_1_eyebrow', '1. ABOUT MST — WHO WE ARE')</div>
                <h2 style="font-family:var(--font-heading);font-size:clamp(1.6rem, 3.5vw, 2.2rem);color:#0f172a;margin-bottom:18px;line-height:1.3">
                    @t('about.section_1_title', 'From Johor Bahru Roots to a Regional & International Supply Platform')
                </h2>
                <div class="about-intro-card">
                    <p style="margin-bottom:14px;line-height:1.7;color:#334155;">
                        {!! __t('about.who_we_are_p1', '<strong>MST Import and Export Sdn Bhd</strong> is a Johor-based frozen food sourcing, trading and distribution company, supplying seafood, meat, frozen food and selected food ingredients to commercial customers.') !!}
                    </p>
                    <p style="margin-bottom:14px;line-height:1.7;color:#334155;">
                        {!! __t('about.who_we_are_p2', 'Founded in <strong>2014 as Mika Seafood Trading</strong>, the business evolved into <strong>MST Import and Export Sdn Bhd in 2024</strong>, marking a new stage of growth and expansion beyond traditional seafood trading.') !!}
                    </p>
                    <p style="margin-bottom:16px;line-height:1.7;color:#334155;">
                        {!! __t('about.who_we_are_p3', 'Today, MST is building a stronger supply platform through <strong>cold storage, customised sourcing, reliable supply and distribution</strong>, with a clear focus on serving customers across Malaysia, Singapore and growing regional and international markets.') !!}
                    </p>
                    <div style="padding-top:14px;border-top:1px dashed #cbd5e1;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                        <span style="font-weight:700;color:#1d4ed8;font-size:0.95rem;letter-spacing:0.02em;">
                            ✨ @t('about.company_motto_val', 'Flow with Integrity, Grow with Strength.')
                        </span>
                        <span style="font-size:0.85rem;color:#64748b;">@t('about.company_name_full', 'MST Import and Export Sdn Bhd')</span>
                    </div>
                </div>
            </div>

            <!-- Visual Feature Box -->
            <div class="about-feature-box">
                <div class="feature-orb-1"></div>
                <div class="feature-orb-2"></div>
                <div class="feature-grid-overlay"></div>

                <div style="position:relative;z-index:2;">
                    <div style="font-size:2.4rem;margin-bottom:14px;">🌏</div>
                    <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.18);padding:4px 12px;border-radius:999px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#93c5fd;margin-bottom:12px;">
                        <span style="width:6px;height:6px;border-radius:50%;background:#22d3ee;display:inline-block;"></span>
                        @t('about.strategic_platform', 'STRATEGIC PLATFORM')
                    </div>
                    
                    <h3 style="color:#ffffff !important;font-family:'Outfit',sans-serif;font-size:1.45rem;font-weight:800;line-height:1.3;margin-bottom:14px;letter-spacing:-0.01em;">
                        @t('about.strategic_title', 'Scalable Frozen Food Supply & Sourcing')
                    </h3>
                    
                    <p style="color:rgba(255,255,255,0.88);font-size:0.92rem;line-height:1.65;margin-bottom:20px;">
                        @t('about.strategic_desc', "Located in Johor's established industrial corridor, MST connects commercial kitchens, food businesses, wholesalers, distributors and overseas buyers with established sourcing channels and reliable supply solutions.")
                    </p>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div class="feature-check-item">
                            <span class="feature-check-icon">✓</span>
                            <span>@t('about.check_1', 'Temperature-Controlled Storage (-18°C to -25°C)')</span>
                        </div>
                        <div class="feature-check-item">
                            <span class="feature-check-icon">✓</span>
                            <span>@t('about.check_2', 'Tailored Product Specifications & Sourcing')</span>
                        </div>
                        <div class="feature-check-item">
                            <span class="feature-check-icon">✓</span>
                            <span>@t('about.check_3', 'Regional & International Supply Capability')</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── Section 2: Our Journey — Brand Story ────────────────────────────── -->
    <section class="about-section">
        <div style="text-align:center;max-width:700px;margin:0 auto 40px;">
            <div class="section-eyebrow" style="color:#1d4ed8;font-weight:700;margin-bottom:8px">@t('about.section_2_eyebrow', '2. OUR JOURNEY — BRAND STORY')</div>
            <h2 style="font-family:var(--font-heading);font-size:clamp(1.6rem, 3.5vw, 2.2rem);color:#0f172a;margin-bottom:12px;">
                @t('about.section_2_title', 'Over a Decade of Growth')
            </h2>
            <p style="color:#64748b;font-size:0.95rem;line-height:1.6;margin:0;">
                @t('about.section_2_desc', 'From our roots in frozen seafood trading in Johor Bahru to a growing multi-category frozen food sourcing and supply business.')
            </p>
        </div>

        <div class="about-timeline-grid">
            <!-- 2014 -->
            <div class="timeline-card timeline-card-1">
                <div class="timeline-badge timeline-badge-1">2014</div>
                <h4 class="timeline-title">@t('about.timeline_2014_title', 'Johor Bahru Roots')</h4>
                <p class="timeline-desc">
                    {!! __t('about.timeline_2014_desc', '<strong>Mika Seafood Trading</strong> began its journey in Johor Bahru, focusing on frozen seafood supply and building long-term relationships with customers and suppliers.') !!}
                </p>
            </div>

            <!-- 2024 -->
            <div class="timeline-card timeline-card-2">
                <div class="timeline-badge timeline-badge-2">2024</div>
                <h4 class="timeline-title">@t('about.timeline_2024_title', 'A New Chapter')</h4>
                <p class="timeline-desc">
                    {!! __t('about.timeline_2024_desc', 'Mika Seafood Trading transitioned into <strong>MST Import and Export Sdn Bhd</strong>, expanding beyond traditional seafood trading into a broader frozen food sourcing, trading and supply business.') !!}
                </p>
            </div>

            <!-- Today -->
            <div class="timeline-card timeline-card-3">
                <div class="timeline-badge timeline-badge-3">@t('about.timeline_today_badge', 'Today')</div>
                <h4 class="timeline-title">@t('about.timeline_today_title', 'SILC Hub Facility')</h4>
                <p class="timeline-desc">
                    {!! __t('about.timeline_today_desc', 'Our <strong>SILC facility</strong> represents the next stage of our development, strengthening our cold storage, product handling, packing and distribution capabilities.') !!}
                </p>
            </div>

            <!-- The Future -->
            <div class="timeline-card timeline-card-4">
                <div class="timeline-badge timeline-badge-4">@t('about.timeline_future_badge', 'The Future')</div>
                <h4 class="timeline-title">@t('about.timeline_future_title', 'Beyond Borders')</h4>
                <p class="timeline-desc">
                    {!! __t('about.timeline_future_desc', 'We are building MST to serve a broader <strong>regional and international market</strong>, with a scalable supply platform designed to support the evolving requirements of our customers and supply partners.') !!}
                </p>
            </div>
        </div>
    </section>

    <!-- ─── Section 3: MST At A Glance — Company Facts ──────────────────────── -->
    <section class="about-section about-facts-wrapper">
        <div style="text-align:center;max-width:650px;margin:0 auto 36px;">
            <div class="section-eyebrow" style="color:#1d4ed8;font-weight:700;margin-bottom:8px">@t('about.section_3_eyebrow', '3. MST AT A GLANCE — COMPANY FACTS')</div>
            <h2 style="font-family:var(--font-heading);font-size:clamp(1.5rem, 3vw, 2rem);color:#0f172a;margin-bottom:8px;">
                @t('about.section_3_title', 'Fast Facts & Infrastructure')
            </h2>
            <p style="color:#64748b;font-size:0.92rem;margin:0;">@t('about.section_3_desc', 'Key facts about our business and operating capabilities.')</p>
        </div>

        <div class="about-facts-grid">
            <div class="fact-card">
                <div class="fact-icon">📅</div>
                <div class="fact-label">@t('about.fact_established', 'Established')</div>
                <div class="fact-value">2014</div>
            </div>

            <div class="fact-card">
                <div class="fact-icon">🏛️</div>
                <div class="fact-label">@t('about.fact_entity', 'Corporate Entity')</div>
                <div class="fact-value" style="font-size:0.95rem;line-height:1.3;">MST Import and Export Sdn Bhd</div>
            </div>

            <div class="fact-card">
                <div class="fact-icon">📍</div>
                <div class="fact-label">@t('about.fact_based', 'Based in')</div>
                <div class="fact-value" style="font-size:1rem;">@t('about.fact_based_val', 'Iskandar Puteri, Johor')</div>
            </div>

            <div class="fact-card">
                <div class="fact-icon">💼</div>
                <div class="fact-label">@t('about.fact_business', 'Business')</div>
                <div class="fact-value" style="font-size:0.9rem;">@t('about.fact_business_val', 'Sourcing, Trading & Distribution')</div>
            </div>

            <div class="fact-card">
                <div class="fact-icon">🥩</div>
                <div class="fact-label">@t('about.fact_products', 'Core Products')</div>
                <div class="fact-value" style="font-size:0.92rem;">@t('about.fact_products_val', 'Seafood · Meat · Frozen Food · Food Ingredients')</div>
            </div>

            <div class="fact-card">
                <div class="fact-icon">❄️</div>
                <div class="fact-label">@t('about.fact_cold_storage', 'Cold Storage')</div>
                <div class="fact-value" style="color:#1d4ed8;">@t('about.fact_cold_storage_val', 'Over 50 Tonnes')</div>
                <div style="font-size:0.72rem;color:#64748b;margin-top:2px;">(-18°C to -25°C)</div>
            </div>

            <div class="fact-card">
                <div class="fact-icon">🏭</div>
                <div class="fact-label">@t('about.fact_facility', 'Primary Facility')</div>
                <div class="fact-value" style="font-size:0.95rem;">@t('about.fact_facility_val', 'SILC, Iskandar Puteri')</div>
            </div>

            <div class="fact-card">
                <div class="fact-icon">🌏</div>
                <div class="fact-label">@t('about.fact_market', 'Market Vision')</div>
                <div class="fact-value" style="color:#059669;font-size:1rem;">@t('about.fact_market_val', 'Regional & International')</div>
            </div>
        </div>
    </section>

    <!-- ─── Section 4: What Defines MST — Current Capabilities ──────────────── -->
    <section class="about-section">
        <div style="text-align:center;max-width:720px;margin:0 auto 40px;">
            <div class="section-eyebrow" style="color:#1d4ed8;font-weight:700;margin-bottom:8px">@t('about.section_4_eyebrow', '4. WHAT DEFINES MST — CURRENT CAPABILITIES')</div>
            <h2 style="font-family:var(--font-heading);font-size:clamp(1.6rem, 3.5vw, 2.2rem);color:#0f172a;margin-bottom:12px;">
                @t('about.section_4_title', 'Five Pillars of Operational Reliability')
            </h2>
            <p style="color:#64748b;font-size:0.95rem;line-height:1.6;margin:0;">
                @t('about.section_4_desc', 'At MST Import and Export Sdn Bhd, we go beyond supplying frozen food. We focus on product quality, reliable sourcing, cold-chain integrity and consistent supply, giving our customers greater confidence from sourcing to delivery.')
            </p>
        </div>

        <div class="about-pillars-grid">
            <!-- 1. Quality -->
            <div class="pillar-card">
                <div class="pillar-icon" style="background:#eff6ff;color:#2563eb;">🛡️</div>
                <h3 class="pillar-title">@t('about.pillar_1_title', 'QUALITY & FOOD SAFETY')</h3>
                <p class="pillar-desc">
                    @t('about.pillar_1_desc', 'We source seafood, meat, frozen food and food ingredients according to product specifications and customer requirements. We work with established suppliers and processing partners to support consistent product quality and food-safety requirements.')
                </p>
            </div>

            <!-- 2. Customised Sourcing -->
            <div class="pillar-card">
                <div class="pillar-icon" style="background:#eff6ff;color:#2563eb;">🔍</div>
                <h3 class="pillar-title">@t('about.pillar_2_title', 'CUSTOMISED SOURCING')</h3>
                <p class="pillar-desc">
                    @t('about.pillar_2_desc', "Can't find what you need? Tell us what you are looking for — including product type, specifications, pack size, origin and quantity. Our sourcing network allows us to identify suitable products and supply options according to your requirements.")
                </p>
            </div>

            <!-- 3. Cold-Chain Integrity -->
            <div class="pillar-card">
                <div class="pillar-icon" style="background:#eff6ff;color:#0284c7;">❄️</div>
                <h3 class="pillar-title">@t('about.pillar_3_title', 'COLD-CHAIN INTEGRITY')</h3>
                <p class="pillar-desc">
                    @t('about.pillar_3_desc', 'From receiving and storage to packing and dispatch, our temperature-controlled operations are designed to maintain product quality and cold-chain integrity throughout the supply chain. Our frozen storage operates within -18°C to -25°C, supporting proper handling and storage of frozen products.')
                </p>
            </div>

            <!-- 4. Reliable Supply -->
            <div class="pillar-card">
                <div class="pillar-icon" style="background:#eff6ff;color:#1d4ed8;">🚚</div>
                <h3 class="pillar-title">@t('about.pillar_4_title', 'RELIABLE SUPPLY & DELIVERY')</h3>
                <p class="pillar-desc">
                    @t('about.pillar_4_desc', 'Our operations cover receiving, storage, packing, order preparation and dispatch. With our cold storage and distribution facility at SILC, we support regular stock supply as well as product-specific sourcing and growing customer requirements.')
                </p>
            </div>

            <!-- 5. Built to Scale -->
            <div class="pillar-card">
                <div class="pillar-icon" style="background:#fef3c7;color:#b45309;">📈</div>
                <h3 class="pillar-title">@t('about.pillar_5_title', 'BUILT TO SCALE')</h3>
                <p class="pillar-desc">
                    @t('about.pillar_5_desc', 'Our infrastructure and sourcing capabilities are designed to support increasing volumes and expanding regional and international markets. Whether you are a restaurant, food business, wholesaler, distributor or international buyer, we aim to be a reliable long-term sourcing and supply partner.')
                </p>
            </div>
        </div>
    </section>

    <!-- ─── Section 5: Creed, Motto & Leadership ───────────────────────────── -->
    <section class="about-section">
        <div class="about-creed-grid">
            <!-- Focus & Creed -->
            <div class="creed-card">
                <div>
                    <div class="section-eyebrow" style="color:#1d4ed8;font-weight:700;margin-bottom:8px">@t('about.section_5_eyebrow', '5. OUR FOCUS & CREED')</div>
                    <h3 style="font-size:1.35rem;font-weight:800;color:#0f172a;margin-bottom:4px;line-height:1.35;">
                        @t('about.creed_title', 'Quality Products. Reliable Supply. Competitive Pricing. Consistent Service.')
                    </h3>
                    
                    <blockquote style="margin:16px 0;padding:16px 20px;background:#f8fafc;border-left:4px solid #2563eb;border-radius:0 12px 12px 0;font-size:0.92rem;color:#334155;line-height:1.7;">
                        <p style="margin:0;font-style:italic;">
                            @t('about.quote_en', '“We believe that long-term business relationships are built on trust, integrity and reliability.”')
                        </p>
                    </blockquote>
                </div>

                <div style="padding-top:18px;border-top:1px solid #f1f5f9;">
                    <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">@t('about.company_motto_label', 'COMPANY MOTTO')</div>
                    <div style="font-size:1.15rem;font-weight:800;color:#1d4ed8;margin-bottom:2px;">
                        @t('about.company_motto_val', 'Flow with Integrity, Grow with Strength.')
                    </div>
                    <div style="font-size:0.85rem;color:#475569;font-weight:600;">
                        @t('about.company_name_full', 'MST Import and Export Sdn Bhd')
                    </div>
                </div>
            </div>

            <!-- Management Profile -->
            <div class="leadership-card">
                <div>
                    <div style="font-size:0.75rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#93c5fd;margin-bottom:8px;">@t('about.leadership_eyebrow', 'MANAGEMENT LEADERSHIP')</div>
                    <div style="display:flex;align-items:center;gap:16px;margin-bottom:18px;">
                        <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#38bdf8,#1d4ed8);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:800;color:white;box-shadow:0 4px 12px rgba(56,189,248,0.3);flex-shrink:0;">
                            WC
                        </div>
                        <div>
                            <h3 style="font-size:1.4rem;font-weight:800;color:#ffffff;margin:0 0 2px;">Wendy Chiam</h3>
                            <div style="font-size:0.88rem;color:#7dd3fc;font-weight:600;">@t('about.wendy_title', 'Director / Managing Director')</div>
                        </div>
                    </div>

                    <p style="font-size:0.92rem;color:#e0f2fe;line-height:1.75;margin:0 0 16px;">
                        @t('about.wendy_bio_1', "Wendy leads MST's strategic development, sourcing network, customer relationships and business expansion, with a focus on building a reliable and scalable supply platform for regional and international customers.")
                    </p>
                    <p style="font-size:0.92rem;color:#e0f2fe;line-height:1.75;margin:0;">
                        @t('about.wendy_bio_2', 'Under her direction, MST continues to strengthen its sourcing capabilities, cold-chain infrastructure and supply operations while developing long-term relationships with customers and business partners.')
                    </p>
                </div>

                <div style="padding-top:20px;border-top:1px solid rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                    <span style="font-size:0.82rem;color:#93c5fd;">@t('about.exec_leadership', 'Executive Leadership')</span>
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-sm" style="background:#2563eb;border-color:#2563eb;padding:7px 14px;font-size:0.82rem;">
                        @t('about.connect_team', 'Connect With Our Team →')
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── Bottom CTA Bar ────────────────────────────────────────────────── -->
    <div class="about-cta-bar">
        <div>
            <h4 style="font-size:1.25rem;font-weight:800;color:#0f172a;margin:0 0 4px;">@t('about.cta_title', 'Looking for a reliable supply partner?')</h4>
            <p style="color:#64748b;font-size:0.9rem;margin:0;">@t('about.cta_desc', "From regular supply requirements to product-specific sourcing, let's talk business.")</p>
        </div>
        <div class="about-cta-actions">
            <a href="{{ route('shop.index') }}" class="btn btn-secondary" style="font-weight:700;padding:10px 20px;background:#eff6ff;color:#1d4ed8;border:1.5px solid #bfdbfe;border-radius:10px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                @t('about.explore_products', 'Explore Products')
            </a>
            <a href="{{ route('contact') }}" class="btn btn-primary" style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%);color:#091a36;border:none;font-weight:800;padding:10px 22px;border-radius:10px;box-shadow:0 4px 14px rgba(245,158,11,0.35);text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                @t('about.request_quote', 'Request a Quote →')
            </a>
        </div>
    </div>

</div>

<style>
/* ════════════════════════════════════════
   ABOUT US PAGE — STYLES & RESPONSIVENESS
   ════════════════════════════════════════ */

.about-page-container {
    padding: var(--space-12) var(--space-4) var(--space-16);
}

.about-section {
    margin-bottom: 70px;
}

/* Section 1: Who We Are */
.about-who-grid {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 40px;
    align-items: center;
}

.about-intro-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 28px 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    line-height: 1.8;
    color: #334155;
    font-size: 1rem;
}

.about-feature-box {
    background: linear-gradient(135deg, #06152b 0%, #0c2146 40%, #14356b 75%, #1d4ed8 100%);
    border-radius: 24px;
    padding: 36px;
    color: #ffffff;
    box-shadow: 0 16px 40px rgba(6, 21, 43, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.12);
    position: relative;
    overflow: hidden;
}

.about-feature-box h3 {
    color: #ffffff !important;
}

.feature-orb-1 {
    position: absolute;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.45) 0%, transparent 70%);
    top: -50px;
    right: -50px;
    pointer-events: none;
}

.feature-orb-2 {
    position: absolute;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(96, 165, 250, 0.3) 0%, transparent 70%);
    bottom: -40px;
    left: -40px;
    pointer-events: none;
}

.feature-grid-overlay {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
}

.feature-check-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.88rem;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    padding: 9px 14px;
    border-radius: 10px;
    backdrop-filter: blur(8px);
    color: #ffffff;
}

.feature-check-icon {
    color: #22d3ee;
    font-weight: 800;
}

/* Section 2: Timeline */
.about-timeline-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.timeline-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 26px 22px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    position: relative;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.timeline-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.06);
}

.timeline-card-1 { border-top: 4px solid #091a36; }
.timeline-card-2 { border-top: 4px solid #2563eb; }
.timeline-card-3 { border-top: 4px solid #f59e0b; }
.timeline-card-4 { border-top: 4px solid #0f274a; }

.timeline-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 800;
    font-size: 0.9rem;
    margin-bottom: 12px;
}
.timeline-badge-1 { background: #eff6ff; color: #091a36; }
.timeline-badge-2 { background: #eff6ff; color: #2563eb; }
.timeline-badge-3 { background: #fef3c7; color: #b45309; }
.timeline-badge-4 { background: #eff6ff; color: #0f274a; }

.timeline-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}

.timeline-desc {
    font-size: 0.88rem;
    color: #64748b;
    line-height: 1.65;
    margin: 0;
}

/* Section 3: Facts */
.about-facts-wrapper {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    padding: 44px 36px;
}

.about-facts-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}

.fact-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    transition: transform 0.2s ease;
}

.fact-card:hover {
    transform: translateY(-2px);
}

.fact-icon {
    font-size: 1.8rem;
    margin-bottom: 6px;
}

.fact-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.04em;
}

.fact-value {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0f172a;
    margin-top: 4px;
}

/* Section 4: Pillars */
.about-pillars-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.pillar-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 30px 26px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.pillar-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.06);
}

.pillar-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    margin-bottom: 18px;
}

.pillar-title {
    font-size: 1.12rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 10px;
}

.pillar-desc {
    font-size: 0.9rem;
    color: #475569;
    line-height: 1.7;
    margin: 0;
}

/* Section 5: Creed & Leadership */
.about-creed-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 36px;
}

.creed-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 36px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.leadership-card {
    background: linear-gradient(135deg, #091a36 0%, #0f274a 100%);
    color: #ffffff;
    border-radius: 20px;
    padding: 36px;
    box-shadow: 0 10px 30px rgba(9,26,54,0.15);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Section 6: CTA Bar */
.about-cta-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 32px 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.03);
}

.about-cta-actions {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

/* ════════════════════════════════════════
   RESPONSIVE MEDIA QUERIES
   ════════════════════════════════════════ */

@media (max-width: 1024px) {
    .about-page-container {
        padding: 32px 16px 60px;
    }
    .about-who-grid {
        grid-template-columns: 1fr;
        gap: 32px;
    }
    .about-timeline-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    .about-facts-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    .about-creed-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
}

@media (max-width: 640px) {
    .about-section {
        margin-bottom: 48px;
    }
    .about-intro-card {
        padding: 20px 18px;
    }
    .about-feature-box {
        padding: 26px 20px;
        border-radius: 18px;
    }
    .about-timeline-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }
    .about-facts-wrapper {
        padding: 28px 18px;
        border-radius: 18px;
    }
    .about-facts-grid {
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .fact-card {
        padding: 16px 12px;
    }
    .about-pillars-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    .pillar-card {
        padding: 24px 20px;
    }
    .creed-card,
    .leadership-card {
        padding: 26px 20px;
        border-radius: 18px;
    }
    .about-cta-bar {
        padding: 24px 20px;
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }
    .about-cta-actions {
        width: 100%;
        flex-direction: column;
        gap: 10px;
    }
    .about-cta-actions .btn {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}

@media (max-width: 400px) {
    .about-facts-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
