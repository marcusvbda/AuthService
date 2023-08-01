<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class Competence extends DefaultModel
{
	use hasCode;
	protected $table = "competences";
	public $guarded = ["created_at"];
	public $appends = [
		"skill_names_str"
	];

	public function getSkillIdsAttribute()
	{
		return $this->skills()->pluck("id")->toArray();
	}

	public function getSkillNamesStrAttribute()
	{
		return implode(", ", $this->skills()->pluck("name")->toArray());
	}

	public function skills()
	{
		return $this->hasMany(Skill::class, "competence_id");
	}

	public function syncSkills($skill_ids)
	{
		$to_create = array_filter($skill_ids, fn ($item) =>  !is_numeric($item));
		$to_sync = array_filter($skill_ids, fn ($item) =>  is_numeric($item));
		$to_delete = $this->skills()->whereNotIn("id", $to_sync)->pluck("id")->toArray();
		$this->skills()->whereIn("id", $to_delete)->delete();
		foreach ($to_create as $skill_name) {
			$skill = Skill::create(["name" => $skill_name, "competence_id" => $this->id]);
			$to_sync[] = $skill->id;
		}

		$skills = Skill::whereIn("id", $to_sync)->get();
		$this->skills()->saveMany($skills);
	}
}
