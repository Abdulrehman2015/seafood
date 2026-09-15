@extends('layouts.admin')
@section('title', 'Page SEO — Admin')

@section('content')

{{-- Top Header --}}
<div class="admin-topbar seo-topbar">
    <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
            <h1 class="admin-page-title" style="margin:0;font-size:clamp(1.35rem, 2.5vw, 1.75rem);font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px">
                <span>🔍</span> Page SEO
            </h1>
            <span class="seo-total-pill">{{ $stats['total'] ?? $pageSeos->count() }} total</span>
        </div>
        <p class="text-sm text-muted" style="margin:0;color:#64748b">
            Manage meta titles, descriptions, open graph social tags, and structured data for public-facing pages.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.page-seo.create') }}" class="btn btn-primary"
           style="background:#4f46e5;border-color:#4f46e5;font-weight:700;display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;font-size:0.9rem;box-shadow:0 2px 6px rgba(79,70,229,0.25)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Create Page SEO</span>
        </a>
    </div>
</div>

{{-- Quick Stat Metrics --}}
<div class="seo-metrics-grid">
    <a href="{{ route('admin.page-seo.index') }}" class="seo-metric-card {{ !request('type') ? 'active' : '' }}">
        <div>
            <div class="seo-metric-value">{{ $stats['total'] ?? $pageSeos->count() }}</div>
            <div class="seo-metric-title">Total Pages</div>
        </div>
        <div class="seo-metric-icon" style="background:#eff6ff;color:#2563eb">
            📄
        </div>
    </a>

    <a href="{{ route('admin.page-seo.index', array_merge(request()->query(), ['type' => 'system'])) }}" class="seo-metric-card {{ request('type') === 'system' ? 'active' : '' }}">
        <div>
            <div class="seo-metric-value" style="color:#0284c7">{{ $stats['system'] ?? 0 }}</div>
            <div class="seo-metric-title">System Pages</div>
        </div>
        <div class="seo-metric-icon" style="background:#e0f2fe;color:#0284c7">
            🔒
        </div>
    </a>

    <a href="{{ route('admin.page-seo.index', array_merge(request()->query(), ['type' => 'custom'])) }}" class="seo-metric-card {{ request('type') === 'custom' ? 'active' : '' }}">
        <div>
            <div class="seo-metric-value" style="color:#7c3aed">{{ $stats['custom'] ?? 0 }}</div>
            <div class="seo-metric-title">Custom Pages</div>
        </div>
        <div class="seo-metric-icon" style="background:#f5f3ff;color:#7c3aed">
            ✏️
        </div>
    </a>

    <div class="seo-metric-card">
        <div>
            <div class="seo-metric-value" style="color:#16a34a">{{ $stats['optimized'] ?? 0 }}</div>
            <div class="seo-metric-title">Optimized Meta</div>
        </div>
        <div class="seo-metric-icon" style="background:#f0fdf4;color:#16a34a">
            ✨
        </div>
    </div>
</div>

{{-- Search & Filtering Toolbar --}}
<div class="card seo-filter-card">
    <form method="GET" action="{{ route('admin.page-seo.index') }}" id="seoFilterForm" class="seo-filter-form">
        <div class="seo-search-wrap">
            <span class="seo-search-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control seo-search-input"
                   style="padding-left:46px !important"
                   placeholder="Search page name, slug, or meta keywords...">
        </div>

        <select name="type" class="form-control seo-filter-select" onchange="document.getElementById('seoFilterForm').submit()">
            <option value="">All Page Types</option>
            <option value="system" {{ request('type') === 'system' ? 'selected' : '' }}>🔒 System Pages Only</option>
            <option value="custom" {{ request('type') === 'custom' ? 'selected' : '' }}>✏️ Custom Pages Only</option>
        </select>

        <div style="display:flex;gap:8px;align-items:center">
            <button type="submit" class="btn btn-primary seo-action-btn">Search</button>
            @if(request('search') || request('type'))
                <a href="{{ route('admin.page-seo.index') }}" class="btn btn-secondary seo-action-btn">✕ Clear</a>
            @endif
        </div>
    </form>

    <div class="seo-count-badge">
        Showing <strong>{{ $pageSeos->count() }}</strong> page{{ $pageSeos->count() !== 1 ? 's' : '' }}
        @if(request('search')) matching "<em>{{ request('search') }}</em>" @endif
    </div>
