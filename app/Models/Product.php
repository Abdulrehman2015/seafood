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
        'name', 'slug', 'description', 'short_description', 'sku',
        'category_id',
        'retail_price', 'walkin_price', 'wholesale_price', 'trading_price',
        'price_sgd', 'price_usd', 'wholesale_price_sgd', 'wholesale_price_usd',
        'trading_price_sgd', 'trading_price_usd',
        'weight', 'unit', 'origin', 'storage_temp', 'brand',
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

    public function isInStock(): bool
    {
        if (!$this->track_stock) return true;
        return $this->stock_quantity > 0;
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
