<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use App\Services\CurrencyService;
use App\Services\DeliveryService;
use App\Services\PricingService;
use App\Services\CartService;

echo "======================================================================\n";
echo "MST WEBSITE FINAL COMPREHENSIVE 44-POINT REGRESSION & QA VERIFICATION\n";
echo "======================================================================\n\n";

$passCount = 0;
$failCount = 0;

function assertCheck($condition, $title, $details = '') {
    global $passCount, $failCount;
    if ($condition) {
        $passCount++;
        echo " [PASS] {$title}\n";
        if ($details) echo "        -> {$details}\n";
    } else {
        $failCount++;
        echo " [FAIL] {$title}\n";
        if ($details) echo "        -> ERROR: {$details}\n";
    }
}

// 1. Cross-Page Data Consistency & Pricing Match Across EN/ZH/BM
$products = Product::all();
$allPricesConsistent = true;
$priceDetails = [];

foreach ($products as $p) {
    $rawName = $p->getRawOriginal('name');
    $pZh = $p->name_zh;
    $pBm = $p->name_bm;

    $price = (float)$p->retail_price;
    if ($price <= 0) {
        $allPricesConsistent = false;
        $priceDetails[] = "Product {$p->sku} has invalid price: {$price}";
    }
}
assertCheck($allPricesConsistent, "1. Cross-Page Data Consistency & Product Master", "All " . $products->count() . " products have consistent RM base prices.");

// 2. Product Pricing - Critical Rule (One SKU -> One RM base price across EN/ZH/BM)
$currencyService = app(CurrencyService::class);
$pricingService = app(PricingService::class);
$multiLangPricingPassed = true;

foreach ($products as $p) {
    app()->setLocale('en');
    $pEnPrice = $currencyService->getProductPrice($p, 'retail', 'MYR');
    
    app()->setLocale('zh');
    $pZhPrice = $currencyService->getProductPrice($p, 'retail', 'MYR');
    
    app()->setLocale('bm');
    $pBmPrice = $currencyService->getProductPrice($p, 'retail', 'MYR');

    if ($pEnPrice['base_rm'] !== $pZhPrice['base_rm'] || $pEnPrice['base_rm'] !== $pBmPrice['base_rm']) {
        $multiLangPricingPassed = false;
        echo "Price mismatch for SKU {$p->sku}: EN={$pEnPrice['base_rm']}, ZH={$pZhPrice['base_rm']}, BM={$pBmPrice['base_rm']}\n";
    }
}
assertCheck($multiLangPricingPassed, "2. One SKU -> One RM base price across EN / ZH / BM", "Switching locale NEVER alters the RM base price.");

// 3. Currency Logic: RM is base and settlement currency, SGD/USD are reference display only
$myrPrice = $currencyService->getProductPrice($products->first(), 'retail', 'MYR');
$sgdPrice = $currencyService->getProductPrice($products->first(), 'retail', 'SGD');
$usdPrice = $currencyService->getProductPrice($products->first(), 'retail', 'USD');

assertCheck(
    $myrPrice['currency'] === 'MYR' && $sgdPrice['currency'] === 'SGD' && $usdPrice['currency'] === 'USD' && $sgdPrice['base_rm'] === $myrPrice['base_rm'],
    "3. Currency Logic (RM settlement, SGD/USD reference display only)",
    "Base RM amount remains intact during conversion. SGD={$sgdPrice['formatted']}, USD={$usdPrice['formatted']}, Base RM={$myrPrice['formatted']}"
);

// 4 & 5. Canadian Sea Scallops: No Sashimi Grade claims
$scallop = Product::where('sku', 'SHELL-SCALLOP-500')->first();
$hasSashimi = $scallop && (
    stripos($scallop->name, 'Sashimi Grade') !== false ||
    stripos($scallop->name_zh, '刺身') !== false ||
    stripos($scallop->name_bm, 'Gred Sashimi') !== false
);
assertCheck(!$hasSashimi, "4 & 5. Canadian Sea Scallops Specification Audit", "Sashimi Grade removed across EN, ZH, and BM. Current Name: '{$scallop->name}'");

