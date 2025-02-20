<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ApplicantEducation;
use App\Models\Department;
use App\Models\StudentPayment;
use Illuminate\Database\Eloquent\Casts\Attribute;



class Student extends Model
{
    use HasFactory;

    protected $appends = ['total_amount_paid', 'remaining_balance'];

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
        'program_id',
        'phonenumber',
        'is_applicant',
        'application_completed',
        'accepted',
        'application_completed',
        'personal_info_completed',
        'remaining',
        'payment_type',
        'balance',
        'mat_number',
        'department_id',
        'acceptance_status',
        'is_sponsored',
        'profile_image',
        'eme_name',
        'eme_numbr',
        'employee',
        'empaddr',
        'empcontact',
        'waive',
        'semester_name',
        'apply_new_course'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educations()
    {
        return $this->hasMany(ApplicantEducation::class);
    }

    public function payments()
    {
        return $this->hasMany(StudentPayment::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    protected function totalAmountPaid(): Attribute
    {
        return new Attribute(
            get: fn () => $this->payments->sum('amount_paid')
        );
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    protected function remainingBalance(): Attribute
    {
        return new Attribute(
            get: fn () => $this->program ? $this->program->fee - $this->totalAmountPaid : null
        );
    }

  
}
