<?php
namespace OAuth;

use OAuth\Sina\SaeTOAuthV2;

use OAuth\Lib\ResultStatus;

use OAuth\Controller\OAuthController;

use OAuth\Model\ClientModel;
use OAuth\Model\SessionModel;
use OAuth\Model\ScopeModel;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(

            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),

            
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
    					'OAuth\Model\ClientModel' => function ($sl) {	
    						return Module::modelFactory($sl, 'OAuth\Model\ClientModel');
    					},
    					'OAuth\Model\SessionModel' => function ($sl) {
    						return Module::modelFactory($sl, 'OAuth\Model\SessionModel');
    					},
    					'OAuth\Model\ScopeModel' => function ($sl) {
    						return Module::modelFactory($sl, 'OAuth\Model\ScopeModel');
    					},
    					'UserModel' => function ($sl) {	
    						$authAdapter = $sl->get('Adapter');   						
    						$model = Module::modelFactory($sl, 'OAuth\Model\UserModel');
    						$model->setAdapter($authAdapter);
    						return $model;
    					},
    					'Adapter' => function ($sl) {
    						return Module::modelFactory($sl, 'OAuth\Auth\Adapter');
    					},
    					'ResultStatus' => function ($sl) {
    						return new ResultStatus();
    					},
    					'SinaOAuth' => function($sl) {
    						$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
    						return $o;
    					},
    					'SinaOAuthModel' => function($sl) {
    						return Module::modelFactory($sl, 'OAuth\Model\SinaOAuthModel');
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
    
//     public function getControllerConfig() {
//     	return array(
//     			'factories' => array(
//     					'OAuth\Controller\OAuth' => function($controllers) {
//     						$services = $controllers->getServiceLocator();
//     						$controller = new OAuthController();
//     						$events = $services->get('EventManager');
//     						$events->attach('dispatch', function($e) use ($controller) {
//     							echo "xxxsdfasdfasdf";
//     						}, 100);
    						
//     						$controller->setEventManager($events);
    						
//     					},
//     				),
//     			);
//     }
    
}
