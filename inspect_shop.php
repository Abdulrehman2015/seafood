<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;

echo "=== CATEGORIES ===\n";
foreach (Category::all() as $cat) {
    echo "ID: {$cat->id} | Name: {$cat->name} | Slug: {$cat->slug} | Parent: {$cat->parent_id}\n";
}

echo "\n=== PRODUCTS ===\n";
foreach (Product::all() as $prod) {
    echo "ID: {$prod->id} | Name: {$prod->name} | Cat: {$prod->category_id} | Origin: {$prod->origin} | Brand: {$prod->brand} | Storage: {$prod->storage_temp} | SKU: {$prod->sku}\n";
}
