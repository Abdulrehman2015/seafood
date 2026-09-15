@extends('emails.layout')

@section('title', 'SMTP Connection Test — MST Import & Export')
@section('preheader', 'Your SMTP mail server configuration is verified and functioning normally.')

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Badge -->
    <tr>
        <td>
            <div style="display: inline-block; background-color: #ecfdf5; color: #047857; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #a7f3d0; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                ⚡ SMTP Connected &amp; Verified
            </div>
        </td>
    </tr>

    <!-- Title -->
    <tr>
        <td>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 10px 0;">
                SMTP Email Service is Working!
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                This automated test message confirms that your email delivery gateway and SMTP settings for <strong>MST Import &amp; Export SDN. BHD.</strong> are correctly configured and capable of transmitting live outbound notifications.
            </p>
        </td>
    </tr>

    <!-- Diagnostics Details Box -->
    <tr>
        <td style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <tr>
                    <td style="background-color: #f1f5f9; padding: 10px 16px; font-size: 12px; font-weight: 800; color: #475569; letter-spacing: 0.05em; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">
                        🔍 Diagnostic Connection Details
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; line-height: 1.8;">
                            <tr>
                                <td width="40%" style="color: #64748b; font-weight: 600;">Recipient:</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $targetEmail ?? 'Admin User' }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Mail Transport:</td>
                                <td style="color: #0f172a;">{{ $mailMailer ?? config('mail.default', 'smtp') }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">SMTP Host:</td>
                                <td style="color: #0f172a; font-family: monospace;">{{ $mailHost ?? config('mail.mailers.smtp.host', 'smtp.gmail.com') }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Sender Address:</td>
                                <td style="color: #0f172a;">{{ $fromAddress ?? config('mail.from.address') }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Sender Display Name:</td>
                                <td style="color: #0f172a;">{{ $fromName ?? config('mail.from.name', 'MST Import & Export') }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Timestamp:</td>
                                <td style="color: #0f172a;">{{ now()->toDateTimeString() }} (MYT)</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Admin Link Button -->
    <tr>
        <td align="center" style="padding-bottom: 20px;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="background-color: #2563eb; border-radius: 8px;">
                        <a href="{{ route('admin.settings.index', ['tab' => 'smtp']) }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            Return to Settings Console ➔
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
