<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'confirmed_date')) {
                $table->string('confirmed_date')->nullable()->after('delivery_date');
            }
            if (!Schema::hasColumn('orders', 'confirmed_time')) {
                $table->string('confirmed_time')->nullable()->after('confirmed_date');
            }
            if (!Schema::hasColumn('orders', 'notified_at')) {
                $table->timestamp('notified_at')->nullable()->after('confirmed_time');
            }
            if (!Schema::hasColumn('orders', 'notification_notes')) {
                $table->text('notification_notes')->nullable()->after('notified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $cols = ['confirmed_date', 'confirmed_time', 'notified_at', 'notification_notes'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
