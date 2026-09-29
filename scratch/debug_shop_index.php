<?php
$lines = file(__DIR__ . '/../resources/views/shop/index.blade.php');
foreach ($lines as $i => $line) {
    if (str_contains($line, 'origin')) {
        echo ($i + 1) . ": " . trim($line) . "\n";
    }
}
