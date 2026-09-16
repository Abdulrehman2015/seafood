@extends('layouts.admin')
@section('title', 'Customer: ' . $user->name . ' — Admin')

@section('content')

<style>
.customer-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media (max-width: 991px) {
    .customer-kpi-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}
@media (max-width: 480px) {
    .customer-kpi-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}
.kpi-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.kpi-title {
    font-size: 0.78rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 4px;
}
.kpi-value {
    font-size: 1.45rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
}
.kpi-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}
.custom-select-styled {
    height: 42px !important;
    border-radius: 10px !important;
    border: 1.5px solid #cbd5e1 !important;
    font-size: 0.88rem !important;
    color: #1e293b !important;
    background-color: #ffffff !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 14px center !important;
    background-size: 16px 16px !important;
    -webkit-appearance: none !important;
    appearance: none !important;
    padding: 0 40px 0 14px !important;
    cursor: pointer;
}
.custom-select-styled:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    outline: none !important;
}
@media (max-width: 768px) {
    .show-table-desktop {
        display: none !important;
    }
    .show-cards-mobile {
        display: flex !important;
        flex-direction: column;
        gap: 12px;
    }
}
@media (min-width: 769px) {
    .show-table-desktop {
        display: block !important;
    }
    .show-cards-mobile {
        display: none !important;
    }
}
</style>

<!-- Page Topbar Header -->
<div class="admin-topbar" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #e2e8f0;">
    <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
        <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.25rem;flex-shrink:0;box-shadow:0 2px 6px rgba(37,99,235,0.25);">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
                <h1 class="admin-page-title" style="margin:0;font-size:clamp(1.3rem,2.5vw,1.65rem);font-weight:700;color:#0f172a;">
                    {{ $user->name }}
                </h1>
                <span class="group-badge group-{{ $user->customer_group }}" style="font-size:0.75rem;padding:3px 10px;">
                    @if($user->customer_group === 'wholesale')
                        🏭 Wholesale (B2B)
                    @elseif($user->customer_group === 'trading')
                        📦 Trading (RFQ)
                    @else
                        🛒 Retail (B2C)
                    @endif
                </span>
                @if($user->isOtpBlocked())
                    <span style="background:#fee2e2;color:#b91c1c;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;border:1px solid #fca5a5;">
                        🔒 OTP Blocked
                    </span>
                @endif
                @if($user->approval_status === 'approved')
                    <span style="background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;border:1px solid #bbf7d0;">
                        ✓ Approved
                    </span>
                @elseif($user->approval_status === 'pending')
                    <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;border:1px solid #fde68a;">
                        ⏳ Pending Review
                    </span>
                @else
                    <span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;border:1px solid #fecaca;">
                        ✕ Rejected
                    </span>
                @endif
            </div>
            <p class="text-sm text-muted" style="margin:0;color:#64748b;">
                Registered on {{ $user->created_at->format('d M Y, h:i A') }} ({{ $user->created_at->diffForHumans() }})
            </p>
        </div>
    </div>
    
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <a href="mailto:{{ $user->email }}" class="btn btn-secondary btn-sm" style="font-weight:600;padding:8px 14px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;">
            ✉ Email
        </a>
        @if($user->phone)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="btn btn-secondary btn-sm" style="color:#16a34a;font-weight:600;padding:8px 14px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;" title="Open WhatsApp Chat">
                💬 WhatsApp
            </a>
        @endif
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm" style="font-weight:600;padding:8px 14px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;">
            ← Back to Customers
        </a>
    </div>
</div>

