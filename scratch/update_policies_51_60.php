<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Policy;
use Illuminate\Support\Facades\DB;

// Update policies with clean, legally accurate content matching Sections 51-60

// 1. Terms & Conditions
$termsEn = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Effective Date:</strong> September 25, 2026 | <strong>Last Updated:</strong> September 30, 2026
</div>

<p>Welcome to <strong>MST Import and Export Sdn. Bhd.</strong> (“MST”, “we”, “us”, or “our”).</p>

<p>By accessing our website, making inquiries, requesting quotations (RFQ), submitting orders, purchasing products, or using our sourcing, delivery or collection services, you agree to be bound by these Terms & Conditions.</p>

<h2>1. Our Business</h2>
<p>MST supplies frozen seafood, meat, frozen food, food ingredients, wholesale and retail supply, custom sourcing, and related trading services.</p>
<p>Our services are available to business-to-business (B2B), wholesale, and retail (B2C) customers.</p>

<h2>2. Website Information</h2>
<p>We make reasonable efforts to maintain accurate information on our website.</p>
<p>However, product availability, packaging, branding, origin, specifications, sizes, weights, images, and other product information may vary or change due to supplier or market conditions.</p>
<p>Product images are for illustration purposes unless otherwise stated.</p>

<h2>3. Product Availability</h2>
<p>All products are subject to availability.</p>
<p>Listing a product on the website does not guarantee immediate availability.</p>
<p>MST may offer a substitute product or alternative arrangement where appropriate, subject to customer agreement.</p>

<h2>4. Prices</h2>
<p>Prices may vary according to:</p>
<ul>
  <li>Market conditions</li>
  <li>Supplier pricing</li>
  <li>Quantity</li>
  <li>Unit of Sale and Pack Size</li>
  <li>Product availability</li>
  <li>Delivery location</li>
  <li>Packaging</li>
  <li>Customer type</li>
  <li>Wholesale requirements</li>
  <li>Custom sourcing requirements</li>
  <li>Agreed commercial terms</li>
</ul>
<p>Prices displayed or quoted may change before an order is confirmed. The confirmed order price is the price applicable at the time the order is formally confirmed by MST.</p>

<h2>5. Quotations, RFQs & Order Requests</h2>
<p>A quotation, RFQ (Request for Quotation) or order request does not by itself constitute acceptance of an order.</p>
<p>An order is confirmed only when MST formally confirms the order and the applicable commercial terms. Product availability, specifications and pricing remain subject to confirmation until the order is confirmed.</p>
<p>Quoted prices are valid only for the period stated in the quotation and remain subject to product availability and the applicable commercial terms.</p>

<h2>6. Wholesale and B2B Orders</h2>
<p>B2B and wholesale orders may be subject to:</p>
<ul>
  <li>Minimum order quantities (MOQ) where applicable</li>
  <li>Minimum delivery reference thresholds (RM350 B2B reference threshold)</li>
  <li>Payment terms</li>
  <li>Credit terms where approved</li>
  <li>Delivery arrangements</li>
  <li>Customer-specific pricing</li>
  <li>Other agreed commercial conditions</li>
</ul>
<p>Wholesale registration and approval do not by themselves guarantee a fixed price, universal discount, credit terms, MOQ or product availability. Applicable pricing and commercial terms may vary according to the customer, product, quantity, specifications, destination, availability and other agreed requirements.</p>

<h2>7. Trading Sourcing & Supply</h2>
<p>Trading sourcing and supply requirements (including container, pallet and commercial supply) may be subject to product specifications, minimum quantities, destination requirements, supplier availability, logistics arrangements and other applicable commercial requirements. Registration or approval of a Trading Account does not by itself guarantee a fixed universal trading price or credit terms; pricing and commercial terms are provided according to specific transaction requirements and confirmed commercial agreements.</p>

<h2>8. B2C Orders</h2>
<p>Retail customers may purchase products subject to product availability, applicable delivery thresholds (RM100 B2C reference threshold), delivery or collection arrangements and payment confirmation.</p>

<h2>9. Custom Sourcing</h2>
<p>MST may source products that are not listed on the website upon customer request as a specialized sourcing service.</p>
<p>Custom Sourcing is a service requirement, not a standard product category, and is subject to supplier availability, pricing, minimum quantities, specifications, lead time and applicable arrangements.</p>
<p>Submitting a custom sourcing request or RFQ does not guarantee product availability, successful sourcing or supply.</p>
<p>Once a custom-sourced order has been specifically confirmed, cancellation, return or refund may be subject to the agreed commercial terms and our Refund & Return Policy.</p>

<h2>10. Order Acceptance & Final Confirmation</h2>
<p>MST reserves the right to decline or cancel an order request where reasonably necessary, including where product is unavailable, information provided is inaccurate, payment cannot be verified, supplier availability changes, delivery cannot reasonably be arranged, or commercial terms cannot be agreed.</p>
<p>The final confirmed order establishes the applicable product, quantity, unit of sale, unit price, settlement currency (RM base), fulfilment method (delivery or self-collection), delivery/transportation fee where applicable, and other agreed commercial terms.</p>

<h2>11. Payment & Settlement Currency</h2>
<p>MST’s base settlement currency is Ringgit Malaysia (RM / MYR). Other currencies (such as SGD or USD) may be displayed for reference purposes only.</p>
<p>Payment must be completed according to the agreed payment terms. For approved B2B accounts, separate credit or invoice terms may apply.</p>

<h2>12. Delivery & Self-Collection</h2>
<p>Local door-to-door delivery is available based on standard reference delivery thresholds:</p>
<ul>
  <li><strong>B2B / Wholesale:</strong> Reference threshold RM350</li>
  <li><strong>B2C / Retail:</strong> Reference threshold RM100</li>
</ul>
<p>These amounts serve as delivery reference thresholds and do not function as hard minimum-order restrictions. Orders below these amounts may still be considered/accepted where delivery service is available, subject to an applicable transportation or delivery charge based on the delivery location / zone and logistics requirements.</p>
<p>Walk-in Express orders are strictly for self-collection at our SILC facility and do not receive delivery options.</p>

<h2>13. Customer Responsibility After Delivery</h2>
<p>Customers must provide accurate delivery information and ensure that an authorised person is available to receive the order. Frozen products should be transferred to appropriate frozen storage (-18°C or below) promptly after delivery or collection.</p>

