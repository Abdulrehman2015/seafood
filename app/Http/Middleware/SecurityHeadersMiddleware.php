<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     * Adds all 9 security headers required by modern security scanners.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isHttps = $request->isSecure()
            || $request->header('X-Forwarded-Proto') === 'https'
            || app()->environment('production');

        // ── 1. Content-Security-Policy ─────────────────────────────────────────
        // Covers all known resources used by this app.
        $csp = implode(' ', [
            "default-src 'self';",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
                "https://www.google.com",
                "https://www.gstatic.com",
                "https://www.google.com/recaptcha/",
                "https://www.gstatic.com/recaptcha/",
                "https://js.stripe.com",
                "https://www.googletagmanager.com",
                "https://cdn.jsdelivr.net",
                "https://cdn.ckeditor.com;",
            "style-src 'self' 'unsafe-inline'",
                "https://fonts.googleapis.com",
                "https://cdn.jsdelivr.net",
                "https://cdn.ckeditor.com;",
            "font-src 'self'",
                "https://fonts.gstatic.com",
                "data:;",
            "img-src 'self' data: blob: https:;",
            "connect-src 'self'",
                "https://www.google.com",
                "https://www.google.com/recaptcha/",
                "https://api.stripe.com",
                "https://www.google-analytics.com;",
            "frame-src 'self'",
                "https://www.google.com",
                "https://www.google.com/recaptcha/",
                "https://recaptcha.google.com/",
                "https://js.stripe.com",
                "https://hooks.stripe.com;",
            "frame-ancestors 'self';",
            "object-src 'none';",
            "base-uri 'self';",
            "form-action 'self';",
            "upgrade-insecure-requests;",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);

        // ── 2. Strict-Transport-Security (HSTS) ────────────────────────────────
        // Always send — scanners test over HTTPS even if local dev is HTTP.
        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains; preload'
        );

        // ── 3. X-Frame-Options (Clickjacking) ──────────────────────────────────
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // ── 4. X-Content-Type-Options (MIME Sniffing) ──────────────────────────
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ── 5. Referrer-Policy ─────────────────────────────────────────────────
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ── 6. Permissions-Policy ──────────────────────────────────────────────
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(self "https://js.stripe.com"), '
            . 'accelerometer=(), autoplay=(), browsing-topics=(), display-capture=(), '
            . 'encrypted-media=(), fullscreen=(self), gyroscope=(), magnetometer=(), '
            . 'midi=(), picture-in-picture=(self), usb=(), xr-spatial-tracking=()'
        );

        // ── 7. Cross-Origin-Opener-Policy ─────────────────────────────────────
        // Prevents cross-origin window attacks. 'same-origin-allow-popups' needed for Stripe.
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');

        // ── 8. Cross-Origin-Embedder-Policy ───────────────────────────────────
        // 'unsafe-none' allows Stripe iframes to load; tighten to 'require-corp' if no 3rd-party embeds.
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');

        // ── 9. Cross-Origin-Resource-Policy ───────────────────────────────────
        // Allows same-site resources (CDN subdomains etc.) to be loaded.
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-site');

        // ── Cookie Security: Enforce proper flags on dynamic pages, strip on static assets ──
        $uri = $request->getRequestUri();
        $isStatic = str_starts_with($uri, '/cdn-assets/')
            || str_starts_with($uri, '/fonts/')
            || str_contains($uri, 'favicon')
            || preg_match('/\.(css|js|jpe?g|png|gif|webp|svg|ico|woff|woff2|ttf|otf|eot|map)(\?.*)?$/i', $uri);

        if ($isStatic) {
            $response->headers->remove('Set-Cookie');
            $response->headers->remove('Cookie');
        } else {
            foreach ($response->headers->getCookies() as $cookie) {
                // Force SameSite=Lax, HttpOnly=true; Secure only over HTTPS
                $secure   = $isHttps ? true : $cookie->isSecure();
                $sameSite = $cookie->getSameSite() ?: 'lax';
                // XSRF-TOKEN must remain readable by JS for AJAX, keep HttpOnly=false for it
                $httpOnly = ($cookie->getName() === 'XSRF-TOKEN') ? false : true;

                $updatedCookie = $cookie
                    ->withSecure($secure)
                    ->withHttpOnly($httpOnly)
                    ->withSameSite($sameSite);

                $response->headers->setCookie($updatedCookie);
            }
        }

        return $response;
    }
}
