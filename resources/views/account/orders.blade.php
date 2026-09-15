@extends('layouts.app')
@section('title', 'My Orders — ' . ($settings['store_name'] ?? 'Mika Import and Export SDN Bhd'))

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:4px">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 Home</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <a href="{{ route('account.dashboard') }}" style="color:#bae6fd;text-decoration:none">My Account</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">My Orders</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        📦 Cold-Chain Order History
                    </span>
                    <span style="color:#bae6fd;font-size:0.78rem">Real-time status &amp; tracking</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,1.95rem);margin-bottom:4px;letter-spacing:-0.02em">
                    My Orders
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.88rem;max-width:680px;line-height:1.4;margin:0">
                    View past purchases, track fulfillment status, and easily reorder fresh seafood.
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <a href="{{ route('account.dashboard') }}" class="btn btn-secondary btn-sm" style="background:rgba(255,255,255,0.12);color:#ffffff;border:1px solid rgba(255,255,255,0.25);border-radius:10px;font-weight:600">
                    ← Dashboard
                </a>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-sm" style="background:#2563eb;color:#ffffff;border:1px solid #3b82f6;border-radius:10px;font-weight:700;box-shadow:0 2px 8px rgba(37,99,235,0.35);display:inline-flex;align-items:center;gap:6px">
                    <span>+ New Order</span>
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
                <span>Dashboard</span>
            </a>
            <a href="{{ route('account.orders') }}" class="profile-nav-pill active">
                <span>📦</span>
                <span>My Orders</span>
            </a>
            <a href="{{ route('account.profile') }}" class="profile-nav-pill">
                <span>👤</span>
                <span>Profile Settings</span>
            </a>
            @if(auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                <a href="{{ route('quotations.index') }}" class="profile-nav-pill">
                    <span>📝</span>
                    <span>My RFQs</span>
                </a>
            @endif
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="profile-nav-pill" style="border-color:#bfdbfe;background:#eff6ff;color:var(--seagreen-700)">
                    <span>⚡</span>
                    <span>Admin Panel</span>
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
                                    <th>Order Number</th>
                                    <th>Date</th>
                                    <th>Fulfillment</th>
                                    <th>Items</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th style="text-align:right">Action</th>
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
                                            <span class="badge badge-self-collection">🏪 Self-Collection</span>
                                        @else
                                            <span class="badge badge-delivery">🚚 Delivery</span>
                                        @endif
                                    </td>
                                    <td class="text-sm">
                                        {{ $order->items->count() }} item(s)
                                    </td>
                                    <td>
                                        @if($order->payment_status === 'paid')
                                            <span class="badge badge-success">✓ Paid</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
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
                                                View
                                            </a>
                                            <form action="{{ route('account.orders.reorder', $order) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm" title="Add items back to cart" style="border-radius:8px">
                                                    Reorder
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
                                <span class="badge badge-self-collection">🏪 Self-Collection</span>
                            @else
                                <span class="badge badge-delivery">🚚 Delivery</span>
                            @endif

                            @if($order->payment_status === 'paid')
                                <span class="badge badge-success">✓ Paid</span>
                            @else
                                <span class="badge badge-warning">Pending Payment</span>
                            @endif

                            <span class="badge badge-secondary" style="font-size:0.75rem">
                                📦 {{ $order->items->count() }} item(s)
                            </span>
                        </div>

                        <div class="order-mobile-footer">
                            <div>
                                <div class="order-mobile-total-label">Total Amount</div>
                                <div class="order-mobile-total-val">RM {{ number_format($order->total, 2) }}</div>
                            </div>
                            <div class="order-mobile-actions">
                                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-secondary btn-sm">
                                    View
                                </a>
                                <form action="{{ route('account.orders.reorder', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        Reorder
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
                    <h3 style="font-family:var(--font-heading);font-size:1.4rem;margin-bottom:var(--space-2);color:var(--seagreen-900)">No Orders Found</h3>
                    <p class="text-muted text-sm mb-6">You have not placed any seafood orders yet.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary" style="padding:12px 28px;border-radius:12px;font-weight:700">
                        🛒 Browse Seafood Catalogue
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
