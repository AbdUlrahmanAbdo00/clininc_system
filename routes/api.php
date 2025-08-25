<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\NotificationController;
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
Route::post('/Secretary_login', [UserController::class, 'Secretary_login']);

Route::post('/patient',[PatientsController::class,'store'])->middleware('auth:sanctum');
Route::post('/logout', [UserController::class, 'logout'])
    ->middleware('auth:sanctum');
Route::post('/logout-all', [UserController::class, 'logoutFromAll'])
    ->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'doctor'])->group(function () {
    Route::post('/appointments/doctor', [AppointmentController::class, 'showBookedappointmentForDoctor']);
    Route::get('/doctors/info', [DoctorsController::class, 'getDoctorByToken']);
});

//appointments_pay
Route::middleware('auth:sanctum')->post('/appointments/{id}/pay', [AppointmentController::class, 'payAppointment']);
Route::middleware('auth:sanctum')->post('/appointments/secretary/{id}/pay', [AppointmentController::class, 'payAppointment_bysecretary']);


Route::middleware(['auth:sanctum', 'patient'])->group(function () {

Route::get('/getAllSpecializations',[DoctorsController::class,'getAllSpecializations']);
Route::post('patient/book/apointment',[AppointmentController::class,'booking']);
Route::post('patient/book/apointment1',[AppointmentController::class,'getAvailableSlotsForDay']);
Route::post('patient/book/apointment2',[AppointmentController::class,'book']);
Route::post('/appointments/patient', [AppointmentController::class, 'showBookedappointmentForPatient']);
Route::delete('/specializations/{id}', [DoctorsController::class, 'deleteSpecialization']);
Route::get('/send-notification', [PatientsController::class, 'sendTestNotification']);
Route::get('/doctors/info/{id}', [DoctorsController::class, 'getDoctorById']);
Route::get('/patients/info/{id}', [PatientsController::class, 'getPatientById']);
Route::get('/patients/info/', [PatientsController::class, 'getAuthenticatedPatientData']);
Route::delete('/appointments/{id}', [AppointmentController::class, 'deleteAppointment']);
Route::get('/doctors/by_specialization/{id}', [DoctorsController::class, 'getDoctorsBySpecialization']);

});
Route::post('patient/cancel/apointment',[AppointmentController::class,'cancel'])->middleware('auth:sanctum');

//secretary
Route::post('/appointments/secretary', [AppointmentController::class, 'showBookedappointmentForSecretary']);

Route::get('/doctorsWorkingToday', [AppointmentController::class, 'doctorsWorkingToday']);



Route::post('admin/specialization/upload-image', [DoctorsController::class, 'uploadSpecializationImage']);
Route::post('doctor/upload-image', [DoctorsController::class, 'uploadDoctorImage']);

Route::post('/Doctor/create',[DoctorsController::class,'store'])->middleware('auth:sanctum');
Route::post('/add_shift',[ShiftsController::class,'store']);
Route::post('/assignShift',[ShiftsController::class,'assignShiftToDoctor']);

// Notification APIs
Route::post('/notifications/send_notification', [NotificationController::class, 'sendTestNotification']);

// Archive APIs
Route::get('/archive/patient/show_upcoming_appointments', [ArchiveController::class, 'showUpcomingArchive_P'])
    ->middleware('auth:sanctum', 'pagination');
Route::get('/archive/doctor/show_upcoming_appointments', [ArchiveController::class, 'showUpcomingArchive_D'])
    ->middleware('auth:sanctum', 'doctor', 'pagination');
Route::get('/archive/patient/show_finished_appointments', [ArchiveController::class, 'showFinishedArchive_P'])
    ->middleware('auth:sanctum', 'pagination');
Route::get('/archive/doctor/show_finished_appointments', [ArchiveController::class, 'showFinishedArchive_D'])
    ->middleware('auth:sanctum', 'doctor', 'pagination');

// Examination
Route::post('examination/add_examination', [ExaminationController::class, 'addExamin'])
    ->middleware('auth:sanctum', 'doctor');
    Route::post('examination/add_examination_by_apointment_id', [ExaminationController::class, 'addExamin_by_appointment_id'])
    ->middleware('auth:sanctum', 'doctor');

// Medicines
Route::post('medicine/add_medicine_db', [MedicineController::class, 'addMedicineToDB'])
    ->middleware('auth:sanctum', 'doctor');
Route::post('medicine/get_medicines_substring', [MedicineController::class, 'getMedicinesBySubstring'])
    ->middleware('auth:sanctum', 'doctor');

//report 
Route::get('/report/{id}', [PatientsController::class, 'report']);
//medicalInfo
Route::get('/medicalInfo', [PatientsController::class, 'medicalInfo']);
//confirmTaken
Route::post('/confirmTaken', [PatientsController::class, 'confirmTaken']);
