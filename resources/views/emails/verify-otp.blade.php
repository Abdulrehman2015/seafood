@extends('emails.layout')

@section('title', 'Verify Your Email Address')
@section('preheader', 'Your 6-digit verification code is ' . $otp . '. Valid for 10 minutes.')

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Security Badge -->
    <tr>
        <td>
            <div style="display: inline-block; background-color: #eff6ff; color: #1d4ed8; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #bfdbfe; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                🔐 Account Security · 账户安全验证
            </div>
        </td>
    </tr>

    <!-- Main Title -->
    <tr>
        <td>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 10px 0;">
                Verify your email address, {{ $user->name }}
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                Thank you for creating an account with <strong>MST Import &amp; Export</strong>. To verify your email and complete your registration, please enter the one-time verification code below on the confirmation screen:
            </p>
        </td>
    </tr>

    <!-- OTP Code Highlight Box -->
    <tr>
        <td style="padding: 10px 0 24px 0;" align="center">
            <table border="0" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #091a36 0%, #1e3a8a 100%); border-radius: 14px; box-shadow: 0 8px 20px rgba(30, 58, 138, 0.25); text-align: center; margin: 0 auto; width: 100%; max-width: 380px;">
                <tr>
                    <td style="padding: 24px 20px;">
                        <div style="font-size: 11px; font-weight: 700; color: #93c5fd; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">
                            One-Time Verification Code
                        </div>
                        <div style="font-family: 'Courier New', Courier, monospace; font-size: 36px; font-weight: 900; letter-spacing: 8px; color: #ffffff; padding: 8px 0; user-select: all;">
                            {{ $otp }}
                        </div>
                        <div style="font-size: 12px; color: #bfdbfe; margin-top: 8px;">
                            ⏱️ Expires in <strong>10 minutes</strong>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Security Warnings -->
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; margin-bottom: 24px;">
                <tr>
                    <td style="padding: 14px 16px; font-size: 13px; color: #92400e; line-height: 1.5;">
                        <strong>Security Notice:</strong>
                        <ul style="margin: 6px 0 0 0; padding-left: 18px;">
                            <li>Never share this code with anyone. MST staff will never ask for your code.</li>
                            <li>For security, you have a maximum of <strong>3 attempts</strong> to enter this code.</li>
                            <li>If you exceed 3 failed attempts, this code will be invalidated and you will need to request a new code.</li>
                        </ul>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Footer Help -->
    <tr>
        <td style="font-size: 13px; color: #64748b; line-height: 1.6; border-top: 1px solid #e2e8f0; padding-top: 18px;">
            If you did not initiate this registration, please disregard this message or contact our support desk immediately at <a href="mailto:info@mst.my" style="color: #2563eb; text-decoration: none; font-weight: 600;">info@mst.my</a>.
        </td>
    </tr>
</table>
@endsection
