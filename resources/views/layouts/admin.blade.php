<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Mika Import and Export SDN Bhd</title>
    <link rel="icon" type="image/webp" href="{{ asset('images/favicon.webp') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">
    <style>
        /* Dedicated Side-by-Side Currency Input Group */
        .currency-input-group {
            display: flex !important;
            align-items: stretch !important;
            width: 100% !important;
            position: relative;
        }
        .currency-badge {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 14px !important;
            background: #e2e8f0 !important;
            border: 1.5px solid #cbd5e1 !important;
            border-right: none !important;
            border-top-left-radius: 10px !important;
            border-bottom-left-radius: 10px !important;
            font-weight: 700 !important;
            color: #475569 !important;
            font-size: 0.88rem !important;
            letter-spacing: 0.02em !important;
            user-select: none !important;
            flex-shrink: 0 !important;
        }
        .currency-input-group .form-control {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            margin: 0 !important;
            flex: 1 1 auto !important;
            min-width: 0 !important;
            padding-left: 12px !important;
        }
        .currency-input-group:focus-within .currency-badge {
            border-color: #2563eb !important;
            background: #eff6ff !important;
            color: #2563eb !important;
        }
        .action-buttons-card {
            display: flex !important;
            flex-direction: column !important;
            gap: 14px !important;
        }
        .action-buttons-card .btn {
            margin: 0 !important;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        .admin-layout {
            display: block !important;
            min-height: 100vh !important;
            width: 100% !important;
        }

        .table-wrapper {
            width: 100% !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }

        .admin-sidebar {
            background: linear-gradient(135deg, #06152b 0%, #0c2146 40%, #14356b 75%, #1d4ed8 100%) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
            width: 250px !important;
            padding: 16px 0 !important;
            box-shadow: 2px 0 16px rgba(6, 21, 43, 0.25) !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            height: 100vh !important;
            overflow-y: auto !important;
            z-index: 100 !important;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.15);
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.16);
            border-radius: 4px;
        }

        .admin-main {
            margin-left: 250px !important;
            padding: 24px 28px !important;
            background: #f8fafc !important;
            min-height: 100vh !important;
            box-sizing: border-box !important;
        }

        .admin-topbar {
            border-bottom: 1px solid #e2e8f0 !important;
            padding-bottom: 18px !important;
            margin-bottom: 24px !important;
        }

        .admin-page-title {
            font-family: inherit !important;
            font-size: 1.35rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            letter-spacing: -0.02em !important;
        }

        /* Responsive Admin Navigation & Layout */
        @media (min-width: 1025px) {
            .admin-mobile-header {
                display: none !important;
            }

            .admin-sidebar-backdrop {
                display: none !important;
            }

            .admin-sidebar-close-btn {
                display: none !important;
            }

            .admin-sidebar {
                transform: none !important;
            }
        }

        @media (max-width: 1024px) {
            .admin-layout {
                display: block !important;
            }

            .admin-sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                bottom: 0 !important;
                height: 100vh !important;
                height: 100dvh !important;
                width: 270px !important;
                max-width: 85vw !important;
                transform: translateX(-100%) !important;
                box-shadow: 0 0 35px rgba(0, 0, 0, 0.45) !important;
                z-index: 9999 !important;
                background: linear-gradient(180deg, #06152b 0%, #0a1f3f 40%, #0e2752 75%, #123368 100%) !important;
                border-right: 1px solid rgba(255, 255, 255, 0.1) !important;
                overflow-y: auto !important;
                -webkit-overflow-scrolling: touch !important;
                transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }

            .admin-sidebar.mobile-open {
                transform: translateX(0) !important;
            }

            .admin-sidebar-backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(6, 21, 43, 0.65);
                backdrop-filter: blur(5px);
                z-index: 9990;
            }

            .admin-sidebar-backdrop.active {
                display: block !important;
            }

            body.admin-sidebar-open {
                overflow: hidden !important;
            }

            .admin-main {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 16px 12px !important;
            }

            .admin-mobile-header {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                position: sticky !important;
                top: 0 !important;
                z-index: 90 !important;
                background: linear-gradient(135deg, #06152b 0%, #0c2146 55%, #14356b 100%) !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
                box-shadow: 0 2px 12px rgba(6, 21, 43, 0.35) !important;
            }

            .admin-mobile-header .mobile-toggle span {
                background: #ffffff !important;
            }

            .admin-sidebar-close-btn {
                display: flex !important;
                align-items: center;
                justify-content: center;
                color: #ffffff !important;
            }

            .admin-topbar {
                margin-bottom: 16px !important;
                padding-bottom: 14px !important;
            }

            .admin-page-title {
                font-size: 1.25rem !important;
            }

            .admin-dashboard-grid {
                grid-template-columns: 1fr !important;
                gap: 16px !important;
            }

            .admin-stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10px !important;
                margin-bottom: 16px !important;
            }

            .admin-stats-grid .stat-card {
                padding: 12px 14px !important;
                border-radius: 12px !important;
            }

            .admin-stats-grid .stat-card:last-child:nth-child(odd) {
                grid-column: span 2 !important;
            }

            .admin-stats-grid .stat-icon {
                font-size: 1.35rem !important;
                margin-bottom: 4px !important;
            }

            .admin-stats-grid .stat-number {
                font-size: 1.35rem !important;
                line-height: 1.2 !important;
            }

            .admin-stats-grid .stat-label {
                font-size: 0.72rem !important;
            }

            .admin-table-desktop {
                display: none !important;
            }

            .admin-orders-mobile {
                display: flex !important;
            }

            .category-edit-grid {
                grid-template-columns: 1fr !important;
                gap: 18px !important;
            }
        }

        .sidebar-section-label {
            padding: 6px 18px 4px !important;
            font-size: 0.68rem !important;
            font-weight: 700 !important;
            color: #60a5fa !important;
            text-transform: uppercase !important;
            letter-spacing: 0.08em !important;
            opacity: 0.9 !important;
        }

        .sidebar-link {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            padding: 8px 12px !important;
            margin: 2px 10px !important;
            border-radius: 8px !important;
            font-size: 0.84rem !important;
            font-weight: 500 !important;
            color: #cbd5e1 !important;
            text-decoration: none !important;
            transition: all 0.15s ease !important;
            position: relative !important;
            background: transparent !important;
            border: 1px solid transparent !important;
        }

        .sidebar-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08) !important;
        }

        .sidebar-link.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.45) 0%, rgba(29, 78, 216, 0.3) 100%) !important;
            font-weight: 600 !important;
            border: 1px solid rgba(96, 165, 250, 0.45) !important;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25) !important;
        }

        .sidebar-link.active::before {
            display: none !important;
        }

        .sidebar-link svg {
            color: #94a3b8 !important;
            flex-shrink: 0 !important;
            transition: color 0.15s ease, transform 0.15s ease !important;
        }

        .sidebar-link:hover svg {
            color: #60a5fa !important;
            transform: scale(1.06) !important;
        }

        .sidebar-link.active svg {
            color: #38bdf8 !important;
        }

        .sidebar-badge {
            margin-left: auto !important;
            background: #ef4444 !important;
            color: #ffffff !important;
            padding: 2px 7px !important;
            border-radius: 9999px !important;
            font-size: 0.68rem !important;
            font-weight: 700 !important;
            border: none !important;
        }

        .card {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 10px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
        }

        .table thead th {
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
            font-size: 0.72rem !important;
            font-weight: 600 !important;
            color: #64748b !important;
            letter-spacing: 0.04em !important;
            text-transform: uppercase !important;
            padding: 12px 16px !important;
        }

        .table tbody td {
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 14px 16px !important;
        }

        .form-control,
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        select {
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            padding: 8px 12px !important;
            font-size: 0.875rem !important;
            transition: border-color 0.12s, box-shadow 0.12s !important;
            background: #ffffff !important;
        }

        .form-control:focus,
        input:focus,
        textarea:focus,
        select:focus {
            border-color: #1d4ed8 !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
        }

        .btn {
            border-radius: 6px !important;
            font-weight: 500 !important;
            font-size: 0.85rem !important;
            padding: 7px 14px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            cursor: pointer !important;
            transition: all 0.12s ease !important;
        }

        .btn-primary {
            background: #1d4ed8 !important;
            color: #ffffff !important;
            border: 1px solid #1d4ed8 !important;
            box-shadow: 0 1px 2px rgba(29, 78, 216, 0.15) !important;
        }

        .btn-primary:hover {
            background: #1e40af !important;
            border-color: #1e40af !important;
        }

        .btn-secondary {
            background: #ffffff !important;
            color: #334155 !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02) !important;
        }

        .btn-secondary:hover {
            background: #f8fafc !important;
            color: #0f172a !important;
            border-color: #94a3b8 !important;
        }

        .btn-danger {
            background: #ef4444 !important;
            color: #ffffff !important;
            border: 1px solid #ef4444 !important;
            box-shadow: 0 1px 2px rgba(239, 68, 68, 0.15) !important;
        }

        .btn-danger:hover {
            background: #dc2626 !important;
            border-color: #dc2626 !important;
        }

        /* ─── Global Top Header & Top-Right Profile / View Website ─────────── */
        .admin-global-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            margin-bottom: 22px;
            background: linear-gradient(135deg, #06152b 0%, #0c2146 40%, #14356b 75%, #1d4ed8 100%);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 4px 16px rgba(6, 21, 43, 0.18);
            gap: 16px;
        }

        .admin-header-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #f1f5f9;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .admin-pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .admin-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-left: auto;
        }

        .admin-view-site-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            white-space: nowrap;
        }

        .admin-view-site-btn:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: #93c5fd;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(29, 78, 216, 0.2);
        }

        /* Clear Cache Button */
        .admin-clear-cache-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            white-space: nowrap;
        }

        .admin-clear-cache-btn:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.22);
            border-color: #93c5fd;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(29, 78, 216, 0.2);
        }

        .admin-clear-cache-btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .admin-clear-cache-btn.success {
            background: rgba(16, 185, 129, 0.25);
            border-color: #10b981;
            color: #6ee7b7;
        }

        .admin-clear-cache-btn.error {
            background: rgba(239, 68, 68, 0.2);
            border-color: #ef4444;
            color: #fca5a5;
        }

        .admin-cache-spinner {
            width: 13px;
            height: 13px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin-cache 0.65s linear infinite;
        }

        @keyframes spin-cache {
            to { transform: rotate(360deg); }
        }

        .admin-profile-wrapper {
            position: relative;
        }

        .admin-profile-trigger {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 4px 12px 4px 5px;
            background: rgba(255, 255, 255, 0.12);
            border: 1.5px solid rgba(255, 255, 255, 0.22);
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .admin-profile-trigger:hover,
        .admin-profile-wrapper.open .admin-profile-trigger {
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.2);
        }

        .admin-profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 1.5px solid #60a5fa;
            flex-shrink: 0;
        }

        .admin-profile-meta {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.15;
        }

        .admin-profile-name {
            font-size: 0.84rem;
            font-weight: 700;
            color: #ffffff;
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-profile-tag {
            font-size: 0.65rem;
            font-weight: 700;
            color: #93c5fd;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-profile-chevron {
            color: #cbd5e1;
            transition: transform 0.2s ease;
        }

        .admin-profile-wrapper.open .admin-profile-chevron {
            transform: rotate(180deg);
            color: #93c5fd;
        }

        .admin-profile-popover {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 230px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            z-index: 200;
            padding: 6px;
            animation: fadeInPopover 0.15s ease;
        }

        .admin-profile-wrapper.open .admin-profile-popover {
            display: block;
        }

        @keyframes fadeInPopover {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .admin-popover-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 4px;
        }

        .admin-popover-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #2563eb;
            flex-shrink: 0;
        }

        .admin-popover-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-popover-email {
            font-size: 0.74rem;
            color: #64748b;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-popover-body {
            padding: 2px 0;
        }

        .admin-popover-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 500;
            color: #334155;
            text-decoration: none;
            transition: all 0.12s ease;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .admin-popover-item:hover {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .admin-popover-item svg {
            color: #64748b;
            flex-shrink: 0;
        }

        .admin-popover-item:hover svg {
            color: #1d4ed8;
        }

        .admin-popover-footer {
            border-top: 1px solid #f1f5f9;
            padding-top: 4px;
            margin-top: 4px;
        }

        .admin-popover-danger {
            color: #ef4444 !important;
        }

        .admin-popover-danger:hover {
            background: #fef2f2 !important;
            color: #dc2626 !important;
        }

        .admin-popover-danger svg {
            color: #ef4444 !important;
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Responsive Mobile Header -->
    <div class="admin-mobile-header" style="padding:10px 16px;display:flex;align-items:center;justify-content:space-between">
        <div style="display:flex;align-items:center;gap:10px">
            <button type="button" class="mobile-toggle" onclick="toggleAdminSidebar()"
                style="display:flex;border:none;background:transparent;cursor:pointer" aria-label="Toggle Navigation">
                <span></span><span></span><span></span>
            </button>
            <div style="display:flex;align-items:center;gap:8px">
                <img src="{{ asset('images/logo.webp') }}" alt="Mika"
                    style="height:28px;width:28px;object-fit:contain;border-radius:6px;">
                <span style="font-weight:700;color:#ffffff;font-size:0.95rem">Mika</span>
                <span style="font-size:0.65rem;background:rgba(255,255,255,0.15);color:#93c5fd;font-weight:700;padding:2px 7px;border-radius:4px;border:1px solid rgba(255,255,255,0.25)">ADMIN</span>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <a href="{{ route('home') }}" target="_blank" class="admin-view-site-btn" style="padding:5px 10px;font-size:0.75rem">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1 4-10z"></path>
                </svg>
                <span>Store</span>
            </a>
            <a href="{{ route('admin.profile.edit') }}" title="My Profile" style="display:flex;align-items:center">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width:30px;height:30px;border-radius:50%;object-fit:cover;border:1.5px solid #60a5fa">
            </a>
        </div>
    </div>

    <!-- Backdrop for Mobile Sidebar Drawer -->
    <div class="admin-sidebar-backdrop" onclick="toggleAdminSidebar()"></div>

    <div class="admin-layout">
        <!-- Minimalist Sidebar -->
        <aside class="admin-sidebar">
            <!-- Logo / Brand Header -->
            <div class="admin-logo"
                style="padding:4px 18px 16px;border-bottom:1px solid rgba(255,255,255,0.08);margin-bottom:12px;display:flex;align-items:center;justify-content:space-between">
                <div style="display:flex;align-items:center;gap:10px">
                    <img src="{{ asset('images/logo.webp') }}" alt="Mika"
                        style="height:36px;width:36px;object-fit:contain;border-radius:8px;">
                    <div>
                        <div style="font-weight:700;font-size:0.95rem;color:#ffffff;line-height:1.2">Mika</div>
                        <div style="font-size:0.68rem;color:#93c5fd;font-weight:500">Admin Console</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px">
                    <span
                        style="font-size:0.65rem;background:rgba(255,255,255,0.12);color:#93c5fd;font-weight:600;padding:2px 6px;border-radius:4px;border:1px solid rgba(255,255,255,0.2)">v2.4</span>
                    <button type="button" class="admin-sidebar-close-btn" onclick="toggleAdminSidebar()"
                        style="display:none;border:none;background:transparent;cursor:pointer;font-size:1.25rem;color:#ffffff;padding:4px 6px;line-height:1"
                        aria-label="Close Navigation">✕</button>
                </div>
            </div>

            <!-- Section: Overview -->
            <div class="sidebar-section" style="margin-bottom:14px">
                <div class="sidebar-section-label">Overview</div>
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Section: Catalogue & Media -->
            <div class="sidebar-section" style="margin-bottom:14px">
                <div class="sidebar-section-label">Catalogue & Media</div>
                <a href="{{ route('admin.products.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                        </path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <span>Products</span>
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Categories</span>
                </a>
                <a href="{{ route('admin.gallery.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <span>Media Gallery</span>
                </a>
            </div>

            <!-- Section: Customers -->
            <div class="sidebar-section" style="margin-bottom:14px">
                <div class="sidebar-section-label">Customers</div>
                <a href="{{ route('admin.customers.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.customers.*') && !request('status') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>All Customers</span>
                </a>
                <a href="{{ route('admin.customers.index', ['status' => 'pending']) }}"
                    class="sidebar-link {{ request('status') === 'pending' ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Pending Approvals</span>
                    @php $pending = \App\Models\User::whereIn('customer_group', ['wholesale', 'trading'])->where('approval_status', 'pending')->count(); @endphp
                    @if($pending > 0)
                        <span class="sidebar-badge">{{ $pending }}</span>
                    @endif
                </a>
            </div>

            <!-- Section: Orders -->
            <div class="sidebar-section" style="margin-bottom:14px">
                <div class="sidebar-section-label">Orders</div>
                <a href="{{ route('admin.orders.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span>All Orders</span>
                </a>
                <a href="{{ route('admin.quotations.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.quotations.*') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Quotations (RFQ)</span>
                    @php $rfq = \App\Models\Quotation::where('status', 'pending')->count(); @endphp
                    @if($rfq > 0)
                        <span class="sidebar-badge">{{ $rfq }}</span>
                    @endif
                </a>
            </div>

            <!-- Section: Communication & Settings -->
            <div class="sidebar-section" style="margin-bottom:14px">
                <div class="sidebar-section-label">Settings & SEO</div>
                <a href="{{ route('admin.messages.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <span>Inquiries</span>
                    @php $unreadCount = \App\Models\ContactMessage::unread()->count(); @endphp
                    @if($unreadCount > 0)
                        <span class="sidebar-badge">{{ $unreadCount }}</span>
                    @endif
                </a>
                <!-- Newsletter Subscribers Link -->
                <a href="{{ route('admin.newsletter.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                    </svg>
                    <span>Newsletter</span>
                    @php $subscriberCount = \App\Models\NewsletterSubscriber::where('status', 'active')->count(); @endphp
                    @if($subscriberCount > 0)
                        <span class="sidebar-badge"
                            style="background:#e0f2fe;color:#0369a1;border-color:#bae6fd">{{ $subscriberCount }}</span>
                    @endif
                </a>
                <!-- Customer Reviews Link -->
                <a href="{{ route('admin.reviews.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <polygon
                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                        </polygon>
                    </svg>
                    <span>Customer Reviews</span>
                    @php $pendingReviews = \App\Models\Review::where('status', 'pending')->count(); @endphp
                    @if($pendingReviews > 0)
                        <span class="sidebar-badge"
                            style="background:#fef3c7;color:#92400e;border-color:#fde68a">{{ $pendingReviews }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.settings.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path
                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                        </path>
                    </svg>
                    <span>Store Settings</span>
                </a>
                <!-- Email Templates Link -->
                <a href="{{ route('admin.emails.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.emails.*') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <span>Email Templates</span>
                </a>
                <!-- NEW: Page SEO Link -->
                <a href="{{ route('admin.page-seo.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.page-seo.*') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span>Page SEO</span>
                </a>
                <a href="{{ route('admin.walkin.qr') }}"
                    class="sidebar-link {{ request()->routeIs('admin.walkin.qr') ? 'active' : '' }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                        <path d="M14 14h2v2h-2z"></path>
                        <path d="M20 14h-2v2h2z"></path>
                        <path d="M14 20h2v-2h-2z"></path>
                        <path d="M20 20h-2v-2h2z"></path>
                    </svg>
                    <span>Walk-in QR Code</span>
                </a>
            </div>


        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Global Admin Top Header (Top-Right Profile & View Website) -->
            <header class="admin-global-header">
                <div class="admin-header-left">
                    <span class="admin-header-pill">
                        <span class="admin-pulse-dot"></span>
                        <span>Mika Online</span>
                    </span>
                </div>

                <div class="admin-header-right">
                    <!-- View Website Button (Top-Right near Profile) -->
                    <a href="{{ route('home') }}" target="_blank" class="admin-view-site-btn" title="Open storefront in a new tab">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                        <span>View Website</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                    </a>

                    <!-- Clear Cache Button -->
                    <button type="button" id="btnClearCache" class="admin-clear-cache-btn" title="Clear all Laravel caches">
                        <svg id="cacheIcon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                        <span id="cacheBtnLabel">Clear Cache</span>
                    </button>

                    <!-- Admin Profile Dropdown (Top-Right Header) -->
                    <div class="admin-profile-wrapper" id="adminProfileWrapper">
                        <button type="button" class="admin-profile-trigger" id="adminProfileTrigger" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="admin-profile-avatar">
                            <div class="admin-profile-meta">
                                <span class="admin-profile-name">{{ auth()->user()->name }}</span>
                                <span class="admin-profile-tag">Administrator</span>
                            </div>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="admin-profile-chevron">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>

                        <div class="admin-profile-popover" id="adminProfilePopover">
                            <div class="admin-popover-header">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="admin-popover-avatar">
                                <div style="min-width:0">
                                    <div class="admin-popover-name">{{ auth()->user()->name }}</div>
                                    <div class="admin-popover-email">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                            <div class="admin-popover-body">
                                <a href="{{ route('admin.profile.edit') }}" class="admin-popover-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span>My Profile &amp; Password</span>
                                </a>
                                <a href="{{ route('admin.settings.index') }}" class="admin-popover-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                    </svg>
                                    <span>Store Settings</span>
                                </a>
                            </div>
                            <div class="admin-popover-footer">
                                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                                    @csrf
                                    <button type="submit" class="admin-popover-item admin-popover-danger">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            @if(session('success') || session('error') || session('warning') || session('info') || session('status'))
                <div class="flash-container" style="position:relative;top:0;right:0;margin-bottom:18px">
                    @if(session('success'))
                        <div
                            style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:12px 16px;border-radius:8px;font-size:0.875rem;font-weight:500;display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                            <span style="display:flex;align-items:center;gap:8px">✓ {{ session('success') }}</span>
                            <button type="button" onclick="this.parentElement.remove()"
                                style="background:transparent;border:none;color:#166534;cursor:pointer;font-size:1rem;line-height:1">✕</button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div
                            style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:12px 16px;border-radius:8px;font-size:0.875rem;font-weight:500;display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                            <span style="display:flex;align-items:center;gap:8px">⚠️ {{ session('error') }}</span>
                            <button type="button" onclick="this.parentElement.remove()"
                                style="background:transparent;border:none;color:#991b1b;cursor:pointer;font-size:1rem;line-height:1">✕</button>
                        </div>
                    @endif
                    @if(session('warning'))
                        <div
                            style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;padding:12px 16px;border-radius:8px;font-size:0.875rem;font-weight:500;display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                            <span style="display:flex;align-items:center;gap:8px">⚠️ {{ session('warning') }}</span>
                            <button type="button" onclick="this.parentElement.remove()"
                                style="background:transparent;border:none;color:#92400e;cursor:pointer;font-size:1rem;line-height:1">✕</button>
                        </div>
                    @endif
                    @if(session('info') || session('status'))
                        <div
                            style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;padding:12px 16px;border-radius:8px;font-size:0.875rem;font-weight:500;display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                            <span style="display:flex;align-items:center;gap:8px">ℹ {{ session('info') ?? session('status') }}</span>
                            <button type="button" onclick="this.parentElement.remove()"
                                style="background:transparent;border:none;color:#1e40af;cursor:pointer;font-size:1rem;line-height:1">✕</button>
                        </div>
                    @endif
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.querySelector('.admin-sidebar');
            const backdrop = document.querySelector('.admin-sidebar-backdrop');
            if (sidebar) sidebar.classList.toggle('mobile-open');
            if (backdrop) backdrop.classList.toggle('active');
            document.body.classList.toggle('admin-sidebar-open');
        }

        // Close mobile sidebar on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.querySelector('.admin-sidebar');
                const backdrop = document.querySelector('.admin-sidebar-backdrop');
                if (sidebar && sidebar.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                    if (backdrop) backdrop.classList.remove('active');
                    document.body.classList.remove('admin-sidebar-open');
                }
            }
        });

        // Close mobile sidebar when clicking a navigation link on small screens
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.admin-sidebar .sidebar-link');
            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 1024) {
                        const sidebar = document.querySelector('.admin-sidebar');
                        const backdrop = document.querySelector('.admin-sidebar-backdrop');
                        if (sidebar && sidebar.classList.contains('mobile-open')) {
                            sidebar.classList.remove('mobile-open');
                            if (backdrop) backdrop.classList.remove('active');
                            document.body.classList.remove('admin-sidebar-open');
                        }
                    }
                });
            });

            // Admin Profile Dropdown Toggle
            const profileWrapper = document.getElementById('adminProfileWrapper');
            const profileTrigger = document.getElementById('adminProfileTrigger');

            if (profileWrapper && profileTrigger) {
                profileTrigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = profileWrapper.classList.toggle('open');
                    profileTrigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });

                document.addEventListener('click', function(e) {
                    if (!profileWrapper.contains(e.target)) {
                        profileWrapper.classList.remove('open');
                        profileTrigger.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // ── Clear Cache Button ─────────────────────────────────────
            const btnClearCache = document.getElementById('btnClearCache');
            if (btnClearCache) {
                const cacheIcon  = document.getElementById('cacheIcon');
                const cacheLbl   = document.getElementById('cacheBtnLabel');
                const origIcon   = cacheIcon.outerHTML;

                btnClearCache.addEventListener('click', async function () {
                    if (btnClearCache.disabled) return;

                    // Loading state
                    btnClearCache.disabled = true;
                    btnClearCache.classList.remove('success', 'error');
                    cacheIcon.outerHTML; // reference kept via id
                    document.getElementById('cacheIcon').replaceWith(
                        Object.assign(document.createElement('span'), { className: 'admin-cache-spinner' })
                    );
                    cacheLbl.textContent = 'Clearing…';

                    try {
                        const res  = await fetch('{{ route("admin.cache.clear") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            }
                        });
                        const data = await res.json();

                        // Success state
                        const spinner = btnClearCache.querySelector('.admin-cache-spinner');
                        if (spinner) {
                            const okSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                            okSvg.setAttribute('id', 'cacheIcon');
                            okSvg.setAttribute('width', '15');
                            okSvg.setAttribute('height', '15');
                            okSvg.setAttribute('viewBox', '0 0 24 24');
                            okSvg.setAttribute('fill', 'none');
                            okSvg.setAttribute('stroke', 'currentColor');
                            okSvg.setAttribute('stroke-width', '2.5');
                            okSvg.innerHTML = '<polyline points="20 6 9 17 4 12"></polyline>';
                            spinner.replaceWith(okSvg);
                        }
                        cacheLbl.textContent = data.success ? 'Cache Cleared!' : 'Failed';
                        btnClearCache.classList.add(data.success ? 'success' : 'error');

                    } catch (e) {
                        const spinner = btnClearCache.querySelector('.admin-cache-spinner');
                        if (spinner) spinner.remove();
                        cacheLbl.textContent = 'Error!';
                        btnClearCache.classList.add('error');
                    }

                    // Reset after 2.5s
                    setTimeout(() => {
                        btnClearCache.classList.remove('success', 'error');
                        btnClearCache.disabled = false;
                        const currentIcon = btnClearCache.querySelector('svg, span.admin-cache-spinner');
                        if (currentIcon) {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(origIcon, 'text/html');
                            currentIcon.replaceWith(doc.body.firstChild);
                        }
                        cacheLbl.textContent = 'Clear Cache';
                    }, 2500);
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>