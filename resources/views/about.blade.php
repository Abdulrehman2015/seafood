@extends('layouts.app')
@section('title', 'About MST Import & Export — MST Import and Export Sdn Bhd')
@section('meta_description', 'MST Import and Export Sdn Bhd is a Johor Bahru-based frozen seafood trading and wholesale company supplying quality products across Malaysia and Singapore since 2014.')

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 Home</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">About MST Import &amp; Export</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:3px 10px;border-radius:999px;font-size:0.72rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        🏆 Established 2014 in Johor Bahru
                    </span>
                    <span style="color:#bae6fd;font-size:0.8rem">Malaysia &amp; Singapore Cold-Chain Distribution</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.4rem);margin-bottom:6px;letter-spacing:-0.02em">
                    About MST Import &amp; Export
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:720px;line-height:1.5;margin:0">
                    MST Import and Export Sdn Bhd is a Johor Bahru-based frozen seafood trading and wholesale company supplying restaurants, food businesses, retailers, and distributors across Malaysia and Singapore.
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div style="font-size:0.85rem;padding:6px 14px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb;font-weight:600">
                    ⭐ Flow with Integrity, Grow with Strength
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container" style="padding:var(--space-12) var(--space-4) var(--space-16)">

    <!-- ─── Section 1: Official Company Story & Focus (Bilingual EN / CN) ──────── -->
    <div class="about-story-grid" style="align-items:start">
        <div>
            <div class="section-eyebrow" style="color:var(--seagreen-700);font-weight:700;margin-bottom:8px">JOHOR BAHRU HERITAGE &amp; STRATEGIC VISION</div>
            <h2 style="font-family:var(--font-heading);font-size:clamp(1.5rem, 4vw, 2.1rem);color:var(--gray-900);margin-bottom:16px;line-height:1.3">
                From Johor Bahru Roots to Cross-Border Cold-Chain Supply
            </h2>

            <!-- English Story -->
            <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;padding:22px 24px;margin-bottom:18px;box-shadow:0 2px 8px rgba(0,0,0,0.03)">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
                    <span style="font-size:1.1rem">🇲🇾</span>
                    <strong style="font-size:1rem;color:#0f172a;text-transform:uppercase;letter-spacing:0.04em">About MST Import &amp; Export</strong>
                </div>
                <p style="color:var(--gray-700);line-height:1.75;margin-bottom:12px;font-size:0.95rem">
                    <strong>MST Import and Export Sdn Bhd</strong> is a Johor Bahru-based frozen seafood trading and wholesale company.
                </p>
                <p style="color:var(--gray-700);line-height:1.75;margin-bottom:12px;font-size:0.95rem">
                    Our journey began in <strong>2014 as Mika Seafood Trading in Johor Bahru</strong>, with a focus on supplying quality frozen seafood to customers and businesses in Malaysia.
                </p>
                <p style="color:var(--gray-700);line-height:1.75;margin-bottom:12px;font-size:0.95rem">
                    Over the years, we have grown through strong supplier relationships, reliable product sourcing and a commitment to providing consistent service to our customers.
                </p>
                <p style="color:var(--gray-700);line-height:1.75;margin-bottom:12px;font-size:0.95rem">
                    In <strong>2024, Mika Seafood Trading transitioned into MST Import and Export Sdn Bhd</strong>, marking the next stage of our business development and expansion.
                </p>
                <p style="color:var(--gray-700);line-height:1.75;margin-bottom:0;font-size:0.95rem">
                    Today, MST Import and Export serves customers across <strong>Malaysia and Singapore</strong>, supplying frozen seafood and selected frozen food products to restaurants, food businesses, retailers, distributors and other commercial customers.
                </p>
            </div>

            <!-- Chinese Story -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-left:4px solid #2563eb;border-radius:14px;padding:22px 24px;margin-bottom:20px">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
                    <span style="font-size:1.1rem">🌏</span>
                    <strong style="font-size:1rem;color:#0f172a">关于 MST Import &amp; Export</strong>
                </div>
                <p style="color:var(--gray-800);line-height:1.8;margin-bottom:12px;font-size:0.95rem">
                    <strong>MST Import and Export Sdn Bhd</strong> 是一家位于马来西亚新山的冷冻海鲜贸易及批发公司。
                </p>
                <p style="color:var(--gray-800);line-height:1.8;margin-bottom:12px;font-size:0.95rem">
                    我们的业务始于 <strong>2014 年</strong>，当时以 <strong>Mika Seafood Trading</strong> 的名称在新山开始经营，专注于为马来西亚客户及企业供应优质冷冻海鲜。
                </p>
                <p style="color:var(--gray-800);line-height:1.8;margin-bottom:12px;font-size:0.95rem">
                    经过多年的发展，我们不断建立稳定的供应商网络、可靠的产品采购渠道，并持续提升我们的服务与供应能力。
                </p>
                <p style="color:var(--gray-800);line-height:1.8;margin-bottom:12px;font-size:0.95rem">
                    <strong>2024 年，Mika Seafood Trading 正式转型为 MST Import and Export Sdn Bhd</strong>，迈向业务发展的新阶段。
                </p>
                <p style="color:var(--gray-800);line-height:1.8;margin-bottom:0;font-size:0.95rem">
                    目前，MST Import and Export 为<strong>马来西亚及新加坡</strong>的餐厅、餐饮业者、零售商、批发商、分销商及其他商业客户供应冷冻海鲜及精选冷冻食品。
                </p>
            </div>
            
            <!-- Milestone Timeline Pills -->
            <div class="about-milestones-grid">
                <div style="background:#ffffff;border-left:3px solid #2563eb;padding:12px 14px;border-radius:0 8px 8px 0;box-shadow:0 1px 4px rgba(0,0,0,0.04)">
                    <div style="font-weight:700;color:var(--gray-900);font-size:0.9rem">2014 · Inception in Johor Bahru</div>
                    <div style="font-size:0.78rem;color:var(--gray-600)">Founded as Mika Seafood Trading in JB, supplying quality frozen catches.</div>
                </div>
                <div style="background:#ffffff;border-left:3px solid #0284c7;padding:12px 14px;border-radius:0 8px 8px 0;box-shadow:0 1px 4px rgba(0,0,0,0.04)">
                    <div style="font-weight:700;color:var(--gray-900);font-size:0.9rem">Growth &amp; Reliable Sourcing</div>
                    <div style="font-size:0.78rem;color:var(--gray-600)">Built resilient supplier networks and strict quality control standards.</div>
                </div>
                <div style="background:#ffffff;border-left:3px solid #16a34a;padding:12px 14px;border-radius:0 8px 8px 0;box-shadow:0 1px 4px rgba(0,0,0,0.04)">
                    <div style="font-weight:700;color:var(--gray-900);margin-bottom:2px;font-size:0.9rem">2024 · Corporate Transition</div>
                    <div style="font-size:0.78rem;color:var(--gray-600)">Transitioned into MST Import and Export Sdn Bhd &amp; expanded SILC logistics.</div>
                </div>
                <div style="background:#ffffff;border-left:3px solid #d97706;padding:12px 14px;border-radius:0 8px 8px 0;box-shadow:0 1px 4px rgba(0,0,0,0.04)">
                    <div style="font-weight:700;color:var(--gray-900);margin-bottom:2px;font-size:0.9rem">Today · Malaysia &amp; Singapore</div>
                    <div style="font-size:0.78rem;color:var(--gray-600)">Cross-border supply for restaurants, distributors &amp; retail commercial clients.</div>
                </div>
            </div>
        </div>

        <!-- Visual Highlight Box / 4 Core Pillars -->
        <div class="about-quote-box" style="background:linear-gradient(135deg, #091a36 0%, #0f274a 60%, #1e3a8a 100%);border:1px solid #1e3a8a">
            <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,0.06);border-radius:50%"></div>
            <div style="font-size:3.5rem;margin-bottom:12px">🌊</div>
            
            <div style="font-size:0.8rem;font-weight:700;letter-spacing:0.08em;color:#7dd3fc;text-transform:uppercase;margin-bottom:6px">OUR FOCUS &amp; CREED</div>
            <h3 style="font-family:var(--font-heading);font-size:1.5rem;color:white;margin-bottom:14px;line-height:1.3">
                Quality Products.<br>Reliable Supply.<br>Competitive Pricing.<br>Consistent Service.
            </h3>
            <div style="font-size:0.9rem;font-weight:600;color:#bae6fd;margin-bottom:16px;line-height:1.5">
                优质产品 · 稳定供应 · 合理价格 · 一致服务
            </div>
            <p style="color:#e0f2fe;line-height:1.7;font-size:0.92rem;margin-bottom:18px">
                "We believe that long-term business relationships are built on trust, integrity and reliability."
            </p>
            <p style="color:#93c5fd;line-height:1.7;font-size:0.88rem;margin-bottom:20px">
                我们相信，长期的商业合作建立在诚信、可靠与信任之上。
            </p>
            <div style="background:rgba(255,255,255,0.1);padding:12px 16px;border-radius:10px;border:1px solid rgba(255,255,255,0.15);margin-bottom:20px">
                <div style="color:#38bdf8;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em">Company Motto</div>
                <div style="color:#ffffff;font-weight:800;font-size:1.1rem;margin-top:2px">Flow with Integrity, Grow with Strength.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;border-top:1px solid rgba(255,255,255,0.15);padding-top:14px">
                <div style="width:42px;height:42px;border-radius:50%;background:rgba(56,189,248,0.2);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.95rem;color:#7dd3fc;border:1px solid rgba(56,189,248,0.4)">
                    MST
                </div>
                <div>
                    <div style="font-weight:700;font-size:0.92rem;color:#ffffff">Management Team</div>
                    <div style="font-size:0.78rem;color:#7dd3fc">MST Import and Export Sdn Bhd</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ─── Section 1.5: Notice of Relocation & Company Name Change (搬迁启事 暨公司更名通知) ─── -->
    <section class="relocation-announcement" id="relocation-notice" aria-label="Notice of Relocation and Company Name Change">
        <div class="relocation-card">
            
            <!-- Header Banner -->
            <div class="relocation-header">
                <div class="relocation-header-badge-row">
                    <span class="relocation-badge-primary">📢 官方通告 · OFFICIAL NOTICE</span>
                    <span class="relocation-badge-highlight">🚀 搬迁升级 · RELOCATED & EXPANDED</span>
                </div>
                <h2 class="relocation-main-title">
                    <span>搬迁启事</span>
                    <span class="relocation-title-sub">暨公司更名通知</span>
                </h2>
                <div class="relocation-en-title">NOTICE OF RELOCATION & COMPANY NAME CHANGE</div>
                
                <div class="relocation-salutation-box">
                    <div class="relocation-salutation-heading">尊敬的客户、供应商及合作伙伴 / Dear Valued Customers, Suppliers & Partners:</div>
                    <p class="relocation-salutation-text">
                        因业务发展需要，本公司已开始搬迁至新办公地点，并正式更名，以提供更优质的服务与更便利的体验！
                        <span class="relocation-salutation-en">Due to rapid business expansion, our company has commenced relocation to our new premises and officially updated our corporate name to serve you better.</span>
                    </p>
                </div>
            </div>

            <!-- Company Name Transition Grid -->
            <div class="relocation-transition-wrap">
                <div class="relocation-name-card relocation-name-old">
                    <div class="relocation-name-tag">原公司名称 · OLD COMPANY NAME</div>
                    <div class="relocation-name-cn">美加冷冻海产</div>
                    <div class="relocation-name-en">MIKA SEAFOOD TRADING</div>
                    <div class="relocation-name-foot">🏛️ Established Brand</div>
                </div>

                <div class="relocation-arrow-badge">
                    <div class="relocation-arrow-pill">现更名为 · NOW KNOWN AS</div>
                    <div class="relocation-arrow-icon">➔</div>
                </div>

                <div class="relocation-name-card relocation-name-new">
                    <div class="relocation-name-tag-new">现更名为 · NEW OFFICIAL NAME</div>
                    <div class="relocation-name-cn-new">镁嘉国际贸易有限公司</div>
                    <div class="relocation-name-en-new">MST IMPORT & EXPORT SDN. BHD.</div>
                    <div class="relocation-name-foot-new">⭐ Official Corporate Entity</div>
                </div>
            </div>

            <!-- Location Comparison (Old vs New Address) -->
            <div class="relocation-locations-grid">
                
                <!-- Old Location -->
                <div class="relocation-loc-card relocation-loc-old">
                    <div class="relocation-loc-header">
                        <div class="relocation-loc-icon">📍</div>
                        <div>
                            <div class="relocation-loc-title">原地址 (旧店址)</div>
                            <div class="relocation-loc-subtitle">PREVIOUS LOCATION</div>
                        </div>
                        <span class="relocation-loc-status-old">旧址</span>
                    </div>
                    <div class="relocation-loc-body">
                        <address class="relocation-address-text">
                            <strong>41 JALAN SENTRAL 24</strong><br>
                            TAMAN NUSA SENTRAL,<br>
                            79100 ISKANDAR PUTERI, JOHOR
                        </address>
                        <div class="relocation-loc-note">原商业零售与批发店面</div>
                    </div>
                </div>

                <!-- New Location -->
                <div class="relocation-loc-card relocation-loc-new">
                    <div class="relocation-loc-header">
                        <div class="relocation-loc-icon relocation-loc-icon-new">🏢</div>
                        <div>
                            <div class="relocation-loc-title relocation-loc-title-new">新地址 (新办公地点)</div>
                            <div class="relocation-loc-subtitle">NEW HEADQUARTERS & LOGISTICS HUB</div>
                        </div>
                        <span class="relocation-loc-status-new">✨ 新地点已开始搬迁！</span>
                    </div>
                    <div class="relocation-loc-body">
                        <address class="relocation-address-text relocation-address-new">
                            <strong>7 JALAN SILC 2/18</strong><br>
                            KAWASAN PERINDUSTRIAN SILC,<br>
                            79200 ISKANDAR PUTERI, JOHOR
                        </address>
                        <div class="relocation-loc-actions">
                            <a href="https://maps.google.com/?q=MST+Import+and+Export,+7+Jalan+SILC+2/18,+Kawasan+Perindustrian+SILC,+79200+Iskandar+Puteri,+Johor" 
                               target="_blank" rel="noopener noreferrer" 
                               class="btn-loc-action btn-loc-gmaps">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Google Maps 导航
                            </a>
                            <a href="https://waze.com/ul?q=MST+Import+and+Export+SILC" 
                               target="_blank" rel="noopener noreferrer" 
                               class="btn-loc-action btn-loc-waze">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><path d="M8 12h8"></path><path d="M12 8l4 4-4 4"></path></svg>
                                Waze 搜索导航
                            </a>
                            <button type="button" class="btn-loc-action btn-loc-copy" onclick="copyNewAddress(this)">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                <span class="copy-text">复制地址</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Status & Continuity Notice Box -->
            <div class="relocation-status-panel">
                <div class="relocation-status-panel-head">
                    <span class="relocation-status-icon">🚧</span>
                    <div>
                        <div class="relocation-status-title">新办公室目前仍在装修中 · OUR NEW OFFICE IS STILL UNDER RENOVATION</div>
                        <div class="relocation-status-desc">业务正常运转中，所有生鲜与冷冻供应链服务持续稳定供应</div>
                    </div>
                </div>

                <div class="relocation-status-items-grid">
                    <div class="relocation-status-pill">
                        <div class="relocation-status-check">✅</div>
                        <div>
                            <div class="relocation-status-pill-cn">业务照常营业</div>
                            <div class="relocation-status-pill-en">BUSINESS AS USUAL</div>
                        </div>
                    </div>
                    <div class="relocation-status-pill">
                        <div class="relocation-status-check">✅</div>
                        <div>
                            <div class="relocation-status-pill-cn">订单正常处理</div>
                            <div class="relocation-status-pill-en">ORDERS ARE BEING PROCESSED</div>
                        </div>
                    </div>
                    <div class="relocation-status-pill">
                        <div class="relocation-status-check">✅</div>
                        <div>
                            <div class="relocation-status-pill-cn">送货服务不受影响</div>
                            <div class="relocation-status-pill-en">DELIVERY SERVICES UNAFFECTED</div>
                        </div>
                    </div>
                </div>

                <div class="relocation-warm-note">
                    <div class="relocation-warm-icon">🤝</div>
                    <p class="relocation-warm-text">
                        装修完成后，我们将诚邀各位客户及合作伙伴莅临参观，敬请期待！感谢各界一直以来的支持与信任，期待在全新的环境继续为您服务！<br>
                        <span class="relocation-warm-en">Once renovations are complete, we look forward to welcoming you to our new premises. Thank you for your continued trust and partnership!</span>
                    </p>
                </div>
            </div>

            <!-- Direct Contact & Map Footer -->
            <div class="relocation-contacts-bar">
                <div class="relocation-contacts-label">联系我们 · CONTACT US:</div>
                <div class="relocation-contacts-grid">
                    <a href="tel:01114360109" class="relocation-contact-item">
                        <div class="relocation-contact-icon" style="background:#eff6ff;color:#2563eb">📞</div>
                        <div>
                            <div class="relocation-contact-type">OFFICE (公司电话)</div>
                            <div class="relocation-contact-val">011-1436 0109</div>
                        </div>
                    </a>

                    <a href="https://wa.me/601112710260?text=Hi%20MST%20Import%20%26%20Export,%20I%20would%20like%20to%20inquire%20about%20your%20seafood%20products." 
                       target="_blank" rel="noopener noreferrer" 
                       class="relocation-contact-item">
                        <div class="relocation-contact-icon" style="background:#ecfdf5;color:#10b981">💬</div>
                        <div>
                            <div class="relocation-contact-type">WHATSAPP (公司WhatsApp)</div>
                            <div class="relocation-contact-val">011-1271 0260</div>
                        </div>
                    </a>

                    <a href="https://maps.google.com/?q=MST+Import+and+Export" 
                       target="_blank" rel="noopener noreferrer" 
                       class="relocation-contact-item">
                        <div class="relocation-contact-icon" style="background:#fef3c7;color:#d97706">🗺️</div>
                        <div>
                            <div class="relocation-contact-type">MAPS / WAZE 搜索</div>
                            <div class="relocation-contact-val">MST Import and Export</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Slogan Ribbon -->
            <div class="relocation-slogan-ribbon">
                <div class="relocation-slogan-cn">镁嘉信任 · 聚我们前进的动力！ 💛 更优质的服务 · 共创更美好的未来</div>
                <div class="relocation-slogan-en">MST IMPORT & EXPORT SDN. BHD. (原: MIKA SEAFOOD TRADING)</div>
            </div>

        </div>
    </section>

    <!-- ─── Section 2: Core Operating Statistics ─────────────────────────────── -->
    <div class="stats-grid" style="margin-bottom:var(--space-16);max-width:1100px;margin-left:auto;margin-right:auto">
        <div class="stat-card stat-teal text-center" style="padding:24px 16px;border-radius:14px;background:white;border:1px solid #e2e8f0">
            <div class="stat-number" style="font-size:2.2rem;font-weight:800;color:var(--seagreen-800)">14+</div>
            <div class="stat-label" style="font-size:0.85rem;color:var(--gray-600);margin-top:4px">Years in Malaysia</div>
        </div>
        <div class="stat-card stat-gold text-center" style="padding:24px 16px;border-radius:14px;background:white;border:1px solid #e2e8f0">
            <div class="stat-number" style="font-size:2.2rem;font-weight:800;color:#d97706">200+</div>
            <div class="stat-label" style="font-size:0.85rem;color:var(--gray-600);margin-top:4px">Seafood SKUs Sourced</div>
        </div>
        <div class="stat-card text-center" style="padding:24px 16px;border-radius:14px;background:white;border:1px solid #e2e8f0">
            <div class="stat-number" style="font-size:2.2rem;font-weight:800;color:#2563eb">50+</div>
            <div class="stat-label" style="font-size:0.85rem;color:var(--gray-600);margin-top:4px">Tonne Cold Storage Cap</div>
        </div>
        <div class="stat-card stat-seafoam text-center" style="padding:24px 16px;border-radius:14px;background:white;border:1px solid #e2e8f0">
            <div class="stat-number" style="font-size:2.2rem;font-weight:800;color:#059669">1,500+</div>
            <div class="stat-label" style="font-size:0.85rem;color:var(--gray-600);margin-top:4px">Verified Happy Clients</div>
        </div>
    </div>

    <!-- ─── Section 3: Our 4 Operating Pillars ────────────────────────────────── -->
    <div style="max-width:1100px;margin:0 auto var(--space-16)">
        <div class="section-header" style="text-align:center;margin-bottom:var(--space-10)">
            <div class="section-eyebrow" style="color:var(--seagreen-700)">CORE VALUES</div>
            <h2 class="section-title">What Defines Mika</h2>
            <p class="section-subtitle">Every product that leaves our warehouse meets strict standards for safety, freshness, and sustainability.</p>
        </div>

        <div class="pillars-grid">
            @foreach([
                ['🏅','Certified Food Safety','All our products originate from certified vessels and licensed processing plants under strict Malaysian & international food regulations.'],
                ['🌍','Responsible Sourcing','We strictly reject destructive fishing practices, partnering exclusively with certified sustainable fisheries and responsible aqua-farms.'],
                ['❄️','Individual Quick Freezing','Our IQF technology drops temperatures to -40°C in minutes, preserving delicate cell membranes and natural succulence.'],
                ['🚚','Refrigerated Direct Fleet','Orders travel exclusively in temperature-monitored refrigerated trucks maintaining continuous sub-zero conditions until handover.'],
            ] as [$icon,$title,$desc])
            <div class="card" style="padding:28px 24px;border-radius:16px;border:1px solid #e2e8f0;background:white;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.05)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="font-size:2.2rem;margin-bottom:14px">{{ $icon }}</div>
                <h3 style="font-size:1.15rem;font-weight:700;margin-bottom:8px;color:var(--gray-900)">{{ $title }}</h3>
                <p style="font-size:0.875rem;color:var(--gray-600);line-height:1.6;margin:0">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ─── Section 4: Facility & Cold Logistics Hub ─────────────────────────── -->
    <div class="facility-wrapper">
        <div class="facility-grid">
            <div>
                <span class="section-eyebrow" style="color:var(--seagreen-700)">STATE-OF-THE-ART LOGISTICS</span>
                <h2 style="font-family:var(--font-heading);font-size:1.85rem;color:var(--gray-900);margin-bottom:14px">
                    Johor Bahru Central Cold Logistics Hub (SILC)
                </h2>
                <p style="color:var(--gray-600);line-height:1.7;margin-bottom:16px">
                    Our central distribution facility in Kawasan Perindustrian SILC, Iskandar Puteri, Johor Bahru houses over 50 tonnes of multi-tier cold storage, operating 24/7 at stable temperatures from -18°C down to -25°C.
                </p>
                <ul style="padding-left:20px;color:var(--gray-700);line-height:1.8;font-size:0.9rem;margin:0">
                    <li>Multi-zone temperature compartmentalization for distinct seafood species</li>
                    <li>Dual backup power generators to eliminate temperature variation during power cuts</li>
                    <li>Live real-time digital temperature logging with instant threshold alerts</li>
                    <li>Clean-room packing facilities meeting HACCP & GMP hygienic requirements</li>
                </ul>
            </div>
            <div class="facility-metric-grid">
                <div style="background:white;padding:18px;border-radius:12px;border:1px solid #e2e8f0;text-align:center">
                    <div style="font-size:2rem;margin-bottom:4px">❄️</div>
                    <div style="font-weight:700;color:var(--gray-900);font-size:1.1rem">-18°C to -25°C</div>
                    <div style="font-size:0.75rem;color:var(--gray-500)">Continuous Cold Storage</div>
                </div>
                <div style="background:white;padding:18px;border-radius:12px;border:1px solid #e2e8f0;text-align:center">
                    <div style="font-size:2rem;margin-bottom:4px">🚚</div>
                    <div style="font-weight:700;color:var(--gray-900);font-size:1.1rem">24–48 Hours</div>
                    <div style="font-size:0.75rem;color:var(--gray-500)">Malaysia &amp; Singapore</div>
                </div>
                <div style="background:white;padding:18px;border-radius:12px;border:1px solid #e2e8f0;text-align:center">
                    <div style="font-size:2rem;margin-bottom:4px">🏪</div>
                    <div style="font-weight:700;color:var(--gray-900);font-size:1.1rem">Walk-in Counter</div>
                    <div style="font-size:0.75rem;color:var(--gray-500)">Instant Self-Collection</div>
                </div>
                <div style="background:white;padding:18px;border-radius:12px;border:1px solid #e2e8f0;text-align:center">
                    <div style="font-size:2rem;margin-bottom:4px">📜</div>
                    <div style="font-weight:700;color:var(--gray-900);font-size:1.1rem">100% Halal</div>
                    <div style="font-size:0.75rem;color:var(--gray-500)">HACCP & GMP Compliant</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ─── Section 5: Dynamic Reviews Section ──────────────────────────────── -->
    <div style="max-width:1100px;margin:0 auto var(--space-16)">
        <x-reviews-section :reviews="$reviews"
                           title="Trusted by Top Restaurants & Home Chefs"
                           subtitle="See how our continuous cold-chain quality elevates dining experiences across Malaysia & Singapore."
                           badge="VERIFIED CUSTOMER TESTIMONIALS" />
    </div>

    <!-- ─── Section 6: Frequently Asked Questions ───────────────────────────── -->
    <div style="max-width:850px;margin:0 auto var(--space-16)">
        <div class="section-header" style="text-align:center;margin-bottom:var(--space-8)">
            <div class="section-eyebrow" style="color:var(--seagreen-700)">HELP & CLARITY</div>
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">Everything you need to know about our sourcing, cold chain, and orders.</p>
        </div>

        <div style="display:flex;flex-direction:column;gap:14px">
            <details style="background:white;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;cursor:pointer">
                <summary style="font-weight:700;color:var(--gray-900);font-size:1rem;outline:none">How does IQF freezing compare to fresh seafood?</summary>
                <p style="margin-top:10px;font-size:0.9rem;color:var(--gray-600);line-height:1.7;margin-bottom:0">
                    Individual Quick Freezing (IQF) freezes seafood in minutes at ultra-low temperatures right after catch. This prevents the formation of large ice crystals that puncture cells in regular slow freezing. When thawed properly, IQF seafood matches the freshness, moisture, and flavor of day-of-catch seafood without chemical treatment.
                </p>
            </details>

            <details style="background:white;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;cursor:pointer">
                <summary style="font-weight:700;color:var(--gray-900);font-size:1rem;outline:none">How do I order for wholesale or restaurant catering?</summary>
                <p style="margin-top:10px;font-size:0.9rem;color:var(--gray-600);line-height:1.7;margin-bottom:0">
                    Simply register an account and choose the <strong>Wholesale</strong> or <strong>Trading</strong> tier. Once approved by our team (usually within 1 business day), your account unlocks wholesale pricing, bulk carton quantities, and Request for Quotation (RFQ) tools.
                </p>
            </details>

            <details style="background:white;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;cursor:pointer">
                <summary style="font-weight:700;color:var(--gray-900);font-size:1rem;outline:none">Can I collect my order in person at the Johor Bahru (SILC) facility?</summary>
                <p style="margin-top:10px;font-size:0.9rem;color:var(--gray-600);line-height:1.7;margin-bottom:0">
                    Yes! You can choose <strong>Self-Collection</strong> during checkout, or scan our QR code at our store entrance to browse with exclusive walk-in pricing and instant checkout counter collection.
                </p>
            </details>

            <details style="background:white;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;cursor:pointer">
                <summary style="font-weight:700;color:var(--gray-900);font-size:1rem;outline:none">What is your delivery coverage and guarantee?</summary>
                <p style="margin-top:10px;font-size:0.9rem;color:var(--gray-600);line-height:1.7;margin-bottom:0">
                    We deliver across Johor, nationwide Malaysia, and cross-border to Singapore via temperature-monitored cold trucks within 24–48 hours. If your seafood arrives defrosted or thawed, our Cold-Chain Guarantee replaces your order immediately at zero cost.
                </p>
            </details>
        </div>
    </div>

    <!-- ─── Section 7: Bottom CTA ────────────────────────────────────────────── -->
    <div class="about-cta-box">
        <h2 style="font-family:var(--font-heading);font-size:2rem;color:white;margin-bottom:12px">Ready to Experience Unrivalled Freshness?</h2>
        <p style="color:#dbeafe;font-size:1.05rem;max-width:550px;margin:0 auto var(--space-8);line-height:1.6">
            Browse our complete seafood catalogue or open a wholesale trading account today.
        </p>
        <div style="display:flex;gap:var(--space-4);justify-content:center;flex-wrap:wrap">
            <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg" style="background:#2563eb;border-color:#1d4ed8;color:#ffffff;font-weight:700">
                Browse Online Catalogue 🛒
            </a>
            <a href="{{ route('register') }}" class="btn btn-secondary btn-lg" style="background:white;color:#1d4ed8;font-weight:700">
                Register Free Account
            </a>
        </div>
    </div>

