<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCode;
use App\Models\AdmissionCodeLocation;
use App\Models\Course;
use App\Models\Department;
use App\Models\Hod;
use App\Models\Lecturer;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Models\StudentRegisteredCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
class DashboardController extends Controller
{
    public function statusCount()
    {

        ////////// sum admission codes sold this semester /////////
        $currentSemesterSoldAdmissionCodes = AdmissionCode::whereHas('admissionCodeLocation', function ($query) {
            $query->where('semester_id', Semester::where('is_current_semester', 1)->value('id'));
        })
            ->where('is_sold', 1)
            ->sum('price');

        ////////// sum admission codes sold last semester /////////
        $lastSemesterSoldAdmissionCodes = null;
        $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');
        if ($currentSemesterId !== null) {
            // If the current semester exists, subtract 1 from its ID
            $lastSemesterId = $currentSemesterId - 1;

            // Check if the last semester ID is valid
            if ($lastSemesterId >= 1) {
                $lastSemesterSoldAdmissionCodes = AdmissionCode::whereHas('admissionCodeLocation', function ($query) use ($lastSemesterId) {
                    $query->where('semester_id', $lastSemesterId);
                })
                    ->where('is_sold', 1)
                    ->sum('price');
            } else {
                // Handle the case when there is no previous semester
                $lastSemesterSoldAdmissionCodes = 0; // or any other appropriate value
            }
        } else {
            // Handle the case when there is no current semester
            $lastSemesterSoldAdmissionCodes = 0; // or any other appropriate value
        }

        ///////////// total tution fee paid this semester /////////////////
        $currentSemesterTuitionPaid = StudentPayment::where('semester_id', Semester::where('is_current_semester', 1)->value('id'))->sum('amount_paid');

        ///////////// total tution fee paid last semester /////////////////
        $lastSemesterTuitionPaid = null;
        if (!is_null($currentSemesterId)) {
            $lastSemesterId = $currentSemesterId - 1;

            // Check if the last semester ID is valid
            if ($lastSemesterId >= 1) {
                $lastSemesterTuitionPaid = StudentPayment::where('semester_id', $lastSemesterId)->sum('amount_paid');
            } else {
                // Handle the case when there is no previous semester
                $lastSemesterTuitionPaid = 0; // or any other appropriate value
            }
        } else {
            // Handle the case when there is no current semester
            $lastSemesterTuitionPaid = 0; // or any other appropriate value
        }

        return response()->json([
            'currentSemesterSoldAdmissionCodes' => $currentSemesterSoldAdmissionCodes,
            'lastSemesterSoldAdmissionCodes' => $lastSemesterSoldAdmissionCodes,
            'currentSemesterTuitionPaid' => $currentSemesterTuitionPaid,
            'lastSemesterTuitionPaid' => $lastSemesterTuitionPaid,
            'totalTutionFeePaid' => StudentPayment::sum('amount_paid'),

        ]);
    }

