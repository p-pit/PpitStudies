<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Notification;
use PpitCore\Model\Account;
use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
use PpitCore\Model\Place;
use PpitCore\Form\CsrfForm;
use PpitStudies\Model\Absence;
use PpitStudies\ViewHelper\SsmlAbsenceViewHelper;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AbsenceController extends AbstractActionController
{
 /*   public function indexAction()
    {
    	$context = Context::getCurrent();
		if (!$context->isAuthenticated()) $this->redirect()->toRoute('home');
    	$place = Place::get($context->getPlaceId());
		$instance_id = $context->getInstanceId();
    	$app = $this->params()->fromRoute('app', 'p-pit-studies');
		$community_id = (int) $context->getCommunityId();

		$menu = $context->getConfig('menus/'.$app)['entries'];
		$currentEntry = $this->params()->fromQuery('entry', 'account');

    	return new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getConfig(),
    			'place' => $place,
    			'applicationId' => $app,
    			'applicationName' => 'P-Pit Studies',
    			'active' => 'application',
    			'community_id' => $community_id,
    			'menu' => $menu,
    			'currentEntry' => $currentEntry,
    			'places' => Place::getList(array()),
    	));
    }*/
    
    public function indexV2Action()
    {
    	$context = Context::getCurrent();
    	$place = Place::get($context->getPlaceId());
    	$entry = $this->params()->fromRoute('entry', 'absence');
    	 
    	// Transient: Serialize a list of the entries from all menus
    	$menuEntries = [];
    	foreach ($context->getApplications() as $applicationId => $application) {
    		if ($context->getConfig('menus/'.$applicationId)) {
    			foreach ($context->getConfig('menus/' . $applicationId)['entries'] as $entryId => $entryDef) {
    				$menuEntries[$entryId] = ['menuId' => $applicationId, 'menu' => $application, 'definition' => $entryDef];
    			}
    		}
    	}
    	$tab = $this->params()->fromRoute('entryId', 'absence');

    	// Retrieve the application
    	$app = $menuEntries[$tab]['menuId'];
    	$applicationName = $context->localize($menuEntries[$tab]['menu']['labels']);
    	 
    	$this->layout('/layout/core-layout');
    	$this->layout()->setVariables(array(
    		'context' => $context,
    		'place' => $place,
    		'entry' => $entry,
//			'config' => $config,
    		'tab' => $tab,
    		'app' => $app,
    		'applicationName' => $applicationName,
    		'pageScripts' => 'ppit-studies/view-controller/absence-scripts',
    	));
    	 
    	return new ViewModel(array(
    		'context' => $context,
    	));
    }
    
    public function getFilters($params)
    {
		$context = Context::getCurrent();
    	
    	// Retrieve the query parameters
    	$filters = array();
    
    	$name = ($params()->fromQuery('name', null));
    	if ($name) $filters['name'] = $name;

    	foreach ($context->getConfig('absence/search')['main'] as $propertyId => $rendering) {
    
    		$property = ($params()->fromQuery($propertyId, null));
    		if ($property) $filters[$propertyId] = $property;
    		$min_property = ($params()->fromQuery('min_'.$propertyId, null));
    		if ($min_property) $filters['min_'.$propertyId] = $min_property;
    		$max_property = ($params()->fromQuery('max_'.$propertyId, null));
    		if ($max_property) $filters['max_'.$propertyId] = $max_property;
    	}

    	foreach ($context->getConfig('absence/search')['more'] as $propertyId => $rendering) {
    	
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
    
    	$description = Absence::getConfig();
    
    	// Return the link list
    	$view = new ViewModel(array(
    		'context' => $context,
    		'config' => $context->getconfig(),
    		'places' => Place::getList(array()),
    		'description' => $description,
    	));
    	$view->setTerminal(true);
    	return $view;
    }
/*    
    public function searchAction()
    {
    	return $this->searchV2Action();
    }*/
    
    public function getList()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$params = $this->getFilters($this->params());
		$limit = $this->params()->fromQuery('limit');
    	$major = ($this->params()->fromQuery('major', 'begin_date'));
    	$dir = ($this->params()->fromQuery('dir', 'DESC'));
    
    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    
    	// Retrieve the list
    	$absences = Absence::getList(null, $params, $major, $dir, $mode, $limit);

    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
	    		'places' => Place::getList(array()),
    			'absences' => $absences,
    			'mode' => $mode,
    			'params' => $params,
    			'major' => $major,
    			'dir' => $dir,
    	));
    	$view->setTerminal(true);
    	return $view;
    }