</div>

@push('styles')
<style>
/* ==========================================================================
   RELOCATION & COMPANY NAME CHANGE NOTICE - RESPONSIVE STYLES
   ========================================================================== */

.relocation-announcement {
    margin-bottom: var(--space-16, 4rem);
    max-width: 1100px;
    margin-left: auto;
    margin-right: auto;
}

.relocation-card {
    background: #ffffff;
    border: 2px solid #e0e7ff;
    border-radius: 20px;
    box-shadow: 0 12px 36px rgba(37, 99, 235, 0.08), 0 2px 8px rgba(15, 23, 42, 0.04);
    padding: clamp(20px, 4vw, 36px);
    position: relative;
    overflow: hidden;
    background-image: radial-gradient(circle at 100% 0%, #eff6ff 0%, transparent 40%),
                      radial-gradient(circle at 0% 100%, #f0fdf4 0%, transparent 30%);
}

/* Header */
.relocation-header {
    text-align: center;
    margin-bottom: clamp(24px, 4vw, 32px);
}

.relocation-header-badge-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.relocation-badge-primary {
    background: #eff6ff;
    color: #2563eb;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 9999px;
    border: 1px solid #bfdbfe;
    letter-spacing: 0.04em;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.relocation-badge-highlight {
    background: #fef2f2;
    color: #dc2626;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 9999px;
    border: 1px solid #fecaca;
    letter-spacing: 0.04em;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    animation: pulseNotice 2s infinite ease-in-out;
}

@keyframes pulseNotice {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.03); }
}

.relocation-main-title {
    font-family: var(--font-heading, "Inter", sans-serif);
    font-size: clamp(1.75rem, 4.5vw, 2.5rem);
    font-weight: 900;
    color: #0f172a;
    line-height: 1.25;
    margin: 0 0 6px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

.relocation-title-sub {
    color: #2563eb;
    font-weight: 800;
    font-size: clamp(1.2rem, 3.5vw, 1.75rem);
}

.relocation-en-title {
    font-size: clamp(0.85rem, 2vw, 1rem);
    font-weight: 700;
    color: #475569;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 20px;
}

.relocation-salutation-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #2563eb;
    border-radius: 12px;
    padding: 16px 20px;
    text-align: left;
    max-width: 900px;
    margin: 0 auto;
}

.relocation-salutation-heading {
    font-weight: 700;
    font-size: 0.95rem;
    color: #1e293b;
    margin-bottom: 6px;
}

.relocation-salutation-text {
    font-size: 0.92rem;
    color: #475569;
    line-height: 1.65;
    margin: 0;
}

.relocation-salutation-en {
    display: block;
    margin-top: 4px;
    color: #64748b;
    font-size: 0.85rem;
    font-style: italic;
}

/* Transition Grid (Old Name -> New Name) */
.relocation-transition-wrap {
    display: grid;
    grid-template-columns: 1fr auto 1.15fr;
    gap: 16px;
    align-items: center;
    margin-bottom: 28px;
}

.relocation-name-card {
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.relocation-name-old {
    background: #f8fafc;
    border: 1.5px dashed #cbd5e1;
}

.relocation-name-new {
    background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
    border: 2px solid #3b82f6;
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.12);
}

.relocation-name-tag {
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748b;
    letter-spacing: 0.06em;
    margin-bottom: 6px;
}

.relocation-name-tag-new {
    font-size: 0.72rem;
    font-weight: 800;
    color: #2563eb;
    letter-spacing: 0.06em;
    margin-bottom: 6px;
    display: inline-block;
    background: #dbeafe;
    padding: 2px 8px;
    border-radius: 4px;
}

.relocation-name-cn {
    font-size: 1.35rem;
    font-weight: 700;
    color: #475569;
    line-height: 1.2;
}

.relocation-name-cn-new {
    font-size: 1.45rem;
    font-weight: 900;
    color: #b45309;
    line-height: 1.2;
    background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.relocation-name-en {
    font-size: 0.95rem;
    font-weight: 600;
    color: #64748b;
    margin-top: 4px;
}

.relocation-name-en-new {
    font-size: 1.05rem;
    font-weight: 800;
    color: #1e3a8a;
    margin-top: 4px;
}

.relocation-name-foot {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 10px;
}

.relocation-name-foot-new {
    font-size: 0.78rem;
    color: #2563eb;
    font-weight: 600;
    margin-top: 10px;
}

.relocation-arrow-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 0 4px;
}

.relocation-arrow-pill {
    background: #dc2626;
    color: #ffffff;
    font-size: 0.72rem;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 9999px;
    white-space: nowrap;
    box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
}

.relocation-arrow-icon {
    font-size: 1.5rem;
    color: #dc2626;
    font-weight: 900;
    line-height: 1;
}

/* Location Comparison Grid */
.relocation-locations-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 18px;
    margin-bottom: 28px;
}

