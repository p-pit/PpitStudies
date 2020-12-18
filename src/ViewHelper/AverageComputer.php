<?php
namespace PpitStudies\ViewHelper;

use PpitCore\Model\Context;

class AverageComputer
{
	public static function compute($notes)
	{
		$context = Context::getCurrent();
		$computed = [];
		foreach ($notes as $link) {
			if (!array_key_exists($link->account_id, $computed)) $computed[$link->account_id] = [];
			if (!array_key_exists($link->subject, $computed[$link->account_id])) $computed[$link->account_id][$link->subject] = [0, 0];
			if ($link->value !== null) {
				$computed[$link->account_id][$link->subject][0] += $link->value * $link->weight;
				$computed[$link->account_id][$link->subject][1] += $link->reference_value * $link->weight;
			}
		}
		$globalComputed = [];
		$indicators = [];
		$averageReference = $context->getConfig('student/parameter/average_computation')['reference_value'];
		foreach ($computed as $account_id => $computedAccount) {
			if (!array_key_exists($account_id, $globalComputed)) $globalComputed[$account_id] = [0, 0];
			foreach ($computedAccount as $subject => $average) {
				if (!array_key_exists($subject, $indicators)) $indicators[$subject] = ['higher_note' => 0, 'average_note' => [0, 0], 'lower_note' => $averageReference];
				if ($subject != 'global' && $average[1]) {
					$normalized = $average[0] / $average[1] * $averageReference;
					if ($subject == 'study-period') $normalized *= 0.5; // To make generic using subject's weight in report
					$globalComputed[$account_id][0] += $normalized;
					$globalComputed[$account_id][1] += ($subject == 'study-period') ? $averageReference * 0.5 : $averageReference;
					if ($indicators[$subject]['higher_note'] < $normalized) $indicators[$subject]['higher_note'] = $normalized;
					if ($indicators[$subject]['lower_note'] > $normalized) $indicators[$subject]['lower_note'] = $normalized;
					$indicators[$subject]['average_note'][0] += $normalized;
					$indicators[$subject]['average_note'][1] ++;
				}
			}
			$computed[$account_id]['global'] = $globalComputed[$account_id];
			$normalized = $globalComputed[$account_id][0] / $globalComputed[$account_id][1] * $averageReference;
			if (array_key_exists('global', $indicators)) {
				if ($indicators['global']['higher_note'] < $normalized) $indicators['global']['higher_note'] = $normalized;
				if ($indicators['global']['lower_note'] > $normalized) $indicators['global']['lower_note'] = $normalized;
				$indicators['global']['average_note'][0] += $normalized;
				$indicators['global']['average_note'][1] ++;
			}
		}
		return ['averages' => $computed, 'indicators' => $indicators];
	}
}