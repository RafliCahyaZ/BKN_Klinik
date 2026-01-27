<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ReportController;

Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [LoginController::class, 'index'])->name("login");
    Route::post('/auth/login', [LoginController::class, 'create'])->name("login.post");
});

Route::middleware("auth")->group(function () {
    Route::post("/auth/logout", [LoginController::class, 'logout'])->name("auth.logout");

    Route::middleware("can:is-admin")->group(function () {
        Route::prefix("user")->name("user.")->group(function () {
            Route::get("/", [UserController::class, 'index'])->name("index");
            Route::get("/create", [UserController::class, 'create'])->name("create");
            Route::post("/store", [UserController::class, 'store'])->name("store");
            Route::get("//edit/{id}", [UserController::class, 'edit'])->name("edit.view");
            Route::post("/edit/{id}", [UserController::class, 'editAction'])->name("edit.action");
        });
    });

    Route::get("/report", [ReportController::class, 'index'])->name("report.index");
    Route::post("/report", [ReportController::class, 'create'])->name("report.create");

    Route::prefix("patient")->name("patient.")->group(function () {
        Route::get("/", [PatientController::class, 'index'])->name("index");
        Route::get("/create", [PatientController::class, 'create'])->name("create");
        Route::get("/queue", [PatientController::class, 'queue'])->name("queue");

        Route::get("/{id}/history", [PatientController::class, 'userHistory'])->name("user.history");
        Route::get("/{id}/history/print", [PatientController::class, 'userHistoryPrint'])->name("user.history.print");

        Route::get("/history", [PatientController::class, 'history'])->name("history");
        Route::get("/history/{id}", [HistoryController::class, 'show'])->name("history.show");
        Route::get("/history/print/{id}", [HistoryController::class, 'print'])->name("history.print");

        // ini print pasien
        Route::get("/print", [PatientController::class, 'print'])->name("print");

        Route::post("/store", [PatientController::class, 'store'])->name("store");
        Route::get("//edit/{id}", [PatientController::class, 'edit'])->name("edit.view");
        Route::post("/edit/{id}", [PatientController::class, 'editAction'])->name("edit.action");
        Route::delete("/destroy/{id}", [PatientController::class, 'destroy'])->name("destroy");

        Route::get("/appointment/{id}", [AppointmentController::class, 'create'])->name("appointment.view");
        Route::post("/appointment/{id}", [AppointmentController::class, 'createAction'])->name("appointment.action");
    });

    Route::prefix("registration")->name("registration.")->group(function () {
        Route::get("/create/{patientId}", [RegistrationController::class, 'create'])->name("create");
        Route::post("/create/{patientId}", [RegistrationController::class, 'store'])->name("store");
        Route::get("/{id}/print", [HistoryController::class, 'show'])->name("print");
        // Route::get("/print", [RegistrationController::class, 'print'])->name("print");
    });

    Route::prefix("medicine")->name("medicine.")->group(function () {
        Route::get("/", [MedicineController::class, 'index'])->name("index");
        Route::get("/print", [MedicineController::class, 'print'])->name("print");
        Route::get("/create", [MedicineController::class, 'create'])->name("create");
        Route::post("/store", [MedicineController::class, 'store'])->name("store");
        Route::get("/edit/{id}", [MedicineController::class, 'edit'])->name("edit.view");
        Route::post("/edit/{id}", [MedicineController::class, 'editAction'])->name("edit.action");
        Route::delete("/destroy/{id}", [MedicineController::class, 'destroy'])->name("destroy");

        Route::get("/{id}/history", [MedicineController::class, 'history'])->name("history");
        Route::get("/{id}/transaction", [MedicineController::class, 'transaction'])->name("transaction.create.view");
        Route::post("/{id}/transaction", [MedicineController::class, 'transactionAction'])->name("transaction.create.action");
    });


    Route::get('/', function () {
        return view('dashboard.home');
    });

    Route::get("/print", [PatientController::class, 'print'])->name("print");

    Route::post("/store", [PatientController::class, 'store'])->name("store");
    Route::get("//edit/{id}", [PatientController::class, 'edit'])->name("edit.view");
    Route::post("/edit/{id}", [PatientController::class, 'editAction'])->name("edit.action");
    Route::delete("/destroy/{id}", [PatientController::class, 'destroy'])->name("destroy");

    Route::get("/appointment/{id}", [AppointmentController::class, 'create'])->name("appointment.view");
    Route::post("/appointment/{id}", [AppointmentController::class, 'createAction'])->name("appointment.action");
});

Route::prefix("diagnosis")->name("diagnosis.")->group(function () {
    Route::get("/", [DiagnosisController::class, 'index'])->name("index");
    Route::get("/create", [DiagnosisController::class, 'create'])->name("create");
    Route::post("/store", [DiagnosisController::class, 'store'])->name("store");
    Route::get("/print", [DiagnosisController::class, 'print'])->name("print");
    Route::get("//edit/{id}", [DiagnosisController::class, 'edit'])->name("edit.view");
    Route::post("/edit/{id}", [DiagnosisController::class, 'editAction'])->name("edit.action");
    Route::delete("/destroy/{id}", [DiagnosisController::class, 'destroy'])->name("destroy");
});


Route::prefix("treatment")->name("treatment.")->group(function () {
    Route::get("/", [TreatmentController::class, 'index'])->name("index");
    Route::get("/create", [TreatmentController::class, 'create'])->name("create");
    Route::post("/store", [TreatmentController::class, 'store'])->name("store");
    Route::get("/print", [TreatmentController::class, 'print'])->name("print");
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
    Route::delete("/destroy/{id}", [UserController::class, 'destroy'])->name("destroy");
});

