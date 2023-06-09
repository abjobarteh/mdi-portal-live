<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationVerificationToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_email_verified_at'
    ];
}
