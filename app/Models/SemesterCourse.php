<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Semester;
use App\Models\Course;
use App\Models\Lecturer;

class SemesterCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'semester_id',
        'lecturer_id',
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}
