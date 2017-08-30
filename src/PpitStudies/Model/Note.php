<?php
namespace PpitStudies\Model;

use PpitCommitment\Model\Account;
use PpitCore\Model\Community;
use PpitCore\Model\Context;
use PpitCore\Model\Generic;
use PpitStudies\Model\NoteLink;
use Zend\Db\Sql\Where;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Filter\StripTags;

class Note implements InputFilterAwareInterface
{
    public $id;
    public $instance_id;
    public $status;
    public $category;
    public $type;
    public $school_year;
    public $level;
    public $class;
    public $school_period;
    public $subject;
    public $date;
    public $target_date;
    public $reference_value;
    public $weight;
    public $observations;
    public $document;
    public $criteria;
    public $results;
    public $lower_note;
    public $higher_note;
    public $average_note;
    public $audit;
    public $update_time;

    // Transient properties
    public $links;
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
        $this->category = (isset($data['category'])) ? $data['category'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
        $this->school_year = (isset($data['school_year'])) ? $data['school_year'] : null;
        $this->level = (isset($data['level'])) ? $data['level'] : null;
        $this->class = (isset($data['class'])) ? $data['class'] : null;
        $this->school_period = (isset($data['school_period'])) ? $data['school_period'] : null;
        $this->subject = (isset($data['subject'])) ? $data['subject'] : null;
        $this->date = (isset($data['date'])) ? $data['date'] : null;
        $this->target_date = (isset($data['target_date'])) ? $data['target_date'] : null;
        $this->reference_value = (isset($data['reference_value'])) ? $data['reference_value'] : null;
        $this->weight = (isset($data['weight'])) ? $data['weight'] : null;
        $this->observations = (isset($data['observations'])) ? $data['observations'] : null;
        $this->document = (isset($data['document'])) ? $data['document'] : null;
        $this->criteria = (isset($data['criteria'])) ? json_decode($data['criteria'], true) : null;
        $this->results = (isset($data['results'])) ? json_decode($data['results'], true) : null;
        $this->average_note = (isset($data['average_note'])) ? $data['average_note'] : null;
        $this->lower_note = (isset($data['lower_note'])) ? $data['lower_note'] : null;
        $this->higher_note = (isset($data['higher_note'])) ? $data['higher_note'] : null;
        $this->audit = (isset($data['audit'])) ? json_decode($data['audit'], true) : null;
        $this->update_time = (isset($data['update_time'])) ? $data['update_time'] : null;
    }
    
    public function toArray()
    {
    	$data = array();
    	$data['id'] = (int) $this->id;
    	$data['instance_id'] = (int) $this->instance_id;
    	$data['status'] =  $this->status;
    	$data['category'] =  $this->category;
    	$data['type'] = $this->type;
    	$data['school_year'] = $this->school_year;
    	$data['level'] = $this->level;
    	$data['class'] = $this->class;
    	$data['school_period'] = $this->school_period;
    	$data['subject'] = $this->subject;
    	$data['date'] =  ($this->date) ? $this->date : null;
    	$data['target_date'] =  ($this->target_date) ? $this->target_date : null;
    	$data['reference_value'] =  ($this->reference_value) ? $this->reference_value : null;
    	$data['weight'] =  ($this->weight) ? $this->weight : null;
    	$data['observations'] =  ($this->observations) ? $this->observations : null;
    	$data['document'] =  ($this->document) ? $this->document : null;
    	$data['criteria'] =  ($this->criteria) ? json_encode($this->criteria) : null;
    	$data['results'] =  ($this->results) ? json_encode($this->results) : null;
    	$data['average_note'] = ($this->average_note) ? $this->average_note : null;
    	$data['lower_note'] = ($this->lower_note) ? $this->lower_note : null;
    	$data['higher_note'] = ($this->higher_note) ? $this->higher_note : null;
    	$data['audit'] =  ($this->audit) ? json_encode($this->audit) : null;

    	return $data;
    }
    
    public static function getList($category, $type, $params, $major, $dir, $mode = 'todo')
    {
    	$context = Context::getCurrent();
    	$select = Note::getTable()->getSelect()
    		->order(array($major.' '.$dir, 'date DESC', 'subject'));
		$where = new Where;
		$where->notEqualTo('student_note.status', 'deleted');
		if ($category) $where->equalTo('student_note.category', $category);
		if ($type) $where->equalTo('student_note.type', $type);
		
    	// Todo list vs search modes
    	if ($mode == 'todo') {
    		$where->greaterThanOrEqualTo('date', $context->getConfig('currentPeriodStart'));
    	}
    	else {

    		// Set the filters
    		foreach ($params as $propertyId => $property) {
				if (substr($propertyId, 0, 4) == 'min_') $where->greaterThanOrEqualTo('student_note.'.substr($propertyId, 4), $params[$propertyId]);
    			elseif (substr($propertyId, 0, 4) == 'max_') $where->lessThanOrEqualTo('student_note.'.substr($propertyId, 4), $params[$propertyId]);
    			else $where->like('student_note.'.$propertyId, '%'.$params[$propertyId].'%');
    		}
    	}
		
    	$select->where($where);
		$cursor = Note::getTable()->selectWith($select);
//    	$criteria = $context->getConfig('note')['criteria'];
		$notes = array();
		foreach ($cursor as $note) {
/*			$keep = true;
			foreach ($params as $propertyId => $property) {
				if (array_key_exists($propertyId, $criteria) && !array_key_exists($propertyId, $note->criteria)) $keep = false;
				else {
					if (substr($propertyId, 0, 4) == 'min_' && $note->criteria[$propertyId] < $params[$propertyId]) $keep = false;
	    			elseif (substr($propertyId, 0, 4) == 'max_' && $note->criteria[$propertyId] > $params[$propertyId]) $keep = false;
	    			elseif ($params[$propertyId] != $note->criteria[$propertyId]) $keep = false;
				}
			}
			if ($keep) {*/
				$note->properties = $note->toArray();
				$notes[] = $note;
//			}
		}
		return $notes;
    }

