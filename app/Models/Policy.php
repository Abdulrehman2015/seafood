<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'summary',
        'status',
        'sort_order',
        'title_zh',
        'content_zh',
        'title_bm',
        'content_bm',
        'meta_title',
        'meta_description',
    ];

    /**
     * Scope a query to only include published policies.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Dynamic title based on active locale.
     */
    public function getTitleForLocaleAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'zh' && !empty($this->title_zh)) {
            return $this->title_zh;
        }
        if ($locale === 'bm' && !empty($this->title_bm)) {
            return $this->title_bm;
        }
        return $this->title;
    }

    /**
     * Dynamic content based on active locale.
     */
    public function getContentForLocaleAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'zh' && !empty($this->content_zh)) {
            return $this->content_zh;
        }
        if ($locale === 'bm' && !empty($this->content_bm)) {
            return $this->content_bm;
        }
        return $this->content;
    }

    /**
     * Helper to generate unique slug.
     */
    public static function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }
}
