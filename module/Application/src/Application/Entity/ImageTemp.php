<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="image_temp")
 **/
class ImageTemp extends AbstractUser{
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $path;
	

	/**
	 * @return the $path
	 */
	public function getPath() {
		return $this->path;
	}


	/**
	 * @param field_type $path
	 */
	public function setPath($path) {
		$this->path = $path;
		return $this;
	}

	
	
}

?>