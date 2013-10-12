<?php

namespace OAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sina_oauth")
 **/
class SinaOAuth extends AbstractBase {
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $uid;
	
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $screen_name;
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $name;
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $url;
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $profile_image_url;
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $access_token;
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $remind_in;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $expires_in;
	
	/**
	 * @ORM\OneToOne(targetEntity="OAuth\Entity\User",  inversedBy="sina_oauth", cascade={"persist"}, fetch="EAGER")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;
	/**
	 * @return the $uid
	 */
	public function getUid() {
		return $this->uid;
	}

	/**
	 * @return the $screen_name
	 */
	public function getScreen_name() {
		return $this->screen_name;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @return the $profile_image_url
	 */
	public function getProfile_image_url() {
		return $this->profile_image_url;
	}

	/**
	 * @return the $access_token
	 */
	public function getAccess_token() {
		return $this->access_token;
	}

	/**
	 * @return the $user
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param field_type $uid
	 */
	public function setUid($uid) {
		$this->uid = $uid;
		return $this;
	}

	/**
	 * @param field_type $screen_name
	 */
	public function setScreen_name($screen_name) {
		$this->screen_name = $screen_name;
		return $this;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @param field_type $url
	 */
	public function setUrl($url) {
		$this->url = $url;
		return $this;
	}

	/**
	 * @param field_type $profile_image_url
	 */
	public function setProfile_image_url($profile_image_url) {
		$this->profile_image_url = $profile_image_url;
		return $this;
	}

	/**
	 * @param field_type $access_token
	 */
	public function setAccess_token($access_token) {
		$this->access_token = $access_token;
		return $this;
	}

	/**
	 * @param field_type $user
	 */
	public function setUser($user) {
		$this->user = $user;
		return $this;
	}
	/**
	 * @return the $remind_in
	 */
	public function getRemind_in() {
		return $this->remind_in;
	}

	/**
	 * @return the $expires_in
	 */
	public function getExpires_in() {
		return $this->expires_in;
	}

	/**
	 * @param field_type $remind_in
	 */
	public function setRemind_in($remind_in) {
		$this->remind_in = $remind_in;
		return $this;
	}

	/**
	 * @param field_type $expires_in
	 */
	public function setExpires_in($expires_in) {
		$this->expires_in = $expires_in;
		return $this;
	}


	
	
	
	
}

?>