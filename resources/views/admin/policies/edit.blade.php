@extends('layouts.admin')
@section('title', 'Edit Page: ' . $policy->title . ' — MST Admin')

@push('styles')
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<style>
    .lang-tab-btn {
        background: transparent;
        border: 1px solid transparent;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .lang-tab-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .lang-tab-btn.active {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
        font-weight: 700;
    }

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
        <h1 class="admin-page-title" style="margin:0;font-size:clamp(1.4rem,3vw,1.85rem);">Edit Page: {{ $policy->title }}</h1>
        <p class="text-sm text-muted" style="margin:4px 0 0;">Update policy terms, multi-language content, search engine SEO tags, and publishing status</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => $policy->slug]) }}" target="_blank" class="btn btn-secondary" style="display:inline-flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
                <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg>
            <span>View Live</span>
        </a>
        <a href="{{ route('admin.policies.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" form="policyForm" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:8px;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Save Changes
        </button>
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

<form id="policyForm" action="{{ route('admin.policies.update', $policy) }}" method="POST">
    @csrf
    @method('PUT')

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
                           value="{{ old('title', $policy->title) }}" placeholder="e.g. Privacy Policy, Terms & Conditions, Wholesale FAQ" required autofocus>
                    @error('title')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <!-- URL Slug -->
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">
                        URL Slug <span class="required" style="color:#ef4444;">*</span>
                    </label>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:0.84rem;color:#64748b;background:#f1f5f9;border:1px solid #cbd5e1;padding:8px 14px;border-radius:6px;font-weight:600;">/en/policy/</span>
                        <input type="text" name="slug" id="pageSlug" class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}"
                               value="{{ old('slug', $policy->slug) }}" placeholder="privacy-policy" required style="flex:1;">
                    </div>
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Public URL key for this policy page</div>
                    @error('slug')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <!-- Summary / Subtitle -->
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">
                        Brief Summary / Hero Subtitle (Optional)
                    </label>
                    <textarea name="summary" class="form-control" rows="2"
                              placeholder="Short 1-2 sentence description that appears below the hero title on the policy page...">{{ old('summary', $policy->summary) }}</textarea>
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Displayed in header banner of the public page</div>
                </div>
            </div>

            <!-- Page Content Editor Card with Multilingual Tabs -->
            <div class="card" style="padding:24px;border-radius:12px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;border-bottom:1px solid #f1f5f9;padding-bottom:14px;flex-wrap:wrap;gap:10px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:1.25rem;">✍️</span>
                        <div class="card-title" style="font-size:1.1rem;font-weight:700;color:#0f172a;margin:0;">Page Content Editor</div>
                    </div>
                    <!-- Language Selection Tabs -->
                    <div style="display:flex;gap:6px;background:#f8fafc;padding:4px;border-radius:10px;border:1px solid #e2e8f0;">
                        <button type="button" class="lang-tab-btn active" onclick="switchLangTab('en')" id="tabBtn_en">
                            <span>🇬🇧</span> English
                        </button>
                        <button type="button" class="lang-tab-btn" onclick="switchLangTab('zh')" id="tabBtn_zh">
                            <span>🇨🇳</span> 中文 @if(!empty($policy->content_zh)) <span style="font-size:0.75rem;color:#059669;">✓</span> @endif
                        </button>
                        <button type="button" class="lang-tab-btn" onclick="switchLangTab('bm')" id="tabBtn_bm">
                            <span>🇲🇾</span> Bahasa Melayu @if(!empty($policy->content_bm)) <span style="font-size:0.75rem;color:#059669;">✓</span> @endif
                        </button>
                    </div>
                </div>

                <!-- English Content Panel -->
                <div id="langTab_en" class="lang-content-panel">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                        <span style="font-size:0.84rem;font-weight:700;color:#1d4ed8;display:flex;align-items:center;gap:6px;">
                            <span>🇬🇧</span> English Content (Primary / Default)
                        </span>
                        <button type="button" class="insert-html-btn" onclick="openInsertHtmlModal('content_en')">
                            <strong>&lt;/&gt;</strong> Insert Custom HTML
                        </button>
                    </div>
                    <textarea name="content" id="content_en" required>{{ old('content', $policy->content) }}</textarea>
                </div>

                <!-- Chinese Content Panel -->
                <div id="langTab_zh" class="lang-content-panel" style="display:none;">
                    <div class="form-group mb-4" style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0;">
                        <label class="form-label" style="color:#dc2626;font-weight:700;margin-bottom:6px;display:block;">
                            🇨🇳 Chinese Page Title (Optional)
                        </label>
                        <input type="text" name="title_zh" value="{{ old('title_zh', $policy->title_zh) }}" placeholder="e.g. 隐私政策 / 服务条款 / 批发常见问题" class="form-control" style="font-weight:600;">
                        <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Displayed when Simplified Chinese is active</div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                        <span style="font-size:0.84rem;font-weight:700;color:#dc2626;display:flex;align-items:center;gap:6px;">
                            <span>🇨🇳</span> Chinese Content (简体中文)
                        </span>
                        <button type="button" class="insert-html-btn" onclick="openInsertHtmlModal('content_zh')">
                            <strong>&lt;/&gt;</strong> Insert Custom HTML
                        </button>
                    </div>
                    <textarea name="content_zh" id="content_zh">{{ old('content_zh', $policy->content_zh) }}</textarea>
                </div>

                <!-- Malay Content Panel -->
                <div id="langTab_bm" class="lang-content-panel" style="display:none;">
                    <div class="form-group mb-4" style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0;">
                        <label class="form-label" style="color:#059669;font-weight:700;margin-bottom:6px;display:block;">
                            🇲🇾 Malay Page Title (Optional)
                        </label>
                        <input type="text" name="title_bm" value="{{ old('title_bm', $policy->title_bm) }}" placeholder="e.g. Dasar Privasi / Terma & Syarat / Soalan Lazim Borong" class="form-control" style="font-weight:600;">
                        <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Displayed when Bahasa Melayu is active</div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                        <span style="font-size:0.84rem;font-weight:700;color:#059669;display:flex;align-items:center;gap:6px;">
                            <span>🇲🇾</span> Malay Content (Bahasa Melayu)
                        </span>
                        <button type="button" class="insert-html-btn" onclick="openInsertHtmlModal('content_bm')">
                            <strong>&lt;/&gt;</strong> Insert Custom HTML
                        </button>
                    </div>
                    <textarea name="content_bm" id="content_bm">{{ old('content_bm', $policy->content_bm) }}</textarea>
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
                    <input type="text" name="meta_title" value="{{ old('meta_title', $policy->meta_title) }}" placeholder="Leave blank to use default page title" class="form-control">
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Title shown in browser tabs and search engine listings</div>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:600;color:#475569;margin-bottom:6px;display:block;">Meta Description</label>
                    <textarea name="meta_description" rows="2" placeholder="Concise summary for Google search engine results..." class="form-control">{{ old('meta_description', $policy->meta_description) }}</textarea>
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
                        <option value="published" {{ old('status', $policy->status) === 'published' ? 'selected' : '' }}>🟢 Published (Visible)</option>
                        <option value="draft" {{ old('status', $policy->status) === 'draft' ? 'selected' : '' }}>🟡 Draft (Hidden)</option>
                    </select>
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:6px;line-height:1.4;">
                        Published pages automatically link in the footer under <strong>Quick Links</strong>.
                    </div>
                </div>

                <!-- Sort Order -->
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">Footer Display Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $policy->sort_order) }}" min="0" class="form-control">
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Lower numbers (0, 1, 2...) appear first in navigation</div>
                </div>
            </div>

            <!-- Page Meta Card -->
            <div class="card" style="padding:20px;border-radius:12px;background:#f8fafc;">
                <div class="card-header" style="display:flex;align-items:center;gap:8px;margin-bottom:12px;border-bottom:1px solid #e2e8f0;padding-bottom:10px;">
                    <span style="font-size:1rem;">ℹ️</span>
                    <div class="card-title" style="font-size:0.92rem;font-weight:700;color:#1e293b;margin:0;">Page Meta Details</div>
                </div>
                <div style="font-size:0.8rem;color:#64748b;display:flex;flex-direction:column;gap:8px;">
                    <div><strong style="color:#334155;">Page ID:</strong> #{{ $policy->id }}</div>
                    <div><strong style="color:#334155;">Created:</strong> {{ $policy->created_at->format('M d, Y H:i') }}</div>
                    <div><strong style="color:#334155;">Last Modified:</strong> {{ $policy->updated_at->format('M d, Y H:i') }}</div>
                    <div>
                        <strong style="color:#334155;">Live URL:</strong><br>
                        <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => $policy->slug]) }}" target="_blank" style="color:#1d4ed8;word-break:break-all;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:4px;margin-top:2px;">
                            <span>/{{ app()->getLocale() }}/policy/{{ $policy->slug }}</span>
                            <span>↗</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons Card -->
            <div class="card action-buttons-card" style="padding:20px;border-radius:12px;display:flex;flex-direction:column;gap:12px;">
                <button type="submit" class="btn btn-primary btn-block" style="padding:12px;font-size:0.95rem;font-weight:700;display:flex;align-items:center;justify-content:center;gap:8px;margin:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Save Changes
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
@endsection

@push('scripts')
<script>
    const ckConfig = {
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

        document.getElementById(`tabBtn_${lang}`)?.classList.add('active');
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
    document.getElementById('policyForm')?.addEventListener('submit', () => {
        for (let instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    });
</script>
@endpush
