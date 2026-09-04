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
            DB::statement("ALTER TABLE `petugas` MODIFY `role` VARCHAR(50) NOT NULL DEFAULT 'Washing'");
        } else {
            Schema::table('petugas', function (Blueprint $table) {
                $table->string('role', 50)->default('Washing')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `petugas` MODIFY `role` ENUM('Admin', 'Operasional', 'Kurir') NOT NULL DEFAULT 'Operasional'");
        } else {
            Schema::table('petugas', function (Blueprint $table) {
                $table->string('role', 50)->default('Operasional')->change();
            });
        }
    }
};
