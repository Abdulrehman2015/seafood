<?php

function inspectFile($path, $label) {
    if (!file_exists($path)) return;
    $content = file_get_contents($path);
    echo "\n======================================================\n";
    echo "FILE: $label (" . strlen($content) . " bytes)\n";
    echo "======================================================\n";

    // 12-hour occurrences
    preg_match_all('/(?:12\s*(?:jam|小时|hour)|within\s*12|tempoh\s*12|12\s*小时内)[^.<>\n]*/ui', $content, $m12);
    if (!empty($m12[0])) {
        echo "[12-Hour Snippets]:\n";
        foreach ($m12[0] as $s) echo "  - " . trim(strip_tags($s)) . "\n";
    }

    // WhatsApp occurrences
    preg_match_all('/wa\.me[^\s"\'<>]+/i', $content, $mWA);
    if (!empty($mWA[0])) {
        echo "[WhatsApp Links]:\n";
        foreach (array_unique($mWA[0]) as $s) echo "  - $s\n";
    }

    // Phone occurrences
    preg_match_all('/(?:\+?60|0)\s*1[0-9\- ]{7,12}/', $content, $mPhone);
    if (!empty($mPhone[0])) {
        echo "[Phone Numbers]:\n";
        foreach (array_unique($mPhone[0]) as $s) echo "  - $s\n";
    }

    // Check specific strings
    $checks = [
        'thawed' => 'Found "thawed"',
        'memadam, memusnahkan atau memadam' => 'Found duplicate "memadam"',
        'Counter 2' => 'Found "Counter 2"',
        'Kaunter 2' => 'Found "Kaunter 2"',
        '2号柜台' => 'Found "2号柜台"',
        'Pesanan Masuk Sendiri' => 'Found "Pesanan Masuk Sendiri"',
        'Pengambilan di Kaunter' => 'Found "Pengambilan di Kaunter"',
        'Walk-in / Self-Collection' => 'Found "Walk-in / Self-Collection"',
        '门店选购 / 到店自提' => 'Found "门店选购 / 到店自提"',
        'Pesanan Walk-in / Pengambilan Sendiri' => 'Found "Pesanan Walk-in / Pengambilan Sendiri"',
    ];

    foreach ($checks as $pattern => $msg) {
        if (stripos($content, $pattern) !== false) {
            echo "  * $msg\n";
        }
    }
}

$slugs = ['refund-policy', 'shipping-policy', 'privacy-policy', 'terms-and-conditions', 'cookie-policy'];
$langs = ['en', 'zh', 'bm'];

foreach ($slugs as $slug) {
    foreach ($langs as $lang) {
        inspectFile(__DIR__ . "/policy_{$slug}_{$lang}.html", "{$slug}_{$lang}");
    }
}
