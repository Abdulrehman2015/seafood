<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_number', 'user_id', 'status',
        'customer_notes', 'admin_notes', 'valid_until', 'total_quoted',
    ];

    protected $casts = [
        'valid_until'   => 'datetime',
        'total_quoted'  => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Quotation $quotation) {
            if (empty($quotation->quotation_number)) {
                $quotation->quotation_number = 'RFQ-' . strtoupper(uniqid());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'   => '<span class="badge badge-warning">Pending Review</span>',
            'quoted'    => '<span class="badge badge-info">Quoted</span>',
            'accepted'  => '<span class="badge badge-success">Accepted</span>',
            'rejected'  => '<span class="badge badge-danger">Rejected</span>',
            'expired'   => '<span class="badge badge-secondary">Expired</span>',
            'converted' => '<span class="badge badge-primary">Converted to Order</span>',
            default     => '<span class="badge badge-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}
