<?php

use App\Services\TranslationService;

if (!function_exists('__t')) {
    /**
     * Dynamic translation helper function.
     *
     * @param string $key e.g. 'home.hero_title' or 'shop_now'
     * @param string|null $default Default English fallback
     * @param array $replace Key-value replacements
     * @param string|null $locale Specific locale override
     * @return string
     */
    function __t(string $key, ?string $default = null, array $replace = [], ?string $locale = null): string
    {
        try {
            return app(TranslationService::class)->translate($key, $default, $replace, $locale);
        } catch (\Throwable $e) {
            return $default ?? $key;
        }
    }
}

if (!function_exists('current_locale')) {
    /**
     * Get current active locale ('en', 'zh', 'bm').
     */
    function current_locale(): string
    {
        try {
            return app(TranslationService::class)->currentLocale();
        } catch (\Throwable $e) {
            return 'en';
        }
    }
}

if (!function_exists('supported_locales')) {
    /**
     * Get list of supported locales.
     */
    function supported_locales(): array
    {
        return TranslationService::$locales;
    }
}

if (!function_exists('localized_url')) {
    /**
     * Generate localized URL for a specific locale (en, zh, bm).
     *
     * @param string|null $locale Target locale (defaults to current)
     * @param string|null $url Source URL (defaults to current URL)
     * @return string
     */
    function localized_url(?string $locale = null, ?string $url = null): string
    {
        $targetLocale = $locale ?: current_locale();
        $sourceUrl = $url ?: url()->current();

        $parsed = parse_url($sourceUrl);
        $path = $parsed['path'] ?? '/';

        if (preg_match('#^/(admin|api|currency|language|newsletter)#', $path)) {
            return $sourceUrl;
        }

        $segments = explode('/', trim($path, '/'));
        if (!empty($segments[0]) && in_array($segments[0], ['en', 'zh', 'bm'])) {
            $segments[0] = $targetLocale;
            $newPath = '/' . implode('/', $segments);
        } else {
            $newPath = '/' . $targetLocale . ($path === '/' ? '' : $path);
        }

        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
        return url($newPath . $query);
    }
}

if (!function_exists('cdn_storage')) {
    /**
     * Generate a cookie-free CDN URL for a storage image.
     * Use this instead of asset('storage/...') to pass Pingdom cookie-free domain check.
     *
     * @param string $path  Path relative to storage/app/public (e.g. 'products/fish.webp')
     * @return string
     */
    function cdn_storage(string $path): string
    {
        $path = ltrim($path, '/');
        return url('/cdn-assets/img/' . $path);
    }
}

if (!function_exists('cdn_img')) {
    /**
     * Generate a cookie-free CDN URL for a public image.
     * Use this instead of asset('images/...') to pass Pingdom cookie-free domain check.
     *
     * @param string $path  Path relative to public/images (e.g. 'logo.webp')
     * @return string
     */
    function cdn_img(string $path): string
    {
        $path = ltrim($path, '/');
        return url('/cdn-assets/img/' . $path);
    }
}
