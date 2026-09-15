<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        // Seed default folders
        $now = now();
        DB::table('media_folders')->insert([
            ['name' => 'General Gallery', 'slug' => 'gallery', 'is_system' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Products', 'slug' => 'products', 'is_system' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Categories', 'slug' => 'categories', 'is_system' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('media_folders');
    }
};
