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
        Schema::table('daily_targets', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_targets', 'is_workday')) {
                $table->boolean('is_workday')->default(true)->after('date')->comment('Apakah hari ini hari kerja aktif');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_targets', function (Blueprint $table) {
            if (Schema::hasColumn('daily_targets', 'is_workday')) {
                $table->dropColumn('is_workday');
            }
        });
    }
};
