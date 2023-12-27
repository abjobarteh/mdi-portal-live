<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Deferment;
use App\Models\Student;
use App\Models\StudentRegisteredCourse;
use Illuminate\Http\Request;

class DefermentController extends Controller
{
    public function addDeferment(Request $request)
    {
        $validatedData = $request->validate([
            'deferment_reason' => 'required',
            'semester_id' => 'required',
        ]);

        $studentId = Student::where('user_id', auth()->user()->id)->value('id');
        $semesterId = $validatedData['semester_id'];

        // Check if the record already exists
        if (Deferment::where('student_id', $studentId)->where('semester_id', $semesterId)->exists()) {
            return response()->json(['error' => 'Deferment record already exists for this student and semester.'], 422);
        }

        // Record doesn't exist, create a new one
        $deferment = Deferment::create([
            'student_id' => $studentId,
            'semester_id' => $semesterId,
            'deferment_reason' => $validatedData['deferment_reason'],
        ]);

        // Continue with your logic here

        // Return a success response if needed
        return response()->json(['message' => 'Deferment record created successfully.'], 200);
    }

    public function index(Request $request)
    {
        $deferments = Deferment::with('semester')->where('student_id', Student::where('user_id', auth()->user()->id)->value('id'))->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $deferments
        ]);
    }
}
