@extends('emails.layout')

@section('title', 'Your Account Has Been Approved — MST Import & Export')
@section('preheader', 'Great news! Your ' . ucfirst($user->customer_group) . ' account has been approved.')

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Badge -->
    <tr>
        <td>
            <div style="display: inline-block; background-color: #ecfdf5; color: #047857; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #a7f3d0; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                🎉 Account Approved · 审核通过
            </div>
        </td>
    </tr>

    <!-- Title -->
    <tr>
        <td>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 10px 0;">
                Congratulations, {{ $user->name }}!
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                Your application for a <strong>{{ ucfirst($user->customer_group) }}</strong> account with <strong>MST Import &amp; Export SDN. BHD.</strong> has been approved by our management team.
            </p>
        </td>
    </tr>

    <!-- Unlocked Benefits Grid -->
    <tr>
        <td style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f0fdf4; border: 1.5px solid #86efac; border-radius: 12px; padding: 18px;">
                <tr>
                    <td style="font-size: 13px; font-weight: 800; color: #166534; padding-bottom: 10px; border-bottom: 1px solid #bbf7d0;">
                        ✨ Unlocked {{ ucfirst($user->customer_group) }} Privileges
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 12px; font-size: 13px; color: #15803d; line-height: 1.8;">
                        ✔ <strong>Exclusive Tier Pricing:</strong> Automatic discounted pricing across all seafood SKUs.<br>
                        ✔ <strong>Bulk Carton Purchasing:</strong> Access to master carton and wholesale pallet quantities.<br>
                        @if($user->customer_group === 'trading')
                        ✔ <strong>RFQ &amp; Quotation Engine:</strong> Submit custom procurement requests and formal quotations.<br>
                        @endif
                        ✔ <strong>Priority Cold-Chain Dispatch:</strong> Scheduled refrigerated fleet delivery direct to your kitchen or warehouse.<br>
                        ✔ <strong>Direct Invoicing &amp; Statements:</strong> Simplified download of invoices and purchase histories.
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Account Details Table -->
    <tr>
        <td style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <tr>
                    <td style="background-color: #f1f5f9; padding: 10px 16px; font-size: 12px; font-weight: 800; color: #475569; letter-spacing: 0.05em; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">
                        📋 Active Account Details
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; line-height: 1.8;">
                            <tr>
                                <td width="35%" style="color: #64748b; font-weight: 600;">Account Email:</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Customer Tier:</td>
                                <td>
                                    <span style="display: inline-block; background-color: #dbeafe; color: #1e40af; font-weight: 800; font-size: 11px; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                        {{ $user->customer_group }}
                                    </span>
                                </td>
                            </tr>
                            @if($user->company_name)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Company:</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $user->company_name }}</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Login CTA Button -->
    <tr>
        <td align="center" style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="background-color: #2563eb; border-radius: 8px;">
                        <a href="{{ route('login') }}" target="_blank" style="display: inline-block; padding: 12px 32px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            Sign In to Your Account ➔
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
