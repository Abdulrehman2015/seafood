<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

app()->setLocale('zh');
session(['locale' => 'zh']);

$order = \App\Models\Order::find(20);
$view = view('checkout.success', compact('order'))->render();

preg_match_all('/.{0,40}Copy.{0,40}/u', $view, $matches);
foreach ($matches[0] as $match) {
    echo "Found match: " . trim($match) . "\n";
}
