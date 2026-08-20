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
        Schema::create('pengajuan_belanjas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan', 30)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nama_pengajuan');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_pengeluaran')->onDelete('set null');
            $table->unsignedBigInteger('estimasi_biaya');
            $table->enum('urgensi', ['biasa', 'mendesak', 'sangat_mendesak'])->default('biasa');
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'selesai'])->default('diajukan');
            $table->text('alasan')->nullable();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_disetujui')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_approval')->nullable();
            $table->string('lampiran')->nullable();
            $table->foreignId('pengeluaran_id')->nullable()->constrained('pengeluarans')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_belanjas');
    }
};
