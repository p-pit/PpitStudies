<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Notification;
use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
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
    public function indexAction()
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

    public function searchAction()
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

    public function getList()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
		$category = $this->params()->fromRoute('category');
		$type = $this->params()->fromRoute('type');
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
    
    public function listAction()
    {
    	return $this->getList();
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
    		print_r($property['labels'][$context->getLocale()].';');
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
    				elseif ($property['type'] == 'select')  print_r((array_key_exists($noteLink->properties[$propertyId], $property['modalities'])) ? $property['modalities'][$noteLink->properties[$propertyId]][$context->getLocale()] : $noteLink->properties[$propertyId]);
    				else print_r($noteLink->properties[$propertyId]);
    			}
    			print_r(';');
    		}
    		print_r("\n");
    	}
    	return $this->response;
    }

    public function updateAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$id = (int) $this->params()->fromRoute('id', 0);
 		$note = Note::get($id);
    	$place = Place::get($note->place_id);
 		$school_period = $context->getCurrentPeriod($place->getConfig('school_periods'));
		if (!$note->school_period) $note->school_period = $school_period;
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
    			'dropboxClient' => $dropboxClient,
    			'documentList' => $documentList,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function updateEvaluationAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();

    	$id = (int) $this->params()->fromRoute('id', 0);
    	$note = Note::get($id);
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
    			    	if ($value !== null) $value = $value * $data['reference_value'] / $context->getConfig('student/parameter/average_computation')['reference_value'];
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
		    				if (!$note->id) $rc = $note->add();
		    				elseif ($action == 'delete') $rc = $note->delete($request->getPost('update_time'));
		    				else {
		    					$rc = $note->update($note->update_time);
		    					$note->update($note->update_time);
		    					foreach ($note->links as $noteLink) $noteLink->update(null);
		    				}
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
    	$select = NoteLink::getTable()->getSelect()
    		->join('student_note', 'student_note.id = student_note_link.note_id', array('note_status' => 'status', 'type', 'school_year', 'level', 'class', 'school_period', 'subject', 'date'), 'left')
    		->join('core_vcard', 'core_vcard.id = student_note.teacher_id', array('n_fn'), 'left')
			->join('core_user', 'core_user.user_id = student_note_link.update_user', array(), 'left')
			->join(array('user_vcard' => 'core_vcard'), 'user_vcard.id = core_user.vcard_id', array('user_n_fn' => 'n_fn'), 'left')
    		->join('core_account', 'core_account.id = student_note_link.account_id', array('name'), 'left')
    		->join(array('student_vcard' => 'core_vcard'), 'student_vcard.id = core_account.contact_1_id', array('name' => 'n_fn'), 'left')
    		->order(array('student_note.type', 'student_note.id', 'student_note_link.account_id'));
		$where = new Where;
		$where->equalTo('student_note.status', 'current');
		$where->equalTo('student_note.category', 'evaluation');
		$select->where($where);
		$cursor = NoteLink::getTable()->selectWith($select);
		header('Content-Type: text/csv; charset=utf-8');
		header("Content-disposition: filename=order-".date('Y-m-d').".csv");
		echo "\xEF\xBB\xBF";
		echo 'id;note_id;Professeur;Professeur initial;account_id;Elève;Type;Année scolaire;Type d\'évaluation;Classe;Période;Matière;Date;Note;Appréciation;'."\n";
		$previousKey = '';
		$previousLink = null;
		$lastPrinted = null;
		foreach ($cursor as $noteLink) {
			$key = $noteLink->type.'_'.$noteLink->note_id.'_'.$noteLink->account_id;
//			if (!$noteLink->subject) {
			if ($key == $previousKey) {
				if ($previousLink && $previousLink->id != $lastPrinted) {
					echo
					$previousLink->id.';'.
					$previousLink->note_id.';'.
					$previousLink->n_fn.';'.
					$previousLink->user_n_fn.';'.
					$previousLink->account_id.';'.
					$previousLink->name.';'.
					$previousLink->type.';'.
					$previousLink->school_year.';'.
					((!$previousLink->level) ? $previousLink->level : $context->getConfig('student/property/evaluationCategory')['modalities'][$previousLink->level][$context->getLocale()]).';'.
					((!$previousLink->class) ? $previousLink->class : $context->getConfig('student/property/class')['modalities'][$previousLink->class][$context->getLocale()]).';'.
					$previousLink->school_period.';'.
					(($previousLink->subject == 'global') ? $previousLink->subject : $context->getConfig('student/property/school_subject')['modalities'][$previousLink->subject][$context->getLocale()]).';'.
					$previousLink->date.';'.
					$previousLink->value.';'.
					$previousLink->assessment.';'.
					"\n";
				}
				$lastPrinted = $noteLink->id;
	    		echo 
	      		$noteLink->id.';'.
	      		$noteLink->note_id.';'.
	      		$noteLink->n_fn.';'.
	    		$noteLink->user_n_fn.';'.
	    		$noteLink->account_id.';'.
	    		$noteLink->name.';'.
	    		$noteLink->type.';'.
	    		$noteLink->school_year.';'.
	    		((!$noteLink->level) ? $noteLink->level : $context->getConfig('student/property/evaluationCategory')['modalities'][$noteLink->level][$context->getLocale()]).';'.
	    		((!$noteLink->class) ? $noteLink->class : $context->getConfig('student/property/class')['modalities'][$noteLink->class][$context->getLocale()]).';'.
	    		$noteLink->school_period.';'.
	    		(($noteLink->subject == 'global') ? $noteLink->subject : $context->getConfig('student/property/school_subject')['modalities'][$noteLink->subject][$context->getLocale()]).';'.
	    		$noteLink->date.';'.
	    		$noteLink->value.';'.
	    		$noteLink->assessment.';'.
	    		"\n";
			}
			$previousKey = $key;
			$previousLink = $noteLink;
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
		    	if ($note->subject == 'global') {
		    		foreach($cursor as $noteLink) {
						$value = $noteLink->computeStudentAverage($note->school_year, $note->school_period);
						$value = round($value * $note->reference_value / $context->getConfig('student/parameter/average_computation')['reference_value'], 2);
						if ($value != $noteLink->value) {
							print_r($note->type.' '.$noteLink->id.' '.$note->place_id.' '.$note->class.' '.$note->subject."\n");
							print_r('New: '.$value."\n");
							print_r('Old: '.$noteLink->value."\n");
							$noteLink->value = $value;
//							$noteLink->update(null);
			    		}
/*						if ($noteLink->value) {
							print_r($note->type.' '.$noteLink->id.' '.$note->place_id.' '.$note->class.' '.$note->subject."\n");
							$noteLink->evaluation = $noteLink->value;
							print_r('Evaluation: '.$noteLink->evaluation."\n");
							$noteLink->value = null;
							$noteLink->update(null);
			    		}*/
/*		    		}
		    	}
		    	else {
		    		$computedAverages = Note::computePeriodAverages($note->place_id, $note->school_year, $note->class, $note->school_period, $note->subject);
			    	foreach($cursor as $noteLink) {
//						$note->links[] = $noteLink;
						$audit = array();
						$distribution = array();
						if (array_key_exists($noteLink->account_id, $computedAverages)) {
		    				$value = $computedAverages[$noteLink->account_id]['global']['note'];
		    				$audit[] = $computedAverages[$noteLink->account_id]['global']['notes'];
		    				foreach ($computedAverages[$noteLink->account_id] as $categoryId => $category) {
		    					$distribution[$categoryId] = $category['note'];
							}
							if (round($value, 2) != round($noteLink->value, 2) || count($distribution) != count($noteLink->distribution)) {
								print_r($note->type.' '.$noteLink->id.' '.$note->place_id.' '.$note->class.' '.$note->subject."\n");
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
		    	}
	    	}
	    	return $this->response;
	    }*/
	
	public function repriseAction()
	{
		$cursor = array(
			"334",
			"335",
			"336",
			"400",
			"401",
			"402",
			"408",
			"409",
			"410",
			"411",
			"412",
			"413",
			"414",
			"420",
			"444",
			"567",
			"606",
			"607",
			"608",
			"609",
			"684",
			"740",
			"743",
			"744",
			"792",
			"870",
			"887",
			"888",
			"982",
			"983",
			"1022",
			"1023",
			"1033",
			"1035",
			"1042",
			"1043",
			"1044",
			"1045",
			"1046",
			"1047",
			"1214",
			"1215",
			"1266",
			"1268",
			"1271",
			"1272",
			"1292",
			"1293",
			"1326",
			"1327",
			"1352",
			"1441",
			"1442",
			"1467",
			"1478",
			"1479",
			"1480",
			"1498",
			"1551",
			"1584",
			"1585",
			"1589",
			"1591",
			"1698",
			"1699",
			"1745",
			"1746",
			"1810",
			"1811",
			"1814",
			"1815",
			"1830",
			"1832",
			"2024",
			"2033",
			"2036",
			"2037",
			"2038",
			"2040",
			"2172",
			"2175",
			"2177",
			"2179",
			"2180",
			"2193",
			"2197",
			"2198",
			"2234",
			"2305",
			"2349",
			"2350",
			"2358",
			"2359",
			"2365",
			"2381",
			"4010",
			"2383",
			"2474",
			"2475",
			"2543",
			"2544",
			"2545",
			"2546",
			"2636",
			"2637",
			"2652",
			"2785",
			"2786",
			"2787",
			"2788",
			"2815",
			"2817",
			"2829",
			"2838",
			"2852",
			"2856",
			"3035",
			"3156",
			"3188",
			"3189",
			"3195",
			"3196",
			"3197",
			"3198",
			"3199",
			"3200",
			"3202",
			"3204",
			"3206",
			"3209",
			"3211",
			"3212",
			"3266",
			"3267",
			"3268",
			"3289",
			"3291",
			"3292",
			"3307",
			"3308",
			"3309",
			"3310",
			"3311",
			"3312",
			"3380",
			"3383",
			"3389",
			"3392",
			"3472",
			"3505",
			"3509",
			"3512",
			"3528",
			"3536",
			"3584",
			"3594",
			"3619",
			"3620",
			"3624",
			"3713",
			"3714",
			"3760",
			"3761",
			"4021",
			"3762",
			"3794",
			"3801",
			"3802",
			"3803",
			"3804",
			"3827",
			"3828",
			"3829",
			"3830",
			"2382",
			"4016",
			"3860",
			"3873",
			"3875",
			"3876",
			"3887",
			"3888",
			"3889",
			"3962",
			"4024",
			"3963",
			"3966",
			"4026",
			"4069",
			"4070",
			"4071",
			"4074",
			"4075",
			"4077",
			"4090",
			"4091",
			"4095",
			"4096",
			"4098",
			"4099",
			"4102",
			"4114",
			"4248",
			"4251",
			"4252",
			"4274",
			"4275",
			"4329",
			"4528",
			"4542",
			"4543",
			"4674",
			"4675",
			"4684",
			"4688",
			"4692",
			"4693",
			"4828",
			"4829",
			"4830",
			"4831",
			"4893",
			"4965",
			"4966",
			"4969",
			"4970",
			"4976",
			"5029",
			"5030",
			"5070",
			"5073",
			"5141",
			"5142",
			"5225",
			"5234",
			"5236",
			"5393",
			"5396",
			"5397",
			"5417",
			"5418",
			"5429",
			"5430",
			"5434",
			"5784",
			"5789",
			"5791",
			"5792",
			"5795",
			"5885",
			"5917",
			"5918",
			"5919",
			"5920",
			"5929",
			"5933",
			"5937",
			"6234",
			"6235",
			"6236",
			"6237",
			"6238",
			"6239",
			"6246",
			"6247",
			"6270",
			"6271",
			"6272",
			"6273",
			"6281",
			"6283",
			"6284",
			"6288",
			"6289",
			"6290",
			"6530",
			"6531",
			"6532",
			"6533",
			"6535",
			"6536",
			"6589",
			"6620",
			"6621",
			"6625",
			"6626",
			"6628",
			"6635",
			"6636",
			"6642",
			"6659",
			"6660",
			"6726",
			"6740",
			"6787",
			"6806",
			"6815",
			"6816",
			"6857",
			"6894",
			"6895",
			"6896",
			"6897",
			"6917",
			"6918",
			"7026",
			"7027",
			"7196",
			"7197",
			"7198",
			"7226",
			"7227",
			"7233",
			"7571",
			"7573",
			"7574",
			"7578",
			"7615",
			"7616",
			"7617",
			"7618",
			"7631",
			"7634",
			"7635",
			"7640",
			"7653",
			"7655",
			"7663",
			"7665",
			"7679",
			"7703",
			"7705",
			"7710",
			"7744",
			"7745",
			"7746",
			"7850",
			"7854",
			"7855",
			"7856",
			"7857",
			"7858",
			"7860",
			"7862",
			"7863",
			"7864",
			"7865",
			"7866",
			"7867",
			"7870",
			"7877",
			"8110",
			"8421",
			"8446",
			"8447",
			"8449",
			"8451",
			"8453",
			"8458",
			"8534",
			"8539",
			"8540",
			"8542",
			"8546",
			"8691",
			"8692",
			"8696",
			"8736",
			"8737",
			"9010",
			"9013",
			"9016",
			"9018",
			"9023",
			"9026",
			"9036",
			"9110",
			"9112",
			"9114",
			"9117",
			"9122",
			"9126",
			"9128",
			"9142",
			"9179",
			"9614",
			"9640",
			"9718",
			"9723",
			"9861",
			"9862",
			"9886",
			"9908",
			"9909",
			"9932",
			"9934",
			"10090",
			"10092",
			"10104",
			"10148",
			"10202",
			"10548",
			"10591",
			"10592",
			"10593",
			"10594",
			"10595",
			"10648",
			"10650",
			"10652",
			"10683",
			"10693",
			"10699",
			"10706",
			"10707",
			"10714",
			"10739",
			"10743",
			"10772",
			"10775",
			"10776",
			"10813",
			"11276",
			"11484",
			"11493",
			"11676",
			"11678",
			"11753",
			"11758",
			"11823",
			"11842",
			"11844",
			"11847",
			"12178",
			"12179",
			"12243",
			"12244",
			"12245",
			"12246",
			"12247",
			"12249",
			"12250",
			"12256",
			"12390",
			"12475",
			"12476",
			"12549",
			"12550",
			"12552",
			"12554",
			"12555",
			"12556",
			"12779",
			"12784",
			"12833",
			"12834",
			"12837",
			"12838",
			"12892",
			"12893",
			"12894",
			"12895",
			"12896",
			"12897",
			"12939",
			"12943",
			"12944",
			"13000",
			"13001",
			"13002",
			"13003",
			"13047",
			"13048",
			"13050",
			"13051",
			"13188",
			"13189",
			"13190",
			"13192",
			"13293",
			"13294",
			"13319",
			"13320",
			"13423",
			"13424",
			"13425",
			"13426",
			"13427",
			"13460",
			"13461",
			"13547",
			"13557",
			"13558",
			"13600",
			"13602",
			"13603",
			"13604",
			"13606",
			"13611",
			"13612",
			"13634",
			"13817",
			"13818",
			"13819",
			"13820",
			"13821",
			"13837",
			"13839",
			"13842",
			"13861",
			"13926",
			"13927",
			"13945",
			"13946",
			"13972",
			"13973",
			"14070",
			"14071",
			"14176",
			"14177",
			"14338",
			"14339",
			"14416",
			"14417",
			"14418",
			"14419",
			"14443",
			"14461",
			"14464",
			"14467",
			"14469",
			"14481",
			"14482",
			"14484",
			"14600",
			"14635",
			"14733",
			"14789",
			"14844",
			"14958",
			"14959",
			"14962",
			"14963",
			"14972",
			"15328",
			"15330",
			"15351",
			"15354",
			"15355",
			"15357",
			"15358",
			"15359",
			"15432",
			"15433",
			"15434",
			"16255",
			"16269",
			"16362",
			"16363",
			"16364",
			"16365",
			"16516",
			"16517",
			"16537",
			"16539",
			"16545",
			"16561",
			"17344",
			"17495",
			"17496",
			"17497",
			"17501",
			"17502",
			"17538",
			"17556",
			"17590",
			"17591",
			"17595",
			"17596",
			"17597",
			"17639",
			"17655",
			"18106",
			"18109",
			"18110",
			"18111",
			"18119",
			"18135",
			"18136",
			"18146",
			"18196",
			"18200",
			"18213",
			"18245",
			"18248",
			"18249",
			"18250",
			"18330",
			"18334",
			"18335",
			"18473",
			"18494",
			"18495",
			"18496",
			"18935",
			"18954",
			"18956",
			"18957",
			"18960",
			"19114",
			"19184",
			"19219",
			"19220",
			"19555",
			"19638",
			"19639",
			"19661",
			"19662",
			"19664",
			"19665",
			"19667",
			"19668",
			"19819",
			"19899",
			"19900",
			"19903",
			"19904",
			"19905",
			"19906",
			"19907",
			"19908",
			"19909",
			"19910",
			"19912",
			"19937",
			"19954",
			"19960",
			"20000",
			"20002",
			"20124",
			"20126",
			"20137",
			"20147",
			"20148",
			"20194",
			"20198",
			"20200",
			"20201",
			"20223",
			"20269",
			"20270",
			"20272",
			"20274",
			"20276",
			"20277",
			"20279",
			"20280",
			"20282",
			"20283",
			"20329",
			"20356",
			"20574",
			"20575",
			"20582",
			"20625",
			"20630",
			"20683",
			"20684",
			"20698",
			"20703",
			"20728",
			"20729",
			"20981",
			"20982",
			"20985",
			"20989",
			"21083",
			"21114",
			"21133",
			"21134",
			"21135",
			"21638",
			"21639",
			"21641",
			"21644",
			"21815",
			"22046",
			"22048",
			"22118",
			"22356",
			"22454",
			"22629",
			"22630",
			"22808",
			"22809",
			"22810",
			"23030",
			"23031",
			"23032",
			"23044",
			"23115",
			"23116",
			"23239",
			"23240",
			"23372",
			"23398",
			"23748",
			"23749",
			"24079",
			"24080",
			"24092",
			"24618",
			"24677",
			"24679",
			"24967",
			"24968",
			"24969",
			"24970",
			"24971",
			"25345",
			"25346",
			"25366",
			"25508",
			"25545",
			"25546",
			"25846",
			"25848",
			"26269",
			"26270",
			"26271",
			"26272",
			"26365",
			"26366",
			"26462",
			"26535",
			"26601",
			"26602",
			"26603",
			"26604",
			"26605",
			"26606",
			"26662",
			"26744",
			"26746",
			"26766",
			"26902",
			"26903",
			"26904",
			"26948",
			"26950",
			"27328",
			"27329",
			"27330",
			"27331",
			"27332",
			"27333",
			"27335",
			"27336",
			"27337",
			"27338",
			"27526",
			"27527",
			"27730",
			"27733",
			"27735",
			"27737",
			"27745",
			"27920",
			"27921",
			"27922",
			"27923",
			"27927",
			"28991",
			"28992",
			"29094",
			"29095",
			"29096",
			"29131",
			"29132",
			"29197",
			"29198",
			"29199",
			"29200",
			"29202",
			"29203",
			"29279",
			"29280",
			"29281",
			"29344",
			"29345",
			"29436",
			"29437",
			"29438",
			"29441",
			"29518",
			"29527",
			"29528",
			"29529",
			"29530",
			"29532",
			"29533",
			"29596",
			"29597",
			"29598",
			"29599",
			"29638",
			"29639",
			"29640",
			"29641",
			"29642",
			"29643",
			"29644",
			"29645",
			"29646",
			"29647",
			"29648",
			"29649",
			"29650",
			"29651",
			"29652",
			"29653",
			"29654",
			"29655",
			"29656",
			"29657",
			"29664",
			"29666",
			"29706",
			"29707",
			"29738",
			"29788",
			"29789",
			"29791",
			"29913",
			"29914",
			"29915",
			"29916",
			"29917",
			"29918",
			"29919",
			"29920",
			"29923",
			"29937",
			"29957",
			"29958",
			"29959",
			"29960",
			"29962",
			"29963",
			"29964",
			"29965",
			"29966",
			"29967",
			"30007",
			"30008",
			"30009",
			"30044",
			"30045",
			"30046",
			"30051",
			"30064",
			"30065",
			"30066",
			"30070",
			"30071",
			"30072",
			"30179",
			"30324",
			"30768",
			"31148",
			"31441",
			"31444",
			"31445",
			"31446",
			"31447",
			"31586",
			"31587",
			"31676",
			"31677",
			"31720",
			"31796",
			"31797",
			"31798",
			"31848",
			"31849",
			"31943",
			"31944",
			"31945",
			"31946",
			"32289",
			"32290",
			"32291",
			"32292",
			"32491",
			"32492",
			"32506",
			"32507",
			"32754",
			"32806",
			"32807",
			"32808",
			"32809",
			"32810",
			"32837",
			"32841",
			"32847",
			"33224",
			"33225",
			"33226",
			"33227",
			"33240",
			"33241",
			"33242",
			"33448",
			"33449",
			"33450",
			"33496",
			"33497",
			"33515",
			"33603",
			"33679",
			"33720",
			"33721",
			"33947",
			"33948",
			"33949",
			"34102",
			"34103",
			"34104",
			"34105",
			"34132",
			"34475",
			"34476",
			"34484",
			"34485",
			"34486",
			"34487",
			"34496",
			"34497",
			"34604",
			"34605",
			"34800",
			"34980",
			"35243",
			"35244",
			"35245",
			"35246",
			"35247",
			"35248",
			"35249",
			"35250",
			"35262",
			"35266",
			"35267",
			"35268",
			"35281",
			"35323",
			"35324",
			"35325",
			"35326",
			"35327",
			"35328",
			"35447",
			"35563",
			"35564",
			"35723",
			"35815",
			"35852",
			"35950",
			"36032",
			"36033",
			"36108",
			"36109",
			"36110",
			"36170",
			"36171",
			"36247",
			"36258",
			"36259",
			"36260",
			"36262",
			"36264",
			"36265",
			"36266",
			"36267",
			"36269",
			"36706",
			"36707",
			"36708",
			"36709",
			"36882",
			"36883",
			"36884",
			"36885",
			"36907",
			"36908",
			"37188",
			"37189",
			"37226",
			"37250",
			"37251",
			"37494",
			"37495",
			"37555",
			"37841",
			"37857",
			"37858",
			"37859",
			"38255",
			"38256",
			"38335",
			"38336",
			"38338",
			"38371",
			"38372",
			"38407",
			"38408",
			"38610",
			"38614",
			"38713",
			"38716",
			"38719",
			"38919",
			"38920",
			"39107",
			"39108",
			"39110",
			"39215",
			"39216",
			"39261",
			"39265",
			"39568",
			"39739",
			"39740",
			"39886",
			"39889",
			"40309",
			"40310",
			"40323",
			"40499",
			"40602",
			"40780",
			"40816",
			"40907",
			"40908",
			"40993",
			"40994",
			"41230",
			"41231",
			"41232",
			"41440",
			"41569",
			"41690",
			"41691",
			"41692",
			"41724",
			"41725",
			"42184",
			"42191",
			"42445",
			"42446",
			"42566",
			"42568",
			"43191",
			"43364",
			"43365",
			"43881",
			"43882",
			"43883",
			"43884",
			"43886",
			"43927",
			"43928",
			"44350",
			"44352",
			"44354",
			"44435",
			"44436",
			"44821",
			"44918",
			"44938",
			"44939",
			"45169",
			"45170",
			"45548",
			"45549",
			"45550",
			"45551",
			"45877",
			"45878",
			"45919",
			"45920",
			"45960",
			"46073",
			"46208",
			"46211",
			"46214",
			"46422",
			"46423",
			"46424",
			"46425",
			"46601",
			"46602",
			"46633",
			"46634",
			"46637",
			"46638",
			"46639",
			"46640",
			"46641",
			"46764",
			"46814",
			"46815",
			"46816",
			"46817",
			"46818",
			"46819",
			"47058",
			"47059",
			"47274",
			"47328",
			"47364",
			"47405",
			"47473",
			"47474",
			"47475",
			"47476",
			"47477",
			"47478",
			"47479",
			"47480",
			"47481",
			"47550",
			"47557",
			"47560",
			"47666",
			"47667",
			"47683",
			"47684",
			"47685"
		);
		foreach ($cursor as $id) {
			$note = Note::get( (int) $id);
			echo $id . '\n';
			$note->place_id = 72;
			$note->update(null);
		}
		echo "Done";
		return $this->response;
	}
}
