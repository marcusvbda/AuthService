<?php

use App\Http\Controllers\AuthController;

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'signin'])->name('signin');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'submitRegister'])->name('submit.register');
Route::get('user-activation/{token}', [AuthController::class, 'userActivation'])->name('user.activation');
