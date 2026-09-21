<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TranslationService
{
    const CACHE_KEY = 'oceanfresh_translations_all_v1';
    const CACHE_TTL = 86400; // 24 hours (cleared on update)

    /**
     * Supported application locales.
     */
    public static array $locales = [
        'en' => [
            'code'   => 'en',
            'label'  => 'EN',
            'name'   => 'English',
            'native' => 'English',
            'flag'   => '🇬🇧',
        ],
        'zh' => [
            'code'   => 'zh',
            'label'  => 'ZH',
            'name'   => 'Simplified Chinese',
            'native' => '简体中文',
            'flag'   => '🇨🇳',
        ],
        'bm' => [
            'code'   => 'bm',
            'label'  => 'BM',
            'name'   => 'Bahasa Melayu',
            'native' => 'Bahasa Melayu',
            'flag'   => '🇲🇾',
        ],
    ];

    /**
     * Get all supported locales.
     */
    public function getSupportedLocales(): array
    {
        return self::$locales;
    }

    /**
     * Check if a locale is supported.
     */
    public function isLocaleSupported(?string $locale): bool
    {
        if (!$locale) {
            return false;
        }
        $locale = strtolower(trim($locale));
        return in_array($locale, ['en', 'zh', 'bm', 'ms']);
    }

    /**
     * Normalize locale code ('ms' -> 'bm').
     */
    public function normalizeLocale(?string $locale): string
    {
        if (!$locale) {
            return 'en';
        }
        $locale = strtolower(trim($locale));
        if ($locale === 'ms') {
            return 'bm';
        }
        if (array_key_exists($locale, self::$locales)) {
            return $locale;
        }
        return 'en';
    }

    /**
     * Get the current active application locale.
     */
    public function currentLocale(): string
    {
        $current = app()->getLocale();
        return $this->normalizeLocale($current);
    }

    /**
     * Switch current locale and persist in session & cookie.
     */
    public function setLocale(string $locale): bool
    {
        $normalized = $this->normalizeLocale($locale);
        if (!$this->isLocaleSupported($normalized)) {
            return false;
        }

        app()->setLocale($normalized);
        session(['locale' => $normalized]);

        if (auth()->check()) {
            try {
                auth()->user()->update(['preferred_locale' => $normalized]);
            } catch (\Throwable $e) {
                // ignore
            }
        }

        return true;
    }

    /**
     * Retrieve all translations cached in memory/cache.
     */
    public function getAllTranslations(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $all = [];
            try {
                $rows = Translation::all();
                foreach ($rows as $row) {
                    $compositeKey = $row->group . '.' . $row->key;
                    $entry = [
                        'id'      => $row->id,
                        'group'   => $row->group,
                        'key'     => $row->key,
                        'text_en' => $row->text_en ?? '',
                        'text_zh' => $row->text_zh ?? '',
                        'text_bm' => $row->text_bm ?? '',
                    ];
                    $all[$compositeKey] = $entry;
                    if ($row->key !== $compositeKey) {
                        $all[$row->key] = $entry;
                    }
                    if (!empty($row->text_en)) {
                        $all['by_en:' . trim($row->text_en)] = $entry;
                    }
                }
            } catch (\Throwable $e) {
                // Database or table not ready yet
            }
            return $all;
        });
    }

    /**
     * Translate a key with optional default text and parameter replacements.
     */
    public function translate(string $key, ?string $default = null, array $replace = [], ?string $locale = null): string
    {
        $targetLocale = $locale ? $this->normalizeLocale($locale) : $this->currentLocale();

        // Separate group and key
        if (str_contains($key, '.')) {
            $parts = explode('.', $key, 2);
            $group = $parts[0];
            $itemKey = $parts[1];
            $compositeKey = $key;
        } else {
            $group = 'common';
            $itemKey = $key;
            $compositeKey = "common.{$key}";
        }

        $all = $this->getAllTranslations();
        $text = null;

        if (isset($all[$compositeKey])) {
            $record = $all[$compositeKey];
            if ($targetLocale === 'zh') {
                $text = !empty($record['text_zh']) ? $record['text_zh'] : (!empty($record['text_en']) ? $record['text_en'] : null);
            } elseif ($targetLocale === 'bm') {
                $text = !empty($record['text_bm']) ? $record['text_bm'] : (!empty($record['text_en']) ? $record['text_en'] : null);
            } else {
                $text = !empty($record['text_en']) ? $record['text_en'] : null;
            }
        } elseif (isset($all['by_en:' . trim($key)])) {
            $record = $all['by_en:' . trim($key)];
            if ($targetLocale === 'zh') {
                $text = !empty($record['text_zh']) ? $record['text_zh'] : (!empty($record['text_en']) ? $record['text_en'] : null);
            } elseif ($targetLocale === 'bm') {
                $text = !empty($record['text_bm']) ? $record['text_bm'] : (!empty($record['text_en']) ? $record['text_en'] : null);
            } else {
                $text = !empty($record['text_en']) ? $record['text_en'] : null;
            }
        }

        // Fallback to provided default or clean key
        if ($text === null || $text === '') {
            $text = $default ?? $itemKey;
        }

        // Replace parameters (e.g. :name or {name})
        if (!empty($replace)) {
            foreach ($replace as $param => $val) {
                $text = str_replace(
                    [':' . $param, '{' . $param . '}'],
                    (string) $val,
                    $text
                );
            }
        }

        return $text;
    }

    /**
     * Clear all cached translations.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Batch save / update translations.
     */
    public function batchSave(array $items): int
    {
        $updatedCount = 0;
        foreach ($items as $item) {
            if (empty($item['key'])) {
                continue;
            }
            $group = !empty($item['group']) ? trim($item['group']) : 'common';
            $key   = trim($item['key']);

            Translation::updateOrCreate(
                ['group' => $group, 'key' => $key],
                [
                    'text_en' => $item['text_en'] ?? '',
                    'text_zh' => $item['text_zh'] ?? '',
                    'text_bm' => $item['text_bm'] ?? '',
                ]
            );
            $updatedCount++;
        }

        $this->clearCache();
        $this->syncLangFiles();

        return $updatedCount;
    }

    /**
     * Sync translations to Laravel lang JSON files (lang/en.json, lang/zh.json, lang/bm.json).
     */
    public function syncLangFiles(): void
    {
        try {
            $langDir = base_path('lang');
            if (!File::isDirectory($langDir)) {
                File::makeDirectory($langDir, 0755, true, true);
            }

            $translations = Translation::all();
            $en = [];
            $zh = [];
            $bm = [];

            foreach ($translations as $t) {
                $fullKey = "{$t->group}.{$t->key}";
                $en[$fullKey] = $t->text_en ?: $t->key;
                $zh[$fullKey] = $t->text_zh ?: ($t->text_en ?: $t->key);
                $bm[$fullKey] = $t->text_bm ?: ($t->text_en ?: $t->key);

                // Also store direct key mapping
                $en[$t->key] = $t->text_en ?: $t->key;
                $zh[$t->key] = $t->text_zh ?: ($t->text_en ?: $t->key);
                $bm[$t->key] = $t->text_bm ?: ($t->text_en ?: $t->key);
            }

            File::put($langDir . '/en.json', json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            File::put($langDir . '/zh.json', json_encode($zh, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            File::put($langDir . '/bm.json', json_encode($bm, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            File::put($langDir . '/ms.json', json_encode($bm, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); // alias
        } catch (\Throwable $e) {
            // Ignore file write errors if permissions restricted
        }
    }
}
