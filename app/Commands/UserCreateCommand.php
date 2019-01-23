<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use Symfony\Component\Console\Input\InputArgument;

class UserCreateCommand extends Command implements SelfHandling {

	public $name = "user:create";

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		//
	}

	public function fire()
	{

	}


	/**
	 * @return array
     */
	public function getArguments()
	{
		return array(
			array('username', InputArgument::REQUIRED, "Username"),
			array('username', InputArgument::REQUIRED, "Password")
		);
	}
}
