<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="timeline")
 **/
class TimeLine extends AbstractUser{
	
	/**
	 * @ORM\Column(type="text")
	 **/
	protected $content;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $deadline;
	
	
	/**
	 * @ORM\ManyToMany(targetEntity="Application\Entity\Image")
	 * @ORM\JoinTable(name="timeline_image",
	 *      joinColumns={@ORM\JoinColumn(name="timeline_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")}
	 * )
	 **/
	protected $images;
	
	/**
	 * @ORM\OneToMany(targetEntity="Application\Entity\Comment", mappedBy="timeline", cascade={"persist"}, fetch="EAGER")
	 **/
	protected $comments;
	
	
	/**
	 * @ORM\ManyToMany(targetEntity="OAuth\Entity\User")
	 * @ORM\JoinTable(name="timeline_favorited",
	 *      joinColumns={@ORM\JoinColumn(name="timeline_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
	 * )
	 **/
	protected $favorited_users;
	
	/**
	 * @ORM\ManyToMany(targetEntity="OAuth\Entity\User")
	 * @ORM\JoinTable(name="timeline_joined",
	 *      joinColumns={@ORM\JoinColumn(name="timeline_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
	 * )
	 **/
	protected $joined_users;
	
	
	
	
	
	
	
	public function __construct() {
		parent::__construct();
		$this->images = new ArrayCollection();
		$this->comments = new ArrayCollection();
		$this->favorited_users = new ArrayCollection();
		$this->joined_users = new ArrayCollection();
	}
	/**
	 * @return the $content
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @return the $deadline
	 */
	public function getDeadline() {
		return $this->deadline;
	}

	/**
	 * @return the $images
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * @return the $comments
	 */
	public function getComments() {
		return $this->comments;
	}

	

	/**
	 * @param field_type $content
	 */
	public function setContent($content) {
		$this->content = $content;
		return $this;
	}

	/**
	 * @param field_type $deadline
	 */
	public function setDeadline($deadline) {
		$this->deadline = $deadline;
		return $this;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection $images
	 */
	public function setImages($images) {
		$this->images = $images;
		return $this;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection $comments
	 */
	public function setComments($comments) {
		$this->comments = $comments;
		return $this;
	}
	/**
	 * @return the $favorited_users
	 */
	public function getFavorited_users() {
		return $this->favorited_users;
	}

	/**
	 * @return the $joined_users
	 */
	public function getJoined_users() {
		return $this->joined_users;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection $favorited_users
	 */
	public function setFavorited_users($favorited_users) {
		$this->favorited_users = $favorited_users;
		return $this;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection $joined_users
	 */
	public function setJoined_users($joined_users) {
		$this->joined_users = $joined_users;
		return $this;
	}


	

	
	
	


	
	
	
}

?>