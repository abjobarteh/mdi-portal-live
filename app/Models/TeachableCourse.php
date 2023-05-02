<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lecturer;

class TeachableCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'lecturer_id',
    ];

    public function lecturers()
    {
        return $this->belongsToMany(Lecturer::class);
    }
}
