<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Translation;
use App\Models\Policy;
use App\Services\TranslationService;

echo "=== Syncing Translations and Policies across MySQL & SQLite ===\n";

$databases = ['mysql'];
// Also check sqlite if configured
try {
    $sqliteDb = new PDO('sqlite:' . database_path('database.sqlite'));
    $sqliteDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "SQLite database connected.\n";
} catch (\Throwable $e) {
    $sqliteDb = null;
    echo "SQLite not available: " . $e->getMessage() . "\n";
}

// 1. MySQL Translations Updates
$translations = Translation::all();
$updatedCount = 0;

foreach ($translations as $t) {
    $origZh = $t->text_zh;
    $origBm = $t->text_bm;
    $origEn = $t->text_en;
    $modified = false;

    // Chinese Custom Sourcing
    if ($t->text_zh && str_contains($t->text_zh, '客制化采购')) {
        $t->text_zh = str_replace('客制化采购', '定制化采购', $t->text_zh);
        $modified = true;
    }

    // BM Custom Sourcing
    if ($t->text_bm) {
        $bm = $t->text_bm;
        $bm = str_replace('Perolehan Tersuai', 'Penyumberan Tersuai', $bm);
        $bm = str_replace('perolehan tersuai', 'penyumberan tersuai', $bm);
        $bm = str_replace('PEROLEHAN TERSUAI', 'PENYUMBERAN TERSUAI', $bm);
        $bm = str_replace('PEROLEHAN penyumberan tersuai', 'PENYUMBERAN TERSUAI', $bm);
        $bm = str_replace('Sumber Tersuai', 'Penyumberan Tersuai', $bm);
        $bm = str_replace('sumber tersuai', 'penyumberan tersuai', $bm);

        // BM Trading Terminology
        $bm = str_replace('Akaun Perdagangan', 'Akaun Dagangan', $bm);
        $bm = str_replace('akaun perdagangan', 'akaun dagangan', $bm);
        $bm = str_replace('Daftar Akaun Perdagangan', 'Daftar Akaun Dagangan', $bm);
        $bm = str_replace('daftar akaun perdagangan', 'daftar akaun dagangan', $bm);
        $bm = str_replace('Harga Perdagangan', 'Harga Dagangan', $bm);
        $bm = str_replace('harga perdagangan', 'harga dagangan', $bm);
        $bm = str_replace('Keperluan Perdagangan', 'Keperluan Dagangan', $bm);
        $bm = str_replace('keperluan perdagangan', 'keperluan dagangan', $bm);
        $bm = str_replace('Perdagangan B2B', 'Dagangan B2B', $bm);
        $bm = str_replace('perdagangan B2B', 'dagangan B2B', $bm);
        $bm = str_replace('Tahap Rakan Perdagangan', 'Tahap Rakan Dagangan', $bm);
        $bm = str_replace('Meja Perdagangan B2B', 'Meja Dagangan B2B', $bm);
        $bm = str_replace('Profil Rakan Perdagangan B2B', 'Profil Rakan Dagangan B2B', $bm);
        $bm = str_replace('Penilaian Perdagangan Kontena', 'Penilaian Dagangan Kontena', $bm);
        $bm = str_replace('penilaian perdagangan kontena', 'penilaian dagangan kontena', $bm);
        $bm = str_replace('Kelebihan Perolehan & Perdagangan B2B', 'Kelebihan Penyumberan & Dagangan B2B', $bm);
        $bm = str_replace('Kelebihan Penyumberan & Perdagangan B2B', 'Kelebihan Penyumberan & Dagangan B2B', $bm);
        $bm = str_replace('kelayakan perdagangan', 'kelayakan dagangan', $bm);
        $bm = str_replace('rakan perdagangan', 'rakan dagangan', $bm);
        $bm = str_replace('perdagangan eksport', 'dagangan eksport', $bm);
        $bm = str_replace('Perdagangan & Eksport', 'Dagangan & Eksport', $bm);
        $bm = str_replace('Perdagangan & Bekalan Pukal', 'Dagangan & Bekalan Pukal', $bm);
        $bm = str_replace('Bekalan Perdagangan', 'Bekalan Dagangan', $bm);
        $bm = str_replace('Harga borong dan perdagangan', 'Harga borong dan dagangan', $bm);

        // BM Retail Fulfilment wording (#30)
        $bm = str_replace('membuat pesanan Ambil Sendiri / Kaunter', 'membuat pesanan untuk pengambilan sendiri.', $bm);
        $bm = str_replace('membuat pesanan pengambilan sendiri di MST Kaunter 2.', 'membuat pesanan untuk pengambilan sendiri.', $bm);

        if ($bm !== $t->text_bm) {
            $t->text_bm = $bm;
            $modified = true;
        }
    }

    // Specific key checks
    if ($t->key === 'trading' && $t->text_bm === 'Perdagangan') {
        $t->text_bm = 'Dagangan';
        $modified = true;
    }
    if (str_contains($t->key, 'fulfilment_walkin')) {
        $t->text_bm = 'Pengambilan Sendiri';
        $t->text_zh = '自提';
        $t->text_en = 'Self-Collection';
        $modified = true;
    }
    if (str_contains($t->key, 'field_existing_customer_question')) {
        $t->text_bm = 'Pelanggan Sedia Ada MST: Ya / Tidak';
        $t->text_zh = '现行 MST 客户：是 / 否';
        $t->text_en = 'Existing MST Customer: Yes / No';
        $modified = true;
    }

    if ($modified) {
        $t->save();
        $updatedCount++;
    }
}

