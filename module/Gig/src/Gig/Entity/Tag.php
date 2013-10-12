<?php

namespace Gig\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gig_tag")
 **/
class Tag extends AbstractBase {
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $name;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Gig\Entity\Gig", mappedBy="tags")
	 **/
	protected $gigs;
	
	public function __construct() {
		parent::__construct();
		$this->gigs = new ArrayCollection();
	}
	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $gigs
	 */
	public function getGigs() {
		return $this->gigs;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection $gigs
	 */
	public function setGigs($gigs) {
		$this->gigs = $gigs;
	}

	
	
}

?>