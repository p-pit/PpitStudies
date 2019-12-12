<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Notification;
use PpitCore\Model\Account;
use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
use PpitCore\Model\Document;
use PpitCore\Model\Place;
use PpitCore\Model\Vcard;
use PpitCore\Form\CsrfForm;
use PpitStudies\Model\Note;
use PpitStudies\Model\NoteLink;
use PpitStudies\ViewHelper\SsmlNoteViewHelper;
use Zend\Db\Sql\Where;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NoteController extends AbstractActionController
{
/*    public function indexAction()
    {
    	$context = Context::getCurrent();
		if (!$context->isAuthenticated()) $this->redirect()->toRoute('home');
    	$place = Place::get($context->getPlaceId());
    	$app = $this->params()->fromRoute('app', 'p-pit-studies');
    	$community_id = (int) $context->getCommunityId();

		$menu = $context->getConfig('menus/'.$app)['entries'];
    	$currentEntry = $this->params()->fromQuery('entry', 'account');

		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		
    	return new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getConfig(),
    			'place' => $place,
    			'applicationId' => 'p-pit-studies',
    			'applicationName' => 'P-Pit Studies',
    			'active' => 'application',
    			'community_id' => $community_id,
    			'menu' => $menu,
    			'currentEntry' => $currentEntry,
    			'category' => $category,
    			'type' => $type,
    			'places' => Place::getList(array()),
    	));
    }*/
    
    public function indexV2Action()
    {
    	$context = Context::getCurrent();
		if (!$context->isAuthenticated()) $this->redirect()->toRoute('home');
    	$place = Place::get($context->getPlaceId());
    	$entry = $this->params()->fromRoute('entry', 'homework');

    	// Transient: Serialize a list of the entries from all menus
    	$menuEntries = [];
    	foreach ($context->getApplications() as $applicationId => $application) {
    		if ($context->getConfig('menus/'.$applicationId)) {
    			foreach ($context->getConfig('menus/' . $applicationId)['entries'] as $entryId => $entryDef) {
    				$menuEntries[$entryId] = ['menuId' => $applicationId, 'menu' => $application, 'definition' => $entryDef];
    			}
    		}
    	}
    	$tab = $this->params()->fromRoute('entryId', 'homework');
    	
    	// Retrieve the application
    	$app = $menuEntries[$tab]['menuId'];
    	$applicationName = $context->localize($menuEntries[$tab]['menu']['labels']);
    	 
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		
		$this->layout('/layout/core-layout');
		$this->layout()->setVariables(array(
			'context' => $context,
			'place' => $place,
			'entry' => $entry,
//			'config' => $config,
			'tab' => $tab,
			'app' => $app,
			'applicationName' => $applicationName,
			'pageScripts' => 'ppit-studies/view-controller/note-scripts',
    		'category' => $category,
    		'type' => $type,
		));
		
		return new ViewModel(array(
    		'context' => $context,
    	));
    }
    
    public function getFilters($category, $params)
    {
		$context = Context::getCurrent();
    	
    	// Retrieve the query parameters
    	$filters = array();
    
    	$name = ($params()->fromQuery('name', null));
    	if ($name) $filters['name'] = $name;

    	foreach ($context->getConfig('note/search'.'/'.$category)['main'] as $propertyId => $rendering) {
    
    		$property = ($params()->fromQuery($propertyId, null));
    		if ($property) $filters[$propertyId] = $property;
    		$min_property = ($params()->fromQuery('min_'.$propertyId, null));
    		if ($min_property) $filters['min_'.$propertyId] = $min_property;
    		$max_property = ($params()->fromQuery('max_'.$propertyId, null));
    		if ($max_property) $filters['max_'.$propertyId] = $max_property;
    	}

    	foreach ($context->getConfig('note/search'.'/'.$category)['more'] as $propertyId => $rendering) {

    		$property = ($params()->fromQuery($propertyId, null));
    		if ($property) $filters[$propertyId] = $property;
    		$min_property = ($params()->fromQuery('min_'.$propertyId, null));
    		if ($min_property) $filters['min_'.$propertyId] = $min_property;
    		$max_property = ($params()->fromQuery('max_'.$propertyId, null));
    		if ($max_property) $filters['max_'.$propertyId] = $max_property;
    	}
    	 
    	return $filters;
    }
    
    public function searchV2Action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();

    	$category = $this->params()->fromRoute('category');
    	$type = $this->params()->fromRoute('type');
    	 
    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'places' => Place::getList(array()),
    			'category' => $category,
    			'type' => $type,
    	));
    	$view->setTerminal(true);
    	return $view;
    }
/*
    public function searchAction()
    {
    	return $this->searchV2Action();
    }*/
/*    
    public function getList()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		if ($type == '*') $type = null;
		$params = $this->getFilters($category, $this->params());
		$limit = $this->params()->fromQuery('limit');
		$major = ($this->params()->fromQuery('major', 'date'));
    	$dir = ($this->params()->fromQuery('dir', 'DESC'));
    
    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    
    	// Retrieve the list
    	$notes = Note::getList($category, $type, $params, $major, $dir, $mode, $limit);
		$average = 0;
    	if ($category == 'evaluation') {
			$totalWeight = 0;
    		$maxAverage = 0;
			foreach ($notes as $note) {
				$maxAverage += $note->reference_value;
				$totalWeight += $note->weight;
				if ($note->reference_value) $average += $note->average_note / $note->reference_value * $note->weight;
			}
			$average /= ($totalWeight) ? $totalWeight : 1;
    	}
    	
    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
	    		'places' => Place::getList(array()),
    			'category' => $category,
    			'type' => $type,
    			'notes' => $notes,
				'average' => $average,
    			'mode' => $mode,
    			'params' => $params,
    			'major' => $major,
    			'dir' => $dir,
    	));
    	$view->setTerminal(true);
    	return $view;
    }*/
 /*   
    public function listAction()
    {
    	return $this->getList();
    }*/

    public function listV2Action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		if ($type == '*') $type = null;
		$params = $this->getFilters($category, $this->params());
		$limit = $this->params()->fromQuery('limit');
		$major = ($this->params()->fromQuery('major', 'date'));
    	$dir = ($this->params()->fromQuery('dir', 'DESC'));
    
    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    
    	// Retrieve the list
    	$notes = Note::getList($category, $type, $params, $major, $dir, $mode, $limit);
		$average = 0;
    	if ($category == 'evaluation') {
			$totalWeight = 0;
    		$maxAverage = 0;
			foreach ($notes as $note) {
				$maxAverage += $note->reference_value;
				$totalWeight += $note->weight;
				if ($note->reference_value) $average += $note->average_note / $note->reference_value * $note->weight;
			}
			$average /= ($totalWeight) ? $totalWeight : 1;
    	}
    	
    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
	    		'places' => Place::getList(array()),
    			'category' => $category,
    			'type' => $type,
    			'notes' => $notes,
				'average' => $average,
    			'mode' => $mode,
    			'params' => $params,
    			'major' => $major,
    			'dir' => $dir,
    	));
    	$view->setTerminal(true);
    	return $view;
    }
    
    public function getAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    	$id = $this->params()->fromRoute('id');
    
    	$account_id = (int) $this->params()->fromQuery('account_id');
    
    	$notes = NoteLink::GetList(null, array('category' => 'homework', 'account_id' => $account_id, 'school_year' => $context->getConfig('student/property/school_year/default')), 'date', 'DESC', 'search');

    	echo json_encode($notes, JSON_PRETTY_PRINT);
    	return $this->getResponse();
    }
    
    public function exportAction()
    {
        	// Retrieve the context
    	$context = Context::getCurrent();
    
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		$params = $this->getFilters($category, $this->params());
  		if ($category) $params['category'] = $category;
		
    	$major = ($this->params()->fromQuery('major', 'date'));
    	$dir = ($this->params()->fromQuery('dir', 'DESC'));
    
    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    
    	// Retrieve the list
    	$noteLinks = NoteLink::getList($type, $params, $major, $dir, $mode);
    	
    	// Return the link list
    	$view = new ViewModel(array(
    			'category' => $category,
    			'noteLinks' => $noteLinks,
    	));
    	
   		include 'public/PHPExcel_1/Classes/PHPExcel.php';
   		include 'public/PHPExcel_1/Classes/PHPExcel/Writer/Excel2007.php';

		$workbook = new \PHPExcel;
		(new SsmlNoteViewHelper)->formatXls($workbook, $view);		
		$writer = new \PHPExcel_Writer_Excel2007($workbook);
		
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition:inline;filename=Fichier.xlsx ');
		$writer->save('php://output');
		return $this->response;
    }

    public function exportCsvAction()
    {
    	$context = Context::getCurrent();
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
		$params = $this->getFilters($category, $this->params());
    
    	$major = ($this->params()->fromQuery('major', 'date'));
    	$dir = ($this->params()->fromQuery('dir', 'DESC'));
    
    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    
    	// Retrieve the list
    	$noteLinks = NoteLink::getList($type, $params, $major, $dir, $mode);
    	
    	header('Content-Type: text/csv; charset=utf-8');
    	header("Content-disposition: filename=contact-".date('Y-m-d').".csv");
    	echo "\xEF\xBB\xBF";
    
		foreach($context->getConfig('note/export'.(($category) ? '/'.$category : ''))['properties'] as $propertyId => $column) {
			$property = $context->getConfig('note')['properties'][$propertyId];
			if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
    		print_r($context->localize($property['labels']).';');
    	}
    	print_r("\n");
    
    	foreach ($noteLinks as $noteLink) {
    		$noteLink->properties = $noteLink->getProperties();
			foreach($context->getConfig('note/export'.(($category) ? '/'.$category : ''))['properties'] as $propertyId => $column) {
				$property = $context->getConfig('note')['properties'][$propertyId];
				if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
    			if ($noteLink->properties[$propertyId]) {
    				if ($propertyId == 'place_id') print_r($account->place_caption);
    				elseif ($property['type'] == 'date') print_r($context->decodeDate($noteLink->properties[$propertyId]));
    				elseif ($property['type'] == 'number') print_r($context->formatFloat($noteLink->properties[$propertyId], 2));
    				elseif ($property['type'] == 'select')  print_r((array_key_exists($noteLink->properties[$propertyId], $property['modalities'])) ? $context->localize($property['modalities'][$noteLink->properties[$propertyId]]) : $noteLink->properties[$propertyId]);
    				else print_r($noteLink->properties[$propertyId]);
    			}
    			print_r(';');
    		}
    		print_r("\n");
    	}
    	return $this->response;
    }
/*
    public function updateAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$id = (int) $this->params()->fromRoute('id', 0);
 		$note = Note::get($id);
    	$place = Place::get($note->place_id);
       	$school_periods = $place->getConfig('school_periods');
       	$current_school_period = $context->getCurrentPeriod($school_periods);
    	if (!$note->school_period) $note->school_period = $current_school_period;
 		$action = $this->params()->fromRoute('act', null);

 		$documentList = array();
 		if (array_key_exists('dropbox', $context->getConfig('ppitDocument'))) {
 			require_once "vendor/dropbox/dropbox-sdk/lib/Dropbox/autoload.php";
 			$dropbox = $context->getConfig('ppitDocument')['dropbox'];
 			$dropboxClient = new \Dropbox\Client($dropbox['credential'], $dropbox['clientIdentifier']);
 			try {
 				$properties = $dropboxClient->getMetadataWithChildren($dropbox['folders']['schooling']);
 				if ($properties) foreach ($properties['contents'] as $content) $documentList[] = substr($content['path'], strrpos($content['path'], '/')+1);
 			}
 			catch(\Exception $e) {}
 		}
 		else $dropboxClient = null;

    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	if ($action == 'delete') $message = 'confirm-delete';
    	elseif ($action) $message =  'confirm-update';
    	else $message = null;
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$message = null;
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    		 
    		if ($csrfForm->isValid()) { // CSRF check
    			if ($action != 'delete') {
	    			// Load the input data
	    			$data = array();
	    			$data['place_id'] = $request->getPost('place_id');
	    			if ($context->hasRole('manager') || $context->hasRole('admin')) $data['teacher_id'] = $request->getPost('teacher_id');
	    			if (!array_key_exists('teacher_id', $data) || !$data['teacher_id']) $data['teacher_id'] = $context->getContactId();
	    			$data['class'] = $request->getPost('class');
    				$data['school_period'] = $request->getPost('school_period');
	    			$data['level'] = $request->getPost('level');
	    			$data['subject'] = $request->getPost('subject');
	    			$data['date'] = $request->getPost('date');
	    			$data['type'] = $request->getPost('type');
	    			$data['target_date'] = $request->getPost('target_date');
	    			$data['observations'] = $request->getPost('observations');
	    			$data['document'] = $request->getPost('document');
	    			$data['comment'] = $request->getPost('comment');
	    			$rc = $note->loadData($data);
					if ($rc != 'OK') throw new \Exception('View error');
    			}

    			// Atomically save
    			$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
	    			if (!$note->id) $rc = $note->add();
	    			elseif ($action == 'delete') $rc = $note->delete($request->getPost('update_time'));
	    			else $rc = $note->update($note->update_time);
    				if ($rc != 'OK') {
    					$connection->rollback();
    					$error = $rc;
    				}
    				if (!$error) {
    					$connection->commit();
    					$message = 'OK';
    				}
    			}
    			catch (\Exception $e) {
    				$connection->rollback();
    				throw $e;
    			}
    		}
    	}
    
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'places' => Place::getList(array()),
    			'id' => $id,
    			'action' => $action,
    			'note' => $note,
    			'school_periods' => $school_periods,
    			'dropboxClient' => $dropboxClient,
    			'documentList' => $documentList,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }*/

    public function updateV2Action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$id = (int) $this->params()->fromRoute('id', 0);
    	$note = Note::get($id);
    	$place = Place::get($note->place_id);
    	$school_periods = $place->getConfig('school_periods');
    	$current_school_period = $context->getCurrentPeriod($school_periods);
    	if (!$note->school_period) $note->school_period = $current_school_period;
    	$action = $this->params()->fromRoute('act', null);
    
    	if ($note->document) $document = Document::get($note->document);
    	else $document = null;
    
    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	if ($action == 'delete') $message = 'confirm-delete';
    	elseif ($action) $message =  'confirm-update';
    	else $message = null;
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$message = null;
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    		 
    		if ($csrfForm->isValid()) { // CSRF check
    			if ($action != 'delete') {
    
    				$document_id = $request->getPost('document');
    				$document = Document::get($document_id);
    
    				// Load the input data
    				$data = array();
    				$data['place_id'] = $request->getPost('place_id');
    				if ($context->hasRole('manager') || $context->hasRole('admin')) $data['teacher_id'] = $request->getPost('teacher_id');
    				if (!array_key_exists('teacher_id', $data) || !$data['teacher_id']) $data['teacher_id'] = $context->getContactId();
    				$data['class'] = $request->getPost('class');
    				$data['school_period'] = $request->getPost('school_period');
    				$data['level'] = $request->getPost('level');
    				$data['subject'] = $request->getPost('subject');
    				$data['date'] = $request->getPost('date');
    				$data['type'] = $request->getPost('type');
    				$data['target_date'] = $request->getPost('target_date');
    				$data['document'] = $document_id;
    				$data['observations'] = $request->getPost('observations');
    				$data['comment'] = $request->getPost('comment');
    				$rc = $note->loadData($data);
    				if ($rc != 'OK') throw new \Exception('View error');
    			}
    
    			// Atomically save
    			$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    				if (!$note->id) $rc = $note->add();
    				elseif ($action == 'delete') {
		    			foreach ($note->links as $noteLink) $noteLink->delete(null);
		    			$rc = $note->delete($request->getPost('update_time'));
		    		}
    				else $rc = $note->update($note->update_time);
    				if ($rc != 'OK') {
    					$connection->rollback();
    					$error = $rc;
    				}
    				if (!$error) {
    					$connection->commit();
    					$message = 'OK';
    				}
    			}
    			catch (\Exception $e) {
    				$connection->rollback();
    				throw $e;
    			}
    		}
    	}
    
    	$view = new ViewModel(array(
    		'context' => $context,
    		'config' => $context->getconfig(),
    		'places' => Place::getList(array()),
    		'id' => $id,
    		'action' => $action,
    		'note' => $note,
    		'school_periods' => $school_periods,
    		'document' => $document,
    		'csrfForm' => $csrfForm,
    		'error' => $error,
    		'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }
    
    public function updateEvaluationV2Action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();

    	$id = (int) $this->params()->fromRoute('id', 0);
    	$note = Note::get($id);
    	$place = Place::get($note->place_id);
    	$action = $this->params()->fromRoute('act', null);

    	// Retrieve the teachers
    	$select = Vcard::getTable()->getSelect()->order('n_fn ASC');
    	$where = new Where;
    	$where->notEqualTo('status', 'deleted');
    	$where->like('roles', '%teacher%');
    	$select->where($where);
    	$cursor = Vcard::getTable()->selectWith($select);
    	$contact = null;
    	$teachers = array();
    	foreach ($cursor as $contact) {
    	    if (	!array_key_exists('p-pit-admin', $contact->perimeters) 
    			|| 	!array_key_exists('place_id', $contact->perimeters['p-pit-admin'])) {
    			$teachers[$contact->id] = $contact;
    		}
    		else {
    			foreach ($contact->perimeters['p-pit-admin']['place_id'] as $place_id) {
    				if ($note->place_id == $place_id) $teachers[$contact->id] = $contact;
    			}
    		}
    	}

    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	if ($action == 'delete') $message = 'confirm-delete';
    	elseif ($action) $message =  'confirm-update';
    	else $message = null;
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$message = null;
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    		 
    		if ($csrfForm->isValid()) { // CSRF check
    
    			// Load the input data
    			$data = array();
    			$data['status'] = 'current';
    			$data['place_id'] = $request->getPost('place_id');
    			if ($context->hasRole('manager') || $context->hasRole('admin')) $data['teacher_id'] = $request->getPost('teacher_id');
    			if (!array_key_exists('teacher_id', $data) || !$data['teacher_id']) $data['teacher_id'] = $context->getContactId();
//    			$data['school_year'] = $context->getConfig('student/property/school_year/default');
    			$data['school_period'] = $request->getPost('school_period');
    			$data['class'] = $request->getPost('class');
    		    $teacher_id = $request->getPost('teacher_id');
    			if ($teacher_id) {
    				$teacher = $teachers[$teacher_id];
    				$data['teacher_id'] = $teacher->id;
    			}
    			$data['level'] = $request->getPost('level');
    			$data['subject'] = $request->getPost('subject');
    			$data['date'] = $request->getPost('date');
    			$data['reference_value'] = $request->getPost('reference_value');
    			$data['weight'] = $request->getPost('weight');
    			$data['observations'] = $request->getPost('observations');
    			$data['comment'] = $request->getPost('comment');
    			$noteLinks = array();

    			// In current evaluation mode, in the case where the 'global' subject is selected, compute the average of all the note of the period for each selected student
    			if ($note->type == 'note' && $data['subject'] == 'global') {
    				$computedAverages = Note::computePeriodAverages($data['place_id'], $note->school_year, $data['class'], $data['school_period'], $data['subject']);
    			}
    			 
    			// In report mode, compute the period average for the selected subject and for all the selected students
    			elseif ($note->type == 'report') {
    				$computedAverages = Note::computePeriodAverages($data['place_id'], $note->school_year, $data['class'], $data['school_period'], $data['subject']);
    			}
    		    
    			// In the case of an exam, compute the average of all the note for the exam per class subjects and for all the selected students
    			elseif ($note->type == 'exam') {
    				$examAverages = Note::computeExamAverages($data['place_id'], $note->school_year, $data['class'], $data['level']);
    			}
    			
    			$noteCount = 0; $noteSum = 0; $lowerNote = 999; $higherNote = 0;
    			foreach ($note->links as $noteLink) {
    				$noteLink->audit = array();
    				// Global mention to move to another property
    				$value = $request->getPost('value_'.$noteLink->account_id);
    				if ($value == '') $value = null;
    				$mention = $request->getPost('mention_'.$noteLink->account_id);
    			    if ($note->type == 'note' && $value === null) {
    				    if ($data['subject'] == 'global') {
							$value = $computedAverages[$noteLink->account_id]['global']['note'];
    				    }
    				}
    				elseif ($note->type == 'report' && $value === null) { // 2018-09 : Retour arrière suite pbme ESI de la demande SEA de forcer la moyenne aussi dans le cas où elle n'est pas explicitement effacée par l'utilisateur
    				    if ($data['subject'] == 'global') {
    			    		$value = $noteLink->computeStudentAverage($note->school_year, $data['school_period']);
    			    	}
    					elseif (array_key_exists($noteLink->account_id, $computedAverages)) {
    						$value = $computedAverages[$noteLink->account_id]['global']['note'];
    						$noteLink->audit[] = $computedAverages[$noteLink->account_id]['global']['notes'];
    					}
    					else $value = null;
    			    	if ($value !== null) $value = $value * $data['reference_value'] / 20; //$context->getConfig('student/parameter/average_computation')['reference_value'];
    				}
    			    elseif ($note->type == 'exam' && $value === null) { // 2018-09 : Retour arrière suite pbme ESI de la demande SEA de forcer la moyenne aussi dans le cas où elle n'est pas explicitement effacée par l'utilisateur
    					if (array_key_exists($noteLink->account_id, $examAverages)) {
							$value = $examAverages[$noteLink->account_id]['global']['note'];
							$audit = $examAverages[$noteLink->account_id]['global']['notes'];
						}
    			    	else $value = null;
    			    	if ($value !== null) $value = $value * $data['reference_value'] / 20;
    				}
    				$noteLink->value = $value;
    				$noteLink->evaluation = $mention;
    				$noteLink->assessment = $request->getPost('assessment_'.$noteLink->account_id);
					$noteLink->distribution = array();
					if ($note->type == 'report') {
						if (array_key_exists($noteLink->account_id, $computedAverages)) {
							foreach ($computedAverages[$noteLink->account_id] as $categoryId => $category) {
								$noteLink->distribution[$categoryId] = $category['note'];
							}
						}
					}
    				if ($value !== null) {
    					$noteSum += $value;
    					$noteCount++;
    					if ($value < $lowerNote) $lowerNote = $value;
	    				if ($value > $higherNote) $higherNote = $value;
    				}
    				if ($value !== null || $noteLink->assessment) $noteLinks[] = $noteLink;
    				else $noteLink->delete(null);
    			}
				$note->links = $noteLinks;
    			if ($noteCount > 0) {
    				$data['average_note'] = round($noteSum / $noteCount, 2);
    				$data['lower_note'] = $lowerNote;
    				$data['higher_note'] = $higherNote;
    			}

	    			if ($action != 'delete') {
	    				$rc = $note->loadData($data);
	    				if ($rc == 'Integrity') throw new \Exception('View error');
    					if ($rc == 'Duplicate') $error = $rc;
	    			}
	    			if (!$error) {
	
		    			// Atomically save
		    			$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
		    			$connection->beginTransaction();
		    			try {
		    				if (!$note->id) $rc = $note->add(); // Pas d'ajout de note dans ce controller => A supprimer
		    				elseif ($action == 'delete') $rc = $note->delete($request->getPost('update_time'));
		    				else {
		    					$rc = $note->update($note->update_time);
		    					$note->update($note->update_time); // Ligne redondante à supprimer
		    					foreach ($note->links as $noteLink) $noteLink->update(null);
		    				}
		    				if ($rc != 'OK') {
		    					$connection->rollback();
		    					$error = $rc;
		    				}
		    				if (!$error && $note->type != 'report') {
		    					// Create or update the reports, per subject and global
		    					
		    					// Retrieve the possibly existing report (same year, class, period, subject)
		    					// So it is possible to add students to it, and recompute the group average
		    					
		    					$report = Note::instanciate('report', $data['class']);
		    					$report->place_id = $data['place_id'];
		    					if (array_key_exists($context->getContactId(), $teachers)) $report->teacher_id = $context->getContactId();
		    					
		    					$school_periods = $place->getConfig('school_periods');
		    					$current_school_period = $context->getCurrentPeriod($school_periods);
		    					$note->school_period = $current_school_period;
		    					
		    					// Load the input data
		    					unset($data['date']);
		    					unset($data['teacher_id']);
		    					$data['level'] = null;
		    					$data['reference_value'] = 20;
		    					$data['weight'] = 1;
		    					$data['observations'] = null;
		    					$data['comment'] = null;
		    					$data['criteria'] = array();
		    					
		    					// Compute the new subject average for the period
		    					$newSubjectAverages = Note::computePeriodAverages($data['place_id'], $note->school_year, $data['class'], $data['school_period'], $data['subject']);
		    					
		    					$previousReport = Note::retrieve($data['place_id'], 'evaluation', 'report', $data['class'], $note->school_year, $data['school_period'], $data['subject']);
		    					if ($previousReport) $report = $previousReport; // Notifier que l'évaluation existe est n'accepter l'ajout que de nouveaux élèves sur l'évaluation existante
		    					else $report->links = array();
		    					
				    			foreach ($note->links as $noteLink) {
		    						if (array_key_exists($noteLink->account_id, $report->links)) $reportLink = $report->links[$noteLink->account_id];
    								else $reportLink = NoteLink::instanciate($noteLink->account_id, null);
		    						$audit = [];
		    						$value = $newSubjectAverages[$noteLink->account_id]['global']['note'];
		    						$audit = $newSubjectAverages[$noteLink->account_id]['global']['notes'];
		    						$value = $value * $data['reference_value'] / $context->getConfig('student/parameter/average_computation')['reference_value'];
			    						$reportLink->value = $value;
		    						$reportLink->distribution = array();
		    						foreach ($newSubjectAverages[$noteLink->account_id] as $categoryId => $category) {
		    							if ($categoryId != 'global') $reportLink->distribution[$categoryId] = $category['note'];
		    						}
		    						$reportLink->audit = $audit;
/*		    						if (array_key_exists($reportLink->account_id, $report->links) && $report->links[$reportLink->account_id]->id) {
		    							$report->links[$reportLink->account_id]->delete(null);
		    							unset($report->links[$reportLink->account_id]);
		    						}*/
		    						if (!array_key_exists($reportLink->account_id, $report->links)) $report->links[$reportLink->account_id] = $reportLink;
		    					}
		    					$noteCount = 0; $noteSum = 0; $lowerNote = 999; $higherNote = 0;
		    					foreach ($report->links as $reportLink) {
		    						if ($reportLink->value !== null) {
		    							$noteSum += $reportLink->value;
		    							$noteCount++;
		    							if ($reportLink->value < $lowerNote) $lowerNote = $reportLink->value;
		    							if ($reportLink->value > $higherNote) $higherNote = $reportLink->value;
		    						}
		    					}
		    					if ($noteCount > 0) {
		    						$data['average_note'] = round($noteSum / $noteCount, 2);
		    						$data['lower_note'] = $lowerNote;
		    						$data['higher_note'] = $higherNote;
		    					};
		    					if (count($report->links)) {
		    						$rc = $report->loadData($data);
		    						if ($rc == 'Integrity') throw new \Exception('View error');
		    						if ($rc == 'Duplicate') $error = $rc;
		    						else {
		    							if ($report->id) $rc = $report->update(null);
		    							else $rc = $report->add();
		    							if ($rc != 'OK') {
		    								$connection->rollback();
		    								$error = $rc;
		    							}
		    							// Save the note at the student level
		    							else foreach ($report->links as $reportLink) {
		    								if (!$reportLink->id) {
		    									$reportLink->note_id = $report->id;
		    									$rc = $reportLink->add();
		    								}
		    								else $rc = $reportLink->update(null);
		    								if ($rc != 'OK') {
		    									$connection->rollback();
		    									$error = $rc;
		    									break;
		    								}
		    							}
		    						}
		    					}
		    					
		    					// Compute the new global average
		    					$newGlobalAverages = Note::computePeriodAverages($data['place_id'], $note->school_year, $data['class']);
		    					$report->id = null;
		    					 
		    					$previousReport = Note::retrieve($data['place_id'], 'evaluation', 'report', $data['class'], $note->school_year, $data['school_period'], 'global');
		    					if ($previousReport) $report = $previousReport; // A faire : Notifier que l'évaluation existe est n'accepter l'ajout que de nouveaux élèves sur l'évaluation existante
		    					else $report->links = array();
		    					
		    					$data['subject'] = 'global';
		    					
				    			foreach ($note->links as $noteLink) {
		    						if (array_key_exists($noteLink->account_id, $report->links)) $reportLink = $report->links[$noteLink->account_id];
    								else $reportLink = NoteLink::instanciate($noteLink->account_id, null);
		    						$audit = [];
		    						$value = $newGlobalAverages[$noteLink->account_id]['global']['note'];
		    						$audit = $newGlobalAverages[$noteLink->account_id]['global']['notes'];
		    						$value = $value * $data['reference_value'] / $context->getConfig('student/parameter/average_computation')['reference_value'];
		    						$reportLink->value = $value;
		    						$reportLink->distribution = array();
		    						foreach ($newGlobalAverages[$noteLink->account_id] as $categoryId => $category) {
		    							if ($categoryId != 'global') $reportLink->distribution[$categoryId] = $category['note'];
		    						}
		    						$reportLink->audit = $audit;
/*		    						if (array_key_exists($reportLink->account_id, $report->links) && $report->links[$reportLink->account_id]->id) {
		    							$report->links[$reportLink->account_id]->delete(null);
		    							unset($report->links[$reportLink->account_id]);
		    						}*/
		    						$report->links[$reportLink->account_id] = $reportLink;
		    					}
		    					$noteCount = 0; $noteSum = 0; $lowerNote = 999; $higherNote = 0;
		    					foreach ($report->links as $reportLink) {
		    						if ($reportLink->value !== null) {
		    							$noteSum += $reportLink->value;
		    							$noteCount++;
		    							if ($reportLink->value < $lowerNote) $lowerNote = $reportLink->value;
		    							if ($reportLink->value > $higherNote) $higherNote = $reportLink->value;
		    						}
		    					}
		    					if ($noteCount > 0) {
		    						$data['average_note'] = round($noteSum / $noteCount, 2);
		    						$data['lower_note'] = $lowerNote;
		    						$data['higher_note'] = $higherNote;
		    					};
		    					if (count($report->links)) {
		    						$rc = $report->loadData($data);
		    						if ($rc == 'Integrity') throw new \Exception('View error');
		    						if ($rc == 'Duplicate') $error = $rc;
		    						else {
		    							if ($report->id) $rc = $report->update(null);
		    							else $rc = $report->add();
		    							if ($rc != 'OK') {
		    								$connection->rollback();
		    								$error = $rc;
		    							}
		    							// Save the note at the student level
		    							else foreach ($report->links as $reportLink) {
		    								if (!$reportLink->id) {
		    									$reportLink->note_id = $report->id;
		    									$rc = $reportLink->add();
		    								}
		    								else $rc = $reportLink->update(null);
		    								if ($rc != 'OK') {
		    									$connection->rollback();
		    									$error = $rc;
		    									break;
		    								}
		    							}
		    						}
		    					}
		    				}
		    				if (!$error) {
		    					$connection->commit();
		    					$message = 'OK';
		    				}
		    			}
		    			catch (\Exception $e) {
		    				$connection->rollback();
		    				throw $e;
		    			}
	    			}
    		}
    	}
    
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'places' => Place::getList(array()),
    			'teachers' => $teachers,
    			'id' => $id,
    			'action' => $action,
    			'note' => $note,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }
/*
    public function repriseAction()
    {
    	$context = Context::getCurrent();
    	$select = Note::getTable()->getSelect()->where(['status' => 'deleted']);
    	$cursor = Note::getTable()->selectWith($select);
	    foreach ($cursor as $note) {
	    	$select = NoteLink::getTable()->getSelect()->where(['note_id' => $note->id]);
    		$cursor = NoteLink::getTable()->selectWith($select);
	    	foreach ($cursor as $noteLink) {
	    		if ($noteLink->status != 'deleted') {
	    			print_r($note->id . ' ' . $noteLink->id . "\n");
	    			$noteLink->delete(null);
	    		}
	    	}
	    }
	    return $this->response;
    }*/
/*
	    public function repriseAction()
	    {
	    	$context = Context::getCurrent();
	    	$place_identifier = $this->params()->fromRoute('place_identifier');
	    	$place = Place::get($place_identifier, 'identifier');
	    	$school_year = $this->params()->fromQuery('school_year');
	    	$school_period = $this->params()->fromQuery('school_period');
	    	$where = array();
	    	if ($place_identifier) $where['place_id'] = $place->id;
	    	if ($school_year) $where['school_year'] = $school_year;
	    	if ($school_period) $where['school_period'] = $school_period;
	    	foreach (Note::getList('evaluation', 'report', $where, 'id', 'asc', 'search') as $note) {
//		    	$note->links = array();
		    	$select = NoteLink::getTable()->getSelect()
		    				->join('core_account', 'core_account.id = student_note_link.account_id', array(), 'left')
		    				->join('core_vcard', 'core_vcard.id = core_account.contact_1_id', array('n_fn'), 'left')
	    					->where(array('note_id' => $note->id, 'student_note_link.status != ?' => 'deleted'));
		    				$cursor = NoteLink::getTable()->selectWith($select);
		    		$computedAverages = Note::computePeriodAverages($note->place_id, $note->school_year, $note->class, $note->school_period, $note->subject);
			    	foreach($cursor as $noteLink) {
//						$note->links[] = $noteLink;
						$audit = array();
						$distribution = array();
						if (array_key_exists($noteLink->account_id, $computedAverages)) {
		    				$value = $computedAverages[$noteLink->account_id]['global']['note'];
							$value = round($value * $note->reference_value / 20 , 2);
//							$value = round($value * $note->reference_value / $context->getConfig('student/parameter/average_computation')['reference_value'], 2);
    						$audit[] = $computedAverages[$noteLink->account_id]['global']['notes'];
		    				foreach ($computedAverages[$noteLink->account_id] as $categoryId => $category) {
		    					if ($categoryId != 'global') $distribution[$categoryId] = $category['note'];
							}
							if (round($value, 2) != round($noteLink->value, 2) || count($distribution) != count($noteLink->distribution)) {
								print_r($note->type.' Note: '.$note->id.' Link: '.$noteLink->id.' Account: '.$noteLink->account_id.' '.$note->class.' '.$note->subject."\n");
								print_r('New: '.$value."\n");
								print_r($distribution);
								print_r('Old: '.$noteLink->value."\n");
								print_r($noteLink->distribution);
								$noteLink->value = $value;
								$noteLink->distribution = $distribution;
								$noteLink->audit = $audit;
//								$noteLink->update(null);
							}
						}
					}
//		    	}
	    	}
	    	return $this->response;
	    }*/

    // Identify n-uplets of average for a same account + school year + period + subject, regardless of the class => Keep the one that has an assessment
	public function repriseAction()
	{
		$context= Context::getCurrent();
		$school_year = $this->params()->fromQuery('school_year', '2019-2020');
		
		$cursor = NoteLink::getList('report', ['school_year' => $school_year], 'id', 'ASC', 'search');
		$noteLinks = [];
		foreach ($cursor as $noteLink) {
			if ($noteLink->class != $noteLink->account_class) {
				$noteLinks[$noteLink->id] = $noteLink;
				echo $noteLink->id . ';' . $noteLink->note_id . ';' . $noteLink->place_id . ';' . $noteLink->account_id . ';' . $noteLink->name . ';' . $noteLink->school_period . ';' . $noteLink->class . ';' . $noteLink->account_class . ';' . $noteLink->subject . ';' . (($noteLink->assessment) ? 'Commentaire...' : '') . ';' . "\n";
			}
		}
		return $this->response;
		
		foreach ($noteLinks as $noteLink) {
			$nUplets = NoteLink::getList('report', ['school_year' => $school_year, 'account_id' => $noteLink->account_id, 'school_period' => $noteLink->school_period, 'subject' => $noteLink->subject], 'id', 'ASC', 'search');
			if (count($nUplets > 1)) {
				foreach ($nUplets as $row) {
					echo $row->id . ';' . $row->note_id . ';' . $row->place_id . ';' . $row->account_id . ';' . $row->name . ';' . $row->school_period . ';' . $row->subject . ';' . $row->class . ';' . $row->account_class . ';' . (($row->assessment) ? 'Commentaire...' : '') . ';' . "\n";
				}
				echo "\n";
			}
		}
		return $this->response;
	}
}
