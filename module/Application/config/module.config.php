<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        		
        	'api-image' => array (
					'type' => 'segment',
					'options' => array (
							'route' => '/api/image[/][:action]',
							'constraints' => array (
									'action' => '[a-zA-Z][a-zA-Z0-9_-]*' 
							),
							'defaults' => array (
									'controller' => 'Application\Controller\Image',
									'action'     => 'index',
							) 
					) 
			),
        	'api-imagetemp' => array (
        			'type' => 'segment',
        			'options' => array (
        					'route' => '/api/imagetemp[/][:action]',
        					'constraints' => array (
       								'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
       						),
       						'defaults' => array (
       								'controller' => 'Application\Controller\ImageTemp',
       								'action'     => 'index',
       						)
       				)
        	),
        	'api-timeline' => array (
        			'type' => 'segment',
       				'options' => array (
       						'route' => '/api/timeline[/][:action]',
       						'constraints' => array (
       								'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
       						),
       						'defaults' => array (
       								'controller' => 'Application\Controller\TimeLine',
       								'action'     => 'index',
       						)
       				)
       		),
        		
        	'api-public' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route' => '/api/public[/][:action]',
        					'constraints' => array (
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
        					),
        					'defaults' => array (
        							'controller' => 'Application\Controller\Public',
        							'action'     => 'index',
        					)
        			)        			
        	),
        		
        	'api-comment' => array(
        			'type' => 'segment',
       				'options' => array(
       						'route' => '/api/comment[/][:action]',
       						'constraints' => array (
       								'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
       						),
       						'defaults' => array (
       								'controller' => 'Application\Controller\Comment',
       								'action'     => 'index',
        					)
        			)
        	),
        	
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
//             'application' => array(
//                 'type'    => 'Literal',
//                 'options' => array(
//                     'route'    => '/application',
//                     'defaults' => array(
//                         '__NAMESPACE__' => 'Application\Controller',
//                         'controller'    => 'Index',
//                         'action'        => 'index',
//                     ),
//                 ),
//                 'may_terminate' => true,
//                 'child_routes' => array(
//                     'default' => array(
//                         'type'    => 'Segment',
//                         'options' => array(
//                             'route'    => '/[:controller[/:action]]',
//                             'constraints' => array(
//                                 'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                 'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
//                             ),
//                             'defaults' => array(
//                             ),
//                         ),
//                     ),
//                 ),
//             ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'zh_CN',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
        	'Application\Controller\Image' => 'Application\Controller\ImageController',
        	'Application\Controller\ImageTemp' => 'Application\Controller\ImageTempController',
        	'Application\Controller\TimeLine' => 'Application\Controller\TimeLineController',
        	'Application\Controller\Public' => 'Application\Controller\PublicController',
        	'Application\Controller\Comment' => 'Application\Controller\CommentController',
//         	'Application\Controller\Sina' => 'Application\Controller\SinaController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    	'strategies' => array(
    		'ViewJsonStrategy',
   		),
    ),
    
    'doctrine' => array (
    		'driver' => array (
    				__NAMESPACE__ . '_driver' => array (
    						'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
    						'cache' => 'array',
    						'paths' => array (
    								__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
    						)
    				),
    				'orm_default' => array (
    						'drivers' => array (
    								__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
    						)
    				)
    		)
    ),
);
