<?php
namespace Gig;
return array(
		
		'controllers' => array (
				'invokables' => array (
						'Gig' => 'Gig\Controller\GigController',
						'Extra' => 'Gig\Controller\ExtraController',
						'Message' => 'Gig\Controller\MessageController',
						'Order' => 'Gig\Controller\OrderController',
				)
		),
		'view_manager' => array (
				'template_path_stack' => array (
						'gig' => __DIR__ . '/../view'
				),
				'strategies' => array(
						'ViewJsonStrategy',
				),
		),
		
		'router' => array(
				'routes' => array(
						'api-gig' => array (
								'type' => 'segment',
								'options' => array (
										'route' => '/api/gig[/][:action]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
										),
										'defaults' => array (
												'controller' => 'Gig',
												'action'     => 'index',
										)
								)
						),
						'api-extra' => array(
								'type' => 'segment',
								'options' => array( 
										'route' => '/api/extra[/][:action]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
										),
										'defaults' => array (
												'controller' => 'Extra',
												'action'     => 'index',
										)
								)
						),
						'api-message' => array(
								'type' => 'segment',
								'options' => array(
										'route' => '/api/msg[/][:action]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
										),
										'defaults' => array (
												'controller' => 'Message',
												'action'     => 'index',
										)
								)
						),
						
						'api-order' => array(
								'type' => 'segment',
								'options' => array(
										'route' => '/api/order[/][:action]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
										),
										'defaults' => array (
												'controller' => 'Order',
												'action'     => 'index',
										)
								)
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