<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use Illuminate\Http\Request;

class ApplicantDeparmentInfoController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'department_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $student = Student::where('user_id', $request->get('id'))->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ], 404);
        }

        $student->update([
            'department_id' => 1,
        ]);

        return response()->json([
            'success' => true,
            'data' => $student,
        ], 200);
    }
}
