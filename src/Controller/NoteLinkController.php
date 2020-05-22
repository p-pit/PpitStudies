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

		// Retrieve the limit
		$limit = $this->params()->fromQuery('limit');
		
		// Retrieve the order
		$order = $this->params()->fromQuery('order', '-date');
		if (substr($order, 0, 1) == '-') {
			$dir = 'DESC';
			$major = substr($order, 1);
		}
		else {
			$dir = 'ASC';
			$major = $order;
		}
	
		// Retrieve the filtered and ordered links
		$content = ['major' => $major, 'dir' => $dir];
		$links = NoteLink::getList($type, $filters, $major, $dir, 'search', $limit);
		$content['links'] = [];

		// Compute the averages
		$averages = [];
		foreach ($links as $link) {
			$content['links'][$link->id] = $link->getProperties();
			if (!array_key_exists($link->subject, $averages)) $averages[$link->subject] = [0, 0];
			$averages[$link->subject][0] += $link->value;
			$averages[$link->subject][1] += $link->reference_value;
		}
		$globalAverage = [0, 0];
		$averageReference = $context->getConfig('student/parameter/average_computation')['reference_value'];
		foreach ($averages as $subject => $average) {
			if ($subject != 'global') {
				$globalAverage[0] += $average[0] / $average[1] * $averageReference;
				$globalAverage[1] += $averageReference;
			}
		}

		$content['config']['properties'] = $config;
		$content['config']['list'] = NoteLink::getConfigList($config);
		$content['config']['student_list'] = NoteLink::getConfigStudentList($config);
		$content['averages'] = $averages;
		$content['globalAverage'] = $globalAverage;
		
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
			$content['link']['class'] = $note->class;
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
/*			$rc = Note::updateAverage($content['link']['place_id'], null, $content['link']['group_id'], $content['link']['subject'], $content['link']['school_year'], $content['link']['school_period']);
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
		$content['update_time'] = $this->request->getPost('update_time');
		$content['config'] = $config;

		$averages = [];
		foreach ($id as $note_link_id) {
if ($note_link_id) {
			$link = NoteLink::get($note_link_id);
			$averages[$link->place_id . '_' . $link->school_year . '_' . $link->school_period . '_' . $link->class . '_' . $link->group_id . '_' . $link->subject] = ['place_id' => $link->place_id, 'school_year' => $link->school_year, 'school_period' => $link->school_period, 'class' => $link->class, 'group_id' => $link->group_id, 'subject' => $link->subject];
			$rc = $link->delete(null);
			if ($rc != 'OK') {
				$this->response->setStatusCode('409');
				$this->response->setReasonPhrase($rc);
				return null;
			}
} else var_dump($note_link_id);
		}
		foreach ($averages as $average) Note::updateAverage($average['place_id'], null, $average['group_id'], $average['subject'], $average['school_year'], $average['school_period']);
		return $content;
	}

	public function indexAction()
	{
		// Retrieve the context and parameters
		$context = Context::getCurrent();
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		$place = Place::get($context->getPlaceId());
		$config = NoteLink::getConfig();
		
		$currentEntry = $this->params()->fromQuery('entry', 'term');
	
		// Transient: Serialize a list of the entries from all menus
		$menuEntries = [];
		foreach ($context->getApplications() as $applicationId => $application) {
			if ($context->getConfig('menus/'.$applicationId)) {
				foreach ($context->getConfig('menus/' . $applicationId)['entries'] as $entryId => $entryDef) {
					$menuEntries[$entryId] = ['menuId' => $applicationId, 'menu' => $application, 'definition' => $entryDef];
				}
			}
		}
		$tab = $this->params()->fromRoute('entryId', 'account');
	
		// Retrieve the application
		$app = $menuEntries[$tab]['menuId'];
		$applicationName = $context->localize($menuEntries[$tab]['menu']['labels']);
				
		// Feed the layout
		$this->layout('/layout/core-layout');
		$this->layout()->setVariables(array(
			'context' => $context,
			'category' => $category,
			'type' => $type,
			'place' => $place,
			'app' => $app,
			'tab' => $tab,
			'applicationName' => $applicationName,
			'pageScripts' => 'ppit-studies/note-link/scripts',
			'config' => $config,
			'configSearch' => NoteLink::getConfigSearch($config),
			'configList' => NoteLink::getConfigList($config),
			'configGroup' => NoteLink::getConfigGroup($config),
		));
	
		return new ViewModel(array(
			'context' => $context,
			'config' => $config,
			'app' => $app,
			'tab' => $tab,
			'applicationName' => $applicationName,
			'currentEntry' => $currentEntry,
		));
	}

	public function searchAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		
		// Retrieve the type
		$config = NoteLink::getconfig();
	
		// Return the link list
		$view = new ViewModel(array(
			'context' => $context,
			'category' => $category,
			'type' => $type,
			'config' => $config,
			'configSearch' => NoteLink::getConfigSearch($config),
    		'places' => Place::getList(array()),
		));
		$view->setTerminal(true);
		return $view;
	}
	
	public function listAction()
	{
		$context = Context::getCurrent();
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		$content = $this->getList();
		$order = $this->params()->fromQuery('order', '-date');

		$view = new ViewModel(array(
			'context' => $context,
			'category' => $category,
			'type' => $type,
			'content' => $content,
			'statusCode' => $this->response->getStatusCode(),
			'reasonPhrase' => $this->response->getReasonPhrase(),
			'order' => $order,
		));
		$view->setTerminal(true);
		return $view;
	}

	public function studentListAction()
	{
		return $this->listAction();
	}
	
	public function groupAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
		if ($this->request->isGet()) $requestType = 'GET';
		elseif ($this->request->isPost()) $requestType = 'POST';
		elseif ($this->request->isDelete()) $requestType = 'DELETE';
		
		// Retrieve the parameters and the panel configuration
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		$id = $this->params()->fromQuery('id');
		$noteLinks = NoteLink::getList($type, ['id' => $id], 'id', 'DESC', null);
		$id = explode(',', $id);
/*		$noteLinks = [];
		foreach ($id as $note_link_id) $noteLinks[$note_link_id] = NoteLink::get($note_link_id);*/
		
		if ($requestType == 'DELETE') $this->delete($id);

		$view = new ViewModel(array(
			'requestType' => $requestType,
			'context' => $context,
			'category' => $category,
			'type' => $type,
			'noteLinks' => $noteLinks,
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
