<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;
use App\Services\TranslationService;

$contactTranslations = [
    'contact.header_title' => [
        'en' => 'Contact MST',
        'zh' => '联系 MST',
        'bm' => 'Hubungi MST',
    ],
    'contact.hero_pill' => [
        'en' => 'Sourcing & Customer Support',
        'zh' => '采购对接与客户支持',
        'bm' => 'Perolehan & Sokongan Pelanggan',
    ],
    'contact.hero_location' => [
        'en' => 'Iskandar Puteri, Johor · Malaysia, Singapore & Selected Regional Markets',
        'zh' => '依斯干达公主城，柔佛 · 马来西亚、新加坡及指定区域市场',
        'bm' => 'Iskandar Puteri, Johor · Malaysia, Singapura & Pasaran Serantau Terpilih',
    ],
    'contact.subtitle' => [
        'en' => 'Have questions about products, wholesale supply, customised sourcing or cold-chain distribution? Tell us what you need and our team will assist you.',
        'zh' => '对产品品类、大宗批发、客制化采购或冷链分销有任何需求？告诉我们您的具体要求，我们的团队将竭诚为您协助。',
        'bm' => 'Ada soalan mengenai produk, bekalan borong, perolehan khusus atau pengedaran rantaian sejuk? Beritahu kami apa yang anda perlukan dan pasukan kami sedia membantu.',
    ],
    'contact.card1_title' => [
        'en' => 'Facility & Collection Centre',
        'zh' => '仓储设施与自提中心',
        'bm' => 'Fasiliti & Pusat Pengambilan',
    ],
    'contact.card1_desc' => [
        'en' => 'Our SILC facility supports product handling, order preparation, cold-chain storage and customer collection.',
        'zh' => '我们的 SILC 设施支持商品处理、订单配货打包、温控冷链仓储及客户自提服务。',
        'bm' => 'Fasiliti SILC kami menyokong pengendalian produk, penyediaan pesanan, penyimpanan rantaian sejuk dan pengambilan pelanggan.',
    ],
    'contact.card1_address' => [
        'en' => '7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia',
        'zh' => '7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia',
        'bm' => '7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia',
    ],
    'contact.view_on_google_maps' => [
        'en' => 'View on Google Maps →',
        'zh' => '在 Google 地图查看 →',
        'bm' => 'Lihat di Google Maps →',
    ],
    'contact.card2_title' => [
        'en' => 'Direct Hotlines',
        'zh' => '服务热线',
        'bm' => 'Talian Terus',
    ],
    'contact.btn_call' => [
        'en' => 'Call Us',
        'zh' => '拨打电话',
        'bm' => 'Hubungi Kami',
    ],
    'contact.card3_title' => [
        'en' => 'Email Us',
        'zh' => '电子邮箱',
        'bm' => 'E-mel Kami',
    ],
    'contact.card3_sub' => [
        'en' => 'B2B Wholesale & Custom RFQ',
        'zh' => 'B2B 大宗批发与定制询价',
        'bm' => 'B2B Borong & Sebut Harga Tersuai',
    ],
    'contact.card3_desc' => [
        'en' => 'For wholesale enquiries, customised sourcing and quotation requests.',
        'zh' => '适用于批发咨询、客制化采购与正式报价单申请。',
        'bm' => 'Untuk pertanyaan borong, perolehan khusus dan permintaan sebut harga.',
    ],
    'contact.btn_email' => [
        'en' => 'Send Email',
        'zh' => '发送邮件',
        'bm' => 'Hantar E-mel',
    ],
    'contact.card4_title' => [
        'en' => 'WhatsApp Support',
        'zh' => 'WhatsApp 在线咨询',
        'bm' => 'Sokongan WhatsApp',
    ],
    'contact.card4_desc' => [
        'en' => 'Product Enquiries · Wholesale · Sourcing Requests',
        'zh' => '产品咨询 · 大宗批发 · 供应链采购对接',
        'bm' => 'Pertanyaan Produk · Borong · Permintaan Perolehan',
    ],
    'contact.btn_whatsapp' => [
        'en' => 'WhatsApp Us',
        'zh' => 'WhatsApp 联系',
        'bm' => 'WhatsApp Kami',
    ],
    'contact.process_badge' => [
        'en' => 'THE SOURCING PROCESS · 4 EASY STEPS',
        'zh' => '采购流程 · 4 个简单步骤',
        'bm' => 'PROSES PEROLEHAN · 4 LANGKAH MUDAH',
    ],
    'contact.process_title_1' => [
        'en' => 'Streamlined Sourcing,',
        'zh' => '高效供应链采购，',
        'bm' => 'Perolehan Lancar,',
    ],
    'contact.process_title_2' => [
        'en' => 'From Inquiry to Supply',
        'zh' => '从需求对接到货源交付',
        'bm' => 'Dari Pertanyaan ke Pembekalan',
    ],
    'contact.step1_title' => [
        'en' => '01 — SELECT YOUR REQUIREMENT',
        'zh' => '01 — 选定品类与需求',
        'bm' => '01 — PILIH KEPERLUAN ANDA',
    ],
    'contact.step1_desc' => [
        'en' => 'Choose from our core categories, including Seafood, Meat, Frozen Food, Food Ingredients and Cuisine Ingredients, or submit a customised sourcing request.',
        'zh' => '从我们的核心品类中进行选择，包括海产水产、肉类、冷冻食品、食品原料与料理食材，或直接提交客制化采购需求。',
        'bm' => 'Pilih daripada kategori teras kami, termasuk Makanan Laut, Daging, Makanan Beku, Ramuan Makanan dan Bahan Masakan, atau kemukakan permintaan perolehan khusus.',
    ],
    'contact.step2_title' => [
        'en' => '02 — TELL US YOUR REQUIREMENTS',
        'zh' => '02 — 说明规格与体量',
        'bm' => '02 — BERITAHU KAMI KEPERLUAN ANDA',
    ],
    'contact.step2_desc' => [
        'en' => 'Specify your target volume (kg, cartons or pallets), pack size, origin preference, delivery frequency and any product specifications or sourcing requirements.',
        'zh' => '指明您的目标采购体量（公斤、箱数或托盘）、包装规格、产地偏好、交货频次及具体产品规格或品质要求。',
        'bm' => 'Nyatakan jumlah sasaran anda (kg, karton atau palet), saiz pek, pilihan asal, kekerapan penghantaran dan sebarang spesifikasi produk atau keperluan perolehan.',
    ],
    'contact.step3_title' => [
        'en' => '03 — RECEIVE A QUOTATION',
        'zh' => '03 — 获取核价与商业报价',
        'bm' => '03 — TERIMA SEBUT HARGA',
    ],
    'contact.step3_desc' => [
        'en' => 'Our commercial team reviews your requirements, checks availability or coordinates with our sourcing network, and provides a quotation based on product specification, quantity and supply conditions.',
        'zh' => '我们的商业团队将审核您的规格要求，核实库存或协调全球采购网络，根据产品规格、批量及供应条件提供透明具有竞争力的商业报价。',
        'bm' => 'Pasukan komersial kami menyemak keperluan anda, menyemak ketersediaan atau menyelaras dengan rangkaian perolehan kami, dan menyediakan sebut harga berdasarkan spesifikasi produk, kuantiti dan syarat bekalan.',
    ],
    'contact.step4_title' => [
        'en' => '04 — ARRANGE SUPPLY & LOGISTICS',
        'zh' => '04 — 安排交付与履约物流',
        'bm' => '04 — SUSUN BEKALAN & LOGISTIK',
    ],
    'contact.step4_desc' => [
        'en' => 'Arrange delivery, collection or other suitable logistics according to your order requirements and destination.',
        'zh' => '根据您的订单规模与交付目的地，安排冷链配送、SILC 设施自提或适用的物流方案。',
        'bm' => 'Susun penghantaran, pengambilan atau logistik lain yang sesuai mengikut keperluan pesanan dan destinasi anda.',
    ],
    'contact.trust_label' => [
        'en' => 'Cold-Chain Handling',
        'zh' => '温控冷链管理',
        'bm' => 'Pengendalian Rantaian Sejuk',
    ],
    'contact.trust_desc' => [
        'en' => 'Temperature-controlled storage and product handling are maintained according to product requirements.',
        'zh' => '依据各品类的严谨温控要求进行专业冷库仓储与全程商品处理。',
        'bm' => 'Penyimpanan dan pengendalian produk dikawal suhu mengikut keperluan setiap produk.',
    ],
    'contact.form_eyebrow' => [
        'en' => 'REQUEST FOR QUOTATION & INQUIRY',
        'zh' => '商业询价与供应链对接',
        'bm' => 'PERMINTAAN SEBUT HARGA & PERTANYAAN',
    ],
    'contact.form_title' => [
        'en' => 'Submit Your Sourcing RFQ',
        'zh' => '提交您的采购询价',
        'bm' => 'Hantar RFQ Perolehan Anda',
    ],
    'contact.form_desc' => [
        'en' => 'Tell us about your requirements. Whether you need standard catalogue items, wholesale quantities or customised sourcing, provide your requirements and our team will review them with you.',
        'zh' => '告诉我们您的具体需求。无论是常规目录商品、批发体量还是定制化特殊规格采购，填写您的需求，我们的商业团队将与您对接。',
        'bm' => 'Beritahu kami tentang keperluan anda. Sama ada anda memerlukan item katalog biasa, kuantiti borong atau perolehan khusus, berikan keperluan anda dan pasukan kami akan menyemaknya bersama anda.',
    ],
    'contact.company_name_label' => [
        'en' => 'Company Name',
        'zh' => '公司名称',
        'bm' => 'Nama Syarikat',
    ],
    'contact.business_reg_no_label' => [
        'en' => 'Business Registration No.',
        'zh' => '商业注册号 (SSM / UEN)',
        'bm' => 'No. Pendaftaran Perniagaan',
    ],
    'contact.order_volume_label' => [
        'en' => 'Estimated Order Volume',
        'zh' => '预估采购量 (例: 500kg / 20箱 / 月度)',
        'bm' => 'Anggaran Jumlah Pesanan',
    ],
    'contact.delivery_location_label' => [
        'en' => 'Delivery / Collection Location',
        'zh' => '交货 / 自提地点 (例: 新山 / 新加坡 / SILC自提)',
        'bm' => 'Lokasi Penghantaran / Pengambilan',
    ],
    'contact.submit_btn' => [
        'en' => 'Submit Enquiry / RFQ →',
        'zh' => '提交需求 / 商业询价 →',
        'bm' => 'Hantar Pertanyaan / RFQ →',
    ],
    'contact.submit_footer' => [
        'en' => 'Our commercial team will review your requirements and contact you regarding availability, pricing and next steps.',
        'zh' => '我们的商业团队将审核您的需求，并在核实供货情况与定价后与您联系后续事宜。',
        'bm' => 'Pasukan komersial kami akan menyemak keperluan anda dan menghubungi anda mengenai ketersediaan, harga dan langkah seterusnya.',
    ],
    'contact.form_success' => [
        'en' => 'Our commercial team will review your requirements and contact you regarding availability, pricing and next steps.',
        'zh' => '我们的商业团队将审核您的需求，并在核实供货情况与定价后与您联系后续事宜。',
        'bm' => 'Pasukan komersial kami akan menyemak keperluan anda dan menghubungi anda mengenai ketersediaan, harga dan langkah seterusnya.',
    ],
    'contact.facility_eyebrow' => [
        'en' => 'FACILITY & COLLECTION CENTRE',
        'zh' => '仓储设施与自提中心',
        'bm' => 'FASILITI & PUSAT PENGAMBILAN',
    ],
    'contact.facility_title' => [
        'en' => 'Visit Our SILC Facility',
        'zh' => '莅临我们的 SILC 设施',
        'bm' => 'Lawati Fasiliti SILC Kami',
    ],
    'contact.facility_subtitle' => [
        'en' => 'Located in SILC, Iskandar Puteri, Johor, our facility supports product handling, order preparation and customer collection.',
        'zh' => '坐落于柔佛依斯干达公主城 SILC 工业园，我们的设施支持商品处理、订单配货打包与客户现场自提。',
        'bm' => 'Terletak di SILC, Iskandar Puteri, Johor, fasiliti kami menyokong pengendalian produk, penyediaan pesanan dan pengambilan pelanggan.',
    ],
    'contact.facility_role_new' => [
        'en' => 'Facility & Collection Centre',
        'zh' => '设施与自提中心',
        'bm' => 'Fasiliti & Pusat Pengambilan',
    ],
    'contact.cta_title' => [
        'en' => 'Not Sure What You Need?',
        'zh' => '尚未确定具体规格与品类？',
        'bm' => 'Tidak Pasti Apa yang Anda Perlukan?',
    ],
    'contact.cta_desc' => [
        'en' => 'Tell us your product, quantity, specification or sourcing requirement. We can help you identify suitable supply options.',
        'zh' => '告诉我们您的预期品类、体量、规格或采购目标。我们可为您评估并推荐最适合的货源与供应方案。',
        'bm' => 'Beritahu kami produk, kuantiti, spesifikasi atau keperluan perolehan anda. Kami boleh membantu anda mengenal pasti pilihan bekalan yang sesuai.',
    ],
    'contact.cta_rfq_btn' => [
        'en' => 'Request a Quote',
        'zh' => '申请报价单',
        'bm' => 'Minta Sebut Harga',
    ],
    'contact.cta_whatsapp_btn' => [
        'en' => 'WhatsApp Us',
        'zh' => 'WhatsApp 咨询',
        'bm' => 'WhatsApp Kami',
    ],
    'contact.cta_products_btn' => [
        'en' => 'Browse Products',
        'zh' => '浏览产品目录',
        'bm' => 'Lihat Produk',
    ],
    'footer.sourcing_desc' => [
        'en' => 'Cold-chain sourcing, wholesale supply and customised import distribution for Malaysia, Singapore and selected regional markets.',
        'zh' => '覆盖马来西亚、新加坡及指定区域市场的冷链采购、大宗批发及客制化进口分销供应链。',
        'bm' => 'Perolehan rantaian sejuk, bekalan borong dan pengedaran import tersuai untuk Malaysia, Singapura dan pasaran serantau terpilih.',
    ],
];

// Update DB
foreach ($contactTranslations as $key => $vals) {
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

    // Also update composite key
    Translation::updateOrCreate(
        ['group' => $group, 'key' => $key],
        [
            'text_en' => $vals['en'],
            'text_zh' => $vals['zh'],
            'text_bm' => $vals['bm'],
        ]
    );
}

// Clear cache & sync files
$translationService = app(TranslationService::class);
$translationService->clearCache();
$translationService->syncLangFiles();

echo "Successfully synchronized " . count($contactTranslations) . " Contact Us translations across DB and JSON files!\n";
