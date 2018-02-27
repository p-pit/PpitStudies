<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Event;
use PpitCore\Form\CsrfForm;
use PpitCore\Model\Account;
use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
use PpitCore\Model\Place;
use PpitCore\ViewHelper\EventPlanningViewHelper;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class EventController extends AbstractActionController
{
    public function indexAction()
    {
    	$context = Context::getCurrent();
		if (!$context->isAuthenticated()) $this->redirect()->toRoute('home');
    	$place = Place::get($context->getPlaceId());
		
		$menu = Context::getCurrent()->getConfig('menus/p-pit-studies')['entries'];
		$currentEntry = $this->params()->fromQuery('entry', 'event');

    	return new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getConfig(),
    			'place' => $place,
    			'applicationId' => 'p-pit-studies',
    			'applicationName' => 'P-Pit Studies',
    			'active' => 'application',
    			'menu' => $menu,
    			'currentEntry' => $currentEntry,
    	));
    }

    public function getFilters($params)
    {
		$context = Context::getCurrent();
    	
    	// Retrieve the query parameters
    	$filters = array();

    	foreach ($context->getConfig('commitmentEvent/search/p-pit-studies')['main'] as $propertyId => $rendering) {
    
    		$property = ($params()->fromQuery($propertyId, null));
    		if ($property) $filters[$propertyId] = $property;
    		$min_property = ($params()->fromQuery('min_'.$propertyId, null));
    		if ($min_property) $filters['min_'.$propertyId] = $min_property;
    		$max_property = ($params()->fromQuery('max_'.$propertyId, null));
    		if ($max_property) $filters['max_'.$propertyId] = $max_property;
    	}

    	foreach ($context->getConfig('commitmentEvent/search/p-pit-studies')['more'] as $propertyId => $rendering) {
    	
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
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function getList()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$params = $this->getFilters($this->params());
    
    	$major = ($this->params()->fromQuery('major', 'end_time'));
    	$dir = ($this->params()->fromQuery('dir', 'ASC'));
    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    
    	// Retrieve the list
    	$events = Event::getList('p-pit-studies', $params, $major, $dir, $mode);

    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'events' => $events,
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

    public function planningAction()
    {
    	$context = Context::getCurrent();
    	$viewBeginDate = $this->params()->fromQuery('begin', date('Y-m-d'));
    	$id = $this->params()->fromRoute('id');
    	$account = Account::get($id);
    	
    	// Retrieve class level events
    	$class = \PpitCore\Model\Event::getList(
	    			'calendar', 
	    			array(
	    					'place_id' => $account->place_id,
	    					'property_1' => $context->getConfig('student/property/school_year/default'),
	    					'property_2' => $account->property_7,
	    			));
    	
    	// Retrieve contact level events
    	$contact = \PpitCore\Model\Event::getList(
	    			'calendar', 
	    			array(
	    					'place_id' => $account->place_id,
	    					'property_1' => $context->getConfig('student/property/school_year/default'),
	    					'vcard_id' => $account->contact_1->id,
	    			));
    	$result = array(
	    	'planning' => EventPlanningViewHelper::format(array_merge($class, $contact), $viewBeginDate),
//    		'events' => $this->getList()->events,
    	);
    	return new JsonModel($result);
    }
    
    public function getAction()
    {
    	return new JsonModel($this->getList()->events);
    }
    
    public function exportAction()
    {
    	$view = $this->getList();

   		include 'public/PHPExcel_1/Classes/PHPExcel.php';
   		include 'public/PHPExcel_1/Classes/PHPExcel/Writer/Excel2007.php';

		$workbook = new \PHPExcel;
		(new SsmlEventViewHelper)->formatXls($workbook, $view);		
		$writer = new \PHPExcel_Writer_Excel2007($workbook);
		
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition:inline;filename=Fichier.xlsx ');
		$writer->save('php://output');
    }
    
    public function updateAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	// Retrieve the event
    	$id = (int) $this->params()->fromRoute('id', 0);
 		$event = Event::get($id);
 		$event->retrieveTarget();
    
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
    			$data['category'] = $request->getPost('category');
    			$data['title'] = $request->getPost('title');
    			$data['location'] = $request->getPost('location');
    			if ($request->getPost('begin_date')) $data['begin_time'] = $request->getPost('begin_date').' '.$request->getPost('begin_h').':'.$request->getPost('begin_m').':00';
    			else $data['begin_time'] = null;
    			if ($request->getPost('end_date')) $data['end_time'] = $request->getPost('end_date').' '.$request->getPost('end_h').':'.$request->getPost('end_m').':00';
    			else $data['end_time'] = null;
    			
    			$data['criteria'] = array();
    			foreach ($context->getConfig('commitmentEvent/update/p-pit-studies')['criteria'] as $criterionId => $unused) {
    				if ($request->getPost($criterionId)) $data['criteria'][$criterionId] = $request->getPost($criterionId);
    			}
    			$data['target'] = array();
    		   	foreach ($event->matchingAccounts as $account) {
    		   		if ($request->getPost('target_'.$account->id)) $data['target'][$account->id] = null;
    		   	}
    			
    		   	$data['update_time'] = $request->getPost('update_time');
				$data['comment'] = $request->getPost('comment');
				$rc = $event->loadData($data);
				if ($rc != 'OK') throw new \Exception('View error');

    			// Atomically save
    			$connection = Event::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
					$rc = $event->update($event->update_time);
    				if ($rc != 'OK') {
    					$connection->rollback();
    					$error = $rc;
    				}
    				else {
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
    			'id' => $id,
    			'event' => $event,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }
    
	public function deleteAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	// Retrieve the params
    	$id = (int) $this->params()->fromRoute('id');
    	$event = Event::get($id);
    	
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
    			$data['status'] = 'deleted';
    			$data['update_time'] = $request->getPost('update_time');
				$rc = $event->loadData($data);
				if ($rc != 'OK') throw new \Exception('View error');

    			// Atomically save
    			$connection = Event::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    				$rc = $event->update($event->update_time);
    				if ($rc != 'OK') {
    					$connection->rollback();
    					$error = $rc;
    				}
    				else {
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
    			'id' => $id,
    			'event' => $event,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message,
    	));
    	$view->setTerminal(true);
    	return $view;
	}
}
