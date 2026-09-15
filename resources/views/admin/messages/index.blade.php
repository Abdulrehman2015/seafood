@extends('layouts.admin')
@section('title', 'Customer Inquiries — Admin')

@section('content')

<style>
.msg-metrics-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 22px;
}
@media (max-width: 768px) {
    .msg-metrics-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}
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
    appearance: none !important;
    padding: 0 40px 0 14px !important;
    min-width: 140px;
    cursor: pointer;
}
.cat-search-group {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1 1 auto;
    min-width: 200px;
    height: 42px;
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
    .messages-table-desktop {
        display: none !important;
    }
    .messages-cards-mobile {
        display: flex !important;
        flex-direction: column;
        gap: 12px;
    }
}
@media (min-width: 769px) {
    .messages-table-desktop {
        display: block !important;
    }
    .messages-cards-mobile {
        display: none !important;
    }
}
</style>

<!-- Topbar Header -->
<div class="admin-topbar" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #e2e8f0;">
    <div>
        <h1 class="admin-page-title" style="font-size:clamp(1.35rem,2.5vw,1.75rem);font-weight:700;color:#0f172a;margin:0 0 4px;display:flex;align-items:center;gap:8px;">
            <span>✉️</span> Customer Inquiries
        </h1>
        <p class="text-sm text-muted" style="margin:0;color:#64748b;">
            Manage inquiries, feedback, and questions submitted via the Contact Us form
        </p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        @if($stats['unread'] > 0)
            <span style="background:#fffbeb;color:#b45309;font-size:0.85rem;font-weight:700;padding:8px 16px;border-radius:20px;border:1px solid #fde68a;display:inline-flex;align-items:center;gap:6px;">
                ● {{ number_format($stats['unread']) }} Unread
            </span>
        @endif
        <span style="background:#eff6ff;color:#1d4ed8;font-size:0.85rem;font-weight:700;padding:8px 16px;border-radius:20px;border:1px solid #bfdbfe;display:inline-flex;align-items:center;gap:6px;">
            ✉️ {{ number_format($stats['total']) }} Inquiries Total
        </span>
    </div>
</div>

<!-- Stat Metric Cards -->
<div class="msg-metrics-grid">
    <a href="{{ route('admin.messages.index') }}" class="category-metric-card {{ !request('filter') ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value">{{ number_format($stats['total']) }}</div>
            <div class="cat-metric-title">All Inquiries</div>
        </div>
        <div class="cat-metric-icon" style="background:#eff6ff;color:#2563eb;">✉️</div>
    </a>

    <a href="{{ route('admin.messages.index', array_merge(request()->query(), ['filter' => 'unread'])) }}" class="category-metric-card {{ request('filter') === 'unread' ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value" style="color:{{ $stats['unread'] > 0 ? '#d97706' : '#0f172a' }};">{{ number_format($stats['unread']) }}</div>
            <div class="cat-metric-title">Unread Inquiries</div>
        </div>
        <div class="cat-metric-icon" style="background:#fffbeb;color:#d97706;">
            @if($stats['unread'] > 0)
                <span style="position:relative;display:inline-block;">
                    ⏳
                    <span style="position:absolute;top:-4px;right:-4px;width:10px;height:10px;background:#ef4444;border-radius:50%;border:2px solid #fff;"></span>
                </span>
            @else
                ⏳
            @endif
        </div>
    </a>

    <a href="{{ route('admin.messages.index', array_merge(request()->query(), ['filter' => 'read'])) }}" class="category-metric-card {{ request('filter') === 'read' ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value" style="color:#15803d;">{{ number_format($stats['read']) }}</div>
            <div class="cat-metric-title">Archived / Read</div>
        </div>
        <div class="cat-metric-icon" style="background:#f0fdf4;color:#16a34a;">✅</div>
    </a>
</div>

<!-- Search & Filtering Toolbar -->
<div class="cat-filter-card" style="margin-bottom:22px;">
    <form method="GET" action="{{ route('admin.messages.index') }}" id="messageFilterForm">
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
                       placeholder="Search sender, email, phone, subject, or message...">
            </div>

            <select name="filter" class="cat-filter-select" onchange="document.getElementById('messageFilterForm').submit()">
                <option value="">All Inquiries</option>
                <option value="unread" {{ request('filter') === 'unread' ? 'selected' : '' }}>● Unread Only</option>
                <option value="read"   {{ request('filter') === 'read'   ? 'selected' : '' }}>✓ Read Only</option>
            </select>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary btn-sm" style="height:42px;padding:0 20px;font-weight:600;display:inline-flex;align-items:center;gap:6px;border-radius:10px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'filter']))
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary btn-sm" style="height:42px;padding:0 14px;display:inline-flex;align-items:center;gap:4px;border-radius:10px;" title="Clear all filters">
                        ✕ Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

