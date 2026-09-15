@extends('layouts.admin')
@section('title', 'Categories — Admin')

@section('content')
<!-- Page Header -->
<div class="admin-topbar" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #e2e8f0;">
    <div>
        <h1 class="admin-page-title" style="font-size:clamp(1.35rem, 2.5vw, 1.75rem);font-weight:700;color:#0f172a;margin:0 0 4px;display:flex;align-items:center;gap:8px;">
            <span>📁</span> Product Categories
        </h1>
        <p class="text-sm text-muted" style="margin:0;color:#64748b;">
            Manage seafood catalogue groups, hierarchical navigation, custom storefront URLs, and homepage highlights
        </p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="font-weight:700;display:inline-flex;align-items:center;gap:6px;padding:10px 18px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Add New Category
        </a>
    </div>
</div>

<!-- Category Stat Metric Cards -->
<div class="cat-metrics-grid">
    <a href="{{ route('admin.categories.index') }}" class="category-metric-card {{ !request('status') && !request('featured') && !request('level') ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value">{{ $stats['total'] ?? $categories->total() }}</div>
            <div class="cat-metric-title">Total Categories</div>
        </div>
        <div class="cat-metric-icon" style="background:#eff6ff;color:#2563eb;">
            📁
        </div>
    </a>

    <a href="{{ route('admin.categories.index', array_merge(request()->query(), ['status' => 'active'])) }}" class="category-metric-card {{ request('status') === 'active' ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value" style="color:#15803d;">{{ $stats['active'] ?? 0 }}</div>
            <div class="cat-metric-title">Active in Store</div>
        </div>
        <div class="cat-metric-icon" style="background:#f0fdf4;color:#16a34a;">
            🟢
        </div>
    </a>

    <a href="{{ route('admin.categories.index', array_merge(request()->query(), ['featured' => 'yes'])) }}" class="category-metric-card {{ request('featured') === 'yes' ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value" style="color:#b45309;">{{ $stats['featured'] ?? 0 }}</div>
            <div class="cat-metric-title">Homepage Featured</div>
        </div>
        <div class="cat-metric-icon" style="background:#fffbeb;color:#d97706;">
            ⭐
        </div>
    </a>

    <a href="{{ route('admin.categories.index', array_merge(request()->query(), ['level' => 'root'])) }}" class="category-metric-card {{ request('level') === 'root' ? 'active' : '' }}">
        <div>
            <div class="cat-metric-value" style="color:#4f46e5;">{{ $stats['root'] ?? 0 }}</div>
            <div class="cat-metric-title">Root Categories</div>
        </div>
        <div class="cat-metric-icon" style="background:#eef2ff;color:#4f46e5;">
            🌳
        </div>
    </a>
</div>

<!-- Search & Filtering Toolbar -->
<div class="cat-filter-card">
    <form method="GET" action="{{ route('admin.categories.index') }}" id="catFilterForm">
        <div class="cat-filter-row">
            <div class="cat-search-group">
                <span class="cat-search-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control cat-search-input" placeholder="Search categories by name, slug, or redirect...">
            </div>

            <select name="status" class="cat-filter-select" onchange="document.getElementById('catFilterForm').submit()">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
            </select>

            <select name="featured" class="cat-filter-select" onchange="document.getElementById('catFilterForm').submit()">
                <option value="">All Homepage Tiers</option>
                <option value="yes" {{ request('featured') === 'yes' ? 'selected' : '' }}>⭐ Featured on Home</option>
                <option value="no" {{ request('featured') === 'no' ? 'selected' : '' }}>Standard Only</option>
            </select>

            <select name="level" class="cat-filter-select" onchange="document.getElementById('catFilterForm').submit()">
                <option value="">All Levels</option>
                <option value="root" {{ request('level') === 'root' ? 'selected' : '' }}>Root Level Only</option>
                <option value="sub" {{ request('level') === 'sub' ? 'selected' : '' }}>Subcategories Only</option>
            </select>

            <select name="sort" class="cat-filter-select" onchange="document.getElementById('catFilterForm').submit()">
                <option value="sort_order" {{ request('sort', 'sort_order') === 'sort_order' ? 'selected' : '' }}>Sort by: Display Order</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Sort by: Name (A-Z)</option>
                <option value="products_count" {{ request('sort') === 'products_count' ? 'selected' : '' }}>Sort by: Products Count</option>
            </select>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary btn-sm" style="height:40px;padding:0 16px;font-weight:600;">
                    Filter
                </button>
                @if(request()->hasAny(['q', 'status', 'featured', 'level', 'sort']))
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm" style="height:40px;padding:0 14px;display:inline-flex;align-items:center;" title="Reset all filters">
                        ✕ Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Categories Listing Container -->
