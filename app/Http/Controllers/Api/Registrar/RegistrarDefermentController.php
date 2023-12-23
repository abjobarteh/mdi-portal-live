<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Deferment;
use App\Models\Program;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Models\StudentRegisteredCourse;
use Illuminate\Http\Request;

class RegistrarDefermentController extends Controller
{

    public function index(Request $request)
    {
        $deferments = Deferment::where('is_approved', 0)->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $deferments
        ]);
    }

    public function approveDeferment($id)
    {
        $deferment = Deferment::where('id', $id)->update([
            'is_approved' => 1,
        ]);
        // 1. check if the student is under scholarship, if yes then refund

        $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');
        $studentPayment = StudentPayment::where('student_id', Deferment::where('id', $id)->value('student_id'))->first();

        if (!is_null($studentPayment)) {
            if ($studentPayment->semester_id == $currentSemesterId) {

                // his remaining balance will be added by the per_semester_fee
                $programFee = Program::where('department_id', $studentPayment->student->department_id)->value('fee');

                if (!is_null($programFee)) {
                    Student::where('id', Deferment::where('id', $id)->value('student_id'))
                        ->increment('remaining', $programFee);
                } else {
                    // Handle the case where program fee is null
                }
            } elseif (($studentPayment->semester_id != $currentSemesterId && $studentPayment->payment_type == 'Full Course Payment') ||
                Student::where('id', Deferment::where('id', $id)->value('student_id'))->value('is_sponsored') == 1
            ) {
                // his remaining balance will be added by the per_semester_fee ie he paid but not this semester
                $programFee = Program::where('department_id', $studentPayment->student->department_id)->value('per_semester_fee');

                if (!is_null($programFee)) {
                    Student::where('id', Deferment::where('id', $id)->value('student_id'))
                        ->increment('remaining', $programFee);
                } else {
                    // Handle the case where program fee is null
                }
            }
        }

        // incase if the student have already registered for courses, just remove all his registered courses, this semester
        StudentRegisteredCourse::where('student_id', Deferment::where('id', $id)->value('student_id'))
            ->where('semester_id', $currentSemesterId)
            ->delete();
        // 2. check if the student have made payment for this semester or full course payment, if yes then refund
        return response()->json([
            'status' => 200,
            'result' => "updated successfully"
        ]);
    }
}
