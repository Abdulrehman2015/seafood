@extends('layouts.admin')
@section('title', 'Customers')

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
@media (max-width: 768px) {
    .cat-filter-row {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 10px !important;
    }
    .cat-search-group,
    .cat-filter-select {
        width: 100% !important;
        min-width: 100% !important;
    }
}
</style>

<!-- Page Header -->
<div class="admin-topbar" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #e2e8f0;">
    <div>
        <h1 class="admin-page-title" style="font-size:clamp(1.35rem,2.5vw,1.75rem);font-weight:700;color:#0f172a;margin:0 0 4px;display:flex;align-items:center;gap:8px;">
            <span>👥</span> Customers
        </h1>
        <p class="text-sm text-muted" style="margin:0;color:#64748b;">
            Manage customer accounts, B2B wholesale approvals, credit limits, and buyer groups
        </p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <span style="background:#eff6ff;color:#1d4ed8;font-size:0.85rem;font-weight:700;padding:8px 16px;border-radius:20px;border:1px solid #bfdbfe;display:inline-flex;align-items:center;gap:6px;">
            👥 {{ number_format($stats['total']) }} Registered Accounts
        </span>
    </div>
</div>

<!-- Stats Metric Cards -->
<div class="cat-metrics-grid" style="margin-bottom:22px;">
    <a href="{{ route('admin.customers.index') }}" class="category-metric-card {{ !request()->hasAny(['group','status','search','sort']) ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value">{{ number_format($stats['total']) }}</div>
            <div class="cat-metric-title">All Customers</div>
        </div>
        <div class="cat-metric-icon" style="background:#eff6ff;color:#2563eb;">👥</div>
    </a>

    <a href="{{ route('admin.customers.index', array_merge(request()->query(), ['status' => 'pending'])) }}" class="category-metric-card {{ request('status') === 'pending' ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value" style="color:{{ $stats['pending'] > 0 ? '#d97706' : '#0f172a' }};">{{ number_format($stats['pending']) }}</div>
            <div class="cat-metric-title">Awaiting Approval</div>
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

    <a href="{{ route('admin.customers.index', array_merge(request()->query(), ['status' => 'approved'])) }}" class="category-metric-card {{ request('status') === 'approved' ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value" style="color:#15803d;">{{ number_format($stats['approved']) }}</div>
            <div class="cat-metric-title">Approved Accounts</div>
        </div>
        <div class="cat-metric-icon" style="background:#f0fdf4;color:#16a34a;">✅</div>
    </a>

    <a href="{{ route('admin.customers.index', array_merge(request()->query(), ['group' => 'wholesale'])) }}" class="category-metric-card {{ in_array(request('group'), ['wholesale','trading']) ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value" style="color:#b45309;">{{ number_format($stats['wholesale']) }}</div>
            <div class="cat-metric-title">Wholesale &amp; B2B</div>
        </div>
        <div class="cat-metric-icon" style="background:#fff7ed;color:#ea580c;">🏭</div>
    </a>
</div>

<!-- Search & Filtering Toolbar -->
<div class="cat-filter-card" style="margin-bottom:22px;">
    <form method="GET" action="{{ route('admin.customers.index') }}" id="customerFilterForm">
        <div class="cat-filter-row">
            <div class="cat-search-group">
                <span class="cat-search-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control cat-search-input"
                       placeholder="Search by name, email, company, phone...">
            </div>

            <select name="group" class="cat-filter-select" onchange="document.getElementById('customerFilterForm').submit()">
                <option value="">All Buyer Groups</option>
                <option value="retail"    {{ request('group') === 'retail'    ? 'selected' : '' }}>🛒 Retail (B2C)</option>
                <option value="wholesale" {{ request('group') === 'wholesale' ? 'selected' : '' }}>🏭 Wholesale (B2B)</option>
                <option value="trading"   {{ request('group') === 'trading'   ? 'selected' : '' }}>📦 Trading (RFQ)</option>
            </select>

            <select name="status" class="cat-filter-select" onchange="document.getElementById('customerFilterForm').submit()">
                <option value="">All Approval Statuses</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ Approved</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>⏳ Pending Review</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
            </select>

            <select name="sort" class="cat-filter-select" onchange="document.getElementById('customerFilterForm').submit()">
                <option value="latest"  {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Sort: Newest First</option>
                <option value="name"    {{ request('sort') === 'name' ? 'selected' : '' }}>Sort: Name (A-Z)</option>
                <option value="company" {{ request('sort') === 'company' ? 'selected' : '' }}>Sort: Company (A-Z)</option>
                <option value="oldest"  {{ request('sort') === 'oldest' ? 'selected' : '' }}>Sort: Oldest First</option>
            </select>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary btn-sm" style="height:40px;padding:0 18px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'group', 'status', 'sort']))
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm" style="height:40px;padding:0 14px;display:inline-flex;align-items:center;gap:4px;" title="Clear all filters">
                        ✕ Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

