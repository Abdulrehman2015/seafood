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
echo "=== COMPREHENSIVE AUDIT & VERIFICATION: ITEMS #41-#50 ===\n";
echo "=========================================================\n\n";

$issues = [];

// ─── 1. DATABASE & JSON INTEGRITY CHECKS ─────────────────────────────────────
echo "1. Checking Database and JSON Translations...\n";

// Check 客制化 in MySQL
$zhOldCust = DB::table('translations')->where('text_zh', 'LIKE', '%客制化%')->get();
if ($zhOldCust->count() > 0) {
    foreach ($zhOldCust as $r) {
        $issues[] = "MySQL translation [{$r->group}.{$r->key}] contains '客制化': '{$r->text_zh}'";
    }
} else {
    echo "  ✓ MySQL translations: 0 instances of '客制化' found.\n";
}

// Check 客制化 in SQLite
$sqlitePath = database_path('database.sqlite');
if (file_exists($sqlitePath)) {
    $pdo = new PDO("sqlite:{$sqlitePath}");
    $res = $pdo->query("SELECT `group`, `key`, text_zh FROM translations WHERE text_zh LIKE '%客制化%'");
    $rows = $res->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) > 0) {
        foreach ($rows as $r) {
            $issues[] = "SQLite translation [{$r['group']}.{$r['key']}] contains '客制化': '{$r['text_zh']}'";
        }
    } else {
        echo "  ✓ SQLite translations: 0 instances of '客制化' found.\n";
    }
}

// Check 客制化 in JSON files
foreach (['zh' => base_path('lang/zh.json')] as $loc => $path) {
    if (file_exists($path)) {
        $json = json_decode(file_get_contents($path), true);
        $foundInJson = 0;
        foreach ($json as $k => $v) {
            if (is_string($v) && str_contains($v, '客制化')) {
                $issues[] = "zh.json key [{$k}] contains '客制化': '{$v}'";
                $foundInJson++;
            }
        }
        if ($foundInJson === 0) {
            echo "  ✓ zh.json: 0 instances of '客制化' found.\n";
        }
    }
}

// ─── 2. RENDERED ABOUT US PAGE CHECKS ACROSS EN / ZH / BM ─────────────────────
echo "\n2. Testing Rendered About Us Pages Across Locales...\n";

$locales = ['en', 'zh', 'bm'];

