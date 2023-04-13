<?php

use App\Http\Controllers\Api\Registrar\CourseController;
use App\Http\Controllers\Api\Registrar\DepartmentController;
use App\Http\Controllers\Api\Registrar\EmployeeController;
use App\Http\Controllers\Api\ExcelController;
use App\Http\Controllers\Api\Registrar\GradingSystemController;
use App\Http\Controllers\Api\Registrar\ProgramController;
use App\Http\Controllers\Api\Registrar\ProgramDurationController;
use App\Http\Controllers\Api\Registrar\RolesController;
use App\Http\Controllers\Api\Admin\UserController;
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


    Route::middleware(['registrar'])->group(function () {
        Route::post('/add-employee', [EmployeeController::class, 'store']);
        Route::get('/view-employees', [EmployeeController::class, 'index']);
        Route::get('/employee/{id}', [EmployeeController::class, 'show']);

        Route::post('/add-grading', [GradingSystemController::class, 'store']);
        Route::get('/view-gradings', [GradingSystemController::class, 'index']);
        Route::get('/grade/{id}', [GradingSystemController::class, 'show']);

        Route::post('/add-department', [DepartmentController::class, 'store']);
        Route::get('/view-departments', [DepartmentController::class, 'index']);
        Route::get('/department/{id}', [DepartmentController::class, 'show']);

        Route::post('/add-program-duration', [ProgramDurationController::class, 'store']);
        Route::get('/view-program-durations', [ProgramDurationController::class, 'index']);
        Route::get('/program-duration/{id}', [ProgramDurationController::class, 'show']);

        Route::post('/add-program', [ProgramController::class, 'store']);
        Route::get('/view-programs', [ProgramController::class, 'index']);
        Route::get('/program/{id}', [ProgramController::class, 'show']);

        Route::post('/add-course', [CourseController::class, 'store']);
        Route::get('/view-courses', [CourseController::class, 'index']);
        Route::get('/course/{id}', [CourseController::class, 'show']);

        Route::post('/add-user', [UserController::class, 'store']);
    });


    Route::middleware(['admin'])->group(function () {
        /////////////////// users controller ////////////
        Route::get('/view-users', [UserController::class, 'index']);
        Route::put('/update-user/{id}', [UserController::class, 'update']);
        Route::put('/block-user/{id}', [UserController::class, 'blockUser']);
        Route::put('/unblock-user/{id}', [UserController::class, 'unBlockUser']);

        Route::get('/view-roles', [RolesController::class, 'index']);
    });
    Route::put('/update-password/{id}', [UserController::class, 'updatePassword']);
});

Route::post('login', [AuthController::class, 'login']);
