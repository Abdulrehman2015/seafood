<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Translation;

app()->setLocale('bm');
echo "Eval auth.section_trading_requirements: " . __t('auth.section_trading_requirements', '4. Trading Requirements') . "\n";

$rows = Translation::where('key', 'like', '%section_trading_requirements%')->get();
foreach ($rows as $r) {
    echo "id: {$r->id} | group: '{$r->group}' | key: '{$r->key}' | en: '{$r->text_en}' | bm: '{$r->text_bm}'\n";
}
