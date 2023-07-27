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
use Laravel\Socialite\Facades\Socialite;

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
		return ["success" => false, "message" => "Credenciais inválidas !"];
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
					if (!preg_match(User::PASS_HEGEX_VALIDATOR, $val)) {
						$fail(User::PASS_VALIDATOR_MESSAGE);
					}
				}],
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

	public function forgotMyPassword()
	{
		return view("auth.forgot_my_password");
	}

	public function submitForgotMyPassword(Request $request)
	{
		try {
			DB::beginTransaction();
			$this->validate($request, [
				'email' => 'required|email'
			]);
			$user = User::where("email", $request->email)->first();
			if (!$user) return ["success" => false, "message" => "Email não encontrado !"];
			$user->sendForgotPasswordEmail();
			DB::commit();
			Messages::send("success", "Email de renovação de senha enviado com sucesso !");
			return ["success" => true, "message" => "Email enviado com sucesso", "route" => "/login"];
		} catch (\Exception $e) {
			DB::rollback();
			return ["success" => false, "message" => $e->getMessage()];
		}
	}

	public function renewPassword($token)
	{
		$token = Token::where("value", $token)
			->where("type", "user_forgot_password_token")
			->where("entity_type", User::class)
			->firstOrFail();
		if (!$token->isValid()) return abort(404);
		$user = $token->entity;
		$tokenValue = $token->value;
		return view("auth.renew_password", compact("user", "tokenValue"));
	}

	public function submitRenewPassword($token, Request $request)
	{
		try {
			DB::beginTransaction();
			Auth::logout();
			$this->validate($request, [
				'password' => ['required', function ($att, $val, $fail) {
					if (!preg_match(User::PASS_HEGEX_VALIDATOR, $val)) {
						$fail(User::PASS_VALIDATOR_MESSAGE);
					}
				}],
				'confirm_password' => 'required|same:password'
			]);

			$token = Token::where("value", $token)
				->where("type", "user_forgot_password_token")
				->where("entity_type", User::class)
				->firstOrFail();
			if (!$token->isValid()) return abort(404);

			$user = $token->entity;
			$user->password = $request->password;
			$user->save();
			Auth::login($user);
			$token->delete();
			DB::commit();
			Messages::send("success", "Senha alterada com sucesso !");
			return ["success" => true, "route" => '/admin'];
		} catch (\Exception $e) {
			DB::rollback();
			return ["success" => false, "message" => $e->getMessage()];
		}
	}

	public function socialiteLogin($provider)
	{
		$providers = config("auth.enabled_providers", []);
		if (!in_array($provider, $providers)) return abort(404);
		return Socialite::driver($provider)->redirect();
	}

	public function socialiteCallback($provider)
	{
		$providers = config("auth.enabled_providers", []);
		if (!in_array($provider, $providers)) return abort(404);
		$providerUser = Socialite::driver($provider)->user();

		return $this->{$provider . "Callback"}($providerUser, $provider);
	}

	protected function githubCallback($providerUser, $provider)
	{
		$user = User::firstOrNew(
			["provider" => $provider],
			["provider_id" => $providerUser->id],
		);

		if (!$user->id) {
			$tenant = Tenant::create([
				"name" => "Tenant {$providerUser->nickname}",
			]);

			$user->name = $providerUser->name;
			$user->email = $providerUser->nickname . "@{$provider}.socialite";
			$user->password = md5(uniqid());
			$user->tenant_id = $tenant->id;
			$user->role = "admin";
			$user->email_verified_at = now();
			$user->save();
		}

		Auth::login($user);
		return redirect("/admin");
	}

	public function choosePlan()
	{
		return view("auth.choose_plan");
	}

	public function submitChoosePlan(Request $request)
	{
		$user = Auth::user();
		$user->plan = $request->plan;
		$user->save();

		return ["success" => true, "route" => "/admin"];
	}
}
