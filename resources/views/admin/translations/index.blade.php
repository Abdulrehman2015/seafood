@extends('layouts.admin')
@section('title', 'Multilingual Translation Manager — Admin')

@section('content')

{{-- Top Header --}}
<div class="admin-topbar" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
    <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
            <h1 class="admin-page-title" style="margin:0;font-size:clamp(1.35rem, 2.5vw, 1.75rem);font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px">
                <span>🌐</span> Multilingual Translations
            </h1>
            <span style="font-size:0.75rem;background:#e0e7ff;color:#4338ca;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #c7d2fe">
                3 Languages: EN • 中文 • BM
            </span>
        </div>
        <p class="text-sm text-muted" style="margin:0;color:#64748b">
            Translate and dynamically manage all user interface text, buttons, alerts, navigation, and page content live across English, Simplified Chinese, and Bahasa Melayu.
        </p>
    </div>

    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        {{-- Language Preview Switcher in Admin --}}
        <div style="display:flex;align-items:center;gap:6px;background:#f1f5f9;padding:4px 8px;border-radius:10px;border:1px solid #e2e8f0">
            <span style="font-size:0.75rem;font-weight:600;color:#64748b">Preview:</span>
            <a href="{{ route('language.switch', 'en') }}" class="btn-lang-pill {{ current_locale() === 'en' ? 'active' : '' }}" title="Preview English">🇬🇧 EN</a>
            <a href="{{ route('language.switch', 'zh') }}" class="btn-lang-pill {{ current_locale() === 'zh' ? 'active' : '' }}" title="Preview Simplified Chinese">🇨🇳 中文</a>
            <a href="{{ route('language.switch', 'bm') }}" class="btn-lang-pill {{ current_locale() === 'bm' ? 'active' : '' }}" title="Preview Bahasa Melayu">🇲🇾 BM</a>
        </div>

        {{-- Add Key Button --}}
        <button type="button" class="btn btn-primary" onclick="openAddKeyModal()"
                style="background:#2563eb;border-color:#2563eb;font-weight:700;display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:10px;font-size:0.875rem">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Add Translation Key</span>
        </button>

        {{-- Sync Core Strings Button --}}
        <form method="POST" action="{{ route('admin.translations.sync') }}" style="display:inline" onsubmit="return confirm('Resync core translation strings from seed catalog? Any existing modified translations will be preserved.')">
            @csrf
            <button type="submit" class="btn btn-secondary" style="font-weight:600;padding:9px 14px;border-radius:10px;font-size:0.875rem;display:inline-flex;align-items:center;gap:6px">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                </svg>
                <span>Resync Catalog</span>
            </button>
        </form>

        {{-- Clear Cache Button --}}
        <form method="POST" action="{{ route('admin.translations.clearCache') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-secondary" style="font-weight:600;padding:9px 12px;border-radius:10px;font-size:0.875rem" title="Clear translation cache and reload files">
                ⚡ Clear Cache
            </button>
        </form>
    </div>
</div>

{{-- Metrics Overview --}}
@php
    $zhPercent = $totalCount > 0 ? round(($zhFilledCount / $totalCount) * 100) : 0;
    $bmPercent = $totalCount > 0 ? round(($bmFilledCount / $totalCount) * 100) : 0;
    $completedPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
