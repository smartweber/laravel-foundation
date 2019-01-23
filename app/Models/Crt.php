<?php namespace App\Models;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="crts")
 * @ORM\HasLifecycleCallbacks()
 */
class Crt{

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
    public $restNumber;    

    /**
     * @ORM\ManyToOne(targetEntity="UserDM", inversedBy="crts", fetch="EAGER")
     **/
    public $userDM;

    /**
     * @ORM\ManyToOne(targetEntity="UserMFP", inversedBy="crts", fetch="EAGER")
     **/
    public $userMFP;

//    /**
//     * @ORM\ManyToOne(targetEntity="Dfp", inversedBy="dpfs", fetch="EAGER")
//     **/
//    public $Dfp;

    /**
     * @ORM\Column(type="integer")
     */
    public $Dfp_id;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="crt", cascade={"all"})
     **/
    public $answers;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $deleted;

    /**
     * @ORM\Column(type="integer")
     */
    public $deleted_by;

    /**
     * @var \DateTime $approved_at
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $approved_at;
    
    /**
     * @var \DateTime $precertify_at
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $precertified_at;

    /**
     * @var \DateTime $decertified_at
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $decertified_at;

    /**
     * @var \DateTime $certify_at
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $certified_at;    
    
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
     * Constructor
     */
    public function __construct()
    {
    	$this->answers = new \Doctrine\Common\Collections\ArrayCollection();
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
    /**
     * Add answer
     *
     * @param Answer $answer
     * @return Crt
     */
    public function addAnswer(Answer $answer)
    {
    	$this->answers[] = $answer;
    	$answer->crt = $this;
    
    	return $this;
    }
    
    /**
     * Remove answer
     *
     * @param Answer $answer
     */
    public function removeAnswer(Answer $answer)
    {
    	$this->answers->removeElement($answer);
    	$answer->crt = null;
    }
    
    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
    	return $this->answers;
    }
    
    public function getMFPId()
    {
    	if(isset($this->userMFP))
    		return $this->userMFP->id;
    	return null;
    }

    
    public function getMFPName()
    {
    	if(isset($this->userMFP))
    		return "{$this->userMFP->name} {$this->userMFP->lastname}";
    	return null;
    }

    public function filterAnswers($surveyId, $surveySubId)
    {
    	$answers = array();
    	foreach($this->answers as $answer)
    	{
    		if($answer->surveyItem->surveySub->survey->id == $surveyId && $answer->surveyItem->surveySub->id == $surveySubId)
    			$answers[] = $answer;
    	}
    	return $answers;
    }
    
    public function sectionInfo($surveyId)
    {
    	$result = array();
    	$result['score'] = 0;
    	$result['sum'] = 0;
    	$result['total'] = 0;    	    	
    	$result['finish'] = $this->answers->count() != 0;
    	$result['approved'] = $this->answers->count() != 0;
    		
    	foreach($this->answers as $answer)
    	{
    		if($answer->surveyItem->surveySub->survey->id == $surveyId)
    		{
    			if($answer->value === null)
    				$result['finish'] = false;
    			else
    				$result['sum'] += $answer->value;
    			
    			$result['total'] += $answer->surveyItem->type == 0 ? 1 : $answer->surveyItem->type;    				
    		}
    	}
    	
    	if($result['total'] != 0)
    		$result['score'] = round(100*$result['sum']/$result['total'],2);
    	if($surveyId != 1)
    		$result['approved'] = $result['score'] >= 85;
    	else
    		$result['approved'] = $result['score'] == 100;
    	
    	return $result;
    }
    
    public function getStatus()
    {
    	if($this->certified_at != null)
    		return 4;
    	else
    	if($this->precertified_at != null)
    		return 3;
    	if($this->approved_at != null)
    		return 2;
    	else
    		return 1;
    }
    
    public function haveAccess()
    {
    	if(isset($this->precertified_at) && date_add(clone $this->precertified_at,new \DateInterval('P90D')) > new \DateTime())
    		return true;
    	if(isset($this->certified_at) && date_add(clone $this->certified_at,new \DateInterval('P1Y')) > new \DateTime())
    		return true;
    	
    	return false;   			    		
    }

}
