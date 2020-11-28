<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave_record extends Model
{
    use HasFactory;

    protected $fillable = [
        'seq',
        'user_id',
        'date_start',
        'date_end',
        'leave_type',
        'detail',
        'status_id'
    ];

    public $timestamps = false;
    protected $table = "leave_record";
}
