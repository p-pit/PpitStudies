<?php
namespace PpitStudies\Model;

use PpitCore\Model\Account;
use PpitCore\Model\Context;
use PpitCore\Model\Generic;
use PpitCore\Model\Place;
use Zend\Db\Sql\Where;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Filter\StripTags;

class NoteLink
{
	public static $model = [
		'entities' => [
			'student_note_link' => 	['table' => 'student_note_link'],
			'student_note' => 		['table' => 'student_note', 'foreign_key' => 'student_note_link.note_id'],
			'core_account' => 		['table' => 'core_account', 'foreign_key' => 'student_note_link.account_id'],
			'core_place' => 		['table' => 'core_place', 'foreign_key' => 'core_account.place_id'],
			'core_vcard' => 		['table' => 'core_vcard', 'foreign_key' => 'core_account.contact_1_id'],
		],
		'properties' => [
			'id' => 				['entity' => 'student_note_link', 'column' => 'id'],
			'status' => 			['entity' => 'student_note_link', 'column' => 'status'],
			'account_id' => 		['entity' => 'student_note_link', 'column' => 'account_id'],
			'note_id' => 			['entity' => 'student_note_link', 'column' => 'note_id'],
			'value' => 				['entity' => 'student_note_link', 'column' => 'value'],
			'distribution' => 		['entity' => 'student_note_link', 'column' => 'distribution'],
			'evaluation' => 		['entity' => 'student_note_link', 'column' => 'evaluation'],
			'assessment' => 		['entity' => 'student_note_link', 'column' => 'assessment'],
			'class' => 				['entity' => 'student_note_link', 'column' => 'class'],
			'distribution' => 		['entity' => 'student_note_link', 'column' => 'distribution'],
			'update_time' => 		['entity' => 'student_note_link', 'column' => 'update_time'],
			'place_id' => 			['entity' => 'core_account', 'column' => 'place_id'],
			'place_caption' => 		['entity' => 'core_place', 'column' => 'caption'],
			'n_fn' => 				['entity' => 'core_vcard', 'column' => 'n_fn'],
			'name' => 				['entity' => 'core_account', 'column' => 'name'],
			'account_property_15' =>['entity' => 'core_account', 'column' => 'property_15'],
			'note_status' => 		['entity' => 'student_note', 'column' => 'status'],
			'category' => 			['entity' => 'student_note', 'column' => 'category'],
			'type' => 				['entity' => 'student_note', 'column' => 'type'],
			'school_year' => 		['entity' => 'student_note', 'column' => 'school_year'],
			'level' => 				['entity' => 'student_note', 'column' => 'level'],
//			'class' => 				['entity' => 'student_note', 'column' => 'class'], // Deprecated
			'class' => 				['entity' => 'student_note_link', 'column' => 'class'], // Deprecated
			'group_id' => 			['entity' => 'student_note', 'column' => 'group_id'],
			'school_period' => 		['entity' => 'student_note', 'column' => 'school_period'],
			'subject' => 			['entity' => 'student_note', 'column' => 'subject'],
			'teacher_id' => 		['entity' => 'student_note', 'column' => 'teacher_id'],
			'date' => 				['entity' => 'student_note', 'column' => 'date'],
			'target_date' => 		['entity' => 'student_note', 'column' => 'target_date'],
			'reference_value' => 	['entity' => 'student_note', 'column' => 'reference_value'],
			'weight' => 			['entity' => 'student_note', 'column' => 'weight'],
			'observations' => 		['entity' => 'student_note', 'column' => 'observations'],
			'lower_note' => 		['entity' => 'student_note', 'column' => 'lower_note'],
			'higher_note' => 		['entity' => 'student_note', 'column' => 'higher_note'],
			'average_note' => 		['entity' => 'student_note', 'column' => 'average_note'],
		],
	];

