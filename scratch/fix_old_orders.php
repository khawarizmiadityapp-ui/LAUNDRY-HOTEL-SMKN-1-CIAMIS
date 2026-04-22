<?php
// Skrip untuk memperbaiki pesanan lama yang tidak muncul di antrean
// Jalankan dengan: php scratch/fix_old_orders.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Transaksi;
use App\Models\LaundryTask;

echo "Mencari pesanan lama yang belum masuk antrean...\n";

// Ambil transaksi yang tidak memiliki task sama sekali
$transaksis = Transaksi::doesntHave('tasks')->get();

$count = 0;
foreach ($transaksis as $trx) {
    echo "Memperbaiki TRX: {$trx->transaksi_code}...\n";
    
    // Default: buat 3 tahap jika pesanan lama (asumsi butuh proses lengkap)
    $trx->tasks()->create(['stage' => 'washing', 'status' => 'pending']);
    $trx->tasks()->create(['stage' => 'ironing', 'status' => 'pending']);
    $trx->tasks()->create(['stage' => 'packing', 'status' => 'pending']);
    
    $count++;
}

echo "\nSelesai! Berhasil memulihkan {$count} pesanan ke dalam antrean.\n";
echo "Silakan cek kembali halaman Washing / Antrean Anda.\n";
