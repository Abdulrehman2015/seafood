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
        $label = match ($this->status) {
            'pending'    => __t('order.status.pending', 'Pending'),
            'confirmed'  => __t('order.status.confirmed', 'Confirmed'),
            'processing' => __t('order.status.processing', 'Processing'),
            'ready'      => __t('order.status.ready', 'Ready'),
            'shipped'    => __t('order.status.shipped', 'Shipped'),
            'delivered'  => __t('order.status.delivered', 'Delivered'),
            'cancelled'  => __t('order.status.cancelled', 'Cancelled'),
            default      => ucfirst($this->status),
        };
        $class = match ($this->status) {
            'pending'    => 'badge-warning',
            'confirmed'  => 'badge-info',
            'processing' => 'badge-primary',
            'ready'      => 'badge-success',
            'shipped'    => 'badge-primary',
            'delivered'  => 'badge-success',
            'cancelled'  => 'badge-danger',
            default      => 'badge-secondary',
        };
        return '<span class="badge ' . $class . '">' . e($label) . '</span>';
    }

    public function getPaymentBadgeAttribute(): string
    {
        $label = match ($this->payment_status) {
            'paid'     => __t('order.payment.paid', 'Paid'),
            'unpaid'   => __t('order.payment.unpaid', 'Unpaid'),
            'refunded' => __t('order.payment.refunded', 'Refunded'),
            'failed'   => __t('order.payment.failed', 'Failed'),
            default    => ucfirst($this->payment_status),
        };
        $class = match ($this->payment_status) {
            'paid'     => 'badge-success',
            'unpaid'   => 'badge-warning',
            'refunded' => 'badge-info',
            'failed'   => 'badge-danger',
            default    => 'badge-secondary',
        };
        return '<span class="badge ' . $class . '">' . e($label) . '</span>';
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
