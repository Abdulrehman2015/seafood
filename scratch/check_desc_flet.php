<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;

$prods = DB::table('products')->where('description_bm', 'LIKE', '%Flet%')->get();
echo "Found " . count($prods) . " products with Flet in description_bm:\n";
foreach ($prods as $p) {
    echo "ID {$p->id}: {$p->name}\n";
}
