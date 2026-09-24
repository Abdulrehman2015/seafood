<?php

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;

$translations = [
    'nav.select_language' => [
        'en' => 'Select Language',
        'zh' => '选择语言',
        'bm' => 'Pilih Bahasa',
    ],
    'nav.select_currency' => [
        'en' => 'Select Currency',
        'zh' => '选择货币',
        'bm' => 'Pilih Mata Wang',
    ],
    'nav.currency_indicative_note' => [
        'en' => 'Currency conversion is indicative only. Final pricing may vary according to the applicable exchange rate.',
        'zh' => '货币换算仅供参考。最终结算金额可能因适用汇率而有所差异。',
        'bm' => 'Penukaran mata wang adalah indikatif sahaja. Harga akhir mungkin berbeza mengikut kadar pertukaran yang berkenaan.',
    ],
    'nav.signin_sub' => [
        'en' => 'Sign in to manage your account or request business access',
        'zh' => '登录以管理您的账户或申请企业权限',
        'bm' => 'Log masuk untuk mengurus akaun anda atau memohon akses perniagaan',
    ],
];

foreach ($translations as $key => $values) {
    Translation::updateOrCreate(
        ['key' => $key],
        [
            'group' => 'nav',
            'text_en' => $values['en'],
            'text_zh' => $values['zh'],
            'text_bm' => $values['bm'],
        ]
    );
}

$langs = ['en', 'zh', 'bm', 'ms'];
foreach ($langs as $lang) {
    $file = __DIR__ . "/../lang/{$lang}.json";
    $data = [];
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true) ?: [];
    }
    foreach ($translations as $key => $values) {
        $data[$key] = $values[$lang] ?? ($values['en'] ?? '');
    }
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

Cache::flush();
\Illuminate\Support\Facades\Artisan::call('cache:clear');
\Illuminate\Support\Facades\Artisan::call('view:clear');

echo "Header translations synced and cache cleared!\n";
