<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'postcodes',
        'areas',
        'states',
        'delivery_fee',
        'below_threshold_fee',
        'is_b2c_enabled',
        'is_b2b_enabled',
        'is_trading_enabled',
        'is_active',
        'manual_quotation_required',
        'sort_order',
        'notes',
    ];

    protected $casts = [
        'delivery_fee'              => 'decimal:2',
        'below_threshold_fee'       => 'decimal:2',
        'is_b2c_enabled'            => 'boolean',
        'is_b2b_enabled'            => 'boolean',
        'is_trading_enabled'        => 'boolean',
        'is_active'                 => 'boolean',
        'manual_quotation_required' => 'boolean',
        'sort_order'                => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeForCustomerGroup($query, string $group)
    {
        return match ($group) {
            'wholesale' => $query->where('is_b2b_enabled', true),
            'trading'   => $query->where('is_trading_enabled', true),
            default     => $query->where('is_b2c_enabled', true),
        };
    }

    /**
     * Determine if this zone matches a given postcode, city, or state.
     */
    public function matchesLocation(?string $postcode, ?string $city, ?string $state): bool
    {
        $cleanPostcode = trim((string) $postcode);
        $cleanCity     = strtolower(trim((string) $city));
        $cleanState    = strtolower(trim((string) $state));

        // Explicit Exclusion: Skudai (81300) must NEVER match Zone A
        if ($this->code === 'ZONE-A') {
            if ($cleanPostcode === '81300' || str_contains($cleanCity, 'skudai')) {
                return false;
            }
        }

        $hasPostcodes = !empty($this->postcodes);
        $hasAreas     = !empty($this->areas);

        $parseList = function ($val) {
            if (is_array($val)) {
                return $val;
            }
            return preg_split('/[\r\n,]+/', (string) $val) ?: [];
        };

        // 1. Postcode is the most authoritative location identifier
        if (!empty($cleanPostcode) && $hasPostcodes) {
            $postcodeEntries = $parseList($this->postcodes);
            foreach ($postcodeEntries as $entry) {
                $entry = trim((string) $entry);
                if (empty($entry)) continue;

                // Exact match (e.g. "79100" or "81300")
                if ($cleanPostcode === $entry) {
                    return true;
                }

                // Prefix / wildcard match (e.g. "79" for 79xxx or "80*")
                $prefix = rtrim(rtrim($entry, '*'), 'x');
                if (strlen($prefix) >= 2 && str_starts_with($cleanPostcode, $prefix)) {
                    return true;
                }
            }

            // CRITICAL: If this zone defines specific postcodes and the customer provided a postcode,
            // failing the postcode match means this zone is NOT a match.
            // Do NOT fall back to city or state matching, because broad municipal city names (e.g. "Johor Bahru")
            // span both Zone A and Outstation/Zone B postcodes.
            return false;
        }

        // 2. Check Areas / Cities if postcode was not provided or zone has no postcode restrictions
        if (!empty($cleanCity) && $hasAreas) {
            $areaEntries = $parseList($this->areas);
            foreach ($areaEntries as $area) {
                $area = strtolower(trim((string) $area));
                if (empty($area)) continue;

                if ($cleanCity === $area || str_contains($cleanCity, $area) || str_contains($area, $cleanCity)) {
                    return true;
                }
            }

            if ($hasAreas) {
                return false;
            }
        }

        // 3. Fallback to State matching ONLY IF zone has no postcodes/areas configured (e.g. statewide outstation)
        if (!empty($cleanState) && !empty($this->states)) {
            if (!empty($cleanPostcode) && $hasPostcodes) {
                return false;
            }
            if (!empty($cleanCity) && $hasAreas) {
                return false;
            }

            $stateEntries = $parseList($this->states);
            foreach ($stateEntries as $st) {
                $st = strtolower(trim((string) $st));
                if (empty($st)) continue;

                if ($cleanState === $st || str_contains($cleanState, $st) || str_contains($st, $cleanState)) {
                    return true;
                }
            }
        }

        return false;
    }
}
