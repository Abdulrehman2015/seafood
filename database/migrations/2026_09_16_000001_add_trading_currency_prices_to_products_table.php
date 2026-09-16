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
            $table->decimal('trading_price_sgd', 10, 2)->nullable()->after('wholesale_price_usd');
            $table->decimal('trading_price_usd', 10, 2)->nullable()->after('trading_price_sgd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['trading_price_sgd', 'trading_price_usd']);
        });
    }
};