<h2>14. Refund and Return</h2>
<p>Refunds, returns, and damaged/incorrect items are handled in accordance with our <strong>Refund & Return Policy</strong>.</p>
<p>Because frozen seafood, meat, and food products are perishable and temperature-sensitive, issues must be reported promptly (within 12 hours of delivery or collection) with photographic evidence.</p>

<h2>15. Intellectual Property</h2>
<p>All content on this website, including text, graphics, logos, images, and software, is the property of MST or its content suppliers and is protected by applicable intellectual property laws.</p>

<h2>16. Limitation of Liability</h2>
<p>To the maximum extent permitted by applicable law, MST shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or related to your use of our website, products, or services.</p>

<h2>17. Governing Law</h2>
<p>These Terms & Conditions shall be governed by and construed in accordance with the laws of Malaysia. Any disputes arising under or in connection with these Terms shall be subject to the jurisdiction of the courts of Malaysia.</p>

<h2>18. Contact Us</h2>
<p>If you have questions regarding these Terms & Conditions, please contact us at:</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.</strong><br>
  镁嘉国际贸易有限公司<br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  Email: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  Phone / WhatsApp: +60 13-280 0168 / +60 11-1271 0260
</p>
HTML;

$termsZh = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>生效日期：</strong>2026年9月25日 | <strong>最后更新：</strong>2026年9月30日
</div>

<p>欢迎访问 <strong>MST Import and Export Sdn. Bhd.</strong>（镁嘉国际贸易有限公司，以下简称“MST”、“我们”或“我们的”）官方网站。</p>

<p>通过访问我们的网站、提交咨询、发送采购需求单（RFQ）、索取报价、下订单、购买产品或使用我们的采购、配送或自提服务，即表示您同意并接受本《条款与条件》。</p>

<h2>1. 业务范围</h2>
<p>MST 供应冷冻海鲜、肉类、冷冻食品、调理食材，提供批发与零售供应、定制化采购及相关贸易服务。</p>
<p>我们的服务面向企业商业客户（B2B）、批发客户以及零售消费者（B2C）。</p>

<h2>2. 网站信息与产品展示</h2>
<p>我们尽合理努力确保网站信息的准确性。</p>
<p>然而，产品库存、包装规格、品牌、产地、规格参数、重量、图片及其他信息可能会因供应商或市场条件发生调整。除另有说明外，产品图片仅供参考。</p>

<h2>3. 产品供应与库存</h2>
<p>所有产品供应均视库存情况而定。</p>
<p>网站上的产品展示并不构成现货库存的永久保证。在合适情况下，经客户同意，MST 可提供替代产品或替代供应方案。</p>

<h2>4. 价格机制</h2>
<p>产品价格可能根据以下因素有所调整：</p>
<ul>
  <li>市场行情</li>
  <li>供应商价格变动</li>
  <li>采购数量</li>
  <li>销售单位与包装规格</li>
  <li>产品库存情况</li>
  <li>配送地点与物流要求</li>
  <li>客户账户类型</li>
  <li>批发要求</li>
  <li>定制化采购要求</li>
  <li>约定的商业条款</li>
</ul>
<p>在订单正式确认前，展示或提供的报价可能会有所变动。最终确认的订单价格以 MST 正式确认订单时适用的价格为准。</p>

<h2>5. 报价单、RFQ 询价与订购申请</h2>
<p>报价单、RFQ 询价单或订购申请本身并不构成订单的确认接受。</p>
<p>只有当 MST 审核并正式确认该订单及适用的商业条款后，订单方告成立。在订单正式确认前，产品库存、规格参数及价格均须经最终确认。</p>
<p>报价仅在报价单上载明的有效期内有效，并受产品库存供应及相关商业条款的约束。</p>

<h2>6. 批发与 B2B 商业订单</h2>
<p>B2B 与批发订单可能受以下条款约束：</p>
<ul>
  <li>最低起订量（MOQ，如适用）</li>
  <li>配送参考门槛（B2B 批发参考门槛 RM350）</li>
  <li>付款条件</li>
  <li>经批准的信用账期</li>
  <li>配送与物流安排</li>
  <li>客户专属商业价格</li>
  <li>其他约定的商业条件</li>
</ul>
<p>批发账户的注册与审核通过，本身并不保证获得固定特惠价格、统一折扣、信用账期、最低起订量（MOQ）或产品现货供应。适用的商业价格及条款将根据客户类型、产品品类、订购量、产品规格、目的地、库存情况及其他商定要求进行评估确定。</p>

<h2>7. 贸易采购与供应</h2>
<p>贸易采购与供应需求（包括货柜、托盘及商业大宗供应）须根据产品规格、最低起订量、目的地要求、供应商货源、物流安排及其他适用商业要求进行评估。贸易账户的注册或审核通过本身并不保证获得固定的通用贸易价格或信用条款；商业价格与条款根据具体交易需求与最终确认的商业协议执行。</p>

<h2>8. 零售 B2C 订单</h2>
<p>零售客户可根据产品库存情况、适用配送门槛（B2C 零售参考门槛 RM100）、配送或自提安排以及付款确认购买产品。</p>

<h2>9. 定制化采购服务</h2>
<p>MST 可根据客户需求提供未列于网站上的产品定制化采购服务。</p>
<p>定制化采购属于专项采购与定制服务，而非普通现货产品分类，须视供应商货源、价格、起订量、产品规格、交付周期及相关安排而定。</p>
<p>提交定制化采购申请或 RFQ 询价并不保证一定能成功采购或供应。</p>
<p>定制化采购订单一旦经双方正式确认，其取消、退货或退款将严格依照约定的商业条款及《退款与退换政策》执行。</p>

<h2>10. 订单接受与最终确认</h2>
<p>在合理必要的情况下，MST 保留拒绝或取消订购申请的权利，包括产品缺货、提供的信息不准确、无法核实付款、供应商货源变动、无法合理安排配送或无法达成商业条款等情形。</p>
<p>最终确认的订单确立适用的产品、数量、销售单位、单价、结算货币（RM 基础货币）、履行方式（配送或自提）、适用的配送/运输费用以及其他约定的商业条款。</p>

