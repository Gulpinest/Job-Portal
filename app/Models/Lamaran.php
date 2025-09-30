<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lamaran extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_lamaran';

    protected $fillable = [
        'id_pelamar',
        'id_lowongan',
        'cv',
        'status_ajuan',
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class, 'id_pelamar');
    }

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class, 'id_lowongan');
    }

    public function statusLamarans(): HasMany
    {
        return $this->hasMany(StatusLamaran::class, 'id_lamaran');
    }

    public function interviewSchedules(): HasMany
    {
        return $this->hasMany(InterviewSchedule::class, 'id_lamaran');
    }
}
