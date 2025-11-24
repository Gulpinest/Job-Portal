<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * Relationship: Pelamar has many Skills (many-to-many through pelamar_skill)
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(
            Skill::class,
            'pelamar_skill',
            'id_pelamar',
            'id_skill',
            'id_pelamar',
            'id_skill'
        )
        ->withPivot('level', 'years_experience')
        ->withTimestamps();
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
