<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Program;
use App\Models\Semester;
use App\Models\SponsoredStudents;
use App\Models\Student;
use App\Models\StudentPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StudentPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $students = Student::with('payments.semester', 'department')->where('accepted', 'accepted')->paginate(1000000);
    //     return response()->json([
    //         'status' => 200,
    //         'result' => $students
    //     ]);
    // }

    // public function index(Request $request)
    // {
    //     $query = Student::with('payments.semester', 'department')->where('accepted', 'accepted');

    //     if ($request->has('sponsored')) {
    //         $query->where('is_sponsored', 1);
    //     } else if ($request->has('notsponsored')) {
    //         $query->where('is_sponsored', 0);
    //     }

    //     if ($request->has('selectedItem') && $request->has('advanceSearch')) {

    //         $selectedItem = $request->input('selectedItem');
    //         $advanceSearch = $request->input('advanceSearch');

    //         switch ($selectedItem) {
    //             case 1:
    //                 $query->where('mat_number', 'like', '%' . $advanceSearch . '%');
    //                 break;
    //             case 2:
    //                 $query->where('email', 'like', '%' . $advanceSearch . '%');
    //                 break;

    //             default:
    //                 break;
    //         }
    //     }
    //     $courses = $query->paginate(13);
    //     return response()->json([
    //         'status' => 200,
    //         'result' => $courses
    //     ]);
    // }

    // public function semesters()
    // {
    //     $semesters = Semester::with('session')->where('is_current_semester', 1)->orderBy('id', 'desc')->paginate(13);
    //     return response()->json([
    //         'status' => 200,
    //         'result' => $semesters
    //     ]);
    // }
    public function index(Request $request)
    {
        $query = Student::with('payments.semester', 'department')->where('accepted', 'accepted');

        if ($request->has('sponsored')) {
            $query->where('is_sponsored', 1);
        } else if ($request->has('notsponsored')) {
            $query->where('is_sponsored', 0);
        }

        if ($request->has('selectedItem') && $request->has('advanceSearch')) {

            $selectedItem = $request->input('selectedItem');
            $advanceSearch = $request->input('advanceSearch');

            switch ($selectedItem) {
                case 1:
                    $query->where('mat_number', '=', '' . $advanceSearch . '');
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
                default:
                    break;
            }
        }
        $courses = $query->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $courses
        ]);
    }

    public function searchstudent(Request $request)
    {
        if ($request->has('selectedItem') && $request->has('advanceSearch')) {
            $query = Student::where('accepted', 'accepted');
            $selectedItem = $request->input('selectedItem');
            $advanceSearch = $request->input('advanceSearch');

            switch ($selectedItem) {
                case 1:
                    $query->where('mat_number', 'like', '%' . $advanceSearch . '%');
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
                case 7:
                    $query->where('gender', '=', '' . $advanceSearch . '');
                    break;
                case 8:
                    $query->where('nationality', 'like', '%' . $advanceSearch . '%');
                    break;
                case 6:
                    // Find the program by name or other field and get the id
                    $program = Program::where('name', 'like', '%' . $advanceSearch . '%')->first(); // Adjust the column if needed
                    if ($program) {
                        $query->where('program_id', '=', $program->id);
                    }
                    break;
                case 9:
                    $department = Department::where('name', 'like', '%' . $advanceSearch . '%')->first(); // Adjust the column if needed
                    if ($department) {
                        $query->where('department_id', '=',  $department->id);
                    }
                    break;
                default:
                    break;
            }

            // Execute the query and get the result
            $courses = $query->paginate(1000);
            return response()->json([
                'status' => 200,
                'result' => $courses
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Invalid search parameters'
        ]);
    }

    // public function viewSemesters(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'student_id' => 'required|max:255',
    //     ]);

    //     if (StudentPayment::where('student_id', $validatedData['student_id'])
    //         ->where('semester_fee_completed', 0)->exist()
    //     ) {
    //         $studentPaymentSemesterId =  StudentPayment::where('student_id', $validatedData['student_id'])
    //             ->where('semester_fee_completed', 0)->value('semester_id');
    //         $semesters = Semester::with('session')->where('id', $studentPaymentSemesterId)->first();

    //         return response()->json([
    //             'status' => 200,
    //             'result' => $semesters
    //         ]);
    //     } else {
    //         $semesters = Semester::with('session')->where('is_current_semester', 1)->orderBy('id', 'desc')->paginate(13);
    //         return response()->json([
    //             'status' => 200,
    //             'result' => $semesters
    //         ]);
    //     }
    // }

    public function waive(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|max:255',
        ]);
        $studentId = $validatedData['student_id'];
        $waive = Student::where('id', $studentId)->update(['waive' => 1]);


        return response()->json([
            'status' => 200,
            'result' => $waive
        ]);
    }

    public function getwaive(Request $request)
    {

        $studentId = $request->get('student_id');

        // Find the student by ID and get the waive status
        $waive = Student::where('id', $studentId)->value('waive');

        // Check if the waive status was found
        if ($waive !== null) {
            return response()->json([
                'status' => 200,
                'result' => $waive
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Student not found'
            ], 404);
        }
    }
    public function viewSemesters(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|max:255',
        ]);

        $studentId = $validatedData['student_id'];

        $studentPayment = StudentPayment::where('student_id', $studentId)
            ->where('semester_fee_completed', 0)
            ->first();

        if ($studentPayment) {
            $semesterId = $studentPayment->semester_id;
            $semester = Semester::with('session')->find($semesterId);

            return response()->json([
                'status' => 200,
                'result' => $semester
            ]);
        } else {
            $semesters = Semester::with('session')
                ->where('is_current_semester', 1)
                ->orderBy('id', 'desc')
                ->first();

            return response()->json([
                'status' => 200,
                'result' => $semesters
            ]);
        }
    }



    public function addPayment(Request $request)
    {
        // know the student total fee

        $validatedData = $request->validate([
            'student_id' => 'required|max:255',
            'semester_id' => 'required|max:255',
            'amount_paid' => 'required|max:255',
            'balance' => 'required'
        ]);
        $studentPayment = StudentPayment::where('student_id', $validatedData['student_id'])->where('semester_id', $validatedData['semester_id'])->first();
        $studentDepartmentFee = Student::find($validatedData['student_id'])->department->programs->first();
        // student have already paid all the fees
        if (Student::find($validatedData['student_id'])->payment_type == 1) {
            return response()->json(['message' => 'You have already made a complete payment'], 422);
        }
        if ($validatedData['balance'] < $validatedData['amount_paid']) {
            return response()->json(['message' => 'Amount Paid Is More Than Balance'], 422);
        }

        // student want to pay a fee that is above the total course fee or smaller than a semster fee * work later *
        if ($studentDepartmentFee->fee == $validatedData['amount_paid']) {

            // here i know the student is paying all the fee
            StudentPayment::create([
                'student_id' => $validatedData['student_id'],
                'semester_id' => $validatedData['semester_id'],
                'amount_paid' => $validatedData['amount_paid'],
                'payment_type' => 'Full Course Payment',
                'semester_fee_balance' => 0,
            ]);
            Student::find($validatedData['student_id'])->update([
                'payment_type' => 1,
                'balance' => 0,
                'waive' => 1,
                'remaining' => $validatedData['amount_paid']
            ]);
        } else if ($validatedData['amount_paid'] == $studentDepartmentFee['per_semester_fee']) {
            StudentPayment::create([
                'student_id' => $validatedData['student_id'],
                'semester_id' => $validatedData['semester_id'],
                'amount_paid' => $validatedData['amount_paid'],
                'payment_type' => 'Per semester installment',
                'semester_fee_balance' => 0,
            ]);
            StudentPayment::where('student_id', $validatedData['student_id'])->where('semester_id', $validatedData['semester_id'])->update([
                'semester_fee_completed' => 1
            ]);
        } /*else if (
            
            ($validatedData['amount_paid'] < $studentDepartmentFee['per_semester_fee'])
        ) {
            if (is_null($studentPayment)) {
                StudentPayment::create([
                    'student_id' => $validatedData['student_id'],
                    'semester_id' => $validatedData['semester_id'],
                    'amount_paid' => $validatedData['amount_paid'],
                    'payment_type' => 'Per semester installment',
                    'semester_fee_balance' => ($studentDepartmentFee['per_semester_fee'] - $validatedData['amount_paid']),
                ]);
            } else if ($validatedData['amount_paid'] > $studentPayment['semester_fee_balance']) {
                return response()->json(['message' => 'Please you should only pay ' .  'D' . $studentPayment['semester_fee_balance'] . ' to complete your balance'], 422);
            } else if ($validatedData['amount_paid'] == $studentPayment['semester_fee_balance']) {
                $studentPayment->update([
                    'semester_fee_balance' => ($studentDepartmentFee['per_semester_fee'] - $validatedData['amount_paid']),
                    'amount_paid' => $studentPayment['amount_paid'] + $validatedData['amount_paid'],
                    'semester_fee_completed' => 1,
                ]);
            } else {
                $studentPayment->update([
                    'semester_fee_balance' => ($studentPayment['semester_fee_balance'] - $validatedData['amount_paid']),
                    'amount_paid' => $studentPayment['amount_paid'] + $validatedData['amount_paid'],
                ]);
            }
        } */ else if ($validatedData['amount_paid'] < $studentDepartmentFee['per_semester_fee'] || $validatedData['amount_paid'] < $studentDepartmentFee->fee) {
            StudentPayment::create([
                'student_id' => $validatedData['student_id'],
                'semester_id' => $validatedData['semester_id'],
                'amount_paid' => $validatedData['amount_paid'],
                'payment_type' => 'Installment',
                'semester_fee_balance' => ($studentDepartmentFee['per_semester_fee'] - $validatedData['amount_paid']),
            ]);

            $student = Student::find($validatedData['student_id']);

            Student::find($validatedData['student_id'])->update([
                'payment_type' => 0,
                'waive' => 1,
                'balance' => $validatedData['balance'] - $validatedData['amount_paid'],
                'remaining' => $validatedData['balance'] - $validatedData['amount_paid']
            ]);
        } else if ($validatedData['amount_paid'] >= $studentDepartmentFee) {
            return response()->json(['message' => 'Pay all the fee (' . $studentDepartmentFee->fee . ') or per semester installment (' . $studentDepartmentFee['per_semester_fee'] . ' or per installment)'], 422);
        }

        /*else if ($validatedData['amount_paid'] <  $studentDepartmentFee['min_payable_per_semester'] && is_null($studentPayment)) {
            return response()->json(['message' => 'The minimum amount to pay is ' .  $studentDepartmentFee['min_payable_per_semester']], 422);
        } else if ($studentPayment) {
            if ($validatedData['amount_paid'] > $studentPayment['semester_fee_balance']) {
                if ($studentPayment['semester_fee_balance'] == 0) {
                    return response()->json(['message' => 'You have completed all your payments for this semester'], 422);
                } else {
                    return response()->json(['message' => 'Clear your balance of ' . 'D' . $studentPayment['semester_fee_balance'] . ' first'], 422);
                }
            } else {
                // you are expected to either pay fully or per semester
                // return response()->json(['message' => 'Pay all the fee (' . $studentDepartmentFee->fee . ') or per semester installment (' . $studentDepartmentFee['per_semester_fee'] . ')'], 422);
                $studentPayment->update([
                    'semester_fee_balance' => ($studentPayment['semester_fee_balance'] - $validatedData['amount_paid']),
                    'amount_paid' => $studentPayment['amount_paid'] + $validatedData['amount_paid'],
                ]);
                if ($studentPayment['semester_fee_balance'] == 0) {
                    $studentPayment->update(['semester_fee_completed' => 1]);
                }
            }
        } else {
            return response()->json(['message' => 'Pay all the fee (' . $studentDepartmentFee->fee . ') or per semester installment (' . $studentDepartmentFee['per_semester_fee'] . ')'], 422);
        } */

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has recieved student payment');

        return response()->json(['message' => 'Payment created successfully.']);
    }



    public function studentPayments()
    {
        $studentId = Student::join('student_registered_courses', 'students.id', '=', 'student_registered_courses.student_id')
            ->where('students.user_id', auth()->user()->id)->value('students.id');

        $studentPayments = StudentPayment::with('semester')
            ->where('student_id', $studentId)->get();

        $groupedPayments = $studentPayments->groupBy(function ($payment) {
            $semester = $payment->semester;
            return $semester->semester_name . ' - ' . $semester->session->session_start;
        });

        $formattedPayments = [];

        foreach ($groupedPayments as $semesterSession => $payments) {
            $semesterPayment = [
                'SemesterSession' => $semesterSession,
                'Payments' => [
                    'PaymentId' => $payments[0]->id,
                    'Amount' => $payments[0]->amount_paid,
                    'Date' => $payments[0]->created_at->toDateString(),
                ],
            ];

            $formattedPayments[] = $semesterPayment;
        }

        return response()->json([
            'status' => 200,
            'result' => $formattedPayments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeStudentSponsorship(Request $request)
    {
        // make the file nullable
        $validatedData = $request->validate([
            'student_id' => 'required|max:255',
            'scholarshipProvider' => 'required|max:255',
            'scholarshipName' => 'required|max:255',
            'startDate' => 'required|max:255',
            'endDate' => 'required|max:255',
            'scholarship_amount' => 'required|max:255',
        ]);


        $student = Student::where('id', $request->get('student_id'))->first();
        $departmentFee = Program::where('department_id', $student->department_id)->first();

        if ($validatedData['scholarship_amount'] != $departmentFee['per_semester_fee'] && ($validatedData['scholarship_amount'] % $departmentFee['per_semester_fee'] != 0)) {
            return response()->json(['message' => 'Scholarship should be (' . $departmentFee->fee . ') or  divisible by (' . $departmentFee['per_semester_fee'] . ')'], 422);
        }


        // Handle file upload
        $uploadedFile = $request->file('uploadedFile');
        $filePath = null;

        if ($uploadedFile) {
            $filePath = $uploadedFile->store('scholarships', 'public'); // Update storage path as needed
        }


        SponsoredStudents::create([
            'student_id' => $validatedData['student_id'],
            'scholarship_provider' => $validatedData['scholarshipProvider'],
            'scholarship_name' => $validatedData['scholarshipName'],
            'start_date' => $validatedData['startDate'],
            'end_date' => $validatedData['endDate'],
            'scholarship_file' => $filePath, // Update column name as needed
            'scholarship_amount' => $validatedData['scholarship_amount'],

        ]);

        Student::where('id', $request->get('student_id'))->update([
            'is_sponsored' => 1,
        ]);


        // $departmentFee = Program::where('department_id', $student->department_id)->value('fee');

        $student->update([
            'remaining' => $student->remaining !== null
                ? $student->remaining + $validatedData['scholarship_amount']
                : $validatedData['scholarship_amount'],
        ]);


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has created a scholarship');

        return response()->json(['message' => 'GradingSystem created successfully.']);
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

    public function scholarshipDetails($id)
    {
        $sponsorship = SponsoredStudents::where('student_id', $id)->paginate(10);
        return response()->json([
            'status' => 200,
            'result' => $sponsorship
        ]);
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
