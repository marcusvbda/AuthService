<?php

use App\Http\Controllers\DebugController;

Route::get('', function () {
	return redirect("/admin"); //temporário até termos uma landing page
});

if (config('app.env') === "homologation") {
	Route::get('debug/{method}', [DebugController::class, 'handler']);
}

require "partials/auth.php";
Route::group(['middleware' => ['auth', 'plan-middleware']], function () {
	Route::group(['prefix' => "admin"], function () {
		require "partials/home.php";
	});
});
