@extends('layouts.app')

@php
    $isWalkin = ($order->fulfillment_type === 'self_collection' || $order->customer_group === 'walkin');
    $shippingAddr = $order->shipping_address ?? [];
    $isPaid = ($order->payment_status === 'paid' || $order->payment_method === 'stripe');
    $storeWhatsapp = preg_replace('/[^0-9]/', '', \App\Models\Setting::get('store_whatsapp', '601112710260'));
    $whatsappMsg = urlencode("Hi MST Team, I would like to inquire about my order #" . $order->order_number . " (" . $order->customer_name . ")");
    $whatsappUrl = "https://wa.me/{$storeWhatsapp}?text={$whatsappMsg}";
    $storePhone = \App\Models\Setting::get('store_phone', '+60 11-1271 0260');
    $storeEmail = \App\Models\Setting::get('store_email', 'order@mstseafood.com');
@endphp

@section('title', $isWalkin
    ? __t('checkout.collection_pass_title', 'Collection Pass') . ' #' . ($order->collection_token ?? $order->order_number) . ' — MST'
    : __t('checkout.order_confirmed_title', 'Order Confirmed') . ' #' . $order->order_number . ' — MST')

@section('content')

<!-- ===== HERO HEADER SECTION (ON-SCREEN) ===== -->
<section class="order-success-hero">
    <div class="hero-backdrop-pattern"></div>
    <div class="container hero-inner">
        
        <!-- Breadcrumb Navigation -->
        <nav class="success-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-link">🏠 @t('nav.home', 'Home')</a>
            <span class="breadcrumb-separator">›</span>
            @auth
                <a href="{{ route('account.orders') }}" class="breadcrumb-link">@t('checkout.track_orders', 'My Orders')</a>
            @else
                <a href="{{ route('shop.index') }}" class="breadcrumb-link">@t('nav.shop', 'Products')</a>
            @endauth
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">@t('checkout.order_confirmation', 'Order Confirmed')</span>
        </nav>

        <!-- Hero Main Header Content -->
        <div class="hero-content-grid">
            <div class="hero-left-content">
                <div class="hero-badge-pill">
                    @if($order->payment_method === 'cash')
                        <span class="badge-icon">💵</span>
                        <span class="badge-text">@t('checkout.badge_pay_counter', 'Pay Cash at Counter')</span>
                    @else
                        <span class="badge-icon">✓</span>
                        <span class="badge-text">@t('checkout.payment_confirmed', 'Payment Confirmed & Verified')</span>
                    @endif
                </div>

                <h1 class="hero-title">
                    @if($isWalkin)
                        @if($order->payment_method === 'cash')
                            @t('checkout.order_confirmed_cash', 'In-Store Order Confirmed!')
                        @else
                            @t('checkout.payment_confirmed_title', 'Collection Pass Ready!')
                        @endif
                    @else
                        @t('checkout.order_confirmed_title', 'Thank You! Your Order is Confirmed.')
                    @endif
                </h1>

                <p class="hero-subtitle">
                    @if($isWalkin)
                        @if($order->payment_method === 'cash')
                            @t('checkout.cash_instruction_subtitle', 'Your order is recorded in our system. Please show your collection token at Counter 2 to pay and collect your fresh seafood.')
                        @else
                            @t('checkout.payment_confirmed_desc', 'Your online payment was successful. We are packing your premium frozen seafood for immediate self-collection.')
                        @endif
                    @else
                        @t('checkout.success_subtitle', 'We have received your payment and our cold-chain team is preparing your shipment for express refrigerated delivery.')
                    @endif
                </p>
            </div>

            <!-- Hero Action Buttons -->
            <div class="hero-right-actions">
                <button type="button" onclick="openReceiptModal()" class="hero-action-btn btn-glass">
                    <span>🖨️</span>
                    <span>@t('checkout.print_invoice', 'Print Receipt')</span>
                </button>
                <a href="{{ route('shop.index') }}" class="hero-action-btn btn-solid">
                    <span>@t('checkout.continue_shopping', 'Continue Shopping')</span>
                    <span>→</span>
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ===== MAIN SUCCESS CONTENT AREA (ON-SCREEN) ===== -->
<div class="order-success-page-body">
    <div class="container success-container">

        <!-- ===== ORDER STATUS TIMELINE STEPPER ===== -->
        <div class="success-card status-stepper-card">
            <div class="stepper-header">
                <div class="stepper-header-title">
                    <span class="pulse-indicator"></span>
                    <span>@t('checkout.order_progress', 'Order Progress & Live Status')</span>
                </div>
                <div class="stepper-header-meta">
                    <span class="status-pill-main">{!! $order->status_badge !!}</span>
                </div>
            </div>

            @php
                // Delivery Stepper State Calculation
                $delivStep1Done = $isPaid || in_array($order->status, ['confirmed', 'processing', 'preparation', 'ready', 'shipped', 'delivered']);
                $delivStep1Active = !$delivStep1Done && $order->status === 'pending';
                
                $delivStep2Done = in_array($order->status, ['ready', 'shipped', 'delivered']);
                $delivStep2Active = !$delivStep2Done && in_array($order->status, ['confirmed', 'processing', 'preparation']);

                $delivStep3Done = ($order->status === 'delivered');
                $delivStep3Active = !$delivStep3Done && in_array($order->status, ['ready', 'shipped']);

                $delivStep4Done = ($order->status === 'delivered');

                // Walk-in Stepper State Calculation
                $walkStep1Done = true;

                $walkStep2Done = $isPaid || in_array($order->status, ['confirmed', 'payment_confirmed', 'processing', 'preparation', 'ready_collection', 'ready', 'collected']);
                $walkStep2Active = !$walkStep2Done && in_array($order->status, ['pending', 'payment_pending']);

                $walkStep3Done = in_array($order->status, ['ready_collection', 'ready', 'collected']);
                $walkStep3Active = !$walkStep3Done && (in_array($order->status, ['processing', 'preparation']) || ($isPaid && in_array($order->status, ['confirmed', 'payment_confirmed'])));

                $walkStep4Done = in_array($order->status, ['collected', 'delivered']);
                $walkStep4Active = !$walkStep4Done && in_array($order->status, ['ready_collection', 'ready']);
            @endphp

            @if($order->status === 'cancelled')
                <div style="background:#fee2e2;border:1.5px solid #ef4444;color:#991b1b;padding:12px 18px;border-radius:12px;font-weight:700;display:flex;align-items:center;gap:10px;margin-bottom:12px">
                    <span style="font-size:1.4rem">✕</span>
                    <div>@t('checkout.order_cancelled_notice', 'This order has been marked as Cancelled by store administration.')</div>
                </div>
            @endif

            <div class="order-timeline-stepper">
                @if($isWalkin)
                    <!-- Walk-in Stepper -->
                    <div class="timeline-step {{ $walkStep1Done ? 'step-completed' : 'step-active' }}">
                        <div class="step-icon-node">✓</div>
                        <div class="step-label">@t('walkin.step_order_placed', 'Token Created')</div>
                        <div class="step-time">{{ $order->created_at->format('h:i A') }}</div>
                    </div>
                    <div class="timeline-connector {{ $walkStep2Done ? 'connector-completed' : ($walkStep2Active ? 'connector-active' : '') }}"></div>
                    <div class="timeline-step {{ $walkStep2Done ? 'step-completed' : ($walkStep2Active ? 'step-active' : 'step-upcoming') }}">
                        <div class="step-icon-node">{{ $walkStep2Done ? '✓' : '💳' }}</div>
                        <div class="step-label">{{ $isPaid ? __t('checkout.paid', 'Paid Online') : __t('walkin.pay_counter', 'Pay at Counter') }}</div>
                        <div class="step-time">{{ $isPaid ? __t('checkout.status_verified', 'Verified') : ($walkStep2Active ? __t('checkout.status_pending_pay', 'Pending Pay') : __t('checkout.status_awaiting', 'Awaiting')) }}</div>
                    </div>
                    <div class="timeline-connector {{ $walkStep3Done ? 'connector-completed' : ($walkStep3Active ? 'connector-active' : '') }}"></div>
                    <div class="timeline-step {{ $walkStep3Done ? 'step-completed' : ($walkStep3Active ? 'step-active' : 'step-upcoming') }}">
                        <div class="step-icon-node">🏬</div>
                        <div class="step-label">@t('walkin.step_preparing', 'Packing at SILC')</div>
                        <div class="step-time">{{ $walkStep3Active ? __t('checkout.status_in_progress', 'In Progress') : ($walkStep3Done ? __t('checkout.status_packed', 'Packed') : __t('checkout.status_counter_2', 'Counter 2')) }}</div>
                    </div>
                    <div class="timeline-connector {{ $walkStep4Done ? 'connector-completed' : ($walkStep4Active ? 'connector-active' : '') }}"></div>
                    <div class="timeline-step {{ $walkStep4Done ? 'step-completed' : ($walkStep4Active ? 'step-active' : 'step-upcoming') }}">
                        <div class="step-icon-node">{{ $walkStep4Done ? '✓' : ($walkStep4Active ? '📦' : '🎉') }}</div>
                        <div class="step-label">{{ $walkStep4Active ? __t('walkin.ready_collection', 'Ready at Counter 2') : __t('walkin.step_collected', 'Order Collected') }}</div>
                        <div class="step-time">{{ $walkStep4Done ? __t('checkout.status_collected', 'Collected') : ($walkStep4Active ? __t('checkout.status_ready_now', 'Ready Now') : __t('checkout.status_final_step', 'Final Step')) }}</div>
                    </div>
                @else
                    <!-- Standard Delivery Stepper -->
                    <div class="timeline-step {{ $delivStep1Done ? 'step-completed' : ($delivStep1Active ? 'step-active' : 'step-upcoming') }}">
                        <div class="step-icon-node">✓</div>
                        <div class="step-label">@t('checkout.step_paid', 'Order Placed & Paid')</div>
                        <div class="step-time">{{ $delivStep1Done ? __t('checkout.status_confirmed', 'Confirmed') : __t('checkout.status_pending', 'Pending') }}</div>
                    </div>
                    <div class="timeline-connector {{ $delivStep2Done ? 'connector-completed' : ($delivStep2Active ? 'connector-active' : '') }}"></div>
                    <div class="timeline-step {{ $delivStep2Done ? 'step-completed' : ($delivStep2Active ? 'step-active' : 'step-upcoming') }}">
                        <div class="step-icon-node">❄️</div>
                        <div class="step-label">@t('checkout.step_cold_packing', 'Cold-Chain Packing')</div>
                        <div class="step-time">{{ $delivStep2Active ? __t('checkout.status_in_progress', 'In Progress') : ($delivStep2Done ? __t('checkout.status_packed', 'Packed') : __t('checkout.status_pending', 'Pending')) }}</div>
                    </div>
                    <div class="timeline-connector {{ $delivStep3Done ? 'connector-completed' : ($delivStep3Active ? 'connector-active' : '') }}"></div>
                    <div class="timeline-step {{ $delivStep3Done ? 'step-completed' : ($delivStep3Active ? 'step-active' : 'step-upcoming') }}">
                        <div class="step-icon-node">🚚</div>
                        <div class="step-label">@t('checkout.step_out_delivery', 'Out for Delivery')</div>
                        <div class="step-time">{{ $delivStep3Active ? __t('checkout.status_on_route', 'On Route') : ($delivStep3Done ? __t('checkout.status_dispatched', 'Dispatched') : __t('checkout.status_refrigerated', 'Refrigerated')) }}</div>
                    </div>
                    <div class="timeline-connector {{ $delivStep4Done ? 'connector-completed' : '' }}"></div>
                    <div class="timeline-step {{ $delivStep4Done ? 'step-completed' : 'step-upcoming' }}">
                        <div class="step-icon-node">📦</div>
                        <div class="step-label">@t('checkout.step_delivered', 'Delivered')</div>
                        <div class="step-time">{{ $delivStep4Done ? __t('checkout.status_received', 'Received') : __t('checkout.status_final_step', 'Final Step') }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- ===== TWO-COLUMN ORDER DETAILS GRID ===== -->
        <div class="success-master-grid">

            <!-- LEFT COLUMN: ITEMS & FULFILLMENT -->
            <div class="success-main-column">

                @if($isWalkin)
                    <!-- Walk-in Collection Token Showcase Card -->
                    <div class="success-card walkin-token-card">
                        <div class="token-card-header">
                            <div>
                                <span class="token-super-title">@t('walkin.title', 'Walk-in Collection Pass')</span>
                                <h3 class="token-location-title">🏬 @t('walkin.facility_name', 'MST Cold-Chain Facility · SILC Industrial Park')</h3>
                            </div>
                            <span class="token-tag">@t('walkin.counter_title', 'Counter 2 Collection')</span>
                        </div>

                        <div class="token-display-box">
                            <div class="token-label">@t('checkout.collection_token', 'Your Collection Reference')</div>
                            <div class="token-number-hero">
                                {{ $order->collection_token ?? ('WE-' . str_pad($order->id, 4, '0', STR_PAD_LEFT)) }}
                            </div>
                            
                            <div class="token-qr-wrap">
                                <div class="qr-canvas-box">
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(110)->generate(url('/admin/orders/' . $order->id)) !!}
                                </div>
                                <div class="qr-info-text">
                                    <span class="qr-scan-title">📱 @t('checkout.scan_or_show', 'Show this screen or QR code upon arrival at Counter 2')</span>
                                    <span class="qr-address-sub">📍 @t('common.store_address_silc', '7 Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor')</span>
                                </div>
                            </div>
                        </div>

                        <div class="token-footer-note">
                            💡 @t('checkout.screenshot_hint', 'Please keep this pass open or take a screenshot to present at Counter 2.')
                        </div>
                    </div>
                @endif

                <!-- Itemized Purchased Products Card -->
                <div class="success-card items-summary-card">
                    <div class="card-section-header">
                        <div class="header-left">
                            <span class="section-icon">🛍️</span>
                            <h2 class="section-title">@t('checkout.order_items', 'Order Items')</h2>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px">
                            <button type="button" onclick="openReceiptModal()" class="print-quick-link">
                                🖨️ @t('checkout.view_short_receipt', 'Short Receipt')
                            </button>
                            <span class="item-count-badge">{{ $order->items->count() }} @t('checkout.items_count_label', 'items')</span>
                        </div>
                    </div>

                    <div class="purchased-items-list">
                        @foreach($order->items as $item)
                            <div class="item-product-row">
                                <div class="item-thumb-box">
                                    @php
                                        $prodThumb = $item->product?->thumbnail ?? ($item->product?->images[0] ?? null);
                                    @endphp
                                    @if($prodThumb)
                                        <img src="{{ cdn_storage($prodThumb) }}" alt="{{ $item->product_name }}" onerror="this.onerror=null;this.parentElement.innerHTML='<span class=\'thumb-fallback\'>🐟</span>';">
                                    @else
                                        <span class="thumb-fallback">🐟</span>
                                    @endif
                                    <span class="item-qty-tag">× {{ $item->quantity }}</span>
                                </div>

                                <div class="item-info-col">
                                    <div class="item-title-row">
                                        <h3 class="item-name">{{ $item->product_name }}</h3>
                                        <div class="item-total-price">RM {{ number_format($item->subtotal, 2) }}</div>
                                    </div>
                                    <div class="item-meta-row">
                                        <span class="item-sku">@t('shop.sku_label', 'SKU'): {{ $item->product_sku ?? 'SEA-ITEM' }}</span>
                                        <span class="meta-dot">·</span>
                                        <span class="item-unit-rate">RM {{ number_format($item->unit_price, 2) }} / @t('shop.per_unit', 'unit')</span>
                                    </div>

                                    @if($item->product?->isVariableWeight())
                                        <div class="variable-weight-chip">
                                            <span>⚖️ @t('shop.reference_estimated_weight', 'Reference Weight'): {{ $item->product->getReferenceWeight() }}</span>
                                            <span class="var-weight-note">(@t('shop.variable_weight_checkout_short', 'Actual Final Weight × Unit Price billed upon weighing'))</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($order->customer_notes)
                        <div class="customer-instructions-box">
                            <div class="instructions-header">
                                <span class="notes-icon">📝</span>
                                <strong>@t('checkout.special_notes_label', 'Special Delivery Instructions & Customer Notes')</strong>
                            </div>
                            <p class="instructions-body">{{ $order->customer_notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Fulfillment & Customer Details Card -->
                <div class="success-card fulfillment-details-card">
                    <div class="card-section-header">
                        <div class="header-left">
                            <span class="section-icon">{{ $isWalkin ? '🏬' : '🚚' }}</span>
                            <h2 class="section-title">
                                {{ $isWalkin ? __t('walkin.fulfillment_info', 'Collection Details') : __t('checkout.shipping_info', 'Delivery & Recipient Information') }}
                            </h2>
                        </div>
                        <span class="fulfillment-badge-pill {{ $isWalkin ? 'badge-green' : 'badge-blue' }}">
                            {{ $isWalkin ? '🏪 ' . __t('checkout.store_pickup', 'Self-Collection') : '🚚 ' . __t('checkout.refrigerated_logistics', 'Cold-Chain Delivery') }}
                        </span>
                    </div>

                    <div class="fulfillment-info-grid">
                        <!-- Recipient Details -->
                        <div class="info-block">
                            <div class="info-block-label">@t('checkout.recipient', 'Recipient / Contact Person')</div>
                            <div class="info-block-value-main">{{ $order->customer_name }}</div>
                            <div class="info-block-sub">
                                <span>📞 {{ $order->customer_phone ?: __t('checkout.no_phone_provided', 'No phone provided') }}</span>
                                @if($order->customer_email)
                                    <span class="meta-dot">·</span>
                                    <span>✉️ {{ $order->customer_email }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Destination / Pickup Address -->
                        <div class="info-block">
                            <div class="info-block-label">
                                {{ $isWalkin ? __t('walkin.collection_location', 'Collection Location') : __t('checkout.shipping_address', 'Shipping Address') }}
                            </div>
                            @if($isWalkin)
                                <div class="info-block-value-main">@t('walkin.facility_full_name', 'MST Import and Export Cold-Chain Facility')</div>
                                <div class="info-block-sub">
                                    @t('common.store_address_full', 'No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor Bahru, Johor, Malaysia')
                                </div>
                                @if($order->confirmed_date)
                                    <div style="margin-top:10px;background:#ecfdf5;border:1.5px solid #6ee7b7;border-radius:10px;padding:10px 14px;">
                                        <div style="display:flex;align-items:center;gap:6px;color:#065f46;font-size:0.85rem;font-weight:800;">
                                            <span>✓</span>
                                            <span>@t('checkout.mst_confirmed_collection_date', 'MST Confirmed Collection Date'):</span>
                                        </div>
                                        <div style="font-size:0.95rem;font-weight:800;color:#047857;margin-top:2px;">
                                            📅 {{ $order->confirmed_date }} {{ $order->confirmed_time ? '(' . $order->confirmed_time . ')' : '' }}
                                        </div>
                                        @if($order->notification_notes)
                                            <div style="font-size:0.78rem;color:#065f46;margin-top:4px;">
                                                ℹ️ {{ $order->notification_notes }}
                                            </div>
                                        @endif
                                    </div>
                                @elseif($order->collection_date || $order->collection_time)
                                    <div style="margin-top:6px;font-size:0.82rem;color:#166534;font-weight:700">
                                        📅 @t('checkout.scheduled_pickup', 'Scheduled Collection'): {{ $order->collection_date }} ({{ $order->collection_time }})
                                    </div>
                                @endif
                                <div style="margin-top:8px">
                                    <a href="https://maps.google.com/?q=MST+Counter+2+7+Jalan+SILC+2/18+SILC+Industrial+Park+Iskandar+Puteri+Johor" target="_blank" rel="noopener noreferrer" class="map-link-btn">
                                        <span>📍 @t('walkin.open_maps', 'Open in Google Maps')</span>
                                        <span>↗</span>
                                    </a>
                                </div>
                            @else
                                <div class="info-block-value-main">
                                    {{ $shippingAddr['address'] ?? __t('checkout.address_on_file', 'Address on file') }}
                                </div>
                                <div class="info-block-sub">
                                    {{ implode(', ', array_filter([$shippingAddr['postcode'] ?? null, $shippingAddr['city'] ?? null, $shippingAddr['state'] ?? null])) ?: 'Johor Bahru, Johor' }}
                                </div>
                                @if($order->confirmed_date)
                                    <div style="margin-top:10px;background:#eff6ff;border:1.5px solid #93c5fd;border-radius:10px;padding:10px 14px;">
                                        <div style="display:flex;align-items:center;gap:6px;color:#1e40af;font-size:0.85rem;font-weight:800;">
                                            <span>✓</span>
                                            <span>@t('checkout.mst_confirmed_delivery_date', 'MST Confirmed Delivery Date'):</span>
                                        </div>
                                        <div style="font-size:0.95rem;font-weight:800;color:#1d4ed8;margin-top:2px;">
                                            📅 {{ $order->confirmed_date }}
                                        </div>
                                        @if($order->notification_notes)
                                            <div style="font-size:0.78rem;color:#1e40af;margin-top:4px;">
                                                ℹ️ {{ $order->notification_notes }}
                                            </div>
                                        @endif
                                    </div>
                                @elseif($order->delivery_date)
                                    <div style="margin-top:6px;font-size:0.82rem;color:#1e40af;font-weight:700">
                                        📅 @t('checkout.scheduled_delivery', 'Requested Delivery Date'): {{ $order->delivery_date }}
                                        <div style="font-size:0.75rem;font-weight:normal;color:#64748b">(@t('checkout.delivery_date_subject_mst', 'Subject to MST Cold-Chain Confirmation'))</div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    @if(!$isWalkin)
                        <div class="cold-chain-guarantee-banner">
                            <div class="guarantee-icon">❄️</div>
                            <div class="guarantee-content">
                                <div class="guarantee-title">@t('checkout.cold_chain_promise', 'Strict Cold-Chain Temperature Controlled Logistics')</div>
                                <div class="guarantee-desc">
                                    @t('checkout.cold_chain_desc', 'Your seafood remains preserved at -18°C throughout transport from our SILC freezing chambers directly to your doorstep.')
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <!-- RIGHT COLUMN: FINANCIAL SUMMARY, ORDER CODES & GUEST ACTIONS -->
            <div class="success-sidebar-column">

                <!-- Financial & Receipt Card -->
                <div class="success-card sidebar-receipt-card">
                    <div class="receipt-header">
                        <span class="receipt-title">@t('checkout.payment_receipt', 'Payment & Invoice Details')</span>
                        <span class="payment-method-chip">
                            @if($order->payment_method === 'cash')
                                💵 @t('checkout.cash_method', 'Counter Cash')
                            @else
                                💳 @t('checkout.stripe_method', 'Stripe Hosted')
                            @endif
                        </span>
                    </div>

                    <!-- Order ID & Reference Bar -->
                    <div class="order-ref-copy-box">
                        <div>
                            <div class="ref-label">@t('checkout.order_reference_number', 'Order Reference Number')</div>
                            <div class="ref-code" id="orderRefNumber">{{ $order->order_number }}</div>
                        </div>
                        <button type="button" onclick="copyOrderReference()" class="copy-ref-btn" id="copyRefBtn" title="{{ __t('common.copy_to_clipboard', 'Copy to clipboard') }}">
                            <span id="copyIcon">📋</span>
                            <span id="copyText">@t('common.copy', 'Copy')</span>
                        </button>
                    </div>

                    <!-- Line Item Breakdown -->
                    <div class="receipt-breakdown-table">
                        <div class="receipt-line">
                            <span class="receipt-line-label">@t('checkout.subtotal', 'Items Subtotal')</span>
                            <span class="receipt-line-val">RM {{ number_format($order->subtotal, 2) }}</span>
                        </div>

                        @if($isWalkin)
                            <div class="receipt-line">
                                <span class="receipt-line-label">@t('checkout.fulfillment_type', 'Fulfillment')</span>
                                <span class="receipt-line-val val-free">@t('checkout.free_pickup', 'Free Self-Collection')</span>
                            </div>
                        @else
                            <div class="receipt-line">
                                <span class="receipt-line-label">@t('checkout.shipping_logistics', 'Delivery & Logistics')</span>
                                <span class="receipt-line-val {{ ($order->shipping_fee <= 0) ? 'val-free' : '' }}">
                                    @if($order->shipping_fee <= 0)
                                        @t('checkout.free_standard_delivery', 'Free (Standard Delivery)')
                                    @else
                                        + RM {{ number_format($order->shipping_fee, 2) }}
                                    @endif
                                </span>
                            </div>
                        @endif

                        @if($order->discount > 0)
                            <div class="receipt-line">
                                <span class="receipt-line-label">@t('checkout.discount', 'Applied Discount')</span>
                                <span class="receipt-line-val val-green">- RM {{ number_format($order->discount, 2) }}</span>
                            </div>
                        @endif

                        <div class="receipt-line grand-total-line">
                            <span class="total-label">
                                @if($order->payment_method === 'cash')
                                    @t('checkout.total_due', 'Total Payable at Counter')
                                @else
                                    @t('checkout.total_paid', 'Total Amount Paid')
                                @endif
                            </span>
                            <span class="total-amount-highlight">RM {{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Email Confirmation Notice -->
                    @if(!empty($order->customer_email))
                        <div class="receipt-email-alert">
                            <span class="email-alert-icon">✉️</span>
                            <div class="email-alert-text">
                                @t('checkout.receipt_sent_to', 'Itemized receipt & order confirmation sent to:')
                                <strong>{{ $order->customer_email }}</strong>
                            </div>
                        </div>
                    @endif
                </div>

                @guest
                    <!-- Optional Quick Account Creation (Guest Customers) -->
                    <div class="success-card guest-account-card">
                        <div class="guest-card-sparkle">✨</div>
                        <h3 class="guest-card-title">@t('checkout.create_account_optional_title', 'Create an Account for Faster Future Orders (Optional)')</h3>
                        <p class="guest-card-desc">
                            @t('checkout.create_account_optional_desc', 'Set up an account anytime using :email. All past guest orders placed with this email will automatically sync to your private dashboard!', ['email' => $order->customer_email ?? 'your email'])
                        </p>
                        <a href="{{ route('register', ['email' => $order->customer_email, 'name' => $order->customer_name, 'phone' => $order->customer_phone]) }}" class="guest-register-btn">
                            <span>@t('checkout.create_account_btn', 'Create Free Account (Optional)')</span>
                            <span>→</span>
                        </a>
                    </div>
                @endguest

                <!-- Customer Support & Concierge Card -->
                <div class="success-card support-concierge-card">
                    <div class="concierge-header">
                        <span class="concierge-icon">💬</span>
                        <div>
                            <div class="concierge-title">@t('checkout.need_help_title', 'Need Assistance with your Order?')</div>
                            <div class="concierge-sub">@t('checkout.support_standby', 'MST Customer Service is on standby')</div>
                        </div>
                    </div>
                    <div class="concierge-actions">
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="whatsapp-support-btn">
                            <span>💬 @t('checkout.chat_whatsapp', 'WhatsApp Customer Service')</span>
                            <span>↗</span>
                        </a>
                        <a href="{{ route('contact') }}" class="contact-page-btn">
                            <span>📧 @t('nav.contact', 'Contact Support Page')</span>
                        </a>
                    </div>
                </div>

                <!-- Navigation Quick Actions -->
                <div class="success-card quick-nav-card">
                    @auth
                        <a href="{{ route('account.orders') }}" class="nav-btn-primary">
                            <span>📦 @t('checkout.view_my_orders', 'View All Orders in Dashboard')</span>
                        </a>
                        <a href="{{ route('shop.index') }}" class="nav-btn-outline">
                            <span>🛒 @t('checkout.order_more_seafood', 'Browse Seafood Menu')</span>
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="nav-btn-primary">
                            <span>🏠 @t('checkout.back_to_home', 'Back to Homepage')</span>
                        </a>
                        <a href="{{ route('shop.index') }}" class="nav-btn-outline">
                            <span>🛒 @t('checkout.continue_shopping', 'Continue Shopping')</span>
                        </a>
                    @endauth
                </div>

            </div>

        </div>

    </div>
</div>

<!-- ========================================================================= -->
<!-- PROFESSIONAL SHORT PRINT RECEIPT (THERMAL & 1-PAGE POS SLIP FORMAT)        -->
<!-- Designed to fit perfectly on standard 80mm roll, A5 or A4 single-page.    -->
<!-- ========================================================================= -->
<div class="short-printable-receipt" id="shortPrintableReceipt">
    <div class="receipt-slip-container">
        
        <!-- 1. Store Brand Header -->
        <div class="slip-header text-center">
            <div class="slip-logo-brand">MST IMPORT & EXPORT</div>
            <div class="slip-chinese-brand">镁嘉国际贸易有限公司</div>
            <div class="slip-reg-no">Reg. No: 202101034567 (1434867-X)</div>
            <div class="slip-store-address">
                No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC,<br>
                79200 Iskandar Puteri, Johor Bahru, Johor
            </div>
            <div class="slip-contact-line">
                Tel: {{ $storePhone }} · {{ $storeEmail }}
            </div>
        </div>

        <div class="slip-divider-dashed"></div>

        <!-- 2. Prominent Token / Voucher Reference -->
        <div class="slip-token-section text-center">
            <div class="slip-doc-type">
                {{ $isWalkin ? __t('receipt.slip_instore_title', 'OFFICIAL IN-STORE COLLECTION SLIP') : __t('receipt.slip_delivery_title', 'OFFICIAL ORDER RECEIPT & PACKING SLIP') }}
            </div>

            @if($isWalkin)
                <div class="slip-token-title">@t('receipt.collection_token_header', 'COLLECTION TOKEN')</div>
                <div class="slip-token-huge">
                    {{ $order->collection_token ?? ('WE-' . str_pad($order->id, 4, '0', STR_PAD_LEFT)) }}
                </div>
                <div class="slip-counter-badge">🏬 @t('receipt.present_counter_2', 'PRESENT AT SILC COUNTER 2')</div>
            @else
                <div class="slip-order-num-hero">{{ $order->order_number }}</div>
                <div class="slip-delivery-badge">🚚 @t('receipt.cold_chain_shipment', 'COLD-CHAIN DELIVERY SHIPMENT')</div>
            @endif
        </div>

        <div class="slip-divider-dashed"></div>

        <!-- 3. Key Order Metadata -->
        <div class="slip-meta-grid">
            <div class="slip-meta-row">
                <span class="meta-title">@t('receipt.order_no', 'Order No:')</span>
                <span class="meta-val font-mono">{{ $order->order_number }}</span>
            </div>
            <div class="slip-meta-row">
                <span class="meta-title">@t('receipt.date_time', 'Date & Time:')</span>
                <span class="meta-val">{{ $order->created_at->format('d/m/Y h:i A') }}</span>
            </div>
            <div class="slip-meta-row">
                <span class="meta-title">@t('receipt.customer', 'Customer:')</span>
                <span class="meta-val font-bold">{{ $order->customer_name }}</span>
            </div>
            @if($order->customer_phone)
            <div class="slip-meta-row">
                <span class="meta-title">@t('receipt.mobile', 'Mobile:')</span>
                <span class="meta-val">{{ $order->customer_phone }}</span>
            </div>
            @endif
            <div class="slip-meta-row">
                <span class="meta-title">@t('receipt.payment', 'Payment:')</span>
                <span class="meta-val font-bold">
                    @if($order->payment_method === 'cash')
                        💵 @t('receipt.cash_due_counter', 'CASH DUE AT COUNTER')
                    @else
                        💳 @t('receipt.stripe_paid', 'STRIPE / ONLINE (PAID)')
                    @endif
                </span>
            </div>
            @if(!$isWalkin && !empty($shippingAddr['address']))
            <div class="slip-meta-row">
                <span class="meta-title">@t('receipt.delivery_to', 'Delivery To:')</span>
                <span class="meta-val">
                    {{ $shippingAddr['address'] }}, {{ $shippingAddr['city'] ?? '' }} ({{ $shippingAddr['postcode'] ?? '' }})
                </span>
            </div>
            @endif
        </div>

        <div class="slip-divider-solid"></div>

        <!-- 4. Itemized Product Table (Short & Crisp) -->
        <table class="slip-items-table">
            <thead>
                <tr>
                    <th class="text-left col-item">@t('receipt.th_item', 'ITEM DESCRIPTION')</th>
                    <th class="text-center col-qty">@t('receipt.th_qty', 'QTY')</th>
                    <th class="text-right col-price">@t('receipt.th_price', 'PRICE')</th>
                    <th class="text-right col-total">@t('receipt.th_total', 'TOTAL')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td class="col-item">
                            <div class="slip-item-name font-bold">{{ $item->product_name }}</div>
                            <div class="slip-item-sku text-muted">@t('shop.sku_label', 'SKU'): {{ $item->product_sku ?? 'ITEM' }}</div>
                        </td>
                        <td class="text-center col-qty font-bold">{{ $item->quantity }}</td>
                        <td class="text-right col-price">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right col-total font-bold">{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="slip-divider-solid"></div>

        <!-- 5. Financial Summary Breakdown -->
        <div class="slip-totals-section">
            <div class="slip-total-row">
                <span>@t('receipt.subtotal_count', 'Subtotal (:count items):', ['count' => $order->items->count()])</span>
                <span>RM {{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="slip-total-row">
                <span>{{ $isWalkin ? __t('receipt.pickup_fee', 'Self-Collection Fee:') : __t('receipt.delivery_fee', 'Cold-Chain Delivery:') }}</span>
                <span>
                    @if($order->shipping_fee <= 0)
                        @t('receipt.free', 'FREE')
                    @else
                        RM {{ number_format($order->shipping_fee, 2) }}
                    @endif
                </span>
            </div>
            @if($order->discount > 0)
            <div class="slip-total-row">
                <span>@t('receipt.discount', 'Discount:')</span>
                <span>- RM {{ number_format($order->discount, 2) }}</span>
            </div>
            @endif
            <div class="slip-divider-dashed"></div>
            <div class="slip-total-row slip-grand-total">
                <span class="grand-label">
                    {{ $order->payment_method === 'cash' ? __t('receipt.total_cash_payable', 'TOTAL CASH PAYABLE:') : __t('receipt.total_amount_paid', 'TOTAL AMOUNT PAID:') }}
                </span>
                <span class="grand-val">RM {{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <div class="slip-divider-dashed"></div>

        <!-- 6. QR Code & Rapid Lookup -->
        <div class="slip-qr-section text-center">
            <div class="slip-qr-image">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(84)->generate(url('/admin/orders/' . $order->id)) !!}
            </div>
            <div class="slip-qr-caption">@t('receipt.qr_caption', 'Scan for Digital Tracking & Counter Verification')</div>
        </div>

        <!-- 7. Footer Instructions & Legal -->
        <div class="slip-footer text-center">
            <div class="slip-storage-notice">@t('receipt.storage_notice', '❄️ KEEP FROZEN AT -18°C · COLD-CHAIN ASSURED')</div>
            <div class="slip-thanks">@t('receipt.thank_you', 'Thank you for ordering with MST Import & Export Sdn. Bhd.!')</div>
            <div class="slip-website">www.mstseafood.com · @t('receipt.support_contact', 'Support:'): {{ $storePhone }}</div>
        </div>

    </div>
</div>

<!-- ===== ON-SCREEN INTERACTIVE SHORT RECEIPT MODAL ===== -->
<div id="receiptModalBackdrop" class="receipt-modal-backdrop" onclick="closeReceiptModal(event)">
    <div class="receipt-modal-dialog" onclick="event.stopPropagation()">
        <div class="receipt-modal-header">
            <div class="modal-header-left">
                <span style="font-size:1.2rem">🧾</span>
                <span class="modal-header-title">@t('checkout.short_receipt_preview', 'Official Short Print Receipt')</span>
            </div>
            <button type="button" onclick="closeReceiptModal()" class="modal-close-btn" aria-label="{{ __t('common.close', 'Close') }}">✕</button>
        </div>

        <div class="receipt-modal-body" id="modalReceiptBody">
            <!-- Rendered preview of short receipt slip -->
        </div>

        <div class="receipt-modal-footer">
            <button type="button" onclick="closeReceiptModal()" class="modal-btn-cancel">
                @t('common.close', 'Close')
            </button>
            <button type="button" onclick="window.print()" class="modal-btn-print">
                <span>🖨️</span>
                <span>@t('checkout.print_now', 'Print / Save PDF')</span>
            </button>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ===== WORLD-CLASS ORDER SUCCESS & CONFIRMATION DESIGN SYSTEM ===== */

:root {
    --success-navy-900: #091a36;
    --success-navy-800: #0f274a;
    --success-navy-700: #1e3a8a;
    --success-blue-600: #2563eb;
    --success-blue-500: #3b82f6;
    --success-blue-100: #dbeafe;
    --success-blue-50:  #eff6ff;
    --success-green-600: #16a34a;
    --success-green-500: #22c55e;
    --success-green-100: #dcfce7;
    --success-green-50:  #f0fdf4;
    --success-card-radius: 16px;
    --success-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
    --success-shadow-hover: 0 10px 30px -4px rgba(15, 23, 42, 0.1);
}

/* Base Body Background */
.order-success-page-body {
    background-color: #f8fafc;
    min-height: calc(100vh - 360px);
    padding-top: clamp(20px, 3vw, 36px);
    padding-bottom: clamp(40px, 6vw, 80px);
}

.success-container {
    max-width: 1240px;
    margin: 0 auto;
    padding-left: clamp(14px, 3vw, 24px);
    padding-right: clamp(14px, 3vw, 24px);
}

/* ===== HERO HEADER SECTION ===== */
.order-success-hero {
    padding-top: calc(75px + clamp(16px, 2.5vw, 28px));
    padding-bottom: clamp(24px, 3.5vw, 40px);
    background: linear-gradient(135deg, var(--success-navy-900) 0%, var(--success-navy-800) 50%, var(--success-navy-700) 100%);
    color: #ffffff;
    border-bottom: 1px solid rgba(59, 130, 246, 0.25);
    position: relative;
    overflow: hidden;
}

.hero-backdrop-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.07;
    background-image: radial-gradient(#38bdf8 1.2px, transparent 1.2px);
    background-size: 24px 24px;
    pointer-events: none;
}

.hero-inner {
    position: relative;
    z-index: 2;
}

.success-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.82rem;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.breadcrumb-link {
    color: #bae6fd;
    text-decoration: none;
    transition: color 0.2s ease;
}
.breadcrumb-link:hover {
    color: #ffffff;
    text-decoration: underline;
}
.breadcrumb-separator {
    color: #60a5fa;
}
.breadcrumb-current {
    color: #ffffff;
    font-weight: 600;
}

.hero-content-grid {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    flex-wrap: wrap;
}

.hero-left-content {
    max-width: 760px;
}

.hero-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(56, 189, 248, 0.16);
    border: 1px solid rgba(125, 211, 252, 0.35);
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #7dd3fc;
    letter-spacing: 0.03em;
    margin-bottom: 10px;
}

.hero-title {
    font-family: var(--font-heading);
    font-size: clamp(1.45rem, 3.2vw, 2.15rem);
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 8px 0;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: clamp(0.88rem, 1.6vw, 0.98rem);
    color: #e0f2fe;
    line-height: 1.5;
    margin: 0;
    max-width: 680px;
}

.hero-right-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.hero-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.hero-action-btn.btn-glass {
    background: rgba(255, 255, 255, 0.12);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(8px);
}
.hero-action-btn.btn-glass:hover {
    background: rgba(255, 255, 255, 0.22);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-1px);
}

.hero-action-btn.btn-solid {
    background: #2563eb;
    color: #ffffff;
    border: 1px solid #3b82f6;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
}
.hero-action-btn.btn-solid:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

/* ===== CARDS ARCHITECTURE ===== */
.success-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: var(--success-card-radius);
    box-shadow: var(--success-shadow);
    padding: clamp(16px, 2.5vw, 24px);
    margin-bottom: clamp(16px, 2.5vw, 24px);
    transition: box-shadow 0.2s ease;
}
.success-card:hover {
    box-shadow: var(--success-shadow-hover);
}

/* ===== ORDER STATUS TIMELINE STEPPER ===== */
.status-stepper-card {
    padding: clamp(16px, 2vw, 22px) clamp(16px, 2.5vw, 28px);
    border-left: 4px solid var(--success-blue-600);
}

.stepper-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 10px;
}

.stepper-header-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 0.95rem;
    color: #0f172a;
}

.pulse-indicator {
    width: 10px;
    height: 10px;
    background: #22c55e;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    animation: pulseDot 2s infinite;
}

.order-timeline-stepper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow-x: auto;
    scrollbar-width: none;
    padding-bottom: 4px;
}
.order-timeline-stepper::-webkit-scrollbar { display: none; }

.timeline-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    min-width: 90px;
    z-index: 2;
}

.step-icon-node {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.9rem;
    margin-bottom: 6px;
    transition: all 0.2s ease;
}

.step-completed .step-icon-node {
    background: #dcfce7;
    color: #15803d;
    border: 2px solid #86efac;
}

.step-active .step-icon-node {
    background: #dbeafe;
    color: #1d4ed8;
    border: 2px solid #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.18);
    animation: pulseNode 2s infinite;
}

.step-upcoming .step-icon-node {
    background: #f1f5f9;
    color: #94a3b8;
    border: 2px solid #e2e8f0;
}

.step-label {
    font-size: 0.78rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}

.step-time {
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 2px;
}

.timeline-connector {
    flex: 1;
    height: 3px;
    background: #e2e8f0;
    margin: 0 8px 24px 8px;
    position: relative;
    top: -10px;
    z-index: 1;
}
.timeline-connector.connector-completed {
    background: #22c55e;
}
.timeline-connector.connector-active {
    background: linear-gradient(90deg, #22c55e 0%, #3b82f6 100%);
}

/* ===== TWO-COLUMN MASTER GRID ===== */
.success-master-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: clamp(16px, 2.5vw, 24px);
    align-items: start;
}

@media (min-width: 992px) {
    .success-master-grid {
        grid-template-columns: 1.35fr 1fr;
    }
}

/* ===== SECTION HEADERS ===== */
.card-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 8px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-icon {
    font-size: 1.25rem;
}

.section-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    font-family: var(--font-heading);
}

