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

        .admin-sidebar {
            background: #ffffff !important;
            border-right: 1px solid #e2e8f0 !important;
            width: 250px !important;
            padding: 16px 0 !important;
            box-shadow: none !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            height: 100vh !important;
            overflow-y: auto !important;
            z-index: 100 !important;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .admin-main {
            margin-left: 250px !important;
            padding: 28px 36px !important;
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
                width: 280px !important;
                max-width: 85vw !important;
                transform: translateX(-100%) !important;
                box-shadow: 0 0 35px rgba(0, 0, 0, 0.25) !important;
                z-index: 9999 !important;
                background: #ffffff !important;
                overflow-y: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }

            .admin-sidebar.mobile-open {
                transform: translateX(0) !important;
            }

            .admin-sidebar-backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.5);
                backdrop-filter: blur(4px);
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
                padding: 16px 14px !important;
            }

            .admin-mobile-header {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                position: sticky !important;
                top: 0 !important;
                z-index: 90 !important;
            }

            .admin-sidebar-close-btn {
                display: flex !important;
                align-items: center;
                justify-content: center;
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
            padding: 4px 18px 6px !important;
            font-size: 0.68rem !important;
            font-weight: 600 !important;
            color: #94a3b8 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.08em !important;
        }

        .sidebar-link {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            padding: 7px 12px !important;
            margin: 1px 12px !important;
            border-radius: 6px !important;
            font-size: 0.84rem !important;
            font-weight: 500 !important;
            color: #475569 !important;
            text-decoration: none !important;
            transition: all 0.12s ease !important;
            position: relative !important;
            background: transparent !important;
        }

        .sidebar-link:hover {
            color: #1d4ed8 !important;
            background: #eff6ff !important;
        }

        .sidebar-link.active {
            color: #1d4ed8 !important;
            background: #eff6ff !important;
            font-weight: 600 !important;
            border: 1px solid #bfdbfe !important;
            box-shadow: 0 1px 2px rgba(29, 78, 216, 0.05) !important;
        }

        .sidebar-link.active::before {
            display: none !important;
        }

        .sidebar-link svg {
            color: #64748b;
            flex-shrink: 0;
            transition: color 0.12s;
        }

        .sidebar-link:hover svg {
            color: #1d4ed8;
        }

        .sidebar-link.active svg {
            color: #1d4ed8;
        }

        .sidebar-badge {
            margin-left: auto !important;
            background: #f1f5f9 !important;
            color: #475569 !important;
            padding: 2px 7px !important;
            border-radius: 9999px !important;
            font-size: 0.68rem !important;
            font-weight: 600 !important;
            border: 1px solid #e2e8f0 !important;
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
    </style>
    @stack('styles')
</head>

<body>

    <!-- Responsive Mobile Header -->
    <div class="admin-mobile-header" style="background:#ffffff;border-bottom:1px solid #e2e8f0;padding:12px 18px">
        <div style="display:flex;align-items:center;gap:10px">
            <button type="button" class="mobile-toggle" onclick="toggleAdminSidebar()"
                style="display:flex;border:none;background:transparent;cursor:pointer" aria-label="Toggle Navigation">
                <span></span><span></span><span></span>
            </button>
            <div style="display:flex;align-items:center;gap:8px">
                <img src="{{ asset('images/logo.webp') }}" alt="Mika"
                    style="height:32px;width:32px;object-fit:contain;border-radius:6px;">
                <span style="font-weight:700;color:#0f172a;font-size:1.05rem">Mika</span>
                <span
                    style="font-size:0.65rem;background:#f1f5f9;color:#64748b;font-weight:600;padding:2px 6px;border-radius:4px;border:1px solid #e2e8f0">ADMIN</span>
            </div>
        </div>
        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" style="font-size:0.8rem">View Store</a>
    </div>

    <!-- Backdrop for Mobile Sidebar Drawer -->
    <div class="admin-sidebar-backdrop" onclick="toggleAdminSidebar()"></div>

    <div class="admin-layout">
        <!-- Minimalist Sidebar -->
        <aside class="admin-sidebar">
            <!-- Logo / Brand Header -->
            <div class="admin-logo"
                style="padding:4px 18px 16px;border-bottom:1px solid #f1f5f9;margin-bottom:12px;display:flex;align-items:center;justify-content:space-between">
                <div style="display:flex;align-items:center;gap:10px">
                    <img src="{{ asset('images/logo.webp') }}" alt="Mika"
                        style="height:36px;width:36px;object-fit:contain;border-radius:8px;">
                    <div>
                        <div style="font-weight:700;font-size:0.95rem;color:#0f172a;line-height:1.2">Mika
                        </div>
                        <div style="font-size:0.68rem;color:#94a3b8;font-weight:500">Admin Console</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px">
                    <span
                        style="font-size:0.65rem;background:#f1f5f9;color:#64748b;font-weight:600;padding:2px 6px;border-radius:4px;border:1px solid #e2e8f0">v2.4</span>
                    <button type="button" class="admin-sidebar-close-btn" onclick="toggleAdminSidebar()"
                        style="display:none;border:none;background:transparent;cursor:pointer;font-size:1.25rem;color:#64748b;padding:4px 6px;line-height:1"
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

            <!-- Bottom Actions -->
            <div
                style="padding:14px 18px;margin-top:auto;border-top:1px solid #f1f5f9;display:flex;flex-direction:column;gap:4px">
                <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path
                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                        </path>
                    </svg>
                    <span>View Website</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" class="sidebar-link"
                        style="width:100%;text-align:left;color:#ef4444;cursor:pointer;border:none">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.8">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
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
    </script>

    @stack('scripts')
</body>

</html>