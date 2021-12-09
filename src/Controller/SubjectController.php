<?php

namespace PpitStudies\Controller;

use PpitCore\Model\Context;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SubjectController extends AbstractActionController
{
	/**
	 * REST version for 2pit2 - GET list
	 */
	public function getList() {
	
		// Retrieve the context and config
		$context = Context::getCurrent();
		$config = $context->getConfig('student/property/school_subject');
		
		// retrieve the filters
		$subject_id = $this->params()->fromQuery('subject_id', null);
		if (!$subject_id) return $config['modalities'];

		$subject_id = explode(',', $subject_id);

		// Retrieve the filtered subjects
		$subjects = [];
		foreach($subject_id as $modalityId) {
			if (array_key_exists($modalityId, $config['modalities'])) $subjects[$modalityId] = $config['modalities'][$modalityId];
		}
		
		return ['subjects' => $subjects];
	}
	
	/**
	 * REST version for 2pit2 - GET
	 */
	public function get($id) {
	
		// Retrieve the context and config
		$context = Context::getCurrent();
		$config = $context->getConfig('student/property/school_subject');

		$subjects = [];
		if (array_key_exists($id, $config['modalities'])) $subjects[$id] = $config['modalities'][$id];
		
		return ['subjects' => $subjects];
	}

	public function v1Action() 
	{
		$context = Context::getCurrent();
		
		// // Authentication
		// if (!$context->wsAuthenticate($this->getEvent())) {
		// 	$this->response->setStatusCode('401');
		// 	$this->response->setContent(json_encode(['message' => 'Non authentifié. Error : P-Pit Subj.']));
		// 	return $this->response;
		// }
		
		// // Authorization
		// if (!$context->hasRole('manager') && !$context->hasRole('teacher')) {
		// 	$this->response->setStatusCode('403');
		// 	$this->response->setContent(json_encode(['message' => 'Non autorisé. Error : P-Pit Subj.']));
		// 	return $this->response;
		// }
		
		// Retrieve the context
		$context = Context::getCurrent();

		if ($this->request->isGet()) {
			$id = $this->params()->fromRoute('id');
			if ($id) $content = $this->get($id);
			else $content = $this->getList();
		}
		
		header('Content-Type: application/json');
		echo json_encode($content, JSON_PRETTY_PRINT);
		return $this->response;
	}
}
