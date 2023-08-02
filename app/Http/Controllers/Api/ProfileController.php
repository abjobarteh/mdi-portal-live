<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApplicantCertificate;
use App\Models\ApplicantEducation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->role_id == 4) {
            $student = User::leftJoin('students', 'users.id', '=', 'students.user_id')
                ->leftJoin('admission_code_verifications', 'users.id', '=', 'admission_code_verifications.user_id')
                ->leftJoin('registration_verification_tokens', 'users.id', '=', 'registration_verification_tokens.user_id')
                ->leftJoin('departments', 'students.department_id', '=', 'departments.id') // Join the departments table
                ->join('programs', 'students.program_id', '=', 'programs.id') // Join the departments table
                ->where('users.id', auth()->user()->id)
                ->select('users.*', 'programs.name as program_name', 'students.gender', 'students.id', 'students.phonenumber',  'students.dob',  'students.address',  'students.nationality', 'students.email',  'students.mat_number', 'students.employment_status', 'students.user_id', 'students.is_applicant', 'students.department_id', 'departments.name', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at', 'registration_verification_tokens.student_email_verified_at',)
                ->first();
            $student['education'] = ApplicantEducation::where('user_id', $student->user_id)->get();
            $student['certificates'] = ApplicantCertificate::where('user_id', $student->user_id)->get();



            return $student;
        } else if (auth()->user()->role_id == 6) {
            return User::where('id', auth()->user()->id)->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
