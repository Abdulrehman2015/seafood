<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;

echo "=======================================================\n";
echo "STEP 1: UPDATING DATABASE SETTINGS\n";
echo "=======================================================\n";

Setting::set('store_name', 'MST Import and Export Sdn. Bhd.');
Setting::set('company_name_zh', '镁嘉国际贸易有限公司');
Setting::set('store_slogan', 'Flow with Integrity, Grow with Strength.');
Setting::set('market_coverage', 'Malaysia and Singapore');
Setting::set('standard_delivery_area', 'Johor Bahru and selected areas of Iskandar Puteri / Nusajaya');

echo "✓ Settings updated successfully.\n\n";

echo "=======================================================\n";
echo "STEP 2: UPDATING CATEGORIES (EN / ZH / BM)\n";
echo "=======================================================\n";

$categoryUpdates = [
    'fish' => [
        'name' => 'Fish',
        'name_zh' => '鱼类',
        'name_bm' => 'Ikan',
    ],
    'fish-fillet' => [
        'name' => 'Fish Fillet',
        'name_zh' => '鱼柳',
        'name_bm' => 'Fillet Ikan',
    ],
    'crab' => [
        'name' => 'Crab',
        'name_zh' => '蟹类',
        'name_bm' => 'Ketam',
    ],
    'prawns-shrimps' => [
        'name' => 'Prawns / Shrimps',
        'name_zh' => '虾类',
        'name_bm' => 'Udang',
    ],
    'squid' => [
        'name' => 'Squid / Cuttlefish',
        'name_zh' => '鱿鱼',
        'name_bm' => 'Sotong',
    ],
    'shellfish' => [
        'name' => 'Shellfish',
        'name_zh' => '贝类',
        'name_bm' => 'Kerang-kerangan',
    ],
    'other-frozen-seafood' => [
        'name' => 'Other Seafood',
        'name_zh' => '其他海产',
        'name_bm' => 'Makanan Laut Lain',
    ],
    'steamboat' => [
        'name' => 'Steamboat / Hotpot',
        'name_zh' => '火锅食材',
        'name_bm' => 'Steamboat / Hotpot',
    ],
];

foreach ($categoryUpdates as $slug => $data) {
    Category::where('slug', $slug)->update($data);
    echo "✓ Category '{$slug}' updated: EN='{$data['name']}', ZH='{$data['name_zh']}', BM='{$data['name_bm']}'\n";
}

echo "\n=======================================================\n";
echo "STEP 3: UPDATING PRODUCTS (CANADIAN SCALLOPS & FROZEN LOLIGO)\n";
echo "=======================================================\n";

// 1. Canadian Sea Scallops: Remove Sashimi Grade claim
$scallop = Product::where('sku', 'SHELL-SCALLOP-500')->orWhere('slug', 'canadian-sea-scallops-500g')->first();
if ($scallop) {
    $scallop->name = 'Canadian Sea Scallops (500g)';
    $scallop->name_zh = '加拿大带子 / 大扇贝肉（500g）';
    $scallop->name_bm = 'Skalop Laut Kanada (500g)';
    $scallop->short_description = 'Wild-caught colossal Canadian sea scallops, dry-packed with no added water.';
    $scallop->description = 'Premium natural dry-pack sea scallops wild-harvested in the icy waters of the North Atlantic. Plump, buttery texture with deep caramelization when seared. Certified chemical-free and unsoaked.';
    $scallop->save();
    echo "✓ Scallop product (ID {$scallop->id}) updated: Name='{$scallop->name}' (Sashimi Grade removed)\n";
}

// 2. Frozen Loligo: Update slug to frozen-loligo-squid-sotong-jarum-1kg
$loligo = Product::where('sku', 'CEPH-LOLIGO-1KG')->orWhere('slug', 'fresh-loligo-squid-sotong-jarum-1kg')->first();
if ($loligo) {
    $loligo->name = 'Frozen Loligo Squid / Sotong Jarum (1kg)';
    $loligo->slug = 'frozen-loligo-squid-sotong-jarum-1kg';
    $loligo->name_zh = '急冻火箭鱿鱼 / 针鱿 (1kg)';
    $loligo->name_bm = 'Sotong Jarum Beku / Loligo Squid (1kg)';
    $loligo->save();
    echo "✓ Loligo product (ID {$loligo->id}) updated: Slug='{$loligo->slug}', Name='{$loligo->name}'\n";
}

// 3. Live Mud Crab: Verify data & condition
$mudCrab = Product::where('sku', 'CRAB-MUD-800')->first();
if ($mudCrab) {
    $mudCrab->name = 'Live Mud Crabs / Ketam Nipah (±800g / pair)';
    $mudCrab->name_zh = '青蟹 / 肉蟹 (±800g / 1对装)';
    $mudCrab->name_bm = 'Ketam Nipah Segar (±800g / Sepasang)';
    $mudCrab->storage_temp = 'Live / Chilled';
    $mudCrab->unit = 'pair';
    $mudCrab->pricing_model = 'variable_weight';
    $mudCrab->reference_weight = '±800g';
    $mudCrab->save();
    echo "✓ Live Mud Crab (ID {$mudCrab->id}) verified & updated: Unit='{$mudCrab->unit}', Pricing Model='{$mudCrab->pricing_model}', Storage='{$mudCrab->storage_temp}'\n";
}

echo "\n=======================================================\n";
echo "STEP 4: SCRIPT COMPLETED\n";
echo "=======================================================\n";
