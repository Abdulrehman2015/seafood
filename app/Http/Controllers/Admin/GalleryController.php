<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MediaFolder;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function __construct(protected ImageUploadService $imageService) {}

    public function index(Request $request)
    {
        // Auto-discover and ensure any folder present in media table is in media_folders
        $distinctFolders = Media::select('folder')->distinct()->pluck('folder')->filter();
        foreach ($distinctFolders as $df) {
            MediaFolder::firstOrCreate(
                ['slug' => $df],
                ['name' => ucwords(str_replace(['-', '_'], ' ', $df)), 'is_system' => in_array($df, ['gallery', 'products', 'categories'])]
            );
        }

        $folders = MediaFolder::withCount('media')->orderBy('name')->get();

        $currentFolder = $request->input('folder', 'gallery');

        $query = Media::query();

        if ($currentFolder === 'gallery' || $currentFolder === 'root') {
            $query->where(function ($q) {
                $q->whereIn('folder', ['gallery', 'root', ''])->orWhereNull('folder');
            });
        } elseif ($currentFolder !== 'all') {
            $query->where('folder', $currentFolder);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('original_name', 'like', "%{$search}%")
                  ->orWhere('filename', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        $mediaItems = $query->latest()->paginate(24)->withQueryString();

        $totalCount = Media::count();
        $totalBytes = Media::sum('size');
        $totalSizeFormatted = $totalBytes >= 1048576 
            ? number_format($totalBytes / 1048576, 2) . ' MB' 
            : number_format($totalBytes / 1024, 1) . ' KB';

        return view('admin.gallery.index', compact(
            'mediaItems',
            'totalCount',
            'totalSizeFormatted',
            'folders',
            'currentFolder'
        ));
    }

    public function upload(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'files'   => 'nullable|array',
            'files.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,bmp,pdf,doc,docx,mp4|max:51200',
            'file'    => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,bmp,pdf,doc,docx,mp4|max:51200',
            'folder'  => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors'  => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $folder = $request->input('folder', 'gallery');
        if ($folder === 'all') {
            $folder = 'gallery';
        }

        // Ensure folder model exists
        MediaFolder::firstOrCreate(
            ['slug' => $folder],
            ['name' => ucwords(str_replace(['-', '_'], ' ', $folder)), 'is_system' => in_array($folder, ['gallery', 'products', 'categories'])]
        );

        $uploaded = [];

        if ($request->hasFile('file')) {
            $media = $this->imageService->upload($request->file('file'), $folder);
            $uploaded[] = $media;
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $media = $this->imageService->upload($file, $folder);
                $uploaded[] = $media;
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => count($uploaded) . ' image(s) converted to WebP and saved successfully.',
                'media'   => $uploaded,
            ]);
        }

        return back()->with('success', count($uploaded) . ' image(s) converted to .webp and added to folder [' . $folder . '].');
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $slug = Str::slug($request->name);
        if (empty($slug)) {
            $slug = 'folder-' . time();
        }

        if (MediaFolder::where('slug', $slug)->exists()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'A folder with this name already exists.'], 422);
            }
            return back()->with('error', 'A folder with this name already exists.');
        }

        $folder = MediaFolder::create([
            'name'      => trim($request->name),
            'slug'      => $slug,
            'is_system' => false,
        ]);

        Storage::disk('public')->makeDirectory($slug);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Folder created successfully.',
                'folder'  => $folder,
            ]);
        }

        return redirect()->route('admin.gallery.index', ['folder' => $slug])->with('success', "Folder '{$folder->name}' created successfully.");
    }

    public function destroyFolder(Request $request, MediaFolder $folder)
    {
        if ($folder->is_system) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'System folders (Products, Categories, General Gallery) cannot be deleted.'], 422);
            }
            return back()->with('error', 'System folders (Products, Categories, General Gallery) cannot be deleted.');
        }

        $fileCount = Media::where('folder', $folder->slug)->count();
        if ($fileCount > 0) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => "Cannot delete folder '{$folder->name}' because it contains {$fileCount} image(s). Please move or delete files first."], 422);
            }
            return back()->with('error', "Cannot delete folder '{$folder->name}' because it contains {$fileCount} image(s). Please move or delete files first.");
        }

        $folderName = $folder->name;
        $folder->delete();
        Storage::disk('public')->deleteDirectory($folder->slug);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Folder '{$folderName}' deleted."]);
        }

        return redirect()->route('admin.gallery.index')->with('success', "Folder '{$folderName}' deleted.");
    }

    public function renameFolder(Request $request, MediaFolder $folder)
    {
        if ($folder->is_system) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'System folders cannot be renamed.'], 422);
            }
            return back()->with('error', 'System folders cannot be renamed.');
        }

        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $newName = trim($request->name);
        $newSlug = Str::slug($newName);
        if (empty($newSlug)) {
            $newSlug = 'folder-' . time();
        }

        if (MediaFolder::where('slug', $newSlug)->where('id', '!=', $folder->id)->exists()) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'A folder with this name already exists.'], 422);
            }
            return back()->with('error', 'A folder with this name already exists.');
        }

        $oldSlug = $folder->slug;

        // Rename disk directory if it exists
        if (Storage::disk('public')->exists($oldSlug)) {
            Storage::disk('public')->move($oldSlug, $newSlug);
        } else {
            Storage::disk('public')->makeDirectory($newSlug);
        }

        // Update media records
        Media::where('folder', $oldSlug)->get()->each(function ($media) use ($oldSlug, $newSlug) {
            $media->folder = $newSlug;
            if (str_starts_with($media->path, $oldSlug . '/')) {
                $media->path = $newSlug . '/' . substr($media->path, strlen($oldSlug) + 1);
            }
            $media->save();
        });

        $folder->name = $newName;
        $folder->slug = $newSlug;
        $folder->save();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Folder renamed to '{$folder->name}'.",
                'folder'  => $folder,
            ]);
        }

        return redirect()->route('admin.gallery.index')->with('success', "Folder renamed to '{$folder->name}'.");
    }

    public function downloadFolder(Request $request, $folder, $filename = null)
    {
        if ($folder instanceof MediaFolder) {
            $folderModel = $folder;
        } elseif ($folder === 'all') {
            $folderModel = null;
        } elseif (is_numeric($folder)) {
            $folderModel = MediaFolder::find($folder);
        } else {
            $folderModel = MediaFolder::where('slug', $folder)->first();
        }

        if ($folder === 'all' || (!$folderModel && $folder === 'root')) {
            $items = Media::all();
            $folderName = 'All Gallery Assets';
            $defaultZipName = 'mst-all-media.zip';
        } else {
            if (!$folderModel) {
                return back()->with('error', 'Folder not found.');
            }

            if ($folderModel->slug === 'gallery' || $folderModel->slug === 'root') {
                $items = Media::where(function ($q) {
                    $q->whereIn('folder', ['gallery', 'root', ''])->orWhereNull('folder');
                })->get();
            } else {
                $items = Media::where('folder', $folderModel->slug)->get();
            }

            $folderName = $folderModel->name;
            $defaultZipName = Str::slug($folderModel->name) . '.zip';
        }

        if ($filename) {
            $cleanRequested = basename($filename);
            $zipFileName = str_ends_with(strtolower($cleanRequested), '.zip') ? $cleanRequested : ($cleanRequested . '.zip');
        } else {
            $zipFileName = $defaultZipName;
        }

        if ($items->isEmpty()) {
            return back()->with('error', "Folder '{$folderName}' contains no images to download.");
        }

        if (!class_exists('ZipArchive')) {
            return back()->with('error', 'ZipArchive extension not enabled on server.');
        }

        $tempDir = storage_path('app/temp');
        if (!file_exists($tempDir)) {
            @mkdir($tempDir, 0755, true);
        }
        foreach (glob($tempDir . '/*') as $oldTemp) {
            if (is_file($oldTemp) && filemtime($oldTemp) < time() - 600) {
                @unlink($oldTemp);
            }
        }
        $zipPath = $tempDir . '/' . time() . '_' . Str::random(4) . '_' . $zipFileName;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Could not create ZIP archive.');
        }

        $addedCount = 0;
        $usedNames = [];

        foreach ($items as $media) {
            $fullPath = Storage::disk('public')->path($media->path);
            if (file_exists($fullPath)) {
                $cleanBase = pathinfo($media->original_name, PATHINFO_FILENAME);
                $cleanBase = preg_replace('/\.(jpeg|jpg|png|webp|gif|svg|bmp|avif)$/i', '', $cleanBase);
                $base = Str::slug($cleanBase) ?: 'image';

                $origExt = strtolower(pathinfo($media->original_name, PATHINFO_EXTENSION) ?: 'webp');
                $targetExt = in_array($origExt, ['jpg', 'jpeg', 'jpe']) ? 'jpg' : ($origExt === 'png' ? 'png' : 'webp');

                $entryName = $base . '.' . $targetExt;
                $counter = 1;
                while (isset($usedNames[$entryName])) {
                    $entryName = $base . '_' . $counter . '.' . $targetExt;
                    $counter++;
                }
                $usedNames[$entryName] = true;

                if ($targetExt !== 'webp' && function_exists('imagecreatefromwebp')) {
                    $img = @imagecreatefromwebp($fullPath);
                    if ($img) {
                        $tempFile = $tempDir . '/zip_' . time() . '_' . Str::random(4) . '_' . $entryName;
                        if ($targetExt === 'png') {
                            imagealphablending($img, false);
                            imagesavealpha($img, true);
                            imagepng($img, $tempFile, 6);
                        } else {
                            imagejpeg($img, $tempFile, 95);
                        }
                        imagedestroy($img);
                        if (file_exists($tempFile)) {
                            $zip->addFile($tempFile, $entryName);
                            $addedCount++;
                            continue;
                        }
                    }
                }

                $zip->addFile($fullPath, $entryName);
                $addedCount++;
            }
        }

        $zip->close();

        if ($addedCount === 0) {
            if (file_exists($zipPath)) {
                @unlink($zipPath);
            }
            return back()->with('error', "No valid image files found on disk for '{$folderName}'.");
        }

        $response = response()->download($zipPath, $zipFileName, [
            'Content-Type'              => 'application/zip',
            'Content-Transfer-Encoding' => 'binary',
            'Cache-Control'             => 'private, no-transform, no-cache, must-revalidate',
            'Pragma'                    => 'no-cache',
            'Expires'                   => '0',
        ]);
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $zipFileName . '"; filename*=UTF-8\'\'' . rawurlencode($zipFileName));
        return $response;
    }

    public function moveFolderFiles(Request $request, MediaFolder $folder)
    {
        $request->validate([
            'target_folder' => 'required|string|max:50',
        ]);

        $targetFolderSlug = $request->target_folder;
        $targetFolder = MediaFolder::where('slug', $targetFolderSlug)->first();

        if (!$targetFolder) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Target folder does not exist.'], 404);
            }
            return back()->with('error', 'Target folder does not exist.');
        }

        if ($folder->slug === $targetFolder->slug) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Source and destination folder are the same.'], 422);
            }
            return back()->with('error', 'Source and destination folder are the same.');
        }

        $items = Media::where('folder', $folder->slug)->get();
        $movedCount = 0;

        Storage::disk('public')->makeDirectory($targetFolder->slug);

        foreach ($items as $media) {
            $oldPath = $media->path;
            $newFilename = $media->filename;
            $newPath = $targetFolder->slug . '/' . $newFilename;

            if (Storage::disk('public')->exists($oldPath)) {
                if (Storage::disk('public')->exists($newPath)) {
                    $newFilename = time() . '_' . Str::random(4) . '_' . $media->filename;
                    $newPath = $targetFolder->slug . '/' . $newFilename;
                    $media->filename = $newFilename;
                }
                Storage::disk('public')->move($oldPath, $newPath);
            }

            $media->folder = $targetFolder->slug;
            $media->path = $newPath;
            $media->save();
            $movedCount++;
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$movedCount} item(s) moved to '{$targetFolder->name}'.",
                'target_folder' => $targetFolder->slug,
            ]);
        }

        return redirect()->route('admin.gallery.index', ['folder' => $targetFolder->slug])
            ->with('success', "{$movedCount} item(s) from '{$folder->name}' moved to folder [{$targetFolder->name}].");
    }

    public function move(Request $request, Media $media)
    {
        $request->validate([
            'target_folder' => 'required|string|max:50',
        ]);

        $targetFolderSlug = $request->target_folder;
        $targetFolder = MediaFolder::where('slug', $targetFolderSlug)->first();

        if (!$targetFolder) {
            return back()->with('error', 'Target folder does not exist.');
        }

        if ($media->folder === $targetFolder->slug) {
            return back()->with('info', 'File is already in this folder.');
        }

        $oldRelativePath = $media->path;
        $newRelativePath = $targetFolder->slug . '/' . $media->filename;

        // Move file in public disk if it exists
        if (Storage::disk('public')->exists($oldRelativePath)) {
            Storage::disk('public')->makeDirectory($targetFolder->slug);

            // If a file with same name already exists in target folder, rename slightly
            if (Storage::disk('public')->exists($newRelativePath)) {
                $newFilename = time() . '_' . Str::random(4) . '_' . $media->filename;
                $newRelativePath = $targetFolder->slug . '/' . $newFilename;
                $media->filename = $newFilename;
            }

            Storage::disk('public')->move($oldRelativePath, $newRelativePath);
        }

        $media->folder = $targetFolder->slug;
        $media->path = $newRelativePath;
        $media->save();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Image moved to folder [{$targetFolder->name}].",
                'media'   => $media,
                'target_folder' => $targetFolder->slug,
            ]);
        }

        return redirect()->route('admin.gallery.index', ['folder' => $targetFolder->slug])
            ->with('success', "Image '{$media->original_name}' moved to folder [{$targetFolder->name}].");
    }

    public function bulkMove(Request $request)
    {
        $request->validate([
            'media_ids'     => 'required|array',
            'media_ids.*'   => 'exists:media,id',
            'target_folder' => 'required|string|max:50',
        ]);

        $targetFolder = MediaFolder::where('slug', $request->target_folder)->firstOrFail();
        $items = Media::whereIn('id', $request->media_ids)->get();

        $movedCount = 0;
        Storage::disk('public')->makeDirectory($targetFolder->slug);

        foreach ($items as $media) {
            if ($media->folder === $targetFolder->slug) {
                continue;
            }

            $oldRelativePath = $media->path;
            $newRelativePath = $targetFolder->slug . '/' . $media->filename;

            if (Storage::disk('public')->exists($oldRelativePath)) {
                if (Storage::disk('public')->exists($newRelativePath)) {
                    $newFilename = time() . '_' . Str::random(4) . '_' . $media->filename;
                    $newRelativePath = $targetFolder->slug . '/' . $newFilename;
                    $media->filename = $newFilename;
                }
                Storage::disk('public')->move($oldRelativePath, $newRelativePath);
            }

            $media->folder = $targetFolder->slug;
            $media->path = $newRelativePath;
            $media->save();
            $movedCount++;
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$movedCount} item(s) moved to {$targetFolder->name}.",
                'target_folder' => $targetFolder->slug,
            ]);
        }

        return redirect()->route('admin.gallery.index', ['folder' => $targetFolder->slug])
            ->with('success', "{$movedCount} item(s) moved to folder [{$targetFolder->name}].");
    }

    public function copy(Request $request, Media $media)
    {
        $targetFolderSlug = $request->input('target_folder', $media->folder);
        $targetFolder = MediaFolder::where('slug', $targetFolderSlug)->first();

        if (!$targetFolder) {
            $targetFolder = MediaFolder::where('slug', $media->folder)->first() ?? MediaFolder::first();
        }

        $baseName = pathinfo($media->filename, PATHINFO_FILENAME);
        $ext = pathinfo($media->filename, PATHINFO_EXTENSION) ?: 'webp';
        $newFilename = time() . '_' . Str::random(6) . '_copy_' . Str::slug($baseName) . '.' . $ext;
        $newRelativePath = $targetFolder->slug . '/' . $newFilename;

        Storage::disk('public')->makeDirectory($targetFolder->slug);

        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->copy($media->path, $newRelativePath);
        }

        $newMedia = Media::create([
            'filename'      => $newFilename,
            'original_name' => 'Copy of ' . $media->original_name,
            'path'          => $newRelativePath,
            'mime_type'     => $media->mime_type ?? 'image/webp',
            'size'          => $media->size,
            'width'         => $media->width,
            'height'        => $media->height,
            'folder'        => $targetFolder->slug,
            'alt_text'      => $media->alt_text,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Image duplicated successfully.',
                'media'   => $newMedia,
            ]);
        }

        return back()->with('success', "Copy created in folder [{$targetFolder->name}].");
    }

    public function bulkCopy(Request $request)
    {
        $request->validate([
            'media_ids'     => 'required|array',
            'media_ids.*'   => 'exists:media,id',
            'target_folder' => 'nullable|string|max:50',
        ]);

        $targetFolderSlug = $request->input('target_folder');
        $items = Media::whereIn('id', $request->media_ids)->get();
        $copiedCount = 0;

        foreach ($items as $media) {
            $destFolderSlug = $targetFolderSlug ?: $media->folder;
            $targetFolder = MediaFolder::where('slug', $destFolderSlug)->first();
            if (!$targetFolder) {
                continue;
            }

            $baseName = pathinfo($media->filename, PATHINFO_FILENAME);
            $ext = pathinfo($media->filename, PATHINFO_EXTENSION) ?: 'webp';
            $newFilename = time() . '_' . Str::random(6) . '_copy_' . Str::slug($baseName) . '.' . $ext;
            $newRelativePath = $targetFolder->slug . '/' . $newFilename;

            Storage::disk('public')->makeDirectory($targetFolder->slug);

            if (Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->copy($media->path, $newRelativePath);
            }

            Media::create([
                'filename'      => $newFilename,
                'original_name' => 'Copy of ' . $media->original_name,
                'path'          => $newRelativePath,
                'mime_type'     => $media->mime_type ?? 'image/webp',
                'size'          => $media->size,
                'width'         => $media->width,
                'height'        => $media->height,
                'folder'        => $targetFolder->slug,
                'alt_text'      => $media->alt_text,
            ]);
            $copiedCount++;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$copiedCount} item(s) copied successfully.",
            ]);
        }

        return back()->with('success', "{$copiedCount} item(s) copied.");
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'media_ids'   => 'required|array',
            'media_ids.*' => 'exists:media,id',
        ]);

        $items = Media::whereIn('id', $request->media_ids)->get();
        $count = $items->count();

        foreach ($items as $media) {
            $media->delete(); // triggers model event to delete file from disk
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} item(s) deleted.",
            ]);
        }

        return back()->with('success', "{$count} item(s) deleted.");
    }

    public function api(Request $request)
    {
        $folder = $request->input('folder', 'root');
        $search = $request->input('search');

        $query = Media::latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('original_name', 'like', "%{$search}%")
                  ->orWhere('filename', 'like', "%{$search}%");
            });
        }

        if ($folder === 'root' || $folder === 'gallery') {
            $query->where(function ($q) {
                $q->whereIn('folder', ['gallery', 'root', ''])->orWhereNull('folder');
            });
        } elseif ($folder !== 'all') {
            $query->where('folder', $folder);
        }

        $items = $query->paginate(36);

        $allFolders = MediaFolder::withCount('media')->orderBy('name')->get();
        // At root or 'all' or 'gallery', display all folder cards! When inside a specific folder, folders can be empty (or subfolders)
        $folders = ($folder === 'root' || $folder === 'all' || $folder === 'gallery') ? $allFolders : collect([]);

        $currentPath = ($folder === 'root' || $folder === 'all') ? '/uploads/files' : '/uploads/' . $folder;

        return response()->json([
            'data'           => $items->items(),
            'current_page'   => $items->currentPage(),
            'last_page'      => $items->lastPage(),
            'total'          => $items->total(),
            'folders'        => $folders,
            'all_folders'    => $allFolders,
            'current_folder' => $folder,
            'current_path'   => $currentPath,
        ]);
    }

    public function destroy(Request $request, Media $media)
    {
        $name = $media->original_name;
        $media->delete();

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => "Image '{$name}' deleted successfully."]);
        }

        return back()->with('success', "Image '{$name}' deleted successfully.");
    }

    public function rename(Request $request, Media $media)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $cleanName = trim($request->name);
        $media->original_name = $cleanName;
        $media->alt_text = pathinfo($cleanName, PATHINFO_FILENAME);
        $media->save();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Image renamed successfully.',
                'media'   => $media,
            ]);
        }

        return back()->with('success', 'Image renamed successfully.');
    }

    public function download(Request $request, Media $media, $filename = null)
    {
        $fullPath = Storage::disk('public')->path($media->path);
        if (!file_exists($fullPath)) {
            abort(404, 'Image file not found on disk.');
        }

        $cleanBaseName = pathinfo($media->original_name, PATHINFO_FILENAME);
        $cleanBaseName = preg_replace('/\.(jpeg|jpg|png|webp|gif|svg|bmp|avif)$/i', '', $cleanBaseName);
        $cleanSlug = Str::slug($cleanBaseName) ?: 'image';

        // Detect target extension
        $origExt = strtolower(pathinfo($media->original_name, PATHINFO_EXTENSION) ?: 'webp');
        if ($filename && preg_match('/\.(png|jpe?g|webp|gif)$/i', $filename, $m)) {
            $targetExt = strtolower($m[1]) === 'jpeg' ? 'jpg' : strtolower($m[1]);
        } else {
            $targetExt = in_array($origExt, ['jpg', 'jpeg', 'jpe']) ? 'jpg' : ($origExt === 'png' ? 'png' : 'webp');
        }

        $downloadName = $cleanSlug . '.' . $targetExt;
        $servePath = $fullPath;

        // Convert WebP on the fly to JPG or PNG if requested target is JPG or PNG
        if ($targetExt !== 'webp' && function_exists('imagecreatefromwebp')) {
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                @mkdir($tempDir, 0755, true);
            }
            foreach (glob($tempDir . '/*') as $oldTemp) {
                if (is_file($oldTemp) && filemtime($oldTemp) < time() - 600) {
                    @unlink($oldTemp);
                }
            }

            $img = @imagecreatefromwebp($fullPath);
            if ($img) {
                $tempFile = $tempDir . '/' . time() . '_' . Str::random(4) . '_' . $downloadName;
                if ($targetExt === 'png') {
                    imagealphablending($img, false);
                    imagesavealpha($img, true);
                    imagepng($img, $tempFile, 6);
                } elseif ($targetExt === 'jpg') {
                    imagejpeg($img, $tempFile, 95);
                }
                imagedestroy($img);
                if (file_exists($tempFile)) {
                    $servePath = $tempFile;
                }
            }
        }

        $mime = match ($targetExt) {
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            default => 'image/webp',
        };

        $response = response()->download($servePath, $downloadName, [
            'Content-Type'              => $mime,
            'Content-Transfer-Encoding' => 'binary',
            'Cache-Control'             => 'private, no-transform, no-cache, must-revalidate',
            'Pragma'                    => 'no-cache',
            'Expires'                   => '0',
        ]);
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $downloadName . '"; filename*=UTF-8\'\'' . rawurlencode($downloadName));
        return $response;
    }

    public function crop(Request $request, Media $media)
    {
        $request->validate([
            'x'          => 'nullable|numeric',
            'y'          => 'nullable|numeric',
            'width'      => 'nullable|numeric',
            'height'     => 'nullable|numeric',
            'image_data' => 'nullable|string',
        ]);

        $fullPath = Storage::disk('public')->path($media->path);
        if (!file_exists($fullPath)) {
            return response()->json(['success' => false, 'message' => 'File not found on disk.'], 404);
        }

        $cropped = false;

        // Option A: base64 canvas data URL supplied from frontend cropper
        if ($request->filled('image_data') && str_starts_with($request->image_data, 'data:image')) {
            $data = $request->image_data;
            $data = substr($data, strpos($data, ',') + 1);
            $binary = base64_decode($data);
            if ($binary !== false) {
                if (function_exists('imagewebp') && function_exists('imagecreatefromstring')) {
                    $res = @imagecreatefromstring($binary);
                    if ($res) {
                        if (function_exists('imagepalettetotruecolor') && !imageistruecolor($res)) {
                            imagepalettetotruecolor($res);
                        }
                        imagealphablending($res, false);
                        imagesavealpha($res, true);
                        $w = imagesx($res);
                        $h = imagesy($res);
                        @imagewebp($res, $fullPath, 90);
                        imagedestroy($res);
                        $media->width = $w;
                        $media->height = $h;
                        $cropped = true;
                    }
                }

                if (!$cropped) {
                    file_put_contents($fullPath, $binary);
                    $info = @getimagesize($fullPath);
                    if ($info) {
                        $media->width = $info[0];
                        $media->height = $info[1];
                    }
                    $cropped = true;
                }
            }
        }

        // Option B: crop coordinates x, y, width, height supplied
        if (!$cropped && $request->filled(['x', 'y', 'width', 'height'])) {
            $x = (int) round($request->x);
            $y = (int) round($request->y);
            $w = (int) round($request->width);
            $h = (int) round($request->height);

            $imgInfo = @getimagesize($fullPath);
            if ($imgInfo) {
                $src = null;
                switch ($imgInfo[2]) {
                    case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($fullPath); break;
                    case IMAGETYPE_PNG:  $src = @imagecreatefrompng($fullPath); break;
                    case IMAGETYPE_WEBP: $src = @imagecreatefromwebp($fullPath); break;
                    case IMAGETYPE_GIF:  $src = @imagecreatefromgif($fullPath); break;
                    default:
                        if (function_exists('imagecreatefromstring')) {
                            $src = @imagecreatefromstring(file_get_contents($fullPath));
                        }
                }

                if ($src) {
                    $origW = imagesx($src);
                    $origH = imagesy($src);
                    $x = max(0, min($x, $origW - 1));
                    $y = max(0, min($y, $origH - 1));
                    $w = max(1, min($w, $origW - $x));
                    $h = max(1, min($h, $origH - $y));

                    $croppedResource = function_exists('imagecrop') ? imagecrop($src, ['x' => $x, 'y' => $y, 'width' => $w, 'height' => $h]) : null;
                    if ($croppedResource) {
                        if (function_exists('imagepalettetotruecolor') && !imageistruecolor($croppedResource)) {
                            imagepalettetotruecolor($croppedResource);
                        }
                        imagealphablending($croppedResource, false);
                        imagesavealpha($croppedResource, true);

                        @imagewebp($croppedResource, $fullPath, 90);
                        $media->width = $w;
                        $media->height = $h;
                        imagedestroy($croppedResource);
                        $cropped = true;
                    }
                    imagedestroy($src);
                }
            }
        }

        if ($cropped) {
            $media->size = filesize($fullPath);
            $media->mime_type = 'image/webp';
            $media->touch();
            $media->save();

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image cropped and saved successfully.',
                    'media'   => $media->fresh(),
                ]);
            }

            return back()->with('success', 'Image cropped successfully.');
        }

        return response()->json(['success' => false, 'message' => 'Failed to crop image.'], 422);
    }
}
