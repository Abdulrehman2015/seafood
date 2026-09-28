<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // e.g. "Zone A - Johor Bahru & Iskandar Puteri"
            $table->string('code')->unique();                // e.g. "ZONE-A", "JB-LOCAL"
            $table->text('description')->nullable();         // e.g. "Selected Johor Bahru / Iskandar Puteri areas"
            $table->text('postcodes')->nullable();           // Comma or newline separated postcodes/prefixes (e.g. 79000, 79100, 80000, 81000)
            $table->text('areas')->nullable();               // Comma or newline separated cities / areas
            $table->text('states')->nullable();              // Comma or newline separated states (e.g. Johor)
            $table->decimal('delivery_fee', 10, 2)->default(0.00);            // Normal / base delivery fee
            $table->decimal('below_threshold_fee', 10, 2)->default(0.00);     // Additional fee if cart is below RM100
            $table->boolean('is_b2c_enabled')->default(true);
            $table->boolean('is_b2b_enabled')->default(false);
            $table->boolean('is_trading_enabled')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('manual_quotation_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Seed default initial zones so MST has clean starter records to edit
        DB::table('delivery_zones')->insert([
            [
                'name'                      => 'Zone A - Johor Bahru & Iskandar Puteri',
                'code'                      => 'ZONE-A',
                'description'               => 'Selected Johor Bahru, Iskandar Puteri, Nusajaya, Skudai and surrounding local areas',
                'postcodes'                 => "79000\n79100\n79200\n79250\n79500\n80000\n80050\n80100\n80150\n80200\n80250\n80300\n80350\n80400\n80500\n81100\n81200\n81300\n81400",
                'areas'                     => "Johor Bahru, JB, Iskandar Puteri, Nusajaya, Skudai, Gelang Patah, Kulai, Senai, Tampoi, Perling, Bukit Indah, Ulu Tiram, Masai, Pasir Gudang",
                'states'                    => "Johor",
                'delivery_fee'              => 0.00,
                'below_threshold_fee'       => 10.00,
                'is_b2c_enabled'            => true,
                'is_b2b_enabled'            => true,
                'is_trading_enabled'        => false,
                'is_active'                 => true,
                'manual_quotation_required' => false,
                'sort_order'                => 1,
                'notes'                     => 'Primary direct cold-chain delivery hub vicinity.',
                'created_at'                => now(),
                'updated_at'                => now(),
            ],
            [
                'name'                      => 'Zone B - Extended Johor & Melaka',
                'code'                      => 'ZONE-B',
                'description'               => 'Extended Johor districts (Batu Pahat, Muar, Kluang, Kota Tinggi, Pontian) & Melaka',
                'postcodes'                 => "82000\n83000\n84000\n85000\n86000\n75000\n76000\n77000",
                'areas'                     => "Batu Pahat, Muar, Kluang, Kota Tinggi, Pontian, Segamat, Mersing, Melaka, Malacca, Ayer Keroh, Alor Gajah, Jasin",
                'states'                    => "Johor, Melaka",
                'delivery_fee'              => 0.00,
                'below_threshold_fee'       => 15.00,
                'is_b2c_enabled'            => true,
                'is_b2b_enabled'            => true,
                'is_trading_enabled'        => false,
                'is_active'                 => true,
                'manual_quotation_required' => false,
                'sort_order'                => 2,
                'notes'                     => 'Regional southern peninsular logistics route.',
                'created_at'                => now(),
                'updated_at'                => now(),
            ],
            [
                'name'                      => 'Zone C - West Malaysia / Klang Valley Outstation',
                'code'                      => 'ZONE-C',
                'description'               => 'Kuala Lumpur, Selangor, Putrajaya, Negeri Sembilan, Perak, Penang, Pahang, Kedah, Terengganu, Kelantan, Perlis',
                'postcodes'                 => "40000\n41000\n42000\n43000\n46000\n47000\n48000\n50000\n51000\n52000\n53000\n54000\n55000\n56000\n57000\n58000\n59000\n60000\n62000\n70000\n30000\n10000\n25000",
                'areas'                     => "Kuala Lumpur, KL, Petaling Jaya, PJ, Shah Alam, Subang Jaya, Klang, Puchong, Cheras, Putrajaya, Cyberjaya, Seremban, Ipoh, George Town, Kuantan",
                'states'                    => "Kuala Lumpur, Selangor, Putrajaya, Negeri Sembilan, Perak, Penang, Pulau Pinang, Kedah, Pahang, Terengganu, Kelantan, Perlis",
                'delivery_fee'              => 0.00,
                'below_threshold_fee'       => 20.00,
                'is_b2c_enabled'            => true,
                'is_b2b_enabled'            => true,
                'is_trading_enabled'        => false,
                'is_active'                 => true,
                'manual_quotation_required' => false,
                'sort_order'                => 3,
                'notes'                     => 'Sub-zero cold courier logistics partners.',
                'created_at'                => now(),
                'updated_at'                => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};