/*    
    public function listAction()
    {
    	return $this->getList();
    }*/

    public function listV2Action()
    {
    	return $this->getList();
    }
    
    public function exportAction()
    {
    	$view = $this->getList();

    	$description = Absence::getConfig();
    	
   		include 'public/PHPExcel_1/Classes/PHPExcel.php';
   		include 'public/PHPExcel_1/Classes/PHPExcel/Writer/Excel2007.php';

		$workbook = new \PHPExcel;
		(new SsmlAbsenceViewHelper)->formatXls($description, $workbook, $view);		
		$writer = new \PHPExcel_Writer_Excel2007($workbook);
		
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition:inline;filename=Fichier.xlsx ');
		$writer->save('php://output');
		return $this->response;
    }

    public function getAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    	$id = $this->params()->fromRoute('id');
    
    	$account_id = $this->params()->fromQuery('account_id');
    
    	$cursor = Absence::getList(null, ['account_id' => $account_id], 'begin_date', 'DESC', 'search', null);
    	$absences = [];
    	foreach ($cursor as $absence_id => $absence) {
    		$absences[] = $absence;
    	}
    	
    	echo json_encode($absences, JSON_PRETTY_PRINT);
    	return $this->getResponse();
    }

    public function updateV2Action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	// Retrieve the absence
    	$id = (int) $this->params()->fromRoute('id', 0);
 		if ($id) $absence = Absence::get($id);
 		else $absence = Absence::instanciate();
 		$action = $this->params()->fromRoute('act', null);
 		
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
				$data['update_time'] = $request->getPost('update_time');
    			$data['category'] = $request->getPost('category');
    			$data['school_period'] = $request->getPost('school_period');
    			$data['subject'] = $request->getPost('subject');
    			$data['motive'] = $request->getPost('motive');
    			$data['begin_date'] = $request->getPost('begin_date');
    			$data['end_date'] = $request->getPost('end_date');
    			$data['duration'] = $request->getPost('duration');
				$data['observations'] = $request->getPost('observations');
				$data['comment'] = $request->getPost('comment');

				$rc = $absence->loadData($data);
				if ($rc != 'OK') throw new \Exception('View error');

    			// Atomically save
    			$connection = Absence::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
	    			if (!$absence->id) $rc = $absence->add();
	    			elseif ($action == 'delete') $rc = $absence->delete($request->getPost('update_time'));
	    			else $rc = $absence->update($request->getPost('update_time'));
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
    			'action' => $action,
    			'absence' => $absence,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }
/*
    public function updateAction()
    {
    	return $this->updateV2Action();
    }*/
    
	public function repriseAction()
	{
	    $where = array();
	    $previous = null;
		$connection = Absence::getTable()->getAdapter()->getDriver()->getConnection();
		$connection->beginTransaction();
		try {
		    foreach (Absence::getList('schooling', $where, 'begin_date', 'asc', 'search', null) as $absence) {
				if ($previous && $previous->n_fn == $absence->n_fn && $previous->begin_date == $absence->begin_date && $previous->end_date == $absence->end_date && $previous->duration == $absence->duration && $previous->subject == $absence->subject && $previous->update_time == $absence->update_time) {
					echo $absence->id.' '.$absence->n_fn.' '.$absence->begin_date.' '.$absence->end_date.' '.$absence->duration.' '.$absence->subject.' '.$absence->motive.' '.$absence->observations.' '.$absence->update_time."\n";
					$absence->delete(null);
				}
				else $previous = $absence;
			}
			$connection->commit();
		}
		catch (\Exception $e) {
			$connection->rollback();
			throw $e;
		}
		return $this->response;
	}
}
