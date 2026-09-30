<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Translation;

$rows = Translation::where('text_bm', 'LIKE', '%Walk-in Express%')->get();
foreach ($rows as $r) {
    echo "id: {$r->id} | group: '{$r->group}' | key: '{$r->key}' | en: '{$r->text_en}' | bm: '{$r->text_bm}'\n";
}
