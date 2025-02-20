<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdmissionCode;
use App\Models\Semester;

class AdmissionCodeLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_name',
        'semester_id',
        'total_number',
        'price',
        'total_remains',
        'user_id',
    ];

    public function admissionCodes()
    {
        return $this->hasMany(AdmissionCode::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
