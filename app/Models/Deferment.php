<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deferment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'deferment_reason',
        'semester_id',
    ];
}
