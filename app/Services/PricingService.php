<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class PricingService
{
    public function __construct(
        protected CurrencyService $currency
    ) {}

    /**
     * Resolve the correct price for a product based on the current customer group and currency.
     */
    public function getPrice(Product $product, string $group, ?string $currency = null): ?float
    {
        $resolved = $this->currency->getProductPrice($product, $group, $currency);
        return $resolved['amount'];
    }

    /**
     * Get full multi-currency pricing payload for a product.
     *
     * @return array{amount: ?float, currency: string, symbol: string, formatted: string, is_manual: bool, base_rm: ?float}
     */
    public function getCurrencyPrice(Product $product, string $group, ?string $currency = null): array
    {
        return $this->currency->getProductPrice($product, $group, $currency);
    }

    /**
     * Get the current customer group for the request.
     * Priority: authenticated user group → walk-in session → retail (default)
     */
    public function resolveGroup(): string
    {
        if (session('walkin_session')) {
            return 'walkin';
        }

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) return 'retail'; // Admin sees retail prices in shop
            if ($user->needsApproval()) return 'retail'; // Unapproved accounts only see retail pricing
            return $user->customer_group ?: 'retail';
        }

        return 'retail';
    }

    /**
     * Format price for display in active (or specified) currency.
     */
    public function formatPrice(?float $price, ?string $currency = null): string
    {
        if ($price === null) {
            return 'Price on Request';
        }

        return $this->currency->format($price, $currency);
    }

    /**
     * Convert an amount from MYR base to the active currency.
     */
    public function convertFromMyr(float $amountInMyr, ?string $currency = null): float
    {
        return $this->currency->convert($amountInMyr, $currency);
    }

    /**
     * Get active currency code.
     */
    public function getCurrentCurrency(): string
    {
        return $this->currency->getCurrentCurrency();
    }

    /**
     * Get active currency symbol.
     */
    public function getCurrencySymbol(?string $code = null): string
    {
        return $this->currency->getSymbol($code);
    }

    /**
     * Get MOQ for current group.
     */
    public function getMoq(Product $product, string $group): int
    {
        return $product->getMoqForGroup($group);
    }
}
