@extends('emails.layout')

@php
    $mailLoc = $mailLocale ?? current_locale();
    $orderNo = $order->order_number ?? '#' . $order->id;
    $appName = config('app.name', 'MST Import and Export Sdn. Bhd.');
    $isWalkin = $order->isWalkin();
    $targetDate = $order->confirmed_date ?: ($isWalkin ? $order->collection_date : $order->delivery_date) ?: date('Y-m-d');
    $targetTime = $order->confirmed_time ?: $order->collection_time ?: 'During Operational Hours';
@endphp

@section('title', $isWalkin 
    ? __t('email.collection_updated_subject', 'Your Self-Collection Schedule Date is Updated: Order :order — :app', ['order' => $orderNo, 'date' => $targetDate, 'app' => $appName], $mailLoc)
    : __t('email.delivery_updated_subject', 'Your Delivery Schedule Date is Updated: Order :order — :app', ['order' => $orderNo, 'date' => $targetDate, 'app' => $appName], $mailLoc)
)

@section('preheader', $isWalkin 
    ? __t('email.collection_updated_preheader', 'Your Self-Collection Schedule date for order #:order has been updated to :date (:time).', ['order' => $orderNo, 'date' => $targetDate, 'time' => $targetTime], $mailLoc)
    : __t('email.delivery_updated_preheader', 'Your delivery schedule date for order #:order has been updated to :date.', ['order' => $orderNo, 'date' => $targetDate], $mailLoc)
)

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Badge -->
    <tr>
        <td>
            <div style="display: inline-block; background-color: {{ $isWalkin ? '#ecfdf5' : '#eff6ff' }}; color: {{ $isWalkin ? '#047857' : '#1d4ed8' }}; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid {{ $isWalkin ? '#a7f3d0' : '#bfdbfe' }}; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                {{ $isWalkin ? '🏪 ' . __t('email.badge_collection_updated', 'Self-Collection Schedule Updated', [], $mailLoc) : '🚚 ' . __t('email.badge_delivery_updated', 'Delivery Schedule Updated', [], $mailLoc) }}
            </div>
        </td>
    </tr>

    <!-- Title -->
    <tr>
        <td>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 8px 0;">
                @if($isWalkin)
                    {{ __t('email.collection_updated_title', 'Your Self-Collection Schedule Date is Updated!', ['name' => $order->customer_name ?? 'Valued Customer'], $mailLoc) }}
                @else
                    {{ __t('email.delivery_updated_title', 'Your Delivery Schedule Date is Updated!', ['name' => $order->customer_name ?? 'Valued Customer'], $mailLoc) }}
                @endif
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                @if($isWalkin)
                    {{ __t('email.collection_updated_body', 'Hello :name, your Self-Collection Schedule date for order #:order has been updated. You can collect your product at our SILC Facility Counter 2 on the updated date and time window below.', ['name' => $order->customer_name ?? 'Customer', 'order' => $orderNo], $mailLoc) }}
                @else
                    {{ __t('email.delivery_updated_body', 'Hello :name, your cold-chain delivery schedule date for order #:order has been updated to :date.', ['name' => $order->customer_name ?? 'Customer', 'order' => $orderNo, 'date' => $targetDate], $mailLoc) }}
                @endif
            </p>
        </td>
    </tr>

    <!-- Highlighted Schedule Banner Box -->
    <tr>
        <td style="padding-bottom: 20px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: {{ $isWalkin ? '#f0fdf4' : '#eff6ff' }}; border: 2px solid {{ $isWalkin ? '#86efac' : '#93c5fd' }}; border-radius: 12px; overflow: hidden;">
                <tr>
                    <td style="padding: 18px 20px;">
                        <div style="font-size: 12px; font-weight: 800; color: {{ $isWalkin ? '#166534' : '#1e40af' }}; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                            {{ $isWalkin ? '📅 ' . __t('email.confirmed_collection_date_label', 'Confirmed Collection Schedule', [], $mailLoc) : '📅 ' . __t('email.confirmed_delivery_date_label', 'Confirmed Delivery Schedule', [], $mailLoc) }}
                        </div>
                        <div style="font-size: 20px; font-weight: 900; color: #0f172a; margin-bottom: 4px;">
                            {{ $targetDate }}
                        </div>
                        @if($isWalkin && $targetTime)
                        <div style="font-size: 14px; font-weight: 700; color: #047857;">
                            ⏰ {{ $targetTime }}
                        </div>
                        @endif

                        @if($order->collection_token)
                        <div style="margin-top: 12px; padding-top: 10px; border-top: 1px dashed {{ $isWalkin ? '#86efac' : '#93c5fd' }}; display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ __t('email.collection_token_label', 'Collection Token:', [], $mailLoc) }}</span>
                            <span style="display: inline-block; background-color: #0f766e; color: #ffffff; font-weight: 900; font-size: 14px; padding: 3px 10px; border-radius: 6px;">
                                🎟 {{ $order->collection_token }}
                            </span>
                        </div>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Custom Admin Message / Notice (if any) -->
    @if(!empty($customMessage) || !empty($order->notification_notes))
    <tr>
        <td style="padding-bottom: 20px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fffbeb; border: 1px solid #fef3c7; border-left: 4px solid #f59e0b; border-radius: 8px;">
                <tr>
                    <td style="padding: 14px 16px;">
                        <div style="font-size: 12px; font-weight: 800; color: #92400e; text-transform: uppercase; margin-bottom: 4px;">
                            📌 {{ __t('email.admin_special_instructions', 'Important Note from MST Facility', [], $mailLoc) }}
                        </div>
                        <div style="font-size: 13px; color: #78350f; line-height: 1.5;">
                            {{ $customMessage ?: $order->notification_notes }}
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endif

    <!-- Location / Address Details -->
    <tr>
        <td style="padding-bottom: 20px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <tr>
                    <td style="background-color: #f1f5f9; padding: 10px 16px; font-size: 12px; font-weight: 800; color: #475569; letter-spacing: 0.05em; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">
                        {{ $isWalkin ? '📍 ' . __t('email.collection_point_info', 'Collection Point & Instructions', [], $mailLoc) : '📍 ' . __t('email.destination_info', 'Delivery Address', [], $mailLoc) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px; font-size: 13px; line-height: 1.6; color: #1e293b;">
                        @if($isWalkin)
                            <strong>MST Import &amp; Export Cold-Chain Facility — Counter 2</strong><br>
                            No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor Bahru, Johor, Malaysia<br>
                            <span style="font-size: 12px; color: #64748b;">(Please present your Collection Token <strong>{{ $order->collection_token ?? '#' . $orderNo }}</strong> at Counter 2)</span>
                            <div style="margin-top: 10px;">
                                <a href="https://maps.google.com/?q=MST+Counter+2+7+Jalan+SILC+2/18+SILC+Industrial+Park+Iskandar+Puteri+Johor" target="_blank" style="color: #2563eb; font-weight: 700; text-decoration: none; font-size: 12px;">
                                    🗺️ {{ __t('email.view_on_google_maps', 'Open Location in Google Maps ↗', [], $mailLoc) }}
                                </a>
                            </div>
                        @else
                            @if(is_array($order->shipping_address))
                                <strong>{{ $order->customer_name }}</strong><br>
                                {{ $order->shipping_address['address'] ?? '' }}<br>
                                {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postcode'] ?? '' }}
                            @else
                                {{ $order->shipping_address }}
                            @endif
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Itemized List -->
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
                        <td style="padding: 10px 14px; font-size: 13px; color: #0f172a; font-weight: 600;">
                            {{ $item->product_name ?? 'Seafood Item' }}
                        </td>
                        <td align="center" style="padding: 10px 10px; font-size: 13px; color: #475569;">
                            {{ $item->quantity }}
                        </td>
                        <td align="right" style="padding: 10px 14px; font-size: 13px; color: #0f172a; font-weight: 700;">
                            RM {{ number_format($item->subtotal ?? ($item->unit_price * $item->quantity), 2) }}
                        </td>
                    </tr>
                    @endforeach
                    <tr style="background-color: #f8fafc;">
                        <td colspan="2" align="right" style="padding: 10px 14px; font-size: 13px; font-weight: 800; color: #0f172a;">
                            {{ __t('email.grand_total_label', 'Grand Total:', [], $mailLoc) }}
                        </td>
                        <td align="right" style="padding: 10px 14px; font-size: 15px; font-weight: 900; color: #0f766e;">
                            RM {{ number_format($order->total ?? 0, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    @endif

    <!-- CTA Button -->
    <tr>
        <td align="center" style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="background-color: #0f766e; border-radius: 8px;">
                        <a href="{{ route('checkout.success', ['order' => $order->id]) }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            {{ __t('email.btn_track_live_order', 'View Live Customer Order Tracker ➔', [], $mailLoc) }}
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
