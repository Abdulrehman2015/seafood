<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;
use Illuminate\Support\Facades\DB;

echo "=== CLEANING BRAND EXPOSURES OF COUNTER 2 AND PAYMENT WORDING ===\n\n";

// 1. JSON Lang files cleanup
foreach (['en', 'zh', 'bm', 'ms'] as $loc) {
    $path = base_path("lang/{$loc}.json");
    if (!file_exists($path)) continue;
    $json = json_decode(file_get_contents($path), true) ?: [];

    foreach ($json as $k => $v) {
        if (!is_string($v)) continue;

        // Payment wording neutralization
        if ($k === 'cart.proceed_to_checkout' || $k === 'checkout.proceed_btn' || $k === 'walkin.proceed_to_payment') {
            if ($loc === 'zh') $json[$k] = '前往结账';
            elseif (in_array($loc, ['bm', 'ms'])) $json[$k] = 'Teruskan ke Pembayaran';
            else $json[$k] = 'Proceed to Payment';
        }

        // Clean brand exposures of Counter 2
        if ($loc === 'zh') {
            $v = str_replace(['MST Counter 2即时提货', 'MST Counter 2 即时提货'], '实体店即时到店自提', $v);
            $v = str_replace(['MST Counter 2 提货', 'MST Counter 2提货'], '现场自提', $v);
            $v = str_replace('MST Counter 2极速自提', '极速到店自提', $v);
            $v = str_replace('零售MST Counter 2自提', '实体店自提', $v);
            $v = str_replace('门市 MST Counter 2即刻自提', '实体店即刻自提', $v);
            $v = str_replace('在 MST Counter 2 自提', '到店自提', $v);
            $v = str_replace('（Counter 2）', '', $v);
            $v = str_replace('(Counter 2)', '', $v);
        } elseif (in_array($loc, ['bm', 'ms'])) {
            $v = str_replace(['Pengambilan Pantas di Kaunter 2', 'Pengambilan Pantas Kaunter 2'], 'Pengambilan Pantas di Premis', $v);
            $v = str_replace('Ambil di Kaunter 2', 'Ambil di Premis', $v);
            $v = str_replace('Ambil Di Kaunter 2', 'Ambil Di Premis', $v);
            $v = str_replace('(Kaunter 2)', '(Pengambilan Sendiri)', $v);
            $v = str_replace('di Kaunter 2', 'di Pusat Pengambilan MST', $v);
        } else {
            $v = str_replace(['Instant Counter 2 Pickup', 'Instant Counter 2 Collection'], 'Instant In-Store Self-Collection', $v);
            $v = str_replace('Counter 2 Collection', 'In-Store Self-Collection', $v);
            $v = str_replace('(Counter 2)', '(Self-Collection)', $v);
        }

        $json[$k] = $v;
    }

    file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "✓ Cleaned lang/{$loc}.json\n";
}

// 2. MySQL Translations Table cleanup
$allRows = Translation::all();
foreach ($allRows as $row) {
    $dirty = false;
    foreach (['text_en', 'text_zh', 'text_bm'] as $col) {
        $val = $row->$col;
        if (!$val) continue;

        if ($col === 'text_zh') {
            $newVal = str_replace(['MST Counter 2即时提货', 'MST Counter 2 即时提货'], '实体店即时到店自提', $val);
            $newVal = str_replace(['MST Counter 2 提货', 'MST Counter 2提货'], '现场自提', $newVal);
            $newVal = str_replace('MST Counter 2极速自提', '极速到店自提', $newVal);
            $newVal = str_replace('零售MST Counter 2自提', '实体店自提', $newVal);
            $newVal = str_replace('门市 MST Counter 2即刻自提', '实体店即刻自提', $newVal);
            $newVal = str_replace('在 MST Counter 2 自提', '到店自提', $newVal);
        } elseif ($col === 'text_bm') {
            $newVal = str_replace(['Pengambilan Pantas di Kaunter 2', 'Pengambilan Pantas Kaunter 2'], 'Pengambilan Pantas di Premis', $val);
            $newVal = str_replace('Ambil di Kaunter 2', 'Ambil di Premis', $newVal);
            $newVal = str_replace('Ambil Di Kaunter 2', 'Ambil Di Premis', $newVal);
            $newVal = str_replace('(Kaunter 2)', '(Pengambilan Sendiri)', $newVal);
        } else {
            $newVal = str_replace(['Instant Counter 2 Pickup', 'Instant Counter 2 Collection'], 'Instant In-Store Self-Collection', $val);
            $newVal = str_replace('Counter 2 Collection', 'In-Store Self-Collection', $newVal);
            $newVal = str_replace('(Counter 2)', '(Self-Collection)', $newVal);
        }

        if ($newVal !== $val) {
            $row->$col = $newVal;
            $dirty = true;
        }
    }
    if ($dirty) {
        $row->save();
    }
}
echo "✓ Cleaned MySQL translations table.\n";

// 3. SQLite parity cleanup
$sqlitePath = database_path('database.sqlite');
if (file_exists($sqlitePath)) {
    $pdo = new PDO("sqlite:{$sqlitePath}");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $res = $pdo->query("SELECT id, text_en, text_zh, text_bm FROM translations");
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['id'];
        $en = str_replace(['Instant Counter 2 Pickup', 'Counter 2 Collection', '(Counter 2)'], ['Instant In-Store Self-Collection', 'In-Store Self-Collection', '(Self-Collection)'], $row['text_en'] ?? '');
        $zh = str_replace(['MST Counter 2即时提货', 'MST Counter 2 提货', 'MST Counter 2提货', 'MST Counter 2极速自提', '零售MST Counter 2自提', '门市 MST Counter 2即刻自提', '在 MST Counter 2 自提'], ['实体店即时到店自提', '现场自提', '现场自提', '极速到店自提', '实体店自提', '实体店即刻自提', '到店自提'], $row['text_zh'] ?? '');
        $bm = str_replace(['Pengambilan Pantas di Kaunter 2', 'Pengambilan Pantas Kaunter 2', 'Ambil di Kaunter 2', 'Ambil Di Kaunter 2', '(Kaunter 2)'], ['Pengambilan Pantas di Premis', 'Pengambilan Pantas di Premis', 'Ambil di Premis', 'Ambil Di Premis', '(Pengambilan Sendiri)'], $row['text_bm'] ?? '');

        if ($en !== $row['text_en'] || $zh !== $row['text_zh'] || $bm !== $row['text_bm']) {
            $stmt = $pdo->prepare("UPDATE translations SET text_en = :en, text_zh = :zh, text_bm = :bm WHERE id = :id");
            $stmt->execute([':en' => $en, ':zh' => $zh, ':bm' => $bm, ':id' => $id]);
        }
    }
    echo "✓ Cleaned SQLite translations table.\n";
}

echo "\nDone!\n";