<h2>11. 结算货币与支付</h2>
<p>MST 的基础结算货币为马来西亚令吉（RM / MYR）。其他货币（如 SGD 或 USD）仅作为参考显示。</p>
<p>客户须根据约定的付款条件完成付款。对于已获批准的 B2B 账户，可适用独立的账期或发票条款。</p>

<h2>12. 配送与自提安排</h2>
<p>本地门到门冷链配送基于以下标准参考门槛提供：</p>
<ul>
  <li><strong>B2B / 批发客户：</strong>参考起送门槛 RM350</li>
  <li><strong>B2C / 零售客户：</strong>参考起送门槛 RM100</li>
</ul>
<p>上述金额为配送参考门槛，并非硬性最低订购限制。在有配送服务的前提下，低于参考门槛的订单仍可受理，须根据配送区域及物流要求加收适用的运输或配送费用。</p>
<p>Walk-in 门店订单仅限在 SILC 园区设施现场自提，不提供配送选项。</p>

<h2>13. 交付后的客户责任</h2>
<p>客户须提供准确的配送信息，并确保在交付时有指定人员接收货物。冷冻产品在交付或自提后应立即转入符合标准的冷冻储存设施（-18°C 或更低）。</p>

<h2>14. 退款与退换货</h2>
<p>退款、退换货以及破损/错发商品的处理均依照我们的<strong>《退款与退换政策》</strong>执行。</p>
<p>鉴于冷冻海鲜、肉类及冷冻食材属于易腐及温控敏感商品，任何问题须在收货或自提后 12 小时内提出并附上照片证明。</p>

<h2>15. 知识产权</h2>
<p>本网站上的所有内容，包括文本、图形、标识、图像及软件，均为 MST 或其内容提供商的财产，受相关知识产权法保护。</p>

<h2>16. 责任限制</h2>
<p>在适用法律允许的最大范围内，MST 对因使用我们的网站、产品或服务而引起的任何间接、附带、特殊、后果性或惩罚性损害不承担责任。</p>

<h2>17. 准据法与管辖权</h2>
<p>本《条款与条件》受马来西亚法律管辖并按其解释。因本条款引起的或与本条款有关的任何争议均应提交马来西亚法院管辖。</p>

<h2>18. 联系我们</h2>
<p>如对本《条款与条件》有任何疑问，请联系我们：</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.</strong><br>
  镁嘉国际贸易有限公司<br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  电邮：<a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  电话 / WhatsApp：+60 13-280 0168 / +60 11-1271 0260
</p>
HTML;

$termsBm = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Tarikh Berkuat Kuasa:</strong> 25 September 2026 | <strong>Terakhir Dikemas Kini:</strong> 30 September 2026
</div>

<p>Selamat datang ke <strong>MST Import and Export Sdn. Bhd.</strong> (“MST”, “kami” atau “kita”).</p>

<p>Dengan mengakses laman web kami, membuat pertanyaan, meminta sebut harga (RFQ), menghantar pesanan, membeli produk, atau menggunakan perkhidmatan penyumberan, penghantaran atau pengambilan kami, anda bersetuju untuk terikat dengan Terma & Syarat ini.</p>

<h2>1. Perniagaan Kami</h2>
<p>MST membekalkan makanan laut sejuk beku, daging, makanan sejuk beku, bahan makanan, bekalan borong dan runcit, penyumberan tersuai, serta perkhidmatan dagangan yang berkaitan.</p>
<p>Perkhidmatan kami disediakan untuk pelanggan perniagaan-ke-perniagaan (B2B), pemborong, dan pelanggan runcit (B2C).</p>

<h2>2. Maklumat Laman Web</h2>
<p>Kami berusaha secara munasabah untuk mengekalkan maklumat yang tepat di laman web kami.</p>
<p>Walau bagaimanapun, ketersediaan produk, pembungkusan, penjenamaan, negara asal, spesifikasi, saiz, berat, imej dan maklumat produk lain mungkin berbeza atau berubah mengikut keadaan pembekal atau pasaran. Imej produk adalah untuk tujuan ilustrasi melainkan dinyatakan sebaliknya.</p>

<h2>3. Ketersediaan Produk</h2>
<p>Semua produk adalah tertakluk kepada ketersediaan stok.</p>
<p>Penyenaraian produk di laman web tidak menjamin ketersediaan serta-merta. MST boleh menawarkan produk pengganti atau susunan alternatif yang sesuai dengan persetujuan pelanggan.</p>

<h2>4. Harga</h2>
<p>Harga produk boleh berbeza mengikut:</p>
<ul>
  <li>Keadaan pasaran</li>
  <li>Harga pembekal</li>
  <li>Kuantiti pesanan</li>
  <li>Unit jualan dan saiz pek</li>
  <li>Ketersediaan produk</li>
  <li>Lokasi penghantaran</li>
  <li>Pembungkusan</li>
  <li>Jenis akaun pelanggan</li>
  <li>Keperluan borong</li>
  <li>Keperluan penyumberan tersuai</li>
  <li>Terma komersial yang dipersetujui</li>
</ul>
<p>Harga yang dipaparkan atau disebut boleh berubah sebelum sesuatu pesanan disahkan. Harga pesanan yang disahkan adalah harga yang terpakai pada masa pesanan disahkan secara rasmi oleh MST.</p>

<h2>5. Sebut Harga, RFQ & Permohonan Pesanan</h2>
<p>Sebut harga, RFQ (Permohonan Sebut Harga) atau permohonan pesanan secara bersendirian tidak membentuk penerimaan sesuatu pesanan.</p>
<p>Sesuatu pesanan hanya disahkan apabila MST mengesahkan pesanan tersebut berserta terma komersial yang berkenaan. Ketersediaan produk, spesifikasi dan harga adalah tertakluk kepada pengesahan sehingga pesanan disahkan secara rasmi.</p>
<p>Harga yang disebut adalah sah hanya untuk tempoh yang dinyatakan dalam sebut harga dan kekal tertakluk kepada ketersediaan produk serta terma komersial yang berkenaan.</p>

