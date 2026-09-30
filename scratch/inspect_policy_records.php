<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Policy;
use Illuminate\Support\Facades\DB;

$policies = Policy::all();

foreach ($policies as $p) {
    echo "=== Policy: {$p->slug} ===\n";
    echo "Title EN: {$p->title}\n";
    echo "Title ZH: {$p->title_zh}\n";
    echo "Title BM: {$p->title_bm}\n";
    echo "Summary EN: {$p->summary}\n";
    echo "Summary ZH: {$p->summary_zh}\n";
    echo "Summary BM: {$p->summary_bm}\n";
    echo "Updated At: {$p->updated_at}\n\n";
}
