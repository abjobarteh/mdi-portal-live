<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ApplicantEducation;


class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_completed',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educations()
    {
        return $this->hasMany(ApplicantEducation::class);
    }
}
