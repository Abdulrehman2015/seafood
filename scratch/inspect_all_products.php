<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "Checking product pricing and units across all items:\n";
foreach (Product::all() as $p) {
    echo "ID: {$p->id} | SKU: {$p->sku} | Slug: {$p->slug}\n";
    echo "  Name EN: " . $p->getRawOriginal('name') . "\n";
    echo "  Name ZH: " . $p->name_zh . "\n";
    echo "  Name BM: " . $p->name_bm . "\n";
    echo "  Unit: '{$p->unit}' | Weight: '{$p->weight}' | Pricing Model: '{$p->pricing_model}'\n";
    echo "  Retail RM: {$p->retail_price} | Walkin RM: {$p->walkin_price} | Wholesale RM: {$p->wholesale_price} | Trading RM: {$p->trading_price}\n";
    echo "  SGD: {$p->price_sgd} | USD: {$p->price_usd}\n";
    echo "--------------------------------------------------------\n";
}
