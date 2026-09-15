<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable()->index(); // for guests & walk-ins
            $table->unsignedBigInteger('user_id')->nullable()->index(); // for logged-in users
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->string('customer_group')->default('retail'); // price group at time of adding
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
