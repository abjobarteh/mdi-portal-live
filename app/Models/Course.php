<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\SemesterCourse;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'course_name',
        'program_id'
    ];


    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function semester_courses()
    {
        return $this->hasMany(SemesterCourse::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'course_location')
                    ->withPivot('day', 'start_time', 'end_time')
                    ->withTimestamps();
    }
}
