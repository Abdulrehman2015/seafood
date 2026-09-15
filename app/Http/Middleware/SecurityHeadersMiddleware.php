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

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Legacy browser XSS filter
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control referrer information sent in HTTP requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restrict sensitive browser permissions / hardware features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(self "https://js.stripe.com")');

        // Content Security Policy: Allow own resources, Stripe SDK, Google Fonts, and CDNs
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
               "font-src 'self' https://fonts.gstatic.com data:; " .
               "img-src 'self' data: blob: https:; " .
               "connect-src 'self' https://api.stripe.com; " .
               "frame-src 'self' https://js.stripe.com https://hooks.stripe.com; " .
               "object-src 'none'; " .
               "base-uri 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
