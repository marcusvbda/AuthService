<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use marcusvbda\vstack\Models\Traits\hasCode;
use Auth;
use App\Http\Models\Tenant;

class User extends Authenticatable
{
	use SoftDeletes, Notifiable, hasCode;

	public $guarded = ['created_at'];
	protected $dates = ['deleted_at'];
	protected $appends = ['code'];
	protected $hashPassword = false;
	public  $casts = [
		"data" => "json",
	];
	public $relations = [];

	public function __construct($hashPassword = true)
	{
		parent::boot();
		$this->hashPassword = $hashPassword;
	}

	public function setPasswordAttribute($val)
	{
		$this->attributes["password"] = bcrypt($val);
	}

	public function tenant()
	{
		return $this->BelongsTo(Tenant::class);
	}
}
