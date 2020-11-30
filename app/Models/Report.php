<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'seq',
        'user_id',
        'working_days',
        'working_hours',
        'ot',
        'sum_salary',
        'date'
    ];

    public $timestamps = false;
    protected $table = "report";
}
