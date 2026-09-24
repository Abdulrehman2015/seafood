<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;
use App\Services\TranslationService;

$translations = [
    'walkin.page_title' => [
        'en' => 'Walk-in Express — In-Store Express Menu',
        'zh' => '到店现购 — 门店现场现购菜单',
        'bm' => 'Walk-in Express — Menu Ekspres di Kedai',
    ],
    'walkin.title' => [
        'en' => 'Walk-in Express',
        'zh' => '到店现购',
        'bm' => 'Walk-in Express',
    ],
    'walkin.menu_subtitle' => [
        'en' => 'In-Store Express Menu',
        'zh' => '门店现场现购菜单',
        'bm' => 'Menu Ekspres di Kedai',
    ],
    'walkin.public_pricing' => [
        'en' => 'Public / Walk-in Pricing',
        'zh' => '公开 / 现购零售价',
        'bm' => 'Harga Awam / Walk-in',
    ],
    'walkin.store_location' => [
        'en' => 'MST Counter 2 · SILC Industrial Park, Iskandar Puteri',
        'zh' => 'MST 2 号柜台 · 依斯干达公主城 SILC 工业园',
        'bm' => 'MST Kaunter 2 · Taman Perindustrian SILC, Iskandar Puteri',
    ],
    'walkin.store_short_loc' => [
        'en' => 'MST Counter 2 · SILC',
        'zh' => 'MST 2 号柜台 · SILC',
        'bm' => 'MST Kaunter 2 · SILC',
    ],
    'walkin.express_pickup_tag' => [
        'en' => 'Express Counter 2 Collection',
        'zh' => '2 号柜台极速提货',
        'bm' => 'Pengambilan Ekspres Kaunter 2',
    ],
    'walkin.service_desc' => [
        'en' => 'Browse available products, select your items, complete payment on your phone, and collect your packed order at MST Counter 2.',
        'zh' => '在手机上浏览现售商品、选择数量并完成付款，随后前往 MST 2 号柜台直接提取已打包订单。',
        'bm' => 'Lihat produk sedia ada, pilih item anda, lengkapkan pembayaran di telefon, dan ambil pesanan yang dibungkus di MST Kaunter 2.',
    ],
    'walkin.cart_title' => [
        'en' => 'Walk-in Express Cart',
        'zh' => '到店现购购物车',
        'bm' => 'Troli Walk-in Express',
    ],
    'walkin.pay_and_collect' => [
        'en' => 'Pay & Collect',
        'zh' => '付款并提货',
        'bm' => 'Bayar & Ambil',
    ],
    'walkin.how_it_works' => [
        'en' => 'How Walk-in Express Works',
        'zh' => '到店现购流程',
        'bm' => 'Cara Walk-in Express Berfungsi',
    ],
    'walkin.step_1_name' => [
        'en' => 'Browse',
        'zh' => '浏览',
        'bm' => 'Lihat',
    ],
    'walkin.step_1_desc' => [
        'en' => 'View available products on your phone.',
        'zh' => '在手机上查看现售商品。',
        'bm' => 'Lihat produk sedia ada di telefon anda.',
    ],
    'walkin.step_2_name' => [
        'en' => 'Select',
        'zh' => '选购',
        'bm' => 'Pilih',
    ],
    'walkin.step_2_desc' => [
        'en' => 'Choose your products and quantities.',
        'zh' => '挑选商品与所需数量。',
        'bm' => 'Pilih produk dan kuantiti anda.',
    ],
    'walkin.step_3_name' => [
        'en' => 'Pay',
        'zh' => '付款',
        'bm' => 'Bayar',
    ],
    'walkin.step_3_desc' => [
        'en' => 'Complete payment securely on your phone.',
        'zh' => '在手机上安全完成付款。',
        'bm' => 'Lengkapkan pembayaran dengan selamat di telefon.',
    ],
    'walkin.step_4_name' => [
        'en' => 'Collect',
        'zh' => '提货',
        'bm' => 'Ambil',
    ],
    'walkin.step_4_desc' => [
        'en' => 'Collect your packed order at Counter 2.',
        'zh' => '在 2 号柜台提取已打包订单。',
        'bm' => 'Ambil pesanan yang dibungkus di Kaunter 2.',
    ],
    'walkin.wholesale_note' => [
        'en' => 'Walk-in Express is intended for retail / individual purchases and Counter 2 collection.',
        'zh' => '到店现购专用于零售 / 个人选购及 2 号柜台提货。',
        'bm' => 'Walk-in Express dikhususkan untuk pembelian runcit / individu dan pengambilan di Kaunter 2.',
    ],
    'walkin.wholesale_link' => [
        'en' => 'Looking for wholesale or regular supply? Request a Business Account',
        'zh' => '寻找批发或大宗长期采购？申请企业业务账户',
        'bm' => 'Mencari bekalan borong atau tetap? Mohon Akaun Perniagaan',
    ],
    'walkin.counter_title' => [
        'en' => 'Counter 2 Collection',
        'zh' => '2 号柜台提货',
        'bm' => 'Pengambilan Kaunter 2',
    ],
    'walkin.counter_desc' => [
        'en' => 'Orders are prepared for Counter 2 collection after payment confirmation. Orders are packed appropriately for collection and transport.',
        'zh' => '确认付款后，订单将妥善打包并送至 2 号柜台供您提取。商品均经合适应冷链保护包装以便运输。',
        'bm' => 'Pesanan disediakan untuk pengambilan di Kaunter 2 selepas pengesahan pembayaran. Pesanan dibungkus dengan sesuai untuk pengambilan dan pengangkutan.',
    ],
    'walkin.counter_note' => [
        'en' => 'Please present your order reference / payment confirmation when collecting your order.',
        'zh' => '提货时请向工作人员出示您的订单参考号 / 付款凭证。',
        'bm' => 'Sila tunjukkan rujukan pesanan / pengesahan pembayaran anda semasa mengambil pesanan.',
    ],
    'walkin.products_count_label' => [
        'en' => 'selected products available for Walk-in / Counter Collection',
        'zh' => '款精选商品供到店选购 / 柜台自提',
        'bm' => 'produk terpilih sedia untuk Walk-in / Pengambilan Kaunter',
    ],
    'walkin.search_placeholder' => [
        'en' => 'Search products by name, category, brand or product code...',
        'zh' => '按商品名称、分类、品牌或货号搜索...',
        'bm' => 'Cari produk mengikut nama, kategori, jenama atau kod produk...',
    ],
    'walkin.search_placeholder_short' => [
        'en' => 'Search products...',
        'zh' => '搜索商品...',
        'bm' => 'Cari produk...',
    ],
    'walkin.walkin_rate_tag' => [
        'en' => 'Walk-in',
        'zh' => '现购零售',
        'bm' => 'Walk-in',
    ],
    'walkin.price_upon_request' => [
        'en' => 'Price Available Upon Request',
        'zh' => '价格待询',
        'bm' => 'Harga Atas Permintaan',
    ],
    'walkin.request_quote' => [
        'en' => 'Request Quote',
        'zh' => '获取报价',
        'bm' => 'Minta Sebut Harga',
    ],
    'walkin.custom_sourcing_title' => [
        'en' => "Can't Find What You Need?",
        'zh' => '找不到您需要的规格或商品？',
        'bm' => 'Tidak Temui Apa yang Anda Perlukan?',
    ],
    'walkin.custom_sourcing_desc' => [
        'en' => 'MST also provides customised sourcing for products, specifications and pack sizes not currently listed online.',
        'zh' => 'MST 亦为未在线列出的特殊规格、包装与品种提供客制化采购与供应链服务。',
        'bm' => 'MST juga menyediakan perolehan khusus untuk produk, spesifikasi dan saiz pek yang belum disenaraikan dalam talian.',
    ],
    'walkin.request_custom_sourcing' => [
        'en' => 'Request Custom Sourcing',
        'zh' => '申请客制化采购',
        'bm' => 'Mohon Perolehan Khusus',
    ],
    'walkin.confirm_pickup_title' => [
        'en' => 'Please confirm your order and collection location before payment.',
        'zh' => '付款前请确认您的订单与提货地点。',
        'bm' => 'Sila sahkan pesanan dan lokasi pengambilan anda sebelum pembayaran.',
    ],
    'walkin.checkout_subtitle' => [
        'en' => 'Please confirm your order and collection location before payment. Collect your packed order at MST Counter 2.',
        'zh' => '付款前请确认您的订单与提货地点。完成付款后至 MST 2 号柜台自提。',
        'bm' => 'Sila sahkan pesanan dan lokasi pengambilan anda sebelum pembayaran. Ambil pesanan anda di MST Kaunter 2.',
    ],
    'walkin.pay_cash_title' => [
        'en' => 'Cash at Counter 2',
        'zh' => '2 号柜台现金付款',
        'bm' => 'Tunai di Kaunter 2',
    ],
    'walkin.pay_cash_desc' => [
        'en' => 'Pay cash directly at SILC Counter 2 upon collecting your packed order.',
        'zh' => '提取已打包订单时，直接在 SILC 2 号柜台以现金结账。',
        'bm' => 'Bayar tunai terus di SILC Kaunter 2 semasa mengambil pesanan yang dibungkus.',
    ],
    'walkin.cash_info_desc' => [
        'en' => 'Your order will be registered and queued. Present your Order Reference at Counter 2 to complete payment and collect your packed order.',
        'zh' => '您的订单将进入处理队列。请在 2 号柜台出示订单参考号完成付款并提货。',
        'bm' => 'Pesanan anda akan didaftarkan dan diproses. Tunjukkan Rujukan Pesanan anda di Kaunter 2 untuk menyelesaikan pembayaran dan mengambil pesanan anda.',
    ],
    'walkin.pay_online_title' => [
        'en' => 'Online Payment (Phone Pay)',
        'zh' => '手机在线安全支付',
        'bm' => 'Pembayaran Dalam Talian (Telefon)',
    ],
    'walkin.pay_online_desc' => [
        'en' => 'Credit / Debit Card, Apple Pay, Google Pay, or FPX Online Banking.',
        'zh' => '信用卡 / 借记卡、Apple Pay、Google Pay 或 FPX 网上银行。',
        'bm' => 'Kad Kredit / Debit, Apple Pay, Google Pay, atau Perbankan FPX.',
    ],
    'walkin.confirm_cash_order' => [
        'en' => 'Confirm Order & Proceed to Counter 2',
        'zh' => '确认订单并前往 2 号柜台',
        'bm' => 'Sahkan Pesanan & Pergi ke Kaunter 2',
    ],
    'walkin.proceed_to_payment' => [
        'en' => 'Proceed to Secure Payment →',
        'zh' => '前往安全支付 →',
        'bm' => 'Teruskan ke Pembayaran Selamat →',
    ],
    'walkin.counter_2_pickup' => [
        'en' => 'MST Counter 2 Collection (FREE)',
        'zh' => 'MST 2 号柜台自提（免费）',
        'bm' => 'Pengambilan MST Kaunter 2 (PERCUMA)',
    ],
    'walkin.prepared_promptly' => [
        'en' => 'Orders prepared for Counter 2 collection after payment confirmation',
        'zh' => '确认付款后订单将尽快备妥供 2 号柜台自提',
        'bm' => 'Pesanan disediakan untuk pengambilan Kaunter 2 selepas pengesahan pembayaran',
    ],
    'walkin.packed_appropriately' => [
        'en' => 'Orders are packed appropriately for collection and transport',
        'zh' => '订单将经过合适应冷链保护包装以便提取运输',
        'bm' => 'Pesanan dibungkus dengan sesuai untuk pengambilan dan pengangkutan',
    ],
    'walkin.packed_appropriate' => [
        'en' => 'Appropriately Packed',
        'zh' => '妥善保冷包装',
        'bm' => 'Dibungkus dengan Sesuai',
    ],
    'walkin.back_to_menu' => [
        'en' => 'Back to Walk-in Menu',
        'zh' => '返回到店菜单',
        'bm' => 'Kembali ke Menu Walk-in',
    ],
    'walkin.public_walkin_price' => [
        'en' => 'Public Walk-in Price',
        'zh' => '公开现购价',
        'bm' => 'Harga Walk-in Awam',
    ],
    'walkin.walkin_retail_item' => [
        'en' => 'Walk-in Retail',
        'zh' => '到店零售',
        'bm' => 'Runcit Walk-in',
    ],
    'checkout.payment_confirmed_title' => [
        'en' => 'Payment Confirmed · Counter 2 Collection',
        'zh' => '付款确认 · 2 号柜台提货',
        'bm' => 'Pengesahan Pembayaran · Pengambilan Kaunter 2',
    ],
    'checkout.payment_confirmed_desc' => [
        'en' => 'Payment confirmed. Your order is being prepared for Counter 2 collection.',
        'zh' => '付款已确认。您的订单正在准备供 2 号柜台提货。',
        'bm' => 'Pembayaran disahkan. Pesanan anda sedang disediakan untuk pengambilan Kaunter 2.',
    ],
    'checkout.paid_online' => [
        'en' => 'Payment Confirmed',
        'zh' => '付款已确认',
        'bm' => 'Pembayaran Disahkan',
    ],
    'footer.sourcing_desc' => [
        'en' => 'Cold-chain sourcing, wholesale supply & customised import distribution across regional & international markets.',
        'zh' => '覆盖区域与国际市场的冷链采购、大宗批发及客制化进口分销供应链。',
        'bm' => 'Perolehan rantaian sejuk, bekalan borong & pengedaran import tersuai merentasi pasaran serantau & antarabangsa.',
    ],
];

// Update DB
foreach ($translations as $key => $vals) {
    $group = str_contains($key, '.') ? explode('.', $key, 2)[0] : 'common';
    $itemKey = str_contains($key, '.') ? explode('.', $key, 2)[1] : $key;

    Translation::updateOrCreate(
        ['group' => $group, 'key' => $itemKey],
        [
            'text_en' => $vals['en'],
            'text_zh' => $vals['zh'],
            'text_bm' => $vals['bm'],
        ]
    );

    // Also update composite key version just in case
    Translation::updateOrCreate(
        ['group' => $group, 'key' => $key],
        [
            'text_en' => $vals['en'],
            'text_zh' => $vals['zh'],
            'text_bm' => $vals['bm'],
        ]
    );
}

// Clear cache and sync files
$translationService = app(TranslationService::class);
$translationService->clearCache();
$translationService->syncLangFiles();

echo "Successfully synchronized " . count($translations) . " translations across DB and JSON files!\n";
