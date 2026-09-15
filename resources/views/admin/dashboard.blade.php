@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Dashboard</h1>
        <div class="text-sm text-muted" style="margin-top:2px">{{ now()->format('l, d M Y') }}</div>
    </div>
    <div style="display:flex;align-items:center;gap:var(--space-3);flex-wrap:wrap">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm" style="border-radius:8px;font-weight:600">
            <span>+ Add Product</span>
        </a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid admin-stats-grid">
    <div class="stat-card stat-teal">
        <div class="stat-icon">📦</div>
        <div class="stat-number">{{ $stats['total_orders'] }}</div>
        <div class="stat-label">Total Orders</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-number">{{ $stats['pending_orders'] }}</div>
        <div class="stat-label">Pending Orders</div>
    </div>
    <div class="stat-card stat-gold">
        <div class="stat-icon">💰</div>
        <div class="stat-number">RM {{ number_format($stats['total_revenue'], 0) }}</div>
        <div class="stat-label">Total Revenue</div>
    </div>
    <div class="stat-card stat-coral">
        <div class="stat-icon">👥</div>
        <div class="stat-number">{{ $stats['pending_approvals'] }}</div>
        <div class="stat-label">Pending Approvals</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📉</div>
        <div class="stat-number">{{ $stats['low_stock'] }}</div>
        <div class="stat-label">Low Stock Products</div>
    </div>
    <div class="stat-card stat-seafoam">
        <div class="stat-icon">💬</div>
        <div class="stat-number">{{ $stats['pending_rfq'] }}</div>
        <div class="stat-label">Pending RFQs</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🧑‍💼</div>
        <div class="stat-number">{{ $stats['total_customers'] }}</div>
        <div class="stat-label">Total Customers</div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="admin-dashboard-grid" style="display:grid;grid-template-columns:1fr 360px;gap:var(--space-6)">

    <!-- Recent Orders -->
    <div class="card" style="overflow:hidden">
        <div class="card-header" style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between">
            <div class="card-title" style="font-weight:700;font-size:1.05rem;color:#0f172a">Recent Orders</div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm" style="font-size:0.8rem">View All</a>
        </div>
        
        <!-- Desktop Table -->
        <div class="admin-table-desktop table-wrapper" style="overflow-x:auto;-webkit-overflow-scrolling:touch">
            <table class="table" style="width:100%;margin-bottom:0">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Group</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $order) }}" style="color:var(--seagreen-700);font-weight:700;text-decoration:none">{{ $order->order_number }}</a></td>
                        <td>{{ $order->customer_name }}</td>
                        <td><span class="group-badge group-{{ $order->customer_group }}">{{ ucfirst($order->customer_group) }}</span></td>
                        <td style="font-weight:700;color:var(--seagreen-900)">RM {{ number_format($order->total, 2) }}</td>
                        <td>{!! $order->status_badge !!}</td>
                        <td>{!! $order->payment_badge !!}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm" style="padding:4px 10px;font-size:0.78rem">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted" style="padding:24px">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Orders Cards -->
        <div class="admin-orders-mobile" style="display:none;flex-direction:column;gap:10px;padding:12px">
            @forelse($recentOrders as $order)
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px 14px;display:flex;flex-direction:column;gap:8px">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px">
                    <a href="{{ route('admin.orders.show', $order) }}" style="color:var(--seagreen-700);font-weight:700;font-size:0.9rem;text-decoration:none">
                        {{ $order->order_number }}
                    </a>
                    {!! $order->status_badge !!}
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.8rem;color:#64748b">
                    <span>{{ $order->customer_name }}</span>
                    <span class="group-badge group-{{ $order->customer_group }}" style="font-size:0.7rem">{{ ucfirst($order->customer_group) }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:8px;border-top:1px solid #e2e8f0">
                    <div>
                        <div style="font-size:0.68rem;color:#64748b;text-transform:uppercase;font-weight:700">Total</div>
                        <div style="font-weight:800;font-size:1rem;color:var(--seagreen-900)">RM {{ number_format($order->total, 2) }}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px">
                        {!! $order->payment_badge !!}
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm" style="padding:6px 12px;font-size:0.8rem">View</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted" style="padding:20px">No orders yet.</div>
            @endforelse
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="card">
        <div class="card-header" style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between">
            <div class="card-title" style="font-weight:700;font-size:1.05rem;color:#0f172a">Pending Approvals</div>
            <a href="{{ route('admin.customers.index', ['status'=>'pending']) }}" class="btn btn-secondary btn-sm" style="font-size:0.8rem">View All</a>
        </div>
        <div style="padding:8px 16px">
            @forelse($pendingApprovals as $customer)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid #f1f5f9;gap:12px;flex-wrap:wrap">
                <div>
                    <div style="font-weight:600;font-size:0.875rem;color:#0f172a">{{ $customer->name }}</div>
                    <div class="text-xs text-muted">{{ $customer->company_name ?? $customer->email }}</div>
                    <div class="mt-1"><span class="group-badge group-{{ $customer->customer_group }}">{{ ucfirst($customer->customer_group) }}</span></div>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                    <form action="{{ route('admin.customers.approve', $customer) }}" method="POST" style="margin:0">
                        @csrf
                        <button class="btn btn-success btn-sm" style="padding:6px 12px;font-size:0.8rem">✓ Approve</button>
                    </form>
                    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-secondary btn-sm" style="padding:6px 12px;font-size:0.8rem">View</a>
                </div>
            </div>
            @empty
            <p class="text-muted text-sm text-center" style="padding:28px 0">No pending approvals 🎉</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
