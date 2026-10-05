@extends('layouts.app')
@section('title', __t('checkout.meta_title', 'Checkout — MST Import and Export Sdn. Bhd.'))

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:clamp(70px, 9vw, 95px);padding-bottom:clamp(18px, 3.5vw, 36px);background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:4px">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 @t('nav.home', 'Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <a href="{{ route('cart.index') }}" style="color:#bae6fd;text-decoration:none">@t('cart.title', 'Shopping Cart')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('checkout.page_title', 'Checkout & Payment')</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        @t('checkout.encrypted_checkout_badge', 'Order Verification & Payment')
                    </span>
                    <span style="color:#bae6fd;font-size:0.78rem">@t('checkout.cold_chain_dispatch', 'Guaranteed Cold-Chain Dispatch')</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,1.95rem);margin-bottom:4px;letter-spacing:-0.02em">
                    @t('checkout.page_title', 'Checkout & Payment')
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.88rem;max-width:680px;line-height:1.4;margin:0">
                    @t('checkout.header_subtitle', "Review your order items, confirm delivery address, and proceed to Stripe's encrypted payment gateway.")
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                @if($group === 'wholesale')
                <div style="font-size:0.8rem;padding:5px 12px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb;font-weight:600">
                    🏢 @t('checkout.tier_wholesale', 'Wholesale Partner Tier')
                </div>
                @elseif($group === 'walkin')
                <div style="font-size:0.8rem;padding:5px 12px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb;font-weight:600">
                    🏪 @t('checkout.tier_walkin', 'In-Store Walk-in Express')
                </div>
                @else
                <div style="font-size:0.8rem;padding:5px 12px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb;font-weight:600">
                    🛒 @t('checkout.tier_retail', 'Retail Customer Order')
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="checkout-page-wrapper">
    <div class="container checkout-container">

        {{-- Stepper Progress Bar --}}
        <div class="checkout-stepper-bar">
            <a href="{{ route('cart.index') }}" class="stepper-item step-completed">
                <div class="step-num">✓</div>
                <div class="step-label">
                    <span class="label-full">@t('checkout.step_cart', 'Shopping Cart')</span>
                    <span class="label-short">@t('checkout.step_cart_short', 'Cart')</span>
                </div>
            </a>
            <div class="stepper-divider"></div>
            <div class="stepper-item step-active">
                <div class="step-num">2</div>
                <div class="step-label">
                    <span class="label-full">@t('checkout.step_checkout', 'Checkout & Payment')</span>
                    <span class="label-short">@t('checkout.step_checkout_short', 'Payment')</span>
                </div>
            </div>
            <div class="stepper-divider"></div>
            <div class="stepper-item step-disabled">
                <div class="step-num">3</div>
                <div class="step-label">
                    <span class="label-full">@t('checkout.step_confirm', 'Order Confirmation')</span>
                    <span class="label-short">@t('checkout.step_confirm_short', 'Done')</span>
                </div>
            </div>
        </div>

        {{-- Mobile Collapsible Order Summary Banner (< 992px) --}}
        <div class="mobile-order-summary-card d-lg-none" onclick="toggleMobileSummary()">
            <div class="mobile-summary-bar">
                <div class="mobile-summary-left">
                    <span class="mobile-summary-icon">🛍️</span>
                    <span class="mobile-summary-text">
                        <span id="mobileSummaryText">@t('checkout.show_summary', 'Show Order Summary')</span>
                        <span class="mobile-summary-count">(@t('checkout.items_count', ':count items', ['count' => $items->count()]))</span>
                    </span>
                    <span id="mobileSummaryChevron" class="mobile-summary-chevron">▼</span>
                </div>
                <div class="mobile-summary-right" id="mobileSummaryTopTotal">
                    @if($currentCurrency !== 'MYR')
                        {{ $currencySymbol }} {{ number_format($currencyService->convert($initialGrandTotal, $currentCurrency), 2) }}
                    @else
                        RM {{ number_format($initialGrandTotal, 2) }}
                    @endif
                </div>
            </div>

            {{-- Collapsible Content --}}
            <div id="mobileSummaryCollapse" class="mobile-summary-collapse" style="display:none" onclick="event.stopPropagation()">
                <div class="mobile-summary-items">
                    @foreach($items as $item)
                        @php $price = $item->product?->getPriceForGroup($item->customer_group) ?? 0; @endphp
                        <div class="mobile-summary-item-row">
                            <div class="mobile-item-thumb">
                                @php
                                    $itemThumb = $item->product?->thumbnail ?? ($item->product?->images[0] ?? null);
                                @endphp
                                @if($itemThumb)
                                    <img src="{{ cdn_storage($itemThumb) }}" alt="{{ $item->product?->name ?? 'Product' }}" onerror="this.onerror=null;this.parentElement.innerHTML='<span class=\'thumb-emoji\'>🦐</span>';">
                                @else
                                    <span class="thumb-emoji">🦐</span>
                                @endif
                                <span class="qty-badge">{{ $item->quantity }}</span>
                            </div>
                            <div class="mobile-item-details">
                                <div class="mobile-item-name">{{ $item->product?->name }}</div>
                                <div class="mobile-item-meta">{{ $item->product?->sku ?? 'SEA-ITEM' }}</div>
                                @if($item->product?->isVariableWeight())
                                    <div style="font-size:0.7rem;color:#b45309;font-weight:600;margin-top:2px">
                                        ⚖️ @t('shop.reference_estimated_weight', 'Reference / Estimated Weight'): {{ $item->product->getReferenceWeight() }}
                                        <br><span style="font-weight:normal;color:#78350f">@t('shop.variable_weight_checkout_short', 'Actual Final Weight × Unit Price billed upon weighing')</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mobile-item-price">
                                @if($currentCurrency !== 'MYR')
                                    {{ $currencySymbol }} {{ number_format($currencyService->convert($price * $item->quantity, $currentCurrency), 2) }}
                                    <span style="font-size:0.75rem;color:#64748b;display:block">RM {{ number_format($price * $item->quantity, 2) }}</span>
                                @else
                                    RM {{ number_format($price * $item->quantity, 2) }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mobile-summary-totals">
                    <div class="summary-line">
                        <span>@t('checkout.subtotal', 'Subtotal')</span>
                        <span id="mobileSubtotalDisplay">
                            @if($currentCurrency !== 'MYR')
                                {{ $currencySymbol }} {{ number_format($currencyService->convert($totals['subtotal'], $currentCurrency), 2) }}
                                <span style="font-size:0.75rem;color:#64748b;display:block">RM {{ number_format($totals['subtotal'], 2) }}</span>
                            @else
                                RM {{ number_format($totals['subtotal'], 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="summary-line" id="mobileDeliveryFeeRow">
                        <span id="mobileDeliveryFeeLabel">
                            @if($group === 'walkin' || (old('fulfillment_type', $deliveryInfo['fulfillment_type'] ?? 'delivery') === 'self_collection'))
                                @t('checkout.fulfillment_type', 'Fulfillment')
                            @elseif(!empty($deliveryInfo['zone_name']))
                                Cold-Chain Delivery – {{ $deliveryInfo['zone_name'] }}
                            @else
                                @t('checkout.shipping_logistics', 'Cold-Chain Delivery')
                            @endif
                        </span>
                        <span class="{{ $initialShippingFee <= 0 ? 'val-green' : 'val-fee' }}" id="mobileShippingDisplay">
                            @if(old('fulfillment_type', $deliveryInfo['fulfillment_type'] ?? 'delivery') === 'self_collection')
                                @t('checkout.free_self_collection', 'Free (Self-collection)')
                            @elseif($initialShippingFee <= 0)
                                @t('checkout.free_standard_delivery', 'Free (Standard Local Delivery)')
                            @else
                                + RM {{ number_format($initialShippingFee, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="summary-line summary-grand-total">
                        <span class="total-label">@t('checkout.grand_total', 'Grand Total')</span>
                        <span class="total-val" id="mobileGrandTotalDisplay">
                            @if($currentCurrency !== 'MYR')
                                {{ $currencySymbol }} {{ number_format($currencyService->convert($initialGrandTotal, $currentCurrency), 2) }}
                                <span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal" id="mobileGrandTotalBase">Base: RM {{ number_format($initialGrandTotal, 2) }}</span>
                            @else
                                RM {{ number_format($initialGrandTotal, 2) }}
                            @endif
                        </span>
                    </div>

                    @php
                        $hasVariableWeight = $items->contains(fn($i) => $i->product?->isVariableWeight());
                    @endphp
                    @if($hasVariableWeight)
                        <div style="margin-top:10px;padding:8px 10px;background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;font-size:0.75rem;color:#92400e;line-height:1.4">
                            <strong>⚖️ @t('shop.variable_weight_notice_title', 'Variable-Weight Products Notice'):</strong>
                            @t('shop.variable_weight_checkout_notice', 'Contains variable-weight items. Estimated total shown is calculated using reference weights. Final payable amount will be settled based on Actual Final Weight × Applicable Unit Price upon preparation.')
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
            @csrf
            <input type="hidden" name="group" value="{{ $group ?? 'retail' }}">
            <input type="hidden" name="payment_method" value="stripe">

            @if(session('error'))
                <div class="alert alert-danger" style="background:#fee2e2;border:1.5px solid #ef4444;color:#991b1b;padding:14px 18px;border-radius:12px;margin-bottom:20px;display:flex;align-items:center;gap:12px">
                    <span style="font-size:1.3rem">⚠️</span>
                    <div style="font-weight:600">{{ session('error') }}</div>
                </div>
            @endif

            <div class="checkout-main-grid">

                <!-- Left Column: Order Forms -->
                <div class="checkout-form-column">

                    <!-- Fulfillment Method Selection -->
                    <div class="card checkout-card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-title-icon">📦</span>
                                <span>@t('checkout.fulfillment_step', '1. Select Fulfillment Method')</span>
                            </div>
                        </div>

                        <div class="fulfillment-options-grid">
                            <label class="fulfillment-tile {{ old('fulfillment_type', 'delivery') == 'delivery' ? 'selected' : '' }}" id="label_delivery" for="delivery">
                                <input type="radio" name="fulfillment_type" id="delivery" value="delivery"
                                       {{ old('fulfillment_type', 'delivery') == 'delivery' ? 'checked' : '' }}
                                       onchange="onFulfillment('delivery')">
                                <div class="tile-check-indicator">✓</div>
                                <div class="tile-icon">🚚</div>
                                <div class="tile-content">
                                    <div class="tile-title">@t('checkout.cold_chain_delivery', 'Cold-Chain Delivery')</div>
                                    <div class="tile-desc">@t('checkout.cold_chain_delivery_desc', 'Direct refrigerated delivery across Klang Valley and West Malaysia.')</div>
                                    <div class="tile-badge badge-blue">@t('checkout.refrigerated_logistics', 'Refrigerated Logistics')</div>
                                </div>
                            </label>

                            <label class="fulfillment-tile {{ old('fulfillment_type') == 'self_collection' ? 'selected' : '' }}" id="label_self_collection" for="self_collection">
                                <input type="radio" name="fulfillment_type" id="self_collection" value="self_collection"
                                       {{ old('fulfillment_type') == 'self_collection' ? 'checked' : '' }}
                                       onchange="onFulfillment('self_collection')">
                                <div class="tile-check-indicator">✓</div>
                                <div class="tile-icon">🏪</div>
                                <div class="tile-content">
                                    <div class="tile-title">@t('checkout.store_pickup', 'Store Self-Collection')</div>
                                    <div class="tile-desc">@t('checkout.store_pickup_desc', 'Collect directly at our SILC Cold-Chain facility in Iskandar Puteri, Johor Bahru.')</div>
                                    <div class="tile-badge badge-green">@t('checkout.free_pickup', 'Free Pickup')</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 2. Customer Information (Full Name, Phone, Email) -->
                    <div class="card checkout-card">
                        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center">
                            <div class="card-title">
                                <span class="card-title-icon">👤</span>
                                <span>@t('checkout.customer_info_step', '2. Customer Details')</span>
                            </div>
                            @guest
                            <span style="font-size:0.75rem;font-weight:700;color:#0284c7;background:#f0f9ff;border:1px solid #bae6fd;padding:3px 10px;border-radius:999px">
                                🛒 @t('checkout.guest_checkout_badge', 'Guest Checkout')
                            </span>
                            @else
                            <span style="font-size:0.75rem;font-weight:600;color:#166534;background:#f0fdf4;border:1px solid #bbf7d0;padding:3px 10px;border-radius:999px">
                                ✓ @t('checkout.signed_in_badge', 'Signed In')
                            </span>
                            @endguest
                        </div>

                        @guest
                        <div style="margin-bottom:14px;background:#f8fafc;border:1px solid #e2e8f0;padding:10px 14px;border-radius:10px;font-size:0.78rem;color:#475569;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                            <span>📧 @t('checkout.guest_receipt_notice', 'Your order confirmation receipt, itemized invoice & delivery updates will be sent to this email & mobile.')</span>
                            <a href="{{ route('login') }}" style="color:#2563eb;font-weight:700;text-decoration:underline">@t('checkout.already_have_account_signin', 'Sign in here')</a>
                        </div>
                        @endguest

                        <div class="form-grid-2" style="margin-bottom:12px">
                            <div class="form-group">
                                <label class="form-label">@t('checkout.full_name', 'Full Name') <span class="required">*</span></label>
                                <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', auth()->user()?->name) }}" placeholder="e.g. John Tan" required>
                                @error('customer_name')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">@t('checkout.phone_number', 'Mobile / Contact Number') <span class="required">*</span></label>
                                <input type="tel" name="customer_phone" class="form-control" value="{{ old('customer_phone', auth()->user()?->phone) }}" placeholder="e.g. 012-345 6789" required>
                                @error('customer_phone')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">@t('checkout.email_address', 'Email Address (for Receipt & Order Confirmation)') <span class="required">*</span></label>
                            <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', auth()->user()?->email) }}" placeholder="e.g. customer@example.com" required>
                            @error('customer_email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- 3A. Delivery Address Card (Shown when Delivery is selected) -->
                    <div class="card checkout-card" id="addressCard" style="{{ old('fulfillment_type', 'delivery') == 'delivery' ? 'display:block' : 'display:none' }}">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-title-icon">📍</span>
                                <span>@t('checkout.shipping_info', '3. Delivery Address & Scheduling')</span>
                            </div>
                        </div>

                        {{-- Delivery Lead Time Notice Banner (7 Working Days) --}}
                        <div class="delivery-lead-time-notice" style="background:#eff6ff;border:1.5px solid #93c5fd;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:flex-start;gap:12px">
                            <span style="font-size:1.3rem;line-height:1">🚚</span>
                            <div style="font-size:0.83rem;color:#1e3a8a;line-height:1.5">
                                <strong style="display:block;margin-bottom:3px;color:#1e40af;font-size:0.88rem">@t('checkout.delivery_lead_time_title', 'Delivery Lead Time:')</strong>
                                @t('checkout.delivery_lead_time_desc', 'Please allow up to 7 working days for order sourcing and cold-chain delivery arrangements. The available delivery date will be provided or confirmed by MST based on product availability and delivery scheduling.')
                            </div>
                        </div>

                        {{-- Dynamic Delivery & Transportation Fee Notice Banner --}}
                        <div id="deliveryNoticeBanner" style="margin-bottom:16px;padding:12px 14px;border-radius:10px;font-size:0.83rem;line-height:1.45;display:flex;align-items:flex-start;gap:10px;{{ !empty($deliveryInfo['requires_manual_arrangement']) ? 'background:#fff7ed;border:1px solid #fdba74;color:#9a3412;' : ($initialShippingFee <= 0 ? 'background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;' : 'background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;') }}">
                            <span style="font-size:1.1rem;line-height:1;flex-shrink:0" id="deliveryNoticeIcon">{{ !empty($deliveryInfo['requires_manual_arrangement']) ? '🚚' : ($initialShippingFee <= 0 ? '✅' : 'ℹ️') }}</span>
                            <div id="deliveryNoticeText" style="flex:1">
                                @if(!empty($deliveryInfo['requires_manual_arrangement']))
                                    <strong>@t('checkout.outstation_delivery_title', 'Outstation Cold-Chain Delivery:')</strong> @t('checkout.outstation_delivery_desc', 'Packaging and transportation fees will be calculated based on the required Styrofoam box size/quantity and confirmed with you via WhatsApp prior to dispatch.')
                                    <div style="margin-top:6px">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('store_whatsapp', '601112710260')) }}" target="_blank" rel="noopener" class="btn btn-sm" style="background:#22c55e;color:#ffffff;font-size:0.75rem;padding:3px 10px;border-radius:6px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                                            💬 Contact via WhatsApp
                                        </a>
                                    </div>
                                @elseif($initialShippingFee <= 0)
                                    <strong>@t('checkout.standard_delivery_eligible', 'Free Standard Delivery (RM 0.00):')</strong> @t('checkout.standard_delivery_desc', 'Your order qualifies for the free standard local delivery arrangement in Johor Bahru and Iskandar Puteri / Nusajaya.')
                                @else
                                    <strong>@t('checkout.delivery_fee_notice_title', 'Delivery Fee Notice:')</strong> @t('checkout.below_threshold_notice', 'Orders below the standard delivery threshold (RM :threshold) can still be placed and may be subject to transportation or delivery charges based on delivery location.', ['threshold' => number_format($deliveryInfo['threshold'] ?? 150, 2)]) ({{ $deliveryInfo['zone_name'] ?? 'Zone Fee' }}: +RM {{ number_format($initialShippingFee, 2) }})
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">@t('checkout.address', 'Street Address') <span class="required">*</span></label>
                            <input type="text" name="address" id="addressInput" class="form-control" value="{{ old('address', auth()->user()?->address) }}" placeholder="Unit / House No, Street, Taman..." {{ old('fulfillment_type', 'delivery') == 'delivery' ? 'required' : '' }}>
                            @error('address')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="address-grid-responsive">
                            <div class="form-group">
                                <label class="form-label">@t('checkout.postcode', 'Postcode') <span class="required">*</span></label>
                                <input type="text" name="postcode" id="postcodeInput" class="form-control" value="{{ old('postcode', auth()->user()?->postcode ?? '79100') }}" placeholder="79100" maxlength="8" oninput="debounceDeliveryRecalculation()" {{ old('fulfillment_type', 'delivery') == 'delivery' ? 'required' : '' }}>
                                @error('postcode')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">@t('checkout.city', 'City') <span class="required">*</span></label>
                                <input type="text" name="city" id="cityInput" class="form-control" value="{{ old('city', auth()->user()?->city ?? 'Johor Bahru') }}" placeholder="e.g. Johor Bahru / Iskandar Puteri" oninput="debounceDeliveryRecalculation()" {{ old('fulfillment_type', 'delivery') == 'delivery' ? 'required' : '' }}>
                                @error('city')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">@t('checkout.state', 'State') <span class="required">*</span></label>
                                <input type="text" name="state" id="stateInput" class="form-control" value="{{ old('state', auth()->user()?->state ?? 'Johor') }}" placeholder="e.g. Johor" oninput="debounceDeliveryRecalculation()" {{ old('fulfillment_type', 'delivery') == 'delivery' ? 'required' : '' }}>
                                @error('state')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:14px">
                            <label class="form-label">
                                @t('checkout.delivery_date_label', 'Earliest Available / Preferred Delivery Date')
                                <span style="font-size:0.75rem;font-weight:normal;color:#64748b">(@t('checkout.delivery_date_subject_mst', 'Subject to MST Confirmation'))</span>
                            </label>
                            <input type="date" name="delivery_date" id="deliveryDateInput" class="form-control"
                                   min="{{ date('Y-m-d', strtotime('+3 days')) }}"
                                   value="{{ old('delivery_date', date('Y-m-d', strtotime('+7 days'))) }}">
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">
                                ℹ️ @t('checkout.delivery_date_notice', 'The delivery date is subject to product availability and MST cold-chain delivery scheduling.')
                            </div>
                        </div>
                    </div>

                    <!-- 3B. Store Self-Collection Card (Shown when Self-Collection is selected) -->
                    <div class="card checkout-card" id="selfCollectionCard" style="{{ old('fulfillment_type', 'delivery') == 'self_collection' ? 'display:block' : 'display:none' }}">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-title-icon">🏪</span>
                                <span>@t('checkout.self_collection_info_title', '3. Self-Collection Details (SILC Facility, Iskandar Puteri)')</span>
                            </div>
                        </div>

                        <div style="background:#f0fdf4;border:1.5px solid #86efac;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:flex-start;gap:12px">
                            <span style="font-size:1.3rem;line-height:1">🏬</span>
                            <div style="font-size:0.83rem;color:#166534;line-height:1.5">
                                <strong style="display:block;margin-bottom:2px;color:#15803d;font-size:0.88rem">@t('checkout.collection_location_title', 'Collection Point: MST Cold-Chain Facility')</strong>
                                7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor Bahru, Malaysia.<br>
                                <span style="font-weight:700;color:#166534">@t('checkout.self_collection_free_tag', 'Store Self-Collection is 100% Free (RM 0.00 Delivery Fee).')</span>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">@t('checkout.collection_date', 'Self-Collection Date') <span class="required">*</span></label>
                                <input type="date" name="collection_date" id="collectionDateInput" class="form-control"
                                       min="{{ date('Y-m-d') }}"
                                       value="{{ old('collection_date', date('Y-m-d', strtotime('+1 day'))) }}"
                                       {{ old('fulfillment_type', 'delivery') == 'self_collection' ? 'required' : '' }}>
                                @error('collection_date')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">@t('checkout.collection_time', 'Self-Collection Time Slot') <span class="required">*</span></label>
                                <select name="collection_time" id="collectionTimeInput" class="form-control" {{ old('fulfillment_type', 'delivery') == 'self_collection' ? 'required' : '' }}>
                                    <option value="08:30 AM - 10:30 AM" {{ old('collection_time', '08:30 AM - 10:30 AM') == '08:30 AM - 10:30 AM' ? 'selected' : '' }}>08:30 AM – 10:30 AM (Morning Slot)</option>
                                    <option value="10:30 AM - 12:30 PM" {{ old('collection_time') == '10:30 AM - 12:30 PM' ? 'selected' : '' }}>10:30 AM – 12:30 PM (Midday Slot)</option>
                                    <option value="01:30 PM - 03:30 PM" {{ old('collection_time') == '01:30 PM - 03:30 PM' ? 'selected' : '' }}>01:30 PM – 03:30 PM (Afternoon Slot)</option>
                                    <option value="03:30 PM - 05:30 PM" {{ old('collection_time') == '03:30 PM - 05:30 PM' ? 'selected' : '' }}>03:30 PM – 05:30 PM (Late Afternoon Slot)</option>
                                </select>
                                @error('collection_time')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- 4. Order Notes Card -->
                    <div class="card checkout-card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-title-icon">📝</span>
                                <span>@t('checkout.special_notes_step', '4. Special Instructions & Notes')</span>
                            </div>
                        </div>
                        <textarea name="customer_notes" class="form-control" rows="3" placeholder="{{ __t('checkout.notes_placeholder', 'Add specific delivery timing, packing instructions, or gate codes (optional)...') }}">{{ old('customer_notes') }}</textarea>
                    </div>

                    <!-- 5. Payment Method Card -->
                    <div class="card checkout-card">
                        <div class="card-header flex-wrap-mobile">
                            <div class="card-title">
                                <span class="card-title-icon">💳</span>
                                <span>@t('checkout.payment_step', '5. Payment via Stripe')</span>
                            </div>
                            <div class="payment-shield-badge">
                                🔒 @t('checkout.ssl_encrypted', '256-bit SSL Encrypted')
                            </div>
                        </div>

                        <div class="stripe-secure-banner">
                            <div class="banner-icon">🛡️</div>
                            <div class="banner-text">
                                <strong>@t('checkout.stripe_banner_title', 'Stripe Official Hosted Checkout:')</strong> @t('checkout.stripe_banner_desc', "When you click below, you will be securely redirected to Stripe's payment page (checkout.stripe.com) to complete your card or online banking payment.")
                            </div>
                        </div>

                        <div class="stripe-hosted-box">
                            <div class="stripe-hosted-top">
                                <div class="stripe-gateway-brand">
                                    <span class="stripe-logo-icon">💳</span>
                                    <div>
                                        <div class="stripe-brand-title">@t('checkout.stripe_official', 'Stripe Official Checkout')</div>
                                        <div class="stripe-brand-subtitle">@t('checkout.stripe_methods', 'Credit / Debit Card, Apple Pay & FPX')</div>
                                    </div>
                                </div>
                                <span class="stripe-badge-pill">@t('checkout.secure_gateway', 'Secure Gateway')</span>
                            </div>

                            <div class="stripe-payment-methods-grid">
                                <span class="pay-method-chip">💳 Visa</span>
                                <span class="pay-method-chip">💳 Mastercard</span>
                                <span class="pay-method-chip">🍎 Apple Pay</span>
                                <span class="pay-method-chip">🌐 Google Pay</span>
                                <span class="pay-method-chip">🏦 FPX Online Banking</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Order Summary (Desktop Sticky) -->
                <div class="checkout-summary-column">
                    <div class="card checkout-summary-card">
                        <div class="card-header summary-header">
                            <div class="card-title">@t('checkout.order_summary', 'Order Summary')</div>
                            <span class="summary-count-badge">@t('checkout.items_count', ':count items', ['count' => $items->count()])</span>
                        </div>

                        <div class="summary-items-scroll">
                            @foreach($items as $item)
                                @php $price = $item->product?->getPriceForGroup($item->customer_group) ?? 0; @endphp
                                <div class="summary-item-row">
                                    <div class="summary-item-left">
                                        <div class="summary-item-thumb">
                                            @php
                                                $itemThumb = $item->product?->thumbnail ?? ($item->product?->images[0] ?? null);
                                            @endphp
                                            @if($itemThumb)
                                                <img src="{{ cdn_storage($itemThumb) }}" alt="{{ $item->product?->name ?? 'Product' }}" onerror="this.onerror=null;this.parentElement.innerHTML='<span class=\'thumb-emoji\'>🦐</span>';">
                                            @else
                                                <span class="thumb-emoji">🦐</span>
                                            @endif
                                            <span class="qty-badge">{{ $item->quantity }}</span>
                                        </div>
                                        <div class="summary-item-details">
                                            <div class="summary-item-name">{{ $item->product?->name }}</div>
                                            <div class="summary-item-meta">{{ $item->product?->sku ?? 'SEA-ITEM' }}</div>
                                            @if($item->product?->isVariableWeight())
                                                <div style="font-size:0.72rem;color:#b45309;font-weight:600;margin-top:3px;background:#fef3c7;padding:3px 6px;border-radius:4px;border:1px solid #fde68a">
                                                    ⚖️ @t('shop.reference_estimated_weight', 'Reference / Estimated Weight'): {{ $item->product->getReferenceWeight() }}
                                                    <div style="font-weight:normal;color:#78350f;font-size:0.68rem;margin-top:1px">@t('shop.variable_weight_checkout_short', 'Actual Final Weight × Unit Price billed upon weighing')</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="summary-item-price">
                                        @if($currentCurrency !== 'MYR')
                                            {{ $currencySymbol }} {{ number_format($currencyService->convert($price * $item->quantity, $currentCurrency), 2) }}
                                            <span style="font-size:0.75rem;color:#64748b;display:block">RM {{ number_format($price * $item->quantity, 2) }}</span>
                                        @else
                                            RM {{ number_format($price * $item->quantity, 2) }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="summary-totals-box">
                            <div class="summary-line">
                                <span class="line-label">@t('checkout.subtotal', 'Subtotal')</span>
                                <span class="line-val" id="desktopSubtotalDisplay">
                                    @if($currentCurrency !== 'MYR')
                                        {{ $currencySymbol }} {{ number_format($currencyService->convert($totals['subtotal'], $currentCurrency), 2) }}
                                        <span style="font-size:0.75rem;color:#64748b;display:block">RM {{ number_format($totals['subtotal'], 2) }}</span>
                                    @else
                                        RM {{ number_format($totals['subtotal'], 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="summary-line" id="desktopDeliveryFeeRow">
                                <span class="line-label" id="desktopDeliveryFeeLabel">
                                    @if($group === 'walkin' || (old('fulfillment_type', $deliveryInfo['fulfillment_type'] ?? 'delivery') === 'self_collection'))
                                        @t('checkout.fulfillment_type', 'Fulfillment')
                                    @elseif(!empty($deliveryInfo['zone_name']))
                                        Cold-Chain Delivery – {{ $deliveryInfo['zone_name'] }}
                                    @else
                                        @t('checkout.shipping_logistics', 'Cold-Chain Delivery')
                                    @endif
                                </span>
                                <span class="line-val {{ $initialShippingFee <= 0 ? 'val-green' : 'val-fee' }}" id="shippingDisplay">
                                    @if(old('fulfillment_type', $deliveryInfo['fulfillment_type'] ?? 'delivery') === 'self_collection')
                                        @t('checkout.free_self_collection', 'Free (Self-collection)')
                                    @elseif($initialShippingFee <= 0)
                                        @t('checkout.free_standard_delivery', 'Free (Standard Local Delivery)')
                                    @else
                                        + RM {{ number_format($initialShippingFee, 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="summary-line summary-grand-total">
                                <span class="total-label">@t('checkout.grand_total', 'Grand Total')</span>
                                <span class="total-val" id="grandTotalDisplay">
                                    @if($currentCurrency !== 'MYR')
                                        {{ $currencySymbol }} {{ number_format($currencyService->convert($initialGrandTotal, $currentCurrency), 2) }}
                                        <span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal" id="grandTotalBaseDisplay">Base: RM {{ number_format($initialGrandTotal, 2) }}</span>
                                    @else
                                        RM {{ number_format($initialGrandTotal, 2) }}
                                    @endif
                                </span>
                            </div>

                            @if($hasVariableWeight)
                                <div style="margin-top:10px;padding:8px 10px;background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;font-size:0.75rem;color:#92400e;line-height:1.4">
                                    <strong>⚖️ @t('shop.variable_weight_notice_title', 'Variable-Weight Products Notice'):</strong>
                                    @t('shop.variable_weight_checkout_notice', 'Contains variable-weight items. Estimated total shown is calculated using reference weights. Final payable amount will be settled based on Actual Final Weight × Applicable Unit Price upon preparation.')
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="checkout-submit-btn" id="submitBtn">
                            <span class="btn-main-text">@t('checkout.place_order', 'Proceed to Payment')</span>
                            <span class="btn-amount-badge" id="submitBtnAmount">
                                @if($currentCurrency !== 'MYR')
                                    {{ $currencySymbol }} {{ number_format($currencyService->convert($initialGrandTotal, $currentCurrency), 2) }}
                                @else
                                    RM {{ number_format($initialGrandTotal, 2) }}
                                @endif
                            </span>
                        </button>

                        <div class="checkout-trust-badges">
                            <div class="trust-item">
                                <span class="trust-icon">✓</span>
                                <span>@t('checkout.trust_verified', 'Verified Order')</span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">❄️</span>
                                <span>@t('checkout.trust_cold_chain', 'Cold-Chain')</span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">⚡</span>
                                <span>@t('checkout.trust_instant', 'Instant Confirm')</span>
                            </div>
                        </div>

                        @if($currentCurrency !== 'MYR')
                            <div style="font-size:0.75rem;color:#64748b;margin:10px 0 12px;background:#f8fafc;padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;line-height:1.4" id="currencyNoteBox">
                                @t('common.currency_notice', "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.")
                            </div>
                        @endif

                        <p class="checkout-terms-note">
                            @t('checkout.terms_note', 'By clicking proceed, you will be redirected to finalize your payment.')
                        </p>
                    </div>
                </div>

            </div>
        </form>

    </div>

    {{-- Floating Sticky Mobile Pay Bar (Visible <= 768px) --}}
    <div class="mobile-sticky-footer-bar d-lg-none">
        <div class="mobile-footer-inner">
            <div class="mobile-footer-price-col">
                <span class="mobile-footer-label">@t('checkout.grand_total', 'Grand Total')</span>
                <span class="mobile-footer-amount" id="mobileStickyGrandTotal">
                    @if($currentCurrency !== 'MYR')
                        {{ $currencySymbol }} {{ number_format($currencyService->convert($initialGrandTotal, $currentCurrency), 2) }}
                    @else
                        RM {{ number_format($initialGrandTotal, 2) }}
                    @endif
                </span>
            </div>
            <button type="button" onclick="submitCheckoutForm()" class="mobile-footer-pay-btn" id="mobilePayBtn">
                <span>@t('checkout.place_order', 'Proceed to Payment')</span>
            </button>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
/* ===== CHECKOUT PAGE PREMIUM RESPONSIVE DESIGN SYSTEM ===== */

.checkout-page-wrapper {
    padding-top: 2rem; /* var(--space-8) */
    padding-bottom: 4rem; /* var(--space-16) */
    background: #f8fafc;
    min-height: auto;
}

.checkout-container {
    max-width: 1240px;
    margin: 0 auto;
    padding-left: clamp(12px, 3vw, 24px);
    padding-right: clamp(12px, 3vw, 24px);
}

/* Stepper Progress Bar */
.checkout-stepper-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: clamp(8px, 2vw, 16px);
    margin-bottom: 28px;
    flex-wrap: nowrap;
    overflow-x: auto;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
    padding: 4px 2px;
}
.checkout-stepper-bar::-webkit-scrollbar { display: none; }

.stepper-item {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
}

.stepper-item.step-completed { color: #059669; }
.stepper-item.step-active { color: #2563eb; }
.stepper-item.step-disabled { color: #94a3b8; }

.step-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 800;
    flex-shrink: 0;
}

.step-completed .step-num { background: #dcfce7; color: #059669; border: 2px solid #86efac; }
.step-active .step-num { background: #2563eb; color: #ffffff; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2); }
.step-disabled .step-num { background: #e2e8f0; color: #64748b; }

.stepper-divider {
    width: clamp(14px, 3vw, 36px);
    height: 2px;
    background: #cbd5e1;
    flex-shrink: 0;
}

.label-short { display: none; }
.label-full { display: inline; }

/* Header Box */
.checkout-header-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 24px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e2e8f0;
}

.checkout-page-title {
    font-size: clamp(1.4rem, 2.5vw, 1.85rem);
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 4px 0;
    letter-spacing: -0.02em;
    line-height: 1.25;
}

.checkout-page-sub {
    font-size: clamp(0.82rem, 1.5vw, 0.9rem);
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}

.tier-badge-pill {
    font-size: 0.8rem;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.tier-wholesale { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.tier-walkin { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
.tier-retail { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }

/* Mobile Order Summary Accordion Card */
.mobile-order-summary-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}

.mobile-summary-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    background: #f8fafc;
    cursor: pointer;
    user-select: none;
}

.mobile-summary-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.88rem;
    font-weight: 700;
    color: #1e293b;
}

.mobile-summary-icon { font-size: 1.1rem; }
.mobile-summary-count { color: #64748b; font-weight: 500; font-size: 0.8rem; }
.mobile-summary-chevron { font-size: 0.75rem; color: #2563eb; transition: transform 0.25s ease; }
.mobile-summary-chevron.open { transform: rotate(180deg); }

.mobile-summary-right {
    font-size: 1rem;
    font-weight: 800;
    color: #2563eb;
}

.mobile-summary-collapse {
    border-top: 1px solid #e2e8f0;
    padding: 16px;
    background: #ffffff;
}

.mobile-summary-items {
    max-height: 240px;
    overflow-y: auto;
    margin-bottom: 14px;
}

.mobile-summary-item-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    border-bottom: 1px dashed #f1f5f9;
}

.mobile-item-thumb {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #f1f5f9;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.mobile-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
.mobile-item-details { flex: 1; min-width: 0; }
.mobile-item-name { font-size: 0.82rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.mobile-item-meta { font-size: 0.72rem; color: #94a3b8; }
.mobile-item-price { font-size: 0.85rem; font-weight: 800; color: #059669; }

/* Grid Layout */
.checkout-main-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px;
    align-items: start;
}

.checkout-form-column {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.checkout-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: clamp(16px, 3vw, 24px);
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}

.checkout-card .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 14px;
    margin-bottom: 18px;
    border-bottom: 1px solid #f1f5f9;
    gap: 10px;
}

.checkout-card .card-title {
    font-size: clamp(0.98rem, 1.8vw, 1.1rem);
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}

.card-title-icon { font-size: 1.15rem; }

/* Fulfillment Tiles */
.fulfillment-options-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}

.fulfillment-tile {
    position: relative;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px;
    cursor: pointer;
    background: #ffffff;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.fulfillment-tile:hover {
    border-color: #93c5fd;
    background: #f8fafc;
}

.fulfillment-tile.selected {
    border-color: #2563eb;
    background: #eff6ff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
}

.fulfillment-tile input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.tile-check-indicator {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #cbd5e1;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    font-weight: 800;
    color: transparent;
    transition: all 0.15s ease;
}

.fulfillment-tile.selected .tile-check-indicator {
    border-color: #2563eb;
    background: #2563eb;
    color: #ffffff;
}

.tile-icon {
    font-size: 1.75rem;
    line-height: 1;
    flex-shrink: 0;
}

.tile-content { flex: 1; min-width: 0; }
.tile-title { font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 3px; }
.tile-desc { font-size: 0.78rem; color: #64748b; line-height: 1.35; margin-bottom: 8px; }

.tile-badge {
    display: inline-block;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
}
.badge-blue { background: #dbeafe; color: #1e40af; }
.badge-green { background: #dcfce7; color: #15803d; }

/* Form Grid 2-Column */
.form-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}

/* Responsive Address Grid */
.address-grid-responsive {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 14px;
}

.form-group {
    margin-bottom: 14px;
}

.form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}

.form-control {
    width: 100%;
    height: 44px;
    font-size: 16px; /* Prevents auto-zoom in iOS Safari */
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 0 14px;
    color: #0f172a;
    background: #ffffff;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    box-sizing: border-box;
}

textarea.form-control {
    height: auto;
    padding: 10px 14px;
    min-height: 80px;
    line-height: 1.5;
}

.form-control:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

/* Payment Section Styling */
.payment-shield-badge {
    font-size: 0.74rem;
    font-weight: 700;
    color: #059669;
    background: #ecfdf5;
    padding: 4px 10px;
    border-radius: 20px;
    border: 1px solid #a7f3d0;
    white-space: nowrap;
}

.stripe-secure-banner {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 18px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.banner-icon { font-size: 1.25rem; flex-shrink: 0; line-height: 1; }
.banner-text { font-size: 0.82rem; color: #1e40af; line-height: 1.45; }
.banner-text code { background: #dbeafe; padding: 1px 4px; border-radius: 4px; font-weight: 700; }

.stripe-hosted-box {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #ffffff;
    padding: 18px;
    border-radius: 14px;
    border: 1px solid #334155;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.stripe-hosted-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}

.stripe-gateway-brand {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stripe-logo-icon { font-size: 1.5rem; line-height: 1; }
.stripe-brand-title { font-weight: 800; font-size: 0.96rem; color: #f8fafc; }
.stripe-brand-subtitle { font-size: 0.78rem; color: #94a3b8; }

.stripe-badge-pill {
    background: rgba(37, 99, 235, 0.3);
    color: #93c5fd;
    border: 1px solid #3b82f6;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
}

.stripe-payment-methods-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding-top: 8px;
    border-top: 1px solid #334155;
}

.pay-method-chip {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #f1f5f9;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.76rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Order Summary Column */
.checkout-summary-column {
    position: sticky;
    top: 96px;
}

.checkout-summary-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: clamp(16px, 3vw, 24px);
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
}

.summary-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 14px;
    margin-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
}

.summary-header .card-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}

.summary-count-badge {
    background: #f1f5f9;
    color: #475569;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
}

.summary-items-scroll {
    max-height: 260px;
    overflow-y: auto;
    margin-bottom: 18px;
    padding-right: 2px;
}

.summary-item-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px dashed #f1f5f9;
}

.summary-item-left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
}

.summary-item-thumb {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    flex-shrink: 0;
    overflow: hidden;
}

.summary-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
.thumb-emoji { font-size: 1.15rem; }

.qty-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #0f172a;
    color: #ffffff;
    font-size: 0.65rem;
    font-weight: 800;
    width: 17px;
    height: 17px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.summary-item-details { flex: 1; min-width: 0; }
.summary-item-name {
    font-size: 0.84rem;
    font-weight: 700;
    color: #0f172a;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.summary-item-meta { font-size: 0.7rem; color: #94a3b8; margin-top: 1px; }
.summary-item-price { font-size: 0.88rem; font-weight: 800; color: #059669; flex-shrink: 0; }

.summary-totals-box {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 18px;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.86rem;
    color: #475569;
    margin-bottom: 8px;
}

.val-green { color: #059669; font-weight: 700; }
.val-fee { color: #d97706; font-weight: 700; }

.summary-grand-total {
    margin-bottom: 0;
    padding-top: 10px;
    border-top: 1px solid #e2e8f0;
    font-size: 1.05rem;
}

.total-label { font-weight: 800; color: #0f172a; }
.total-val { font-weight: 900; color: #2563eb; font-size: 1.25rem; }

/* Enhanced Submit Button with Amount Pill */
.checkout-submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border: none;
    color: #091a36;
    font-weight: 800;
    padding: 13px 18px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 16px rgba(245, 158, 11, 0.35);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    box-sizing: border-box;
    font-family: inherit;
    text-decoration: none;
}

.checkout-submit-btn:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.45);
    color: #091a36;
}

.checkout-submit-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

.checkout-submit-btn:disabled {
    opacity: 0.75;
    cursor: not-allowed;
    transform: none;
}

.btn-main-text {
    font-size: clamp(0.85rem, 1.4vw, 0.96rem);
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.btn-amount-badge {
    background: rgba(9, 26, 54, 0.12);
    border: 1px solid rgba(9, 26, 54, 0.2);
    color: #091a36;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: clamp(0.82rem, 1.3vw, 0.9rem);
    font-weight: 800;
    white-space: nowrap;
    letter-spacing: 0.2px;
}

.checkout-trust-badges {
    display: flex;
    align-items: center;
    justify-content: space-around;
    gap: 6px;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.7rem;
    font-weight: 700;
    color: #64748b;
    white-space: nowrap;
}

.checkout-terms-note {
    font-size: 0.72rem;
    color: #94a3b8;
    text-align: center;
    margin: 12px 0 0 0;
    line-height: 1.4;
}

/* Floating Sticky Mobile Bottom Checkout Bar */
.mobile-sticky-footer-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-top: 1px solid #e2e8f0;
    padding: 10px 16px max(10px, env(safe-area-inset-bottom));
    z-index: 999;
    box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.08);
}

.mobile-footer-inner {
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.mobile-footer-price-col {
    display: flex;
    flex-direction: column;
}

.mobile-footer-label {
    font-size: 0.72rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
}

.mobile-footer-amount {
    font-size: 1.2rem;
    font-weight: 900;
    color: #2563eb;
    line-height: 1.1;
}

.mobile-footer-pay-btn {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border: none;
    color: #091a36;
    font-weight: 800;
    font-size: clamp(0.82rem, 3.2vw, 0.92rem);
    padding: clamp(10px, 2.5vw, 12px) clamp(12px, 3.2vw, 18px);
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-shrink: 0;
    transition: all 0.2s ease;
    font-family: inherit;
}

.mobile-footer-pay-btn:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #091a36;
}

.mobile-footer-pay-btn:active {
    transform: scale(0.98);
}

.mobile-footer-pay-btn:disabled {
    opacity: 0.75;
    cursor: not-allowed;
}

/* ===== RESPONSIVE MEDIA QUERIES ===== */

@media (min-width: 993px) {
    .d-lg-none {
        display: none !important;
    }
}

/* Tablet & Smaller PC (769px - 1024px) */
@media (max-width: 1024px) {
    .checkout-main-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .checkout-summary-column {
        position: static;
        margin-top: 10px;
    }

    .address-grid-responsive {
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
}

/* Tablets (<= 768px) */
@media (max-width: 768px) {
    .fulfillment-options-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .address-grid-responsive {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .form-grid-2 {
        grid-template-columns: 1fr;
        gap: 12px;
    }
}

/* Mobile Screens (<= 640px) */
@media (max-width: 640px) {
    .checkout-page-wrapper {
        padding-top: 1.5rem;
        padding-bottom: 110px; /* Room for mobile sticky bottom footer */
    }

    .checkout-container {
        padding-left: 12px;
        padding-right: 12px;
    }

    .label-full { display: none; }
    .label-short { display: inline; }

    .checkout-stepper-bar {
        gap: 6px;
        margin-bottom: 16px;
    }

    .stepper-item {
        font-size: 0.78rem;
        gap: 5px;
    }

    .step-num {
        width: 24px;
        height: 24px;
        font-size: 0.72rem;
    }

    .stepper-divider {
        width: 12px;
    }

    .checkout-header-box {
        margin-bottom: 14px;
        padding-bottom: 12px;
    }

    .flex-wrap-mobile {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 8px;
    }

    .checkout-card {
        padding: 16px 14px;
        border-radius: 14px;
    }

    .checkout-submit-btn {
        padding: 12px 14px;
    }

    .checkout-trust-badges {
        flex-wrap: wrap;
        gap: 10px;
    }
}

@media (max-width: 420px) {
    .checkout-submit-btn {
        flex-wrap: wrap;
        justify-content: center;
        text-align: center;
        padding: 12px 10px;
        gap: 6px;
    }

    .btn-main-text {
        font-size: 0.88rem;
        justify-content: center;
    }

    .btn-amount-badge {
        font-size: 0.82rem;
        padding: 3px 8px;
    }

    .mobile-sticky-footer-bar {
        padding: 10px 12px max(10px, env(safe-area-inset-bottom));
    }

    .mobile-footer-inner {
        gap: 8px;
    }

    .mobile-footer-amount {
        font-size: 1.1rem;
    }

    .mobile-footer-pay-btn {
        padding: 10px 12px;
        font-size: 0.82rem;
        gap: 4px;
    }
}

@media (max-width: 350px) {
    .mobile-footer-inner {
        gap: 6px;
    }

    .mobile-footer-label {
        font-size: 0.65rem;
    }

    .mobile-footer-amount {
        font-size: 1rem;
    }

    .mobile-footer-pay-btn {
        padding: 8px 10px;
        font-size: 0.78rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
const checkoutI18n = {
    calculatedByAdmin: @json(__t('checkout.shipping_calculated_admin', 'Calculated by admin')),
    freeSelfCollection: @json(__t('checkout.free_self_collection', 'Free (Self-collection)')),
    freeStandardDelivery: @json(__t('checkout.free_standard_delivery', 'Free (Standard Local Delivery)')),
    fulfillmentLabel: @json(__t('checkout.fulfillment_type', 'Fulfillment')),
    deliveryLabel: @json(__t('checkout.shipping_logistics', 'Cold-Chain Delivery')),
    showOrderSummary: @json(__t('checkout.show_summary', 'Show Order Summary')),
    hideOrderSummary: @json(__t('checkout.hide_summary', 'Hide Order Summary')),
    proceeding: @json(__t('checkout.proceeding', 'Proceeding to checkout...')),
    currency: @json($currentCurrency),
    currencySymbol: @json($currencySymbol),
    subtotal: {{ (float) ($totals['subtotal'] ?? 0) }},
};

let deliveryRecalcTimer = null;

function debounceDeliveryRecalculation() {
    clearTimeout(deliveryRecalcTimer);
    deliveryRecalcTimer = setTimeout(() => {
        fetchDeliveryFee();
    }, 350);
}

function fetchDeliveryFee() {
    const fulfillmentInput = document.querySelector('input[name="fulfillment_type"]:checked');
    const fulfillment = fulfillmentInput ? fulfillmentInput.value : 'delivery';
    const stateInput    = document.getElementById('stateInput');
    const cityInput     = document.getElementById('cityInput');
    const postcodeInput = document.getElementById('postcodeInput');

    const state    = stateInput ? stateInput.value : '';
    const city     = cityInput ? cityInput.value : '';
    const postcode = postcodeInput ? postcodeInput.value : '';

    const payload = {
        fulfillment_type: fulfillment,
        state: state,
        city: city,
        postcode: postcode,
        subtotal: checkoutI18n.subtotal,
        _token: '{{ csrf_token() }}'
    };

    fetch('{{ route("api.delivery.calculate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        applyDeliveryFeeUpdate(data);
    })
    .catch(err => {
        console.warn('Delivery fee calculation error:', err);
    });
}

function applyDeliveryFeeUpdate(data) {
    if (!data) return;

    const isFree = data.fee <= 0;
    const isSelfCollection = Boolean(data.is_self_collection);
    const requiresManual = Boolean(data.requires_manual_arrangement);

    let feeText = '';
    if (isSelfCollection) {
        feeText = checkoutI18n.freeSelfCollection;
    } else if (requiresManual) {
        feeText = 'Quoted via WhatsApp';
    } else if (isFree) {
        feeText = checkoutI18n.freeStandardDelivery;
    } else {
        feeText = '+ RM ' + data.fee_formatted;
    }

    // Update Shipping display elements
    const desktopShip = document.getElementById('shippingDisplay');
    if (desktopShip) {
        desktopShip.textContent = feeText;
        desktopShip.className = (isFree || isSelfCollection) ? 'line-val val-green' : (requiresManual ? 'line-val' : 'line-val val-fee');
    }

    const mobileShip = document.getElementById('mobileShippingDisplay');
    if (mobileShip) {
        mobileShip.textContent = feeText;
        mobileShip.className = (isFree || isSelfCollection) ? 'val-green' : (requiresManual ? '' : 'val-fee');
    }

    // Update Delivery line labels
    const deliveryLabelText = isSelfCollection 
        ? checkoutI18n.fulfillmentLabel || 'Fulfillment'
        : (data.zone_name ? 'Cold-Chain Delivery – ' + data.zone_name : 'Cold-Chain Delivery');

    const desktopDeliveryLabel = document.getElementById('desktopDeliveryFeeLabel');
    if (desktopDeliveryLabel) {
        desktopDeliveryLabel.textContent = deliveryLabelText;
    }

    const mobileDeliveryLabel = document.getElementById('mobileDeliveryFeeLabel');
    if (mobileDeliveryLabel) {
        mobileDeliveryLabel.textContent = deliveryLabelText;
    }

    // Format Grand Total strings
    const isForeign = checkoutI18n.currency !== 'MYR';
    const totalDisplayFormatted = isForeign
        ? checkoutI18n.currencySymbol + ' ' + data.converted_total
        : 'RM ' + data.total_formatted;

    // Update Desktop Grand Total
    const grandTotalEl = document.getElementById('grandTotalDisplay');
    if (grandTotalEl) {
        if (isForeign) {
            grandTotalEl.innerHTML = totalDisplayFormatted + '<span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal" id="grandTotalBaseDisplay">Base: RM ' + data.total_formatted + '</span>';
        } else {
            grandTotalEl.textContent = totalDisplayFormatted;
        }
    }

    // Update Mobile Collapsible Grand Total
    const mobileGrandTotalEl = document.getElementById('mobileGrandTotalDisplay');
    if (mobileGrandTotalEl) {
        if (isForeign) {
            mobileGrandTotalEl.innerHTML = totalDisplayFormatted + '<span style="font-size:0.8rem;color:#64748b;display:block;font-weight:normal" id="mobileGrandTotalBase">Base: RM ' + data.total_formatted + '</span>';
        } else {
            mobileGrandTotalEl.textContent = totalDisplayFormatted;
        }
    }

    // Update Top Mobile Banner Total
    const topMobileTotal = document.getElementById('mobileSummaryTopTotal');
    if (topMobileTotal) {
        topMobileTotal.textContent = totalDisplayFormatted;
    }

    // Update Submit Button Amount Badge
    const btnBadge = document.getElementById('submitBtnAmount');
    if (btnBadge) {
        btnBadge.textContent = totalDisplayFormatted;
    }

    // Update Mobile Sticky Footer Amount
    const mobileStickyTotal = document.getElementById('mobileStickyGrandTotal');
    if (mobileStickyTotal) {
        mobileStickyTotal.textContent = totalDisplayFormatted;
    }

    // Update Delivery Address Notice Banner
    const noticeBanner = document.getElementById('deliveryNoticeBanner');
    const noticeIcon = document.getElementById('deliveryNoticeIcon');
    const noticeText = document.getElementById('deliveryNoticeText');

    if (noticeBanner && noticeIcon && noticeText) {
        if (isSelfCollection) {
            noticeBanner.style.background = '#f0fdf4';
            noticeBanner.style.border = '1px solid #bbf7d0';
            noticeBanner.style.color = '#166534';
            noticeIcon.textContent = '🏪';
            noticeText.innerHTML = '<strong>Self-Collection:</strong> Collect your confirmed order directly from MST (Self-collection only · no delivery).';
        } else if (requiresManual) {
            noticeBanner.style.background = '#fff7ed';
            noticeBanner.style.border = '1px solid #fdba74';
            noticeBanner.style.color = '#9a3412';
            noticeIcon.textContent = '🚚';
            noticeText.innerHTML = '<strong>Outstation Cold-Chain Delivery:</strong> Packaging and transportation fees will be calculated based on the required Styrofoam box size/quantity and confirmed with you via WhatsApp prior to dispatch.<div style="margin-top:6px"><a href="' + (data.whatsapp_url || '#') + '" target="_blank" rel="noopener" class="btn btn-sm" style="background:#22c55e;color:#ffffff;font-size:0.75rem;padding:3px 10px;border-radius:6px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px">💬 Contact via WhatsApp</a></div>';
        } else if (isFree) {
            noticeBanner.style.background = '#f0fdf4';
            noticeBanner.style.border = '1px solid #bbf7d0';
            noticeBanner.style.color = '#166534';
            noticeIcon.textContent = '✅';
            noticeText.innerHTML = '<strong>Standard Local Delivery Eligible:</strong> Your order qualifies for standard local delivery arrangement in Johor Bahru and Iskandar Puteri / Nusajaya.';
        } else {
            noticeBanner.style.background = '#eff6ff';
            noticeBanner.style.border = '1px solid #bfdbfe';
            noticeBanner.style.color = '#1e40af';
            noticeIcon.textContent = 'ℹ️';
            noticeText.innerHTML = '<strong>Delivery Fee Notice:</strong> Orders below the standard delivery threshold (RM ' + Number(data.threshold).toFixed(2) + ') may be subject to an additional delivery fee based on your delivery location. (<strong>' + data.zone_name + '</strong>: Delivery Fee RM ' + data.fee_formatted + (hasBelowFee ? ' including RM ' + data.below_threshold_fee_formatted + ' below-threshold fee' : '') + ').';
        }
    }

    // Update Currency Note Box if present
    const currencyBox = document.getElementById('currencyNoteBox');
    if (currencyBox && isForeign) {
        currencyBox.innerHTML = {!! json_encode(__t('common.currency_notice', "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.")) !!};
    }
}

function onFulfillment(type) {
    document.querySelectorAll('.fulfillment-tile').forEach(l => l.classList.remove('selected'));
    const labelEl = document.getElementById('label_' + type);
    if (labelEl) labelEl.classList.add('selected');

    const addressCard = document.getElementById('addressCard');
    const selfCollectionCard = document.getElementById('selfCollectionCard');

    const addressInput = document.getElementById('addressInput');
    const postcodeInput = document.getElementById('postcodeInput');
    const cityInput = document.getElementById('cityInput');
    const stateInput = document.getElementById('stateInput');

    const collectionDateInput = document.getElementById('collectionDateInput');
    const collectionTimeInput = document.getElementById('collectionTimeInput');

    if (type === 'delivery') {
        if (addressCard) addressCard.style.display = 'block';
        if (selfCollectionCard) selfCollectionCard.style.display = 'none';

        if (addressInput) addressInput.required = true;
        if (postcodeInput) postcodeInput.required = true;
        if (cityInput) cityInput.required = true;
        if (stateInput) stateInput.required = true;

        if (collectionDateInput) collectionDateInput.required = false;
        if (collectionTimeInput) collectionTimeInput.required = false;
    } else {
        if (addressCard) addressCard.style.display = 'none';
        if (selfCollectionCard) selfCollectionCard.style.display = 'block';

        if (addressInput) addressInput.required = false;
        if (postcodeInput) postcodeInput.required = false;
        if (cityInput) cityInput.required = false;
        if (stateInput) stateInput.required = false;

        if (collectionDateInput) collectionDateInput.required = true;
        if (collectionTimeInput) collectionTimeInput.required = true;
    }

    fetchDeliveryFee();
}

function toggleMobileSummary() {
    const box = document.getElementById('mobileSummaryCollapse');
    const chevron = document.getElementById('mobileSummaryChevron');
    const text = document.getElementById('mobileSummaryText');
    if (!box) return;

    if (box.style.display === 'none' || box.style.display === '') {
        box.style.display = 'block';
        if (chevron) chevron.classList.add('open');
        if (text) text.textContent = checkoutI18n.hideOrderSummary;
    } else {
        box.style.display = 'none';
        if (chevron) chevron.classList.remove('open');
        if (text) text.textContent = checkoutI18n.showOrderSummary;
    }
}

let isSubmitting = false;

function resetSubmitButton() {
    isSubmitting = false;
    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.style.pointerEvents = 'auto';
        btn.style.opacity = '1';
        btn.innerHTML = '<span class="btn-main-text">@t("checkout.place_order", "Proceed to Payment")</span><span class="btn-amount-badge" id="submitBtnAmount">' + (document.getElementById('grandTotalDisplay') ? document.getElementById('grandTotalDisplay').innerText.split('\n')[0] : '') + '</span>';
    }

    const mobileBtn = document.getElementById('mobilePayBtn');
    if (mobileBtn) {
        mobileBtn.style.pointerEvents = 'auto';
        mobileBtn.style.opacity = '1';
        mobileBtn.innerHTML = '<span>@t("checkout.place_order", "Proceed to Payment")</span>';
    }
}

window.addEventListener('pageshow', function(event) {
    resetSubmitButton();
});

function submitCheckoutForm() {
    const form = document.getElementById('checkoutForm');
    if (!form) return;

    if (isSubmitting) return;

    // Check HTML5 validity
    if (!form.reportValidity()) {
        const firstInvalid = form.querySelector(':invalid');
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
        resetSubmitButton();
        return;
    }

    isSubmitting = true;

    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.style.pointerEvents = 'none';
        btn.style.opacity = '0.85';
        btn.innerHTML = '<span class="btn-main-text">⏳ ' + checkoutI18n.proceeding + '</span>';
    }

    const mobileBtn = document.getElementById('mobilePayBtn');
    if (mobileBtn) {
        mobileBtn.style.pointerEvents = 'none';
        mobileBtn.style.opacity = '0.85';
        mobileBtn.innerHTML = '<span>⏳ ' + checkoutI18n.proceeding + '</span>';
    }

    setTimeout(function() {
        resetSubmitButton();
    }, 15000);

    form.submit();
}

document.getElementById('checkoutForm').addEventListener('submit', function (e) {
    if (isSubmitting) {
        return;
    }

    if (!this.reportValidity()) {
        e.preventDefault();
        resetSubmitButton();
        return false;
    }

    isSubmitting = true;

    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.style.pointerEvents = 'none';
        btn.style.opacity = '0.85';
        btn.innerHTML = '<span class="btn-main-text">⏳ ' + checkoutI18n.proceeding + '</span>';
    }

    const mobileBtn = document.getElementById('mobilePayBtn');
    if (mobileBtn) {
        mobileBtn.style.pointerEvents = 'none';
        mobileBtn.style.opacity = '0.85';
        mobileBtn.innerHTML = '<span>⏳ ' + checkoutI18n.proceeding + '</span>';
    }

    setTimeout(function() {
        resetSubmitButton();
    }, 15000);
});
</script>
@endpush
