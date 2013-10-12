<?php
namespace OAuth;
return array (
		'controllers' => array (
				'invokables' => array (
						'OAuth\Controller\OAuth' => 'OAuth\Controller\OAuthController',
						'OAuth\Controller\User' => 'OAuth\Controller\UserController',
						'OAuth\Controller\Sina' => 'OAuth\Controller\SinaController',
				) 
		),
		'view_manager' => array (
				'template_path_stack' => array (
						'oauth' => __DIR__ . '/../view' 
				),
				'strategies' => array(
						'ViewJsonStrategy',
				),
		),
		
		'router' => array (
				'routes' => array (
						'oauth' => array (
								'type' => 'segment',
								'options' => array (
										'route' => '/oauth[/][:action]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*' 
										),
										'defaults' => array (
												'controller' => 'OAuth\Controller\OAuth',
												'action' => 'index' 
										) 
								) 
						),
						'api-user' => array (
								'type' => 'segment',
								'options' => array (
										'route' => '/api/user[/][:action]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*' 
										),
										'defaults' => array (
												'controller' => 'OAuth\Controller\User',
												'action' => 'index' 
										) 
								) 
						),
						
						'api-sina' => array(
								'type' => 'segment',
								'options' => array(
										'route' => '/api/sina[/][:action]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
										),
										'defaults' => array (
												'controller' => 'OAuth\Controller\Sina',
												'action'     => 'signup',
										)
								)
						),
						'user-signin' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/user/signin',
										'defaults' => array(
												'controller' => 'OAuth\Controller\User',
												'action'     => 'signin',
										),
								),
						),
						'user-signup' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/user/signup',
										'defaults' => array(
												'controller' => 'OAuth\Controller\User',
												'action'     => 'signup',
										),
								),
						),
						'user-signout' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/user/signout',
										'defaults' => array(
												'controller' => 'OAuth\Controller\User',
												'action'     => 'signout',
										),
								),
						),
						
				) 
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