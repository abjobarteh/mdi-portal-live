<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'certificate',
        'certificate_name',
    ];
}
