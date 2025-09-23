<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