foreach ($locales as $loc) {
    $uri = "/{$loc}/about";
    $request = Request::create($uri, 'GET');
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    $content = $response->getContent();

    if (str_starts_with($content, "\x1f\x8b")) {
        $content = gzdecode($content);
    }

    echo "\n  Testing {$uri} (HTTP {$status}):\n";

    if ($status !== 200) {
        $issues[] = "Route {$uri} returned HTTP {$status}";
        continue;
    }

    // ── Item 43: Current vs Future Market Positioning ──
    if ($loc === 'en') {
        if (!str_contains($content, 'Serving customers in Malaysia and Singapore, with plans to expand into regional and international markets.')
            && !str_contains($content, 'Supplying seafood, meat, frozen food and selected food ingredients to customers in Malaysia and Singapore, with plans to expand into regional and international markets.')) {
            $issues[] = "EN About Us missing approved current market copy (Malaysia & Singapore / future expansion)";
        } else {
            echo "    ✓ Item 43 (EN): Market positioning verified.\n";
        }
    } elseif ($loc === 'zh') {
        if (!str_contains($content, '目前服务马来西亚与新加坡客户，并逐步拓展区域及国际市场。')) {
            $issues[] = "ZH About Us missing '目前服务马来西亚与新加坡客户，并逐步拓展区域及国际市场。'";
        } else {
            echo "    ✓ Item 43 (ZH): Market positioning verified.\n";
        }
    } elseif ($loc === 'bm') {
        if (!str_contains($content, 'menyokong pelanggan di Malaysia dan Singapura, dengan rancangan untuk mengembangkan pasaran ke peringkat serantau dan antarabangsa')
            && !str_contains($content, 'Menyokong pelanggan di Malaysia dan Singapura, dengan rancangan untuk mengembangkan pasaran ke peringkat serantau dan antarabangsa')) {
            $issues[] = "BM About Us missing approved current market copy (Malaysia & Singapura / pengembangan serantau)";
        } else {
            echo "    ✓ Item 43 (BM): Market positioning verified.\n";
        }
    }

    // ── Item 44: Regional Expansion Wording ──
    if ($loc === 'en') {
        if (!str_contains($content, 'From Our Johor Bahru Roots Towards Regional & International Growth')) {
            $issues[] = "EN About Us missing 'From Our Johor Bahru Roots Towards Regional & International Growth'";
        } else {
            echo "    ✓ Item 44 (EN): Regional expansion heading verified.\n";
        }
    } elseif ($loc === 'zh') {
        if (!str_contains($content, '从新山根基走向区域与国际发展')) {
            $issues[] = "ZH About Us missing '从新山根基走向区域与国际发展'";
        } else {
            echo "    ✓ Item 44 (ZH): Regional expansion heading verified.\n";
        }
    } elseif ($loc === 'bm') {
        if (!str_contains($content, 'Dari Johor Bahru ke Arah Pengembangan Serantau & Antarabangsa')) {
            $issues[] = "BM About Us missing 'Dari Johor Bahru ke Arah Pengembangan Serantau & Antarabangsa'";
        } else {
            echo "    ✓ Item 44 (BM): Regional expansion heading verified.\n";
        }
    }

    // ── Item 45: Quality / Value Statement (Fair Value) ──
    if ($loc === 'en') {
        if (!str_contains($content, 'Quality Products. Reliable Supply. Fair Value. Consistent Service.')) {
            $issues[] = "EN About Us missing 'Quality Products. Reliable Supply. Fair Value. Consistent Service.'";
        } else {
            echo "    ✓ Item 45 (EN): Fair Value creed verified.\n";
        }
    } elseif ($loc === 'zh') {
        if (!str_contains($content, '优质产品 · 可靠供应 · 公平价值 · 始终如一的服务')) {
            $issues[] = "ZH About Us missing '优质产品 · 可靠供应 · 公平价值 · 始终如一的服务'";
        } else {
            echo "    ✓ Item 45 (ZH): Fair Value creed verified.\n";
        }
    } elseif ($loc === 'bm') {
        if (!str_contains($content, 'Produk Berkualiti · Bekalan Boleh Dipercayai · Nilai Saksama · Perkhidmatan Konsisten')) {
            $issues[] = "BM About Us missing 'Produk Berkualiti · Bekalan Boleh Dipercayai · Nilai Saksama · Perkhidmatan Konsisten'";
        } else {
            echo "    ✓ Item 45 (BM): Fair Value creed verified.\n";
        }
    }

    // ── Item 46 & 50: Wendy Profile & Management Heading ──
    if ($loc === 'en') {
        if (!str_contains($content, 'Wendy Chiam is responsible for MST’s strategic development, sourcing and supplier relationships, commercial customer relationships and business expansion. She also drives the development of the company’s cold-chain infrastructure and supply capabilities.')) {
            $issues[] = "EN About Us missing approved Wendy Chiam profile text";
        } else {
            echo "    ✓ Item 46 (EN): Wendy profile verified.\n";
        }
        if (!str_contains($content, 'MANAGEMENT') || !str_contains($content, 'Management')) {
            $issues[] = "EN About Us missing 'Management' heading";
        } else {
            echo "    ✓ Item 46 (EN): Management heading verified.\n";
        }
    } elseif ($loc === 'zh') {
        if (!str_contains($content, 'Wendy Chiam 负责 MST 的战略发展、采购与供应商关系、商业客户关系及业务拓展，并推动公司冷链基础设施与供应能力的发展。')) {
            $issues[] = "ZH About Us missing approved Wendy Chiam profile text";
        } else {
            echo "    ✓ Item 46 (ZH): Wendy profile verified.\n";
        }
        if (!str_contains($content, '管理团队')) {
            $issues[] = "ZH About Us missing '管理团队' heading";
        } else {
            echo "    ✓ Item 46 (ZH): Management heading verified.\n";
        }
    } elseif ($loc === 'bm') {
        if (!str_contains($content, 'Wendy Chiam bertanggungjawab terhadap pembangunan strategik MST, perolehan dan hubungan pembekal, hubungan pelanggan komersial serta pengembangan perniagaan. Beliau turut memacu pembangunan infrastruktur rantaian sejuk dan keupayaan bekalan syarikat.')) {
            $issues[] = "BM About Us missing approved Wendy Chiam profile text";
        } else {
            echo "    ✓ Item 46 (BM): Wendy profile verified.\n";
        }
        if (!str_contains($content, 'PENGURUSAN') && !str_contains($content, 'Pengurusan')) {
            $issues[] = "BM About Us missing 'Pengurusan' heading";
        } else {
            echo "    ✓ Item 46 (BM): Management heading verified.\n";
        }
    }

    // ── Item 47 & 50: HACCP / GMP Principle-Based Wording ──
    if ($loc === 'en') {
        if (!str_contains($content, 'based on HACCP/GMP principles') && !str_contains($content, 'HACCP/GMP principles')) {
            $issues[] = "EN About Us missing 'based on HACCP/GMP principles'";
        } else {
            echo "    ✓ Item 47 (EN): HACCP/GMP principles verified.\n";
        }
    } elseif ($loc === 'zh') {
        if (!str_contains($content, '基于 HACCP 与 GMP 规范原则')) {
            $issues[] = "ZH About Us missing '基于 HACCP 与 GMP 规范原则'";
        } else {
            echo "    ✓ Item 47 (ZH): HACCP/GMP principles verified.\n";
        }
    } elseif ($loc === 'bm') {
        if (!str_contains($content, 'berasaskan prinsip HACCP/GMP')) {
            $issues[] = "BM About Us missing 'berasaskan prinsip HACCP/GMP'";
        } else {
            echo "    ✓ Item 47 (BM): HACCP/GMP principles verified.\n";
        }
    }

    // ── Item 48 & 50: Cold-Chain Operations Claims ──
    if ($loc === 'en') {
        if (!str_contains($content, 'frozen storage (-18°C to -25°C), temperature-controlled handling, order preparation, packing and applicable logistics coordination')
            && !str_contains($content, 'frozen storage, temperature-controlled handling, order preparation, packing and applicable logistics coordination')) {
            $issues[] = "EN About Us missing approved cold-chain operations wording";
        } else {
            echo "    ✓ Item 48 (EN): Cold-chain operations verified.\n";
        }
    } elseif ($loc === 'zh') {
        if (!str_contains($content, '冷冻储存（-18°C 至 -25°C）、温度受控处理、订单准备、包装，以及根据产品和客户需求进行相关物流协调')
            && !str_contains($content, '冷冻储存、温度受控处理、订单准备、包装，以及根据产品和客户需求进行相关物流协调')) {
            $issues[] = "ZH About Us missing approved cold-chain operations wording";
        } else {
            echo "    ✓ Item 48 (ZH): Cold-chain operations verified.\n";
        }
    } elseif ($loc === 'bm') {
        if (!str_contains($content, 'penyimpanan produk sejuk beku (-18°C hingga -25°C), pengendalian suhu terkawal, penyediaan pesanan, pembungkusan serta penyelarasan logistik')
            && !str_contains($content, 'penyimpanan produk sejuk beku, pengendalian suhu terkawal, penyediaan pesanan, pembungkusan serta penyelarasan logistik')) {
            $issues[] = "BM About Us missing approved cold-chain operations wording";
        } else {
            echo "    ✓ Item 48 (BM): Cold-chain operations verified.\n";
        }
    }

    // ── Item 49: Delivery Coordination Wording ──
    if ($loc === 'en') {
        if (!str_contains($content, 'receiving, storage, packing, order preparation and delivery coordination according to customer requirements.')) {
            $issues[] = "EN About Us missing 'receiving, storage, packing, order preparation and delivery coordination according to customer requirements.'";
        } else {
            echo "    ✓ Item 49 (EN): Delivery coordination verified.\n";
        }
    } elseif ($loc === 'zh') {
        if (!str_contains($content, '接收、储存、包装、订单准备及根据客户需求进行配送协调。')
            && !str_contains($content, '我们的运营涵盖接收、储存、包装、订单准备及根据客户需求进行配送协调。')) {
            $issues[] = "ZH About Us missing '接收、储存、包装、订单准备及根据客户需求进行配送协调。'";
        } else {
            echo "    ✓ Item 49 (ZH): Delivery coordination verified.\n";
        }
    } elseif ($loc === 'bm') {
        if (!str_contains($content, 'penerimaan, penyimpanan, pembungkusan, penyediaan pesanan serta penyelarasan penghantaran mengikut keperluan pelanggan.')
            && !str_contains($content, 'Operasi kami merangkumi penerimaan, penyimpanan, pembungkusan, penyediaan pesanan serta penyelarasan penghantaran mengikut keperluan pelanggan.')) {
            $issues[] = "BM About Us missing 'penerimaan, penyimpanan, pembungkusan, penyediaan pesanan serta penyelarasan penghantaran mengikut keperluan pelanggan.'";
        } else {
            echo "    ✓ Item 49 (BM): Delivery coordination verified.\n";
        }
    }

    // ── Global Forbidden Claims Check ──
    $forbiddenWords = [
        'HACCP Certified',
        'GMP Certified',
        'Competitive Pricing',
        '竞争性价格',
        'Executive Leadership',
        'Kepimpinan Eksekutif',
        '执行领导层',
        '客制化采购',
        '客制化',
        'unbroken cold chain',
        'Unbroken Cold Chain',
        '100% Temperature Control',
        'Guaranteed Cold Chain',
        'global supply network',
        'worldwide delivery',
        '全程不间断冷链',
        '全供应链温度控制',
    ];

    foreach ($forbiddenWords as $fw) {
        if (stripos($content, $fw) !== false) {
            $issues[] = "Page {$uri} contains forbidden claim: '{$fw}'";
        }
    }
}

echo "\n=========================================================\n";
if (empty($issues)) {
    echo "🎉 ALL TESTS PASSED! 0 ISSUES FOUND ACROSS SECTIONS #41-#50.\n";
} else {
    echo "❌ AUDIT FAILED WITH " . count($issues) . " ISSUES:\n";
    foreach ($issues as $iss) {
        echo "   - {$iss}\n";
    }
}
echo "=========================================================\n";
