<?php
/**
 * PpitCore V1.0 (https://github.com/p-pit/PpitCore)
 *
 * @link      https://github.com/p-pit/PpitCore
 * @copyright Copyright (c) 2016 Bruno Lartillot
 * @license   https://github.com/p-pit/PpitCore/blob/master/license.txt GNU-GPL license
 */

define('CORE_ACCOUNT_MESSAGE_TEACHER', [

	'commitments/message/teacher/time_sheet' => [
		'name' => ['default' => 'Relevé d’heures formateur'],
		'route' => 'event/timeSheet',
		'style' => ['default' => '
			<style>
			table {
				border-collapse: collapse;
			}
			p {
				font-size: 10;
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
							'default' => 'Learning course: <strong>%s</strong>',
							'fr_FR' => 'Formation: <strong>%s</strong>'
						],
						'params' => ['property_2'],
					],
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
			'event' => [
				'class' => 'table',
				'entity' => 'event',
				'paragraphs' => [
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Trainee</strong>', 'fr_FR' => '<strong>Stagiaire</strong>'],
						'label' => ['default' => '<span>%s %s</span>'],
						'params' => ['event:begin_date', 'event:end_date'],
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
	
	'commitments/message/teacher' => [
		'time_sheet',
	],
]);
