<?php namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Answer{

	/**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $value; 
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $actionRequired;    
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $comments;
    
    /**
     * @ORM\ManyToOne(targetEntity="Crt", inversedBy="answers", fetch="EAGER")
     **/
    public $crt;    
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Models\Survey\SurveyItem")
     **/
    public $surveyItem;

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
     * Set value
     *
     * @param integer $value
     * @return Answer
     */
    public function setValue($value)
    {
    	$this->value = $value;
    
    	return $this;
    }
    
    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
    	return $this->value;
    }
    
    /**
     * Set actionRequired
     *
     * @param boolean $actionRequired
     * @return Answer
     */
    public function setActionRequired($actionRequired)
    {
    	$this->actionRequired = $actionRequired;
    
    	return $this;
    }
    
    /**
     * Get actionRequired
     *
     * @return boolean
     */
    public function getActionRequired()
    {
    	return $this->actionRequired;
    }
    
    /**
     * Set comments
     *
     * @param string $comments
     * @return Answer
     */
    public function setComments($comments)
    {
    	$this->comments = $comments;
    
    	return $this;
    }
    
    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
    	return $this->comments;
    }
    
    /**
     * Set crt
     *
     * @param \App\Models\Crt $crt
     * @return Answer
     */
    public function setCrt(\App\Models\Crt $crt = null)
    {
    	$this->crt = $crt;
    
    	return $this;
    }
    
    /**
     * Get crt
     *
     * @return \App\Models\Crt
     */
    public function getCrt()
    {
    	return $this->crt;
    }
    
    /**
     * Set surveyItem
     *
     * @param \App\Models\Survey\SurveyItem $surveyItem
     * @return Answer
     */
    public function setSurveyItem(\App\Models\Survey\SurveyItem $surveyItem = null)
    {
    	$this->surveyItem = $surveyItem;
    
    	return $this;
    }
    
    /**
     * Get surveyItem
     *
     * @return \App\Models\Survey\SurveyItem
     */
    public function getSurveyItem()
    {
    	return $this->surveyItem;
    }
    
    public function isEditable()
    {
    	if($this->surveyItem->surveySub->survey->id == 7)
    	{
    		if($this->crt->getStatus() == 3)
    			return true;
    		else
    			return false;    		
    	}
    	else
    	{
    		if($this->crt->getStatus() == 1)
    			return true;
    		else
    			return false;
    	}
    }
    
        
}