<h2>6. Pesanan Borong dan B2B</h2>
<p>Pesanan B2B dan borong mungkin tertakluk kepada:</p>
<ul>
  <li>Kuantiti pesanan minimum (MOQ) jika berkenaan</li>
  <li>Ambang rujukan penghantaran (ambang rujukan B2B RM350)</li>
  <li>Syarat pembayaran</li>
  <li>Terma kredit jika diluluskan</li>
  <li>Pengaturan penghantaran</li>
  <li>Harga khusus komersial</li>
  <li>Syarat komersial lain yang dipersetujui</li>
</ul>
<p>Pendaftaran dan kelulusan akaun Borong tidak dengan sendirinya menjamin harga tetap, diskaun sejagat, terma kredit, MOQ atau ketersediaan produk. Harga dan terma komersial yang berkenaan mungkin berbeza mengikut pelanggan, produk, kuantiti, spesifikasi, destinasi, ketersediaan dan keperluan lain yang dipersetujui.</p>

<h2>7. Penyumberan & Bekalan Dagangan</h2>
<p>Keperluan penyumberan dan bekalan dagangan (termasuk kontena, palet dan bekalan pukal komersial) adalah tertakluk kepada spesifikasi produk, kuantiti minimum, keperluan destinasi, ketersediaan pembekal, susunan logistik dan keperluan komersial lain yang berkenaan. Pendaftaran atau kelulusan Akaun Dagangan tidak dengan sendirinya menjamin harga dagangan sejagat yang tetap atau terma kredit; harga dan terma komersial disediakan mengikut keperluan transaksi tertentu dan perjanjian komersial yang disahkan.</p>

<h2>8. Pesanan Runcit B2C</h2>
<p>Pelanggan runcit boleh membeli produk tertakluk kepada ketersediaan produk, ambang rujukan penghantaran yang berkenaan (ambang rujukan B2C RM100), pengaturan penghantaran atau pengambilan sendiri serta pengesahan pembayaran.</p>

<h2>9. Penyumberan Tersuai (Custom Sourcing)</h2>
<p>MST boleh membantu mendapatkan produk yang tidak disenaraikan di laman web atas permintaan pelanggan sebagai perkhidmatan penyumberan khusus.</p>
<p>Penyumberan Tersuai adalah keperluan perkhidmatan dan bukan kategori produk biasa, serta tertakluk kepada ketersediaan pembekal, harga, kuantiti minimum, spesifikasi, tempoh masa dan keperluan yang berkenaan.</p>
<p>Menghantar permintaan penyumberan tersuai atau RFQ tidak menjamin ketersediaan produk atau jaminan bekalan yang berjaya.</p>
<p>Sebaik sahaja pesanan penyumberan tersuai telah disahkan secara khusus, pembatalan, pemulangan atau bayaran balik adalah tertakluk kepada terma komersial yang dipersetujui dan Dasar Bayaran Balik & Pemulangan kami.</p>

<h2>10. Penerimaan Pesanan & Pengesahan Akhir</h2>
<p>MST berhak untuk menolak atau membatalkan permohonan pesanan jika perlu secara munasabah, termasuk sekiranya produk tiada dalam stok, maklumat yang diberikan tidak tepat, pembayaran tidak dapat disahkan, ketersediaan pembekal berubah, penghantaran tidak dapat diatur secara munasabah, atau terma komersial tidak dapat dipersetujui.</p>
<p>Pesanan akhir yang disahkan menetapkan produk, kuantiti, unit jualan, harga unit, mata wang penyelesaian (asas RM), kaedah pelaksanaan (penghantaran atau pengambilan sendiri), caj penghantaran/pengangkutan jika berkenaan, dan terma komersial lain yang dipersetujui.</p>

<h2>11. Pembayaran & Mata Wang Penyelesaian</h2>
<p>Mata wang penyelesaian asas MST ialah Ringgit Malaysia (RM / MYR). Mata wang lain (seperti SGD atau USD) dipaparkan untuk tujuan rujukan sahaja.</p>
<p>Pembayaran mesti diselesaikan mengikut terma pembayaran yang dipersetujui. Bagi akaun B2B yang diluluskan, terma kredit atau invois berasingan mungkin terpakai.</p>

<h2>12. Penghantaran & Pengambilan Sendiri</h2>
<p>Penghantaran tempatan pintu ke pintu disediakan berdasarkan ambang rujukan penghantaran standard:</p>
<ul>
  <li><strong>B2B / Borong:</strong> Ambang rujukan RM350</li>
  <li><strong>B2C / Runcit:</strong> Ambang rujukan RM100</li>
</ul>
<p>Amaun ini berfungsi sebagai ambang rujukan penghantaran dan bukan sekatan pesanan minimum yang mutlak. Pesanan di bawah amaun ini masih boleh dipertimbangkan/diterima jika perkhidmatan penghantaran tersedia, tertakluk kepada caj pengangkutan atau penghantaran yang berkenaan mengikut zon lokasi dan keperluan logistik.</p>
<p>Pesanan Walk-in Express adalah terhad untuk pengambilan sendiri di kemudahan SILC kami dan tidak menerima pilihan penghantaran.</p>

<h2>13. Tanggungjawab Pelanggan Selepas Penghantaran</h2>
<p>Pelanggan mesti memberikan maklumat penghantaran yang tepat dan memastikan individu yang diberi kuasa berada di lokasi untuk menerima pesanan. Produk sejuk beku hendaklah dipindahkan ke storan beku yang sesuai (-18°C atau ke bawah) dengan segera selepas penghantaran atau pengambilan.</p>

<h2>14. Bayaran Balik dan Pemulangan</h2>
<p>Bayaran balik, pemulangan, dan barangan yang rosak atau salah dikendalikan mengikut <strong>Dasar Bayaran Balik & Pemulangan</strong> kami.</p>
<p>Oleh kerana makanan laut sejuk beku, daging dan produk makanan mudah rosak dan sensitif terhadap suhu, sebarang isu mesti dilaporkan dengan segera (dalam masa 12 jam selepas penghantaran atau pengambilan) berserta bukti bergambar.</p>

<h2>15. Harta Intelek</h2>
<p>Semua kandungan di laman web ini, termasuk teks, grafik, logo, imej dan perisian, adalah hak milik MST atau pembekal kandungannya dan dilindungi oleh undang-undang harta intelek yang berkenaan.</p>

<h2>16. Had Liabiliti</h2>
<p>Setakat yang dibenarkan sepenuhnya oleh undang-undang yang terpakai, MST tidak akan bertanggungjawab terhadap sebarang kerosakan tidak langsung, sampingan, khas atau berbangkit yang timbul daripada penggunaan laman web, produk atau perkhidmatan kami.</p>

