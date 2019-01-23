<?php namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="dfp")
 * @ORM\HasLifecycleCallbacks()
 */
class Dfp
{
//    /**
//     * @ORM\OneToMany(targetEntity="Crt", mappedBy="Dfp", cascade={"all"})
//     **/
//    public $dfps;

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