@if($messages->count() > 0)

    <!-- ─── Desktop Table View (>= 769px) ─────────────────────────────────── -->
    <div class="card messages-table-desktop" style="border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);padding:0;margin-bottom:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
            <table class="table" style="margin:0;width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Sender</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Subject &amp; Message</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;">Received</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;text-align:center;">Status</th>
                        <th style="padding:13px 18px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;font-weight:700;text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $msg)
                    <tr style="border-bottom:1px solid #f1f5f9;{{ !$msg->is_read ? 'background:#f0fdfa;' : '' }}transition:background 0.15s ease;">
                        <td style="padding:14px 18px;">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:38px;height:38px;border-radius:50%;background:{{ !$msg->is_read ? 'linear-gradient(135deg,#0d9488,#0f766e)' : 'linear-gradient(135deg,#64748b,#475569)' }};color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.88rem;flex-shrink:0;">
                                    {{ strtoupper(substr($msg->name, 0, 1)) }}
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-weight:{{ !$msg->is_read ? '800' : '600' }};color:#0f172a;font-size:0.92rem;line-height:1.25;margin-bottom:2px;">
                                        <a href="{{ route('admin.messages.show', $msg) }}" style="color:inherit;text-decoration:none;">
                                            {{ $msg->name }}
                                        </a>
                                    </div>
                                    <div style="font-size:0.8rem;color:#64748b;">
                                        {{ $msg->email }}
                                    </div>
                                    @if($msg->phone)
                                        <div style="font-size:0.78rem;margin-top:2px;">
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $msg->phone) }}" target="_blank" style="color:#16a34a;text-decoration:none;font-weight:600;">
                                                💬 {{ $msg->phone }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="padding:14px 18px;max-width:380px;">
                            <div style="font-weight:{{ !$msg->is_read ? '700' : '600' }};color:{{ !$msg->is_read ? '#0f766e' : '#1e293b' }};font-size:0.92rem;margin-bottom:3px;">
                                <a href="{{ route('admin.messages.show', $msg) }}" style="color:inherit;text-decoration:none;">
                                    {{ $msg->subject ?? 'General Enquiry' }}
                                </a>
                            </div>
                            <div style="font-size:0.82rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ Str::limit($msg->message, 80) }}
                            </div>
                        </td>
                        <td style="padding:14px 18px;font-size:0.84rem;color:#64748b;white-space:nowrap;">
                            <div>{{ $msg->created_at->format('d M Y') }}</div>
                            <div style="font-size:0.75rem;color:#94a3b8;">{{ $msg->created_at->format('h:i A') }} ({{ $msg->created_at->diffForHumans() }})</div>
                        </td>
                        <td style="padding:14px 18px;text-align:center;">
                            @if(!$msg->is_read)
                                <span style="background:#ccfbf1;color:#0f766e;font-size:0.75rem;font-weight:800;padding:4px 10px;border-radius:20px;border:1px solid #99f6e4;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    ● Unread
                                </span>
                            @else
                                <span style="background:#f1f5f9;color:#64748b;font-size:0.75rem;font-weight:600;padding:4px 10px;border-radius:20px;border:1px solid #e2e8f0;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                                    ✓ Read
                                </span>
                            @endif
                        </td>
                        <td style="padding:14px 18px;text-align:right;">
                            <div style="display:inline-flex;gap:6px;align-items:center;justify-content:flex-end;">
                                <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn-primary btn-sm" style="font-size:0.78rem;padding:6px 14px;font-weight:600;border-radius:8px;">
                                    Read
                                </a>
                                <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" style="margin:0;" onsubmit="return confirm('Delete this customer inquiry?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color:#ef4444;border-color:#fecaca;padding:6px 10px;font-size:0.8rem;border-radius:8px;" title="Delete message">
                                        ✕
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

    <!-- ─── Mobile Cards View (< 769px) ───────────────────────────────────── -->
    <div class="messages-cards-mobile">
        @foreach($messages as $msg)
        <div class="category-item-card" style="box-shadow:0 1px 3px rgba(0,0,0,0.03);{{ !$msg->is_read ? 'border-left:4px solid #0d9488;background:#fbfefe;' : '' }}">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:8px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:38px;height:38px;border-radius:50%;background:{{ !$msg->is_read ? 'linear-gradient(135deg,#0d9488,#0f766e)' : 'linear-gradient(135deg,#64748b,#475569)' }};color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;flex-shrink:0;">
                        {{ strtoupper(substr($msg->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;color:#0f172a;font-size:0.95rem;">
                            {{ $msg->name }}
                        </div>
                        <div style="font-size:0.76rem;color:#64748b;">
                            {{ $msg->email }}
                        </div>
                    </div>
                </div>

                <div>
                    @if(!$msg->is_read)
                        <span style="background:#ccfbf1;color:#0f766e;font-size:0.7rem;font-weight:800;padding:2px 8px;border-radius:20px;border:1px solid #99f6e4;white-space:nowrap;">
                            ● Unread
                        </span>
                    @else
                        <span style="background:#f1f5f9;color:#64748b;font-size:0.7rem;font-weight:600;padding:2px 8px;border-radius:20px;border:1px solid #e2e8f0;white-space:nowrap;">
                            ✓ Read
                        </span>
                    @endif
                </div>
            </div>

            <div style="margin:10px 0;">
                <div style="font-weight:{{ !$msg->is_read ? '700' : '600' }};color:{{ !$msg->is_read ? '#0f766e' : '#1e293b' }};font-size:0.92rem;margin-bottom:3px;">
                    {{ $msg->subject ?? 'General Enquiry' }}
                </div>
                <div style="font-size:0.82rem;color:#475569;line-height:1.4;">
                    {{ Str::limit($msg->message, 100) }}
                </div>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 10px;background:#f8fafc;border-radius:8px;margin-bottom:12px;font-size:0.76rem;color:#64748b;">
                <div>
                    @if($msg->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $msg->phone) }}" target="_blank" style="color:#16a34a;text-decoration:none;font-weight:600;">
                            💬 {{ $msg->phone }}
                        </a>
                    @else
                        <span>No phone</span>
                    @endif
                </div>
                <div>{{ $msg->created_at->format('d M Y') }} ({{ $msg->created_at->diffForHumans() }})</div>
            </div>

            <div style="display:flex;gap:8px;">
                <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn-primary btn-sm" style="flex:1;text-align:center;justify-content:center;font-weight:600;padding:8px 12px;border-radius:8px;font-size:0.82rem;">
                    Read Message
                </a>
                <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" style="margin:0;" onsubmit="return confirm('Delete this inquiry?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-secondary btn-sm" style="color:#ef4444;border-color:#fecaca;padding:8px 14px;border-radius:8px;font-size:0.82rem;">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination & Counter Bar -->
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-top:22px;padding:14px 20px;background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
        <div style="font-size:0.86rem;color:#64748b;">
            Showing <strong style="color:#0f172a;">{{ $messages->firstItem() ?? 0 }}</strong> to <strong style="color:#0f172a;">{{ $messages->lastItem() ?? 0 }}</strong> of <strong style="color:#0f172a;">{{ number_format($messages->total()) }}</strong> inquiries
        </div>
        <div>
            {{ $messages->links() }}
        </div>
    </div>

@else
    <!-- Empty State -->
    <div class="card" style="padding:64px 20px;text-align:center;border-radius:12px;border:1px solid #e2e8f0;background:#ffffff;">
        <div style="font-size:3.5rem;margin-bottom:14px;">✉️</div>
        <h3 style="font-size:1.25rem;font-weight:700;color:#0f172a;margin:0 0 6px;">No Customer Inquiries Found</h3>
        <p class="text-sm text-muted" style="margin:0 auto 20px;max-width:440px;color:#64748b;line-height:1.5;">
            @if(request()->hasAny(['search', 'filter']))
                No messages match your search filter criteria. Try clearing filters to view all inquiries.
            @else
                No customer inquiries have been received through the website contact form yet.
            @endif
        </p>
        @if(request()->hasAny(['search', 'filter']))
            <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary" style="font-weight:600;padding:10px 20px;display:inline-flex;align-items:center;gap:6px;">
                ✕ Clear Filters
            </a>
        @endif
    </div>
@endif

@endsection
