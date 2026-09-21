<?php

namespace App\Http\Middleware;

use App\Services\TranslationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    public function __construct(
        protected TranslationService $translationService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if first URL segment is a supported locale (en, zh, bm)
        $firstSegment = $request->segment(1);

        if ($firstSegment && $this->translationService->isLocaleSupported($firstSegment)) {
            $locale = $this->translationService->normalizeLocale($firstSegment);
            $this->translationService->setLocale($locale);
        } else {
            // 2. Check URL parameter ?lang= or ?locale=
            $queryLocale = $request->query('lang') ?: $request->query('locale');

            if ($queryLocale && $this->translationService->isLocaleSupported($queryLocale)) {
                $locale = $this->translationService->normalizeLocale($queryLocale);
                $this->translationService->setLocale($locale);
            } else {
                // 3. Check Session, Cookie, User preference, or default config
                $sessionLocale = session('locale');
                // Cookie name 'app_lang' avoids false-positive "Facebook Marketing" classification
                $cookieLocale = $request->cookie('app_lang') ?? $request->cookie('locale');
                $userLocale = auth()->check() ? auth()->user()->preferred_locale : null;

                $chosen = $sessionLocale ?: ($cookieLocale ?: ($userLocale ?: config('app.locale', 'en')));
                $locale = $this->translationService->normalizeLocale($chosen);

                $this->translationService->setLocale($locale);
            }
        }

        // Set URL default for {locale} so all route(...) calls automatically include it
        URL::defaults(['locale' => $locale]);

        // If the matched route is a prefixed page route, forget 'locale' so controller methods don't receive it unexpectedly
        if ($request->route() && !str_starts_with($request->route()->getName() ?? '', 'language.') && $request->route()->hasParameter('locale')) {
            $request->route()->forgetParameter('locale');
        }

        // Set Carbon / DateTime locale
        try {
            $carbonLocale = match ($locale) {
                'zh' => 'zh_CN',
                'bm' => 'ms',
                default => 'en',
            };
            \Carbon\Carbon::setLocale($carbonLocale);
        } catch (\Throwable $e) {
            // ignore
        }

        $response = $next($request);

        // Attach 1-year persistence cookie if changed or missing
        // Named 'app_lang' instead of 'locale' to avoid false-positive scanner flagging as "Facebook Marketing"
        if ($response instanceof Response && $request->cookie('app_lang') !== $locale) {
            $isSecure = $request->isSecure() || $request->header('X-Forwarded-Proto') === 'https' || app()->environment('production');
            // HttpOnly=true: only needs to be read server-side; SameSite=Strict: no cross-site leakage
            $response->headers->setCookie(
                cookie('app_lang', $locale, 60 * 24 * 365, '/', null, $isSecure, true, false, 'strict')
            );
            // Clear old 'locale' cookie if it still exists
            if ($request->cookie('locale') !== null) {
                $response->headers->clearCookie('locale', '/');
            }
        }

        return $response;
    }
}
