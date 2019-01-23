<?php namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 */
class UserMFP extends User{

    /**
     * @ORM\OneToMany(targetEntity="Crt", mappedBy="userMFP", cascade={"all"})
     **/
    public $crts;
    
    /**
     * Add crt
     *
     * @param \App\Models\Crt $crt
     * @return UserMFP
     */
    public function addCrt(\App\Models\Crt $crt)
    {
    	$this->crts[] = $crt;
    	$crt->userMFP = $this;
    
    	return $this;
    }
    
    /**
     * Remove crt
     *
     * @param \App\Models\Crt $crt
     */
    public function removeCrt(\App\Models\Crt $crt)
    {
    	$this->crts->removeElement($crt);
    	$crt->approved_at = null;
    	$crt->userMFP = null;
    }
    
    /**
     * Get crts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCrts()
    {
    	$criteria = Criteria::create()->where(Criteria::expr()->eq("deleted", null));
    	return $this->crts->matching($criteria);
    }    
    
}