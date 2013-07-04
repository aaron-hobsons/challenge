<?php
/**
 * Simple model based on Zend_DB_Table_Abstract
 *
 * @author		Aaron Smith
 * @copyright	2012 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */
class Model_User_Table extends G_Zend_Db_Table
{

    /**
     * The name of the table
	 *
     * @var string
     */
	protected $_name = 'user';

    /**
     * The name of the primary key
	 *
     * @var string
     */
	protected $_primary='user_id';

    /**
     * The name of the class for the associated row class
	 *
     * @var string
     */
	protected $_rowClass='Model_User_TableRow';

    /**
     * Adds a new user to the database.
	 *
     * @param string $username is the username of the user
     * @param string $email is the email address of the user
     * @param string $password is the password for the user
     * @param string $staticSalt is the static salt (i.e., the salt that is constant for all users as opposed to the dynamic salt for which each user has a unique one) for the user
     * @param string $registrationStatus determines how far along the user is in the registration process
     */
	public function addUser($username, $email, $password, $staticSalt,$registrationStatus)
	{
		try {
			$data = array('username' => $username, 'email' => $email, 
			'password' => $password, 'password_salt' => $dynamicSalt,
			'registration_status_id'=>$registrationStatus);
			return $this->insert($data);
		} catch (G_Exception $e) {
			return $e;
		}
	}
	
    /**
     * Adds a new consumer to the database.
	 *
     * @param array $data is an array of data to be added. Admittedly, I got a little lazy here, since I didn't specify the exact parameters that it takes to add a consumer -- but these are variable and frequently optional, so sue me! Plus, since this class works hand in hand with the Model_User class and the parameters are well defined there, it really shouldn't matter. Anyway, here are the parameters that can be set:
	 *		-  $data['user_id'] int The user_id for the consumer
	 *		-  $data['user_address_id'] int The id in the user_address table (if we're updating an address, not inserting one)
	 *		-  $data['first_name'] string The consumer's first name
	 *		-  $data['last_name'] string The consumer's last name
	 *		-  $data['street1'] string The first line of the user's street address
	 *		-  $data['street2'] string The second line of the user's street address
	 *		-  $data['state_id'] string The state_id (or province) for the user
	 *		-  $data['postal_code_id'] string The user's postal (ZIP) code
	 *		-  $data['country_id'] string The country_id for the user
     */
	public function saveConsumer($data)
	{
		$userId=$data['user_id'];
		if (isset($data['user_address_id'])) {
			$userAddressId=$data['user_address_id'];
		} else {
			$userAddressId=null;			
		}
		$this->getAdapter()->beginTransaction();
		try {
			// First let's update the user's name
			$userData = array(
				'first_name' => $data['first_name'], 
				'last_name' => $data['last_name']
			);
			
			if (!G_Session_User::getRegistrationComplete()) {
				$userData['registration_status_id']='consumer_main';
			}
			
			$where=$this->getAdapter()->quoteInto('user_id = ?', $userId);
			$result= $this->update($userData, $where);

			// Next we update the user's address
			$userAddress = new Model_UserAddress_Table();
			
			$addressData = array (
				'user_id' => $data['user_id'],
				'street1' => $data['street1'],
				'street2' => $data['street2'],
				'state_id' => $data['state_id'],
				'city' => $data['city'],
				'postal_code_id' => $data['postal_code_id'],
				'country_id' => $data['country_id']
			);
			
			if (!$userAddressId) {
				$result = $userAddress->insert($addressData);
			} else {
				$where=$this->getAdapter()->quoteInto('user_address_id = ?', $userAddressId);			
				$result = $userAddress->update($addressData, $where);
			}
			
			// Finally we update the user session data
			foreach ($userData as $key=>$val) {
				G_Session_User::set($key,$val);
			}
			
			$this->getAdapter()->commit();
			return true;
		} catch (G_Exception $e) {
			$this->getAdapter()->rollBack();
			return $e;
		}
	}
	
