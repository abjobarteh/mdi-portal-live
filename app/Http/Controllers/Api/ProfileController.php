<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApplicantCertificate;
use App\Models\ApplicantEducation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                ->leftJoin('programs', 'students.program_id', '=', 'programs.id') // Join the departments table
                ->where('users.id', auth()->user()->id)
                ->select('users.*', 'programs.name as program_name', 'students.profile_image', 'students.gender', 'students.id', 'students.phonenumber',  'students.dob',  'students.address',  'students.nationality', 'students.email',  'students.mat_number', 'students.employment_status', 'students.user_id', 'students.is_applicant', 'students.department_id', 'departments.name', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at', 'registration_verification_tokens.student_email_verified_at',)
                ->first();
            $student['education'] = ApplicantEducation::where('user_id', $student->user_id)->get();
            $student['certificates'] = ApplicantCertificate::where('user_id', $student->user_id)->get();



            return $student;
        } else if (auth()->user()->role_id == 6) {
            return User::where('id', auth()->user()->id)->first();
        } else if (auth()->user()->role_id == 2) {
            return User::where('id', auth()->user()->id)->first();
        } else if (auth()->user()->role_id == 3) {
            return User::where('id', auth()->user()->id)->first();
        } else if (auth()->user()->role_id == 5) {
            return User::where('id', auth()->user()->id)->first();
        } else if (auth()->user()->role_id == 1) {
            return User::where('id', auth()->user()->id)->first();
        } else if (auth()->user()->role_id == 7) {
            return User::where('id', auth()->user()->id)->first();
        }else if (auth()->user()->role_id == 8) {
            return User::where('id', auth()->user()->id)->first();
        }
    }

    public function studentDetail($id)
    {
        $student = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->leftJoin('admission_code_verifications', 'users.id', '=', 'admission_code_verifications.user_id')
            ->leftJoin('registration_verification_tokens', 'users.id', '=', 'registration_verification_tokens.user_id')
            ->leftJoin('departments', 'students.department_id', '=', 'departments.id') // Join the departments table
            ->leftJoin('programs', 'students.program_id', '=', 'programs.id') // Join the departments table
            ->where('users.id', $id)
            ->select('users.*', 'programs.name as program_name', 'students.gender', 'students.id', 'students.phonenumber',  'students.dob',  'students.address',  'students.nationality', 'students.email',  'students.mat_number', 'students.employment_status', 'students.user_id', 'students.is_applicant', 'students.department_id', 'departments.name', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at', 'registration_verification_tokens.student_email_verified_at',)
            ->first();
        $student['education'] = ApplicantEducation::where('user_id', $student->user_id)->get();
        $student['certificates'] = ApplicantCertificate::where('user_id', $student->user_id)->get();



        return response()->json([
            'status' => 200,
            'result' => $student
        ]);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        $user = Auth::user(); // Get the authenticated user
        $photo = $request->file('photo');
        $photoName = time() . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path('images/avatars'), $photoName); // Store the uploaded file in the 'uploads' folder

        // Update the user's photo in the database
        $user->picture = $photoName;
        $user->save();

        return response()->json(['photo' => $photoName]);
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
