<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Account;
use PpitCommitment\ViewHelper\SsmlAccountViewHelper;
use PpitContact\Model\Vcard;
use PpitCore\Model\Csrf;
use PpitCore\Model\Context;
use PpitCore\Form\CsrfForm;
use PpitMasterData\Model\Place;
use PpitStudies\Model\StudentSportImport;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StudentController extends AbstractActionController
{
    public function indexAction()
    {
    	$context = Context::getCurrent();
    	$instance_id = $context->getInstanceId();
		$community_id = (int) $context->getCommunityId();
		$contact = Vcard::getNew($instance_id, $community_id);

		$menu = $context->getConfig('menus')[$context->getCurrentApplication()];
		$currentEntry = $this->params()->fromQuery('entry', 'account');

    	return new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getConfig(),
    			'productIdentifier' => 'p-pit-studies',
    			'community_id' => $community_id,
    			'menu' => $menu,
    			'contact' => $contact,
    			'currentEntry' => $currentEntry,
    	));
    }

    public function getFilters($params)
    {
		$context = Context::getCurrent();
    	
    	// Retrieve the query parameters
    	$filters = array();
    
    	$customer_name = ($params()->fromQuery('customer_name', null));
    	if ($customer_name) $filters['customer_name'] = $customer_name;

    	foreach ($context->getConfig('commitmentAccount/search')['main'] as $propertyId => $rendering) {
    
    		$property = ($params()->fromQuery($propertyId, null));
    		if ($property) $filters[$propertyId] = $property;
    		$min_property = ($params()->fromQuery('min_'.$propertyId, null));
    		if ($min_property) $filters['min_'.$propertyId] = $min_property;
    		$max_property = ($params()->fromQuery('max_'.$propertyId, null));
    		if ($max_property) $filters['max_'.$propertyId] = $max_property;
    	}

    	foreach ($context->getConfig('commitmentAccount/search')['more'] as $propertyId => $rendering) {
    	
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
    
    	// Retrieve the list
    	$accounts = Account::getList($params, $major, $dir, $mode);

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

    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'type' => $type,
    			'accounts' => $accounts,
    	));
    	$view->setTerminal(true);
    	return $view;
    }

	public function importAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();

		// First Ids to delete
		$firstCommunityId = $this->params()->fromQuery('firstCommunityId');
		$firstVcardId = $this->params()->fromQuery('firstVcardId');
		$firstUserId = $this->params()->fromQuery('firstUserId');
		$firstDocumentId = $this->params()->fromQuery('firstDocumentId');
		if (!$firstCommunityId || !$firstVcardId || !$firstUserId || !$firstDocumentId) throw new \Exception('Bad request');
		
		// Atomically save
		$connection = StudentSportImport::getTable()->getAdapter()->getDriver()->getConnection();
		$connection->beginTransaction();
		try {
		
//			StudentSportImport::importUser($firstCommunityId, $firstVcardId, $firstUserId, $firstDocumentId);
			StudentSportImport::import();

			$connection->commit();
		
			$message = 'OK';
		}
		catch (\Exception $e) {
			$connection->rollback();
			throw $e;
		}
		
//		return $this->redirect()->toRoute('home');
	}
}
