@extends('layouts.admin')
@section('title', 'Orders')

@section('content')

<style>
.cat-filter-select {
    height: 42px !important;
    border-radius: 10px !important;
    border: 1.5px solid #cbd5e1 !important;
    font-size: 0.86rem !important;
    font-weight: 500 !important;
    color: #1e293b !important;
    background-color: #ffffff !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 14px center !important;
    background-size: 16px 16px !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    padding: 0 40px 0 14px !important;
    min-width: 140px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    box-sizing: border-box;
}
.cat-filter-select:hover {
    border-color: #94a3b8 !important;
    background-color: #f8fafc !important;
}
.cat-filter-select:focus {
    outline: none !important;
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    background-color: #ffffff !important;
}
.cat-search-input {
    padding-left: 44px !important;
    padding-right: 14px !important;
    height: 42px !important;
    border-radius: 10px !important;
    border: 1.5px solid #cbd5e1 !important;
    font-size: 0.88rem !important;
    box-sizing: border-box !important;
}
.cat-search-input:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
}
.cat-search-group {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1 1 auto;
    min-width: 200px;
    height: 42px;
}
@media (max-width: 768px) {
    .cat-filter-row {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 10px !important;
    }
    .cat-search-group {
        flex: none !important;
        width: 100% !important;
        min-width: 100% !important;
        height: 42px !important;
        margin: 0 !important;
    }
    .cat-filter-select {
        flex: none !important;
        width: 100% !important;
        min-width: 100% !important;
        margin: 0 !important;
    }
}
</style>

<!-- Page Header -->
<div class="admin-topbar" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #e2e8f0;">
    <div>
        <h1 class="admin-page-title" style="font-size:clamp(1.35rem,2.5vw,1.75rem);font-weight:700;color:#0f172a;margin:0 0 4px;display:flex;align-items:center;gap:8px;">
            <span>📦</span> Orders
        </h1>
        <p class="text-sm text-muted" style="margin:0;color:#64748b;">
            Track customer orders, fulfillment statuses, payment collections, and invoices
        </p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <span style="background:#f0fdf4;color:#15803d;font-size:0.85rem;font-weight:700;padding:8px 16px;border-radius:20px;border:1px solid #bbf7d0;display:inline-flex;align-items:center;gap:6px;">
            💰 RM {{ number_format($stats['paid_revenue'], 2) }} Collected
        </span>
        <span style="background:#eff6ff;color:#1d4ed8;font-size:0.85rem;font-weight:700;padding:8px 16px;border-radius:20px;border:1px solid #bfdbfe;display:inline-flex;align-items:center;gap:6px;">
            📦 {{ number_format($stats['total']) }} Orders
        </span>
    </div>
</div>