echo "MySQL: Updated {$updatedCount} translation records.\n";

// Also update SQLite translations table if available
if ($sqliteDb) {
    $stmt = $sqliteDb->query("SELECT id, `group`, `key`, text_en, text_zh, text_bm FROM translations");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $sqliteUpdated = 0;

    $updateStmt = $sqliteDb->prepare("UPDATE translations SET text_en = :en, text_zh = :zh, text_bm = :bm WHERE id = :id");

    foreach ($rows as $r) {
        $mod = false;
        $zh = $r['text_zh'];
        $bm = $r['text_bm'];
        $en = $r['text_en'];

        if ($zh && str_contains($zh, '客制化采购')) {
            $zh = str_replace('客制化采购', '定制化采购', $zh);
            $mod = true;
        }

        if ($bm) {
            $orig = $bm;
            $bm = str_replace('Perolehan Tersuai', 'Penyumberan Tersuai', $bm);
            $bm = str_replace('perolehan tersuai', 'penyumberan tersuai', $bm);
            $bm = str_replace('PEROLEHAN TERSUAI', 'PENYUMBERAN TERSUAI', $bm);
            $bm = str_replace('PEROLEHAN penyumberan tersuai', 'PENYUMBERAN TERSUAI', $bm);
            $bm = str_replace('Sumber Tersuai', 'Penyumberan Tersuai', $bm);
            $bm = str_replace('sumber tersuai', 'penyumberan tersuai', $bm);

            $bm = str_replace('Akaun Perdagangan', 'Akaun Dagangan', $bm);
            $bm = str_replace('akaun perdagangan', 'akaun dagangan', $bm);
            $bm = str_replace('Daftar Akaun Perdagangan', 'Daftar Akaun Dagangan', $bm);
            $bm = str_replace('daftar akaun perdagangan', 'daftar akaun dagangan', $bm);
            $bm = str_replace('Harga Perdagangan', 'Harga Dagangan', $bm);
            $bm = str_replace('harga perdagangan', 'harga dagangan', $bm);
            $bm = str_replace('Keperluan Perdagangan', 'Keperluan Dagangan', $bm);
            $bm = str_replace('keperluan perdagangan', 'keperluan dagangan', $bm);
            $bm = str_replace('Perdagangan B2B', 'Dagangan B2B', $bm);
            $bm = str_replace('perdagangan B2B', 'dagangan B2B', $bm);
            $bm = str_replace('Tahap Rakan Perdagangan', 'Tahap Rakan Dagangan', $bm);
            $bm = str_replace('Meja Perdagangan B2B', 'Meja Dagangan B2B', $bm);
            $bm = str_replace('Profil Rakan Perdagangan B2B', 'Profil Rakan Dagangan B2B', $bm);
            $bm = str_replace('Penilaian Perdagangan Kontena', 'Penilaian Dagangan Kontena', $bm);
            $bm = str_replace('penilaian perdagangan kontena', 'penilaian dagangan kontena', $bm);
            $bm = str_replace('Kelebihan Perolehan & Perdagangan B2B', 'Kelebihan Penyumberan & Dagangan B2B', $bm);
            $bm = str_replace('Kelebihan Penyumberan & Perdagangan B2B', 'Kelebihan Penyumberan & Dagangan B2B', $bm);
            $bm = str_replace('kelayakan perdagangan', 'kelayakan dagangan', $bm);
            $bm = str_replace('rakan perdagangan', 'rakan dagangan', $bm);
            $bm = str_replace('perdagangan eksport', 'dagangan eksport', $bm);
            $bm = str_replace('Perdagangan & Eksport', 'Dagangan & Eksport', $bm);
            $bm = str_replace('Perdagangan & Bekalan Pukal', 'Dagangan & Bekalan Pukal', $bm);
            $bm = str_replace('Bekalan Perdagangan', 'Bekalan Dagangan', $bm);
            $bm = str_replace('Harga borong dan perdagangan', 'Harga borong dan dagangan', $bm);

            $bm = str_replace('membuat pesanan Ambil Sendiri / Kaunter', 'membuat pesanan untuk pengambilan sendiri.', $bm);
            $bm = str_replace('membuat pesanan pengambilan sendiri di MST Kaunter 2.', 'membuat pesanan untuk pengambilan sendiri.', $bm);

            if ($bm !== $orig) {
                $mod = true;
            }
        }

        if ($r['key'] === 'trading' && $bm === 'Perdagangan') {
            $bm = 'Dagangan';
            $mod = true;
        }
        if (str_contains($r['key'], 'fulfilment_walkin')) {
            $bm = 'Pengambilan Sendiri';
            $zh = '自提';
            $en = 'Self-Collection';
            $mod = true;
        }
        if (str_contains($r['key'], 'field_existing_customer_question')) {
            $bm = 'Pelanggan Sedia Ada MST: Ya / Tidak';
            $zh = '现行 MST 客户：是 / 否';
            $en = 'Existing MST Customer: Yes / No';
            $mod = true;
        }

        if ($mod) {
            $updateStmt->execute([
                ':en' => $en,
                ':zh' => $zh,
                ':bm' => $bm,
                ':id' => $r['id'],
            ]);
            $sqliteUpdated++;
        }
    }
    echo "SQLite: Updated {$sqliteUpdated} translation records.\n";
}

