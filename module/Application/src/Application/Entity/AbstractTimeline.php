<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as Orm;

/**
 * @Orm\MappedSuperclass
 */
class AbstractTimeline extends AbstractBase{
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $user_id;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $timeline_id;
	
	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Timeline",  cascade={"persist"}, fetch="EAGER")
	 * @ORM\JoinColumn(name="timeline_id", referencedColumnName="id")
	 */
	protected $timeline;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity="OAuth\Entity\User",  cascade={"persist"}, fetch="EAGER")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;
	
	
	/**
	 * @return the $user_id
	 */
	public function getUser_id() {
		return $this->user_id;
	}

	/**
	 * @return the $timeline_id
	 */
	public function getTimeline_id() {
		return $this->timeline_id;
	}

	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return the $timeline
	 */
	public function getTimeline() {
		return $this->timeline;
	}

	/**
	 * @return the $user
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param field_type $user_id
	 */
	public function setUser_id($user_id) {
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @param field_type $timeline_id
	 */
	public function setTimeline_id($timeline_id) {
		$this->timeline_id = $timeline_id;
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
	 * @param field_type $timeline
	 */
	public function setTimeline($timeline) {
		$this->timeline = $timeline;
		return $this;
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