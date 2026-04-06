<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('id_petugas')->unique(); // contoh: STF-0012
            $table->enum('role', ['Admin', 'Operasional', 'Kurir'])->default('Operasional');
            $table->enum('status', ['Aktif', 'Off Duty'])->default('Aktif');
            $table->string('shift'); // misal "08:00 - 16:00"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};