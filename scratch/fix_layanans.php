<?php
// Cukup jalankan: php scratch/fix_layanans.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Layanan;

echo "Updating Layanan workflow flags...\n";

$count = Layanan::whereNull('needs_washing')
    ->orWhereNull('needs_ironing')
    ->orWhereNull('needs_packing')
    ->update([
        'needs_washing' => true,
        'needs_ironing' => true,
        'needs_packing' => true
    ]);

echo "Done! Updated {$count} services.\n";
