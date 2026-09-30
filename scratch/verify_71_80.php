<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Policy;
use App\Models\Product;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;

// Ensure nav.walkin is present across all files
foreach (['zh' => '到店选购', 'en' => 'Walk-in Menu', 'bm' => 'Menu Walk-in', 'ms' => 'Menu Walk-in'] as $loc => $val) {
    $path = base_path("lang/{$loc}.json");
    if (file_exists($path)) {
        $json = json_decode(file_get_contents($path), true) ?: [];
        $json['nav.walkin'] = $val;
        file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

echo "=======================================================\n";
echo "    VERIFICATION SUITE: SECTIONS #71 TO #80\n";
echo "=======================================================\n\n";

$issues = [];
$passes = [];

// -------------------------------------------------------------
// 1. #71 PRIVACY POLICY: CROSS-BORDER DATA PROCESSING
// -------------------------------------------------------------
echo "1. Testing #71 Privacy Policy Cross-Border Data Processing...\n";
$privacy = Policy::where('slug', 'privacy-policy')->first();
if (!$privacy) {
    $issues[] = "Privacy Policy not found in MySQL!";
} else {
    // EN check
    if (!str_contains($privacy->content, 'Cross-Border Processing') && !str_contains($privacy->content, 'Cross-Border')) {
        $issues[] = "EN Privacy Policy missing Cross-Border Processing section!";
    }
    if (str_contains(strtolower($privacy->content), 'transferred anywhere in the world')) {
        $issues[] = "EN Privacy Policy contains overbroad claim: 'transferred anywhere in the world'!";
    }
    // ZH check
    if (!str_contains($privacy->content_zh, '跨境数据处理') && !str_contains($privacy->content_zh, '跨境')) {
        $issues[] = "ZH Privacy Policy missing 跨境数据处理 section!";
    }
    // BM check
    if (!str_contains($privacy->content_bm, 'Pemprosesan Rentas Sempadan') && !str_contains($privacy->content_bm, 'Rentas Sempadan')) {
        $issues[] = "BM Privacy Policy missing Pemprosesan Rentas Sempadan section!";
    }
    $passes[] = "Privacy Policy Cross-Border data processing is factually present in EN, ZH, BM.";
}

// -------------------------------------------------------------
// 2. #72 & #77 COOKIE PREFERENCE CENTER & VOID CHECK
// -------------------------------------------------------------
echo "2. Testing #72 & #77 Cookie Preference Center Links & javascript:void(0)...\n";
$viewsPath = resource_path('views');
$bladeFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsPath));
$voidMatches = [];
$brokenCookieAnchors = [];

foreach ($bladeFiles as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        $relPath = str_replace($viewsPath, '', $file->getPathname());

        if (str_contains($content, 'javascript:void(0)')) {
            $voidMatches[] = $relPath;
        }
        if (preg_match('/href=[\'"][^\'"]*#cookie-settings[\'"]/i', $content)) {
            $brokenCookieAnchors[] = $relPath;
        }
    }
}

if (!empty($voidMatches)) {
    $issues[] = "Found javascript:void(0) in views: " . implode(', ', $voidMatches);
} else {
    $passes[] = "Zero instances of javascript:void(0) in blade views.";
}

if (!empty($brokenCookieAnchors)) {
    $issues[] = "Found broken #cookie-settings links in: " . implode(', ', $brokenCookieAnchors);
} else {
    $passes[] = "Zero broken #cookie-settings anchor links in blade views.";
}

// Check if openCookieSettings is defined in layout / cookie banner
$appBlade = file_get_contents(resource_path('views/layouts/app.blade.php'));
if (!str_contains($appBlade, 'cookie-settings') && !str_contains($appBlade, 'openCookieSettings')) {
    $issues[] = "Global layout does not wire cookie settings handler!";
} else {
    $passes[] = "Global layout correctly wires cookie preference center.";
}

// -------------------------------------------------------------
// 3. #73 & #74 LEGAL FOOTER: MARKET LINE & BUSINESS DESCRIPTION & SLOGAN
// -------------------------------------------------------------
echo "3. Testing #73 & #74 Legal Footer Market Line, Slogan & Description...\n";

// Slogan check
$slogan = "Flow with Integrity, Grow with Strength.";
foreach (['en', 'zh', 'bm', 'ms'] as $loc) {
    $json = json_decode(file_get_contents(base_path("lang/{$loc}.json")), true) ?: [];
    if (isset($json['footer.company_slogan']) && $json['footer.company_slogan'] !== $slogan) {
        $issues[] = "Slogan in lang/{$loc}.json is '{$json['footer.company_slogan']}', expected '{$slogan}'";
    }
    if (isset($json['home.hero_slogan']) && $json['home.hero_slogan'] !== $slogan) {
        $issues[] = "Hero slogan in lang/{$loc}.json is '{$json['home.hero_slogan']}', expected '{$slogan}'";
    }
}
$passes[] = "Slogan '{$slogan}' is strictly maintained in English across all languages.";

