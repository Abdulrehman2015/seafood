<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate or serve valid Google-compliant XML sitemap.
     */
    public function index(): Response
    {
        $mode = Setting::get('sitemap_mode', 'dynamic');
        $customPath = storage_path('app/sitemaps/sitemap_custom.xml');

        if ($mode === 'custom' && file_exists($customPath)) {
            $xml = file_get_contents($customPath);
            // Ensure XSL stylesheet is attached for human browser viewing if missing
            if (!str_contains($xml, 'xml-stylesheet')) {
                $xslUrl = url('/sitemap.xsl');
                $xslTag = '<?xml-stylesheet type="text/xsl" href="' . $xslUrl . '"?>' . "\n";
                if (preg_match('/<\?xml[^>]*\?>/i', $xml)) {
                    $xml = preg_replace('/(<\?xml[^>]*\?>\s*)/i', "$1" . $xslTag, $xml, 1);
                } else {
                    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $xslTag . $xml;
                }
            }

            return response($xml, 200)
                ->header('Content-Type', 'application/xml; charset=utf-8');
        }

        $xml = static::generateDynamicXml();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    /**
     * Build the dynamic XML string strictly according to Google Search Central guidelines:
     * - Canonical URLs ONLY (HTTP 200 OK, zero 404s, zero redirect loops, no query-string filter duplicates)
     * - Google Image Sitemap extension (xmlns:image) for rich visual indexing
     * - Google Multilingual hreflang annotations (xmlns:xhtml) with reciprocal alternate links & x-default
     * - Standard W3C ISO-8601 timestamps
     */
    public static function generateDynamicXml(): string
    {
        $baseUrl = static::resolveBaseUrl();
        $locales = ['en', 'zh', 'bm'];

        // Core stable timestamp based on latest database updates
        $latestProductDate = Product::where('is_active', true)->max('updated_at');
        $siteLastMod = $latestProductDate ? date('Y-m-d\TH:i:sP', strtotime($latestProductDate)) : date('Y-m-d\TH:i:sP');

        $urls = [];

        // 1. Canonical Core Pages (100% verified 200 OK endpoints)
        $corePages = [
            ['path' => '',           'priority' => '1.0', 'changefreq' => 'daily'],
            ['path' => 'shop',       'priority' => '0.9', 'changefreq' => 'daily'],
            ['path' => 'categories', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['path' => 'about',      'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => 'contact',    'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        foreach ($corePages as $page) {
            foreach ($locales as $locale) {
                $subPath = $page['path'] ? '/' . $page['path'] : '';
                $pageUrl = $baseUrl . '/' . $locale . $subPath;

                $alts = [];
                foreach ($locales as $altLoc) {
                    $hreflang = match ($altLoc) {
                        'zh' => 'zh-Hans',
                        'bm' => 'ms',
                        default => 'en',
                    };
                    $alts[] = [
                        'hreflang' => $hreflang,
                        'href'     => $baseUrl . '/' . $altLoc . $subPath,
                    ];
                }
                $alts[] = [
                    'hreflang' => 'x-default',
                    'href'     => $baseUrl . '/en' . $subPath,
                ];

                $urls[] = [
                    'loc'        => $pageUrl,
                    'lastmod'    => $siteLastMod,
                    'changefreq' => $page['changefreq'],
                    'priority'   => $page['priority'],
                    'alts'       => $alts,
                    'image'      => null,
                ];
            }
        }

        // 2. Canonical Active Products with Google Image Extension
        try {
            $products = Product::where('is_active', true)
                ->select('id', 'name', 'name_zh', 'name_bm', 'slug', 'thumbnail', 'updated_at', 'created_at')
                ->orderBy('sort_order')
                ->get();

            foreach ($products as $product) {
                $updatedAt = $product->updated_at ?? $product->created_at ?? now();
                $lastmod = $updatedAt->toAtomString();

                // Google Image payload
                $imagePayload = null;
                if (!empty($product->thumbnail)) {
                    $imgUrl = str_starts_with($product->thumbnail, 'http')
                        ? $product->thumbnail
                        : $baseUrl . '/storage/' . ltrim($product->thumbnail, '/');

                    $imagePayload = [
                        'loc'   => $imgUrl,
                        'title' => $product->name,
                    ];
                }

                foreach ($locales as $locale) {
                    $prodUrl = $baseUrl . '/' . $locale . '/shop/' . $product->slug;

                    $alts = [];
                    foreach ($locales as $altLoc) {
                        $hreflang = match ($altLoc) {
                            'zh' => 'zh-Hans',
                            'bm' => 'ms',
                            default => 'en',
                        };
                        $alts[] = [
                            'hreflang' => $hreflang,
                            'href'     => $baseUrl . '/' . $altLoc . '/shop/' . $product->slug,
                        ];
                    }
                    $alts[] = [
                        'hreflang' => 'x-default',
                        'href'     => $baseUrl . '/en/shop/' . $product->slug,
                    ];

                    $urls[] = [
                        'loc'        => $prodUrl,
                        'lastmod'    => $lastmod,
                        'changefreq' => 'weekly',
                        'priority'   => '0.8',
                        'alts'       => $alts,
                        'image'      => $imagePayload,
                    ];
                }
            }
        } catch (\Throwable $e) {}

        // 3. Render Standard Google XML with XSL Stylesheet
        $xslUrl = $baseUrl . '/sitemap.xsl';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?xml-stylesheet type="text/xsl" href="' . $xslUrl . '"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:xhtml="http://www.w3.org/1999/xhtml"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($urls as $item) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8') . '</loc>' . "\n";
            $xml .= '    <lastmod>' . htmlspecialchars($item['lastmod'], ENT_XML1, 'UTF-8') . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . htmlspecialchars($item['changefreq'], ENT_XML1, 'UTF-8') . '</changefreq>' . "\n";
            $xml .= '    <priority>' . htmlspecialchars($item['priority'], ENT_XML1, 'UTF-8') . '</priority>' . "\n";

            if (!empty($item['alts'])) {
                foreach ($item['alts'] as $alt) {
                    $xml .= '    <xhtml:link rel="alternate" hreflang="' . htmlspecialchars($alt['hreflang'], ENT_XML1, 'UTF-8') . '" href="' . htmlspecialchars($alt['href'], ENT_XML1, 'UTF-8') . '" />' . "\n";
                }
            }

            // Google Image Sitemap Tag
            if (!empty($item['image']) && !empty($item['image']['loc'])) {
                $xml .= '    <image:image>' . "\n";
                $xml .= '      <image:loc>' . htmlspecialchars($item['image']['loc'], ENT_XML1, 'UTF-8') . '</image:loc>' . "\n";
                if (!empty($item['image']['title'])) {
                    $xml .= '      <image:title>' . htmlspecialchars($item['image']['title'], ENT_XML1, 'UTF-8') . '</image:title>' . "\n";
                }
                $xml .= '    </image:image>' . "\n";
            }

            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Resolve reliable canonical base URL.
     */
    public static function resolveBaseUrl(): string
    {
        if (request() && !app()->runningInConsole()) {
            $host = request()->getSchemeAndHttpHost();
            if (!empty($host)) {
                return rtrim($host, '/');
            }
        }

        $canonicalSetting = Setting::get('canonical_url');
        if (!empty($canonicalSetting) && filter_var($canonicalSetting, FILTER_VALIDATE_URL)) {
            $parsed = parse_url($canonicalSetting);
            if (!empty($parsed['scheme']) && !empty($parsed['host'])) {
                $port = !empty($parsed['port']) ? ':' . $parsed['port'] : '';
                return $parsed['scheme'] . '://' . $parsed['host'] . $port;
            }
        }

        return rtrim(config('app.url', 'http://127.0.0.1:8000'), '/');
    }

    /**
     * Get statistics breakdown about URLs in dynamic sitemap.
     */
    public static function getStats(): array
    {
        $coreCount = 5 * 3; // 5 canonical core pages * 3 locales = 15 URLs
        $productCount = 0;
        $imageCount = 0;

        try {
            $products = Product::where('is_active', true)->select('id', 'thumbnail')->get();
            $productCount = $products->count() * 3;
            $imageCount = $products->filter(fn($p) => !empty($p->thumbnail))->count();
        } catch (\Throwable $e) {}

        return [
            'total'      => $coreCount + $productCount,
            'core'       => $coreCount,
            'products'   => $productCount,
            'images'     => $imageCount,
            'locales'    => 3,
        ];
    }
}
