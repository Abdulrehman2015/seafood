<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Translation;
use App\Models\Policy;
use Illuminate\Support\Facades\Cache;

echo "=== SYNCING ITEMS #31-#40 ACROSS MYSQL, SQLITE & JSON FILES ===\n\n";

$sqliteDb = new PDO('sqlite:' . database_path('database.sqlite'));
$sqliteDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 1. Array of exact translations to insert or update
$keysToSync = [
    // #31 Marketing Consent
    [
        'group' => 'auth',
        'key'   => 'marketing_consent_title',
        'en'    => 'Marketing Updates & Communications (Optional)',
        'zh'    => '营销资讯与通知推送（可选）',
        'bm'    => 'Maklumat & Komunikasi Pemasaran (Pilihan)',
    ],
    [
        'group' => 'auth',
        'key'   => 'consent_whatsapp',
        'en'    => 'I agree to receive MST updates via WhatsApp.',
        'zh'    => '我同意通过 WhatsApp 接收 MST 的最新动态。',
        'bm'    => 'Saya bersetuju untuk menerima maklumat terkini MST melalui WhatsApp.',
    ],
    [
        'group' => 'auth',
        'key'   => 'consent_email',
        'en'    => 'I agree to receive MST updates via Email.',
        'zh'    => '我同意通过电子邮箱接收 MST 的最新动态。',
        'bm'    => 'Saya bersetuju untuk menerima maklumat terkini MST melalui e-mel.',
    ],
    [
        'group' => 'auth',
        'key'   => 'consent_whatsapp_exact',
        'en'    => 'I agree to receive MST updates via WhatsApp.',
        'zh'    => '我同意通过 WhatsApp 接收 MST 的最新动态。',
        'bm'    => 'Saya bersetuju untuk menerima maklumat terkini MST melalui WhatsApp.',
    ],
    [
        'group' => 'auth',
        'key'   => 'consent_email_exact',
        'en'    => 'I agree to receive MST updates via Email.',
        'zh'    => '我同意通过电子邮箱接收 MST 的最新动态。',
        'bm'    => 'Saya bersetuju untuk menerima maklumat terkini MST melalui e-mel.',
    ],

    // #32 Wholesale English Residues
    [
        'group' => 'auth',
        'key'   => 'field_company_ssm_required',
        'en'    => 'Company Registration No. / SSM / UEN / Other',
        'zh'    => '公司注册编号 / SSM / UEN / 其他',
        'bm'    => 'No. Pendaftaran Syarikat / SSM / UEN / Lain-lain',
    ],
    [
        'group' => 'auth',
        'key'   => 'section_business_address',
        'en'    => '2. Business Address',
        'zh'    => '2. 企业地址',
        'bm'    => '2. Alamat Syarikat',
    ],
    [
        'group' => 'auth',
        'key'   => 'field_business_address',
        'en'    => 'Business Address',
        'zh'    => '企业地址',
        'bm'    => 'Alamat Syarikat',
    ],
    [
        'group' => 'auth',
        'key'   => 'field_business_country',
        'en'    => 'Country (where applicable)',
        'zh'    => '国家/地区（如适用）',
        'bm'    => 'Negara (jika berkenaan)',
    ],
    [
        'group' => 'auth',
        'key'   => 'same_as_business_address',
        'en'    => 'Delivery address is the same as Business Address',
        'zh'    => '配送地址与企业地址相同',
        'bm'    => 'Alamat penghantaran adalah sama dengan Alamat Syarikat',
    ],

    // #33 Trading Registration & Dagangan
    [
        'group' => 'auth',
        'key'   => 'section_target_market',
        'en'    => '3. Target Market & Destination',
        'zh'    => '3. 目标市场与目的地',
        'bm'    => '3. Pasaran Sasaran & Destinasi',
    ],
    [
        'group' => 'auth',
        'key'   => 'section_trading_requirements',
        'en'    => '4. Trading Requirements',
        'zh'    => '4. 贸易需求',
        'bm'    => '4. Keperluan Dagangan',
    ],
    [
        'group' => 'auth',
        'key'   => 'section_product_requirements',
        'en'    => '6. Product Requirements',
        'zh'    => '6. 产品需求',
        'bm'    => '6. Keperluan Produk',
    ],
    [
        'group' => 'auth',
        'key'   => 'section_additional_requirements',
        'en'    => '7. Additional Requirements',
        'zh'    => '7. 附加要求',
        'bm'    => '7. Keperluan Tambahan',
    ],
    [
        'group' => 'auth',
        'key'   => 'section_product_requirements_title',
        'en'    => 'Product Requirements',
        'zh'    => '产品需求',
        'bm'    => 'Keperluan Produk',
    ],
    [
        'group' => 'auth',
        'key'   => 'section_additional_requirements_title',
        'en'    => 'Additional Requirements',
        'zh'    => '附加要求',
        'bm'    => 'Keperluan Tambahan',
    ],
    [
        'group' => 'auth',
        'key'   => 'treq_import',
        'en'    => 'Import',
        'zh'    => '进口',
        'bm'    => 'Import',
    ],
    [
        'group' => 'auth',
        'key'   => 'treq_export',
        'en'    => 'Export',
        'zh'    => '出口',
        'bm'    => 'Eksport',
    ],
    [
        'group' => 'auth',
        'key'   => 'treq_distribution',
        'en'    => 'Distribution',
        'zh'    => '分销',
        'bm'    => 'Pengedaran',
    ],
    [
        'group' => 'auth',
        'key'   => 'treq_bulk_purchasing',
        'en'    => 'Bulk Purchasing',
        'zh'    => '批量采购',
        'bm'    => 'Pembelian Pukal',
    ],
    [
        'group' => 'auth',
        'key'   => 'treq_customised_sourcing',
        'en'    => 'Customised Sourcing',
        'zh'    => '定制化采购',
        'bm'    => 'Penyumberan Tersuai',
    ],
    [
        'group' => 'auth',
        'key'   => 'treq_other',
        'en'    => 'Other',
        'zh'    => '其他',
        'bm'    => 'Lain-lain',
    ],

    // #35 BM Product Breadcrumbs & Hero
    [
        'group' => 'shop',
        'key'   => 'breadcrumb_products_sourcing',
        'en'    => 'Products & Sourcing',
        'zh'    => '产品与采购',
        'bm'    => 'Produk & Penyumberan',
    ],
    [
        'group' => 'common',
        'key'   => 'breadcrumb_products_sourcing',
        'en'    => 'Products & Sourcing',
        'zh'    => '产品与采购',
        'bm'    => 'Produk & Penyumberan',
    ],
    [
        'group' => 'shop',
        'key'   => 'hero_title',
        'en'    => 'Products & Sourcing',
        'zh'    => '产品与采购',
        'bm'    => 'Produk & Penyumberan',
    ],
    [
        'group' => 'shop',
        'key'   => 'meta_title',
        'en'    => 'Products & Sourcing — MST Import and Export Sdn. Bhd.',
        'zh'    => '产品与采购 — MST Import and Export Sdn. Bhd.',
        'bm'    => 'Produk & Penyumberan — MST Import and Export Sdn. Bhd.',
    ],

    // #36 Category Filters: strictly 'Semua Kategori' / 'All Categories'
    [
        'group' => 'shop',
        'key'   => 'all_categories',
        'en'    => 'All Categories',
        'zh'    => '全部分类',
        'bm'    => 'Semua Kategori',
    ],
    [
        'group' => 'common',
        'key'   => 'all_categories',
        'en'    => 'All Categories',
        'zh'    => '全部分类',
        'bm'    => 'Semua Kategori',
    ],
    [
        'group' => 'contact',
        'key'   => 'all_categories',
        'en'    => 'All Categories',
        'zh'    => '全部分类',
        'bm'    => 'Semua Kategori',
    ],

    // #37 BM Walk-in Page
    [
        'group' => 'walkin',
        'key'   => 'step_indicator',
        'en'    => '4 Easy Steps',
        'zh'    => '4 步流程',
        'bm'    => '4 Langkah Mudah',
    ],
    [
        'group' => 'walkin',
        'key'   => 'service_desc',
        'en'    => 'Walk-in Express is dedicated to retail purchases and self-collection on premises.',
        'zh'    => 'Walk-in Express 专用于零售选购与现场自提。',
        'bm'    => 'Walk-in Express dikhususkan untuk pembelian runcit dan pengambilan sendiri di premis.',
    ],
    [
        'group' => 'walkin',
        'key'   => 'counter_desc',
        'en'    => 'Orders are properly prepared for self-collection and frozen product handling.',
        'zh'    => '订单妥善备好供现场自提，并做好冷冻产品保存处理。',
        'bm'    => 'Pesanan disediakan dengan sewajarnya untuk pengambilan sendiri dan pengendalian produk sejuk beku.',
    ],
    [
        'group' => 'walkin',
        'key'   => 'step_4_desc',
        'en'    => 'Collect your prepared order at our premises.',
        'zh'    => '在我们的现场提取已备好的订单。',
        'bm'    => 'Ambil pesanan yang telah disediakan di premis kami.',
    ],
    [
        'group' => 'walkin',
        'key'   => 'step_3_desc',
        'en'    => 'Complete payment conveniently on your phone.',
        'zh'    => '通过手机轻松完成付款。',
        'bm'    => 'Lengkapkan pembayaran melalui telefon anda.',
    ],
    [
        'group' => 'walkin',
        'key'   => 'express_pickup_tag',
        'en'    => 'Self-collection only · No delivery',
        'zh'    => '仅限现场自提 · 不设配送',
        'bm'    => 'Pengambilan sendiri sahaja · Tiada penghantaran',
    ],
    [
        'group' => 'walkin',
        'key'   => 'wholesale_note',
        'en'    => 'Self-collection only · No delivery. Orders are prepared for collection at our SILC facility after payment confirmation.',
        'zh'    => '仅限自提 · 不设配送。付款确认后，订单将在我们 SILC 设施备妥供自提。',
        'bm'    => 'Pengambilan sendiri sahaja · Tiada penghantaran. Pesanan disediakan untuk diambil di premis kami selepas pengesahan pembayaran.',
    ],
    [
        'group' => 'shop',
        'key'   => 'walkin_collection_title',
        'en'    => 'Walk-in / Self-Collection',
        'zh'    => '现场选购 / 到店自提',
        'bm'    => 'Walk-in / Pengambilan Sendiri',
    ],
    [
        'group' => 'shop',
        'key'   => 'walkin_collection_desc',
        'en'    => 'Collect your confirmed order directly from MST SILC Cold-Chain Facility. Self-collection only · No delivery threshold applies.',
        'zh'    => '直接从 MST SILC 冷链设施自提已确认订单。仅限现场自提 · 不适用配送起送金额要求。',
        'bm'    => 'Ambil pesanan yang disahkan terus dari Fasiliti Rangkaian Sejuk MST SILC. Pengambilan sendiri sahaja · Tiada ambang rujukan penghantaran dikenakan.',
    ],

    // #38 Walk-in Payment Neutral Wording
    [
        'group' => 'walkin',
        'key'   => 'proceed_to_payment',
        'en'    => 'Proceed to Payment →',
        'zh'    => '前往结账 →',
        'bm'    => 'Teruskan ke Pembayaran →',
    ],
    [
        'group' => 'common',
        'key'   => 'proceed_to_payment',
        'en'    => 'Proceed to Payment →',
        'zh'    => '前往结账 →',
        'bm'    => 'Teruskan ke Pembayaran →',
    ],
    [
        'group' => 'walkin',
        'key'   => 'stripe_info_title',
        'en'    => 'Phone Payment:',
        'zh'    => '手机支付：',
        'bm'    => 'Pembayaran Melalui Telefon:',
    ],

    // #39 BM Cart Breadcrumb
    [
        'group' => 'cart',
        'key'   => 'title',
        'en'    => 'Shopping Cart',
        'zh'    => '您的购物车',
        'bm'    => 'Troli Beli-belah',
    ],
    [
        'group' => 'cart',
        'key'   => 'breadcrumb_cart',
        'en'    => 'Shopping Cart',
        'zh'    => '您的购物车',
        'bm'    => 'Troli Beli-belah',
    ],

    // #40 Cart & Checkout Neutral Security Wording
    [
        'group' => 'cart',
        'key'   => 'secure_checkout',
        'en'    => 'Proceed to Payment',
        'zh'    => '前往结账',
        'bm'    => 'Teruskan ke Pembayaran',
    ],
    [
        'group' => 'cart',
        'key'   => 'checkout_btn',
        'en'    => 'Proceed to Payment',
        'zh'    => '前往结账',
        'bm'    => 'Teruskan ke Pembayaran',
    ],
    [
        'group' => 'cart',
        'key'   => 'trust_secure',
        'en'    => 'Order Processing & Checkout',
        'zh'    => '订单处理与结账',
        'bm'    => 'Pemprosesan Pesanan & Pembayaran',
    ],
    [
        'group' => 'cart',
        'key'   => 'trust_encrypted',
        'en'    => 'Verified Order Processing',
        'zh'    => '订单核验与处理',
        'bm'    => 'Pemprosesan Pesanan',
    ],
    [
        'group' => 'checkout',
        'key'   => 'encrypted_checkout_badge',
        'en'    => 'Order Verification & Payment',
        'zh'    => '订单确认与结账',
        'bm'    => 'Pengesahan Pesanan & Pembayaran',
    ],
    [
        'group' => 'checkout',
        'key'   => 'place_order',
        'en'    => 'Proceed to Payment',
        'zh'    => '前往结账',
        'bm'    => 'Teruskan ke Pembayaran',
    ],
    [
        'group' => 'checkout',
        'key'   => 'trust_verified',
        'en'    => 'Verified Order',
        'zh'    => '订单已核验',
        'bm'    => 'Pesanan Disahkan',
    ],
];

