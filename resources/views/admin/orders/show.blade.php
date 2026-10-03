@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number . ' — Admin')

@section('content')

<style>
/* Modern Admin Order Details Styles */
.admin-order-container {
    max-width: 1400px;
    margin: 0 auto;
}

.order-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.order-title-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.order-title-row {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.order-main-number {
    font-size: clamp(1.3rem, 2.5vw, 1.7rem);
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
    margin: 0;
}

.token-pill-badge {
    background: #ccfbf1;
    color: #0f766e;
    font-size: 0.85rem;
    font-weight: 800;
    padding: 4px 12px;
    border: 1px solid #99f6e4;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.header-actions-group {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

/* 2-Column Responsive Layout */
.order-show-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}
@media (min-width: 1024px) {
    .order-show-grid {
        grid-template-columns: 2fr 1fr;
        align-items: start;
    }
}

/* Common Card Styling */
.order-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 22px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.order-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 18px;
}

.order-card-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Form Controls */
.custom-select-styled {
    height: 44px !important;
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
    box-sizing: border-box;
    width: 100%;
    transition: all 0.2s ease;
}
.custom-select-styled:focus {
    border-color: #0f766e !important;
    box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15) !important;
    outline: none !important;
}

.order-update-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    align-items: flex-end;
}
.order-update-form-grid .full-span {
    grid-column: 1 / -1;
}

/* Admin Live Stepper */
.admin-stepper-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 20px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.admin-step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    min-width: 90px;
    flex: 1;
}
.admin-step-icon {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 700;
    margin-bottom: 6px;
    transition: all 0.2s ease;
}
.admin-step-item.completed .admin-step-icon {
    background: #10b981;
    color: #ffffff;
    box-shadow: 0 0 0 3px #d1fae5;
}
.admin-step-item.active .admin-step-icon {
    background: #0f766e;
    color: #ffffff;
    box-shadow: 0 0 0 3px #ccfbf1;
    animation: pulseStep 2s infinite;
}
.admin-step-item.upcoming .admin-step-icon {
    background: #e2e8f0;
    color: #94a3b8;
}
.admin-step-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #334155;
    line-height: 1.2;
}
.admin-step-item.upcoming .admin-step-label {
    color: #94a3b8;
    font-weight: 500;
}
.admin-step-line {
    flex: 1;
    height: 3px;
    background: #e2e8f0;
    margin-top: -16px;
    min-width: 24px;
}
.admin-step-line.completed {
    background: #10b981;
}

@keyframes pulseStep {
    0% { box-shadow: 0 0 0 0 rgba(15, 118, 110, 0.4); }
    70% { box-shadow: 0 0 0 8px rgba(15, 118, 110, 0); }
    100% { box-shadow: 0 0 0 0 rgba(15, 118, 110, 0); }
}

/* Items Table */
.order-items-table {
    width: 100%;
    border-collapse: collapse;
}
.order-items-table th {
    background: #f8fafc;
    padding: 12px 14px;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    border-bottom: 1.5px solid #e2e8f0;
}
.order-items-table td {
    padding: 14px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

/* Mobile & Tablet Responsive Styles */
@media (max-width: 768px) {
    .order-items-table-wrapper {
        display: none !important;
    }
    .order-items-mobile-list {
        display: flex !important;
        flex-direction: column;
        gap: 12px;
    }
}
@media (min-width: 769px) {
    .order-items-table-wrapper {
        display: block !important;
    }
    .order-items-mobile-list {
        display: none !important;
    }
}

@media (max-width: 640px) {
    .order-header-bar {
        padding: 16px !important;
        border-radius: 12px !important;
    }
    .header-actions-group {
        width: 100% !important;
        display: flex;
        gap: 8px;
    }
    .header-actions-group .btn {
        flex: 1 1 auto !important;
        justify-content: center !important;
        padding: 8px 10px !important;
        font-size: 0.8rem !important;
    }
    .order-card {
        padding: 16px !important;
        border-radius: 12px !important;
    }
    .admin-stepper-wrap {
        padding: 12px 10px !important;
    }
    .admin-step-item {
        min-width: 76px !important;
    }
    .admin-step-icon {
        width: 30px !important;
        height: 30px !important;
        font-size: 0.8rem !important;
    }
    .admin-step-label {
        font-size: 0.68rem !important;
    }
    .order-update-form-grid {
        grid-template-columns: 1fr !important;
    }
}

.copy-btn-inline {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 3px 8px;
    font-size: 0.72rem;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.15s ease;
}
.copy-btn-inline:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
    color: #0f172a;
}
</style>

