<?php namespace App\Models\Survey;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Survey{

	/**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $title;

    /**
     * @ORM\Column(type="integer")
     */
    public $color;
    
    /**
     * @ORM\OneToMany(targetEntity="SurveySub", mappedBy="survey", cascade={"all"})
     **/
    public $surveySubs;  
        
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->surveySubs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add surveySub
     *
     * @param \App\Models\Survey\SurveySub $surveySub
     * @return Survey
     */
    public function addSurveySub(\App\Models\Survey\SurveySub $surveySub)
    {
    	$this->surveySubs[] = $surveySub;
    	$surveySub->survey = $this;
    
    	return $this;
    }
    
    /**
     * Remove surveySub
     *
     * @param \App\Models\Survey\SurveySub $surveySub
     */
    public function removeSurveySub(\App\Models\Survey\SurveySub $surveySub)
    {
    	$this->surveySubs->removeElement($surveySub);
    	$surveySub->survey = null;
    }
    
    /**
     * Get surveySubs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurveySubs()
    {
    	return $this->surveySubs;
    }
    
}