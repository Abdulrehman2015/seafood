@extends('layouts.app')

@section('title', ($policy->meta_title ?: $policy->title_for_locale) . ' — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd'))
@section('meta_description', $policy->meta_description ?: ($policy->summary ?: \Illuminate\Support\Str::limit(strip_tags($policy->content_for_locale), 160)))

@section('content')
<div class="policy-page-wrapper">
    <!-- Hero Header matching Site Aesthetic -->
    <section class="policy-hero">
        <div class="policy-hero-glow"></div>
        <div class="container">
            <!-- Breadcrumbs -->
            <nav class="policy-breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">@t('nav.home', 'Home')</a>
                <span class="crumb-sep">/</span>
                <span class="crumb-active">{{ $policy->title_for_locale }}</span>
            </nav>

            <div class="policy-hero-content">
                <div class="policy-badge">
                    <span class="badge-icon">📜</span>
                    <span>@t('policy.legal_policy', 'Store Policy & Legal Notice')</span>
                </div>
                <h1 class="policy-title">{{ $policy->title_for_locale }}</h1>
                @if($policy->summary)
                    <p class="policy-subtitle">{{ $policy->summary }}</p>
                @endif
                <div class="policy-meta-bar">
                    <span class="meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        @t('policy.last_updated', 'Last Updated'): {{ $policy->updated_at->format('F d, Y') }}
                    </span>
                    <span class="meta-dot">·</span>
                    <span class="meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        @t('policy.official_document', 'Official MST Document')
                    </span>
                    <button type="button" class="policy-print-btn" onclick="window.print()" title="Print this policy">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        <span>@t('policy.print', 'Print')</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Grid -->
    <section class="policy-body-section">
        <div class="container">
            @if($policy->status === 'draft')
                <div class="policy-draft-alert">
                    <div style="font-size:1.25rem">⚠️</div>
                    <div>
                        <strong>@t('policy.draft_preview_title', 'Draft Mode Preview')</strong>
                        <p style="margin:2px 0 0 0;font-size:0.84rem">@t('policy.draft_preview_desc', 'This page is currently unpublished and only visible to administrators. It is not visible to public visitors or in the footer.')</p>
                    </div>
                </div>
            @endif

            <div class="policy-layout-grid">
                <!-- Sidebar: Other Policies Navigation -->
                @if(isset($allPolicies) && $allPolicies->count() > 1)
                    <aside class="policy-sidebar">
                        <div class="policy-nav-card">
                            <h3 class="policy-nav-heading">@t('policy.all_policies', 'Policies & Guidelines')</h3>
                            <ul class="policy-nav-list">
                                @foreach($allPolicies as $otherPolicy)
                                    <li>
                                        <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => $otherPolicy->slug]) }}" 
                                           class="policy-nav-link {{ $otherPolicy->id === $policy->id ? 'active' : '' }}">
                                            <span class="nav-link-dot"></span>
                                            <span class="nav-link-title">{{ $otherPolicy->title_for_locale }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Need Help Widget -->
                        <div class="policy-help-widget">
                            <div class="help-widget-icon">💬</div>
                            <h4 class="help-widget-title">@t('policy.have_questions', 'Have Questions?')</h4>
                            <p class="help-widget-desc">@t('policy.help_desc', 'Our support & logistics team is here to assist with any policy inquiries.')</p>
                            <a href="{{ route('contact') }}" class="help-widget-btn">
                                @t('nav.contact', 'Contact Support') →
                            </a>
                        </div>
                    </aside>
                @endif

                <!-- Main Policy Content Card -->
                <main class="policy-main-content">
                    <article class="policy-article-card">
                        <div class="policy-rich-content">
                            {!! $policy->content_for_locale !!}
                        </div>

                        <!-- Footer Signoff -->
                        <div class="policy-signoff">
                            <div class="signoff-brand">
                                <strong>{{ $settings['store_name'] ?? 'MST Import and Export Sdn Bhd' }}</strong>
                                <span>@t('footer.tagline', $settings['store_tagline'] ?? 'Flow with Integrity, Grow with Strength')</span>
                            </div>
                            <div class="signoff-contact">
                                <span>📍 {{ $settings['store_address'] ?? 'Johor, Malaysia' }}</span>
                                <span>✉ <a href="mailto:{{ $settings['store_email'] ?? 'mikatrading15@gmail.com' }}">{{ $settings['store_email'] ?? 'mikatrading15@gmail.com' }}</a></span>
                            </div>
                        </div>
                    </article>
                </main>
            </div>
        </div>
    </section>
</div>

<style>
    .policy-page-wrapper {
        background: #f8fafc;
        min-height: 80vh;
    }

    /* Hero Section */
    .policy-hero {
        position: relative;
        background: linear-gradient(135deg, #06152b 0%, #0c2146 45%, #14356b 80%, #1d4ed8 100%);
        padding: 48px 0 54px;
        color: #ffffff;
        overflow: hidden;
    }

    .policy-hero-glow {
        position: absolute;
        top: -40%;
        right: -10%;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, rgba(2, 132, 199, 0) 70%);
        filter: blur(40px);
        pointer-events: none;
    }

    .policy-breadcrumbs {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #93c5fd;
        margin-bottom: 18px;
    }

    .policy-breadcrumbs a {
        color: #93c5fd;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .policy-breadcrumbs a:hover {
        color: #ffffff;
        text-decoration: underline;
    }

    .crumb-sep {
        color: rgba(255, 255, 255, 0.4);
    }

    .crumb-active {
        color: #ffffff;
        font-weight: 600;
    }

    .policy-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(56, 189, 248, 0.14);
        border: 1px solid rgba(56, 189, 248, 0.35);
        color: #7dd3fc;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 999px;
        margin-bottom: 12px;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .policy-title {
        font-family: 'Outfit', 'Inter', sans-serif;
        font-size: 2.2rem;
        font-weight: 800;
        line-height: 1.2;
        color: #ffffff;
        margin: 0 0 10px 0;
        letter-spacing: -0.02em;
    }

    .policy-subtitle {
        font-size: 1rem;
        color: #cbd5e1;
        max-width: 720px;
        margin: 0 0 18px 0;
        line-height: 1.5;
    }

    .policy-meta-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .meta-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #cbd5e1;
    }

    .meta-dot {
        color: rgba(255, 255, 255, 0.3);
    }

    .policy-print-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        margin-left: auto;
    }

    .policy-print-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: #7dd3fc;
    }

    /* Body Section */
    .policy-body-section {
        padding: 40px 0 70px;
    }

    .policy-draft-alert {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #fef3c7;
        border: 1px solid #fde68a;
        color: #92400e;
        border-radius: 12px;
        padding: 14px 20px;
        margin-bottom: 24px;
    }

    .policy-layout-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 32px;
        align-items: start;
    }

    @media (max-width: 900px) {
        .policy-layout-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Sidebar Navigation */
    .policy-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
        position: sticky;
        top: 90px;
    }

    .policy-nav-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .policy-nav-heading {
        font-size: 0.8rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0 0 12px 0;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }

    .policy-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .policy-nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 0.86rem;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .policy-nav-link .nav-link-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #cbd5e1;
        transition: all 0.15s ease;
    }

    .policy-nav-link:hover {
        background: #f8fafc;
        color: #1d4ed8;
    }

    .policy-nav-link:hover .nav-link-dot {
        background: #3b82f6;
    }

    .policy-nav-link.active {
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
        border: 1px solid #bfdbfe;
    }

    .policy-nav-link.active .nav-link-dot {
        background: #1d4ed8;
        transform: scale(1.3);
    }

    .policy-help-widget {
        background: linear-gradient(135deg, #0c2146 0%, #14356b 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        padding: 20px;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(6, 21, 43, 0.12);
    }

    .help-widget-icon {
        font-size: 1.5rem;
        margin-bottom: 8px;
    }

    .help-widget-title {
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0 0 6px 0;
        color: #ffffff;
    }

    .help-widget-desc {
        font-size: 0.8rem;
        color: #cbd5e1;
        margin: 0 0 14px 0;
        line-height: 1.4;
    }

    .help-widget-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #38bdf8;
        color: #0c2146;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 7px 14px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.15s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .help-widget-btn:hover {
        background: #7dd3fc;
        transform: translateY(-1px);
    }

    /* Main Article Card */
    .policy-article-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 36px 42px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }

    @media (max-width: 600px) {
        .policy-article-card {
            padding: 22px 18px;
        }
        .policy-title {
            font-size: 1.6rem;
        }
    }

    /* Rich Content Styling */
    .policy-rich-content {
        font-size: 0.95rem;
        line-height: 1.8;
        color: #334155;
    }

    .policy-rich-content h2 {
        font-family: 'Outfit', 'Inter', sans-serif;
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin: 28px 0 12px 0;
        padding-bottom: 8px;
        border-bottom: 1.5px solid #f1f5f9;
        letter-spacing: -0.01em;
    }

    .policy-rich-content h2:first-child {
        margin-top: 0;
    }

    .policy-rich-content h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 20px 0 8px 0;
    }

    .policy-rich-content p {
        margin: 0 0 16px 0;
    }

    .policy-rich-content ul,
    .policy-rich-content ol {
        margin: 0 0 18px 0;
        padding-left: 24px;
    }

    .policy-rich-content li {
        margin-bottom: 8px;
    }

    .policy-rich-content strong {
        color: #0f172a;
        font-weight: 700;
    }

    .policy-rich-content a {
        color: #2563eb;
        text-decoration: underline;
    }

    .policy-rich-content a:hover {
        color: #1d4ed8;
    }

    /* Signoff */
    .policy-signoff {
        margin-top: 40px;
        padding-top: 24px;
        border-top: 2px dashed #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 0.82rem;
        color: #64748b;
    }

    .signoff-brand {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .signoff-brand strong {
        color: #0f172a;
        font-size: 0.88rem;
    }

    .signoff-contact {
        display: flex;
        flex-direction: column;
        gap: 4px;
        text-align: right;
    }

    @media (max-width: 600px) {
        .signoff-contact {
            text-align: left;
        }
    }

    @media print {
        .policy-hero {
            background: #ffffff !important;
            color: #000000 !important;
            padding: 20px 0 !important;
        }
        .policy-title {
            color: #000000 !important;
        }
        .policy-subtitle, .policy-breadcrumbs, .policy-print-btn, .policy-sidebar, .footer, .navbar {
            display: none !important;
        }
        .policy-article-card {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
    }
</style>
@endsection