.print-quick-link {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    font-size: 0.74rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.print-quick-link:hover {
    background: #dbeafe;
}

.item-count-badge {
    background: #f1f5f9;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
}

/* ===== PURCHASED ITEMS LIST ===== */
.purchased-items-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.item-product-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 10px 12px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.2s ease;
}
.item-product-row:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.item-thumb-box {
    width: 60px;
    height: 60px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    position: relative;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.item-thumb-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.thumb-fallback {
    font-size: 1.7rem;
}

.item-qty-tag {
    position: absolute;
    bottom: -2px;
    right: -2px;
    background: #0f172a;
    color: #ffffff;
    font-size: 0.65rem;
    font-weight: 800;
    padding: 1px 6px;
    border-radius: 6px;
    border: 1.5px solid #ffffff;
}

.item-info-col {
    flex: 1;
    min-width: 0;
}

.item-title-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
}

.item-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    line-height: 1.35;
    word-break: break-word;
}

.item-total-price {
    font-size: 0.92rem;
    font-weight: 800;
    color: #0f172a;
    white-space: nowrap;
}

.item-meta-row {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: #64748b;
    margin-top: 3px;
    flex-wrap: wrap;
}

.meta-dot {
    color: #cbd5e1;
}

.variable-weight-chip {
    margin-top: 6px;
    background: #fef3c7;
    border: 1px solid #fde68a;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 0.72rem;
    color: #92400e;
    font-weight: 600;
    line-height: 1.3;
}
.var-weight-note {
    display: block;
    font-size: 0.68rem;
    color: #78350f;
    font-weight: normal;
    margin-top: 1px;
}

