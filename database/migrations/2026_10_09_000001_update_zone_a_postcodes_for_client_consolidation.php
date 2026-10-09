<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidate Zone A coverage strictly per client confirmation:
     * Gelang Patah, Iskandar Puteri / Nusajaya, Johor Bahru, Kulai (Indahpura only),
     * Masai, Senai, Skudai (including 81300), Setia Eco Gardens, Mount Austin, and ICQ area.
     */
    public function up(): void
    {
        // 1. Zone A - Consolidated Coverage
        $zoneAPostcodes = implode("\n", [
            '79000', '79100', '79200', '79250', '79500', // Iskandar Puteri, Nusajaya, Medini, Puteri Harbour, ICQ / Second Link
            '80000', '80050', '80100', '80150', '80200', '80250', '80300', '80350', '80400', '80500', // JB City Core
            '80550', '80600', '80650', '80700', '80710', '80720', '80730', '80800', '80990', // Extended JB Core
            '81100', // Mount Austin, Austin Heights, Tebrau
            '81200', // Tampoi, Perling
            '81300', // Skudai, Taman Universiti, Mutiara Rini, Tun Aminah
            '81400', // Senai, Senai Airport City
            '81550', // Gelang Patah, Setia Eco Gardens
            '81750', // Masai, Bandar Seri Alam
            '81000', // Kulai (Indahpura only - filtered by area rule)
        ]);

        $zoneAAreas = "Gelang Patah, Iskandar Puteri, Nusajaya, Johor Bahru, JB, Indahpura, Masai, Senai, Skudai, Setia Eco Gardens, Mount Austin, Austin Heights, Medini, Puteri Harbour, Tampoi, Perling, Bukit Indah, CIQ, ICQ";

        DB::table('delivery_zones')->where('code', 'ZONE-A')->update([
            'name'                      => 'Zone A — Local JB, Iskandar Puteri & Central Suburbs',
            'description'               => 'Local direct coverage: Gelang Patah, Iskandar Puteri, JB, Indahpura, Masai, Senai, Skudai (81300), Setia Eco Gardens, Mount Austin & ICQ',
            'postcodes'                 => $zoneAPostcodes,
            'areas'                     => $zoneAAreas,
            'states'                    => null,
            'delivery_fee'              => 0.00,
            'below_threshold_fee'       => 10.00,
            'manual_quotation_required' => false,
            'is_b2c_enabled'            => true,
            'is_b2b_enabled'            => true,
            'is_active'                 => true,
            'notes'                     => 'Standard Zone A: Free for orders >= RM150; RM10 fee below RM150. Skudai (81300) included. Kulai restricted to Indahpura only.',
            'updated_at'                => now(),
        ]);

        // 2. Zone B - Extended Johor (Remove 81300 since Skudai is now in Zone A)
        $zoneBPostcodes = implode("\n", [
            '81000', // Kulai (Non-Indahpura areas)
            '81800', // Ulu Tiram
            '81900', // Kota Tinggi
            '82000', // Pontian
            '83000', // Batu Pahat
            '84000', // Muar
            '85000', // Segamat
            '86000', // Kluang
            '86800', // Mersing
            '75000', '76000', '77000', // Melaka
        ]);

        DB::table('delivery_zones')->where('code', 'ZONE-B')->update([
            'name'                      => 'Zone B — Extended Johor & Outstation Cold-Chain',
            'description'               => 'Extended Johor districts (Kulai non-Indahpura, Ulu Tiram, Batu Pahat, Muar, Kluang, Pontian, Kota Tinggi) & Outstation',
            'postcodes'                 => $zoneBPostcodes,
            'areas'                     => "Kulai (outside Indahpura), Ulu Tiram, Batu Pahat, Muar, Kluang, Pontian, Kota Tinggi, Segamat, Mersing, Melaka",
            'states'                    => "Johor, Melaka",
            'delivery_fee'              => 0.00,
            'below_threshold_fee'       => 0.00,
            'manual_quotation_required' => true,
            'notes'                     => 'Outstation cold-chain: Transport and Styrofoam box fees quoted via WhatsApp before dispatch.',
            'updated_at'                => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversible if needed
    }
};
