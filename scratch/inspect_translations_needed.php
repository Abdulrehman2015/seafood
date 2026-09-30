<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Translation;

echo "=== 1. Searching for 客制化采购 in translations ===\n";
$zhMatches = Translation::where('text_zh', 'LIKE', '%客制化采购%')->get();
echo "Found " . $zhMatches->count() . " rows\n";
foreach ($zhMatches as $r) {
    echo "  [{$r->id}] {$r->group}.{$r->key}: {$r->text_zh}\n";
}

echo "\n=== 2. Searching for Perolehan Tersuai in translations ===\n";
$bmMatches = Translation::where('text_bm', 'LIKE', '%Perolehan Tersuai%')
    ->orWhere('text_bm', 'LIKE', '%perolehan tersuai%')
    ->orWhere('text_bm', 'LIKE', '%Sumber Tersuai%')
    ->orWhere('text_bm', 'LIKE', '%sumber tersuai%')
    ->get();
echo "Found " . $bmMatches->count() . " rows\n";
foreach ($bmMatches as $r) {
    echo "  [{$r->id}] {$r->group}.{$r->key}: {$r->text_bm}\n";
}

echo "\n=== 3. Searching for Perdagangan patterns in translations ===\n";
$patterns = [
    '%Akaun Perdagangan%',
    '%Daftar Akaun Perdagangan%',
    '%Harga Perdagangan%',
    '%Keperluan Perdagangan%',
    '%Perdagangan B2B%',
    '%perdagangan eksport%',
    '%Akaun dagangan%',
    '%perdagangan%'
];
$tradingMatches = Translation::where(function($q) {
    $q->where('text_bm', 'LIKE', '%Akaun Perdagangan%')
      ->orWhere('text_bm', 'LIKE', '%Daftar Akaun Perdagangan%')
      ->orWhere('text_bm', 'LIKE', '%Harga Perdagangan%')
      ->orWhere('text_bm', 'LIKE', '%Keperluan Perdagangan%')
      ->orWhere('text_bm', 'LIKE', '%Perdagangan B2B%')
      ->orWhere('text_bm', 'LIKE', '%Meja Perdagangan%')
      ->orWhere('text_bm', 'LIKE', '%penilaian perdagangan%')
      ->orWhere('text_bm', 'LIKE', '%rakan perdagangan%')
      ->orWhere('text_bm', 'LIKE', '%kelayakan perdagangan%')
      ->orWhere('key', 'trading');
})->get();
echo "Found " . $tradingMatches->count() . " rows for Trading terminology\n";
foreach ($tradingMatches as $r) {
    echo "  [{$r->id}] {$r->group}.{$r->key} [EN: {$r->text_en}]: {$r->text_bm}\n";
}
