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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('transaksi_code')->unique();
            $table->foreignId('user_id')->constrained(); // Petugas
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->enum('service_type', ['regular', 'express']);
            $table->decimal('weight', 8, 2); // kg
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['diterima', 'disortir', 'dicuci', 'dikeringkan', 'disetrika', 'dipacking', 'selesai', 'diambil'])->default('diterima');
            $table->enum('payment_status', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
