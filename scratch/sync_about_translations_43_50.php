<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Translation;
use App\Services\TranslationService;

$data = [
    'about.meta_title' => [
        'en' => 'About Us — MST Import and Export Sdn. Bhd.',
        'zh' => '关于我们 — MST Import and Export Sdn. Bhd.',
        'bm' => 'Tentang Kami — MST Import and Export Sdn. Bhd.',
    ],
    'about.meta_desc' => [
        'en' => 'Learn about MST Import and Export Sdn. Bhd. Sourcing, trading and supply of seafood, meat, frozen food and food ingredients across Malaysia and Singapore.',
        'zh' => '了解 MST Import and Export Sdn. Bhd.。为马来西亚与新加坡客户提供海鲜、肉类、冷冻食品及精选食材的采购、贸易与供应服务。',
        'bm' => 'Ketahui tentang MST Import and Export Sdn. Bhd. Penyumberan, perdagangan dan pembekalan makanan laut, daging, makanan sejuk beku dan bahan makanan merentasi Malaysia dan Singapura.',
    ],
    'about.est_badge' => [
        'en' => '🏆 Established in 2014 · Johor Bahru, Malaysia',
        'zh' => '🏆 创立于 2014 年 · 马来西亚柔佛新山',
        'bm' => '🏆 Ditubuhkan pada 2014 · Johor Bahru, Malaysia',
    ],
    'about.cold_chain_supply' => [
        'en' => 'Cold-Chain Sourcing & Supply',
        'zh' => '冷链采购与供应',
        'bm' => 'Penyumberan & Bekalan Rantaian Sejuk',
    ],
    'about.header_title' => [
        'en' => 'About Us — MST Import and Export Sdn. Bhd.',
        'zh' => '关于我们 — MST Import and Export Sdn. Bhd.',
        'bm' => 'Tentang Kami — MST Import and Export Sdn. Bhd.',
    ],
    'about.header_subtitle_strong' => [
        'en' => 'More Than a Supplier. Your Sourcing & Supply Partner.',
        'zh' => '不只是供应商 · 更是您的采购与供应合作伙伴',
        'bm' => 'Lebih Daripada Pembekal. Rakan Penyumberan & Bekalan Anda.',
    ],
    'about.header_subtitle_text' => [
        'en' => 'Supplying seafood, meat, frozen food and selected food ingredients to customers in Malaysia and Singapore, with plans to expand into regional and international markets.',
        'zh' => '供应海鲜、肉类、冷冻食品及精选食材，目前服务马来西亚与新加坡客户，并逐步拓展区域及国际市场。',
        'bm' => 'Membekalkan makanan laut, daging, makanan sejuk beku dan bahan makanan terpilih kepada pelanggan di Malaysia dan Singapura, dengan rancangan untuk mengembangkan pasaran ke peringkat serantau dan antarabangsa.',
    ],
    'about.motto' => [
        'en' => 'Flow with Integrity, Grow with Strength.',
        'zh' => '源于诚信，蓄力致远。',
        'bm' => 'Integriti Asas Kemajuan, Keteguhan Menjana Kejayaan.',
    ],
    'about.section_1_eyebrow' => [
        'en' => '1. ABOUT MST — WHO WE ARE',
        'zh' => '1. 走进 MST — 我们是谁',
        'bm' => '1. MENGENAI MST — SIAPA KAMI',
    ],
    'about.section_1_title' => [
        'en' => 'From Our Johor Bahru Roots Towards Regional & International Growth',
        'zh' => '从新山根基走向区域与国际发展',
        'bm' => 'Dari Johor Bahru ke Arah Pengembangan Serantau & Antarabangsa',
    ],
    'about.who_we_are_p1' => [
        'en' => '<strong>MST Import and Export Sdn. Bhd.</strong> is a Johor-based frozen food sourcing, trading and distribution company, supplying seafood, meat, frozen food and selected food ingredients to commercial customers.',
        'zh' => '<strong>MST Import and Export Sdn. Bhd.</strong> 是一家立足柔佛的冷冻食品采购、贸易与分销企业，为商业客户供应海鲜、肉类、冷冻食品及精选食材。',
        'bm' => '<strong>MST Import and Export Sdn. Bhd.</strong> ialah syarikat penyumberan, perdagangan dan pengedaran makanan sejuk beku yang berpangkalan di Johor, membekalkan makanan laut, daging, makanan sejuk beku dan bahan makanan terpilih kepada pelanggan komersial.',
    ],
    'about.who_we_are_p2' => [
        'en' => 'Founded in 2014 (formerly known as Mika Seafood Trading), the business evolved into <strong>MST Import and Export Sdn. Bhd. in 2024</strong>, marking a new stage of growth and expansion beyond traditional seafood trading.',
        'zh' => '企业创立于 2014 年（前身为 Mika Seafood Trading），并于 <strong>2024 年正式升级为 MST Import and Export Sdn. Bhd.</strong>，迈向超越传统海鲜贸易的全新发展与扩张阶段。',
        'bm' => 'Diasaskan pada tahun 2014 (dahulunya dikenali sebagai Mika Seafood Trading), perniagaan ini berkembang menjadi <strong>MST Import and Export Sdn. Bhd. pada tahun 2024</strong>, menandakan fasa pertumbuhan dan pengembangan baharu melangkaui perdagangan makanan laut tradisional.',
    ],
    'about.who_we_are_p3' => [
        'en' => 'Today, MST is building a stronger supply platform through <strong>cold storage, customised sourcing, reliable supply and distribution</strong>, serving customers in Malaysia and Singapore, with plans to expand into regional and international markets.',
        'zh' => '今天，MST 正通过<strong>冷库储存、定制化采购、可靠供应及配送</strong>构建更稳健的供应平台，目前服务马来西亚与新加坡客户，并逐步拓展区域及国际市场。',
        'bm' => 'Hari ini, MST sedang membina platform bekalan yang lebih kukuh melalui <strong>penyimpanan sejuk beku, penyumberan tersuai, bekalan dan pengedaran yang boleh dipercayai</strong>, menyokong pelanggan di Malaysia dan Singapura, dengan rancangan untuk mengembangkan pasaran ke peringkat serantau dan antarabangsa.',
    ],
    'about.company_motto_val' => [
        'en' => 'Flow with Integrity, Grow with Strength.',
        'zh' => '源于诚信，蓄力致远。',
        'bm' => 'Integriti Asas Kemajuan, Keteguhan Menjana Kejayaan.',
    ],
    'about.company_name_full' => [
        'en' => 'MST Import and Export Sdn. Bhd.',
        'zh' => 'MST Import and Export Sdn. Bhd.',
        'bm' => 'MST Import and Export Sdn. Bhd.',
    ],
    'about.strategic_platform' => [
        'en' => 'STRATEGIC PLATFORM',
        'zh' => '战略平台',
        'bm' => 'PLATFORM STRATEGIK',
    ],
    'about.strategic_title' => [
        'en' => 'Scalable Frozen Food Supply & Sourcing',
        'zh' => '可扩展的冷冻食品供应与采购',
        'bm' => 'Penyumberan & Bekalan Makanan Sejuk Beku Berskala',
    ],
    'about.strategic_desc' => [
        'en' => "Located in Johor's established industrial corridor, MST connects commercial kitchens, food businesses, wholesalers, distributors and commercial buyers with sourcing networks and reliable supply solutions.",
        'zh' => 'MST 位于柔佛成熟的工业走廊，通过优质采购网络与可靠供应方案，连接商业厨房、餐饮企业、批发商、分销商及商业客户。',
        'bm' => 'Terletak di koridor perindustrian Johor yang mantap, MST menghubungkan dapur komersial, perniagaan makanan, pemborong, pengedar dan pembeli komersial dengan rangkaian penyumberan dan penyelesaian bekalan yang boleh dipercayai.',
    ],
    'about.check_1' => [
        'en' => 'Temperature-Controlled Storage (-18°C to -25°C)',
        'zh' => '温控储存（-18°C 至 -25°C）',
        'bm' => 'Storan Kawalan Suhu (-18°C hingga -25°C)',
    ],
    'about.check_2' => [
        'en' => 'Tailored Product Specifications & Sourcing',
        'zh' => '定制规格与采购服务',
        'bm' => 'Spesifikasi Produk & Penyumberan Tersuai',
    ],
    'about.check_3' => [
        'en' => 'Serving Malaysia & Singapore with Cold-Chain Reliability',
        'zh' => '服务马来西亚与新加坡，具备可靠冷链能力',
        'bm' => 'Menyokong Malaysia & Singapura dengan Kebolehpercayaan Rantaian Sejuk',
    ],
    'about.section_2_eyebrow' => [
        'en' => '2. OUR JOURNEY — BRAND STORY',
        'zh' => '2. 发展历程 — 品牌故事',
        'bm' => '2. PERJALANAN KAMI — KISAH JENAMA',
    ],
    'about.section_2_title' => [
        'en' => 'Over a Decade of Growth',
        'zh' => '逾十年的稳步发展',
        'bm' => 'Pertumbuhan Lebih Sedekad',
    ],
    'about.section_2_desc' => [
        'en' => 'From our roots in frozen seafood trading in Johor Bahru to a growing multi-category frozen food sourcing and supply business.',
        'zh' => '从新山冷冻海鲜贸易根基出发，逐步成长为多品类冷冻食品采购与供应企业。',
        'bm' => 'Dari akar umbi perdagangan makanan laut sejuk beku di Johor Bahru ke perniagaan penyumberan dan pembekalan makanan sejuk beku pelbagai kategori yang semakin berkembang.',
    ],
    'about.timeline_2014_title' => [
        'en' => 'Johor Bahru Roots',
        'zh' => '立足新山',
        'bm' => 'Akar Umbi Johor Bahru',
    ],
    'about.timeline_2014_desc' => [
        'en' => '<strong>Mika Seafood Trading</strong> began its journey in Johor Bahru, focusing on frozen seafood supply and building long-term relationships with customers and suppliers.',
        'zh' => '<strong>Mika Seafood Trading</strong> 在新山开启业务，专注于冷冻海鲜供应，并与客户及供应商建立长期互信合作。',
        'bm' => '<strong>Mika Seafood Trading</strong> memulakan perjalanannya di Johor Bahru, memfokuskan kepada bekalan makanan laut sejuk beku dan membina hubungan jangka panjang dengan pelanggan dan pembekal.',
    ],
    'about.timeline_2024_title' => [
        'en' => 'A New Chapter',
        'zh' => '崭新篇章',
        'bm' => 'Bab Baharu',
    ],
    'about.timeline_2024_desc' => [
        'en' => 'Mika Seafood Trading transitioned into <strong>MST Import and Export Sdn. Bhd.</strong>, expanding beyond traditional seafood trading into a broader frozen food sourcing, trading and supply business.',
        'zh' => 'Mika Seafood Trading 正式转型为 <strong>MST Import and Export Sdn. Bhd.</strong>，业务从传统海鲜贸易拓展至更广泛的冷冻食品采购、贸易与供应领域。',
        'bm' => 'Mika Seafood Trading beralih kepada <strong>MST Import and Export Sdn. Bhd.</strong>, berkembang melangkaui perdagangan makanan laut tradisional kepada perniagaan penyumberan, perdagangan dan pembekalan makanan sejuk beku yang lebih luas.',
    ],
    'about.timeline_today_badge' => [
        'en' => 'Today',
        'zh' => '当前',
        'bm' => 'Hari Ini',
    ],
    'about.timeline_today_title' => [
        'en' => 'SILC Hub Facility',
        'zh' => 'SILC 运营枢纽',
        'bm' => 'Kemudahan Hab SILC',
    ],
    'about.timeline_today_desc' => [
        'en' => 'Our <strong>SILC facility</strong> represents the next stage of our development, strengthening our cold storage, product handling, packing and distribution capabilities.',
        'zh' => '位于 <strong>SILC 的设施</strong> 标志着我们发展的新阶段，进一步强化了冷库储存、产品处理、包装及配送能力。',
        'bm' => '<strong>Kemudahan SILC</strong> kami mewakili fasa perkembangan seterusnya, mengukuhkan keupayaan penyimpanan sejuk beku, pengendalian produk, pembungkusan dan pengedaran kami.',
    ],
    'about.timeline_future_badge' => [
        'en' => 'The Future',
        'zh' => '未来展望',
        'bm' => 'Masa Depan',
    ],
    'about.timeline_future_title' => [
        'en' => 'Beyond Borders',
        'zh' => '迈向更广市场',
        'bm' => 'Melangkaui Sempadan',
    ],
    'about.timeline_future_desc' => [
        'en' => 'We are preparing MST for future regional development, with a scalable supply platform designed to support growing customer requirements and cross-border opportunities over time.',
        'zh' => '我们正推进 MST 的未来区域发展规划，建立可扩展的供应平台，以随着时间推进支持日益增长的客户需求及跨境合作机会。',
        'bm' => 'Kami sedang menyediakan MST untuk pembangunan serantau masa depan, dengan platform bekalan berskala yang direka untuk menyokong keperluan pelanggan yang semakin berkembang serta peluang rentas sempadan dari semasa ke semasa.',
    ],
    'about.section_3_eyebrow' => [
        'en' => '3. MST AT A GLANCE — COMPANY FACTS',
        'zh' => '3. 概览 MST — 企业关键信息',
        'bm' => '3. IMBASAN MST — FAKTA SYARIKAT',
    ],
    'about.section_3_title' => [
        'en' => 'Fast Facts & Infrastructure',
        'zh' => '企业概览与基础设施',
        'bm' => 'Fakta Ringkas & Infrastruktur',
    ],
    'about.section_3_desc' => [
        'en' => 'Key facts about our business and operating capabilities.',
        'zh' => '关于我们业务与运营能力的关键概览。',
        'bm' => 'Fakta utama mengenai perniagaan dan keupayaan operasi kami.',
    ],
    'about.fact_established' => [
        'en' => 'Established',
        'zh' => '创立年份',
        'bm' => 'Ditubuhkan',
    ],
    'about.fact_entity' => [
        'en' => 'Corporate Entity',
        'zh' => '企业主体',
        'bm' => 'Entiti Korporat',
    ],
    'about.fact_entity_val' => [
        'en' => 'MST Import and Export Sdn. Bhd.',
        'zh' => 'MST Import and Export Sdn. Bhd.',
        'bm' => 'MST Import and Export Sdn. Bhd.',
    ],
    'about.fact_based' => [
        'en' => 'Based in',
        'zh' => '运营基地',
        'bm' => 'Berpangkalan di',
    ],
    'about.fact_based_val' => [
        'en' => 'Iskandar Puteri, Johor',
        'zh' => '柔佛依斯干达公主城',
        'bm' => 'Iskandar Puteri, Johor',
    ],
    'about.fact_business' => [
        'en' => 'Business',
        'zh' => '核心业务',
        'bm' => 'Perniagaan',
    ],
    'about.fact_business_val' => [
        'en' => 'Sourcing, Trading & Supply',
        'zh' => '采购 · 贸易 · 供应',
        'bm' => 'Penyumberan, Perdagangan & Bekalan',
    ],
    'about.fact_products' => [
        'en' => 'Core Products',
        'zh' => '核心品类',
        'bm' => 'Produk Teras',
    ],
    'about.fact_products_val' => [
        'en' => 'Seafood · Meat · Frozen Food · Food Ingredients',
        'zh' => '海鲜 · 肉类 · 冷冻食品 · 食材',
        'bm' => 'Makanan Laut · Daging · Makanan Sejuk Beku · Bahan Makanan',
    ],
    'about.fact_cold_storage' => [
        'en' => 'Cold Storage',
        'zh' => '冷库储存',
        'bm' => 'Penyimpanan Sejuk Beku',
    ],
    'about.fact_cold_storage_val' => [
        'en' => 'Over 50 Tonnes',
        'zh' => '超过 50 吨',
        'bm' => 'Melebihi 50 Tan',
    ],
    'about.fact_cold_storage_temp' => [
        'en' => '(-18°C to -25°C)',
        'zh' => '（-18°C 至 -25°C）',
        'bm' => '(-18°C hingga -25°C)',
    ],
    'about.fact_facility' => [
        'en' => 'Primary Facility',
        'zh' => '主要设施',
        'bm' => 'Kemudahan Utama',
    ],
    'about.fact_facility_val' => [
        'en' => 'SILC, Iskandar Puteri',
        'zh' => '依斯干达公主城 SILC 园区',
        'bm' => 'SILC, Iskandar Puteri',
    ],
    'about.fact_market' => [
        'en' => 'Market Focus',
        'zh' => '目标市场',
        'bm' => 'Fokus Pasaran',
    ],
    'about.fact_market_val' => [
        'en' => 'Malaysia & Singapore',
        'zh' => '马来西亚与新加坡',
        'bm' => 'Malaysia & Singapura',
    ],
    'about.section_4_eyebrow' => [
        'en' => '4. WHAT DEFINES MST — CURRENT CAPABILITIES',
        'zh' => '4. MST 的核心优势 — 当前运营能力',
        'bm' => '4. KELEBIHAN MST — KEUPAYAAN SEMASA',
    ],
    'about.section_4_title' => [
        'en' => 'Five Pillars of Operational Reliability',
        'zh' => '五大运营可靠性支柱',
        'bm' => 'Lima Tonggak Kebolehpercayaan Operasi',
    ],
    'about.section_4_desc' => [
        'en' => 'At MST Import and Export Sdn. Bhd., we go beyond supplying frozen food. We focus on product quality, reliable sourcing, cold-chain integrity and consistent supply, giving our customers greater confidence from sourcing to delivery.',
        'zh' => '在 MST Import and Export Sdn. Bhd.，我们不仅提供冷冻食品，更注重产品品质、可靠采购、温控规范及稳定供应，为客户从采购到交付各环节提供充分信心。',
        'bm' => 'Di MST Import and Export Sdn. Bhd., kami melangkaui sekadar membekalkan makanan sejuk beku. Kami memberi tumpuan kepada kualiti produk, penyumberan yang boleh dipercayai, integriti rantaian sejuk dan bekalan yang konsisten, memberi pelanggan keyakinan dari penyumberan hingga penghantaran.',
    ],
    'about.pillar_1_title' => [
        'en' => 'QUALITY & FOOD SAFETY',
        'zh' => '品质与食品安全',
        'bm' => 'KUALITI & KESELAMATAN MAKANAN',
    ],
    'about.pillar_1_desc' => [
        'en' => 'We source seafood, meat, frozen food and food ingredients according to product specifications and customer requirements. We work with established suppliers and processing partners based on HACCP/GMP principles to support consistent product quality and food-safety requirements.',
        'zh' => '我们根据产品规格和客户需求采购海鲜、肉类、冷冻食品及食材，并基于 HACCP 与 GMP 规范原则与优质供应商及加工伙伴合作，以支持稳定的产品质量和食品安全要求。',
        'bm' => 'Kami memperoleh makanan laut, daging, makanan sejuk beku dan bahan makanan mengikut spesifikasi produk dan keperluan pelanggan. Kami bekerjasama dengan pembekal dan rakan pemprosesan berasaskan prinsip HACCP/GMP untuk menyokong kualiti produk dan keperluan keselamatan makanan yang konsisten.',
    ],
    'about.pillar_2_title' => [
        'en' => 'CUSTOMISED SOURCING',
        'zh' => '定制化采购',
        'bm' => 'PENYUMBERAN TERSUAI',
    ],
    'about.pillar_2_desc' => [
        'en' => "Can't find what you need? Tell us what you are looking for — including product type, specifications, pack size, origin and quantity. Our sourcing network allows us to identify suitable products and supply options according to your requirements.",
        'zh' => '未找到所需产品？请告诉我们您的具体需求——包括产品品类、规格要求、包装规格、原产地及采购量。我们的采购网络将根据您的需求协助寻找合适的产品与供应方案。',
        'bm' => 'Tidak menemui apa yang anda perlukan? Beritahu kami apa yang anda cari — termasuk jenis produk, spesifikasi, saiz pek, negara asal dan kuantiti. Rangkaian penyumberan kami membantu mengenal pasti produk dan pilihan bekalan yang sesuai mengikut keperluan anda.',
    ],
    'about.pillar_3_title' => [
        'en' => 'TEMPERATURE-CONTROLLED OPERATIONS',
        'zh' => '温控运营与管理',
        'bm' => 'OPERASI KAWALAN SUHU',
    ],
    'about.pillar_3_desc' => [
        'en' => 'Our operations support frozen storage (-18°C to -25°C), temperature-controlled handling, order preparation, packing and applicable logistics coordination according to product and customer requirements.',
        'zh' => '我们的运营支持冷冻储存（-18°C 至 -25°C）、温度受控处理、订单准备、包装，以及根据产品和客户需求进行相关物流协调。',
        'bm' => 'Operasi kami menyokong penyimpanan produk sejuk beku (-18°C hingga -25°C), pengendalian suhu terkawal, penyediaan pesanan, pembungkusan serta penyelarasan logistik yang berkenaan mengikut keperluan produk dan pelanggan.',
    ],
    'about.pillar_4_title' => [
        'en' => 'SUPPLY & DELIVERY COORDINATION',
        'zh' => '供应与配送协调',
        'bm' => 'BEKALAN & PENYELARASAN PENGHANTARAN',
    ],
    'about.pillar_4_desc' => [
        'en' => 'Our operations cover receiving, storage, packing, order preparation and delivery coordination according to customer requirements. With our cold storage and distribution facility at SILC, we support regular commercial supply as well as tailored sourcing requirements.',
        'zh' => '我们的运营涵盖接收、储存、包装、订单准备及根据客户需求进行配送协调。依托位于 SILC 的储存设施，我们支持常规商业供应以及定制化采购需求。',
        'bm' => 'Operasi kami merangkumi penerimaan, penyimpanan, pembungkusan, penyediaan pesanan serta penyelarasan penghantaran mengikut keperluan pelanggan. Dengan kemudahan storan kami di SILC, kami menyokong bekalan komersial tetap serta keperluan penyumberan tersuai.',
    ],
    'about.pillar_5_title' => [
        'en' => 'BUILT TO SCALE',
        'zh' => '具备规模扩展能力',
        'bm' => 'DIBINA UNTUK BERKEMBANG',
    ],
    'about.pillar_5_desc' => [
        'en' => 'Our infrastructure and sourcing capabilities are designed to support increasing volumes and future regional development. Whether you are a restaurant, food business, wholesaler, distributor or commercial buyer, we aim to be a reliable long-term sourcing and supply partner.',
        'zh' => '我们的基础设施与采购能力旨在支持业务量的逐步提升以及未来区域发展。无论您是餐厅、餐饮企业、批发商、分销商还是商业买家，我们都致力于成为您值得信赖的长期采购与供应伙伴。',
        'bm' => 'Infrastruktur dan keupayaan penyumberan kami direka untuk menyokong peningkatan volum serta perkembangan serantau masa hadapan. Sama ada anda sebuah restoran, perniagaan makanan, pemborong, pengedar atau pembeli komersial, kami berhasrat untuk menjadi rakan penyumberan dan bekalan jangka panjang yang boleh dipercayai.',
    ],
    'about.section_5_eyebrow' => [
        'en' => '5. OUR FOCUS & CREED',
        'zh' => '5. 经营理念与准则',
        'bm' => '5. FOKUS & PEGANGAN KAMI',
    ],
    'about.creed_title' => [
        'en' => 'Quality Products. Reliable Supply. Fair Value. Consistent Service.',
        'zh' => '优质产品 · 可靠供应 · 公平价值 · 始终如一的服务',
        'bm' => 'Produk Berkualiti · Bekalan Boleh Dipercayai · Nilai Saksama · Perkhidmatan Konsisten',
    ],
    'about.quote_en' => [
        'en' => '“We believe that long-term business relationships are built on trust, integrity and reliability.”',
        'zh' => '“我们深信，长久稳固的商业合作建立在信任、诚信与可靠的基础之上。”',
        'bm' => '“Kami percaya bahawa hubungan perniagaan jangka panjang dibina atas dasar kepercayaan, integriti dan kebolehpercayaan.”',
    ],
    'about.company_motto_label' => [
        'en' => 'COMPANY MOTTO',
        'zh' => '企业箴言',
        'bm' => 'MOTO SYARIKAT',
    ],
    'about.leadership_eyebrow' => [
        'en' => 'MANAGEMENT',
        'zh' => '管理团队',
        'bm' => 'PENGURUSAN',
    ],
    'about.exec_leadership' => [
        'en' => 'Management',
        'zh' => '管理团队',
        'bm' => 'Pengurusan',
    ],
    'about.wendy_title' => [
        'en' => 'Director / Managing Director',
        'zh' => '董事 / 董事总经理',
        'bm' => 'Pengarah / Pengarah Urusan',
    ],
    'about.wendy_bio_1' => [
        'en' => 'Wendy Chiam is responsible for MST’s strategic development, sourcing and supplier relationships, commercial customer relationships and business expansion. She also drives the development of the company’s cold-chain infrastructure and supply capabilities.',
        'zh' => 'Wendy Chiam 负责 MST 的战略发展、采购与供应商关系、商业客户关系及业务拓展，并推动公司冷链基础设施与供应能力的发展。',
        'bm' => 'Wendy Chiam bertanggungjawab terhadap pembangunan strategik MST, perolehan dan hubungan pembekal, hubungan pelanggan komersial serta pengembangan perniagaan. Beliau turut memacu pembangunan infrastruktur rantaian sejuk dan keupayaan bekalan syarikat.',
    ],
    'about.wendy_bio_2' => [
        'en' => '',
        'zh' => '',
        'bm' => '',
    ],
    'about.connect_team' => [
        'en' => 'Connect With Our Team →',
        'zh' => '联系我们的团队 →',
        'bm' => 'Hubungi Pasukan Kami →',
    ],
    'about.cta_title' => [
        'en' => 'Looking for a reliable supply partner?',
        'zh' => '寻找可靠的供应与采购伙伴？',
        'bm' => 'Mencari rakan bekalan yang boleh dipercayai?',
    ],
    'about.cta_desc' => [
        'en' => "From regular commercial supply to tailored sourcing requirements, let's talk business.",
        'zh' => '从常规商业供应到定制化采购需求，欢迎与我们洽谈合作。',
        'bm' => 'Daripada bekalan komersial tetap hingga keperluan penyumberan tersuai, mari berbincang tentang perniagaan.',
    ],
    'about.explore_products' => [
        'en' => 'Explore Products',
        'zh' => '浏览产品',
        'bm' => 'Terokai Produk',
    ],
    'about.request_quote' => [
        'en' => 'Request a Quote →',
        'zh' => '获取报价 →',
        'bm' => 'Minta Sebut Harga →',
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

foreach ($data as $key => $vals) {
    $prefixes = [$key];
    if (!str_starts_with($key, 'about.about.')) {
        $prefixes[] = 'about.' . $key;
    }

    foreach ($prefixes as $k) {
        $jsonContents['en'][$k] = $vals['en'];
        $jsonContents['zh'][$k] = $vals['zh'];
        $jsonContents['bm'][$k] = $vals['bm'];
        $jsonContents['ms'][$k] = $vals['bm'];
    }
}

// Global replace of 客制化 with 定制化 in zh.json
foreach ($jsonContents['zh'] as $k => $v) {
    if (is_string($v) && str_contains($v, '客制化')) {
        $jsonContents['zh'][$k] = str_replace('客制化', '定制化', $v);
        echo "Replaced 客制化 in zh.json key: {$k}\n";
    }
}

foreach ($jsonPaths as $loc => $p) {
    file_put_contents($p, json_encode($jsonContents[$loc], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo "Saved {$p}\n";
}

echo "\n=== Updating MySQL Database ===\n";
foreach ($data as $fullKey => $vals) {
    $group = str_contains($fullKey, '.') ? explode('.', $fullKey, 2)[0] : 'about';
    $subKey = str_contains($fullKey, '.') ? explode('.', $fullKey, 2)[1] : $fullKey;

    // We store both the short key and full key under group 'about' and group 'common'
    $keyVariants = [
        ['group' => $group, 'key' => $subKey],
        ['group' => 'about', 'key' => $fullKey],
        ['group' => 'common', 'key' => $fullKey],
        ['group' => 'about', 'key' => 'about.' . $fullKey],
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

// Global replace of 客制化 in MySQL translations
$allZhRows = Translation::where('text_zh', 'LIKE', '%客制化%')->get();
foreach ($allZhRows as $row) {
    $row->text_zh = str_replace('客制化', '定制化', $row->text_zh);
    $row->save();
    echo "MySQL updated 客制化 -> 定制化 for key: {$row->group}.{$row->key}\n";
}

echo "\n=== Updating SQLite Database ===\n";
$sqlitePath = database_path('database.sqlite');
if (file_exists($sqlitePath)) {
    $pdo = new PDO("sqlite:{$sqlitePath}");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO translations (`group`, `key`, text_en, text_zh, text_bm, created_at, updated_at) 
                           VALUES (:group, :key, :text_en, :text_zh, :text_bm, datetime('now'), datetime('now'))
                           ON CONFLICT(`group`, `key`) DO UPDATE SET text_en = :text_en, text_zh = :text_zh, text_bm = :text_bm, updated_at = datetime('now')");

    foreach ($data as $fullKey => $vals) {
        $group = str_contains($fullKey, '.') ? explode('.', $fullKey, 2)[0] : 'about';
        $subKey = str_contains($fullKey, '.') ? explode('.', $fullKey, 2)[1] : $fullKey;

        $keyVariants = [
            ['group' => $group, 'key' => $subKey],
            ['group' => 'about', 'key' => $fullKey],
            ['group' => 'common', 'key' => $fullKey],
            ['group' => 'about', 'key' => 'about.' . $fullKey],
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

    // Replace 客制化 in SQLite
    $res = $pdo->query("SELECT id, `group`, `key`, text_zh FROM translations WHERE text_zh LIKE '%客制化%'");
    $updateStmt = $pdo->prepare("UPDATE translations SET text_zh = :text_zh, updated_at = datetime('now') WHERE id = :id");
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $newVal = str_replace('客制化', '定制化', $row['text_zh']);
        $updateStmt->execute([':text_zh' => $newVal, ':id' => $row['id']]);
        echo "SQLite updated 客制化 -> 定制化 for key: {$row['group']}.{$row['key']}\n";
    }
    echo "SQLite database synced.\n";
}

// Clear translation cache
app(TranslationService::class)->clearCache();
echo "\nTranslation cache cleared!\n";
