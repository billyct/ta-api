<?php

namespace OAuth\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_session")
 */
class OAuthSession {
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=40)
	 */
	protected $client_id;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $redirect_url;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $owner_type;
	
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $owner_id;
	
	
	
	/**
	 * @ORM\Column(type="string", length=40, nullable=true)
	 */
	protected $auth_code;
	
	/**
	 * @ORM\Column(type="string", length=40, nullable=true)
	 */
	protected $access_token;
	
	/**
	 * @ORM\Column(type="string", length=40, nullable=true)
	 */
	protected $refresh_token;
	
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $access_token_expires;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $stage;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $first_requested;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $last_update;
	
	/**
	 * @ORM\ManyToMany(targetEntity="OAuthScope")
	 * @ORM\JoinTable(name="oauth_session_scope",
	 * 		joinColumns={@ORM\JoinColumn(name="session_id", referencedColumnName="id")},
	 * 		inverseJoinColumns={@ORM\JoinColumn(name="scope_id",referencedColumnName="id")}
	 * 	)
	 */
	protected $scopes;
	
	/**
	 * @ORM\ManyToOne(targetEntity="OAuth\Entity\User", inversedBy="oauth_sessions", cascade={"persist"}, fetch="EAGER")
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
	 */
	protected $user;
	
	public function __construct() {
		$this->scopes = new ArrayCollection();
	}
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $client_id
	 */
	public function getClient_id() {
		return $this->client_id;
	}

	/**
	 * @return the $redirect_url
	 */
	public function getRedirect_url() {
		return $this->redirect_url;
	}

	/**
	 * @return the $owner_type
	 */
	public function getOwner_type() {
		return $this->owner_type;
	}

	/**
	 * @return the $owner_id
	 */
	public function getOwner_id() {
		return $this->owner_id;
	}

	/**
	 * @return the $auth_code
	 */
	public function getAuth_code() {
		return $this->auth_code;
	}

	/**
	 * @return the $access_token
	 */
	public function getAccess_token() {
		return $this->access_token;
	}

	/**
	 * @return the $refresh_token
	 */
	public function getRefresh_token() {
		return $this->refresh_token;
	}

	/**
	 * @return the $access_token_expires
	 */
	public function getAccess_token_expires() {
		return $this->access_token_expires;
	}

	/**
	 * @return the $stage
	 */
	public function getStage() {
		return $this->stage;
	}

	/**
	 * @return the $first_requested
	 */
	public function getFirst_requested() {
		return $this->first_requested;
	}

	/**
	 * @return the $last_update
	 */
	public function getLast_update() {
		return $this->last_update;
	}

	/**
	 * @return the $scopes
	 */
	public function getScopes() {
		return $this->scopes;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @param field_type $client_id
	 */
	public function setClient_id($client_id) {
		$this->client_id = $client_id;
		return $this;
	}

	/**
	 * @param field_type $redirect_url
	 */
	public function setRedirect_url($redirect_url) {
		$this->redirect_url = $redirect_url;
		return $this;
	}

	/**
	 * @param field_type $owner_type
	 */
	public function setOwner_type($owner_type) {
		$this->owner_type = $owner_type;
		return $this;
	}

	/**
	 * @param field_type $owner_id
	 */
	public function setOwner_id($owner_id) {
		$this->owner_id = $owner_id;
		return $this;
	}

	/**
	 * @param field_type $auth_code
	 */
	public function setAuth_code($auth_code) {
		$this->auth_code = $auth_code;
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
	 * @param field_type $refresh_token
	 */
	public function setRefresh_token($refresh_token) {
		$this->refresh_token = $refresh_token;
		return $this;
	}

	/**
	 * @param field_type $access_token_expires
	 */
	public function setAccess_token_expires($access_token_expires) {
		$this->access_token_expires = $access_token_expires;
		return $this;
	}

	/**
	 * @param field_type $stage
	 */
	public function setStage($stage) {
		$this->stage = $stage;
		return $this;
	}

	/**
	 * @param field_type $first_requested
	 */
	public function setFirst_requested($first_requested) {
		$this->first_requested = $first_requested;
		return $this;
	}

	/**
	 * @param field_type $last_update
	 */
	public function setLast_update($last_update) {
		$this->last_update = $last_update;
		return $this;
	}

	/**
	 * @param field_type $scopes
	 */
	public function setScopes($scopes) {
		$this->scopes = $scopes;
		return $this;
	}
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