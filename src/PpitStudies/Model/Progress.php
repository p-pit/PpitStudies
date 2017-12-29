<?php
namespace PpitStudies\Model;

use PpitCore\Model\Account;
use PpitCore\Model\Community;
use PpitCore\Model\Context;
use PpitCore\Model\Generic;
use Zend\Db\Sql\Where;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Filter\StripTags;

class Progress implements InputFilterAwareInterface
{
    public $id;
    public $instance_id;
    public $school_year;
    public $type;
    public $account_id;
    public $subject;
    public $date;
    public $period;
    public $criteria;
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
    public $account;

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
        $this->period = (isset($data['period'])) ? $data['period'] : null;
        $this->criteria = (isset($data['criteria'])) ? json_decode($data['criteria'], true) : null;
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
    	$data['date'] = ($this->date) ? $this->date : null;
    	$data['period'] = $this->period;
    	$data['criteria'] = json_encode($this->criteria);
    	$data['observations'] = $this->observations;
    	$data['status'] = $this->status;
    	$data['audit'] =  ($this->audit) ? json_encode($this->audit) : null;
		return $data;
    }
    
    public static function getList($type, $params, $major, $dir, $mode = 'todo')
    {
    	$select = Progress::getTable()->getSelect()
    		->join('core_account', 'student_progress.account_id = core_account.id', array('name', 'photo' => 'contact_1_id', 'sport' => 'property_1'), 'left')
    		->order(array($major.' '.$dir, 'school_year DESC', 'period DESC', 'subject', 'name'));
		$where = new Where;
		$where->notEqualTo('student_progress.status', 'deleted');
		$where->equalTo('student_progress.type', $type);
		
    	// Todo list vs search modes
    	if ($mode == 'todo') {
    		$where->notEqualTo('student_progress.status', 'completed');
    	}
    	else {

    		// Set the filters
    		foreach ($params as $propertyId => $property) {
    			if ($propertyId == 'name') $where->like('core_community.name', '%'.$params[$propertyId].'%');
				elseif (substr($propertyId, 0, 4) == 'min_') $where->greaterThanOrEqualTo(substr($propertyId, 4), $params[$propertyId]);
    			elseif (substr($propertyId, 0, 4) == 'max_') $where->lessThanOrEqualTo(substr($propertyId, 4), $params[$propertyId]);
    			else $where->like($propertyId, '%'.$params[$propertyId].'%');
    		}
    	}
		
    	$select->where($where);
		$cursor = Progress::getTable()->selectWith($select);
		$progresses = array();
		foreach ($cursor as $progress) {
			$progress->properties = $progress->toArray();
			$progresses[] = $progress;
		}
		return $progresses;
    }

    public static function get($id, $column = 'id')
    {
    	$progress = Progress::getTable()->get($id, $column);
    	$account = Account::get($progress->account_id);
    	$progress->account = $account;
    	return $progress;
    }
    
    public static function instanciate($type = null)
    {
		$progress = new Progress;
		$progress->type = $type;
		$progress->criteria = array();
		$progress->audit = array();
		return $progress;
    }

    public static function retrieveExisting($progress)
    {
    	$select = Progress::getTable()->getSelect()
    		->where(array(
    			'account_id' => $progress->account_id,
    			'type' => $progress->type,
    			'subject' => $progress->subject,
    			'school_year' => $progress->school_year,
    			'period' => $progress->period,
    		));
    	$cursor = Progress::getTable()->selectWith($select);
    	if (count($cursor) > 0) {
    		reset($cursor);
    		return current($cursor);
    	}
    	else return null;
    }
    
    public static function retrievePrevious($progress)
    {
    	$select = Progress::getTable()->getSelect()
    		->columns(array('date' => new \Zend\Db\Sql\Expression('MAX(date)')))
    		->where(array(
    			'account_id' => $progress->account_id,
    			'type' => $progress->type,
    			'subject' => $progress->subject,
    			'status' => 'completed',
    	));
    	$cursor = Progress::getTable()->selectWith($select);
    	if (!count($cursor) > 0) return null;
    	foreach ($cursor as $previousProgress) $previousDate = $previousProgress->date;

    	$select = Progress::getTable()->getSelect()
    		->where(array(
    			'account_id' => $progress->account_id,
    			'type' => $progress->type,
    			'subject' => $progress->subject,
    			'date' => $previousDate,
    		));
    	$cursor = Progress::getTable()->selectWith($select);
    	foreach ($cursor as $progress) {
    		return $progress;
    	}
    }

    public static function retrieveAll($type, $account_id)
    {
    	$select = Progress::getTable()->getSelect()
	    	->where(array('status' => 'completed', 'account_id' => $account_id, 'type' => $type))
	    	->order(array('school_year DESC', 'period DESC'));
    	$cursor = Progress::getTable()->selectWith($select);
    	$progresses = array();
    	foreach ($cursor as $progress) $progresses[] = $progress;
    	return $progresses;
    }

    public function loadData($type, $data) {
    
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
		if (array_key_exists('period', $data)) {
	    	$this->period = trim(strip_tags($data['period']));
		    if (!$this->period || strlen($this->period) > 255) return 'Integrity';
		}

        $subject = $context->getConfig('progress'.(($type) ? '/'.$type : ''))['criteria'];

		if (array_key_exists('qualitative_criteria', $subject)) {
			foreach ($subject['qualitative_criteria'] as $criterionId => $criterion) {
				if (array_key_exists($criterionId, $data)) {
			    	$this->criteria[$criterionId] = trim(strip_tags($data[$criterionId]));
				    if (strlen($this->criteria[$criterionId]) > 255) return 'Integrity';
				}
			}
		}
		
		if (array_key_exists('quantitative_criteria', $subject)) {
			foreach ($subject['quantitative_criteria'] as $criterionId => $criterion) {
				if (array_key_exists($criterionId, $data)) {
			    	$this->criteria[$criterionId] = trim(strip_tags($data[$criterionId]));
				    if (strlen($this->criteria[$criterionId]) > 255) return 'Integrity';
				}
			}
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
    	Progress::getTable()->save($this);
    
    	return ('OK');
    }
    
    public function update($update_time)
    {
    	$context = Context::getCurrent();
    	$progress = Progress::get($this->id);

    	// Isolation check
    	if ($progress->update_time > $update_time) return 'Isolation';
    	 
    	Progress::getTable()->save($this);
    
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
    	$progress = Progress::get($this->id);
    
    	// Isolation check
    	if ($progress->update_time > $update_time) return 'Isolation';
    	 
    	Progress::getTable()->delete($this->id);
    
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
    	if (!Progress::$table) {
    		$sm = Context::getCurrent()->getServiceManager();
    		Progress::$table = $sm->get('PpitStudies\Model\ProgressTable');
    	}
    	return Progress::$table;
    }
}