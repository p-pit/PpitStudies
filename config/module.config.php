<?php
namespace PpitStudies;

return array(
	'controllers' => array(
        'invokables' => array(
        	'PpitStudies\Controller\Absence' => 'PpitStudies\Controller\AbsenceController',
        	'PpitStudies\Controller\Home' => 'PpitStudies\Controller\HomeController',
        	'PpitStudies\Controller\Sms' => 'PpitStudies\Controller\SmsController',
        	'PpitStudies\Controller\Student' => 'PpitStudies\Controller\StudentController',
        ),
    ),
 
    'router' => array(
        'routes' => array(
            'index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PpitStudies\Controller',
                        'controller' => 'Student',
                        'action'     => 'index',
                    ),
                ),
            ),
        	'absence' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/absence',
                    'defaults' => array(
                        'controller' => 'PpitStudies\Controller\Absence',
                        'action'     => 'index',
                    ),
                ),
           		'may_terminate' => true,
	       		'child_routes' => array(
        						'index' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/index',
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),
        						'search' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/search',
        										'defaults' => array(
        												'action' => 'search',
        										),
        								),
        						),
        						'list' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/list',
        										'defaults' => array(
        												'action' => 'list',
        										),
        								),
        						),
        						'export' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/export',
        										'defaults' => array(
        												'action' => 'export',
        										),
        								),
        						),
	       						'detail' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/detail[/:id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'detail',
        										),
        								),
        						),
	       						'add' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add[/:type]',
        										'defaults' => array(
        												'action' => 'add',
        										),
        								),
        						),
	       						'update' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/update[/:id][/:act]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'update',
		        								),
		        						),
		        				),
	       			),
	       			'delete' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/delete[/:id]',
		                    'constraints' => array(
		                    	'id' => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'delete',
	                        ),
	                    ),
	                ),
	       		),
        		'home' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/home[/:action][/:centre][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'centre' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    	'id'     => '[0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'PpitStudies\Controller\Home',
                        'action'     => 'index',
                    ),
                ),
            ),
        	'sms' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/sms',
                    'defaults' => array(
                        'controller' => 'PpitStudies\Controller\Sms',
                        'action'     => 'index',
                    ),
                ),
           		'may_terminate' => true,
	       		'child_routes' => array(
	                'index' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/index',
	                    	'defaults' => array(
	                    		'action' => 'index',
	                        ),
	                    ),
	                ),
	       			'delete' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/delete[/:id]',
		                    'constraints' => array(
		                    	'id' => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'delete',
	                        ),
	                    ),
	                ),
	       			'simulate' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/simulate[/:id]',
		                    'constraints' => array(
		                    	'id' => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'simulate',
	                        ),
	                    ),
	                ),
	       			'update' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/update[/:id]',
		                    'constraints' => array(
		                    	'id' => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'update',
	                        ),
	                    ),
	                ),
	       		),
        	),
        	'student' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/student',
                    'defaults' => array(
                        'controller' => 'PpitStudies\Controller\Student',
                        'action'     => 'index',
                    ),
                ),
           		'may_terminate' => true,
	       		'child_routes' => array(
        						'index' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/index',
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),
        						'search' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/search',
        										'defaults' => array(
        												'action' => 'search',
        										),
        								),
        						),
        						'list' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/list',
        										'defaults' => array(
        												'action' => 'list',
        										),
        								),
        						),
        						'export' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/export',
        										'defaults' => array(
        												'action' => 'export',
        										),
        								),
        						),
	       						'group' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/group[:type]',
        										'defaults' => array(
        												'action' => 'group',
        										),
        								),
        						),
	       		),
        	),
        ),
    ),
	'bjyauthorize' => array(
		// Guard listeners to be attached to the application event manager
		'guards' => array(
			'BjyAuthorize\Guard\Route' => array(

				array('route' => 'absence', 'roles' => array('user')),
				array('route' => 'absence/index', 'roles' => array('user')),
				array('route' => 'absence/search', 'roles' => array('user')),
            	array('route' => 'absence/list', 'roles' => array('user')),
				array('route' => 'absence/export', 'roles' => array('user')),
				array('route' => 'absence/detail', 'roles' => array('user')),
            	array('route' => 'absence/add', 'roles' => array('user')),
				array('route' => 'absence/update', 'roles' => array('admin')),
				array('route' => 'absence/delete', 'roles' => array('admin')),
				array('route' => 'sms', 'roles' => array('responsible')),
				array('route' => 'sms/delete', 'roles' => array('responsible')),
				array('route' => 'sms/index', 'roles' => array('responsible')),
				array('route' => 'sms/simulate', 'roles' => array('responsible')),
				array('route' => 'sms/update', 'roles' => array('responsible')),
				array('route' => 'student', 'roles' => array('user')),
				array('route' => 'student/index', 'roles' => array('user')),
				array('route' => 'student/search', 'roles' => array('user')),
				array('route' => 'student/detail', 'roles' => array('user')),
            	array('route' => 'student/group', 'roles' => array('user')),
				array('route' => 'student/export', 'roles' => array('user')),
            	array('route' => 'student/list', 'roles' => array('user')),
				array('route' => 'student/import', 'roles' => array('admin')),
			)
		)
	),
	__NAMESPACE__ => array(
			'options' => array(
					'routes' => array(
							'eleve' => 'eleve',
//							'eleve-login' => 'zfcuser/login',
							'home' => 'home',
//							'home-login' => 'zfcuser/login'
					)
			)
	),
		
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',       // On défini notre doctype
        'not_found_template'       => 'error/404',   // On indique la page 404
        'exception_template'       => 'error/index', // On indique la page en cas d'exception
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            'ppit-studies' => __DIR__ . '/../view',
         ),
    ),
	'translator' => array(
		'locale' => 'fr_FR',
		'translation_file_patterns' => array(
			array(
				'type'     => 'phparray',
				'base_dir' => __DIR__ . '/../language',
				'pattern'  => '%s.php',
				'text_domain' => 'ppit-studies'
			),
	       	array(
	            'type' => 'phparray',
	            'base_dir' => './vendor/zendframework/zendframework/resources/languages/',
	            'pattern'  => 'fr/Zend_Validate.php',
	        ),
		),
	),

	'ppitRoles' => array(
			'responsible' => array(
					'show' => true,
					'labels' => array(
							'en_US' => 'Responsible',
							'fr_FR' => 'Responsable',
					),
			),
	),

	'absence/update' => array(),
	'ppitStudiesDependencies' => array(),
	'student/index' => array(
			'title' => array('en_US' => 'P-PIT Studies', 'fr_FR' => 'P-PIT Studies'),
	),
);
