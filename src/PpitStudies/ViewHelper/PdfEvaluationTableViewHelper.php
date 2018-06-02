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

class PdfEvaluationTableViewHelper
{	
	public static function mapArgs($headerDef, $params, $locale)
	{
		$arguments = array($headerDef['html']);
		foreach ($params as $param) if (is_array($param)) $arguments[] = $param[$locale];
		else $arguments[] = $param;
		return call_user_func_array('sprintf', $arguments);
	}

	public static function render($evaluations, $category = 'report')
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
		$translator = $context->getServiceManager()->get('translator');

		$text = $context->getConfig('student/report')['pdfDetailStyle'];
		$rows = '';
	    $subject = null;
	    foreach ($evaluations as $evaluation) {
	    	if ($evaluation->subject != $subject) {
	    		$rows .= sprintf(
	   					$context->getConfig('student/report')['evaluationSubject']['html'], 
		   				'style="background-color: #EEE"',
	    				(!$evaluation->subject) ? '' : ($context->getConfig('student/property/school_subject')['modalities'][$evaluation->subject][$context->getLocale()]).' - '.$evaluation->n_fn);
	    	}
	    	$subject = $evaluation->subject;
	   		$rows.= sprintf(
	   				$context->getConfig('student/report')['evaluationRow']['html'], 
	    			'',
	   				'',
					(array_key_exists($evaluation->level, $context->getConfig('student/property/evaluationCategory')['modalities'])) ? $context->getConfig('student/property/evaluationCategory')['modalities'][$evaluation->level][$context->getLocale()] : '',
	   				$context->formatFloat($evaluation->weight, 1),
	   				($evaluation->value === null) ? $translator->translate('Not eval.', 'ppit-studies', $context->getLocale()) : $context->formatFloat($evaluation->value, 1).'/'.$context->formatFloat($evaluation->reference_value, 0),
					$context->formatFloat($evaluation->lower_note, 2),
	   				$context->formatFloat($evaluation->average_note, 2),
					$context->formatFloat($evaluation->higher_note, 2),
					$context->decodeDate($evaluation->date)/*,
					$evaluation->assessment*/
	   		);
	    }

	   	$headerDef = $context->getConfig('student/report')['evaluationHeader'];
	   	$params = $headerDef['params'];
		$params['rows'] = $rows;
		$header = PdfEvaluationTableViewHelper::mapArgs($headerDef, $params, $context->getLocale());
	    $text .= $header;
    	return $text;
    }
}
