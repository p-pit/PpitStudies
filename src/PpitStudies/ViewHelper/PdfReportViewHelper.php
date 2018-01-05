<?php
namespace PpitStudies\ViewHelper;

use Zend\View\Model\ViewModel;
use PpitCommitment\Model\Commitment;
use PpitCommitment\Model\Term;
use PpitCore\Model\Context;
use PpitCore\Model\Place;
use PpitCore\Model\Vcard;
use PpitMasterData\Model\Product;
use PpitMasterData\Model\ProductOption;

require_once('vendor/TCPDF-master/tcpdf.php');

class PdfReportViewHelper
{	
    public static function render($category, $pdf, $place, $school_year, $school_period, $date, $account, $addressee, $averages, $notes, $absenceCount, $cumulativeAbsence, $latenessCount, $cumulativeLateness, $classSize = null)
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
		$translator = $context->getServiceManager()->get('translator');
    	$reportSpecs = $context->getConfig('student/report');
    	
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
    	if ($category == 'report') {
//	    	$text = '<div style="text-align: center"><strong>Bulletin scolaire</strong></div><div style="text-align: center"><strong>Période du '.$context->decodeDate($context->getConfig('currentPeriodStart')).' au '.$context->decodeDate($context->getConfig('currentPeriodEnd')).'</strong></div>';
	    	$text = '<div style="text-align: center"><strong>Bulletin scolaire';
	    	if ($date) $text .= ' au '.$context->decodeDate($date);
	    	$text .= '<br>Année '.$context->getConfig('student/property/school_year')['modalities'][$school_year][$context->getLocale()].' - '.$context->getConfig('student/property/school_period')['modalities'][$school_period][$context->getLocale()].'</strong></div>';
    	}
    	else {
    		$text = '<div style="text-align: center"><strong>Relevé de notes au '.$context->decodeDate(date('Y-m-d'));
	    	if ($date) $text .= ' au '.$context->decodeDate($date);
    		$text .= '<br>Année '.$context->getConfig('student/property/school_year')['modalities'][$school_year][$context->getLocale()].' - '.$context->getConfig('student/property/school_period')['modalities'][$school_period][$context->getLocale()].'</strong></div>';
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
    	foreach($reportSpecs['description'] as $line) {
    		$arguments = array();
    		foreach($line['params'] as $propertyId) {
    			if ($propertyId == 'date') $arguments[] = $context->decodeDate(date('Y-m-d'));
    			elseif ($propertyId == 'name') $arguments[] = $addressee->n_fn;
				elseif ($propertyId == 'class_size') $arguments[] = $classSize;
    			else {
					$property = $context->getConfig('core_account/p-pit-studies')['properties'][$propertyId];
					if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
					if ($propertyId == 'name') $arguments[] = $account->name;
					elseif ($property['type'] == 'date') $arguments[] = $context->decodeDate($account->properties[$propertyId]);
	    			elseif ($property['type'] == 'number') $arguments[] = $context->formatFloat($account->properties[$propertyId], 2);
	    			elseif ($property['type'] == 'select' && array_key_exists($account->properties[$propertyId], $property['modalities'])) $arguments[] = $property['modalities'][$account->properties[$propertyId]][$context->getLocale()];
	    			else $arguments[] = $account->properties[$propertyId];
    			}
    		}
    		$value = vsprintf($line['right'][$context->getLocale()], $arguments);
    		if ($value) {
	    		$pdf->MultiCell(30, 5, '<strong>'.$line['left'][$context->getLocale()].'</strong>', 1, 'L', 1, 0, '', '', true, 0, true);
	    		$pdf->MultiCell(5, 5, ':', 1, 'L', 1, 0, '', '', true);
	    		$pdf->MultiCell(145, 5, $value, 1, 'L', 0, 1, '' ,'', true);
    		}
    	}

	    $pdf->SetFont('', '', 8);
	    $pdf->Ln();
	    $pdf->SetDrawColor(0, 0, 0);

	    if ($category == 'report') {
	    	$text = PdfReportTableViewHelper::render($averages, $category);
	    	$pdf->writeHTML($text, true, 0, true, 0);
	
			$pdf->SetDrawColor(255, 255, 255);
	
			if (!in_array($place->id, [28, 36, 37])) {
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
			
			$text = $context->getConfig('student/report')['pdfDetailStyle'];
			$mention = '';
			if ($globalEvaluation) {
				if ($globalEvaluation->value) {
					$first = true;
					foreach ($context->getConfig('student/property/reportMention')['modalities'] as $modalityId => $modality) {
						if (!$first) $mention .= '<br>';
						if ($globalEvaluation->value == $modalityId) $mention .= '<strong>';
						else $mention .= '<span style="font-style: italic; color: lightgray">';
						$mention .= $context->getConfig('student/property/reportMention')['modalities'][$modalityId][$context->getLocale()];
						if ($globalEvaluation->value == $modalityId) $mention .= '</strong>';
						else $mention .= '</span>';
						$first = false;
					}
				}
			}
			$text .= sprintf(
						$context->getConfig('student/report')['signatureFrame']['html'],
						'<em>'.$translator->translate('Staff meeting opinion', 'ppit-studies', $context->getLocale()).'</em><br>'.
						(($globalEvaluation) ? $globalEvaluation->assessment : '<br><br><br><br>').
						'<br><br><br><br>'.
						(($globalEvaluation) ? '<strong>'.$translator->translate('Main teacher', 'ppit-studies', $context->getLocale()).' : </strong>'.$globalEvaluation->n_fn : ''),
						$mention
					);
			$pdf->writeHTML($text, true, 0, true, 0);
			$pdf->writeHTML('<strong>'.$translator->translate('Report to keep carefully. No duplicate will be provided', 'ppit-studies', $context->getLocale()).'</strong>'.
						'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
						'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
						'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
						'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
						'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
						'<em>P-Pit Studies</em> (www.p-pit.fr)'
						, true, 0, true, 0);
/*		
	    	$pdf->SetFont('', '', 12);
			$pdf->Ln(5);
			$text = '<div style="text-align: center"><strong>Période : '.$context->getConfig('student/property/school_year')['modalities'][$school_year][$context->getLocale()].' - '.$context->getConfig('student/property/school_period')['modalities'][$school_period][$context->getLocale()].' Relevé de notes</strong></div>';
			$pdf->writeHTML($text, true, 0, true, 0);
			$pdf->Ln(10);*/
	    }
	    else {

	    	$pdf->SetFont('', '', 8);
			$text = PdfEvaluationTableViewHelper::render($notes, $category);
/*			$text .= sprintf(
					'<div><br></div>'.$context->getConfig('student/report')['evaluationSignatureFrame']['html'],
					'<br><br><br><br><br><br><br><br>',
					''
			);*/
			$pdf->writeHTML($text, true, 0, true, 0);
			$pdf->writeHTML('<strong>'.$translator->translate('Relevé à conserver préciseusement. Aucun duplicata ne sera délivré', 'ppit-studies', $context->getLocale()).'</strong>'.
					'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
					'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
					'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
					'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
					'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
					'<em>P-Pit Studies</em> (www.p-pit.fr)'
					, true, 0, true, 0);
	    	// Close and output PDF document
	    	// This method has several options, check the source code documentation for more information.
	    	return $pdf;
	    }
    }
}
