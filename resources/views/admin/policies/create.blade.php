@extends('layouts.admin')
@section('title', 'Add New Policy / Page — MST Admin')

@push('styles')
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<style>
    .insert-html-btn {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        color: #1e40af;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .insert-html-btn:hover {
        background: #eff6ff;
        border-color: #2563eb;
        color: #2563eb;
    }

    .html-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(6, 21, 43, 0.65);
        backdrop-filter: blur(4px);
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .html-modal-card {
        background: #ffffff;
        border-radius: 14px;
        width: 100%;
        max-width: 620px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        overflow: hidden;
    }
    .html-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .html-modal-close {
        border: none;
        background: transparent;
        font-size: 1.2rem;
        color: #94a3b8;
        cursor: pointer;
        line-height: 1;
    }
    .html-modal-close:hover {
        color: #ef4444;
    }
    .html-modal-footer {
        padding: 14px 20px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
</style>
@endpush

@section('content')
<!-- Topbar Navigation -->
<div class="admin-topbar" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;padding-bottom:18px;border-bottom:1px solid var(--gray-200);">
    <div>
        <a href="{{ route('admin.policies.index') }}" class="text-sm" style="color:var(--seagreen-700);text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px;font-weight:600">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to Policies &amp; Pages
        </a>
        <h1 class="admin-page-title" style="margin:0;font-size:clamp(1.4rem,3vw,1.85rem);">Add New Policy / Page</h1>
        <p class="text-sm text-muted" style="margin:4px 0 0;">Compose legal, shipping, and store policies with rich visual editor and multi-language translations</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <a href="{{ route('admin.policies.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" form="policyForm" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:8px;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Create Page
        </button>
    </div>
</div>

<!-- Translation Workflow Notice Banner -->
<div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:14px 18px;margin-bottom:24px;display:flex;align-items:center;gap:14px;">
    <div style="font-size:1.5rem;flex-shrink:0;">🌐</div>
    <div style="font-size:0.86rem;color:#1e40af;line-height:1.45;">
        <strong>English First Workflow:</strong> Create your primary English policy page here using CKEditor. Once saved, you can add Chinese (中文) and Malay (Bahasa Melayu) translations directly by clicking the <strong>+ ZH</strong> or <strong>+ BM</strong> buttons on the pages list!
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger mb-6" style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;border-radius:12px;padding:14px 18px;margin-bottom:24px;">
        <div style="font-weight:700;margin-bottom:4px;display:flex;align-items:center;gap:8px;">
            <span>⚠️</span> Please correct the errors below before submitting:
        </div>
        <ul style="margin:0;padding-left:20px;font-size:0.875rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form id="policyForm" action="{{ route('admin.policies.store') }}" method="POST">
    @csrf

    <div class="admin-form-layout">

        <!-- ─── Main Information Column ─────────────────────────────────── -->
        <div style="display:flex;flex-direction:column;gap:20px;min-width:0;">

            <!-- Basic Info Card -->
            <div class="card" style="padding:24px;border-radius:12px;">
                <div class="card-header" style="display:flex;align-items:center;gap:10px;margin-bottom:20px;border-bottom:1px solid #f1f5f9;padding-bottom:14px;">
                    <span style="font-size:1.25rem;">📄</span>
                    <div class="card-title" style="font-size:1.1rem;font-weight:700;color:#0f172a;margin:0;">General Information</div>
                </div>

                <!-- Page Title -->
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">
                        Page Title (🇬🇧 English Base) <span class="required" style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" name="title" id="pageTitle" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                           value="{{ old('title') }}" placeholder="e.g. Privacy Policy, Terms & Conditions, Wholesale FAQ" required autofocus>
                    @error('title')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <!-- URL Slug -->
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">
                        URL Slug
                    </label>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:0.84rem;color:#64748b;background:#f1f5f9;border:1px solid #cbd5e1;padding:8px 14px;border-radius:6px;font-weight:600;">/en/policy/</span>
                        <input type="text" name="slug" id="pageSlug" class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}"
                               value="{{ old('slug') }}" placeholder="privacy-policy" style="flex:1;">
                    </div>
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Auto-generated from title if left blank</div>
                    @error('slug')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <!-- Summary / Subtitle -->
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">
                        Brief Summary / Hero Subtitle (Optional)
                    </label>
                    <textarea name="summary" class="form-control" rows="2"
                              placeholder="Short 1-2 sentence description shown in the header banner of the public page...">{{ old('summary') }}</textarea>
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Displayed under the title on the storefront policy page</div>
                </div>
            </div>

            <!-- Page Content Editor Card -->
            <div class="card" style="padding:24px;border-radius:12px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;border-bottom:1px solid #f1f5f9;padding-bottom:14px;flex-wrap:wrap;gap:10px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:1.25rem;">✍️</span>
                        <div class="card-title" style="font-size:1.1rem;font-weight:700;color:#0f172a;margin:0;">Page Content (English Default)</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:0.75rem;font-weight:700;background:#eff6ff;color:#1d4ed8;padding:4px 10px;border-radius:6px;border:1px solid #bfdbfe;">
                            🇬🇧 Primary Language
                        </span>
                        <button type="button" class="insert-html-btn" onclick="openInsertHtmlModal('pageContent')">
                            <strong>&lt;/&gt;</strong> Insert Custom HTML
                        </button>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <textarea name="content" id="pageContent" required>{{ old('content', '<h2>1. Overview</h2><p>Write your policy content here...</p>') }}</textarea>
                </div>
            </div>

            <!-- SEO Settings Card -->
            <div class="card" style="padding:24px;border-radius:12px;">
                <div class="card-header" style="display:flex;align-items:center;gap:10px;margin-bottom:18px;border-bottom:1px solid #f1f5f9;padding-bottom:14px;">
                    <span style="font-size:1.25rem;">🔍</span>
                    <div class="card-title" style="font-size:1.1rem;font-weight:700;color:#0f172a;margin:0;">Search Engine Optimization (SEO)</div>
                </div>
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:600;color:#475569;margin-bottom:6px;display:block;">Custom Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Leave blank to use default page title" class="form-control">
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Title shown in browser tabs and search engine listings</div>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:600;color:#475569;margin-bottom:6px;display:block;">Meta Description</label>
                    <textarea name="meta_description" rows="2" placeholder="Concise summary for Google search engine results..." class="form-control">{{ old('meta_description') }}</textarea>
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Recommended length: 150–160 characters</div>
                </div>
            </div>

        </div>

        <!-- ─── Sidebar Column: Media & Settings ────────────────────────── -->
        <div style="display:flex;flex-direction:column;gap:20px;min-width:0;">

            <!-- Publishing Settings Card -->
            <div class="card" style="padding:22px;border-radius:12px;">
                <div class="card-header" style="display:flex;align-items:center;gap:10px;margin-bottom:16px;border-bottom:1px solid #f1f5f9;padding-bottom:12px;">
                    <span style="font-size:1.1rem;">🚀</span>
                    <div class="card-title" style="font-size:1rem;font-weight:700;color:#0f172a;margin:0;">Publish Settings</div>
                </div>

                <!-- Status -->
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">Page Status</label>
                    <select name="status" class="form-control" style="font-weight:600;">
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>🟢 Published (Visible)</option>
                        <option value="draft" {{ old('status', 'published') === 'draft' ? 'selected' : '' }}>🟡 Draft (Hidden)</option>
                    </select>
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:6px;line-height:1.4;">
                        Published pages automatically appear in the footer under <strong>Quick Links</strong>.
                    </div>
                </div>

                <!-- Sort Order -->
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">Footer Display Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $nextSortOrder ?? 0) }}" min="0" class="form-control">
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Lower numbers (0, 1, 2...) appear first in navigation</div>
                </div>
            </div>

            <!-- Translations Guide Card -->
            <div class="card" style="padding:20px;border-radius:12px;background:#f8fafc;">
                <div class="card-header" style="display:flex;align-items:center;gap:8px;margin-bottom:12px;border-bottom:1px solid #e2e8f0;padding-bottom:10px;">
                    <span style="font-size:1rem;">📋</span>
                    <div class="card-title" style="font-size:0.92rem;font-weight:700;color:#1e293b;margin:0;">Next Step: Translations</div>
                </div>
                <div style="font-size:0.8rem;color:#64748b;line-height:1.5;">
                    After saving, you can translate this page into Chinese (中文) and Malay (Bahasa Melayu) with full CKEditor support by clicking <strong>+ ZH</strong> and <strong>+ BM</strong> on the pages list!
                </div>
            </div>

            <!-- Submit Buttons Card -->
            <div class="card action-buttons-card" style="padding:20px;border-radius:12px;display:flex;flex-direction:column;gap:12px;">
                <button type="submit" class="btn btn-primary btn-block" style="padding:12px;font-size:0.95rem;font-weight:700;display:flex;align-items:center;justify-content:center;gap:8px;margin:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Create Page
                </button>
                <a href="{{ route('admin.policies.index') }}" class="btn btn-secondary btn-block" style="text-align:center;padding:10px;margin:0;">
                    Cancel &amp; Return
                </a>
            </div>

        </div>

    </div>
