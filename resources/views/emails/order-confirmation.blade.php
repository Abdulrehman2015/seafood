@extends('emails.layout')

@php
    $mailLoc = $mailLocale ?? current_locale();
    $orderNo = $order->order_number ?? $order->id;
    $appName = config('app.name', 'MST Import & Export');
@endphp

@section('title', __t('email.order_confirmed_subject', 'Order Confirmed: :order — :app', ['order' => $orderNo, 'app' => $appName], $mailLoc))
@section('preheader', __t('email.order_confirmed_preheader', 'Thank you for your order #:order. We are preparing your cold-chain shipment.', ['order' => $orderNo], $mailLoc))

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Badge -->
    <tr>
        <td>
            <div style="display: inline-block; background-color: #ecfdf5; color: #047857; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #a7f3d0; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                {{ __t('email.badge_order_confirmed', '📦 Order Confirmed', [], $mailLoc) }}
            </div>
        </td>
    </tr>

    <!-- Title -->
    <tr>
        <td>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 8px 0;">
                {{ __t('email.order_thank_you_title', 'Thank you for your order, :name!', ['name' => $order->customer_name ?? 'Customer'], $mailLoc) }}
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                {{ __t('email.order_received_body', 'We have received your seafood order #:order. Our distribution hub is currently packing your items under continuous sub-zero conditions.', ['order' => $orderNo], $mailLoc) }}
            </p>
        </td>
    </tr>

    <!-- Order Summary Details -->
    <tr>
        <td style="padding-bottom: 20px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <tr>
                    <td style="background-color: #f1f5f9; padding: 10px 16px; font-size: 12px; font-weight: 800; color: #475569; letter-spacing: 0.05em; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">
                        {{ __t('email.order_information_header', '🧾 Order Information', [], $mailLoc) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; line-height: 1.8;">
                            <tr>
                                <td width="35%" style="color: #64748b; font-weight: 600;">{{ __t('email.order_id_label', 'Order ID:', [], $mailLoc) }}</td>
                                <td style="color: #0f172a; font-weight: 800; font-family: monospace;">#{{ $orderNo }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.order_date_label', 'Order Date:', [], $mailLoc) }}</td>
                                <td style="color: #0f172a;">{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : date('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.fulfillment_label', 'Fulfillment:', [], $mailLoc) }}</td>
                                <td style="color: #0f172a; font-weight: 700;">
                                    {{ $order->fulfillment_type === 'self_collection' ? __t('email.fulfillment_self_collection', '🏪 Self-Collection at Store', [], $mailLoc) : __t('email.fulfillment_delivery', '🚚 Cold-Chain Direct Delivery', [], $mailLoc) }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.payment_status_label', 'Payment Status:', [], $mailLoc) }}</td>
                                <td>
                                    <span style="display: inline-block; background-color: #dcfce7; color: #15803d; font-weight: 800; font-size: 11px; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                        {{ $order->payment_status === 'paid' ? __t('email.paid_status', 'Paid', [], $mailLoc) : ucfirst($order->payment_status ?? 'Paid') }}
                                    </span>
                                </td>
                            </tr>
                            @if($order->shipping_address)
                            <tr>
                                <td style="color: #64748b; font-weight: 600; vertical-align: top;">{{ __t('email.delivery_address_label', 'Delivery Address:', [], $mailLoc) }}</td>
                                <td style="color: #0f172a;">
                                    @if(is_array($order->shipping_address))
                                        {{ $order->shipping_address['address'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postcode'] ?? '' }}
                                    @else
                                        {{ $order->shipping_address }}
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Itemized Table -->
    @if($order->items && count($order->items) > 0)
    <tr>
        <td style="padding-bottom: 20px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <thead>
                    <tr style="background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                        <th align="left" style="padding: 10px 14px; font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase;">{{ __t('email.th_product', 'Product', [], $mailLoc) }}</th>
                        <th align="center" style="padding: 10px 10px; font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase;">{{ __t('email.th_qty', 'Qty', [], $mailLoc) }}</th>
                        <th align="right" style="padding: 10px 14px; font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase;">{{ __t('email.th_subtotal', 'Subtotal', [], $mailLoc) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px 14px; font-size: 13px; color: #0f172a; font-weight: 600;">
                            {{ $item->product_name ?? 'Seafood Item' }}
                            @if($item->product_sku)
                            <div style="font-size: 11px; color: #94a3b8; font-family: monospace;">SKU: {{ $item->product_sku }}</div>
                            @endif
                        </td>
                        <td align="center" style="padding: 12px 10px; font-size: 13px; color: #475569;">
                            {{ $item->quantity }}
                        </td>
                        <td align="right" style="padding: 12px 14px; font-size: 13px; color: #0f172a; font-weight: 700;">
                            RM {{ number_format($item->subtotal ?? ($item->unit_price * $item->quantity), 2) }}
                        </td>
                    </tr>
                    @endforeach
                    <tr style="background-color: #f8fafc;">
                        <td colspan="2" align="right" style="padding: 12px 14px; font-size: 13px; font-weight: 800; color: #0f172a;">
                            {{ __t('email.grand_total_label', 'Grand Total:', [], $mailLoc) }}
                        </td>
                        <td align="right" style="padding: 12px 14px; font-size: 16px; font-weight: 900; color: #2563eb;">
                            RM {{ number_format($order->total ?? $order->grand_total ?? 0, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    @endif

    <!-- CTA Button -->
    <tr>
        <td align="center" style="padding-bottom: 20px;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="background-color: #2563eb; border-radius: 8px;">
                        @if(!empty($order->id))
                        <a href="{{ route('account.orders.show', ['locale' => $mailLoc, 'order' => $order->id]) }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            {{ __t('email.btn_track_order', 'Track Your Order ➔', [], $mailLoc) }}
                        </a>
                        @else
                        <a href="{{ route('account.orders', ['locale' => $mailLoc]) }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            {{ __t('email.btn_view_orders', 'View Your Orders ➔', [], $mailLoc) }}
                        </a>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
