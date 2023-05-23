<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Semester;
use App\Models\SemesterCourse;
use App\Models\Student;
use App\Models\StudentRegisteredCourse;
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
        // if the course is part of the registered courses, then add registered to true else registered to false
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

        // $courseId = Student::join('student_registered_courses', 'students.id', '=', 'student_registered_courses.student_id')
        //     ->where('students.user_id', auth()->user()->id)->first()->course_id;
        // return $courseId;

        foreach ($runningCourses as $runningCourse) {
            $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');
            $courseId = Student::join('student_registered_courses', 'students.id', '=', 'student_registered_courses.student_id')
                ->where('students.user_id', auth()->user()->id)->where('course_id', $runningCourse['course_id'])->where('student_registered_courses.semester_id', $currentSemesterId)->value('course_id');

            if ($runningCourse['course_id'] == $courseId) {
                // the course is registered
                $runningCourse['course']['registered'] = true;
            } else {
                $runningCourse['course']['registered'] = false;
            }
        }

        // where the course belong to the department of the login user, if he is a student
        return response()->json([
            'status' => 200,
            'result' => $runningCourses
        ]);
    }

    public function studentTranscript()
    {
        // $registeredCourses = StudentRegisteredCourse::with('course', 'semester')->get();


        // return response()->json([
        //     'status' => 200,
        //     'result' => $registeredCourses
        // ]);

        // $registeredCourses = StudentRegisteredCourse::with('course', 'semester')->get();
        // $groupedCourses = $registeredCourses->groupBy(function ($item) {
        //     return $item->semester->semester_name;
        // });

        // $transcript = [];

        // foreach ($groupedCourses as $semesterName => $courses) {
        //     $semesterTranscript = [];

        //     foreach ($courses as $course) {
        //         $courseTranscript = [
        //             'Course Code' => $course->course->course_code,
        //             'Course Name' => $course->course->course_name,
        //             'Test Mark' => $course->test_mark,
        //             'Exam Mark' => $course->exam_mark,
        //             'Total Mark' => $course->total_mark,
        //         ];

        //         $semesterTranscript[] = $courseTranscript;
        //     }

        //     $transcript[$semesterName] = $semesterTranscript;
        // }

        // return $transcript;

        $registeredCourses = StudentRegisteredCourse::with('course', 'semester.session')->get();

        $groupedCourses = $registeredCourses->groupBy(function ($item) {
            $semester = $item->semester;
            $session = $semester->session;
            return $semester->semester_name . ' - ' . $session->session_name;
        });

        $transcript = [];

        foreach ($groupedCourses as $semesterSession => $courses) {
            $semesterTranscript = [
                'Semester Session' => $semesterSession,
                'Courses' => []
            ];

            foreach ($courses as $course) {
                $courseTranscript = [
                    'Course Code' => $course->course->course_code,
                    'Course Name' => $course->course->course_name,
                    'Test Mark' => $course->test_mark,
                    'Exam Mark' => $course->exam_mark,
                    'Total Mark' => $course->total_mark,
                    'Start Date' => $course->semester->session->start_date,
                    'End Date' => $course->semester->session->end_date,
                ];

                $semesterTranscript['Courses'][] = $courseTranscript;
            }

            $transcript[] = $semesterTranscript;
        }

        return response()->json([
            'status' => 200,
            'result' => $transcript
        ]);
    }
}
