<?php
namespace PpitStudies;

return array(
	'controllers' => array(
        'invokables' => array(
        	'PpitStudies\Controller\Absence' => 'PpitStudies\Controller\AbsenceController',
        	'PpitStudies\Controller\Event' => 'PpitStudies\Controller\EventController',
        	'PpitStudies\Controller\Note' => 'PpitStudies\Controller\NoteController',
        	'PpitStudies\Controller\Notification' => 'PpitStudies\Controller\NotificationController',
        	'PpitStudies\Controller\Planning' => 'PpitStudies\Controller\PlanningController',
        	'PpitStudies\Controller\Progress' => 'PpitStudies\Controller\ProgressController',
        	'PpitStudies\Controller\Student' => 'PpitStudies\Controller\StudentController',
        ),
    ),

	'console' => array(
			'router' => array(
					'routes' => array(
							'batchNomad' => array(
									'options' => array(
											'route'    => 'student nomad <instance_id> <request> [--place_identifier=] [--limit=]',
											'defaults' => array(
													'controller' => 'PpitStudies\Controller\Student',
													'action'     => 'batchNomad'
											)
									)
							),
					),
			),
	),
		
    'router' => array(
        'routes' => array(
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
        										'route' => '/index[/:app]',
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
		        								'route' => '/update[/:id][/:act]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'update',
		        								),
		        						),
		        				),
	       						'reprise' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/reprise',
		        								'defaults' => array(
		        										'action' => 'reprise',
		        								),
		        						),
		        				),
	       		),
            ),
        	'planning' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/planning',
                    'defaults' => array(
                        'controller' => 'PpitStudies\Controller\Planning',
                        'action'     => 'index',
                    ),
                ),
            	'may_terminate' => true,
            		'child_routes' => array(
            				'index' => array(
            						'type' => 'segment',
            						'options' => array(
            								'route' => '/index[/:type]',
            								'defaults' => array(
            										'action' => 'index',
            								),
            						),
            				),
            		),
        	),
        	'studentEvent' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/student-event',
                    'defaults' => array(
                        'controller' => 'PpitStudies\Controller\Event',
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
        						'planning' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/planning[/:id]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
        										'defaults' => array(
        												'action' => 'planning',
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
		        								'route' => '/update[/:id]',
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
        										'route' => '/index[/:app][/:category][/:type]',
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),
        						'search' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/search[/:category][/:type]',
        										'defaults' => array(
        												'action' => 'search',
        										),
        								),
        						),
        						'list' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/list[/:category][/:type]',
        										'defaults' => array(
        												'action' => 'list',
        										),
        								),
        						),
        						'export' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/export[/:category][/:type]',
        										'defaults' => array(
        												'action' => 'export',
        										),
        								),
        						),
        						'exportCsv' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/export-csv[/:category][/:type]',
        										'defaults' => array(
        												'action' => 'exportCsv',
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
		        								'route' => '/update[/:id][/:act]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'update',
		        								),
		        						),
		        				),
	       						'updateEvaluation' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/update-evaluation[/:id][/:act]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'updateEvaluation',
		        								),
		        						),
		        				),
			       				'reprise' => array(
	       								'type' => 'segment',
		        						'options' => array(
		        								'route' => '/reprise[/:place_identifier]',
		        								'defaults' => array(
		        										'action' => 'reprise',
		        								),
		        						),
			       				),
	       		),
            ),
        	'studentNotification' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/student-notification',
                    'defaults' => array(
                        'controller' => 'PpitStudies\Controller\Notification',
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
		        								'route' => '/update[/:id]',
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
        	'planning' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/planning',
                    'defaults' => array(
                        'controller' => 'PpitStudies\Controller\Planning',
                        'action'     => 'index',
                    ),
                ),
            	'may_terminate' => true,
            		'child_routes' => array(
            				'index' => array(
            						'type' => 'segment',
            						'options' => array(
            								'route' => '/index[/:type]',
            								'defaults' => array(
            										'action' => 'index',
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
        										'route' => '/index[/:app][/:type]',
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),
        						'search' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/search[/:type]',
        										'defaults' => array(
        												'action' => 'search',
        										),
        								),
        						),
        						'list' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/list[/:type]',
        										'defaults' => array(
        												'action' => 'list',
        										),
        								),
        						),
        						'export' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/export[/:type]',
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
        										'route' => '/index[/:app]',
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),
        						'studentHome' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/student-home[/:account_id]',
		        								'constraints' => array(
		        										'account_id'     => '[0-9]*',
		        								),
        										'defaults' => array(
        												'action' => 'studentHome',
        										),
        								),
        						),
	       						'registrationIndex' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/registration-index[/:app][/:type]',
        										'defaults' => array(
        												'action' => 'registrationIndex',
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
	       						'addEvent' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add-event[/:category]',
        										'defaults' => array(
        												'action' => 'addEvent',
        										),
        								),
        						),
	       						'addNote' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add-note[/:type][/:class]',
        										'defaults' => array(
        												'action' => 'addNote',
        										),
        								),
        						),
	       						'addEvaluation' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add-evaluation[/:type][/:class]',
        										'defaults' => array(
        												'action' => 'addEvaluation',
        										),
        								),
        						),
	       						'addNotification' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add-notification[/:category]',
        										'defaults' => array(
        												'action' => 'addNotification',
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
	       						'dashboard' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/dashboard[/:account_id][/:category]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'dashboard',
        										),
        								),
        						),
	       						'planning' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/planning[/:id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'planning',
        										),
        								),
        						),
	       						'file' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/file[/:id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'file',
        										),
        								),
        						),
	       						'absence' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/absence[/:id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'absence',
        										),
        								),
        						),
	       						'homework' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/homework[/:id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'homework',
        										),
        								),
        						),
	       						'evaluation' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/evaluation[/:id][/:mock]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'evaluation',
        										),
        								),
        						),
	       						'exam' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/exam[/:id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'exam',
        										),
        								),
        						),
	       						'report' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/report[/:id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'report',
        										),
        								),
        						),
	       						'download' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/download[/:category][/:account_id][/:school_year][/:school_period][/:level]',
		        								'constraints' => array(
		        										'account_id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'download',
		        								),
		        						),
		        				),
	       						'downloadExam' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/download-exam[/:account_id][/:school_year][/:level]',
		        								'constraints' => array(
		        										'account_id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'downloadExam',
		        								),
		        						),
		        				),
	       						'dropboxLink' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/dropbox-link[/:document]',
        										'defaults' => array(
        												'action' => 'dropboxLink',
        										),
        								),
        						),
	       						'letter' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/letter[/:template][/:id]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'letter',
		        								),
		        						),
		        				),
	       						'confirmation' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/confirmation[/:id]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'confirmation',
		        								),
		        						),
		        				),
	       						'attestation' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/attestation[/:id]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'attestation',
		        								),
		        						),
		        				),
	       						'acknowledgement' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/acknowledgement[/:id]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'acknowledgement',
		        								),
		        						),
		        				),
	       						'commitment' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/commitment[/:id]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'commitment',
		        								),
		        						),
		        				),
	       						'nomad' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/nomad[/:request][/:from]',
		        								'defaults' => array(
		        										'action' => 'nomad',
		        								),
		        						),
		        				),
	       						'cleanUserPerimeter' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/clean-user-perimeter',
		        								'defaults' => array(
		        										'action' => 'cleanUserPerimeter',
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

				array('route' => 'absence', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/index', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/search', 'roles' => array('manager', 'coach', 'teacher')),
            	array('route' => 'absence/list', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/export', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/detail', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/update', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/reprise', 'roles' => array('admin')),
				
				array('route' => 'studentEvent', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'studentEvent/index', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'studentEvent/search', 'roles' => array('manager', 'coach', 'teacher')),
            	array('route' => 'studentEvent/list', 'roles' => array('manager', 'coach', 'teacher')),
            	array('route' => 'studentEvent/planning', 'roles' => array('user')),
				array('route' => 'studentEvent/export', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'studentEvent/update', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'studentEvent/delete', 'roles' => array('manager', 'coach', 'teacher')),

				array('route' => 'note', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/index', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/search', 'roles' => array('manager', 'teacher')),
            	array('route' => 'note/list', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/export', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/exportCsv', 'roles' => array('admin')),
				array('route' => 'note/detail', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/update', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/updateEvaluation', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/reprise', 'roles' => array('admin')),
						
				array('route' => 'studentNotification', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'studentNotification/index', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'studentNotification/search', 'roles' => array('manager', 'coach', 'teacher')),
            	array('route' => 'studentNotification/list', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'studentNotification/export', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'studentNotification/update', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'studentNotification/delete', 'roles' => array('manager', 'coach', 'teacher')),

				array('route' => 'planning', 'roles' => array('manager')),
				array('route' => 'planning/index', 'roles' => array('manager')),

				array('route' => 'progress', 'roles' => array('manager', 'coach')),
				array('route' => 'progress/index', 'roles' => array('manager', 'coach')),
				array('route' => 'progress/search', 'roles' => array('manager', 'coach')),
            	array('route' => 'progress/list', 'roles' => array('manager', 'coach')),
				array('route' => 'progress/export', 'roles' => array('manager', 'coach')),
				array('route' => 'progress/update', 'roles' => array('manager', 'coach')),
				array('route' => 'progress/delete', 'roles' => array('manager', 'coach')),


				array('route' => 'student', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/registrationIndex', 'roles' => array('manager')),
				array('route' => 'student/index', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/studentHome', 'roles' => array('user')),
				array('route' => 'student/search', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/export', 'roles' => array('manager', 'coach', 'teacher')),
            	array('route' => 'student/list', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/detail', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/group', 'roles' => array('manager', 'coach', 'teacher')),
            	array('route' => 'student/addAbsence', 'roles' => array('manager', 'coach', 'teacher')),
            	array('route' => 'student/addEvent', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/addNote', 'roles' => array('manager', 'teacher')),
				array('route' => 'student/addEvaluation', 'roles' => array('manager', 'teacher')),
				array('route' => 'student/addNotification', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/addProgress', 'roles' => array('manager', 'coach')),
				array('route' => 'student/dashboard', 'roles' => array('user')),
				array('route' => 'student/planning', 'roles' => array('guest')),
				array('route' => 'student/file', 'roles' => array('guest')),
				array('route' => 'student/absence', 'roles' => array('guest')),
				array('route' => 'student/homework', 'roles' => array('guest')),
				array('route' => 'student/evaluation', 'roles' => array('guest')),
				array('route' => 'student/exam', 'roles' => array('guest')),
				array('route' => 'student/report', 'roles' => array('guest')),
				array('route' => 'student/download', 'roles' => array('guest')),
				array('route' => 'student/downloadExam', 'roles' => array('guest')),
				array('route' => 'student/dropboxLink', 'roles' => array('guest')),
				array('route' => 'student/letter', 'roles' => array('operational_management', 'sales_manager', 'manager')),
				array('route' => 'student/confirmation', 'roles' => array('operational_management', 'sales_manager', 'manager')),
				array('route' => 'student/attestation', 'roles' => array('operational_management', 'sales_manager', 'manager')),
				array('route' => 'student/acknowledgement', 'roles' => array('operational_management', 'sales_manager', 'manager')),
				array('route' => 'student/commitment', 'roles' => array('operational_management', 'sales_manager', 'manager')),
				array('route' => 'student/nomad', 'roles' => array('admin')),
				array('route' => 'student/cleanUserPerimeter', 'roles' => array('admin')),
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

	'ppitStudiesDependencies' => array(),

	'menus/p-pit-studies' => array(
		'entries' => array(
					'student' => array(
							'route' => 'student/index',
							'params' => array('app' => 'p-pit-studies', 'type' => ''),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-list-alt',
							'label' => array(
									'en_US' => 'Students/Classes',
									'fr_FR' => 'Elèves/Classes',
							),
					),
					'planning' => array(
							'route' => 'planning/index',
							'params' => array('type' => 'calendar', 'app' => 'p-pit-studies'),
							'glyphicon' => 'glyphicon-time',
							'label' => array(
									'en_US' => 'Planning',
									'fr_FR' => 'Planning',
							),
					),
/*					'notification' => array(
							'route' => 'studentNotification',
							'params' => array('type' => ''),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-edit',
							'label' => array(
									'en_US' => 'Notifications',
									'fr_FR' => 'Notifications',
							),
					),
					'progress' => array(
							'route' => 'progress/index',
							'params' => array('app' => 'p-pit-studies'),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-edit',
							'label' => array(
									'en_US' => 'Sport progress',
									'fr_FR' => 'Suivi sportif',
							),
					),*/
					'absence' => array(
							'route' => 'absence/index',
							'params' => array('app' => 'p-pit-studies', 'type' => ''),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-hourglass',
							'label' => array(
									'en_US' => 'Absences/Lateness',
									'fr_FR' => 'Absences/Retards',
							),
					),
					'homework' => array(
							'route' => 'note/index',
							'params' => array('app' => 'p-pit-studies', 'category' => 'homework'),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-calendar',
							'label' => array(
									'en_US' => 'Homework notebook',
									'fr_FR' => 'Cahier de texte',
							),
					),
					'evaluation' => array(
							'route' => 'note/index',
							'params' => array('app' => 'p-pit-studies', 'category' => 'evaluation', 'type' => 'note'),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-dashboard',
							'label' => array(
									'en_US' => 'Evaluations',
									'fr_FR' => 'Relevés de notes',
							),
					),
					'report' => array(
							'route' => 'note/index',
							'params' => array('app' => 'p-pit-studies', 'category' => 'evaluation', 'type' => 'report'),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-education',
							'label' => array(
									'en_US' => 'School reports',
									'fr_FR' => 'Bulletins',
							),
					),
					'exam' => array(
							'route' => 'note/index',
							'params' => array('app' => 'p-pit-studies', 'category' => 'evaluation', 'type' => 'exam'),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-education',
							'label' => array(
									'en_US' => 'Mock exams',
									'fr_FR' => 'Examens blancs',
							),
					),
/*					'event' => array(
							'route' => 'studentEvent',
							'params' => array('type' => ''),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-edit',
							'label' => array(
									'en_US' => 'Appointments',
									'fr_FR' => 'Rendez-vous',
							),
					),*/
					'account' => array(
							'route' => 'student/registrationIndex',
							'params' => array('app' => 'p-pit-studies', 'type' => 'p-pit-studies'),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-folder-open',
							'label' => array(
									'en_US' => 'Registrations',
									'fr_FR' => 'Inscriptions',
							),
					),
					'user' => array(
							'route' => 'user/index',
							'params' => array('app' => 'p-pit-studies'),
							'glyphicon' => 'glyphicon-user',
							'urlParams' => array(),
							'label' => array(
									'en_US' => 'Teachers',
									'fr_FR' => 'Professeurs',
							),
					),
					'admin' => array(
							'route' => 'instance/admin',
							'params' => array('app' => 'p-pit-studies'),
							'glyphicon' => 'glyphicon-cog',
							'urlParams' => array(),
							'label' => array(
									'en_US' => 'Admin',
									'fr_FR' => 'Admin',
							),
					),
		),
		'labels' => array(
			'default' => '2pit Studies',
			'fr_FR' => 'P-Pit Studies',
		),
	),

	'admin/p-pit-studies' => array(
			'student/property/contact_meeting_context',
			'student/property/discipline',
			'student/property/level',
			'student/property/class',
			'student/property/boarding_school',
			'student/property/school_year',
			'student/property/school_period',
			'student/property/evaluationCategory',
			'student/property/reportMention',
			'student/property/school_subject',
			'absence/property/motive',
	),

	'currentApplication' => 'p-pit-studies',
	'currentPeriodStart' => '2017-09-01',
	'currentPeriodEnd' => '2017-10-20',

	'place_config/default' => array(
			'school_periods' => array(
					'type' => 'periods',
					'end_dates' => array(
							'Q1' => '2018-12-10',
							'Q2' => '2019-02-28',
							'Q3' => '2019-06-30',
					),
			),
	),

	'calendar/p-pit-studies' => array(
			'school_periods' => array('definition' => 'place_config'),
	),

	'ppitProduct/p-pit-studies' => array(
			'properties' => array(),
			'criteria' => array(),
			'todo' => array(
					'sales_manager' => array(),
			),
	),
		
	'ppitProduct/index/p-pit-studies' => array(
			'title' => array('en_US' => 'Sport studies', 'fr_FR' => 'Sport études'),
	),
		
	'ppitProduct/search/p-pit-studies' => array(),
		
	'ppitProduct/list/p-pit-studies' => array(),
		
	'ppitProduct/update/p-pit-studies' => array(),

	'commitment/p-pit-studies/property/account_status' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'new' => array('en_US' => 'New', 'fr_FR' => 'Nouveau'),
			'suspect' => array('en_US' => 'Suspect (landing page)', 'fr_FR' => 'Suspect (landing page)'),
			'interested' => array('en_US' => 'Interested', 'fr_FR' => 'Intéressé'),
			'candidate' => array('en_US' => 'Condidate', 'fr_FR' => 'Candidat'),
			'active' => array('en_US' => 'Active', 'fr_FR' => 'Actif'),
			'gone' => array('en_US' => 'Gone', 'fr_FR' => 'Parti'),
		),
		'labels' => array(
			'en_US' => 'Account status',
			'fr_FR' => 'Statut du compte',
		),
	),
	
	'commitment/p-pit-studies' => array(
			'tax' => 'including',
			'currencySymbol' => '€',
			'properties' => array(
					'type' => array(
							'type' => 'repository',
							'definition' => 'commitment/types',
					),
					'status' => array(
							'definition' => 'inline',
							'type' => 'select',
							'modalities' => array(
								'new' => array('en_US' => 'To be confirmed', 'fr_FR' => 'A confirmer'),
								'confirmed' => array('en_US' => 'Confirmed', 'fr_FR' => 'Confirmé'),
								'settled' => array('en_US' => 'Settled', 'fr_FR' => 'Réglé'),
								'invoiced' => array('en_US' => 'Invoiced', 'fr_FR' => 'Facturé'),
							),
							'labels' => array(
									'en_US' => 'Status',
									'fr_FR' => 'Statut',
							),
					),
					'place_id' => array('definition' => 'commitment/property/place_id'),
					'account_name' => array(
							'definition' => 'inline',
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Name',
									'fr_FR' => 'Nom',
							),
					),
					'invoice_n_fn' => array('definition' => 'commitment/property/invoice_n_fn'),
					'year' => array('definition' => 'commitment/property/year'),
					'caption' => array(
							'type' => 'repository',
							'definition' => 'student/property/school_year',
							'labels' => array(
									'en_US' => 'School year',
									'fr_FR' => 'Année scolaire',
							),
					),
					'product_caption' => array('definition' => 'commitment/property/product_caption'),
					'account_id' => array('definition' => 'commitment/property/account_id'),
					'description' => array(
							'definition' => 'inline',
							'type' => 'textarea',
							'labels' => array(
									'en_US' => 'Description',
									'fr_FR' => 'Description',
							),
					),
					'quantity' => array('definition' => 'commitment/property/quantity'),
					'unit_price' => array('definition' => 'commitment/property/unit_price'),
					'amount' => array('definition' => 'commitment/property/amount'),
					'product_brand' => array(
							'definition' => 'inline',
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Brand',
									'fr_FR' => 'Marque',
							),
					),
					'property_1' => array(
							'type' => 'repository',
							'definition' => 'student/property/level',
							'labels' => array(
									'en_US' => 'Level',
									'fr_FR' => 'Niveau',
							),
					),
					'property_2' => array(
							'type' => 'repository',
							'definition' => 'student/property/specialty',
							'labels' => array(
									'en_US' => 'Specialty',
									'fr_FR' => 'Spécialité',
							),
					),
					'property_3' => array(
							'type' => 'repository',
							'definition' => 'student/property/boarding_school',
							'labels' => array(
									'en_US' => 'Boarding school',
									'fr_FR' => 'Internat',
							),
					),
					'including_options_amount' => array(
							'definition' => 'inline',
							'type' => 'number',
							'labels' => array(
									'en_US' => 'Amount',
									'fr_FR' => 'Montant',
							),
					),
					'invoice_identifier' => array('definition' => 'commitment/property/invoice_identifier'),
					'invoice_date' => array('definition' => 'commitment/property/invoice_date'),
					'tax_amount' => array('definition' => 'commitment/property/tax_amount'),
					'tax_inclusive' => array('definition' => 'commitment/property/tax_inclusive'),
					'default_means_of_payment' => array('definition' => 'commitment/property/default_means_of_payment'),

					'account_status' => array('definition' => 'commitment/property/account_status'),
					'account_date_1' => array('definition' => 'core_account/generic/property/date_1'),
					'account_date_2' => array('definition' => 'core_account/generic/property/date_2'),
					'account_date_3' => array('definition' => 'core_account/generic/property/date_3'),
					'account_date_4' => array('definition' => 'core_account/generic/property/date_4'),
					'account_date_5' => array('definition' => 'core_account/generic/property/date_5'),
					'account_property_1' => array('definition' => 'student/property/discipline'),
					'account_property_2' => array('definition' => 'core_account/generic/property/property_2'),
					'account_property_3' => array('definition' => 'core_account/generic/property/property_3'),
					'account_property_4' => array('definition' => 'core_account/p-pit-studies/property/property_4'),
					'account_property_5' => array('definition' => 'core_account/generic/property/property_5'),
					'account_property_6' => array('definition' => 'core_account/p-pit-studies/property/property_6'),
					'account_property_7' => array('definition' => 'student/property/class'),
					'account_property_8' => array('definition' => 'core_account/generic/property/property_8'),
					'account_property_9' => array('definition' => 'core_account/generic/property/property_9'),
					'account_property_10' => array('definition' => 'student/property/level'),
					'account_property_11' => array('definition' => 'core_account/generic/property/property_11'),
					'account_property_12' => array('definition' => 'core_account/generic/property/property_12'),
					'account_property_13' => array('definition' => 'student/property/contact_meeting_context'),
					'account_property_14' => array('definition' => 'core_account/generic/property/property_14'),
					'account_property_15' => array('definition' => 'core_account/p-pit-studies/property/property_15'),
					'account_property_16' => array('definition' => 'student/property/school_year'),
					'update_time' => array('definition' => 'commitment/property/update_time'),
			),
			'order' => 'school_year DESC',
			'todo' => array(
					'sales_manager' => array(
							'status' => array('selector' => 'in', 'value' => array('new')),
					),
			),
			'actions' => array(
					'' => array(
							'currentStatuses' => array(),
							'label' => array('en_US' => 'Update', 'fr_FR' => 'Modifier'),
							'properties' => array(
									'account_id' => 'update',
	//								'subscription_id' => 'update',
									'caption' => 'update',
									'description' => 'update',
									'quantity' => 'update',
									'unit_price' => 'update',
									'amount' => 'update',
									'identifier' => 'update',
									'comment' => 'update',
									'product_identifier' => 'update',
							),
					),
					'update' => array(
							'currentStatuses' => array('new' => null),
							'glyphicon' => 'glyphicon-edit',
							'label' => array('en_US' => 'Update', 'fr_FR' => 'Modifier'),
							'properties' => array(
									'status' => 'display',
									'account_id' => 'update',
									//								'subscription_id' => 'update',
									'caption' => 'update',
									'description' => 'update',
									'quantity' => 'update',
									'unit_price' => 'update',
									'amount' => 'update',
									'identifier' => 'update',
									'comment' => 'update',
									'product_identifier' => 'update',
							),
					),
					'delete' => array(
							'currentStatuses' => array('new' => null),
							'targetStatus' => 'deleted',
							'glyphicon' => 'glyphicon-trash',
							'label' => array('en_US' => 'Delete', 'fr_FR' => 'Supprimer'),
							'properties' => array(
							),
					),
					'confirm' => array(
							'currentStatuses' => array('new' => null),
							'targetStatus' => 'confirmed',
							'label' => array('en_US' => 'Confirm', 'fr_FR' => 'Confirmer'),
							'properties' => array(
							),
					),
					'reject' => array(
							'currentStatuses' => array('new' => null),
							'targetStatus' => 'rejected',
							'label' => array('en_US' => 'Reject', 'fr_FR' => 'Rejeter'),
							'properties' => array(
							),
					),
					'settle' => array(
							'currentStatuses' => array('approved' => null),
							'targetStatus' => 'settled',
							'label' => array('en_US' => 'Settle', 'fr_FR' => 'Régler'),
							'properties' => array(
							),
					),
			),
	),
		
	'commitment/index/p-pit-studies' => array(
			'title' => array('en_US' => 'P-PIT Commitments', 'fr_FR' => 'P-PIT Engagements'),
	),

	'commitment/search/p-pit-studies' => array(
			'title' => array('en_US' => 'Subscriptions', 'fr_FR' => 'Inscriptions'),
			'todoTitle' => array('en_US' => 'active', 'fr_FR' => 'actifs'),
			'main' => array(
					'place_id' => ['multiple' => true],
					'type' => ['multiple' => true],
					'status' => ['multiple' => true],
					'account_status' => [],
					'including_options_amount' => [],
					'account_name' => [],
					'caption' => ['multiple' => true],
			),
	),

	'commitment/list/p-pit-studies' => array(
			'place_id' => 'select',
			'account_name' => 'text',
			'caption' => 'text',
			'property_1' => 'select',
			'property_2' => 'select',
			'property_3' => 'select',
			'including_options_amount' => 'number',
			'status' => 'select',
			'account_status' => 'select',
			'update_time' => 'datetime',
	),
		
	'commitment/update/p-pit-studies' => array(
			'caption' => array('mandatory' => true),
			'account_id' => array('mandatory' => true),
			'description' => array('mandatory' => false),
			'property_1' => array('mandatory' => false),
			'property_2' => array('mandatory' => false),
			'property_3' => array('mandatory' => false),
	),

	'commitment/group/p-pit-studies' => array(
			'status' => [],
			'caption' => [],
			'description' => [],
	),

	'commitment/export/p-pit-studies' => array(
		'year' => 'A',
		'invoice_identifier' => 'B',
		'invoice_date' => 'C',
		'account_name' => 'D',
		'invoice_n_fn' => 'E',
		'caption' => 'F',
		'description' => 'G',
		'property_1' => 'H',
		'property_2' => 'I',
		'property_3' => 'J',
		'product_caption' => 'K',
		'unit_price' => 'L',
		'quantity' => 'M',
		'amount' => 'N',
		'including_options_amount' => 'O',
		'tax_amount' => 'P',
		'tax_inclusive' => 'Q',
		'default_means_of_payment' => 'R',
	),
	
	'commitment/invoice/p-pit-studies' => array(
			'header' => array(),
			'description' => array(
					array(
							'left' => array('en_US' => 'Caption', 'fr_FR' => 'Libellé'),
							'right' => array('en_US' => '%s school year %s', 'fr_FR' => 'Scolarité %s %s'),
							'params' => array('product_brand', 'caption'),
					),
					array(
							'left' => array('en_US' => 'Invoice date', 'fr_FR' => 'Date de facture'),
							'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
							'params' => array('date'),
					),
					array(
							'left' => array('en_US' => 'Student', 'fr_FR' => 'Elève'),
							'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
							'params' => array('account_name'),
					),
					array(
							'left' => array('en_US' => 'Place', 'fr_FR' => 'Centre'),
							'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
							'params' => array('place_caption'),
					),
			),
			'terms' => true,
	),

	// Account p-pit-studies
	
	'core_account/p-pit-studies/property/status' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
				'new' => array('en_US' => 'New', 'fr_FR' => 'Nouveau'),
				'interested' => array('en_US' => 'Intéressé', 'fr_FR' => 'A relancer'),
				'candidate' => array('en_US' => 'Condidate', 'fr_FR' => 'Candidat'),
				'answer' => array('en_US' => 'Answer to give', 'fr_FR' => 'Réponse à donner'),
				'conversion' => array('en_US' => 'To be converted', 'fr_FR' => 'A convertir'),
				'committed' => array('en_US' => 'Committed', 'fr_FR' => 'Engagé'),
				'visa' => array('en_US' => 'Waiting for a visa', 'fr_FR' => 'En attente de visa'),
				'active' => array('en_US' => 'Registered', 'fr_FR' => 'Inscrit'),
				'gone' => array('en_US' => 'Gone', 'fr_FR' => 'Parti'),
		),
		'labels' => array(
				'en_US' => 'Status',
				'fr_FR' => 'Statut',
		),
		'perspectives' => array(
				'contact' => array('new', 'interested', 'candidate', 'answer', 'conversion', 'gone'),
				'account' => array('committed', 'visa', 'active'),
		),
		'mandatory' => true,
	),
	
	'core_account/p-pit-studies/property/place_id' => array('definition' => 'core_account/generic/property/place_id'),
	'core_account/p-pit-studies/property/identifier' => array('definition' => 'core_account/generic/property/identifier'),
	'core_account/p-pit-studies/property/name' => array(
		'definition' => 'core_account/generic/property/name'
	),
	'core_account/p-pit-studies/property/photo_link_id' => array('definition' => 'core_account/generic/property/photo_link_id'),
	'core_account/p-pit-studies/property/basket' => array('definition' => 'core_account/generic/property/basket'),
	'core_account/p-pit-studies/property/contact_1_id' => array('definition' => 'core_account/generic/property/contact_1_id'),

	'core_account/p-pit-studies/property/n_title' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Title',
			'fr_FR' => 'Titre',
		),
	),
	
	'core_account/p-pit-studies/property/title_1' => array(
		'definition' => 'inline',
		'definition' => 'inline',
		'type' => 'title',
		'labels' => array(
			'en_US' => 'PERSONAL DATA',
			'fr_FR' => 'DONNEES PERSONNELLES',
		),
	),
	
	'core_account/p-pit-studies/property/n_first' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'First name',
			'fr_FR' => 'Prénom',
		),
	),
	
	'core_account/p-pit-studies/property/n_last' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Last name',
			'fr_FR' => 'Nom',
		),
	),
	
	'core_account/p-pit-studies/property/n_fn' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Name',
			'fr_FR' => 'Nom',
		),
	),
	
	'core_account/p-pit-studies/property/email' => array(
		'definition' => 'inline',
		'type' => 'email',
		'labels' => array(
			'en_US' => 'Email',
			'fr_FR' => 'Email',
		),
	),
	
	'core_account/p-pit-studies/property/tel_work' => array(
		'definition' => 'inline',
		'type' => 'phone',
		'labels' => array(
			'en_US' => 'Phone',
			'fr_FR' => 'Téléphone',
		),
	),
	
	'core_account/p-pit-studies/property/tel_cell' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Cellular',
			'fr_FR' => 'Mobile',
		),
	),

	'core_account/p-pit-studies/property/adr_street' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Address',
			'fr_FR' => 'Adresse',
		),
	),
	
	'core_account/p-pit-studies/property/adr_extended' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Complement',
			'fr_FR' => 'Complément',
		),
	),
	
	'core_account/p-pit-studies/property/adr_post_office_box' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Post office box',
			'fr_FR' => 'Boîte postale',
		),
	),
	
	'core_account/p-pit-studies/property/adr_zip' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Zip code',
			'fr_FR' => 'Code postal',
		),
	),
	
	'core_account/p-pit-studies/property/adr_city' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'City',
			'fr_FR' => 'Ville',
		),
	),
	
	'core_account/p-pit-studies/property/adr_state' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'State',
			'fr_FR' => 'Etat',
		),
	),
	
	'core_account/p-pit-studies/property/adr_country' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Country',
			'fr_FR' => 'Pays',
		),
	),
	
	'core_account/p-pit-studies/property/address' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Address',
			'fr_FR' => 'Adresse',
		),
	),
	
	'core_account/p-pit-studies/property/birth_date' => array('definition' => 'core_account/generic/property/birth_date'),	
	'core_account/p-pit-studies/property/gender' => array('definition' => 'core_account/generic/property/gender'),
	'core_account/p-pit-studies/property/nationality' => array('definition' => 'core_account/generic/property/nationality'),

	'core_account/p-pit-studies/property/n_title_2' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Father title',
			'fr_FR' => 'Titre père',
		),
	),
	'core_account/p-pit-studies/property/n_first_2' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Father first name',
			'fr_FR' => 'Prénom père',
		),
	),
	'core_account/p-pit-studies/property/n_last_2' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Father last name',
			'fr_FR' => 'Nom famille père',
		),
	),
	'core_account/p-pit-studies/property/n_fn_2' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Father name',
			'fr_FR' => 'Nom père',
		),
	),
	'core_account/p-pit-studies/property/email_2' => array(
		'definition' => 'inline',
		'type' => 'email',
		'labels' => array(
			'en_US' => 'Father email',
			'fr_FR' => 'Email père',
		),
	),
	'core_account/p-pit-studies/property/tel_work_2' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Father phone',
			'fr_FR' => 'Téléphone père',
		),
	),
	'core_account/p-pit-studies/property/tel_cell_2' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Father cell',
			'fr_FR' => 'Mobile père',
		),
	),
	'core_account/p-pit-studies/property/address_2' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Father address',
			'fr_FR' => 'Adresse père',
		),
	),
	'core_account/p-pit-studies/property/n_title_3' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Mother title',
			'fr_FR' => 'Titre mère',
		),
	),
	'core_account/p-pit-studies/property/n_first_3' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Mother first name',
			'fr_FR' => 'Prénom mère',
		),
	),
	'core_account/p-pit-studies/property/n_last_3' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Mother last name',
			'fr_FR' => 'Nom famille mère',
		),
	),
	'core_account/p-pit-studies/property/n_fn_3' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Mother name',
			'fr_FR' => 'Nom mère',
		),
	),
	'core_account/p-pit-studies/property/email_3' => array(
		'definition' => 'inline',
		'type' => 'email',
		'labels' => array(
			'en_US' => 'Mother email',
			'fr_FR' => 'Email mère',
		),
	),
	'core_account/p-pit-studies/property/tel_work_3' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Mother phone',
			'fr_FR' => 'Téléphone mère',
		),
	),
	'core_account/p-pit-studies/property/tel_cell_3' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Mother cell',
			'fr_FR' => 'Mobile mère',
		),
	),
	'core_account/p-pit-studies/property/address_3' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Mother address',
			'fr_FR' => 'Adresse mère',
		),
	),
	'core_account/p-pit-studies/property/n_title_4' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Legal repr. title',
			'fr_FR' => 'Titre repr. légal',
		),
	),
	'core_account/p-pit-studies/property/n_first_4' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Legal repr. first name',
			'fr_FR' => 'Prénom repr. légal',
		),
	),
	'core_account/p-pit-studies/property/n_last_4' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Legal repr. last name',
			'fr_FR' => 'Nom famille repr. légal',
		),
	),
	'core_account/p-pit-studies/property/n_fn_4' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Legal repr. name',
			'fr_FR' => 'Nom repr. légal',
		),
	),
	'core_account/p-pit-studies/property/email_4' => array(
		'definition' => 'inline',
		'type' => 'email',
		'labels' => array(
			'en_US' => 'Legal repr. email',
			'fr_FR' => 'Email repr. légal',
		),
	),
	'core_account/p-pit-studies/property/tel_work_4' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Legal repr. phone',
			'fr_FR' => 'Téléphone repr. légal',
		),
	),
	'core_account/p-pit-studies/property/tel_cell_4' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Legal repr. cell',
			'fr_FR' => 'Portable repr. légal',
		),
	),
	'core_account/p-pit-studies/property/address_4' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Legal repr. address',
			'fr_FR' => 'Adresse repr. légal',
		),
	),
	'core_account/p-pit-studies/property/n_title_5' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Contact abroad title',
			'fr_FR' => 'Titre contact étranger',
		),
	),
	'core_account/p-pit-studies/property/n_first_5' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Contact abroad first name',
			'fr_FR' => 'Prénom contact étranger',
		),
	),
	'core_account/p-pit-studies/property/n_last_5' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Contact abroad name',
			'fr_FR' => 'Nom contact étranger',
		),
	),
	'core_account/p-pit-studies/property/n_fn_5' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Contact abroad name',
			'fr_FR' => 'Nom contact étranger',
		),
	),
	'core_account/p-pit-studies/property/email_5' => array(
		'definition' => 'inline',
		'type' => 'email',
		'labels' => array(
			'en_US' => 'Contact abroad email',
			'fr_FR' => 'Email contact étranger',
		),
	),
	'core_account/p-pit-studies/property/tel_work_5' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Contact abroad phone',
			'fr_FR' => 'Téléphone contact étranger',
		),
	),
	'core_account/p-pit-studies/property/tel_cell_5' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Contact abroad cell',
			'fr_FR' => 'Portable contact étranger',
		),
	),
	'core_account/p-pit-studies/property/address_5' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Contact abroad address',
			'fr_FR' => 'Adresse contact étranger',
		),
	),
	
	'core_account/p-pit-studies/property/opening_date' => array('definition' => 'core_account/generic/property/opening_date'),
	'core_account/p-pit-studies/property/closing_date' => array('definition' => 'core_account/generic/property/closing_date'),
	'core_account/p-pit-studies/property/callback_date' => array('definition' => 'core_account/generic/property/callback_date'),
	'core_account/p-pit-studies/property/first_activation_date' => array('definition' => 'core_account/generic/property/first_activation_date'),
	
	'core_account/p-pit-studies/property/next_meeting_date' => array(
		'definition' => 'inline',
		'type' => 'datetime',
		'labels' => array(
			'en_US' => 'Subscription meeting_date',
			'fr_FR' => 'Date de RDV d\'inscription',
		),
	),
	
	'core_account/p-pit-studies/property/next_meeting_confirmed' => array(
		'definition' => 'inline',
		'type' => 'datetime',
		'labels' => array(
			'en_US' => 'Subscription meeting confirmed',
			'fr_FR' => 'RDV d\'inscription confirmé',
		),
	),
	
	'core_account/p-pit-studies/property/priority' => array('definition' => 'core_account/generic/property/priority'),
	'core_account/p-pit-studies/property/origine' => array('definition' => 'core_account/generic/property/origine'),
	'core_account/p-pit-studies/property/contact_history' => array('definition' => 'core_account/generic/property/contact_history'),
	'core_account/p-pit-studies/property/default_means_of_payment' => array('definition' => 'core_account/generic/property/default_means_of_payment'),
	'core_account/p-pit-studies/property/transfer_order_id' => array('definition' => 'core_account/generic/property/transfer_order_id'),
	'core_account/p-pit-studies/property/transfer_order_date' => array('definition' => 'core_account/generic/property/transfer_order_date'),
	'core_account/p-pit-studies/property/bank_identifier' => array('definition' => 'core_account/generic/property/bank_identifier'),
	
	'core_account/p-pit-studies/property/title_2' => array(
		'definition' => 'inline',
		'type' => 'title',
		'labels' => array(
			'en_US' => 'REGISTRATION DATA',
			'fr_FR' => 'DONNEES D\'INSCRIPTION',
		),
	),
	
	'core_account/p-pit-studies/property/property_1' => array('definition' => 'student/property/discipline'),
	
	'core_account/p-pit-studies/property/property_2' => array(
		'definition' => 'inline',
		'type' => 'time',
		'labels' => array(
			'en_US' => 'Admission meeting time',
			'fr_FR' => 'Heure RDV d\'admission',
		),
	),
	
	'core_account/p-pit-studies/property/property_3' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'student' => array('en_US' => 'Student', 'fr_FR' => 'Etudiant/Lycéen'),
			'employee' => array('en_US' => 'Employee', 'fr_FR' => 'Salarié'),
			'others' => array('en_US' => 'Others', 'fr_FR' => 'Autres'),
		),
		'labels' => array(
			'en_US' => 'Current situation',
			'fr_FR' => 'Situation actuelle',
		),
	),
	
	'core_account/p-pit-studies/property/property_4' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Languages',
			'fr_FR' => 'Langues',
		),
	),
	
	'core_account/p-pit-studies/property/property_5' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Options',
			'fr_FR' => 'Options',
		),
	),

	'core_account/p-pit-studies/property/property_6' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'Externe' => array('fr_FR' => 'Externe', 'fr_FR' => 'Externe'),
			'Interne' => array('fr_FR' => 'Internat', 'fr_FR' => 'Internat'),
			'Weekend' => array('fr_FR' => 'Internat + WE', 'fr_FR' => 'Internat + WE'),
			'Dimanche' => array('fr_FR' => 'Internat + dimanche', 'fr_FR' => 'Internat + dimanche'),
			'annual' => array('fr_FR' => 'Internat annuel', 'fr_FR' => 'Internat annuel'),
		),
		'labels' => array(
			'en_US' => 'Boarding-school',
			'fr_FR' => 'Internat',
		),
	),
	
	'core_account/p-pit-studies/property/property_7' => array('definition' => 'student/property/class'),
	
	'core_account/p-pit-studies/property/property_8' => array(
		'definition' => 'inline',
		'type' => 'date',
		'labels' => array(
			'en_US' => 'Admission meeting date',
			'fr_FR' => 'Date RDV d\'admission',
		),
	),
	
	'core_account/p-pit-studies/property/property_9' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Sport referent',
			'fr_FR' => 'Référent sportif',
		),
	),

	'core_account/p-pit-studies/property/property_10' => array('definition' => 'student/property/level'),
	
	'core_account/p-pit-studies/property/property_11' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Sport level',
			'fr_FR' => 'Niveau sportif',
		),
	),
	
	'core_account/p-pit-studies/property/property_12' => array(
		'definition' => 'inline',
		'type' => 'date',
		'labels' => array(
			'en_US' => 'Price communicated on',
			'fr_FR' => 'Tarif communiqué le',
		),
	),

	'core_account/p-pit-studies/property/property_13' => array('definition' => 'student/property/contact_meeting_context'),
	
	'core_account/p-pit-studies/property/property_14' => array(
		'definition' => 'inline',
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
			'en_US' => 'School level at registration',
			'fr_FR' => 'Niveau scolaire à l\'inscription',
		),
	),

	'core_account/p-pit-studies/property/property_15' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'initial' => array('en_US' => 'Initial training', 'fr_FR' => 'Formation initiale'),
			'part_time' => array('en_US' => 'Part time training', 'fr_FR' => 'Formation en alternance'),
			'part_time_initial' => array('en_US' => 'Part time/Initial training', 'fr_FR' => 'Formation initiale/en alternance'),
		),
		'labels' => array(
			'en_US' => 'Study choice',
			'fr_FR' => 'Rythme',
		),
	),
	
	'core_account/p-pit-studies/property/property_16' => array('definition' => 'student/property/school_year'),
	
	'core_account/p-pit-studies/property/json_property_1' => array(
		'definition' => 'inline',
		'type' => 'key_value',
		'labels' => array(
			'en_US' => 'Collected informations',
			'fr_FR' => 'Informations collectées',
		),
		'properties' => array(
			'updatedAt' => null,
			'type' => null,
			'state' => null,
			'levelOfEducation' => null,
			'minor' => null,
			'branch' => null,
			'currentDegree' => null,
			'birthDate' => null,
			'sponsorName' => null,
			'payForStudies' => null,
			'appName' => null,
			'createdAt' => null,
			'emailResult' => null,
			'emailSendex' => null,
			'emailDeliverable' => null,
			'emailOriginal' => null,
			'phoneOriginal' => null,
		),
	),
	
	'core_account/p-pit-studies/property/json_property_2' => array(
		'definition' => 'inline',
		'type' => 'array',
		'labels' => array(
			'en_US' => 'Wished domains',
			'fr_FR' => 'Domaines souhaités',
		),
		'properties' => array(
			'name' => null,
			'studyDomainOptions' => null,
		),
	),
	
	'core_account/p-pit-studies/property/json_property_3' => array(
		'definition' => 'inline',
		'type' => 'array',
		'labels' => array(
			'en_US' => 'Engagements',
			'fr_FR' => 'Engagements',
		),
		'properties' => array(
			'source' => null,
			'contextModel' => null,
			'contentModel' => null,
			'mailSentToUser' => null,
			'mailSentToSponsor' => null,
		),
	),
	
	'core_account/p-pit-studies/property/title_3' => array(
		'definition' => 'inline',
		'type' => 'title',
		'labels' => array(
			'en_US' => 'COMMENTS',
			'fr_FR' => 'COMMENTAIRES',
		),
	),
	
	'core_account/p-pit-studies/property/comment_1' => array(
		'definition' => 'inline',
		'type' => 'textarea',
		'labels' => array(
			'en_US' => 'Trainings comments',
			'fr_FR' => 'Commentaires formations',
		),
		'max_length' => 65535,
	),
	
	'core_account/p-pit-studies/property/comment_2' => array(
		'definition' => 'inline',
		'type' => 'textarea',
		'labels' => array(
			'en_US' => 'Competencies comments',
			'fr_FR' => 'Commentaires compétences',
		),
		'max_length' => 65535,
	),
	
	'core_account/p-pit-studies/property/comment_3' => array(
		'definition' => 'inline',
		'type' => 'textarea',
		'labels' => array(
			'en_US' => 'Experience comments',
			'fr_FR' => 'Commentaire Expérience',
		),
		'max_length' => 65535,
	),
	
	'core_account/p-pit-studies/property/comment_4' => array(
		'definition' => 'inline',
		'type' => 'textarea',
		'labels' => array(
			'en_US' => 'Motivations comments',
			'fr_FR' => 'Commentaires motivations',
		),
		'max_length' => 65535,
	),
	
	'core_account/p-pit-studies/property/update_time' => array(
		'definition' => 'inline',
		'type' => 'time',
		'labels' => array(
			'en_US' => 'Last update',
			'fr_FR' => 'Dernière mise à jour',
		),
	),
	
	'core_account/p-pit-studies' => array(
		'properties' => array(
			'title_1', 'title_2', 'title_3', 'status', 'place_id', 'identifier', 'name', 'photo_link_id', 'basket',
			'contact_1_id', 'n_title', 'n_first', 'n_last', 'n_fn', 'email', 'tel_work', 'tel_cell',
			'adr_street', 'adr_zip', 'adr_city', 'adr_country', 'address', 'birth_date', 'gender', 'nationality',
			'n_title_2', 'n_first_2', 'n_last_2', 'n_fn_2', 'email_2', 'tel_work_2', 'tel_cell_2', 'address_2',
			'n_title_3', 'n_first_3', 'n_last_3', 'n_fn_3', 'email_3', 'tel_work_3', 'tel_cell_3', 'address_3',
			'n_title_4', 'n_first_4', 'n_last_4', 'n_fn_4', 'email_4', 'tel_work_4', 'tel_cell_4', 'address_4',
			'n_title_5', 'n_first_5', 'n_last_5', 'n_fn_5', 'email_5', 'tel_work_5', 'tel_cell_5', 'address_5',
			'opening_date', 'closing_date', 'callback_date', 'first_activation_date', 'date_1', 'date_2', 'date_3', 'date_4', 'date_5', 'next_meeting_date', 'next_meeting_confirmed', 'priority', 'origine', 'contact_history',
			'default_means_of_payment', 'transfer_order_id', 'transfer_order_date', 'bank_identifier',
			'property_1', 'property_2', 'property_3', 'property_4', 'property_5', 'property_6', 'property_7', 'property_8',
			'property_9', 'property_10', 'property_11', 'property_12', 'property_13', 'property_14', 'property_15', 'property_16',
			'json_property_1', 'json_property_2', 'json_property_3',
			'comment_1', 'comment_2', 'comment_3', 'comment_4', 'update_time'
		),
		'order' => 'n_fn',
		'options' => ['internal_identifier' => true],
	),

	'core_account/index/p-pit-studies' => array(
			'title' => array('en_US' => 'P-PIT Commitments', 'fr_FR' => 'P-PIT Engagements'),
	),
	'core_account/search/p-pit-studies' => array(
			'title' => array('en_US' => 'Students', 'fr_FR' => 'Eleves'),
			'todoTitle' => array('en_US' => 'todo list', 'fr_FR' => 'todo list'),
			'properties' => array(
					'status' => ['multiple' => true],
					'place_id' => ['multiple' => true],
					'property_16' => ['multiple' => true],
					'basket' => ['multiple' => true],
					'opening_date' => [],
					'callback_date' => [],
					'first_activation_date' => [],
					'property_8' => [],
					'property_13' => ['multiple' => true],
					'next_meeting_date' => [],
					'next_meeting_confirmed' => ['type' => 'boolean'],
					'origine' => ['multiple' => true],
					'property_1' => ['multiple' => true],
					'property_7' => ['multiple' => true],
					'property_6' => ['multiple' => true],
					'property_15' => [],
					'n_fn' => [],
					'email' => [],
					'tel_cell' => [],
					'gender' => [],
					'property_14' => [],
			),
	),

	'core_account/list/p-pit-studies' => array(
			'properties' => array(
					'status' => ['color' => ['new' => 'LightGreen', 'interested' => 'LightSalmon', 'candidate' => 'LightBlue', 'answer' => 'LightSalmon', 'gone' => 'LightGrey']],
					'n_fn' => [],
					'tel_cell' => ['rendering' => 'phone'],
					'property_16' => [],
//					'basket' => [],
					'property_1' => ['rendering' => 'image'],
					'opening_date' => [],
					'callback_date' => [],
					'first_activation_date' => [],
					'property_8' => [],
					'property_13' => [],
					'next_meeting_date' => [],
//					'next_meeting_confirmed' => [],
//					'property_2' => [],
					'origine' => [],
					'property_7' => [],
//					'property_15' => [],
//					'identifier' => [],
					'place_id' => [],
			),
	),

	'core_account/todo/p-pit-studies/contact' => array(
		'filters' => array(
			'status' => ['new', 'interested'],
		),
		'order' => ['status' => 'DESC', 'opening_date' => 'ASC'],
	),
	
	'core_account/detail/p-pit-studies' => array(
			'title' => array('en_US' => 'Student sheet:', 'fr_FR' => 'FICHE ELEVE'),
			'displayAudit' => false,
			'tabs' => array(
					'contact_1' => array(
							'definition' => 'inline',
							'route' => 'account/update',
							'params' => array('type' => 'p-pit-studies'),
							'labels' => array('en_US' => 'Student', 'fr_FR' => 'Elève'),
					),
					'user' => array(
							'definition' => 'inline',
							'route' => 'account/updateUser',
							'params' => array('type' => 'p-pit-studies'),
							'labels' => array('en_US' => 'User account', 'fr_FR' => 'Compte utilisateur'),
					),
					'contact_2' => array(
							'definition' => 'inline',
							'route' => 'account/updateContact',
							'params' => array('type' => 'p-pit-studies', 'contactNumber' => 2),
							'labels' => array('en_US' => 'Father', 'fr_FR' => 'Père'),
					),
					'contact_3' => array(
							'definition' => 'inline',
							'route' => 'account/updateContact',
							'params' => array('type' => 'p-pit-studies', 'contactNumber' => 3),
							'labels' => array('en_US' => 'Mother', 'fr_FR' => 'Mère'),
					),
					'contact_4' => array(
							'definition' => 'inline',
							'route' => 'account/updateContact',
							'params' => array('type' => 'p-pit-studies', 'contactNumber' => 4),
							'labels' => array('en_US' => 'Other', 'fr_FR' => 'Autre'),
					),
			),
	),
	'core_account/update/p-pit-studies' => array(
			'place_id' => array('mandatory' => true),
			'status' => array('mandatory' => true),
			'identifier' => array('mandatory' => false),
			'name' => array('mandatory' => false),
			'property_16' => array('mandatory' => false),
			'basket' => array('mandatory' => false),
			'opening_date' => array('mandatory' => false),
			'callback_date' => array('mandatory' => false),
			'first_activation_date' => array('mandatory' => false),
			'origine' => array('mandatory' => false),
			'property_12' => array('mandatory' => false),
			'title_1' => null,
			'n_first' => array('mandatory' => true),
			'n_last' => array('mandatory' => true),
			'photo_link_id' => array('mandatory' => false),
			'email' => array('mandatory' => false),
			'tel_cell' => array('mandatory' => false),
			'tel_cell_2' => array('mandatory' => false),
			'tel_cell_3' => array('mandatory' => false),
			'adr_street' => array('mandatory' => false),
			'adr_zip' => array('mandatory' => false),
			'adr_city' => array('mandatory' => false),
			'gender' => array('mandatory' => false),
			'birth_date' => array('mandatory' => false),
			'nationality' => array('mandatory' => false),
			'property_8' => array('mandatory' => false),
			'property_2' => array('mandatory' => false),
			'property_13' => array('mandatory' => false),
			'next_meeting_date' => array('mandatory' => false),
			'next_meeting_confirmed' => array('mandatory' => false),
			'property_3' => array('mandatory' => false),
			'default_means_of_payment' => array('mandatory' => false),
			'transfer_order_id' => array('mandatory' => false), 
			'transfer_order_date' => array('mandatory' => false), 
			'bank_identifier' => array('mandatory' => false),
			'title_2' => null,
			'property_15' => array('mandatory' => false),
			'property_1' => array('mandatory' => false),
			'property_11' => array('mandatory' => false),
			'property_14' => array('mandatory' => false),
			'property_10' => array('mandatory' => false),
//			'opening_date' => array('mandatory' => false),
			'property_7' => array('mandatory' => false),
			'property_4' => array('mandatory' => false),
			'property_5' => array('mandatory' => false),
			'property_9' => array('mandatory' => false),
			'property_6' => array('mandatory' => false),
			'title_3' => null,
			'comment_1' => array('mandatory' => false),
//			'json_property_2' => array('readonly' => true),
//			'json_property_3' => array('readonly' => true),
			'comment_2' => array('mandatory' => false),
//			'json_property_4' => array('readonly' => true),
			'comment_3' => array('mandatory' => false),
//			'json_property_5' => array('readonly' => true),
			'comment_4' => array('mandatory' => false),
			'contact_history' => array('mandatory' => false),
			'json_property_1' => array('readonly' => true),
			'json_property_2' => array('readonly' => true),
			'json_property_3' => array('readonly' => true),
	),
	'core_account/updateContact/p-pit-studies' => array(
			'n_title' => array('mandatory' => false),
			'n_first' => array('mandatory' => true),
			'n_last' => array('mandatory' => true),
			'tel_work' => array('mandatory' => false),
			'tel_cell' => array('mandatory' => false),
			'email' => array('mandatory' => false),
			'adr_street' => array('mandatory' => false),
			'adr_extended' => array('mandatory' => false),
			'adr_post_office_box' => array('mandatory' => false),
			'adr_zip' => array('mandatory' => false),
			'adr_city' => array('mandatory' => false),
			'adr_state' => array('mandatory' => false),
			'adr_country' => array('mandatory' => false),
			'locale' => array('mandatory' => true),
	),

	'core_account/groupUpdate/p-pit-studies' => array(
			'status' => array('mandatory' => false),
			'callback_date' => array('mandatory' => false),
			'first_activation_date' => array('mandatory' => false),
			'property_8' => array('mandatory' => false),
			'property_13' => array('mandatory' => false),
			'property_16' => array('mandatory' => false),
			'property_15' => array('mandatory' => false),
			'property_7' => array('mandatory' => false),
			'contact_history' => array('mandatory' => false),
	),

	'core_account/indexCard/p-pit-studies' => array(
			'title' => array('en_US' => 'Student index card', 'fr_FR' => 'Fiche élève'),
			'header' => array(
					'place_id' => null,
					'status' => null,
					'origine' => null,
					'property_16' => null,
					'basket' => null,
			),
			'1st-column' => array(
				'title' => 'title_1',
				'rows' => array(
					'n_first' => array('mandatory' => true),
					'n_last' => array('mandatory' => true),
					'email' => array('mandatory' => false),
					'tel_cell' => array('mandatory' => false),
					'gender' => array('mandatory' => false),
					'birth_date' => array('mandatory' => false),
					'property_8' => array('mandatory' => false),
					'property_2' => array('mandatory' => false),
					'property_3' => array('mandatory' => false),
				),
			),
			'2nd-column' => array(
				'title' => 'title_2',
				'rows' => array(
					'property_15' => array('mandatory' => false),
					'property_1' => array('mandatory' => false),
					'property_10' => array('mandatory' => false),
					'property_7' => array('mandatory' => false),
					'property_4' => array('mandatory' => false),
					'property_5' => array('mandatory' => false),
					'property_9' => array('mandatory' => false),
					'property_6' => array('mandatory' => false),
				),
			),
			'pdfDetailStyle' => '
<style>
table.note-report {
	font-size: 1em;
	border: 1px solid gray;
}
table.note-report th {
	color: #FFF;
	font-weight: bold;
	text-align: center;
	vertical-align: center;
	border: 1px solid gray;
	background-color: #006169;
}

table.note-report td {
	color: #666;
	border: 1px solid gray;
}
</style>
',
	),
	'core_account/requestTypes/p-pit-studies' => array(
			'general_information' => array('en_US' => 'General information', 'fr_FR' => 'Information générale'),
	),
	'core_account/export/p-pit-studies' => array(
			'status' => [],
			'property_16' => [],
			'basket' => [],
			'opening_date' => [],
			'callback_date' => [],
			'first_activation_date' => [],
			'origine' => [],
			'n_first' => [],
			'n_last' => [],
			'property_8' => [],
			'property_1' => [],
			'property_15' => [],
			'place_id' => [],
			'email' => [],
			'address' => [],
			'gender' => [],
			'birth_date' => [],
			'tel_cell' => [],
			'property_7' => [],
			'property_6' => [],

			'default_means_of_payment' => [],
			'transfer_order_id' => [],
			'transfer_order_date' => [],
			'bank_identifier' => [],
		
			'n_title_2' => [],
			'n_first_2' => [],
			'n_last_2' => [],
			'tel_work_2' => [],
			'tel_cell_2' => [],
			'email_2' => [],
			'address_2' => [],
				
			'n_title_3' => [],
			'n_first_3' => [],
			'n_last_3' => [],
			'tel_work_3' => [],
			'tel_cell_3' => [],
			'email_3' => [],
			'address_3' => [],

			'n_title_4' => [],
			'n_first_4' => [],
			'n_last_4' => [],
			'tel_work_4' => [],
			'tel_cell_4' => [],
			'email_4' => [],
			'address_4' => [],

			'comment_1' => [],
			'comment_2' => [],
			'comment_3' => [],
			'comment_4' => [],

			'contact_history' => [],
	),

	'core_account/nomad/p-pit-studies' => array(
			'properties' => array(
					'n_first' => 'firstName',
					'n_last' => 'lastName',
					'gender' => 'gender',
					'email' => 'email',
					'tel_cell' => 'phone',
					'adr_zip' => 'zip',
					'adr_country' => 'country',
			),
	),

	// Product

	'core_product/p-pit-studies/property/status' => ['definition' => 'core_product/generic/property/status'],
	'core_product/p-pit-studies/property/place_id' => ['definition' => 'core_product/generic/property/place_id'],
	'core_product/p-pit-studies/property/identifier' => ['definition' => 'core_product/generic/property/identifier'],
	'core_product/p-pit-studies/property/caption' => ['definition' => 'core_product/generic/property/caption'],
	'core_product/p-pit-studies/property/description' => ['definition' => 'core_product/generic/property/description'],
	'core_product/p-pit-studies/property/variants' => ['definition' => 'core_product/generic/property/variants'],
	'core_product/p-pit-studies/property/tax_1_share' => ['definition' => 'core_product/generic/property/tax_1_share'],
	'core_product/p-pit-studies/property/tax_2_share' => ['definition' => 'core_product/generic/property/tax_2_share'],
	'core_product/p-pit-studies/property/tax_3_share' => ['definition' => 'core_product/generic/property/tax_3_share'],
	
	'core_product/search/p-pit-studies' => array(
		'title' => array('en_US' => 'Products', 'fr_FR' => 'Produits'),
		'todoTitle' => array('en_US' => 'todo list', 'fr_FR' => 'todo list'),
		'properties' => array(
			'place_id' => [],
			'status' => [],
			'identifier' => [],
			'caption' => [],
		),
	),
	
	'core_product/export/p-pit-studies' => array(
		'title' => array('en_US' => 'Products', 'fr_FR' => 'Produits'),
		'properties' => array(
//			'place_id' => 'A',
			'status' => 'B',
			'identifier' => 'C',
			'caption' => 'D',
			'description' => 'E',
			'variants' => 'F',
			'tax_1_share' => 'G',
			'tax_2_share' => 'H',
			'tax_3_share' => 'I',
		),
	),
	
	'commitmentAccount/contactForm/p-pit-studies' => array('definition' => 'customization/esi/commitmentAccount/contactForm/p-pit-studies'),

	'commitment/accountList/p-pit-studies' => array(
			'title' => array('en_US' => 'Registrations', 'fr_FR' => 'INSCRIPTIONS'),
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
									'student/acknowledgement' => array(
											'labels' => array('en_US' => 'Acknowledgement', 'fr_FR' => 'Accusé réception'),
									),
									'student/confirmation' => array(
											'labels' => array('en_US' => 'Confirmation', 'fr_FR' => 'Confirmation'),
									),
									'student/commitment' => array(
											'labels' => array('en_US' => 'Coverage', 'fr_FR' => 'Prise en charge'),
									),
									'student/attestation' => array(
											'labels' => array('en_US' => 'Attestation', 'fr_FR' => 'Attestation'),
									),
							),
					),
			),
	),
		
	'commitmentEvent/p-pit-studies' => array(
			'category' => array(
					'news_flash' => array(
							'labels' => array('en_US' => 'News flash', 'fr_FR' => 'Flash info'),
							'color' => array('green' => null),
					),
					'sport' => array(
							'labels' => array('en_US' => 'Sport', 'fr_FR' => 'Sport'),
							'color' => array('blue' => null),
					),
/*					'schooling' => array(
							'labels' => array('en_US' => 'Schooling', 'fr_FR' => 'Scolarité'),
							'color' => array('orange' => null),
					),*/
					'medical' => array(
							'labels' => array('en_US' => 'Medical', 'fr_FR' => 'Médical'),
							'color' => array('red' => null),
					),
			),
	),
	
	'commitmentEvent/index/p-pit-studies' => array(
			'title' => array('en_US' => 'P-PIT Studies', 'fr_FR' => 'P-PIT Studies'),
	),
	
	'commitmentEvent/search/p-pit-studies' => array(
			'title' => array('en_US' => 'Appointments', 'fr_FR' => 'Rendez-vous'),
			'todoTitle' => array('en_US' => 'To come', 'fr_FR' => 'A venir'),
			'searchTitle' => array('en_US' => 'Search', 'fr_FR' => 'Recherche'),
			'main' => array(
					'category' => 'select',
					'property_1' => 'select',
					'level' => 'select',
					'title' => 'contains',
			),
			'more' => array(
					'school_year' => 'select',
					'specialty' => 'select',
					'begin_time' => 'range',
					'end_time' => 'range',
			),
	),
	
	'commitmentEvent/list/p-pit-studies' => array(
			'begin_time' => 'date',
			'end_time' => 'date',
			'title' => 'text',
	),
	
	'commitmentEvent/update/p-pit-studies' => array(
			'criteria' => array(
					'property_1' => 'select',
					'property_4' => 'select',
					'place_id' => 'select',
			)
	),
		
	'commitmentNotification/p-pit-studies' => array(
			'category' => array(
					'news_flash' => array(
							'labels' => array('en_US' => 'News flash', 'fr_FR' => 'Flash infos'),
					),
					'sport' => array(
							'labels' => array('en_US' => 'Sport', 'fr_FR' => 'Sport'),
					),
/*					'schooling' => array(
							'labels' => array('en_US' => 'Schooling', 'fr_FR' => 'Scolarité'),
					),*/
					'boarding_school' => array(
							'labels' => array('en_US' => 'Boarding school', 'fr_FR' => 'Internat'),
					),
			),
	),

	'commitmentNotification/index/p-pit-studies' => array(
			'title' => array('en_US' => 'P-PIT Studies', 'fr_FR' => 'P-PIT Studies'),
	),

	'commitmentNotification/search/p-pit-studies' => array(
			'title' => array('en_US' => 'News flash', 'fr_FR' => 'Notifications'),
			'todoTitle' => array('en_US' => 'visible', 'fr_FR' => 'visibles'),
			'searchTitle' => array('en_US' => 'Search', 'fr_FR' => 'Recherche'),
			'main' => array(
					'property_1' => 'select',
					'property_4' => 'select',
					'title' => 'contains',
			),
			'more' => array(
					'category' => 'select',
					'property_5' => 'select',
					'begin_date' => 'date',
					'end_date' => 'date',
			),
	),
	
	'commitmentNotification/list/p-pit-studies' => array(
			'begin_date' => 'date',
			'end_date' => 'date',
			'title' => 'text',
	),
	
	'commitmentNotification/update/p-pit-studies' => array(
			'criteria' => array(
					'property_1' => 'select',
					'property_4' => 'select',
					'place_id' => 'select',
			)
	),

	'student/parameter/average_computation' => array(
			'reference_value' => 20,
	),

	'student/property/place' => array(
			'type' => 'select',
			'labels' => array(
					'en_US' => 'Center',
					'fr_FR' => 'Centre',
			),
	),

	'student/property/contact_meeting_context' => array(
			'type' => 'select',
			'modalities' => array(
					'detection-day' => array('en_US' => 'Detection day', 'fr_FR' => 'Journée de détection'),
					'detection-day-confirmed' => array('en_US' => 'Detection day', 'fr_FR' => 'Journée de détection confirmée'),
					'competitive-examination' => array('en_US' => 'Competitive examination', 'fr_FR' => 'Concours'),
					'competitive-examination-confirmed' => array('en_US' => 'Competitive examination', 'fr_FR' => 'Concours confirmé'),
					'appointment' => array('en_US' => 'Appointment', 'fr_FR' => 'Rendez-vous'),
					'appointment-confirmed' => array('en_US' => 'Appointment', 'fr_FR' => 'Rendez-vous confirmé'),
			),
			'labels' => array(
					'en_US' => 'Admission meeting context',
					'fr_FR' => 'Cadre RDV d\'admission',
			),
	),
		
	'student/property/discipline' => array(
			'type' => 'select',
			'modalities' => array(
					'football' => array('en_US' => 'Football', 'fr_FR' => 'Football'),
					'football-women' => array('en_US' => 'Women\'s Football', 'fr_FR' => 'Football féminin'),
					'rugby' => array('en_US' => 'Rugby', 'fr_FR' => 'Rugby'),
					'basketball' => array('en_US' => 'Basketball', 'fr_FR' => 'Basketball'),
					'handball' => array('en_US' => 'Handball', 'fr_FR' => 'Handball'),
					'equitation' => array('en_US' => 'Horse-riding', 'fr_FR' => 'Equitation'),
					'golf' => array('en_US' => 'Golf', 'fr_FR' => 'Golf'),
					'tennis' => array('en_US' => 'Tennis', 'fr_FR' => 'Tennis'),
					'table-tennis' => array('en_US' => 'Table tennis', 'fr_FR' => 'Tennis de table'),
					'danse' => array('en_US' => 'Danse', 'fr_FR' => 'Danse'),
					'figure-skating' => array('en_US' => 'Figure skating', 'fr_FR' => 'Patinage artistique'),
					'multisport' => array('en_US' => 'Multisport', 'fr_FR' => 'Multisport'),
					'car-racing' => array('en_US' => 'Car racing', 'fr_FR' => 'Sport automobile'),
					'cycling' => array('en_US' => 'Cycling', 'fr_FR' => 'Cyclisme'),
					'taekwondo' => array('en_US' => 'Tae kwon do', 'fr_FR' => 'Taekwondo'),
					'karate' => array('en_US' => 'Karate', 'fr_FR' => 'Karaté'),
			),
			'labels' => array(
					'en_US' => 'Sport',
					'fr_FR' => 'Sport',
			),
	),

	'student/property/level' => array(
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
					'en_US' => 'Target school level',
					'fr_FR' => 'Niveau scolaire à intégrer',
			),
	),

	'student/property/specialty' => array(
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

	'student/property/class' => array(
			'type' => 'select',
			'modalities' => array(
					'cm1_cm2' => array('fr_FR' => 'CM1/CM2'),
					'cm2' => array('fr_FR' => 'CM2'),
					'6e' => array('fr_FR' => '6e'),
					'5e' => array('fr_FR' => '5e'),
					'5e1' => array('fr_FR' => '5e1'),
					'5e2' => array('fr_FR' => '5e2'),
					'4e' => array('fr_FR' => '4e'),
					'4e1' => array('fr_FR' => '4e1'),
					'4e2' => array('fr_FR' => '4e2'),
					'3e' => array('fr_FR' => '3e'),
					'3e1' => array('fr_FR' => '3e1'),
					'3e2' => array('fr_FR' => '3e2'),
					'3ea' => array('fr_FR' => '3ème A'),
					'3eb' => array('fr_FR' => '3ème B'),
					'2nde' => array('fr_FR' => '2nde'),
					'2nde1' => array('fr_FR' => '2nde1'),
					'2nde2' => array('fr_FR' => '2nde2'),
					'2nde3' => array('fr_FR' => '2nde3'),
					'2nde4' => array('fr_FR' => '2nde4'),
					'2ndea' => array('fr_FR' => '2nde A'),
					'2ndeb' => array('fr_FR' => '2nde B'),
					'1ereL' => array('fr_FR' => '1ère L'),
					'1ereS' => array('fr_FR' => '1ère S'),
					'1ereES' => array('fr_FR' => '1ère ES'),
					'1ereSTMG' => array('fr_FR' => '1ère STMG'),
					'1ereSTMG1' => array('fr_FR' => '1ère STMG 1'),
					'1ereSTMG2' => array('fr_FR' => '1ère STMG 2'),
					'1ereSTMGa' => array('fr_FR' => '1ère STMG A'),
					'1ereSTMGb' => array('fr_FR' => '1ère STMG B'),
					'termL' => array('fr_FR' => 'Term. L'),
					'termS' => array('fr_FR' => 'Term. S'),
					'termES' => array('fr_FR' => 'Term. ES'),
					'termSTMG' => array('fr_FR' => 'Term. STMG'),
					'2ndeProCommerce' => array('fr_FR' => '2nde Pro Commerce'),
					'1ereProCommerce' => array('fr_FR' => '1ère Pro Commerce'),
					'termProCommerce' => array('fr_FR' => 'Term. Pro Commerce'),
					'1ereProGa' => array('fr_FR' => '1ère Pro GA'),
					'2ndeProService' => array('fr_FR' => '2nde Pro Service'),
					'1ereProVente' => array('fr_FR' => '1ère Pro Vente'),
					'termProVente' => array('fr_FR' => 'Term. Pro Vente'),
					'fle' => array('fr_FR' => 'FLE', 'level' => 'FLE'),
					'cap-vente' => array('fr_FR' => 'CAP Vente'),
					'bts1' => array('fr_FR' => 'BTS1'),
					'bts2' => array('fr_FR' => 'BTS2'),
					'bts-muc1' => array('fr_FR' => 'BTS MUC 1'),
					'bts-muc2' => array('fr_FR' => 'BTS MUC 2'),
					'bts-com2' => array('fr_FR' => 'BTS COM 2'),
					'l1-staps' => array('fr_FR' => 'L1 STAPS'),
					'stss' => array('fr_FR' => '1ère STSS'),
			),
			'labels' => array(
					'en_US' => 'Class',
					'fr_FR' => 'Classe',
			),
	),
		
	'student/property/boarding_school' => array( // Deprecated
			'type' => 'select',
			'modalities' => array(
					'Externe' => array('fr_FR' => 'Externe', 'fr_FR' => 'Externe'),
					'Interne' => array('fr_FR' => 'Internat', 'fr_FR' => 'Internat'),
					'Weekend' => array('fr_FR' => 'Internat + WE', 'fr_FR' => 'Internat + WE'),
					'Dimanche' => array('fr_FR' => 'Internat + dimanche', 'fr_FR' => 'Internat + dimanche'),
					'annual' => array('fr_FR' => 'Internat annuel', 'fr_FR' => 'Internat annuel'),
			),
			'labels' => array(
					'en_US' => 'Boarding-school',
					'fr_FR' => 'Internat',
			),
	),

	'core_account/p-pit-studies/property/property_6' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'Externe' => array('fr_FR' => 'Externe', 'fr_FR' => 'Externe'),
			'Interne' => array('fr_FR' => 'Internat', 'fr_FR' => 'Internat'),
			'Weekend' => array('fr_FR' => 'Internat + WE', 'fr_FR' => 'Internat + WE'),
			'Dimanche' => array('fr_FR' => 'Internat + dimanche', 'fr_FR' => 'Internat + dimanche'),
			'annual' => array('fr_FR' => 'Internat annuel', 'fr_FR' => 'Internat annuel'),
		),
		'labels' => array(
			'en_US' => 'Boarding-school',
			'fr_FR' => 'Internat',
		),
	),
	
	'student/property/school_year' => array(
			'type' => 'select',
			'modalities' => array(
					'2016-2017' => array('fr_FR' => '2016-2017', 'en_US' => '2016-2017'),
					'2017-2018' => array('fr_FR' => '2017-2018', 'en_US' => '2017-2018'),
					'2018-2019' => array('fr_FR' => '2018-2019', 'en_US' => '2018-2019'),
					'2019-2020' => array('fr_FR' => '2019-2020', 'en_US' => '2019-2020'),
					'2020-2021' => array('fr_FR' => '2020-2021', 'en_US' => '2020-2021'),
			),
			'labels' => array(
					'en_US' => 'School year',
					'fr_FR' => 'Année scolaire',
			),
	),
	'student/property/school_year/default' => '2018-2019',

	'student/property/school_period' => array(
			'type' => 'select',
			'modalities' => array(
					'Q1' => array('en_US' => 'Quarter 1', 'fr_FR' => '1er trimestre'),
					'Q2' => array('en_US' => 'Quarter 2', 'fr_FR' => '2e trimestre'),
					'Q3' => array('en_US' => 'Quarter 3', 'fr_FR' => '3e trimestre'),
			),
			'labels' => array(
					'en_US' => 'Period',
					'fr_FR' => 'Période',
			),
	),
	
