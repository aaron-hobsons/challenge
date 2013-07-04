<?php
/**
 * Basic model class. Should be extended -- for more information on using this see H_Model_Table_Interface
 *
 * @author		Aaron Smith
 * @copyright	2013 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */
class H_Model {
	/* Set these when extending this class */

    /**
     * The database classed used for CRUD operations
	 *
     * @var string
	 * @acccess protected
     */
	protected $_tableClass;

   /**
     * Filters on various fields are set here. In this case, we want to strip all HTML tags and trim whitespace off of all values
	 *
     * @var array
	 * @acccess protected
     */
	protected $_filters;

    /**
     * Validators to ensure that the data is set is correct.
	 *
     * @var array
	 * @acccess protected
     */
	protected $_validators;

    /**
     * Dummy fields are not ever inserted in the database, but they may be validated against. In this case, we have
	 * a 'confirm_password' field that is compared with the password field, but is not inserted
	 *
     * @var array
	 * @acccess protected
     */	
	protected $_dummyFields=array();

	/* Leave these alone when extending this class */

	/**
	* The table object instantiated by this class
	* @var mixed
	*/
	protected $_table;
	
	/**
	 * 
	 * If we call an unrecognized method, we'll return the results of that method for the 
	 * getTable() object
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $arguments
	 */
	public function __call($name,$arguments)
	{
		return call_user_func_array(array($this->getTable(),$name), $arguments);
	}
	
	/**
	 * Returns the appropriate table object
	 *
	 * @return H_Model_Table_Interface
	 */
	public function getTable()
	{
		if ($this->_table===null) {
			$this->_table=new $this->_tableClass;
		}
		return $this->_table;
	}

	/**
	 * Sets the validators. Uses Zend_Filter_Input
	 * @param array $validators The validators being used
	 */
	public function setValidators($validators)
	{
		$this->_validators=$validators;
	}

	/**
	 * Sets the filters. Uses Zend_Filter Input
	 * @param array $filters The filters being used
	 */
	public function setFilters($filters)
	{
		$this->_filters=$filters;
	}

	/**
	 * Returns the validators being checked
	 * @return array The validators being checked
	 */
	public function getValidators()
	{
		return $this->_validators;
	}

	/**
	 * Returns the filters being checked
	 * @return array The filters being checked
	 */	
	public function getFilters() 
	{
		return $this->_filters;
	}

	/**
	 * Validates the data beings set for the model based on the validators/filters set.
	 * @param array $data The data being validated
	 * @options array Any specific validation options set
	 */
	public function validate(array $data,$options = array()) 
	{	
		$options['filterNamespace']='H_Filter';
		$options['validatorNamespace']='H_Validate';
		
		$input = new Zend_Filter_Input($this -> _filters,$this -> _validators,$data,$options);
		
		if  ($input->hasInvalid() || $input->hasMissing()) {
			$errorMessages = $input->getMessages();
			$error=new H_Error($errorMessages);
		} else {
			$data=array_merge($input->getUnescaped(),$input->getUnknown());
			if ($this->_dummyFields) {
				foreach ($this->_dummyFields as $field) {
					if (isset($data[$field])) {
						unset($data[$field]);
					}
				}
			}
			return $data;
		}
		return $error;
	}

	/**
	 * Returns true if there is an error with the model or false if there is no error
	 * @param array $data The data being validated
	 */	
	public function isError($data)
	{
		if (is_a($data,'H_Error')) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * Searches a model by a specific parameter
	 *
     * @param array $dataArray an array of parameters to search by (e.g., to search for first_name="Aaron" you'd set $dataArray=array('first_name'=>Aaron);
     * @param string $orderBy field to sort by
     */
	public function getBy($data,$orderBy=null)
	{
		return $this->getTable()->getBy($data,$orderBy);
	}
	
    /**
     * Searches a model by the id (primary key)
	 *
     * @param mixed $id The ID to search by
     */
	public function getById($id)
	{
		return $this->getTable()->getById($id);		
	}
	
   /**
     * Returns all rows for a model
	 *
     * @param string $orderBy Field to sort by
     */
	public function getAll($data)
	{
		return $this->getTable()->getAll($data);
	}

   /**
     * Insert a record into the model
	 *
     * @param array $data The data to insert into the Model
     */
	public function insert($data)
	{
		$data = $this->validate($data);
		if(is_a($data,'H_Error')){
			return $data;		
		} else {
			$id = $this->getTable()->insert($data);
			return $id;
		}
	}

   /**
     * Update a record in a model
	 *
     * @param array $data The data to update
	 * @param array $where The conditions to find the rows to update in the format array(column=>value)
     */	
	public function update($data,$where)
	{
		$data = $this->validate($data);
		if(is_a($data,'H_Error')) {
			return $data;
		} else {
			return $this->getTable()->update($data);
		} 
	}

   /**
     * Update a record in a model based on the record's ID
	 *
     * @param array $data The data to update
	 * @param mixed $id The id of the record to update
     */	
	public function updateById($data,$id)
	{
		$data = $this -> isValidUpdate($data);
		if(is_a($data,'H_Error')) {
			return $data;
		} else {
			return $this->getTable()->updateById($data, $id);
		} 
	}

   /**
     * Delete a record in a model
	 *
	 * @param array $where The conditions to find the rows to delete in the format array(column=>value)
     */
	public function delete($where)
	{
		return $this->getTable()->delete($where);
	}

   /**
     * Delete a record in a model based on the record's ID
	 *
	 * @param mixed $id The id of the record to delete
     */	
	public function deleteById($id)
	{
		return $this->getTable()->deleteById($id);
	}
}
