<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::get('/chat', function () {
    return view('chat');
});

// Auth Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('auth.login');
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
Route::middleware(['CheckAuthToken'])->group(function () {
    // الصفحة الرئيسية للوحة التحكم

    // إدارة الأطباء
    Route::get('/dashboard/doctors', [DashboardController::class, 'doctors'])->name('dashboard.doctors.index');
    Route::get('/dashboard/doctors/create', [DashboardController::class, 'doctorsCreate'])->name('dashboard.doctors.create');
    Route::get('/dashboard/doctors/{id}/edit', [DashboardController::class, 'doctorsEdit'])->name('dashboard.doctors.edit');
    Route::post('/dashboard/doctors', [DashboardController::class, 'createDoctor'])->name('dashboard.doctors.store');
    Route::put('/dashboard/doctors/{id}', [DashboardController::class, 'updateDoctor'])->name('dashboard.doctors.update');
    Route::delete('/dashboard/doctors/{id}', [DashboardController::class, 'deleteDoctor'])->name('dashboard.doctors.destroy');

    // إدارة الاختصاصات
    Route::get('/dashboard/specializations', [DashboardController::class, 'specializations'])->name('dashboard.specializations.index');
    Route::get('/dashboard/specializations/create', [DashboardController::class, 'specializationsCreate'])->name('dashboard.specializations.create');
    Route::get('/dashboard/specializations/{id}/edit', [DashboardController::class, 'specializationsEdit'])->name('dashboard.specializations.edit');
    Route::post('/dashboard/specializations', [DashboardController::class, 'createSpecialization'])->name('dashboard.specializations.store');
    Route::get('/dashboard/specializations-api', [DashboardController::class, 'getAllSpecializations'])->name('dashboard.specializations.api');

    // إدارة الشيفتات
    Route::get('/dashboard/shifts', [DashboardController::class, 'shifts'])->name('dashboard.shifts.index');
    Route::get('/dashboard/shifts/create', [DashboardController::class, 'shiftsCreate'])->name('dashboard.shifts.create');
    Route::get('/dashboard/shifts/{id}/edit', [DashboardController::class, 'shiftsEdit'])->name('dashboard.shifts.edit');
    Route::post('/dashboard/shifts', [DashboardController::class, 'createShift'])->name('dashboard.shifts.store');
    Route::post('/dashboard/shifts/assign', [DashboardController::class, 'assignShiftToDoctor'])->name('dashboard.shifts.assign');

    // إدارة المرضى
    Route::get('/dashboard/patients', [DashboardController::class, 'patients'])->name('dashboard.patients.index');

    // إدارة المواعيد
    Route::get('/dashboard/appointments', [DashboardController::class, 'appointments'])->name('dashboard.appointments.index');

    // التقارير
    Route::get('/dashboard/reports', [DashboardController::class, 'reports'])->name('dashboard.reports');
    
    // الملف الشخصي
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('auth.profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('auth.profile.update');
    Route::post('/profile/change-password', [AuthController::class, 'changePassword'])->name('auth.profile.change-password');
});
