<?php
namespace PpitStudies\Controller;

use PpitContact\Controller\MessageController;
use PpitStudies\Model\SportStudiesTarget;

class SmsController extends MessageController
{	
	protected $placeOfBusinessTable;
	protected $studentTable;
	
	public function getTargetExemplary($message) { 

		return new SportStudiesTarget($message);
	}
}
