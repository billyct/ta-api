<?php

namespace OAuth\Entity;


use Zend\Crypt\Password\Bcrypt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 *
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 * @author billyct
 */
class User {
	/**
	 *
	 * @var int @ORM\Id
	 *      @ORM\Column(type="integer")
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 *
	 * @var string @ORM\Column(type="string", length=255, unique=true,
	 *      nullable=true)
	 */
	protected $username;
	
	/**
	 *
	 * @var string @ORM\Column(type="string", unique=true, length=255)
	 */
	protected $email;
	
	/**
	 *
	 * @var string @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $displayName;
	
	/**
	 *
	 * @var string @ORM\Column(type="string", length=128)
	 */
	protected $password;
	
	
	/**
	 * @ORM\OneToOne(targetEntity="OAuth\Entity\SinaOAuth", mappedBy="user")
	 **/
	protected $sina_oauth;
	
	/**
	 * @ORM\OneToMany(targetEntity="OAuth\Entity\OAuthSession", mappedBy="user")
	 **/
	protected $oauth_sessions;
	
	/**
	 * @ORM\OneToOne(targetEntity="Application\Entity\Image")
	 * @ORM\JoinColumn(name="image_avatar_id", referencedColumnName="id")
	 **/
	protected $avatar;
	
	
	/**
	 * Initialies the roles variable.
	 */
// 	public function __construct() {
// 		$this->roles = new ArrayCollection ();
// 	}
	
	/**
	 * Get id.
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Get username.
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * Set username.
	 *
	 * @param string $username        	
	 *
	 * @return void
	 */
	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}
	
	/**
	 * Get email.
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * Set email.
	 *
	 * @param string $email        	
	 *
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}
	
	/**
	 * Get displayName.
	 *
	 * @return string
	 */
	public function getDisplayName() {
		return $this->displayName;
	}
	
	/**
	 * Set displayName.
	 *
	 * @param string $displayName        	
	 *
	 * @return void
	 */
	public function setDisplayName($displayName) {
		$this->displayName = $displayName;
		return $this;
	}
	
	/**
	 * Get password.
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * Set password.
	 *
	 * @param string $password        	
	 *
	 * @return void
	 */
	public function setPassword($password) {
		$bcrypt = new Bcrypt ();
		$this->password = $bcrypt->create ( $password );
		return $this;
	}
	
	/**
	 * Get state.
	 *
	 * @return int
	 */
	public function getState() {
		return $this->state;
	}
	
	/**
	 * Set state.
	 *
	 * @param int $state        	
	 *
	 * @return void
	 */
	public function setState($state) {
		$this->state = $state;
		return $this;
	}
	
// 	/**
// 	 * Get role.
// 	 *
// 	 * @return array
// 	 */
// 	public function getRoles() {
// 		return $this->roles->getValues ();
// 	}
	
// 	/**
// 	 * Add a role to the user.
// 	 *
// 	 * @param Role $role        	
// 	 *
// 	 * @return void
// 	 */
// 	public function addRole($role) {
// 		$this->roles [] = $role;
// 	}

	/**
	 * @return the $sina_oauth
	 */
	public function getSina_oauth() {
		return $this->sina_oauth;
	}

	/**
	 * @param field_type $sina_oauth
	 */
	public function setSina_oauth($sina_oauth) {
		$this->sina_oauth = $sina_oauth;
		return $this;
	}
	
	
	

}
