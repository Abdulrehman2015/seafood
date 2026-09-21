<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'text_en',
        'text_zh',
        'text_bm',
    ];

    /**
     * Scope to filter by group.
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get translated text for given locale, fallback to English.
     */
    public function getForLocale(string $locale): string
    {
        $normalized = strtolower(trim($locale));
        if ($normalized === 'zh' || str_starts_with($normalized, 'zh')) {
            return !empty($this->text_zh) ? $this->text_zh : ($this->text_en ?? '');
        }
        if ($normalized === 'bm' || $normalized === 'ms' || str_starts_with($normalized, 'ms')) {
            return !empty($this->text_bm) ? $this->text_bm : ($this->text_en ?? '');
        }
        return $this->text_en ?? '';
    }
}
