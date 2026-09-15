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
        //
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
        });
    }
}
