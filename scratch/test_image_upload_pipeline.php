<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\ImageUploadService;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

echo "=======================================================================\n";
echo "       TESTING GLOBAL IMAGE COMPRESSION & WEBP PIPELINE                \n";
echo "=======================================================================\n\n";

$service = app(ImageUploadService::class);
$tempDir = storage_path('framework/testing/img_test');
File::ensureDirectoryExists($tempDir);

$passes = [];
$failures = [];

// -------------------------------------------------------------
// TEST 1: JPEG Upload & Proportional Scaling to WebP
// -------------------------------------------------------------
echo "1. Testing JPEG upload & conversion to WebP...\n";
$jpegPath = $tempDir . '/test_large.jpg';
$img = imagecreatetruecolor(2400, 1600);
$color1 = imagecolorallocate($img, 30, 60, 120);
$color2 = imagecolorallocate($img, 220, 200, 50);
imagefilledrectangle($img, 0, 0, 2400, 1600, $color1);
imagefilledellipse($img, 1200, 800, 800, 800, $color2);
imagejpeg($img, $jpegPath, 95);
imagedestroy($img);

$uploadedFile = new UploadedFile($jpegPath, 'test_large.jpg', 'image/jpeg', null, true);
$media = $service->upload($uploadedFile, 'test_products', 'Test Large JPEG');

if ($media && str_ends_with($media->filename, '.webp') && $media->mime_type === 'image/webp') {
    $fullPath = Storage::disk('public')->path($media->path);
    if (file_exists($fullPath) && filesize($fullPath) > 0) {
        $info = getimagesize($fullPath);
        // Should scale down to max 1920 wide proportionally (1920x1280)
        if ($info[0] <= 1920 && $info[1] <= 1920 && abs(($info[0]/$info[1]) - (2400/1600)) < 0.01) {
            $passes[] = "JPEG converted to WebP successfully with proportional scaling: {$info[0]}x{$info[1]} px, size " . round(filesize($fullPath)/1024, 1) . " KB (orig: " . round(filesize($jpegPath)/1024, 1) . " KB).";
        } else {
            $failures[] = "JPEG dimensions invalid: {$info[0]}x{$info[1]}";
        }
    } else {
        $failures[] = "Converted WebP file does not exist on disk!";
    }
} else {
    $failures[] = "JPEG conversion failed or did not return WebP!";
}

// -------------------------------------------------------------
// TEST 2: PNG with Alpha Transparency to WebP
// -------------------------------------------------------------
echo "2. Testing PNG with alpha transparency to WebP...\n";
$pngPath = $tempDir . '/test_transparent.png';
$pngImg = imagecreatetruecolor(500, 500);
imagealphablending($pngImg, false);
imagesavealpha($pngImg, true);
$trans = imagecolorallocatealpha($pngImg, 0, 0, 0, 127);
imagefilledrectangle($pngImg, 0, 0, 500, 500, $trans);
$red = imagecolorallocatealpha($pngImg, 220, 20, 60, 0);
imagefilledellipse($pngImg, 250, 250, 300, 300, $red);
imagepng($pngImg, $pngPath, 6);
imagedestroy($pngImg);

$uploadedPng = new UploadedFile($pngPath, 'test_transparent.png', 'image/png', null, true);
$mediaPng = $service->upload($uploadedPng, 'test_gallery', 'Test Transparent PNG');

if ($mediaPng && str_ends_with($mediaPng->filename, '.webp') && $mediaPng->mime_type === 'image/webp') {
    $pngFullPath = Storage::disk('public')->path($mediaPng->path);
    if (file_exists($pngFullPath) && filesize($pngFullPath) > 0) {
        $passes[] = "PNG with transparency converted to WebP successfully: size " . round(filesize($pngFullPath)/1024, 1) . " KB (orig: " . round(filesize($pngPath)/1024, 1) . " KB).";
    } else {
        $failures[] = "PNG WebP file does not exist!";
    }
} else {
    $failures[] = "PNG conversion failed!";
}

// -------------------------------------------------------------
// TEST 3: Multi-Image Upload
// -------------------------------------------------------------
echo "3. Testing multi-image batch upload...\n";
$multiFiles = [
    new UploadedFile($jpegPath, 'batch_1.jpg', 'image/jpeg', null, true),
    new UploadedFile($pngPath, 'batch_2.png', 'image/png', null, true),
];

$batchResults = $service->uploadMultiple($multiFiles, 'test_batch');
if ($batchResults->count() === 2) {
    $allWebp = $batchResults->every(fn($m) => str_ends_with($m->filename, '.webp'));
    if ($allWebp) {
        $passes[] = "Batch multi-image upload processed 2 images independently into WebP.";
    } else {
        $failures[] = "Batch multi-image upload contained non-webp results.";
    }
} else {
    $failures[] = "Batch multi-image upload returned count " . $batchResults->count() . ", expected 2.";
}

// -------------------------------------------------------------
// TEST 4: Safe Deletion on Replacement
// -------------------------------------------------------------
echo "4. Testing safe deletion on replacement...\n";
$testOldPath = 'test_gallery/' . $mediaPng->filename;
$deleted = $service->deleteOldImage($testOldPath);
if ($deleted && !file_exists(Storage::disk('public')->path($testOldPath))) {
    $passes[] = "deleteOldImage successfully removed old file from disk upon replacement.";
} else {
    $failures[] = "deleteOldImage failed to remove old test file.";
}

// Cleanup test records and temp files
$media->delete();
$mediaPng->delete();
foreach ($batchResults as $bm) {
    $bm->delete();
}
File::deleteDirectory($tempDir);
Storage::disk('public')->deleteDirectory('test_products');
Storage::disk('public')->deleteDirectory('test_gallery');
Storage::disk('public')->deleteDirectory('test_batch');

echo "\n=======================================================================\n";
echo "                         TEST RESULTS                                  \n";
echo "=======================================================================\n";

foreach ($passes as $p) {
    echo "  ✓ {$p}\n";
}

if (empty($failures)) {
    echo "\n🎉 ALL IMAGE PIPELINE TESTS PASSED WITH 0 FAILURES!\n";
} else {
    echo "\n⚠️ FAILURES:\n";
    foreach ($failures as $f) {
        echo "  ✗ {$f}\n";
    }
}
echo "=======================================================================\n";
