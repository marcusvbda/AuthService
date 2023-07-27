<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use marcusvbda\vstack\Models\Traits\hasCode;

class Token extends Model
{
	use hasCode;
	protected $table = "tokens";
	public $guarded = ["created_at"];

	public function isValid()
	{
		return $this->due_date === null || ($this->due_date >= now());
	}

	public function entity()
	{
		return $this->morphTo();
	}
}
