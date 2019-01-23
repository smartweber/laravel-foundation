<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use EntityManager;
use Mail;
use Helpers;

class ExpireCertification extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'certification:expire:check';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Reminder when CRTs expire certification.';

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
		//check precertified - 5 days before expire
			$date1 = new \DateTime();
			$date1->setTime(0,0,0);
			$date1->sub(new \DateInterval("P85D"));
			$date2 = new \DateTime();
			$date2->setTime(0,0,0);
			$date2->sub(new \DateInterval("P84D"));
			$crts = EntityManager::getRepository("App\Models\Crt")->createQueryBuilder('c')
									->where('c.precertified_at >= :date1')
									->andwhere('c.precertified_at <= :date2')
									->andwhere('c.certified_at is NULL')
									->setParameter('date1',$date1)
									->setParameter('date2',$date2)
									->getQuery()
									->getResult();

//		echo $date1->format('Y-m-d') . "\n\r" . $date2->format('Y-m-d') . "\n\r";

			foreach($crts as $crt)
			{
    			Mail::send(array("emails.reminder_expire_30","emails.txt.reminder_expire_30"), ['crt' => $crt], function($message) use($crt)
    			{
    				$message->to(Helpers::mail($crt->userMFP->email), $crt->userMFP->name." ".$crt->userMFP->lastname)->subject("CRT Final Certification is Pending!");
					$message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
    			});
			}						

		//check certified
			$date1 = new \DateTime();
			$date1->setTime(0,0,0);
			$date1->sub(new \DateInterval("P1Y"));
			$date1->add(new \DateInterval("P60D"));
			$date2 = new \DateTime();
			$date2->setTime(0,0,0);
			$date2->sub(new \DateInterval("P1Y"));
			$date2->add(new \DateInterval("P61D"));
			$crts = EntityManager::getRepository("App\Models\Crt")->createQueryBuilder('c')
									->where('c.certified_at >= :date1')
									->andwhere('c.certified_at <= :date2')
									->setParameter('date1',$date1)
									->setParameter('date2',$date2)
									->getQuery()
									->getResult();

//		echo $date1->format('Y-m-d') . "\n\r" . $date2->format('Y-m-d') . "\n\r";

			foreach($crts as $crt)
			{
				Mail::send(array("emails.reminder_expire_60","emails.txt.reminder_expire_60"), ['crt' => $crt], function($message) use($crt)
				{
					$message->to(array(Helpers::mail($crt->userMFP->email),Helpers::mail($crt->userDM->email),Helpers::mail($crt->email)))->subject("CRT Certification will expire in 60 days");
					$message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
				});
			}
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
			/*['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],*/
		];
	}

}
