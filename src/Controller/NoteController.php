<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Notification;
use PpitCore\Model\Account;
use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
use PpitCore\Model\Document;
use PpitCore\Model\Place;
use PpitCore\Model\Event;
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
	public function getList() {
		$context = Context::getCurrent();
		$where = [];
		$teacher_id = $this->params()->fromQuery('teacher_id');
		if ($teacher_id) $where['teacher_id'] = $teacher_id;
		$cursor = Note::getList('evaluation', 'note', $where, 'subject', 'ASC', 'search', null);
		$evaluations = [];
		foreach ($cursor as $evaluation) {
			$evaluations[] = [
				'id' => $evaluation->id,
				'status' => $evaluation->status,
				'place_id' => $evaluation->place_id,
				'teacher_id' => $evaluation->teacher_id,
				'school_year' => $evaluation->school_year,
				'school_period' => $evaluation->school_period,
				'group_id' => $evaluation->group_id,
				'subject' => $evaluation->subject,
				'date' => $evaluation->date,
				'criteria' => $evaluation->criteria,
				'reference_value' => $evaluation->reference_value,
				'weight' => $evaluation->weight,
				'observations' => $evaluation->observations,
				'category' => $evaluation->level,
			];
		}
		return $evaluations;
	}
	
	public function get($id) {
		$context = Context::getCurrent();
		$evaluation = Note::get($id, 'id', 'note', 'type');
		if (!$evaluation) {
			$this->response->setStatusCode('400');
			$this->response->setReasonPhrase("Evaluation with id $id does not exists");
			return;
		}
		$cursor = NoteLink::getList('note', ['note_id' => $id], 'name', 'ASC', 'search');
		$links = [];
		foreach ($cursor as $link) {
			$links[$link->id] = [
				'status' => $link->status,
				'account_id' => $link->account_id,
				'specific_weight' => $link->specific_weight,
				'value' => $link->value,
				'evaluation' => $link->evaluation,
				'assessment' => $link->assessment,
			];
		}
		$content = [[
			'id' => $evaluation->id,
			'status' => $evaluation->status,
			'place_id' => $evaluation->place_id,
			'teacher_id' => $evaluation->teacher_id,
			'school_year' => $evaluation->school_year,
			'school_period' => $evaluation->school_period,
			'group_id' => $evaluation->group_id,
			'criteria' => $evaluation->criteria,
			'subject' => $evaluation->subject,
			'category' => $evaluation->level,
			'date' => $evaluation->date,
			'reference_value' => $evaluation->reference_value,
			'weight' => $evaluation->weight,
			'observations' => $evaluation->observations,
			'links' => $links,
		]];
		return $content;
	}
	
	/**
	 * Add a note
	 */
	public function post() {
		$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
		$connection->beginTransaction();
		try {
			$body = json_decode($this->request->getContent(), true);
			$note = Note::instanciate('note');
			$note->category = 'evaluation';
			$note->status = 'current';
	
			$note->place_id = $body['place_id'];
			$note->teacher_id = $body['teacher_id'];
			if (array_key_exists('group_id', $body)) $note->group_id = $body['group_id'];
			$note->school_year = $body['school_year'];
			$note->school_period = $body['school_period'];
			if (array_key_exists('level', $body)) $note->level = $body['level'];
			$note->subject = $body['subject'];
			$note->date = $body['date'];
			$note->reference_value = $body['reference_value'];
			$note->weight = $body['weight'];
			$note->criteria = $body['criteria'];
			if (array_key_exists('observations', $body)) $note->observations = $body['observations'];

			$rc = $note->add();
			if ($rc != 'OK') {
				$connection->rollback();
                $this->response->setStatusCode('400');
                $this->response->setReasonPhrase($rc);
				return [];
			}

			foreach ($body['links'] as $account_id => $link) {
				$noteLink = NoteLink::instanciate($account_id, $note->id);
				if (array_key_exists('weight', $link)) $noteLink->specific_weight = $link['weight'];
				$noteLink->value = $link['value'];
				if (array_key_exists('evaluation', $link)) $noteLink->evaluation = $link['evaluation'];
				if (array_key_exists('assessment', $link)) $noteLink->assessment = $link['assessment'];
				$rc = $noteLink->add();
				if ($rc != 'OK') {
					$connection->rollback();
					$this->response->setStatusCode('400');
					$this->response->setReasonPhrase($rc);
					return [];
				}
			}

			$connection->commit();
			return ['id' => $note->id];
		}
		catch (\Exception $e) {
			$connection->rollback();
			$this->response->setStatusCode('500');
			return [];
		}
	}
	
	/**
	 * Update 1 note
	 */
	public function patch($id) {
		$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
		$connection->beginTransaction();
		$note = Note::get($id, 'id', 'note', 'type');
		if (!$note) {
			$this->response->setStatusCode('400');
			$this->response->setReasonPhrase("Evaluation with id $id does not exists");
			return;
		}

		try {
			$body = json_decode($this->request->getContent(), true);
			if (array_key_exists('place_id', $body)) $note->place_id = $body['place_id'];
			if (array_key_exists('teacher_id', $body)) $note->teacher_id = $body['teacher_id'];
			if (array_key_exists('group_id', $body)) $note->group_id = $body['group_id'];
			if (array_key_exists('school_year', $body)) $note->school_year = $body['school_year'];
			if (array_key_exists('school_period', $body)) $note->school_period = $body['school_period'];
			if (array_key_exists('level', $body)) $note->level = $body['level'];
			if (array_key_exists('subject', $body)) $note->subject = $body['subject'];
			if (array_key_exists('date', $body)) $note->date = $body['date'];
			if (array_key_exists('reference_value', $body)) $note->reference_value = $body['reference_value'];
			if (array_key_exists('weight', $body)) $note->weight = $body['weight'];
			if (array_key_exists('observations', $body)) $note->observations = $body['observations'];
			if (array_key_exists('criteria', $body)) $note->criteria = $body['criteria'];

			$rc = $note->update(null);
			if ($rc != 'OK') {
				$connection->rollback();
                $this->response->setStatusCode('400');
                $this->response->setReasonPhrase($rc);
				return [];
			}

			$links = NoteLink::getList('note', ['note_id' => $id], 'name', 'ASC', 'search');
			foreach ($links as $noteLink) $noteLink->delete(null);

			foreach ($body['links'] as $account_id => $link) {
				$noteLink = NoteLink::instanciate($account_id, $note->id);
				if (array_key_exists('weight', $link)) $noteLink->specific_weight = $link['weight'];
				$noteLink->value = $link['value'];
				if (array_key_exists('evaluation', $link)) $noteLink->evaluation = $link['evaluation'];
				if (array_key_exists('assessment', $link)) $noteLink->assessment = $link['assessment'];
				$rc = $noteLink->add();
				if ($rc != 'OK') {
					$connection->rollback();
					$this->response->setStatusCode('400');
					$this->response->setReasonPhrase($rc);
					return [];
				}
			}

			$connection->commit();
			return [];
		}
		catch (\Exception $e) {
			$connection->rollback();
			$this->response->setStatusCode('500');
			return [];
		}
	}
	
	/**
	 * Upsert notes
	 */
	public function put() {
	}
	
	/**
	 * Delete notes
	 */
	public function delete($id) {
		$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
		$connection->beginTransaction();
		$note = Note::get($id, 'id', 'note', 'type');
		if (!$note) {
			$this->response->setStatusCode('400');
			$this->response->setReasonPhrase("Evaluation with id $id does not exists");
			return;
		}

		try {

			$rc = $note->delete(null);
			if ($rc != 'OK') {
				$connection->rollback();
                $this->response->setStatusCode('400');
                $this->response->setReasonPhrase($rc);
				return [];
			}

			$links = NoteLink::getList('note', ['note_id' => $id], 'name', 'ASC', 'search');
			foreach ($links as $noteLink) $noteLink->delete(null);

			$connection->commit();
			return [];
		}
		catch (\Exception $e) {
			$connection->rollback();
			$this->response->setStatusCode('500');
			return [];
		}
	}

	public function v1Action() 
	{
		$context = Context::getCurrent();
		
		// // Authentication
		// if (!$context->wsAuthenticate($this->getEvent())) {
		// 	$this->getResponse()->setStatusCode('401');
		// 	return $this->getResponse();
		// }
		
		// // Authorization
		// if (!$context->hasRole('manager') && !$context->hasRole('teacher')) {
		// 	$this->response->setStatusCode('403');
		// 	return $this->response;
		// }
		
		// Retrieve the context
		$context = Context::getCurrent();

		if ($this->request->isGet()) {
			$id = $this->params()->fromRoute('id');
			if ($id) $content = $this->get($id);
			else $content = $this->getList();
		}

		elseif ($this->request->isPost()) {
			$content = $this->post();
		}

		elseif ($this->request->isPatch()) {
			$id = $this->params()->fromRoute('id');
			$content = $this->patch($id);
		}

		elseif ($this->request->isDelete()) {
			$id = $this->params()->fromRoute('id');
			$content = $this->delete($id);
		}
		
		header('Content-Type: application/json');
		echo json_encode($content, JSON_PRETTY_PRINT);
		return $this->response;
	}

    public function indexAction()
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
			'pageScripts' => 'ppit-studies/note/index-scripts',
    		'category' => $category,
    		'type' => $type,
		));
		
		return new ViewModel(array(
    		'context' => $context,
			'tab' => $tab,
			'app' => $app,
		));
    }
    
    /**
     * Deprecated
     */
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
			'tab' => $tab,
			'app' => $app,
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
    	$accountConfig = Account::getConfig('p-pit-studies');
    	 
    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'accountConfig' => $accountConfig,
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
    
    	/*if (count($params) == 0) $mode = 'todo'; else */$mode = 'search';
    
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
    			'groups' => Account::getList('group', [], '+name', null),
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
    
    public function teacherListAction()
    {
    	return $this->listV2Action();
    }
    
    public function getAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    	$id = $this->params()->fromRoute('id');
    
    	$account_id = (int) $this->params()->fromQuery('account_id');
    	$account = null;
    	if ($account_id) {
    		$account = Account::get($account_id);
    		$noteLinks = NoteLink::GetList(null, array('category' => 'homework', 'account_id' => $account_id, 'school_year' => $context->getConfig('student/property/school_year/default')), 'date', 'DESC', 'search');
    	}

    	$group_id = $this->params()->fromQuery('group_id');
    	$filters = ['group_id' => $group_id];
    	if ($account) $filters['place_id'] = $account->place_id;
		$notes = Note::getList('homework', null, $filters, 'id', 'ASC', 'search', null); 
		
		if ($account_id) $notes = array_merge($noteLinks, $notes);

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

		$accountIds = [];
		// Report case : Retrieve the notes to cumpute the averages
		if ($type == 'report') {

	    	// Report case: Catalog the report weight for this subject
			$reportWeights = [];
			foreach ($noteLinks as $link) {
				$reportWeights[$link->account_id . '_' . $link->subject . '_' . $link->school_year . '_' . $link->school_period] = ($link->specific_weight) ? $link->specific_weight : $link->weight;
				$accountIds[] = $link->account_id;
			}

			$allAbsences = Event::GetList('absence', array('account_id' => implode(",", $accountIds), 'property_1' => $context->getConfig('student/property/school_year/default')), '-begin_date', null);

			// Report case : Retrieve the notes to cumpute the averages and restrict on the selected report scope
			$cursor = NoteLink::getList('note', $params, $major, $dir, $mode);
			$notes = [];
			foreach ($cursor as $link) {
				if (array_key_exists($link->account_id . '_' . $link->subject . '_' . $link->school_year . '_' . $link->school_period, $reportWeights)) {
					$notes[] = $link;
				}
			}
		}
		else $notes = $noteLinks;

		// Compute the averages
		$catchUp = false;
		$averages = [];
		foreach ($notes as $link) {
			$key = $link->account_id . '|' . $link->school_year . '|' . $link->school_period . '|' . $link->subject;
			if (!array_key_exists($key, $averages)) {
				$averages[$key] = [
					'account_id' => $link->account_id,
					'school_year' => $link->school_year,
					'school_period' => $link->school_period,
					'subject' => $link->subject,
					'sum' => $link->value * $link->weight,
					'reference_value' => $link->reference_value * $link->weight,
				];

				// Report case: Retrieve the report weight for this subject
				if ($type == 'report') {
					if (array_key_exists($link->account_id . '_' . $link->subject . '_' . $link->school_year . '_' . $link->school_period, $reportWeights)) {
						$averages[$key]['weight'] = $reportWeights[$link->account_id . '_' . $link->subject . '_' . $link->school_year . '_' . $link->school_period];
					}
					else $averages[$key]['weight'] = 1;
				}
				
			}
			else {
				$averages[$key]['sum'] += $link->value * $link->weight;
				$averages[$key]['reference_value'] += $link->reference_value * $link->weight;
			}

			// Applying same logic from absenceCount in PdfReportTableViewHelper to compute absences but with semester now

			// Column "Défaillant/Rattrapages" on Excel Report Export
			
			$absenceCount = [];
			$absenceCount['global'] = 0;

			foreach ($allAbsences as $absence) {
				if ($absence->account_id == $link->account_id) $absenceById[] = $absence;
			}

			foreach ($absenceById as $cursor) {
				if (!array_key_exists($cursor->property_3, $absenceCount)) $absenceCount[$cursor->property_3] = 0;

				$absenceCount[$cursor->property_3]++;
				$absenceCount['global']++;
			}
	

			if (isset($absenceCount[$link->subject])) {
				if ($absenceCount[$link->subject] >= 3) $catchUp = "A rattraper";
				elseif ($absenceCount['global'] >= 40) $catchUp = "Défaillant";
			}
			if ($averages[$key]['sum'] <= 1 && $catchUp != "Défaillant") $catchUp = "A rattraper";
			$averages[$key]['catchUp'] = $catchUp;

			print_r($averages[$key]['sum']); 
		} exit;
		$globalAverages = [];
		foreach ($averages as $key => $average) {
			$key = $average['account_id'] . '|' . $average['school_year'] . '|' . $average['school_period'];
			if ($type == 'report' && array_key_exists($average['account_id'] . '_' . $average['subject'] . '_' . $average['school_year'] . '_' . $average['school_period'], $reportWeights)) $reportWeight = $reportWeights[$average['account_id'] . '_' . $average['subject'] . '_' . $average['school_year'] . '_' . $average['school_period']];
			else $reportWeight = 1;
			$value = round($average['sum'] * $reportWeight / $average['reference_value'] * 100) / 100;
			if (!array_key_exists($key, $globalAverages)) $globalAverages[$key] = ['sum' => $value, 'reference_value' => $reportWeight];
			else {
				$globalAverages[$key]['sum'] += $value;
				$globalAverages[$key]['reference_value'] += $reportWeight;
			}
		}
		$yearlyAverages = [];
		foreach ($averages as $key => $average) {
			$key = $average['account_id'] . '|' . $average['school_year'];
			if ($type == 'report' && array_key_exists($average['account_id'] . '_' . $average['subject'] . '_' . $average['school_year'] . '_' . $average['school_period'], $reportWeights)) $reportWeight = $reportWeights[$average['account_id'] . '_' . $average['subject'] . '_' . $average['school_year'] . '_' . $average['school_period']];
			else $reportWeight = 1;
			$value = round($average['sum'] * $reportWeight / $average['reference_value'] * 100) / 100;
			if (!array_key_exists($key, $yearlyAverages)) $yearlyAverages[$key] = ['sum' => $value, 'reference_value' => $reportWeight];
			else {
				$yearlyAverages[$key]['sum'] += $value;
				$yearlyAverages[$key]['reference_value'] += $reportWeight;
			}
		}


    	// Return the link list
    	$view = new ViewModel(array(
			'category' => $category,
			'type' => $type,
			'noteLinks' => $noteLinks,
			'averages' => $averages,
			'globalAverages' => $globalAverages,
			'yearlyAverages' => $yearlyAverages,
    	));
    	
   		include 'public/PHPExcel_1/Classes/PHPExcel.php';
   		include 'public/PHPExcel_1/Classes/PHPExcel/Writer/Excel2007.php';

		$workbook = new \PHPExcel;
		(new SsmlNoteViewHelper)->formatXls($workbook, $view, Account::getList('group', [], '+name', null));		
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
    	if ($place) {
	    	$school_periods = $place->getConfig('school_periods');
	    	$current_school_period = $context->getCurrentPeriod($school_periods);
	    	if (!$note->school_period) $note->school_period = $current_school_period;
    	}
    	else $school_periods = null;
    	
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
    				$data['group_id'] = $request->getPost('group_id');
