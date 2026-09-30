<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;
use Illuminate\Support\Facades\DB;

echo "=== AUDITING MISSING TRANSLATIONS IN MYSQL ===\n\n";

$missingZh = Translation::whereNull('text_zh')
    ->orWhere('text_zh', '')
    ->orWhere('text_zh', 'LIKE', '%输入简体中文%')
    ->get();

$missingBm = Translation::whereNull('text_bm')
    ->orWhere('text_bm', '')
    ->orWhere('text_bm', 'LIKE', '%Masukkan Bahasa Melayu%')
    ->get();

$missingEn = Translation::whereNull('text_en')
    ->orWhere('text_en', '')
    ->get();

echo "Total missing / empty Chinese (ZH): " . $missingZh->count() . "\n";
echo "Total missing / empty Malay (BM): " . $missingBm->count() . "\n";
echo "Total missing / empty English (EN): " . $missingEn->count() . "\n\n";

echo "--- Missing Chinese (ZH) Rows ---\n";
foreach ($missingZh as $row) {
    echo "ID: {$row->id} | Group: {$row->group} | Key: {$row->key} | EN: '{$row->text_en}' | BM: '{$row->text_bm}'\n";
}

echo "\n--- Missing Malay (BM) Rows ---\n";
foreach ($missingBm as $row) {
    echo "ID: {$row->id} | Group: {$row->group} | Key: {$row->key} | EN: '{$row->text_en}' | ZH: '{$row->text_zh}'\n";
}
