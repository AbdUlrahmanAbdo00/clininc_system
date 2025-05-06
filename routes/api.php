<?php

use App\Http\Controllers\PatientsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/requestOtp', [UserController::class, 'requestOtp']);
Route::post('/verif', [UserController::class, 'verif']);

Route::middleware(['auth:sanctum', 'patient'])->group(function () {
Route::resource('/patient',PatientsController::class);



});
