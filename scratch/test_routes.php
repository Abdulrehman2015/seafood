<?php

$urls = [
    'http://127.0.0.1:8000/en',
    'http://127.0.0.1:8000/en/products',
    'http://127.0.0.1:8000/en/walkin',
    'http://127.0.0.1:8000/en/about',
    'http://127.0.0.1:8000/en/contact',
    'http://127.0.0.1:8000/en/cart',
    'http://127.0.0.1:8000/zh',
    'http://127.0.0.1:8000/zh/products',
    'http://127.0.0.1:8000/bm',
    'http://127.0.0.1:8000/bm/products',
];

foreach ($urls as $url) {
    $start = microtime(true);
    $ctx = stream_context_create(['http' => ['timeout' => 5]]);
    $raw = @file_get_contents($url, false, $ctx);
    $time = round((microtime(true) - $start) * 1000, 1);
    if ($raw === false) {
        echo "[FAIL] $url ($time ms)\n";
        continue;
    }
    
    $html = str_starts_with($raw, "\x1f\x8b") ? gzdecode($raw) : $raw;
    
    $hasLoader = strpos($html, 'id="pageSwitchLoader"') !== false;
    $hasActive = strpos($html, 'class="page-switch-loader active"') !== false;
    $hasTabLoader = strpos($html, 'id="shopTabLoader"') !== false;
    $hasStartTimeScript = strpos($html, 'window.__pageLoadStartTime') !== false;
    $hasScheduleScript = strpos($html, 'schedulePageLoaderFinish') !== false;
    
    echo "[OK] $url ({$time}ms, " . strlen($html) . " bytes) - GlobalLoader: " . ($hasLoader ? 'YES' : 'NO') . ", Active: " . ($hasActive ? 'YES' : 'NO') . ", DuplicateTabLoader: " . ($hasTabLoader ? 'DUPLICATE FOUND' : 'NONE (PERFECT)') . ", StartTime: " . ($hasStartTimeScript ? 'YES' : 'NO') . ", 1sFinisher: " . ($hasScheduleScript ? 'YES' : 'NO') . "\n";
}
