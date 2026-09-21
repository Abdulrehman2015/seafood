@extends('emails.layout')

@php
    $mailLoc = $mailLocale ?? current_locale();
    $appName = config('app.name', 'MST Import & Export');
    $qNum = $quotation->quotation_number ?? '#' . $quotation->id;
@endphp

@section('title', __t('email.quotation_ready_subject', 'Quotation Ready: :quotation — :app', ['quotation' => $qNum, 'app' => $appName], $mailLoc))
@section('preheader', __t('email.quotation_ready_preheader', 'Your formal wholesale quotation :quotation is ready for review.', ['quotation' => $qNum], $mailLoc))

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Badge -->
    <tr>
        <td>
            <div style="display: inline-block; background-color: #f5f3ff; color: #7c3aed; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #ddd6fe; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                {{ __t('email.badge_quotation_prepared', '📑 Formal Quotation Prepared', [], $mailLoc) }}
            </div>
        </td>
    </tr>

    <!-- Title -->
    <tr>
        <td>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 10px 0;">
                {{ __t('email.quotation_title', 'Quotation #:quotation', ['quotation' => $qNum], $mailLoc) }}
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                {{ __t('email.quotation_ready_body', 'Dear :name, our commercial trading team has prepared your customized bulk seafood quotation. You can review the item breakdown and accept the quotation online.', ['name' => $quotation->user?->name ?? 'Valued Customer'], $mailLoc) }}
            </p>
        </td>
    </tr>

    <!-- Quotation Summary Card -->
    <tr>
        <td style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <tr>
                    <td style="background-color: #f1f5f9; padding: 10px 16px; font-size: 12px; font-weight: 800; color: #475569; letter-spacing: 0.05em; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">
                        {{ __t('email.quotation_details_header', '📋 Quotation Details', [], $mailLoc) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; line-height: 1.8;">
                            <tr>
                                <td width="40%" style="color: #64748b; font-weight: 600;">{{ __t('email.quotation_number_label', 'Quotation Number:', [], $mailLoc) }}</td>
                                <td style="color: #0f172a; font-weight: 800; font-family: monospace;">{{ $qNum }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.payment_status_label', 'Status:', [], $mailLoc) }}</td>
                                <td>
                                    <span style="display: inline-block; background-color: #ecfdf5; color: #059669; font-weight: 800; font-size: 11px; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                        {{ ucfirst($quotation->status ?? 'Ready') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.quotation_total_value_label', 'Total Quoted Value:', [], $mailLoc) }}</td>
                                <td style="color: #2563eb; font-weight: 900; font-size: 16px;">RM {{ number_format($quotation->total_amount ?? $quotation->total ?? 0, 2) }}</td>
                            </tr>
                            @if(!empty($quotation->valid_until))
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ __t('email.valid_until_label', 'Valid Until:', [], $mailLoc) }}</td>
                                <td style="color: #b45309; font-weight: 700;">{{ \Carbon\Carbon::parse($quotation->valid_until)->format('d M Y') }}</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- CTA Button -->
    <tr>
        <td align="center" style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="background-color: #2563eb; border-radius: 8px;">
                        @if(!empty($quotation->id))
                        <a href="{{ route('quotations.show', ['locale' => $mailLoc, 'quotation' => $quotation->id]) }}" target="_blank" style="display: inline-block; padding: 12px 32px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            {{ __t('email.btn_review_quotation', 'Review & Accept Quotation ➔', [], $mailLoc) }}
                        </a>
                        @else
                        <a href="{{ route('account.dashboard', ['locale' => $mailLoc]) }}" target="_blank" style="display: inline-block; padding: 12px 32px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            {{ __t('email.btn_view_dashboard', 'View My Dashboard ➔', [], $mailLoc) }}
                        </a>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
