<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Translation;
use App\Services\TranslationService;

$translations = [
    'contact.hero_location' => [
        'en' => 'Iskandar Puteri, Johor · Serving Customers in Malaysia and Singapore',
        'zh' => '柔佛依斯干达公主城 · 服务马来西亚与新加坡客户',
        'bm' => 'Iskandar Puteri, Johor · Melayani pelanggan di Malaysia dan Singapura',
    ],
    'contact.card3_sub' => [
        'en' => 'Wholesale B2B & RFQ for Custom Sourcing',
        'zh' => '批发 B2B 与定制化采购 RFQ',
        'bm' => 'Borong B2B & RFQ Penyumberan Tersuai',
    ],
    'contact.submit_footer' => [
        'en' => 'Product availability, specifications, pricing and supply arrangements are subject to confirmation. Submitting an RFQ does not guarantee product availability or supply.',
        'zh' => '产品供应情况、规格参数、价格及供应方案均须经最终确认。提交 RFQ 询价不代表已锁定库存或保证供应。',
        'bm' => 'Ketersediaan produk, spesifikasi, harga dan susunan bekalan adalah tertakluk kepada pengesahan. Menghantar RFQ tidak menjamin ketersediaan produk atau jaminan bekalan.',
    ],
    'policy.legal_policy' => [
        'en' => 'Store Policy & Legal Notice',
        'zh' => '商城条款与法律声明',
        'bm' => 'Polisi Kedai & Notis Perundangan',
    ],
    'policy.last_updated' => [
        'en' => 'Last Updated',
        'zh' => '最后更新日期',
        'bm' => 'Kemas Kini Terakhir',
    ],
    'policy.official_document' => [
        'en' => 'Official MST Document',
        'zh' => 'MST 官方合规文件',
        'bm' => 'Dokumen Rasmi MST',
    ],
    'policy.print' => [
        'en' => 'Print',
        'zh' => '打印文档',
        'bm' => 'Cetak Dokumen',
    ],
    'policy.print_title' => [
        'en' => 'Print this policy',
        'zh' => '打印此政策文档',
        'bm' => 'Cetak dokumen polisi ini',
    ],
    'policy.all_policies' => [
        'en' => 'Policies & Guidelines',
        'zh' => '条款与政策导航',
        'bm' => 'Polisi & Garis Panduan',
    ],
    'policy.have_questions' => [
        'en' => 'Have Questions?',
        'zh' => '对条款有任何疑问？',
        'bm' => 'Ada Sebarang Soalan?',
    ],
    'policy.help_desc' => [
        'en' => 'Our support & logistics team is here to assist with any policy inquiries.',
        'zh' => '如有关于条款、订单或商业合作的疑问，请随时联系我们的支持团队。',
        'bm' => 'Pasukan sokongan dan logistik kami sedia membantu dengan sebarang pertanyaan mengenai polisi.',
    ],
    'policy.draft_preview_title' => [
        'en' => 'Draft Mode Preview',
        'zh' => '草稿预览模式',
        'bm' => 'Pratonton Mod Draf',
    ],
    'policy.draft_preview_desc' => [
        'en' => 'This page is currently unpublished and only visible to administrators. It is not visible to public visitors or in the footer.',
        'zh' => '此页面目前未发布，仅管理员可见。公开访客及页脚中不可见。`',
        'bm' => 'Halaman ini belum diterbitkan dan hanya boleh dilihat oleh pentadbir. Ia tidak kelihatan kepada pelawat awam atau di pengaki.',
    ],
    'about.fact_business_val' => [
        'en' => 'Sourcing, Trading & Supply',
        'zh' => '采购 · 贸易 · 供应',
        'bm' => 'Perolehan, Dagangan & Bekalan',
    ],
    'common.fact_business_val' => [
        'en' => 'Sourcing, Trading & Distribution',
        'zh' => '采购 · 贸易 · 分销',
        'bm' => 'Penyumberan, Dagangan & Pengedaran',
    ],
    'home.hero_title' => [
        'en' => 'Premium Frozen Seafood Sourcing, Trading & Distribution',
        'zh' => '优质冷冻海鲜采购 · 贸易 · 分销',
        'bm' => 'Penyumberan, Dagangan & Pengedaran Makanan Laut Beku Berkualiti',
    ],
    'home.stat_supply_dist' => [
        'en' => 'Wholesale & Trading Supply',
        'zh' => '批发与贸易供应',
        'bm' => 'Bekalan Borong & Dagangan',
    ],
    'auth.register_tiers_sub' => [
        'en' => 'Retail · Direct Customer · Wholesale · Trading',
        'zh' => '零售 · 直客 · 批发 · 贸易',
        'bm' => 'Runcit · Pelanggan Terus · Borong · Dagangan',
    ],
    'auth.account_type_trading_badge' => [
        'en' => '🌏 Trading',
        'zh' => '🌏 贸易账户',
        'bm' => '🌏 Dagangan',
    ],
    'auth.trading_account' => [
        'en' => 'Trading Account',
        'zh' => '贸易账户',
        'bm' => 'Akaun Dagangan',
    ],
    'auth.trading_requirements' => [
        'en' => 'Trading Requirements',
        'zh' => '贸易需求',
        'bm' => 'Keperluan Dagangan',
    ],
];

