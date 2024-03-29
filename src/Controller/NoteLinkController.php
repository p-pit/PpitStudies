<?php

namespace PpitStudies\Controller;

use PpitCore\Model\Account;
use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
use PpitCore\Model\GenericTable;
use PpitCore\Model\Place;
use PpitCore\Model\Vcard;
use PpitCore\Form\CsrfForm;
use PpitStudies\Model\Note;
use PpitStudies\Model\NoteLink;
use Zend\Db\Sql\Where;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NoteLinkController extends AbstractActionController
{
	/**
	 * REST version for 2pit2 - GET list
	 */
	public function getList() {

		// Retrieve the note type and category
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
	
		// Retrieve the context and config
		$context = Context::getCurrent();
		//$config = NoteLink::getConfig(false);
		$model = $context->getConfig('v3/note_link/' . $type);
    	if (!$model) $model = $context->getConfig('v3/note_link/generic');
		$columns = array_keys($model['properties']);
		$config = array();
		foreach($columns as $propertyId) {
			$property = $context->getConfig('note_link/generic/property/'.$propertyId);
			if ($property) {
				$propertyType = (array_key_exists('type', $property)) ? $property['type'] : null;
				if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
				if ($propertyType) $property['type'] = $propertyType;
				if (!array_key_exists('private', $property)) $property['private'] = false;
				$config[$propertyId] = $property;	
			}
		}
		
		// retrieve the filters
		$filters = ['category' => $category, 'type' => $type];
		foreach ($config as $propertyId => $property) {
			$value = $this->params()->fromQuery($propertyId, null);
			if ($value !== null) {
				if ($propertyId == 'name') $filters[$propertyId] = ['like', $value];
				elseif (in_array($propertyId, ['value', 'evaluation'])) {
					$filters[$propertyId] = array_merge(['in'], explode(',', $value));
				}
				else $filters[$propertyId] = $value;
			}
		}

		// Retrieve the limit
		$limit = $this->params()->fromQuery('limit');
		
		// Retrieve the order
		$order = $this->params()->fromQuery('order', '-date');
		if (substr($order, 0, 1) == '-') {
			$dir = 'DESC';
			$major = substr($order, 1);
		}
		elseif (substr($order, 0, 1) == '+') {
			$dir = 'ASC';
			$major = substr($order, 1);
		}
		else {
			$dir = 'ASC';
			$major = $order;
			$order = '+' . $order;
		}
	
		// Retrieve the filtered and ordered links
		$content = ['major' => $major, 'dir' => $dir];

		$result = GenericTable::select('student_note_link', $model, $filters, $columns, $order, $limit);
		$links = [];
		foreach($result as $row) {
			$links[$row['id']] = $row;
		}
	
		//$links = NoteLink::getList($type, $filters, $major, $dir, 'search', $limit);
		$content['links'] = [];

		// Compute the averages
		$averages = [];
		foreach ($links as $link) {
			$content['links'][$link['id']] = $link;
			if (!array_key_exists($link['subject'], $averages)) $averages[$link['subject']] = [0, 0];
			if ($link['value'] !== null) {
				$averages[$link['subject']][0] += $link['value'] * $link['weight'];
				$averages[$link['subject']][1] += $link['reference_value'] * $link['weight'];
			}
		}
		$globalAverage = [0, 0];
		$averageReference = $context->getConfig('student/parameter/average_computation')['reference_value'];
		foreach ($averages as $subject => $average) {
			if ($subject != 'global' && $average[1]) {
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

		NoteLink::groupDelete($id);
		return $content;
	}

	public function indexAction()
	{
		// Retrieve the context and parameters
		$context = Context::getCurrent();
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		$place = Place::get($context->getPlaceId());
		//$config = NoteLink::getConfig(false);
		
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
			/*'config' => $config,
			'configSearch' => NoteLink::getConfigSearch($config),
			'configList' => NoteLink::getConfigList($config),
			'configGroup' => NoteLink::getConfigGroup($config),*/
		));
	
		return new ViewModel(array(
			'context' => $context,
			//'config' => $config,
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
		
		/*$cursor = Account::getListV3('teacher', ['n_fn', 'contact_1_id'], ['status' => 'active,committed,contrat_envoye,reconnect_with'], '+name');
		$teachers = [];
		foreach ($cursor as $teacher_id => $teacher) $teachers[$teacher['contact_1_id']] = $teacher;*/
		// Retrieve the groups
		$groups = Account::getList('group', [], null, null, ['id', 'status', 'name'], false, false, false, true);

		// Compute the average
		$filters = [];
		foreach (NoteLink::getConfig(false) as $propertyId => $property) {
			$value = $this->params()->fromQuery($propertyId, null);
			if ($value !== null/* && $propertyId != 'value'*/) $filters[$propertyId] = $value;
		}
		if ($type == 'report'/* && $filters*/) {
			//$averageReference = $context->getConfig('student/parameter/average_computation')['reference_value'];
			$notes = NoteLink::GetList('note', $filters, 'subject', 'ASC', 'search');
			/*$averages = [];
			foreach ($notes as $link) {
				if (!array_key_exists($link->school_year . '_' . $link->school_period . '_' . $link->account_id . '_' . $link->subject, $averages)) {
					$averages[$link->school_year . '_' . $link->school_period . '_' . $link->account_id . '_' . $link->subject] = [
						'school_year' => $link->school_year,
						'school_period' => $link->school_period,
						'account_id' => $link->account_id,
						'subject' => $link->subject,
						'num' => 0,
						'den' => 0
					];
				}
				if ($link->value !== null) {
					$averages[$link->school_year . '_' . $link->school_period . '_' . $link->account_id . '_' . $link->subject]['num'] += $link->value * $link->weight;
					$averages[$link->school_year . '_' . $link->school_period . '_' . $link->account_id . '_' . $link->subject]['den'] += $link->reference_value * $link->weight;
				}
			}
	    	$averageReference = $context->getConfig('student/parameter/average_computation')['reference_value'];
			$globalAverages = [];
			foreach ($averages as $averageKey => $average) {
				if (!array_key_exists($average['school_year'] . '_' . $average['school_period'] . '_' . $average['account_id'] . '_' . 'global', $globalAverages)) {
					$globalAverages[$average['school_year'] . '_' . $average['school_period'] . '_' . $average['account_id'] . '_' . 'global'] = [
						'school_year' => $average['school_year'],
						'school_period' => $average['school_period'],
						'account_id' => $average['account_id'],
						'subject' => 'global',
						'num' => 0,
						'den' => 0
					];
				}
				if ($average['den']) {
					$globalAverages[$average['school_year'] . '_' . $average['school_period'] . '_' . $average['account_id'] . '_' . 'global']['num'] += $average['num'] / $average['den'] * $averageReference;
					$globalAverages[$average['school_year'] . '_' . $average['school_period'] . '_' . $average['account_id'] . '_' . 'global']['den'] += $averageReference;
				}
			}
			$averages = array_merge($averages, $globalAverages);*/
		}
		//else $averages = [];
		
		$view = new ViewModel(array(
			'context' => $context,
			'category' => $category,
			'type' => $type,
			//'teachers' => $teachers,
			'groups' => $groups,
			'content' => $content,
			//'averages' => $averages,
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
		
		$places = Place::getList(array());
		$cursor = Account::getList('group', ['status' => 'active'], '+name', null);
		$groups = [];
		foreach ($cursor as $group) {
			$label = $group->name;
			if ($group->place_id && array_key_exists($group->place_id, $places)) $label .= ' (' . $places[$group->place_id]->caption . ')';
			$groups[$group->id] = ['default' => $label];
		}
		
		$myAccount = Account::get($context->getContactId(), 'contact_1_id');
		if ($myAccount && $myAccount->groups) $myGroups = explode(',', $myAccount->groups);
		else $myGroups = [];
		
		// Retrieve the parameters and the panel configuration
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		$id = $this->params()->fromQuery('id');
		$noteLinks = NoteLink::getList($type, ['id' => $id], 'id', 'DESC', null);
		$id = explode(',', $id);
/*		$noteLinks = [];
		foreach ($id as $note_link_id) $noteLinks[$note_link_id] = NoteLink::get($note_link_id);*/
		
		$params = ['status' => $context->getConfig('event/calendar/property/account_id')['account_status']];
		$teachers = Account::getList('teacher', $params, '+n_fn', null, ['id', 'n_fn', 'email', 'place_caption']);

		if ($requestType == 'DELETE') $this->delete($id);
		elseif ($requestType == 'POST') {
			$noteIds = [];
			$noteLinkIds = [];
			foreach ($noteLinks as $noteLink) {
				$noteIds[$noteLink->note_id] = null;
				if ($this->request->getPost('evaluation_checked')) {
					$noteLinkIds[] = $noteLink->id;
				}
			}
			if ($this->request->getPost('evaluation_checked')) {
				NoteLink::getTable()->groupUpdate($noteLinkIds, ['evaluation' => $this->request->getPost('evaluation')]);
			}
			foreach ($noteIds as $note_id => $unused) {
				$note = Note::get($note_id);
				$update = false;
				if ($this->request->getPost('group_id_checked')) {
					$note->group_id = $this->request->getPost('group_id');
					$update = true;
				}
				if ($this->request->getPost('teacher_id_checked')) {
					$note->teacher_id = $this->request->getPost('teacher_id');
					$update = ture;
				}
				if ($update) $note->update(null);
			}
		}
		
		$view = new ViewModel(array(
			'requestType' => $requestType,
			'context' => $context,
			'category' => $category,
			'type' => $type,
    		'groups' => $groups,
    		'myGroups' => $myGroups,
			'noteLinks' => $noteLinks,
			'teachers' => $teachers,
		));
		$view->setTerminal(true);
		return $view;
	}

	public function updateAction()
	{
		$context = Context::getCurrent();
		$request = ($this->getRequest()->isPost()) ? 'POST' : (($this->getRequest()->isDelete()) ? 'DELETE' : 'GET');
		$id = $this->params()->fromRoute('id');
		if (!$id) $this->redirect()->toRoute('home');
		$noteLink = NoteLink::get($id);
		$place = Place::get($noteLink->place_id);
		
		// Compute the average
		/*$averages = [];
		if ($noteLink->type == 'report') {
			$averageReference = $context->getConfig('student/parameter/average_computation')['reference_value'];
			$notes = NoteLink::GetList('note', ['school_year' => $noteLink->school_year, 'school_period' => $noteLink->school_period, 'account_id' => $noteLink->account_id], 'subject', 'ASC', 'search');
			foreach ($notes as $link) {
				if (!array_key_exists($link->subject, $averages)) $averages[$link->subject] = [0, 0];
				if ($link->value !== null) {
					$averages[$link->subject][0] += $link->value * $link->weight;
					$averages[$link->subject][1] += $link->reference_value * $link->weight;
				}
			}
			$globalAverage = [0, 0];
			foreach ($averages as $averageKey => $average) {
				$globalAverage[0] += $average[0] / $average[1] * $averageReference;
				$globalAverage[1] += $averageReference;
			}
			$averages['global'] = $globalAverage;	
		}*/
		
		//$cursor = Account::getListV3('teacher', ['n_fn', 'contact_1_id'], ['status' => 'active,committed,contrat_envoye,reconnect_with'], '+name');
		$teachers = [];
		//foreach ($cursor as $teacher_id => $teacher) $teachers[$teacher['contact_1_id']] = $teacher;

		// Retrieve the subject list. As a teacher my subject list is restricted according to my competences
		$subjects = [];
		foreach ($place->getConfig('student/property/school_subject')['modalities'] as $subjectId => $subject) {
			if (!array_key_exists('archive', $subject) || !$subject['archive']) {
				if ($context->hasRole('manager')) $subjects[$subjectId] = $subject;
				else {
					$subjects[$subjectId] = $subject;
				}
			}
		}
		
		if ($request == 'POST') {
			$data = [];
			$data['assessment'] = $this->getRequest()->getPost('assessment');
			$data['evaluation'] = $this->getRequest()->getPost('mention');
			$data['specific_weight'] = $this->getRequest()->getPost('specific_weight');
			$rc = $noteLink->loadData($data);
			if ($rc != 'OK') {
				$this->response->setStatusCode('409');
				$this->response->setReasonPhrase($rc);
			}
			else {

				$updateNote = false;
				if ($this->getRequest()->getPost('date')) {
					$noteLink->note->date = $this->getRequest()->getPost('date');
					$updateNote = true;
				}
				if ($this->getRequest()->getPost('teacher_id')) {
					$noteLink->note->teacher_id = $this->getRequest()->getPost('teacher_id');
					$updateNote = true;
				}
				if ($this->getRequest()->getPost('value')) $noteLink->value = $this->getRequest()->getPost('value');
				else $noteLink->value = null;
				$noteLink->properties['value'] = $noteLink->value;
				$noteLink->update(null);
				if ($updateNote) $noteLink->note->update(null);
				
				// Atomically save
				$connection = NoteLink::getTable()->getAdapter()->getDriver()->getConnection();
				$connection->beginTransaction();
				try {
					$update_time = $this->request->getPost('update_time');
					$noteLink->update($update_time);
					$connection->commit();
					$this->response->setStatusCode('200');
				}
				catch (\Exception $e) {
					$connection->rollback();
					$this->response->setStatusCode('409');
					$this->response->setReasonPhrase('Exception: ' . $e);
					return null;
				}
			}
		}

		if ($request == 'DELETE') {
			$noteLink->delete(null);
			$this->response->setStatusCode('200');
		}
		
		$view = new ViewModel(array(
			'context' => $context,
			'request' => ($this->getRequest()->isPost()) ? 'POST' : (($this->getRequest()->isDelete()) ? 'DELETE' : 'GET'),
			'noteLink' => $noteLink,
    		'places' => Place::getList([]),
    		'teachers' => $teachers,
			'subjects' => $subjects,
			//'averages' => $averages,
			'indicators' => NULL,
			'statusCode' => $this->response->getStatusCode(),
			'reasonPhrase' => $this->response->getReasonPhrase(),
		));
		$view->setTerminal(true);
		return $view;
	}
	
	public function generateReportAction() {
		$context = Context::getCurrent();
		if ($this->request->isPost()) $requestType = 'POST';
		else $requestType = 'GET';
		$groupIds = $this->params()->fromQuery('groups');
		$groups = Account::getList('group', ['id' => $groupIds], null, null);
		$teachers = Account::getList('teacher', ['status' => 'active,committed,contrat_envoye,reconnect_with', 'property_8' => $context->getConfig('student/property/school_year/default')], '+name', null);

		$place_identifier = $this->params()->fromQuery('place_identifier');
		if ($place_identifier) $place = Place::get($place_identifier, 'identifier');
		else $place = Place::get($context->getPlaceId());
		$places = Place::getList([]);

		$schoolYear = $context->getConfig('student/property/school_year/default');
		$schoolPeriods = $place->getConfig('school_periods');
		$currentSchoolPeriod = $context->getCurrentPeriod($schoolPeriods);
		if (!$currentSchoolPeriod) $currentSchoolPeriod = 'q1';

		$statusCode = '200';
		$reasonCode = '';

		if ($this->request = 'POST') {

		}
		
		$view = new ViewModel([
			'context' => $context,
			'statusCode' => $statusCode,
			'reasonCode' => $reasonCode,
			'requestType' => $requestType,
			'places' => $places,
			'groups' => $groups,
			'teachers' => $teachers,
			'currentSchoolPeriod' => $currentSchoolPeriod,
		]);
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

	/**
	 * user_story - note_link/repair: Check that 1 and only 1 average exists per student, period and subject on which there are notes.
	 */
	public function repairAction()
	{
		$context = Context::getCurrent();

		$place_identifier = $this->params()->fromQuery('place_identifier');
		if ($place_identifier) $place = Place::get($place_identifier, 'identifier');
		else $place = Place::get($context->getPlaceId());

		$school_year = $this->params()->fromQuery('school_year');
		if (!$school_year) $school_year = $context->getConfig('student/property/school_year/default');

		$school_period = $this->params()->fromQuery('school_period');
		if (!$school_period) {
			$school_periods = $place->getConfig('school_periods');
			$current_school_period = $context->getCurrentPeriod($school_periods);
		}
		if (!$school_period) $school_period = 'q1';
		
		$where = array('category' => 'evaluation', 'type' => 'note', 'place_id' => $place->id, 'school_year' => $school_year, 'school_period' => $school_period);

		$computedReportLinks = [];
		$existingReportLinks = [];
		
		// Retrieve all the notes in the required scope
		foreach (NoteLink::getList(null, $where, 'id', 'asc', 'search') as $evaluation) {
			
			$computedKey = $evaluation->place_id/* . '_' . $evaluation->group_id*/ . '_' . $evaluation->school_year . '_' . $evaluation->school_period . '_' . $evaluation->subject . '_' . $evaluation->account_id;
			if (!array_key_exists($computedKey, $computedReportLinks)) {
				$computedReportLinks[$computedKey] = ['evaluations' => [], 'reports' => []];
				$computedGlobalKey = $evaluation->place_id/* . '_' . $evaluation->group_id*/ . '_' . $evaluation->school_year . '_' . $evaluation->school_period . '_' . 'global' . '_' . $evaluation->account_id;
				if (!array_key_exists($computedGlobalKey, $computedReportLinks)) {
					$computedReportLinks[$computedGlobalKey] = ['evaluations' => [], 'reports' => []];
					$computedReportLinks[$computedGlobalKey]['evaluations'][] = ['place_id' => $evaluation->place_id, 'group_id' => null, 'school_year' => $evaluation->school_year, 'school_period' => $evaluation->school_period, 'subject' => 'global', 'id' => null, 'note_id' => null, 'account_id' => $evaluation->account_id, 'name' => $evaluation->name, 'value' => null, 'assessment' => null];
				}
			}
			$computedReportLinks[$computedKey]['evaluations'][] = ['place_id' => $evaluation->place_id, 'group_id' => $evaluation->group_id, 'school_year' => $evaluation->school_year, 'school_period' => $evaluation->school_period, 'subject' => $evaluation->subject, 'id' => $evaluation->id, 'note_id' => $evaluation->note_id, 'account_id' => $evaluation->account_id, 'name' => $evaluation->name, 'value' => $evaluation->value, 'assessment' => $evaluation->assessment];
		}

		// Retrieve all the per-student reports in the required scope
		$where['type'] = 'report';
		foreach (NoteLink::getList(null, $where, 'id', 'asc', 'search') as $report) {
		
			$existingKey = $report->place_id/* . '_' . $report->group_id*/ . '_' . $report->school_year . '_' . $report->school_period . '_' . $report->subject . '_' . $report->account_id;
			if (array_key_exists($existingKey, $computedReportLinks)) {
				$computedReportLinks[$existingKey]['reports'][] = ['id' => $report->id, 'place_id' => $report->place_id, 'group_id' => $report->group_id, 'school_year' => $report->school_year, 'school_period' => $report->school_period, 'subject' => $report->subject, 'id' => $report->id, 'note_id' => $report->note_id, 'account_id' => $report->account_id, 'name' => $report->name, 'value' => $report->value, 'assessment' => $report->assessment, 'creation_user' => $report->creation_user, 'row' => $report];
			}
			else {
//				if ($report->subject != 'global') {
					if (!array_key_exists($existingKey, $existingReportLinks)) $existingReportLinks[$existingKey] = [];
					$existingReportLinks[$existingKey][] = ['place_id' => $report->place_id, 'group_id' => $report->group_id, 'school_year' => $report->school_year, 'school_period' => $report->school_period, 'subject' => $report->subject, 'id' => $report->id, 'note_id' => $report->note_id, 'account_id' => $report->account_id, 'name' => $report->name, 'value' => $report->value, 'assessment' => $report->assessment];
					
					// Drop the report not corresponding to any evaluation
//					if (!$report->assessment) $report->drop();
//				}
			}
		}
		
		// Retrieve all the reports in the required scope
		$existingReports = [];
		foreach (Note::getList('evaluation', 'report', $where, 'id', 'asc', 'search', null) as $report) {
		
			$existingKey = $report->place_id . '_' . $report->group_id . '_' . $report->school_year . '_' . $report->school_period . '_' . $report->subject;
//			if ($report->subject != 'global') {
				if (!array_key_exists($existingKey, $existingReports)) $existingReports[$existingKey] = [];
				$existingReports[$existingKey] = ['place_id' => $report->place_id, 'group_id' => $report->group_id, 'school_year' => $report->school_year, 'school_period' => $report->school_period, 'subject' => $report->subject, 'id' => $report->id];
//			}
		}
		
		$result = ['duplicate_or_missing_report' => [], 'report_without_note' => []];

		$removeList = []; // Transient
		foreach ($computedReportLinks as $computedKey => &$computedReport) {
			if (count($computedReport['reports']) != 1) {
				$result['duplicate_or_missing_report'][$computedKey] = &$computedReport;
				if (count($computedReport['reports']) == 0) {
					$existingReportKey = $computedReport['evaluations'][0]['place_id'] . '_' . (int) $computedReport['evaluations'][0]['group_id'] . '_' . $computedReport['evaluations'][0]['school_year'] . '_' . $computedReport['evaluations'][0]['school_period'] . '_' . $computedReport['evaluations'][0]['subject'];
					$newReport = NoteLink::instanciate($computedReport['evaluations'][0]['account_id'], $existingReports[$existingReportKey]['id']);
					$result['duplicate_or_missing_report'][$computedKey]['report_id'] = $newReport->note_id;
					$newReport->add();
				}
				// Transient
				else {
					$assessment = null; $value = null;
					foreach ($computedReport['reports'] as &$report) {
						$update = false;
						if (!$assessment && $report['assessment']) $assessment = $report['assessment'];
						elseif ($assessment && !$report['assessment']) {
							$report['assessment'] = $assessment;
							$update = true;
						}

						if (!$value && $report['value']) $value = $report['value'];
						elseif ($value && !$report['value']) {
							$report['value'] = $value;
							$update = true;
						}
						if ($update && $report['creation_user'] == 83) $report['row']->update(null);
						if ($report['creation_user'] != 83) $removeList[] = $report['id'];
					}
				}
			}
		}
	
		foreach ($existingReportLinks as $existingKey => $existingReport) {
			$result['report_without_note'][] = [$existingKey => $existingReport];
		}

		echo implode(',', $removeList);
		echo json_encode($result, JSON_PRETTY_PRINT);
		return $this->response;
	}
}
