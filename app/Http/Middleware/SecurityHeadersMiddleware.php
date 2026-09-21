<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. X-Frame-Options (Prevent Clickjacking)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 2. X-Content-Type-Options (Prevent MIME-sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 3. X-XSS-Protection (Legacy filter)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 4. Referrer-Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 5. Permissions-Policy
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(self "https://js.stripe.com")'
        );

        // 6. Strict-Transport-Security (HSTS - 1 year, includeSubDomains, preload)
        $isHttps = $request->isSecure()
            || $request->header('X-Forwarded-Proto') === 'https'
            || app()->environment('production');

        if ($isHttps) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // 7. Content-Security-Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com https://www.googletagmanager.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
               "font-src 'self' https://fonts.gstatic.com data:; " .
               "img-src 'self' data: blob: https:; " .
               "connect-src 'self' https://api.stripe.com https://www.google-analytics.com; " .
               "frame-src 'self' https://js.stripe.com https://hooks.stripe.com; " .
               "object-src 'none'; " .
               "base-uri 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        // 8. Enforce Cookie Security (Secure, HttpOnly, SameSite) on all outgoing cookies
        foreach ($response->headers->getCookies() as $cookie) {
            $secure = $isHttps || $cookie->isSecure();
            $httpOnly = true;
            $sameSite = $cookie->getSameSite() ?: 'lax';

            $updatedCookie = $cookie
                ->withSecure($secure)
                ->withHttpOnly($httpOnly)
                ->withSameSite($sameSite);

            $response->headers->setCookie($updatedCookie);
        }

        return $response;
    }
}
