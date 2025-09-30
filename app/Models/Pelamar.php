<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelamar extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pelamar';

    protected $fillable = [
        'id_user',
        'nama_pelamar',
        'status_pekerjaan',
        'no_telp',
        'alamat',
        'jenis_kelamin',
        'tgl_lahir',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class, 'id_pelamar');
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class, 'id_pelamar');
    }

    public function lamarans(): HasMany
    {
        return $this->hasMany(Lamaran::class, 'id_pelamar');
    }
}