// Insert or update in MySQL
echo "1. Syncing to MySQL translations table...\n";
foreach ($keysToSync as $item) {
    Translation::updateOrCreate(
        ['group' => $item['group'], 'key' => $item['key']],
        ['text_en' => $item['en'], 'text_zh' => $item['zh'], 'text_bm' => $item['bm']]
    );
    // Also update flat key if group is common, shop, auth, etc.
    Translation::updateOrCreate(
        ['group' => 'common', 'key' => $item['key']],
        ['text_en' => $item['en'], 'text_zh' => $item['zh'], 'text_bm' => $item['bm']]
    );
}

// 2. Global cleanup of old terms in MySQL translations table
echo "2. Cleaning up any remaining old terms in MySQL translations...\n";
$all = Translation::all();
foreach ($all as $t) {
    $changed = false;
    if ($t->text_bm) {
        $bm = $t->text_bm;
        // Clean Perdagangan -> Dagangan
        if (str_contains($bm, 'Perdagangan') || str_contains($bm, 'perdagangan')) {
            $bm = str_replace('Keperluan Perdagangan', 'Keperluan Dagangan', $bm);
            $bm = str_replace('keperluan perdagangan', 'keperluan dagangan', $bm);
            $bm = str_replace('Akaun Perdagangan', 'Akaun Dagangan', $bm);
            $bm = str_replace('akaun perdagangan', 'akaun dagangan', $bm);
            $bm = str_replace('Perdagangan B2B', 'Dagangan B2B', $bm);
            $bm = str_replace('rakan perdagangan', 'rakan dagangan', $bm);
            $bm = str_replace('Rakan Perdagangan', 'Rakan Dagangan', $bm);
            $bm = str_replace('kelayakan perdagangan', 'kelayakan dagangan', $bm);
            $bm = str_replace('perdagangan eksport', 'dagangan eksport', $bm);
            $bm = str_replace('Perdagangan', 'Dagangan', $bm);
            $bm = str_replace('perdagangan', 'dagangan', $bm);
        }
        // Clean Perolehan Tersuai -> Penyumberan Tersuai
        if (str_contains($bm, 'Perolehan Tersuai') || str_contains($bm, 'perolehan tersuai')) {
            $bm = str_replace('Perolehan Tersuai', 'Penyumberan Tersuai', $bm);
            $bm = str_replace('perolehan tersuai', 'penyumberan tersuai', $bm);
            $bm = str_replace('PEROLEHAN TERSUAI', 'PENYUMBERAN TERSUAI', $bm);
        }
        // Clean Produk & Perolehan -> Produk & Penyumberan
        if (str_contains($bm, 'Produk & Perolehan')) {
            $bm = str_replace('Produk & Perolehan', 'Produk & Penyumberan', $bm);
        }
        // Clean Semua Kategori / Penyumberan Tersuai -> Semua Kategori
        if (str_contains($bm, 'Semua Kategori / Penyumberan Tersuai') || str_contains($bm, 'Semua Kategori / Perolehan Tersuai')) {
            $bm = str_replace('Semua Kategori / Penyumberan Tersuai', 'Semua Kategori', $bm);
            $bm = str_replace('Semua Kategori / Perolehan Tersuai', 'Semua Kategori', $bm);
        }
        // Clean Walk-in Express in cart.title
        if ($t->group === 'cart' && $t->key === 'title' && $bm === 'Walk-in Express') {
            $bm = 'Troli Beli-belah';
        }
        // Clean Walk-in Counter 2 repeated exposure
        if (str_contains($bm, 'Pesanan dibungkus dengan sesuai untuk pengambilan dan pengangkutan.')) {
            $bm = str_replace('Pesanan dibungkus dengan sesuai untuk pengambilan dan pengangkutan.', 'Pesanan disediakan dengan sewajarnya untuk pengambilan sendiri dan pengendalian produk sejuk beku.', $bm);
        }
        if (str_contains($bm, 'Walk-in Express dikhususkan untuk pembelian runcit / individu dan pengambilan di Kaunter 2.')) {
            $bm = str_replace('Walk-in Express dikhususkan untuk pembelian runcit / individu dan pengambilan di Kaunter 2.', 'Walk-in Express dikhususkan untuk pembelian runcit dan pengambilan sendiri di premis.', $bm);
        }
        if (str_contains($bm, 'Ambil pesanan yang telah disediakan di Kaunter 2.')) {
            $bm = str_replace('Ambil pesanan yang telah disediakan di Kaunter 2.', 'Ambil pesanan yang telah disediakan di premis kami.', $bm);
        }
        if ($bm !== $t->text_bm) {
            $t->text_bm = $bm;
            $changed = true;
        }
    }
    if ($changed) {
        $t->save();
    }
}