/* Customer Notes Box */
.customer-instructions-box {
    margin-top: 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-left: 3px solid #f59e0b;
    padding: 10px 14px;
    border-radius: 8px;
}
.instructions-header {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    color: #92400e;
    margin-bottom: 4px;
}
.instructions-body {
    font-size: 0.82rem;
    color: #475569;
    margin: 0;
    line-height: 1.4;
}

/* ===== FULFILLMENT & RECIPIENT CARD ===== */
.fulfillment-info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

@media (min-width: 640px) {
    .fulfillment-info-grid {
        grid-template-columns: 1fr 1.2fr;
    }
}

.info-block {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 12px 14px;
    border-radius: 12px;
}

.info-block-label {
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 4px;
}

.info-block-value-main {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.35;
}

.info-block-sub {
    font-size: 0.78rem;
    color: #475569;
    margin-top: 4px;
    line-height: 1.4;
}

.fulfillment-badge-pill {
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
}
.badge-blue {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}
.badge-green {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.map-link-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #1d4ed8;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    padding: 4px 10px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.map-link-btn:hover {
    background: #dbeafe;
}

.cold-chain-guarantee-banner {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%);
    border: 1px solid #bfdbfe;
    padding: 12px 16px;
    border-radius: 12px;
    margin-top: 16px;
}
.guarantee-icon {
    font-size: 1.4rem;
    flex-shrink: 0;
}
.guarantee-title {
    font-size: 0.82rem;
    font-weight: 800;
    color: #1e3a8a;
    margin-bottom: 2px;
}
.guarantee-desc {
    font-size: 0.76rem;
    color: #475569;
    line-height: 1.4;
}

