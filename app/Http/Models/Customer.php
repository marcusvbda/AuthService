<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class Customer extends DefaultModel
{
	use hasCode;
	protected $table = "customers";
	public $guarded = ["created_at"];
}
