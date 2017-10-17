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
use PpitStudies\ViewHelper\PdfReportTableViewHelper;

require_once('vendor/TCPDF-master/tcpdf.php');

class PdfReportTableViewHelper
{	
	public static function mapArgs($headerDef, $params, $locale)
	{
		$arguments = array($headerDef['html']);
		foreach ($params as $param) if (is_array($param)) $arguments[] = $param[$locale];
		else $arguments[] = $param;
		return call_user_func_array('sprintf', $arguments);
	}

	public static function render($period, $category = 'report')
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
		$translator = $context->getServiceManager()->get('translator');

		$text = $context->getConfig('student/report')['pdfDetailStyle'];
		$rows = '';
	    $color = 0;
	    $globalEvaluation = '';
	    foreach ($period as $evaluation) {
	    	if ($evaluation->subject != 'global') {
	    		if ($category == 'note' && $evaluation->level) $distribution = $context->getConfig('student/property/evaluationCategory')['modalities'][$evaluation->level][$context->getLocale()].'&nbsp;'.$context->decodeDate($evaluation->date);
	    		else {
			   		$distribution = array();
			   		foreach ($evaluation->distribution as $category => $value) {
			   			if ($category != 'global') {
			   				$distribution[] = $context->getConfig('student/property/evaluationCategory')['modalities'][$category][$context->getLocale()].':&nbsp;'.$context->formatFloat($value, 2);
			   			}
			   		}
			   		$distribution = implode('<br>', $distribution);
	    		}
		   		$rows.= sprintf(
		   				$context->getConfig('student/report')['detailRow']['html'], 
		   				(($color) ? 'style="background-color: #EEE"' : ''),
		   				$context->getConfig('student/property/school_subject')['modalities'][$evaluation->subject][$context->getLocale()],
						$evaluation->n_fn,
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
	    }

	   	$headerDef = $context->getConfig('student/report')['detailHeader'];
	   	$params = $headerDef['params'];
		$params['rows'] = $rows;
		$header = PdfReportTableViewHelper::mapArgs($headerDef, $params, $context->getLocale());
	    $text .= $header;
    	return $text;
    }
}
