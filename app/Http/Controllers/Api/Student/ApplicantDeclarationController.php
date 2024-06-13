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

        if (is_null(auth()->user()->id)) {
            return response()->json(['message' => 'Student not found.'], 404);
        } else if (is_null(ApplicantEducation::where('user_id', auth()->user()->id)->first())) {
            return response()->json(['message' => 'Please fill in the academic information section.'], 422);
        } else if (is_null(Student::where('user_id', auth()->user()->id)->value('dob'))) { // or other fields here
            return response()->json(['message' => 'Please fill in the personal info section.'], 422);
        } elseif (is_null(Student::where('user_id', auth()->user()->id)->value('program_id'))) {
            return response()->json(['message' => 'Please fill in the course details section.'], 422);
        } elseif (is_null(ApplicantCertificate::where('user_id', auth()->user()->id)->first())) {
            return response()->json(['message' => 'Please fill in the upload document section.'], 422);
        }
        $student = Student::where('user_id', auth()->user()->id)->first()
            ->update(['application_completed' => 1]);

        return response()->json(['success' => true]);
    }
}
