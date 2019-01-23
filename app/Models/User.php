<?php namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $name; 
    
    /**
     * @ORM\Column(type="string")
     */
    public $lastname;    

    /**
     * @ORM\Column(type="string", unique=true)
     */
    public $email;

    /**
     * @ORM\Column(type="string")
     */
    public $password;
    
    /** 
     * @ORM\Column(type="string", columnDefinition="ENUM('ADMIN', 'DM', 'MFP')")
     */
    public $type;    
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $remember_token;

    /**
     * @var \DateTime $created_at
     *
     * @ORM\Column(type="datetime")
     */
    public $created_at;
    
    /**
     * @var \DateTime $updated_at
     *
     * @ORM\Column(type="datetime")
     */
    public $updated_at;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	public $deleted;

	/**
	 * @ORM\Column(type="integer")
	 */
	public $deleted_by;
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
    	$this->updated_at = new \DateTime();
    
    	if($this->created_at == null)
    	{
    		$this->created_at = new \DateTime();
    	}
    }    
	
	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->id;		
	}	
	
	/**
	 * Get the password for the user.
	 *
	 * @return string
	*/
	public function getAuthPassword()
	{
		return $this->password;
	}			
	
	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	*/
	public function getRememberTokenName()
	{
		return 'remember_token';
	}	
	
	/**
	 * Get the e-mail address where password reset links are sent.
	 *
	 * @return string
	 */
	public function getEmailForPasswordReset()
	{
		return $this->email;	
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	public function is($type)
	{
		return $type == $this->type;		
	}
	

}
