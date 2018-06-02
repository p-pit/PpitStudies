<?php
namespace PpitStudies\ViewHelper;

use Zend\View\Model\ViewModel;
use PpitCommitment\Model\Commitment;
use PpitCommitment\Model\Term;
use PpitCore\Model\Context;
use PpitCore\Model\Place;
use PpitCore\Model\Vcard;
use PpitStudies\ViewHelper\PdfReportTableViewHelper;

require_once('vendor/TCPDF-master/tcpdf.php');

class PdfAbsenceTableViewHelper
{	
	public static function mapArgs($headerDef, $params, $locale)
	{
		$arguments = array($headerDef['html']);
		foreach ($params as $param) if (is_array($param)) $arguments[] = $param[$locale];
		else $arguments[] = $param;
		return call_user_func_array('sprintf', $arguments);
	}

	public static function render($absLates)
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
		$translator = $context->getServiceManager()->get('translator');

		$text = $context->getConfig('student/report')['pdfDetailStyle'];
		$rows = '';
	    $subject = null;
	    foreach ($absLates as $absLate) {
	    	$period = '';
			if ($absLate->end_date > $absLate->begin_date) {
				$period .= $translator->translate('From the', 'ppit-studies', $context->getLocale());
				$period .= ' <strong>'.$context->decodeDate($absLate->begin_date).'</strong><br>';
				$period .= $translator->translate('to the', 'ppit-studies', $context->getLocale());
				$period .= ' <strong>'.$context->decodeDate($absLate->end_date).'</strong>';
			}
			else {
				$period .= $context->decodeDate($absLate->begin_date);
			}
			$duration = '';
			if ($absLate->duration) {
				if ((int)($absLate->duration/60)) $duration .= ((int)($absLate->duration/60)).'h';
				if ($absLate->duration%60) $duration .= sprintf('%02u', $absLate->duration%60).'mn';
			}
	    	$rows.= sprintf(
	   				$context->getConfig('student/report')['absenceRow']['html'], 
	    			'',
					$translator->translate((($absLate->category == 'absence') ? 'Absence' : 'Lateness'), 'ppit-studies', $context->getLocale()),
	    			($absLate->subject) ? $context->getConfig('student/property/school_subject')['modalities'][$absLate->subject][$context->getLocale()] : '',
					$period,
	    			$duration,
	    			(($absLate->motive) ? $context->localize($context->getConfig('absence/property/motive')['modalities'][$absLate->motive]) : ''),
	    			(($absLate->observations) ? $absLate->observations : '')
	   		);
	    }

	   	$headerDef = $context->getConfig('student/report')['absenceHeader'];
	   	$params = $headerDef['params'];
		$params['rows'] = $rows;
		$header = PdfAbsenceTableViewHelper::mapArgs($headerDef, $params, $context->getLocale());
	    $text .= $header;

    	return $text;
    }
}
