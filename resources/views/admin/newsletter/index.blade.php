@extends('layouts.admin')
@section('title', 'Newsletter Subscribers — Admin')

@section('content')

<style>
/* ── Metric Cards ─────────────────────────────────── */
.nl-metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 22px;
}
@media (max-width: 900px) { .nl-metrics-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .nl-metrics-grid { grid-template-columns: 1fr 1fr; gap: 10px; } }

.nl-metric-card {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    transition: box-shadow 0.18s;
}
.nl-metric-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,0.07); }
.nl-metric-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.055em;
    color: #64748b;
}
.nl-metric-value { font-size: 1.85rem; font-weight: 800; line-height: 1; color: #0f172a; }
.nl-metric-card.green  { background: #f0fdf4 !important; border-color: #bbf7d0 !important; }
.nl-metric-card.green  .nl-metric-label { color: #15803d; }
.nl-metric-card.green  .nl-metric-value { color: #166534; }
.nl-metric-card.blue   { background: #eff6ff !important; border-color: #bfdbfe !important; }
.nl-metric-card.blue   .nl-metric-label { color: #1d4ed8; }
.nl-metric-card.blue   .nl-metric-value { color: #1e40af; }
.nl-metric-card.slate  { background: #f8fafc !important; border-color: #e2e8f0 !important; }
.nl-metric-card.slate  .nl-metric-label { color: #475569; }
.nl-metric-card.slate  .nl-metric-value { color: #334155; }

/* ── Filter Toolbar ───────────────────────────────── */
.nl-filter-card {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 18px;
    margin-bottom: 20px;
}
.nl-filter-row {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}
.nl-search-group {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1 1 auto;
    min-width: 200px;
    height: 42px;
}
.nl-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #94a3b8;
}
.nl-search-input {
    width: 100%;
    height: 42px !important;
    padding-left: 42px !important;
    padding-right: 14px !important;
    border-radius: 10px !important;
    border: 1.5px solid #cbd5e1 !important;
    font-size: 0.875rem !important;
    color: #1e293b !important;
    background: #fff !important;
    outline: none;
    transition: border-color 0.18s, box-shadow 0.18s;
}
.nl-search-input:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.1) !important;
}
.nl-filter-select {
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
    appearance: none !important;
    padding: 0 40px 0 14px !important;
    min-width: 155px;
    cursor: pointer;
}
.nl-btn {
    height: 42px;
    padding: 0 18px;
    border-radius: 10px;
    font-size: 0.86rem;
    font-weight: 600;
    border: 1.5px solid #cbd5e1;
    background: #fff;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.18s;
    white-space: nowrap;
}
.nl-btn:hover   { background: #f1f5f9; color: #1e293b; border-color: #94a3b8; }
.nl-btn.active  { background: #6366f1; color: #fff; border-color: #6366f1; }
.nl-btn.reset   { color: #ef4444; border-color: #fecaca; }
.nl-btn.reset:hover { background: #fef2f2; border-color: #ef4444; }

/* ── Table Card ───────────────────────────────────── */
.nl-table-card {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
}
.nl-table-card .nl-table-desktop { overflow-x: auto; }
.nl-table-card table { width: 100%; border-collapse: collapse; margin: 0; }
.nl-table-card thead th {
    background: #f8fafc;
    padding: 12px 16px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #64748b;
    border-bottom: 1.5px solid #e2e8f0;
    white-space: nowrap;
}
.nl-table-card tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.12s; }
.nl-table-card tbody tr:last-child { border-bottom: none; }
.nl-table-card tbody tr:hover { background: #f8fafc; }
.nl-table-card tbody td { padding: 13px 16px; vertical-align: middle; font-size: 0.875rem; color: #334155; }

/* ── Badges ───────────────────────────────────────── */
.nl-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 0.72rem; font-weight: 700; white-space: nowrap;
}
.nl-badge.active       { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.nl-badge.unsubscribed { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

/* ── Action Buttons ───────────────────────────────── */
.nl-action-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 8px;
    font-size: 0.78rem; font-weight: 600;
    cursor: pointer; border: 1.5px solid #e2e8f0;
    background: #f8fafc; color: #475569;
    transition: all 0.16s; white-space: nowrap;
}
.nl-action-btn:hover { background: #f1f5f9; border-color: #94a3b8; color: #1e293b; }
.nl-action-btn.danger { color: #ef4444; border-color: #fecaca; background: #fff; }
.nl-action-btn.danger:hover { background: #fef2f2; border-color: #ef4444; }

/* ── Mobile Cards ─────────────────────────────────── */
.nl-mobile-cards { display: none; }
@media (max-width: 768px) {
    .nl-table-desktop { display: none !important; }
    .nl-mobile-cards  { display: block; }
}
.nl-sub-card {
    padding: 16px;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.12s;
}
.nl-sub-card:last-child { border-bottom: none; }
.nl-sub-card:hover { background: #f8fafc; }
.nl-sub-email {
    font-weight: 700; color: #0f172a;
    font-size: 0.92rem; word-break: break-all; margin-bottom: 8px;
}
.nl-sub-meta {
    display: flex; gap: 10px; flex-wrap: wrap;
    font-size: 0.78rem; color: #64748b;
    margin-bottom: 10px; align-items: center;
}
.nl-sub-actions { display: flex; gap: 8px; flex-wrap: wrap; }

/* ── Topbar ───────────────────────────────────────── */
.nl-topbar {
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 12px;
    flex-wrap: wrap; margin-bottom: 22px;
}
.nl-export-btn {
    display: inline-flex; align-items: center; gap: 7px;
    height: 40px; padding: 0 18px; border-radius: 10px;
    font-size: 0.86rem; font-weight: 600;
    background: #fff; border: 1.5px solid #cbd5e1; color: #475569;
    text-decoration: none; transition: all 0.18s; white-space: nowrap;
}
.nl-export-btn:hover { background: #f8fafc; border-color: #94a3b8; color: #1e293b; }
</style>

{{-- Page Header --}}
<div class="nl-topbar">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;margin:0 0 4px">Newsletter Subscribers</h1>
        <p style="margin:0;color:#64748b;font-size:0.875rem">Manage email subscriptions captured from the homepage newsletter section</p>
    </div>
    <a href="{{ route('admin.newsletter.export', request()->query()) }}" class="nl-export-btn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Export CSV
    </a>
</div>

{{-- Metric Cards --}}
<div class="nl-metrics-grid">
    <div class="nl-metric-card">
        <div class="nl-metric-label">Total Subscribers</div>
        <div class="nl-metric-value">{{ number_format($stats['total']) }}</div>
    </div>
    <div class="nl-metric-card green">
        <div class="nl-metric-label">Active</div>
        <div class="nl-metric-value">{{ number_format($stats['active']) }}</div>
    </div>
    <div class="nl-metric-card slate">
        <div class="nl-metric-label">Unsubscribed</div>
        <div class="nl-metric-value">{{ number_format($stats['unsubscribed']) }}</div>
    </div>
    <div class="nl-metric-card blue">
        <div class="nl-metric-label">Joined This Month</div>
        <div class="nl-metric-value">+{{ number_format($stats['this_month']) }}</div>
    </div>
</div>

{{-- Filter Toolbar --}}
<div class="nl-filter-card">
    <form action="{{ route('admin.newsletter.index') }}" method="GET" class="nl-filter-row">
        <div class="nl-search-group">
            <svg class="nl-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by email address..."
                   class="nl-search-input">
        </div>

        <select name="status" class="nl-filter-select" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            <option value="active"       {{ request('status') === 'active'       ? 'selected' : '' }}>Active</option>
            <option value="unsubscribed" {{ request('status') === 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
        </select>

        <button type="submit" class="nl-btn active">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Search
        </button>

        @if(request('search') || request('status'))
            <a href="{{ route('admin.newsletter.index') }}" class="nl-btn reset">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Subscribers Table --}}
<div class="nl-table-card">

    {{-- Desktop Table --}}
    <div class="nl-table-desktop">
        <table>
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th>Email Address</th>
                    <th>Status</th>
                    <th>IP Address</th>
                    <th>Subscribed On</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $sub)
                <tr>
                    <td style="color:#94a3b8;font-size:0.8rem;font-weight:600">#{{ $sub->id }}</td>
                    <td><span style="font-weight:600;color:#0f172a">{{ $sub->email }}</span></td>
                    <td>
                        @if($sub->status === 'active')
                            <span class="nl-badge active">
                                <svg width="7" height="7" viewBox="0 0 8 8" fill="#15803d"><circle cx="4" cy="4" r="4"/></svg>
                                Active
                            </span>
                        @else
                            <span class="nl-badge unsubscribed">Unsubscribed</span>
                        @endif
                    </td>
                    <td style="font-size:0.8rem;color:#64748b;font-family:monospace">{{ $sub->ip_address ?? '—' }}</td>
                    <td>
                        <div style="font-size:0.85rem;color:#334155">{{ $sub->created_at->format('d M Y, h:i A') }}</div>
                        <div style="font-size:0.72rem;color:#94a3b8">{{ $sub->created_at->diffForHumans() }}</div>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap">
                            <form action="{{ route('admin.newsletter.toggleStatus', $sub) }}" method="POST">
                                @csrf
                                <button type="submit" class="nl-action-btn">
                                    @if($sub->status === 'active')
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                        Deactivate
                                    @else
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        Activate
                                    @endif
                                </button>
                            </form>
                            <form action="{{ route('admin.newsletter.destroy', $sub) }}" method="POST"
                                  onsubmit="return confirm('Remove {{ addslashes($sub->email) }} from subscribers?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="nl-action-btn danger" title="Delete">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:56px 24px;text-align:center">
                        <div style="font-size:2.8rem;margin-bottom:10px">📬</div>
                        <div style="font-weight:700;color:#334155;font-size:1rem;margin-bottom:4px">No subscribers found</div>
                        <div style="font-size:0.82rem;color:#94a3b8">When visitors submit their email in the homepage newsletter section, they will appear here.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="nl-mobile-cards">
        @forelse($subscribers as $sub)
        <div class="nl-sub-card">
            <div class="nl-sub-email">{{ $sub->email }}</div>
            <div class="nl-sub-meta">
                @if($sub->status === 'active')
                    <span class="nl-badge active">
                        <svg width="7" height="7" viewBox="0 0 8 8" fill="#15803d"><circle cx="4" cy="4" r="4"/></svg>
                        Active
                    </span>
                @else
                    <span class="nl-badge unsubscribed">Unsubscribed</span>
                @endif
                <span>{{ $sub->created_at->format('d M Y') }}</span>
                <span style="color:#94a3b8">{{ $sub->created_at->diffForHumans() }}</span>
                @if($sub->ip_address)
                    <span style="font-family:monospace;font-size:0.75rem">{{ $sub->ip_address }}</span>
                @endif
            </div>
            <div class="nl-sub-actions">
                <form action="{{ route('admin.newsletter.toggleStatus', $sub) }}" method="POST">
                    @csrf
                    <button type="submit" class="nl-action-btn">
                        {{ $sub->status === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <form action="{{ route('admin.newsletter.destroy', $sub) }}" method="POST"
                      onsubmit="return confirm('Remove {{ addslashes($sub->email) }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="nl-action-btn danger">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div style="padding:56px 24px;text-align:center">
            <div style="font-size:2.8rem;margin-bottom:10px">📬</div>
            <div style="font-weight:700;color:#334155;font-size:1rem;margin-bottom:4px">No subscribers found</div>
            <div style="font-size:0.82rem;color:#94a3b8">When visitors submit their email in the homepage newsletter section, they will appear here.</div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($subscribers->hasPages())
    <div style="padding:14px 18px;border-top:1.5px solid #f1f5f9;background:#fafafa;display:flex;justify-content:center">
        {{ $subscribers->appends(request()->query())->links() }}
    </div>
    @endif

</div>

@endsection
