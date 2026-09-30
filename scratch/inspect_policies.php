<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Policy;

$policies = Policy::all();
foreach ($policies as $p) {
    foreach (['en', 'zh', 'bm'] as $lang) {
        $content = $p->{"content_$lang"};
        if (str_contains($content, 'cookie-settings') || str_contains($content, '#cookie-settings')) {
            echo "Found in policy '{$p->slug}' ($lang)!\n";
        }
    }
}
