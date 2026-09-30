<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$policies = App\Models\Policy::all();
foreach ($policies as $p) {
    file_put_contents(__DIR__ . "/policy_{$p->slug}_en.html", $p->content);
    file_put_contents(__DIR__ . "/policy_{$p->slug}_zh.html", $p->content_zh);
    file_put_contents(__DIR__ . "/policy_{$p->slug}_bm.html", $p->content_bm);
    echo "Dumped {$p->slug}\n";
}
