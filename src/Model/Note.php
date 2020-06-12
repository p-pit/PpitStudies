<?php
namespace PpitStudies\Model;

use PpitCore\Model\Community;
use PpitCore\Model\Context;
use PpitCore\Model\Generic;
use PpitCore\Model\Vcard;
use PpitStudies\Model\NoteLink;
use Zend\Db\Sql\Where;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Filter\StripTags;

class Note
{
    public $id;
    public $instance_id;
    public $status;
    public $place_id;
    public $teacher_id;
    public $category;
    public $type;
    public $school_year;
    public $level;
    public $class;
    public $group_id;
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
    
    // Joined properties
    public $teacher_n_fn;
    
    // Transient properties
    public $links;
    public $comment;
    public $properties;

    // Deprecated
    public $groups;
    
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
        $this->place_id = (isset($data['place_id'])) ? $data['place_id'] : null;
        $this->teacher_id = (isset($data['teacher_id'])) ? $data['teacher_id'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->category = (isset($data['category'])) ? $data['category'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
        $this->school_year = (isset($data['school_year'])) ? $data['school_year'] : null;
        $this->level = (isset($data['level'])) ? $data['level'] : null;
        $this->class = (isset($data['class'])) ? $data['class'] : null;
        $this->group_id = (isset($data['group_id'])) ? $data['group_id'] : null;
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
 
    	// Joined properties
        $this->teacher_n_fn = (isset($data['teacher_n_fn'])) ? $data['teacher_n_fn'] : null;
    
        // Deprecated
        $this->groups = (isset($data['groups'])) ? $data['groups'] : null;
    }

    public function getProperties()
    {
    	$data = array();
    	$data['id'] = (int) $this->id;
    	$data['place_id'] = (int) $this->place_id;
    	$data['teacher_id'] = (int) $this->teacher_id;
    	$data['status'] =  $this->status;
    	$data['category'] =  $this->category;
    	$data['type'] = $this->type;
    	$data['school_year'] = $this->school_year;
    	$data['level'] = $this->level;
    	$data['class'] = $this->class;
    	$data['group_id'] = $this->group_id;
    	$data['school_period'] = $this->school_period;
    	$data['subject'] = $this->subject;
    	$data['date'] =  ($this->date) ? $this->date : null;
    	$data['target_date'] =  ($this->target_date) ? $this->target_date : null;
    	$data['reference_value'] =  ($this->reference_value) ? $this->reference_value : null;
    	$data['weight'] =  ($this->weight) ? $this->weight : null;
    	$data['observations'] =  ($this->observations) ? $this->observations : null;
    	$data['document'] =  ($this->document) ? $this->document : null;
    	$data['criteria'] =  ($this->criteria) ? $this->criteria : null;
    	$data['results'] =  ($this->results) ? $this->results : null;
    	$data['average_note'] = ($this->average_note) ? $this->average_note : null;
    	$data['lower_note'] = ($this->lower_note) ? $this->lower_note : null;
    	$data['higher_note'] = ($this->higher_note) ? $this->higher_note : null;
    
    	// Deprecated
    	$data['groups'] = $this->groups;
    	 
    	return $data;
    }
    
    public function toArray()
    {
    	$data = array();
    	$data['id'] = (int) $this->id;
    	$data['instance_id'] = (int) $this->instance_id;
    	$data['place_id'] = (int) $this->place_id;
    	$data['teacher_id'] = (int) $this->teacher_id;
    	$data['status'] =  $this->status;
    	$data['category'] =  $this->category;
    	$data['type'] = $this->type;
    	$data['school_year'] = $this->school_year;
    	$data['level'] = $this->level;
    	$data['class'] = $this->class;
    	$data['group_id'] = (int) $this->group_id;
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

    	// Deprecated
    	$data['groups'] = $this->groups;
    	 
    	return $data;
    }
    
    public static function getList($category, $type, $params, $major, $dir, $mode = 'todo', $limit = 50)
    {
    	$context = Context::getCurrent();
    	$select = Note::getTable()->getSelect()
    		->join('core_vcard', 'core_vcard.id = student_note.teacher_id', array('teacher_n_fn' => 'n_fn'), 'left')
    		->order(array($major.' '.$dir, 'date DESC', 'subject'));
		$where = new Where;
		$where->notEqualTo('student_note.status', 'deleted');
		if ($category) $where->equalTo('student_note.category', $category);
		if ($type) $where->equalTo('student_note.type', $type);
		
    	// Todo list vs search modes
    	if ($mode == 'todo') {
//    		$where->greaterThanOrEqualTo('date', $context->getConfig('currentPeriodStart'));
    		$where->greaterThanOrEqualTo('school_year', $context->getConfig('student/property/school_year/default'));
    	}
    	else {

    		// Set the filters
    		foreach ($params as $propertyId => $property) {
    			if ($propertyId == 'type') $where->equalTo('student_note.type', $params[$propertyId]);
    			elseif ($propertyId == 'place_id') $where->equalTo('place_id', $params[$propertyId]);
    			elseif ($propertyId == 'school_year') $where->equalTo('student_note.school_year', $params[$propertyId]);
    			elseif ($propertyId == 'group_id') $where->equalTo('student_note.group_id', $params[$propertyId]);
    			elseif ($propertyId == 'school_period') $where->equalTo('student_note.school_period', $params[$propertyId]);
    			elseif ($propertyId == 'subject') $where->equalTo('student_note.subject', $params[$propertyId]);
    			elseif (substr($propertyId, 0, 4) == 'min_') $where->greaterThanOrEqualTo('student_note.'.substr($propertyId, 4), $params[$propertyId]);
    			elseif (substr($propertyId, 0, 4) == 'max_') $where->lessThanOrEqualTo('student_note.'.substr($propertyId, 4), $params[$propertyId]);
    			else $where->equalTo('student_note.'.$propertyId, $params[$propertyId]);
//    			else $where->like('student_note.'.$propertyId, '%'.$params[$propertyId].'%');
    		}
    	}
		
    	$select->where($where);
		$cursor = Note::getTable()->selectWith($select);
//    	$criteria = $context->getConfig('note')['criteria'];
		$notes = array();

		$i = 0;
		foreach ($cursor as $note) {
			$note->properties = $note->toArray();
			
			$keep = true;
			if (array_key_exists('p-pit-admin', $context->getPerimeters())) {
				foreach ($context->getPerimeters()['p-pit-admin'] as $key => $values) {
					$keep2 = false;
					foreach ($values as $value) {
						if ($note->properties[$key] == $value) $keep2 = true;
					}
					if (!$keep2) $keep = false;
				}
			}
/*			foreach ($params as $propertyId => $property) {
				if (array_key_exists($propertyId, $criteria) && !array_key_exists($propertyId, $note->criteria)) $keep = false;
				else {
					if (substr($propertyId, 0, 4) == 'min_' && $note->criteria[$propertyId] < $params[$propertyId]) $keep = false;
	    			elseif (substr($propertyId, 0, 4) == 'max_' && $note->criteria[$propertyId] > $params[$propertyId]) $keep = false;
	    			elseif ($params[$propertyId] != $note->criteria[$propertyId]) $keep = false;
				}
			}*/
			if ($keep) {
				$i++;
				if ($limit && $i > $limit) break;
				$notes[] = $note;
			}
		}
		return $notes;
    }

    public static function retrieve($place_id, $category, $type, $class, $school_year, $school_period, $subject, $level = null, $date = null)
    {
    	$select = Note::getTable()->getSelect();
    	$where = new Where;
    	$where->notEqualTo('status', 'deleted');
    	$where->equalTo('place_id', $place_id);
    	$where->equalTo('category', $category);
    	$where->equalTo('type', $type);
    	$where->equalTo('class', $class);
    	$where->equalTo('school_year', $school_year);
    	$where->equalTo('school_period', $school_period);
    	$where->equalTo('subject', $subject);
    	if ($level) $where->equalTo('level', $level);
    	if ($date) $where->equalTo('date', $date);
    	$select->where($where);
    	$cursor = Note::getTable()->selectWith($select);
    	foreach ($cursor as $note) {
	    	$note->links = array();
	    	$select = NoteLink::getTable()->getSelect()
	    				->join('core_account', 'core_account.id = student_note_link.account_id', array(), 'left')
	    				->join('core_vcard', 'core_vcard.id = core_account.contact_1_id', array('n_fn'), 'left')
	    				->where(array('note_id' => $note->id, 'student_note_link.status != ?' => 'deleted'));
			$cursor = NoteLink::getTable()->selectWith($select);
			foreach($cursor as $noteLink) $note->links[$noteLink->account_id] = $noteLink;
    		return $note;
    	}
    	return null;
	}

	public static function updateAverage($place_id, $class, $group_id, $subject, $school_year, $school_period)
	{
		$context = Context::getCurrent();
		
		// user_story - student_evaluation_subject_average:
		// L'ajout d'une évaluation ou l'ajout d'étudiants à une évaluation existante entraîne le recalcul automatique de la moyenne de la matière de la période, pour chaque élève affecté à l'évaluation.
		// La note de référence des moyennes (ex. /20) est un paramètre global.
		// La moyenne par matière calculée est mémorisée pour pouvoir être affichée dans la home étudiant.

		// Compute the subject average for each account
		$teacher_id = null;
		$noteLinks = NoteLink::getList('note', ['group_id' => $group_id, 'subject' => $subject, 'school_year' => $school_year, 'school_period' => $school_period], 'date', 'ASC', 'search');
		$periodNotes = [];
		foreach ($noteLinks as $noteLink) {
			$teacher_id = $noteLink->teacher_id;
			if ($noteLink->value !== null) {
				$periodNotes[$noteLink->account_id][] = ['id' => $noteLink->id, 'reference_value' => $noteLink->reference_value, 'weight' => $noteLink->weight, 'note' => $noteLink->value];
			}
		}
		
		// Load the input data
		$avgData = [];
		$avgData['place_id'] = $place_id;
		$avgData['group_id'] = $group_id;
		$avgData['school_year'] = $school_year;
		$avgData['school_period'] = $school_period;
		$avgData['subject'] = $subject;
		$avgData['teacher_id'] = $teacher_id;
		$avgData['reference_value'] = $context->getConfig('student/parameter/average_computation')['reference_value'];
		$subjectConfig = $context->getConfig('student/property/school_subject')['modalities'][$subject];
		$avgData['weight'] = array_key_exists('credits', $subjectConfig) ? $subjectConfig['credits'] : 1;
		
		$averages = array();
		foreach ($periodNotes as $account_id => $notes) {
			$average = 0;
			$globalWeight = 0;
			foreach ($notes as $note) {
				$average += $note['note'] * $note['weight'];
				$globalWeight += $note['reference_value'] * $note['weight'];
			}
			if ($globalWeight != 0) {
				$average = $average / $globalWeight * $context->getConfig('student/parameter/average_computation')['reference_value'];
				$averages[$account_id] = array('note' => $average, 'notes' => $notes);
			}
		}
		
		// Retrieve the report for the given subject
		$previousReport = current(Note::getList('evaluation', 'report', ['group_id' => $group_id, 'subject' => $subject, 'school_year' => $school_year, 'school_period' => $school_period], 'id', 'ASC', 'search', null));
		if ($previousReport) $report = Note::get($previousReport->id); // Retrieve also the note links we need
		else {
			$report = Note::instanciate('report', null, $group_id);
			$report->links = array();
			$report->teacher_id = $teacher_id;
		}

		foreach ($averages as $account_id => $average) {
			if (!array_key_exists($account_id, $report->links)) $report->links[$account_id] = NoteLink::instanciate($account_id, null);
			$reportLink = $report->links[$account_id];
			$reportLink->value = $average['note'];
			$reportLink->distribution = $average['notes'];
		}

		// user_story - student_evaluation_subject_average_indicators: Le recalcul d'une moyenne par matière de la classe entraîne le recalcul automatique de la moyenne inférieure, de la moyenne des moyennes et de la moyenne supérieure de l'ensemble des moyennes à cette matière de tous les étudiants de la classe.
		
		$noteCount = 0; $noteSum = 0; $lowerNote = 999; $higherNote = 0;
		foreach ($report->links as $reportLink) {
			if ($reportLink->value !== null) {
				$noteSum += $reportLink->value;
				$noteCount++;
				if ($reportLink->value < $lowerNote) $lowerNote = $reportLink->value;
				if ($reportLink->value > $higherNote) $higherNote = $reportLink->value;
			}
		}
		if ($noteCount > 0) {
			$avgData['average_note'] = round($noteSum / $noteCount, 2);
			$avgData['lower_note'] = $lowerNote;
			$avgData['higher_note'] = $higherNote;
		}

		$rc = $report->loadData($avgData);
		if ($rc != 'OK') return $rc;

		if ($report->id) $rc = $report->update(null);
		else $rc = $report->add();
		if ($rc != 'OK') return $rc;

		// Save the note at the student level
		foreach ($report->links as $reportLink) {
			if ($reportLink->id) {
				$rc = $reportLink->drop();
				if ($rc != 'OK') return $rc;
				$reportLink->id = null;
			}
			$reportLink->note_id = $report->id;
			$rc = $reportLink->add();
			if ($rc != 'OK') return $rc;
		}

		// user_story - student_evaluation_global_average:
		// L'ajout d'une évaluation ou l'ajout d'étudiants à une évaluation existante entraîne le recalcul automatique de moyenne générale de la période, pour chaque élève affecté à l'évaluation.
		// La note de référence des moyennes (ex. /20) est un paramètre global.
		// La moyenne générale calculée est mémorisée pour pouvoir être affichée dans la home étudiant.

		$avgData['subject'] = 'global';
		$avgData['teacher_id'] = null;
		$avgData['weight'] = 1;
		
		// Compute the global average for each account
		$noteLinks = NoteLink::getList('report', ['group_id' => $group_id, 'school_year' => $school_year, 'school_period' => $school_period], 'date', 'ASC', 'search');
		$periodAverages = [];
		foreach ($noteLinks as $noteLink) {
			if ($noteLink->subject != 'global') {
				if ($noteLink->value !== null) {
					$periodAverages[$noteLink->account_id][] = ['id' => $noteLink->id, 'reference_value' => $noteLink->reference_value, 'weight' => $noteLink->weight, 'note' => $noteLink->value];
				}
			}
		}
		
		$globalAverages = array();
		foreach ($periodAverages as $account_id => $notes) {
			$average = 0;
			$globalWeight = 0;
			foreach ($notes as $note) {
				$average += $note['note'] * $note['weight'];
				$globalWeight += $note['reference_value'] * $note['weight'];
			}
			if ($globalWeight != 0) {
				$average = $average / $globalWeight * $context->getConfig('student/parameter/average_computation')['reference_value'];
				$globalAverages[$account_id] = array('note' => $average, 'notes' => $notes);
			}
		}
		
		// Retrieve the global report
		$previousReport = current(Note::getList('evaluation', 'report', ['group_id' => $group_id, 'subject' => 'global', 'school_year' => $school_year, 'school_period' => $school_period], 'id', 'ASC', 'search', null));
		if ($previousReport) $report = Note::get($previousReport->id); // Retrieve also the note links we need
		else {
			$report = Note::instanciate('report', null, $group_id);
			$report->links = array();
			$report->teacher_id = $teacher_id;
		}
		
		foreach ($globalAverages as $account_id => $average) {
			if (!array_key_exists($account_id, $report->links)) $report->links[$account_id] = NoteLink::instanciate($account_id, null);
			$reportLink = $report->links[$account_id];
			$reportLink->value = $average['note'];
			$reportLink->distribution = $average['notes'];
		}

		// user_story - student_evaluation_global_average_indicators: Le recalcul de la moyenne générale de la classe entraîne le recalcul automatique de la moyenne inférieure, de la moyenne des moyennes et de la moyenne supérieure de l'ensemble des moyennes de tous les étudiants de la classe.

		$noteCount = 0; $noteSum = 0; $lowerNote = 999; $higherNote = 0;
		foreach ($report->links as $reportLink) {
			if ($reportLink->value !== null) {
				$noteSum += $reportLink->value;
				$noteCount++;
				if ($reportLink->value < $lowerNote) $lowerNote = $reportLink->value;
				if ($reportLink->value > $higherNote) $higherNote = $reportLink->value;
			}
		}
		if ($noteCount > 0) {
			$avgData['average_note'] = round($noteSum / $noteCount, 2);
			$avgData['lower_note'] = $lowerNote;
			$avgData['higher_note'] = $higherNote;
		}

		$rc = $report->loadData($avgData);
		if ($rc != 'OK') return $rc;
		
		if ($report->id) $rc = $report->update(null);
		else $rc = $report->add();
		if ($rc != 'OK') return $rc;
		
		// Save the note at the student level
		foreach ($report->links as $reportLink) {
			if (!$reportLink->id) {
				$reportLink->note_id = $report->id;
				$rc = $reportLink->add();
			}
			else $rc = $reportLink->update(null);
			if ($rc != 'OK') return $rc;
		}

		return null;
	}
	
	public function computeGroupIndicators()
	{
		$noteCount = 0; $noteSum = 0; $lowerNote = 999; $higherNote = 0;
		foreach ($this->links as $reportLink) {
			if ($reportLink->value !== null) {
				$noteSum += $reportLink->value;
				$noteCount++;
				if ($reportLink->value < $lowerNote) $lowerNote = $reportLink->value;
				if ($reportLink->value > $higherNote) $higherNote = $reportLink->value;
			}
		}
		if ($noteCount > 0) {
			return ['average_note' => round($noteSum / $noteCount, 2), 'lower_note' => $lowerNote, 'higher_note' => $higherNote];
		}
		else return null;
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

    public static function computePeriodAverages($place_id, $school_year, $class, $period = null, $subject = 'global')
    {
    	$context = Context::getCurrent();
    	$select = NoteLink::getTable()->getSelect()
    		->join('student_note', 'student_note_link.note_id = student_note.id', array('note_status' => 'status', 'type', 'school_year', 'level', 'class', 'school_period', 'subject', 'date', 'target_date', 'reference_value', 'weight', 'observations', 'criteria', 'average_note', 'lower_note', 'higher_note'), 'left')
    		->order(array('date DESC', 'subject ASC'));
    	$where = new Where;
    	$where->notEqualTo('student_note.status', 'deleted');
    	$where->notEqualTo('student_note_link.status', 'deleted');
    	$where->equalTo('category', 'evaluation');
    	if ($subject == 'global') $where->equalTo('type', 'report');
    	else $where->equalTo('type', 'note');
    	$where->equalTo('place_id', $place_id);
    	$where->equalTo('school_year', $school_year);
    	if ($class) $where->equalTo('class', $class);
    	if ($period) $where->equalTo('school_period', $period);
    	if ($subject == 'global') $where->notEqualTo('subject', 'global');
    	else $where->equalTo('subject', $subject);
    	$select->where($where);
    	$cursor = NoteLink::getTable()->selectWith($select);
    	$periodNotes = array();
    	$periodCategoryNotes = array();
    	$periodSubjectNotes = array();
    	$periodSubjectCategoryNotes = array();
    	foreach ($cursor as $noteLink) {
    		if ($noteLink->value !== null) {
		    	$periodNotes[$noteLink->account_id][] = array('id' => $noteLink->id, 'reference_value' => $noteLink->reference_value, 'weight' => $noteLink->weight, 'note' => $noteLink->value);
		    	$periodCategoryNotes[$noteLink->account_id][$noteLink->level][] = array('reference_value' => $noteLink->reference_value, 'weight' => $noteLink->weight, 'note' => $noteLink->value);
		    	$periodSubjectNotes[$noteLink->account_id][$noteLink->subject][] = array('reference_value' => $noteLink->reference_value, 'weight' => $noteLink->weight, 'note' => $noteLink->value);
		    	$periodSubjectCategoryNotes[$noteLink->account_id][$noteLink->subject][$noteLink->level][] = array('reference_value' => $noteLink->reference_value, 'weight' => $noteLink->weight, 'note' => $noteLink->value);
    		}
    	}
    	$categoryAverages = array();
    	
    	// Compute the global average
    	foreach ($periodNotes as $account_id => $notes) {
    		$average = 0;
    		$globalWeight = 0;
    		foreach ($notes as $note) {
    			$average += $note['note'] * $note['weight'];
    			$globalWeight += $note['reference_value'] * $note['weight'];
/*    			$average += $note['note'] * 20 / $note['reference_value'] * $note['weight'];
    			$globalWeight += $note['weight'];*/
    		}
    		if ($globalWeight != 0) {
    			$average = $average / $globalWeight * 20;
	    		$categoryAverages[$account_id]['global'] = array('note' => $average, 'notes' => $notes);
    		}
    	}

    	// Compute the per category (example 1st mock exam) average
    	foreach ($periodCategoryNotes as $account_id => $categories) {
    		foreach ($categories as $categoryId => $notes) {
	    		$average = 0;
	    		$globalWeight = 0;
	    		foreach ($notes as $note) {
	    			$average += $note['note'] * $note['weight'];
	    			$globalWeight += $note['reference_value'] * $note['weight'];
/*	    			$average += $note['note'] * 20 / $note['reference_value'] * $note['weight'];
	    			$globalWeight += $note['weight'];*/
	    		}
	    		if ($globalWeight != 0) {
	    			$average = $average / $globalWeight * 20;
		    		$categoryAverages[$account_id][$categoryId] = array('note' => $average, 'notes' => $notes);
	    		}
    		}
    	}
/*    
    	// Compute the per subject average
    	foreach ($periodSubjectNotes as $account_id => $subjects) {
    		foreach ($subjects as $subjectId => $notes) {
	    		$average = 0;
	    		$globalWeight = 0;
	    		foreach ($notes as $note) {
	    			$average += $note['note'] * 20 / $note['reference_value'] * $note['weight'];
	    			$globalWeight += $note['weight'];
	    		}
	    		if ($globalWeight != 0) {
	    			$average /= $globalWeight;
		    		$categoryAverages[$account_id][$subjectId]['global'] = array('note' => $average, 'notes' => $notes);
	    		}
    		}
    	}

    	// Compute the per subject and per category (ex. 1st mock exam in french) average
    	foreach ($periodSubjectCategoryNotes as $account_id => $subjects) {
    		foreach ($subjects as $subject_id => $categories) {
				foreach ($categories as $categoryId => $notes) {
		    		$average = 0;
		    		$globalWeight = 0;
		    		foreach ($notes as $note) {
		    			$average += $note['note'] * 20 / $note['reference_value'] * $note['weight'];
		    			$globalWeight += $note['weight'];
		    		}
		    		if ($globalWeight != 0) {
		    			$average /= $globalWeight;
			    		$categoryAverages[$account_id][$subject_id][$categoryId] = array('note' => $average, 'notes' => $notes);
		    		}
	    		}
    		}
    	}*/
    	 
    	return $categoryAverages;
    }

    public static function computeExamAverages($place_id, $school_year, $class, $level)
    {

    	$context = Context::getCurrent();
    	$select = NoteLink::getTable()->getSelect()
	    	->join('student_note', 'student_note_link.note_id = student_note.id', array('note_status' => 'status', 'type', 'school_year', 'level', 'class', 'school_period', 'subject', 'date', 'target_date', 'reference_value', 'weight', 'observations', 'criteria', 'average_note', 'lower_note', 'higher_note'), 'left')
	    	->order(array('date DESC', 'subject ASC'));
    	$where = new Where;
    	$where->notEqualTo('student_note.status', 'deleted');
    	$where->notEqualTo('student_note_link.status', 'deleted');
    	$where->equalTo('category', 'evaluation');
    	$where->equalTo('type', 'note');
    	$where->equalTo('place_id', $place_id);
    	$where->equalTo('school_year', $school_year);
    	$where->equalTo('class', $class);
    	$where->equalTo('level', $level);
    	$select->where($where);
    	$cursor = NoteLink::getTable()->selectWith($select);
    	$periodNotes = array();
    	foreach ($cursor as $noteLink) {
    		if ($noteLink->value !== null) {
    			$periodNotes[$noteLink->account_id][] = array('reference_value' => $noteLink->reference_value, 'weight' => $noteLink->weight, 'note' => $noteLink->value);
    		}
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
    	return $categoryAverages;
    }
    
    public static function get($id, $column = 'id')
    {
    	$note = Note::getTable()->get($id, $column);
    	if (!$note) return null;
    	if ($note->teacher_id) $note->teacher_n_fn = Vcard::get($note->teacher_id)->n_fn;
    	$note->links = array();
    	$select = NoteLink::getTable()->getSelect()
    				->join('core_account', 'core_account.id = student_note_link.account_id', array(), 'left')
    				->join('core_vcard', 'core_vcard.id = core_account.contact_1_id', array('n_fn'), 'left')
    				->where(array('note_id' => $id, 'student_note_link.status != ?' => 'deleted'));
		$cursor = NoteLink::getTable()->selectWith($select);
		foreach($cursor as $noteLink) $note->links[$noteLink->account_id] = $noteLink;
    	return $note;
    }
    
    public static function instanciate($type = null, $class = null, $group_id = null)
    {
    	$context = Context::getCurrent();
		$note = new Note;
    	$note->status = 'new';
		$note->type = $type;
		$note->class = $class;
		if (in_array($type, ['note', 'report', 'exam'])) $note->category = 'evaluation';
		elseif ($type == 'done-work' || $type == 'todo-work' || $type == 'event') $note->category = 'homework';
		$note->teacher_n_fn = $context->getFormatedName();
		$note->audit = array();
		return $note;
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
        if (array_key_exists('teacher_id', $data)) {
		    $this->teacher_id = (int) $data['teacher_id'];
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

        if (array_key_exists('group_id', $data)) {
		    $this->group_id = (int) $data['group_id'];
		}
		
       	if (array_key_exists('class', $data)) {
	    	$this->class = trim(strip_tags($data['class']));
		    if (!$this->class || strlen($this->class) > 255) return 'Integrity';
		}
        if (array_key_exists('groups', $data)) {
	    	$this->groups = trim(strip_tags($data['groups']));
		    if (strlen($this->groups) > 255) return 'Integrity';
		}

		if (array_key_exists('school_period', $data)) {
	    	$this->school_period = trim(strip_tags($data['school_period']));
		    if (strlen($this->school_period) > 255) return 'Integrity';
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
		    if (strlen($this->observations) > 65535) return 'Integrity';
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
    	if ($update_time && $note->update_time > $update_time) return 'Isolation';
    	 
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

    	foreach ($this->links as $noteLink) $noteLink->delete(null);

    	$this->status = 'deleted';
    	Note::getTable()->save($this);
    	 
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
    		Note::$table = $sm->get(NoteTable::class);
    	}
    	return Note::$table;
    }
}