@endphp
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;margin-bottom:24px">
    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;box-shadow:0 1px 3px rgba(0,0,0,0.04);display:flex;align-items:center;justify-content:space-between">
        <div>
            <div style="font-size:1.6rem;font-weight:800;color:#0f172a">{{ $totalCount }}</div>
            <div style="font-size:0.8rem;color:#64748b;font-weight:600;margin-top:2px">Total UI Strings</div>
        </div>
        <div style="width:44px;height:44px;border-radius:10px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:1.3rem">
            📝
        </div>
    </div>

    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;box-shadow:0 1px 3px rgba(0,0,0,0.04);display:flex;align-items:center;justify-content:space-between">
        <div>
            <div style="font-size:1.6rem;font-weight:800;color:#1e3a8a">100%</div>
            <div style="font-size:0.8rem;color:#64748b;font-weight:600;margin-top:2px">🇬🇧 English (Base)</div>
        </div>
        <div style="width:44px;height:44px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:1.3rem">
            🇬🇧
        </div>
    </div>

    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;box-shadow:0 1px 3px rgba(0,0,0,0.04);display:flex;align-items:center;justify-content:space-between">
        <div>
            <div style="font-size:1.6rem;font-weight:800;color:#dc2626">{{ $zhPercent }}% <span style="font-size:0.85rem;color:#64748b;font-weight:500">({{ $zhFilledCount }}/{{ $totalCount }})</span></div>
            <div style="font-size:0.8rem;color:#64748b;font-weight:600;margin-top:2px">🇨🇳 Simplified Chinese (中文)</div>
        </div>
        <div style="width:44px;height:44px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;font-size:1.3rem">
            🇨🇳
        </div>
    </div>

    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;box-shadow:0 1px 3px rgba(0,0,0,0.04);display:flex;align-items:center;justify-content:space-between">
        <div>
            <div style="font-size:1.6rem;font-weight:800;color:#059669">{{ $bmPercent }}% <span style="font-size:0.85rem;color:#64748b;font-weight:500">({{ $bmFilledCount }}/{{ $totalCount }})</span></div>
            <div style="font-size:0.8rem;color:#64748b;font-weight:600;margin-top:2px">🇲🇾 Bahasa Melayu (BM)</div>
        </div>
        <div style="width:44px;height:44px;border-radius:10px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;font-size:1.3rem">
            🇲🇾
        </div>
    </div>
</div>

{{-- Filters & Search Bar --}}
<div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;margin-bottom:20px;box-shadow:0 1px 3px rgba(0,0,0,0.03)">
    <form method="GET" action="{{ route('admin.translations.index') }}" style="display:flex;flex-wrap:wrap;align-items:center;gap:14px;justify-content:space-between">
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;flex:1;min-width:300px">
            {{-- Group Dropdown --}}
            <div>
                <label style="font-size:0.75rem;font-weight:700;color:#64748b;display:block;margin-bottom:4px">SECTION / PAGE</label>
                <select name="group" onchange="this.form.submit()" style="padding:8px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.85rem;background:#ffffff;color:#0f172a;min-width:170px">
                    <option value="all" {{ $group === 'all' ? 'selected' : '' }}>All Sections ({{ $totalCount }})</option>
                    @foreach($groups as $g)
                        <option value="{{ $g->group }}" {{ $group === $g->group ? 'selected' : '' }}>
                            {{ ucfirst($g->group) }} ({{ $g->total }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label style="font-size:0.75rem;font-weight:700;color:#64748b;display:block;margin-bottom:4px">COMPLETION STATUS</label>
                <select name="status" onchange="this.form.submit()" style="padding:8px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.85rem;background:#ffffff;color:#0f172a">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Strings</option>
                    <option value="missing_zh" {{ $status === 'missing_zh' ? 'selected' : '' }}>Missing Chinese (ZH)</option>
                    <option value="missing_bm" {{ $status === 'missing_bm' ? 'selected' : '' }}>Missing Malay (BM)</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>100% Fully Translated</option>
                </select>
            </div>

            {{-- Search Box --}}
            <div style="flex:1;min-width:220px">
                <label style="font-size:0.75rem;font-weight:700;color:#64748b;display:block;margin-bottom:4px">SEARCH TEXT OR KEY</label>
                <div class="cat-search-group">
                    <span class="cat-search-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control cat-search-input" placeholder="Search English, Chinese, Malay, or key...">
                </div>
            </div>
        </div>

        <div style="display:flex;align-items:flex-end;gap:8px">
            <button type="submit" class="btn btn-primary" style="padding:8px 16px;border-radius:8px;font-size:0.85rem;font-weight:600">
                Filter
            </button>
            @if($group !== 'all' || $status !== 'all' || !empty($search))
                <a href="{{ route('admin.translations.index') }}" class="btn btn-secondary" style="padding:8px 14px;border-radius:8px;font-size:0.85rem">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Group Quick Tabs --}}
<div style="display:flex;gap:6px;overflow-x:auto;padding-bottom:12px;margin-bottom:16px;-webkit-overflow-scrolling:touch">
    <a href="{{ route('admin.translations.index', array_merge(request()->except('group', 'page'), ['group' => 'all'])) }}"
       class="group-tab-badge {{ $group === 'all' ? 'active' : '' }}">
       All ({{ $totalCount }})
    </a>
    @foreach($groups as $g)
        <a href="{{ route('admin.translations.index', array_merge(request()->except('group', 'page'), ['group' => $g->group])) }}"
           class="group-tab-badge {{ $group === $g->group ? 'active' : '' }}">
           {{ ucfirst($g->group) }} ({{ $g->total }})
        </a>
    @endforeach
