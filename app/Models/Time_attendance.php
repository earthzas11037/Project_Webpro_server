<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time_attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'seq',
        'user_id',
        'date',
        'time_in',
        'time_out'
    ];

    public $timestamps = false;
    protected $table = "time_attendance";
}
