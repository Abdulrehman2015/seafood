<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            // Pricing for each customer group
            $table->decimal('retail_price', 10, 2)->default(0);
            $table->decimal('walkin_price', 10, 2)->default(0);
            $table->decimal('wholesale_price', 10, 2)->default(0);
            $table->decimal('trading_price', 10, 2)->nullable(); // null = RFQ only
            // Product details
            $table->string('weight')->nullable();       // e.g. "500g", "1kg"
            $table->string('unit')->default('pcs');     // pcs, kg, box, etc.
            $table->string('origin')->nullable();       // Country of origin
            $table->string('storage_temp')->nullable(); // e.g. "-18°C"
            $table->string('brand')->nullable();
            $table->json('specifications')->nullable(); // Extra specs as key-value JSON
            $table->json('images')->nullable();         // Array of image paths
            $table->string('thumbnail')->nullable();    // Main product thumbnail
            // Stock
            $table->integer('stock_quantity')->default(0);
            $table->boolean('track_stock')->default(true);
            // Ordering rules
            $table->integer('moq')->default(1);         // Minimum order quantity
            $table->integer('moq_wholesale')->default(1);
            $table->integer('moq_trading')->default(1);
            // Availability
            $table->boolean('is_active')->default(true);
            $table->boolean('is_walkin_available')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_rfq_only')->default(false); // Trading: price on request
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
