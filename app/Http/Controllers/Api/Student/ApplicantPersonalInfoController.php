<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\ApplicantEducation;
use App\Models\Student;
use Illuminate\Http\Request;

class ApplicantPersonalInfoController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'middlename' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'marital_status' => 'required',
            'nationality' => 'required',
            'address' => 'required',
            'employment_status' => 'required',
            'phonenumber' => 'required',
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
            'middlename' => $request->get('middlename'),
            'gender' => $request->get('gender'),
            'dob' => $request->get('dob'),
            'marital_status' => $request->get('marital_status'),
            'nationality' => $request->get('nationality'),
            'address' => $request->get('address'),
            'employment_status' => $request->get('employment_status'),
            'phonenumber' => $request->get('phonenumber'),
            'personal_info_completed' => 1,
        ]);

        return response()->json([
            'success' => true,
            'data' => $student,
        ], 200);
    }
}
