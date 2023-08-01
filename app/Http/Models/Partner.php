<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class Partner extends DefaultModel
{
	use hasCode;
	protected $table = "partners";
	public $guarded = ["created_at"];
	public $casts = [
		"contract_due_date" => "date",
	];

	public function setPriceHourAttribute($val)
	{
		$this->attributes["price_hour"] = intval($val * 100);
	}

	public function getPriceHourAttribute($val)
	{
		return floatval($val / 100);
	}

	public function syncSkills($skill_ids)
	{
		dd($skill_ids);
	}
}
