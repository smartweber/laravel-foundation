<?php namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use EntityManager;

class DoctrineUserProvider implements UserProvider {

	/**
	 * The hasher implementation.
	 *
	 * @var \Illuminate\Contracts\Hashing\Hasher
	 */
	protected $hasher;

	/**
	 * Create a new database user provider.
	 *
	 * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
	 * @param  string  $model
	 * @return void
	 */
	public function __construct(HasherContract $hasher)
	{
		$this->hasher = $hasher;
	}

    public function retrieveById($identifier)
    {
		return	EntityManager::find("App\Models\User",$identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
		return EntityManager::getRepository("App\Models\User")
					 ->findOneBy(array('email' => $identifier, 'remember_token' => $token));
    }

    public function updateRememberToken(UserContract $user, $token)
    {
		$user->remember_token = $token;

		EntityManager::flush();
    }

    public function retrieveByCredentials(array $credentials)
    {	
    	foreach ($credentials as $key => $value)
    	{
    		if ( str_contains($key, 'password')) 
    			unset($credentials[$key]);
    	}
		return	EntityManager::getRepository("App\Models\User")->findOneBy($credentials);
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {
		$plain = $credentials['password'];

		return $this->hasher->check($plain, $user->getAuthPassword());
    }

}