<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Mail\AdmissionCodeMail;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('send', function () {
//     Mail::to('babajasseh@gmail.com')->send(new AdmissionCodeMail("1343423432"));
// });



Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');
