<?php
namespace PpitStudies\Model;

use PpitCommitment\Model\Account;
use PpitCore\Model\Community;
use PpitCore\Model\Context;
use PpitCore\Model\Generic;
use PpitCore\Model\Vcard;
use Zend\Db\Sql\Where;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Filter\StripTags;

class Absence implements InputFilterAwareInterface
{
    public $id;
    public $instance_id;
    public $status;
    public $place_id;
    public $category;
    public $school_year;
    public $school_period;
    public $type;
    public $account_id;
    public $subject;
    public $motive;
    public $begin_date;
    public $end_date;
    public $duration;
    public $observations;
    public $audit;
    public $update_time;

    // Joined properties
    public $n_fn;
    public $name; // Deprecated
    public $sport;
    public $class;
    public $specialty;
    public $photo;
    
    // Transient properties
    public $comment;
    public $properties;

    protected $inputFilter;

    // Static fields
    private static $table;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->instance_id = (isset($data['instance_id'])) ? $data['instance_id'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->place_id = (isset($data['place_id'])) ? $data['place_id'] : null;
        $this->category = (isset($data['category'])) ? $data['category'] : null;
        $this->school_year = (isset($data['school_year'])) ? $data['school_year'] : null;
        $this->school_period = (isset($data['school_period'])) ? $data['school_period'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
        $this->account_id = (isset($data['account_id'])) ? $data['account_id'] : null;
        $this->subject = (isset($data['subject'])) ? $data['subject'] : null;
        $this->motive = (isset($data['motive'])) ? $data['motive'] : null;
        $this->begin_date = (isset($data['begin_date'])) ? $data['begin_date'] : null;
        $this->end_date = (isset($data['end_date'])) ? $data['end_date'] : null;
        $this->duration = (isset($data['duration'])) ? $data['duration'] : null;
        $this->observations = (isset($data['observations'])) ? $data['observations'] : null;
        $this->audit = (isset($data['audit'])) ? json_decode($data['audit'], true) : null;
        $this->update_time = (isset($data['update_time'])) ? $data['update_time'] : null;
        
        // Joined properties
        $this->n_fn = (isset($data['n_fn'])) ? $data['n_fn'] : null;
//        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->sport = (isset($data['sport'])) ? $data['sport'] : null;
        $this->class = (isset($data['class'])) ? $data['class'] : null;
        $this->specialty = (isset($data['specialty'])) ? $data['specialty'] : null;
        $this->photo = (isset($data['photo'])) ? $data['photo'] : null;
    }
    
    public function toArray()
    {
    	$data = array();
    	$data['id'] = (int) $this->id;
    	$data['instance_id'] = (int) $this->instance_id;
    	$data['status'] =  ($this->status) ? $this->status : null;
    	$data['place_id'] = (int) $this->place_id;
    	$data['category'] = $this->category;
    	$data['school_year'] = $this->school_year;
    	$data['school_period'] = $this->school_period;
    	$data['type'] = $this->type;
    	$data['account_id'] = (int) $this->account_id;
    	$data['subject'] = $this->subject;
    	$data['motive'] = $this->motive;
    	$data['begin_date'] =  ($this->begin_date) ? $this->begin_date : null;
    	$data['end_date'] =  ($this->end_date) ? $this->end_date : null;
    	$data['duration'] = (int) $this->duration;
    	$data['observations'] =  ($this->observations) ? $this->observations : null;
    	$data['audit'] =  ($this->audit) ? json_encode($this->audit) : null;
		return $data;
    }
    
    public static function getList($type, $params, $major, $dir, $mode = 'todo')
    {
    	$context = Context::getCurrent();
    	
    	$select = Absence::getTable()->getSelect()
    		->join('commitment_account', 'student_absence.account_id = commitment_account.id', array('sport' => 'property_1', 'class' => 'property_7' /*, 'name', 'photo' => 'contact_1_id', 'specialty' => 'property_5'*/), 'left')
    		->join('core_vcard', 'core_vcard.id = commitment_account.contact_1_id', array('n_fn'), 'left')
    		->order(array($major.' '.$dir, 'begin_date', 'subject', 'name'));
		$where = new Where;
		$where->notEqualTo('student_absence.status', 'deleted');
		if ($type) $where->equalTo('student_absence.type', $type);

    	// Todo list vs search modes
    	if ($mode == 'todo') {
    		$where->greaterThanOrEqualTo('begin_date', $context->getConfig('currentPeriodStart'));
    	}
    	else {

    		// Set the filters
    		foreach ($params as $propertyId => $property) {
				if ($propertyId == 'place_id') $where->equalTo('student_absence.place_id', $params[$propertyId]);
				elseif ($propertyId == 'n_fn') $where->like('core_vcard.n_fn', '%'.$params[$propertyId].'%');
				elseif (substr($propertyId, 0, 4) == 'min_') $where->greaterThanOrEqualTo(substr($propertyId, 4), $params[$propertyId]);
    			elseif (substr($propertyId, 0, 4) == 'max_') $where->lessThanOrEqualTo(substr($propertyId, 4), $params[$propertyId]);
    			else $where->like((($propertyId == 'type') ? 'student_absence.' : '').$propertyId, '%'.$params[$propertyId].'%');
    		}
    	}
		
    	$select->where($where);
		$cursor = Absence::getTable()->selectWith($select);
		$absences = array();
		$currentRoles = $context->getRoles();
		foreach ($cursor as $absence) {
			$absence->properties['place_id'] = $absence->place_id;
			$absence->properties['property_1'] = $absence->sport;
			$absence->properties['property_7'] = $absence->class;
			$absence->properties['school_subject'] = $absence->subject;
				
			// Filter on authorized perimeter
			$keep = true;
			if (array_key_exists('p-pit-admin', $context->getPerimeters())) {
				foreach ($context->getPerimeters()['p-pit-admin'] as $key => $values) {
					$keep2 = false;
					foreach ($values as $value) {
						if ($absence->properties[$key] == $value) $keep2 = true;
					}
					if (!$keep2) $keep = false;
				}
			}
			if (array_key_exists('p-pit-studies', $context->getPerimeters())) {
				foreach ($context->getPerimeters()['p-pit-studies'] as $key => $values) {
					$keep2 = false;
					foreach ($values as $value) {
						if ($absence->properties[$key] == $value) $keep2 = true;
					}
					if (!$keep2) $keep = false;
				}
			}

			if ($keep) {
/*				if (array_key_exists('manager', $currentRoles)
				||	$absence->type == 'schooling' && array_key_exists('teacher', $currentRoles)
				||	$absence->type == 'sport' && array_key_exists('coach', $currentRoles)
				||	$absence->type == 'boarding_school' && array_key_exists('boarding_school_headmaster', $currentRoles))
				{*/
					$absence->properties = $absence->toArray();
					$absences[] = $absence;
//				}
			}
		}
		return $absences;
    }
/*
    public static function retrieveAll($type, $account_id)
    {
    	$select = Absence::getTable()->getSelect()
    		->order(array('begin_date DESC', 'subject ASC'));
    	$where = new Where;
    	$where->notEqualTo('status', 'deleted');
    	$where->equalTo('account_id', $account_id);
    	if ($type) $where->equalTo('type', $type);
    	$select->where($where);
    	$cursor = Absence::getTable()->selectWith($select);
    	$absences = array();
    	foreach ($cursor as $absence) $absences[] = $absence;
    	return $absences;
    }*/
    
    public static function get($id, $column = 'id')
    {
    	$absence = Absence::getTable()->get($id, $column);
    	$account = Account::get($absence->account_id);
    	$contact = Vcard::get($account->contact_1_id);
//    	$absence->name = $account->name;
    	$absence->n_fn = $contact->n_fn;
    	return $absence;
    }
    
    public static function instanciate($type = null)
    {
		$absence = new Absence;
		$absence->status = 'new';
		$absence->type = $type;
		$absence->audit = array();
		return $absence;
    }

    public function loadData($data) {
    
    	$context = Context::getCurrent();

        if (array_key_exists('status', $data)) {
		    $this->status = trim(strip_tags($data['status']));
		    if (strlen($this->status) > 255) return 'Integrity';
		}
        if (array_key_exists('place_id', $data)) {
		    $this->place_id = (int) $data['place_id'];
		    if (!$this->place_id) return 'Integrity';
		}
		if (array_key_exists('category', $data)) {
		    $this->category = trim(strip_tags($data['category']));
		    if (!$this->category || strlen($this->category) > 255) return 'Integrity';
		}
    	if (array_key_exists('school_year', $data)) {
	    	$this->school_year = trim(strip_tags($data['school_year']));
		    if (!$this->school_year || strlen($this->school_year) > 255) return 'Integrity';
		}
        if (array_key_exists('school_period', $data)) {
	    	$this->school_period = trim(strip_tags($data['school_period']));
		    if (!$this->school_period || strlen($this->school_period) > 255) return 'Integrity';
		}
		if (array_key_exists('type', $data)) {
		    $this->type = trim(strip_tags($data['type']));
		    if (!$this->type || strlen($this->type) > 255) return 'Integrity';
		}
    	if (array_key_exists('account_id', $data)) {
		    $this->account_id = (int) $data['account_id'];
		    if (!$this->account_id) return 'Integrity';
		}
    	if (array_key_exists('subject', $data)) {
		    $this->subject = trim(strip_tags($data['subject']));
		    if (strlen($this->subject) > 255) return 'Integrity';
		}
        if (array_key_exists('motive', $data)) {
		    $this->motive = trim(strip_tags($data['motive']));
		    if (strlen($this->motive) > 255) return 'Integrity';
		}
		if (array_key_exists('begin_date', $data)) {
	    	$this->begin_date = trim(strip_tags($data['begin_date']));
	    	if (!$this->begin_date || !checkdate(substr($this->begin_date, 5, 2), substr($this->begin_date, 8, 2), substr($this->begin_date, 0, 4))) return 'Integrity';
		}
    	if (array_key_exists('end_date', $data)) {
	    	$this->end_date = trim(strip_tags($data['end_date']));
	    	if ($this->end_date && !checkdate(substr($this->end_date, 5, 2), substr($this->end_date, 8, 2), substr($this->end_date, 0, 4))) return 'Integrity';
		}
		if (array_key_exists('duration', $data)) $this->duration = (int) $data['duration'];
		if (array_key_exists('observations', $data)) {
		    $this->observations = trim(strip_tags($data['observations']));
		    if (strlen($this->observations) > 2047) return 'Integrity';
		}
		if (array_key_exists('comment', $data)) {
		    $this->comment = trim(strip_tags($data['comment']));
		    if (strlen($this->comment) > 2047) return 'Integrity';
		}
		if (array_key_exists('update_time', $data)) $this->update_time = $data['update_time'];

    	$this->properties = $this->toArray();
    	
    	// Update the audit
    	$this->audit[] = array(
    			'time' => Date('Y-m-d G:i:s'),
    			'n_fn' => $context->getFormatedName(),
    			'status' => $this->status,
    			'comment' => $this->comment,
    	);

    	return 'OK';
    }

    public function add()
    {
    	$context = Context::getCurrent();

    	$this->id = null;
    	$this->status = 'new';
    	Absence::getTable()->save($this);
    
    	return ('OK');
    }
    
    public function update($update_time)
    {
    	$context = Context::getCurrent();
    	$absence = Absence::get($this->id);

    	// Isolation check
    	if ($absence->update_time > $update_time) return 'Isolation';
    	 
    	Absence::getTable()->save($this);
    
    	return 'OK';
    }

    public function isDeletable()
    {
    	$context = Context::getCurrent();
    
    	// Check dependencies
    	$config = $context->getConfig();
    	foreach($config['ppitStudiesDependencies'] as $dependency) {
    		if ($dependency->isUsed($this)) return false;
    	}

    	return true;
    }
    
    public function delete($update_time)
    {
    	$context = Context::getCurrent();
    	$absence = Absence::get($this->id);
    
    	// Isolation check
    	if ($absence->update_time > $update_time) return 'Isolation';

    	$this->status = 'deleted';
    	Absence::getTable()->save($this);
    
    	return 'OK';
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        throw new \Exception("Not used");
    }

    public static function getTable()
    {
    	if (!Absence::$table) {
    		$sm = Context::getCurrent()->getServiceManager();
    		Absence::$table = $sm->get('PpitStudies\Model\AbsenceTable');
    	}
    	return Absence::$table;
    }
}