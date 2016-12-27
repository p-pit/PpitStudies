<?php
namespace PpitStudies;

return array(
	'controllers' => array(
        'invokables' => array(
        	'PpitStudies\Controller\Absence' => 'PpitStudies\Controller\AbsenceController',
        	'PpitStudies\Controller\Event' => 'PpitStudies\Controller\EventController',
        	'PpitStudies\Controller\Note' => 'PpitStudies\Controller\NoteController',
        	'PpitStudies\Controller\Notification' => 'PpitStudies\Controller\NotificationController',
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
		        								'route' => '/update[/:id][/:act]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'update',
		        								),
		        						),
		        				),
/*	       						'delete' => array(
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
				                ),*/
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
        						'get' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/get',
        										'defaults' => array(
        												'action' => 'get',
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
        										'route' => '/index[/:category]',
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),
        						'search' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/search[/:category]',
        										'defaults' => array(
        												'action' => 'search',
        										),
        								),
        						),
        						'list' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/list[/:category]',
        										'defaults' => array(
        												'action' => 'list',
        										),
        								),
        						),
        						'export' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/export[/:category]',
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
        										'route' => '/index[/:type]',
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
        						'studentHome' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/student-home',
        										'defaults' => array(
        												'action' => 'studentHome',
        										),
        								),
        						),
	       						'registrationIndex' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/registration-index',
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
        										'route' => '/add-note[/:type]',
        										'defaults' => array(
        												'action' => 'addNote',
        										),
        								),
        						),
	       						'addEvaluation' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add-evaluation[/:type]',
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
	       						'import' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/import',
        										'defaults' => array(
        												'action' => 'import',
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
	       		),
	    	   	),
	    ),
    ),
	'bjyauthorize' => array(
		// Guard listeners to be attached to the application event manager
		'guards' => array(
			'BjyAuthorize\Guard\Route' => array(

				array('route' => 'absence', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'absence/index', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'absence/search', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
            	array('route' => 'absence/list', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'absence/export', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'absence/detail', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'absence/update', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
//				array('route' => 'absence/delete', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'studentEvent', 'roles' => array('manager', 'coach', 'teacher', 'medical')),
				array('route' => 'studentEvent/index', 'roles' => array('manager', 'coach', 'teacher', 'medical')),
				array('route' => 'studentEvent/search', 'roles' => array('manager', 'coach', 'teacher', 'medical')),
            	array('route' => 'studentEvent/list', 'roles' => array('manager', 'coach', 'teacher', 'medical')),
            	array('route' => 'studentEvent/get', 'roles' => array('student', 'manager', 'coach', 'teacher', 'medical')),
				array('route' => 'studentEvent/export', 'roles' => array('manager', 'coach', 'teacher', 'medical')),
				array('route' => 'studentEvent/update', 'roles' => array('manager', 'coach', 'teacher', 'medical')),
				array('route' => 'studentEvent/delete', 'roles' => array('manager', 'coach', 'teacher', 'medical')),
				array('route' => 'note', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/index', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/search', 'roles' => array('manager', 'teacher')),
            	array('route' => 'note/list', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/export', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/detail', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/update', 'roles' => array('manager', 'teacher')),
				array('route' => 'note/updateEvaluation', 'roles' => array('manager', 'teacher')),
				array('route' => 'studentNotification', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'studentNotification/index', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'studentNotification/search', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
            	array('route' => 'studentNotification/list', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'studentNotification/export', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'studentNotification/update', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'studentNotification/delete', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'progress', 'roles' => array('manager', 'coach')),
				array('route' => 'progress/index', 'roles' => array('manager', 'coach')),
				array('route' => 'progress/search', 'roles' => array('manager', 'coach')),
            	array('route' => 'progress/list', 'roles' => array('manager', 'coach')),
				array('route' => 'progress/export', 'roles' => array('manager', 'coach')),
				array('route' => 'progress/update', 'roles' => array('manager', 'coach')),
				array('route' => 'progress/delete', 'roles' => array('manager', 'coach')),
				array('route' => 'sms', 'roles' => array('manager')),
				array('route' => 'sms/delete', 'roles' => array('manager')),
				array('route' => 'sms/index', 'roles' => array('manager')),
				array('route' => 'sms/simulate', 'roles' => array('manager')),
				array('route' => 'sms/update', 'roles' => array('manager')),
				array('route' => 'student', 'roles' => array('student', 'manager', 'coach', 'teacher', 'boarding_school_headmaster', 'medical')),
				array('route' => 'student/registrationIndex', 'roles' => array('sales_manager')),
				array('route' => 'student/index', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster', 'medical')),
				array('route' => 'student/studentHome', 'roles' => array('student')),
				array('route' => 'student/search', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster', 'medical')),
				array('route' => 'student/export', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster', 'medical')),
            	array('route' => 'student/list', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster', 'medical')),
				array('route' => 'student/detail', 'roles' => array('student', 'manager', 'coach', 'teacher', 'boarding_school_headmaster', 'medical')),
				array('route' => 'student/group', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster', 'medical')),
            	array('route' => 'student/addAbsence', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
            	array('route' => 'student/addEvent', 'roles' => array('manager', 'coach', 'teacher', 'medical')),
				array('route' => 'student/addNote', 'roles' => array('manager', 'teacher')),
				array('route' => 'student/addEvaluation', 'roles' => array('manager', 'teacher')),
				array('route' => 'student/addNotification', 'roles' => array('manager', 'coach', 'teacher', 'boarding_school_headmaster')),
				array('route' => 'student/addProgress', 'roles' => array('manager', 'coach')),
				array('route' => 'student/import', 'roles' => array('admin')),
				array('route' => 'student/dashboard', 'roles' => array('user')),
				array('route' => 'student/update', 'roles' => array('business_owner')),
				array('route' => 'student/letter', 'roles' => array('manager')),
				array('route' => 'student/confirmation', 'roles' => array('manager')),
				array('route' => 'student/attestation', 'roles' => array('manager')),
				array('route' => 'student/acknowledgement', 'roles' => array('manager')),
				array('route' => 'student/commitment', 'roles' => array('manager')),
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

	'ppitStudiesDependencies' => array(),

	'menus' => array(
			'p-pit-studies' => array(
					'student' => array(
							'route' => 'student',
							'params' => array('type' => ''),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-list-alt',
							'label' => array(
									'en_US' => 'Students',
									'fr_FR' => 'Elèves',
							),
					),
					'notification' => array(
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
							'params' => array(),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-edit',
							'label' => array(
									'en_US' => 'Sport progress',
									'fr_FR' => 'Suivi sportif',
							),
					),
					'absence' => array(
							'route' => 'absence',
							'params' => array('type' => ''),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-edit',
							'label' => array(
									'en_US' => 'Absences/Lateness',
									'fr_FR' => 'Absences/Retards',
							),
					),
					'homework' => array(
							'route' => 'note/index',
							'params' => array('category' => 'homework'),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-edit',
							'label' => array(
									'en_US' => 'Homework notebook',
									'fr_FR' => 'Cahier de texte',
							),
					),
					'evaluation' => array(
							'route' => 'note/index',
							'params' => array('category' => 'evaluation'),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-edit',
							'label' => array(
									'en_US' => 'Evaluations',
									'fr_FR' => 'Evaluations',
							),
					),
					'event' => array(
							'route' => 'studentEvent',
							'params' => array('type' => ''),
							'urlParams' => array(),
							'glyphicon' => 'glyphicon-edit',
							'label' => array(
									'en_US' => 'Appointments',
									'fr_FR' => 'Rendez-vous',
							),
					),
					'account' => array(
							'route' => 'student/registrationIndex',
							'params' => array('type' => 'p-pit-studies'),
							'urlParams' => array(),
							'label' => array(
									'en_US' => 'Registrations',
									'fr_FR' => 'Inscriptions',
							),
					),
			),
	),

	'currentApplication' => 'p-pit-studies',
	'currentPeriodStart' => '2016-09-01',

	'ppitProduct/p-pit-studies' => array(
			'properties' => array(),
			'criteria' => array(),
			'todo' => array(
					'sales_manager' => array(),
					'business_owner' => array(),
			),
	),
		
	'ppitProduct/index/p-pit-studies' => array(
			'title' => array('en_US' => 'Sport studies', 'fr_FR' => 'Sport études'),
	),
		
	'ppitProduct/search/p-pit-studies' => array(),
		
	'ppitProduct/list/p-pit-studies' => array(),
		
	'ppitProduct/update/p-pit-studies' => array(),
		
	'commitment/p-pit-studies' => array(
/*			'statuses' => array(
					'new' => array(
							'labels' => array(
									'en_US' => 'To be approved',
									'fr_FR' => 'A valider',
							)
					),
					'confirmed' => array(
							'labels' => array(
									'en_US' => 'Confirmed',
									'fr_FR' => 'Confirmé',
							)
					),
					'settled' => array(
							'labels' => array(
									'en_US' => 'Settled',
									'fr_FR' => 'Réglé',
							)
					),
			),*/
			'tax' => 'including',
			'currencySymbol' => '€',
			'properties' => array(
					'status' => array(
							'type' => 'select',
							'modalities' => array(
								'new' => array('en_US' => 'To be confirmed', 'fr_FR' => 'A confirmer'),
								'confirmed' => array('en_US' => 'Confirmed', 'fr_FR' => 'Confirmé'),
								'settled' => array('en_US' => 'Settled', 'fr_FR' => 'Réglé'),
								'closed' => array('en_US' => 'Closed', 'fr_FR' => 'Clôturé'),
							),
							'labels' => array(
									'en_US' => 'Status',
									'fr_FR' => 'Statut',
							),
					),
					'customer_name' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Name',
									'fr_FR' => 'Nom',
							),
					),
					'caption' => array(
							'type' => 'repository',
							'definition' => 'student/property/school_year',
							'labels' => array(
									'en_US' => 'School year',
									'fr_FR' => 'Année scolaire',
							),
					),
					'description' => array(
							'type' => 'textarea',
							'labels' => array(
									'en_US' => 'Description',
									'fr_FR' => 'Description',
							),
					),
					'product_brand' => array(
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
							'type' => 'number',
							'labels' => array(
									'en_US' => 'Amount',
									'fr_FR' => 'Montant',
							),
					),
					'invoice_date' => array(
							'type' => 'date',
							'labels' => array(
									'en_US' => 'Invoice date',
									'fr_FR' => 'Date de facture',
							),
					),
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
					'status' => 'select',
					'including_options_amount' => 'range',
					'customer_name' => 'contains',
			),
	),

	'commitment/list/p-pit-studies' => array(
			'property_1' => 'select',
			'property_2' => 'select',
			'property_3' => 'select',
			'including_options_amount' => 'number',
			'status' => 'select',
	),
		
	'commitment/update/p-pit-studies' => array(
			'caption' => array('mandatory' => true),
			'description' => array('mandatory' => false),
			'property_1' => array('mandatory' => true),
			'property_2' => array('mandatory' => false),
			'property_3' => array('mandatory' => false),
	),

	'commitment/invoice/p-pit-studies' => array(
			'header' => array(),
			'description' => array(
					array(
							'left' => array('en_US' => 'Caption', 'fr_FR' => 'Libellé'),
							'right' => array('en_US' => '%s school year %s', 'fr_FR' => 'Année scolaire %s %s'),
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
							'params' => array('customer_name'),
					),
			),
	),

	'commitment/proforma/p-pit-studies' => array(
			'header' => array(),
			'description' => array(
					array(
							'left' => array('en_US' => 'Caption', 'fr_FR' => 'Libellé'),
							'right' => array('en_US' => '%s school year %s', 'fr_FR' => 'Année scolaire %s %s'),
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
							'params' => array('customer_name'),
					),
			),
	),
		
	'commitmentAccount/p-pit-studies' => array(
			'statuses' => array(),
			'properties' => array(
					'status' => array(
							'type' => 'select',
							'modalities' => array(
									'new' => array('en_US' => 'New', 'fr_FR' => 'Nouveau'),
									'active' => array('en_US' => 'Active', 'fr_FR' => 'Actif'),
									'inactive' => array('en_US' => 'Inactive', 'fr_FR' => 'Inactif'),
									'gone' => array('en_US' => 'Gone', 'fr_FR' => 'Parti'),
							),
							'labels' => array(
									'en_US' => 'Status',
									'fr_FR' => 'Statut',
							),
					),
					'customer_name' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Name',
									'fr_FR' => 'Nom',
							),
					),
					'contact_1_id' => array(
							'type' => 'photo',
							'labels' => array(
									'en_US' => '',
									'fr_FR' => '',
							),
					),
					'n_first' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'First name',
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
					'n_fn' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Name',
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
					'birth_date' => array(
							'type' => 'date',
							'labels' => array(
									'en_US' => 'Birth date',
									'fr_FR' => 'Date de naissance',
							),
					),
					'place_id' => array(
							'type' => 'repository',
							'definition' => 'student/property/place',
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
							'type' => 'repository',
							'definition' => 'student/property/discipline',
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
							'type' => 'repository',
							'definition' => 'student/property/level',
							'labels' => array(
									'en_US' => 'Class',
									'fr_FR' => 'Classe',
							),
					),
					'property_5' => array(
							'type' => 'repository',
							'definition' => 'student/property/specialty',
							'labels' => array(
									'en_US' => 'Specialty',
									'fr_FR' => 'Spécialité',
							),
					),
					'property_6' => array(
							'type' => 'repository',
							'definition' => 'student/property/boarding_school',
							'labels' => array(
									'en_US' => 'Boarding-school',
									'fr_FR' => 'Internat',
							),
					),
					'property_7' => array(
							'type' => 'repository',
							'definition' => 'student/property/class',
							'labels' => array(
									'en_US' => 'Class',
									'fr_FR' => 'Classe',
							),
					),
			),
			'order' => 'n_fn',
	),
	'commitmentAccount/index/p-pit-studies' => array(
			'title' => array('en_US' => 'P-PIT Commitments', 'fr_FR' => 'P-PIT Engagements'),
	),
	'commitmentAccount/search/p-pit-studies' => array(
			'title' => array('en_US' => 'Students', 'fr_FR' => 'Eleves'),
			'todoTitle' => array('en_US' => 'registered', 'fr_FR' => 'inscrits'),
			'main' => array(
					'status' => 'select',
					'place_id' => 'select',
					'property_1' => 'select',
					'property_7' => 'select',
					'property_6' => 'select',
					'customer_name' => 'contains',
			),
			'more' => array(
					'opening_date' => 'range',
					'closing_date' => 'range',
			),
	),
	'commitmentAccount/list/p-pit-studies' => array(
			'property_1' => 'image',
			'property_3' => 'photo',
			'n_fn' => 'text',
			'property_2' => 'phone',
	),
	'commitmentAccount/detail/p-pit-studies' => array(
			'title' => array('en_US' => 'Student sheet:', 'fr_FR' => 'FICHE ELEVE'),
			'displayAudit' => false,
			'tabs' => array(
					'contact_1' => array(
							'route' => 'commitmentAccount/update',
							'params' => array('type' => 'p-pit-studies'),
							'labels' => array('en_US' => 'Student', 'fr_FR' => 'Elève'),
					),
					'user' => array(
							'route' => 'commitmentAccount/updateUser',
							'params' => array('type' => 'p-pit-studies'),
							'labels' => array('en_US' => 'User account', 'fr_FR' => 'Compte utilisateur'),
					),
					'contact_2' => array(
							'route' => 'commitmentAccount/updateContact',
							'params' => array('type' => 'p-pit-studies', 'contactNumber' => 2),
							'labels' => array('en_US' => 'Father', 'fr_FR' => 'Père'),
					),
					'contact_3' => array(
							'route' => 'commitmentAccount/updateContact',
							'params' => array('type' => 'p-pit-studies', 'contactNumber' => 3),
							'labels' => array('en_US' => 'Mother', 'fr_FR' => 'Mère'),
					),
					'contact_4' => array(
							'route' => 'commitmentAccount/updateContact',
							'params' => array('type' => 'p-pit-studies', 'contactNumber' => 4),
							'labels' => array('en_US' => 'Other', 'fr_FR' => 'Autre'),
					),
			),
	),
	'commitmentAccount/update/p-pit-studies' => array(
			'status' => array('mandatory' => true),
			'n_first' => array('mandatory' => true),
			'n_last' => array('mandatory' => true),
			'property_3' => array('mandatory' => false),
			'email' => array('mandatory' => false),
			'birth_date' => array('mandatory' => false),
			'place_id' => array('mandatory' => true),
			'opening_date' => array('mandatory' => true),
			'closing_date' => array('mandatory' => false),
			'property_1' => array('mandatory' => true),
			'property_2' => array('mandatory' => false),
			'property_7' => array('mandatory' => true),
			'property_6' => array('mandatory' => false),
	),
	'commitmentAccount/updateContact/p-pit-studies' => array(
			'n_title' => array('mandatory' => false),
			'n_first' => array('mandatory' => true),
			'n_last' => array('mandatory' => true),
			'tel_work' => array('mandatory' => false),
			'tel_cell' => array('mandatory' => false),
			'email' => array('mandatory' => false),
			'adr_street' => array('mandatory' => false),
			'adr_extended' => array('mandatory' => false),
			'adr_zip' => array('mandatory' => false),
			'adr_city' => array('mandatory' => false),
			'adr_state' => array('mandatory' => false),
			'adr_country' => array('mandatory' => false),
			'locale' => array('mandatory' => true),
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
					'schooling' => array(
							'labels' => array('en_US' => 'Schooling', 'fr_FR' => 'Scolarité'),
							'color' => array('orange' => null),
					),
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

	'student/property/place' => array(
			'type' => 'table',
			'labels' => array(
					'en_US' => 'Center',
					'fr_FR' => 'Centre',
			),
	),
	
	'student/property/discipline' => array(
			'type' => 'select',
			'modalities' => array(
					'Football' => array('fr_FR' => 'Football'),
					'Basketball' => array('fr_FR' => 'Basketball'),
					'Equitation' => array('fr_FR' => 'Equitation'),
					'Golf' => array('fr_FR' => 'Golf'),
					'Tennis' => array('fr_FR' => 'Tennis'),
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
					'en_US' => 'School level',
					'fr_FR' => 'Niveau scolaire',
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
					'6e' => array('fr_FR' => '6e', 'level' => '6e'),
					'5e' => array('fr_FR' => '5e', 'level' => '5e'),
					'4e' => array('fr_FR' => '4e', 'level' => '4e'),
					'3e' => array('fr_FR' => '3e', 'level' => '3e'),
					'2nde' => array('fr_FR' => '2nde', 'level' => '2nde'),
					'1ère S' => array('fr_FR' => '1ère S', 'level' => '1ère', 'specialty' => 'S'),
					'1ère ES' => array('fr_FR' => '1ère ES', 'level' => '1ère', 'specialty' => 'ES'),
					'1ère STMG' => array('fr_FR' => '1ère STMG', 'level' => '1ère', 'specialty' => 'STMG'),
					'Term. S' => array('fr_FR' => 'Term. S', 'level' => 'Term.', 'specialty' => 'S'),
					'Term. ES' => array('fr_FR' => 'Term. ES', 'level' => 'Term.', 'specialty' => 'ES'),
					'Term. STMG' => array('fr_FR' => 'Term. STMG', 'level' => 'Term.', 'specialty' => 'STMG'),
			),
			'labels' => array(
					'en_US' => 'Class',
					'fr_FR' => 'Classe',
			),
	),
		
	'student/property/boarding_school' => array(
			'type' => 'select',
			'modalities' => array(
					'Externe' => array('fr_FR' => 'Externe'),
					'Interne' => array('fr_FR' => 'Internat'),
					'Weekend' => array('fr_FR' => 'Internat + WE'),
					'Dimanche' => array('fr_FR' => 'Internat + dimanche'),
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
			),
			'labels' => array(
					'en_US' => 'School year',
					'fr_FR' => 'Année scolaire',
			),
	),
	
	'student/property/school_period' => array(
			'type' => 'select',
			'modalities' => array(
					'Q1' => array('en_US' => 'Quarter 1', 'fr_FR' => 'Trim. 1'),
					'Q2' => array('en_US' => 'Quarter 2', 'fr_FR' => 'Trim. 2'),
					'Q3' => array('en_US' => 'Quarter 3', 'fr_FR' => 'Trim. 3'),
			),
			'labels' => array(
					'en_US' => 'Period',
					'fr_FR' => 'Période',
			),
	),

	'student/property/school_subject' => array(
			'type' => 'select',
			'modalities' => array(
					'french' => array('en_US' => 'French', 'fr_FR' => 'Français'),
					'mathematics' => array('en_US' => 'Mathematics', 'fr_FR' => 'Mathématiques'),
					'physics-chemistry' => array('en_US' => 'Physics/chemistry', 'fr_FR' => 'Physique/chimie'),
					'life-science' => array('en_US' => 'Life sciences', 'fr_FR' => 'SVT'),
					'll1' => array('en_US' => 'LL1', 'fr_FR' => 'LV1'),
					'll2' => array('en_US' => 'LL2', 'fr_FR' => 'LV2'),
					'economics' => array('en_US' => 'Economics', 'fr_FR' => 'Economie'),
					'history-geography' => array('en_US' => 'History/geography', 'fr_FR' => 'Histoire/géographie'),
			),
			'labels' => array(
					'en_US' => 'Subject',
					'fr_FR' => 'Matière'
			),
	),
/*
	'student/property/discipline_subject' => array(
			'type' => 'select',
			'modalities' => array(
					'training' => array('en_US' => 'Training', 'fr_FR' => 'Entrainement'),
					'footing' => array('en_US' => 'Footing', 'fr_FR' => 'Footing'),
			),
			'labels' => array(
					'en_US' => 'Appointment',
					'fr_FR' => 'Rendez-vous'
			),
	),*/

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
					'property_1' => 'select',
					'property_7' => 'select',
					'property_6' => 'select',
					'customer_name' => 'contains',
			),
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
			'signature1' => array('text' => 'Fait à Verneuil sur Seine, le %s', 'params' => array('date')),
			'signature3' => array('text' => 'Thierry DERKX', 'params' => array()),
			'signature5' => array('text' => 'Directeur', 'params' => array()),
	),

	'student/commitment' => array(
			'address1' => array('text' => 'Verneuil sur Seine, le %s', 'params' => array('date')),
			'title' => array('text' => 'ENGAGEMENT DE PRISE EN CHARGE', 'params' => array()),
			'paragraph1a' => array('text' => 'Je soussigné,', 'params' => array()),
			'paragraph2a' => array('text' => 'Monsieur Thierry DERKX, Directeur de F.M. SPORTS ETUDES', 'params' => array()),
			'paragraph4a' => array('text' => 'Nationalité : Française', 'params' => array()),
			'paragraph5a' => array('text' => 'Né le 29 mars 1964', 'params' => array()),
			'paragraph7a' => array('text' => 'Adresse : 61 Avenue du Château 78480 VERNEUIL SUR SEINE France', 'params' => array()),
			'paragraph8a' => array('text' => 'Numéro de téléphone travail : 01 39 71 12 12', 'params' => array()),
			'paragraph10a' => array('text' => 'M’engage à héberger %s %s', 'params' => array('n_first', 'n_last')),
			'paragraph12a' => array('text' => 'dans notre résidence du SPORTS ETUDES située : 15 Quai Rennequin Sualem - 78380 Bougival - France, en pension complète du lundi au vendredi, avec possibilité de rester en internat les week-ends également.', 'params' => array('n_first', 'n_last')),
			'paragraph14a' => array('text' => '%s %s, né le %s, est inscrit en SPORTS ETUDES section %s pour l’année scolaire %s. Il sera inscrit en parallèle au CNED (L’Education Nationale Française) en classe de %s.', 'params' => array('n_first', 'n_last', 'birth_date', 'sport', 'school_year', 'school_level')),
			'paragraph16a' => array('text' => 'Fait à la demande de l’intéressé,', 'params' => array()),
			'signature1' => array('text' => 'Thierry DERKX', 'params' => array()),
			'signature3' => array('text' => 'Directeur', 'params' => array()),
	),
		
	'student/confirmation' => array(
			'address1' => array('text' => 'Verneuil sur Seine, le %s', 'params' => array('date')),
			'title' => array('text' => 'CONFIRMATION D\'INSCRIPTION', 'params' => array()),
			'paragraph1a' => array('text' => 'FM SPORTS ET ETUDES certifie que l’élève dont les coordonnées figurent ci-dessous :', 'params' => array()),
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
									'modalities' => array(
											'french' => array('en_US' => 'French', 'fr_FR' => 'Français'),
											'mathematics' => array('en_US' => 'Mathematics', 'fr_FR' => 'Mathématiques'),
											'physics-chemistry' => array('en_US' => 'Physics/chemistry', 'fr_FR' => 'Physique/chimie'),
											'life-science' => array('en_US' => 'Life sciences', 'fr_FR' => 'SVT'),
											'll1' => array('en_US' => 'LL1', 'fr_FR' => 'LV1'),
											'll2' => array('en_US' => 'LL2', 'fr_FR' => 'LV2'),
											'economics' => array('en_US' => 'Economics', 'fr_FR' => 'Economie'),
											'history-geography' => array('en_US' => 'History/geography', 'fr_FR' => 'Histoire/géographie'),
									),
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
					'category' => array(
							'type' => 'select',
							'modalities' => array(
									'absence' => array('en_US' => 'Absence', 'fr_FR' => 'Absence'),
									'lateness' => array('en_US' => 'Lateness', 'fr_FR' => 'Retard'),
							),
							'labels' => array(
									'en_US' => 'Category',
									'fr_FR' => 'Catégorie',
							),
					),
					'school_year' => array(
							'type' => 'repository',
							'definition' => 'student/property/school_year',
					),
					'type' => array(
							'type' => 'select',
							'modalities' => array(
									'sport' => array('en_US' => 'Sport', 'fr_FR' => 'Sport'),
									'schooling' => array('en_US' => 'Scolarité', 'fr_FR' => 'Scolarité'),
									'boarding_school' => array('en_US' => 'Boarding school', 'fr_FR' => 'Internat'),
							),
							'labels' => array(
									'en_US' => 'Type',
									'fr_FR' => 'Type',
							),
					),
					'name' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Name',
									'fr_FR' => 'Nom',
							),
					),
					'motive' => array(
							'type' => 'repository',
							'definition' => 'absence/property/motive',
					),
					'date' => array(
							'type' => 'date',
							'labels' => array(
									'en_US' => 'Date',
									'fr_FR' => 'Date',
							),
					),
					'duration' => array(
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Lateness (mn)',
									'fr_FR' => 'Retard (mn)',
							),
					),
					'observations' => array(
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
					'family' => array('en_US' => 'Family', 'fr_FR' => 'Familial'),
					'transport' => array('en_US' => 'Transport', 'fr_FR' => 'Transport'),
					'unjustified' => array('en_US' => 'Unjustified', 'fr_FR' => 'Non justifié'),
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
			'main' => array('type' => 'select', 'name' => 'contains', 'date' => 'range'),
			'more' => array(),
	),
	
	'absence/list' => array(
			'name' => 'text',
			'category' => 'select',
			'subject' => 'text',
			'date' => 'date',
			'duration' => 'number',
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
			'title' => array('en_US' => 'Evaluations', 'fr_FR' => 'Evaluations'),
	),

	'note/search/homework' => array(
			'title' => array('en_US' => 'Homework notebook', 'fr_FR' => 'Cahier de texte'),
	),
		
	'note/search' => array(
			'todoTitle' => array('en_US' => 'current period', 'fr_FR' => 'période en cours'),
			'searchTitle' => array('en_US' => 'Search', 'fr_FR' => 'recherche'),
			'main' => array(
					'type' => 'select',
					'class' => 'select',
					'subject' => 'select',
					'date' => 'date',
			),
			'more' => array(
			),
	),
	
	'note/list' => array(
			'type' => 'select',
			'class' => 'select',
			'subject' => 'select',
			'date' => 'date',
	),
	
	'note/update' => array(
			'types' => array(
					'schooling' => array(
							'labels' => array('en_US' => 'Schooling', 'fr_FR' => 'Scolarité'),
							'subjects' => array(
									'french' => array('en_US' => 'French', 'fr_FR' => 'Français'),
									'mathematics' => array('en_US' => 'Mathematics', 'fr_FR' => 'Mathématiques'),
									'physics-chemistry' => array('en_US' => 'Physics/chemistry', 'fr_FR' => 'Physique/chimie'),
									'life-science' => array('en_US' => 'Life sciences', 'fr_FR' => 'SVT'),
									'll1' => array('en_US' => 'LL1', 'fr_FR' => 'LV1'),
									'll2' => array('en_US' => 'LL2', 'fr_FR' => 'LV2'),
									'economics' => array('en_US' => 'Economics', 'fr_FR' => 'Economie'),
									'history-geography' => array('en_US' => 'History/geography', 'fr_FR' => 'Histoire/géographie'),
							),
					),
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

	'ppitRoles' => array(
			'PpitStudies' => array(
					'manager' => array(
							'show' => true,
							'default' => true,
							'labels' => array(
									'en_US' => 'School life',
									'fr_FR' => 'Vie scolaire',
							),
					),
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
					'boarding_school_headmaster' => array(
							'labels' => array(
							'show' => true,
									'en_US' => 'Boarding school headmaster',
									'fr_FR' => 'Maître d\'internat',
							),
					),
					'medical' => array(
							'labels' => array(
							'show' => true,
									'en_US' => 'Medical',
									'fr_FR' => 'Médical',
							),
					),
					'student' => array(
							'labels' => array(
							'show' => true,
									'en_US' => 'Student',
									'fr_FR' => 'Elève',
							),
					),
					'representative' => array(
							'labels' => array(
							'show' => true,
									'en_US' => 'Legal representative',
									'fr_FR' => 'Représentant légal',
							),
					),
			),
	),

	'contact/perimeters' => array(
			'p-pit-studies' => array(
					'student/property/place' => null,
					'student/property/discipline' => null,
					'student/property/level' => null,
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
			'note/list/evaluation' => array(
					'en_US' => '
					',
					'fr_FR' => '
<h4>Liste des évaluations et bulletins</h4>
<p>Cette liste permet de retrouver toutes les évaluations et bulletins qui ont été saisies. Vous disposez de filtres sur la classe, la matière et la date.</p>
',
			),
			'note/updateEvaluation' => array(
					'en_US' => '
					',
					'fr_FR' => '
<h4>Détail d\'une évaluation</h4>
<p>Depuis la liste des évaluations/bulletins, vous accédez au détail. Vous pouvez corriger ou supprimer une évaluation ou un bulletin.</p>
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
			'commitmentAccount/search/p-pit-studies/title' => array(
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
</p>',
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
</p>',
			),
	),
);
