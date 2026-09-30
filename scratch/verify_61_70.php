<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Policy;
use Illuminate\Http\Request;

echo "=========================================================\n";
echo "=== COMPREHENSIVE AUDIT & VERIFICATION: ITEMS #61-#70 ===\n";
echo "=========================================================\n\n";

$errors = [];
$warnings = [];

function check($cond, $successMsg, $failMsg) {
    global $errors;
    if ($cond) {
        echo "    ✓ " . $successMsg . "\n";
    } else {
        echo "    ❌ ERROR: " . $failMsg . "\n";
        $errors[] = $failMsg;
    }
}

// 1. Check Policy Contents in Database
echo "1. Auditing Legal Policies Database Contents...\n";
$policies = Policy::all()->keyBy('slug');

// 61. 12-Hour Complaint Period
echo "\n--- #61: 12-Hour Complaint Period Check ---\n";
$en_12 = "Customers should notify MST of an applicable product quality issue within 12 hours after the delivery or self-collection is recorded as completed.";
$zh_12 = "客户应在配送或到店自提被记录为完成之时起 12 小时内，就适用的产品质量问题通知 MST。";
$bm_12 = "Pelanggan hendaklah memaklumkan MST mengenai isu kualiti produk yang berkenaan dalam tempoh 12 jam selepas penghantaran atau pengambilan sendiri direkodkan sebagai selesai.";

foreach (['refund-policy', 'shipping-policy', 'terms-and-conditions'] as $slug) {
    $p = $policies[$slug] ?? null;
    check($p !== null, "Policy {$slug} exists", "Policy {$slug} missing");
    if ($p) {
        check(str_contains($p->content, $en_12), "[{$slug} EN] Contains exact 12h completion wording", "[{$slug} EN] Missing exact 12h completion wording");
        check(str_contains($p->content_zh, $zh_12), "[{$slug} ZH] Contains exact 12h completion wording", "[{$slug} ZH] Missing exact 12h completion wording");
        check(str_contains($p->content_bm, $bm_12), "[{$slug} BM] Contains exact 12h completion wording", "[{$slug} BM] Missing exact 12h completion wording");
    }
}

// 62. Refund Policy Product Condition & BM (thawed) Check
echo "\n--- #62: Product Condition & BM '(thawed)' residue ---\n";
$ref = $policies['refund-policy'] ?? null;
if ($ref) {
    check(!str_contains($ref->content_bm, '(thawed)'), "[refund-policy BM] Free of '(thawed)' residue", "[refund-policy BM] Contains '(thawed)' residue");
    check(str_contains($ref->content_bm, 'Tidak dinyahbeku') || str_contains($ref->content_bm, 'dinyahbeku'), "[refund-policy BM] Uses correct 'dinyahbeku' terminology", "[refund-policy BM] Missing 'dinyahbeku' terminology");
    check(str_contains($ref->content, 'Frozen') && str_contains($ref->content, 'Not thawed'), "[refund-policy EN] Inspectable condition requirements present", "[refund-policy EN] Missing inspectable conditions");
    check(str_contains($ref->content_zh, '保持冷冻状态') && str_contains($ref->content_zh, '未解冻'), "[refund-policy ZH] Inspectable condition requirements present", "[refund-policy ZH] Missing inspectable conditions");
}

// 63. Refund Policy Cooking/Taste & Statutory Rights
echo "\n--- #63: Cooking / Taste / Statutory Rights ---\n";
$en_stat = "Nothing in this policy excludes or restricts any rights or remedies that cannot legally be excluded or restricted under applicable law.";
$zh_stat = "本政策不排除或限制适用法律规定不得排除或限制的任何权利或补救措施。";
$bm_stat = "Polisi ini tidak mengecualikan atau mengehadkan mana-mana hak atau remedi yang tidak boleh dikecualikan atau dihadkan di bawah undang-undang yang terpakai.";

if ($ref) {
    check(str_contains($ref->content, $en_stat), "[refund-policy EN] Contains statutory rights clause", "[refund-policy EN] Missing statutory rights clause");
    check(str_contains($ref->content_zh, $zh_stat), "[refund-policy ZH] Contains statutory rights clause", "[refund-policy ZH] Missing statutory rights clause");
    check(str_contains($ref->content_bm, $bm_stat), "[refund-policy BM] Contains statutory rights clause", "[refund-policy BM] Missing statutory rights clause");
}

// 64. Refund / Privacy Policy BM Translation Error
echo "\n--- #64: BM Translation Error ('memadam... memadam') ---\n";
$priv = $policies['privacy-policy'] ?? null;
if ($priv) {
    check(!str_contains($priv->content_bm, 'memadam, memusnahkan atau memadam nama'), "[privacy-policy BM] No duplicate 'memadam... memadam' text", "[privacy-policy BM] Found duplicate 'memadam... memadam'");
    check(str_contains($priv->content_bm, 'memadam, memusnahkan atau menyahpengenalan maklumat tersebut dengan selamat'), "[privacy-policy BM] Contains correct 'menyahpengenalan' text", "[privacy-policy BM] Missing correct 'menyahpengenalan' text");
}

