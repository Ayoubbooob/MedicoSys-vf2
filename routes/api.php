<?php

use App\Http\Controllers\AppointmentRequestController;
use App\Http\Controllers\PatientLoginController;
use App\Http\Controllers\PatientRegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/******* POUR L'application Mobile ******/

////Registration endpoint -- localhost:8000/api/register
//Route::post('register', 'App\Http\Controllers\PatientRegistrationController@register');

//login  endpoint -- localhost:8000/api/login
//Route::post('patient/login', 'App\Http\Controllers\PatientLoginController@login');
//
////logout  endpoint -- localhost:8000/api/logout
//Route::post('logout', 'App\Http\Controllers\PatientLoginController@logout');

//storing IMC endpoint -- localhost:8000/api/patients/{patient_id}/imc

Route::post('patients/{patient_id}/imc', 'App\Http\Controllers\IMCController@store');

//GETTING ALL IMC OF A PATIENT endpoint - DESIGN A DIAGRAM  -- localhost:8000/api/patients/{patient_id}/imc

Route::get('patients/{patient_id}/getallimcs', 'App\Http\Controllers\IMCController@index');





//new version

//login  endpoint -- localhost:8000/api/patient/login
Route::post('/patient/login', [PatientLoginController::class, 'login'])->name('patient.login');
Route::post('/patient/logout', [PatientLoginController::class, 'logout'])->name('patient.logout');

//Registration endpoint -- localhost:8000/api/register
Route::post('/patient/register', [PatientRegistrationController::class, 'register'])->name('patient.register');

//storing IMC endpoint -- localhost:8000/api/patients/{patient_id}/imc
Route::post('patient/{patient_id}/imc', 'App\Http\Controllers\IMCController@store');

//GETTING ALL IMC OF A PATIENT endpoint - DESIGN A DIAGRAM  -- localhost:8000/api/patients/{patient_id}/imc
Route::get('patient/{patient_id}/getallimcs', 'App\Http\Controllers\IMCController@index');


//Appointment Request from patient
Route::post('/appointment/request', [AppointmentRequestController::class, 'create'])->name('appointment.request');


