<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$policies = App\Models\Policy::all();
foreach ($policies as $p) {
    echo "=========================================================\n";
    echo "POLICY: {$p->slug} (ID: {$p->id})\n";
    echo "=========================================================\n";
    foreach (['en', 'zh', 'bm'] as $loc) {
        $col = 'content_' . $loc;
        $text = $p->$col;
        echo "\n--- [{$loc}] (length: " . strlen($text) . ") ---\n";
        
        // check WhatsApp
        preg_match_all('/wa\.me[^\s"\'<>]+/i', $text, $m1);
        if (!empty($m1[0])) {
            echo "  WhatsApp Links: " . implode(', ', array_unique($m1[0])) . "\n";
        }
        
        // check Phone numbers
        preg_match_all('/(?:\+?60|0)\s*1[0-9\- ]{7,12}/', $text, $m2);
        if (!empty($m2[0])) {
            echo "  Phone numbers: " . implode(', ', array_unique($m2[0])) . "\n";
        }

        // check Counter 2 / Kaunter 2 / 2号柜台
        preg_match_all('/(?:counter\s*2|kaunter\s*2|2\s*号柜台)/ui', $text, $mCounter);
        if (!empty($mCounter[0])) {
            echo "  Counter 2 occurrences: " . implode(', ', array_unique($mCounter[0])) . "\n";
        }

        // check (thawed) / memadam
        if (stripos($text, 'thawed') !== false) {
            echo "  FOUND 'thawed' in text!\n";
        }
        if (stripos($text, 'memadam, memusnahkan atau memadam') !== false) {
            echo "  FOUND duplicate 'memadam, memusnahkan atau memadam' in text!\n";
        }
        if (stripos($text, 'Pesanan Masuk Sendiri') !== false) {
            echo "  FOUND 'Pesanan Masuk Sendiri' in text!\n";
        }
    }
}
