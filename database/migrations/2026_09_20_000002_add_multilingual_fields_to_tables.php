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
        // 1. Products table
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_zh')->nullable()->after('name');
            $table->string('name_bm')->nullable()->after('name_zh');
            $table->text('short_description_zh')->nullable()->after('short_description');
            $table->text('short_description_bm')->nullable()->after('short_description_zh');
            $table->longText('description_zh')->nullable()->after('description');
            $table->longText('description_bm')->nullable()->after('description_zh');
        });

        // 2. Categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_zh')->nullable()->after('name');
            $table->string('name_bm')->nullable()->after('name_zh');
        });

        // 3. Users table (customer language preference)
        Schema::table('users', function (Blueprint $table) {
            $table->string('preferred_locale', 10)->default('en')->after('email');
        });

        // 4. Page SEO table
        if (Schema::hasTable('page_seos')) {
            Schema::table('page_seos', function (Blueprint $table) {
                $table->string('meta_title_zh')->nullable()->after('meta_title');
                $table->string('meta_title_bm')->nullable()->after('meta_title_zh');
                $table->text('meta_description_zh')->nullable()->after('meta_description');
                $table->text('meta_description_bm')->nullable()->after('meta_description_zh');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'name_zh', 'name_bm',
                'short_description_zh', 'short_description_bm',
                'description_zh', 'description_bm'
            ]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_zh', 'name_bm']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['preferred_locale']);
        });

        if (Schema::hasTable('page_seos')) {
            Schema::table('page_seos', function (Blueprint $table) {
                $table->dropColumn([
                    'meta_title_zh', 'meta_title_bm',
                    'meta_description_zh', 'meta_description_bm'
                ]);
            });
        }
    }
};
