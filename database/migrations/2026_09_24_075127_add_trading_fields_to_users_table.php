<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'position_role')) {
                $table->string('position_role')->nullable()->after('contact_person');
            }
            if (!Schema::hasColumn('users', 'country_market')) {
                $table->string('country_market')->nullable()->after('business_location');
            }
            if (!Schema::hasColumn('users', 'destination_country')) {
                $table->string('destination_country')->nullable()->after('country_market');
            }
            if (!Schema::hasColumn('users', 'supply_arrangement')) {
                $table->string('supply_arrangement')->nullable()->after('preferred_fulfilment');
            }
            if (!Schema::hasColumn('users', 'commercial_access')) {
                $table->string('commercial_access')->default('Not Assigned')->nullable()->after('approval_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'position_role',
                'country_market',
                'destination_country',
                'supply_arrangement',
                'commercial_access',
            ]);
        });
    }
};
