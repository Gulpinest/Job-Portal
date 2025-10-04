<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Roles extends Model
{
    //
    use HasFactory;

    protected $table = "roles";

    protected $fillable = [
        "name",
        "display_name",
        "description",
        "is_active"
    ];
}
