<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

echo "=== FIXING CORRUPTED TITLES AND REMAINING OLD TERMS ===\n\n";

$sqliteDb = new PDO('sqlite:' . database_path('database.sqlite'));
$sqliteDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$updates = [
    // Corrupted rows where text_bm was 'Walk-in Express'
    [
        'group' => 'cart',
        'key'   => 'cart.title',
        'en'    => 'Shopping Cart',
        'zh'    => '您的购物车',
        'bm'    => 'Troli Beli-belah',
    ],
    [
        'group' => 'checkout',
        'key'   => 'title',
        'en'    => 'Checkout & Payment',
        'zh'    => '结账与支付',
        'bm'    => 'Daftar Keluar & Pembayaran',
    ],
    [
        'group' => 'checkout',
        'key'   => 'page_title',
        'en'    => 'Checkout & Payment — MST Import and Export Sdn. Bhd.',
        'zh'    => '结账与支付 — MST Import and Export Sdn. Bhd.',
        'bm'    => 'Daftar Keluar & Pembayaran — MST Import and Export Sdn. Bhd.',
    ],
    [
        'group' => 'contact',
        'key'   => 'title',
        'en'    => 'Contact Us',
        'zh'    => '联系我们',
        'bm'    => 'Hubungi Kami',
    ],
    [
        'group' => 'rfq',
        'key'   => 'title',
        'en'    => 'Request a Commercial Quotation (RFQ)',
        'zh'    => '申请商业报价 (RFQ)',
        'bm'    => 'Minta Sebut Harga Komersial (RFQ)',
    ],
    [
        'group' => 'rfq',
        'key'   => 'page_title',
        'en'    => 'Request a Quotation (RFQ) — MST Import and Export Sdn. Bhd.',
        'zh'    => '申请报价 (RFQ) — MST Import and Export Sdn. Bhd.',
        'bm'    => 'Minta Sebut Harga (RFQ) — MST Import and Export Sdn. Bhd.',
    ],
    [
        'group' => 'invoice',
        'key'   => 'title',
        'en'    => 'INVOICE',
        'zh'    => '发票',
        'bm'    => 'INVOIS',
    ],

    // 4. Keperluan Dagangan with section number
    [
        'group' => 'auth',
        'key'   => 'section_trading_requirements',
        'en'    => '4. Trading Requirements',
        'zh'    => '4. 贸易需求',
        'bm'    => '4. Keperluan Dagangan',
    ],
    [
        'group' => 'common',
        'key'   => 'section_trading_requirements',
        'en'    => '4. Trading Requirements',
        'zh'    => '4. 贸易需求',
        'bm'    => '4. Keperluan Dagangan',
    ],

    // Neutral Payment and Handling
    [
        'group' => 'common',
        'key'   => 'stripe_info_desc',
        'en'    => 'You will be redirected to finalize payment. Upon payment confirmation, your order will be prepared for collection at our premises.',
        'zh'    => '您将被引导以完成付款。付款确认后，您的订单将在我们现场备妥供自提。',
        'bm'    => 'Anda akan dialihkan ke halaman pembayaran. Setelah pembayaran disahkan, pesanan anda akan disediakan untuk pengambilan di premis kami.',
    ],
    [
        'group' => 'walkin',
        'key'   => 'stripe_info_desc',
        'en'    => 'You will be redirected to finalize payment. Upon payment confirmation, your order will be prepared for collection at our premises.',
        'zh'    => '您将被引导以完成付款。付款确认后，您的订单将在我们现场备妥供自提。',
        'bm'    => 'Anda akan dialihkan ke halaman pembayaran. Setelah pembayaran disahkan, pesanan anda akan disediakan untuk pengambilan di premis kami.',
    ],
    [
        'group' => 'cart',
        'key'   => 'cart.secure_checkout',
        'en'    => 'Proceed to Payment',
        'zh'    => '前往结账',
        'bm'    => 'Teruskan ke Pembayaran',
    ],
    [
        'group' => 'cart',
        'key'   => 'cart.trust_secure',
        'en'    => 'Order Processing & Checkout',
        'zh'    => '订单处理与结账',
        'bm'    => 'Pemprosesan Pesanan & Pembayaran',
    ],
    [
        'group' => 'walkin',
        'key'   => 'walkin.proceed_to_payment',
        'en'    => 'Proceed to Payment →',
        'zh'    => '前往结账 →',
        'bm'    => 'Teruskan ke Pembayaran →',
    ],
    [
        'group' => 'common',
        'key'   => 'how_trading_title',
        'en'    => 'TRADING',
        'zh'    => '贸易伙伴',
        'bm'    => 'DAGANGAN',
    ],
    [
        'group' => 'home',
        'key'   => 'how_trading_title',
        'en'    => 'TRADING',
        'zh'    => '贸易伙伴',
        'bm'    => 'DAGANGAN',
    ],
];

echo "1. Updating MySQL records...\n";
foreach ($updates as $u) {
    Translation::updateOrCreate(
        ['group' => $u['group'], 'key' => $u['key']],
        ['text_en' => $u['en'], 'text_zh' => $u['zh'], 'text_bm' => $u['bm']]
    );
    echo "  Updated [{$u['group']}.{$u['key']}]\n";
}

echo "2. Syncing to SQLite...\n";
$sqliteDb->beginTransaction();
$checkStmt = $sqliteDb->prepare("SELECT id FROM translations WHERE `group` = :group AND `key` = :key");
$upStmt = $sqliteDb->prepare("UPDATE translations SET text_en = :en, text_zh = :zh, text_bm = :bm WHERE `group` = :group AND `key` = :key");
$inStmt = $sqliteDb->prepare("INSERT INTO translations (`group`, `key`, text_en, text_zh, text_bm, created_at, updated_at) VALUES (:group, :key, :en, :zh, :bm, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

foreach ($updates as $u) {
    $checkStmt->execute([':group' => $u['group'], ':key' => $u['key']]);
    if ($checkStmt->fetchColumn()) {
        $upStmt->execute([
            ':en'    => $u['en'],
            ':zh'    => $u['zh'],
            ':bm'    => $u['bm'],
            ':group' => $u['group'],
            ':key'   => $u['key'],
        ]);
    } else {
        $inStmt->execute([
            ':group' => $u['group'],
            ':key'   => $u['key'],
            ':en'    => $u['en'],
            ':zh'    => $u['zh'],
            ':bm'    => $u['bm'],
        ]);
    }
}
$sqliteDb->commit();

echo "3. Updating JSON files...\n";
$langFiles = [
    'en' => base_path('lang/en.json'),
    'zh' => base_path('lang/zh.json'),
    'bm' => base_path('lang/bm.json'),
    'ms' => base_path('lang/ms.json'),
];

foreach ($langFiles as $locale => $filePath) {
    if (!file_exists($filePath)) continue;
    $data = json_decode(file_get_contents($filePath), true);
    if (!is_array($data)) continue;
    $locKey = ($locale === 'ms') ? 'bm' : $locale;

    foreach ($updates as $u) {
        $val = $u[$locKey];
        $data[$u['group'] . '.' . $u['key']] = $val;
        $data[$u['key']] = $val;
    }

    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo "  Updated {$filePath}\n";
}

Cache::flush();
Artisan::call('view:clear');
Artisan::call('cache:clear');
Artisan::call('config:clear');

echo "\nDone!\n";