</div>

{{-- Desktop Table View --}}
<div class="card seo-table-card seo-desktop-view">
    <div class="table-responsive" style="margin:0">
        <table class="table seo-table">
            <thead>
                <tr class="seo-thead-row">
                    <th class="seo-th" style="width:26%">Page</th>
                    <th class="seo-th" style="width:18%">Slug / URL</th>
                    <th class="seo-th" style="width:28%">Meta Title</th>
                    <th class="seo-th" style="width:18%">Description</th>
                    <th class="seo-th" style="width:10%;text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pageSeos as $item)
                <tr class="seo-tr">

                    {{-- Page Name --}}
                    <td class="seo-td">
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="seo-page-icon">
                                @if($item->is_system) 🔒 @else 📄 @endif
                            </div>
                            <div>
                                <a href="{{ route('admin.page-seo.edit', $item) }}" class="seo-page-name">
                                    {{ $item->page_name }}
                                </a>
                                @if($item->is_system)
                                    <span class="seo-system-badge">System</span>
                                @else
                                    <span class="seo-custom-badge">Custom</span>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Slug --}}
                    <td class="seo-td">
                        <code class="seo-slug">/{{ $item->page_slug }}</code>
                    </td>

                    {{-- Meta Title --}}
                    <td class="seo-td">
                        @if(!empty($item->meta_title))
                            <div class="seo-meta-title-wrap">
                                <span class="seo-meta-title-text">{{ Str::limit($item->meta_title, 48) }}</span>
                                @php $len = strlen($item->meta_title); @endphp
                                <span class="seo-char-badge {{ $len <= 60 ? 'seo-char-ok' : 'seo-char-warn' }}">
                                    {{ $len }}/60
                                </span>
                            </div>
                        @else
                            <span class="seo-not-set">— Not configured</span>
                        @endif
                    </td>

                    {{-- Meta Description --}}
                    <td class="seo-td">
                        @if(!empty($item->meta_description))
                            <span class="seo-meta-desc-text" title="{{ $item->meta_description }}">
                                {{ Str::limit($item->meta_description, 50) }}
                            </span>
                        @else
                            <span class="seo-not-set">—</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="seo-td" style="text-align:right;white-space:nowrap">
                        <div style="display:inline-flex;gap:6px;align-items:center">
                            <a href="{{ route('admin.page-seo.edit', $item) }}"
                               class="btn btn-secondary btn-sm seo-btn-edit">
                                ✏️ Edit
                            </a>
                            @if(!$item->is_system)
                            <form action="{{ route('admin.page-seo.destroy', $item) }}" method="POST"
                                  onsubmit="return confirm('Delete SEO settings for this page?')"
                                  style="display:inline;margin:0">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm seo-btn-delete" title="Delete">✕</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="seo-empty-state">
                        <div style="font-size:2.5rem;margin-bottom:10px">🔍</div>
                        <div style="font-weight:700;font-size:1.05rem;color:#334155;margin-bottom:4px">No Page SEO records found</div>
                        <div style="font-size:0.85rem;color:#94a3b8;margin-bottom:16px">
                            @if(request('search') || request('type'))
                                No pages match your active filters. Try clearing your search.
                            @else
                                Start by configuring SEO metadata for your website pages.
                            @endif
                        </div>
                        <a href="{{ route('admin.page-seo.create') }}" class="btn btn-primary btn-sm">+ Create Page SEO</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Cards List (Visible <= 768px) --}}
