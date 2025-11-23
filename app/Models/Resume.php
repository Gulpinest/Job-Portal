<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_resume';

    protected $fillable = [
        'id_pelamar',
        'nama_resume',
        'skill',
        'pendidikan_terakhir',
        'ringkasan_singkat',
        'file_resume',
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class, 'id_pelamar');
    }
}
