<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (file_exists(app_path('helpers.php'))) {
            require_once app_path('helpers.php');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Dynamically configure SMTP mailer and Stripe settings from Database
        \App\Models\Setting::configureMailer();
        \App\Models\Setting::configureStripe();

        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.custom');

        // Always ensure a fallback default for localized routes ({locale})
        \Illuminate\Support\Facades\URL::defaults(['locale' => config('app.locale', 'en')]);

        // Enforce HTTPS URLs when request is secure, or behind reverse proxy, or running on live/production domain
        if (
            request()->isSecure()
            || request()->header('X-Forwarded-Proto') === 'https'
            || request()->header('HTTP_X_FORWARDED_PROTO') === 'https'
            || request()->header('X-Forwarded-Ssl') === 'on'
            || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (request()->getHost() && !in_array(request()->getHost(), ['127.0.0.1', 'localhost']))
            || app()->environment('production')
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->numbers();
        });

        if (config('database.default') === 'sqlite') {
            try {
                \Illuminate\Support\Facades\DB::statement('PRAGMA journal_mode = WAL;');
                \Illuminate\Support\Facades\DB::statement('PRAGMA synchronous = NORMAL;');
                \Illuminate\Support\Facades\DB::statement('PRAGMA temp_store = MEMORY;');
                \Illuminate\Support\Facades\DB::statement('PRAGMA mmap_size = 1073741824;');
                \Illuminate\Support\Facades\DB::statement('PRAGMA cache_size = -262144;');
            } catch (\Throwable $e) {}
        }

        // Self-healing database schema on live environments (e.g. shared hosting without CLI)
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'delivery_date')) {
                    \Illuminate\Support\Facades\Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->string('delivery_date')->nullable()->after('fulfillment_type');
                    });
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'collection_date')) {
                    \Illuminate\Support\Facades\Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->string('collection_date')->nullable()->after('shipping_address');
                    });
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'collection_time')) {
                    \Illuminate\Support\Facades\Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->string('collection_time')->nullable()->after('collection_date');
                    });
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'confirmed_date')) {
                    \Illuminate\Support\Facades\Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->string('confirmed_date')->nullable()->after('delivery_date');
                    });
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'confirmed_time')) {
                    \Illuminate\Support\Facades\Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->string('confirmed_time')->nullable()->after('confirmed_date');
                    });
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'notified_at')) {
                    \Illuminate\Support\Facades\Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->timestamp('notified_at')->nullable()->after('confirmed_time');
                    });
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'notification_notes')) {
                    \Illuminate\Support\Facades\Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->text('notification_notes')->nullable()->after('notified_at');
                    });
                }
            }
        } catch (\Throwable $e) {}

        view()->composer('*', function ($view) {
            static $sharedData = null;
            if ($sharedData === null) {
                $settings = [];
                try {
                    $settings = \App\Models\Setting::allKeyed();
                } catch (\Throwable $e) {}

                $currencyData = [];
                try {
                    $currencyService = app(\App\Services\CurrencyService::class);
                    $currencyData = [
                        'currencyService' => $currencyService,
                        'currentCurrency' => $currencyService->getCurrentCurrency(),
                        'currencySymbol'  => $currencyService->getSymbol(),
                        'currencyLabel'   => $currencyService->getLabel(),
                        'currencyList'    => $currencyService->getSupportedCurrencies(),
                    ];
                } catch (\Throwable $e) {
                    $currencyData = [
                        'currentCurrency' => 'MYR',
                        'currencySymbol'  => 'RM',
                        'currencyLabel'   => 'RM',
                        'currencyList'    => [],
                    ];
                }

                $translationData = [];
                try {
                    $translationService = app(\App\Services\TranslationService::class);
                    $currentLocale      = $translationService->currentLocale();
                    $supportedLocales   = $translationService->getSupportedLocales();
                    $translationData = [
                        'translationService' => $translationService,
                        'currentLocale'      => $currentLocale,
                        'activeLocale'       => $currentLocale,
                        'supportedLocales'   => $supportedLocales,
                        'activeLocaleData'   => $supportedLocales[$currentLocale] ?? $supportedLocales['en'],
                    ];
                } catch (\Throwable $e) {
                    $translationData = [
                        'currentLocale'    => 'en',
                        'activeLocale'     => 'en',
                        'supportedLocales' => [],
                        'activeLocaleData' => ['code' => 'en', 'label' => 'EN', 'flag' => '🇬🇧', 'native' => 'English'],
                    ];
                }

                $sharedData = array_merge(['settings' => $settings], $currencyData, $translationData);
            }

            $view->with($sharedData);
        });

        // Register custom Blade directive @t
        \Illuminate\Support\Facades\Blade::directive('t', function ($expression) {
            return "<?php echo __t({$expression}); ?>";
        });
    }
}
