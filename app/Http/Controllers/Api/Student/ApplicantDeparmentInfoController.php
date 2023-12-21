<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use Illuminate\Http\Request;

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
            'department_id' => Program::where('id', $request->get('program_id'))->value('department_id'),
        ]);

        return response()->json([
            'success' => true,
            'data' => $student,
        ], 200);
    }
}