<!-- Stats Metric Cards -->
<div class="cat-metrics-grid" style="margin-bottom:22px;">
    <a href="{{ route('admin.orders.index') }}" class="category-metric-card {{ !request()->hasAny(['status','group','payment','fulfillment','search','sort']) ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value">{{ number_format($stats['total']) }}</div>
            <div class="cat-metric-title">All Orders</div>
        </div>
        <div class="cat-metric-icon" style="background:#eff6ff;color:#2563eb;">📦</div>
    </a>

    <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'pending'])) }}" class="category-metric-card {{ request('status') === 'pending' ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value" style="color:{{ $stats['pending'] > 0 ? '#d97706' : '#0f172a' }};">{{ number_format($stats['pending']) }}</div>
            <div class="cat-metric-title">Needs Attention</div>
        </div>
        <div class="cat-metric-icon" style="background:#fffbeb;color:#d97706;">
            @if($stats['pending'] > 0)
                <span style="position:relative;display:inline-block;">
                    ⏳
                    <span style="position:absolute;top:-4px;right:-4px;width:10px;height:10px;background:#ef4444;border-radius:50%;border:2px solid #fff;"></span>
                </span>
            @else
                ⏳
            @endif
        </div>
    </a>

    <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'processing'])) }}" class="category-metric-card {{ request('status') === 'processing' ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value" style="color:#0284c7;">{{ number_format($stats['processing']) }}</div>
            <div class="cat-metric-title">In Fulfillment</div>
        </div>
        <div class="cat-metric-icon" style="background:#f0f9ff;color:#0284c7;">🚚</div>
    </a>

    <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['payment' => 'unpaid'])) }}" class="category-metric-card {{ request('payment') === 'unpaid' ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value" style="color:{{ $stats['unpaid'] > 0 ? '#dc2626' : '#0f172a' }};">{{ number_format($stats['unpaid']) }}</div>
            <div class="cat-metric-title">Unpaid Orders</div>
        </div>
        <div class="cat-metric-icon" style="background:#fef2f2;color:#dc2626;">💳</div>
    </a>
</div>

<!-- Search & Filtering Toolbar -->
<div class="cat-filter-card" style="margin-bottom:22px;">
    <form method="GET" action="{{ route('admin.orders.index') }}" id="orderFilterForm">
        <div class="cat-filter-row">
            <div class="cat-search-group">
                <span class="cat-search-icon" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);display:flex;align-items:center;pointer-events:none;color:#94a3b8;z-index:2;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control cat-search-input"
                       style="padding-left:44px !important;padding-right:14px !important;height:42px;border-radius:10px;"
                       placeholder="Order #, token, customer, email, phone...">
            </div>

            <select name="status" class="cat-filter-select" onchange="document.getElementById('orderFilterForm').submit()">
                <option value="">All Order Statuses</option>
                <option value="pending"    {{ request('status') === 'pending'    ? 'selected' : '' }}>⏳ Pending</option>
                <option value="confirmed"  {{ request('status') === 'confirmed'  ? 'selected' : '' }}>📋 Confirmed</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                <option value="ready"      {{ request('status') === 'ready'      ? 'selected' : '' }}>📦 Ready</option>
                <option value="shipped"    {{ request('status') === 'shipped'    ? 'selected' : '' }}>🚚 Shipped</option>
                <option value="delivered"  {{ request('status') === 'delivered'  ? 'selected' : '' }}>✅ Delivered</option>
                <option value="cancelled"  {{ request('status') === 'cancelled'  ? 'selected' : '' }}>❌ Cancelled</option>
            </select>

            <select name="payment" class="cat-filter-select" onchange="document.getElementById('orderFilterForm').submit()">
                <option value="">All Payments</option>
                <option value="paid"     {{ request('payment') === 'paid'     ? 'selected' : '' }}>✅ Paid</option>
                <option value="unpaid"   {{ request('payment') === 'unpaid'   ? 'selected' : '' }}>⏳ Unpaid</option>
                <option value="refunded" {{ request('payment') === 'refunded' ? 'selected' : '' }}>↩️ Refunded</option>
            </select>

            <select name="group" class="cat-filter-select" onchange="document.getElementById('orderFilterForm').submit()">
                <option value="">All Buyer Groups</option>
                <option value="retail"    {{ request('group') === 'retail'    ? 'selected' : '' }}>🛒 Retail (B2C)</option>
                <option value="walkin"    {{ request('group') === 'walkin'    ? 'selected' : '' }}>🚶 Walk-in</option>
                <option value="wholesale" {{ request('group') === 'wholesale' ? 'selected' : '' }}>🏭 Wholesale (B2B)</option>
                <option value="trading"   {{ request('group') === 'trading'   ? 'selected' : '' }}>📦 Trading (RFQ)</option>
            </select>

            <select name="fulfillment" class="cat-filter-select" onchange="document.getElementById('orderFilterForm').submit()">
                <option value="">All Fulfillment</option>
                <option value="self_collection" {{ request('fulfillment') === 'self_collection' ? 'selected' : '' }}>🏪 Self-Collection</option>
                <option value="delivery"        {{ request('fulfillment') === 'delivery'        ? 'selected' : '' }}>🚚 Delivery</option>
            </select>

            <select name="sort" class="cat-filter-select" onchange="document.getElementById('orderFilterForm').submit()">
                <option value="latest"     {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Sort: Newest First</option>
                <option value="total_high" {{ request('sort') === 'total_high'       ? 'selected' : '' }}>Sort: Total (High to Low)</option>
                <option value="total_low"  {{ request('sort') === 'total_low'        ? 'selected' : '' }}>Sort: Total (Low to High)</option>
                <option value="oldest"     {{ request('sort') === 'oldest'           ? 'selected' : '' }}>Sort: Oldest First</option>
            </select>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary btn-sm" style="height:42px;padding:0 20px;font-weight:600;display:inline-flex;align-items:center;gap:6px;border-radius:10px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'group', 'payment', 'fulfillment', 'sort']))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm" style="height:42px;padding:0 14px;display:inline-flex;align-items:center;gap:4px;border-radius:10px;" title="Clear all filters">
                        ✕ Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

