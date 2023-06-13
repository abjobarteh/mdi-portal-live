<?php

use App\Http\Controllers\Api\Registrar\AdmissionStatusController;
use App\Http\Controllers\Api\Registrar\CourseController;
use App\Http\Controllers\Api\Registrar\DepartmentController;
use App\Http\Controllers\Api\Registrar\EmployeeController;
use App\Http\Controllers\Api\ExcelController;
use App\Http\Controllers\Api\Registrar\GradingSystemController;
use App\Http\Controllers\Api\Registrar\ProgramController;
use App\Http\Controllers\Api\Registrar\ProgramDurationController;
use App\Http\Controllers\Api\Registrar\RolesController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\Finance\StudentPaymentController;
use App\Http\Controllers\Api\Lecturer\StudentMarksController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Registrar\AdmissionCodeController;
use App\Http\Controllers\Api\Registrar\AdmissionCodeLocationController;
use App\Http\Controllers\Api\Registrar\ApplicationsController;
use App\Http\Controllers\Api\Registrar\LecturerController;
use App\Http\Controllers\Api\Registrar\RegistrationStatusController;
use App\Http\Controllers\Api\Registrar\SemesterController;
use App\Http\Controllers\Api\Registrar\SemesterCourseController;
use App\Http\Controllers\Api\Registrar\SessionController;
use App\Http\Controllers\Api\Student\ApplicantCertificateController;
use App\Http\Controllers\Api\Student\ApplicantDeclarationController;
use App\Http\Controllers\Api\Student\ApplicantDeparmentInfoController;
use App\Http\Controllers\Api\Student\ApplicantEducationController;
use App\Http\Controllers\Api\Student\ApplicantPersonalInfoController;
use App\Http\Controllers\Api\Student\RegisterCoursesController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/upload-excel-file', [ExcelController::class, 'uploadExcelFile']);

    ///////////////////////////////////  REGISTRAR END POINTS  ////////////////////////////
    Route::middleware(['registrar'])->group(function () {
        Route::post('/add-employee', [EmployeeController::class, 'store']);
        Route::get('/view-employees', [EmployeeController::class, 'index']);
        Route::get('/employee/{id}', [EmployeeController::class, 'show']);
        Route::delete('/delete-employee/{id}', [EmployeeController::class, 'destroy']);


        Route::post('/add-grading', [GradingSystemController::class, 'store']);
        Route::get('/view-gradings', [GradingSystemController::class, 'index']);
        Route::get('/grading/{id}', [GradingSystemController::class, 'show']);
        Route::put('/grading/{id}', [GradingSystemController::class, 'update']);
        Route::delete('/delete-grading/{id}', [GradingSystemController::class, 'destroy']);


        Route::post('/add-department', [DepartmentController::class, 'store']);
        Route::get('/department/{id}', [DepartmentController::class, 'show']);
        Route::put('/department/{id}', [DepartmentController::class, 'update']);
        Route::delete('/delete-department/{id}', [DepartmentController::class, 'destroy']);
        Route::get('/view-departments', [DepartmentController::class, 'index']);



        Route::post('/add-program-duration', [ProgramDurationController::class, 'store']);
        Route::get('/program-duration/{id}', [ProgramDurationController::class, 'show']);
        Route::put('/program-duration/{id}', [ProgramDurationController::class, 'update']);
        Route::delete('/delete-program-duration/{id}', [ProgramDurationController::class, 'destroy']);


        Route::post('/add-program', [ProgramController::class, 'store']);
        Route::get('/program/{id}', [ProgramController::class, 'show']);
        Route::put('/program/{id}', [ProgramController::class, 'update']);
        Route::delete('/delete-program/{id}', [ProgramController::class, 'destroy']);


        Route::post('/add-course', [CourseController::class, 'store']);
        Route::get('/view-courses', [CourseController::class, 'index']);
        Route::get('/course/{id}', [CourseController::class, 'show']);
        Route::put('/course/{id}', [CourseController::class, 'update']);
        Route::delete('/delete-course/{id}', [CourseController::class, 'destroy']);


        Route::get('/view-admission_codes_locations', [AdmissionCodeLocationController::class, 'index']);
        Route::post('/add-admission_codes_location', [AdmissionCodeLocationController::class, 'store']);
        Route::post('/add-admission_codes_to_location', [AdmissionCodeLocationController::class, 'addAdmissionCodes']);


        Route::delete('/delete-admission_codes_location/{id}', [AdmissionCodeLocationController::class, 'destroy']);



        Route::put('/sell-code/{id}', [AdmissionCodeController::class, 'sellCode']);




        Route::post('/add-session', [SessionController::class, 'store']);
        Route::get('/view-sessions', [SessionController::class, 'index']);

        Route::post('/add-semester', [SemesterController::class, 'store']);
        Route::get('/view-semesters', [SemesterController::class, 'index']);  // middleware should be created for it.


        Route::get('/view-lecturers', [LecturerController::class, 'index']);


        // applications
        Route::post('/view-accepted-applications', [ApplicationsController::class, 'acceptedApplications']);
        Route::post('/view-rejected-applications', [ApplicationsController::class, 'rejectedApplications']);
        Route::post('/view-incoming-applications', [ApplicationsController::class, 'incomingApplications']);
        Route::post('/accept-student-application', [ApplicationsController::class, 'acceptStudentApplication']);
        Route::post('/reject-student-application', [ApplicationsController::class, 'rejectStudentApplication']);




        Route::post('/update-admission', [AdmissionStatusController::class, 'updateAdmissionStatus']);
        Route::post('/update-registration-status', [RegistrationStatusController::class, 'updateRegistrationStatus']);



        Route::get('/view-semester-available-courses/{lecturerId}', [SemesterCourseController::class, 'index']);
        Route::post('/allocate-semester-available-courses', [SemesterCourseController::class, 'allocateSemesterCourses']);

        Route::post('/send-email', [EmailController::class, 'sendEmail']);
    });



    Route::middleware(['admin'])->group(function () {
        /////////////////// users controller ////////////
        Route::post('/add-user', [UserController::class, 'store']);
        Route::get('/view-users', [UserController::class, 'index']);
        Route::put('/update-user/{id}', [UserController::class, 'update']);
        Route::put('/block-user/{id}', [UserController::class, 'blockUser']);
        Route::put('/unblock-user/{id}', [UserController::class, 'unBlockUser']);
        Route::get('/view-roles', [RolesController::class, 'index']);
    });
    Route::put('/update-password/{id}', [UserController::class, 'updatePassword']);
    Route::get('/profile', [ProfileController::class, 'index']);





    Route::middleware(['student'])->group(function () {
        Route::get('/view-student-payments', [StudentPaymentController::class, 'studentPayments']);

        Route::post('/department-courses', [DepartmentController::class, 'deparmentCourses']);  // for the student middleware
        Route::post('/redeem-admission-code', [AdmissioncodeController::class, 'redeemAdmissionCode']);

        Route::post('/add-applicant-education', [ApplicantEducationController::class, 'store']);
        Route::post('/add-applicant-certificates', [ApplicantCertificateController::class, 'store']);
        Route::post('/submit-applicant-personal-info', [ApplicantPersonalInfoController::class, 'store']);
        Route::post('/submit-applicantion', [ApplicantDeclarationController::class, 'submitApplication']);
        Route::post('/submit-applicant-department-info', [ApplicantDeparmentInfoController::class, 'store']);

        Route::get('/running-courses', [CourseController::class, 'runningCourses']);  // for the student middleware

        Route::post('/register-courses', [RegisterCoursesController::class, 'registerCourse']);  // for the student middleware

        Route::post('/un-register-courses', [RegisterCoursesController::class, 'unRegisterCourse']);  // for the student middleware

        Route::get('/transcript-courses', [CourseController::class, 'studentTranscript']);  // for the student middleware

        Route::post('/verify-registration-token', [AuthController::class, 'verifyRegistrationToken']);  // for the student middleware

        // verifyRegistrationToken
    });


    Route::middleware(['finance'])->group(function () {
        Route::post('/add-student-fee', [StudentPaymentController::class, 'addPayment']);
        Route::get('/view-students', [StudentPaymentController::class, 'index']);
        Route::get('/view-semesters', [SemesterController::class, 'index']);  // middleware should be created for it.
        // Route::get('/view-departments', [DepartmentController::class, 'index']);
        Route::get('/view-programs', [ProgramController::class, 'index']);
        Route::get('/view-program-durations', [ProgramDurationController::class, 'index']);
    });

    Route::get('/registration-status', [RegistrationStatusController::class, 'index']); // is needed by the student and registrar
    Route::get('/admission-status', [AdmissionStatusController::class, 'index']); // is needed by the student and registrar
    Route::get('/view-departments', [DepartmentController::class, 'index']); // needed by student and finance

    Route::post('/manage-student-marks', [StudentMarksController::class, 'marks']); // needed by student and finance
    Route::get('/my-courses', [StudentMarksController::class, 'myCourses']); // needed by student and finance



});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
