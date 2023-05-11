<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\ApplicantEducation;
use Illuminate\Http\Request;

class ApplicantEducationController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'schools' => 'required|array',
            'schools.*.name' => 'required|string|max:255',
            'schools.*.startDate' => 'required|date',
            'schools.*.endDate' => 'required|date',
            'schools.*.certificate' => 'required|string|max:255',
        ]);

        foreach ($validatedData['schools'] as $schoolData) {
            if (!isset($schoolData['name']) || empty($schoolData['name'])) {
                return response()->json(['error' => 'School name is missing.'], 400);
            }
            $school = new ApplicantEducation([
                'user_id' => auth()->user()->id,
                'start_date' => $schoolData['startDate'],
                'end_date' => $schoolData['endDate'],
                'school_name' => $schoolData['name'],
                'certificate' => $schoolData['certificate'],

            ]);

            $school->save();
        }

        return response()->json(['message' => 'Certificates created successfully.']);
    }
}