// 65. Contact Info & WhatsApp Links
echo "\n--- #65: Contact Info & WhatsApp Links ---\n";
foreach ($policies as $slug => $p) {
    foreach (['content' => 'EN', 'content_zh' => 'ZH', 'content_bm' => 'BM'] as $field => $loc) {
        $text = $p->$field;
        // Verify wa.me link target matches 601112710260
        preg_match_all('/href=["\']https:\/\/wa\.me\/([^"\']+)["\']/i', $text, $waMatches);
        if (!empty($waMatches[1])) {
            foreach ($waMatches[1] as $target) {
                check(str_starts_with($target, '601112710260'), "[{$slug} {$loc}] wa.me target is 601112710260 ($target)", "[{$slug} {$loc}] Invalid wa.me target: $target");
            }
        }
        // Verify phone link
        preg_match_all('/href=["\']tel:([^"\']+)["\']/i', $text, $telMatches);
        if (!empty($telMatches[1])) {
            foreach ($telMatches[1] as $target) {
                check(str_contains($target, '60132800168'), "[{$slug} {$loc}] tel target is +60132800168 ($target)", "[{$slug} {$loc}] Invalid tel target: $target");
            }
        }
    }
}

// 66. Shipping Policy Failed Delivery
echo "\n--- #66: Shipping Policy Failed Delivery ---\n";
$ship = $policies['shipping-policy'] ?? null;
$en_fail = "However, such charges will not apply to delivery failures caused by MST or a logistics provider appointed by MST, to the extent applicable.";
$zh_fail = "但是，如配送失败是由 MST 或 MST 委任的物流服务提供商所造成，则在适用范围内不应向客户收取上述相关费用。";
$bm_fail = "Walau bagaimanapun, caj tersebut tidak akan dikenakan bagi kegagalan penghantaran yang berpunca daripada MST atau penyedia logistik yang dilantik oleh MST, setakat yang berkenaan.";

if ($ship) {
    check(str_contains($ship->content, $en_fail), "[shipping-policy EN] Failed delivery MST protection clause present", "[shipping-policy EN] Missing failed delivery MST protection clause");
    check(str_contains($ship->content_zh, $zh_fail), "[shipping-policy ZH] Failed delivery MST protection clause present", "[shipping-policy ZH] Missing failed delivery MST protection clause");
    check(str_contains($ship->content_bm, $bm_fail), "[shipping-policy BM] Failed delivery MST protection clause present", "[shipping-policy BM] Missing failed delivery MST protection clause");
}

// 67. Shipping Policy Walk-In Terminology
echo "\n--- #67: Shipping Policy Walk-In Terminology ---\n";
if ($ship) {
    check(str_contains($ship->content, 'Walk-in / Self-Collection'), "[shipping-policy EN] Uses 'Walk-in / Self-Collection'", "[shipping-policy EN] Missing 'Walk-in / Self-Collection'");
    check(str_contains($ship->content_zh, '门店选购 / 到店自提'), "[shipping-policy ZH] Uses '门店选购 / 到店自提'", "[shipping-policy ZH] Missing '门店选购 / 到店自提'");
    check(str_contains($ship->content_bm, 'Pesanan Walk-in / Pengambilan Sendiri'), "[shipping-policy BM] Uses 'Pesanan Walk-in / Pengambilan Sendiri'", "[shipping-policy BM] Missing 'Pesanan Walk-in / Pengambilan Sendiri'");
    check(!str_contains($ship->content_bm, 'Pesanan Masuk Sendiri'), "[shipping-policy BM] No legacy 'Pesanan Masuk Sendiri'", "[shipping-policy BM] Found legacy 'Pesanan Masuk Sendiri'");
}

// 68. Shipping Policy International
echo "\n--- #68: Shipping Policy International Case-by-case ---\n";
$en_intl = "International / cross-border orders may be considered on a case-by-case basis, subject to product availability, destination requirements, logistics, regulatory requirements and commercial terms.";
$zh_intl = "国际 / 跨境订单可根据具体情况逐案考虑，并须视产品供应情况、目的地要求、物流安排、监管要求及商业条款而定。";
$bm_intl = "Pesanan antarabangsa / rentas sempadan boleh dipertimbangkan berdasarkan kes demi kes, tertakluk kepada ketersediaan produk, keperluan destinasi, logistik, keperluan kawal selia dan terma komersial.";

if ($ship) {
    check(str_contains($ship->content, $en_intl), "[shipping-policy EN] International case-by-case statement verified", "[shipping-policy EN] Missing international case-by-case statement");
    check(str_contains($ship->content_zh, $zh_intl), "[shipping-policy ZH] International case-by-case statement verified", "[shipping-policy ZH] Missing international case-by-case statement");
    check(str_contains($ship->content_bm, $bm_intl), "[shipping-policy BM] International case-by-case statement verified", "[shipping-policy BM] Missing international case-by-case statement");
}

