@extends('layouts.app')
@section('title', 'My Profile — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd'))

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
            <span style="font-weight:600;color:#ffffff">Profile Settings</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    @if($user->customer_group === 'trading')
                        <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                            🏢 B2B Trading Partner Profile
                        </span>
                        <span style="color:#bae6fd;font-size:0.78rem">Commercial Wholesale &amp; Container Supply</span>
                    @elseif($user->customer_group === 'wholesale')
                        <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                            🏢 Wholesale Partner Profile
                        </span>
                        <span style="color:#bae6fd;font-size:0.78rem">Bulk Carton &amp; F&B Supply</span>
                    @else
                        <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                            👤 Member Profile
                        </span>
                        <span style="color:#bae6fd;font-size:0.78rem">Personal Account &amp; Cold-Chain Delivery</span>
                    @endif
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,1.95rem);margin-bottom:4px;letter-spacing:-0.02em">
                    Account Settings &amp; Profile
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.88rem;max-width:680px;line-height:1.4;margin:0">
                    @if($user->customer_group === 'trading')
                        Manage your company trading credentials, SSM registration, contact personnel, and delivery ports.
                    @else
                        Manage your personal information, contact credentials, and default shipping addresses.
                    @endif
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <a href="{{ route('account.dashboard') }}" class="btn btn-secondary btn-sm" style="background:rgba(255,255,255,0.12);color:#ffffff;border:1px solid rgba(255,255,255,0.25);border-radius:10px;font-weight:600">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<div style="padding-top:var(--space-8);padding-bottom:var(--space-16);background:#f8fafc;min-height:calc(100vh - 220px)">
    <div class="profile-container">

        <!-- Account Sub-navigation Pills -->
        <div class="profile-nav-pills">
            <a href="{{ route('account.dashboard') }}" class="profile-nav-pill">
                <span>📊</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('account.orders') }}" class="profile-nav-pill">
                <span>📦</span>
                <span>My Orders</span>
            </a>
            <a href="{{ route('account.profile') }}" class="profile-nav-pill active">
                <span>👤</span>
                <span>Profile Settings</span>
            </a>
            @if(auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                <a href="{{ route('quotations.index') }}" class="profile-nav-pill">
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

        <!-- Account Classification & Status Hero Card -->
        <div class="profile-hero-card">
            <div class="profile-hero-main">
                <div class="profile-user-badge-wrap">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="profile-hero-name">{{ $user->name }}</div>
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                            <span class="group-badge group-{{ $user->customer_group }}" style="font-size:0.75rem;padding:3px 10px;border-radius:6px">
                                {{ ucfirst($user->customer_group) }} Account
                            </span>
                            @if($user->isAdmin())
                                <span style="font-size:0.72rem;background:#fee2e2;color:#991b1b;padding:3px 8px;border-radius:6px;font-weight:700">Admin</span>
                            @endif
                            @if($user->isPending())
                                <span class="badge badge-warning" style="background:#fef3c7;color:#92400e;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:700">Approval Pending</span>
                            @elseif($user->isApproved())
                                <span class="badge badge-success" style="background:#dcfce7;color:#15803d;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:700">✓ Verified &amp; Approved</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="profile-hero-meta">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span>Member since {{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>

            @if($user->company_name || $user->company_reg_no || $user->business_type)
            <div class="profile-info-grid">
                @if($user->company_name)
                <div class="profile-info-tile">
                    <div class="profile-info-tile-label">Registered Company</div>
                    <div class="profile-info-tile-value">{{ $user->company_name }}</div>
                </div>
                @endif
                @if($user->company_reg_no)
                <div class="profile-info-tile">
                    <div class="profile-info-tile-label">Registration No / SSM</div>
                    <div class="profile-info-tile-value">{{ $user->company_reg_no }}</div>
                </div>
                @endif
                @if($user->business_type)
                <div class="profile-info-tile">
                    <div class="profile-info-tile-label">Business Type</div>
                    <div class="profile-info-tile-value">{{ ucfirst(str_replace('_', ' ', $user->business_type)) }}</div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Profile Edit Form Card -->
        <div class="profile-card">
            <form action="{{ route('account.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <!-- Section 1: Personal & Contact Details -->
                <div>
                    <div class="profile-section-header">
                        <div class="profile-section-icon">👤</div>
                        <div class="profile-section-title">Personal &amp; Contact Details</div>
                    </div>
                    <div class="profile-section-sub">Your personal identity and primary phone contact for order delivery notifications.</div>

                    <div class="profile-grid-2">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" style="font-weight:700">Full Name <span class="required" style="color:#ef4444">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" placeholder="e.g. John Tan" required style="border-radius:10px">
                            @error('name')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0">
                            <div style="display:flex;align-items:center;justify-content:space-between">
                                <label class="form-label" style="font-weight:700">Email Address (Login)</label>
                                <span style="font-size:0.75rem;color:#64748b;font-weight:600">🔒 Read-only</span>
                            </div>
                            <input type="email" class="form-control profile-input-readonly" value="{{ $user->email }}" disabled style="border-radius:10px;background:#f1f5f9;cursor:not-allowed">
                            <span class="text-xs text-muted" style="display:block;margin-top:4px;font-size:0.75rem">Primary login email cannot be changed directly</span>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:18px;margin-bottom:0">
                        <label class="form-label" style="font-weight:700">Phone Number (WhatsApp / Calls) <span class="required" style="color:#ef4444">*</span></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 012-345 6789 or +60 12-345 6789" required style="border-radius:10px">
                        <span class="text-xs text-muted" style="display:block;margin-top:4px;font-size:0.75rem">Used by drivers for shipping delivery confirmation and receipt SMS</span>
                        @error('phone')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="profile-divider"></div>

                <!-- Section 2: Default Delivery Address -->
                <div>
                    <div class="profile-section-header">
                        <div class="profile-section-icon">📍</div>
                        <div class="profile-section-title">Default Delivery Address</div>
                    </div>
                    <div class="profile-section-sub">Standard shipping destination applied automatically to your future seafood orders.</div>

                    <div class="form-group" style="margin-bottom:18px">
                        <label class="form-label" style="font-weight:700">Street Address <span class="required" style="color:#ef4444">*</span></label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $user->address) }}" placeholder="e.g. Unit 12-A, Jalan SILC 2/18, Kawasan Perindustrian SILC" required style="border-radius:10px">
                        @error('address')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>

                    <div class="profile-grid-3">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" style="font-weight:700">City <span class="required" style="color:#ef4444">*</span></label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $user->city) }}" placeholder="e.g. Iskandar Puteri" required style="border-radius:10px">
                            @error('city')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" style="font-weight:700">State <span class="required" style="color:#ef4444">*</span></label>
                            <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $user->state) }}" placeholder="e.g. Johor" required style="border-radius:10px">
                            @error('state')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" style="font-weight:700">Postcode <span class="required" style="color:#ef4444">*</span></label>
                            <input type="text" name="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode', $user->postcode) }}" placeholder="e.g. 79200" maxlength="10" required style="border-radius:10px">
                            @error('postcode')<div class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="profile-actions">
                    <a href="{{ route('account.dashboard') }}" class="btn btn-secondary" style="border-radius:10px;padding:12px 20px">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding:12px 32px;border-radius:10px;font-weight:700;box-shadow:0 4px 14px rgba(29, 78, 216, 0.25)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        <span>Save Profile Changes</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.profile-container {
    max-width: 980px;
    margin: 0 auto;
    padding: 0 16px;
}
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

