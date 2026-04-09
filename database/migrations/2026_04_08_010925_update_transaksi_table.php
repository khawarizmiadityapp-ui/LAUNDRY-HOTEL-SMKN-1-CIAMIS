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
        Schema::table('transaksi', function (Blueprint $table) {
            // Ubah user_id jadi nullable agar pelanggan bisa booking tanpa petugas
            $table->foreignId('user_id')->nullable()->change();
            
            // Tambahkan metode pembayaran
            if (!Schema::hasColumn('transaksi', 'payment_method')) {
                $table->string('payment_method')->default('cash')->after('payment_status'); // cash, dana, qris
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->dropColumn('payment_method');
        });
    }
};
