<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_zh', 'name_bm', 'slug', 'custom_url', 'description', 'icon', 'image', 'parent_id', 'sort_order', 'is_active', 'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function getNameAttribute($value)
    {
        $locale = current_locale();
        if ($locale === 'zh' && !empty($this->attributes['name_zh'])) {
            return $this->attributes['name_zh'];
        }
        if ($locale === 'bm' && !empty($this->attributes['name_bm'])) {
            return $this->attributes['name_bm'];
        }
        return $value;
    }

    public function getUrlAttribute(): string
    {
        if (!empty($this->custom_url)) {
            return $this->custom_url;
        }
        return route('shop.index', ['category' => $this->slug]);
    }

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('footer.categories');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('footer.categories');
        });
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/category-placeholder.png');
    }
}