// 2. Policies Updates in MySQL
$policies = Policy::all();
foreach ($policies as $p) {
    $bm = $p->content_bm ?? '';
    $mod = false;
    if (str_contains($bm, 'Perolehan Tersuai') || str_contains($bm, 'perolehan tersuai')) {
        $bm = str_replace('Perolehan Tersuai', 'Penyumberan Tersuai', $bm);
        $bm = str_replace('perolehan tersuai', 'penyumberan tersuai', $bm);
        $mod = true;
    }
    if ($p->slug === 'terms-and-conditions') {
        if (str_contains($bm, 'Perdagangan / Import & Pengedaran')) {
            $bm = str_replace('Perdagangan / Import & Pengedaran', 'Dagangan / Import & Pengedaran', $bm);
            $mod = true;
        }
        if (str_contains($bm, 'perkhidmatan perdagangan yang berkaitan')) {
            $bm = str_replace('perkhidmatan perdagangan yang berkaitan', 'perkhidmatan dagangan yang berkaitan', $bm);
            $mod = true;
        }
    }
    if ($mod) {
        $p->content_bm = $bm;
        $p->save();
        echo "MySQL Policy updated: {$p->slug}\n";
    }
}

// 3. Policies Updates in SQLite
if ($sqliteDb) {
    $stmt = $sqliteDb->query("SELECT id, slug, content_bm FROM policies");
    $polRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $upPol = $sqliteDb->prepare("UPDATE policies SET content_bm = :content_bm WHERE id = :id");

    foreach ($polRows as $pr) {
        $bm = $pr['content_bm'] ?? '';
        $mod = false;
        if (str_contains($bm, 'Perolehan Tersuai') || str_contains($bm, 'perolehan tersuai')) {
            $bm = str_replace('Perolehan Tersuai', 'Penyumberan Tersuai', $bm);
            $bm = str_replace('perolehan tersuai', 'penyumberan tersuai', $bm);
            $mod = true;
        }
        if ($pr['slug'] === 'terms-and-conditions') {
            if (str_contains($bm, 'Perdagangan / Import & Pengedaran')) {
                $bm = str_replace('Perdagangan / Import & Pengedaran', 'Dagangan / Import & Pengedaran', $bm);
                $mod = true;
            }
            if (str_contains($bm, 'perkhidmatan perdagangan yang berkaitan')) {
                $bm = str_replace('perkhidmatan perdagangan yang berkaitan', 'perkhidmatan dagangan yang berkaitan', $bm);
                $mod = true;
            }
        }
        if ($mod) {
            $upPol->execute([':content_bm' => $bm, ':id' => $pr['id']]);
            echo "SQLite Policy updated: {$pr['slug']}\n";
        }
    }
}

// Clear translation cache
Cache::forget(TranslationService::CACHE_KEY);
echo "Translation cache cleared.\n";
echo "Sync completed successfully.\n";
