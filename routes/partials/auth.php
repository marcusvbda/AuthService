<?php

use App\Http\Controllers\AuthController;

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'signin'])->name('signin');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'submitRegister'])->name('submit.register');
Route::get('user-activation/{token}', [AuthController::class, 'userActivation'])->name('user.activation');
Route::get('forgot-my-password', [AuthController::class, 'forgotMyPassword'])->name('forgot.my.password');
Route::post('forgot-my-password', [AuthController::class, 'submitForgotMyPassword'])->name('submit.forgot.my.password');
Route::get('renew-password/{token}', [AuthController::class, 'renewPassword'])->name('user.renew_password');
Route::post('renew-password/{token}', [AuthController::class, 'submitRenewPassword'])->name('submit.renew_password');
Route::get('socialite/login/{provider}', [AuthController::class, 'socialiteLogin'])->name('socialite.login');
Route::get('socialite/callback/{provider}', [AuthController::class, 'socialiteCallback'])->name('socialite.callback');

Route::group(['middleware' => ['auth']], function () {
    Route::get('choose-a-plan', [AuthController::class, 'choosePlan'])->name('user.choose_plan');
    Route::post('choose-a-plan', [AuthController::class, 'submitChoosePlan'])->name('user.choose_plan');
});
