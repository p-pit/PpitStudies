<?php
namespace PpitStudies\Model;

use PpitContact\Model\Vcard;
use PpitCore\Controller\Functions;
use PpitCore\Model\Context;
use PpitCore\Model\Link;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Sql\Expression;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;

class UserImport implements InputFilterAwareInterface
{
    public $user_id;
    public $instance_id;
    public $customer_id;
    public $username;
    public $display_name;
    public $password;
    private $password_init_token;
    private $password_init_validity;
    public $nb_trials;
    public $state;
    public $type; // Temporaire, le temps que la gestion des rôles et périmètres standard soit intégrée à fmsportetudes
    public $perimetre; // Temporaire, le temps que la gestion des rôles et périmètres standard soit intégrée à fmsportetudes
    public $contact_id;
	public $locale;
    
    // Additional fields (not in database)
    public $caption;
    public $n_title;
    public $n_first;
    public $n_last;
    public $n_fn;
    public $org;
    public $email;
    public $tel_work;
    public $tel_cell;
    public $role_id;
    public $role_caption;
    
    // For control access
	public $agent_id;
    public $org_unit_id;
    public $roles; // role_id + its parents
    public $perimeters;
	public $allowedRoutes;
	public $fs_root;

	// Security
	public $identifier;
	public $token;
	public $current_password;
	public $new_password;

	// Transient fields
	public $currency;
	public $currency_symbol;
	public $header_code;
	public $pdf_header_code;
	
	public static $table;
	
    protected $inputFilter;
    protected $delegationInputFilter;
    
    protected static $roleCaptions;
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function exchangeArray($data)
    {
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->instance_id = (isset($data['instance_id'])) ? $data['instance_id'] : null;
        $this->customer_id = (isset($data['customer_id'])) ? (int) $data['customer_id'] : 0;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
 	   	$this->display_name = (isset($data['display_name'])) ? $data['display_name'] : null;
 	   	$this->password = (isset($data['password'])) ? $data['password'] : null;
 	   	$this->password_init_token = (isset($data['password_init_token'])) ? $data['password_init_token'] : null;
 	   	$this->password_init_validity = (isset($data['password_init_validity'])) ? $data['password_init_validity'] : null;
 	   	$this->nb_trials = (isset($data['nb_trials'])) ? $data['nb_trials'] : null;
 	   	$this->state = (isset($data['state'])) ? $data['state'] : null;
 	   	$this->type = (isset($data['type'])) ? $data['type'] : null;
 	   	$this->perimetre = (isset($data['perimetre'])) ? $data['perimetre'] : null;
 	   	$this->contact_id = (isset($data['contact_id'])) ? $data['contact_id'] : null;
 	   	$this->locale = (isset($data['locale'])) ? $data['locale'] : null;
 	   	
 	   	$this->caption = (isset($data['caption'])) ? $data['caption'] : null;
 	   	$this->n_first = (isset($data['n_first'])) ? $data['n_first'] : null;
 	   	$this->n_last = (isset($data['n_last'])) ? $data['n_last'] : null;
 	   	$this->n_fn = (isset($data['n_fn'])) ? $data['n_fn'] : null;
 	   	$this->email = (isset($data['email'])) ? $data['email'] : null;
 	   	$this->role_id = (isset($data['role_id'])) ? $data['role_id'] : null;
 	   	$this->role_caption = (isset($data['role_caption'])) ? $data['role_caption'] : null;
    }

    public function toArray()
    {
    	$data = array();
    	$data['user_id'] = (int) $this->user_id;
    	$data['customer_id'] = (int) $this->customer_id;
    	$data['username'] =  $this->username;
    	$data['display_name'] = $this->display_name;
    	$data['password'] = $this->password;
    	$data['password_init_token'] = $this->password_init_token;
    	$data['password_init_validity'] = $this->password_init_validity;
    	$data['nb_trials'] = $this->nb_trials;
    	$data['state'] = (int) $this->state;
    	$data['type'] = $this->type;
    	$data['perimetre'] = $this->perimetre;
    	$data['contact_id'] = (int) $this->contact_id;
    	$data['locale'] = $this->locale;
    	return $data;
    }

    public function retrieveAdditionals($vcard, $userRl, $currentUser) {
    	$this->n_first = $vcard->n_first;
    	$this->n_last = $vcard->n_last;
    	$this->n_fn = $vcard->n_fn;
    	$this->email = $vcard->email;
    	$this->role_id = $userRl->role_id;
    }
    
