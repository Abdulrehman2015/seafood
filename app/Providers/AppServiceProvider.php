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

        view()->composer('*', function ($view) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    $view->with('settings', \App\Models\Setting::allKeyed());
                } else {
                    $view->with('settings', []);
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('categories')) {
                    $footerCategories = \Illuminate\Support\Facades\Cache::remember('footer.categories', 3600, function () {
                        $featured = \App\Models\Category::where('is_active', true)
                            ->where('is_featured', true)
                            ->orderBy('sort_order')
                            ->orderBy('name')
                            ->take(8)
                            ->get();

                        if ($featured->isNotEmpty()) {
                            return $featured;
                        }

                        return \App\Models\Category::where('is_active', true)
                            ->orderBy('sort_order')
                            ->orderBy('name')
                            ->take(6)
                            ->get();
                    });
                    $view->with('footerCategories', $footerCategories);
                } else {
                    $view->with('footerCategories', collect());
                }
            } catch (\Throwable $e) {
                $view->with('settings', []);
                $view->with('footerCategories', collect());
            }

            // Share Multi-Currency Data with all views
            try {
                $currencyService = app(\App\Services\CurrencyService::class);
                $view->with([
                    'currencyService'   => $currencyService,
                    'currentCurrency'   => $currencyService->getCurrentCurrency(),
                    'currencySymbol'    => $currencyService->getSymbol(),
                    'currencyLabel'     => $currencyService->getLabel(),
                    'currencyList'      => $currencyService->getSupportedCurrencies(),
                ]);
            } catch (\Throwable $e) {
                $view->with([
                    'currentCurrency' => 'MYR',
                    'currencySymbol'  => 'RM',
                    'currencyLabel'   => 'RM',
                    'currencyList'    => [],
                ]);
            }

            // Share Multilingual Data with all views
            try {
                $translationService = app(\App\Services\TranslationService::class);
                $currentLocale      = $translationService->currentLocale();
                $supportedLocales   = $translationService->getSupportedLocales();
                $view->with([
                    'translationService' => $translationService,
                    'currentLocale'      => $currentLocale,
                    'activeLocale'       => $currentLocale,
                    'supportedLocales'   => $supportedLocales,
                    'activeLocaleData'   => $supportedLocales[$currentLocale] ?? $supportedLocales['en'],
                ]);
            } catch (\Throwable $e) {
                $view->with([
                    'currentLocale'    => 'en',
                    'activeLocale'     => 'en',
                    'supportedLocales' => [],
                    'activeLocaleData' => ['code' => 'en', 'label' => 'EN', 'flag' => '🇬🇧', 'native' => 'English'],
                ]);
            }
        });

        // Register custom Blade directive @t
        \Illuminate\Support\Facades\Blade::directive('t', function ($expression) {
            return "<?php echo __t({$expression}); ?>";
        });
    }
}
