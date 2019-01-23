<?php namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="restaurants")
 */
class Restaurants
{

	/**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $rest_id;

    /**
     * @ORM\Column(type="integer")
     */
    public $rest_name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ORM\Column(type="integer")
     */
    public $deleted_by;
    
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