<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionStatus extends Model
{
    use HasFactory;

    public $fillable = [
        'admission_status'
    ];
}
