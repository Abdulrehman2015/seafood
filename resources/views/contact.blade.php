@extends('layouts.app')
@section('title', 'Contact Us — MST Import and Export Sdn Bhd')
@section('meta_description', 'Get in touch with MST Import and Export Sdn Bhd. Reach out for retail inquiries, wholesale seafood orders, or customer support across Malaysia and Singapore.')

@section('content')
<!-- Local Leaflet CSS (Same-Origin for strict CSP & ad-blocker compliance) -->
<link rel="stylesheet" href="{{ asset('css/leaflet.css') }}" />

<style>
    .contact-page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 48px 20px 80px;
    }
    
    /* 1. Top 4 Cards Grid */
    .contact-top-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 50px;
    }
    .contact-top-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 32px 22px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .contact-top-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.08);
        border-color: #bfdbfe;
    }
    .contact-icon-bubble {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        transition: all 0.2s ease;
    }
    .contact-top-card:hover .contact-icon-bubble {
        background: #2563eb;
        color: #ffffff;
    }
    .contact-card-title {
        font-size: 1.12rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 8px;
    }
    .contact-card-main-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
    }
    .contact-card-main-link {
        font-size: 1rem;
        font-weight: 800;
        color: #2563eb;
        margin-bottom: 4px;
        text-decoration: none;
        display: inline-block;
    }
    .contact-card-sub {
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.45;
    }

    /* 2. Main Consultation Split Section */
    .consultation-split-wrapper {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
        border: 1px solid #e2e8f0;
        background: #ffffff;
        display: grid;
        grid-template-columns: 1fr 1.35fr;
        margin-bottom: 90px;
    }
    .consultation-blue-panel {
        background: linear-gradient(150deg, #1d4ed8 0%, #2563eb 55%, #3b82f6 100%);
        padding: 48px 42px;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }
    .consultation-blue-panel::after {
        content: '';
        position: absolute;
        bottom: -50px;
        right: -50px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.16) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .consultation-white-panel {
        padding: 48px 46px;
        background: #ffffff;
    }
    .consultation-step-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }
    .step-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #ffffff;
        color: #1d4ed8;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Form Elements */
    .consultation-form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 18px;
    }
    .form-group-custom {
        margin-bottom: 18px;
    }
    .form-label-custom {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }
    .form-control-custom {
        width: 100%;
        height: 44px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0 14px;
        font-size: 0.92rem;
        color: #0f172a;
        background: #ffffff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control-custom:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    textarea.form-control-custom {
        height: auto;
        padding: 12px 14px;
        resize: vertical;
    }

    /* ─── Custom Searchable Select Styles ─── */
    .searchable-select-container {
        position: relative;
        width: 100%;
    }
    .searchable-trigger {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
        font-size: 0.92rem;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        user-select: none;
    }
    .searchable-trigger:hover {
        border-color: #93c5fd;
        background: #f8fafc;
    }
    .searchable-select-container.open .searchable-trigger {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        background: #ffffff;
    }
    .searchable-select-container.disabled .searchable-trigger {
        background: #f1f5f9;
        border-color: #e2e8f0;
        color: #94a3b8;
        cursor: not-allowed;
    }
    .searchable-selected-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 8px;
    }
    .searchable-selected-text.placeholder {
        color: #94a3b8;
    }
    .searchable-arrow {
        color: #64748b;
        display: flex;
        align-items: center;
        transition: transform 0.2s ease;
        flex-shrink: 0;
    }
    .searchable-select-container.open .searchable-arrow {
        transform: rotate(180deg);
        color: #2563eb;
    }
    .searchable-dropdown-panel {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.14), 0 4px 12px rgba(15, 23, 42, 0.08);
        padding: 8px;
        z-index: 1000;
        display: none;
        animation: fadeInSelect 0.15s ease-out;
    }
    @keyframes fadeInSelect {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .searchable-select-container.open .searchable-dropdown-panel {
        display: block;
    }
    .searchable-search-wrapper {
        position: relative;
        margin-bottom: 8px;
    }
    .searchable-search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
        display: flex;
        align-items: center;
    }
    .searchable-search-input {
        width: 100%;
        height: 36px;
        padding: 0 10px 0 32px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background: #f8fafc;
        font-size: 0.85rem;
        color: #0f172a;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .searchable-search-input:focus {
        background: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
    }
    .searchable-options-list {
        max-height: 220px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 2px;
        scrollbar-width: thin;
    }
    .searchable-option-item {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.88rem;
        color: #1e293b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.15s ease;
    }
    .searchable-option-item:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }
    .searchable-option-item.selected {
        background: #dbeafe;
        color: #1e40af;
        font-weight: 700;
    }
    .searchable-option-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
        background: #e2e8f0;
        color: #475569;
    }
    .searchable-option-item.selected .searchable-option-badge {
        background: #bfdbfe;
        color: #1e40af;
    }
    .searchable-no-results {
        padding: 14px 10px;
        text-align: center;
        font-size: 0.84rem;
        color: #94a3b8;
    }
    .searchable-check-icon {
        display: none;
        color: #2563eb;
        margin-left: 6px;
        flex-shrink: 0;
    }
    .searchable-option-item.selected .searchable-check-icon {
        display: inline-flex;
    }
    .location-split-grid {
        display: grid;
        grid-template-columns: 1fr 1.35fr;
        gap: 26px;
        align-items: stretch;
        margin-bottom: 70px;
    }
    .office-info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 34px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .map-embed-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        background: #f8fafc;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        min-height: 520px;
        height: 100%;
    }
    #contactMap {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        min-height: 520px;
        z-index: 1;
    }
    .map-overlay-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.14);
        padding: 14px 18px;
        z-index: 500;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    /* Responsive Queries */
    @media (max-width: 1024px) {
        .contact-page-container {
            display: flex;
            flex-direction: column;
            padding-top: 36px;
            padding-bottom: 60px;
        }
        /* Mobile: Form section will be on TOP after 1st section (Hero) */
        .consultation-split-wrapper {
            order: 1;
            display: flex;
            flex-direction: column;
            margin-bottom: 48px;
            border-radius: 20px;
        }
        .consultation-white-panel {
            order: 1; /* Form inputs appear at the top */
            padding: 36px 28px;
        }
        .consultation-blue-panel {
            order: 2; /* Informational steps appear below the form */
            padding: 36px 28px;
        }
        /* 4 Contact cards appear after the consultation section on mobile */
        .contact-top-cards {
            order: 2;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-bottom: 50px;
        }
        /* Location & Map section appears after contact cards */
        .contact-location-section {
            order: 3;
        }
        .location-split-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }
    }

    @media (max-width: 640px) {
        .page-header {
            padding-top: calc(70px + 20px) !important;
            padding-bottom: 24px !important;
        }
        .page-title {
            font-size: 1.75rem !important;
            line-height: 1.25 !important;
            margin-bottom: 8px !important;
        }
        .page-subtitle {
            font-size: 0.92rem !important;
            line-height: 1.5 !important;
        }
        .contact-page-container {
            padding-left: 14px;
            padding-right: 14px;
            padding-top: 20px;
            padding-bottom: 50px;
        }
        .consultation-split-wrapper {
            margin-bottom: 36px;
            border-radius: 18px;
        }
        .consultation-white-panel {
            padding: 24px 16px;
        }
        .consultation-white-panel h3 {
            font-size: 1.75rem !important;
        }
        .consultation-blue-panel {
            padding: 28px 18px;
        }
        .consultation-blue-panel h2 {
            font-size: 1.65rem !important;
            margin-bottom: 22px !important;
        }
        .consultation-step-item {
            gap: 12px;
        }
        .step-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 10px;
        }
        .consultation-form-grid-2 {
            grid-template-columns: 1fr;
            gap: 14px;
            margin-bottom: 14px;
        }
        .form-group-custom {
            margin-bottom: 14px;
        }
        .form-control-custom, .searchable-trigger {
            font-size: 16px; /* Prevents auto-zoom on iOS */
            height: 46px;
        }
        .searchable-search-input {
            font-size: 16px;
            height: 40px;
        }
        .searchable-dropdown-panel {
            max-height: 260px;
            border-radius: 10px;
        }
        .contact-top-cards {
            grid-template-columns: 1fr;
            gap: 14px;
            margin-bottom: 36px;
        }
        .contact-top-card {
            padding: 24px 18px;
            border-radius: 16px;
        }
        .contact-location-heading h2 {
            font-size: 1.75rem !important;
        }
        .contact-location-heading p {
            font-size: 0.9rem !important;
        }
        .office-info-card {
            padding: 24px 18px;
            border-radius: 16px;
        }
        .map-embed-wrapper {
            min-height: 380px;
            height: 380px;
            border-radius: 16px;
        }
        #contactMap {
            min-height: 380px;
            height: 380px;
        }
        .map-overlay-badge {
            top: 10px;
            left: 10px;
            right: 10px;
            max-width: calc(100% - 20px);
            padding: 10px 14px;
            border-radius: 10px;
        }
        .map-overlay-badge div:first-child {
            font-size: 0.92rem !important;
        }
        .map-overlay-badge div:nth-child(2) {
            font-size: 0.75rem !important;
            margin-bottom: 6px !important;
        }
    }
