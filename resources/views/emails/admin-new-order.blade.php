@extends('emails.layout')

@section('title', 'New Order Received #' . ($order->order_number ?? $order->id))
@section('preheader', 'New order #' . ($order->order_number ?? $order->id) . ' placed by ' . ($order->customer_name ?? 'Customer') . ' (RM ' . number_format($order->total ?? 0, 2) . ')')

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Badge -->
    <tr>
        <td>
            <div style="display: inline-block; background-color: #eff6ff; color: #1d4ed8; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #bfdbfe; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                🔔 New Order Alert · 新订单通知
            </div>
        </td>
    </tr>

    <!-- Title -->
    <tr>
        <td>
            <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 8px 0;">
                New Order: #{{ $order->order_number ?? $order->id }}
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                A new order worth <strong>RM {{ number_format($order->total ?? 0, 2) }}</strong> has been placed on <strong>MST Import &amp; Export</strong>. Please review fulfillment requirements below:
            </p>
        </td>
    </tr>

    <!-- Order Summary Card -->
    <tr>
        <td style="padding-bottom: 20px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <tr>
                    <td style="background-color: #f1f5f9; padding: 10px 16px; font-size: 12px; font-weight: 800; color: #475569; letter-spacing: 0.05em; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">
                        📋 Order Overview
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; line-height: 1.8;">
                            <tr>
                                <td width="35%" style="color: #64748b; font-weight: 600;">Customer:</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $order->customer_name }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Customer Tier:</td>
                                <td>
                                    <span style="display: inline-block; background-color: #e0e7ff; color: #3730a3; font-weight: 800; font-size: 11px; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                        {{ $order->customer_group ?? 'Retail' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Contact:</td>
                                <td style="color: #0f172a;">
                                    {{ $order->customer_phone ?? 'N/A' }}
                                    @if($order->customer_email) · <a href="mailto:{{ $order->customer_email }}" style="color: #2563eb;">{{ $order->customer_email }}</a>@endif
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Total Amount:</td>
                                <td style="color: #2563eb; font-weight: 900; font-size: 15px;">RM {{ number_format($order->total ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Payment Status:</td>
                                <td style="color: #16a34a; font-weight: 700;">{{ ucfirst($order->payment_status ?? 'Paid') }} ({{ $order->payment_method ?? 'Stripe' }})</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Fulfillment:</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $order->fulfillment_type === 'self_collection' ? '🏪 Self-Collection' : '🚚 Delivery' }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Placed At:</td>
                                <td style="color: #0f172a;">{{ $order->created_at ? $order->created_at->format('d M Y, h:i:s A') : date('d M Y, h:i:s A') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Admin CTA Button -->
    <tr>
        <td align="center" style="padding-bottom: 20px;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="background-color: #2563eb; border-radius: 8px;">
                        @if(!empty($order->id))
                        <a href="{{ route('admin.orders.show', $order->id) }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            Manage Order in Admin Console ➔
                        </a>
                        @else
                        <a href="{{ route('admin.orders.index') }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            View All Orders ➔
                        </a>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
