<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "=== CHECKING PRODUCTS ===\n";
$products = Product::all();
foreach ($products as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | SKU: {$p->sku} | Thumbnail: {$p->thumbnail} | Unit: {$p->unit}\n";
}
