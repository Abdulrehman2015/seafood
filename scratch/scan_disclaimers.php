<?php
$files = glob(__DIR__ . "/policy_*.html");
foreach ($files as $file) {
    $content = file_get_contents($file);
    $basename = basename($file);
    echo "=== $basename ===\n";
    // Check English absolute phrases
    if (preg_match_all('/([^.\n<>]*(?:not responsible|bears no responsibility|no liability|shall not be liable|cannot be held liable)[^.\n<>]*)/i', $content, $m)) {
        foreach ($m[0] as $match) {
            echo "  [EN Match]: " . trim($match) . "\n";
        }
    }
    // Check Chinese absolute phrases
    if (preg_match_all('/([^。\n<>]*(?:不承担|概不负责|无须承担|免责|不负责任)[^。\n<>]*)/u', $content, $m)) {
        foreach ($m[0] as $match) {
            echo "  [ZH Match]: " . trim($match) . "\n";
        }
    }
    // Check BM absolute phrases
    if (preg_match_all('/([^.\n<>]*(?:tidak bertanggungjawab|tidak menanggung|tiada liabiliti|tidak akan bertanggungan)[^.\n<>]*)/i', $content, $m)) {
        foreach ($m[0] as $match) {
            echo "  [BM Match]: " . trim($match) . "\n";
        }
    }
}