</style>

<!-- ─── Page Header / Hero Section ──────────────────────────────────────── -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 Home</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">Contact Us</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:3px 10px;border-radius:999px;font-size:0.72rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        📍 Direct Customer Support
                    </span>
                    <span style="color:#bae6fd;font-size:0.8rem">Iskandar Puteri, Johor Bahru · Malaysia &amp; Singapore</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.4rem);margin-bottom:6px;letter-spacing:-0.02em">
                    Contact MST Import and Export Sdn Bhd
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:680px;line-height:1.5;margin:0">
                    Have questions about our ocean catches, wholesale pallet distribution, or refrigerated logistics across Malaysia and Singapore? Reach out to our dedicated team and we'll assist you promptly.
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div style="font-size:0.85rem;padding:6px 14px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb">
                    📞 Quick Response Assured
                </div>
            </div>
        </div>
    </div>
</div>


<div class="contact-page-container">

    <!-- 1. Top 4 Contact Info Cards (Direct from Admin -> Settings -> Contact Page) -->
    <div class="contact-top-cards">
        
        <!-- Card 1: Our Store & Office -->
        <div class="contact-top-card">
            <div class="contact-icon-bubble">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </div>
            <h3 class="contact-card-title">Store & Office</h3>
            <div class="contact-card-main-title">
                {{ $settings['store_name'] ?? 'Mika Import and Export SDN Bhd' }}
            </div>
            <div class="contact-card-sub">
                {{ $settings['store_address'] ?? '7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia' }}
            </div>
            @if(!empty($settings['store_map_url']))
                <a href="{{ $settings['store_map_url'] }}" target="_blank" rel="noopener" 
                   style="margin-top:8px;font-size:0.78rem;font-weight:700;color:#2563eb;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                    <span>View on Google Maps</span>
                    <span>&rarr;</span>
                </a>
            @endif
        </div>

        <!-- Card 2: Direct Call -->
        <div class="contact-top-card">
            <div class="contact-icon-bubble">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
            </div>
            <h3 class="contact-card-title">Direct Call</h3>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['store_phone'] ?? '013-2800168') }}" class="contact-card-main-link">
                {{ $settings['store_phone'] ?? '013-2800168' }}
            </a>
            <div class="contact-card-sub">
                @if(!empty($settings['store_phone_2']))
                    <div style="color:#1d4ed8;font-weight:600;font-size:0.8rem">Alt: {{ $settings['store_phone_2'] }}</div>
                @endif
                @if(!empty($settings['store_phone_3']))
                    <div style="color:#1d4ed8;font-weight:600;font-size:0.8rem">Alt: {{ $settings['store_phone_3'] }}</div>
                @endif
                <div style="margin-top:2px">{{ $settings['store_hours'] ?? 'Mon – Sat: 8:00 AM – 6:00 PM' }}</div>
            </div>
        </div>

        <!-- Card 3: Email Us -->
        <div class="contact-top-card">
            <div class="contact-icon-bubble">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
            <h3 class="contact-card-title">Email Us</h3>
            <a href="mailto:{{ $settings['store_email'] ?? 'mikatrading15@gmail.com' }}" class="contact-card-main-link" style="word-break:break-all">
                {{ $settings['store_email'] ?? 'mikatrading15@gmail.com' }}
            </a>
            <div class="contact-card-sub">
                @if(!empty($settings['store_wholesale_email']))
                    <div style="color:#1d4ed8;font-weight:600;font-size:0.8rem">B2B: {{ $settings['store_wholesale_email'] }}</div>
                @endif
                <div>Guaranteed Response Within 24 Hours</div>
            </div>
        </div>

        <!-- Card 4: WhatsApp Support -->
        <div class="contact-top-card">
            <div class="contact-icon-bubble">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                </svg>
            </div>
            <h3 class="contact-card-title">WhatsApp Support</h3>
            @php
                $waUrl = $settings['social_whatsapp'] ?? 'https://wa.me/601112710260';
                preg_match('/(\d{7,})/', $waUrl, $waMatches);
                $waDigits = $waMatches[1] ?? '601112710260';
            @endphp
            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="contact-card-main-link" style="word-break:break-all">
                +{{ substr($waDigits, 0, 2) }} {{ substr($waDigits, 2, 3) }}-{{ substr($waDigits, 5) }}
            </a>
            <div class="contact-card-sub">
                Fast Chat & Inquiries<br>
                Live Support Online
            </div>
        </div>

    </div>

    <!-- 2. Main Consultation Split Card -->
    <div class="consultation-split-wrapper">
        
        <!-- Left Panel: Deep Blue with Feature Highlights -->
        <div class="consultation-blue-panel">
            <div>
                <!-- Badge -->
                <div style="margin-bottom:18px">
                    <span style="background:#ffffff;color:#1d4ed8;font-size:0.75rem;font-weight:800;letter-spacing:0.8px;padding:6px 16px;border-radius:30px;display:inline-block;text-transform:uppercase;box-shadow:0 2px 8px rgba(0,0,0,0.1)">
                        SEAFOOD INQUIRY &amp; RFQ
                    </span>
                </div>

                <!-- Title -->
                <h2 style="color:#ffffff;font-size:2.3rem;font-weight:800;line-height:1.2;margin:0 0 34px;letter-spacing:-0.5px">
                    Let's Discuss<br>Your Requirements
                </h2>

                <!-- Feature Steps -->
                <div style="display:flex;flex-direction:column;gap:24px;margin-bottom:36px">
                    
                    <!-- Step 1 -->
                    <div class="consultation-step-item">
                        <div class="step-icon-box">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.95rem;color:#ffffff;margin-bottom:2px">Select Category &amp; Product</div>
                            <div style="font-size:0.82rem;color:#bfdbfe;line-height:1.4">Browse our premium ocean catches and choose what you need.</div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="consultation-step-item">
                        <div class="step-icon-box">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.95rem;color:#ffffff;margin-bottom:2px">Specify Volume or Schedule</div>
                            <div style="font-size:0.82rem;color:#bfdbfe;line-height:1.4">Retail packs, restaurant supply, or container bulk trading.</div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="consultation-step-item">
                        <div class="step-icon-box">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.95rem;color:#ffffff;margin-bottom:2px">Direct Tier Pricing</div>
                            <div style="font-size:0.82rem;color:#bfdbfe;line-height:1.4">We respond within 24 hours with competitive, transparent pricing.</div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="consultation-step-item">
                        <div class="step-icon-box">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.95rem;color:#ffffff;margin-bottom:2px">Cold Chain Logistics</div>
                            <div style="font-size:0.82rem;color:#bfdbfe;line-height:1.4">Doorstep delivery at -18&deg;C or instant store self-collection pass.</div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Shield Trust Box -->
            <div style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.25);backdrop-filter:blur(8px);border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:12px">
                <div style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;color:#ffffff;flex-shrink:0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <div style="font-size:0.83rem;color:#ffffff;line-height:1.4">
                    <strong>Halal &amp; HACCP Sourced:</strong> Quality inspected with unbroken cold chain assurance.
                </div>
            </div>

        </div>

        <!-- Right Panel: White Card Form -->
        <div class="consultation-white-panel">
            
            <div style="font-size:0.75rem;font-weight:800;letter-spacing:1.2px;color:#2563eb;text-transform:uppercase;margin-bottom:6px">
                CUSTOMER INQUIRY &amp; QUOTE
            </div>
            
            <h3 style="font-size:2.2rem;font-weight:800;color:#0f172a;margin:0 0 6px;letter-spacing:-0.5px">
                Product Inquiry
            </h3>

            <p style="font-size:0.92rem;color:#64748b;margin:0 0 28px;line-height:1.5">
                Tell us about your seafood requirements. Our team will get back to you with pricing and details within 24 hours.
            </p>

            @if(session('success'))
                <div style="background:#dcfce7;border:1px solid #86efac;color:#166534;padding:14px 18px;border-radius:10px;font-size:0.9rem;margin-bottom:22px;display:flex;align-items:center;gap:10px">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.submit') }}">
                @csrf

                <!-- Row 1: Full Name & Email -->
                <div class="consultation-form-grid-2">
                    <div>
                        <label class="form-label-custom">
                            Full name <span style="color:#ef4444">*</span>
                        </label>
                        <input type="text" name="name" class="form-control-custom" 
                               value="{{ old('name', auth()->user()?->name) }}" required 
                               placeholder="Your full name">
                        @error('name')<div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="form-label-custom">
                            Email address <span style="color:#ef4444">*</span>
                        </label>
                        <input type="email" name="email" class="form-control-custom" 
                               value="{{ old('email', auth()->user()?->email) }}" required 
                               placeholder="you@example.com">
                        @error('email')<div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Row 2: Phone & I'm interested in (All Categories from Admin Panel) -->
                <div class="consultation-form-grid-2">
                    <div>
                        <label class="form-label-custom">
                            Phone number <span style="font-weight:400;color:#94a3b8">(optional)</span>
                        </label>
                        <input type="tel" name="phone" class="form-control-custom" 
                               value="{{ old('phone', auth()->user()?->phone) }}" 
                               placeholder="+60 12-345 6789">
                        @error('phone')<div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="form-label-custom">
                            I'm interested in <span style="color:#ef4444">*</span>
                        </label>
                        <!-- Custom Searchable Category Dropdown -->
                        <div class="searchable-select-container" id="categorySelectContainer">
                            <input type="hidden" name="subject" id="categoryHiddenInput" value="{{ old('subject') }}" required>
                            <div class="searchable-trigger" id="categoryTrigger" tabindex="0" role="combobox" aria-haspopup="listbox" aria-expanded="false">
                                <span class="searchable-selected-text {{ old('subject') ? '' : 'placeholder' }}" id="categoryTriggerText">
                                    {{ old('subject') ? old('subject') : 'Select a category...' }}
                                </span>
                                <span class="searchable-arrow">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </div>
                            <div class="searchable-dropdown-panel" id="categoryDropdownPanel">
                                <div class="searchable-search-wrapper">
                                    <span class="searchable-search-icon">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                    </span>
                                    <input type="text" class="searchable-search-input" id="categorySearchInput" placeholder="Search categories..." autocomplete="off">
                                </div>
                                <div class="searchable-options-list" id="categoryOptionsList" role="listbox">
                                    @if(isset($categories) && $categories->count())
                                        @foreach($categories as $cat)
                                            <div class="searchable-option-item {{ old('subject') == $cat->name ? 'selected' : '' }}" 
                                                 data-value="{{ $cat->name }}" 
                                                 data-label="{{ $cat->name }}">
                                                <div style="display:flex;align-items:center;gap:6px">
                                                    <span>{{ $cat->name }}</span>
                                                    <span class="searchable-check-icon">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    </span>
                                                </div>
                                                <span class="searchable-option-badge">{{ $cat->products->count() }} {{ Str::plural('item', $cat->products->count()) }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="searchable-option-item {{ old('subject') == 'General Wholesale & Custom RFQ' ? 'selected' : '' }}" 
                                         data-value="General Wholesale & Custom RFQ" 
                                         data-label="General Wholesale & Custom RFQ">
                                        <div style="display:flex;align-items:center;gap:6px">
                                            <span>General Wholesale &amp; Custom RFQ</span>
                                            <span class="searchable-check-icon">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="searchable-option-item {{ old('subject') == 'General Business Inquiry' ? 'selected' : '' }}" 
                                         data-value="General Business Inquiry" 
                                         data-label="General Business Inquiry">
                                        <div style="display:flex;align-items:center;gap:6px">
                                            <span>General Business Inquiry</span>
                                            <span class="searchable-check-icon">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="searchable-no-results" id="categoryNoResults" style="display:none">
                                        No matching category found
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('subject')<div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Row 3: Product of selected category -->
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        Product <span style="font-weight:400;color:#94a3b8">(optional &mdash; select category first)</span>
                    </label>
                    <!-- Custom Searchable Product Dropdown -->
                    <div class="searchable-select-container disabled" id="productSelectContainer">
                        <input type="hidden" name="product" id="productHiddenInput" value="{{ old('product') }}">
                        <div class="searchable-trigger" id="productTrigger" tabindex="0" role="combobox" aria-haspopup="listbox" aria-expanded="false">
                            <span class="searchable-selected-text {{ old('product') ? '' : 'placeholder' }}" id="productTriggerText">
                                {{ old('product') ? old('product') : 'Select a category first...' }}
                            </span>
                            <span class="searchable-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </span>
                        </div>
                        <div class="searchable-dropdown-panel" id="productDropdownPanel">
                            <div class="searchable-search-wrapper">
                                <span class="searchable-search-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </span>
                                <input type="text" class="searchable-search-input" id="productSearchInput" placeholder="Search products in category..." autocomplete="off">
                            </div>
                            <div class="searchable-options-list" id="productOptionsList" role="listbox">
                                <div class="searchable-no-results" id="productNoResults" style="display:none">
                                    No matching product found
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('product')<div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                </div>

                <!-- Row 4: Description (formerly About your project) -->
                <div class="form-group-custom" style="margin-bottom:24px">
                    <label class="form-label-custom">
                        Description <span style="color:#ef4444">*</span>
                    </label>
                    <textarea name="message" class="form-control-custom" rows="4" required 
                              placeholder="Please describe your inquiry, volume requirements, delivery destination, or questions...">{{ old('message') }}</textarea>
                    @error('message')<div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        style="width:100%;height:50px;background:#1d4ed8;color:#ffffff;font-weight:700;font-size:1.02rem;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:background 0.2s, transform 0.1s"
                        onmouseover="this.style.background='#1e40af'" 
                        onmouseout="this.style.background='#1d4ed8'">
                    <span>Send Message</span>
                    <span style="font-size:1.15rem">&rarr;</span>
                </button>

                <p style="text-align:center;font-size:0.8rem;color:#64748b;margin:14px 0 0">
                    Our team will review your inquiry and get in touch within 24 hours.
                </p>
            </form>

        </div>

    </div>

    <!-- 3. Bottom Office & Interactive Location Map Section (From Admin -> Settings -> Contact Page) -->
    <div class="contact-location-section">
        <div class="contact-location-heading" style="text-align:center;margin-bottom:36px">
        <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:0.75rem;font-weight:800;letter-spacing:0.8px;padding:6px 16px;border-radius:30px;display:inline-flex;align-items:center;gap:6px;text-transform:uppercase;margin-bottom:12px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
            STORE &amp; COLD STORAGE LOCATION
        </span>

        <h2 style="font-size:2.4rem;font-weight:800;color:#0f172a;margin:0 0 10px;letter-spacing:-0.5px">
            Visit Our Store &amp; Cold Hub
        </h2>

        <p style="font-size:0.95rem;color:#64748b;max-width:650px;margin:0 auto;line-height:1.5">
            Drop by our central distribution facility or contact our sales team for walk-in wholesale collection.
        </p>
    </div>

    <div class="location-split-grid">
        
        <!-- Left Column: Detailed Office Card (Dynamically configured from Admin) -->
        <div class="office-info-card">
            <div>
                <!-- Card Header -->
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:22px">
                    <div style="width:50px;height:50px;border-radius:14px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                            <line x1="9" y1="22" x2="9" y2="22.01"></line>
                            <line x1="15" y1="22" x2="15" y2="22.01"></line>
                            <line x1="9" y1="6" x2="9" y2="6.01"></line>
                            <line x1="15" y1="6" x2="15" y2="6.01"></line>
                            <line x1="9" y1="10" x2="9" y2="10.01"></line>
                            <line x1="15" y1="10" x2="15" y2="10.01"></line>
                            <line x1="9" y1="14" x2="9" y2="14.01"></line>
                            <line x1="15" y1="14" x2="15" y2="14.01"></line>
                            <line x1="9" y1="18" x2="9" y2="18.01"></line>
                            <line x1="15" y1="18" x2="15" y2="18.01"></line>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:1.2rem;font-weight:800;color:#0f172a;line-height:1.2;margin-bottom:4px">
                            {{ $settings['store_name'] ?? 'Mika Import and Export SDN Bhd' }}
                        </div>
                        <div style="color:#2563eb;font-weight:600;font-size:0.85rem">
                            {{ $settings['store_tagline'] ?? 'Central Cold Storage & Distribution Facility' }}
                        </div>
                    </div>
                </div>

                <div style="border-top:1px solid #f1f5f9;margin-bottom:22px"></div>

                <!-- 4 Contact Points -->
                <div style="display:flex;flex-direction:column;gap:20px">
                    
                    <!-- 1. Office Address -->
                    <div style="display:flex;align-items:flex-start;gap:14px">
                        <div style="color:#2563eb;margin-top:2px">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.9rem;color:#0f172a;margin-bottom:2px">Store Address</div>
                            <div style="font-size:0.85rem;color:#64748b;line-height:1.4">
                                {{ $settings['store_address'] ?? '7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia' }}
                            </div>
                        </div>
                    </div>

                    <!-- 2. Working Hours -->
                    <div style="display:flex;align-items:flex-start;gap:14px">
                        <div style="color:#2563eb;margin-top:2px">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.9rem;color:#0f172a;margin-bottom:2px">Working Hours</div>
                            <div style="font-size:0.85rem;color:#64748b;line-height:1.4">
                                {{ $settings['store_hours'] ?? 'Mon – Sat: 8:00 AM – 6:00 PM' }}
                            </div>
                        </div>
                    </div>

                    <!-- 3. Direct Email -->
                    <div style="display:flex;align-items:flex-start;gap:14px">
                        <div style="color:#2563eb;margin-top:2px">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.9rem;color:#0f172a;margin-bottom:2px">Email Contacts</div>
                            <div style="font-size:0.85rem">
                                <a href="mailto:{{ $settings['store_email'] ?? 'mikatrading15@gmail.com' }}" style="color:#2563eb;text-decoration:none;word-break:break-all;font-weight:600">
                                    {{ $settings['store_email'] ?? 'mikatrading15@gmail.com' }}
                                </a>
                                @if(!empty($settings['store_wholesale_email']))
                                    <div style="margin-top:4px">
                                        <a href="mailto:{{ $settings['store_wholesale_email'] }}" style="color:#2563eb;text-decoration:none;font-weight:600">
                                            {{ $settings['store_wholesale_email'] }} (Wholesale)
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 4. Phone Support -->
                    <div style="display:flex;align-items:flex-start;gap:14px">
                        <div style="color:#2563eb;margin-top:2px">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.9rem;color:#0f172a;margin-bottom:2px">Phone Hotlines</div>
                            <div style="font-size:0.85rem">
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['store_phone'] ?? '013-2800168') }}" style="color:#2563eb;font-weight:700;text-decoration:none">
                                    {{ $settings['store_phone'] ?? '013-2800168' }}
                                </a>
                                @if(!empty($settings['store_phone_2']))
                                    <div style="margin-top:2px">
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['store_phone_2']) }}" style="color:#2563eb;font-weight:600;text-decoration:none">
                                            {{ $settings['store_phone_2'] }}
                                        </a>
                                    </div>
                                @endif
                                @if(!empty($settings['store_phone_3']))
                                    <div style="margin-top:2px">
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['store_phone_3']) }}" style="color:#2563eb;font-weight:600;text-decoration:none">
                                            {{ $settings['store_phone_3'] }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- In-Store Collection Pass Badge -->
            <div style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;padding:12px 16px;border-radius:10px;font-size:0.84rem;font-weight:600;display:flex;align-items:center;gap:10px;margin-top:24px">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>Walk-in showroom collection ready &middot; QR Instant Collection Tokens</span>
            </div>
        </div>

        <!-- Right Column: Interactive Map Container -->
        <div class="map-embed-wrapper">
            
            <!-- Map Floating Card -->
            <div class="map-overlay-badge">
                <div style="font-weight:800;font-size:1.05rem;color:#0f172a">{{ $settings['store_name'] ?? 'Mika Import and Export SDN Bhd' }}</div>
                <div style="font-size:0.8rem;color:#64748b;margin-bottom:10px">{{ Str::limit($settings['store_address'] ?? '7, Jalan SILC 2/18, Kawasan Perindustrian SILC, Johor', 48) }}</div>
                <div style="display:flex;gap:8px">
                    <a href="{{ $settings['store_map_url'] ?? 'https://maps.app.goo.gl/jLMaDYCNJ6vfk376A' }}" target="_blank" rel="noopener"
                       style="display:inline-flex;align-items:center;gap:6px;background:#2563eb;color:#ffffff;padding:6px 12px;border-radius:6px;font-size:0.78rem;font-weight:700;text-decoration:none;box-shadow:0 2px 8px rgba(37,99,235,0.3)">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                        <span>Open in Google Maps</span>
                    </a>
                </div>
            </div>

            <!-- Interactive Map Container -->
            <div id="contactMap"></div>
        </div>

    </div>
    </div>

</div>

<!-- Local Leaflet JS Library (Same-origin to comply with CSP script-src 'self') -->
<script src="{{ asset('js/leaflet.js') }}"></script>

<!-- Dynamic Searchable Dropdown & Category-Product Script -->
<script>
    // Mapping of category names to products for dynamic dropdown
    const categoryProductsMap = {
        @if(isset($categories))
            @foreach($categories as $cat)
                "{{ addslashes($cat->name) }}": [
                    @foreach($cat->products as $prod)
                        { id: {{ $prod->id }}, name: "{{ addslashes($prod->name) }}" },
                    @endforeach
                ],
            @endforeach
        @endif
    };

    document.addEventListener("DOMContentLoaded", function() {
        // Elements - Category
        const catContainer = document.getElementById('categorySelectContainer');
        const catTrigger = document.getElementById('categoryTrigger');
        const catTriggerText = document.getElementById('categoryTriggerText');
        const catSearchInput = document.getElementById('categorySearchInput');
        const catOptionsList = document.getElementById('categoryOptionsList');
        const catHiddenInput = document.getElementById('categoryHiddenInput');
        const catNoResults = document.getElementById('categoryNoResults');

        // Elements - Product
        const prodContainer = document.getElementById('productSelectContainer');
        const prodTrigger = document.getElementById('productTrigger');
        const prodTriggerText = document.getElementById('productTriggerText');
        const prodSearchInput = document.getElementById('productSearchInput');
        const prodOptionsList = document.getElementById('productOptionsList');
        const prodHiddenInput = document.getElementById('productHiddenInput');
        const prodNoResults = document.getElementById('productNoResults');

        // Close all dropdowns
        function closeAllDropdowns() {
            if (catContainer) catContainer.classList.remove('open');
            if (prodContainer) prodContainer.classList.remove('open');
        }

        // --- Category Dropdown Logic ---
        if (catTrigger) {
            catTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const wasOpen = catContainer.classList.contains('open');
                closeAllDropdowns();
                if (!wasOpen) {
                    catContainer.classList.add('open');
                    catSearchInput.value = '';
                    filterOptions(catOptionsList, '', catNoResults);
                    setTimeout(() => catSearchInput.focus(), 50);
                }
            });
        }

        if (catSearchInput) {
            catSearchInput.addEventListener('input', function() {
                filterOptions(catOptionsList, this.value.trim().toLowerCase(), catNoResults);
            });
            catSearchInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Attach click handlers to initial category options
        bindCategoryOptionClicks();

        function bindCategoryOptionClicks() {
            const items = catOptionsList.querySelectorAll('.searchable-option-item');
            items.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const val = this.getAttribute('data-value');
                    const label = this.getAttribute('data-label');
                    selectCategory(val, label);
                    closeAllDropdowns();
                });
            });
        }

        function selectCategory(value, label) {
            catHiddenInput.value = value;
            catTriggerText.textContent = label;
            catTriggerText.classList.remove('placeholder');

            // Mark selected
            catOptionsList.querySelectorAll('.searchable-option-item').forEach(el => {
                if (el.getAttribute('data-value') === value) {
                    el.classList.add('selected');
                } else {
                    el.classList.remove('selected');
                }
            });

            // Rebuild Product Dropdown for this category
            populateProductDropdown(value);
        }

        // --- Product Dropdown Logic ---
        if (prodTrigger) {
            prodTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                if (prodContainer.classList.contains('disabled')) return;
                const wasOpen = prodContainer.classList.contains('open');
                closeAllDropdowns();
                if (!wasOpen) {
                    prodContainer.classList.add('open');
                    prodSearchInput.value = '';
                    filterOptions(prodOptionsList, '', prodNoResults);
                    setTimeout(() => prodSearchInput.focus(), 50);
                }
            });
        }

        if (prodSearchInput) {
            prodSearchInput.addEventListener('input', function() {
                filterOptions(prodOptionsList, this.value.trim().toLowerCase(), prodNoResults);
            });
            prodSearchInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        function selectProduct(value, label) {
            prodHiddenInput.value = value;
            prodTriggerText.textContent = label;
            if (value) {
                prodTriggerText.classList.remove('placeholder');
            } else {
                prodTriggerText.classList.add('placeholder');
            }

            prodOptionsList.querySelectorAll('.searchable-option-item').forEach(el => {
                if (el.getAttribute('data-value') === value) {
                    el.classList.add('selected');
                } else {
                    el.classList.remove('selected');
                }
            });
        }

        function populateProductDropdown(categoryName, preselectedProduct = '') {
            if (!categoryName) {
                prodContainer.classList.add('disabled');
                prodTriggerText.textContent = 'Select a category first...';
                prodTriggerText.classList.add('placeholder');
                prodHiddenInput.value = '';
                prodOptionsList.innerHTML = '<div class="searchable-no-results" id="productNoResults" style="display:none">No matching product found</div>';
                return;
            }

            prodContainer.classList.remove('disabled');
            const products = categoryProductsMap[categoryName] || [];

            let html = '';
            
            // General / All option
            const defaultLabel = products.length > 0 
                ? 'All products in ' + categoryName + ' / General Inquiry' 
                : 'General Inquiry for ' + categoryName;
            
            const isAllSelected = !preselectedProduct || preselectedProduct === defaultLabel;

            html += `
                <div class="searchable-option-item ${isAllSelected ? 'selected' : ''}" data-value="" data-label="${escapeHtml(defaultLabel)}">
                    <div style="display:flex;align-items:center;gap:6px">
                        <span>${escapeHtml(defaultLabel)}</span>
                        <span class="searchable-check-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
                    </div>
                </div>
            `;

            products.forEach(p => {
                const isSelected = preselectedProduct && (preselectedProduct === p.name || preselectedProduct == p.id);
                html += `
                    <div class="searchable-option-item ${isSelected ? 'selected' : ''}" data-value="${escapeHtml(p.name)}" data-label="${escapeHtml(p.name)}">
                        <div style="display:flex;align-items:center;gap:6px">
                            <span>${escapeHtml(p.name)}</span>
                            <span class="searchable-check-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
                        </div>
                    </div>
                `;
            });

            html += '<div class="searchable-no-results" id="productNoResults" style="display:none">No matching product found</div>';

            prodOptionsList.innerHTML = html;

            if (preselectedProduct && preselectedProduct !== defaultLabel) {
                prodHiddenInput.value = preselectedProduct;
                prodTriggerText.textContent = preselectedProduct;
                prodTriggerText.classList.remove('placeholder');
            } else {
                prodHiddenInput.value = '';
                prodTriggerText.textContent = defaultLabel;
                prodTriggerText.classList.remove('placeholder');
            }

            // Bind click events on newly created product options
            prodOptionsList.querySelectorAll('.searchable-option-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const val = this.getAttribute('data-value');
                    const label = this.getAttribute('data-label');
                    selectProduct(val, label);
                    closeAllDropdowns();
                });
            });
        }

        // Generic Option Filtering
        function filterOptions(optionsList, query, noResultsEl) {
            const items = optionsList.querySelectorAll('.searchable-option-item');
            let visibleCount = 0;
            items.forEach(item => {
                const text = item.getAttribute('data-label') || item.textContent;
                if (!query || text.toLowerCase().includes(query)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            const noResults = optionsList.querySelector('.searchable-no-results') || noResultsEl;
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Global document click to close dropdowns
        document.addEventListener('click', function() {
            closeAllDropdowns();
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAllDropdowns();
            }
        });

        // Form submission validation handling
        const form = catContainer.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!catHiddenInput.value) {
                    e.preventDefault();
                    catTrigger.style.borderColor = '#ef4444';
                    catTrigger.focus();
                    catContainer.classList.add('open');
                    setTimeout(() => catSearchInput.focus(), 50);
                }
            });
        }

        // Initialize state if old values exist
        const initialCategory = "{{ old('subject') }}";
        const initialProduct = "{{ old('product', old('budget')) }}";
        if (initialCategory) {
            selectCategory(initialCategory, initialCategory);
            if (initialProduct) {
                populateProductDropdown(initialCategory, initialProduct);
            }
        }

        // Initialize Map centered on exact SILC 2/18 store location using same-origin tiles
        if (typeof L !== 'undefined') {
            const lat = 1.4760;
            const lng = 103.5940;
            const map = L.map('contactMap', { 
                scrollWheelZoom: false,
                zoomControl: true 
            }).setView([lat, lng], 16);

            // Same-origin tile proxy: fully compliant with CSP img-src 'self' & ad-blocker immune
            L.tileLayer('/map-tile/{z}/{x}/{y}', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19
            }).addTo(map);

            const customMarkerHtml = `
                <div style="background:#2563eb;width:38px;height:38px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(37,99,235,0.45);border:3px solid #ffffff">
                    <div style="transform:rotate(45deg);display:flex;align-items:center;justify-content:center;color:#ffffff">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                </div>
            `;

            const customIcon = L.divIcon({
                className: 'custom-map-pin',
                html: customMarkerHtml,
                iconSize: [38, 38],
                iconAnchor: [19, 38],
                popupAnchor: [0, -38]
            });

            const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
            marker.bindPopup(`
                <div style="font-family:sans-serif;padding:6px;min-width:210px">
                    <div style="font-weight:800;font-size:0.95rem;color:#0f172a;margin-bottom:4px">{{ addslashes($settings['store_name'] ?? 'Mika Import and Export SDN Bhd') }}</div>
                    <div style="color:#64748b;font-size:0.82rem;line-height:1.4">{{ addslashes($settings['store_address'] ?? '7, Jalan SILC 2/18, Kawasan Perindustrian SILC, Johor, Malaysia') }}</div>
                    <div style="margin-top:8px">
                        <a href="{{ $settings['store_map_url'] ?? 'https://maps.app.goo.gl/jLMaDYCNJ6vfk376A' }}" target="_blank" rel="noopener" style="color:#2563eb;font-weight:700;font-size:0.8rem;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                            <span>Get Directions</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            `).openPopup();

            setTimeout(() => {
                map.invalidateSize();
            }, 250);
            window.addEventListener('resize', () => {
                map.invalidateSize();
            });
        }
    });
</script>
@endsection
