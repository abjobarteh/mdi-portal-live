<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCode;
use App\Models\AdmissionCodeLocation;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Models\StudentRegisteredCourse;
use Illuminate\Http\Request;

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

        return response()->json([
            'acceptedStudents' => $acceptedStudents,
            'rejectedStudents' => $rejectedStudents,
            'activeStudents' => $activeStudents, // students who have taken courses this semester
            'activeLecturers' => $activeLecturers, // lecturers who have taken courses this semester
        ]);
    }
}