</form>

<!-- ═══════════════════════════════════════════════════════════════════════════ -->
<!-- INSERT CUSTOM HTML POPUP MODAL                                              -->
<!-- ═══════════════════════════════════════════════════════════════════════════ -->
<div id="insertHtmlModal" class="html-modal-backdrop" style="display:none" onclick="if(event.target===this) closeInsertHtmlModal()">
    <div class="html-modal-card">
        <div class="html-modal-header">
            <h3 style="margin:0;font-size:1rem;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px">
                <span>&lt;/&gt;</span> Insert Custom HTML Code
            </h3>
            <button type="button" class="html-modal-close" onclick="closeInsertHtmlModal()">✕</button>
        </div>
        <div style="padding:18px 20px">
            <p style="margin:0 0 10px 0;font-size:0.82rem;color:#64748b">
                Paste your custom HTML snippet below (tables, styled divs, buttons, banners, or embeds). It will be inserted into the CKEditor at your current cursor position:
            </p>
            <textarea id="customHtmlInput" rows="8" placeholder="<div class='custom-box'>&#10;  <h3>Heading</h3>&#10;  <p>Content...</p>&#10;</div>" class="form-control" style="font-family:monospace;font-size:0.84rem;line-height:1.5"></textarea>
        </div>
        <div class="html-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeInsertHtmlModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="executeInsertHtml()">Insert into Editor</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize CKEditor on pageContent
    let editorInstance = null;
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof CKEDITOR !== 'undefined') {
            editorInstance = CKEDITOR.replace('pageContent', {
                height: 420,
                extraPlugins: 'sourcearea,format,font,colorbutton,justify,table',
                removePlugins: 'exportpdf',
                allowedContent: true, // Allow all HTML tags without stripping
                toolbarGroups: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                    { name: 'links' },
                    { name: 'insert' },
                    { name: 'styles' },
                    { name: 'colors' },
                    { name: 'tools' }
                ]
            });
        }
    });

    // Auto-generate slug from title
    const titleInput = document.getElementById('pageTitle');
    const slugInput = document.getElementById('pageSlug');
    let manualSlug = false;

    slugInput?.addEventListener('input', () => {
        manualSlug = slugInput.value.trim().length > 0;
    });

    titleInput?.addEventListener('input', () => {
        if (!manualSlug && slugInput) {
            slugInput.value = titleInput.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-');
        }
    });

    // Insert Custom HTML Modal Handler
    let targetEditorId = 'pageContent';
    function openInsertHtmlModal(editorId) {
        targetEditorId = editorId;
        document.getElementById('customHtmlInput').value = '';
        document.getElementById('insertHtmlModal').style.display = 'flex';
        document.getElementById('customHtmlInput').focus();
    }

    function closeInsertHtmlModal() {
        document.getElementById('insertHtmlModal').style.display = 'none';
    }

    function executeInsertHtml() {
        const html = document.getElementById('customHtmlInput').value;
        if (html && CKEDITOR.instances[targetEditorId]) {
            CKEDITOR.instances[targetEditorId].insertHtml(html);
        }
        closeInsertHtmlModal();
    }

    // Ensure CKEditor syncs with form submit
    document.getElementById('policyForm')?.addEventListener('submit', () => {
        for (let instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    });
</script>
@endpush
