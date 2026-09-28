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

        // 1. Check Postcodes if provided
        if (!empty($cleanPostcode) && !empty($this->postcodes)) {
            $postcodeEntries = preg_split('/[\r\n,]+/', (string) $this->postcodes);
            foreach ($postcodeEntries as $entry) {
                $entry = trim($entry);
                if (empty($entry)) continue;

                // Exact match (e.g. "79100")
                if ($cleanPostcode === $entry) {
                    return true;
                }

                // Prefix / wildcard match (e.g. "79" for 79xxx or "80*")
                $prefix = rtrim(rtrim($entry, '*'), 'x');
                if (strlen($prefix) >= 2 && str_starts_with($cleanPostcode, $prefix)) {
                    return true;
                }
            }
        }

        // 2. Check Areas / Cities if provided
        if (!empty($cleanCity) && !empty($this->areas)) {
            $areaEntries = preg_split('/[\r\n,]+/', strtolower((string) $this->areas));
            foreach ($areaEntries as $area) {
                $area = trim($area);
                if (empty($area)) continue;

                if ($cleanCity === $area || str_contains($cleanCity, $area) || str_contains($area, $cleanCity)) {
                    return true;
                }
            }
        }

        // 3. Check States if provided
        if (!empty($cleanState) && !empty($this->states)) {
            $stateEntries = preg_split('/[\r\n,]+/', strtolower((string) $this->states));
            foreach ($stateEntries as $st) {
                $st = trim($st);
                if (empty($st)) continue;

                if ($cleanState === $st || str_contains($cleanState, $st) || str_contains($st, $cleanState)) {
                    return true;
                }
            }
        }

        return false;
    }
}
