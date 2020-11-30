<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'seq',
        'user_id',
        'topic',
        'detail',
        'date_end',
        'date_start',
        'calendar_type',
    ];

    public $timestamps = false;
    protected $table = "calendar";
}
