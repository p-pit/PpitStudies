<?php
/**
 * PpitCore V1.0 (https://github.com/p-pit/PpitCore)
 *
 * @link      https://github.com/p-pit/PpitCore
 * @copyright Copyright (c) 2016 Bruno Lartillot
 * @license   https://github.com/p-pit/PpitCore/blob/master/license.txt GNU-GPL license
 */

define('CORE_ACCOUNT_MESSAGE_P_PIT_STUDIES', [

	'commitments/message/p-pit-studies/attendance' => [
		'name' => ['default' => 'Attestation de présence'],
		'route' => 'student/generateAttendance',
		'style' => ['default' => '
			<style>
	    	table {
	    		border-collapse: collapse;
			}
			.table {
	    		font-size: 7;
				padding-top: 10;
				padding-right: 5;
				padding-bottom: 10;
				padding-left: 5;
				border: 1px solid black;
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
				'class' => 'box-title',
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
						'params' => ['property_1', 'n_title', 'n_first', 'n_last'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Tuteur :'],
					],
				],
			],
			'months' => [
				'class' => 'table',
				'entity' => 'month',
				'paragraphs' => [
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Période</strong>'],
						'label' => ['default' => '%s'],
						'params' => ['month:period'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Heures en groupe</strong>', 'style' => 'background-color: green'],
						'label' => ['default' => '%s'],
						'params' => ['month:group'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Heures individuelles</strong>', 'style' => 'background-color: green'],
						'label' => ['default' => '%s'],
						'params' => ['month:individual'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Total présence</strong>', 'style' => 'background-color: green'],
						'label' => ['default' => '%s'],
						'params' => ['month:total_presence'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Absences justifiées pour maladie</strong>', 'style' => 'background-color: red'],
						'label' => ['default' => '%s'],
						'params' => ['month:health_absence'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Congés payés</strong>', 'style' => 'background-color: red'],
						'label' => ['default' => '%s'],
						'params' => ['month:vacation_absence'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Cas de<br>force majeure</strong>', 'style' => 'background-color: red'],
						'label' => ['default' => '%s'],
						'params' => ['month:necessity_absence'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Demande<br>de<br>l’entreprise</strong>', 'style' => 'background-color: red'],
						'label' => ['default' => '%s'],
						'params' => ['month:business_absence'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Autres absences *</strong>', 'style' => 'background-color: red'],
						'label' => ['default' => '%s'],
						'params' => ['month:other_absence'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Total absences</strong>', 'style' => 'background-color: red'],
						'label' => ['default' => '%s'],
						'params' => ['month:total_absence'],
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
						'label' => ['default' => 'Les états de présence signés par %s sont tenus à la disposition de l’OPCA.'],
						'params' => ['study_manager_name'],
					],
				],
			],
			'footer' => [
				'class' => 'table',
				'paragraphs' => [
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>L’établissement</strong>'],
						'label' => ['default' => '&nbsp;'],
					],
					[
						'class' => 'text-justify',
						'header' => ['default' => '<strong>Signature de l’élève</strong>'],
						'label' => ['default' => '&nbsp;'],
					],
				],
			],
		],
	],
	
	'commitments/message/p-pit-studies' => [
		'attendance',
	],
]);