// 3. Sync all MySQL translations to SQLite
echo "3. Syncing all records from MySQL to SQLite translations table...\n";
$allMySql = Translation::all();
$sqliteDb->beginTransaction();
$checkStmt = $sqliteDb->prepare("SELECT id FROM translations WHERE `group` = :group AND `key` = :key");
$updateStmt = $sqliteDb->prepare("UPDATE translations SET text_en = :en, text_zh = :zh, text_bm = :bm, updated_at = :ua WHERE `group` = :group AND `key` = :key");
$insertStmt = $sqliteDb->prepare("INSERT INTO translations (id, `group`, `key`, text_en, text_zh, text_bm, created_at, updated_at) VALUES (:id, :group, :key, :en, :zh, :bm, :ca, :ua)");

foreach ($allMySql as $t) {
    $checkStmt->execute([':group' => $t->group, ':key' => $t->key]);
    $exists = $checkStmt->fetchColumn();
    if ($exists) {
        $updateStmt->execute([
            ':en'    => $t->text_en,
            ':zh'    => $t->text_zh,
            ':bm'    => $t->text_bm,
            ':ua'    => $t->updated_at,
            ':group' => $t->group,
            ':key'   => $t->key,
        ]);
    } else {
        $insertStmt->execute([
            ':id'    => $t->id,
            ':group' => $t->group,
            ':key'   => $t->key,
            ':en'    => $t->text_en,
            ':zh'    => $t->text_zh,
            ':bm'    => $t->text_bm,
            ':ca'    => $t->created_at,
            ':ua'    => $t->updated_at,
        ]);
    }
}
$sqliteDb->commit();

