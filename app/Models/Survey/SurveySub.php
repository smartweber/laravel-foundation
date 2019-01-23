<?php namespace App\Models\Survey;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class SurveySub{

	/**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $subtitle; 
    
    /**
     * @ORM\ManyToOne(targetEntity="Survey", inversedBy="surveySubs", fetch="EAGER")
     **/
    public $survey;

    /**
     * @ORM\OneToMany(targetEntity="SurveyItem", mappedBy="surveySub", cascade={"all"})
     **/
    public $surveyItems;    

    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->surveyItems = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add surveyItem
     *
     * @param \App\Models\Survey\SurveyItem $surveyItem
     * @return SurveySub
     */
    public function addSurveyItem(\App\Models\Survey\SurveyItem $surveyItem)
    {
    	$this->surveyItems[] = $surveyItem;
    	$surveyItem->surveySub = $this;
    
    	return $this;
    }
    
    /**
     * Remove surveyItem
     *
     * @param \App\Models\Survey\SurveyItem $surveyItem
     */
    public function removeSurveyItem(\App\Models\Survey\SurveyItem $surveyItem)
    {
    	$this->surveyItems->removeElement($surveyItem);
    	$surveyItem->surveySub = null;
    }
    
    /**
     * Get surveyItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurveyItems()
    {
    	return $this->surveyItems;
    }
    
}