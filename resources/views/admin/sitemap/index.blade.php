@extends('layouts.admin')
@section('title', 'XML Sitemap Management — Admin')

@section('content')

{{-- Top Header --}}
<div class="admin-topbar seo-topbar" style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px">
    <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
            <h1 class="admin-page-title" style="margin:0;font-size:clamp(1.35rem, 2.5vw, 1.75rem);font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px">
                <span>🗺️</span> XML Sitemap Management
            </h1>
            <span class="badge" style="background:{{ $mode === 'dynamic' ? '#ecfdf5' : '#eff6ff' }};color:{{ $mode === 'dynamic' ? '#047857' : '#1d4ed8' }};border:1px solid {{ $mode === 'dynamic' ? '#a7f3d0' : '#bfdbfe' }};font-weight:700;padding:4px 10px;border-radius:999px;font-size:0.75rem;text-transform:uppercase">
                {{ $mode === 'dynamic' ? '⚡ Dynamic Auto-Generated' : '📁 Custom Uploaded' }}
            </span>
        </div>
        <p class="text-sm text-muted" style="margin:0;color:#64748b">
            Auto-generate Google-compliant XML sitemaps from database records or upload a custom <code>sitemap.xml</code> file.
        </p>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="{{ $sitemapUrl }}" target="_blank" class="btn btn-secondary" style="display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:9px;font-weight:600;font-size:0.875rem">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
            </svg>
            <span>View Live Sitemap</span>
        </a>
        <a href="{{ route('admin.sitemap.download') }}" class="btn btn-secondary" style="display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:9px;font-weight:600;font-size:0.875rem">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Download XML</span>
        </a>
    </div>
</div>