.profile-hero-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 22px 24px;
    margin-bottom: 20px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
}
.profile-hero-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
}
.profile-user-badge-wrap {
    display: flex;
    align-items: center;
    gap: 14px;
}
.profile-avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1d4ed8 0%, #0f172a 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(29, 78, 216, 0.25);
    flex-shrink: 0;
}
.profile-hero-name {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0f172a;
    font-family: var(--font-heading, inherit);
    margin-bottom: 4px;
}
.profile-hero-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #64748b;
    font-size: 0.82rem;
}

.profile-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
}
.profile-info-tile {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
}
.profile-info-tile-label {
    font-size: 0.72rem;
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 2px;
}
.profile-info-tile-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: #0f172a;
}

.profile-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 28px 24px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
}
.profile-section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 4px;
}
.profile-section-icon {
    font-size: 1.3rem;
}
.profile-section-title {
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f172a;
    font-family: var(--font-heading, inherit);
}
.profile-section-sub {
    font-size: 0.82rem;
    color: #64748b;
    margin-bottom: 18px;
    margin-left: 32px;
}
.profile-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.profile-grid-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 16px;
}
.profile-divider {
    height: 1px;
    background: #f1f5f9;
    margin: 24px 0;
}
.profile-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    margin-top: 28px;
    padding-top: 20px;
    border-top: 1px solid #f1f5f9;
}

@media (max-width: 768px) {
    .profile-grid-2,
    .profile-grid-3 {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .profile-section-sub {
        margin-left: 0;
    }
    .profile-hero-main {
        flex-direction: column;
        align-items: flex-start;
    }
    .profile-actions {
        flex-direction: column-reverse;
        width: 100%;
    }
    .profile-actions button,
    .profile-actions a {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}
</style>
@endpush
