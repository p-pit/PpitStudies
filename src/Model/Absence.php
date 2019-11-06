<?php
namespace PpitStudies\Model;

use PpitCore\Model\Account;
use PpitCore\Model\Community;
use PpitCore\Model\Context;
use PpitCore\Model\Generic;
use PpitCore\Model\Vcard;
use Zend\Db\Sql\Where;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Filter\StripTags;

class Absence
{
	public static $model = array(
		'entities' => array(
			'absence' => 			['table' => 'student_absence'],
			'core_account' => 		['table' => 'core_account', 'foreign_key' => 'absence.account_id'],
		),
		'properties' => array(
			'id' => 						['entity' => 'student_absence', 'column' => 'id'],
			'status' => 					['entity' => 'student_absence', 'column' => 'status'],
			'place_id' => 					['entity' => 'student_absence', 'column' => 'place_id'],
			'category' => 					['entity' => 'student_absence', 'column' => 'category'],
			'school_year' => 				['entity' => 'student_absence', 'column' => 'school_year'],
			'school_period' => 				['entity' => 'student_absence', 'column' => 'school_period'],
			'type' => 						['entity' => 'student_absence', 'column' => 'type'],
			'account_id' => 				['entity' => 'student_absence', 'column' => 'account_id'],
			'subject' => 					['entity' => 'student_absence', 'column' => 'subject'],
			'motive' => 					['entity' => 'student_absence', 'column' => 'motive'],
			'begin_date' => 				['entity' => 'student_absence', 'column' => 'begin_date'],
			'end_date' => 					['entity' => 'student_absence', 'column' => 'end_date'],
			'duration' => 					['entity' => 'student_absence', 'column' => 'duration'],
			'observations' => 				['entity' => 'student_absence', 'column' => 'observations'],
			'update_time' => 				['entity' => 'student_absence', 'column' => 'update_time'],
			
			'n_fn' => 						['entity' => 'core_vcard', 'column' => 'n_fn'],
			
			'account_status' => 			['entity' => 'core_account', 'column' => 'status'],
			'account_identifier' => 		['entity' => 'core_account', 'column' => 'identifier'],
			'account_name' => 				['entity' => 'core_account', 'column' => 'name'],
			'account_groups' => 			['entity' => 'core_account', 'column' => 'groups'],
			'account_date_1' => 			['entity' => 'core_account', 'column' => 'date_1'],
			'account_date_2' => 			['entity' => 'core_account', 'column' => 'date_2'],
			'account_date_3' => 			['entity' => 'core_account', 'column' => 'date_3'],
			'account_date_4' => 			['entity' => 'core_account', 'column' => 'date_4'],
			'account_date_5' => 			['entity' => 'core_account', 'column' => 'date_5'],
			'account_property_1' => 		['entity' => 'core_account', 'column' => 'property_1'],
			'account_property_2' => 		['entity' => 'core_account', 'column' => 'property_2'],
			'account_property_3' => 		['entity' => 'core_account', 'column' => 'property_3'],
			'account_property_4' => 		['entity' => 'core_account', 'column' => 'property_4'],
			'account_property_5' => 		['entity' => 'core_account', 'column' => 'property_5'],
			'account_property_6' => 		['entity' => 'core_account', 'column' => 'property_6'],
			'account_property_7' => 		['entity' => 'core_account', 'column' => 'property_7'],
			'account_property_8' => 		['entity' => 'core_account', 'column' => 'property_8'],
			'account_property_9' => 		['entity' => 'core_account', 'column' => 'property_9'],
			'account_property_10' => 		['entity' => 'core_account', 'column' => 'property_10'],
			'account_property_11' => 		['entity' => 'core_account', 'column' => 'property_11'],
			'account_property_12' => 		['entity' => 'core_account', 'column' => 'property_12'],
			'account_property_13' => 		['entity' => 'core_account', 'column' => 'property_13'],
			'account_property_14' => 		['entity' => 'core_account', 'column' => 'property_14'],
			'account_property_15' => 		['entity' => 'core_account', 'column' => 'property_15'],
			'account_property_16' => 		['entity' => 'core_account', 'column' => 'property_16'],
			'account_property_19' => 		['entity' => 'core_account', 'column' => 'property_19'],
		),
	);
	

