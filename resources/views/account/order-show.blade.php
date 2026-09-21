@extends('layouts.app')
@section('title', __t('account.order_show_title', 'Order :number', ['number' => $order->order_number]) . ' — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd'))

@section('content')
    <div style="padding-top:80px;padding-bottom:var(--space-16);background:#f8fafc;min-height:calc(100vh - 80px)">
        <div class="order-show-container">
            <!-- Breadcrumb & Top Bar -->
            <div class="order-show-header">
                <div>
                    <div class="breadcrumb" style="margin-bottom:6px">
                        <a href="{{ route('home') }}">@t('account.breadcrumb_home', 'Home')</a>
                        <span class="breadcrumb-sep">/</span>
                        <a href="{{ route('account.dashboard') }}">@t('account.breadcrumb_account', 'My Account')</a>
                        <span class="breadcrumb-sep">/</span>
                        <a href="{{ route('account.orders') }}">@t('account.nav_orders', 'My Orders')</a>
                        <span class="breadcrumb-sep">/</span>
                        <span>{{ $order->order_number }}</span>
                    </div>
                    <div class="order-show-title-wrap">
                        <h1 class="order-show-title">{{ __t('account.order_show_title', 'Order :number', ['number' => $order->order_number]) }}</h1>
                        {!! $order->status_badge !!}
                    </div>
                    <p class="order-show-subtitle">{{ __t('account.order_placed_on', 'Placed on :date', ['date' => $order->created_at->format('d F Y \a\t h:i A')]) }}</p>
                </div>
                <div class="order-header-actions">
                    <a href="{{ route('account.orders') }}" class="btn btn-secondary btn-sm"
                        style="border-radius:10px;display:inline-flex;align-items:center;gap:6px">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>@t('account.back_to_orders', 'Back to Orders')</span>
                    </a>
                    <a href="{{ route('account.orders.invoice', $order) }}" target="_blank" class="btn btn-secondary btn-sm"
                        style="border-radius:10px;font-weight:700;display:inline-flex;align-items:center;gap:6px">
                        <span>@t('account.btn_invoice_pdf', '🖨️ Invoice PDF')</span>
                    </a>
                    <form action="{{ route('account.orders.reorder', $order) }}" method="POST" style="margin:0">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm"
                            style="border-radius:10px;font-weight:700;display:inline-flex;align-items:center;gap:6px">
                            <span>@t('account.btn_reorder_all', '🔄 Reorder All Items')</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Account Sub-navigation Pills -->
            <div class="profile-nav-pills">
                <a href="{{ route('account.dashboard') }}" class="profile-nav-pill">
                    <span>📊</span>
                    <span>@t('account.nav_dashboard', 'Dashboard')</span>
                </a>
                <a href="{{ route('account.orders') }}" class="profile-nav-pill active">
                    <span>📦</span>
                    <span>@t('account.nav_orders', 'My Orders')</span>
                </a>
                <a href="{{ route('account.profile') }}" class="profile-nav-pill">
                    <span>👤</span>
                    <span>@t('account.nav_profile', 'Profile Settings')</span>
                </a>
                @if(auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                    <a href="{{ route('quotations.index') }}" class="profile-nav-pill">
                        <span>📝</span>
                        <span>@t('account.nav_rfqs', 'My RFQs')</span>
                    </a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="profile-nav-pill"
                        style="border-color:#bfdbfe;background:#eff6ff;color:var(--seagreen-700)">
                        <span>⚡</span>
                        <span>@t('account.nav_admin', 'Admin Panel')</span>
                    </a>
                @endif
            </div>

            <div class="order-show-grid">
                <!-- Left Column: Items & Summary -->
                <div>
                    <!-- Items Card -->
                    <div class="card mb-6" style="padding:0;overflow:hidden;border-radius:18px">
                        <div class="card-header"
                            style="padding:20px 24px;margin-bottom:0;border-bottom:1px solid var(--gray-200);background:var(--white)">
                            <div class="card-title"
                                style="margin:0;font-size:1.1rem;display:flex;align-items:center;gap:8px">
                                <span>@t('account.card_order_items', '📦 Order Items')</span>
                                <span class="badge badge-secondary"
                                    style="font-size:0.75rem">{{ $order->items->count() }}</span>
                            </div>
                        </div>

                        <!-- Desktop Table View (> 768px) -->
                        <div class="order-desktop-items-table">
                            <table class="table" style="margin-bottom:0">
                                <thead>
                                    <tr>
                                        <th>@t('account.th_product', 'Product')</th>
                                        <th>@t('account.th_sku', 'SKU')</th>
                                        <th>@t('account.th_unit_price', 'Unit Price')</th>
                                        <th>@t('account.th_quantity', 'Quantity')</th>
                                        <th style="text-align:right">@t('account.th_subtotal', 'Subtotal')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div style="font-weight:700;color:var(--seagreen-900)">{{ $item->product_name }}
                                                </div>
                                                @if($item->product && $item->product->weight)
                                                    <div class="text-xs text-muted">{{ $item->product->weight }} /
                                                        {{ $item->product->unit }}</div>
                                                @endif
                                            </td>
                                            <td class="text-xs text-muted" style="font-family:monospace">
                                                {{ $item->product_sku ?? '—' }}</td>
                                            <td>RM {{ number_format($item->unit_price, 2) }}</td>
                                            <td style="font-weight:600">{{ $item->quantity }}</td>
                                            <td style="text-align:right;font-weight:800;color:var(--seagreen-800)">
                                                RM {{ number_format($item->subtotal, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Items List (<= 768px) -->
                        <div class="order-mobile-items-list">
                            @foreach($order->items as $item)
                                <div class="order-mobile-item-card">
                                    <div class="order-mobile-item-header">
                                        <div>
                                            <div class="order-mobile-item-name">{{ $item->product_name }}</div>
                                            @if($item->product && $item->product->weight)
                                                <div style="font-size:0.75rem;color:#64748b;margin-top:2px">
                                                    {{ $item->product->weight }} / {{ $item->product->unit }}
                                                </div>
                                            @endif
                                            @if($item->product_sku)
                                                <div style="font-size:0.7rem;color:#94a3b8;font-family:monospace;margin-top:1px">
                                                    SKU: {{ $item->product_sku }}
                                                </div>
                                            @endif
                                        </div>
                                        <div
                                            style="font-size:1rem;font-weight:800;color:var(--seagreen-800);white-space:nowrap">
                                            RM {{ number_format($item->subtotal, 2) }}
                                        </div>
                                    </div>
                                    <div class="order-mobile-item-meta">
                                        <span>{{ __t('account.label_unit_price', 'Unit: RM :price', ['price' => number_format($item->unit_price, 2)]) }}</span>
                                        <span style="font-weight:700;background:#f1f5f9;padding:2px 8px;border-radius:6px">{{ __t('account.label_qty', 'Qty: :qty', ['qty' => $item->quantity]) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Financial Summary -->
                        <div class="order-financial-card">
                            <div class="order-financial-wrap">
                                <div class="summary-row"
                                    style="display:flex;justify-content:space-between;font-size:0.9rem;margin-bottom:8px">
                                    <span style="color:#64748b">@t('account.summary_subtotal', 'Subtotal')</span>
                                    <span style="font-weight:600;color:#1e293b">RM
                                        {{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                <div class="summary-row"
                                    style="display:flex;justify-content:space-between;font-size:0.9rem;margin-bottom:8px">
                                    <span style="color:#64748b">@t('account.summary_shipping', 'Shipping / Handling')</span>
                                    <span style="font-weight:600;color:#1e293b">RM
                                        {{ number_format($order->shipping_fee ?? 0, 2) }}</span>
                                </div>
                                <div class="summary-row"
                                    style="display:flex;justify-content:space-between;margin-top:10px;padding-top:12px;border-top:1.5px solid var(--gray-300);align-items:center">
                                    <span style="font-weight:800;font-size:1.05rem;color:var(--seagreen-900)">@t('account.summary_grand_total', 'Grand Total')</span>
                                    <span style="font-weight:900;font-size:1.3rem;color:var(--seagreen-800)">RM
                                        {{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($order->customer_notes)
                        <div class="card" style="border-radius:18px">
                            <div class="card-header" style="margin-bottom:12px;padding-bottom:10px">
                                <div class="card-title" style="font-size:1rem">@t('account.card_customer_notes', '📝 Customer Notes')</div>
                            </div>
                            <p class="text-sm text-secondary" style="line-height:1.6;margin:0">{{ $order->customer_notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Delivery & Payment Details -->
                <div>
                    <!-- Fulfillment card -->
                    <div class="card mb-6" style="border-radius:18px;padding:22px">
                        <div class="card-header" style="margin-bottom:14px;padding-bottom:12px">
                            <div class="card-title" style="font-size:1.05rem">@t('account.card_fulfillment_details', '🚚 Fulfillment Details')</div>
                        </div>
                        <div>
                            @if($order->fulfillment_type === 'self_collection')
                                <div style="margin-bottom:12px">
                                    <span class="badge badge-self-collection" style="font-size:0.8rem;padding:4px 10px">
                                        @t('account.badge_self_collection', '🏪 In-Store Self-Collection')
                                    </span>
                                </div>
                                <p class="text-sm text-secondary" style="line-height:1.6;margin-bottom:0">
                                    <strong>@t('account.pickup_counter_title', 'MST Import and Export Sdn Bhd Counter')</strong><br>
                                    7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
                                    <span class="text-xs text-muted">@t('account.pickup_operating_hours', 'Ready for pickup during store operating hours.')</span>
                                </p>
                            @else
                                <div style="margin-bottom:12px">
                                    <span class="badge badge-delivery" style="font-size:0.8rem;padding:4px 10px">
                                        @t('account.cold_chain_delivery_badge', '🚚 Cold-Chain Delivery')
                                    </span>
                                </div>
                                @if($order->shipping_address)
                                    <p class="text-sm text-secondary" style="line-height:1.6;margin-bottom:0">
                                        <strong>{{ $order->shipping_address['address'] ?? '' }}</strong><br>
                                        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}
                                        {{ $order->shipping_address['postcode'] ?? '' }}
                                    </p>
                                @else
                                    <p class="text-sm text-muted">@t('account.delivery_address_on_file', 'Standard delivery address on file.')</p>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Payment card -->
                    <div class="card" style="border-radius:18px;padding:22px">
                        <div class="card-header" style="margin-bottom:14px;padding-bottom:12px">
                            <div class="card-title" style="font-size:1.05rem">@t('account.card_payment_status', '💳 Payment Status')</div>
                        </div>
                        <div>
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                                <span class="text-muted text-sm">@t('account.label_payment_status', 'Payment Status')</span>
                                {!! $order->payment_badge !!}
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                                <span class="text-muted text-sm">@t('account.label_payment_method', 'Payment Method')</span>
                                <span style="font-weight:700;color:var(--seagreen-900);font-size:0.9rem">
                                    {{ $order->payment_method ? ucfirst($order->payment_method) : __t('account.online_payment', 'Online Payment') }}
                                </span>
                            </div>
                            @if($order->payment_reference)
                                <div style="display:flex;justify-content:space-between;align-items:center">
                                    <span class="text-muted text-sm">@t('account.label_transaction_ref', 'Transaction Ref')</span>
                                    <span class="text-xs text-muted"
                                        style="font-family:monospace;background:#f1f5f9;padding:2px 6px;border-radius:4px">
                                        {{ substr($order->payment_reference, 0, 16) }}...
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection