<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\Tenant;
use Illuminate\Http\Request;
use Auth;
use App\User;
use marcusvbda\vstack\Services\Messages;

class AuthController extends Controller
{
	public function index()
	{
		Auth::logout();
		return view("auth.login");
	}

	public function register()
	{
		Auth::logout();
		return view("auth.register");
	}

	public function signin(Request $request)
	{
		Auth::logout();
		$this->validate($request, [
			'email'    => 'required|email',
			'password' => 'required'
		]);
		$credentials = $request->only('email', 'password');
		if (User::where("email", $credentials["email"])->where("email_verified_at", "!=", null)->count() > 0) {
			if (Auth::attempt($credentials, (@$request['remember'] ? true : false))) {
				return ["success" => true, "route" => '/admin'];
			}
		}
		return ["success" => false, "message" => "Credenciais inválidas"];
	}

	public function submitRegister(Request $request)
	{

		Auth::logout();
		$this->validate($request, [
			'name'     => 'required',
			'email'    => 'required|email|unique:users',
			'password' => 'required',
			'confirm_'
		]);

		$tenant = Tenant::create([
			"name" => "Tenant {$request->name}",
		]);

		$user = new User();
		$user->name = $request->name;
		$user->role = "admin";
		$user->email = $request->email;
		$user->tenant_id = $tenant->id;
		$user->plan = $request->plan;
		$user->password = $request->password;
		$user->save();
		$user->sendConfirmationEmail();
		Messages::send("success", "Usuário cadastrado com sucesso, verifique seu email para confirmar o cadastro.");
		return ["success" => true, "route" => '/login'];
	}
}