<div class="seo-mobile-list">
    @forelse($pageSeos as $item)
    <div class="card seo-mobile-card-item">
        <div class="seo-mobile-card-header">
            <div style="display:flex;align-items:center;gap:10px;min-width:0">
                <div class="seo-page-icon" style="width:36px;height:36px;font-size:1.1rem">
                    @if($item->is_system) 🔒 @else 📄 @endif
                </div>
                <div style="min-width:0">
                    <a href="{{ route('admin.page-seo.edit', $item) }}" class="seo-page-name" style="font-size:0.95rem">
                        {{ $item->page_name }}
                    </a>
                    <div style="display:flex;align-items:center;gap:6px;margin-top:2px">
                        @if($item->is_system)
                            <span class="seo-system-badge">System</span>
                        @else
                            <span class="seo-custom-badge">Custom</span>
                        @endif
                        <code class="seo-slug" style="font-size:0.75rem">/{{ $item->page_slug }}</code>
                    </div>
                </div>
            </div>
            <div style="display:flex;gap:6px;align-items:center;flex-shrink:0">
                <a href="{{ route('admin.page-seo.edit', $item) }}" class="btn btn-secondary btn-sm seo-btn-edit">
                    ✏️ Edit
                </a>
                @if(!$item->is_system)
                <form action="{{ route('admin.page-seo.destroy', $item) }}" method="POST"
                      onsubmit="return confirm('Delete SEO settings for this page?')"
                      style="display:inline;margin:0">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm seo-btn-delete" title="Delete">✕</button>
                </form>
                @endif
            </div>
        </div>

        @if(!empty($item->meta_title))
        <div class="seo-mobile-meta-section">
            <div style="font-size:0.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:3px;display:flex;justify-content:space-between;align-items:center">
                <span>Meta Title</span>
                @php $len = strlen($item->meta_title); @endphp
                <span class="seo-char-badge {{ $len <= 60 ? 'seo-char-ok' : 'seo-char-warn' }}">
                    {{ $len }}/60 chars
                </span>
            </div>
            <div class="seo-meta-title-text" style="font-size:0.85rem">{{ $item->meta_title }}</div>
        </div>
        @endif

        @if(!empty($item->meta_description))
        <div class="seo-mobile-meta-section">
            <div style="font-size:0.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:3px">
                Meta Description
            </div>
            <div class="seo-meta-desc-text" style="font-size:0.8rem;color:#64748b">
                {{ Str::limit($item->meta_description, 120) }}
            </div>
        </div>
        @endif
    </div>
    @empty
    <div class="card" style="padding:40px 20px;text-align:center;color:#94a3b8">
        <div style="font-size:2rem;margin-bottom:8px">🔍</div>
        <div style="font-weight:700;color:#334155;margin-bottom:4px">No records found</div>
        <p style="font-size:0.85rem;margin:0">Try adjusting your search criteria</p>
    </div>
    @endforelse
</div>

@endsection

@push('styles')
<style>
/* ===== PAGE SEO INDEX STYLES ===== */

.seo-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e2e8f0;
}

.seo-total-pill {
    font-size: 0.75rem;
    font-weight: 700;
    color: #4f46e5;
    background: #eef2ff;
    padding: 3px 10px;
    border-radius: 20px;
    border: 1px solid #c7d2fe;
}

/* Metric Cards */
.seo-metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 20px;
}
.seo-metric-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    transition: all 0.15s ease;
}
.seo-metric-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 3px 6px rgba(0,0,0,0.04);
}
.seo-metric-card.active {
    border-color: #4f46e5;
    background: #fbfbfe;
    box-shadow: 0 0 0 1px #4f46e5;
}
.seo-metric-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
}
.seo-metric-title {
    font-size: 0.78rem;
    color: #64748b;
    font-weight: 600;
    margin-top: 4px;
}
.seo-metric-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

/* Filter Toolbar */
.seo-filter-card {
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 18px;
    border: 1.5px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    background: #ffffff;
}
.seo-filter-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    flex-wrap: wrap;
}
.seo-search-wrap {
    position: relative;
    flex: 1;
    min-width: 260px;
}
.seo-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    display: flex;
    align-items: center;
    pointer-events: none;
    z-index: 2;
}
.seo-search-input {
    height: 42px !important;
    padding-left: 42px !important;
    padding-right: 12px !important;
    border-radius: 8px !important;
    width: 100% !important;
    border: 1px solid #cbd5e1 !important;
    font-size: 0.88rem !important;
    background: #ffffff !important;
}
.seo-search-input:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.12) !important;
}
.seo-filter-select {
    height: 42px !important;
    border-radius: 8px !important;
    padding: 0 12px !important;
    font-size: 0.85rem !important;
    min-width: 170px;
    border: 1px solid #cbd5e1 !important;
    background: #ffffff !important;
}
.seo-action-btn {
    height: 42px !important;
    padding: 0 16px !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    white-space: nowrap !important;
}
.seo-count-badge {
    font-size: 0.82rem;
    color: #64748b;
    white-space: nowrap;
}

