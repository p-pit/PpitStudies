<?php
namespace PpitStudies;

return array(
	'controllers' => array(
        'invokables' => array(
        	'PpitStudies\Controller\Absence' => 'PpitStudies\Controller\AbsenceController',
        	'PpitStudies\Controller\Note' => 'PpitStudies\Controller\NoteController',
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
	       						'update' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/update[/:id]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'update',
		        								),
		        						),
		        				),
	       						'dashboard' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/dashboard[/:account_id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'dashboard',
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
        	'note' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/note',
                    'defaults' => array(
                        'controller' => 'PpitStudies\Controller\Note',
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
	       						'update' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/update[/:id]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'update',
		        								),
		        						),
		        				),
	       						'dashboard' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/dashboard[/:account_id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'dashboard',
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
	       						'update' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/update[/:type][/:id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'update',
        										),
        								),
        						),
	       						'dashboard' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/dashboard[/:account_id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'dashboard',
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
	       						'detail' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/detail[/:id]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'detail',
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
	       						'addAbsence' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add-absence[/:type]',
        										'defaults' => array(
        												'action' => 'addAbsence',
        										),
        								),
        						),
	       						'addNote' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add-note[/:type]',
        										'defaults' => array(
        												'action' => 'addNote',
        										),
        								),
        						),
	       						'addProgress' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add-progress[/:type]',
        										'defaults' => array(
        												'action' => 'addProgress',
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
				array('route' => 'absence/update', 'roles' => array('admin')),
				array('route' => 'absence/dashboard', 'roles' => array('user')),
				array('route' => 'absence/delete', 'roles' => array('admin')),
				array('route' => 'note', 'roles' => array('user')),
				array('route' => 'note/index', 'roles' => array('user')),
				array('route' => 'note/search', 'roles' => array('user')),
            	array('route' => 'note/list', 'roles' => array('user')),
				array('route' => 'note/export', 'roles' => array('user')),
				array('route' => 'note/detail', 'roles' => array('user')),
				array('route' => 'note/update', 'roles' => array('admin')),
				array('route' => 'note/dashboard', 'roles' => array('user')),
				array('route' => 'note/delete', 'roles' => array('admin')),
				array('route' => 'progress', 'roles' => array('user')),
				array('route' => 'progress/index', 'roles' => array('user')),
				array('route' => 'progress/search', 'roles' => array('user')),
            	array('route' => 'progress/list', 'roles' => array('user')),
				array('route' => 'progress/export', 'roles' => array('user')),
				array('route' => 'progress/update', 'roles' => array('user')),
				array('route' => 'progress/dashboard', 'roles' => array('user')),
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
            	array('route' => 'student/addAbsence', 'roles' => array('user')),
            	array('route' => 'student/addNote', 'roles' => array('user')),
				array('route' => 'student/addProgress', 'roles' => array('user')),
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
					'note' => array(
							'action' => 'Note',
							'route' => 'note',
							'params' => array('type' => ''),
							'urlParams' => array(),
							'label' => array(
									'en_US' => 'Notes',
									'fr_FR' => 'Notes',
							),
					),
					'absence' => array(
							'action' => 'Absence',
							'route' => 'absence',
							'params' => array('type' => ''),
							'urlParams' => array(),
							'label' => array(
									'en_US' => 'Absences',
									'fr_FR' => 'Absences',
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
	'commitmentAccount/p-pit-studies' => array(
			'statuses' => array(),
			'properties' => array(
					'customer_name' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Name',
									'fr_FR' => 'Nom',
							),
					),
					'n_first' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Fisrst name',
									'fr_FR' => 'Prénom',
							),
					),
					'n_last' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Last name',
									'fr_FR' => 'Nom',
							),
					),
					'email' => array(
							'type' => 'email',
							'labels' => array(
									'en_US' => 'Email',
									'fr_FR' => 'Email',
							),
					),
					'place_id' => array(
							'type' => 'select',
							'labels' => array(
									'en_US' => 'Center',
									'fr_FR' => 'Centre',
							),
					),
					'opening_date' => array(
							'type' => 'date',
							'labels' => array(
									'en_US' => 'Account opening date',
									'fr_FR' => 'Date ouverture compte',
							),
					),
					'closing_date' => array(
							'type' => 'date',
							'labels' => array(
									'en_US' => 'Account closing date',
									'fr_FR' => 'Date fermeture compte',
							),
					),
					'property_1' => array(
							'type' => 'select',
							'modalities' => array(
									'Basketball' => array('fr_FR' => 'Basketball'),
									'Equitation' => array('fr_FR' => 'Equitation'),
									'Football' => array('fr_FR' => 'Football'),
									'Golf' => array('fr_FR' => 'Golf'),
									'Tennis' => array('fr_FR' => 'Tennis'),
							),
							'labels' => array(
									'en_US' => 'Sport',
									'fr_FR' => 'Sport',
							),
					),
					'property_2' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Emergency phone',
									'fr_FR' => 'Tél. urgence',
							),
					),
					'property_3' => array(
							'type' => 'photo',
							'labels' => array(
									'en_US' => '',
									'fr_FR' => '',
							),
					),
					'property_4' => array(
							'type' => 'select',
							'modalities' => array(
									'6e' => array('fr_FR' => '6e'),
									'5e' => array('fr_FR' => '5e'),
									'4e' => array('fr_FR' => '4e'),
									'3e' => array('fr_FR' => '3e'),
									'2nde' => array('fr_FR' => '2nde'),
									'1ère' => array('fr_FR' => '1ère'),
									'Term.' => array('fr_FR' => 'Term.'),
							),
							'labels' => array(
									'en_US' => 'Class',
									'fr_FR' => 'Classe',
							),
					),
					'property_5' => array(
							'type' => 'select',
							'modalities' => array(
									'S' => array('fr_FR' => 'S'),
									'ES' => array('fr_FR' => 'ES'),
									'STMG' => array('fr_FR' => 'STMG'),
							),
							'labels' => array(
									'en_US' => 'Specialty',
									'fr_FR' => 'Spécialité',
							),
					),
					'property_6' => array(
							'type' => 'select',
							'modalities' => array(
									'Interne' => array('fr_FR' => 'Interne'),
									'Externe' => array('fr_FR' => 'Externe'),
									'Weekend' => array('fr_FR' => 'Weekend'),
									'Dimanche' => array('fr_FR' => 'Dimanche'),
							),
							'labels' => array(
									'en_US' => 'Boarding-school',
									'fr_FR' => 'Internat',
							),
					),
			),
	),
	'commitmentAccount/index/p-pit-studies' => array(
			'title' => array('en_US' => 'P-PIT Commitments', 'fr_FR' => 'P-PIT Engagements'),
	),
	'commitmentAccount/search/p-pit-studies' => array(
			'title' => array('en_US' => 'Students', 'fr_FR' => 'Eleves'),
			'todoTitle' => array('en_US' => 'registered', 'fr_FR' => 'inscrits'),
			'main' => array(
					'property_1' => 'select',
					'customer_name' => 'contains',
					'property_4' => 'select',
			),
			'more' => array(
					'place_id' => 'select',
					'opening_date' => 'range',
					'closing_date' => 'range',
			),
	),
	'commitmentAccount/list/p-pit-studies' => array(
			'property_1' => 'image',
			'property_3' => 'photo',
			'customer_name' => 'text',
			'property_2' => 'phone',
	),
	'commitmentAccount/detail/p-pit-studies' => array(
			'title' => array('en_US' => 'Student sheet:', 'fr_FR' => 'FICHE ELEVE'),
			'displayAudit' => false,
	),
	'commitmentAccount/update/p-pit-studies' => array(
			'n_first' => array('mandatory' => true),
			'n_last' => array('mandatory' => true),
			'email' => array('mandatory' => true),
			'place_id' => array('mandatory' => true),
			'opening_date' => array('mandatory' => true),
			'closing_date' => array('mandatory' => false),
			'property_1' => array('mandatory' => true),
			'property_2' => array('mandatory' => false),
	),
	'commitmentAccount/register/p-pit-studies' => array(),
	'commitment/accountList/p-pit-studies' => array(
			'title' => array('en_US' => 'Registrations', 'fr_FR' => 'INSCRIPTIONS'),
			'addRoute' => 'eleve/add',
			'glyphicons' => array(
					'eleve/eleve' => array(
							'labels' => array('en_US' => 'Update', 'fr_FR' => 'Modifier'),
							'glyphicon' => 'glyphicon-edit',
					),
					'eleve/delete' => array(
							'labels' => array('en_US' => 'Delete', 'fr_FR' => 'Supprimer'),
							'glyphicon' => 'glyphicon-trash',
					),
			),
			'properties' => array(
					'caption' => 'text',
					'property_1' => 'text',
					'property_2' => 'text',
			),
			'anchors' => array(
					'document' => array(
							'type' => 'nav',
							'labels' => array('en_US' => 'Documents', 'fr_FR' => 'Documents'),
							'entries' => array(
									'eleve/accuse' => array(
											'labels' => array('en_US' => 'Acknowledgement', 'fr_FR' => 'Accusé réception'),
									),
									'eleve/confirmation' => array(
											'labels' => array('en_US' => 'Confirmation', 'fr_FR' => 'Confirmation'),
									),
									'eleve/attestation' => array(
											'labels' => array('en_US' => 'Attestation', 'fr_FR' => 'Attestation'),
									),
							),
					),
					'bill' => array(
							'type' => 'btn',
							'labels' => array('en_US' => 'Bill', 'fr_FR' => 'Facture'),
					),
			),
	),
		
	'student' => array(
			'schoolYears' => array(
					'2016-2017' => array('fr_FR' => '2016-2017', 'en_US' => '2016-2017'),
			),
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
									'P1' => array('en_US' => 'Quarter 1', 'fr_FR' => 'Trim. 1'),
									'P2' => array('en_US' => 'Quarter 2', 'fr_FR' => 'Trim. 2'),
									'P3' => array('en_US' => 'Quarter 3', 'fr_FR' => 'Trim. 3'),
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
					'sport' => array(
							'type' => 'select',
							'modalities' => array(
									'Basketball' => array('fr_FR' => 'Basketball'),
									'Equitation' => array('fr_FR' => 'Equitation'),
									'Football' => array('fr_FR' => 'Football'),
									'Golf' => array('fr_FR' => 'Golf'),
									'Tennis' => array('fr_FR' => 'Tennis'),
							),
							'labels' => array(
									'en_US' => 'Sport',
									'fr_FR' => 'Sport',
							),
					),
					'photo' => array(
							'type' => 'photo',
							'labels' => array(
									'en_US' => '',
									'fr_FR' => '',
							),
					),
			),
	),

	'student/index' => array(
			'title' => array('en_US' => 'P-PIT Studies', 'fr_FR' => 'P-PIT Studies'),
	),

	'absence' => array(
			'types' => array(
					'boarding-school' => array(
							'labels' => array('en_US' => 'Boarding school', 'fr_FR' => 'Internat'),
					),
			),
	),
		
	'absence/search' => array(
			'title' => array('en_US' => 'Absences', 'fr_FR' => 'Absences'),
			'todoTitle' => array('en_US' => 'to check', 'fr_FR' => 'à viser'),
			'searchTitle' => array('en_US' => 'Search', 'fr_FR' => 'Recherche'),
			'main' => array('school_year' => 'select', 'name' => 'contains'/*, 'subject' => 'select'*/),
			'more' => array(),
	),
	
	'absence/list' => array(
			'sport' => 'image',
			'photo' => 'photo',
			'name' => 'text',
//			'subject' => 'select',
	),

	'absence/update' => array(
			'types' => array(
					'boarding-school' => array(
							'labels' => array('en_US' => 'Boarding school', 'fr_FR' => 'Internat'),
							'subjects' => array(
									'Matin' => array('en_US' => 'Morning', 'fr_FR' => 'Matin'),
									'Soir' => array('en_US' => 'Evening', 'fr_FR' => 'Soir'),
									'Week-end' => array('en_US' => 'Weekend', 'fr_FR' => 'Week-end'),
									'Dimanche' => array('en_US' => 'Sunday', 'fr_FR' => 'Dimanche'),
									'Footing' => array('en_US' => 'Footing', 'fr_FR' => 'Footing'),
							),
					),
					'schooling' => array(
							'labels' => array('en_US' => 'Schooling', 'fr_FR' => 'Scolarité'),
							'subjects' => array(
									'Français' => array('en_US' => 'French', 'fr_FR' => 'Français'),
									'Mathématiques' => array('en_US' => 'Mathematics', 'fr_FR' => 'Mathématiques'),
									'Physique/chimie' => array('en_US' => 'Physics/chemistry', 'fr_FR' => 'Physique/chimie'),
									'SVT' => array('en_US' => 'Life sciences', 'fr_FR' => 'SVT'),
									'LV1' => array('en_US' => 'LL1', 'fr_FR' => 'LV1'),
									'LV2' => array('en_US' => 'LL2', 'fr_FR' => 'LV2'),
									'Economie' => array('en_US' => 'Economics', 'fr_FR' => 'Economie'),
									'Histoire/géographie' => array('en_US' => 'History/geography', 'fr_FR' => 'Histoire/géographie'),
							),
					),
			),
	),

	'note' => array(
			'types' => array(
					'schooling' => array(
							'labels' => array('en_US' => 'Schooling', 'fr_FR' => 'Scolarité'),
					),
			),
	),

	'note/index' => array(
			'title' => array('en_US' => 'P-PIT Studies', 'fr_FR' => 'P-PIT Studies'),
	),
		
	'note/search' => array(
			'title' => array('en_US' => 'Notes', 'fr_FR' => 'Notes'),
			'todoTitle' => array('en_US' => 'to check', 'fr_FR' => 'à viser'),
			'searchTitle' => array('en_US' => 'Search', 'fr_FR' => 'Recherche'),
			'main' => array('school_year' => 'select', 'name' => 'contains'/*, 'subject' => 'select'*/),
			'more' => array(),
	),
	
	'note/list' => array(
			'sport' => 'image',
			'photo' => 'photo',
			'name' => 'text',
			//			'subject' => 'select',
	),
	
	'note/update' => array(
			'types' => array(
					'schooling' => array(
							'labels' => array('en_US' => 'Schooling', 'fr_FR' => 'Scolarité'),
							'subjects' => array(
									'Français' => array('en_US' => 'French', 'fr_FR' => 'Français'),
									'Mathématiques' => array('en_US' => 'Mathematics', 'fr_FR' => 'Mathématiques'),
									'Physique/chimie' => array('en_US' => 'Physics/chemistry', 'fr_FR' => 'Physique/chimie'),
									'SVT' => array('en_US' => 'Life sciences', 'fr_FR' => 'SVT'),
									'LV1' => array('en_US' => 'LL1', 'fr_FR' => 'LV1'),
									'LV2' => array('en_US' => 'LL2', 'fr_FR' => 'LV2'),
									'Economie' => array('en_US' => 'Economics', 'fr_FR' => 'Economie'),
									'Histoire/géographie' => array('en_US' => 'History/geography', 'fr_FR' => 'Histoire/géographie'),
							),
					),
			),
	),
		
	'progress' => array(
			'types' => array(
					'sport' => array(
							'labels' => array('en_US' => 'Sport', 'fr_FR' => 'Sport'),
							'accountProperty' => 'property_1',
							'subjects' => array(),
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
			'sport' => 'image',
			'photo' => 'photo',
			'name' => 'text',
			'period' => 'select',
	),
);
