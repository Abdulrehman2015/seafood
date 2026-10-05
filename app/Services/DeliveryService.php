<?php

namespace App\Services;

use App\Models\DeliveryZone;
use App\Models\Setting;

class DeliveryService
{
    /**
     * Standard B2C threshold for local delivery fee exemption (MYR).
     */
    public const DEFAULT_B2C_THRESHOLD = 150.00;

    /**
     * Standard B2B Wholesale threshold for local delivery fee exemption (MYR).
     */
    public const DEFAULT_B2B_THRESHOLD = 350.00;

    /**
     * Get the active delivery threshold amount for given customer group.
     */
    public function getThreshold(string $group = 'retail'): float
    {
        if ($group === 'wholesale') {
            return (float) Setting::get('delivery_b2b_free_threshold', self::DEFAULT_B2B_THRESHOLD);
        }

        return (float) Setting::get('delivery_b2c_free_threshold', self::DEFAULT_B2C_THRESHOLD);
    }

    /**
     * Resolve the applicable DeliveryZone for given location and customer group.
     */
    public function resolveZone(?string $postcode, ?string $city, ?string $state, string $group = 'retail'): ?DeliveryZone
    {
        $zones = DeliveryZone::active()->forCustomerGroup($group)->get();

        foreach ($zones as $zone) {
            if ($zone->matchesLocation($postcode, $city, $state)) {
                return $zone;
            }
        }

        return null;
    }

