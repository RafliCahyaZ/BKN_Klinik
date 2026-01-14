<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\UserController;

Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [LoginController::class, 'index']);
    Route::post('/auth/login', [LoginController::class, 'create'])->name("login.post");
});

Route::prefix("patient")->name("patient.")->group(function () {
    Route::get("/", [PatientController::class, 'index'])->name("index");
    Route::get("/create", [PatientController::class, 'create'])->name("create");
    Route::get("/queue", [PatientController::class, 'queue'])->name("queue");
    Route::get("/history", [PatientController::class, 'history'])->name("history");
    Route::post("/store", [PatientController::class, 'store'])->name("store");
    Route::get("//edit/{id}", [PatientController::class, 'edit'])->name("edit.view");
    Route::post("/edit/{id}", [PatientController::class, 'editAction'])->name("edit.action");
    Route::delete("/destroy/{id}", [PatientController::class, 'destroy'])->name("destroy");
});

Route::prefix("diagnosis")->name("diagnosis.")->group(function () {
    Route::get("/", [DiagnosisController::class, 'index'])->name("index");
    Route::get("/create", [DiagnosisController::class, 'create'])->name("create");
    Route::post("/store", [DiagnosisController::class, 'store'])->name("store");
    Route::get("//edit/{id}", [DiagnosisController::class, 'edit'])->name("edit.view");
    Route::post("/edit/{id}", [DiagnosisController::class, 'editAction'])->name("edit.action");
    Route::delete("/destroy/{id}", [DiagnosisController::class, 'destroy'])->name("destroy");
});


Route::prefix("treatment")->name("treatment.")->group(function () {
    Route::get("/", [TreatmentController::class, 'index'])->name("index");
    Route::get("/create", [TreatmentController::class, 'create'])->name("create");
    Route::post("/store", [TreatmentController::class, 'store'])->name("store");
    Route::get("//edit/{id}", [TreatmentController::class, 'edit'])->name("edit.view");
    Route::post("/edit/{id}", [TreatmentController::class, 'editAction'])->name("edit.action");
    Route::delete("/destroy/{id}", [TreatmentController::class, 'destroy'])->name("destroy");
});

Route::prefix("user")->name("user.")->group(function () {
    Route::get("/", [UserController::class, 'index'])->name("index");
    Route::get("/create", [UserController::class, 'create'])->name("create");
    Route::post("/store", [UserController::class, 'store'])->name("store");
    Route::get("//edit/{id}", [UserController::class, 'edit'])->name("edit.view");
    Route::post("/edit/{id}", [UserController::class, 'editAction'])->name("edit.action");
});

Route::prefix("registration")->name("registration.")->group(function () {
    Route::get("/create/{patientId}", [RegistrationController::class, 'create'])->name("create");
    Route::post("/create/{patientId}", [RegistrationController::class, 'store'])->name("store");
});

Route::get('/', function () {
    return view('dashboard.home');
});
