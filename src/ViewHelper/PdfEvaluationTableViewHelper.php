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
		$translator = $context->getServiceManager()->get(\Zend\I18n\Translator\TranslatorInterface::class);

		$text = $context->getConfig('student/report/pdfDetailStyle');
		$rows = '';
	    $subject = null;
	    $globalAverage = null;
	    foreach ($evaluations as $evaluation) {
	    	if ($evaluation->subject == 'global') $globalAverage = $evaluation;
	    	else {
		    	if ($evaluation->subject != $subject) {
		    		$rows .= sprintf(
		   					$context->getConfig('student/report/evaluationSubject')['html'], 
			   				'style="background-color: #EEE"',
		    				(!$evaluation->subject) ? '' : ($context->localize($context->getConfig('student/property/school_subject')['modalities'][$evaluation->subject])).' - '.$evaluation->n_fn);
		    	}
		    	$subject = $evaluation->subject;
		    	$caption = (array_key_exists($evaluation->level, $context->getConfig('student/property/evaluationCategory')['modalities'])) ? $context->localize($context->getConfig('student/property/evaluationCategory')['modalities'][$evaluation->level]) : '';
		    	if ($evaluation->assessment) $caption .= '<br><span style="font-weight: bold">'.$evaluation->assessment.'</span>';
		   		$rows.= sprintf(
		   				$context->getConfig('student/report/evaluationRow')['html'], 
						'',
		   				$caption,
		   				$context->formatFloat($evaluation->weight, 1),
		   				($evaluation->value === null) ? $translator->translate('Not eval.', 'ppit-studies', $context->getLocale()) : $context->formatFloat($evaluation->value, 1).'/'.$context->formatFloat($evaluation->reference_value, 0),
		   				$context->formatFloat($evaluation->higher_note, 2),
		   				$context->formatFloat($evaluation->average_note, 2),
						$context->formatFloat($evaluation->lower_note, 2),
						$context->decodeDate($evaluation->date)/*,
						$evaluation->assessment*/
		   		);
	    	}
	    }

	    if ($globalAverage) {
	    	$evaluation = $globalAverage;
	    	$subject = '<strong>'.$translator->translate('Global average', 'ppit-studies', $context->getLocale()).'</strong>';
	    	if ($evaluation->value === null) $note = $translator->translate('Not eval.', 'ppit-studies', $context->getLocale());
	    	else {
	    		$note = $context->formatFloat($evaluation->value, 2);
	    		if ($evaluation->reference_value != 20) $note .= '/'.$context->formatFloat($evaluation->reference_value, 0);
	    	}
	    	$caption = $subject;
		    if ($evaluation->assessment) $caption .= '<br><span style="font-weight: bold">'.$evaluation->assessment.'</span>';
	    	$rows.= sprintf(
	    		$context->getConfig('student/report/evaluationRow')['html'],
				'',
	    		$caption,
		   		$context->formatFloat($evaluation->weight, 1),
		   		($evaluation->value === null) ? $translator->translate('Not eval.', 'ppit-studies', $context->getLocale()) : $context->formatFloat($evaluation->value, 1).'/'.$context->formatFloat($evaluation->reference_value, 0),
				$context->formatFloat($evaluation->lower_note, 2),
		   		$context->formatFloat($evaluation->average_note, 2),
				$context->formatFloat($evaluation->higher_note, 2),
				$context->decodeDate($evaluation->date)
	    	);
	    };

	   	$headerDef = $context->getConfig('student/report/evaluationHeader');
	   	$params = $headerDef['params'];
		$params['rows'] = $rows;
		$header = PdfEvaluationTableViewHelper::mapArgs($headerDef, $params, $context->getLocale());
	    $text .= $header;
    	return $text;
    }
}
