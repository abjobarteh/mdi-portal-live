<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\Course;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function courses()
    {
        return $this->hasManyThrough(Course::class, Program::class);
    }
}
