<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.name', 'MST Import & Export SDN. BHD.'))</title>
    <!--[if mso]>
    <style type="text/css">
        table {border-collapse:collapse;border-spacing:0;margin:0;}
        div, td {padding:0;}
        div {margin:0 !important;}
    </style>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        
        /* Mobile styles */
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .mobile-p-16 { padding: 16px !important; }
            .mobile-stack { display: block !important; width: 100% !important; }
            .mobile-text-center { text-align: center !important; }
            .mobile-btn { width: 100% !important; text-align: center !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; color: #334155;">

    <!-- Preheader Text (invisible preview) -->
    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        @yield('preheader', 'Important notification from MST Import & Export SDN. BHD.')
    </div>

    <!-- Main Outer Wrapper -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 28px 12px 40px 12px;">
                
                <!-- Email Card Container (Max 600px) -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" class="email-container" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05); border: 1px solid #e2e8f0; width: 100%; max-width: 600px;">
                    
                    <!-- Header Bar -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 26px 24px; text-align: center;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        @php
                                            $baseUrl = \App\Models\Setting::getBaseUrl();
                                            $logoPhysicalPath = \App\Models\Setting::getLogoPhysicalPath();
                                            $logoSrc = null;
                                            if (isset($message) && is_object($message) && method_exists($message, 'embed') && $logoPhysicalPath && file_exists($logoPhysicalPath)) {
                                                try {
                                                    $logoSrc = $message->embed($logoPhysicalPath);
                                                } catch (\Throwable $e) {
                                                    $logoSrc = null;
                                                }
                                            }
                                            if (empty($logoSrc)) {
                                                $logoSrc = \App\Models\Setting::getLogoUrl();
                                            }
                                        @endphp
                                        <div style="margin-bottom: 12px;">
                                            <a href="{{ $baseUrl }}" target="_blank" style="text-decoration: none; display: inline-block;">
                                                <img src="{{ $logoSrc }}" alt="MST Import & Export" style="height: 56px; width: auto; max-width: 220px; object-fit: contain; vertical-align: middle; border-radius: 8px; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3)); display: block; margin: 0 auto;" />
                                            </a>
                                        </div>
                                        <div style="font-size: 17px; font-weight: 800; color: #ffffff; letter-spacing: 0.04em; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; text-transform: uppercase;">
                                            MST IMPORT &amp; EXPORT
                                        </div>
                                        @php
                                            $mailLoc = $mailLocale ?? current_locale();
                                        @endphp
                                        <div style="font-size: 11px; color: #94a3b8; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; margin-top: 4px;">
                                            @if($mailLoc === 'zh')
                                                镁嘉国际贸易有限公司 · (原 MIKA SEAFOOD TRADING)
                                            @elseif($mailLoc === 'bm')
                                                MST IMPORT &amp; EXPORT SDN. BHD. · Pengedaran Makanan Laut Rangkaian Sejuk
                                            @else
                                                MST IMPORT &amp; EXPORT SDN. BHD. · Cold-Chain Seafood Distribution
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Top Decorative Strip -->
                    <tr>
                        <td style="background: linear-gradient(90deg, #2563eb, #38bdf8, #10b981); height: 4px; font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <!-- Main Email Body Content -->
                    <tr>
                        <td style="padding: 32px 32px 28px 32px;" class="mobile-p-16">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Security & Help Notice -->
                    <tr>
                        <td style="padding: 0 32px 24px 32px;" class="mobile-p-16">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px;">
                                <tr>
                                    <td style="font-size: 12px; color: #64748b; line-height: 1.5;">
                                        @if($mailLoc === 'zh')
                                            <strong>需要协助？</strong>请直接通过 WhatsApp 联系客服团队 <a href="https://wa.me/601112710260" style="color: #2563eb; font-weight: 700; text-decoration: none;">+60 11-1271 0260</a> 或致电 <a href="tel:+60132800168" style="color: #2563eb; font-weight: 700; text-decoration: none;">+60 13-280 0168</a>。
                                        @elseif($mailLoc === 'bm')
                                            <strong>Perlukan bantuan?</strong> Hubungi meja sokongan kami terus melalui WhatsApp di <a href="https://wa.me/601112710260" style="color: #2563eb; font-weight: 700; text-decoration: none;">+60 11-1271 0260</a> atau hubungi kami di <a href="tel:+60132800168" style="color: #2563eb; font-weight: 700; text-decoration: none;">+60 13-280 0168</a>.
                                        @else
                                            <strong>Need assistance?</strong> Reach our support desk directly via WhatsApp at <a href="https://wa.me/601112710260" style="color: #2563eb; font-weight: 700; text-decoration: none;">+60 11-1271 0260</a> or call us at <a href="tel:+60132800168" style="color: #2563eb; font-weight: 700; text-decoration: none;">+60 13-280 0168</a>.
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 32px; text-align: center;" class="mobile-p-16">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="font-size: 12px; color: #64748b; line-height: 1.6;">
                                        <strong style="color: #1e293b;">MST IMPORT &amp; EXPORT SDN. BHD.</strong><br>
                                        7 Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
                                        <span style="color: #94a3b8; font-size: 11px;">{{ __t('email.cold_chain_cert', 'Cold-Chain Sourcing, Trading & Distribution Platform', [], $mailLoc) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 14px;">
                                        @php
                                            $locPrefix = ($mailLoc && $mailLoc !== 'en') ? '/' . $mailLoc : '';
                                        @endphp
                                        <a href="{{ $baseUrl . $locPrefix }}" style="font-size: 12px; color: #2563eb; font-weight: 700; text-decoration: none; margin: 0 8px;">{{ __t('email.visit_store', 'Visit Store', [], $mailLoc) }}</a>
                                        <span style="color: #cbd5e1;">·</span>
                                        <a href="{{ $baseUrl . $locPrefix . '/about' }}" style="font-size: 12px; color: #2563eb; font-weight: 700; text-decoration: none; margin: 0 8px;">{{ __t('email.about_us', 'About Us', [], $mailLoc) }}</a>
                                        <span style="color: #cbd5e1;">·</span>
                                        <a href="{{ $baseUrl . $locPrefix . '/contact' }}" style="font-size: 12px; color: #2563eb; font-weight: 700; text-decoration: none; margin: 0 8px;">{{ __t('email.contact_us', 'Contact Us', [], $mailLoc) }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 14px; font-size: 11px; color: #94a3b8;">
                                        © {{ date('Y') }} MST Import &amp; Export SDN. BHD. {{ __t('email.all_rights_reserved', 'All rights reserved.', [], $mailLoc) }}<br>
                                        {{ __t('email.automated_notice', 'This is an automated system email notification. Please do not reply directly to this address.', [], $mailLoc) }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                <!-- End Email Card Container -->

            </td>
        </tr>
    </table>
</body>
</html>