	/**
	 * Returns a dictionary of each property associated with its description contextual to the current instance config for the given account type.
	 */
	public static function getConfig() 
	{
	
		// Retrieve the context
		$context = Context::getCurrent();
	
		// Retrieve the properties description defined in the current instance config for the given account type
		$description = $context->getConfig('absence');
	
		// Construct the resulting dictionary for each defined property
		$properties = array();
		foreach($description['properties'] as $propertyId => $property) {
			if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
			if ($propertyId == 'account_groups') {
				$property['modalities'] = array();
				foreach (Account::getList('group', [], '+name', null) as $group) $property['modalities'][$group->id] = ['default' => $group->name];
			}
			$properties[$propertyId] = $property;
		}
		
		return $properties;
	}
	
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
    public $email;
    public $sport;
    public $class;
    public $specialty;
    public $photo;

    public $account_status;
    public $account_name;
    public $account_identifier;
    public $account_groups;
    public $account_date_1;
    public $account_date_2;
    public $account_date_3;
    public $account_date_4;
    public $account_date_5;
    public $account_property_1;
    public $account_property_2;
    public $account_property_3;
    public $account_property_4;
    public $account_property_5;
    public $account_property_6;
    public $account_property_7;
    public $account_property_8;
    public $account_property_9;
    public $account_property_10;
    public $account_property_11;
    public $account_property_12;
    public $account_property_13;
    public $account_property_14;
    public $account_property_15;
    public $account_property_16;
    public $account_property_19;
    public $account_property_20;
    
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
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->sport = (isset($data['sport'])) ? $data['sport'] : null;
        $this->class = (isset($data['class'])) ? $data['class'] : null;
        $this->specialty = (isset($data['specialty'])) ? $data['specialty'] : null;
        $this->photo = (isset($data['photo'])) ? $data['photo'] : null;

