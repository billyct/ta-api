<?php

namespace Gig\Entity;
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
	

	
	
	public function __construct() {
		$this->create_at = time();
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

}

?>