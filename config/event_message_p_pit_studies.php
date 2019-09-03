<?php
/**
 * PpitCore V1.0 (https://github.com/p-pit/PpitCore)
 *
 * @link      https://github.com/p-pit/PpitCore
 * @copyright Copyright (c) 2016 Bruno Lartillot
 * @license   https://github.com/p-pit/PpitCore/blob/master/license.txt GNU-GPL license
 */

define('EVENT_MESSAGE_P_PIT_STUDIES', [

	'event/message/p-pit-studies/attendance_sheet' => [
		'name' => ['default' => 'Attendance sheet', 'fr_FR' => 'Feuille d’émargement'],
		'filters' => ['groups', 'property_7'],
		'style' => ['default' => '
			<style>
	    	table {
	    		border-collapse: collapse;
			}
			table, th, td {
	    		font-size: 10;
				padding-top: 10;
				padding-right: 5;
				padding-bottom: 10;
				padding-left: 5;
			border: 1px solid black;
			}
			.addressee {
				font-weight: bold;
				font-size: 12;
			}
			.text-center {
				text-align: center;
			}
			.text-justify{
				text-align: justify;
			}
			</style>
		'],
		'sections' => [
			'header' => [
				'class' => 'header',
				'paragraphs' => [
				],
			],
			'title' => [
				'class' => 'box-title',
				'paragraphs' => [
					['type' => 'h1', 'class' => 'text-center', 'label' => ['default' => 'ATTENDANCE SHEET', 'fr_FR' => 'LISTE D’ÉMARGEMENT']],
				],
			],
			'body' => [
				'class' => 'body',
				'paragraphs' => [
					['type' => 'br'],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => [
							'default' => 'Module: <strong>%s</strong> &mdash; Date: <strong>%s</strong> &mdash; Start: <strong>%s</strong> &mdash; End: <strong>%s</strong> &mdash; Duration: <strong>%s</strong>',
							'fr_FR' => 'Module: <strong>%s</strong> &mdash; Date: <strong>%s</strong> &mdash; Début: <strong>%s</strong> &mdash; Fin: <strong>%s</strong> &mdash; Durée: <strong>%s</strong>'
						],
						'params' => ['property_3', 'begin_date', 'begin_time', 'end_time', 'duration'],
					],
					['type' => 'br'],
				],
			],
			'table' => [
				'class' => 'table',
				'entity' => 'account',
				'columns' => [
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Trainee</strong> / Hours', 'fr_FR' => '<strong>Stagiaire</strong> / Horaire'],
						'label' => ['default' => '<strong>%s %s</strong> / from %s to %s (%s)', 'fr_FR' => '<strong>%s %s</strong> / de %s à %s (%s)'],
						'params' => ['account:n_first', 'account:n_last', 'begin_time', 'end_time', 'duration'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Trainee’s signature</strong> / Hours', 'fr_FR' => '<strong>Signature du stagiaire</strong>'],
						'label' => ['default' => '&nbsp;'],
					],
				],
			],
			'footer' => [
				'class' => 'footer',
				'paragraphs' => [
				],
			],
		],
	],
	
	'event/message/p-pit-studies' => [
		'attendance_sheet',
	],
]);
