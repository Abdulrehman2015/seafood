<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;
use Illuminate\Support\Facades\File;

echo "=======================================================\n";
echo "SYNCHRONIZING MULTILINGUAL JSON FILES & DATABASE\n";
echo "=======================================================\n\n";

$langDir = base_path('lang');
$enJsonPath = $langDir . '/en.json';
$zhJsonPath = $langDir . '/zh.json';
$bmJsonPath = $langDir . '/bm.json';
$msJsonPath = $langDir . '/ms.json';

$en = json_decode(file_get_contents($enJsonPath), true) ?: [];
$zh = json_decode(file_get_contents($zhJsonPath), true) ?: [];
$bm = json_decode(file_get_contents($bmJsonPath), true) ?: [];

// Targeted Translation Updates / Corrections
$updates = [
    // Currency notices
    'common.currency_notice' => [
        'en' => "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.",
        'zh' => "ℹ️ SGD 和 USD 价格仅供参考。MST 的基础价格及结算货币为 RM。页面显示的参考汇率可能随时调整。",
        'bm' => "ℹ️ Harga dalam SGD dan USD adalah untuk rujukan sahaja. Harga asas MST adalah dalam RM. Kadar pertukaran yang dipaparkan mungkin berubah dari semasa ke semasa.",
    ],
    'currency_notice' => [
        'en' => "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.",
        'zh' => "ℹ️ SGD 和 USD 价格仅供参考。MST 的基础价格及结算货币为 RM。页面显示的参考汇率可能随时调整。",
        'bm' => "ℹ️ Harga dalam SGD dan USD adalah untuk rujukan sahaja. Harga asas MST adalah dalam RM. Kadar pertukaran yang dipaparkan mungkin berubah dari semasa ke semasa.",
    ],
    'common.currency_indicative_disclaimer' => [
        'en' => "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.",
        'zh' => "ℹ️ SGD 和 USD 价格仅供参考。MST 的基础价格及结算货币为 RM。页面显示的参考汇率可能随时调整。",
        'bm' => "ℹ️ Harga dalam SGD dan USD adalah untuk rujukan sahaja. Harga asas MST adalah dalam RM. Kadar pertukaran yang dipaparkan mungkin berubah dari semasa ke semasa.",
    ],
    'currency_indicative_disclaimer' => [
        'en' => "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.",
        'zh' => "ℹ️ SGD 和 USD 价格仅供参考。MST 的基础价格及结算货币为 RM。页面显示的参考汇率可能随时调整。",
        'bm' => "ℹ️ Harga dalam SGD dan USD adalah untuk rujukan sahaja. Harga asas MST adalah dalam RM. Kadar pertukaran yang dipaparkan mungkin berubah dari semasa ke semasa.",
    ],
    'nav.currency_indicative_note' => [
        'en' => "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.",
        'zh' => "ℹ️ SGD 和 USD 价格仅供参考。MST 的基础价格及结算货币为 RM。页面显示的参考汇率可能随时调整。",
        'bm' => "ℹ️ Harga dalam SGD dan USD adalah untuk rujukan sahaja. Harga asas MST adalah dalam RM. Kadar pertukaran yang dipaparkan mungkin berubah dari semasa ke semasa.",
    ],
    'shop.currency_indicative_disclaimer' => [
        'en' => "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.",
        'zh' => "ℹ️ SGD 和 USD 价格仅供参考。MST 的基础价格及结算货币为 RM。页面显示的参考汇率可能随时调整。",
        'bm' => "ℹ️ Harga dalam SGD dan USD adalah untuk rujukan sahaja. Harga asas MST adalah dalam RM. Kadar pertukaran yang dipaparkan mungkin berubah dari semasa ke semasa.",
    ],
    'cart.currency_note' => [
        'en' => "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.",
        'zh' => "ℹ️ SGD 和 USD 价格仅供参考。MST 的基础价格及结算货币为 RM。页面显示的参考汇率可能随时调整。",
        'bm' => "ℹ️ Harga dalam SGD dan USD adalah untuk rujukan sahaja. Harga asas MST adalah dalam RM. Kadar pertukaran yang dipaparkan mungkin berubah dari semasa ke semasa.",
    ],
    'checkout.currency_note' => [
        'en' => "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.",
        'zh' => "ℹ️ SGD 和 USD 价格仅供参考。MST 的基础价格及结算货币为 RM。页面显示的参考汇率可能随时调整。",
        'bm' => "ℹ️ Harga dalam SGD dan USD adalah untuk rujukan sahaja. Harga asas MST adalah dalam RM. Kadar pertukaran yang dipaparkan mungkin berubah dari semasa ke semasa.",
    ],
    'walkin.currency_note' => [
        'en' => "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.",
        'zh' => "ℹ️ SGD 和 USD 价格仅供参考。MST 的基础价格及结算货币为 RM。页面显示的参考汇率可能随时调整。",
        'bm' => "ℹ️ Harga dalam SGD dan USD adalah untuk rujukan sahaja. Harga asas MST adalah dalam RM. Kadar pertukaran yang dipaparkan mungkin berubah dari semasa ke semasa.",
    ],
    'auth.currency_reference_notice' => [
        'en' => "ℹ️ SGD and USD prices are for reference only. MST's base prices and settlement currency are in RM. Reference exchange rates may change from time to time.",
        'zh' => "ℹ️ SGD 和 USD 价格仅供参考。MST 的基础价格及结算货币为 RM。页面显示的参考汇率可能随时调整。",
        'bm' => "ℹ️ Harga dalam SGD dan USD adalah untuk rujukan sahaja. Harga asas MST adalah dalam RM. Kadar pertukaran yang dipaparkan mungkin berubah dari semasa ke semasa.",
    ],

    // Slogan (Consistent Official English Brand Slogan)
    'common.slogan' => [
        'en' => 'Flow with Integrity, Grow with Strength.',
        'zh' => 'Flow with Integrity, Grow with Strength.',
        'bm' => 'Flow with Integrity, Grow with Strength.',
    ],
    'slogan' => [
        'en' => 'Flow with Integrity, Grow with Strength.',
        'zh' => 'Flow with Integrity, Grow with Strength.',
        'bm' => 'Flow with Integrity, Grow with Strength.',
    ],
    'home.slogan' => [
        'en' => 'Flow with Integrity, Grow with Strength.',
        'zh' => 'Flow with Integrity, Grow with Strength.',
        'bm' => 'Flow with Integrity, Grow with Strength.',
    ],
    'footer.slogan' => [
        'en' => 'Flow with Integrity, Grow with Strength.',
        'zh' => 'Flow with Integrity, Grow with Strength.',
        'bm' => 'Flow with Integrity, Grow with Strength.',
    ],
    'footer.tagline' => [
        'en' => 'Flow with Integrity, Grow with Strength.',
        'zh' => 'Flow with Integrity, Grow with Strength.',
        'bm' => 'Flow with Integrity, Grow with Strength.',
    ],
    'nav.slogan' => [
        'en' => 'Flow with Integrity, Grow with Strength.',
        'zh' => 'Flow with Integrity, Grow with Strength.',
        'bm' => 'Flow with Integrity, Grow with Strength.',
    ],

    // Product Categories
    'categories.fish' => [
        'en' => 'Fish',
        'zh' => '鱼类',
        'bm' => 'Ikan',
    ],
    'categories.fish_fillet' => [
        'en' => 'Fish Fillet',
        'zh' => '鱼柳',
        'bm' => 'Fillet Ikan',
    ],
    'categories.crab' => [
        'en' => 'Crab',
        'zh' => '蟹类',
        'bm' => 'Ketam',
    ],
    'categories.prawns_shrimps' => [
        'en' => 'Prawns / Shrimps',
        'zh' => '虾类',
        'bm' => 'Udang',
    ],
    'categories.squid' => [
        'en' => 'Squid / Cuttlefish',
        'zh' => '鱿鱼',
        'bm' => 'Sotong',
    ],
    'categories.shellfish' => [
        'en' => 'Shellfish',
        'zh' => '贝类',
        'bm' => 'Kerang-kerangan',
    ],
    'categories.other_seafood' => [
        'en' => 'Other Seafood',
        'zh' => '其他海产',
        'bm' => 'Makanan Laut Lain',
    ],
    'categories.steamboat' => [
        'en' => 'Steamboat / Hotpot',
        'zh' => '火锅食材',
        'bm' => 'Steamboat / Hotpot',
    ],

    // Delivery Reference Threshold
    'common.delivery_threshold_title' => [
        'en' => 'Standard Delivery Reference Threshold',
        'zh' => '标准配送参考门槛',
        'bm' => 'Ambang Rujukan Penghantaran Standard',
    ],
    'delivery_threshold_title' => [
        'en' => 'Standard Delivery Reference Threshold',
        'zh' => '标准配送参考门槛',
        'bm' => 'Ambang Rujukan Penghantaran Standard',
    ],
    'shop.delivery_threshold_title' => [
        'en' => 'Standard Delivery Reference Threshold',
        'zh' => '标准配送参考门槛',
        'bm' => 'Ambang Rujukan Penghantaran Standard',
    ],
    'cart.delivery_threshold_title' => [
        'en' => 'Standard Delivery Reference Threshold',
        'zh' => '标准配送参考门槛',
        'bm' => 'Ambang Rujukan Penghantaran Standard',
    ],
    'checkout.delivery_threshold_title' => [
        'en' => 'Standard Delivery Reference Threshold',
        'zh' => '标准配送参考门槛',
        'bm' => 'Ambang Rujukan Penghantaran Standard',
    ],

    // Shopping Mode / Customer Type Terminology (Section 18 & 19)
    'common.shopping_for' => [
        'en' => 'Shopping for:',
        'zh' => '采购类型：',
        'bm' => 'Jenis Pembelian:',
    ],
    'shopping_for' => [
        'en' => 'Shopping for:',
        'zh' => '采购类型：',
        'bm' => 'Jenis Pembelian:',
    ],
    'shop.shopping_for' => [
        'en' => 'Shopping for:',
        'zh' => '采购类型：',
        'bm' => 'Jenis Pembelian:',
    ],
    'shop.type_retail' => [
        'en' => 'Retail / Walk-in',
        'zh' => '零售 / 现场自提',
        'bm' => 'Runcit / Ambil Sendiri',
    ],
    'common.type_retail' => [
        'en' => 'Retail / Walk-in',
        'zh' => '零售 / 现场自提',
        'bm' => 'Runcit / Ambil Sendiri',
    ],
    'type_retail' => [
        'en' => 'Retail / Walk-in',
        'zh' => '零售 / 现场自提',
        'bm' => 'Runcit / Ambil Sendiri',
    ],
    'shop.type_wholesale' => [
        'en' => 'Wholesale / Business',
        'zh' => '批发 / 商业采购',
        'bm' => 'Borong / Perniagaan',
    ],
    'common.type_wholesale' => [
        'en' => 'Wholesale / Business',
        'zh' => '批发 / 商业采购',
        'bm' => 'Borong / Perniagaan',
    ],
    'type_wholesale' => [
        'en' => 'Wholesale / Business',
        'zh' => '批发 / 商业采购',
        'bm' => 'Borong / Perniagaan',
    ],

    // Self-Collection Terminology (Section 14 & 18)
    'walkin.self_collection' => [
        'en' => 'Self-Collection',
        'zh' => '到店自提',
        'bm' => 'Pengambilan Sendiri',
    ],
    'common.self_collection' => [
        'en' => 'Self-Collection',
        'zh' => '到店自提',
        'bm' => 'Pengambilan Sendiri',
    ],
    'self_collection' => [
        'en' => 'Self-Collection',
        'zh' => '到店自提',
        'bm' => 'Pengambilan Sendiri',
    ],
    'walkin.express_pickup_tag' => [
        'en' => 'Self-collection only · No delivery',
        'zh' => '仅限到店自提 · 不设配送',
        'bm' => 'Pengambilan sendiri sahaja · Tiada penghantaran',
    ],

    // BM Phone label (Section 18)
    'footer.phone_label' => [
        'en' => 'Phone:',
        'zh' => '电话：',
        'bm' => 'Telefon:',
    ],
    'common.phone_label' => [
        'en' => 'Phone:',
        'zh' => '电话：',
        'bm' => 'Telefon:',
    ],
    'phone_label' => [
        'en' => 'Phone:',
        'zh' => '电话：',
        'bm' => 'Telefon:',
    ],
    'footer.whatsapp_label' => [
        'en' => 'WhatsApp:',
        'zh' => 'WhatsApp：',
        'bm' => 'WhatsApp:',
    ],

    // Product Availability / Pricing Subject to Confirmation (Section 22, 38)
    'shop.availability_subject_to_confirmation' => [
        'en' => 'Product availability, specifications and pricing are subject to confirmation.',
        'zh' => '产品供应、规格及价格以最终确认为准。',
        'bm' => 'Ketersediaan produk, spesifikasi dan harga adalah tertakluk kepada pengesahan.',
    ],
    'common.availability_subject_to_confirmation' => [
        'en' => 'Product availability, specifications and pricing are subject to confirmation.',
        'zh' => '产品供应、规格及价格以最终确认为准。',
        'bm' => 'Ketersediaan produk, spesifikasi dan harga adalah tertakluk kepada pengesahan.',
    ],
    'shop.stock_status_in_stock' => [
        'en' => 'Availability subject to confirmation',
        'zh' => '库存以确认为准',
        'bm' => 'Ketersediaan tertakluk kepada pengesahan',
    ],

    // Market and Delivery Coverage Clean-up (Section 13, 21, 43)
    'footer.brand_desc' => [
        'en' => 'Cold-chain sourcing, wholesale supply & customised sourcing for customers in Malaysia and Singapore.',
        'zh' => '专为马来西亚及新加坡客户提供冷链海鲜、冷冻食品批发供应与定制化采购服务。',
        'bm' => 'Penyumberan rangkaian sejuk, bekalan borong & penyumberan tersuai untuk pelanggan di Malaysia dan Singapura.',
    ],
    'footer.sourcing_desc' => [
        'en' => 'Cold-chain sourcing, wholesale supply & customised sourcing for customers in Malaysia and Singapore.',
        'zh' => '专为马来西亚及新加坡客户提供冷链海鲜、冷冻食品批发供应与定制化采购服务。',
        'bm' => 'Penyumberan rangkaian sejuk, bekalan borong & penyumberan tersuai untuk pelanggan di Malaysia dan Singapura.',
    ],
    'home.coverage_desc' => [
        'en' => 'Serving businesses and customers in Malaysia and Singapore with standard local delivery in Johor Bahru and selected areas of Iskandar Puteri / Nusajaya.',
        'zh' => '服务马来西亚与新加坡商业伙伴与客户；标准本地配送覆盖新山及依斯干达公主城 / 努沙再也指定区域。',
        'bm' => 'Menyediakan perkhidmatan untuk perniagaan dan pelanggan di Malaysia dan Singapura dengan penghantaran standard tempatan di Johor Bahru dan kawasan terpilih di Iskandar Puteri / Nusajaya.',
    ],
    'home.hero_delivery_badge' => [
        'en' => 'Johor Bahru & selected areas of Iskandar Puteri / Nusajaya',
        'zh' => '新山及依斯干达公主城 / 努沙再也指定区域',
        'bm' => 'Johor Bahru & kawasan terpilih Iskandar Puteri / Nusajaya',
    ],

    // Clean Counter 2 brand references from store locations / titles (Section 14)
    'common.store_location' => [
        'en' => 'MST Cold-Chain Facility, No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia',
        'zh' => 'MST 冷链配送基地，No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia',
        'bm' => 'Fasiliti Rangkaian Sejuk MST, No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia',
    ],
    'store_location' => [
        'en' => 'MST Cold-Chain Facility, No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia',
        'zh' => 'MST 冷链配送基地，No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia',
        'bm' => 'Fasiliti Rangkaian Sejuk MST, No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia',
    ],
    'common.instant_pickup' => [
        'en' => 'Instant In-Store Self-Collection',
        'zh' => '实体店即时到店自提',
        'bm' => 'Pengambilan Segera di Premis',
    ],
    'instant_pickup' => [
        'en' => 'Instant In-Store Self-Collection',
        'zh' => '实体店即时到店自提',
        'bm' => 'Pengambilan Segera di Premis',
    ],
    'common.store_pickup_title' => [
        'en' => 'In-Store Self-Collection',
        'zh' => '实体店到店自提',
        'bm' => 'Pengambilan Sendiri di Premis',
    ],
    'store_pickup_title' => [
        'en' => 'In-Store Self-Collection',
        'zh' => '实体店到店自提',
        'bm' => 'Pengambilan Sendiri di Premis',
    ],
    'common.counter_pickup_ready' => [
        'en' => 'Ready for In-Store Collection',
        'zh' => '已备妥供到店自提',
        'bm' => 'Sedia Untuk Diambil di Premis',
    ],
    'common.instant_counter' => [
        'en' => 'In-Store Immediate Collection',
        'zh' => '实体店即刻自提',
        'bm' => 'Pengambilan Segera di Premis',
    ],
    'common.counter_2_pickup' => [
        'en' => 'MST In-Store Self-Collection (Free)',
        'zh' => 'MST 实体店自提（免运费）',
        'bm' => 'Pengambilan Sendiri MST (Percuma)',
    ],
];

