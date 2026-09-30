<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;

$sqliteDb = new PDO('sqlite:' . database_path('database.sqlite'));
$sqliteDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$walkinUpdates = [
    'packed_appropriately' => [
        'en' => 'Orders are prepared appropriately for self-collection and frozen product handling.',
        'zh' => '订单妥善备好供现场自提，并做好冷冻产品保存处理。',
        'bm' => 'Pesanan disediakan dengan sewajarnya untuk pengambilan sendiri dan pengendalian produk sejuk beku.',
    ],
    'prepared_promptly' => [
        'en' => 'Orders prepared for collection at premises after payment confirmation.',
        'zh' => '付款确认后，订单在现场备妥供自提。',
        'bm' => 'Pesanan disediakan untuk pengambilan di premis selepas pengesahan pembayaran.',
    ],
    'store_short_loc' => [
        'en' => 'SILC Cold-Chain Facility',
        'zh' => 'SILC 冷链设施',
        'bm' => 'Fasiliti Rangkaian Sejuk SILC',
    ],
    'confirm_cash_order' => [
        'en' => 'Confirm Order & Collect In-Store',
        'zh' => '确认订单并到店自提',
        'bm' => 'Sahkan Pesanan & Ambil di Premis',
    ],
    'pay_cash_title' => [
        'en' => 'Cash upon Collection',
        'zh' => '自提时现金付款',
        'bm' => 'Tunai Semasa Pengambilan',
    ],
    'counter_title' => [
        'en' => 'Self-Collection at Premises',
        'zh' => '现场自提',
        'bm' => 'Pengambilan Sendiri di Premis',
    ],
];

foreach ($walkinUpdates as $key => $vals) {
    foreach (['walkin', 'common'] as $grp) {
        foreach ([$key, "$grp.$key"] as $k) {
            Translation::updateOrCreate(
                ['group' => $grp, 'key' => $k],
                ['text_en' => $vals['en'], 'text_zh' => $vals['zh'], 'text_bm' => $vals['bm']]
            );
        }
    }
}

// Sync to SQLite
$all = Translation::all();
$sqliteDb->beginTransaction();
$up = $sqliteDb->prepare("UPDATE translations SET text_en = :en, text_zh = :zh, text_bm = :bm WHERE `group` = :group AND `key` = :key");
$in = $sqliteDb->prepare("INSERT INTO translations (`group`, `key`, text_en, text_zh, text_bm, created_at, updated_at) VALUES (:group, :key, :en, :zh, :bm, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
$check = $sqliteDb->prepare("SELECT id FROM translations WHERE `group` = :group AND `key` = :key");

foreach ($all as $t) {
    $check->execute([':group' => $t->group, ':key' => $t->key]);
    if ($check->fetchColumn()) {
        $up->execute([':en' => $t->text_en, ':zh' => $t->text_zh, ':bm' => $t->text_bm, ':group' => $t->group, ':key' => $t->key]);
    } else {
        $in->execute([':group' => $t->group, ':key' => $t->key, ':en' => $t->text_en, ':zh' => $t->text_zh, ':bm' => $t->text_bm]);
    }
}
$sqliteDb->commit();

Cache::flush();
echo "Walkin phrases cleaned!\n";
