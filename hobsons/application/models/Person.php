<?php
/**
 * Abstract Person Model
 *
 * @author		Aaron Smith
 * @copyright	2013 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */

abstract class Model_Person extends H_Model  {
	/*
	 * Here are the general properties of the class. In general, I tend to make properties private/protected unless I have a
	 * good reason not to. I'll then supply some settor/accessor methods. 
	 */
	
	/**
	 * A person's first name. I don't like storing names as single fields as it renders
	 * even simple operations like sorting by last name impossible
	 *
	 * @var string
	 * @acccess protected
	 */
	protected $_firstName;
	
	/**
	 * A person's last name. Self-explanatory.
	 *
	 * @var string
	 * @acccess protected
	 */
	protected $_lastName;
	
	/**
	 * The person's birthdate
	 *
	 * @var string
	 * @acccess protected
	 */
	protected $_birthDate;
	
	/**
	 * The person's gender. I've made this an integer because I'm using the gender standard ISO 5218
	 * rather than just M/F or something like that.
	 *
	 * @var int
	 * @acccess protected
	 */
	protected $_gender;
	
	/**
	 * Sets the first name
	 * @param string The person's first name
	 */
	public function setFirstName($firstName)
	{
		$this->_firstName=$firstName;
	}

	/**
	 * Returns firstName
	 *
	 * @return string The first name
	 */
	public function getFirstName()
	{
		return $this->_firstName;
	}
	
	/**
	 * Sets the last name
	 * @param string The person's last name
	 */
	public function setLastName($lastName)
	{
		$this->_lastName=$lastName;
	}
	
	/**
	 * Returns last name
	 *
	 * @return string The last name
	 */
	public function getLastName()
	{
		return $this->_lastName;
	}
	
	/**
	 * Sets the person's date of birth. Note: I made the method
	 * setBirthDate() instead of setBirtDate (as in the exercise)
	 * as it is easier to remember a correctly spelled method than
	 * an incorrectly spelled method.
	 * 
	 * @param string The person's date of birth
	 */
	public function setBirthDate($birthDate)
	{
		$this->_birthDate=$birthDate;
	}
	
	/**
	 * Returns the person's date of birth
	 *
	 * @return string The date of birth
	 */
	public function getBirthDate()
	{
		return $this->_birthDate;
	}

	/**
	 * Sets the person's gender. Should be an integer corresponding to ISO 5218:
	 * 
	 * 0 = not known
	 * 1 = male
	 * 2 = female
	 * 9 = not applicable
	 * 
	 * @param int The person's gender
	 */
	public function setGender($gender)
	{
		$this->_gender=$gender;
	}
	
	/**
	 * Returns the person's gender
	 *
	 * @return string The gender
	 */
	public function getGender()
	{
		return $this->_gender;
	}
	
	/**
	 * Sets the person's name
	 * @param string The person's name
	 */
	public function setName($name)
	{
		$nameArray=explode(' ',$name);
		if (isset($name[0])) {
			$this->setFirstName($nameArray[0]);
		}		

		if (isset($name[1])) {
			$this->setLastName($nameArray[1]);
		}		
	}
	
	/**
	 * Returns a person's name, the concatenation of his/her firstName and lastName
	 *
	 * @return string The first name and last name of the person
	 */
	public function getName()
	{
		return $this->getFirstName().' '.$this->getLastName();		
	}
	
	/**
	 * Get the characteristics of the person
	 *
	 */
	abstract public function getInfo();

	/**
	 * Output the characteristics of the person
	 *
	 */
	public function printInfo()
	{
		echo $this->getInfo();
	}
}