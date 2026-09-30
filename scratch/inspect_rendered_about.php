<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Services\TranslationService;

app(TranslationService::class)->clearCache();

foreach (['en', 'zh', 'bm'] as $loc) {
    app(TranslationService::class)->setLocale($loc);
    app()->setLocale($loc);

    $req = Request::create('/' . $loc . '/about', 'GET');
    $resp = app()->handle($req);
    $content = $resp->getContent();

    echo "=== Locale: {$loc} ===\n";
    // Search for Creed
    if (preg_match('/class="about-creed-grid".*?class="about-cta-bar"/s', $content, $m)) {
        echo "Section 5 Content:\n" . strip_tags($m[0]) . "\n\n";
    }

    // Search for Section 1
    if (preg_match('/class="about-who-grid".*?<\/section>/s', $content, $m)) {
        echo "Section 1 Content:\n" . strip_tags($m[0]) . "\n\n";
    }

    // Search for Pillars
    if (preg_match('/class="about-pillars-grid".*?<\/section>/s', $content, $m)) {
        echo "Pillars Content:\n" . strip_tags($m[0]) . "\n\n";
    }
}
