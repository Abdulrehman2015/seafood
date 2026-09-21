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
                        Category Name (English - Default) <span style="color:#ef4444">*</span>
                    </label>
                    <input type="text" name="name" id="categoryNameInput" class="form-control" 
                           value="{{ old('name', $category->getRawOriginal('name')) }}" required 
                           style="width:100%;height:42px;border-radius:8px;font-size:0.95rem"
                           placeholder="e.g. Shellfish, Whole Fish, Prawns">
                    @error('name')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;background:#f8fafc;padding:14px;border-radius:8px;border:1px solid #e2e8f0">
                    <div>
                        <label class="form-label" style="font-weight:600;color:#1e293b;font-size:0.88rem;margin-bottom:6px;display:flex;align-items:center;gap:6px">
                            <span>🇨🇳</span> Chinese Name (简体中文)
                        </label>
                        <input type="text" name="name_zh" class="form-control" 
                               value="{{ old('name_zh', $category->name_zh) }}" 
                               style="width:100%;height:40px;border-radius:6px;font-size:0.9rem"
                               placeholder="e.g. 贝类海鲜 / 虾类">
                        @error('name_zh')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label" style="font-weight:600;color:#1e293b;font-size:0.88rem;margin-bottom:6px;display:flex;align-items:center;gap:6px">
                            <span>🇲🇾</span> Malay Name (Bahasa Melayu)
                        </label>
                        <input type="text" name="name_bm" class="form-control" 
                               value="{{ old('name_bm', $category->name_bm) }}" 
                               style="width:100%;height:40px;border-radius:6px;font-size:0.9rem"
                               placeholder="e.g. Makanan Laut Bercangkerang / Udang">
                        @error('name_bm')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                </div>

