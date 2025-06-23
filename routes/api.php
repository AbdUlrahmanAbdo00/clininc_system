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
Route::post('/verify', [UserController::class, 'verif']);

Route::middleware(['auth:sanctum', 'patient'])->group(function () {
Route::apiResource('/patient',PatientsController::class);
Route::get('/patient/getAllSpecializations',[DoctorsController::class,'getAllSpecializations']);
Route::get('/doctors/by_specialization/{id}', [DoctorsController::class, 'getDoctorsBySpecialization']);
Route::post('patient/book/apointment',[AppointmentController::class,'booking']);
Route::post('patient/book/apointment1',[AppointmentController::class,'getAvailableSlotsForDay']);
Route::post('patient/book/apointment2',[AppointmentController::class,'book']);
});
Route::post('admin/specialization/upload-image', [DoctorsController::class, 'uploadSpecializationImage']);

Route::post('/Doctor/create',[DoctorsController::class,'store'])->middleware('auth:sanctum');
Route::post('/add_shift',[ShiftsController::class,'store']);
Route::post('/assignShift',[ShiftsController::class,'assignShiftToDoctor']);