    /**
     * Calculate delivery / transportation fee for an order.
     *
     * @param float $subtotal
     * @param string $fulfillmentType 'delivery' or 'self_collection'
     * @param string|null $state
     * @param string|null $city
     * @param string|null $postcode
     * @param string $group 'retail', 'walkin', 'wholesale', 'trading'
     * @return array
     */
    public function calculateFee(
        float $subtotal,
        string $fulfillmentType = 'delivery',
        ?string $state = null,
        ?string $city = null,
        ?string $postcode = null,
        string $group = 'retail'
    ): array {
        // ─── Rule 1: Self-collection and Walk-in are NEVER subject to threshold or delivery charge ───
        if ($fulfillmentType === 'self_collection' || $group === 'walkin') {
            return [
                'fulfillment_type'             => 'self_collection',
                'is_self_collection'           => true,
                'is_matched'                   => true,
                'requires_manual_arrangement'  => false,
                'is_eligible_free_delivery'    => true,
                'threshold'                    => 0.00,
                'shortfall_for_free_delivery'  => 0.00,
                'base_delivery_fee'            => 0.00,
                'below_threshold_fee'          => 0.00,
                'fee'                          => 0.00,
                'zone_id'                      => null,
                'zone_code'                    => 'SELF-COLLECTION',
                'zone_name'                    => 'Store Self-Collection',
                'zone_description'             => 'SILC Cold-Chain Facility Counter 2 (Free)',
                'message'                      => 'Walk-in / Counter Collection: Collect your confirmed order directly from MST. No delivery fee applies.',
            ];
        }

        // ─── Rule 2: Trading / Import & Distribution uses custom quotation logistics ───
        if ($group === 'trading') {
            $zone = $this->resolveZone($postcode, $city, $state, 'trading');

            if ($zone && !$zone->manual_quotation_required) {
                $baseFee = (float) $zone->delivery_fee;
                return [
                    'fulfillment_type'             => 'delivery',
                    'is_self_collection'           => false,
                    'is_matched'                   => true,
                    'requires_manual_arrangement'  => false,
                    'is_eligible_free_delivery'    => ($baseFee <= 0),
                    'threshold'                    => 0.00,
                    'shortfall_for_free_delivery'  => 0.00,
                    'base_delivery_fee'            => $baseFee,
                    'below_threshold_fee'          => 0.00,
                    'fee'                          => $baseFee,
                    'zone_id'                      => $zone->id,
                    'zone_code'                    => $zone->code,
                    'zone_name'                    => $zone->name,
                    'zone_description'             => $zone->description,
                    'message'                      => 'Trading logistics delivery applied (' . $zone->name . ').',
                ];
            }

            return [
                'fulfillment_type'             => 'delivery',
                'is_self_collection'           => false,
                'is_matched'                   => false,
                'requires_manual_arrangement'  => true,
                'is_eligible_free_delivery'    => false,
                'threshold'                    => 0.00,
                'shortfall_for_free_delivery'  => 0.00,
                'base_delivery_fee'            => 0.00,
                'below_threshold_fee'          => 0.00,
                'fee'                          => 0.00,
                'zone_id'                      => $zone?->id,
                'zone_code'                    => $zone?->code ?? 'OUTSIDE-ZONE',
                'zone_name'                    => $zone?->name ?? 'Commercial Logistics Arrangement Required',
                'zone_description'             => $zone?->description ?? 'Custom bulk pallet / container logistics',
                'message'                      => 'Trading logistics arrangement required. Please contact MST for schedule confirmation and quotation.',
            ];
        }

        // ─── Rule 3: B2C Retail (RM150 threshold) and B2B Wholesale (RM350 threshold) ───
        $threshold = $this->getThreshold($group);
        $zone      = $this->resolveZone($postcode, $city, $state, $group);

        // Case 3A: Outstation, Zone B/C, or manual cold-chain quotation required
        if (!$zone || $zone->manual_quotation_required) {
            $zoneName = $zone?->name ?? 'Outstation / Extended Area';
            return [
                'fulfillment_type'             => 'delivery',
                'is_self_collection'           => false,
                'is_matched'                   => (bool) $zone,
                'requires_manual_arrangement'  => true,
                'is_eligible_free_delivery'    => false,
                'threshold'                    => $threshold,
                'shortfall_for_free_delivery'  => 0.00,
                'base_delivery_fee'            => 0.00,
                'below_threshold_fee'          => 0.00,
                'fee'                          => 0.00,
                'zone_id'                      => $zone?->id,
                'zone_code'                    => $zone?->code ?? 'OUTSTATION',
                'zone_name'                    => $zoneName,
                'zone_description'             => $zone?->description ?? 'Outstation Cold-Chain Transportation',
                'message'                      => '🚚 Outstation Cold-Chain Delivery: Packaging and transportation fees will be calculated based on the required Styrofoam box size/quantity and confirmed with you via WhatsApp prior to dispatch.',
            ];
        }

        // Case 3B: Standard Zone Matched (Zone A - Local JB / Iskandar Puteri / Nusajaya / Skudai)
        $baseFee           = (float) $zone->delivery_fee;
        $belowThresholdFee = (float) $zone->below_threshold_fee;
        $isAboveThreshold  = ($subtotal >= $threshold);
        $shortfall         = max(0.00, round($threshold - $subtotal, 2));

        if ($isAboveThreshold) {
            $appliedBelowFee  = 0.00;
            $totalDeliveryFee = $baseFee;
            $message          = "Standard Delivery applied ({$zone->name}). Order qualifies for Free Standard Delivery (≥ RM " . number_format($threshold, 2) . ").";
        } else {
            $appliedBelowFee  = $belowThresholdFee;
            $totalDeliveryFee = round($baseFee + $appliedBelowFee, 2);
            $tierLabel        = ($group === 'wholesale') ? 'B2B Wholesale' : 'B2C Retail';
            $message          = "Standard local delivery for {$zone->name}. Orders below RM " . number_format($threshold, 2) . " are subject to a local delivery fee of RM " . number_format($appliedBelowFee, 2) . ".";
        }

        return [
            'fulfillment_type'             => 'delivery',
            'is_self_collection'           => false,
            'is_matched'                   => true,
            'requires_manual_arrangement'  => false,
            'is_eligible_free_delivery'    => $isAboveThreshold,
            'threshold'                    => $threshold,
            'shortfall_for_free_delivery'  => $shortfall,
            'base_delivery_fee'            => $baseFee,
            'below_threshold_fee'          => $appliedBelowFee,
            'fee'                          => $totalDeliveryFee,
            'zone_id'                      => $zone->id,
            'zone_code'                    => $zone->code,
            'zone_name'                    => $zone->name,
            'zone_description'             => $zone->description,
            'message'                      => $message,
        ];
    }
}
