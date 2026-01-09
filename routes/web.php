<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;

Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [LoginController::class, 'index']);
    Route::post('/auth/login', [LoginController::class, 'create'])->name("login.post");
});

Route::get("/patient", [PatientController::class, 'index']);

Route::get('/', function () {
    return view('dashboard.home');
});
