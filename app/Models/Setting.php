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
     * Get dynamic base URL based on active web request domain, fallback to config('app.url').
     */
    public static function getBaseUrl(): string
    {
        try {
            $reqHost = (request() && !app()->runningInConsole()) ? request()->getSchemeAndHttpHost() : null;
            $baseUrl = !empty($reqHost) ? $reqHost : rtrim(config('app.url', 'http://127.0.0.1:8000'), '/');
        } catch (\Throwable $e) {
            $baseUrl = rtrim(config('app.url', 'http://127.0.0.1:8000'), '/');
        }
        return rtrim($baseUrl, '/');
    }

    /**
     * Get absolute URL for site logo matching current active working domain.
     */
    public static function getLogoUrl(): string
    {
        $baseUrl = static::getBaseUrl();
        $siteLogo = static::get('site_logo');

        if (!empty($siteLogo)) {
            if (str_starts_with($siteLogo, 'http://') || str_starts_with($siteLogo, 'https://')) {
                return $siteLogo;
            }
            if (str_contains($siteLogo, 'images/')) {
                $cleanPath = ltrim(preg_replace('/^(\.\.\/)+/', '', $siteLogo), '/');
                return $baseUrl . '/' . $cleanPath;
            }
            if (file_exists(public_path('storage/' . ltrim($siteLogo, '/')))) {
                return $baseUrl . '/storage/' . ltrim($siteLogo, '/');
            }
            if (file_exists(public_path(ltrim($siteLogo, '/')))) {
                return $baseUrl . '/' . ltrim($siteLogo, '/');
            }
        }

        return $baseUrl . '/images/logo.webp';
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
}
