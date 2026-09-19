@extends('layouts.admin')
@section('title', ($isEdit ? 'Edit Page SEO: ' . $pageSeo->page_name : 'Create Page SEO') . ' — Admin')

@section('content')

{{-- Topbar Header --}}
<div class="admin-topbar seo-form-topbar">
    <div class="seo-topbar-left">
        <div class="seo-breadcrumb">
            <a href="{{ route('admin.page-seo.index') }}" class="seo-breadcrumb-link">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Page SEO
            </a>
            <span class="seo-breadcrumb-sep">/</span>
            <span class="seo-breadcrumb-current">{{ $isEdit ? $pageSeo->page_name : 'New Page' }}</span>
        </div>
        <h1 class="admin-page-title seo-form-title">
            <span>{{ $isEdit ? '✏️' : '✨' }}</span>
            <span>{{ $isEdit ? 'Edit Page SEO' : 'Create Page SEO' }}</span>
        </h1>
    </div>
    <div class="seo-topbar-actions">
        <a href="{{ route('admin.page-seo.index') }}" class="btn btn-secondary seo-btn-cancel">Cancel</a>
        <button type="submit" form="seoForm" class="btn btn-primary seo-btn-save">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            <span>{{ $isEdit ? 'Update Page SEO' : 'Save Page SEO' }}</span>
        </button>
    </div>
</div>

