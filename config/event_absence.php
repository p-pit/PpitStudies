<?php
/**
 * PpitCore V1.0 (https://github.com/p-pit/PpitCore)
 *
 * @link      https://github.com/p-pit/PpitCore
 * @copyright Copyright (c) 2016 Bruno Lartillot
 * @license   https://github.com/p-pit/PpitCore/blob/master/license.txt GNU-GPL license
 */

define('EVENT_ABSENCE', [

		/**
		 * event/absence
		 */
		
		'event/absence/property/property_12' => array(
			'definition' => 'inline',
			'type' => 'select',
			'modalities' => array(
				'to_justify' => array('default' => 'À justifier'),
				'processing' => array('default' => 'Traitement en cours'),
				'unrecevable' => array('default' => 'Non recevable'),
				'justified' => array('default' => 'Justifiée'),
			),
			'labels' => array(
				'en_US' => 'Absence status',
				'fr_FR' => 'Statut de l\'absence',
			),
		),

		'event/absence/property/property_13' => array(
			'definition' => 'inline',
			'type' => 'select',
			'modalities' => array(
				'medical' => array('default' => 'Raisons médicales'),
				'work' => array('default' => 'Arrêt de travail'),
				'enterprise' => array('default' => 'Présence entreprise'),
				'exclusion_class' => array('default' => 'Exclusion de cours'),
				'death' => array('default' => 'Décès'),
				'appointment' => array('default' => 'RDV d\'administration'), 
				'other' => array('default' => 'Autre justificatif'),
			),
			'labels' => array(
				'en_US' => 'Motive',
				'fr_FR' => 'Motif',
			),
		),

		// event commentaire 


		'event/absence/property/property_4' => array(
			'definition' => 'inline',
			'type' => 'input',
			
			'labels' => array(
				'default' => 'Commentaire ',
			),
		),
		// absence lien motif 


		'event/absence/property/property_6' => array(
			'definition' => 'inline',
			'type' => 'link',
			'labels' => array(
				'default' => 'Justificatif',
			),
		),

		// add property email work
		'event/absence/property/email_work' => array(
			'definition' => 'inline',
			'type' => 'email',
			'labels' => array(
				'default' => 'School e-mail',
				'fr_FR' => 'Email école',
			),
		),

		// 'event/absence/property/creation_time' => array(
		// 	'definition' => 'inline',
		// 	'type' => 'date',
		// 	'labels' => array(
		// 		'default' => 'Creation time',
		// 		'fr_FR' => 'Date de création',
		// 	),
		// ),

		'absence/property/motive' => array(
			'type' => 'select',
			'modalities' => array(
				'to_justify' => array('default' => 'À justifier'),
				'processing' => array('default' => 'Traitement en cours'),
				'medical' => array('default' => 'Arrêt maladie'),
				'enterprise' => array('default' => 'Présence entreprise'),
				'other' => array('default' => 'Autre justificatif'),
				'unjustified' => array('default' => 'Non justifié'),
			),
			'labels' => array(
				'en_US' => 'Motive',
				'fr_FR' => 'Motif',
			),
		),
		
		'event/search/absence' => array(
			'title' => array('default' => 'Absences', 'fr_FR' => 'Absences'),
			'todoTitle' => array('default' => 'recent', 'fr_FR' => 'récents'),
			'searchTitle' => array('default' => 'search', 'fr_FR' => 'recherche'),
			
			'properties' => array(
			
				'place_id' => ['multiple' => true],
				'account_property_18' => ['multiple' => true],
				'account_id' => ['multiple' => true],
				'account_groups' => ['multiple' => true],
				'account_property_15' => ['multiple' => true],
				'property_1' => ['multiple' => true],
				'property_3' => ['multiple' => true],
				'begin_date' => ['multiple' => true],
				'property_12' =>['default'=> 'processing', 'multiple' => true ],
				'property_13' => ['multiple' => true],
				'count' => [],
			),
		),

		'event/list/absence' => array(
			'count' => [],
			'place_id' => [],
			'property_1' => [],
			'account_id' => [],
			'account_groups' => [],
			'property_3' => [],
			'property_12' => [],
			'property_13' => [],
			'begin_date' => [],
			'begin_time' => [],
			'end_time' => [],
			'update_time' => [],
			'property_4'=>[],
			'property_6' => [], 
			'account_property_15' => [],
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
			'property_13' => [],
			'property_4' => [],
			'property_6' => [],
		),
	
		'event/export/absence' => array(
			'count' => 'A',
			'place_id' => 'B',
			'account_id' => 'C',
			// ajout mail etudiant
			'email_work' => 'D',
			'property_1' => 'E',
			'property_2' => 'F',
			'property_3' => 'G',
			'begin_date' => 'H',
			'end_date' => 'I',
			'begin_time' => 'J',
			'end_time' => 'K',
			'property_12' => 'L',
			'property_13' => 'M',
			'property_4' => 'N',
			'property_6' => 'O',
		),
		'event/notify/absence' => [
			'title' => ['default' => 'Notifier des absences'],
			'from_mail' => 'no-reply@p-pit.fr',
			'from_name' => 'Notification - Ne pas répondre',
			'subject' => [
				'text' => ['default' => 'Groupe GEMA - Vos absences'],
				'params' => [],
			],
			
			'body' => [
				'text' => [
					'default' => '<p>Bonjour %s</p>',
				],
				'params' => ['n_fn'],
				'event_text' => [],
				'event_params' => [],
				'sum_text' => [
					'default' => '
					<p>Nous vous informons que vous avez un total de <strong>%s absences</strong> sur l’ensemble du semestre.</p>
					<p>Veuillez vous rapprocher de votre pôle pédagogique dans les meilleurs délais afin de clarifier la situation.</p>
					<p>Nous vous rappelons que <strong>3 absences </strong> dans un même module entraînent le rattrapage de la matière et que <strong>20 absences</strong> au total entraînent le rattrapage de tous les modules.</p>
					<p></p>
					<p>L\'équipe pédagogique se tient disponible au besoin.</p>
					<p></p>
					<p>-----</p>
					<p></p>
					<p><strong>%s</strong> - <strong>%s</strong></p>
					<p>01.39.71.12.12 touche 3</p>',
					
				],
				'sum_params' => ['nbEvents', 'account_property_18', 'place_caption'],
			],
		],

		'event/notify/absence/relance' => [
			'title' => ['default' => 'Notifier des absences'],
			'from_mail' => 'no-reply@p-pit.fr',
			'from_name' => 'Notification - Ne pas répondre',
			'subject' => [
				'text' => ['default' => 'Rappel - Absences à justifier '],
				'params' => [],
			],
			
			'body' => [
				'text' => [],
				'params' => [],
				'event_text' => [],
				'event_params' => [],
				'sum_text' => [
					'default' => '
					<p>Cher.e étudiant.e,</p>
					<p>Vous avez une ou plusieurs absences à justifier sur votre espace MyGema.</p>
					<p>Sans justificatif de votre part dans les 48 heures, toutes ces absences seront automatiquement considérées comme <strong>"Non justifiées"</strong>.</p>
					<p>Nous vous rappelons que <u>les absences doivent normalement être justifiées sous 48 heures</u> à partir du jour de l\'absence, directement sur votre espace MyGema.</p>
					<p></p>
					<p>L\'équipe pédagogique se tient disponible au besoin.</p>
					<p>Bien à vous,</p>
					<p></p>
					<p><em>Merci de ne pas répondre à ce courriel</em></p>
					<p></p>
					<p>-----</p>
					<p></p>
					<p><strong>%s</strong> - <strong>%s</strong></p>
					<p>01.39.71.12.12 touche 3</p>',
				],
				'sum_params' => [ 'account_property_18', 'place_caption'],
			],
		],
		
		//  Mail spécifique aux part-times, afin de mieux suivre les personnes en alternance
		'event/notify/absence/part-time' => [
			'title' => ['default' => 'Notifier des absences'],
			'from_mail' => 'no-reply@p-pit.fr',
			'from_name' => 'Notification - Ne pas répondre',
			'subject' => [
				'text' => ['default' => 'Alternant - Vos absences'],
				'params' => [],
			],
			
			'body' => [
				'text' => [
					'default' => '<p>Bonjour %s</p>',
				],
				'text' => [],
				'params' => [],
				'event_text' => [],
				'event_params' => [],
				'sum_text' => [
					'default' => '
					<p>Bonjour,</p>
					<p>Vous cumulez déjà <strong>%s absences</strong> pour ce semestre.</P>
					<p>Dans le cadre de votre contrat d\'alternance et pour la réussite pédagogique de votre année, nous vous rappelons qu\'il est primordial d\'être assidu.e à vos cours.</p>
					<p>Pour rappel, trois absences dans un même module entraînent un rattrapage obligatoire en septembre.</p>
					<p>Par ailleurs, votre tuteur a également accès à vos absences, qui peuvent être décomptées de votre paie. A ce sujet, n\'hésitez pas à contacter votre référent au pôle carrières si vous avez besoin de faire le point.</p>
					<p>En cas d\'absence inévitable et indépendante de votre volonté, pensez à dûment la justifier sur votre espace MyGema (Arrêt de travail, justificatif officiel, etc.).</p>
					<p></p>
					<p>L\'équipe pédagogique se tient disponible au besoin.</p>
					<p></p>
					<p>-----</p>
					<p></p>
					<p><strong>%s</strong> - <strong>%s</strong></p>
					<p>01.39.71.12.12 touche 3</p>',
					
				],
				'sum_params' => ['nbEvents', 'account_property_18', 'place_caption'],
			],
		],
		
	]);