	/**
	 * Returns a dictionary of each property associated with its description contextual to the current instance config.
	 */
	public static function getConfig() {
	
		// Retrieve the context
		$context = Context::getCurrent();
	
		// If no description is found for the given type retrieve the properties description for the generic type
		$description = $context->getConfig('note_link/generic');
	
		// Construct the resulting dictionary for each defined property
		$properties = array();
		foreach($description['properties'] as $propertyId) {

			// Retrieve the property description according to the given type, defaulting to the generic type
			$property = $context->getConfig('note_link/generic/property/'.$propertyId);
	
			// Overwrite the description with the referred description for non-inline property definition
			$propertyType = (array_key_exists('type', $property)) ? $property['type'] : null;
			if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
			if ($propertyType) $property['type'] = $propertyType;
	
			if (!array_key_exists('private', $property)) $property['private'] = false;
				
			// Cache the place list retrieved from the database for the current instance in the place_id property description
			if ($propertyId == 'place_id') {
				$property['modalities'] = [];
				foreach (Place::getList(array()) as $place) $property['modalities'][$place->id] = $place->caption;
			}

			// Cache the accounts retrieved from the database for the current instance in the account_id property description
			elseif ($propertyId == 'account_id') {
				$property['modalities'] = [];
				foreach (Account::getList('p-pit-studies', ['status' => 'active,retention'], '+name', null) as $account) $property['modalities'][$account->id] = ['default' => $account->n_fn];
			}
				
			// Cache the groups retrieved from the database for the current instance in the group_id property description
			elseif ($propertyId == 'group_id') {
				$property['modalities'] = [];
				foreach (Account::getList('group', ['status' => 'active'], '+identifier', null) as $group) $property['modalities'][$group->id] = ['default' => $group->name . (($group->place_caption) ? ' (' . $group->place_caption . ')' : '')];
			}

			// Cache the teachers retrieved from the database for the current instance in the group_id property description
			elseif ($propertyId == 'teacher_id') {
				$property['modalities'] = [];
				foreach (Account::getList('teacher', ['status' => 'active,reconnect_with,contrat_envoye'], '+name', null) as $teacher) $property['modalities'][$teacher->contact_1_id] = ['default' => $teacher->name];
			}
				
			// Cache the referred modalities definition for modalities not defined inline
			elseif (in_array($property['type'], ['select', 'multiselect']) && array_key_exists('definition', $property['modalities']) && $property['modalities']['definition'] != 'inline') {
				$definition = $context->getConfig($property['modalities']['definition']);
				$property['modalities'] = [];
				foreach ($definition as $modalityId => $modality) {
					$property['modalities'][$modalityId] = $modality['labels'];
				}
			}
	
			$properties[$propertyId] = $property;
		}
	
		// Return the dictionary
		return $properties;
	}

	public static function getConfigSearch($properties) {
	
		// Retrieve the context
		$context = Context::getCurrent();
	
		// If no description is found for the given type retrieve the properties description for the generic type
		$configSearch = $context->getConfig('note_link/search/generic');
	
		// Construct the resulting dictionary for each defined property
		$searchProperties = [];
		foreach($configSearch as $propertyId => $options) $searchProperties[$propertyId] = $properties[$propertyId];
	
		// Return the dictionary
		return $searchProperties;
	}
	
	public static function getConfigList($properties) {
	
		// Retrieve the context
		$context = Context::getCurrent();
	
		// If no description is found for the given type retrieve the properties description for the generic type
		$configList = $context->getConfig('note_link/list/generic');
	
		// Construct the resulting dictionary for each defined property
		$listProperties = [];
		foreach($configList as $propertyId => $options) $listProperties[$propertyId] = $properties[$propertyId];
	
		// Return the dictionary
		return $listProperties;
	}

	public static function getConfigStudentList($properties) {
	
		// Retrieve the context
		$context = Context::getCurrent();
	
		// If no description is found for the given type retrieve the properties description for the generic type
		$configStudentList = $context->getConfig('note_link/student_list/generic');
	
		// Construct the resulting dictionary for each defined property
		$studentListProperties = [];
		foreach($configStudentList as $propertyId => $options) $studentListProperties[$propertyId] = $properties[$propertyId];
	
		// Return the dictionary
		return $studentListProperties;
	}

