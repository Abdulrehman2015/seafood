<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'customer_group',
        'approval_status',
        'phone',
        'company_name',
        'company_reg_no',
        'business_type',
        'address',
        'city',
        'state',
        'postcode',
        'rejection_reason',
        'approved_at',
        'approved_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at'       => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->customer_group === 'admin';
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    public function needsApproval(): bool
    {
        return in_array($this->customer_group, ['wholesale', 'trading']) && !$this->isApproved();
    }

    public function getGroupLabelAttribute(): string
    {
        return match ($this->customer_group) {
            'retail'    => 'Retail Customer',
            'wholesale' => 'Wholesale Customer',
            'trading'   => 'Trading Customer',
            'admin'     => 'Administrator',
            default     => ucfirst($this->customer_group),
        };
    }

    public function getGroupBadgeClassAttribute(): string
    {
        return match ($this->customer_group) {
            'retail'    => 'badge-retail',
            'wholesale' => 'badge-wholesale',
            'trading'   => 'badge-trading',
            'admin'     => 'badge-admin',
            default     => 'badge-default',
        };
    }
}
