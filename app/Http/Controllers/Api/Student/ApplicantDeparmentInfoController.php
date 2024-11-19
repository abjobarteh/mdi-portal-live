<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\StudentRegisteredCourse;

class ApplicantDeparmentInfoController extends Controller
{

    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'id' => 'required',
        //     'program_id' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'errors' => $validator->errors(),
        //     ], 422);
        // }
        $validatedData = $request->validate([
            'id' => 'required',
            'program_id' => 'required',
            'semester_name' => 'required',
        ]);

        $student = Student::where('user_id', $request->get('id'))->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ], 404);
        }

        $student->update([
            'program_id' => $request->get('program_id'),
            'semester_name' => $request->get('semester_name'),
            'department_id' => Program::where('id', $request->get('program_id'))->value('department_id'),
        ]);

        return response()->json([
            'success' => true,
            'data' => $student,
        ], 200);
    }


    public function changeprogram(Request $request)
    {

        
        $studentId = $request->input('studentId');
        $programId = $request->input('programId');

        // Check the number of rows in student_registered_courses for the given studentId
        $registeredCoursesCount = StudentRegisteredCourse::where('student_id', $studentId)
            ->count();


                 $checkprogramid = Program::where('id', $programId)
            ->count();
        
        // Perform the update only if the count is not 10

        if ($registeredCoursesCount > 10) {
            return response()->json([
                'success' => false,
                'error' => true,
                'errorMessage' => 'Sorry, cannot change programs'
            ]);
        }

        if($checkprogramid == 0){
            return response()->json([
                'success' => false,
                'error' => true,
                'errorMessage' => 'Select Program'
            ]);
        }
        
        $student = Student::where('id', $studentId)->first();

        if ($student) {
            $student->update([
                'program_id' => $programId,
                'department_id' => Program::where('id', $programId)->value('department_id'),
            ]);

            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => true,
            'errorMessage' => 'Failed to update student'
        ]);
    }

}
