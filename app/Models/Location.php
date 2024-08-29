<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\ProgramDuration;



class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_name',
        'location_code',
        'location_id'
    ];
    
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_location')
                    ->withPivot('day', 'start_time', 'end_time')
                    ->withTimestamps();
    }
}
