@extends('layouts.admin')

@section('title', 'Edit Page — ' . $policy->title)

@section('content')
<div style="max-width:1000px;margin:0 auto">
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
                    Update content and translations. Changes take effect immediately across the website and footer.
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
                        📝 Page Information
                    </h3>

                    <!-- Title -->
                    <div style="margin-bottom:16px">
                        <label style="display:block;font-size:0.84rem;font-weight:700;color:#334155;margin-bottom:6px">
                            Page Title <span style="color:#ef4444">*</span>
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
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;border-bottom:1px solid #e2e8f0;padding-bottom:8px">
                            <label style="font-size:0.88rem;font-weight:700;color:#0f172a;margin:0">
                                Page Content <span style="color:#ef4444">*</span>
                            </label>
                            <div style="display:flex;gap:4px">
                                <button type="button" class="lang-tab-btn active" onclick="switchLangTab('en')" id="tabBtn_en">🇺🇸 English</button>
                                <button type="button" class="lang-tab-btn" onclick="switchLangTab('zh')" id="tabBtn_zh">🇨🇳 中文</button>
                                <button type="button" class="lang-tab-btn" onclick="switchLangTab('bm')" id="tabBtn_bm">🇲🇾 Bahasa Melayu</button>
                            </div>
                        </div>

                        <!-- Editor Toolbar Helper Buttons -->
                        <div style="display:flex;gap:6px;flex-wrap:wrap;background:#f8fafc;border:1px solid #e2e8f0;border-bottom:none;border-top-left-radius:8px;border-top-right-radius:8px;padding:8px 10px">
                            <button type="button" class="editor-btn" onclick="insertTag('h2')" title="Section Heading">H2</button>
                            <button type="button" class="editor-btn" onclick="insertTag('h3')" title="Subheading">H3</button>
                            <button type="button" class="editor-btn" onclick="insertTag('strong')" title="Bold"><strong>B</strong></button>
                            <button type="button" class="editor-btn" onclick="insertTag('em')" title="Italic"><em>I</em></button>
                            <button type="button" class="editor-btn" onclick="insertTag('p')" title="Paragraph">&lt;p&gt;</button>
                            <button type="button" class="editor-btn" onclick="insertTag('ul')" title="Bullet List">• List</button>
                            <button type="button" class="editor-btn" onclick="insertTag('li')" title="List Item">List Item</button>
                        </div>

                        <!-- English Tab -->
                        <div id="langTab_en" class="lang-content-panel">
                            <textarea name="content" id="content_en" rows="18" required placeholder="Write your page content here in HTML format..." class="form-control" style="border-top-left-radius:0;border-top-right-radius:0;font-family:monospace;font-size:0.86rem;line-height:1.6">{{ old('content', $policy->content) }}</textarea>
                        </div>

                        <!-- Chinese Tab -->
                        <div id="langTab_zh" class="lang-content-panel" style="display:none">
                            <div style="margin-bottom:12px">
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:#64748b;margin-bottom:4px">Chinese Page Title (Optional)</label>
                                <input type="text" name="title_zh" value="{{ old('title_zh', $policy->title_zh) }}" placeholder="e.g. 隐私政策 / 服务条款" class="form-control">
                            </div>
                            <textarea name="content_zh" id="content_zh" rows="16" placeholder="在此输入中文页面内容..." class="form-control" style="border-top-left-radius:0;border-top-right-radius:0;font-family:monospace;font-size:0.86rem;line-height:1.6">{{ old('content_zh', $policy->content_zh) }}</textarea>
                        </div>

                        <!-- Malay Tab -->
                        <div id="langTab_bm" class="lang-content-panel" style="display:none">
                            <div style="margin-bottom:12px">
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:#64748b;margin-bottom:4px">Malay Page Title (Optional)</label>
                                <input type="text" name="title_bm" value="{{ old('title_bm', $policy->title_bm) }}" placeholder="e.g. Dasar Privasi / Terma & Syarat" class="form-control">
                            </div>
                            <textarea name="content_bm" id="content_bm" rows="16" placeholder="Masukkan kandungan halaman dalam Bahasa Melayu di sini..." class="form-control" style="border-top-left-radius:0;border-top-right-radius:0;font-family:monospace;font-size:0.86rem;line-height:1.6">{{ old('content_bm', $policy->content_bm) }}</textarea>
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
    .editor-btn {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 3px 8px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        transition: all 0.1s ease;
    }
    .editor-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
</style>

<script>
    // Language switcher
    let activeLang = 'en';
    function switchLangTab(lang) {
        activeLang = lang;
        document.querySelectorAll('.lang-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.lang-content-panel').forEach(p => p.style.display = 'none');

        document.getElementById(`tabBtn_${lang}`).classList.add('active');
        document.getElementById(`langTab_${lang}`).style.display = 'block';
    }

    // Insert HTML formatting helper into active textarea
    function insertTag(tag) {
        const textarea = document.getElementById(`content_${activeLang}`);
        if (!textarea) return;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end);
        let replacement = '';

        if (tag === 'ul') {
            replacement = `\n<ul>\n  <li>${selectedText || 'Item 1'}</li>\n  <li>Item 2</li>\n</ul>\n`;
        } else if (tag === 'li') {
            replacement = `<li>${selectedText || 'List item text'}</li>`;
        } else {
            replacement = `<${tag}>${selectedText || 'Text here'}</${tag}>`;
        }

        textarea.setRangeText(replacement, start, end, 'end');
        textarea.focus();
    }

    // Check URL parameters on load for ?lang=zh or ?lang=bm or hash
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const langParam = urlParams.get('lang') || window.location.hash.replace('#', '');
        if (langParam && ['zh', 'bm', 'en'].includes(langParam)) {
            switchLangTab(langParam);
        }
    });
</script>
@endsection
