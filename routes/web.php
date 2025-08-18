<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chat', function () {
    return view('chat');
});
Route::get('/dashboard/index', function () {
    return view('/dashboard/index');
});
Route::get('/dashboard/reports', function () {
    return view('/dashboard/reports');
});
Route::get('/dashboard/doctors/create', function () {
    return view('/dashboard/doctors/create');
});

Route::get('/dashboard/doctors/edit', function () {
    return view('/dashboard/doctors/edit');
});
Route::get('/dashboard/doctors/index', function () {
    return view('/dashboard/doctors/index');
});
Route::get('/dashboard/layouts/app', function () {
    return view('/dashboard/layouts/app');
});
Route::get('/dashboard/patients/index', function () {
    return view('/dashboard/patients/index');
});
Route::get('/dashboard/shifts/index', function () {
    return view('/dashboard/shifts/index');
});
/*
|--------------------------------------------------------------------------
| Dashboard Routes - لوحة التحكم
|--------------------------------------------------------------------------
| 
| Routes المطلوبة للوحة التحكم:
|
| // الصفحة الرئيسية للوحة التحكم
| Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
|
| // إدارة الأطباء
| Route::get('/dashboard/doctors', [DashboardController::class, 'doctors'])->name('dashboard.doctors.index');
| Route::get('/dashboard/doctors/create', [DashboardController::class, 'doctorsCreate'])->name('dashboard.doctors.create');
| Route::post('/dashboard/doctors', [DashboardController::class, 'doctorsStore'])->name('dashboard.doctors.store');
| Route::get('/dashboard/doctors/{id}/edit', [DashboardController::class, 'doctorsEdit'])->name('dashboard.doctors.edit');
| Route::put('/dashboard/doctors/{id}', [DashboardController::class, 'doctorsUpdate'])->name('dashboard.doctors.update');
| Route::delete('/dashboard/doctors/{id}', [DashboardController::class, 'doctorsDestroy'])->name('dashboard.doctors.destroy');
|
| // إدارة الاختصاصات
| Route::get('/dashboard/specializations', [DashboardController::class, 'specializations'])->name('dashboard.specializations.index');
| Route::get('/dashboard/specializations/create', [DashboardController::class, 'specializationsCreate'])->name('dashboard.specializations.create');
| Route::post('/dashboard/specializations', [DashboardController::class, 'specializationsStore'])->name('dashboard.specializations.store');
| Route::get('/dashboard/specializations/{id}/edit', [DashboardController::class, 'specializationsEdit'])->name('dashboard.specializations.edit');
| Route::put('/dashboard/specializations/{id}', [DashboardController::class, 'specializationsUpdate'])->name('dashboard.specializations.update');
| Route::delete('/dashboard/specializations/{id}', [DashboardController::class, 'specializationsDestroy'])->name('dashboard.specializations.destroy');
|
| // إدارة الشيفتات
| Route::get('/dashboard/shifts', [DashboardController::class, 'shifts'])->name('dashboard.shifts.index');
| Route::get('/dashboard/shifts/create', [DashboardController::class, 'shiftsCreate'])->name('dashboard.shifts.create');
| Route::post('/dashboard/shifts', [DashboardController::class, 'shiftsStore'])->name('dashboard.shifts.store');
| Route::get('/dashboard/shifts/{id}/edit', [DashboardController::class, 'shiftsEdit'])->name('dashboard.shifts.edit');
| Route::put('/dashboard/shifts/{id}', [DashboardController::class, 'shiftsUpdate'])->name('dashboard.shifts.update');
| Route::delete('/dashboard/shifts/{id}', [DashboardController::class, 'shiftsDestroy'])->name('dashboard.shifts.destroy');
|
| // إدارة المرضى
| Route::get('/dashboard/patients', [DashboardController::class, 'patients'])->name('dashboard.patients.index');
| Route::get('/dashboard/patients/create', [DashboardController::class, 'patientsCreate'])->name('dashboard.patients.create');
| Route::post('/dashboard/patients', [DashboardController::class, 'patientsStore'])->name('dashboard.patients.store');
| Route::get('/dashboard/patients/{id}', [DashboardController::class, 'patientsShow'])->name('dashboard.patients.show');
| Route::get('/dashboard/patients/{id}/edit', [DashboardController::class, 'patientsEdit'])->name('dashboard.patients.edit');
| Route::put('/dashboard/patients/{id}', [DashboardController::class, 'patientsUpdate'])->name('dashboard.patients.update');
| Route::delete('/dashboard/patients/{id}', [DashboardController::class, 'patientsDestroy'])->name('dashboard.patients.destroy');
|
| // إدارة المواعيد
| Route::get('/dashboard/appointments', [DashboardController::class, 'appointments'])->name('dashboard.appointments.index');
| Route::get('/dashboard/appointments/create', [DashboardController::class, 'appointmentsCreate'])->name('dashboard.appointments.create');
| Route::post('/dashboard/appointments', [DashboardController::class, 'appointmentsStore'])->name('dashboard.appointments.store');
| Route::get('/dashboard/appointments/{id}', [DashboardController::class, 'appointmentsShow'])->name('dashboard.appointments.show');
| Route::get('/dashboard/appointments/{id}/edit', [DashboardController::class, 'appointmentsEdit'])->name('dashboard.appointments.edit');
| Route::put('/dashboard/appointments/{id}', [DashboardController::class, 'appointmentsUpdate'])->name('dashboard.appointments.update');
| Route::delete('/dashboard/appointments/{id}', [DashboardController::class, 'appointmentsDestroy'])->name('dashboard.appointments.destroy');
|
| // التقارير
| Route::get('/dashboard/reports', [DashboardController::class, 'reports'])->name('dashboard.reports');
|
*/

