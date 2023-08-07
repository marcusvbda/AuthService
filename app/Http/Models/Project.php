<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class Project extends DefaultModel
{
	use hasCode;
	protected $table = "projects";
	public $guarded = ["created_at"];
	public $casts = [
		"start_date" => "date",
		"end_date" => "date",
	];

	public static function isAuditable()
	{
		return true;
	}

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}
}
