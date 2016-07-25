<?php
namespace PpitStudies\Model;

use PpitContact\Model\Community;
use PpitContact\Model\Contract;
use PpitContact\Model\Vcard;
use PpitCore\Model\Context;
use PpitMasterData\Model\Place;
use PpitOrder\Model\Order;
use PpitStudies\Model\Student;
use Zend\db\sql\Where;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class StudentSport implements InputFilterAwareInterface
{
    public $id;
    public $student_id;
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

	// Medical
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

    // Additional properties
    public $customer_community_id;
	public $customer_name;
	public $status;
    public $center_name;
    public $emergency_phone_1;
    public $emergency_phone_2;
    public $emergency_email;
    public $n_first;
    public $n_last;
    public $n_fn;
    public $email;
    public $tel_cell;
    public $student_contact_id;
    public $photo_link_id;
    public $adr_city;
    public $adr_state;
    public $adr_country;
    public $sex;
    public $birth_date;
    public $place_of_birth;
    public $nationality;
    public $school_year;
    public $class;
    public $specialty;
    public $boarding_school;
/*    public $parents_marital_status;
    public $health_insurance;
    public $id_card_passport;
    public $medical_examination_date;
    public $observations;*/
    
    public $update_time;

    // Transient properties (for mass import)
    public $student;
    public $contract;
    public $studentContact;
    public $adr_street;
    public $adr_zip;
    public $main_contact;
    public $main_contact_status;
    public $main_contact_n_first;
    public $main_contact_n_last;
    public $main_contact_email;
    public $main_contact_tel_cell;
    public $main_contact_tel_org;
    public $main_contact_adr_street;
    public $main_contact_adr_zip;
    public $main_contact_adr_city;
    public $main_contact_adr_country;
    public $main_contact_occupation;
    public $backup_contact;
    public $backup_contact_status;
    public $backup_contact_n_first;
    public $backup_contact_n_last;
    public $backup_contact_email;
    public $backup_contact_tel_cell;
    public $backup_contact_tel_org;
    public $backup_contact_adr_street;
    public $backup_contact_adr_zip;
    public $backup_contact_adr_city;
    public $backup_contact_adr_country;
    public $backup_contact_occupation;
    public $bill_contact;
    public $bill_contact_n_title;
    public $bill_contact_n_first;
    public $bill_contact_n_last;
    public $bill_contact_adr_street;
    public $bill_contact_adr_zip;
    public $bill_contact_adr_city;
    public $bill_contact_adr_country;
    public $inscription_date;
    public $package;
    public $sisters_brothers;
    
    protected $inputFilter;

    // Static fields
    private static $table;

    public static function getPreferedProperties()
    {
    	return array(
    			'n_fn' => null,
    			'center_name' => null,
    			'school_year' => null,
    			'emergency_phone_1' => null,
    			'emergency_phone_2' => null,
    			'emergency_email' => null,
    			'sport' => null,
    			'category' => null,
    			'class' => null,
    			'specialty' => null,
    			'boarding_school' => null,
    			'adr_city' => null,
    			'adr_state' => null,
    			'adr_country' => null,
    			'sex' => null,
		    	'birth_date' => null,
    	);
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
    	$this->id = (isset($data['id'])) ? $data['id'] : null;
    	$this->student_id = (isset($data['student_id'])) ? $data['student_id'] : null;
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

        $this->update_time = (isset($data['update_time'])) ? $data['update_time'] : null;

    	// Additional properties
        $this->customer_community_id = (isset($data['customer_community_id'])) ? $data['customer_community_id'] : null;
        $this->customer_name = (isset($data['customer_name'])) ? $data['customer_name'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->center_name = (isset($data['center_name'])) ? $data['center_name'] : null;
        $this->emergency_phone_1 = (isset($data['emergency_phone_1'])) ? $data['emergency_phone_1'] : null;
        $this->emergency_phone_2 = (isset($data['emergency_phone_2'])) ? $data['emergency_phone_2'] : null;
        $this->emergency_email = (isset($data['emergency_email'])) ? $data['emergency_email'] : null;
        $this->n_title = (isset($data['n_title'])) ? $data['n_title'] : null;
        $this->n_first = (isset($data['n_first'])) ? $data['n_first'] : null;
        $this->n_last = (isset($data['n_last'])) ? $data['n_last'] : null;
        $this->n_fn = (isset($data['n_fn'])) ? $data['n_fn'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->tel_cell = (isset($data['tel_cell'])) ? $data['tel_cell'] : null;
        $this->student_contact_id = (isset($data['student_contact_id'])) ? $data['student_contact_id'] : null;
        $this->photo_link_id = (isset($data['photo_link_id'])) ? $data['photo_link_id'] : null;
        $this->adr_city = (isset($data['adr_city'])) ? $data['adr_city'] : null;
        $this->adr_state = (isset($data['adr_state'])) ? $data['adr_state'] : null;
        $this->adr_country = (isset($data['adr_country'])) ? $data['adr_country'] : null;
        $this->sex = (isset($data['sex'])) ? $data['sex'] : null;
        $this->birth_date = (isset($data['birth_date'])) ? $data['birth_date'] : null;
        $this->place_of_birth = (isset($data['place_of_birth'])) ? $data['place_of_birth'] : null;
        $this->nationality = (isset($data['nationality'])) ? $data['nationality'] : null;
        $this->place_id = (isset($data['place_id'])) ? $data['place_id'] : null;
    	$this->school_year = (isset($data['school_year'])) ? $data['school_year'] : null;
        $this->class = (isset($data['class'])) ? $data['class'] : null;
        $this->specialty = (isset($data['specialty'])) ? $data['specialty'] : null;
        $this->boarding_school = (isset($data['boarding_school'])) ? $data['boarding_school'] : null;
/*        $this->parents_marital_status = (isset($data['parents_marital_status'])) ? $data['parents_marital_status'] : null;
        $this->health_insurance = (isset($data['health_insurance'])) ? $data['health_insurance'] : null;
        $this->id_card_passport = (isset($data['id_card_passport'])) ? $data['id_card_passport'] : null;
        $this->medical_examination_date = (isset($data['medical_examination_date'])) ? $data['medical_examination_date'] : null;
        $this->observations = (isset($data['observations'])) ? $data['observations'] : null;*/
    }

    public function toArray() {
    	$data = array();
    	$data['id'] = $this->id;
    	$data['student_id'] = (int) $this->student_id;
    	$data['sport'] = $this->sport;
    	$data['category'] = $this->category;
    	$data['licence_holder'] = $this->licence_holder;
    	$data['level'] = $this->level;
    	$data['results'] = $this->results;
    	$data['left_right_handed'] = $this->left_right_handed;
    	$data['main_post'] = $this->main_post;
    	$data['secondary_posts'] = $this->secondary_posts;
    	$data['club'] = $this->club;
    	$data['coach_name'] = $this->coach_name;
    	$data['coach_tel'] = $this->coach_tel;
		
		// Medical
		$data['weight'] = $this->weight;
		$data['height'] = $this->height;
		$data['wear_size'] = $this->wear_size;
		$data['shoe_size'] = $this->shoe_size;
		$data['medical_histories'] = $this->medical_histories;
		$data['medical_checkup'] = $this->medical_checkup;
		$data['muscle_checkup'] = $this->muscle_checkup;
		$data['stretching_checkup'] = $this->stretching_checkup;
		$data['ecg'] = $this->ecg;
		$data['oro_dental_checkup'] = $this->oro_dental_checkup;
		$data['primary_doctor'] = $this->primary_doctor;
		
    	return $data;
    }
    
    public static function getList($params, $major, $dir, $mode)
    {
    	$context = Context::getCurrent();
    
    	// Prepare the SQL request
    	$select = StudentSport::getTable()->getSelect()
    		->join('student', 'student_sport.student_id = student.id', array('school_year', 'student_contact_id', 'emergency_phone_1', 'emergency_phone_2', 'emergency_email', 'class', 'specialty', 'boarding_school'), 'left')
    		->join('contact_contract', 'student.contract_id = contact_contract.id', array('customer_community_id'), 'left')
    		->join('contact_community', 'contact_contract.customer_community_id = contact_community.id', array('customer_name' => 'name'), 'left')
    		->join('order', 'student.order_id = order.id', array('status'), 'left')
    		->join('md_place', 'student.place_id = md_place.id', array('center_name' => 'name'), 'left')
    		->join('contact_vcard', 'student.student_contact_id = contact_vcard.id', array('n_fn', 'n_first', 'n_last', 'email', 'tel_cell', 'photo_link_id', 'adr_city', 'adr_state', 'adr_country', 'sex', 'birth_date', 'place_of_birth', 'nationality'), 'left');
    	
    	$where = new Where();

    	if ($mode == 'todo') {
    	
    		$where->isNull('status');
    		$where->equalTo('place_id', $context->getPlace()->id);
    		$where->equalTo('school_year', $context->getConfig()['ppitStudies']['currentSchoolYear']);
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
    		if (isset($params['sport'])) $where->equalTo('sport', $params['sport']);
    		if (isset($params['category'])) $where->like('category', '%'.$params['category'].'%');
    		if (isset($params['class'])) $where->like('class', '%'.$params['class'].'%');
    		if (isset($params['specialty'])) $where->like('specialty', '%'.$params['specialty'].'%');
    		if (isset($params['boarding_school'])) $where->like('boarding_school', '%'.$params['boarding_school'].'%');
    	}

    	$select->where($where)->order(array($major.' '.$dir, 'n_fn'));
    	$cursor = StudentSport::getTable()->selectWith($select);
    
    	// Execute the request
    	$studentSports = array();
    	foreach ($cursor as $studentSport) $studentSports[$studentSport->id] = $studentSport;

    	return $studentSports;
    }

    public static function get($id)
    {
    	$context = Context::getCurrent();
    	$studentSport = StudentSport::getTable()->get($id);

    	return $studentSport;
    }
    
    public static function instanciate()
    {
    	$studentSport = new StudentSport;
    	$studentSport->student = Student::instanciate();
    	return $studentSport;
    }
    
    public function loadData($data) {

    	$this->sport = $data['sport'];
    	if (!array_key_exists($this->sport, $settings['ppitStudies']['sports'])) return 'Integrity';

    	$this->category = $data['category'];
    	if (strlen($this->category) > 255) return 'Integrity';

    	$this->licence_holder = trim(strip_tags($data['licence_holder']));
    	if (strlen($this->medical_examination) > 255) return 'Integrity';

    	$this->level = trim(strip_tags($data['level']));
    	if (strlen($this->level) > 255) return 'Integrity';

    	$this->results = trim(strip_tags($data['results']));
    	if (strlen($this->results) > 255) return 'Integrity';

    	$this->left_right_handed = $data['left_right_handed'];
    	if ($this->left_right_handed != 'left' and $this->left_right_handed != 'right') return 'Integrity';
    	 
    	$this->main_post = trim(strip_tags($data['main_post']));
    	if (strlen($this->main_post) > 255) return 'Integrity';

    	$this->secondary_posts = trim(strip_tags($data['secondary_posts']));
    	if (strlen($this->secondary_posts) > 255) return 'Integrity';

    	$this->club = trim(strip_tags($data['club']));
    	if (strlen($this->club) > 255) return 'Integrity';

    	$this->coach_name = trim(strip_tags($data['coach_name']));
    	if (strlen($this->coach_name) > 255) return 'Integrity';

    	$this->coach_tel = trim(strip_tags($data['coach_tel']));
    	if (strlen($this->coach_tel) > 255) return 'Integrity';

    	// Medical

    	$this->weight = trim(strip_tags($data['weight']));
    	if (strlen($this->weight) > 255) return 'Integrity';

    	$this->height = trim(strip_tags($data['height']));
    	if (strlen($this->height) > 255) return 'Integrity';

    	$this->wear_size = trim(strip_tags($data['wear_size']));
    	if (strlen($this->wear_size) > 255) return 'Integrity';

    	$this->shoe_size = trim(strip_tags($data['shoe_size']));
    	if (strlen($this->shoe_size) > 255) return 'Integrity';

    	$this->medical_histories = trim(strip_tags($data['medical_histories']));
    	if (strlen($this->medical_histories) > 255) return 'Integrity';

    	$this->medical_checkup = trim(strip_tags($data['medical_checkup']));
    	if (strlen($this->medical_checkup) > 255) return 'Integrity';

    	$this->muscle_checkup = trim(strip_tags($data['muscle_checkup']));
    	if (strlen($this->muscle_checkup) > 255) return 'Integrity';

    	$this->stretching_checkup = trim(strip_tags($data['streching_checkup']));
    	if (strlen($this->stretching_checkup) > 255) return 'Integrity';

    	$this->ecg = trim(strip_tags($data['ecg']));
    	if (strlen($this->ecg) > 255) return 'Integrity';

    	$this->oro_dental_checkup = trim(strip_tags($data['oro_dental_checkup']));
    	if (strlen($this->oro_dental_checkup) > 255) return 'Integrity';

    	$this->primary_doctor = trim(strip_tags($data['primary_doctor']));
    	if (strlen($this->primary_doctor) > 255) return 'Integrity';
    }

    public function loadDataFromRequest($request, $vcardTable, $settings, $currentUser, $centers) {

    	$data = array();
    	$data['student_id'] = $request->getPost('student_id');
    	$data['sport'] = $request->getPost('sport');
    	$data['category'] = $request->getPost('category');
    	$data['licence_holder'] = $request->getPost('licence_holder');
    	$data['level'] = $request->getPost('level');
    	$data['results'] = $request->getPost('results');
    	$data['left_right_handed'] = $request->getPost('left_right_handed');
    	$data['main_post'] = $request->getPost('main_post');
    	$data['secondary_posts'] = $request->getPost('secondary_posts');
    	$data['club'] = $request->getPost('club');
    	$data['coach_name'] = $request->getPost('coach_name');
    	$data['coach_tel'] = $request->getPost('coach_tel');
    	
    	// Medical
    	$data['weight'] = $request->getPost('weight');
    	$data['height'] = $request->getPost('height');
    	$data['wear_size'] = $request->getPost('wear_size');
    	$data['shoe_size'] = $request->getPost('shoe_size');
    	$data['medical_histories'] = $request->getPost('medical_histories');
    	$data['medical_checkup'] = $request->getPost('medical_checkup');
    	$data['muscle_checkup'] = $request->getPost('muscle_checkup');
    	$data['stretching_checkup'] = $request->getPost('stretching_checkup');
    	$data['ecg'] = $request->getPost('ecg');
    	$data['oro_dental_checkup'] = $request->getPost('oro_dental_checkup');
    	$data['primary_doctor'] = $request->getPost('primary_doctor');
    	 
    	if ($this->loadData($data) != 'OK') throw new \Exception('View error');
    }

    public function add()
    {
    	$rc = $this->student->add();
    	if ($rc != 'OK') return $rc;
    	$this->student_id = $this->student->id;
    	StudentSport::getTable()->save($this);

    	return 'OK';
    }
    
    public function update($update_time)
    {
    	$context = Context::getCurrent();
    
    	$studentSport = StudentSport::get($this->id);
    
    	// Isolation check
    	if ($studentSport->update_time > $update_time) return 'Isolation';
    
    	// Save the order form and the order
    	$rc = $this->student->update($update_time);
    	if ($rc != 'OK') return $rc;
    	StudentSport::getTable()->save($this);
    
    	return 'OK';
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
    	$studentSport = StudentSport::get($this->id);
    
    	// Isolation check
    	if ($studentSport->update_time > $update_time) return 'Isolation';
    
    	// Delete the sport informations
    	$studentSport = StudentSport::getTable()->get($this->id, 'student_id');
    	if ($studentSport) $return = $studentSport->delete($updateTime);
    	if ($return != 'OK') return $return;
    
    	StudentSport::getTable()->delete($this->id);
    
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
    	if (!StudentSport::$table) {
    		$sm = Context::getCurrent()->getServiceManager();
    		StudentSport::$table = $sm->get('PpitStudies\Model\StudentSportTable');
    	}
    	return StudentSport::$table;
    }
}
