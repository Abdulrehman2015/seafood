<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'session_id', 'user_id', 'product_id', 'quantity', 'customer_group',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSubtotalAttribute(): float
    {
        $price = $this->product?->getPriceForGroup($this->customer_group) ?? 0;
        return $price * $this->quantity;
    }
}
