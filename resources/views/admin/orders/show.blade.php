@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number . ' — Admin')

@section('content')

<style>
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
    box-sizing: border-box;
}
.custom-select-styled:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    outline: none !important;
}
.order-update-grid {
    display: grid;
    grid-template-columns: 1.2fr 130px 1.8fr auto;
    gap: 12px;
    align-items: flex-end;
}
@media (max-width: 991px) {
    .order-update-grid {
        grid-template-columns: 1fr 1fr;
    }
    .order-update-grid .notes-col {
        grid-column: span 2;
    }
    .order-update-grid .btn-col {
        grid-column: span 2;
    }
}
@media (max-width: 540px) {
    .order-update-grid {
        grid-template-columns: 1fr;
    }
    .order-update-grid .notes-col,
    .order-update-grid .btn-col {
        grid-column: span 1;
    }
}
@media (max-width: 768px) {
    .order-items-table {
        display: none !important;
    }
    .order-items-mobile {
        display: flex !important;
        flex-direction: column;
        gap: 12px;
    }
}
@media (min-width: 769px) {
    .order-items-table {
        display: block !important;
    }
    .order-items-mobile {
        display: none !important;
    }
}
</style>

<!-- Topbar Header -->
<div class="admin-topbar" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #e2e8f0;">
    <div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px;">
            <h1 class="admin-page-title" style="margin:0;font-size:clamp(1.3rem,2.5vw,1.65rem);font-weight:700;color:#0f172a;">
                Order {{ $order->order_number }}
            </h1>
            @if($order->collection_token)
                <span style="background:#ccfbf1;color:#0f766e;font-size:0.85rem;font-weight:800;padding:4px 12px;border:1px solid #99f6e4;border-radius:8px;display:inline-flex;align-items:center;gap:4px;">
                    🎟 Token: {{ $order->collection_token }}
                </span>
            @endif
            <div>
                {!! $order->status_badge !!}
            </div>
            <div>
                {!! $order->payment_badge !!}
            </div>
        </div>
        <div class="text-sm text-muted" style="color:#64748b;margin:0;">
            Placed on {{ $order->created_at->format('d M Y, h:i A') }} ({{ $order->created_at->diffForHumans() }})
        </div>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-secondary btn-sm" target="_blank" style="font-weight:600;padding:8px 16px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;">
            📄 Invoice PDF
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm" style="font-weight:600;padding:8px 14px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;">
            ← Back to Orders
        </a>
    </div>
</div>

