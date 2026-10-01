<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Translation;

echo "=======================================================\n";
echo "MST WEBSITE MASTER AUDIT & QA\n";
echo "=======================================================\n\n";

// 1. PRODUCTS & PRICING AUDIT
echo "--- 1. PRODUCTS IN DATABASE ---\n";
$products = Product::with('category')->orderBy('id')->get();
echo "Total Products: " . $products->count() . "\n";

foreach ($products as $p) {
    echo sprintf(
        "ID: %2d | SKU: %-18s | Slug: %-35s | Unit: %-8s | Model: %-15s | Retail: RM %6.2f | Walkin: RM %6.2f | Wholesale: RM %6.2f | Trading: RM %6.2f | Active: %d | WalkinAvail: %d\n",
        $p->id,
        $p->sku,
        $p->slug,
        $p->unit,
        $p->pricing_model ?? 'fixed_unit',
        (float)$p->retail_price,
        (float)$p->walkin_price,
        (float)$p->wholesale_price,
        (float)$p->trading_price,
        $p->is_active ? 1 : 0,
        $p->is_walkin_available ? 1 : 0
    );
    echo "   EN Name: " . $p->getRawOriginal('name') . "\n";
    echo "   ZH Name: " . ($p->name_zh ?: '(empty)') . "\n";
    echo "   BM Name: " . ($p->name_bm ?: '(empty)') . "\n";
    echo "   Thumb  : " . ($p->thumbnail ?: '(empty)') . "\n";
    echo "   Storage: " . ($p->storage_temp ?: '(empty)') . " | Icon: " . ($p->storage_icon ?: '(auto)') . "\n";
    echo "   Specs  : " . json_encode($p->specifications) . "\n\n";
}

// 2. CATEGORIES AUDIT
echo "--- 2. CATEGORIES IN DATABASE ---\n";
$categories = Category::orderBy('sort_order')->get();
foreach ($categories as $c) {
    echo sprintf("ID: %2d | Slug: %-25s | EN: %-25s | ZH: %-25s | BM: %-25s | Parent: %s\n",
        $c->id,
        $c->slug,
        $c->name,
        $c->name_zh ?? '(empty)',
        $c->name_bm ?? '(empty)',
        $c->parent_id ?? 'None'
    );
}

// 3. CANADIAN SCALLOP & SASHIMI AUDIT
echo "\n--- 3. SASHIMI GRADE / CANADIAN SCALLOP AUDIT ---\n";
$scallops = Product::where('slug', 'like', '%scallop%')->orWhere('name', 'like', '%scallop%')->orWhere('sku', 'like', '%scallop%')->get();
foreach ($scallops as $sc) {
    echo "Scallop ID: {$sc->id} | Name: {$sc->getRawOriginal('name')} | ZH: {$sc->name_zh} | BM: {$sc->name_bm}\n";
    echo "   Specs: " . json_encode($sc->specifications) . "\n";
    echo "   Short Desc: " . $sc->short_description . "\n";
    echo "   Desc: " . $sc->description . "\n\n";
}

// 4. FROZEN LOLIGO AUDIT
echo "\n--- 4. LOLIGO / SQUID AUDIT ---\n";
$squids = Product::where('slug', 'like', '%loligo%')->orWhere('name', 'like', '%loligo%')->get();
foreach ($squids as $sq) {
    echo "Squid ID: {$sq->id} | Slug: {$sq->slug} | Name: {$sq->getRawOriginal('name')} | ZH: {$sq->name_zh} | BM: {$sq->name_bm}\n";
}

// 5. LIVE MUD CRAB AUDIT
echo "\n--- 5. LIVE MUD CRAB AUDIT ---\n";
$crabs = Product::where('slug', 'like', '%mud-crab%')->orWhere('name', 'like', '%mud crab%')->orWhere('sku', 'like', '%crab%')->get();
foreach ($crabs as $cr) {
    echo "Crab ID: {$cr->id} | SKU: {$cr->sku} | Slug: {$cr->slug} | Unit: {$cr->unit} | Pricing Model: {$cr->pricing_model} | Ref Wt: {$cr->reference_weight} | Actual Wt Unit: {$cr->actual_weight_unit} | Unit Price Per Wt: {$cr->unit_price_per_weight}\n";
    echo "   Name EN: {$cr->getRawOriginal('name')}\n";
    echo "   Name ZH: {$cr->name_zh}\n";
    echo "   Name BM: {$cr->name_bm}\n";
    echo "   Storage: {$cr->storage_temp}\n";
    echo "   Specs  : " . json_encode($cr->specifications) . "\n\n";
}

// 6. DORY & UNAGI IMAGE AUDIT
echo "\n--- 6. DORY & UNAGI AUDIT ---\n";
$dory = Product::where('slug', 'like', '%dory%')->orWhere('name', 'like', '%dory%')->first();
if ($dory) {
    echo "Dory ID: {$dory->id} | Slug: {$dory->slug} | Thumb: {$dory->thumbnail} | Images: " . json_encode($dory->images) . "\n";
    echo "File exists: " . (file_exists(public_path('storage/' . $dory->thumbnail)) || file_exists(storage_path('app/public/' . $dory->thumbnail)) ? 'YES' : 'NO') . "\n";
}
$unagi = Product::where('slug', 'like', '%unagi%')->orWhere('name', 'like', '%unagi%')->first();
if ($unagi) {
    echo "Unagi ID: {$unagi->id} | Slug: {$unagi->slug} | Thumb: {$unagi->thumbnail} | Images: " . json_encode($unagi->images) . "\n";
    echo "File exists: " . (file_exists(public_path('storage/' . $unagi->thumbnail)) || file_exists(storage_path('app/public/' . $unagi->thumbnail)) ? 'YES' : 'NO') . "\n";
}

// 7. SETTINGS AUDIT
echo "\n--- 7. KEY SETTINGS ---\n";
echo "store_name: " . Setting::get('store_name') . "\n";
echo "company_name_zh: " . Setting::get('company_name_zh') . "\n";
echo "slogan: " . Setting::get('store_slogan') . "\n";
echo "min_order_retail (delivery threshold): " . Setting::get('min_order_retail', '100') . "\n";
echo "min_order_wholesale (delivery threshold): " . Setting::get('min_order_wholesale', '350') . "\n";

echo "\nMaster Audit Complete.\n";
