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

Route::get("/patient", [PatientController::class, 'index'])->name("patient.index");
Route::get("/patient/create", [PatientController::class, 'create']);
Route::get("/patient/queue", [PatientController::class, 'queue']);
Route::get("/patient/history", [PatientController::class, 'history']);
Route::post("/patient/store", [PatientController::class, 'store'])->name("patient.store");
Route::get("/patient/edit/{id}", [PatientController::class, 'edit'])->name("patient.edit.view");
Route::post("/patient/edit/{id}", [PatientController::class, 'editAction'])->name("patient.edit.action");
Route::delete("/patient/destroy/{id}", [PatientController::class, 'destroy'])->name("patient.destroy");

Route::get("/diagnosis", [DiagnosisController::class, 'index']);
Route::get("/treatment", [TreatmentController::class, 'index']);
Route::get("/user", [UserController::class, 'index']);

Route::get('/', function () {
    return view('dashboard.home');
});
