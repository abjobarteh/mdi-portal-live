<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public $fillable = [
        'session_name',
        'is_current_session',
        'next_session',
    ];


    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }
}
