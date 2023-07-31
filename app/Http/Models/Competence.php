<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class Competence extends DefaultModel
{
	use hasCode;
	protected $table = "competences";
	public $guarded = ["created_at"];
	public $casts = [
		"skills" => "array"
	];
}
