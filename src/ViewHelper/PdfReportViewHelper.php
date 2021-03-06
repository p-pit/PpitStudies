<?php
namespace PpitStudies\ViewHelper;

use Zend\View\Model\ViewModel;
use PpitCommitment\Model\Commitment;
use PpitCommitment\Model\Term;
use PpitCore\Model\Account;
use PpitCore\Model\Context;
use PpitCore\Model\Place;
use PpitCore\Model\Vcard;

require_once('vendor/TCPDF-master/tcpdf.php');

class PdfReportViewHelper
{	
    public static function render($category, $pdf, $place, $school_year, $school_period, $date, $account, $addressee, $averages, $notes, $absenceCount, $cumulativeAbsence, $latenessCount, $cumulativeLateness, $absences, $latenesss, $classSize = null, $absLates = null, $mock = null)
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
		$translator = $context->getServiceManager()->get(\Zend\I18n\Translator\TranslatorInterface::class);
    	
    	// create new PDF document
    	$pdf->footer = ($place->legal_footer) ? $place->legal_footer : $context->getConfig('headerParams')['footer']['value'];
    	
    	// set document information
    	$pdf->SetCreator(PDF_CREATOR);
    	$pdf->SetAuthor('P-PIT');
    	$pdf->SetTitle('School report');
    	$pdf->SetSubject('School report');
    	$pdf->SetKeywords('TCPDF, PDF, school, report');
    	
    	// set default header data
		if ($place && $place->banner_src) $pdf->SetHeaderData($place->banner_src, ($place->banner_width) ? $place->banner_width : $context->getConfig('corePlace')['properties']['banner_width']['maxValue']);
		else $pdf->SetHeaderData('logos/'.$context->getInstance()->caption.'/'.$context->getConfig('headerParams')['advert'], $context->getConfig('headerParams')['advert-width']);
    	// set header and footer fonts
    	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
    	// set default monospaced font
    	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    	
    	// set margins
    	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    	 
    	// set auto page breaks
    	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    	
    	// set image scale factor
    	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    	
    	// set some language-dependent strings (optional)
    	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    		require_once(dirname(__FILE__).'/lang/eng.php');
    		$pdf->setLanguageArray($l);
    	}
    	
    	// ---------------------------------------------------------
    	
    	/*
    	 NOTES:
    	 - To create self-signed signature: openssl req -x509 -nodes -days 365000 -newkey rsa:1024 -keyout tcpdf.crt -out tcpdf.crt
    	 - To export crt to p12: openssl pkcs12 -export -in tcpdf.crt -out tcpdf.p12
    	 - To convert pfx certificate to pem: openssl pkcs12 -in tcpdf.pfx -out tcpdf.crt -nodes
    	 */
    	
    	// set certificate file
    	$certificate = 'file://vendor/TCPDF-master/examples/data/cert/tcpdf.crt';
    	
    	// set additional information
    	$info = array(
    			'Name' => 'School report',
    			'Location' => 'Office',
    			'Reason' => 'School report',
    			'ContactInfo' => 'https://www.p-pit.fr',
    	);
    	
    	// set document signature
