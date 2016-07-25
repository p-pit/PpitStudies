<?php
namespace PpitStudies;

return array(
	'controllers' => array(
        'invokables' => array(
        	'PpitStudies\Controller\Home' => 'PpitStudies\Controller\HomeController',
        	'PpitStudies\Controller\Sms' => 'PpitStudies\Controller\SmsController',
        	'PpitStudies\Controller\Student' => 'PpitStudies\Controller\StudentController',
//        	'PpitStudies\Controller\StudentSport' => 'PpitStudies\Controller\StudentSportController',
        ),
    ),
 
    // The following section is new and should be added to your file
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
                'type'    => 'segment',
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
	                        'route' => '/add',
	                    	'defaults' => array(
	                            'action' => 'add',
	                        ),
	                    ),
	                ),
	       			'duplicate' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/duplicate[/:id]',
		                    'constraints' => array(
		                    	'id' => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'duplicate',
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
	       			'updateSport' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/update-sport[/:id]',
		                    'constraints' => array(
		                    	'id' => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'updateSport',
	                        ),
	                    ),
	                ),
	       			'updateMainContact' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/update-main-contact[/:id]',
		                    'constraints' => array(
		                    	'id' => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'updateMainContact',
	                        ),
	                    ),
	                ),
	       			'updateBackupContact' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/update-backup-contact[/:id]',
		                    'constraints' => array(
		                    	'id' => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'updateBackupContact',
	                        ),
	                    ),
	                ),
	       			'updateBillContact' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/update-bill-contact[/:id]',
		                    'constraints' => array(
		                    	'id' => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'updateBillContact',
	                        ),
	                    ),
	                ),
	       			'home' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/home[/:id]',
		                    'constraints' => array(
		                    	'id' => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'home',
	                        ),
	                    ),
	                ),
	       			'photo' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/photo[/:id]',
		                    'constraints' => array(
		                    	'id'     => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'photo',
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
	       			'import' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/import',
	                    	'defaults' => array(
	                            'action' => 'import',
	                        ),
	                    ),
	                ),
	       		),
        	),
/*        	'studentSport' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/student-sport',
                    'defaults' => array(
                        'controller' => 'PpitStudies\Controller\StudentSport',
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
	       			'updateSport' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/update-sport[/:id]',
		                    'constraints' => array(
		                    	'id' => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'updateSport',
	                        ),
	                    ),
	                ),
	       			'photo' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/photo[/:id]',
		                    'constraints' => array(
		                    	'id'     => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'photo',
	                        ),
	                    ),
	                ),
	       			'home' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/home[/:id]',
		                    'constraints' => array(
		                    	'id'     => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'home',
	                        ),
	                    ),
	                ),
	       			'delete' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/delete[/:id]',
		                    'constraints' => array(
		                    		'id'     => '[0-9]*',
		                    ),
	                    	'defaults' => array(
	                            'action' => 'delete',
	                        ),
	                    ),
	                ),
	       		),
	       	),*/
        ),
    ),
	'bjyauthorize' => array(
		// Guard listeners to be attached to the application event manager
		'guards' => array(
			'BjyAuthorize\Guard\Route' => array(

				// Users
				array('route' => 'sms', 'roles' => array('responsible')),
				array('route' => 'sms/delete', 'roles' => array('responsible')),
				array('route' => 'sms/index', 'roles' => array('responsible')),
				array('route' => 'sms/simulate', 'roles' => array('responsible')),
				array('route' => 'sms/update', 'roles' => array('responsible')),
				array('route' => 'student', 'roles' => array('responsible')),
				array('route' => 'student/add', 'roles' => array('responsible')),
				array('route' => 'student/index', 'roles' => array('responsible')),
				array('route' => 'student/delete', 'roles' => array('responsible')),
				array('route' => 'student/duplicate', 'roles' => array('responsible')),
				array('route' => 'student/detail', 'roles' => array('responsible')),
				array('route' => 'student/export', 'roles' => array('responsible')),
				array('route' => 'student/home', 'roles' => array('user')),
				array('route' => 'student/photo', 'roles' => array('user')),
				array('route' => 'student/search', 'roles' => array('admin')),
				array('route' => 'student/update', 'roles' => array('responsible')),
				array('route' => 'student/updateSport', 'roles' => array('responsible')),
				array('route' => 'student/updateMainContact', 'roles' => array('responsible')),
				array('route' => 'student/updateBackupContact', 'roles' => array('responsible')),
				array('route' => 'student/updateBillContact', 'roles' => array('responsible')),
				array('route' => 'student/import', 'roles' => array('admin')),
/*				array('route' => 'studentSport/add', 'roles' => array('admin')),
				array('route' => 'studentSport/photo', 'roles' => array('user')),
				array('route' => 'studentSport/delete', 'roles' => array('admin')),
				array('route' => 'studentSport/detail', 'roles' => array('admin')),
				array('route' => 'studentSport/export', 'roles' => array('admin')),
				array('route' => 'studentSport/home', 'roles' => array('user')),
				array('route' => 'studentSport/index', 'roles' => array('admin')),
				array('route' => 'studentSport/list', 'roles' => array('admin')),
				array('route' => 'studentSport/update', 'roles' => array('responsible')),
				array('route' => 'studentSport/updateSport', 'roles' => array('responsible')),
				array('route' => 'studentSport/search', 'roles' => array('admin')),
				array('route' => 'studentSport/import', 'roles' => array('admin')),*/
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

	'ppitStudiesDependencies' => array(
	),
);
