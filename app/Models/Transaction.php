<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'package_id',
        'midtrans_order_id',
        'gross_amount',
        'snap_token',
        'payment_status',
        // Pastikan nama kolom di sini sama persis dengan yang ada di migration Anda
    ];
    // 👆👆👆 TAMBAHKAN KODE INI 👆👆👆

    // Relasi ke User (Company)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Package
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}