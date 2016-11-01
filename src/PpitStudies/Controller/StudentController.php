<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Account;
use PpitCommitment\Model\Event;
use PpitCommitment\Model\Notification;
use PpitCommitment\ViewHelper\SsmlAccountViewHelper;
use PpitContact\Model\Vcard;
use PpitCore\Form\CsrfForm;
use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
use PpitCore\Model\Credit;
use PpitCore\Model\Instance;
use PpitMasterData\Model\Place;
use PpitMasterData\Model\Product;
use PpitStudies\Model\Absence;
use PpitStudies\Model\Note;
use PpitStudies\Model\Progress;
use PpitStudies\Model\StudentSportImport;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StudentController extends AbstractActionController
{
    public function indexAction()
    {
    	$context = Context::getCurrent();

    	if ($context->hasRole('student')) return $this->redirect()->toRoute('student/studentHome');

    	$menu = Context::getCurrent()->getConfig('menus')['p-pit-studies'];
		$currentEntry = $this->params()->fromQuery('entry');

		if ($context->getInstanceId() == 0) $outOfStockCredits = false;
		elseif ($context->getConfig('credit')['unlimitedCredits']) $outOfStockCredits = false;
		else {
			$credit = Credit::get('p-pit-communities', 'type');
			if (!$credit) $outOfStockCredits = true;
			else $outOfStockCredits = ($credit->quantity < 0);
		}

    	return new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getConfig(),
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
    			'applicationName' => 'P-Pit Studies',
    			'active' => 'application',
     			'account_id' => $account_id,
    	));
    }

    public function registrationIndexAction()
    {
    	$context = Context::getCurrent();
		if (!$context->isAuthenticated()) $this->redirect()->toRoute('home');

		$applicationName = 'P-Pit Studies';
		$menu = Context::getCurrent()->getConfig('menus')['p-pit-studies'];
		$currentEntry = $this->params()->fromQuery('entry');

    	return new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getConfig(),
    			'active' => 'application',
    			'applicationName' => $applicationName,
    			'menu' => $menu,
    			'currentEntry' => $currentEntry,
    			'type' => 'p-pit-studies',
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
				'places' => Place::getList(),
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
    	if (!array_key_exists('min_closing_date', $params)) $params['min_closing_date'] = date('Y-m-d');

    	// Retrieve the list
    	$accounts = Account::getList('p-pit-studies', $params, $major, $dir, $mode);

    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'accounts' => $accounts,
				'places' => Place::getList(),
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
    			$data['subject'] = $request->getPost('subject');
    			$data['date'] = $request->getPost('date');
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
    
    	$note = Note::instanciate('note' /*$type*/);
    
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
    	}

    	if ($request->getPost('date')) {
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    
    		if ($csrfForm->isValid()) { // CSRF check

    			// Load the input data
    			$data = array();
    			$data['school_year'] = $request->getPost('school_year');
    			$data['level'] = $request->getPost('level');
    			$data['school_period'] = $request->getPost('school_period');
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

    			// Atomically save
    			$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
//    				foreach ($accounts as $account) {
//    					$data['account_id'] = $account->id;
//    					$data['value'] = $request->getPost('value_'.$account->id);
    			    			
	    			$nbAccount = $request->getPost('nb-account');
	    			$data['results'] = array();
	    			for ($i = 0; $i < $nbAccount; $i++) {
	    				$account = $accounts[$request->getPost('account_'.$i)];
	    				$value = $request->getPost('value_'.$account->id);
	    				$data['results'][$request->getPost('account_'.$i)] = $value;
	    			}
    				$rc = $note->loadData($data);
    				if ($rc != 'OK') throw new \Exception('View error');
    				
    				$rc = $note->add();
    				if ($rc != 'OK') {
    					$connection->rollback();
    					$error = $rc;
    				}
    				// Save the note at the student level
    				else for ($i = 0; $i < $nbAccount; $i++) {
    					$account = $accounts[$request->getPost('account_'.$i)];
    					$value = $request->getPost('value_'.$account->id);
						$account->json_property_1[$note->id] = array(
								'date' => $note->date,
								'subject' => $note->subject,
								'reference_value' => $note->reference_value,
								'weight' => $note->weight,
								'note' => $value,
								'average_note' => $note->average_note,
								'higher_note' => $note->higher_note,
								'lower_note' => $note->lower_note,
								'observations' => $note->observations,
						);
						$account->update($account->update_time);
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
				$properties = $dropboxClient->getMetadataWithChildren('/P-PIT Finance');
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
			StudentSportImport::importBillTerm();
				
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
		$absences = Absence::retrieveAll($category, $account_id);
		$events = Event::retrieveComing('p-pit-studies', $category, $account_id);
		$notifications = Notification::retrieveCurrents('p-pit-studies', $category, $account_id);
		$notes = array();
		foreach ($account->json_property_1 as $noteId => $note) {
			$evaluation = Note::get($noteId);
			if ($evaluation->status != 'deleted') $notes[$noteId] = $note;
		}
		if ($category == 'sport') $progresses = Progress::retrieveAll($account->property_1, $account_id);
		else $progresses = array();
		
		// Return the link list
		$view = new ViewModel(array(
				'context' => $context,
				'config' => $context->getconfig(),
				'category' => $category,
				'type' => $account->property_1,
				'account' => $account,
				'absences' => $absences,
				'events' => $events,
				'notifications' => $notifications,
				'notes' => $notes,
				'progresses' => $progresses,
		));
		$view->setTerminal(true);
		return $view;
	}
}
