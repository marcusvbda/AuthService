<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class AccessGroup extends DefaultModel
{
	use hasCode;
	protected $table = "access_groups";
	public $guarded = ["created_at"];

	public function permissions()
	{
		return $this->belongsToMany(Permission::class, "access_group_permissions", "access_group_id", "permission_id");
	}

	public function getPermissionIdsAttribute()
	{
		return $this->permissions->pluck("id");
	}

	public function getLevelAttribute()
	{
		$totalPermissions = Permission::count();
		$totalPermissionsByGroup = $this->permissions()->count();
		return round(($totalPermissionsByGroup * 100) / $totalPermissions, 2);
	}
}
