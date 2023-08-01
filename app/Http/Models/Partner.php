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

	public function getFPriceHourAttribute()
	{
		return "R$ " . number_format($this->price_hour, 2, ",", ".");
	}

	public function getFSkillCompetenceAttribute()
	{
		$competences = $this->skills->map(function ($x) {
			return $x->competence->name;
		})->toArray();

		return implode(", ", array_unique($competences));
	}

	public function getPriceHourAttribute($val)
	{
		return floatval($val / 100);
	}

	public function getContractDueDateAttribute($val)
	{
		return $val ? date("Y-m-d", strtotime($val)) : null;
	}

	public function skills()
	{
		return $this->belongsToMany(Skill::class, "partner_skills", "partner_id", "skill_id");
	}

	public function getSkillIdsAttribute()
	{
		return $this->skills->pluck("id");
	}

	public function syncSkills($skill_ids)
	{
		$skills = [];
		foreach ($skill_ids as $skill_id) {
			$skills[$skill_id] = ["skill_id" => $skill_id];
		}
		$this->skills()->sync($skills);
	}
}