    public function loadData($request, $vcard, $userRl, $currentUser) {
    
    	// Retrieve the date from the request
        $vcard->n_first = trim(strip_tags($request->getPost('n_first')));
        $vcard->n_last = trim(strip_tags($request->getPost('n_last')));
        $vcard->n_fn = $vcard->n_last.', '.$vcard->n_first;
        $vcard->email = trim(strip_tags($request->getPost('email')));

        if ($request->getPost('username')) $this->username = trim(strip_tags($request->getPost('username')));
		else $this->username = $vcard->email;

		$userRl->role_id = $request->getPost('role_id');

    	// Check integrity
    
    	if (	!$vcard->n_first || strlen($vcard->n_first) > 255
	||	!$vcard->n_last || strlen($vcard->n_last) > 255
	||	strlen($vcard->email) > 255
    	||	$vcard->email && !preg_match(Vcard::$emailRegex, $vcard->email)
) {

    		throw new \Exception('View error');
    	}

    	$this->state = 1;
        $this->locale = 'fr_FR';
    }
    
    public function ensureUser($controller, $settings, $customer_id, $vcard, $currentUser) {

    	// Check for a duplicate user (same user name and different contact_id)
    	$user = $controller->getUserTable()->get($this->username, $currentUser, 'username');
    	if ($user) {
			if ($user->contact_id == $vcard->id) return $user;
			else return null; // Duplicate user name
    	}
    	
    		$this->customer_id = $customer_id;
    		$this->requestPasswordInit();
    		$this->contact_id = $vcard->id;
    		$user_id = $controller->getUserTable()->save($this, $currentUser);
    	
    		// Supplyer users only: Check for an existing agent and create if not
/*			if (!$customer_id) {
    			$select = $controller->getAgentTable()->getSelect()->where(array('contact_id' => $user->contact_id));
    			$cursor = $controller->getAgentTable()->selectWith($select, $currentUser);
    			if (count($cursor) == 0) {
    			$agent = new Agent();
    			$agent->id = 0;
    			$agent->contact_id = $user->contact_id;
    			$agent->start_date = date('Y-m-d');
    			$agent->id = $controller->getAgentTable()->save($agent, $currentUser);
    	
    			// Create the agent attachment
    			$agentAttachment = new AgentAttachment();
    			$agentAttachment->id = 0;
    			$agentAttachment->agent_id = $agent->id;
    			$agentAttachment->org_unit_id = $request->getPost('org_unit_id');
    			$agent->effective_date = date('Y-m-d');
    			$agentAttachment->id = $controller->getAgentAttachmentTable()->save($agentAttachment, $currentUser);
    			}
    		}*/

    		// Create the fs root link
    		$link = new Link;
    		$link->id = 0;
    		$link->owner_id = $user_id;
    		$link->parent_id = 0;
    		$link->name = $this->username;
    		$controller->getLinkTable()->save($link, $currentUser);
    			 
    		// Send the email to the user
    		$translator = $controller->getServiceLocator()->get('translator');
    		$url = $controller->getServiceLocator()->get('viewhelpermanager')->get('url');
    		$email_body = $translator->translate('In order to set your password for your new identifier: %s, please follow this link: ', 'ppit-user', $currentUser->locale);
    		$email_body = sprintf($email_body, $this->username);
   			$email_body .= $settings['ppitCoreSettings']['domainName'].$url('ppitUser/initpassword', array('id' => $user_id)).'?hash='.$this->getPasswordInitToken();
 			$email_title = $translator->translate('Your credentials', 'ppit-user', $currentUser->locale);
    		Functions::envoiMail($currentUser, $settings['ppitCoreSettings'], $vcard->email, $email_body, $email_title, null);
    	
	   		return $this;
    }

    public static function visibleUserList($cursor, $customer_id, $currentUser) {
    
    	// Execute the request
    	$users = array();
    	// Only the users belonging to one's instance, except superadmin which see everyone
    	foreach ($cursor as $user) {
    
    		// Super admin
    		if ($currentUser->role_id == 'super_admin') $users[$user->user_id] = $user;
//    		elseif ($currentUser->instance_id == $user->instance_id &&
//    				$customer_id == $user->customer_id) {
    				
    			$users[$user->user_id] = $user;
//    		}
    	}
    	return $users;
    }
    
    public function getPasswordInitToken() { return $this->password_init_token; }
    public function getPasswordInitValidity() { return $this->password_init_validity; }
    
