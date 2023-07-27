<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Http\Models\Tenant;
use Illuminate\Support\Facades\DB;

class StartUpSeeder extends Seeder
{
	private $tenant = null;
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createTenant();
		$this->createUsers();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	private function createTenant()
	{
		DB::table("tenants")->truncate();
		$this->tenant = Tenant::create([
			"name" => "Tenant Default",
		]);
	}

	private function createUsers()
	{
		DB::table("users")->truncate();
		$user = new User();
		$user->name = "root";
		$user->email = "root@root.com";
		$user->password = "roottoor";
		$user->tenant_id = $this->tenant->id;
		$user->email_verified_at = now();
		$user->role = "user";
		$user->save();
	}
}
