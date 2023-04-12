<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;

class ProgramDuration extends Model
{
    use HasFactory;

    protected $fillable = [
        'duration',
        'description',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
