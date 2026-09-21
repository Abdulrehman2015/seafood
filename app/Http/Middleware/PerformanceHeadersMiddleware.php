<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * PerformanceHeadersMiddleware
 *
 * Applies GZIP compression and aggressive caching to all PHP-delivered responses.
 * Designed to work on shared hosts (InfinityFree, ByetHost) where Nginx sits in
 * front of Apache and may strip Accept-Encoding from crawler/bot requests.
 */
class PerformanceHeadersMiddleware
{
    /** Static file extensions that should never have session cookies */
    private const STATIC_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
                                        'ico', 'woff', 'woff2', 'ttf', 'otf', 'eot',
                                        'css', 'js', 'map'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $contentType = $response->headers->get('Content-Type', '');
        $uri         = $request->getRequestUri();
        $ext         = strtolower(pathinfo(parse_url($uri, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));

        // ── 1. STATIC ASSET CACHING + COOKIE STRIP ───────────────────────────
        $isStaticContentType = str_starts_with($contentType, 'image/')
            || str_contains($contentType, 'font/')
            || str_contains($contentType, 'text/css')
            || str_contains($contentType, 'application/javascript')
            || str_contains($contentType, 'text/javascript')
            || str_contains($contentType, 'image/x-icon')
            || str_contains($contentType, 'image/vnd.microsoft.icon');

        $isStaticByExtension = in_array($ext, self::STATIC_EXTENSIONS, true);

        if ($isStaticContentType || $isStaticByExtension) {
            // Cache for 1 year (immutable)
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

            // CRITICAL: Strip ALL cookies from static assets (Pingdom: Use cookie-free domains)
            $response->headers->remove('Set-Cookie');
            $response->headers->remove('Cookie');

            // Add Vary for proper CDN caching
            $response->headers->set('Vary', 'Accept-Encoding');
        }

        // ── 2. FAVICON CACHING ────────────────────────────────────────────────
        if (str_contains($uri, 'favicon')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
            $response->headers->remove('Set-Cookie');
            $response->headers->remove('Cookie');
        }

        // Do not alter streaming or binary file downloads for dynamic GZIP compression
        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }

        // ── 3. DYNAMIC GZIP COMPRESSION ──────────────────────────────────────
        // On InfinityFree / shared hosts, the Nginx proxy may strip Accept-Encoding
        // from bot/crawler requests. We apply GZIP UNLESS the client explicitly
        // requests uncompressed content (Accept-Encoding: identity).
        $rawEncoding = strtolower(
            $request->header('Accept-Encoding')
            ?? ($_SERVER['HTTP_ACCEPT_ENCODING'] ?? '')
        );

        // Apply GZIP: default ON unless client says identity only
        $clientWantsRaw = $rawEncoding !== ''
            && str_contains($rawEncoding, 'identity')
            && !str_contains($rawEncoding, 'gzip')
            && !str_contains($rawEncoding, '*');

        if (
            !$clientWantsRaw
            && function_exists('gzencode')
            && !in_array('ob_gzhandler', ob_list_handlers())
            && !$response->headers->has('Content-Encoding')
        ) {
            $isCompressible = str_contains($contentType, 'text/html')
                || str_contains($contentType, 'application/json')
                || str_contains($contentType, 'text/plain')
                || str_contains($contentType, 'text/css')
                || str_contains($contentType, 'application/javascript')
                || str_contains($contentType, 'text/javascript')
                || str_contains($contentType, 'image/svg+xml')
                || str_contains($contentType, 'application/xml');

            if ($isCompressible) {
                $content = $response->getContent();
                if ($content !== false && strlen($content) > 256) {
                    $compressed = gzencode($content, 6);
                    if ($compressed !== false && strlen($compressed) < strlen($content)) {
                        $response->setContent($compressed);
                        $response->headers->set('Content-Encoding', 'gzip');
                        $response->headers->set('Vary', 'Accept-Encoding');
                        $response->headers->set('Content-Length', (string) strlen($compressed));
                    }
                }
            }
        }

        return $response;
    }
}
