<?php

require __DIR__ . '/../vendor/autoload.php';

echo "=== PHP ENVIRONMENT IMAGE EXTENSIONS AUDIT ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "GD Extension: " . (extension_loaded('gd') ? 'ENABLED' : 'NOT INSTALLED') . "\n";
if (extension_loaded('gd')) {
    $info = gd_info();
    echo "  - WebP Support: " . ($info['WebP Support'] ?? false ? 'YES' : 'NO') . "\n";
    echo "  - AVIF Support: " . ($info['AVIF Support'] ?? false ? 'YES' : 'NO') . "\n";
    echo "  - JPEG Support: " . ($info['JPEG Support'] ?? false ? 'YES' : 'NO') . "\n";
    echo "  - PNG Support: " . ($info['PNG Support'] ?? false ? 'YES' : 'NO') . "\n";
    echo "  - GIF Read/Create: " . ($info['GIF Read Support'] ?? false ? 'YES' : 'NO') . " / " . ($info['GIF Create Support'] ?? false ? 'YES' : 'NO') . "\n";
    echo "  - BMP Support: " . ($info['BMP Support'] ?? false ? 'YES' : 'NO') . "\n";
    echo "  - FreeType Support: " . ($info['FreeType Support'] ?? false ? 'YES' : 'NO') . "\n";
}
echo "Imagick Extension: " . (extension_loaded('imagick') ? 'ENABLED' : 'NOT INSTALLED') . "\n";
echo "Intervention Image: " . (class_exists('Intervention\Image\ImageManager') || class_exists('Intervention\Image\ImageManagerStatic') ? 'INSTALLED' : 'NOT INSTALLED') . "\n";

echo "\nFunctions check:\n";
echo "  - imagewebp: " . (function_exists('imagewebp') ? 'YES' : 'NO') . "\n";
echo "  - imagecreatefromjpeg: " . (function_exists('imagecreatefromjpeg') ? 'YES' : 'NO') . "\n";
echo "  - imagecreatefrompng: " . (function_exists('imagecreatefrompng') ? 'YES' : 'NO') . "\n";
echo "  - imagecreatefromwebp: " . (function_exists('imagecreatefromwebp') ? 'YES' : 'NO') . "\n";
echo "  - imagecreatefromgif: " . (function_exists('imagecreatefromgif') ? 'YES' : 'NO') . "\n";
echo "  - imagecreatefrombmp: " . (function_exists('imagecreatefrombmp') ? 'YES' : 'NO') . "\n";
echo "  - imagecreatefromavif: " . (function_exists('imagecreatefromavif') ? 'YES' : 'NO') . "\n";
echo "  - exif_read_data: " . (function_exists('exif_read_data') ? 'YES' : 'NO') . "\n";
echo "  - finfo_open: " . (function_exists('finfo_open') ? 'YES' : 'NO') . "\n";
