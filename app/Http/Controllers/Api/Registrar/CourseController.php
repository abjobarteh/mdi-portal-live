<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Deferment;
use App\Models\GradingSystem;
use App\Models\Program;
use App\Models\Semester;
use App\Models\SemesterCourse;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Models\StudentRegisteredCourse;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Course::with(['program.department']);

        if ($request->has('selectedItem') && $request->has('advanceSearch')) {
            $selectedItem = $request->input('selectedItem');
            $advanceSearch = $request->input('advanceSearch');

            switch ($selectedItem) {
                case 1:
                    $query->where('course_name', 'like', '%' . $advanceSearch . '%');
                    break;
                case 2:
                    $query->where('course_code', 'like', '%' . $advanceSearch . '%');
                    break;

                default:
                    break;
            }
        }
        $courses = $query->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $courses
        ]);
    }


    
    public function getcourse(Request $request)
    {
        $query = Course::with(['program.department'])->orderBy("course_name");

        if ($request->has('selectedItem') && $request->has('advanceSearch')) {
            $selectedItem = $request->input('selectedItem');
            $advanceSearch = $request->input('advanceSearch');

            switch ($selectedItem) {
                case 1:
                    $query->where('course_name', 'like', '%' . $advanceSearch . '%');
                    break;
                case 2:
                    $query->where('course_code', 'like', '%' . $advanceSearch . '%');
                    break;

                default:
                    break;
            }
        }
        $courses = $query->paginate(1000);
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
    public function getprogcourse($id)
    {
        $results = Course::join('programs', 'courses.program_id', '=', 'programs.id')
            ->where('programs.id', $id)
            ->select('courses.*') // or select specific columns if needed
            ->get();
        return response()->json([
            'status' => 200,
            'result' => $results
        ]);
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


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has added a course');

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


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has updated a course');

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


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has deleted a course');
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

            $current_semester = Semester::where('is_current_semester', 1)->value('id');

            $student_id =  Student::join('student_payments', 'students.id', '=', 'student_payments.student_id')->where('students.user_id', auth()->user()->id)->value('student_id');
            if (Deferment::where('semester_id', $current_semester)->where('student_id', $student_id)->exists()) {
                $runningCourse["can_register"] = false;
            } else if (StudentPayment::where('semester_id', $current_semester)->where('student_id', $student_id)->exists() || ((Student::where('user_id', auth()->user()->id)->value('payment_type') == 1 || Student::where('user_id', auth()->user()->id)->value('is_sponsored') == 1) && Student::where('user_id', auth()->user()->id)->value('remaining') != 0)) {
                // can register
                $runningCourse["can_register"] = true;
            } else {
                $runningCourse["can_register"] = false;
            }

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

    public function studentTranscript($id)
    {
        $registeredCourses = StudentRegisteredCourse::where('registrar_approved', 1)->with('course', 'semester.session')
            ->where('student_id', Student::join('student_registered_courses', 'students.id', '=', 'student_registered_courses.student_id')
                ->where('students.user_id', $id)->value('students.id'))->get();

        $groupedCourses = $registeredCourses->groupBy(function ($item) {
            $semester = $item->semester;
            $session = $semester->session;
            return $semester->semester_name . ' (' . strtoupper(explode(' ', $session->session_start)[0]) . ' - ' . strtoupper($session->session_end) . ')';
        });

        $transcript = [];
        $totalSemesters = count($groupedCourses);
        $totalCGPASum = 0;

        foreach ($groupedCourses as $semesterSession => $courses) {
            $semesterTranscript = [
                'SemesterSession' => $semesterSession,
                'Courses' => [],
                'Average' => 0,
            ];
            $noOfCourses = 0;
            $sumOfMarks = 0;
            foreach ($courses as $course) {
                $noOfCourses++;
                $totalMark = $course->test_mark  + $course->exam_mark;
                $sumOfMarks += $totalMark;
                $courseTranscript = [
                    'CourseCode' => $course->course->course_code,
                    'CourseName' => $course->course->course_name,
                    'TestMark' => $course->test_mark,
                    'ExamMark' => $course->exam_mark,
                    'StartDate' => $course->semester->session->start_date,
                    'EndDate' => $course->semester->session->end_date,
                    'Grade' => GradingSystem::where('mark_from', '<=', $totalMark)->where('mark_to', '>=', $totalMark)->value('grade'),
                    'GradePoint' => GradingSystem::where('mark_from', '<=', $totalMark)->where('mark_to', '>=', $totalMark)->value('grade_point'),
                    'Interpretation' => GradingSystem::where('mark_from', '<=', $totalMark)->where('mark_to', '>=',  $totalMark)->value('interpretation'),
                ];

                $semesterTranscript['Courses'][] = $courseTranscript;
                $semesterTranscript['Average'] = $sumOfMarks / $noOfCourses;
            }

            $totalCGPASum += $semesterTranscript['Average']; // Accumulate the semester averages for CGPA calculation
            $transcript[] = $semesterTranscript;
        }

        return response()->json([
            'cgpa' => $totalCGPASum / $totalSemesters,
            'status' => 200,
            'result' => $transcript
        ]);
    }

    public function coursesToApprove()
    {
        $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');
        // i have to add approved later
        $activeSemesterCourses = SemesterCourse::with('course')->where('semester_id', $currentSemesterId)->where('submitted', 1)->where('approved', 1)->paginate(13); // ie the ones approved by hod
        foreach ($activeSemesterCourses as $activeSemesterCourse) {
            $activeSemesterCourse['marks'] = StudentRegisteredCourse::with('student')->where('course_id', $activeSemesterCourse['course_id'])->get();
        }

        return response()->json([
            'status' => 200,
            'result' => $activeSemesterCourses
        ]);
    }

    public function approveCourses(Request $request)
    {
        SemesterCourse::where('course_id', $request->get('course_id'))
            ->where('semester_id', Semester::where('is_current_semester', 1)->value('id'))
            ->update(['registrar_approved' =>  1]);

        StudentRegisteredCourse::where('course_id', $request->get('course_id'))
            ->where('semester_id', Semester::where('is_current_semester', 1)->value('id'))
            ->update(['registrar_approved' => 1]);
    }

    public function registeredCourses()
    {
        $registeredCourses = StudentRegisteredCourse::with('course', 'semester.session', 'lecturer')
            ->where('student_id', Student::join('student_registered_courses', 'students.id', '=', 'student_registered_courses.student_id')
                ->where('students.user_id', auth()->user()->id)->value('students.id'))->get();

        $groupedCourses = $registeredCourses->groupBy(function ($item) {
            $semester = $item->semester;
            $session = $semester->session;
            return $semester->semester_name . ' - ' . $session->session_start;
        });

        $transcript = [];

        foreach ($groupedCourses as $semesterSession => $courses) {
            $semesterTranscript = [
                'SemesterSession' => $semesterSession,
                'Courses' => []
            ];

            foreach ($courses as $course) {
                $courseTranscript = [
                    'CourseCode' => $course->course->course_code,
                    'Lecturer' => $course->lecturer->firstname . ' ' . $course->lecturer->lastname,
                    'CourseName' => $course->course->course_name,
                    'SemesterCourseId' => $course->semester_course_id,
                    'created_at' => $course->created_at,

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

    public function updateStudentMark(Request $request)
    {

        StudentRegisteredCourse::where([
            'student_id' => $request->get('student_id'),
            'semester_id' => $request->get('semester_id'),
            'course_id' => $request->get('course_id')
        ])->update([
            'test_mark' => $request->get('testMark'),
            'exam_mark' => $request->get('examMark'),
        ]);

        return response()->json([
            'status' => 200,
            'result' => "Grades updated successfully"
        ]);
    }

    public function viewPrograms()
    {
        $programs = Program::with(['department', 'duration'])->paginate(100);
        return response()->json([
            'status' => 200,
            'result' => $programs
        ]);
    }
}
