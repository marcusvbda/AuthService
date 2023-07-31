<?php

use App\Http\Controllers\HomeController;

Route::get('', [HomeController::class, 'index']);
Route::get('get-data/{action}', [HomeController::class, 'getData']);
