<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;

echo "=== Updating MySQL ===\n";

// 1. Correct Product Images (#22)
$doryUpdated = DB::table('products')->where('slug', 'premium-dory-fish-fillet-1kg')->update([
    'thumbnail' => 'products/dory_fish_fillet.jpg',
]);
echo "Updated Dory image in MySQL: $doryUpdated\n";

$unagiUpdated = DB::table('products')->where('slug', 'japanese-seasoned-unagi-kabayaki-200g')->update([
    'thumbnail' => 'products/unagi_kabayaki.jpg',
]);
echo "Updated Unagi image in MySQL: $unagiUpdated\n";

// 2. Correct BM Product Terminology (#23)
$catUpdated = DB::table('categories')->where('slug', 'fish-fillet')->update([
    'name_bm' => 'Fillet Ikan',
]);
echo "Updated Category fish-fillet name_bm: $catUpdated\n";

// Update any Flet in products table in MySQL
$prods = DB::table('products')->get();
foreach ($prods as $p) {
    $nameBm = $p->name_bm;
    $shortBm = $p->short_description_bm;
    $changed = false;

    if ($nameBm && str_contains($nameBm, 'Flet')) {
        $nameBm = str_replace('Flet', 'Fillet', $nameBm);
        $changed = true;
    }
    if ($shortBm && str_contains($shortBm, 'Flet')) {
        $shortBm = str_replace('Flet', 'Fillet', $shortBm);
        $changed = true;
    }

    if ($changed) {
        DB::table('products')->where('id', $p->id)->update([
            'name_bm' => $nameBm,
            'short_description_bm' => $shortBm,
        ]);
        echo "Updated product ID {$p->id} in MySQL ({$p->name})\n";
    }
}

echo "\n=== Updating SQLite ===\n";
$sqlite = new PDO('sqlite:database/database.sqlite');
$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $sqlite->prepare("UPDATE products SET thumbnail = 'products/dory_fish_fillet.jpg' WHERE slug = 'premium-dory-fish-fillet-1kg'");
$stmt->execute();
echo "Updated Dory in SQLite: " . $stmt->rowCount() . "\n";

$stmt = $sqlite->prepare("UPDATE products SET thumbnail = 'products/unagi_kabayaki.jpg' WHERE slug = 'japanese-seasoned-unagi-kabayaki-200g'");
$stmt->execute();
echo "Updated Unagi in SQLite: " . $stmt->rowCount() . "\n";

$stmt = $sqlite->prepare("UPDATE categories SET name_bm = 'Fillet Ikan' WHERE slug = 'fish-fillet'");
$stmt->execute();
echo "Updated Category in SQLite: " . $stmt->rowCount() . "\n";

$sqRows = $sqlite->query("SELECT id, name, name_bm, short_description_bm FROM products")->fetchAll(PDO::FETCH_ASSOC);
foreach ($sqRows as $r) {
    $nameBm = $r['name_bm'];
    $shortBm = $r['short_description_bm'];
    $changed = false;

    if ($nameBm && str_contains($nameBm, 'Flet')) {
        $nameBm = str_replace('Flet', 'Fillet', $nameBm);
        $changed = true;
    }
    if ($shortBm && str_contains($shortBm, 'Flet')) {
        $shortBm = str_replace('Flet', 'Fillet', $shortBm);
        $changed = true;
    }

    if ($changed) {
        $up = $sqlite->prepare("UPDATE products SET name_bm = ?, short_description_bm = ? WHERE id = ?");
        $up->execute([$nameBm, $shortBm, $r['id']]);
        echo "Updated product ID {$r['id']} in SQLite\n";
    }
}

echo "Database updates completed successfully!\n";
