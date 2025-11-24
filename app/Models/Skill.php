<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_skill';

    protected $fillable = [
        'nama_skill',
        'deskripsi',
    ];

    /**
     * Relationship: Skill has many Pelamars (many-to-many through pelamar_skill)
     */
    public function pelamars(): BelongsToMany
    {
        return $this->belongsToMany(
            Pelamar::class,
            'pelamar_skill',
            'id_skill',
            'id_pelamar',
            'id_skill',
            'id_pelamar'
        )
        ->withPivot('level', 'years_experience')
        ->withTimestamps();
    }
}
