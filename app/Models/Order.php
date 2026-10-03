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
        'fulfillment_type', 'shipping_address', 'collection_date', 'collection_time', 'delivery_date',
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

    // Standard Delivery Statuses
    public const STATUS_PENDING    = 'pending';
    public const STATUS_CONFIRMED  = 'confirmed';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED    = 'shipped';
    public const STATUS_DELIVERED  = 'delivered';
    public const STATUS_CANCELLED  = 'cancelled';

    // Walk-in / Self-Collection Status Lifecycle (Item 14)
    public const WALKIN_STATUS_PAYMENT_PENDING   = 'payment_pending';
    public const WALKIN_STATUS_PAYMENT_CONFIRMED = 'payment_confirmed';
    public const WALKIN_STATUS_PREPARATION       = 'preparation';
    public const WALKIN_STATUS_READY_COLLECTION  = 'ready_collection';
    public const WALKIN_STATUS_COLLECTED         = 'collected';

    public function isWalkin(): bool
    {
        return $this->customer_group === 'walkin' || $this->fulfillment_type === 'self_collection';
    }

    public function canPrepare(): bool
    {
        // Payment confirmation must occur before preparation
        return $this->payment_status === 'paid' || $this->status === self::WALKIN_STATUS_PAYMENT_CONFIRMED;
    }

    public function canCollect(): bool
    {
        // Must be paid before order can be released for collection
        return $this->payment_status === 'paid' && in_array($this->status, [self::WALKIN_STATUS_READY_COLLECTION, 'ready']);
    }

    public function getStatusBadgeAttribute(): string
    {
        $label = match ($this->status) {
            'pending', 'payment_pending'     => __t('order.status.payment_pending', 'Payment Pending'),
            'confirmed', 'payment_confirmed' => __t('order.status.payment_confirmed', 'Payment Confirmed'),
            'processing', 'preparation'      => __t('order.status.preparation', 'Preparation'),
            'ready', 'ready_collection'      => __t('order.status.ready_collection', 'Ready for Collection'),
            'collected'                      => __t('order.status.collected', 'Collected'),
            'shipped'                        => __t('order.status.shipped', 'Shipped'),
            'delivered'                      => __t('order.status.delivered', 'Delivered'),
            'cancelled'                      => __t('order.status.cancelled', 'Cancelled'),
            default                          => ucfirst($this->status),
        };
        $class = match ($this->status) {
            'pending', 'payment_pending'     => 'badge-warning',
            'confirmed', 'payment_confirmed' => 'badge-info',
            'processing', 'preparation'      => 'badge-primary',
            'ready', 'ready_collection'      => 'badge-success',
            'collected'                      => 'badge-secondary',
            'shipped'                        => 'badge-primary',
            'delivered'                      => 'badge-success',
            'cancelled'                      => 'badge-danger',
            default                          => 'badge-secondary',
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

    /**
     * Automatically link past guest orders matching email or phone to this user.
     */
    public static function linkGuestOrdersToUser(User $user): int
    {
        if (empty($user->id)) {
            return 0;
        }

        $email = trim($user->email ?? '');
        $phone = trim($user->phone ?? '');

        if (empty($email) && empty($phone)) {
            return 0;
        }

        $query = static::whereNull('user_id');

        $query->where(function ($q) use ($email, $phone) {
            if (!empty($email) && !empty($phone)) {
                $q->where('customer_email', $email)
                  ->orWhere('customer_phone', $phone);
            } elseif (!empty($email)) {
                $q->where('customer_email', $email);
            } elseif (!empty($phone)) {
                $q->where('customer_phone', $phone);
            }
        });

        return $query->update(['user_id' => $user->id]);
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
