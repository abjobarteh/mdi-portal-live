<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCode;
use App\Models\AdmissionCodeLocation;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Models\StudentRegisteredCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;
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


        return response()->json([
            'acceptedStudents' => $acceptedStudents,
            'rejectedStudents' => $rejectedStudents,
            'activeStudents' => $activeStudents, // students who have taken courses this semester
            'activeLecturers' => $activeLecturers, // lecturers who have taken courses this semester
            'weekyStudentLogins' => $counts,
            'lecturersWithMostCourses'  => $lecturersWithMostCourses,
        ]);
    }

    public function lecturerDashboardCounts()
    {
        $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');

        $myCourses = StudentRegisteredCourse::with('course')->join('lecturers', 'lecturers.id', '=', 'student_registered_courses.lecturer_id')
            ->where('lecturers.user_id', auth()->user()->id)->where('semester_id', $currentSemesterId)
            ->distinct('lecturer_id')->withCount('student') // Count the number of related students
            ->paginate(10);



        $myTotalStudents = StudentRegisteredCourse::join('lecturers', 'lecturers.id', '=', 'student_registered_courses.lecturer_id')
            ->where('lecturers.user_id', auth()->user()->id)->where('semester_id', $currentSemesterId)
            ->distinct('lecturer_id')
            ->count();



        return response()->json([
            'myCourses' => $myCourses,
            'myStudents' => $myTotalStudents,

        ]);
    }
}
