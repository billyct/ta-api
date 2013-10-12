<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="timeline_comment")
 **/
class Comment extends AbstractUser{
	
	/**
	 * @ORM\Column(type="text")
	 **/
	protected $content;
	
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $timeline_id;
	
	/**
	 * @ORM\OneToOne(targetEntity="Application\Entity\Image",  cascade={"persist"}, fetch="EAGER")
	 * @ORM\JoinTable(name="comment_image",
	 * 				joinColumns={@ORM\JoinColumn(name="comment_id", referencedColumnName="id")},
	 * 				inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")}
	 * )
	 */
	protected $image;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\TimeLine", inversedBy="comments")
	 * @ORM\JoinColumn(name="timeline_id", referencedColumnName="id")
	 **/
	protected $timeline;
	
	
	

	/**
	 * @return the $content
	 */
	public function getContent() {
		return $this->content;
	}


	/**
	 * @return the $timeline
	 */
	public function getTimeline() {
		return $this->timeline;
	}


	/**
	 * @param field_type $content
	 */
	public function setContent($content) {
		$this->content = $content;
		return $this;
	}

	/**
	 * @param field_type $create_at
	 */
	public function setCreate_at($create_at) {
		$this->create_at = $create_at;
		return $this;
	}

	/**
	 * @param field_type $timeline
	 */
	public function setTimeline($timeline) {
		$this->timeline = $timeline;
		return $this;
	}

	/**
	 * @return the $timeline_id
	 */
	public function getTimeline_id() {
		return $this->timeline_id;
	}

	/**
	 * @return the $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @param field_type $timeline_id
	 */
	public function setTimeline_id($timeline_id) {
		$this->timeline_id = $timeline_id;
		return $this;
	}

	/**
	 * @param field_type $image
	 */
	public function setImage($image) {
		$this->image = $image;
		return $this;
	}


	
	
	
}

?>