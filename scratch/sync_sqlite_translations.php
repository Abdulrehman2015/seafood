<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Translation;

$sqliteDb = new PDO('sqlite:' . database_path('database.sqlite'));
$sqliteDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$cnt = $sqliteDb->query("SELECT count(*) FROM translations")->fetchColumn();
echo "SQLite translations count before: {$cnt}\n";

if ($cnt == 0) {
    echo "Copying translations from MySQL to SQLite...\n";
    $all = Translation::all();
    $insert = $sqliteDb->prepare("INSERT INTO translations (id, `group`, `key`, text_en, text_zh, text_bm, created_at, updated_at) VALUES (:id, :group, :key, :en, :zh, :bm, :ca, :ua)");
    $sqliteDb->beginTransaction();
    foreach ($all as $t) {
        $insert->execute([
            ':id'    => $t->id,
            ':group' => $t->group,
            ':key'   => $t->key,
            ':en'    => $t->text_en,
            ':zh'    => $t->text_zh,
            ':bm'    => $t->text_bm,
            ':ca'    => $t->created_at,
            ':ua'    => $t->updated_at,
        ]);
    }
    $sqliteDb->commit();
    echo "Copied " . count($all) . " rows to SQLite translations.\n";
} else {
    echo "Updating SQLite translations from MySQL...\n";
    $all = Translation::all();
    $up = $sqliteDb->prepare("UPDATE translations SET text_en = :en, text_zh = :zh, text_bm = :bm WHERE id = :id");
    $sqliteDb->beginTransaction();
    foreach ($all as $t) {
        $up->execute([
            ':en' => $t->text_en,
            ':zh' => $t->text_zh,
            ':bm' => $t->text_bm,
            ':id' => $t->id,
        ]);
    }
    $sqliteDb->commit();
    echo "Synced all rows in SQLite translations.\n";
}
