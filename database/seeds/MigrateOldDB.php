<?php

namespace Database\Seeders;

use App\Http\Models\AccessGroup;
use App\Http\Models\Competence;
use App\Http\Models\Customer;
use App\Http\Models\Demand;
use App\Http\Models\Partner;
use App\Http\Models\Project;
use App\Http\Models\Skill;
use App\Http\Models\Squad;
use App\Http\Models\Tenant;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrateOldDB extends Seeder
{
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createCompetences();
		$this->createSkills();
		$this->createAccessGroups();
		$this->createpermissions();
		$this->createUsers();
		$this->createCustomers();
		$this->createProjects();
		$this->createPartners();
		$this->createSquads();
		$this->createDemands();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	protected function createSquads()
	{
		$createPayload = DB::connection("old_mysql")->table("jobs")->groupBy("squad")->get()->map(function ($x) {
			return [
				"name" => $x->name,
				"tenant_id" => 1,
				"import_ref" => $x->id,
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
				"tenant_id" => 1,
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
	}

	protected function createPartners()
	{
		$createPayload = DB::connection("old_mysql")->table("partners")->get()->map(function ($x) {
			return [
				"name" => $x->company_name,
				"user_id" =>  $this->getOldIndex("users", "import_ref", $x->user_id, "id"),
				"doc_number" => $x->cnpj,
				"tenant_id" => 1,
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
			];
		})->toArray();
		Partner::insert($createPayload);
	}

	protected function createProjects()
	{
		$createPayload = DB::connection("old_mysql")->table("projects")->get()->map(function ($x) {
			return [
				"name" => $x->name,
				"customer_id" =>  $this->getOldIndex("customers", "import_ref", $x->client_id, "id"),
				"google_drive_url" => $x->drive_folder,
				"tenant_id" => 1,
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
				"tenant_id" => 1,
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
				"tenant_id" => 1,
				"email" => $x->email,
				"email_verified_at" => $date,
				"password" => $pass,
				"plan" => "premium",
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
				"import_ref" => $x->id,
			];
		})->toArray();
		User::insert($createPayload);
	}

	protected function createpermissions()
	{
		// criar manualmente
	}

	protected function createAccessGroups()
	{
		$createPayload = DB::connection("old_mysql")->table("roles")->get()->map(function ($x) {
			return [
				"name" => $x->name,
				"tenant_id" => 1,
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
				"import_ref" => $x->id,
			];
		})->toArray();
		AccessGroup::insert($createPayload);
	}

	protected function createSkills()
	{
		$createPayload = DB::connection("old_mysql")->table("skills")->get()->map(function ($x) {
			return [
				"name" => $x->name,
				"tenant_id" => 1,
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
				"tenant_id" => 1,
				"created_at" => $x->created_at,
				"updated_at" => $x->updated_at,
				"import_ref" => $x->id,
			];
		})->toArray();
		Competence::insert($createPayload);
	}

	public function createTenant()
	{
		$this->tenant = Tenant::create([
			'name' => 'Diwe',
			'data' => []
		]);
	}

	public function removeTags($string)
	{
		$string = strip_tags($string);
		return trim(strip_tags($string));
	}
}
