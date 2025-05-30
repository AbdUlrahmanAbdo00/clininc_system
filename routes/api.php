<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\ShiftsController;
use App\Http\Controllers\UserController;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Role;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/requestOtp', [UserController::class, 'requestOtp']);
Route::post('/verif', [UserController::class, 'verif']);

Route::middleware(['auth:sanctum', 'patient'])->group(function () {
Route::apiResource('/patient',PatientsController::class);



});
Route::post('/Doctor/create',[DoctorsController::class,'store'])->middleware('auth:sanctum');
Route::post('/add_shift',[ShiftsController::class,'store']);
Route::post('/assignShift',[ShiftsController::class,'assignShiftToDoctor']);
Route::post('patient/book/apointment',[AppointmentController::class,'booking'])->middleware('auth:sanctum');