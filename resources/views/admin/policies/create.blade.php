@extends('layouts.admin')

@section('title', 'Add New Policy / Page')

@section('content')
<div style="max-width:960px;margin:0 auto">
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
                    Enter the page content in <strong>English</strong>. You can add <strong>Chinese (ZH)</strong> and <strong>Malay (BM)</strong> translations directly from the list after saving.
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
            <strong>English First Workflow:</strong> Fill in your primary English title and content here. Once published, click the <strong>+ ZH</strong> or <strong>+ BM</strong> buttons on the pages table to translate with a side-by-side reference!
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
            <!-- Left Column: English Content -->
            <div style="display:flex;flex-direction:column;gap:20px">
                
                <!-- Main Card -->
                <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:22px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;border-bottom:1px solid #f1f5f9;padding-bottom:10px">
                        <h3 style="margin:0;font-size:1rem;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px">
                            📝 Page Details (English)
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

                    <!-- Page Content (English) -->
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                            <label style="font-size:0.88rem;font-weight:700;color:#0f172a;margin:0">
                                Page Content (English HTML) <span style="color:#ef4444">*</span>
                            </label>
                            <div style="display:flex;gap:4px">
                                <button type="button" class="editor-btn" onclick="insertTag('h2')" title="Section Heading">H2</button>
                                <button type="button" class="editor-btn" onclick="insertTag('h3')" title="Subheading">H3</button>
                                <button type="button" class="editor-btn" onclick="insertTag('strong')" title="Bold"><strong>B</strong></button>
                                <button type="button" class="editor-btn" onclick="insertTag('em')" title="Italic"><em>I</em></button>
                                <button type="button" class="editor-btn" onclick="insertTag('p')" title="Paragraph">&lt;p&gt;</button>
                                <button type="button" class="editor-btn" onclick="insertTag('ul')" title="Bullet List">• List</button>
                            </div>
                        </div>
                        <textarea name="content" id="pageContent" rows="18" required placeholder="<h2>1. Overview</h2>&#10;<p>Write your policy text here...</p>" class="form-control" style="font-family:monospace;font-size:0.86rem;line-height:1.6">{{ old('content') }}</textarea>
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
                    After creating this page, you can translate it into Chinese (中文) and Malay (Bahasa Melayu) by clicking the <strong>+ ZH</strong> and <strong>+ BM</strong> buttons on the pages list!
                </div>
            </div>
        </div>
    </form>
</div>

<style>
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

    // Insert HTML formatting helper into textarea
    function insertTag(tag) {
        const textarea = document.getElementById('pageContent');
        if (!textarea) return;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end);
        let replacement = '';

        if (tag === 'ul') {
            replacement = `\n<ul>\n  <li>${selectedText || 'Item 1'}</li>\n  <li>Item 2</li>\n</ul>\n`;
        } else {
            replacement = `<${tag}>${selectedText || 'Text here'}</${tag}>`;
        }

        textarea.setRangeText(replacement, start, end, 'end');
        textarea.focus();
    }
</script>
@endsection
