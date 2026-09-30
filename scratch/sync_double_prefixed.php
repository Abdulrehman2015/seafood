<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;

$sqliteDb = new PDO('sqlite:' . database_path('database.sqlite'));
$sqliteDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$doubleKeys = Translation::where('key', 'like', 'auth.%')
    ->orWhere('key', 'like', 'cart.%')
    ->orWhere('key', 'like', 'walkin.%')
    ->orWhere('key', 'like', 'shop.%')
    ->get();

echo "Found " . count($doubleKeys) . " double-prefixed keys in MySQL.\n";
foreach ($doubleKeys as $dk) {
    // If stripped key exists in the same group, sync it!
    $strippedKey = substr($dk->key, strpos($dk->key, '.') + 1);
    $baseRecord = Translation::where('group', $dk->group)->where('key', $strippedKey)->first();
    if ($baseRecord) {
        $dk->text_en = $baseRecord->text_en;
        $dk->text_zh = $baseRecord->text_zh;
        $dk->text_bm = $baseRecord->text_bm;
        $dk->save();
        echo "Synced [{$dk->group}.{$dk->key}] to '{$baseRecord->text_bm}'\n";
    }
}

// Also sync all to SQLite
echo "Syncing all to SQLite...\n";
$all = Translation::all();
$sqliteDb->beginTransaction();
$up = $sqliteDb->prepare("UPDATE translations SET text_en = :en, text_zh = :zh, text_bm = :bm WHERE `group` = :group AND `key` = :key");
$in = $sqliteDb->prepare("INSERT INTO translations (`group`, `key`, text_en, text_zh, text_bm, created_at, updated_at) VALUES (:group, :key, :en, :zh, :bm, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
$check = $sqliteDb->prepare("SELECT id FROM translations WHERE `group` = :group AND `key` = :key");

foreach ($all as $t) {
    $check->execute([':group' => $t->group, ':key' => $t->key]);
    if ($check->fetchColumn()) {
        $up->execute([':en' => $t->text_en, ':zh' => $t->text_zh, ':bm' => $t->text_bm, ':group' => $t->group, ':key' => $t->key]);
    } else {
        $in->execute([':group' => $t->group, ':key' => $t->key, ':en' => $t->text_en, ':zh' => $t->text_zh, ':bm' => $t->text_bm]);
    }
}
$sqliteDb->commit();

Cache::flush();
echo "Done!\n";
