<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\UserController;

Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [LoginController::class, 'index']);
    Route::post('/auth/login', [LoginController::class, 'create'])->name("login.post");
});

Route::get("/patient", [PatientController::class, 'index']);
Route::get("/patient/queue", [PatientController::class, 'queue']);
Route::get("/patient/history", [PatientController::class, 'history']);
Route::get("/diagnosis", [DiagnosisController::class, 'index']);
Route::get("/treatment", [TreatmentController::class, 'index']);
Route::get("/user", [UserController::class, 'index']);

Route::get('/', function () {
    return view('dashboard.home');
});