<form id="seoForm" action="{{ $isEdit ? route('admin.page-seo.update', $pageSeo) : route('admin.page-seo.store') }}" method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="seo-form-layout">

        {{-- ===== LEFT / MAIN COLUMN ===== --}}
        <div class="seo-form-main">

            {{-- Card 1: Page Selection --}}
            <div class="seo-card">
                <div class="seo-card-header">
                    <div class="seo-card-icon">📄</div>
                    <div>
                        <div class="seo-card-title">Target Page</div>
                        <div class="seo-card-subtitle">Select which public URL or storefront route this SEO configuration applies to.</div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="seo-label">Page Selection <span class="required" style="color:#ef4444">*</span></label>

                    @if($isEdit && $pageSeo->is_system)
                        <div class="seo-system-page-display">
                            <div class="seo-system-page-info">
                                <span class="seo-system-name">{{ $pageSeo->page_name }}</span>
                                <code class="seo-slug">/{{ $pageSeo->page_slug }}</code>
                            </div>
                            <span class="seo-system-badge">🔒 System Page</span>
                        </div>
                        <input type="hidden" name="page_name" value="{{ $pageSeo->page_name }}">
                        <input type="hidden" name="page_slug" value="{{ $pageSeo->page_slug }}">
                    @else
                        <select id="pageSelectDropdown" class="form-control seo-input seo-select-dropdown" onchange="handlePageSelectChange(this)" required>
                            @if(count($defaultPages) > 0)
                                <option value="">— Choose a system page to configure —</option>
                            @else
                                <option value="">— All system pages are configured —</option>
                            @endif
                            @foreach($defaultPages as $slug => $data)
                                <option value="{{ $slug }}"
                                        data-name="{{ $data['name'] }}"
                                        data-title="{{ $data['title'] }}"
                                        data-desc="{{ $data['description'] }}"
                                        data-keywords="{{ $data['keywords'] }}"
                                        {{ old('page_slug', $pageSeo->page_slug) === $slug ? 'selected' : '' }}>
                                    {{ $data['name'] }}  ·  /{{ $slug }}
                                </option>
                            @endforeach
                            <option value="custom" {{ old('page_slug', $pageSeo->page_slug) && !isset($defaultPages[old('page_slug', $pageSeo->page_slug)]) ? 'selected' : '' }}>
                                ✏️ Custom Page (Enter custom name & slug)
                            </option>
                        </select>

                        @if(count($defaultPages) === 0 && !$isEdit)
                            <div class="seo-notice-box">
                                <span>✅</span>
                                <span>All system pages are already configured. Use <strong>Custom Page</strong> below to create a custom landing page SEO.</span>
                            </div>
                        @else
                            <div class="seo-hint" style="margin-top:6px">
                                {{ count($defaultPages) }} system page{{ count($defaultPages) !== 1 ? 's' : '' }} available — or choose <strong>Custom Page</strong> to add your own.
                            </div>
                        @endif

                        <div id="customPageInputs" class="seo-custom-inputs-grid" style="display:{{ old('page_slug', $pageSeo->page_slug) && !isset($defaultPages[old('page_slug', $pageSeo->page_slug)]) ? 'grid' : 'none' }}">
                            <div class="form-group">
                                <label class="seo-label">Page Name <span class="required" style="color:#ef4444">*</span></label>
                                <input type="text" id="customPageName" name="page_name" class="form-control seo-input"
                                       value="{{ old('page_name', $pageSeo->page_name) }}"
                                       placeholder="e.g. Sustainability Charter">
                            </div>
                            <div class="form-group">
                                <label class="seo-label">Page Slug / URL Path <span class="required" style="color:#ef4444">*</span></label>
                                <input type="text" id="customPageSlug" name="page_slug" class="form-control seo-input"
                                       value="{{ old('page_slug', $pageSeo->page_slug) }}"
                                       placeholder="e.g. sustainability">
                                <div class="seo-hint">Public URL: <code class="seo-slug">yoursite.com/<span id="slugPreview">...</span></code></div>
                            </div>
                        </div>
                    @endif

                    @error('page_slug')
                        <div class="seo-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Card 2: English SEO / Metadata --}}
            <div class="seo-card">
                <div class="seo-card-header">
                    <div class="seo-card-icon">🌐</div>
                    <div>
                        <div class="seo-card-title">Search Engine Metadata</div>
                        <div class="seo-card-subtitle">Primary title and description displayed in Google search results and browser tabs.</div>
                    </div>
                </div>

                {{-- Meta Title --}}
                <div class="form-group seo-field-group">
                    <div class="seo-label-row">
                        <label class="seo-label" style="margin:0">Meta Title</label>
                        <span class="seo-char-counter" id="titleCounter">0 / 60</span>
                    </div>
                    <input type="text" name="meta_title" id="metaTitleInput" class="form-control seo-input"
                           value="{{ old('meta_title', $pageSeo->meta_title) }}"
                           placeholder="e.g. Premium Frozen Food & Sourcing — MST"
                           maxlength="80">
                    <div class="seo-hint">Recommended length: 50–60 characters. Shows as the main clickable headline in search engines.</div>
                </div>

                {{-- Meta Description --}}
                <div class="form-group seo-field-group">
                    <div class="seo-label-row">
                        <label class="seo-label" style="margin:0">Meta Description</label>
                        <span class="seo-char-counter" id="descCounter">0 / 160</span>
                    </div>
                    <textarea name="meta_description" id="metaDescInput" class="form-control seo-input" rows="3"
                              maxlength="250"
                              placeholder="e.g. Explore certified sustainable seafood products, wholesale seafood export, and cold-chain distribution...">{{ old('meta_description', $pageSeo->meta_description) }}</textarea>
                    <div class="seo-hint">Recommended length: 120–160 characters. Shows as the descriptive snippet below your title in Google.</div>
                </div>

                {{-- Meta Keywords --}}
                <div class="form-group" style="margin-bottom:0">
                    <label class="seo-label">Meta Keywords <span class="seo-optional">(optional)</span></label>
                    <textarea name="meta_keywords" id="metaKeywordsInput" class="form-control seo-input" rows="2"
                              placeholder="e.g. frozen seafood, wholesale fish, halal seafood malaysia, cold chain export">{{ old('meta_keywords', $pageSeo->meta_keywords) }}</textarea>
                    <div class="seo-hint">Comma-separated keyword tags for internal catalog filtering and indexing.</div>
                </div>
            </div>

            {{-- Card 3: OpenGraph & Social Sharing --}}
            <div class="seo-card">
                <div class="seo-card-header">
                    <div class="seo-card-icon">🖼️</div>
                    <div>
                        <div class="seo-card-title">Social Share & Open Graph (OG)</div>
                        <div class="seo-card-subtitle">Card preview image shown when this page is shared on WhatsApp, Facebook, LinkedIn, or Twitter.</div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="seo-label">OpenGraph Share Image</label>

                    <div class="seo-og-input-group">
                        <input type="text" name="og_image" id="ogImageInput" class="form-control seo-input"
                               value="{{ old('og_image', $pageSeo->og_image) }}"
                               placeholder="https://... or choose from gallery">
                        <button type="button" class="btn btn-secondary seo-gallery-btn" onclick="openGalleryPickerForOg()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <span>Pick from Gallery</span>
                        </button>
                    </div>

                    {{-- OG Preview Box --}}
                    <div id="ogImagePreview" class="seo-og-preview-wrap" style="display:{{ !empty($pageSeo->og_image) ? 'block' : 'none' }}">
                        <div class="seo-og-preview-box">
                            <img id="ogPreviewImg"
                                 src="{{ $pageSeo->og_image ? (str_starts_with($pageSeo->og_image, 'http') ? $pageSeo->og_image : asset('storage/' . $pageSeo->og_image)) : '' }}"
                                 class="seo-og-preview-img" alt="OG Preview">
                            <div class="seo-og-preview-content">
                                <div class="seo-og-preview-badge">Social Card Preview</div>
                                <div id="ogTitlePreview" class="seo-og-preview-title">{{ $pageSeo->meta_title ?? 'Page Title' }}</div>
                                <div id="ogDescPreview" class="seo-og-preview-desc">{{ Str::limit($pageSeo->meta_description ?? 'Page description preview...', 90) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="seo-hint">Recommended dimension: 1200 × 630 px (1.91:1 ratio). Formats: WEBP, PNG, JPG.</div>
                </div>
            </div>

            {{-- Card 4: Technical & Structured Data --}}
            <div class="seo-card">
                <div class="seo-card-header">
                    <div class="seo-card-icon">⚙️</div>
                    <div>
                        <div class="seo-card-title">Technical SEO & Schema</div>
                        <div class="seo-card-subtitle">Canonical canonicalization and JSON-LD structured data markup for rich search results.</div>
                    </div>
                </div>

                <div class="form-group seo-field-group">
                    <label class="seo-label">Canonical URL <span class="seo-optional">(optional)</span></label>
                    <input type="text" name="canonical_url" class="form-control seo-input"
                           value="{{ old('canonical_url', $pageSeo->canonical_url) }}"
                           placeholder="https://yourdomain.com/exact-canonical-path">
                    <div class="seo-hint">Leave blank to use the current page canonical URL automatically.</div>
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="seo-label">Schema JSON-LD Structured Data <span class="seo-optional">(optional)</span></label>
                    <textarea name="schema_markup" class="form-control seo-input seo-schema-textarea" rows="5"
                              placeholder='{"@@context": "https://schema.org", "@@type": "WebPage", "name": "..."}'>{{ old('schema_markup', $pageSeo->schema_markup) }}</textarea>
                    <div class="seo-hint">Valid JSON-LD markup. Validate anytime with the <a href="https://search.google.com/test/rich-results" target="_blank" style="color:#4f46e5;font-weight:600">Google Rich Results Test</a>.</div>
                </div>
            </div>

        </div>{{-- /.seo-form-main --}}

        {{-- ===== RIGHT SIDEBAR ===== --}}
        <div class="seo-form-sidebar">

            {{-- Card A: SEO Health Checklist --}}
            <div class="seo-card seo-sidebar-card">
                <div class="seo-sidebar-card-title">
                    <span>📊</span>
                    <span>SEO Optimization Score</span>
                </div>

                <div class="seo-checklist" id="seoChecklist">
                    <div class="seo-check-item" id="check-title">
                        <span class="seo-check-icon" id="check-title-icon">⬜</span>
                        <span class="seo-check-text">Meta title set</span>
                    </div>
                    <div class="seo-check-item" id="check-title-len">
                        <span class="seo-check-icon" id="check-title-len-icon">⬜</span>
                        <span class="seo-check-text">Title length ≤ 60 chars</span>
                    </div>
                    <div class="seo-check-item" id="check-desc">
                        <span class="seo-check-icon" id="check-desc-icon">⬜</span>
                        <span class="seo-check-text">Meta description set</span>
                    </div>
                    <div class="seo-check-item" id="check-desc-len">
                        <span class="seo-check-icon" id="check-desc-len-icon">⬜</span>
                        <span class="seo-check-text">Description ≤ 160 chars</span>
                    </div>
                    <div class="seo-check-item" id="check-og">
                        <span class="seo-check-icon" id="check-og-icon">⬜</span>
                        <span class="seo-check-text">OpenGraph image set</span>
                    </div>
                    <div class="seo-check-item" id="check-kw">
                        <span class="seo-check-icon" id="check-kw-icon">⬜</span>
                        <span class="seo-check-text">Keyword tags defined</span>
                    </div>
                </div>

                <div class="seo-score-footer">
                    <div class="seo-score-display">
                        <span id="seo-score-value" class="seo-score-num">0</span>
                        <span class="seo-score-denom">/ 6</span>
                    </div>
                    <div class="seo-score-label">checks passing</div>
                </div>
            </div>

            {{-- Card B: Live Google SERP Preview --}}
            <div class="seo-card seo-sidebar-card">
                <div class="seo-sidebar-card-title">
                    <span>🔍</span>
                    <span>Google SERP Snippet Preview</span>
                </div>

                <div class="seo-serp-box">
                    <div class="seo-serp-header">
                        <span class="seo-serp-favicon">🌐</span>
                        <span class="seo-serp-url" id="serp-url-preview">https://yoursite.com/{{ $pageSeo->page_slug ?? 'page' }}</span>
                    </div>
                    <div id="serp-title" class="seo-serp-title">
                        {{ $pageSeo->meta_title ?: 'Your page title appears here' }}
                    </div>
                    <div id="serp-desc" class="seo-serp-desc">
                        {{ $pageSeo->meta_description ?: 'Your meta description snippet will appear here in Google search results. Write a compelling description to improve click-through rate.' }}
                    </div>
                </div>
                <div class="seo-hint" style="margin-top:8px">⚡ Live preview updates in real time as you type.</div>
            </div>

            {{-- Card C: Actions --}}
            <div class="seo-card seo-actions-card">
                <button type="submit" form="seoForm" class="btn btn-primary seo-action-submit-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    <span>{{ $isEdit ? 'Update Page SEO' : 'Save Page SEO' }}</span>
                </button>
                <a href="{{ route('admin.page-seo.index') }}" class="btn btn-secondary seo-action-cancel-btn">
                    Cancel
                </a>
            </div>

        </div>{{-- /.seo-form-sidebar --}}

    </div>{{-- /.seo-form-layout --}}
</form>

<x-gallery-picker-modal />
@endsection

@push('scripts')
<script>
// Page select change
function handlePageSelectChange(select) {
    const customDiv = document.getElementById('customPageInputs');
    const nameInput = document.getElementById('customPageName');
    const slugInput = document.getElementById('customPageSlug');

    if (select.value === 'custom') {
        if (customDiv) customDiv.style.display = 'grid';
        if (nameInput) {
            nameInput.required = true;
            nameInput.value = '';
            setTimeout(() => nameInput.focus(), 50);
        }
        if (slugInput) {
            slugInput.required = true;
            slugInput.value = '';
        }
        updateSlugPreview('');
    } else if (select.value) {
        if (customDiv) customDiv.style.display = 'none';
        const opt = select.options[select.selectedIndex];
        if (nameInput) {
            nameInput.required = false;
            nameInput.value = opt.dataset.name || '';
        }
        if (slugInput) {
            slugInput.required = false;
            slugInput.value = select.value;
        }
        const titleEl = document.getElementById('metaTitleInput');
        const descEl  = document.getElementById('metaDescInput');
        const kwEl    = document.getElementById('metaKeywordsInput');
        if (titleEl && !titleEl.value && opt.dataset.title) titleEl.value = opt.dataset.title;
        if (descEl  && !descEl.value  && opt.dataset.desc)  descEl.value  = opt.dataset.desc;
        if (kwEl    && !kwEl.value    && opt.dataset.keywords) kwEl.value  = opt.dataset.keywords;
        updateAll();
    } else {
        if (customDiv) customDiv.style.display = 'none';
    }
}

function updateSlugPreview(val) {
    const el = document.getElementById('slugPreview');
    const serpUrl = document.getElementById('serp-url-preview');
    if (el) el.textContent = val || '...';
    if (serpUrl) serpUrl.textContent = 'https://yoursite.com/' + (val || 'page');
}

function openGalleryPickerForOg() {
    if (typeof openGalleryPicker === 'function') {
        openGalleryPicker((path, url, name) => {
            const input = document.getElementById('ogImageInput');
            if (input) input.value = url;
            const preview = document.getElementById('ogImagePreview');
            const img = document.getElementById('ogPreviewImg');
            if (img) img.src = url;
            if (preview) preview.style.display = 'block';
            updateSeoChecklist();
        });
    }
}

// ---- Live counters + SERP + checklist ----
function updateCounter(inputId, counterId, limit) {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(counterId);
    if (!input || !counter) return;
    const len = input.value.length;
    counter.textContent = len + ' / ' + limit;
    if (len > limit) {
        counter.style.color = '#ef4444';
    } else if (len >= limit * 0.8) {
        counter.style.color = '#f59e0b';
    } else {
        counter.style.color = '#94a3b8';
    }
}

function updateSerp() {
    const title = document.getElementById('metaTitleInput')?.value?.trim();
    const desc  = document.getElementById('metaDescInput')?.value?.trim();
    const serpTitle = document.getElementById('serp-title');
    const serpDesc  = document.getElementById('serp-desc');
    if (serpTitle) serpTitle.textContent = title || 'Your page title appears here';
    if (serpDesc)  serpDesc.textContent  = desc  || 'Your meta description snippet will appear here in Google search results.';

    // OG Card preview
    const ogTitle = document.getElementById('ogTitlePreview');
    const ogDesc  = document.getElementById('ogDescPreview');
    if (ogTitle) ogTitle.textContent = title || 'Page Title';
    if (ogDesc)  ogDesc.textContent  = (desc || '').substring(0, 90) + (desc && desc.length > 90 ? '...' : '');
}

function updateSeoChecklist() {
    const title = document.getElementById('metaTitleInput')?.value?.trim() || '';
    const desc  = document.getElementById('metaDescInput')?.value?.trim()  || '';
    const og    = document.getElementById('ogImageInput')?.value?.trim()   || '';
    const kw    = document.getElementById('metaKeywordsInput')?.value?.trim() || '';

    const checks = [
        ['check-title',     title.length > 0],
        ['check-title-len', title.length > 0 && title.length <= 60],
        ['check-desc',      desc.length > 0],
        ['check-desc-len',  desc.length > 0 && desc.length <= 160],
        ['check-og',        og.length > 0],
        ['check-kw',        kw.length > 0],
    ];

    let passing = 0;
    checks.forEach(([id, ok]) => {
        const icon = document.getElementById(id + '-icon');
        if (icon) { icon.textContent = ok ? '✅' : '⬜'; }
        if (ok) passing++;
    });

    const score = document.getElementById('seo-score-value');
    if (score) {
        score.textContent = passing;
        score.style.color = passing >= 5 ? '#16a34a' : passing >= 3 ? '#d97706' : '#ef4444';
    }
}

function updateAll() {
    updateCounter('metaTitleInput', 'titleCounter', 60);
    updateCounter('metaDescInput',  'descCounter',  160);
    updateSerp();
    updateSeoChecklist();
}

document.addEventListener('DOMContentLoaded', () => {
    const titleInput = document.getElementById('metaTitleInput');
    const descInput  = document.getElementById('metaDescInput');
    const ogInput    = document.getElementById('ogImageInput');
    const kwInput    = document.getElementById('metaKeywordsInput');

    [titleInput, descInput, ogInput, kwInput].forEach(el => {
        if (el) el.addEventListener('input', updateAll);
    });

    if (ogInput) {
        ogInput.addEventListener('input', () => {
            const url = ogInput.value.trim();
            const preview = document.getElementById('ogImagePreview');
            const img = document.getElementById('ogPreviewImg');
            if (url) {
                if (img) img.src = url;
                if (preview) preview.style.display = 'block';
            } else {
                if (preview) preview.style.display = 'none';
            }
        });
    }

    updateAll();

    const customSlugInput = document.getElementById('customPageSlug');
    if (customSlugInput) {
        customSlugInput.addEventListener('input', () => {
            updateSlugPreview(customSlugInput.value.trim());
        });
        updateSlugPreview(customSlugInput.value.trim());
    }

    @if(!$isEdit)
    const pageSelect = document.getElementById('pageSelectDropdown');
    if (pageSelect && !pageSelect.value) {
        const hasRealOptions = Array.from(pageSelect.options).some(o => o.value && o.value !== 'custom');
        if (!hasRealOptions) {
            pageSelect.value = 'custom';
            handlePageSelectChange(pageSelect);
        }
    }
    @endif
});
</script>
@endpush

@push('styles')
<style>
/* ===== PAGE SEO FORM DESIGN SYSTEM ===== */

.seo-form-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e2e8f0;
}

.seo-topbar-left {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.seo-breadcrumb {
    font-size: 0.82rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}
.seo-breadcrumb-link {
    color: #4f46e5;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.seo-breadcrumb-link:hover { text-decoration: underline; }
.seo-breadcrumb-sep { color: #cbd5e1; }
.seo-breadcrumb-current { color: #475569; font-weight: 500; }

.seo-form-title {
    font-size: clamp(1.35rem, 2.5vw, 1.75rem) !important;
    font-weight: 700 !important;
    color: #0f172a !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}

.seo-topbar-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.seo-btn-cancel {
    padding: 8px 16px !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    font-size: 0.88rem !important;
}

.seo-btn-save {
    background: #4f46e5 !important;
    border-color: #4f46e5 !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    padding: 8px 18px !important;
    border-radius: 8px !important;
    font-size: 0.88rem !important;
    box-shadow: 0 2px 6px rgba(79,70,229,0.25) !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
}
.seo-btn-save:hover {
    background: #4338ca !important;
    border-color: #4338ca !important;
}

/* Layout Grid */
.seo-form-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 22px;
    align-items: start;
}

.seo-form-main {
    display: flex;
    flex-direction: column;
    gap: 18px;
    min-width: 0;
}

.seo-form-sidebar {
    display: flex;
    flex-direction: column;
    gap: 16px;
    position: sticky;
    top: 80px;
}

/* Card */
.seo-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    padding: 22px;
    transition: border-color 0.15s ease;
}
.seo-card:hover {
    border-color: #cbd5e1;
}

.seo-card-header {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
}
.seo-card-icon {
    font-size: 1.35rem;
    line-height: 1;
    width: 36px;
    height: 36px;
    background: #f8fafc;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid #e2e8f0;
}
.seo-card-title {
    font-weight: 700;
    color: #0f172a;
    font-size: 1rem;
}
.seo-card-subtitle {
    font-size: 0.8rem;
    color: #64748b;
    margin-top: 3px;
    line-height: 1.4;
}

/* Form Fields & Labels */
.seo-label {
    font-weight: 600;
    color: #334155;
    font-size: 0.875rem;
    display: block;
    margin-bottom: 6px;
}
.seo-label-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 6px;
}
.seo-optional {
    font-weight: 400;
    color: #94a3b8;
    font-size: 0.8rem;
}
.seo-hint {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 5px;
    line-height: 1.4;
}
.seo-field-group {
    margin-bottom: 18px;
}
.seo-error {
    color: #ef4444;
    font-size: 0.82rem;
    margin-top: 4px;
    font-weight: 500;
}

/* Input Fields */
.seo-input {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 9px 12px !important;
    font-size: 0.88rem !important;
    width: 100% !important;
    background: #ffffff !important;
    transition: border-color 0.15s, box-shadow 0.15s !important;
}
.seo-input:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.12) !important;
}

.seo-select-dropdown {
    height: 44px !important;
}

.seo-schema-textarea {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
    font-size: 0.82rem !important;
    line-height: 1.4 !important;
}

/* System Page Display */
.seo-system-page-display {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.seo-system-page-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.seo-system-name {
    font-weight: 700;
    color: #0f172a;
    font-size: 0.95rem;
}
.seo-slug {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 0.78rem;
    color: #475569;
    background: #ffffff;
    padding: 2px 8px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
}
.seo-system-badge {
    font-size: 0.72rem;
    font-weight: 700;
    background: #e0f2fe;
    color: #0369a1;
    border-radius: 20px;
    padding: 3px 10px;
    border: 1px solid #bae6fd;
    white-space: nowrap;
}

/* Custom Page Inputs Grid */
.seo-custom-inputs-grid {
    margin-top: 14px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

.seo-notice-box {
    margin-top: 8px;
    padding: 10px 14px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    font-size: 0.82rem;
    color: #166534;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Char counter badge */
.seo-char-counter {
    font-size: 0.75rem;
    font-weight: 700;
    color: #94a3b8;
}

/* OG Image Group */
.seo-og-input-group {
    display: flex;
    gap: 10px;
    align-items: center;
}
.seo-gallery-btn {
    white-space: nowrap !important;
    height: 42px !important;
    padding: 0 16px !important;
    font-size: 0.84rem !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    flex-shrink: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
}

.seo-og-preview-wrap {
    margin-top: 12px;
}
.seo-og-preview-box {
    display: flex;
    gap: 14px;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px;
}
.seo-og-preview-img {
    width: 90px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    flex-shrink: 0;
    border: 1px solid #e2e8f0;
    background: #ffffff;
}
.seo-og-preview-content {
    flex: 1;
    min-width: 0;
}
.seo-og-preview-badge {
    font-size: 0.68rem;
    font-weight: 700;
    color: #4f46e5;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 2px;
}
.seo-og-preview-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.seo-og-preview-desc {
    font-size: 0.78rem;
    color: #64748b;
    line-height: 1.35;
}

/* Sidebar Components */
.seo-sidebar-card {
    padding: 18px;
}
.seo-sidebar-card-title {
    font-size: 0.78rem;
    font-weight: 800;
    color: #4f46e5;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.seo-checklist {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.seo-check-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.82rem;
    color: #334155;
}
.seo-check-icon {
    font-size: 0.95rem;
    line-height: 1;
    flex-shrink: 0;
}
.seo-check-text {
    font-weight: 500;
}

.seo-score-footer {
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
    text-align: center;
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 6px;
}
.seo-score-display {
    display: flex;
    align-items: baseline;
    gap: 3px;
}
.seo-score-num {
    font-size: 1.85rem;
    font-weight: 900;
    color: #4f46e5;
    line-height: 1;
}
.seo-score-denom {
    font-size: 0.9rem;
    color: #94a3b8;
    font-weight: 700;
}
.seo-score-label {
    font-size: 0.78rem;
    color: #64748b;
    font-weight: 600;
}

/* SERP Preview Box */
.seo-serp-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
}
.seo-serp-header {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}
.seo-serp-favicon {
    font-size: 0.8rem;
}
.seo-serp-url {
    font-size: 0.75rem;
    color: #202124;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 500;
}
.seo-serp-title {
    color: #1a0dab;
    font-size: 0.95rem;
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: 4px;
    cursor: pointer;
}
.seo-serp-title:hover {
    text-decoration: underline;
}
.seo-serp-desc {
    font-size: 0.8rem;
    color: #4d5156;
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Actions Card */
.seo-actions-card {
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.seo-action-submit-btn {
    width: 100% !important;
    background: #4f46e5 !important;
    border-color: #4f46e5 !important;
    color: #ffffff !important;
    padding: 12px 0 !important;
    font-weight: 700 !important;
    font-size: 0.92rem !important;
    border-radius: 8px !important;
    box-shadow: 0 2px 6px rgba(79,70,229,0.25) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
}
.seo-action-submit-btn:hover {
    background: #4338ca !important;
    border-color: #4338ca !important;
}
.seo-action-cancel-btn {
    width: 100% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 10px 0 !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    font-size: 0.88rem !important;
}

/* Responsive Media Queries */
@media (max-width: 960px) {
    .seo-form-layout {
        grid-template-columns: 1fr;
    }
    .seo-form-sidebar {
        position: static;
    }
}

@media (max-width: 640px) {
    .seo-form-topbar {
        flex-direction: column;
        align-items: stretch;
    }
    .seo-topbar-actions {
        width: 100%;
        justify-content: flex-end;
    }
    .seo-topbar-actions .btn {
        flex: 1;
    }
    .seo-custom-inputs-grid {
        grid-template-columns: 1fr;
    }
    .seo-og-input-group {
        flex-direction: column;
        align-items: stretch;
    }
    .seo-gallery-btn {
        width: 100% !important;
        justify-content: center !important;
    }
}

@media (max-width: 480px) {
    .seo-card {
        padding: 16px;
    }
    .seo-card-header {
        margin-bottom: 14px;
        padding-bottom: 10px;
    }
    .seo-card-title {
        font-size: 0.92rem;
    }
    .seo-system-page-display {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    .seo-og-preview-box {
        flex-direction: column;
        align-items: flex-start;
    }
    .seo-og-preview-img {
        width: 100%;
        height: 120px;
    }
}
</style>
@endpush
