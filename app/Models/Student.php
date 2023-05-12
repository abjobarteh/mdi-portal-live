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
        'firstname',
        'lastname',
        'username',
        'address',
        'email',
        'user_id',
        'department_id',
        'phonenumber',
        'is_applicant',
        'application_completed',
        'accepted',
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