{{-- Quick Metrics Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;margin-bottom:24px">
    {{-- Total URLs --}}
    <div class="card" style="padding:18px 20px;display:flex;justify-content:space-between;align-items:center">
        <div>
            <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.04em">Total URLs Indexed</div>
            <div style="font-size:1.75rem;font-weight:800;color:#0f172a;margin-top:2px">
                {{ $mode === 'custom' && $hasCustomFile ? ($customFileInfo['url_count'] ?? 0) : $dynamicStats['total'] }}
            </div>
            <div style="font-size:0.75rem;color:#059669;font-weight:600;margin-top:2px">
                ● 100% crawlable by Google &amp; Bing
            </div>
        </div>
        <div style="width:48px;height:48px;border-radius:12px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#2563eb">
            📄
        </div>
    </div>

    {{-- Active Mode --}}
    <div class="card" style="padding:18px 20px;display:flex;justify-content:space-between;align-items:center">
        <div>
            <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.04em">Active Engine Mode</div>
            <div style="font-size:1.2rem;font-weight:800;color:{{ $mode === 'dynamic' ? '#047857' : '#1d4ed8' }};margin-top:6px">
                {{ $mode === 'dynamic' ? 'Auto-Generated' : 'Custom Uploaded' }}
            </div>
            <div style="font-size:0.75rem;color:#64748b;margin-top:2px">
                {{ $mode === 'dynamic' ? 'Real-time database sync' : 'Static custom file' }}
            </div>
        </div>
        <div style="width:48px;height:48px;border-radius:12px;background:{{ $mode === 'dynamic' ? '#ecfdf5' : '#eff6ff' }};display:flex;align-items:center;justify-content:center;font-size:1.4rem">
            {{ $mode === 'dynamic' ? '⚡' : '📁' }}
        </div>
    </div>

    {{-- Last Generated --}}
    <div class="card" style="padding:18px 20px;display:flex;justify-content:space-between;align-items:center">
        <div>
            <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.04em">Last Generated</div>
            <div style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-top:6px">
                {{ $lastGenerated ? \Carbon\Carbon::parse($lastGenerated)->format('M d, Y H:i') : 'Real-time (Active)' }}
            </div>
            <div style="font-size:0.75rem;color:#64748b;margin-top:2px">
                {{ $lastGenerated ? \Carbon\Carbon::parse($lastGenerated)->diffForHumans() : 'Auto-synced' }}
            </div>
        </div>
        <div style="width:48px;height:48px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#d97706">
            🕒
        </div>
    </div>

    {{-- Browser Styling --}}
    <div class="card" style="padding:18px 20px;display:flex;justify-content:space-between;align-items:center">
        <div>
            <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.04em">Browser XSL Styling</div>
            <div style="font-size:1.1rem;font-weight:800;color:#059669;margin-top:6px">
                Active &amp; Styled
            </div>
            <div style="font-size:0.75rem;color:#64748b;margin-top:2px">
                Transformed via /sitemap.xsl
            </div>
        </div>
        <div style="width:48px;height:48px;border-radius:12px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#16a34a">
            ✨
        </div>
    </div>
</div>

{{-- Main Grid: 2 Action Columns --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(360px, 1fr));gap:24px;margin-bottom:28px">

    {{-- Panel 1: Dynamic Auto-Generation --}}
    <div class="card" style="padding:24px;border:1px solid {{ $mode === 'dynamic' ? '#93c5fd' : '#e2e8f0' }};box-shadow:{{ $mode === 'dynamic' ? '0 4px 16px rgba(37,99,235,0.08)' : 'none' }}">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px">
            <div>
                <h2 style="font-size:1.15rem;font-weight:700;color:#0f172a;margin:0 0 4px 0;display:flex;align-items:center;gap:8px">
                    <span>⚡</span> Dynamic Auto-Generator
                </h2>
                <p style="font-size:0.84rem;color:#64748b;margin:0">
                    Automatically discovers and indexes all catalog products, category filters, content pages, and multi-language variants.
                </p>
            </div>
            @if($mode === 'dynamic')
                <span style="background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;font-size:0.72rem;font-weight:700;padding:3px 8px;border-radius:6px;text-transform:uppercase">
                    ● Currently Active
                </span>
            @endif
        </div>

        {{-- Dynamic URLs Breakdown --}}
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px 16px;margin-bottom:20px">
            <div style="font-size:0.76rem;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:10px">
                Google Canonical URL Breakdown (× 3 Locales: EN, ZH, BM)
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:0.84rem">
                <div style="display:flex;justify-content:space-between;border-bottom:1px dashed #cbd5e1;padding-bottom:4px">
                    <span style="color:#64748b">Canonical Core Pages:</span>
                    <strong style="color:#0f172a">{{ $dynamicStats['core'] }} URLs</strong>
                </div>
                <div style="display:flex;justify-content:space-between;border-bottom:1px dashed #cbd5e1;padding-bottom:4px">
                    <span style="color:#64748b">Live Catalog Products:</span>
                    <strong style="color:#0f172a">{{ $dynamicStats['products'] }} URLs</strong>
                </div>
                <div style="display:flex;justify-content:space-between;border-bottom:1px dashed #cbd5e1;padding-bottom:4px">
                    <span style="color:#64748b">Google Images Indexed:</span>
                    <strong style="color:#047857">{{ $dynamicStats['images'] }} Images</strong>
                </div>
                <div style="display:flex;justify-content:space-between;border-bottom:1px dashed #cbd5e1;padding-bottom:4px">
                    <span style="color:#64748b">Multilingual Hreflang:</span>
                    <strong style="color:#0f172a">EN, ZH, BM</strong>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <form method="POST" action="{{ route('admin.sitemap.generate') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-primary" style="background:#2563eb;border-color:#2563eb;font-weight:700;display:inline-flex;align-items:center;gap:7px;padding:10px 18px;border-radius:9px;font-size:0.88rem">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                    <span>Generate &amp; Update Sitemap Now</span>
                </button>
            </form>

            @if($mode !== 'dynamic')
                <form method="POST" action="{{ route('admin.sitemap.mode') }}" style="display:inline">
                    @csrf
                    <input type="hidden" name="mode" value="dynamic">
                    <button type="submit" class="btn btn-secondary" style="font-weight:600;padding:10px 16px;border-radius:9px;font-size:0.88rem">
                        Switch to Dynamic Mode
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Panel 2: Upload Custom sitemap.xml --}}
    <div class="card" style="padding:24px;border:1px solid {{ $mode === 'custom' ? '#93c5fd' : '#e2e8f0' }};box-shadow:{{ $mode === 'custom' ? '0 4px 16px rgba(37,99,235,0.08)' : 'none' }}">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px">
            <div>
                <h2 style="font-size:1.15rem;font-weight:700;color:#0f172a;margin:0 0 4px 0;display:flex;align-items:center;gap:8px">
                    <span>📁</span> Upload Custom Sitemap
                </h2>
                <p style="font-size:0.84rem;color:#64748b;margin:0">
                    Upload a custom <code>sitemap.xml</code> exported from third-party tools or customized manually.
                </p>
            </div>
            @if($mode === 'custom')
                <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:0.72rem;font-weight:700;padding:3px 8px;border-radius:6px;text-transform:uppercase">
                    ● Currently Active
                </span>
            @endif
        </div>

        {{-- If Custom File Exists --}}
        @if($hasCustomFile && $customFileInfo)
            <div style="background:#f1f5f9;border:1px solid #cbd5e1;border-radius:10px;padding:14px 16px;margin-bottom:18px">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                    <span style="font-weight:700;font-size:0.86rem;color:#0f172a;display:flex;align-items:center;gap:6px">
                        <span>📄</span> sitemap_custom.xml
                    </span>
                    <span style="font-size:0.75rem;background:#e2e8f0;color:#475569;padding:2px 7px;border-radius:4px;font-weight:600">
                        {{ $customFileInfo['size_human'] }}
                    </span>
                </div>
                <div style="font-size:0.8rem;color:#64748b">
                    <div>● Total URLs in file: <strong>{{ $customFileInfo['url_count'] }}</strong></div>
                    <div>● Uploaded: <strong>{{ $customFileInfo['modified_at'] }}</strong></div>
                </div>

                <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap">
                    @if($mode !== 'custom')
                        <form method="POST" action="{{ route('admin.sitemap.mode') }}" style="display:inline">
                            @csrf
                            <input type="hidden" name="mode" value="custom">
                            <button type="submit" class="btn btn-sm btn-primary" style="font-size:0.8rem;padding:6px 12px;font-weight:600">
                                Use This Custom File
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.sitemap.deleteCustom') }}" onsubmit="return confirm('Are you sure you want to delete this custom sitemap? Mode will revert to Dynamic.')" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" style="background:#ef4444;color:#fff;border:none;font-size:0.8rem;padding:6px 12px;border-radius:6px;font-weight:600">
                            Delete Custom File
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Upload Form --}}
        <form method="POST" action="{{ route('admin.sitemap.upload') }}" enctype="multipart/form-data">
            @csrf
            <div style="border:2px dashed #cbd5e1;border-radius:10px;padding:20px;text-align:center;background:#f8fafc;margin-bottom:16px;cursor:pointer" onclick="document.getElementById('sitemapFileInput').click()">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" style="margin-bottom:8px">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <div style="font-weight:700;color:#1e293b;font-size:0.88rem">Choose sitemap.xml to upload</div>
                <div style="font-size:0.76rem;color:#64748b;margin-top:2px">Valid .xml files up to 10MB</div>
                <input type="file" name="sitemap_file" id="sitemapFileInput" accept=".xml,text/xml,application/xml" style="display:none" onchange="document.getElementById('fileNameDisplay').innerText = this.files[0] ? this.files[0].name : ''; document.getElementById('uploadSubmitBtn').disabled = !this.files[0]">
                <div id="fileNameDisplay" style="margin-top:8px;font-size:0.82rem;font-weight:700;color:#2563eb"></div>
            </div>

            <button type="submit" id="uploadSubmitBtn" disabled class="btn btn-primary" style="background:#0f172a;border-color:#0f172a;width:100%;font-weight:700;padding:10px;border-radius:8px;font-size:0.88rem">
                Upload &amp; Activate Custom Sitemap
            </button>
        </form>
    </div>
