<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use App\Models\Product;

$products = Product::where('name', 'LIKE', '%Dory%')
    ->orWhere('name', 'LIKE', '%Unagi%')
    ->orWhere('name', 'LIKE', '%Barramundi%')
    ->orWhere('name', 'LIKE', '%Squid%')
    ->get(['id', 'name', 'slug', 'thumbnail', 'images']);

foreach ($products as $p) {
    echo "ID: {$p->id} | {$p->name} | Slug: {$p->slug}\n";
    echo "  Thumbnail: {$p->thumbnail}\n";
    echo "  Images: " . json_encode($p->images) . "\n";
}
