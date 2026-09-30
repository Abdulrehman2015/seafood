<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$urls = ['/', '/shop', '/zh/walkin', '/bm/cart', '/en/policy/shipping-policy', '/zh/policy/terms-and-conditions'];
foreach ($urls as $url) {
    $req = Illuminate\Http\Request::create($url, 'GET');
    $res = $kernel->handle($req);
    echo $url . ' => Status: ' . $res->getStatusCode() . PHP_EOL;
    $kernel->terminate($req, $res);
}