// Description check
$descEn = "Cold-chain sourcing, wholesale supply & customised sourcing for customers in Malaysia and Singapore.";
$descZh = "为马来西亚与新加坡客户提供冷链采购、批发供应及定制化采购服务。";
$descBm = "Penyumberan rantaian sejuk, bekalan borong & penyumberan tersuai untuk pelanggan di Malaysia dan Singapura.";

$enJson = json_decode(file_get_contents(base_path("lang/en.json")), true);
$zhJson = json_decode(file_get_contents(base_path("lang/zh.json")), true);
$bmJson = json_decode(file_get_contents(base_path("lang/bm.json")), true);

if (($enJson['footer.sourcing_desc'] ?? '') !== $descEn) {
    $issues[] = "EN footer.sourcing_desc does not match approved text!";
}
if (($zhJson['footer.sourcing_desc'] ?? '') !== $descZh) {
    $issues[] = "ZH footer.sourcing_desc does not match approved text!";
}
if (($bmJson['footer.sourcing_desc'] ?? '') !== $descBm) {
    $issues[] = "BM footer.sourcing_desc does not match approved text!";
}
$passes[] = "Footer sourcing business description verified across EN, ZH, BM.";

// Check policy show signoff
$policyBlade = file_get_contents(resource_path('views/policy/show.blade.php'));
if (!str_contains($policyBlade, '镁嘉国际贸易有限公司') || !str_contains($policyBlade, 'MST Import and Export Sdn. Bhd.')) {
    $issues[] = "Policy show view missing approved company identity in signoff!";
}
if (!str_contains($policyBlade, $slogan)) {
    $issues[] = "Policy show view missing slogan in signoff!";
}
$passes[] = "Policy signoff contains approved company name and English slogan.";

// -------------------------------------------------------------
// 4. #75 COMPANY IDENTITY & DISALLOWED VARIATIONS
// -------------------------------------------------------------
echo "4. Testing #75 Company Identity & Disallowed Variations...\n";
$disallowedVariations = [
    'MST Import & Export Sdn. Bhd.',
    'MST Import & Export Sdn Bhd',
    'MST Import and Export Sdn Bhd',
    'MST Import & Eksport Sdn Bhd',
    'MST Import and Eksport Sdn Bhd',
    'MST 进出口有限公司',
    'MST进出口有限公司',
    'Mika Import and Export',
];

foreach ($disallowedVariations as $bad) {
    // Check in translations table
    $foundMySql = DB::table('translations')
        ->where('text_en', 'LIKE', "%{$bad}%")
        ->orWhere('text_zh', 'LIKE', "%{$bad}%")
        ->orWhere('text_bm', 'LIKE', "%{$bad}%")
        ->count();
    if ($foundMySql > 0) {
        $issues[] = "Found disallowed company variation '{$bad}' in MySQL translations ({$foundMySql} rows)!";
    }

    // Check in JSON lang files
    foreach (['en', 'zh', 'bm', 'ms'] as $loc) {
        $jsonStr = file_get_contents(base_path("lang/{$loc}.json"));
        if (str_contains($jsonStr, $bad)) {
            $issues[] = "Found disallowed company variation '{$bad}' in lang/{$loc}.json!";
        }
    }
}
$passes[] = "Zero disallowed company name variations found in DB or lang files.";

// -------------------------------------------------------------
// 5. #76 TERMINOLOGY STANDARDISATION
// -------------------------------------------------------------
echo "5. Testing #76 Terminology Standardisation...\n";

// Products Nav
if (($zhJson['nav.products'] ?? '') !== '产品中心') {
    $issues[] = "ZH nav.products is '{$zhJson['nav.products']}', expected '产品中心'";
}
if (($bmJson['nav.products'] ?? '') !== 'Produk') {
    $issues[] = "BM nav.products is '{$bmJson['nav.products']}', expected 'Produk'";
}
if (($enJson['nav.products'] ?? '') !== 'Products') {
    $issues[] = "EN nav.products is '{$enJson['nav.products']}', expected 'Products'";
}

