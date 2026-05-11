<?php

// Test if MenuService can be loaded
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $menuService = app(\App\Services\MenuService::class);
    echo "✅ MenuService loaded successfully!\n";
    
    $brand = $menuService->getBrandInfo();
    echo "✅ Brand: " . $brand['name'] . "\n";
    
    $initials = $menuService->getUserInitials('John Doe');
    echo "✅ Initials: " . $initials . "\n";
    
    echo "\n✅ All tests passed! Service is working.\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
