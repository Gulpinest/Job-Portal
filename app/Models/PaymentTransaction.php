<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'package_id',
        'transaction_number',
        'amount',
        'payment_status',
        'va_number',
        'payment_url',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Get the company that owns the payment transaction.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id_company');
    }

    /**
     * Get the package for this payment transaction.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    /**
     * Check if payment is paid.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if payment is expired.
     */
    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast() && !$this->isPaid();
    }

    /**
     * Check if payment is still pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending' && !$this->isExpired();
    }
}
