<?php namespace App\Models\Survey;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class SurveyItem{

	/**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $matter; 
    
    /**
     * @ORM\Column(type="integer")
     */
    public $type;
    
    /**
     * @ORM\Column(type="boolean")
     */
    public $deleted = false;
    
    /**
     * @ORM\ManyToOne(targetEntity="SurveySub", inversedBy="surveyItems", fetch="EAGER")
     **/
    public $surveySub;

    /**
     * @ORM\Column(type="integer")
     */
    public $autoKill;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
    	return $this->id;
    }    
        
}