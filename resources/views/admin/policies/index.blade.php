@extends('layouts.admin')

@section('title', 'Policies & Dynamic Pages')

@push('styles')
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
@endpush

@section('content')
<div style="max-width:1200px;margin:0 auto">
    <!-- Header -->
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px">
        <div>
            <h1 class="admin-page-title" style="margin:0 0 6px 0;font-size:1.45rem">📜 Policies & Dynamic Pages</h1>
            <p style="margin:0;font-size:0.85rem;color:#64748b">
                Add pages in <strong>English (Default)</strong>, and click any language badge (<strong>ZH</strong> / <strong>BM</strong>) to open the <strong>CKEditor Translation Modal</strong>.
            </p>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
            <a href="{{ route('admin.policies.create') }}" class="btn btn-primary" style="padding:9px 18px;font-weight:600">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Add New Page (English)</span>
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:24px">
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
            <div style="width:44px;height:44px;border-radius:10px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:1.25rem">
                📄
            </div>
            <div>
                <div style="font-size:1.35rem;font-weight:800;color:#0f172a;line-height:1.2">{{ $stats['total'] }}</div>
                <div style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em">Total Pages</div>
            </div>
        </div>
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
            <div style="width:44px;height:44px;border-radius:10px;background:#ecfdf5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:1.25rem">
                🟢
            </div>
            <div>
                <div style="font-size:1.35rem;font-weight:800;color:#059669;line-height:1.2">{{ $stats['published'] }}</div>
                <div style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em">Published (Live in Footer)</div>
            </div>
        </div>
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
            <div style="width:44px;height:44px;border-radius:10px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:1.25rem">
                🟡
            </div>
            <div>
                <div style="font-size:1.35rem;font-weight:800;color:#d97706;line-height:1.2">{{ $stats['draft'] }}</div>
                <div style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em">Drafts (Hidden)</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;margin-bottom:20px;box-shadow:0 1px 3px rgba(0,0,0,0.02)">
        <form method="GET" action="{{ route('admin.policies.index') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
            <div class="search-input-wrap" style="flex:1;min-width:240px">
                <span class="search-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search pages by title or slug..." class="form-control">
            </div>

            <div style="display:flex;align-items:center;gap:8px">
                <select name="status" class="form-control" style="width:auto;padding-right:32px" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Only</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                </select>

                <button type="submit" class="btn btn-secondary">Filter</button>

                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.policies.index') }}" class="btn btn-secondary" style="color:#ef4444" title="Clear Filters">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Policies Table Card -->
    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.03)">
        <div class="table-wrapper">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:0.875rem">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#475569;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.04em">
                        <th style="padding:12px 18px;font-weight:700;width:60px">Order</th>
                        <th style="padding:12px 18px;font-weight:700">Page Title & Slug</th>
                        <th style="padding:12px 18px;font-weight:700;text-align:center">Languages (Click to Translate)</th>
                        <th style="padding:12px 18px;font-weight:700;text-align:center">Status</th>
                        <th style="padding:12px 18px;font-weight:700">Last Updated</th>
                        <th style="padding:12px 18px;font-weight:700;text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody style="divide-y:1px solid #f1f5f9">
                    @forelse($policies as $policy)
                        <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.12s ease" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding:14px 18px;font-weight:700;color:#64748b">
                                <span style="background:#f1f5f9;border-radius:6px;padding:4px 8px;font-size:0.75rem;border:1px solid #e2e8f0">#{{ $policy->sort_order }}</span>
                            </td>
                            <td style="padding:14px 18px">
                                <div style="font-weight:700;color:#0f172a;font-size:0.92rem;margin-bottom:2px">
                                    {{ $policy->title }}
                                </div>
                                <div style="font-family:monospace;font-size:0.78rem;color:#64748b;display:flex;align-items:center;gap:6px">
                                    <span>/{{ app()->getLocale() }}/policy/<strong>{{ $policy->slug }}</strong></span>
                                </div>
                                @if($policy->summary)
                                    <div style="font-size:0.78rem;color:#94a3b8;margin-top:4px;max-width:380px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                        {{ $policy->summary }}
                                    </div>
                                @endif
                            </td>

                            <!-- Interactive Languages Column -->
                            <td style="padding:14px 18px;text-align:center">
                                <div style="display:inline-flex;align-items:center;gap:6px">
                                    <!-- EN Badge (Default) -->
                                    <button type="button" class="policy-lang-pill active" 
                                            onclick="openTranslateModal({{ $policy->id }}, @js($policy->title), @js($policy->content), 'en', 'English 🇺🇸', @js($policy->title), @js($policy->content))"
                                            title="English (Default) — Click to view/edit with CKEditor">
                                        EN
                                    </button>

                                    <!-- ZH Badge (Chinese) -->
                                    @if(!empty($policy->content_zh))
                                        <button type="button" class="policy-lang-pill translated" 
                                                onclick="openTranslateModal({{ $policy->id }}, @js($policy->title), @js($policy->content), 'zh', 'Chinese 🇨🇳 (中文)', @js($policy->title_zh), @js($policy->content_zh))"
                                                title="Chinese Translation: Ready — Click to edit with CKEditor">
                                            ZH ✓
                                        </button>
                                    @else
                                        <button type="button" class="policy-lang-pill missing" 
                                                onclick="openTranslateModal({{ $policy->id }}, @js($policy->title), @js($policy->content), 'zh', 'Chinese 🇨🇳 (中文)', '', '')"
                                                title="Add Chinese Translation with CKEditor (+)">
                                            + ZH
                                        </button>
                                    @endif

                                    <!-- BM Badge (Malay) -->
                                    @if(!empty($policy->content_bm))
                                        <button type="button" class="policy-lang-pill translated" 
                                                onclick="openTranslateModal({{ $policy->id }}, @js($policy->title), @js($policy->content), 'bm', 'Malay 🇲🇾 (Bahasa Melayu)', @js($policy->title_bm), @js($policy->content_bm))"
                                                title="Malay Translation: Ready — Click to edit with CKEditor">
                                            BM ✓
                                        </button>
                                    @else
                                        <button type="button" class="policy-lang-pill missing" 
                                                onclick="openTranslateModal({{ $policy->id }}, @js($policy->title), @js($policy->content), 'bm', 'Malay 🇲🇾 (Bahasa Melayu)', '', '')"
                                                title="Add Malay Translation with CKEditor (+)">
                                            + BM
                                        </button>
                                    @endif
                                </div>
                            </td>

                            <td style="padding:14px 18px;text-align:center">
                                <form method="POST" action="{{ route('admin.policies.toggle-status', $policy) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" style="border:none;background:transparent;cursor:pointer;padding:0" title="Click to toggle status">
                                        @if($policy->status === 'published')
                                            <span style="display:inline-flex;align-items:center;gap:5px;background:#ecfdf5;color:#059669;font-weight:700;font-size:0.75rem;padding:4px 10px;border-radius:999px;border:1px solid #a7f3d0">
                                                <span style="width:6px;height:6px;border-radius:50%;background:#10b981"></span>
                                                Published
                                            </span>
                                        @else
                                            <span style="display:inline-flex;align-items:center;gap:5px;background:#fef3c7;color:#d97706;font-weight:700;font-size:0.75rem;padding:4px 10px;border-radius:999px;border:1px solid #fde68a">
                                                <span style="width:6px;height:6px;border-radius:50%;background:#f59e0b"></span>
                                                Draft
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td style="padding:14px 18px;color:#64748b;font-size:0.8rem">
                                {{ $policy->updated_at->format('M d, Y') }}
                                <div style="font-size:0.72rem;color:#94a3b8">{{ $policy->updated_at->format('H:i') }}</div>
                            </td>
                            <td style="padding:14px 18px;text-align:right">
                                <div style="display:inline-flex;align-items:center;gap:6px">
                                    <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => $policy->slug]) }}" target="_blank" class="btn btn-secondary" style="padding:5px 9px;font-size:0.78rem" title="View Page on Frontend">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.policies.edit', $policy) }}" class="btn btn-secondary" style="padding:5px 10px;font-size:0.78rem" title="Edit Page Content">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.policies.destroy', $policy) }}" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this page ({{ $policy->title }})? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding:5px 9px;font-size:0.78rem" title="Delete Page">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:48px 24px;text-align:center;color:#64748b">
                                <div style="font-size:2.5rem;margin-bottom:8px">📄</div>
                                <div style="font-weight:700;font-size:1.05rem;color:#0f172a;margin-bottom:4px">No policies or pages found</div>
                                <p style="font-size:0.85rem;color:#94a3b8;margin:0 0 16px 0">Create your first custom page to have it appear automatically in your store footer.</p>
                                <a href="{{ route('admin.policies.create') }}" class="btn btn-primary" style="padding:8px 16px">Create Page (English)</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($policies->hasPages())
            <div style="padding:16px 20px;border-top:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center">
                {{ $policies->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════════════ -->
<!-- CKEDITOR QUICK TRANSLATION MODAL POPUP                                      -->
<!-- ═══════════════════════════════════════════════════════════════════════════ -->
<div id="translateModal" class="translate-modal-backdrop" style="display:none" onclick="if(event.target===this) closeTranslateModal()">
    <div class="translate-modal-card">
        <!-- Modal Header -->
        <div class="translate-modal-header">
            <div style="display:flex;align-items:center;gap:10px">
                <div class="translate-modal-icon">🌐</div>
                <div>
                    <h2 id="modalTitle" style="margin:0 0 2px 0;font-size:1.15rem;font-weight:800;color:#0f172a">Translate Policy</h2>
                    <div id="modalSub" style="font-size:0.78rem;color:#64748b">Full CKEditor Translation Studio</div>
                </div>
            </div>
            <button type="button" class="translate-modal-close" onclick="closeTranslateModal()" aria-label="Close">✕</button>
        </div>

        <!-- Modal Form -->
        <form id="translateForm" method="POST" action="">
            @csrf
            <input type="hidden" name="lang" id="modalLangInput" value="">

            <div class="translate-modal-body">
                <!-- Two Column Comparison Layout -->
                <div class="translate-grid">
                    <!-- Left: Original English Reference -->
                    <div class="translate-col-ref">
                        <div class="translate-col-header">
                            <span style="font-weight:700;color:#1e40af;font-size:0.82rem">🇺🇸 Original English (Reference)</span>
                            <button type="button" class="translate-copy-btn" onclick="copyEnglishToModalEditor()" title="Copy English HTML into the translation CKEditor">
                                📋 Copy English to Editor
                            </button>
                        </div>
                        <div style="margin-bottom:12px">
                            <label style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase">Title</label>
                            <div id="refEnglishTitle" style="font-weight:700;color:#0f172a;font-size:0.92rem;background:#ffffff;border:1px solid #cbd5e1;padding:8px 12px;border-radius:6px;margin-top:4px">
                            </div>
                        </div>
                        <div>
                            <label style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase">Content Preview</label>
                            <div id="refEnglishContent" class="translate-ref-scroll">
                            </div>
                        </div>
                    </div>

                    <!-- Right: Translation Input Area with CKEditor -->
                    <div class="translate-col-edit">
                        <div class="translate-col-header">
                            <span id="targetLangLabel" style="font-weight:700;color:#0f172a;font-size:0.84rem">🇨🇳 Translation Content</span>
                            <button type="button" class="insert-html-btn" onclick="openInsertHtmlModal('modalTargetContent')">
                                <strong>&lt;/&gt;</strong> Insert Custom HTML
                            </button>
                        </div>

                        <!-- Target Title Input -->
                        <div style="margin-bottom:14px">
                            <label style="font-size:0.76rem;font-weight:700;color:#334155;text-transform:uppercase;display:block;margin-bottom:4px">
                                Translated Title <span style="color:#ef4444">*</span>
                            </label>
                            <input type="text" name="title" id="modalTargetTitle" required placeholder="e.g. 隐私政策 / Dasar Privasi" class="form-control" style="font-weight:700;font-size:0.92rem">
                        </div>

                        <!-- CKEditor Container -->
                        <div>
                            <label style="font-size:0.76rem;font-weight:700;color:#334155;text-transform:uppercase;display:block;margin-bottom:6px">
                                Translated Content (CKEditor) <span style="color:#ef4444">*</span>
                            </label>
                            <textarea name="content" id="modalTargetContent"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="translate-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeTranslateModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding:9px 24px;font-weight:700">
                    💾 Save Translation
                </button>
            </div>
        </form>
    </div>
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
    /* Language pill buttons on table */
    .policy-lang-pill {
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.72rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        line-height: 1.2;
    }

    .policy-lang-pill.active {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #93c5fd;
    }
    .policy-lang-pill.active:hover {
        background: #dbeafe;
        border-color: #3b82f6;
    }

    .policy-lang-pill.translated {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #93c5fd;
    }
    .policy-lang-pill.translated:hover {
        background: #dbeafe;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(29, 78, 216, 0.15);
    }

    .policy-lang-pill.missing {
        background: #f8fafc;
        color: #64748b;
        border: 1.5px dashed #cbd5e1;
    }
    .policy-lang-pill.missing:hover {
        background: #eff6ff;
        color: #2563eb;
        border-color: #2563eb;
        border-style: solid;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.15);
    }

    /* Modal Styling */
    .translate-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(6, 21, 43, 0.65);
        backdrop-filter: blur(5px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        box-sizing: border-box;
    }

    .translate-modal-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        width: 100%;
        max-width: 1140px;
        max-height: 94vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: modalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalPop {
        0% { transform: scale(0.96); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .translate-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .translate-modal-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .translate-modal-close {
        background: transparent;
        border: none;
        font-size: 1.25rem;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.1s ease;
    }
    .translate-modal-close:hover {
        background: #fee2e2;
        color: #ef4444;
    }

    .translate-modal-body {
        padding: 20px 24px;
        overflow-y: auto;
        flex: 1;
    }

    .translate-grid {
        display: grid;
        grid-template-columns: 340px 1fr;
        gap: 20px;
        align-items: start;
    }

    @media (max-width: 900px) {
        .translate-grid {
            grid-template-columns: 1fr;
        }
    }

    .translate-col-ref {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
    }

    .translate-col-edit {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .translate-col-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e2e8f0;
    }

    .translate-ref-scroll {
        max-height: 360px;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px 12px;
        font-family: monospace;
        font-size: 0.8rem;
        line-height: 1.5;
        color: #475569;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .translate-copy-btn {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 0.72rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.12s ease;
    }
    .translate-copy-btn:hover {
        background: #dbeafe;
    }

    .insert-html-btn {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        color: #1e40af;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.74rem;
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

    .translate-modal-footer {
        padding: 14px 24px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
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
    let currentEnglishTitle = '';
    let currentEnglishContent = '';
    let modalEditorInstance = null;

    const modalCkConfig = {
        height: 320,
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

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof CKEDITOR !== 'undefined') {
            modalEditorInstance = CKEDITOR.replace('modalTargetContent', modalCkConfig);
        }
    });

    function openTranslateModal(policyId, englishTitle, englishContent, targetLang, langDisplayName, existingTitle, existingContent) {
        currentEnglishTitle = englishTitle;
        currentEnglishContent = englishContent;

        // Set form action
        const form = document.getElementById('translateForm');
        form.action = `/admin/policies/${policyId}/translation`;

        // Set hidden lang
        document.getElementById('modalLangInput').value = targetLang;

        // Set headers
        document.getElementById('modalTitle').textContent = `🌐 Translate: ${englishTitle}`;
        document.getElementById('modalSub').textContent = `Editing translation for ${langDisplayName}`;
        document.getElementById('targetLangLabel').textContent = `${langDisplayName} Content`;

        // Fill English reference
        document.getElementById('refEnglishTitle').textContent = englishTitle;
        document.getElementById('refEnglishContent').textContent = englishContent;

        // Fill target inputs
        document.getElementById('modalTargetTitle').value = existingTitle || '';
        
        // Fill CKEditor
        if (modalEditorInstance) {
            modalEditorInstance.setData(existingContent || '');
        } else {
            document.getElementById('modalTargetContent').value = existingContent || '';
        }

        // Show modal
        const modal = document.getElementById('translateModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            document.getElementById('modalTargetTitle').focus();
        }, 150);
    }

    function closeTranslateModal() {
        document.getElementById('translateModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function copyEnglishToModalEditor() {
        const titleInput = document.getElementById('modalTargetTitle');
        if (!titleInput.value) {
            titleInput.value = currentEnglishTitle;
        }
        if (modalEditorInstance) {
            modalEditorInstance.setData(currentEnglishContent);
        }
    }

    // Insert Custom HTML Modal Handler
    let targetEditorId = 'modalTargetContent';
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

    // Form submit: sync CKEditor
    document.getElementById('translateForm').addEventListener('submit', () => {
        if (modalEditorInstance) {
            modalEditorInstance.updateElement();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (document.getElementById('insertHtmlModal').style.display !== 'none') {
                closeInsertHtmlModal();
            } else if (document.getElementById('translateModal').style.display !== 'none') {
                closeTranslateModal();
            }
        }
    });
</script>
@endsection
