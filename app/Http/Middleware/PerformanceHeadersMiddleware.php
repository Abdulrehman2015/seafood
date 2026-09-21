<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PerformanceHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Do not alter streaming or binary file downloads
        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }

        $contentType = $response->headers->get('Content-Type', '');

        // 1. Static/Media asset routes served by PHP (e.g., map tiles)
        // 1. Static/Media asset routes served by PHP (e.g., map tiles, CSS, fonts, icons)
        if (str_starts_with($contentType, 'image/') || str_contains($contentType, 'font/') || str_contains($contentType, 'text/css')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
            $response->headers->remove('Set-Cookie');
        }

        // 2. Dynamic GZIP Compression for HTML, JSON, JS, CSS, SVG, XML
        $rawEncoding = strtolower($request->header('Accept-Encoding', $_SERVER['HTTP_ACCEPT_ENCODING'] ?? ''));
        $supportsGzip = ($rawEncoding === '' || str_contains($rawEncoding, 'gzip') || str_contains($rawEncoding, '*'))
            && !str_contains($rawEncoding, 'identity');
        $zlibActive = filter_var(ini_get('zlib.output_compression'), FILTER_VALIDATE_BOOLEAN);

        if (
            function_exists('gzencode')
            && !in_array('ob_gzhandler', ob_list_handlers())
            && !$zlibActive
            && $supportsGzip
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
                        $response->headers->remove('Content-Length');
                    }
                }
            }
        }

        return $response;
    }
}
