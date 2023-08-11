<?php

namespace Database\Seeders;

use App\Enums\TransactionStatus;
use App\Http\Models\AccessGroup;
use App\Http\Models\Competence;
use App\Http\Models\Customer;
use App\Http\Models\Demand;
use App\Http\Models\Partner;
use App\Http\Models\Permission;
use App\Http\Models\Project;
use App\Http\Models\Skill;
use App\Http\Models\Squad;
use App\Http\Models\Tenant;
use App\Http\Models\Transaction;
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
		$this->createCompetences();
		$this->createSkills();
		$this->createAccessGroups();
		$this->createUsers();
		$this->createCustomers();
		$this->createProjects();
		$this->createPartners();
		$this->createSquads();
		$this->createDemands();
		$this->createTransactions();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	protected function createPermissions()
	{
		Permission::create(["name" => "Visualizar permissões"], ["key" => "viewlist-permissions"]);

		Permission::create(["name" => "Visualizar grupos de acesso"], ["key" => "viewlist-access-groups"]);
		Permission::create(["name" => "Cadastrar grupos de acesso"], ["key" => "create-access-groups"]);
		Permission::create(["name" => "Editar grupos de acesso"], ["key" => "edit-access-groups"]);
		Permission::create(["name" => "Excluir grupos de acesso"], ["key" => "delete-access-groups"]);

		Permission::create(["name" => "Visualizar usuários"], ["key" => "viewlist-users"]);
		Permission::create(["name" => "Cadastrar usuários"], ["key" => "create-users"]);
		Permission::create(["name" => "Editar usuários"], ["key" => "edit-users"]);
		Permission::create(["name" => "Excluir usuários"], ["key" => "delete-users"]);
		Permission::create(["name" => "Resetar senhas de usuários"], ["key" => "reset-credentials"]);

		Permission::create(["name" => "Visualizar competências"], ["key" => "viewlist-competences"]);
		Permission::create(["name" => "Cadastrar competências"], ["key" => "create-competences"]);
		Permission::create(["name" => "Editar competências"], ["key" => "edit-competences"]);
		Permission::create(["name" => "Excluir competências"], ["key" => "delete-competences"]);

		Permission::create(["name" => "Visualizar clientes"], ["key" => "viewlist-customers"]);
		Permission::create(["name" => "Cadastrar clientes"], ["key" => "create-customers"]);
		Permission::create(["name" => "Editar clientes"], ["key" => "edit-customers"]);
		Permission::create(["name" => "Excluir clientes"], ["key" => "delete-customers"]);
		Permission::create(["name" => "Ver logs de clientes"], ["key" => "view-audits-customers"]);

		Permission::create(["name" => "Visualizar projetos"], ["key" => "viewlist-projects"]);
		Permission::create(["name" => "Cadastrar projetos"], ["key" => "create-projects"]);
		Permission::create(["name" => "Editar projetos"], ["key" => "edit-projects"]);
		Permission::create(["name" => "Excluir projetos"], ["key" => "delete-projects"]);
		Permission::create(["name" => "Ver logs de projetos"], ["key" => "view-audits-projects"]);

		Permission::create(["name" => "Visualizar parceiros"], ["key" => "viewlist-partners"]);
		Permission::create(["name" => "Cadastrar parceiros"], ["key" => "create-partners"]);
		Permission::create(["name" => "Editar parceiros"], ["key" => "edit-partners"]);
		Permission::create(["name" => "Excluir parceiros"], ["key" => "delete-partners"]);
		Permission::create(["name" => "Ver logs de parceiros"], ["key" => "view-audits-partners"]);

		Permission::create(["name" => "Visualizar últimas demandas"], ["key" => "viewlist-last-demands"]);
		Permission::create(["name" => "Visualizar detalhes da demandas"], ["key" => "view-demands"]);
		Permission::create(["name" => "Visualizar demandas"], ["key" => "viewlist-demands"]);
		Permission::create(["name" => "Cadastrar demandas"], ["key" => "create-demands"]);
		Permission::create(["name" => "Editar demandas"], ["key" => "edit-demands"]);
		Permission::create(["name" => "Excluir demandas"], ["key" => "delete-demands"]);
		Permission::create(["name" => "Ver logs de demandas"], ["key" => "view-audits-demands"]);

		Permission::create(["name" => "Visualizar pagamentos"], ["key" => "viewlist-transactions"]);
		Permission::create(["name" => "Ver relatório de pagamentos"], ["key" => "report-transactions"]);
		Permission::create(["name" => "Cadastrar pagamentos"], ["key" => "create-transactions"]);
		Permission::create(["name" => "Editar pagamentos"], ["key" => "edit-transactions"]);
		Permission::create(["name" => "Excluir pagamentos"], ["key" => "delete-transactions"]);
		Permission::create(["name" => "Ver logs de pagamentos"], ["key" => "view-audits-transactions"]);

		Permission::create(["name" => "Visualizar squads"], ["key" => "viewlist-squads"]);
		Permission::create(["name" => "Cadastrar squads"], ["key" => "create-squads"]);
		Permission::create(["name" => "Editar squads"], ["key" => "edit-squads"]);
		Permission::create(["name" => "Excluir squads"], ["key" => "delete-squads"]);
	}

	protected function createAccessGroups()
	{
		$createPayload = DB::connection("old_mysql")->table("roles")->whereNotIn("id", [1, 8])->get()->map(function ($x) {
			return [
				"name" => $x->name,
				"tenant_id" => $this->tenant->id,
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
				"import_ref" => $x->id,
			];
		})->toArray();
		AccessGroup::insert($createPayload);
	}

	protected function getPermissionIds($keys)
	{
		return Permission::whereIn("key", $keys)->pluck("id")->toArray();
	}

	public function createTenant()
	{
		$this->tenant = Tenant::create([
			'name' => 'Tenant Diwe',
			'data' => []
		]);
	}

	protected function createTransactions()
	{
		$createPayload = [];
		$ref = uniqid();
		$current_demand = null;

		foreach (DB::connection("old_mysql")->table("transactions")->get() as $x) {
			if ($current_demand !== $x->job_id) {
				$current_demand = $x->job_id;
				$total = array_sum(array_map(function ($item) {
					return $item["installment_amount"];
				}, array_filter($createPayload, function ($item) use ($ref) {
					return $item["ref"] == $ref;
				})));

				$createPayload = array_map(function ($item) use ($total, $ref) {
					if ($item["ref"] == $ref) {
						$item["total_amount"] = $total;
					}
					return $item;
				}, $createPayload);
				$ref = uniqid();
				$total = 0;
			}

			$total =  $x->amount * 100;
			$status =  $x->status_id == 1 ? TransactionStatus::approved->name : TransactionStatus::pending->name;
			$installment_id =  str_replace("pagamento", "", str_replace("parcela", "", strtolower($x->observation ?? "1/1")));
			$createPayload[] = [
				"ref" => $ref,
				"description" => "pgto sem descrição",
				"import_ref" => $x->id,
				"tenant_id" => $this->tenant->id,
				"demand_id" =>  $this->getOldIndex("demands", "import_ref", $x->job_id, "id"),
				"due_date" =>  $x->due_date,
				"total_amount" => 0,
				"installment_amount" =>  $x->amount * 100,
				"status" => $status,
				"installment_id" => $installment_id,
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
			];
		}

		Transaction::insert($createPayload);
	}

	protected function createSquads()
	{
		$now = now()->format("Y-m-d H:i:s");
		$createPayload = DB::connection("old_mysql")->table("jobs")->groupBy("squad")->get()->map(function ($x) use ($now) {
			return [
				"name" => $x->name,
				"tenant_id" => $this->tenant->id,
				"created_at" => $now,
				"updated_at" => $now,
			];
		})->toArray();
		Squad::insert($createPayload);
	}

	protected function createDemands()
	{
		$createPayload = DB::connection("old_mysql")->table("jobs")->get()->map(function ($x) {
			return [
				"name" => $x->name,
				"customer_id" =>  $this->getOldIndex("customers", "import_ref", $x->client_id, "id"),
				"project_id" =>  $this->getOldIndex("projects", "import_ref", $x->project_id, "id"),
				"partner_id" =>  @Partner::where("user_id", $this->getOldIndex("users", "import_ref", $x->user_id, "id"))->first()->id,
				"squad_id" =>  @Squad::where("name", $x->squad)->first()->id,
				"start_date" => $x->start_date,
				"end_date" => $x->end_date,
				"tenant_id" => $this->tenant->id,
				"briefing_url" => $x->briefing,
				"obs" => $this->removeTags($x->description),
				"status" => $x->status,
				"budget" => $x->budget,
				"partner_obs" => $this->removeTags($x->note),
				"comunication_rate" => $x->communication,
				"delivery_rate" => $x->quality,
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
				"import_ref" => $x->id,
			];
		})->toArray();
		Demand::insert($createPayload);

		foreach (Demand::groupBy("partner_id")->get() as $demand) {
			if (!$demand->partner_id) return null;
			$demand->skills()->sync($demand->partner->skills->pluck("id"));
		}
	}

	protected function createPartners()
	{
		$now = now()->format("Y-m-d H:i:s");
		$createPayload = DB::connection("old_mysql")->table("partners")->get()->map(function ($x)  use ($now) {
			return [
				"name" => $x->company_name,
				"user_id" =>  $this->getOldIndex("users", "import_ref", $x->user_id, "id"),
				"doc_number" => $x->cnpj,
				"tenant_id" => $this->tenant->id,
				"price_hour" => $x->hour_value * 100,
				"portifolio" => $x->portfolio,
				"contract_due_date" => $x->agreement_due_date,
				"contract_url" => $x->agreement,
				"phone" => $x->phone,
				"obs" => $this->removeTags($x->obs),
				"bank_info" => $this->removeTags($x->bank_details),
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
				"import_ref" => $x->id,
				"partner_since" => $x->date_start ?? $now,
			];
		})->toArray();
		Partner::insert($createPayload);

		$partnerSkillsPayload = DB::connection("old_mysql")->table("skill_user")->get()->map(function ($x) {
			return [
				"import_ref" => $x->id,
				"skill_id" =>  $this->getOldIndex("skills", "import_ref", $x->skill_id, "id"),
				"partner_id" =>  @Partner::where("user_id", $this->getOldIndex("users", "import_ref", $x->user_id, "id"))->first()->id,
			];
		})->filter(function ($x) {
			return $x["partner_id"] > 0;
		})->toArray();

		DB::connection("mysql")->table("partner_skills")->insert($partnerSkillsPayload);
	}

	protected function createProjects()
	{
		$createPayload = DB::connection("old_mysql")->table("projects")->get()->map(function ($x) {
			return [
				"name" => $x->name,
				"customer_id" =>  $this->getOldIndex("customers", "import_ref", $x->client_id, "id"),
				"google_drive_url" => $x->drive_folder,
				"tenant_id" => $this->tenant->id,
				"board" => $x->board,
				"start_date" => $x->start_date,
				"end_date" => $x->end_date,
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
				"import_ref" => $x->id,
			];
		})->toArray();
		Project::insert($createPayload);
	}

	protected function createCustomers()
	{
		$createPayload = DB::connection("old_mysql")->table("clients")->get()->map(function ($x) {
			return [
				"name" => $x->name,
				"tenant_id" => $this->tenant->id,
				"phone" => $x->phone,
				"website" => $x->site_url,
				"guide" => $x->guide_url,
				"responsible_name" => $x->responsible_name,
				"responsible_phone" => $x->responsible_phone,
				"responsible_email" => $x->responsible_email,
				"billing_category" => $x->category,
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
				"import_ref" => $x->id,
			];
		})->toArray();
		Customer::insert($createPayload);
	}

	protected function createUsers()
	{
		$date =  now();
		$pass = bcrypt(uniqid());
		$createPayload = DB::connection("old_mysql")->table("users")->get()->map(function ($x) use ($date, $pass) {
			return [
				"name" => $x->name,
				"tenant_id" => $this->tenant->id,
				"email" => $x->email,
				"email_verified_at" => $date,
				"password" => $pass,
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
				"import_ref" => $x->id,
			];
		})->toArray();
		User::insert($createPayload);
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

	protected function createSkills()
	{
		$createPayload = DB::connection("old_mysql")->table("skills")->get()->map(function ($x) {
			return [
				"name" => $x->name,
				"tenant_id" => $this->tenant->id,
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
				"import_ref" => $x->id,
				"competence_id" => $this->getOldIndex("competences", "import_ref", $x->competence_id, "id")
			];
		})->toArray();
		Skill::insert($createPayload);
	}

	protected function getOldIndex($table, $column, $ref, $index)
	{
		return @DB::connection("mysql")->table($table)->where($column, $ref)->first()->{$index};
	}

	protected function createCompetences()
	{
		$createPayload = DB::connection("old_mysql")->table("competences")->get()->map(function ($x) {
			return [
				"name" => $x->name,
				"tenant_id" => $this->tenant->id,
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
				"import_ref" => $x->id,
			];
		})->toArray();
		Competence::insert($createPayload);
	}


	public function removeTags($string)
	{
		return trim(strip_tags($string));
	}
}