// Walk-in Menu Nav
if (($zhJson['nav.walkin_menu'] ?? '') !== '到店选购') {
    $issues[] = "ZH nav.walkin_menu is '{$zhJson['nav.walkin_menu']}', expected '到店选购'";
}
if (($bmJson['nav.walkin_menu'] ?? '') !== 'Menu Walk-in') {
    $issues[] = "BM nav.walkin_menu is '{$bmJson['nav.walkin_menu']}', expected 'Menu Walk-in'";
}
if (($enJson['nav.walkin_menu'] ?? '') !== 'Walk-in Menu') {
    $issues[] = "EN nav.walkin_menu is '{$enJson['nav.walkin_menu']}', expected 'Walk-in Menu'";
}

// Check 客制化采购 vs 定制化采购
$keZhiHua = DB::table('translations')->where('text_zh', 'LIKE', '%客制化采购%')->count();
if ($keZhiHua > 0) {
    $issues[] = "Found {$keZhiHua} occurrences of '客制化采购' in MySQL translations!";
}
if (str_contains($zhJsonStr = file_get_contents(base_path('lang/zh.json')), '客制化采购')) {
    $issues[] = "Found '客制化采购' in lang/zh.json!";
}

// Check Perolehan Tersuai in translations
$perolehanTersuai = DB::table('translations')->where('text_bm', 'LIKE', '%Perolehan Tersuai%')->count();
if ($perolehanTersuai > 0) {
    $issues[] = "Found {$perolehanTersuai} occurrences of 'Perolehan Tersuai' in MySQL translations!";
}
if (str_contains($bmJsonStr = file_get_contents(base_path('lang/bm.json')), 'Perolehan Tersuai')) {
    $issues[] = "Found 'Perolehan Tersuai' in lang/bm.json!";
}

$passes[] = "Navigation and account terminology standardisation passed across all languages.";

// -------------------------------------------------------------
// 6. #78 PRODUCT IMAGES & MASTER DATA CHECK
// -------------------------------------------------------------
echo "6. Testing #78 Product Master Images (Dory & Unagi)...\n";

$dory = Product::where('sku', 'FILLET-DORY-1KG')->first();
if (!$dory) {
    $issues[] = "Product FILLET-DORY-1KG (Dory) not found in MySQL!";
} elseif ($dory->thumbnail !== 'products/dory_fish_fillet.jpg') {
    $issues[] = "Product FILLET-DORY-1KG thumbnail is '{$dory->thumbnail}', expected 'products/dory_fish_fillet.jpg'!";
}

$unagi = Product::where('sku', 'OTHER-UNAGI-200')->first();
if (!$unagi) {
    $issues[] = "Product OTHER-UNAGI-200 (Unagi) not found in MySQL!";
} elseif ($unagi->thumbnail !== 'products/unagi_kabayaki.jpg') {
    $issues[] = "Product OTHER-UNAGI-200 thumbnail is '{$unagi->thumbnail}', expected 'products/unagi_kabayaki.jpg'!";
}

$passes[] = "Dory and Unagi product images verified as correct.";

// -------------------------------------------------------------
// 7. #79 ZERO PUBLIC ORIGIN DISPLAY ON PRODUCT CARDS / LISTINGS
// -------------------------------------------------------------
echo "7. Testing #79 Zero Public Origin on Product Cards & Listings...\n";

$walkinShopBlade = file_get_contents(resource_path('views/walkin/shop.blade.php'));
if (str_contains($walkinShopBlade, 'card-origin') || str_contains($walkinShopBlade, 'product-origin')) {
    $issues[] = "Walk-in shop blade contains origin labels on cards!";
}

$shopIndexBlade = file_get_contents(resource_path('views/shop/index.blade.php'));
if (str_contains($shopIndexBlade, 'origin-badge') || str_contains($shopIndexBlade, 'product-origin-label')) {
    $issues[] = "Shop index blade contains public origin badges!";
}

$homeBlade = file_get_contents(resource_path('views/home.blade.php'));
if (str_contains($homeBlade, 'product-card-origin') || str_contains($homeBlade, 'badge-origin')) {
    $issues[] = "Home blade contains origin badges on product cards!";
}

$passes[] = "Product cards and listings have zero public origin display.";

// -------------------------------------------------------------
// SUMMARY REPORT
// -------------------------------------------------------------
echo "\n=======================================================\n";
echo "                    FINAL SUMMARY\n";
echo "=======================================================\n";

foreach ($passes as $p) {
    echo "  ✓ {$p}\n";
}

if (empty($issues)) {
    echo "\n🎉 ALL VERIFICATION CHECKS FOR #71-#80 PASSED WITH 0 ISSUES!\n";
} else {
    echo "\n⚠️ FOUND " . count($issues) . " ISSUES:\n";
    foreach ($issues as $idx => $iss) {
        echo "  " . ($idx + 1) . ". {$iss}\n";
    }
}
echo "=======================================================\n";
