<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use OAuth\Sina\SaeTOAuthV2;

use Application\Lib\ImageThumb;

use Application\View\Helper\AuthService;

use Application\View\Helper\UserIdentity;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use OAuth\Lib\ResultStatus;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    
    public function getServiceConfig() {
    	return array(
    			'factories' => array(
    					'OAuth\Model\SessionModel' => function ($sl) {
    						return Module::modelFactory($sl, 'OAuth\Model\SessionModel');
    					},
    					'Application\Model\TimeLineModel' => function ($sl) {
    						return Module::modelFactory($sl, 'Application\Model\TimeLineModel');
    					},
    					'Application\Model\ImageModel' => function ($sl) {
    						return Module::modelFactory($sl, 'Application\Model\ImageModel');
    					},
    					'Application\Model\ImageTempModel' => function ($sl) {
    						return Module::modelFactory($sl, 'Application\Model\ImageTempModel');
    					},
    					'Application\Model\CommentModel' => function ($sl) {
    						return Module::modelFactory($sl, 'Application\Model\CommentModel');
    					},
    					'UserModel' => function ($sl) {
    						$authAdapter = $sl->get('OAuth\Auth\Adapter');
    						$model = Module::modelFactory($sl, 'OAuth\Model\UserModel');
    						$model->setAdapter($authAdapter);
    						return $model;
    					},
    					'GigModel' => function ($sl) {
    						return Module::modelFactory($sl, 'Gig\Model\GigModel');
    					},
    					'OAuth\Auth\Adapter' => function ($sl) {
    						return Module::modelFactory($sl, 'OAuth\Auth\Adapter');
    					},
    					'ImageThumb' => function($sl) {
    						$thumbnailer = $sl->get('WebinoImageThumb');
    						$imageThumb = new ImageThumb();
    						$imageThumb->setThumbnailer($thumbnailer);
    						return $imageThumb;
    					},
    					'SinaOAuth' => function($sl) {
    						$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
    						return $o;
    					},
    					'SinaOAuthModel' => function($sl) {
    						return Module::modelFactory($sl, 'Application\Model\SinaOAuthModel');
    					}
   
    			),
    	);
    }
    
    public static function modelFactory($sl, $className) {
    	$entityManager = $sl->get('Doctrine\ORM\EntityManager');
    	$model = new $className();
    	$model->setEntityManager($entityManager);
    	return $model;
    }
    
    public function getViewHelperConfig() {
    	return array(
    			'factories' => array(
    					'userIdentity' => function($sm) {
    						$authService = new AuthenticationService();
    						$authService->setStorage(new SessionStorage(ResultStatus::USER));
    						$viewHelper = new UserIdentity();
    						$viewHelper->setAuthService($authService);
    						return $viewHelper;
    						
    					},
    					'authService' => function($sm) {
    						$authService = new AuthenticationService();
    						$authService->setStorage(new SessionStorage(ResultStatus::USER));
    						$viewHelper = new AuthService();
    						$viewHelper->setAuthService($authService);
    						return $viewHelper;
    					}
    				),
    			);
    }
}
