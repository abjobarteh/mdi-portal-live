<?php

namespace App\Http\Controllers\Api\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\Semester;
use App\Models\SemesterCourse;
use App\Models\Student;
use App\Models\StudentRegisteredCourse;
use Illuminate\Http\Request;

class StudentMarksController extends Controller
{

    public function marks(Request $request)
    {
        $studentMarks =  StudentRegisteredCourse::with('student')
            ->where('lecturer_id', Lecturer::where('user_id', auth()->user()->id)->value('id'))
            ->where('course_id', $request->course_id)
            ->when($request->has('semester_id'), function ($query) use ($request) {
                return $query->where('semester_id', $request->semester_id);
            })
            ->when(!$request->has('semester_id'), function ($query) {
                return $query->where('semester_id', Semester::where('is_current_semester', 1)->value('id'));
            })
            ->get();

        return response()->json([
            'status' => 200,
            'result' => $studentMarks
        ]);
    }

    public function myCourses()
    {
        $myCourses = SemesterCourse::with('course')->where('submitted', 0)->where('semester_id', Semester::where('is_current_semester', 1)->value('id'))
            ->where('lecturer_id', Lecturer::where('user_id', auth()->user()->id)->value('id'))->get();
        $courses = $myCourses->pluck('course');


        return response()->json([
            'status' => 200,
            'result' => $courses
        ]);
    }

    public function takeTestMark(Request $request)
    {
        foreach ($request->student as $student) {
            StudentRegisteredCourse::where([
                ['student_id', $student['student_id']],
                ['lecturer_id', $student['lecturer_id']],
                ['semester_id', $student['semester_id']],
                ['course_id', $student['course_id']]
            ])->update([
                'test_mark' => $student['test_mark']
            ]);
        }
    }

    public function saveExamMarkAndSubmit(Request $request)
    {
        foreach ($request->student as $student) {
            StudentRegisteredCourse::where([
                ['student_id', $student['student_id']],
                ['lecturer_id', $student['lecturer_id']],
                ['semester_id', $student['semester_id']],
                ['course_id', $student['course_id']]
            ])->update([
                'exam_mark' => $student['exam_mark']
            ]);
        }
        SemesterCourse::where('course_id', $request->get('student')[0]['course_id'])
            ->where('semester_id', Semester::where('is_current_semester', 1)->value('id'))
            ->update(['submitted' => 1,]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
