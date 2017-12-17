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

		$menu = $context->getConfig('menus/'.$app);
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
    
    	$major = ($this->params()->fromQuery('major', 'date'));
    	$dir = ($this->params()->fromQuery('dir', 'DESC'));
    
    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    
    	// Retrieve the list
    	$notes = Note::getList($category, $type, $params, $major, $dir, $mode);
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
    	$view = $this->getList();

   		include 'public/PHPExcel_1/Classes/PHPExcel.php';
   		include 'public/PHPExcel_1/Classes/PHPExcel/Writer/Excel2007.php';

		$workbook = new \PHPExcel;
		(new SsmlNoteViewHelper)->formatXls($workbook, $view);		
		$writer = new \PHPExcel_Writer_Excel2007($workbook);
		
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition:inline;filename=Fichier.xlsx ');
		$writer->save('php://output');
    }
    
    public function updateAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$id = (int) $this->params()->fromRoute('id', 0);
 		$note = Note::get($id);
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
    			$data['school_year'] = $context->getConfig('student/property/school_year/default');
    			$data['school_period'] = $request->getPost('school_period');
    			$data['class'] = $request->getPost('class');
/*    		    if ($request->getPost('teacher_n_fn')) {
    				$select = Vcard::getTable()->getSelect();
    				$where = new Where;
    				$where->notEqualTo('status', 'deleted');
    				$where->like('n_fn', '%'.$request->getPost('teacher_n_fn').'%');
    				$where->like('roles', '%teacher%');
			    	$select->where($where);
    				$cursor = Vcard::getTable()->selectWith($select);
    				$contact = null;
    				foreach ($cursor as $contact);
    				if ($contact) {
    					$note->teacher_n_fn = $contact->n_fn;
    					$data['teacher_id'] = $contact->id;
    				}
	    		}*/
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
    			if ($note->type == 'report') {
    				$computedAverages = Note::computePeriodAverages($data['place_id'], $data['school_year'], $data['class'], $data['school_period'], $data['subject']);
    			}
    			$noteCount = 0; $noteSum = 0; $lowerNote = 999; $higherNote = 0;
    			foreach ($note->links as $noteLink) {
    				$noteLink->audit = array();
    				$value = ($data['subject'] == 'global') ? $request->getPost('mention_'.$noteLink->account_id) : $request->getPost('value_'.$noteLink->account_id);
    				if (!$value) $value = null;
    				if ($note->type == 'report' && $value === null) {
    					if (array_key_exists($noteLink->account_id, $computedAverages)) {
    						$value = $computedAverages[$noteLink->account_id]['global']['note'];
    						$noteLink->audit[] = $computedAverages[$noteLink->account_id]['global']['notes'];
    					}
    				}
    				$noteLink->value = $value;
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
	    
	    public function repriseAction()
	    {
	    	foreach (Note::getList('evaluation', 'report', array('place_id', '1'), 'id', 'asc') as $note) {
	    		print_r($note->id."\n");
		    	$note->links = array();
		    	$select = NoteLink::getTable()->getSelect()
		    				->join('core_account', 'core_account.id = student_note_link.account_id', array(), 'left')
		    				->join('core_vcard', 'core_vcard.id = core_account.contact_1_id', array('n_fn'), 'left')
	    					->where(array('note_id' => $note->id, 'student_note_link.status != ?' => 'deleted'));
		    				$cursor = NoteLink::getTable()->selectWith($select);
	    		$computedAverages = Note::computePeriodAverages($note->place_id, $note->school_year, $note->class, $note->school_period, $note->subject);
		    	foreach($cursor as $noteLink) {
					$note->links[] = $noteLink;
					$audit = array();
					$distribution = array();
					if (array_key_exists($noteLink->account_id, $computedAverages)) {
	    				$value = $computedAverages[$noteLink->account_id]['global']['note'];
	    				$audit[] = $computedAverages[$noteLink->account_id]['global']['notes'];
	    				foreach ($computedAverages[$noteLink->account_id] as $categoryId => $category) {
	    					$distribution[$categoryId] = $category['note'];
						}
						if ($value != $noteLink->value || count($distribution) != count($noteLink->distribution)) {
							print_r($noteLink->id.' '.$note->place_id.' '.$note->class.' '.$note->subject."\n");
							print_r('New: '.$value."\n");
							print_r($distribution);
							print_r('Old: '.$noteLink->value."\n");
							print_r($noteLink->distribution);
							$noteLink->value = $value;
							$noteLink->distribution = $distribution;
							$noteLink->audit = $audit;
//							$noteLink->update(null);
						}
					}
				}
	    	}
	    	return $this->response;
	    }
}
