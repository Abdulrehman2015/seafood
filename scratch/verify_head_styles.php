<?php
$ch = curl_init('http://127.0.0.1:8000/en/products');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_ENCODING, ''); // automatically handle gzip
$html = curl_exec($ch);
curl_close($ch);

$headPos = stripos($html, '</head>');
$bodyPos = stripos($html, '<body');
$stylePos = stripos($html, '.products-hero-section');

echo "Decompressed HTML Length: " . strlen($html) . "\n";
echo "Head end position: $headPos\n";
echo "Body start position: $bodyPos\n";
echo "Shop CSS position: $stylePos\n";

if ($stylePos !== false && $stylePos < $headPos) {
    echo "SUCCESS: All shop styles are rendered inside <head> before <body>!\n";
}
