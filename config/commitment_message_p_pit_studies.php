<?php
/**
 * PpitCore V1.0 (https://github.com/p-pit/PpitCore)
 *
 * @link      https://github.com/p-pit/PpitCore
 * @copyright Copyright (c) 2016 Bruno Lartillot
 * @license   https://github.com/p-pit/PpitCore/blob/master/license.txt GNU-GPL license
 */

define('COMMITMENT_MESSAGE_P_PIT_STUDIES', [

	'commitment/message/p-pit-studies/attestation_fin_formation' => [
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
				[
					'type' => 'p',
					'class' => 'text-center',
					'label' => ['default' => 'LA DIRECTION STUDENCY'],
					'params' => [],
				],
			],
		],
	],
	
	'commitment/message/p-pit-studies' => [
		'attestation_fin_formation',
	],
]);
