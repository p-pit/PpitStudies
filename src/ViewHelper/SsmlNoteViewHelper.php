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

class SsmlNoteViewHelper
{
	public static function formatXls($workbook, $view, $groups)
	{
		$context = Context::getCurrent();
		$translator = $context->getServiceManager()->get(\Zend\I18n\Translator\TranslatorInterface::class);
		$category = $view->category;
		$type = $view->type;
		
		$title = $context->localize($context->getConfig('note/export'.(($category) ? '/'.$category : ''))['title']);
		
		// Set document properties
		$workbook->getProperties()->setCreator('P-Pit')
			->setLastModifiedBy('P-PIT')
			->setTitle($title)
			->setSubject($title)
			->setDescription($title)
			->setKeywords($title)
			->setCategory($title);

		$sheet = $workbook->getActiveSheet();

		foreach($context->getConfig('note/export'.(($category) ? '/'.$category : ''))['properties'] as $propertyId => $column) {
			$property = $context->getConfig('note')['properties'][$propertyId];
			if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
			$sheet->setCellValue($column.'1', $context->localize($property['labels']));
			$sheet->getStyle($column.'1')->getFont()->getColor()->setRGB(substr($context->getConfig('styleSheet')['panelHeadingColor'], 1, 6));
			$sheet->getStyle($column.'1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB(substr($context->getConfig('styleSheet')['panelHeadingBackground'], 1, 6));
			$sheet->getStyle($column.'1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.'1')->getFont()->setBold(true);
		}

		$j = 1;
		foreach ($view->noteLinks as $noteLink) {
			$j++;
			foreach($context->getConfig('note/export'.(($category) ? '/'.$category : ''))['properties'] as $propertyId => $column) {
				$property = $context->getConfig('note')['properties'][$propertyId];
				if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
				if ($property) {
					if ($propertyId == 'group_id') {
						if ($noteLink->getProperties()[$propertyId]) $sheet->setCellValue($column.$j, $groups[$noteLink->getProperties()[$propertyId]]->name);
					}
					elseif ($propertyId == 'average') {
						$key = $noteLink->account_id . '-' . $noteLink->school_year . '-' . $noteLink->school_period . '-' . $noteLink->subject;
						if (array_key_exists($key, $view->averages)) $average = $view->averages[$key]['sum'] / $view->averages[$key]['reference_value'] * $context->getConfig('student/parameter/average_computation')['reference_value'];
						else $average = 'Non noté';
						$sheet->setCellValue($column.$j, $average);
						$sheet->getStyle($column.$j)->getNumberFormat()->setFormatCode('### ##0.00');
					}
					elseif ($propertyId == 'global_average') {
						$key = $noteLink->account_id . '-' . $noteLink->school_year . '-' . $noteLink->school_period . '-global';
						if (array_key_exists($key, $view->averages)) $average = $view->averages[$key]['sum'] / $view->averages[$key]['reference_value'] * $context->getConfig('student/parameter/average_computation')['reference_value'];
						else $average = 'Non noté';
						$sheet->setCellValue($column.$j, $average);
						$sheet->getStyle($column.$j)->getNumberFormat()->setFormatCode('### ##0.00');
					}
					elseif ($property['type'] == 'date') $sheet->setCellValue($column.$j, $context->decodeDate($noteLink->getProperties()[$propertyId]));
					elseif ($property['type'] == 'number') {
						$sheet->setCellValue($column.$j, $noteLink->getProperties()[$propertyId]);
						$sheet->getStyle($column.$j)->getNumberFormat()->setFormatCode('### ##0.00');
					}
					elseif ($property['type'] == 'select')  $sheet->setCellValue($column.$j, (array_key_exists('modalities', $property) && array_key_exists($noteLink->getProperties()[$propertyId], $property['modalities'])) ? $context->localize($property['modalities'][$noteLink->getProperties()[$propertyId]]) : $noteLink->getProperties()[$propertyId]);
					else $sheet->setCellValue($column.$j, $noteLink->getProperties()[$propertyId]);
				}
			}
		}
		foreach($context->getConfig('note/export'.(($category) ? '/'.$category : ''))['properties'] as $propertyId => $column) {
			$property = $context->getConfig('note')['properties'][$propertyId];
			if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
			if ($property['type'] != 'specific' || $context->getConfig($property['definition'])) {
				$sheet->getColumnDimension($column)->setAutoSize(true);
			}
		}
	}
}