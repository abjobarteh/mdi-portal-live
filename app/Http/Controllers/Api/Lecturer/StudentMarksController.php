<?php

namespace App\Http\Controllers\Api\Lecturer;

use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Models\GradingSystem;
use App\Models\Lecturer;
use App\Models\Semester;
use App\Models\SemesterCourse;
use App\Models\Student;
use App\Models\StudentRegisteredCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentMarksController extends Controller
{

    public function marks(Request $request)
    {
        $studentMarks = StudentRegisteredCourse::with('student')
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

    public function destroy($id)
    {

        $user = Lecturer::find($id);
        if ($user->id == auth()->user()->id) {
            return response()->json(['error' => 'You cannot delete yourself'], 422);
        }
        $user->delete();


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has deleted a user');
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
                        'test_mark' => $student['test'] + $student['assignment'],
                        'assignment' => $student['assignment'],
                        'test' => $student['test'],
                        'total_mark' => DB::raw('exam_mark + ' . ($student['test'] + $student['assignment']))
                    ]);

            $totalMark = $student['test'] + $student['assignment'] + StudentRegisteredCourse::where([
                ['student_id', $student['student_id']],
                ['lecturer_id', $student['lecturer_id']],
                ['semester_id', $student['semester_id']],
                ['course_id', $student['course_id']]
            ])->value('exam_mark');

            $gradingSystem = GradingSystem::where('mark_from', '<=', $totalMark)
                ->where('mark_to', '>=', $totalMark)
                ->where('grade_type', $request->grade_type)
                ->first();

            StudentRegisteredCourse::where([
                ['student_id', $student['student_id']],
                ['lecturer_id', $student['lecturer_id']],
                ['semester_id', $student['semester_id']],
                ['course_id', $student['course_id']]
            ])->update([
                        'grade_point' => $gradingSystem->grade_point, // Assuming grade_point is a column
                        'letter_grade' => $gradingSystem->grade,
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
                        'exam_mark' => $student['exam_mark'],
                        'total_mark' => DB::raw('test_mark + ' . ($student['exam_mark']))
                    ]);

            $totalMark = $student['exam_mark'] + StudentRegisteredCourse::where([
                ['student_id', $student['student_id']],
                ['lecturer_id', $student['lecturer_id']],
                ['semester_id', $student['semester_id']],
                ['course_id', $student['course_id']]
            ])->value('test_mark');

            $gradingSystem = GradingSystem::where('mark_from', '<=', $totalMark)
                ->where('mark_to', '>=', $totalMark)
                ->where('grade_type', $request->grade_type)
                ->first();

            StudentRegisteredCourse::where([
                ['student_id', $student['student_id']],
                ['lecturer_id', $student['lecturer_id']],
                ['semester_id', $student['semester_id']],
                ['course_id', $student['course_id']]
            ])->update([
                        'grade_point' => $gradingSystem->grade_point, // Assuming grade_point is a column
                        'letter_grade' => $gradingSystem->grade,
                    ]);
        }



        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has saved and submitted exam marks');
    }



    public function export(Request $request)
    {
        $lecturer = Lecturer::where('user_id', auth()->user()->id)->first();
        $courseId = $request->input('course_id');
        $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');
        $students = DB::select("SELECT b.id as 'Student ID',b.mat_number as 'Mat Number',b.firstname,b.middlename,b.lastname ,c.course_name as course FROM 
        student_registered_courses a join students b on (a.student_id=b.id) 
        join courses c on (a.course_id=c.id) where a.course_id = ? and a.semester_id = ? and a.lecturer_id = ? ", [$courseId, $currentSemesterId, $lecturer->id]);

        return Excel::download(new StudentsExport($courseId, $currentSemesterId, $lecturer->id), 'MARK_UPLOAD_TEMPLATE_FILE.xlsx');
    }

    public function importMarks(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
            'course_id' => 'required', // Validate that the course_id exists in the courses table
            'grade_type' => 'required', // Adjust based on the actual grade types
        ]);

        // Retrieve the course_id and grade_type from the request
        $courseId = $request->input('course_id');
        $gradeType = $request->input('grade_type');
        $lecturer = Lecturer::where('user_id', auth()->user()->id)->first()->id;
        $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');
        // Process the file with the Excel import
        try {
            Excel::import(new StudentsImport($courseId, $gradeType, $lecturer, $currentSemesterId), $request->file('file'));
            return response()->json(['message' => 'Marks Uploaded Successfully'], 200);
        } catch (\Exception $e) {
            // Handle the generic exception
            return response()->json(['error' => $e->getMessage()], 400); // 400 Bad Request
        }

    }

    public function submitMarks(Request $request)
    {
        $courseId = $request->course_id;

        SemesterCourse::where('course_id', $courseId)
            ->where('semester_id', Semester::where('is_current_semester', 1)->value('id'))
            ->update(['submitted' => 1]);


        return response()->json([
            'status' => 200,
            'result' => "Marks Submitted Successfully",
            'course_id' => $request->course_id
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
}
