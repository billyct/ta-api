<?php 

namespace OAuth\Controller;



use OAuth\Exceptions\OAuthException;

use Zend\Http\Client;

use Zend\Escaper\Escaper;

use OAuth\Lib\ResultStatus;

use Zend\Session\Container;

use Doctrine\ORM\EntityManager;

use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\EventManager\EventManagerInterface;

use OAuth\Model\ClientModel;
use OAuth\Model\SessionModel;
use OAuth\Model\ScopeModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;


class OAuthController extends AbstractActionController {
	
	private $authserver;
	private $auth;
	private $escaper;
	
	public function __construct() {
		$this->auth = new AuthenticationService();
		$this->auth->setStorage(new SessionStorage(ResultStatus::USER));
		$this->escaper = new Escaper('utf-8');
	}

	
 	public function setEventManager(EventManagerInterface $events) {
        parent::setEventManager($events);
        $this->init();
    }
    
    public function init() {
    	$request = new \OAuth2\Util\Request();
    	
    	$clientModel = $this->getServiceLocator()->get('OAuth\Model\ClientModel');
    	$sessionModel = $this->getServiceLocator()->get('OAuth\Model\SessionModel');
    	$scopeModel = $this->getServiceLocator()->get('OAuth\Model\ScopeModel');
    		
    	$this->authserver = new \OAuth2\AuthServer(
    			$clientModel,
    			$sessionModel,
    			$scopeModel
    	);
    	 
    	$this->authserver->addGrantType(new \OAuth2\Grant\AuthCode());
    	$this->authserver->setExpiresIn(86400);
    }
	
	public function indexAction() {
		//return $this->redirect()->toRoute('user/signin/');
		//return new JsonModel(array('msg' => 'this is an oauth2.0 api for ta'));
// 		$params = $this->getParamsFromSession();
// 		var_dump($params);
// 		var_dump($this->auth->getIdentity());
		return new ViewModel();
	}
	
	
	/* 
	 * example 
	 * http://api.localhost/oauth/authorize?client_id=1652999032&redirect_uri=http://127.0.0.1/callback.php&response_type=code&scope=user
	 *  
	 *  */
	public function authAction() {
		
		$resultStatus = $this->getServiceLocator()->get('ResultStatus');

		try {
			$this->storeParamsToSesstion();	
			if ($this->auth->hasIdentity()) {
				//授权页
				return $this->redirect()->toUrl('authorise');
			} else {
				$sina_oauth = $this->getServiceLocator()->get('SinaOAuth');
				$sina_url = $sina_oauth->getAuthorizeURL(WB_CALLBACK_URL);
				return new ViewModel(array(
						'request_uri' => $this->getRequest()->getRequestUri(),
						'sina_url' => $sina_url
						));
			}
		
		} catch (\Oauth2\Exception\ClientException $e) {	
			$resultStatus->setCM(ResultStatus::FAILED, $e->getMessage());
		} catch (OAuthException $e) {	
			$resultStatus->setCM(ResultStatus::FAILED, $e->getMessage());
		}
		return new JsonModel($resultStatus->getCM());
	}
	
	
	public function authoriseAction() {
		$resultStatus = $this->getServiceLocator()->get('ResultStatus');
		$authServer = $this->authserver;
		try {
			$params = $this->getParamsFromSession();

			if ($this->auth->hasIdentity()) {
				$user = $this->auth->getIdentity();
				$params['user_id'] = $user->getId();
			} else {
				$url = $this->getRequest()->getHeader('Referer')->getUri();
				return $this->redirect()->toUrl($url);
			}

			// 检查是否自动授权
			$autoApprove = ($params['client_details']['auto_approve'] == '1') ? true : false;
			
			// 判断用户授权成功
			if ( ($this->getRequest()->isPost() && $this->getRequest()->getPost('approve') != null) ||  $autoApprove == true) {		
				// 生成code
				
				$code = $authServer->newAuthoriseRequest('user', $params['user_id'], $params);
				$auth = new AuthenticationService();
				$auth->setStorage(new SessionStorage(ResultStatus::USER));
				$auth->clearIdentity();
				return $this->redirect()->toUrl(
						\OAuth2\Util\RedirectUri::make($params['redirect_uri'],
								array(
										'code'  =>  $code,
										//'state' =>  isset($params['state']) ? $params['state'] : ''
								)
						));
			} 
			
			if ($this->getRequest()->isPost() && $this->getRequest()->getPost('deny') != null) {		
				return $this->redirect()->toUrl(
						\OAuth2\Util\RedirectUri::make($params['redirect_uri'],
								array(
										'error' =>  $authServer::getExceptionType(2),
										'error_message' =>  $authServer::getExceptionMessage($authServer::getExceptionType(2)),
										//'state' =>  isset($params['state']) ? $params['state'] : ''
								)
						));
			}
			
			
		} catch (OAuthException $e) {
			$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
			return new JsonModel($resultStatus->getCM());
		}
		
		//加载授权页面
		return new ViewModel($params);
	}
	
	public function callbackAction() {
		$resultStatus = $this->getServiceLocator()->get('ResultStatus');
		try {
			$params = $this->getParamsFromSession();
			$request = $this->getRequest();
			$code = $request->getQuery('code');
			
			$queryStrings = array(
					'client_id' => $params['client_id'],
					'client_secret' => $params['client_details']['secret'],
					'grant_type' => 'authorization_code',
					'redirect_uri' => $params['redirect_uri'],
					'code' => $code,
					);
			return $this->redirect()->toUrl(\OAuth2\Util\RedirectUri::make('accesstoken', $queryStrings));
			
		}catch (OAuthException $e) {
			$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
			
		}
		return new JsonModel($resultStatus->getCM());

	}
	
	
	public function tokenAction() {
		
		$authServer = $this->authserver;
		try {
			// 获取token
			$response = $authServer->issueAccessToken();
		} catch (\Oauth2\Exception\ClientException $e) {
			// 返回客户端错误request的结果
			$response = array(
					'error' =>  $authServer::getExceptionType($e->getCode()),
					'error_description' => $e->getMessage()
			);
		
		} catch (\Exception $e) {
			$response = array(
					'error' =>  'undefined_error',
					'error_description' => $e->getMessage()
			);
		}
		
		return new JsonModel($response);
	}
	
	private function storeParamsToSesstion() {
		// 通过url接收一些query string
		$params = $this->authserver->checkAuthoriseParams();
		$this->checkParams($params);
		//将返回的认证信息存入session
		$oauth_session = new Container(ResultStatus::OAUTH);
		$oauth_session->client_id = $params['client_id'];
		$oauth_session->client_details = $params['client_details'];
		$oauth_session->redirect_uri = $params['redirect_uri'];
		$oauth_session->response_type = $params['response_type'];
		$oauth_session->scopes = $params['scopes'];
		$oauth_session->request_uri = $this->getRequest()->getRequestUri();
	}
	
	private function getParamsFromSession() {
		// 从session里取出信息
		$oauth_session = new Container(ResultStatus::OAUTH);
		$params['client_id'] = $oauth_session->client_id;
		$params['client_details'] = $oauth_session->client_details;
		$params['redirect_uri'] = $oauth_session->redirect_uri;
		$params['response_type'] = $oauth_session->response_type;
		$params['scopes'] = $oauth_session->scopes;
		$this->checkParams($params);
		return $params;
	}
	
	private function checkParams($params) {
		foreach ($params as $key => $value) {
			if ($value == null && $key != 'state') {
				throw new OAuthException('参数 '.$key.' 丢失');
			}
		}
	}
}

?>