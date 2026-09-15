<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `jadwal_petugas` MODIFY `selected_station` ENUM('washing', 'setrika', 'packing', 'kasir', 'inventory', 'none') NOT NULL DEFAULT 'none'");
        } else {
            Schema::table('jadwal_petugas', function (Blueprint $table) {
                $table->string('selected_station', 50)->default('none')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `jadwal_petugas` MODIFY `selected_station` ENUM('washing', 'setrika', 'packing', 'kasir', 'none') NOT NULL DEFAULT 'none'");
        } else {
            Schema::table('jadwal_petugas', function (Blueprint $table) {
                $table->string('selected_station', 50)->default('none')->change();
            });
        }
    }
};
