<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "Updating Product #16 in MySQL...\n";
$p = Product::find(16);
if ($p) {
    $p->name = 'Live Mud Crabs / Ketam Nipah (±800g / pair)';
    $p->name_bm = 'Ketam Nipah Segar (±800g / Sepasang)';
    $p->name_zh = '青蟹 / 肉蟹 (±800g / 1对装)';
    $p->weight = '±800g';
    $p->reference_weight = '±800g';
    $p->pricing_model = 'variable_weight';
    $p->unit = 'pair';
    $p->save();
    echo "MySQL Product #16 updated: {$p->name} | model: {$p->pricing_model} | ref: {$p->reference_weight}\n";
}

// Also update SQLite
try {
    $sqlite = new PDO('sqlite:' . database_path('database.sqlite'));
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $up = $sqlite->prepare("UPDATE products SET name = :name, name_bm = :name_bm, name_zh = :name_zh, weight = :weight, reference_weight = :ref, pricing_model = :model, unit = :unit WHERE id = 16");
    $up->execute([
        ':name'    => 'Live Mud Crabs / Ketam Nipah (±800g / pair)',
        ':name_bm' => 'Ketam Nipah Segar (±800g / Sepasang)',
        ':name_zh' => '青蟹 / 肉蟹 (±800g / 1对装)',
        ':weight'  => '±800g',
        ':ref'     => '±800g',
        ':model'   => 'variable_weight',
        ':unit'    => 'pair',
    ]);
    echo "SQLite Product #16 updated successfully.\n";
} catch (\Throwable $e) {
    echo "SQLite error: " . $e->getMessage() . "\n";
}

// Set all other products with empty pricing_model to 'fixed_unit'
Product::whereNull('pricing_model')->orWhere('pricing_model', '')->update(['pricing_model' => 'fixed_unit']);
$sqlite->exec("UPDATE products SET pricing_model = 'fixed_unit' WHERE pricing_model IS NULL OR pricing_model = ''");
echo "All other products confirmed as fixed_unit pricing model.\n";
