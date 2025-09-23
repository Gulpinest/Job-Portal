<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'email',
        'password_user',
    ];

    public function pelamar(): HasOne
    {
        return $this->hasOne(Pelamar::class, 'id_user');
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id_user');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class, 'id_user');
    }
}
