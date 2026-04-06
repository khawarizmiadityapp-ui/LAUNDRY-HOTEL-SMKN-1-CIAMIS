<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->string('id_transaksi', 20)->unique();
            $table->string('nama');
            $table->string('kategori', 100);
            $table->string('keterangan', 255)->nullable();
            $table->date('tanggal');
            $table->unsignedBigInteger('nominal');
            $table->enum('status', ['lunas', 'pending', 'urgent'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};