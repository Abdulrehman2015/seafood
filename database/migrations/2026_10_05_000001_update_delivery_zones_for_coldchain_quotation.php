<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update B2C Free Delivery Threshold to RM 150.00
        DB::table('settings')->updateOrInsert(
            ['key' => 'delivery_b2c_free_threshold'],
            ['value' => '150.00', 'updated_at' => now(), 'created_at' => now()]
        );

        // Update Zone A - Local JB / Iskandar Puteri / Nusajaya / Skudai
        DB::table('delivery_zones')->where('code', 'ZONE-A')->update([
            'name'                      => 'Zone A - Local JB / Iskandar Puteri / Nusajaya / Skudai',
            'delivery_fee'              => 0.00,
            'below_threshold_fee'       => 10.00,
            'manual_quotation_required' => false,
            'notes'                     => 'Local delivery: Free for orders >= RM150; RM10 fee for orders below RM150.',
            'updated_at'                => now(),
        ]);

        // Update Zone B - Extended Johor & Melaka (Outstation cold-chain quote)
        DB::table('delivery_zones')->where('code', 'ZONE-B')->update([
            'name'                      => 'Zone B - Extended Johor & Melaka (Outstation Cold-Chain)',
            'delivery_fee'              => 0.00,
            'below_threshold_fee'       => 0.00,
            'manual_quotation_required' => true,
            'notes'                     => 'Outstation cold-chain: Transport and Styrofoam box fees quoted via WhatsApp before dispatch.',
            'updated_at'                => now(),
        ]);

        // Update Zone C - West Malaysia / Klang Valley Outstation (Outstation cold-chain quote)
        DB::table('delivery_zones')->where('code', 'ZONE-C')->update([
            'name'                      => 'Zone C - West Malaysia / Outstation (Cold-Chain Courier)',
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
        DB::table('delivery_zones')->where('code', 'ZONE-B')->update([
            'manual_quotation_required' => false,
            'below_threshold_fee'       => 15.00,
        ]);

        DB::table('delivery_zones')->where('code', 'ZONE-C')->update([
            'manual_quotation_required' => false,
            'below_threshold_fee'       => 20.00,
        ]);
    }
};
