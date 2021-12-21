<?php
namespace PpitStudies\Controller;

use PpitCore\Model\Account;
use PpitCore\Model\Context;
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

			// Cache the students
			$studentsById = Account::getList('p-pit-studies', ['status' => 'active,retention'], '+id', null, ['id', 'place_id', 'groups', 'property_15']);
			$students = [];
			foreach ($studentsById as $studentId => $student) {
				if ($student->groups) {
					foreach (explode(',', $student->groups) as $groupId) {
						$students[((int) $student->place_id) . '/' . ((int) $groupId)][] = $student;
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

			// Cache the existing per account report links
			$existingLinks = NoteLink::getList(null, ['note_id' => implode(',', $reportIds)], 'id', 'ASC', 'search');
			foreach ($existingLinks as $link) {
				$existingReportsById[$link->note_id]->links[$link->account_id] = $link;
			}

			$subjectConfig = $context->getConfig('student/property/school_subject');
			$referenceValue = $context->getConfig('student/parameter/average_computation')['reference_value'];
			foreach ($requestBody['groups'] as $groupId => $group) {
				if (array_key_exists('subjects', $group)) {
					foreach ($group['subjects'] as $subjectId => $subjectData) {
						foreach ($places as $placeId) {

							// Retrieve the student list by group and place

							if (array_key_exists(((int) $placeId) . '/' . ((int) $groupId), $students)) {
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
							}
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
}
