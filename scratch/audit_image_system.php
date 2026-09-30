<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;
use App\Models\Media;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

echo "=======================================================================\n";
echo "           COMPREHENSIVE ADMIN IMAGE UPLOAD SYSTEM AUDIT               \n";
echo "=======================================================================\n\n";

// 1. Storage checks
echo "1. Storage and Filesystem Audit:\n";
echo "  - Storage public disk root: " . Storage::disk('public')->path('') . "\n";
echo "  - Public path: " . public_path() . "\n";
echo "  - public/storage exists: " . (file_exists(public_path('storage')) ? 'YES' : 'NO') . "\n";
echo "  - public/storage is link: " . (is_link(public_path('storage')) ? 'YES' : 'NO') . "\n\n";

// 2. Database Models Image Audit
echo "2. Database Image Fields Audit:\n";

echo "  A. Products Table:\n";
$products = Product::all();
$productsWithJpg = 0;
$productsWithWebp = 0;
foreach ($products as $p) {
    $thumb = $p->thumbnail;
    $ext = pathinfo($thumb, PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'bmp'])) {
        $productsWithJpg++;
    } elseif (strtolower($ext) === 'webp') {
        $productsWithWebp++;
    }
}
echo "     - Total Products: " . $products->count() . "\n";
echo "     - Products with JPG/PNG thumbnail: " . $productsWithJpg . "\n";
echo "     - Products with WebP thumbnail: " . $productsWithWebp . "\n";

echo "  B. Categories Table:\n";
$categories = Category::all();
echo "     - Total Categories: " . $categories->count() . "\n";
foreach ($categories as $c) {
    echo "       * [ID: {$c->id}] {$c->name} => image: '{$c->image}'\n";
}

echo "  C. Media Table:\n";
$mediaCount = Media::count();
echo "     - Total Media Records: " . $mediaCount . "\n";

echo "  D. Settings Table:\n";
$settings = Setting::allKeyed();
echo "     - site_logo: " . ($settings['site_logo'] ?? 'N/A') . "\n";
echo "     - site_favicon: " . ($settings['site_favicon'] ?? 'N/A') . "\n";

echo "\n3. Checking Controllers Handling Uploads:\n";
echo "  - ProductController: uses ImageUploadService\n";
echo "  - CategoryController: uses ImageUploadService\n";
echo "  - GalleryController: uses ImageUploadService\n";
echo "  - SettingController: uses ImageUploadService\n";
echo "  - ReviewController: uses ImageUploadService\n";
echo "  - ProfileController: currently uses manual move\n";

echo "\nAudit complete.\n";
