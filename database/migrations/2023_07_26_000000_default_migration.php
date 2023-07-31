<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\StartUpSeeder;

class DefaultMigration extends Migration
{
	private $defaultOptions = ["id" => true, "timestamps" => true, "softDeletes" => true];

	public function createTable($tableName, $callback, $op = [])
	{
		Schema::create($tableName, function (Blueprint $table) use ($callback, $op) {
			$options = array_merge($this->defaultOptions, $op);
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';
			$table->engine = 'InnoDB';
			if ($options["id"]) $table->bigIncrements('id');
			$callback($table);
			if ($options["softDeletes"]) $table->softDeletes();
			if ($options["timestamps"]) $table->timestamps();
		});
	}

	public function addForeignKey($table, $fk, $refTable, $refColumn, $onDelete = 'restrict')
	{
		$table->unsignedBigInteger($fk)->nullable();
		$table->foreign($fk)
			->references($refColumn)
			->on($refTable)
			->onDelete($onDelete);
		return $table;
	}

	public function up()
	{
		$this->initFramework();
		$this->initAppTables();
	}

	public function initFramework()
	{
		$this->createTable('tenants', function (Blueprint $table) {
			$table->string('name');
			$table->jsonb('data')->nullable();
		});

		$this->createTable('permissions', function (Blueprint $table) {
			$table->string('name');
			$table->string('key');
		}, ["timestamps" => false, "softDeletes" => false]);

		$this->createTable('access_groups', function (Blueprint $table) {
			$table->string('name');
			$table = $this->addForeignKey($table, 'tenant_id', 'tenants', 'id');
		});

		$this->createTable('access_group_permissions', function (Blueprint $table) {
			$table = $this->addForeignKey($table, 'access_group_id', 'access_groups', 'id');
			$table = $this->addForeignKey($table, 'permission_id', 'permissions', 'id');
		}, ["id" => false, "timestamps" => false, "softDeletes" => false]);
		StartUpSeeder::createPermissions();

		$this->createTable('users', function (Blueprint $table) {
			$table->string('name');
			$table->string('email');
			$table = $this->addForeignKey($table, 'tenant_id', 'tenants', 'id');
			$table = $this->addForeignKey($table, 'access_group_id', 'access_groups', 'id');
			$table->string('provider')->nullable();
			$table->string('provider_id')->nullable();
			$table->string('password');
			$table->string('role')->nullable();
			$table->string('plan')->nullable();
			$table->jsonb('data')->nullable();
			$table->timestamp('email_verified_at')->nullable();
			$table->timestamp('plan_expires_at')->nullable();
			$table->rememberToken();
		});

		$this->createTable('tokens', function (Blueprint $table) {
			$table->string('type');
			$table->string('value');
			$table->timestamp('due_date')->nullable();
			$table->morphs("entity");
		}, [
			"softDeletes" => false
		]);

		$this->createTable('jobs', function (Blueprint $table) {
			$table->string('queue')->index();
			$table->longText('payload');
			$table->unsignedTinyInteger('attempts');
			$table->unsignedInteger('reserved_at')->nullable();
			$table->unsignedInteger('available_at');
			$table->unsignedInteger('created_at');
		}, ["timestamps" => false, "softDeletes" => false]);

		$this->createTable('failed_jobs', function (Blueprint $table) {
			$table->text('connection');
			$table->text('queue');
			$table->longText('payload');
			$table->longText('exception');
			$table->timestamp('failed_at')->useCurrent();
		}, ["timestamps" => false, "softDeletes" => false]);

		$this->createTable('resource_configs', function (Blueprint $table) {
			$table->string('name');
			$table->string('resource');
			$table->string('config');
			$table->jsonb('data');
		}, ["timestamps" => false, "softDeletes" => false]);

		(new StartUpSeeder())->run();
	}

	public function initAppTables()
	{
		$this->createTable('competences', function (Blueprint $table) {
			$table->string('name');
			$table->jsonb('skills');
			$table = $this->addForeignKey($table, 'tenant_id', 'tenants', 'id');
		});
	}

	public function down()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$tables = DB::select('SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = ?', ['public']);
		foreach ($tables as $table) {
			Schema::dropIfExists($table->tablename);
		}
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}
