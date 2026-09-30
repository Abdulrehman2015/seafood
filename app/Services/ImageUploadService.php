<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Max dimensions for proportional downscaling (never upscales).
     */
    protected int $maxWidth = 1920;
    protected int $maxHeight = 1920;

    /**
     * Default WebP compression quality (82-85 for photo, 88-90 for graphics).
     */
    protected int $defaultQuality = 84;

    /**
     * Upload an image, validate, resize proportionally, optimize, convert to .webp, and record in Media.
     */
    public function upload(UploadedFile $file, string $folder = 'gallery', ?string $alt = null, array $options = []): Media
    {
        // 1. Validate file existence and basic integrity
        if (!$file->isValid()) {
            throw new \InvalidArgumentException('Uploaded file is invalid: ' . $file->getErrorMessage());
        }

        $sourcePath = $file->getRealPath();
        $originalName = $file->getClientOriginalName();
        $originalSize = $file->getSize();

        // 2. Determine actual MIME type using Fileinfo / getimagesize
        $imageInfo = @getimagesize($sourcePath);
        $finfoMime = @mime_content_type($sourcePath);
        $mimeType = $imageInfo['mime'] ?? $finfoMime ?? $file->getMimeType();

        $baseName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        if (empty($baseName)) {
            $baseName = 'image';
        }

        $relativeFolder = trim($folder, '/');
        $uniqueSuffix = Str::lower(Str::random(6));
        $timestamp = time();

        // Handle SVG and non-raster media separately without raster degradation
        if ($mimeType === 'image/svg+xml' || str_ends_with(strtolower($originalName), '.svg')) {
            $filename = "{$timestamp}_{$uniqueSuffix}_{$baseName}.svg";
            $relativePath = "{$relativeFolder}/{$filename}";
            $targetFullPath = Storage::disk('public')->path($relativePath);
            File::ensureDirectoryExists(dirname($targetFullPath));

            // Copy file to storage
            copy($sourcePath, $targetFullPath);
            $this->mirrorToPublicStorage($relativePath, $targetFullPath);

            return Media::create([
                'filename'      => $filename,
                'original_name' => $originalName,
                'path'          => $relativePath,
                'mime_type'     => 'image/svg+xml',
                'size'          => filesize($targetFullPath),
                'width'         => null,
                'height'        => null,
                'folder'        => $relativeFolder,
                'alt_text'      => $alt ?? pathinfo($originalName, PATHINFO_FILENAME),
            ]);
        }

        // 3. For raster images, define WebP target path
        $filename = "{$timestamp}_{$uniqueSuffix}_{$baseName}.webp";
        $relativePath = "{$relativeFolder}/{$filename}";
        $targetFullPath = Storage::disk('public')->path($relativePath);
        File::ensureDirectoryExists(dirname($targetFullPath));

        // 4. Optimize and convert to WebP
        $quality = $options['quality'] ?? $this->determineQuality($mimeType, $imageInfo);
        $maxWidth = $options['max_width'] ?? $this->maxWidth;
        $maxHeight = $options['max_height'] ?? $this->maxHeight;

        $conversionResult = $this->optimizeAndConvert(
            $sourcePath,
            $targetFullPath,
            $imageInfo,
            $quality,
            $maxWidth,
            $maxHeight
        );

        if (!$conversionResult['success'] || !file_exists($targetFullPath)) {
            // Fallback: store original file if conversion library was completely unavailable
            $rawExt = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = "{$timestamp}_{$uniqueSuffix}_{$baseName}.{$rawExt}";
            $relativePath = "{$relativeFolder}/{$filename}";
            $file->storeAs($relativeFolder, $filename, 'public');
            $targetFullPath = Storage::disk('public')->path($relativePath);
            $finalMime = $mimeType;
            $width = $imageInfo ? $imageInfo[0] : null;
            $height = $imageInfo ? $imageInfo[1] : null;
        } else {
            $finalMime = 'image/webp';
            $width = $conversionResult['width'];
            $height = $conversionResult['height'];
        }

        // 5. Mirror to public/storage if directory exists
        $this->mirrorToPublicStorage($relativePath, $targetFullPath);

        $fileSize = file_exists($targetFullPath) ? filesize($targetFullPath) : $originalSize;

        return Media::create([
            'filename'      => $filename,
            'original_name' => $originalName,
            'path'          => $relativePath,
            'mime_type'     => $finalMime,
            'size'          => $fileSize,
            'width'         => $width,
            'height'        => $height,
            'folder'        => $relativeFolder,
            'alt_text'      => $alt ?? pathinfo($originalName, PATHINFO_FILENAME),
        ]);
    }

    /**
     * Convert an arbitrary image file to optimized WebP at a specific target destination.
     */
    public function optimizeAndConvert(
        string $sourcePath,
        string $targetFullPath,
        ?array $imageInfo = null,
        int $quality = 84,
        int $maxWidth = 1920,
        int $maxHeight = 1920
    ): array {
        if (!function_exists('imagewebp')) {
            Log::warning('GD imagewebp is not available on this server.');
            return ['success' => false, 'width' => null, 'height' => null];
        }

        if (!$imageInfo) {
            $imageInfo = @getimagesize($sourcePath);
        }

        if (!$imageInfo) {
            return ['success' => false, 'width' => null, 'height' => null];
        }

        $origWidth = $imageInfo[0];
        $origHeight = $imageInfo[1];
        $imageType = $imageInfo[2];

        $imageResource = null;

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $imageResource = @imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $imageResource = @imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_WEBP:
                $imageResource = @imagecreatefromwebp($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $imageResource = @imagecreatefromgif($sourcePath);
                break;
            case defined('IMAGETYPE_BMP') ? IMAGETYPE_BMP : 6:
                if (function_exists('imagecreatefrombmp')) {
                    $imageResource = @imagecreatefrombmp($sourcePath);
                }
                break;
            case defined('IMAGETYPE_AVIF') ? IMAGETYPE_AVIF : 19:
                if (function_exists('imagecreatefromavif')) {
                    $imageResource = @imagecreatefromavif($sourcePath);
                }
                break;
        }

        if (!$imageResource) {
            return ['success' => false, 'width' => $origWidth, 'height' => $origHeight];
        }

        // Preserve palette truecolor
        if (function_exists('imagepalettetotruecolor') && !imageistruecolor($imageResource)) {
            imagepalettetotruecolor($imageResource);
        }

        // Calculate proportional dimensions (never upscale smaller images)
        $targetWidth = $origWidth;
        $targetHeight = $origHeight;

        if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
            $targetWidth = (int) max(1, round($origWidth * $ratio));
            $targetHeight = (int) max(1, round($origHeight * $ratio));

            $resizedResource = imagecreatetruecolor($targetWidth, $targetHeight);

            // Handle transparency for alpha-channel images
            imagealphablending($resizedResource, false);
            imagesavealpha($resizedResource, true);
            $transparent = imagecolorallocatealpha($resizedResource, 255, 255, 255, 127);
            imagefilledrectangle($resizedResource, 0, 0, $targetWidth, $targetHeight, $transparent);

            imagecopyresampled(
                $resizedResource,
                $imageResource,
                0, 0, 0, 0,
                $targetWidth,
                $targetHeight,
                $origWidth,
                $origHeight
            );

            imagedestroy($imageResource);
            $imageResource = $resizedResource;
        } else {
            imagealphablending($imageResource, false);
            imagesavealpha($imageResource, true);
        }

        File::ensureDirectoryExists(dirname($targetFullPath));

        // Output optimized WebP
        $converted = @imagewebp($imageResource, $targetFullPath, $quality);
        imagedestroy($imageResource);

        return [
            'success' => $converted && file_exists($targetFullPath) && filesize($targetFullPath) > 0,
            'width'   => $targetWidth,
            'height'  => $targetHeight,
        ];
    }

    /**
     * Upload multiple images.
     */
    public function uploadMultiple(array $files, string $folder = 'gallery', array $options = []): Collection
    {
        $uploaded = collect();

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                try {
                    $uploaded->push($this->upload($file, $folder, null, $options));
                } catch (\Throwable $e) {
                    Log::error('Multi-upload file error: ' . $e->getMessage());
                }
            }
        }

        return $uploaded;
    }

    /**
     * Safely delete an old image file when replaced by an administrator.
     */
    public function deleteOldImage(?string $relativePath): bool
    {
        if (empty($relativePath)) {
            return false;
        }

        // Protect core brand assets / placeholders
        $protected = ['images/logo.webp', 'images/logo.png', 'images/favicon.webp', 'images/placeholder.png', 'images/og-default.jpg'];
        foreach ($protected as $p) {
            if (str_ends_with(strtolower($relativePath), $p)) {
                return false;
            }
        }

        $cleanPath = ltrim(preg_replace('#^storage/#', '', $relativePath), '/');

        $storagePath = Storage::disk('public')->path($cleanPath);
        $publicPath = public_path('storage/' . $cleanPath);

        $deleted = false;
        if (file_exists($storagePath) && is_file($storagePath)) {
            @unlink($storagePath);
            $deleted = true;
        }
        if (file_exists($publicPath) && is_file($publicPath) && !is_link(public_path('storage'))) {
            @unlink($publicPath);
            $deleted = true;
        }

        return $deleted;
    }

    /**
     * Mirror a saved file to public/storage for immediate web accessibility on Windows environments.
     */
    protected function mirrorToPublicStorage(string $relativePath, string $sourceFullPath): void
    {
        $publicStorageBase = public_path('storage');
        if (file_exists($publicStorageBase) && !is_link($publicStorageBase)) {
            $destPath = $publicStorageBase . '/' . ltrim($relativePath, '/');
            File::ensureDirectoryExists(dirname($destPath));
            @copy($sourceFullPath, $destPath);
        }
    }

    /**
     * Determine intelligent WebP quality depending on image characteristics.
     */
    protected function determineQuality(string $mimeType, ?array $imageInfo): int
    {
        if ($mimeType === 'image/png' || ($imageInfo && $imageInfo[2] === IMAGETYPE_PNG)) {
            return 88; // Higher quality for sharp graphic lines / transparency
        }
        return $this->defaultQuality; // 84 for photos (optimal compression with pristine clarity)
    }
}
