<?php

use App\Http\Controllers\Auth\UsersController;

Route::get('', function () {
	return redirect("/admin"); //temporário até termos uma landing page
});

require "partials/auth.php";
Route::group(['middleware' => ['auth']], function () {
	Route::group(['prefix' => "admin"], function () {
		require "partials/home.php";
	});
});
