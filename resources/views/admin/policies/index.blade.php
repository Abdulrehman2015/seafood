@extends('layouts.admin')

@section('title', 'Policies & Dynamic Pages')

@section('content')
<div style="max-width:1200px;margin:0 auto">
    <!-- Header -->
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px">
        <div>
            <h1 class="admin-page-title" style="margin:0 0 6px 0;font-size:1.45rem">📜 Policies & Dynamic Pages</h1>
            <p style="margin:0;font-size:0.85rem;color:#64748b">
                Manage your store policies, legal notices, and dynamic informational pages. Published pages automatically appear in the website footer under <strong>Quick Links</strong>.
            </p>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
            <a href="{{ route('admin.policies.create') }}" class="btn btn-primary" style="padding:9px 18px;font-weight:600">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Add New Page</span>
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:24px">
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
            <div style="width:44px;height:44px;border-radius:10px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:1.25rem">
                📄
            </div>
            <div>
                <div style="font-size:1.35rem;font-weight:800;color:#0f172a;line-height:1.2">{{ $stats['total'] }}</div>
                <div style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em">Total Pages</div>
            </div>
        </div>
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
            <div style="width:44px;height:44px;border-radius:10px;background:#ecfdf5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:1.25rem">
                🟢
            </div>
            <div>
                <div style="font-size:1.35rem;font-weight:800;color:#059669;line-height:1.2">{{ $stats['published'] }}</div>
                <div style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em">Published (Live in Footer)</div>
            </div>
        </div>
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
            <div style="width:44px;height:44px;border-radius:10px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:1.25rem">
                🟡
            </div>
            <div>
                <div style="font-size:1.35rem;font-weight:800;color:#d97706;line-height:1.2">{{ $stats['draft'] }}</div>
                <div style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em">Drafts (Hidden)</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;margin-bottom:20px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
        <form method="GET" action="{{ route('admin.policies.index') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
            <div class="search-input-wrap" style="flex:1;min-width:240px">
                <span class="search-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search pages by title or slug..." class="form-control">
            </div>

            <div style="display:flex;align-items:center;gap:8px">
                <select name="status" class="form-control" style="width:auto;padding-right:32px" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Only</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                </select>

                <button type="submit" class="btn btn-secondary">Filter</button>

                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.policies.index') }}" class="btn btn-secondary" style="color:#ef4444" title="Clear Filters">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Policies Table Card -->
    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.03)">
        <div class="table-wrapper">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:0.875rem">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#475569;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.04em">
                        <th style="padding:12px 18px;font-weight:700;width:60px">Order</th>
                        <th style="padding:12px 18px;font-weight:700">Page Title & Slug</th>
                        <th style="padding:12px 18px;font-weight:700">Languages</th>
                        <th style="padding:12px 18px;font-weight:700;text-align:center">Status</th>
                        <th style="padding:12px 18px;font-weight:700">Last Updated</th>
                        <th style="padding:12px 18px;font-weight:700;text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody style="divide-y:1px solid #f1f5f9">
                    @forelse($policies as $policy)
                        <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.12s ease" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding:14px 18px;font-weight:700;color:#64748b">
                                <span style="background:#f1f5f9;border-radius:6px;padding:4px 8px;font-size:0.75rem;border:1px solid #e2e8f0">#{{ $policy->sort_order }}</span>
                            </td>
                            <td style="padding:14px 18px">
                                <div style="font-weight:700;color:#0f172a;font-size:0.92rem;margin-bottom:2px">
                                    {{ $policy->title }}
                                </div>
                                <div style="font-family:monospace;font-size:0.78rem;color:#64748b;display:flex;align-items:center;gap:6px">
                                    <span>/{{ app()->getLocale() }}/policy/<strong>{{ $policy->slug }}</strong></span>
                                </div>
                                @if($policy->summary)
                                    <div style="font-size:0.78rem;color:#94a3b8;margin-top:4px;max-width:400px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                        {{ $policy->summary }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding:14px 18px">
                                <div style="display:flex;gap:4px">
                                    <span style="font-size:0.7rem;font-weight:700;background:#eff6ff;color:#2563eb;padding:2px 6px;border-radius:4px;border:1px solid #bfdbfe">EN</span>
                                    <span style="font-size:0.7rem;font-weight:700;background:{{ !empty($policy->content_zh) ? '#eff6ff;color:#2563eb;border:1px solid #bfdbfe' : '#f1f5f9;color:#94a3b8;border:1px solid #e2e8f0' }};padding:2px 6px;border-radius:4px">ZH</span>
                                    <span style="font-size:0.7rem;font-weight:700;background:{{ !empty($policy->content_bm) ? '#eff6ff;color:#2563eb;border:1px solid #bfdbfe' : '#f1f5f9;color:#94a3b8;border:1px solid #e2e8f0' }};padding:2px 6px;border-radius:4px">BM</span>
                                </div>
                            </td>
                            <td style="padding:14px 18px;text-align:center">
                                <form method="POST" action="{{ route('admin.policies.toggle-status', $policy) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" style="border:none;background:transparent;cursor:pointer;padding:0" title="Click to toggle status">
                                        @if($policy->status === 'published')
                                            <span style="display:inline-flex;align-items:center;gap:5px;background:#ecfdf5;color:#059669;font-weight:700;font-size:0.75rem;padding:4px 10px;border-radius:999px;border:1px solid #a7f3d0">
                                                <span style="width:6px;height:6px;border-radius:50%;background:#10b981"></span>
                                                Published
                                            </span>
                                        @else
                                            <span style="display:inline-flex;align-items:center;gap:5px;background:#fef3c7;color:#d97706;font-weight:700;font-size:0.75rem;padding:4px 10px;border-radius:999px;border:1px solid #fde68a">
                                                <span style="width:6px;height:6px;border-radius:50%;background:#f59e0b"></span>
                                                Draft
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td style="padding:14px 18px;color:#64748b;font-size:0.8rem">
                                {{ $policy->updated_at->format('M d, Y') }}
                                <div style="font-size:0.72rem;color:#94a3b8">{{ $policy->updated_at->format('H:i') }}</div>
                            </td>
                            <td style="padding:14px 18px;text-align:right">
                                <div style="display:inline-flex;align-items:center;gap:6px">
                                    <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => $policy->slug]) }}" target="_blank" class="btn btn-secondary" style="padding:5px 9px;font-size:0.78rem" title="View Page on Frontend">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.policies.edit', $policy) }}" class="btn btn-secondary" style="padding:5px 10px;font-size:0.78rem" title="Edit Page Content">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.policies.destroy', $policy) }}" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this page ({{ $policy->title }})? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding:5px 9px;font-size:0.78rem" title="Delete Page">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:48px 24px;text-align:center;color:#64748b">
                                <div style="font-size:2.5rem;margin-bottom:8px">📄</div>
                                <div style="font-weight:700;font-size:1.05rem;color:#0f172a;margin-bottom:4px">No policies or pages found</div>
                                <p style="font-size:0.85rem;color:#94a3b8;margin:0 0 16px 0">Create your first custom page to have it appear automatically in your store footer.</p>
                                <a href="{{ route('admin.policies.create') }}" class="btn btn-primary" style="padding:8px 16px">Create Page</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($policies->hasPages())
            <div style="padding:16px 20px;border-top:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center">
                {{ $policies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
