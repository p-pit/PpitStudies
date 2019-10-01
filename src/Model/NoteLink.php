<?php
namespace PpitStudies\Model;

use PpitCore\Model\Context;
use PpitCore\Model\Generic;
use Zend\Db\Sql\Where;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Filter\StripTags;

class NoteLink
{
    public $id;
    public $instance_id;
    public $status;
    public $account_id;
    public $note_id;
    public $value;
    public $distribution;
    public $evaluation;
    public $assessment;
    public $audit;
    public $update_time;

    // Joined properties
    public $place_id;
    public $place_caption;
    public $n_fn;
    public $user_n_fn;
    public $name;
    public $note_status;
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
        $this->evaluation = (isset($data['evaluation'])) ? $data['evaluation'] : null;
        $this->assessment = (isset($data['assessment'])) ? $data['assessment'] : null;
        $this->audit = (isset($data['audit'])) ? json_decode($data['audit'], true) : null;
        
        // Joined properties
        $this->place_id = (isset($data['place_id'])) ? $data['place_id'] : null;
        $this->place_caption = (isset($data['place_caption'])) ? $data['place_caption'] : null;
        $this->n_fn = (isset($data['n_fn'])) ? $data['n_fn'] : null;
        $this->user_n_fn = (isset($data['user_n_fn'])) ? $data['user_n_fn'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null; // Deprecated
        $this->note_status = (isset($data['note_status'])) ? $data['note_status'] : null;
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
        $this->average_note = (isset($data['average_note'])) ? $data['average_note'] : null;
        $this->lower_note = (isset($data['lower_note'])) ? $data['lower_note'] : null;
        $this->higher_note = (isset($data['higher_note'])) ? $data['higher_note'] : null;
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
    	$data['audit'] =  $this->audit;

    	$data['place_id'] = (int) $this->place_id;
    	$data['place_caption'] =  $this->place_caption;
    	$data['n_fn'] =  $this->n_fn;
    	$data['user_n_fn'] =  $this->user_n_fn;
    	$data['name'] =  $this->name;
    	$data['note_status'] =  $this->note_status;
    	$data['category'] =  $this->category;
    	$data['type'] =  $this->type;
    	$data['school_year'] =  $this->school_year;
    	$data['level'] =  $this->level;
    	$data['class'] =  $this->class;
    	$data['school_period'] =  $this->school_period;
    	$data['subject'] =  $this->subject;
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
    	unset($data['note_status']);
    	unset($data['category']);
    	unset($data['type']);
    	unset($data['school_year']);
    	unset($data['level']);
    	unset($data['class']);
    	unset($data['school_period']);
    	unset($data['subject']);
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
    
    public static function getList($type, $params, $major, $dir, $mode = 'todo')
    {
    	$context = Context::getCurrent();
    	$select = NoteLink::getTable()->getSelect()
    		->order(array($major.' '.$dir, 'date DESC', 'type ASC'))
    		->join('student_note', 'student_note_link.note_id = student_note.id', array('place_id', 'note_status' => 'status', 'type', 'category', 'school_year', 'level', 'class', 'school_period', 'subject', 'date', 'target_date', 'reference_value', 'weight', 'observations', 'document', 'criteria', 'average_note', 'lower_note', 'higher_note'), 'left')
    		->join('core_place', 'student_note.place_id = core_place.id', array('place_caption' => 'caption'), 'left')
    		->join('core_account', 'student_note_link.account_id = core_account.id', array('name'), 'left')
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
    			if ($propertyId == 'place_id') $where->equalTo('student_note.place_id', $params[$propertyId]);
    			elseif ($propertyId == 'account_id') $where->equalTo('account_id', $params[$propertyId]);
    			elseif ($propertyId == 'level') $where->like('student_note.level', '%'.$params[$propertyId].'%');
    			elseif (substr($propertyId, 0, 4) == 'min_') $where->greaterThanOrEqualTo(substr($propertyId, 4), $params[$propertyId]);
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
    	$noteLink->category = $note->category;
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

    public function computeStudentAverage($school_year, $period)
    {
    	$context = Context::getCurrent();
    	$select = NoteLink::getTable()->getSelect()
	    	->join('student_note', 'student_note_link.note_id = student_note.id', array('note_status' => 'status', 'type', 'school_year', 'level', 'class', 'school_period', 'subject', 'date', 'target_date', 'reference_value', 'weight', 'observations', 'criteria', 'average_note', 'lower_note', 'higher_note'), 'left')
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
    			$periodAverages[] = array('reference_value' => ($noteLink->reference_value) ? $noteLink->reference_value : 20, 'weight' => $noteLink->weight, 'note' => $noteLink->value);
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
    	 
    	$this->status = 'deleted';
    	NoteLink::getTable()->save($this);
    
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