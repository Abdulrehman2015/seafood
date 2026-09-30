<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;

$request = Request::create('/bm/register?type=trading', 'GET');
$response = $kernel->handle($request);
$content = $response->getContent();
if (str_starts_with($content, "\x1f\x8b")) {
    $content = gzdecode($content);
}

if (preg_match('/<div id="tradingRequirementsSection"[^>]*>(.*?)<\/div>\s*<\/div>/s', $content, $m)) {
    echo "tradingRequirementsSection HTML:\n" . substr($m[0], 0, 400) . "\n";
} else {
    echo "Not found with regex. Searching snippet around tradingRequirementsSection:\n";
    $pos = strpos($content, 'tradingRequirementsSection');
    if ($pos !== false) {
        echo substr($content, $pos, 400) . "\n";
    }
}
