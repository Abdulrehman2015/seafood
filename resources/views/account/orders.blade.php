@extends('layouts.app')
@section('title', __t('account.orders_meta_title', 'My Orders — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd')))

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
            <span style="font-weight:600;color:#ffffff">@t('account.nav_orders', 'My Orders')</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        @t('account.orders_tag_badge', '📦 Cold-Chain Order History')
                    </span>
                    <span style="color:#bae6fd;font-size:0.78rem">@t('account.orders_tag_sub', 'Real-time status & tracking')</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,1.95rem);margin-bottom:4px;letter-spacing:-0.02em">
                    @t('account.orders_page_title', 'My Orders')
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.88rem;max-width:680px;line-height:1.4;margin:0">
                    @t('account.orders_page_subtitle', 'View past purchases, track fulfillment status, and easily reorder fresh seafood.')
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <a href="{{ route('account.dashboard') }}" class="btn btn-secondary btn-sm" style="background:rgba(255,255,255,0.12);color:#ffffff;border:1px solid rgba(255,255,255,0.25);border-radius:10px;font-weight:600">
                    @t('account.back_to_dashboard', '← Dashboard')
                </a>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-sm" style="background:#2563eb;color:#ffffff;border:1px solid #3b82f6;border-radius:10px;font-weight:700;box-shadow:0 2px 8px rgba(37,99,235,0.35);display:inline-flex;align-items:center;gap:6px">
                    <span>@t('account.btn_new_order', '+ New Order')</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div style="padding-top:var(--space-8);padding-bottom:var(--space-16);background:#f8fafc;min-height:calc(100vh - 220px)">
    <div class="container orders-container" style="max-width:1160px">
        <!-- Account Sub-navigation Pills -->
        <div class="profile-nav-pills" style="margin-bottom:20px;display:flex;gap:8px;overflow-x:auto;padding-bottom:6px">
            <a href="{{ route('account.dashboard') }}" class="profile-nav-pill">
                <span>📊</span>
                <span>@t('account.nav_dashboard', 'Dashboard')</span>
            </a>
            <a href="{{ route('account.orders') }}" class="profile-nav-pill active">
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

        <div class="orders-card">
            @if($orders->count() > 0)
                <!-- Desktop Table View (> 768px) -->
                <div class="orders-desktop-table">
                    <div class="table-wrapper" style="border-radius:14px;border:1px solid var(--gray-200);overflow:hidden">
                        <table class="table" style="margin-bottom:0">
                            <thead>
                                <tr>
                                    <th>@t('account.th_order_number', 'Order Number')</th>
                                    <th>@t('account.th_date', 'Date')</th>
                                    <th>@t('account.th_fulfillment', 'Fulfillment')</th>
                                    <th>@t('account.th_items', 'Items')</th>
                                    <th>@t('account.th_payment', 'Payment')</th>
                                    <th>@t('account.th_status', 'Status')</th>
                                    <th>@t('account.th_total', 'Total')</th>
                                    <th style="text-align:right">@t('account.th_action', 'Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('account.orders.show', $order) }}" class="font-bold" style="text-decoration:none;color:var(--seagreen-700)">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="text-sm text-muted">
                                        {{ $order->created_at->format('d M Y, h:i A') }}
                                    </td>
                                    <td>
                                        @if($order->fulfillment_type === 'self_collection')
                                            <span class="badge badge-self-collection">@t('account.badge_self_collection', '🏪 Self-Collection')</span>
                                        @else
                                            <span class="badge badge-delivery">@t('account.badge_delivery', '🚚 Delivery')</span>
                                        @endif
                                    </td>
                                    <td class="text-sm">
                                        {{ $order->items->count() > 1 ? __t('account.items_count_multi', ':count items', ['count' => $order->items->count()]) : __t('account.items_count_single', ':count item', ['count' => $order->items->count()]) }}
                                    </td>
                                    <td>
                                        @if($order->payment_status === 'paid')
                                            <span class="badge badge-success">@t('account.badge_paid', '✓ Paid')</span>
                                        @else
                                            <span class="badge badge-warning">@t('account.badge_pending_payment', 'Pending')</span>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $order->status_badge !!}
                                    </td>
                                    <td class="font-bold" style="color:var(--seagreen-800);font-size:0.95rem">
                                        RM {{ number_format($order->total, 2) }}
                                    </td>
                                    <td style="text-align:right">
                                        <div style="display:inline-flex;gap:var(--space-2)">
                                            <a href="{{ route('account.orders.show', $order) }}" class="btn btn-secondary btn-sm" style="border-radius:8px">
                                                @t('account.btn_view', 'View')
                                            </a>
                                            <form action="{{ route('account.orders.reorder', $order) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm" title="Add items back to cart" style="border-radius:8px">
                                                    @t('account.btn_reorder', 'Reorder')
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View (<= 768px) -->
                <div class="orders-mobile-cards">
                    @foreach($orders as $order)
                    <div class="order-mobile-card">
                        <div class="order-mobile-header">
                            <div>
                                <a href="{{ route('account.orders.show', $order) }}" class="order-mobile-num">
                                    {{ $order->order_number }}
                                </a>
                                <div class="order-mobile-date">
                                    📅 {{ $order->created_at->format('d M Y, h:i A') }}
                                </div>
                            </div>
                            <div>
                                {!! $order->status_badge !!}
                            </div>
                        </div>

                        <div class="order-mobile-chips">
                            @if($order->fulfillment_type === 'self_collection')
                                <span class="badge badge-self-collection">@t('account.badge_self_collection', '🏪 Self-Collection')</span>
                            @else
                                <span class="badge badge-delivery">@t('account.badge_delivery', '🚚 Delivery')</span>
                            @endif

                            @if($order->payment_status === 'paid')
                                <span class="badge badge-success">@t('account.badge_paid', '✓ Paid')</span>
                            @else
                                <span class="badge badge-warning">@t('account.badge_pending_payment', 'Pending Payment')</span>
                            @endif

                            <span class="badge badge-secondary" style="font-size:0.75rem">
                                📦 {{ $order->items->count() > 1 ? __t('account.items_count_multi', ':count items', ['count' => $order->items->count()]) : __t('account.items_count_single', ':count item', ['count' => $order->items->count()]) }}
                            </span>
                        </div>

                        <div class="order-mobile-footer">
                            <div>
                                <div class="order-mobile-total-label">@t('account.mobile_total_label', 'Total Amount')</div>
                                <div class="order-mobile-total-val">RM {{ number_format($order->total, 2) }}</div>
                            </div>
                            <div class="order-mobile-actions">
                                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-secondary btn-sm">
                                    @t('account.btn_view', 'View')
                                </a>
                                <form action="{{ route('account.orders.reorder', $order) }}" method="POST">
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

                <div style="padding:var(--space-6) 0 0;display:flex;justify-content:center">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="empty-state" style="padding:var(--space-16) 0;text-align:center">
                    <div style="font-size:3.5rem;margin-bottom:var(--space-4)">📦</div>
                    <h3 style="font-family:var(--font-heading);font-size:1.4rem;margin-bottom:var(--space-2);color:var(--seagreen-900)">@t('account.empty_orders_page_title', 'No Orders Found')</h3>
                    <p class="text-muted text-sm mb-6">@t('account.empty_orders_page_desc', 'You have not placed any seafood orders yet.')</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary" style="padding:12px 28px;border-radius:12px;font-weight:700">
                        @t('account.btn_browse_catalogue', '🛒 Browse Seafood Catalogue')
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
