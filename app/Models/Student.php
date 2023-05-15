<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ApplicantEducation;
use App\Models\Department;


class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'gender',
        'dob',
        'marital_status',
        'nationality',
        'employment_status',
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
        'personal_info_completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educations()
    {
        return $this->hasMany(ApplicantEducation::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
