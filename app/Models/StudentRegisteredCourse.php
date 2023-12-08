<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Student;
use App\Models\SemesterCourse;
use App\Models\Lecturer;


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
        'total_mark',
        'semester_course_id',
        'order',


    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function semester_course()
    {
        return $this->belongsTo(SemesterCourse::class);
    }
}
