<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'nama_package',
        'price',
        'job_limit',
        'duration_months',
        'description',
    ];

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class, 'package_id', 'id');
    }
}
