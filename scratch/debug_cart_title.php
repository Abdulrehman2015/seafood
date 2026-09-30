<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Translation;

app()->setLocale('bm');

echo "@t('cart.title'): " . __t('cart.title', 'Shopping Cart') . "\n";

$rows = Translation::where('key', 'like', '%title%')->orWhere('key', 'cart')->get();
foreach ($rows as $r) {
    if (str_contains($r->text_bm, 'Walk-in') || $r->group === 'cart') {
        echo "id: {$r->id} | group: '{$r->group}' | key: '{$r->key}' | en: '{$r->text_en}' | bm: '{$r->text_bm}'\n";
    }
}