// 4. Update Policies table in MySQL and SQLite (#34 Cookie Settings)
echo "4. Updating policy links for Cookie Settings (#34) in MySQL and SQLite...\n";
$policies = Policy::all();
foreach ($policies as $p) {
    $pChanged = false;
    foreach (['en', 'zh', 'bm'] as $lang) {
        $content = ($lang === 'en') ? $p->content : $p->{"content_$lang"};
        if ($content && (str_contains($content, 'href="#cookie-settings"') || str_contains($content, "href='#cookie-settings'"))) {
            // Replace with button triggering global preference center without hash
            $newContent = str_replace(
                'href="#cookie-settings"',
                'type="button"',
                $content
            );
            $newContent = str_replace(
                '<a type="button" class="js-open-cookie-settings" data-cookie-settings="true"',
                '<button type="button" class="policy-cookie-btn js-open-cookie-settings" data-cookie-settings="true"',
                $newContent
            );
            $newContent = preg_replace(
                '/<a\s+type="button"\s+class="([^"]*)"\s+data-cookie-settings="true"\s+onclick="([^"]*)">([^<]+)<\/a>/',
                '<button type="button" class="$1 policy-cookie-btn" data-cookie-settings="true" onclick="$2" style="background:none;border:none;padding:0;color:#2563eb;text-decoration:underline;cursor:pointer;font:inherit;">$3</button>',
                $newContent
            );
            if ($lang === 'en') {
                $p->content = $newContent;
            } else {
                $p->{"content_$lang"} = $newContent;
            }
            $pChanged = true;
        }
    }
    if ($pChanged) {
        $p->save();
        // Also sync to SQLite
        $upSqlite = $sqliteDb->prepare("UPDATE policies SET content = :en, content_zh = :zh, content_bm = :bm WHERE id = :id");
        $upSqlite->execute([
            ':en' => $p->content,
            ':zh' => $p->content_zh,
            ':bm' => $p->content_bm,
            ':id' => $p->id,
        ]);
        echo "  Updated policy: {$p->slug}\n";
    }
}

