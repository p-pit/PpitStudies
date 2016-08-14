<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Account;
use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
use PpitCore\Form\CsrfForm;
use PpitStudies\Model\Absence;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AbsenceController extends AbstractActionController
{
    public function indexAction()
    {
    	$context = Context::getCurrent();
		if (!$context->isAuthenticated()) $this->redirect()->toRoute('home');
		$instance_id = $context->getInstanceId();
		$community_id = (int) $context->getCommunityId();

		$menu = Context::getCurrent()->getConfig('menus')['p-pit-studies'];
		$currentEntry = $this->params()->fromQuery('entry', 'account');

    	return new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getConfig(),
    			'applicationName' => 'p-pit-studies',
    			'active' => 'application',
    			'community_id' => $community_id,
    			'menu' => $menu,
    			'currentEntry' => $currentEntry,
    	));
    }

    public function getFilters($params)
    {
		$context = Context::getCurrent();
    	
    	// Retrieve the query parameters
    	$filters = array();
    
    	$name = ($params()->fromQuery('name', null));
    	if ($name) $filters['name'] = $customer_name;

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
    
    	$major = ($this->params()->fromQuery('major', 'name'));
    	$dir = ($this->params()->fromQuery('dir', 'ASC'));
    
    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    
    	// Retrieve the list
    	$absences = Absence::getList('boarding-school', $params, $major, $dir, $mode);

    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'absences' => $absences,
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
		(new SsmlAbsenceViewHelper)->formatXls($workbook, $view);		
		$writer = new \PHPExcel_Writer_Excel2007($workbook);
		
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition:inline;filename=Fichier.xlsx ');
		$writer->save('php://output');
    }
/*
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
    }*/
    
    public function updateAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	// Retrieve the absence
    	$id = (int) $this->params()->fromRoute('id', 0);
 		$absence = Absence::get($id);
    
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

    			// Load the input data
    			$data = array();
				$data['update_time'] = $request->getPost('update_time');
    			$data['date'] = $request->getPost('date');
				$data['observations'] = $request->getPost('observations');
				$data['comment'] = $request->getPost('comment');

				$rc = $absence->loadData($data);
				if ($rc != 'OK') throw new \Exception('View error');

    			// Atomically save
    			$connection = Absence::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
					$rc = $absence->update($absence->update_time);
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
    			'absence' => $absence,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function dashboardAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$account_id = (int) $this->params()->fromRoute('account_id');
    	$account = Account::get($account_id);
    
    	$absences = Absence::retrieveAll('boarding-school', $account_id);
    	 
    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'account' => $account,
    			'absences' => $absences,
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
    	$absence = Absence::get($id);
    	
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
				$rc = $absence->loadData($data);
				if ($rc != 'OK') throw new \Exception('View error');

    			// Atomically save
    			$connection = Absence::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    				$rc = $absence->update($absence->update_time);
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
    			'absence' => $absence,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
	}
}
