<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== MySQL columns ===\n";
print_r(Schema::getColumnListing('policies'));

$policies = DB::table('policies')->get();
foreach ($policies as $p) {
    echo "ID {$p->id}: {$p->slug} | updated_at: {$p->updated_at}\n";
    echo "  title: {$p->title}\n";
    echo "  title_zh: " . ($p->title_zh ?? 'N/A') . "\n";
    echo "  title_bm: " . ($p->title_bm ?? 'N/A') . "\n";
}

echo "\n=== SQLite columns ===\n";
try {
    $sqlite = new PDO('sqlite:database/database.sqlite');
    $cols = $sqlite->query("PRAGMA table_info(policies)")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $c) {
        echo "  " . $c['name'] . " (" . $c['type'] . ")\n";
    }
    $sqPolicies = $sqlite->query("SELECT id, slug, title, updated_at FROM policies")->fetchAll(PDO::FETCH_ASSOC);
    echo "SQLite policies rows: " . count($sqPolicies) . "\n";
    foreach ($sqPolicies as $sp) {
        echo "  ID {$sp['id']}: {$sp['slug']} | updated_at: {$sp['updated_at']}\n";
    }
} catch (Exception $e) {
    echo "SQLite error: " . $e->getMessage() . "\n";
}