</div>

{{-- Guidelines & Search Console Helpers --}}
<div class="card" style="padding:22px 24px">
    <h3 style="font-size:1.05rem;font-weight:700;color:#0f172a;margin:0 0 12px 0;display:flex;align-items:center;gap:8px">
        <span>ℹ️</span> Webmaster &amp; Search Engine Instructions
    </h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:16px;font-size:0.86rem;color:#475569">
        <div style="background:#f8fafc;padding:14px 16px;border-radius:8px;border:1px solid #e2e8f0">
            <strong style="color:#0f172a">1. Public Endpoint</strong>
            <p style="margin:4px 0 0 0;line-height:1.4">
                Your sitemap is always live at <a href="{{ $sitemapUrl }}" target="_blank" style="color:#2563eb;font-weight:600">{{ $sitemapUrl }}</a> and automatically referenced in <code>/robots.txt</code>.
            </p>
        </div>

        <div style="background:#f8fafc;padding:14px 16px;border-radius:8px;border:1px solid #e2e8f0">
            <strong style="color:#0f172a">2. Browser vs Crawlers</strong>
            <p style="margin:4px 0 0 0;line-height:1.4">
                When opened in a web browser, it renders as an interactive dashboard via XSLT. When requested by Googlebot or Bing, it outputs raw XML.
            </p>
        </div>

        <div style="background:#f8fafc;padding:14px 16px;border-radius:8px;border:1px solid #e2e8f0">
            <strong style="color:#0f172a">3. InspectWP &amp; Testing</strong>
            <p style="margin:4px 0 0 0;line-height:1.4">
                When tested on InspectWP or Search Console, the XML parser receives valid XML headers with zero malformed characters.
            </p>
        </div>
    </div>
</div>

@endsection
