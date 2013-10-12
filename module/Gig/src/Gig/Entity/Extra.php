<?php

namespace Gig\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="gig_extra")
 **/
class Extra extends AbstractUser{
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $title;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $price;
	
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 **/
	protected $extra_time;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Gig\Entity\Gig", inversedBy="comments")
	 * @ORM\JoinColumn(name="gig_id", referencedColumnName="id")
	 **/
	protected $gig;
	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return the $price
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @return the $extra_time
	 */
	public function getExtra_time() {
		return $this->extra_time;
	}

	/**
	 * @return the $gig
	 */
	public function getGig() {
		return $this->gig;
	}

	/**
	 * @param field_type $title
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @param field_type $price
	 */
	public function setPrice($price) {
		$this->price = $price;
		return $this;
	}

	/**
	 * @param field_type $extra_time
	 */
	public function setExtra_time($extra_time) {
		$this->extra_time = $extra_time;
		return $this;
	}

	/**
	 * @param field_type $gig
	 */
	public function setGig($gig) {
		$this->gig = $gig;
		return $this;
	}

	
	
	
	
}

?>