<h2>17. Undang-undang Pentadbiran</h2>
<p>Terma & Syarat ini ditadbir dan ditafsirkan mengikut undang-undang Malaysia. Sebarang pertikaian yang timbul di bawah atau berkaitan dengan Terma ini hendaklah tertakluk kepada bidang kuasa mahkamah Malaysia.</p>

<h2>18. Hubungi Kami</h2>
<p>Jika anda mempunyai soalan mengenai Terma & Syarat ini, sila hubungi kami di:</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.</strong><br>
  镁嘉国际贸易有限公司<br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  E-mel: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  Telefon / WhatsApp: +60 13-280 0168 / +60 11-1271 0260
</p>
HTML;

// 2. Update Privacy Policy & Cookie Policy for Leaflet + OpenStreetMap & Map disclosures (#54)
$privacyEn = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Effective Date:</strong> September 25, 2026 | <strong>Last Updated:</strong> September 30, 2026
</div>

<p><strong>MST Import and Export Sdn. Bhd.</strong> (“MST”, “we”, “us” or “our”) respects your privacy and is committed to protecting the personal information provided to us.</p>

<p>This Privacy Policy explains how MST may collect, use, disclose, store and protect personal information when you visit our website, contact us, submit an inquiry or RFQ, place an order, request custom sourcing, arrange delivery or collection, or otherwise interact with us.</p>

<h2>1. Personal Data We Collect</h2>
<p>We may collect personal information directly from you when you interact with our website or services, including:</p>
<ul>
  <li><strong>Contact Information:</strong> Name, business name, company registration number (SSM/UEN), email address, telephone/WhatsApp number, billing address, delivery address.</li>
  <li><strong>Commercial & Order Details:</strong> Account type (Retail, Wholesale, Trading), product requirements, inquiry details, quotations, order history, payment records, delivery preferences.</li>
  <li><strong>Technical & Browsing Data:</strong> IP address, browser type, device information, operating system, pages visited, time spent, and cookie data as described in our Cookie Policy.</li>
</ul>

<h2>2. How We Use Your Personal Data</h2>
<p>MST uses personal data for legitimate business purposes, including:</p>
<ul>
  <li>Processing inquiries, requests for quotation (RFQ), and custom sourcing requests</li>
  <li>Processing, confirming, and fulfilling orders, deliveries, and self-collection arrangements</li>
  <li>Managing customer accounts and business verification (including SSM/UEN verification for wholesale and trading accounts)</li>
  <li>Providing customer support and communicating regarding orders, stock availability, and commercial terms</li>
  <li>Complying with applicable legal, accounting, and food-safety regulatory requirements</li>
  <li>Maintaining website security, detecting fraud, and improving site functionality</li>
</ul>

<h2>3. Disclosure of Personal Information</h2>
<p>We do not sell, rent or trade your personal information. We may share information with trusted third parties solely to the extent necessary to conduct our business:</p>
<ul>
  <li><strong>Logistics & Delivery Partners:</strong> Cold-chain transport providers and couriers for order fulfillment and delivery coordination.</li>
  <li><strong>Payment Gateways:</strong> Payment processors (e.g. Stripe) for secure payment processing. We do not store full credit card numbers on our servers.</li>
  <li><strong>Technical & IT Service Providers:</strong> Hosting, database, email, and communication providers that support our operations under strict confidentiality terms.</li>
  <li><strong>Legal & Regulatory Authorities:</strong> When required by applicable Malaysian law, court order, or regulatory bodies.</li>
</ul>

<h2>4. Map & Third-Party Technical Services</h2>
<p>Our website utilizes <strong>Leaflet with OpenStreetMap map tiles</strong> served locally to display our SILC facility location without loading third-party tracking scripts. For directions, external links to Google Maps are provided for convenience. Clicking an external map link directs you to Google's platform, subject to Google's independent privacy policy.</p>

<h2>5. Data Security & Storage</h2>
<p>We implement appropriate technical and organisational security measures to protect your personal information against unauthorised access, alteration, disclosure or destruction. Data is stored on secure servers located in professional data centres.</p>

<h2>6. Your Rights</h2>
<p>Under the Malaysian Personal Data Protection Act 2010 (PDPA), you have the right to access, correct, or request the deletion of your personal data, subject to legal and contractual obligations. You may also withdraw marketing consent at any time.</p>

<h2>7. Contact Us Regarding Privacy</h2>
<p>If you have any questions or requests regarding your personal data, please contact our Data Protection Officer at:</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.</strong><br>
  镁嘉国际贸易有限公司<br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  Email: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  Phone / WhatsApp: +60 13-280 0168 / +60 11-1271 0260
</p>
HTML;

$privacyZh = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>生效日期：</strong>2026年9月25日 | <strong>最后更新：</strong>2026年9月30日
</div>

<p><strong>镁嘉国际贸易有限公司 (MST Import and Export Sdn. Bhd.)</strong>（以下简称“MST”或“我们”）尊重您的隐私并致力于保护向我们提供的个人信息。</p>

<p>本《隐私政策》阐明当您访问我们的网站、联系我们、提交咨询或询价 (RFQ)、下达订单、申请定制化采购、安排配送或自提，或以其他方式与我们互动时，MST 如何收集、使用、披露、存储及保护您的个人信息。</p>

<h2>1. 我们收集的个人信息</h2>
<p>我们可能在您与我们的网站或服务互动时直接收集个人信息，包括：</p>
<ul>
  <li><strong>联系信息：</strong>姓名、企业名称、公司注册号 (SSM/UEN)、电子邮箱、联系电话/WhatsApp、账单地址、配送地址。</li>
  <li><strong>商业与订单详情：</strong>账户类型（零售、批发、贸易账户）、采购需求、询价详情、报价单、订单历史、付款记录及配送偏好。</li>
  <li><strong>技术与浏览数据：</strong>IP 地址、浏览器类型、设备信息、操作系统、访问页面、停留时间以及如《Cookie 政策》中所述的 Cookie 数据。</li>
</ul>

