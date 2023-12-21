<?php
namespace PpitStudies;

include('commitment_message_p_pit_studies.php');
include('core_account_message_p_pit_studies.php');
include('core_account_message_teacher.php');
include('event_message_p_pit_studies.php');
include('note_link_generic.php');
include('event_absence.php');

return array_merge(
[
	'controllers' => array(
        'invokables' => array(
        	'PpitStudies\Controller\Absence' => 'PpitStudies\Controller\AbsenceController',
        	'PpitStudies\Controller\Event' => 'PpitStudies\Controller\EventController',
        	'PpitStudies\Controller\Note' => 'PpitStudies\Controller\NoteController',
        	'PpitStudies\Controller\NoteLink' => 'PpitStudies\Controller\NoteLinkController',
        	'PpitStudies\Controller\Report' => 'PpitStudies\Controller\ReportController',
        	'PpitStudies\Controller\Student' => 'PpitStudies\Controller\StudentController',
        	'PpitStudies\Controller\Subject' => 'PpitStudies\Controller\SubjectController',
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
/*        						'index' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/index[/:app]',
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),*/
        						'indexV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/index-v2[/:entryId]',
        										'defaults' => array(
        												'action' => 'indexV2',
        										),
        								),
        						),
/*	       						'search' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/search',
        										'defaults' => array(
        												'action' => 'search',
        										),
        								),
        						),*/
	       						'searchV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/search-v2',
        										'defaults' => array(
        												'action' => 'searchV2',
        										),
        								),
        						),
/*	       						'list' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/list',
        										'defaults' => array(
        												'action' => 'list',
        										),
        								),
        						),*/
	       						'listV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/list-v2',
        										'defaults' => array(
        												'action' => 'listV2',
        										),
        								),
        						),
	       						'get' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/get[/:type]',
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
	       						/*'detail' => array(
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
        						),*/
/*	       						'update' => array(
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
		        				),*/
	       						'updateV2' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/update-v2[/:id][/:act]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'updateV2',
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
/*        	'planning' => array(
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
        	),*/
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
/*        						'index' => array(
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
        						),*/
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
/*	       						'export' => array(
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
				                ),*/
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
								'v1' => array(
										'type' => 'segment',
										'options' => array(
												'route' => '/v1[/:id]',
												'defaults' => array(
														'action' => 'v1',
												),
										),
								),
								'index' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/index[/:category][/:type][/:entryId]',
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),
        						'indexV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/index-v2[/:category][/:type][/:entryId]',
        										'defaults' => array(
        												'action' => 'indexV2',
        										),
        								),
        						),
/*				       			'search' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/search[/:category][/:type]',
        										'defaults' => array(
        												'action' => 'search',
        										),
        								),
        						),*/
				       			'searchV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/search-v2[/:category][/:type]',
        										'defaults' => array(
        												'action' => 'searchV2',
        										),
        								),
        						),
/*	       						'list' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/list[/:category][/:type]',
        										'defaults' => array(
        												'action' => 'list',
        										),
        								),
        						),*/
	       						'listV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/list-v2[/:category][/:type]',
        										'defaults' => array(
        												'action' => 'listV2',
        										),
        								),
        						),
	       						'teacherList' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/teacher-list[/:category][/:type]',
        										'defaults' => array(
        												'action' => 'teacherList',
        										),
        								),
        						),
	       						'get' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/get[/:category][/:type]',
        										'defaults' => array(
        												'action' => 'get',
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
/*	       						'detail' => array(
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
        						),*/
/*	       						'update' => array(
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
		        				),*/
	       						'updateV2' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/update-v2[/:id][/:act]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'updateV2',
		        								),
		        						),
		        				),
	       						'apiEvaluation' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/api-evaluation[/:type][/:id]',
		        								'defaults' => array(
		        										'action' => 'apiEvaluation',
		        								),
		        						),
		        				),
	       						'evaluation' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/evaluation[/:type][/:id]',
		        								'defaults' => array(
		        										'action' => 'evaluation',
		        								),
		        						),
		        				),
	       						'updateEvaluationV2' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/update-evaluation-v2[/:id][/:act]',
		        								'constraints' => array(
		        										'id'     => '[0-9]*',
		        								),
		        								'defaults' => array(
		        										'action' => 'updateEvaluationV2',
		        								),
		        						),
		        				),
	       						'apiUpdateAverage' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/api-update-average',
		        								'defaults' => array(
		        										'action' => 'apiUpdateAverage',
		        								),
		        						),
		        				),
				       			'repair' => [
									'type' => 'segment',
									'options' => [
										'route' => '/repair',
										'defaults' => [
											'action' => 'repair',
										],
									],
								],
				       			'batchAverage' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/batch-average',
		        								'defaults' => array(
		        										'action' => 'batchAverage',
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

        	'noteLink' => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/note-link',
                    'defaults' => [
                        'controller' => 'PpitStudies\Controller\NoteLink',
                        'action'     => 'v1',
                    ],
                ],
           		'may_terminate' => true,
	       		'child_routes' => [
					'index' => [
						'type' => 'segment',
						'options' => [
							'route' => '/index[/:category][/:type][/:entryId]',
							'defaults' => [
								'action' => 'index',
							],
						],
					],
					'search' => [
						'type' => 'segment',
						'options' => [
							'route' => '/search[/:category][/:type]',
							'defaults' => [
								'action' => 'search',
							],
						],
					],
	       			'list' => [
						'type' => 'segment',
						'options' => [
							'route' => '/list[/:category][/:type]',
							'defaults' => [
								'action' => 'list',
							],
						],
					],
					'studentList' => [
						'type' => 'segment',
						'options' => [
							'route' => '/student-list[/:category][/:type]',
							'defaults' => [
								'action' => 'studentList',
							],
						],
					],
	       			'group' => [
						'type' => 'segment',
						'options' => [
							'route' => '/group[/:category][/:type]',
							'defaults' => [
								'action' => 'group',
							],
						],
					],
	       			'update' => [
						'type' => 'segment',
						'options' => [
							'route' => '/update[/:id]',
							'defaults' => [
								'action' => 'update',
							],
						],
					],
					'generateReport' => [
						'type' => 'segment',
						'options' => [
							'route' => '/generate-report',
							'defaults' => [
								'action' => 'generateReport',
							],
						],
				 	],
	       			'v1' => [
						'type' => 'segment',
						'options' => [
							'route' => '/v1[/:category][/:type][/:id]',
							'defaults' => [
								'action' => 'v1',
							],
						],
					],
	       			'repair' => [
						'type' => 'segment',
						'options' => [
							'route' => '/repair',
							'defaults' => [
								'action' => 'repair',
							],
						],
					],
	       		],
	       	],

			'report' => [
				'type'    => 'literal',
				'options' => [
					'route'    => '/report',
					'defaults' => [
						'controller' => 'PpitStudies\Controller\Report',
						'action'     => 'post',
					],
				],
				'may_terminate' => true,
				'child_routes' => [
					'v1' => [
						'type' => 'segment',
						'options' => [
							'route' => '/v1[/:id]',
							'defaults' => [
								'action' => 'v1',
							],
						],
					],
					'post' => [
						'type' => 'segment',
						'options' => [
							'route' => '/post',
							'defaults' => [
								'action' => 'post',
							],
						],
					],
					'link' => [
						'type' => 'segment',
						'options' => [
							'route' => '/link',
							'defaults' => [
								'action' => 'link',
							],
						],
					],
					'fix' => [
						'type' => 'segment',
						'options' => [
							'route' => '/fix',
							'defaults' => [
								'action' => 'fix',
							],
						],
					],
					'globalFix' => [
						'type' => 'segment',
						'options' => [
							'route' => '/global-fix',
							'defaults' => [
								'action' => 'globalFix',
							],
						],
					],
					'getStudentsFromGroups' => [
						'type' => 'segment',
						'options' => [
							'route' => '/get-students-from-groups[/:id][/:report_id]',
							'defaults' => [
								'action' => 'getStudentsFromGroups',
							],
						],
					],
				],
			],
	       			