/* ===== WALK-IN DIGITAL COLLECTION PASS CARD ===== */
.walkin-token-card {
    border: 2px solid #93c5fd;
    background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%);
    padding: 0;
    overflow: hidden;
}

.token-card-header {
    background: linear-gradient(135deg, var(--success-navy-900), var(--success-navy-700));
    color: #ffffff;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.token-super-title {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #7dd3fc;
    display: block;
}
.token-location-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #ffffff;
    margin: 2px 0 0 0;
}
.token-tag {
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
}

.token-display-box {
    text-align: center;
    padding: 24px 20px;
    border-bottom: 2px dashed #bfdbfe;
}
.token-label {
    font-size: 0.82rem;
    font-weight: 800;
    color: #1d4ed8;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 6px;
}
.token-number-hero {
    display: inline-block;
    background: #dbeafe;
    color: #1e3a8a;
    font-family: var(--font-heading);
    font-size: clamp(2.2rem, 5vw, 3.2rem);
    font-weight: 900;
    letter-spacing: 3px;
    line-height: 1;
    padding: 10px 28px;
    border-radius: 14px;
    border: 2px solid #93c5fd;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.14);
    margin-bottom: 18px;
}

.token-qr-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    max-width: 440px;
    margin: 0 auto;
    text-align: left;
    flex-wrap: wrap;
}
.qr-canvas-box {
    background: #ffffff;
    padding: 8px;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.qr-info-text {
    flex: 1;
    min-width: 180px;
}
.qr-scan-title {
    display: block;
    font-size: 0.82rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.35;
    margin-bottom: 4px;
}
.qr-address-sub {
    display: block;
    font-size: 0.74rem;
    color: #64748b;
    line-height: 1.35;
}

.token-footer-note {
    background: #f8fafc;
    padding: 10px 18px;
    text-align: center;
    font-size: 0.75rem;
    color: #64748b;
}

/* ===== RIGHT COLUMN: RECEIPT & SIDEBAR ===== */
.sidebar-receipt-card {
    position: sticky;
    top: 20px;
}

.receipt-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
}
.receipt-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0f172a;
    font-family: var(--font-heading);
}

