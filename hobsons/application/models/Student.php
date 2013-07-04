<?php
/**
 * Student Model
 *
 * @author		Aaron Smith
 * @copyright	2013 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */

class Model_Student extends Model_Person
{
	/**
	 * The student's ID
	 *
	 * @var string
	 * @acccess protected
	 */
	
	protected $_studentId;
	
	/**
	 * Sets the student's ID
	 * @param string $studentId The student's ID
	 */
	public function setStudentId($studentId)
	{
		$this->_studentId=$studentId;
	}
	
	/**
	 * Returns the person's ID number
	 *
	 * @return string The student ID
	 */
	public function getStudentId()
	{
		return $this->_studentId;
	}
	
	/**
	 * Returns formatted information about a student which can then be printed
	 *
	 * @return string The formatted information about the student
	 */
	public function getInfo()
	{
		$studentId=$this->getStudentId();
		$name=$this->getName();
		switch ($this->getGender()) {
			case 1:
				$gender='M';
				break;
			case 2:
				$gender='F';
				break;
			case 9:
				$gender='Not Applicale';
				break;
			default:
				$gender='Not Known';
				break;
		}
		$birthDate=$this->getBirthDate();

		$output=<<<EOT
<student>
id: $studentId
name: $name
gender: $gender
birthday: $birthDate
EOT;
		return $output;
	}
	
	public function isValidStudentId()
	{
		$studentId=$this->getStudentId();
		$regex='/^
					(88|99)		# Begins with an 88 or 99
					[0-9]{5}	# Continues with five digits
					[A-Z]{2}	# Ends with two uppercase letters
				$/x';
		
		if (preg_match($regex,$studentId)) {
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Loads the student object parameters by doing a lookup using the Student's ID
	 * @param string $studentId The student's ID
	 */
	public function getStudentById($studentId)
	{
		$student=$this->getTable()->getById($studentId);
		
		$this->setFirstName($student->first_name);
		$this->setLastName($student->last_name);
		$this->setGender($student->gender);
		$this->setBirthDate($student->birthDate);
	}
}