</div>

{{-- Side-by-Side Translation Editor Form --}}
<form method="POST" action="{{ route('admin.translations.update') }}" id="translationForm">
    @csrf

    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.04);overflow:hidden">
        {{-- Sticky Table Action Bar --}}
        <div style="padding:14px 20px;background:#f8fafc;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:0.875rem;font-weight:600;color:#334155">
                Showing <span style="font-weight:700;color:#0f172a">{{ $translations->firstItem() ?? 0 }} - {{ $translations->lastItem() ?? 0 }}</span> of <span style="font-weight:700;color:#0f172a">{{ $translations->total() }}</span> strings
                <span id="unsavedCountBadge" style="display:none;margin-left:8px;font-size:0.75rem;background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:12px;border:1px solid #fde68a;font-weight:700">
                    Unsaved Changes
                </span>
            </div>

            <div style="display:flex;align-items:center;gap:10px">
                <button type="button" class="btn btn-secondary" onclick="window.location.reload()" style="padding:8px 14px;border-radius:8px;font-size:0.85rem">
                    Discard
                </button>
                <button type="submit" class="btn btn-primary" id="saveAllBtn"
                        style="background:#16a34a;border-color:#16a34a;font-weight:700;padding:8px 20px;border-radius:8px;font-size:0.875rem;box-shadow:0 2px 6px rgba(22,163,74,0.25);display:inline-flex;align-items:center;gap:6px">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    <span>Save All Changes</span>
                </button>
            </div>
        </div>

        {{-- Translations Table --}}
        <div class="table-wrapper">
            <table class="admin-table" style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;text-align:left">
                        <th style="padding:12px 16px;font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;width:20%">Key & Helper</th>
                        <th style="padding:12px 16px;font-size:0.75rem;font-weight:700;color:#1e3a8a;text-transform:uppercase;width:26%">🇬🇧 English (Base)</th>
                        <th style="padding:12px 16px;font-size:0.75rem;font-weight:700;color:#dc2626;text-transform:uppercase;width:26%">🇨🇳 简体中文 (Chinese)</th>
                        <th style="padding:12px 16px;font-size:0.75rem;font-weight:700;color:#059669;text-transform:uppercase;width:24%">🇲🇾 Bahasa Melayu (BM)</th>
                        <th style="padding:12px 16px;font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;text-align:center;width:4%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($translations as $index => $t)
                        <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.12s ease" class="trans-row" id="row-{{ $t->id }}">
                            <input type="hidden" name="translations[{{ $index }}][group]" value="{{ $t->group }}">
                            <input type="hidden" name="translations[{{ $index }}][key]" value="{{ $t->key }}">

                            {{-- Key & Blade Helper Code --}}
                            <td style="padding:14px 16px;vertical-align:top">
                                <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px">
                                    <span class="group-pill group-pill-{{ $t->group }}">{{ ucfirst($t->group) }}</span>
                                </div>
                                <div style="font-family:monospace;font-weight:700;color:#0f172a;font-size:0.85rem;word-break:break-all">
                                    {{ $t->key }}
                                </div>
                                <div style="margin-top:6px">
                                    <span class="code-copy-pill" data-code="&#64;t('{{ $t->group }}.{{ $t->key }}')" onclick="copyHelper(this.getAttribute('data-code'))" title="Click to copy Blade directive">
                                        @@t('{{ $t->group }}.{{ $t->key }}')
                                    </span>
                                </div>
                            </td>

                            {{-- English (Base) --}}
                            <td style="padding:14px 16px;vertical-align:top">
                                @if(strlen($t->text_en ?? '') > 60 || str_contains($t->text_en ?? '', "\n"))
                                    <textarea name="translations[{{ $index }}][text_en]" rows="2" class="trans-input"
                                              oninput="markChanged(this)">{{ $t->text_en }}</textarea>
                                @else
                                    <input type="text" name="translations[{{ $index }}][text_en]" value="{{ $t->text_en }}" class="trans-input"
                                           oninput="markChanged(this)">
                                @endif
                            </td>

                            {{-- Simplified Chinese --}}
                            <td style="padding:14px 16px;vertical-align:top">
                                @if(strlen($t->text_zh ?? '') > 50 || str_contains($t->text_zh ?? '', "\n"))
                                    <textarea name="translations[{{ $index }}][text_zh]" rows="2" class="trans-input {{ empty($t->text_zh) ? 'trans-missing' : '' }}"
                                              placeholder="输入简体中文..." oninput="markChanged(this)">{{ $t->text_zh }}</textarea>
                                @else
                                    <input type="text" name="translations[{{ $index }}][text_zh]" value="{{ $t->text_zh }}" class="trans-input {{ empty($t->text_zh) ? 'trans-missing' : '' }}"
                                           placeholder="输入简体中文..." oninput="markChanged(this)">
                                @endif
                                @if(empty($t->text_zh))
                                    <div style="font-size:0.7rem;color:#dc2626;margin-top:3px;font-weight:600">⚠️ Missing Chinese</div>
                                @endif
                            </td>

                            {{-- Bahasa Melayu --}}
                            <td style="padding:14px 16px;vertical-align:top">
                                @if(strlen($t->text_bm ?? '') > 50 || str_contains($t->text_bm ?? '', "\n"))
                                    <textarea name="translations[{{ $index }}][text_bm]" rows="2" class="trans-input {{ empty($t->text_bm) ? 'trans-missing' : '' }}"
                                              placeholder="Masukkan Bahasa Melayu..." oninput="markChanged(this)">{{ $t->text_bm }}</textarea>
                                @else
                                    <input type="text" name="translations[{{ $index }}][text_bm]" value="{{ $t->text_bm }}" class="trans-input {{ empty($t->text_bm) ? 'trans-missing' : '' }}"
                                           placeholder="Masukkan Bahasa Melayu..." oninput="markChanged(this)">
                                @endif
                                @if(empty($t->text_bm))
                                    <div style="font-size:0.7rem;color:#059669;margin-top:3px;font-weight:600">⚠️ Missing Malay</div>
                                @endif
                            </td>

                            {{-- Delete action for custom key --}}
                            <td style="padding:14px 16px;vertical-align:top;text-align:center">
                                <button type="button" class="btn-delete-icon" onclick="deleteTranslation({{ $t->id }}, '{{ $t->group }}.{{ $t->key }}')" title="Delete key">
                                    🗑️
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:48px 16px;color:#64748b">
                                <div style="font-size:2rem;margin-bottom:8px">🔍</div>
                                <div style="font-size:1rem;font-weight:700;color:#0f172a">No translations found matching criteria.</div>
                                <p style="font-size:0.85rem;color:#64748b;margin:6px 0 16px">Try adjusting your filters or click below to resync default strings.</p>
                                <a href="{{ route('admin.translations.index') }}" class="btn btn-secondary">Clear Filters</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Bottom Pagination & Action Bar --}}
        <div style="padding:14px 20px;background:#f8fafc;border-top:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:0.85rem;color:#64748b">
                Tip: Changes take effect across the entire website immediately upon clicking Save.
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <button type="submit" class="btn btn-primary"
                        style="background:#16a34a;border-color:#16a34a;font-weight:700;padding:8px 20px;border-radius:8px;font-size:0.875rem">
                    Save All Changes
                </button>
            </div>
        </div>
    </div>
