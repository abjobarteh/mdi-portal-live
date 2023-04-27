<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'user_id',
        'address',
        'phonenumber'
    ];

    // firstname');
    //         $table->string('lastname');
    //         $table->string('username');
    //         $table->string('email')->unique();
    //         $table->foreignId('user_id'

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function semesterCourses()
    {
        return $this->hasMany(SemesterCourse::class)
            ->whereHas('semester', function ($query) {
                $query->where('is_current_semester', 1);
            })->with('course');

        // return $this->hasMany(SemesterCourse::class)
        // ->join('semesters', 'semesters.id', '=', 'semester_courses.semester_id')
        // ->where('semesters.is_current_semester', 1);
    }
}
