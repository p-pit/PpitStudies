<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Account;
use PpitCommitment\Model\Commitment;
use PpitCommitment\Model\Event;
use PpitCommitment\Model\Notification;
use PpitCommitment\ViewHelper\SsmlAccountViewHelper;
use PpitCore\Form\CsrfForm;
use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
use PpitCore\Model\Credit;
use PpitCore\Model\Instance;
use PpitCore\Model\Place;
use PpitCore\Model\Vcard;
use PpitMasterData\Model\Product;
use PpitStudies\Model\Absence;
use PpitStudies\Model\Note;
use PpitStudies\Model\NoteLink;
use PpitStudies\Model\Progress;
use PpitStudies\Model\StudentSportImport;
use PpitStudies\ViewHelper\DocumentTemplate;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StudentController extends AbstractActionController
{
    public function indexAction()
    {
    	$context = Context::getCurrent();
    	$config = $context->getConfig();

    	if ($context->hasRole('student')) return $this->redirect()->toRoute('student/studentHome');

    	$menu = Context::getCurrent()->getConfig('menus')['p-pit-studies'];
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
    	$account_id = Account::get($context->getCommunityId(), 'customer_community_id')->id;

     	return new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getConfig(),
    			'applicationId' => 'p-pit-studies',
    			'applicationName' => 'P-Pit Studies',
     			'active' => 'application',
     			'account_id' => $account_id,
    	));
    }

    public function registrationIndexAction()
    {
    	$context = Context::getCurrent();
		if (!$context->isAuthenticated()) $this->redirect()->toRoute('home');

		$type = $this->params()->fromRoute('type', 'p-pit-studies');
		
		$menu = $context->getConfig('menus')[$type];
		$currentEntry = $this->params()->fromQuery('entry');

    	return new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getConfig(),
    			'active' => 'application',
    			'applicationId' => $type,
    			'applicationName' => $context->getConfig('ppitApplications')[$type]['labels'][$context->getLocale()],
    			'menu' => $menu,
    			'currentEntry' => $currentEntry,
    			'type' => $type,
    	));
    }

    public function getFilters($params)
    {
		$context = Context::getCurrent();
    	
    	// Retrieve the query parameters
    	$filters = array();
    
    	$customer_name = ($params()->fromQuery('customer_name', null));
    	if ($customer_name) $filters['customer_name'] = $customer_name;

    	foreach ($context->getConfig('commitmentAccount/search/p-pit-studies')['main'] as $propertyId => $rendering) {
    
    		$property = ($params()->fromQuery($propertyId, null));
    		if ($property) $filters[$propertyId] = $property;
    		$min_property = ($params()->fromQuery('min_'.$propertyId, null));
    		if ($min_property) $filters['min_'.$propertyId] = $min_property;
    		$max_property = ($params()->fromQuery('max_'.$propertyId, null));
    		if ($max_property) $filters['max_'.$propertyId] = $max_property;
    	}

    	foreach ($context->getConfig('commitmentAccount/search/p-pit-studies')['more'] as $propertyId => $rendering) {
    	
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
    	$major = ($this->params()->fromQuery('major', 'customer_name'));
    	$dir = ($this->params()->fromQuery('dir', 'ASC'));
    
    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    	$params['status'] = 'active';

    	// Retrieve the list
    	$accounts = Account::getList('p-pit-studies', $params, $major, $dir, $mode);

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

   		include 'public/PHPExcel_1/Classes/PHPExcel.php';
   		include 'public/PHPExcel_1/Classes/PHPExcel/Writer/Excel2007.php';

		$workbook = new \PHPExcel;
		(new SsmlAccountViewHelper)->formatXls($workbook, $view);		
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

       	$criteria = array();
       	foreach ($context->getConfig('commitmentAccount/search/p-pit-studies')['main'] as $propertyId => $rendering) {
       		if ($rendering == 'range') {
       			if ($request->getPost('min_'.$propertyId)) $criteria['min_'.$propertyId] = $request->getPost('min_'.$propertyId);
       			if ($request->getPost('max_'.$propertyId)) $criteria['max_'.$propertyId] = $request->getPost('max_'.$propertyId);
       		}
       		else {
       			if ($request->getPost($propertyId)) $criteria[$propertyId] = $request->getPost($propertyId);
       		}
       	}
       	foreach ($context->getConfig('commitmentAccount/search/p-pit-studies')['more'] as $propertyId => $rendering) {
       		if ($rendering == 'range') {
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

    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	$message = null;

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
    			$data['category'] = $request->getPost('category');
    			$data['subject'] = $request->getPost('subject');
    			$data['motive'] = $request->getPost('motive');
    			$data['date'] = $request->getPost('date');
    			$data['duration'] = $request->getPost('duration');
				$data['observations'] = $request->getPost('observations');
    			$data['comment'] = $request->getPost('comment');
    
    			// Atomically save
    			$connection = Absence::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    				foreach ($accounts as $account) {
    					$data['account_id'] = $account->id;
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
    
    	// Retrieve the type
    	$type = $this->params()->fromRoute('type', null);
    
    	$note = Note::instanciate('homework');

    	$documentList = array();
    	if (array_key_exists('dropbox', $context->getConfig('ppitDocument'))) {
    		require_once "vendor/dropbox/dropbox-sdk/lib/Dropbox/autoload.php";
    		$dropbox = $context->getConfig('ppitDocument')['dropbox'];
    		$dropboxClient = new \Dropbox\Client($dropbox['credential'], $dropbox['clientIdentifier']);
    		try {
    			$properties = $dropboxClient->getMetadataWithChildren($dropbox['folders']['schooling']);
    			foreach ($properties['contents'] as $content) $documentList[] = substr($content['path'], strrpos($content['path'], '/')+1);
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
    			$data['category'] = 'homework';
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
    
    	// Retrieve the type
    	$type = $this->params()->fromRoute('type', null);
    
    	$note = Note::instanciate($type);
    
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
    			$data['class'] = $request->getPost('class');
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
    			$noteLinks = array();
    			if ($type == 'report') {
    				$computedAverages = Note::computePeriodAverages(/*$data['school_year'], */$data['class'], /*$data['school_period'], */$data['subject']);
    			}
    			$nbAccount = $request->getPost('nb-account');
    			$noteSum = 0; $lowerNote = 999; $higherNote = 0;
    			for ($i = 0; $i < $nbAccount; $i++) {
    				$account = $accounts[$request->getPost('account_'.$i)];
    				$noteLink = NoteLink::instanciate($account->id, null);
    				$value = $request->getPost('value_'.$account->id);
    				if ($value == '') {
    					if (array_key_exists($account->id, $computedAverages)) {
    						$value = $computedAverages[$account->id]['note'];
    						$noteLink->audit = $computedAverages[$account->id]['notes'];
    					}
    				}
    				$noteLink->value = $value;
    				$noteLink->assessment = $request->getPost('assessment_'.$account->id);
    				$noteSum += $value;
    				if ($value < $lowerNote) $lowerNote = $value;
    				if ($value > $higherNote) $higherNote = $value;
    				if ($noteLink->value != '') $noteLinks[] = $noteLink;
    			}

    			if ($nbAccount > 0) {
    				$data['average_note'] = round($noteSum / $nbAccount, 2);
	    			$data['lower_note'] = $lowerNote;
	    			$data['higher_note'] = $higherNote;
	    			$rc = $note->loadData($data);
	    			if ($rc == 'Integrity') throw new \Exception('View error');
	    			if ($rc == 'Duplicate') $error = $rc;
	    			else {
		
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
		    				else foreach ($noteLinks as $noteLink) {
		    					$noteLink->note_id = $note->id;
		    					$rc = $noteLink->add();
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
    
	public function importAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();

		// First Ids to delete
/*		$firstCommunityId = $this->params()->fromQuery('firstCommunityId');
		$firstVcardId = $this->params()->fromQuery('firstVcardId');
		$firstUserId = $this->params()->fromQuery('firstUserId');
		$firstDocumentId = $this->params()->fromQuery('firstDocumentId');
		if (!$firstCommunityId || !$firstVcardId || !$firstUserId || !$firstDocumentId) throw new \Exception('Bad request');*/
		
//			StudentSportImport::importUser($firstCommunityId, $firstVcardId, $firstUserId, $firstDocumentId);
//			StudentSportImport::import();
//			StudentSportImport::importProduct();
//			StudentSportImport::importOption();
//			StudentSportImport::importBill();
//			StudentSportImport::importBillRow();
//			StudentSportImport::importBillOption();
//			StudentSportImport::importBillTerm();
			StudentSportImport::importSejour();
		
		return $this->getResponse();
//		return $this->redirect()->toRoute('home');
	}

	public function dashboardAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
	
		$account_id = (int) $this->params()->fromRoute('account_id');
		$account = Account::get($account_id);
		$category = $this->params()->fromRoute('category');
		$absLates = Absence::getList($category, array('account_id' => $account_id, 'min_date' => $context->getConfig('currentPeriodStart')), 'date', 'DESC', 'search');
		$absences = array();
		$latenesss = array();
		$cumulativeLateness = 0;
		$absenceCount = 0;
		foreach ($absLates as $absLate) {
			if ($absLate->category == 'absence') {
				$absences[] = $absLate;
				$absenceCount++;
			}
			elseif ($absLate->category =='lateness') {
				$latenesss[] = $absLate;
				$cumulativeLateness += $absLate->duration;
			}
		}

		$periods = array();
		$notes = NoteLink::GetList(null, array('account_id' => $account_id, 'min_date' => $context->getConfig('currentPeriodStart')), 'date', 'DESC', 'search');
		foreach($notes as $note) {
			if ($note->type == 'report') {
				$key = $note->school_year.'.'.$note->school_period;
				if (!array_key_exists($key, $periods)) $periods[$key] = array();
				$periods[$key][] = $note;
			}
		}
		krsort($periods);

		$events = Event::retrieveComing('p-pit-studies', $category, $account_id);
		$notifications = Notification::retrieveCurrents('p-pit-studies', $category, $account_id);
		
		if ($category == 'sport') $progresses = Progress::retrieveAll($account->property_1, $account_id);
		else $progresses = array();
		
		// Return the link list
		$view = new ViewModel(array(
				'context' => $context,
				'config' => $context->getconfig(),
				'category' => $category,
				'type' => $account->property_1,
				'account' => $account,
				'notes' => $notes,
				'absences' => $absences,
				'absenceCount' => $absenceCount,
				'latenesss' => $latenesss,
				'cumulativeLateness' => $cumulativeLateness,
				'events' => $events,
				'periods' => $periods,
				'notifications' => $notifications,
				'progresses' => $progresses,
		));
		$view->setTerminal(true);
		return $view;
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

		$noImage = 2; //on incrémentera pour chaque image différente
		$extImage = explode(".",$context->getConfig('headerParams')['footer-img']);
		$extImage = $extImage[count($extImage)-1]; //on récupère l'extension de l'image
//		$footer = $context->getConfig('headerParams')['footer']['value'];
/*		$footer = "<w:pict>\n";
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

    	$data = array(
    		'date' => date('d/m/Y'),
    		'n_last' => $commitment->account->contact_1->n_last,
    		'n_first' => $commitment->account->contact_1->n_first,
    		'adr_street' => $commitment->account->contact_1->adr_street,
    		'adr_zip' => $commitment->account->contact_1->adr_zip,
    		'adr_city' => $commitment->account->contact_1->adr_city,
    		'adr_country' => $commitment->account->contact_1->adr_country,
    		'birth_date' => $commitment->account->contact_1->birth_date,
    		'caption' => $commitment->caption,
    		'sport' => $commitment->account->property_1,
    		'class' => $commitment->property_1.' '.$commitment->property_2,
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
			$logo_width = $context->getConfig('headerParams')['logo-width']*2/3;
			$logo_height = $context->getConfig('headerParams')['logo-height']*2/3;
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
    		$logo_width = $context->getConfig('headerParams')['logo-width']*2/3;
    		$logo_height = $context->getConfig('headerParams')['logo-height']*2/3;
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
    
    	$data = array(
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
    		$logo_width = $context->getConfig('headerParams')['logo-width']*2/3;
    		$logo_height = $context->getConfig('headerParams')['logo-height']*2/3;
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
    	
    	$data = array(
    			'date' => date('d/m/Y'),
    			'n_first' => $commitment->account->contact_1->n_first,
    			'n_last' => $commitment->account->contact_1->n_last,
    			'birth_date' => $context->decodeDate($commitment->account->contact_1->birth_date),
    			'sport' => $commitment->account->property_1,
    			'school_year' => $commitment->caption,
    			'school_level' => $commitment->property_1,
    	);

    	$place = Place::get($commitment->account->place_id);
    	if ($place && $place->logo_src) {
    		$logo_src = $place->logo_src;
    		$logo_width = $place->logo_width*2/3;
    		$logo_height = $place->logo_height*2/3;
    	}
    	else {
    		$logo_src = 'logos/'.$context->getInstance()->caption.'/'.$context->getConfig('headerParams')['logo'];
    		$logo_width = $context->getConfig('headerParams')['logo-width']*2/3;
    		$logo_height = $context->getConfig('headerParams')['logo-height']*2/3;
    	}
		$footer = ($place->legal_footer) ? $place->legal_footer : $context->getConfig('headerParams')['footer']['value'];
    	return $this->letter($template, $data, $logo_src, $logo_width, $logo_height, $footer);
    }
}
