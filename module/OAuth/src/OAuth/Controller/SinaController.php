<?php

namespace OAuth\Controller;


use OAuth\Exceptions\UserException;

use OAuth\Entity\User;

use Zend\View\Model\ViewModel;

use Zend\View\Model\JsonModel;

use Zend\Session\Container;

use OAuth\Lib\ResultStatus;

use OAuth\Sina\SaeTClientV2;

use OAuth\Entity\SinaOAuth;

use OAuth\Sina\OAuthException;

use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Authentication\AuthenticationService;

class SinaController extends AbstractServerController {
	
	public function indexAction() {
		
	}
	
	public function callbackAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		$code = $request->getQuery('code');
		
		$keys = array();
		
		$keys['code'] = $code;
		$keys['redirect_uri'] = WB_CALLBACK_URL;
		try {
			$o = $this->getServiceLocator()->get('SinaOAuth');
			//获取新浪api的access_token
			$token = $o->getAccessToken('code', $keys);
		} catch (OAuthException $e) {
			$resultStatus->setCM($resultStatus::FAILED, "授权失败");
		}
		
		if ($token) {
			$sina_oauth_model = $this->getServiceLocator()->get('SinaOAuthModel');
			$sina_user = $sina_oauth_model->find($token['uid']);
			if ($sina_user != null ) {
				//更新本地数据库的新浪认证信息
				$sina_oauth_model->updateToken($token);
				if ($sina_user->getUser() != null) {
					$user = $sina_user->getUser();
					$user->setPassword(null);
					
					$auth = new AuthenticationService();
					$auth->setStorage(new SessionStorage(ResultStatus::USER));
					$auth->getStorage()->write($user);
					
					$oauth_session = new Container(ResultStatus::OAUTH);
					return $this->redirect()->toUrl($oauth_session->request_uri);
				}
			} else {
				//插入本地数据库新浪的认证信息
				$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $token['access_token']);
				$user_message = $c->show_user_by_id($token['uid']);
				$sina_oauth = new SinaOAuth();
				$sina_oauth->setAccess_token($token['access_token'])
							->setExpires_in($token['expires_in'])
							->setRemind_in($token['remind_in'])
							->setUid($token['uid'])
							->setName($user_message['name'])
							->setScreen_name($user_message['screen_name'])
							->setUrl($user_message['url'])
							->setProfile_image_url($user_message['profile_image_url']);
				$sina_oauth_model->insert($sina_oauth);
			}
			$oauth_session = new Container('sina_oauth');
			$oauth_session->uid = $token['uid'];
			return $this->redirect()->toUrl('signup');
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	
	public function signupAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		$sina_session = new Container('sina_oauth');
		$uid = $sina_session->uid;
		$oauth_session = new Container(ResultStatus::OAUTH);
		$url = $oauth_session->request_uri;
		if ($uid == null) {
			return $this->redirect()->toRoute('home');
		}
		$sina_oauth_model = $this->getServiceLocator()->get('SinaOAuthModel');
		$sina_user = $sina_oauth_model->find($uid);
	
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
			try {
				$user = $userModel->register($user);
			}catch (UserException $e) {
				$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
				return new JsonModel($resultStatus->getCM());
			}
			;
			$sina_oauth_model->updateUser($sina_user, $user);
		
			$userData = array(
					'indentity' => $username,
					'credential' => $password
			);
			//登录
			$userModel->auth($userData);
		
			
			return $this->redirect()->toUrl($url);
		}
		
		
		return new ViewModel(array('sina_user' => $sina_user));
		
	}
}

?>