	public static function getConfigGroup($properties) {
	
		// Retrieve the context
		$context = Context::getCurrent();
	
		// If no description is found for the given type retrieve the properties description for the generic type
		$configGroup = $context->getConfig('note_link/group/generic');
	
		// Construct the resulting dictionary for each defined property
		$listProperties = [];
		foreach($configGroup as $propertyId => $options) $groupProperties[$propertyId] = $properties[$propertyId];
	
		// Return the dictionary
		return $groupProperties;
	}
	
	// For compatibility
	
	public $id;
    public $instance_id;
    public $status;
    public $account_id;
    public $note_id;
    public $value;
    public $distribution;
    public $evaluation;
    public $assessment;
    public $class;
    public $audit;
    public $update_time;

    // Joined properties
    public $place_id;
    public $place_caption;
    public $n_fn;
    public $user_n_fn; // Deprecated
    public $name;
    public $account_property_15;
    public $account_class; 
    public $note_status;
    public $category;
    public $type;
    public $school_year;
    public $level;
    public $group_id;
    public $school_period;
    public $subject;
    public $teacher_id;
    public $date;
    public $target_date;
    public $reference_value;
    public $weight;
    public $observations;
    public $document;
    public $criteria; // Deprecated
    public $lower_note;
    public $higher_note;
    public $average_note;
    
    // Transient
    public $creation_user;
    
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
        $this->account_id = (isset($data['account_id'])) ? $data['account_id'] : null;
        $this->note_id = (isset($data['note_id'])) ? $data['note_id'] : null;
        $this->value = (isset($data['value'])) ? $data['value'] : null;
        $this->distribution = (isset($data['distribution'])) ? json_decode($data['distribution'], true) : array();
        $this->evaluation = (isset($data['evaluation'])) ? $data['evaluation'] : null;
        $this->assessment = (isset($data['assessment'])) ? $data['assessment'] : null;
        $this->class = (isset($data['class'])) ? $data['class'] : null;
        $this->audit = (isset($data['audit'])) ? json_decode($data['audit'], true) : null;
        
