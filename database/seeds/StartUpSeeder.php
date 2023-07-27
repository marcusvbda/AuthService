<?php

namespace Database\Seeders;

use App\Http\Models\AccessGroup;
use App\Http\Models\Permission;
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
		static::createPermissions(true);
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
		$user->name = "vinicius";
		$user->email = "bassalobre.vinicius@gmail.com";
		$user->password = "bassalobre";
		$user->tenant_id = $this->tenant->id;
		$user->email_verified_at = now();
		$user->role = "admin";
		$user->save();
	}

	public static function createPermissions($truncate = false)
	{
		if ($truncate) {
			Permission::truncate();
			AccessGroup::truncate();
			DB::table("access_group_permissions")->truncate();
		}
		Permission::updateOrCreate(["name" => "Visualizar permissões"], ["key" => "viewlist-permissions"]);

		Permission::updateOrCreate(["name" => "Cadastrar grupos de acesso"], ["key" => "create-access-groups"]);
		Permission::updateOrCreate(["name" => "Editar grupos de acesso"], ["key" => "edit-access-groups"]);
		Permission::updateOrCreate(["name" => "Excluir grupos de acesso"], ["key" => "delete-access-groups"]);

		Permission::updateOrCreate(["name" => "Cadastrar usuários"], ["key" => "create-users"]);
		Permission::updateOrCreate(["name" => "Editar usuários"], ["key" => "edit-users"]);
		Permission::updateOrCreate(["name" => "Excluir usuários"], ["key" => "delete-users"]);
		Permission::updateOrCreate(["name" => "Resetar senhas de usuários"], ["key" => "reset-credentials"]);
	}
}
