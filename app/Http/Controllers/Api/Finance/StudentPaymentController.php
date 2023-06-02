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
    public function index()
    {
        $students = Student::with('payments.semester', 'department')->where('accepted', 'accepted')->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $students
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
        if ($studentDepartmentFee->fee == $validatedData['amount_paid']) {

            // here i know the student paid all the fee
            StudentPayment::create([
                'student_id' => $validatedData['student_id'],
                'semester_id' => $validatedData['semester_id'],
                'amount_paid' => $validatedData['amount_paid'],
            ]);
            Student::find($validatedData['student_id'])->update([
                'payment_type' => 1,
                'balance' => 0,
                'remaining' => $validatedData['amount_paid']
            ]);
        } else if ($studentDepartmentFee->fee == $validatedData['per_semester_fee']) {
            StudentPayment::create([
                'student_id' => $validatedData['student_id'],
                'semester_id' => $validatedData['semester_id'],
                'amount_paid' => $validatedData['amount_paid'],
            ]);
        } else {
            // you are expected to either pay fully or per semester
            return response()->json(['message' => 'Pay all the fee or per semester fee.']);
        }


        return response()->json(['message' => 'Payment created successfully.']);
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