@if($categories->count() > 0)
    <!-- ─── Desktop Table View (>= 992px) ─────────────────────────────────── -->
    <div class="card categories-table-container" style="border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);padding:0;">
        <div class="table-wrapper">
            <table class="table" style="margin:0;width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                        <th style="width:72px;padding:12px 16px;">Photo</th>
                        <th style="padding:12px 16px;">Category &amp; Routing</th>
                        <th style="padding:12px 16px;">Hierarchy</th>
                        <th style="padding:12px 16px;">Products</th>
                        <th style="padding:12px 16px;text-align:center;width:80px;">Order</th>
                        <th style="padding:12px 16px;text-align:center;width:95px;">Status</th>
                        <th style="padding:12px 16px;text-align:center;width:125px;">Homepage</th>
                        <th style="padding:12px 16px;text-align:right;width:180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s ease;">
                        <td style="padding:12px 16px;">
                            @if($cat->image)
                                <img src="{{ str_starts_with($cat->image, 'http') ? $cat->image : (str_starts_with($cat->image, 'images/') ? asset($cat->image) : asset('storage/'.$cat->image)) }}" 
                                     alt="{{ $cat->name }}" 
                                     style="width:48px;height:48px;object-fit:cover;border-radius:10px;border:1px solid #e2e8f0;background:#f8fafc;display:block;">
                            @else
                                <div style="width:48px;height:48px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;border-radius:10px;font-size:1.5rem;border:1px solid #e2e8f0;">
                                    📁
                                </div>
                            @endif
                        </td>
                        <td style="padding:12px 16px;">
                            <div style="font-weight:700;color:#0f172a;font-size:0.95rem;margin-bottom:3px;display:flex;align-items:center;gap:6px;">
                                <a href="{{ route('admin.categories.edit', $cat) }}" style="color:inherit;text-decoration:none;">
                                    {{ $cat->name }}
                                </a>
                                @if($cat->children->count() > 0)
                                    <span style="font-size:0.7rem;background:#e0f2fe;color:#0369a1;padding:1px 6px;border-radius:999px;font-weight:700;">
                                        {{ $cat->children->count() }} subs
                                    </span>
                                @endif
                            </div>
                            <div style="display:flex;align-items:center;gap:6px;font-size:0.78rem;color:#64748b;flex-wrap:wrap;">
                                <span style="font-family:monospace;background:#f8fafc;padding:2px 6px;border-radius:4px;border:1px solid #e2e8f0;">
                                    /shop?category={{ $cat->slug }}
                                </span>
                                @if($cat->custom_url)
                                    <span class="badge" style="background:#f3e8ff;color:#6b21a8;padding:2px 7px;border-radius:4px;font-size:0.7rem;font-weight:700;border:1px solid #e9d5ff;" title="Custom Redirect: {{ $cat->custom_url }}">
                                        Redirect: {{ Str::limit($cat->custom_url, 28) }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td style="padding:12px 16px;">
                            @if($cat->parent)
                                <span class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:4px 9px;border-radius:6px;font-size:0.78rem;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                                    <span>📁</span> {{ $cat->parent->name }}
                                </span>
                            @else
                                <span style="font-size:0.78rem;font-weight:600;color:#059669;background:#ecfdf5;border:1px solid #a7f3d0;padding:4px 9px;border-radius:6px;display:inline-flex;align-items:center;gap:4px;">
                                    <span>🌳</span> Root Category
                                </span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;">
                            <a href="{{ route('admin.products.index', ['category_id' => $cat->id]) }}" 
                               style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:20px;transition:all 0.15s ease;"
                               title="View items assigned to this category">
                                <span style="font-weight:800;color:#0f172a;font-size:0.88rem;">{{ $cat->products_count ?? $cat->products()->count() }}</span>
                                <span style="font-size:0.75rem;color:#64748b;">products</span>
                            </a>
                        </td>
                        <td style="padding:12px 16px;text-align:center;">
                            <span style="font-weight:700;font-size:0.85rem;color:#475569;background:#f1f5f9;padding:3px 8px;border-radius:6px;">
                                {{ $cat->sort_order }}
                            </span>
                        </td>
                        <td style="padding:12px 16px;text-align:center;">
                            @if($cat->is_active)
                                <span class="badge" style="background:#dcfce7;color:#15803d;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;border:1px solid #bbf7d0;">
                                    Active
                                </span>
                            @else
                                <span class="badge" style="background:#f1f5f9;color:#64748b;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;border:1px solid #e2e8f0;">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;text-align:center;">
                            <form method="POST" action="{{ route('admin.categories.toggle-featured', $cat) }}" style="display:inline;margin:0;">
                                @csrf
                                <button type="submit" class="btn btn-sm" 
                                        style="padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;display:inline-flex;align-items:center;gap:4px;cursor:pointer;transition:all 0.15s ease;{{ $cat->is_featured ? 'background:#fef3c7;color:#92400e;border:1px solid #fde68a;' : 'background:#f8fafc;color:#64748b;border:1px solid #cbd5e1;' }}" 
                                        title="{{ $cat->is_featured ? 'Featured on Homepage — click to remove' : 'Click to feature on Homepage' }}">
                                    <span>{{ $cat->is_featured ? '⭐ Featured' : '☆ Standard' }}</span>
                                </button>
                            </form>
                        </td>
                        <td style="padding:12px 16px;text-align:right;">
                            <div style="display:inline-flex;gap:6px;align-items:center;">
                                <a href="{{ $cat->url }}" target="_blank" class="btn btn-secondary btn-sm" style="padding:5px 9px;font-size:0.78rem;" title="View in storefront">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                </a>
                                <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-secondary btn-sm" style="padding:5px 11px;font-size:0.78rem;font-weight:600;">
                                    Edit
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" style="padding:5px 9px;font-size:0.78rem;"
                                        onclick="openDeleteCategoryModal('{{ route('admin.categories.destroy', $cat) }}', '{{ addslashes($cat->name) }}')"
                                        title="Delete Category">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ─── Mobile & Tablet Card View (< 992px) ──────────────────────────── -->
    <div class="categories-cards-container">
        @foreach($categories as $cat)
        <div class="category-item-card">
            <div class="cat-card-header">
                @if($cat->image)
                    <img src="{{ str_starts_with($cat->image, 'http') ? $cat->image : (str_starts_with($cat->image, 'images/') ? asset($cat->image) : asset('storage/'.$cat->image)) }}" 
                         alt="{{ $cat->name }}" 
                         class="cat-card-img">
                @else
                    <div class="cat-card-img">📁</div>
                @endif

                <div class="cat-card-info">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:4px;">
                        <h3 class="cat-card-title">
                            <a href="{{ route('admin.categories.edit', $cat) }}" style="color:inherit;text-decoration:none;">
                                {{ $cat->name }}
                            </a>
                        </h3>
                        @if($cat->is_active)
                            <span class="badge" style="background:#dcfce7;color:#15803d;padding:2px 8px;border-radius:20px;font-size:0.7rem;font-weight:700;border:1px solid #bbf7d0;flex-shrink:0;">
                                Active
                            </span>
                        @else
                            <span class="badge" style="background:#f1f5f9;color:#64748b;padding:2px 8px;border-radius:20px;font-size:0.7rem;font-weight:600;border:1px solid #e2e8f0;flex-shrink:0;">
                                Inactive
                            </span>
                        @endif
                    </div>

                    <div class="cat-card-badges">
                        @if($cat->parent)
                            <span class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:2px 6px;border-radius:4px;font-size:0.72rem;font-weight:600;">
                                📁 {{ $cat->parent->name }}
                            </span>
                        @else
                            <span style="font-size:0.72rem;font-weight:600;color:#059669;background:#ecfdf5;border:1px solid #a7f3d0;padding:2px 6px;border-radius:4px;">
                                🌳 Root
                            </span>
                        @endif

                        @if($cat->children->count() > 0)
                            <span style="font-size:0.7rem;background:#e0f2fe;color:#0369a1;padding:2px 6px;border-radius:4px;font-weight:600;">
                                {{ $cat->children->count() }} subcategories
                            </span>
                        @endif
                    </div>

                    <div style="font-family:monospace;font-size:0.75rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        /shop?category={{ $cat->slug }}
                    </div>
                </div>
            </div>

            <div class="cat-card-meta">
                <div style="display:flex;align-items:center;gap:12px;">
                    <a href="{{ route('admin.products.index', ['category_id' => $cat->id]) }}" style="text-decoration:none;color:inherit;font-weight:700;font-size:0.82rem;">
                        📦 {{ $cat->products_count ?? $cat->products()->count() }} Products
                    </a>
                    <span style="color:#cbd5e1;">|</span>
                    <span style="color:#64748b;font-size:0.8rem;">
                        Order: <strong>{{ $cat->sort_order }}</strong>
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.categories.toggle-featured', $cat) }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-sm" 
                            style="padding:3px 8px;border-radius:20px;font-size:0.72rem;font-weight:700;display:inline-flex;align-items:center;gap:4px;{{ $cat->is_featured ? 'background:#fef3c7;color:#92400e;border:1px solid #fde68a;' : 'background:#ffffff;color:#64748b;border:1px solid #cbd5e1;' }}">
                        {{ $cat->is_featured ? '⭐ Featured' : '☆ Standard' }}
                    </button>
                </form>
            </div>

            <div class="cat-card-actions">
                <a href="{{ $cat->url }}" target="_blank" class="btn btn-secondary btn-sm" title="View Storefront">
                    View
                </a>
                <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-secondary btn-sm" style="font-weight:700;">
                    Edit
                </a>
                <button type="button" class="btn btn-danger btn-sm" 
                        onclick="openDeleteCategoryModal('{{ route('admin.categories.destroy', $cat) }}', '{{ addslashes($cat->name) }}')">
                    Delete
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination & Results Counter -->
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-top:20px;padding:16px 20px;background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;">
        <div style="font-size:0.85rem;color:#64748b;">
            Showing <strong>{{ $categories->firstItem() ?? 0 }}</strong> to <strong>{{ $categories->lastItem() ?? 0 }}</strong> of <strong>{{ $categories->total() }}</strong> categories
        </div>
        <div>
            {{ $categories->links() }}
        </div>
    </div>

@else
    <!-- Empty State -->
    <div class="card" style="padding:60px 20px;text-align:center;border-radius:12px;border:1px solid #e2e8f0;">
        <div style="font-size:3.5rem;margin-bottom:12px;">📁</div>
        <h3 style="font-size:1.25rem;font-weight:700;color:#0f172a;margin:0 0 6px;">No Categories Found</h3>
        <p class="text-sm text-muted" style="margin:0 auto 20px;max-width:440px;color:#64748b;">
            @if(request()->hasAny(['q', 'status', 'featured', 'level']))
                No categories match your current search and filter criteria. Try adjusting or clearing your filters.
            @else
                Get started by creating your first product category to organize your seafood items.
            @endif
        </p>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            @if(request()->hasAny(['q', 'status', 'featured', 'level']))
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    ✕ Clear All Filters
                </a>
            @endif
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="font-weight:700;">
                + Create New Category
            </a>
        </div>
    </div>
@endif

<!-- Modal: Category Delete Confirmation -->
<div id="deleteCategoryModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.6);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(3px);padding:16px;">
    <div style="background:white;border-radius:14px;width:100%;max-width:440px;padding:24px;box-shadow:0 20px 25px -5px rgba(0,0,0,0.1),0 10px 10px -5px rgba(0,0,0,0.04);">
        <div style="width:48px;height:48px;border-radius:50%;background:#fee2e2;color:#ef4444;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:14px;">
            🗑️
        </div>
        <h3 style="font-size:1.15rem;font-weight:700;color:#0f172a;margin:0 0 6px;">Delete Category</h3>
        <p style="font-size:0.875rem;color:#64748b;line-height:1.5;margin:0 0 20px;">
            Are you sure you want to delete <strong id="deleteCategoryName" style="color:#0f172a;"></strong>? Any child categories and products will be safely moved to root level without being deleted.
        </p>
        <div style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
            <button type="button" onclick="closeDeleteCategoryModal()" class="btn btn-secondary" style="flex:1;min-width:100px;">
                Cancel
            </button>
            <form id="modalDeleteCategoryForm" action="" method="POST" style="margin:0;flex:1;min-width:140px;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="width:100%;font-weight:700;">
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openDeleteCategoryModal(actionUrl, categoryName) {
    const modal = document.getElementById('deleteCategoryModal');
    const form = document.getElementById('modalDeleteCategoryForm');
    const nameEl = document.getElementById('deleteCategoryName');
    
    if (modal && form && nameEl) {
        form.action = actionUrl;
        nameEl.textContent = categoryName;
        modal.style.display = 'flex';
    }
}

function closeDeleteCategoryModal() {
    const modal = document.getElementById('deleteCategoryModal');
    if (modal) modal.style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteCategoryModal();
});
</script>
@endpush
