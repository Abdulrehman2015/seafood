@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<style>
/* ─── Compact Minimalist Dashboard (Royal Navy & Frontend Theme) ─────────── */
.dash-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 20px;
}
.dash-title-group h1 {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0c2146;
    margin: 0 0 2px;
    letter-spacing: -0.02em;
}
.dash-date-pill {
    font-size: 0.78rem;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.dash-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.dash-btn-primary {
    background: #2563eb !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 7px 14px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(37,99,235,0.2);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.15s ease;
}
.dash-btn-primary:hover {
    background: #1d4ed8 !important;
    transform: translateY(-1px);
}
.dash-btn-secondary {
    background: #ffffff !important;
    border: 1px solid #cbd5e1 !important;
    color: #334155 !important;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 7px 14px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.15s ease;
}
.dash-btn-secondary:hover {
    background: #f8fafc !important;
    border-color: #94a3b8 !important;
    color: #0f172a !important;
}

/* ─── Metric Cards Grid ─────────────────────────────────────────────────── */
.dash-metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 14px;
}
.dash-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    transition: all 0.15s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.dash-card:hover {
    border-color: #bfdbfe;
    box-shadow: 0 4px 12px rgba(12,33,70,0.05);
}
.dash-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}
.dash-card-label {
    font-size: 0.76rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.dash-card-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.dash-card-value {
    font-size: 1.55rem;
    font-weight: 800;
    color: #0c2146;
    letter-spacing: -0.02em;
    line-height: 1.1;
}
.dash-card-sub {
    font-size: 0.74rem;
    color: #94a3b8;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* ─── Secondary Quick Status Row ────────────────────────────────────────── */
.dash-sub-status-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 22px;
}
.dash-sub-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    transition: all 0.15s ease;
}
.dash-sub-card:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    transform: translateY(-1px);
}
.dash-sub-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.dash-sub-icon {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
}
.dash-sub-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #334155;
}
.dash-sub-count {
    font-size: 0.88rem;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 999px;
}

