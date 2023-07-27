<?php

use App\Http\Controllers\HomeController;

Route::get('', [HomeController::class, 'index']);
Route::get('dashboard', [HomeController::class, 'dashboard']);
