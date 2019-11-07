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
		'name' => ['default' => 'Récapitulatif par salarié puis type de pointage'],
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
					['type' => 'h1', 'class' => 'text-center', 'label' => ['default' => 'Récapitulatif par salarié puis type de pointage']],
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
							'default' => '<strong>%s</strong>',
						],
						'params' => ['n_fn'],
					],
					['type' => 'br'],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => [
							'default' => '<strong>%s</strong>',
						],
						'params' => ['month'],
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
						'header' => ['default' => '<strong>Date</strong>'],
						'label' => ['default' => '<span>%s</span>'],
						'params' => ['event:begin_date'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Code analytique</strong>'],
						'label' => ['default' => '<span>%s</span>'],
						'params' => ['event:property_3'],
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
