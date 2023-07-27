<?php

use App\Http\Models\Token;
use App\User;

Artisan::command('logs:clear', function () {
	array_map('unlink', array_filter((array) glob(storage_path('logs/*.log'))));
	$this->comment('Old logs cleared!');
})->describe('Clear log files');

Artisan::command('activate-user-tokens:clear', function () {
	DB::beginTransaction();
	try {
		$tokens = Token::whereNotNull("due_date")->where("due_date", "<", now())->where("type", "user_activation_token")->where("entity_type", User::class);
		User::whereIn("id", $tokens->get()->pluck("entity_id")->toArray())->get()->each(function ($user) {
			$user->delete();
		});
		$tokens->delete();
		DB::commit();
		$this->comment('All expired tokens cleared!');
	} catch (\Throwable $th) {
		DB::rollback();
		$this->comment('Error clearing expired tokens!');
		throw $th;
	}
})->describe('Clear expired tokens');
