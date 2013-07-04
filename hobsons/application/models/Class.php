<?php
/**
 * Class Model
 *
 * @author		Aaron Smith
 * @copyright	2013 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */

class Model_Class extends H_Model
{
	/**
	 * Sets the class's title
	 * @param string The teacher's title
	 */
	public function setTitle($title)
	{
		$this->_title=$title;
	}
	
	/**
	 * Returns the class's title
	 *
	 * @return string The title
	 */
	public function getTitle()
	{
		return $this->_title;
	}
}