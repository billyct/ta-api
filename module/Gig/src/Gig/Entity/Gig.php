<?php

namespace Gig\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="gig")
 **/
class Gig extends AbstractUser{
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $title;
	
	
	/**
	 * @ORM\Column(type="text")
	 **/
	protected $description;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 **/
	protected $instructions;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $day_to_complete;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $price;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $status;
	
	
	/**
	 * @ORM\ManyToMany(targetEntity="Gig\Entity\Tag", inversedBy="gigs")
	 * @ORM\JoinTable(name="gig_tags",
	 *      joinColumns={@ORM\JoinColumn(name="gig_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
	 * )
	 **/
	protected $tags;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Application\Entity\Image", cascade={"persist"})
	 * @ORM\JoinTable(name="gig_image",
	 *      joinColumns={@ORM\JoinColumn(name="gig_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")}
	 * )
	 **/
	protected $images;
	
	
	/**
	 * @ORM\ManyToMany(targetEntity="Gig\Entity\Video")
	 * @ORM\JoinTable(name="gig_videos",
	 *      joinColumns={@ORM\JoinColumn(name="gig_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="video_id", referencedColumnName="id", unique=true)}
	 * )
	 **/
	protected $videos;//但是一对多
	
	
	
	
	/**
	 * @ORM\OneToMany(targetEntity="Gig\Entity\Extra", mappedBy="gig")
	 **/
	protected $extras;
	
	
	/**
	 * @ORM\ManyToMany(targetEntity="OAuth\Entity\User")
	 * @ORM\JoinTable(name="gig_favorited",
	 *      joinColumns={@ORM\JoinColumn(name="gig_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
	 * )
	 **/
	protected $favorited_users;
	
	/**
	 * @ORM\OneToMany(targetEntity="Gig\Entity\Order", mappedBy="gig")
	 **/
	protected $orders;
	
	/**
	 * @ORM\OneToMany(targetEntity="Gig\Entity\Comment", mappedBy="gig")
	 **/
	protected $comments;
	
	
	
	
	public function __construct() {
		parent::__construct();
		$this->extras = new ArrayCollection();
		$this->images = new ArrayCollection();
		$this->tags = new ArrayCollection();
		$this->favorited_users = new ArrayCollection();
		$this->orders = new ArrayCollection();
		$this->videos = new ArrayCollection();
		$this->status = 1;
	}
	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return the $instructions
	 */
	public function getInstructions() {
		return $this->instructions;
	}

	/**
	 * @return the $tags
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * @return the $images
	 */
	public function getImages() {
		return $this->images;
	}


	/**
	 * @return the $price
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @return the $extras
	 */
	public function getExtras() {
		return $this->extras;
	}

	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param field_type $title
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @param field_type $description
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @param field_type $instructions
	 */
	public function setInstructions($instructions) {
		$this->instructions = $instructions;
		return $this;
	}

	/**
	 * @param field_type $tags
	 */
	public function setTags($tags) {
		$this->tags = $tags;
		return $this;
	}

	/**
	 * @param field_type $images
	 */
	public function setImages($images) {
		$this->images = $images;
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
	 * @param \Doctrine\Common\Collections\ArrayCollection $extras
	 */
	public function setExtras($extras) {
		$this->extras = $extras;
		return $this;
	}

	/**
	 * @param field_type $status
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}
	/**
	 * @return the $favorited_users
	 */
	public function getFavorited_users() {
		return $this->favorited_users;
	}

	/**
	 * @param field_type $favorited_users
	 */
	public function setFavorited_users($favorited_users) {
		$this->favorited_users = $favorited_users;
	}
	/**
	 * @return the $orders
	 */
	public function getOrders() {
		return $this->orders;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection $orders
	 */
	public function setOrders($orders) {
		$this->orders = $orders;
	}
	/**
	 * @return the $day_to_complete
	 */
	public function getDay_to_complete() {
		return $this->day_to_complete;
	}

	/**
	 * @param field_type $day_to_complete
	 */
	public function setDay_to_complete($day_to_complete) {
		$this->day_to_complete = $day_to_complete;
		return $this;
	}




	
	
	
}

?>