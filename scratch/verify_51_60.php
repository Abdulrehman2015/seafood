<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Translation;
use App\Services\TranslationService;

app(TranslationService::class)->clearCache();

echo "=========================================================\n";
echo "=== COMPREHENSIVE AUDIT & VERIFICATION: ITEMS #51-#60 ===\n";
echo "=========================================================\n\n";

$issues = [];

// ─── 1. DATABASE & TRANSLATION CHECKS ────────────────────────────────────────
echo "1. Checking Database & Lang Terminology...\n";

// Check Perolehan Tersuai in MySQL
$bmPerolehan = DB::table('translations')->where('text_bm', 'LIKE', '%Perolehan Tersuai%')->get();
if ($bmPerolehan->count() > 0) {
    foreach ($bmPerolehan as $r) {
        $issues[] = "MySQL translation [{$r->group}.{$r->key}] contains 'Perolehan Tersuai': '{$r->text_bm}'";
    }
} else {
    echo "  ✓ MySQL translations: 0 instances of 'Perolehan Tersuai' found.\n";
}

// Check 客制化 in MySQL
$zhOldCust = DB::table('translations')->where('text_zh', 'LIKE', '%客制化%')->get();
if ($zhOldCust->count() > 0) {
    foreach ($zhOldCust as $r) {
        $issues[] = "MySQL translation [{$r->group}.{$r->key}] contains '客制化': '{$r->text_zh}'";
    }
} else {
    echo "  ✓ MySQL translations: 0 instances of '客制化' found.\n";
}

// ─── 2. CONTACT US ROUTE TESTING (EN, ZH, BM) ────────────────────────────────
echo "\n2. Testing Contact Us Pages (EN, ZH, BM)...\n";

$contactRoutes = [
    'en' => [
        'uri' => '/en/contact',
        'market' => 'Serving Customers in Malaysia and Singapore',
        'rfq_sub' => 'Wholesale B2B & RFQ for Custom Sourcing',
        'disclaimer' => 'Product availability, specifications, pricing and supply arrangements are subject to confirmation. Submitting an RFQ does not guarantee product availability or supply.',
    ],
    'zh' => [
        'uri' => '/zh/contact',
        'market' => '服务马来西亚与新加坡客户',
        'rfq_sub' => '批发 B2B 与定制化采购 RFQ',
        'disclaimer' => '产品供应情况、规格参数、价格及供应方案均须经最终确认。提交 RFQ 询价不代表已锁定库存或保证供应。',
    ],
    'bm' => [
        'uri' => '/bm/contact',
        'market' => 'Melayani pelanggan di Malaysia dan Singapura',
        'rfq_sub' => 'Borong B2B & RFQ Penyumberan Tersuai',
        'disclaimer' => 'Ketersediaan produk, spesifikasi, harga dan susunan bekalan adalah tertakluk kepada pengesahan. Menghantar RFQ tidak menjamin ketersediaan produk atau jaminan bekalan.',
    ],
];

foreach ($contactRoutes as $loc => $data) {
    $req = Request::create($data['uri'], 'GET');
    $resp = $kernel->handle($req);
    $content = $resp->getContent();
    if (str_starts_with($content, "\x1f\x8b")) {
        $content = gzdecode($content);
    }

    echo "  Testing {$data['uri']} (HTTP {$resp->getStatusCode()}):\n";
    if ($resp->getStatusCode() !== 200) {
        $issues[] = "Route {$data['uri']} failed with HTTP {$resp->getStatusCode()}";
        continue;
    }

    if (!str_contains($content, $data['market'])) {
        $issues[] = "Contact page ({$loc}) missing market text: '{$data['market']}'";
    } else {
        echo "    ✓ Market positioning verified: '{$data['market']}'\n";
    }

    if (!str_contains($content, $data['rfq_sub'])) {
        $issues[] = "Contact page ({$loc}) missing RFQ sub text: '{$data['rfq_sub']}'";
    } else {
        echo "    ✓ RFQ sub header verified: '{$data['rfq_sub']}'\n";
    }

    if (!str_contains($content, $data['disclaimer'])) {
        $issues[] = "Contact page ({$loc}) missing RFQ disclaimer: '{$data['disclaimer']}'";
    } else {
        echo "    ✓ RFQ disclaimer verified.\n";
    }
}

// ─── 3. LEGAL POLICY PAGES AUDIT (5 POLICIES × 3 LANGUAGES) ─────────────────
echo "\n3. Testing Legal Policy Pages across All 5 Policies & 3 Languages...\n";

