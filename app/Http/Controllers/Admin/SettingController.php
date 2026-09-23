<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function __construct(protected ImageUploadService $imageService) {}

    public function index(Request $request)
    {
        $settings = Setting::allKeyed();
        $activeTab = $request->input('tab', session('tab', 'general'));
        $currencyService = app(\App\Services\CurrencyService::class);
        $liveRates = $currencyService->getRates();

        return view('admin.settings.index', compact('settings', 'activeTab', 'liveRates'));
    }

    public function update(Request $request)
    {
        $tab = $request->input('tab', 'general');

        $request->validate([
            'site_logo'    => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:8192',
            'site_favicon' => 'nullable|file|mimes:jpeg,png,jpg,webp,ico,gif|max:2048',
        ]);

        // Extract and process any uploaded appearance images
        if ($request->hasFile('site_logo')) {
            $media = $this->imageService->upload($request->file('site_logo'), 'gallery', 'Site Logo');
            Setting::set('site_logo', $media->path);
        } elseif ($request->filled('gallery_site_logo')) {
            Setting::set('site_logo', $request->gallery_site_logo);
        }

        if ($request->hasFile('site_favicon')) {
            $media = $this->imageService->upload($request->file('site_favicon'), 'gallery', 'Site Favicon');
            Setting::set('site_favicon', $media->path);
        } elseif ($request->filled('gallery_site_favicon')) {
            Setting::set('site_favicon', $request->gallery_site_favicon);
        }

        // List of all savable setting keys
        $allowedKeys = [
            // General Settings
            'site_name', 'site_description',
            'meta_keywords', 'canonical_url', 'header_tags', 'footer_tags', 'schema_markup',

            // SMTP Settings
            'mail_mailer', 'mail_host', 'mail_port', 'mail_encryption', 'mail_username',
            'mail_password', 'mail_from_address', 'mail_from_name', 'mail_contact_email', 'mail_secondary_email',

            // Modules Settings
            'module_walkin_flow', 'module_wholesale_approval', 'module_rfq_trading',
            'module_stock_tracking', 'module_online_payment', 'module_inquiry_emails',

            // Multi-Currency & Exchange Rates
            'currency_auto_convert', 'currency_manual_rate_sgd', 'currency_manual_rate_usd',

            // Website Tracking
            'tracking_ga4_id', 'tracking_gtm_id', 'tracking_fb_pixel', 'tracking_tiktok_pixel',

            // Site Appearance
            'primary_color', 'footer_copyright',

            // reCAPTCHA
            'recaptcha_enabled', 'recaptcha_site_key', 'recaptcha_secret_key',
            'recaptcha_on_contact', 'recaptcha_on_login', 'recaptcha_on_register',

            // Payment & Stripe Settings
            'stripe_enabled', 'stripe_mode', 'stripe_test_key', 'stripe_test_secret',
            'stripe_live_key', 'stripe_live_secret', 'stripe_currency', 'stripe_webhook_secret',

            // Site Keys
            'stripe_key', 'stripe_secret', 'whatsapp_api_key', 'webhook_signing_secret',

            // Store legacy contact
            'store_name', 'store_company_zh', 'store_tagline', 'store_address', 'store_phone',
            'store_phone_2', 'store_phone_3', 'store_map_url',
            'store_whatsapp', 'store_email', 'store_wholesale_email', 'store_hours',
            'social_facebook', 'social_instagram', 'social_whatsapp',
        ];

        // Process module checkboxes (if unchecked, they won't be in request, so default to 0 if updating modules tab)
        if ($tab === 'modules') {
            foreach ([
                'module_walkin_flow', 'module_wholesale_approval', 'module_rfq_trading',
                'module_stock_tracking', 'module_online_payment', 'module_inquiry_emails'
            ] as $modKey) {
                Setting::set($modKey, $request->has($modKey) ? '1' : '0');
            }
        }

        if ($tab === 'recaptcha') {
            Setting::set('recaptcha_enabled', $request->has('recaptcha_enabled') ? '1' : '0');
            Setting::set('recaptcha_on_contact', $request->has('recaptcha_on_contact') ? '1' : '0');
            Setting::set('recaptcha_on_login', $request->has('recaptcha_on_login') ? '1' : '0');
            Setting::set('recaptcha_on_register', $request->has('recaptcha_on_register') ? '1' : '0');
        }

        if ($tab === 'payment') {
            Setting::set('stripe_enabled', $request->has('stripe_enabled') ? '1' : '0');
        }

        if ($tab === 'currency') {
            Setting::set('currency_auto_convert', $request->has('currency_auto_convert') ? '1' : '0');
        }

        foreach ($request->only($allowedKeys) as $key => $value) {
            // Do not overwrite password or secret keys with empty string if user left it blank
            if (in_array($key, ['mail_password', 'stripe_test_secret', 'stripe_live_secret']) && empty($value)) {
                continue;
            }
            Setting::set($key, is_null($value) ? '' : $value);
        }

        // If site_name updated, sync store_name for consistency
        if ($request->filled('site_name')) {
            Setting::set('store_name', $request->site_name);
        }

        // Reconfigure mailer & Stripe dynamically if settings changed
        Setting::configureMailer();
        Setting::configureStripe();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Settings saved successfully.']);
        }

        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Settings updated successfully.');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        Setting::configureMailer();

        $targetEmail = $request->email;
        $mailHost = Setting::get('mail_host', config('mail.mailers.smtp.host', 'smtp.gmail.com'));
        $fromAddress = Setting::get('mail_from_address', config('mail.from.address', 'no-reply@mst.my'));
        $fromName = Setting::get('mail_from_name', config('mail.from.name', 'MST Import & Export'));
        $mailMailer = Setting::get('mail_mailer', config('mail.default', 'smtp'));

        if (in_array(strtolower($mailMailer), ['log', 'array', 'null'])) {
            return back()->with('error', "⚠️ Mailer is set to '{$mailMailer}' which logs emails locally instead of sending over SMTP. Set mailer to 'smtp' to test real delivery.")->with('tab', 'smtp');
        }

        try {
            // Dispatch rich HTML Test SMTP Email
            Mail::to($targetEmail)->send(new \App\Mail\TestSmtpMail(
                targetEmail: $targetEmail,
                mailHost: $mailHost,
                fromAddress: $fromAddress,
                fromName: $fromName,
                mailMailer: $mailMailer
            ));

            return back()->with('success', "✅ Live test email successfully sent to {$targetEmail} via SMTP ({$mailHost})! Please check your inbox.")->with('tab', 'smtp');
        } catch (\Throwable $e) {
            return back()->with('error', "❌ SMTP Error: " . $e->getMessage())->with('tab', 'smtp');
        }
    }

    /**
     * Test Stripe API Connection with active credentials.
     */
    public function testStripe(Request $request)
    {
        Setting::configureStripe();

        $mode      = Setting::get('stripe_mode', 'test');
        $secretKey = Setting::getStripeSecretKey();

        if (empty($secretKey) || str_contains($secretKey, 'YOUR_TEST_SECRET') || str_contains($secretKey, 'YOUR_SECRET')) {
            return back()->with('error', "⚠️ Please enter your valid Stripe Secret Key for '{$mode}' mode first.")->with('tab', 'payment');
        }

        try {
            \Stripe\Stripe::setApiKey($secretKey);
            $account = \Stripe\Account::retrieve();

            $accountName = $account->business_profile->name ?? $account->settings->dashboard->display_name ?? $account->id;
            $country = strtoupper($account->country ?? 'MY');
            $currency = strtoupper($account->default_currency ?? 'MYR');

            return back()->with('success', "✅ Stripe API Connection Verified! Mode: [" . strtoupper($mode) . "] · Account: {$accountName} (ID: {$account->id}, Country: {$country}, Currency: {$currency})")->with('tab', 'payment');
        } catch (\Throwable $e) {
            return back()->with('error', "❌ Stripe Connection Error [" . strtoupper($mode) . " Mode]: " . $e->getMessage())->with('tab', 'payment');
        }
    }
}