.relocation-loc-card {
    border-radius: 16px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.relocation-loc-old {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
}

.relocation-loc-new {
    background: #ffffff;
    border: 2px solid #2563eb;
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
}

.relocation-loc-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e2e8f0;
}

.relocation-loc-icon {
    font-size: 1.4rem;
    width: 38px;
    height: 38px;
    background: #e2e8f0;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.relocation-loc-icon-new {
    background: #dbeafe;
    color: #2563eb;
}

.relocation-loc-title {
    font-weight: 700;
    font-size: 0.95rem;
    color: #334155;
}

.relocation-loc-title-new {
    color: #1e3a8a;
    font-weight: 800;
}

.relocation-loc-subtitle {
    font-size: 0.72rem;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 0.05em;
}

.relocation-loc-status-old {
    margin-left: auto;
    font-size: 0.72rem;
    font-weight: 700;
    background: #e2e8f0;
    color: #64748b;
    padding: 3px 8px;
    border-radius: 6px;
}

.relocation-loc-status-new {
    margin-left: auto;
    font-size: 0.75rem;
    font-weight: 800;
    background: #fee2e2;
    color: #dc2626;
    padding: 4px 10px;
    border-radius: 6px;
    border: 1px solid #fca5a5;
    white-space: nowrap;
}

