<?php
namespace PpitStudies\ViewHelper;

use Zend\View\Model\ViewModel;
use PpitCommitment\Model\Account;
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
	public static function mapArgs($headerDef, $params, $locale)
	{
		$arguments = array($headerDef['html']);
		foreach ($params as $param) if (is_array($param)) $arguments[] = $param[$locale];
		else $arguments[] = $param;
		return call_user_func_array('sprintf', $arguments);
	}

    public static function render($pdf, $place, $account, $addressee, $period)
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
		if ($place && $place->logo_src) $pdf->SetHeaderData($place->logo_src, $context->getConfig('headerParams')['advert-width']);
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
    	 
    	// Invoice header
    	$pdf->MultiCell(100, 5, '', 0, 'L', 0, 0, '', '', true);
    	$pdf->SetTextColor(0);
    	$pdf->SetFont('', '', 12);
    	
    	$header = "\n"."\n";
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
    	$pdf->Ln(10);

    	// Title
    	$text = '<div style="text-align: center"><strong>Bulletin scolaire'.'</strong></div><div style="text-align: center"><strong>Période du '.$context->decodeDate($context->getConfig('currentPeriodStart')).' au '.$context->decodeDate(date('Y-m-d')).'</strong></div>';
    	$pdf->writeHTML($text, true, 0, true, 0);
    	$pdf->Ln(10);

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
    			else {
					$property = $context->getConfig('commitmentAccount/p-pit-studies')['properties'][$propertyId];
					if ($property['type'] == 'repository') $property = $context->getConfig($property['definition']);
					if ($propertyId == 'customer_name') $arguments[] = $account->customer_name;
	    			elseif ($property['type'] == 'date') $arguments[] = $context->decodeDate($account->properties[$propertyId]);
	    			elseif ($property['type'] == 'number') $arguments[] = $context->formatFloat($account->properties[$propertyId], 2);
//	    			elseif ($property['type'] == 'select' && array_key_exists($account->properties[$propertyId], $property['modalities'])) $arguments[] = $property['modalities'][$account->properties[$propertyId]][$context->getLocale()];
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

	    	$text = $context->getConfig('student/report')['pdfDetailStyle'];
	    	
			$rows = '';
	    	$color = 0;
	    	foreach ($period as $evaluation) {

	    		$distribution = array();
	    		foreach ($evaluation->distribution as $category => $value) {
	    			if ($category != 'global') {
	    				$distribution[] = $context->getConfig('student/property/evaluationCategory')['modalities'][$category][$context->getLocale()].':&nbsp;'.$context->formatFloat($value, 2);
	    			}
	    		}
	    		$distribution = implode('<br>', $distribution);
	    		$rows.= sprintf(
	    				$context->getConfig('student/report')['detailRow']['html'], 
	    				(($color) ? 'style="background-color: #EEE"' : ''),
	    				$context->getConfig('student/property/school_subject')['modalities'][$evaluation->subject][$context->getLocale()],
						$context->getFormatedName(),
	    				$context->formatFloat($evaluation->weight, 1),
	    				$context->formatFloat($evaluation->value, 2),
						$context->formatFloat($evaluation->lower_note, 2),
	    				$context->formatFloat($evaluation->average_note, 2),
						$context->formatFloat($evaluation->higher_note, 2),
						$distribution,
						$evaluation->assessment
	    		);
	    		$color = ($color+1)%2;
	    	}

	    	$headerDef = $context->getConfig('student/report')['detailHeader'];
	    	$params = $headerDef['params'];
			$params['rows'] = $rows;
			$header = PdfReportViewHelper::mapArgs($headerDef, $params, $context->getLocale());
	    	
	    	$text .= $header;

		$pdf->writeHTML($text, true, 0, true, 0);
		    								
    	// Close and output PDF document
    	// This method has several options, check the source code documentation for more information.
    	return $pdf;
    }
}