$policies = ['privacy-policy', 'terms-and-conditions', 'refund-policy', 'shipping-policy', 'cookie-policy'];

foreach ($policies as $pSlug) {
    foreach (['en', 'zh', 'bm'] as $loc) {
        $uri = "/{$loc}/policy/{$pSlug}";
        $req = Request::create($uri, 'GET');
        $resp = $kernel->handle($req);
        $content = $resp->getContent();
        if (str_starts_with($content, "\x1f\x8b")) {
            $content = gzdecode($content);
        }

        if ($resp->getStatusCode() !== 200) {
            $issues[] = "Policy {$uri} returned HTTP {$resp->getStatusCode()}";
            continue;
        }

        // Check English UI residues in ZH and BM
        if ($loc === 'zh') {
            $residuesZh = [
                'Store Policy & Legal Notice',
                'Official MST Document',
                'Have Questions?',
                'Policies & Guidelines',
                'Last Updated:',
                'Print this policy',
            ];
            foreach ($residuesZh as $resStr) {
                if (str_contains($content, $resStr)) {
                    $issues[] = "ZH Policy ({$pSlug}) contains English residue: '{$resStr}'";
                }
            }
        }

        if ($loc === 'bm') {
            $residuesBm = [
                'Store Policy & Legal Notice',
                'Official MST Document',
                'Have Questions?',
                'Policies & Guidelines',
                'Last Updated:',
                'Print this policy',
            ];
            foreach ($residuesBm as $resStr) {
                if (str_contains($content, $resStr)) {
                    $issues[] = "BM Policy ({$pSlug}) contains English residue: '{$resStr}'";
                }
            }
        }

        // Specific checks for terms-and-conditions
        if ($pSlug === 'terms-and-conditions') {
            if ($loc === 'en') {
                if (!str_contains($content, 'A quotation, RFQ (Request for Quotation) or order request does not by itself constitute acceptance of an order.')) {
                    $issues[] = "EN Terms missing order acceptance quotation clause";
                }
                if (!str_contains($content, 'Wholesale registration and approval do not by themselves guarantee a fixed price')) {
                    $issues[] = "EN Terms missing wholesale pricing variation clause";
                }
            } elseif ($loc === 'zh') {
                if (!str_contains($content, '报价单、RFQ 询价单或订购申请本身并不构成订单的确认接受。')) {
                    $issues[] = "ZH Terms missing order acceptance quotation clause";
                }
                if (!str_contains($content, '批发账户的注册与审核通过，本身并不保证获得固定特惠价格')) {
                    $issues[] = "ZH Terms missing wholesale pricing variation clause";
                }
                if (!str_contains($content, '贸易账户')) {
                    $issues[] = "ZH Terms missing '贸易账户' terminology";
                }
                if (!str_contains($content, '定制化采购')) {
                    $issues[] = "ZH Terms missing '定制化采购' terminology";
                }
            } elseif ($loc === 'bm') {
                if (!str_contains($content, 'Sebut harga, RFQ (Permohonan Sebut Harga) atau permohonan pesanan secara bersendirian tidak membentuk penerimaan sesuatu pesanan.')) {
                    $issues[] = "BM Terms missing order acceptance quotation clause";
                }
                if (!str_contains($content, 'Pendaftaran dan kelulusan akaun Borong tidak dengan sendirinya menjamin harga tetap')) {
                    $issues[] = "BM Terms missing wholesale pricing variation clause";
                }
                if (!str_contains($content, 'Dagangan')) {
                    $issues[] = "BM Terms missing 'Dagangan' terminology";
                }
                if (!str_contains($content, 'Penyumberan Tersuai')) {
                    $issues[] = "BM Terms missing 'Penyumberan Tersuai' terminology";
                }
            }
        }

        // Specific checks for privacy-policy & cookie-policy
        if ($pSlug === 'privacy-policy' || $pSlug === 'cookie-policy') {
            if (!str_contains($content, 'Leaflet') || !str_contains($content, 'OpenStreetMap')) {
                $issues[] = "{$pSlug} ({$loc}) missing Leaflet / OpenStreetMap technical map disclosure";
            }
        }
    }
    echo "  ✓ Policy {$pSlug}: Verified across EN, ZH, BM.\n";
}

echo "\n=========================================================\n";
if (empty($issues)) {
    echo "🎉 ALL TESTS PASSED! 0 ISSUES FOUND ACROSS SECTIONS #51-#60.\n";
} else {
    echo "❌ AUDIT FAILED WITH " . count($issues) . " ISSUES:\n";
    foreach ($issues as $iss) {
        echo "   - {$iss}\n";
    }
}
echo "=========================================================\n";
