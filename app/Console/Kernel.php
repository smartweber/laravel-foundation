<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
		'App\Console\Commands\CheckRestaurants',
		'App\Console\Commands\ExpireCertification',
		'App\Console\Commands\Fixture',
		'App\Console\Commands\IntroducingEmails',
		'App\Console\Commands\ProcessList',
		'App\Console\Commands\SplitList',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$now = new \DateTime();
		$now = $now->format('Y-m-d_H:i:s');

		$schedule->command('restaurants:check ftps.servicemanagement.com BK_GURU_FTPS 6Z5LgQJO')
			->dailyAt('01:00')
			->withoutOverlapping()
			->sendOutputTo(storage_path() . "/CheckRestaurantResults/" . $now );

		$schedule->command('splitlist')
			->cron('* 23 * * *')
			->withoutOverlapping();

		$schedule->command('processlist')
			->cron('*/10 0,1,2 * * *')
			->withoutOverlapping()
			->sendOutputTo(storage_path() . "/ProcessListResults/" . $now );

		$schedule->command('certification:expire:check')
			->dailyAt('1:00');

	}

}
