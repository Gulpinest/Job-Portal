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
        'lokasi_kantor',
        'gaji',
        'tipe_kerja',
        'deskripsi',
        'persyaratan_tambahan',
        'status',
    ];

    protected $appends = ['pelamar_baru_count'];

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

    public function interviewSchedule()
    {
        return $this->hasOne(InterviewSchedule::class, 'id_lowongan');
    }

    public function skills()
    {
        return $this->hasMany(LowonganSkill::class, 'id_lowongan', 'id_lowongan');
    }

    /**
     * Get count of new/pending lamarans
     * Uses status_ajuan = 'Pending' to identify new applications
     */
    public function getPelamarBaruCountAttribute()
    {
        return $this->lamarans()
            ->where('status_ajuan', 'Pending')
            ->count();
    }

    // Filter berdasarkan kecocokan skill pelamar
    public function scopeMatchSkills($query, $pelamarSkills)
    {
        return $query->whereHas('skills', function ($q) use ($pelamarSkills) {
            $q->whereIn('nama_skill', $pelamarSkills);
        });
    }

    // Filter berdasarkan pencarian umum (judul, posisi)
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('judul', 'like', '%' . $keyword . '%')
            ->orWhere('posisi', 'like', '%' . $keyword . '%');
        });
    }

    // Filter berdasarkan status (Open / Closed)
    public function scopeStatus($query, $status)
    {
        if (!empty($status)) {
            return $query->where('status', $status);
        }

        return $query;
    }

}
