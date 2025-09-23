<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusLamaran extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_status_lamaran';

    protected $fillable = [
        'id_lamaran',
        'status_lamaran',
    ];

    public function lamaran(): BelongsTo
    {
        return $this->belongsTo(Lamaran::class, 'id_lamaran');
    }
}
