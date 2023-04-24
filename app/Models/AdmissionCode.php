<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdmissionCodeLocation;


class AdmissionCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_code',
        'admission_code_location_id',
        'is_sold',
        'price',
    ];

    public function admissionCodeLocation()
    {
        return $this->belongsTo(AdmissionCodeLocation::class, 'admission_code_location_id');
    }
}
