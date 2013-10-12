<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="image")
 **/
class Image extends AbstractUser{
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $path;
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $path_thumb;
	

	/**
	 * @return the $path
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @return the $path_thumb
	 */
	public function getPath_thumb() {
		return $this->path_thumb;
	}


	/**
	 * @param field_type $path
	 */
	public function setPath($path) {
		$this->path = $path;
		return $this;
	}

	/**
	 * @param field_type $path_thumb
	 */
	public function setPath_thumb($path_thumb) {
		$this->path_thumb = $path_thumb;
		return $this;
	}

}

?>