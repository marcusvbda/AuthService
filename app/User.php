<?php

namespace App;

use App\Http\Models\AccessGroup;
use App\Http\Models\Permission;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use marcusvbda\vstack\Models\Traits\hasCode;
use App\Http\Models\Tenant;

class User extends Authenticatable
{
	use SoftDeletes, Notifiable, hasCode;

	public $guarded = ['created_at'];
	protected $dates = ['deleted_at'];
	protected $appends = ['code'];
	public  $casts = [
		"data" => "json",
	];
	public $relations = [];

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
		// temporarário
		$this->email_verified_at = now();
		$this->save();
	}

	public function accessGroups()
	{
		return $this->belongsToMany(AccessGroup::class, "access_group_users", "user_id", "access_group_id");
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
}
