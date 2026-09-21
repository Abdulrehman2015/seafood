@extends('emails.layout')

@php
    $mailLoc = $mailLocale ?? current_locale();
    $appName = config('app.name', 'MST Import & Export');
@endphp

@section('title', __t('email.account_rejected_subject', 'Update on Your Account Application — :app', ['app' => $appName], $mailLoc))
@section('preheader', __t('email.account_rejected_preheader', 'Important update regarding your account application at :app.', ['app' => $appName], $mailLoc))

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Badge -->
    <tr>
        <td>
            <div style="display: inline-block; background-color: #fef2f2; color: #dc2626; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #fecaca; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                {{ __t('email.badge_application_update', '⚠️ Application Update', [], $mailLoc) }}
            </div>
        </td>
    </tr>

    <!-- Title -->
    <tr>
        <td>
            <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 10px 0;">
                {{ __t('email.application_status_update_title', 'Application Status Update', [], $mailLoc) }}
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                {{ __t('email.account_rejected_greeting', 'Dear :name, thank you for your interest in partnering with MST Import & Export SDN. BHD.', ['name' => $user->name], $mailLoc) }}
            </p>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                {{ __t('email.account_rejected_body', 'After reviewing your :group application, our compliance team was unable to verify the complete business credentials provided. As a result, your commercial tier status could not be activated at this time.', ['group' => ucfirst($user->customer_group)], $mailLoc) }}
            </p>
        </td>
    </tr>

    @if(!empty($user->rejection_reason))
    <!-- Rejection Reason Card -->
    <tr>
        <td style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fef2f2; border: 1.5px solid #fecaca; border-radius: 12px; padding: 18px;">
                <tr>
                    <td style="font-size: 13px; font-weight: 800; color: #991b1b; padding-bottom: 8px;">
                        {{ __t('email.feedback_from_team_header', '📋 Feedback from Verification Team:', [], $mailLoc) }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 13px; color: #7f1d1d; line-height: 1.7; font-weight: 500;">
                        {{ $user->rejection_reason }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endif

    <!-- Help Box -->
    <tr>
        <td style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px; padding: 18px;">
                <tr>
                    <td style="font-size: 13px; font-weight: 800; color: #92400e; padding-bottom: 8px;">
                        {{ __t('email.how_to_reapply_header', '📌 How to Re-apply or Provide Additional Documents', [], $mailLoc) }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 13px; color: #78350f; line-height: 1.7;">
                        {{ __t('email.how_to_reapply_body', 'If you believe this was an error or would like to submit updated SSM registration documents, company profile, or premise licenses, please contact our merchant onboarding team directly.', [], $mailLoc) }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Contact Button -->
    <tr>
        <td align="center" style="padding-bottom: 20px;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="background-color: #2563eb; border-radius: 8px;">
                        <a href="https://wa.me/601112710260?text=Hello%20MST%20Team,%20I%20would%20like%20to%20inquire%20about%20my%20wholesale%20account%20application%20for%20{{ urlencode($user->email) }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            {{ __t('email.btn_chat_onboarding_support', 'Chat with Onboarding Support via WhatsApp ➔', [], $mailLoc) }}
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