.relocation-address-text {
    font-style: normal;
    font-size: 0.92rem;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 14px;
}

.relocation-address-new {
    color: #0f172a;
    font-size: 0.96rem;
}

.relocation-loc-note {
    font-size: 0.78rem;
    color: #94a3b8;
}

.relocation-loc-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.btn-loc-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 12px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    border: none;
    transition: all 0.15s ease;
}

.btn-loc-gmaps {
    background: #2563eb;
    color: #ffffff !important;
}
.btn-loc-gmaps:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

.btn-loc-waze {
    background: #0284c7;
    color: #ffffff !important;
}
.btn-loc-waze:hover {
    background: #0369a1;
    transform: translateY(-1px);
}

.btn-loc-copy {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
}
.btn-loc-copy:hover {
    background: #e2e8f0;
}

/* Status Panel */
.relocation-status-panel {
    background: #ffffff;
    border: 1.5px solid #fde047;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: 0 4px 14px rgba(234, 179, 8, 0.08);
}

.relocation-status-panel-head {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.relocation-status-icon {
    font-size: 1.5rem;
}

.relocation-status-title {
    font-weight: 800;
    font-size: 0.95rem;
    color: #854d0e;
}

.relocation-status-desc {
    font-size: 0.82rem;
    color: #713f12;
    margin-top: 2px;
}

.relocation-status-items-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}

