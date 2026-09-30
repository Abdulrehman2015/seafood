<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

$slugs = ['privacy-policy', 'terms-and-conditions', 'refund-policy', 'shipping-policy', 'cookie-policy'];

echo "=== 1. Updating MySQL policies ===\n";
foreach ($slugs as $slug) {
    $en = file_get_contents("scratch/final_{$slug}_en.html");
    $zh = file_get_contents("scratch/final_{$slug}_zh.html");
    $bm = file_get_contents("scratch/final_{$slug}_bm.html");

    $affected = DB::table('policies')->where('slug', $slug)->update([
        'content'    => $en,
        'content_zh' => $zh,
        'content_bm' => $bm,
        'updated_at' => '2026-09-25 00:00:00',
    ]);
    echo "MySQL policy {$slug}: updated ({$affected} rows affected)\n";
}

echo "\n=== 2. Updating SQLite policies ===\n";
$sqlite = new PDO('sqlite:database/database.sqlite');
$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if cookie-policy exists in SQLite
$stmt = $sqlite->prepare("SELECT COUNT(*) FROM policies WHERE slug = 'cookie-policy'");
$stmt->execute();
$hasCookie = (int) $stmt->fetchColumn();

if (!$hasCookie) {
    echo "Inserting missing cookie-policy into SQLite...\n";
    $mysqlCookie = DB::table('policies')->where('slug', 'cookie-policy')->first();
    $insertStmt = $sqlite->prepare("
        INSERT INTO policies (id, title, slug, content, summary, status, sort_order, title_zh, content_zh, title_bm, content_bm, meta_title, meta_description, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $insertStmt->execute([
        5,
        $mysqlCookie->title ?? 'Cookie Policy',
        'cookie-policy',
        file_get_contents("scratch/final_cookie-policy_en.html"),
        $mysqlCookie->summary ?? 'Manage cookie preferences and privacy settings',
        'published',
        5,
        'Cookie 政策',
        file_get_contents("scratch/final_cookie-policy_zh.html"),
        'Dasar Kuki',
        file_get_contents("scratch/final_cookie-policy_bm.html"),
        'Cookie Policy — MST Import and Export Sdn. Bhd.',
        'Cookie Policy and consent preference management for MST Import and Export Sdn. Bhd.',
        '2026-09-24 07:19:22',
        '2026-09-25 00:00:00',
    ]);
    echo "Inserted cookie-policy into SQLite!\n";
}

foreach ($slugs as $slug) {
    $en = file_get_contents("scratch/final_{$slug}_en.html");
    $zh = file_get_contents("scratch/final_{$slug}_zh.html");
    $bm = file_get_contents("scratch/final_{$slug}_bm.html");

    $uStmt = $sqlite->prepare("UPDATE policies SET content = ?, content_zh = ?, content_bm = ?, updated_at = '2026-09-25 00:00:00' WHERE slug = ?");
    $uStmt->execute([$en, $zh, $bm, $slug]);
    echo "SQLite policy {$slug}: updated\n";
}

echo "\n=== 3. Standardising Settings in MySQL & SQLite ===\n";
$settingsUpdates = [
    'store_name'       => 'MST Import and Export Sdn. Bhd.',
    'site_name'        => 'MST Import and Export Sdn. Bhd.',
    'store_tagline'    => 'Flow with Integrity, Grow with Strength.',
    'store_phone'      => '+60 13-280 0168',
    'store_whatsapp'   => '601112710260',
    'social_whatsapp'  => 'https://wa.me/601112710260',
];

foreach ($settingsUpdates as $k => $v) {
    // MySQL
    DB::table('settings')->updateOrInsert(['key' => $k], ['value' => $v, 'updated_at' => now()]);
    // SQLite
    $check = $sqlite->prepare("SELECT COUNT(*) FROM settings WHERE key = ?");
    $check->execute([$k]);
    if ($check->fetchColumn() > 0) {
        $sqU = $sqlite->prepare("UPDATE settings SET value = ?, updated_at = datetime('now') WHERE key = ?");
        $sqU->execute([$v, $k]);
    } else {
        $sqI = $sqlite->prepare("INSERT INTO settings (key, value, created_at, updated_at) VALUES (?, ?, datetime('now'), datetime('now'))");
        $sqI->execute([$k, $v]);
    }
}
echo "Settings standardized successfully in MySQL and SQLite.\n";

echo "\n=== 4. Clearing Cache ===\n";
Artisan::call('cache:clear');
Artisan::call('view:clear');
echo "Laravel cache and views cleared!\n";
