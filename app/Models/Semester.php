<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Session;


class Semester extends Model
{
    use HasFactory;

    public $fillable = [
        'semester_name',
        'is_current_semester',
        'session_id',
        'next_semester',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
}
