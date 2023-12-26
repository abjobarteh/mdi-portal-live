<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsoredStudents extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'scholarship_provider',
        'scholarship_name',
        'start_date',
        'end_date',
        'scholarship_file',
        'scholarship_amount'
    ];
}
