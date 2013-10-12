<?php

namespace OAuth\Model;


use OAuth\Exceptions\UserException;

use Doctrine\Common\Collections\Criteria;

use Doctrine\Common\Collections\ArrayCollection;

use OAuth\Lib\ResultStatus;

use OAuth\Auth\Adapter;

use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Authentication\AuthenticationService;
use OAuth\Lib\EntitySerializer;
use OAuth\Entity\User;

class UserModel extends AbstractModel {
	
	/*
	 * @var User\Auth\Adapter
	 * */
	private $_adapter;
	
	/**
	 * 判断用户是否存在
	 * @param string $username
	 * @param string $email
	 * @throws \Exception  
	 * */
	public function userExitCheck($username, $email) {
		$em = $this->getEntityManager ();
		
		$criteria = new Criteria();
		$criteria->where($criteria->expr()->eq('username', $username))
			->orWhere($criteria->expr()->eq('email', $email));
		$exitUser = $em->getRepository('OAuth\Entity\User')->matching($criteria);
		if (!$exitUser->isEmpty()) {
			throw new UserException( '用户已经存在' ); 
		}
	}
	
	/**
	 * 用户注册
	 * @param User $user
	 * @return null  
	 * */
	public function register(User $user) {
		$em = $this->getEntityManager ();
		$this->userExitCheck($user->getUsername(), $user->getEmail());	
		$em->persist($user);
		$em->flush();
		
		return $user;
	}
	
	/**
	 * 用户登录认证
	 * @param array $data
	 *        -$data 必须有indentity，和credential
	 * @return \Zend\Authentication\Result  
	 * */
	public function auth(array $data) {
		$authAdapter = $this->_adapter;
		$authAdapter->setIdentityColumn('username')
				->setIdentity($data['indentity'])
				->setCredentialColumn('password')
				->setCredential($data['credential']);
		
		$auth = new AuthenticationService();
		$auth->setStorage(new SessionStorage(ResultStatus::USER));
		$result = $auth->authenticate($authAdapter);
		return $result;
	}
	
	/**
	 * 通过ID获取用户信息
	 * @param int 用户ID
	 * @return array
	 **/
	public function getUserArrayById($id) {
		$em = $this->getEntityManager ();
		$user = $em->find('OAuth\Entity\User', $id);
		if ($user != null) {
			$entitySer = new EntitySerializer($em);
			$user = $entitySer->toArray($user);
			unset($user['password']);
			unset($user['email']);
		}
		return $user;
	}
	
	/**
	 * 通过ID获取用户信息
	 * @param int 用户ID
	 * @return User
	 **/
	public function getUserObjectById($id) {
		$em = $this->getEntityManager ();
		$user = $em->find('OAuth\Entity\User', $id);
		return $user;
	}
	/**
	 * @return the $dapater
	 */
	public function getAdapter() {
		return $this->_adapter;
	}

	/**
	 * @param field_type $dapater
	 */
	public function setAdapter(Adapter $adapter) {
		$this->_adapter = $adapter;
	}

}

?>