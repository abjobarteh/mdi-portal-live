<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRegisteredCourse extends Model
{
    use HasFactory;

    public $fillable = [
        'student_id',
        'lecturer_id',
        'semester_id',
        'course_id',
        'test_mark',
        'exam_mark',
        'total_mark'

    ];
}
