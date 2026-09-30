<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'name_zh', 'name_bm',
        'slug',
        'description', 'description_zh', 'description_bm',
        'short_description', 'short_description_zh', 'short_description_bm',
        'sku',
        'category_id',
        'retail_price', 'walkin_price', 'wholesale_price', 'trading_price',
        'price_sgd', 'price_usd', 'wholesale_price_sgd', 'wholesale_price_usd',
        'trading_price_sgd', 'trading_price_usd',
        'weight', 'unit', 'pricing_model', 'reference_weight', 'actual_weight_unit', 'unit_price_per_weight',
        'origin', 'storage_temp', 'storage_icon', 'brand',
        'specifications', 'images', 'thumbnail',
        'stock_quantity', 'track_stock',
        'moq', 'moq_wholesale', 'moq_trading',
        'is_active', 'is_walkin_available', 'is_featured', 'is_rfq_only',
        'sort_order',
    ];

    protected $casts = [
        'specifications'      => 'array',
        'images'              => 'array',
        'is_active'           => 'boolean',
        'is_walkin_available' => 'boolean',
        'is_featured'         => 'boolean',
        'is_rfq_only'         => 'boolean',
        'track_stock'         => 'boolean',
        'retail_price'        => 'decimal:2',
        'walkin_price'        => 'decimal:2',
        'wholesale_price'     => 'decimal:2',
        'trading_price'       => 'decimal:2',
        'price_sgd'           => 'decimal:2',
        'price_usd'           => 'decimal:2',
        'wholesale_price_sgd'  => 'decimal:2',
        'wholesale_price_usd'  => 'decimal:2',
        'trading_price_sgd'   => 'decimal:2',
        'trading_price_usd'   => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWalkinAvailable($query)
    {
        return $query->where('is_walkin_available', true)->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('track_stock', false)
              ->orWhere('stock_quantity', '>', 0);
        });
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function getPriceForGroup(string $group): ?float
    {
        return match ($group) {
            'retail'    => (float) $this->retail_price,
            'walkin'    => (float) $this->walkin_price,
            'wholesale' => (float) $this->wholesale_price,
            'trading'   => $this->is_rfq_only ? null : (float) $this->trading_price,
            default     => (float) $this->retail_price,
        };
    }

    public function getMoqForGroup(string $group): int
    {
        return match ($group) {
            'wholesale' => $this->moq_wholesale ?? $this->moq,
            'trading'   => $this->moq_trading   ?? $this->moq,
            default     => 1,
        };
    }

    public function isVariableWeight(): bool
    {
        return ($this->pricing_model ?? 'fixed_unit') === 'variable_weight';
    }

    public function isFixedUnit(): bool
    {
        return empty($this->pricing_model) || $this->pricing_model === 'fixed_unit';
    }

    public function getReferenceWeight(): ?string
    {
        return $this->reference_weight ?: $this->weight;
    }

    public function isInStock(): bool
    {
        if (!$this->track_stock) return true;
        return $this->stock_quantity > 0;
    }

    public function getThumbnailUrlAttribute(): string
    {
        $thumb = $this->thumbnail ?? ($this->images[0] ?? null);
        if ($thumb) {
            return cdn_storage($thumb);
        }
        return asset('images/placeholder.png');
    }

    /**
     * Get resolved multi-currency price array.
     */
    public function getDisplayPrice(string $group = 'retail', ?string $currency = null): array
    {
        return app(\App\Services\CurrencyService::class)->getProductPrice($this, $group, $currency);
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->getDisplayPrice()['formatted'];
    }

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

    public function getShortDescriptionAttribute($value)
    {
        $locale = current_locale();
        if ($locale === 'zh' && !empty($this->attributes['short_description_zh'])) {
            return $this->attributes['short_description_zh'];
        }
        if ($locale === 'bm' && !empty($this->attributes['short_description_bm'])) {
            return $this->attributes['short_description_bm'];
        }
        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        $locale = current_locale();
        if ($locale === 'zh' && !empty($this->attributes['description_zh'])) {
            return $this->attributes['description_zh'];
        }
        if ($locale === 'bm' && !empty($this->attributes['description_bm'])) {
            return $this->attributes['description_bm'];
        }
        return $value;
    }

    public function getOriginAttribute($value)
    {
        if (empty($value)) return $value;
        $locale = current_locale();
        if ($locale === 'zh') {
            $originMapZh = [
                'Norway'          => '挪威',
                'Sabah, Malaysia' => '马来西亚沙巴',
                'Malaysia'        => '马来西亚',
                'Thailand'        => '泰国',
                'Indonesia'       => '印度尼西亚',
                'Vietnam'         => '越南',
                'Myanmar'         => '缅甸',
                'Canada'          => '加拿大',
                'New Zealand'     => '新西兰',
                'Japan'           => '日本',
                'Australia'       => '澳大利亚',
                'China'           => '中国',
                'Chile'           => '智利',
                'Taiwan'          => '中国台湾',
                'India'           => '印度',
                'South Korea'     => '韩国',
                'Korea'           => '韩国',
                'USA'             => '美国',
                'United Kingdom'  => '英国',
                'Argentina'       => '阿根廷',
            ];
            return $originMapZh[$value] ?? $value;
        }
        if ($locale === 'bm') {
            $originMapBm = [
                'Japan'       => 'Jepun',
                'Canada'      => 'Kanada',
                'South Korea' => 'Korea Selatan',
                'USA'         => 'Amerika Syarikat',
            ];
            return $originMapBm[$value] ?? $value;
        }
        return $value;
    }

    public function getUnitAttribute($value)
    {
        if (empty($value)) return $value;
        $norm = strtolower(trim($value));
        $locale = current_locale();
        if ($locale === 'zh') {
            $unitMapZh = [
                'pack'    => '包',
                'packs'   => '包',
                'pkt'     => '包',
                'box'     => '盒',
                'boxes'   => '盒',
                'carton'  => '箱',
                'cartons' => '箱',
                'bag'     => '袋',
                'bags'    => '袋',
                'piece'   => '件',
                'pieces'  => '件',
                'pc'      => '件',
                'pcs'     => '件',
                'fish'    => '条',
                'kg'      => '公斤',
                'kilogram'=> '公斤',
                'g'       => '克',
                'gram'    => '克',
                'pair'    => '对',
                'pairs'   => '对',
                'tube'    => '条',
                'tubes'   => '条',
                'tray'    => '盒',
                'trays'   => '盒',
                'bottle'  => '瓶',
                'can'     => '罐',
            ];
            return $unitMapZh[$norm] ?? $value;
        }
        if (in_array($locale, ['bm', 'ms'])) {
            $unitMapBm = [
                'pack'    => 'pek',
                'packs'   => 'pek',
                'pkt'     => 'pek',
                'box'     => 'kotak',
                'boxes'   => 'kotak',
                'carton'  => 'karton',
                'cartons' => 'karton',
                'bag'     => 'beg',
                'bags'    => 'beg',
                'piece'   => 'keping',
                'pieces'  => 'keping',
                'pc'      => 'keping',
                'pcs'     => 'keping',
                'fish'    => 'ekor',
                'kg'      => 'kg',
                'kilogram'=> 'kg',
                'g'       => 'g',
                'gram'    => 'g',
                'pair'    => 'pasang',
                'pairs'   => 'pasang',
                'tube'    => 'tiub',
                'tubes'   => 'tiub',
                'tray'    => 'dulang',
                'trays'   => 'dulang',
                'bottle'  => 'botol',
                'can'     => 'tin',
            ];
            return $unitMapBm[$norm] ?? $value;
        }
        return $value;
    }

    public function getStorageIcon(): string
    {
        if (!empty($this->storage_icon)) {
            return trim($this->storage_icon);
        }
        $st = strtolower($this->storage_temp ?? '');
        if (str_contains($st, 'live')) {
            return '🦀';
        }
        if (str_contains($st, 'chilled')) {
            return '🧊';
        }
        return '❄️';
    }

    public function getStorageDisplay(): string
    {
        $st = trim($this->storage_temp ?? '');
        $stLower = strtolower($st);
        $nameLower = strtolower($this->name ?? '');
        $locale = current_locale();

        if (str_contains($stLower, 'live') || str_contains($nameLower, 'live')) {
            if ($locale === 'bm') return '🦀 Storan: Live / Chilled';
            if ($locale === 'zh') return '🦀 储存：活体 / 冰鲜';
            return '🦀 Storage: Live / Chilled';
        }
        if (str_contains($stLower, 'chilled') || str_contains($stLower, '0°c to 4°c') || str_contains($stLower, '0-4°c')) {
            if ($locale === 'bm') return '🧊 Storan: Sejuk Dingin';
            if ($locale === 'zh') return '🧊 储存：冷藏 (0°C~4°C)';
            return '🧊 Storage: Chilled';
        }
        if (str_contains($stLower, 'ambient') || str_contains($stLower, 'room')) {
            if ($locale === 'bm') return '📦 Storan: Suhu Bilik';
            if ($locale === 'zh') return '📦 储存：常温';
            return '📦 Storage: Ambient';
        }
        if (str_contains($nameLower, 'iqf') || str_contains($stLower, 'iqf')) {
            if ($locale === 'bm') return '❄️ Storan: -18°C · IQF';
            if ($locale === 'zh') return '❄️ 储存：-18°C · IQF 单冻';
            return '❄️ Storage: -18°C · IQF';
        }
        if (!empty($st)) {
            if ($locale === 'bm') return '❄️ Storan: ' . $st;
            if ($locale === 'zh') return '❄️ 储存：' . $st;
            return '❄️ Storage: ' . $st;
        }
        if ($locale === 'bm') return '❄️ Storan: Beku -18°C';
        if ($locale === 'zh') return '❄️ 储存：冷冻 -18°C';
        return '❄️ Storage: Frozen -18°C';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
