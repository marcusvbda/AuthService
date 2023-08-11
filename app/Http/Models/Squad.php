<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class Squad extends DefaultModel
{
	use hasCode;
	protected $table = "squads";
	public $guarded = ["created_at"];
}
