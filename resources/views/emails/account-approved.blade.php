@extends('emails.layout')

@php
    $mailLoc = $mailLocale ?? current_locale();
    $appName = config('app.name', 'MST Import & Export');
@endphp

@section('title', __t('email.account_approved_subject', 'Your Account Has Been Approved — :app', ['app' => $appName], $mailLoc))
@section('preheader', __t('email.account_approved_preheader', 'Great news! Your :group account has been approved.', ['group' => ucfirst($user->customer_group)], $mailLoc))

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Badge -->
    <tr>
        <td>
            <div style="display: inline-block; background-color: #ecfdf5; color: #047857; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #a7f3d0; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                {{ __t('email.badge_account_approved', '🎉 Account Approved', [], $mailLoc) }}
            </div>
        </td>
    </tr>

    <!-- Title -->
    <tr>
        <td>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 10px 0;">
                {{ __t('email.congratulations_title', 'Congratulations, :name!', ['name' => $user->name], $mailLoc) }}
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                {{ __t('email.account_approved_body', 'Your application for a :group account with MST Import & Export SDN. BHD. has been approved by our management team.', ['group' => ucfirst($user->customer_group)], $mailLoc) }}
            </p>
        </td>
    </tr>

    <!-- Unlocked Benefits Grid -->
    <tr>
        <td style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f0fdf4; border: 1.5px solid #86efac; border-radius: 12px; padding: 18px;">
                <tr>
                    <td style="font-size: 13px; font-weight: 800; color: #166534; padding-bottom: 10px; border-bottom: 1px solid #bbf7d0;">
                        {{ __t('email.unlocked_privileges_header', '✨ Unlocked :group Privileges', ['group' => ucfirst($user->customer_group)], $mailLoc) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 12px; font-size: 13px; color: #15803d; line-height: 1.8;">
                        ✔ <strong>{{ __t('email.privilege_tier_pricing', 'Exclusive Tier Pricing: Automatic discounted pricing across all seafood SKUs.', [], $mailLoc) }}</strong><br>
                        ✔ <strong>{{ __t('email.privilege_bulk_cartons', 'Bulk Carton Purchasing: Access to master carton and wholesale pallet quantities.', [], $mailLoc) }}</strong><br>
                        @if($user->customer_group === 'trading')
                        ✔ <strong>{{ __t('email.privilege_rfq_engine', 'RFQ & Quotation Engine: Submit custom procurement requests and formal quotations.', [], $mailLoc) }}</strong><br>
                        @endif
                        ✔ <strong>{{ __t('email.privilege_cold_chain', 'Priority Cold-Chain Dispatch: Scheduled refrigerated fleet delivery direct to your kitchen or warehouse.', [], $mailLoc) }}</strong><br>
                        ✔ <strong>{{ __t('email.privilege_invoicing', 'Direct Invoicing & Statements: Simplified download of invoices and purchase histories.', [], $mailLoc) }}</strong>
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
                        {{ __t('email.active_account_details_header', '📋 Active Account Details', [], $mailLoc) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; line-height: 1.8;">
                            <tr>
                                <td width="35%" style="color: #64748b; font-weight: 600;">{{ __t('email.field_email', 'Account Email:', [], $mailLoc) }}</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.field_account_tier', 'Customer Tier:', [], $mailLoc) }}</td>
                                <td>
                                    <span style="display: inline-block; background-color: #dbeafe; color: #1e40af; font-weight: 800; font-size: 11px; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                        {{ $user->customer_group }}
                                    </span>
                                </td>
                            </tr>
                            @if($user->company_name)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.field_company_name', 'Company:', [], $mailLoc) }}</td>
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
                        <a href="{{ route('login', ['locale' => $mailLoc]) }}" target="_blank" style="display: inline-block; padding: 12px 32px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            {{ __t('email.btn_sign_in_account', 'Sign In to Your Account ➔', [], $mailLoc) }}
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
