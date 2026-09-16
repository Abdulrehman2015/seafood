<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CurrencyService
{
    public const DEFAULT_CURRENCY = 'MYR';

    public const CURRENCIES = [
        'MYR' => [
            'code'   => 'MYR',
            'symbol' => 'RM',
            'label'  => 'RM',
            'name'   => 'Malaysian Ringgit',
            'flag'   => '🇲🇾',
        ],
        'SGD' => [
            'code'   => 'SGD',
            'symbol' => 'S$',
            'label'  => 'SGD',
            'name'   => 'Singapore Dollar',
            'flag'   => '🇸🇬',
        ],
        'USD' => [
            'code'   => 'USD',
            'symbol' => '$',
            'label'  => 'USD',
            'name'   => 'US Dollar',
            'flag'   => '🇺🇸',
        ],
    ];

    /**
     * Get all supported currencies metadata.
     */
    public function getSupportedCurrencies(): array
    {
        return self::CURRENCIES;
    }

    /**
     * Get the active currency code from session.
     */
    public function getCurrentCurrency(): string
    {
        $code = strtoupper((string) Session::get('currency', self::DEFAULT_CURRENCY));
        return array_key_exists($code, self::CURRENCIES) ? $code : self::DEFAULT_CURRENCY;
    }

    /**
     * Set the active user currency in session.
     */
    public function setCurrency(string $code): bool
    {
        $code = strtoupper(trim($code));
        if (array_key_exists($code, self::CURRENCIES)) {
            Session::put('currency', $code);
            return true;
        }
        return false;
    }

    /**
     * Get currency symbol for a currency code (or active currency).
     */
    public function getSymbol(?string $code = null): string
    {
        $code = strtoupper($code ?: $this->getCurrentCurrency());
        return self::CURRENCIES[$code]['symbol'] ?? 'RM';
    }

    /**
     * Get currency display label (e.g. 'RM', 'SGD', 'USD').
     */
    public function getLabel(?string $code = null): string
    {
        $code = strtoupper($code ?: $this->getCurrentCurrency());
        return self::CURRENCIES[$code]['label'] ?? 'RM';
    }

    /**
     * Check if Auto Currency Conversion mode is enabled in Settings.
     */
    public function isAutoConvert(): bool
    {
        return Setting::get('currency_auto_convert', '1') === '1';
    }

    /**
     * Get live/cached exchange rates against 1 MYR.
     */
    public function getRates(): array
    {
        $manualSgd = (float) Setting::get('currency_manual_rate_sgd', 0.3117);
        $manualUsd = (float) Setting::get('currency_manual_rate_usd', 0.2453);

        $fallback = [
            'MYR' => 1.0,
            'SGD' => $manualSgd > 0 ? $manualSgd : 0.3117,
            'USD' => $manualUsd > 0 ? $manualUsd : 0.2453,
        ];

        // If Auto Conversion is OFF, use the manual exchange rates from Settings
        if (!$this->isAutoConvert()) {
            return $fallback;
        }

        // Cache live rates for 6 hours (21600 seconds)
        return Cache::remember('currency_live_rates_myr', 21600, function () use ($fallback) {
            try {
                $response = Http::withoutVerifying()->timeout(6)->get('https://open.er-api.com/v6/latest/MYR');
                if ($response->successful()) {
                    $json = $response->json();
                    if (isset($json['result']) && $json['result'] === 'success' && isset($json['rates'])) {
                        $sgdRate = (float) ($json['rates']['SGD'] ?? $fallback['SGD']);
                        $usdRate = (float) ($json['rates']['USD'] ?? $fallback['USD']);

                        // Cache timestamp for admin status indicator
                        Cache::put('currency_rates_updated_at', now()->toDateTimeString(), 86400);

                        return [
                            'MYR' => 1.0,
                            'SGD' => $sgdRate,
                            'USD' => $usdRate,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Live currency API fetch error: ' . $e->getMessage());
            }

            return $fallback;
        });
    }

    /**
     * Force refresh live exchange rates from the API.
     */
    public function syncRates(): array
    {
        Cache::forget('currency_live_rates_myr');

        $manualSgd = (float) Setting::get('currency_manual_rate_sgd', 0.3117);
        $manualUsd = (float) Setting::get('currency_manual_rate_usd', 0.2453);

        $fallback = [
            'MYR' => 1.0,
            'SGD' => $manualSgd > 0 ? $manualSgd : 0.3117,
            'USD' => $manualUsd > 0 ? $manualUsd : 0.2453,
        ];

        try {
            $response = Http::withoutVerifying()->timeout(8)->get('https://open.er-api.com/v6/latest/MYR');
            if ($response->successful()) {
                $json = $response->json();
                if (isset($json['result']) && $json['result'] === 'success' && isset($json['rates'])) {
                    $sgdRate = (float) ($json['rates']['SGD'] ?? $fallback['SGD']);
                    $usdRate = (float) ($json['rates']['USD'] ?? $fallback['USD']);

                    $rates = [
                        'MYR' => 1.0,
                        'SGD' => $sgdRate,
                        'USD' => $usdRate,
                    ];

                    Cache::put('currency_live_rates_myr', $rates, 21600);
                    Cache::put('currency_rates_updated_at', now()->toDateTimeString(), 86400);

                    // Also update fallback rates in settings so input fields update
                    Setting::set('currency_manual_rate_sgd', (string) round($sgdRate, 4));
                    Setting::set('currency_manual_rate_usd', (string) round($usdRate, 4));

                    return $rates;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Live currency API manual sync error: ' . $e->getMessage());
        }

        return $fallback;
    }

    /**
     * Convert an amount in MYR to the target currency.
     */
    public function convert(float $amountInMyr, ?string $targetCurrency = null): float
    {
        $targetCurrency = strtoupper($targetCurrency ?: $this->getCurrentCurrency());
        if ($targetCurrency === 'MYR') {
            return (float) $amountInMyr;
        }

        $rates = $this->getRates();
        $rate  = $rates[$targetCurrency] ?? 1.0;

        return (float) round($amountInMyr * $rate, 2);
    }

    /**
     * Format an amount into standard currency notation.
     */
    public function format(?float $amount, ?string $currency = null): string
    {
        if ($amount === null) {
            return 'Price on Request';
        }

        $currency = strtoupper($currency ?: $this->getCurrentCurrency());
        $symbol   = $this->getSymbol($currency);

        return $symbol . ' ' . number_format($amount, 2);
    }

    /**
     * Resolve product pricing taking into account:
     * - Customer Group (retail, walkin, wholesale, trading)
     * - Active Selected Currency (MYR, SGD, USD)
     * - Auto Conversion Mode (ON: API convert / OFF: per-product manual price)
     *
     * @return array{amount: ?float, currency: string, symbol: string, formatted: string, is_manual: bool, base_rm: ?float}
     */
    public function getProductPrice(Product $product, string $group = 'retail', ?string $currency = null): array
    {
        $currency = strtoupper($currency ?: $this->getCurrentCurrency());
        $symbol   = $this->getSymbol($currency);
        $baseRm   = $product->getPriceForGroup($group);

        // RFQ Only products for trading
        if ($baseRm === null) {
            return [
                'amount'    => null,
                'currency'  => $currency,
                'symbol'    => $symbol,
                'formatted' => 'Price on Request',
                'is_manual' => false,
                'base_rm'   => null,
            ];
        }

        // Base currency (MYR / RM) is always standard
        if ($currency === 'MYR') {
            return [
                'amount'    => (float) $baseRm,
                'currency'  => 'MYR',
                'symbol'    => 'RM',
                'formatted' => 'RM ' . number_format($baseRm, 2),
                'is_manual' => false,
                'base_rm'   => (float) $baseRm,
            ];
        }

        $isAuto = $this->isAutoConvert();

        // 1. If Auto Conversion is OFF, check for manual per-product pricing
        if (!$isAuto) {
            if ($currency === 'SGD') {
                if ($group === 'wholesale' && !empty($product->wholesale_price_sgd)) {
                    $manualPrice = (float) $product->wholesale_price_sgd;
                } elseif ($group === 'trading' && !empty($product->trading_price_sgd)) {
                    $manualPrice = (float) $product->trading_price_sgd;
                } elseif (!empty($product->price_sgd)) {
                    $manualPrice = (float) $product->price_sgd;
                } else {
                    $manualPrice = null;
                }

                if ($manualPrice !== null && $manualPrice > 0) {
                    return [
                        'amount'    => $manualPrice,
                        'currency'  => 'SGD',
                        'symbol'    => 'S$',
                        'formatted' => 'S$ ' . number_format($manualPrice, 2),
                        'is_manual' => true,
                        'base_rm'   => (float) $baseRm,
                    ];
                }
            } elseif ($currency === 'USD') {
                if ($group === 'wholesale' && !empty($product->wholesale_price_usd)) {
                    $manualPrice = (float) $product->wholesale_price_usd;
                } elseif ($group === 'trading' && !empty($product->trading_price_usd)) {
                    $manualPrice = (float) $product->trading_price_usd;
                } elseif (!empty($product->price_usd)) {
                    $manualPrice = (float) $product->price_usd;
                } else {
                    $manualPrice = null;
                }

                if ($manualPrice !== null && $manualPrice > 0) {
                    return [
                        'amount'    => $manualPrice,
                        'currency'  => 'USD',
                        'symbol'    => '$',
                        'formatted' => '$ ' . number_format($manualPrice, 2),
                        'is_manual' => true,
                        'base_rm'   => (float) $baseRm,
                    ];
                }
            }
        }

        // 2. Auto Conversion mode OR fallback if manual price was not filled:
        $converted = $this->convert((float) $baseRm, $currency);

        return [
            'amount'    => $converted,
            'currency'  => $currency,
            'symbol'    => $symbol,
            'formatted' => $symbol . ' ' . number_format($converted, 2),
            'is_manual' => false,
            'base_rm'   => (float) $baseRm,
        ];
    }
}
