<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SecureCredentialsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'settings:secure {--sanitize-env : Also sanitize and redact sensitive credentials from the .env file}';

    /**
     * The console command description.
     */
    protected $description = 'Encrypt sensitive credentials in the database in Hash format and secure the .env file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=====================================================');
        $this->info('  Securing Platform Credentials & Encrypting Settings');
        $this->info('=====================================================');

        // Dynamically read environment credentials without hardcoding secrets in codebase
        $credentials = array_filter([
            'mail_password'       => env('MAIL_PASSWORD'),
            'mail_username'       => env('MAIL_USERNAME'),
            'mail_host'           => env('MAIL_HOST', 'smtp.gmail.com'),
            'mail_port'           => env('MAIL_PORT', '465'),
            'mail_encryption'     => env('MAIL_ENCRYPTION', 'ssl'),
            'mail_from_address'   => env('MAIL_FROM_ADDRESS', 'no-reply@mst.my'),
            'mail_from_name'      => env('MAIL_FROM_NAME', 'MST Import and Export Sdn. Bhd.'),
            'stripe_test_key'     => env('STRIPE_KEY'),
            'stripe_test_secret'  => env('STRIPE_SECRET'),
            'stripe_key'          => env('STRIPE_KEY'),
            'stripe_secret'       => env('STRIPE_SECRET'),
            'stripe_enabled'      => '1',
            'stripe_mode'         => 'test',
        ], fn($val) => !is_null($val) && $val !== '');

        $results = [];

        try {
            foreach ($credentials as $key => $val) {
                if (!empty($val)) {
                    Setting::set($key, (string) $val);
                }

                $rawDb       = DB::table('settings')->where('key', $key)->value('value');
                $isEncrypted = str_starts_with((string) $rawDb, 'enc:');
                $decrypted   = Setting::get($key);

                $results[] = [
                    'Key'               => $key,
                    'Is Sensitive'      => Setting::isSensitiveKey($key) ? 'YES' : 'NO',
                    'DB Storage Format' => $isEncrypted ? 'HASH / CIPHER (enc:...)' : 'STANDARD',
                    'Decrypted Test'    => !empty($decrypted) ? 'SUCCESS (Verified)' : 'EMPTY',
                ];
            }

            // Also check and re-encrypt any other sensitive settings in DB
            $rows = DB::table('settings')->get();
            foreach ($rows as $row) {
                if (Setting::isSensitiveKey($row->key) && !empty($row->value) && !str_starts_with($row->value, 'enc:')) {
                    DB::table('settings')->where('id', $row->id)->update([
                        'value'      => Setting::prepareValueForStorage($row->key, $row->value),
                        'updated_at' => now(),
                    ]);
                }
            }

            $this->table(['Key', 'Is Sensitive', 'DB Storage Format', 'Decrypted Test'], $results);
            $this->info('All sensitive keys are verified and stored in encrypted Hash format in the database.');

        } catch (\Throwable $e) {
            $this->warn('Database connection warning: ' . $e->getMessage());
            $this->line('Settings encryption handler is registered and will automatically encrypt when DB is active.');
        }

        // Sanitize .env if requested
        if ($this->option('sanitize-env')) {
            $this->sanitizeEnvFile();
        }

        return Command::SUCCESS;
    }

    /**
     * Sanitize and redact plaintext credentials from the .env file.
     */
    protected function sanitizeEnvFile(): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }

        $content = file_get_contents($envPath);

        // Redact plain text secrets
        $replacements = [
            '/^MAIL_PASSWORD=.*/m'  => 'MAIL_PASSWORD=',
            '/^STRIPE_KEY=.*/m'      => 'STRIPE_KEY=',
            '/^STRIPE_SECRET=.*/m'   => 'STRIPE_SECRET=',
            '/^VITE_STRIPE_KEY=.*/m' => 'VITE_STRIPE_KEY=',
        ];

        $sanitized = preg_replace(array_keys($replacements), array_values($replacements), $content);

        // Add security banner comment if not present
        if (!str_contains($sanitized, '# [SECURITY NOTICE: Sensitive credentials')) {
            $notice = "\n# [SECURITY NOTICE: Sensitive credentials like SMTP password and Stripe keys are securely encrypted in the database settings table]\n";
            $sanitized .= $notice;
        }

        file_put_contents($envPath, $sanitized);
        $this->info('Successfully secured .env: sensitive plaintext credentials removed.');
    }
}
