<?php

namespace Gig\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="video")
 **/
class Video extends AbstractUser{
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $video_id;
	
}

?>