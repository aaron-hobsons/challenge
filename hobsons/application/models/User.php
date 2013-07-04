<?php
/**
 * Class to manipulate user data. See also Model_User_Table.
 *
 * @author		Aaron Smith
 * @copyright	2012 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */
class Model_User extends G_Model
{
    /**
     * The database classed used for CRUD operations
	 *
     * @var string
	 * @acccess protected
     */
	protected $_tableClass = 'Model_User_Table';

    /**
     * The namespace used for creating caching keys
	 *
     * @var string
	 * @acccess protected
     */
	protected $_cacheNamespace = 'user';

    /**
     * Errors encountered when setting values will be set here
	 *
     * @var array
	 * @acccess protected
     */
	protected $_errors = array();

    /**
     * Filters on various fields are set here. In this case, we want to strip all HTML tags and trim whitespace off of all values
	 *
     * @var array
	 * @acccess protected
     */
	protected $_filters = array(
		'*'=>array('StringTrim','StripTags')
	);

    /**
     * Validators to ensure that the data is set is correct.
	 *
     * @var array
	 * @acccess protected
     */
	protected $_validators = array(
		'user_address_id'=>array('Int'),
		'username'=>array(
			'NotEmpty',
			array('StringLength',2, 50),
			'Alnum',
			array('Db_NoRecordExists','user', 'username'),
			'messages'=>array(
				'Username is required',
				'Username must be between %min% and %max% characters long',
				'Username can only contain letters and numbers',
				'Username has been taken'
			)
		),
		'email'=>array(
			'NotEmpty',
			'Email',
			array('Db_NoRecordExists','user', 'email'),
			'messages'=>array(
				'Email is required',
				'Email address is invalid',
				'Email address is already in use'
			)
		),
		'password'=>array(
			'NotEmpty',
			array('StringLength',7),
			'messages'=>array(
				'Password is required',
				'Password must be %min% characters long'
			)
		),
		'confirm_password'=>array(
			'StringsEqual',
			'allowEmpty' => true,
			'fields'=>array('confirm_password','password'),
			'messages'=>array(
				'Passwords do not match'
			)
		),
		'registration_status_id'=>array('NotEmpty','messages'=>array('Registration status is required')),
		'first_name'=>array('NotEmpty','messages'=>array('First name is required')),
		'last_name'=>array('NotEmpty','messages'=>array('Last name is required')),
		'postal_code_id'=>array(
			'NotEmpty',
			array('PostCode','en_US'),
			'messages'=>array(
				'ZIP Code is required',
				'Invalid ZIP Code'
			)
		),
		'state_id'=>array(
			array('Db_RecordExists','state','state_id'),
			'messages'=>array(
				'Invalid country'
			)
		),
		'country_id'=>array(
			'NotEmpty',
			array('Db_RecordExists','country','country_id'),
			'messages'=>array(
				'Country is required',
				'Invalid country'
			)
		)
	);

    /**
     * Dummy fields are not ever inserted in the database, but they may be validated against. In this case, we have
	 * a 'confirm_password' field that is compared with the password field, but is not inserted
	 *
     * @var array
	 * @acccess protected
     */	
	protected $_dummyFields=array('confirm_password');
	
    /**
     * Searches a model by a specific parameter
	 *
     * @param array $dataArray an array of parameters to search by (e.g., to search for first_name="Aaron" you'd set $dataArray=array('first_name'=>Aaron);
     * @param string $orderBy field to sort by
     */
	public function addUser($username,$email,$password,$confirmPassword,$registrationStatus)
	{
		$data=$this->validate(array(
			'username'=>$username,
			'email'=>$email,
			'password'=>$password,
			'confirm_password'=>$confirmPassword,
			'registration_status_id'=>$registrationStatus
		));
		
		if ($this->isError($data)) {
			return $data;
		}
		
		// For security we'll hash our password with both a static salt (from application.ini)
		// and a dynamic salt (randomly generated and stored in the DB)
		$dynamicSalt = G_Auth::getDynamicPasswordSalt($password);
		$staticSalt = G_Auth::getStaticPasswordSalt();
		$password = sha1($dynamicSalt . $password . $staticSalt);

		$data['password']=$password;
		$data['password_salt']=$dynamicSalt;
		return $this->getTable()->insert($data);
	}
	

    /**
     * Save a consumer record
	 *
     * @param int $userId The user's user_id
	 * @param int $userAddressId The user's user_address_id
     * @param int $firstName The user's first name
     * @param int $lastName The user's last name
     * @param int $street1 The user's first line in their street address
     * @param int $street2 The user's second line of their street address
     * @param int $city The user's city
     * @param int $state The user's state/province
     * @param int $postalCode The user's postal code
     */
	public function saveConsumer($userId,$userAddressId,$firstName,$lastName,$street1,$street2,$city,$state,$postalCode) 
	{
		// First we want to check all the errors at once whether
		// they are user errors or address errors
		$data=array();
		$data['user_id']=$userId;
		$data['first_name']=$firstName;	
		$data['last_name']=$lastName;
		if ($userAddressId) {
			$data['user_address_id']=$userAddressId;
		}
		
		$data['street1']=$street1;	
		$data['street2']=$street2;	
		$data['city']=$city;
		$data['state_id']=$state;	
		$data['postal_code_id']=$postalCode;
		$data['country_id']='US';
		$data=$this->validate($data);
			
		$addressData=array();
		
		if ($this->isError($data)) {
			return $data;
		} else {
			$this->getTable()->saveConsumer($data);
			return true;
		}
	}
	
    /**
     * Sets the registration status
	 *
     * @param $userId The user's id
	 * @param $status The registration status being set
     */
	public function setRegistrationStatus($userId, $status)
	{
		$result=$this->getTable()->setRegistrationStatus($userId,$status);
		if ($result) {
			G_Session_User::set('registration_status_id',$status);
			return true;
		} else {
			return false;
		}
	}
}
