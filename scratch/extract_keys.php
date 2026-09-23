<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Translation;

$views = glob('resources/views/walkin/*.blade.php');
$views[] = 'resources/views/layouts/app.blade.php';
$views[] = 'resources/views/checkout/success.blade.php';

$keys = [];
foreach ($views as $v) {
    $content = file_get_contents($v);
    preg_match_all('/(?:@t|__t)\s*\(\s*[\'\"]([^\'\"]+)[\'\"]\s*(?:,\s*[\'\"]([^\'\"]*)[\'\"])?/', $content, $matches, PREG_SET_ORDER);
    foreach ($matches as $m) {
        $k = $m[1];
        $def = $m[2] ?? '';
        $keys[$k] = $def;
    }
}

ksort($keys);
echo "Total keys found: " . count($keys) . "\n";
foreach ($keys as $k => $def) {
    $db = Translation::where(function($q) use ($k) {
        if (str_contains($k, '.')) {
            [$g, $item] = explode('.', $k, 2);
            $q->where('group', $g)->where('key', $item);
        } else {
            $q->where('key', $k);
        }
    })->first();

    $zh = $db ? $db->text_zh : '(MISSING IN DB)';
    echo "KEY: $k\n  EN Default: $def\n  DB ZH: $zh\n";
}
