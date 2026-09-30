<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Translation;

echo "=== FINAL GLOBAL TERM SEARCH ===\n\n";

// 1. Check Perdagangan in BM translations
$bmPerdagangan = Translation::where('text_bm', 'LIKE', '%Perdagangan%')->get();
echo "1. 'Perdagangan' in MySQL text_bm: " . count($bmPerdagangan) . "\n";
foreach ($bmPerdagangan as $r) {
    echo "   - [{$r->group}.{$r->key}] '{$r->text_bm}'\n";
}

// 2. Check Keperluan Perdagangan in BM translations
$bmKeperluanPerdagangan = Translation::where('text_bm', 'LIKE', '%Keperluan Perdagangan%')->get();
echo "2. 'Keperluan Perdagangan' in MySQL text_bm: " . count($bmKeperluanPerdagangan) . "\n";
foreach ($bmKeperluanPerdagangan as $r) {
    echo "   - [{$r->group}.{$r->key}] '{$r->text_bm}'\n";
}

// 3. Check Perolehan Tersuai in BM translations
$bmPerolehanTersuai = Translation::where('text_bm', 'LIKE', '%Perolehan Tersuai%')->get();
echo "3. 'Perolehan Tersuai' in MySQL text_bm: " . count($bmPerolehanTersuai) . "\n";
foreach ($bmPerolehanTersuai as $r) {
    echo "   - [{$r->group}.{$r->key}] '{$r->text_bm}'\n";
}

// 4. Check Semua Kategori / Perolehan Tersuai
$bmSemuaKat = Translation::where('text_bm', 'LIKE', '%Semua Kategori /%')->get();
echo "4. 'Semua Kategori /...' in MySQL text_bm: " . count($bmSemuaKat) . "\n";
foreach ($bmSemuaKat as $r) {
    echo "   - [{$r->group}.{$r->key}] '{$r->text_bm}'\n";
}

// 5. Check Pembayaran Selamat in BM translations
$bmPembayaranSelamat = Translation::where('text_bm', 'LIKE', '%Pembayaran Selamat%')->get();
echo "5. 'Pembayaran Selamat' in MySQL text_bm: " . count($bmPembayaranSelamat) . "\n";
foreach ($bmPembayaranSelamat as $r) {
    echo "   - [{$r->group}.{$r->key}] '{$r->text_bm}'\n";
}

// 6. Check Secure Payment in EN translations
$enSecurePayment = Translation::where('text_en', 'LIKE', '%Secure Payment%')->get();
echo "6. 'Secure Payment' in MySQL text_en: " . count($enSecurePayment) . "\n";
foreach ($enSecurePayment as $r) {
    echo "   - [{$r->group}.{$r->key}] '{$r->text_en}'\n";
}

// 7. Check Secure Checkout in EN translations
$enSecureCheckout = Translation::where('text_en', 'LIKE', '%Secure Checkout%')->get();
echo "7. 'Secure Checkout' in MySQL text_en: " . count($enSecureCheckout) . "\n";
foreach ($enSecureCheckout as $r) {
    echo "   - [{$r->group}.{$r->key}] '{$r->text_en}'\n";
}

// 8. Check cart.title in BM
$cartTitle = Translation::where('group', 'cart')->where('key', 'title')->first();
echo "8. 'cart.title' BM translation: '{$cartTitle->text_bm}'\n";

// 9. Check SQLite translations
$sqliteDb = new PDO('sqlite:' . database_path('database.sqlite'));
$cntOldSqlite = $sqliteDb->query("SELECT count(*) FROM translations WHERE text_bm LIKE '%Perdagangan%' OR text_bm LIKE '%Perolehan Tersuai%' OR text_bm LIKE '%Semua Kategori /%'")->fetchColumn();
echo "9. SQLite old terms count: {$cntOldSqlite}\n";

echo "\nGlobal search complete!\n";
