<?php
namespace PpitStudies;

return array(
	'controllers' => array(
        'invokables' => array(
        	'PpitStudies\Controller\Absence' => 'PpitStudies\Controller\AbsenceController',
        	'PpitStudies\Controller\Progress' => 'PpitStudies\Controller\ProgressController',
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
        										'route' => '/detail[/:type][/:id][/:action]',
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
            ),
        	'progress' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/progress',
                    'defaults' => array(
                        'controller' => 'PpitStudies\Controller\Progress',
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
        										'route' => '/detail[/:type][/:id]',
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
				array('route' => 'progress', 'roles' => array('user')),
				array('route' => 'progress/index', 'roles' => array('user')),
				array('route' => 'progress/search', 'roles' => array('user')),
            	array('route' => 'progress/list', 'roles' => array('user')),
				array('route' => 'progress/export', 'roles' => array('user')),
				array('route' => 'progress/detail', 'roles' => array('user')),
            	array('route' => 'progress/add', 'roles' => array('user')),
				array('route' => 'progress/delete', 'roles' => array('admin')),
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

	'ppitStudiesDependencies' => array(),

	'menus' => array(
			'p-pit-studies' => array(
					'student' => array(
							'action' => 'Student',
							'route' => 'student',
							'params' => array('type' => ''),
							'urlParams' => array(),
							'label' => array(
									'en_US' => 'Students',
									'fr_FR' => 'Eleves',
							),
					),
					'progress' => array(
							'action' => 'Progress',
							'route' => 'progress',
							'params' => array('type' => ''),
							'urlParams' => array(),
							'label' => array(
									'en_US' => 'Sport progress',
									'fr_FR' => 'Suivi sportif',
							),
					),
			),
	),

	'absence/update' => array(),

	'student' => array(
			'types' => array(
					'sport' => array(
							'type' => 'select',
							'modalities' => array(
									'Basketball' => array('fr_FR' => 'Basketball'),
									'Football' => array('fr_FR' => 'Football'),
									'Golf' => array('fr_FR' => 'Golf'),
									'Tennis' => array('fr_FR' => 'Tennis'),
							),
							'labels' => array(
									'en_US' => 'Sport',
									'fr_FR' => 'Sport',
							),
					),
			),
	),

	'student/index' => array(
			'title' => array('en_US' => 'P-PIT Studies', 'fr_FR' => 'P-PIT Studies'),
	),

	'progress' => array(
			'types' => array(
					'sport' => array(
							'labels' => array('en_US' => 'Sport', 'fr_FR' => 'Sport'),
							'accountProperty' => 'property_1',
							'subjects' => array(),
					),
			),
			'properties' => array(
					'school_year' => array(
							'type' => 'select',
							'modalities' => array(
									'2016-2017' => array('fr_FR' => '2016-2017', 'en_US' => '2016-2017'),
							),
							'labels' => array(
									'en_US' => 'School year',
									'fr_FR' => 'Année scolaire',
							),
					),
					'period' => array(
							'type' => 'select',
							'modalities' => array(
									'P1' => array('fr_FR' => 'Sept/oct', 'en_US' => 'Sept/oct'),
									'P2' => array('fr_FR' => 'Nov/déc', 'en_US' => 'Nov/dec'),
									'P3' => array('fr_FR' => 'Jan/fév', 'en_US' => 'Jan/fev'),
									'P4' => array('fr_FR' => 'Mar/Avr', 'en_US' => 'Mar/Apr'),
									'P5' => array('fr_FR' => 'Mai/Juin', 'en_US' => 'May/June'),
							),
							'labels' => array(
									'en_US' => 'Period',
									'fr_FR' => 'Période',
							),
					),
					'name' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Name',
									'fr_FR' => 'Nom',
							),
					),
			),
	),

	'progress/index' => array(
			'title' => array('en_US' => 'P-PIT Studies', 'fr_FR' => 'P-PIT Studies'),
	),

	'progress/detail' => array(
			'types' => array(
					'sport' => array(
							'labels' => array('en_US' => 'Sport', 'fr_FR' => 'Sport'),
							'subjects' => array(
									'Basketball' => array(),
									'Equitation' => array(
											'qualitative_criteria' => array(
													'posture' => array(
															'labels' => array('fr_FR' => 'Mise en selle'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'obstacle' => array(
															'labels' => array('fr_FR' => 'Obstacle'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'soin' => array(
															'labels' => array('fr_FR' => 'Soins'),
															'type' => 'input',
															'maxLength'  => '255',
													),
											),
									),
									'Football' => array(
											'modalities' => array(
													'NA' => array('fr_FR' => 'Non acquis'),
													'EC' => array('fr_FR' => 'En cours'),
													'AC' => array('fr_FR' => 'Acquis'),
											),
											'quantitative_criteria' => array(
													'passe-pd' => array(
															'labels' => array('fr_FR' => 'Passes'),
															'type' => 'select',
															'maxLength'  => '255',
													),
													'controle' => array(
															'labels' => array('fr_FR' => 'Contrôle ballon'),
															'type' => 'select',
															'maxLength'  => '255',
													),
													'tir-pd' => array(
															'labels' => array('fr_FR' => 'Tirs'),
															'type' => 'select',
															'maxLength'  => '255',
													),
													'tete' => array(
															'labels' => array('fr_FR' => 'Jeu de tête'),
															'type' => 'select',
															'maxLength'  => '255',
													),
													'placement-def' => array(
															'labels' => array('fr_FR' => 'Placement'),
															'type' => 'select',
															'maxLength'  => '255',
													),
													'demarquage' => array(
															'labels' => array('fr_FR' => 'Démarquage'),
															'type' => 'select',
															'maxLength'  => '255',
													),
											),
									),
									'Golf' => array(
											'qualitative_criteria' => array(
													'chipping' => array(
															'labels' => array('fr_FR' => 'Chipping'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'putting' => array(
															'labels' => array('fr_FR' => 'Putting'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'bunker' => array(
															'labels' => array('fr_FR' => 'Bunker'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'parcours' => array(
															'labels' => array('fr_FR' => 'Parcours'),
															'type' => 'input',
															'maxLength'  => '255',
													),
											),
									),
									'Tennis' => array(
											'qualitative_criteria' => array(
													array(
															'labels' => array('fr_FR' => 'TECHNIQUE'),
															'type' => 'subtitle',
													),
													'coup-droit' => array(
															'labels' => array('fr_FR' => 'Coup droit'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'revers' => array(
															'labels' => array('fr_FR' => 'Revers'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'service' => array(
															'labels' => array('fr_FR' => 'Service'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'volee' => array(
															'labels' => array('fr_FR' => 'Volée'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'smash' => array(
															'labels' => array('fr_FR' => 'Smash'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													array(
															'labels' => array('fr_FR' => 'PHYSIQUE'),
															'type' => 'subtitle',
													),
													'placement' => array(
															'labels' => array('fr_FR' => 'Placement'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'jeu-jambe' => array(
															'labels' => array('fr_FR' => 'Jeux de jambe'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'foncier' => array(
															'labels' => array('fr_FR' => 'Foncier'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'vitesse' => array(
															'labels' => array('fr_FR' => 'Vitesse'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'test' => array(
															'labels' => array('fr_FR' => 'Tests'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													array(
															'labels' => array('fr_FR' => 'MENTAL'),
															'type' => 'subtitle',
													),
													'mental' => array(
															'labels' => array('fr_FR' => ''),
															'type' => 'input',
															'maxLength'  => '255',
													),
													array(
															'labels' => array('fr_FR' => 'OBJECTIFS'),
															'type' => 'subtitle',
													),
													'objectif-ct' => array(
															'labels' => array('fr_FR' => 'Objectifs court-terme'),
															'type' => 'input',
															'maxLength'  => '255',
													),
													'objectif-lt' => array(
															'labels' => array('fr_FR' => 'Objectifs long-terme'),
															'type' => 'input',
															'maxLength'  => '255',
													),
											),
									),
							),
					),
			),
	),

	'progress/search' => array(
			'title' => array('en_US' => 'Sport progress', 'fr_FR' => 'Suivi sportif'),
			'todoTitle' => array('en_US' => 'to be completed', 'fr_FR' => 'à compléter'),
			'searchTitle' => array('en_US' => 'Search', 'fr_FR' => 'Recherche'),
			'main' => array('school_year' => 'select', 'name' => 'contains', 'period' => 'select'),
			'more' => array(),
	),

	'progress/list' => array(
			'school_year' => 'select',
			'name' => 'text',
			'period' => 'select',
	),
);
