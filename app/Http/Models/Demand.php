<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class Demand extends DefaultModel
{
	use hasCode;
	protected $table = "demands";
	public $guarded = ["created_at"];
	public $casts = [
		"start_date" => "date",
		"end_date" => "date",
		"skills" => "array",
	];

	public function setBudgetAttribute($val)
	{
		return $this->attributes["budget"] = intval($val * 100);
	}

	public function getBudgetAttribute($val)
	{
		return $val / 100;
	}
}
