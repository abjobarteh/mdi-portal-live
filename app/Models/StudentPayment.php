<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class StudentPayment extends Model
{
    use HasFactory;

    public $fillable = [
        'student_id',
        'semester_id',
        'amount_paid'
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
