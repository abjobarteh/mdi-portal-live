<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterCourseFile extends Model
{
    use HasFactory;

    public $fillable = [
        'semester_course_id',
        'file_name',
        'file_title'
    ];
}
