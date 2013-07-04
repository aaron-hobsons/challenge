<?php
/**
 * Teacher Model
 *
 * @author		Aaron Smith
 * @copyright	2013 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */

class Model_Teacher extends Model_Person
{
	protected $_tableName='';
	/**
	 * The teacher's title
	 *
	 * @var string
	 * @acccess protected
	 */
	protected $_title;

	/**
	 * The teacher's classes
	 *
	 * @var array
	 * @acccess protected
	 */
	
	protected $_classes=array();
	
	/**
	 * Sets the teacher's title
	 * @param string The teacher's title
	 */
	public function setTitle($title)
	{
		$this->_title=$title;
	}
	
	/**
	 * Returns the teachers title
	 *
	 * @return string The title
	 */
	public function getTitle()
	{
		return $this->_title;
	}
	
	/**
	 * Adds a class for a teacher
	 * @param string The teacher's title
	 */
	public function addClass($classTitle)
	{
		$class=new Model_Class();
		$class->setTitle($classTitle);
		$this->_classes[]=$class;
	}
	
	/**
	 * Returns an array with class objects for a teacher
	 *
	 * @return array Array of Model_Class objects
	 */
	public function getClasses()
	{
		return $this->_classes;
	}

	/**
	 * Returns an array with class titles for a teacher
	 *
	 * @return array Array of a teacher's class titles
	 */
	public function getClassTitles()
	{
		$classTitles=array();
		foreach ($this->_classes as $class) {
			$classTitles[]=$class->getTitle();
		}
		return $classTitles;
	}
	
	/**
	 * Returns formatted information about a teacher which can then be printed
	 *
	 * @return string The formatted information about the teacher
	 */
	public function getInfo()
	{
		$title=$this->getTitle();
		$name=$this->getName();
		$birthDate=$this->getBirthDate();
		
		// Classes when displayed should be indented, with each appearing on a new line
		$classes="\t".implode("\n\t",$this->getClassTitles());

		$output=<<<EOT
<teacher>
name: $title $name
birthday: $birthDate
classes:
$classes
EOT;
		return $output;
	}	
}