<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Services\TranslationService;

app(TranslationService::class)->clearCache();

$locales = ['en', 'zh', 'bm'];

foreach ($locales as $loc) {
    app(TranslationService::class)->setLocale($loc);
    app()->setLocale($loc);

    $response = app()->handle(Request::create('/' . $loc . '/about', 'GET'));
    $content = $response->getContent();

    echo "\n========================================\n";
    echo "Testing Locale: {$loc} (Status: " . $response->getStatusCode() . ")\n";
    echo "========================================\n";

    // 1. Current vs Future Market Check
    if ($loc === 'en') {
        $marketMatch = str_contains($content, 'Serving customers in Malaysia and Singapore, with plans to expand into regional and international markets.')
                    || str_contains($content, 'Supplying seafood, meat, frozen food and selected food ingredients to customers in Malaysia and Singapore, with plans to expand into regional and international markets.');
        echo "Market check (EN): " . ($marketMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'zh') {
        $marketMatch = str_contains($content, '目前服务马来西亚与新加坡客户，并逐步拓展区域及国际市场。');
        echo "Market check (ZH): " . ($marketMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'bm') {
        $marketMatch = str_contains($content, 'menyokong pelanggan di Malaysia dan Singapura, dengan rancangan untuk mengembangkan pasaran ke peringkat serantau dan antarabangsa')
                    || str_contains($content, 'Menyokong pelanggan di Malaysia dan Singapura, dengan rancangan untuk mengembangkan pasaran ke peringkat serantau dan antarabangsa');
        echo "Market check (BM): " . ($marketMatch ? "PASS" : "FAIL") . "\n";
    }

    // 2. Regional Expansion Title Check
    if ($loc === 'en') {
        $expMatch = str_contains($content, 'From Our Johor Bahru Roots Towards Regional & International Growth');
        echo "Expansion title (EN): " . ($expMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'zh') {
        $expMatch = str_contains($content, '从新山根基走向区域与国际发展');
        echo "Expansion title (ZH): " . ($expMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'bm') {
        $expMatch = str_contains($content, 'Dari Johor Bahru ke Arah Pengembangan Serantau & Antarabangsa');
        echo "Expansion title (BM): " . ($expMatch ? "PASS" : "FAIL") . "\n";
    }

    // 3. Creed / Value Statement Check
    if ($loc === 'en') {
        $creedMatch = str_contains($content, 'Quality Products. Reliable Supply. Fair Value. Consistent Service.');
        echo "Creed / Fair Value (EN): " . ($creedMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'zh') {
        $creedMatch = str_contains($content, '优质产品 · 可靠供应 · 公平价值 · 始终如一的服务');
        echo "Creed / Fair Value (ZH): " . ($creedMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'bm') {
        $creedMatch = str_contains($content, 'Produk Berkualiti · Bekalan Boleh Dipercayai · Nilai Saksama · Perkhidmatan Konsisten');
        echo "Creed / Fair Value (BM): " . ($creedMatch ? "PASS" : "FAIL") . "\n";
    }

    // 4. Management Section & Wendy Bio Check
    if ($loc === 'en') {
        $wendyMatch = str_contains($content, 'Wendy Chiam is responsible for MST’s strategic development, sourcing and supplier relationships, commercial customer relationships and business expansion. She also drives the development of the company’s cold-chain infrastructure and supply capabilities.');
        $mgmtMatch = str_contains($content, 'MANAGEMENT') && str_contains($content, 'Management');
        echo "Wendy Bio (EN): " . ($wendyMatch ? "PASS" : "FAIL") . "\n";
        echo "Management heading (EN): " . ($mgmtMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'zh') {
        $wendyMatch = str_contains($content, 'Wendy Chiam 负责 MST 的战略发展、采购与供应商关系、商业客户关系及业务拓展，并推动公司冷链基础设施与供应能力的发展。');
        $mgmtMatch = str_contains($content, '管理团队');
        echo "Wendy Bio (ZH): " . ($wendyMatch ? "PASS" : "FAIL") . "\n";
        echo "Management heading (ZH): " . ($mgmtMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'bm') {
        $wendyMatch = str_contains($content, 'Wendy Chiam bertanggungjawab terhadap pembangunan strategik MST, perolehan dan hubungan pembekal, hubungan pelanggan komersial serta pengembangan perniagaan. Beliau turut memacu pembangunan infrastruktur rantaian sejuk dan keupayaan bekalan syarikat.');
        $mgmtMatch = str_contains($content, 'PENGURUSAN') || str_contains($content, 'Pengurusan');
        echo "Wendy Bio (BM): " . ($wendyMatch ? "PASS" : "FAIL") . "\n";
        echo "Management heading (BM): " . ($mgmtMatch ? "PASS" : "FAIL") . "\n";
    }

    // 5. HACCP / GMP Principles Check
    if ($loc === 'en') {
        $haccpMatch = str_contains($content, 'HACCP/GMP principles') || str_contains($content, 'HACCP &amp; GMP principles');
        echo "HACCP/GMP principle (EN): " . ($haccpMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'zh') {
        $haccpMatch = str_contains($content, '基于 HACCP 与 GMP 规范原则');
        echo "HACCP/GMP principle (ZH): " . ($haccpMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'bm') {
        $haccpMatch = str_contains($content, 'berasaskan prinsip HACCP/GMP');
        echo "HACCP/GMP principle (BM): " . ($haccpMatch ? "PASS" : "FAIL") . "\n";
    }

    // 6. Cold-Chain & Delivery Coordination Check
    if ($loc === 'en') {
        $coldMatch = str_contains($content, 'frozen storage, temperature-controlled handling, order preparation, packing and applicable logistics coordination')
                  || str_contains($content, 'frozen storage (-18°C to -25°C), temperature-controlled handling, order preparation, packing and applicable logistics coordination');
        echo "Cold-Chain / Delivery Coordination (EN): " . ($coldMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'zh') {
        $coldMatch = str_contains($content, '冷冻储存、温度受控处理、订单准备、包装，以及根据产品和客户需求进行相关物流协调')
                  || str_contains($content, '冷冻储存（-18°C 至 -25°C）、温度受控处理、订单准备、包装，以及根据产品和客户需求进行相关物流协调');
        echo "Cold-Chain / Delivery Coordination (ZH): " . ($coldMatch ? "PASS" : "FAIL") . "\n";
    } elseif ($loc === 'bm') {
        $coldMatch = str_contains($content, 'penyimpanan produk sejuk beku, pengendalian suhu terkawal, penyediaan pesanan, pembungkusan serta penyelarasan logistik')
                  || str_contains($content, 'penyimpanan produk sejuk beku (-18°C hingga -25°C), pengendalian suhu terkawal, penyediaan pesanan, pembungkusan serta penyelarasan logistik');
        echo "Cold-Chain / Delivery Coordination (BM): " . ($coldMatch ? "PASS" : "FAIL") . "\n";
    }

    // 7. Forbidden Claims Check
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

    $foundForbidden = [];
    foreach ($forbiddenWords as $fw) {
        if (stripos($content, $fw) !== false) {
            $foundForbidden[] = $fw;
        }
    }

    if (empty($foundForbidden)) {
        echo "Forbidden claims check: ALL CLEAR (0 forbidden claims found)\n";
    } else {
        echo "Forbidden claims check: FAILED -> Found: " . implode(', ', $foundForbidden) . "\n";
    }
}