/*        	'studentNotification' => array(
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
            ),*/
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
/*        	'progress' => array(
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
            ),*/
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
        										'route' => '/index[/:app][/:entryId]',
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),
        						'indexV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/index-v2[/:app][/:entryId]',
        										'defaults' => array(
        												'action' => 'indexV2',
        										),
        								),
        						),
	       						'studentHomeV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/student-home-v2[/:account_id]',
		        								'constraints' => array(
		        										'account_id'     => '[0-9]*',
		        								),
        										'defaults' => array(
        												'action' => 'studentHomeV2',
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
	       						'searchV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/search-v2',
        										'defaults' => array(
        												'action' => 'searchV2',
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
	       						'listV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/list-v2',
        										'defaults' => array(
        												'action' => 'listV2',
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
	       						'groupV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/group-v2[:type]',
        										'defaults' => array(
        												'action' => 'groupV2',
        										),
        								),
        						),
	       						'addAbsenceV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add-absence-v2[/:type]',
        										'defaults' => array(
        												'action' => 'addAbsenceV2',
        										),
        								),
        						),
	       						'addNoteV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add-note-v2[/:type][/:class]',
        										'defaults' => array(
        												'action' => 'addNoteV2',
        										),
        								),
        						),
	       						'addEvaluationV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add-evaluation-v2[/:type][/:class]',
        										'defaults' => array(
        												'action' => 'addEvaluationV2',
        										),
        								),
        						),
	       						'planningV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/planning-v2[/:id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'planningV2',
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
	       						'content' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/content[/:id][/:category]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'content',
        										),
        								),
        						),
	       						'absenceV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/absence-v2[/:account_id][/:start_date][/:end_date]',
        										'constraints' => array(
        												'account_id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'absenceV2',
        										),
        								),
        						),
	       						'evaluationV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/evaluation-v2[/:id][/:mock]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'evaluationV2',
        										),
        								),
        						),
	       						'examV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/exam-v2[/:id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'examV2',
        										),
        								),
        						),
	       						'generateAttendance' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/generate-attendance[/:account_id][/:start_date][/:end_date]',
        										'constraints' => array(
        												'account_id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'generateAttendance',
        										),
        								),
        						),
	       						'downloadAttendance' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/download-attendance[/:account_id][/:start_date][/:end_date]',
        										'constraints' => array(
        												'account_id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'downloadAttendance',
        										),
        								),
        						),
	       						'homeworkV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/homework-v2[/:account_id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'homeworkV2',
        										),
        								),
        						),
	       						'reportV2' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/report-v2[/:id]',
        										'constraints' => array(
        												'id' => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'reportV2',
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
	       						'nomad' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/nomad[/:request][/:from]',
		        								'defaults' => array(
		        										'action' => 'nomad',
		        								),
		        						),
		        				),
	       						'keystone' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/keystone',
		        								'defaults' => array(
		        										'action' => 'keystone',
		        								),
		        						),
		        				),
	       						'init' => array(
		        						'type' => 'segment',
		        						'options' => array(
		        								'route' => '/init',
		        								'defaults' => array(
		        										'action' => 'init',
		        								),
		        						),
		        				),
	       				),
	    	   	),

				'subject' => [
					'type'    => 'literal',
					'options' => [
						'route'    => '/subject',
						'defaults' => [
							'controller' => 'PpitStudies\Controller\Subject',
							'action'     => 'v1',
						],
					],
					'may_terminate' => true,
					'child_routes' => [
						'v1' => [
							'type' => 'segment',
							'options' => [
								'route' => '/v1[/:id]',
								'defaults' => [
									'action' => 'v1',
								],
							],
						],
					],
				],
			),
    ),
	'bjyauthorize' => array(
		// Guard listeners to be attached to the application event manager
		'guards' => array(
			'BjyAuthorize\Guard\Route' => array(

				array('route' => 'absence', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/indexV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/searchV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/listV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/get', 'roles' => array('user')),
				array('route' => 'absence/export', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/updateV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'absence/reprise', 'roles' => array('admin')),

				array('route' => 'studentEvent/planning', 'roles' => array('user')),

				array('route' => 'note', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'note/v1', 'roles' => array('guest')),
				array('route' => 'note/index', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'note/indexV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'note/searchV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'note/listV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'note/teacherList', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'note/get', 'roles' => array('manager', 'coach', 'user')),
				array('route' => 'note/export', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'note/exportCsv', 'roles' => array('admin')),
				array('route' => 'note/updateV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'note/apiEvaluation', 'roles' => array('guest', 'guest')),
				array('route' => 'note/evaluation', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'note/apiUpdateAverage', 'roles' => array('guest', 'guest')),
				array('route' => 'note/updateEvaluationV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'note/repair', 'roles' => array('admin')),
				array('route' => 'note/batchAverage', 'roles' => array('guest')),
				array('route' => 'note/reprise', 'roles' => array('admin')),

				array('route' => 'noteLink', 'roles' => array('manager', 'coach')),
				array('route' => 'noteLink/index', 'roles' => array('manager', 'coach')),
				array('route' => 'noteLink/search', 'roles' => array('manager', 'coach')),
				array('route' => 'noteLink/list', 'roles' => array('manager', 'coach')),
				array('route' => 'noteLink/studentList', 'roles' => array('manager', 'coach')),
				array('route' => 'noteLink/group', 'roles' => array('manager', 'coach')),
				array('route' => 'noteLink/update', 'roles' => array('manager')),
				array('route' => 'noteLink/generateReport', 'roles' => array('manager')),
				array('route' => 'noteLink/v1', 'roles' => array('guest')),
				array('route' => 'noteLink/repair', 'roles' => array('admin')),

				array('route' => 'report/post', 'roles' => array('manager')),
				array('route' => 'report/link', 'roles' => array('manager')),
				array('route' => 'report/fix', 'roles' => array('guest')),
				array('route' => 'report/globalFix', 'roles' => array('guest')),
				array('route' => 'report/v1', 'roles' => array('guest')),
				array('route' => 'report/getStudentsFromGroups', 'roles' => array('guest')),
				
				array('route' => 'student', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/registrationIndex', 'roles' => array('manager')),
				array('route' => 'student/index', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/indexV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/studentHomeV2', 'roles' => array('user')),
				array('route' => 'student/searchV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/export', 'roles' => array('manager', 'coach', 'teacher')),
            	array('route' => 'student/list', 'roles' => array('manager', 'coach', 'teacher')),
            	array('route' => 'student/listV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/detail', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/group', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/groupV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/addAbsenceV2', 'roles' => array('manager', 'coach', 'teacher')),
				array('route' => 'student/addNoteV2', 'roles' => array('manager', 'teacher')),
				array('route' => 'student/addEvaluationV2', 'roles' => array('manager', 'teacher')),
				array('route' => 'student/listEvaluation', 'roles' => array('manager', 'teacher')),
				array('route' => 'student/planningV2', 'roles' => array('user')),
				array('route' => 'student/file', 'roles' => array('user')),
				array('route' => 'student/content', 'roles' => array('user')),
				array('route' => 'student/absenceV2', 'roles' => array('user')),
				array('route' => 'student/homeworkV2', 'roles' => array('user')),
				array('route' => 'student/evaluationV2', 'roles' => array('guest')),
				array('route' => 'student/examV2', 'roles' => array('guest')),
				array('route' => 'student/generateAttendance', 'roles' => array('operational_management', 'sales_manager', 'manager')),
				array('route' => 'student/downloadAttendance', 'roles' => array('operational_management', 'sales_manager', 'manager')),
				array('route' => 'student/reportV2', 'roles' => array('guest')),
				array('route' => 'student/download', 'roles' => array('guest')),
				array('route' => 'student/downloadExam', 'roles' => array('guest')),
				array('route' => 'student/nomad', 'roles' => array('guest')),
				array('route' => 'student/keystone', 'roles' => array('guest')),
				array('route' => 'student/init', 'roles' => array('admin')),

				array('route' => 'subject/v1', 'roles' => array('guest')),
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
			'subscriptions' => array(
				'route' => 'commitment/index',
				'params' => array('entry' => 'account', 'type' => 'p-pit-studies', 'entryId' => 'subscriptions'),
				'glyphicon' => 'glyphicon-link',
				'label' => array(
					'default' => 'Inscriptions',
				),
			),
	
			'teacher' => array(
				'route' => 'account/indexAlt',
				'params' => array('entry' => 'account', 'type' => 'teacher', 'app' => 'p-pit-studies', 'entryId' => 'teacher'),
				'glyphicon' => 'glyphicon-user',
				'label' => array(
					'default' => 'Trainers',
					'fr_FR' => 'Intervenants',
				),
			),
	
			'group' => array(
				'route' => 'account/indexAlt',
				'params' => array('entry' => 'group', 'type' => 'group', 'app' => 'p-pit-studies', 'entryId' => 'group'),
				'label' => array(
					'default' => 'Groups',
					'fr_FR' => 'Groupes',
				),
			),
	
			'student' => array(
				'route' => 'student/index',
				'params' => array('app' => 'p-pit-studies', 'type' => '', 'entryId' => 'student'),
				'urlParams' => array(),
				'glyphicon' => 'glyphicon-list-alt',
				'label' => array(
					'default' => 'Students/Groups',
					'fr_FR' => 'Elèves/Groupes',
				),
			),

			// planning_implement_event_calendar: Le planning de P-Pit Studies implémente la feature event/calendar de l'enabler PpitCore.
			
			'calendar' => array(
				'route' => 'event/calendar',
				'params' => array('type' => 'calendar', 'category' => 'calendar', 'entryId' => 'calendar'),
				'urlParams' => '?status=new',
				'glyphicon' => 'glyphicon-calendar',
				'label' => array(
					'default' => 'Planning',
					'fr_FR' => 'Planning',
				),
			),
				
			'absence' => array(
				'route' => 'event/indexAlt',
				'params' => array('type' => 'absence', 'entryId' => 'absence'),
				'urlParams' => array(),
				'glyphicon' => 'glyphicon-hourglass',
				'label' => array(
					'default' => 'Absences',
					'fr_FR' => 'Absences',
				),
			),

			'lateness' => array(
				'route' => 'absence/indexV2',
				'params' => array('type' => '', 'entryId' => 'lateness'),
				'urlParams' => array(),
				'glyphicon' => 'glyphicon-hourglass',
				'label' => array(
					'en_US' => 'Lateness',
					'fr_FR' => 'Retards',
				),
			),
					
			'homework' => array(
				'route' => 'note/indexV2',
				'params' => array('category' => 'homework', 'type' => '*', 'entryId' => 'homework'),
				'urlParams' => array(),
				'glyphicon' => 'glyphicon-calendar',
				'label' => array(
					'default' => 'Homework notebook',
					'fr_FR' => 'Cahier de texte',
				),
			),
			'evaluation' => array(
				'route' => 'noteLink/index',
				'params' => array('category' => 'evaluation', 'type' => 'note', 'entryId' => 'evaluation'),
				'urlParams' => array(),
				'glyphicon' => 'glyphicon-dashboard',
				'label' => array(
					'default' => 'Evaluations',
					'fr_FR' => 'Évaluations',
				),
			),
			'report' => array(
				'route' => 'noteLink/index',
				'params' => array('category' => 'evaluation', 'type' => 'report', 'entryId' => 'report'),
				'urlParams' => array(),
				'glyphicon' => 'glyphicon-dashboard',
				'label' => array(
					'default' => 'Reports',
					'fr_FR' => 'Bulletins',
				),
			),
		),
		'labels' => array(
			'default' => '2pit Studies',
			'fr_FR' => 'P-Pit Studies',
		),
		'support' => [
			['labels' => ['default' => 'Mode opératoire P-Pit Studies'], 'href' => ['default' => '/img/solo_studies/presentation_p_pit_studies_rp.pdf']],
			['labels' => ['default' => 'Mode opératoire P-Pit Learning Formateur'], 'href' => ['default' => '/img/solo_studies/presentation_p_pit_learning_formateur.pdf']],
			['labels' => ['default' => 'Vidéo de présentation Inscriptions'], 'href' => ['default' => '/img/solo_studies/video_p_pit_studies_inscription.mp4']],
			['labels' => ['default' => 'Vidéo de présentation Planning'], 'href' => ['default' => '/img/solo_studies/video_p_pit_studies_planning.mp4']],
			['labels' => ['default' => 'Vidéo de présentation Formateur'], 'href' => ['default' => '/img/solo_studies/video_p_pit_learning.mp4']],
		],
	),
	
	'admin/p-pit-studies' => array(
/*			'student/property/contact_meeting_context',
			'student/property/discipline',
			'student/property/level',
			'student/property/class',
			'student/property/boarding_school',
			'student/property/school_year',
			'student/property/school_period',*/
			'student/property/evaluationCategory',
//			'student/property/reportMention',
			'student/property/school_subject',
//			'absence/property/motive',
	),

	'currentApplication' => 'p-pit-studies',
	'currentPeriodStart' => '2017-09-01',
	'currentPeriodEnd' => '2017-10-20',

	'studentHome' => 'student/studentHomeV2',
	
	'place_config/default' => array(
			'school_periods' => array(
					'type' => 'periods',
					'end_dates' => array(
/*							'Q1' => '2020-12-18',
							'Q2' => '2021-03-31',
							'Q3' => '2021-06-30',*/
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

	// Product option
	
	'productOption/p-pit-studies/property/category' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'deplacement' => ['default' => 'Deplacement', 'fr_FR' => 'Déplacement'],
			'hosting' => ['default' => 'Hosting', 'fr_FR' => 'Hébergement'],
			'half_board' => ['default' => 'Half board', 'fr_FR' => 'Demi-pension'],
			'sunday_hosting' => ['default' => 'Sunday hosting', 'fr_FR' => 'Accueil dimanche'],
			'weekend_hosting' => ['default' => 'Weekend hosting', 'fr_FR' => 'Accueil week-end'],
			'transport' => ['default' => 'Transport', 'fr_FR' => 'Transport'],
			'distance-learning' => ['default' => 'Distance learning', 'fr_FR' => 'CNED'],
			'insurance' => ['default' => 'Insurance', 'fr_FR' => 'Assurance'],
			'licence' => ['default' => 'Licence', 'fr_FR' => 'Licence'],
			'medical' => ['default' => 'Medical expenses', 'fr_FR' => 'Frais médicaux'],
			'trousseau' => ['default' => 'Trousseau', 'fr_FR' => 'Trousseau'],
			'registration_fees' => ['default' => 'Registration fees', 'fr_FR' => 'Droits d’inscription'],
			'scholarship' => ['default' => 'Scholarship', 'fr_FR' => 'Bourse'],
			'discount' => ['default' => 'Discount', 'fr_FR' => 'Remise'],
			'late_arrival' => ['default' => 'Late arrival', 'fr_FR' => 'Arrivée tardive'],
			'other' => ['default' => 'Other option', 'fr_FR' => 'Option autre'],
			'' => ['default' => 'Undefined option', 'fr_FR' => 'Option indéfinie'],
		),
	),
	
	// Commitment

	'commitment/p-pit-studies/property/status' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'new' => array('en_US' => 'New', 'fr_FR' => 'Nouveau'),
			'confirmed' => array('en_US' => 'Confirmed', 'fr_FR' => 'Confirmé'),
			'approved' => array('en_US' => 'Approved', 'fr_FR' => 'Validé'),
			'invoiced' => array('en_US' => 'Invoiced', 'fr_FR' => 'Facturé', 'auto' => true),
			'settled' => array('en_US' => 'Settled', 'fr_FR' => 'Réglé', 'auto' => true),
			'registered' => array('en_US' => 'Registered', 'fr_FR' => 'Comptabilisé', 'auto' => true),
			'archived' => array('en_US' => 'Archived', 'fr_FR' => 'Archivé'),
		),
		'labels' => array(
			'en_US' => 'Commitment status',
			'fr_FR' => 'Statut d’engagement',
		),
	),

	'commitment/p-pit-studies/property/account_id' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Client',
			'fr_FR' => 'Client',
		),
	),
	
	'commitment/p-pit-studies/property/account_status' => array(
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
			'retention' => array('en_US' => 'Retention', 'fr_FR' => 'Ré-inscrit'),
			'suspended' => array('en_US' => 'Suspended', 'fr_FR' => 'Suspendu'),
			'canceled' => array('en_US' => 'Canceled', 'fr_FR' => 'Annulé'),
			'gone' => array('en_US' => 'Gone', 'fr_FR' => 'Parti'),
		),
		'perspectives' => array(
				'contact' => array('new', 'interested', 'candidate', 'answer', 'conversion', 'gone'),
				'account' => array('committed', 'visa', 'active', 'retention', 'suspended', 'canceled'),
		),
		'labels' => array(
			'en_US' => 'Account status',
			'fr_FR' => 'Statut du compte',
		),
	),

	'commitment/p-pit-studies/property/n_fn' => array(
		'definition' => 'inline',
		'type' => 'phone',
		'labels' => array(
			'en_US' => 'Name',
			'fr_FR' => 'Nom',
		),
	),

	'commitment/p-pit-studies/property/email_work' => array(
		'definition' => 'inline',
		'type' => 'email',
		'labels' => array(
			'default' => 'School e-mail',
			'fr_FR' => 'Email école',
		),
	),
	
	'commitment/p-pit-studies/property/tel_cell' => array(
		'definition' => 'inline',
		'type' => 'phone',
		'labels' => array(
			'en_US' => 'Cellular',
			'fr_FR' => 'Mobile',
		),
	),

	'commitment/p-pit-studies/property/adr_street' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Address',
			'fr_FR' => 'Adresse',
		),
	),
	
	'commitment/p-pit-studies/property/adr_zip' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Zip code',
			'fr_FR' => 'Code postal',
		),
	),

	'commitment/p-pit-studies/property/adr_city' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'City',
			'fr_FR' => 'Ville',
		),
	),

	'commitment/p-pit-studies/property/adr_country' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Country',
			'fr_FR' => 'Pays',
		),
	),

	'commitment/p-pit-studies/property/address' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Address',
			'fr_FR' => 'Adresse',
		),
	),

	'commitment/p-pit-studies/property/photo_link_id' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Photo link',
			'fr_FR' => 'Lien photo',
		),
	),
	
	'commitment/p-pit-studies/property/default_means_of_payment' => ['definition' => 'core_account/generic/property/default_means_of_payment'],
	'commitment/p-pit-studies/property/transfer_order_id' => ['definition' => 'core_account/generic/property/transfer_order_id'],
	'commitment/p-pit-studies/property/transfer_order_date' => ['definition' => 'core_account/generic/property/transfer_order_date'],
	'commitment/p-pit-studies/property/bank_identifier' => ['definition' => 'core_account/generic/property/bank_identifier'],
	'commitment/p-pit-studies/property/account_contact_history' => ['definition' => 'core_account/generic/property/contact_history'],

	'commitment/p-pit-studies/property/n_title_2' => ['definition' => 'core_account/p-pit-studies/property/n_title_2'],
	'commitment/p-pit-studies/property/n_first_2' => ['definition' => 'core_account/p-pit-studies/property/n_first_2'],
	'commitment/p-pit-studies/property/n_last_2' => ['definition' => 'core_account/p-pit-studies/property/n_last_2'],
	'commitment/p-pit-studies/property/n_fn_2' => ['definition' => 'core_account/p-pit-studies/property/n_fn_2'],
	'commitment/p-pit-studies/property/email_2' => ['definition' => 'core_account/p-pit-studies/property/email_2'],
	'commitment/p-pit-studies/property/tel_work_2' => ['definition' => 'core_account/p-pit-studies/property/tel_work_2'],
	'commitment/p-pit-studies/property/tel_cell_2' => ['definition' => 'core_account/p-pit-studies/property/tel_cell_2'],

	'commitment/p-pit-studies/property/n_title_3' => ['definition' => 'core_account/p-pit-studies/property/n_title_3'],
	'commitment/p-pit-studies/property/n_first_3' => ['definition' => 'core_account/p-pit-studies/property/n_first_3'],
	'commitment/p-pit-studies/property/n_last_3' => ['definition' => 'core_account/p-pit-studies/property/n_last_3'],
	'commitment/p-pit-studies/property/n_fn_3' => ['definition' => 'core_account/p-pit-studies/property/n_fn_3'],
	'commitment/p-pit-studies/property/email_3' => ['definition' => 'core_account/p-pit-studies/property/email_3'],
	'commitment/p-pit-studies/property/tel_work_3' => ['definition' => 'core_account/p-pit-studies/property/tel_work_3'],
	'commitment/p-pit-studies/property/tel_cell_3' => ['definition' => 'core_account/p-pit-studies/property/tel_cell_3'],
	
	'commitment/p-pit-studies/property/n_title_4' => ['definition' => 'core_account/p-pit-studies/property/n_title_4'],
	'commitment/p-pit-studies/property/n_first_4' => ['definition' => 'core_account/p-pit-studies/property/n_first_4'],
	'commitment/p-pit-studies/property/n_last_4' => ['definition' => 'core_account/p-pit-studies/property/n_last_4'],
	'commitment/p-pit-studies/property/n_fn_4' => ['definition' => 'core_account/p-pit-studies/property/n_fn_4'],
	'commitment/p-pit-studies/property/email_4' => ['definition' => 'core_account/p-pit-studies/property/email_4'],
	'commitment/p-pit-studies/property/tel_work_4' => ['definition' => 'core_account/p-pit-studies/property/tel_work_4'],
	'commitment/p-pit-studies/property/tel_cell_4' => ['definition' => 'core_account/p-pit-studies/property/tel_cell_4'],
	
	'commitment/p-pit-studies/property/caption' => ['definition' => 'student/property/school_year'],
	'commitment/p-pit-studies/property/property_1' => ['definition' => 'student/property/level'],
	'commitment/p-pit-studies/property/property_2' => ['definition' => 'student/property/specialty'],
	'commitment/p-pit-studies/property/property_3' => ['definition' => 'student/property/boarding_school'],
	
	'commitment/p-pit-studies/property/property_4' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'File reference',
			'fr_FR' => 'Référence dossier',
		),
	),

	'commitment/p-pit-studies/property/property_5' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Training name',
			'fr_FR' => 'Référence de la formation',
		),
	),

	'commitment/p-pit-studies/property/property_6' => array(
		'definition' => 'inline',
		'type' => 'date',
		'labels' => array(
			'default' => 'Training start date',
			'fr_FR' => 'Date de début de la formation',
		),
	),

	'commitment/p-pit-studies/property/property_7' => array(
		'definition' => 'inline',
		'type' => 'date',
		'labels' => array(
			'default' => 'Training end date',
			'fr_FR' => 'Date de fin de la formation',
		),
	),

	'commitment/p-pit-studies/property/property_8' => array(
/*		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Enterprise name',
			'fr_FR' => 'Nom de l’entreprise',
		),*/
		'definition' => 'inline',
		'type' => 'select',
		'source' => array(
			'entity' => 'core_account',
			'type' => 'business',
			'status' => 'new,active,retention',
			'property' => 'name',
		),
		'labels' => array(
			'en_US' => 'Company',
			'fr_FR' => 'Entreprise',
		),
	),

	'commitment/p-pit-studies/property/property_9' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Expected number of training hours',
			'fr_FR' => 'Nombre d’heures de formation prévues',
		),
	),

	'commitment/p-pit-studies/property/property_10' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'initial' => array('default' => 'Initial'),
			'searching' => array('default' => 'En recherche'),
			'undefined' => array('en_US' => '?', 'fr_FR' => '?'),
			'company' => array('default' => 'Professionnalisation'),
			'apprenticeship' => array('default' => 'Apprentissage'),
			'training_course' => array('default' => 'Stage'),
			'student' => array('default' => 'Job étudiant'),
			'service_civique' => array('default' => 'Service civique'),
			'reclassement' => array('default' => 'Reclassement'),
			'pole_emploi' => array('default' => 'Pôle emploi'),
			'cpf' => array('default' => 'CPF'),
		),
		'labels' => array(
			'default' => 'Student follow-up',
			'fr_FR' => 'Suivi Elève',
		),
	),
	
	'commitment/p-pit-studies/property/property_11' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'full_time' => array('default' => 'Full time', 'fr_FR' => 'Full time'),
			'part_time' => array('default' => 'Part time', 'fr_FR' => 'Part time'),
			'online' => array('default' => 'Online', 'fr_FR' => 'En ligne'),
		),
		'labels' => array(
			'default' => 'Rythm',
			'fr_FR' => 'Rythme',
		),
	),

	'commitment/p-pit-studies/property/property_12' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'financing_personal' => array('default' => 'Personal financing', 'fr_FR' => 'Financement personnel'),
			'financing_initial_in_progress_e' => array('default' => 'Financement initial en cours E'),
			'financing_initial_in_progress' => array('default' => 'Financement initial en cours R'),
			'financing_initial_completed' => array('default' => 'Financement initial en cours RR'),
			'financing_initial_built' => array('default' => 'Financement initial monté'),
			'financing_initial_signed' => array('default' => 'Financement initial signé'),
			'financing_company_in_progress' => array('default' => 'Company financing in progress', 'fr_FR' => 'Financement entreprise en cours'),
			'financing_company_built' => array('default' => 'Company financing built', 'fr_FR' => 'Financement entreprise monté'),
			'financing_company_validated' => array('default' => 'Company financing validated', 'fr_FR' => 'Financement entreprise validé'),
			'financing_company_signed' => array('default' => 'Company financing signed', 'fr_FR' => 'Financement entreprise signé'),
		),
		'labels' => array(
			'default' => 'Financing',
			'fr_FR' => 'Financement',
		),
	),

	'commitment/p-pit-studies/property/property_13' => ['definition' => 'student/property/class'],

	'commitment/p-pit-studies/property/property_14' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Learning hourly rate',
			'fr_FR' => 'Taux horaire de la formation',
		),
	),

	'commitment/p-pit-studies/property/property_15' => array('definition' => 'inline',
		'type' => 'date',
		'labels' => array(
			'default' => 'Last financing status date',
			'fr_FR' => 'Date statut financement',
		),
	),

	'commitment/p-pit-studies/property/property_16' => array('definition' => 'inline',
		'type' => 'date',
		'labels' => array(
			'default' => 'Start of contract date',
			'fr_FR' => 'Date début contrat',
		),
	),

	'commitment/p-pit-studies/property/property_17' => array('definition' => 'inline',
		'type' => 'date',
		'labels' => array(
			'default' => 'End of contract date',
			'fr_FR' => 'Date fin contrat',
		),
	),

	'commitment/p-pit-studies/property/property_18' => array('definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Unused',
		),
	),

	'commitment/p-pit-studies/property/property_19' => array('definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Unused',
		),
	),

	'commitment/p-pit-studies/property/property_20' => array('definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Unused',
		),
	),

	'commitment/p-pit-studies/property/property_21' => array('definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Unused',
		),
	),

	'commitment/p-pit-studies/property/property_22' => array('definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Unused',
		),
	),

	'commitment/p-pit-studies/property/property_23' => array('definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Unused',
		),
	),

	'commitment/p-pit-studies/property/property_24' => [
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => [
			'LM' => ['default' => '(LM) Lun-Mar'],
			'JV' => ['default' => '(JV) Jeu-Ven'],
			'VS' => ['default' => '(VS) Ven-Sam'],
		],
		'labels' => ['default' => 'Rythme scolaire'],
	],

	'commitment/p-pit-studies/property/property_25' => [
		'definition' => 'inline',
		'type' => 'input',
		'labels' => ['default' => 'OPCO - Téléphone'],
	],

	'commitment/p-pit-studies/property/property_26' => [
		'definition' => 'inline',
		'type' => 'input',
		'labels' => ['default' => 'OPCO - E-mail'],
	],

	'commitment/p-pit-studies/property/property_27' => [
		'definition' => 'inline',
		'type' => 'email',
		'labels' => ['default' => 'Tuteur - Email école'],
	],

	'commitment/p-pit-studies/property/property_28' => [
		'definition' => 'inline',
		'type' => 'input',
		'labels' => ['default' => 'Libre'],
	],

	'commitment/p-pit-studies/property/property_29' => array(
		'definition' => 'inline',
		'type' => 'multiselect',
		'source' => array(
			'entity' => 'core_account',
			'type' => 'group',
			'status' => 'active',
			'property' => 'name',
		),
		'labels' => array(
			'en_US' => 'Subscription groups',
			'fr_FR' => 'Groupes de l’inscription',
		),
	),

	'commitment/p-pit-studies/property/property_30' => [
		'definition' => 'inline',
		'type' => 'input',
		'labels' => ['default' => 'Nom de l\'entreprise App/Pro'],
	],

	'commitment/p-pit-studies/property/tinyint_1' => [
		'definition' => 'inline',
		'type' => 'select',
		'labels' => ['default' => 'School status Q1', 'fr_FR' => 'Statut pédagogique S1'],
		'modalities' => [
			'1' => ['default' => 'Q1 - Pedagogic surveillance', 'fr_FR' => 'S1 - Surveillance pédagogique'],
			'2' => ['default' => 'Q1 - Pedagogic exclusion', 'fr_FR' => 'S1 - Exclusion pédagogique'],
		]
	],

	'commitment/p-pit-studies/property/tinyint_2' => [
		'definition' => 'inline',
		'type' => 'select',
		'labels' => ['default' => 'School status Q2', 'fr_FR' => 'Statut pédagogique S2'],
		'modalities' => [
			'1' => ['default' => 'Q2 - Pedagogic surveillance', 'fr_FR' => 'S2 - Surveillance pédagogique'],
			'2' => ['default' => 'Q2 - Pedagogic exclusion', 'fr_FR' => 'S2 - Exclusion pédagogique'],
		]
	],

	'commitment/p-pit-studies/property/account_opening_date' => ['definition' => 'core_account/generic/property/opening_date'],
	'commitment/p-pit-studies/property/account_callback_date' => ['definition' => 'core_account/generic/property/callback_date'],
	'commitment/p-pit-studies/property/account_date_1' => ['definition' => 'core_account/generic/property/date_1'],
	'commitment/p-pit-studies/property/account_date_2' => ['definition' => 'core_account/generic/property/date_2'],
	'commitment/p-pit-studies/property/account_date_3' => ['definition' => 'core_account/generic/property/date_3'],
	'commitment/p-pit-studies/property/account_date_4' => ['definition' => 'core_account/generic/property/date_4'],
	'commitment/p-pit-studies/property/account_date_5' => ['definition' => 'core_account/generic/property/date_5'],
	'commitment/p-pit-studies/property/account_origine' => ['definition' => 'core_account/p-pit-studies/property/origine'],
	'commitment/p-pit-studies/property/account_property_1' => ['definition' => 'student/property/discipline'],
	'commitment/p-pit-studies/property/account_property_2' => ['definition' => 'core_account/p-pit-studies/property/property_2'],
	'commitment/p-pit-studies/property/account_property_3' => ['definition' => 'core_account/p-pit-studies/property/property_3'],
	'commitment/p-pit-studies/property/account_property_4' => ['definition' => 'core_account/p-pit-studies/property/property_4'],
	'commitment/p-pit-studies/property/account_property_5' => ['definition' => 'core_account/p-pit-studies/property/property_5'],
	'commitment/p-pit-studies/property/account_property_6' => ['definition' => 'core_account/p-pit-studies/property/property_6'],
	'commitment/p-pit-studies/property/account_property_7' => ['definition' => 'student/property/class'],
	'commitment/p-pit-studies/property/account_property_8' => ['definition' => 'core_account/p-pit-studies/property/property_8'],
	'commitment/p-pit-studies/property/account_property_9' => ['definition' => 'core_account/p-pit-studies/property/property_9'],
	'commitment/p-pit-studies/property/account_property_10' => ['definition' => 'student/property/level'],
	'commitment/p-pit-studies/property/account_property_11' => ['definition' => 'core_account/p-pit-studies/property/property_11'],
	'commitment/p-pit-studies/property/account_property_12' => ['definition' => 'core_account/p-pit-studies/property/property_12'],
	'commitment/p-pit-studies/property/account_property_13' => ['definition' => 'student/property/contact_meeting_context'],
	'commitment/p-pit-studies/property/account_property_14' => ['definition' => 'core_account/p-pit-studies/property/property_14'],
	'commitment/p-pit-studies/property/account_property_15' => ['definition' => 'core_account/p-pit-studies/property/property_15'],
	'commitment/p-pit-studies/property/account_property_16' => ['definition' => 'student/property/school_year'],
	'commitment/p-pit-studies/property/account_property_17' => ['definition' => 'core_account/p-pit-studies/property/property_17'],
	'commitment/p-pit-studies/property/account_property_18' => ['definition' => 'core_account/p-pit-studies/property/property_18'],
	'commitment/p-pit-studies/property/account_property_19' => ['definition' => 'core_account/p-pit-studies/property/property_19'],
	'commitment/p-pit-studies/property/account_property_20' => ['definition' => 'core_account/p-pit-studies/property/property_20'],
	'commitment/p-pit-studies/property/account_int_1' => ['definition' => 'core_account/p-pit-studies/property/int_1'],
	'commitment/p-pit-studies/property/account_int_3' => ['definition' => 'core_account/p-pit-studies/property/int_3'],
	// 'commitment/p-pit-studies/property/contact_4_n_fn' => ['definition' => 'core_account/p-pit-studies/property/contact_4_n_fn'],
	// 'commitment/p-pit-studies/property/contact_4_email' => ['definition' => 'core_account/p-pit-studies/property/contact_4_email'],
	// 'commitment/p-pit-studies/property/contact_4_tel_work' => ['definition' => 'core_account/p-pit-studies/property/contact_4_tel_work'],

		
	'commitment/p-pit-studies/property/comment' => [
		'definition' => 'inline',
		'type' => 'audit',
		'labels' => ['default' => 'Commentaire'],
	],

	'commitment/p-pit-studies/property/notification_time' => [
		'definition' => 'inline',
		'type' => 'date',
		'labels' => ['default' => 'Date de notification'],
	],
	
	'commitment/p-pit-studies' => array(
		'tax' => 'including',
		'currencySymbol' => '€',
		'properties' => array(
			'status', 'state', 'place_id', 'place_caption', 'account_name', 'email', 'email_work', 'tel_cell', 'n_title', 'n_first', 'n_last', 'n_fn', 'birth_date', 'gender', 'photo_link_id', 'invoice_n_fn', 'year', 'adr_street', 'adr_zip', 'adr_city', 'adr_country', 'address', 'photo_link_id',
			'email_2', 'tel_cell_2', 'tel_work_2', 'n_title_2', 'n_first_2', 'n_last_2', 'n_fn_2',
			'email_3', 'tel_cell_3', 'tel_work_3', 'n_title_3', 'n_first_3', 'n_last_3', 'n_fn_3',
			'email_4', 'tel_cell_4', 'tel_work_4', 'n_title_4', 'n_first_4', 'n_last_4', 'n_fn_4',
			'account_owner_id', 'invoice_account_id',
			'caption', 'product_caption','account_id', 'account_status', 'description', 'description_2',
			'quantity', 'unit_price', 'amount', 'product_brand',
			'property_1', 'property_2', 'property_3', 'property_4', 'property_5', 'property_6', 'property_7', 'property_8', 'property_9', 'property_10',
			'property_11', 'property_12', 'property_13', 'property_14', 'property_15', 'property_16', 'property_17', 'property_18', 'property_19',
			'property_20', 'property_21', 'property_22', 'property_23','property_24','property_25','property_26', 'property_27', 'property_28', 'property_29', 'property_30',
			'tinyint_1', 'tinyint_2',
			'including_options_amount', 'order_identifier', 'invoice_identifier', 'credit_identifier', 'invoice_date', 'tax_amount', 'tax_inclusive',
			'account_groups', 'account_opening_date', 'account_callback_date', 'account_date_1', 'account_date_2', 'account_date_3', 'account_date_4', 'account_date_5', 'account_origine', 'account_has_replied',
			'account_property_1', 'account_property_2', 'account_property_3', 'account_property_4', 'account_property_5', 'account_property_6', 'account_property_7', 'account_property_8', 'account_property_9', 'account_property_10',
			'account_property_11', 'account_property_12', 'account_property_13', 'account_property_14', 'account_property_15', 'account_property_16', 'account_property_17', 'account_property_18', 'account_property_19', 'account_property_20', 'account_int_1', 'account_int_3',
			'default_means_of_payment', 'transfer_order_id', 'transfer_order_date', 'bank_identifier', 'account_contact_history', 'update_time',
			'comment', 'shipment_date', 'shipment_message_id', 'notification_time', 'place_caption','account_int_2',
		),
		'order' => 'school_year DESC',
		'todo' => array(
			'sales_manager' => array(
				'status' => array('selector' => 'in', 'value' => array('new', 'approved')),
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
		'properties' => array(
			'place_id' => ['multiple' => true],
			'status' => ['multiple' => true],
			'account_status' => [],
			'including_options_amount' => [],
			'account_name' => [],
			'caption' => ['multiple' => true],
		),
	),

	'commitment/subscriptionSearch/p-pit-studies' => array(
		'title' => array('en_US' => 'Subscriptions', 'fr_FR' => 'Inscriptions'),
		'todoTitle' => array('en_US' => 'active', 'fr_FR' => 'actifs'),
		'properties' => array(
			'place_id' => ['multiple' => true],
			'status' => ['multiple' => true],
			'account_status' => [],
			'n_fn' => [],
			'caption' => ['multiple' => true],
			'property_13' => ['multiple' => true],
			'property_2' => ['multiple' => true],
			'account_int_1' => ['multiple' => true],
		),
	),
	
	'commitment/list/p-pit-studies' => array(
		'properties' => array(
			'place_id' => [],
			'account_name' => [],
			'caption' => [],
			'property_1' => [],
			'email' => [],
			'tel_cell' => [],
			'property_2' => [],
			'property_3' => [],
			'including_options_amount' => [],
			'status' => [],
			'account_status' => [],
			'update_time' => [],
		),
	),
	
	'commitment/account_list/p-pit-studies' => array(
		'links' => [ 
			'certificat_scolarite' => array(
				'definition' => 'inline',
				'route' => 'gemaDocument/downloadDocument',
				'params' => ['template_identifier' => 'certificat_scolarite'],
				'labels' => array('default' => 'Proforma', 'fr_FR' => 'Certificat de scolarité'),
			),
		],
	),
	
	'commitment/subscriptionList/p-pit-studies' => array(
		'properties' => array(
			'place_id' => [],
			'n_fn' => [],
			'caption' => [],
			'email' => [],
			'tel_cell' => [],
			'property_13' => [],
			'property_2' => [],
			'status' => [],
			'account_status' => [],
			'including_options_amount' => [],
			'update_time' => [],
		),
	),
	
	'commitment/detail/p-pit-studies' => array(
		'title' => array('en_US' => 'SUBSCRIPTIONS', 'fr_FR' => 'INSCRIPTIONS'),
		'displayAudit' => false,
		'tabs' => array(
			'commitment' => array(
				'definition' => 'inline',
				'labels' => array('default' => 'Subscription year', 'fr_FR' => 'Année d’inscription'),
			),
			'contact_1' => array(
				'definition' => 'inline',
				'route' => ['account/update', ['account_id']],
				'params' => array('type' => 'p-pit-studies'),
				'labels' => array('en_US' => 'Student', 'fr_FR' => 'Elève'),
			),
			'user' => array(
				'definition' => 'inline',
				'route' => ['account/updateUser', ['account_id']],
				'params' => array('type' => 'p-pit-studies'),
				'labels' => array('en_US' => 'User account', 'fr_FR' => 'Compte utilisateur'),
			),
			'accountDocument' => array(
				'definition' => 'inline',
				'labels' => array('en_US' => 'Documents', 'fr_FR' => 'Documents'),
			),
			'accountUpdate_company' => array(
				'definition' => 'inline',
				'route' => ['account/update', ['id' => 'property_18', 'type' => 'company']],
				'labels' => array('en_US' => 'Business Data', 'fr_FR' => 'Données de l\'entreprise'),
			),
		),
	),

	'commitment/update/p-pit-studies' => array(
		'status' => array('mandatory' => true, 'accountant' => true),
		'year' => array('mandatory' => true, 'accountant' => true),
		'invoice_date' => array('mandatory' => true, 'accountant' => true),
		'caption' => array('mandatory' => true, 'default' => '2020-2021'),
		'account_id' => array('mandatory' => true, 'accountant' => true),
		'invoice_account_id' => array('mandatory' => false, 'accountant' => true),
		'description' => array('mandatory' => false),
		'property_1' => array('mandatory' => false, 'account-initial-value' => 'property_10'),
		'property_13' => array('mandatory' => false, 'account-initial-value' => 'property_7'),
		'property_2' => array('mandatory' => false),
		'property_3' => array('mandatory' => false, 'account-initial-value' => 'property_6'),
		'property_4' => array('mandatory' => false),
		'order_identifier' => array('mandatory' => false),
		'property_5' => array('mandatory' => false),
		'property_6' => array('mandatory' => false),
		'property_7' => array('mandatory' => false),
		'property_8' => array('mandatory' => false),
		'property_9' => array('mandatory' => false),
		'property_14' => array('mandatory' => false),
		'property_10' => array('mandatory' => false),
		'property_11' => array('mandatory' => false),
		'property_12' => array('mandatory' => false),
		'amount' => array('mandatory' => false),
	),

	'commitment/subscriptionDetail/p-pit-studies' => [],

	'commitment/subscriptionUpdate/p-pit-studies' => array(
		'status' => array('mandatory' => true),
		'year' => array('mandatory' => true, 'accountant' => true),
		'invoice_date' => array('mandatory' => true, 'accountant' => true),
		'caption' => array('mandatory' => true, 'default' => '2020-2021'),
//		'account_id' => array('mandatory' => true, 'accountant' => true),
		'description' => array('mandatory' => false),
		'property_13' => array('mandatory' => false, 'account-initial-value' => 'property_7'),
		'property_2' => array('mandatory' => false),
		'property_3' => array('mandatory' => false, 'account-initial-value' => 'property_6'),
		'property_4' => array('mandatory' => false),
		'order_identifier' => array('mandatory' => false),
		'property_5' => array('mandatory' => false),
		'property_6' => array('mandatory' => false),
		'property_7' => array('mandatory' => false),
		'property_8' => array('mandatory' => false),
		'property_9' => array('mandatory' => false),
		'property_14' => array('mandatory' => false),
		'property_10' => array('mandatory' => false),
		'property_11' => array('mandatory' => false),
		'property_12' => array('mandatory' => false),
		'amount' => array('mandatory' => false),
	),
	
	'commitment/model_rules/p-pit-studies' => [
	],
	
	'commitment/group/p-pit-studies' => array(
		'status' => [],
		'caption' => [],
		'description' => [],
	),
	
	'commitment/duplicate/p-pit-studies' => array(
		'init' => [
			'year' => null, // if null duplicate the value from the original
			'invoice_date' => null,
			'description' => null,
			'description_2' => null,
			'property_4' => null,
			'property_5' => null,
			'property_6' => null,
			'property_7' => null,
			'property_8' => null,
			'property_9' => null,
			'property_10' => null,
			'property_11' => null,
			'property_12' => null,
			//'property_12' => null,
			'property_14' => null,
			'property_15' => null,
			'property_16' => null,
			'property_17' => null,
			'property_18' => null,
			'property_19' => null,
			//'property_20' => null,
			'property_21' => null,
			'property_22' => null,
			'property_23' => null,
			'property_24' => null,
			'property_25' => null,
			'property_26' => null,
			'property_27' => null,
			'property_28' => null,
			'order_identifier' => null,
		],
		'properties' => [
			'account_status' => ['defaultValue' => 'retention'],
			'status' => ['defaultValue' => ''],
			'caption' => ['defaultValue' => 'student/property/school_year/next'],
			'property_1' => [],
			'property_13' => [],
			'property_2' => [],  
			'property_3' => [],
		],
	),
	
	'commitment/subscriptionGroup/p-pit-studies' => [
		'caption' => ['defaultValue' => '2022-2023'],
		'property_1' => [],
	],

	'commitment/export/p-pit-studies' => array(
		'year' => 'A',
		'invoice_identifier' => 'B',
		'invoice_date' => 'C',
		'account_name' => 'D',
		'contact_email' => 'X',
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
		'property_4' => 'S',
		'property_5' => 'T',
		'property_6' => 'U',
		'property_7' => 'V',
		'property_8' => 'W',
		'property_9' => 'X',
		'property_10' => 'X',
		'property_11' => 'Y',
		'property_12' => 'Z',
		'tel_cell' => 'AA',
		'identifier' => 'AB',
	),

	'commitment/subscriptionExport/p-pit-studies' => array(
		'place_id' => 'A',
		'account_property_1' => 'B',
		'account_property_10' => 'C',
		'account_property_14' => 'D',
		'account_property_7' => 'E',
		'account_name' => 'F',
		'invoice_n_fn' => 'G',
		'caption' => 'H',
		'description' => 'I',
		'property_1' => 'J',
		'property_2' => 'K',
		'property_3' => 'L',
		'product_caption' => 'M',
		'unit_price' => 'N',
		'quantity' => 'O',
		'amount' => 'P',
		'including_options_amount' => 'Q',
		'default_means_of_payment' => 'R',
		'property_4' => 'S',
		'property_5' => 'T',
		'property_6' => 'U',
		'property_7' => 'V',
		'property_8' => 'W',
		'property_9' => 'X',
		'property_11' => 'Y',
		'property_12' => 'Z',
		'tel_cell' => 'AA',
		'address' => 'AB',
		
		'n_title_2' => 'AC',
		'n_first_2' => 'AD',
		'n_last_2' => 'AE',
		'tel_work_2' => 'AF',
		'tel_cell_2' => 'AG',
		'email_2' => 'AH',
	
		'n_title_3' => 'AI',
		'n_first_3' => 'AJ',
		'n_last_3' => 'AK',
		'tel_work_3' => 'AL',
		'tel_cell_3' => 'AM',
		'email_3' => 'AN',

		'including_options_amount' => 'AB',
		'account_id' => 'AO',
	),
	
	'commitment/invoice/p-pit-studies' => array(
			'header' => array(),
			'description' => array(
					array(
							'left' => array('en_US' => 'Caption', 'fr_FR' => 'Libellé'),
							'right' => array('en_US' => '%s school year %s', 'fr_FR' => 'Scolarité %s %s'),
							'params' => array('product_brand', 'caption'),
					),
/*					array(
							'left' => array('en_US' => 'Invoice date', 'fr_FR' => 'Date de facture'),
							'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
							'params' => array('date'),
					),*/
					array(
							'left' => array('en_US' => 'Student', 'fr_FR' => 'Elève'),
							'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
							'params' => array('account_name'),
					),
					array(
							'left' => array('en_US' => 'Place', 'fr_FR' => 'Centre'),
							'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
							'params' => array('place_id'),
					),
					array(
						'left' => array('en_US' => 'Description', 'fr_FR' => 'Description'),
						'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
						'params' => array('description'),
					),
			),
			'terms' => true,
			'update' => ['status' => 'invoiced'],
	),

	'commitment/invoiceControl/service' => [
		'header' => ['labels' => ['default' => 'Entête'], 'key' => 'header', 'type' => 'text'],
		'customer_invoice_name' => ['labels' => ['default' => 'Nom facturation'], 'key' => 'customer_invoice_name', 'type' => 'text'],
		'customer_n_fn' => ['labels' => ['default' => 'Contact facturation'], 'key' => 'customer_n_fn', 'type' => 'text'],
		'customer_adr_street' => ['labels' => ['default' => 'Adresse facturation'], 'key' => 'customer_adr_street', 'type' => 'text'],
		'customer_adr_zip' => ['labels' => ['default' => 'Code postal'], 'key' => 'customer_adr_zip', 'type' => 'text'],
		'customer_adr_city' => ['labels' => ['default' => 'Adresse facturation'], 'key' => 'customer_adr_city', 'type' => 'text'],
		'customer_adr_country' => ['labels' => ['default' => 'Pays facturation'], 'key' => 'customer_adr_country', 'type' => 'text'],
		'identifier' => ['labels' => ['default' => 'N° facture'], 'key' => 'identifier', 'type' => 'text'],
		'date' => ['labels' => ['default' => 'Date facture'], 'key' => 'date', 'type' => 'date'],
		'libelle' => ['labels' => ['default' => 'Libellé'], 'key' => 'description/0/value', 'type' => 'text'],
		'eleve' => ['labels' => ['default' => 'Élève'], 'key' => 'description/1/value', 'type' => 'text'],
		'centre' => ['labels' => ['default' => 'Centre'], 'key' => 'description/2/value', 'type' => 'text'],
		'lines' => ['labels' => ['default' => 'Détail'], 'key' => 'lines', 'type' => 'list', 'format' => '%s: %s €', 'params' => ['caption' => ['type' => 'text'], 'amount' => ['type' => 'amount']]],
		'including_tax' => ['labels' => ['default' => 'Montant total'], 'key' => 'excluding_tax', 'type' => 'amount'],
		'terms' => ['labels' => ['default' => 'Échéancier'], 'key' => 'terms', 'type' => 'list', 'format' => '%s: %s €, %s le %s', 'params' => ['caption' => ['type' => 'text'], 'unit_price' => ['type' => 'amount'], 'status' => ['definition' => 'commitmentTerm/service/property/status'], 'due_date' => ['type' => 'date']]],
		'settled_amount' => ['labels' => ['default' => 'Montant réglé'], 'key' => 'settled_amount', 'type' => 'amount'],
		'collected_amount' => ['labels' => ['default' => 'Montant encaissé'], 'key' => 'collected_amount', 'type' => 'amount'],
		'still_due' => ['labels' => ['default' => 'Reste-à-payer'], 'key' => 'still_due', 'type' => 'amount'],
		'tax_mention' => ['labels' => ['default' => 'Mention TVA'], 'key' => 'tax_mention', 'type' => 'text'],
	],
	
	// Legal documents
/*	
	'commitments/message/p-pit-studies/attestation_fin_formation' => [
		'name' => ['default' => 'Attestation de fin de formation'],
		'header' => [
			'class' => 'header',
			'paragraphs' => [
				['type' => 'p', 'class' => 'text-right', 'label' => ['default' => '%s'], 'params' => ['addressee_invoice_name']],
				['type' => 'p', 'class' => 'text-right', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_street']],
				['type' => 'p', 'class' => 'text-right', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_extended']],
				['type' => 'p', 'class' => 'text-right', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_post_office_box']],
				['type' => 'p', 'class' => 'text-right', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_zip', 'addressee_adr_city']],
				['type' => 'p', 'class' => 'text-right', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_state']],
				['type' => 'p', 'class' => 'text-right', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_state', 'addressee_adr_country']],
				['type' => 'br'], ['type' => 'br'], ['type' => 'br'],
				['type' => 'h1', 'label' => ['default' => 'ATTESTATION DE FIN DE FORMATION']],
				['type' => 'h3', 'label' => ['default' => 'Art. L.6353-1 du Code du travail']],
			],
		],
		'body' => [
			'class' => 'body',
			'paragraphs' => [
				[
					'type' => 'p',
					'class' => 'text-justify',
					'label' => ['default' => '<p><strong>Intitulé de la formation : </strong>%s</p>'],
					'params' => ['property_5'],
				],
				[
					'type' => 'p',
					'class' => 'text-justify',
					'label' => ['default' => '<strong>Objectifs : Maîtriser les connaissances techniques et les compétences professionnelles associées aux épreuves du %s</strong>'],
					'params' => ['account_property_10'],
				],
				[
					'type' => 'p',
					'class' => 'text-justify',
					'label' => ['default' => '<strong>Lieu de formation : </strong>(Lieu à préciser)'],
				],
				[
					'type' => 'p',
					'class' => 'text-justify',
					'label' => ['default' => '<strong>Date et durée : </strong>du <strong>%s</strong> au <strong>%s</strong> pour une durée de <strong>%s heures</strong>'],
					'params' => ['property_6', 'property_7', 'property_9'],
				],
				[
					'type' => 'p',
					'class' => 'text-justify',
					'label' => ['default' => '<strong>Évaluation des acquis de la formation : </strong>'],
				],
				[
					'type' => 'p',
					'class' => 'text-justify',
					'label' => ['default' => '&nbsp;&nbsp;&nbsp;&nbsp;<strong>Examen du brevet de technicien supérieur : ADMIS(E) ou REFUSÉ(E)</strong>'],
				],
				[
					'type' => 'p',
					'class' => 'text-justify',
					'label' => ['default' => 'Cette attestation peut vous permettre de renseigner votre passeport orientation-formation (art. L.6315-2 du Code du travail)'],
				],
			],
		],
		'footer' => [
			'class' => 'footer',
			'paragraphs' => [
				[
					'type' => 'p',
					'label' => ['default' => 'Délivrée par (à préciser),'],
					'params' => [],
				],
				[
					'type' => 'p',
					'label' => ['default' => 'à %s %s %s'],
					'params' => ['contact_n_title', 'contact_n_first', 'contact_n_last'],
				],
				[
					'type' => 'p',
					'label' => ['default' => '%s %s'],
					'params' => ['adr_street', 'adr_extended'],
				],
				[
					'type' => 'p',
					'label' => ['default' => '%s %s'],
					'params' => ['adr_zip', 'adr_city'],
				],
				[
					'type' => 'p',
					'label' => ['default' => '%s'],
					'params' => ['adr_country'],
				],
				[
					'type' => 'p',
					'label' => ['default' => ''],
					'params' => [],
				],
				[
					'type' => 'p',
					'label' => ['default' => 'à LE PERREUX SUR MARNE, le %s'],
					'params' => ['current_date'],
				],
				[
					'type' => 'p',
					'class' => 'text-center',
					'label' => ['default' => 'LA DIRECTION STUDENCY'],
					'params' => [],
				],
			],
		],
	],

	'commitments/message/p-pit-studies' => [
		'commitments/message/p-pit-studies/attestation_fin_formation',
	],*/
	
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
				'retention' => array('en_US' => 'Retention', 'fr_FR' => 'Ré-inscrit'),
				'suspended' => array('en_US' => 'Suspended', 'fr_FR' => 'Suspendu'),
				'canceled' => array('en_US' => 'Canceled', 'fr_FR' => 'Annulé'),
				'gone' => array('en_US' => 'Gone', 'fr_FR' => 'Parti'),
		),
		'labels' => array(
				'en_US' => 'Status',
				'fr_FR' => 'Statut',
		),
		'perspectives' => array(
				'contact' => array('new', 'interested', 'candidate', 'answer', 'conversion', 'committed', 'visa', 'active', 'retention', 'suspended', 'gone', 'canceled'),
				'account' => array('committed', 'visa', 'active', 'retention', 'suspended', 'canceled'),
		),
		'mandatory' => true,
	),
	
	'core_account/p-pit-studies/property/place_id' => array('definition' => 'core_account/generic/property/place_id'),
	'core_account/p-pit-studies/property/identifier' => array('definition' => 'core_account/generic/property/identifier'),
	'core_account/p-pit-studies/property/name' => array(
		'definition' => 'core_account/generic/property/name'
	),
	
	'core_account/p-pit-studies/property/email_work' => array(
		'definition' => 'inline',
		'type' => 'email',
		'labels' => array(
			'en_US' => 'School e-mail',
			'fr_FR' => 'Email école',
		),
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
		'type' => 'phone',
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
	
	'core_account/generic/property/date_4' => array(
		'definition' => 'inline',
		'type' => 'date',
		'dependency' => ['property' => 'status', 'values' => ['active', 'retention']],
		'labels' => array(
			'en_US' => 'Registration date',
			'fr_FR' => 'Date Inscription',
		),
	),

	'core_account/generic/property/date_5' => array(
		'definition' => 'inline',
		'type' => 'date',
		'dependency' => ['property' => 'status', 'values' => ['canceled', 'gone', 'ne_plus_contacter']],
		'labels' => array(
			'en_US' => 'Cancellation date',
			'fr_FR' => 'Date Annulation',
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
			'fr_FR' => 'Date de RDV d’inscription',
		),
	),
	
	'core_account/p-pit-studies/property/next_meeting_confirmed' => array(
		'definition' => 'inline',
		'type' => 'datetime',
		'labels' => array(
			'en_US' => 'Subscription meeting confirmed',
			'fr_FR' => 'RDV d’inscription confirmé',
		),
	),
	
	'core_account/p-pit-studies/property/priority' => array('definition' => 'core_account/generic/property/priority'),
	
	'core_account/p-pit-studies/property/origine' => [
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'contact_request' => array('en_US' => 'Contact request', 'fr_FR' => 'Demande de contact'),
			'subscription' => array('en_US' => 'Online subscription', 'fr_FR' => 'Souscription en ligne'),
			'cooptation' => array('en_US' => 'Cooptation', 'fr_FR' => 'Cooptation'),
			'file' => array('en_US' => 'File', 'fr_FR' => 'Fichier'),
			'e_mailing' => array('en_US' => 'e-mailing', 'fr_FR' => 'e-mailing'),
			'facebook' => array('default' => 'Facebook'),
			'agency' => array('default' => 'Agence'),
		),
		'labels' => array(
			'en_US' => 'Origine',
			'fr_FR' => 'Origine',
		),
	],
	
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
			'fr_FR' => 'DONNEES D’INSCRIPTION',
		),
	),
	
	'core_account/p-pit-studies/property/property_1' => array('definition' => 'student/property/discipline'),
	
	'core_account/p-pit-studies/property/property_2' => array(
		'definition' => 'inline',
		'type' => 'time',
		'labels' => array(
			'en_US' => 'Admission meeting time',
			'fr_FR' => 'Heure RDV d’admission',
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
			'Externe' => array('default' => 'Externe'),
			'Demi-pensionnaire' => array('default' => 'Demi-pensionnaire'),
			'Interne' => array('default' => 'Internat'),
			'Weekend' => array('default' => 'Internat + WE'),
			'Dimanche' => array('default' => 'Internat + dimanche'),
			'annual' => array('default' => 'Internat annuel'),
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
			'fr_FR' => 'Date RDV d’admission',
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
			'fr_FR' => 'Complément niveau sportif',
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
			'cm2' => array('fr_FR' => 'CM2'),
			'6e' => array('fr_FR' => '6e'),
			'5e' => array('fr_FR' => '5e'),
			'4e' => array('fr_FR' => '4e'),
			'3e' => array('fr_FR' => '3e'),
			'2nde' => array('fr_FR' => '2nde'),
			'2nde pro' => array('fr_FR' => '2nde pro'),
			'1ère' => array('fr_FR' => '1ère'),
			'1ère pro' => array('fr_FR' => '1ère pro'),
			'Term.' => array('fr_FR' => 'Term.'),
			'Term.' => array('fr_FR' => 'Term. pro'),
			'Niveau BAC' => array('fr_FR' => 'Niveau BAC'),
			'Niveau BAC+1' => array('fr_FR' => 'Niveau BAC+1'),
		),
		'labels' => array(
			'en_US' => 'School level at registration',
			'fr_FR' => 'Niveau scolaire à l’inscription',
		),
	),

	'core_account/p-pit-studies/property/property_15' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'full_time' => array('default' => 'Full time', 'fr_FR' => 'Full time'),
			'part_time' => array('default' => 'Part time', 'fr_FR' => 'Part time'),
			'online' => array('default' => 'Online', 'fr_FR' => 'En ligne'),
			'initial' => array('default' => 'Initial', 'archive' => true),

			'?' => array('default' => '?', 'fr_FR' => '?', 'archive' => true),
			'financing_personal' => array('default' => 'Personal financing', 'fr_FR' => 'Financement personnel', 'archive' => true),
			'financing_company_in_progress' => array('default' => 'Company financing in progress', 'fr_FR' => 'Financement entreprise en cours', 'archive' => true),
			'financing_company_validated' => array('default' => 'Company financing validated', 'fr_FR' => 'Financement entreprise validé', 'archive' => true),
		),
		'labels' => array(
			'en_US' => 'Rythm',
			'fr_FR' => 'Rythme',
		),
	),
	
	'core_account/p-pit-studies/property/property_16' => array('definition' => 'student/property/school_year'),
