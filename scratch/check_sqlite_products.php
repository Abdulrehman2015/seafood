<?php
$sqlite = new PDO('sqlite:database/database.sqlite');
$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$tables = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
echo "SQLite tables: " . implode(', ', $tables) . "\n";

if (in_array('products', $tables)) {
    $rows = $sqlite->query("SELECT id, name, slug, thumbnail FROM products WHERE slug IN ('premium-dory-fish-fillet-1kg', 'japanese-seasoned-unagi-kabayaki-200g')")->fetchAll(PDO::FETCH_ASSOC);
    echo "Found " . count($rows) . " products in SQLite:\n";
    foreach ($rows as $r) {
        echo "  ID: {$r['id']} | {$r['name']} | {$r['thumbnail']}\n";
    }
}
