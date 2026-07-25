<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add temporary column
        Schema::table('layanans', function (Blueprint $table) {
            $table->unsignedInteger('estimasi_jam')->nullable()->after('estimasi');
        });

        // Step 2: Migrate existing data - parse string values into hours
        $layanans = DB::table('layanans')->get();
        foreach ($layanans as $layanan) {
            $estimasi = $layanan->estimasi;
            $jam = null;

            if ($estimasi) {
                // Try to extract number from strings like "2-3 hari pengerjaan", "1 hari", etc.
                if (preg_match('/(\d+)\s*-\s*(\d+)\s*hari/i', $estimasi, $matches)) {
                    // Range: take the larger number, e.g. "2-3 hari" → 72 hours
                    $jam = (int) $matches[2] * 24;
                } elseif (preg_match('/(\d+)\s*hari/i', $estimasi, $matches)) {
                    // Single number: e.g. "1 hari" → 24 hours
                    $jam = (int) $matches[1] * 24;
                } elseif (preg_match('/(\d+)\s*jam/i', $estimasi, $matches)) {
                    // Already in hours
                    $jam = (int) $matches[1];
                } else {
                    // Default: 48 hours (2 days) for unrecognized strings
                    $jam = 48;
                }
            }

            DB::table('layanans')
                ->where('id', $layanan->id)
                ->update(['estimasi_jam' => $jam]);
        }

        // Step 3: Drop old string column and rename new one
        Schema::table('layanans', function (Blueprint $table) {
            $table->dropColumn('estimasi');
        });

        Schema::table('layanans', function (Blueprint $table) {
            $table->renameColumn('estimasi_jam', 'estimasi');
        });
    }

    public function down(): void
    {
        // Reverse: convert integer back to string
        Schema::table('layanans', function (Blueprint $table) {
            $table->string('estimasi_str')->nullable()->after('estimasi');
        });

        $layanans = DB::table('layanans')->get();
        foreach ($layanans as $layanan) {
            $jam = $layanan->estimasi;
            $str = null;
            if ($jam) {
                if ($jam % 24 === 0) {
                    $hari = $jam / 24;
                    $str = "{$hari} hari pengerjaan";
                } else {
                    $str = "{$jam} jam pengerjaan";
                }
            }
            DB::table('layanans')
                ->where('id', $layanan->id)
                ->update(['estimasi_str' => $str]);
        }

        Schema::table('layanans', function (Blueprint $table) {
            $table->dropColumn('estimasi');
        });

        Schema::table('layanans', function (Blueprint $table) {
            $table->renameColumn('estimasi_str', 'estimasi');
        });
    }
};