// Apply updates to JSON files and database
$appliedCount = 0;
foreach ($updates as $key => $vals) {
    $en[$key] = $vals['en'];
    $zh[$key] = $vals['zh'];
    $bm[$key] = $vals['bm'];

    // Also update/insert into translations DB table
    $group = 'common';
    $tKey = $key;
    if (str_contains($key, '.')) {
        $parts = explode('.', $key, 2);
        $group = $parts[0];
        $tKey = $parts[1];
    }

    Translation::updateOrCreate(
        ['group' => $group, 'key' => $tKey],
        [
            'text_en' => $vals['en'],
            'text_zh' => $vals['zh'],
            'text_bm' => $vals['bm'],
        ]
    );
    $appliedCount++;
}

// Global text replacements for remaining legacy strings across JSON arrays
$enJsonStr = json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$zhJsonStr = json_encode($zh, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$bmJsonStr = json_encode($bm, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Replace "Malaysia, Singapore and selected markets" -> "Malaysia and Singapore"
$enJsonStr = str_replace('Malaysia, Singapore and selected markets', 'Malaysia and Singapore', $enJsonStr);
$zhJsonStr = str_replace(['马来西亚、新加坡及指定市场', '马来西亚、新加坡及精选市场'], '马来西亚与新加坡', $zhJsonStr);
$bmJsonStr = str_replace(['Malaysia, Singapura dan pasaran terpilih', 'Malaysia, Singapura dan pasaran pilihan'], 'Malaysia dan Singapura', $bmJsonStr);

// Clean Counter 2 brand phrases
$zhJsonStr = str_replace([
    'MST Counter 2即时提货', 'MST Counter 2 即时提货', '门店 2号柜台即时提货', '2号柜台即时提货',
    'MST 2号柜台自提', '2号柜台极速自提', '门市 2号柜台即刻自提'
], '实体店到店自提', $zhJsonStr);

$bmJsonStr = str_replace([
    'Pengambilan Segera Kaunter 2 Kedai', 'Pengambilan Kaunter 2', 'MST Kaunter 2'
], 'Pengambilan Sendiri di Premis', $bmJsonStr);

// Clean BM phrases
$bmJsonStr = str_replace('harga berperingkat', 'harga perniagaan', $bmJsonStr);
$bmJsonStr = str_replace('Perolehan Tersuai', 'Penyumberan Tersuai', $bmJsonStr);
$bmJsonStr = str_replace('Sumber Tersuai', 'Penyumberan Tersuai', $bmJsonStr);

file_put_contents($enJsonPath, $enJsonStr);
file_put_contents($zhJsonPath, $zhJsonStr);
file_put_contents($bmJsonPath, $bmJsonStr);
file_put_contents($msJsonPath, $bmJsonStr); // Sync ms.json with bm.json

echo "✓ Updated {$appliedCount} primary translation keys across JSON files and DB.\n";
echo "✓ Synchronized en.json, zh.json, bm.json, ms.json successfully.\n";

// Clear translation and config caches
\Illuminate\Support\Facades\Cache::flush();
echo "✓ Cache flushed successfully.\n";
