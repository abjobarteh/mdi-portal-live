<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Semester;
use App\Models\SemesterCourse;
use App\Models\Student;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $semesters = Semester::with('session')->orderBy('id', 'desc')->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $semesters
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
        $validatedData = $request->validate([
            'semester_name' => 'required|max:255',
            'session_id' => 'required',
        ]);

        Semester::where('is_current_semester', 1)->update(['is_current_semester' => 0]);

        $semester = Semester::create([
            'semester_name' => $validatedData['semester_name'],
            'session_id' => $validatedData['session_id'],
            'next_semester' => "Not known",
            'is_current_semester' => 1,
        ]);

        // Get all courses
        $courses = Course::all();

        // Loop through each course and create a SemesterCourse
        foreach ($courses as $course) {
            SemesterCourse::create([
                'course_id' => $course->id,
                'semester_id' => $semester->id,
            ]);
        }

        // loop through all the students where the payment_types is 1 and remaining is not 0 and update their remaining
        $students = Student::where('payment_type',  1)->where('remaining', '>',  0)->get();
        foreach ($students as $student) {

            $semesterFee = $student->department->programs->value('per_semester_fee');
            $student->decrement('remaining', $semesterFee);
            // remember initially the remaining will be - one semester fee
        }

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has created a semester');

        return response()->json(['message' => 'Semester created successfully.']);
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
