<?php
namespace PpitStudies\Model;

use PpitContact\Model\UnitaryTarget;
use PpitMasterData\Model\PlaceOfBusiness;
use Zend\db\sql\Where;

class SportStudiesTarget extends UnitaryTarget {

	public $center_id;
	public $sport;
	public $class;
	public $boarding_school;
	public $centers;
	public $result = array();

	public function __construct($message) {
		parent::__construct($message);
		$to = $message->to;
		if (isset($to->center_id)) $this->center_id = $to->center_id;
		if (isset($to->sport)) $this->sport = $to->sport;
		if (isset($to->class)) $this->class = $to->class;
		if (isset($to->boarding_school)) $this->boarding_school = $to->boarding_school;
		$select = PlaceOfBusiness::getTable()->getSelect()->columns(array('id', 'name'));
		$cursor = PlaceOfBusiness::getTable()->selectWith($select);
		$this->centers = array();
		foreach ($cursor as $center) $this->centers[] = $center;
	}

	public function loadData($data) {
		$this->center_id = trim(strip_tags($data['center_id']));
		$this->sport = trim(strip_tags($data['sport']));
		$this->class = trim(strip_tags($data['class']));
		$this->boarding_school = trim(strip_tags($data['boarding_school']));

		$this->to = array();
		if ($this->center_id) $this->to['center_id'] = $this->center_id;
		if ($this->sport) $this->to['sport'] = $this->sport;
		if ($this->class) $this->to['class'] = $this->class;
		if ($this->boarding_school) $this->to['boarding_school'] = $this->boarding_school;
		return 200;
	}

	public function loadDataFromRequest($request) {

		$data = array();
		$data['center_id'] = $request->getPost('center_id');
		$data['sport'] = $request->getPost('sport');
		$data['class'] = $request->getPost('class');
		$data['boarding_school'] = $request->getPost('boarding_school');
		return $this->loadData($data);
	}
	
	public function compute() {
		$select = Contact::getTable()->getSelect()->order(array('nom_famille', 'prenoms'));
		$where = new Where();
		if ($this->center_id) $where->equalTo('centre', $this->center_id);
		if ($this->sport) $where->equalTo('sport', $this->sport);
		if ($this->class) $where->equalTo('classe', $this->class);
		if ($this->boarding_school) $where->equalTo('internat', $this->boarding_school);
		$select->where($where);
		$cursor = Contact::getTable()->selectWith($select);
		$this->to = array();
		foreach ($cursor as $student) {
			$student->ok = $this->addTo($student->tel_cell);
			$this->result[] = $student;
		}
		return $this->to;
	}
}