<!-- Customer KPI Summary Cards -->
<div class="customer-kpi-grid">
    <div class="kpi-card">
        <div>
            <div class="kpi-title">Total Orders</div>
            <div class="kpi-value">{{ number_format($ordersCount) }}</div>
        </div>
        <div class="kpi-icon" style="background:#eff6ff;color:#2563eb;">📦</div>
    </div>

    <div class="kpi-card">
        <div>
            <div class="kpi-title">Total Spent</div>
            <div class="kpi-value" style="color:#0f766e;">RM {{ number_format($totalSpent, 2) }}</div>
        </div>
        <div class="kpi-icon" style="background:#f0fdf4;color:#16a34a;">💰</div>
    </div>

    <div class="kpi-card">
        <div>
            <div class="kpi-title">Quotations / RFQs</div>
            <div class="kpi-value">{{ number_format($quotationsCount) }}</div>
        </div>
        <div class="kpi-icon" style="background:#f5f3ff;color:#7c3aed;">💬</div>
    </div>

    <div class="kpi-card">
        <div>
            <div class="kpi-title">Last Order Date</div>
            <div class="kpi-value" style="font-size:1.05rem;font-weight:700;color:#334155;margin-top:2px;">
                {{ $lastOrder ? $lastOrder->created_at->format('d M Y') : 'No orders yet' }}
            </div>
        </div>
        <div class="kpi-icon" style="background:#fffbeb;color:#d97706;">📅</div>
    </div>
</div>

<!-- OTP Security Lock Alert Banner -->
@if($user->isOtpBlocked())
    <div class="card mb-6" style="border-left:4px solid #ef4444;background:#fef2f2;border-radius:12px;padding:16px 20px;border-top:1px solid #fecaca;border-right:1px solid #fecaca;border-bottom:1px solid #fecaca;margin-bottom:22px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div style="flex:1;min-width:260px;">
                <h3 style="font-size:1.02rem;font-weight:700;color:#991b1b;margin:0 0 4px;display:flex;align-items:center;gap:6px;">
                    <span>🔒</span> Account Blocked (Failed OTP Verification)
                </h3>
                <p class="text-xs text-muted" style="margin:0;color:#7f1d1d;line-height:1.4;">
                    This customer was blocked on {{ $user->email_otp_blocked_at ? $user->email_otp_blocked_at->format('d M Y, h:i A') : 'recently' }} after repeated failed OTP verification attempts.
                    Click <strong>"Unblock &amp; Verify Email"</strong> to instantly clear the block, verify their email address, and grant access to their dashboard.
                </p>
            </div>
            <div>
                <form action="{{ route('admin.customers.unblock', $user) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm" style="background:#15803d;border-color:#15803d;padding:9px 20px;font-weight:700;border-radius:8px;display:inline-flex;align-items:center;gap:6px;">
                        <span>🔓</span> Unblock &amp; Verify Email
                    </button>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- B2B Approval Action Banner (if Wholesale or Trading) -->
@if(in_array($user->customer_group, ['wholesale', 'trading']))
    <div class="card mb-6" style="border-left:4px solid #0d9488;background:#f0fdfa;border-radius:12px;padding:16px 20px;border-top:1px solid #ccfbf1;border-right:1px solid #ccfbf1;border-bottom:1px solid #ccfbf1;margin-bottom:22px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div style="flex:1;min-width:260px;">
                <h3 style="font-size:1.02rem;font-weight:700;color:#0f766e;margin:0 0 4px;display:flex;align-items:center;gap:6px;">
                    <span>🏢</span> B2B Application Status: {{ ucfirst($user->approval_status) }}
                </h3>
                <p class="text-xs text-muted" style="margin:0;color:#475569;line-height:1.4;">
                    @if($user->approval_status === 'pending')
                        Please verify the SSM business registration number and company credentials below before granting wholesale pricing.
                    @elseif($user->approval_status === 'approved')
                        Account is verified for B2B pricing &amp; wholesale catalog access. Approved on {{ $user->approved_at ? $user->approved_at->format('d M Y') : 'N/A' }}.
                    @else
                        Application is rejected. Reason: <strong style="color:#991b1b;">{{ $user->rejection_reason ?? 'Unspecified' }}</strong>
                    @endif
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                @if($user->approval_status !== 'approved')
                    <form action="{{ route('admin.customers.approve', $user) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm" style="padding:8px 18px;font-weight:700;border-radius:8px;">
                            ✓ Approve B2B Access
                        </button>
                    </form>
                @endif
                @if($user->approval_status !== 'rejected')
                    <button type="button" class="btn btn-secondary btn-sm" style="color:#dc2626;border-color:#fecaca;padding:8px 16px;font-weight:600;border-radius:8px;" onclick="document.getElementById('rejectModal').style.display='flex'">
                        ✕ Reject Account
                    </button>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Customer Edit & Details Layout -->