/*
	'core_account/p-pit-studies/property/property_17' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Real-time global average',
			'fr_FR' => 'Moyenne générale temps-réel',
		),
	),*/
	
	'core_account/p-pit-studies/property/property_17' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => [
			'parcours_sport_etudes' => ['default' => 'Parcours Sport Études'],
			'parcours_sportif_de_haut_niveau' => ['default' => 'Parcours sportif de haut niveau'],
		],
		'labels' => array(
			'en_US' => 'Journey',
			'fr_FR' => 'Parcours',
		),
	),

	'core_account/p-pit-studies/property/property_18' => array(
		'definition' => 'inline',
		'type' => 'multiselect',
		'modalities' => [
			'art' => ['default' => 'Arts'],
			'biology' => ['default' => 'Biologie, écologie'],
			'history-geography' => ['default' => 'Histoire-géographie, géopolitique et sciences politiques'],
			'literature' => ['default' => 'Humanités, littérature et philosophie'],
			'language' => ['default' => 'Langues, littératures et cultures étrangères'],
			'antiquity' => ['default' => 'Littérature, langues et cultures de l’Antiquité'],
			'mathematics' => ['default' => 'Mathématiques'],
			'computer-science' => ['default' => 'Numérique et sciences informatiques'],
			'physics-chemistry' => ['default' => 'Physique-Chimie'],
			'life-science' => ['default' => 'Sciences de la Vie et de la Terre'],
			'engineering-science' => ['default' => 'Sciences de l’ingénieur'],
			'economic-social-science' => ['default' => 'Sciences économiques et sociales'],
		],
		'labels' => array(
			'en_US' => 'Specialties',
			'fr_FR' => 'Spécialités',
		),
	),
	
	'core_account/p-pit-studies/property/property_19' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'financing_personal' => array('default' => 'Personal financing', 'fr_FR' => 'Financement personnel'),
			'financing_company_in_progress' => array('default' => 'Company financing in progress', 'fr_FR' => 'Financement entreprise en cours'),
			'financing_company_built' => array('default' => 'Company financing built', 'fr_FR' => 'Financement entreprise monté'),
			'financing_company_validated' => array('default' => 'Company financing validated', 'fr_FR' => 'Financement entreprise validé'),
			'financing_company_signed' => array('default' => 'Company financing signed', 'fr_FR' => 'Financement entreprise signé'),
		),
		'labels' => array(
			'en_US' => 'Financing',
			'fr_FR' => 'Financement',
		),
	),

	'core_account/p-pit-studies/property/property_20' => array(
		'definition' => 'inline',
		'type' => 'select',
		'source' => array(
			'entity' => 'core_account',
			'type' => 'business',
			'status' => 'new,active',
			'property' => 'name',
		),
		'labels' => array(
			'en_US' => 'Company',
			'fr_FR' => 'Entreprise',
		),
	),
			
	'core_vcard/tiny_1' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Occupation',
			'fr_FR' => 'Profession',
		),
	),

	'core_account/p-pit-studies/property/profile_tiny_1' => ['definition' => 'core_vcard/tiny_1'],


	'core_account/p-pit-studies/property/int_1' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'1' => array('default' =>'Non'),
			'2' => array('default' =>'Oui'),
		),
		'labels' => array(
			'default' => 'RQTH',
		),
	),
	
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
			'title_1', 'title_2', 'title_3', 'status', 'state', 'place_id', 'place_caption', 'owner_id', 'identifier', 'name', 'email_work', 'photo_link_id', 'basket',
			'contact_1_id', 'contact_2_id', 'n_title', 'n_first', 'n_last', 'n_fn', 'email', 'tel_work', 'tel_cell',
			'adr_street', 'adr_zip', 'adr_city', 'adr_country', 'address', 'birth_date', 'gender', 'nationality',
			'contact_2_role', 'n_title_2', 'n_first_2', 'n_last_2', 'n_fn_2', 'email_2', 'tel_work_2', 'tel_cell_2', 'address_2',
			'n_title_3', 'n_first_3', 'n_last_3', 'n_fn_3', 'email_3', 'tel_work_3', 'tel_cell_3', 'address_3',
			'n_title_4', 'n_first_4', 'n_last_4', 'n_fn_4', 'email_4', 'tel_work_4', 'tel_cell_4', 'address_4',
			'n_title_5', 'n_first_5', 'n_last_5', 'n_fn_5', 'email_5', 'tel_work_5', 'tel_cell_5', 'address_5',
			'invoice_account_id', 'groups', 'opening_date', 'closing_date', 'callback_date', 'first_activation_date', 'date_1', 'date_2', 'date_3', 'date_4', 'date_5', 'next_meeting_date', 'next_meeting_confirmed', 'priority', 'origine', 'has_replied', 'contact_history', 'opt_out_time', 'notification_time',
			'default_means_of_payment', 'transfer_order_id', 'transfer_order_date', 'bank_identifier',
			'property_1', 'property_2', 'property_3', 'property_4', 'property_5', 'property_6', 'property_7', 'property_8',
			'property_9', 'property_10', 'property_11', 'property_12', 'property_13', 'property_14', 'property_15', 'property_16', 'property_17', 'property_18', 'property_19', 'property_20',
			'profile_tiny_1',
			'json_property_1', 'json_property_2', 'json_property_3',
			'comment_1', 'comment_2', 'comment_3', 'comment_4', 'update_time', 'int_1', 'int_2', 'int_3',
			'comment_1', 'comment_2', 'comment_3', 'comment_4', 'update_time', 'int_1', 'int_2', 'int_4',
		),
		'order' => 'opening_date',
		'options' => ['internal_identifier' => true, 'unique_key' => true /*['n_fn', 'n_last', 'email', 'tel_cell']*/],
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
					'property_1' => ['multiple' => true],
					'property_11' => [],
					'owner_id' => ['multiple' => true],
					'property_16' => ['multiple' => true],
					'origine' => ['multiple' => true],
					'opening_date' => [],
					'callback_date' => [],
					'property_8' => [],
					'next_meeting_date' => [],
					'groups' => ['multiple' => true],
					'property_7' => ['multiple' => true],
					'property_18' => ['multiple' => true],
					'property_6' => ['multiple' => true],
					'property_15' => [],
					'property_19' => [],
					'property_20' => [],
					'n_fn' => [],
					'email' => [],
					'tel_cell' => [],
					'birth_date' => [],
			),
	),
	
	'core_account/list/p-pit-studies' => array(
			'properties' => array(
					'status' => array(
						'background-color' => array(
							'LightGreen' => ['status' => 'new'],
							'LightSalmon' => ['status' => 'interested'],
							'LightBlue' => ['status' => 'candidate'],
							'LightSalmon' => ['status' => 'answer'],
							'LightGrey' => ['status' => 'gone'],
						),
					),
					'n_fn' => [],
					'tel_cell' => ['rendering' => 'phone'],
					'property_16' => [],
//					'basket' => [],
					'property_1' => ['rendering' => 'image'],
					'property_11' => [],
					'opening_date' => [],
					'callback_date' => [],
//					'first_activation_date' => [],
					'property_8' => [],
//					'property_13' => [],
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
					'commitments' => array(
						'definition' => 'inline',
						'labels' => array('default' => 'Commitments', 'fr_FR' => 'Années d’inscription'),
					),
					'account-document' => array(
							'definition' => 'inline',
							'labels' => array('en_US' => 'Documents', 'fr_FR' => 'Documents'),
					),
					'account-form' => array(
							'definition' => 'inline',
							'labels' => array('en_US' => 'Send a form', 'fr_FR' => 'Envoyer un formulaire'),
					),
					'user' => array(
							'definition' => 'inline',
							'route' => 'account/updateUser',
							'params' => array('type' => 'p-pit-studies'),
							'labels' => array('en_US' => 'User account', 'fr_FR' => 'Compte utilisateur'),
					),
					'notifications' => array(
						'definition' => 'inline',
						'route' => 'account/showNotifications',
						'params' => array('entity' => 'commitment_term'),
						'labels' => array('default' => 'Notifications', 'fr_FR' => 'Notifications'),
					),
			),
	),

	'core_account/commitments/p-pit-studies' => [
		'index' => [
			'route' => 'commitment/indexV2',
			'type' => 'p-pit-studies',
			'entryId' => 'commitment',
			'labels' => ['default' => 'Engagements'],
		],
	],

	'core_account/update/p-pit-studies' => array(
			'place_id' => array('mandatory' => true),
			'status' => array(
				'mandatory' => true,
				'model_rules' => [
					[
						'function' => 'checkInitialCommitment',
						'params' => [
							'type' => 'p-pit-studies',
							'status' => 'confirmed',
							'caption' => 'defaultSchoolYear',
							'property_10' => 'property_4',
							'property_11' => 'property_15', 
							'property_12' => 'property_19',
							'property_1' => 'property_10',
							'property_13' => 'property_7',
						],
					],
				],
			),
			'identifier' => array('mandatory' => false),
			'basket' => array('mandatory' => false),
			'opening_date' => array('mandatory' => false),
			'callback_date' => array('mandatory' => false),
//			'first_activation_date' => array('mandatory' => false),
			'date_4' => array('readonly' => true),
			'date_5' => array('mandatory' => false),
			'origine' => array('mandatory' => false),
			'title_1' => null,
			'n_title' => array('mandatory' => false),
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
			'next_meeting_date' => array('mandatory' => false),
			'next_meeting_confirmed' => array('mandatory' => false),
			'property_3' => array('mandatory' => false),
			'title_2' => null,
			'property_1' => array('mandatory' => false),
			'property_11' => array('mandatory' => false),
			'groups' => array(),
			'property_14' => array('mandatory' => false),
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
			'tiny_1' => array('mandatory' => false),
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
			'property_19' => array('mandatory' => false),
			'property_7' => array('mandatory' => false),
			'contact_history' => array('mandatory' => false),
	),
	'core_account/duplicate/p-pit-studies' => array(
		'properties' => [
			'status' => ['defaultValue' => 'retention'],
			'property_16' => ['defaultValue' => 'student/property/school_year/default'],
			'property_7' => array('mandatory' => false),
			'contact_history' => array('mandatory' => false),
		]
	),

	'core_account/groupAddForms/p-pit-studies' => null,
	
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
					'property_20' => array('mandatory' => false),
					'property_11' => array('mandatory' => false),
				),
			),
			'2nd-column' => array(
				'title' => 'title_2',
				'rows' => array(
					'property_15' => array('mandatory' => false),
					'property_19' => array('mandatory' => false),
					'property_1' => array('mandatory' => false),
					'property_10' => array('mandatory' => false),
					'property_7' => array('mandatory' => false),
					'property_4' => array('mandatory' => false),
					'property_18' => array('mandatory' => false),
					'property_9' => array('mandatory' => false),
					'property_6' => array('mandatory' => false),
					'contact_history' => [],
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
		'properties' => [
			'status' => [],
			'property_16' => [],
			'basket' => [],
			'opening_date' => [],
			'callback_date' => [],
//			'first_activation_date' => [],
			'date_4' => [],
			'date_5' => [],
			'origine' => [],
			'n_first' => [],
			'n_last' => [],
			'property_8' => [],
			'property_1' => [],
			'property_15' => [],
			'property_19' => [],
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

			'property_17' => [],
		],
	),

	'core_account/subscription/events/modalities/p-pit-studies' => [
		'l_etudiant' => ['default' => 'L’étudiant'],
		'sup_alternance' => ['default' => 'Sup’Alternance'],
		'fete_alternance' => ['default' => 'Fête de l’Alternance'],
		'hors_parcours_sup' => ['default' => 'Formations hors Parcours Sup'],
		'forum_alternance' => ['default' => 'Forum de l’Alternance'],
		'sup_alternance_special_rentree' => ['default' => 'Sup’Alternance spécial rentrée'],
	],
	
	'core_account/subscription/next_meeting_date/modalities/p-pit-studies' => [
		'2020-02-03' => ['default' => 'Lundi 3 février 2020'],
		'2020-02-04' => ['default' => 'Mardi 4 février 2020'],
		'2020-02-05' => ['default' => 'Mercredi 5 février 2020'],
		'2020-02-06' => ['default' => 'Jeudi 6 février 2020'],
		'2020-02-07' => ['default' => 'Vendredi 7 février 2020'],
		'2020-02-10' => ['default' => 'Lundi 10 février 2020'],
		'2020-02-11' => ['default' => 'Mardi 11 février 2020'],
		'2020-02-12' => ['default' => 'Mercredi 12 février 2020'],
		'2020-02-13' => ['default' => 'Jeudi 13 février 2020'],
		'2020-02-14' => ['default' => 'Vendredi 14 février 2020'],
	],
	
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

	// core_account (group)

	'core_account/group/property/property_3' => [
		'definition' => 'student/property/school_subject',
		'max_length' => 2047,
		'type' => 'multiselect',
	],

	// Terms

	'commitmentTerm/p-pit-studies/property/status' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'expected' => array('fr_FR' => 'Attendu', 'en_US' => 'Expected'),
			'to_invoice' => array('fr_FR' => 'A facturer', 'en_US' => 'To invoice'),
			'settled' => array('fr_FR' => 'Réglé', 'en_US' => 'Settled'),
			'collected' => array('fr_FR' => 'Encaissé', 'en_US' => 'Collected'),
			'invoiced' => array('fr_FR' => 'Facturé', 'en_US' => 'Invoiced'),
			'rejected' => array('fr_FR' => 'Rejeté', 'en_US' => 'Rejected'),
			'registered' => array('fr_FR' => 'Comptabilisé', 'en_US' => 'Registered'),
			'proceedings' => array('fr_FR' => 'Procédure engagée', 'en_US' => 'Proceedings'),
		),
		'labels' => array(
			'en_US' => 'Status',
			'fr_FR' => 'Statut',
		),
	),

	'commitmentTerm/p-pit-studies/property/email_work' => array(
		'definition' => 'inline',
		'type' => 'email',
		'labels' => array(
			'default' => 'School e-mail',
			'fr_FR' => 'Email école',
		),
	),
	
	'commitmentTerm/p-pit-studies/property/quantity' => array(
		'definition' => 'inline',
		'type' => 'number',
		'minValue' => -99999999,
		'maxValue' => 99999999,
		'labels' => array(
			'en_US' => 'Or number of hours',
			'fr_FR' => 'Ou nombre d’heures',
		),
	),
	
	'commitmentTerm/p-pit-studies/property/unit_price' => array(
		'definition' => 'inline',
		'type' => 'number',
		'minValue' => -99999999,
		'maxValue' => 99999999,
		'labels' => array(
			'en_US' => 'Tax ecl. hourly rate',
			'fr_FR' => 'Taux horaire HT',
		),
	),

	'commitmentTerm/p-pit-studies/property/tiny_2' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'File reference',
			'fr_FR' => 'Référence du dossier',
		),
	),

	'commitmentTerm/p-pit-studies/property/tiny_3' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Training name',
			'fr_FR' => 'Nom de la formation',
		),
	),

	'commitmentTerm/p-pit-studies/property/tiny_4' => array(
		'definition' => 'inline',
		'type' => 'date',
		'labels' => array(
			'default' => 'Training start date',
			'fr_FR' => 'Date de début de la formation',
		),
	),

	'commitmentTerm/p-pit-studies/property/tiny_5' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'Enterprise name',
			'fr_FR' => 'Nom de l’entreprise',
		),
	),

	'commitmentTerm/p-pit-studies/property/tiny_9' => array(
		'definition' => 'inline',
		'type' => 'date',
		'labels' => array(
			'default' => 'Date de mise en demeure',
		),
	),

	'commitmentTerm/p-pit-studies/property/commitment_status' => ['definition' => 'commitment/p-pit-studies/property/status'],
	'commitmentTerm/p-pit-studies/property/commitment_caption' => ['definition' => 'student/property/school_year'],
	'commitmentTerm/p-pit-studies/property/commitment_order_identifier' => ['definition' => 'commitment/generic/property/order_identifier'],
	'commitmentTerm/p-pit-studies/property/commitment_property_1' => ['definition' => 'student/property/level'],
	'commitmentTerm/p-pit-studies/property/commitment_property_4' => ['definition' => 'commitment/p-pit-studies/property/property_4'],
	'commitmentTerm/p-pit-studies/property/commitment_property_5' => ['definition' => 'commitment/p-pit-studies/property/property_5'],
	'commitmentTerm/p-pit-studies/property/commitment_property_6' => ['definition' => 'commitment/p-pit-studies/property/property_6'],
	'commitmentTerm/p-pit-studies/property/commitment_property_7' => ['definition' => 'commitment/p-pit-studies/property/property_7'],
	'commitmentTerm/p-pit-studies/property/commitment_property_8' => ['definition' => 'commitment/p-pit-studies/property/property_8'],
	'commitmentTerm/p-pit-studies/property/commitment_property_10' => ['definition' => 'commitment/p-pit-studies/property/property_10'],
	'commitmentTerm/p-pit-studies/property/commitment_property_11' => ['definition' => 'commitment/p-pit-studies/property/property_11'],
	'commitmentTerm/p-pit-studies/property/commitment_property_12' => ['definition' => 'commitment/p-pit-studies/property/property_12'],
	'commitmentTerm/p-pit-studies/property/commitment_property_13' => ['definition' => 'commitment/p-pit-studies/property/property_13'],
	'commitmentTerm/p-pit-studies/property/commitment_property_15' => ['definition' => 'commitment/p-pit-studies/property/property_15'],
	'commitmentTerm/p-pit-studies/property/commitment_property_16' => ['definition' => 'commitment/p-pit-studies/property/property_16'],
	'commitmentTerm/p-pit-studies/property/commitment_property_17' => ['definition' => 'commitment/p-pit-studies/property/property_17'],
	
	'commitmentTerm/search/p-pit-studies' => array(
		'title' => array('en_US' => 'Terms', 'fr_FR' => 'Echéances'),
		'todoTitle' => array('en_US' => 'todo list', 'fr_FR' => 'todo list'),
		'properties' => array(
			'place_id' => ['multiple' => true],
			'commitment_caption' => ['multiple' => true],
			'name' => [],
			'invoice_account_id' => [],
			'status' => ['multiple' => true],
			'account_status' => ['multiple' => true],
			'due_date' => [],
			'collection_date' => [],
			'means_of_payment' => [],
			'amount' => [],
			'invoice_identifier' => [],
			'reference' => [],
			'comment' => [],
		),
	),
	'commitmentTerm/list/p-pit-studies' => array(
		'properties' => array(
			'commitment_caption' => [],
			'name' => [],
			'email' => [],
			'tel_cell' => [],
			'status' => [],
			'invoice_account_id' => [],
			'due_date' => [],
			'collection_date' => [],
			'amount' => [],
		),
	),

	'commitmentTerm/detail/p-pit-studies' => array(
		'title' => array('en_US' => 'Term detail', 'fr_FR' => 'Détail de l\'échéance'),
		'displayAudit' => true,
		'links' => [ 
			'proforma' => array(
				'definition' => 'inline',
				'route' => 'commitmentTerm/downloadInvoice',
				'params' => [],
				'labels' => array('default' => 'Proforma', 'fr_FR' => 'Proforma'),
			),
		],
	),
	
	'commitmentTerm/update/p-pit-studies' => array(
		'invoice_account_id' => [],
		'status' => ['mandatory' => true],
		'commitment_caption' => ['readonly' => true],
		'caption' => ['mandatory' => true],
		'due_date' => ['mandatory' => true],
		'settlement_date' => [],
		'collection_date' => [],
		'amount' => ['mandatory' => false],
		'quantity' => ['mandatory' => false],
		'unit_price' => ['mandatory' => false],
		'means_of_payment' => [],
		'bank_name' => [],
		'invoice_n_last' => [],
		'invoice_identifier' => ['readonly' => true],
		'reference' => [],
		'tiny_1' => [],
/*		'tiny_2' => [],
		'tiny_3' => [],
		'tiny_4' => [],
		'tiny_5' => [],*/
		'commitment_property_4' => ['readonly' => true],
		'commitment_order_identifier' => ['readonly' => true],
		'commitment_property_5' => ['readonly' => true],
		'commitment_property_6' => ['readonly' => true],
		'commitment_property_7' => ['readonly' => true],
		'commitment_property_8' => ['readonly' => true],
		'comment' => [],
		'document' => [],
	),

	'commitmentTerm/group/p-pit-studies' => array(
		'tabs' => [],
	),

	'commitmentTerm/generate/p-pit-studies' => array(
		'commitment_caption' => [],
		'invoice_account_id' => [],
		'scheduling' => [],
		'status' => [],
		'amount_to_divide' => [],
		'means_of_payment' => [],
		'tiny_1' => [],
	),
	
	'commitmentTerm/export/p-pit-studies' => array(
		'name' => 'A',
		'invoice_account_id' => 'B',
		'commitment_caption' => 'C',
		'status' => 'D',
		'caption' => 'E',
		'due_date' => 'F',
		'settlement_date' => 'G',
		'collection_date' => 'H',
		'amount' => 'I',
		'means_of_payment' => 'J',
		'bank_name' => 'K',
		'invoice_n_last' => 'L',
		'reference' => 'M',
		'tiny_1' => 'N',
/*		'tiny_2' => 'O',
		'tiny_3' => 'P',
		'tiny_4' => 'Q',
		'tiny_5' => 'R',*/
		'commitment_property_4' => 'O',
		'commitment_property_5' => 'P',
		'commitment_property_6' => 'Q',
		'commitment_property_7' => 'R',
		'commitment_property_8' => 'S',
		'comment' => 'T',
		'document' => 'U',
		'invoice_identifier' => 'V',
		'email' => 'W',
		'tel_cell' => 'X',
	),

	'commitmentTerm/report/p-pit-studies' => array(
		'columns' => [
			'commitment_caption' => ['position' => 'A'],
			'place_id' => ['position' => 'B'],
			'account_status' => ['position' => 'C'],
			'commitment_property_10' => ['position' => 'D'],
			'commitment_property_11' => ['position' => 'E'],
			'commitment_property_12' => ['position' => 'F'],
			'account_property_6' => ['position' => 'G'],
			'account_property_1' => ['position' => 'H'],
			'invoice_account_id' => ['position' => 'I'],
			'email' => ['position' => 'J'],
			'name' => ['position' => 'K'],
			'account_groups' => ['position' => 'L'],
			'commitment_tax_inclusive' => ['position' => 'M'],
			'collected' => ['position' => 'N', 'type' => 'sum', 'labels' => ['default' => 'Elève - Encaissé'], 'indicator' => 'amount', 'filter' => ['status' => 'collected', 'invoice_account_id' => false]],
			'settled' => ['position' => 'O', 'type' => 'sum', 'labels' => ['default' => 'Elève - Réglé'], 'indicator' => 'amount', 'filter' => ['status' => 'settled', 'invoice_account_id' => false]],
			'invoiced' => ['position' => 'P', 'type' => 'sum', 'labels' => ['default' => 'Elève - Facturé'], 'indicator' => 'amount', 'filter' => ['status' => 'invoiced', 'invoice_account_id' => false]],
			'to_invoice' => ['position' => 'Q', 'type' => 'sum', 'labels' => ['default' => 'Elève - A facturer'], 'indicator' => 'amount', 'filter' => ['status' => 'to_invoice', 'invoice_account_id' => false]],
			'expected' => ['position' => 'R', 'type' => 'sum', 'labels' => ['default' => 'Elève - Attendu'], 'indicator' => 'amount', 'filter' => ['status' => 'expected', 'invoice_account_id' => false]],
			'rejected' => ['position' => 'S', 'type' => 'sum', 'labels' => ['default' => 'Elève - Rejeté'], 'indicator' => 'amount', 'filter' => ['status' => 'rejected', 'invoice_account_id' => false]],
			'proceedings' => ['position' => 'T', 'type' => 'sum', 'labels' => ['default' => 'Elève - Procédure'], 'indicator' => 'amount', 'filter' => ['status' => 'proceedings', 'invoice_account_id' => false]],
			'invoice_account_collected' => ['position' => 'U', 'type' => 'sum', 'labels' => ['default' => 'Financeur - Encaissé'], 'indicator' => 'amount', 'filter' => ['status' => 'collected', 'invoice_account_id' => true]],
			'invoice_account_settled' => ['position' => 'V', 'type' => 'sum', 'labels' => ['default' => 'Financeur - Réglé'], 'indicator' => 'amount', 'filter' => ['status' => 'settled', 'invoice_account_id' => true]],
			'invoice_account_invoiced' => ['position' => 'W', 'type' => 'sum', 'labels' => ['default' => 'Financeur - Facturé'], 'indicator' => 'amount', 'filter' => ['status' => 'invoiced', 'invoice_account_id' => true]],
			'invoice_account_to_invoice' => ['position' => 'X', 'type' => 'sum', 'labels' => ['default' => 'Financeur - A facturer'], 'indicator' => 'amount', 'filter' => ['status' => 'to_invoice', 'invoice_account_id' => true]],
			'invoice_account_expected' => ['position' => 'Y', 'type' => 'sum', 'labels' => ['default' => 'Financeur - Attendu'], 'indicator' => 'amount', 'filter' => ['status' => 'expected', 'invoice_account_id' => true]],
			'invoice_account_rejected' => ['position' => 'Z', 'type' => 'sum', 'labels' => ['default' => 'Financeur - Rejeté'], 'indicator' => 'amount', 'filter' => ['status' => 'rejected', 'invoice_account_id' => true]],
			'invoice_account_proceedings' => ['position' => 'AA', 'type' => 'sum', 'labels' => ['default' => 'Financeur - Procédure'], 'indicator' => 'amount', 'filter' => ['status' => 'proceedings', 'invoice_account_id' => true]],
			'total_collected' => ['position' => 'AB', 'type' => 'sum', 'labels' => ['default' => 'Total - Encaissé'], 'indicator' => 'amount', 'filter' => ['status' => 'collected']],
			'total_settled' => ['position' => 'AC', 'type' => 'sum', 'labels' => ['default' => 'Total - Réglé'], 'indicator' => 'amount', 'filter' => ['status' => 'settled']],
			'total_invoiced' => ['position' => 'AD', 'type' => 'sum', 'labels' => ['default' => 'Total - Facturé'], 'indicator' => 'amount', 'filter' => ['status' => 'invoiced']],
			'total_to_invoice' => ['position' => 'AE', 'type' => 'sum', 'labels' => ['default' => 'Total - A facturer'], 'indicator' => 'amount', 'filter' => ['status' => 'to_invoice']],
			'total_expected' => ['position' => 'AF', 'type' => 'sum', 'labels' => ['default' => 'Total - Attendu'], 'indicator' => 'amount', 'filter' => ['status' => 'expected']],
			'total_rejected' => ['position' => 'AG', 'type' => 'sum', 'labels' => ['default' => 'Total - Rejeté'], 'indicator' => 'amount', 'filter' => ['status' => 'rejected']],
			'total_proceedings' => ['position' => 'AH', 'type' => 'sum', 'labels' => ['default' => 'Total - Procédure'], 'indicator' => 'amount', 'filter' => ['status' => 'proceedings']],
			'term_amount_gap' => ['position' => 'AI', 'type' => 'sum', 'labels' => ['default' => 'Total - Non planifié'], 'indicator' => 'amount'],
		],
		'sums' => [],
	),
	
	'commitmentTerm/invoice/p-pit-studies' => array(
		'header' => array(),
		'description' => array(
			array(
				'left' => array('en_US' => 'Caption', 'fr_FR' => 'Libellé'),
				'right' => array('en_US' => 'school year %s', 'fr_FR' => 'Scolarité %s'),
				'params' => array('caption'),
			),
/*			array(
				'left' => array('en_US' => 'Invoice date', 'fr_FR' => 'Date de facture'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('date'),
			),*/
			array(
				'left' => array('en_US' => 'Student', 'fr_FR' => 'Elève'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('name'),
			),
			array(
				'left' => array('en_US' => 'Place', 'fr_FR' => 'Centre'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('place_id'),
			),
			array(
				'left' => array('en_US' => 'Invoicing period', 'fr_FR' => 'Période de facturation'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('tiny_1'),
			),
			array(
				'left' => array('en_US' => 'File reference', 'fr_FR' => 'Référence dossier'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('commitment_property_4'),
			),
			array(
				'left' => array('en_US' => 'Order identifier', 'fr_FR' => 'Numéro de commande client'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('commitment_order_identifier'),
			),
			array(
				'left' => array('en_US' => 'Training name', 'fr_FR' => 'Nom de la formation'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('commitment_property_5'),
			),
			array(
				'left' => array('en_US' => 'Training start date', 'fr_FR' => 'Date de début de formation'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('commitment_property_6'),
			),
			array(
				'left' => array('en_US' => 'Training end date', 'fr_FR' => 'Date de fin de formation'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('commitment_property_7'),
			),
			array(
				'left' => array('en_US' => 'Mentor name', 'fr_FR' => 'Nom de l’entreprise'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('commitment_property_8'),
			),
			array(
				'left' => array('en_US' => 'Description', 'fr_FR' => 'Description'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('commitment_description'),
			),
		),
		'terms' => true,
	),

	'commitmentTerm/credit/p-pit-studies' => [
		'InstrId' => [
			'labels' => ['default' => '%s/Remb.'],
			'params' => ['name'],
		],
		'EndToEndId' => [
			'labels' => ['default' => '%s / Remboursement frais scolarité'],
			'params' => ['name'],
		],
		'Ustrd' => [
			'labels' => ['default' => 'Remboursement frais'],
			'params' => [],
		],
	],
	
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
			'title' => array('default' => 'Commitments', 'fr_FR' => 'Engagements'),
			'properties' => array(
				'status' => [],
				'caption' => [],
				'property_13' => [],
				'property_2' => [],
				'property_10' => [],
				'property_11' => [],
				'property_12' => [],
			),
			'anchors' => array(
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
/*		
	'commitmentNotification/p-pit-studies' => array(
			'category' => array(
					'news_flash' => array(
							'labels' => array('en_US' => 'News flash', 'fr_FR' => 'Flash infos'),
					),
					'sport' => array(
							'labels' => array('en_US' => 'Sport', 'fr_FR' => 'Sport'),
					),
//					'schooling' => array(
//							'labels' => array('en_US' => 'Schooling', 'fr_FR' => 'Scolarité'),
//					),
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
	),*/

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
					'fr_FR' => 'Cadre RDV d’admission',
			),
	),
		
	'student/property/discipline' => array(
			'type' => 'select',
			'modalities' => array(
					'athletics' => array('en_US' => 'Athletics', 'fr_FR' => 'Athlétisme'),
					'football' => array('en_US' => 'Football', 'fr_FR' => 'Football'),
					'football-women' => array('en_US' => 'Women’s Football', 'fr_FR' => 'Football féminin'),
					'rugby' => array('en_US' => 'Rugby', 'fr_FR' => 'Rugby'),
					'basketball' => array('en_US' => 'Basketball', 'fr_FR' => 'Basketball'),
					'handball' => array('en_US' => 'Handball', 'fr_FR' => 'Handball'),
					'equitation' => array('en_US' => 'Horse-riding', 'fr_FR' => 'Equitation'),
					'golf' => array('en_US' => 'Golf', 'fr_FR' => 'Golf'),
					'tennis' => array('en_US' => 'Tennis', 'fr_FR' => 'Tennis'),
					'table-tennis' => array('en_US' => 'Table tennis', 'fr_FR' => 'Tennis de table'),
					'danse' => array('en_US' => 'Danse', 'fr_FR' => 'Danse'),
					'music' => array('en_US' => 'Music', 'fr_FR' => 'Musique'),
					'musical' => array('en_US' => 'Musical', 'fr_FR' => 'Comédie musicale'),
					'figure-skating' => array('en_US' => 'Figure skating', 'fr_FR' => 'Patinage artistique'),
					'ice_sport' => array('en_US' => 'Ice sports', 'fr_FR' => 'Sports de glace'),
					'multisport' => array('en_US' => 'Multisport', 'fr_FR' => 'Multisport'),
					'car-racing' => array('en_US' => 'Car racing', 'fr_FR' => 'Sport automobile'),
					'cycling' => array('en_US' => 'Cycling', 'fr_FR' => 'Cyclisme'),
					'taekwondo' => array('en_US' => 'Tae kwon do', 'fr_FR' => 'Taekwondo'),
					'karate' => array('en_US' => 'Karate', 'fr_FR' => 'Karaté'),
					'padel' => array('default' => 'Padel'),
			),
			'labels' => array(
					'en_US' => 'Sport',
					'fr_FR' => 'Sport',
			),
	),

	'student/property/level' => array(
			'type' => 'select',
			'modalities' => array(
					'cm2' => array('fr_FR' => 'CM2'),
					'6e' => array('fr_FR' => '6e'),
					'5e' => array('fr_FR' => '5e'),
					'4e' => array('fr_FR' => '4e'),
					'3e' => array('fr_FR' => '3e'),
					'2nde' => array('fr_FR' => '2nde'),
					'1ere' => array('fr_FR' => '1ère'),
					'term' => array('fr_FR' => 'Term.'),
					'stmg' => array('fr_FR' => 'STMG'),
					'bts-muc' => array('fr_FR' => 'BTS MUC'),
			),
			'labels' => array(
					'en_US' => 'School level',
					'fr_FR' => 'Niveau scolaire',
			),
	),

	'student/property/specialty' => array(
		'type' => 'multiselect',
		'modalities' => array(
			'art' => ['default' => 'Arts'],
			'biology' => ['default' => 'Biologie, écologie'],
			'history-geography' => ['default' => 'Histoire-géographie, géopolitique et sciences politiques'],
			'literature' => ['default' => 'Humanités, littérature et philosophie'],
			'language' => ['default' => 'Langues, littératures et cultures étrangères'],
			'antiquity' => ['default' => 'Littérature, langues et cultures de l’Antiquité'],
			'mathematics' => ['default' => 'Mathématiques'],
			'computer-science' => ['default' => 'Numérique et sciences informatiques'],
			'physics-chemistry' => ['default' => 'Physique-Chimie'],
			'life-science' => ['default' => 'Sciences de la Vie et de la Terre'],
			'engineering-science' => ['default' => 'Sciences de l’ingénieur'],
			'economic-social-science' => ['default' => 'Sciences économiques et sociales'],
		),
		'labels' => array(
			'en_US' => 'Specialty',
			'fr_FR' => 'Spécialité',
		),
	),

	'student/property/class' => array(
		'type' => 'select',
		'modalities' => array(
			'8cm1' => array('fr_FR' => 'CM1'),
			'7cm2' => array('fr_FR' => 'CM2'),
			'6e' => array('fr_FR' => 'Sixième'),
			'5e' => array('fr_FR' => 'Cinquième'),
			'4e' => array('fr_FR' => 'Quatrième'),
			'3e' => array('fr_FR' => 'Troisième'),
			'2nde' => array('fr_FR' => 'Seconde générale'),
			'1ere' => array('fr_FR' => 'Première générale'),
			'1ereSTMG' => array('fr_FR' => 'Première STMG'),
			'1ereSTSS' => array('fr_FR' => 'Première STSS'),
			'0term' => array('fr_FR' => 'Terminale générale'),
			'0termSTMG' => array('fr_FR' => 'Terminale STMG'),
			'0termSTSS' => array('fr_FR' => 'Terminale STSS'),
			'2ndeProCommerce' => array('fr_FR' => 'Seconde Pro Commerce'),
			'1ereProCommerce' => array('fr_FR' => 'Première Pro Commerce'),
			'0termProCommerce' => array('fr_FR' => 'Terminale Pro Commerce'),
			'2ndeProService' => array('fr_FR' => 'Seconde Pro Service'),
			'1ereProService' => array('fr_FR' => 'Première Pro Service'),
			'0termProService' => array('fr_FR' => 'Terminale Pro Service'),
			'2ndeProVente' => array('fr_FR' => 'Seconde Pro Vente'),
			'1ereProVente' => array('fr_FR' => 'Première Pro Vente'),
			'0termProVente' => array('fr_FR' => 'Terminale Pro Vente'),
			'bts-com1' => array('fr_FR' => 'BTS COM 1'),
			'bts-com2' => array('fr_FR' => 'BTS COM 2'),
			'bts-mco' => array('fr_FR' => 'BTS MCO', 'archive' => true),
			'bts-mco1' => array('fr_FR' => 'BTS MCO 1'),
			'bts-mco2' => array('fr_FR' => 'BTS MCO 2'),
			'bts-ndrc' => array('fr_FR' => 'BTS NDRC', 'archive' => true),
			'bts-ndrc1' => array('fr_FR' => 'BTS NDRC 1'),
			'bts-ndrc2' => array('fr_FR' => 'BTS NDRC 2'),
			'bts-muc1' => array('fr_FR' => 'BTS MUC 1', 'archive' => true),
			'bts-muc2' => array('fr_FR' => 'BTS MUC 2', 'archive' => true),
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
	
	'student/property/school_year' => array(
			'type' => 'select',
			'modalities' => array(
					'2016-2017' => array('fr_FR' => '2016-2017', 'en_US' => '2016-2017', 'archive' => true),
					'2017-2018' => array('fr_FR' => '2017-2018', 'en_US' => '2017-2018', 'archive' => true),
					'2018-2019' => array('fr_FR' => '2018-2019', 'en_US' => '2018-2019', 'archive' => true),
					'2019-2020' => array('fr_FR' => '2019-2020', 'en_US' => '2019-2020'),
					'2020-2021' => array('fr_FR' => '2020-2021', 'en_US' => '2020-2021'),
					'2021-2022' => array('fr_FR' => '2021-2022', 'en_US' => '2021-2022'),
					'2022-2023' => array('fr_FR' => '2022-2023', 'en_US' => '2022-2023'),
					'2023-2024' => array('fr_FR' => '2023-2024', 'en_US' => '2023-2024'),
			),
			'labels' => array(
					'en_US' => 'School year',
					'fr_FR' => 'Année scolaire',
			),
	),
	'student/property/school_year/default' => '2023-2024',
	'student/property/school_year/next' => '2024-2025',
	'student/property/school_year/start' => '2023-09-01',
	'student/property/school_year/end' => '2024-07-31',
	
	'student/property/school_period' => array(
			'type' => 'select',
			'modalities' => array(
					'Q1' => array('en_US' => 'Quarter 1', 'fr_FR' => 'Premier trimestre'),
					'Q2' => array('en_US' => 'Quarter 2', 'fr_FR' => 'Deuxième trimestre'),
					'Q3' => array('en_US' => 'Quarter 3', 'fr_FR' => 'Troisième trimestre'),
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
					'assessment' => array('default' => 'Contrôle'),
					'homework' => array('default' => 'Devoirs maison'),
					'oral-test' => array('default' => 'Interrogation orale'),
					'written-test' => array('default' => 'Interrogation écrite'),
					'participation' => array('default' => 'Participation'),
					'involvement' => array('default' => 'Investissement'),
					'mock-exam' => array('default' => '1er brevet blanc'),
					'mock-exam_2' => array('default' => '2nd brevet blanc'),
					'mock-exam_3' => array('default' => '3e brevet blanc'),
					'mock-bac' => array('default' => '1er baccalauréat blanc'),
					'mock-bac_2' => array('default' => '2nd baccalauréat blanc'),
					'mock-bac_3' => array('default' => '3e baccalauréat blanc'),
					'mock-bep' => array('default' => 'BEP blanc n°1'),
					'mock-bep_2' => array('default' => 'BEP blanc n°2'),
					'mock-bts' => array('default' => '1er BTS blanc'),
					'mock-bts_2' => array('default' => '2nd BTS blanc'),
					'mock-bts_3' => array('default' => '3e BTS blanc'),
					'mock-oral_1' => array('default' => 'Oral blanc n°1'),
					'mock-oral_2' => array('default' => 'Oral blanc n°2'),
					'mock-oral_3' => array('default' => 'Oral blanc n°3'),
					'cned' => array('default' => 'CNED'),
					'cned_1' => array('default' => 'CNED N°1'),
					'cned_2' => array('default' => 'CNED N°2'),
					'cned_3' => array('default' => 'CNED N°3'),
					'cned_4' => array('default' => 'CNED N°4'),
					'cned_5' => array('default' => 'CNED N°5'),
					'cned_6' => array('default' => 'CNED N°6'),
					'cned_7' => array('default' => 'CNED N°7'),
					'cned_8' => array('default' => 'CNED N°8'),
					'cned_9' => array('default' => 'CNED N°9'),
					'cned_10' => array('default' => 'CNED N°10'),
					'cned_11' => array('default' => 'CNED N°11'),
					'cned_12' => array('default' => 'CNED N°12'),
					'sea_1' => array('default' => 'Devoir SEA 1'),
					'sea_2' => array('default' => 'Devoir SEA 2'),
					'sea_3' => array('default' => 'Devoir SEA 3'),
					'sea_4' => array('default' => 'Devoir SEA 4'),
					'sea_5' => array('default' => 'Devoir SEA 5'),
					'sea_6' => array('default' => 'Devoir SEA 6'),
					'sea_7' => array('default' => 'Devoir SEA 7'),
					'sea_8' => array('default' => 'Devoir SEA 8'),
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
/*			'mock-lv1' => [],
			'mock-lv2' => [],
			'mock-specialty' => [],
			'mock-mngt-science' => [],*/
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
					'humanite_litterature_philosophie' => array( 'default' => 'Humanité/littérature/philosophie'),
					'llce' => array( 'default' => 'LLCE'),
					'mathematics' => array('en_US' => 'Mathematics', 'fr_FR' => 'Mathématiques'),
					'history-geography' => array('en_US' => 'History/geography', 'fr_FR' => 'Histoire/géographie'),
					'history' => array('en_US' => 'History', 'fr_FR' => 'Histoire'),
					'hg_geopolitique_science_politique' => array('default' => 'HG, géopolitique, sciences politiques'),
					'hg-science' => array('default' => 'Histoire/Géographie/Sciences'),
					'science_politique' => array('default' => 'Sciences politiques'),
					'civics' => array('en_US' => 'Civics', 'fr_FR' => 'Instruction civique'),
					'moral_and_civics' => array('default' => 'Enseignement moral et civique'),
					'physics-chemistry' => array('en_US' => 'Physics/chemistry', 'fr_FR' => 'Physique/chimie'),
					'life-science' => array('en_US' => 'Life sciences', 'fr_FR' => 'SVT'),
					'svt_techno' => array('default' => 'SVT techno'),
					'sciences' => array('en_US' => 'Sciences', 'fr_FR' => 'Sciences'),
					'enseignement_scientifique' => array('default' => 'Enseignement scientifique'),
					'numerique_informatique' => array('default' => 'Numérique informatique'),
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
					'russian' => array('en_US' => 'Russian', 'fr_FR' => 'Russe'),
					'litterature_etrangere' => array('default' => 'Littérature étrangère'),
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
					'animation_offre_commerciale' => array('default' => 'Animation de l’offre commerciale'),
					'culture_juridique_economique_manageriale' => array('default' => 'Culture juridique, économique et managériale'),
					'management' => array('en_US' => 'Management', 'fr_FR' => 'Management'),
					'management_equipe_commerciale' => array('default' => 'Management de l’équipe commerciale'),
					'communication' => array('en_US' => 'Communication', 'fr_FR' => 'Communication'),
					'animer' => array('default' => 'Animer'),
					'gerer' => array('default' => 'Gérer'),
					'vendre' => array('default' => 'Vendre'),
					'duc' => array('en_US' => 'Business unit development', 'fr_FR' => 'Développement de l’unité commerciale (DUC)'),
					'developpement_relation_client' => array('default' => 'Développement de la relation client et vente conseil'),
					'gestion_operationnelle' => array('default' => 'Gestion opérationnelle'),
					'pfeg' => array('en_US' => 'PFEG', 'fr_FR' => 'PFEG'),
					'epi' => array('en_US' => 'EPI', 'fr_FR' => 'EPI'),
					'vsp' => array('en_US' => 'VSP', 'fr_FR' => 'VSP'),
					'mde' => array('en_US' => 'MDE', 'fr_FR' => 'MDE'),
					'pse' => array('en_US' => 'PSE', 'fr_FR' => 'PSE (Prévention Santé Environnement'),
					'eps-arts-music' => array('default' => 'EPS / Arts plastiques / Éducation musicale'),
					'applied-arts' => array('en_US' => 'Applied arts', 'fr_FR' => 'Arts appliqués'),
					'plastic-arts' => array('en_US' => 'Plastic arts', 'fr_FR' => 'Arts plastiques'),
					'music' => array('en_US' => 'Music', 'fr_FR' => 'Musique'),
					'dance-history' => array('en_US' => 'Dance history', 'fr_FR' => 'Histoire de la danse'),
					'specialite' => array('en_US' => 'Specialty', 'fr_FR' => 'Spécialité'),
					'spe-stmg' => array('en_US' => 'STMG specialty', 'fr_FR' => 'Spécialité STMG'),
					'spe-acrc' => array('en_US' => 'ACRC specialty', 'fr_FR' => 'Spécialité ACRC'),
					'spe-mguc' => array('en_US' => 'MGUC specialty', 'fr_FR' => 'Spécialité MGUC'),
					'spe-cge' => array('en_US' => 'CGE specialty', 'fr_FR' => 'Spécialité CGE'),
					'spe-pduc' => array('en_US' => 'PDUC specialty', 'fr_FR' => 'Spécialité PDUC'),
					'spe-mo' => array('en_US' => 'MO specialty', 'fr_FR' => 'Spécialité MO'),
					'spe-pse' => array('en_US' => 'PSE specialty', 'fr_FR' => 'Spécialité PSE'),
					'spe-bac-pro' => array('en_US' => 'Bac pro specialty', 'fr_FR' => 'Spécialité Bac Pro'),

					'relation_client_digitalisation' => array('default' => 'Relation client à distance et digitalisation', 'classes' => ['bts_ndrc']),
					'relation_client_reseau' => array('default' => 'Relation client et animation des réseaux', 'classes' => ['bts_ndrc']),
					'relation_client_vente' => array('default' => 'Relation client et négociation vente', 'classes' => ['bts_ndrc']),
					'relation_client_vente_2' => array('default' => 'Relation client et négociation vente (2)', 'classes' => ['bts_ndrc']),
					'guc' => array('default' => 'GUC'),
					'muc' => array('default' => 'MUC'),
				
					'physio_pathologie' => array('en_US' => 'Physio-pathology', 'fr_FR' => 'Physio-pathologie'),
					'medico-social' => array('en_US' => 'Medico-social', 'fr_FR' => 'Sciences médico-sociales'),
					'nutrition' => array('en_US' => 'Biologics and nutrition', 'fr_FR' => 'Biologie et nutrition'),
					'sanitary-social' => array('en_US' => 'Sanitary and social', 'fr_FR' => 'Sanitaire et sociales'),
					'customer-relationship' => array('en_US' => 'Customer and user relationship', 'fr_FR' => 'Relation aux clients et aux usagers'),

					'pro_skills' => array('en_US' => 'Professional skills', 'fr_FR' => 'Techniques pros / Techniques d’entretien'),
				
					'study-period' => array('en_US' => 'Study period', 'fr_FR' => 'Etudes dirigées'),
					'dst' => array('default' => 'DST'),
					'ed_dst' => array('default' => 'ED-DST'),
					'tpe' => array('en_US' => 'TPE', 'fr_FR' => 'TPE'),
				
					'school_life' => array('en_US' => 'School life', 'fr_FR' => 'Vie scolaire'),
					'sport' => array('en_US' => 'Sport', 'fr_FR' => 'Sport'),
			
					'dejeuner' => array('default' => 'Déjeuner'),
					'pause' => array('default' => 'Pause'),
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
					'groups' => 'select',
					'property_1' => 'select',
					'n_fn' => 'contains',
			),
	),

	'student/list' => array(
			'photo_link_id' => 'photo',
			'n_fn' => 'text',
			'groups' => 'select',
			'tel_cell' => 'phone',
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

	'student/report/header' => array(
		array(
			'format' => array('en_US' => '%s', 'fr_FR' => '%s'),
			'params' => array('name'),
		),
	),
			
	'student/report/description' => array(
		array(
			'left' => array('en_US' => 'Student', 'fr_FR' => 'Elève'),
			'right' => array('en_US' => '%s - %s - %s', 'fr_FR' => '%s - %s - %s'),
			'params' => array('n_fn', 'property_1', 'property_6'),
		),
/*		array(
			'left' => array('en_US' => 'Class', 'fr_FR' => 'Classe'),
			'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
			'params' => array('property_7'),
		),*/
		array(
				'left' => array('en_US' => 'Group', 'fr_FR' => 'Groupe'),
				'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
				'params' => array('groups'),
			),
		array(
			'left' => array('en_US' => 'Birth date', 'fr_FR' => 'Date de naissance'),
			'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
			'params' => array('birth_date'),
		),
/*		array(
			'left' => array('en_US' => 'Class size', 'fr_FR' => 'Effectif'),
			'right' => array('en_US' => '%s', 'fr_FR' => '%s'),
			'params' => array('class_size'),
		),*/
	),

	'student/report/detailHeader' => array(
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
			array('en_US' => 'Max.', 'fr_FR' => 'Max.'),
			array('en_US' => 'Avg.', 'fr_FR' => 'Moy.'),
			array('en_US' => 'Min.', 'fr_FR' => 'Min.'),
			'rows' => null,
		),
	),

	'student/report/detailRow' => array(
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

	'student/report/signatureFrame' => array(
		'html' => '
<table class="table note-report">
    <tr>
    	<td style="width: 70%%">%s</td>
    	<td style="width: 30%%">%s</td>
	</tr>
</table>',
	),

	'student/report/withoutMentionFrame' => array(
		'html' => '
<table class="table note-report">
    <tr>
    	<td style="width: 100%%">%s</td>
	</tr>
</table>',
	),

	'student/report/evaluationHeader' => array(
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
//			array('en_US' => 'Observations', 'fr_FR' => 'Observations'),
			array('en_US' => 'Max.', 'fr_FR' => 'Max.'),
			array('en_US' => 'Avg.', 'fr_FR' => 'Moy.'),
			array('en_US' => 'Min.', 'fr_FR' => 'Min.'),
			'rows' => null,
		),
	),

	'student/report/evaluationSubject' => array(
		'html' => '
<tr %s>
	<td colspan="8" style="font-weight: bold">%s</td>
</tr>',
		'params' => array('color', 'subject'),
	),

	'student/report/evaluationRow' => array(
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

	'student/report/absenceHeader' => array(
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
			
	'student/report/absenceRow' => array(
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

	'student/report/pdfDetailStyle' => '
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

	'student/report/evaluationSignatureFrame' => array(
		'html' => '
<table class="table note-report">
    <tr>
    	<td style="width: 100%%">%s</td>
	</tr>
</table>',
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

	// Planning event

	'event/planning/property/matched_accounts' => array(
		'definition' => 'inline',
		'type' => 'select',
		'account_type' => 'p-pit-studies',
		'labels' => array(
			'en_US' => 'Student',
			'fr_FR' => 'Élève',
		),
	),
	
	// Calendar event

	'event/calendar/property/status' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
			'new' => array('en_US' => 'New', 'fr_FR' => 'Nouveau'),
			'completed' => array('en_US' => 'Completed', 'fr_FR' => 'Complété'),
			'scheduled' => array('en_US' => 'Scheduled', 'fr_FR' => 'Planifié'),
			'realized' => array('en_US' => 'Realized', 'fr_FR' => 'Réalisé'),
			'to_invoice' => array('en_US' => 'To Invoice', 'fr_FR' => 'A facturer'),
			'canceled' => array('en_US' => 'Canceled', 'fr_FR' => 'Annulé'),
			'replaced' => array('en_US' => 'replaced', 'fr_FR' => 'Remplacé'),

		),
		'labels' => array(
			'en_US' => 'Status',
			'fr_FR' => 'Statut',
		),
		'perspectives' => array(
			'generic' => array('', 'new', 'scheduled'),
		),
	),
	
	'event/calendar/property/account_status' => array(
		'definition' => 'core_account/teacher/property/status',
		'labels' => array(
			'default' => 'Account status',
			'fr_FR' => 'Statut du compte',
		),
	),
	
	'event/calendar/property/email_work' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'default' => 'School email',
			'fr_FR' => 'Email école',
		),
	),
	
	'event/calendar/property/property_1' => array('definition' => 'student/property/school_year'),
	'event/calendar/property/property_2' => array('definition' => 'student/property/class'),
	'event/calendar/property/property_3' => array('definition' => 'student/property/school_subject'),

	'event/calendar/property/property_4' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Teacher',
			'fr_FR' => 'Intervenant',
		),
	),
	
	'event/calendar/property/property_5' => array(
		'definition' => 'inline',
		'type' => 'select',
		'labels' => array(
			'default' => 'Facturé',
		),
		'modalities' => array(
			'oui' => array('default' => 'Oui'),
			'non' => array('default' => 'Non'),
		),
	),

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
			'status', 'type', 'place_id', 'school', 'place_caption', 'vcard_id', 'n_fn', 'account_status', 'email_work', 'category', 'subcategory', 'identifier', 'caption', 'description',
			'begin_date', 'end_date', 'day_of_week', 'day_of_month', 'exception_1', 'exception_2', 'exception_3', 'exception_4', 'begin_time', 'end_time', 'time_zone', 'location', 'latitude', 'longitude',
			'value', 'comments',
			'property_1', 'property_2', 'property_3', 'property_4', 
			'update_time',
		),
		'options' => ['calendar' => true, 'account_type' => 'teacher'],
	),
	
	'event/index/calendar' => array(
		'title' => array('default' => 'Planning', 'fr_FR' => 'Planning'),
	),
	
	// planning_search_property: Je filtre en standard le planning par établissement, par année scolaire, par groupe, par tout ou partie du nom formaté (NOM, Prénom), par matière, par intervenant

	'event/search/calendar' => array(
		'title' => array('default' => 'Planning', 'fr_FR' => 'Planning'),
		'todoTitle' => array('default' => 'recent', 'fr_FR' => 'récents'),
		'searchTitle' => array('default' => 'search', 'fr_FR' => 'recherche'),
		'properties' => array(
			'place_id' => ['multiple' => true],
			'school' => ['multiple' => true],
			'property_1' => [/*'default' => 'student/property/school_year/default',*/ 'multiple' => true],
			'groups' => [
				'multiple' => true,
				'restrictions' => [
					['property' => 'place_id', 'parent' => 'place_id'],
					['property' => 'property_18', 'parent' => 'school'],
				],
			],
			'subcategory' => ['multiple' => true],
			'property_3' => ['multiple' => true],
			'property_5' => ['multiple' => true],
			'account_id' => ['multiple' => true],
			'status' => ['multiple' => true],
			'account_status' => ['multiple' => true],
			'email_work' => [],
			'begin_date' => [],
			'end_date' => [],
			'day_of_week' => ['multiple' => true],
			'begin_time' => [],
			'end_time' => [],
		),
	),

	'event/list/calendar' => array(
		'place_id' => ['mandatory' => true],
		'property_1' => ['mandatory' => true],
		'groups' => ['multiple' => true],
		'subcategory' => ['multiple' => true],
		'property_3' => [],
		'account_id' => ['multiple' => true],
		'status' => [],
		'account_status' => ['readonly' => true],
		'email_work' => ['readonly' => true],
		'begin_date' => ['mandatory' => true],
		'end_date' => [],
		'day_of_week' => ['multiple' => true],
		'begin_time' => ['mandatory' => true],
		'end_time' => ['mandatory' => true],
		'location' => ['mandatory' => false],
		'value' => ['readonly' => true],
	),

	'event/group/calendar' => [
		'title' => ['default' => 'Selected events', 'fr_FR' => 'Evénements sélectionnés'],
		'checklist' => ['format' => '%s %s %s %s %s', 'params' => ['place_id', 'groups', 'property_3', 'begin_date', 'begin_time']],
		'tabs' => [],
	],

	'event/export/calendar' => array(

        'place_id' => 'A',
        'property_1' => 'B',
        'n_fn' => 'C',
        'groups' => 'D',
        'subcategory' => 'E',
        'property_3' => 'F',
        'status'=> 'G', 
        'begin_date' => 'H',
        'end_date' => 'I',
        'day_of_week' => 'J',
        'begin_time' => 'K',
        'end_time' => 'L',
        'value' => 'M',
        'location' => 'N',
        'caption' => 'O',
        'email_work' => 'P',
        'property_5' => 'Q',
        
    ),
	
	'event/detail/calendar' => array(
		'title' => array('default' => 'Event detail', 'fr_FR' => 'Détail de l\'évènement'),
		'displayAudit' => true,
	),

	'event/format/calendar' => [
		'mask' => '%s %s %s',
		'params' => [
			'property_3' => [],
			'caption' => ['mask' => ' - %s'],
			'location' => ['mask' => '(%s)'],
		],
	],
	
	'event/update/calendar' => array(
		'status' => ['mandatory' => true],
		'place_id' => ['mandatory' => false],
		'groups' => [],
		'subcategory' => ['multiple' => true],
		'property_1' => ['mandatory' => true, 'default' => 'student/property/school_year/default'],
		'account_id' => [],
//		'property_2' => [],
		'property_3' => [],
		'caption' => array('mandatory' => false),
		'property_5' => array('readonly' => true),
		'day_of_week' => array('mandatory' => false),
		'begin_date' => array('mandatory' => true),
		'end_date' => array('mandatory' => false),
		'begin_time' => array('mandatory' => true),
		'end_time' => array('mandatory' => true),
		'exception_1' => array('mandatory' => false),
		'exception_2' => array('mandatory' => false),
		'exception_3' => array('mandatory' => false),
		'exception_4' => array('mandatory' => false),
		'location' => array('mandatory' => false),
	),

	// Absence event

	// Properties between property_1 and property_10 are loaded with their counterpart in the calendar event

	'event/absence/property/account_id' => array(
		'definition' => 'inline',
		'type' => 'select',
		'account_type' => 'p-pit-studies',
		'account_status' => 'active,retention,inscrit_passerelle,alumni,suspendu,exclu,sortant',
		'labels' => array(
			'en_US' => 'Student',
			'fr_FR' => 'Étudiant',
		),
	),
	
	'event/absence/property/account_groups' => array('definition' => 'core_account/generic/property/groups'),
	'event/absence/property/property_1' => array('definition' => 'student/property/school_year'),
//	'event/absence/property/property_2' => array('definition' => 'student/property/school_period'),
	'event/absence/property/property_2' => array('definition' => 'student/property/class'),
	'event/absence/property/property_3' => array('definition' => 'student/property/school_subject'),
	
	// Properties starting from property_11 are reserved for absence specific data

	'event/absence/property/property_11' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => ['definition' => 'inline'],
		'labels' => array(
			'en_US' => 'Slot',
			'fr_FR' => 'Créneau',
		),
	),

	'event/absence/property/property_12' => array(
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => array(
				'medical' => array('en_US' => 'Medical', 'fr_FR' => 'Médical'),
				'training' => array('en_US' => 'Training', 'fr_FR' => 'Entrainement'),
				'competition' => array('en_US' => 'Tournament / Competition', 'fr_FR' => 'Tournoi / Compétition'),
				'spectacle' => array('en_US' => 'Spectacle', 'fr_FR' => 'Spectacle'),
				'family' => array('en_US' => 'Family', 'fr_FR' => 'Familial'),
				'justified' => array('en_US' => 'Justified absence', 'fr_FR' => 'Absence justifiée'),
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

	'event/absence/property/account_property_15' => array('definition' => 'core_account/p-pit-studies/property/property_15'),
	'event/absence/property/account_property_18' => array('definition' => 'core_account/p-pit-studies/property/property_18'),
	'event/absence/property/account_property_19' => array('definition' => 'core_account/p-pit-studies/property/property_19'),
	
	// add email_work in properties
	'event/absence' => array(
		'statuses' => array(),
		'dimensions' => array(),
		'indicators' => array(),
		'properties' => array(
			'status', 'type', 'identifier', 'place_id', 'place_caption', 'account_id', 'n_fn', 'account_groups',
			'begin_date', 'end_date', 'begin_time', 'end_time',
			'property_1', 'property_2', 'property_3', 'property_4', 'property_6', 'property_11', 'property_12', 'property_13', 'account_property_15', 'account_property_18',
			'update_time', 'count', 'email_work'
		),
	),
	
	'event/index/absence' => array(
		'title' => array('default' => 'Planning', 'fr_FR' => 'Planning'),
	),
	
	'event/search/absence' => array(
		'title' => array('default' => 'Absences', 'fr_FR' => 'Absences'),
		'todoTitle' => array('default' => 'recent', 'fr_FR' => 'récents'),
		'searchTitle' => array('default' => 'search', 'fr_FR' => 'recherche'),
		'properties' => array(
			'place_id' => ['multiple' => true],
			'property_1' => ['default' => 'student/property/school_year/default', 'multiple' => true],
			'property_2' => ['multiple' => true],
			'property_3' => ['multiple' => true],
			'account_id' => ['multiple' => true],
			'begin_date' => ['multiple' => true],
		),
	),
	
	'event/list/absence' => array(
		'count' => [],
		'place_id' => [],
		'property_1' => [],
		'account_id' => [],
		'property_2' => [],
		'property_3' => [],
		'property_12' => [],
		'begin_date' => [],
		'begin_time' => [],
		'end_time' => [],
		'update_time' => [],
	),
	
	'event/update/absence' => array(
		'place_id' => ['readonly' => true],
		'account_id' => ['readonly' => true, 'mandatory' => true],
		'property_1' => ['readonly' => true],
		'property_2' => ['readonly' => true],
		'property_3' => ['readonly' => true],
		'begin_date' => ['readonly' => true],
		'end_date' => ['readonly' => true],
		'begin_time' => ['readonly' => true],
		'end_time' => ['readonly' => true],
		'property_12' => [],
	),

	'event/group/absence' => [
		'title' => ['default' => 'Selected absences', 'fr_FR' => 'Absences sélectionnées'],
		'checklist' => ['format' => '%s - %s %s', 'params' => ['n_fn', 'property_3', 'begin_date']],
	],

	'event/notify/absence' => [
		'title' => ['default' => 'Notify absences', 'fr_FR' => 'Notifier des absences'],
		'from_mail' => 'no-reply@p-pit.fr',
		'from_name' => 'Notification - Ne pas répondre',
		'subject' => [
			'text' => ['default' => '%s - Absence', 'fr_FR' => '%s - Absence'],
			'params' => ['place_caption'],
		],
		
		'body' => [
			'text' => [
				'default' => '<p>Hello,</p>
<p>We inform you about the absence(s) of %s detailed below.</p>
<p>Should you need any additional information, please do not hesitate to contact us.</p>
',
				'fr_FR' => '<p>Bonjour,</p>
<p>Nous vous informons de(s) (l’)absence(s) de %s détaillée(s) ci-après.</p>
<p>Si vous avez besoin d’informations complémentaires, n’hésitez-pas à nous contacter.</p>
',
			],
			'params' => ['n_fn'],
			'event_text' => [
				'default' => '<p style="text-align: center"><strong>Subject: %s &mdash; %s from %s to %s (%s) &mdash; Motivation: %s</strong></p>',
				'fr_FR' => '<p style="text-align: center"><strong>Matières: %s &mdash; %s de %s à %s (%s) &mdash; Justification : %s</strong></p>',
			],
			'event_params' => ['property_3', 'begin_date', 'begin_time', 'end_time', 'duration', 'property_12'],
			'sum_text' => [
				'default' => '<p>Which amounts to a sum of <strong>%s</strong> during the period and <strong>%s</strong> absence(s) out of the total number of absences.</p>',
				'fr_FR' => '<p>Soit un total de <strong>%s</strong> sur la période.</p>',
			],
			'sum_params' => ['duration'],
		],
	],
	
	'event/export/absence' => array(
		'count' => 'A',
		'place_id' => 'B',
		'account_id' => 'C',
		'property_1' => 'D',
		'property_2' => 'E',
		'property_3' => 'F',
		'begin_date' => 'G',
		'end_date' => 'H',
		'begin_time' => 'I',
		'end_time' => 'J',
		'property_12' => 'K',
	),
	
	// To be progressively replaced by event/absence
	
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
					'email' => array(
							'definition' => 'inline',
							'type' => 'input',
							'labels' => array(
									'en_US' => 'Email',
									'fr_FR' => 'Email',
							),
					),
					'property_7' => array(
							'type' => 'repository', // Deprecated
							'definition' => 'student/property/class',
					),
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
				
					'account_id' => ['definition' => 'commitment/p-pit-studies/property/account_id'],
					'account_status' => ['definition' => 'commitment/generic/property/account_status'],
					'account_name' => ['definition' => 'core_account/generic/property/name'],
					'account_identifier' => ['definition' => 'commitment/generic/property/account_identifier'],
					'account_groups' => ['definition' => 'core_account/generic/property/groups'],
				
					'account_date_1' => ['definition' => 'core_account/generic/property/date_1'],
					'account_date_2' => ['definition' => 'core_account/generic/property/date_2'],
					'account_date_3' => ['definition' => 'core_account/generic/property/date_3'],
					'account_date_4' => ['definition' => 'core_account/generic/property/date_4'],
					'account_date_5' => ['definition' => 'core_account/generic/property/date_5'],
				
					'account_property_1' => ['definition' => 'core_account/generic/property/property_1'],
					'account_property_2' => ['definition' => 'core_account/generic/property/property_2'],
					'account_property_3' => ['definition' => 'core_account/generic/property/property_3'],
					'account_property_4' => ['definition' => 'core_account/generic/property/property_4'],
					'account_property_5' => ['definition' => 'core_account/generic/property/property_5'],
					'account_property_6' => ['definition' => 'core_account/generic/property/property_6'],
					'account_property_7' => ['definition' => 'core_account/generic/property/property_7'],
					'account_property_8' => ['definition' => 'core_account/generic/property/property_8'],
					'account_property_9' => ['definition' => 'core_account/generic/property/property_9'],
					'account_property_10' => ['definition' => 'core_account/generic/property/property_10'],
					'account_property_11' => ['definition' => 'core_account/generic/property/property_11'],
					'account_property_12' => ['definition' => 'core_account/generic/property/property_12'],
					'account_property_13' => ['definition' => 'core_account/generic/property/property_13'],
					'account_property_14' => ['definition' => 'core_account/generic/property/property_14'],
					'account_property_15' => ['definition' => 'core_account/generic/property/property_15'],
					'account_property_16' => ['definition' => 'core_account/generic/property/property_16'],
					'account_property_18' => ['definition' => 'core_account/generic/property/property_18'],
					'account_property_19' => ['definition' => 'core_account/p-pit-studies/property/property_19'],
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
			'main' => array('place_id' => 'select', 'n_fn' => 'contains', 'account_groups' => 'select', 'account_property_19' => 'select', 'school_year' => 'select', 'school_period' => 'select', 'category' => 'select', 'subject' => 'select', 'begin_date' => 'range'),
			'more' => array(),
	),
	
	'absence/list' => array(
			'place_id' => 'text',
			'n_fn' => 'text',
			'school_period' => 'select',
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
			'email' => 'D',
			'property_7' => 'E',
			'school_period' => 'F',
			'category' => 'G',
			'subject' => 'H',
			'motive' => 'I',
			'begin_date' => 'J',
			'end_date' => 'K',
			'duration' => 'L',
			'observations' => 'M',
			'account_groups' => 'N',
			'account_property_19' => 'O',
		),
	),
	
	// Position Tableau Croisé Excel des absences 
	'event/report/absence' => [
		'columns' => [
            // Student Details
            'n_fn' => ['position' => 'A'],
            'place_caption' => ['position' => 'B'],
            'email_work' => ['position' => 'C'],
            'account_groups' => ['position' => 'D'],
            //'account_property_18' => ['position' => 'E'],
            
            // Student Financials
            'to_justify' => ['position' => 'F', 'type' => 'count', 'labels' => ['default' => 'À justifier'], 'background' => '#EBF1DE', 'filter' => ['property_12' => 'to_justify']],
            'processing' => ['position' => 'G', 'type' => 'count', 'labels' => ['default' => 'Traitement en cours'], 'background' => '#EBF1DE','filter' => ['property_12' => 'processing']],
            'medical' => ['position' => 'H', 'type' => 'count', 'labels' => ['default' => 'Raisons médicales'], 'background' => '#EBF1DE','filter' => ['property_12' => 'medical']],
            'enterprise' => ['position' => 'I', 'type' => 'count', 'labels' => ['default' => 'Présence entreprise'], 'background' => '#EBF1DE','filter' => ['property_12' => 'enterprise']],
			// add motifs 
			'work' => ['position' => 'J', 'type' => 'count', 'labels' => ['default' => 'Arrêt de travail'], 'background' => '#EBF1DE','filter' => ['property_12' => 'work']],
			'exclusion_class' => ['position' => 'K', 'type' => 'count', 'labels' => ['default' => 'Exclusion de cours'], 'background' => '#EBF1DE','filter' => ['property_12' => 'exclusion_class']],

            'unjustified' => ['position' => 'L', 'type' => 'count', 'labels' => ['default' => 'Non justifié'], 'background' => '#EBF1DE','filter' => ['property_12' => 'unjustified']],
			'unrecevable' => ['position' => 'M', 'type' => 'count', 'labels' => ['default' => 'Non recevable'], 'background' => '#EBF1DE','filter' => ['property_12' =>'unrecevable']],
			'other' => ['position' => 'N', 'type' => 'count', 'labels' => ['default' => 'Autre justificatif'], 'background' => '#EBF1DE','filter' => ['property_12' => 'other']],


            'total_student' => [
                'position' => 'O',
                'type' => 'count',
                'labels' => ['default' => 'Total Etudiant'],
                'background' => '#EBF1DE',
                //'indicator' => ['to_justify','processing','medical','unjustified','other'],
            ],
        ],
        'sums' => [
            // Totals Students

            'to_justify' => ['position' => 'F', 'type' => 'count', 'labels' => ['default' => 'À justifier'], 'background' => '#f44336', 'filter' => ['property_12' => 'to_justify']],
            'processing' => ['position' => 'G', 'type' => 'count', 'labels' => ['default' => 'Traitement en cours'], 'background' => '#f44336','filter' => ['property_12' => 'processing']],
            'medical' => ['position' => 'H', 'type' => 'count', 'labels' => ['default' => 'Raisons médicales'], 'background' => '#f44336','filter' => ['property_12' => 'medical']],
            'enterprise' => ['position' => 'I', 'type' => 'count', 'labels' => ['default' => 'Présence entreprise'], 'background' => '#f44336','filter' => ['property_12' => 'enterprise']],
			// add motifs 
			'work' => ['position' => 'J', 'type' => 'count', 'labels' => ['default' => 'Arrêt de travail'], 'background' => '#f44336','filter' => ['property_12' => 'work']],
			'exclusion_class' => ['position' => 'K', 'type' => 'count', 'labels' => ['default' => 'Exclusion de cours'], 'background' => '#f44336','filter' => ['property_12' => 'exclusion_class']],

            'unjustified' => ['position' => 'L', 'type' => 'count', 'labels' => ['default' => 'Non justifié'], 'background' => '#f44336','filter' => ['property_12' => 'unjustified']],
			'unrecevable' => ['position' => 'M', 'type' => 'count', 'labels' => ['default' => 'Non recevable'], 'background' => '#f44336','filter' => ['property_12' =>'unrecevable']],
			'other' => ['position' => 'N', 'type' => 'count', 'labels' => ['default' => 'Autre justificatif'], 'background' => '#f44336','filter' => ['property_12' => 'other']],


            'total_student' => [
                'position' => 'O',
                'type' => 'count',
                'labels' => ['default' => 'Total Etudiant Par Motif'],
                'background' => '#f44336',
                //'indicator' => ['ƒ','processing','medical','unjustified','other'],
            ],
        ],
	],



	// Note

	'note/property/group_id' => [
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => [], // Dynamically loaded
		'labels' => ['default' => 'Groupe'],
	],
	
	'note/property/place_caption' => array(
			'type' => 'text',
			'labels' => array(
					'en_US' => 'Place',
					'fr_FR' => 'Centre',
			),
	),

	'note/property/teacher_id' => array(
		'definition' => 'inline',
		'type' => 'input',
		'labels' => array(
			'en_US' => 'Teacher',
			'fr_FR' => 'Intervenant',
		),
	),

	'note/property/date' => array(
			'type' => 'date',
			'labels' => array(
					'en_US' => 'Notification date',
					'fr_FR' => 'Date de notification',
			),
	),
	'note/property/target_date' => array(
			'type' => 'date',
			'labels' => array(
					'en_US' => 'Target date',
					'fr_FR' => 'Date cible',
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
	'note/property/account_email_work' => array(
		'type' => 'textarea',
		'labels' => array(
			'en_US' => 'Email Work',
			'fr_FR' => 'Email Ecole',
		),
	),
	'note/property/value' => array(
			'type' => 'number',
			'labels' => array(
					'en_US' => 'Value',
					'fr_FR' => 'Valeur',
			),
			'precision' => 2,
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
	'note/property/average' => array(
			'type' => 'number',
			'labels' => array(
					'en_US' => 'Student average',
					'fr_FR' => 'Moyenne de l’élève',
			),
	),
	'note/property/global_average' => array(
		'type' => 'number',
		'labels' => array(
				'en_US' => 'Student global average',
				'fr_FR' => 'Moyenne générale de l’élève',
		),
	),
	'note/property/yearly_average' => array(
		'type' => 'number',
		'labels' => array(
				'en_US' => 'Student yeraly average',
				'fr_FR' => 'Moyenne annuelle de l’élève',
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
	'note/property/catchUp' => array(
		'type' => 'textarea',
		'labels' => array(
			'en_US' => 'Catch-up',
			'fr_FR' => 'Rattrapages / Défaillant',
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
									'default' => 'Campus',
							),
					),
					'place_caption' => array('definition' => 'note/property/place_caption'),
					'teacher_id' => ['definition' => 'note/property/teacher_id'],
					'group_id' => ['definition' => 'note/property/group_id'],
					'school_year' => array(
							'type' => 'repository', //Deprecated
							'definition' => 'student/property/school_year',
					),
					'school_period' => array(
							'type' => 'repository', //Deprecated
							'definition' => 'student/property/school_period',
					),
					'group_id' => array('definition' => 'student/property/groups'),
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
					'target_date' => array('definition' => 'note/property/target_date'),
					'name' => array('definition' => 'note/property/name'),
					'value' => array('definition' => 'note/property/value'),
					'reference_value' => array('definition' => 'note/property/reference_value'),
					'weight' => array('definition' => 'note/property/weight'),
					'average' => array('definition' => 'note/property/average'),
					'global_average' => array('definition' => 'note/property/global_average'),
					'yearly_average' => array('definition' => 'note/property/yearly_average'),
					'lower_note' => array('definition' => 'note/property/lower_note'),
					'higher_note' => array('definition' => 'note/property/higher_note'),
					'average_note' => array('definition' => 'note/property/average_note'),
					'distribution' => array('definition' => 'note/property/distribution'),
					'assessment' => array('definition' => 'note/property/assessment'),
					'evaluation' => array('definition' => 'student/property/reportMention'),
					'observations' => array('definition' => 'note/property/observations'),
					'group_id' => array('definition' => 'note/property/group_id'),
					'catchUp' => array('definition' => 'note/property/catchUp'),
					'account_email_work' => array('definition' => 'note/property/account_email_work'),
			),
	),
	'report/generic/property/groups' => ['definition' => 'core_account/generic/property/groups'],


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
					'teacher_id' => 'select',
					'school_year' => 'select',
					'school_period' => 'select',
					'group_id' => 'select',
					'subject' => 'select',
					'date' => 'date',
					'account_email_work' => 'input',
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
			'group_id' => 'select',
			'subject' => 'select',
			'target_date' => 'date',
		),
		'more' => array(
		),
	),
	
	'note/list/homework' => array(
			'place_id' => 'select',
			'school_period' => 'select',
			'type' => 'select',
			'group_id' => 'select',
			'subject' => 'select',
			'date' => 'date',
			'target_date' => 'date',
	),
		
	'note/list/evaluation' => array(
			'place_id' => 'select',
			'school_period' => 'select',
			'group_id' => 'select',
			'subject' => 'select',
			'date' => 'date',
			'weight' => 'number',
	),

	'note/export/homework' => array(
			'title' => ['default' => 'Homework', 'fr_FR' => 'Cahier de texte'],
			'properties' => array(
				'id' => 'A',
				'type' => 'B',
				'place_caption' => 'C',
				'school_period' => 'D',
				'group_id' => 'E',
				'level' => 'F',
				'subject' => 'G',
				'name' => 'H',
				'target_date' => 'I',
				'observations' => 'J',
				'date' => 'K',
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
			'account_email_work' => 'I',
			'date' => 'J',
			'reference_value' => 'K',
			'weight' => 'L',
			'value' => 'M',
			'average' => 'N',
			'global_average' => 'O',
			'yearly_average' => 'P',
			'lower_note' => 'Q',
			'average_note' => 'R',
			'higher_note' => 'S',
			'assessment' => 'T',
			'evaluation' => 'U',
			'group_id' => 'V',
			'catchUp' => 'W',
		),
	),

	'note/update/evaluation' => [
		'properties' => [
			'place_id' => [],
			'school_year' => [],
			'school_period' => [],
			'teacher_id' => [],
			'subject' => [],
			'level' => [],
			'date' => [],
			'reference_value' => [],
			'weight' => [],
			'assessment' => [],
			'observations' => [],
		],
	],

	'report/update/evaluation' => [
		'properties' => [
			'place_id' => [],
			'school_year' => [],
			'school_period' => [],
			'teacher_id' => [],
			'subject' => [],
			'date' => [],
			'reference_value' => [],
			'weight' => [],
			'assessment' => [],
			'groups' => [],
		],
	],
	
	// Progress
	
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
			'default' => 'calendar-next',
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
	'public/community/student' => array( // Deprecated
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
											'label' => array('en_US' => 'Absences & lateness', 'fr_FR' => 'Absences & retards'),
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

	'student/home/tabs' => array(
		'content' => array(
			'planning' => array(
				'type' => 'calendar',
				'level' => 'community',
				'route' => 'student/planningV2',
				'label' => array('en_US' => 'Planning', 'fr_FR' => 'Planning'),
			),
			'evaluation' => array(
				'type' => 'static',
				'level' => 'subject',
				'route' => 'student/evaluationV2',
				'filter' => 'evaluation_category',
				'label' => array('en_US' => 'Evaluations', 'fr_FR' => 'Relevés de notes'),
			),
			'schooling' => array(
				'type' => 'static',
				'level' => 'subject',
				'route' => 'student/reportV2',
				'label' => array('en_US' => 'School reports', 'fr_FR' => 'Bulletins scolaires'),
			),
			'mock_evaluation' => array(
				'type' => 'static',
				'level' => 'subject',
				'route' => 'student/examV2',
				'params' => ['mock' => 'mock'],
				'label' => array('en_US' => 'Mock exams', 'fr_FR' => 'Examens blancs'),
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
],

	COMMITMENT_MESSAGE_P_PIT_STUDIES,
	CORE_ACCOUNT_MESSAGE_P_PIT_STUDIES,
	CORE_ACCOUNT_MESSAGE_TEACHER,
	EVENT_MESSAGE_P_PIT_STUDIES,
	NOTE_LINK_GENERIC,
	EVENT_ABSENCE,
);
