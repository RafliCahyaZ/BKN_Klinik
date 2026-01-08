<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [LoginController::class, 'index']);
    Route::post('/auth/login', [LoginController::class, 'create'])->name("login.post");
});

Route::get('/', function () {
    return view('dashboard.home');
});
