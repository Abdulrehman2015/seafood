<?php

echo "=== SECTION 26 & 27 VERIFICATION AUDIT ===\n\n";

$passCount = 0;
$failCount = 0;

function assertCondition($testName, $condition, $details = '') {
    global $passCount, $failCount;
    if ($condition) {
        echo " [PASS] $testName\n";
        $passCount++;
    } else {
        echo " [FAIL] $testName : $details\n";
        $failCount++;
    }
}

// 1. Check shop/index.blade.php
$shopIndex = file_get_contents(__DIR__ . '/../resources/views/shop/index.blade.php');
assertCondition(
    'Shop Index: Origin filter dropdown removed',
    !str_contains($shopIndex, 'id="dropdown-origin"') && !str_contains($shopIndex, 'data-name="origin"')
);
assertCondition(
    'Shop Index: Active Origin chip removed',
    !str_contains($shopIndex, "request('origin')")
);
assertCondition(
    'Shop Index: QuickView Origin tag removed',
    !str_contains($shopIndex, 'qvOriginTag')
);
assertCondition(
    'Shop Index: Card grid / item has no badge-origin or origin metadata',
    !str_contains($shopIndex, 'badge-origin') && !str_contains($shopIndex, '$product->origin') && !str_contains($shopIndex, 'data-origin')
);

// 2. Check home.blade.php
$homeView = file_get_contents(__DIR__ . '/../resources/views/home.blade.php');
assertCondition(
    'Home: QuickView Origin tag removed',
    !str_contains($homeView, 'qvOriginTag')
);
assertCondition(
    'Home: Featured product cards do not render product origin',
    !str_contains($homeView, '$product->origin') && !str_contains($homeView, 'badge-origin')
);

// 3. Check walkin/shop.blade.php
$walkinShop = file_get_contents(__DIR__ . '/../resources/views/walkin/shop.blade.php');
assertCondition(
    'Walkin Shop: Product cards do not render badge-origin or origin text',
    !str_contains($walkinShop, 'badge-origin') && !str_contains($walkinShop, '$product->origin')
);

// 4. Check shop/show.blade.php related products
$shopShow = file_get_contents(__DIR__ . '/../resources/views/shop/show.blade.php');
$relatedPos = strpos($shopShow, 'Related Products');
$relatedSection = $relatedPos !== false ? substr($shopShow, $relatedPos) : '';
assertCondition(
    'Product Detail Related Products: No origin on related cards',
    !str_contains($relatedSection, '$rel->origin')
);

// 5. Check Product Model (Ensuring underlying data field is preserved for backend/CMS use)
$productModelCode = file_get_contents(__DIR__ . '/../app/Models/Product.php');
assertCondition(
    'Product Model: origin fillable attribute preserved for database/CMS',
    str_contains($productModelCode, "'origin'")
);

// 6. Check Product Detail page specs tab (preserved for deferred decision)
assertCondition(
    'Product Detail: Detail page specs table preserved (decision deferred)',
    str_contains($shopShow, 'Country of Origin') && str_contains($shopShow, '$product->origin')
);

echo "\nSummary: $passCount Passed, $failCount Failed.\n";

if ($failCount > 0) {
    exit(1);
}
