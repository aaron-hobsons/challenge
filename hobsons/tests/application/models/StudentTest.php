<?php
/**
 * Student Test
 *
 * @author		Aaron Smith
 * @copyright	2013 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */

class StudentTest extends PHPUnit_Framework_TestCase
{
	protected $_student = null;
	
	public function setUp()
	{
		$this->_student = new Model_Student;
	}
	
	public function tearDown(){
		unset($this->_student);
	}
	
	public function testPrintInfo()
	{
		$this->_student->setName('John Doe');
		$this->_student->setBirthDate('02-02-1990');
		$this->_student->setStudentId('9912345US');
		$this->_student->setGender(1);
		
		$expected=<<<EOD
<student>
id: 9912345US
name: John Doe
gender: M
birthday: 02-02-1990
EOD;
		$actual=$this->_student->getInfo();
		$this->assertEquals($expected, $actual);
	}
	
	public function testIsValidStudentId()
	{
		$this->_student->setStudentId('9912345US');
		$expected=true;
		$actual=$this->_student->isValidStudentId();
		$this->assertEquals($expected, $actual);
	}
}