//	'student/property/school_period/default' => 'Q1',
		
	'student/property/evaluationCategory' => array(
			'type' => 'select',
			'modalities' => array(
					'assessment' => array( 'en_US' => 'Assessment', 'fr_FR' => 'Contrôle'),
					'homework' => array('en_US' => 'Homework', 'fr_FR' => 'Devoirs maison'),
					'oral-test' => array('en_US' => 'Oral test', 'fr_FR' => 'Interrogation orale'),
					'written-test' => array('en_US' => 'Written test', 'fr_FR' => 'Interrogation écrite'),
					'participation' => array('en_US' => 'Participation', 'fr_FR' => 'Participation'),
					'mock-exam' => array('en_US' => '1st Mock exam', 'fr_FR' => '1er brevet blanc'),
					'mock-exam_2' => array('en_US' => '2nd Mock exam', 'fr_FR' => '2nd brevet blanc'),
					'mock-exam_3' => array('en_US' => '3rd Mock exam', 'fr_FR' => '3e brevet blanc'),
					'mock-bac' => array('en_US' => '1st Mock Baccalaureate', 'fr_FR' => '1er baccalauréat blanc'),
					'mock-bac_2' => array('en_US' => '2nd Mock Baccalaureate', 'fr_FR' => '2nd baccalauréat blanc'),
					'mock-bac_3' => array('en_US' => '3rd Mock Baccalaureate', 'fr_FR' => '3e baccalauréat blanc'),
					'mock-bts' => array('en_US' => '1st Mock BTS', 'fr_FR' => '1er BTS blanc'),
					'mock-bts_2' => array('en_US' => '2nd Mock BTS', 'fr_FR' => '2nd BTS blanc'),
					'mock-bts_3' => array('en_US' => '3rd Mock BTS', 'fr_FR' => '3e BTS blanc'),
					'cned' => array( 'en_US' => 'CNED', 'fr_FR' => 'CNED'),
					'cned_1' => array( 'en_US' => 'CNED Nbr 1', 'fr_FR' => 'CNED N°1'),
					'cned_2' => array( 'en_US' => 'CNED Nbr 2', 'fr_FR' => 'CNED N°2'),
					'cned_3' => array( 'en_US' => 'CNED Nbr 3', 'fr_FR' => 'CNED N°3'),
					'cned_4' => array( 'en_US' => 'CNED Nbr 4', 'fr_FR' => 'CNED N°4'),
					'cned_5' => array( 'en_US' => 'CNED Nbr 5', 'fr_FR' => 'CNED N°5'),
					'cned_6' => array( 'en_US' => 'CNED Nbr 6', 'fr_FR' => 'CNED N°6'),
					'cned_7' => array( 'en_US' => 'CNED Nbr 7', 'fr_FR' => 'CNED N°7'),
					'cned_8' => array( 'en_US' => 'CNED Nbr 8', 'fr_FR' => 'CNED N°8'),
					'cned_9' => array( 'en_US' => 'CNED Nbr 9', 'fr_FR' => 'CNED N°9'),
					'cned_10' => array( 'en_US' => 'CNED Nbr 10', 'fr_FR' => 'CNED N°10'),
					'cned_11' => array( 'en_US' => 'CNED Nbr 11', 'fr_FR' => 'CNED N°11'),
					'cned_12' => array( 'en_US' => 'CNED Nbr 12', 'fr_FR' => 'CNED N°12'),
			),
			'labels' => array(
					'en_US' => 'Evaluation category',
					'fr_FR' => 'Catégorie d\'évaluation',
			),
	),

	'student/property/exam' => array(
		'type' => 'select',
		'modalities' => array(
			'mock-exam' => [],
			'mock-exam_2' => [],
			'mock-exam_3' => [],
			'mock-bac' => [],
			'mock-bac_2' => [],
			'mock-bac_3' => [],
			'mock-bts' => [],
			'mock-bts_2' => [],
			'mock-bts_3' => [],
		),
		'labels' => array(
			'en_US' => 'Mock exam',
			'fr_FR' => 'Examen blanc',
		),
	),
	
	'student/property/reportMention' => array(
			'type' => 'select',
			'modalities' => array(
					20 => array( 'en_US' => 'Congratulations', 'fr_FR' => 'Félicitations'),
					16 => array( 'en_US' => 'Compliments', 'fr_FR' => 'Compliments'),
					12 => array( 'en_US' => 'Encouragements', 'fr_FR' => 'Encouragements'),
					8 => array( 'en_US' => 'Warning on work', 'fr_FR' => 'Avert. travail'),
					4 => array( 'en_US' => 'Warning on behaviour', 'fr_FR' => 'Avert. conduite'),
					1 => array( 'en_US' => 'Warning behaviour & work', 'fr_FR' => 'Avert. conduite & travail'),
			),
			'labels' => array(
					'en_US' => 'Mention',
					'fr_FR' => 'Mention',
			),
	),
		
	'student/property/school_subject' => array(
			'type' => 'select',
			'modalities' => array(
					'french' => array( 'en_US' => 'French (native)', 'fr_FR' => 'Français'),
					'philosophy' => array( 'en_US' => 'Philosophy', 'fr_FR' => 'Philosophie'),
					'mathematics' => array('en_US' => 'Mathematics', 'fr_FR' => 'Mathématiques'),
					'history-geography' => array('en_US' => 'History/geography', 'fr_FR' => 'Histoire/géographie'),
					'history' => array('en_US' => 'History', 'fr_FR' => 'Histoire'),
					'civics' => array('en_US' => 'Civics', 'fr_FR' => 'Instruction civique'),
					'physics-chemistry' => array('en_US' => 'Physics/chemistry', 'fr_FR' => 'Physique/chimie'),
					'life-science' => array('en_US' => 'Life sciences', 'fr_FR' => 'SVT'),
					'sciences' => array('en_US' => 'Sciences', 'fr_FR' => 'Sciences'),
					'english' => array('en_US' => 'English', 'fr_FR' => 'Anglais'),
					'english-toefl' => array('en_US' => 'English TOEFL', 'fr_FR' => 'Anglais TOEFL'),
					'applied-english' => array('en_US' => 'Applied english', 'fr_FR' => 'Anglais appliqué'),
					'german' => array('en_US' => 'German', 'fr_FR' => 'Allemand'),
					'arabian' => array('en_US' => 'Arabian', 'fr_FR' => 'Arabe'),
					'spanish' => array('en_US' => 'Spanish', 'fr_FR' => 'Espagnol'),
					'fle' => array('en_US' => 'French', 'fr_FR' => 'FLE'),
					'italien' => array('en_US' => 'Italian', 'fr_FR' => 'Italien'),
					'portuguese' => array('en_US' => 'Portuguese', 'fr_FR' => 'Portugais'),
					'chinese' => array('en_US' => 'Chinese', 'fr_FR' => 'Chinois'),
					'technology' => array('en_US' => 'Technology', 'fr_FR' => 'Technologie'),
					'computing' => array('en_US' => 'Computing', 'fr_FR' => 'Informatique'),
					'management-sciences' => array('en_US' => 'Management sciences', 'fr_FR' => 'Science de la gestion'),
					'eco-management' => array('en_US' => 'Economy / Management', 'fr_FR' => 'Economie / Gestion'),
					'marketing' => array('en_US' => 'Marketing', 'fr_FR' => 'Mercatique'),
					'management' => array('en_US' => 'Management', 'fr_FR' => 'Management'),
					'economics' => array('en_US' => 'Economics', 'fr_FR' => 'Economie / Droit'),
					'ess' => array('en_US' => 'Economic & social sciences', 'fr_FR' => 'SES'),
					'business' => array('en_US' => 'Business', 'fr_FR' => 'Commerce'),
					'sales' => array('en_US' => 'Sales', 'fr_FR' => 'Vente'),
					'management' => array('en_US' => 'Management', 'fr_FR' => 'Management'),
					'communication' => array('en_US' => 'Communication', 'fr_FR' => 'Communication'),
					'duc' => array('en_US' => 'Business unit development', 'fr_FR' => 'Développement de l’unité commerciale (DUC)'),

// Demande M. Volle
					'pfeg' => array('en_US' => 'PFEG', 'fr_FR' => 'PFEG'),
//
// Demande A Herrera
					'epi' => array('en_US' => 'EPI', 'fr_FR' => 'EPI'),
					'vsp' => array('en_US' => 'VSP', 'fr_FR' => 'VSP'),
//
					'applied-arts' => array('en_US' => 'Applied arts', 'fr_FR' => 'Arts appliqués'),
					'plastic-arts' => array('en_US' => 'Plastic arts', 'fr_FR' => 'Arts plastiques'),
					'music' => array('en_US' => 'Music', 'fr_FR' => 'Musique'),
// Demande E Moreau
					'dance-history' => array('en_US' => 'Dance history', 'fr_FR' => 'Histoire de la danse'),
//
					'specialite' => array('en_US' => 'Specialty', 'fr_FR' => 'Spécialité'),
					'spe-stmg' => array('en_US' => 'STMG specialty', 'fr_FR' => 'Spécialité STMG'),
					'spe-acrc' => array('en_US' => 'ACRC specialty', 'fr_FR' => 'Spécialité ACRC'),
					'spe-mguc' => array('en_US' => 'MGUC specialty', 'fr_FR' => 'Spécialité MGUC'),
					'spe-cge' => array('en_US' => 'CGE specialty', 'fr_FR' => 'Spécialité CGE'),
					'spe-pduc' => array('en_US' => 'PDUC specialty', 'fr_FR' => 'Spécialité PDUC'),
					'spe-mo' => array('en_US' => 'MO specialty', 'fr_FR' => 'Spécialité MO'),
					'spe-pse' => array('en_US' => 'PSE specialty', 'fr_FR' => 'Spécialité PSE'),
					'spe-bac-pro' => array('en_US' => 'Bac pro specialty', 'fr_FR' => 'Spécialité Bac Pro'),

					'physio_pathologie' => array('en_US' => 'Physio-pathology', 'fr_FR' => 'Physio-pathologie'),
					'medico-social' => array('en_US' => 'Medico-social', 'fr_FR' => 'Sciences médico-sociales'),
					'nutrition' => array('en_US' => 'Biologics and nutrition', 'fr_FR' => 'Biologie et nutrition'),
					'sanitary-social' => array('en_US' => 'Sanitary and social', 'fr_FR' => 'Sanitaire et sociales'),
					'customer-relationship' => array('en_US' => 'Customer and user relationship', 'fr_FR' => 'Relation aux clients et aux usagers'),
				
					'study-period' => array('en_US' => 'Study period', 'fr_FR' => 'Etude surveillée'),

					'school_life' => array('en_US' => 'School life', 'fr_FR' => 'Vie scolaire'),
					'sport' => array('en_US' => 'Sport', 'fr_FR' => 'Sport'),
			),
			'labels' => array(
					'en_US' => 'Subject',
					'fr_FR' => 'Matière'
			),
	),

	'student/property/prestation' => array(
			'type' => 'select',
			'modalities' => array(
					'training' => array('en_US' => 'Morning', 'fr_FR' => 'Matin'),
					'lunch' => array('en_US' => 'Lunch', 'fr_FR' => 'Déjeuner'),
					'evening' => array('en_US' => 'Evening', 'fr_FR' => 'Soir'),
					'weekend' => array('en_US' => 'Weekend', 'fr_FR' => 'Week-end'),
					'sunday_evening' => array('en_US' => 'Dimanche soir', 'fr_FR' => 'Sunday evening'),
			),
			'labels' => array(
					'en_US' => 'Appointment',
					'fr_FR' => 'Rendez-vous'
			),
	),
		
	'student/property/discipline_period' => array(
			'type' => 'select',
			'modalities' => array(
					'P1' => array('en_US' => 'Sept/oct', 'fr_FR' => 'Sept/oct'),
					'P2' => array('en_US' => 'Nov/dec', 'fr_FR' => 'Nov/Dec'),
					'P3' => array('en_US' => 'Jan/Feb', 'fr_FR' => 'Jan/Fév'),
					'P4' => array('en_US' => 'Mar/Apr', 'fr_FR' => 'Mars/Avr'),
					'P5' => array('en_US' => 'May/Jun', 'fr_FR' => 'Mai/Juin'),
			),
			'labels' => array(
					'en_US' => 'Period',
					'fr_FR' => 'Période',
			),
	),

	'student/index' => array(
			'title' => array('en_US' => 'P-PIT Studies', 'fr_FR' => 'P-PIT Studies'),
	),

	'student/search' => array(
			'title' => array('en_US' => 'Students', 'fr_FR' => 'Eleves'),
			'todoTitle' => array('en_US' => 'registered', 'fr_FR' => 'inscrits'),
			'main' => array(
					'place_id' => 'select',
					'property_16' => 'select',
					'property_1' => 'select',
					'property_7' => 'select',
					'property_6' => 'select',
					'name' => 'contains',
			),
	),

	'student/list' => array(
			'property_1' => 'image',
			'photo_link_id' => 'photo',
			'n_fn' => 'text',
			'property_7' => 'select',
			'property_2' => 'phone',
			'email' => 'email',
	),
		
	'student/acknowledgement' => array(
			'address1' => array('text' => '%s %s %s', 'params' => array('invoicing_n_title', 'invoicing_n_last', 'invoicing_n_first')),
			'address2' => array('text' => '%s', 'params' => array('adr_street')),
			'address3' => array('text' => '%s %s', 'params' => array('adr_zip', 'adr_city')),
			'address4' => array('text' => '%s', 'params' => array('adr_country')),
			'address6' => array('text' => '%s, le %s', 'params' => array('place', 'date')),
			'title' => array('text' => 'ACCUSE DE RECEPTION', 'params' => array()),
			'paragraph1a' => array('text' => 'Chers parents,', 'params' => array()),
			'paragraph3a' => array('text' => 'Nous avons bien reçu l’inscription de %s %s en Sport Etudes à %s pour l’année scolaire %s et nous vous en remercions.', 'params' => array('n_first', 'n_last', 'place', 'school_year')),
			'paragraph5a' => array('text' => 'Vous trouverez ci-joint :', 'params' => array()),
			'paragraph7a' => array('text' => '- La confirmation d’inscription', 'params' => array()),
			'paragraph8a' => array('text' => '- Le trousseau', 'params' => array()),
			'paragraph9a' => array('text' => '- La facture', 'params' => array()),
			'paragraph10a' => array('text' => '- La fiche sanitaire de liaison (à remplir et à faire signer par un médecin, et nous la retourner avant la rentrée).', 'params' => array()),
			'paragraph11a' => array('text' => '- Les informations sur la rentrée scolaire, les vacances scolaires et la liste des documents à nous fournir avant la rentrée.', 'params' => array()),
			'paragraph12a' => array('text' => '- Le calendrier de l’année et la liste des fournitures scolaires.', 'params' => array()),
			'paragraph14a' => array('text' => 'Restant, à votre entière disposition, nous vous prions d’agréer, Chers parents, nos salutations sportives.', 'params' => array()),
	),

	'student/attestation' => array(
			'address1' => array('text' => '%s %s %s', 'params' => array('invoicing_n_title', 'invoicing_n_last', 'invoicing_n_first')),
			'address2' => array('text' => '%s', 'params' => array('invoicing_adr_street')),
			'address3' => array('text' => '%s %s', 'params' => array('invoicing_adr_zip', 'invoicing_adr_city')),
			'address4' => array('text' => '%s', 'params' => array('invoicing_adr_country')),
			'address6' => array('text' => '%s, le %s', 'params' => array('place', 'date')),
			'title' => array('text' => 'ATTESTATION SCOLAIRE', 'params' => array()),
			'paragraph1a' => array('text' => 'Notre Sports Etudes propose aux élèves de la classe de 6ème à la Terminale de suivre les cours du Centre National d’Etudes à Distance (CNED). Le CNED est un collège et lycée public (mêmes assurances scolaires, mêmes équivalences pour réintégrer un établissement réglementé classique). Nous apportons en complément un corps enseignant qui utilise les cours du CNED, qui supervise les études de chaque élève et qui vérifie que les devoirs sont réalisés en fonction du calendrier fourni par le CNED.', 'params' => array()),
			'paragraph3a' => array('text' => 'CNED de Rouen (collège public)', 'params' => array()),
			'paragraph4a' => array('text' => 'BP 288', 'params' => array()),
			'paragraph5a' => array('text' => '76137 MONT ST AIGNAN', 'params' => array()),
			'paragraph6a' => array('text' => 'Cedex Tel : 02 35 59 87 95', 'params' => array()),
			'paragraph8a' => array('text' => 'CNED de Rennes (lycée public)', 'params' => array()),
			'paragraph9a' => array('text' => '7 Rue du Clos Courtel', 'params' => array()),
			'paragraph10a' => array('text' => '35050 RENNES Cedex 09', 'params' => array()),
			'paragraph11a' => array('text' => 'Tel : 02 99 63 03 71', 'params' => array()),
			'paragraph13a' => array('text' => '%s %s sera inscrit en classe de %s.', 'params' => array('n_first', 'n_last', 'school_level')),
			'paragraph15a' => array('text' => 'Pour faire valoir ce que de droit.', 'params' => array()),
			'signature1' => array('text' => 'Fait à Boulogne Billancourt, le %s', 'params' => array('date')),
			'signature3' => array('text' => 'Thierry DERKX', 'params' => array()),
			'signature5' => array('text' => 'Directeur', 'params' => array()),
	),

	'student/commitment' => array(
			'address1' => array('text' => '%s %s %s', 'params' => array('invoicing_n_title', 'invoicing_n_last', 'invoicing_n_first')),
			'address2' => array('text' => '%s', 'params' => array('invoicing_adr_street')),
			'address3' => array('text' => '%s %s', 'params' => array('invoicing_adr_zip', 'invoicing_adr_city')),
			'address4' => array('text' => '%s', 'params' => array('invoicing_adr_country')),
			'address6' => array('text' => '%s, le %s', 'params' => array('place', 'date')),
			'title' => array('text' => 'ENGAGEMENT DE PRISE EN CHARGE', 'params' => array()),
			'paragraph1a' => array('text' => 'Je soussigné,', 'params' => array()),
			'paragraph2a' => array('text' => 'Monsieur Thierry DERKX, Directeur de F.M. SPORTS ETUDES', 'params' => array()),
			'paragraph4a' => array('text' => 'Nationalité : Française', 'params' => array()),
			'paragraph5a' => array('text' => 'Né le 29 mars 1964', 'params' => array()),
			'paragraph7a' => array('text' => 'Adresse : 87, rue du château - 92100 - Boulogne Billancourt France', 'params' => array()),
			'paragraph8a' => array('text' => 'Numéro de téléphone travail : 01 39 71 12 12', 'params' => array()),
			'paragraph10a' => array('text' => 'M’engage à héberger %s %s', 'params' => array('n_first', 'n_last')),
			'paragraph12a' => array('text' => 'dans notre résidence du SPORTS ETUDES située : 15 Quai Rennequin Sualem - 78380 Bougival - France, en pension complète du lundi au vendredi, avec possibilité de rester en internat les week-ends également.', 'params' => array('n_first', 'n_last')),
			'paragraph14a' => array('text' => '%s %s, né le %s, est inscrit en SPORTS ETUDES section %s pour l’année scolaire %s. Il sera inscrit en parallèle au CNED (L’Education Nationale Française) en classe de %s.', 'params' => array('n_first', 'n_last', 'birth_date', 'sport', 'school_year', 'school_level')),
			'paragraph16a' => array('text' => 'Fait à la demande de l’intéressé,', 'params' => array()),
			'signature1' => array('text' => 'Thierry DERKX', 'params' => array()),
			'signature3' => array('text' => 'Directeur', 'params' => array()),
	),
		
	'student/confirmation' => array(
			'address1' => array('text' => '%s %s %s', 'params' => array('invoicing_n_title', 'invoicing_n_last', 'invoicing_n_first')),
			'address2' => array('text' => '%s', 'params' => array('invoicing_adr_street')),
			'address3' => array('text' => '%s %s', 'params' => array('invoicing_adr_zip', 'invoicing_adr_city')),
			'address4' => array('text' => '%s', 'params' => array('invoicing_adr_country')),
			'address6' => array('text' => '%s, le %s', 'params' => array('place', 'date')),
			'title' => array('text' => 'CONFIRMATION D\'INSCRIPTION', 'params' => array()),
			'paragraph1a' => array('text' => 'SPORTS ETUDES ACADEMY certifie que l’élève dont les coordonnées figurent ci-dessous :', 'params' => array()),
			'paragraph4a' => array('text' => 'Nom :', 'params' => array()),
			'paragraph4b' => array('text' => '%s', 'params' => array('n_last')),
			'paragraph6a' => array('text' => 'Prénom :', 'params' => array()),
			'paragraph6b' => array('text' => '%s', 'params' => array('n_first')),
			'paragraph8a' => array('text' => 'Adresse :', 'params' => array()),
			'paragraph8b' => array('text' => '%s', 'params' => array('adr_street')),
			'paragraph9a' => array('text' => '', 'params' => array()),
			'paragraph9b' => array('text' => '%s %s', 'params' => array('adr_zip', 'adr_city')),
			'paragraph10a' => array('text' => '', 'params' => array()),
			'paragraph10b' => array('text' => '%s', 'params' => array('adr_country')),
			'paragraph12a' => array('text' => 'Date de naissance :', 'params' => array()),
			'paragraph12b' => array('text' => '%s', 'params' => array('birth_date')),
			'paragraph14a' => array('text' => 'Est inscrit à nos cours SPORTS ETUDES pour l’année scolaire %s section %s en classe de %s à %s.', 'params' => array('caption', 'sport', 'class', 'place')),
			'signature1' => array('text' => 'Thierry DERKX', 'params' => array()),
			'signature3' => array('text' => 'Directeur', 'params' => array()),
	),

	'student/report' => array(
			
			'header' => array(
					array(
							'format' => array('en_US' => '%s', 'fr_FR' => '%s'),
							'params' => array('name'),
					),
			),
			
			'description' => array(
					array(
							'left' => array('en_US' => 'Student', 'fr_FR' => 'Elève'),
							'right' => array('en_US' => '%s - %s - %s', 'fr_FR' => '%s - %s - %s'),
							'params' => array('n_fn', 'property_1', 'property_6'),
					),
					array(
							'left' => array('en_US' => 'Class', 'fr_FR' => 'Classe'),
							'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
							'params' => array('property_7'),
					),
					array(
							'left' => array('en_US' => 'Birth date', 'fr_FR' => 'Date de naissance'),
							'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
							'params' => array('birth_date'),
					),
					array(
							'left' => array('en_US' => 'Class size', 'fr_FR' => 'Effectif'),
							'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
							'params' => array('class_size'),
					),
			),

			'detailHeader' => array(
					'html' => '
<table class="table note-report">
	<tr>
		<th rowspan="2" style="width: 15%%">%s</th>
		<th rowspan="2" style="width: 10%%">%s</th>
		<th rowspan="2" style="width: 10%%">%s</th>
		<th colspan="3" style="width: 24%%">%s</th>
	   	<th rowspan="2" colspan="2" style="width: 41%%">%s</th>
	</tr>
    <tr>
    	<th style="width: 8%%">%s</th>
	   	<th style="width: 8%%">%s</th>
		<th style="width: 8%%">%s</th>
    </tr>
%s
</table>',
					'params' => array(
							array('en_US' => 'Subject', 'fr_FR' => 'Matière'),
							array('en_US' => 'Weight', 'fr_FR' => 'Coef. / Crédits'),
							array('en_US' => 'Student', 'fr_FR' => 'Elève.'),
							array('en_US' => 'Class', 'fr_FR' => 'Classe'),
							array('en_US' => 'Assessments', 'fr_FR' => 'Appréciations'),
							array('en_US' => 'Min.', 'fr_FR' => 'Min.'),
							array('en_US' => 'Avg.', 'fr_FR' => 'Moy.'),
							array('en_US' => 'Max.', 'fr_FR' => 'Max.'),
							'rows' => null,
					),
			),

			'detailRow' => array(
					'html' => '
<tr %s>
	<td style="width: 15%%">%s<br><span style="font-weight: normal">%s</span></td>
	<td style="width: 10%%" align="right">%s</td>
	<td style="width: 10%%; font-size: 1.2em; font-weight: bold" align="right">%s</td>
	<td style="width: 8%%" align="right">%s</td>
	<td style="width: 8%%" align="right">%s</td>
	<td style="width: 8%%" align="right">%s</td>
	<td style="width: 41%%">%s</td>
</tr>',
					'params' => array('color', 'subject', 'n_fn', 'weight', 'value', 'lower_note', 'average_note', 'higher_note', 'assessment'),
			),

			'signatureFrame' => array(
					'html' => '
<table class="table note-report">
    <tr>
    	<td style="width: 70%%">%s</td>
    	<td style="width: 30%%">%s</td>
	</tr>
</table>',
			),

			'withoutMentionFrame' => array(
				'html' => '
<table class="table note-report">
    <tr>
    	<td style="width: 100%%">%s</td>
	</tr>
</table>',
			),

			'evaluationHeader' => array(
					'html' => '
<table class="table note-report">
	<tr>
		<th rowspan="2" style="width: 36%%">%s</th>
		<th rowspan="2" style="width: 10%%">%s</th>
		<th rowspan="2" style="width: 12%%">%s</th>
		<th colspan="3" style="width: 30%%">%s</th>
		<th rowspan="2" style="width: 12%%">%s</th>
<!--		<th rowspan="2" style="width: 31%%">s</th> -->
	</tr>
    <tr>
    	<th style="width: 10%%">%s</th>
	   	<th style="width: 10%%">%s</th>
		<th style="width: 10%%">%s</th>
	</tr>
%s
</table>',
					'params' => array(
							array('en_US' => 'Subject', 'fr_FR' => 'Matière'),
							array('en_US' => 'Weight', 'fr_FR' => 'Coef.'),
							array('en_US' => 'Student', 'fr_FR' => 'Elève.'),
							array('en_US' => 'Class', 'fr_FR' => 'Classe'),
							array('en_US' => 'Date', 'fr_FR' => 'Date'),
//							array('en_US' => 'Observations', 'fr_FR' => 'Observations'),
							array('en_US' => 'Min.', 'fr_FR' => 'Min.'),
							array('en_US' => 'Avg.', 'fr_FR' => 'Moy.'),
							array('en_US' => 'Max.', 'fr_FR' => 'Max.'),
							'rows' => null,
					),
			),

			'evaluationSubject' => array(
					'html' => '
<tr %s>
	<td colspan="8" style="font-weight: bold">%s</td>
</tr>',
					'params' => array('color', 'subject'),
			),

			'evaluationRow' => array(
					'html' => '
<tr %s>
	<td style="width: 36%%">%s</td>
	<td style="width: 10%%" align="right">%s</td>
	<td style="width: 12%%; font-size: 1.2em; font-weight: bold" align="right">%s</td>
	<td style="width: 10%%" align="right">%s</td>
	<td style="width: 10%%" align="right">%s</td>
	<td style="width: 10%%" align="right">%s</td>
	<td style="width: 12%%; font-size: 0.8em" align="right">%s</td>
<!--	<td style="width: 31%%">s</td> -->
</tr>',
					'params' => array('color', 'subject', 'n_fn', 'weight', 'value', 'lower_note', 'average_note', 'higher_note', 'distribution'/*, 'assessment'*/),
			),

			'absenceHeader' => array(
					'html' => '
<table class="table note-report">
	<tr>
		<th style="width: 10%%">%s</th>
		<th style="width: 15%%">%s</th>
		<th style="width: 15%%">%s</th>
		<th style="width: 15%%">%s</th>
		<th style="width: 15%%">%s</th>
		<th style="width: 30%%">%s</th>
	</tr>
%s
</table>',
					'params' => array(
							array('en_US' => 'Type', 'fr_FR' => 'Type'),
							array('en_US' => 'Subject', 'fr_FR' => 'Matière'),
							array('en_US' => 'Period', 'fr_FR' => 'Période'),
							array('en_US' => 'Duration', 'fr_FR' => 'Durée'),
							array('en_US' => 'Motive', 'fr_FR' => 'Motif'),
							array('en_US' => 'Observations', 'fr_FR' => 'Observations'),
							'rows' => null,
					),
			),
			
			'absenceRow' => array(
					'html' => '
<tr %s>
	<td style="width: 10%%">%s</td>
	<td style="width: 15%%">%s</td>
	<td style="width: 15%%">%s</td>
	<td style="width: 15%%; text-align: right">%s</td>
	<td style="width: 15%%">%s</td>
	<td style="width: 30%%">%s</td>
</tr>',
					'params' => array('color', 'subject', 'period', 'motive', 'observations'),
			),

			'pdfDetailStyle' => '
<style>
table.note-report {
	font-size: 1em;
	border: 1px solid gray;
}
table.note-report th {
	color: #FFF;
	font-weight: bold;
	text-align: center;
	vertical-align: center;
	border: 1px solid gray;
	background-color: #006169;
}

table.note-report td {
	color: #666;
	border: 1px solid gray;
}

table.note-report td.subject {
	font-weight: bold;
}

table.note-report tr.period {
	background-color:#DDD;
}
</style>
',

			'evaluationSignatureFrame' => array(
					'html' => '
<table class="table note-report">
    <tr>
    	<td style="width: 100%%">%s</td>
	</tr>
</table>',
					),
	),

	'event/type' => array(
			'type' => 'select',
			'modalities' => array(
					'calendar' => array('en_US' => 'Planning', 'fr_FR' => 'Planning'),
			),
			'labels' => array(
					'en_US' => 'Type',
					'fr_FR' => 'Type',
			),
			'column' => 'type',
	),

	// Calendar event

	'event/calendar/property/property_1' => array('definition' => 'student/property/school_year'),
	'event/calendar/property/property_2' => array('definition' => 'student/property/class'),
	'event/calendar/property/property_3' => array('definition' => 'student/property/school_subject'),

	'event/calendar/property/vcard_id' => array( // Deprecated
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Vcard',
			'fr_FR' => 'Vcard',
		),
	),
	
	'event/calendar' => array(
		'statuses' => array(),
		'dimensions' => array(),
		'indicators' => array(),
		'properties' => array(
			'status', 'type', 'place_id', 'place_caption', 'vcard_id', 'n_fn', 'category', 'subcategory', 'identifier', 'caption', 'description',
			'begin_date', 'end_date', 'day_of_week', 'day_of_month', 'exception_1', 'exception_2', 'exception_3', 'exception_4', 'begin_time', 'end_time', 'time_zone', 'location', 'latitude', 'longitude',
			'value', 'comments',
			'property_1', 'property_2', 'property_3', 
			'update_time',
		),
		'options' => ['calendar' => true, 'account_type' => 'teacher'],
	),
	
	'event/index/calendar' => array(
		'title' => array('default' => 'Planning', 'fr_FR' => 'Planning'),
	),
	
	'event/search/calendar' => array(
		'title' => array('default' => 'Planning', 'fr_FR' => 'Planning'),
		'todoTitle' => array('default' => 'recent', 'fr_FR' => 'récents'),
		'searchTitle' => array('default' => 'search', 'fr_FR' => 'recherche'),
		'properties' => array(
			'place_id' => [],
			'identifier' => [],
			'value' => [],
			'property_1' => [],
			'property_2' => [],
			'n_fn' => [],
			'property_3' => [],
			'caption' => [],
			'day_of_week' => [],
			'begin_date' => [],
			'begin_time' => [],
		),
	),
	
	'event/list/calendar' => array(
		'place_id' => [],
		'property_1' => [],
		'n_fn' => [],
		'property_2' => [],
		'property_3' => [],
		'category' => [],
		'subcategory' => [],
		'identifier' => [],
		'caption' => [],
		'value' => [],
		'begin_date' => [],
		'end_date' => [],
		'day_of_week' => [],
		'day_of_month' => [],
		'begin_time' => [],
		'end_time' => [],
		'comments' => [],
		'update_time' => [],
	),
	
	'event/detail/calendar' => array(
		'title' => array('default' => 'Event detail', 'fr_FR' => 'Détail de l\'évènement'),
		'displayAudit' => true,
	),
	
	'event/update/calendar' => array(
		'status' => ['mandatory' => true],
		'place_id' => [],
		'property_1' => [],
		'n_fn' => [],
		'property_2' => [],
		'property_3' => [],
		'caption' => array('mandatory' => false),
		'description' => [],
		'day_of_week' => array('mandatory' => false),
		'begin_date' => array('mandatory' => false),
		'begin_time' => array('mandatory' => false),
		'end_date' => array('mandatory' => false),
		'end_time' => array('mandatory' => false),
		'exception_1' => array('mandatory' => false),
		'exception_2' => array('mandatory' => false),
		'exception_3' => array('mandatory' => false),
		'exception_4' => array('mandatory' => false),
		'location' => array('mandatory' => false),
	),
	
	'event/export/calendar' => array(
		'status' => 'A',
		'type' => 'B',
		'place_id' => 'C',
		'property_1' => 'D',
		'n_fn' => 'E',
		'property_2' => 'F',
		'property_3' => 'G',
		'category' => 'H',
		'subcategory' => 'I',
		'place_caption' => 'J',
		'identifier' => 'K',
		'caption' => 'L',
		'description' => 'M',
		'value' => 'N',
	),
	
	'absence' => array(
			'types' => array(
					'sport' => array(
							'labels' => array('en_US' => 'Sport', 'fr_FR' => 'Sport'),
							'subject' => array(
									'type' => 'select',
									'labels' => array('en_US' => 'Date', 'fr_FR' => 'Rendez-vous'),
									'modalities' => array(
											'training' => array('en_US' => 'Training', 'fr_FR' => 'Entrainement'),
											'footing' => array('en_US' => 'Footing', 'fr_FR' => 'Footing'),
									),
							),
					),
					'schooling' => array(
							'labels' => array('en_US' => 'Schooling', 'fr_FR' => 'Scolarité'),
							'subject' => array(
									'type' => 'select',
									'labels' => array('en_US' => 'Subject', 'fr_FR' => 'Matière'),
									//'modalities' => Listed in 'student/property/school_subject'
							),
					),
					'boarding_school' => array(
							'labels' => array('en_US' => 'Boarding school', 'fr_FR' => 'Internat'),
							'subject' => array(
									'type' => 'select',
									'labels' => array('en_US' => 'Prestation', 'fr_FR' => 'Prestation'),
									'modalities' => array(
											'morning' => array('en_US' => 'Morning', 'fr_FR' => 'Matin'),
											'lunch' => array('en_US' => 'Lunch', 'fr_FR' => 'Déjeuner'),
											'evening' => array('en_US' => 'Evening', 'fr_FR' => 'Soir'),
											'weekend' => array('en_US' => 'Weekend', 'fr_FR' => 'Week-end'),
											'sunday_evening' => array('en_US' => 'Sunday evening', 'fr_FR' => 'Dimanche soir'),
									),
							),
					),
			),
			'properties' => array(
					'place_id' => array(
							'definition' => 'inline',
							'type' => 'select',
							'modalities' => array(
									'2pit' => array('fr_FR' => 'P-PIT', 'en_US' => '2PIT'),
							),
							'labels' => array(
									'en_US' => 'Center',
									'fr_FR' => 'Centre',
							),
					),
					'category' => array(
							'definition' => 'inline',
							'type' => 'select',
							'modalities' => array(
									'absence' => array('en_US' => 'Absence', 'fr_FR' => 'Absence'),
									'lateness' => array('en_US' => 'Lateness', 'fr_FR' => 'Retard'),
							),
							'labels' => array(
									'en_US' => 'Type',
									'fr_FR' => 'Type',
							),
					),
					'school_year' => array(
							'type' => 'repository', // Deprecated
							'definition' => 'student/property/school_year',
					),
					'school_period' => array(
							'type' => 'repository', // Deprecated
							'definition' => 'student/property/school_period',
					),
					'type' => array(
							'definition' => 'inline',
							'type' => 'select',
							'modalities' => array(
									'sport' => array('en_US' => 'Sport', 'fr_FR' => 'Sport'),
									'schooling' => array('en_US' => 'Scolarité', 'fr_FR' => 'Scolarité'),
									'boarding_school' => array('en_US' => 'Boarding school', 'fr_FR' => 'Internat'),
							),
							'labels' => array(
									'en_US' => 'Domain',
									'fr_FR' => 'Domaine',
							),
					),
					'n_fn' => array(
							'definition' => 'inline',
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Name',
									'fr_FR' => 'Nom',
							),
					),
/*					'name' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Name',
									'fr_FR' => 'Nom',
							),
					),*/
					'subject' => array(
							'type' => 'repository', // Deprecated
							'definition' => 'student/property/school_subject',
					),
					'motive' => array(
							'type' => 'repository',
							'definition' => 'absence/property/motive',
					),
					'begin_date' => array(
							'definition' => 'inline',
							'type' => 'date',
							'labels' => array(
									'en_US' => 'Begin date',
									'fr_FR' => 'Date début',
							),
					),
					'end_date' => array(
							'definition' => 'inline',
							'type' => 'date',
							'labels' => array(
									'en_US' => 'End date',
									'fr_FR' => 'Date fin',
							),
					),
					'duration' => array(
							'definition' => 'inline',
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Duration',
									'fr_FR' => 'Durée',
							),
					),
					'observations' => array(
							'definition' => 'inline',
							'type' => 'textarea',
							'labels' => array(
									'en_US' => 'Observations',
									'fr_FR' => 'Observations',
							),
					),
			),
	),

	'absence/property/motive' => array(
			'type' => 'select',
			'modalities' => array(
					'medical' => array('en_US' => 'Medical', 'fr_FR' => 'Médical'),
					'training' => array('en_US' => 'Training', 'fr_FR' => 'Entrainement'),
					'competition' => array('en_US' => 'Tournament / Competition', 'fr_FR' => 'Tournoi / Compétition'),
					'spectacle' => array('en_US' => 'Spectacle', 'fr_FR' => 'Spectacle'),
					'family' => array('en_US' => 'Family', 'fr_FR' => 'Familial'),
					'transport' => array('en_US' => 'Transport', 'fr_FR' => 'Transport'),
					'unjustified' => array('en_US' => 'Unjustified', 'fr_FR' => 'Non justifié'),
					'repetition' => array('en_US' => 'Repetition', 'fr_FR' => 'Répétition'),
					'exclusion' => array('en_US' => 'Exclusion', 'fr_FR' => 'Exclusion'),
					'other' => array('en_US' => 'Other', 'fr_FR' => 'Autre'),
			),
			'labels' => array(
					'en_US' => 'Motive',
					'fr_FR' => 'Motif',
			),
	),
		
	'absence/search' => array(
			'title' => array('en_US' => 'Absences/Lateness', 'fr_FR' => 'Absences/Retards'),
			'todoTitle' => array('en_US' => 'current period', 'fr_FR' => 'période en cours'),
			'searchTitle' => array('en_US' => 'Search', 'fr_FR' => 'Recherche'),
			'main' => array('place_id' => 'select', 'n_fn' => 'contains', 'school_year' => 'select', 'school_period' => 'select', 'category' => 'select', 'subject' => 'select', 'begin_date' => 'range'),
			'more' => array(),
	),
	
	'absence/list' => array(
			'place_id' => 'text',
			'n_fn' => 'text',
			'school_period' => 'text',
			'category' => 'select',
			'subject' => 'text',
			'begin_date' => 'date',
			'end_date' => 'date',
			'duration' => 'number',
	),

	'absence/export' => array(
		'title' => ['default' => 'evaluations', 'fr_FR' => 'evaluations'],
		'properties' => array(
			'place_id' => 'A',
			'type' => 'B',
			'n_fn' => 'C',
			'school_period' => 'D',
			'category' => 'E',
			'subject' => 'F',
			'motive' => 'G',
			'begin_date' => 'H',
			'end_date' => 'I',
			'duration' => 'J',
			'observations' => 'K',
		),
	),
	
	// Note

	'note/property/place_caption' => array(
			'type' => 'text',
			'labels' => array(
					'en_US' => 'Place',
					'fr_FR' => 'Centre',
			),
	),

	'note/property/date' => array(
			'type' => 'date',
			'labels' => array(
					'en_US' => 'Date',
					'fr_FR' => 'Date',
			),
	),
	'note/property/name' => array(
			'type' => 'text',
			'labels' => array(
					'en_US' => 'Student name',
					'fr_FR' => 'Nom de l\'élève',
			),
	),
	'note/property/n_fn' => array(
			'type' => 'text',
			'labels' => array(
					'en_US' => 'Student name',
					'fr_FR' => 'Nom de l\'élève',
			),
	),
	'note/property/value' => array(
			'type' => 'number',
			'labels' => array(
					'en_US' => 'Value',
					'fr_FR' => 'Valeur',
			),
	),
	'note/property/reference_value' => array(
			'type' => 'number',
			'labels' => array(
					'en_US' => 'Reference value',
					'fr_FR' => 'Valeur de référence',
			),
	),
	'note/property/weight' => array(
			'type' => 'number',
			'labels' => array(
					'en_US' => 'Coef. / Credits',
					'fr_FR' => 'Coef./ Crédits',
			),
	),
	'note/property/lower_note' => array(
			'type' => 'number',
			'labels' => array(
					'en_US' => 'Lowest note',
					'fr_FR' => 'Note inférieure',
			),
	),
	'note/property/higher_note' => array(
			'type' => 'number',
			'labels' => array(
					'en_US' => 'Highest note',
					'fr_FR' => 'Note supérieure',
			),
	),
	'note/property/average_note' => array(
			'type' => 'number',
			'labels' => array(
					'en_US' => 'Average note',
					'fr_FR' => 'Note moyenne',
			),
	),
	'note/property/assessment' => array(
			'type' => 'textarea',
			'labels' => array(
					'en_US' => 'Assessment',
					'fr_FR' => 'Appréciation',
			),
	),
	'note/property/distribution' => array(
			'type' => 'key_value',
			'labels' => array(
					'en_US' => 'Distribution',
					'fr_FR' => 'Distribution',
			),
	),
	'note/property/observations' => array(
		'type' => 'textarea',
		'labels' => array(
			'en_US' => 'Observations',
			'fr_FR' => 'Observations',
		),
	),
	
	'note' => array(
			'types' => array(
					'evaluation' => array(
						'note' => array(
								'labels' => array('en_US' => 'Note', 'fr_FR' => 'Note'),
						),
						'report' => array(
								'labels' => array('en_US' => 'Report', 'fr_FR' => 'Bulletin'),
						),
					),
					'homework' => array(
						'done-work' => array(
								'labels' => array('en_US' => 'Done work', 'fr_FR' => 'Réalisé'),
						),
						'todo-work' => array(
								'labels' => array('en_US' => 'Work to do', 'fr_FR' => 'A faire'),
						),
						'event' => array(
								'labels' => array('en_US' => 'Event', 'fr_FR' => 'Evènement'),
						),
					),
			),
			'properties' => array(
					'id' => array(
							'definition' => 'inline',
							'type' => 'text',
							'labels' => array(
									'en_US' => 'ID',
									'fr_FR' => 'ID',
							),
					),
					'type' => array(
							'definition' => 'inline',
							'type' => 'select',
							'modalities' => array(
									'note' => array('en_US' => 'Note', 'fr_FR' => 'Note'),
									'report' => array('en_US' => 'Report', 'fr_FR' => 'Bulletin'),
									'done-work' => array('en_US' => 'Done work', 'fr_FR' => 'Travail réalisé'),
									'todo-work' => array('en_US' => 'Todo work', 'fr_FR' => 'Travail à faire'),
							),
							'labels' => array(
									'en_US' => 'Type',
									'fr_FR' => 'Type',
							),
					),
					'place_id' => array(
							'definition' => 'inline',
							'type' => 'select',
							'modalities' => array(
									'2pit' => array('fr_FR' => 'P-PIT', 'en_US' => '2PIT'),
							),
							'labels' => array(
									'en_US' => 'Center',
									'fr_FR' => 'Centre',
							),
					),
					'place_caption' => array('definition' => 'note/property/place_caption'),
					'school_year' => array(
							'type' => 'repository', //Deprecated
							'definition' => 'student/property/school_year',
					),
					'school_period' => array(
							'type' => 'repository', //Deprecated
							'definition' => 'student/property/school_period',
					),
					'class' => array(
							'type' => 'repository', //Deprecated
							'definition' => 'student/property/class',
					),
					'subject' => array(
							'type' => 'repository', //Deprecated
							'definition' => 'student/property/school_subject',
					),
					'n_fn' => array('definition' => 'note/property/n_fn'),
					'level' => array('definition' => 'student/property/evaluationCategory'),
					'date' => array('definition' => 'note/property/date'),
					'name' => array('definition' => 'note/property/name'),
					'value' => array('definition' => 'note/property/value'),
					'reference_value' => array('definition' => 'note/property/reference_value'),
					'weight' => array('definition' => 'note/property/weight'),
					'lower_note' => array('definition' => 'note/property/lower_note'),
					'higher_note' => array('definition' => 'note/property/higher_note'),
					'average_note' => array('definition' => 'note/property/average_note'),
					'distribution' => array('definition' => 'note/property/distribution'),
					'assessment' => array('definition' => 'note/property/assessment'),
					'evaluation' => array('definition' => 'student/property/reportMention'),
					'observations' => array('definition' => 'note/property/observations'),
			),
	),

	'note/colour' => array(
			'done-work' => 'LightGreen',	
			'todo-work' => 'LightSalmon',	
			'event' => 'LightBlue',	
	),
		
	'note/index' => array(
			'title' => array('en_US' => 'P-PIT Studies', 'fr_FR' => 'P-PIT Studies'),
	),

	'note/search/evaluation' => array(
			'todoTitle' => array('en_US' => 'current period', 'fr_FR' => 'période en cours'),
			'searchTitle' => array('en_US' => 'Search', 'fr_FR' => 'recherche'),
			'main' => array(
					'place_id' => 'select',
					'school_year' => 'select',
					'school_period' => 'select',
					'class' => 'select',
					'subject' => 'select',
					'date' => 'date',
			),
			'more' => array(
			),
	),

	'note/search/evaluation/note' => array(
			'title' => array('en_US' => 'Evaluations', 'fr_FR' => 'Relevés de notes'),
	),

	'note/search/evaluation/report' => array(
			'title' => array('en_US' => 'School reports', 'fr_FR' => 'Bulletins'),
	),
		
	'note/search/homework' => array(
			'title' => array('en_US' => 'Homework notebook', 'fr_FR' => 'Cahier de texte'),
			'todoTitle' => array('en_US' => 'current period', 'fr_FR' => 'période en cours'),
			'searchTitle' => array('en_US' => 'Search', 'fr_FR' => 'recherche'),
			'main' => array(
					'place_id' => 'select',
					'type' => 'select',
					'school_year' => 'select',
					'school_period' => 'select',
					'class' => 'select',
					'subject' => 'select',
					'date' => 'date',
			),
			'more' => array(
			),
	),

	'note/list/homework' => array(
			'place_id' => 'select',
			'school_period' => 'select',
			'type' => 'select',
			'class' => 'select',
			'subject' => 'select',
			'date' => 'date',
	),
		
	'note/list/evaluation' => array(
			'place_id' => 'select',
			'school_period' => 'select',
			'class' => 'select',
			'level' => 'select',
			'subject' => 'select',
			'date' => 'date',
			'weight' => 'number',
			'lower_note' => 'number',
			'average_note' => 'number',
			'higher_note' => 'number',
	),

	'note/export/homework' => array(
			'title' => ['default' => 'Homework', 'fr_FR' => 'Cahier de texte'],
			'properties' => array(
				'id' => 'A',
				'type' => 'B',
				'place_caption' => 'C',
				'school_period' => 'D',
				'class' => 'E',
				'level' => 'F',
				'subject' => 'G',
				'name' => 'H',
				'date' => 'I',
				'observations' => 'J',
			),
	),

	'note/export/evaluation' => array(
		'title' => ['default' => 'evaluations', 'fr_FR' => 'evaluations'],
		'properties' => array(
			'id' => 'A',
			'type' => 'B',
			'place_caption' => 'C',
			'school_period' => 'D',
			'class' => 'E',
			'level' => 'F',
			'subject' => 'G',
			'name' => 'H',
			'date' => 'I',
			'reference_value' => 'J',
			'weight' => 'K',
			'value' => 'L',
			'lower_note' => 'M',
			'average_note' => 'N',
			'higher_note' => 'O',
			'assessment' => 'P',
			'evaluation' => 'Q',
		),
	),
	
	'progress/Basketball' => array(
			'criteria' => array(
					'modalities' => array(
							'NA' => array('fr_FR' => 'Non acquis'),
							'EC' => array('fr_FR' => 'En cours'),
							'AC' => array('fr_FR' => 'Acquis'),
					),
					'quantitative_criteria' => array(
							'passe' => array(
									'labels' => array('fr_FR' => 'Passes'),
									'type' => 'select',
									'maxLength'  => '255',
							),
							'controle' => array(
									'labels' => array('fr_FR' => 'Contrôle ballon'),
									'type' => 'select',
									'maxLength'  => '255',
							),
							'placement' => array(
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
	),
		
	'progress/Equitation' => array(
			'criteria' => array(
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
	),

	'progress/Football' => array(
			'criteria' => array(
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
	),

	'progress/Golf' => array(
			'criteria' => array(
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
	),

	'progress/Tennis' => array(
			'criteria' => array(
					'qualitative_criteria' => array(
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
					),
			),
	),

	'progress/search' => array(
			'title' => array('en_US' => 'Progress', 'fr_FR' => 'Progression'),
			'todoTitle' => array('en_US' => 'in progress', 'fr_FR' => 'en cours'),
			'searchTitle' => array('en_US' => 'Search', 'fr_FR' => 'Recherche'),
			'main' => array('property_1' => 'select', 'name' => 'contains', 'period' => 'select'),
			'more' => array('school_year' => 'select'),
	),

	'progress/list' => array(
			'property_1' => 'image',
			'contact_1_id' => 'photo',
			'name' => 'text',
			'period' => 'select',
	),

	'ppit_roles' => array(
		'coach' => array(
			'show' => true,
			'labels' => array(
				'en_US' => 'Coach',
				'fr_FR' => 'Coach',
			),
		),
		'teacher' => array(
			'show' => true,
			'labels' => array(
				'en_US' => 'Teacher',
				'fr_FR' => 'Enseignant',
			),
		),
	),

	'manageable_roles' => ['coach', 'teacher'],
	
	'ppitApplications' => array(
		'p-pit-studies' => array(
			'labels' => array('fr_FR' => 'P-Pit Studies', 'en_US' => 'Studies by 2Pit'),
			'default' => 'student',
			'defaultRole' => 'teacher',
		),
	),

	// News
	'public/news/student' => array(
			'p-pit-studies' => array(
					'title' => 'Rentrée 2018-2019',
					'description' => '',
					'date' => '2017-09-01',
					'parts' => array(
							array(
									'type' => null,
									'text' => array(
											'en_US' => 'To be translated',
											'fr_FR' => '<h2>Rentrée 2018-2019</h2><hr><p>Prêt(e) à relever de nouveaux challenges ?',
									),
									'image' => array(
											'src' => 'banner.jpg',
									),
							),
					),
			),
	),
		
	// Home page
	'public/community/student' => array(
			'title' => array(
					'en_US' => 'Studies by 2pit',
					'fr_FR' => 'P-Pit Studies',
			),
			'description' => array(
					'en_US' => '',
					'fr_FR' => '',
			),
			'rows' => array(
					'jumbotron' => array(
							'type' => 'jumbotron',
							'directory' => 'public/news/student',
							'entry' => 'p-pit-studies',
					),
					'tabs' => array(
							'type' => 'tabs',
							'content' => array(
									'planning' => array(
											'type' => 'calendar',
											'level' => 'community',
											'route' => 'student/planning',
											'label' => array('en_US' => 'Planning', 'fr_FR' => 'Planning'),
									),
/*									'file' => array(
											'type' => 'static',
											'level' => 'subject',
											'route' => 'student/file',
											'label' => array('en_US' => 'Student file', 'fr_FR' => 'Dossier élève'),
									),*/
									'absence' => array(
											'type' => 'static',
											'level' => 'subject',
											'route' => 'student/absence',
											'label' => array('en_US' => 'Absences/lateness', 'fr_FR' => 'Absences/retards'),
									),
									'homework' => array(
											'type' => 'static',
											'level' => 'subject',
											'route' => 'student/homework',
											'label' => array('en_US' => 'Homework', 'fr_FR' => 'Cahier de texte'),
									),
									'evaluation' => array(
											'type' => 'static',
											'level' => 'subject',
											'route' => 'student/evaluation',
											'filter' => 'evaluation_category',
											'label' => array('en_US' => 'Evaluations', 'fr_FR' => 'Relevés de notes'),
									),
									'schooling' => array(
											'type' => 'static',
											'level' => 'subject',
											'route' => 'student/report',
											'label' => array('en_US' => 'School reports', 'fr_FR' => 'Bulletins scolaires'),
									),
									'mock_evaluation' => array(
											'type' => 'static',
											'level' => 'subject',
											'route' => 'student/exam',
											'params' => ['mock' => 'mock'],
											'label' => array('en_US' => 'Mock exams', 'fr_FR' => 'Examens blancs'),
									),
							),
					),
			),
	),
		
	'perimeters' => array(
			'p-pit-studies' => array(
					'property_1' => 'student/property/discipline',
					'school_subject' => 'student/property/school_subject',
					'property_7' => 'student/property/class',
			),
	),
		
	'demo' => array(
			'student/menu' => array(
					'en_US' => '
',
					'fr_FR' => '
<h4>Menu</h4>
<p>Nous allons faire dans cette prise en main un tour des différentes fonctions auxquelles ce menu (qui s\'adapte en fonction du rôle de chacun) donne accès :</p>
<li>
	<ul><strong>Elèves</strong> : point d\'entrée pour l\'ajout collectif d\'éléments (notes, absences, etc.),</ul>
	<ul><strong>Flash</strong> : gestion des flashs, quelle que soit leur catégorie de diffusion (scolaire, sport, etc.)</ul>
	<ul><strong>Suivi sportif</strong> : saisie des suivis sportifs,</ul>
	<ul><strong>Evaluations</strong> : notes et bulletins scolaires,</ul>
	<ul><strong>Rendez-vous</strong> : gestion du calendrier de la home page élève,</ul>
	<ul><strong>Absences</strong> : suivi des absences,</ul>
	<ul><strong>Inscriptions</strong> : gestion des inscriptions,</ul>
	<ul><strong>Utilisateurs</strong> : gestion des accès, rôle, périmètre visible du staff ainsi que du compte familial de l\'élève.</ul>
</li>
',
			),
			'student/search/title' => array(
					'en_US' => '
<h4>Student list</h4>
<p>As a default, all the currently registered students are presented in the list.</p>
<p>As soon as a criterion below is specified, the list switch in search mode.</p>
',
					'fr_FR' => '
<h4>Liste des élèves</h4>
<p>Par défaut, tous les élèves actuellement inscrits sont présentés dans la liste.</p>
<p>Dès lors qu\'un des critères ci-dessous est spécifié, le mode <em>Elèves (recherche)</em> est automatiquement activé.</p>
',
			),
			'student/search/x' => array(
					'en_US' => '
<h4>Return in default mode</h4>
<p>The <code>x</code> button reinitializes all the search criteria and reset the list in default mode (registered students).</p>
',
					'fr_FR' => '
<h4>Retour au mode par défaut</h4>
<p>Le bouton <code>x</code> réinitialise tous les critères de recherche et ré-affiche la liste en mode <em>Elèves (inscrits)</em> initial.</p>
',
			),
			'student/search/export' => array(
					'en_US' => '
<h4>List export</h4>
<p>The list can be exported to Excel as it is presented: defaulting list or list resulting of a multi-criteria search.</p>
',
					'fr_FR' => '
<h4>Export de la liste</h4>
<p>La liste peut être exportée sous Excel telle que présentée : liste par défaut ou liste résultant d\'une recherche multi-critère.</p>
',
			),
			'student/list/ordering' => array(
					'en_US' => '
<h4>Ordering</h4>
<p>The list can be sorted according to each column in ascending or descending order.</p>
',
					'fr_FR' => '
<h4>Classement</h4>
<p>La liste peut être triée selon chaque colonne en ordre ascendant ou descendant.</p>
',
			),
			'student/list/checkAll' => array(
					'en_US' => '
<h4>Check all</h4>
<p>This check-box allows to check at one time all the items of the list.</p>
					',
					'fr_FR' => '
<h4>Tout sélectionner</h4>
<p>Cette case à cocher permet de sélectionner d\'un coup tous les éléments de la liste.</p>
',
			),
			'student/list/groupedActions' => array(
					'en_US' => '
<h4>Grouped actions</h4>
<p>The group action button operates along with the individual or global checkboxes on the left column.</p>
<p>It opens a new panel proposing actions to apply to each student who has previously been checked in the list.</p>
<p>For example you can generate an absence statement by checking absent students and then point out the in a grouped way. The same is for lateness.</p>
<p>Another use case: Make a test notation, or a quartery evaluation, in a unique data entry on each student of a class.</p>
<p>The operating mode for makin a grouped action (absence, lateness, homework notebook, notation, quarterly evaluation...) is explained in the description of the <em>grouped action</em> panel.</p>
					',
					'fr_FR' => '
<h4>Actions groupées</h4>
<p>Le bouton d\'actions groupées agit conjointement avec les cases à cocher individuelles ou globales en colonne de gauche de la liste.</p>
<p>Il ouvre un nouveau panneau proposant des actions à appliquer à chaque élève qui a préalablement été sélectionné dans la liste.</p>
<p>Par exemple vous pouvez générer un relevé d\'absence en cochant dans la liste les élèves absents puis indiquer l\'absence de façon groupée. De même pour les retards.</p>
<p>Autre cas d\'utilisation : Effectuer la notation d\'un contrôle, ou une évaluation trimestrielle, en une seule saisie sur tous les élèves d\'une classe.</p>
<p>Le mode opératoire pour effectuer une action groupée (absence, retard, cahier de texte, notation, évaluation trimestrielle, etc) est précisé dans la description du panneau <em>Actions groupées</em>.
					',
			),
			'student/list/detail' => array(
					'en_US' => '
<h4>Student home page</h4>
<p>The magnifier button gives access to the student home page as he sees it.</p>
					',
					'fr_FR' => '
<h4>Home page élève</h4>
<p>La loupe donne accès à la home page élève telle que celui-ci la voit.</p>
',
			),
			'student/group/tabs' => array(
					'en_US' => '
<h4>Tab organization</h4>
<p>The information is organized in thematic tabs corresponding to tabs from the student home page.</p>
',
					'fr_FR' => '
<h4>Organisation en onglets</h4>
<p>L\'information est organisée en onglets thématiques qui correspondent aux onglets de la home page de l\'élève.</p>
',
			),
			'student/group/navigation' => array(
					'en_US' => '
<h4>Navigation buttons</h4>
<p>Navigation buttons are grey as a default and become blue while being activated.</p>
<p>Currently you can see in blue the <em>Students</em> button from the top of page menu and the <em>Grouped actions</em> from the list panel. If you select <em>+ News flash</em> in this local menu, it will adopt the same blue color.</p>
<p>Hence you have a visual breadcrumb for your current navigation, really practical for finding a way as the screen is getting rich of information.</p>
					',
					'fr_FR' => '
<h4>Boutons de navigation</h4>
<p>Les boutons de navigation sont gris par défaut et deviennent bleus une fois activés.</p>
<p>Vous voyez actuellement en bleu le bouton <em>Elèves</em> du menu de haut de page et le bouton <em>Actions groupées</em> du panneau de liste. Si vous sélectionnez <em>+ Notification</em> dans ce menu local, il adoptera la même couleur bleue.</p>
<p>Vous avez ainsi un <em>fil d\'ariane</em> visuel de votre navigation courante, bien pratique pour se repérer tandis que l\'écran s\'enrichit.</p>
',
			),
			'student/addNote/note' => array(
					'en_US' => '
					',
					'fr_FR' => '
<h4>Ajout dans le cahier de texte</h4>
<p>Vous disposez de trois types d\'entrées pour le cahier de texte : Travail effectué, travail à faire et évènement, chacun avec une couleur de fond différente, afin de permettre aux parents/élèves de bien les distinguer.</p>
<p>Vous pouvez lier dans le cahier de texte tout document présent dans le répertoire Scolarité de votre espace Dropbox.</p>
',
			),
			'student/addEvaluation/note' => array(
					'en_US' => '
					',
					'fr_FR' => '
<h4>Saisie d\'une évaluation</h4>
<p>Le formulaire d\'ajout de notes permet d\'entrer en une fois une évaluation pour une classe.</p>
<p>Le coefficient et la note de référence sont précisés. Ceci permet de calculer automatiquement les moyennes des bulletins.</p>
',
			),
			'student/addEvaluation/report' => array(
					'en_US' => '
					',
					'fr_FR' => '
<h4>Saisie d\'un bulletin trimestriel</h4>
<p>Le formulaire d\'ajout de bulletin permet d\'entrer en une fois le bulletin pour une classe.</p>
<p>Un seul bulletin par matière peut être créé pour la période en cours. Une fois la période clôturée, la saisie n\'est plus possible.</p>
<p>Si la moyenne d\'un élève n\'est pas saisie, elle est calculée automatiquement à partir de toutes les notes disponibles dans la période du bulletin.</p>
',
			),
			'note/list/homework' => array(
					'en_US' => '
					',
					'fr_FR' => '
<h4>Cahier de texte</h4>
<p>Cette liste permet de retrouver toutes les entrées qui ont été saisies dans le cahier de texte. Vous disposez de filtres sur la classe, la matière et la date.</p>
',
			),
			'note/update' => array(
					'en_US' => '
					',
					'fr_FR' => '
<h4>Détail du cahier de texte</h4>
<p>Depuis le cahier de texte, vous accédez au détail. Vous pouvez corriger ou supprimer une entrée du cahier de texte.</p>
',
			),
			'note/list/note' => array(
					'en_US' => '
					',
					'fr_FR' => '
<h4>Liste des évaluations</h4>
<p>Cette liste permet de retrouver toutes les évaluations qui ont été saisies. Vous disposez de filtres sur la classe, la matière et la date.</p>
',
			),
			'note/updateEvaluation/note' => array(
					'en_US' => '
					',
					'fr_FR' => '
<h4>Détail d\'une évaluation</h4>
<p>Depuis la liste des évaluations, vous accédez au détail. Vous pouvez corriger ou supprimer une évaluation.</p>
',
			),
			'note/list/report' => array(
					'en_US' => '
					',
					'fr_FR' => '
<h4>Liste des moyennes</h4>
<p>Cette liste permet de retrouver toutes les contributions par matière ayant été ajoutées au bulletin trimestriel. Vous disposez de filtres sur la période, la classe, la matière et la date.</p>
',
			),
			'note/updateEvaluation/report' => array(
					'en_US' => '
					',
					'fr_FR' => '
<h4>Détail d\'une contribution par matière</h4>
<p>Depuis la liste des contributions par matière, vous accédez à la liste des moyennes par élève sur cette matière et pour une période donnée. Vous pouvez corriger ou supprimer cette contribution au bulletin.</p>
',
			),

/*
 * Contacts
 */
			'core_account/search/contact/title' => array(
					'en_US' => '
<h4>CRM</h4>
<p>The <em>Contacts</em> tab is the place where you can add manage contacts.</p>
<p><em>2Pit Contacts</em> is integrated with the back-office solution <em>2Pit Commitments</em> in option.</p>
<p>As a default, all the contacts that should be called today are presented in the list.</p>
<p>As soon as a criterion below is specified, the list switch in search mode.</p>
',
					'fr_FR' => '
<h4>CRM</h4>
<p>L\'entrée <em>Contacts</em> est le lieu où vous pouvez ajouter gérer les contacts.</p>
<p><em>P-Pit Contacts</em> est intégrée avec la solution back-office <em>P-Pit Engagements</em> en option.</p>
<p>Par défaut, tous les contacts qui doivent être appelés aujourd\'hui sont présentés dans la liste.</p>
<p>Dès lors qu\'un des critères ci-dessous est spécifié, le mode de recherche est automatiquement activé.</p>
',
			),
			'core_account/list/contact/add' => array(
					'en_US' => '',
					'fr_FR' => '
<h4>Ajout d\'un contact</h4>
<p>Le bouton + permet d\'accéder à l\'ajout d\un nouveau contact.</p>
<p>Les dossiers d\'inscription par année scolaire, destinés à la facturation, seront créés dans un second temps.</p>
<p>On peut ainsi lors de la vente transformer un contact en compte élève regroupant ces inscriptions successives.</p>
',
			),
			'core_account/list/contact/detail' => array(
					'en_US' => '',
					'fr_FR' => '
<h4>Détail d\'un élève</h4>
<p>Un code couleur vert, orange ou rouge est associé à chaque contact en fonction de son statut chaud tiède ou froid.</p>
<p>Le bouton zoom permet d\'accéder au détail d\'un contact et le modifier.</p>
',
			),
				
/*
 *  Inscriptions
 */
			'core_account/search/p-pit-studies/title' => array(
					'en_US' => '
<h4>Back-office</h4>
<p>The <em>Subscriptions</em> tab is the place where you can add students and manage personal and parental data.</p>
<p><em>2Pit Studies</em> is integrated with the back-office solution <em>2Pit Commitments</em> in option.</p>
<p><em>2Pit Commitments</em> allows to manage:</p>
	<ul>
		<li>Orders and acknowledgements</li>
		<li>Confirmations and attestations</li>
		<li>Product and service catalog</li>
		<li>Invoicing and payment tracking</li>
	</ul>
</p>
<p>As a default, all the accounts with a <em>Active</em> status are presented in the list.</p>
<p>As soon as a criterion below is specified, the list switch in search mode.</p>					
',
					'fr_FR' => '
<h4>Back-office</h4>
<p>L\'entrée <em>Inscriptions</em> est le lieu où vous pouvez ajouter de nouveau élèves et gérer données personnelles et parentales.</p>
<p><em>P-Pit Studies</em> est intégrée avec la solution back-office <em>P-Pit Engagements</em> en option.</p>
<p><em>P-Pit Engagements</em> permet de gérer :</p>
	<ul>
		<li>Accusés de réception de commande</li>
		<li>Confirmations et attestations</li>
		<li>Catalogue de produits et prestations</li>
		<li>Facturation et suivi des paiements</li>
	</ul>
</p>
<p>Par défaut, tous les comptes dont le statut est <em>Actif</em> sont présentés dans la liste.</p>
<p>Dès lors qu\'un des critères ci-dessous est spécifié, le mode de recherche est automatiquement activé.</p>
',
			),
			'core_account/list/p-pit-studies/add' => array(
					'en_US' => '',
					'fr_FR' => '
<h4>Ajout d\'un élève</h4>
<p>Le bouton + permet d\'accéder à l\'ajout d\un nouvel élève.</p>
<p>Les dossiers d\'inscription par année scolaire, destinés à la facturation, seront créés dans un second temps.</p>
<p>On peut ainsi gérer un compte élève regroupant ces inscriptions successives.</p>
',
			),
			'core_account/add/p-pit-studies' => array(
					'en_US' => '',
					'fr_FR' => '
<h4>Ajout d\'un élève (contact ou client)</h4>
<p>Lors de la création de la fiche élève les données principales sont renseignées.</p>
<p>Pour un contact :</p>
	<ul>
		<li>Statut <em>Chaud</em>, <em>Tiède</em> ou <em>Froid</em> du contact. Au moment de la vente ce statut doit être mis <em>Actif</em> pour basculer le contact comme client vers la gestion commerciale ;</li>
		<li>Date de rappel qui pilote l\'affichage du contact en <em>todo-list</em> des contacts à rappeler ;</li>
		<li>Origine du contact : site, salon, appel... ;</li>
		<li>Identification</li>
		<li>Sport</li>
		<li>Date de la journée de détection</li>
		<li>Centre d\'affectation</li>
	</ul>
<p>En complément pour un élève inscrit</p>
	<ul>
		<li>Données de contact, téléphone d\'urgence mentionné dans la liste et photo</li>
		<li>période de validité du compte (seule la date d\'ouverture est obligatoire)</li>
		<li>Données de vie scolaire : classe, langues, options et internat</li>
	</ul>
',
			),
			'core_account/list/p-pit-studies/detail' => array(
					'en_US' => '',
					'fr_FR' => '
<h4>Détail d\'un élève</h4>
<p>Le bouton zoom permet d\'accéder au détail d\'un élève et aux inscriptions associées.</p>
',
			),
			'core_account/update/p-pit-studies' => array(
					'en_US' => '',
					'fr_FR' => '
<h4>Gestion des données de l\'élève</h4>
<p>L\'accès à la fiche élève permet de consulter et éventuellement en rectifier les données.</p>
<p>Il donne également accès aux onglets complémentaires de gestion des coordonnées du père, de la mère et le cas échéant d\'un autre représentant légal, ainsi que l\'onglet de gestion du compte de connexion parent/élève au site intranet.</p>
<p>Il donne enfin un accès centralisé, en ajout ou modification, aux inscriptions annuelles pour l\'élève sélectionné.</p>
',
			),
			'commitment/accountList/p-pit-studies/add' => array(
					'en_US' => '',
					'fr_FR' => '
<h4>Ajout d\'une inscription annuelle</h4>
<p>Le bouton + permet l\'ajout d\une nouvelle inscription annuelle pour cet élève.</p>
',
			),
			'commitment/accountList/p-pit-studies/documents' => array(
					'en_US' => '',
					'fr_FR' => '
<h4>Documents</h4>
<p>Quatre documents pré-formatés sont disponibles au niveau du dossier d\'inscription annuelle :</p>
	<ul>
		<li>L\'accusé de réception</li>
		<li>La confirmation d\'inscription</li>
		<li>L\'engagement de prise en charge</li>
		<li>L\'attestation scolaire</li>
	</ul>
<p>Ces documents sont générés au format Word et peuvent être complétés manuellement après téléchargement, par exemple si besoin d\'ajouter une mention spécifique.</p>
',
			),
	),
);
