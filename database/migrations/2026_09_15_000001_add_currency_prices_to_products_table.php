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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price_sgd', 10, 2)->nullable()->after('retail_price');
            $table->decimal('price_usd', 10, 2)->nullable()->after('price_sgd');
            $table->decimal('wholesale_price_sgd', 10, 2)->nullable()->after('wholesale_price');
            $table->decimal('wholesale_price_usd', 10, 2)->nullable()->after('wholesale_price_sgd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price_sgd', 'price_usd', 'wholesale_price_sgd', 'wholesale_price_usd']);
        });
    }
};