.payment-method-chip {
    background: #f1f5f9;
    color: #334155;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}

/* Reference Bar */
.order-ref-copy-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    gap: 10px;
}
.ref-label {
    font-size: 0.68rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.ref-code {
    font-size: 0.92rem;
    font-weight: 800;
    color: #1d4ed8;
    font-family: monospace;
    letter-spacing: 0.5px;
}

.copy-ref-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #334155;
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}
.copy-ref-btn:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
}

/* Receipt Breakdown Table */
.receipt-breakdown-table {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 16px;
}

.receipt-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    color: #475569;
}
.receipt-line-label {
    color: #64748b;
}
.receipt-line-val {
    font-weight: 600;
    color: #0f172a;
}
.val-free {
    color: #16a34a;
    font-weight: 700;
}
.val-green {
    color: #16a34a;
    font-weight: 700;
}

.receipt-line.grand-total-line {
    border-top: 2px solid #f1f5f9;
    padding-top: 12px;
    margin-top: 4px;
}
.total-label {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0f172a;
    font-family: var(--font-heading);
}
.total-amount-highlight {
    font-size: 1.25rem;
    font-weight: 900;
    color: #1e3a8a;
    font-family: var(--font-heading);
}

.receipt-email-alert {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 10px 12px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 0.78rem;
    color: #166534;
    line-height: 1.4;
}
.email-alert-icon {
    font-size: 1rem;
    flex-shrink: 0;
}

