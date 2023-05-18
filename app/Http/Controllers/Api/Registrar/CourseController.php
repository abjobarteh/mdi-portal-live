<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Semester;
use App\Models\SemesterCourse;
use App\Models\Student;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::with(['program.department'])->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $courses
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
            'course_code' => 'required|max:255',
            'course_name' => 'required|max:255',
            'program_id' => 'required|max:255',
        ]);
        Course::create([
            'course_code' => $validatedData['course_code'],
            'course_name' => $validatedData['course_name'],
            'program_id' => $validatedData['program_id'],
        ]);

        return response()->json(['message' => 'Course created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::find($id);

        return response()->json([
            'status' => 200,
            'result' => $course
        ]);
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
        $validatedData = $request->validate([
            'course_code' => 'required|max:255',
            'course_name' => 'required|max:255',
            'program_id' => 'required|max:255',
        ]);

        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        $course->update([
            'course_code' => $validatedData['course_code'],
            'course_name' => $validatedData['course_name'],
            'program_id' => $validatedData['program_id'],
        ]);

        return response()->json(['message' => 'Course updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Course::find($id);
        $department->delete();
    }

    public function runningCourses()
    {
        $studentDepartmentId = Student::where('user_id', auth()->user()->id)->value('department_id');

        $runningCourses = SemesterCourse::with('course')
            ->with('lecturer')
            ->where('semester_id', Semester::where('is_current_semester', 1)->value('id'))
            ->whereNotNull('lecturer_id')
            ->whereHas('course', function ($query) use ($studentDepartmentId) {
                $query->whereHas('program', function ($query) use ($studentDepartmentId) {
                    $query->where('department_id', $studentDepartmentId);
                });
            })
            ->get();

        // where the course belong to the department of the login user, if he is a student
        return response()->json([
            'status' => 200,
            'result' => $runningCourses
        ]);
    }
}