echo "=== Updating JSON Files ===\n";
$jsonPaths = [
    'en' => base_path('lang/en.json'),
    'zh' => base_path('lang/zh.json'),
    'bm' => base_path('lang/bm.json'),
    'ms' => base_path('lang/ms.json'),
];

$jsonContents = [];
foreach ($jsonPaths as $loc => $p) {
    $jsonContents[$loc] = file_exists($p) ? json_decode(file_get_contents($p), true) : [];
}

foreach ($translations as $key => $vals) {
    $parts = explode('.', $key, 2);
    $prefix = $parts[0];
    $subKey = $parts[1] ?? $key;

    $keys = [
        $key,
        $subKey,
        "{$prefix}.{$key}",
    ];

    foreach ($keys as $k) {
        $jsonContents['en'][$k] = $vals['en'];
        $jsonContents['zh'][$k] = $vals['zh'];
        $jsonContents['bm'][$k] = $vals['bm'];
        $jsonContents['ms'][$k] = $vals['bm'];
    }
}

// Global replace of Perolehan Tersuai / Sumber Tersuai with Penyumberan Tersuai in bm.json and ms.json
foreach (['bm', 'ms'] as $loc) {
    foreach ($jsonContents[$loc] as $k => $v) {
        if (is_string($v)) {
            if (str_contains($v, 'Perolehan Tersuai')) {
                $jsonContents[$loc][$k] = str_replace('Perolehan Tersuai', 'Penyumberan Tersuai', $v);
                echo "Replaced Perolehan Tersuai -> Penyumberan Tersuai in {$loc}.json key: {$k}\n";
            }
            if (str_contains($v, 'Sumber Tersuai')) {
                $jsonContents[$loc][$k] = str_replace('Sumber Tersuai', 'Penyumberan Tersuai', $v);
                echo "Replaced Sumber Tersuai -> Penyumberan Tersuai in {$loc}.json key: {$k}\n";
            }
        }
    }
}

// Global replace of 客制化 with 定制化 in zh.json
foreach ($jsonContents['zh'] as $k => $v) {
    if (is_string($v) && str_contains($v, '客制化')) {
        $jsonContents['zh'][$k] = str_replace('客制化', '定制化', $v);
        echo "Replaced 客制化 -> 定制化 in zh.json key: {$k}\n";
    }
}

