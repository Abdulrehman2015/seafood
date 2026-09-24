<?php

namespace App\Services;

use App\Models\Setting;

class DeliveryService
{
    /**
     * Standard B2C threshold for local delivery arrangement (MYR).
     */
    public const DEFAULT_THRESHOLD = 100.00;

    /**
     * Get the active B2C delivery threshold amount from settings.
     */
    public function getThreshold(): float
    {
        return (float) Setting::get('delivery_b2c_free_threshold', self::DEFAULT_THRESHOLD);
    }

    /**
     * Get configured transportation rates.
     */
    public function getRates(): array
    {
        return [
            'local_johor' => (float) Setting::get('delivery_fee_zone_local', 10.00),
            'outstation'  => (float) Setting::get('delivery_fee_zone_outstation', 20.00),
            'default'     => (float) Setting::get('delivery_fee_default', 15.00),
        ];
    }

    /**
     * Determine delivery zone based on state and city.
     */
    public function resolveZone(?string $state, ?string $city): array
    {
        $stateClean = strtolower(trim((string) $state));
        $cityClean  = strtolower(trim((string) $city));

        $rates = $this->getRates();

        // 1. Local Johor Zone (Local hub vicinity: Iskandar Puteri, Johor Bahru, Skudai, Kulai, Pasir Gudang, etc.)
        if (str_contains($stateClean, 'johor') || in_array($cityClean, ['johor bahru', 'jb', 'skudai', 'iskandar puteri', 'gelang patah', 'kulai', 'pasir gudang', 'senai', 'tampoi', 'perling', 'bukit indah', 'ulu tiram', 'masai', 'nusajaya'])) {
            return [
                'zone_key'    => 'local_johor',
                'zone_name'   => 'Johor Bahru & Local Surroundings',
                'description' => 'Local Cold-Chain Direct Fleet',
                'rate'        => $rates['local_johor'],
            ];
        }

        // 2. West / Peninsular Malaysia Outstation Zone
        $peninsularStates = [
            'kuala lumpur', 'kl', 'selangor', 'putrajaya', 'melaka', 'malacca',
            'negeri sembilan', 'perak', 'penang', 'pulau pinang', 'kedah',
            'pahang', 'terengganu', 'kelantan', 'perlis'
        ];

        foreach ($peninsularStates as $pState) {
            if (str_contains($stateClean, $pState) || str_contains($cityClean, $pState)) {
                return [
                    'zone_key'    => 'outstation',
                    'zone_name'   => 'Peninsular / Outstation West Malaysia',
                    'description' => 'Outstation Sub-Zero Courier Logistics',
                    'rate'        => $rates['outstation'],
                ];
            }
        }

        // 3. Fallback General Zone
        return [
            'zone_key'    => 'default',
            'zone_name'   => 'Standard West Malaysia Logistics',
            'description' => 'Cold-Chain Delivery',
            'rate'        => $rates['default'],
        ];
    }

    /**
     * Calculate delivery / transportation fee for an order.
     *
     * @param float $subtotal
     * @param string $fulfillmentType 'delivery' or 'self_collection'
     * @param string|null $state
     * @param string|null $city
     * @param string $group 'retail', 'walkin', 'wholesale', 'trading'
     * @return array
     */
    public function calculateFee(
        float $subtotal,
        string $fulfillmentType = 'delivery',
        ?string $state = null,
        ?string $city = null,
        string $group = 'retail'
    ): array {
        $threshold = $this->getThreshold();

        // Rule 1: Self-collection and Walk-in are NEVER subject to threshold or delivery charge
        if ($fulfillmentType === 'self_collection' || $group === 'walkin') {
            return [
                'fulfillment_type'            => $fulfillmentType,
                'is_self_collection'          => true,
                'is_eligible_free_delivery'   => true,
                'threshold'                   => $threshold,
                'shortfall_for_free_delivery' => 0.00,
                'fee'                         => 0.00,
                'zone_key'                    => 'self_collection',
                'zone_name'                   => 'Store Self-Collection',
                'zone_description'            => 'SILC Cold-Chain Facility Counter 2 (Free)',
                'message'                     => 'Self-collection is free with no threshold.',
            ];
        }

        $zone = $this->resolveZone($state, $city);

        // Rule 2: RM100 and above -> eligible for standard local delivery arrangement (Fee = 0)
        if ($subtotal >= $threshold) {
            return [
                'fulfillment_type'            => 'delivery',
                'is_self_collection'          => false,
                'is_eligible_free_delivery'   => true,
                'threshold'                   => $threshold,
                'shortfall_for_free_delivery' => 0.00,
                'fee'                         => 0.00,
                'zone_key'                    => $zone['zone_key'],
                'zone_name'                   => $zone['zone_name'],
                'zone_description'            => $zone['description'],
                'message'                     => "Eligible for standard local delivery arrangement (Order total exceeds RM " . number_format($threshold, 2) . ").",
            ];
        }

        // Rule 3: Below RM100 -> transportation fee applies according to delivery zone
        $fee = (float) $zone['rate'];
        $shortfall = round($threshold - $subtotal, 2);

        return [
            'fulfillment_type'            => 'delivery',
            'is_self_collection'          => false,
            'is_eligible_free_delivery'   => false,
            'threshold'                   => $threshold,
            'shortfall_for_free_delivery' => $shortfall,
            'fee'                         => $fee,
            'zone_key'                    => $zone['zone_key'],
            'zone_name'                   => $zone['zone_name'],
            'zone_description'            => $zone['description'],
            'message'                     => "Orders below RM " . number_format($threshold, 2) . " are subject to an area transportation charge of RM " . number_format($fee, 2) . " ({$zone['zone_name']}). Add RM " . number_format($shortfall, 2) . " more to qualify for standard delivery.",
        ];
    }
}
