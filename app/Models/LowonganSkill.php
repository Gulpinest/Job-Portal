<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowonganSkill extends Model
{
    use HasFactory;

    protected $table = 'lowongan_skill';
    protected $fillable = ['id_lowongan', 'nama_skill'];

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'id_lowongan', 'id_lowongan');
    }
}

