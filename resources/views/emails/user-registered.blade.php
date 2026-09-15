@extends('emails.layout')

@section('title', $user->isPending() ? 'Account Application Received' : 'Welcome to MST Import & Export')
@section('preheader', $user->isPending() ? 'Your wholesale/trading account application is under review.' : 'Welcome to MST Import & Export! Your account is active.')

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Status Badge -->
    <tr>
        <td>
            @if($user->isPending())
                <div style="display: inline-block; background-color: #fef3c7; color: #b45309; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #fde68a; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                    ⏳ Application Under Review · 审核中
                </div>
            @else
                <div style="display: inline-block; background-color: #ecfdf5; color: #047857; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #a7f3d0; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                    ✅ Account Active · 账户已激活
                </div>
            @endif
        </td>
    </tr>

    <!-- Main Greeting -->
    <tr>
        <td>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 10px 0;">
                @if($user->isPending())
                    Thank you for applying, {{ $user->name }}!
                @else
                    Welcome to MST Import &amp; Export, {{ $user->name }}!
                @endif
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                @if($user->isPending())
                    We have successfully received your <strong>{{ ucfirst($user->customer_group) }}</strong> account registration. Because you registered as a commercial partner, our accounts team is currently verifying your business details to unlock specialized tier pricing and wholesale terms.
                @else
                    Your account has been successfully created. You can now browse our catalogue of premium frozen seafood, place retail orders with continuous cold-chain delivery, and manage your delivery addresses.
                @endif
            </p>
        </td>
    </tr>

    <!-- Account Details Box -->
    <tr>
        <td style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <tr>
                    <td style="background-color: #f1f5f9; padding: 10px 16px; font-size: 12px; font-weight: 800; color: #475569; letter-spacing: 0.05em; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">
                        👤 Account Summary · 账户概览
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; line-height: 1.8;">
                            <tr>
                                <td width="35%" style="color: #64748b; font-weight: 600;">Full Name:</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Email Address:</td>
                                <td style="color: #0f172a;">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Account Tier:</td>
                                <td>
                                    <span style="display: inline-block; background-color: #eff6ff; color: #1d4ed8; font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                        {{ $user->customer_group }}
                                    </span>
                                </td>
                            </tr>
                            @if($user->phone)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Phone Number:</td>
                                <td style="color: #0f172a;">{{ $user->phone }}</td>
                            </tr>
                            @endif
                            @if($user->company_name)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Company Name:</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $user->company_name }}</td>
                            </tr>
                            @endif
                            @if($user->company_reg_no)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Registration No (SSM):</td>
                                <td style="color: #0f172a;">{{ $user->company_reg_no }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Registered At:</td>
                                <td style="color: #0f172a;">{{ $user->created_at ? $user->created_at->format('d M Y, h:i A') : date('d M Y, h:i A') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Call to Action Button -->
    <tr>
        <td align="center" style="padding-bottom: 24px;">
            @if($user->isPending())
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" style="background-color: #2563eb; border-radius: 8px;">
                            <a href="{{ route('approval.pending') }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                                Check Application Status ➔
                            </a>
                        </td>
                    </tr>
                </table>
            @else
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" style="background-color: #2563eb; border-radius: 8px;">
                            <a href="{{ route('shop.index') }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                                Start Shopping Now 🛒
                            </a>
                        </td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>

    <!-- Tier Guidance Notice -->
    @if($user->isPending())
    <tr>
        <td style="padding-bottom: 12px;">
            <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 12px 16px; border-radius: 0 8px 8px 0; font-size: 12px; color: #1e40af; line-height: 1.6;">
                <strong>What happens next?</strong> Verification usually takes <strong>1 business day</strong>. You will receive an email confirmation as soon as your wholesale account is approved by our management.
            </div>
        </td>
    </tr>
    @endif
</table>
@endsection