        // Joined properties
        $this->place_id = (isset($data['place_id'])) ? $data['place_id'] : null;
        $this->place_caption = (isset($data['place_caption'])) ? $data['place_caption'] : null;
        $this->n_fn = (isset($data['n_fn'])) ? $data['n_fn'] : null;
        $this->user_n_fn = (isset($data['user_n_fn'])) ? $data['user_n_fn'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null; // Deprecated
        $this->account_property_15 = (isset($data['account_property_15'])) ? $data['account_property_15'] : null; // Deprecated
        $this->account_class = (isset($data['account_class'])) ? $data['account_class'] : null;
        $this->note_status = (isset($data['note_status'])) ? $data['note_status'] : null;
        $this->category = (isset($data['category'])) ? $data['category'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
        $this->school_year = (isset($data['school_year'])) ? $data['school_year'] : null;
        $this->level = (isset($data['level'])) ? $data['level'] : null;
        $this->group_id = (isset($data['group_id'])) ? $data['group_id'] : null;
        $this->school_period = (isset($data['school_period'])) ? $data['school_period'] : null;
        $this->subject = (isset($data['subject'])) ? $data['subject'] : null;
        $this->teacher_id = (isset($data['teacher_id'])) ? $data['teacher_id'] : null;
        $this->date = (isset($data['date'])) ? $data['date'] : null;
        $this->target_date = (isset($data['target_date'])) ? $data['target_date'] : null;
        $this->reference_value = (isset($data['reference_value'])) ? $data['reference_value'] : null;
        $this->weight = (isset($data['weight'])) ? $data['weight'] : null;
        $this->observations = (isset($data['observations'])) ? $data['observations'] : null;
        $this->document = (isset($data['document'])) ? $data['document'] : null;
        $this->criteria = (isset($data['criteria'])) ? json_decode($data['criteria'], true) : null;
        $this->average_note = (isset($data['average_note'])) ? $data['average_note'] : null;
        $this->lower_note = (isset($data['lower_note'])) ? $data['lower_note'] : null;
        $this->higher_note = (isset($data['higher_note'])) ? $data['higher_note'] : null;

        $this->creation_user = (isset($data['creation_user'])) ? $data['creation_user'] : null;
    }

    public function getProperties()
    {
    	$data = array();
    	$data['id'] = (int) $this->id;
    	$data['instance_id'] = (int) $this->instance_id;
    	$data['status'] = $this->status;
    	$data['account_id'] = (int) $this->account_id;
    	$data['note_id'] = (int) $this->note_id;
    	$data['value'] = $this->value; // Nullable
    	$data['distribution'] = $this->distribution;
    	$data['evaluation'] = $this->evaluation;
    	$data['assessment'] = $this->assessment;
    	$data['class'] =  $this->class;
    	$data['audit'] =  $this->audit;

    	$data['place_id'] = (int) $this->place_id;
    	$data['place_caption'] =  $this->place_caption;
    	$data['n_fn'] =  $this->n_fn;
    	$data['user_n_fn'] =  $this->user_n_fn;
    	$data['name'] =  $this->name;
    	$data['account_property_15'] =  $this->account_property_15;
    	$data['account_class'] =  $this->account_class;
    	$data['note_status'] =  $this->note_status;
    	$data['category'] =  $this->category;
    	$data['type'] =  $this->type;
    	$data['school_year'] =  $this->school_year;
    	$data['level'] =  $this->level;
    	$data['group_id'] =  $this->group_id;
    	$data['school_period'] =  $this->school_period;
    	$data['subject'] =  $this->subject;
    	$data['teacher_id'] =  $this->teacher_id;
    	$data['date'] =  $this->date;
    	$data['target_date'] =  $this->target_date;
    	$data['reference_value'] =  $this->reference_value;
    	$data['weight'] =  $this->weight;
    	$data['observations'] =  $this->observations;
    	$data['document'] =  $this->document;
    	$data['criteria'] =  $this->criteria;
    	$data['lower_note'] =  $this->lower_note;
    	$data['higher_note'] =  $this->higher_note;
    	$data['average_note'] =  $this->average_note;

    	return $data;
    }
    
    public function toArray()
    {
    	$data = $this->getProperties();
    	$data['distribution'] =  json_encode($this->distribution);
    	$data['audit'] =  ($this->audit) ? json_encode($this->audit) : null;
    	unset($data['place_id']);
    	unset($data['place_caption']);
    	unset($data['n_fn']);
    	unset($data['user_n_fn']);
    	unset($data['name']);
    	unset($data['account_property_15']);
    	unset($data['account_class']);
    	unset($data['note_status']);
    	unset($data['category']);
    	unset($data['type']);
    	unset($data['school_year']);
    	unset($data['level']);
    	unset($data['group_id']);
//    	unset($data['class']); // Deprecated
    	unset($data['school_period']);
    	unset($data['subject']);
    	unset($data['teacher_id']);
    	unset($data['date']);
    	unset($data['target_date']);
    	unset($data['reference_value']);
    	unset($data['weight']);
    	unset($data['observations']);
    	unset($data['document']);
    	unset($data['criteria']);
    	unset($data['lower_note']);
    	unset($data['higher_note']);
    	unset($data['average_note']);
    	return $data;
    }
    
    public static function getList($type, $params, $major, $dir, $mode = 'todo', $limit = null)
    {
    	$context = Context::getCurrent();
    	$select = NoteLink::getTable()->getSelect()
    		->order(array($major.' '.$dir, 'name ASC', 'type ASC'))
    		->join('student_note', 'student_note_link.note_id = student_note.id', array('place_id', 'note_status' => 'status', 'type', 'category', 'school_year', 'level', 'group_id'/*, 'class'*/, 'school_period', 'subject', 'teacher_id', 'date', 'target_date', 'reference_value', 'weight', 'observations', 'document', 'criteria', 'average_note', 'lower_note', 'higher_note'), 'left')
    		->join('core_place', 'student_note.place_id = core_place.id', array('place_caption' => 'caption'), 'left')
    		->join('core_account', 'student_note_link.account_id = core_account.id', array('name', 'account_property_15' => 'property_15', 'account_class' => 'property_7'), 'left')
    		->join('core_vcard', 'student_note.teacher_id = core_vcard.id', array('n_fn'), 'left');
    	$where = new Where;
    	$where->notEqualTo('student_note_link.status', 'deleted');
    	if ($type) $where->equalTo('student_note.type', $type);

    	// Todo list vs search modes
    	if ($mode == 'todo') {
    	}
    	else {
    		// Set the filters
    		foreach ($params as $propertyId => $property) {
    			if (in_array(substr($propertyId, 0, 4), array('min_', 'max_'))) $propertyKey = substr($propertyId, 4);
    			else $propertyKey = $propertyId;
    			$entity = NoteLink::$model['properties'][$propertyKey]['entity'];
    			$column = NoteLink::$model['properties'][$propertyKey]['column'];
    			 
    			if ($propertyId == 'school_year') $where->equalTo('student_note.school_year', $params[$propertyId]);
    			elseif ($propertyId == 'group_id') $where->in('student_note.group_id', explode(',', $params[$propertyId]));
    			elseif ($propertyId == 'place_id') $where->equalTo('student_note.place_id', $params[$propertyId]);
    			elseif ($propertyId == 'account_id') $where->equalTo('account_id', $params[$propertyId]);
    			elseif ($propertyId == 'account_property_15') $where->equalTo('core_account.property_15', $params[$propertyId]);
    			elseif ($propertyId == 'school_period') $where->equalTo('student_note.school_period', $params[$propertyId]);
    			elseif ($propertyId == 'subject') $where->equalTo('student_note.subject', $params[$propertyId]);
    			elseif ($propertyId == 'level') $where->like('student_note.level', '%'.$params[$propertyId].'%');
				elseif (strpos($params[$propertyId], ',')) $where->in($entity . '.' . $column, array_map('trim', explode(',', $params[$propertyId])));
    			elseif (substr($propertyId, 0, 4) == 'min_') $where->greaterThanOrEqualTo($entity . '.' . $column, $params[$propertyId]);
    			elseif (substr($propertyId, 0, 4) == 'max_') $where->lessThanOrEqualTo($entity . '.' . $column, $params[$propertyId]);
    			else $where->like($entity . '.' . $column, '%'.$params[$propertyId].'%');
    		}
    	}
		
    	$select->where($where);

    	// Set the limit or no-limit
    	if ($limit) $select->limit((int)$limit);

    	$cursor = NoteLink::getTable()->selectWith($select);
		$noteLinks = array();
		foreach ($cursor as $noteLink) {
			if ($noteLink->note_status != 'deleted') $noteLinks[$noteLink->id] = $noteLink;
		}
		return $noteLinks;
    }

    public static function get($id, $column = 'id')
    {
    	$noteLink = NoteLink::getTable()->get($id, $column);
    	$note = Note::get($noteLink->note_id);
    	$account = Account::get($noteLink->account_id);
    	$noteLink->status = $note->status;
    	$noteLink->category = $note->category;
    	$noteLink->type = $note->type;
    	$noteLink->school_year = $note->school_year;
    	$noteLink->level = $note->level;
//    	$noteLink->class = $note->class;
    	$noteLink->school_period = $note->school_period;
    	$noteLink->subject = $note->subject;
    	$noteLink->n_fn = $account->n_fn;
    	$noteLink->teacher_id = $note->teacher_id;
    	$noteLink->date = $note->date;
    	$noteLink->target_date = $note->target_date;
    	$noteLink->reference_value = $note->reference_value;
    	$noteLink->weight = $note->weight;
    	$noteLink->observations = $note->observations;
    	$noteLink->criteria = $note->criteria;
    	$noteLink->average_note = $note->average_note;
    	$noteLink->lower_note = $note->lower_note;
    	$noteLink->higher_note = $note->higher_note;
    	return $noteLink;
    }

    public function computeStudentAverage($school_year, $period)
    {
    	$context = Context::getCurrent();
    	$select = NoteLink::getTable()->getSelect()
	    	->join('student_note', 'student_note_link.note_id = student_note.id', array('note_status' => 'status', 'type', 'school_year', 'level'/*, 'class'*/, 'school_period', 'subject', 'date', 'target_date', 'reference_value', 'weight', 'observations', 'criteria', 'average_note', 'lower_note', 'higher_note'), 'left')
	    	->order(array('date DESC', 'subject ASC'));
    	$where = new Where;
    	$where->notEqualTo('student_note.status', 'deleted');
    	$where->notEqualTo('student_note_link.status', 'deleted');
    	$where->equalTo('category', 'evaluation');
    	$where->equalTo('type', 'report');
    	$where->notEqualTo('subject', 'global');
    	$where->equalTo('account_id', $this->account_id);
    	$where->equalTo('school_year', $school_year);
    	$where->equalTo('school_period', $period);
    	$select->where($where);
    	$cursor = NoteLink::getTable()->selectWith($select);
    	$periodAverages = array();
    	foreach ($cursor as $noteLink) {
    		if ($noteLink->value !== null) {
    			$periodAverages[] = array('id' => $noteLink->id, 'reference_value' => ($noteLink->reference_value) ? $noteLink->reference_value : 20, 'weight' => $noteLink->weight, 'note' => $noteLink->value);
    		}
    	}
    	$globalAverage = 0;
    	$globalWeight = 0;
    	$this->audit = $periodAverages;
    	foreach ($periodAverages as $average) {
    		$globalAverage += $average['note'] * $context->getConfig('student/parameter/average_computation')['reference_value'] / $average['reference_value'] * $average['weight'];
    		$globalWeight += $average['weight'];
    	}
    	if ($globalWeight != 0) $globalAverage /= $globalWeight;
    	return $globalAverage;
    }
    
    public static function instanciate($account_id = null, $note_id = null)
    {
		$noteLink = new NoteLink;
		$noteLink->status = 'new';
		$noteLink->account_id = $account_id;
		$noteLink->note_id = $note_id;
		$noteLink->distribution = array();
		$noteLink->audit = array();
		$noteLink->properties = $noteLink->getProperties();
		return $noteLink;
    }

	public static function validate($data)
	{
		$context = Context::getCurrent();
		$result = [];
		$errors = [];

		$configProperties = array();
		foreach($context->getConfig('note_link/generic')['properties'] as $propertyId) {
			$property = $context->getConfig('note_link/generic/property/'.$propertyId);
			$propertyType = (array_key_exists('type', $property)) ? $property['type'] : null;
			if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
			if ($propertyType) $property['type'] = $propertyType;
			if (!array_key_exists('private', $property)) $property['private'] = false;
			$configProperties[$propertyId] = $property;
		}
		
		// Iterates on the given pairs of property => value
		foreach ($data as $propertyId => $value) {
	
			// Check that this property is managed
			if (array_key_exists($propertyId, $configProperties)) {
	
				// Retrieve the property description for the given type
				$property = $configProperties[$propertyId];
	
				// Suppress white spaces and tags in the string values
				if (in_array($property['type'], ['array', 'key_value', 'structure', 'number'])) $value = $data[$propertyId];
				else $value = trim(strip_tags($data[$propertyId]));
	
				// Check for maximum sizes
				if (in_array($property['type'], ['input', 'select', 'multiselect'])) {
					if (strlen($value) > 255) $errors[$propertyId] = "$propertyId should not be longer than 255 characters";
				}
				elseif (in_array($property['type'], ['textarea', 'log'])) {
					$maxLength = (array_key_exists('max_length', $property)) ? $property['max_length'] : 32767;
					if (strlen($value) > $maxLength) $errors[$propertyId] = "$propertyId should not be longer than $maxLength characters";
				}
	
				// Check for date validity
				elseif (in_array($property['type'], ['date'])) {
					if ($value && (strlen($value) < 10 || !checkdate(substr($value, 5, 2), substr($value, 8, 2), substr($value, 0, 4)))) $errors[$propertyId] = "$propertyId should be a valid date according to the format yyyy-mm-dd";
				}
	
				// Check for time validity
				elseif (in_array($property['type'], ['time'])) {
					if ($value && !Account::checktime($value)) $errors[$propertyId] = "$propertyId should be a valid time according to the format hh:mm:ss";
				}
				elseif (in_array($property['type'], ['datetime'])) {
					if ($value && (!checkdate(substr($value, 5, 2), substr($value, 8, 2), substr($value, 0, 4)) || !Account::checktime(substr($value, 11, 8)))) $errors[$propertyId] = "$propertyId should be a valid date & time according to the format yyyy-mm-dd hh:mm:ss";
				}
	
				elseif ($property['type'] == 'id') $value = (int) $value;
	
				// Cast number to int or float depending on the precision defined for this property
				elseif ($property['type'] == 'number') {
					if ($value !== null) {
						$value = preg_replace('/,/', '.', $value);
						if (array_key_exists('precision', $property) && $property['precision'] > 0) $value = (float) $value;
						else $value = (int) $value;
					}
				}
	
				// Private data protection
				if ($property['private'] && $value) {
					$value = $context->getSecurityAgent()->protectPrivateDataV2($value);
				}
	
				$result[$propertyId] = $value;
			}
		}
	
		// Return either the errors or the resulting data if no error
		return ($errors) ? ['errors' => $errors] : ['data' => $result];
	}
	
    public function loadData($data)
    {
    	$context = Context::getCurrent();
    	$errors = [];
    
    	$auditRow = [
    		'time' => Date('Y-m-d G:i:s'),
    		'n_fn' => $context->getFormatedName(),
    	];
    	
    	$configProperties = array();
    	foreach($context->getConfig('note_link/generic')['properties'] as $propertyId) {
    		$property = $context->getConfig('note_link/generic/property/'.$propertyId);
    		$propertyType = (array_key_exists('type', $property)) ? $property['type'] : null;
    		if ($property['definition'] != 'inline') $property = $context->getConfig($property['definition']);
    		if ($propertyType) $property['type'] = $propertyType;
    		$configProperties[$propertyId] = $property;
    	}
    
    	$validation = NoteLink::validate($data);
    	foreach ($validation as $resultType => $result) {
    		if ($resultType == 'errors') return 'Integrity';
    		if ($resultType == 'data') $data = $result;
    	}
    
    	foreach ($data as $propertyId => $value) {
    
    		// Retrieve the property description for the given type
    		$property = $configProperties[$propertyId];
    
    		if ($propertyId == 'status') $this->status = $value;
    		elseif ($propertyId == 'account_id') $this->account_id = $value;
    		elseif ($propertyId == 'note_id') $this->note_id = $value;
    		elseif ($propertyId == 'value') $this->value = $value;
    		elseif ($propertyId == 'evaluation') $this->evaluation = $value;
    		elseif ($propertyId == 'assessment') $this->assessment = $value;

    		if ($propertyId && $this->getProperties()[$propertyId] != $value) $auditRow[$propertyId] = $value;
    	}
    
    	$this->audit[] = $auditRow;
    	return 'OK';
    }
    
    public function add()
    {
    	$context = Context::getCurrent();

    	$this->id = null;
    	NoteLink::getTable()->save($this);
    
    	return ('OK');
    }
    
    public function update($update_time)
    {
    	$context = Context::getCurrent();
    	$noteLink = NoteLink::get($this->id);

    	// Isolation check
    	if ($update_time && $noteLink->update_time > $update_time) return 'Isolation';
    	 
    	NoteLink::getTable()->save($this);
    
    	return 'OK';
    }

    public function isDeletable()
    {
    	$context = Context::getCurrent();
    	return true;
    }
    
    public function delete($update_time)
    {
    	$context = Context::getCurrent();
    	$noteLink = NoteLink::get($this->id);
    
    	// Isolation check
    	if ($update_time && $noteLink->update_time > $update_time) return 'Isolation';
    	 
    	$this->status = 'deleted';
    	NoteLink::getTable()->save($this);
    
    	return 'OK';
    }

    public function drop()
    {
    	$context = Context::getCurrent();
    	NoteLink::getTable()->delete($this->id);
    
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
    	if (!NoteLink::$table) {
    		$sm = Context::getCurrent()->getServiceManager();
    		NoteLink::$table = $sm->get(NoteLinkTable::class);
    	}
    	return NoteLink::$table;
    }
}