<h2>2. 个人信息的使用目的</h2>
<p>MST 出于合法的业务目的使用个人信息，包括：</p>
<ul>
  <li>处理咨询、采购需求单 (RFQ) 以及定制化采购请求</li>
  <li>处理、确认并履行订单、配送及自提安排</li>
  <li>管理客户账户及商业资质审核（包括批发与贸易账户的 SSM/UEN 审核）</li>
  <li>提供客户服务，就订单状态、库存情况及商业条款进行沟通</li>
  <li>遵守马来西亚法律法规、会计及食品安全监管要求</li>
  <li>维护网站安全、防范欺诈及优化网站功能</li>
</ul>

<h2>3. 个人信息的披露</h2>
<p>我们绝不出售、出租或交易您的个人信息。我们仅在开展业务所必需的范围内，与受信任的第三方共享信息：</p>
<ul>
  <li><strong>物流与配送合作伙伴：</strong>冷链运输商与物流公司，用于订单交付与物流协调。</li>
  <li><strong>支付处理网关：</strong>第三方支付处理机构（如 Stripe），用于安全处理在线支付。我们不在服务器上存储完整银行卡信息。</li>
  <li><strong>技术与 IT 服务提供商：</strong>在严格保密条款下支持我们运营的主机、数据库及通信服务商。</li>
  <li><strong>法律与监管机构：</strong>根据马来西亚适用法律、法院命令或监管部门要求披露。</li>
</ul>

<h2>4. 地图与第三方技术服务</h2>
<p>我们的网站使用本地托管的 <strong>Leaflet 与 OpenStreetMap 地图瓦片服务</strong>来展示 SILC 园区设施位置，不加载第三方追踪脚本。如需路线导航，网站提供了前往 Google Maps 的外部链接。点击外部地图链接将跳转至 Google 平台，受 Google 独立的隐私政策管辖。</p>

<h2>5. 数据安全与存储</h2>
<p>我们采取适当的技术和组织安全措施，保护您的个人数据免遭未经授权的访问、篡改、披露或销毁。所有数据均存储在专业数据中心的受保护安全服务器上。</p>

<h2>6. 您的权利</h2>
<p>根据马来西亚《2010年个人数据保护法》(PDPA)，您有权查阅、更正或请求删除您的个人数据（须受法律和合同义务约束）。您还可以随时撤回营销推广许可。</p>

<h2>7. 隐私事务联系方式</h2>
<p>如对您的个人信息有任何疑问或请求，请联系我们的数据保护主管：</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.</strong><br>
  镁嘉国际贸易有限公司<br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  电邮：<a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  电话 / WhatsApp：+60 13-280 0168 / +60 11-1271 0260
</p>
HTML;

$privacyBm = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Tarikh Berkuat Kuasa:</strong> 25 September 2026 | <strong>Terakhir Dikemas Kini:</strong> 30 September 2026
</div>

<p><strong>MST Import and Export Sdn. Bhd.</strong> (“MST”, “kami” atau “kita”) menghormati privasi anda dan komited untuk melindungi maklumat peribadi yang diberikan kepada kami.</p>

<p>Dasar Privasi ini menerangkan cara MST mengumpul, menggunakan, mendedahkan, menyimpan dan melindungi maklumat peribadi apabila anda melayari laman web kami, menghubungi kami, menghantar pertanyaan atau RFQ, membuat pesanan, memohon penyumberan tersuai, mengatur penghantaran atau pengambilan, atau berinteraksi dengan kami.</p>

<h2>1. Data Peribadi yang Kami Kumpul</h2>
<p>Kami mungkin mengumpul maklumat peribadi secara langsung daripada anda apabila anda berinteraksi dengan laman web atau perkhidmatan kami, termasuk:</p>
<ul>
  <li><strong>Maklumat Hubungan:</strong> Nama, nama perniagaan, nombor pendaftaran syarikat (SSM/UEN), alamat e-mel, nombor telefon/WhatsApp, alamat pengebilan, alamat penghantaran.</li>
  <li><strong>Maklumat Komersial & Pesanan:</strong> Jenis akaun (Runcit, Borong, Dagangan), keperluan produk, butiran pertanyaan, sebut harga, sejarah pesanan, rekod pembayaran dan pilihan penghantaran.</li>
  <li><strong>Data Teknikal & Pelayaran:</strong> Alamat IP, jenis penyemak imbas, maklumat peranti, sistem pengendalian, halaman yang dilawati, masa yang diluangkan dan data kuki seperti yang diterangkan dalam Dasar Kuki kami.</li>
</ul>

<h2>2. Cara Kami Menggunakan Data Peribadi Anda</h2>
<p>MST menggunakan data peribadi untuk tujuan perniagaan yang sah, termasuk:</p>
<ul>
  <li>Memproses pertanyaan, permohonan sebut harga (RFQ) dan permohonan penyumberan tersuai</li>
  <li>Memproses, mengesahkan dan melaksanakan pesanan, penghantaran serta susunan pengambilan sendiri</li>
  <li>Mengurus akaun pelanggan dan pengesahan perniagaan (termasuk pengesahan SSM/UEN untuk akaun borong dan dagangan)</li>
  <li>Menyediakan sokongan pelanggan dan berkomunikasi mengenai status pesanan, ketersediaan stok dan terma komersial</li>
  <li>Mematuhi undang-undang, perakaunan dan keperluan kawal selia keselamatan makanan Malaysia yang berkaitan</li>
  <li>Mengekalkan keselamatan laman web, mengesan penipuan dan meningkatkan fungsi laman web</li>
</ul>

<h2>3. Pendedahan Maklumat Peribadi</h2>
<p>Kami tidak menjual, menyewa atau memperdagangkan maklumat peribadi anda. Kami hanya berkongsi maklumat dengan pihak ketiga yang dipercayai setakat yang diperlukan untuk menjalankan perniagaan kami:</p>
<ul>
  <li><strong>Rakan Kongsi Logistik & Penghantaran:</strong> Penyedia pengangkutan rantaian sejuk dan kurier untuk pelaksanaan pesanan dan penyelarasan penghantaran.</li>
  <li><strong>Gerbang Pembayaran:</strong> Pemproses pembayaran pihak ketiga (cth. Stripe) untuk pemprosesan pembayaran selamat. Kami tidak menyimpan nombor kad kredit penuh pada pelayan kami.</li>
  <li><strong>Penyedia Perkhidmatan Teknikal & IT:</strong> Penyedia pengehosan, pangkalan data dan komunikasi yang menyokong operasi kami di bawah terma kerahsiaan yang ketat.</li>
  <li><strong>Pihak Berkuasa Undang-undang & Kawal Selia:</strong> Apabila dikehendaki oleh undang-undang Malaysia, perintah mahkamah atau badan kawal selia yang berkaitan.</li>
