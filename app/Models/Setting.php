<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting by key with fallback.
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set/update a setting value.
     */
    public static function set(string $key, $value): static
    {
        Cache::forget("setting.{$key}");
        Cache::forget('settings.all');

        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Get dynamic base URL based on active web request domain, fallback to canonical_url or config('app.url').
     * If on local server (e.g. 127.0.0.1:8000), it returns that local server domain.
     * If on live domain (e.g. https://yourdomain.com), it returns that live domain.
     */
    public static function getBaseUrl(): string
    {
        try {
            if (request() && !app()->runningInConsole()) {
                $reqHost = request()->getSchemeAndHttpHost();
                if (!empty($reqHost)) {
                    return rtrim($reqHost, '/');
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            $canonical = static::get('canonical_url');
            if (!empty($canonical) && filter_var($canonical, FILTER_VALIDATE_URL)) {
                return rtrim($canonical, '/');
            }
        } catch (\Throwable $e) {
            // ignore
        }

        $configUrl = config('app.url', 'http://127.0.0.1:8000');
        return rtrim($configUrl, '/');
    }

    /**
     * Get absolute local filesystem path to the logo file for embedding in emails (CID embedding).
     */
    public static function getLogoPhysicalPath(): ?string
    {
        try {
            $siteLogo = static::get('site_logo');

            if (!empty($siteLogo) && !str_starts_with($siteLogo, 'http://') && !str_starts_with($siteLogo, 'https://')) {
                $cleanPath = ltrim(preg_replace('/^(\.\.\/)+/', '', $siteLogo), '/');
                if (file_exists(public_path($cleanPath))) {
                    return public_path($cleanPath);
                }
                if (file_exists(public_path('storage/' . $cleanPath))) {
                    return public_path('storage/' . $cleanPath);
                }
                if (file_exists(storage_path('app/public/' . $cleanPath))) {
                    return storage_path('app/public/' . $cleanPath);
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Check preferred PNG first (best email client compatibility), then WebP
        if (file_exists(public_path('images/logo.png'))) {
            return public_path('images/logo.png');
        }
        if (file_exists(public_path('images/logo.webp'))) {
            return public_path('images/logo.webp');
        }

        return null;
    }

    /**
     * Get absolute URL for site logo matching current active working domain.
     */
    public static function getLogoUrl(): string
    {
        $baseUrl = static::getBaseUrl();

        try {
            $siteLogo = static::get('site_logo');

            if (!empty($siteLogo)) {
                if (str_starts_with($siteLogo, 'http://') || str_starts_with($siteLogo, 'https://')) {
                    return $siteLogo;
                }
                $cleanPath = ltrim(preg_replace('/^(\.\.\/)+/', '', $siteLogo), '/');
                if (file_exists(public_path($cleanPath))) {
                    return $baseUrl . '/' . $cleanPath;
                }
                if (file_exists(public_path('storage/' . $cleanPath))) {
                    return $baseUrl . '/storage/' . $cleanPath;
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        if (file_exists(public_path('images/logo.png'))) {
            return $baseUrl . '/images/logo.png';
        }

        return $baseUrl . '/images/logo.webp';
    }

    /**
     * Get the site logo as a base64-encoded data URI for use in emails or previews.
     */
    public static function getLogoBase64(): string
    {
        $filePath = static::getLogoPhysicalPath();

        if ($filePath && file_exists($filePath)) {
            try {
                $imageData   = base64_encode(file_get_contents($filePath));
                $extension   = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                $mimeTypeMap = [
                    'webp' => 'image/webp',
                    'png'  => 'image/png',
                    'jpg'  => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'gif'  => 'image/gif',
                    'svg'  => 'image/svg+xml',
                ];
                $mimeType = $mimeTypeMap[$extension] ?? 'image/png';
                return 'data:' . $mimeType . ';base64,' . $imageData;
            } catch (\Throwable $e) {
                // Fall through to placeholder
            }
        }

        // Minimal SVG fallback
        $svgFallback = '<svg xmlns="http://www.w3.org/2000/svg" width="160" height="48" viewBox="0 0 160 48">'
            . '<rect width="160" height="48" rx="8" fill="#0f274a"/>'
            . '<text x="80" y="30" text-anchor="middle" font-family="Arial,sans-serif" '
            . 'font-size="14" font-weight="bold" fill="#38bdf8">MST Import &amp; Export</text>'
            . '</svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svgFallback);
    }

    /**
     * Get all settings as key => value array with defaults.
     */
    public static function allKeyed(): array
    {
        return Cache::remember('settings.all', 3600, function () {
            $defaults = [
                // General Settings
                'site_name'               => 'MST IMPORT AND EXPORT SDN BHD | Premium Frozen Seafood Trading & Retail',
                'site_description'        => 'Leading B2B and B2C seafood distributor, wholesale importer, and cold logistics seafood market in Malaysia and Singapore.',
                'arabic_meta_title'       => 'MST IMPORT AND EXPORT SDN BHD | المركز الرائد لتجارة وتوزيع المأكولات البحرية المجمدة',
                'arabic_meta_description' => 'أفضل منتجات المأكولات البحرية المجمدة الطازجة للبيع بالجملة والتجزئة والتوزيع في ماليزيا.',
                'meta_keywords'           => 'frozen seafood, salmon fillet, king prawns, mud crabs, wholesale seafood Malaysia, B2B seafood trading, walk-in seafood market',
                'canonical_url'           => 'https://boat-paris-taking-singer.trycloudflare.com',
                'header_tags'             => '',
                'footer_tags'             => '',
                'schema_markup'           => '{\n  "@context": "https://schema.org",\n  "@type": "SeafoodBusiness",\n  "name": "MST IMPORT AND EXPORT SDN BHD",\n  "description": "Premium Frozen Seafood Trading & Retail",\n  "currenciesAccepted": "MYR",\n  "paymentAccepted": "Cash, Credit Card, FPX Online Banking"\n}',

                // SMTP Mail Settings
                'mail_mailer'             => 'smtp',
                'mail_host'               => 'smtp.gmail.com',
                'mail_port'               => '465',
                'mail_encryption'         => 'SSL',
                'mail_username'           => 'info@mst.my',
                'mail_password'           => '',
                'mail_from_address'       => 'no-reply@mst.my',
                'mail_from_name'          => 'MST IMPORT AND EXPORT SDN BHD',
                'mail_contact_email'      => 'info@mst.my',
                'mail_secondary_email'    => 'admin@mst.my',

                // Modules Settings
                'module_walkin_flow'        => '1',
                'module_wholesale_approval' => '1',
                'module_rfq_trading'        => '1',
                'module_stock_tracking'     => '1',
                'module_online_payment'     => '1',
                'module_inquiry_emails'     => '1',

                // Multi-Currency & Exchange Rates
                'currency_auto_convert'     => '1',
                'currency_manual_rate_sgd'  => '0.3117',
                'currency_manual_rate_usd'  => '0.2453',

                // Website Tracking
                'tracking_ga4_id'         => '',
                'tracking_gtm_id'         => '',
                'tracking_fb_pixel'       => '',
                'tracking_tiktok_pixel'   => '',

                // Site Appearance
                'site_logo'               => '',
                'site_favicon'            => '',
                'primary_color'           => '#0f766e',
                'footer_copyright'        => '© ' . date('Y') . ' MST IMPORT AND EXPORT SDN BHD. All rights reserved.',

                // reCAPTCHA Settings
                'recaptcha_enabled'       => '0',
                'recaptcha_site_key'      => '',
                'recaptcha_secret_key'    => '',
                'recaptcha_on_contact'    => '1',
                'recaptcha_on_register'   => '1',

                // Site Keys & Stripe Payment Integrations
                'stripe_enabled'          => '1',
                'stripe_mode'             => 'test',
                'stripe_test_key'         => 'pk_test_YOUR_TEST_PUBLISHABLE_KEY',
                'stripe_test_secret'      => 'sk_test_YOUR_TEST_SECRET_KEY',
                'stripe_live_key'         => '',
                'stripe_live_secret'      => '',
                'stripe_key'              => '',
                'stripe_secret'           => '',
                'stripe_webhook_secret'   => '',
                'stripe_currency'         => 'MYR',
                'whatsapp_api_key'        => '',

                // Store Legacy / Physical Details
                'store_name'              => 'MST IMPORT AND EXPORT SDN BHD',
                'store_tagline'           => 'Premium Frozen Seafood Trading & Retail in Malaysia',
                'store_address'           => '7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia',
                'store_phone'             => '+60 13-280 0168',
                'store_whatsapp'          => '60123456789',
                'store_email'             => 'info@mst.my',
                'store_wholesale_email'   => 'wholesale@mst.my',
                'store_hours'             => 'Monday – Saturday: 8:00am – 6:00pm (Sunday & Public Holidays: Closed)',
                'social_facebook'         => 'https://facebook.com',
                'social_instagram'        => 'https://instagram.com',
                'social_whatsapp'         => 'https://wa.me/60123456789',
            ];

            $dbSettings = static::pluck('value', 'key')->toArray();

            return array_merge($defaults, $dbSettings);
        });
    }

    /**
     * Get active Stripe Publishable Key based on selected mode (test/live).
     */
    public static function getStripePublishableKey(): string
    {
        $mode = static::get('stripe_mode', 'test');
        if ($mode === 'live') {
            $key = static::get('stripe_live_key');
            if (empty($key) || str_contains($key, 'YOUR_PUBLISHABLE') || str_contains($key, 'YOUR_KEY')) {
                $key = static::get('stripe_key', config('services.stripe.key', ''));
            }
            return $key ?: '';
        }

        $key = static::get('stripe_test_key');
        if (empty($key) || str_contains($key, 'YOUR_TEST_PUBLISHABLE') || str_contains($key, 'YOUR_PUBLISHABLE') || str_contains($key, 'YOUR_KEY')) {
            $key = static::get('stripe_key', config('services.stripe.key', ''));
        }
        return $key ?: '';
    }

    /**
     * Get active Stripe Secret Key based on selected mode (test/live).
     */
    public static function getStripeSecretKey(): string
    {
        $mode = static::get('stripe_mode', 'test');
        if ($mode === 'live') {
            $key = static::get('stripe_live_secret');
            if (empty($key) || str_contains($key, 'YOUR_SECRET')) {
                $key = static::get('stripe_secret', config('services.stripe.secret', ''));
            }
            return $key ?: '';
        }

        $key = static::get('stripe_test_secret');
        if (empty($key) || str_contains($key, 'YOUR_TEST_SECRET') || str_contains($key, 'YOUR_SECRET')) {
            $key = static::get('stripe_secret', config('services.stripe.secret', ''));
        }
        return $key ?: '';
    }

    /**
     * Configure Stripe service dynamically from Database Settings.
     */
    public static function configureStripe(): void
    {
        try {
            $key      = static::getStripePublishableKey();
            $secret   = static::getStripeSecretKey();
            $mode     = static::get('stripe_mode', 'test');
            $currency = static::get('stripe_currency', 'MYR');

            config([
                'services.stripe.key'      => $key,
                'services.stripe.secret'   => $secret,
                'services.stripe.mode'     => $mode,
                'services.stripe.currency' => $currency,
            ]);
        } catch (\Throwable $e) {
            // Ignore during setup
        }
    }

    /**
     * Configure Laravel Mailer dynamically from Database Settings.
     */
    public static function configureMailer(): void
    {
        try {
            $mailer     = static::get('mail_mailer', 'smtp');
            $host       = static::get('mail_host', 'smtp.gmail.com');
            $port       = (int) static::get('mail_port', 465);
            $encryption = strtolower(static::get('mail_encryption', 'ssl'));
            $username   = static::get('mail_username');
            $password   = static::get('mail_password');
            $fromAddr   = static::get('mail_from_address', $username ?: 'no-reply@mst.my');
            $fromName   = static::get('mail_from_name', 'MST IMPORT AND EXPORT SDN BHD');

            $scheme = null;
            if ($port === 465 || $encryption === 'ssl') {
                $scheme = 'smtps';
            } elseif ($encryption === 'tls') {
                $scheme = 'smtp';
            }

            config([
                'mail.default'                 => $mailer,
                'mail.mailers.smtp.transport'  => 'smtp',
                'mail.mailers.smtp.scheme'     => $scheme,
                'mail.mailers.smtp.host'       => $host,
                'mail.mailers.smtp.port'       => $port,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.username'   => $username,
                'mail.mailers.smtp.password'   => $password,
                'mail.mailers.smtp.timeout'    => 15,
                'mail.from.address'            => $fromAddr,
                'mail.from.name'               => $fromName,
            ]);

            \Illuminate\Support\Facades\Mail::purge();
        } catch (\Throwable $e) {
            // Ignore during migrations / setup
        }
    }

    /**
     * Get all configured administrative recipient email addresses for order & registration alerts.
     *
     * @return array<string>
     */
    public static function getAdminNotificationEmails(): array
    {
        $emails = [];

        // 1. Primary contact / notification email in Settings
        $contactEmail = static::get('mail_contact_email');
        if (!empty($contactEmail) && filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
            $emails[] = strtolower(trim($contactEmail));
        }

        // 2. Secondary notification email in Settings
        $secondaryEmail = static::get('mail_secondary_email');
        if (!empty($secondaryEmail) && filter_var($secondaryEmail, FILTER_VALIDATE_EMAIL)) {
            $emails[] = strtolower(trim($secondaryEmail));
        }

        // 3. System admin user accounts
        try {
            $adminUsers = User::where('customer_group', 'admin')->pluck('email')->toArray();
            foreach ($adminUsers as $adminMail) {
                if (!empty($adminMail) && filter_var($adminMail, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = strtolower(trim($adminMail));
                }
            }
        } catch (\Throwable $e) {}

        // 4. Fallback to store email or admin@mst.my if list is empty
        if (empty($emails)) {
            $storeEmail = static::get('store_email', 'admin@mst.my');
            if (filter_var($storeEmail, FILTER_VALIDATE_EMAIL)) {
                $emails[] = strtolower(trim($storeEmail));
            }
        }

        return array_values(array_unique(array_filter($emails)));
    }

    /**
     * Determine if Google reCAPTCHA is globally enabled and active for a specific context.
     *
     * @param string|null $context 'contact', 'login', 'register' or null for global check
     */
    public static function isRecaptchaEnabled(?string $context = null): bool
    {
        $globallyEnabled = static::get('recaptcha_enabled', '0') === '1';
        $siteKey = static::getRecaptchaSiteKey();
        $secretKey = static::getRecaptchaSecretKey();

        // Must be globally enabled and have valid keys configured
        if (!$globallyEnabled || empty($siteKey) || empty($secretKey)) {
            return false;
        }

        if ($context === 'contact') {
            return static::get('recaptcha_on_contact', '1') === '1';
        }

        if ($context === 'login') {
            return static::get('recaptcha_on_login', '1') === '1';
        }

        if ($context === 'register') {
            return static::get('recaptcha_on_register', '1') === '1';
        }

        return true;
    }

    /**
     * Get Google reCAPTCHA Site Key with fallback.
     */
    public static function getRecaptchaSiteKey(): string
    {
        return trim(static::get('recaptcha_site_key', config('services.recaptcha.site_key', '')));
    }

    /**
     * Get Google reCAPTCHA Secret Key with fallback.
     */
    public static function getRecaptchaSecretKey(): string
    {
        return trim(static::get('recaptcha_secret_key', config('services.recaptcha.secret_key', '')));
    }

    /**
     * Verify Google reCAPTCHA response token against Google Verification API.
     *
     * @param string|null $token Response token from g-recaptcha-response
     * @param string|null $ip Remote client IP address
     * @return bool
     */
    public static function verifyRecaptcha(?string $token, ?string $ip = null): bool
    {
        if (empty($token)) {
            return false;
        }

        $secretKey = static::getRecaptchaSecretKey();
        if (empty($secretKey)) {
            return true; // Gracefully pass if secret key is not set
        }

        try {
            $response = \Illuminate\Support\Facades\Http::asForm()
                ->timeout(5)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $secretKey,
                    'response' => $token,
                    'remoteip' => $ip ?? request()->ip(),
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['success']) && $data['success'] === true;
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('reCAPTCHA verification request error: ' . $e->getMessage());
        }

        return false;
    }
}
