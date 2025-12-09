<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_company';

    protected $fillable = [
        'id_user',
        'nama_perusahaan',
        'alamat_perusahaan',
        'no_telp_perusahaan',
        'desc_company',
        'package_id',
        'subscription_date',
        'subscription_expired_at',
        'is_verified',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'subscription_date' => 'datetime',
        'subscription_expired_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function lowongans(): HasMany
    {
        return $this->hasMany(Lowongan::class, 'id_company');
    }

    public function interviewSchedules(): HasMany
    {
        return $this->hasMany(InterviewSchedule::class, 'id_company');
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class, 'company_id', 'id_company');
    }

    /**
     * Scope: Get only verified companies
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope: Get only unverified companies
     */
    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function jobs():hasMany
    {
        return $this->hasMany(Lowongan::class, 'id_company', 'id_company');
    }

    /**
     * Check if company's subscription is currently active
     */
    public function isSubscriptionActive(): bool
    {
        // If no subscription expiry date, subscription is not active (unless it's free package)
        if (!$this->subscription_expired_at) {
            return false;
        }

        // Check if subscription has not expired yet
        return $this->subscription_expired_at->isFuture();
    }

    /**
     * Get remaining subscription duration in months
     */
    public function getRemainingDurationMonths(): int
    {
        if (!$this->subscription_expired_at) {
            return 0;
        }

        // Calculate months between now and expiry date
        $now = now();
        $expiry = $this->subscription_expired_at;

        if ($expiry->lte($now)) {
            return 0; // Subscription expired
        }

        // Calculate the difference in months
        $months = $now->diffInMonths($expiry, false);

        // If there are remaining days after the month calculation, add 1
        if ($now->copy()->addMonths($months)->lte($expiry)) {
            $days = $now->copy()->addMonths($months)->diffInDays($expiry);
            if ($days > 0) {
                return $months + 1;
            }
        }

        return max(1, $months); // Minimum 1 month if subscription is active
    }
}
