<?php

namespace App;

use App\Http\Models\AccessGroup;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use marcusvbda\vstack\Models\Traits\hasCode;
use App\Http\Models\Tenant;
use App\Http\Models\Token;
use App\Mail\DefaultEmail;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
	use SoftDeletes, Notifiable, hasCode;

	public const PASS_HEGEX_VALIDATOR = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/';
	public const PASS_VALIDATOR_MESSAGE = 'A senha deve ter ao menos 1 caracter especial, 1 numeral e no minimo 6 caracteres';

	public $guarded = ['created_at'];
	protected $dates = ['deleted_at'];
	protected $appends = ['code'];
	public  $casts = [
		"data" => "json",
	];
	public $relations = [];


	public static function boot()
	{
		parent::boot();
		static::deleted(function ($item) {
			if ($item->role == "admin") {
				$item->tenant->purge();
			}
		});
	}

	public function setPasswordAttribute($val)
	{
		$this->attributes["password"] = bcrypt($val);
	}

	public function tenant()
	{
		return $this->BelongsTo(Tenant::class);
	}

	public function sendConfirmationEmail()
	{
		$token = md5(uniqid());
		$dueDate = now()->addHours(24);
		$this->activationToken()->create([
			"type" => "user_activation_token",
			"value" => $token,
			"due_date" => $dueDate
		]);
		$this->save();
		Mail::to($this->email)->send(new DefaultEmail([
			'subject' => "Ativação de conta",
			'view' => "emails.user_activation",
			'with' => [
				'firstName' => $this->firstName,
				'activationLink' => $this->activationLink
			]
		]));
	}

	public function accessGroups()
	{
		return $this->belongsToMany(AccessGroup::class, "access_group_users", "user_id", "access_group_id");
	}

	public function renewToken()
	{
		return $this->morphOne(Token::class, 'entity')->where("type", "user_forgot_password_token");
	}

	public function activationToken()
	{
		return $this->morphOne(Token::class, 'entity')->where("type", "user_activation_token");
	}

	public function hasPermissionTo($permissionKey)
	{
		if ($this->role === "admin") return true;
		return AccessGroup::join("access_group_users", "access_group_users.access_group_id", "=", "access_groups.id")
			->join("access_group_permissions", "access_group_permissions.access_group_id", "=", "access_groups.id")
			->join("permissions", "permissions.id", "=", "access_group_permissions.permission_id")
			->where("access_group_users.user_id", $this->id)
			->where("permissions.key", $permissionKey)
			->count() > 0;
	}

	public function getFirstNameAttribute()
	{
		return explode(" ", $this->name)[0];
	}

	public function getActivationLinkAttribute()
	{
		return route("user.activation", ["token" => @$this->activationToken->value]);
	}

	public function getRenewLinkAttribute()
	{
		return route("user.renew_password", ["token" => @$this->renewToken->value]);
	}

	public function sendForgotPasswordEmail()
	{
		$this->renewToken()->delete();
		$token = md5(uniqid());
		$dueDate = now()->addHours(24);
		$this->activationToken()->create([
			"type" => "user_forgot_password_token",
			"value" => $token,
			"due_date" => $dueDate
		]);
		$this->save();
		Mail::to($this->email)->send(new DefaultEmail([
			'subject' => "Renovação de senha",
			'view' => "emails.user_renew",
			'with' => [
				'firstName' => $this->firstName,
				'renewLink' => $this->renewLink
			]
		]));
	}
}