/* Table Card */
.seo-table-card {
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    padding: 0 !important;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.seo-table {
    width: 100%;
    margin: 0;
    border-collapse: collapse;
}
.seo-thead-row {
    background: #f8fafc;
    border-bottom: 1.5px solid #e2e8f0;
}
.seo-th {
    padding: 13px 18px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #475569;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}
.seo-tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.12s;
}
.seo-tr:hover {
    background: #f8fafc;
}
.seo-td {
    padding: 14px 18px;
    vertical-align: middle;
}

/* Row Content */
.seo-page-icon {
    width: 32px;
    height: 32px;
    background: #f1f5f9;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
}
.seo-page-name {
    font-weight: 700;
    color: #0f172a;
    text-decoration: none;
    font-size: 0.88rem;
    display: inline-block;
}
.seo-page-name:hover {
    color: #4f46e5;
}
.seo-slug {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 0.78rem;
    color: #475569;
    background: #f1f5f9;
    padding: 2px 7px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}
.seo-system-badge {
    font-size: 0.65rem;
    font-weight: 700;
    background: #e0f2fe;
    color: #0369a1;
    border-radius: 20px;
    padding: 2px 7px;
    margin-left: 6px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    vertical-align: middle;
    border: 1px solid #bae6fd;
}
.seo-custom-badge {
    font-size: 0.65rem;
    font-weight: 700;
    background: #f5f3ff;
    color: #6d28d9;
    border-radius: 20px;
    padding: 2px 7px;
    margin-left: 6px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    vertical-align: middle;
    border: 1px solid #ddd6fe;
}
.seo-meta-title-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.seo-meta-title-text {
    font-size: 0.85rem;
    color: #1e293b;
    font-weight: 500;
}
.seo-meta-desc-text {
    font-size: 0.8rem;
    color: #64748b;
    line-height: 1.4;
}
.seo-not-set {
    font-size: 0.8rem;
    color: #94a3b8;
    font-style: italic;
}
.seo-char-badge {
    display: inline-block;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 20px;
    vertical-align: middle;
}
.seo-char-ok {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}
.seo-char-warn {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}
.seo-btn-edit {
    font-size: 0.8rem !important;
    padding: 6px 12px !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
}
.seo-btn-delete {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fca5a5;
    padding: 6px 10px;
    font-size: 0.78rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.15s;
}
.seo-btn-delete:hover {
    background: #fca5a5;
}
.seo-empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}

/* Mobile View Elements */
.seo-mobile-list {
    display: none;
    flex-direction: column;
    gap: 12px;
}
.seo-mobile-card-item {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.seo-mobile-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}
.seo-mobile-meta-section {
    margin-top: 10px;
    padding-top: 8px;
}

/* Responsive Media Queries */
@media (max-width: 1024px) {
    .seo-metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .seo-desktop-view {
        display: none !important;
    }
    .seo-mobile-list {
        display: flex !important;
    }
    .seo-filter-card {
        flex-direction: column;
        align-items: stretch;
    }
    .seo-filter-form {
        flex-direction: column;
        align-items: stretch;
    }
    .seo-search-wrap,
    .seo-filter-select {
        width: 100% !important;
        min-width: 0 !important;
    }
    .seo-count-badge {
        text-align: center;
    }
}

@media (max-width: 480px) {
    .seo-metrics-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    .seo-metric-card {
        padding: 12px 14px;
    }
    .seo-metric-value {
        font-size: 1.25rem;
    }
    .seo-metric-title {
        font-size: 0.72rem;
    }
    .seo-metric-icon {
        width: 32px;
        height: 32px;
        font-size: 1rem;
    }
}
</style>
@endpush
