@extends('layouts.admin')

@section('title', 'Edit Page — ' . $policy->title)

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
            <span>Edit Page</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <h1 class="admin-page-title" style="margin:0 0 4px 0;font-size:1.4rem">✏️ Edit: {{ $policy->title }}</h1>
                <p style="margin:0;font-size:0.85rem;color:#64748b">
                    Edit content in visual <strong>CKEditor</strong> mode or HTML source mode with live translation tabs.
                </p>
            </div>
            <div style="display:flex;gap:8px">
                <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => $policy->slug]) }}" target="_blank" class="btn btn-secondary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                    <span>View Live</span>
                </a>
                <a href="{{ route('admin.policies.index') }}" class="btn btn-secondary">
                    ← Back to List
                </a>
            </div>
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

    <form method="POST" action="{{ route('admin.policies.update', $policy) }}" id="policyForm">
        @csrf
        @method('PUT')

        <div style="display:grid;grid-template-columns:2.5fr 1fr;gap:24px;align-items:start">
            <!-- Left Column: Main Content -->
            <div style="display:flex;flex-direction:column;gap:20px">
                
                <!-- Main Card -->
                <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:22px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
                    <h3 style="margin:0 0 16px 0;font-size:1rem;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px">
                        📝 Page Information & Content
                    </h3>

                    <!-- Title -->
                    <div style="margin-bottom:16px">
                        <label style="display:block;font-size:0.84rem;font-weight:700;color:#334155;margin-bottom:6px">
                            Page Title (English) <span style="color:#ef4444">*</span>
                        </label>
                        <input type="text" name="title" id="pageTitle" value="{{ old('title', $policy->title) }}" required placeholder="e.g. Privacy Policy" class="form-control" style="font-size:0.95rem;font-weight:600">
                    </div>

                    <!-- Slug -->
                    <div style="margin-bottom:16px">
                        <label style="display:block;font-size:0.84rem;font-weight:700;color:#334155;margin-bottom:6px">
                            URL Slug <span style="color:#ef4444">*</span>
                        </label>
                        <div style="display:flex;align-items:center;gap:6px">
                            <span style="font-size:0.84rem;color:#64748b;background:#f1f5f9;border:1px solid #cbd5e1;padding:8px 12px;border-radius:6px">/en/policy/</span>
                            <input type="text" name="slug" id="pageSlug" value="{{ old('slug', $policy->slug) }}" required placeholder="privacy-policy" class="form-control" style="flex:1">
                        </div>
                    </div>

                    <!-- Summary / Excerpt -->
                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:0.84rem;font-weight:700;color:#334155;margin-bottom:6px">
                            Brief Summary / Subtitle (Optional)
                        </label>
                        <textarea name="summary" rows="2" placeholder="Short description of this policy that appears below the hero title on the page..." class="form-control">{{ old('summary', $policy->summary) }}</textarea>
                    </div>

                    <!-- Language Tabs for Content -->
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;border-bottom:1px solid #e2e8f0;padding-bottom:10px;flex-wrap:wrap;gap:8px">
                            <label style="font-size:0.88rem;font-weight:700;color:#0f172a;margin:0">
                                Page Content Editor
                            </label>
                            <div style="display:flex;gap:4px">
                                <button type="button" class="lang-tab-btn active" onclick="switchLangTab('en')" id="tabBtn_en">🇺🇸 English</button>
                                <button type="button" class="lang-tab-btn" onclick="switchLangTab('zh')" id="tabBtn_zh">
                                    🇨🇳 中文 @if(!empty($policy->content_zh)) ✓ @endif
                                </button>
                                <button type="button" class="lang-tab-btn" onclick="switchLangTab('bm')" id="tabBtn_bm">
                                    🇲🇾 Bahasa Melayu @if(!empty($policy->content_bm)) ✓ @endif
                                </button>
                            </div>
                        </div>

                        <!-- English Tab -->
                        <div id="langTab_en" class="lang-content-panel">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                                <span style="font-size:0.8rem;font-weight:700;color:#2563eb">🇺🇸 English Content (Default)</span>
                                <button type="button" class="insert-html-btn" onclick="openInsertHtmlModal('content_en')">
                                    <strong>&lt;/&gt;</strong> Insert Custom HTML
                                </button>
                            </div>
                            <textarea name="content" id="content_en" required>{{ old('content', $policy->content) }}</textarea>
                        </div>

                        <!-- Chinese Tab -->
                        <div id="langTab_zh" class="lang-content-panel" style="display:none">
                            <div style="margin-bottom:14px">
                                <label style="display:block;font-size:0.82rem;font-weight:700;color:#475569;margin-bottom:5px">Chinese Page Title (Optional)</label>
                                <input type="text" name="title_zh" value="{{ old('title_zh', $policy->title_zh) }}" placeholder="e.g. 隐私政策 / 服务条款" class="form-control" style="font-weight:600">
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                                <span style="font-size:0.8rem;font-weight:700;color:#059669">🇨🇳 Chinese Content (中文)</span>
                                <button type="button" class="insert-html-btn" onclick="openInsertHtmlModal('content_zh')">
                                    <strong>&lt;/&gt;</strong> Insert Custom HTML
                                </button>
                            </div>
                            <textarea name="content_zh" id="content_zh">{{ old('content_zh', $policy->content_zh) }}</textarea>
                        </div>

                        <!-- Malay Tab -->
                        <div id="langTab_bm" class="lang-content-panel" style="display:none">
                            <div style="margin-bottom:14px">
                                <label style="display:block;font-size:0.82rem;font-weight:700;color:#475569;margin-bottom:5px">Malay Page Title (Optional)</label>
                                <input type="text" name="title_bm" value="{{ old('title_bm', $policy->title_bm) }}" placeholder="e.g. Dasar Privasi / Terma & Syarat" class="form-control" style="font-weight:600">
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                                <span style="font-size:0.8rem;font-weight:700;color:#d97706">🇲🇾 Malay Content (Bahasa Melayu)</span>
                                <button type="button" class="insert-html-btn" onclick="openInsertHtmlModal('content_bm')">
                                    <strong>&lt;/&gt;</strong> Insert Custom HTML
                                </button>
                            </div>
                            <textarea name="content_bm" id="content_bm">{{ old('content_bm', $policy->content_bm) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings Card -->
                <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:22px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
                    <h3 style="margin:0 0 14px 0;font-size:0.95rem;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px">
                        🔍 Search Engine Optimization (SEO)
                    </h3>
                    <div style="margin-bottom:14px">
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:#475569;margin-bottom:5px">Custom Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $policy->meta_title) }}" placeholder="Leave blank to use page title" class="form-control">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:#475569;margin-bottom:5px">Meta Description</label>
                        <textarea name="meta_description" rows="2" placeholder="Short description for Google search results..." class="form-control">{{ old('meta_description', $policy->meta_description) }}</textarea>
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
                            <option value="published" {{ old('status', $policy->status) === 'published' ? 'selected' : '' }}>🟢 Published (Visible in Footer)</option>
                            <option value="draft" {{ old('status', $policy->status) === 'draft' ? 'selected' : '' }}>🟡 Draft (Hidden)</option>
                        </select>
                        <div style="font-size:0.75rem;color:#64748b;margin-top:6px;line-height:1.4">
                            Published pages appear automatically in the footer under <strong>Quick Links</strong>.
                        </div>
                    </div>

                    <!-- Sort Order -->
                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px">Footer Display Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $policy->sort_order) }}" min="0" class="form-control">
                        <div style="font-size:0.75rem;color:#94a3b8;margin-top:4px">Lower numbers appear first.</div>
                    </div>

                    <!-- Submit Button -->
                    <div style="display:flex;flex-direction:column;gap:10px">
                        <button type="submit" class="btn btn-primary" style="width:100%;padding:10px;font-weight:700;font-size:0.9rem">
                            💾 Update Page
                        </button>
                        <a href="{{ route('admin.policies.index') }}" class="btn btn-secondary" style="width:100%">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Page Meta Details -->
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px;font-size:0.8rem;color:#64748b">
                    <div style="font-weight:700;color:#334155;margin-bottom:8px">ℹ️ Page Meta</div>
                    <div style="margin-bottom:4px"><strong>Created:</strong> {{ $policy->created_at->format('M d, Y H:i') }}</div>
                    <div style="margin-bottom:4px"><strong>Last Modified:</strong> {{ $policy->updated_at->format('M d, Y H:i') }}</div>
                    <div><strong>Live URL:</strong> <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => $policy->slug]) }}" target="_blank" style="color:#2563eb;word-break:break-all">/{{ app()->getLocale() }}/policy/{{ $policy->slug }}</a></div>
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
                Paste your custom HTML snippet below. It will be inserted into the active CKEditor at your current cursor position:
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
    .lang-tab-btn {
        background: transparent;
        border: 1px solid transparent;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.12s ease;
    }
    .lang-tab-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .lang-tab-btn.active {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    .insert-html-btn {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        color: #1e40af;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.75rem;
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
    const ckConfig = {
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
    };

    // Initialize CKEditor for all language content textareas
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('content_en', ckConfig);
            CKEDITOR.replace('content_zh', ckConfig);
            CKEDITOR.replace('content_bm', ckConfig);
        }

        // Auto switch tab if URL has ?lang=zh or ?lang=bm
        const urlParams = new URLSearchParams(window.location.search);
        const langParam = urlParams.get('lang') || window.location.hash.replace('#', '');
        if (langParam && ['zh', 'bm', 'en'].includes(langParam)) {
            switchLangTab(langParam);
        }
    });

    // Language tab switcher
    let activeLang = 'en';
    function switchLangTab(lang) {
        activeLang = lang;
        document.querySelectorAll('.lang-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.lang-content-panel').forEach(p => p.style.display = 'none');

        document.getElementById(`tabBtn_${lang}`).classList.add('active');
        document.getElementById(`langTab_${lang}`).style.display = 'block';
    }

    // Insert Custom HTML Modal Handler
    let targetEditorId = 'content_en';
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

    // Ensure all CKEditor instances update their underlying textarea on form submit
    document.getElementById('policyForm').addEventListener('submit', () => {
        for (let instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    });
</script>
@endsection
