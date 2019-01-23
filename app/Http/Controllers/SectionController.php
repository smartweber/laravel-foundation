<?php namespace App\Http\Controllers;

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

class SectionController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Section Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "section" for users that
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
     * Display the specified resource.
     *
     * @param  int  $crtid
     * @param  int  $section
     * @return Response
     */
    public function show($crtid, $section)
    {
    	$crt = EntityManager::getRepository("App\Models\Crt")->find($crtid);
    	
    	if(Auth::User()->is('DM') && $crt->userDM != Auth::User())
    		abort(404);
    	if(Auth::User()->is('MFP') && $crt->userMFP != Auth::User())
    		abort(404);    	
    	
    	$survey = EntityManager::getRepository("App\Models\Survey\Survey")->find($section);
    	return view('section.show', compact('crt','survey','section'));
    }
    
    /**
     * Edit the specified resource.
     *
     * @param  int  $crtid
     * @param  int  $section
     * @return Response
     */
    public function edit($crtid, $section)
    {
    	$crt = EntityManager::getRepository("App\Models\Crt")->find($crtid);
    	
    	if(Auth::User()->is('DM') && $crt->userDM != Auth::User())
    		abort(404);
    	if(Auth::User()->is('MFP') && $crt->userMFP != Auth::User())
    		abort(404);    	
    	
    	$survey = EntityManager::getRepository("App\Models\Survey\Survey")->find($section);
    	return view('section.edit', compact('crt','survey','section'));
    }    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  int  $section
     * @return Response
     */
    public function update($crtid, $section)
    {
		echo $crtid.'   ===  '.$section;  	
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  int  $section
     * @return Response
     */
    public function answer($answerId, $field, $value = null)
    {
    	$answer = EntityManager::getRepository("App\Models\Answer")->find($answerId);
    	
    	if(Auth::User()->is('DM') && ($answer->surveyItem->surveySub->survey->id == 7 || $answer->crt->userDM != Auth::User()))
    		abort(404);
    	if(Auth::User()->is('MFP') && ($answer->surveyItem->surveySub->survey->id != 7 || $answer->crt->userMFP != Auth::User()))
    		abort(404);    	
    	
    	$answer->{$field} = $value;
    	EntityManager::flush();
    }       
    
    
	
}
