<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionCodeVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'verified_at'
    ];
}