/*
|--------------------------------------------------------------------------
| Dashboard Routes - لوحة التحكم
|--------------------------------------------------------------------------
| 
| Routes المطلوبة للوحة التحكم:
|
| // الصفحة الرئيسية للوحة التحكم
| Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
|
| // إدارة الأطباء
| Route::get('/dashboard/doctors', [DashboardController::class, 'doctors'])->name('dashboard.doctors.index');
| Route::get('/dashboard/doctors/create', [DashboardController::class, 'doctorsCreate'])->name('dashboard.doctors.create');
| Route::post('/dashboard/doctors', [DashboardController::class, 'doctorsStore'])->name('dashboard.doctors.store');
| Route::get('/dashboard/doctors/{id}/edit', [DashboardController::class, 'doctorsEdit'])->name('dashboard.doctors.edit');
| Route::put('/dashboard/doctors/{id}', [DashboardController::class, 'doctorsUpdate'])->name('dashboard.doctors.update');
| Route::delete('/dashboard/doctors/{id}', [DashboardController::class, 'doctorsDestroy'])->name('dashboard.doctors.destroy');
|
| // إدارة الاختصاصات
| Route::get('/dashboard/specializations', [DashboardController::class, 'specializations'])->name('dashboard.specializations.index');
| Route::get('/dashboard/specializations/create', [DashboardController::class, 'specializationsCreate'])->name('dashboard.specializations.create');
| Route::post('/dashboard/specializations', [DashboardController::class, 'specializationsStore'])->name('dashboard.specializations.store');
| Route::get('/dashboard/specializations/{id}/edit', [DashboardController::class, 'specializationsEdit'])->name('dashboard.specializations.edit');
| Route::put('/dashboard/specializations/{id}', [DashboardController::class, 'specializationsUpdate'])->name('dashboard.specializations.update');
| Route::delete('/dashboard/specializations/{id}', [DashboardController::class, 'specializationsDestroy'])->name('dashboard.specializations.destroy');
|
| // إدارة الشيفتات
| Route::get('/dashboard/shifts', [DashboardController::class, 'shifts'])->name('dashboard.shifts.index');
| Route::get('/dashboard/shifts/create', [DashboardController::class, 'shiftsCreate'])->name('dashboard.shifts.create');
| Route::post('/dashboard/shifts', [DashboardController::class, 'shiftsStore'])->name('dashboard.shifts.store');
| Route::get('/dashboard/shifts/{id}/edit', [DashboardController::class, 'shiftsEdit'])->name('dashboard.shifts.edit');
| Route::put('/dashboard/shifts/{id}', [DashboardController::class, 'shiftsUpdate'])->name('dashboard.shifts.update');
| Route::delete('/dashboard/shifts/{id}', [DashboardController::class, 'shiftsDestroy'])->name('dashboard.shifts.destroy');
|
| // إدارة المرضى
| Route::get('/dashboard/patients', [DashboardController::class, 'patients'])->name('dashboard.patients.index');
| Route::get('/dashboard/patients/create', [DashboardController::class, 'patientsCreate'])->name('dashboard.patients.create');
| Route::post('/dashboard/patients', [DashboardController::class, 'patientsStore'])->name('dashboard.patients.store');
| Route::get('/dashboard/patients/{id}', [DashboardController::class, 'patientsShow'])->name('dashboard.patients.show');
| Route::get('/dashboard/patients/{id}/edit', [DashboardController::class, 'patientsEdit'])->name('dashboard.patients.edit');
| Route::put('/dashboard/patients/{id}', [DashboardController::class, 'patientsUpdate'])->name('dashboard.patients.update');
| Route::delete('/dashboard/patients/{id}', [DashboardController::class, 'patientsDestroy'])->name('dashboard.patients.destroy');
|
| // إدارة المواعيد
| Route::get('/dashboard/appointments', [DashboardController::class, 'appointments'])->name('dashboard.appointments.index');
| Route::get('/dashboard/appointments/create', [DashboardController::class, 'appointmentsCreate'])->name('dashboard.appointments.create');
| Route::post('/dashboard/appointments', [DashboardController::class, 'appointmentsStore'])->name('dashboard.appointments.store');
| Route::get('/dashboard/appointments/{id}', [DashboardController::class, 'appointmentsShow'])->name('dashboard.appointments.show');
| Route::get('/dashboard/appointments/{id}/edit', [DashboardController::class, 'appointmentsEdit'])->name('dashboard.appointments.edit');
| Route::put('/dashboard/appointments/{id}', [DashboardController::class, 'appointmentsUpdate'])->name('dashboard.appointments.update');
| Route::delete('/dashboard/appointments/{id}', [DashboardController::class, 'appointmentsDestroy'])->name('dashboard.appointments.destroy');
|
| // التقارير
| Route::get('/dashboard/reports', [DashboardController::class, 'reports'])->name('dashboard.reports');
|
*/
