<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Ini penting agar kita bisa menyimpan semua kolom (id_company, amount, dll)
    protected $guarded = []; 
    
    // Opsional: Definisikan relasi jika nanti butuh
    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}