// 69. Privacy Policy Marketing Consent
echo "\n--- #69: Privacy Policy Marketing Consent ---\n";
if ($priv) {
    check(str_contains($priv->content, 'Marketing communications are sent where the relevant consent has been provided'), "[privacy-policy EN] Marketing consent explanation verified", "[privacy-policy EN] Missing marketing consent explanation");
    check(str_contains($priv->content, 'I agree to receive MST updates via WhatsApp.'), "[privacy-policy EN] WhatsApp consent string verified", "[privacy-policy EN] Missing WhatsApp consent string");
    check(str_contains($priv->content, 'I agree to receive MST updates via Email.'), "[privacy-policy EN] Email consent string verified", "[privacy-policy EN] Missing Email consent string");
    
    check(str_contains($priv->content_zh, '我同意通过 WhatsApp 接收 MST 的最新动态。'), "[privacy-policy ZH] WhatsApp consent string verified", "[privacy-policy ZH] Missing WhatsApp consent string");
    check(str_contains($priv->content_zh, '我同意通过电子邮箱接收 MST 的最新动态。'), "[privacy-policy ZH] Email consent string verified", "[privacy-policy ZH] Missing Email consent string");

    check(str_contains($priv->content_bm, 'Saya bersetuju untuk menerima maklumat terkini MST melalui WhatsApp.'), "[privacy-policy BM] WhatsApp consent string verified", "[privacy-policy BM] Missing WhatsApp consent string");
    check(str_contains($priv->content_bm, 'Saya bersetuju untuk menerima maklumat terkini MST melalui e-mel.'), "[privacy-policy BM] Email consent string verified", "[privacy-policy BM] Missing Email consent string");
}

// 70. Privacy Policy Payment Data
echo "\n--- #70: Privacy Policy Payment Data Neutrality ---\n";
$en_pay = "Payment or transaction information may be collected or processed as necessary to process and confirm an order. Where applicable, card or payment details may be handled directly by the relevant third-party payment provider in accordance with its own privacy and security practices. MST does not store full card details unless this is specifically required and technically implemented.";
$zh_pay = "为处理及确认订单，MST 可能收集或处理必要的付款或交易信息。如适用，银行卡或付款资料可能由相关第三方支付服务提供商直接处理，并受其自身的隐私及安全措施约束。除非相关功能已实际实施且确有必要，否则 MST 不会储存完整的银行卡资料。";
$bm_pay = "Maklumat pembayaran atau transaksi mungkin dikumpul atau diproses setakat yang diperlukan untuk memproses dan mengesahkan pesanan. Jika berkenaan, maklumat kad atau pembayaran mungkin dikendalikan secara langsung oleh penyedia pembayaran pihak ketiga yang berkaitan mengikut amalan privasi dan keselamatannya sendiri. MST tidak menyimpan maklumat kad penuh melainkan fungsi tersebut dilaksanakan secara khusus dan benar-benar diperlukan.";

if ($priv) {
    check(str_contains($priv->content, $en_pay), "[privacy-policy EN] Payment data neutrality verified", "[privacy-policy EN] Missing payment data neutrality statement");
    check(str_contains($priv->content_zh, $zh_pay), "[privacy-policy ZH] Payment data neutrality verified", "[privacy-policy ZH] Missing payment data neutrality statement");
    check(str_contains($priv->content_bm, $bm_pay), "[privacy-policy BM] Payment data neutrality verified", "[privacy-policy BM] Missing payment data neutrality statement");
}

// 2. HTTP Route Checks
echo "\n2. Testing Live HTTP Policy Routes (EN, ZH, BM)...\n";
$httpKernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$policySlugs = ['privacy-policy', 'terms-and-conditions', 'refund-policy', 'shipping-policy', 'cookie-policy'];
$locales = ['en', 'zh', 'bm'];

foreach ($policySlugs as $slug) {
    foreach ($locales as $loc) {
        $uri = "/{$loc}/policy/{$slug}";
        $request = Request::create($uri, 'GET');
        $response = $httpKernel->handle($request);
        $status = $response->getStatusCode();
        $content = $response->getContent();
        if (str_starts_with($content, "\x1f\x8b")) {
            $content = gzdecode($content);
        }

        check($status === 200, "Route {$uri} returned HTTP 200", "Route {$uri} returned HTTP {$status}");
        check(strlen($content) > 1000, "Route {$uri} returned valid rendered content (" . strlen($content) . " bytes)", "Route {$uri} content suspiciously short");
        $httpKernel->terminate($request, $response);
    }
}

echo "\n=========================================================\n";
if (empty($errors)) {
    echo "🎉 ALL TESTS PASSED! 0 ISSUES FOUND ACROSS SECTIONS #61-#70.\n";
} else {
    echo "❌ AUDIT COMPLETED WITH " . count($errors) . " ISSUES.\n";
}
echo "=========================================================\n";
