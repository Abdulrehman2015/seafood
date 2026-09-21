@extends('layouts.app')
@section('title', __t('contact.meta_title', 'Contact Us & RFQ Sourcing — MST Import and Export Sdn Bhd'))
@section('meta_description', __t('contact.meta_desc', 'Contact MST Import and Export Sdn Bhd. Request a quote for frozen seafood, meats, frozen foods, food ingredients, or customised sourcing across Malaysia, Singapore, and regional markets.'))

@section('content')
<!-- Local Leaflet CSS (Same-Origin for strict CSP & ad-blocker compliance) -->
<link rel="stylesheet" href="{{ asset('css/leaflet.css') }}" />

<style>
    /* Base Container & Typography */
    .contact-page-container {
        max-width: 1240px;
        margin: 0 auto;
        padding: 44px 24px 80px;
        box-sizing: border-box;
    }

    /* ─── Hero Section Polish ─────────────────────────────────────────────── */
    .contact-hero-section {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #07152b 0%, #0c234b 45%, #1d4ed8 100%);
        color: #ffffff;
        border-bottom: 1px solid rgba(37, 99, 235, 0.35);
        padding-top: calc(78px + 32px);
        padding-bottom: 36px;
    }
    .contact-hero-grid-pattern {
        position: absolute;
        inset: 0;
        opacity: 0.08;
        background-image: radial-gradient(#38bdf8 1.5px, transparent 1.5px);
        background-size: 24px 24px;
        pointer-events: none;
    }
    .contact-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(56, 189, 248, 0.16);
        border: 1px solid rgba(186, 230, 253, 0.35);
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #7dd3fc;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .contact-hero-badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.84rem;
        padding: 8px 16px;
        border-radius: 999px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        background: rgba(9, 26, 54, 0.85);
        color: #7dd3fc;
        border: 1px solid rgba(59, 130, 246, 0.6);
        backdrop-filter: blur(8px);
    }

    /* ─── 1. Top 4 Contact Action Cards ───────────────────────────────────── */
    .contact-top-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 48px;
    }
    .contact-top-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 26px 18px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
        position: relative;
    }
    .contact-top-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(37, 99, 235, 0.1);
        border-color: #93c5fd;
    }
    .contact-icon-bubble {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .contact-top-card:hover .contact-icon-bubble {
        background: #2563eb;
        color: #ffffff;
        transform: scale(1.05);
    }
    .contact-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px;
    }
    .contact-card-main-title {
        font-size: 0.92rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
        line-height: 1.3;
    }
    .contact-card-main-link {
        font-size: 0.95rem;
        font-weight: 800;
        color: #2563eb;
        margin-bottom: 4px;
        text-decoration: none;
        display: inline-block;
        transition: color 0.15s ease;
        word-break: break-word;
    }
    .contact-card-main-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }
    .contact-card-sub {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.45;
        width: 100%;
    }

    /* ─── 2. Main Sourcing & Consultation Split Section ─────────────────── */
    .consultation-split-wrapper {
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        display: grid;
        grid-template-columns: 1fr 1.35fr;
        margin-bottom: 72px;
        box-shadow: 0 16px 40px rgba(6, 21, 43, 0.07);
        overflow: visible;
    }
    .consultation-blue-panel {
        background: linear-gradient(135deg, #07152b 0%, #0e2246 45%, #15386f 80%, #1e4ed8 100%);
        padding: 44px 38px;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        border-radius: 24px 0 0 24px;
    }
    .consultation-blue-panel .panel-orb-1 {
        position: absolute;
        width: 340px;
        height: 340px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.4) 0%, transparent 70%);
        top: -80px;
        right: -80px;
        pointer-events: none;
    }
    .consultation-blue-panel .panel-orb-2 {
        position: absolute;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(96, 165, 250, 0.25) 0%, transparent 70%);
        bottom: -40px;
        left: -40px;
        pointer-events: none;
    }
    .consultation-blue-panel .panel-grid-overlay {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
        background-size: 36px 36px;
        pointer-events: none;
    }
    .sourcing-step-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(12px);
        border-radius: 999px;
        padding: 5px 14px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #93c5fd;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
    }
    .step-live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #22d3ee;
        box-shadow: 0 0 8px rgba(34, 211, 238, 0.8);
        flex-shrink: 0;
    }
    .sourcing-panel-title {
        font-family: 'Outfit', 'Inter', -apple-system, sans-serif;
        color: #ffffff;
        font-size: clamp(1.65rem, 2.8vw, 2.1rem);
        font-weight: 800;
        line-height: 1.22;
        margin: 0 0 28px;
        letter-spacing: -0.02em;
        position: relative;
        z-index: 2;
    }
    .sourcing-gradient-text {
        background: linear-gradient(135deg, #60a5fa 0%, #93c5fd 50%, #bfdbfe 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .consultation-white-panel {
        padding: 44px 40px;
        background: #ffffff;
        border-radius: 0 24px 24px 0;
        position: relative;
        overflow: visible;
    }
    .consultation-step-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        position: relative;
        z-index: 2;
    }
    .step-number-box {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        color: #60a5fa;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.95rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
    }
    .sourcing-trust-box {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.14);
        backdrop-filter: blur(12px);
        border-radius: 14px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
        z-index: 2;
        margin-top: 24px;
    }
    .sourcing-trust-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(34, 211, 238, 0.15);
        border: 1px solid rgba(34, 211, 238, 0.3);
        color: #22d3ee;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ─── Multi-select Dropdown Styles ────────────────────────────────────── */
    .multi-select-container {
        position: relative;
        width: 100%;
    }
    .multi-select-trigger {
        min-height: 46px;
        height: auto;
        padding: 6px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        cursor: pointer;
        border: 1.5px solid #cbd5e1;
        border-radius: 9px;
        background: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .multi-select-trigger:hover {
        border-color: #93c5fd;
    }
    .multi-select-container.open .multi-select-trigger {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .multi-select-display {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        flex: 1;
        min-width: 0;
    }
    .selected-tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1d4ed8;
        padding: 3px 8px 3px 10px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        line-height: 1.4;
        animation: fadeInSelect 0.15s ease-out;
    }
    .selected-tag-remove {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        color: #60a5fa;
        cursor: pointer;
        font-size: 13px;
        font-weight: 700;
        transition: background 0.15s, color 0.15s;
    }
    .selected-tag-remove:hover {
        background: #dbeafe;
        color: #1e40af;
    }
    .multi-select-badge-count {
        background: #2563eb;
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 999px;
        letter-spacing: 0.02em;
    }
    .multi-select-dropdown-panel {
        max-height: 340px;
        display: none;
        flex-direction: column;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff !important;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.22), 0 6px 14px rgba(15, 23, 42, 0.12);
        padding: 10px;
        z-index: 3500 !important;
        animation: fadeInSelect 0.15s ease-out;
    }
    .multi-select-container.open .multi-select-dropdown-panel {
        display: flex;
    }
    .multi-select-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 6px;
    }
    .multi-select-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        flex-shrink: 0;
    }
    .multi-select-action-btn {
        background: none;
        border: none;
        color: #2563eb;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        padding: 2px 4px;
        border-radius: 4px;
        transition: background 0.15s, color 0.15s;
    }
    .multi-select-action-btn:hover {
        background: #eff6ff;
        color: #1d4ed8;
        text-decoration: underline;
    }
    .multi-select-options-list {
        max-height: 220px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 4px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }
    .multi-select-option-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
        border: 1px solid transparent;
    }
    .multi-select-option-item:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }
    .multi-select-option-item.selected {
        background: #eff6ff;
        border-color: #bfdbfe;
    }
    .multi-select-checkbox-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .multi-select-checkbox-wrapper input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        margin: 0;
        z-index: 2;
    }
    .custom-checkbox-indicator {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        transition: all 0.15s ease;
    }
    .multi-select-checkbox-wrapper input[type="checkbox"]:checked + .custom-checkbox-indicator,
    .multi-select-option-item.selected .custom-checkbox-indicator {
        background: #2563eb;
        border-color: #2563eb;
    }
    .multi-select-checkbox-wrapper input[type="checkbox"]:focus + .custom-checkbox-indicator {
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }
    .multi-select-item-content {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 0;
    }
    .multi-select-item-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
    }
    .multi-select-option-item.selected .multi-select-item-label {
        color: #1d4ed8;
    }
    .multi-select-item-desc {
        font-size: 0.74rem;
        color: #64748b;
        margin-top: 1px;
    }
    .multi-select-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 8px;
        margin-top: 6px;
        border-top: 1px solid #f1f5f9;
    }

    /* ─── Form Elements & Responsive Grids ────────────────────────────────── */
    .consultation-form-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 18px;
    }
    .consultation-form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 18px;
        position: relative;
        z-index: 50;
    }
    .consultation-form-grid-2.has-open-dropdown {
        z-index: 2000 !important;
    }
    .consultation-form-grid-2 > div {
        position: relative;
        z-index: 1;
    }
    .consultation-form-grid-2 > div.is-dropdown-open,
    .consultation-form-grid-2 > div:has(.searchable-select-container.open) {
        z-index: 2000 !important;
    }
    .form-group-custom {
        position: relative;
        z-index: 10;
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
        height: 46px;
        border-radius: 9px;
        border: 1.5px solid #cbd5e1;
        padding: 0 14px;
        font-size: 0.92rem;
        font-weight: 400;
        color: #0f172a;
        background: #ffffff;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control-custom:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    textarea.form-control-custom {
        height: auto;
        min-height: 105px;
        padding: 12px 14px;
        resize: vertical;
        line-height: 1.5;
        font-weight: 400 !important;
        font-size: 0.90rem;
    }
    textarea.form-control-custom::placeholder,
    textarea.form-control-custom::-webkit-input-placeholder,
    textarea.form-control-custom::-moz-placeholder,
    textarea.form-control-custom:-ms-input-placeholder,
    .form-control-custom::placeholder,
    .form-control-custom::-webkit-input-placeholder,
    .form-control-custom::-moz-placeholder,
    .form-control-custom:-ms-input-placeholder {
        font-size: 0.78rem !important;
        font-weight: 400 !important;
        color: #94a3b8 !important;
        line-height: 1.45 !important;
        opacity: 1 !important;
        -webkit-text-fill-color: #94a3b8 !important;
    }

    /* ─── Custom Searchable Select Styles ─────────────────────────────────── */
    .searchable-select-container {
        position: relative;
        width: 100%;
        z-index: 15;
    }
    .searchable-select-container.open {
        z-index: 2200 !important;
    }
    .form-group-interests {
        position: relative;
        z-index: 25;
    }
    .form-group-interests.has-open-dropdown,
    .form-group-custom:has(.multi-select-container.open) {
        z-index: 3500 !important;
    }
    .multi-select-container {
        position: relative;
        width: 100%;
        z-index: 25;
    }
    .multi-select-container.open {
        z-index: 3500 !important;
    }
    .searchable-trigger {
        width: 100%;
        height: 46px;
        padding: 0 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 9px;
        background: #ffffff;
        font-size: 0.92rem;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        user-select: none;
        box-sizing: border-box;
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
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #94a3b8;
        cursor: not-allowed;
    }
    .searchable-selected-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 8px;
        font-size: 0.92rem;
    }
    .searchable-selected-text.placeholder {
        color: #94a3b8;
        font-weight: 400 !important;
        font-size: 0.88rem !important;
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
        box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.22), 0 6px 14px rgba(15, 23, 42, 0.12);
        padding: 8px;
        z-index: 2500 !important;
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
        height: 38px;
        padding: 0 10px 0 34px !important;
        border: 1px solid #e2e8f0;
        border-radius: 7px;
        background: #f8fafc;
        font-size: 0.86rem;
        color: #0f172a;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        box-sizing: border-box;
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
        -webkit-overflow-scrolling: touch;
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

    /* ─── Submit Button ───────────────────────────────────────────────────── */
    .contact-submit-btn {
        width: 100%;
        height: 52px;
        position: relative;
        z-index: 5;
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 1.02rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 16px rgba(29, 78, 216, 0.35);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .contact-submit-btn:hover {
        background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
        box-shadow: 0 8px 24px rgba(29, 78, 216, 0.45);
        transform: translateY(-1px);
    }
    .contact-submit-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(29, 78, 216, 0.3);
    }

    /* ─── 3. Location & Map Section ───────────────────────────────────────── */
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
        top: 18px;
        left: 18px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        padding: 14px 18px;
        z-index: 500;
        border: 1px solid rgba(226, 232, 240, 0.9);
        display: flex;
        flex-direction: column;
        gap: 2px;
        max-width: 300px;
    }

    /* ─── TABLET RESPONSIVE (1024px and below) ────────────────────────────── */
    @media (max-width: 1024px) {
        .contact-page-container {
            padding: 36px 20px 64px;
        }
        /* Top cards keep natural order at top */
        .contact-top-cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 40px;
        }
        .contact-top-card {
            padding: 24px 16px;
        }

        /* Split wrapper stacks naturally: Process on top, RFQ form on bottom */
        .consultation-split-wrapper {
            display: flex;
            flex-direction: column;
            border-radius: 20px;
            margin-bottom: 56px;
        }
        .consultation-blue-panel {
            padding: 36px 30px;
            border-radius: 20px 20px 0 0;
        }
        .consultation-white-panel {
            padding: 36px 30px;
            border-radius: 0 0 20px 20px;
        }

        /* Tablet Form inputs layout: 2 cols for name/email, full width for phone */
        .consultation-form-grid-3 {
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .consultation-form-grid-3 > div:nth-child(3) {
            grid-column: span 2;
        }
        .consultation-form-grid-2 {
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        /* Map and Office stack */
        .location-split-grid {
            grid-template-columns: 1fr;
            gap: 22px;
            margin-bottom: 56px;
        }
        .office-info-card {
            padding: 28px 24px;
            border-radius: 18px;
        }
        .map-embed-wrapper {
            min-height: 420px;
            height: 420px;
            border-radius: 18px;
        }
        #contactMap {
            min-height: 420px;
            height: 420px;
        }
    }

    /* ─── TABLET PORTRAIT / INTERMEDIATE (860px and below) ────────────────── */
    @media (max-width: 860px) {
        .consultation-form-grid-2 {
            grid-template-columns: 1fr !important;
            gap: 14px;
        }
    }

    /* ─── MOBILE RESPONSIVE (640px and below) ─────────────────────────────── */
    @media (max-width: 640px) {
        .contact-hero-section {
            padding-top: calc(78px + 30px);
            padding-bottom: 28px;
        }
        .contact-page-container {
            padding: 20px 14px 48px;
        }

        /* Top 4 Cards in 2x2 grid for mobile - compact, tap-friendly */
        .contact-top-cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 26px;
        }
        .contact-top-card {
            padding: 18px 10px;
            border-radius: 16px;
        }
        .contact-icon-bubble {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            margin-bottom: 10px;
        }
        .contact-card-title {
            font-size: 0.88rem;
            margin-bottom: 3px;
        }
        .contact-card-main-title {
            font-size: 0.82rem;
            line-height: 1.25;
            margin-bottom: 2px;
        }
        .contact-card-main-link {
            font-size: 0.84rem;
            line-height: 1.25;
            margin-bottom: 2px;
        }
        .contact-card-sub {
            font-size: 0.72rem;
            line-height: 1.35;
        }

        /* Split wrapper on mobile */
        .consultation-split-wrapper {
            border-radius: 18px;
            margin-bottom: 36px;
        }
        .consultation-blue-panel {
            padding: 24px 16px;
            border-radius: 18px 18px 0 0;
        }
        .sourcing-step-badge {
            font-size: 0.68rem;
            padding: 4px 12px;
            margin-bottom: 16px;
        }
        .sourcing-panel-title {
            font-size: 1.55rem;
            margin-bottom: 20px;
        }
        .consultation-step-item {
            gap: 12px;
        }
        .step-number-box {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            font-size: 0.88rem;
        }
        .sourcing-trust-box {
            padding: 12px 14px;
            gap: 10px;
            border-radius: 12px;
            margin-top: 18px;
        }
        .sourcing-trust-icon {
            width: 34px;
            height: 34px;
        }

        /* White Form Panel on mobile */
        .consultation-white-panel {
            padding: 24px 16px;
            border-radius: 0 0 18px 18px;
        }
        .consultation-white-panel h3 {
            font-size: 1.6rem !important;
            margin-bottom: 4px !important;
        }

        /* Form grids single column on mobile */
        .consultation-form-grid-3,
        .consultation-form-grid-2 {
            grid-template-columns: 1fr !important;
            gap: 14px;
            margin-bottom: 14px;
        }
        .consultation-form-grid-3 > div:nth-child(3) {
            grid-column: auto;
        }
        .form-group-custom {
            margin-bottom: 14px;
        }

        /* Form Controls on mobile */
        .form-control-custom,
        .searchable-trigger {
            font-size: 15px;
            height: 48px;
            border-radius: 9px;
        }
        .searchable-selected-text {
            font-size: 0.90rem;
        }
        .searchable-selected-text.placeholder {
            font-size: 0.86rem !important;
            font-weight: 400 !important;
            color: #94a3b8 !important;
        }
        textarea.form-control-custom {
            font-size: 15px;
            min-height: 95px;
            font-weight: 400 !important;
        }
        textarea.form-control-custom::placeholder,
        textarea.form-control-custom::-webkit-input-placeholder,
        textarea.form-control-custom::-moz-placeholder,
        textarea.form-control-custom:-ms-input-placeholder,
        .form-control-custom::placeholder,
        .form-control-custom::-webkit-input-placeholder,
        .form-control-custom::-moz-placeholder,
        .form-control-custom:-ms-input-placeholder {
            font-size: 0.75rem !important;
            font-weight: 400 !important;
            color: #94a3b8 !important;
            line-height: 1.4 !important;
            opacity: 1 !important;
            -webkit-text-fill-color: #94a3b8 !important;
        }
        .searchable-search-input {
            font-size: 15px;
            height: 40px;
        }
        .searchable-dropdown-panel,
        .multi-select-dropdown-panel {
            max-height: 270px;
            border-radius: 10px;
        }
        .contact-submit-btn {
            height: 50px;
            font-size: 0.96rem;
            border-radius: 9px;
        }

        /* Office Card & Map on mobile */
        .location-split-grid {
            gap: 18px;
            margin-bottom: 40px;
        }
        .office-info-card {
            padding: 20px 16px;
            border-radius: 16px;
        }
        .map-embed-wrapper {
            min-height: 330px;
            height: 330px;
            border-radius: 16px;
        }
        #contactMap {
            min-height: 330px;
            height: 330px;
        }
        /* Sleek compact pill badge on mobile so map remains interactive */
        .map-overlay-badge {
            top: 10px;
            left: 10px;
            right: 10px;
            max-width: none;
            padding: 10px 12px;
            border-radius: 10px;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .map-overlay-badge .map-badge-desc {
            display: none;
        }
    }

    /* ─── ULTRA-SMALL MOBILE (360px and below) ────────────────────────────── */
    @media (max-width: 360px) {
        .contact-top-cards {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .contact-top-card {
            padding: 16px 14px;
        }
    }
</style>

<!-- ─── Page Header / Hero Section ──────────────────────────────────────── -->
<div class="page-header contact-hero-section">
    <div class="contact-hero-grid-pattern"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 @t('nav.home', 'Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('contact.title', 'Contact Us & RFQ')</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap">
                    <span class="contact-hero-pill">
                        @t('contact.hero_pill', '📍 Sourcing & Customer Support')
                    </span>
                    <span style="color:#bae6fd;font-size:0.8rem">@t('contact.hero_location', 'Iskandar Puteri, Johor Bahru · Regional & International Supply')</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.4rem);margin-bottom:6px;letter-spacing:-0.02em">
                    @t('contact.header_title', 'Contact Us — MST Import and Export')
                </h1>
                <div style="color:#93c5fd;font-size:0.88rem;font-weight:700;margin-bottom:8px">
                    镁嘉国际贸易有限公司 · MST IMPORT &amp; EXPORT SDN. BHD.
                </div>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:720px;line-height:1.5;margin:0">
                    @t('contact.subtitle', 'Have questions about our frozen seafood, meats, food ingredients, customised sourcing, or cold-chain distribution? Reach out directly to our commercial team for prompt quotations and dedicated assistance.')
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div class="contact-hero-badge-tag">
                    @t('contact.prompt_response', '⚡ Prompt Quote Response Within 24 Hours')
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact-page-container">

    <!-- 1. Top 4 Contact Info Cards -->
    <div class="contact-top-cards">
        
        <!-- Card 1: Facility & Cold Storage Hub -->
        <div class="contact-top-card">
            <div class="contact-icon-bubble">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </div>
            <h3 class="contact-card-title">@t('contact.card1_title', 'Facility & Cold Hub')</h3>
            <div class="contact-card-main-title">
                @t('about.company_name_full', 'MST Import and Export Sdn Bhd')
            </div>
            <div style="font-size:0.78rem;color:#2563eb;font-weight:700;margin-bottom:4px">
                镁嘉国际贸易有限公司
            </div>
            <div class="contact-card-sub">
                @t('contact.card1_address', 'No. 7, Jalan SiLC 2/18, Kawasan Perindustrian SiLC, 79200 Iskandar Puteri, Johor, Malaysia')
            </div>
            <a href="https://maps.app.goo.gl/jLMaDYCNJ6vfk376A" target="_blank" rel="noopener" 
               style="margin-top:8px;font-size:0.78rem;font-weight:700;color:#2563eb;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                <span>@t('contact.view_on_maps', 'View on Google Maps')</span>
                <span>&rarr;</span>
            </a>
        </div>

        <!-- Card 2: Direct Hotlines -->
        <div class="contact-top-card">
            <div class="contact-icon-bubble">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
            </div>
            <h3 class="contact-card-title">@t('contact.card2_title', 'Direct Hotlines')</h3>
            <a href="tel:0132800168" class="contact-card-main-link">
                013-280 0168
            </a>
            <div class="contact-card-sub">
                <div style="color:#1d4ed8;font-weight:600;font-size:0.82rem">Alt: 011-1436 0109</div>
                <div style="color:#1d4ed8;font-weight:600;font-size:0.82rem">WhatsApp: 011-1271 0260</div>
                <div style="margin-top:4px">@t('contact.operating_hours_val', 'Mon – Sat: 8:00 AM – 6:00 PM')</div>
                <div style="color:#94a3b8;font-size:0.75rem">@t('contact.closed_val', 'Sunday & PH: Closed')</div>
            </div>
        </div>

        <!-- Card 3: Email Contacts -->
        <div class="contact-top-card">
            <div class="contact-icon-bubble">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
            <h3 class="contact-card-title">@t('contact.card3_title', 'Email Us')</h3>
            <a href="mailto:mikatrading15@gmail.com" class="contact-card-main-link" style="word-break:break-all">
                mikatrading15@gmail.com
            </a>
            <div class="contact-card-sub">
                <div style="color:#1d4ed8;font-weight:600;font-size:0.8rem">@t('contact.card3_sub', 'B2B Wholesale & Custom RFQ')</div>
                <div style="margin-top:2px">@t('contact.card3_response', 'Guaranteed Response Within 24 Hours')</div>
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
            <h3 class="contact-card-title">@t('contact.card4_title', 'WhatsApp Support')</h3>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener" class="contact-card-main-link">
                +60 11-1271 0260
            </a>
            <div class="contact-card-sub">
                @t('contact.card4_sub1', 'Fast Chat & Inquiries')<br>
                @t('contact.card4_sub2', 'Live Support Online')
            </div>
        </div>

    </div>

    <!-- 2. Main Consultation Split Section: 4-Step Process & RFQ Form -->
    <div class="consultation-split-wrapper">
        
        <!-- Left Panel: The 4-Step Sourcing Process (Section 7.1) -->
        <div class="consultation-blue-panel">
            {{-- Background ambient glows & grid matching Homepage Hero --}}
            <div class="panel-orb-1"></div>
            <div class="panel-orb-2"></div>
            <div class="panel-grid-overlay"></div>

            <div>
                <!-- Badge -->
                <div class="sourcing-step-badge">
                    <span class="step-live-dot"></span>
                    @t('contact.process_badge', 'THE SOURCING PROCESS · 4 EASY STEPS')
                </div>

                <!-- Title -->
                <h2 class="sourcing-panel-title">
                    @t('contact.process_title_1', 'Streamlined Sourcing,')<br>
                    <span class="sourcing-gradient-text">@t('contact.process_title_2', 'From Inquiry to Supply')</span>
                </h2>

                <!-- 4 Steps Flow -->
                <div style="display:flex;flex-direction:column;gap:24px;margin-bottom:36px;position:relative;z-index:2;">
                    
                    <!-- Step 1 -->
                    <div class="consultation-step-item">
                        <div class="step-number-box">01</div>
                        <div>
                            <div style="font-weight:800;font-size:0.96rem;color:#ffffff;margin-bottom:3px">@t('contact.step1_title', 'Select Requirement')</div>
                            <div style="font-size:0.83rem;color:rgba(255,255,255,0.75);line-height:1.45">
                                @t('contact.step1_desc', 'Choose from our core categories (Seafood, Meat, Frozen Food, Food Ingredients, Cuisine Ingredients, Desserts) or specify a customised sourcing request.')
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="consultation-step-item">
                        <div class="step-number-box">02</div>
                        <div>
                            <div style="font-weight:800;font-size:0.96rem;color:#ffffff;margin-bottom:3px">@t('contact.step2_title', 'Tell Us Your Requirements')</div>
                            <div style="font-size:0.83rem;color:rgba(255,255,255,0.75);line-height:1.45">
                                @t('contact.step2_desc', 'Specify your target volume (kg, cartons, pallets), pack size, origin preference, delivery frequency, or customized product specifications.')
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="consultation-step-item">
                        <div class="step-number-box">03</div>
                        <div>
                            <div style="font-weight:800;font-size:0.96rem;color:#ffffff;margin-bottom:3px">@t('contact.step3_title', 'Receive a Quotation')</div>
                            <div style="font-size:0.83rem;color:rgba(255,255,255,0.75);line-height:1.45">
                                @t('contact.step3_desc', 'Our commercial team evaluates availability or coordinates with our network, providing a transparent, competitive quotation within 24 hours.')
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="consultation-step-item">
                        <div class="step-number-box">04</div>
                        <div>
                            <div style="font-weight:800;font-size:0.96rem;color:#ffffff;margin-bottom:3px">@t('contact.step4_title', 'Arrange Supply & Logistics')</div>
                            <div style="font-size:0.83rem;color:rgba(255,255,255,0.75);line-height:1.45">
                                @t('contact.step4_desc', 'Scheduled temperature-controlled logistics (-18°C to -25°C) across Malaysia & Singapore, regional export, or self-collection at our SILC facility.')
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Cold Chain Trust Box -->
            <div class="sourcing-trust-box">
                <div class="sourcing-trust-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <div style="font-size:0.82rem;color:rgba(255,255,255,0.85);line-height:1.4">
                    <strong style="color:#ffffff;">@t('contact.trust_label', 'Cold-Chain & HACCP Assurance:')</strong> @t('contact.trust_desc', 'Stored at -18°C to -25°C with unbroken cold-chain integrity and certified handling.')
                </div>
            </div>

        </div>

        <!-- Right Panel: White Card RFQ & Inquiry Form (Section 7.2) -->
        <div class="consultation-white-panel">
            
            <div style="font-size:0.75rem;font-weight:800;letter-spacing:1.2px;color:#2563eb;text-transform:uppercase;margin-bottom:6px">
                @t('contact.form_eyebrow', 'REQUEST FOR QUOTATION & INQUIRY')
            </div>
            
            <h3 style="font-size:2.1rem;font-weight:800;color:#0f172a;margin:0 0 6px;letter-spacing:-0.5px">
                @t('contact.form_title', 'Submit Your Sourcing RFQ')
            </h3>

            <p style="font-size:0.92rem;color:#64748b;margin:0 0 24px;line-height:1.5">
                @t('contact.form_desc', 'Tell us about your requirements. Whether you need standard catalog items, wholesale quantities, or tailored sourcing, our team will get back to you within 24 hours.')
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

                <!-- Row 1: Contact Details (Name, Email, Phone/WhatsApp) -->
                <div class="consultation-form-grid-3">
                    <div>
                        <label class="form-label-custom">
                            @t('contact.full_name', 'Full name') <span style="color:#ef4444">*</span>
                        </label>
                        <input type="text" name="name" class="form-control-custom" 
                               value="{{ old('name', auth()->user()?->name) }}" required 
                               placeholder="{{ __t('contact.name_placeholder', 'Your full name') }}">
                        @error('name')<div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="form-label-custom">
                            @t('contact.email_address', 'Email address') <span style="color:#ef4444">*</span>
                        </label>
                        <input type="email" name="email" class="form-control-custom" 
                               value="{{ old('email', auth()->user()?->email) }}" required 
                               placeholder="{{ __t('contact.email_placeholder', 'you@company.com') }}">
                        @error('email')<div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="form-label-custom">
                            @t('contact.phone_whatsapp', 'Phone / WhatsApp') <span style="color:#ef4444">*</span>
                        </label>
                        <input type="tel" name="phone" class="form-control-custom" 
                               value="{{ old('phone', auth()->user()?->phone) }}" required
                               placeholder="+60 12-345 6789">
                        @error('phone')<div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Row 2: Multi-select Interests Dropdown -->
                <div class="form-group-custom form-group-interests" id="interestsFormGroup">
                    <label class="form-label-custom" for="interestsMultiSelectTrigger">
                        @t('contact.interested_in', 'I am interested in:') <span style="font-weight:400;color:#64748b">@t('contact.select_multiple_hint', '(Select multiple from dropdown)')</span>
                    </label>
                    <div class="searchable-select-container multi-select-container" id="interestsMultiSelectContainer">
                        <div class="searchable-trigger multi-select-trigger" id="interestsMultiSelectTrigger" tabindex="0" role="combobox" aria-haspopup="listbox" aria-expanded="false">
                            <div class="multi-select-display" id="interestsDisplay">
                                <span class="searchable-selected-text placeholder" id="interestsPlaceholder">{{ __t('contact.select_interests_placeholder', 'Select interested categories / services...') }}</span>
                            </div>
                            <div style="display:flex;align-items:center;gap:6px;margin-left:auto;flex-shrink:0;">
                                <span class="multi-select-badge-count" id="interestsCountBadge" style="display:none">0 selected</span>
                                <span class="searchable-arrow">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="searchable-dropdown-panel multi-select-dropdown-panel" id="interestsDropdownPanel">
                            <div class="multi-select-header">
                                <div class="searchable-search-wrapper" style="margin-bottom:0;flex:1;">
                                    <span class="searchable-search-icon">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                    </span>
                                    <input type="text" class="searchable-search-input" id="interestsSearchInput" placeholder="{{ __t('contact.filter_interests', 'Filter interests...') }}" autocomplete="off">
                                </div>
                                <div class="multi-select-actions">
                                    <button type="button" class="multi-select-action-btn" id="selectAllInterests">@t('contact.select_all', 'Select All')</button>
                                    <span style="color:#cbd5e1">·</span>
                                    <button type="button" class="multi-select-action-btn" id="clearAllInterests">@t('contact.clear', 'Clear')</button>
                                </div>
                            </div>

                            <div class="searchable-options-list multi-select-options-list" id="interestsOptionsList" role="listbox" aria-multiselectable="true">
                                @php
                                    $interestOptions = [
                                        'Seafood' => ['label' => __t('contact.interest_seafood_label', 'Seafood'), 'icon' => '🐟', 'desc' => __t('contact.interest_seafood_desc', 'Wild & farmed fresh-frozen seafood')],
                                        'Meat' => ['label' => __t('contact.interest_meat_label', 'Meat'), 'icon' => '🥩', 'desc' => __t('contact.interest_meat_desc', 'Poultry, beef, lamb & speciality meats')],
                                        'Frozen Food' => ['label' => __t('contact.interest_frozen_label', 'Frozen Food'), 'icon' => '❄️', 'desc' => __t('contact.interest_frozen_desc', 'Processed & ready-to-cook products')],
                                        'Food Ingredients' => ['label' => __t('contact.interest_food_ing_label', 'Food Ingredients'), 'icon' => '🧂', 'desc' => __t('contact.interest_food_ing_desc', 'Commercial seasonings, pastes & bases')],
                                        'Cuisine Ingredients' => ['label' => __t('contact.interest_cuisine_label', 'Cuisine Ingredients'), 'icon' => '🌏', 'desc' => __t('contact.interest_cuisine_desc', 'Regional & Asian culinary specialties')],
                                        'Desserts & Snacks' => ['label' => __t('contact.interest_desserts_label', 'Desserts & Snacks'), 'icon' => '🍰', 'desc' => __t('contact.interest_desserts_desc', 'Pastries, dim sum & snack items')],
                                        'Customised Sourcing' => ['label' => __t('contact.interest_sourcing_label', 'Customised Sourcing'), 'icon' => '🔍', 'desc' => __t('contact.interest_sourcing_desc', 'Tailored specs & bulk import services')],
                                        'Wholesale Supply' => ['label' => __t('contact.interest_wholesale_label', 'Wholesale Supply'), 'icon' => '🏭', 'desc' => __t('contact.interest_wholesale_desc', 'B2B food service & contract supply')],
                                        'Trading & Export' => ['label' => __t('contact.interest_trading_label', 'Trading & Export'), 'icon' => '📦', 'desc' => __t('contact.interest_trading_desc', 'Cross-border logistics & export trade')],
                                        'Other' => ['label' => __t('contact.interest_other_label', 'Other'), 'icon' => '📋', 'desc' => __t('contact.interest_other_desc', 'Other specific inquiries & custom requests')],
                                    ];
                                    $oldInterests = (array) old('interests', []);
                                @endphp
                                @foreach($interestOptions as $val => $info)
                                    @php $isChecked = in_array($val, $oldInterests); @endphp
                                    <label class="multi-select-option-item {{ $isChecked ? 'selected' : '' }}" data-value="{{ $val }}" data-label="{{ $info['label'] }}" data-icon="{{ $info['icon'] }}">
                                        <div class="multi-select-checkbox-wrapper">
                                            <input type="checkbox" name="interests[]" value="{{ $val }}" class="interest-checkbox" {{ $isChecked ? 'checked' : '' }}>
                                            <span class="custom-checkbox-indicator">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="multi-select-item-content">
                                            <div class="multi-select-item-label">
                                                <span class="multi-select-emoji">{{ $info['icon'] }}</span>
                                                <span class="multi-select-name">{{ $info['label'] }}</span>
                                            </div>
                                            <span class="multi-select-item-desc">{{ $info['desc'] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                                <div class="searchable-no-results" id="interestsNoResults" style="display:none">@t('contact.no_category_match', 'No matching category found')</div>
                            </div>

                            <div class="multi-select-footer">
                                <span id="interestsFooterCount" style="font-size:0.8rem;color:#64748b;font-weight:600">0 selected</span>
                                <button type="button" class="btn btn-sm btn-primary" id="interestsDoneBtn" style="padding:4px 14px;font-size:0.8rem;border-radius:6px;background:#2563eb;color:#ffffff;border:none;cursor:pointer">@t('contact.done', 'Done')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Category & Product Selection -->
                <div class="consultation-form-grid-2">
                    <div class="field-col-category">
                        <label class="form-label-custom">
                            @t('contact.specific_category', 'Specific Category') <span style="font-weight:400;color:#94a3b8">@t('contact.optional', '(optional)')</span>
                        </label>
                        <!-- Custom Searchable Category Dropdown -->
                        <div class="searchable-select-container" id="categorySelectContainer">
                            <input type="hidden" name="subject" id="categoryHiddenInput" value="{{ old('subject') }}">
                            <div class="searchable-trigger" id="categoryTrigger" tabindex="0" role="combobox" aria-haspopup="listbox" aria-expanded="false">
                                <span class="searchable-selected-text {{ old('subject') ? '' : 'placeholder' }}" id="categoryTriggerText">
                                    {{ old('subject') ? old('subject') : __t('contact.select_category_placeholder', 'Select category (optional)...') }}
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
                                    <input type="text" class="searchable-search-input" id="categorySearchInput" placeholder="{{ __t('contact.search_categories', 'Search categories...') }}" autocomplete="off">
                                </div>
                                <div class="searchable-options-list" id="categoryOptionsList" role="listbox">
                                    <div class="searchable-option-item {{ !old('subject') ? 'selected' : '' }}" 
                                         data-value="" 
                                         data-label="{{ __t('contact.all_categories', 'All Categories / Custom Sourcing') }}">
                                        <div style="display:flex;align-items:center;gap:6px">
                                            <span>@t('contact.all_categories', 'All Categories / Custom Sourcing')</span>
                                            <span class="searchable-check-icon">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            </span>
                                        </div>
                                    </div>
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
                                    <div class="searchable-option-item {{ old('subject') == 'Customised Sourcing Request' ? 'selected' : '' }}" 
                                         data-value="Customised Sourcing Request" 
                                         data-label="{{ __t('contact.customised_sourcing_req', 'Customised Sourcing Request') }}">
                                        <div style="display:flex;align-items:center;gap:6px">
                                            <span>🔍 @t('contact.customised_sourcing_req', 'Customised Sourcing Request')</span>
                                            <span class="searchable-check-icon">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="searchable-no-results" id="categoryNoResults" style="display:none">
                                        @t('contact.no_category_match', 'No matching category found')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field-col-product">
                        <label class="form-label-custom">
                            @t('contact.product_item', 'Product / Item') <span style="font-weight:400;color:#94a3b8">@t('contact.optional', '(optional)')</span>
                        </label>
                        <!-- Custom Searchable Product Dropdown -->
                        <div class="searchable-select-container disabled" id="productSelectContainer">
                            <input type="hidden" name="product" id="productHiddenInput" value="{{ old('product') }}">
                            <div class="searchable-trigger" id="productTrigger" tabindex="0" role="combobox" aria-haspopup="listbox" aria-expanded="false">
                                <span class="searchable-selected-text {{ old('product') ? '' : 'placeholder' }}" id="productTriggerText">
                                    {{ old('product') ? old('product') : __t('contact.select_product_placeholder', 'Select product (optional)...') }}
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
                                    <input type="text" class="searchable-search-input" id="productSearchInput" placeholder="{{ __t('contact.search_products', 'Search products...') }}" autocomplete="off">
                                </div>
                                <div class="searchable-options-list" id="productOptionsList" role="listbox">
                                    <div class="searchable-no-results" id="productNoResults" style="display:none">
                                        @t('contact.no_product_match', 'No matching product found')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 4: Requirements / Description (Section 7.2) -->
                <div class="form-group-custom" style="margin-bottom:24px">
                    <label class="form-label-custom">
                        @t('contact.requirements_desc', 'Requirements / Description') <span style="color:#ef4444">*</span>
                    </label>
                    <textarea name="message" class="form-control-custom" rows="4" required 
                              placeholder="{{ __t('contact.message_placeholder', 'Please describe your requirements in detail: target volume (e.g. 500kg, cartons), preferred pack size, origin specifications, delivery frequency or location...') }}">{{ old('message') }}</textarea>
                    @error('message')<div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="contact-submit-btn">
                    <span>@t('contact.submit_btn', 'Submit Sourcing RFQ & Inquiry')</span>
                    <span style="font-size:1.2rem">&rarr;</span>
                </button>

                <p style="text-align:center;font-size:0.8rem;color:#64748b;margin:14px 0 0">
                    @t('contact.submit_footer', 'Our procurement and commercial team will review your specifications and get in touch within 24 hours.')
                </p>
            </form>

        </div>

    </div>

    <!-- 3. Facility & SILC Cold Storage Location (Section 7.3) -->
    <div class="contact-location-section">
        <div class="contact-location-heading" style="text-align:center;margin-bottom:36px">
            <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:0.75rem;font-weight:800;letter-spacing:0.8px;padding:6px 16px;border-radius:30px;display:inline-flex;align-items:center;gap:6px;text-transform:uppercase;margin-bottom:12px">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                @t('contact.facility_eyebrow', 'FACILITY & COLLECTION CENTRE')
            </span>

            <h2 style="font-size:2.3rem;font-weight:800;color:#0f172a;margin:0 0 10px;letter-spacing:-0.5px">
                @t('contact.facility_title', 'Visit Our SILC Cold Hub')
            </h2>

            <p style="font-size:0.95rem;color:#64748b;max-width:680px;margin:0 auto;line-height:1.5">
                @t('contact.facility_subtitle', 'Centrally located at SiLC Iskandar Puteri, Johor. Open for customer visits, pre-arranged wholesale inspections, and walk-in counter collections.')
            </p>
        </div>

        <div class="location-split-grid">
            
            <!-- Left Column: Detailed Office & Facility Card -->
            <div class="office-info-card">
                <div>
                    <!-- Card Header -->
                    <div style="display:flex;align-items:center;gap:16px;margin-bottom:22px">
                        <div style="width:52px;height:52px;border-radius:14px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;flex-shrink:0">
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
                            <div style="font-size:1.2rem;font-weight:800;color:#0f172a;line-height:1.2;margin-bottom:2px">
                                @t('about.company_name_full', 'MST Import and Export Sdn Bhd')
                            </div>
                            <div style="font-size:0.82rem;font-weight:700;color:#1d4ed8;margin-bottom:4px">
                                镁嘉国际贸易有限公司
                            </div>
                            <div style="color:#64748b;font-weight:600;font-size:0.83rem">
                                @t('contact.facility_role', 'Central Cold Storage & Logistics Distribution Hub')
                            </div>
                        </div>
                    </div>

                    <div style="border-top:1px solid #f1f5f9;margin-bottom:22px"></div>

                    <!-- 4 Contact Points -->
                    <div style="display:flex;flex-direction:column;gap:20px">
                        
                        <!-- 1. Facility Address -->
                        <div style="display:flex;align-items:flex-start;gap:14px">
                            <div style="color:#2563eb;margin-top:2px">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:0.9rem;color:#0f172a;margin-bottom:2px">@t('contact.facility_address_label', 'Facility & Hub Address')</div>
                                <div style="font-size:0.85rem;color:#64748b;line-height:1.45">
                                    No. 7, Jalan SiLC 2/18, Kawasan Perindustrian SiLC, 79200 Iskandar Puteri, Johor, Malaysia
                                </div>
                            </div>
                        </div>

                        <!-- 2. Operating Hours -->
                        <div style="display:flex;align-items:flex-start;gap:14px">
                            <div style="color:#2563eb;margin-top:2px">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:0.9rem;color:#0f172a;margin-bottom:2px">@t('contact.operating_hours_label', 'Operating Hours')</div>
                                <div style="font-size:0.85rem;color:#0f172a;font-weight:600;line-height:1.4">
                                    @t('contact.hours_full', 'Monday – Saturday: 8:00 AM – 6:00 PM')
                                </div>
                                <div style="font-size:0.8rem;color:#64748b;margin-top:2px">
                                    @t('contact.sunday_closed', 'Sunday & Public Holidays: Closed')
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
                                <div style="font-weight:700;font-size:0.9rem;color:#0f172a;margin-bottom:2px">@t('contact.email_contacts_label', 'Email Contacts')</div>
                                <div style="font-size:0.85rem">
                                    <a href="mailto:mikatrading15@gmail.com" style="color:#2563eb;text-decoration:none;word-break:break-all;font-weight:600">
                                        mikatrading15@gmail.com
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Phone Hotlines -->
                        <div style="display:flex;align-items:flex-start;gap:14px">
                            <div style="color:#2563eb;margin-top:2px">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:0.9rem;color:#0f172a;margin-bottom:2px">@t('contact.phone_hotlines_label', 'Phone Hotlines')</div>
                                <div style="font-size:0.85rem;display:flex;flex-direction:column;gap:3px">
                                    <div>
                                        <a href="tel:0132800168" style="color:#2563eb;font-weight:700;text-decoration:none">
                                            013-280 0168
                                        </a>
                                    </div>
                                    <div>
                                        <a href="tel:01114360109" style="color:#2563eb;font-weight:600;text-decoration:none">
                                            011-1436 0109
                                        </a>
                                    </div>
                                    <div>
                                        <a href="https://wa.me/601112710260" target="_blank" rel="noopener" style="color:#2563eb;font-weight:600;text-decoration:none">
                                            011-1271 0260 (WhatsApp)
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Walk-In Collection Info Banner -->
                <div style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;padding:14px 18px;border-radius:12px;font-size:0.85rem;font-weight:600;display:flex;align-items:center;gap:10px;margin-top:24px">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>@t('contact.walkin_banner', 'Walk-in wholesale counter with QR instant collection tokens available during business hours.')</span>
                </div>
            </div>

            <!-- Right Column: Interactive Map Container -->
            <div class="map-embed-wrapper">
                
                <!-- Map Floating Card -->
                <div class="map-overlay-badge">
                    <div>
                        <div style="font-weight:800;font-size:0.98rem;color:#0f172a;line-height:1.25">@t('about.company_name_full', 'MST Import and Export Sdn Bhd')</div>
                        <div style="font-size:0.75rem;font-weight:700;color:#2563eb">镁嘉国际贸易有限公司</div>
                        <div class="map-badge-desc" style="font-size:0.78rem;color:#64748b;margin:4px 0 10px;line-height:1.35">No. 7, Jalan SiLC 2/18, SiLC Johor</div>
                    </div>
                    <div>
                        <a href="https://maps.app.goo.gl/jLMaDYCNJ6vfk376A" target="_blank" rel="noopener"
                           style="display:inline-flex;align-items:center;gap:6px;background:#2563eb;color:#ffffff;padding:7px 14px;border-radius:8px;font-size:0.78rem;font-weight:700;text-decoration:none;box-shadow:0 2px 8px rgba(37,99,235,0.3);white-space:nowrap">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                <polyline points="15 3 21 3 21 9"></polyline>
                                <line x1="10" y1="14" x2="21" y2="3"></line>
                            </svg>
                            <span>@t('contact.open_maps', 'Open in Google Maps')</span>
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

        const formGrid2 = document.querySelector('.consultation-form-grid-2');
        const catCol = document.querySelector('.field-col-category');
        const prodCol = document.querySelector('.field-col-product');

        // Close all dropdowns
        function closeAllDropdowns() {
            if (catContainer) catContainer.classList.remove('open');
            if (prodContainer) prodContainer.classList.remove('open');
            if (interestsContainer) interestsContainer.classList.remove('open');
            if (catCol) catCol.classList.remove('is-dropdown-open');
            if (prodCol) prodCol.classList.remove('is-dropdown-open');
            if (formGrid2) formGrid2.classList.remove('has-open-dropdown');
            const interestsGroup = document.getElementById('interestsFormGroup');
            if (interestsGroup) interestsGroup.classList.remove('has-open-dropdown');
        }

        // --- Category Dropdown Logic ---
        if (catTrigger) {
            catTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const wasOpen = catContainer.classList.contains('open');
                closeAllDropdowns();
                if (!wasOpen) {
                    catContainer.classList.add('open');
                    if (catCol) catCol.classList.add('is-dropdown-open');
                    if (formGrid2) formGrid2.classList.add('has-open-dropdown');
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
            catTriggerText.textContent = label || "{{ __t('contact.select_category_placeholder', 'Select category (optional)...') }}";
            if (value) {
                catTriggerText.classList.remove('placeholder');
            } else {
                catTriggerText.classList.add('placeholder');
            }

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
                    if (prodCol) prodCol.classList.add('is-dropdown-open');
                    if (formGrid2) formGrid2.classList.add('has-open-dropdown');
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
            if (!categoryName || categoryName === 'Customised Sourcing Request') {
                prodContainer.classList.add('disabled');
                prodTriggerText.textContent = categoryName === 'Customised Sourcing Request' 
                    ? "{{ __t('contact.customised_sourcing_req', 'Customised Sourcing Request') }}" 
                    : "{{ __t('contact.select_product_placeholder', 'Select product (optional)...') }}";
                prodTriggerText.classList.add('placeholder');
                prodHiddenInput.value = '';
                prodOptionsList.innerHTML = '<div class="searchable-no-results" id="productNoResults" style="display:none">No matching product found</div>';
                return;
            }

            prodContainer.classList.remove('disabled');
            const products = categoryProductsMap[categoryName] || [];

            let html = '';
            
            const defaultLabel = products.length > 0 
                ? 'All products in ' + categoryName + ' / General RFQ' 
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
                .replace(/&/g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // --- Multi-Select Interests Dropdown Logic ---
        const interestsContainer = document.getElementById('interestsMultiSelectContainer');
        const interestsTrigger = document.getElementById('interestsMultiSelectTrigger');
        const interestsDisplay = document.getElementById('interestsDisplay');
        const interestsPlaceholder = document.getElementById('interestsPlaceholder');
        const interestsCountBadge = document.getElementById('interestsCountBadge');
        const interestsSearchInput = document.getElementById('interestsSearchInput');
        const interestsOptionsList = document.getElementById('interestsOptionsList');
        const interestsNoResults = document.getElementById('interestsNoResults');
        const interestsFooterCount = document.getElementById('interestsFooterCount');
        const selectAllBtn = document.getElementById('selectAllInterests');
        const clearAllBtn = document.getElementById('clearAllInterests');
        const doneBtn = document.getElementById('interestsDoneBtn');

        function updateInterestsDisplay() {
            const checkedItems = interestsOptionsList.querySelectorAll('.multi-select-option-item input[type="checkbox"]:checked');
            const count = checkedItems.length;

            interestsFooterCount.textContent = count + ' selected';

            if (count === 0) {
                interestsDisplay.innerHTML = '<span class="searchable-selected-text placeholder">{{ __t('contact.select_interests_placeholder', 'Select interested categories / services...') }}</span>';
                interestsCountBadge.style.display = 'none';
                return;
            }

            interestsCountBadge.style.display = 'inline-block';
            interestsCountBadge.textContent = count + ' selected';

            let pillsHtml = '';
            const maxVisible = 2;

            checkedItems.forEach((cb, idx) => {
                const item = cb.closest('.multi-select-option-item');
                const val = item.getAttribute('data-value');
                const label = item.getAttribute('data-label');
                const icon = item.getAttribute('data-icon') || '📦';

                if (idx < maxVisible) {
                    pillsHtml += `
                        <span class="selected-tag-pill">
                            <span>${icon} ${escapeHtml(label)}</span>
                            <span class="selected-tag-remove" data-val="${escapeHtml(val)}" title="Remove">&times;</span>
                        </span>
                    `;
                }
            });

            if (count > maxVisible) {
                pillsHtml += `
                    <span class="selected-tag-pill" style="background:#e0f2fe;color:#0369a1;border-color:#bae6fd;">
                        +${count - maxVisible} more
                    </span>
                `;
            }

            interestsDisplay.innerHTML = pillsHtml;

            // Bind tag remove buttons
            interestsDisplay.querySelectorAll('.selected-tag-remove').forEach(rmBtn => {
                rmBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const removeVal = this.getAttribute('data-val');
                    const targetOption = interestsOptionsList.querySelector(`.multi-select-option-item[data-value="${removeVal}"]`);
                    if (targetOption) {
                        const targetCb = targetOption.querySelector('input[type="checkbox"]');
                        if (targetCb) {
                            targetCb.checked = false;
                            targetOption.classList.remove('selected');
                            updateInterestsDisplay();
                        }
                    }
                });
            });
        }

        if (interestsTrigger) {
            interestsTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const wasOpen = interestsContainer.classList.contains('open');
                closeAllDropdowns();
                if (!wasOpen) {
                    interestsContainer.classList.add('open');
                    const interestsGroup = document.getElementById('interestsFormGroup');
                    if (interestsGroup) interestsGroup.classList.add('has-open-dropdown');
                    interestsSearchInput.value = '';
                    filterMultiSelectOptions('');
                    setTimeout(() => interestsSearchInput.focus(), 50);
                }
            });
        }

        if (interestsSearchInput) {
            interestsSearchInput.addEventListener('input', function() {
                filterMultiSelectOptions(this.value.trim().toLowerCase());
            });
            interestsSearchInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        function filterMultiSelectOptions(query) {
            const items = interestsOptionsList.querySelectorAll('.multi-select-option-item');
            let visibleCount = 0;
            items.forEach(item => {
                const label = (item.getAttribute('data-label') || '').toLowerCase();
                const desc = (item.querySelector('.multi-select-item-desc')?.textContent || '').toLowerCase();
                if (!query || label.includes(query) || desc.includes(query)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            interestsNoResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        // Option checkbox click handling
        interestsOptionsList.querySelectorAll('.multi-select-option-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // If clicked directly on the input checkbox, change event handles it
                if (e.target.tagName.toLowerCase() !== 'input') {
                    const cb = this.querySelector('input[type="checkbox"]');
                    if (cb) {
                        cb.checked = !cb.checked;
                        if (cb.checked) {
                            this.classList.add('selected');
                        } else {
                            this.classList.remove('selected');
                        }
                        updateInterestsDisplay();
                    }
                }
            });

            const cb = item.querySelector('input[type="checkbox"]');
            if (cb) {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        item.classList.add('selected');
                    } else {
                        item.classList.remove('selected');
                    }
                    updateInterestsDisplay();
                });
            }
        });

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                interestsOptionsList.querySelectorAll('.multi-select-option-item').forEach(item => {
                    const cb = item.querySelector('input[type="checkbox"]');
                    if (cb) cb.checked = true;
                    item.classList.add('selected');
                });
                updateInterestsDisplay();
            });
        }

        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                interestsOptionsList.querySelectorAll('.multi-select-option-item').forEach(item => {
                    const cb = item.querySelector('input[type="checkbox"]');
                    if (cb) cb.checked = false;
                    item.classList.remove('selected');
                });
                updateInterestsDisplay();
            });
        }

        if (doneBtn) {
            doneBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                closeAllDropdowns();
            });
        }

        // Initialize display from initial checkboxes
        updateInterestsDisplay();

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
                <div style="font-family:sans-serif;padding:6px;min-width:220px">
                    <div style="font-weight:800;font-size:0.95rem;color:#0f172a;margin-bottom:2px">{{ __t('about.company_name_full', 'MST Import and Export Sdn Bhd') }}</div>
                    <div style="font-size:0.78rem;font-weight:700;color:#2563eb;margin-bottom:4px">镁嘉国际贸易有限公司</div>
                    <div style="color:#64748b;font-size:0.82rem;line-height:1.4">No. 7, Jalan SiLC 2/18, Kawasan Perindustrian SiLC, 79200 Iskandar Puteri, Johor</div>
                    <div style="margin-top:8px">
                        <a href="https://maps.app.goo.gl/jLMaDYCNJ6vfk376A" target="_blank" rel="noopener" style="color:#2563eb;font-weight:700;font-size:0.8rem;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                            <span>{{ __t('contact.get_directions', 'Get Directions') }}</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            `).bindTooltip("MST Import & Export (SiLC Hub)", { direction: "top", offset: [0, -36] });

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