</form>

{{-- Pagination Links --}}
<div style="margin-top:20px">
    {{ $translations->links() }}
</div>

{{-- Add Key Modal --}}
<div id="addKeyModal" class="modal-backdrop" style="display:none">
    <div class="modal-box" style="max-width:540px;background:#ffffff;border-radius:14px;box-shadow:0 20px 40px rgba(0,0,0,0.2);overflow:hidden;border:1px solid #e2e8f0">
        <div style="padding:16px 20px;background:#f8fafc;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between">
            <h3 style="margin:0;font-size:1.1rem;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px">
                <span>➕</span> Add New Translation Key
            </h3>
            <button type="button" onclick="closeAddKeyModal()" style="border:none;background:transparent;font-size:1.25rem;cursor:pointer;color:#64748b">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.translations.store') }}" style="padding:20px">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:#334155;margin-bottom:4px">SECTION / GROUP *</label>
                    <input type="text" name="group" required placeholder="e.g. common, home, shop" list="groupSuggestions"
                           style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.85rem;box-sizing:border-box">
                    <datalist id="groupSuggestions">
                        @foreach($groups as $g)
                            <option value="{{ $g->group }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:#334155;margin-bottom:4px">KEY NAME *</label>
                    <input type="text" name="key" required placeholder="e.g. hero_cta_button"
                           style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.85rem;box-sizing:border-box">
                </div>
            </div>

            <div style="margin-bottom:14px">
                <label style="display:block;font-size:0.8rem;font-weight:700;color:#1e3a8a;margin-bottom:4px">🇬🇧 ENGLISH TEXT (BASE) *</label>
                <textarea name="text_en" required rows="2" placeholder="English default text..."
                          style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.85rem;box-sizing:border-box"></textarea>
            </div>

            <div style="margin-bottom:14px">
                <label style="display:block;font-size:0.8rem;font-weight:700;color:#dc2626;margin-bottom:4px">🇨🇳 SIMPLIFIED CHINESE (中文)</label>
                <textarea name="text_zh" rows="2" placeholder="简体中文翻译..."
                          style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.85rem;box-sizing:border-box"></textarea>
            </div>

            <div style="margin-bottom:20px">
                <label style="display:block;font-size:0.8rem;font-weight:700;color:#059669;margin-bottom:4px">🇲🇾 BAHASA MELAYU (BM)</label>
                <textarea name="text_bm" rows="2" placeholder="Terjemahan Bahasa Melayu..."
                          style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.85rem;box-sizing:border-box"></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" class="btn btn-secondary" onclick="closeAddKeyModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#2563eb;font-weight:700">Save Translation</button>
            </div>
        </form>
    </div>
