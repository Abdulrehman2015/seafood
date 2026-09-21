@extends('emails.layout')

@php
    $mailLoc = $mailLocale ?? current_locale();
    $appName = config('app.name', 'MST Import & Export');
@endphp

@section('title', $user->isPending() ? __t('email.user_registered_subject_pending', 'Account Application Received — :app', ['app' => $appName], $mailLoc) : __t('email.user_registered_subject_active', 'Welcome to :app!', ['app' => $appName], $mailLoc))
@section('preheader', $user->isPending() ? __t('email.user_registered_preheader_pending', 'Your wholesale/trading account application is under review.', [], $mailLoc) : __t('email.user_registered_preheader_active', 'Welcome to :app! Your account is active.', ['app' => $appName], $mailLoc))

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Status Badge -->
    <tr>
        <td>
            @if($user->isPending())
                <div style="display: inline-block; background-color: #fef3c7; color: #b45309; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #fde68a; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                    {{ __t('email.badge_app_under_review', '⏳ Application Under Review', [], $mailLoc) }}
                </div>
            @else
                <div style="display: inline-block; background-color: #ecfdf5; color: #047857; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #a7f3d0; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                    {{ __t('email.badge_account_active', '✅ Account Active', [], $mailLoc) }}
                </div>
            @endif
        </td>
    </tr>

    <!-- Main Greeting -->
    <tr>
        <td>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 10px 0;">
                @if($user->isPending())
                    {{ __t('email.thank_you_for_applying', 'Thank you for applying, :name!', ['name' => $user->name], $mailLoc) }}
                @else
                    {{ __t('email.welcome_user_title', 'Welcome to :app, :name!', ['app' => $appName, 'name' => $user->name], $mailLoc) }}
                @endif
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                @if($user->isPending())
                    {{ __t('email.user_registered_body_pending', 'We have successfully received your :group account registration. Because you registered as a commercial partner, our accounts team is currently verifying your business details to unlock specialized tier pricing and wholesale terms.', ['group' => ucfirst($user->customer_group)], $mailLoc) }}
                @else
                    {{ __t('email.user_registered_body_active', 'Your account has been successfully created. You can now browse our catalogue of premium frozen seafood, place retail orders with continuous cold-chain delivery, and manage your delivery addresses.', [], $mailLoc) }}
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
                        {{ __t('email.account_summary_header', '👤 Account Summary', [], $mailLoc) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; line-height: 1.8;">
                            <tr>
                                <td width="35%" style="color: #64748b; font-weight: 600;">{{ __t('email.field_full_name', 'Full Name:', [], $mailLoc) }}</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.field_email', 'Email Address:', [], $mailLoc) }}</td>
                                <td style="color: #0f172a;">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.field_account_tier', 'Account Tier:', [], $mailLoc) }}</td>
                                <td>
                                    <span style="display: inline-block; background-color: #eff6ff; color: #1d4ed8; font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                        {{ $user->customer_group }}
                                    </span>
                                </td>
                            </tr>
                            @if($user->phone)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.field_phone', 'Phone Number:', [], $mailLoc) }}</td>
                                <td style="color: #0f172a;">{{ $user->phone }}</td>
                            </tr>
                            @endif
                            @if($user->company_name)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.field_company_name', 'Company Name:', [], $mailLoc) }}</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $user->company_name }}</td>
                            </tr>
                            @endif
                            @if($user->company_reg_no)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.field_company_reg_no', 'Registration No (SSM):', [], $mailLoc) }}</td>
                                <td style="color: #0f172a;">{{ $user->company_reg_no }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.field_registered_at', 'Registered At:', [], $mailLoc) }}</td>
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
                            <a href="{{ route('approval.pending', ['locale' => $mailLoc]) }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                                {{ __t('email.btn_check_status', 'Check Application Status ➔', [], $mailLoc) }}
                            </a>
                        </td>
                    </tr>
                </table>
            @else
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" style="background-color: #2563eb; border-radius: 8px;">
                            <a href="{{ route('shop.index', ['locale' => $mailLoc]) }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                                {{ __t('email.btn_start_shopping', 'Start Shopping Now 🛒', [], $mailLoc) }}
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
                <strong>{{ __t('email.what_happens_next_title', 'What happens next?', [], $mailLoc) }}</strong> {{ __t('email.what_happens_next_text', 'Verification usually takes 1 business day. You will receive an email confirmation as soon as your wholesale account is approved by our management.', [], $mailLoc) }}
            </div>
        </td>
    </tr>
    @endif
</table>
@endsection
