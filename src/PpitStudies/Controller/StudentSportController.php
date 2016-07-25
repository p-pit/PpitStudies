<?php
namespace PpitStudies\Controller;

use PpitCore\Form\CsrfForm;
use PpitCore\Model\Context;
use PpitCore\Model\Csrf;
use PpitMasterData\Model\Place;
use PpitStudies\Model\StudentSport;
use PpitStudies\Model\StudentSportImport;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class StudentSportController extends AbstractActionController
{
	public function indexAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();

		// Return the page
		$view = new ViewModel(array(
				'context' => $context,
				'config' => $context->getconfig(),
				'properties' => StudentSport::getPreferedProperties(),
		));
		return $view;
	}

	public function searchAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();

		$centers = Place::getList();
		
		// Return the link list
		$view = new ViewModel(array(
				'context' => $context,
				'config' => $context->getconfig(),
				'centers' => $centers,
				'properties' => StudentSport::getPreferedProperties(),
		));
		$view->setTerminal(true);
		return $view;
	}

	public function getFilters($params)
	{
		// Retrieve the query parameters
		$filters = array();
	
		$n_fn = ($params()->fromQuery('n_fn', null));
		if ($n_fn) $filters['n_fn'] = $n_fn;

		$center_name = ($params()->fromQuery('center_name', null));
		if ($center_name) $filters['center_name'] = $center_name;

		$min_school_year = ($params()->fromQuery('min_school_year', null));
		if ($min_school_year) $filters['min_school_year'] = $min_school_year;

		$max_school_year = ($params()->fromQuery('max_school_year', null));
		if ($max_school_year) $filters['max_school_year'] = $max_school_year;

		$emergency_phone_1 = ($params()->fromQuery('emergency_phone_1', null));
		if ($emergency_phone_1) $filters['emergency_phone_1'] = $emergency_phone_1;

		$emergency_phone_2 = ($params()->fromQuery('emergency_phone_2', null));
		if ($emergency_phone_2) $filters['emergency_phone_2'] = $emergency_phone_2;

		$emergency_email = ($params()->fromQuery('emergency_email', null));
		if ($emergency_email) $filters['emergency_email'] = $emergency_email;

		$sport = ($params()->fromQuery('sport', null));
		if ($sport) $filters['sport'] = $sport;

		$category = ($params()->fromQuery('category', null));
		if ($category) $filters['category'] = $category;

		$class = ($params()->fromQuery('class', null));
		if ($class) $filters['class'] = $class;

		$specialty = ($params()->fromQuery('specialty', null));
		if ($specialty) $filters['specialty'] = $specialty;

		$boarding_school = ($params()->fromQuery('boarding_school', null));
		if ($boarding_school) $filters['boarding_school'] = $boarding_school;
		
		$adr_city = ($params()->fromQuery('adr_city', null));
		if ($adr_city) $filters['adr_city'] = $adr_city;

		$adr_state = ($params()->fromQuery('adr_state', null));
		if ($adr_state) $filters['adr_state'] = $adr_state;

		$adr_country = ($params()->fromQuery('adr_country', null));
		if ($adr_country) $filters['adr_country'] = $adr_country;

		$sex = ($params()->fromQuery('sex', null));
		if ($sex) $filters['sex'] = $sex;
		
		$min_birth_date = ($params()->fromQuery('min_birth_date', null));
		if ($min_birth_date) $filters['min_birth_date'] = $min_birth_date;
	
		$max_birth_date = ($params()->fromQuery('max_birth_date', null));
		if ($max_birth_date) $filters['max_birth_date'] = $max_birth_date;

		return $filters;
	}

    public function getList()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();

		// Retrieve the vcard list
    	$params = $this->getFilters($this->params());

    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    	$major = $this->params()->fromQuery('major', 'n_fn');
    	$dir = $this->params()->fromQuery('dir', 'ASC');
    	$studentSports = StudentSport::getList($params, $major, $dir, $mode);

    	// Return the page
    	$view = new ViewModel(array(
    			'context' => $context,
    			'config' => $context->getconfig(),
    			'major' => $major,
    			'dir' => $dir,
    			'mode' => $mode,
    			'params' => $params,
    			'studentSports' => $studentSports,
				'properties' => StudentSport::getPreferedProperties(),
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
    	return $this->getList();
    }

    public function detailAction()
    {
    	// Retrieve the context
		$context = Context::getCurrent();

    	// Retrieve the student sport record
    	$id = (int) $this->params()->fromRoute('id', 0);
    	$view = new ViewModel(array(
    		'context' => $context,
			'config' => $context->getconfig(),
        	'id' => $id,
        ));
   		$view->setTerminal(true);
   		return $view;
    }

    public function updateAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();

    	// Check the presence of an id parameter (update case)
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if ($id) $studentSport = StudentSport::get($id);
    	else $studentSport = StudentSport::instanciate();
    
    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    
    	$error = null;
    	$message = null;
    	$request = $this->getRequest();
    	if ($request->isPost())
    	{
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    
    		if ($csrfForm->isValid())
    		{
    			$studentSport->student->loadDataFromRequest($request);
    
    			// Atomically save
    			$connection = StudentSport::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    
    				// Save
    				if ($studentSport->id) $studentSport->update($request->getPost('update_time'));
    				else $studentSport->add();
    
    				$connection->commit();
    
    				$message = 'OK';
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
    			'studentSport' => $studentSport,
    			'id' => $id,
    			'places' => Place::getList(),
    			'csrfForm' => $csrfForm,
    			'message' => $message,
    			'error' => $error
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function updateSportAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    	    
    	// Check the presence of an id parameter (update case)
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if ($id) {
    		$studentSport = StudentSport::get($id);
    		if (!$studentSport) $this->redirect()->toRoute('index'); // Not allowed
    	}
    	else $studentSport = StudentSport::instanciate();

    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    
    	$error = null;
    	$message = null;
    	$request = $this->getRequest();
    	if ($request->isPost())
    	{
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    
    		if ($csrfForm->isValid())
    		{
    			// Atomically save
    			$connection = StudentSport::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    				$studentSport->loadDataFromRequest($request);
    
    				// Save
    				if ($studentSport->id) $studentSport->update($request->getPost('update_time'));
    				else $studentSport->add();
    
    				$connection->commit();
    
    				$message = 'OK';
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
    			'studentSport' => $studentSport,
    			'id' => $id,
    			'places' => Place::getList(),
    			'csrfForm' => $csrfForm,
    			'message' => $message,
    			'error' => $error
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function homeAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) return $this->redirect()->toRoute('index');
    	
    	$studentSport = StudentSport::getTable()->get($id);
    	$evaluations = Evaluation::getList($id);
    	$sportResults = SportResult::getList($id);
    	$absences = Absences::getList($id);
    	$appointments = Appointments::getList($id);
       	$documents = Document::getList();

    	$view = new ViewModel(array(
    			'studentSports' => $studentSports,
    			'evaluations' => evaluations,
    			'sportResults' => $sportResults,
    			'absences' => $absences,
    			'appointments' => $appointments,
    			'documents' => $documents,
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function deleteAction()
    {
		// Control access
    	$id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->redirect()->toRoute('index');

    	// Retrieve the context
    	$context = Context::getCurrent();

    	// Retrieve the student sport record
    	$studentSport = StudentSport::get($id);
    	if (!$studentSport) $this->redirect()->toRoute('index'); // Not allowed

	    $csrfForm = new CsrfForm();
	    $csrfForm->addCsrfElement('csrf');
	    $message = null;
	    $error = null;
	    $request = $this->getRequest();
        if ($request->isPost()) {
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    		
    		if ($csrfForm->isValid()) { // CSRF check
    			 
			   	$studentSport->delete($request->getPost('update_time'));

				$message = 'OK';
    		}
        }

        $view = new ViewModel(array(
    		'context' => $context,
			'config' => $context->getconfig(),
        	'csrfForm' => $csrfForm,
        	'studentSport' => $studentSport,
    		'id' => $id,
    		'message' => $message,
        	'error' => $error
        ));
   		$view->setTerminal(true);
   		return $view;
    }

	public function importAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();
		
		// Atomically save
		$connection = StudentSport::getTable()->getAdapter()->getDriver()->getConnection();
		$connection->beginTransaction();
		try {
		
			StudentSportImport::import();

			$connection->commit();
		
			$message = 'OK';
		}
		catch (\Exception $e) {
			$connection->rollback();
			throw $e;
		}
		
		return $this->redirect()->toRoute('studentSport/search');
	}
}