    public function counts()
    {
        $acceptedStudents = Student::where('accepted', 'accepted')->count();
        $rejectedStudents = Student::where('accepted', 'rejected')->count();

        $maleStudents = Student::where('accepted', 'accepted')
            ->where(function ($query) {
                $query->where('gender', 'Male')
                    ->orWhere('gender', 'male');
            })
            ->count();

        $femaleStudents = Student::where('accepted', 'accepted')
            ->where(function ($query) {
                $query->where('gender', 'Female')
                    ->orWhere('gender', 'female');
            })
            ->count();

        $semesters = Semester::all(['id', 'semester_name']);

        $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');

        $activeStudents = StudentRegisteredCourse::where('semester_id', $currentSemesterId)
            ->distinct('student_id')
            ->count();

        $activeLecturers = StudentRegisteredCourse::where('semester_id', $currentSemesterId)
            ->distinct('lecturer_id')
            ->count();


        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfWeek = $endOfWeek->copy()->startOfWeek();

        $countsByDay = Activity::whereJsonContains('properties->attributes->role_id', 2)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as day, COUNT(*) as count')
            ->groupBy('day')
            ->get();

        // Create an array representing all days of the week with initial count set to 0
        $counts = [];
        $currentDay = $startOfWeek->copy();

        while ($currentDay <= $endOfWeek) {
            $counts[] = 0; // Only store counts without dates
            $currentDay->addDay();
        }

        // Fill in the counts for the existing days
        foreach ($countsByDay as $count) {
            $dayCarbon = Carbon::parse($count->day); // Convert the string to a Carbon instance
            $index = $dayCarbon->diffInDays($startOfWeek);
            $counts[$index] = $count->count;
        }

        $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');

        $lecturersWithMostCourses = StudentRegisteredCourse::with('lecturer')->where('semester_id', $currentSemesterId)
            ->groupBy('lecturer_id')
            ->selectRaw('lecturer_id, count(*) as course_count')
            ->orderByDesc('course_count')
            ->take(5) // Limit the results to the first 5 lecturers
            ->get();

        $departmentCount = DB::select("
            SELECT b.name AS department_name, COUNT(a.id) AS student_count
            FROM students a
            JOIN departments b ON a.department_id = b.id
            WHERE a.accepted = 'accepted'
            GROUP BY b.name
            ORDER BY b.name");


        $maledepartmentCount = DB::select("
            SELECT b.name AS department_name, COUNT(a.id) AS male_student_count
            FROM students a
            JOIN departments b ON a.department_id = b.id
            WHERE a.accepted = 'accepted'
            and a.gender = 'Male' or a.gender = 'male'
            GROUP BY b.name
            ORDER BY b.name");



        $femaledepartmentCount = DB::select("
            SELECT b.name AS department_name, COUNT(a.id) AS female_student_count
            FROM students a
            JOIN departments b ON a.department_id = b.id
            WHERE a.accepted = 'accepted'
            and a.gender = 'female' or a.gender = 'Female'
            GROUP BY b.name
            ORDER BY b.name");

        return response()->json([
            'acceptedStudents' => $acceptedStudents,
            'rejectedStudents' => $rejectedStudents,
            'maleStudents' => $maleStudents,
            'femaleStudents' => $femaleStudents,
            'activeStudents' => $activeStudents, // students who have taken courses this semester
            'activeLecturers' => $activeLecturers, // lecturers who have taken courses this semester
            'weekyStudentLogins' => $counts,
            'lecturersWithMostCourses' => $lecturersWithMostCourses,
            'departmentCount' => $departmentCount,
            'maledepartmentCount' => $maledepartmentCount,
            'femaledepartmentCount' => $femaledepartmentCount,
            'semester' => $semesters
        ]);
    }

    public function deptcounts($id)
    {
        $semester = Semester::findorfail($id);
        $departmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
        GROUP BY b.name
        ORDER BY b.name", [$semester->semester_name]);

        $maledepartmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS male_student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
        AND (a.gender = 'Male' OR a.gender = 'male')
        GROUP BY b.name
        ORDER BY b.name", [$semester->semester_name]);

        $femaledepartmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS female_student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
        AND (a.gender = 'Female' OR a.gender = 'female')
        GROUP BY b.name
        ORDER BY b.name", [$semester->semester_name]);

        $semesters = Semester::all(['id', 'semester_name']);

        return response()->json([
            'departmentCount' => $departmentCount,
            'maledepartmentCount' => $maledepartmentCount,
            'femaledepartmentCount' => $femaledepartmentCount,
            'semester' => $semesters
        ]);

    }

    public function lecturerDashboardCounts()
    {
        $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');

        $myCourses = StudentRegisteredCourse::with('course')->join('lecturers', 'lecturers.id', '=', 'student_registered_courses.lecturer_id')
            ->where('lecturers.user_id', auth()->user()->id)->where('semester_id', $currentSemesterId)
            ->distinct('course_id')->withCount('student') // Count the number of related students
            ->paginate(20);

        $coursestat = DB::select("SELECT 
courses.course_code,course_name,
    COUNT(student_registered_courses.student_id) AS student_count 
FROM 
    student_registered_courses
JOIN 
    lecturers 
ON 
    lecturers.id = student_registered_courses.lecturer_id
    JOIN courses On (student_registered_courses.course_id=courses.id)
WHERE 
    lecturers.user_id = ?
GROUP BY 
    courses.course_code,courses.course_name ", [auth()->user()->id]);

        $coursestatmale = DB::select("SELECT 
courses.course_code,course_name,
    COUNT(student_registered_courses.student_id) AS student_count 
FROM 
    student_registered_courses
JOIN 
    lecturers 
ON 
    lecturers.id = student_registered_courses.lecturer_id
    JOIN courses 
    On 
    (student_registered_courses.course_id=courses.id)
    JOIN students 
    On (students.id=student_registered_courses.student_id)
WHERE 
    (students.gender = 'Male' or students.gender = 'male') AND
    lecturers.user_id = ?
GROUP BY 
    courses.course_code,courses.course_name ", [auth()->user()->id]);


        $coursestatfemale = DB::select("SELECT 
courses.course_code,course_name,
    COUNT(student_registered_courses.student_id) AS student_count 
FROM 
    student_registered_courses
JOIN 
    lecturers 
ON 
    lecturers.id = student_registered_courses.lecturer_id
    JOIN courses 
    On 
    (student_registered_courses.course_id=courses.id)
    JOIN students 
    On (students.id=student_registered_courses.student_id)
WHERE 
    (students.gender = 'Female' or students.gender = 'female') AND
    lecturers.user_id = ? 
GROUP BY 
    courses.course_code,courses.course_name ", [auth()->user()->id]);

        $myTotalStudents = StudentRegisteredCourse::join('lecturers', 'lecturers.id', '=', 'student_registered_courses.lecturer_id')
            ->where('lecturers.user_id', auth()->user()->id)->where('semester_id', $currentSemesterId)
            ->distinct('student_id')
            ->count();


        $mytotalfemalestudent = DB::select("
            SELECT COUNT(STUDENT_ID) AS femalecount FROM student_registered_courses A JOIN STUDENTS B ON (a.student_id=b.id) JOIN Lecturers c on (a.lecturer_id=c.id)
            WHERE (b.gender = 'Female' OR b.gender = 'female') AND  A.SEMESTER_ID = ? AND C.USER_ID = ? ", [$currentSemesterId, auth()->user()->id]);

        $mytotalmalestudent = DB::select("
                 SELECT COUNT(STUDENT_ID) AS malecount FROM student_registered_courses A JOIN STUDENTS B ON (a.student_id=b.id) JOIN Lecturers c on (a.lecturer_id=c.id)
            WHERE (b.gender = 'Male' OR b.gender = 'male') AND  A.SEMESTER_ID = ? AND C.USER_ID = ? ", [$currentSemesterId, auth()->user()->id]);


        $semesters = Semester::all(['id', 'semester_name']);

        return response()->json([
            'myCourses' => $myCourses,
            'myStudents' => $myTotalStudents,
            'myfemalestudents' => $mytotalfemalestudent,
            'mymalestudents' => $mytotalmalestudent,
            'coursestat' => $coursestat,
            'coursestatmale' => $coursestatmale,
            'coursestatfemale' => $coursestatfemale,
            'semester' => $semesters
        ]);
    }


    public function lecturerCounts($id)
    {
        $coursestat = DB::select("SELECT 
courses.course_code,course_name,
    COUNT(student_registered_courses.student_id) AS student_count 
FROM 
    student_registered_courses
JOIN 
    lecturers 
ON 
    lecturers.id = student_registered_courses.lecturer_id
    JOIN courses On (student_registered_courses.course_id=courses.id)
WHERE 
    lecturers.user_id = ? AND student_registered_courses.semester_id = ?
GROUP BY 
    courses.course_code,courses.course_name ", [auth()->user()->id, $id]);

        $coursestatmale = DB::select("SELECT 
courses.course_code,course_name,
    COUNT(student_registered_courses.student_id) AS student_count 
FROM 
    student_registered_courses
JOIN 
    lecturers 
ON 
    lecturers.id = student_registered_courses.lecturer_id
    JOIN courses 
    On 
    (student_registered_courses.course_id=courses.id)
    JOIN students 
    On (students.id=student_registered_courses.student_id)
WHERE 
    (students.gender = 'Male' or students.gender = 'male') AND
    lecturers.user_id = ? AND student_registered_courses.semester_id = ?
GROUP BY 
    courses.course_code,courses.course_name ", [auth()->user()->id, $id]);


        $coursestatfemale = DB::select("SELECT 
courses.course_code,course_name,
    COUNT(student_registered_courses.student_id) AS student_count 
FROM 
    student_registered_courses
JOIN 
    lecturers 
ON 
    lecturers.id = student_registered_courses.lecturer_id
    JOIN courses 
    On 
    (student_registered_courses.course_id=courses.id)
    JOIN students 
    On (students.id=student_registered_courses.student_id)
WHERE 
    (students.gender = 'Female' or students.gender = 'female') AND
    lecturers.user_id = ? AND student_registered_courses.semester_id = ?
GROUP BY 
    courses.course_code,courses.course_name ", [auth()->user()->id, $id]);


        $semesters = Semester::all(['id', 'semester_name']);
        return response()->json([

            'coursestat' => $coursestat,
            'coursestatmale' => $coursestatmale,
            'coursestatfemale' => $coursestatfemale,
            'semester' => $semesters
        ]);
    }
    public function hodDashboardCounts()
    {
        $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');
        $semestername = Semester::where('is_current_semester', 1)->value('semester_name');

        $userId = auth()->user()->id;
        $departmentId = Hod::where('user_id', $userId)->value('department_id');
        $query = Course::whereHas('program.department', function ($query) use ($departmentId) {
            $query->where('id', $departmentId);
        })->with(['program.department']);
        $myCourses = $query->count();


        $departmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
          AND b.id = ?
        GROUP BY b.name
        ORDER BY b.name", [$semestername, $departmentId]);

        $maledepartmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS male_student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
          AND b.id = ?
        AND (a.gender = 'Male' OR a.gender = 'male')
        GROUP BY b.name
        ORDER BY b.name", [$semestername, $departmentId]);

        $femaledepartmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS female_student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
        AND b.id = ?
        AND (a.gender = 'Female' OR a.gender = 'female')
        GROUP BY b.name
        ORDER BY b.name", [$semestername, $departmentId]);

        $students = Student::where('department_id', $departmentId)->with('payments.semester', 'department')->where('accepted', 'accepted');
        $studentsCount = $students->count();
        $semesters = Semester::all(['id', 'semester_name']);
        return response()->json([
            'myCourses' => $myCourses,
            'myStudents' => $studentsCount,
            'departmentCount' => $departmentCount,
            'maledepartmentCount' => $maledepartmentCount,
            'femaledepartmentCount' => $femaledepartmentCount,
            'semester' => $semesters
        ]);
    }


    public function hodCounts()
    {

        $semestername = Semester::where('is_current_semester', 1)->value('semester_name');

        $userId = auth()->user()->id;
        $departmentId = Hod::where('user_id', $userId)->value('department_id');
        $query = Course::whereHas('program.department', function ($query) use ($departmentId) {
            $query->where('id', $departmentId);
        })->with(['program.department']);
        $myCourses = $query->count();


        $departmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
          AND b.id = ?
        GROUP BY b.name
        ORDER BY b.name", [$semestername, $departmentId]);

        $maledepartmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS male_student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
          AND b.id = ?
        AND (a.gender = 'Male' OR a.gender = 'male')
        GROUP BY b.name
        ORDER BY b.name", [$semestername, $departmentId]);

        $femaledepartmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS female_student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
        AND b.id = ?
        AND (a.gender = 'Female' OR a.gender = 'female')
        GROUP BY b.name
        ORDER BY b.name", [$semestername, $departmentId]);

        $semesters = Semester::all(['id', 'semester_name']);
        return response()->json([

            'departmentCount' => $departmentCount,
            'maledepartmentCount' => $maledepartmentCount,
            'femaledepartmentCount' => $femaledepartmentCount,
            'semester' => $semesters
        ]);
    }

    public function hodsemestercounts($id)
    {
        $semestername = Semester::where('id', $id)->value('semester_name');

        $userId = auth()->user()->id;
        $departmentId = Hod::where('user_id', $userId)->value('department_id');
        $query = Course::whereHas('program.department', function ($query) use ($departmentId) {
            $query->where('id', $departmentId);
        })->with(['program.department']);
        $myCourses = $query->count();


        $departmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
          AND b.id = ?
        GROUP BY b.name
        ORDER BY b.name", [$semestername, $departmentId]);

        $maledepartmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS male_student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
          AND b.id = ?
        AND (a.gender = 'Male' OR a.gender = 'male')
        GROUP BY b.name
        ORDER BY b.name", [$semestername, $departmentId]);

        $femaledepartmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS female_student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        AND a.semester_name = ?
        AND b.id = ?
        AND (a.gender = 'Female' OR a.gender = 'female')
        GROUP BY b.name
        ORDER BY b.name", [$semestername, $departmentId]);

        $semesters = Semester::all(['id', 'semester_name']);
        return response()->json([

            'departmentCount' => $departmentCount,
            'maledepartmentCount' => $maledepartmentCount,
            'femaledepartmentCount' => $femaledepartmentCount,
            'semester' => $semesters
        ]);
    }
    public function getstudentdepartmentcount()
    {

        $departmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        GROUP BY b.name
        ORDER BY b.name
    ");

        return response()->json([
            'status' => 200,
            'result' => $departmentCount
        ]);
    }


}
