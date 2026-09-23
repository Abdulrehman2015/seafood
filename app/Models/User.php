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
        'preferred_locale',
        'avatar',
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
        'marketing_opt_in',
        'marketing_channels',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'email_verified_at',
        'email_otp_code',
        'email_otp_expires_at',
        'email_otp_attempts',
        'email_otp_sent_at',
        'email_otp_resends',
        'email_otp_blocked_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'approved_at'          => 'datetime',
            'password'             => 'hashed',
            'marketing_opt_in'     => 'boolean',
            'email_otp_expires_at' => 'datetime',
            'email_otp_sent_at'    => 'datetime',
            'email_otp_attempts'   => 'integer',
            'email_otp_resends'    => 'integer',
            'email_otp_blocked_at' => 'datetime',
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
            'walkin'    => 'Walk-in Customer',
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
            'walkin'    => 'badge-walkin',
            'wholesale' => 'badge-wholesale',
            'trading'   => 'badge-trading',
            'admin'     => 'badge-admin',
            default     => 'badge-default',
        };
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path($this->avatar))) {
            return asset($this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1d4ed8&color=ffffff&bold=true&size=128';
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= mb_substr($w, 0, 1);
        }
        return strtoupper($initials ?: 'U');
    }

    // ─── OTP Methods ──────────────────────────────────────────────────────────

    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function isOtpBlocked(): bool
    {
        return !is_null($this->email_otp_blocked_at);
    }

    public function hasUsedOtpResend(): bool
    {
        return (int) $this->email_otp_resends >= 1;
    }

    public function blockUserForOtpFailure(): void
    {
        $this->update([
            'email_otp_blocked_at' => now(),
            'email_otp_code'       => null,
            'approval_status'      => 'rejected',
            'rejection_reason'     => 'Account blocked due to multiple failed OTP verification attempts.',
        ]);
    }

    public function generateEmailOtp(bool $isResend = false): string
    {
        $otp = sprintf('%06d', random_int(100000, 999999));
        $data = [
            'email_otp_code'       => $otp,
            'email_otp_expires_at' => now()->addMinutes(10),
            'email_otp_attempts'   => 0,
            'email_otp_sent_at'    => now(),
        ];
        if ($isResend) {
            $data['email_otp_resends'] = (int) ($this->email_otp_resends ?? 0) + 1;
        }
        $this->update($data);
        return $otp;
    }

    public function isOtpExpired(): bool
    {
        if (!$this->email_otp_expires_at) {
            return true;
        }
        return now()->gt($this->email_otp_expires_at);
    }

    public function hasExceededOtpAttempts(): bool
    {
        return $this->email_otp_attempts >= 3;
    }

    public function recordFailedOtpAttempt(): int
    {
        $attempts = $this->email_otp_attempts + 1;
        $updates = ['email_otp_attempts' => $attempts];

        if ($attempts >= 3) {
            $updates['email_otp_code'] = null; // Invalidate OTP upon 3rd failed attempt
            
            // If user already used their 1 allowed resend and failed on the second OTP, block the account!
            if ($this->hasUsedOtpResend()) {
                $updates['email_otp_blocked_at'] = now();
                $updates['approval_status']      = 'rejected';
                $updates['rejection_reason']     = 'Account blocked due to multiple failed OTP verification attempts.';
            }
        }

        $this->update($updates);
        return max(0, 3 - $attempts);
    }

    public function clearEmailOtp(): void
    {
        $this->update([
            'email_otp_code'       => null,
            'email_otp_expires_at' => null,
            'email_otp_attempts'   => 0,
            'email_otp_sent_at'    => null,
            'email_otp_resends'    => 0,
            'email_otp_blocked_at' => null,
        ]);
    }

    public function unblockAndVerifyFromAdmin(): void
    {
        $this->approval_status      = 'approved';
        $this->approved_at          = $this->approved_at ?? now();
        $this->approved_by          = auth()->id() ?? $this->approved_by ?? 1;
        $this->rejection_reason     = null;
        $this->email_otp_blocked_at = null;
        $this->email_otp_attempts   = 0;
        $this->email_otp_resends    = 0;
        $this->email_otp_code       = null;
        $this->email_otp_expires_at = null;
        $this->email_otp_sent_at    = null;
        if (!$this->email_verified_at) {
            $this->email_verified_at = now();
        }
        $this->save();
    }
}

