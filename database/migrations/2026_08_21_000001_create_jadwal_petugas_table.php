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
        Schema::create('jadwal_petugas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->index();
            $table->string('nama');
            $table->string('id_petugas')->nullable()->index(); // STF-xxxx atau NIS
            $table->string('shift')->default('Pagi'); // Pagi, Siang, dsb.
            $table->enum('selected_station', ['washing', 'setrika', 'packing', 'kasir', 'none'])->default('none');
            $table->timestamp('checked_in_at')->nullable();
            $table->enum('status', ['terjadwal', 'hadir', 'izin', 'alpha'])->default('terjadwal');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->index(['tanggal', 'selected_station']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_petugas');
    }
};
