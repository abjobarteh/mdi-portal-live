<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Mail\AcceptedApplicationEmail;
use App\Mail\RejectedApplicationEmail;
use App\Models\ApplicantCertificate;
use App\Models\ApplicantEducation;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ApplicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function acceptedApplications()
    {

        $students = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->leftJoin('admission_code_verifications', 'users.id', '=', 'admission_code_verifications.user_id')
            ->leftJoin('departments', 'students.department_id', '=', 'departments.id') // Join the departments table
            ->select('users.*', 'students.gender',  'students.phonenumber',  'students.dob',  'students.address',  'students.nationality', 'students.email',  'students.employment_status', 'students.user_id', 'students.is_applicant', 'students.department_id', 'departments.name', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at',)
            ->where('role_id', 4)
            ->where('application_completed', 1)->where('accepted', 'accepted')
            ->paginate(10);
        foreach ($students as $student) {
            $student['education'] = ApplicantEducation::where('user_id', $student->id)->get();
            $student['certificates'] = ApplicantCertificate::where('user_id', $student->id)->get();
        }

        return response()->json([
            'status' => 200,
            'result' => $students
        ]);
    }

    public function rejectedApplications()
    {

        $students = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->leftJoin('admission_code_verifications', 'users.id', '=', 'admission_code_verifications.user_id')
            ->leftJoin('departments', 'students.department_id', '=', 'departments.id') // Join the departments table
            ->select('users.*', 'students.gender',  'students.phonenumber',  'students.dob',  'students.address',  'students.nationality', 'students.email',  'students.employment_status', 'students.user_id', 'students.is_applicant', 'students.department_id', 'departments.name', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at',)
            ->where('role_id', 4)
            ->where('application_completed', 1)->where('accepted', 'rejected')
            ->paginate(10);
        foreach ($students as $student) {
            $student['education'] = ApplicantEducation::where('user_id', $student->id)->get();
            $student['certificates'] = ApplicantCertificate::where('user_id', $student->id)->get();
        }

        return response()->json([
            'status' => 200,
            'result' => $students
        ]);
    }

    public function incomingApplications(Request $request)
    {

        $students = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->leftJoin('admission_code_verifications', 'users.id', '=', 'admission_code_verifications.user_id')
            ->leftJoin('departments', 'students.department_id', '=', 'departments.id')
            ->select('users.*', 'students.gender',  'students.phonenumber',  'students.dob',  'students.address',  'students.nationality', 'students.email',  'students.employment_status',  'students.user_id', 'students.is_applicant', 'students.department_id', 'departments.name', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at')
            ->where('role_id', 4)
            ->where('application_completed', 1)
            ->where('accepted', 'pending')
            ->when($request->has('userId'), function ($query) use ($request) {
                $query->where('users.id', $request->userId);
            })
            ->paginate(10);

        foreach ($students as $student) {
            $student['education'] = ApplicantEducation::where('user_id', $student->id)->get();
            $student['certificates'] = ApplicantCertificate::where('user_id', $student->id)->get();
        }

        return response()->json([
            'status' => 200,
            'result' => $students
        ]);
    }

    public function acceptStudentApplication(Request $request)
    {
        // interviewDate
        $student = Student::where('user_id', $request->get('userId'))->first();
        $student->update(['is_applicant' => 0, 'accepted' => 'accepted', 'mat_number' => $this->generateStudentNumber()]);

        Mail::to($student->email)->send(new AcceptedApplicationEmail($request->interviewDate));

        // when the student application is accepted, then he needs a matnumber
        return response()->json([
            'status' => 200,
            'result' => 'Accepted Successful'
        ]);
    }

    private function generateStudentNumber()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Determine the month range and assign the fifth digit accordingly
        $fifthDigit = ($currentMonth >= 1 && $currentMonth <= 6) ? 1 : 2;

        // Retrieve the last student number from the database and increment it by one
        $lastStudent = Student::whereNotNull('mat_number')->orderBy('id', 'desc')->first();
        $lastNumber = ($lastStudent) ? intval(substr($lastStudent->mat_number, -4)) + 1 : 1;
        $lastNumber = str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        // Generate the complete student number
        $studentNumber = $currentYear . $fifthDigit . sprintf('%04d', $lastNumber);

        return $studentNumber;
    }

    public function rejectStudentApplication(Request $request)
    {
        $student = Student::where('user_id', $request->get('userId'))->first();

        $student->update(['accepted' => 'rejected']);

        // Send email to the student
        Mail::to($student->email)->send(new RejectedApplicationEmail());

        return response()->json([
            'status' => 200,
            'result' => 'Application Rejected Successfully'
        ]);
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
