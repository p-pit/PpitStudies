<?php
namespace PpitStudies\Model;

use PpitContact\Model\Community;
use PpitContact\Model\Contract;
use PpitContact\Model\Vcard;
use PpitCore\Model\Context;
use PpitMasterData\Model\Place;
use PpitOrder\Model\Order;
use Zend\db\sql\Where;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Student implements InputFilterAwareInterface
{
    public $id;
    public $contract_id;
    public $status;
    public $emergency_phone_1;
    public $emergency_phone_2;
    public $emergency_email;
    public $student_contact_id;
    public $place_id;
    public $school_year;
    public $class;
    public $specialty;
    public $boarding_school;
    public $sisters_brothers;
    public $parents_marital_status;
    public $health_insurance;
    public $id_card_passport;
    public $medical_examination_date;
    public $observations;
    public $sport_option_id;
    public $update_time;

    public $order_id;
    
	// Studies
    public $previous_class;
    public $previous_school;
    public $main_school;
    public $previous_quarter_average;
    public $orientation;
    public $mother_tongue;
    public $living_language_1;
    public $living_language_2;

    // Additional properties
    public $customer_name;
    public $center_name;
    public $n_first;
    public $n_last;
    public $n_fn;
    public $email;
    public $tel_cell;
    public $photo_link_id;
    public $sex;
    public $birth_date;
    public $place_of_birth;
    public $nationality;

    // Sport additional properties
    public $sport;
    public $category;
    public $licence_holder;
    public $level;
    public $results;
    public $left_right_handed;
    public $main_post;
    public $secondary_posts;
    public $club;
    public $coach_name;
    public $coach_tel;
    
    // Medical additional properties
    public $weight;
    public $height;
    public $wear_size;
    public $shoe_size;
    public $medical_histories;
    public $medical_checkup;
    public $muscle_checkup;
    public $streatching_checkup;
    public $ecg;
    public $oro_dental_checkup;
    public $primary_doctor;
    
    // Transient properties
    public $files;
    public $contract;
    public $customerCommunity;
    //    public $order;
    public $studentContact;
    public $customer;
    public $bill_contact;
    public $sportOption;
    public $ok;
	
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
        $this->contract_id = (isset($data['contract_id'])) ? $data['contract_id'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
    	$this->emergency_phone_1 = (isset($data['emergency_phone_1'])) ? $data['emergency_phone_1'] : null;
    	$this->emergency_phone_2 = (isset($data['emergency_phone_2'])) ? $data['emergency_phone_2'] : null;
    	$this->emergency_email = (isset($data['emergency_email'])) ? $data['emergency_email'] : null;
    	$this->student_contact_id = (isset($data['student_contact_id'])) ? $data['student_contact_id'] : null;
        $this->place_id = (isset($data['place_id'])) ? $data['place_id'] : null;
    	$this->school_year = (isset($data['school_year'])) ? $data['school_year'] : null;
        $this->class = (isset($data['class'])) ? $data['class'] : null;
        $this->specialty = (isset($data['specialty'])) ? $data['specialty'] : null;
        $this->boarding_school = (isset($data['boarding_school'])) ? $data['boarding_school'] : null;
        $this->sisters_brothers = (isset($data['sisters_brothers'])) ? $data['sisters_brothers'] : null;
        $this->parents_marital_status = (isset($data['parents_marital_status'])) ? $data['parents_marital_status'] : null;
	    $this->health_insurance = (isset($data['health_insurance'])) ? $data['health_insurance'] : null;
        $this->id_card_passport = (isset($data['id_card_passport'])) ? $data['id_card_passport'] : null;
	    $this->medical_examination_date = (isset($data['medical_examination_date'])) ? $data['medical_examination_date'] : null;
	    $this->observations = (isset($data['observations'])) ? $data['observations'] : null;
	    $this->sport_option_id = (isset($data['sport_option_id'])) ? $data['sport_option_id'] : null;
	    $this->update_time = (isset($data['update_time'])) ? $data['update_time'] : null;

	    $this->order_id = (isset($data['order_id'])) ? $data['order_id'] : null;

		// Etudes
        $this->previous_class = (isset($data['previous_class'])) ? $data['previous_class'] : null;
        $this->previous_school = (isset($data['previous_school'])) ? $data['previous_school'] : null;
        $this->main_school = (isset($data['main_school'])) ? $data['main_school'] : null;
        $this->previous_quarter_average = (isset($data['previous_quarter_average'])) ? $data['previous_quarter_average'] : null;
        $this->orientation = (isset($data['orientation'])) ? $data['orientation'] : null;
        $this->mother_tongue = (isset($data['mother_tongue'])) ? $data['mother_tongue'] : null;
        $this->living_language_1 = (isset($data['living_language_1'])) ? $data['living_language_1'] : null;
        $this->living_language_2 = (isset($data['living_language_2'])) ? $data['living_language_2'] : null;

    	// Additional properties
        $this->center_name = (isset($data['center_name'])) ? $data['center_name'] : null;
        $this->n_title = (isset($data['n_title'])) ? $data['n_title'] : null;
        $this->n_first = (isset($data['n_first'])) ? $data['n_first'] : null;
        $this->n_last = (isset($data['n_last'])) ? $data['n_last'] : null;
        $this->n_fn = (isset($data['n_fn'])) ? $data['n_fn'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->tel_cell = (isset($data['tel_cell'])) ? $data['tel_cell'] : null;
        $this->photo_link_id = (isset($data['photo_link_id'])) ? $data['photo_link_id'] : null;
        $this->birth_date = (isset($data['birth_date'])) ? $data['birth_date'] : null;
        $this->birth_place = (isset($data['birth_place'])) ? $data['birth_place'] : null;
        $this->nationality = (isset($data['nationality'])) ? $data['nationality'] : null;

        // Sport additional properties
        $this->sport = (isset($data['sport'])) ? $data['sport'] : null;
        $this->category = (isset($data['category'])) ? $data['category'] : null;
        $this->licence_holder = (isset($data['licence_holder'])) ? $data['licence_holder'] : null;
        $this->level = (isset($data['level'])) ? $data['level'] : null;
        $this->results = (isset($data['results'])) ? $data['results'] : null;
        $this->left_right_handed = (isset($data['left_right_handed'])) ? $data['left_right_handed'] : null;
        $this->main_post = (isset($data['main_post'])) ? $data['main_post'] : null;
        $this->secondary_post = (isset($data['secondary_posts'])) ? $data['secondary_posts'] : null;
        $this->club = (isset($data['club'])) ? $data['club'] : null;
        $this->coach_name = (isset($data['coach_name'])) ? $data['coach_name'] : null;
        $this->coach_tel = (isset($data['coach_tel'])) ? $data['coach_tel'] : null;
        
		// Medical
        $this->weight = (isset($data['weight'])) ? $data['weight'] : null;
        $this->height = (isset($data['height'])) ? $data['height'] : null;
        $this->wear_size = (isset($data['wear_size'])) ? $data['wear_size'] : null;
        $this->shoe_size = (isset($data['shoe_size'])) ? $data['shoe_size'] : null;
        $this->medical_histories = (isset($data['medical_histories'])) ? $data['medical_histories'] : null;
        $this->medical_checkup = (isset($data['medical_checkup'])) ? $data['medical_checkup'] : null;
        $this->muscle_checkup = (isset($data['muscle_checkup'])) ? $data['muscle_checkup'] : null;
        $this->stretching_checkup = (isset($data['stretching_checkup'])) ? $data['stretching_checkup'] : null;
        $this->ecg = (isset($data['ecg'])) ? $data['ecg'] : null;
        $this->oro_dental_checkup = (isset($data['oro_dental_checkup'])) ? $data['oro_dental_checkup'] : null;
        $this->primary_doctor = (isset($data['primary_doctor'])) ? $data['primary_doctor'] : null;
    }

    public function toArray() {
    	$data = array();
    	$data['id'] = $this->id;
    	$data['contract_id'] = (int) $this->contract_id;
    	$data['status'] = $this->status;
    	$data['emergency_phone_1'] = $this->emergency_phone_1;
    	$data['emergency_phone_2'] = $this->emergency_phone_2;
    	$data['student_contact_id'] = (int) $this->student_contact_id;
    	$data['place_id'] = (int) $this->place_id;
    	$data['school_year'] = $this->school_year;
    	$data['class'] = $this->class;
    	$data['specialty'] = $this->specialty;
    	$data['boarding_school'] = $this->boarding_school;
    	$data['sisters_brothers'] = $this->sisters_brothers;
    	$data['parents_marital_status'] = $this->parents_marital_status;
    	$data['health_insurance'] = $this->health_insurance;
    	$data['id_card_passport'] = $this->id_card_passport;
    	$data['medical_examination_date'] = ($this->medical_examination_date) ? $this->medical_examination_date : null;
    	$data['observations'] = $this->observations;
    	$data['sport_option_id'] = $this->sport_option_id;

    	$data['order_id'] = (int) $this->order_id;

		// Studies
		$data['previous_class'] = $this->previous_class;
		$data['previous_school'] = $this->previous_school;
		$data['main_school'] = $this->main_school;
		$data['previous_quarter_average'] = ($this->previous_quarter_average) ? $this->previous_quarter_average : null;
		$data['orientation'] = $this->orientation;
		$data['mother_tongue'] = $this->mother_tongue;
		$data['living_language_1'] = $this->living_language_1;
		$data['living_language_2'] = $this->living_language_2;

    	return $data;
    }

    public static function getList($params, $major, $dir, $mode)
    {
    	$context = Context::getCurrent();
    	$config = $context->getConfig();

    	// Prepare the SQL request
    	$select = Student::getTable()->getSelect()
	    	->join('contact_contract', 'student.contract_id = contact_contract.id', array('customer_community_id'), 'left')
//	    	->join('contact_community', 'contact_contract.customer_community_id = contact_community.id', array('customer_name' => 'name'), 'left')
//	    	->join('order', 'student.order_id = order.id', array('status'), 'left')
	    	->join('md_place', 'student.place_id = md_place.id', array('center_name' => 'name'), 'left')
	    	->join('contact_vcard', 'student.student_contact_id = contact_vcard.id', array('n_fn', 'n_first', 'n_last', 'email', 'tel_cell', 'photo_link_id', 'adr_city', 'adr_state', 'adr_country', 'sex', 'birth_date', 'place_of_birth', 'nationality'), 'left');

    	if ($config['ppitStudies']['sportOption']) {
    		$select->join('student_sport', 'student.sport_option_id = student_sport.id', array('sport', 'category'), 'left');
    	}
    	    	 
    	$where = new Where();
    
    	if ($mode == 'todo') {
    		 
    		$where->equalTo('status', 'new');
    		$where->equalTo('place_id', $context->getPlace()->id);
//    		$where->equalTo('school_year', $context->getConfig()['ppitStudies']['currentSchoolYear']);
    	}
    	else {
    		 
    		// Set the filters
    		if (isset($params['customer_name'])) $where->like('customer_name', '%'.$params['customer_name'].'%');
    		if (isset($params['status'])) $where->like('status', '%'.$params['status'].'%');
    		if (isset($params['center_name'])) $where->like('md_place.name', '%'.$params['center_name'].'%');
    		if (isset($params['emergency_phone_1'])) $where->like('emergency_phone_1', '%'.$params['emergency_phone_1'].'%');
    		if (isset($params['emergency_phone_2'])) $where->like('emergency_phone_2', '%'.$params['emergency_phone_2'].'%');
    		if (isset($params['emergency_email'])) $where->like('emergency_email', '%'.$params['emergency_email'].'%');
    		if (isset($params['n_fn'])) $where->like('n_fn', '%'.$params['n_fn'].'%');
    		if (isset($params['email'])) $where->like('email', '%'.$params['email'].'%');
    		if (isset($params['tel_cell'])) $where->like('tel_cell', '%'.$params['tel_cell'].'%');
    		if (isset($params['adr_city'])) $where->like('adr_city', '%'.$params['adr_city'].'%');
    		if (isset($params['adr_state'])) $where->like('adr_state', '%'.$params['adr_state'].'%');
    		if (isset($params['adr_country'])) $where->like('adr_country', '%'.$params['adr_country'].'%');
    		if (isset($params['sex'])) $where->equalTo('sex', $params['sex']);
    		if (isset($params['min_birth_date'])) $where->greaterThanOrEqualTo('birth_date', $params['min_birth_date']);
    		if (isset($params['max_birth_date'])) $where->lessThanOrEqualTo('birth_date', $params['max_birth_date']);
    		if (isset($params['place_of_birth'])) $where->like('place_of_birth', '%'.$params['place_of_birth'].'%');
    		if (isset($params['nationality'])) $where->like('nationality', '%'.$params['nationality'].'%');
    		if (isset($params['min_school_year'])) $where->greaterThanOrEqualTo('school_year', $params['min_school_year']);
    		if (isset($params['max_school_year'])) $where->lessThanOrEqualTo('school_year', $params['max_school_year']);

    		if ($config['ppitStudies']['sportOption']) {
	    		if (isset($params['sport'])) $where->equalTo('sport', $params['sport']);
	    		if (isset($params['category'])) $where->like('category', '%'.$params['category'].'%');
    		}

    		if (isset($params['class'])) $where->like('class', '%'.$params['class'].'%');
    		if (isset($params['specialty'])) $where->like('specialty', '%'.$params['specialty'].'%');
    		if (isset($params['boarding_school'])) $where->like('boarding_school', '%'.$params['boarding_school'].'%');
    	}
    
    	$select->where($where)->order(array($major.' '.$dir, 'n_fn'));
    	$cursor = Student::getTable()->selectWith($select);
    
    	// Execute the request
    	$students = array();
    	foreach ($cursor as $student) $students[$student->id] = $student;

    	return $students;
    }

    public static function get($id)
    {
    	$context = Context::getCurrent();
    	$config = $context->getConfig();

    	$student = Student::getTable()->get($id);

    	// Retrieve the customer name via the contract
    	$student->contract = Contract::getTable()->get($student->contract_id);
    	$customerCommunity = Community::get($student->contract->customer_community_id);
    	$student->customer_name = $customerCommunity->name;

    	// Retrieve the order
/*    	$order = Order::getTable()->get($student->order_id);
    	$student->status = $order->status;*/
    	 
    	// Retrieve the place
    	$place = Place::getTable()->get($student->place_id);
    	$student->center_name = $place->name;

    	// Retrieve the student contact
    	$contact = Vcard::getTable()->get($student->student_contact_id);
    	$student->studentContact = $contact;
    	$student->n_fn = $contact->n_fn;
    	$student->n_first = $contact->n_first;
    	$student->n_last = $contact->n_last;
    	$student->email = $contact->email;
    	$student->tel_cell = $contact->tel_cell;
    	$student->photo_link_id = $contact->photo_link_id;
    	$student->sex = $contact->sex;
    	$student->birth_date = $contact->birth_date;
    	$student->place_of_birth = $contact->place_of_birth;
    	$student->nationality = $contact->nationality;

    	// Retrieve the sport properties
    	if ($config['ppitStudies']['sportOption']) {
    		$sportOption = StudentSport::get($student->sport_option_id);
    		$student->sport = $sportOption->sport;
    		$student->category = $sportOption->category;
    		$student->licence_holder = $sportOption->licence_holder;
    		$student->level = $sportOption->level;
    		$student->results = $sportOption->results;
    		$student->left_right_handed = $sportOption->left_right_handed;
    		$student->main_post = $sportOption->main_post;
    		$student->secondary_posts = $sportOption->secondary_posts;
    		$student->club = $sportOption->club;
    		$student->coach_name = $sportOption->coach_name;
    		$student->coach_tel = $sportOption->coach_tel;
    		$student->weight = $sportOption->weight;
    		$student->height = $sportOption->height;
    		$student->wear_size = $sportOption->wear_size;
    		$student->shoe_size = $sportOption->shoe_size;
    		$student->medical_histories = $sportOption->medical_histories;
    		$student->medical_checkup = $sportOption->medical_checkup;
    		$student->muscle_checkup = $sportOption->muscle_checkup;
    		$student->streatching_checkup = $sportOption->stretching_checkup;
    		$student->ecg = $sportOption->ecg;
			$student->oro_dental_checkup = $sportOption->oro_dental_checkup;
    		$student->primary_doctor = $sportOption->primary_doctor;
    		$student->sportOption = $sportOption;
    	}

    	return $student;
    }
    
    public static function instanciate()
    {
    	$student = new Student;
    	$student->status = 'new';
    	$student->contract = Contract::instanciate();
    	$student->customerCommunity = Community::instanciate();
    	$student->studentContact = Vcard::instanciate();
    	return $student;
    }
    
    public function loadData($data) {
		$context = Context::getCurrent();
		$config = $context->getConfig();

		$this->status = trim(strip_tags($data['status']));
		if (!$this->status || strlen($this->status) > 255) return 'Integrity';
		
		$this->studentContact->n_first = trim(strip_tags($data['n_first']));
		if (!$this->studentContact->n_first || strlen($this->studentContact->n_first) > 255) return 'Integrity';
		
		$this->studentContact->n_last = trim(strip_tags($data['n_last']));
		if (!$this->studentContact->n_last || strlen($this->studentContact->n_last) > 255) return 'Integrity';

		$this->studentContact->n_fn = $this->studentContact->n_last.', '.$this->studentContact->n_first;

		$this->studentContact->files = $data['files'];
		
    	$this->place_id = (int) $data['place_id'];
    	if (!Place::get($this->place_id)) return 'Integrity';

    	$this->school_year = (int) $data['school_year'];

    	$this->class = trim(strip_tags($data['class']));
    	if (strlen($this->class) > 255) return 'Integrity';

    	$this->specialty = trim(strip_tags($data['specialty']));
    	if (strlen($this->specialty) > 255) return 'Integrity';
    	 
    	$this->boarding_school = trim(strip_tags($data['boarding_school']));
    	if (strlen($this->boarding_school) > 255) return 'Integrity';
    	 
    	$this->emergency_phone_1 = trim(strip_tags($data['emergency_phone_1']));
    	if (strlen($this->emergency_phone_1) > 255) return 'Integrity';
    	
    	$this->emergency_phone_2 = trim(strip_tags($data['emergency_phone_2']));
    	if (strlen($this->emergency_phone_2) > 255) return 'Integrity';
    	
    	$this->studentContact->email = trim(strip_tags($data['email']));
    	if (!$this->studentContact->email || strlen($this->studentContact->email) > 255) return 'Integrity';

    	$this->studentContact->birth_date = trim(strip_tags($data['birth_date']));
    	if (strlen($this->studentContact->birth_date) > 255) return 'Integrity';

    	$this->studentContact->place_of_birth = trim(strip_tags($data['place_of_birth']));
    	if (strlen($this->studentContact->place_of_birth) > 255) return 'Integrity';

    	$this->studentContact->nationality = trim(strip_tags($data['nationality']));
    	if (strlen($this->studentContact->nationality) > 255) return 'Integrity';

    	$this->sisters_brothers = trim(strip_tags($data['sisters_brothers']));
    	if (strlen($this->boarding_school) > 255) return 'Integrity';

    	$this->parents_marital_status = trim(strip_tags($data['parents_marital_status']));
    	if (strlen($this->parents_marital_status) > 255) return 'Integrity';

    	$this->health_insurance = trim(strip_tags($data['health_insurance']));
    	if (strlen($this->health_insurance) > 255) return 'Integrity';
    	 
    	$this->id_card_passport = trim(strip_tags($data['id_card_passport']));
    	if (strlen($this->id_card_passport) > 255) return 'Integrity';

    	$this->medical_examination_date = trim(strip_tags($data['medical_examination_date']));
    	if ($this->medical_examination_date && !checkdate(substr($this->medical_examination_date, 5, 2), substr($this->medical_examination_date, 8, 2), substr($this->medical_examination_date, 0, 4))) return 'Integrity';

    	$this->observations = trim(strip_tags($data['observations']));
    	if (strlen($this->observations) > 2047) return 'Integrity';

    	// Studies

    	$this->previous_class = trim(strip_tags($data['previous_class']));
    	if (strlen($this->previous_class) > 255) return 'Integrity';

    	$this->previous_school = trim(strip_tags($data['previous_school']));
    	if (strlen($this->previous_school) > 255) return 'Integrity';
    	 
    	$this->main_school = trim(strip_tags($data['main_school']));
    	if (strlen($this->main_school) > 255) return 'Integrity';

    	$this->previous_quarter_average = trim(strip_tags($data['previous_quarter_average']));
    	if (strlen($this->previous_quarter_average) > 255) return 'Integrity';

    	$this->orientation = trim(strip_tags($data['orientation']));
    	if (strlen($this->orientation) > 255) return 'Integrity';

    	$this->mother_tongue = trim(strip_tags($data['mother_tongue']));
    	if (strlen($this->mother_tongue) > 255) return 'Integrity';

    	$this->living_language_1 = trim(strip_tags($data['living_language_1']));
    	if (strlen($this->living_language_1) > 255) return 'Integrity';

    	$this->living_language_2 = trim(strip_tags($data['living_language_2']));
    	if (strlen($this->living_language_2) > 255) return 'Integrity';

    	return 'OK';
    }

    public function loadDataFromRequest($request) {

    	$data = array();
    	$data['status'] = $request->getPost('status');
    	$data['n_first'] = $request->getPost('n_first');
    	$data['n_last'] = $request->getPost('n_last');

    	// Retrieve the photo file
    	$data['files'] = $request->getFiles()->toArray();
//    	if (array_key_exists('photo', $files)) $data['file'] = $files['photo'];
    	
    	$data['place_id'] = $request->getPost('place_id');
    	$data['school_year'] = $request->getPost('school_year');
    	$data['class'] = $request->getPost('class');
    	$data['specialty'] = $request->getPost('specialty');
    	$data['boarding_school'] = $request->getPost('boarding_school');
    	$data['emergency_phone_1'] = $request->getPost('emergency_phone_1');
    	$data['emergency_phone_2'] = $request->getPost('emergency_phone_2');
    	$data['email'] = $request->getPost('email');
    	$data['birth_date'] = $request->getPost('birth_date');
    	$data['place_of_birth'] = $request->getPost('place_of_birth');
    	$data['nationality'] = $request->getPost('nationality');
    	$data['sisters_brothers'] = $request->getPost('sisters_brothers');
    	$data['parents_marital_status'] = $request->getPost('parents_marital_status');
    	$data['health_insurance'] = $request->getPost('health_insurance');
    	$data['id_card_passport'] = $request->getPost('id_card_passport');
    	$data['medical_examination_date'] = $request->getPost('medical_examination_date');
    	$data['observations'] = $request->getPost('observations');
    	
    	// Studies
    	$data['previous_class'] = $request->getPost('previous_class');
    	$data['previous_school'] = $request->getPost('previous_school');
    	$data['main_school'] = $request->getPost('main_school');
    	$data['previous_quarter_average'] = $request->getPost('previous_quarter_average');
    	$data['orientation'] = $request->getPost('orientation');
    	$data['mother_tongue'] = $request->getPost('mother_tongue');
    	$data['living_language_1'] = $request->getPost('living_language_1');
    	$data['living_language_2'] = $request->getPost('living_language_2');

    	if ($this->loadData($data) != 'OK') {
    		throw new \Exception('View error');
    	}
    }
    
    public function add()
    {
    	$context = Context::getCurrent();
    	
    	$this->customerCommunity->add();
    	$this->contract->customer_community_id = $this->customerCommunity->id;
    	$this->contract->supplyer_community_id = $context->getCommunityId();
    	$this->contract->add();
    	$this->contract_id = $this->contract->id;
    	$this->studentContact->add();
		$this->student_contact_id = $this->studentContact->id;
    	$this->id = Student::getTable()->save($this);
    	return 'OK';
    }

    public function update($update_time)
    {
    	$context = Context::getCurrent();
    	 
    	$student = Student::get($this->id);
    
    	// Isolation check
    	if ($student->update_time > $update_time) return 'Isolation';
    	$rc = $this->studentContact->update($update_time);
    	if ($rc != 'OK') return $rc;
    	
    	Student::getTable()->save($this);
    
    	return 'OK';
    }

    public function duplicate()
    {
    	$context = Context::getCurrent();

    	$this->id = null;
    	if ($this->sport_option_id) {
    		$this->sportOption->id = null;
    		StudentSport::getTable()->save($this->sportOption);
    		$this->sport_option_id = $this->sportOption->id;
    	}
    	$this->id = Student::getTable()->save($this);
    	return $this->id;
    }

    public function isUsed($object)
    {
    	// Allow or not deleting a contract
    	if (get_class($object) == 'PpitContact\Model\Contract') {
    		if (Student::getTable()->get($object->id, 'contract_id')) return true;
    	}
        // Allow or not deleting a place of business
    	if (get_class($object) == 'PpitMasterData\Model\PlaceOfBusiness') {
    		if (Student::getTable()->get($object->id, 'place_of_business_id')) return true;
    	}
        // Allow or not deleting a contact
    	if (get_class($object) == 'PpitContact\Model\Vcard') {
    		if (Student::getTable()->get($object->id, 'student_contact_id')) return true;
    	}
    	return false;
    }
    
    public function isDeletable()
    {
    	$config = Context::getCurrent()->getConfig();
    	foreach($config['ppitStudiesDependencies'] as $dependency) {
    		if ($dependency->isUsed($this)) return false;
    	}
    	return true;
    }
    
    public function delete($update_time)
    {
    	$context = Context::getCurrent();
    	$config = $context->getConfig();
    	
    	$student = Student::get($this->id);
    
    	// Isolation check
    	if ($student->update_time > $update_time) return 'Isolation';
    	 
    	// Delete the sport informations
    	if ($this->sport_option_id) {
	    	$studentSport = StudentSport::getTable()->get($this->sport_option_id);
	    	$rc = $studentSport->delete($updateTime);
	    	if ($rc != 'OK') return $rc;
    	}
 
    	Student::getTable()->delete($this->id);
    
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
    	if (!Student::$table) {
    		$sm = Context::getCurrent()->getServiceManager();
    		Student::$table = $sm->get('PpitStudies\Model\StudentTable');
    	}
    	return Student::$table;
    }
}
