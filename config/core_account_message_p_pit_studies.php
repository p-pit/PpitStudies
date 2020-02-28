<?php
/**
 * PpitCore V1.0 (https://github.com/p-pit/PpitCore)
 *
 * @link      https://github.com/p-pit/PpitCore
 * @copyright Copyright (c) 2016 Bruno Lartillot
 * @license   https://github.com/p-pit/PpitCore/blob/master/license.txt GNU-GPL license
 */

define('CORE_ACCOUNT_MESSAGE_P_PIT_STUDIES', [

	'commitments/message/p-pit-studies/attendance/data' => [
		'absenceMotives' => [
			'health_absence' => ['medical'],
			'vacation_absence' => [],
			'necessity_absence' => [],
			'business_absence' => ['enterprise'],
			'other_absence' => ['unjustified', 'other'],
		],
	],
	
	'commitments/message/p-pit-studies/attendance' => [
		'name' => ['default' => 'Attestation de présence'],
		'route' => 'student/generateAttendance',
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
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_n_fn']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_street']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_extended']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_post_office_box']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_zip', 'addressee_adr_city']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_state']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_state', 'addressee_adr_country']],
					['type' => 'br'],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => 'Boulogne, le %s'], 'params' => ['current_date']],
					['type' => 'br'],
				],
			],
			'title' => [
				'class' => '',
				'paragraphs' => [
					['type' => 'h1', 'class' => 'text-center', 'label' => ['default' => 'ATTESTATION DE PRÉSENCE']],
				],
			],
			'body' => [
				'class' => 'body',
				'paragraphs' => [
					['type' => 'br'],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Convention : <strong>%s - %s %s %s</strong>'],
						'params' => ['property_7', 'n_title', 'n_first', 'n_last'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Entreprise : %s &mdash; Tuteur : %s'],
						'params' => ['property_20', 'property_11'],
					],
				],
			],
			'months' => [
				'class' => 'table',
				'entity' => 'month',
				'paragraphs' => [
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black;',
						'group_header' => ['default' => ''],
						'header' => ['default' => '<strong>Période</strong>'],
						'label' => ['default' => '%s'],
						'params' => ['month:period'],
						'sum' => ['label' => ['default' => '<strong>Total</strong>']],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black;',
						'group_header' => ['default' => '<strong>Heures de présence</strong>'],
						'header' => ['default' => '<strong>Heures en groupe</strong>'],
						'label' => ['default' => '%s'],
						'params' => ['month:group'],
						'sum' => ['label' => ['default' => '<strong>%s</strong>'], 'params' => ['sum:group']],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black;',
						'group_header' => ['default' => '<strong>Heures de présence</strong>'],
						'header' => ['default' => '<strong>Heures individuelles</strong>'],
						'label' => ['default' => '%s'],
						'params' => ['month:individual'],
						'sum' => ['label' => ['default' => '<strong>%s</strong>'], 'params' => ['sum:individual']],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black;',
						'group_header' => ['default' => '<strong>Heures de présence</strong>'],
						'header' => ['default' => '<strong>Total présence</strong>'],
						'label' => ['default' => '%s'],
						'params' => ['month:total_presence'],
						'sum' => ['label' => ['default' => '<strong>%s</strong>'], 'params' => ['sum:total_presence']],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black;',
						'group_header' => ['default' => '<strong>Heures d’absences</strong>'],
						'header' => ['default' => '<strong>Absences justifiées pour maladie</strong>'],
						'label' => ['default' => '%s'],
						'params' => ['month:health_absence'],
						'sum' => ['label' => ['default' => '<strong>%s</strong>'], 'params' => ['sum:health_absence']],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black;',
						'group_header' => ['default' => '<strong>Heures d’absences</strong>'],
						'header' => ['default' => '<strong>Congés payés</strong>'],
						'label' => ['default' => '%s'],
						'params' => ['month:vacation_absence'],
						'sum' => ['label' => ['default' => '<strong>%s</strong>'], 'params' => ['sum:vacation_absence']],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black;',
						'group_header' => ['default' => '<strong>Heures d’absences</strong>'],
						'header' => ['default' => '<strong>Cas de<br>force majeure</strong>'],
						'label' => ['default' => '%s'],
						'params' => ['month:necessity_absence'],
						'sum' => ['label' => ['default' => '<strong>%s</strong>'], 'params' => ['sum:necessity_absence']],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black;',
						'group_header' => ['default' => '<strong>Heures d’absences</strong>'],
						'header' => ['default' => '<strong>Demande<br>de<br>l’entreprise</strong>'],
						'label' => ['default' => '%s'],
						'params' => ['month:business_absence'],
						'sum' => ['label' => ['default' => '<strong>%s</strong>'], 'params' => ['sum:business_absence']],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black;',
						'group_header' => ['default' => '<strong>Heures d’absences</strong>'],
						'header' => ['default' => '<strong>Autres absences *</strong>'],
						'label' => ['default' => '%s'],
						'params' => ['month:other_absence'],
						'sum' => ['label' => ['default' => '<strong>%s</strong>'], 'params' => ['sum:other_absence']],
					],
					[
						'class' => 'text-justify',
						'style' => 'border: 1px solid black;',
						'group_header' => ['default' => '<strong>Heures d’absences</strong>'],
						'header' => ['default' => '<strong>Total absences</strong>'],
						'label' => ['default' => '%s'],
						'params' => ['month:total_absence'],
						'sum' => ['label' => ['default' => '<strong>%s</strong>'], 'params' => ['sum:total_absence']],
					],
				],
			],
			'body_2' => [
				'class' => 'body',
				'paragraphs' => [
					['type' => 'br'],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => '*	La fourniture d’un justificatif pourra entraîner la requalification de l’absence sur les prochaines attestations de présence.'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Les états de présence signés par %s %s %s sont tenus à la disposition de l’OPCA.'],
						'params' => ['n_title', 'n_first', 'n_last'],
					],
				],
			],
			'footer' => [
				'class' => 'table',
				'paragraphs' => [
					[
						'class' => 'text-justify',
						'style' => 'font-size: 12',
						'header' => ['default' => '<strong>L’établissement</strong>'],
						'label' => ['default' => '&nbsp;'],
					],
					[
						'class' => 'text-justify',
						'style' => 'font-size: 12',
						'header' => ['default' => '<strong>Signature de l’élève</strong>'],
						'label' => ['default' => '&nbsp;'],
					],
				],
			],
		],
	],

	'commitments/message/p-pit-studies/absence' => [
		'name' => ['default' => 'Relevé d’absences'],
		'route' => 'student/absenceV2',
	],
	
	'commitments/message/p-pit-studies' => [
		'attendance',
		'absence',
	],
]);
