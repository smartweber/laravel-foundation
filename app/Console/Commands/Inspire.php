<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use EntityManager;
use Helpers;
use App\Models\User;
use App\Models\UserMfp;
use Mail;

class Inspire extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'inspire';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Display an inspiring quote';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$user_mfp = EntityManager::getRepository('App\Models\User')->findBy(array("email"
=>'sean@originalimpressions.com'));


                            $user_mfp->restNumber = "333";
                            $user_mfp->active_crts = "crt string";

		Mail::send(array("emails.active_crts", "emails.txt.active_crts"), ['user'=>$user_mfp], function ($message) use ($user_mfp) {
			$message->to("r.komatz@gmail.com", "Richard K")->subject('Welcome to BK Foundations');
			$message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
		});
	}

}
