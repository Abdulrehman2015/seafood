<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'collection_token', 'user_id', 'customer_group',
        'customer_name', 'customer_email', 'customer_phone',
        'status', 'payment_status', 'payment_method', 'payment_reference', 'paid_at',
        'fulfillment_type', 'shipping_address', 'collection_date', 'collection_time',
        'subtotal', 'shipping_fee', 'tax', 'discount', 'total',
        'stripe_payment_intent',
        'customer_notes', 'admin_notes',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'paid_at'          => 'datetime',
        'subtotal'         => 'decimal:2',
        'shipping_fee'     => 'decimal:2',
        'tax'              => 'decimal:2',
        'discount'         => 'decimal:2',
        'total'            => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'    => '<span class="badge badge-warning">Pending</span>',
            'confirmed'  => '<span class="badge badge-info">Confirmed</span>',
            'processing' => '<span class="badge badge-primary">Processing</span>',
            'ready'      => '<span class="badge badge-success">Ready</span>',
            'shipped'    => '<span class="badge badge-primary">Shipped</span>',
            'delivered'  => '<span class="badge badge-success">Delivered</span>',
            'cancelled'  => '<span class="badge badge-danger">Cancelled</span>',
            default      => '<span class="badge badge-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    public function getPaymentBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'     => '<span class="badge badge-success">Paid</span>',
            'unpaid'   => '<span class="badge badge-warning">Unpaid</span>',
            'refunded' => '<span class="badge badge-info">Refunded</span>',
            'failed'   => '<span class="badge badge-danger">Failed</span>',
            default    => '<span class="badge badge-secondary">' . ucfirst($this->payment_status) . '</span>',
        };
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }

            if (($order->customer_group === 'walkin' || $order->fulfillment_type === 'self_collection') && empty($order->collection_token)) {
                $todayCount = static::whereDate('created_at', today())
                    ->whereNotNull('collection_token')
                    ->count() + 1;
                $order->collection_token = 'W-' . str_pad((string) $todayCount, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
