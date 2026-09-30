<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Media;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

echo "=======================================================================\n";
echo "       MIGRATING EXISTING PRODUCT & MEDIA IMAGES TO WEBP               \n";
echo "=======================================================================\n\n";

$service = app(ImageUploadService::class);
$storageDir = storage_path('app/public/products');
$publicStorageDir = public_path('storage/products');

File::ensureDirectoryExists($storageDir);
File::ensureDirectoryExists($publicStorageDir);

$files = scandir($storageDir);
$convertedCount = 0;
$totalSavings = 0;

foreach ($files as $file) {
    if ($file === '.' || $file === '..' || is_dir($storageDir . '/' . $file)) {
        continue;
    }

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'bmp'])) {
        $sourcePath = $storageDir . '/' . $file;
        $webpFilename = pathinfo($file, PATHINFO_FILENAME) . '.webp';
        $targetStoragePath = $storageDir . '/' . $webpFilename;
        $targetPublicPath = $publicStorageDir . '/' . $webpFilename;

        $origSize = filesize($sourcePath);

        $result = $service->optimizeAndConvert(
            $sourcePath,
            $targetStoragePath,
            null,
            85, // quality
            1600, // max width
            1600  // max height
        );

        if ($result['success'] && file_exists($targetStoragePath)) {
            $newSize = filesize($targetStoragePath);
            $savings = $origSize - $newSize;
            $totalSavings += max(0, $savings);

            // Mirror to public storage
            @copy($targetStoragePath, $targetPublicPath);

            $percent = $origSize > 0 ? round(($savings / $origSize) * 100, 1) : 0;
            echo "✓ Converted: {$file} (" . round($origSize / 1024, 1) . " KB) → {$webpFilename} (" . round($newSize / 1024, 1) . " KB) [Saved {$percent}%]\n";
            $convertedCount++;
        } else {
            echo "✗ Failed to convert: {$file}\n";
        }
    }
}

echo "\nTotal raster images converted to WebP: {$convertedCount}\n";
echo "Total bandwidth / disk saved: " . round($totalSavings / 1024, 1) . " KB\n\n";

// Update Product Database records
echo "Updating Products Table database references in MySQL...\n";
$products = Product::all();
$updatedProducts = 0;

foreach ($products as $p) {
    $dirty = false;

    // Update thumbnail
    if ($p->thumbnail) {
        $thumbExt = strtolower(pathinfo($p->thumbnail, PATHINFO_EXTENSION));
        if (in_array($thumbExt, ['jpg', 'jpeg', 'png', 'bmp'])) {
            $newThumb = pathinfo($p->thumbnail, PATHINFO_DIRNAME) . '/' . pathinfo($p->thumbnail, PATHINFO_FILENAME) . '.webp';
            $cleanNewThumb = ltrim(str_replace('\\', '/', $newThumb), './');
            
            // Verify file exists
            if (file_exists(storage_path('app/public/' . $cleanNewThumb)) || file_exists(public_path('storage/' . $cleanNewThumb))) {
                $p->thumbnail = $cleanNewThumb;
                $dirty = true;
            }
        }
    }

    // Update gallery images array
    if (is_array($p->images) && !empty($p->images)) {
        $newImages = [];
        foreach ($p->images as $img) {
            $imgExt = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            if (in_array($imgExt, ['jpg', 'jpeg', 'png', 'bmp'])) {
                $newImg = pathinfo($img, PATHINFO_DIRNAME) . '/' . pathinfo($img, PATHINFO_FILENAME) . '.webp';
                $cleanNewImg = ltrim(str_replace('\\', '/', $newImg), './');
                if (file_exists(storage_path('app/public/' . $cleanNewImg)) || file_exists(public_path('storage/' . $cleanNewImg))) {
                    $newImages[] = $cleanNewImg;
                    $dirty = true;
                    continue;
                }
            }
            $newImages[] = $img;
        }
        $p->images = $newImages;
    }

    if ($dirty) {
        $p->save();
        echo "✓ Updated Product [ID: {$p->id}] '{$p->name}' → Thumbnail: {$p->thumbnail}\n";
        $updatedProducts++;
    }
}

echo "\nTotal product records updated to WebP: {$updatedProducts}\n";
echo "\n=======================================================================\n";
echo "MIGRATION COMPLETED SUCCESSFULLY!\n";
echo "=======================================================================\n";
