<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'semester_id',
        'lecturer_id',
    ];
}
