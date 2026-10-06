<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Align Zone A coverage strictly to Johor Bahru / Iskandar Puteri / Nusajaya per client business rules.
     * Skudai (81300) is re-routed to Zone B / Extended Area (requiring manual cold-chain WhatsApp quotation).
     */
    public function up(): void
    {
        // 1. Update Zone A - Strictly Local JB / Iskandar Puteri / Nusajaya
        DB::table('delivery_zones')->where('code', 'ZONE-A')->update([
            'name'                      => 'Zone A - Local JB / Iskandar Puteri / Nusajaya',
            'description'               => 'Johor Bahru, Iskandar Puteri, and Nusajaya local direct delivery coverage',
            'postcodes'                 => "79000\n79100\n79200\n79250\n79500\n80000\n80050\n80100\n80150\n80200\n80250\n80300\n80350\n80400\n80500\n81100\n81200",
            'areas'                     => "Johor Bahru, JB, Iskandar Puteri, Nusajaya, Medini, Puteri Harbour, Gelang Patah, Tampoi, Perling, Bukit Indah",
            'states'                    => "Johor",
            'delivery_fee'              => 0.00,
            'below_threshold_fee'       => 10.00,
            'manual_quotation_required' => false,
            'is_b2c_enabled'            => true,
            'is_b2b_enabled'            => true,
            'is_active'                 => true,
            'notes'                     => 'Local delivery: Free for orders >= RM150; RM10 fee for orders below RM150. Strictly covers JB, Iskandar Puteri & Nusajaya.',
            'updated_at'                => now(),
        ]);

        // 2. Update Zone B - Extended Johor (Including Skudai, Kulai, Senai, Outstation)
        DB::table('delivery_zones')->where('code', 'ZONE-B')->update([
            'name'                      => 'Zone B - Extended Johor & Melaka (Outstation Cold-Chain)',
            'description'               => 'Extended Johor districts (Skudai, Kulai, Senai, Batu Pahat, Muar, Kluang, Pontian) & Melaka',
            'postcodes'                 => "81300\n81400\n82000\n83000\n84000\n85000\n86000\n75000\n76000\n77000",
            'areas'                     => "Skudai, Kulai, Senai, Batu Pahat, Muar, Kluang, Kota Tinggi, Pontian, Segamat, Mersing, Melaka, Malacca, Ayer Keroh, Alor Gajah, Jasin",
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
        DB::table('delivery_zones')->where('code', 'ZONE-A')->update([
            'name'                      => 'Zone A - Local JB / Iskandar Puteri / Nusajaya / Skudai',
            'description'               => 'Selected Johor Bahru, Iskandar Puteri, Nusajaya, Skudai and surrounding local areas',
            'postcodes'                 => "79000\n79100\n79200\n79250\n79500\n80000\n80050\n80100\n80150\n80200\n80250\n80300\n80350\n80400\n80500\n81100\n81200\n81300\n81400",
            'areas'                     => "Johor Bahru, JB, Iskandar Puteri, Nusajaya, Skudai, Gelang Patah, Kulai, Senai, Tampoi, Perling, Bukit Indah, Ulu Tiram, Masai, Pasir Gudang",
        ]);
    }
};