    public function checkIntegrity($locales, $roles) {

    	$this->username = trim(strip_tags($this->username));
    	$this->state = (int) $this->state;
    	 
    	if (!$this->contact_id ||
    		!$this->username ||
    		!strlen($this->username) > 255 ||
    		!isset($this->locale, $locales) ||
    		!isset($this->role_id, $roles) ||
    		$this->state != 0 && $this->state != 1) {
    	
    		throw new \Exception('View error');
    	}
    }

    public function checkPasswordIntegrity() {
    
    	$this->identifier = trim(strip_tags($this->identifier));
    	   
    	$regex = "/(?=.*[A-Z])(?=.*[0-9]).{8,}$/";
    	if (!$this->identifier ||
    		!strlen($this->identifier) > 255 /*||
    		!preg_match($regex, $this->new_password)*/) {
	
    		throw new \Exception('View error');
    	}
    }

    public function requestPasswordInit() {
    	$this->password_init_token = md5(uniqid(rand(), true));
    	$this->password_init_validity = date('Y-m-d', strtotime(date('Y-m-d').' '.'2'.' day'));
    }

    public function tokenAuthenticate() {
    
    	if ($this->identifier != $this->username) return false;
    	
    	if ($this->token != $this->password_init_token ||
    		date('Y-m-d') > $this->password_init_validity) return false;

    	return true;
    }

    public function getRoleCaption() {
    	$captions = array(
			'accountant' => 'Accountant',
			'admin' => 'Administrator',
			'approver' => 'Approver',
			'customer_admin' => 'Customer administrator',
			'customer_approver' => 'Customer approver',
//    		'customer_responsible' => 'Customer responsible',
			'policy_approver' => 'Policy approver',
    		'responsible' => 'Responsible',
    		'super_admin' => 'Super administrator',
			'technical_responsible' => 'Technical responsible',
    		'user' => 'User'
       	);
    	return ($this->role_id) ? User::$roleCaptions[$this->role_id] : '';
    }

    public function formatFloat($float, $nbDecimal) {
    	if (!$float) return '0';
    	if ($this->locale == 'fr_FR') return number_format($float, $nbDecimal, ',', ' ');
    	else return number_format($float, $nbDecimal, '.', ',');
    }

    public function encodeDate($date) {
    	if (!$date) return null;
    	if ($this->locale == 'fr_FR') return substr($date, 6, 4).'-'.substr($date, 3, 2).'-'.substr($date, 0, 2);
    	else return substr($date, 6, 4).'-'.substr($date, 0, 2).'-'.substr($date, 3, 2);
    }
    
    public function decodeDate($date) {
    	if (!$date) return null;
    	if ($this->locale == 'fr_FR') return substr($date, 8, 2).'/'.substr($date, 5, 2).'/'.substr($date, 0, 4);
    	else return substr($date, 5, 2).'/'.substr($date, 8, 2).'/'.substr($date, 0, 4);
    }
    
    public function dateFormat() {
		if ($this->locale == 'fr_FR') return array(2, 1, 0);
		else return array(2, 0, 1);
    }

    public function decimalSeparator() {
    	if ($this->locale == 'fr_FR') return ',';
    	else return '.';
    }
    
    public function isAllowed($route, $entity = null, $column = null, $value = null) {
    	if (!array_key_exists($route, $this->allowedRoutes)) return false;
    	if (!$value) return true;
    	else {
    		foreach ($this->perimeters as $perimeter) {
    			if ($perimeter->entity == $entity &&
    				$perimeter->column == $column &&
    				$perimeter->value == $value) {
    
   					return true;
  				}
    		}
    	}
    	return false;
    }

    private static function _traverseTree($item, $tree, &$result) {
    	foreach ($tree[$item] as $child) {
    		$result[$child] = $child;
    		User::_traverseTree($child, $tree, $result);
    	}
    }
    
