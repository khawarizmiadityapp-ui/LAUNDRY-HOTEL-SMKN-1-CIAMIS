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
            $table->index(['status', 'payment_status']);
            $table->index('created_at');
            $table->index('customer_phone');
            $table->index('transaksi_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropIndex(['status', 'payment_status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['customer_phone']);
            $table->dropIndex(['transaksi_code']);
        });
    }
};
