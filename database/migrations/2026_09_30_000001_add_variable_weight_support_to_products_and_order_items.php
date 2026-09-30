<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'pricing_model')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('pricing_model', 50)->default('fixed_unit')->after('unit'); // 'fixed_unit', 'variable_weight', 'pending_review'
                $table->string('reference_weight', 100)->nullable()->after('pricing_model'); // e.g. '±800g'
                $table->string('actual_weight_unit', 20)->nullable()->after('reference_weight'); // e.g. 'kg', 'g'
                $table->decimal('unit_price_per_weight', 10, 2)->nullable()->after('actual_weight_unit');
            });
        }

        if (Schema::hasTable('order_items') && !Schema::hasColumn('order_items', 'pricing_model')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->string('pricing_model', 50)->default('fixed_unit')->after('quantity');
                $table->string('reference_weight', 100)->nullable()->after('pricing_model');
                $table->decimal('actual_final_weight', 8, 3)->nullable()->after('reference_weight');
                $table->decimal('unit_price_per_weight', 10, 2)->nullable()->after('actual_final_weight');
                $table->decimal('final_calculated_amount', 10, 2)->nullable()->after('unit_price_per_weight');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'pricing_model')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn(['pricing_model', 'reference_weight', 'actual_weight_unit', 'unit_price_per_weight']);
            });
        }

        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'pricing_model')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn(['pricing_model', 'reference_weight', 'actual_final_weight', 'unit_price_per_weight', 'final_calculated_amount']);
            });
        }
    }
};
