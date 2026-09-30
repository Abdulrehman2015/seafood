<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

$crabs = Product::where('name', 'LIKE', '%Crab%')
    ->orWhere('name', 'LIKE', '%Mud%')
    ->orWhere('name', 'LIKE', '%Ketam%')
    ->orWhere('name_bm', 'LIKE', '%Ketam%')
    ->orWhere('name_zh', 'LIKE', '%蟹%')
    ->get();

echo "Found " . $crabs->count() . " crab products:\n";
foreach ($crabs as $c) {
    echo "ID: {$c->id}, Name: {$c->name}, Name BM: {$c->name_bm}, Name ZH: {$c->name_zh}\n";
    echo "Unit: {$c->unit}, Weight: {$c->weight}, Retail Price: {$c->retail_price}\n";
    echo "Specs: " . json_encode($c->specifications) . "\n";
    echo "Short Desc: {$c->short_description}\n";
    echo "---\n";
}

echo "\nAll products with unit or weight:\n";
$all = Product::select('id', 'name', 'unit', 'weight', 'retail_price')->get();
foreach ($all as $item) {
    echo "#{$item->id}: {$item->name} | Unit: [{$item->unit}] | Weight: [{$item->weight}] | Price: [{$item->retail_price}]\n";
}
