<?php

$raw = file_get_contents('http://127.0.0.1:8000/en');
// If gzipped, decode
if (str_starts_with($raw, "\x1f\x8b")) {
    $html = gzdecode($raw);
} else {
    $html = $raw;
}

echo "Decoded Length: " . strlen($html) . "\n";
echo "Has pageSwitchLoader: " . (strpos($html, 'pageSwitchLoader') !== false ? 'YES' : 'NO') . "\n";

$bodyPos = strpos($html, '<body');
echo "\n--- Body Start ---\n";
echo substr($html, $bodyPos, 600);
