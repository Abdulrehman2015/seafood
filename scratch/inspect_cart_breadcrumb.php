<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;

$request = Request::create('/bm/cart', 'GET');
$response = $kernel->handle($request);
$content = $response->getContent();
if (str_starts_with($content, "\x1f\x8b")) {
    $content = gzdecode($content);
}

// Find breadcrumb
if (preg_match('/<div class="breadcrumb"[^>]*>(.*?)<\/div>/s', $content, $m)) {
    echo "BM Cart Breadcrumb HTML:\n" . $m[1] . "\n";
} else {
    echo "Breadcrumb div not found.\n";
}
