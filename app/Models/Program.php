<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\ProgramDuration;



class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'program_abbreviation',
        'fee',
        'department_id',
        'program_duration_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function duration()
    {
        return $this->belongsTo(ProgramDuration::class, 'program_duration_id');
    }


    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