foreach ($jsonPaths as $loc => $p) {
    file_put_contents($p, json_encode($jsonContents[$loc], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo "Saved {$p}\n";
}

echo "\n=== Updating MySQL Database ===\n";
foreach ($translations as $fullKey => $vals) {
    $group = str_contains($fullKey, '.') ? explode('.', $fullKey, 2)[0] : 'common';
    $subKey = str_contains($fullKey, '.') ? explode('.', $fullKey, 2)[1] : $fullKey;

    $keyVariants = [
        ['group' => $group, 'key' => $subKey],
        ['group' => $group, 'key' => $fullKey],
        ['group' => 'common', 'key' => $fullKey],
        ['group' => $group, 'key' => "{$group}.{$fullKey}"],
    ];

    foreach ($keyVariants as $v) {
        Translation::updateOrCreate(
            ['group' => $v['group'], 'key' => $v['key']],
            [
                'text_en' => $vals['en'],
                'text_zh' => $vals['zh'],
                'text_bm' => $vals['bm'],
            ]
        );
    }
}

// MySQL replace of Perolehan Tersuai -> Penyumberan Tersuai
$bmRows = Translation::where('text_bm', 'LIKE', '%Perolehan Tersuai%')->get();
foreach ($bmRows as $row) {
    $row->text_bm = str_replace('Perolehan Tersuai', 'Penyumberan Tersuai', $row->text_bm);
    $row->save();
    echo "MySQL updated Perolehan Tersuai -> Penyumberan Tersuai for key: {$row->group}.{$row->key}\n";
}

// MySQL replace of Perdagangan -> Dagangan where relevant
$bmTradingRows = Translation::where('text_bm', 'LIKE', '%Perolehan, Perdagangan & Bekalan%')->get();
foreach ($bmTradingRows as $row) {
    $row->text_bm = str_replace('Perolehan, Perdagangan & Bekalan', 'Perolehan, Dagangan & Bekalan', $row->text_bm);
    $row->save();
    echo "MySQL updated Perdagangan -> Dagangan for key: {$row->group}.{$row->key}\n";
}

echo "\n=== Updating SQLite Database ===\n";
$sqlitePath = database_path('database.sqlite');
if (file_exists($sqlitePath)) {
    $pdo = new PDO("sqlite:{$sqlitePath}");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO translations (`group`, `key`, text_en, text_zh, text_bm, created_at, updated_at) 
                           VALUES (:group, :key, :text_en, :text_zh, :text_bm, datetime('now'), datetime('now'))
                           ON CONFLICT(`group`, `key`) DO UPDATE SET text_en = :text_en, text_zh = :text_zh, text_bm = :text_bm, updated_at = datetime('now')");

    foreach ($translations as $fullKey => $vals) {
        $group = str_contains($fullKey, '.') ? explode('.', $fullKey, 2)[0] : 'common';
        $subKey = str_contains($fullKey, '.') ? explode('.', $fullKey, 2)[1] : $fullKey;

        $keyVariants = [
            ['group' => $group, 'key' => $subKey],
            ['group' => $group, 'key' => $fullKey],
            ['group' => 'common', 'key' => $fullKey],
            ['group' => $group, 'key' => "{$group}.{$fullKey}"],
        ];

        foreach ($keyVariants as $v) {
            $stmt->execute([
                ':group'   => $v['group'],
                ':key'     => $v['key'],
                ':text_en' => $vals['en'],
                ':text_zh' => $vals['zh'],
                ':text_bm' => $vals['bm'],
            ]);
        }
    }

    // SQLite replace Perolehan Tersuai -> Penyumberan Tersuai
    $res = $pdo->query("SELECT id, `group`, `key`, text_bm FROM translations WHERE text_bm LIKE '%Perolehan Tersuai%'");
    $updateStmt = $pdo->prepare("UPDATE translations SET text_bm = :text_bm, updated_at = datetime('now') WHERE id = :id");
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $newVal = str_replace('Perolehan Tersuai', 'Penyumberan Tersuai', $row['text_bm']);
        $updateStmt->execute([':text_bm' => $newVal, ':id' => $row['id']]);
        echo "SQLite updated Perolehan Tersuai -> Penyumberan Tersuai for key: {$row['group']}.{$row['key']}\n";
    }

    echo "SQLite database synced.\n";
}

// Clear translation cache
app(TranslationService::class)->clearCache();
echo "\nTranslation cache cleared!\n";
