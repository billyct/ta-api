<?php
namespace Gig;

use Application\Lib\ImageThumb;

use OAuth\Sina\SaeTOAuthV2;

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
    					'OAuth\Model\SessionModel' => function ($sl) {
    						return Module::modelFactory($sl, 'OAuth\Model\SessionModel');
    					},
    					'GigModel' => function ($sl) {
    						return Module::modelFactory($sl, 'Gig\Model\GigModel');
    					},
    					'ExtraModel' => function ($sl) {
    						return Module::modelFactory($sl, 'Gig\Model\ExtraModel');
    					},
    					
    					'MessageModel' => function ($sl) {
    						return Module::modelFactory($sl, 'Gig\Model\MessageModel');
    					},
    					'OrderModel' => function ($sl) {
    						return Module::modelFactory($sl, 'Gig\Model\OrderModel');
    					},
    					'TagModel' => function ($sl) {
    						return Module::modelFactory($sl, 'Gig\Model\TagModel');
    					},
    					'UserModel' => function ($sl) {
    						$authAdapter = $sl->get('Adapter');
    						$model = Module::modelFactory($sl, 'OAuth\Model\UserModel');
    						$model->setAdapter($authAdapter);
    						return $model;
    					},
    					'ImageThumb' => function($sl) {
    						$thumbnailer = $sl->get('WebinoImageThumb');
    						$imageThumb = new ImageThumb();
    						$imageThumb->setThumbnailer($thumbnailer);
    						return $imageThumb;
    					},
    					'Adapter' => function ($sl) {
    						return Module::modelFactory($sl, 'OAuth\Auth\Adapter');
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
}