@if($customers->count() > 0)

    <!-- ─── Desktop Table View (>= 992px) ───────────────────────────────────── -->
    <div class="card categories-table-container" style="border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);padding:0;margin-bottom:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
            <table class="table" style="margin:0;width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Customer</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Buyer Group</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Company &amp; Contact</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;text-align:center;">Approval</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Registered</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s ease;">
                        <td style="padding:14px 18px;">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.88rem;flex-shrink:0;box-shadow:0 2px 4px rgba(37,99,235,0.2);">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-weight:700;color:#0f172a;font-size:0.92rem;line-height:1.25;margin-bottom:2px;">
                                        <a href="{{ route('admin.customers.show', $customer) }}" style="color:inherit;text-decoration:none;">
                                            {{ $customer->name }}
                                        </a>
                                    </div>
                                    <div style="font-size:0.8rem;color:#64748b;display:flex;align-items:center;gap:4px;">
                                        <span>{{ $customer->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:14px 18px;">
                            <span class="group-badge group-{{ $customer->customer_group }}" style="font-size:0.75rem;padding:4px 10px;letter-spacing:0.02em;">
                                @if($customer->customer_group === 'wholesale')
                                    🏭 Wholesale (B2B)
                                @elseif($customer->customer_group === 'trading')
                                    📦 Trading (RFQ)
                                @else
                                    🛒 Retail (B2C)
                                @endif
                            </span>
                        </td>
                        <td style="padding:14px 18px;font-size:0.86rem;color:#334155;">
                            @if($customer->company_name)
                                <div style="font-weight:600;color:#0f172a;margin-bottom:2px;display:flex;align-items:center;gap:5px;">
                                    <span style="font-size:0.8rem;">🏢</span> {{ $customer->company_name }}
                                </div>
                            @endif
                            @if($customer->phone)
                                <div style="font-size:0.82rem;">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone) }}" target="_blank" style="color:#16a34a;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:4px;" title="Chat on WhatsApp">
                                        <span>💬</span> {{ $customer->phone }}
                                    </a>
                                </div>
                            @else
                                <span style="color:#94a3b8;font-size:0.82rem;">—</span>
                            @endif
                        </td>
                        <td style="padding:14px 18px;text-align:center;">
                            @if($customer->approval_status === 'approved')
                                <span style="background:#dcfce7;color:#15803d;padding:4px 12px;border-radius:20px;font-size:0.76rem;font-weight:700;border:1px solid #bbf7d0;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    ✓ Approved
                                </span>
                            @elseif($customer->approval_status === 'pending')
                                <span style="background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:20px;font-size:0.76rem;font-weight:700;border:1px solid #fde68a;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    ⏳ Pending
                                </span>
                            @else
                                <span style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:20px;font-size:0.76rem;font-weight:700;border:1px solid #fecaca;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    ✕ Rejected
                                </span>
                            @endif
                        </td>
                        <td style="padding:14px 18px;font-size:0.84rem;color:#64748b;white-space:nowrap;">
                            <div>{{ $customer->created_at->format('d M Y') }}</div>
                            <div style="font-size:0.75rem;color:#94a3b8;">{{ $customer->created_at->diffForHumans() }}</div>
                        </td>
                        <td style="padding:14px 18px;text-align:right;">
                            <div style="display:inline-flex;gap:6px;align-items:center;justify-content:flex-end;flex-wrap:nowrap;">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-secondary btn-sm" style="font-size:0.78rem;padding:6px 12px;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                                    View
                                </a>
                                @if($customer->approval_status === 'pending')
                                    <form action="{{ route('admin.customers.approve', $customer) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm" style="padding:6px 10px;font-size:0.78rem;background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;font-weight:700;border-radius:8px;cursor:pointer;" title="Approve Account">
                                            ✓ Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.customers.reject', $customer) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="reason" value="Application does not meet requirements.">
                                        <button type="submit" class="btn btn-danger btn-sm" style="padding:6px 10px;font-size:0.78rem;font-weight:700;" title="Reject Account">
                                            ✕ Reject
                                        </button>
                                    </form>
                                @elseif($customer->approval_status === 'approved' && in_array($customer->customer_group, ['wholesale','trading']))
                                    <form action="{{ route('admin.customers.reject', $customer) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" style="padding:6px 10px;font-size:0.78rem;font-weight:600;" onclick="return confirm('Revoke B2B access for {{ addslashes($customer->name) }}?')">
                                            Revoke
                                        </button>
                                    </form>
                                @endif
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
        @foreach($customers as $customer)
        <div class="category-item-card" style="box-shadow:0 1px 3px rgba(0,0,0,0.03);">
            <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:12px;">
                <!-- Avatar -->
                <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;flex-shrink:0;box-shadow:0 2px 5px rgba(37,99,235,0.25);">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>

                <div style="flex:1;min-width:0;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:4px;">
                        <div style="min-width:0;">
                            <a href="{{ route('admin.customers.show', $customer) }}" style="font-weight:700;color:#0f172a;font-size:0.98rem;text-decoration:none;display:block;line-height:1.25;">
                                {{ $customer->name }}
                            </a>
                            <div style="font-size:0.78rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-top:2px;">
                                {{ $customer->email }}
                            </div>
                        </div>

                        @if($customer->approval_status === 'approved')
                            <span style="background:#dcfce7;color:#15803d;padding:3px 9px;border-radius:20px;font-size:0.72rem;font-weight:700;border:1px solid #bbf7d0;flex-shrink:0;white-space:nowrap;">
                                ✓ Approved
                            </span>
                        @elseif($customer->approval_status === 'pending')
                            <span style="background:#fef3c7;color:#92400e;padding:3px 9px;border-radius:20px;font-size:0.72rem;font-weight:700;border:1px solid #fde68a;flex-shrink:0;white-space:nowrap;">
                                ⏳ Pending
                            </span>
                        @else
                            <span style="background:#fee2e2;color:#991b1b;padding:3px 9px;border-radius:20px;font-size:0.72rem;font-weight:700;border:1px solid #fecaca;flex-shrink:0;white-space:nowrap;">
                                ✕ Rejected
                            </span>
                        @endif
                    </div>

                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-top:6px;">
                        <span class="group-badge group-{{ $customer->customer_group }}" style="font-size:0.72rem;padding:2px 8px;">
                            {{ ucfirst($customer->customer_group) }}
                        </span>
                        @if($customer->company_name)
                            <span style="font-size:0.78rem;color:#475569;background:#f1f5f9;padding:2px 8px;border-radius:6px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px;">
                                🏢 {{ $customer->company_name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Meta Row -->
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#f8fafc;border-radius:8px;margin-bottom:12px;flex-wrap:wrap;gap:8px;border:1px solid #f1f5f9;">
                <div style="font-size:0.8rem;color:#475569;">
                    @if($customer->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone) }}" target="_blank" style="color:#16a34a;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                            💬 {{ $customer->phone }}
                        </a>
                    @else
                        <span style="color:#94a3b8;">No phone</span>
                    @endif
                </div>
                <div style="font-size:0.78rem;color:#64748b;">
                    Joined {{ $customer->created_at->format('d M Y') }} ({{ $customer->created_at->diffForHumans() }})
                </div>
            </div>

            <!-- Mobile Actions -->
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-secondary btn-sm" style="flex:1;min-width:120px;text-align:center;justify-content:center;font-weight:600;padding:8px 12px;font-size:0.82rem;">
                    View Profile
                </a>

                @if($customer->approval_status === 'pending')
                    <form action="{{ route('admin.customers.approve', $customer) }}" method="POST" style="margin:0;flex:1;min-width:100px;">
                        @csrf
                        <button type="submit" class="btn btn-sm" style="width:100%;padding:8px 10px;background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;font-weight:700;border-radius:8px;cursor:pointer;font-size:0.82rem;">
                            ✓ Approve
                        </button>
                    </form>
                    <form action="{{ route('admin.customers.reject', $customer) }}" method="POST" style="margin:0;flex:1;min-width:100px;">
                        @csrf
                        <input type="hidden" name="reason" value="Application does not meet requirements.">
                        <button type="submit" class="btn btn-danger btn-sm" style="width:100%;padding:8px 10px;font-weight:700;font-size:0.82rem;">
                            ✕ Reject
                        </button>
                    </form>
                @elseif($customer->approval_status === 'approved' && in_array($customer->customer_group, ['wholesale','trading']))
                    <form action="{{ route('admin.customers.reject', $customer) }}" method="POST" style="margin:0;flex:1;min-width:110px;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" style="width:100%;padding:8px 10px;font-weight:600;font-size:0.82rem;" onclick="return confirm('Revoke B2B access for {{ addslashes($customer->name) }}?')">
                            Revoke B2B
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination & Counter Bar -->
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-top:22px;padding:14px 20px;background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
        <div style="font-size:0.86rem;color:#64748b;">
            Showing <strong style="color:#0f172a;">{{ $customers->firstItem() ?? 0 }}</strong> to <strong style="color:#0f172a;">{{ $customers->lastItem() ?? 0 }}</strong> of <strong style="color:#0f172a;">{{ number_format($customers->total()) }}</strong> customers
        </div>
        <div>
            {{ $customers->links() }}
        </div>
    </div>

@else
    <!-- Empty State -->
    <div class="card" style="padding:64px 20px;text-align:center;border-radius:12px;border:1px solid #e2e8f0;background:#ffffff;">
        <div style="font-size:3.5rem;margin-bottom:14px;">👥</div>
        <h3 style="font-size:1.25rem;font-weight:700;color:#0f172a;margin:0 0 6px;">No Customers Found</h3>
        <p class="text-sm text-muted" style="margin:0 auto 20px;max-width:440px;color:#64748b;line-height:1.5;">
            @if(request()->hasAny(['search', 'group', 'status', 'sort']))
                No customer accounts match your search and filter criteria. Try adjusting or clearing your filters to see more results.
            @else
                No customer accounts have registered on the platform yet.
            @endif
        </p>
        @if(request()->hasAny(['search', 'group', 'status', 'sort']))
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary" style="font-weight:600;padding:10px 20px;display:inline-flex;align-items:center;gap:6px;">
                ✕ Clear All Filters
            </a>
        @endif
    </div>
@endif

@endsection
