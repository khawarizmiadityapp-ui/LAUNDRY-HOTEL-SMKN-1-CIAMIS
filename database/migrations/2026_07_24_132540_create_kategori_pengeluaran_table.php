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
        Schema::create('kategori_pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default categories
        DB::table('kategori_pengeluaran')->insert([
            [
                'nama' => 'Operasional',
                'deskripsi' => 'Pengeluaran operasional harian laundry',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bahan Kimia & Sabun',
                'deskripsi' => 'Pembelian deterjen, pewangi, pelembut, dan bahan kimia laundry',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Listrik & Air',
                'deskripsi' => 'Tagihan listrik dan air bulanan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_pengeluaran');
    }
};