//    	$pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);
    	
    	// set font
    	$pdf->SetFont('helvetica', '', 12);
    	
    	// add a page
    	$pdf->AddPage();
    	 
    	// Report header
    	$pdf->MultiCell(100, 5, '', 0, 'L', 0, 0, '', '', true);
    	$pdf->SetTextColor(0);
    	$pdf->SetFont('', '', 10);
    	
    	$header = "\n";
    	if ($addressee->n_fn) $header .= $addressee->n_fn."\n";
    	if ($addressee->adr_street) $header .= $addressee->adr_street."\n";
    	if ($addressee->adr_extended) $header .= $addressee->adr_extended."\n";
    	if ($addressee->adr_post_office_box) $header .= $addressee->adr_post_office_box."\n";
    	if ($addressee->adr_zip || $addressee->adr_city) {
    		if ($addressee->adr_zip) $header .= $addressee->adr_zip." ";
	    	if ($addressee->adr_city) $header .= $addressee->adr_city;
    		$header .= "\n";
    	}
    	if ($addressee->adr_state) $header .= $addressee->adr_state."\n";
    	if ($addressee->adr_country) $header .= $addressee->adr_country."\n";
    	$pdf->MultiCell(80, 5, $header, 0, 'L', 0, 1, '', '', true);
    	$pdf->Ln(4);
    	 
    	// Title
    	$pdf->SetFont('', '', 12);
		$school_periods = ($place) ? $place->getConfig('school_periods') : null;
		if ($school_periods && array_key_exists('labels', $school_periods) && array_key_exists($school_period, $school_periods['labels'])) $school_period_label = $school_periods['labels'][$school_period];
    	elseif (array_key_exists($school_period, $context->getConfig('student/property/school_period')['modalities'])) $school_period_label = $context->getConfig('student/property/school_period')['modalities'][$school_period];
    	else $school_period_label = '';
    	if ($category == 'report') {
//	    	$text = '<div style="text-align: center"><strong>Bulletin scolaire</strong></div><div style="text-align: center"><strong>Période du '.$context->decodeDate($context->getConfig('currentPeriodStart')).' au '.$context->decodeDate($context->getConfig('currentPeriodEnd')).'</strong></div>';
	    	$text = '<div style="text-align: center"><strong>Bulletin scolaire';
	    	if ($date) $text .= ' au '.$context->decodeDate($date);
	    	$text .= '<br>Année '.$context->localize($context->getConfig('student/property/school_year')['modalities'][$school_year]).' - '.$context->localize($school_period_label).'</strong></div>';
    	}
		elseif ($category == 'exam') {
    		$text = '<div style="text-align: center"><strong>Année '.$context->localize($context->getConfig('student/property/school_year')['modalities'][$school_year]).' - '.$context->localize($context->getConfig('student/property/evaluationCategory')['modalities'][$school_period]).'</strong></div>';
    	}
    	elseif ($category == 'absence') {
    		$text = '<div style="text-align: center"><strong>Relevé d\'absences au '.$context->decodeDate(date('Y-m-d'));
	    	if ($date) $text .= ' au '.$context->decodeDate($date);
    		$text .= '<br>Année '.$context->localize($context->getConfig('student/property/school_year')['modalities'][$school_year]);
    		if ($school_period) $text .= ' - '.$context->localize($school_period_label);
    		$text .= '</strong></div>';
    	}
    	else {
    		$text = '<div style="text-align: center"><strong>'.(($mock) ? 'Epreuve blanche' : 'Relevé de notes').' au '.$context->decodeDate(date('Y-m-d'));
	    	if ($date) $text .= ' au '.$context->decodeDate($date);
    		$text .= '<br>Année '.$context->localize($context->getConfig('student/property/school_year')['modalities'][$school_year]).' - '.$context->localize($school_period_label).'</strong></div>';
    	}
    	$pdf->writeHTML($text, true, 0, true, 0);
    	$pdf->Ln(4);

    	// Report references
		$pdf->SetFillColor(255, 255, 255);
//    	$pdf->SetTextColor(255);
    	$pdf->SetDrawColor(255, 255, 255);
