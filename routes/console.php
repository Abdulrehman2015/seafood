<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('translations:sync', function () {
    $this->info('Starting translations sync from lang/*.json to DB...');
    
    $zhFile = base_path('lang/zh.json');
    $bmFile = base_path('lang/bm.json');
    $msFile = base_path('lang/ms.json');
    $enFile = base_path('lang/en.json');

    $zh = file_exists($zhFile) ? (json_decode(file_get_contents($zhFile), true) ?: []) : [];
    $bm = file_exists($bmFile) ? (json_decode(file_get_contents($bmFile), true) ?: []) : [];
    $ms = file_exists($msFile) ? (json_decode(file_get_contents($msFile), true) ?: []) : [];
    $en = file_exists($enFile) ? (json_decode(file_get_contents($enFile), true) ?: []) : [];

    $mergedBm = array_merge($ms, $bm);
    $allKeys = array_unique(array_merge(array_keys($en), array_keys($zh), array_keys($mergedBm)));

    $count = 0;
    foreach ($allKeys as $fullKey) {
        $group = str_contains($fullKey, '.') ? explode('.', $fullKey, 2)[0] : 'common';
        $itemKey = str_contains($fullKey, '.') ? explode('.', $fullKey, 2)[1] : $fullKey;

        // Try matching by exact full key or itemKey within the group
        $existing = \App\Models\Translation::where('group', $group)
            ->where(function($q) use ($fullKey, $itemKey) {
                $q->where('key', $fullKey)->orWhere('key', $itemKey);
            })->first();

        if (!$existing) {
            $existing = new \App\Models\Translation();
            $existing->group = $group;
            $existing->key = $fullKey;
        }

        if (isset($en[$fullKey])) $existing->text_en = $en[$fullKey];
        if (isset($zh[$fullKey])) $existing->text_zh = $zh[$fullKey];
        if (isset($mergedBm[$fullKey])) $existing->text_bm = $mergedBm[$fullKey];
        
        $existing->save();
        $count++;
    }

    \Illuminate\Support\Facades\Cache::flush();
    $this->info("✓ Successfully synced {$count} translation keys to database and cleared cache.");
})->purpose('Sync all lang/*.json files into the translations database table');
