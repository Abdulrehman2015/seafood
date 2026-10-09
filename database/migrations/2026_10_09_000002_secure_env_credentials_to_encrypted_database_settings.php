<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Secure sensitive credentials into the database settings table in encrypted Hash format.
     */
    public function up(): void
    {
        // 1. SMTP Credentials (read dynamically without exposing plain secrets in git)
        $mailUsername = env('MAIL_USERNAME', config('mail.mailers.smtp.username'));
        $mailPassword = env('MAIL_PASSWORD', config('mail.mailers.smtp.password'));
        $mailHost     = env('MAIL_HOST', config('mail.mailers.smtp.host', 'smtp.gmail.com'));
        $mailPort     = env('MAIL_PORT', config('mail.mailers.smtp.port', '465'));
        $mailEnc      = env('MAIL_ENCRYPTION', config('mail.mailers.smtp.encryption', 'ssl'));
        $mailFrom     = env('MAIL_FROM_ADDRESS', config('mail.from.address', 'no-reply@mst.my'));
        $mailName     = env('MAIL_FROM_NAME', config('mail.from.name', 'MST Import and Export Sdn. Bhd.'));

        // 2. Stripe Gateway Credentials (read dynamically)
        $stripeKey    = env('STRIPE_KEY', config('services.stripe.key'));
        $stripeSecret = env('STRIPE_SECRET', config('services.stripe.secret'));

        // Store SMTP in Database (sensitive fields automatically encrypted in Hash format via Setting::set)
        if (!empty($mailPassword)) {
            Setting::set('mail_password', (string) $mailPassword);
        }
        if (!empty($mailUsername)) {
            Setting::set('mail_username', (string) $mailUsername);
        }
        if (!empty($mailHost)) {
            Setting::set('mail_host', (string) $mailHost);
        }
        if (!empty($mailPort)) {
            Setting::set('mail_port', (string) $mailPort);
        }
        if (!empty($mailEnc)) {
            Setting::set('mail_encryption', (string) $mailEnc);
        }
        if (!empty($mailFrom)) {
            Setting::set('mail_from_address', (string) $mailFrom);
        }
        if (!empty($mailName)) {
            Setting::set('mail_from_name', (string) $mailName);
        }

        // Store Stripe in Database (sensitive fields automatically encrypted in Hash format via Setting::set)
        if (!empty($stripeKey)) {
            Setting::set('stripe_test_key', (string) $stripeKey);
            Setting::set('stripe_key', (string) $stripeKey);
        }
        if (!empty($stripeSecret)) {
            Setting::set('stripe_test_secret', (string) $stripeSecret);
            Setting::set('stripe_secret', (string) $stripeSecret);
        }
        Setting::set('stripe_enabled', '1');
        Setting::set('stripe_mode', 'test');

        // 3. Scan and re-encrypt any existing sensitive settings in Hash format
        try {
            $rows = DB::table('settings')->get();
            foreach ($rows as $row) {
                if (Setting::isSensitiveKey($row->key) && !empty($row->value) && !str_starts_with($row->value, 'enc:')) {
                    DB::table('settings')->where('id', $row->id)->update([
                        'value'      => Setting::prepareValueForStorage($row->key, $row->value),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Ignore during early bootstrap
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe to keep
    }
};
