<?php
/**
 * PpitCore V1.0 (https://github.com/p-pit/PpitCore)
 *
 * @link      https://github.com/p-pit/PpitCore
 * @copyright Copyright (c) 2016 Bruno Lartillot
 * @license   https://github.com/p-pit/PpitCore/blob/master/license.txt GNU-GPL license
 */

namespace PpitStudies\ViewHelper;

use PpitCore\Model\Context;

class SsmlAbsenceViewHelper
{
	public static function formatXls($workbook, $view)
	{
		$context = Context::getCurrent();
		$translator = $context->getServiceManager()->get('translator');
		
		$title = $context->getConfig('absence/export')['title'][$context->getLocale()];
		
		// Set document properties
		$workbook->getProperties()->setCreator('P-Pit')
			->setLastModifiedBy('P-PIT')
			->setTitle($title)
			->setSubject($title)
			->setDescription($title)
			->setKeywords($title)
			->setCategory($title);

		$sheet = $workbook->getActiveSheet();

		foreach($context->getConfig('absence/export')['properties'] as $propertyId => $column) {
			$property = $context->getConfig('absence')['properties'][$propertyId];
			if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
			$sheet->setCellValue($column.'1', $property['labels'][$context->getLocale()]);
			$sheet->getStyle($column.'1')->getFont()->getColor()->setRGB(substr($context->getConfig('styleSheet')['panelHeadingColor'], 1, 6));
			$sheet->getStyle($column.'1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB(substr($context->getConfig('styleSheet')['panelHeadingBackground'], 1, 6));
			$sheet->getStyle($column.'1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.'1')->getFont()->setBold(true);
		}

		$j = 1;
		foreach ($view->absences as $absence) {
			$j++;
			foreach($context->getConfig('absence/export')['properties'] as $propertyId => $column) {
				$property = $context->getConfig('absence')['properties'][$propertyId];
				if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
				if ($property) {
					if ($property['type'] == 'date') $sheet->setCellValue($column.$j, $context->decodeDate($absence->properties[$propertyId]));
					elseif ($property['type'] == 'number') {
						$sheet->setCellValue($column.$j, $absence->properties[$propertyId]);
						$sheet->getStyle($column.$j)->getNumberFormat()->setFormatCode('### ##0.00');
					}
					elseif ($property['type'] == 'select')  $sheet->setCellValue($column.$j, (array_key_exists('modalities', $property) && array_key_exists($absence->properties[$propertyId], $property['modalities'])) ? $property['modalities'][$absence->properties[$propertyId]][$context->getLocale()] : $absence->properties[$propertyId]);
					else $sheet->setCellValue($column.$j, $absence->properties[$propertyId]);
				}
			}
		}
		foreach($context->getConfig('absence/export')['properties'] as $propertyId => $column) {
			$property = $context->getConfig('absence')['properties'][$propertyId];
			if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
			if ($property['type'] != 'specific' || $context->getConfig($property['definition'])) {
				$sheet->getColumnDimension($column)->setAutoSize(true);
			}
		}
	}
}