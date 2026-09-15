<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Upload an image, convert to .webp preserving 100% resolution, minimize size, and record in Media.
     */
    public function upload(UploadedFile $file, string $folder = 'gallery', ?string $alt = null): Media
    {
        $originalName = $file->getClientOriginalName();
        $baseName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        if (empty($baseName)) {
            $baseName = 'image';
        }

        $filename = time() . '_' . Str::random(6) . '_' . $baseName . '.webp';
        $relativeFolder = trim($folder, '/');
        $relativePath = $relativeFolder . '/' . $filename;
        $targetFullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($relativePath);
        File::ensureDirectoryExists(dirname($targetFullPath));

        $sourcePath = $file->getRealPath();
        $imageInfo = @getimagesize($sourcePath);

        $width = $imageInfo ? $imageInfo[0] : null;
        $height = $imageInfo ? $imageInfo[1] : null;
        $mimeType = $imageInfo ? $imageInfo['mime'] : $file->getMimeType();

        $converted = false;

        if (function_exists('imagewebp') && $imageInfo) {
            $imageResource = null;

            switch ($imageInfo[2]) {
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
            }

            if ($imageResource) {
                // Handle palette images and preserve transparency for PNG/WebP
                if (function_exists('imagepalettetotruecolor') && !imageistruecolor($imageResource)) {
                    imagepalettetotruecolor($imageResource);
                }

                imagealphablending($imageResource, false);
                imagesavealpha($imageResource, true);
                $width = imagesx($imageResource);
                $height = imagesy($imageResource);

                // Convert to WebP at Quality 85: lossless resolution, massive file-size reduction
                $converted = @imagewebp($imageResource, $targetFullPath, 85);
                imagedestroy($imageResource);
            }
        }

        // Fallback if GD conversion failed
        if (!$converted || !file_exists($targetFullPath)) {
            $rawExt = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = time() . '_' . Str::random(6) . '_' . $baseName . '.' . $rawExt;
            $relativePath = $relativeFolder . '/' . $filename;
            $file->storeAs($relativeFolder, $filename, 'public');
            $targetFullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($relativePath);
            $finalMime = $mimeType;
        } else {
            $finalMime = 'image/webp';
        }

        $fileSize = file_exists($targetFullPath) ? filesize($targetFullPath) : $file->getSize();

        // If resolution wasn't captured, check final file
        if ((!$width || !$height) && file_exists($targetFullPath)) {
            $finalInfo = @getimagesize($targetFullPath);
            if ($finalInfo) {
                $width = $finalInfo[0];
                $height = $finalInfo[1];
            }
        }

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
     * Upload multiple images.
     */
    public function uploadMultiple(array $files, string $folder = 'gallery'): Collection
    {
        $uploaded = collect();

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $uploaded->push($this->upload($file, $folder));
            }
        }

        return $uploaded;
    }
}
