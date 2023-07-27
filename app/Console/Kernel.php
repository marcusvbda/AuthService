<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	protected $commands = [
		// 
	];

	protected function schedule(Schedule $schedule)
	{
		$schedule
			->command('logs:clear')
			->days(2)
			->runInBackground();

		$schedule
			->command('activate-user-tokens:clear')
			->hourly()
			->runInBackground();

		$schedule
			->command('optimize:clear')
			->days(2)
			->runInBackground();

		$schedule
			->command('config:cache')
			->days(2)
			->runInBackground();

		$schedule
			->command('queue:work --queue=resource-import,alert-broadcasts,event-broadcasts,default')
			->everyMinute()
			->runInBackground();
	}

	protected function commands()
	{
		$this->load(__DIR__ . '/Commands');
		require base_path('routes/console.php');
	}
}
