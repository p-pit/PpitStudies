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
	public static function formatXls($description, $workbook, $view)
	{
		$context = Context::getCurrent();
		$translator = $context->getServiceManager()->get(\Zend\I18n\Translator\TranslatorInterface::class);
		
		$title = $context->localize($context->getConfig('absence/export')['title']);
		
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
			$property = $description[$propertyId];
			$sheet->setCellValue($column.'1', $context->localize($property['labels']));
			$sheet->getStyle($column.'1')->getFont()->getColor()->setRGB(substr($context->getConfig('styleSheet')['panelHeadingColor'], 1, 6));
			$sheet->getStyle($column.'1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB(substr($context->getConfig('styleSheet')['panelHeadingBackground'], 1, 6));
			$sheet->getStyle($column.'1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.'1')->getFont()->setBold(true);
		}

		$j = 1;
		foreach ($view->absences as $absence) {
			$j++;
			foreach($context->getConfig('absence/export')['properties'] as $propertyId => $column) {
				$property = $description[$propertyId];
				if ($property) {
					if ($property['type'] == 'date') $sheet->setCellValue($column.$j, $context->decodeDate($absence->properties[$propertyId]));
					elseif ($property['type'] == 'number') {
						$sheet->setCellValue($column.$j, $absence->properties[$propertyId]);
						$sheet->getStyle($column.$j)->getNumberFormat()->setFormatCode('### ##0.00');
					}
					elseif ($property['type'] == 'select')  $sheet->setCellValue($column.$j, (array_key_exists('modalities', $property) && array_key_exists($absence->properties[$propertyId], $property['modalities'])) ? $context->localize($property['modalities'][$absence->properties[$propertyId]]) : $absence->properties[$propertyId]);
					elseif ($property['type'] == 'multiselect') {
						if ($absence->properties[$propertyId]) {
							$values = explode(',', $absence->properties[$propertyId]);
							$decodedValues = [];
							foreach ($values as $value) if (array_key_exists('modalities', $property) && array_key_exists($value, $property['modalities'])) $decodedValues[] = $context->localize($property['modalities'][$value]);
						}
						$sheet->setCellValue($column.$j, implode(',', $decodedValues));
					}
					else $sheet->setCellValue($column.$j, $absence->properties[$propertyId]);
				}
			}
		}
		foreach($context->getConfig('absence/export')['properties'] as $propertyId => $column) {
			$property = $description[$propertyId];
			if ($property['type'] != 'specific' || $context->getConfig($property['definition'])) {
				$sheet->getColumnDimension($column)->setAutoSize(true);
			}
		}
	}
}