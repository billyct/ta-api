<?php

namespace OAuth\Entity;
use Doctrine\ORM\Mapping as Orm;

/**
 * @Orm\MappedSuperclass
 */
class AbstractBase {
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $create_at;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $status;
	
	
	public function __construct() {
		$this->create_at = time();
		$this->status = 0;
	}
	
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return the $create_at
	 */
	public function getCreate_at() {
		return $this->create_at;
	}
	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param number $status
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}

	
}

?>