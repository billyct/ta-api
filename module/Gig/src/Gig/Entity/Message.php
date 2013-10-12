<?php

namespace Gig\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="message")
 **/
class Message extends AbstractUser{
	
	/**
	 * @ORM\Column(type="text")
	 **/
	protected $msg;
	
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $to_user_id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="OAuth\Entity\User",  cascade={"persist"}, fetch="EAGER")
	 * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id")
	 */
	protected $to;
	/**
	 * @return the $msg
	 */
	public function getMsg() {
		return $this->msg;
	}

	/**
	 * @return the $to
	 */
	public function getTo() {
		return $this->to;
	}

	/**
	 * @param field_type $msg
	 */
	public function setMsg($msg) {
		$this->msg = $msg;
		return $this;
	}

	/**
	 * @param field_type $to
	 */
	public function setTo($to) {
		$this->to = $to;
		return $this;
	}

	
	
}

?>