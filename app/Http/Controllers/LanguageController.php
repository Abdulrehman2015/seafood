<?php

namespace App\Http\Controllers;

use App\Services\TranslationService;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function __construct(
        protected TranslationService $translationService
    ) {}

    /**
     * Switch application language (en, zh, bm).
     */
    public function switch(Request $request, ?string $locale = null)
    {
        $rawLocale = $locale ?: ($request->route('locale') ?: ($request->segment(2) ?: ($request->input('locale') ?: ($request->input('lang') ?: $request->query('locale', $request->query('lang'))))));
        $locale = $this->translationService->normalizeLocale($rawLocale);

        if (!$this->translationService->isLocaleSupported($locale)) {
            $locale = 'en';
        }

        $this->translationService->setLocale($locale);

        $locales = $this->translationService->getSupportedLocales();
        $target = $locales[$locale] ?? $locales['en'];
        $message = "Language changed to {$target['name']} ({$target['native']}).";

        $redirectUrl = $this->getLocalizedRedirectUrl($request, $locale);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'locale'       => $locale,
                'redirect_url' => $redirectUrl,
                'label'        => $target['label'],
                'name'         => $target['name'],
                'native'       => $target['native'],
                'flag'         => $target['flag'],
                'message'      => $message,
            ])->cookie('locale', $locale, 60 * 24 * 365, '/', null, $request->isSecure() || $request->header('X-Forwarded-Proto') === 'https' || app()->environment('production'), true, false, 'lax');
        }

        $isSecure = $request->isSecure() || $request->header('X-Forwarded-Proto') === 'https' || app()->environment('production');
        return redirect()->to($redirectUrl)->with('success', $message)->cookie('locale', $locale, 60 * 24 * 365, '/', null, $isSecure, true, false, 'lax');
    }

    /**
     * Determine the redirect URL with the new locale prefix.
     */
    protected function getLocalizedRedirectUrl(Request $request, string $newLocale): string
    {
        $referer = $request->input('current_url') ?: ($request->query('current_url') ?: $request->headers->get('referer'));
        if (!$referer) {
            return url('/' . $newLocale);
        }

        $parsed = parse_url($referer);
        $path = $parsed['path'] ?? '/';

        // Do not alter admin or non-prefixed system routes
        if (preg_match('#^/(admin|api|currency|language|newsletter)#', $path)) {
            return $referer;
        }

        $segments = explode('/', trim($path, '/'));
        if (!empty($segments[0]) && in_array($segments[0], ['en', 'zh', 'bm'])) {
            $segments[0] = $newLocale;
            $newPath = '/' . implode('/', $segments);
        } else {
            $newPath = '/' . $newLocale . ($path === '/' ? '' : $path);
        }

        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
        return url($newPath . $query);
    }
}
