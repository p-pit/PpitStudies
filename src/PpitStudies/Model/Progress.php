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
        $this->observations = (isset($data['observations'])) ? json_decode($data['observations'], true) : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->audit = (isset($data['audit'])) ? $data['audit'] : null;
        $this->update_time = (isset($data['update_time'])) ? $data['update_time'] : null;
        
        // Joined properties
        $this->name = (isset($data['name'])) ? $data['name'] : null;
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
    		->join('commitment_account', 'student_progress.account_id = commitment_account.id', array(), 'left')
    		->join('contact_community', 'commitment_account.customer_community_id = contact_community.id', array('name'), 'left')
    		->order(array($major.' '.$dir, 'period', 'subject', 'name'));
		$where = new Where;
		$where->equalTo('type', $type);
		
    	// Todo list vs search modes
    	if ($mode == 'todo') {
    	}
    	else {

    		// Set the filters
    		foreach ($params as $propertyId => $property) {
    			if ($propertyId == 'name') $where->like('contact_community.name', '%'.$params[$propertyId].'%');
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
    	$community = Community::get($account->customer_community_id);
    	$progress->name = $community->name;
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

    public static function retrievePrevious($progress)
    {
    	$select = Progress::getTable()->getSelect()
    		->columns(array('date' => new \Zend\Db\Sql\Expression('MAX(date)')))
    		->where(array(
    			'account_id' => $progress->account_id,
    			'type' => $progress->type,
    			'subject' => $progress->subject,
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
		if (array_key_exists('period', $data)) {
	    	$this->period = trim(strip_tags($data['period']));
		    if (!$this->period || strlen($this->period) > 255) return 'Integrity';
		}

        $subject = $context->getConfig('progress/detail')['types']['sport']['subjects'][$this->subject];
		if (array_key_exists('qualitative_criteria', $subject)) {
			foreach ($subject['qualitative_criteria'] as $criterionId => $criterion) {
				if (array_key_exists('qualitative_'.$criterionId, $data)) {
			    	$this->criteria[$criterionId] = trim(strip_tags($data['qualitative_'.$criterionId]));
				    if (strlen($this->criteria[$criterionId]) > 255) return 'Integrity';
				}
			}
		}
		if (array_key_exists('quantitative_criteria', $subject)) {
			foreach ($subject['quantitative_criteria'] as $criterionId => $criterion) {
				if (array_key_exists('qualitative_'.$criterionId, $data)) {
			    	$this->criteria[$criterionId] = trim(strip_tags($data['qualitative'.$criterionId]));
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
    			'comment' => $this->comment,
    	);

    	return 'OK';
    }

    public function add()
    {
    	$context = Context::getCurrent();

    	$this->id = null;
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