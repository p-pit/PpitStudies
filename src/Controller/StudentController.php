<?php

namespace PpitStudies\Controller;

use PpitCommitment\Model\Commitment;
use PpitCommitment\Model\Notification;
use PpitCommitment\ViewHelper\CommitmentMessageViewHelper;
use PpitCommitment\ViewHelper\SsmlAccountViewHelper;
use PpitCore\Form\CsrfForm;
use PpitCore\Model\Account;
use PpitCore\Model\Community;
use PpitCore\Model\Context;
use PpitCore\Model\Credit;
use PpitCore\Model\Csrf;
use PpitCore\Model\Document;
use PpitCore\Model\Event;
use PpitCore\Model\Instance;
use PpitCore\Model\Place;
use PpitCore\Model\Vcard;
use PpitCore\ViewHelper\EventPlanningViewHelper;
use PpitCommitment\ViewHelper\PpitPDF;
use PpitStudies\Model\Absence;
use PpitStudies\Model\Note;
use PpitStudies\Model\NoteLink;
use PpitStudies\Model\Progress;
use PpitStudies\Model\StudentSportImport;
use PpitStudies\ViewHelper\AverageComputer;
use PpitStudies\ViewHelper\DocumentTemplate;
use PpitStudies\ViewHelper\PdfReportTableViewHelper;
use PpitStudies\ViewHelper\PdfReportViewHelper;
use Zend\Db\Sql\Where;
use Zend\Http\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StudentController extends AbstractActionController
{
    public function indexAction()
    {
    	$context = Context::getCurrent();
    	$config = $context->getConfig();
    	$place = Place::get($context->getPlaceId());
		
    	// Transient: Serialize a list of the entries from all menus
    	$menuEntries = [];
    	foreach ($context->getApplications() as $applicationId => $application) {
    		if ($context->getConfig('menus/'.$applicationId)) {
    			foreach ($context->getConfig('menus/' . $applicationId)['entries'] as $entryId => $entryDef) {
    				$menuEntries[$entryId] = ['menuId' => $applicationId, 'menu' => $application, 'definition' => $entryDef];
    			}
    		}
    	}
    	$tab = $this->params()->fromRoute('entryId', 'student');

    	// Retrieve the application
    	$app = $menuEntries[$tab]['menuId'];
    	$applicationName = $context->localize($menuEntries[$tab]['menu']['labels']);

    	// Feed the layout
    	$this->layout('/layout/core-layout');
    	$this->layout()->setVariables(array(
    		'context' => Context::getCurrent(),
    		'title' => ['default' => 'Elèves/classes'],
    		'place' => $place,
    		'tab' => $tab,
    		'app' => $app,
    		'active' => 'application',
    		'applicationName' => $applicationName,
			'pageScripts' => '/ppit-studies/student/scripts',
    	));

    	$view = new ViewModel(array(
    		'context' => $context,
			'config' => $context->getConfig(),
    		'tab' => $tab,
    		'app' => $app,
    		'place' => $place,
    	));
		return $view;
    }
    
    public function indexV2Action()
    {
    	$context = Context::getCurrent();
    	$config = $context->getConfig();
    	$place = Place::get($context->getPlaceId());

    	// Transient: Serialize a list of the entries from all menus
    	$menuEntries = [];
    	foreach ($context->getApplications() as $applicationId => $application) {
    		if ($context->getConfig('menus/'.$applicationId)) {
    			foreach ($context->getConfig('menus/' . $applicationId)['entries'] as $entryId => $entryDef) {
    				$menuEntries[$entryId] = ['menuId' => $applicationId, 'menu' => $application, 'definition' => $entryDef];
    			}
    		}
    	}
    	$tab = $this->params()->fromRoute('entryId', 'student');

    	// Retrieve the application
    	$app = $menuEntries[$tab]['menuId'];
    	$applicationName = $context->localize($menuEntries[$tab]['menu']['labels']);

    	// Feed the layout
    	$this->layout('/layout/core-layout');
    	$this->layout()->setVariables(array(
    		'context' => Context::getCurrent(),
    		'title' => ['default' => 'Elèves/classes'],
    		'place' => $place,
    		'tab' => $tab,
    		'app' => $app,
    		'active' => 'application',
    		'applicationName' => $applicationName,
			'pageScripts' => '/partials/student-scripts-v2',
    	));

    	$view = new ViewModel(array(
    		'context' => $context,
			'config' => $context->getConfig(),
    		'tab' => $tab,
    		'app' => $app,
    		'place' => $place,
    	));
		return $view;
    }

    public function studentHomeV2Action()
    {
    	// Retrieve context and parameters
    	$context = Context::getCurrent();
    	$account_id = (int) $this->params()->fromRoute('account_id');
    	 
    	$profile = null;
    	if ($account_id) $profile = Account::get($account_id);
    	else {
    		$candidates = Account::getList('p-pit-studies', ['contact_1_id' => $context->getContactId()]);
    		foreach ($candidates as $candidate) if (!in_array($candidate->status, ['gone', 'canceled'])) {
    			$profile = $candidate;
    			break;
    		}
    	}
    	if (!$profile) return $this->redirect()->toRoute('home');
    	$place = Place::get($profile->place_id);
    	$template = $context->getConfig('student/home/tabs');
    	$logo = ($place->logo_src) ? $place->logo_src : '/logos/'.$context->getInstance()->caption.'/'.$context->getConfig('headerParams')['logo'];
    	$logo_height = ($place->logo_src) ? $place->logo_height : $context->getConfig('headerParams')['logo-height'];
    	$configProperties = Account::getConfig('p-pit-studies');
    
    	// Authentication
    	$panel = $this->params()->fromQuery('panel');
    	$email = $this->params()->fromQuery('email');
    	$error = $this->params()->fromQuery('error');
    	$message = $this->params()->fromQuery('message');
    	$redirect = $this->params()->fromQuery('redirect', 'home');
    	if ($email && !$context->isAuthenticated()) {
    		$vcard = Vcard::get($email, 'email');
    		$profile->email = $email;
    		if ($vcard) {
    			$userContact = UserContact::get($vcard->id, 'vcard_id');
    			if ($userContact) $panel = 'modalLoginForm';
    			$profile->n_first = $vcard->n_first;
    			$profile->n_last = $vcard->n_last;
    		}
    		else {
    			$profile->n_first = $this->params()->fromQuery('n_first');
    			$profile->n_last = $this->params()->fromQuery('n_last');
    		}
    		if ($panel != 'modalLoginForm') {
    			$panel = 'modalRegisterForm';
    		}
    	}
    
    	// Retrieve the global average if exists
    	$current_school_year = $context->getConfig('student/property/school_year/default');
    	$school_periods = $place->getConfig('school_periods');
    	$current_school_period = $context->getCurrentPeriod($school_periods);
    	$cursor = NoteLink::getList('report', ['category' => 'evaluation', 'subject' => 'global', 'school_year' => $current_school_year, 'school_period' => $current_school_period, 'account_id' => $profile->id], 'id', 'ASC', $mode = 'search');
    	foreach ($cursor as $report) $averageNote = $report; // Should be unique but to keep only the last one
    	$global_average = (isset($averageNote) && $averageNote) ? $averageNote->value : null;
    
    	$view = new ViewModel(array(
    		'context' => $context,
    		'place_identifier' => $place->identifier,
    		'place' => $place,
    		'profile' => $profile,
    		'global_average' => $global_average,
    		'requestUri' => $this->request->getRequestUri(),
    		'viewController' => 'ppit-studies/view-controller/student-scripts.phtml',
    		'configProperties' => $configProperties,
    		'groups' => Account::getList('group', [], '+name', null),
    
    		'template' => $template,
    		'logo' => $logo,
    		'logo_height' => $logo_height,
    
    		'token' => $this->params()->fromQuery('hash', null),
    		'panel' => $panel,
    		'email' => $email,
    		'redirect' => $redirect,
    		'message' => $message,
    		'error' => $error,
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
    	$commitmentProperties = \PpitCommitment\Model\Commitment::getDescription('p-pit-studies')['update'];
    
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
    		'commitmentProperties' => $commitmentProperties,
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
    
    public function searchV2action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();

		$places = Place::getList(array());
		$cursor = Account::getList('group', ['status' => 'active'], '+name', null);
		$groups = [];
		foreach ($cursor as $group) {
			$label = $group->name;
			if ($group->place_id && array_key_exists($group->place_id, $places)) $label .= ' (' . $places[$group->place_id]->caption . ')';
			$groups[$group->id] = ['default' => $label];
		}
		
		$myAccount = Account::get($context->getContactId(), 'contact_1_id');
    	if ($myAccount && $myAccount->groups) $myGroups = explode(',', $myAccount->groups);
    	else $myGroups = [];
    	
    	// Return the link list
    	$view = new ViewModel(array(
    		'context' => $context,
    		'config' => $context->getconfig(),
    		'places' => Place::getList(array()),
    		'groups' => $groups,
    		'myGroups' => $myGroups,
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
    	$accountConfig = Account::getConfig('p-pit-studies');
    	 
    	if (count($params) == 0) $mode = 'todo'; else $mode = 'search';
    	$params['status'] = 'active,retention,alumni,inscrit_passerelle';
    
    	// Retrieve the list
    	$accounts = Account::getList('p-pit-studies', $params, (($dir == 'DESC') ? '-' : '+').$major, $limit);
    
    	// Return the link list
    	$view = new ViewModel(array(
    		'context' => $context,
    		'config' => $context->getconfig(),
    		'accountConfig' => $accountConfig,
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
    
    public function listV2Action()
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
    	(new SsmlAccountViewHelper)->formatXls($description, $workbook, $view->accounts);
    	$writer = new \PHPExcel_Writer_Excel2007($workbook);
    
    	header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    	header('Content-Disposition:inline;filename=Fichier.xlsx ');
    	$writer->save('php://output');
    }
    
    public function detailAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    	$id = $this->params()->fromRoute('id');
    	$account = Account::get($id);

    	$view = new ViewModel(array(
    		'context' => $context,
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
    
    	$accounts = [];
    	$accountIds = $this->params()->fromQuery('accounts');
    	if ($accountIds) $accountIds = explode(',', $accountIds);
    	else $accountIds = [];
    	foreach ($accountIds as $account_id) {
    		$account = Account::get($account_id);
    		$accounts[$account_id] = $account;
    	}
    	$place = Place::get($account->place_id);
    
    	$school_periods = $place->getConfig('school_periods');
    	$current_school_period = $context->getCurrentPeriod($school_periods);
    
    	$view = new ViewModel(array(
    		'context' => $context,
    		'config' => $context->getconfig(),
    		'type' => $type,
    		'accounts' => $accounts,
    		'places' => Place::getList(array()),
    		'school_periods' => $school_periods,
    		'current_school_period' => $current_school_period,
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function groupV2Action()
    {
    	return $this->groupAction();
    }
    
    public function addAbsenceV2Action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	// Retrieve the type
    	$type = $this->params()->fromRoute('type', null);
    
    	$absence = Absence::instanciate($type);
    
    	$accounts = [];
    	$accountIds = $this->params()->fromQuery('accounts');
    	if ($accountIds) $accountIds = explode(',', $accountIds);
    	else $accountIds = [];
    	foreach ($accountIds as $account_id) {
    		$account = Account::get($account_id);
    		$accounts[$account_id] = $account;
    	}
    	$place = Place::get($account->place_id);
    
    	$school_periods = $place->getConfig('school_periods');
    	$current_school_period = $context->getCurrentPeriod($school_periods);
    	$absence->school_period = $current_school_period;
    
    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	$message = null;
    
    	$request = $this->getRequest();
    	if ($request->getPost('category')) {
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    
    		if ($csrfForm->isValid()) { // CSRF check
    				
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

    public function addNoteV2Action() {
    
    	// Retrieve the context
    	$context = Context::getCurrent();
    	$places = Place::getList(array());
    
    	// Retrieve the type and class
    	$type = $this->params()->fromRoute('type', null);
    	$class = $this->params()->fromQuery('class', null);
    
    	$note = Note::instanciate('homework', $class);
    	 
    	$note->class = $class;
    
    	$accounts = [];
    	$accountIds = $this->params()->fromQuery('accounts');
    	if ($accountIds) $accountIds = explode(',', $accountIds);
    	else $accountIds = [];
    	foreach ($accountIds as $account_id) {
    		$account = Account::get($account_id);
    		$accounts[$account_id] = $account;
    	}
    	$place = Place::get($account->place_id);
    	$note->place_id = $account->place_id;
    
    	$document = null;
    
    	$done_observations = null;
    	$todo_observations = null;
    	$event_observations = null;
    
    	// Instanciate the csrf form
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	$message = null;
    	$request = $this->getRequest();
    	 
    	$school_periods = $place->getConfig('school_periods');
    	$current_school_period = $context->getCurrentPeriod($school_periods);
    	$note->school_period = $current_school_period;
    
    	if ($request->isPost()) {
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    
    		if ($csrfForm->isValid()) { // CSRF check
    
    			$document_id = $request->getPost('document');
    			$document = Document::get($document_id);
    
    			// Load the note data
    			$data = array();
    			$data['place_id'] = $request->getPost('place_id');
    			$data['category'] = 'homework';
    			$data['school_year'] = $context->getConfig('student/property/school_year/default');
    			$data['school_period'] = $request->getPost('school_period');
    			$data['class'] = $request->getPost('class');
    			$data['subject'] = $request->getPost('subject');
    			$data['date'] = $request->getPost('date');
    			$data['target_date'] = $request->getPost('target_date');
    			$data['document'] = $document_id;
    			$data['comment'] = $request->getPost('comment');
    
    			// Atomically save
    			$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
    			$connection->beginTransaction();
    			try {
    					
    				// Done work
    				if ($request->getPost('done_observations')) {
    					$data['type'] = 'done-work';
    					$data['observations'] = $request->getPost('done_observations');
    					$done_observations = $data['observations'];
    					$rc = $note->loadData($data);
    					if ($rc != 'OK') throw new \Exception('View error');
    
    					$rc = $note->add();
    					if ($rc != 'OK') $error = $rc;
    
    					// Save the note at the student level
    					else foreach ($accounts as $account_id => $account) {
    						$noteLink = NoteLink::instanciate($account_id, $note->id);
    						$rc = $noteLink->add();
    						if ($rc != 'OK') {
    							$connection->rollback();
    							$error = $rc;
    						}
    					}
    				}
    
    				// Todo work
    				if ($request->getPost('todo_observations')) {
    					$data['type'] = 'todo-work';
    					$data['observations'] = $request->getPost('todo_observations');
    					$todo_observations = $data['observations'];
    					$rc = $note->loadData($data);
    					if ($rc != 'OK') throw new \Exception('View error');
    
    					$rc = $note->add();
    					if ($rc != 'OK') $error = $rc;
    
    					// Save the note at the student level
    					else foreach ($accounts as $account_id => $account) {
    						$noteLink = NoteLink::instanciate($account->id, $note->id);
    						$rc = $noteLink->add();
    						if ($rc != 'OK') {
    							$connection->rollback();
    							$error = $rc;
    						}
    					}
    				}
    
    				// Event
    				if ($request->getPost('event_observations')) {
    					$data['type'] = 'event';
    					$data['observations'] = $request->getPost('event_observations');
    					$event_observations = $data['observations'];
    					$rc = $note->loadData($data);
    					if ($rc != 'OK') throw new \Exception('View error');
    
    					$rc = $note->add();
    					if ($rc != 'OK') $error = $rc;
    
    					// Save the note at the student level
    					else foreach ($accounts as $account_id => $account) {
    						$noteLink = NoteLink::instanciate($account->id, $note->id);
    						$rc = $noteLink->add();
    						if ($rc != 'OK') {
    							$connection->rollback();
    							$error = $rc;
    						}
    					}
    				}
    
    				if ($error) $connection->rollback();
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
    		'places' => $places,
    		'type' => $type,
    		'accounts' => $accounts,
    		'note' => $note,
    		'done_observations' => $done_observations,
    		'todo_observations' => $todo_observations,
    		'event_observations' => $event_observations,
    		'document' => $document,
    		'csrfForm' => $csrfForm,
    		'error' => $error,
    		'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }
    
    public function addEvaluationV2Action() {
    
    	// Retrieve the context
    	$context = Context::getCurrent();
    	$places = Place::getList(array());
    	$type = $this->params()->fromRoute('type');
    
    	// Retrieve the class
    	$class = $this->params()->fromQuery('class', null);
    
    	$note = Note::instanciate($type, $class);
    
    	$accounts = [];
    	$accountIds = $this->params()->fromQuery('accounts');
    	if ($accountIds) $accountIds = explode(',', $accountIds);
    	else $accountIds = [];
    	foreach ($accountIds as $account_id) {
    		$account = Account::get($account_id);
    		$accounts[$account_id] = $account;
    	}
    
    	$place = Place::get($account->place_id);
    	$note->place_id = $account->place_id;
    
    	// user_story - student_evaluation_teachers: Les enseignants pouvant être selectionnés dans le formulaire sont tous les enseignants ayant un statut "actif"
    	// todo: not compliant to the user story
    
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
    
    	// user_story - student_evaluation_period: La période est pré-renseignée à la période en cours (en paramètre) mais peut être modifiée (ex. pour effectuer une rétro-saisie sur une période antérieure).
    	$school_periods = $place->getConfig('school_periods');
    	$current_school_period = $context->getCurrentPeriod($school_periods);
    	$note->school_period = $current_school_period;
    
    	$request = $this->getRequest();
    	$csrfForm = new CsrfForm();
    	$csrfForm->addCsrfElement('csrf');
    	$error = null;
    	$message = null;
    
    	if ($request->isPost()) {
    		$csrfForm->setInputFilter((new Csrf('csrf'))->getInputFilter());
    		$csrfForm->setData($request->getPost());
    
    		if ($csrfForm->isValid()) { // CSRF check
    
    			// Load the input data
    			$data = array();
    			$data['status'] = 'current';
    			$data['place_id'] = $request->getPost('place_id');
    
    			// User story - student_evaluation_teachers:
    			// Rôle manager: les enseignants pouvant être selectionnés dans le formulaire sont tous les enseignants ayant un statut "actif".
    			// Rôle enseignant: je ne peux pas affecter un autre enseignant que moi-même à l'évaluation.
    			 
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
    
    			// user_story - student_evaluation_add_student_more_student:
    			// Si l'évaluation existe déjà (même période, classe, matière, catégorie et date), les étudiants sélectionnés en action groupée sont ajoutés à cette évaluation uniquement s'ils n'y sont pas déjà affectés.
    			// Si un étudiant est sélectionné mais et déjà affecté à cette évaluation, il est écarté de la sélection et l'utilisateur est averti du rejet.
    
    			$previousNote = Note::retrieve($data['place_id'], 'evaluation', $type, $data['class'], $data['school_year'], $data['school_period'], $data['subject'], $data['level'], $data['date']);
    			if ($previousNote) $note = $previousNote; // Notifier que l'évaluation existe est n'accepter l'ajout que de nouveaux élèves sur l'évaluation existante
    			else $note->links = array();

    			// In the case of an exam, compute the average of all the note for the exam per class subjects and for all the selected students
    			if ($type == 'exam') {
    				$examAverages = Note::computeExamAverages($data['place_id'], $data['school_year'], $data['class'], $data['level']);
    			}
    			 
    			foreach ($accounts as $account_id => $account) {
    				$noteLink = NoteLink::instanciate($account->id, null);
    				$value = $request->getPost('value_'.$account->id);
    				if ($value == '') $value = null;

    				// Set the exam average
    			    if ($type == 'exam' && $value === null) {
    					if (array_key_exists($account->id, $examAverages)) {
							$value = $examAverages[$account->id]['global']['note'];
							$audit = $examAverages[$account->id]['global']['notes'];
						}
    			    	else $value = null;
    			    	if ($value !== null) $value = $value * $data['reference_value'] / 20;
    				}

    				$mention = $request->getPost('mention_'.$account->id);
    				$assessment = $request->getPost('assessment_'.$account->id);
    				$audit = [];
    				if ($value !== null || $assessment) {
    					$noteLink->value = $value;
    					$noteLink->evaluation = $mention;
    					$noteLink->assessment = $assessment;
    					$noteLink->distribution = array();
    
    					// todo: Not compliant to the user story. The evaluation for students already linked should not be affected
    					if (array_key_exists($noteLink->account_id, $note->links)) $note->links[$noteLink->account_id]->delete(null);
    					$note->links[$noteLink->account_id] = $noteLink;
    				}
    			}
    
    			// user_story - student_evaluation_group_indicators: L'ajout d'une évaluation ou l'ajout d'étudiants à une évaluation existante entraîne le recalcul automatique de la note inférieure, de la note moyenne et de la note supérieure de l'ensemble des notes à cette évaluation de tous les étudiants qui lui sont affectés. Les étudiants affectés mais non notés n'influencent pas ce calcul.
    
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
		    				// Déactivation temporaire du calcul automatique des moyennes
    						if (!$error) {
    
    							// user_story - student_evaluation_subject_average:
    							// L'ajout d'une évaluation ou l'ajout d'étudiants à une évaluation existante entraîne le recalcul automatique de la moyenne de la matière de la période, pour chaque élève affecté à l'évaluation.
    							// La note de référence des moyennes (ex. /20) est un paramètre global.
    							// La moyenne par matière calculée est mémorisée pour pouvoir être affichée dans la home étudiant.
    								
    							$school_periods = $place->getConfig('school_periods');
    							$current_school_period = $context->getCurrentPeriod($school_periods);
    							$note->school_period = $current_school_period;
    
    							// Load the input data
		    					unset($data['date']);
		    					unset($data['teacher_id']);
		    					$data['level'] = null;
    							$data['reference_value'] = 20;
		    					$data['weight'] = 1;
    							$data['observations'] = null;
    							$data['comment'] = null;
    							$data['criteria'] = array();
    
    							// Compute the new subject average for the period of all the student linked to the selected place and belonging to the selected class
    							$newSubjectAverages = Note::computePeriodAverages($data['place_id'], $data['school_year'], $data['class'], $data['school_period'], $data['subject']);
    
    							$previousReport = Note::retrieve($data['place_id'], 'evaluation', 'report', $data['class'], $data['school_year'], $data['school_period'], $data['subject']);
    							if ($previousReport) $report = $previousReport;
    							else {
    								$report = Note::instanciate('report', $class);
    								$report->links = array();
    								$report->place_id = $account->place_id;
    								if (array_key_exists($context->getContactId(), $teachers)) $report->teacher_id = $context->getContactId();
    							}
    
    							foreach ($accounts as $account_id => $account) {
    									
    								// Compute the average only for evaluated students in the list
    								if (array_key_exists($account->id, $newSubjectAverages)) {
    									if (array_key_exists($account_id, $report->links)) $reportLink = $report->links[$account_id];
    									else $reportLink = NoteLink::instanciate($account->id, null);
    									$audit = [];
    									$value = $newSubjectAverages[$account->id]['global']['note'];
    									$audit = $newSubjectAverages[$account->id]['global']['notes'];
    									$value = $value * 20 / $context->getConfig('student/parameter/average_computation')['reference_value'];
    									$reportLink->value = $value;
    									$reportLink->distribution = array();
    									foreach ($newSubjectAverages[$account->id] as $categoryId => $category) {
    										if ($categoryId != 'global') $reportLink->distribution[$categoryId] = $category['note'];
    									}
    									$reportLink->audit = $audit;
    
    									// todo: not delete and add. Update instead
    
//    									if (array_key_exists($reportLink->account_id, $report->links)) $report->links[$reportLink->account_id]->delete(null);
    									if (!array_key_exists($reportLink->account_id, $report->links)) $report->links[$reportLink->account_id] = $reportLink;
    								}
    							}
    
    							// user_story - student_evaluation_subject_average_indicators: Le recalcul d'une moyenne par matière de la classe entraîne le recalcul automatique de la moyenne inférieure, de la moyenne des moyennes et de la moyenne supérieure de l'ensemble des moyennes à cette matière de tous les étudiants de la classe.
    
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
    
    							// user_story - student_evaluation_global_average:
    							// L'ajout d'une évaluation ou l'ajout d'étudiants à une évaluation existante entraîne le recalcul automatique de moyenne générale de la période, pour chaque élève affecté à l'évaluation.
    							// La note de référence des moyennes (ex. /20) est un paramètre global.
    							// La moyenne générale calculée est mémorisée pour pouvoir être affichée dans la home étudiant.
    
    							$newGlobalAverages = Note::computePeriodAverages($data['place_id'], $data['school_year'], $data['class']);
    							$report->id = null;
    
    							$previousReport = Note::retrieve($data['place_id'], 'evaluation', 'report', $data['class'], $data['school_year'], $data['school_period'], 'global'/*, $data['level'], $data['date']*/);
    							if ($previousReport) $report = $previousReport;
    							else $report->links = array();
    
    							$data['subject'] = 'global';
    
    							foreach ($accounts as $account_id => $account) {
    								if (array_key_exists($account->id, $newGlobalAverages)) {
    									if (array_key_exists($account_id, $report->links)) $reportLink = $report->links[$account_id];
    									else $reportLink = NoteLink::instanciate($account->id, null);
    									$audit = [];
    									$value = $newGlobalAverages[$account->id]['global']['note'];
    									$audit = $newGlobalAverages[$account->id]['global']['notes'];
    									$value = $value * 20 / $context->getConfig('student/parameter/average_computation')['reference_value'];
    									$reportLink->value = $value;
    									$reportLink->distribution = array();
    									foreach ($newGlobalAverages[$account->id] as $categoryId => $category) {
    										if ($categoryId != 'global') $reportLink->distribution[$categoryId] = $category['note'];
    									}
    
    									$reportLink->audit = $audit;
//    									if (array_key_exists($reportLink->account_id, $report->links)) $report->links[$reportLink->account_id]->delete(null);
    									if (!array_key_exists($reportLink->account_id, $report->links)) $report->links[$reportLink->account_id] = $reportLink;
    								}
    							}
    
    							// user_story - student_evaluation_global_average_indicators: Le recalcul de la moyenne générale de la classe entraîne le recalcul automatique de la moyenne inférieure, de la moyenne des moyennes et de la moyenne supérieure de l'ensemble des moyennes de tous les étudiants de la classe.
    
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
    	}
    
    	$view = new ViewModel(array(
    		'context' => $context,
    		'config' => $context->getconfig(),
    		'places' => $places,
    		'current_school_period' => $current_school_period,
    		'teachers' => $teachers,
    		'type' => 'note',
    		'accounts' => $accounts,
    		'note' => $note,
    		'csrfForm' => $csrfForm,
    		'error' => $error,
    		'message' => $message
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function planningV2Action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$account_id = (int) $this->params()->fromRoute('id');
    	$account = Account::get($account_id);
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
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$account_id = (int) $this->params()->fromRoute('id');
    	$account = Account::get($account_id);

    	$start_date = $this->params()->fromRoute('start_date', $context->getConfig('student/property/school_year/start'));
    	$end_date = $this->params()->fromRoute('end_date', $context->getConfig('student/property/school_year/end'));
    	 
    	$reports = $this->getReport($account);
    	$absences = $this->getAbsences($account);

    	// Homework
    	$template = $context->getConfig('commitment/message/p-pit-studies' . $account->type);
		$filters['folder'] = ['eq', 'commitments'];
		$filters['account_id'] = ['eq', $account_id];
		$select = Document::getSelect('binary', [], $filters, ['name']);
		$documents = [];
    	$cursor = Document::getTable()->selectWith($select);
    	foreach ($cursor as $document) {
    		$documents[$document->id] = $document;
    	}

    	$view = new ViewModel(array(
    		'context' => $context,
    		'reports' => $reports,
    		'absences' => $absences,
    		'documents' => $documents,
    		'start_date' => $start_date,
    		'end_date' => $end_date,
    		'account_id' => $account->id,
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function contentAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$account_id = (int) $this->params()->fromRoute('id');
    	$account = Account::get($account_id);

    	$category = $this->params()->fromRoute('category');
    	 
    	$view = new ViewModel(array(
    		'context' => $context,
    		'account_id' => $account->id,
    		'category' => $category,
    	));
    	$view->setTerminal(true);
    	return $view;
    }
    
    public function generateAttendanceAction()
    {
    	// Retrieve the context and parameters
    	$context = Context::getCurrent();
    	$account_id = (int) $this->params()->fromRoute('account_id');
    	$account = Account::get($account_id);
    	$place = Place::get($account->place_id);
    	$addressee = $this->params()->fromQuery('addressee');
    	$start_date = $this->params()->fromRoute('start_date', $context->getConfig('student/property/school_year/start'));
    	$end_date = $this->params()->fromRoute('end_date', $context->getConfig('student/property/school_year/end'));

    	// Add the presentation template
    	$attendance = Document::generateAttendance($account, $addressee, $start_date, $end_date, $place);
    
    	// Render the message in HTML
    	$html = CommitmentMessageViewHelper::renderHtml($attendance, $place);
    
    	$view = new ViewModel(array(
    		'context' => $context,
    		'account_id' => $account_id,
    		'start_date' => $start_date,
    		'end_date' => $end_date,
    		'attendance' => $attendance,
    		'html' => $html,
    	));
    	$view->setTerminal(true);
    	return $view;
    }
    
    public function downloadAttendanceAction()
    {
    	// Retrieve the context, parameters and data
    	$context = Context::getCurrent();
    	$account_id = (int) $this->params()->fromRoute('account_id');
    	$account = Account::get($account_id);
    	$place = Place::get($account->place_id);
    	$addressee = $this->params()->fromQuery('addressee');
    	$start_date = $this->params()->fromRoute('start_date', $context->getConfig('student/property/school_year/start'));
    	$end_date = $this->params()->fromRoute('end_date', $context->getConfig('student/property/school_year/end'));
    	 
    	// Add the presentation template
    	$attendance = Document::generateAttendance($account, $addressee, $start_date, $end_date, $place);

    	foreach ($attendance['data'] as $propertyId => $unused) {
    		$pos = strpos($propertyId, ':');
    		if ($pos > 0) $recodedId = substr($propertyId, 0, $pos) . '-' . substr($propertyId, $pos + 1);
    		else $recodedId = $propertyId;
    		if ($this->params()->fromQuery($recodedId)) $attendance['data'][$propertyId] = $this->params()->fromQuery($recodedId);
	    }
	     
    	// create new PDF document
    	$pdf = new PpitPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    	CommitmentMessageViewHelper::renderPdf($pdf, $attendance, $place);
    
    	$content = $pdf->Output($attendance['identifier'] . '-' . $context->getInstance()->caption . '-' . $account->n_fn . '.pdf', 'I');
    	return $this->response;
    }

    public function getAbsences($account)
    {
    	// Retrieve the context and parameter values
    	$context = Context::getCurrent();
    	$start_date = $this->params()->fromRoute('start_date', $context->getConfig('student/property/school_year/start'));
    	$end_date = $this->params()->fromRoute('end_date', $context->getConfig('student/property/school_year/end'));
    	
		$place = Place::get($account->place_id);

    	$absLates = Absence::getList('schooling', ['account_id' => $account->id, 'school_year' => $context->getConfig('student/property/school_year/default'), 'min_begin_date' => $start_date, 'max_begin_date' => $end_date], 'begin_date', 'DESC', 'search', null);
    	$absences = array();
    	$latenesss = array();
    
    	$periods = array();
    	foreach($absLates as $absLate) {
    		$key = $absLate->school_year . '.' . $absLate->school_period;
    		if (!array_key_exists($key, $periods)) $periods[$key] = array();
    		$periods[$key][] = ['category' => $absLate->category, 'subject' => $absLate->subject, 'begin_date' => $absLate->begin_date, 'end_date' => $absLate->end_date, 'motive' => $absLate->motive, 'observations' => $absLate->observations];
    	}
    	 
    	$absences = Event::GetList('absence', ['account_id' => $account->id, 'property_1' => $context->getConfig('student/property/school_year/default'), 'min_begin_date' => $start_date, 'max_end_date' => $end_date], '+begin_date', null);
    	foreach($absences as $absence) {
			$placePeriods = $place->getConfig('school_periods');
			$school_period = null;
			foreach ($placePeriods['end_dates'] as $periodId => $date) {
				if ($date >= $absence->begin_date) {
					$school_period = $periodId;
					break;
				}
			}
			if (!$school_period) foreach ($context->getConfig('place_config/default')['school_periods']['end_dates'] as $periodId => $date) {
				if ($date >= $absence->begin_date) {
					$school_period = $periodId;
					break;
				}
			}
			if (!$school_period) $school_period = 'Q1';
			$key = $absence->property_1 . '.' . $school_period;
    		if (!array_key_exists($key, $periods)) $periods[$key] = array();
    		$periods[$key][] = ['category' => 'absence', 'subject' => $absence->property_3, 'begin_date' => $absence->begin_date, 'end_date' => $absence->end_date, 'motive' => $absence->property_12, 'observations' => ''];
    	}
    
    	krsort($periods);
    	return $periods;
    }
    
    public function absenceV2Action()
    {
    	// Retrieve the context and parameter values
    	$context = Context::getCurrent();
    	$account_id = (int) $this->params()->fromRoute('account_id');
    	$account = Account::get($account_id);
    	$start_date = $this->params()->fromRoute('start_date', $context->getConfig('student/property/school_year/start'));
    	$end_date = $this->params()->fromRoute('end_date', $context->getConfig('student/property/school_year/end'));

    	$periods = $this->getAbsences($account);
    	
    	// Return the link list
    	$view = new ViewModel(array(
    		'context' => $context,
    		'config' => $context->getconfig(),
    		'account' => $account,
    		'start_date' => $start_date,
    		'end_date' => $end_date,
    		'periods' => $periods,
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function homeworkV2Action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$account_id = $this->params()->fromRoute('account_id');
    	$account = Account::get($account_id);
    	$type = $this->params()->fromQuery('type');
    	$group_id = $this->params()->fromQuery('group_id');
    	$subject = $this->params()->fromQuery('subject');
    	$date = $this->params()->fromQuery('date');
    
    	$filters = [];
    	$filters['group_id'] = $group_id;
    	if ($account) $filters['place_id'] = $account->place_id;
//    	$filters['place_id'] = $account->place_id;
//    	$filters['class'] = $account->property_7;
    	if ($subject) $filters['subject'] = $subject;
    	if ($type == 'done-work') $filters['date'] = $date;
    	elseif (in_array($type, ['todo-work', 'event'])) $filters['target_date'] = $date;

    	/*		$groups = [];
    		foreach ($account->groups as $group_id => $unused) $groups[] = $group_id;*/
    	//		if ($groups) $filters['groups'] = $groups;
    
    	$notes = Note::GetList('homework', $type, $filters, 'date', 'DESC', 'search', null);
    	foreach ($notes as $note) {
    		$documents = [];
    		if ($note->document) {
    			$documentIds = explode(',', $note->document);
    			foreach ($documentIds as $document_id) {
    				$document = Document::get($document_id);
    				$documents[$document->id] = $document->getProperties();
    			}
    		}
    		$note->properties['documents'] = $documents;
    	}
    
    	// Return the link list
    	$view = new ViewModel(array(
    		'context' => $context,
    		'type' => $type,
    		'subject' => $subject,
    		'date' => $date,
    		'account' => $account,
    		'notes' => $notes,
    	));
    	$view->setTerminal(true);
    	return $view;
    }

    public function evaluationV2Action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$account_id = (int) $this->params()->fromRoute('id');
    	$account = Account::get($account_id);
    	$school_year = $context->getConfig('student/property/school_year/default');
    	$place = Place::get($account->place_id);
    	$school_period = $context->getCurrentPeriod($place->getConfig('school_periods'));
    	$mock = $this->params()->fromRoute('mock');
    
    	$periods = array();
    	$params = array(/*'account_id' => $account->id, */'school_year' => $school_year/*, 'school_period' => $school_period*/);
    	if ($mock) $params['level'] = "mock";
    	$noteLinks = NoteLink::GetList('note', $params, 'date', 'DESC', 'search');
    	foreach($noteLinks as $noteLink) {
	    	$key = $noteLink->school_year.'.'.$noteLink->school_period;
	    	if (!array_key_exists($key, $periods)) $periods[$key] = array();
    		if ($noteLink->account_id == $account->id) {
    			$noteLink->sum = 0;
    			$noteLink->number = 0;
    			$noteLink->min = 100;
    			$noteLink->max = 0;
    			$periods[$key][$noteLink->note_id] = $noteLink;
    		}
    	}
    	krsort($periods);

    	foreach($noteLinks as $noteLink) {
    		if (array_key_exists($noteLink->note_id, $periods[$key])) {
	    		$key = $noteLink->school_year.'.'.$noteLink->school_period;
    			$periods[$key][$noteLink->note_id]->sum += $noteLink->value;
    			$periods[$key][$noteLink->note_id]->number ++;
    			if ($noteLink->value < $periods[$key][$noteLink->note_id]->min) $periods[$key][$noteLink->note_id]->min = $noteLink->value;
	    		if ($noteLink->value > $periods[$key][$noteLink->note_id]->max) $periods[$key][$noteLink->note_id]->max = $noteLink->value;
    		}
    	}
    	 
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

    public function examV2Action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$account_id = (int) $this->params()->fromRoute('id');
    	$account = Account::get($account_id);
    	$place = Place::get($account->place_id);
    
    	$periods = array();
    	$notes = NoteLink::GetList('exam', ['account_id' => $account->id], 'date', 'DESC', 'search');
    	foreach($notes as $note) {
    		$key = $note->school_year.'.'.$note->level;
    		if (!array_key_exists($key, $periods)) $periods[$key] = array();
    		$periods[$key][] = $note;
    	}
    	krsort($periods);
    	foreach ($periods as $periodId => &$period) {
    		$school_year = substr($periodId, 0, 9);
    		$level = substr($periodId, 10);
    		$notes = NoteLink::GetList('note', ['account_id' => $account->id, 'school_year' => $school_year, 'level' => $level], 'date', 'DESC', 'search');
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

    public function getReport($account, $currentYear = false)
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    	
    	$periods = array();
    	$select = array('account_id' => $account->id);
    	if ($currentYear) $select['school_year'] = $context->getConfig('student/property/school_year/default');
    	$notes = NoteLink::GetList('report', $select, 'date', 'DESC', 'search');
    	foreach($notes as $note) {
    		$key = $note->school_year.'.'.$note->school_period;
    		if (!array_key_exists($key, $periods)) $periods[$key] = array();
    		$periods[$key][] = $note;
    	}
    	krsort($periods);
    	return $periods;
    }
    
    public function reportV2Action()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
    
    	$account_id = (int) $this->params()->fromRoute('id');
    	$account = Account::get($account_id);
    	$place = Place::get($account->place_id);

    	$periods = $this->getReport($account, false);

    	foreach ($periods as $periodId => $period) {

    		$periodId = explode('.', $periodId);
    		$school_year = $periodId[0];
    		$school_period = $periodId[1];

    		$params = array('school_year' => $school_year, 'school_period' => $school_period, 'account_id' => $account_id);
    		$notesAccount = NoteLink::GetList('note', $params, 'subject', 'ASC', 'search');
    		
    		if ($period[0]->group_id) {
	    		$params = array('school_year' => $school_year, 'school_period' => $school_period, 'group_id' => $period[0]->group_id);
	    		$notesGroup = NoteLink::GetList('note', $params, 'subject', 'ASC', 'search');
    		}
    		else $notesGroup = [];
    		
    		$notes = array_merge($notesAccount, $notesGroup);
    		
    		// Compute the averages
			$averageReference = $context->getConfig('student/parameter/average_computation')['reference_value'];
    		$computed = AverageComputer::compute($notes);
    		$indicators = $computed['indicators'];
    		$averages = $computed['averages'];
    		foreach ($period as $row) {
    			if (array_key_exists($row->account_id, $averages) && array_key_exists($row->subject, $averages[$row->account_id])) {
    				if (!$row->value) $row->value = $averages[$row->account_id][$row->subject][0] / $averages[$row->account_id][$row->subject][1] * $averageReference;
    				if (array_key_exists($row->subject, $indicators)) {
	    				if (!$row->higher_note) $row->higher_note = $indicators[$row->subject]['higher_note'];
    					if (!$row->lower_note) $row->lower_note = $indicators[$row->subject]['lower_note'];
    					if (!$row->average_note) $row->average_note = $indicators[$row->subject]['average_note'][0] / $indicators[$row->subject]['average_note'][1];
    				}
    			}
    		}
    	}
    
    	// Return the link list
    	$view = new ViewModel(array(
    		'context' => $context,
    		'config' => $context->getconfig(),
    		'account' => $account,
    		'place' => $place,
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
    	/*if ($account->contact_2 && $account->contact_2->adr_street) $addressee = $account->contact_2;
    	elseif ($account->contact_3 && $account->contact_3->adr_street) $addressee = $account->contact_3;
    	elseif ($account->contact_4 && $account->contact_4->adr_street) $addressee = $account->contact_4;
    	elseif ($account->contact_5 && $account->contact_5->adr_street) $addressee = $account->contact_5;
    	else*/ $addressee = $account->contact_1;
    
    	$school_year = $this->params()->fromRoute('school_year');
    	if (!$school_year) $school_year = $context->getConfig('student/property/school_year/default');
    	$place = Place::get($account->place_id);
    	$school_period = $this->params()->fromRoute('school_period');
/*    	if (!$school_period) {
    		$school_period = $context->getCurrentPeriod($place->getConfig('school_periods'));
    	}*/
    	$level = $this->params()->fromRoute('level');

    	$start_date = $this->params()->fromQuery('start_date', $context->getConfig('student/property/school_year/start'));
    	$end_date = $this->params()->fromQuery('end_date', $context->getConfig('student/property/school_year/end'));

    	$absences = array();
    	$latenesss = array();
    	$cumulativeAbsence = 0;
    	$cumulativeLateness = 0;
    	$absenceCount = 0;
    	$latenessCount = 0;
    	$absLates = [];

    	// Retrieve the manual absences
    	$filter = ['account_id' => $account_id, 'school_year' => $school_year, 'min_begin_date' => $start_date, 'max_begin_date' => $end_date];
    	if ($school_period) $filter['school_period'] = $school_period;
    	$cursor = Absence::getList(null, $filter, 'date', 'DESC', 'search', null);
    	foreach ($cursor as $absLate) {
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
    		$absLates[] = ['category' => $absLate->category, 'subject' => $absLate->subject, 'begin_date' => $absLate->begin_date, 'end_date' => $absLate->end_date, 'duration' => $absLate->duration, 'motive' => $absLate->motive, 'observations' => $absLate->observations];
    	}
    	
    	// Retrieve the absences from attendance sheet
    	$cursor = Event::GetList('absence', array('account_id' => $account->id, 'property_1' => $school_year, 'min_begin_date' => $start_date, 'max_begin_date' => $end_date), '+begin_date', null);
    	foreach ($cursor as $absence) {
			$placePeriods = $place->getConfig('school_periods');
			$period = null;
			foreach ($placePeriods['end_dates'] as $periodId => $date) {
				if ($date >= $absence->begin_date) {
					$period = $periodId;
					break;
				}
			}
			if (!$period) foreach ($context->getConfig('place_config/default')['school_periods']['end_dates'] as $periodId => $date) {
				if ($date >= $absence->begin_date) {
					$period = $periodId;
					break;
				}
			}
			if (!$period) $period = 'Q1';

			if ($period == $school_period && $absence->end_time >= $absence->begin_time) {
				$startHour = (int) substr($absence->begin_time, 0, 2);
				$startMin = (int) substr($absence->begin_time, 3, 2);
				$start = $startHour * 60 + $startMin;
				$endHour = (int) substr($absence->end_time, 0, 2);
				$endMin = (int) substr($absence->end_time, 3, 2);
				$end = $endHour * 60 + $endMin;
	    		$cumulativeAbsence += $end - $start;
	    		$absenceCount++;
	    		$absLates[] = ['category' => 'absence', 'subject' => $absence->property_3, 'begin_date' => $absence->begin_date, 'end_date' => $absence->end_date, 'duration' => $end - $start, 'motive' => $absence->property_12, 'observations' => ''];
    		}
    	}
    	 
    	$date = null;
    	$classSize = null;
    	if ($category == 'report') {
    		$averages = NoteLink::GetList($category, array('account_id' => $account_id, 'school_year' => $school_year, 'school_period' => $school_period), 'date', 'DESC', 'search');
    		foreach ($averages as $average) if ($average->subject == 'global') {
    			$group_id = $average->group_id;
    			$date = $average->date;
    			$classSize = count(NoteLink::GetList($category, array('note_id' => $average->note_id), 'date', 'DESC', 'search'));
    		}
    	}
    	else $averages = null;

    	$notesAccount = [];
    	$params = array('school_year' => $school_year, 'school_period' => $school_period/*, 'account_id' => $account_id*/);
    	if ($level) $params['level'] = $level;
    	$maxDate = $context->getConfig('note_link/maxDate');
    	if ($maxDate) $params['max_date'] = $maxDate;
    	$cursor = NoteLink::GetList('note', $params, 'subject', 'ASC', 'search');
    	foreach ($cursor as $noteLink) {
    		if ($noteLink->account_id == $account_id) {
    			$notesAccount[$noteLink->note_id] = $noteLink;
    			$noteLink->sum = 0;
    			$noteLink->number = 0;
    			$noteLink->min = 100;
    			$noteLink->max = 0;
    		}
    	}
    	foreach ($cursor as $noteLink) {
    		if (array_key_exists($noteLink->note_id, $notesAccount)) {
	    		$notesAccount[$noteLink->note_id]->sum += $noteLink->value;
	    		$notesAccount[$noteLink->note_id]->number++;
	    		if ($noteLink->value < $notesAccount[$noteLink->note_id]->min) $notesAccount[$noteLink->note_id]->min = $noteLink->value;
	    		if ($noteLink->value > $notesAccount[$noteLink->note_id]->max) $notesAccount[$noteLink->note_id]->max = $noteLink->value;
    		}
    	}
    	
    	if ($category == 'report' && $account->groups) {
    		$params = array('school_year' => $school_year, 'school_period' => $school_period, 'place_id' => $account->place_id, 'group_id' => $account->groups);
    		if ($level) $params['level'] = $level;
    		$notesGroup = NoteLink::GetList('note', $params, 'subject', 'ASC', 'search');
    	}
    	else $notesGroup = [];
    	
    	$notes = array_merge($notesAccount, $notesGroup);
    	if (!$date) foreach ($notes as $note) if ($note->subject == 'global') $date = $note->date;

    	if ($category == 'report') {
    		$averageReference = $context->getConfig('student/parameter/average_computation')['reference_value'];
    		
	    	// Compute the averages
	    	$computed = AverageComputer::compute($notes);
	    	$indicators = $computed['indicators'];
	    	$computed = $computed['averages'];
	    	foreach ($averages as $average) {
	    		if (array_key_exists($average->account_id, $computed) && array_key_exists($average->subject, $computed[$average->account_id])) {
	    			if (!$average->value && $computed[$average->account_id][$average->subject][1]) $average->value = $computed[$average->account_id][$average->subject][0] / $computed[$average->account_id][$average->subject][1] * $averageReference;
    				if (array_key_exists($average->subject, $indicators) && $indicators[$average->subject]['average_note'][1]) {
		    			if (!$average->higher_note) $average->higher_note = $indicators[$average->subject]['higher_note'];
		    			if (!$average->lower_note) $average->lower_note = $indicators[$average->subject]['lower_note'];
		    			if (!$average->average_note) $average->average_note = $indicators[$average->subject]['average_note'][0] / $indicators[$average->subject]['average_note'][1];
    				}
	    		}
	    	}
    	}
    	 
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

    public function nomad($request, $from, $place_identifier, $limit)
    {
    	$context = Context::getCurrent();
    	$safe = $context->getConfig()['ppitUserSettings']['safe'];
    	//    	$url = 'https://v1.adam.nomadeducation.fr/'.$request.'?from='.$from.'&limit='.$limit;
    	$url = 'https://v1.adam.nomadeducation.fr/'.$request.'?where={"updatedAt":{"gte":"'.$from.'"},"schoolContactAcceptance":true}';
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
    
    		// If the account exists, update it if the contact is a prospect (new status) and the existing account is a suspect
    		$account = Account::get($lead['id'], 'identifier');
    		if (!$account) {
    			$vcard = Vcard::get($lead['email'], 'email');
    			if ($vcard) $account = Account::get($vcard->id, 'contact_1_id');
    		}
    		if ($account && in_array($account->status, ['suspect', 'gone']) && $lead['type'] == 'sponsor') {
    			$account->status = 'new';
    			$account->callback_date = date('Y-m-d');
    			$account->update(null);
    			continue;
    		}
    
    		$data = [];
    		if ($place_identifier) $data['place_id'] = Place::get($place_identifier, 'identifier')->id;
    		$data['identifier'] = $lead['id'];
    		$data['status'] = (array_key_exists('type', $lead) && $lead['type'] == 'registration') ? 'suspect' : 'new';
    		$data['origine'] = 'nomad';
    		$data['callback_date'] = date('Y-m-d');
    		foreach ($context->getConfig('core_account/nomad/p-pit-studies')['properties'] as $propertyId => $property) {
    			if (array_key_exists($property, $lead)) $data[$propertyId] = $lead[$property];
    		}
    
    		$levels = ['Terminale' => '1st', '1ère année' => '2nd', '2ème année' => '3rd', '3ème année' => '4th'];
    		$data['property_10'] = (array_key_exists($lead['levelOfEducation'], $levels)) ? $levels[$lead['levelOfEducation']] : '';
    
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
    		$account->loadAndAdd($data, Account::getConfig('p-pit-studies'));
    	}
    	return $this->response;
    }
    
    public function nomadAction() {
    
    	$context = Context::getCurrent();
    	 
    	// Authentication
    	if (!$context->isAuthenticated() && !$context->wsAuthenticate($this->getEvent())) {
    		$this->getResponse()->setStatusCode('401');
    		return $this->getResponse();
    	}
    	 
    	$request = $this->params()->fromRoute('request');
    	$date = $this->params()->fromQuery('date');
    	if (!$date) $date = date('Y-m-d', strtotime(date('Y-m-d').' - 1 days'));
    	echo 'Extraction date: ' . $date . "\n";
    	$from = $this->params()->fromRoute('from', $date);
    	$place_identifier = $this->params()->fromQuery('place_identifier', '');
    	$limit = $this->params()->fromQuery('limit', 10);
    	return $this->nomad($request, $from, $place_identifier, $limit);
    }
    
    public function batchNomadAction() { // Deprecated
    	$context = Context::getCurrent();
    	$instance_id = $this->params()->fromRoute('instance_id');
    	$context->updateFromInstanceId($instance_id);
    	$request = $this->params()->fromRoute('request');
    	$place_identifier = $this->params()->fromRoute('place_identifier', '');
    	$limit = $this->params()->fromRoute('limit', 10);
    	echo date('Y-m-d')."\n";
    	return $this->nomad($request, date('Y-m-d', strtotime(date('Y-m-d').' - 1 days')), $place_identifier, $limit);
    }

    public function keystoneAction() 
	{
		$request = $this->getRequest();
		if (!$request->isPost()) {
			$this->getResponse()->setStatusCode('401');
    		return $this->getResponse();
		}

		$context = Context::getCurrent();

		// Authentication
		if (!$context->isAuthenticated() && !$context->wsAuthenticate($this->getEvent())) {
    		$this->getResponse()->setStatusCode('401');
    		return $this->getResponse();
    	}

		$content = $this->request->getContent();
		$data = json_decode($content, true);

		// Mapping data
		$lead[0]['id'] = $data['id'];
		$lead[0]['identifier'] = 'KYST-' . $data['id'];
		$lead[0]['origine'] = 'master_etude';
		$lead[0]['status'] = 'new';
		$lead[0]['place_identifier'] = '1_paris';
		$lead[0]['property_10'] = '4th';
		$lead[0]['property_18'] = $data['program']['custom_program_id'];
		$lead[0]['n_first'] = $data['firstname'];
		$lead[0]['n_last'] = $data['lastname'];
		$lead[0]['email'] = strtolower($data['contact']['email']);
		$lead[0]['tel_cell'] = $data['contact']['phone'];
		$lead[0]['adr_street'] = $data['contact']['address'];
		$lead[0]['adr_zip'] = $data['contact']['zip'];
		$lead[0]['adr_city'] = $data['contact']['city'];
		$lead[0]['adr_country'] = $data['contact']['country']['name'];
		$lead[0]['property_6'] = (strtolower($data['nationality_country']['country_name']) === 'france') ? 'france' : 'visa';
		$lead[0]['gender'] = $data['gender'];
		$lead[0]['contact_history'] = $data['communication']['posts'];
		// $lead[0]['contact_history'] = $data['communication']['posts'][0]['message'];

		// Connect to P-Pit Account API
		$safe = $context->getConfig()['ppitUserSettings']['safe']['ESI']['ppitWebhook'];
    	$url = $safe['protocol'] . '://' . $safe['subDomain'] . '/account/v2/p-pit-studies';
    	$client = new Client($url, ['adapter' => 'Zend\Http\Client\Adapter\Curl', 'maxredirects' => 0, 'timeout' => 30]);
    	$client->setHeaders(['Authorization' => $safe['auth'], 'Accept-Encoding' => 'gzip,deflate']);
		$client->setMethod('PUT');
		$client->setRawBody(json_encode($lead));
		$postResponse = $client->send();

		// Initialize the logger
		$writer = new \Zend\Log\Writer\Stream('data/log/keystone.txt');
		$logger = new \Zend\Log\Logger();
		$logger->addWriter($writer);
		$logger->info(print_r($content).print_r($data).print_r($lead));


		if ($postResponse->getStatusCode() == 200) {
			$responseBody = json_decode($postResponse->getContent(), true);
			$account = $responseBody[$lead[0]['email']];

			if ($account['status'] == 'OK') {	
				$this->response->setContent($postResponse->getContent()); // debugging mode only
				$this->response->setStatusCode($postResponse->getStatusCode());
				$this->response->setReasonPhrase("created successfully");
				// $this->response->setReasonPhrase($postResponse->getReasonPhrase());
				return $this->response;
			}

			if ($account['status'] == 'exists') {

				if ((in_array($account['account_status'], ['gone', 'called', 'reminder'])) && $account['account_callback_date'] < date('Y-m-d')) {

					$update['status'] = $lead[0]['status'];
					$update['origine'] = $lead[0]['origine'];
					$update['place_identifier'] = $lead[0]['place_identifier'];
		
					$rest = 'Lead réactivé => Keystone data : <br>';
					foreach ($lead[0] as $property => $value) {
						if (in_array($property, ['origine', 'n_first', 'n_last', 'email', 'tel_cell', 'contact_history'])) {
							$rest .= (' '. $property .' : ' . $value . ' <br>');
						}
					}

					$update['contact_history'] = $rest;
					
					$url = $safe['protocol'] . '://' . $safe['subDomain'] . '/account/v2/p-pit-studies/' . $account['account_id'];
					$client = new Client($url, ['adapter' => 'Zend\Http\Client\Adapter\Curl', 'maxredirects' => 0, 'timeout' => 30]);
					$client->setHeaders(['Authorization' => $safe['auth'], 'Accept-Encoding' => 'gzip,deflate',]);
					$client->setMethod('POST');
					$client->setRawBody(json_encode($update));

					$updateResponse = $client->send();

					if ($updateResponse->getStatusCode() == 200) {
						$updatedAccount = json_decode($updateResponse->getContent(), true);
						if ($updatedAccount[$account['account_id']] == 'Updated') {
							$this->response->setContent($updateResponse->getContent()); // debugging mode only
							$this->response->setStatusCode($updateResponse->getStatusCode());
							$this->response->setReasonPhrase("updated successfully");
							// $this->response->setReasonPhrase($updateResponse->getReasonPhrase());
							return $this->response;
						}
					} else {
						$this->response->setContent($updateResponse->getContent()); // debugging mode only
						$this->response->setStatusCode($updateResponse->getStatusCode());
						$this->response->setReasonPhrase($updateResponse->getReasonPhrase());
						return $this->response;
					}
				} else {
					$this->response->setContent($postResponse->getContent()); // debugging mode only
					$this->response->setStatusCode($postResponse->getStatusCode());
					$this->response->setReasonPhrase("update case not met");
					// $this->response->setReasonPhrase($postResponse->getReasonPhrase());
					return $this->response;
				}
			} else {
				$this->response->setContent($postResponse->getContent()); // debugging mode only
				$this->response->setStatusCode($postResponse->getStatusCode());
				$this->response->setReasonPhrase("already exists");
				// $this->response->setReasonPhrase($postResponse->getReasonPhrase());
				return $this->response;
			}
		} else {	
			$this->response->setContent($postResponse->getContent()); // debugging mode only
			$this->response->setStatusCode($postResponse->getStatusCode());
			$this->response->setReasonPhrase($postResponse->getReasonPhrase());
			return $this->response;
		}	
	}
	
	public function initAction()
	{
		$context = Context::getCurrent();
		foreach ($context->getConfig('student/property/class')['modalities'] as $group_identifier => $class) {
			$account = Account::get('identifier', $group_identifier);
			if (!$account) {
				$account = Account::instanciate('group');
				$data = ['identifier' => $group_identifier, 'name' => $context->localize($class)];
				if (array_key_exists('archive', $class) && $class['archive']) $data['status'] = 'gone';
				else $data['status'] = 'active';
				$rc = $account->loadAndAdd($data, null, true, true);
				if ($rc[0] == '206') $account = Account::get($rc[1]); // Partially accepted on an already existing account which is returned as rc[1]
				elseif ($rc[0] != '200') $error = $rc;
			}
		}
	}
}
