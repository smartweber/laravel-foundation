<?php namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Auth;
use EntityManager;
use Illuminate\Contracts\Auth\Registrar;
use Redirect;


class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Setup DFP's to pass to views
		$dfps = EntityManager::getRepository("App\Models\Dfp")->findBy(array('deleted' => null));
		$dfp = array();
		foreach ($dfps as $dfpi) {
			$dfp[$dfpi->id] = $dfpi->name . " " . $dfpi->lastname;
		}

		switch (Auth::User()->type)
		{
			case 'ADMIN':
					$users = EntityManager::getRepository("App\Models\User")->findBy(array('type' => array("DM", "MFP"), 'deleted' => null));
					return view('dashboard.admin', compact('crts', 'users', 'dfp'));
			case 'DM':
					$crts = Auth::User()->getCrts();
					return view('dashboard.dm', compact('crts', 'dfp'));
			case 'MFP':
					$crts = Auth::User()->getCrts();
					return view('dashboard.mfp', compact('crts', 'dfp'));
		}
		App::abort(404);
	}
	
	/**
	 * Get report csv.
	 *
	 * @return Response
	 */
	public function report()
	{
		if(!Auth::User()->is('ADMIN'))
			abort(404);
		
		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=report.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');
		
		// output the column headings
		fputcsv($output, array('DM Name','DM Email','MFP Name','MFP Email','CRT Name','CRT Email','CRT Code','Restaurant #',
		'Section 1 (%)','Section 2 (%)','Section 3 (%)','Section 4 (%)','Section 5 (%)','Section 6 (%)','Section 7 (%)',
		'Date & Time DM Started', 'Date & Time DM Finished','Date & Time MFP PreCertified','Date & Time MFP Certified','Date CRT will Expire'));		
		
		foreach(EntityManager::getRepository("App\Models\Crt")->findBy(array('deleted' => null)) as $crt)
		{
			$row = array('','','','','','','','','','','','','','','','','','','','');
			if(isset($crt->userDM))
			{
				$row[0] = $crt->userDM->name . ' ' . $crt->userDM->lastname;
				$row[1] = $crt->userDM->email;
			}
			if(isset($crt->userMFP))
			{
				$row[2] = $crt->userMFP->name . ' ' . $crt->userMFP->lastname;
				$row[3] = $crt->userMFP->email;
			}
			$row[4] = $crt->name . ' ' . $crt->lastname;
			$row[5] = $crt->email;
			$row[6] = $crt->id;
			$row[7] = $crt->restNumber;
			
			for($i = 1; $i <= 7; $i++)
			{
				$info = $crt->sectionInfo($i);
				$row[7+$i] = $info['score'] . ' %';
			}
			
			$row[15] = $crt->created_at->format('m/d/Y h:n:s');
			if(isset($crt->approved_at))
				$row[16] = $crt->approved_at->format('m/d/Y h:n:s');
			if(isset($crt->precertified_at))
				$row[17] = $crt->precertified_at->format('m/d/Y h:n:s');
			if(isset($crt->certified_at))
				$row[18] = $crt->certified_at->format('m/d/Y h:n:s');
			
			$expiredDate = null;
			if(isset($crt->precertified_at))
			{
				$expiredDate = $crt->precertified_at;
				$expiredDate->add(new \DateInterval('P90D'));
			}
			if(isset($crt->certified_at))
			{
				$expiredDate = $crt->certified_at;
				$expiredDate->add(new \DateInterval('P1Y'));
			}
			if(isset($expiredDate))
			{
				$row[19] = $expiredDate->format('m/d/Y h:n:s');
				$today = new \DateTime();
				$today->setTime(0,0,0);
				if($today > $expiredDate)
				$row[19] ="EXPIRED " . $row[19];
			}
			else
				$row[19] = "WAITING CERTIFICATION";		
			
			fputcsv($output, $row);		
		}
		
	}

	/**
	 * Login as different user.
	 *
	 * @return Response
	 */
	public function loginas($id)
	{
		if(!Auth::User()->is('ADMIN'))
			abort(404);

		Auth::logout();
		Auth::loginUsingId($id);	

		return Redirect::to('home');
	}	
}
