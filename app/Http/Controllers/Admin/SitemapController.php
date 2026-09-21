<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SitemapController as PublicSitemapController;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SitemapController extends Controller
{
    /**
     * Show sitemap management dashboard.
     */
    public function index()
    {
        $mode = Setting::get('sitemap_mode', 'dynamic');
        $customFilePath = storage_path('app/sitemaps/sitemap_custom.xml');
        $hasCustomFile = file_exists($customFilePath);

        $customFileInfo = null;
        if ($hasCustomFile) {
            $fileSize = filesize($customFilePath);
            $lastModified = filemtime($customFilePath);
            $customUrlCount = 0;
            try {
                libxml_use_internal_errors(true);
                $xml = simplexml_load_file($customFilePath);
                if ($xml !== false) {
                    $customUrlCount = count($xml->url ?? $xml->sitemap ?? []);
                }
            } catch (\Throwable $e) {}

            $customFileInfo = [
                'size'          => $fileSize,
                'size_human'    => $this->formatBytes($fileSize),
                'modified_at'   => date('Y-m-d H:i:s', $lastModified),
                'url_count'     => $customUrlCount,
            ];
        }

        $dynamicStats = PublicSitemapController::getStats();
        $lastGenerated = Setting::get('sitemap_last_generated');

        return view('admin.sitemap.index', [
            'mode'           => $mode,
            'hasCustomFile'  => $hasCustomFile,
            'customFileInfo' => $customFileInfo,
            'dynamicStats'   => $dynamicStats,
            'lastGenerated'  => $lastGenerated,
            'sitemapUrl'     => url('/sitemap.xml'),
        ]);
    }

    /**
     * Trigger dynamic generation and refresh.
     */
    public function generate()
    {
        try {
            $xml = PublicSitemapController::generateDynamicXml();

            // Save static copy to public/sitemap.xml as well for ultra-fast static serving
            File::put(public_path('sitemap.xml'), $xml);

            $stats = PublicSitemapController::getStats();
            Setting::set('sitemap_last_generated', now()->toDateTimeString());
            Setting::set('sitemap_mode', 'dynamic');

            return redirect()->route('admin.sitemap.index')->with('success', "Dynamic sitemap.xml generated successfully! Total {$stats['total']} URLs indexed across 3 languages.");
        } catch (\Throwable $e) {
            return redirect()->route('admin.sitemap.index')->with('error', 'Failed to generate sitemap: ' . $e->getMessage());
        }
    }

    /**
     * Upload custom sitemap.xml file.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'sitemap_file' => 'required|file|max:10240', // max 10MB
        ]);

        $file = $request->file('sitemap_file');
        $content = file_get_contents($file->getRealPath());

        // Validate XML syntax
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);
        if ($xml === false) {
            $errors = [];
            foreach (libxml_get_errors() as $error) {
                $errors[] = trim($error->message);
            }
            libxml_clear_errors();
            $errorMsg = !empty($errors) ? implode('; ', array_slice($errors, 0, 3)) : 'Malformed XML syntax.';
            return redirect()->route('admin.sitemap.index')->with('error', "Uploaded file is not a valid XML file: {$errorMsg}");
        }

        // Count URLs or sitemaps
        $urlCount = count($xml->url ?? $xml->sitemap ?? []);

        // Save to sitemaps directory
        $destinationDir = storage_path('app/sitemaps');
        if (!File::isDirectory($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true, true);
        }

        $targetPath = $destinationDir . '/sitemap_custom.xml';
        File::put($targetPath, $content);

        // Also copy to public/sitemap.xml so it's active immediately
        File::put(public_path('sitemap.xml'), $content);

        Setting::set('sitemap_mode', 'custom');
        Setting::set('sitemap_last_generated', now()->toDateTimeString());

        return redirect()->route('admin.sitemap.index')->with('success', "Custom sitemap.xml uploaded successfully! {$urlCount} URLs detected. Mode switched to Custom Uploaded.");
    }

    /**
     * Set active sitemap mode (dynamic vs custom).
     */
    public function setMode(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:dynamic,custom',
        ]);

        $mode = $request->mode;
        $customFilePath = storage_path('app/sitemaps/sitemap_custom.xml');

        if ($mode === 'custom' && !file_exists($customFilePath)) {
            return redirect()->route('admin.sitemap.index')->with('error', 'No custom sitemap.xml has been uploaded yet. Please upload a file first.');
        }

        Setting::set('sitemap_mode', $mode);

        if ($mode === 'dynamic') {
            // Re-generate public/sitemap.xml
            $xml = PublicSitemapController::generateDynamicXml();
            File::put(public_path('sitemap.xml'), $xml);
            return redirect()->route('admin.sitemap.index')->with('success', 'Sitemap switched to Dynamic Auto-Generated mode.');
        } else {
            // Copy custom file to public/sitemap.xml
            File::copy($customFilePath, public_path('sitemap.xml'));
            return redirect()->route('admin.sitemap.index')->with('success', 'Sitemap switched to Custom Uploaded mode.');
        }
    }

    /**
     * Download the currently active sitemap.xml.
     */
    public function download()
    {
        $mode = Setting::get('sitemap_mode', 'dynamic');
        $customFilePath = storage_path('app/sitemaps/sitemap_custom.xml');

        if ($mode === 'custom' && file_exists($customFilePath)) {
            return response()->download($customFilePath, 'sitemap.xml', [
                'Content-Type' => 'application/xml',
            ]);
        }

        $xml = PublicSitemapController::generateDynamicXml();
        return response()->streamDownload(function () use ($xml) {
            echo $xml;
        }, 'sitemap.xml', [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Delete custom sitemap and revert to dynamic mode.
     */
    public function deleteCustom()
    {
        $customFilePath = storage_path('app/sitemaps/sitemap_custom.xml');
        if (file_exists($customFilePath)) {
            File::delete($customFilePath);
        }

        Setting::set('sitemap_mode', 'dynamic');

        // Regenerate dynamic public/sitemap.xml
        $xml = PublicSitemapController::generateDynamicXml();
        File::put(public_path('sitemap.xml'), $xml);

        return redirect()->route('admin.sitemap.index')->with('success', 'Custom sitemap deleted. Mode reverted to Dynamic Auto-Generated.');
    }

    /**
     * Helper to format bytes to readable string.
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
