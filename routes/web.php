<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/chat', function () {
    return view('chat');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

/*
|--------------------------------------------------------------------------
| Dashboard Routes - لوحة التحكم
|--------------------------------------------------------------------------
*/

// Dashboard Routes (Protected)
Route::middleware(['auth'])->group(function () {
    // الصفحة الرئيسية للوحة التحكم

    // إدارة الأطباء
    Route::get('/dashboard/doctors', [DashboardController::class, 'doctors'])->name('dashboard.doctors.index');
    Route::get('/dashboard/doctors/create', [DashboardController::class, 'doctorsCreate'])->name('dashboard.doctors.create');
    Route::get('/dashboard/doctors/{id}/edit', [DashboardController::class, 'doctorsEdit'])->name('dashboard.doctors.edit');
Route::put('/dashboard/doctors/{id}', [DashboardController::class, 'doctorsUpdate'])->name('dashboard.doctors.update');
Route::post('/dashboard/doctors', [DashboardController::class, 'createDoctor'])->name('dashboard.doctors.store');

    // إدارة الاختصاصات
    Route::get('/dashboard/specializations', [DashboardController::class, 'specializations'])->name('dashboard.specializations.index');
    Route::get('/dashboard/specializations/create', [DashboardController::class, 'specializationsCreate'])->name('dashboard.specializations.create');
    Route::get('/dashboard/specializations/{id}/edit', [DashboardController::class, 'specializationsEdit'])->name('dashboard.specializations.edit');
    Route::put('/dashboard/specializations/{id}', [DashboardController::class, 'specializationsUpdate'])->name('dashboard.specializations.update');
    Route::post('/dashboard/specializations', [DashboardController::class, 'createSpecialization'])->name('dashboard.specializations.store');
    Route::get('/dashboard/specializations-api', [DashboardController::class, 'getAllSpecializations'])->name('dashboard.specializations.api');

    // إدارة الشيفتات
    Route::get('/dashboard/shifts', [DashboardController::class, 'shifts'])->name('dashboard.shifts.index');
    Route::get('/dashboard/shifts/create', [DashboardController::class, 'shiftsCreate'])->name('dashboard.shifts.create');
    Route::get('/dashboard/shifts/{id}/edit', [DashboardController::class, 'shiftsEdit'])->name('dashboard.shifts.edit');
    Route::get('/dashboard/shifts/{id}/edit-data', [DashboardController::class, 'shiftsEditData'])->name('dashboard.shifts.edit_data');
    Route::get('/dashboard/shifts/{id}/edit-doctor', [DashboardController::class, 'shiftsEditDoctor'])->name('dashboard.shifts.edit_doctor');
    Route::post('/dashboard/shifts', [DashboardController::class, 'createShift'])->name('dashboard.shifts.store');
    Route::post('/dashboard/shifts/assign', [DashboardController::class, 'assignShiftToDoctor'])->name('dashboard.shifts.assign');
    Route::put('/dashboard/shifts/{id}/update-data', [DashboardController::class, 'shiftsUpdateData'])->name('dashboard.shifts.update_data');
    Route::put('/dashboard/shifts/{id}/update-doctor', [DashboardController::class, 'shiftsUpdateDoctor'])->name('dashboard.shifts.update_doctor');

    // إدارة المرضى
    Route::get('/dashboard/patients', [DashboardController::class, 'patients'])->name('dashboard.patients.index');
    Route::get('/dashboard/patients/{id}/edit', [DashboardController::class, 'patientsEdit'])->name('dashboard.patients.edit');
    Route::put('/dashboard/patients/{id}', [DashboardController::class, 'patientsUpdate'])->name('dashboard.patients.update');
    Route::post('/dashboard/patients/{id}/recharge', [DashboardController::class, 'patientsRecharge'])->name('dashboard.patients.recharge');

    // إدارة المواعيد
    Route::get('/dashboard/appointments', [DashboardController::class, 'appointments'])->name('dashboard.appointments.index');

    // التقارير
    Route::get('/dashboard/reports', [DashboardController::class, 'reports'])->name('dashboard.reports');
    
    // الملف الشخصي
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('auth.profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('auth.profile.update');
    Route::post('/profile/change-password', [AuthController::class, 'changePassword'])->name('auth.profile.change-password');
});
