<?php
/**
 * Teacher Test
 *
 * @author		Aaron Smith
 * @copyright	2013 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */

class TeacherTest extends PHPUnit_Framework_TestCase
{
	protected $_teacher = null;
	
	public function setUp()
	{
		$this->_teacher = new Model_Teacher;
	}
	
	public function tearDown(){
		unset($this->_teacher);
	}
	
	public function testPrintInfo()
	{
		$this->_teacher->setTitle('Mr.');
		$this->_teacher->setName('Robert Smith');
		$this->_teacher->setBirthDate('03-04-1970');
		$this->_teacher->addClass('Physics 101');
		
		$expected=<<<EOD
<teacher>
name: Mr. Robert Smith
birthday: 03-04-1970
classes:
	Physics 101
EOD;
		$actual=$this->_teacher->getInfo();
		$this->assertEquals($expected, $actual);
	}
}