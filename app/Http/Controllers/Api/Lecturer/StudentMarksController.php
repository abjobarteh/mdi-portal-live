<?php

namespace App\Http\Controllers\Api\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\Semester;
use App\Models\SemesterCourse;
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
        $myCourses = SemesterCourse::with('course')->where('semester_id', Semester::where('is_current_semester', 1)->value('id'))
            ->where('lecturer_id', Lecturer::where('user_id', auth()->user()->id)->value('id'))->get();
        $courses = $myCourses->pluck('course');


        return response()->json([
            'status' => 200,
            'result' => $courses
        ]);
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
