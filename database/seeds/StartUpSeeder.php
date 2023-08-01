<?php

namespace Database\Seeders;

use App\Http\Models\AccessGroup;
use App\Http\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StartUpSeeder extends Seeder
{
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		static::createPermissions(true);
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	public static function createPermissions($truncate = false)
	{
		if ($truncate) {
			Permission::truncate();
			AccessGroup::truncate();
			DB::table("access_group_permissions")->truncate();
		}
		Permission::updateOrCreate(["name" => "Visualizar permissões"], ["key" => "viewlist-permissions"]);

		Permission::updateOrCreate(["name" => "Visualizar grupos de acesso"], ["key" => "viewlist-access-groups"]);
		Permission::updateOrCreate(["name" => "Cadastrar grupos de acesso"], ["key" => "create-access-groups"]);
		Permission::updateOrCreate(["name" => "Editar grupos de acesso"], ["key" => "edit-access-groups"]);
		Permission::updateOrCreate(["name" => "Excluir grupos de acesso"], ["key" => "delete-access-groups"]);

		Permission::updateOrCreate(["name" => "Visualizar usuários"], ["key" => "viewlist-users"]);
		Permission::updateOrCreate(["name" => "Cadastrar usuários"], ["key" => "create-users"]);
		Permission::updateOrCreate(["name" => "Editar usuários"], ["key" => "edit-users"]);
		Permission::updateOrCreate(["name" => "Excluir usuários"], ["key" => "delete-users"]);
		Permission::updateOrCreate(["name" => "Resetar senhas de usuários"], ["key" => "reset-credentials"]);

		Permission::updateOrCreate(["name" => "Visualizar competências"], ["key" => "viewlist-competences"]);
		Permission::updateOrCreate(["name" => "Cadastrar competências"], ["key" => "create-competences"]);
		Permission::updateOrCreate(["name" => "Editar competências"], ["key" => "edit-competences"]);
		Permission::updateOrCreate(["name" => "Excluir competências"], ["key" => "delete-competences"]);

		Permission::updateOrCreate(["name" => "Visualizar clientes"], ["key" => "viewlist-customers"]);
		Permission::updateOrCreate(["name" => "Cadastrar clientes"], ["key" => "create-customers"]);
		Permission::updateOrCreate(["name" => "Editar clientes"], ["key" => "edit-customers"]);
		Permission::updateOrCreate(["name" => "Excluir clientes"], ["key" => "delete-customers"]);

		Permission::updateOrCreate(["name" => "Visualizar projetos"], ["key" => "viewlist-projects"]);
		Permission::updateOrCreate(["name" => "Cadastrar projetos"], ["key" => "create-projects"]);
		Permission::updateOrCreate(["name" => "Editar projetos"], ["key" => "edit-projects"]);
		Permission::updateOrCreate(["name" => "Excluir projetos"], ["key" => "delete-projects"]);

		Permission::updateOrCreate(["name" => "Visualizar parceiros"], ["key" => "viewlist-partners"]);
		Permission::updateOrCreate(["name" => "Cadastrar parceiros"], ["key" => "create-partners"]);
		Permission::updateOrCreate(["name" => "Editar parceiros"], ["key" => "edit-partners"]);
		Permission::updateOrCreate(["name" => "Excluir parceiros"], ["key" => "delete-partners"]);

		Permission::updateOrCreate(["name" => "Visualizar demandas"], ["key" => "viewlist-demands"]);
		Permission::updateOrCreate(["name" => "Cadastrar demandas"], ["key" => "create-demands"]);
		Permission::updateOrCreate(["name" => "Editar demandas"], ["key" => "edit-demands"]);
		Permission::updateOrCreate(["name" => "Excluir demandas"], ["key" => "delete-demands"]);
	}
}
