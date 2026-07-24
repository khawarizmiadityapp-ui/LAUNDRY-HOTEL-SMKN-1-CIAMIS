<?php

/**
 * Script untuk cek dan fix duplicate layanan
 * 
 * CARA PAKAI:
 * 1. php fix_duplicate_layanan.php --check      (cek duplicate saja, tidak delete)
 * 2. php fix_duplicate_layanan.php --fix        (delete duplicate, keep yang pertama)
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Layanan;
use Illuminate\Support\Facades\DB;

$mode = $argv[1] ?? '--check';

echo "\n";
echo "════════════════════════════════════════════\n";
echo "  Fix Duplicate Layanan Script\n";
echo "════════════════════════════════════════════\n\n";

// Get all layanan with duplicate check
$duplicates = DB::table('layanans')
    ->select('nama', 'kategori', DB::raw('COUNT(*) as jumlah'), DB::raw('GROUP_CONCAT(id ORDER BY id) as ids'))
    ->groupBy('nama', 'kategori')
    ->having('jumlah', '>', 1)
    ->get();

if ($duplicates->isEmpty()) {
    echo "✅ GOOD NEWS: Tidak ada layanan duplicate!\n\n";
    echo "Total layanan di database: " . Layanan::count() . "\n";
    exit(0);
}

echo "❌ FOUND DUPLICATES:\n\n";

foreach ($duplicates as $dup) {
    $ids = explode(',', $dup->ids);
    $keepId = $ids[0];
    $deleteIds = array_slice($ids, 1);
    
    echo "┌─ {$dup->nama} ({$dup->kategori})\n";
    echo "│  Total: {$dup->jumlah}x\n";
    echo "│  IDs: {$dup->ids}\n";
    echo "│  ✅ Keep ID: {$keepId}\n";
    echo "│  ❌ Delete IDs: " . implode(', ', $deleteIds) . "\n";
    
    $layanans = Layanan::whereIn('id', $ids)->get();
    foreach ($layanans as $l) {
        echo "│     - ID {$l->id}: Rp {$l->harga} | {$l->satuan} | Status: " . ($l->status ? 'Aktif' : 'Nonaktif') . "\n";
    }
    echo "└─\n\n";
}

echo "Total duplicate groups: " . $duplicates->count() . "\n";
echo "Total layanan yang akan dihapus: " . $duplicates->sum(fn($d) => $d->jumlah - 1) . "\n\n";

if ($mode === '--check') {
    echo "📋 MODE: CHECK ONLY (tidak ada perubahan)\n";
    echo "\nJika ingin menghapus duplicate, jalankan:\n";
    echo "   php fix_duplicate_layanan.php --fix\n\n";
} else if ($mode === '--fix') {
    echo "⚠️  MODE: FIX (akan menghapus duplicate!)\n";
    echo "\nApakah Anda yakin? (y/n): ";
    
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    $confirm = trim(strtolower($line));
    fclose($handle);
    
    if ($confirm !== 'y' && $confirm !== 'yes') {
        echo "\n❌ Dibatalkan oleh user.\n\n";
        exit(0);
    }
    
    echo "\n🔧 Menghapus duplicate...\n\n";
    
    $totalDeleted = 0;
    foreach ($duplicates as $dup) {
        $ids = explode(',', $dup->ids);
        $keepId = $ids[0];
        $deleteIds = array_slice($ids, 1);
        
        // Check if any of the duplicates are being used in transaksi_details
        $usedInTransactions = DB::table('transaksi_details')
            ->whereIn('layanan_id', $deleteIds)
            ->count();
        
        if ($usedInTransactions > 0) {
            echo "⚠️  SKIP: {$dup->nama} - Ada {$usedInTransactions} transaksi yang menggunakan layanan duplicate ini\n";
            echo "   Anda perlu update transaksi_details terlebih dahulu:\n";
            echo "   UPDATE transaksi_details SET layanan_id = {$keepId} WHERE layanan_id IN (" . implode(',', $deleteIds) . ");\n\n";
            continue;
        }
        
        // Safe to delete
        $deleted = Layanan::whereIn('id', $deleteIds)->delete();
        $totalDeleted += $deleted;
        
        echo "✅ Deleted {$deleted} duplicate for: {$dup->nama}\n";
    }
    
    echo "\n";
    echo "════════════════════════════════════════════\n";
    echo "  HASIL:\n";
    echo "  Total layanan dihapus: {$totalDeleted}\n";
    echo "  Layanan tersisa: " . Layanan::count() . "\n";
    echo "════════════════════════════════════════════\n\n";
} else {
    echo "❌ Invalid mode. Use --check or --fix\n\n";
    exit(1);
}
