<?php
namespace PpitStudies\Model;

use PpitCore\Model\Context;
use PpitCore\Model\Generic;
use Zend\Db\Sql\Where;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Filter\StripTags;

class NoteLink implements InputFilterAwareInterface
{
    public $id;
    public $instance_id;
    public $status;
    public $account_id;
    public $note_id;
    public $value;
    public $distribution;
    public $assessment;
    public $audit;
    public $update_time;

    // Joined properties
    public $n_fn;
	public $name; // Deprecated
    public $note_status;
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
    public $lower_note;
    public $higher_note;
    public $average_note;
    
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
        $this->assessment = (isset($data['assessment'])) ? $data['assessment'] : null;
        $this->audit = (isset($data['audit'])) ? json_decode($data['audit'], true) : null;
        
        // Joined properties
        $this->n_fn = (isset($data['n_fn'])) ? $data['n_fn'] : null; // Deprecated
        $this->name = (isset($data['name'])) ? $data['name'] : null; // Deprecated
        $this->note_status = (isset($data['note_status'])) ? $data['note_status'] : null;
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
        $this->average_note = (isset($data['average_note'])) ? $data['average_note'] : null;
        $this->lower_note = (isset($data['lower_note'])) ? $data['lower_note'] : null;
        $this->higher_note = (isset($data['higher_note'])) ? $data['higher_note'] : null;
    }
    
    public function toArray()
    {
    	$data = array();
    	$data['id'] = (int) $this->id;
    	$data['instance_id'] = (int) $this->instance_id;
    	$data['status'] = $this->status;
    	$data['account_id'] = (int) $this->account_id;
    	$data['note_id'] = (int) $this->note_id;
    	$data['value'] =  (float) $this->value;
    	$data['distribution'] =  json_encode($this->distribution);
    	$data['assessment'] = $this->assessment;
    	$data['audit'] =  ($this->audit) ? json_encode($this->audit) : null;
		return $data;
    }
    
    public static function getList($type, $params, $major, $dir, $mode = 'todo')
    {
    	$context = Context::getCurrent();
    	$select = NoteLink::getTable()->getSelect()
    		->order(array($major.' '.$dir, 'date DESC', 'type ASC'))
    		->join('student_note', 'student_note_link.note_id = student_note.id', array('note_status' => 'status', 'type', 'school_year', 'level', 'class', 'school_period', 'subject', 'date', 'target_date', 'reference_value', 'weight', 'observations', 'document', 'criteria', 'average_note', 'lower_note', 'higher_note'), 'left');
		$where = new Where;
		if ($type) $where->equalTo('type', $type);

    	// Todo list vs search modes
    	if ($mode == 'todo') {
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
		$cursor = NoteLink::getTable()->selectWith($select);
		$noteLinks = array();
		foreach ($cursor as $noteLink) $noteLinks[] = $noteLink;
		return $noteLinks;
    }

    public static function get($id, $column = 'id')
    {
    	$noteLink = NoteLink::getTable()->get($id, $column);
    	$note = Note::get($noteLink->note_id);
    	$noteLink->status = $note->status;
    	$noteLink->type = $note->type;
    	$noteLink->school_year = $note->school_year;
    	$noteLink->level = $note->level;
    	$noteLink->class = $note->class;
    	$noteLink->school_period = $note->school_period;
    	$noteLink->subject = $note->subject;
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
    
    public static function instanciate($account_id = null, $note_id)
    {
		$noteLink = new NoteLink;
		$noteLink->status = 'new';
		$noteLink->account_id = $account_id;
		$noteLink->note_id = $note_id;
		$noteLink->distribution = array();
		$noteLink->audit = array();
		return $noteLink;
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
    		NoteLink::$table = $sm->get('PpitStudies\Model\NoteLinkTable');
    	}
    	return NoteLink::$table;
    }
}