/* ===== GUEST QUICK ACCOUNT CARD ===== */
.guest-account-card {
    background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
    border: 1.5px dashed #93c5fd;
    text-align: center;
    padding: 20px 18px;
}
.guest-card-sparkle {
    font-size: 1.4rem;
    margin-bottom: 6px;
}
.guest-card-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: #1e40af;
    margin: 0 0 6px 0;
}
.guest-card-desc {
    font-size: 0.78rem;
    color: #475569;
    line-height: 1.45;
    margin: 0 0 14px 0;
}
.guest-register-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #2563eb;
    color: #ffffff;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 9px 20px;
    border-radius: 999px;
    text-decoration: none;
    box-shadow: 0 3px 10px rgba(37, 99, 235, 0.3);
    transition: all 0.2s ease;
}
.guest-register-btn:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

/* ===== SUPPORT & QUICK NAV CARDS ===== */
.support-concierge-card {
    padding: 16px 18px;
}
.concierge-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}
.concierge-icon {
    font-size: 1.3rem;
}
.concierge-title {
    font-size: 0.85rem;
    font-weight: 800;
    color: #0f172a;
}
.concierge-sub {
    font-size: 0.72rem;
    color: #64748b;
}

.concierge-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.whatsapp-support-btn {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #22c55e;
    color: #ffffff;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 9px 14px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.whatsapp-support-btn:hover {
    background: #16a34a;
}
.contact-page-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #334155;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 8px 12px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.contact-page-btn:hover {
    background: #f1f5f9;
}

