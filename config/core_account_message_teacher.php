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
.table {
	font-size: 7;
	padding-top: 10;
	padding-right: 5;
	padding-bottom: 10;
	padding-left: 5;
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
				'class' => '',
				'paragraphs' => [
					['type' => 'h1', 'class' => 'text-center', 'label' => ['default' => 'Récapitulatif par salarié']],
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
						'style' => 'border: 1px solid black; width: 10%%; text-align:center',
						'header' => ['default' => '<strong>Date</strong>'],
						'label' => ['default' => '<span>%s</span>'],
						'params' => ['event:begin_date'],
						'sum' => ['style' => 'text-align:center'],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black; width: 30%%',
						'header' => ['default' => '<strong>Action</strong>'],
						'label' => ['default' => '<span>%s</span>'],
						'params' => ['event:property_2'],
						'sum' => ['style' => 'text-align:center'],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black; width: 30%%',
						'header' => ['default' => '<strong>Module/matière</strong>'],
						'label' => ['default' => '<span>%s</span>'],
						'params' => ['event:property_3'],
						'sum' => ['style' => 'text-align: right', 'label' => ['default' => '<strong>Total %s</strong>'], 'params' => ['month']],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black; width: 10%%; text-align:center',
						'header' => ['default' => '<strong>Début</strong>'],
						'label' => ['default' => '<span>%s</span>'],
						'params' => ['event:begin_time'],
						'sum' => ['style' => 'text-align:center'],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black; width: 10%%; text-align:center',
						'header' => ['default' => '<strong>Durée</strong>'],
						'label' => ['default' => '<span>%s</span>'],
						'params' => ['event:duration'],
						'sum' => ['style' => 'text-align:center', 'label' => ['default' => '<strong>%s</strong>'], 'params' => ['time_sum']],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black; width: 10%%; text-align:center',
						'header' => ['default' => '<strong>Fin</strong>'],
						'label' => ['default' => '<span>%s</span>'],
						'params' => ['event:end_time'],
						'sum' => ['style' => 'text-align:center'],
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
