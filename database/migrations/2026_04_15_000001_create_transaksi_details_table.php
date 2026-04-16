<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->cascadeOnDelete();
            $table->foreignId('layanan_id')->constrained('layanans')->cascadeOnDelete();
            $table->decimal('qty', 8, 2)->default(1);
            $table->decimal('price', 12, 2);       // harga satuan saat order
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // Add customer_id to transaksi if not exists
        if (!Schema::hasColumn('transaksi', 'customer_id')) {
            Schema::table('transaksi', function (Blueprint $table) {
                $table->foreignId('customer_id')->nullable()->after('user_id')->constrained('customers')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');

        if (Schema::hasColumn('transaksi', 'customer_id')) {
            Schema::table('transaksi', function (Blueprint $table) {
                $table->dropForeign(['customer_id']);
                $table->dropColumn('customer_id');
            });
        }
    }
};