    public static function retrieveAll($type, $account_id)
    {
    	$select = Note::getTable()->getSelect()
    		->order(array('date DESC', 'subject ASC'));
		$where = new Where;
		$where->notEqualTo('status', 'deleted');
		$where->equalTo('type', $type);
		$where->like('results', '%"'.$account_id.'"%');
		$select->where($where);
    	$cursor = Note::getTable()->selectWith($select);
    	$notes = array();
    	foreach ($cursor as $note) $notes[] = $note;
    	return $notes;
    }

    public static function computePeriodAverages(/*$school_year, */$class/*, $period*/, $subject)
    {
    	$context = Context::getCurrent();
    	$select = NoteLink::getTable()->getSelect()
    		->join('student_note', 'student_note_link.note_id = student_note.id', array('note_status' => 'status', 'type', 'school_year', 'level', 'class', 'school_period', 'subject', 'date', 'target_date', 'reference_value', 'weight', 'observations', 'criteria', 'average_note', 'lower_note', 'higher_note'), 'left')
    		->order(array('date DESC', 'subject ASC'));
    	$where = new Where;
    	$where->notEqualTo('student_note.status', 'deleted');
    	$where->equalTo('type', 'note');
//    	$where->equalTo('school_year', $school_year);
    	$where->equalTo('class', $class);
//    	$where->equalTo('school_period', $period);
    	$where->equalTo('subject', $subject);
//		$where->greaterThanOrEqualTo('date', $context->getConfig('currentPeriodStart'));
		$where->equalTo('school_year', $context->getConfig('student/property/school_year/default'));
		$where->equalTo('school_period', $context->getConfig('student/property/school_period/default'));
		$select->where($where);
    	$cursor = NoteLink::getTable()->selectWith($select);
    	$periodNotes = array();
    	$periodCategoryNotes = array();
    	foreach ($cursor as $noteLink) {
	    	$periodNotes[$noteLink->account_id][] = array('reference_value' => $noteLink->reference_value, 'weight' => $noteLink->weight, 'note' => $noteLink->value);
	    	$periodCategoryNotes[$noteLink->account_id][$noteLink->level][] = array('reference_value' => $noteLink->reference_value, 'weight' => $noteLink->weight, 'note' => $noteLink->value);
    	}
        $categoryAverages = array();
    	foreach ($periodNotes as $account_id => $notes) {
    		$average = 0;
    		$globalWeight = 0;
    		foreach ($notes as $note) {
    			$average += $note['note'] * 20 / $note['reference_value'] * $note['weight'];
    			$globalWeight += $note['weight'];
    		}
    		if ($globalWeight != 0) {
    			$average /= $globalWeight;
	    		$categoryAverages[$account_id]['global'] = array('note' => $average, 'notes' => $notes);
    		}
    	}
    	foreach ($periodCategoryNotes as $account_id => $categories) {
    		foreach ($categories as $categoryId => $notes) {
	    		$average = 0;
	    		$globalWeight = 0;
	    		foreach ($notes as $note) {
	    			$average += $note['note'] * 20 / $note['reference_value'] * $note['weight'];
	    			$globalWeight += $note['weight'];
	    		}
	    		if ($globalWeight != 0) {
	    			$average /= $globalWeight;
		    		$categoryAverages[$account_id][$categoryId] = array('note' => $average, 'notes' => $notes);
	    		}
    		}
    	}
    	return $categoryAverages;
    }

    public static function get($id, $column = 'id')
    {
    	$note = Note::getTable()->get($id, $column);
    	$note->links = array();
    	$select = NoteLink::getTable()->getSelect()
    				->join('commitment_account', 'commitment_account.id = student_note_link.account_id', array(), 'left')
    				->join('core_vcard', 'core_vcard.id = commitment_account.contact_1_id', array('n_fn'), 'left')
//    				->join('core_community', 'core_community.id = commitment_account.customer_community_id', array('name'), 'left')
    				->where(array('note_id' => $id));
		$cursor = NoteLink::getTable()->selectWith($select);
		foreach($cursor as $noteLink) $note->links[] = $noteLink;
    	return $note;
    }
    