.relocation-status-pill {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.relocation-status-check {
    font-size: 1.2rem;
    line-height: 1;
}

.relocation-status-pill-cn {
    font-weight: 800;
    color: #166534;
    font-size: 0.88rem;
    line-height: 1.2;
}

.relocation-status-pill-en {
    font-size: 0.72rem;
    font-weight: 700;
    color: #15803d;
    letter-spacing: 0.03em;
    margin-top: 2px;
}

.relocation-warm-note {
    background: #fffbeb;
    border-radius: 10px;
    padding: 12px 16px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.relocation-warm-icon {
    font-size: 1.2rem;
    line-height: 1.4;
}

.relocation-warm-text {
    font-size: 0.875rem;
    color: #78350f;
    line-height: 1.6;
    margin: 0;
}

.relocation-warm-en {
    display: block;
    margin-top: 4px;
    font-size: 0.8rem;
    color: #92400e;
    font-style: italic;
}

/* Contacts Bar */
.relocation-contacts-bar {
    background: #0f172a;
    border-radius: 14px;
    padding: 18px 22px;
    color: #ffffff;
    margin-bottom: 16px;
}

.relocation-contacts-label {
    font-size: 0.8rem;
    font-weight: 800;
    color: #94a3b8;
    letter-spacing: 0.06em;
    margin-bottom: 12px;
}

.relocation-contacts-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.relocation-contact-item {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: inherit;
    transition: background 0.15s ease, transform 0.15s ease;
}

.relocation-contact-item:hover {
    background: rgba(255, 255, 255, 0.12);
    transform: translateY(-2px);
    color: #ffffff;
}

.relocation-contact-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.relocation-contact-type {
    font-size: 0.72rem;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 0.04em;
}

.relocation-contact-val {
    font-size: 0.92rem;
    font-weight: 800;
    color: #ffffff;
    margin-top: 2px;
}

/* Slogan Ribbon */
.relocation-slogan-ribbon {
    text-align: center;
    padding-top: 10px;
    border-top: 1px dashed #e2e8f0;
}

.relocation-slogan-cn {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0f172a;
}

.relocation-slogan-en {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 600;
    margin-top: 4px;
}

/* ==========================================================================
   RESPONSIVE BREAKPOINTS (TABLET & MOBILE)
   ========================================================================== */

/* Tablet & Medium Screens (<= 992px) */
@media (max-width: 992px) {
    .relocation-transition-wrap {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .relocation-arrow-badge {
        flex-direction: row;
        gap: 8px;
    }

    .relocation-arrow-icon {
        transform: rotate(90deg);
    }

    .relocation-locations-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .relocation-contacts-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .relocation-status-items-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}

/* Mobile Screens (<= 640px) */
@media (max-width: 640px) {
    .relocation-card {
        padding: 16px;
        border-radius: 14px;
    }

    .relocation-main-title {
        font-size: 1.5rem;
    }

    .relocation-title-sub {
        font-size: 1.15rem;
    }

    .relocation-salutation-box {
        padding: 12px 14px;
    }

    .relocation-name-card {
        padding: 14px;
    }

    .relocation-name-cn, .relocation-name-cn-new {
        font-size: 1.2rem;
    }

    .relocation-loc-card {
        padding: 14px;
    }

    .relocation-loc-actions {
        flex-direction: column;
    }

    .btn-loc-action {
        width: 100%;
        justify-content: center;
        padding: 9px 12px;
    }

    .relocation-status-panel {
        padding: 14px;
    }

    .relocation-contacts-bar {
        padding: 14px;
    }

    .relocation-slogan-cn {
        font-size: 0.85rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function copyNewAddress(btn) {
    const address = "7 Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor";
    navigator.clipboard.writeText(address).then(() => {
        const textSpan = btn.querySelector('.copy-text');
        const originalText = textSpan.textContent;
        textSpan.textContent = '已复制！Copied!';
        btn.style.background = '#dcfce7';
        btn.style.borderColor = '#86efac';
        btn.style.color = '#15803d';

        setTimeout(() => {
            textSpan.textContent = originalText;
            btn.style.background = '';
            btn.style.borderColor = '';
            btn.style.color = '';
        }, 2500);
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
</script>
@endpush
@endsection
