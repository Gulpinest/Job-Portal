<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lowongan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_lowongan';

    protected $fillable = [
        'id_company',
        'judul',
        'posisi',
        'deskripsi',
        'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function lamarans(): HasMany
    {
        return $this->hasMany(Lamaran::class, 'id_lowongan');
    }

    public function interviewSchedules(): HasMany
    {
        return $this->hasMany(InterviewSchedule::class, 'id_lowongan');
    }
}