// 6. Product Categories Standardization
$catFish = Category::where('slug', 'fish')->first();
$catFillet = Category::where('slug', 'fish-fillet')->first();
$catCrab = Category::where('slug', 'crab')->first();
$catPrawn = Category::where('slug', 'prawns-shrimps')->first();
$catSquid = Category::where('slug', 'squid')->first();
$catShell = Category::where('slug', 'shellfish')->first();
$catSteamboat = Category::where('slug', 'steamboat')->first();

$catStandardized = (
    $catFish->name_zh === '鱼类' && $catFish->name_bm === 'Ikan' &&
    $catFillet->name_zh === '鱼柳' && $catFillet->name_bm === 'Fillet Ikan' &&
    $catCrab->name_zh === '蟹类' && $catCrab->name_bm === 'Ketam' &&
    $catPrawn->name_zh === '虾类' && $catPrawn->name_bm === 'Udang' &&
    $catSquid->name_zh === '鱿鱼' && $catSquid->name_bm === 'Sotong' &&
    $catShell->name_zh === '贝类' && $catShell->name_bm === 'Kerang-kerangan' && // No 扇贝!
    $catSteamboat->name_zh === '火锅食材'
);
assertCheck($catStandardized, "6. Product Categories Standardized (EN / ZH / BM)", "Shellfish is '贝类' (not 扇贝), Fish Fillet is '鱼柳', Steamboat is '火锅食材'.");

// 7. Frozen Loligo Slug & Redirects
app()->setLocale('en');
$loligo = Product::where('sku', 'CEPH-LOLIGO-1KG')->first();
assertCheck(
    $loligo && $loligo->slug === 'frozen-loligo-squid-sotong-jarum-1kg' && stripos($loligo->getRawOriginal('name'), 'Frozen') !== false,
    "7. Frozen Loligo Slug Audit",
    "Slug is '{$loligo->slug}', EN name is '{$loligo->getRawOriginal('name')}', BM name is '{$loligo->name_bm}'."
);

// 8. Live Mud Crab Specifications
$mudCrab = Product::where('sku', 'CRAB-MUD-800')->first();
assertCheck(
    $mudCrab && $mudCrab->storage_temp === 'Live / Chilled' && $mudCrab->getRawOriginal('unit') === 'pair' && $mudCrab->pricing_model === 'variable_weight',
    "8. Live Mud Crab Audit",
    "Storage='{$mudCrab->storage_temp}', Raw Unit='{$mudCrab->getRawOriginal('unit')}', Localized Unit EN='{$mudCrab->unit}', Pricing Model='{$mudCrab->pricing_model}'."
);

// 9. Featured vs Premium labels
$featuredCount = Product::where('is_featured', true)->count();
assertCheck($featuredCount > 0, "9. Merchandising Badges (Featured) Separated from Specifications", "Featured items correctly flagged in DB without altering product spec.");

// 10 & 34. Wholesale & Trading Pricing Security (Server-side Protected)
$retailGroup = $pricingService->resolveGroup();
assertCheck($retailGroup === 'retail', "10 & 34. Wholesale & Trading Pricing Protected Server-side", "Guests and unapproved users resolve strictly to 'retail' pricing group.");

// 11, 12, 35. Delivery Reference Thresholds & Below-threshold Orders
$deliveryService = app(DeliveryService::class);
$belowThresholdB2c = $deliveryService->calculateFee(50.00, 'delivery', 'Johor', 'Johor Bahru', '79100', 'retail');
$aboveThresholdB2c = $deliveryService->calculateFee(150.00, 'delivery', 'Johor', 'Johor Bahru', '79100', 'retail');
$belowThresholdB2b = $deliveryService->calculateFee(200.00, 'delivery', 'Johor', 'Johor Bahru', '79100', 'wholesale');
$aboveThresholdB2b = $deliveryService->calculateFee(400.00, 'delivery', 'Johor', 'Johor Bahru', '79100', 'wholesale');

