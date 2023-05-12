<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\ApplicantCertificate;
use App\Models\ApplicantEducation;
use App\Models\Student;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\StudentT;

class ApplicantDeclarationController extends Controller
{

    public function submitApplication(Request $request)
    {

        $student = Student::where('user_id', auth()->user()->id)->first()
            ->update(['application_completed' => 1]);

        return response()->json(['success' => true]);
    }
}
