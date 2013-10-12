<?php

namespace Gig\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gig_comment")
 **/
class Comment extends AbstractUser{
	/**
	 * @ORM\Column(type="text")
	 **/
	protected $content;
	
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $gig_id;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Gig\Entity\Gig", inversedBy="comments")
	 * @ORM\JoinColumn(name="gig_id", referencedColumnName="id")
	 **/
	protected $gig;
}

?>