<?php

namespace App\Http\Models;

use App\Enums\TransactionStatus;
use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class Transaction extends DefaultModel
{
	use hasCode;
	protected $table = "transactions";
	public $guarded = ["created_at"];
	public $casts = [
		"due_date" => "date",
	];

	public function defaultListOrder()
	{
		return ["installment_id", "asc"];
	}

	public static function boot()
	{
		parent::boot();
		self::creating(function ($model) {
			$model->status = TransactionStatus::pending->name;
		});

		self::deleting(function ($model) {
			$model->refs()->delete();
		});
	}

	public static function isAuditable()
	{
		return true;
	}

	public function getFDueDateAttribute()
	{
		return @$this->due_date ? $this->due_date->format("d/m/Y") : '';
	}

	public function refs()
	{
		return $this->hasMany(Transaction::class, "ref", "ref")->where("id", "!=", $this->id);
	}

	public function setTotalAmountAttribute($val)
	{
		$this->attributes["total_amount"] = intval($val * 100);
	}

	public function setInstallmentAmountAttribute($val)
	{
		$this->attributes["installment_amount"] = intval($val * 100);
	}

	public function getTotalAmountAttribute($val)
	{
		return $val / 100;
	}

	public function getInstallmentAmountAttribute($val)
	{
		return $val / 100;
	}

	public function getFInstallmentAmountAttribute()
	{
		$amount =  $this->installment_amount;
		return "R$ " . number_format($amount, 2, ",", ".");
	}

	public function getFTotalAmountAttribute()
	{
		$amount =  $this->total_amount;
		return "R$ " . number_format($amount, 2, ",", ".");
	}

	public function getFStatusAttribute()
	{
		return TransactionStatus::badge($this->status);
	}
}
