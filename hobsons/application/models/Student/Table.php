<?php
/**
 * Simple model based on Zend_DB_Table_Abstract
 *
 * @author		Aaron Smith
 * @copyright	2013 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */
class Model_Student_Table extends H_Zend_Db_Table
{

    /**
     * The name of the table
	 *
     * @var string
     */
	protected $_name = 'student';

    /**
     * The name of the primary key
	 *
     * @var string
     */
	protected $_primary='student_id';

    /**
     * The name of the class for the associated row class
	 *
     * @var string
     */
	protected $_rowClass='Model_Student_TableRow';
}
