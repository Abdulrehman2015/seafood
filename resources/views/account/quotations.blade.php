@extends('layouts.app')
@section('title', 'My Quotation Requests (RFQ) — MST Import and Export Sdn Bhd')

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:4px">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 Home</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <a href="{{ route('account.dashboard') }}" style="color:#bae6fd;text-decoration:none">My Account</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">Quotations &amp; RFQs</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        📋 B2B Trading Desk
                    </span>
                    <span style="color:#bae6fd;font-size:0.78rem">Volume-Tiered Commercial Pricing</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,1.95rem);margin-bottom:4px;letter-spacing:-0.02em">
                    Quotations &amp; RFQ History
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.88rem;max-width:680px;line-height:1.4;margin:0">
                    Review and accept custom quotations for bulk container, pallet, and commercial trading orders.
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <a href="{{ route('account.dashboard') }}" class="btn btn-secondary btn-sm" style="background:rgba(255,255,255,0.12);color:#ffffff;border:1px solid rgba(255,255,255,0.25);border-radius:10px;font-weight:600">
                    ← Dashboard
                </a>
                <a href="{{ route('quotations.create') }}" class="btn btn-primary btn-sm" style="background:#2563eb;color:#ffffff;border:1px solid #3b82f6;border-radius:10px;font-weight:700;box-shadow:0 2px 8px rgba(37,99,235,0.35)">
                    + Request New Quote (RFQ)
                </a>
            </div>
        </div>
    </div>
</div>

<div style="padding-top:var(--space-8);padding-bottom:var(--space-16);background:#f8fafc;min-height:calc(100vh - 220px)">
    <div class="container" style="max-width:1160px">

        <!-- Account Sub-navigation Pills -->
        <div class="profile-nav-pills" style="margin-bottom:20px;display:flex;gap:8px;overflow-x:auto;padding-bottom:6px">
            <a href="{{ route('account.dashboard') }}" class="profile-nav-pill">
                <span>📊</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('account.orders') }}" class="profile-nav-pill">
                <span>📦</span>
                <span>My Orders</span>
            </a>
            <a href="{{ route('account.profile') }}" class="profile-nav-pill">
                <span>👤</span>
                <span>Profile Settings</span>
            </a>
            @if(auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                <a href="{{ route('quotations.index') }}" class="profile-nav-pill active">
                    <span>📝</span>
                    <span>My RFQs</span>
                </a>
            @endif
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="profile-nav-pill" style="border-color:#bfdbfe;background:#eff6ff;color:var(--seagreen-700)">
                    <span>⚡</span>
                    <span>Admin Panel</span>
                </a>
            @endif
        </div>

        <div class="card" style="border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 4px 16px rgba(15,23,42,0.03);overflow:hidden">
            @if($quotations->count() > 0)
                <div class="table-wrapper">
                    <table class="table" style="width:100%;border-collapse:collapse">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0">
                                <th style="padding:14px 16px;font-size:0.8rem;color:#475569;text-transform:uppercase;letter-spacing:0.05em">RFQ Number</th>
                                <th style="padding:14px 16px;font-size:0.8rem;color:#475569;text-transform:uppercase;letter-spacing:0.05em">Submitted Date</th>
                                <th style="padding:14px 16px;font-size:0.8rem;color:#475569;text-transform:uppercase;letter-spacing:0.05em">Items</th>
                                <th style="padding:14px 16px;font-size:0.8rem;color:#475569;text-transform:uppercase;letter-spacing:0.05em">Valid Until</th>
                                <th style="padding:14px 16px;font-size:0.8rem;color:#475569;text-transform:uppercase;letter-spacing:0.05em">Quoted Total</th>
                                <th style="padding:14px 16px;font-size:0.8rem;color:#475569;text-transform:uppercase;letter-spacing:0.05em">Status</th>
                                <th style="padding:14px 16px;font-size:0.8rem;color:#475569;text-transform:uppercase;letter-spacing:0.05em;text-align:right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotations as $quotation)
                            <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s ease">
                                <td style="padding:14px 16px">
                                    <a href="{{ route('quotations.show', $quotation) }}" style="font-weight:700;color:#1d4ed8;text-decoration:none">
                                        {{ $quotation->quotation_number }}
                                    </a>
                                </td>
                                <td style="padding:14px 16px;font-size:0.85rem;color:#64748b">
                                    {{ $quotation->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td style="padding:14px 16px;font-size:0.85rem;color:#334155;font-weight:600">
                                    {{ $quotation->items->count() }} item(s)
                                </td>
                                <td style="padding:14px 16px;font-size:0.85rem">
                                    @if($quotation->valid_until)
                                        <span class="{{ $quotation->valid_until->isPast() ? 'text-danger' : 'text-primary' }}" style="font-weight:600">
                                            {{ $quotation->valid_until->format('d M Y') }}
                                        </span>
                                    @else
                                        <span style="color:#94a3b8">—</span>
                                    @endif
                                </td>
                                <td style="padding:14px 16px;font-weight:800;color:#0f172a">
                                    @if($quotation->total_quoted)
                                        RM {{ number_format($quotation->total_quoted, 2) }}
                                    @else
                                        <span style="color:#64748b;font-weight:normal;font-size:0.85rem">Pending review</span>
                                    @endif
                                </td>
                                <td style="padding:14px 16px">
                                    {!! $quotation->status_badge !!}
                                </td>
                                <td style="padding:14px 16px;text-align:right">
                                    <a href="{{ route('quotations.show', $quotation) }}" class="btn btn-secondary btn-sm" style="border-radius:8px;font-size:0.8rem;padding:6px 12px;font-weight:600">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="padding:16px">
                    {{ $quotations->links() }}
                </div>
            @else
                <div class="empty-state" style="padding:60px 24px;text-align:center">
                    <div style="font-size:3.5rem;margin-bottom:12px">📋</div>
                    <h3 style="font-family:var(--font-heading);font-size:1.4rem;color:#0f172a;margin-bottom:8px">No Quotation Requests</h3>
                    <p style="color:#64748b;font-size:0.9rem;max-width:440px;margin:0 auto 20px;line-height:1.5">
                        Trading and wholesale customers can request custom pricing and cold-chain terms for volume container orders.
                    </p>
                    <a href="{{ route('quotations.create') }}" class="btn btn-primary" style="padding:10px 22px;border-radius:10px;font-weight:700">
                        Submit First RFQ
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.profile-nav-pills {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    overflow-x: auto;
    padding-bottom: 4px;
}
.profile-nav-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    border-radius: 12px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    color: #475569;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
    white-space: nowrap;
}
.profile-nav-pill:hover {
    border-color: #93c5fd;
    color: #1d4ed8;
    background: #eff6ff;
}
.profile-nav-pill.active {
    background: #1d4ed8 !important;
    border-color: #1d4ed8 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(29, 78, 216, 0.25);
}
</style>
@endpush
