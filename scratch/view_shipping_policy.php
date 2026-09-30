<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$sp = DB::table('policies')->where('slug', 'shipping-policy')->first();
if ($sp) {
    echo "=== MySQL Shipping Policy Content Preview ===\n";
    echo "Title: {$sp->title}\n";
    echo "Content EN length: " . strlen($sp->content) . "\n";
    echo "Content ZH length: " . strlen($sp->content_zh ?? '') . "\n";
    echo "Content BM length: " . strlen($sp->content_bm ?? '') . "\n";
    file_put_contents('scratch/shipping_en.html', $sp->content);
    file_put_contents('scratch/shipping_zh.html', $sp->content_zh ?? '');
    file_put_contents('scratch/shipping_bm.html', $sp->content_bm ?? '');
    echo "Saved to scratch/shipping_{en,zh,bm}.html\n";
} else {
    echo "Shipping policy not found in MySQL\n";
}

$tc = DB::table('policies')->where('slug', 'terms-and-conditions')->first();
if ($tc) {
    echo "=== MySQL Terms Content Preview ===\n";
    echo "Title: {$tc->title}\n";
    file_put_contents('scratch/terms_en.html', $tc->content);
    file_put_contents('scratch/terms_zh.html', $tc->content_zh ?? '');
    file_put_contents('scratch/terms_bm.html', $tc->content_bm ?? '');
    echo "Saved to scratch/terms_{en,zh,bm}.html\n";
}
