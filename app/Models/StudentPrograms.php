<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPrograms extends Model
{
    use HasFactory;
    protected $table ='student_programs';
    public $fillable = [
       'student_id',
       'program_id',
       'semester_name'
    ];

    
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function students()
    {
        return $this->belongsTo(Student::class);
    }
    

}