    public function retrievehabilitations($controller) {

    	// Retrieve the instance data
    	$instance = $controller->getInstanceTable()->get($this->instance_id);
    	$this->currency = $instance->currency;
    	$this->currency_symbol = $instance->currency_symbol;
/*    	$this->header_code = $instance->header_code;
    	$this->pdf_header_code = $instance->pdf_header_code;*/

    	// Retrieve the role captions;
    	$serviceLocator = $controller->getServiceLocator();
    	$config = $serviceLocator->get('config');
    	$settings = $config['ppitUserSettings'];
    	$roles = $settings['roles'];
    	$roleTree = $settings['roleTree'];
    	$customerRoles = $settings['customer_roles'];
    	User::$roleCaptions = array_merge($roles, $customerRoles);  // A remonter dans onBoostrap()

    	$perimeters = array();
    	$ownedRoles = array();

    	// Retrieve the contact data
    	if ($this->contact_id) {
	    	$vcard = $controller->getVcardTable()->get($this->contact_id, $this);
	    	$this->email = $vcard->email;
	    	$this->n_fn = $vcard->n_fn;
    	}

    	if ($this->username == 'guest') {
//    		$this->instance_id = null;
    		$this->role_id = 'guest';
	    	$ownedRoles[] = $controller->getUserRoleTable()->get($this->role_id, 'role_id');
    	}
    	else {
    		// Retrieve the agent current organisational unit if any
			$agent = $controller->getAgentTable()->get($this->contact_id, $this, 'contact_id');
			if ($agent) {
				$this->agent_id = $agent->id;
 
				if (!$agent->end_date || $agent->end_date >= date('Y-m-d')) { // The agent should be present

					// Retrieve the most recent attachment
					$select = $controller->getAgentAttachmentTable()->getSelect()
						->columns(array('max_effective_date' => new Expression('MAX(effective_date)'), 'org_unit_id'))
						->where(array('agent_id' => $agent->id));
					$agentAttachment = $controller->getAgentAttachmentTable()->selectWith($select, $this)->current();
					$this->org_unit_id = $agentAttachment->org_unit_id;
				}
			}

    		// Retrieve current role
    		$role_id = $controller->getUserRoleLinkerTable()->get($this->user_id, $this)->role_id;
    		$this->role_id = $role_id;
			$container = new Container('Zend_Auth');
			$ownedRoles = array($role_id => $role_id /*'guest'*/);
//			if ($container->roles) $roles = array_merge($roles, $container->roles);

			// Retrieve the parent roles
/*			foreach ($roles as $role_id) {
				$role = $controller->getUserRoleTable()->get($role_id, 'role_id');
				$end = false;
				while (!$end) {
					$ownedRoles[$role->role_id] = $role->role_id;
					if ($role->parent) $role = $controller->getUserRoleTable()->get($role->parent, 'role_id');
					else $end = true;
				}
			}*/
			User::_traverseTree($role_id, $roleTree, $ownedRoles);

	    	// Retrieve current perimeters
			$select = $controller->getUserPerimeterLinkerTable()->getSelect()
				->join('user_perimeter', 'user_perimeter_linker.perimeter_id = user_perimeter.id', array('entity', 'column', 'value'), 'left')
				->where(array('user_perimeter_linker.user_id' => $this->user_id));
			$cursor = $controller->getUserPerimeterLinkerTable()->selectWith($select, $this);
			foreach ($cursor as $perimeter) $perimeters[] = $perimeter;
			$this->perimeters = $perimeters;
    	}
    	$this->roles = $ownedRoles;

    	// Caching of the route array
    	if (!$controller->routes) {
    		$serviceLocator = $controller->getServiceLocator();
    		$config = $serviceLocator->get('config');
    		$settings = $config['bjyauthorize'];
    		$list = $settings['guards']['BjyAuthorize\Guard\Route'];
    		$controller->routes = array();
    		foreach ($list as $item) {
    			$key = $item['route'];
    			$value = $item['roles'];
    			$controller->routes[$key] = $value;
    		}
    	}
    	$allowedRoutes = array();

    	// Scan the route array
    	foreach ($controller->routes as $route => $roles) {
    		// Scan the user role and parent roles
    		foreach ($ownedRoles as $role) {
    			// Check for the correspondence between owned role and route authorized role
    			if (!(array_search($role, $roles) === FALSE)) $allowedRoutes[$route] = $route;
    		}
    	}
    	$this->allowedRoutes = $allowedRoutes;
    	
    	// Retrieve the file system root
    	$select = $controller->getLinkTable()->getSelect()
	    	->where(array('owner_id' => $this->user_id, 'parent_id' => 0));
    	$this->fs_root = $controller->getLinkTable()->selectWith($select, $this)->current();
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
    	if (!UserImport::$table) {
    		$sm = Context::getCurrent()->getServiceManager();
    		UserImport::$table = $sm->get('PpitStudies\Model\UserImportTable');
    	}
    	return UserImport::$table;
    }
}
