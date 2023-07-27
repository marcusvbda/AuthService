<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use marcusvbda\vstack\Models\Traits\hasCode;

class Permission extends Model
{
	use hasCode;
	protected $table = "permissions";
	public $timestamps = false;
	public $guarded = ["created_at"];
}