.quick-nav-card {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 16px 18px;
}
.nav-btn-primary {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0f172a;
    color: #ffffff;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 10px 14px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.nav-btn-primary:hover {
    background: #1e293b;
}
.nav-btn-outline {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    color: #334155;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 10px 14px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.nav-btn-outline:hover {
    background: #f8fafc;
    border-color: #94a3b8;
}

/* ========================================================================= */
/* ===== PROFESSIONAL SHORT PRINT RECEIPT (THERMAL & 1-PAGE SLIP CSS) ===== */
/* ========================================================================= */

.short-printable-receipt {
    display: none; /* Hidden on normal screen view, shown on print and inside modal */
}

.receipt-slip-container {
    max-width: 440px;
    margin: 0 auto;
    background: #ffffff;
    color: #000000;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    font-size: 11px;
    line-height: 1.35;
    padding: 12px 14px;
    box-sizing: border-box;
}

.slip-header {
    text-align: center;
    margin-bottom: 8px;
}
.slip-logo-brand {
    font-size: 16px;
    font-weight: 900;
    letter-spacing: 0.5px;
    color: #000000;
    text-transform: uppercase;
}
.slip-chinese-brand {
    font-size: 13px;
    font-weight: 800;
    margin-top: 1px;
}
.slip-reg-no {
    font-size: 9.5px;
    color: #444444;
    margin-top: 2px;
}
.slip-store-address {
    font-size: 10px;
    color: #222222;
    margin-top: 3px;
    line-height: 1.3;
}
.slip-contact-line {
    font-size: 9.5px;
    color: #444444;
    margin-top: 2px;
}

