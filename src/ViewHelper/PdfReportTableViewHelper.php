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

class PdfReportTableViewHelper
{	
	public static function mapArgs($headerDef, $params, $locale)
	{
		$context = Context::getCurrent();
		$arguments = array($headerDef['html']);
		foreach ($params as $param) {
			if (is_array($param)) $arguments[] = $context->localize($param, $locale);
			else $arguments[] = $param;
		}
		return call_user_func_array('sprintf', $arguments);
	}

	public static function render($period, $category = 'report', $orderedSubjects = [])
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
		$translator = $context->getServiceManager()->get(\Zend\I18n\Translator\TranslatorInterface::class);

		$text = $context->getConfig('student/report/pdfDetailStyle');
		$rows = '';
	    $color = 0;
	    $globalEvaluation = '';
	    $globalAverage = null;

	    $evaluations = [];
	    foreach ($orderedSubjects as $subject) $evaluations[$subject] = null;
	    foreach ($period as $evaluation) $evaluations[$evaluation->subject] = $evaluation;
	    	 
	    foreach ($evaluations as $evaluation) {
	    	if ($evaluation) {
	    		if ($evaluation->subject == 'global') $globalAverage = $evaluation;
	    		else {
	    			$subject = (!$evaluation->subject) ? '' : $context->localize($context->getConfig('student/property/school_subject')['modalities'][$evaluation->subject]);
		    		if ($evaluation->value === null) $note = $translator->translate('Not eval.', 'ppit-studies', $context->getLocale());
		    		else {
		    			$score = null;
		    			if (array_key_exists('segmentation', $context->getConfig('student/parameter/average_computation'))) {
			    			foreach ($context->getConfig('student/parameter/average_computation')['segmentation'] as $score => $ceiling) {
			    				if ($ceiling >= $evaluation->value) break;
			    			}
			    		}
		    			$note = $context->formatFloat($evaluation->value, 2);
			    		if ($evaluation->reference_value != 20) $note .= '/'.$context->formatFloat($evaluation->reference_value, 0);
		    			if ($score) $note = $score .' ('.$note.')';
		    		}
			   		$rows.= sprintf(
			   				$context->getConfig('student/report/detailRow')['html'], 
			   				(($color) ? 'style="background-color: #EEE"' : ''),
			   				$subject,
							$evaluation->n_fn,
			   				$context->formatFloat($evaluation->weight, 1),
			   				$note,
			   				$context->formatFloat($evaluation->higher_note, 2),
			   				$context->formatFloat($evaluation->average_note, 2),
							$context->formatFloat($evaluation->lower_note, 2),
							$evaluation->assessment
			   		);
			   		$color = ($color+1)%2;
	    		}
    		}
	    }
	    if ($globalAverage) {
	    	$evaluation = $globalAverage;
		    $subject = '<strong>'.$translator->translate('Global average', 'ppit-studies', $context->getLocale()).'</strong>';
		    if ($evaluation->value === null) $note = $translator->translate('Not eval.', 'ppit-studies', $context->getLocale());
		    else {
    			$score = null;
    			if (array_key_exists('segmentation', $context->getConfig('student/parameter/average_computation'))) {
	    			foreach ($context->getConfig('student/parameter/average_computation')['segmentation'] as $score => $ceiling) {
	    				if ($ceiling >= $evaluation->value) break;
	    			}
	    		}
    			$note = $context->formatFloat($evaluation->value, 2);
	    		if ($evaluation->reference_value != 20) $note .= '/'.$context->formatFloat($evaluation->reference_value, 0);
//    			if ($score) $note = $score.' ('.$note.')';
		    }
		    $rows.= sprintf(
		    		$context->getConfig('student/report/detailRow')['html'],
		    		(($color) ? 'style="background-color: #EEE"' : ''),
		    		$subject,
		    		$evaluation->n_fn,
		    		'',
		    		$note,
		    		'', //$context->formatFloat($evaluation->lower_note, 2),
		    		'', //$context->formatFloat($evaluation->average_note, 2),
		    		'', //$context->formatFloat($evaluation->higher_note, 2),
		    		''
		    );
	    };

	   	$headerDef = $context->getConfig('student/report/detailHeader');
	   	$params = $headerDef['params'];
		$params['rows'] = $rows;
		$header = PdfReportTableViewHelper::mapArgs($headerDef, $params, $context->getLocale());
	    $text .= $header;
    	return $text;
    }
}
