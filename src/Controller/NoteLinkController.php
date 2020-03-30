<?php

namespace PpitStudies\Controller;

use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
use PpitCore\Model\Place;
use PpitCore\Form\CsrfForm;
use PpitStudies\Model\Note;
use PpitStudies\Model\NoteLink;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NoteLinkController extends AbstractActionController
{
	/**
	 * REST version for 2pit2 - GET list
	 */
	public function getList() {
	
		// Retrieve the context and config
		$context = Context::getCurrent();
		$config = NoteLink::getConfig();
		
		// Retrieve the note type and category
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		
		// retrieve the filters
		$filters = ['category' => $category];
		foreach ($config as $propertyId => $property) {
			$value = $this->params()->fromQuery($propertyId, null);
			if ($value !== null) $filters[$propertyId] = $value;
		}

		// Retrieve the order
		$order = $this->params()->fromQuery('order', '-date');
		if (substr($order, 0, 1) == '+') $dir = 'ASC';
		else $dir = 'DESC';
		$major = substr($order, 1);
	
		// Retrieve the filtered and ordered links
		$content = ['major' => $major];
		$links = NoteLink::getList($type, $filters, $major, $dir, 'search');
		$content['links'] = [];
		foreach ($links as $link) $content['links'][$link->id] = $link->getProperties();
		$content['config']['properties'] = $config;
		$content['config']['list'] = NoteLink::getConfigList($config);
		
		return $content;
	}
	
	/**
	 * REST version for 2pit2 - GET
	 */
	public function get($id) {
	
		// Retrieve the context and config
		$context = Context::getCurrent();
		$config = NoteLink::getConfig();
		
		// Retrieve the filtered and ordered links
		$content = [];
		$link = NoteLink::get($id);
		$content['link'] = $link->getProperties();
		$content['config'] = $config;

		return $content;
	}
	
	/**
	 * REST version for 2pit2 - POST
	 */
	public function post($id) { // To do
	
		// Retrieve the context
		$context = Context::getCurrent();
		$config = NoteLink::getConfig();
		
		// Instanciate the new link
	
		$content = [];

		if ($id) {
			$link= NoteLink::get($id);
			$content['link'] = $link->getProperties();
		}
		else {
			$link = NoteLink::instanciate($account_id, $note_id);
		
			// Retrieve the note
			$note_id = $this->params()->fromQuery('note_id');
			$note = Note::get($note_id);
			if (!$note) {
				$this->response->setStatusCode('400');
				$this->response->setReasonPhrase('Expecting an existing note');
				return;
			}
			$content['link'] = $link->getProperties();
			$content['link']['place_id'] = $note->place_id;
			$content['link']['place_caption'] = $note->place_caption;
			$content['link']['n_fn'] = $note->n_fn;
			$content['link']['account_class'] = $note->account_class;
			$content['link']['note_status'] = $note->note_status;
			$content['link']['category'] = $note->category;
			$content['link']['type'] = $note->type;
			$content['link']['subject'] = $note->subject;
			$content['link']['school_year'] = $note->school_year;
			$content['link']['level'] = $note->level;
			$content['link']['group_id'] = $note->group_id;
			$content['link'] ['school_period'] = $note->school_periods;
			$content['link']['teacher_id'] = $note->teacher_id;
			$content['link']['date'] = $note->date;
			$content['link']['reference_value'] = $note->reference_value;
			$content['link']['weight'] = $note->weight;
			$content['link']['observations'] = $note->observations;
			$content['link']['update_time'] = $link->update_time;
		}
		
		$content['config'] = $config;
		
		$rc = $noteLink->loadData($content['link']);
		if ($rc != 'OK') {
			$this->response->setStatusCode('409');
			$this->response->setReasonPhrase($rc);
			return null;
		}

		// Atomically save
		$connection = NoteLink::getTable()->getAdapter()->getDriver()->getConnection();
		$connection->beginTransaction();
		try {
			$update_time = $this->request->getPost('update_time');
			if ($link->id) $rc = $link->update('update_time');
			else {
				$rc = $noteLink->add();
				$content['link']['id'] = $link->id;
			}
			if ($rc != 'OK') {
				$connection->rollback();
				$this->response->setStatusCode('409');
				$this->response->setReasonPhrase($rc);
				return null;
			}
			
			// Update the subject and global averages
/*			$rc = Note::updateAverage($content['link']['group_id'], $content['link']['subject'], $content['link']['school_year'], $content['link']['school_period']);
			if ($rc) {
				$connection->rollback();
				$this->response->setStatusCode('409');
				$this->response->setReasonPhrase($rc);
				return null;
			}
			
			// Compute the group indicators
			$content['indicators'] = $note->computeGroupIndicators();*/

			$connection->commit();
			$this->response->setStatusCode('200');
			return $content;
		}
		catch (\Exception $e) {
			$connection->rollback();
			$this->response->setStatusCode('409');
			$this->response->setReasonPhrase('Unhandled exception');
			return null;
		}

		return $content;
	}

	/**
	 * REST version for 2pit2 - GET
	 */
	public function delete($id) {
	
		// Retrieve the context and config
		$context = Context::getCurrent();
		$config = NoteLink::getConfig();
	
		// Retrieve the filtered and ordered links
		$content = [];
		$content['id'] = $id;
		$content['update_time'] = $this->request()->getPost('update_time');
		$content['config'] = $config;
	
		$link = NoteLink::get($id);
		$rc = $link->delete(null);
		if ($rc != 'OK') {
			$connection->rollback();
			$this->response->setStatusCode('409');
			$this->response->setReasonPhrase($rc);
			return null;
		}
		
		return $content;
	}
	
	public function listAction()
	{
		$context = Context::getCurrent();
		$content = $this->getList();
	
		$view = new ViewModel(array(
			'context' => $context,
			'content' => $content,
			'statusCode' => $this->response->getStatusCode(),
			'reasonPhrase' => $this->response->getReasonPhrase(),
		));
		$view->setTerminal(true);
		return $view;
	}
	
	public function v1Action() 
	{
		$context = Context::getCurrent();
		
		// Authentication
		if (!$context->wsAuthenticate($this->getEvent())) {
			$this->getResponse()->setStatusCode('401');
			return $this->getResponse();
		}
		
		// Authorization
		if (!$context->hasRole('manager') && !$context->hasRole('teacher')) {
			$this->response->setStatusCode('403');
			return $this->response;
		}
		
		// Retrieve the context
		$context = Context::getCurrent();

		if ($this->request->isGet()) {
			$id = $this->params()->fromRoute('id');
			if ($id) $content = $this->get($id);
			else $content = $this->getList();
		}

		elseif ($this->request->isPost()) {
			$id = $this->params()->fromRoute('id');
			$content = $this->post($id);
		}

		elseif ($this->request->isDelete()) {
			$id = $this->params()->fromRoute('id');
			$content = $this->delete($id);
		}
		
		header('Content-Type: application/json');
		echo json_encode($content, JSON_PRETTY_PRINT);
		return $this->response;
	}
}
