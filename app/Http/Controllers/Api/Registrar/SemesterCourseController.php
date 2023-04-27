<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\SemesterCourse;
use Illuminate\Http\Request;

class SemesterCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // bring semester courses, which is for the current semester and the lecture_id is not null;
        // $availableSemesterCourses = SemesterCourse::whereHas('semester', function ($query) {
        //     $query->where('is_current_semester', 1);
        // })
        //     ->whereNull('lecturer_id')
        //     ->with('course')
        //     ->paginate(13);

        // return response()->json([
        //     'status' => 200,
        //     'result' => $availableSemesterCourses
        // ]);

        $lecturerCourses = SemesterCourse::whereHas('semester', function ($query) {
            $query->where('is_current_semester', 1);
        })
            ->whereNotNull('lecturer_id')
            ->with('course')
            ->paginate(13);

        return response()->json([
            'status' => 200,
            'result' => $lecturerCourses
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
