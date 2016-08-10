<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Account;
use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
use PpitCore\Form\CsrfForm;
use PpitStudies\Model\Progress;
use PpitStudies\ViewHelper\SsmlProgressViewHelper;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ProgressController extends AbstractActionController
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
//    			'productIdentifier' => 'p-pit-studies',
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
    	if ($name) $filters['name'] = $name;

    	foreach ($context->getConfig('progress/search')['main'] as $propertyId => $rendering) {
    
    		$property = ($params()->fromQuery($propertyId, null));
    		if ($property) $filters[$propertyId] = $property;
    		$min_property = ($params()->fromQuery('min_'.$propertyId, null));
    		if ($min_property) $filters['min_'.$propertyId] = $min_property;
    		$max_property = ($params()->fromQuery('max_'.$propertyId, null));
    		if ($max_property) $filters['max_'.$propertyId] = $max_property;
    	}

    	foreach ($context->getConfig('progress/search')['more'] as $propertyId => $rendering) {
    	
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
    	$progresses = Progress::getList('sport', $params, $major, $dir, $mode);

    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'progresses' => $progresses,
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
		(new SsmlProgressViewHelper)->formatXls($workbook, $view);		
		$writer = new \PHPExcel_Writer_Excel2007($workbook);
		
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition:inline;filename=Fichier.xlsx ');
		$writer->save('php://output');
    	$view->setTerminal(true);
    	return $view;
    }

    public function addAction() {
    	
    	// Retrieve the context
    	$context = Context::getCurrent();

    	// Retrieve the type
    	$type = $this->params()->fromRoute('type', null);

    	$progress = Progress::instanciate($type);
    	$accountProperty = $context->getConfig('progress')['types'][$type]['accountProperty']; // ex. 'property_1' for 'sport' at FM Sports

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
    		$accounts[] = $account;
    	}

    	if ($request->getPost('period')) {
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
			$csrfForm->setData($request->getPost());
			 
			if ($csrfForm->isValid()) { // CSRF check

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
						$progress->subject = $account->properties[$accountProperty]; // ex. value of 'property_1 for 'sport' at FM Sports
						$rc = $progress->loadData($data);
						if ($rc != 'OK') throw new \Exception('View error');

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
    			'accounts' => $accounts,
    			'progress' => $progress,
    			'csrfForm' => $csrfForm,
    			'error' => $error,
    			'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function detailAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	// Retrieve the params
    	$type = $this->params()->fromRoute('type');
    	$id = (int) $this->params()->fromRoute('id');
    	$action = $this->params()->fromRoute('action', 'detail');
    	$progress = Progress::get($id);
    	
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
    			$data['date'] = $request->getPost('date');
    			 
    			$subject = $context->getConfig('progress/detail')['types']['sport']['subjects'][$progress->subject];
				if (array_key_exists('qualitative_criteria', $subject)) {
					foreach ($subject['qualitative_criteria'] as $criterionId => $criterion) {
						if ($criterion['type'] != 'subtitle') $data['qualitative_'.$criterionId] = $request->getPost('qualitative_'.$criterionId);
					}
				}
				if (array_key_exists('quantitative_criteria', $subject)) {
					foreach ($subject['quantitative_criteria'] as $criterionId => $criterion) {
						if ($criterion['type'] != 'subtitle') $data['quantitative_'.$criterionId] = $request->getPost('quantitative_'.$criterionId);
					}
				}

				$data['observations'] = $request->getPost('observations');
				$data['status'] = $request->getPost('status');
				$data['comment'] = $request->getPost('comment');
				$data['update_time'] = $request->getPost('update_time');
				$rc = $progress->loadData($data);
				if ($rc != 'OK') throw new \Exception('View error');

    			// Atomically save
    			$connection = Account::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    				$rc = $progress->add();
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
    			'progress' => $progress,
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

//    	$progresses = Progress::getList('sport', array('account_id' => $account_id), 'school_year', 'ASC', null);
  		$progresses = Progress::retrieveAll('sport', $account_id);
    	
    	// Return the link list
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'account' => $account,
    			'progresses' => $progresses,
    	));
    	$view->setTerminal(true);
    	return $view;
    }
    
	public function deleteAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) return $this->redirect()->toRoute('index');

    	// Retrieve the context
    	$context = Context::getCurrent();

    	// Retrieve the organizational unit
		$account = Account::get($id);
		$csrfForm = new CsrfForm();
		$csrfForm->addCsrfElement('csrf');
		$message = null;
		$error = null;
    	// Retrieve the user validation from the post
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    		
    		if ($csrfForm->isValid()) {

    			// Atomicity
    			$connection = Account::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
		    		// Delete the row
					$return = $account->delete($account->update_time);
					if ($return != 'OK') {
						$connection->rollback();
						$error = $return;
					}
					else {
						$connection->commit();
						$message = $return;
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
    		'account' => $account,
    		'id' => $id,
    		'csrfForm' => $csrfForm,
    		'message' => $message,
    		'error' => $error,
    	));
   		if ($context->isSpaMode()) $view->setTerminal(true);
   		return $view;
    }
}