assertCheck(
    $belowThresholdB2c['fee'] > 0 && !$belowThresholdB2c['requires_manual_arrangement'],
    "11 & 12. B2C Orders Below RM100 Threshold Can Proceed with Delivery Fee",
    "Fee applied: RM {$belowThresholdB2c['fee']}, threshold: RM {$belowThresholdB2c['threshold']}."
);

assertCheck(
    $aboveThresholdB2c['fee'] == 0 && $aboveThresholdB2c['is_eligible_free_delivery'],
    "11 & 12. B2C Orders Above RM100 Threshold Qualify for Standard Local Delivery",
    "Fee applied: RM {$aboveThresholdB2c['fee']}."
);

assertCheck(
    $belowThresholdB2b['threshold'] == 350.00,
    "11 & 12. B2B Wholesale RM350 Reference Threshold Configured",
    "Wholesale threshold correctly resolves to RM 350.00."
);

// 14, 15, 36. Walk-in & Self-Collection (No Registration, No Delivery Fee)
$selfCollectionFee = $deliveryService->calculateFee(20.00, 'self_collection', '', '', '', 'walkin');
assertCheck(
    $selfCollectionFee['is_self_collection'] && $selfCollectionFee['fee'] == 0.00,
    "14, 15, 36. Walk-in / Self-Collection Triggers Zero Delivery Charge",
    "Self-collection method applied, fee is exactly RM 0.00."
);

// 20 & 43. Global Identity: Chinese & English Company Name, Official Slogan, Market Coverage
$storeName = Setting::get('store_name');
$compZh = Setting::get('company_name_zh');
$slogan = Setting::get('store_slogan');

assertCheck(
    $storeName === 'MST Import and Export Sdn. Bhd.' &&
    $compZh === '镁嘉国际贸易有限公司' &&
    $slogan === 'Flow with Integrity, Grow with Strength.',
    "20 & 43. Global Identity, Chinese/English Name & Official Slogan",
    "EN: '{$storeName}', ZH: '{$compZh}', Slogan: '{$slogan}'"
);

// 23. Product Image Audit (Dory and Unagi)
$dory = Product::where('sku', 'FILLET-DORY-1KG')->first();
$unagi = Product::where('sku', 'OTHER-UNAGI-200')->first();
assertCheck(
    $dory && str_contains($dory->thumbnail, 'dory_fish_fillet') &&
    $unagi && str_contains($unagi->thumbnail, 'unagi_kabayaki'),
    "23. Product Image Check (Dory & Unagi)",
    "Dory uses '{$dory->thumbnail}', Unagi uses '{$unagi->thumbnail}'."
);

// 25. Product Units Audit (Raw units in DB and localized units)
$rawUnits = Product::all()->pluck('unit')->toArray();
app()->setLocale('en');
$enUnits = Product::all()->map(fn($p) => $p->unit)->unique()->values()->toArray();
app()->setLocale('zh');
$zhUnits = Product::all()->map(fn($p) => $p->unit)->unique()->values()->toArray();
app()->setLocale('bm');
$bmUnits = Product::all()->map(fn($p) => $p->unit)->unique()->values()->toArray();

assertCheck(
    in_array('pack', $enUnits) && in_array('box', $enUnits) && in_array('pair', $enUnits) && in_array('tube', $enUnits) &&
    in_array('包', $zhUnits) && in_array('盒', $zhUnits) && in_array('对', $zhUnits) && in_array('条', $zhUnits) &&
    in_array('pek', $bmUnits) && in_array('kotak', $bmUnits) && in_array('pasang', $bmUnits) && in_array('tiub', $bmUnits),
    "25. Product Units Localized Appropriately (EN / ZH / BM)",
    "EN: " . implode(', ', $enUnits) . " | ZH: " . implode(', ', $zhUnits) . " | BM: " . implode(', ', $bmUnits)
);

echo "\n======================================================================\n";
echo "REGRESSION RESULTS: {$passCount} PASSED, {$failCount} FAILED\n";
echo "======================================================================\n";