//    	$pdf->SetDrawColor(128, 0, 0);
    	$pdf->SetLineWidth(0.2);
    	$pdf->SetFont('', '', 9);

    	$groupConfig = [];
    	foreach (Account::getList('group', [], '+name', null) as $group) $groupConfig[$group->id] = $group->name;
    	 
    	if (!$averages && !$notes) $class = '';
		else $class = current(($averages) ? $averages : $notes)->class;
		foreach($context->getConfig('student/report/description') as $line) {
    		$arguments = array();
    		foreach($line['params'] as $propertyId) {
    			if ($propertyId == 'date') $arguments[] = $context->decodeDate(date('Y-m-d'));
    			elseif ($propertyId == 'name') $arguments[] = $addressee->n_fn;
				elseif ($propertyId == 'class_size') $arguments[] = $classSize;
    			else {
					$property = $context->getConfig('core_account/p-pit-studies/property/'.$propertyId);
					if (!$property) $property = $context->getConfig('core_account/generic/property/'.$propertyId);
					if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
					if ($propertyId == 'name') $arguments[] = $account->name;
    				elseif ($propertyId == 'groups') {
						$groups = [];
						if ($account->groups) foreach (explode(',', $account->groups) as $group_id) {
							$groups[] = $groupConfig[$group_id];
						}
						$arguments[] = implode(',', $groups);
					}
					elseif ($propertyId == 'property_7') {
						if ($class) $arguments[] = (array_key_exists($class, $property['modalities'])) ? $context->localize($property['modalities'][$class]) : '';
					}
					elseif ($property['type'] == 'date') $arguments[] = $context->decodeDate($account->properties[$propertyId]);
	    			elseif ($property['type'] == 'number') $arguments[] = $context->formatFloat($account->properties[$propertyId], 2);
	    			elseif (in_array($property['type'], ['select', 'multiselect']) && array_key_exists($account->properties[$propertyId], $property['modalities'])) $arguments[] = $context->localize($property['modalities'][$account->properties[$propertyId]]);
	    			else $arguments[] = $account->properties[$propertyId];
    			}
    		}
    		$value = vsprintf($context->localize($line['right']), $arguments);
    		if ($value) {
	    		$pdf->MultiCell(30, 5, '<strong>'.$context->localize($line['left']).'</strong>', 1, 'L', 1, 0, '', '', true, 0, true);
	    		$pdf->MultiCell(5, 5, ':', 1, 'L', 1, 0, '', '', true);
	    		$pdf->MultiCell(145, 5, $value, 1, 'L', 0, 1, '' ,'', true);
    		}
    	}

	    $pdf->SetFont('', '', 8);
	    $pdf->Ln();
	    $pdf->SetDrawColor(0, 0, 0);

	    if (in_array($category, ['report', 'exam'])) {
	    	$classDefinition = $context->getConfig('student/property/class')['modalities'];
	    	if (array_key_exists($account->property_7, $classDefinition) && array_key_exists('subjects', $classDefinition[$account->property_7])) {
	    		$orderedSubjects = $classDefinition[$account->property_7]['subjects'];
	    	}
	    	else $orderedSubjects = [];
	    	$text = PdfReportTableViewHelper::render($averages, $category, $orderedSubjects);
	    	$pdf->writeHTML($text, true, 0, true, 0);
	
			$pdf->SetDrawColor(255, 255, 255);
	
			if ($category == 'report' && !in_array($place->id, [28, 36, 37])) {
				// Absences
				$pdf->MultiCell(16, 5, '<strong>'.'Absences'.'</strong>', 1, 'L', 1, 0, '', '', true, 0, true);
				$pdf->MultiCell(5, 5, ':', 1, 'L', 1, 0, '', '', true);
				$pdf->MultiCell(10, 5, $absenceCount, 1, 'L', 1, 0, '' ,'', true);
		
				$pdf->MultiCell(24, 5, '<strong>'.'Durée cumulée'.'</strong>', 1, 'L', 1, 0, '', '', true, 0, true);
				$pdf->MultiCell(5, 5, ':', 1, 'L', 1, 0, '', '', true);
				$pdf->MultiCell(30, 5, (((int)($cumulativeAbsence/60)) ? ((int)($cumulativeAbsence/60)).'h' : '').(($cumulativeAbsence%60) ? sprintf('%02u', $cumulativeAbsence%60).'mn' : ''), 1, 'L', 1, 0, '' ,'', true);
				
				// Lateness
				$pdf->MultiCell(16, 5, '<strong>'.'Retards'.'</strong>', 1, 'L', 1, 0, '', '', true, 0, true);
				$pdf->MultiCell(5, 5, ':', 1, 'L', 1, 0, '', '', true);
				$pdf->MultiCell(10, 5, $latenessCount, 1, 'L', 1, 0, '' ,'', true);
				
				$pdf->MultiCell(24, 5, '<strong>'.'Durée cumulée'.'</strong>', 1, 'L', 1, 0, '', '', true, 0, true);
				$pdf->MultiCell(5, 5, ':', 1, 'L', 1, 0, '', '', true);
				$pdf->MultiCell(30, 5, (((int)($cumulativeLateness/60)) ? ((int)($cumulativeLateness/60)).'h' : '').(($cumulativeLateness%60) ? sprintf('%02u', $cumulativeLateness%60).'mn' : ''), 1, 'L', 0, 1, '' ,'', true);
			}

			$globalEvaluation = '';
			foreach ($averages as $evaluation) if ($evaluation->subject == 'global') $globalEvaluation = $evaluation;
			
			$text = $context->getConfig('student/report/pdfDetailStyle');
			$mention = '';
			if ($globalEvaluation) {
				if ($globalEvaluation->evaluation) {
					$first = true;
					foreach ($context->getConfig('student/property/reportMention')['modalities'] as $modalityId => $modality) {
						if (!$first) $mention .= '<br>';
						if ($globalEvaluation->evaluation == $modalityId) $mention .= '<strong>';
						else $mention .= '<span style="font-style: italic; color: lightgray">';
						$mention .= $context->localize($context->getConfig('student/property/reportMention')['modalities'][$modalityId]);
						if ($globalEvaluation->evaluation == $modalityId) $mention .= '</strong>';
						else $mention .= '</span>';
						$first = false;
					}
				}
			}
			if ($category == 'report') {
				$text .= sprintf(
					$context->getConfig('student/report/signatureFrame')['html'],
					'<em>'.$translator->translate('Staff meeting opinion', 'ppit-studies', $context->getLocale()).'</em><br>'.
					(($globalEvaluation) ? $globalEvaluation->assessment : '<br><br><br><br>').
					'<br><br><br><br><br><br><br><br>'.
					(($globalEvaluation) ? '<strong>'.$translator->translate('Head of training', 'ppit-studies', $context->getLocale()).' : </strong>'.$globalEvaluation->n_fn : ''),
					$mention
				);
			}
			elseif ($category == 'exam') {
	    		$pdf->SetFont('', '', 10);
				$text .= sprintf(
					$context->getConfig('student/report/withoutMentionFrame')['html'],
					'<em>'.$translator->translate('Opinion of the jury', 'ppit-studies', $context->getLocale()).'</em><br>'.
					(($globalEvaluation) ? $globalEvaluation->assessment : '<br><br><br><br>').
					'<br><br><br><br><br><br><br><br>'
				);				
			}
			$pdf->writeHTML($text, true, 0, true, 0);
	    }
	    elseif ($category == 'note') {

	    	$pdf->SetFont('', '', 8);
			$text = PdfEvaluationTableViewHelper::render($notes, $category);
/*			$text .= sprintf(
					'<div><br></div>'.$context->getConfig('student/report/evaluationSignatureFrame')['html'],
					'<br><br><br><br><br><br><br><br>',
					''
			);*/
			$pdf->writeHTML($text, true, 0, true, 0);
	    }
    	elseif ($category == 'absence') {

	    	$pdf->SetFont('', '', 8);
			$text = PdfAbsenceTableViewHelper::render($absLates);
			$pdf->writeHTML($text, true, 0, true, 0);
	    }
	    $pdf->SetFont('', '', 8);
	    $pdf->writeHTML('<strong>'.$translator->translate('Report to keep carefully. No duplicate will be provided', 'ppit-studies', $context->getLocale()).'</strong>', true, 0, true, 0);
	    return $pdf;
    }
}
