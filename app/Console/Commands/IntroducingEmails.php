<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use EntityManager;
use Mail;
use Helpers;;
use Password;

class IntroducingEmails extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'introducingemails:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send Emails to users to reset his passwords.';

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
		$type = $this->option('type');
		$email = $this->option('email');
		
		foreach(EntityManager::getRepository("App\Models\User")->findAll() as $user)
		{			
			if(($type == null || $user->type == $type) && ($email == null || $user->email == $email))
			{
				Password::sendResetLink(array("email"=>$user->email), function($message)
				{
					$message->introducingEmail = true;
					$message->subject('CRT Certification Manager - Verification Required');
					$message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
				});
			}
		}
		
		echo 'OK';
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			/*['option', InputArgument::REQUIRED, '1: CRT Final Certification is pending. 2: CRT Certification will expire in 60 days'],*/
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
			['type', null, InputOption::VALUE_REQUIRED, 'Type of user to reset password.', null],
			['email', null, InputOption::VALUE_REQUIRED, 'Email of user to send introducing email.', null]
		];
	}

}