</div>

{{-- Hidden Delete Form --}}
<form id="deleteForm" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

<style>
    .btn-lang-pill {
        padding: 3px 8px;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 6px;
        text-decoration: none;
        color: #475569;
        transition: all 0.15s ease;
    }
    .btn-lang-pill.active {
        background: #2563eb;
        color: #ffffff !important;
        box-shadow: 0 1px 4px rgba(37,99,235,0.3);
    }
    .group-tab-badge {
        display: inline-block;
        white-space: nowrap;
        padding: 6px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        background: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: 20px;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .group-tab-badge:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .group-tab-badge.active {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }
    .trans-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #0f172a;
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color 0.15s, box-shadow 0.15s;
        background: #ffffff;
    }
    .trans-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        outline: none;
    }
    .trans-input.changed {
        border-color: #f59e0b;
        background: #fffbeb;
    }
    .trans-missing {
        border-color: #fca5a5;
        background: #fef2f2;
    }
    .group-pill {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
        background: #e2e8f0;
        color: #334155;
    }
    .group-pill-nav { background: #dbeafe; color: #1e40af; }
    .group-pill-home { background: #e0e7ff; color: #3730a3; }
    .group-pill-shop { background: #fef3c7; color: #92400e; }
    .group-pill-cart { background: #fce7f3; color: #9d174d; }
    .group-pill-checkout { background: #dcfce7; color: #166534; }
    .group-pill-contact { background: #ccfbf1; color: #115e59; }
    .group-pill-rfq { background: #ffedd5; color: #9a3412; }
    .group-pill-footer { background: #f1f5f9; color: #475569; }

    .code-copy-pill {
        display: inline-block;
        font-size: 0.7rem;
        font-family: monospace;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        padding: 2px 6px;
        border-radius: 4px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s;
    }
    .code-copy-pill:hover {
        background: #eff6ff;
        border-color: #93c5fd;
        color: #1d4ed8;
    }
    .btn-delete-icon {
        border: none;
        background: transparent;
        cursor: pointer;
        padding: 6px;
        border-radius: 6px;
        opacity: 0.5;
        transition: opacity 0.15s, transform 0.15s;
    }
    .btn-delete-icon:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        padding: 16px;
    }
</style>

<script>
    let hasUnsavedChanges = false;
    let changedInputs = new Set();

    function markChanged(input) {
        input.classList.add('changed');
        changedInputs.add(input);
        hasUnsavedChanges = true;
        document.getElementById('unsavedCountBadge').style.display = 'inline-block';
        document.getElementById('saveAllBtn').classList.add('animate-pulse');
    }

    function copyHelper(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard: ' + text);
        });
    }

    function openAddKeyModal() {
        document.getElementById('addKeyModal').style.display = 'flex';
    }

    function closeAddKeyModal() {
        document.getElementById('addKeyModal').style.display = 'none';
    }

    function deleteTranslation(id, label) {
        if (confirm(`Are you sure you want to delete translation "${label}"?`)) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/translations/${id}`;
            form.submit();
        }
    }

    window.addEventListener('beforeunload', function (e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = 'You have unsaved translation edits!';
        }
    });

    document.getElementById('translationForm').addEventListener('submit', function() {
        hasUnsavedChanges = false;
    });
</script>

@endsection
