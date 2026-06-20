<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi', 'discount')) {
                $table->integer('discount')->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('transaksi', 'kembalian')) {
                $table->integer('kembalian')->default(0)->after('dibayar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['discount', 'kembalian']);
        });
    }
};
