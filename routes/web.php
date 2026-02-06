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
    Route::post('/auth/login', [LoginController::class, 'create'])->middleware('throttle:5,1')->name("login.post");
});

Route::middleware("auth")->group(function () {
    Route::post("/auth/logout", [LoginController::class, 'logout'])->name("auth.logout");

    Route::prefix("user")->name("user.")->group(function () {
        Route::get("/profile", [UserController::class, 'profile'])->name("profile.view");
        Route::post("/profile", [UserController::class, 'profileAction'])->name("profile.action");
    });

    Route::prefix("patient")->name("patient.")->group(function () {
        Route::get("/", [PatientController::class, 'index'])
            ->middleware("can:get-all-patient")
            ->name("index");

        Route::get("/create", [PatientController::class, 'create'])
            ->middleware("can:create-patient")
            ->name("create");

        Route::get("/queue", [PatientController::class, 'queue'])
            ->middleware('permission:get-all-appointment|register-patient')
            ->name("queue");

        Route::middleware("can:get-history-appointment")->group(function () {
            Route::get("/{id}/history", [PatientController::class, 'userHistory'])->name("user.history");
            Route::get("/{id}/history/print", [PatientController::class, 'userHistoryPrint'])->name("user.history.print");
            Route::get("/history", [PatientController::class, 'history'])->name("history");
            Route::get("/history/print", [HistoryController::class, 'printAll'])->name("all-history.print");
            Route::get("/history/{id}", [HistoryController::class, 'show'])->name("history.show");
            Route::get("/history/print/{id}", [HistoryController::class, 'print'])->name("history.print");
        });

        Route::get("/print", [PatientController::class, 'print'])
            ->middleware("can:print-patient")
            ->name("print");

        Route::post("/store", [PatientController::class, 'store'])
            ->middleware("can:create-patient")
            ->name("store");

        Route::get("/edit/{id}", [PatientController::class, 'edit'])
            ->middleware("can:edit-patient")
            ->name("edit.view");

        Route::post("/edit/{id}", [PatientController::class, 'editAction'])
            ->middleware("can:edit-patient")
            ->name("edit.action");

        Route::delete("/destroy/{id}", [PatientController::class, 'destroy'])
            ->middleware("can:delete-patient")
            ->name("destroy");

        Route::get("/appointment/{id}", [AppointmentController::class, 'create'])
            ->middleware("can:create-appointment")
            ->name("appointment.view");

        Route::post("/appointment/{id}", [AppointmentController::class, 'createAction'])
            ->middleware("can:create-appointment")
            ->name("appointment.action");

        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
        Route::get('/report/print', [ReportController::class, 'print'])->name('report.print');
        Route::post("/report", [ReportController::class, 'create'])->name("report.create");
    });

    Route::prefix("registration")->middleware("can:register-patient")->name("registration.")->group(function () {
        Route::get("/create/{patientId}", [RegistrationController::class, 'create'])->name("create");
        Route::post("/create/{patientId}", [RegistrationController::class, 'store'])->name("store");
        Route::get("/{id}/print", [HistoryController::class, 'show'])->name("print");
        Route::post("/{id}/cancel", [AppointmentController::class, 'cancel'])->name("cancel");
    });

    Route::prefix("medicine")->name("medicine.")->group(function () {
        Route::get("/", [MedicineController::class, 'index'])
            ->middleware("can:get-all-medicine")
            ->name("index");

        Route::get("/print", [MedicineController::class, 'print'])
            ->middleware("can:print-medicine")
            ->name("print");

        Route::get("/create", [MedicineController::class, 'create'])
            ->middleware("can:create-medicine")
            ->name("create");

        Route::post("/store", [MedicineController::class, 'store'])
            ->middleware("can:create-medicine")
            ->name("store");
        Route::get("/edit/{id}", [MedicineController::class, 'edit'])
            ->middleware("can:edit-medicine")
            ->name("edit.view");

        Route::post("/edit/{id}", [MedicineController::class, 'editAction'])
            ->middleware("can:edit-medicine")
            ->name("edit.action");

        Route::delete("/destroy/{id}", [MedicineController::class, 'destroy'])
            ->middleware("can:delete-medicine")
            ->name("destroy");

        Route::get("/transaction", [MedicineController::class, 'getAllTransaction'])
            ->middleware("can:get-all-transaction-medicine")
            ->name("transaction.index");

        Route::get("/transaction/print", [MedicineController::class, 'printAllTransaction'])
            ->middleware("can:print-transaction-medicine")
            ->name("transaction.print");
        
        Route::get('/transaction/create', [MedicineController::class, 'createTransaction'])
            ->middleware("can:create-transaction-medicine")
            ->name("transaction.create.view");

        Route::post('/transaction/create', [MedicineController::class, 'storeTransaction'])
            ->middleware("can:create-transaction-medicine")
            ->name("transaction.store.action");

        Route::get('/transaction/report', [MedicineController::class, 'createTransactionReport'])
            ->middleware("can:get-report-transaction-medicine")
            ->name("transaction.report");

        Route::get('/transaction/report/print', [MedicineController::class, 'printTransactionReport'])
            ->middleware("can:get-report-transaction-medicine")
            ->name("transaction.report.print");
    });

    Route::prefix("diagnosis")->name("diagnosis.")->group(function () {
        Route::get("/", [DiagnosisController::class, 'index'])
            ->middleware("can:get-all-diagnosis")
            ->name("index");

        Route::get("/create", [DiagnosisController::class, 'create'])
            ->middleware("can:create-diagnosis")
            ->name("create");

        Route::post("/store", [DiagnosisController::class, 'store'])
            ->middleware("can:create-diagnosis")
            ->name("store");

        Route::get("/print", [DiagnosisController::class, 'print'])
            ->middleware("can:print-diagnosis")
            ->name("print");

        Route::get("/edit/{id}", [DiagnosisController::class, 'edit'])
            ->middleware("can:edit-diagnosis")
            ->name("edit.view");

        Route::post("/edit/{id}", [DiagnosisController::class, 'editAction'])
            ->middleware("can:edit-diagnosis")
            ->name("edit.action");

        Route::delete("/destroy/{id}", [DiagnosisController::class, 'destroy'])
            ->middleware("can:delete-diagnosis")
            ->name("destroy");
    });


    Route::prefix("treatment")->name("treatment.")->group(function () {
        Route::get("/", [TreatmentController::class, 'index'])
            ->middleware("can:get-all-treatment")
            ->name("index");

        Route::get("/create", [TreatmentController::class, 'create'])
            ->middleware("can:create-treatment")
            ->name("create");

        Route::post("/store", [TreatmentController::class, 'store'])
            ->middleware("can:create-treatment")
            ->name("store");

        Route::get("/print", [TreatmentController::class, 'print'])
            ->middleware("can:print-treatment")
            ->name("print");

        Route::get("//edit/{id}", [TreatmentController::class, 'edit'])
            ->middleware("can:edit-treatment")
            ->name("edit.view");

        Route::post("/edit/{id}", [TreatmentController::class, 'editAction'])
            ->middleware("can:edit-treatment")
            ->name("edit.action");

        Route::delete("/destroy/{id}", [TreatmentController::class, 'destroy'])
            ->middleware("can:delete-treatment")
            ->name("destroy");
    });

    Route::prefix("user")->middleware("role:admin")->name("user.")->group(function () {
        Route::get("/", [UserController::class, 'index'])->name("index");
        Route::get("/create", [UserController::class, 'create'])->name("create");
        Route::post("/store", [UserController::class, 'store'])->name("store");
        Route::patch("/activate/{id}", [UserController::class, 'activate'])->name("activate");
        Route::get("/edit/{id}", [UserController::class, 'edit'])->name("edit.view");
        Route::post("/edit/{id}", [UserController::class, 'editAction'])->name("edit.action");
        Route::delete("/destroy/{id}", [UserController::class, 'destroy'])->name("destroy");
    });

    Route::get('/', function () {
        return view('dashboard.home');
    });

    Route::get('/dashboard/api/patients-status', [ReportController::class, 'patientsStatus']);
    Route::get('/dashboard/api/weekly-patients', [ReportController::class, 'weeklyPatientsChart']);


});
