<?php
namespace PpitStudies\Controller;

use PpitCore\Model\Account;
use PpitCore\Model\Context;
use PpitCore\Model\Event;
use PpitCore\Model\Place;
use PpitStudies\Model\Note;
use PpitStudies\Model\NoteLink;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ReportController extends AbstractActionController
{	
	public function getList() {
		$context = Context::getCurrent();
		$where = [];
		$places = Place::getList([]);
		$teacher_id = $this->params()->fromQuery('teacher_id');
		if ($teacher_id) $where['teacher_id'] = $teacher_id;
		$cursor = Note::getList('evaluation', 'report', $where, 'subject', 'ASC', 'search', null);
		$reports = [];
		foreach ($cursor as $report) {
			$reports[] = [
				'id' => $report->id,
				'status' => $report->status,
				'place_id' => $report->place_id,
				'place_caption'=> $places[$report->place_id]->caption,
				'teacher_id' => $report->teacher_id,
				'school_year' => $report->school_year,
				'school_period' => $report->school_period,
				'group_id' => $report->group_id,
				'subject' => $report->subject,
				'date' => $report->date,
				'reference_value' => $report->reference_value,
				'weight' => $report->weight,
				'observations' => $report->observations,	
			];
		}
		return $reports;
	}
	
	public function get($id) {
		$context = Context::getCurrent();
		$report = Note::get($id, 'id', 'report', 'type');
		if (!$report) {
			$this->response->setStatusCode('400');
			$this->response->setReasonPhrase("Report with id $id does not exists");
			return;
		}
		$cursor = NoteLink::getList('report', ['note_id' => $id], 'name', 'ASC', 'search');
		$links = [];
		foreach ($cursor as $link) {
			$links[$link->id] = [
				'status' => $link->status,
				'account_id' => $link->account_id,
				'specific_weight' => $link->specific_weight,
				'value' => $link->value,
				'evaluation' => $link->evaluation,
				'assessment' => $link->assessment,
			];
		}
		$content = [[
			'id' => $report->id,
			'status' => $report->status,
			'place_id' => $report->place_id,
			'teacher_id' => $report->teacher_id,
			'school_year' => $report->school_year,
			'school_period' => $report->school_period,
			'group_id' => $report->group_id,
			'subject' => $report->subject,
			'date' => $report->date,
			'reference_value' => $report->reference_value,
			'weight' => $report->weight,
			'observations' => $report->observations,
			'links' => $links,
		]];
		return $content;
	}
	
    public function postAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
/*		$view = new ViewModel(['context' => $context]);
    	$view->setTerminal(true);*/

		if (!$this->getRequest()->isPost()) {
			$view->statusCode = '400';
			$view->reasonPhrase = 'A POST request is expected';
			return $view;
		}
 
		$requestBody = json_decode($this->getRequest()->getContent(), true);
		$responseBody = ['reportCreated' => [], 'studentLinkCreated' => []];
		

		// Atomically save
		$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
		$connection->beginTransaction();
		try {
			// ajout la condition pas la peine de recuperer les accounts des etudiants on aura pas besoin
			// Cache the students
			if($requestBody['studentsParam'] == true){

				$studentsById = Account::getList('p-pit-studies', ['status' => 'active,retention, suspendu'], '+id', null, ['id', 'place_id', 'groups', 'property_15']);
				$students = [];
			
				foreach ($studentsById as $studentId => $student) {
					if ($student->groups) {
						foreach (explode(',', $student->groups) as $groupId) {
							$students[((int) $student->place_id) . '/' . ((int) $groupId)][] = $student;
						}
					} 
				}
			}

			// Retrieve the existing reports
			$filters = ['school_year' => $requestBody['schoolYear'], 'school_period' => $requestBody['schoolPeriod']];
			$groupIds = [];
			foreach ($requestBody['groups'] as $groupId => $group) {
				$groupIds[] = $groupId;
			}
			$groupIds = implode(',', $groupIds);
			$filters['group_id'] = $groupIds;
			if (array_key_exists('places', $requestBody)) {
				$places = $requestBody['places'];
				$filters['place_id'] = implode(',', $places);
			}
			else $places = [0];

			$existingReportsById = Note::GetList('evaluation', 'report', $filters, 'id', 'ASC', 'search', null);
			$existingReports = [];

		
			$reportIds = [];
			foreach ($existingReportsById as $report) {
				$reportIds[] = $report->id;
				$existingReports[((int) $report->place_id) . '/' . ((int) $report->group_id) . '/' . $report->subject] = $report;
			}

			// echo "affichage de existing reports " ;
			// var_dump($existingReports);
			
	
			// Cache the existing per account report links
			if($requestBody['studentsParam'] == true){

				$existingLinks = NoteLink::getList(null, ['note_id' => implode(',', $reportIds)], 'id', 'ASC', 'search');
				foreach ($existingLinks as $link) {
					$existingReportsById[$link->note_id]->links[$link->account_id] = $link;
				}

				echo "existing Links []" ;
				var_dump($existingLinks);
				echo "==================================" ;

			}
			// echo "affichage des link qui existe deja" ;
         	// var_dump($link);

			$subjectConfig = $context->getConfig('student/property/school_subject');
			$referenceValue = $context->getConfig('student/parameter/average_computation')['reference_value'];
			foreach ($requestBody['groups'] as $groupId => $group) {
				if (array_key_exists('subjects', $group)) {
					foreach ($group['subjects'] as $subjectId => $subjectData) {
						foreach ($places as $placeId) {

							// Retrieve the student list by group and place

							//if (array_key_exists(((int) $placeId) . '/' . ((int) $groupId), $students)) {
								if (array_key_exists(((int) $placeId) . '/' . ((int) $groupId) . '/' . $subjectId, $existingReports)) {
									$report = $existingReports[((int) $placeId) . '/' . ((int) $groupId) . '/' . $subjectId];
								}
								else {
									$report = Note::instanciate('report', null, $groupId);
									$report->status = 'current';
									$report->category = 'evaluation';
									$report->place_id = $placeId;
									$report->school_year = $requestBody['schoolYear'];
									$report->school_period = $requestBody['schoolPeriod'];
									$report->subject = $subjectId;
									$report->reference_value = $referenceValue;
									if (array_key_exists($subjectId, $subjectConfig['modalities'])) $report->weight = $subjectConfig['modalities'][$subjectId]['credits'];
									else $report->weight = 1;
									if (array_key_exists('teacherId', $subjectData)) $report->teacher_id = $subjectData['teacherId'];
									$report->add();
									$report->links = [];
									$responseBody['reportCreated'][] = $report->id;
								}

								// Generate the student links for this report
								//if le param est false (ne genere pas des note link pour les eleves)
								if($requestBody['studentsParam'] == true)
								{
									foreach ($students[((int) $placeId) . '/' . ((int) $groupId)] as $student) {

										if (	$subjectId != 'global'
											&&	array_key_exists('full_time', $subjectConfig['modalities'][$subjectId]) 
											&&  $subjectConfig['modalities'][$subjectId]['full_time']
											&& 	$student->property_15 != 'full_time' ) {

											continue;
										}

										// Ignore the student already having a link for the current report
										if (array_key_exists($student->id, $report->links)) continue;

										$studentLink = NoteLink::instanciate($student->id, $report->id);
										if (	$subjectId != 'global'
											&&	array_key_exists('credits_pt', $subjectConfig['modalities'][$subjectId]) 
											&& 	$student->property_15 != 'full_time' ) {

											$studentLink->specific_weight = $subjectConfig['modalities'][$subjectId]['credits_pt'];
										}

										$studentLink->add();
										$responseBody['studentLinkCreated'][] = $studentLink->id;

									}

										echo "studentLink []" ;
										var_dump($studentLink);
										echo "==================================" ;

								}
							//}
						}
					}
				}
			}
		}

		
		catch (\Exception $e) {
			$connection->rollback();
			$this->response->setStatusCode('500');
			return $this-response;
		}

		$connection->commit();
		$this->response->setStatusCode('200');
		echo json_encode($responseBody);
		return $this->response;

			
    }
	
    public function linkAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
		if ($this->getRequest()->isGet()) {
			$view = new ViewModel(['context' => $context]);
    		$view->setTerminal(true);
			return $view;
		}
 
		$requestBody = json_decode($this->getRequest()->getContent(), true);
		$reportIds = $requestBody['ids'];
		$responseBody = ['studentLinkCreated' => []];
		
		// Atomically save
		$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
		$connection->beginTransaction();
		try {

			$studentsById = Account::getListV3('p-pit-studies', ['id', 'place_id', 'groups', 'property_15'], ['status' => 'active,retention,suspendu,alumni']);
			$students = [];
		
			foreach ($studentsById as $studentId => $student) {
				if ($student['groups']) {
					foreach (explode(',', $student['groups']) as $groupId) {
						$students[((int) $student['place_id']) . '/' . ((int) $groupId)][] = $student;
					}
				} 
			}

			// Retrieve the existing reports
			$filters = ['id' => $reportIds];
			$existingReportsById = Note::GetList('evaluation', 'report', $filters, 'id', 'ASC', 'search', null);
	
			$existingLinks = NoteLink::getList(null, ['note_id' => implode(',', $reportIds)], 'id', 'ASC', 'search');
			foreach ($existingLinks as $link) {
				$existingReportsById[$link->note_id]->links[$link->account_id] = $link;
			}

			$subjectConfig = $context->getConfig('student/property/school_subject');
			$referenceValue = $context->getConfig('student/parameter/average_computation')['reference_value'];
			foreach ($existingReportsById as $report) {

				// Generate the student links for this report
				$subjectId = $report->subject;
				$key = ((int) $report->place_id) . '/' . ((int) $report->group_id);
				if (isset($students[$key])) {
					foreach ($students[$key] as $student) {

						if (	$subjectId != 'global'
							&&	array_key_exists('full_time', $subjectConfig['modalities'][$subjectId]) 
							&&  $subjectConfig['modalities'][$subjectId]['full_time']
							&& 	$student['property_15'] != 'full_time' ) {

							continue;
						}

						// Ignore the student already having a link for the current report
						if (isset($report->links) && array_key_exists($student['id'], $report->links)) continue;

						$studentLink = NoteLink::instanciate($student['id'], $report->id);
						if (	$subjectId != 'global'
							&&	array_key_exists('credits_pt', $subjectConfig['modalities'][$subjectId]) 
							&& 	$student['property_15'] != 'full_time' ) {

							$studentLink->specific_weight = $subjectConfig['modalities'][$subjectId]['credits_pt'];
						}
						$studentLink->add();
						$responseBody['studentLinkCreated'][] = $studentLink->id;
					}
				}
			}
		}
		
		catch (\Exception $e) {
			$connection->rollback();
			$this->response->setStatusCode('500');
			return $this-response;
		}

		$connection->commit();
		$this->response->setStatusCode('200');
		echo json_encode($responseBody);
		return $this->response;
    }
	
    public function fixAction()
    {
    	// Retrieve the context
    	$context = Context::getCurrent();
		if ($this->getRequest()->isGet()) {
			$view = new ViewModel(['context' => $context]);
    		$view->setTerminal(true);
			return $view;
		}
 
		$requestBody = json_decode($this->getRequest()->getContent(), true);
		$reportIds = $requestBody['ids'];
		
		// Atomically save
		$connection = Note::getTable()->getAdapter()->getDriver()->getConnection();
		$connection->beginTransaction();
		try {

			// Retrieve the existing reports
			$filters = ['id' => $reportIds];
			$existingReportsById = Note::GetList('evaluation', 'report', $filters, 'id', 'ASC', 'search', null);

			$accountIds = [];
			$reportComputed = [];
	
			// Create the dictionary of reports by key = account + subject + year + period
			$existingLinks = NoteLink::getList(null, ['note_id' => implode(',', $reportIds)], 'id', 'ASC', 'search');
			foreach ($existingLinks as $link) {
				$weight = ($link->specific_weight) ? $link->specific_weight : $link->weight; 
				$key = $link->account_id . '_' . $link->subject . '_' . $link->school_year . '_' . $link->school_period;
				$reportComputed[$key] = [
					'report' => $existingReportsById[$link->note_id],
					'weight' => $weight,
					'link' => $link,
					'notes' => [],
					'absences' => [],
					'average' => ['sum' => 0, 'referenceValue' => 0],
					'acquisition' => (in_array($link->evaluation, [12, 13, 16])) ? $link->evaluation : null,
				];	
			}

			// Report case : Retrieve the notes to compute the averages and restrict on the selected report scope
			$existingNotes = NoteLink::select('note', ['school_year' => '2022-2023'], 'id', 'ASC');
			$notes = [];
			foreach ($existingNotes as $note) {
				if ($note->evaluation === 'Non évalué') continue;
				$key = $note->account_id . '_' . $note->subject . '_' . $note->school_year . '_' . $note->school_period;

				if (isset($reportComputed[$key])) {
					$report = &$reportComputed[$key];
					$report['notes'][] = $note;
					$report['average']['sum'] += $note->value * $note->weight;
					$report['average']['referenceValue'] += $note->reference_value * $note->weight;
				}
			}

			$absenceById = [];
			$allAbsences = Event::GetList('absence', array('account_id' => implode(",", $accountIds), 'property_1' => $context->getConfig('student/property/school_year/default')), '-begin_date', null);
			foreach ($allAbsences as $absence) {
				$key = $absence->account_id . '_' . $absence->property_3 . '_' . $absence->property_1 . '_' . 'Q1';
				if (isset($reportComputed[$key])) { print_r($absence->id . "\n");
					$reportComputed[$key][$absences][] = $absence;
				}
				$globalKey = $absence->account_id . '_global_' . $absence->property_1 . '_' . 'Q1';
				if (isset($reportComputed[$globalKey])) {
					$reportComputed[$globalKey][$absences][] = $absence;
				}
			}

			// Compute the averages
			foreach ($reportComputed as $key => &$reportLink) {
				if ($reportLink['report']->subject != 'global') {
					if ($reportLink['average']['referenceValue']) {
						$reportLink['link']->value = round($reportLink['average']['sum'] / $reportLink['average']['referenceValue'] * $reportLink['link']->reference_value * 100) / 100;
						$globalKey = $reportLink['link']->account_id . '_global_' . $reportLink['link']->school_year . '_' . $reportLink['link']->school_period;
						if (isset($reportComputed[$globalKey])) {
							$report = $reportComputed[$globalKey];
							$report['notes'][] = $reportLink['report'];
							$report['average']['sum'] += $reportLink['link']->value * $reportLink['report']->weight;
							$report['average']['referenceValue'] += $reportLink['report']->reference_value * $reportLink['report']->weight;
						}
					}
					if (!in_array($reportLink['acquisition'], [12, 13, 16])) {
						if (count($reportLink['absences']) >=3) $reportLink['acquisition'] = 'Défaillant';
						elseif ($reportLink['link']->value <= 1) $reportLink['acquisition'] = "A rattraper";
					}

					$globalKey = $reportLink['link']->account_id . '_global_' . $reportLink['report']->school_year . '_' . $reportLink['report']->school_period;
					if (isset($reportComputed[$globalKey])) {
						$globalReport = &$reportComputed[$globalKey];
						$globalReport['notes'][] = $reportLink['report'];	
						$weight = ($reportLink['link']->specific_weight) ? $reportLink['link']->specific_weight : $reportLink['weight'];
						$globalReport['average']['sum'] += $reportLink['link']->value * $weight;
						$globalReport['average']['referenceValue'] += $reportLink['report']->reference_value * $weight;
					}
				}
			}

			$values = [];
			$acquisitions = [];
			foreach ($reportComputed as $key => &$reportLink) {
				if ($reportLink['report']->subject == 'global') {
					if ($reportLink['average']['referenceValue']) $reportLink['link']->value = round($reportLink['average']['sum'] * $reportLink['report']->reference_value / $reportLink['average']['referenceValue'] * 100) / 100;
				}
				if ($reportLink['average']['referenceValue']) {
					if ($reportLink['link']->value !== null) $values[$reportLink['link']->id] = $reportLink['link']->value;
					if ($reportLink['acquisition'] && !in_array($reportLink['acquisition'], [12, 13, 16])) $acquisitions[$reportLink['link']->id] = $reportLink['acquisition'];
				}
			}
			NoteLink::updateCase('value', $values);
			NoteLink::updateCase('evaluation', $acquisitions);
			$responseBody = ['studentLinkPatched' => [
				'value' => $values,
				'evaluation' => $acquisitions,
			]];
		}
		
		catch (\Exception $e) {
			$connection->rollback();
			print_r($e);
			$this->response->setStatusCode('500');
			return $this->response;
		}

		$connection->commit();
		$this->response->setStatusCode('200');
		echo json_encode($responseBody);
		return $this->response;
    }

	public function v1Action()
	{
		$context = Context::getCurrent();
		if ($this->request->isGet()) $requestType = 'GET';
		elseif ($this->request->isPost()) $requestType = 'POST';
		elseif ($this->request->isDelete()) $requestType = 'DELETE';
		
		// Authentication
		// if (!$context->isAuthenticated() && !$context->wsAuthenticate($this->getEvent())) {
		// 	$this->getResponse()->setStatusCode('401');
		// 	return $this->getResponse();
		// }

		// Get
		if ($requestType == 'GET') {
			$id = $this->params()->fromRoute('id');
			if ($id) $content = $this->get($id);
			else $content = $this->getList();
		}

		elseif ($requestType == 'POST') {
			$result = $this->postAction();
			$this->getResponse()->setStatusCode($result->statusCode);
			$this->getResponse()->setReasonPhrase($result->reasonPhrase);
			$content = $result->responseBody;
		}
		
		header('Content-Type: application/json');
		$this->response->setContent(json_encode($content));
		return $this->response;
	}

	public function getStudentsFromGroupsAction()
	{
		$context = Context::getCurrent();
		$group_id = $this->params()->fromRoute('id');
		$report_id = $this->params()->fromRoute('report_id');
		$subject = $this->params()->fromQuery('subject');

		$coursesConfig = $context->getConfig('student/property/school_subject')['modalities'];
		$courseConfig = $coursesConfig[$subject];

		// Course data
		if (array_key_exists('full_time', $courseConfig) && $courseConfig['full_time'] == true) $full_time = true;
		else $full_time = false;

		// Report searching to check if the report is generate or not.
		$report = Note::get($report_id);

		if ($report->links) {
			foreach ($report->links as $link) {
				$studentIdsFromLink[] = $link->account_id;
			}
			// if report is generated.
			$account_ids = Account::getListV3('p-pit-studies', ['id', 'name', 'status', 'groups', 'property_15'], ['id' => implode(',', $studentIdsFromLink)]);
		} else {

			// if report isn't generated yet.
			$account_ids =  Account::getListV3('p-pit-studies', ['id', 'name', 'status', 'groups', 'property_15'], ['groups' => $group_id]);
		}

		$accounts = [];

		// Filter or not when subject is fulltime or parttime
		foreach ($account_ids as $account) {
			if ($full_time) {
				if ($account['property_15'] !== "full_time") continue;
				else {
					$accounts[] = ['id' => $account['id'], 'status' => $account['status'], 'name' => $account['name']];
				}
			} else {
				$accounts[] = ['id' => $account['id'], 'status' => $account['status'], 'name' => $account['name']];
			}
		}


		$this->response->setStatusCode('200');
		$this->response->setContent(json_encode([$accounts]));
		return $this->response;

	}
}