    /**
     * Sets the registration status so we know where the user is in the registration process
	 *
     * @param int $userId The user_id for the user
     * @param string $registrationStatus the registration_status_id. Possible values include:
     *   - consumer_complete: Consumer Registration Complete
     *   - consumer_main: Main Page of Consumer Form
     *   - consumer_registration: Initial Registration Page for Consumers (username/email/password)
     *   - sp_availability: Service Provider Availability 
     *   - sp_complete: Service Provider Complete
     *   - sp_credentials: Service Provider Credentials
     *   - sp_description: Service Provider Description
     *   - sp_faq: Service Provider FAQ
     *   - sp_fees: Service Provider Fees
     *   - sp_languages: Service Provider Languages
     *   - sp_location: Service Provider Location
     *   - sp_main: Service Provider Main
     *   - sp_registration: Initial Registration Page for Service Providers (username/email/password)
     */

	public function setRegistrationStatus($userId,$registrationStatus)
	{
		try {
			$where=$this->getAdapter()->quoteInto('user_id = ?', $userId);
			$this->update(array('registration_status_id'=>$registrationStatus), $where);
			return true;
		} catch (G_Exception $e) {
			return false;
		}
	}

    /**
     * Sets the registration status so we know where the user is in the registration process
	 *
     * @param int $userId The user_id for the user
     * @param string $firstName the user's first name.
     * @param string $lastName the user's last name.
     * @param string $companyName the user's company.
     * @param string $email the company's email address.
     * @param string $phone the company's phone number.
     * @param string $fax the company's fax.
     * @param string $website the company's website.
     * @param string $street1 the first line of the company's street address.
     * @param string $street2 the second line of the company's street address.
     * @param string $city the company's city.
     * @param string $state the company's state/province.
     * @param string $postalCode the company's postal (ZIP) code.
     * @param string $country the company's country.
     */

	public function saveServiceProvider ($userId, $firstName, $lastName, $companyName, 
		$email, $phone, $fax, $website, $street1, $street2, $city, $state, $postalCode, $country)
	{
		$this->getAdapter()->beginTransaction();
		
		try {
			/*
			Since the user is already added, we can add a business first, then tie them together with
			a user_business record
			*/
			$business = new Model_Business();
			$businessId = $business->insert(
				array('name' => $companyName, 'account_type_id' => 'prerelease', 
				'website' => $website));
				
			$userBusiness = new Model_UserBusiness();
			$userBusiness->insert(array('user_id'=>$userId,'business_id'=>$businessId,'business_role_id'=>'admin'));
	
			/*
			At some point we may wish to support multiple locations for the business, so we'll tie the business_address to a business_location and not directly to the business. Ditto for the email address, phone and fax
			*/
			$businessLocation = new Model_BusinessLocation();
			$businessLocationId = $businessLocation->insert(
				array('business_id' => $businessId));
	
			$businessAddress = new Model_BusinessAddress();
			$businessAddress->insert(
				array('business_location_id' => $businessLocationId, 
					'street1' => $street1, 'street2' => $street2, 
					'state_id' => $state, 'city' => $city, 'postal_code_id' => $postalCode, 
					'country_id' => $country));
			
			$businessEmail = new Model_BusinessEmail();
			$businessEmail->insert(
				array('business_location_id' => $businessLocationId, 
				'email' => $email));
				
			if ($phone) {
				$businessPhone = new Model_BusinessPhone();
				$businessPhone->insert(
					array('phone_type_id' => 'work', 
					'business_location_id' => $businessLocationId, 
					'phone_number' => $phone));
			}
 
			if ($fax) {
				$businessFax = new Model_BusinessPhone();
				$businessFax->insert(
					array('phone_type_id' => 'fax', 
					'business_location_id' => $businessLocationId, 'phone_number' => $fax));
			}
				
			$where=$this->getAdapter()->quoteInto('user_id = ?',$userId);
			$data = array('first_name' => $firstName, 'last_name' => $lastName, 'email'=>$email);
			
			if (!G_Session_User::getRegistrationComplete()) {
				$data['registration_status_id']='sp_main';
			}
			
			$this->update($data, $where);
			
			G_Session_Business::setBusinessId($businessId);
			G_Session_User::set('first_name',$firstName);
			G_Session_User::set('last_name',$lastName);
			G_Session_User::set('email',$email);
			
			if (!G_Session_User::getRegistrationComplete()) {
				G_Session_User::set('registration_status_id','sp_main');
			}
			
			$this->getAdapter()->commit();
			return true;
			
		} catch (G_Exception $e) {
			$this->getAdapter()->rollback();
			return $e;
		}
	}
}
