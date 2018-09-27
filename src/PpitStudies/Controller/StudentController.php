<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Commitment;
use PpitCommitment\Model\Event;
use PpitCommitment\Model\Notification;
use PpitCommitment\ViewHelper\SsmlAccountViewHelper;
use PpitCore\Form\CsrfForm;
use PpitCore\Model\Account;
use PpitCore\Model\Community;
use PpitCore\Model\Context;
use PpitCore\Model\Credit;
use PpitCore\Model\Csrf;
use PpitCore\Model\Instance;
use PpitCore\Model\Place;
use PpitCore\Model\Vcard;
use PpitCommitment\ViewHelper\PpitPDF;
use PpitStudies\Model\Absence;
use PpitStudies\Model\Note;
use PpitStudies\Model\NoteLink;
use PpitStudies\Model\Progress;
use PpitStudies\Model\StudentSportImport;
use PpitStudies\ViewHelper\DocumentTemplate;
use PpitStudies\ViewHelper\PdfReportTableViewHelper;
use PpitStudies\ViewHelper\PdfReportViewHelper;
use Zend\Db\Sql\Where;
use Zend\Http\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StudentController extends AbstractActionController
{
	public function getConfigProperties($type) {
		$context = Context::getCurrent();
		$properties = array();
		foreach($context->getConfig('core_account/'.$type)['properties'] as $propertyId) {
			$property = $context->getConfig('core_account/'.$type.'/property/'.$propertyId);
			if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
			$properties[$propertyId] = $property;
		}
		return $properties;
	}
/*
	public function getVcardProperties() {
		$context = Context::getCurrent();
		$properties = array();
		foreach($context->getConfig('vcard/properties') as $propertyId => $property) {
			if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
			$properties[$propertyId] = $property;
		}
		return $properties;
	}*/
	
	public function indexAction()
    {
    	$context = Context::getCurrent();
    	$config = $context->getConfig();
    	$app = $this->params()->fromRoute('app', 'p-pit-studies');
    	$place = Place::get($context->getPlaceId());

		$menu = $context->getConfig('menus/'.$app)['entries'];
    	$currentEntry = $this->params()->fromQuery('entry');

		if ($config['isDemoAccountUpdatable'] || $context->getInstanceId() == 0) $outOfStockCredits = false;
		elseif ($context->getConfig('credit')['unlimitedCredits']) $outOfStockCredits = false;
		else {
			$credit = Credit::get('p-pit-communities', 'type');
			if (!$credit) $outOfStockCredits = true;
			else $outOfStockCredits = ($credit->quantity < 0);
		}

    	return new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getConfig(),
    			'place' => $place,
    			'applicationId' => 'p-pit-studies',
    			'applicationName' => 'P-Pit Studies',
    			'active' => 'application',
    			'menu' => $menu,
    			'currentEntry' => $currentEntry,
    			'outOfStockCredits' => $outOfStockCredits,
    	));
    }

	public function studentHomeAction()
    {
    	$context = Context::getCurrent();
    	$account_id = (int) $this->params()->fromRoute('account_id', 0);
    	$account = Account::get($account_id);
    	$place = Place::get($account->place_id);

     	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getConfig(),
     			'account' => $account,
     			'place' => $place,
    	));
    	$view->setTerminal(true);
    	return $view;
	}

    public function registrationIndexAction()
    {
    	$context = Context::getCurrent();
		$place = Place::get($context->getPlaceId());
		
		$type = $this->params()->fromRoute('type', 'p-pit-studies');
		$configProperties = Account::getConfig($type);
		$vcardProperties = Vcard::getConfig();
		
		$menu = $context->getConfig('menus/'.$type)['entries'];
		$currentEntry = $this->params()->fromQuery('entry');

    	return new ViewModel(array(
    			'context' => $context,
				'configProperties' => $configProperties,
				'vcardProperties' => $vcardProperties,
    			'config' => $context->getConfig(),
    			'place' => $place,
    			'active' => 'application',
    			'applicationId' => $type,
    			'applicationName' => $context->getConfig('ppitApplications')[$type]['labels'][$context->getLocale()],
    			'menu' => $menu,
    			'currentEntry' => $currentEntry,
    			'templates' => array(),
    			'entry' => 'account',
    			'type' => $type,
				'page' => $context->getConfig('core_account/index/'.$type),
				'indexPage' => $context->getConfig('core_account/index/'.$type),
    			'searchPage' => Account::getConfigSearch($type, $configProperties),
				'listPage' => Account::getConfigList($type, $configProperties),
				'detailPage' => $context->getConfig('core_account/detail/'.$type),
				'updatePage' => Account::getConfigUpdate($type, $configProperties),
				'updateContactPage' => $context->getConfig('core_account/updateContact/'.$type),
    			'groupUpdatePage' => Account::getConfigGroupUpdate($type, $configProperties),
    			'status' => 'active',
    	));
    }

    public function getFilters($params)
    {
		$context = Context::getCurrent();
    	
    	// Retrieve the query parameters
    	$filters = array();

    	foreach ($context->getConfig('student/search')['main'] as $propertyId => $rendering) {
    
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
    
    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
				'places' => Place::getList(array()),
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function getList()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    	$params = $this->getFilters($this->params());
    	$major = $this->params()->fromQuery('major');
    	$dir = $this->params()->fromQuery('dir');
    	$limit = $this->params()->fromQuery('limit');
    	 
    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    	$params['status'] = 'active';

    	// Retrieve the list
    	$accounts = Account::getList('p-pit-studies', $params, (($dir == 'DESC') ? '-' : '+').$major, $limit);

    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'accounts' => $accounts,
				'places' => Place::getList(array()),
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
    	$description = Account::getDescription($view->type);
    	 
   		include 'public/PHPExcel_1/Classes/PHPExcel.php';
   		include 'public/PHPExcel_1/Classes/PHPExcel/Writer/Excel2007.php';

		$workbook = new \PHPExcel;
		(new SsmlAccountViewHelper)->formatXls($description, $workbook, $view);		
		$writer = new \PHPExcel_Writer_Excel2007($workbook);
		
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition:inline;filename=Fichier.xlsx ');
		$writer->save('php://output');
    }
    
    public function detailAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if ($id) $account= Account::get($id);
    	else $account = Account::instanciate();
    
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'id' => $account->id,
    			'account' => $account,
    	));
    	$view->setTerminal(true);
    	return $view;
    }
    
    public function groupAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	// Retrieve the type
    	$type = $this->params()->fromRoute(0);
    	 
    	$request = $this->getRequest();
       	if (!$request->isPost()) return $this->redirect()->toRoute('home');
       	$nbAccount = $request->getPost('nb-account');

       	$accounts = array();
       	for ($i = 0; $i < $nbAccount; $i++) {
       		$account = Account::get($request->getPost('account_'.$i));
       		$accounts[] = $account;
       	}
       	$place = Place::get($account->place_id);
       	$school_period = $context->getCurrentPeriod($place->getConfig('school_periods'));
       	
       	$criteria = array();
       	foreach ($context->getConfig('core_account/search/p-pit-studies')['properties'] as $propertyId => $unused) {
			$property = $context->getConfig('core_account/p-pit-studies/property/'.$propertyId);
			if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
			if (in_array($property['type'], array('date', 'time', 'datetime', 'number'))) {
				if ($request->getPost('min_'.$propertyId)) $criteria['min_'.$propertyId] = $request->getPost('min_'.$propertyId);
       			if ($request->getPost('max_'.$propertyId)) $criteria['max_'.$propertyId] = $request->getPost('max_'.$propertyId);
       		}
       		else {
       			if ($request->getPost($propertyId)) $criteria[$propertyId] = $request->getPost($propertyId);
       		}
       	}
       	
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'type' => $type,
    			'criteria' => $criteria,
    			'accounts' => $accounts,
    			'places' => Place::getList(array()),
    			'school_period' => $school_period,
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function addAbsenceAction() {
    
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	// Retrieve the type
    	$type = $this->params()->fromRoute('type', null);
    
    	$absence = Absence::instanciate($type);
    
    	$request = $this->getRequest();
    	$nbCriteria = $request->getPost('nb-criteria');
    	$criteria = array();
    	for ($i = 0; $i < $nbCriteria; $i++) {
    		$criterionId = $request->getPost('criterion-id_'.$i);
    		$criterionValue = $request->getPost('criterion_'.$i);
    		$criteria[$criterionId] = $criterionValue;
//    		if ($criterionId == 'property_7' && !$note->class) $note->class = $criterionValue;
    	}

    	$nbAccount = $request->getPost('nb-account');
    	$accounts = array();
    	for ($i = 0; $i < $nbAccount; $i++) {
    		$account = Account::get($request->getPost('account_'.$i));
    		$accounts[] = $account;
    	}
    	$place = Place::get($account->place_id);
    	$school_period = $context->getCurrentPeriod($place->getConfig('school_periods'));
    	$absence->school_period = $school_period;
    	 
    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	$message = null;

    	if ($request->getPost('category')) {
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    
    		if ($csrfForm->isValid()) { // CSRF check
				
    			$nbCriteria = $request->getPost('nb-criteria');
    			$criteria = array();
    			for ($i = 0; $i < $nbCriteria; $i++) {
    				$criterionId = $request->getPost('criterion-id_'.$i);
    				$criterionValue = $request->getPost('criterion_'.$i);
    				$criteria[$criterionId] = $criterionValue;
    			}
    			 
    			// Load the input data
    			$data = array();
    			$data['category'] = $request->getPost('category');
    			$data['school_year'] = $context->getConfig('student/property/school_year/default');
    			$data['school_period'] = $request->getPost('school_period');
    			$data['subject'] = $request->getPost('subject');
    			$data['motive'] = $request->getPost('motive');
    			$data['begin_date'] = $request->getPost('begin_date');
    			$data['end_date'] = $request->getPost('end_date');
    			$data['duration'] = $request->getPost('duration');
				$data['observations'] = $request->getPost('observations');
    			$data['comment'] = $request->getPost('comment');

    			// Atomically save
    			$connection = Absence::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    				foreach ($accounts as $account) {
    					$data['account_id'] = $account->id;
    					$data['place_id'] = $account->place_id;
    					$rc = $absence->loadData($data);
    					if ($rc != 'OK') throw new \Exception('View error');
    
    					$rc = $absence->add();
    					if ($rc != 'OK') {
    						$connection->rollback();
    						$error = $rc;
    						break;
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
    	 
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'type' => $type,
    			'absence' => $absence,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function addEventAction() {
    
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	// Retrieve the type
    	$category = $this->params()->fromRoute('category', null);
    
    	$event = Event::instanciate('p-pit-studies');
    
    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	$message = null;
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    
    		if ($csrfForm->isValid()) { // CSRF check
    
    			// Load the input data
    			$data = array();
    			$data['category'] = $category;
    			$data['title'] = $request->getPost('title');
    			$data['location'] = $request->getPost('location');
    			if ($request->getPost('begin_date')) $data['begin_time'] = $request->getPost('begin_date').' '.$request->getPost('begin_h').':'.$request->getPost('begin_m').':00';
    			else $data['begin_time'] = null;
    			if ($request->getPost('end_date')) $data['end_time'] = $request->getPost('end_date').' '.$request->getPost('end_h').':'.$request->getPost('end_m').':00';
    			else $data['end_time'] = null;
    			 
    			$nbCriteria = $request->getPost('nb-criteria');
    			$data['criteria'] = array();
    			for ($i = 0; $i < $nbCriteria; $i++) {
    				$criterionId = $request->getPost('criterion-id_'.$i);
    				$criterionValue = $request->getPost('criterion_'.$i);
    				$data['criteria'][$criterionId] = $criterionValue;
    			}
    
    			$nbAccount = $request->getPost('nb-account');
    			$data['target'] = array();
    			for ($i = 0; $i < $nbAccount; $i++) {
    				$data['target'][$request->getPost('account_'.$i)] = null;
    			}
    			$data['comment'] = $request->getPost('comment');
    			 
    			// Atomically save
    			$connection = Event::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    				$rc = $event->loadData($data);
    				if ($rc != 'OK') throw new \Exception('View error');
    
    				$rc = $event->add();
    				if ($rc != 'OK') {
    					$connection->rollback();
    					$error = $rc;
    					break;
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
    			'category' => $category,
    			'event' => $event,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }
    
    public function addNoteAction() {

    	// Retrieve the context
    	$context = Context::getCurrent();
    	$places = Place::getList(array());
    	 
    	// Retrieve the type and class
    	$type = $this->params()->fromRoute('type', null);
    	$class = $this->params()->fromRoute('class', null);

    	$note = Note::instanciate('homework', $class);
		if (count($places) == 1) $note->place_id = current($places)->id;
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
    	else $dropbox = null;

    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	$message = null;
    	$request = $this->getRequest();
    	if (!$request->isPost()) return $this->redirect()->toRoute('home');
    	$nbAccount = $request->getPost('nb-account');
    	$accounts = array();
    	for ($i = 0; $i < $nbAccount; $i++) {
    		$account = Account::get($request->getPost('account_'.$i));
    		$accounts[$account->id] = $account;
    	}
    	$place = Place::get($account->place_id);
		$school_period = $context->getCurrentPeriod($place->getConfig('school_periods'));
		$note->school_period = $school_period;
		
    	$nbCriteria = $request->getPost('nb-criteria');
    	$criteria = array();
    	for ($i = 0; $i < $nbCriteria; $i++) {
    		$criterionId = $request->getPost('criterion-id_'.$i);
    		$criterionValue = $request->getPost('criterion_'.$i);
   			$criteria[$criterionId] = $criterionValue;
    		if ($criterionId == 'property_7' && !$note->class) $note->class = $criterionValue;
    	}

    	if ($request->getPost('date')) {
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    
    		if ($csrfForm->isValid()) { // CSRF check

    			// Load the note data
    			$data = array();
    			$data['place_id'] = $request->getPost('place_id');
    			$data['category'] = 'homework';
    			$data['school_year'] = $context->getConfig('student/property/school_year/default');
    			$data['school_period'] = $school_period;
    			$data['class'] = $request->getPost('class');
    			$data['subject'] = $request->getPost('subject');
    			$data['date'] = $request->getPost('date');
    			$data['type'] = $request->getPost('type');
    			$data['target_date'] = $request->getPost('target_date');
    			$data['observations'] = $request->getPost('observations');
    			$data['document'] = $request->getPost('document');
    			$data['comment'] = $request->getPost('comment');
    			$rc = $note->loadData($data);
				if ($rc != 'OK') throw new \Exception('View error');

    			// Atomically save
    			$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    				$rc = $note->add();
    				if ($rc != 'OK') {
    					$connection->rollback();
    					$error = $rc;
    				}
    				// Save the note at the student level
    				else for ($i = 0; $i < $nbAccount; $i++) {
    					$account = $accounts[$request->getPost('account_'.$i)];
    					$noteLink = NoteLink::instanciate($account->id, $note->id);
    				   	$rc = $noteLink->add();
	    				if ($rc != 'OK') {
	    					$connection->rollback();
	    					$error = $rc;
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

    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
				'places' => $places,
    			'type' => $type,
    			'accounts' => $accounts,
    			'note' => $note,
	    		'dropbox' => $dropbox,
	    		'documentList' => $documentList,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function addEvaluationAction() {
    
    	// Retrieve the context
    	$context = Context::getCurrent();
    	$places = Place::getList(array());
    	
    	// Retrieve the type and class
    	$type = $this->params()->fromRoute('type', null);
    	$class = $this->params()->fromRoute('class', null);

    	$note = Note::instanciate($type, $class);

    	$request = $this->getRequest();
    	if (!$request->isPost()) return $this->redirect()->toRoute('home');
    	$nbAccount = $request->getPost('nb-account');
    	$accounts = array();
    	for ($i = 0; $i < $nbAccount; $i++) {
    		$account = Account::get($request->getPost('account_'.$i));
    		$accounts[$account->id] = $account;
    	}
    	$place = Place::get($account->place_id);
    	$note->place_id = $account->place_id;
    	 
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
    		if (	!$account->place_id
    			||	!array_key_exists('p-pit-admin', $contact->perimeters) 
    			|| 	!array_key_exists('place_id', $contact->perimeters['p-pit-admin'])) {
    			$teachers[$contact->id] = $contact;
    		}
    		else {
    			foreach ($contact->perimeters['p-pit-admin']['place_id'] as $place_id) {
    				if ($account->place_id == $place_id) $teachers[$contact->id] = $contact;
    			}
    		}
    	}
    	if (array_key_exists($context->getContactId(), $teachers)) $note->teacher_id = $context->getContactId();

    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	$message = null;
    
		$school_period = $context->getCurrentPeriod($place->getConfig('school_periods'));
    	$note->school_period = $school_period;

    	$nbCriteria = $request->getPost('nb-criteria');
    	$criteria = array();
    	for ($i = 0; $i < $nbCriteria; $i++) {
    		$criterionId = $request->getPost('criterion-id_'.$i);
    		$criterionValue = $request->getPost('criterion_'.$i);
    		$criteria[$criterionId] = $criterionValue;
    		if ($criterionId == 'property_7' && !$note->class) $note->class = $criterionValue;
    	}
    
    	if ($request->getPost('date')) {
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
    			$nbCriteria = $request->getPost('nb-criteria');
    			$data['criteria'] = array();
    			for ($i = 0; $i < $nbCriteria; $i++) {
    				$criterionId = $request->getPost('criterion-id_'.$i);
    				$criterionValue = $request->getPost('criterion_'.$i);
    				$data['criteria'][$criterionId] = $criterionValue;
    			}
    			$previousNote = Note::retrieve($data['place_id'], 'evaluation', $type, $data['class'], $data['school_year'], $data['school_period'], $data['subject'], $data['level'], $data['date']);
    			if ($previousNote) $note = $previousNote;
    			else $note->links = array();
    			if ($type == 'report') {
    				$computedAverages = Note::computePeriodAverages($data['place_id'], $data['school_year'], $data['class'], $data['school_period'], $data['subject']);
    			}
    		    elseif ($type == 'exam') {
    				$examAverages = Note::computeExamAverages($data['place_id'], $data['school_year'], $data['class'], $data['level']);
    			}
    			$nbAccount = $request->getPost('nb-account');
    			for ($i = 0; $i < $nbAccount; $i++) {
    				$account = $accounts[$request->getPost('account_'.$i)];
	    			$noteLink = NoteLink::instanciate($account->id, null);
    				$value = $request->getPost('value_'.$account->id);
    				if ($value == '') $value = null;
    				$mention = $request->getPost('mention_'.$account->id);
    				$assessment = $request->getPost('assessment_'.$account->id);
	    			$audit = [];
    				if ($type == 'report' && $value === null) {
    				    if ($data['subject'] == 'global') {
    			    		$value = $noteLink->computeStudentAverage($data['school_year'], $data['school_period']);
    			    	}
    					elseif (array_key_exists($account->id, $computedAverages)) {
							$value = $computedAverages[$account->id]['global']['note'];
							$audit = $computedAverages[$account->id]['global']['notes'];
						}
    			    	else $value = null;
    			    	if ($value !== null) $value = $value * $data['reference_value'] / 20;
    				}
    			    elseif ($type == 'exam' && $value === null) {
    					if (array_key_exists($account->id, $examAverages)) {
							$value = $examAverages[$account->id]['global']['note'];
							$audit = $examAverages[$account->id]['global']['notes'];
						}
    			    	else $value = null;
    			    	if ($value !== null) $value = $value * $data['reference_value'] / 20;
    				}
    				if ($value !== null || $assessment) {
	    				$noteLink->value = $value;
	    				$noteLink->evaluation = $mention;
	    				$noteLink->assessment = $assessment;
	    				$noteLink->distribution = array();
		    			if ($type == 'report') {
	    					if (array_key_exists($account->id, $computedAverages)) {
			    				foreach ($computedAverages[$account->id] as $categoryId => $category) {
			    					$noteLink->distribution[$categoryId] = $category['note'];
			    				}
			    				$noteLink->audit = $audit;
	    					}
		    			}
	    				if (array_key_exists($noteLink->account_id, $note->links)) $note->links[$noteLink->account_id]->delete(null); 
	    				$note->links[$noteLink->account_id] = $noteLink;
    				}
    			}
    			$noteCount = 0; $noteSum = 0; $lowerNote = 999; $higherNote = 0;
    			foreach ($note->links as $noteLink) {
    			    if ($noteLink->value !== null) {
    					$noteSum += $noteLink->value;
    					$noteCount++;
	    				if ($noteLink->value < $lowerNote) $lowerNote = $noteLink->value;
	    				if ($noteLink->value > $higherNote) $higherNote = $noteLink->value;
    			    }
    			}
    			if ($noteCount > 0) {
    				$data['average_note'] = round($noteSum / $noteCount, 2);
	    			$data['lower_note'] = $lowerNote;
	    			$data['higher_note'] = $higherNote;
    			};
    			if (count($note->links)) {
	    			$rc = $note->loadData($data);
	    			if ($rc == 'Integrity') throw new \Exception('View error');
	    			if ($rc == 'Duplicate') $error = $rc;
	    			else {
		
		    			// Atomically save
		    			$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
		    			$connection->beginTransaction();
		    			try {
		    				if ($note->id) $rc = $note->update(null);
		    				else $rc = $note->add();
		    				if ($rc != 'OK') {
		    					$connection->rollback();
		    					$error = $rc;
		    				}
		    				// Save the note at the student level
		    				else foreach ($note->links as $noteLink) {
		    					if (!$noteLink->id) {
			    					$noteLink->note_id = $note->id;
		    						$rc = $noteLink->add();
		    					}
			    				if ($rc != 'OK') {
			    					$connection->rollback();
			    					$error = $rc;
			    					break;
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
    	}
    
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'places' => $places,
    			'teachers' => $teachers,
    			'type' => $type,
    			'accounts' => $accounts,
    			'note' => $note,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }
    
    public function addNotificationAction() {
    
    	// Retrieve the context
    	$context = Context::getCurrent();

    	// Retrieve the type
    	$category = $this->params()->fromRoute('category', null);

    	$notification = Notification::instanciate('p-pit-studies');

		$documentList = array();
    	if (array_key_exists('dropbox', $context->getConfig('ppitDocument'))) {
			require_once "vendor/dropbox/dropbox-sdk/lib/Dropbox/autoload.php";
		    $dropbox = $context->getConfig('ppitDocument')['dropbox'];
    		$dropboxClient = new \Dropbox\Client($dropbox['credential'], $dropbox['clientIdentifier']);
			try {
				$properties = $dropboxClient->getMetadataWithChildren($context->getConfig('ppitDocument')['dropbox']['folders'][$category]);
				foreach ($properties['contents'] as $content) $documentList[] = $content['path'];
	    	}
	    	catch(\Exception $e) {}
		}

    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	$message = null;
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    
    		if ($csrfForm->isValid()) { // CSRF check
    
    			// Load the input data
    			$data = array();
    			$data['category'] = $category;
    			$data['title'] = $request->getPost('title');

    			$data['content'] = $request->getPost('content');
    			if ($request->getPost('image_src')) {
	    		   	$data['image'] = array(
	    				'src' => $request->getPost('image_src'),
	    		   		'style' => $request->getPost('image_style'),
	    				'width' => $request->getPost('image_width'),
	    				'href' => $request->getPost('image_href'),
	    				'target' => '_blank',
	    		   	);
    			}

    		   	$data['begin_date'] = $request->getPost('begin_date');
    			$data['end_date'] = $request->getPost('end_date');    			

    			$data['attachment_label'] = $request->getPost('attachment_label');
    			$data['attachment_path'] = $request->getPost('attachment_path');
    			if ($data['attachment_path'] && array_key_exists('dropbox', $context->getConfig('ppitDocument'))) $data['attachment_type'] = 'dropbox';
    			 
    			$data['comment'] = $request->getPost('comment');

    			$nbCriteria = $request->getPost('nb-criteria');
    			$data['criteria'] = array();
    			for ($i = 0; $i < $nbCriteria; $i++) {
    				$criterionId = $request->getPost('criterion-id_'.$i);
    				$criterionValue = $request->getPost('criterion_'.$i);
    				$data['criteria'][$criterionId] = $criterionValue;
    			}
    		    
    			$nbAccount = $request->getPost('nb-account');
    			$data['target'] = array();
    			for ($i = 0; $i < $nbAccount; $i++) {
    				$data['target'][$request->getPost('account_'.$i)] = null;
    			}

    			// Atomically save
    			$connection = Notification::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
					$rc = $notification->loadData($data);
    				if ($rc != 'OK') throw new \Exception('View error');
    
    				$rc = $notification->add();
    				if ($rc != 'OK') {
    					$connection->rollback();
    					$error = $rc;
   						break;
   					}
    				if (!$error) {
    					// Write the loaded images
						$files = $request->getFiles()->toArray();
						if ($files) foreach ($files as $file) Instance::saveFile($file);

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
    			'category' => $category,
    			'notification' => $notification,
    			'documentList' => $documentList,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }
    
	public function addProgressAction() {
    	 
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	// Retrieve the type
    	$type = $this->params()->fromRoute('type', null);
    	$progress = Progress::instanciate($type);
    
    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	$message = null;
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    
    		if ($csrfForm->isValid()) { // CSRF check

    			$nbAccount = $request->getPost('nb-account');
    			$accounts = array();
    			for ($i = 0; $i < $nbAccount; $i++) {
    				$account = Account::get($request->getPost('account_'.$i));
    				$accounts[] = $account;
    			}
    			
    			$nbCriteria = $request->getPost('nb-criteria');
    			$criteria = array();
    			for ($i = 0; $i < $nbCriteria; $i++) {
    				$criterionId = $request->getPost('criterion-id_'.$i);
    				$criterionValue = $request->getPost('criterion_'.$i);
    				$criteria[$criterionId] = $criterionValue;
    			}
    			 
    			// Load the input data
    			$data = array();
    			$data['school_year'] = $request->getPost('school_year');
    			$data['period'] = $request->getPost('period');
    			$data['comment'] = $request->getPost('comment');
    
    			// Atomically save
    			$connection = Progress::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    				foreach ($accounts as $account) {
    
    					$data['account_id'] = $account->id;
    					$progress->type = $account->properties['property_1'];
    
    					$rc = $progress->loadData($account->property_1, $data);
    					if ($rc != 'OK') throw new \Exception('View error');
    
    					// Check the existence and ignore in that case
    					if (Progress::retrieveExisting($progress)) break;
    
    					// Retrieve the previous criteria values from the most recent progress
    					$lastProgress = Progress::retrievePrevious($progress);
    					if ($lastProgress) $progress->criteria = $lastProgress->criteria;
    
    					$rc = $progress->add();
    					if ($rc != 'OK') {
    						$connection->rollback();
    						$error = $rc;
    						break;
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
    	 
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'type' => $type,
    			'progress' => $progress,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }

	public function planningAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
	
		$contact_id = (int) $this->params()->fromRoute('id');
		$account = Account::get($contact_id, 'contact_1_id');
		$eventsRoute = $this->url()->fromRoute('studentEvent/planning', array('id' => ($account) ? $account->id : null), array('force_canonical' => true));

		// Return the link list
		$view = new ViewModel(array(
				'context' => $context,
				'eventsRoute' => $eventsRoute,
		));
		$view->setTerminal(true);
		return $view;
	}

	public function fileAction()
	{
		$context = Context::getCurrent();

		$account_id = (int) $this->params()->fromRoute('id');
		$account = Account::get($account_id);
		
		$documentList = array();
    	if (array_key_exists('dropbox', $context->getConfig('ppitDocument'))) {
    		require_once "vendor/dropbox/dropbox-sdk/lib/Dropbox/autoload.php";
    		$dropbox = $context->getConfig('ppitDocument')['dropbox'];
    		$dropboxClient = new \Dropbox\Client($dropbox['credential'], $dropbox['clientIdentifier']);
    		try {
    			$properties = $dropboxClient->getMetadataWithChildren($dropbox['folders']['students']);
    			foreach ($properties['contents'] as $content) $documentList[] = substr($content['path'], strrpos($content['path'], '/')+1);
    		}
    		catch(\Exception $e) {}
    	}
    	else $dropbox = null;

    	$view = new ViewModel(array(
    			'context' => $context,
	    		'dropbox' => $dropbox,
	    		'documentList' => $documentList,
    	));
    	$view->setTerminal(true);
    	return $view;
	}

	public function linkAction()
	{
		$context = Context::getCurrent();
		$document = $this->params()->fromRoute('document', 0);
		require_once "vendor/dropbox/dropbox-sdk/lib/Dropbox/autoload.php";
		$dropbox = $context->getConfig('ppitDocument')['dropbox'];
		$dropboxClient = new \Dropbox\Client($dropbox['credential'], $dropbox['clientIdentifier']);
		$link = $dropboxClient->createTemporaryDirectLink($dropbox['folders']['students'].'/'.$document);
		if ($link[0]) return $this->redirect()->toUrl($link[0]);
		else return $this->response;
	}

	public function absenceAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
	
		$contact_id = (int) $this->params()->fromRoute('id');
		$account = Account::get($contact_id, 'contact_1_id');
		$absLates = Absence::getList('schooling', array('account_id' => $account->id, 'school_year' => $context->getConfig('student/property/school_year/default')), 'begin_date', 'DESC', 'search');
		$absences = array();
		$absenceCount = 0;
		$cumulativeAbsence = 0;
		$latenesss = array();
		$latenessCount = 0;
		$cumulativeLateness = 0;
		foreach ($absLates as $absLate) {
			if ($absLate->category == 'absence') {
				$absences[] = $absLate;
				$absenceCount++;
				$cumulativeAbsence += $absLate->duration;
			}
			elseif ($absLate->category =='lateness') {
				$latenesss[] = $absLate;
				$latenessCount++;
				$cumulativeLateness += $absLate->duration;
			}
		}

		$periods = array();
		$absLates = Absence::GetList('schooling', array('account_id' => $account->id, 'school_year' => $context->getConfig('student/property/school_year/default')), 'date', 'DESC', 'search');
		foreach($absLates as $absLate) {
			$key = $absLate->school_year.'.'.$absLate->school_period;
			if (!array_key_exists($key, $periods)) $periods[$key] = array();
			$periods[$key][] = $absLate;
		}
		krsort($periods);

		// Return the link list
		$view = new ViewModel(array(
				'context' => $context,
				'config' => $context->getconfig(),
				'account' => $account,
				'periods' => $periods,
				'absences' => $absences,
				'absenceCount' => $absenceCount,
				'cumulativeAbsence' => $cumulativeAbsence,
				'latenesss' => $latenesss,
				'latenessCount' => $latenessCount,
				'cumulativeLateness' => $cumulativeLateness,
		));
		$view->setTerminal(true);
		return $view;
	}

	public function homeworkAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
	
		$contact_id = (int) $this->params()->fromRoute('id');
		$account = Account::get($contact_id, 'contact_1_id');
	
		$notes = NoteLink::GetList(null, array('category' => 'homework', 'account_id' => $account->id, 'school_year' => $context->getConfig('student/property/school_year/default')), 'date', 'DESC', 'search');
	
		// Return the link list
		$view = new ViewModel(array(
				'context' => $context,
				'config' => $context->getconfig(),
				'account' => $account,
				'notes' => $notes,
		));
		$view->setTerminal(true);
		return $view;
	}

	public function progressAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
		$account_id = (int) $this->params()->fromRoute('id');
		$account = Account::get($account_id);
		$progresses = Progress::retrieveAll($account->property_1, $account_id);
	
		// Return the link list
		$view = new ViewModel(array(
				'context' => $context,
				'config' => $context->getconfig(),
				'type' => $account->property_1,
				'account' => $account,
				'progresses' => $progresses,
		));
		$view->setTerminal(true);
		return $view;
	}

	public function evaluationAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
	
		$contact_id = (int) $this->params()->fromRoute('id');
		$account = Account::get($contact_id, 'contact_1_id');
		$school_year = $context->getConfig('student/property/school_year/default');
		$place = Place::get($account->place_id);
		$school_period = $context->getCurrentPeriod($place->getConfig('school_periods'));
		$mock = $this->params()->fromRoute('mock');

		$periods = array();
		$params = array('account_id' => $account->id/*, 'school_year' => $school_year, 'school_period' => $school_period*/);
		if ($mock) $params['level'] = "mock";
		$notes = NoteLink::GetList('note', $params, 'date', 'DESC', 'search');
		foreach($notes as $note) {
			$key = $note->school_year.'.'.$note->school_period;
			if (!array_key_exists($key, $periods)) $periods[$key] = array();
			$periods[$key][] = $note;
		}
		krsort($periods);
		
		// Return the link list
		$view = new ViewModel(array(
				'context' => $context,
				'config' => $context->getconfig(),
				'account' => $account,
				'periods' => $periods,
				'mock' => $mock,
		));
		$view->setTerminal(true);
		return $view;
	}

	public function examAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
	
		$contact_id = (int) $this->params()->fromRoute('id');
		$account = Account::get($contact_id, 'contact_1_id');
		$place = Place::get($account->place_id);
	
		$periods = array();
		$notes = NoteLink::GetList('exam', ['account_id' => $account->id], 'date', 'DESC', 'search');
		foreach($notes as $note) {
			$key = $note->level;
			if (!array_key_exists($key, $periods)) $periods[$key] = array();
			$periods[$key][] = $note;
		}
		krsort($periods);
		foreach ($periods as $periodId => &$period) {
			$notes = NoteLink::GetList('note', ['account_id' => $account->id, 'level' => $periodId], 'date', 'DESC', 'search');
			foreach($notes as $note) {
				$period[] = $note;
			}
		}

		// Return the link list
		$view = new ViewModel(array(
			'context' => $context,
			'config' => $context->getconfig(),
			'account' => $account,
			'periods' => $periods,
		));
		$view->setTerminal(true);
		return $view;
	}
	
	public function reportAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
	
		$contact_id = (int) $this->params()->fromRoute('id');
		$account = Account::get($contact_id, 'contact_1_id');
	
		$periods = array();
		$notes = NoteLink::GetList('report', array('account_id' => $account->id), 'date', 'DESC', 'search');
		foreach($notes as $note) {
			$key = $note->school_year.'.'.$note->school_period;
			if (!array_key_exists($key, $periods)) $periods[$key] = array();
			$periods[$key][] = $note;
		}
		krsort($periods);

		// Return the link list
		$view = new ViewModel(array(
				'context' => $context,
				'config' => $context->getconfig(),
				'account' => $account,
				'periods' => $periods,
		));
		$view->setTerminal(true);
		return $view;
	}
	
	public function downloadAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
		$category = $this->params()->fromRoute('category');
		$account_id = (int) $this->params()->fromRoute('account_id');
		$account = Account::get($account_id);
		$account->properties = $account->getProperties();
		if ($account->contact_2 && $account->contact_2->adr_street) $addressee = $account->contact_2;
		elseif ($account->contact_3 && $account->contact_3->adr_street) $addressee = $account->contact_3;
		elseif ($account->contact_4 && $account->contact_4->adr_street) $addressee = $account->contact_4;
		elseif ($account->contact_5 && $account->contact_5->adr_street) $addressee = $account->contact_5;
		else $addressee = $account->contact_1;
		
		$school_year = $this->params()->fromRoute('school_year');
		if (!$school_year) $school_year = $context->getConfig('student/property/school_year/default');
		$place = Place::get($account->place_id);
		$school_period = $this->params()->fromRoute('school_period');
		if (!$school_period) {
			$school_period = $this->params()->fromRoute('school_period');
			$school_period = $context->getCurrentPeriod($place->getConfig('school_periods'));
		}
		$level = $this->params()->fromRoute('level');

		$absLates = Absence::getList(null, array('account_id' => $account_id, 'school_year' => $school_year, 'school_period' => $school_period), 'date', 'DESC', 'search');
		$absences = array();
		$latenesss = array();
		$cumulativeAbsence = 0;
		$cumulativeLateness = 0;
		$absenceCount = 0;
		$latenessCount = 0;
		foreach ($absLates as $absLate) {
			if ($absLate->category == 'absence') {
				$absences[] = $absLate;
				$cumulativeAbsence += $absLate->duration;
				$absenceCount++;
			}
			elseif ($absLate->category =='lateness') {
				$latenesss[] = $absLate;
				$cumulativeLateness += $absLate->duration;
				$latenessCount++;
			}
		}
		$date = null;
		$classSize = null;
		if ($category == 'report') {
			$averages = NoteLink::GetList($category, array('account_id' => $account_id, 'school_year' => $school_year, 'school_period' => $school_period), 'date', 'DESC', 'search');
			foreach ($averages as $average) if ($average->subject == 'global') {
				$date = $average->date;
				$classSize = count(NoteLink::GetList($category, array('note_id' => $average->note_id), 'date', 'DESC', 'search'));
			}
		}
		else $averages = null;

		$params = array('account_id' => $account_id, 'school_year' => $school_year, 'school_period' => $school_period);
		if ($level) $params['level'] = $level;
		$notes = NoteLink::GetList('note', $params, 'subject', 'ASC', 'search');
		if (!$date) foreach ($notes as $note) if ($note->subject == 'global') $date = $note->date;
		
    	// create new PDF document
    	$pdf = new PpitPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    	PdfReportViewHelper::render($category, $pdf, $place, $school_year, $school_period, $date, $account, $addressee, $averages, $notes, $absenceCount, $cumulativeAbsence, $latenessCount, $cumulativeLateness, $absences, $latenesss, $classSize, $absLates, $level);
    	
    	// Close and output PDF document
    	// This method has several options, check the source code documentation for more information.
    	$content = $pdf->Output('school-report-'.$context->getInstance()->caption.'-'.$account->name.'.pdf', 'I');
    	return $this->response;
	}

	public function downloadExamAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
		$account_id = (int) $this->params()->fromRoute('account_id');
		$account = Account::get($account_id);
		$account->properties = $account->getProperties();
		if ($account->contact_2 && $account->contact_2->adr_street) $addressee = $account->contact_2;
		elseif ($account->contact_3 && $account->contact_3->adr_street) $addressee = $account->contact_3;
		elseif ($account->contact_4 && $account->contact_4->adr_street) $addressee = $account->contact_4;
		elseif ($account->contact_5 && $account->contact_5->adr_street) $addressee = $account->contact_5;
		else $addressee = $account->contact_1;
	
		$school_year = $this->params()->fromRoute('school_year');
		if (!$school_year) $school_year = $context->getConfig('student/property/school_year/default');
		$place = Place::get($account->place_id);
		$level = $this->params()->fromRoute('level');
	
		$date = null;
	
		$params = array('account_id' => $account_id, 'school_year' => $school_year, 'level' => $level);
		$notes = NoteLink::GetList('note', $params, 'subject', 'ASC', 'search');
		if (!$date) foreach ($notes as $note) if ($note->subject == 'global') $date = $note->date;

		$classSize = null;
		$averages = NoteLink::GetList('exam', array('account_id' => $account_id, 'school_year' => $school_year, 'level' => $level), 'date', 'DESC', 'search');
		foreach ($averages as $average) if ($average->subject == 'global') {
			$date = $average->date;
			$classSize = count(NoteLink::GetList('exam', array('note_id' => $average->note_id), 'date', 'DESC', 'search'));
			$notes[] = $average;
		}
	
		// create new PDF document
		$pdf = new PpitPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
		PdfReportViewHelper::render('exam', $pdf, $place, $school_year, $level, $date, $account, $addressee, $notes, $notes, 0, 0, 0, 0, [], [], $classSize, [], $level);
		 
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$content = $pdf->Output('school-report-'.$context->getInstance()->caption.'-'.$account->name.'.pdf', 'I');
		return $this->response;
	}
	
	public function dropboxLinkAction()
	{
		$context = Context::getCurrent();
		$document = $this->params()->fromRoute('document', 0);
		require_once "vendor/dropbox/dropbox-sdk/lib/Dropbox/autoload.php";
		$dropbox = $context->getConfig('ppitDocument')['dropbox'];
		$dropboxClient = new \Dropbox\Client($dropbox['credential'], $dropbox['clientIdentifier']);
		$link = $dropboxClient->createTemporaryDirectLink($dropbox['folders']['schooling'].'/'.$document);
		if ($link[0]) return $this->redirect()->toUrl($link[0]);
		else return $this->response;
	}
	
	public function letter($template, $data, $logo_src, $logo_width, $logo_height, $footer)
	{
		// Retrieve the context
		$context = Context::getCurrent();
		
		$noImage = 1; //on incrémentera pour chaque image différente
		$extImage = explode(".",$logo_src);
		$extImage = $extImage[count($extImage)-1]; //on récupère l'extension de l'image
		$logo = "<w:pict>\n";
		$logo .= '<w:binData w:name="wordml://03000'.str_pad($noImage,3,"0",STR_PAD_LEFT).'.'.$extImage.'" xml:space="preserve">';
		$content = file_get_contents('public/'.$logo_src);
		$logo .= base64_encode($content);
		$logo .= "\n</w:binData>\n";
		$logo .= '<v:shape id="_x0000_i' . $noImage
			   . '" type="#_x0000_t75" style="width:'.$logo_width.'pt;height:'.$logo_height.'pt">'."\n";
		$logo .= '<v:imagedata src="wordml://03000'.str_pad($noImage,3,"0",STR_PAD_LEFT).'.'.$extImage.'" o:title="'.$context->getConfig('headerParams')['logo'].'"/>';
		$logo .= "</v:shape>\n</w:pict>\n";

/*		$noImage = 2; //on incrémentera pour chaque image différente
		$extImage = explode(".",$context->getConfig('headerParams')['footer-img']);
		$extImage = $extImage[count($extImage)-1]; //on récupère l'extension de l'image
//		$footer = $context->getConfig('headerParams')['footer']['value'];
		$footer = "<w:pict>\n";
		$footer .= '<w:binData w:name="wordml://03000'.str_pad($noImage,3,"0",STR_PAD_LEFT).'.'.$extImage.'" xml:space="preserve">';
		$content = file_get_contents('public/logos/'.$context->getConfig('headerParams')['footer-img']);
		$footer .= base64_encode($content);
		$footer .= "\n</w:binData>\n";
		$footer .= '<v:shape id="_x0000_i' . $noImage
		. '" type="#_x0000_t75" style="width:'.$context->getConfig('headerParams')['footer-width'].'pt;height:'.$context->getConfig('headerParams')['footer-height'].'pt">'."\n";
		$footer .= '<v:imagedata src="wordml://03000'.str_pad($noImage,3,"0",STR_PAD_LEFT).'.'.$extImage.'" o:title="'.$context->getConfig('headerParams')['footer-img'].'"/>';
		$footer .= "</v:shape>\n</w:pict>\n";*/
		
		DocumentTemplate::$letterTemplate = str_replace("@LOGO@", $logo, DocumentTemplate::$letterTemplate);
		DocumentTemplate::$letterTemplate = str_replace("@FOOTER@", $footer, DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('address1', $template)) {
			$arguments = array();
			foreach ($template['address1']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_1@", vsprintf($template['address1']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_1@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('address2', $template)) {
			$arguments = array();
			foreach ($template['address2']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_2@", vsprintf($template['address2']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_2@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('address3', $template)) {
			$arguments = array();
			foreach ($template['address3']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_3@", vsprintf($template['address3']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_3@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('address4', $template)) {
			$arguments = array();
			foreach ($template['address4']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_4@", vsprintf($template['address4']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_4@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('address5', $template)) {
			$arguments = array();
			foreach ($template['address5']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_5@", vsprintf($template['address5']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_5@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('address6', $template)) {
			$arguments = array();
			foreach ($template['address6']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_6@", vsprintf($template['address6']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@ADDRESS_6@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('title', $template)) {
			$arguments = array();
			foreach ($template['title']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@TITLE@", vsprintf($template['title']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@TITLE@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph1a', $template)) {
			$arguments = array();
			foreach ($template['paragraph1a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P1A@", vsprintf($template['paragraph1a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P1A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph1B', $template)) {
			$arguments = array();
			foreach ($template['paragraph1b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P1B@", vsprintf($template['paragraph1b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P1B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph2a', $template)) {
			$arguments = array();
			foreach ($template['paragraph2a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P2A@", vsprintf($template['paragraph2a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P2A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph2b', $template)) {
			$arguments = array();
			foreach ($template['paragraph2b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P2B@", vsprintf($template['paragraph2b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P2B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph3a', $template)) {
			$arguments = array();
			foreach ($template['paragraph3a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P3A@", vsprintf($template['paragraph3a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P3A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph3b', $template)) {
			$arguments = array();
			foreach ($template['paragraph3b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P3B@", vsprintf($template['paragraph3b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P3B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph4a', $template)) {
			$arguments = array();
			foreach ($template['paragraph4a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P4A@", vsprintf($template['paragraph4a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P4A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph4b', $template)) {
			$arguments = array();
			foreach ($template['paragraph4b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P4B@", vsprintf($template['paragraph4b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P4B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph5a', $template)) {
			$arguments = array();
			foreach ($template['paragraph5a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P5A@", vsprintf($template['paragraph5a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P5A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph5b', $template)) {
			$arguments = array();
			foreach ($template['paragraph5b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P5B@", vsprintf($template['paragraph5b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P5B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph6a', $template)) {
			$arguments = array();
			foreach ($template['paragraph6a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P6A@", vsprintf($template['paragraph6a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P6A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph6b', $template)) {
			$arguments = array();
			foreach ($template['paragraph6b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P6B@", vsprintf($template['paragraph6b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P6B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph7a', $template)) {
			$arguments = array();
			foreach ($template['paragraph7a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P7A@", vsprintf($template['paragraph7a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P7A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph7b', $template)) {
			$arguments = array();
			foreach ($template['paragraph7b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P7B@", vsprintf($template['paragraph7b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P7B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph8a', $template)) {
			$arguments = array();
			foreach ($template['paragraph8a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P8A@", vsprintf($template['paragraph8a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P8A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph8b', $template)) {
			$arguments = array();
			foreach ($template['paragraph8b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P8B@", vsprintf($template['paragraph8b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P8B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph9a', $template)) {
			$arguments = array();
			foreach ($template['paragraph9a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P9A@", vsprintf($template['paragraph9a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P9A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph9b', $template)) {
			$arguments = array();
			foreach ($template['paragraph9b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P9B@", vsprintf($template['paragraph9b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P9B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph10a', $template)) {
			$arguments = array();
			foreach ($template['paragraph10a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P10A@", vsprintf($template['paragraph10a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P10A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph10b', $template)) {
			$arguments = array();
			foreach ($template['paragraph10b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P10B@", vsprintf($template['paragraph10b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P10B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph11a', $template)) {
			$arguments = array();
			foreach ($template['paragraph11a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P11A@", vsprintf($template['paragraph11a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P11A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph11b', $template)) {
			$arguments = array();
			foreach ($template['paragraph11b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P11B@", vsprintf($template['paragraph11b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P11B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph12a', $template)) {
			$arguments = array();
			foreach ($template['paragraph12a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P12A@", vsprintf($template['paragraph12a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P12A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph12b', $template)) {
			$arguments = array();
			foreach ($template['paragraph12b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P12B@", vsprintf($template['paragraph12b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P12B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph13a', $template)) {
			$arguments = array();
			foreach ($template['paragraph13a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P13A@", vsprintf($template['paragraph13a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P13A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph13b', $template)) {
			$arguments = array();
			foreach ($template['paragraph13b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P13B@", vsprintf($template['paragraph13b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P13B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph14a', $template)) {
			$arguments = array();
			foreach ($template['paragraph14a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P14A@", vsprintf($template['paragraph14a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P14A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph14b', $template)) {
			$arguments = array();
			foreach ($template['paragraph14b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P14B@", vsprintf($template['paragraph14b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P14B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph15a', $template)) {
			$arguments = array();
			foreach ($template['paragraph15a']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P15A@", vsprintf($template['paragraph15a']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P15A@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('paragraph15b', $template)) {
			$arguments = array();
			foreach ($template['paragraph15b']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@P15B@", vsprintf($template['paragraph15b']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@P15B@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('signature1', $template)) {
			$arguments = array();
			foreach ($template['signature1']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@SIGNATURE_1@", vsprintf($template['signature1']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@SIGNATURE_1@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('signature2', $template)) {
			$arguments = array();
			foreach ($template['signature2']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@SIGNATURE_2@", vsprintf($template['signature2']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@SIGNATURE_2@", '', DocumentTemplate::$letterTemplate);
		
		if (array_key_exists('signature3', $template)) {
			$arguments = array();
			foreach ($template['signature3']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@SIGNATURE_3@", vsprintf($template['signature3']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@SIGNATURE_3@", '', DocumentTemplate::$letterTemplate);

		if (array_key_exists('signature4', $template)) {
			$arguments = array();
			foreach ($template['signature4']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@SIGNATURE_4@", vsprintf($template['signature4']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@SIGNATURE_4@", '', DocumentTemplate::$letterTemplate);

		if (array_key_exists('signature5', $template)) {
			$arguments = array();
			foreach ($template['signature5']['params'] as $param) $arguments[] = $data[$param];
			DocumentTemplate::$letterTemplate = str_replace("@SIGNATURE_5@", vsprintf($template['signature5']['text'], $arguments), DocumentTemplate::$letterTemplate);
		}
		else DocumentTemplate::$letterTemplate = str_replace("@SIGNATURE_5@", '', DocumentTemplate::$letterTemplate);

		header('Content-Type: application/msword; charset=utf-8');
		header("Content-disposition: filename=confirmation.doc");
		echo DocumentTemplate::$letterTemplate;
		return $this->response;
	}
	
    public function confirmationAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();

    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) return $this->redirect()->toRoute('home');
    	$commitment = Commitment::get($id);
    	
    	$template = $context->getConfig('student/confirmation');
    	if ($commitment->account->contact_2) $invoicing_contact = $commitment->account->contact_2;
    	elseif ($commitment->account->contact_3) $invoicing_contact = $commitment->account->contact_3;
    	else $invoicing_contact = Vcard::instanciate();
    	
    	$data = array(
    		'invoicing_n_title' => $invoicing_contact->n_title,
    		'invoicing_n_last' => $invoicing_contact->n_last,
    		'invoicing_n_first' => $invoicing_contact->n_first,
    		'invoicing_adr_street' => $invoicing_contact->adr_street,
    		'invoicing_adr_zip' => $invoicing_contact->adr_zip,
    		'invoicing_adr_city' => $invoicing_contact->adr_city,
    		'invoicing_adr_country' => $invoicing_contact->adr_country,
    		'date' => date('d/m/Y'),
    		'n_last' => $commitment->account->contact_1->n_last,
    		'n_first' => $commitment->account->contact_1->n_first,
    		'adr_street' => $commitment->account->contact_1->adr_street,
    		'adr_zip' => $commitment->account->contact_1->adr_zip,
    		'adr_city' => $commitment->account->contact_1->adr_city,
    		'adr_country' => $commitment->account->contact_1->adr_country,
    		'birth_date' => $context->decodeDate($commitment->account->contact_1->birth_date),
    		'caption' => $commitment->caption,
    		'sport' => $commitment->account->property_1,
    		'class' => $commitment->property_1.' '.$commitment->property_2,
    		'place' => $commitment->account->place_caption,
    	);
    	
		$place = Place::get($commitment->account->place_id);
		if ($place && $place->logo_src) {
			$logo_src = $place->logo_src;
			$logo_width = $place->logo_width*1/3;
			$logo_height = $place->logo_height*1/3;
		}
    	else {
    		$logo_src = 'logos/'.$context->getInstance()->caption.'/'.$context->getConfig('headerParams')['logo'];
			$logo_width = $context->getConfig('headerParams')['logo-width']*1/3;
			$logo_height = $context->getConfig('headerParams')['logo-height']*1/3;
    	}
		$footer = ($place->legal_footer) ? $place->legal_footer : $context->getConfig('headerParams')['footer']['value'];
    	return $this->letter($template, $data, $logo_src, $logo_width, $logo_height, $footer);
    }

    public function certificateAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$id = (int) $this->params()->fromRoute('eid', 0);
    	if (!$id) {
    		return $this->redirect()->toRoute('eleve', array(
    				'action' => 'index'
    		));
    	}
    	$eleve = Eleve::getTable()->getEleve($id);

    	DocumentTemplate::$attestationTemplate = str_replace("@PRENOM@", $eleve->prenoms, DocumentTemplate::$attestationTemplate);
    	DocumentTemplate::$attestationTemplate = str_replace("@NOM@", $eleve->nom_famille, DocumentTemplate::$attestationTemplate);
    	DocumentTemplate::$attestationTemplate = str_replace("@CLASSE@", $eleve->classe, DocumentTemplate::$attestationTemplate);
    	DocumentTemplate::$attestationTemplate = str_replace("@DATE@", date('d/m/Y'), DocumentTemplate::$attestationTemplate);
    
    	$view = new ViewModel(array(
    			'content' => DocumentTemplate::$attestationTemplate,
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function acknowledgementAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();

    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) return $this->redirect()->toRoute('home');
    	$commitment = Commitment::get($id);

    	$template = $context->getConfig('student/acknowledgement');
    	if ($commitment->account->contact_2) $invoicing_contact = $commitment->account->contact_2;
    	elseif ($commitment->account->contact_3) $invoicing_contact = $commitment->account->contact_3;
    	else $invoicing_contact = Vcard::instanciate();

    	$data = array(
    			'invoicing_n_title' => $invoicing_contact->n_title,
    			'invoicing_n_last' => $invoicing_contact->n_last,
    			'invoicing_n_first' => $invoicing_contact->n_first,
	    		'invoicing_adr_street' => $invoicing_contact->adr_street,
	    		'invoicing_adr_zip' => $invoicing_contact->adr_zip,
	    		'invoicing_adr_city' => $invoicing_contact->adr_city,
	    		'invoicing_adr_country' => $invoicing_contact->adr_country,
	    		'adr_street' => $invoicing_contact->adr_street,
    			'adr_zip' => $invoicing_contact->adr_zip,
    			'adr_city' => $invoicing_contact->adr_city,
    			'adr_country' => $invoicing_contact->adr_country,
    			'place' => $commitment->account->place_caption,
    			'date' => date('d/m/Y'),
    			'n_first' => $commitment->account->contact_1->n_first,
    			'n_last' => $commitment->account->contact_1->n_last,
    			'school_year' => $commitment->caption,
    	);

    	$place = Place::get($commitment->account->place_id);
    	if ($place && $place->logo_src) {
    		$logo_src = $place->logo_src;
    		$logo_width = $place->logo_width*2/3;
    		$logo_height = $place->logo_height*2/3;
    	}
    	else {
    		$logo_src = 'logos/'.$context->getInstance()->caption.'/'.$context->getConfig('headerParams')['logo'];
    		$logo_width = $context->getConfig('headerParams')['logo-width']*1/3;
    		$logo_height = $context->getConfig('headerParams')['logo-height']*1/3;
    	}
		$footer = ($place->legal_footer) ? $place->legal_footer : $context->getConfig('headerParams')['footer']['value'];
    	return $this->letter($template, $data, $logo_src, $logo_width, $logo_height, $footer);
    }

    public function attestationAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) return $this->redirect()->toRoute('home');
    	$commitment = Commitment::get($id);
    
    	$template = $context->getConfig('student/attestation');
    	if ($commitment->account->contact_2) $invoicing_contact = $commitment->account->contact_2;
    	elseif ($commitment->account->contact_3) $invoicing_contact = $commitment->account->contact_3;
    	else $invoicing_contact = Vcard::instanciate();
    	
    	$data = array(
    		'invoicing_n_title' => $invoicing_contact->n_title,
    		'invoicing_n_last' => $invoicing_contact->n_last,
    		'invoicing_n_first' => $invoicing_contact->n_first,
    		'invoicing_adr_street' => $invoicing_contact->adr_street,
    		'invoicing_adr_zip' => $invoicing_contact->adr_zip,
    		'invoicing_adr_city' => $invoicing_contact->adr_city,
    		'invoicing_adr_country' => $invoicing_contact->adr_country,
    		'place' => $commitment->account->place_caption,
    		'n_first' => $commitment->account->contact_1->n_first,
    			'n_last' => $commitment->account->contact_1->n_last,
    			'school_level' => $commitment->property_1,
    			'date' => date('d/m/Y'),
    	);

    	$place = Place::get($commitment->account->place_id);
    	if ($place && $place->logo_src) {
    		$logo_src = $place->logo_src;
    		$logo_width = $place->logo_width*2/3;
    		$logo_height = $place->logo_height*2/3;
    	}
    	else {
    		$logo_src = 'logos/'.$context->getInstance()->caption.'/'.$context->getConfig('headerParams')['logo'];
    		$logo_width = $context->getConfig('headerParams')['logo-width']*1/3;
    		$logo_height = $context->getConfig('headerParams')['logo-height']*1/3;
    	}
		$footer = ($place->legal_footer) ? $place->legal_footer : $context->getConfig('headerParams')['footer']['value'];
    	return $this->letter($template, $data, $logo_src, $logo_width, $logo_height, $footer);
    }
    
    public function commitmentAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();

    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) return $this->redirect()->toRoute('home');
    	$commitment = Commitment::get($id);
    	
    	$template = $context->getConfig('student/commitment');
    	if ($commitment->account->contact_2) $invoicing_contact = $commitment->account->contact_2;
    	elseif ($commitment->account->contact_3) $invoicing_contact = $commitment->account->contact_3;
    	else $invoicing_contact = Vcard::instanciate();
    	
    	$data = array(
	    		'invoicing_n_title' => $invoicing_contact->n_title,
	    		'invoicing_n_last' => $invoicing_contact->n_last,
	    		'invoicing_n_first' => $invoicing_contact->n_first,
	    		'invoicing_adr_street' => $invoicing_contact->adr_street,
	    		'invoicing_adr_zip' => $invoicing_contact->adr_zip,
	    		'invoicing_adr_city' => $invoicing_contact->adr_city,
	    		'invoicing_adr_country' => $invoicing_contact->adr_country,
	    		'date' => date('d/m/Y'),
    			'n_first' => $commitment->account->contact_1->n_first,
    			'n_last' => $commitment->account->contact_1->n_last,
    			'birth_date' => $context->decodeDate($commitment->account->contact_1->birth_date),
    			'sport' => $commitment->account->property_1,
    			'school_year' => $commitment->caption,
    			'school_level' => $commitment->property_1,
    			'place' => $commitment->account->place_caption,
    	);

    	$place = Place::get($commitment->account->place_id);
    	if ($place && $place->logo_src) {
    		$logo_src = $place->logo_src;
    		$logo_width = $place->logo_width*2/3;
    		$logo_height = $place->logo_height*2/3;
    	}
    	else {
    		$logo_src = 'logos/'.$context->getInstance()->caption.'/'.$context->getConfig('headerParams')['logo'];
    		$logo_width = $context->getConfig('headerParams')['logo-width']*1/3;
    		$logo_height = $context->getConfig('headerParams')['logo-height']*1/3;
    	}
		$footer = ($place->legal_footer) ? $place->legal_footer : $context->getConfig('headerParams')['footer']['value'];
    	return $this->letter($template, $data, $logo_src, $logo_width, $logo_height, $footer);
    }
    
    public function nomad($request, $from, $place_identifier, $limit)
    {
    	$context = Context::getCurrent();
    	$safe = $context->getConfig()['ppitUserSettings']['safe'];
    	$url = $context->getConfig()['ppitStudies']['nomadUrl'].$request.'?from='.$from.'&limit='.$limit;
    	$client = new Client(
    			$url,
    			array('adapter' => 'Zend\Http\Client\Adapter\Curl', 'maxredirects' => 0, 'timeout' => 30)
    	);
    	
		$client->setHeaders(array(
			'Authorization' => $safe[$context->getInstance()->caption]['nomad'],
			'Accept-Encoding' => 'gzip,deflate',
		));
    	$client->setMethod('GET');
    	$response = $client->send();
    	$body = $response->getBody();
    	$leads = json_decode($body, true);
    	foreach ($leads as $lead) {
    		echo $lead['id']."\n";
    		$data = [];
    		if ($place_identifier) $data['place_id'] = Place::get($place_identifier, 'identifier')->id;
    		$data['identifier'] = $lead['id'];
    		$data['status'] = (array_key_exists('type', $lead) && $lead['type'] == 'registration') ? 'suspect' : 'new';
    		$data['origine'] = 'nomad';
    		$data['callback_date'] = date('Y-m-d');
    		foreach ($context->getConfig('core_account/nomad/p-pit-studies')['properties'] as $propertyId => $property) {
    			if (array_key_exists($property, $lead)) $data[$propertyId] = $lead[$property];
    		}
    		$data['json_property_2'] = $lead['wishedDomain'];
    		unset($lead['wishedDomain']);
    		$data['json_property_3'] = $lead['engagements'];
    		unset($lead['engagements']);
    		if (array_key_exists('studyChoices', $lead)) {
    			foreach ($lead['studyChoices'] as $group) {
	    			foreach ($group as $key => $value) {
	    				if ($key == 'name') {
			    			if ($value == 'Alternance') $data['property_15'] = 'part_time';
			    			elseif ($value == 'Formation initiale') $data['property_15'] = 'initial';
			    			else $data['property_15'] = $value;
	    				}
	    			}
    			}
    		}
    		$data['json_property_1'] = $lead;
   			$data['contact_history'] = 'P-Pit -> Nomad connector';
   			$account = Account::instanciate('p-pit-studies');
			$account->loadAndAdd($data);
    	}
    	return $this->response;
    }
    
    public function nomadAction() {
    	$request = $this->params()->fromRoute('request');
    	$from = $this->params()->fromRoute('from');
    	$place_identifier = $this->params()->fromQuery('place_identifier', '');
    	$limit = $this->params()->fromQuery('limit', 10);
    	return $this->nomad($request, $from, $place_identifier, $limit);
    }
    
    public function batchNomadAction() {
    	$context = Context::getCurrent();
    	$instance_id = $this->params()->fromRoute('instance_id');
		$context->updateFromInstanceId($instance_id);
    	$request = $this->params()->fromRoute('request');
    	$place_identifier = $this->params()->fromRoute('place_identifier', '');
    	$limit = $this->params()->fromRoute('limit', 10);
    	echo date('Y-m-d')."\n";
    	return $this->nomad($request, date('Y-m-d', strtotime(date('Y-m-d').' - 1 days')), $place_identifier, $limit);
    }
}
