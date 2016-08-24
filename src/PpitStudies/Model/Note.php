<?php
namespace PpitStudies\Model;

use PpitContact\Model\Community;
use PpitCommitment\Model\Account;
use PpitCore\Model\Context;
use PpitCore\Model\Generic;
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
    public $school_year;
    public $type;
    public $account_id;
    public $subject;
    public $date;
    public $value;
    public $reference_value;
    public $weight;
    public $observations;
    public $status;
    public $audit;
    public $update_time;

    // Joined properties
    public $name;
    public $sport;
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
        $this->school_year = (isset($data['school_year'])) ? $data['school_year'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
        $this->account_id = (isset($data['account_id'])) ? $data['account_id'] : null;
        $this->subject = (isset($data['subject'])) ? $data['subject'] : null;
        $this->date = (isset($data['date'])) ? $data['date'] : null;
        $this->value = (isset($data['value'])) ? $data['value'] : null;
        $this->reference_value = (isset($data['reference_value'])) ? $data['reference_value'] : null;
        $this->weight = (isset($data['weight'])) ? $data['weight'] : null;
        $this->observations = (isset($data['observations'])) ? $data['observations'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->audit = (isset($data['audit'])) ? json_decode($data['audit'], true) : null;
        $this->update_time = (isset($data['update_time'])) ? $data['update_time'] : null;
        
        // Joined properties
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->sport = (isset($data['sport'])) ? $data['sport'] : null;
        $this->photo = (isset($data['photo'])) ? $data['photo'] : null;
    }
    
    public function toArray()
    {
    	$data = array();
    	$data['id'] = (int) $this->id;
    	$data['instance_id'] = (int) $this->instance_id;
    	$data['school_year'] = $this->school_year;
    	$data['type'] = $this->type;
    	$data['account_id'] = (int) $this->account_id;
    	$data['subject'] = $this->subject;
    	$data['date'] =  ($this->date) ? $this->date : null;
    	$data['value'] =  ($this->value) ? $this->value : null;
    	$data['reference_value'] =  ($this->reference_value) ? $this->reference_value : null;
    	$data['weight'] =  ($this->weight) ? $this->weight : null;
    	$data['observations'] =  ($this->observations) ? $this->observations : null;
    	$data['status'] =  $this->status;
    	$data['audit'] =  ($this->audit) ? json_encode($this->audit) : null;
		return $data;
    }
    
    public static function getList($type, $params, $major, $dir, $mode = 'todo')
    {
    	$select = Note::getTable()->getSelect()
    		->join('commitment_account', 'student_note.account_id = commitment_account.id', array('sport' => 'property_1'), 'left')
    		->join('contact_community', 'commitment_account.customer_community_id = contact_community.id', array('name', 'photo' => 'main_contact_id'), 'left')
    		->order(array($major.' '.$dir, 'date', 'subject', 'name'));
		$where = new Where;
		$where->notEqualTo('student_note.status', 'deleted');
		$where->equalTo('student_note.type', $type);
		
    	// Todo list vs search modes
    	if ($mode == 'todo') {
//    		$where->equalTo('date', date('Y-m-d'));
    	}
    	else {

    		// Set the filters
    		foreach ($params as $propertyId => $property) {
				if (substr($propertyId, 0, 4) == 'min_') $where->greaterThanOrEqualTo(substr($propertyId, 4), $params[$propertyId]);
    			elseif (substr($propertyId, 0, 4) == 'max_') $where->lessThanOrEqualTo(substr($propertyId, 4), $params[$propertyId]);
    			else $where->like($propertyId, '%'.$params[$propertyId].'%');
    		}
    	}
		
    	$select->where($where);
		$cursor = Note::getTable()->selectWith($select);
		$notes = array();
		foreach ($cursor as $note) {
			$note->properties = $note->toArray();
			$notes[] = $note;
		}
		return $notes;
    }

    public static function retrieveAll($type, $account_id)
    {
    	$select = Note::getTable()->getSelect()
    		->where(array('status != ?' => 'deleted', 'account_id' => $account_id, 'type' => $type))
    		->order(array('date DESC', 'subject ASC'));
    	$cursor = Note::getTable()->selectWith($select);
    	$notes = array();
    	foreach ($cursor as $note) $notes[] = $note;
    	return $notes;
    }
    
    public static function get($id, $column = 'id')
    {
    	$note = Note::getTable()->get($id, $column);
    	$account = Account::get($note->account_id);
    	$community = Community::get($account->customer_community_id);
    	$note->name = $community->name;
    	return $note;
    }
    
    public static function instanciate($type = null)
    {
		$note = new Note;
		$note->type = $type;
		$note->audit = array();
		return $note;
    }

    public function loadData($data) {
    
    	$context = Context::getCurrent();

        if (array_key_exists('school_year', $data)) {
	    	$this->school_year = trim(strip_tags($data['school_year']));
		    if (!$this->school_year || strlen($this->school_year) > 255) return 'Integrity';
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
		    if (!$this->subject || strlen($this->subject) > 255) return 'Integrity';
		}
		if (array_key_exists('date', $data)) {
	    	$this->date = trim(strip_tags($data['date']));
	    	if (!$this->date || !checkdate(substr($this->date, 5, 2), substr($this->date, 8, 2), substr($this->date, 0, 4))) return 'Integrity';
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
		if (array_key_exists('observations', $data)) {
		    $this->observations = trim(strip_tags($data['observations']));
		    if (strlen($this->observations) > 2047) return 'Integrity';
		}
        if (array_key_exists('status', $data)) {
		    $this->status = trim(strip_tags($data['status']));
		    if (strlen($this->status) > 255) return 'Integrity';
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