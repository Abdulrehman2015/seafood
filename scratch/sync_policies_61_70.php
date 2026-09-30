<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Policy;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;

echo "=== STARTING SYNC FOR SECTIONS #61-#70 ===\n\n";

// 1. Update Lang and DB Translations for Walk-in and other keys
$translationUpdates = [
    'products_count_label' => [
        'en' => ' products available for Walk-in / Self-Collection',
        'zh' => ' 款商品支持 门店选购 / 到店自提',
        'bm' => ' produk sedia ada untuk Walk-in / Pengambilan Sendiri',
    ],
];

foreach ($translationUpdates as $suffix => $vals) {
    // Update matching rows in MySQL
    DB::table('translations')
        ->where('key', 'like', "%{$suffix}")
        ->update([
            'text_en' => $vals['en'],
            'text_zh' => $vals['zh'],
            'text_bm' => $vals['bm'],
            'updated_at' => now(),
        ]);

    // Update JSON lang files
    foreach (['en', 'zh', 'bm', 'ms'] as $loc) {
        $langFile = base_path("lang/{$loc}.json");
        if (file_exists($langFile)) {
            $data = json_decode(file_get_contents($langFile), true) ?: [];
            $valKey = ($loc === 'ms') ? 'bm' : $loc;
            foreach (['walkin.products_count_label', 'common.products_count_label', 'products_count_label'] as $k) {
                if (isset($data[$k])) {
                    $data[$k] = $vals[$valKey];
                }
            }
            file_put_contents($langFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
}
echo "✓ Translation files and MySQL translations table updated.\n";

// 2. Build Policy HTML Contents

// -------------------------------------------------------------
// REFUND POLICY
// -------------------------------------------------------------
$refund_en = <<<'HTML'
<div class="policy-header-badge mb-3">
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
        <i class="bi bi-shield-check me-1"></i> Effective Date: September 30, 2026 | Last Updated: September 30, 2026
    </span>
</div>

<div class="lead text-dark mb-4 fw-normal">
    <p><strong>MST Import and Export Sdn. Bhd.</strong> (&ldquo;MST&rdquo;) supplies frozen seafood, frozen food, meat, ingredients and other temperature-sensitive products.</p>
    <p>Because these products are perishable and temperature-sensitive, refund, return and quality complaint requests are subject to the following conditions.</p>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>1. 12-Hour Notification Requirement</h3>
    <p>Customers should notify MST of an applicable product quality issue within 12 hours after the delivery or self-collection is recorded as completed.</p>
    <p>The notification should include the order or invoice number and relevant photographs or videos where applicable.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-snow text-primary me-2"></i>2. Product Condition & Inspection</h3>
    <p>Where a product-quality complaint requires the affected product to remain available for inspection, the product should, where relevant and reasonably applicable depending on the nature of the product and complaint, remain:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Frozen</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Not thawed</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Not cooked</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Not processed</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Not repacked</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Not altered</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Available for inspection where reasonably required</li>
    </ul>
    <p class="text-muted small">These requirements allow MST to properly assess whether the issue originated from the product. Requirements are applied according to the nature of the specific product.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-patch-check text-primary me-2"></i>3. Valid Quality Issues</h3>
    <p>A refund or replacement may be considered where MST verifies a genuine product-related quality issue or an issue attributable to MST or the supplied product.</p>
    <p class="mb-2">Examples may include:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>Genuine product quality defect</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>Wrong product supplied</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>Material difference from the confirmed order</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>Damage attributable to MST during delivery</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>Other verified product-related defects</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-slash-circle text-primary me-2"></i>4. Cooking Method, Preparation & Personal Taste</h3>
    <p>Refunds will not be accepted solely because of:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Cooking method</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Preparation method</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Personal taste</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Personal preference</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Preferred texture</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Customer preparation choices</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Dissatisfaction with the result after customer preparation</li>
    </ul>
    <p class="bg-light p-3 rounded-3 border text-secondary small">
        <i class="bi bi-info-circle me-1"></i> For example, if a product is prepared differently from standard methods and the customer does not prefer the taste or texture, this will not automatically constitute a product quality defect.
    </p>
    <p class="text-muted small">Nothing in this policy excludes or restricts any rights or remedies that cannot legally be excluded or restricted under applicable law.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-arrow-repeat text-primary me-2"></i>5. Change of Mind</h3>
    <p>Refunds or returns will generally not be accepted because:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>The customer changed their mind</li>
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>The customer ordered the wrong item</li>
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>The customer no longer requires the product</li>
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>The customer prefers another brand or product</li>
    </ul>
    <p class="text-muted small">Nothing in this policy excludes or restricts any rights or remedies that cannot legally be excluded or restricted under applicable law.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-camera text-primary me-2"></i>6. Evidence and Investigation</h3>
    <p>MST may request:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Order or invoice number</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Photographs or videos</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Product label and batch information</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Delivery / collection records</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Description of the problem</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Storage information</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Handling information</li>
    </ul>
    <p>MST may inspect the affected product before approving a refund or replacement.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-trash3 text-primary me-2"></i>7. Disposal of Product</h3>
    <p>Customers should not dispose of an affected product before contacting MST, unless disposal is reasonably necessary for health or safety reasons.</p>
    <p>Where the product has already been thawed, cooked, disposed of or otherwise altered, MST may be unable to verify the alleged product-quality issue and the refund request may not be accepted, subject to applicable law.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-cash-coin text-primary me-2"></i>8. Approved Refunds</h3>
    <p>If MST confirms that a refund is appropriate, the refund will be processed using the applicable payment method or another arrangement agreed with the customer.</p>
    <p>Processing time may depend on the relevant payment provider or bank.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-arrow-left-right text-primary me-2"></i>9. Replacement / Credit Note / Other Resolution</h3>
    <p>Depending on the circumstances, MST may provide:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Replacement product</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Refund</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Partial refund</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Credit note</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Other reasonable resolution</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>10. Custom Sourcing Orders</h3>
    <p>Custom-sourced or specially ordered products may not be eligible for cancellation, return or refund simply because the customer changes their mind or no longer requires the product.</p>
    <p>Specific terms will be communicated before confirmation where applicable.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>11. Wholesale and Trading Orders</h3>
    <p>Wholesale and Trading orders may be subject to additional commercial terms stated in quotations, invoices, purchase orders, sales confirmations or separate agreements.</p>
    <p>Those specific terms will apply to the relevant transaction to the extent permitted by law.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-person-check text-primary me-2"></i>12. Customer Responsibility</h3>
    <p>Customers are responsible for:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Providing accurate order information</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Providing accurate delivery information</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Receiving or collecting products promptly</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Properly storing frozen products in appropriate facilities</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Maintaining suitable temperature-controlled storage conditions</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Following reasonable product-handling instructions</li>
    </ul>
    <p>To the extent permitted by applicable law, MST is not liable for deterioration caused by improper customer storage, handling, thawing, cooking, delayed acceptance, or circumstances outside MST's reasonable control.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-headset text-primary me-2"></i>13. Contact</h3>
    <div class="card bg-light border-0 shadow-sm p-3">
        <p class="fw-bold mb-2">MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</p>
        <p class="mb-1"><i class="bi bi-geo-alt text-primary me-2"></i>No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia</p>
        <p class="mb-1"><i class="bi bi-envelope text-primary me-2"></i><strong>Email:</strong> <a href="mailto:mikatrading15@gmail.com" class="text-decoration-none">mikatrading15@gmail.com</a></p>
        <p class="mb-1"><i class="bi bi-telephone text-primary me-2"></i><strong>Phone:</strong> <a href="tel:+60132800168" class="text-decoration-none">+60 13-280 0168</a></p>
        <p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 11-1271 0260</a></p>
        <p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>Please include your order/invoice number and relevant photographs or videos when making a complaint.</p>
    </div>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="legal-disclaimer text-muted small p-3 bg-light rounded-3">
    <p class="mb-0"><i class="bi bi-shield-shaded me-1"></i> Nothing in this policy excludes or restricts any rights or remedies that cannot legally be excluded or restricted under applicable law.</p>
</div>
HTML;

$refund_zh = <<<'HTML'
<div class="policy-header-badge mb-3">
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
        <i class="bi bi-shield-check me-1"></i> 生效日期：2026年9月30日 | 最近更新：2026年9月30日
    </span>
</div>

<div class="lead text-dark mb-4 fw-normal">
    <p><strong>MST Import and Export Sdn. Bhd.（镁嘉国际贸易有限公司）</strong>（以下简称“MST”）供应冷冻海鲜、冷冻肉类、冷冻食品、食材及其他对温度敏感的产品。</p>
    <p>由于此类产品具有易腐性和温度敏感性，因此退款、退换及品质投诉需遵守以下各项条件。</p>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>1. 12 小时内通知要求</h3>
    <p>客户应在配送或到店自提被记录为完成之时起 12 小时内，就适用的产品质量问题通知 MST。</p>
    <p>通知时应附上相关的订单号或发票号，并在适用情况下提供清晰的照片或视频证明。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-snow text-primary me-2"></i>2. 产品保持可供查验状态</h3>
    <p>若产品质量投诉需要对受影响产品进行检验，根据产品性质及投诉实际情况，在合理适用的范围内，该产品应尽量保持：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>保持冷冻状态</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>未解冻</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>未烹饪</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>未加工</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>未重新包装</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>未经改动</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>在合理需要时可供查验</li>
    </ul>
    <p class="text-muted small">此要求有助于 MST 准确核实问题是否源自产品本身。相关要求将根据具体产品的性质合理适用。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-patch-check text-primary me-2"></i>3. 合效的品质问题范围</h3>
    <p>经 MST 核实确实存在归属于产品本身或由 MST 责任导致的真实品质问题时，方可考虑予以退款或更换。</p>
    <p class="mb-2">示例包括：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>确认的产品质量缺陷</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>错发商品</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>与已确认订单存在实质性差异</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>配送过程中归属于 MST 责任的破损变质</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>其他经核实的产品瑕疵</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-slash-circle text-primary me-2"></i>4. 烹饪方式、备料与个人口味偏好</h3>
    <p>以下情形不能单独作为退款理由：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>烹饪方法</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>备料调制方法</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>个人口味偏好</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>个人主观偏好</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>对口感与肉质的主观喜好</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>客户个人的料理与烹饪选择</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>烹饪后对成菜结果不满意</li>
    </ul>
    <p class="bg-light p-3 rounded-3 border text-secondary small">
        <i class="bi bi-info-circle me-1"></i> 例如，若商品烹饪方式与常规或推荐做法不同，客户对最终呈现的味道或口感不满意，并不自动构成产品质量问题。
    </p>
    <p class="text-muted small">本政策不排除或限制适用法律规定不得排除或限制的任何权利或补救措施。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-arrow-repeat text-primary me-2"></i>5. 改变主意</h3>
    <p>一般情况下，以下原因不属于退款或退换范围：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>客户改变主意</li>
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>客户订错商品</li>
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>客户不再需要该商品</li>
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>客户更偏好其他品牌或替代品</li>
    </ul>
    <p class="text-muted small">本政策不排除或限制适用法律规定不得排除或限制的任何权利或补救措施。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-camera text-primary me-2"></i>6. 证明材料与调查核实</h3>
    <p>MST 在处理投诉时可能要求提供：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>订单号或发票号</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>照片或视频证明</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>产品标签与批次号</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>配送或自提记录</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>问题详细描述</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>冷冻储存情况说明</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>操作处理说明</li>
    </ul>
    <p>MST 保留在批准退款或更换前查验受影响产品的权利。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-trash3 text-primary me-2"></i>7. 产品处置</h3>
    <p>在联系 MST 之前，除因卫生或安全原因确有必要外，客户不应擅自丢弃或处置受影响的产品。</p>
    <p>若产品已被解冻、烹饪、丢弃或擅自改动，MST 可能无法核实所声称的品质问题，在此情况下退款申请可能无法受理（以适用法律为准）。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-cash-coin text-primary me-2"></i>8. 批准退款的处理</h3>
    <p>若 MST 经核实确认退款合理，款项将通过原支付渠道或与客户双方协商一致的其他方式退回。</p>
    <p>具体到账周期取决于相关支付网关、发卡行或银行机构的处理时间。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-arrow-left-right text-primary me-2"></i>9. 更换 / 贷记单 / 其他解决方案</h3>
    <p>根据具体情况，MST 可提供：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>商品更换</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>退款</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>部分退款</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>抵用贷记单（Credit Note）</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>其他双方同意的合理方案</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>10. 定制化采购订单</h3>
    <p>对于定制化采购或专项预订的商品，客户不能仅因改变主意或不再需要而要求取消、退换或退款。</p>
    <p>专项条款将在确认前明确告知客户。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>11. 批发及贸易订单</h3>
    <p>批发与贸易订单可能受报价单、发票、采购单（PO）、销售确认书或专项协议中所约定的额外商务条款约束。</p>
    <p>在法律允许的范围内，该等特定条款优先适用于相关交易。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-person-check text-primary me-2"></i>12. 客户责任与储存要求</h3>
    <p>客户有责任：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>提供准确的订单信息</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>提供准确的配送信息</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>送达或自提时及时安排妥善收货</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>收货后立即转入合规的冷冻设施储存</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>保持正确的冷冻温控环境</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>遵循合理的冷冻食品处理指引</li>
    </ul>
    <p>在适用法律允许的范围内，对于因客户不当储存、操作不当、擅自解冻、烹饪失误、延误收货或超出 MST 合理控制范围的其他情况导致的产品变质，MST 不承担责任。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-headset text-primary me-2"></i>13. 联系方式</h3>
    <div class="card bg-light border-0 shadow-sm p-3">
        <p class="fw-bold mb-2">MST Import and Export Sdn. Bhd.（镁嘉国际贸易有限公司）</p>
        <p class="mb-1"><i class="bi bi-geo-alt text-primary me-2"></i>No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia</p>
        <p class="mb-1"><i class="bi bi-envelope text-primary me-2"></i><strong>电子邮箱：</strong> <a href="mailto:mikatrading15@gmail.com" class="text-decoration-none">mikatrading15@gmail.com</a></p>
        <p class="mb-1"><i class="bi bi-telephone text-primary me-2"></i><strong>电话：</strong> <a href="tel:+60132800168" class="text-decoration-none">+60 13-280 0168</a></p>
        <p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp：</strong> <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 11-1271 0260</a></p>
        <p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>提出品质问题或退款申请时，请务必提供您的订单号/发票号及清晰的照片或视频材料。</p>
    </div>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="legal-disclaimer text-muted small p-3 bg-light rounded-3">
    <p class="mb-0"><i class="bi bi-shield-shaded me-1"></i> 本政策不排除或限制适用法律规定不得排除或限制的任何权利或补救措施。</p>
</div>
HTML;

$refund_bm = <<<'HTML'
<div class="policy-header-badge mb-3">
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
        <i class="bi bi-shield-check me-1"></i> Tarikh Berkuat Kuasa: 30 September 2026 | Terakhir Dikemas Kini: 30 September 2026
    </span>
</div>

<div class="lead text-dark mb-4 fw-normal">
    <p><strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong> (&ldquo;MST&rdquo;) membekalkan makanan laut sejuk beku, makanan sejuk beku, daging, bahan ramuan makanan dan produk sensitif suhu yang lain.</p>
    <p>Disebabkan produk-produk ini mudah rosak dan sensitif terhadap suhu, sebarang permohonan bayaran balik, pemulangan dan aduan kualiti adalah tertakluk kepada syarat-syarat berikut.</p>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>1. Keperluan Pemberitahuan Dalam Tempoh 12 Jam</h3>
    <p>Pelanggan hendaklah memaklumkan MST mengenai isu kualiti produk yang berkenaan dalam tempoh 12 jam selepas penghantaran atau pengambilan sendiri direkodkan sebagai selesai.</p>
    <p>Pemberitahuan hendaklah mengandungi nombor pesanan atau invois berserta gambar atau video yang berkaitan jika berkenaan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-snow text-primary me-2"></i>2. Keadaan Produk & Pemeriksaan</h3>
    <p>Sekiranya aduan kualiti produk memerlukan produk yang terjejas disediakan untuk pemeriksaan, produk tersebut hendaklah, setakat yang berkaitan dan munasabah mengikut sifat produk dan aduan:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Kekal dalam keadaan sejuk beku</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Tidak dinyahbeku</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Belum dimasak</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Tidak diproses</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Tidak dibungkus semula</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Tidak diubah suai</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Kekal tersedia untuk pemeriksaan jika diperlukan secara munasabah</li>
    </ul>
    <p class="text-muted small">Keperluan ini membolehkan MST menilai dengan tepat sama ada isu tersebut berpunca daripada produk itu sendiri. Keperluan diguna pakai mengikut sifat produk yang berkaitan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-patch-check text-primary me-2"></i>3. Isu Kualiti Yang Sah</h3>
    <p>Bayaran balik atau penggantian boleh dipertimbangkan sekiranya MST mengesahkan isu kualiti tulen berkaitan produk atau isu yang berpunca daripada MST atau produk yang dibekalkan.</p>
    <p class="mb-2">Contoh isu yang boleh dipertimbangkan:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>Kecacatan kualiti produk yang disahkan</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>Produk salah dibekalkan</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>Perbezaan ketara daripada pesanan yang disahkan</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>Kerosakan semasa penghantaran yang berpunca daripada MST</li>
        <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>Kecacatan produk lain yang disahkan</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-slash-circle text-primary me-2"></i>4. Kaedah Memasak, Penyediaan dan Citarasa Peribadi</h3>
    <p>Bayaran balik tidak akan diterima semata-mata atas faktor:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Kaedah memasak</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Kaedah penyediaan</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Citarasa peribadi</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Keutamaan peribadi</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Tekstur kegemaran</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Pilihan penyediaan oleh pelanggan</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Ketidakpuasan hati dengan hasil selepas penyediaan pelanggan</li>
    </ul>
    <p class="bg-light p-3 rounded-3 border text-secondary small">
        <i class="bi bi-info-circle me-1"></i> Sebagai contoh, jika produk dimasak berbeza daripada kaedah penyediaan lazim dan pelanggan tidak menyukai rasa atau tekstur yang terhasil, ini tidak secara automatik dianggap sebagai isu kualiti produk.
    </p>
    <p class="text-muted small">Polisi ini tidak mengecualikan atau mengehadkan mana-mana hak atau remedi yang tidak boleh dikecualikan atau dihadkan di bawah undang-undang yang terpakai.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-arrow-repeat text-primary me-2"></i>5. Perubahan Fikiran</h3>
    <p>Secara umumnya, bayaran balik atau pemulangan tidak akan diterima atas sebab:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>Pelanggan mengubah fikiran</li>
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>Pelanggan tersalah memesan item</li>
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>Pelanggan tidak lagi memerlukan produk</li>
        <li class="mb-2"><i class="bi bi-x text-muted me-2"></i>Pelanggan lebih menyukai jenama atau produk lain</li>
    </ul>
    <p class="text-muted small">Polisi ini tidak mengecualikan atau mengehadkan mana-mana hak atau remedi yang tidak boleh dikecualikan atau dihadkan di bawah undang-undang yang terpakai.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-camera text-primary me-2"></i>6. Bukti dan Siasatan</h3>
    <p>MST mungkin meminta:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Nombor pesanan atau invois</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Gambar atau video</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Label produk dan maklumat kelompok (batch)</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Rekod penghantaran / pengambilan</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Penerangan terperinci mengenai masalah yang dihadapi</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Maklumat penyimpanan sejuk beku</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Maklumat pengendalian produk</li>
    </ul>
    <p>MST berhak memeriksa produk yang terjejas sebelum meluluskan bayaran balik atau penggantian.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-trash3 text-primary me-2"></i>7. Pelupusan Produk</h3>
    <p>Pelanggan tidak harus melupuskan produk yang terjejas sebelum menghubungi MST, melainkan pelupusan tersebut amat perlu atas faktor kesihatan atau keselamatan.</p>
    <p>Sekiranya produk telah dinyahbeku, dimasak, dilupuskan atau diubah suai, MST mungkin tidak dapat mengesahkan isu kualiti produk yang didakwa dan permohonan bayaran balik mungkin tidak dapat diterima, tertakluk kepada undang-undang yang terpakai.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-cash-coin text-primary me-2"></i>8. Bayaran Balik Yang Diluluskan</h3>
    <p>Sekiranya MST mengesahkan bahawa bayaran balik adalah wajar, bayaran balik akan diproses melalui kaedah pembayaran yang berkaitan atau persetujuan lain yang dipersetujui bersama pelanggan.</p>
    <p>Tempoh pemprosesan mungkin bergantung kepada penyedia perkhidmatan pembayaran atau pihak bank yang berkenaan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-arrow-left-right text-primary me-2"></i>9. Penggantian / Nota Kredit / Penyelesaian Lain</h3>
    <p>Bergantung pada keadaan, MST boleh menyediakan:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Penggantian produk</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Bayaran balik</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Bayaran balik sebahagian</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Nota kredit</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Penyelesaian munasabah lain</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>10. Pesanan Penyumberan Tersuai (Custom Sourcing)</h3>
    <p>Bagi produk pesanan khas atau penyumberan tersuai, pelanggan tidak boleh membatalkan, memulangkan atau meminta bayaran balik semata-mata kerana perubahan fikiran atau tidak lagi memerlukan produk tersebut.</p>
    <p>Terma khusus akan dimaklumkan sebelum pengesahan pesanan jika berkenaan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>11. Pesanan Borong dan Dagangan</h3>
    <p>Pesanan borong dan dagangan mungkin tertakluk kepada terma komersial tambahan yang dinyatakan dalam sebut harga, invois, pesanan pembelian (PO), pengesahan jualan atau perjanjian berasingan.</p>
    <p>Terma khusus tersebut akan diguna pakai untuk transaksi yang berkaitan setakat yang dibenarkan oleh undang-undang.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-person-check text-primary me-2"></i>12. Tanggungjawab Pelanggan</h3>
    <p>Pelanggan bertanggungjawab untuk:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Memberikan maklumat pesanan yang tepat</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Memberikan maklumat penghantaran yang tepat</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Menerima atau mengambil barangan dengan segera</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Menyimpan produk sejuk beku di kemudahan yang sesuai</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Mengekalkan keadaan penyimpanan kawalan suhu yang sesuai selepas penerimaan</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Mematuhi arahan pengendalian produk yang munasabah</li>
    </ul>
    <p>Setakat yang dibenarkan oleh undang-undang, MST tidak bertanggungjawab atas kemerosotan kualiti yang disebabkan oleh penyimpanan, pengendalian, penyahbekuan, masakan yang tidak betul oleh pelanggan, kelewatan menerima barangan, atau keadaan di luar kawalan munasabah MST.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-headset text-primary me-2"></i>13. Hubungi Kami</h3>
    <div class="card bg-light border-0 shadow-sm p-3">
        <p class="fw-bold mb-2">MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</p>
        <p class="mb-1"><i class="bi bi-geo-alt text-primary me-2"></i>No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia</p>
        <p class="mb-1"><i class="bi bi-envelope text-primary me-2"></i><strong>Emel:</strong> <a href="mailto:mikatrading15@gmail.com" class="text-decoration-none">mikatrading15@gmail.com</a></p>
        <p class="mb-1"><i class="bi bi-telephone text-primary me-2"></i><strong>Telefon:</strong> <a href="tel:+60132800168" class="text-decoration-none">+60 13-280 0168</a></p>
        <p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 11-1271 0260</a></p>
        <p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>Sila sertakan nombor pesanan/invois anda berserta gambar atau video yang berkaitan semasa membuat aduan kualiti.</p>
    </div>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="legal-disclaimer text-muted small p-3 bg-light rounded-3">
    <p class="mb-0"><i class="bi bi-shield-shaded me-1"></i> Polisi ini tidak mengecualikan atau mengehadkan mana-mana hak atau remedi yang tidak boleh dikecualikan atau dihadkan di bawah undang-undang yang terpakai.</p>
</div>
HTML;


// -------------------------------------------------------------
// SHIPPING POLICY
// -------------------------------------------------------------
$shipping_en = <<<'HTML'
<div class="policy-header-badge mb-3">
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
        <i class="bi bi-shield-check me-1"></i> Effective Date: September 30, 2026 | Last Updated: September 30, 2026
    </span>
</div>

<div class="lead text-dark mb-4 fw-normal">
    <p><strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong> supplies frozen seafood, frozen meat, frozen food, ingredients and other temperature-sensitive products.</p>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-truck text-primary me-2"></i>1. Standard Local Delivery Coverage</h3>
    <p>MST provides door-to-door delivery with transparent tier and zone-based delivery arrangements:</p>
    
    <div class="row g-3 my-2">
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2 px-2 py-1">B2B / Wholesale</span>
                    <strong class="text-dark">Commercial Orders</strong>
                </div>
                <p class="fs-5 fw-bold text-primary mb-0">Standard Delivery Threshold: RM350</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2 px-2 py-1">B2C / Retail</span>
                    <strong class="text-dark">Consumer Orders</strong>
                </div>
                <p class="fs-5 fw-bold text-success mb-0">Standard Delivery Threshold: RM100</p>
            </div>
        </div>
    </div>
    
    <p class="text-muted small mt-2">
        <i class="bi bi-geo-alt me-1 text-primary"></i> <strong>Standard Local Delivery Coverage:</strong> <code>Johor Bahru and Iskandar Puteri / Nusajaya</code>.
    </p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-cart-check text-primary me-2"></i>2. Orders Below Standard Delivery Threshold</h3>
    <p>B2C / Retail customers can complete checkout at any order amount. For orders below RM100, an additional delivery or transport surcharge may apply based on your delivery zone/postcode.</p>
    <p>However, transportation / delivery charges will apply.</p>
    <p class="text-muted small">The applicable delivery charge will be communicated before the order is confirmed.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-pin-map text-primary me-2"></i>3. Outside Standard Local Area</h3>
    <p>Delivery outside the standard local area (Johor Bahru and Iskandar Puteri / Nusajaya) is not automatically available.</p>
    <p>Delivery outside the standard local area may be:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Considered on a case-by-case basis</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Subject to destination</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Subject to product requirements</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Subject to logistics requirements</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Subject to applicable transportation charges</li>
    </ul>
    <p>Applicable transportation/logistics charges may be payable by the customer unless otherwise agreed with MST.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-receipt text-primary me-2"></i>4. Delivery / Transportation Charges</h3>
    <p>Delivery or transportation charges may depend on factors including:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Delivery location / zone</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Distance</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Order quantity / weight</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Vehicle requirements</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Logistics requirements</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Cold-chain requirements</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Delivery schedule</li>
    </ul>
    <p class="text-muted small">Where a delivery charge applies, it will be communicated to the customer before final order confirmation according to actual system capability.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-calendar-check text-primary me-2"></i>5. Delivery Schedule</h3>
    <p>Delivery times are estimates unless specifically confirmed in writing.</p>
    <p class="text-muted small">Delivery may be affected by traffic, weather, public holidays, vehicle availability, product availability, customer availability or circumstances beyond MST's reasonable control.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-signpost-2 text-primary me-2"></i>6. Delivery Address</h3>
    <p>Customers must provide accurate:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Delivery address</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Contact person</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Contact number</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Receiving instructions</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Operating or receiving hours where applicable</li>
    </ul>
    <p class="text-muted small">To the extent permitted by applicable law, MST is not liable for delays or additional charges caused by inaccurate or incomplete information provided by the customer.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>7. Receiving Frozen Products</h3>
    <p>Customers should ensure that an authorised person is available to receive the order.</p>
    <p>Customers should inspect the products and packaging promptly upon delivery.</p>
    <p>Customers should notify MST of an applicable product quality issue within 12 hours after the delivery or self-collection is recorded as completed.</p>
    <div class="alert alert-info py-2 px-3 small border-0 rounded-3 my-2">
        <i class="bi bi-info-circle me-1"></i> Please refer to our <a href="/en/policy/refund-policy" class="alert-link fw-semibold">Refund &amp; Return Policy</a>.
    </div>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-snow2 text-primary me-2"></i>8. Frozen Product Storage</h3>
    <p>Customers are responsible for transferring frozen products to appropriate frozen storage promptly after receiving the delivery.</p>
    <p class="text-muted small">To the extent permitted by applicable law, MST is not liable for deterioration occurring after delivery due to improper customer storage, thawing, handling, or other circumstances attributable to the customer. Nothing in this policy limits or excludes statutory rights that cannot legally be excluded under applicable law.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-exclamation-triangle text-primary me-2"></i>9. Failed Delivery</h3>
    <p>If delivery cannot be completed due to reasons attributable to the customer (such as no authorised recipient available, incorrect address provided, customer unreachable, or agreed receiving arrangements not followed):</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Waiting charges</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Redelivery charges</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Transport charges</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Handling charges</li>
    </ul>
    <p>Additional costs may apply as listed above.</p>
    <p class="text-muted small">However, such charges will not apply to delivery failures caused by MST or a logistics provider appointed by MST, to the extent applicable.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-shop text-primary me-2"></i>10. Walk-in / Self-Collection</h3>
    <div class="mb-2"><span class="badge bg-secondary-subtle text-dark border px-3 py-1 rounded-pill fw-semibold">Self-Collection only · No delivery</span></div>
    <p>Customers who select Walk-in / Self-Collection collect their confirmed orders directly from our SILC facility. There is no delivery charge because the customer is collecting the order themselves (this is self-collection, not a delivery service with zero delivery fee).</p>
    <p>Orders are subject to product availability and payment confirmation. Payment confirmation must occur before the order proceeds to preparation and before the order can be released for collection.</p>
    <p>Customers should check their order at the time of collection where reasonably practicable and contact MST promptly if there is an apparent issue with the order.</p>
    <p class="text-muted small">Detailed collection instructions will be provided upon order confirmation and pickup notification. Once collected, customers are responsible for transferring frozen products to appropriate frozen storage promptly.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>11. Wholesale and Bulk Deliveries</h3>
    <p>Large or wholesale orders may require scheduled delivery arrangements.</p>
    <p class="text-muted small">Specific delivery arrangements may be stated in the quotation, sales confirmation or other commercial agreement.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-globe2 text-primary me-2"></i>12. International / Cross-Border Orders</h3>
    <p>MST's established customer markets are: <strong>Malaysia and Singapore</strong>.</p>
    <p>International / cross-border orders may be considered on a case-by-case basis, subject to product availability, destination requirements, logistics, regulatory requirements and commercial terms.</p>
    <p class="text-muted small">MST does not operate a universal worldwide or automatic international delivery network. Regional and international expansion remains a future development direction.</p>
    <p>Where international or cross-border orders are agreed, customers may be responsible for applicable:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Freight and transport charges</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Customs clearance and documentation</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Import permits and regulatory compliance</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Duties, taxes, and destination fees</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Handling and cold-chain logistics charges</li>
    </ul>
    <p class="text-muted small">unless otherwise agreed in writing.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-clipboard-check text-primary me-2"></i>13. Proof of Delivery</h3>
    <p>Delivery may be confirmed through delivery orders (DO), invoices, proof of delivery, customer signature, digital confirmation, or other reasonable delivery records.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-headset text-primary me-2"></i>14. Contact</h3>
    <div class="card bg-light border-0 shadow-sm p-4 rounded-3">
        <p class="fw-bold fs-6 text-dark mb-2">MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</p>
        <p class="mb-2 text-muted">
            <i class="bi bi-geo-alt-fill text-primary me-2"></i>No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia
            <br>
            <a href="https://maps.google.com/?q=7+Jalan+SILC+2/18+Kawasan+Perindustrian+SILC+79200+Iskandar+Puteri+Johor+Malaysia" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mt-2">
                <i class="bi bi-map me-1"></i> Get Directions (Google Maps)
            </a>
        </p>
        <hr class="my-2 text-muted opacity-25">
        <p class="mb-2">
            <i class="bi bi-envelope-fill text-primary me-2"></i><strong>Email:</strong> 
            <a href="mailto:mikatrading15@gmail.com" class="text-decoration-none fw-semibold">mikatrading15@gmail.com</a>
        </p>
        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>Phone:</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold">+60 13-280 0168</a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> 
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-semibold me-2">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>Chat on WhatsApp
            </a>
        </p>
    </div>
</div>
HTML;

$shipping_zh = <<<'HTML'
<div class="policy-header-badge mb-3">
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
        <i class="bi bi-shield-check me-1"></i> 生效日期：2026年9月30日 | 最近更新：2026年9月30日
    </span>
</div>

<div class="lead text-dark mb-4 fw-normal">
    <p><strong>MST Import and Export Sdn. Bhd.（镁嘉国际贸易有限公司）</strong>供应冷冻海鲜、冷冻肉类、冷冻食品、食材及其他对温度敏感的产品。</p>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-truck text-primary me-2"></i>1. 标准本地配送覆盖范围</h3>
    <p>MST 提供清晰分级的门到门配送安排：</p>
    
    <div class="row g-3 my-2">
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2 px-2 py-1">B2B / 批发</span>
                    <strong class="text-dark">商业订单</strong>
                </div>
                <p class="fs-5 fw-bold text-primary mb-0">标准起送参考金额：RM350</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2 px-2 py-1">B2C / 零售</span>
                    <strong class="text-dark">个人消费订单</strong>
                </div>
                <p class="fs-5 fw-bold text-success mb-0">标准起送参考金额：RM100</p>
            </div>
        </div>
    </div>
    
    <p class="text-muted small mt-2">
        <i class="bi bi-geo-alt me-1 text-primary"></i> <strong>标准本地配送区域：</strong> <code>新山市区（Johor Bahru）及依斯干达公主城 / 努沙再也（Iskandar Puteri / Nusajaya）</code>。
    </p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-cart-check text-primary me-2"></i>2. 低于标准起送参考金额的订单</h3>
    <p>B2C / 零售客户可以任意金额结账。对于低于 RM100 的订单，将根据您的配送区域/邮编收取适用的运输/配送费用。</p>
    <p class="text-muted small">适用的运费将在订单最终确认前向客户明确告知。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-pin-map text-primary me-2"></i>3. 标准本地配送区域以外的订单</h3>
    <p>超出标准本地配送区域的订单不设自动配送服务。</p>
    <p>该等区域的配送安排：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>根据具体情况逐案评估</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>视目的地而定</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>视商品冷链要求而定</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>视物流承运能力而定</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>客户须承担适用的长途/专项运输费用</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-receipt text-primary me-2"></i>4. 配送 / 运输费用标准</h3>
    <p>配送或运输费用取决于以下因素：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>配送地点 / 区域</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>行驶距离</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>订单货量 / 重量</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>车型要求</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>物流特殊要求</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>冷链温控要求</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>配送时段安排</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-calendar-check text-primary me-2"></i>5. 配送时间与时效</h3>
    <p>所有预计送达时间均为估算值，除非书面明确另行约定。</p>
    <p class="text-muted small">配送时效可能受交通状况、天气、公共假日、车辆调配、货源准备、客户收货配合情况或超出 MST 控制范围的不可抗力影响。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-signpost-2 text-primary me-2"></i>6. 配送地址与收货信息</h3>
    <p>客户须提供准确完整的收货地址、联系人姓名、联系电话及特殊收货指引。因客户提供错误或不完整信息导致的延误或额外运费，由客户自行承担。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>7. 冷冻食品收货指引</h3>
    <p>客户应确保在送达时有指定人员负责收货并立即检查商品外观与包装。</p>
    <p>客户应在配送或到店自提被记录为完成之时起 12 小时内，就适用的产品质量问题通知 MST。</p>
    <div class="alert alert-info py-2 px-3 small border-0 rounded-3 my-2">
        <i class="bi bi-info-circle me-1"></i> 详情请参阅我们的 <a href="/zh/policy/refund-policy" class="alert-link fw-semibold">退款与退换政策</a>。
    </div>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-snow2 text-primary me-2"></i>8. 收货后的冷冻储存责任</h3>
    <p>客户在收货后应立即将冷冻商品转入合规的冷冻设施（-18°C或以下）储存。在法律允许的范围内，对于收货后因客户储存不当、擅自解冻、操作不当导致的产品变质，MST 不承担责任。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-exclamation-triangle text-primary me-2"></i>9. 配送失败处理</h3>
    <p>因客户原因（如无人收货、地址有误、联系不上、未遵守收货安排等）导致配送失败的，可能产生等候费、二次配送费、运输费或处理费等额外费用：</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>等候费</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>二次配送费</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>运输费</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>额外处理费</li>
    </ul>
    <p class="text-muted small">但是，如配送失败是由 MST 或 MST 委任的物流服务提供商所造成，则在适用范围内不应向客户收取上述相关费用。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-shop text-primary me-2"></i>10. 门店选购 / 到店自提</h3>
    <div class="mb-2"><span class="badge bg-secondary-subtle text-dark border px-3 py-1 rounded-pill fw-semibold">仅限自提 · 不设配送</span></div>
    <p>选择 门店选购 / 到店自提 的客户须直接前往我们位于依斯干达公主城 SILC 的冷库设施提货。由于是客户自行提货，因此不收取运费（这是自提模式，并非零运费的配送服务）。</p>
    <p>订单视现货库存及付款确认情况而定。订单在备货及交付提货前须完成付款确认。</p>
    <p>客户应在提货时当场核对货品，如有明显问题请立即告知 MST 工作人员。提货后，客户有责任立即妥善冷冻储存。</p>
    <p class="text-muted small">订单确认及提货通知发出后，系统将提供详细的自提指引。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>11. 大宗批发与商业订单配送</h3>
    <p>整柜、托盘及大宗商业批发订单需单独协商排单与专车物流方案。具体条款以报价单或专项商业合同为准。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-globe2 text-primary me-2"></i>12. 国际 / 跨境订单</h3>
    <p>MST 目前已建立的客户市场为：<strong>马来西亚与新加坡</strong>。</p>
    <p>国际 / 跨境订单可根据具体情况逐案考虑，并须视产品供应情况、目的地要求、物流安排、监管要求及商业条款而定。</p>
    <p class="text-muted small">MST 并未运营通用的全球或全自动国际配送网络。区域与国际供应链拓展属于未来发展方向。</p>
    <p>在双方协商一致的情况下，跨境订单的报关、进口许可、关税、目的港费用及专项冷链运输费用由客户按约定承担。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-clipboard-check text-primary me-2"></i>13. 交付凭证</h3>
    <p>配送交付可通过送货单（DO）、发票、签收单、客户签名、数字化确认记录或其他合理的送达凭证进行确认。</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-headset text-primary me-2"></i>14. 联系方式</h3>
    <div class="card bg-light border-0 shadow-sm p-4 rounded-3">
        <p class="fw-bold fs-6 text-dark mb-2">MST Import and Export Sdn. Bhd.（镁嘉国际贸易有限公司）</p>
        <p class="mb-2 text-muted">
            <i class="bi bi-geo-alt-fill text-primary me-2"></i>No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia
            <br>
            <a href="https://maps.google.com/?q=7+Jalan+SILC+2/18+Kawasan+Perindustrian+SILC+79200+Iskandar+Puteri+Johor+Malaysia" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mt-2">
                <i class="bi bi-map me-1"></i> 在 Google Maps 中查看导航路线
            </a>
        </p>
        <hr class="my-2 text-muted opacity-25">
        <p class="mb-2">
            <i class="bi bi-envelope-fill text-primary me-2"></i><strong>电子邮箱：</strong> 
            <a href="mailto:mikatrading15@gmail.com" class="text-decoration-none fw-semibold">mikatrading15@gmail.com</a>
        </p>
        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>电话：</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold">+60 13-280 0168</a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp：</strong> 
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-semibold me-2">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>通过 WhatsApp 咨询
            </a>
        </p>
    </div>
</div>
HTML;

$shipping_bm = <<<'HTML'
<div class="policy-header-badge mb-3">
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
        <i class="bi bi-shield-check me-1"></i> Tarikh Berkuat Kuasa: 30 September 2026 | Terakhir Dikemas Kini: 30 September 2026
    </span>
</div>

<div class="lead text-dark mb-4 fw-normal">
    <p><strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong> membekalkan makanan laut sejuk beku, daging sejuk beku, makanan sejuk beku, bahan ramuan makanan dan produk sensitif suhu yang lain.</p>
</div>

<hr class="my-4 text-muted opacity-25">

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-truck text-primary me-2"></i>1. Liputan Penghantaran Tempatan Standard</h3>
    <p>MST menyediakan penghantaran pintu ke pintu dengan pengaturan penghantaran berasaskan zon dan kategori yang telus:</p>
    
    <div class="row g-3 my-2">
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2 px-2 py-1">B2B / Borong</span>
                    <strong class="text-dark">Pesanan Komersial</strong>
                </div>
                <p class="fs-5 fw-bold text-primary mb-0">Ambang Rujukan Penghantaran: RM350</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 bg-light p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2 px-2 py-1">B2C / Runcit</span>
                    <strong class="text-dark">Pesanan Pengguna</strong>
                </div>
                <p class="fs-5 fw-bold text-success mb-0">Ambang Rujukan Penghantaran: RM100</p>
            </div>
        </div>
    </div>
    
    <p class="text-muted small mt-2">
        <i class="bi bi-geo-alt me-1 text-primary"></i> <strong>Kawasan Liputan Penghantaran Tempatan Standard:</strong> <code>Johor Bahru dan Iskandar Puteri / Nusajaya</code>.
    </p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-cart-check text-primary me-2"></i>2. Pesanan Di Bawah Ambang Rujukan Penghantaran</h3>
    <p>Pelanggan B2C / Runcit boleh melengkapkan pesanan pada sebarang nilai. Bagi pesanan di bawah RM100, surcaj penghantaran atau pengangkutan tambahan mungkin dikenakan mengikut zon penghantaran/poskod anda.</p>
    <p class="text-muted small">Caj penghantaran yang berkenaan akan dimaklumkan kepada pelanggan sebelum pesanan disahkan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-pin-map text-primary me-2"></i>3. Di Luar Kawasan Liputan Standard</h3>
    <p>Penghantaran di luar kawasan tempatan standard (Johor Bahru dan Iskandar Puteri / Nusajaya) tidak disediakan secara automatik.</p>
    <p>Penghantaran di luar kawasan standard boleh:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Dipertimbangkan berdasarkan kes demi kes</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Tertakluk kepada destinasi</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Tertakluk kepada keperluan produk</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Tertakluk kepada keperluan logistik</li>
        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Tertakluk kepada caj pengangkutan yang berkenaan</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-receipt text-primary me-2"></i>4. Caj Penghantaran / Pengangkutan</h3>
    <p>Caj penghantaran atau pengangkutan mungkin bergantung pada faktor-faktor termasuk:</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Lokasi / zon penghantaran</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Jarak perjalanan</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Kuantiti / berat pesanan</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Keperluan kenderaan</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Keperluan logistik khusus</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Keperluan kawalan suhu sejuk beku</li>
        <li class="mb-2"><i class="bi bi-dot text-primary fs-5 me-1"></i>Jadual penghantaran</li>
    </ul>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-calendar-check text-primary me-2"></i>5. Jadual Penghantaran</h3>
    <p>Masa penghantaran adalah anggaran melainkan disahkan secara bertulis.</p>
    <p class="text-muted small">Penghantaran mungkin dipengaruhi oleh trafik, cuaca, cuti umum, ketersediaan kenderaan, ketersediaan produk, atau keadaan di luar kawalan munasabah MST.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-signpost-2 text-primary me-2"></i>6. Alamat Penghantaran</h3>
    <p>Pelanggan mesti memberikan alamat penghantaran yang tepat, nama penerima, nombor telefon dan arahan penerimaan khusus. MST tidak bertanggungjawab atas kelewatan atau caj tambahan yang berpunca daripada maklumat yang tidak tepat yang diberikan oleh pelanggan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-box-seam text-primary me-2"></i>7. Penerimaan Produk Sejuk Beku</h3>
    <p>Pelanggan hendaklah memastikan orang yang diberi kuasa bersedia untuk menerima pesanan dan memeriksa produk serta bungkusan dengan segera semasa penghantaran.</p>
    <p>Pelanggan hendaklah memaklumkan MST mengenai isu kualiti produk yang berkenaan dalam tempoh 12 jam selepas penghantaran atau pengambilan sendiri direkodkan sebagai selesai.</p>
    <div class="alert alert-info py-2 px-3 small border-0 rounded-3 my-2">
        <i class="bi bi-info-circle me-1"></i> Sila rujuk <a href="/bm/policy/refund-policy" class="alert-link fw-semibold">Polisi Bayaran Balik &amp; Pemulangan</a> kami.
    </div>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-snow2 text-primary me-2"></i>8. Penyimpanan Produk Sejuk Beku Selepas Penerimaan</h3>
    <p>Pelanggan bertanggungjawab memindahkan produk sejuk beku ke kemudahan penyimpanan sejuk beku yang betul dengan segera selepas penerimaan. Setakat yang dibenarkan oleh undang-undang, MST tidak bertanggungjawab atas kemerosotan kualiti yang berlaku selepas penghantaran akibat penyimpanan, penyahbekuan atau pengendalian yang tidak betul oleh pelanggan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-exclamation-triangle text-primary me-2"></i>9. Kegagalan Penghantaran</h3>
    <p>Sekiranya penghantaran tidak dapat diselesaikan atas sebab yang berpunca daripada pelanggan (seperti tiada penerima yang sah, alamat salah diberikan, pelanggan tidak dapat dihubungi, atau susunan penerimaan tidak dipatuhi):</p>
    <ul class="list-unstyled ps-3">
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Caj menunggu</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Caj penghantaran semula</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Caj pengangkutan</li>
        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i>Caj pengendalian</li>
    </ul>
    <p>Caj tambahan seperti di atas mungkin dikenakan.</p>
    <p class="text-muted small">Walau bagaimanapun, caj tersebut tidak akan dikenakan bagi kegagalan penghantaran yang berpunca daripada MST atau penyedia logistik yang dilantik oleh MST, setakat yang berkenaan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-shop text-primary me-2"></i>10. Pesanan Walk-in / Pengambilan Sendiri</h3>
    <div class="mb-2"><span class="badge bg-secondary-subtle text-dark border px-3 py-1 rounded-pill fw-semibold">Pengambilan Sendiri sahaja · Tiada penghantaran</span></div>
    <p>Pelanggan yang memilih Pesanan Walk-in / Pengambilan Sendiri bertanggungjawab mengambil pesanan yang telah disahkan secara terus di premis kemudahan SILC kami. Tiada caj penghantaran dikenakan kerana pelanggan mengambil sendiri pesanan tersebut (ini adalah pengambilan sendiri, bukan perkhidmatan penghantaran percuma).</p>
    <p>Pesanan tertakluk kepada ketersediaan produk dan pengesahan pembayaran. Pengesahan pembayaran mesti selesai sebelum pesanan disediakan dan sebelum pesanan boleh diserahkan.</p>
    <p>Pelanggan hendaklah menyemak pesanan mereka semasa pengambilan dan memaklumkan MST dengan segera jika terdapat sebarang isu nyata. Selepas diambil, pelanggan bertanggungjawab memindahkan produk ke storan sejuk beku dengan segera.</p>
    <p class="text-muted small">Arahan pengambilan terperinci akan disediakan setelah pesanan disahkan dan pemberitahuan pengambilan dikeluarkan.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>11. Penghantaran Pukal dan Borong</h3>
    <p>Pesanan borong atau kuantiti besar mungkin memerlukan jadual dan susunan logistik khusus mengikut perjanjian komersial yang dipersetujui.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-globe2 text-primary me-2"></i>12. Pesanan Antarabangsa / Rentas Sempadan</h3>
    <p>Pasaran pelanggan semasa MST yang mantap adalah: <strong>Malaysia dan Singapura</strong>.</p>
    <p>Pesanan antarabangsa / rentas sempadan boleh dipertimbangkan berdasarkan kes demi kes, tertakluk kepada ketersediaan produk, keperluan destinasi, logistik, keperluan kawal selia dan terma komersial.</p>
    <p class="text-muted small">MST tidak mengendalikan rangkaian penghantaran antarabangsa sejagat atau automatik ke seluruh dunia. Pengembangan serantau dan antarabangsa kekal sebagai hala tuju pembangunan masa hadapan.</p>
    <p>Bagi pesanan rentas sempadan yang dipersetujui, pelanggan mungkin bertanggungjawab terhadap caj pengangkutan, pelepasan kastam, permit import, duti dan cukai melainkan dipersetujui sebaliknya secara bertulis.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-clipboard-check text-primary me-2"></i>13. Bukti Penghantaran</h3>
    <p>Penghantaran boleh disahkan melalui nota penghantaran (DO), invois, bukti penghantaran, tandatangan pelanggan, pengesahan digital, atau rekod penghantaran munasabah yang lain.</p>
</div>

<div class="policy-section mb-4">
    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-headset text-primary me-2"></i>14. Hubungi Kami</h3>
    <div class="card bg-light border-0 shadow-sm p-4 rounded-3">
        <p class="fw-bold fs-6 text-dark mb-2">MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</p>
        <p class="mb-2 text-muted">
            <i class="bi bi-geo-alt-fill text-primary me-2"></i>No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia
            <br>
            <a href="https://maps.google.com/?q=7+Jalan+SILC+2/18+Kawasan+Perindustrian+SILC+79200+Iskandar+Puteri+Johor+Malaysia" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mt-2">
                <i class="bi bi-map me-1"></i> Dapatkan Arah (Google Maps)
            </a>
        </p>
        <hr class="my-2 text-muted opacity-25">
        <p class="mb-2">
            <i class="bi bi-envelope-fill text-primary me-2"></i><strong>Emel:</strong> 
            <a href="mailto:mikatrading15@gmail.com" class="text-decoration-none fw-semibold">mikatrading15@gmail.com</a>
        </p>
        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>Telefon:</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold">+60 13-280 0168</a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> 
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-semibold me-2">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>Sembang di WhatsApp
            </a>
        </p>
    </div>
</div>
HTML;


// -------------------------------------------------------------
// PRIVACY POLICY
// -------------------------------------------------------------
$privacy_en = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Effective Date:</strong> September 30, 2026 | <strong>Last Updated:</strong> September 30, 2026
</div>

<p><strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong> (&ldquo;MST&rdquo;, &ldquo;we&rdquo;, &ldquo;us&rdquo; or &ldquo;our&rdquo;) respects your privacy and is committed to protecting the personal information provided to us.</p>

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

<h2>3. Marketing Communications & Consent</h2>
<p>Marketing communications are sent where the relevant consent has been provided or where otherwise permitted by applicable law.</p>
<p>In accordance with our account registration procedures, marketing consent choices are optional and independently selectable:</p>
<ul>
  <li><strong>WhatsApp:</strong> <em>"I agree to receive MST updates via WhatsApp."</em></li>
  <li><strong>Email:</strong> <em>"I agree to receive MST updates via Email."</em></li>
</ul>
<p>Marketing consent remains separate from account registration, separate from acceptance of the Terms & Conditions, and separate from Privacy Policy acknowledgement. Refusing marketing consent does not prevent normal account registration. You may withdraw or update your marketing preferences at any time by contacting us.</p>

<h2>4. Payment & Transaction Information</h2>
<p>Payment or transaction information may be collected or processed as necessary to process and confirm an order. Where applicable, card or payment details may be handled directly by the relevant third-party payment provider in accordance with its own privacy and security practices. MST does not store full card details unless this is specifically required and technically implemented.</p>

<h2>5. Disclosure of Personal Information</h2>
<p>We do not sell, rent or trade your personal information. We may share information with trusted third parties solely to the extent necessary to conduct our business:</p>
<ul>
  <li><strong>Logistics & Transport Partners:</strong> Cold-chain transport providers and couriers for order fulfillment and delivery coordination.</li>
  <li><strong>Payment & Financial Institutions:</strong> Relevant payment providers or banks as necessary to process and confirm authorized transactions.</li>
  <li><strong>Technical & IT Service Providers:</strong> Hosting, database, email, and communication providers that support our operations under strict confidentiality terms.</li>
  <li><strong>Legal & Regulatory Authorities:</strong> When required by applicable Malaysian law, court order, or regulatory bodies.</li>
</ul>

<h2>6. Map & Technical Services</h2>
<p>Our website utilizes <strong>Leaflet with OpenStreetMap map tiles</strong> served locally to display our SILC facility location without loading third-party tracking scripts. For directions, external links to Google Maps are provided for convenience. Clicking an external map link directs you to Google's platform, subject to Google's independent privacy policy.</p>

<h2>7. Cross-Border Data Processing</h2>
<p>Personal information is primarily stored and processed within Malaysia. Where third-party service providers utilized by MST (such as cloud hosting, email delivery infrastructure, messaging services or technical security networks) operate or maintain systems outside Malaysia, personal data may be processed in those jurisdictions solely as necessary to support our website operations and services. MST takes reasonable contractual and operational measures to ensure that personal data processed outside Malaysia is handled securely and in accordance with applicable data protection standards.</p>

<h2>8. Data Retention, Deletion & Anonymisation</h2>
<p>Personal data is retained only for as long as necessary to fulfill the purposes for which it was collected or to comply with legal, tax, and accounting requirements. When information is no longer needed, MST will securely delete, destroy, or anonymise the personal information.</p>

<h2>9. Your Rights Under Malaysian PDPA</h2>
<p>Under the Malaysian Personal Data Protection Act 2010 (PDPA), you have the right to access, correct, or request the deletion of your personal data, subject to legal and contractual obligations. You may also withdraw marketing consent at any time.</p>

<h2>10. Contact Us Regarding Privacy</h2>
<p>If you have any questions or requests regarding your personal data, please contact our Data Protection Officer at:</p>
<p>
  <strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong><br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  Email: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  Phone: <a href="tel:+60132800168">+60 13-280 0168</a> | WhatsApp: <a href="https://wa.me/601112710260">+60 11-1271 0260</a>
</p>
HTML;

$privacy_zh = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>生效日期：</strong>2026年9月30日 | <strong>最近更新：</strong>2026年9月30日
</div>

<p><strong>MST Import and Export Sdn. Bhd.（镁嘉国际贸易有限公司）</strong>（以下简称“MST”、“我们”或“我们的”）高度尊重您的隐私，并致力于保护向我们提供的个人数据信息。</p>

<p>本《隐私政策》说明了当您访问我们的网站、与我们联系、提交咨询或采购需求单（RFQ）、下订单、申请定制化采购、安排配送或自提、或以其他方式与我们互动时，MST 如何收集、使用、披露、储存及保护您的个人信息。</p>

<h2>1. 我们收集的个人数据</h2>
<p>当您与我们的网站或服务互动时，我们可能直接向您收集个人信息，包括：</p>
<ul>
  <li><strong>联络信息：</strong>姓名、企业名称、企业注册编号（SSM / UEN）、电子邮箱、电话号码 / WhatsApp 号码、账单地址、配送地址。</li>
  <li><strong>商业与订单信息：</strong>账户类型（零售、批发、贸易）、商品需求、咨询详情、报价记录、订单历史、付款记录、配送偏好。</li>
  <li><strong>技术与浏览数据：</strong>IP 地址、浏览器类型、设备信息、操作系统、访问页面、停留时间以及 Cookie 数据（详见《Cookie 政策》）。</li>
</ul>

<h2>2. 个人数据的使用目的</h2>
<p>MST 基于正当商业目的使用个人数据，包括：</p>
<ul>
  <li>处理咨询、采购需求单（RFQ）及定制化采购申请；</li>
  <li>处理、确认及履行订单、安排配送及自提服务；</li>
  <li>管理客户账户及商业主体核验（包括批发与贸易账户的 SSM / UEN 审核）；</li>
  <li>提供客户服务，沟通订单状态、库存供应及商业条款；</li>
  <li>遵守适用的法律法规、会计准则及食品安全监管要求；</li>
  <li>维护网站系统安全、防范欺诈及优化网站运行功能。</li>
</ul>

<h2>3. 营销信息与用户同意机制</h2>
<p>营销推广信息仅在客户已明确提供相关同意或适用法律另有允许的情况下发送。</p>
<p>在我们的账户注册流程中，营销选项完全属于自愿选择且彼此独立：</p>
<ul>
  <li><strong>WhatsApp：</strong><em>“我同意通过 WhatsApp 接收 MST 的最新动态。”</em></li>
  <li><strong>电子邮箱：</strong><em>“我同意通过电子邮箱接收 MST 的最新动态。”</em></li>
</ul>
<p>营销同意与账户注册、接受《条款与条件》以及确认《隐私政策》保持严格分离。拒绝勾选营销选项绝不会影响正常的账户注册或常规交易。您可以随时联系我们更新或撤回您的营销偏好设置。</p>

<h2>4. 付款与交易数据</h2>
<p>为处理及确认订单，MST 可能收集或处理必要的付款或交易信息。如适用，银行卡或付款资料可能由相关第三方支付服务提供商直接处理，并受其自身的隐私及安全措施约束。除非相关功能已实际实施且确有必要，否则 MST 不会储存完整的银行卡资料。</p>

<h2>5. 个人信息的共享与披露</h2>
<p>我们绝不出售、出租或交易您的个人信息。我们仅在开展业务所必需的范围内，与受严格保密条款约束的受信任第三方共享必要信息：</p>
<ul>
  <li><strong>物流与运输合作伙伴：</strong>负责订单履行与冷链配送协调的物流承运商；</li>
  <li><strong>支付与金融机构：</strong>用于处理及核验经授权交易的相关支付服务提供商或银行机构；</li>
  <li><strong>技术与 IT 服务提供商：</strong>支持我们网站系统运行的主机、数据库、邮件及通信服务商；</li>
  <li><strong>法律与监管机构：</strong>根据马来西亚适用法律法规、法院命令或监管部门法定要求必须披露的情形。</li>
</ul>

<h2>6. 地图与技术服务</h2>
<p>我们的网站采用通过本地代理加载的 <strong>Leaflet 与 OpenStreetMap 地图瓦片技术</strong>展示依斯干达公主城 SILC 设施位置，不加载任何第三方追踪脚本。如需路线导航，网站提供指向 Google Maps 的外部链接。点击外部地图链接将跳转至 Google 平台，并受 Google 独立隐私政策的约束。</p>

<h2>7. 跨境数据处理</h2>
<p>个人信息主要在马来西亚境内进行储存与处理。在 MST 所使用的第三方技术服务提供商（如云端主机托管、邮件发送系统、通信消息服务或网络安全技术支持）在马来西亚境外运营或维护系统的情况下，个人数据可能会在相关境外司法管辖区进行处理，且仅限于支持我们网站运行与业务服务所必需的范围。MST 采取合理的合同与运营管理措施，确保在马来西亚境外处理的个人数据获得安全保护并符合适用的数据保护标准。</p>

<h2>8. 数据保留、删除与去标识化</h2>
<p>个人数据仅在实现收集目的或满足法定法律、税务和会计要求所需的期限内保留。当信息不再需要时，MST 将以安全方式删除、销毁或对该信息进行去标识化处理（memadam, memusnahkan atau menyahpengenalan maklumat tersebut dengan selamat）。</p>

<h2>9. 您的权利（马来西亚 PDPA）</h2>
<p>根据马来西亚《2010年个人数据保护法》（PDPA），在符合法律及合同约定的前提下，您有权查阅、更正或请求删除您的个人数据。您亦可随时撤回营销同意。</p>

<h2>10. 隐私事务联络方式</h2>
<p>如对您的个人数据有任何疑问或请求，请联系我们的数据保护专员：</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.（镁嘉国际贸易有限公司）</strong><br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  电子邮箱：<a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  电话：<a href="tel:+60132800168">+60 13-280 0168</a> | WhatsApp：<a href="https://wa.me/601112710260">+60 11-1271 0260</a>
</p>
HTML;

$privacy_bm = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Tarikh Berkuat Kuasa:</strong> 30 September 2026 | <strong>Terakhir Dikemas Kini:</strong> 30 September 2026
</div>

<p><strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong> (&ldquo;MST&rdquo;, &ldquo;kami&rdquo; atau &ldquo;kita&rdquo;) menghormati privasi anda dan komited untuk melindungi maklumat peribadi yang diberikan kepada kami.</p>

<p>Polisi Privasi ini menerangkan bagaimana MST mengumpul, menggunakan, mendedahkan, menyimpan dan melindungi maklumat peribadi apabila anda melayari laman web kami, menghubungi kami, mengemukakan pertanyaan atau RFQ, membuat pesanan, memohon penyumberan tersuai, mengatur penghantaran atau pengambilan, atau berinteraksi dengan kami.</p>

<h2>1. Data Peribadi Yang Kami Kumpul</h2>
<p>Kami mungkin mengumpul maklumat peribadi secara langsung daripada anda apabila anda berinteraksi dengan laman web atau perkhidmatan kami, termasuk:</p>
<ul>
  <li><strong>Maklumat Hubungan:</strong> Nama, nama perniagaan, nombor pendaftaran syarikat (SSM/UEN), alamat e-mel, nombor telefon/WhatsApp, alamat pengebilan, alamat penghantaran.</li>
  <li><strong>Butiran Komersial & Pesanan:</strong> Jenis akaun (Runcit, Borong, Dagangan), keperluan produk, butiran pertanyaan, sebut harga, sejarah pesanan, rekod pembayaran, keutamaan penghantaran.</li>
  <li><strong>Data Teknikal & Pelayaran:</strong> Alamat IP, jenis pelayar, maklumat peranti, sistem operasi, halaman yang dilawati, masa yang diluangkan, dan data kuki seperti yang diterangkan dalam Polisi Kuki kami.</li>
</ul>

<h2>2. Bagaimana Kami Menggunakan Data Peribadi Anda</h2>
<p>MST menggunakan data peribadi untuk tujuan perniagaan yang sah, termasuk:</p>
<ul>
  <li>Memproses pertanyaan, permohonan sebut harga (RFQ), dan permohonan penyumberan tersuai</li>
  <li>Memproses, mengesahkan, dan melaksanakan pesanan, penghantaran, dan susunan pengambilan sendiri</li>
  <li>Mengurus akaun pelanggan dan pengesahan perniagaan (termasuk pengesahan SSM/UEN untuk akaun borong dan dagangan)</li>
  <li>Menyediakan sokongan pelanggan dan berkomunikasi mengenai pesanan, ketersediaan stok, dan terma komersial</li>
  <li>Mematuhi keperluan undang-undang, perakaunan, dan kawal selia keselamatan makanan yang terpakai</li>
  <li>Mengekalkan keselamatan laman web, mengesan penipuan, dan menambah baik fungsi laman web</li>
</ul>

<h2>3. Komunikasi Pemasaran & Persetujuan</h2>
<p>Komunikasi pemasaran hanya dihantar apabila persetujuan yang berkaitan telah diberikan atau di mana dibenarkan oleh undang-undang yang terpakai.</p>
<p>Selaras dengan prosedur pendaftaran akaun kami, pilihan persetujuan pemasaran adalah pilihan dan boleh dipilih secara berasingan:</p>
<ul>
  <li><strong>WhatsApp:</strong> <em>"Saya bersetuju untuk menerima maklumat terkini MST melalui WhatsApp."</em></li>
  <li><strong>E-mel:</strong> <em>"Saya bersetuju untuk menerima maklumat terkini MST melalui e-mel."</em></li>
</ul>
<p>Persetujuan pemasaran kekal berasingan daripada pendaftaran akaun, berasingan daripada penerimaan Terma & Syarat, dan berasingan daripada pengakuan Polisi Privasi. Keengganan memberikan persetujuan pemasaran tidak menghalang pendaftaran akaun biasa. Anda boleh menarik balik atau mengemas kini keutamaan pemasaran anda pada bila-bila masa dengan menghubungi kami.</p>

<h2>4. Maklumat Pembayaran & Transaksi</h2>
<p>Maklumat pembayaran atau transaksi mungkin dikumpul atau diproses setakat yang diperlukan untuk memproses dan mengesahkan pesanan. Jika berkenaan, maklumat kad atau pembayaran mungkin dikendalikan secara langsung oleh penyedia pembayaran pihak ketiga yang berkaitan mengikut amalan privasi dan keselamatannya sendiri. MST tidak menyimpan maklumat kad penuh melainkan fungsi tersebut dilaksanakan secara khusus dan benar-benar diperlukan.</p>

<h2>5. Pendedahan Maklumat Peribadi</h2>
<p>Kami tidak menjual, menyewa atau memperdagangkan maklumat peribadi anda. Kami hanya berkongsi maklumat dengan pihak ketiga yang dipercayai setakat yang diperlukan untuk menjalankan perniagaan kami:</p>
<ul>
  <li><strong>Rakan Kongsi Logistik & Penghantaran:</strong> Penyedia pengangkutan kawalan suhu sejuk beku dan kurier untuk pelaksanaan pesanan dan penyelarasan penghantaran.</li>
  <li><strong>Penyedia Pembayaran & Institusi Kewangan:</strong> Penyedia pembayaran atau bank yang berkaitan untuk memproses transaksi yang dibenarkan.</li>
  <li><strong>Penyedia Perkhidmatan Teknikal & IT:</strong> Penyedia pengehosan, pangkalan data, e-mel, dan komunikasi yang menyokong operasi kami di bawah terma kerahsiaan yang ketat.</li>
  <li><strong>Pihak Berkuasa Undang-undang & Kawal Selia:</strong> Apabila dikehendaki oleh undang-undang Malaysia yang terpakai, perintah mahkamah, atau badan kawal selia.</li>
</ul>

<h2>6. Perkhidmatan Peta & Teknikal</h2>
<p>Laman web kami menggunakan <strong>Leaflet dengan jubin peta OpenStreetMap</strong> yang diproksi secara tempatan untuk memaparkan lokasi kemudahan SILC kami tanpa memuatkan skrip penjejakan pihak ketiga. Untuk arah pemanduan, pautan luaran ke Google Maps disediakan untuk kemudahan anda. Mengklik pautan peta luaran akan membawa anda ke platform Google, tertakluk kepada polisi privasi bebas Google.</p>

<h2>7. Pemprosesan Data Rentas Sempadan</h2>
<p>Maklumat peribadi disimpan dan diproses terutamanya di dalam Malaysia. Sekiranya penyedia perkhidmatan pihak ketiga yang digunakan oleh MST (seperti pengehosan awan, infrastruktur penghantaran e-mel, perkhidmatan pemesejan atau rangkaian keselamatan teknikal) mengendalikan atau mengekalkan sistem di luar Malaysia, data peribadi mungkin diproses di bidang kuasa tersebut setakat yang diperlukan untuk menyokong operasi laman web dan perkhidmatan kami. MST mengambil langkah kontraktual dan operasi yang munasabah untuk memastikan bahawa data peribadi yang diproses di luar Malaysia dikendalikan secara selamat dan selaras dengan standard perlindungan data yang terpakai.</p>

<h2>8. Pengekalan Data, Pemadaman & Penyahpengenalan</h2>
<p>Data peribadi disimpan hanya selama yang diperlukan untuk memenuhi tujuan ia dikumpul atau untuk mematuhi keperluan undang-undang, cukai dan perakaunan. Apabila maklumat tidak lagi diperlukan, MST akan memadam, memusnahkan atau menyahpengenalan maklumat tersebut dengan selamat.</p>

<h2>9. Hak Anda di Bawah PDPA Malaysia</h2>
<p>Di bawah Akta Perlindungan Data Peribadi 2010 (PDPA) Malaysia, anda mempunyai hak untuk mengakses, membetulkan, atau meminta pemadaman data peribadi anda, tertakluk kepada kewajipan undang-undang dan kontrak. Anda juga boleh menarik balik persetujuan pemasaran pada bila-bila masa.</p>

<h2>10. Hubungi Kami Mengenai Privasi</h2>
<p>Sekiranya anda mempunyai sebarang pertanyaan atau permintaan mengenai data peribadi anda, sila hubungi Pegawai Perlindungan Data kami di:</p>
<p>
  <strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong><br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  Emel: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  Telefon: <a href="tel:+60132800168">+60 13-280 0168</a> | WhatsApp: <a href="https://wa.me/601112710260">+60 11-1271 0260</a>
</p>
HTML;


// -------------------------------------------------------------
// TERMS AND CONDITIONS
// -------------------------------------------------------------
$terms_en = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Effective Date:</strong> September 30, 2026 | <strong>Last Updated:</strong> September 30, 2026
</div>

<p>Welcome to <strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong> (&ldquo;MST&rdquo;, &ldquo;we&rdquo;, &ldquo;us&rdquo;, or &ldquo;our&rdquo;).</p>

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
<p>Prices may vary according to market conditions, supplier pricing, order quantity, unit of sale, pack size, availability, delivery location, packaging, customer type, wholesale requirements, custom sourcing requirements, and agreed commercial terms.</p>
<p>Prices displayed or quoted may change before an order is confirmed. The confirmed order price is the price applicable at the time the order is formally confirmed by MST.</p>

<h2>5. Quotations, RFQs & Order Requests</h2>
<p>A quotation, RFQ (Request for Quotation) or order request does not by itself constitute acceptance of an order.</p>
<p>An order is confirmed only when MST formally confirms the order and the applicable commercial terms. Product availability, specifications and pricing remain subject to confirmation until the order is confirmed.</p>
<p>Quoted prices are valid only for the period stated in the quotation and remain subject to product availability and the applicable commercial terms.</p>

<h2>6. Wholesale and B2B Orders</h2>
<p>B2B and wholesale orders may be subject to minimum order quantities (MOQ), standard delivery reference thresholds (RM350 B2B reference threshold), payment terms, credit terms where approved, delivery arrangements, customer-specific pricing, and other agreed commercial conditions.</p>
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
<p>Local door-to-door delivery is available based on standard reference delivery thresholds (B2B: RM350, B2C: RM100). These amounts serve as delivery reference thresholds and do not function as hard minimum-order restrictions. Orders below these amounts may still be considered/accepted where delivery service is available, subject to an applicable transportation or delivery charge based on the delivery location / zone and logistics requirements.</p>
<p>Walk-in / Self-Collection orders are strictly for self-collection at our SILC facility and do not receive delivery options.</p>

<h2>13. Customer Responsibility After Delivery</h2>
<p>Customers must provide accurate delivery information and ensure that an authorised person is available to receive the order. Frozen products should be transferred to appropriate frozen storage (-18°C or below) promptly after delivery or collection.</p>

<h2>14. Refund and Return</h2>
<p>Refunds, returns, and damaged/incorrect items are handled in accordance with our <strong>Refund & Return Policy</strong>.</p>
<p>Customers should notify MST of an applicable product quality issue within 12 hours after the delivery or self-collection is recorded as completed.</p>

<h2>15. Intellectual Property</h2>
<p>All content on this website, including text, graphics, logos, images, and software, is the property of MST or its content suppliers and is protected by applicable intellectual property laws.</p>

<h2>16. Limitation of Liability</h2>
<p>To the maximum extent permitted by applicable law, MST shall not be liable for any indirect, incidental, special, exemplary, consequential, or punitive damages arising out of or related to your use of our website, products, or services. Nothing in these Terms excludes or restricts statutory consumer rights that cannot legally be excluded under applicable law.</p>

<h2>17. Governing Law</h2>
<p>These Terms & Conditions shall be governed by and construed in accordance with the laws of Malaysia. Any disputes arising under or in connection with these Terms shall be subject to the jurisdiction of the courts of Malaysia.</p>

<h2>18. Contact Us</h2>
<p>If you have questions regarding these Terms & Conditions, please contact us at:</p>
<p>
  <strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong><br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  Email: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  Phone: <a href="tel:+60132800168">+60 13-280 0168</a> | WhatsApp: <a href="https://wa.me/601112710260">+60 11-1271 0260</a>
</p>
HTML;

$terms_zh = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>生效日期：</strong>2026年9月30日 | <strong>最近更新：</strong>2026年9月30日
</div>

<p>欢迎访问 <strong>MST Import and Export Sdn. Bhd.（镁嘉国际贸易有限公司）</strong>（以下简称“MST”、“我们”或“我们的”）官方网站。</p>

<p>通过访问我们的网站、提交咨询、发送采购需求单（RFQ）、索取报价、下订单、购买产品或使用我们的采购、配送或自提服务，即表示您同意并接受本《条款与条件》。</p>

<h2>1. 业务范围</h2>
<p>MST 供应冷冻海鲜、肉类、冷冻食品、食材原料，提供批发与零售供应、定制化采购以及相关贸易服务。我们的服务面向企业采购（B2B）、批发客户及零售消费者（B2C）。</p>

<h2>2. 网站信息与产品展示</h2>
<p>我们尽合理努力保持网站信息的准确性。然而，产品供应情况、包装规格、品牌、产地、规格参数、重量及产品图片可能会因供应商或市场变化而有所调整。除另有说明外，产品图片仅供参考。</p>

<h2>3. 产品供应与库存</h2>
<p>所有产品供应均视库存情况而定。网站上的产品展示并不构成现货库存的永久保证。在合适情况下，经客户同意，MST 可提供替代产品或替代供应方案。</p>

<h2>4. 价格机制</h2>
<p>产品价格可能根据市场行情、供应商成本、采购数量、销售单位与包装规格、库存供应、配送地点、包装要求、客户类型、批发采购需求、定制化采购需求及双方约定的商业条款而调整。网站显示或初步沟通的价格在订单正式确认前可能会发生变动。最终执行价格以 MST 正式确认订单时的价格为准。</p>

<h2>5. 报价单、采购需求（RFQ）与订购申请</h2>
<p>报价单、RFQ 询价单或订购申请本身并不构成订单的确认接受。只有在 MST 审核并正式确认订单及相关商业条款后，订单方告成立。在订单正式确认前，产品库存、规格及价格均须经进一步核实。报价单中列明的价格仅在报价单注明的有效期内有效，并视产品库存及适用商业条款而定。</p>

<h2>6. 批发 B2B 订单</h2>
<p>B2B 与批发订单可能受最低起订量（MOQ）、标准起送参考金额（B2B 起送参考金额 RM350）、付款条件、经批准的信用账期、配送安排、专属客户价格及其他约定的商业条款约束。批发账户的注册与审核通过，本身并不保证获得固定特惠价格、信用账期、MOQ 或产品供应保证。适用的商业价格与条款可能根据客户、产品、数量、规格、目的地、货源及其他约定需求而有所不同。</p>

<h2>7. 贸易采购与供应</h2>
<p>贸易采购与供应需求（包括集装箱整柜、托盘及大宗商业供应）须根据产品规格、最低起订量、目的地要求、供应商货源、物流安排及其他适用商业要求进行评估。贸易账户的注册或审核通过本身并不保证获得固定的通用贸易价格或信用条款；商业价格与条款根据具体交易需求与最终确认的商业协议执行。</p>

<h2>8. 零售 B2C 订单</h2>
<p>零售客户可根据产品库存、适用的配送起送参考标准（B2C 起送参考金额 RM100）、配送或自提安排以及付款确认情况购买商品。</p>

<h2>9. 定制化采购（Custom Sourcing）</h2>
<p>MST 可根据客户要求，作为专项采购服务协助寻找网站未列出的特定产品。定制化采购属于专项服务需求而非固定零售产品类别，其供应视供应商货源、价格、起订量、规格、交货期及适用安排而定。提交定制化采购申请或 RFQ 并不保证必然有货或必然达成供货。一旦定制化采购订单获得正式确认，相关取消、退换或退款将受制于约定的专项商业条款及我们的《退款与退换政策》。</p>

<h2>10. 订单确认与最终合同效力</h2>
<p>在合理必要的情况下（包括商品缺货、信息不准确、付款未确认、供应商供应变动、无法合理安排配送或商业条款未能达成一致），MST 保留拒绝或取消订单申请的权利。最终确认的订单明确约定适用的产品、数量、销售单位、单价、结算货币（以 RM 为基础）、履约方式（配送或自提）、适用的配送/运输费用以及其他约定的商业条款。</p>

<h2>11. 结算货币与支付</h2>
<p>MST 的基础结算货币为马来西亚令吉（RM / MYR）。其他货币（如 SGD 或 USD）仅作为参考显示。客户须根据约定的付款条件完成付款。对于已获批准的 B2B 账户，可适用约定的信用账期或对公账单条款。</p>

<h2>12. 配送与自提安排</h2>
<p>本地门到门配送基于标准起送参考金额提供（B2B：RM350，B2C：RM100）。该金额为起送参考标准，并非绝对的最低起订限制。低于起送参考金额的订单在具备配送条件时仍可受理，并根据配送区域/邮编及物流要求收取适用的运输/配送费用。门店选购 / 到店自提（Walk-in / Self-Collection）订单严格限定于依斯干达公主城 SILC 设施自提，不享受配送服务。</p>

<h2>13. 交付后的客户责任</h2>
<p>客户须提供准确完整的配送信息，并确保在送达时有指定人员负责收货。冷冻商品在交付或自提完成后，客户须立即将其转入合规的冷冻设施（-18°C或以下）妥善储存。</p>

<h2>14. 退款与退换</h2>
<p>退款、退换及货品瑕疵处理严格按照我们的<strong>《退款与退换政策》</strong>执行。客户应在配送或到店自提被记录为完成之时起 12 小时内，就适用的产品质量问题通知 MST。</p>

<h2>15. 知识产权</h2>
<p>本网站上的所有内容，包括文本、图形、标识、图像及软件，均属 MST 或其内容提供者的财产，受相关知识产权法律的保护。</p>

<h2>16. 责任限制</h2>
<p>在适用法律允许的最大范围内，MST 不对因使用本网站、产品或服务而产生的任何间接、附带、特殊、惩罚性或后果性损害承担责任。本条款不排除或限制适用法律规定不得排除或限制的法定消费者权益。</p>

<h2>17. 管辖法律与争议解决</h2>
<p>本《条款与条件》受马来西亚法律管辖并按其解释。因本条款引起的或与本条款相关的任何争议，均应受马来西亚法院的管辖。</p>

<h2>18. 联系我们</h2>
<p>如对本《条款与条件》有任何疑问，请联系我们：</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.（镁嘉国际贸易有限公司）</strong><br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  电邮：<a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  电话：<a href="tel:+60132800168">+60 13-280 0168</a> | WhatsApp：<a href="https://wa.me/601112710260">+60 11-1271 0260</a>
</p>
HTML;

$terms_bm = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Tarikh Berkuat Kuasa:</strong> 30 September 2026 | <strong>Terakhir Dikemas Kini:</strong> 30 September 2026
</div>

<p>Selamat datang ke <strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong> (&ldquo;MST&rdquo;, &ldquo;kami&rdquo; atau &ldquo;kita&rdquo;).</p>

<p>Dengan melayari laman web kami, membuat pertanyaan, memohon sebut harga (RFQ), mengemukakan pesanan, membeli produk, atau menggunakan perkhidmatan penyumberan, penghantaran atau pengambilan kami, anda bersetuju untuk terikat dengan Terma & Syarat ini.</p>

<h2>1. Skop Perniagaan Kami</h2>
<p>MST membekalkan makanan laut sejuk beku, daging, makanan sejuk beku, bahan ramuan makanan, bekalan borong dan runcit, penyumberan tersuai, dan perkhidmatan dagangan yang berkaitan. Perkhidmatan kami disediakan untuk pelanggan perniagaan-ke-perniagaan (B2B), borong, dan runcit (B2C).</p>

<h2>2. Maklumat Laman Web</h2>
<p>Kami berusaha secara munasabah untuk mengekalkan maklumat yang tepat di laman web kami. Walau bagaimanapun, ketersediaan produk, pembungkusan, penjenamaan, asal usul, spesifikasi, saiz, berat, imej dan maklumat produk lain mungkin berbeza atau berubah mengikut keadaan pembekal atau pasaran. Gambar produk adalah untuk tujuan ilustrasi melainkan dinyatakan sebaliknya.</p>

<h2>3. Ketersediaan Produk</h2>
<p>Semua produk tertakluk kepada ketersediaan stok. Penyenaraian produk di laman web tidak menjamin ketersediaan serta-merta. MST boleh menawarkan produk pengganti atau susunan alternatif yang sesuai dengan persetujuan pelanggan.</p>

<h2>4. Harga</h2>
<p>Harga produk boleh berbeza mengikut keadaan pasaran, harga pembekal, kuantiti pesanan, unit jualan dan saiz pek, ketersediaan produk, lokasi penghantaran, pembungkusan, jenis pelanggan, keperluan borong, keperluan penyumberan tersuai, dan terma komersial yang dipersetujui. Harga yang dipaparkan atau disebut boleh berubah sebelum pesanan disahkan. Harga pesanan yang disahkan ialah harga yang terpakai pada masa pesanan disahkan secara rasmi oleh MST.</p>

<h2>5. Sebut Harga, RFQ & Permohonan Pesanan</h2>
<p>Sebut harga, RFQ (Permohonan Sebut Harga) atau permohonan pesanan secara bersendirian tidak membentuk penerimaan sesuatu pesanan. Pesanan hanya disahkan apabila MST mengesahkan pesanan dan terma komersial yang berkaitan secara rasmi. Ketersediaan produk, spesifikasi dan harga kekal tertakluk kepada pengesahan sehingga pesanan disahkan. Harga yang disebut harga adalah sah hanya untuk tempoh yang dinyatakan dalam sebut harga dan kekal tertakluk kepada ketersediaan produk serta terma komersial yang berkenaan.</p>

<h2>6. Pesanan Borong dan B2B</h2>
<p>Pesanan B2B dan borong mungkin tertakluk kepada kuantiti pesanan minimum (MOQ) jika berkenaan, ambang rujukan penghantaran standard (ambang rujukan B2B RM350), syarat pembayaran, terma kredit jika diluluskan, pengaturan penghantaran, harga khusus komersial, dan syarat komersial lain yang dipersetujui. Pendaftaran dan kelulusan akaun Borong tidak dengan sendirinya menjamin harga tetap, diskaun sejagat, terma kredit, MOQ atau ketersediaan produk; harga dan terma komersial yang berkenaan mungkin berbeza mengikut pelanggan, produk, kuantiti, spesifikasi, destinasi, ketersediaan dan keperluan lain yang dipersetujui.</p>

<h2>7. Penyumberan & Bekalan Dagangan</h2>
<p>Keperluan penyumberan dan bekalan dagangan (termasuk kontena, palet dan bekalan komersial) tertakluk kepada spesifikasi produk, kuantiti minimum, keperluan destinasi, ketersediaan pembekal, pengaturan logistik dan keperluan komersial lain yang terpakai. Pendaftaran atau kelulusan Akaun Dagangan tidak dengan sendirinya menjamin harga dagangan sejagat yang tetap atau terma kredit; harga dan terma komersial disediakan mengikut keperluan transaksi tertentu dan perjanjian komersial yang disahkan.</p>

<h2>8. Pesanan Runcit B2C</h2>
<p>Pelanggan runcit boleh membeli produk tertakluk kepada ketersediaan produk, ambang rujukan penghantaran yang berkenaan (ambang rujukan B2C RM100), pengaturan penghantaran atau pengambilan sendiri serta pengesahan pembayaran.</p>

<h2>9. Penyumberan Tersuai (Custom Sourcing)</h2>
<p>MST boleh membantu mendapatkan produk yang tidak disenaraikan di laman web atas permintaan pelanggan sebagai perkhidmatan penyumberan khusus. Penyumberan Tersuai adalah keperluan perkhidmatan dan bukannya kategori produk standard, serta tertakluk kepada ketersediaan pembekal, harga, kuantiti minimum, spesifikasi, tempoh masa dan pengaturan yang berkenaan. Mengemukakan permohonan penyumberan tersuai atau RFQ tidak menjamin ketersediaan produk, kejayaan penyumberan atau bekalan. Setelah pesanan penyumberan tersuai disahkan secara khusus, pembatalan, pemulangan atau bayaran balik tertakluk kepada terma komersial yang dipersetujui dan Polisi Bayaran Balik & Pemulangan kami.</p>

<h2>10. Penerimaan Pesanan & Pengesahan Akhir</h2>
<p>MST berhak menolak atau membatalkan permohonan pesanan jika perlu secara munasabah, termasuk sekiranya produk tiada dalam stok, maklumat yang diberikan tidak tepat, pembayaran tidak dapat disahkan, ketersediaan pembekal berubah, penghantaran tidak dapat diatur secara munasabah, atau terma komersial tidak dapat dipersetujui. Pesanan akhir yang disahkan menetapkan produk, kuantiti, unit jualan, harga unit, mata wang penyelesaian (asas RM), kaedah pelaksanaan (penghantaran atau pengambilan sendiri), caj penghantaran/pengangkutan jika berkenaan, dan terma komersial lain yang dipersetujui.</p>

<h2>11. Pembayaran & Mata Wang Penyelesaian</h2>
<p>Mata wang penyelesaian asas MST ialah Ringgit Malaysia (RM / MYR). Mata wang lain (seperti SGD atau USD) dipaparkan untuk tujuan rujukan sahaja. Pembayaran mesti diselesaikan mengikut terma pembayaran yang dipersetujui. Bagi akaun B2B yang diluluskan, terma kredit atau invois berasingan mungkin terpakai.</p>

<h2>12. Penghantaran & Pengambilan Sendiri</h2>
<p>Penghantaran tempatan pintu ke pintu disediakan berdasarkan ambang rujukan penghantaran standard (B2B: RM350, B2C: RM100). Amaun ini berfungsi sebagai ambang rujukan penghantaran dan bukan sebagai had pesanan minimum yang mutlak. Pesanan di bawah amaun ini masih boleh dipertimbangkan/diterima jika perkhidmatan penghantaran tersedia, tertakluk kepada caj pengangkutan atau penghantaran yang berkenaan mengikut zon lokasi dan keperluan logistik. Pesanan Walk-in / Pengambilan Sendiri adalah terhad untuk pengambilan sendiri di kemudahan SILC kami dan tidak menerima pilihan penghantaran.</p>

<h2>13. Tanggungjawab Pelanggan Selepas Penghantaran</h2>
<p>Pelanggan mesti memberikan maklumat penghantaran yang tepat dan memastikan individu yang diberi kuasa bersedia untuk menerima pesanan. Produk sejuk beku hendaklah dipindahkan ke storan sejuk beku yang sesuai (-18°C atau ke bawah) dengan segera selepas penghantaran atau pengambilan.</p>

<h2>14. Bayaran Balik dan Pemulangan</h2>
<p>Bayaran balik, pemulangan, dan barangan yang rosak/salah dikendalikan mengikut <strong>Polisi Bayaran Balik & Pemulangan</strong> kami. Pelanggan hendaklah memaklumkan MST mengenai isu kualiti produk yang berkenaan dalam tempoh 12 jam selepas penghantaran atau pengambilan sendiri direkodkan sebagai selesai.</p>

<h2>15. Harta Intelek</h2>
<p>Semua kandungan di laman web ini, termasuk teks, grafik, logo, imej dan perisian, adalah hak milik MST atau pembekal kandungannya dan dilindungi oleh undang-undang harta intelek yang terpakai.</p>

<h2>16. Had Liabiliti</h2>
<p>Setakat yang dibenarkan sepenuhnya oleh undang-undang yang terpakai, MST tidak akan bertanggungjawab ke atas sebarang ganti rugi tidak langsung, sampingan, khas, berbangkit atau punitif yang timbul daripada atau berkaitan dengan penggunaan laman web, produk atau perkhidmatan kami. Tiada apa-apa dalam Terma ini mengecualikan atau mengehadkan hak pengguna berkanun yang tidak boleh dikecualikan di bawah undang-undang yang terpakai.</p>

<h2>17. Undang-Undang Yang Mentadbir</h2>
<p>Terma & Syarat ini ditadbir oleh dan ditafsirkan mengikut undang-undang Malaysia. Sebarang pertikaian yang timbul di bawah atau berkaitan dengan Terma ini hendaklah tertakluk kepada bidang kuasa mahkamah Malaysia.</p>

<h2>18. Hubungi Kami</h2>
<p>Sekiranya anda mempunyai sebarang soalan mengenai Terma & Syarat ini, sila hubungi kami di:</p>
<p>
  <strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong><br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  E-mel: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  Telefon: <a href="tel:+60132800168">+60 13-280 0168</a> | WhatsApp: <a href="https://wa.me/601112710260">+60 11-1271 0260</a>
</p>
HTML;


// -------------------------------------------------------------
// COOKIE POLICY
// -------------------------------------------------------------
$cookie_en = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Effective Date:</strong> September 30, 2026 | <strong>Last Updated:</strong> September 30, 2026
</div>

<p><strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong> (&ldquo;MST&rdquo;, &ldquo;we&rdquo;, &ldquo;us&rdquo; or &ldquo;our&rdquo;) may use cookies and similar technologies on our website to ensure core functionality and support visitor preferences.</p>

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
  <strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong><br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  Email: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  Phone: <a href="tel:+60132800168">+60 13-280 0168</a> | WhatsApp: <a href="https://wa.me/601112710260">+60 11-1271 0260</a>
</p>
HTML;

$cookie_zh = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>生效日期：</strong>2026年9月30日 | <strong>最近更新：</strong>2026年9月30日
</div>

<p><strong>MST Import and Export Sdn. Bhd.（镁嘉国际贸易有限公司）</strong>（以下简称“MST”、“我们”或“我们的”）可能在网站上使用 Cookie 及类似技术，以确保网站核心运行并支持访问者偏好设置。</p>

<p>本《Cookie 政策》说明了 Cookie 的使用方式以及您如何管理偏好设置。本政策应与我们的<strong>《隐私政策》</strong>一并阅读。</p>

<h2>1. 什么是 Cookie？</h2>
<p>Cookie 是当您访问网站时放置在您设备上的小型文本文件。它们被广泛用于确保网站高效运行、记住您的个性化偏好并提供必要的会话安全保障。</p>

<h2>2. 我们使用的 Cookie 类别</h2>
<p>我们的网站采用以下类别的 Cookie：</p>

<h3>A. 绝对必要的 Cookie（始终处于启用状态）</h3>
<p>此类 Cookie 对于网站的基本运行必不可少，支持核心功能，如购物车数据保留、用户登录认证、客户会话安全、语言切换以及安全防护。缺少此类 Cookie，网站将无法正常运行。</p>

<h3>B. 偏好与功能性 Cookie（自选）</h3>
<p>此类 Cookie 允许网站记住您的选择（如首选货币显示、区域或界面偏好设置），从而提供更加贴合需求的浏览体验。</p>

<h3>C. 技术与安全 Cookie</h3>
<p>此类 Cookie 有助于保障交易安全并防止表单滥用与垃圾提交（例如在咨询或注册表单中使用的 Google reCAPTCHA 安全核验）。</p>

<h2>3. MST 网站采用的地图技术</h2>
<p>为了展示我们位于依斯干达公主城 SILC 的设施位置，我们采用 <strong>Leaflet 结合 OpenStreetMap 地图瓦片技术</strong>。这些地图瓦片直接通过我们的服务器进行本地代理加载，以保护您的隐私并避免产生第三方追踪 Cookie。我们不会在您的浏览器中加载第三方地图追踪脚本。</p>

<h2>4. 如何管理您的 Cookie 偏好</h2>
<p>您可随时通过点击网站底部的<strong>“Cookie 设置”</strong>按钮来管理或修改您的偏好选择。您也可以通过浏览器设置来管理 Cookie（如阻止或清除 Cookie）。</p>

<h2>5. 联系我们</h2>
<p>如对我们使用 Cookie 有任何疑问，请联系我们：</p>
<p>
  <strong>MST Import and Export Sdn. Bhd.（镁嘉国际贸易有限公司）</strong><br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  电子邮箱：<a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  电话：<a href="tel:+60132800168">+60 13-280 0168</a> | WhatsApp：<a href="https://wa.me/601112710260">+60 11-1271 0260</a>
</p>
HTML;

$cookie_bm = <<<'HTML'
<div class="legal-doc-meta" style="margin-bottom: 24px; padding: 12px 18px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; font-size: 0.9rem; color: #475569;">
    <strong>Tarikh Berkuat Kuasa:</strong> 30 September 2026 | <strong>Terakhir Dikemas Kini:</strong> 30 September 2026
</div>

<p><strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong> (&ldquo;MST&rdquo;, &ldquo;kami&rdquo; atau &ldquo;kita&rdquo;) mungkin menggunakan kuki dan teknologi serupa di laman web kami untuk memastikan kefungsian teras dan menyokong keutamaan pelawat.</p>

<p>Polisi Kuki ini menerangkan bagaimana kuki digunakan dan bagaimana anda boleh mengurus keutamaan anda. Polisi ini hendaklah dibaca bersama dengan <strong>Polisi Privasi</strong> kami.</p>

<h2>1. Apakah Itu Kuki?</h2>
<p>Kuki ialah fail teks kecil yang diletakkan pada peranti anda oleh laman web yang anda lawati. Ia digunakan secara meluas untuk memastikan laman web berfungsi dengan cekap, mengingati keutamaan anda, dan menyediakan keselamatan sesi yang diperlukan.</p>

<h2>2. Kategori Kuki Yang Kami Gunakan</h2>
<p>Laman web kami menggunakan kategori kuki berikut:</p>

<h3>A. Kuki Yang Sangat Diperlukan (Sentiasa Aktif)</h3>
<p>Kuki ini penting untuk operasi laman web kami, membolehkan ciri teras seperti pengekalan troli beli-belah, pengesahan pengguna, keselamatan sesi pelanggan, pilihan bahasa, dan pencegahan penipuan. Laman web tidak dapat berfungsi dengan betul tanpa kuki ini.</p>

<h3>B. Kuki Keutamaan & Kefungsian (Pilihan)</h3>
<p>Kuki ini membolehkan laman web mengingati pilihan yang anda buat (seperti paparan mata wang pilihan, wilayah, atau tetapan antara muka) untuk menyediakan pengalaman yang lebih disesuaikan.</p>

<h3>C. Kuki Teknikal & Keselamatan</h3>
<p>Kuki ini membantu mengekalkan transaksi yang selamat dan melindungi borang kami daripada spam dan penyalahgunaan (cth. Google reCAPTCHA semasa penyerahan pertanyaan/pendaftaran).</p>

<h2>3. Teknologi Peta Yang Digunakan di Laman Web MST</h2>
<p>Untuk memaparkan lokasi kemudahan kami di Iskandar Puteri, kami menggunakan <strong>Leaflet dengan jubin peta OpenStreetMap</strong>. Jubin peta ini diproksi secara langsung melalui pelayan kami untuk menghormati privasi anda dan mengelakkan kuki penjejakan pihak ketiga. Kami tidak memuatkan skrip penjejakan peta pihak ketiga ke dalam pelayar anda.</p>

<h2>4. Cara Mengurus Keutamaan Kuki Anda</h2>
<p>Anda boleh mengurus atau menukar keutamaan kuki anda pada bila-bila masa dengan mengklik butang <strong>Tetapan Kuki</strong> di bahagian bawah laman web kami. Anda juga boleh mengawal kuki melalui tetapan pelayar web anda (cth. menyekat atau memadam kuki).</p>

<h2>5. Hubungi Kami</h2>
<p>Sekiranya anda mempunyai sebarang pertanyaan mengenai penggunaan kuki kami, sila hubungi kami di:</p>
<p>
  <strong>MST Import and Export Sdn. Bhd. (镁嘉国际贸易有限公司)</strong><br>
  No. 7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia<br>
  Emel: <a href="mailto:mikatrading15@gmail.com">mikatrading15@gmail.com</a><br>
  Telefon: <a href="tel:+60132800168">+60 13-280 0168</a> | WhatsApp: <a href="https://wa.me/601112710260">+60 11-1271 0260</a>
</p>
HTML;

$allPolicies = [
    'refund-policy' => [
        'en' => $refund_en,
        'zh' => $refund_zh,
        'bm' => $refund_bm,
        'title' => 'Refund & Return Policy',
        'title_zh' => '退款与退换政策',
        'title_bm' => 'Polisi Bayaran Balik & Pemulangan',
    ],
    'shipping-policy' => [
        'en' => $shipping_en,
        'zh' => $shipping_zh,
        'bm' => $shipping_bm,
        'title' => 'Shipping & Delivery Policy',
        'title_zh' => '配送与运输政策',
        'title_bm' => 'Polisi Penghantaran & Pengangkutan',
    ],
    'privacy-policy' => [
        'en' => $privacy_en,
        'zh' => $privacy_zh,
        'bm' => $privacy_bm,
        'title' => 'Privacy Policy',
        'title_zh' => '隐私政策',
        'title_bm' => 'Polisi Privasi',
    ],
    'terms-and-conditions' => [
        'en' => $terms_en,
        'zh' => $terms_zh,
        'bm' => $terms_bm,
        'title' => 'Terms & Conditions',
        'title_zh' => '条款与条件',
        'title_bm' => 'Terma & Syarat',
    ],
    'cookie-policy' => [
        'en' => $cookie_en,
        'zh' => $cookie_zh,
        'bm' => $cookie_bm,
        'title' => 'Cookie Policy',
        'title_zh' => 'Cookie 政策',
        'title_bm' => 'Polisi Kuki',
    ],
];

// 3. Update MySQL Database
foreach ($allPolicies as $slug => $data) {
    Policy::updateOrCreate(
        ['slug' => $slug],
        [
            'title' => $data['title'],
            'title_zh' => $data['title_zh'],
            'title_bm' => $data['title_bm'],
            'content' => $data['en'],
            'content_zh' => $data['zh'],
            'content_bm' => $data['bm'],
            'status' => 'published',
            'updated_at' => '2026-09-30 12:00:00',
        ]
    );
    echo "✓ MySQL Policy updated: {$slug}\n";
}

// 4. Update SQLite Database for parity
$sqlitePath = database_path('database.sqlite');
if (file_exists($sqlitePath)) {
    $pdo = new PDO("sqlite:{$sqlitePath}");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach ($allPolicies as $slug => $data) {
        $stmt = $pdo->prepare("
            UPDATE policies 
            SET title = :title,
                title_zh = :title_zh,
                title_bm = :title_bm,
                content = :content,
                content_zh = :content_zh,
                content_bm = :content_bm,
                status = 'published',
                updated_at = '2026-09-30 12:00:00'
            WHERE slug = :slug
        ");
        $stmt->execute([
            ':title' => $data['title'],
            ':title_zh' => $data['title_zh'],
            ':title_bm' => $data['title_bm'],
            ':content' => $data['en'],
            ':content_zh' => $data['zh'],
            ':content_bm' => $data['bm'],
            ':slug' => $slug,
        ]);
        echo "✓ SQLite Policy updated: {$slug}\n";
    }

    // Sync translations to SQLite as well
    foreach ($translationUpdates as $suffix => $vals) {
        $stmt = $pdo->prepare("
            UPDATE translations
            SET `text_en` = :text_en,
                `text_zh` = :text_zh,
                `text_bm` = :text_bm,
                `updated_at` = datetime('now')
            WHERE `key` LIKE :pattern
        ");
        $stmt->execute([
            ':text_en' => $vals['en'],
            ':text_zh' => $vals['zh'],
            ':text_bm' => $vals['bm'],
            ':pattern' => "%{$suffix}",
        ]);
    }
    echo "✓ SQLite Translations synced.\n";
}

echo "\n=======================================================\n";
echo "SYNC COMPLETED SUCCESSFULLY!\n";
echo "=======================================================\n";
