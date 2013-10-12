<?php

namespace OAuth\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_client")
 **/

class OAuthClient {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=40)
	 **/
	protected $client_id;
	
	/**
	 * @ORM\Column(type="string", length=40)
	 **/
	protected $secret;
	
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $name;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 **/
	protected $redirect_url;
	
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $auto_approve;
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
	 * @return the $secret
	 */
	public function getSecret() {
		return $this->secret;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $redirect_url
	 */
	public function getRedirect_url() {
		return $this->redirect_url;
	}

	/**
	 * @return the $auto_approve
	 */
	public function getAuto_approve() {
		return $this->auto_approve;
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
	 * @param field_type $secret
	 */
	public function setSecret($secret) {
		$this->secret = $secret;
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
	 * @param field_type $redirect_url
	 */
	public function setRedirect_url($redirect_url) {
		$this->redirect_url = $redirect_url;
		return $this;
	}

	/**
	 * @param field_type $auto_approve
	 */
	public function setAuto_approve($auto_approve) {
		$this->auto_approve = $auto_approve;
		return $this;
	}



}

?>