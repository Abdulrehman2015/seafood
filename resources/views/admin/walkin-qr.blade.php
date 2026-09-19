@extends('layouts.admin')
@section('title', 'Walk-in QR Code — Admin')

@section('content')
<div class="no-print">
    <div class="admin-topbar">
        <div>
            <h1 class="admin-page-title">📱 Store Walk-in QR Code</h1>
            <p class="text-sm text-muted">Display this QR code at your physical store counter for walk-in shoppers</p>
        </div>
        <div style="display:flex;gap:var(--space-3);flex-wrap:wrap">
            <a href="{{ route('admin.walkin.qr', ['format' => 'svg']) }}" class="btn btn-secondary">⬇️ Download SVG</a>
            <button type="button" onclick="window.print()" class="btn btn-primary">🖨️ Print Counter Poster</button>
            <a href="{{ $url }}" target="_blank" class="btn btn-secondary">🔗 Open Walk-in Link</a>
        </div>
    </div>
</div>

<!-- Standee / Poster Card Container -->
<div style="display:flex;justify-content:center;padding:var(--space-6) 0">
    <div class="qr-poster-card" style="background:#ffffff;color:#0f172a;max-width:520px;width:100%;border-radius:24px;padding:48px 36px;box-shadow:0 12px 40px rgba(13,148,136,0.15);border:2px solid #ccfbf1;text-align:center">
        <!-- Header -->
        <div style="margin-bottom:24px">
            <img src="{{ asset('images/logo.webp') }}" alt="Meijia" style="height:80px;width:80px;object-fit:contain;margin-bottom:8px;">
            <div style="font-family:var(--font-heading);font-size:1.65rem;font-weight:800;color:#0f766e;letter-spacing:-0.02em">
                MST Import and Export Sdn Bhd
            </div>
            <div style="font-size:0.75rem;font-weight:700;color:#0d9488;letter-spacing:0.12em;text-transform:uppercase;margin-top:4px">
                In-Store Customer Catalogue
            </div>
        </div>

        <!-- Call to Action Banner -->
        <div style="background:#f0fdfa;border:1px solid #99f6e4;border-radius:14px;padding:12px 20px;margin-bottom:28px">
            <div style="font-weight:700;font-size:1.1rem;color:#0f766e">
                📲 Scan to View Store Prices
            </div>
            <div style="font-size:0.85rem;color:#334155;margin-top:2px">
                Browse our fresh inventory & collect at the counter
            </div>
        </div>

        <!-- QR Code Display -->
        <div style="display:inline-block;padding:20px;background:#ffffff;border-radius:18px;border:2px dashed #0d9488;box-shadow:0 4px 16px rgba(0,0,0,0.05);margin-bottom:24px">
            <div style="width:260px;height:260px;margin:0 auto;display:flex;align-items:center;justify-content:center">
                {!! $qrCodeSvg !!}
            </div>
        </div>

        <!-- Instructions -->
        <div style="background:#f8fafc;border-radius:14px;padding:16px;margin-bottom:20px;text-align:left">
            <div style="font-weight:700;font-size:0.85rem;color:#0f766e;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.05em">
                How it works:
            </div>
            <ol style="margin:0;padding-left:20px;font-size:0.85rem;color:#475569;line-height:1.6">
                <li>Open your smartphone camera or QR code scanner.</li>
                <li>Point at this code to open the <strong>Walk-in Catalogue</strong>.</li>
                <li>View exclusive in-store pricing and add items to your cart.</li>
                <li>Self-collect directly at our cashier counter!</li>
            </ol>
        </div>

        <!-- URL Footer -->
        <div style="font-size:0.8rem;color:#64748b">
            Store URL: <span style="font-family:monospace;color:#0d9488;font-weight:600">{{ $url }}</span>
        </div>
    </div>
</div>

<style>
@media print {
    .admin-sidebar, .no-print, .flash-container {
        display: none !important;
    }
    .admin-main {
        padding: 0 !important;
        margin: 0 !important;
        background: #ffffff !important;
    }
    body {
        background: #ffffff !important;
    }
    .qr-poster-card {
        box-shadow: none !important;
        border: 2px solid #0d9488 !important;
        max-width: 100% !important;
        margin: 0 auto !important;
        page-break-inside: avoid;
    }
}
</style>
@endsection
