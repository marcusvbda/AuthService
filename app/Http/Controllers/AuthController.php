<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\Tenant;
use App\Http\Models\Token;
use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Support\Facades\DB;
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
		try {
			DB::beginTransaction();
			Auth::logout();
			$this->validate($request, [
				'name'     => 'required',
				'email'    => 'required|email|unique:users',
				'password' => ['required', function ($att, $val, $fail) {
					if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/', $val)) {
						$fail('A senha deve ter ao menos 1 caracter especial, 1 numeral e no minimo 6 caracteres');
					}
				}],
				'plan'     => 'required',
				'confirm_password' => 'required|same:password'
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
			DB::commit();
			Messages::send("success", "Usuário cadastrado com sucesso, verifique seu email para confirmar seu acesso.");
			return ["success" => true, "route" => '/login'];
		} catch (\Exception $e) {
			Messages::send("error", "Erro ao cadastrar usuário, tente novamente mais tarde.");
			DB::rollback();
			return ["success" => false, "message" => $e->getMessage()];
		}
	}

	public function userActivation($token)
	{
		$token = Token::where("value", $token)
			->where("type", "user_activation_token")
			->where("entity_type", User::class)
			->firstOrFail();
		if (!$token->isValid()) return abort(404);
		$user = $token->entity;
		$user->email_verified_at = now();
		$user->save();
		$token->delete();
		Auth::login($user);
		return redirect("/admin");
	}
}
