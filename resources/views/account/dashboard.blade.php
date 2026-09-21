@extends('layouts.app')
@section('title', __t('account.dashboard_meta_title', 'My Dashboard — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd')))

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:4px">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">@t('account.breadcrumb_home', '🏠 Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <a href="{{ route('account.dashboard') }}" style="color:#bae6fd;text-decoration:none">@t('account.breadcrumb_account', 'My Account')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('account.nav_dashboard', 'Dashboard')</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        @t('account.customer_portal_badge', '👋 Customer Portal')
                    </span>
                    <span style="color:#bae6fd;font-size:0.78rem">@t('account.portal_tagline', 'Cold-Chain Seafood Management')</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,1.95rem);margin-bottom:4px;letter-spacing:-0.02em">
                    {{ __t('account.welcome_back', 'Welcome back, :name!', ['name' => explode(' ', auth()->user()->name)[0]]) }}
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.88rem;max-width:680px;line-height:1.4;margin:0">
                    @t('account.dashboard_subtitle', 'Manage your purchases, check fulfillment statuses, and access your profile preferences.')
                </p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                <span class="group-badge group-{{ auth()->user()->customer_group }}" style="font-size:0.78rem;padding:5px 12px;border-radius:20px;border:1px solid rgba(255,255,255,0.25);background:rgba(255,255,255,0.12);color:#ffffff;font-weight:700">
                    {{ __t('account.tier_' . auth()->user()->customer_group, ucfirst(auth()->user()->customer_group)) }} @t('account.tier_suffix', 'Tier')
                </span>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-sm" style="background:#2563eb;color:#ffffff;border:1px solid #3b82f6;border-radius:10px;font-weight:700;box-shadow:0 2px 8px rgba(37,99,235,0.35);display:inline-flex;align-items:center;gap:6px">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span>@t('account.shop_catalog_btn', 'Shop Catalog')</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div style="padding-top:var(--space-8);padding-bottom:var(--space-16);background:#f8fafc;min-height:calc(100vh - 220px)">
    <div class="container dashboard-container" style="max-width:1160px">
        <!-- Account Sub-navigation Pills -->
        <div class="profile-nav-pills" style="margin-bottom:20px;display:flex;gap:8px;overflow-x:auto;padding-bottom:6px">
            <a href="{{ route('account.dashboard') }}" class="profile-nav-pill active">
                <span>📊</span>
                <span>@t('account.nav_dashboard', 'Dashboard')</span>
            </a>
            <a href="{{ route('account.orders') }}" class="profile-nav-pill">
                <span>📦</span>
                <span>@t('account.nav_orders', 'My Orders')</span>
            </a>
            <a href="{{ route('account.profile') }}" class="profile-nav-pill">
                <span>👤</span>
                <span>@t('account.nav_profile', 'Profile Settings')</span>
            </a>
            @if(auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                <a href="{{ route('quotations.index') }}" class="profile-nav-pill">
                    <span>📝</span>
                    <span>@t('account.nav_rfqs', 'My RFQs')</span>
                </a>
            @endif
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="profile-nav-pill" style="border-color:#bfdbfe;background:#eff6ff;color:var(--seagreen-700)">
                    <span>⚡</span>
                    <span>@t('account.nav_admin', 'Admin Panel')</span>
                </a>
            @endif
        </div>

        <!-- Quick Stats Grid -->
        <div class="dashboard-stats-grid">
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon-wrap dashboard-stat-icon-blue">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                </div>
                <div class="dashboard-stat-body">
                    <div class="dashboard-stat-number">{{ auth()->user()->orders()->count() }}</div>
                    <div class="dashboard-stat-label">@t('account.stat_total_orders', 'Total Orders')</div>
                </div>
            </div>

            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon-wrap dashboard-stat-icon-green">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div class="dashboard-stat-body">
                    <div class="dashboard-stat-number">{{ auth()->user()->orders()->where('status', 'delivered')->count() }}</div>
                    <div class="dashboard-stat-label">@t('account.stat_delivered', 'Delivered')</div>
                </div>
            </div>

            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon-wrap dashboard-stat-icon-amber">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
                <div class="dashboard-stat-body">
                    <div class="dashboard-stat-number">RM {{ number_format(auth()->user()->orders()->where('payment_status','paid')->sum('total'), 2) }}</div>
                    <div class="dashboard-stat-label">@t('account.stat_total_spent', 'Total Spent')</div>
                </div>
            </div>

            @if(auth()->user()->customer_group === 'trading')
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon-wrap dashboard-stat-icon-purple">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <div class="dashboard-stat-body">
                    <div class="dashboard-stat-number">{{ auth()->user()->quotations()->count() }}</div>
                    <div class="dashboard-stat-label">@t('account.stat_rfqs_submitted', 'RFQs Submitted')</div>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content 2-Column Grid (Stacks on Mobile) -->
        <div class="dashboard-main-grid">

            <!-- Left: Recent Orders -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h2 class="dashboard-card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--seagreen-700)"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        <span>@t('account.recent_orders_title', 'Recent Orders')</span>
                    </h2>
                    <a href="{{ route('account.orders') }}" class="btn btn-secondary btn-sm" style="border-radius:8px;font-size:0.8rem">
                        {{ __t('account.view_all_count', 'View All (:count)', ['count' => auth()->user()->orders()->count()]) }}
                    </a>
                </div>

                @if($orders->count() > 0)
                    <!-- Desktop Orders List -->
                    <div class="dashboard-orders-desktop">
                        @foreach($orders as $order)
                        <div class="dashboard-order-row">
                            <div class="dashboard-order-left">
                                <a href="{{ route('account.orders.show', $order) }}" class="dashboard-order-id">
                                    {{ $order->order_number }}
                                </a>
                                <div class="dashboard-order-meta">
                                    {{ $order->created_at->format('d M Y, h:i A') }} · {{ $order->items->count() > 1 ? __t('account.items_count_multi', ':count items', ['count' => $order->items->count()]) : __t('account.items_count_single', ':count item', ['count' => $order->items->count()]) }}
                                </div>
                            </div>
                            <div class="dashboard-order-right">
                                {!! $order->status_badge !!}
                                <span class="dashboard-order-price">RM {{ number_format($order->total, 2) }}</span>
                                <div class="dashboard-order-actions">
                                    <a href="{{ route('account.orders.show', $order) }}" class="btn btn-secondary btn-sm" style="border-radius:8px;padding:6px 12px;font-size:0.8rem">
                                        @t('account.btn_view', 'View')
                                    </a>
                                    <form action="{{ route('account.orders.reorder', $order) }}" method="POST" style="margin:0">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;padding:6px 12px;font-size:0.8rem">
                                            @t('account.btn_reorder', 'Reorder')
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Mobile Orders Cards -->
                    <div class="dashboard-orders-mobile">
                        @foreach($orders as $order)
                        <div class="dashboard-mobile-order-card">
                            <div class="dashboard-mobile-order-head">
                                <a href="{{ route('account.orders.show', $order) }}" class="dashboard-order-id">
                                    {{ $order->order_number }}
                                </a>
                                {!! $order->status_badge !!}
                            </div>
                            <div class="dashboard-mobile-order-meta">
                                📅 {{ $order->created_at->format('d M Y') }} · 📦 {{ $order->items->count() > 1 ? __t('account.items_count_multi', ':count items', ['count' => $order->items->count()]) : __t('account.items_count_single', ':count item', ['count' => $order->items->count()]) }}
                            </div>
                            <div class="dashboard-mobile-order-foot">
                                <div>
                                    <div style="font-size:0.7rem;color:var(--gray-500);text-transform:uppercase;font-weight:700">@t('account.label_total', 'Total')</div>
                                    <div class="dashboard-mobile-order-price">RM {{ number_format($order->total, 2) }}</div>
                                </div>
                                <div class="dashboard-mobile-order-btns">
                                    <a href="{{ route('account.orders.show', $order) }}" class="btn btn-secondary btn-sm">
                                        @t('account.btn_view', 'View')
                                    </a>
                                    <form action="{{ route('account.orders.reorder', $order) }}" method="POST" style="margin:0">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            @t('account.btn_reorder', 'Reorder')
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state" style="padding:48px 24px;text-align:center">
                        <div style="font-size:3rem;margin-bottom:12px">📦</div>
                        <h3 style="font-size:1.15rem;font-weight:700;color:var(--seagreen-900);margin-bottom:6px">@t('account.empty_orders_title', 'No Orders Yet')</h3>
                        <p style="color:var(--gray-500);font-size:0.88rem;margin-bottom:20px">@t('account.empty_orders_desc', 'Browse our premium frozen seafood selection and place your first order.')</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary" style="border-radius:10px;padding:10px 22px">@t('account.btn_start_shopping', 'Start Shopping')</a>
                    </div>
                @endif
            </div>

            <!-- Right: Quick Actions & Account Info -->
            <div style="display:flex;flex-direction:column;gap:20px">

                <!-- Quick Actions Card -->
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h2 class="dashboard-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--seagreen-700)"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                            <span>@t('account.quick_actions_title', 'Quick Actions')</span>
                        </h2>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="dashboard-action-links">
                            <a href="{{ route('shop.index') }}" class="dashboard-action-btn dashboard-action-btn-primary">
                                <span style="display:inline-flex;align-items:center;gap:10px">
                                    <span>🛒</span>
                                    <span>@t('account.action_browse_shop', 'Browse Shop')</span>
                                </span>
                                <span>→</span>
                            </a>
                            <a href="{{ route('account.orders') }}" class="dashboard-action-btn dashboard-action-btn-secondary">
                                <span style="display:inline-flex;align-items:center;gap:10px">
                                    <span>📦</span>
                                    <span>@t('account.action_past_orders', 'My Past Orders')</span>
                                </span>
                                <span style="font-size:0.8rem;color:var(--gray-400)">{{ auth()->user()->orders()->count() }}</span>
                            </a>
                            <a href="{{ route('account.profile') }}" class="dashboard-action-btn dashboard-action-btn-secondary">
                                <span style="display:inline-flex;align-items:center;gap:10px">
                                    <span>👤</span>
                                    <span>@t('account.action_profile_settings', 'Profile Settings')</span>
                                </span>
                                <span>⚙️</span>
                            </a>
                            @if(auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                            <a href="{{ route('quotations.create') }}" class="dashboard-action-btn dashboard-action-btn-warning">
                                <span style="display:inline-flex;align-items:center;gap:10px">
                                    <span>📝</span>
                                    <span>@t('account.action_request_quote', 'Request Wholesale Quote')</span>
                                </span>
                                <span>+</span>
                            </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="dashboard-action-btn dashboard-action-btn-secondary" style="border-color:#bfdbfe;background:#eff6ff;color:var(--seagreen-700)">
                                <span style="display:inline-flex;align-items:center;gap:10px">
                                    <span>⚡</span>
                                    <span>@t('account.action_admin_portal', 'Admin Portal')</span>
                                </span>
                                <span>→</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Account Info Card -->
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h2 class="dashboard-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--seagreen-700)"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span>@t('account.account_details_title', 'Account Details')</span>
                        </h2>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="dashboard-info-rows">
                            <div class="dashboard-info-row">
                                <span class="dashboard-info-label">@t('account.field_account_name', 'Account Name')</span>
                                <span class="dashboard-info-value">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="dashboard-info-row">
                                <span class="dashboard-info-label">@t('account.field_email_address', 'Email Address')</span>
                                <span class="dashboard-info-value">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="dashboard-info-row">
                                <span class="dashboard-info-label">@t('account.field_contact_phone', 'Contact Phone')</span>
                                <span class="dashboard-info-value">{{ auth()->user()->phone ?? '—' }}</span>
                            </div>
                            @if(auth()->user()->company_name)
                            <div class="dashboard-info-row">
                                <span class="dashboard-info-label">@t('account.field_company', 'Company')</span>
                                <span class="dashboard-info-value">{{ auth()->user()->company_name }}</span>
                            </div>
                            @endif
                            <div class="dashboard-info-row">
                                <span class="dashboard-info-label">@t('account.field_account_status', 'Account Status')</span>
                                <span class="dashboard-info-value">
                                    <span class="badge {{ auth()->user()->isApproved() ? 'badge-success' : 'badge-warning' }}" style="font-size:0.75rem;padding:3px 10px;border-radius:6px">
                                        {{ __t('account.status_' . auth()->user()->approval_status, ucfirst(auth()->user()->approval_status)) }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