/* ─── Main Content Grid (Recent Orders & Side Column) ───────────────────── */
.dash-content-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
}
.dash-panel {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.dash-panel-header {
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
}
.dash-panel-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #0c2146;
    display: flex;
    align-items: center;
    gap: 8px;
}
.dash-table {
    width: 100%;
    border-collapse: collapse;
}
.dash-table th {
    background: #f8fafc;
    padding: 10px 16px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
}
.dash-table td {
    padding: 12px 16px;
    font-size: 0.84rem;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.dash-table tr:hover td {
    background: #f8fafc;
}
.dash-order-link {
    color: #1d4ed8;
    font-weight: 700;
    text-decoration: none;
    font-family: monospace;
    font-size: 0.86rem;
}
.dash-order-link:hover {
    text-decoration: underline;
}

/* ─── Compact Approvals & Shortcuts ─────────────────────────────────────── */
.dash-approval-item {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.dash-approval-item:last-child {
    border-bottom: none;
}
.dash-approval-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    color: #ffffff;
    font-weight: 700;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ─── Mobile Table Fallback Cards ───────────────────────────────────────── */
.dash-orders-mobile {
    display: none;
    flex-direction: column;
    gap: 10px;
    padding: 12px;
}
.dash-order-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
}

/* ─── Responsive Queries ────────────────────────────────────────────────── */
@media (max-width: 1200px) {
    .dash-metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 992px) {
    .dash-content-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 768px) {
    .dash-metrics-grid {
        grid-template-columns: 1fr;
    }
    .dash-sub-status-grid {
        grid-template-columns: 1fr;
    }
    .dash-table-wrapper {
        display: none;
    }
    .dash-orders-mobile {
        display: flex;
    }
}
</style>

<!-- Dashboard Topbar Header -->
<div class="dash-header">
    <div class="dash-title-group">
        <h1>Dashboard</h1>
        <div class="dash-date-pill">
            <span>📅</span>
            <span>{{ now()->format('l, d M Y') }}</span>
            <span style="color:#cbd5e1">•</span>
            <span style="color:#059669;font-weight:600">Active Session</span>
        </div>
    </div>
    <div class="dash-actions">
        <a href="{{ route('admin.orders.index') }}" class="dash-btn-secondary">
            <span>📦 Orders</span>
        </a>
        <a href="{{ route('admin.customers.index') }}" class="dash-btn-secondary">
            <span>👥 Customers</span>
        </a>
        <a href="{{ route('admin.products.create') }}" class="dash-btn-primary">
            <span>+ Add Product</span>
        </a>
    </div>
</div>

<!-- Primary Metrics Row -->
<div class="dash-metrics-grid">
    
    <!-- 1. Total Revenue -->
    <div class="dash-card">
        <div class="dash-card-header">
            <span class="dash-card-label">Revenue (Paid)</span>
            <div class="dash-card-icon" style="background:#eff6ff;color:#2563eb">
                💰
            </div>
        </div>
        <div>
            <div class="dash-card-value">RM {{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="dash-card-sub">
                <span style="color:#10b981;font-weight:700">✓ Settled</span>
                <span>from paid orders</span>
            </div>
        </div>
    </div>

    <!-- 2. Total Orders -->
    <div class="dash-card">
        <div class="dash-card-header">
            <span class="dash-card-label">Total Orders</span>
            <div class="dash-card-icon" style="background:#eff6ff;color:#1d4ed8">
                📦
            </div>
        </div>
        <div>
            <div class="dash-card-value">{{ number_format($stats['total_orders']) }}</div>
            <div class="dash-card-sub">
                @if($stats['pending_orders'] > 0)
                    <span style="color:#f59e0b;font-weight:700">⚠️ {{ $stats['pending_orders'] }} pending processing</span>
                @else
                    <span style="color:#10b981;font-weight:700">✓ All processed</span>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. Registered Customers -->
    <div class="dash-card">
        <div class="dash-card-header">
            <span class="dash-card-label">Customers</span>
            <div class="dash-card-icon" style="background:#f0fdf4;color:#16a34a">
                👥
            </div>
        </div>
        <div>
            <div class="dash-card-value">{{ number_format($stats['total_customers']) }}</div>
            <div class="dash-card-sub">
                <a href="{{ route('admin.customers.index') }}" style="color:#2563eb;text-decoration:underline">
                    View customer directory →
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Low Stock Alert -->
    <div class="dash-card">
        <div class="dash-card-header">
            <span class="dash-card-label">Low Stock</span>
            <div class="dash-card-icon" style="background:{{ $stats['low_stock'] > 0 ? '#fef2f2' : '#f0fdf4' }};color:{{ $stats['low_stock'] > 0 ? '#ef4444' : '#16a34a' }}">
                ⚠️
            </div>
        </div>
        <div>
            <div class="dash-card-value" style="color:{{ $stats['low_stock'] > 0 ? '#dc2626' : '#0c2146' }}">
                {{ $stats['low_stock'] }}
            </div>
            <div class="dash-card-sub">
                @if($stats['low_stock'] > 0)
                    <a href="{{ route('admin.products.index') }}" style="color:#ef4444;font-weight:600;text-decoration:underline">
                        Restock items now →
                    </a>
                @else
                    <span style="color:#10b981;font-weight:600">✓ Healthy inventory</span>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Secondary Quick Action Statuses -->
<div class="dash-sub-status-grid">
    
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="dash-sub-card">
        <div class="dash-sub-left">
            <div class="dash-sub-icon" style="background:#fffbeb;color:#d97706">⏳</div>
            <span class="dash-sub-label">Pending Orders</span>
        </div>
        <span class="dash-sub-count" style="background:{{ $stats['pending_orders'] > 0 ? '#fef3c7' : '#f1f5f9' }};color:{{ $stats['pending_orders'] > 0 ? '#b45309' : '#64748b' }}">
            {{ $stats['pending_orders'] }}
        </span>
    </a>

    <a href="{{ route('admin.customers.index', ['status' => 'pending']) }}" class="dash-sub-card">
        <div class="dash-sub-left">
            <div class="dash-sub-icon" style="background:#eff6ff;color:#2563eb">📝</div>
            <span class="dash-sub-label">Awaiting Approvals (B2B)</span>
        </div>
        <span class="dash-sub-count" style="background:{{ $stats['pending_approvals'] > 0 ? '#dbeafe' : '#f1f5f9' }};color:{{ $stats['pending_approvals'] > 0 ? '#1d4ed8' : '#64748b' }}">
            {{ $stats['pending_approvals'] }}
        </span>
    </a>

    <a href="{{ route('admin.quotations.index', ['status' => 'pending']) }}" class="dash-sub-card">
        <div class="dash-sub-left">
            <div class="dash-sub-icon" style="background:#f0fdf4;color:#15803d">💬</div>
            <span class="dash-sub-label">Pending RFQ Quotations</span>
        </div>
        <span class="dash-sub-count" style="background:{{ $stats['pending_rfq'] > 0 ? '#dcfce7' : '#f1f5f9' }};color:{{ $stats['pending_rfq'] > 0 ? '#15803d' : '#64748b' }}">
            {{ $stats['pending_rfq'] }}
        </span>
    </a>

</div>

<!-- Main Content Grid -->
<div class="dash-content-grid">
    
    <!-- Left Panel: Recent Orders -->
    <div class="dash-panel">
        <div class="dash-panel-header">
            <div class="dash-panel-title">
                <span>📦</span> Recent Orders
            </div>
            <a href="{{ route('admin.orders.index') }}" style="font-size:0.8rem;color:#2563eb;font-weight:600;text-decoration:none">
                View All Orders →
            </a>
        </div>

        <!-- Desktop Table -->
        <div class="dash-table-wrapper" style="overflow-x:auto">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Buyer Group</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th style="text-align:right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="dash-order-link">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td>
                            <div style="font-weight:600;color:#0f172a">{{ $order->customer_name }}</div>
                            <div style="font-size:0.75rem;color:#94a3b8">{{ $order->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <span class="group-badge group-{{ $order->customer_group }}" style="font-size:0.72rem;padding:2px 8px">
                                {{ ucfirst($order->customer_group) }}
                            </span>
                        </td>
                        <td>
                            <strong style="color:#0c2146">RM {{ number_format($order->total, 2) }}</strong>
                        </td>
                        <td>{!! $order->status_badge !!}</td>
                        <td>{!! $order->payment_badge !!}</td>
                        <td style="text-align:right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="dash-btn-secondary" style="padding:4px 10px;font-size:0.76rem">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:36px;color:#94a3b8">
                            No orders recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Fallback Cards -->
        <div class="dash-orders-mobile">
            @forelse($recentOrders as $order)
            <div class="dash-order-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                    <a href="{{ route('admin.orders.show', $order) }}" class="dash-order-link">
                        {{ $order->order_number }}
                    </a>
                    {!! $order->status_badge !!}
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.8rem;color:#64748b;margin-bottom:6px">
                    <span>{{ $order->customer_name }}</span>
                    <span class="group-badge group-{{ $order->customer_group }}">{{ ucfirst($order->customer_group) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:8px;border-top:1px solid #e2e8f0">
                    <strong style="color:#0c2146;font-size:0.92rem">RM {{ number_format($order->total, 2) }}</strong>
                    <a href="{{ route('admin.orders.show', $order) }}" class="dash-btn-secondary" style="padding:4px 10px;font-size:0.76rem">View</a>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:24px;color:#94a3b8">No orders yet.</div>
            @endforelse
        </div>
    </div>

    <!-- Right Panel: Pending Approvals & Shortcuts -->
    <div style="display:flex;flex-direction:column;gap:16px">
        
        <!-- Pending Approvals Card -->
        <div class="dash-panel">
            <div class="dash-panel-header">
                <div class="dash-panel-title">
                    <span>📝</span> Awaiting Approval
                </div>
                <a href="{{ route('admin.customers.index', ['status' => 'pending']) }}" style="font-size:0.78rem;color:#2563eb;font-weight:600;text-decoration:none">
                    All ({{ $stats['pending_approvals'] }})
                </a>
            </div>

            <div>
                @forelse($pendingApprovals as $customer)
                <div class="dash-approval-item">
                    <div style="display:flex;align-items:center;gap:10px;min-width:0">
                        <div class="dash-approval-avatar">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <div style="min-width:0">
                            <div style="font-size:0.84rem;font-weight:700;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $customer->name }}
                            </div>
                            <div style="font-size:0.74rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $customer->company_name ?? $customer->email }}
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;flex-shrink:0">
                        <form action="{{ route('admin.customers.approve', $customer) }}" method="POST" style="margin:0">
                            @csrf
                            <button class="btn btn-sm" style="background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;padding:4px 9px;font-size:0.75rem;font-weight:700;border-radius:6px;cursor:pointer">
                                ✓
                            </button>
                        </form>
                        <a href="{{ route('admin.customers.show', $customer) }}" class="dash-btn-secondary" style="padding:4px 9px;font-size:0.75rem">
                            View
                        </a>
                    </div>
                </div>
                @empty
                <div style="padding:28px 16px;text-align:center;color:#94a3b8;font-size:0.84rem">
                    <span>🎉</span> No pending approvals
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Shortcuts -->
        <div class="dash-panel" style="padding:16px 18px">
            <div style="font-size:0.76rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:12px">
                Quick Navigation
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                <a href="{{ route('admin.products.index') }}" class="dash-sub-card" style="padding:8px 10px">
                    <span style="font-size:0.8rem;color:#334155;font-weight:600">🐟 Products</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="dash-sub-card" style="padding:8px 10px">
                    <span style="font-size:0.8rem;color:#334155;font-weight:600">📁 Categories</span>
                </a>
                <a href="{{ route('admin.gallery.index') }}" class="dash-sub-card" style="padding:8px 10px">
                    <span style="font-size:0.8rem;color:#334155;font-weight:600">🖼️ Gallery</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="dash-sub-card" style="padding:8px 10px">
                    <span style="font-size:0.8rem;color:#334155;font-weight:600">⚙️ Settings</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
