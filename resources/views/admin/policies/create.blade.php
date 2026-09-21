@extends('layouts.admin')

@section('title', 'Add New Policy / Page')

@push('styles')
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
@endpush

@section('content')
<div style="max-width:1050px;margin:0 auto">
    <!-- Breadcrumb & Header -->
    <div style="margin-bottom:24px">
        <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;color:#64748b;margin-bottom:8px">
            <a href="{{ route('admin.policies.index') }}" style="color:#2563eb;text-decoration:none;font-weight:600">Policies & Pages</a>
            <span>/</span>
            <span>Create New Page</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <h1 class="admin-page-title" style="margin:0 0 4px 0;font-size:1.4rem">✨ Add New Page (English Default)</h1>
                <p style="margin:0;font-size:0.85rem;color:#64748b">
                    Compose your page using the full visual <strong>CKEditor</strong> with HTML Source editing and Insert HTML tools.
                </p>
            </div>
            <a href="{{ route('admin.policies.index') }}" class="btn btn-secondary">
                ← Back to List
            </a>
        </div>
    </div>

    <!-- Notification / Workflow Tip -->
    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:12px">
        <div style="font-size:1.4rem">🌐</div>
        <div style="font-size:0.84rem;color:#1e40af">
            <strong>English First Workflow:</strong> Create your primary English page here using CKEditor. Once saved, click the <strong>+ ZH</strong> or <strong>+ BM</strong> buttons on the pages list to add Chinese & Malay translations!
        </div>
    </div>

    @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-bottom:20px;color:#b91c1c;font-size:0.85rem">
            <div style="font-weight:700;margin-bottom:6px">Please resolve the following errors:</div>
            <ul style="margin:0;padding-left:20px">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.policies.store') }}" id="policyForm">
        @csrf

        <div style="display:grid;grid-template-columns:2.5fr 1fr;gap:24px;align-items:start">
            <!-- Left Column: Content with CKEditor -->
            <div style="display:flex;flex-direction:column;gap:20px">
                
                <!-- Main Card -->
                <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:22px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;border-bottom:1px solid #f1f5f9;padding-bottom:10px">
                        <h3 style="margin:0;font-size:1rem;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px">
                            📝 Page Content (English)
                        </h3>
                        <span style="font-size:0.75rem;font-weight:700;background:#eff6ff;color:#2563eb;padding:3px 8px;border-radius:6px;border:1px solid #bfdbfe">
                            🇺🇸 Default Language
                        </span>
                    </div>

                    <!-- Title -->
                    <div style="margin-bottom:16px">
                        <label style="display:block;font-size:0.84rem;font-weight:700;color:#334155;margin-bottom:6px">
                            Page Title (English) <span style="color:#ef4444">*</span>
                        </label>
                        <input type="text" name="title" id="pageTitle" value="{{ old('title') }}" required placeholder="e.g. Privacy Policy, Terms & Conditions, Wholesale FAQ" class="form-control" style="font-size:0.95rem;font-weight:600">
                    </div>

                    <!-- Slug -->
                    <div style="margin-bottom:16px">
                        <label style="display:block;font-size:0.84rem;font-weight:700;color:#334155;margin-bottom:6px">
                            URL Slug
                        </label>
                        <div style="display:flex;align-items:center;gap:6px">
                            <span style="font-size:0.84rem;color:#64748b;background:#f1f5f9;border:1px solid #cbd5e1;padding:8px 12px;border-radius:6px">/en/policy/</span>
                            <input type="text" name="slug" id="pageSlug" value="{{ old('slug') }}" placeholder="privacy-policy" class="form-control" style="flex:1">
                        </div>
                        <span style="font-size:0.75rem;color:#94a3b8;margin-top:4px;display:block">Auto-generated from title if left blank.</span>
                    </div>

                    <!-- Summary / Excerpt -->
                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:0.84rem;font-weight:700;color:#334155;margin-bottom:6px">
                            Brief Summary / Subtitle (Optional)
                        </label>
                        <textarea name="summary" rows="2" placeholder="Short description of this policy shown under the title on the page..." class="form-control">{{ old('summary') }}</textarea>
                    </div>

                    <!-- CKEditor Content Area -->
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;flex-wrap:wrap;gap:8px">
                            <label style="font-size:0.88rem;font-weight:700;color:#0f172a;margin:0">
                                Page Content <span style="color:#ef4444">*</span>
                            </label>
                            <div style="display:flex;align-items:center;gap:6px">
                                <button type="button" class="insert-html-btn" onclick="openInsertHtmlModal('pageContent')">
                                    <strong>&lt;/&gt;</strong> Insert Custom HTML
                                </button>
                            </div>
                        </div>

                        <textarea name="content" id="pageContent" required>{{ old('content', '<h2>1. Overview</h2><p>Write your policy content here...</p>') }}</textarea>
                    </div>
                </div>

                <!-- SEO Settings Card -->
                <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
                    <h3 style="margin:0 0 14px 0;font-size:0.95rem;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px">
                        🔍 Search Engine Optimization (SEO)
                    </h3>
                    <div style="margin-bottom:14px">
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:#475569;margin-bottom:5px">Custom Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Leave blank to use page title" class="form-control">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:#475569;margin-bottom:5px">Meta Description</label>
                        <textarea name="meta_description" rows="2" placeholder="Short description for Google search results..." class="form-control">{{ old('meta_description') }}</textarea>
                    </div>
                </div>

            </div>

            <!-- Right Column: Publishing Controls -->
            <div style="display:flex;flex-direction:column;gap:20px">
                <!-- Publish Card -->
                <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
                    <h3 style="margin:0 0 14px 0;font-size:0.95rem;font-weight:700;color:#0f172a">
                        🚀 Publish Settings
                    </h3>

                    <!-- Status -->
                    <div style="margin-bottom:16px">
                        <label style="display:block;font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px">Status</label>
                        <select name="status" class="form-control" style="font-weight:600">
                            <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>🟢 Published (Visible in Footer)</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>🟡 Draft (Hidden)</option>
                        </select>
                        <div style="font-size:0.75rem;color:#64748b;margin-top:6px;line-height:1.4">
                            Published pages appear automatically in the footer under <strong>Quick Links</strong>.
                        </div>
                    </div>

                    <!-- Sort Order -->
                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px">Footer Display Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $nextSortOrder) }}" min="0" class="form-control">
                        <div style="font-size:0.75rem;color:#94a3b8;margin-top:4px">Lower numbers appear first.</div>
                    </div>

                    <!-- Submit Button -->
                    <div style="display:flex;flex-direction:column;gap:10px">
                        <button type="submit" class="btn btn-primary" style="width:100%;padding:10px;font-weight:700;font-size:0.9rem">
                            💾 Save & Publish Page
                        </button>
                        <a href="{{ route('admin.policies.index') }}" class="btn btn-secondary" style="width:100%">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Next Steps Card -->
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px;font-size:0.8rem;color:#64748b;line-height:1.5">
                    <div style="font-weight:700;color:#334155;margin-bottom:6px">📋 Next Step: Adding Translations</div>
                    After saving, you can translate this page into Chinese (中文) and Malay (Bahasa Melayu) with full CKEditor support by clicking <strong>+ ZH</strong> and <strong>+ BM</strong> on the pages list!
                </div>
            </div>
        </div>
    </form>
</div>

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

<style>
    .insert-html-btn {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        color: #1e40af;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.12s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
        max-width: 600px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        overflow: hidden;
    }
    .html-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .html-modal-close {
        border: none;
        background: transparent;
        font-size: 1.1rem;
        color: #94a3b8;
        cursor: pointer;
    }
    .html-modal-close:hover {
        color: #ef4444;
    }
    .html-modal-footer {
        padding: 12px 20px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }
</style>

<script>
    // Initialize CKEditor on pageContent
    let editorInstance = null;
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof CKEDITOR !== 'undefined') {
            editorInstance = CKEDITOR.replace('pageContent', {
                height: 380,
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

    slugInput.addEventListener('input', () => {
        manualSlug = slugInput.value.trim().length > 0;
    });

    titleInput.addEventListener('input', () => {
        if (!manualSlug) {
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
    document.getElementById('policyForm').addEventListener('submit', () => {
        for (let instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    });
</script>
@endsection
