<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewSchedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_schedule';

    protected $fillable = [
        'id_lowongan',
        'id_company',
        'id_lamaran',
        'tempat',
    ];

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class, 'id_lowongan');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function lamaran(): BelongsTo
    {
        return $this->belongsTo(Lamaran::class, 'id_lamaran');
    }
}