@push('styles')
<style>
/* ─── Custom Searchable Select (Parent Category) ─── */
.custom-searchable-select {
    position: relative;
    width: 100%;
}
.searchable-trigger-box {
    width: 100%;
    min-height: 42px;
    height: 42px;
    padding: 0 12px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 0.92rem;
    color: #0f172a;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
    box-sizing: border-box;
}
.searchable-trigger-box:hover {
    border-color: #94a3b8;
    background: #f8fafc;
}
.searchable-trigger-box:focus,
.custom-searchable-select.is-open .searchable-trigger-box {
    outline: none;
    border-color: #2563eb;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
.searchable-selected-content {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
    min-width: 0;
}
.selected-icon {
    font-size: 1rem;
    line-height: 1;
    flex-shrink: 0;
}
.selected-text {
    font-size: 0.92rem;
    font-weight: 500;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.selected-text.is-placeholder {
    color: #64748b;
    font-weight: 400;
}
.searchable-trigger-actions {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}
.searchable-clear-btn {
    border: none;
    background: #e2e8f0;
    color: #475569;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
    padding: 0;
}
.searchable-clear-btn:hover {
    background: #fee2e2;
    color: #ef4444;
}
.searchable-chevron-icon {
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.custom-searchable-select.is-open .searchable-chevron-icon {
    transform: rotate(180deg);
    color: #2563eb;
}
.searchable-panel {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.18), 0 6px 14px -2px rgba(15, 23, 42, 0.08);
    padding: 8px;
    z-index: 1050;
    display: none;
    box-sizing: border-box;
    animation: searchDropdownFade 0.15s ease-out;
}
@keyframes searchDropdownFade {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}
.custom-searchable-select.is-open .searchable-panel {
    display: block;
}
.searchable-search-box {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    margin-bottom: 6px !important;
}
.searchable-search-box .search-icon {
    position: absolute !important;
    left: 12px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    color: #94a3b8 !important;
    pointer-events: none !important;
    z-index: 5 !important;
    display: flex !important;
    align-items: center !important;
}
.searchable-filter-input {
    width: 100% !important;
    height: 38px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 7px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    padding-left: 38px !important;
    padding-right: 32px !important;
    font-size: 0.88rem !important;
    color: #0f172a !important;
    outline: none !important;
    background: #f8fafc !important;
    transition: all 0.15s ease !important;
    box-sizing: border-box !important;
}
.searchable-filter-input:focus {
    border-color: #2563eb !important;
    background: #ffffff !important;
    box-shadow: 0 0 0 2.5px rgba(37, 99, 235, 0.12) !important;
}
.searchable-search-clear {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: #e2e8f0;
    color: #64748b;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    padding: 0;
    transition: all 0.15s;
}
.searchable-search-clear:hover {
    background: #cbd5e1;
    color: #1e293b;
}
.searchable-count-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 4px 6px 6px;
    font-size: 0.74rem;
    color: #64748b;
    font-weight: 600;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 4px;
}
.searchable-options-scroll {
    max-height: 230px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 2px;
    scrollbar-width: thin;
    overscroll-behavior: contain;
}
.searchable-options-scroll::-webkit-scrollbar {
    width: 5px;
}
.searchable-options-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.searchable-option-item {
    padding: 8px 10px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.12s ease;
    border: 1px solid transparent;
    user-select: none;
}
.searchable-option-item:hover,
.searchable-option-item.is-focused {
    background: #f1f5f9;
    border-color: #e2e8f0;
}
.searchable-option-item.is-selected {
    background: #eff6ff !important;
    border-color: #bfdbfe !important;
}
.searchable-option-item.is-selected .option-title {
    color: #1d4ed8 !important;
    font-weight: 700 !important;
}
.searchable-option-item.is-selected:hover,
.searchable-option-item.is-selected.is-focused {
    background: #dbeafe !important;
    border-color: #93c5fd !important;
}
.option-left {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 0;
    flex: 1;
}
.option-icon {
    font-size: 1rem;
    line-height: 1;
    flex-shrink: 0;
}
.option-text-group {
    display: flex;
    flex-direction: column;
    min-width: 0;
}
.option-title {
    font-size: 0.88rem;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.option-desc {
    font-size: 0.72rem;
    color: #94a3b8;
    line-height: 1.1;
    margin-top: 1px;
}
.option-check {
    display: none;
    color: #2563eb;
    margin-left: 8px;
    flex-shrink: 0;
}
.searchable-option-item.is-selected .option-check {
    display: inline-flex;
}
.searchable-divider {
    height: 1px;
    background: #f1f5f9;
    margin: 4px 0;
}
.searchable-empty-state {
    padding: 22px 12px;
    text-align: center;
}
</style>
@endpush

                <div class="form-group" style="margin-bottom:18px;position:relative">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:flex;align-items:center;justify-content:space-between">
                        <span>Parent Category <span class="text-muted" style="font-weight:400">(Optional for subcategories)</span></span>
                        <span id="parentCategorySelectedBadge" class="badge" style="font-size:0.75rem;padding:2px 8px;border-radius:6px;background:#e0f2fe;color:#0284c7;display:none;font-weight:600">
                            Subcategory
                        </span>
                    </label>

                    <!-- Hidden Input for Form Submission -->
                    <input type="hidden" name="parent_id" id="parentIdInput" value="{{ old('parent_id', $category->parent_id) }}">

                    <!-- Custom Searchable Select Element -->
                    <div class="custom-searchable-select" id="parentCategorySelect">
                        <div class="searchable-trigger-box" id="parentCategoryTrigger" tabindex="0" role="combobox" aria-expanded="false" aria-haspopup="listbox">
                            <div class="searchable-selected-content">
                                <span class="selected-icon" id="parentCategoryIcon">📁</span>
                                <span class="selected-text" id="parentCategoryLabel">None (Top-Level Category)</span>
                            </div>
                            <div class="searchable-trigger-actions">
                                <button type="button" class="searchable-clear-btn" id="parentCategoryClearBtn" title="Clear selection (Set as Top-Level Category)" style="display:none">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                                <span class="searchable-chevron-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- Dropdown Panel -->
                        <div class="searchable-panel" id="parentCategoryPanel">
                            <!-- Search Bar -->
                            <div class="searchable-search-box" style="position:relative;display:flex;align-items:center;margin-bottom:6px">
                                <span class="search-icon" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);display:flex;align-items:center;pointer-events:none;color:#94a3b8;z-index:5">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </span>
                                <input type="text" 
                                       class="searchable-filter-input" 
                                       id="parentCategoryFilterInput" 
                                       placeholder="Type to search parent category..." 
                                       autocomplete="off" 
                                       spellcheck="false"
                                       style="padding-left:38px !important;padding-right:32px !important;width:100% !important;height:38px !important;box-sizing:border-box !important">
                                <button type="button" class="searchable-search-clear" id="parentCategoryFilterClear" style="display:none" title="Clear search">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </div>

                            <!-- Summary info -->
                            <div class="searchable-count-header">
                                <span id="parentCategoryCount">{{ count($parents) }} categories available</span>
                                <span class="text-muted" style="font-size:0.75rem">↑↓ keys &amp; Enter to select</span>
                            </div>

                            <!-- Options List -->
                            <div class="searchable-options-scroll" id="parentCategoryOptionsList" role="listbox">
                                <!-- Top-Level None Option -->
                                <div class="searchable-option-item {{ !old('parent_id', $category->parent_id) ? 'is-selected' : '' }}" 
                                     data-value="" 
                                     data-label="None (Top-Level Category)"
                                     data-icon="🌐"
                                     role="option">
                                    <div class="option-left">
                                        <span class="option-icon">🌐</span>
                                        <div class="option-text-group">
                                            <span class="option-title">None (Top-Level Category)</span>
                                            <span class="option-desc">This category will be displayed at the root level</span>
                                        </div>
                                    </div>
                                    <span class="option-check">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </span>
                                </div>

                                <div class="searchable-divider"></div>

                                @foreach($parents as $parent)
                                    <div class="searchable-option-item {{ old('parent_id', $category->parent_id) == $parent->id ? 'is-selected' : '' }}" 
                                         data-value="{{ $parent->id }}" 
                                         data-label="{{ $parent->name }}"
                                         data-icon="📁"
                                         role="option">
                                        <div class="option-left">
                                            <span class="option-icon">📁</span>
                                            <div class="option-text-group">
                                                <span class="option-title">{{ $parent->name }}</span>
                                                <span class="option-desc">Slug: /{{ $parent->slug }}</span>
                                            </div>
                                        </div>
                                        <span class="option-check">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Empty Search Results State -->
                            <div class="searchable-empty-state" id="parentCategoryEmpty" style="display:none">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 6px">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <div style="font-weight:600;color:#64748b;font-size:0.88rem">No matching categories found</div>
                                <div style="color:#94a3b8;font-size:0.78rem">Try searching with a different keyword</div>
                            </div>
                        </div>
                    </div>
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

// ─── Searchable Parent Category Dropdown Logic ───
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('parentCategorySelect');
    if (!container) return;

    const trigger = document.getElementById('parentCategoryTrigger');
    const panel = document.getElementById('parentCategoryPanel');
    const label = document.getElementById('parentCategoryLabel');
    const icon = document.getElementById('parentCategoryIcon');
    const clearBtn = document.getElementById('parentCategoryClearBtn');
    const filterInput = document.getElementById('parentCategoryFilterInput');
    const filterClear = document.getElementById('parentCategoryFilterClear');
    const hiddenInput = document.getElementById('parentIdInput');
    const countEl = document.getElementById('parentCategoryCount');
    const emptyEl = document.getElementById('parentCategoryEmpty');
    const badgeEl = document.getElementById('parentCategorySelectedBadge');
    const optionsList = document.getElementById('parentCategoryOptionsList');
    const options = Array.from(optionsList.querySelectorAll('.searchable-option-item'));

    let focusedIndex = -1;

    // Initialize display from current hidden input value
    function syncFromValue() {
        const currentVal = hiddenInput.value ? String(hiddenInput.value).trim() : '';
        let matched = null;

        options.forEach((opt, idx) => {
            const val = opt.getAttribute('data-value') ? String(opt.getAttribute('data-value')).trim() : '';
            if (val === currentVal) {
                opt.classList.add('is-selected');
                matched = opt;
                focusedIndex = idx;
            } else {
                opt.classList.remove('is-selected');
            }
        });

        if (matched && currentVal !== '') {
            label.textContent = matched.getAttribute('data-label') || 'Selected Category';
            icon.textContent = matched.getAttribute('data-icon') || '📁';
            clearBtn.style.display = 'inline-flex';
            if (badgeEl) badgeEl.style.display = 'inline-block';
        } else {
            // None / Top-Level
            label.textContent = 'None (Top-Level Category)';
            icon.textContent = '🌐';
            clearBtn.style.display = 'none';
            if (badgeEl) badgeEl.style.display = 'none';
            const noneOpt = options.find(o => !o.getAttribute('data-value'));
            if (noneOpt) noneOpt.classList.add('is-selected');
        }
    }

    syncFromValue();

    function openDropdown() {
        container.classList.add('is-open');
        trigger.setAttribute('aria-expanded', 'true');
        filterInput.value = '';
        if (filterClear) filterClear.style.display = 'none';
        filterOptions('');
        setTimeout(() => {
            filterInput.focus();
            // Scroll selected item into view
            const selectedOpt = optionsList.querySelector('.searchable-option-item.is-selected');
            if (selectedOpt) {
                selectedOpt.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            }
        }, 60);
    }

    function closeDropdown() {
        container.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
        clearHighlight();
    }

    function toggleDropdown(e) {
        if (e) e.stopPropagation();
        if (container.classList.contains('is-open')) {
            closeDropdown();
        } else {
            openDropdown();
        }
    }

    trigger.addEventListener('click', toggleDropdown);

    trigger.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
            e.preventDefault();
            if (!container.classList.contains('is-open')) {
                openDropdown();
            }
        }
    });

    // Clear selection back to None
    clearBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        selectOption('', 'None (Top-Level Category)', '🌐');
    });

    // Filter clear
    if (filterClear) {
        filterClear.addEventListener('click', function(e) {
            e.stopPropagation();
            filterInput.value = '';
            filterClear.style.display = 'none';
            filterOptions('');
            filterInput.focus();
        });
    }

    // Filter typing
    filterInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        if (filterClear) {
            filterClear.style.display = query ? 'inline-flex' : 'none';
        }
        filterOptions(query);
    });

    filterInput.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    function getVisibleOptions() {
        return options.filter(opt => opt.style.display !== 'none');
    }

    function clearHighlight() {
        options.forEach(opt => opt.classList.remove('is-focused'));
        focusedIndex = -1;
    }

    function setHighlight(index) {
        const visible = getVisibleOptions();
        if (visible.length === 0) return;
        clearHighlight();
        if (index < 0) index = 0;
        if (index >= visible.length) index = visible.length - 1;
        focusedIndex = index;
        visible[focusedIndex].classList.add('is-focused');
        visible[focusedIndex].scrollIntoView({ block: 'nearest' });
    }

    // Keyboard navigation in search input
    filterInput.addEventListener('keydown', function(e) {
        const visible = getVisibleOptions();
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            let nextIndex = focusedIndex + 1;
            if (nextIndex >= visible.length) nextIndex = 0;
            setHighlight(nextIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            let prevIndex = focusedIndex - 1;
            if (prevIndex < 0) prevIndex = visible.length - 1;
            setHighlight(prevIndex);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (visible.length > 0 && focusedIndex >= 0 && visible[focusedIndex]) {
                const opt = visible[focusedIndex];
                selectOption(opt.getAttribute('data-value') || '', opt.getAttribute('data-label'), opt.getAttribute('data-icon') || '📁');
            } else if (visible.length > 0) {
                // Select first visible
                const opt = visible[0];
                selectOption(opt.getAttribute('data-value') || '', opt.getAttribute('data-label'), opt.getAttribute('data-icon') || '📁');
            }
        } else if (e.key === 'Escape') {
            e.preventDefault();
            closeDropdown();
            trigger.focus();
        } else if (e.key === 'Tab') {
            closeDropdown();
        }
    });

    function filterOptions(query) {
        let matchCount = 0;
        options.forEach(opt => {
            const val = opt.getAttribute('data-value') || '';
            const lbl = (opt.getAttribute('data-label') || '').toLowerCase();
            const descEl = opt.querySelector('.option-desc');
            const desc = descEl ? descEl.textContent.toLowerCase() : '';

            // If empty query, show all
            if (!query) {
                opt.style.display = 'flex';
                matchCount++;
                return;
            }

            // Match if label or desc contains query, or if searching "none"/"top" and it's the root option
            const isNoneOption = val === '';
            const isMatch = lbl.includes(query) || desc.includes(query) || (isNoneOption && ('none'.includes(query) || 'top'.includes(query) || 'root'.includes(query)));

            if (isMatch) {
                opt.style.display = 'flex';
                matchCount++;
            } else {
                opt.style.display = 'none';
            }
        });

        // Update count & empty state
        if (countEl) {
            if (query) {
                countEl.textContent = matchCount + (matchCount === 1 ? ' category match' : ' category matches');
            } else {
                countEl.textContent = (options.length - 1) + ' categories available';
            }
        }

        if (emptyEl) {
            emptyEl.style.display = matchCount === 0 ? 'block' : 'none';
        }

        // Highlight first matching
        const visible = getVisibleOptions();
        if (visible.length > 0) {
            setHighlight(0);
        } else {
            clearHighlight();
        }
    }

    function selectOption(value, labelText, iconText) {
        hiddenInput.value = value;
        syncFromValue();
        closeDropdown();
        trigger.focus();

        // Dispatch change event on hidden input in case listeners exist
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    // Option click listener
    options.forEach(opt => {
        opt.addEventListener('click', function(e) {
            e.stopPropagation();
            const val = this.getAttribute('data-value') || '';
            const lbl = this.getAttribute('data-label') || 'Selected Category';
            const ic = this.getAttribute('data-icon') || '📁';
            selectOption(val, lbl, ic);
        });

        opt.addEventListener('mouseenter', function() {
            clearHighlight();
            this.classList.add('is-focused');
            const visible = getVisibleOptions();
            focusedIndex = visible.indexOf(this);
        });
    });

    // Close when clicked outside
    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            closeDropdown();
        }
    });
});
</script>
@endpush
