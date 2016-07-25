<?php
namespace PpitStudies\Model;

use PpitContact\Model\VcardProperty;
use PpitCore\Model\Context;
use PpitCore\Model\Link;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class VcardImport implements InputFilterAwareInterface
{
    public $id;
	public $instance_id;
	public $customer_id;
	public $n_title;
    public $n_first;
    public $n_last;
    public $n_fn;
    public $org;
    public $tel_work;
    public $tel_cell;
    public $email;
    public $properties;
    public $photo_link_id;
    
    // Additional fields (from joined table)
/*    public $address_type;
    public $ADR_street;
    public $ADR_extended;
    public $ADR_post_office_box;
    public $ADR_zip;
    public $ADR_city;
    public $ADR_state;
    public $ADR_country;*/

    public static $table;
    
    protected $inputFilter;
    protected $devisInputFilter;

    // Static fields
    public static $emailRegex = "/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/";
    public static $telRegex = "/^\+?([0-9\. ]*)$/";
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->instance_id = (isset($data['instance_id'])) ? $data['instance_id'] : null;
        $this->customer_id = (isset($data['customer_id'])) ? $data['customer_id'] : null;
        $this->n_title = (isset($data['n_title'])) ? $data['n_title'] : null;
        $this->n_first = (isset($data['n_first'])) ? $data['n_first'] : null;
        $this->n_last = (isset($data['n_last'])) ? $data['n_last'] : null;
        $this->n_fn = (isset($data['n_fn'])) ? $data['n_fn'] : null;
        $this->org = (isset($data['org'])) ? $data['org'] : null;
        $this->tel_work = (isset($data['tel_work'])) ? $data['tel_work'] : null;
        $this->tel_cell = (isset($data['tel_cell'])) ? $data['tel_cell'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        
        // Retrieve the properties
        $properties = (isset($data['properties'])) ? ((json_decode($data['properties'])) ? json_decode($data['properties']) : array()) : array();
        $this->properties = array();
        foreach ($properties as $property_name => $property_array) {
        	$property = new VcardProperty;
        	$property->name = $property_name;
        	$property->type = $property_array->type;
        	$property->text_value = $property_array->text_value;
        	$this->properties[$property_name] = $property;
	    }

	    $this->photo_link_id = (isset($data['photo_link_id'])) ? $data['photo_link_id'] : null;
	     
		// Additional fields
        $this->address_type = (isset($data['address_type'])) ? $data['address_type'] : null;
        $this->ADR_street = (isset($data['ADR_street'])) ? $data['ADR_street'] : null;
        $this->ADR_extended = (isset($data['ADR_extended'])) ? $data['ADR_extended'] : null;
        $this->ADR_post_office_box = (isset($data['ADR_post_office_box'])) ? $data['ADR_post_office_box'] : null;
        $this->ADR_zip = (isset($data['ADR_zip'])) ? $data['ADR_zip'] : null;
        $this->ADR_city = (isset($data['ADR_city'])) ? $data['ADR_city'] : null;
        $this->ADR_state = (isset($data['ADR_state'])) ? $data['ADR_state'] : null;
        $this->ADR_country = (isset($data['ADR_country'])) ? $data['ADR_country'] : null;
    }

    public function toArray()
    {
    	$data = array();
    	$data['id'] = (int) $this->id;
    	$data['instance_id'] = (int) $this->instance_id;
    	$data['customer_id'] = (int) $this->customer_id;
    	$data['n_title'] = $this->n_title;
    	$data['n_first'] = $this->n_first;
    	$data['n_last'] = $this->n_last;
    	$data['n_fn'] = $this->n_fn;
    	$data['org'] = $this->org;
    	$data['tel_work'] = $this->tel_work;
    	$data['tel_cell'] = $this->tel_cell;
    	$data['email'] = $this->email;
    	if ($this->properties) {
	    	$properties = array();
	    	foreach ($this->properties as $property) {
	    		if ($property->text_value) $properties[$property->name] = $property->toArray();
	    	}
	    	$data['properties'] = json_encode($properties);
    	}
    	$data['photo_link_id'] = $this->photo_link_id;
    	 
    	return $data;
    }

    public function retrieveProperties($captions) {

    	$properties = array();
    	foreach ($captions as $property_name => $property_caption) {
	    	if ($this->properties && array_key_exists($property_name, $this->properties)) {
	    		$property = $this->properties[$property_name];
	    	}
	    	else {
	    		$property = new VcardProperty;
	    	}
	    	$property->name = $property_name;
	    	$property->caption = $property_caption;
	    	$properties[$property_name] = $property;
    	}
    	return $properties;
    }

    public function checkIsolation($request, $properties, $prefix='') {

    	if ($this->n_title != $request->getPost('db_'.$prefix.'n_title')
    	||	$this->n_last != $request->getPost('db_'.$prefix.'n_last')
    	||	$this->n_first != $request->getPost('db_'.$prefix.'n_first')
    	||	$this->email != $request->getPost('db_'.$prefix.'email')
    	||	$this->tel_work != $request->getPost('db_'.$prefix.'tel_work')
    	||	$this->tel_cell != $request->getPost('db_'.$prefix.'tel_cell'))
    	{
    		return false;
    	}

    	foreach ($properties as $property_name => $property) {
    		if ($this->properties[$property_name] != $request->getPost('db_'.$prefix.$property_name))
    	    {
    			return false;
	    	}
    	}

    	return true;
    }
    
    public function loadData($request, $properties, $vcardTable, $currentUser, $prefix='') {
    	 
    	// Save the identifying previous data
    	$previous_n_last = $this->n_last;
    	$previous_n_first = $this->n_first;
    	$previous_email = $this->email;
    	$previous_tel_cell = $this->tel_cell;
    	
    	// Retrieve the data from the request
    	$this->n_title =  trim(strip_tags($request->getPost($prefix.'n_title')));
    	$this->n_last =  trim(strip_tags($request->getPost($prefix.'n_last')));
    	$this->n_first =  trim(strip_tags($request->getPost($prefix.'n_first')));
    	$this->email =  trim(strip_tags($request->getPost($prefix.'email')));
    	$this->tel_work =  trim(strip_tags($request->getPost($prefix.'tel_work')));
    	$this->tel_cell =  trim(strip_tags($request->getPost($prefix.'tel_cell')));

    	// Check integrity
    
    	if (	strlen($this->n_title) > 255
    		||	$this->n_first == '' || strlen($this->n_first) > 255
    		||	$this->n_last == '' || strlen($this->n_last) > 255
    		||	strlen($this->email) > 255
    		|| ($this->email && !preg_match(Vcard::$emailRegex, $this->email))
    		||	strlen($this->tel_work) > 255
    		|| ($this->tel_work && !preg_match(Vcard::$telRegex, $this->tel_work))
    		||	strlen($this->tel_cell) > 255
    		|| ($this->tel_cell && !preg_match(Vcard::$telRegex, $this->tel_cell))
    		/*|| (!$this->email && (!$this->tel_work && !$this->tel_cell))*/) // At least an email or a phone
    	{
    		throw new \Exception('View error');
    	}
    			
    	foreach ($properties as $property_name => $property) {
			$property->text_value = trim(strip_tags($request->getPost($prefix.$property_name)));
    		if (strlen($property->text_value) > 255) throw new \Exception('View error');
    		$this->properties[$property_name] = $property;
    	}
    				 
    	$this->n_fn = $this->n_last.', '.$this->n_first;

		// Determine if the contact change (change in the identifying data
		if ($this->n_last != $previous_n_last
		||	$this->n_first != $previous_n_first
		|| 	($this->email && $this->email != $previous_email)
		||	($this->tel_cell && $this->tel_cell != $previous_tel_cell)) {
	    	
			$this->id = null;
			
	    	// Check for an existing contact : same first name and last name and (email or cellular)
	    	if ($this->email || $this->tel_cell) {
			    $select = $vcardTable->getSelect()
			    	->where(array('n_first' => $this->n_first, 'n_last' => $this->n_last));
			    if ($this->email) {
				   	$select->where->equalTo('email', $this->email);
			    }
			    else {
					$select->where->equalTo('tel_cell', $this->tel_cell);
			    }
			    $cursor = $vcardTable->selectWith($select, $currentUser);
				if (count($cursor) > 0) $this->id = $cursor->current()->id;
	    	}
		}
		return $this->id;
    }

    public function loadPhoto ($file, $controller, $currentUser, $settings) {
    	$link = new Link;
	    $link->loadFile($file, $settings['ppitContactSettings']['photoRootLink'], $controller, $currentUser, $settings);
	    $this->photo_link_id = $link->id;
	    $controller->getVcardTable()->save($this, $currentUser);
    }
    
    public function loadPhotoFromRequest($request, $name, $controller, $currentUser, $settings) {
    	$nonFiles = $request->getPost()->toArray();
    	$files = $request->getFiles()->toArray();
    	$this->loadPhoto($files[$name], $controller, $currentUser, $settings);
    }

    public static function visibleContactList($cursor, $customer_id, $currentUser) {
    
    	// Execute the request
    	$contacts = array();
    	// Only the users belonging to one's instance, except superadmin which see everyone
    	foreach ($cursor as $contact) {
    
    		// Super admin
    		if ($currentUser->role_id == 'super_admin') $contacts[$contact->id] = $contact;
    
/*    		// Admin
    		elseif ($currentUser->role_id == 'admin' &&
    				$currentUser->instance_id == $contact->instance_id) {
    				
    			$contacts[$contact->id] = $contact;
    		}
    
    		// Customer admin*/
    		elseif (/*$currentUser->role_id == 'customer_admin' &&*/
    				$currentUser->instance_id == $contact->instance_id &&
    				$customer_id == $contact->customer_id) {

    			$contacts[$contact->id] = $contact;
    		}
    	}
    	return $contacts;
    }
    
    // Add content to this method:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        throw new \Exception("Not used");
    }

    public function getDevisInputFilter()
    {
        throw new \Exception("Not used");
    }    

    public static function getTable()
    {
    	if (!VcardImport::$table) {
    		$sm = Context::getCurrent()->getServiceManager();
    		VcardImport::$table = $sm->get('PpitStudies\Model\VcardImportTable');
    	}
    	return VcardImport::$table;
    }
}
