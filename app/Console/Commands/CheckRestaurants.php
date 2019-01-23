<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use EntityManager;

class CheckRestaurants extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'restaurants:check';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check restaurants and publish in SFTP.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$host = $this->argument('host');
		$user = $this->argument('user');
		$pass = $this->argument('pass');
		
		$data['header'] = array("Fname","LName","Restaurant Number");

		$crts = EntityManager::getRepository("App\Models\Crt")->findBy(array('deleted' => null));
		foreach($crts as $crt)
		{
			if ($crt->haveAccess() && !array_key_exists($crt->restNumber, $data))
				$data[$crt->restNumber] = array($crt->name, $crt->lastname, $crt->restNumber);
		}


		$fp = fopen('CRT_OutgoingFeed.csv', 'w');
		
		foreach($data as $row)
			fputcsv($fp, $row);
		
		fclose($fp);
		
		$script = "sshpass -p {$pass} sftp {$user}@{$host}<<END_SCRIPT
put CRT_OutgoingFeed.csv
END_SCRIPT";
		echo shell_exec($script);

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['host', InputArgument::REQUIRED, 'SFTP Host.'],
			['user', InputArgument::REQUIRED, 'User.'],
			['pass', InputArgument::REQUIRED, 'Password.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			/*['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],*/
		];
	}

}
