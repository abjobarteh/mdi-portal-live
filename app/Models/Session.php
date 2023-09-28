<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Semester;

class Session extends Model
{
    use HasFactory;

    public $fillable = [
        'session_name',
        'is_current_session',
        'next_session',
        'session_start',
        'session_end'
    ];


    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    // Define the 'saving' event listener
    protected static function boot()
    {
        parent::boot();

        // Listen for the 'saving' event
        static::saving(function ($model) {
            // Format session_start and session_end before saving
            $model->session_start = date('F Y', strtotime($model->session_start));
            $model->session_end = date('F Y', strtotime($model->session_end));
        });
    }
}
