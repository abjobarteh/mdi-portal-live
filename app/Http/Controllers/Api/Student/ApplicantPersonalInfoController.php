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
        // $validator = Validator::make($request->all(), [
        //     'id' => 'required',
        //     'gender' => 'required',
        //     'dob' => 'required',
        //     'marital_status' => 'required',
        //     'nationality' => 'required',
        //     'address' => 'required',
        //     'employment_status' => 'required',
        //     'phonenumber' => 'required',
        //     'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'errors' => $validator->errors(),
        //     ], 422);
        // }
        $validatedData = $request->validate([
            'id' => 'required',
            'gender' => 'required',
            'dob' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    $minDate = now()->subYears(18)->format('Y-m-d');
                    if ($value > $minDate) {
                        $fail('The date of birth must be at least 18 years ago.');
                    }
                },
            ],            'marital_status' => 'required',
            'nationality' => 'required',
            'address' => 'required',
            'employment_status' => 'required',
            'phonenumber' => [
                'required',
                'regex:/^[2-9][0-9]{6}$/',
            ],
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        $student = Student::where('user_id', $request->get('id'))->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ], 404);
        }

        $studentData = [
            'gender' => $request->get('gender'),
            'dob' => $request->get('dob'),
            'marital_status' => $request->get('marital_status'),
            'nationality' => $request->get('nationality'),
            'address' => $request->get('address'),
            'employment_status' => $request->get('employment_status'),
            'phonenumber' => $request->get('phonenumber'),
            'personal_info_completed' => 1,
        ];

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/profiles'), $imageName);

            $studentData['profile_image'] = 'images/profiles/' . $imageName;
        }

        if (!empty($request->get('middlename'))) {
            $studentData['middlename'] = $request->get('middlename');
        }

        $student->update($studentData);

        return response()->json([
            'success' => true,
            'data' => $student,
        ], 200);
    }
}