@if($orders->count() > 0)

    <!-- ─── Desktop Table View (>= 992px) ───────────────────────────────────── -->
    <div class="card categories-table-container" style="border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);padding:0;margin-bottom:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
            <table class="table" style="margin:0;width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Order &amp; Token</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Customer</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Group</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Fulfillment</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Total</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;text-align:center;">Payment</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;text-align:center;">Status</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Date</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s ease;">
                        <td style="padding:14px 18px;">
                            <div style="font-weight:700;font-size:0.92rem;color:#0f766e;line-height:1.25;">
                                <a href="{{ route('admin.orders.show', $order) }}" style="color:inherit;text-decoration:none;">
                                    {{ $order->order_number }}
                                </a>
                            </div>
                            @if($order->collection_token)
                                <div style="margin-top:4px;">
                                    <span style="background:#ccfbf1;color:#0f766e;font-weight:800;font-size:0.75rem;padding:2px 8px;border-radius:6px;border:1px solid #99f6e4;display:inline-flex;align-items:center;gap:3px;">
                                        🎟 {{ $order->collection_token }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td style="padding:14px 18px;">
                            <div style="font-weight:600;font-size:0.9rem;color:#0f172a;line-height:1.25;margin-bottom:2px;">
                                {{ $order->customer_name }}
                            </div>
                            <div style="font-size:0.8rem;color:#64748b;display:flex;align-items:center;gap:4px;">
                                {{ $order->customer_email }}
                            </div>
                            @if($order->customer_phone)
                                <div style="font-size:0.78rem;margin-top:2px;">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" style="color:#16a34a;text-decoration:none;font-weight:600;" title="WhatsApp">
                                        💬 {{ $order->customer_phone }}
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td style="padding:14px 18px;">
                            <span class="group-badge group-{{ $order->customer_group }}" style="font-size:0.75rem;padding:4px 10px;letter-spacing:0.02em;">
                                {{ ucfirst($order->customer_group) }}
                            </span>
                        </td>
                        <td style="padding:14px 18px;font-size:0.85rem;color:#334155;white-space:nowrap;">
                            @if($order->fulfillment_type === 'self_collection')
                                <span style="background:#f1f5f9;color:#334155;padding:4px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                                    🏪 Self-Collection
                                </span>
                            @else
                                <span style="background:#eff6ff;color:#1e40af;padding:4px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                                    🚚 Delivery
                                </span>
                            @endif
                        </td>
                        <td style="padding:14px 18px;white-space:nowrap;">
                            <div style="font-weight:800;font-size:0.95rem;color:#0f766e;">
                                RM {{ number_format($order->total, 2) }}
                            </div>
                        </td>
                        <td style="padding:14px 18px;text-align:center;">
                            @if($order->payment_status === 'paid')
                                <span style="background:#dcfce7;color:#15803d;padding:4px 12px;border-radius:20px;font-size:0.76rem;font-weight:700;border:1px solid #bbf7d0;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    ✓ Paid
                                </span>
                            @elseif($order->payment_status === 'unpaid')
                                <span style="background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:20px;font-size:0.76rem;font-weight:700;border:1px solid #fde68a;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    ⏳ Unpaid
                                </span>
                            @else
                                <span style="background:#eff6ff;color:#1d4ed8;padding:4px 12px;border-radius:20px;font-size:0.76rem;font-weight:700;border:1px solid #bfdbfe;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            @endif
                        </td>
                        <td style="padding:14px 18px;text-align:center;">
                            @if($order->status === 'delivered' || $order->status === 'ready')
                                <span style="background:#dcfce7;color:#15803d;padding:4px 12px;border-radius:20px;font-size:0.76rem;font-weight:700;border:1px solid #bbf7d0;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @elseif($order->status === 'pending')
                                <span style="background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:20px;font-size:0.76rem;font-weight:700;border:1px solid #fde68a;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    ⏳ Pending
                                </span>
                            @elseif($order->status === 'cancelled')
                                <span style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:20px;font-size:0.76rem;font-weight:700;border:1px solid #fecaca;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    ✕ Cancelled
                                </span>
                            @else
                                <span style="background:#eff6ff;color:#1d4ed8;padding:4px 12px;border-radius:20px;font-size:0.76rem;font-weight:700;border:1px solid #bfdbfe;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>
                        <td style="padding:14px 18px;font-size:0.82rem;color:#64748b;white-space:nowrap;">
                            <div>{{ $order->created_at->format('d M Y') }}</div>
                            <div style="font-size:0.75rem;color:#94a3b8;">{{ $order->created_at->format('h:i A') }} ({{ $order->created_at->diffForHumans() }})</div>
                        </td>
                        <td style="padding:14px 18px;text-align:right;">
                            <div style="display:inline-flex;gap:6px;align-items:center;justify-content:flex-end;flex-wrap:nowrap;">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm" style="font-size:0.78rem;padding:6px 12px;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                                    View
                                </a>
                                <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-secondary btn-sm" target="_blank" style="font-size:0.78rem;padding:6px 10px;font-weight:600;display:inline-flex;align-items:center;gap:4px;" title="Print or Save Invoice PDF">
                                    📄 PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ─── Mobile & Tablet Card View (< 992px) ─────────────────────────────── -->
    <div class="categories-cards-container">
        @foreach($orders as $order)
        <div class="category-item-card" style="box-shadow:0 1px 3px rgba(0,0,0,0.03);">
            <!-- Top Header -->
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:12px;border-bottom:1px solid #f1f5f9;padding-bottom:10px;">
                <div>
                    <div style="font-weight:800;font-size:1rem;color:#0f766e;display:flex;align-items:center;gap:6px;">
                        <a href="{{ route('admin.orders.show', $order) }}" style="color:inherit;text-decoration:none;">
                            {{ $order->order_number }}
                        </a>
                    </div>
                    @if($order->collection_token)
                        <div style="margin-top:3px;">
                            <span style="background:#ccfbf1;color:#0f766e;font-weight:800;font-size:0.72rem;padding:2px 7px;border-radius:6px;border:1px solid #99f6e4;">
                                🎟 Token: {{ $order->collection_token }}
                            </span>
                        </div>
                    @endif
                </div>

                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                    @if($order->status === 'delivered' || $order->status === 'ready')
                        <span style="background:#dcfce7;color:#15803d;padding:3px 9px;border-radius:20px;font-size:0.72rem;font-weight:700;border:1px solid #bbf7d0;white-space:nowrap;">
                            {{ ucfirst($order->status) }}
                        </span>
                    @elseif($order->status === 'pending')
                        <span style="background:#fef3c7;color:#92400e;padding:3px 9px;border-radius:20px;font-size:0.72rem;font-weight:700;border:1px solid #fde68a;white-space:nowrap;">
                            ⏳ Pending
                        </span>
                    @elseif($order->status === 'cancelled')
                        <span style="background:#fee2e2;color:#991b1b;padding:3px 9px;border-radius:20px;font-size:0.72rem;font-weight:700;border:1px solid #fecaca;white-space:nowrap;">
                            ✕ Cancelled
                        </span>
                    @else
                        <span style="background:#eff6ff;color:#1d4ed8;padding:3px 9px;border-radius:20px;font-size:0.72rem;font-weight:700;border:1px solid #bfdbfe;white-space:nowrap;">
                            {{ ucfirst($order->status) }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;align-items:baseline;gap:8px;">
                    <div style="font-weight:700;color:#0f172a;font-size:0.95rem;">
                        {{ $order->customer_name }}
                    </div>
                    <span class="group-badge group-{{ $order->customer_group }}" style="font-size:0.7rem;padding:2px 7px;">
                        {{ ucfirst($order->customer_group) }}
                    </span>
                </div>
                <div style="font-size:0.78rem;color:#64748b;margin-top:2px;">
                    {{ $order->customer_email }}
                </div>
                @if($order->customer_phone)
                    <div style="font-size:0.78rem;margin-top:2px;">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" style="color:#16a34a;text-decoration:none;font-weight:600;">
                            💬 {{ $order->customer_phone }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- Details Strip -->
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:#f8fafc;border-radius:8px;margin-bottom:12px;flex-wrap:wrap;gap:8px;border:1px solid #f1f5f9;">
                <div>
                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;">Fulfillment</div>
                    <div style="font-size:0.82rem;font-weight:600;color:#334155;margin-top:1px;">
                        {{ $order->fulfillment_type === 'self_collection' ? '🏪 Collection' : '🚚 Delivery' }}
                    </div>
                </div>
                <div>
                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;">Payment</div>
                    <div style="margin-top:1px;">
                        @if($order->payment_status === 'paid')
                            <span style="color:#15803d;font-size:0.82rem;font-weight:700;">✓ Paid</span>
                        @elseif($order->payment_status === 'unpaid')
                            <span style="color:#92400e;font-size:0.82rem;font-weight:700;">⏳ Unpaid</span>
                        @else
                            <span style="color:#1d4ed8;font-size:0.82rem;font-weight:700;">{{ ucfirst($order->payment_status) }}</span>
                        @endif
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;">Total</div>
                    <div style="font-size:1.05rem;font-weight:800;color:#0f766e;margin-top:1px;">
                        RM {{ number_format($order->total, 2) }}
                    </div>
                </div>
            </div>

            <!-- Date & Actions -->
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;font-size:0.76rem;color:#94a3b8;">
                <span>Placed on {{ $order->created_at->format('d M Y, h:i A') }}</span>
                <span>{{ $order->created_at->diffForHumans() }}</span>
            </div>

            <div style="display:flex;gap:8px;">
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm" style="flex:1;text-align:center;justify-content:center;font-weight:600;padding:8px 12px;font-size:0.82rem;">
                    View Order
                </a>
                <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-secondary btn-sm" target="_blank" style="flex:1;text-align:center;justify-content:center;font-weight:600;padding:8px 12px;font-size:0.82rem;">
                    📄 Invoice PDF
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination & Counter Bar -->
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-top:22px;padding:14px 20px;background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
        <div style="font-size:0.86rem;color:#64748b;">
            Showing <strong style="color:#0f172a;">{{ $orders->firstItem() ?? 0 }}</strong> to <strong style="color:#0f172a;">{{ $orders->lastItem() ?? 0 }}</strong> of <strong style="color:#0f172a;">{{ number_format($orders->total()) }}</strong> orders
        </div>
        <div>
            {{ $orders->links() }}
        </div>
    </div>

@else
    <!-- Empty State -->
    <div class="card" style="padding:64px 20px;text-align:center;border-radius:12px;border:1px solid #e2e8f0;background:#ffffff;">
        <div style="font-size:3.5rem;margin-bottom:14px;">📦</div>
        <h3 style="font-size:1.25rem;font-weight:700;color:#0f172a;margin:0 0 6px;">No Orders Found</h3>
        <p class="text-sm text-muted" style="margin:0 auto 20px;max-width:440px;color:#64748b;line-height:1.5;">
            @if(request()->hasAny(['search', 'status', 'group', 'payment', 'fulfillment', 'sort']))
                No orders match your search and filter criteria. Try adjusting or clearing your filters to see more results.
            @else
                No orders have been recorded in the store yet.
            @endif
        </p>
        @if(request()->hasAny(['search', 'status', 'group', 'payment', 'fulfillment', 'sort']))
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary" style="font-weight:600;padding:10px 20px;display:inline-flex;align-items:center;gap:6px;">
                ✕ Clear All Filters
            </a>
        @endif
    </div>
@endif

@endsection
