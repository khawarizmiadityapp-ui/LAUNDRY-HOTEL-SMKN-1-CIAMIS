<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\LaundryTask;

$layanans = Layanan::all(['id', 'nama', 'needs_washing', 'needs_ironing', 'needs_packing']);
echo "Layanans:\n";
foreach ($layanans as $l) {
    echo "ID: {$l->id}, Name: {$l->nama}, W: " . ($l->needs_washing ? 'Y' : 'N') . ", I: " . ($l->needs_ironing ? 'Y' : 'N') . ", P: " . ($l->needs_packing ? 'Y' : 'N') . "\n";
}

$latestTransaksi = Transaksi::latest()->first();
if ($latestTransaksi) {
    echo "\nLatest Transaksi: {$latestTransaksi->transaksi_code} (Status: {$latestTransaksi->status})\n";
    $tasks = $latestTransaksi->tasks;
    echo "Tasks:\n";
    foreach ($tasks as $t) {
        echo "- Stage: {$t->stage}, Status: {$t->status}\n";
    }
} else {
    echo "\nNo transactions found.\n";
}
