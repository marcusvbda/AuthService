<?php

namespace App\Http\Models;

use App\Enums\DemandStatus;
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
	];

	public static function boot()
	{
		parent::boot();
		self::creating(function ($model) {
			$model->status = DemandStatus::opened->name;
		});
	}

	public static function isAuditable()
	{
		return true;
	}

	public function setBudgetAttribute($val)
	{
		return $this->attributes["budget"] = intval($val * 100);
	}

	public function getBudgetAttribute($val)
	{
		return $val / 100;
	}

	public function skills()
	{
		return $this->belongsToMany(Skill::class, "demand_skills", "demand_id", "skill_id");
	}

	public function getSkillIdsAttribute()
	{
		return $this->skills()->pluck("id");
	}

	public function syncSkills($skill_ids)
	{
		$skills = [];
		foreach ($skill_ids as $skill_id) {
			$skills[$skill_id] = ["skill_id" => $skill_id];
		}
		$this->skills()->sync($skills);
	}

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function project()
	{
		return $this->belongsTo(Project::class);
	}

	public function getFStatusAttribute()
	{
		return DemandStatus::badge($this->status);
	}
}
