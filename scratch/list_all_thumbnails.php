<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use App\Models\Product;

$all = Product::all(['id', 'name', 'slug', 'thumbnail']);
foreach ($all as $p) {
    echo "ID: {$p->id} | {$p->name} => {$p->thumbnail}\n";
}
