<?php namespace App\Services;

use App\Models\User;
use App\Models\UserDM;
use App\Models\UserMFP;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use EntityManager;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|max:255',
			'lastname' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
			'type' => 'required',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		switch($data['type'])
		{
			case "DM":
				$user = new UserDM();
				break;
			case "MFP":
				$user = new UserMFP();
				break;
			default:
				$user = new User();
		}
		
		$user->name = $data['name'];
		$user->lastname = $data['lastname'];
		$user->email = $data['email'];
		$user->type = $data['type'];
		$user->password  = bcrypt($data['password']);
		EntityManager::persist($user);
		EntityManager::flush();
		return $user;
	}

}
