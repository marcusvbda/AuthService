<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class Skill extends DefaultModel
{
	use hasCode;
	protected $table = "skills";
	public $guarded = ["created_at"];
	public $appends = ["competence_name"];


	public function competence()
	{
		return $this->belongsTo(Competence::class, "competence_id");
	}

	public function getCompetenceNameAttribute()
	{
		return $this->competence?->name;
	}
}
