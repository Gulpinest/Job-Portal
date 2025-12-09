<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterviewSchedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_lowongan',
        'id_lamaran',
        'waktu_jadwal',
        'lokasi',
        'type',
        'catatan',
        'status',
    ];

    protected $casts = [
        'waktu_jadwal' => 'datetime',
    ];

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class, 'id_lowongan');
    }

    public function lamaran(): BelongsTo
    {
        return $this->belongsTo(Lamaran::class, 'id_lamaran');
    }

    /**
     * Get all accepted lamarans for this interview's lowongan
     * This is a query builder method, not a direct relationship
     */
    // public function lamarans()
    // {
    //     return Lamaran::where('id_lowongan', $this->id_lowongan)
    //                   ->where('status_ajuan', 'Accepted')
    //                   ->get();
    // }

    public function lamarans(): HasMany
    {
        // Parameter 2: Foreign Key di tabel 'lamarans' (id_lowongan)
        // Parameter 3: Local Key di tabel 'interview_schedules' (id_lowongan)
        return $this->hasMany(Lamaran::class, 'id_lowongan', 'id_lowongan')
                    ->where('status_ajuan', 'Accepted');
    }

    /**
     * Get accepted lamarans count
     */
    public function getAcceptedLamaransCountAttribute()
    {
        return Lamaran::where('id_lowongan', $this->id_lowongan)
                      ->where('status_ajuan', 'Accepted')
                      ->count();
    }
}
