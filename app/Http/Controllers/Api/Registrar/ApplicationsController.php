<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Mail\EnrollmentApplicationEmail;
use App\Http\ConditionalApplicationEmail;
use App\Mail\AcceptedApplicationEmail;
use App\Mail\RejectedApplicationEmail;
use App\Mail\RevertApplicationMail;
use App\Models\ApplicantCertificate;
use App\Models\ApplicantEducation;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentAnounceMail;
use App\Mail\LecturerAnnounceMail;
use App\Models\AdmissionCode;
use App\Models\AdmissionCodeVerification;
use Illuminate\Support\Facades\DB;



class ApplicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function acceptedApplications(Request $request)
    {

        $students = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->leftJoin('admission_code_verifications', 'users.id', '=', 'admission_code_verifications.user_id')
            ->leftJoin('programs', 'students.program_id', '=', 'programs.id') // Join the departments table
            // ->select('users.*', 'students.gender',  'students.phonenumber',  'students.dob',  'students.address',  'students.nationality', 'students.email',  'students.employment_status', 'students.user_id', 'students.is_applicant', 'students.profile_image', 'programs.name as program_name', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at',)
            ->select('users.*', 'students.gender', 'students.id AS studentId', 'students.phonenumber', 'students.dob', 'students.address', 'students.nationality', 'students.email', 'students.employment_status', 'students.user_id', 'students.is_applicant', 'programs.name as program_name', 'students.profile_image', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at', 'students.eme_name', 'students.eme_numbr', 'students.employee', 'students.empaddr', 'students.empcontact', 'students.semester_name')

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

    public function viewAceptedApplicationDetails(Request $request)
    {

        $students = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->leftJoin('admission_code_verifications', 'users.id', '=', 'admission_code_verifications.user_id')
            ->leftJoin('programs', 'students.program_id', '=', 'programs.id') // Join the departments table
            // ->select('users.*', 'students.gender', 'students.id',  'students.phonenumber',  'students.dob',  'students.address',  'students.nationality', 'students.email',  'students.employment_status', 'students.user_id', 'students.is_applicant', 'students.profile_image', 'programs.name as program_name', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at',)
            ->select('users.*', 'students.gender', 'students.id AS studentId', 'students.phonenumber', 'students.dob', 'students.address', 'students.nationality', 'students.email', 'students.employment_status', 'students.user_id', 'students.is_applicant', 'programs.name as program_name', 'students.profile_image', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at', 'students.eme_name', 'students.eme_numbr', 'students.employee', 'students.empaddr', 'students.empcontact', 'students.semester_name')

            ->where('role_id', 4)
            ->where('application_completed', 1)->where('accepted', 'accepted')
            ->where('users.id', $request->get('userId'))
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


    public function getprogdept(Request $request)
    {


        $studentData = DB::table('students as a')
            ->join('departments as b', 'a.department_id', '=', 'b.id')
            ->join('programs as c', 'a.program_id', '=', 'c.id')
            ->select('b.name as department_name', 'c.name as program_name')  // Customize the columns you want
            ->where('a.id', '=', $request->get('student_id'))
            ->first();

        return response()->json([
            'status' => 200,
            'result' => $studentData
        ]);
    }
    public function rejectedApplications()
    {

        $students = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->leftJoin('admission_code_verifications', 'users.id', '=', 'admission_code_verifications.user_id')
            ->leftJoin('programs', 'students.program_id', '=', 'programs.id') // Join the departments table
            ->select('users.*', 'students.gender', 'students.profile_image', 'students.phonenumber', 'students.dob', 'students.address', 'students.nationality', 'students.email', 'students.employment_status', 'students.user_id', 'students.is_applicant', 'programs.name as program_name', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at', 'students.eme_name', 'students.eme_numbr', 'students.employee', 'students.empaddr', 'students.empcontact', 'students.semester_name')
            ->where('role_id', 4)
            ->where('application_completed', 1)->where('accepted', 'rejected')
            ->paginate(15);
        foreach ($students as $student) {
            $student['education'] = ApplicantEducation::where('user_id', $student->id)->get();
            $student['certificates'] = ApplicantCertificate::where('user_id', $student->id)->get();
        }

        return response()->json([
            'status' => 200,
            'result' => $students
        ]);
    }

    public function getrejectedApplications($id)
    {

        $students = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->leftJoin('admission_code_verifications', 'users.id', '=', 'admission_code_verifications.user_id')
            ->leftJoin('programs', 'students.program_id', '=', 'programs.id') // Join the departments table
            ->select('users.*', 'students.gender', 'students.id AS studentId', 'students.phonenumber', 'students.dob', 'students.address', 'students.nationality', 'students.email', 'students.employment_status', 'students.user_id', 'students.is_applicant', 'programs.name as program_name', 'students.profile_image', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at', 'students.eme_name', 'students.eme_numbr', 'students.semester_name')
            ->where('role_id', 4)
            ->where('application_completed', 1)->where('accepted', 'rejected')->where('students.user_id', $id)
            ->paginate(15);
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
            ->leftJoin('programs', 'students.program_id', '=', 'programs.id') // Join the departments table
            ->select('users.*', 'students.gender', 'students.id AS studentId', 'students.phonenumber', 'students.dob', 'students.address', 'students.nationality', 'students.email', 'students.employment_status', 'students.user_id', 'students.is_applicant', 'programs.name as program_name', 'students.profile_image', 'students.application_completed', 'students.personal_info_completed', 'students.accepted', 'admission_code_verifications.verified_at', 'students.eme_name', 'students.eme_numbr', 'students.employee', 'students.empaddr', 'students.empcontact', 'students.semester_name')
            ->where('role_id', 4)
            ->where('application_completed', 1)
            ->where('accepted', 'pending')
            ->when($request->has('userId'), function ($query) use ($request) {
                $query->where('users.id', $request->userId);
            })
            ->paginate(15);

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
        if ($student->apply_new_course == 1) {
            $studentName = $student->firstname . ' ' . $student->lastname;
            $studentNumber = $student->mat_number;
            $student->update(['is_applicant' => 0, 'accepted' => 'accepted', 'mat_number' => $studentNumber, 'acceptance_status' => 1, 'apply_new_course' => 0]);
            $orientaionDate = Carbon::parse($request->orientationDate);
            $commencementDate = Carbon::parse($request->commencementDate);
            Mail::to($student->email)->send(new AcceptedApplicationEmail($orientaionDate->format('jS F Y H:i:s A'), $commencementDate->format('jS F Y H:i:s A'), $studentNumber, $studentName, 1));
        } else {
            $studentName = $student->firstname . ' ' . $student->lastname;
            $studentNumber = $this->generateStudentNumber($student->id);
            $student->update(['is_applicant' => 0, 'accepted' => 'accepted', 'mat_number' => $studentNumber, 'acceptance_status' => 1]);
            $orientaionDate = Carbon::parse($request->orientationDate);
            $commencementDate = Carbon::parse($request->commencementDate);
            Mail::to($student->email)->send(new AcceptedApplicationEmail($orientaionDate->format('jS F Y H:i:s A'), $commencementDate->format('jS F Y H:i:s A'), $studentNumber, $studentName, 1));
        }



        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has accepted a student application');

        // when the student application is accepted, then he needs a matnumber
        return response()->json([
            'status' => 200,
            'result' => 'Accepted Successful'
        ]);
    }

    public function studentannouncement(Request $request)
    {

        ini_set('max_execution_time', 3600);

        $emails = Student::whereNotNull('mat_number')->pluck('email');

        foreach ($emails as $email) {
            try {
                Mail::to($email)->queue(new StudentAnounceMail($request->get('message')));
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
        }

        return response()->json([
            'status' => 200,
            'result' => 'Announcement queued successfully for students!'
        ]);
    }

    public function applicantannouncement(Request $request)
    {
        ini_set('max_execution_time', 3600);
        $emails = Student::where('application_completed', 0)->pluck('email');

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new StudentAnounceMail($request->get('message')));
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
        }

        return response()->json([
            'status' => 200,
            'result' => 'Announcement queued successfully for applicants!'
        ]);
    }


    public function searchIncomingapplicant(Request $request)
    {
        if (!$request->has('selectedItem') || !$request->has('advanceSearch')) {
            return response()->json(['error' => 'Invalid search parameters'], 400);
        }

        $query = Student::where('application_completed', 1)
            ->where('accepted', 'pending');

        $selectedItem = $request->input('selectedItem');
        $advanceSearch = $request->input('advanceSearch');

        switch ($selectedItem) {
            case 1:
                $query->where('username', 'like', '%' . $advanceSearch . '%');
                break;
            case 2:
                $query->where('firstname', 'like', '%' . $advanceSearch . '%');
                break;
            case 3:
                $query->where('middlename', 'like', '%' . $advanceSearch . '%');
                break;
            case 4:
                $query->where('lastname', 'like', '%' . $advanceSearch . '%');
                break;
            case 5:
                $query->where('email', 'like', '%' . $advanceSearch . '%');
                break;
            default:
                return response()->json(['error' => 'Invalid search type'], 400);
        }

        $students = $query->paginate(13); // Paginate results

        return response()->json([
            'status' => 200,
            'result' => $students
        ]);
    }



    public function searchAcceptedapplicant(Request $request)
    {
        if (!$request->has('selectedItem') || !$request->has('advanceSearch')) {
            return response()->json(['error' => 'Invalid search parameters'], 400);
        }

        $query = Student::where('application_completed', 1)->where('accepted', 'accepted');

        $selectedItem = $request->input('selectedItem');
        $advanceSearch = $request->input('advanceSearch');

        switch ($selectedItem) {
            case 1:
                $query->where('username', 'like', '%' . $advanceSearch . '%');
                break;
            case 2:
                $query->where('firstname', 'like', '%' . $advanceSearch . '%');
                break;
            case 3:
                $query->where('middlename', 'like', '%' . $advanceSearch . '%');
                break;
            case 4:
                $query->where('lastname', 'like', '%' . $advanceSearch . '%');
                break;
            case 5:
                $query->where('email', 'like', '%' . $advanceSearch . '%');
                break;
            default:
                return response()->json(['error' => 'Invalid search type'], 400);
        }

        $students = $query->paginate(13); // Paginate results

        return response()->json([
            'status' => 200,
            'result' => $students
        ]);

    }



    public function searchRejectedapplicant(Request $request)
    {
        if (!$request->has('selectedItem') || !$request->has('advanceSearch')) {
            return response()->json(['error' => 'Invalid search parameters'], 400);
        }

        $query = Student::where('application_completed', 1)->where('accepted', 'rejected');

        $selectedItem = $request->input('selectedItem');
        $advanceSearch = $request->input('advanceSearch');

        switch ($selectedItem) {
            case 1:
                $query->where('username', 'like', '%' . $advanceSearch . '%');
                break;
            case 2:
                $query->where('firstname', 'like', '%' . $advanceSearch . '%');
                break;
            case 3:
                $query->where('middlename', 'like', '%' . $advanceSearch . '%');
                break;
            case 4:
                $query->where('lastname', 'like', '%' . $advanceSearch . '%');
                break;
            case 5:
                $query->where('email', 'like', '%' . $advanceSearch . '%');
                break;
            default:
                return response()->json(['error' => 'Invalid search type'], 400);
        }

        $students = $query->paginate(13); // Paginate results

        return response()->json([
            'status' => 200,
            'result' => $students
        ]);

    }

    public function new(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:students,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // Attempt to retrieve the student
        $student = Student::where('id', $request->id)
            ->whereNull('apply_new_course')
            ->orWhere('apply_new_course', 0)
            ->first();

        // Handle case when no record is found

        if (!$student) {
            return response()->json([
                'status' => 404,
                'errorMessage' => 'Student not found or already eligible for a new program',
            ], 404);
        }

        // Perform the update
        DB::transaction(function () use ($student, $request) {
            $student->update([
                'is_applicant' => 1,
                'apply_new_course' => 1,
                'application_completed' => 0,
                'accepted' => 'Pending',
                'personal_info_completed' => 0,
                'program_id' => NULL,
                'department_id' => NULL,
                'waive' => NULL
            ]);

            ApplicantCertificate::where('user_id', $request->user_id)->delete();
            ApplicantEducation::where('user_id', $request->user_id)->delete();
            AdmissionCodeVerification::where('user_id', $request->user_id)->delete();
        });

        // Return a success response
        return response()->json([
            'status' => 200,
            'result' => 'Student Can Now Apply For A New Program',
        ]);

    }

    public function lecturerannouncement(Request $request)
    {
        ini_set('max_execution_time', 3600);
        $emails = Lecturer::pluck('email');

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new LecturerAnnounceMail($request->get('message')));
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
        }

        return response()->json([
            'status' => 200,
            'result' => 'Announcement queued successfully for lecturers!'
        ]);
    }
    public function conditionalStudentApplication(Request $request)
    {
        // interviewDate


        $student = Student::where('user_id', $request->get('userId'))->first();
        if ($student->apply_new_course == 1) {
            $studentName = $student->firstname . ' ' . $student->lastname;
            $studentNumber = $student->mat_number;
            $student->update(['is_applicant' => 0, 'accepted' => 'accepted', 'mat_number' => $studentNumber, 'acceptance_status' => 0, 'apply_new_course' => 0]);
            $orientaionDate = Carbon::parse($request->orientationDate);
            $commencementDate = Carbon::parse($request->commencementDate);

            Mail::to($student->email)->send(new AcceptedApplicationEmail($orientaionDate->format('jS F Y H:i:s A'), $commencementDate->format('jS F Y H:i:s A'), $studentNumber, $studentName, 0));
        } else {
            $studentName = $student->firstname . ' ' . $student->lastname;
            $studentNumber = $this->generateStudentNumber($student->id);
            $student->update(['is_applicant' => 0, 'accepted' => 'accepted', 'mat_number' => $studentNumber, 'acceptance_status' => 0]);
            $orientaionDate = Carbon::parse($request->orientationDate);
            $commencementDate = Carbon::parse($request->commencementDate);
            Mail::to($student->email)->send(new AcceptedApplicationEmail($orientaionDate->format('jS F Y H:i:s A'), $commencementDate->format('jS F Y H:i:s A'), $studentNumber, $studentName, 0));
        }



        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has accepted a student application');

        // when the student application is accepted, then he needs a matnumber
        return response()->json([
            'status' => 200,
            'result' => 'Accepted Successful'
        ]);
    }

    public function enrollStudentApplication(Request $request)
    {
        // interviewDate
        $student = Student::where('user_id', $request->get('userId'))->first();

        $studentName = $student->firstname . ' ' . $student->lastname;
        $student->update(['is_applicant' => 0, 'accepted' => 'accepted', 'acceptance_status' => 1]);

        Mail::to($student->email)->send(new EnrollmentApplicationEmail($studentName, $request->get('userId')));

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has enrolled a student application');

        // when the student application is accepted, then he needs a matnumber
        return response()->json([
            'status' => 200,
            'result' => 'Enrollment Successful'
        ]);
    }
    private function generateStudentNumber(int $id)
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Determine the month range and assign the fifth digit accordingly
        $fifthDigit = ($currentMonth >= 1 && $currentMonth <= 6) ? 1 : 2;

        // Retrieve the count of students with a non-null mat_number

        $lastNumber = str_pad($id, 4, '0', STR_PAD_LEFT);

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


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has rejected a student application');

        return response()->json([
            'status' => 200,
            'result' => 'Application Rejected Successfully'
        ]);
    }

    public function revertStudentApplication(Request $request)
    {
        $validatedData = $request->validate([
            'studentId' => 'required',
        ]);

        $email = Student::where('id', $validatedData['studentId'])->value('email');

        Student::where('id', $validatedData['studentId'])->update([
            'application_completed' => 0,
            'accepted' => 'pending'
        ]);


        if ($request->get('message') != '') {
            try {
                Mail::to($email)->send(new RevertApplicationMail($request->get('message')));
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            return response()->json(['message' => 'Email sent successfully']);
        }

        return response()->json([
            'status' => 200,
            'result' => 'Student application reverted successfully'
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
