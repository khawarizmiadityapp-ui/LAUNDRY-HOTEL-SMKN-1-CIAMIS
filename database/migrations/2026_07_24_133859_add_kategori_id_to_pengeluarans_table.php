<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriPengeluaran;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable()->after('kategori')->constrained('kategori_pengeluaran')->onDelete('restrict');
        });

        // Migrate old kategori string to kategori_id
        $kategoris = KategoriPengeluaran::all();
        foreach ($kategoris as $kat) {
            DB::table('pengeluarans')
                ->where('kategori', $kat->nama)
                ->update(['kategori_id' => $kat->id]);
        }

        // Make kategori_id required after migration
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });
    }
};
