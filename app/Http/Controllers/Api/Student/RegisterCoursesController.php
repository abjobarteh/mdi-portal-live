<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentRegisteredCourse;
use Illuminate\Http\Request;

class RegisterCoursesController extends Controller
{
    public function registerCourse(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|max:255',
            'lecturer_id' => 'required',
            'semester_id' => 'required',
            'course_id' => 'required',
        ]);

        $studentId = $validatedData['student_id'];
        $semesterId = $validatedData['semester_id'];

        $totalRegisteredCourses = StudentRegisteredCourse::where([
            'student_id' => $studentId,
            'semester_id' => $semesterId,
        ])->count();

        if ($totalRegisteredCourses >= 6) {
            return response()->json(['message' => 'Cannot register more than 6 courses in the same semester.'], 400);
        }

        $studentRegisteredCourse = StudentRegisteredCourse::firstOrCreate([
            'student_id' => $studentId,
            'lecturer_id' => $validatedData['lecturer_id'],
            'semester_id' => $semesterId,
            'course_id' => $validatedData['course_id'],
        ]);

        return response()->json(['message' => 'Program created successfully.']);
    }

    public function unRegisterCourse(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|max:255',
            'lecturer_id' => 'required',
            'semester_id' => 'required',
            'course_id' => 'required',
        ]);

        $studentId = $validatedData['student_id'];
        $lecturerId = $validatedData['lecturer_id'];
        $semesterId = $validatedData['semester_id'];
        $courseId = $validatedData['course_id'];

        $deleted = StudentRegisteredCourse::where([
            'student_id' => $studentId,
            'lecturer_id' => $lecturerId,
            'semester_id' => $semesterId,
            'course_id' => $courseId,
        ])->delete();

        if ($deleted) {
            return response()->json(['message' => 'Program removed successfully.']);
        } else {
            return response()->json(['message' => 'Program not found.'], 404);
        }
    }
}
