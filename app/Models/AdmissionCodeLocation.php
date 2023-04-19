<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdmissionCode;

class AdmissionCodeLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_name',
        'semester',
        'total_number',
        'price',
        'total_remains'
    ];

    public function admissionCodes()
    {
        return $this->hasMany(AdmissionCode::class);
    }
}
