@extends('layouts.admin')
@section('title', 'Customer Reviews — Admin')

@section('content')

<style>
/* ── Reviews Page Styles ─────────────────────────────── */

/* Metric Cards */
.rev-metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 22px;
}
@media (max-width: 960px) {
    .rev-metrics-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
}
@media (max-width: 480px) {
    .rev-metrics-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
}

.rev-metric-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.rev-metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08);
}
.rev-metric-card.active {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
}

.rev-metric-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    margin-bottom: 4px;
}
.rev-metric-value {
    font-size: 1.85rem;
    font-weight: 800;
    line-height: 1;
    color: #0f172a;
}
.rev-metric-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.rev-metric-card.total .rev-metric-icon { background: #f1f5f9; color: #475569; }
.rev-metric-card.green { background: #f0fdf4; border-color: #bbf7d0; }
.rev-metric-card.green .rev-metric-label { color: #15803d; }
.rev-metric-card.green .rev-metric-value { color: #166534; }
.rev-metric-card.green .rev-metric-icon { background: #dcfce7; color: #15803d; }

.rev-metric-card.amber { background: #fffbeb; border-color: #fde68a; }
.rev-metric-card.amber .rev-metric-label { color: #b45309; }
.rev-metric-card.amber .rev-metric-value { color: #92400e; }
.rev-metric-card.amber .rev-metric-icon { background: #fef3c7; color: #b45309; }

.rev-metric-card.blue { background: #eff6ff; border-color: #bfdbfe; }
.rev-metric-card.blue .rev-metric-label { color: #1d4ed8; }
.rev-metric-card.blue .rev-metric-value { color: #1e40af; }
.rev-metric-card.blue .rev-metric-icon { background: #dbeafe; color: #1d4ed8; }

/* Filter Toolbar */
.rev-filter-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 18px;
    margin-bottom: 20px;
}
.rev-filter-row {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}
.rev-search-group {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1 1 220px;
    height: 42px !important;
    max-height: 42px !important;
    box-sizing: border-box !important;
}
.rev-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}
.rev-search-input {
    width: 100% !important;
    height: 42px !important;
    max-height: 42px !important;
    padding-left: 44px !important;
    padding-right: 14px !important;
    border-radius: 10px !important;
    border: 1.5px solid #cbd5e1 !important;
    font-size: 0.875rem !important;
    color: #1e293b !important;
    background: #ffffff !important;
    box-sizing: border-box !important;
    outline: none;
    transition: border-color 0.18s, box-shadow 0.18s;
}
.rev-search-input:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
}
.rev-filter-select {
    height: 42px !important;
    max-height: 42px !important;
    line-height: 42px !important;
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
    padding: 0 38px 0 14px !important;
    flex: 0 0 auto;
    width: auto;
    min-width: 140px;
    cursor: pointer;
    box-sizing: border-box !important;
}
.rev-btn {
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
    box-sizing: border-box !important;
}
.rev-btn:hover { background: #f1f5f9; color: #1e293b; border-color: #94a3b8; }
.rev-btn.primary { background: #2563eb; color: #fff; border-color: #2563eb; }
.rev-btn.primary:hover { background: #1d4ed8; border-color: #1d4ed8; }
.rev-btn.reset { color: #ef4444; border-color: #fecaca; }
.rev-btn.reset:hover { background: #fef2f2; border-color: #ef4444; }

/* Table and Card Container */
.rev-table-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.rev-table-desktop { overflow-x: auto; }
.rev-table-desktop table { width: 100%; border-collapse: collapse; margin: 0; }
.rev-table-desktop thead th {
    background: #f8fafc;
    padding: 13px 18px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.055em;
    color: #64748b;
    border-bottom: 1.5px solid #e2e8f0;
    white-space: nowrap;
}
.rev-table-desktop tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.12s ease;
}
.rev-table-desktop tbody tr:last-child { border-bottom: none; }
.rev-table-desktop tbody tr:hover { background: #f8fafc; }
.rev-table-desktop tbody td {
    padding: 14px 18px;
    vertical-align: middle;
    font-size: 0.875rem;
    color: #334155;
}

/* Badges */
.rev-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 0.74rem;
    font-weight: 700;
    white-space: nowrap;
}
.rev-badge.approved { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.rev-badge.pending { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.rev-badge.featured { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }

/* Stars Rating */
.star-rating {
    color: #f59e0b;
    font-size: 0.95rem;
    letter-spacing: 1px;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
}
.star-rating-score {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    margin-left: 6px;
    background: #f1f5f9;
    padding: 1px 6px;
    border-radius: 6px;
}

/* Action Buttons */
.rev-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 11px;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    transition: all 0.16s ease;
    text-decoration: none;
    white-space: nowrap;
}
.rev-action-btn:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
}
.rev-action-btn.feature {
    color: #d97706;
    border-color: #fde68a;
    background: #fffdf5;
}
.rev-action-btn.feature:hover {
    background: #fef3c7;
    border-color: #f59e0b;
}
.rev-action-btn.danger {
    color: #ef4444;
    border-color: #fecaca;
    background: #ffffff;
}
.rev-action-btn.danger:hover {
    background: #fef2f2;
    border-color: #ef4444;
}

/* Toggle Status Pill Button */
.status-toggle-btn {
    border: none;
    background: transparent;
    padding: 0;
    cursor: pointer;
    border-radius: 9999px;
    transition: transform 0.15s ease, opacity 0.15s ease;
}
.status-toggle-btn:hover {
    transform: scale(1.04);
    opacity: 0.92;
}

/* Mobile Cards View */
.rev-mobile-cards { display: none; }

@media (max-width: 768px) {
    .rev-table-desktop { display: none !important; }
    .rev-mobile-cards { display: flex; flex-direction: column; }
    
    .rev-filter-card {
        padding: 14px 14px !important;
    }
    .rev-filter-row {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 10px !important;
    }
    .rev-search-group {
        flex: none !important;
        width: 100% !important;
        height: 42px !important;
        max-height: 42px !important;
        min-height: 42px !important;
    }
    .rev-filter-select {
        flex: none !important;
        width: 100% !important;
        height: 42px !important;
        max-height: 42px !important;
        min-height: 42px !important;
        line-height: 42px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .rev-filter-actions {
        flex: none !important;
        width: 100% !important;
        display: flex !important;
        gap: 8px !important;
    }
    .rev-filter-actions .rev-btn {
        flex: 1 !important;
        justify-content: center !important;
    }
    .rev-filter-count {
        margin-left: 0 !important;
        text-align: center !important;
        padding-top: 4px !important;
        display: block !important;
        width: 100% !important;
    }
}

.rev-card-item {
    padding: 18px;
    border-bottom: 1.5px solid #f1f5f9;
    background: #ffffff;
    transition: background 0.15s ease;
}
.rev-card-item:last-child { border-bottom: none; }
.rev-card-item:hover { background: #fafcff; }

.rev-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 12px;
}
.rev-card-author {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}
.rev-card-name {
    font-weight: 700;
    color: #0f172a;
    font-size: 0.95rem;
    line-height: 1.25;
    margin-bottom: 2px;
}
.rev-card-role {
    font-size: 0.78rem;
    color: #64748b;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.rev-card-quote {
    background: #f8fafc;
    border-left: 3px solid #cbd5e1;
    padding: 10px 14px;
    border-radius: 0 8px 8px 0;
    font-size: 0.86rem;
    line-height: 1.55;
    color: #334155;
    margin-bottom: 14px;
    font-style: italic;
    position: relative;
}

.rev-card-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 14px;
}

.rev-card-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
    border-top: 1px dashed #e2e8f0;
    padding-top: 12px;
}

/* Modal Styling */
.rev-modal-backdrop {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(3px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.rev-modal-backdrop.active { display: flex; }
.rev-modal {
    background: #ffffff;
    border-radius: 16px;
    max-width: 580px;
    width: 100%;
    padding: 28px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    position: relative;
    max-height: 90vh;
    overflow-y: auto;
}
.rev-modal-close {
    position: absolute;
    top: 18px;
    right: 18px;
    background: #f1f5f9;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.1rem;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s;
}
.rev-modal-close:hover { background: #e2e8f0; color: #0f172a; }
</style>

{{-- Page Topbar Header --}}
<div class="admin-topbar" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #e2e8f0;">
    <div>
        <h1 class="admin-page-title" style="font-size:clamp(1.35rem,2.5vw,1.75rem);font-weight:800;color:#0f172a;margin:0 0 4px;display:flex;align-items:center;gap:10px;">
            <span style="color:#f59e0b">★</span> Customer Reviews &amp; Testimonials
        </h1>
        <p class="text-sm text-muted" style="margin:0;color:#64748b;font-size:0.875rem">
            Curate customer reviews, 5-star ratings, and endorsements featured on the Storefront Homepage, About, and Contact pages.
        </p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;font-weight:700;border-radius:10px;box-shadow:0 2px 6px rgba(37,99,235,0.25);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add New Review
        </a>
    </div>
</div>

{{-- Interactive Metric Cards --}}
<div class="rev-metrics-grid">
    <a href="{{ route('admin.reviews.index') }}" 
       class="rev-metric-card total {{ (!request('status') && !request('featured')) ? 'active' : '' }}"
       title="Show all reviews">
        <div>
            <div class="rev-metric-label">Total Reviews</div>
            <div class="rev-metric-value">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="rev-metric-icon">💬</div>
    </a>

    <a href="{{ route('admin.reviews.index', array_merge(request()->except(['status', 'page']), ['status' => 'approved'])) }}" 
       class="rev-metric-card green {{ request('status') === 'approved' ? 'active' : '' }}"
       title="Filter by approved reviews">
        <div>
            <div class="rev-metric-label">Approved</div>
            <div class="rev-metric-value">{{ number_format($stats['approved']) }}</div>
        </div>
        <div class="rev-metric-icon">✅</div>
    </a>

    <a href="{{ route('admin.reviews.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}" 
       class="rev-metric-card amber {{ request('status') === 'pending' ? 'active' : '' }}"
       title="Filter by pending moderation">
        <div>
            <div class="rev-metric-label">Pending Moderation</div>
            <div class="rev-metric-value">{{ number_format($stats['pending']) }}</div>
        </div>
        <div class="rev-metric-icon">⏳</div>
    </a>

    <a href="{{ route('admin.reviews.index', array_merge(request()->except(['featured', 'page']), ['featured' => '1'])) }}" 
       class="rev-metric-card blue {{ request('featured') == '1' ? 'active' : '' }}"
       title="Filter by featured reviews">
        <div>
            <div class="rev-metric-label">Featured on Home</div>
            <div class="rev-metric-value">{{ number_format($stats['featured']) }}</div>
        </div>
        <div class="rev-metric-icon">⭐</div>
    </a>
</div>

{{-- Search & Filtering Toolbar --}}
<div class="rev-filter-card">
    <form action="{{ route('admin.reviews.index') }}" method="GET" class="rev-filter-row" id="reviewFilterForm">
        <div class="rev-search-group">
            <span class="rev-search-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by author name, company, or testimonial text..."
                   class="rev-search-input"
                   style="padding-left: 46px !important;">
        </div>

        <select name="status" class="rev-filter-select" onchange="document.getElementById('reviewFilterForm').submit()">
            <option value="">All Statuses</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>● Approved Only</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>○ Pending Only</option>
        </select>

        <select name="featured" class="rev-filter-select" onchange="document.getElementById('reviewFilterForm').submit()">
            <option value="">All Placement</option>
            <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>★ Featured on Home</option>
            <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Standard (Not Featured)</option>
        </select>

        <div class="rev-filter-actions" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <button type="submit" class="rev-btn primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Filter
            </button>

            @if(request()->anyFilled(['search', 'status', 'featured']))
                <a href="{{ route('admin.reviews.index') }}" class="rev-btn reset" title="Clear all filters">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Reset
                </a>
            @endif
        </div>

        <div class="rev-filter-count" style="margin-left:auto;font-size:0.82rem;color:#64748b;font-weight:600;white-space:nowrap">
            Showing {{ $reviews->total() }} review(s)
        </div>
    </form>
</div>

{{-- Reviews Container (Desktop Table + Mobile Cards) --}}
<div class="rev-table-card">

    {{-- ── Desktop Table (>= 769px) ────────────────────── --}}
    <div class="rev-table-desktop">
        <table>
            <thead>
                <tr>
                    <th style="width:26%">Customer / Author</th>
                    <th style="width:15%">Rating</th>
                    <th style="width:33%">Feedback Snippet</th>
                    <th style="width:11%;text-align:center">Moderation</th>
                    <th style="width:15%;text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $item)
                <tr>
                    {{-- Author --}}
                    <td>
                        <div style="display:flex;align-items:center;gap:12px">
                            @if($item->avatar)
                                <img src="{{ asset('storage/'.$item->avatar) }}" alt="{{ $item->name }}" 
                                     style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:1.5px solid #e2e8f0;flex-shrink:0">
                            @else
                                <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg, #0d9488, #0f766e);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.88rem;flex-shrink:0;box-shadow:0 2px 4px rgba(13,148,136,0.2)">
                                    {{ $item->initials }}
                                </div>
                            @endif
                            <div style="min-width:0">
                                <a href="{{ route('admin.reviews.edit', $item) }}" style="font-weight:700;color:#0f172a;text-decoration:none;font-size:0.92rem;display:block;line-height:1.3">
                                    {{ $item->name }}
                                </a>
                                <span style="font-size:0.78rem;color:#64748b;display:block;margin-top:2px">
                                    {{ $item->role_or_company ?? 'Verified Customer' }}
                                </span>
                            </div>
                        </div>
                    </td>

                    {{-- Rating --}}
                    <td>
                        <div class="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <span style="color:{{ $i <= $item->rating ? '#f59e0b' : '#cbd5e1' }}">★</span>
                            @endfor
                            <span class="star-rating-score">{{ $item->rating }}.0</span>
                        </div>
                        <div style="font-size:0.74rem;color:#64748b;margin-top:3px;font-weight:500">
                            @if($item->rating == 5) Exceptional
                            @elseif($item->rating == 4) Very Good
                            @elseif($item->rating == 3) Good
                            @elseif($item->rating == 2) Fair
                            @else Poor
                            @endif
                        </div>
                    </td>

                    {{-- Feedback Snippet --}}
                    <td>
                        <div style="font-size:0.875rem;color:#334155;line-height:1.55;font-style:italic">
                            “{{ Str::limit($item->comment, 110) }}”
                        </div>
                        <div style="display:flex;gap:8px;align-items:center;margin-top:6px;flex-wrap:wrap">
                            @if($item->is_featured)
                                <span class="rev-badge featured" title="Shown on Homepage">
                                    ★ Featured on Home
                                </span>
                            @endif
                            @if(strlen($item->comment) > 110)
                                <button type="button" 
                                        onclick="openReviewModal({{ json_encode($item->name) }}, {{ json_encode($item->role_or_company ?? 'Verified Customer') }}, {{ $item->rating }}, {{ json_encode($item->comment) }}, {{ $item->is_featured ? 'true' : 'false' }}, {{ json_encode($item->status) }}, {{ json_encode($item->avatar ? asset('storage/'.$item->avatar) : null) }}, {{ json_encode($item->initials) }})"
                                        style="background:none;border:none;color:#2563eb;font-size:0.75rem;font-weight:600;padding:0;cursor:pointer;text-decoration:underline">
                                    Read Full Review
                                </button>
                            @endif
                        </div>
                    </td>

                    {{-- Status Toggle Button --}}
                    <td style="text-align:center">
                        <form action="{{ route('admin.reviews.toggleStatus', $item) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="status-toggle-btn" title="Click to toggle status (Approved / Pending)">
                                @if($item->status === 'approved')
                                    <span class="rev-badge approved">
                                        <svg width="7" height="7" viewBox="0 0 8 8" fill="#059669"><circle cx="4" cy="4" r="4"/></svg>
                                        Approved
                                    </span>
                                @else
                                    <span class="rev-badge pending">
                                        <svg width="7" height="7" viewBox="0 0 8 8" fill="#d97706"><circle cx="4" cy="4" r="4"/></svg>
                                        Pending
                                    </span>
                                @endif
                            </button>
                        </form>
                    </td>

                    {{-- Actions --}}
                    <td style="text-align:right">
                        <div style="display:inline-flex;gap:6px;align-items:center;justify-content:flex-end">
                            {{-- Quick Toggle Featured --}}
                            <form action="{{ route('admin.reviews.toggleFeatured', $item) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="rev-action-btn feature" title="{{ $item->is_featured ? 'Remove from Featured' : 'Mark as Featured on Homepage' }}">
                                    @if($item->is_featured)
                                        <span style="color:#f59e0b">★</span> Unfeature
                                    @else
                                        <span style="color:#94a3b8">☆</span> Feature
                                    @endif
                                </button>
                            </form>

                            {{-- Edit --}}
                            <a href="{{ route('admin.reviews.edit', $item) }}" class="rev-action-btn" title="Edit review details">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('admin.reviews.destroy', $item) }}" method="POST" style="display:inline"
                                  onsubmit="return confirm('Permanently delete customer review from {{ addslashes($item->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rev-action-btn danger" title="Delete review">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:60px 24px;text-align:center">
                        <div style="font-size:2.6rem;margin-bottom:12px">⭐</div>
                        <div style="font-weight:700;color:#1e293b;font-size:1.05rem;margin-bottom:6px">No customer reviews found</div>
                        <div style="font-size:0.85rem;color:#64748b;max-width:420px;margin:0 auto 18px">
                            @if(request()->anyFilled(['search', 'status', 'featured']))
                                No reviews match your filter criteria. Try adjusting or clearing your filters.
                            @else
                                No customer testimonials have been created yet. Click below to add your first customer review.
                            @endif
                        </div>
                        @if(request()->anyFilled(['search', 'status', 'featured']))
                            <a href="{{ route('admin.reviews.index') }}" class="rev-btn primary">Reset All Filters</a>
                        @else
                            <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary btn-sm">+ Add New Review</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Mobile Cards List (<= 768px) ────────────────── --}}
    <div class="rev-mobile-cards">
        @forelse($reviews as $item)
        <div class="rev-card-item">
            {{-- Header with Avatar & Name & Stars --}}
            <div class="rev-card-header">
                <div class="rev-card-author">
                    @if($item->avatar)
                        <img src="{{ asset('storage/'.$item->avatar) }}" alt="{{ $item->name }}" 
                             style="width:44px;height:44px;border-radius:50%;object-fit:cover;border:1.5px solid #e2e8f0;flex-shrink:0">
                    @else
                        <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg, #0d9488, #0f766e);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;flex-shrink:0">
                            {{ $item->initials }}
                        </div>
                    @endif
                    <div style="min-width:0">
                        <div class="rev-card-name">
                            <a href="{{ route('admin.reviews.edit', $item) }}" style="color:inherit;text-decoration:none">
                                {{ $item->name }}
                            </a>
                        </div>
                        <div class="rev-card-role">{{ $item->role_or_company ?? 'Verified Customer' }}</div>
                    </div>
                </div>

                <div style="text-align:right;flex-shrink:0">
                    <div class="star-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <span style="color:{{ $i <= $item->rating ? '#f59e0b' : '#cbd5e1' }}">★</span>
                        @endfor
                    </div>
                    <div style="font-size:0.72rem;color:#64748b;font-weight:700">{{ $item->rating }} / 5 Stars</div>
                </div>
            </div>

            {{-- Quote text --}}
            <div class="rev-card-quote">
                “{{ $item->comment }}”
            </div>

            {{-- Badges row --}}
            <div class="rev-card-badges">
                <form action="{{ route('admin.reviews.toggleStatus', $item) }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="status-toggle-btn">
                        @if($item->status === 'approved')
                            <span class="rev-badge approved">
                                <svg width="7" height="7" viewBox="0 0 8 8" fill="#059669"><circle cx="4" cy="4" r="4"/></svg>
                                Approved (Tap to toggle)
                            </span>
                        @else
                            <span class="rev-badge pending">
                                <svg width="7" height="7" viewBox="0 0 8 8" fill="#d97706"><circle cx="4" cy="4" r="4"/></svg>
                                Pending (Tap to toggle)
                            </span>
                        @endif
                    </button>
                </form>

                @if($item->is_featured)
                    <span class="rev-badge featured">★ Featured on Home</span>
                @endif
            </div>

            {{-- Mobile Action Buttons --}}
            <div class="rev-card-actions">
                <form action="{{ route('admin.reviews.toggleFeatured', $item) }}" method="POST" style="flex:1">
                    @csrf
                    <button type="submit" class="rev-action-btn feature" style="width:100%;justify-content:center">
                        @if($item->is_featured)
                            <span style="color:#f59e0b">★</span> Unfeature
                        @else
                            <span style="color:#94a3b8">☆</span> Feature on Home
                        @endif
                    </button>
                </form>

                <a href="{{ route('admin.reviews.edit', $item) }}" class="rev-action-btn" style="flex:1;justify-content:center">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>

                <form action="{{ route('admin.reviews.destroy', $item) }}" method="POST"
                      onsubmit="return confirm('Permanently delete review from {{ addslashes($item->name) }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rev-action-btn danger" style="padding:6px 12px">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="padding:50px 20px;text-align:center">
            <div style="font-size:2.6rem;margin-bottom:12px">⭐</div>
            <div style="font-weight:700;color:#1e293b;font-size:1rem;margin-bottom:6px">No customer reviews found</div>
            <div style="font-size:0.85rem;color:#64748b;margin-bottom:16px">
                @if(request()->anyFilled(['search', 'status', 'featured']))
                    No reviews match your filters.
                @else
                    Add your first customer testimonial.
                @endif
            </div>
            @if(request()->anyFilled(['search', 'status', 'featured']))
                <a href="{{ route('admin.reviews.index') }}" class="rev-btn primary" style="width:100%;justify-content:center">Reset All Filters</a>
            @else
                <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary btn-sm">+ Add New Review</a>
            @endif
        </div>
        @endforelse
    </div>

    {{-- Pagination Bar --}}
    @if($reviews->hasPages())
    <div style="padding:14px 20px;border-top:1.5px solid #f1f5f9;background:#fafbfc;display:flex;justify-content:center">
        {{ $reviews->appends(request()->query())->links() }}
    </div>
    @endif

</div>

{{-- Full Review Preview Modal --}}
<div class="rev-modal-backdrop" id="reviewModalBackdrop" onclick="closeReviewModal(event)">
    <div class="rev-modal" onclick="event.stopPropagation()">
        <button class="rev-modal-close" onclick="closeReviewModal()">✕</button>
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
            <div id="modalAvatarContainer"></div>
            <div>
                <h3 id="modalAuthorName" style="margin:0 0 4px;font-size:1.15rem;font-weight:800;color:#0f172a"></h3>
                <div id="modalAuthorRole" style="font-size:0.82rem;color:#64748b"></div>
            </div>
        </div>

        <div style="display:flex;gap:10px;align-items:center;margin-bottom:16px;flex-wrap:wrap">
            <div id="modalStars" class="star-rating" style="font-size:1.1rem"></div>
            <span id="modalScore" class="star-rating-score" style="font-size:0.82rem"></span>
            <span id="modalStatusBadge" class="rev-badge"></span>
            <span id="modalFeaturedBadge" class="rev-badge featured" style="display:none">★ Featured</span>
        </div>

        <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;margin-bottom:6px">
            Customer Testimonial
        </div>
        <div id="modalComment" style="background:#f8fafc;border-left:3px solid #0d9488;padding:16px 18px;border-radius:0 10px 10px 0;font-size:0.95rem;line-height:1.65;color:#334155;font-style:italic;margin-bottom:20px">
        </div>

        <div style="display:flex;justify-content:flex-end">
            <button type="button" onclick="closeReviewModal()" class="rev-btn" style="padding:0 20px">Close</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openReviewModal(name, role, rating, comment, isFeatured, status, avatarUrl, initials) {
    document.getElementById('modalAuthorName').textContent = name;
    document.getElementById('modalAuthorRole').textContent = role;
    document.getElementById('modalComment').textContent = '“' + comment + '”';
    document.getElementById('modalScore').textContent = rating + '.0 / 5';

    // Stars
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        starsHtml += `<span style="color:${i <= rating ? '#f59e0b' : '#cbd5e1'}">★</span>`;
    }
    document.getElementById('modalStars').innerHTML = starsHtml;

    // Status Badge
    const statusBadge = document.getElementById('modalStatusBadge');
    if (status === 'approved') {
        statusBadge.className = 'rev-badge approved';
        statusBadge.innerHTML = '● Approved';
    } else {
        statusBadge.className = 'rev-badge pending';
        statusBadge.innerHTML = '○ Pending';
    }

    // Featured Badge
    const featBadge = document.getElementById('modalFeaturedBadge');
    featBadge.style.display = isFeatured ? 'inline-flex' : 'none';

    // Avatar
    const avatarBox = document.getElementById('modalAvatarContainer');
    if (avatarUrl) {
        avatarBox.innerHTML = `<img src="${avatarUrl}" alt="${name}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0">`;
    } else {
        avatarBox.innerHTML = `<div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg, #0d9488, #0f766e);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem">${initials}</div>`;
    }

    document.getElementById('reviewModalBackdrop').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeReviewModal(e) {
    if (e && e.target !== e.currentTarget && !e.target.classList.contains('rev-modal-close')) {
        return;
    }
    document.getElementById('reviewModalBackdrop').classList.remove('active');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReviewModal();
    }
});
</script>
@endpush

@endsection