</ul>

<h2>4. Peta & Perkhidmatan Teknikal Pihak Ketiga</h2>
<p>Laman web kami menggunakan <strong>Leaflet dengan jubin peta OpenStreetMap</strong> yang dihoskan secara tempatan untuk memaparkan lokasi kemudahan SILC kami tanpa memuatkan skrip penjejakan pihak ketiga. Untuk panduan arah perjalanan, pautan luaran ke Google Maps disediakan untuk kemudahan anda. Mengklik pautan peta luaran akan membawa anda ke platform Google yang tertakluk kepada dasar privasi bebas Google.</p>

<h2>5. Keselamatan & Penyimpanan Data</h2>
<p>Kami melaksanakan langkah keselamatan teknikal dan organisasi yang sesuai untuk melindungi data peribadi anda daripada akses, pengubahan, pendedahan atau pemusnahan yang tidak dibenarkan. Data disimpan pada pelayan selamat di pusat data profesional.</p>

<h2>6. Hak Anda</h2>
<p>Di bawah Akta Perlindungan Data Peribadi 2010 (PDPA) Malaysia, anda mempunyai hak untuk mengakses, membetulkan, atau meminta pemadaman data peribadi anda, tertakluk kepada kewajipan undang-undang dan kontrak. Anda juga boleh menarik balik kebenaran pemasaran pada bila-bila masa.</p>

<h2>7. Hubungi Kami Mengenai Privasi</h2>
<p>Jika anda mempunyai sebarang soalan atau permintaan mengenai data peribadi anda, sila hubungi Pegawai Perlindungan Data kami di:</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.</strong><br>
  镁嘉国际贸易有限公司<br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  E-mel: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  Telefon / WhatsApp: +60 13-280 0168 / +60 11-1271 0260
</p>
HTML;

// 3. Update Cookie Policy (#54)
$cookieEn = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Effective Date:</strong> September 25, 2026 | <strong>Last Updated:</strong> September 30, 2026
</div>

<p><strong>MST Import and Export Sdn. Bhd.</strong> (“MST”, “we”, “us” or “our”) may use cookies and similar technologies on our website to ensure core functionality and support visitor preferences.</p>

<p>This Cookie Policy explains how cookies are used and how you can manage your preferences. This policy should be read together with our <strong>Privacy Policy</strong>.</p>

<h2>1. What Are Cookies?</h2>
<p>Cookies are small text files placed on your device by websites you visit. They are widely used to ensure websites function efficiently, remember your preferences, and provide necessary session security.</p>

<h2>2. Categories of Cookies We Use</h2>
<p>Our website utilizes the following categories of cookies:</p>

<h3>A. Strictly Necessary Cookies (Always Active)</h3>
<p>These cookies are essential for the operation of our website, enabling core features such as shopping cart persistence, user authentication, customer session security, language selection, and fraud prevention. The website cannot function properly without these cookies.</p>

<h3>B. Preference & Functionality Cookies (Optional)</h3>
<p>These cookies allow the website to remember choices you make (such as preferred currency display, region, or interface settings) to provide a more tailored experience.</p>

<h3>C. Technical & Security Cookies</h3>
<p>These cookies help maintain secure transactions and protect our forms from spam and abuse (e.g. Google reCAPTCHA during inquiry/registration submission).</p>

<h2>3. Map Technologies Used on MST Website</h2>
<p>To display our facility location in Iskandar Puteri, we use <strong>Leaflet with OpenStreetMap map tiles</strong>. These map tiles are proxied directly through our server to respect your privacy and avoid third-party tracking cookies. We do not load third-party map tracking scripts into your browser.</p>

<h2>4. How to Manage Your Cookie Preferences</h2>
<p>You can manage or change your cookie preferences at any time by clicking the <strong>Cookie Settings</strong> button in our website footer. You may also control cookies through your web browser settings (e.g. blocking or deleting cookies).</p>

<h2>5. Contact Us</h2>
<p>If you have any questions about our use of cookies, please contact us at:</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.</strong><br>
  镁嘉国际贸易有限公司<br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  Email: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a>
</p>
HTML;

$cookieZh = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>生效日期：</strong>2026年9月25日 | <strong>最后更新：</strong>2026年9月30日
</div>

<p><strong>MST Import and Export Sdn. Bhd.</strong>（镁嘉国际贸易有限公司，以下简称“MST”、“我们”或“我们的”）可能会在本网站上使用 Cookie 及类似技术，以确保核心功能正常运行并支持访客偏好设置。</p>

<p>本《Cookie 政策》说明了 Cookie 的使用方式以及您如何管理偏好设置。本政策应与我们的<strong>《隐私政策》</strong>结合阅读。</p>

<h2>1. 什么是 Cookie？</h2>
<p>Cookie 是您访问网站时放置在您设备上的小型文本文件。它们被广泛用于确保网站高效运行、记住您的偏好设置以及提供必要的会话安全保障。</p>

<h2>2. 我们使用的 Cookie 类别</h2>
<p>我们的网站使用以下类别的 Cookie：</p>

<h3>A. 绝对必要 Cookie（始终启用）</h3>
<p>这些 Cookie 对网站的基本运行至关重要，支持购物车保存、用户登录认证、会话安全、语言选择及防欺诈等核心功能。没有这些 Cookie，网站无法正常提供服务。</p>

<h3>B. 偏好与功能性 Cookie（可选）</h3>
<p>这些 Cookie 允许网站记住您的选择（例如货币显示偏好或界面设置），从而提供更加个性化的使用体验。</p>

<h3>C. 安全与技术验证 Cookie</h3>
<p>这些 Cookie 用于保障交易安全并防止表单被垃圾信息滥用（例如在咨询与注册表单中使用的 Google reCAPTCHA 验证）。</p>

