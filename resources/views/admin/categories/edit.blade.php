@extends('layouts.admin')
@section('title', 'Edit Category: ' . $category->name . ' — Admin')

@section('content')
<!-- Topbar Navigation -->
<div class="admin-topbar" style="margin-bottom:24px">
    <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
            <h1 class="admin-page-title" style="font-size:1.5rem;font-weight:700;color:#0f172a;margin:0">Edit Category: {{ $category->name }}</h1>
            @if($category->is_active)
                <span class="badge" style="background:#dcfce7;color:#15803d;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;border:1px solid #bbf7d0">Active</span>
            @else
                <span class="badge" style="background:#f1f5f9;color:#64748b;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;border:1px solid #e2e8f0">Inactive</span>
            @endif
        </div>
        <p class="text-sm text-muted" style="margin:0;color:#64748b">Update category settings, hierarchy, custom storefront URL, and banner image</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        <a href="{{ $category->url }}" target="_blank" class="btn btn-secondary btn-sm" style="font-weight:600">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            View in Storefront
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
            ← Back to Categories
        </a>
    </div>
</div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" id="categoryEditForm">
    @csrf
    @method('PUT')

    <div class="admin-form-layout">

        <!-- Left Column: Primary Details & Custom URL -->
        <div style="display:flex;flex-direction:column;gap:24px">

            <!-- Card 1: Category Information -->
            <div class="card" style="padding:24px;border-radius:12px">
                <div style="font-weight:700;font-size:1.05rem;color:#0f172a;margin-bottom:18px;display:flex;align-items:center;gap:8px">
                    <span>📝</span> Category Information
                </div>

                <div class="form-group" style="margin-bottom:18px">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block">
                        Category Name <span style="color:#ef4444">*</span>
                    </label>
                    <input type="text" name="name" id="categoryNameInput" class="form-control" 
                           value="{{ old('name', $category->name) }}" required 
                           style="width:100%;height:42px;border-radius:8px;font-size:0.95rem"
                           placeholder="e.g. Shellfish, Whole Fish, Prawns">
                    @error('name')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:18px">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block">
                        Parent Category <span class="text-muted" style="font-weight:400">(Optional for subcategories)</span>
                    </label>
                    <select name="parent_id" class="form-control" style="width:100%;height:42px;border-radius:8px;font-size:0.9rem">
                        <option value="">None (Top-Level Category)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                📁 {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block">
                        Description
                    </label>
                    <textarea name="description" class="form-control" rows="4" 
                              style="width:100%;border-radius:8px;padding:10px 12px;font-size:0.9rem"
                              placeholder="Describe this seafood category for customers and SEO...">{{ old('description', $category->description) }}</textarea>
                    @error('description')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Card 2: Custom URL & Storefront Routing -->
            <div class="card" style="padding:24px;border-radius:12px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
                    <div style="font-weight:700;font-size:1.05rem;color:#0f172a;display:flex;align-items:center;gap:8px">
                        <span>🔗</span> Custom URL & Navigation Settings
                    </div>
                    <span class="badge" style="background:#e0e7ff;color:#4338ca;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600">SEO & Routing</span>
                </div>
                <p style="font-size:0.85rem;color:#64748b;margin:0 0 18px;line-height:1.5">
                    Customize the storefront URL slug for this category or define an optional custom redirect landing page.
                </p>

                <!-- URL Slug Input -->
                <div class="form-group" style="margin-bottom:18px">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block">
                        Category URL Slug
                    </label>
                    <div style="display:flex;align-items:center;border:1px solid #cbd5e1;border-radius:8px;overflow:hidden;background:#f8fafc">
                        <span style="padding:0 12px;font-size:0.85rem;color:#64748b;background:#f1f5f9;border-right:1px solid #cbd5e1;height:42px;display:flex;align-items:center;white-space:nowrap">
                            /shop?category=
                        </span>
                        <input type="text" name="slug" id="categorySlugInput" class="form-control" 
                               value="{{ old('slug', $category->slug) }}" 
                               style="border:none !important;height:42px;font-weight:600;color:#0f172a;font-size:0.95rem;background:white;padding:0 12px;flex:1"
                               placeholder="custom-category-slug">
                    </div>
                    @error('slug')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                </div>

                <!-- Live URL Preview Bar -->
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:12px 14px;display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:18px;flex-wrap:wrap">
                    <div style="font-size:0.82rem;color:#1d4ed8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:100%">
                        <strong style="color:#1e40af">Live URL:</strong> 
                        <span id="fullUrlPreview">{{ url('/shop?category=' . $category->slug) }}</span>
                    </div>
                    <div style="display:flex;gap:6px">
                        <button type="button" onclick="copyCategoryUrl()" id="btnCopyUrl" class="btn btn-secondary btn-sm" style="font-size:0.75rem;padding:4px 10px;background:white">
                            📋 Copy URL
                        </button>
                        <a href="{{ url('/shop?category=' . $category->slug) }}" target="_blank" id="btnTestUrl" class="btn btn-secondary btn-sm" style="font-size:0.75rem;padding:4px 10px;background:white">
                            ↗ Visit
                        </a>
                    </div>
                </div>

                <!-- Direct Custom URL / Redirect (Optional) -->
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:flex;align-items:center;justify-content:space-between">
                        <span>Direct Custom Redirect URL <span class="text-muted" style="font-weight:400">(Optional)</span></span>
                        <span class="text-xs text-muted">Overrides default shop filter</span>
                    </label>
                    <input type="text" name="custom_url" class="form-control" 
                           value="{{ old('custom_url', $category->custom_url) }}" 
                           style="width:100%;height:42px;border-radius:8px;font-size:0.9rem"
                           placeholder="e.g. /promotions/wild-seafood or https://partner.mst.my">
                    <span style="font-size:0.75rem;color:#94a3b8;margin-top:4px;display:block">
                        Leave blank to use the standard category catalogue page. If set, category links will navigate directly to this custom path.
                    </span>
                    @error('custom_url')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                </div>
            </div>

        </div>

        <!-- Right Column: Publishing, Media & Danger Zone -->
        <div style="display:flex;flex-direction:column;gap:24px">

            <!-- Card 3: Publishing & Visibility -->
            <div class="card" style="padding:22px;border-radius:12px">
                <div style="font-weight:700;font-size:1rem;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:8px">
                    <span>⚙️</span> Publishing Settings
                </div>

                <!-- Active Toggle -->
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px 14px;margin-bottom:12px">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin:0">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:#1d4ed8">
                        <div>
                            <div style="font-weight:700;color:#0f172a;font-size:0.88rem">Active in Storefront</div>
                            <div style="font-size:0.75rem;color:#64748b">Show in navigation menus and catalogue filters</div>
                        </div>
                    </label>
                </div>

                <!-- Featured Toggle -->
                <div style="background:#fffbeb;border:1px solid #fef3c7;border-radius:10px;padding:12px 14px;margin-bottom:16px">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin:0">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:#d97706">
                        <div>
                            <div style="font-weight:700;color:#92400e;font-size:0.88rem;display:flex;align-items:center;gap:6px">
                                <span>Featured Category</span>
                                <span style="font-size:0.65rem;background:#fde68a;color:#78350f;padding:1px 6px;border-radius:4px;font-weight:700">HOMEPAGE</span>
                            </div>
                            <div style="font-size:0.75rem;color:#b45309">Display in the featured categories section on the homepage</div>
                        </div>
                    </label>
                </div>

                <!-- Sort Order -->
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block">
                        Display Sort Order
                    </label>
                    <input type="number" name="sort_order" class="form-control" 
                           value="{{ old('sort_order', $category->sort_order) }}" min="0"
                           style="width:100%;height:40px;border-radius:8px">
                    <span style="font-size:0.72rem;color:#94a3b8;margin-top:2px;display:block">Lower numbers appear first (0 = top priority)</span>
                </div>

                <!-- Products Metric -->
                <div style="padding:12px 14px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:space-between">
                    <span style="font-size:0.82rem;color:#475569;font-weight:600">Assigned Products</span>
                    <span class="badge" style="background:#0f172a;color:white;font-weight:700;padding:3px 8px;border-radius:12px;font-size:0.75rem">
                        {{ $category->products()->count() }} items
                    </span>
                </div>
            </div>

            <!-- Card 4: Category Banner / Image -->
            <div class="card" style="padding:22px;border-radius:12px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
                    <div style="font-weight:700;font-size:1rem;color:#0f172a;display:flex;align-items:center;gap:8px">
                        <span>🖼️</span> Category Image
                    </div>
                </div>

                <div id="catImgPreviewBox" style="width:100%;aspect-ratio:16/9;background:#f8fafc;border-radius:10px;border:1px dashed #cbd5e1;display:flex;align-items:center;justify-content:center;margin-bottom:12px;overflow:hidden;position:relative">
                    @if($category->image)
                        <img id="catPreviewImg" src="{{ asset('storage/'.$category->image) }}" style="width:100%;height:100%;object-fit:cover">
                        <div id="catPlaceholder" style="display:none;font-size:2.5rem;color:#94a3b8">📁</div>
                        <button type="button" id="catClearBtn" onclick="clearCatImgSelection()" style="position:absolute;top:8px;right:8px;background:rgba(15,23,42,0.8);color:white;border:none;border-radius:50%;width:26px;height:26px;cursor:pointer;font-size:0.8rem;display:flex;align-items:center;justify-content:center">✕</button>
                    @else
                        <div id="catPlaceholder" style="font-size:2.5rem;color:#94a3b8">📁</div>
                        <img id="catPreviewImg" src="" style="display:none;width:100%;height:100%;object-fit:cover">
                        <button type="button" id="catClearBtn" onclick="clearCatImgSelection()" style="display:none;position:absolute;top:8px;right:8px;background:rgba(15,23,42,0.8);color:white;border:none;border-radius:50%;width:26px;height:26px;cursor:pointer;font-size:0.8rem;display:flex;align-items:center;justify-content:center">✕</button>
                    @endif
                </div>

                <input type="hidden" name="gallery_image" id="galleryImageInput" value="{{ $category->image }}">

                <div style="display:flex;flex-direction:column;gap:8px">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="pickCategoryImage()" style="width:100%;justify-content:center;font-weight:600">
                        🖼️ Choose from Media Gallery
                    </button>
                    <label class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;margin:0;cursor:pointer">
                        <span>📁 Upload From Computer</span>
                        <input type="file" name="image" accept="image/*" onchange="previewUploadedImage(this)" style="display:none">
                    </label>
                </div>
            </div>

            <!-- Card 5: Danger Zone (Delete) -->
            <div class="card" style="padding:22px;border-radius:12px;border:1px solid #fecaca !important;background:#fff5f5 !important">
                <div style="font-weight:700;font-size:0.95rem;color:#991b1b;margin-bottom:6px;display:flex;align-items:center;gap:6px">
                    <span>⚠️</span> Danger Zone
                </div>
                <p style="font-size:0.8rem;color:#b91c1c;margin:0 0 14px;line-height:1.4">
                    Deleting this category will safely reassign its products to root level.
                </p>
                <button type="button" class="btn btn-danger btn-sm" onclick="openDeleteCategoryModal()" style="width:100%;justify-content:center;font-weight:700">
                    🗑️ Delete Category
                </button>
            </div>

            <!-- Main Submit Bar -->
            <div style="display:flex;flex-direction:column;gap:10px">
                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;font-weight:700;height:46px;box-shadow:0 4px 14px rgba(15,23,42,0.15)">
                    💾 Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" style="width:100%;justify-content:center">
                    Cancel
                </a>
            </div>

        </div>

    </div>
</form>

<!-- Modal: Category Delete Confirmation -->
<div id="deleteCategoryModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.6);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(3px)">
    <div style="background:white;border-radius:14px;width:100%;max-width:440px;padding:24px;box-shadow:0 20px 25px -5px rgba(0,0,0,0.1),0 10px 10px -5px rgba(0,0,0,0.04);margin:16px">
        <div style="width:48px;height:48px;border-radius:50%;background:#fee2e2;color:#ef4444;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:14px">
            🗑️
        </div>
        <h3 style="font-size:1.15rem;font-weight:700;color:#0f172a;margin:0 0 6px">Delete Category: {{ $category->name }}</h3>
        <p style="font-size:0.875rem;color:#64748b;line-height:1.5;margin:0 0 16px">
            Are you sure you want to permanently delete this category? Any associated products and subcategories will be preserved and moved to root level.
        </p>
        <div style="display:flex;justify-content:flex-end;gap:10px">
            <button type="button" onclick="closeDeleteCategoryModal()" class="btn btn-secondary">
                Cancel
            </button>
            <form id="deleteCategoryForm" action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="margin:0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="font-weight:700">
                    Yes, Delete Category
                </button>
            </form>
        </div>
    </div>
</div>

@include('components.gallery-picker-modal')
@endsection

@push('scripts')
<script>
// Live Custom URL Slug Sync & Preview
const slugInput = document.getElementById('categorySlugInput');
const fullUrlPreview = document.getElementById('fullUrlPreview');
const testUrlBtn = document.getElementById('btnTestUrl');
const baseUrl = "{{ url('/shop?category=') }}/";

if (slugInput) {
    slugInput.addEventListener('input', function() {
        // Replace spaces with hyphens for clean slug
        let val = this.value.toLowerCase().replace(/[^a-z0-9\-]/g, '-').replace(/\-+/g, '-');
        const finalUrl = baseUrl + val;
        if (fullUrlPreview) fullUrlPreview.textContent = finalUrl;
        if (testUrlBtn) testUrlBtn.href = finalUrl;
    });
}

function copyCategoryUrl() {
    const url = fullUrlPreview.textContent;
    navigator.clipboard.writeText(url).then(() => {
        const btn = document.getElementById('btnCopyUrl');
        if (btn) {
            btn.textContent = '✓ Copied!';
            setTimeout(() => { btn.textContent = '📋 Copy URL'; }, 2000);
        }
    });
}

// Media Gallery Picker
function pickCategoryImage() {
    openGalleryPicker(function(path, url, name) {
        document.getElementById('galleryImageInput').value = path;
        const img = document.getElementById('catPreviewImg');
        img.src = url;
        img.style.display = 'block';
        document.getElementById('catPlaceholder').style.display = 'none';
        document.getElementById('catClearBtn').style.display = 'flex';
    }, false);
}

function previewUploadedImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('catPreviewImg');
            img.src = e.target.result;
            img.style.display = 'block';
            document.getElementById('catPlaceholder').style.display = 'none';
            document.getElementById('catClearBtn').style.display = 'flex';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearCatImgSelection() {
    document.getElementById('galleryImageInput').value = '';
    const img = document.getElementById('catPreviewImg');
    img.src = '';
    img.style.display = 'none';
    document.getElementById('catPlaceholder').style.display = 'block';
    document.getElementById('catClearBtn').style.display = 'none';
}

// Modal Delete Confirmation
function openDeleteCategoryModal() {
    const modal = document.getElementById('deleteCategoryModal');
    if (modal) modal.style.display = 'flex';
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
