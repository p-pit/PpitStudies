<?php
/**
 * PpitCore V1.0 (https://github.com/p-pit/PpitCore)
 *
 * @link      https://github.com/p-pit/PpitCore
 * @copyright Copyright (c) 2016 Bruno Lartillot
 * @license   https://github.com/p-pit/PpitCore/blob/master/license.txt GNU-GPL license
 */

define('COMMITMENT_MESSAGE_P_PIT_STUDIES', [

	'commitment/message/p-pit-studies/certificat_scolarite' => [
		'name' => ['default' => 'Certificat de scolarité'],
		'route' => 'commitment/generateMessage',
		'stamp' => true,
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
					['type' => 'h1', 'class' => 'text-center', 'label' => ['default' => 'CERTIFICAT DE SCOLARITÉ']],
				],
			],
			'body' => [
				'class' => 'body',
				'paragraphs' => [
					['type' => 'br'], ['type' => 'br'], ['type' => 'br'],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Je soussigné(e) le Directeur pédagogique de l’établissement certifie que :'],
//						'label' => ['default' => 'Je soussigné(e), %s, Directeur pédagogique de l’établissement certifie que :'],
//						'params' => ['study_manager_name'],
					],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => ['default' => '<strong>%s %s %s</strong>'],
						'params' => ['n_title', 'n_first', 'n_last'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Né(e) le %s'],
						'params' => ['birth_date'],
						'fillEmptyValue' => true
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Demeurant à l’adresse :'],
					],
					['type' => 'p', 'class' => 'text-justify', 'label' => ['default' => '%s'], 'params' => ['addressee_invoice_name']],
					['type' => 'p', 'class' => 'text-justify', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_street'], 'fillEmptyValue' => true],
					['type' => 'p', 'class' => 'text-justify', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_extended']],
					['type' => 'p', 'class' => 'text-justify', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_post_office_box']],
					['type' => 'p', 'class' => 'text-justify', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_zip', 'addressee_adr_city']],
					['type' => 'p', 'class' => 'text-justify', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_state']],
					['type' => 'p', 'class' => 'text-justify', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_state', 'addressee_adr_country']],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Est inscrit(e) en :'],
					],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => ['default' => '<strong>%s</strong>'],
						'params' => ['property_1'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'au sein de notre établissement.'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Pour la période du <strong>%s</strong> au <strong>%s</strong>.'],
						'params' => ['property_6', 'property_7'],
						'fillEmptyValue' => true
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Pour faire valoir ce que de droit.'],
					],
				],
			],
			'footer' => [
				'class' => 'footer',
				'paragraphs' => [
					[
						'type' => 'p',
						'label' => ['default' => 'Fait à Boulogne Billancourt, le %s'],
						'params' => ['current_date'],
					],
					['type' => 'br'], ['type' => 'br'], ['type' => 'br'], ['type' => 'br'],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => ['default' => 'Le Directeur Pédagogique'],
						'params' => [],
					],
				],
			],
		],
	],
	
	'commitment/message/p-pit-studies/convocation_formation' => [
		'name' => ['default' => 'Convocation à la formation'],
		'route' => 'commitment/generateMessage',
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
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_invoice_name']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_street']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_extended']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_post_office_box']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_zip', 'addressee_adr_city']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_state']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_state', 'addressee_adr_country']],
					['type' => 'br'], ['type' => 'br'], ['type' => 'br'],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => 'Boulogne, le %s'], 'params' => ['current_date']],
				],
			],
			'body' => [
				'class' => 'body',
				'paragraphs' => [
					['type' => 'br'], ['type' => 'br'], ['type' => 'br'],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => '%s,'],
						'params' => ['n_title'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Vous êtes invité(e) à vous présenter le %s à 9h30 pour suivre l’action de formation :'],
						'params' => ['property_6'],
					],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => ['default' => '<strong>%s</strong>'],
						'params' => ['property_5'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Cette formation, d’une durée de %s heures se déroulera à l’adresse suivante : <strong>61 bis rue des Peupliers, 92100 Boulogne-Billancourt</strong>'],
						'params' => ['property_9'],
					],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => ['default' => ''],
						'params' => [],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Dans l’attente de vous recevoir, nous vous prions d’agréer, %s, nos salutations distinguées.'],
						'params' => ['n_title'],
					],
				],
			],
			'footer' => [
				'class' => 'footer',
				'paragraphs' => [
					['type' => 'br'], ['type' => 'br'], ['type' => 'br'], ['type' => 'br'],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => ['default' => 'LA DIRECTION STUDENCY'],
						'params' => [],
					],
				],
			],
		],
	],
	
	'commitment/message/p-pit-studies/attestation_entree_formation' => [
		'name' => ['default' => 'Attestation d’entrée en formation'],
		'route' => 'commitment/generateMessage',
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
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_invoice_name']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_street']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_extended']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_post_office_box']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_zip', 'addressee_adr_city']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_state']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_state', 'addressee_adr_country']],
					['type' => 'br'], ['type' => 'br'], ['type' => 'br'],
				],
			],
			'title' => [
				'class' => 'box-title',
				'paragraphs' => [
					['type' => 'h1', 'class' => 'text-center', 'label' => ['default' => 'ATTESTATION D’ENTRÉE EN FORMATION']],
				],
			],
			'body' => [
				'class' => 'body',
				'paragraphs' => [
					['type' => 'br'], ['type' => 'br'], ['type' => 'br'],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Je soussigné(e) le Directeur du Centre de Formation atteste que :'],
						'params' => ['study_manager_name'],
					],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => ['default' => '<strong>%s %s %s</strong>'],
						'params' => ['n_title', 'n_first', 'n_last'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'a débuté la formation intitulée :'],
					],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => ['default' => '<strong>%s</strong>'],
						'params' => ['property_5'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'le <strong>%s</strong>. Cette formation se terminera le %s. Le détail des heures est le suivant :'],
						'params' => ['property_6', 'property_7'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Heures en centre de formation : <strong>%s</strong> heures prévues.'],
						'params' => ['property_9'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => 'Les heures en centre de formation sont dispensées au lieu : <strong>61 bis rue des Peupliers, 92100 Boulogne-Billancourt</strong>'],
					],
				],
			],
			'footer' => [
				'class' => 'footer',
				'paragraphs' => [
					[
						'type' => 'p',
						'label' => ['default' => 'Fait à LE PERREUX SUR MARNE, le %s'],
						'params' => ['current_date'],
					],
					['type' => 'br'], ['type' => 'br'], ['type' => 'br'], ['type' => 'br'],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => ['default' => 'LA DIRECTION STUDENCY'],
						'params' => [],
					],
				],
			],
		],
	],

	'commitment/message/p-pit-studies/attestation_fin_formation' => [
		'name' => ['default' => 'Attestation de fin de formation'],
		'route' => 'commitment/generateMessage',
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
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_invoice_name']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_street']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_extended']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_post_office_box']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_zip', 'addressee_adr_city']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s'], 'params' => ['addressee_adr_state']],
					['type' => 'p', 'class' => 'addressee', 'label' => ['default' => '%s %s'], 'params' => ['addressee_adr_state', 'addressee_adr_country']],
					['type' => 'br'], ['type' => 'br'], ['type' => 'br'],
				],
			],
			'title' => [
				'class' => 'box-title',
				'paragraphs' => [
					['type' => 'h1', 'class' => 'text-center', 'label' => ['default' => 'ATTESTATION DE FIN DE FORMATION']],
					['type' => 'h3', 'class' => 'text-center', 'label' => ['default' => 'Art. L.6353-1 du Code du travail']],
				],
			],
			'body' => [
				'class' => 'body',
				'paragraphs' => [
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => '<strong>Intitulé de la formation : </strong>%s'],
						'params' => ['property_5'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => '<strong>Objectifs : Maîtriser les connaissances techniques et les compétences professionnelles associées aux épreuves de la formation</strong>'],
					],
					[
						'type' => 'p',
						'class' => 'text-justify',
						'label' => ['default' => '<strong>Lieu de formation : </strong>61 bis rue des Peupliers, 92100 Boulogne-Billancourt'],
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
						'label' => ['default' => '&nbsp;&nbsp;&nbsp;&nbsp;<strong>Examen de la formation %s : ADMIS(E) ou REFUSÉ(E)</strong>'],
						'params' => ['property_5'],
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
						'label' => ['default' => 'Délivrée par le Directeur du Centre de Formation,'],
					],
					[
						'type' => 'p',
						'label' => ['default' => 'à %s %s %s'],
						'params' => ['n_title', 'n_first', 'n_last'],
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
					['type' => 'br'], ['type' => 'br'], ['type' => 'br'], ['type' => 'br'],
					[
						'type' => 'p',
						'class' => 'text-center',
						'label' => ['default' => 'LA DIRECTION STUDENCY'],
						'params' => [],
					],
				],
			],
		],
	],

	'commitment/message/p-pit-studies/attendance' => [
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
	
	'commitment/message/p-pit-studies' => [
		'certificat_scolarite',
		'convocation_formation',
		'attestation_entree_formation',
		'attestation_fin_formation',
		'attendance',
	],
]);
