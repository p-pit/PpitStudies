<?php
/**
 * PpitCore V1.0 (https://github.com/p-pit/PpitCore)
 *
 * @link      https://github.com/p-pit/PpitCore
 * @copyright Copyright (c) 2016 Bruno Lartillot
 * @license   https://github.com/p-pit/PpitCore/blob/master/license.txt GNU-GPL license
 */

define('NOTE_LINK_GENERIC', [

	'v3/note_link/generic' => [
		'entities' => [
			'student_note' => 		['table' => 'student_note', 'foreign_entity' => 'student_note_link', 'foreign_key' => 'note_id'],
			'core_account' => 		['table' => 'core_account', 'foreign_entity' => 'student_note_link', 'foreign_key' => 'account_id'],
			'core_place' => 		['table' => 'core_place', 'foreign_entity' => 'student_note', 'foreign_key' => 'place_id'],
			'core_vcard' => 		['table' => 'core_vcard', 'foreign_entity' => 'core_account', 'foreign_key' => 'contact_1_id'],
			'teacher' => 			['table' => 'core_vcard', 'foreign_entity' => 'student_note', 'foreign_key' => 'teacher_id'],
		],
		'properties' => [
			'id' => 				['entity' => 'student_note_link', 'column' => 'id'],
			'status' => 			['entity' => 'student_note_link', 'column' => 'status'],
			'account_id' => 		['entity' => 'student_note_link', 'column' => 'account_id'],
			'note_id' => 			['entity' => 'student_note_link', 'column' => 'note_id'],
			'specific_weight' => 	['entity' => 'student_note_link', 'column' => 'specific_weight'],
			'value' => 				['entity' => 'student_note_link', 'column' => 'value'],
			'distribution' => 		['entity' => 'student_note_link', 'column' => 'distribution'],
			'evaluation' => 		['entity' => 'student_note_link', 'column' => 'evaluation'],
			'assessment' => 		['entity' => 'student_note_link', 'column' => 'assessment'],
			'class' => 				['entity' => 'student_note_link', 'column' => 'class'],
			'distribution' => 		['entity' => 'student_note_link', 'column' => 'distribution'],
			'update_time' => 		['entity' => 'student_note_link', 'column' => 'update_time'],
			'place_id' => 			['entity' => 'core_account', 'column' => 'place_id'],
			'place_caption' => 		['entity' => 'core_place', 'column' => 'caption'],
			'n_fn' => 				['entity' => 'core_vcard', 'column' => 'n_fn'],
			'name' => 				['entity' => 'core_account', 'column' => 'name'],
			'account_property_15' =>['entity' => 'core_account', 'column' => 'property_15'],
			'note_status' => 		['entity' => 'student_note', 'column' => 'status'],
			'category' => 			['entity' => 'student_note', 'column' => 'category'],
			'type' => 				['entity' => 'student_note', 'column' => 'type'],
			'school_year' => 		['entity' => 'student_note', 'column' => 'school_year'],
			'level' => 				['entity' => 'student_note', 'column' => 'level'],
			'group_id' => 			['entity' => 'student_note', 'column' => 'group_id'],
			'criteria' => 			['entity' => 'student_note', 'column' => 'criteria'],
			'school_period' => 		['entity' => 'student_note', 'column' => 'school_period'],
			'subject' => 			['entity' => 'student_note', 'column' => 'subject'],
			'teacher_id' => 		['entity' => 'student_note', 'column' => 'teacher_id'],
			'teacher_n_fn' => 		['entity' => 'teacher', 'column' => 'n_fn'],
			'date' => 				['entity' => 'student_note', 'column' => 'date'],
			'target_date' => 		['entity' => 'student_note', 'column' => 'target_date'],
			'reference_value' => 	['entity' => 'student_note', 'column' => 'reference_value'],
			'weight' => 			['entity' => 'student_note', 'column' => 'weight'],
			'observations' => 		['entity' => 'student_note', 'column' => 'observations'],
			'lower_note' => 		['entity' => 'student_note', 'column' => 'lower_note'],
			'higher_note' => 		['entity' => 'student_note', 'column' => 'higher_note'],
			'average_note' => 		['entity' => 'student_note', 'column' => 'average_note'],
		],
	],
	
	// Note link

	'note_link/generic/property/status' => [
		'definition' => 'inline',
		'type' => 'select',
		'labels' => ['default' => 'Observations'],
		'modalities' => [
			'new' => ['default' => 'Nouveau'],
		],
	],

	'note_link/generic/property/account_id' => [
		'definition' => 'inline',
		'type' => 'dynamic',
		'modalities' => [],
		'labels' => [
			'en_US' => 'Account',
			'fr_FR' => 'Compte',
		],
	],
	
	'note_link/generic/property/value' => [
		'definition' => 'inline',
		'type' => 'select',
		'labels' => ['default' => 'Value'],
		'modalities' => [
			'4' => ['default' => 'A'],
			'3.5' => ['default' => 'B'],
			'3' => ['default' => 'C'],
			'2.5' => ['default' => 'D'],
			'2' => ['default' => 'E'],
			'1' => ['default' => 'F'],
			'0.5' => ['default' => 'FX'],
		],
	],
	
	'note_link/generic/property/evaluation' => [
		'definition' => 'inline',
		'type' => 'input',
		'labels' => ['default' => 'Evaluation'],
	],

	'note_link/generic/property/assessment' => [
		'definition' => 'inline',
		'type' => 'textarea',
		'labels' => ['default' => 'Commentaire'],
	],

	'note_link/generic/property/update_time' => [
		'definition' => 'inline',
		'type' => 'time',
		'labels' => [
			'en_US' => 'Last update',
			'fr_FR' => 'Dernière mise à jour',
		],
	],

	'note_link/generic/property/place_id' => ['definition' => 'core_account/generic/property/place_id'],
	'note_link/generic/property/place_caption' => ['definition' => 'corePlace/property/caption'],
	'note_link/generic/property/n_fn' => ['definition' => 'note/property/n_fn'],
	'note_link/generic/property/name' => ['definition' => 'note/property/name'],
	'note_link/generic/property/account_property_15' => ['definition' => 'core_account/p-pit-studies/property/property_15'],
	
	'note_link/generic/property/school_year' => ['definition' => 'student/property/school_year'],
	'note_link/generic/property/level' => array('definition' => 'student/property/evaluationCategory'),

	'note_link/generic/property/group_id' => [
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => [], // Dynamically loaded
		'labels' => ['default' => 'Groupe'],
	],

	'note_link/generic/property/teacher_id' => [
		'definition' => 'inline',
		'type' => 'select',
		'modalities' => [], // Dynamically loaded
		'labels' => ['default' => 'Intervenant'],
	],

	'note_link/generic/property/teacher_n_fn' => [
		'definition' => 'inline',
		'type' => 'input',
		'labels' => ['default' => 'Intervenant'],
	],

	'note_link/generic/property/specific_weight' => array(
		'definition' => 'inline',
		'type' => 'number',
		'labels' => array(
				'en_US' => 'Coef. / Credits',
				'fr_FR' => 'Coef./ Crédits',
		),
	),

	'note_link/generic/property/class' => ['definition' => 'student/property/class'],
	'note_link/generic/property/school_period' => ['definition' => 'student/property/school_period'],
	'note_link/generic/property/subject' => ['definition' => 'student/property/school_subject'],
	'note_link/generic/property/date' => array('definition' => 'note/property/date'),
	'note_link/generic/property/target_date' => array('definition' => 'note/property/target_date'),
	'note_link/generic/property/value' => array('definition' => 'note/property/value'),
	'note_link/generic/property/reference_value' => array('definition' => 'note/property/reference_value'),
	'note_link/generic/property/weight' => array('definition' => 'note/property/weight'),
	'note_link/generic/property/average' => array('definition' => 'note/property/average'),
	'note_link/generic/property/observations' => array('definition' => 'note/property/observations'),
	'note_link/generic/property/lower_note' => array('definition' => 'note/property/lower_note'),
	'note_link/generic/property/higher_note' => array('definition' => 'note/property/higher_note'),
	'note_link/generic/property/average_note' => array('definition' => 'note/property/average_note'),

	'note_link/generic' => [
		'properties' => [
			'status', 'account_id', 'value', 'evaluation', 'assessment', 'update_time',
			'place_id', 'n_fn', 'name', 'account_property_15',
			'school_year', 'level', 'group_id', 'teacher_id', 'class', 'school_period', 'subject', 'date', 'target_date', 'reference_value', 'weight', 'specific_weight', 'average', 'observations', 'lower_note', 'higher_note', 'average_note',
		],
	],

	'note_link/routes' => [
		'export' => 'note/export',
	],

	'note_link/student_list/generic' => [
		'subject' => [],
		'date' => [],
		'reference_value' => [],
		'weight' => [],
		'value' => [], 
//		'evaluation' => [], 
		'assessment' => [],
	],

	'note_link/search/generic' => [
		'place_id' => [],
		'school_year' => [],
		'school_period' => [],
		'teacher_id' => [],
		'class' => [],
		'group_id' => [],
		'name' => [],
		'subject' => [],
		'level' => [],
		'date' => [],
	],
	
	'note_link/list/generic' => [
		'place_caption' => [],
		'school_year' => [],
		'school_period' => [],
		'teacher_n_fn' => [],
		'class' => [],
		'group_id' => [],
		'name' => [],
		'subject' => [],
		'level' => [],
		'date' => [],
		'reference_value' => [],
		'weight' => [],
		'value' => [],
//		'evaluation' => [],
		'assessment' => [],
	],

	'note_link/group/generic' => [
		'value' => [],
		'evaluation' => [],
		'assessment' => [],
	],
]);
