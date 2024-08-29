<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLocation extends Model
{
    
    protected $fillable = [
        'location_id',
        'course_id',
        'day',
        'start_time',
        'end_time'
    ];

    use HasFactory;
}
