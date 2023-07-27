<?php

use App\Http\Controllers\AuthController;

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'signin'])->name('signin');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'submitRegister'])->name('submit.register');
// Route::get('esqueci-a-senha', [ForgotPasswordController::class, 'index']);
// Route::post('esqueci-a-senha', [ForgotPasswordController::class, 'resetPassword']);
// Route::get('esqueci-a-senha/{token}', [ForgotPasswordController::class, 'renewPassword']);
// Route::post('esqueci-a-senha/{token}', [ForgotPasswordController::class, 'setPassword']);