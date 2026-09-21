<!doctype html>
<html lang="{{ current_locale() === 'zh' ? 'zh-Hans' : (current_locale() === 'bm' ? 'ms' : 'en') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $currentRouteName = request()->route() ? request()->route()->getName() : '';
        $routeSlugMap = [
            'home' => 'home',
            'shop.index' => 'shop',
            'shop.show' => 'shop',
            'about' => 'about',
            'contact' => 'contact',
            'quotations.create' => 'quotations',
            'walkin.index' => 'walkin',
            'cart.index' => 'cart',
            'checkout.index' => 'checkout',
            'terms' => 'terms_conditions',
            'privacy' => 'privacy_policy',
        ];
        $activeSlug = $routeSlugMap[$currentRouteName] ?? trim(request()->path(), '/');
        $activePageSeo = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('page_seos')) {
                $activePageSeo = \App\Models\PageSeo::where('page_slug', $activeSlug)->first();
            }
        } catch (\Throwable $e) {
            $activePageSeo = null;
        }

        $defaultOgImage = asset('images/og-default.jpg');
        $resolvedOgImage = $defaultOgImage;
        if (!empty($activePageSeo?->og_image)) {
            if (str_starts_with($activePageSeo->og_image, 'http')) {
                $resolvedOgImage = $activePageSeo->og_image;
            } elseif (file_exists(public_path($activePageSeo->og_image))) {
                $resolvedOgImage = asset($activePageSeo->og_image);
            } else {
                $resolvedOgImage = asset('storage/' . ltrim($activePageSeo->og_image, '/'));
            }
        }
        $cleanCanonical = url()->current();
        if (request()->path() === '/' || request()->path() === '') {
            $cleanCanonical = url('/' . current_locale());
        }
        $resolvedCanonical = !empty($activePageSeo?->canonical_url)
            ? $activePageSeo->canonical_url
            : (!empty($settings['canonical_url']) ? $settings['canonical_url'] : $cleanCanonical);
    @endphp

    <title>@yield('title', (!empty($activePageSeo?->meta_title) ? $activePageSeo->meta_title : ($settings['site_name'] ?? 'MST Import & Export | Frozen Food Sourcing & Trading')))</title>
    <meta name="description"
        content="@yield('meta_description', (!empty($activePageSeo?->meta_description) ? $activePageSeo->meta_description : ($settings['site_description'] ?? 'MST Import and Export provides frozen food sourcing, wholesale trading and cold-chain distribution for restaurants, retailers and global partners.')))">

    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    @php
        $keywords = !empty($activePageSeo?->meta_keywords) ? $activePageSeo->meta_keywords : ($settings['meta_keywords'] ?? 'fresh seafood, frozen salmon, king prawns, lobsters, seafood export, b2b seafood, cold-chain distribution, MST import export');
    @endphp
    @if(!empty($keywords))
        <meta name="keywords" content="{{ $keywords }}">
    @endif

    <link rel="canonical"
        href="@yield('canonical_url', $resolvedCanonical)">
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}?lang=en">
    <link rel="alternate" hreflang="zh-Hans" href="{{ url()->current() }}?lang=zh">
    <link rel="alternate" hreflang="ms" href="{{ url()->current() }}?lang=bm">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">
    <!-- Favicon & Apple Touch Icons -->
    <link rel="icon" type="image/x-icon" href="{{ url('/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ cdn_img('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ url('/site.webmanifest') }}">
    <meta name="apple-mobile-web-app-title" content="{{ $settings['store_name'] ?? 'MST Seafood' }}">
    <meta name="application-name" content="{{ $settings['store_name'] ?? 'MST Seafood' }}">
    <meta name="theme-color" content="#06152b">
    <meta name="msapplication-TileColor" content="#06152b">
    <meta name="msapplication-TileImage" content="{{ url('/apple-touch-icon.png') }}">

    <!-- Open Graph Meta Tags -->
    <meta property="og:site_name" content="{{ $settings['store_name'] ?? 'MST Import and Export Sdn Bhd' }}">
    <meta property="og:title"
        content="@yield('og_title', $activePageSeo?->meta_title ?? ($settings['site_name'] ?? 'MST Import & Export | Frozen Food Sourcing & Trading'))">
    <meta property="og:description"
        content="@yield('og_description', $activePageSeo?->meta_description ?? ($settings['site_description'] ?? 'MST Import and Export provides frozen food sourcing, wholesale trading and cold-chain distribution for restaurants, retailers and global partners.'))">
    <meta property="og:image" content="@yield('og_image', $resolvedOgImage)">
    <meta property="og:url" content="{{ $resolvedCanonical }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ current_locale() === 'zh' ? 'zh_CN' : (current_locale() === 'bm' ? 'ms_MY' : 'en_US') }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title"
        content="@yield('og_title', $activePageSeo?->meta_title ?? ($settings['site_name'] ?? 'MST Import & Export | Frozen Food Sourcing & Trading'))">
    <meta name="twitter:description"
        content="@yield('og_description', $activePageSeo?->meta_description ?? ($settings['site_description'] ?? 'MST Import and Export provides frozen food sourcing, wholesale trading and cold-chain distribution for restaurants, retailers and global partners.'))">
    <meta name="twitter:image" content="@yield('og_image', $resolvedOgImage)">

    <link rel="stylesheet" href="{{ url('/cdn-assets/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ url('/cdn-assets/css/app.min.css') }}?v={{ file_exists(public_path('css/app.min.css')) ? filemtime(public_path('css/app.min.css')) : time() }}">

    @if(!empty($settings['tracking_ga4_id']))
        <!-- Google Analytics GA4 -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['tracking_ga4_id'] }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '{{ $settings["tracking_ga4_id"] }}');
        </script>
    @endif

    @if(!empty($settings['schema_markup']))
        <!-- Schema Markup JSON-LD -->
        {!! str_starts_with(trim($settings['schema_markup']), '<script') ? $settings['schema_markup'] : '<script type="application/ld+json">' . $settings['schema_markup'] . '</script>' !!}
    @else
        <!-- Schema Markup JSON-LD (Organization & WebSite) -->
        @php
            $schemaOrg = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Organization',
                        '@id' => url('/') . '/#organization',
                        'name' => $settings['store_name'] ?? 'MST Import and Export Sdn Bhd',
                        'url' => url('/'),
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => cdn_img('logo.webp'),
                        ],
                        'description' => 'MST Import and Export Sdn Bhd provides frozen food sourcing, trading and distribution solutions across regional and international markets.',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'addressLocality' => 'Johor Bahru',
                            'addressCountry' => 'MY',
                        ],
                    ],
                    [
                        '@type' => 'WebSite',
                        '@id' => url('/') . '/#website',
                        'url' => url('/'),
                        'name' => $settings['store_name'] ?? 'MST Import and Export Sdn Bhd',
                        'publisher' => [
                            '@id' => url('/') . '/#organization',
                        ],
                    ],
                ],
            ];
        @endphp
        <script type="application/ld+json">
        {!! json_encode($schemaOrg, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endif

    @if(!empty($settings['header_tags']))
        <!-- Custom Header Tags -->
        {!! $settings['header_tags'] !!}
    @endif

    <style>
        /* ─── Footer: Matches Homepage 1st Section (Hero) Royal Oceanic Gradient ─── */
        .footer {
            position: relative !important;
            overflow: hidden !important;
            background: linear-gradient(135deg, #06152b 0%, #0c2146 40%, #14356b 75%, #1d4ed8 100%) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.12) !important;
            padding: var(--space-16, 4rem) 0 var(--space-8, 2rem) !important;
            margin-top: var(--space-16, 4rem) !important;
            color: #cbd5e1 !important;
        }
        .footer-bg-glow {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }
        .footer-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
        }
        .footer-orb-1 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.35) 0%, transparent 70%);
            top: -120px;
            right: -100px;
        }
        .footer-orb-2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(96, 165, 250, 0.25) 0%, transparent 70%);
            bottom: -60px;
            left: -80px;
        }
        .footer-grid-overlay {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }
        .footer .container {
            position: relative;
            z-index: 1;
        }
        .footer-brand .logo-brand {
            color: #ffffff !important;
            font-size: 1.35rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.02em !important;
        }
        .footer-brand .logo-sub {
            color: #93c5fd !important;
            font-size: 0.74rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.06em !important;
        }
        .footer-desc {
            font-size: 0.88rem !important;
            color: #cbd5e1 !important;
            max-width: 320px;
            line-height: 1.7 !important;
        }
        .footer-heading {
            font-family: var(--font-heading, inherit) !important;
            font-size: 0.92rem !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            text-transform: uppercase !important;
            letter-spacing: 0.08em !important;
            margin-bottom: var(--space-4, 1rem) !important;
        }
        .footer-links a {
            font-size: 0.88rem !important;
            color: #94a3b8 !important;
            text-decoration: none !important;
            transition: all 0.18s ease !important;
            display: inline-flex;
            align-items: center;
        }
        .footer-links a:hover {
            color: #60a5fa !important;
            transform: translateX(3px);
        }
        .footer-contact .contact-item {
            font-size: 0.875rem !important;
            color: #cbd5e1 !important;
            line-height: 1.55 !important;
        }
        .footer-contact .contact-item a {
            color: #cbd5e1 !important;
            text-decoration: none !important;
            transition: color 0.18s ease !important;
        }
        .footer-contact .contact-item a:hover {
            color: #60a5fa !important;
            text-decoration: underline !important;
        }
        .footer-bottom {
            padding-top: var(--space-6, 1.5rem) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #94a3b8 !important;
        }
        .footer-bottom p {
            color: #94a3b8 !important;
            margin: 0;
        }
        .footer-bottom-links a {
            color: #94a3b8 !important;
            text-decoration: none !important;
            transition: color 0.18s ease !important;
        }
        .footer-bottom-links a:hover {
            color: #ffffff !important;
        }
        .footer-social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            color: #ffffff !important;
            text-decoration: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
            flex-shrink: 0;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .footer-social-icon:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .social-share-btn:hover {
            transform: translateY(-2px);
            opacity: 0.92;
        }

        @keyframes btnSpin { 100% { transform: rotate(360deg); } }
        .btn-added {
            background: #059669 !important;
            border-color: #047857 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.35) !important;
        }

        /* ── Currency Selector (Circular Button & Smooth Dropdown) ── */
        .currency-menu {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        .currency-circle-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid var(--royalblue-600, #2563eb);
            color: var(--royalblue-600, #2563eb);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            font-weight: 800;
            font-family: var(--font-heading, inherit);
            letter-spacing: 0.02em;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.18);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            padding: 0;
            flex-shrink: 0;
            outline: none;
        }
        .currency-circle-btn:hover {
            background: var(--royalblue-50, #eff6ff);
            border-color: var(--royalblue-700, #1d4ed8);
            color: var(--royalblue-700, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28);
        }
        .currency-circle-btn.active {
            background: var(--royalblue-600, #2563eb);
            border-color: var(--royalblue-600, #2563eb);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
        }
        .currency-circle-code {
            line-height: 1;
        }
        .currency-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 240px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 16px 36px rgba(6, 21, 43, 0.14), 0 4px 12px rgba(6, 21, 43, 0.06);
            padding: 8px;
            opacity: 0;
            transform: translateY(-8px);
            pointer-events: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 1050;
        }
        .currency-dropdown.open {
            opacity: 1;
            transform: translateY(0);
            pointer-events: all;
        }
        .currency-dropdown-header {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--royalblue-700, #1d4ed8);
            padding: 6px 10px 8px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .currency-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 10px;
            text-decoration: none;
            color: #1e293b;
            background: none;
            border: 1px solid transparent;
            width: 100%;
            cursor: pointer;
            text-align: left;
            transition: all 0.15s ease;
            font-family: inherit;
        }
        .currency-option:hover {
            background: #f8fafc;
            border-color: #e2e8f0;
        }
        .currency-option.active {
            background: #eff6ff; /* Clean light royal blue surface */
            border-color: #bfdbfe; /* Glacial ice blue border */
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.08);
        }
        .currency-option-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 28px;
            border-radius: 6px;
            background: #f1f5f9;
            color: #334155;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            flex-shrink: 0;
            border: 1px solid #e2e8f0;
            transition: all 0.15s ease;
        }
        .currency-option:hover .currency-option-pill {
            background: #e2e8f0;
            color: #0f172a;
        }
        .currency-option.active .currency-option-pill {
            background: #2563eb; /* Vibrant Royal Blue matching brand */
            color: #ffffff;
            border-color: #1d4ed8;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.32);
        }
        .currency-option-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .currency-option-name {
            font-size: 0.84rem;
            font-weight: 700;
            line-height: 1.2;
            color: #1e293b;
            transition: color 0.15s ease;
        }
        .currency-option.active .currency-option-name {
            color: #1d4ed8; /* Strong Royal Blue */
            font-weight: 800;
        }
        .currency-option-rate {
            font-size: 0.72rem;
            color: #64748b;
            margin-top: 2px;
            transition: color 0.15s ease;
        }
        .currency-option.active .currency-option-rate {
            color: #2563eb;
            font-weight: 500;
        }
        .currency-option-check {
            color: #2563eb; /* Vibrant Royal Blue checkmark */
            font-weight: 900;
            font-size: 1rem;
            line-height: 1;
        }

        /* ─── Oceanic & Frozen Seafood Cold-Chain Page Switch Loader ─── */
        .page-switch-loader {
            position: fixed;
            inset: 0;
            z-index: 9999999;
            background: radial-gradient(circle at 50% 38%, rgba(10, 36, 74, 0.95) 0%, rgba(4, 18, 38, 0.98) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }

        .page-switch-loader.active {
            opacity: 1;
            pointer-events: auto;
        }

        .ocean-backdrop {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        /* Underwater Caustic Sunbeam */
        .ocean-caustic-light {
            position: absolute;
            top: -10%;
            left: 20%;
            width: 60%;
            height: 55%;
            background: radial-gradient(ellipse at 50% 0%, rgba(56, 189, 248, 0.22) 0%, rgba(14, 116, 144, 0.12) 45%, transparent 75%);
            filter: blur(50px);
            animation: causticSway 8s ease-in-out infinite alternate;
        }

        .ocean-depth-glow {
            position: absolute;
            bottom: -80px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 300px;
            background: radial-gradient(ellipse, rgba(30, 64, 175, 0.3) 0%, transparent 70%);
            filter: blur(70px);
        }

        /* Rising Ocean Air Bubbles */
        .ocean-bubble {
            position: absolute;
            bottom: -40px;
            border-radius: 50%;
            background: radial-gradient(circle at 32% 32%, rgba(255, 255, 255, 0.85) 0%, rgba(186, 230, 253, 0.45) 40%, rgba(56, 189, 248, 0.15) 80%, transparent 100%);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: inset 0 0 6px rgba(255, 255, 255, 0.6), 0 0 8px rgba(56, 189, 248, 0.3);
            animation: bubbleRise 6s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        .ocean-bubble.b1 { width: 14px; height: 14px; left: 15%; animation-duration: 6.5s; animation-delay: 0s; }
        .ocean-bubble.b2 { width: 22px; height: 22px; left: 28%; animation-duration: 7.8s; animation-delay: 1.4s; }
        .ocean-bubble.b3 { width: 10px; height: 10px; left: 52%; animation-duration: 5.8s; animation-delay: 0.7s; }
        .ocean-bubble.b4 { width: 18px; height: 18px; left: 70%; animation-duration: 8.2s; animation-delay: 2.1s; }
        .ocean-bubble.b5 { width: 12px; height: 12px; left: 84%; animation-duration: 6.9s; animation-delay: 3.2s; }
        .ocean-bubble.b6 { width: 26px; height: 26px; left: 42%; animation-duration: 9.0s; animation-delay: 2.8s; }

        @keyframes bubbleRise {
            0% {
                transform: translateY(0) translateX(0) scale(0.7);
                opacity: 0;
            }
            15% {
                opacity: 0.75;
            }
            50% {
                transform: translateY(-50vh) translateX(16px) scale(0.95);
            }
            85% {
                opacity: 0.75;
            }
            100% {
                transform: translateY(-110vh) translateX(-12px) scale(1.1);
                opacity: 0;
            }
        }

        @keyframes causticSway {
            0% { transform: scale(1) translateX(0); opacity: 0.18; }
            100% { transform: scale(1.12) translateX(25px); opacity: 0.28; }
        }

        /* Central Seafood Card */
        .ocean-loader-card {
            position: relative;
            z-index: 2;
            background: rgba(9, 30, 62, 0.72);
            border: 1px solid rgba(56, 189, 248, 0.25);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-radius: 28px;
            padding: 38px 46px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 20px 50px rgba(2, 10, 24, 0.65), 0 0 45px rgba(2, 132, 199, 0.25);
            transform: scale(0.95) translateY(6px);
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            min-width: 310px;
            max-width: 440px;
            box-sizing: border-box;
        }

        .page-switch-loader.active .ocean-loader-card {
            transform: scale(1) translateY(0);
        }

        /* Mascot Floating Container & Expanding Water Ripples */
        .ocean-mascot-wrap {
            position: relative;
            width: 136px;
            height: 136px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .water-ripple {
            position: absolute;
            border: 2px solid rgba(56, 189, 248, 0.55);
            border-radius: 50%;
            inset: 8px;
            pointer-events: none;
            animation: waterRipple 2.8s cubic-bezier(0.2, 0.8, 0.2, 1) infinite;
        }

        .water-ripple.r2 { animation-delay: 0.95s; }
        .water-ripple.r3 { animation-delay: 1.9s; }

        @keyframes waterRipple {
            0% {
                transform: scale(0.75);
                opacity: 0.85;
                border-color: rgba(125, 211, 252, 0.8);
            }
            100% {
                transform: scale(1.55);
                opacity: 0;
                border-color: rgba(2, 132, 199, 0);
            }
        }

        .ocean-mascot-badge {
            position: relative;
            width: 106px;
            height: 106px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #ffffff 0%, #f0f9ff 70%, #e0f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 28px rgba(2, 10, 24, 0.5), 0 0 30px rgba(56, 189, 248, 0.4);
            padding: 8px;
            box-sizing: border-box;
            z-index: 2;
            animation: oceanBob 2.8s ease-in-out infinite alternate;
        }

        .ocean-mascot-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 10px rgba(2, 132, 199, 0.3));
        }

        @keyframes oceanBob {
            0% {
                transform: translateY(-5px) rotate(-2deg);
            }
            100% {
                transform: translateY(5px) rotate(2deg);
            }
        }

        /* Brand Typography */
        .ocean-brand-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2px;
        }

        .ocean-brand-title {
            font-family: 'Outfit', 'Inter', sans-serif;
            font-size: 1.55rem;
            font-weight: 900;
            letter-spacing: 0.07em;
            line-height: 1.15;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(2, 10, 24, 0.5);
        }

        .ocean-brand-tagline {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            color: #7dd3fc;
            letter-spacing: 0.12em;
            margin-top: 4px;
            text-transform: uppercase;
        }

        /* Seafood Status Pill */
        .ocean-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.28);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 999px;
            padding: 6px 18px;
            margin-top: 16px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #e0f2fe;
            letter-spacing: 0.02em;
        }

        .ocean-live-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #38bdf8;
            box-shadow: 0 0 0 0 rgba(56, 189, 248, 0.8);
            animation: oceanPulse 1.8s infinite;
            flex-shrink: 0;
        }

        @keyframes oceanPulse {
            0% { box-shadow: 0 0 0 0 rgba(56, 189, 248, 0.8); }
            70% { box-shadow: 0 0 0 9px rgba(56, 189, 248, 0); }
            100% { box-shadow: 0 0 0 0 rgba(56, 189, 248, 0); }
        }

        /* Ocean Wave Progress Track & Fill */
        .ocean-wave-track {
            width: 210px;
            height: 6px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 999px;
            overflow: hidden;
            margin-top: 18px;
            position: relative;
        }

        .ocean-wave-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #0284c7 0%, #38bdf8 50%, #7dd3fc 100%);
            box-shadow: 0 0 12px rgba(56, 189, 248, 0.85);
            border-radius: 999px;
            transition: width 0.35s ease;
        }

        .main-content {
            transition: opacity 0.24s cubic-bezier(0.4, 0, 0.2, 1), transform 0.24s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: opacity, transform;
        }
        .main-content.lang-transitioning {
            opacity: 0.35;
            transform: translateY(2px);
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- ─── Oceanic Frozen Seafood & Cold-Chain Page Switch Loader ─── -->
    <div id="pageSwitchLoader" class="page-switch-loader" aria-hidden="true">
        <div class="ocean-backdrop">
            <div class="ocean-caustic-light"></div>
            <div class="ocean-depth-glow"></div>
            <!-- Rising Ocean Bubbles -->
            <div class="ocean-bubble b1"></div>
            <div class="ocean-bubble b2"></div>
            <div class="ocean-bubble b3"></div>
            <div class="ocean-bubble b4"></div>
            <div class="ocean-bubble b5"></div>
            <div class="ocean-bubble b6"></div>
        </div>

        <div class="ocean-loader-card">
            {{-- Seafood Chef Mascot with Water Ripples --}}
            <div class="ocean-mascot-wrap">
                <div class="water-ripple r1"></div>
                <div class="water-ripple r2"></div>
                <div class="water-ripple r3"></div>
                <div class="ocean-mascot-badge">
                    <img src="{{ cdn_img('logo.webp') }}" alt="{{ $settings['store_name'] ?? 'MST Import and Export Sdn Bhd' }}" class="ocean-mascot-img">
                </div>
            </div>

            {{-- Brand Typography & Cold-Chain Identity --}}
            <div class="ocean-brand-box">
                <span class="ocean-brand-title">MST IMPORT &amp; EXPORT</span>
                <span class="ocean-brand-tagline">
                    <span>❄️</span>
                    <span>PREMIUM FROZEN SEAFOOD &amp; COLD CHAIN</span>
                    <span>🐟</span>
                </span>
            </div>

            {{-- Fresh Catch / Loading Status --}}
            <div class="ocean-status-pill">
                <span class="ocean-live-dot"></span>
                <span id="loaderStatusText" class="ocean-status-text">Updating...</span>
            </div>

            {{-- Wave Progress Line --}}
            <div class="ocean-wave-track">
                <div id="loaderProgressBar" class="ocean-wave-bar"></div>
            </div>
        </div>
    </div>

    <nav class="navbar" id="navbar">
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="nav-logo">
                <img src="{{ cdn_img('logo.webp') }}" alt="{{ $settings['store_name'] ?? 'MST Import and Export Sdn Bhd' }}"
                    style="height:44px;width:44px;object-fit:contain;border-radius:8px;">
                <div class="logo-text">
                    <span class="logo-brand">MST</span>
                    <span class="logo-sub">@t('common.import_export_sdn_bhd', 'Import & Export Sdn Bhd')</span>
                </div>
            </a>
            <div class="nav-links" id="navLinks">
                <div class="mobile-drawer-header-hint">@t('nav.navigation', 'Navigation')</div>
                <div class="mobile-nav-group">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <span class="nav-link-content">
                            <svg class="mobile-nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>@t('nav.home', 'Home')</span>
                        </span>
                        <span class="mobile-chevron">›</span>
                    </a>
                    <a href="{{ route('shop.index') }}"
                        class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}">
                        <span class="nav-link-content">
                            <svg class="mobile-nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <span>@t('nav.shop', 'Shop')</span>
                        </span>
                        <span class="mobile-chevron">›</span>
                    </a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        <span class="nav-link-content">
                            <svg class="mobile-nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <span>@t('nav.about', 'About')</span>
                        </span>
                        <span class="mobile-chevron">›</span>
                    </a>
                    <a href="{{ route('contact') }}"
                        class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <span class="nav-link-content">
                            <svg class="mobile-nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            <span>@t('nav.contact', 'Contact')</span>
                        </span>
                        <span class="mobile-chevron">›</span>
                    </a>
                    @auth
                        @if(auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                            <a href="{{ route('quotations.index') }}"
                                class="nav-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}">
                                <span class="nav-link-content">
                                    <svg class="mobile-nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    <span>@t('nav.my_rfqs', 'My RFQs')</span>
                                </span>
                                <span class="mobile-chevron">›</span>
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="mobile-drawer-divider"></div>

                <!-- Mobile Drawer Cart Link -->
                <a href="{{ route('cart.index') }}" class="mobile-drawer-cart-link">
                    <div style="display:flex;align-items:center;gap:12px">
                        <div class="mobile-drawer-cart-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 01-8 0"></path>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.95rem;color:var(--seagreen-900);line-height:1.2">
                                @t('nav.shopping_cart', 'Shopping Cart')</div>
                            <div style="font-size:0.75rem;color:#64748b;margin-top:2px">@t('nav.view_items_checkout', 'View items & checkout')</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span class="cart-count mobile-cart-badge" style="display:none">0</span>
                        <span class="mobile-chevron" style="color:var(--seagreen-700)">›</span>
                    </div>
                </a>

                <!-- Mobile Drawer User Account Section -->
                @auth
                    <div class="mobile-drawer-user-card">
                        <div class="mobile-drawer-user-header">
                            <div class="user-avatar"
                                style="width:40px;height:40px;font-size:1rem;background:var(--seagreen-700);color:#ffffff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div style="flex:1;min-width:0">
                                <div
                                    style="font-weight:700;color:var(--seagreen-900);font-size:0.95rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ auth()->user()->name }}
                                </div>
                                <div style="display:flex;align-items:center;gap:6px;margin-top:3px;flex-wrap:wrap">
                                    <span class="group-badge group-{{ auth()->user()->customer_group }}"
                                        style="font-size:0.7rem;padding:2px 8px;border-radius:6px">
                                        {{ ucfirst(auth()->user()->customer_group) }}
                                    </span>
                                    @if(auth()->user()->isAdmin())
                                        <span
                                            style="font-size:0.7rem;background:#fee2e2;color:#991b1b;padding:2px 7px;border-radius:6px;font-weight:700">Admin</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mobile-drawer-user-links">
                            <a href="{{ route('account.dashboard') }}" class="mobile-drawer-user-item">
                                <div style="display:flex;align-items:center;gap:10px">
                                    <span class="drawer-icon">📊</span>
                                    <span>@t('nav.dashboard', 'Dashboard')</span>
                                </div>
                                <span class="mobile-chevron">›</span>
                            </a>
                            <a href="{{ route('account.orders') }}" class="mobile-drawer-user-item">
                                <div style="display:flex;align-items:center;gap:10px">
                                    <span class="drawer-icon">📦</span>
                                    <span>@t('nav.my_orders', 'My Orders')</span>
                                </div>
                                <span class="mobile-chevron">›</span>
                            </a>
                            <a href="{{ route('account.profile') }}" class="mobile-drawer-user-item">
                                <div style="display:flex;align-items:center;gap:10px">
                                    <span class="drawer-icon">👤</span>
                                    <span>@t('nav.profile', 'Profile Settings')</span>
                                </div>
                                <span class="mobile-chevron">›</span>
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="mobile-drawer-user-item admin-item">
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <span class="drawer-icon">⚡</span>
                                        <span>@t('nav.admin_panel', 'Admin Panel')</span>
                                    </div>
                                    <span class="mobile-chevron" style="color:var(--seagreen-700)">›</span>
                                </a>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('logout') }}" style="margin-top:10px">
                            @csrf
                            <button type="submit" class="mobile-drawer-logout-btn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span>@t('nav.sign_out', 'Sign Out')</span>
                            </button>
                        </form>
                    </div>
                @else
                <div class="mobile-drawer-auth-card">
                    <div class="mobile-drawer-auth-title">@t('nav.welcome_to', 'Welcome to') {{ $settings['store_name'] ?? 'MST Import and Export Sdn Bhd' }}</div>
                    <div class="mobile-drawer-auth-sub">@t('nav.signin_sub', 'Sign in to track orders or access wholesale rates')</div>
                    <div class="mobile-drawer-auth-buttons">
                        <a href="{{ route('login') }}" class="btn btn-primary"
                            style="flex:1;text-align:center;font-weight:700;padding:11px;border-radius:10px">@t('nav.sign_in', 'Sign In')</a>
                        <a href="{{ route('register') }}" class="btn btn-secondary"
                            style="flex:1;text-align:center;font-weight:700;padding:11px;border-radius:10px">@t('nav.register', 'Register')</a>
                    </div>
                </div>
                @endguest

                <div class="mobile-drawer-footer-info">
                    <span>📞 {{ $settings['store_phone'] ?? '013-280 0168' }}</span>
                    <span>•</span>
                    <span>📍 @t('common.location_jb', 'Johor Bahru, Malaysia')</span>
                </div>
            </div>
            <div class="nav-actions">
                @if(session('walkin_session'))
                    <div class="walkin-badge">
                        <span>🏪 @t('common.walkin_mode', 'Walk-in Mode')</span>
                        <a href="{{ route('walkin.exit') }}" class="walkin-exit">@t('common.exit', 'Exit')</a>
                    </div>
                @endif
                @auth
                    <div class="group-badge group-{{ auth()->user()->customer_group }}">
                        {{ ucfirst(auth()->user()->customer_group) }}
                    </div>
                @endauth

                <!-- Multilingual Language Selector Circle Button (EN / ZH / BM) -->
                <div class="currency-menu" id="languageMenu" style="margin-right:6px">
                    <button type="button" class="currency-circle-btn" id="languageBtn" onclick="toggleLanguageMenu()" aria-label="Select Language" title="Select Language">
                        <span class="currency-circle-code" id="activeLanguageCode">{{ $activeLocaleData['label'] ?? 'EN' }}</span>
                    </button>
                    <div class="currency-dropdown" id="languageDropdown">
                        <div class="currency-dropdown-header">Select Language</div>
                        @foreach($supportedLocales as $code => $loc)
                            <a href="{{ route('language.switch', $code) }}" class="currency-option {{ $currentLocale === $code ? 'active' : '' }}" data-code="{{ $code }}" onclick="selectLanguage(event, '{{ $code }}', '{{ route('language.switch', $code) }}')">
                                <span class="currency-option-pill">{{ $loc['label'] }}</span>
                                <div class="currency-option-info">
                                    <span class="currency-option-name">{{ $loc['native'] }}</span>
                                    <span class="currency-option-rate">
                                        @if($code === 'en')
                                            Default Language
                                        @else
                                            {{ $loc['name'] }}
                                        @endif
                                    </span>
                                </div>
                                <span class="currency-option-check" style="{{ $currentLocale === $code ? '' : 'display:none' }}">✓</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Currency Selector Circle Button (RM / SGD / USD) -->
                <div class="currency-menu" id="currencyMenu">
                    <button type="button" class="currency-circle-btn" id="currencyBtn" onclick="toggleCurrencyMenu()" aria-label="Select Currency" title="Select Currency">
                        <span class="currency-circle-code" id="activeCurrencyCode">{{ $currencyList[$currentCurrency]['label'] ?? 'RM' }}</span>
                    </button>
                    <div class="currency-dropdown" id="currencyDropdown">
                        <div class="currency-dropdown-header">Select Currency</div>
                        @foreach($currencyList as $code => $cur)
                            <button type="button" class="currency-option {{ $currentCurrency === $code ? 'active' : '' }}" onclick="selectCurrency('{{ $code }}')" data-code="{{ $code }}">
                                <span class="currency-option-pill">{{ $cur['label'] }}</span>
                                <div class="currency-option-info">
                                    <span class="currency-option-name">{{ $cur['name'] }}</span>
                                    <span class="currency-option-rate">
                                        @if($code === 'MYR')
                                            Base Currency
                                        @else
                                            1 RM ≈ {{ $cur['symbol'] }} {{ number_format($currencyService->convert(1, $code), 4) }}
                                        @endif
                                    </span>
                                </div>
                                <span class="currency-option-check" style="{{ $currentCurrency === $code ? '' : 'display:none' }}">✓</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('cart.index') }}" class="cart-btn" id="cartBtn">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 01-8 0"></path>
                    </svg>
                    <span class="cart-count" id="cartCount" style="display:none">0</span>
                </a>
                @guest
                    <a href="{{ route('login') }}" class="btn-ghost">@t('nav.sign_in', 'Sign In')</a>
                    <a href="{{ route('register') }}" class="btn-primary-sm">@t('nav.register', 'Register')</a>
                @else
                    <div class="user-menu" id="userMenu">
                        <button class="user-btn" onclick="toggleUserMenu()">
                            <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            <span class="user-name-nav">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="{{ route('account.dashboard') }}" class="dropdown-item">@t('nav.dashboard', 'Dashboard')</a>
                            <a href="{{ route('account.orders') }}" class="dropdown-item">@t('nav.my_orders', 'My Orders')</a>
                            <a href="{{ route('account.profile') }}" class="dropdown-item">@t('nav.profile', 'Profile')</a>
                            @if(auth()->user()->isAdmin())
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item dropdown-admin">@t('nav.admin_panel', 'Admin Panel')</a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item dropdown-logout">@t('nav.sign_out', 'Sign Out')</button>
                            </form>
                        </div>
                    </div>
                @endguest
                <button class="mobile-toggle" id="mobileToggle" onclick="toggleMobileMenu()">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    @if(session('success') || session('error') || session('info'))
        <div class="flash-container">
            @if(session('success'))
                <div class="flash flash-success">✓ {{ session('success') }}<button class="flash-close"
                        onclick="this.parentElement.remove()">✕</button></div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">⚠ {{ session('error') }}<button class="flash-close"
                        onclick="this.parentElement.remove()">✕</button></div>
            @endif
            @if(session('info'))
                <div class="flash flash-info">ℹ {{ session('info') }}<button class="flash-close"
                        onclick="this.parentElement.remove()">✕</button></div>
            @endif
        </div>
    @endif

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="footer">
        {{-- Background Glow Orbs matching Homepage Hero Section --}}
        <div class="footer-bg-glow" aria-hidden="true">
            <div class="footer-orb footer-orb-1"></div>
            <div class="footer-orb footer-orb-2"></div>
            <div class="footer-grid-overlay"></div>
        </div>

        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="{{ cdn_img('logo.webp') }}" alt="{{ $settings['store_name'] ?? 'MST Import and Export Sdn Bhd' }}"
                            style="height:52px;width:52px;object-fit:contain;border-radius:10px;">
                        <div class="logo-text">
                            <span class="logo-brand">MST</span>
                            <span class="logo-sub">@t('common.import_export_sdn_bhd', 'Import & Export Sdn Bhd')</span>
                        </div>
                    </div>
                    <p class="footer-desc">
                        @t('footer.tagline', $settings['store_tagline'] ?? 'Flow with Integrity, Grow with Strength')
                    </p>
                    <div class="footer-social"
                        style="display:flex;gap:10px;align-items:center;margin-top:16px;flex-wrap:wrap">
                        <!-- WhatsApp -->
                        <a href="{{ !empty($settings['social_whatsapp']) ? $settings['social_whatsapp'] : 'https://wa.me/60123456789' }}"
                            target="_blank" class="footer-social-icon" title="WhatsApp"
                            style="background:#25D366;box-shadow:0 2px 6px rgba(37,211,102,0.35)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2zm.01 1.67c4.54 0 8.24 3.7 8.24 8.24 0 2.2-.86 4.27-2.41 5.82a8.18 8.18 0 0 1-5.83 2.42c-1.45 0-2.88-.38-4.14-1.11l-.3-.17-3.12.82.83-3.04-.19-.31a8.21 8.21 0 0 1-1.26-4.43c0-4.54 3.7-8.24 8.24-8.24zm4.52 11.64c-.25-.12-1.47-.72-1.7-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.38-1.72-.15-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.15.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.12-.56-1.34-.76-1.84-.2-.48-.4-.42-.56-.43h-.47c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.12.17 1.78 2.71 4.3 3.8 2.53 1.09 2.53.73 2.99.69.45-.04 1.47-.6 1.68-1.18.2-.58.2-1.08.14-1.18-.06-.1-.22-.16-.47-.28z" />
                            </svg>
                        </a>

                        <!-- Facebook -->
                        <a href="{{ !empty($settings['social_facebook']) ? $settings['social_facebook'] : 'https://facebook.com' }}"
                            target="_blank" class="footer-social-icon" title="Facebook"
                            style="background:#1877F2;box-shadow:0 2px 6px rgba(24,119,242,0.35)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>

                        <!-- Instagram -->
                        <a href="{{ !empty($settings['social_instagram']) ? $settings['social_instagram'] : 'https://instagram.com' }}"
                            target="_blank" class="footer-social-icon" title="Instagram"
                            style="background:linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);box-shadow:0 2px 6px rgba(220,39,67,0.35)">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>

                        <!-- TikTok -->
                        <a href="{{ !empty($settings['social_tiktok']) ? $settings['social_tiktok'] : 'https://tiktok.com' }}"
                            target="_blank" class="footer-social-icon" title="TikTok"
                            style="background:#000000;box-shadow:0 2px 6px rgba(0,0,0,0.35)">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                            </svg>
                        </a>

                        <!-- X / Twitter -->
                        <a href="{{ !empty($settings['social_twitter']) ? $settings['social_twitter'] : 'https://x.com' }}"
                            target="_blank" class="footer-social-icon" title="X (Twitter)"
                            style="background:#0f172a;box-shadow:0 2px 6px rgba(15,23,42,0.35)">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">@t('footer.quick_links', 'Quick Links')</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">@t('nav.home', 'Home')</a></li>
                        <li><a href="{{ route('shop.index') }}">@t('footer.shop_catalogue', 'Shop Catalogue')</a></li>
                        <li><a href="{{ route('walkin.entry') }}">@t('footer.walkin_store', 'Walk-in Store (QR)')</a></li>
                        <li><a href="{{ route('about') }}">@t('nav.about', 'About Us')</a></li>
                        <li><a href="{{ route('contact') }}">@t('nav.contact', 'Contact Us')</a></li>
                        @php
                            $publishedFooterPolicies = \App\Models\Policy::published()->get();
                        @endphp
                        @foreach($publishedFooterPolicies as $footerPolicy)
                            <li><a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => $footerPolicy->slug]) }}">{{ $footerPolicy->title_for_locale }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">@t('footer.featured_categories', 'Featured Categories')</h4>
                    <ul class="footer-links">
                        @if(isset($footerCategories) && $footerCategories->count())
                            @foreach($footerCategories as $fCat)
                                <li><a href="{{ route('shop.index', ['category' => $fCat->slug]) }}">{{ $fCat->name }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ route('shop.index') }}">@t('footer.all_fresh_seafood', 'All Fresh Seafood')</a></li>
                        @endif
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">@t('footer.location_contact', 'Store Location & Contact')</h4>
                    <div class="footer-contact">
                        <div class="contact-item">📍
                            @t('footer.store_address', $settings['store_address'] ?? '7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia')
                        </div>
                        @php
                            $footerPhones = array_filter([
                                $settings['store_phone'] ?? '013-2800168',
                                $settings['store_phone_2'] ?? '',
                                $settings['store_phone_3'] ?? '',
                            ]);
                        @endphp
                        @foreach($footerPhones as $fPhone)
                            <div class="contact-item">📞 <a href="tel:{{ preg_replace('/[^0-9+]/', '', $fPhone) }}"
                                    style="color:inherit;text-decoration:none">{{ $fPhone }}</a></div>
                        @endforeach
                        <div class="contact-item">✉ <a
                                href="mailto:{{ $settings['store_email'] ?? 'mikatrading15@gmail.com' }}"
                                style="color:inherit;text-decoration:none">{{ $settings['store_email'] ??
                                'mikatrading15@gmail.com' }}</a></div>
                        <div class="contact-item">🕐 @t('footer.store_hours', $settings['store_hours'] ?? 'Monday - Saturday: 8:00am - 6:00pm (Sunday & Public Holidays: Closed)')</div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© {{ date('Y') }} @t('footer.company_name', rtrim($settings['store_name'] ?? 'MST Import and Export Sdn Bhd', '.')) · @t('footer.all_rights_reserved', 'All rights reserved.')</p>
                <div class="footer-bottom-links">
                    <a href="{{ route('contact') }}">@t('footer.support', 'Support')</a>
                    <a href="{{ route('about') }}">@t('nav.about', 'About')</a>
                    @if(isset($publishedFooterPolicies) && $publishedFooterPolicies->count())
                        @foreach($publishedFooterPolicies->take(3) as $bPolicy)
                            <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => $bPolicy->slug]) }}">{{ $bPolicy->title_for_locale }}</a>
                        @endforeach
                    @endif
                    <a href="{{ route('walkin.entry') }}">@t('footer.instore_pass', 'In-Store Pass')</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        window.addEventListener('scroll', () => {
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
        });
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            const toggle = document.getElementById('mobileToggle');
            const isOpen = navLinks.classList.toggle('open');
            toggle.classList.toggle('active', isOpen);
            document.body.classList.toggle('mobile-drawer-open', isOpen);
        }
        window.AppCurrency = {
            current: @json($currentCurrency),
            label: @json($currencyLabel),
            symbol: @json($currencySymbol),
            isAuto: @json((bool)$currencyService->isAutoConvert()),
            rates: @json($currencyService->getRates()),
            currencies: @json($currencyList)
        };

        function selectCurrency(code) {
            // 1. Close currency dropdown
            document.getElementById('currencyDropdown')?.classList.remove('open');
            document.getElementById('currencyBtn')?.classList.remove('active');

            // 2. Immediate visual update of circular button text
            const label = code === 'MYR' ? 'RM' : code;
            const circleCode = document.getElementById('activeCurrencyCode');
            if (circleCode) circleCode.textContent = label;

            // 3. Update dropdown options visual active/check state
            document.querySelectorAll('#currencyDropdown .currency-option').forEach(opt => {
                const isSelected = opt.getAttribute('data-code') === code;
                opt.classList.toggle('active', isSelected);
                const check = opt.querySelector('.currency-option-check');
                if (check) check.style.display = isSelected ? 'inline-block' : 'none';
            });

            // 4. Update local currency state
            if (window.AppCurrency) {
                window.AppCurrency.current = code;
                const curMeta = window.AppCurrency.currencies[code] || {};
                window.AppCurrency.symbol = curMeta.symbol || (code === 'MYR' ? 'RM' : code);
                window.AppCurrency.label = curMeta.label || code;
            }

            // 5. Update every price on the current page immediately WITHOUT refresh
            updatePageCurrencies(code);

            // 6. Asynchronously update server session
            fetch('/currency/' + encodeURIComponent(code), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(r => r.json()).then(data => {
                if (data && data.success) {
                    if (data.rates) window.AppCurrency.rates = data.rates;
                    if (typeof data.is_auto !== 'undefined') window.AppCurrency.isAuto = data.is_auto;
                    updatePageCurrencies(code);
                }
            }).catch(e => console.error('Currency switch error:', e));
        }

        function calculatePriceForElement(el, targetCurrency) {
            const baseRm = parseFloat(el.getAttribute('data-base-rm') || '0');
            if (isNaN(baseRm) || baseRm <= 0) {
                return { formatted: 'Price on Request', amount: null, baseRm: null };
            }

            const conf = window.AppCurrency || {};
            const curMeta = (conf.currencies && conf.currencies[targetCurrency]) || {};
            const symbol = curMeta.symbol || (targetCurrency === 'MYR' ? 'RM' : targetCurrency);

            if (targetCurrency === 'MYR') {
                return {
                    amount: baseRm,
                    symbol: 'RM',
                    formatted: 'RM ' + baseRm.toFixed(2),
                    baseRm: null
                };
            }

            // Auto Convert mode
            if (conf.isAuto) {
                const rate = (conf.rates && conf.rates[targetCurrency]) ? parseFloat(conf.rates[targetCurrency]) : 1;
                const converted = Math.round(baseRm * rate * 100) / 100;
                return {
                    amount: converted,
                    symbol: symbol,
                    formatted: symbol + ' ' + converted.toFixed(2),
                    baseRm: baseRm
                };
            }

            // Manual Mode (Auto OFF): Check per-product manual prices
            const isWholesale = el.getAttribute('data-group') === 'wholesale' || el.getAttribute('data-group') === 'trading';
            let manualVal = null;
            if (targetCurrency === 'SGD') {
                manualVal = isWholesale ? (el.getAttribute('data-manual-wholesale-sgd') || el.getAttribute('data-manual-sgd')) : el.getAttribute('data-manual-sgd');
            } else if (targetCurrency === 'USD') {
                manualVal = isWholesale ? (el.getAttribute('data-manual-wholesale-usd') || el.getAttribute('data-manual-usd')) : el.getAttribute('data-manual-usd');
            }

            if (manualVal && !isNaN(parseFloat(manualVal)) && parseFloat(manualVal) > 0) {
                const amt = parseFloat(manualVal);
                return {
                    amount: amt,
                    symbol: symbol,
                    formatted: symbol + ' ' + amt.toFixed(2),
                    baseRm: baseRm
                };
            }

            // Fallback to manual exchange rate conversion
            const rate = (conf.rates && conf.rates[targetCurrency]) ? parseFloat(conf.rates[targetCurrency]) : 1;
            const converted = Math.round(baseRm * rate * 100) / 100;
            return {
                amount: converted,
                symbol: symbol,
                formatted: symbol + ' ' + converted.toFixed(2),
                baseRm: baseRm
            };
        }

        function updatePageCurrencies(targetCurrency) {
            const conf = window.AppCurrency || {};
            const curMeta = (conf.currencies && conf.currencies[targetCurrency]) || {};
            const symbol = curMeta.symbol || (targetCurrency === 'MYR' ? 'RM' : targetCurrency);

            // 1. Update all standard product price blocks (.js-currency-price)
            document.querySelectorAll('.js-currency-price').forEach(el => {
                const res = calculatePriceForElement(el, targetCurrency);
                const amountEl = el.querySelector('.price-amount');
                if (amountEl) {
                    amountEl.textContent = res.formatted;
                } else {
                    el.textContent = res.formatted;
                }

                const baseRmEl = el.querySelector('.price-base-rm');
                if (baseRmEl) {
                    if (targetCurrency !== 'MYR' && res.baseRm) {
                        baseRmEl.textContent = 'RM ' + res.baseRm.toFixed(2);
                        baseRmEl.style.display = 'block';
                    } else {
                        baseRmEl.style.display = 'none';
                    }
                }
            });

            // 2. Update product detail approx note (.js-product-approx-note)
            document.querySelectorAll('.js-product-approx-note').forEach(el => {
                const baseRm = parseFloat(el.getAttribute('data-base-rm') || '0');
                if (targetCurrency !== 'MYR' && baseRm > 0) {
                    el.innerHTML = 'Approx. <strong>RM ' + baseRm.toFixed(2) + '</strong> (billed in MYR at checkout)';
                    el.style.display = 'block';
                } else {
                    el.style.display = 'none';
                }
            });

            // 3. Update cart items & totals if on cart page (.js-cart-item-price, .js-cart-item-subtotal, etc.)
            document.querySelectorAll('.js-cart-item-price').forEach(el => {
                const res = calculatePriceForElement(el, targetCurrency);
                el.textContent = res.formatted;
            });
            document.querySelectorAll('.js-cart-item-subtotal').forEach(el => {
                const qty = parseInt(el.getAttribute('data-qty') || '1', 10);
                const parent = el.closest('[data-base-rm]');
                const unitBase = parent ? parseFloat(parent.getAttribute('data-base-rm') || '0') : parseFloat(el.getAttribute('data-base-rm') || '0');
                const lineBase = unitBase * qty;
                const rate = (conf.rates && conf.rates[targetCurrency]) ? parseFloat(conf.rates[targetCurrency]) : 1;
                const convertedLine = targetCurrency === 'MYR' ? lineBase : Math.round(lineBase * rate * 100) / 100;
                if (targetCurrency !== 'MYR') {
                    el.innerHTML = symbol + ' ' + convertedLine.toFixed(2) + '<div style="font-size:0.75rem;color:#64748b;font-weight:normal">RM ' + lineBase.toFixed(2) + '</div>';
                } else {
                    el.textContent = 'RM ' + lineBase.toFixed(2);
                }
            });
            document.querySelectorAll('.js-cart-summary-subtotal').forEach(el => {
                const base = parseFloat(el.getAttribute('data-base-subtotal') || '0');
                const rate = (conf.rates && conf.rates[targetCurrency]) ? parseFloat(conf.rates[targetCurrency]) : 1;
                const converted = targetCurrency === 'MYR' ? base : Math.round(base * rate * 100) / 100;
                if (targetCurrency !== 'MYR') {
                    el.innerHTML = symbol + ' ' + converted.toFixed(2) + '<span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal">RM ' + base.toFixed(2) + '</span>';
                } else {
                    el.textContent = 'RM ' + base.toFixed(2);
                }
            });
            document.querySelectorAll('.js-cart-summary-total').forEach(el => {
                const base = parseFloat(el.getAttribute('data-base-total') || '0');
                const rate = (conf.rates && conf.rates[targetCurrency]) ? parseFloat(conf.rates[targetCurrency]) : 1;
                const converted = targetCurrency === 'MYR' ? base : Math.round(base * rate * 100) / 100;
                if (targetCurrency !== 'MYR') {
                    el.innerHTML = symbol + ' ' + converted.toFixed(2) + '<span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal;margin-top:2px">Base: RM ' + base.toFixed(2) + '</span>';
                } else {
                    el.textContent = 'RM ' + base.toFixed(2);
                }
            });
            document.querySelectorAll('.js-cart-currency-note').forEach(el => {
                el.style.display = targetCurrency !== 'MYR' ? 'block' : 'none';
                const codeEl = el.querySelector('.js-cart-currency-code');
                if (codeEl) codeEl.textContent = targetCurrency;
            });
        }

        function toggleUserMenu() {
            document.getElementById('userDropdown')?.classList.toggle('open');
            document.getElementById('currencyDropdown')?.classList.remove('open');
            document.getElementById('currencyBtn')?.classList.remove('active');
            document.getElementById('languageDropdown')?.classList.remove('open');
            document.getElementById('languageBtn')?.classList.remove('active');
        }
        function toggleCurrencyMenu() {
            const dropdown = document.getElementById('currencyDropdown');
            const btn = document.getElementById('currencyBtn');
            const isOpen = dropdown?.classList.toggle('open');
            btn?.classList.toggle('active', isOpen);
            document.getElementById('userDropdown')?.classList.remove('open');
            document.getElementById('languageDropdown')?.classList.remove('open');
            document.getElementById('languageBtn')?.classList.remove('active');
        }
        function toggleLanguageMenu() {
            const dropdown = document.getElementById('languageDropdown');
            const btn = document.getElementById('languageBtn');
            const isOpen = dropdown?.classList.toggle('open');
            btn?.classList.toggle('active', isOpen);
            document.getElementById('userDropdown')?.classList.remove('open');
            document.getElementById('currencyDropdown')?.classList.remove('open');
            document.getElementById('currencyBtn')?.classList.remove('active');
        }

        let isLanguageSwitching = false;

        function showPageLoader(message = 'Loading...') {
            const loader = document.getElementById('pageSwitchLoader');
            const statusText = document.getElementById('loaderStatusText');
            const progressBar = document.getElementById('loaderProgressBar');
            if (statusText && message) statusText.textContent = message;
            if (progressBar) progressBar.style.width = '20%';
            if (loader) {
                loader.classList.add('active');
                loader.setAttribute('aria-hidden', 'false');
            }
            if (progressBar) {
                setTimeout(() => {
                    if (loader && loader.classList.contains('active')) {
                        progressBar.style.width = '75%';
                    }
                }, 100);
            }
        }

        function hidePageLoader() {
            const loader = document.getElementById('pageSwitchLoader');
            const progressBar = document.getElementById('loaderProgressBar');
            if (progressBar) progressBar.style.width = '100%';
            setTimeout(() => {
                if (loader) {
                    loader.classList.remove('active');
                    loader.setAttribute('aria-hidden', 'true');
                }
                if (progressBar) {
                    setTimeout(() => { progressBar.style.width = '0%'; }, 250);
                }
            }, 260);
        }

        function updatePageLocaleHrefs(newLocale) {
            if (!newLocale) return;
            const supported = ['en', 'zh', 'bm'];
            document.querySelectorAll('a[href]').forEach(link => {
                try {
                    const rawHref = link.getAttribute('href');
                    if (!rawHref || rawHref.startsWith('#') || rawHref.startsWith('javascript:') || rawHref.startsWith('mailto:') || rawHref.startsWith('tel:')) {
                        return;
                    }
                    const url = new URL(link.href, window.location.origin);
                    if (url.origin === window.location.origin) {
                        if (url.pathname.match(/^\/(admin|api|currency|language|newsletter)/)) {
                            return;
                        }
                        const parts = url.pathname.split('/');
                        if (parts.length > 1 && supported.includes(parts[1])) {
                            if (parts[1] !== newLocale) {
                                parts[1] = newLocale;
                                url.pathname = parts.join('/');
                                link.href = url.pathname + url.search + url.hash;
                            }
                        }
                    }
                } catch (e) {}
            });
        }

        async function selectLanguage(event, code, switchUrl, directTargetUrl = null, updateHistory = true) {
            if (event && typeof event.preventDefault === 'function') {
                event.preventDefault();
            }
            if (isLanguageSwitching) return;

            // 1. Immediately close language dropdown
            const langDropdown = document.getElementById('languageDropdown');
            const langBtn = document.getElementById('languageBtn');
            langDropdown?.classList.remove('open');
            langBtn?.classList.remove('active');

            const targetCode = (code || 'en').trim().toLowerCase();
            const currentCode = (document.getElementById('activeLanguageCode')?.textContent || '').trim().toUpperCase();
            const langLabels = { en: 'EN', zh: 'ZH', bm: 'BM' };
            const targetLabel = langLabels[targetCode] || targetCode.toUpperCase();

            // If already on this language and no forced URL, do nothing
            if (!directTargetUrl && currentCode === targetLabel) {
                return;
            }

            // Immediately set cookie so subsequent requests / navigations maintain chosen locale
            try {
                document.cookie = 'locale=' + encodeURIComponent(targetCode) + '; path=/; max-age=31536000; SameSite=Lax';
            } catch (e) {}

            // 2. Immediate visual update of circular button and dropdown checkmarks
            const activeCodeEl = document.getElementById('activeLanguageCode');
            if (activeCodeEl) activeCodeEl.textContent = targetLabel;

            document.querySelectorAll('#languageDropdown .currency-option').forEach(opt => {
                const isMatch = opt.getAttribute('data-code') === targetCode;
                opt.classList.toggle('active', isMatch);
                const check = opt.querySelector('.currency-option-check');
                if (check) check.style.display = isMatch ? 'inline-block' : 'none';
            });

            // 3. Show high-quality hero-themed page loader with localized message
            isLanguageSwitching = true;
            const startTime = Date.now();

            const switchMsgs = {
                zh: '🐟 正在切换语言至 简体中文 · 镁嘉水产冷链',
                bm: '🐟 Menukar bahasa ke Bahasa Melayu · Makanan Laut Beku MST',
                en: '🐟 Switching language to English · MST Frozen Seafood'
            };
            showPageLoader(switchMsgs[targetCode] || `Switching to ${targetLabel}...`);

            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.classList.add('lang-transitioning');
            }

            try {
                let targetUrl = directTargetUrl;

                if (!targetUrl) {
                    // Call backend language switcher endpoint with current URL for accurate redirection
                    const currentFullUrl = window.location.href;
                    const endpoint = (switchUrl || ('/language/' + encodeURIComponent(targetCode))) + 
                        (switchUrl && switchUrl.includes('?') ? '&' : '?') + 'current_url=' + encodeURIComponent(currentFullUrl);

                    const switchRes = await fetch(endpoint, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!switchRes.ok) throw new Error('Language switch endpoint failed');
                    const switchData = await switchRes.json();
                    targetUrl = switchData.redirect_url;
                }

                if (!targetUrl) throw new Error('No redirect URL resolved');

                // 4. Fetch the target page in the new language
                const pageRes = await fetch(targetUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!pageRes.ok) throw new Error('Failed to fetch translated page: ' + pageRes.status);
                const html = await pageRes.text();

                // 5. Parse the returned HTML document
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');

                // 6. Update document title & html lang attribute
                if (newDoc.title) {
                    document.title = newDoc.title;
                }
                document.documentElement.lang = targetCode;

                // 7. Update Navigation Logo (.nav-logo) so clicking it routes to new locale
                const currentNavLogo = document.querySelector('.nav-logo');
                const newNavLogo = newDoc.querySelector('.nav-logo');
                if (currentNavLogo && newNavLogo) {
                    currentNavLogo.href = newNavLogo.href;
                    currentNavLogo.innerHTML = newNavLogo.innerHTML;
                }

                // 8. Update Navigation Links & Mobile Drawer
                const currentNavLinks = document.getElementById('navLinks');
                const newNavLinks = newDoc.getElementById('navLinks');
                if (currentNavLinks && newNavLinks) {
                    currentNavLinks.innerHTML = newNavLinks.innerHTML;
                }

                // 9. Update Cart Button (#cartBtn)
                const currentCartBtn = document.getElementById('cartBtn');
                const newCartBtn = newDoc.getElementById('cartBtn');
                if (currentCartBtn && newCartBtn) {
                    currentCartBtn.href = newCartBtn.href;
                }

                // 10. Update Guest Auth Buttons if present
                const currentGhost = document.querySelector('.nav-actions .btn-ghost');
                const newGhost = newDoc.querySelector('.nav-actions .btn-ghost');
                if (currentGhost && newGhost) {
                    currentGhost.href = newGhost.href;
                    currentGhost.innerHTML = newGhost.innerHTML;
                }
                const currentPrimarySm = document.querySelector('.nav-actions .btn-primary-sm');
                const newPrimarySm = newDoc.querySelector('.nav-actions .btn-primary-sm');
                if (currentPrimarySm && newPrimarySm) {
                    currentPrimarySm.href = newPrimarySm.href;
                    currentPrimarySm.innerHTML = newPrimarySm.innerHTML;
                }

                // 11. Update Walkin Exit Button if present
                const currentWalkinExit = document.querySelector('.walkin-exit');
                const newWalkinExit = newDoc.querySelector('.walkin-exit');
                if (currentWalkinExit && newWalkinExit) {
                    currentWalkinExit.href = newWalkinExit.href;
                    currentWalkinExit.innerHTML = newWalkinExit.innerHTML;
                }

                // 12. Update User Menu / Auth buttons
                const currentUserMenu = document.getElementById('userMenu');
                const newUserMenu = newDoc.getElementById('userMenu');
                if (currentUserMenu && newUserMenu) {
                    currentUserMenu.innerHTML = newUserMenu.innerHTML;
                }

                // 13. Update Language Dropdown options for next switch
                const currentLangDropdown = document.getElementById('languageDropdown');
                const newLangDropdown = newDoc.getElementById('languageDropdown');
                if (currentLangDropdown && newLangDropdown) {
                    currentLangDropdown.innerHTML = newLangDropdown.innerHTML;
                }

                // 14. Update Main Content (.main-content)
                const newMain = newDoc.querySelector('.main-content');
                if (mainContent && newMain) {
                    mainContent.innerHTML = newMain.innerHTML;
                    mainContent.className = newMain.className;
                    mainContent.classList.add('lang-transitioning');
                    executeInlineScripts(mainContent);
                }

                // 15. Update Footer (.footer)
                const currentFooter = document.querySelector('.footer');
                const newFooter = newDoc.querySelector('.footer');
                if (currentFooter && newFooter) {
                    currentFooter.innerHTML = newFooter.innerHTML;
                }

                // 16. Rewrite all links across the page to ensure all links retain target language
                updatePageLocaleHrefs(targetCode);

                // 17. Update Flash Notifications if any
                const currentFlash = document.querySelector('.flash-container');
                const newFlash = newDoc.querySelector('.flash-container');
                if (currentFlash && newFlash) {
                    currentFlash.innerHTML = newFlash.innerHTML;
                } else if (!currentFlash && newFlash && newFlash.children.length > 0) {
                    document.body.insertBefore(newFlash, mainContent);
                }

                // 13. Update Browser URL in Address Bar (pushState)
                if (updateHistory) {
                    window.history.pushState({ locale: targetCode, url: targetUrl }, newDoc.title || '', targetUrl);
                }

                // 14. Re-run currency formatting on newly swapped elements
                if (window.AppCurrency && typeof updatePageCurrencies === 'function') {
                    updatePageCurrencies(window.AppCurrency.current || 'MYR');
                }

                // 15. Re-check Cart Count
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }

                // 16. Notify any listeners that language switched
                window.dispatchEvent(new CustomEvent('app:locale-changed', {
                    detail: { locale: targetCode, url: targetUrl }
                }));

            } catch (err) {
                console.warn('Seamless switch error, falling back to standard navigation:', err);
                window.location.href = switchUrl || ('/language/' + encodeURIComponent(targetCode));
                return;
            } finally {
                // Ensure the high-quality loader displays for at least 420ms for a smooth, cinematic feel
                const elapsed = Date.now() - startTime;
                const remaining = Math.max(0, 420 - elapsed);
                setTimeout(() => {
                    hidePageLoader();
                    if (mainContent) {
                        setTimeout(() => {
                            mainContent.classList.remove('lang-transitioning');
                        }, 50);
                    }
                    isLanguageSwitching = false;
                }, remaining);
            }
        }

        function executeInlineScripts(container) {
            if (!container) return;
            const scripts = container.querySelectorAll('script');
            scripts.forEach(oldScript => {
                if (oldScript.src) {
                    const alreadyLoaded = Array.from(document.scripts).some(s => s.src === oldScript.src);
                    if (alreadyLoaded) return;
                }
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                let code = oldScript.textContent;
                if (document.readyState !== 'loading' && code.includes('DOMContentLoaded')) {
                    code = code.replace(/document\.addEventListener\s*\(\s*['"]DOMContentLoaded['"]\s*,\s*(\([^)]*\)\s*=>|\bfunction\s*\([^)]*\))\s*\{/g, '(function() {');
                }
                newScript.textContent = code;
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });
        }

        window.addEventListener('popstate', (e) => {
            if (e.state && e.state.locale && e.state.url) {
                selectLanguage(null, e.state.locale, null, e.state.url, false);
            }
        });

        // Trigger page loader on internal link navigation
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (!link || !link.href) return;
            // Ignore new tabs, anchors, javascript, mailto, tel
            if (link.target === '_blank' || link.hasAttribute('download') || link.href.includes('#') || link.href.startsWith('javascript:') || link.href.startsWith('mailto:') || link.href.startsWith('tel:')) return;
            // Ignore language and currency dropdown items which have their own handlers
            if (link.closest('#languageDropdown') || link.closest('#currencyDropdown')) return;
            // Ignore modal triggers, accordions, or buttons disguised as links
            if (link.hasAttribute('onclick') || link.classList.contains('mobile-toggle')) return;
            if (e.ctrlKey || e.metaKey || e.shiftKey || e.defaultPrevented) return;

            // If same-origin link, display attractive page loader
            if (link.origin === window.location.origin) {
                const text = (link.textContent || '').trim();
                const hint = text && text.length < 24 ? `Loading ${text}...` : 'Loading page...';
                showPageLoader(hint);
            }
        });

        // Hide loader when navigating via browser back/forward cache
        window.addEventListener('pageshow', (e) => {
            hidePageLoader();
        });
        document.addEventListener('click', (e) => {
            const menu = document.getElementById('userMenu');
            if (menu && !menu.contains(e.target)) {
                document.getElementById('userDropdown')?.classList.remove('open');
            }
            const curMenu = document.getElementById('currencyMenu');
            if (curMenu && !curMenu.contains(e.target)) {
                document.getElementById('currencyDropdown')?.classList.remove('open');
                document.getElementById('currencyBtn')?.classList.remove('active');
            }
            const langMenu = document.getElementById('languageMenu');
            if (langMenu && !langMenu.contains(e.target)) {
                document.getElementById('languageDropdown')?.classList.remove('open');
                document.getElementById('languageBtn')?.classList.remove('active');
            }
            const navLinks = document.getElementById('navLinks');
            const mobileToggle = document.getElementById('mobileToggle');
            if (navLinks && navLinks.classList.contains('open')) {
                if (!navLinks.contains(e.target) && !mobileToggle.contains(e.target)) {
                    navLinks.classList.remove('open');
                    mobileToggle.classList.remove('active');
                    document.body.classList.remove('mobile-drawer-open');
                }
            }
        });
        async function updateCartCount() {
            try {
                const res = await fetch('{{ route("cart.count") }}');
                const data = await res.json();
                document.querySelectorAll('.cart-count').forEach(badge => {
                    badge.textContent = data.count;
                    badge.style.display = data.count > 0 ? (badge.classList.contains('mobile-cart-badge') ? 'inline-flex' : 'flex') : 'none';
                });
            } catch (e) { }
        }
        function showGlobalToast(message, type = 'success') {
            let container = document.querySelector('.flash-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'flash-container';
                document.body.appendChild(container);
            }
            const icon = type === 'success' ? '✓' : (type === 'error' ? '⚠' : 'ℹ');
            const flash = document.createElement('div');
            flash.className = `flash flash-${type}`;
            flash.innerHTML = `<span>${icon}</span> <span>${message}</span><button type="button" class="flash-close" onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;margin-left:auto;font-size:1.1rem;line-height:1">✕</button>`;
            container.appendChild(flash);
            setTimeout(() => {
                flash.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                flash.style.opacity = '0';
                flash.style.transform = 'translateX(24px)';
                setTimeout(() => flash.remove(), 350);
            }, 4000);
        }

        // Global AJAX Add to Cart (prevents full-page reload)
        document.addEventListener('submit', async function(e) {
            const form = e.target;
            if (!form || !form.action) return;
            const actionUrl = form.action;

            if (actionUrl.includes('/cart/add') || actionUrl.endsWith('/cart')) {
                // If Buy Now is flagged, allow standard redirection
                const buyNowVal = form.querySelector('input[name="buy_now"]')?.value;
                if (buyNowVal === '1' || buyNowVal === 'true') {
                    return;
                }
                if (form.getAttribute('data-no-ajax') === 'true') {
                    return;
                }

                e.preventDefault();
                const submitBtn = form.querySelector('button[type="submit"]') || form.querySelector('button');
                const origHtml = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                    submitBtn.innerHTML = '<span style="display:inline-flex;align-items:center;gap:6px"><svg style="animation:btnSpin 0.8s linear infinite;width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-dasharray="32" stroke-dashoffset="12"/></svg> Adding...</span>';
                }

                try {
                    const formData = new FormData(form);
                    const bodyObj = {};
                    formData.forEach((val, key) => bodyObj[key] = val);

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || form.querySelector('input[name="_token"]')?.value || '';

                    const res = await fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(bodyObj)
                    });

                    const data = await res.json();
                    if (data.success) {
                        if (submitBtn) {
                            submitBtn.classList.remove('loading');
                            submitBtn.classList.add('btn-added');
                            submitBtn.innerHTML = '✓ Added!';
                            setTimeout(() => {
                                submitBtn.classList.remove('btn-added');
                                submitBtn.innerHTML = origHtml;
                                submitBtn.disabled = false;
                            }, 1800);
                        }
                        if (typeof updateCartCount === 'function') updateCartCount();
                        showGlobalToast(data.message || 'Product added to cart!', 'success');
                    } else {
                        showGlobalToast(data.message || 'Could not add to cart.', 'error');
                        if (submitBtn) {
                            submitBtn.classList.remove('loading');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = origHtml;
                        }
                    }
                } catch (err) {
                    console.error('Add to cart error:', err);
                    showGlobalToast('Error adding product to cart.', 'error');
                    if (submitBtn) {
                        submitBtn.classList.remove('loading');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = origHtml;
                    }
                }
            }
        });

        setTimeout(() => {
            document.querySelectorAll('.flash').forEach(f => {
                f.style.opacity = '0';
                setTimeout(() => f.remove(), 400);
            });
        }, 5000);
        updateCartCount();
    </script>
    @if(!empty($settings['footer_tags']))
        <!-- Custom Footer Tags -->
        {!! $settings['footer_tags'] !!}
    @endif

    @if(auth()->check() && !auth()->user()->isAdmin() && !request()->routeIs('approval.*'))
    <script>
    (function() {
        var approvalCheckTimer = setInterval(function() {
            fetch('{{ route("approval.check_status") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (!data) return;
                if (data.rejected) {
                    clearInterval(approvalCheckTimer);
                    window.location.href = '{{ route("approval.rejected") }}';
                } else if (data.pending) {
                    clearInterval(approvalCheckTimer);
                    window.location.href = '{{ route("approval.pending") }}';
                }
            })
            .catch(function() {});
        }, 4000);
    })();
    </script>
    @endif

    @include('partials.cookie-banner')

    @stack('scripts')
</body>

</html>