<div class="admin-order-container">

    <!-- Top Header Bar -->
    <div class="order-header-bar">
        <div class="order-title-group">
            <div class="order-title-row">
                <h1 class="order-main-number">
                    Order {{ $order->order_number }}
                </h1>
                @if($order->collection_token)
                    <span class="token-pill-badge">
                        🎟 Token: {{ $order->collection_token }}
                    </span>
                @endif
                <div>
                    {!! $order->status_badge !!}
                </div>
                <div>
                    {!! $order->payment_badge !!}
                </div>
                <span class="group-badge group-{{ $order->customer_group }}" style="font-size:0.75rem;padding:3px 10px;border-radius:20px;">
                    {{ ucfirst($order->customer_group) }}
                </span>
            </div>
            <div style="font-size:0.85rem;color:#64748b;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <span>Placed on {{ $order->created_at->format('d M Y, h:i A') }}</span>
                <span>•</span>
                <span>{{ $order->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <div class="header-actions-group">
            <a href="{{ route('checkout.success', ['order' => $order->id]) }}" class="btn btn-secondary btn-sm" target="_blank" style="font-weight:600;padding:8px 14px;border-radius:10px;display:inline-flex;align-items:center;gap:6px;" title="View Customer Live Status & Timeline">
                👁️ Live Customer Page
            </a>
            <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-secondary btn-sm" target="_blank" style="font-weight:600;padding:8px 14px;border-radius:10px;display:inline-flex;align-items:center;gap:6px;" title="Print or Save Invoice PDF">
                📄 Invoice PDF
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm" style="font-weight:600;padding:8px 14px;border-radius:10px;display:inline-flex;align-items:center;gap:6px;">
                ← Back to Orders
            </a>
        </div>
    </div>

    <!-- 2-Column Layout -->
    <div class="order-show-grid">

        <!-- ─── LEFT COLUMN: Items & Status Control ────────────────────────── -->
        <div>

            <!-- Status & Fulfillment Control Card -->
            <div class="order-card">
                <div class="order-card-header">
                    <h2 class="order-card-title">
                        ⚙️ Update Fulfillment &amp; Payment Status
                    </h2>
                    <span style="font-size:0.8rem;color:#64748b;font-weight:600;">
                        Auto-updates customer live tracker
                    </span>
                </div>

                @php
                    $isWalkin = ($order->fulfillment_type === 'self_collection' || $order->customer_group === 'walkin' || !empty($order->collection_token));
                    $isPaid = ($order->payment_status === 'paid');

                    if ($isWalkin) {
                        $s1Done = true;
                        $s2Done = $isPaid || in_array($order->status, ['confirmed', 'payment_confirmed', 'processing', 'preparation', 'ready_collection', 'ready', 'collected', 'delivered']);
                        $s2Active = !$s2Done && in_array($order->status, ['pending', 'payment_pending']);
                        $s3Done = in_array($order->status, ['ready_collection', 'ready', 'collected', 'delivered']);
                        $s3Active = !$s3Done && (in_array($order->status, ['processing', 'preparation']) || ($isPaid && in_array($order->status, ['confirmed', 'payment_confirmed'])));
                        $s4Done = in_array($order->status, ['collected', 'delivered']);
                        $s4Active = !$s4Done && in_array($order->status, ['ready_collection', 'ready']);
                    } else {
                        $s1Done = $isPaid || in_array($order->status, ['confirmed', 'processing', 'preparation', 'ready', 'shipped', 'delivered']);
                        $s1Active = !$s1Done && $order->status === 'pending';
                        $s2Done = in_array($order->status, ['ready', 'shipped', 'delivered']);
                        $s2Active = !$s2Done && in_array($order->status, ['confirmed', 'processing', 'preparation']);
                        $s3Done = ($order->status === 'delivered');
                        $s3Active = !$s3Done && in_array($order->status, ['ready', 'shipped']);
                        $s4Done = ($order->status === 'delivered');
                        $s4Active = false;
                    }
                @endphp

                <!-- Admin Visual Stepper -->
                <div class="admin-stepper-wrap">
                    @if($isWalkin)
                        <div class="admin-step-item {{ $s1Done ? 'completed' : 'active' }}">
                            <div class="admin-step-icon">✓</div>
                            <span class="admin-step-label">Token Created</span>
                        </div>
                        <div class="admin-step-line {{ $s2Done ? 'completed' : '' }}"></div>
                        <div class="admin-step-item {{ $s2Done ? 'completed' : ($s2Active ? 'active' : 'upcoming') }}">
                            <div class="admin-step-icon">{{ $s2Done ? '✓' : '💳' }}</div>
                            <span class="admin-step-label">{{ $isPaid ? 'Paid Online' : 'Pay Counter' }}</span>
                        </div>
                        <div class="admin-step-line {{ $s3Done ? 'completed' : '' }}"></div>
                        <div class="admin-step-item {{ $s3Done ? 'completed' : ($s3Active ? 'active' : 'upcoming') }}">
                            <div class="admin-step-icon">🏬</div>
                            <span class="admin-step-label">Packing at SILC</span>
                        </div>
                        <div class="admin-step-line {{ $s4Done ? 'completed' : '' }}"></div>
                        <div class="admin-step-item {{ $s4Done ? 'completed' : ($s4Active ? 'active' : 'upcoming') }}">
                            <div class="admin-step-icon">{{ $s4Done ? '✓' : ($s4Active ? '📦' : '🎉') }}</div>
                            <span class="admin-step-label">{{ $s4Active ? 'Ready Counter 2' : ($s4Done ? 'Collected' : 'Collection') }}</span>
                        </div>
                    @else
                        <div class="admin-step-item {{ $s1Done ? 'completed' : ($s1Active ? 'active' : 'upcoming') }}">
                            <div class="admin-step-icon">✓</div>
                            <span class="admin-step-label">Placed &amp; Paid</span>
                        </div>
                        <div class="admin-step-line {{ $s2Done ? 'completed' : '' }}"></div>
                        <div class="admin-step-item {{ $s2Done ? 'completed' : ($s2Active ? 'active' : 'upcoming') }}">
                            <div class="admin-step-icon">❄️</div>
                            <span class="admin-step-label">Cold Packing</span>
                        </div>
                        <div class="admin-step-line {{ $s3Done ? 'completed' : '' }}"></div>
                        <div class="admin-step-item {{ $s3Done ? 'completed' : ($s3Active ? 'active' : 'upcoming') }}">
                            <div class="admin-step-icon">🚚</div>
                            <span class="admin-step-label">Out for Delivery</span>
                        </div>
                        <div class="admin-step-line {{ $s4Done ? 'completed' : '' }}"></div>
                        <div class="admin-step-item {{ $s4Done ? 'completed' : 'upcoming' }}">
                            <div class="admin-step-icon">📦</div>
                            <span class="admin-step-label">Delivered</span>
                        </div>
                    @endif
                </div>

                <!-- Update Form -->
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf 
                    @method('PATCH')
                    
                    <div class="order-update-form-grid">
                        <div>
                            <label class="form-label" style="font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px;display:block;">
                                Fulfillment Status
                            </label>
                            <select name="status" class="custom-select-styled">
                                @if($isWalkin)
                                    <optgroup label="🏬 Walk-in / In-Store Collection Lifecycle">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>🎟 Pending (Token Created / Awaiting Payment)</option>
                                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>💳 Confirmed (Paid Online / Verified)</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>🏬 Packing at SILC Counter 2</option>
                                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>📋 Ready for Collection at Counter 2</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>🎉 Order Collected (Completed)</option>
                                    </optgroup>
                                    <optgroup label="🚚 Standard Delivery Lifecycle">
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>🚚 Out for Delivery</option>
                                    </optgroup>
                                @else
                                    <optgroup label="🚚 Standard Delivery Lifecycle">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Pending (Review / Awaiting Payment)</option>
                                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>✓ Confirmed (Payment Verified)</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>❄️ Cold-Chain Packing (In Progress)</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>🚚 Out for Delivery (Refrigerated Logistics)</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>📦 Delivered (Completed)</option>
                                    </optgroup>
                                    <optgroup label="🏬 Walk-in Lifecycle">
                                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>📋 Ready for Pickup</option>
                                    </optgroup>
                                @endif
                                <optgroup label="⚠️ Other">
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>✕ Cancelled</option>
                                </optgroup>
                            </select>
                        </div>

                        <div>
                            <label class="form-label" style="font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px;display:block;">
                                Payment Status
                            </label>
                            <select name="payment_status" class="custom-select-styled">
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>✓ Paid</option>
                                <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>⏳ Unpaid / Due</option>
                                <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>↩ Refunded</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>✕ Failed</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label" style="font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px;display:block;">
                                Shipping Fee (RM)
                            </label>
                            <input type="number" name="shipping_fee" step="0.01" min="0" class="form-control" value="{{ $order->shipping_fee }}" style="height:44px;border-radius:10px;border:1.5px solid #cbd5e1;font-weight:700;width:100%;box-sizing:border-box;padding:0 14px;">
                        </div>

                        <div class="full-span">
                            <label class="form-label" style="font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px;display:block;">
                                Internal Admin Notes &amp; Tracking
                            </label>
                            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                                <input type="text" name="admin_notes" class="form-control" value="{{ $order->admin_notes }}" placeholder="e.g. Cold-truck driver #14, Temperature -18°C verified, collection slot noted..." style="flex:1;min-width:240px;height:44px;border-radius:10px;border:1.5px solid #cbd5e1;box-sizing:border-box;padding:0 14px;">
                                <button type="submit" class="btn btn-primary" style="height:44px;padding:0 24px;font-weight:700;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;gap:6px;white-space:nowrap;">
                                    ✓ Save &amp; Update Status
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Items Card -->
            <div class="order-card">
                <div class="order-card-header">
                    <h2 class="order-card-title">
                        📦 Order Items ({{ $order->items->count() }})
                    </h2>
                    <span style="font-weight:800;color:#0f766e;font-size:1.1rem;">
                        Subtotal: RM {{ number_format($order->subtotal, 2) }}
                    </span>
                </div>

                <!-- Desktop Items Table (>= 769px) -->
                <div class="order-items-table-wrapper" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                    <table class="order-items-table">
                        <thead>
                            <tr>
                                <th style="text-align:left;">Product Details</th>
                                <th style="text-align:left;">SKU</th>
                                <th style="text-align:right;">Unit Price</th>
                                <th style="text-align:center;">Qty</th>
                                <th style="text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            @php
                                $prodThumb = $item->product?->thumbnail ?? ($item->product?->images[0] ?? null);
                            @endphp
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:12px;">
                                        <div style="width:48px;height:48px;flex-shrink:0;border-radius:8px;overflow:hidden;border:1px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:center;">
                                            @if($prodThumb)
                                                <img src="{{ cdn_storage($prodThumb) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.onerror=null;this.parentElement.innerHTML='🐟';">
                                            @else
                                                <span style="font-size:1.2rem;">🐟</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div style="font-weight:700;font-size:0.92rem;color:#0f172a;">
                                                {{ $item->product_name }}
                                            </div>
                                            @if($item->price_group)
                                                <span style="font-size:0.72rem;background:#f1f5f9;color:#475569;padding:2px 6px;border-radius:4px;font-weight:600;">
                                                    Tier: {{ ucfirst($item->price_group) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:0.82rem;color:#64748b;font-family:monospace;">
                                    {{ $item->product_sku ?? '—' }}
                                </td>
                                <td style="text-align:right;font-size:0.9rem;color:#334155;font-weight:600;">
                                    RM {{ number_format($item->unit_price, 2) }}
                                </td>
                                <td style="text-align:center;">
                                    <span style="background:#f1f5f9;color:#0f172a;padding:4px 10px;border-radius:20px;font-weight:700;font-size:0.85rem;border:1px solid #e2e8f0;">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td style="text-align:right;font-weight:800;color:#0f766e;font-size:0.95rem;">
                                    RM {{ number_format($item->subtotal, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Items List (< 769px) -->
                <div class="order-items-mobile-list">
                    @foreach($order->items as $item)
                    @php
                        $prodThumb = $item->product?->thumbnail ?? ($item->product?->images[0] ?? null);
                    @endphp
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;display:flex;gap:12px;align-items:flex-start;">
                        <div style="width:54px;height:54px;border-radius:8px;overflow:hidden;border:1px solid #e2e8f0;background:#ffffff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            @if($prodThumb)
                                <img src="{{ cdn_storage($prodThumb) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.onerror=null;this.parentElement.innerHTML='🐟';">
                            @else
                                <span style="font-size:1.4rem;">🐟</span>
                            @endif
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:700;color:#0f172a;font-size:0.95rem;line-height:1.3;margin-bottom:2px;">
                                {{ $item->product_name }}
                            </div>
                            @if($item->product_sku)
                                <div style="font-size:0.75rem;color:#64748b;margin-bottom:6px;">
                                    SKU: {{ $item->product_sku }}
                                </div>
                            @endif
                            <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #e2e8f0;padding-top:8px;margin-top:6px;font-size:0.86rem;">
                                <span style="color:#64748b;">
                                    RM {{ number_format($item->unit_price, 2) }} × <strong>{{ $item->quantity }}</strong>
                                </span>
                                <span style="font-weight:800;color:#0f766e;font-size:1rem;">
                                    RM {{ number_format($item->subtotal, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Financial Summary Box -->
                <div style="display:flex;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9;">
                    <div style="width:100%;max-width:340px;background:#f8fafc;padding:16px 18px;border-radius:12px;border:1px solid #e2e8f0;">
                        <div style="display:flex;justify-content:space-between;padding:4px 0;font-size:0.88rem;color:#475569;">
                            <span>Subtotal</span>
                            <span style="font-weight:600;">RM {{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:4px 0;font-size:0.88rem;color:#475569;">
                            <span>Shipping &amp; Logistics</span>
                            <span style="font-weight:600;">RM {{ number_format($order->shipping_fee, 2) }}</span>
                        </div>
                        @if((float)$order->discount > 0)
                        <div style="display:flex;justify-content:space-between;padding:4px 0;font-size:0.88rem;color:#16a34a;">
                            <span>Discount</span>
                            <span style="font-weight:600;">- RM {{ number_format($order->discount, 2) }}</span>
                        </div>
                        @endif
                        <div style="display:flex;justify-content:space-between;padding:10px 0 0;margin-top:8px;border-top:1.5px solid #cbd5e1;font-size:1.15rem;">
                            <span style="font-weight:800;color:#0f172a;">Grand Total</span>
                            <span style="font-weight:800;color:#0f766e;">RM {{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ─── RIGHT COLUMN: Customer, Fulfillment, Payment Cards ──────────── -->
        <div>

            <!-- Customer Card -->
            <div class="order-card">
                <div class="order-card-header">
                    <h3 class="order-card-title">
                        👤 Customer Details
                    </h3>
                    <span class="group-badge group-{{ $order->customer_group }}" style="font-size:0.72rem;padding:2px 8px;">
                        {{ ucfirst($order->customer_group) }}
                    </span>
                </div>
                
                <div style="display:flex;flex-direction:column;gap:14px;font-size:0.88rem;">
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Name</div>
                        <div style="font-weight:800;color:#0f172a;font-size:1rem;margin-top:2px;">{{ $order->customer_name }}</div>
                    </div>

                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Email</div>
                        <div style="margin-top:2px;">
                            <a href="mailto:{{ $order->customer_email }}" style="color:#2563eb;text-decoration:none;font-weight:600;word-break:break-all;">
                                {{ $order->customer_email }}
                            </a>
                        </div>
                    </div>

                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Phone &amp; WhatsApp</div>
                        @if($order->customer_phone)
                            <div style="margin-top:4px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <a href="tel:{{ $order->customer_phone }}" style="color:#0f172a;text-decoration:none;font-weight:700;">
                                    📞 {{ $order->customer_phone }}
                                </a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" style="background:#dcfce7;color:#15803d;padding:3px 8px;border-radius:6px;font-size:0.75rem;font-weight:700;text-decoration:none;border:1px solid #bbf7d0;display:inline-flex;align-items:center;gap:4px;">
                                    💬 WhatsApp
                                </a>
                            </div>
                        @else
                            <div style="color:#94a3b8;margin-top:2px;">— No phone provided</div>
                        @endif
                    </div>

                    @if($order->user)
                        <div style="margin-top:4px;border-top:1px solid #f1f5f9;padding-top:12px;">
                            <a href="{{ route('admin.customers.show', $order->user) }}" class="btn btn-secondary btn-sm" style="width:100%;text-align:center;justify-content:center;font-weight:700;border-radius:10px;padding:8px 12px;font-size:0.82rem;">
                                View User Profile (ID #{{ $order->user->id }}) →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Fulfillment & Logistics Card -->
            <div class="order-card">
                <div class="order-card-header">
                    <h3 class="order-card-title">
                        🚚 Fulfillment &amp; Logistics
                    </h3>
                </div>
                
                <div style="display:flex;flex-direction:column;gap:14px;font-size:0.88rem;">
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Fulfillment Method</div>
                        <div style="font-weight:800;color:#0f172a;font-size:0.95rem;margin-top:2px;">
                            {{ $order->fulfillment_type === 'self_collection' ? '🏪 Self-Collection / Walk-in' : '🚚 Standard Cold-Chain Delivery' }}
                        </div>
                    </div>

                    @if($order->collection_token)
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Collection Token</div>
                        <div style="margin-top:4px;display:flex;align-items:center;gap:8px;">
                            <span style="background:#ccfbf1;color:#0f766e;font-weight:800;font-size:0.95rem;padding:4px 12px;border-radius:8px;border:1px solid #99f6e4;">
                                🎟 Token: {{ $order->collection_token }}
                            </span>
                            <button type="button" class="copy-btn-inline" onclick="navigator.clipboard.writeText('{{ $order->collection_token }}'); alert('Token copied!');">
                                Copy
                            </button>
                        </div>
                        <div style="font-size:0.78rem;color:#64748b;margin-top:4px;">
                            📍 SILC Facility Counter 2 Pickup
                        </div>
                    </div>
                    @endif

                    @if($order->collection_date || $order->collection_time)
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Self-Collection Schedule</div>
                        <div style="font-weight:700;color:#0f766e;margin-top:2px;background:#f0fdf4;padding:6px 10px;border-radius:6px;border:1px solid #bbf7d0;">
                            📅 {{ $order->collection_date }} ({{ $order->collection_time }})
                        </div>
                    </div>
                    @endif

                    @if($order->delivery_date)
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Requested Delivery Date</div>
                        <div style="font-weight:700;color:#1e40af;margin-top:2px;background:#eff6ff;padding:6px 10px;border-radius:6px;border:1px solid #bfdbfe;">
                            📅 {{ $order->delivery_date }}
                            <div style="font-size:0.72rem;font-weight:normal;color:#64748b;margin-top:2px;">(Subject to MST Availability &amp; Dispatch Scheduling)</div>
                        </div>
                    </div>
                    @endif

                    @if($order->shipping_address && count(array_filter($order->shipping_address)))
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Shipping Address</div>
                        <div style="background:#f8fafc;padding:12px;border-radius:10px;border:1px solid #e2e8f0;margin-top:4px;line-height:1.45;color:#1e293b;font-weight:500;">
                            <strong>{{ $order->customer_name }}</strong><br>
                            {{ $order->shipping_address['address'] ?? '' }}<br>
                            {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postcode'] ?? '' }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment & Transaction Details Card -->
            <div class="order-card">
                <div class="order-card-header">
                    <h3 class="order-card-title">
                        💳 Payment &amp; Transaction
                    </h3>
                </div>
                
                <div style="display:flex;flex-direction:column;gap:14px;font-size:0.88rem;">
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Payment Status</div>
                        <div style="margin-top:4px;">
                            {!! $order->payment_badge !!}
                        </div>
                    </div>

                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Payment Method</div>
                        <div style="color:#0f172a;font-weight:700;margin-top:2px;">
                            {{ $order->payment_method ? ucfirst($order->payment_method) : 'Online (Stripe)' }}
                        </div>
                    </div>

                    @if($order->stripe_payment_id || $order->stripe_payment_intent)
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Stripe Intent ID</div>
                        <div style="display:flex;align-items:center;gap:6px;margin-top:4px;">
                            <div style="font-size:0.75rem;font-family:monospace;word-break:break-all;color:#334155;background:#f8fafc;padding:6px 8px;border-radius:6px;border:1px solid #e2e8f0;flex:1;">
                                {{ $order->stripe_payment_id ?? $order->stripe_payment_intent }}
                            </div>
                            <button type="button" class="copy-btn-inline" onclick="navigator.clipboard.writeText('{{ $order->stripe_payment_id ?? $order->stripe_payment_intent }}'); alert('Payment ID copied!');">
                                Copy
                            </button>
                        </div>
                    </div>
                    @endif

                    @if($order->paid_at)
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Paid Timestamp</div>
                        <div style="color:#15803d;font-weight:600;font-size:0.82rem;margin-top:2px;">
                            ✓ {{ $order->paid_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Notes (if any) -->
            @if($order->customer_notes)
            <div class="order-card">
                <div class="order-card-header">
                    <h3 class="order-card-title">
                        📝 Customer Notes
                    </h3>
                </div>
                <p style="font-size:0.88rem;color:#334155;margin:0;line-height:1.5;background:#f8fafc;padding:12px;border-radius:10px;border:1px solid #e2e8f0;">
                    {{ $order->customer_notes }}
                </p>
            </div>
            @endif

        </div>

    </div>

</div>

@endsection