.slip-divider-dashed {
    border-top: 1px dashed #000000;
    margin: 8px 0;
}
.slip-divider-solid {
    border-top: 1.5px solid #000000;
    margin: 8px 0;
}

.slip-token-section {
    text-align: center;
    padding: 4px 0;
}
.slip-doc-type {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #000000;
}
.slip-token-title {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    margin-top: 4px;
}
.slip-token-huge {
    font-size: 34px;
    font-weight: 900;
    letter-spacing: 2px;
    line-height: 1;
    margin: 4px 0;
    border: 2px solid #000000;
    display: inline-block;
    padding: 4px 18px;
    border-radius: 6px;
}
.slip-order-num-hero {
    font-size: 18px;
    font-weight: 900;
    font-family: monospace;
    margin: 4px 0;
}
.slip-counter-badge, .slip-delivery-badge {
    font-size: 10px;
    font-weight: 800;
    margin-top: 2px;
    display: inline-block;
}

.slip-meta-grid {
    display: flex;
    flex-direction: column;
    gap: 3px;
    margin: 6px 0;
}
.slip-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    font-size: 10.5px;
}
.meta-title {
    color: #333333;
    font-weight: 600;
}
.meta-val {
    font-weight: 700;
    color: #000000;
    text-align: right;
    max-width: 65%;
}

.slip-items-table {
    width: 100%;
    border-collapse: collapse;
    margin: 4px 0;
    font-size: 10.5px;
}
.slip-items-table th {
    font-size: 9.5px;
    font-weight: 800;
    padding: 4px 2px;
    border-bottom: 1.5px solid #000000;
}
.slip-items-table td {
    padding: 5px 2px;
    border-bottom: 1px dotted #cccccc;
    vertical-align: top;
}
.col-item { width: 52%; }
.col-qty { width: 14%; text-align: center; }
.col-price { width: 16%; text-align: right; }
.col-total { width: 18%; text-align: right; }

.slip-item-name {
    font-size: 10.5px;
    line-height: 1.25;
}
.slip-item-sku {
    font-size: 9px;
    color: #555555;
}

.slip-totals-section {
    display: flex;
    flex-direction: column;
    gap: 3px;
    margin: 6px 0;
}
.slip-total-row {
    display: flex;
    justify-content: space-between;
    font-size: 10.5px;
    color: #222222;
}
.slip-grand-total {
    font-size: 13px;
    font-weight: 900;
    color: #000000;
    padding-top: 2px;
}
.grand-label {
    font-weight: 900;
}
.grand-val {
    font-weight: 900;
    font-size: 14px;
}

.slip-qr-section {
    text-align: center;
    margin: 8px 0 6px 0;
}
.slip-qr-image {
    display: inline-block;
    padding: 4px;
    border: 1px solid #000000;
    border-radius: 4px;
    background: #ffffff;
}
.slip-qr-caption {
    font-size: 9px;
    color: #444444;
    margin-top: 3px;
}

.slip-footer {
    text-align: center;
    margin-top: 8px;
    font-size: 9.5px;
    line-height: 1.35;
    color: #333333;
}
.slip-storage-notice {
    font-weight: 800;
    font-size: 10px;
    color: #000000;
    margin-bottom: 2px;
}
.slip-thanks {
    font-weight: 700;
}
.slip-website {
    font-size: 8.5px;
    color: #666666;
    margin-top: 2px;
}

/* ===== ON-SCREEN RECEIPT PREVIEW MODAL ===== */
.receipt-modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(6px);
    z-index: 99999;
    align-items: center;
    justify-content: center;
    padding: 16px;
    overflow-y: auto;
}

.receipt-modal-dialog {
    background: #f8fafc;
    border-radius: 16px;
    max-width: 490px;
    width: 100%;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    border: 1px solid #cbd5e1;
    overflow: hidden;
    animation: modalScaleIn 0.25s ease-out;
}

.receipt-modal-header {
    background: #091a36;
    color: #ffffff;
    padding: 14px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header-left {
    display: flex;
    align-items: center;
    gap: 8px;
}
.modal-header-title {
    font-size: 0.95rem;
    font-weight: 800;
    font-family: var(--font-heading);
}
.modal-close-btn {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #ffffff;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}
.modal-close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

.receipt-modal-body {
    padding: 16px;
    max-height: 70vh;
    overflow-y: auto;
    background: #e2e8f0;
}
.receipt-modal-body .receipt-slip-container {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    border: 1px solid #cbd5e1;
    border-radius: 8px;
}

.receipt-modal-footer {
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    padding: 12px 18px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
.modal-btn-cancel {
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    color: #334155;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 9px 18px;
    border-radius: 8px;
    cursor: pointer;
}
.modal-btn-cancel:hover {
    background: #e2e8f0;
}
.modal-btn-print {
    background: #2563eb;
    border: 1px solid #1d4ed8;
    color: #ffffff;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 9px 20px;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
}
.modal-btn-print:hover {
    background: #1d4ed8;
}

@keyframes modalScaleIn {
    0% { transform: scale(0.92); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

/* ========================================================================= */
/* ===== STRICT 1-PAGE PRINT FORMAT CSS (@media print) ===================== */
/* ========================================================================= */
@media print {
    @page {
        size: auto;
        margin: 5mm 8mm;
    }

    /* Hide entire website structure */
    header, footer, nav, 
    .order-success-hero, 
    .order-success-page-body,
    .receipt-modal-backdrop,
    .btn, button {
        display: none !important;
    }

    body, html {
        background: #ffffff !important;
        color: #000000 !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Show only the short clean receipt slip */
    .short-printable-receipt {
        display: block !important;
        width: 100% !important;
        max-width: 440px !important;
        margin: 0 auto !important;
    }

    .receipt-slip-container {
        max-width: 100% !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function copyOrderReference() {
    const refText = document.getElementById('orderRefNumber')?.innerText;
    if (!refText) return;

    navigator.clipboard.writeText(refText.trim()).then(() => {
        const icon = document.getElementById('copyIcon');
        const text = document.getElementById('copyText');
        const btn = document.getElementById('copyRefBtn');

        if (icon && text && btn) {
            icon.textContent = '✓';
            text.textContent = @json(__t('common.copied', 'Copied!'));
            btn.style.borderColor = '#22c55e';
            btn.style.color = '#15803d';
            btn.style.background = '#dcfce7';

            setTimeout(() => {
                icon.textContent = '📋';
                text.textContent = @json(__t('common.copy', 'Copy'));
                btn.style.borderColor = '';
                btn.style.color = '';
                btn.style.background = '';
            }, 2500);
        }
    }).catch(err => {
        console.warn('Clipboard write failed', err);
    });
}

function openReceiptModal() {
    const modal = document.getElementById('receiptModalBackdrop');
    const modalBody = document.getElementById('modalReceiptBody');
    const originalSlip = document.getElementById('shortPrintableReceipt');

    if (modal && modalBody && originalSlip) {
        modalBody.innerHTML = originalSlip.innerHTML;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    } else {
        window.print();
    }
}

function closeReceiptModal(e) {
    const modal = document.getElementById('receiptModalBackdrop');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReceiptModal();
    }
});
</script>
@endpush
