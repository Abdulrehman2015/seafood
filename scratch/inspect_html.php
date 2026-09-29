<?php
$ch = curl_init('http://127.0.0.1:8000/en/products');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
curl_close($ch);

echo substr($html, 0, 1500);