// 5. Update JSON files (lang/en.json, lang/zh.json, lang/bm.json, lang/ms.json)
echo "5. Updating JSON translation files...\n";
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

    foreach ($keysToSync as $item) {
        $val = $item[$locKey];
        $data[$item['group'] . '.' . $item['key']] = $val;
        $data[$item['key']] = $val;
    }

    // Direct cleanups in JSON
    if ($locKey === 'bm') {
        foreach ($data as $k => $v) {
            if (is_string($v)) {
                $nv = $v;
                if (str_contains($nv, 'Keperluan Perdagangan')) $nv = str_replace('Keperluan Perdagangan', 'Keperluan Dagangan', $nv);
                if (str_contains($nv, 'keperluan perdagangan')) $nv = str_replace('keperluan perdagangan', 'keperluan dagangan', $nv);
                if (str_contains($nv, 'Akaun Perdagangan')) $nv = str_replace('Akaun Perdagangan', 'Akaun Dagangan', $nv);
                if (str_contains($nv, 'akaun perdagangan')) $nv = str_replace('akaun perdagangan', 'akaun dagangan', $nv);
                if (str_contains($nv, 'Perolehan Tersuai')) $nv = str_replace('Perolehan Tersuai', 'Penyumberan Tersuai', $nv);
                if (str_contains($nv, 'perolehan tersuai')) $nv = str_replace('perolehan tersuai', 'penyumberan tersuai', $nv);
                if (str_contains($nv, 'Semua Kategori / Penyumberan Tersuai')) $nv = str_replace('Semua Kategori / Penyumberan Tersuai', 'Semua Kategori', $nv);
                if (str_contains($nv, 'Semua Kategori / Perolehan Tersuai')) $nv = str_replace('Semua Kategori / Perolehan Tersuai', 'Semua Kategori', $nv);
                if (str_contains($nv, 'Produk & Perolehan')) $nv = str_replace('Produk & Perolehan', 'Produk & Penyumberan', $nv);
                if ($k === 'cart.title' && $nv === 'Walk-in Express') $nv = 'Troli Beli-belah';
                $data[$k] = $nv;
            }
        }
    }

    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo "  Updated {$filePath}\n";
}

Cache::flush();
echo "\n=== SYNC COMPLETED SUCCESSFULLY ===\n";