<h2>3. MST 网站采用的地图技术</h2>
<p>为了展示我们在依斯干达公主城 SILC 园区的设施位置，我们采用 <strong>Leaflet 与 OpenStreetMap 地图瓦片技术</strong>。这些地图瓦片直接通过我们的服务器进行代理传输，充分尊重您的隐私并避免第三方追踪 Cookie。我们不会在您的浏览器中加载第三方地图追踪脚本。</p>

<h2>4. 如何管理您的 Cookie 偏好</h2>
<p>您可以随时通过点击网站页脚的 <strong>Cookie 设置</strong> 按钮来查看或修改您的 Cookie 偏好。您也可以通过浏览器设置来管理、阻止或删除 Cookie。</p>

<h2>5. 联系我们</h2>
<p>如对我们的 Cookie 使用有任何疑问，请联系我们：</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.</strong><br>
  镁嘉国际贸易有限公司<br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  电邮：<a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a>
</p>
HTML;

$cookieBm = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Tarikh Berkuat Kuasa:</strong> 25 September 2026 | <strong>Terakhir Dikemas Kini:</strong> 30 September 2026
</div>

<p><strong>MST Import and Export Sdn. Bhd.</strong> (“MST”, “kami” atau “kita”) boleh menggunakan kuki dan teknologi serupa di laman web kami untuk memastikan fungsi teras berjalan dengan lancar serta menyokong pilihan pelawat.</p>

<p>Dasar Kuki ini menerangkan cara kuki digunakan dan cara anda boleh mengurus pilihan anda. Dasar ini hendaklah dibaca bersama <strong>Dasar Privasi</strong> kami.</p>

<h2>1. Apakah Itu Kuki?</h2>
<p>Kuki ialah fail teks kecil yang disimpan pada peranti anda oleh laman web yang anda layari. Ia digunakan secara meluas untuk memastikan laman web berfungsi dengan cekap, mengingati pilihan anda dan menyediakan keselamatan sesi yang diperlukan.</p>

<h2>2. Kategori Kuki yang Kami Gunakan</h2>
<p>Laman web kami menggunakan kategori kuki berikut:</p>

<h3>A. Kuki Sangat Diperlukan (Sentiasa Aktif)</h3>
<p>Kuki ini penting untuk operasi laman web kami, membolehkan fungsi teras seperti penyimpanan troli beli-belah, pengesahan log masuk pengguna, keselamatan sesi pelanggan, pemilihan bahasa dan pencegahan penipuan. Laman web tidak dapat berfungsi dengan baik tanpa kuki ini.</p>

<h3>B. Kuki Pilihan & Fungsi (Pilihan)</h3>
<p>Kuki ini membolehkan laman web mengingati pilihan yang anda buat (seperti pilihan paparan mata wang atau tetapan antara muka) untuk memberikan pengalaman yang lebih sesuai.</p>

<h3>C. Kuki Keselamatan & Teknikal</h3>
<p>Kuki ini membantu mengekalkan transaksi yang selamat dan melindungi borang daripada spam serta penyalahgunaan (cth. Google reCAPTCHA semasa penghantaran pertanyaan/pendaftaran).</p>

<h2>3. Teknologi Peta yang Digunakan di Laman Web MST</h2>
<p>Untuk memaparkan lokasi kemudahan SILC kami di Iskandar Puteri, kami menggunakan <strong>Leaflet dengan jubin peta OpenStreetMap</strong>. Jubin peta ini diproksi secara langsung melalui pelayan kami untuk menghormati privasi anda dan mengelakkan kuki penjejakan pihak ketiga. Kami tidak memuatkan skrip penjejakan peta pihak ketiga ke dalam penyemak imbas anda.</p>

<h2>4. Cara Mengurus Pilihan Kuki Anda</h2>
<p>Anda boleh mengurus atau menukar pilihan kuki anda pada bila-bila masa dengan mengklik butang <strong>Tetapan Kuki</strong> di pengaki laman web kami. Anda juga boleh mengawal kuki melalui tetapan penyemak imbas anda.</p>

<h2>5. Hubungi Kami</h2>
<p>Jika anda mempunyai sebarang soalan mengenai penggunaan kuki kami, sila hubungi kami di:</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.</strong><br>
  镁嘉国际贸易有限公司<br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  E-mel: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a>
</p>
HTML;

echo "=== Updating MySQL policies table ===\n";
$policyData = [
    'terms-and-conditions' => [
        'en' => $termsEn,
        'zh' => $termsZh,
        'bm' => $termsBm,
    ],
    'privacy-policy' => [
        'en' => $privacyEn,
        'zh' => $privacyZh,
        'bm' => $privacyBm,
    ],
    'cookie-policy' => [
        'en' => $cookieEn,
        'zh' => $cookieZh,
        'bm' => $cookieBm,
    ],
];

foreach ($policyData as $slug => $contents) {
    DB::table('policies')->where('slug', $slug)->update([
        'content'    => $contents['en'],
        'content_zh' => $contents['zh'],
        'content_bm' => $contents['bm'],
        'updated_at' => '2026-09-30 00:00:00',
    ]);
    echo "Updated MySQL policy: {$slug}\n";
}

// Also update dates for refund-policy and shipping-policy to 2026-09-30 for perfect legal consistency
DB::table('policies')->whereIn('slug', ['refund-policy', 'shipping-policy'])->update([
    'updated_at' => '2026-09-30 00:00:00',
]);

echo "\n=== Updating SQLite policies table ===\n";
$sqlitePath = database_path('database.sqlite');
if (file_exists($sqlitePath)) {
    $pdo = new PDO("sqlite:{$sqlitePath}");
    $stmt = $pdo->prepare("UPDATE policies SET content = :content, content_zh = :content_zh, content_bm = :content_bm, updated_at = '2026-09-30 00:00:00' WHERE slug = :slug");
    foreach ($policyData as $slug => $contents) {
        $stmt->execute([
            ':content'    => $contents['en'],
            ':content_zh' => $contents['zh'],
            ':content_bm' => $contents['bm'],
            ':slug'       => $slug,
        ]);
        echo "Updated SQLite policy: {$slug}\n";
    }
    $pdo->exec("UPDATE policies SET updated_at = '2026-09-30 00:00:00' WHERE slug IN ('refund-policy', 'shipping-policy')");
    echo "SQLite policies updated.\n";
}

echo "\nPolicies updated successfully!\n";
