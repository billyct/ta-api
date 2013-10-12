<?php

namespace OAuth\Controller;


use OAuth\Entity\User;

use Zend\Session\Container;

use OAuth\Lib\ResultStatus;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use OAuth\Lib\EntitySerializer;

use Application\Controller\AbstractServerController;

use User\Model\UserModel;

use Zend\View\Model\JsonModel;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\EventManager\EventManagerInterface;

class UserController extends AbstractServerController {
	
	public function indexAction() {
		echo "xxx";
		return false;
	}
	
	public function signinAction() {
	
		$resultStatus = $this->getServiceLocator()->get('ResultStatus');
		$request = $this->getRequest();
		if ($request->isPost()) {
				
			$userModel = $this->getServiceLocator()->get('UserModel');
			$userData = array(
					'indentity' => $request->getPost('username'),
					'credential' => $request->getPost('password')
			);
				
			$result = $userModel->auth($userData);
				
			//$url = $request->getPost('url');
				
			$oauth_session = new Container(ResultStatus::OAUTH);
			$url = $oauth_session->request_uri;
				
			return $this->redirect()->toUrl($url);
			$resultStatus->setCM($result->getCode(), $result->getMessages());
		} else {
			$resultStatus->setCM($resultStatus::FAILED, 'request error');
		}
		return new JsonModel($resultStatus->getCM());
	}
	
	public function signupAction() {
		$resultStatus = $this->getServiceLocator()->get('ResultStatus');
		try {
			$request = $this->getRequest();
			if ($request->isPost()) {
				$userModel = $this->getServiceLocator()->get('UserModel');
				$user = new User();
				$username = $request->getPost('username');
				$password = $request->getPost('password');
				$email = $request->getPost('email');
	
				$user->setUsername($username)
				->setEmail($email)
				->setPassword($password);
				//注册
				$userModel->register($user);
	
				$userData = array(
						'indentity' => $username,
						'credential' => $password
				);
				//登录
				$userModel->auth($userData);
	
				$oauth_session = new Container(ResultStatus::OAUTH);
				$url = $oauth_session->request_uri;
				return $this->redirect()->toUrl($url);
	
				$resultStatus->setCM($resultStatus::SUCCESS, '用户注册成功');
			}
		} catch (\Exception $e) {
			$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
		}
	
		return new JsonModel($resultStatus->getCM());
	
	}
	
	public function signoutAction() {
		$auth = new AuthenticationService();
		$auth->setStorage(new SessionStorage(ResultStatus::USER));
		$auth->clearIdentity();
		$this->redirect()->toRoute('user-signin');
	}
	
	
	public function endSessionAction() {
		$resultStatus = $this->resultStatus;
		try {
			$sessionModel = $this->getServiceLocator()->get('OAuth\Model\SessionModel');
			$sessionModel->endSession($this->server->getAccessToken());
			
			$resultStatus->setCM($resultStatus::SUCCESS, '退出成功');
		} catch (\Exception $e ) {
			$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function meAction() {
		$resultStatus = $this->resultStatus;
		$server = $this->server;
		$userModel = $this->getServiceLocator()->get('UserModel');
		if ($server->getOwnerType() == 'user') {
			$uid = $server->getOwnerId();
			$user = $userModel->getUserArrayById($uid);
			if ($user == null) {
				$this->endSessionAction();
				$resultStatus->setCM($resultStatus::FAILED, '找不到用户');
			}else {
				return new JsonModel($user);
			}
		} else {
			$resultStatus->setCM($resultStatus::FAILED, 'Only access tokens representing users can use this endpoint');
		}
		
		return new JsonModel($resultStatus->getCM());
		
	}
	
	public function uploadAvatarAction() {
		$resultStatus = $this->resultStatus;
		$server = $this->server;
		$request = $this->getRequest();
		$userModel = $this->getServiceLocator()->get('UserModel');
		if ($server->getOwnerType() == 'user') {
			$user_id = $request->getQuery('user_id');
			
			
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function showAction() {
		$resultStatus = $this->resultStatus;
		$server = $this->server;
		$request = $this->getRequest();
		$userModel = $this->getServiceLocator()->get('UserModel');
		if ($server->getOwnerType() == 'user') {
			$user_id = $request->getQuery('user_id');
			$user = $userModel->getUserArrayById($user_id);
			if ($user) {
				return new JsonModel($user);
			}
		}
		return new JsonModel($resultStatus->getCM());
	}
}

?>