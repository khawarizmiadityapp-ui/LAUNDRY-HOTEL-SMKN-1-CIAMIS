<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengeluarans', 'bon_file')) {
                $table->string('bon_file')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            if (Schema::hasColumn('pengeluarans', 'bon_file')) {
                $table->dropColumn('bon_file');
            }
        });
    }
};
