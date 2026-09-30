<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "=== MYSQL PRODUCTS ===\n";
$dory = Product::where('name', 'LIKE', '%Dory%')->orWhere('sku', 'LIKE', '%DOR%')->get();
foreach ($dory as $p) {
    echo "ID: {$p->id} | SKU: {$p->sku} | Name: {$p->name} | Thumbnail: {$p->thumbnail} | Unit: {$p->unit}\n";
}
$unagi = Product::where('name', 'LIKE', '%Unagi%')->orWhere('sku', 'LIKE', '%UNA%')->get();
foreach ($unagi as $p) {
    echo "ID: {$p->id} | SKU: {$p->sku} | Name: {$p->name} | Thumbnail: {$p->thumbnail} | Unit: {$p->unit}\n";
}

echo "\n=== SQLITE PRODUCTS ===\n";
$sqlite = new PDO('sqlite:' . database_path('database.sqlite'));
$stmt = $sqlite->query("SELECT id, sku, name, thumbnail, unit FROM products WHERE name LIKE '%Dory%' OR name LIKE '%Unagi%'");
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$r['id']} | SKU: {$r['sku']} | Name: {$r['name']} | Thumbnail: {$r['thumbnail']} | Unit: {$r['unit']}\n";
}
