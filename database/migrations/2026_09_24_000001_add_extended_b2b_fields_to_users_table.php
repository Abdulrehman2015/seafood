<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('business_type');
            }
            if (!Schema::hasColumn('users', 'business_location')) {
                $table->string('business_location')->nullable()->after('contact_person');
            }
            if (!Schema::hasColumn('users', 'estimated_order_volume')) {
                $table->string('estimated_order_volume')->nullable()->after('business_location');
            }
            if (!Schema::hasColumn('users', 'product_interest')) {
                $table->string('product_interest')->nullable()->after('estimated_order_volume');
            }
            if (!Schema::hasColumn('users', 'destination_market')) {
                $table->string('destination_market')->nullable()->after('product_interest');
            }
            if (!Schema::hasColumn('users', 'import_requirements')) {
                $table->text('import_requirements')->nullable()->after('destination_market');
            }
            if (!Schema::hasColumn('users', 'existing_mst_customer')) {
                $table->string('existing_mst_customer')->nullable()->after('import_requirements');
            }
            if (!Schema::hasColumn('users', 'preferred_fulfilment')) {
                $table->string('preferred_fulfilment')->nullable()->after('existing_mst_customer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'contact_person',
                'business_location',
                'estimated_order_volume',
                'product_interest',
                'destination_market',
                'import_requirements',
                'existing_mst_customer',
                'preferred_fulfilment',
            ]);
        });
    }
};
