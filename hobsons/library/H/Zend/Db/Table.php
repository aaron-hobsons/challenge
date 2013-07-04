<?php
/**
 * This class should be extended to do DB inserts. In addition to implementing the H_Model_Table_Interface has specific methods designed for
 * manipulating data in a database using Zend_Db_Table
 *
 * @author		Aaron Smith
 * @copyright	2012 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */
class H_Zend_Db_Table extends Zend_Db_Table_Abstract implements H_Model_Table_Interface
{

    /**
     * Searches a model by a specific parameter
	 *
     * @param array $dataArray an array of parameters to search by (e.g., to search for first_name="Aaron" you'd set $dataArray=array('first_name'=>Aaron);
     * @param string $orderBy field to sort by
     */
	public function getBy(array $dataArray,$orderBy=null)
	{
		$select=$this->select();
		
		if ($orderBy) {
			$select->order($orderBy);
		}
		
		foreach ($dataArray as $key=>$val) {
			$select->where("$key = ?",$val);
		}
		
		$rowset = $this->fetchAll($select);
		return $rowset;
	}

    /**
     * Searches a model by the id (primary key)
	 *
     * @param mixed $id The ID to search by
     */
	public function getById($id)
	{
		if (is_array($this->_primary)) {
			if (is_array($id)) {
				$data=$id;
			} else {
				$data=array();
				$args=func_get_args();
				foreach ($this->_primary as $key=>$field) {
					$data[$field]=$args[$key];
				}
			}
			return $this->find($data);
		} else {
			return $this->find($id);
		}
	}

   /**
     * Returns all rows for a model
	 *
     * @param string $orderBy Field to sort by
     */
	public function getAll($orderBy=null)
	{
		$select=$this->select();
		if ($orderBy) {
			$select->order($orderBy);
		}
		$rowset = $this->fetchAll($select);
		return $rowset;
	}

   /**
     * Insert a record into the model
	 *
     * @param array $data The data to insert into the Model
     */
	public function insert(array $data)
	{
		$data['add_date']=$this->getNow();
		$data['change_date']=$this->getNow();
		return parent::insert($data);
	}

   /**
     * Update a record in a model
	 *
     * @param array $data The data to update
	 * @param array $where The conditions to find the rows to update in the format array(column=>value)
     */
	public function update(array $data,$where)
	{
		$data['change_date']=$this->getNow();
		if(is_array($where)) {
			$w = array();
			foreach($where as $key => $val){
				$w[] = $this->getAdapter()->quoteInto($key . '=?',$val);
			}
			$where = implode(' AND ',$w);
		}
		return parent::update($data,$where);
	}

   /**
     * Update a record in a model based on the record's ID
	 *
     * @param array $data The data to update
	 * @param mixed $id The id of the record to update
     */	
	public function updateById(array $data,$id)
	{
		if(!is_array($this->_primary)){
			$where=$this->_primary.'='.$this->getAdapter()->quote($id);
		}
		return $this->update($data,$where);		
	}

   /**
     * Delete a record in a model based on the record's ID
	 *
	 * @param mixed $id The id of the record to delete
     */
	public function deleteById($id)
	{
		if(!is_array($this->_primary)){
			$where=$this->_primary.'='.$this->getAdapter()->quote($id);
		} else {
			$where=$id;
		}
		$row = $this ->fetchRow($where);
		
		if(!is_null($row)){
			return $row->delete();
		} else {
			return false;
		}
	}
	
	/**
	* For Memcached. Assigns a prefix for namespacing purposes and returns it.
    * @return string The cache prefix
	*/
	protected function _getCachePrefix()
	{
		return 'DB_T_'.$this->_name.'_';
	}
	
	/**
	* Sets a cache value using the cache prefixa as well as the key/val submitted
    * @string $key The key for the cache entry
	* @mixed $val The value of the cache entry
	*/

	protected function _setCache($key,$val)
	{
		$prefix=$this->_getCachePrefix();
		H_Memcached::set($prefix.$key,$val);
	}
	
	/**
	* Returns a cache value based on the supplied key
	* @string @key The cache key
	*/
	protected function _getCache($key) 
	{
		$prefix=$this->_getCachePrefix();
		$result=H_Memcached::get($prefix.$key);
		return $result;
	}
	
	/**
	* Starts a transaction
	*/
	public function beginTransaction()
	{
		return $this->getAdapter()->beginTransaction();
	}

	/**
	* Commits a transaction
	*/	
	public function commit()
	{
		return $this->getAdapter()->commit();
	}
	
	/**
	* Rolls back a transaction
	*/
	public function rollBack()
	{
		return $this->getAdapter()->rollBack();
	}
	
	/**
	* Returns the MySQL NOW() function for use in Zend queries
	*/
	public function getNow()
	{
		return new Zend_Db_Expr('NOW()');
	}
	
	public function splitData($data,$keys)
	{
		if (is_string($keys)) {
			$keys=array($keys);
		}
		$newData=array();
		foreach ($keys as $key) {
			if (!isset($data[$key])) {
				throw new H_Exception($key.' does not exist in the data array');	
			} else {
				$newData[$key]=$data[$key];
			}
		}
		return $newData;
	}
}