        $this->account_status = (isset($data['account_status'])) ? $data['account_status'] : null;
        $this->account_name = (isset($data['account_name'])) ? $data['account_name'] : null;
        $this->account_identifier = (isset($data['account_identifier'])) ? $data['account_identifier'] : null;
        $this->account_groups = (isset($data['account_groups'])) ? $data['account_groups'] : null;
        $this->account_property_1 = (isset($data['account_property_1'])) ? $data['account_property_1'] : null;
        $this->account_property_2 = (isset($data['account_property_2'])) ? $data['account_property_2'] : null;
        $this->account_property_3 = (isset($data['account_property_3'])) ? $data['account_property_3'] : null;
        $this->account_property_4 = (isset($data['account_property_4'])) ? $data['account_property_4'] : null;
        $this->account_property_5 = (isset($data['account_property_5'])) ? $data['account_property_5'] : null;
        $this->account_property_6 = (isset($data['account_property_6'])) ? $data['account_property_6'] : null;
        $this->account_property_7 = (isset($data['account_property_7'])) ? $data['account_property_7'] : null;
        $this->account_property_8 = (isset($data['account_property_8'])) ? $data['account_property_8'] : null;
        $this->account_property_9 = (isset($data['account_property_9'])) ? $data['account_property_9'] : null;
        $this->account_property_10 = (isset($data['account_property_10'])) ? $data['account_property_10'] : null;
        $this->account_property_11 = (isset($data['account_property_11'])) ? $data['account_property_11'] : null;
        $this->account_property_12 = (isset($data['account_property_12'])) ? $data['account_property_12'] : null;
        $this->account_property_13 = (isset($data['account_property_13'])) ? $data['account_property_13'] : null;
        $this->account_property_14 = (isset($data['account_property_14'])) ? $data['account_property_14'] : null;
        $this->account_property_15 = (isset($data['account_property_15'])) ? $data['account_property_15'] : null;
        $this->account_property_16 = (isset($data['account_property_16'])) ? $data['account_property_16'] : null;
        $this->account_property_19 = (isset($data['account_property_19'])) ? $data['account_property_19'] : null;
        $this->account_property_20 = (isset($data['account_property_20'])) ? $data['account_property_20'] : null;
    }

    public function getProperties()
    {
    	$data = $this->toArray();
    	$data['n_fn'] = $this->n_fn;
    	$data['email'] = $this->email;
    	$data['property_7'] = $this->class;
    	return $data;
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
    
    public static function getList($type, $params, $major, $dir, $mode = 'todo', $limit = 50)
    {
    	$context = Context::getCurrent();
    	$select = Absence::getTable()->getSelect()
    		->join('core_account', 'student_absence.account_id = core_account.id', array('sport' => 'property_1', 'class' => 'property_7', 'account_groups' => 'groups', 'account_status' => 'status', 'account_name' => 'name', 'account_identifier' => 'identifier', 'place_id', 'account_date_1' => 'date_1', 'account_date_2' => 'date_2', 'account_date_3' => 'date_3', 'account_date_4' => 'date_4', 'account_date_5' => 'date_5', 'account_property_1' => 'property_1', 'account_property_2' => 'property_2', 'account_property_3' => 'property_3', 'account_property_4' => 'property_4', 'account_property_5' => 'property_5', 'account_property_6' => 'property_6', 'account_property_7' => 'property_7', 'account_property_8' => 'property_8', 'account_property_9' => 'property_9', 'account_property_10' => 'property_10', 'account_property_11' => 'property_11', 'account_property_12' => 'property_12', 'account_property_13' => 'property_13', 'account_property_14' => 'property_14', 'account_property_15' => 'property_15', 'account_property_16' => 'property_16', 'account_property_19' => 'property_19', 'account_property_20' => 'property_20'), 'left')
    		->join('core_vcard', 'core_vcard.id = core_account.contact_1_id', array('n_fn', 'email'), 'left')
    		->order(array($major.' '.$dir, 'begin_date', 'subject', 'n_fn'));
		$where = new Where;
		$where->notEqualTo('student_absence.status', 'deleted');
		if ($type) $where->equalTo('student_absence.type', $type);

    	// Todo list vs search modes
    	if ($mode == 'todo') {
//    		$where->greaterThanOrEqualTo('begin_date', $context->getConfig('currentPeriodStart'));
    		$where->greaterThanOrEqualTo('school_year', $context->getConfig('student/property/school_year/default'));
    	}
    	else {

    		// Set the filters
    		foreach ($params as $propertyId => $value) {
				if (in_array(substr($propertyId, 0, 4), array('min_', 'max_'))) $propertyKey = substr($propertyId, 4);
				else $propertyKey = $propertyId;
				$property = Absence::getConfig()[$propertyKey];
				$entity = Absence::$model['properties'][$propertyKey]['entity'];
				$column = Absence::$model['properties'][$propertyKey]['column'];
    			
				if ($propertyId == 'place_id') $where->equalTo('student_absence.place_id', $value);
				elseif ($propertyId == 'n_fn') $where->like('core_vcard.n_fn', '%'.$value.'%');
				elseif (substr($propertyId, 0, 4) == 'min_') $where->greaterThanOrEqualTo(substr($propertyId, 4), $value);
    			elseif (substr($propertyId, 0, 4) == 'max_') $where->lessThanOrEqualTo(substr($propertyId, 4), $value);
				elseif (strpos($value, ',')) $where->in($entity.'.'.$column, array_map('trim', explode(',', $value)));
				elseif ($value == '*') $where->notEqualTo($entity.'.'.$column, '');
				elseif ($property['type'] == 'select') {
					if (array_key_exists('multiple', $property) && $property['multiple']) $where->like($entity.'.'.$column, '%'.$value.'%');
					else $where->equalTo($entity.'.'.$column, $value);
				}
				elseif ($property['type'] == 'multiselect') $where->like($entity.'.'.$column, '%'.$value.'%');
    			else $where->like((($propertyId == 'type') ? 'student_absence.' : '').$propertyId, '%'.$value.'%');
    		}
    	}
		
    	$select->where($where);
		$cursor = Absence::getTable()->selectWith($select);
		$absences = array();
		$currentRoles = $context->getRoles();
		$i = 0;
		foreach ($cursor as $absence) {
			$absence->properties = $absence->getProperties();
			$absence->properties['place_id'] = $absence->place_id;
			$absence->properties['email'] = $absence->email;
			$absence->properties['property_1'] = $absence->sport;
			$absence->properties['property_7'] = $absence->class;
			$absence->properties['school_subject'] = $absence->subject;
			
			$absence->properties['account_status'] = $absence->account_status;
			$absence->properties['account_identifier'] = $absence->account_identifier;
			$absence->properties['account_groups'] = $absence->account_groups;
			$absence->properties['account_date_1'] = $absence->account_date_1;
			$absence->properties['account_date_2'] = $absence->account_date_2;
			$absence->properties['account_date_3'] = $absence->account_date_3;
			$absence->properties['account_date_4'] = $absence->account_date_4;
			$absence->properties['account_date_5'] = $absence->account_date_5;
			$absence->properties['account_property_1'] = $absence->account_property_1;
			$absence->properties['account_property_2'] = $absence->account_property_2;
			$absence->properties['account_property_3'] = $absence->account_property_3;
			$absence->properties['account_property_4'] = $absence->account_property_4;
			$absence->properties['account_property_5'] = $absence->account_property_5;
			$absence->properties['account_property_6'] = $absence->account_property_6;
			$absence->properties['account_property_7'] = $absence->account_property_7;
			$absence->properties['account_property_8'] = $absence->account_property_8;
			$absence->properties['account_property_9'] = $absence->account_property_9;
			$absence->properties['account_property_10'] = $absence->account_property_10;
			$absence->properties['account_property_11'] = $absence->account_property_11;
			$absence->properties['account_property_12'] = $absence->account_property_12;
			$absence->properties['account_property_13'] = $absence->account_property_13;
			$absence->properties['account_property_14'] = $absence->account_property_14;
			$absence->properties['account_property_15'] = $absence->account_property_15;
			$absence->properties['account_property_16'] = $absence->account_property_16;
			$absence->properties['account_property_19'] = $absence->account_property_19;
				
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
					$i++;
					if ($limit && $i > $limit) break;
					$absences[$absence->id] = $absence;
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

    	$absence->account_status = $account->status;
		$absence->account_name = $account->name;
		$absence->account_identifier = $account->identifier;
		$absence->account_groups = $account->groups;
		
		$account->account_date_1 = $account->date_1;
		$account->account_date_2 = $account->date_2;
		$account->account_date_3 = $account->date_3;
		$account->account_date_4 = $account->date_4;
		$account->account_date_5 = $account->date_5;
		$account->account_property_1 = $account->property_1;
		$account->account_property_2 = $account->property_2;
		$account->account_property_3 = $account->property_3;
		$account->account_property_4 = $account->property_4;
		$account->account_property_5 = $account->property_5;
		$account->account_property_6 = $account->property_6;
		$account->account_property_7 = $account->property_7;
		$account->account_property_8 = $account->property_8;
		$account->account_property_9 = $account->property_9;
		$account->account_property_10 = $account->property_10;
		$account->account_property_11 = $account->property_11;
		$account->account_property_12 = $account->property_12;
		$account->account_property_13 = $account->property_13;
		$account->account_property_14 = $account->property_14;
		$account->account_property_15 = $account->property_15;
		$account->account_property_16 = $account->property_16;
		$account->account_property_17 = $account->property_17;
		$account->account_property_18 = $account->property_18;
		$account->account_property_19 = $account->property_19;
		$account->account_property_20 = $account->property_20;
		
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
		    if (strlen($this->school_period) > 255) return 'Integrity';
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

    	$this->properties = $this->getProperties();
    	
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
    	if ($update_time && $absence->update_time > $update_time) return 'Isolation';
    	 
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
    	if ($update_time && $absence->update_time > $update_time) return 'Isolation';

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
    		Absence::$table = $sm->get(AbsenceTable::class);
    	}
    	return Absence::$table;
    }
}