<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use EntityManager;
use Mail;
use Helpers;
use Illuminate\Contracts\Auth\Registrar;
use Password;

class Fixture extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'fixture:run';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Fill out database with default data.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Registrar $registrar)
	{
		$this->registrar = $registrar;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$arr_surveys = array('Training Materials & Resources','Your Restaurant – Clean, Safe & Organized','Your Food – Safe, Hot and Tasty',
				'Your Guests –Guest Service & Guest Recovery','Your Team – Team Training & Labor Management',
				'Shift Leadership','CRT Skills / Behaviors');
		$arr_colors = array(1,2,3,4,5,6,7);
		$arr_surveyssub[] = array('Reference Materials – Paper & Electronic','Operations Tools / Equipment');
		$arr_surveyssub[] = array('Cleaning Management System','Travel Path System','Restaurant Organization');
		$arr_surveyssub[] = array('Hand Washing System','Hot Product Holding System','Fresh & Ready System','Sanitizing System','Time & Temperature Control System');
		$arr_surveyssub[] = array('Guest Service System – C.A.R.E.','Guest Recovery System');
		$arr_surveyssub[] = array('Team Training System','Labor Management System');
		$arr_surveyssub[] = array('Shift Routines');
		$arr_surveyssub[] = array('Foundations Training Program');
		
		$arr_surveyitem[0][] = array('Locate and navigate BURGER KING® Operations Manual','Locate and navigate Equipment Emphasis Guides',
				'Hazard Communication Program & Training Log - Updated','Loss Control / Policy Manual or Equivalent',
				'Legal & Government Compliant Posters (Federal)','Fresh & Ready Poster','Restaurant Routines (paper or tablet)',
				'Command Station present','Manager’s Pocket Guide','BK® GURU Coaching Guide',
				'Foundations Modules Coaching Guide','Foundations CRT Guidebook');
		$arr_type[0][] = array(1,1,1,1,1,1,1,1,1,1,1,1);
		$arr_surveyitem[0][] = array('Stopwatches – 1 minimum','Microwave Wattage bowl and/or Micro Tester','Digital Thermometer (w/probe and surface attachments)',
				'Digital Scale','Shortening color test kit','Broiler Cleaning Tools / Brushes','Shake Machine Brushes / Tools',
				'Filter Machine / Fryer Tools','All equipment labeled & programmed per OPS Standards','All equipment clean and in working condition',
				'Safety Equipment clean and in good condition');
		$arr_type[0][] = array(1,1,1,1,1,1,1,1,1,1,1);
		
		$arr_surveyitem[1][] = array('Managers assign and follow-up on cleaning tasks','Zone Cleaning Tools & procedures are used / followed as per OPS standards',
				'Hazard Communication program is used effectively to communicate and train chemical accident procedures',
				'Closing Checklist is used effectively','Team Members demonstrate “clean as you go” behaviors');
		$arr_type[1][] = array(10,10,5,10,5);
		$arr_surveyitem[1][] = array('Managers conduct effective Travel Paths per OPS standards','Immediate corrective action taken on issues affecting QSC',
				'Managers follow-up on assigned tasks','Managers Coach Team Members during Travel Paths',
				'Managers interact with Guests and perform Table Touches during Travel Paths');
		$arr_type[1][] = array(10,10,10,5,10);
		$arr_surveyitem[1][] = array('Promotional Marketing materials are current and used correctly','Team Communication Board  / King Board is located in a prominent location',
				'Dry Storage and Walk-in coolers/freezers are neat and organized.','Front Counter and Drive-Thru areas are neat and organized',
				'Manager’s office is neat and organized','OPS Reference Materials/ Training Posters are current and located in a prominent location');
		$arr_type[1][] = array(2,2,2,5,2,2);
		
		$arr_surveyitem[2][] = array('Hand washing sinks functional, properly stocked and easily accessible','Team Members properly "wash by the numbers"',
				'Team Members wash hands after food handling has been interrupted','Team Members follow proper gloves procedures during',
				'Team Members are washing hands at least once per hour');
		$arr_type[2][] = array(6,6,6,6,6);
		$arr_surveyitem[2][] = array('KITCHEN MINDERTM updated using current information','Team Members follow KITCHEN MINDERTM procedures when cooking; product placed in proper PHU cavity',
				'PHU Timer Bars used effectively – No Double-tapping, expired product discarded','Heat Chute levels used effectively',
				'Fry Bagging Station procedures correctly executed – Quality Control Timers used to monitor cooking and holding times, proper salting, FIFO rotation, waste buckets used');
		$arr_type[2][] = array(2,3,5,2,5);
		$arr_surveyitem[2][] = array('Condiments prepared at the right time and labeled properly according to the Fresh & Ready Chart.','All products stored properly with correct labels',
				'Pull / Thaw charts updated and used effectively','Condiment Stock Guide updated and used effectively');
		$arr_type[2][] = array(5,5,2,2);
		$arr_surveyitem[2][] = array('Sanitizer solution at proper strength at all open work stations and the 3 compartment sink','Red & green pack sanitizers and unexpired test strips available and used correctly',
				'Team Members take initiative to check sanitizer strength and change as needed','Sanitizer solution at proper strength in spray bottles');
		$arr_type[2][] = array(5,5,1,3);
		$arr_surveyitem[2][] = array('Beef Cookouts are completed and documented per OPS Manual standards','All Equipment Temperature checks are completed as required',
				'All Product Quality Temperature checks are completed as required','Team Members consistently time products, follow proper holding procedures and discard when indicated (without reminders)',
				'Managers routinely monitor hold times throughout their shift');
		$arr_type[2][] = array(5,5,5,5,5);
		
		$arr_surveyitem[3][] = array('Entire restaurant Team is focused on making a connection with their Guests using eye contact, smiles, "Please & Thank You" during every interaction',
				'Team looks for opportunities to provide "Acts of Kindness"','Sales and Service Leaders effectively use a 3-part greeting',
				'Appropriate suggestive selling protocols are followed','Appropriate parting phrases used');
		$arr_type[3][] = array(15,10,10,10,10,10);
		$arr_surveyitem[3][] = array('Guest problems recognized promptly and solved effectively and graciously','Guest complaint section of King board is used effectively to communicate and motivate Team Members',
				'GUEST TRAC ® and Guest Relations comments and feedback reviewed frequently with entire Restaurant team','L.A.S.T. is being effectively used by Team and Managers');
		$arr_type[3][] = array(15,10,10,10);
		
		$arr_surveyitem[4][] = array('BK® GURU is located in a place conducive for learning','MAT tool is current and up to date',
				'BK® GURU Training Plans are developed Monthly and used for initial and on-going training','Every Team Member has a Performance Scorecard',
				'Managers and Team Trainers utilize the Performance Scorecard to reinforce and redirect behaviors','BK® GURU Modules are completed PRIOR to working the station',
				'Managers use the BK® GURU Coaching Guide to coach Team Members in and out of learning modules','The 4-step training process is used effectively',
				'Restaurant achieves BK® GURU Quarterly Milestones and LTO Modules','Restaurant Team Members are knowledgeable of current promotions');
		$arr_type[4][] = array(5,5,5,5,10,10,10,10,5,5);
		$arr_surveyitem[4][] = array('Minimum staffing requirements and previous 1⁄2 hour sales projections used to provide a forecast of direct labor needs',
				'Indirect labor is allocated and scheduled to complete duties such as receiving deliveries, opening / closing the restaurant, salad preparation, porter activities and special considerations',
				'1⁄2 hour sales are recorded and used for tracking labor throughout the day','Restaurant staffed with sufficient amount of Team Members and Managers to provide a positive Guest experience at all times',
				'Managers utilize positioning guides on every day part');
		$arr_type[4][] = array(5,5,5,10,5);
		
		$arr_surveyitem[5][] = array('Restaurant Routines (electronic or paper version) used effectively to manage restaurant operations','Command Station information is current and used effectively',
				'Manager maintains high sense of urgency during pre-rush, rush, and post-rush periods','Shift goals (B2, SOS, Sales, etc.) are set and communicated to Team during Pre-Shift Meetings',
				'Service Team Member knows shift goal(s)','Kitchen Team Member knows shift goal(s)','Manager gives positive reinforcement to Team Members during shift',
				'Manager redirects poor procedures or performance in a professional way during the shift','Manager reacts to service bottlenecks and takes immediate corrective action',
				'Every Manager reads the OPS Calendar information daily','Managers use a problem solving methodology to fix problems long term',
				'REV Action Plan items are completed and maintained as appropriate');
		$arr_type[5][] = array(10,10,5,10,5,5,10,10,10,5,5,15);
		
		$arr_surveyitem[6][] = array('CRT can demonstrate the 4-Step Training Process','CRT can demonstrate proper use of the BK® GURU Coaching Guides (Team Member & Manager Modules)',
				'All Managers (including CRT) have completed all BK® GURU Team Member & Manager modules','CRT has attended a Foundations Train-the-Trainer session',
				'CRT uses the Foundations Daily Agendas to successfully complete daily learning objectives','CRT effectively executes Foundations Training program for multiple Trainees',
				'CRT demonstrates constructive feedback to redirect ineffective behaviors','CRT demonstrates appreciative feedback to reinforce effective behaviors',
				'CRT provide constructive feedback within the Foundations TRAC website every day','CRT ensures Trainees complete Foundations Modules and daily OPS Tests',
				'CRT addresses performance issues immediately','CRT ensures Above Restaurant Leaders stay informed of Trainee’s progress',
				'CRT only certifies Trainees who demonstrate high levels of Shift Readiness','CRT works the same schedule as their Trainee');
		$arr_type[6][] = array(5,5,5,10,10,10,5,5,10,10,5,5,10,5);
		
		
		foreach($arr_surveys as $key_s => $value_s)
		{
			$survey = new \App\Models\Survey\Survey();
			$survey->title = $value_s;
			$survey->color = $arr_colors[$key_s];
			EntityManager::persist($survey);
				
			foreach($arr_surveyssub[$key_s] as $key_ss => $value_ss)
			{
				$surveySub = new \App\Models\Survey\SurveySub();
				$surveySub->subtitle = $value_ss;
				EntityManager::persist($surveySub);
				$survey->addSurveySub($surveySub);
				echo $key_ss;echo "<br/>";
				foreach($arr_surveyitem[$key_s][$key_ss] as $key_si => $value_si)
				{
					$surveyItem = new \App\Models\Survey\SurveyItem();
					$surveyItem->matter = $value_si;
					$surveyItem->type = $arr_type[$key_s][$key_ss][$key_si];
					EntityManager::persist($surveyItem);
					$surveySub->addSurveyItem($surveyItem);
				}
			}
				
			EntityManager::flush();
		}
		
		$arr_users = array();
		$arr_users[]=array("Aaron","Willis","AWillis2@whopper.com","whopper","MFP");
		$arr_users[]=array("Andrew","Fournier","AFournier2@whopper.com","whopper","MFP");
		$arr_users[]=array("Andrew","Hutto","ahutto@whopper.com","whopper","MFP");
		$arr_users[]=array("Andy","Garcia","AGarcia6@whopper.com","whopper","MFP");
		$arr_users[]=array("Arthur","Rodriguez","ARodriguez04@whopper.com","whopper","MFP");
		$arr_users[]=array("Aykea","Gamble","AGamble@Whopper.com","whopper","MFP");
		$arr_users[]=array("Blake","Matthews","BMatthews@whopper.com","whopper","MFP");
		$arr_users[]=array("Brandon","Harvey","BHarvey@whopper.com","whopper","MFP");
		$arr_users[]=array("Brian","Geiger","BGeiger1@Whopper.com","whopper","MFP");
		$arr_users[]=array("Brock","Trautman","BTrautman@Whopper.com","whopper","MFP");
		$arr_users[]=array("Brooke","Grimes","BGrimes@whopper.com","whopper","MFP");
		$arr_users[]=array("Carla","Davison","CDavison@whopper.com","whopper","MFP");
		$arr_users[]=array("Chandler","Arrighi","CArrighi1@Whopper.com","whopper","MFP");
		$arr_users[]=array("Chandra","DiRosaria","CDiRosaria2@whopper.com","whopper","MFP");
		$arr_users[]=array("Christopher","House","CHouse@whopper.com","whopper","MFP");
		$arr_users[]=array("Crystal","Golden","CGolden2@whopper.com","whopper","MFP");
		$arr_users[]=array("David","Kennedy","DKennedy02@Whopper.com","whopper","MFP");
		$arr_users[]=array("David","Mogull","DMogull@Whopper.com","whopper","MFP");
		$arr_users[]=array("David","Rodriguez","DRodriguez@whopper.com","whopper","MFP");
		$arr_users[]=array("Elaine","Kilburn","EKilburn@whopper.com","whopper","MFP");
		$arr_users[]=array("Eric","Goldhersz","EGoldhersz@whopper.com","whopper","MFP");
		$arr_users[]=array("Erica","Holt","EHolt@whopper.com","whopper","MFP");
		$arr_users[]=array("Evelyn","Miranda-Cox","EMiranda-Cox@whopper.com","whopper","MFP");
		$arr_users[]=array("Gregory","Backes","GBackes@whopper.com","whopper","MFP");
		$arr_users[]=array("Hernan","Adamo","HAdamo1@Whopper.com","whopper","MFP");
		$arr_users[]=array("Javin","Grove","JGrove@Whopper.com","whopper","MFP");
		$arr_users[]=array("Jeffrey","Nayavich","JNayavich@whopper.com","whopper","MFP");
		$arr_users[]=array("John","Gargiulo","JGargiulo3@whopper.com","whopper","MFP");
		$arr_users[]=array("John","Lancaster","JLancaster1@Whopper.com","whopper","MFP");
		$arr_users[]=array("Johnny","Walton","JWalton@whopper.com","whopper","MFP");
		$arr_users[]=array("Jonathan","Massey","JMassey@Whopper.com","whopper","MFP");
		$arr_users[]=array("Joseph","Ortega","JOrtega@whopper.com","whopper","MFP");
		$arr_users[]=array("Joshua","Costanzo","JCostanzo@whopper.com","whopper","MFP");
		$arr_users[]=array("Julius","Henderson","JHenderson1@Whopper.com","whopper","MFP");
		$arr_users[]=array("Kelly","Hall","KHall@whopper.com","whopper","MFP");
		$arr_users[]=array("Kenneth","Cobbs","KCobbs@whopper.com","whopper","MFP");
		$arr_users[]=array("Kieara","Patterson","KPatterson02@Whopper.com","whopper","MFP");
		$arr_users[]=array("Leon","Velarde","LVelarde@whopper.com","whopper","MFP");
		$arr_users[]=array("Lesley","Jackson","LJackson01@whopper.com","whopper","MFP");
		$arr_users[]=array("Lysnandie","Jacques","LJacques1@whopper.com","whopper","MFP");
		$arr_users[]=array("Matthew","Ernst","MErnst@whopper.com","whopper","MFP");
		$arr_users[]=array("Matthew","Martinez","MMartinez1@whopper.com","whopper","MFP");
		$arr_users[]=array("Matthew","Shaffer","MShaffer@Whopper.com","whopper","MFP");
		$arr_users[]=array("Michael","Sugar","MSugar2@whopper.com","whopper","MFP");
		$arr_users[]=array("Ofelia","Cruthirds","OCruthirds@whopper.com","whopper","MFP");
		$arr_users[]=array("Paul","Lattibeaudaire","PLattibeaudaire@whopper.com","whopper","MFP");
		$arr_users[]=array("Phillip","Carpenter","PCarpenter@whopper.com","whopper","MFP");
		$arr_users[]=array("Qaiser","Shah","QShah@whopper.com","whopper","MFP");
		$arr_users[]=array("Randy","Sell","RSell@whopper.com","whopper","MFP");
		$arr_users[]=array("Rebecca","Hill","RHill@whopper.com","whopper","MFP");
		$arr_users[]=array("Richard","Aulicino","RAulicino@whopper.com","whopper","MFP");
		$arr_users[]=array("Sam","Meyers","SMeyers@Whopper.com","whopper","MFP");
		$arr_users[]=array("Samy","Guerrero","SGuerrero@whopper.com","whopper","MFP");
		$arr_users[]=array("Sarah","Beaudoin","SBeaudoin2@whopper.com","whopper","MFP");
		$arr_users[]=array("Sarnethia","Smith","SSmith@whopper.com","whopper","MFP");
		$arr_users[]=array("Sean","O'Brien","sobrien@whopper.com","whopper","MFP");
		$arr_users[]=array("Seth","Corless","SCorless@whopper.com","whopper","MFP");
		$arr_users[]=array("Stan","Penner","SPenner@whopper.com","whopper","MFP");
		$arr_users[]=array("Steven","Gerlach","SGerlach01@whopper.com","whopper","MFP");
		$arr_users[]=array("Steven","Johann","SJohann@whopper.com","whopper","MFP");
		$arr_users[]=array("Susan","Kraniewski","SKraniewski@whopper.com","whopper","MFP");
		$arr_users[]=array("Thomas","Grande","TGrande@whopper.com","whopper","MFP");
		$arr_users[]=array("Timothy","Adams","TAdams@whopper.com","whopper","MFP");
		$arr_users[]=array("Timothy","Broadbridge","TBroadbridge@whopper.com","whopper","MFP");
		$arr_users[]=array("Timothy","Irish","TIrish@whopper.com","whopper","MFP");
		$arr_users[]=array("Todd","Gray","TGray@whopper.com","whopper","MFP");
		$arr_users[]=array("Todd","McGrew","TMcGrew@whopper.com","whopper","MFP");
		$arr_users[]=array("Tony","Askins","TAskins@whopper.com","whopper","MFP");
		$arr_users[]=array("Vincent","Kelly","VKelly@whopper.com","whopper","MFP");
		$arr_users[]=array("William","Green","WGreen@Whopper.com","whopper","MFP");
		$arr_users[]=array("William","Turk","WTurk1@whopper.com","whopper","MFP");
		$arr_users[]=array("Zachary","Womble","zwomble@whopper.com","whopper","MFP");
		
		$arr_users[]=array("Susan","Englar","senglar@whopper.com","whopper1","ADMIN");
		$arr_users[]=array("Debbie","Stewart","dstewart2@whopper.com","whopper1","ADMIN");
		$arr_users[]=array("Diana","Jansma","djansma@whopper.com","whopper1","ADMIN");
		$arr_users[]=array("Sean","Karshis","sean@originalimpressions.com","whopper1","ADMIN");
		$arr_users[]=array("Alexander","Santana","alexander@originalimpressions.com","whopper1","ADMIN");
		
		foreach($arr_users as $row)
		{
			$this->registrar->create(array(
					'name' => $row[0],
					'lastname' => $row[1],
					'email' => $row[2],
					'password' => $row[3],
					'type' => $row[4]
			));
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
			/*['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],*/
		];
	}

}