<div class="admin-form-layout" style="margin-bottom:24px;">
    
    <!-- Left: Customer Profile Form -->
    <div class="card" style="padding:22px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
        <div style="padding-bottom:14px;border-bottom:1px solid #f1f5f9;margin-bottom:18px;">
            <h2 style="font-size:1.05rem;font-weight:700;color:#0f172a;margin:0;">
                ✏️ Customer Profile &amp; Account Settings
            </h2>
        </div>

        <form action="{{ route('admin.customers.update', $user) }}" method="POST">
            @csrf @method('PATCH')

            <div class="form-grid-2">
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">Full Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="height:42px;border-radius:10px;border:1.5px solid #cbd5e1;">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">Email Address <span style="color:#ef4444;">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required style="height:42px;border-radius:10px;border:1.5px solid #cbd5e1;">
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">Customer Group <span style="color:#ef4444;">*</span></label>
                    <select name="customer_group" class="form-control custom-select-styled">
                        <option value="retail" {{ old('customer_group', $user->customer_group) == 'retail' ? 'selected' : '' }}>🛒 Retail (B2C Public)</option>
                        <option value="wholesale" {{ old('customer_group', $user->customer_group) == 'wholesale' ? 'selected' : '' }}>🏭 Wholesale (B2B Bulk)</option>
                        <option value="trading" {{ old('customer_group', $user->customer_group) == 'trading' ? 'selected' : '' }}>📦 Trading (RFQ / Quotation)</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">Approval Status <span style="color:#ef4444;">*</span></label>
                    <select name="approval_status" class="form-control custom-select-styled">
                        <option value="approved" {{ old('approval_status', $user->approval_status) == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                        <option value="pending" {{ old('approval_status', $user->approval_status) == 'pending' ? 'selected' : '' }}>⏳ Pending Review</option>
                        <option value="rejected" {{ old('approval_status', $user->approval_status) == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                    </select>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">Phone Number</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+60 12-345 6789" style="height:42px;border-radius:10px;border:1.5px solid #cbd5e1;">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">Company Name (for B2B)</label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $user->company_name) }}" placeholder="e.g. Seafood Delight Sdn Bhd" style="height:42px;border-radius:10px;border:1.5px solid #cbd5e1;">
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">SSM Reg Number</label>
                    <input type="text" name="company_reg_no" class="form-control" value="{{ old('company_reg_no', $user->company_reg_no) }}" placeholder="e.g. 202301012345 (123456-X)" style="height:42px;border-radius:10px;border:1.5px solid #cbd5e1;">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">Business Type</label>
                    <input type="text" name="business_type" class="form-control" value="{{ old('business_type', $user->business_type) }}" placeholder="Restaurant, Supermarket, Distributor, Hotel" style="height:42px;border-radius:10px;border:1.5px solid #cbd5e1;">
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">Street Address</label>
                <textarea name="address" class="form-control" rows="2" placeholder="Full delivery or billing street address" style="border-radius:10px;border:1.5px solid #cbd5e1;">{{ old('address', $user->address) }}</textarea>
            </div>

            <div class="form-grid-3">
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}" placeholder="Kuala Lumpur" style="height:42px;border-radius:10px;border:1.5px solid #cbd5e1;">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state', $user->state) }}" placeholder="Selangor" style="height:42px;border-radius:10px;border:1.5px solid #cbd5e1;">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">Postcode</label>
                    <input type="text" name="postcode" class="form-control" value="{{ old('postcode', $user->postcode) }}" placeholder="50450" style="height:42px;border-radius:10px;border:1.5px solid #cbd5e1;">
                </div>
            </div>

            <div style="margin-top:20px;display:flex;justify-content:flex-end;">
                <button type="submit" class="btn btn-primary" style="font-weight:700;padding:11px 26px;border-radius:10px;display:inline-flex;align-items:center;gap:6px;">
                    💾 Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Right: Account Quick Info Sidebar -->
    <div style="display:flex;flex-direction:column;gap:18px;">
        <div class="card" style="padding:22px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
            <div style="padding-bottom:12px;border-bottom:1px solid #f1f5f9;margin-bottom:16px;">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin:0;">
                    🏢 Account Details
                </h3>
            </div>

            <div style="display:flex;flex-direction:column;gap:14px;font-size:0.875rem;">
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Customer Group</div>
                    <div style="font-weight:700;color:#0f172a;margin-top:2px;">
                        {{ ucfirst($user->customer_group) }} Account
                    </div>
                </div>

                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Account Email</div>
                    <div style="color:#0f172a;word-break:break-all;margin-top:2px;">
                        {{ $user->email }}
                    </div>
                </div>

                @if($user->phone)
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Phone Number</div>
                    <div style="margin-top:2px;">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" style="color:#16a34a;text-decoration:none;font-weight:600;">
                            💬 {{ $user->phone }}
                        </a>
                    </div>
                </div>
                @endif

                @if($user->company_name)
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Company</div>
                    <div style="font-weight:600;color:#0f172a;margin-top:2px;">
                        {{ $user->company_name }}
                    </div>
                </div>
                @endif

                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Registered Date</div>
                    <div style="color:#334155;margin-top:2px;">
                        {{ $user->created_at->format('d M Y, h:i A') }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Email Verification</div>
                    <div style="margin-top:2px;font-weight:600;">
                        @if($user->isEmailVerified())
                            <span style="color:#15803d;">✓ Verified ({{ $user->email_verified_at->format('d M Y') }})</span>
                        @else
                            <span style="color:#b91c1c;">⚠️ Unverified</span>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">OTP Security Status</div>
                    <div style="margin-top:2px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        @if($user->isOtpBlocked())
                            <span style="color:#b91c1c;font-weight:700;">🔒 Blocked</span>
                            <form action="{{ route('admin.customers.unblock', $user) }}" method="POST" style="margin:0;display:inline;">
                                @csrf
                                <button type="submit" style="background:#15803d;color:#ffffff;font-size:0.72rem;font-weight:700;padding:2px 8px;border-radius:6px;border:none;cursor:pointer;">
                                    Unblock
                                </button>
                            </form>
                        @else
                            <span style="color:#15803d;font-weight:600;">✓ Normal ({{ $user->email_otp_attempts }} attempts)</span>
                        @endif
                    </div>
                </div>

                @if($user->approved_at)
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Approved Date</div>
                    <div style="color:#15803d;font-weight:600;margin-top:2px;">
                        {{ $user->approved_at->format('d M Y, h:i A') }}
                    </div>
                </div>
                @endif

                @if($user->rejection_reason)
                <div style="background:#fef2f2;padding:12px;border-radius:10px;border:1px solid #fecaca;margin-top:4px;">
                    <div style="font-size:0.75rem;color:#991b1b;font-weight:700;margin-bottom:2px;">Rejection Note:</div>
                    <div style="font-size:0.82rem;color:#7f1d1d;">{{ $user->rejection_reason }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Orders History Section -->
<div class="card mb-6" style="padding:22px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);margin-bottom:24px;">
    <div style="padding-bottom:14px;border-bottom:1px solid #f1f5f9;margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
        <h3 style="font-size:1.05rem;font-weight:700;color:#0f172a;margin:0;">
            📋 Order History ({{ $user->orders->count() }})
        </h3>
    </div>

    @if($user->orders->count() > 0)
        <!-- Desktop Table (>= 769px) -->
        <div class="show-table-desktop">
            <div class="table-wrapper" style="border:1px solid #e2e8f0;border-radius:10px;">
                <table class="table" style="margin:0;width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Order #</th>
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Date</th>
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Fulfillment</th>
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Status</th>
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Payment</th>
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Total</th>
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->orders as $order)
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:12px 16px;">
                                <span style="font-weight:700;color:#0f766e;font-size:0.88rem;">{{ $order->order_number }}</span>
                                @if($order->collection_token)
                                    <div>
                                        <span style="background:#ccfbf1;color:#0f766e;font-size:0.72rem;font-weight:800;padding:2px 7px;border-radius:4px;">
                                            🎟 Token: {{ $order->collection_token }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td style="padding:12px 16px;font-size:0.84rem;color:#64748b;white-space:nowrap;">
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                            <td style="padding:12px 16px;font-size:0.84rem;white-space:nowrap;">
                                {{ $order->fulfillment_type === 'self_collection' ? '🏪 Collection' : '🚚 Delivery' }}
                            </td>
                            <td style="padding:12px 16px;">{!! $order->status_badge !!}</td>
                            <td style="padding:12px 16px;">{!! $order->payment_badge !!}</td>
                            <td style="padding:12px 16px;font-weight:800;color:#0f766e;white-space:nowrap;">
                                RM {{ number_format($order->total, 2) }}
                            </td>
                            <td style="padding:12px 16px;text-align:right;">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm" style="font-size:0.78rem;padding:5px 12px;font-weight:600;">
                                    View Order
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards (< 769px) -->
        <div class="show-cards-mobile">
            @foreach($user->orders as $order)
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:8px;">
                    <div>
                        <a href="{{ route('admin.orders.show', $order) }}" style="font-weight:700;color:#0f766e;font-size:0.92rem;text-decoration:none;">
                            {{ $order->order_number }}
                        </a>
                        @if($order->collection_token)
                            <div>
                                <span style="background:#ccfbf1;color:#0f766e;font-size:0.7rem;font-weight:800;padding:2px 6px;border-radius:4px;">
                                    🎟 {{ $order->collection_token }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div>{!! $order->status_badge !!}</div>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;font-size:0.8rem;color:#64748b;">
                    <span>{{ $order->fulfillment_type === 'self_collection' ? '🏪 Collection' : '🚚 Delivery' }} &bull; {{ $order->created_at->format('d M Y') }}</span>
                    <span style="font-weight:800;color:#0f766e;font-size:0.95rem;">RM {{ number_format($order->total, 2) }}</span>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #e2e8f0;padding-top:10px;">
                    <div>{!! $order->payment_badge !!}</div>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm" style="font-size:0.78rem;padding:5px 12px;font-weight:600;">
                        View Order
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-muted" style="padding:24px 0;text-align:center;margin:0;color:#94a3b8;">
            This customer has not placed any orders yet.
        </p>
    @endif
</div>

<!-- Quotations History Table (for Trading Customers) -->
@if($user->quotations->count() > 0 || $user->customer_group === 'trading')
<div class="card" style="padding:22px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
    <div style="padding-bottom:14px;border-bottom:1px solid #f1f5f9;margin-bottom:18px;">
        <h3 style="font-size:1.05rem;font-weight:700;color:#0f172a;margin:0;">
            💬 Quotations / RFQs ({{ $user->quotations->count() }})
        </h3>
    </div>

    @if($user->quotations->count() > 0)
        <!-- Desktop Table (>= 769px) -->
        <div class="show-table-desktop">
            <div class="table-wrapper" style="border:1px solid #e2e8f0;border-radius:10px;">
                <table class="table" style="margin:0;width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Quote Ref</th>
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Submitted</th>
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Items</th>
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Status</th>
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Quoted Total</th>
                            <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->quotations as $quote)
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:12px 16px;font-weight:700;color:#0f766e;">
                                {{ $quote->reference_no ?? 'RFQ #' . $quote->id }}
                            </td>
                            <td style="padding:12px 16px;font-size:0.84rem;color:#64748b;white-space:nowrap;">
                                {{ $quote->created_at->format('d M Y') }}
                            </td>
                            <td style="padding:12px 16px;font-size:0.84rem;">{{ $quote->items->count() }} items</td>
                            <td style="padding:12px 16px;">
                                @if($quote->status === 'approved' || $quote->status === 'accepted')
                                    <span class="badge badge-success">{{ ucfirst($quote->status) }}</span>
                                @elseif($quote->status === 'pending')
                                    <span class="badge badge-warning">Pending Review</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($quote->status) }}</span>
                                @endif
                            </td>
                            <td style="padding:12px 16px;font-weight:700;color:#0f766e;white-space:nowrap;">
                                {{ $quote->total_quoted ? 'RM ' . number_format($quote->total_quoted, 2) : 'Awaiting Quote' }}
                            </td>
                            <td style="padding:12px 16px;text-align:right;">
                                <a href="{{ route('admin.quotations.show', $quote) }}" class="btn btn-secondary btn-sm" style="font-size:0.78rem;padding:5px 12px;font-weight:600;">
                                    View RFQ
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards (< 769px) -->
        <div class="show-cards-mobile">
            @foreach($user->quotations as $quote)
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <span style="font-weight:700;color:#0f766e;font-size:0.92rem;">
                        {{ $quote->reference_no ?? 'RFQ #' . $quote->id }}
                    </span>
                    <span>
                        @if($quote->status === 'approved' || $quote->status === 'accepted')
                            <span class="badge badge-success">{{ ucfirst($quote->status) }}</span>
                        @elseif($quote->status === 'pending')
                            <span class="badge badge-warning">Pending Review</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($quote->status) }}</span>
                        @endif
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.8rem;color:#64748b;margin-bottom:10px;">
                    <span>{{ $quote->items->count() }} items &bull; {{ $quote->created_at->format('d M Y') }}</span>
                    <span style="font-weight:700;color:#0f766e;">
                        {{ $quote->total_quoted ? 'RM ' . number_format($quote->total_quoted, 2) : 'Awaiting Quote' }}
                    </span>
                </div>
                <div style="text-align:right;border-top:1px solid #e2e8f0;padding-top:10px;">
                    <a href="{{ route('admin.quotations.show', $quote) }}" class="btn btn-secondary btn-sm" style="width:100%;text-align:center;justify-content:center;font-size:0.8rem;padding:6px 12px;font-weight:600;">
                        View RFQ Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-muted" style="padding:24px 0;text-align:center;margin:0;color:#94a3b8;">
            No quotations submitted by this customer.
        </p>
    @endif
</div>
@endif

<!-- Reject Modal Dialog -->
<div id="rejectModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);z-index:999;align-items:center;justify-content:center;padding:16px;">
    <div class="card" style="max-width:480px;width:100%;background:#ffffff;border-radius:14px;padding:24px;box-shadow:0 10px 30px rgba(0,0,0,0.15);">
        <div style="font-weight:700;font-size:1.15rem;color:#0f172a;margin-bottom:6px;">Reject Customer Application</div>
        <p class="text-xs text-muted mb-4" style="color:#64748b;margin-bottom:16px;">State the reason why this wholesale/trading account is being rejected.</p>

        <form action="{{ route('admin.customers.reject', $user) }}" method="POST">
            @csrf
            <div class="form-group mb-4" style="margin-bottom:16px;">
                <label class="form-label font-bold" style="font-weight:600;color:#334155;margin-bottom:6px;display:block;">Reason for Rejection</label>
                <textarea name="reason" class="form-control" rows="4" placeholder="e.g. Incomplete SSM documentation, business registration could not be verified..." required style="border-radius:10px;border:1.5px solid #cbd5e1;"></textarea>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" class="btn btn-secondary" style="font-weight:600;border-radius:8px;padding:8px 16px;" onclick="document.getElementById('rejectModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-danger" style="font-weight:600;border-radius:8px;padding:8px 16px;">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>

@endsection
