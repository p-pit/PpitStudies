<?php
namespace PpitStudies\ViewHelper;

use PpitCore\Model\Context;
use PpitStudies\Model\Progress;

class SsmlProgressViewHelper
{
	public static function formatXls($workbook, $view)
	{
		$context = Context::getCurrent();
		$tableColNames = $context->getConfig()['tableColNames'];
		$translator = $context->getServiceManager()->get('translator');

		$title = $context->getConfig('progress/search')['title'];
		
		// Set document properties
		$workbook->getProperties()->setCreator('P-PIT')
			->setLastModifiedBy('P-PIT')
			->setTitle($title)
			->setSubject($title)
			->setDescription($title)
			->setKeywords($title)
			->setCategory($title);

		$sheet = $workbook->getActiveSheet();
		

		$tableColNames = array(
			1 => 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
			'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
			'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
		);
		
		$i = 0;
		
		foreach($context->getConfig('progress')['properties'] as $propertyId => $property) {
			$i++;
			$sheet->setCellValue($tableColNames[$i].'1', $property['labels'][$context->getLocale()]);
		}

		$j = 1;
		foreach ($view->progresses as $progress) {
			$j++;
			$i = 0;
			foreach($context->getConfig('progress')['properties'] as $propertyId => $property) {
				$i++;
				if ($propertyId == 'name') $sheet->setCellValue($tableColNames[$i].$j, $progress->name);
				elseif ($property['type'] == 'date') $sheet->setCellValue($colNames[$i].$j, $context->decodeDate($progress->properties[$propertyId]));
				elseif ($property['type'] == 'number') $sheet->setCellValue($colNames[$i].$j, $context->formatFloat($progress->properties[$propertyId], 2));
				else $sheet->setCellValue($tableColNames[$i].$j, $progress->properties[$propertyId]);
			}
		}
		$i = 0;
		foreach($context->getConfig('progress')['properties'] as $propertyId => $property) {
			$i++;
			$sheet->getColumnDimension($tableColNames[$i])->setAutoSize(true);
		}
	}
}