//    				$data['class'] = $request->getPost('class');
    				$data['school_period'] = $request->getPost('school_period');
    				$data['level'] = $request->getPost('level');
    				$data['subject'] = $request->getPost('subject');
//    				$data['date'] = $request->getPost('date');
//    				$data['type'] = $request->getPost('type');
    				$data['target_date'] = $request->getPost('target_date');
    				$data['document'] = $document_id;
    				$data['observations'] = $request->getPost('observations');
    				$data['comment'] = $request->getPost('comment');
    				$rc = $note->loadData($data);
    				if ($rc != 'OK') throw new \Exception('View error: ' . $rc);
    			}
    
    			// Atomically save
    			$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    				if (!$note->id) $rc = $note->add();
    				elseif ($action == 'delete') {
//		    			foreach ($note->links as $noteLink) $noteLink->delete(null);
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
    		'groups' => Account::getList('group', [], '+name', null),
    		'school_periods' => $school_periods,
    		'document' => $document,
    		'csrfForm' => $csrfForm,
    		'error' => $error,
    		'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }

	/**
	 * REST version for 2pit2
	 * Update the subject average and the global average for the given group, subject, school year and period
	 */
	public function apiUpdateAverageAction() {

		// Retrieve the context
		$context = Context::getCurrent();
		
		// Authentication
		if (!$context->isAuthenticated() && !$context->wsAuthenticate($this->getEvent())) {
			$this->getResponse()->setStatusCode('401');
			return $this->getResponse();
		}

		// Authorization
		if (!$context->hasRole('manager') && !$context->hasRole('teacher')) {
			$this->response->setStatusCode('401');
			return $this->response;
		}

		// Only POST
		if (!$this->getRequest()->isPost()) {
			$this->response->setStatusCode('405');
			$this->response->setReasonPhrase('This URI only supports POST HTTP requests');
			return $this->response;
		}

		// Retrieve the parameters
		$class = $this->params()->fromQuery('class');
		$group_id = $this->params()->fromQuery('group_id');
		$group = Account::get($group_id);
		if (!$group || $group->type != 'group') {
			$this->response->setStatusCode('400');
			$this->response->setReasonPhrase('Expecting a group ID');
			return $this->response;
		}
		
		$subject = $this->params()->fromQuery('subject');
		if (!$subject)  {
			$this->response->setStatusCode('400');
			$this->response->setReasonPhrase('Expecting a subject');
			return $this->response;
		}
		
		$school_year = $this->params()->fromQuery('school_year');
		if (!$school_year) $school_year = $context->getConfig('student/property/school_year/default');

		if ($group->place_id) $place_id = $group->place_id;
		else $place_id = $context->getPlaceId();
		$place = Place::get($place_id);
		$school_period = $this->params()->fromQuery('school_period');
		if (!$school_period) {
			$school_periods = $place->getConfig('school_periods');
			$current_school_period = $context->getCurrentPeriod($school_periods);
		}
		
		// Atomically save
		$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
		$connection->beginTransaction();
		try {
			
			// Compute and update subject and global average for the group, the year and the period
			$rc = Note::updateAverage($place_id, $class, $group_id, $subject, $school_year, $school_period);
			if ($rc) {
				$connection->rollback();
				$this->getResponse()->setStatusCode('400');
				$this->getResponse()->setReasonPhrase($rc);
				return $this->response;
			}
			$connection->commit();
			$this->getResponse()->setStatusCode('204');
			return $this->response;
		}
		catch (\Exception $e) {
			$connection->rollback();
			throw $e;
		}
	}
	
	/**
	 * REST version for 2pit2
	 */
	public function evaluation() {
	
		// Retrieve the context
		$context = Context::getCurrent();
	
		// Authentication
		if (!$context->isAuthenticated() && !$context->wsAuthenticate($this->getEvent())) {
			$this->getResponse()->setStatusCode('401');
			return $this->getResponse();
		}
	
		// Authorization
		if (!$context->hasRole('manager') && !$context->hasRole('teacher')) {
			$this->response->setStatusCode('403');
			return $this->response;
		}
	
		// Retrieve the parameters
		$type = $this->params()->fromRoute('type');
		if (!$type) {
			$this->response->setStatusCode('400');
			$this->response->setReasonPhrase('Expecting a type');
			return $this->response;
		}
		$id = $this->params()->fromRoute('id');

		$accounts = null; // If no account is provided in parameters, all the group is evaluated
		$accounts = $this->params()->fromQuery('accounts');
		if ($accounts) {
			$accountsData = Account::getList('p-pit-studies', ['id' => $accounts], '+name', null);
			$accounts = explode(',', $accounts);
		}
		else {
			$accounts = null;
			$accountsData = Account::getList('p-pit-studies', ['status' => 'active,interested,converted,committed,undefined,retention,alumni,canceled'], '+name', null);
		}
		
		$subject = $this->params()->fromQuery('subject');
		$level = $this->params()->fromQuery('level');
		$date = $this->params()->fromQuery('date', date('Y-m-d'));

		// user_story - student_evaluation_teachers: Les enseignants pouvant être selectionnés dans le formulaire sont tous les enseignants ayant un statut "actif"
		$teachers = [];
		if ($context->hasRole('manager')) {
			$cursor = Account::getList('teacher', ['status' => 'active,committed,contrat_envoye,reconnect_with'], '+name', null);
			foreach ($cursor as $teacher_id => $teacher) {
				$teachers[$teacher->contact_1_id] = $teacher->properties;
				$competences = $teachers[$teacher->contact_1_id]['property_3'];
				if ($competences) $competences = explode(',', $competences);
				else $competences = [];
				$teachers[$teacher->contact_1_id]['competences'] = $competences;
			}
		}
		else {
			$myAccount = Account::get($context->getContactId(), 'contact_1_id', 'teacher', 'type');
			if (!$myAccount) {
				$this->response->setStatusCode('403');
				return $this->response;
			}
			$teachers[$myAccount->contact_1_id] = $myAccount->properties;

			$competences = $myAccount->property_3;
			if ($competences) $competences = explode(',', $competences);
			else $competences = [];
			$teachers[$myAccount->contact_1_id]['competences'] = $competences;
			
			if ($myAccount->groups) $teachers[$myAccount->contact_1_id]['groups'] = explode(',', $myAccount->groups); 
			else $teachers[$myAccount->contact_1_id]['groups'] = []; 
		}
		
		// Retrieve the existing note or instanciate
	
		$content = [];
		$content['type'] = $type;
		$content['id'] = $id;
		$content['note'] = [];
		$content['note']['type'] = $type;
		$content['noteLinks'] = [];
		$content['teachers'] = $teachers;
		$content['accounts'] = $accounts;
		$content['accountsData'] = $accountsData;
		
		if ($id) {
			
			$note = Note::get($id);

			// Retrieve the group and the place
			$class = $note->class;
			$group_id = $note->group_id;
			$content['note']['group_id'] = $group_id;
			$group = Account::get($group_id);
			if ($group) $content['group'] = $group->properties;
			else $content['group'] = null;
			$place = Place::get($note->place_id);
			if ($place) $content['place'] = $place->properties;
			else $content['place'] = [];
			$content['places'] = Place::getList([]);

			$noteLinks = $note->links;
			$content['note']['status'] = $note->status;
			$content['note']['place_id'] = $note->place_id;
			$content['note']['teacher_id'] = $note->teacher_id;
			$content['note']['subject'] = $note->subject;
			$content['note']['level'] = $note->level;
			$content['note']['date'] = $note->date;
			$content['note']['school_year'] = $note->school_year;
			$content['note']['school_period'] = $note->school_period;
			$content['note']['reference_value'] = $note->reference_value;
			$content['note']['weight'] = $note->weight;
			$content['note']['observations'] = $note->observations;
			foreach ($note->links as $noteLink) $content['noteLinks'][$noteLink->account_id] = $noteLink->getProperties();
			$content['update_time'] = $note->update_time;
		}
		else {
		
			// Retrieve the group and the place
			$class = $this->params()->fromQuery('class');
			$group_id = $this->params()->fromQuery('group_id');
			$content['note']['group_id'] = $group_id;
			$group = Account::get($group_id);
			if (!$group) {
				$content['group'] = null;
				$place = null;
			}
			else {
				if (!$group || $group->type != 'group') {
					$this->response->setStatusCode('400');
					$this->response->setReasonPhrase('Not existing group');
					return $this->response;
				}
				if (!$context->hasRole('manager') && !in_array($group_id, $teachers[$myAccount->contact_1_id]['groups'])) {
					$this->response->setStatusCode('403');
					$this->response->setReasonPhrase('Group not allowed for this user');
					return $this->response;
				}
				$content['group'] = $group->properties;
				$place = Place::get($group->place_id);
			}
			if ($place) $content['places'] = [$place];
			else {
				$place = $context->getPlace();
				$places = Place::getList(array());
				$content['places'] = $places;
			}
			$content['place'] = $place->properties;
				
			$note = Note::instanciate($type, null, $group_id);
			$noteLinks = [];
			if ($group) {
				foreach ($group->members as $member_id => $member) {
					if ($member->type == 'p-pit-studies') {
						if (!$accounts || in_array($member_id, $accounts)) {
							$noteLink = [
								'account_id' => $member_id,
								'n_fn' => $member->n_fn,
								'value' => null,
								'assessment' => '',
							];
							$noteLinks[] = $noteLink;
						}
					}
				}
			}
			elseif ($accounts) {
				foreach ($accounts as $account_id) {
					if (array_key_exists($account_id, $accountsData)) {
						$account = $accountsData[$account_id];
						$noteLink = [
							'account_id' => $account_id,
							'n_fn' => $account->n_fn,
							'value' => null,
							'assessment' => '',
						];
						$noteLinks[] = $noteLink;
					}
				}
			}
			else $noteLinks[] = ['account_id' => null, 'n_fn' => null, 'value' => null, 'assessment' => ''];

			$content['note']['status'] = 'current';
			$content['note']['place_id'] = $place->id;
			if ($context->hasRole('manager')) $content['note']['teacher_id'] = null;
			else $content['note']['teacher_id'] = $myAccount->contact_1_id;
			$content['note']['subject'] = $subject;
			$content['note']['level'] = $level;
			$content['note']['date'] = $date;
			$content['note']['school_year'] = $context->getConfig('student/property/school_year/default');
		
			// user_story - student_evaluation_period: La période est pré-renseignée à la période en cours (en paramètre) mais peut être modifiée (ex. pour effectuer une rétro-saisie sur une période antérieure).
			$school_periods = $place->getConfig('school_periods');
			$current_school_period = $context->getCurrentPeriod($school_periods);

			$content['note']['school_period'] = $current_school_period;
			$content['note']['reference_value'] = $context->getConfig('student/parameter/average_computation')['reference_value'];
			$content['note']['weight'] = 1;
			$content['note']['observations'] = '';
			$content['noteLinks'] = $noteLinks;
			$content['update_time'] = null;
		}

		// Retrieve the subject list. As a teacher my subject list is restricted according to my competences
		$subjects = [];
		foreach ($place->getConfig('student/property/school_subject')['modalities'] as $subjectId => $subject) {
			if (!array_key_exists('archive', $subject) || !$subject['archive']) {
				if ($context->hasRole('manager')) $subjects[$subjectId] = $subject;
				else {
					$teacher = $teachers[$myAccount->contact_1_id];
					$teacherSubjects = ($teacher['property_5']) ? explode(',', $teacher['property_5']) : [];
					if (in_array($subjectId, $teacherSubjects)) $subjects[$subjectId] = $subject;
					if (array_key_exists('subcategory', $subject) && in_array($subject['subcategory'], $teacher['competences'])) $subjects[$subjectId] = $subject;
				}
			}
		}
		$content['config'] = [];
		$content['config']['subjects'] = $subjects;
		$content['config']['categories'] = $place->getConfig('student/property/evaluationCategory')['modalities'];

		// DELETE request
		if ($this->request->isDelete()) {
			if (!$id) {
				$this->response->setStatusCode('400');
				$this->response->setReasonPhrase('Expecting an id');
				return null;
			}

			// Atomically save
			$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
			$connection->beginTransaction();
			try {
				$update_time = $this->request->getPost('update_time');
				$note->delete('update_time');
				
				// Update the subject and global averages (for admin only)
//				if ($context->hasRole('admin')) {
/*					$rc = Note::updateAverage($content['note']['place_id'], $class, $group_id, $content['note']['subject'], $content['note']['school_year'], $content['note']['school_period']);
					if ($rc) {
						$connection->rollback();
						$this->response->setStatusCode('409');
						$this->response->setReasonPhrase($rc);
						return null;
					}*/
//				}

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
		}
		
		// POST request for create or update
		elseif ($this->request->isPost()) {
	
			// User story - student_evaluation_teachers:
			// Rôle manager: les enseignants pouvant être selectionnés dans le formulaire sont tous les enseignants ayant un statut "actif".
			// Rôle enseignant: je ne peux pas affecter un autre enseignant que moi-même à l'évaluation.
	
			// Load the input data
	
			$content['note']['teacher_id'] = $this->request->getPost('teacher_id');
	
			$content['note']['place_id'] = $this->request->getPost('place_id');
			$content['note']['school_year'] = $this->request->getPost('school_year');
			$content['note']['school_period'] = ($this->request->getPost('school_period')) ? $this->request->getPost('school_period') : 'Q1';
			$content['note']['level'] = $this->request->getPost('level');
			$content['note']['subject'] = $this->request->getPost('subject');
			$content['note']['date'] = $this->request->getPost('date');
			$content['note']['reference_value'] = $this->request->getPost('reference_value');
			$content['note']['weight'] = $this->request->getPost('weight');
			$content['note']['observations'] = $this->request->getPost('observations');
	
			$newLinks = [];
			foreach ($content['noteLinks'] as $noteLinkData) {
				$account_id = $noteLinkData['account_id'];
				$value = $this->request->getPost('value-' . $account_id);
				
				if ($value === '') $value = null;
				else {
					if (array_key_exists('value', $context->getConfig('teacher/evaluation/update')['properties'])) {
						$param = $context->getConfig('teacher/evaluation/update')['properties']['value'][$value];
					} 
					elseif ($context->getConfig('note/property/value')['type'] == 'select') {
						$param = $context->getConfig('note/property/value')['modalities'][$value];
					}
					else $param = null;
				}
					
				$assessment = $this->request->getPost('assessment-' . $account_id);
				
				if (!$account_id) {
					$account_id = $this->request->getPost('account_id');
					$noteLinkData['account_id'] = $account_id;
					$noteLink = NoteLink::instanciate($account_id);
				}
				else {
					if (!$id) $noteLink = NoteLink::instanciate($account_id);
					else $noteLink = $note->links[$account_id];
				}
				$mention = $this->request->getPost('mention-' . $account_id);
				$audit = [];
	
				if ($value !== null || $type == 'report' || $assessment || $mention) {

					if ($mention) $noteLinkData['evaluation'] = $mention;
					elseif ($value !== 'Non Évalué') $noteLinkData['evaluation'] = NULL; 
					else {
						$noteLinkData['evaluation'] = $value; 
						$value = $param['value'];
					} 

					$noteLinkData['value'] = $value;
					$noteLinkData['assessment'] = $assessment;
					$noteLink->loadData($noteLinkData);
					$newLinks[$account_id] = $noteLink;
				}
			}

			$rc = $note->loadData($content['note']);
			if ($rc != 'OK') {
				$this->response->setStatusCode('409');
				$this->response->setReasonPhrase($rc);
				return null;
			}
			else {
	
				// Atomically save
				$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
				$connection->beginTransaction();
				try {
					$update_time = $this->request->getPost('update_time');
					if ($note->id) $rc = $note->update('update_time');
					elseif (count($newLinks)) {
						$rc = $note->add();
						$content['id'] = $note->id;
					}
					if ($rc != 'OK') {
						$connection->rollback();
						$this->response->setStatusCode('409');
						$this->response->setReasonPhrase($rc);
						return null;
					}

					// Save the note at the student level
					if ($id) foreach ($note->links as $noteLink) $noteLink->drop();
					$note->links = $newLinks;
					foreach ($note->links as $noteLink) {
						$noteLink->note_id = $note->id;
						$rc = $noteLink->add();
						if ($rc != 'OK') {
							$connection->rollback();
							$this->response->setStatusCode('409');
							$this->response->setReasonPhrase($rc);
							return null;
						}
					}
					
					// Update the subject and global averages
					if (false /* (transient rule) $note->id*/) {
						$rc = Note::updateAverage($content['note']['place_id'], $class, $group_id, $content['note']['subject'], $content['note']['school_year'], $content['note']['school_period']);
						if ($rc) {
							$connection->rollback();
							$this->response->setStatusCode('409');
							$this->response->setReasonPhrase($rc);
							return null;
						}
					}
					
					// Compute the group indicators
					$content['indicators'] = null; //$note->computeGroupIndicators();

					$connection->commit();
					$this->response->setStatusCode('200');
					return $content;
				}
				catch (\Exception $e) {
					$connection->rollback();
					$this->response->setStatusCode('409');
					$this->response->setReasonPhrase('Exception: ' . $e);
					return null;
				}
			}
		}
		return $content;
	}
	
	public function apiEvaluationAction() {
	
		// Retrieve the context
		$context = Context::getCurrent();

		$content = $this->evaluation();

		header('Content-Type: application/json');
		echo json_encode($content, JSON_PRETTY_PRINT);
		return $this->response;
	}
	
	public function evaluationAction()
	{
		$context = Context::getCurrent();
		
		$content = $this->evaluation();
		$view = new ViewModel(array(
			'context' => $context,
			'request' => ($this->getRequest()->isPost()) ? 'POST' : (($this->getRequest()->isDelete()) ? 'DELETE' : 'GET'),
			'content' => $content,
			'statusCode' => $this->response->getStatusCode(),
			'reasonPhrase' => $this->response->getReasonPhrase(),
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
    				$noteLink->distribution = array();
    				// Global mention to move to another property
    				$value = $request->getPost('value_'.$noteLink->account_id);
    				if ($value == '') $value = null;
    				$mention = $request->getPost('mention_'.$noteLink->account_id);
    			    if ($note->type == 'note' && $value === null) {
    				    if ($data['subject'] == 'global') {
							$value = $computedAverages[$noteLink->account_id]['global']['note'];
    				    }
    				}
    				//elseif ($note->type == 'report' /*&& $value === null*/) { // 2018-09 : Retour arrière suite pbme ESI de la demande SEA de forcer la moyenne aussi dans le cas où elle n'est pas explicitement effacée par l'utilisateur
    				elseif ($note->type == 'report' && $value === null) {
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
    			    elseif ($note->type == 'exam' /*&& $value === null*/) { // 2018-09 : Retour arrière suite pbme ESI de la demande SEA de forcer la moyenne aussi dans le cas où elle n'est pas explicitement effacée par l'utilisateur
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
		    				// Réactivation du calcul automatique des moyennes //pour les RP uniquement
		    				if (!$error && $note->type != 'report' /*&& $context->hasRole('manager')*/) { // (false) {
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
								$data['school_year'] = $context->getConfig('student/property/school_year/default');
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
		    					$newGlobalAverages = Note::computePeriodAverages($data['place_id'], $note->school_year, $data['class'], $data['school_period']);
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
		
		$where = array('place_id' => $place->id, 'school_year' => $school_year, 'school_period' => $school_period);
		
		$computedReports = [];
		$existingReports = [];
		
		// Retrieve all the notes in the required scope
		foreach (Note::getList('evaluation', 'note', $where, 'id', 'asc', 'search', null) as $evaluation) {
				
			$computedKey = $evaluation->place_id . '_' . $evaluation->group_id . '_' . $evaluation->school_year . '_' . $evaluation->school_period . '_' . $evaluation->subject;
			if (!array_key_exists($computedKey, $computedReports)) {
				$computedReports[$computedKey] = ['evaluations' => [], 'reports' => []];
				$computedGlobalKey = $evaluation->place_id . '_0_' . $evaluation->school_year . '_' . $evaluation->school_period . '_' . 'global';
				if (!array_key_exists($computedGlobalKey, $computedReports)) {
					$computedReports[$computedGlobalKey] = ['evaluations' => [], 'reports' => []];
					$computedReports[$computedGlobalKey]['evaluations'][] = ['place_id' => $evaluation->place_id, 'group_id' => null, 'school_year' => $evaluation->school_year, 'school_period' => $evaluation->school_period, 'subject' => 'global', 'teacher_id' => null, 'id' => null];
				}
			}
			$computedReports[$computedKey]['evaluations'][] = ['place_id' => $evaluation->place_id, 'group_id' => $evaluation->group_id, 'school_year' => $evaluation->school_year, 'school_period' => $evaluation->school_period, 'subject' => $evaluation->subject, 'teacher_id' => $evaluation->teacher_id, 'id' => $evaluation->id];
		}

		// Retrieve all the reports in the required scope
		foreach (Note::getList('evaluation', 'report', $where, 'id', 'asc', 'search', null) as $report) {
		
			$existingKey = $report->place_id . '_' . $report->group_id . '_' . $report->school_year . '_' . $report->school_period . '_' . $report->subject;
			if (array_key_exists($existingKey, $computedReports)) {
				$computedReports[$existingKey]['reports'][] = ['place_id' => $report->place_id, 'group_id' => $report->group_id, 'school_year' => $report->school_year, 'school_period' => $report->school_period, 'subject' => $report->subject, 'id' => $report->id];
			}
			else {
//				if ($report->subject != 'global') {
					if (!array_key_exists($existingKey, $existingReports)) $existingReports[$existingKey] = [];
					$existingReports[$existingKey][] = ['place_id' => $report->place_id, 'group_id' => $report->group_id, 'school_year' => $report->school_year, 'school_period' => $report->school_period, 'subject' => $report->subject, 'id' => $report->id];
//				}
			}
		}
		
		$result = ['duplicate_or_missing_report' => [], 'report_without_note' => []];
		
		foreach ($computedReports as $computedKey => $computedReport) {
			if (count($computedReport['reports']) != 1) {
				$result['duplicate_or_missing_report'][] = [$computedKey => $computedReport];
				if (count($computedReport['reports']) == 0) {
					$newReport = Note::instanciate('report');
					$newReport->category = 'evaluation';
					$newReport->place_id = $computedReport['evaluations'][0]['place_id'];
					if ($computedReport['evaluations'][0]['subject'] != 'global') $newReport->group_id = $computedReport['evaluations'][0]['group_id'];
					$newReport->school_year = $computedReport['evaluations'][0]['school_year'];
					$newReport->school_period = $computedReport['evaluations'][0]['school_period'];
					$newReport->subject = $computedReport['evaluations'][0]['subject'];
					$newReport->teacher_id = $computedReport['evaluations'][0]['teacher_id'];
					$newReport->weight = 1;
					$newReport->reference_value = $context->getConfig('student/parameter/average_computation')['reference_value'];
					$newReport->add();
				}
			}
		}
		
		foreach ($existingReports as $existingKey => $existingReport) {
			$result['report_without_note'][] = [$existingKey => $existingReport];
		}
		
		echo json_encode($result, JSON_PRETTY_PRINT);
		return $this->response;
	}
    
	/**
	 * user_story - note_average_auto: The student averages by subject and global are checked globally, and patched when necessary, for a given place or for all the places at the same time.
	 */
	public function batchAverageAction()
	{
		$context = Context::getCurrent();
	
		// Authentication allowed either online or by WS
		if (!$context->isAuthenticated() && !$context->wsAuthenticate($this->getEvent())) {
			$this->getResponse()->setStatusCode('401');
			return $this->getResponse();
		}

		// Authorization
		if (!$context->hasRole('admin')) {
			$this->response->setStatusCode('401');
			return $this->response;
		}

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
		
		$where = array('school_year' => $school_year, 'school_period' => $school_period);
		if ($place_identifier) $where['place_id'] = $place->id;

		// Check and update when necessary the average for all the reports
		foreach (Note::getList('evaluation', 'report', $where, 'id', 'asc', 'search', null) as $note) {
			$select = NoteLink::getTable()->getSelect()
				->join('core_account', 'core_account.id = student_note_link.account_id', array(), 'left')
				->join('core_vcard', 'core_vcard.id = core_account.contact_1_id', array('n_fn'), 'left')
				->where(array('note_id' => $note->id, 'student_note_link.status != ?' => 'deleted'));
				$cursor = NoteLink::getTable()->selectWith($select);
			$computedAverages = Note::computePeriodAverages($note->place_id, $note->school_year, $note->class, $note->school_period, $note->subject);
			foreach($cursor as $noteLink) {
				$audit = array();
				$distribution = array();
				$update = false;
				if (array_key_exists($noteLink->account_id, $computedAverages)) {
					$value = $computedAverages[$noteLink->account_id]['global']['note'];
					$value = round($value * $note->reference_value / 20 , 2);
//					$value = round($value * $note->reference_value / $context->getConfig('student/parameter/average_computation')['reference_value'], 2);
					$audit[] = $computedAverages[$noteLink->account_id]['global']['notes'];
					foreach ($computedAverages[$noteLink->account_id] as $categoryId => $category) {
						if ($categoryId != 'global') $distribution[$categoryId] = $category['note'];
					}
					if ((int) round($value * 100) != (int) round($noteLink->value * 100) /*|| count($distribution) != count($noteLink->distribution)*/) {
						print_r($note->type.' Note: '.$note->id.' Link: '.$noteLink->id.' Account: ' . $noteLink->account_id . ' ' . $noteLink->n_fn.' '.$note->class.' '.$note->subject."\n");
						print_r('New: '.$value."\n");
						print_r($distribution);
						print_r('Old: '.$noteLink->value."\n");
						print_r($noteLink->distribution);
						$noteLink->value = $value;
						$noteLink->distribution = $distribution;
						$noteLink->audit = $audit;
						$update = true;
					}
				}
				if ($context->getInstanceId() == 28 && $noteLink->value === null) {
					print_r($note->type.' Note: '.$note->id.' Link: '.$noteLink->id.' Account: ' . $noteLink->account_id . ' ' . $noteLink->n_fn.' '.$note->class.' '.$note->subject."\n");
					print_r('New: '.$value."\n");
					print_r('Old: '.$noteLink->value."\n");
					$noteLink->value = 0;
					$update = true;
				}
				if ($update) $noteLink->update(null);
			}
		}
		return $this->response;
	}

    // Identify n-uplets of average for a same account + school year + period + subject, regardless of the class => Keep the one that has an assessment
/*	public function repriseAction()
	{
		$context= Context::getCurrent();
		$school_year = $this->params()->fromQuery('school_year', '2019-2020');
		
		$cursor = NoteLink::getList('report', ['school_year' => $school_year], 'id', 'ASC', 'search');
		$noteLinks = [];
		foreach ($cursor as $noteLink) {
			if ($noteLink->class != $noteLink->account_class) {
				$noteLinks[$noteLink->id] = $noteLink;
//				echo $noteLink->id . ';' . $noteLink->note_id . ';' . $noteLink->place_id . ';' . $noteLink->account_id . ';' . $noteLink->name . ';' . $noteLink->school_period . ';' . $noteLink->class . ';' . $noteLink->account_class . ';' . $noteLink->subject . ';' . (($noteLink->assessment) ? 'Commentaire...' : '') . ';' . $noteLink->evaluation . ';' . "\n";
			}
		}
		
		$deleted = [];
		foreach ($noteLinks as $noteLink) {
			$nUplets = NoteLink::getList('report', ['school_year' => $school_year, 'account_id' => $noteLink->account_id, 'school_period' => $noteLink->school_period, 'subject' => $noteLink->subject], 'id', 'ASC', 'search');

			if (count($nUplets) > 1) {
				$hasComment = null;
				$hasConsistentClass = null;
				foreach ($nUplets as $row) {
					if ($row->class == $row->account_class) $hasConsistentClass = $row;
					if ($row->assessment) $hasComment = $row;
					echo $row->id . ';' . $row->note_id . ';' . $row->place_id . ';' . $row->account_id . ';' . $row->name . ';' . $row->school_period . ';' . $row->subject . ';' . $row->class . ';' . $row->account_class . ';' . (($row->assessment) ? 'Commentaire...' : '') . ';' . $row->evaluation . ';' . "\n";
				}
				if ($hasConsistentClass) $row = $hasConsistentClass;
				elseif ($hasComment) $row = $hasComment;
				if (!$row->assessment && $hasComment) $row->assessment = $hasComment->assessment;
				if (!$row->evaluation && $hasComment) $row->evaluation = $hasComment->evaluation;

				// Delete the rows except the one that is kept
				$kept = $row;
				foreach ($nUplets as $row) {
					if ($row != $kept) {
						$deleted[] = $row->id;
						echo 'Deleting ' . $row->id . ';' . $row->note_id . ';' . $row->place_id . ';' . $row->account_id . ';' . $row->name . ';' . $row->school_period . ';' . $row->subject . ';' . $row->class . ';' . $row->account_class . ';' . (($row->assessment) ? 'Commentaire...' : '') . ';' . $row->evaluation . ';' . "\n";
//						$row->delete(null);
					}
					else {
//						$row->update(null);
						echo 'Updating ' . $row->id . ';' . $row->note_id . ';' . $row->place_id . ';' . $row->account_id . ';' . $row->name . ';' . $row->school_period . ';' . $row->subject . ';' . $row->class . ';' . $row->account_class . ';' . (($row->assessment) ? 'Commentaire...' : '') . ';' . $row->evaluation . ';' . "\n";
					}
				}
				echo "\n";
			}
		}
		echo implode(',', $deleted) . "\n";
		return $this->response;
	}*/
	
	/**
	 * Pour les évaluations (note et report) note.teacher_id = vcard.id et non account.id
	 */
	public function repriseAction()
	{
		$context = Context::getCurrent();
		$place_id = $this->params()->fromQuery('place_id');
		$where = ['school_year' => '2020-2021'];
		if ($place_id) $where['place_id'] = $place_id;
		$notes = Note::getList('evaluation', 'note', $where, 'id', 'ASC', 'search', null);
		foreach ($notes as $note) {
			$teacher = Account::get($note->teacher_id);
			if (!$teacher || $teacher->type != 'teacher') echo 'Teacher ' . $note->teacher_id . " not found\n";
			else {
				echo 'In note ' . $note->id . ', teacher ' . $note->teacher_id . ' replaced by ' . $teacher->contact_1_id . "\n";
				$note->teacher_id = $teacher->contact_1_id;
//				$note->update(null);
			}
		}
		
		return $this->response;
	}
}
