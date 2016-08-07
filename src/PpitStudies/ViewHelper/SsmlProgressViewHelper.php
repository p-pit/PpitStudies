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