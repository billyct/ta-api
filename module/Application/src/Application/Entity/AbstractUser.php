<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as Orm;

/**
 * @Orm\MappedSuperclass
 */
class AbstractUser extends AbstractBase {
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $user_id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="OAuth\Entity\User",  cascade={"persist"}, fetch="EAGER")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;
	
	
	/**
	 * @return the $user
	 */
	public function getUser() {
		return $this->user;
	}
	
	/**
	 * @param field_type $user
	 */
	public function setUser($user) {
		$this->user = $user;
		return $this;
	}
}

?>