    public static function instanciate($type = null, $class = null)
    {
		$note = new Note;
    	$note->status = 'new';
		$note->type = $type;
		$note->class = $class;
		if ($type == 'note' || $type == 'report') $note->category = 'evaluation';
		elseif ($type == 'done-work' || $type == 'todo-work' || $type == 'event') $note->category = 'homework';
		$note->audit = array();
		return $note;
    }

    public function loadData($data) {
    
    	$context = Context::getCurrent();

        if (array_key_exists('status', $data)) {
		    $this->status = trim(strip_tags($data['status']));
		    if (strlen($this->status) > 255) return 'Integrity';
		}
        if (array_key_exists('category', $data)) {
		    $this->category = trim(strip_tags($data['category']));
		    if (!$this->category || strlen($this->category) > 255) return 'Integrity';
		}
		if (array_key_exists('type', $data)) {
		    $this->type = trim(strip_tags($data['type']));
		    if (!$this->type || strlen($this->type) > 255) return 'Integrity';
		}
    	if (array_key_exists('school_year', $data)) {
	    	$this->school_year = trim(strip_tags($data['school_year']));
		    if (!$this->school_year || strlen($this->school_year) > 255) return 'Integrity';
		}
        if (array_key_exists('level', $data)) {
	    	$this->level = trim(strip_tags($data['level']));
		    if (strlen($this->level) > 255) return 'Integrity';
		}
       	if (array_key_exists('class', $data)) {
	    	$this->class = trim(strip_tags($data['class']));
		    if (!$this->class || strlen($this->class) > 255) return 'Integrity';
		}
		if (array_key_exists('school_period', $data)) {
	    	$this->school_period = trim(strip_tags($data['school_period']));
		    if (!$this->school_period || strlen($this->school_period) > 255) return 'Integrity';
		}
    	if (array_key_exists('subject', $data)) {
		    $this->subject = trim(strip_tags($data['subject']));
		    if (!$this->subject || strlen($this->subject) > 255) return 'Integrity';
		}
		if (array_key_exists('date', $data)) {
	    	$this->date = trim(strip_tags($data['date']));
	    	if (!$this->date || !checkdate(substr($this->date, 5, 2), substr($this->date, 8, 2), substr($this->date, 0, 4))) return 'Integrity';
		}
    	if (array_key_exists('target_date', $data)) {
	    	$this->target_date = trim(strip_tags($data['target_date']));
	    	if (!$this->target_date || !checkdate(substr($this->target_date, 5, 2), substr($this->target_date, 8, 2), substr($this->target_date, 0, 4))) return 'Integrity';
		}
		if (array_key_exists('value', $data)) {
		    $this->value = (float) $data['value'];
		    if ($this->value > 100) return 'Integrity';
		}
        if (array_key_exists('reference_value', $data)) {
		    $this->reference_value = (float) $data['reference_value'];
		    if ($this->reference_value > 100) return 'Integrity';
		}
        if (array_key_exists('weight', $data)) {
		    $this->weight = (float) $data['weight'];
		    if ($this->weight > 100) return 'Integrity';
		}
        if (array_key_exists('criteria', $data)) {
			$this->criteria = array();
			foreach ($data['criteria'] as $criterionId => $criterion) {
				$criterion = trim(strip_tags($criterion));
				if (!$criterion || strlen($criterion) > 255) return 'Integrity';
				$this->criteria[$criterionId] = $criterion;
			}
		}
    	if (array_key_exists('observations', $data)) {
		    $this->observations = $data['observations'];
		    if (strlen($this->observations) > 2047) return 'Integrity';
		}
        if (array_key_exists('document', $data)) {
    		$this->document = trim(strip_tags($data['document']));
			if (strlen($this->document) > 255) return 'Integrity';
    	}
		if (array_key_exists('average_note', $data)) {
		    $this->average_note = (float) $data['average_note'];
		    if ($this->average_note > 100) return 'Integrity';
		}
        if (array_key_exists('lower_note', $data)) {
		    $this->lower_note = (float) $data['lower_note'];
		    if ($this->lower_note > 100) return 'Integrity';
		}
        if (array_key_exists('higher_note', $data)) {
		    $this->higher_note = (float) $data['higher_note'];
		    if ($this->higher_note > 100) return 'Integrity';
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
    	Note::getTable()->save($this);
    
    	return ('OK');
    }
    
    public function update($update_time)
    {
    	$context = Context::getCurrent();
    	$note = Note::get($this->id);

    	// Isolation check
    	if ($note->update_time > $update_time) return 'Isolation';
    	 
    	Note::getTable()->save($this);
    
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
    	$note = Note::get($this->id);
    
    	// Isolation check
    	if ($note->update_time > $update_time) return 'Isolation';
    	 
    	Note::getTable()->delete($this->id);
    
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
    	if (!Note::$table) {
    		$sm = Context::getCurrent()->getServiceManager();
    		Note::$table = $sm->get('PpitStudies\Model\NoteTable');
    	}
    	return Note::$table;
    }
}