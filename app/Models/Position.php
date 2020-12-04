<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'position_id',
        'position_eng',
        'position_th'
    ];

    public $timestamps = false;
    protected $table = "position";
}
