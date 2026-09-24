<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'existing_customer_ref')) {
                $table->string('existing_customer_ref')->nullable()->after('existing_mst_customer');
            }
            if (!Schema::hasColumn('users', 'additional_message')) {
                $table->text('additional_message')->nullable()->after('import_requirements');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'existing_customer_ref')) {
                $table->dropColumn('existing_customer_ref');
            }
            if (Schema::hasColumn('users', 'additional_message')) {
                $table->dropColumn('additional_message');
            }
        });
    }
};
