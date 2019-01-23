<?php namespace App\Http\Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use EntityManager;
use App\Models\Crt;
use App\Models\Answer;
use URL;
use Validator;
use Input;
use Response;
use Redirect;
use Mail;
use Helpers;
use Carbon\Carbon;
use Log;

class CrtController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Dashboard Admin Controller
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
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$crts = EntityManager::getRepository("App\Models\Crt")->findBy(array('deleted' => null));
		return view('crt.index', compact('crts'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if(Auth::User()->is('MFP'))
			abort(404);

		$crt = new Crt();

		$rests = array(0 => 'Please Select a Restaurant');
		foreach(EntityManager::getRepository("App\Models\Restaurants")->findBy(array('deleted' => null), array('rest_id' => 'ASC')) as $rest)
			$rests[$rest->rest_id] = "{$rest->rest_name}";

		$mfps = array(0 => 'Select a Restaurant Above');
		foreach(EntityManager::getRepository("App\Models\User")->findBy(array('type' => 'MFP', 'deleted' => null), array('lastname' => 'ASC')) as $user)
			$mfps[$user->id] = "{$user->name} {$user->lastname}";

		$dfps = array(0 => 'Select a Restaurant Above');
		foreach(EntityManager::getRepository("App\Models\Dfp")->findBy(array('deleted' => null), array('lastname' => 'ASC')) as $user)
			$dfps[$user->id] = "{$user->name} {$user->lastname}";

		$action = URL::route('crt.store');

		$editMode = false;

		return view('crt.edit', compact('crt','rests','mfps','dfps','action','editMode'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(Auth::User()->is('MFP'))
			abort(404);

		$activeTab = Input::get('active-tab');

		// if we want to poach existing users...
		if ($activeTab == 'exist')
		{
			$ecrts = (Input::get('existing_crt'));
			$this->poachCrts($ecrts);

			return Response::json(array(
				'success' => true,
				'refresh' => true,
			));
		}
		else

		$rules = array(
			'name'       => 'required',
			'lastname'   => 'required',
			'email'      => 'required|email',
			'restNumber' => 'required|numeric'
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Response::json(array(
				'fail' => true,
				'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			/* This will allow them to update CRT information
			if ($crt = EntityManager::getRepository("App\Models\Crt")->findOneBy(array('email' => Input::get('email')))) {

				// We need to update the CRT instead...
				if(Input::get('mfps_id') != 0 && Input::get('dfps_id') != 0)
				{
					$crt->name = Input::get('name');
					$crt->lastname  = Input::get('lastname');
					$crt->email = Input::get('email');
					$crt->restNumber = Input::get('restNumber');
					$crt->Dfp_id = Input::get('dfps_id');
					$crt->userMfp_id = 69; //Input::get('mfps_id');
					$crt->deleted = null;
					$crt->deleted_by = null;
					Auth::User()->addCrt($crt);

					$mfp = EntityManager::getRepository("App\Models\User")->find(Input::get('mfps_id'));

					Log::info($mfp->id);

					$mfp->addCrt($crt);
				}

				EntityManager::flush();

				return Response::json(array(
					'success' => true,
					'id' => $crt->getId(),
					'status' => $crt->getStatus(),
					'view' => view('crt.show', compact('crt'))->__toString()
				));
			}
			else
			{
				// store
				$crt = new Crt();
				$crt->name = Input::get('name');
				$crt->lastname  = Input::get('lastname');
				$crt->email = Input::get('email');
				$crt->restNumber = Input::get('restNumber');
				$crt->Dfp_id = Input::get('dfps_id');
				Auth::User()->addCrt($crt);
			}
			*/

			// start: this is the "old" functionality in place before the block above.
			// store
			$crt = new Crt();
			$crt->name = Input::get('name');
			$crt->lastname  = Input::get('lastname');
			$crt->email = Input::get('email');
			$crt->restNumber = Input::get('restNumber');
			$crt->Dfp_id = Input::get('dfps_id');
			Auth::User()->addCrt($crt);
			// end: this is the "old" functionality in place before the block above.

			if ($theCrt = EntityManager::getRepository("App\Models\Crt")->findOneBy(array('email' => Input::get('email')))) {
				return Response::json(array(
					'duplicate' => true,
					'crtEmail' => $theCrt->email,
					'dmName' => $theCrt->userDM->name . " " . $theCrt->userDM->lastname,
					'mfpName' => $theCrt->userMFP->name . " " . $theCrt->userMFP->lastname,
				));
			}

			if(Input::get('mfps_id') != 0)
			{
				EntityManager::getRepository("App\Models\User")->find(Input::get('mfps_id'))->addCrt($crt);
				Mail::send(array("emails.associated_mfp","emails.txt.associated_mfp"), ['crt' => $crt], function($message) use($crt)
				{
					$message->to(Helpers::mail($crt->userMFP->email), $crt->userMFP->name." ".$crt->userMFP->lastname)->subject('New CRT Candidate');
					$message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
				});
			}
			foreach(EntityManager::getRepository("App\Models\Survey\SurveyItem")->findBy(array('deleted' => 'false')) as $surveyItem)
			{
				$answer = new Answer();
				$answer->surveyItem = $surveyItem;
				EntityManager::persist($answer);
				$crt->addAnswer($answer);
			}

			EntityManager::persist($crt);
			EntityManager::flush();

			return Response::json(array(
				'success' => true,
				'id' => $crt->getId(),
				'status' => $crt->getStatus(),
				'view' => view('crt.show', compact('crt'))->__toString()
			));
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$crt = EntityManager::getRepository("App\Models\Crt")->find($id);
		if(Auth::User()->is('MFP') && $crt->userMFP !== Auth::User())
			$view_string = "";
		else
			$view_string = view('crt.show', compact('crt'))->__toString();

		return Response::json(array(
			'success' => true,
			'id' => $crt->getId(),
			'status' => $crt->getStatus(),
			'view' => $view_string
		));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$crt = EntityManager::getRepository("App\Models\Crt")->find($id);
		if(Auth::User()->is('MFP'))
		{
			$mfps = array(0 => "I don't know this CRT");
			$mfps[Auth::User()->id] = Auth::User()->name." ".Auth::User()->lastname;
		}

		$rests = array(0 => 'Please Select a Restaurant');
		foreach(EntityManager::getRepository("App\Models\Restaurants")->findBy(array('deleted' => null), array('rest_id' => 'ASC')) as $rest)
			$rests[$rest->rest_id] = "{$rest->rest_name}";

		$mfps = array(0 => 'Select a Restaurant Above');
		foreach(EntityManager::getRepository("App\Models\User")->findBy(array('type' => 'MFP', 'deleted' => null), array('lastname' => 'ASC')) as $user)
			$mfps[$user->id] = "{$user->name} {$user->lastname}";

		$dfps = array(0 => 'Select a Restaurant Above');
		foreach(EntityManager::getRepository("App\Models\Dfp")->findBy(array('deleted' => null), array('lastname' => 'ASC')) as $user)
			$dfps[$user->id] = "{$user->name} {$user->lastname}";

		$action = URL::route('crt.update', ['id' => $id]);

		$editMode = true;

		return view('crt.edit', compact('crt','rests','mfps','dfps','action','editMode'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$rules = array(
			'name'       => 'required',
			'lastname'   => 'required',
			'email'      => 'required|email',
			'restNumber' => 'required|numeric'
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if (!Auth::User()->is('MFP') && $validator->fails()) {
			return Response::json(array(
				'fail' => true,
				'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			// store
			$crt = EntityManager::getRepository("App\Models\Crt")->find($id);
			if(!Auth::User()->is('MFP'))
			{
				$crt->name = Input::get('name');
				$crt->lastname  = Input::get('lastname');
				$crt->email = Input::get('email');
				$crt->restNumber = Input::get('restNumber');
				$crt->Dfp_id = Input::get('dfps_id');
			}
			if(Input::get('mfps_id') != 0 && Input::get('dfps_id') != 0)
			{
				$oldUserMFP = $crt->userMFP;
				$newUserMFP = EntityManager::getRepository("App\Models\User")->find(Input::get('mfps_id'));

				if($oldUserMFP !== $newUserMFP)
				{
					$newUserMFP->addCrt($crt);
					Mail::send(array("emails.associated_mfp","emails.txt.associated_mfp"), ['crt' => $crt], function($message) use($crt)
					{
						$message->to(Helpers::mail($crt->userMFP->email), $crt->userMFP->name." ".$crt->userMFP->lastname)->subject('New CRT Candidate');
						$message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
					});
				}
			}
			else
				if(isset($crt->userMFP))
				{
					if(Auth::User()->is('MFP'))
						Mail::send(array("emails.dontknow_crt","emails.txt.dontknow_crt"), ['crt' => $crt], function($message) use($crt)
						{
							$message->to(Helpers::mail($crt->userDM->email), $crt->userDM->name." ".$crt->userDM->lastname)->subject('Unrecognized MFP Alignment');
							$message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
						});
					$crt->userMFP->removeCrt($crt);
				}
			EntityManager::flush();

			// Poach CRTS if selected

//			$this->poachCrts($restNumber, $crts);

			return Response::json(array(
				'success' => true,
				'id' => $crt->getId(),
				'status' => $crt->getStatus(),
				'view' => view('crt.show', compact('crt'))->__toString()
			));
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// delete
		$crt = EntityManager::getRepository("App\Models\Crt")->find($id);
		$mytime = Carbon::now();
		$crt->deleted = $mytime->toDateTimeString();

		Log::info($crt->deleted);

		$crt->deleted_by = Auth::User()->id;
	//	$crt->email = $crt->email . '_' . time();
		EntityManager::flush();

		return Response::json(array(
			'success' => true,
			'id' => $crt->getId()
		));
	}

	/**
	 * Approve crt.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function approve($id)
	{
		// delete
		$crt = EntityManager::getRepository("App\Models\Crt")->find($id);

		if(!Auth::User()->is('DM') || $crt->userDM !== Auth::User() || !isset($crt->userMFP))
			abort(404);

		$crt->approved_at  = new \DateTime();

		Mail::send(array("emails.crt_approved","emails.txt.crt_approved"), ['crt' => $crt], function($message) use($crt)
		{
			$message->to(Helpers::mail($crt->userMFP->email), $crt->userMFP->name." ".$crt->userMFP->lastname)->subject('New CRT Pre-Certification Request');
			$message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
		});

		EntityManager::flush();

		return Response::json(array(
			'success' => true,
			'id' => $crt->getId()
		));
	}

	/**
	 * PreCertify crt.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function precertify($id)
	{
		// delete
		$crt = EntityManager::getRepository("App\Models\Crt")->find($id);

		if(!Auth::User()->is('MFP') || $crt->userMFP !== Auth::User())
			abort(404);

		$crt->precertified_at  = new \DateTime();

		Mail::send(array("emails.crt_precertified","emails.txt.crt_precertified"), ['crt' => $crt], function($message) use($crt)
		{
			$message->to(Helpers::mail($crt->userDM->email), $crt->userDM->name." ".$crt->userDM->lastname)->subject("Your CRT and Training Restaurant has been Pre-Certified!");
			$message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
		});

		EntityManager::flush();

		return Response::json(array(
			'success' => true,
			'id' => $crt->getId()
		));
	}

	/**
	 * Certify crt.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function certify($id)
	{
		// delete
		$crt = EntityManager::getRepository("App\Models\Crt")->find($id);

		if(!Auth::User()->is('MFP') || $crt->userMFP !== Auth::User())
			abort(404);

		$crt->certified_at  = new \DateTime();

		Mail::send(array("emails.crt_certified","emails.txt.crt_certified"), ['crt' => $crt], function($message) use($crt)
		{
			$message->to(Helpers::mail($crt->userDM->email), $crt->userDM->name." ".$crt->userDM->lastname)->subject("Final CRT Certification is Complete");
			$message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
		});

		EntityManager::flush();

		return Response::json(array(
			'success' => true,
			'id' => $crt->getId()
		));
	}

	public function decertify($id)
	{
		$crt = EntityManager::getRepository("App\Models\Crt")->find($id);

		// TODO: determine access control
//		if(!Auth::User()->is('MFP') || $crt->userMFP !== Auth::User())
//			abort(404);

		$crt->certified_at = null;
		$crt->decertified_at  = new \DateTime();

		EntityManager::flush();

		return Response::json(array(
			'success' => true,
			'id' => $crt->getId()
		));
	}

	public function getMfpDfp($id)
	{
		$data = EntityManager::createQuery('SELECT b FROM App\Models\RestaurantMfp a JOIN App\Models\MfpDfp b WITH a.mfp_id=b.mfp_id WHERE a.rest_id = '.$id)->getResult();
		return Response::json(array(
			'success' => true,
			'mfp_id' => isset($data[0]->mfp_id) ? $data[0]->mfp_id : 0,
			'dfp_id' => isset($data[0]->dfp_id) ? $data[0]->dfp_id : 0
		));
	}

	public function getCrts($restNumber)
	{
		$rest_crts = EntityManager::getRepository("App\Models\Crt")->findBy(array('deleted' => null, 'restNumber' => $restNumber));
		foreach ($rest_crts as $crt) {
			$crts[] = array(
				'id' => $crt->id,
				'name' => implode(' ', array($crt->name, $crt->lastname))
			);
		}

		return Response::json(array(
			'success' => true,
			'crts' => $crts
		));
	}

	public function poachCrts($crts)
	{
		foreach ($crts as $crt) {
			$poachedCrt = EntityManager::getRepository("App\Models\Crt")->find($crt);
			Auth::User()->addCrt($poachedCrt);
			EntityManager::persist($poachedCrt);
			EntityManager::flush();
		}
	}
}