<div class="admin-form-layout" style="margin-bottom:24px;">

    <!-- Left: Order Details & Status Updater -->
    <div>
        <!-- Order Items Card -->
        <div class="card mb-5" style="padding:22px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);margin-bottom:22px;">
            <div style="padding-bottom:14px;border-bottom:1px solid #f1f5f9;margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.05rem;font-weight:700;color:#0f172a;margin:0;">
                    📦 Order Items ({{ $order->items->count() }})
                </h2>
                <span style="font-weight:800;color:#0f766e;font-size:1.1rem;">
                    Total: RM {{ number_format($order->total, 2) }}
                </span>
            </div>

            <!-- Desktop Table (>= 769px) -->
            <div class="order-items-table">
                <div class="table-wrapper" style="border:1px solid #e2e8f0;border-radius:10px;">
                    <table class="table" style="margin:0;width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                                <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">Product</th>
                                <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;">SKU</th>
                                <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;text-align:right;">Unit Price</th>
                                <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;text-align:center;">Qty</th>
                                <th style="padding:12px 16px;font-size:0.8rem;text-transform:uppercase;color:#64748b;text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:12px 16px;">
                                    <div style="font-weight:700;font-size:0.9rem;color:#0f172a;">
                                        {{ $item->product_name }}
                                    </div>
                                </td>
                                <td style="padding:12px 16px;font-size:0.82rem;color:#64748b;">
                                    {{ $item->product_sku ?? '—' }}
                                </td>
                                <td style="padding:12px 16px;text-align:right;font-size:0.88rem;color:#334155;">
                                    RM {{ number_format($item->unit_price, 2) }}
                                </td>
                                <td style="padding:12px 16px;text-align:center;font-weight:600;color:#0f172a;">
                                    {{ $item->quantity }}
                                </td>
                                <td style="padding:12px 16px;text-align:right;font-weight:800;color:#0f766e;">
                                    RM {{ number_format($item->subtotal, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Items Card View (< 769px) -->
            <div class="order-items-mobile">
                @foreach($order->items as $item)
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px;">
                    <div style="font-weight:700;color:#0f172a;font-size:0.92rem;margin-bottom:4px;">
                        {{ $item->product_name }}
                    </div>
                    @if($item->product_sku)
                        <div style="font-size:0.75rem;color:#64748b;margin-bottom:8px;">
                            SKU: {{ $item->product_sku }}
                        </div>
                    @endif
                    <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #e2e8f0;padding-top:8px;font-size:0.84rem;">
                        <span style="color:#64748b;">
                            RM {{ number_format($item->unit_price, 2) }} × <strong>{{ $item->quantity }}</strong>
                        </span>
                        <span style="font-weight:800;color:#0f766e;font-size:0.95rem;">
                            RM {{ number_format($item->subtotal, 2) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Financial Summary Box -->
            <div style="display:flex;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9;">
                <div style="width:100%;max-width:320px;">
                    <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:0.88rem;color:#475569;">
                        <span>Subtotal</span>
                        <span style="font-weight:600;">RM {{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:0.88rem;color:#475569;">
                        <span>Shipping Fee</span>
                        <span style="font-weight:600;">RM {{ number_format($order->shipping_fee, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:10px 0 0;margin-top:6px;border-top:1.5px solid #e2e8f0;font-size:1.1rem;">
                        <span style="font-weight:800;color:#0f172a;">Total</span>
                        <span style="font-weight:800;color:#0f766e;">RM {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Status Card -->
        <div class="card" style="padding:22px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
            <div style="padding-bottom:12px;border-bottom:1px solid #f1f5f9;margin-bottom:16px;">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin:0;">
                    ⚙️ Update Order Status &amp; Fulfillment
                </h3>
            </div>

            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf @method('PATCH')
                
                <div class="order-update-grid">
                    <div>
                        <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:5px;display:block;">Status</label>
                        <select name="status" class="form-control custom-select-styled" style="width:100%;">
                            @foreach(['pending','confirmed','processing','ready','shipped','delivered','cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:5px;display:block;">Shipping (RM)</label>
                        <input type="number" name="shipping_fee" step="0.01" min="0" class="form-control" value="{{ $order->shipping_fee }}" style="height:42px;border-radius:10px;border:1.5px solid #cbd5e1;font-weight:600;width:100%;">
                    </div>

                    <div class="notes-col">
                        <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:5px;display:block;">Internal Admin Notes</label>
                        <input type="text" name="admin_notes" class="form-control" value="{{ $order->admin_notes }}" placeholder="Tracking #, driver note, etc." style="height:42px;border-radius:10px;border:1.5px solid #cbd5e1;width:100%;">
                    </div>

                    <div class="btn-col">
                        <button type="submit" class="btn btn-primary" style="height:42px;padding:0 22px;font-weight:700;border-radius:10px;width:100%;display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                            ✓ Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Right: Customer, Fulfillment, Payment Cards -->
    <div style="display:flex;flex-direction:column;gap:18px;">
        
        <!-- Customer Info -->
        <div class="card" style="padding:22px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
            <div style="padding-bottom:12px;border-bottom:1px solid #f1f5f9;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin:0;">👤 Customer</h3>
                <span class="group-badge group-{{ $order->customer_group }}" style="font-size:0.72rem;padding:2px 8px;">
                    {{ ucfirst($order->customer_group) }}
                </span>
            </div>
            
            <div style="display:flex;flex-direction:column;gap:12px;font-size:0.875rem;">
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Name</div>
                    <div style="font-weight:700;color:#0f172a;margin-top:1px;">{{ $order->customer_name }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Email</div>
                    <div style="color:#334155;word-break:break-all;margin-top:1px;">{{ $order->customer_email }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Phone</div>
                    @if($order->customer_phone)
                        <div style="margin-top:1px;">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" style="color:#16a34a;text-decoration:none;font-weight:600;">
                                💬 {{ $order->customer_phone }}
                            </a>
                        </div>
                    @else
                        <div style="color:#94a3b8;">—</div>
                    @endif
                </div>
                @if($order->user)
                    <div style="margin-top:6px;border-top:1px solid #f1f5f9;padding-top:12px;">
                        <a href="{{ route('admin.customers.show', $order->user) }}" class="btn btn-secondary btn-sm" style="width:100%;text-align:center;justify-content:center;font-weight:600;border-radius:8px;padding:8px 12px;font-size:0.8rem;">
                            View Customer Profile →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Fulfillment Info -->
        <div class="card" style="padding:22px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
            <div style="padding-bottom:12px;border-bottom:1px solid #f1f5f9;margin-bottom:14px;">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin:0;">🚚 Fulfillment</h3>
            </div>
            
            <div style="display:flex;flex-direction:column;gap:12px;font-size:0.875rem;">
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Method</div>
                    <div style="font-weight:700;color:#0f172a;margin-top:2px;">
                        {{ $order->fulfillment_type === 'self_collection' ? '🏪 Self-Collection' : '🚚 Delivery' }}
                    </div>
                </div>

                @if($order->collection_token)
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Pickup Token</div>
                    <div style="margin-top:2px;">
                        <span style="background:#ccfbf1;color:#0f766e;font-weight:800;font-size:0.82rem;padding:3px 10px;border-radius:6px;border:1px solid #99f6e4;">
                            🎟 Token: {{ $order->collection_token }}
                        </span>
                    </div>
                </div>
                @endif

                @if($order->shipping_address && count(array_filter($order->shipping_address)))
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Delivery Address</div>
                    <div style="color:#334155;line-height:1.4;margin-top:2px;">
                        {{ $order->shipping_address['address'] ?? '' }}<br>
                        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postcode'] ?? '' }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Info -->
        <div class="card" style="padding:22px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
            <div style="padding-bottom:12px;border-bottom:1px solid #f1f5f9;margin-bottom:14px;">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin:0;">💳 Payment Details</h3>
            </div>
            
            <div style="display:flex;flex-direction:column;gap:12px;font-size:0.875rem;">
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Payment Status</div>
                    <div style="margin-top:3px;">
                        {!! $order->payment_badge !!}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Payment Method</div>
                    <div style="color:#0f172a;font-weight:600;margin-top:2px;">
                        {{ $order->payment_method ?? 'Online (Stripe)' }}
                    </div>
                </div>

                @if($order->stripe_payment_id)
                <div>
                    <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Stripe Intent ID</div>
                    <div style="font-size:0.75rem;font-family:monospace;word-break:break-all;color:#475569;background:#f8fafc;padding:6px 8px;border-radius:6px;border:1px solid #e2e8f0;margin-top:3px;">
                        {{ $order->stripe_payment_id }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Customer Notes -->
        @if($order->customer_notes)
        <div class="card" style="padding:22px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
            <div style="padding-bottom:10px;border-bottom:1px solid #f1f5f9;margin-bottom:12px;">
                <h3 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin:0;">📝 Customer Notes</h3>
            </div>
            <p style="font-size:0.85rem;color:#475569;margin:0;line-height:1.5;">
                {{ $order->customer_notes }}
            </p>
        </div>
        @endif

    </div>

</div>

@endsection
