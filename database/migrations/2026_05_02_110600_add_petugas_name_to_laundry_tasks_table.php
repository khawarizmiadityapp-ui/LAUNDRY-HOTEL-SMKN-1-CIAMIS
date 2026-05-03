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
        Schema::table('laundry_tasks', function (Blueprint $table) {
            $table->string('petugas_name')->nullable()->after('petugas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laundry_tasks', function (Blueprint $table) {
            $table->dropColumn('petugas_name');
        });
    }
};
