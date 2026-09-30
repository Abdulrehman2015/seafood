<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;

echo "=== MySQL Categories with Flet ===\n";
$cats = DB::table('categories')->where('name_bm', 'LIKE', '%Flet%')->get();
foreach ($cats as $c) {
    echo "ID: {$c->id} | {$c->name} | name_bm: {$c->name_bm}\n";
}

echo "=== MySQL Products with Flet ===\n";
$prods = DB::table('products')->where('name_bm', 'LIKE', '%Flet%')->orWhere('short_description_bm', 'LIKE', '%Flet%')->orWhere('description_bm', 'LIKE', '%Flet%')->get();
foreach ($prods as $p) {
    echo "ID: {$p->id} | {$p->name} | name_bm: {$p->name_bm} | short_bm: {$p->short_description_bm}\n";
}

$sqlite = new PDO('sqlite:database/database.sqlite');
$sqCats = $sqlite->query("SELECT id, name, name_bm FROM categories WHERE name_bm LIKE '%Flet%'")->fetchAll(PDO::FETCH_ASSOC);
echo "=== SQLite Categories with Flet === (" . count($sqCats) . ")\n";
foreach ($sqCats as $c) {
    echo "ID: {$c['id']} | {$c['name']} | name_bm: {$c['name_bm']}\n";
}
$sqProds = $sqlite->query("SELECT id, name, name_bm FROM products WHERE name_bm LIKE '%Flet%' OR short_description_bm LIKE '%Flet%'")->fetchAll(PDO::FETCH_ASSOC);
echo "=== SQLite Products with Flet === (" . count($sqProds) . ")\n";
foreach ($sqProds as $p) {
    echo "ID: {$p['id']} | {$p['name']} | name_bm: {$p['name_bm']}\n";
}
