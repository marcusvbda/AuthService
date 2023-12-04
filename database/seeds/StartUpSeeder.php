<?php

namespace Database\Seeders;

use App\Http\Models\Permission;
use App\Http\Models\Tenant;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StartUpSeeder extends Seeder
{
	public Tenant $tenant;

	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createPermissions();
		$this->createTenant();
		$this->createUsers();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	protected function createPermissions()
	{
		// Permission::create(["name" => "Visualizar permissões"], ["key" => "viewlist-permissions"]);
	}

	protected function getPermissionIds($keys)
	{
		return Permission::whereIn("key", $keys)->pluck("id")->toArray();
	}

	public function createTenant()
	{
		$this->tenant = Tenant::create([
			'name' => 'Tenant example',
			'data' => []
		]);
	}

	protected function createUsers()
	{
		User::insert([
			"name" => "Vinicius Bassalobre",
			"email" => "marcusvbda@github.socialite",
			"tenant_id" =>  $this->tenant->id,
			"role" =>  "root",
			"provider" =>  "github",
			"provider_id" =>  "14343030",
			"password" => uniqid(),
		]);
	}

	public function removeTags($string)
	{
		return trim(strip_tags($string));
	}
}
