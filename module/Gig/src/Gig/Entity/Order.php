<?php

namespace Gig\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gig_order")
 **/
class Order extends AbstractUser{
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $sum;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $gig_id;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $total;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Gig\Entity\Gig", inversedBy="orders", cascade={"persist"}, fetch="EAGER")
	 * @ORM\JoinColumn(name="gig_id", referencedColumnName="id")
	 */
	protected $gig;
	
	
	/**
	 * @ORM\ManyToMany(targetEntity="Gig\Entity\Extra")
	 * @ORM\JoinTable(name="gig_order_extras",
	 *      joinColumns={@ORM\JoinColumn(name="order_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="extra_id", referencedColumnName="id")}
	 * )
	 */
	protected $extras;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $status;
	
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 **/
	protected $owner_id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="OAuth\Entity\User",  cascade={"persist"}, fetch="EAGER")
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
	 */
	protected $owner;
	
	public function __construct() {
		parent::__construct();
		$this->extras = new ArrayCollection();
		$this->status = 0;
	}
	/**
	 * @return the $gig
	 */
	public function getGig() {
		return $this->gig;
	}

	/**
	 * @return the $sum
	 */
	public function getSum() {
		return $this->sum;
	}

	/**
	 * @return the $extras
	 */
	public function getExtras() {
		return $this->extras;
	}

	/**
	 * @param field_type $gig
	 */
	public function setGig($gig) {
		$this->gig = $gig;
		return $this;
	}

	/**
	 * @param field_type $sum
	 */
	public function setSum($sum) {
		$this->sum = $sum;
		return $this;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection $extras
	 */
	public function setExtras($extras) {
		$this->extras = $extras;
		return $this;
	}
	/**
	 * @return the $gig_id
	 */
	public function getGig_id() {
		return $this->gig_id;
	}

	/**
	 * @param field_type $gig_id
	 */
	public function setGig_id($gig_id) {
		$this->gig_id = $gig_id;
		return $this;
	}
	/**
	 * @return the $total
	 */
	public function getTotal() {
		return $this->total;
	}

	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param field_type $total
	 */
	public function setTotal($total) {
		$this->total = $total;
		return $this;
	}

	/**
	 * @param number $status
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}
	/**
	 * @return the $owner_id
	 */
	public function getOwner_id() {
		return $this->owner_id;
	}

	/**
	 * @return the $owner
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @param field_type $owner_id
	 */
	public function setOwner_id($owner_id) {
		$this->owner_id = $owner_id;
		return $this;
	}

	/**
	 * @param field_type $owner
	 */
	public function setOwner($owner) {
		$this->owner = $owner;
		return $this;
	}




	
}

?>