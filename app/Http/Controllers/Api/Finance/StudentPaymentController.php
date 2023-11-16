<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentPayment;
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

    public function index(Request $request)
    {
        $query = Student::with('payments.semester', 'department')->where('accepted', 'accepted');

        if ($request->has('selectedItem') && $request->has('advanceSearch')) {

            $selectedItem = $request->input('selectedItem');
            $advanceSearch = $request->input('advanceSearch');

            switch ($selectedItem) {
                case 1:
                    $query->where('mat_number', 'like', '%' . $advanceSearch . '%');
                    break;
                case 2:
                    $query->where('email', 'like', '%' . $advanceSearch . '%');
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

    public function addPayment(Request $request)
    {
        // know the student total fee

        $validatedData = $request->validate([
            'student_id' => 'required|max:255',
            'semester_id' => 'required|max:255',
            'amount_paid' => 'required|max:255',
        ]);
        $studentDepartmentFee = Student::find($validatedData['student_id'])->department->programs->first();
        // student have already paid all the fees
        if (Student::find($validatedData['student_id'])->payment_type == 1) {
            return response()->json(['message' => 'You have already made a complete payment'], 422);
        }

        // student want to pay a fee that is above the total course fee or smaller than a semster fee * work later *
        if ($studentDepartmentFee->fee == $validatedData['amount_paid']) {

            // here i know the student is paying all the fee
            StudentPayment::create([
                'student_id' => $validatedData['student_id'],
                'semester_id' => $validatedData['semester_id'],
                'amount_paid' => $validatedData['amount_paid'],
                'payment_type' => 'Full Course Payment'
            ]);
            Student::find($validatedData['student_id'])->update([
                'payment_type' => 1,
                'balance' => 0,
                'remaining' => $validatedData['amount_paid']
            ]);
        } else if ($validatedData['amount_paid'] == $studentDepartmentFee['per_semester_fee']) {
            StudentPayment::create([
                'student_id' => $validatedData['student_id'],
                'semester_id' => $validatedData['semester_id'],
                'amount_paid' => $validatedData['amount_paid'],
                'payment_type' => 'Per semester installment'
            ]);
        } else {
            // you are expected to either pay fully or per semester
            return response()->json(['message' => 'Pay all the fee or per semester fee.'], 422);
        }


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
            return $semester->semester_name . ' - ' . $semester->session->session_name;
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
