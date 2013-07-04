<?php
/**
 * Interface to be implemented when creating models for data query and manipulation. Basically, I want to abstract the data layer so it isn't tied to a specific database or even using a database
 * at all. In theory, an implementation of this interface could use XML or just write to RAM like Memcached does though in practice some sort of RDBMS will probably always be desirable at least
 * for most parts of this application.
 * In essence the various model classes should work like this:
 * H_Model_Table_Interface => Describes the basic methods necessary to build a model
 * H_Model => Implements H_Model_Interface, specifically tying it to the Zend_Db_Table
 * Model_ModelName => A generic model that extends H_Model. This should not be dependent on any database. Validation should occur her, but actual CRUD operations are passed to Model_ModelName_Table
 * Model_ModelName_Table => A Zend_Db_Table class that is called by Model_ModelName. All it does is CRUD
 * Model_ModelName_TableRow => A Zend_Db_TableRow class that is called by Model_ModelName_Table
 *
 * @author		Aaron Smith
 * @copyright	2012 Aaron Smith. All rights reserved.
 * @version		1.0
 *
 */
interface H_Model_Table_Interface
{
    /**
     * Searches a model by a specific parameter
	 *
     * @param array $dataArray an array of parameters to search by (e.g., to search for first_name="Aaron" you'd set $dataArray=array('first_name'=>Aaron);
     * @param string $orderBy field to sort by
     */
	public function getBy(array $dataArray,$orderBy=null);

    /**
     * Searches a model by the id (primary key)
	 *
     * @param mixed $id The ID to search by
     */
	public function getById($id);

   /**
     * Returns all rows for a model
	 *
     * @param string $orderBy Field to sort by
     */
	public function getAll($orderBy=null);

   /**
     * Insert a record into the model
	 *
     * @param array $data The data to insert into the Model
     */
	public function insert(array $data);

   /**
     * Update a record in a model
	 *
     * @param array $data The data to update
	 * @param array $where The conditions to find the rows to update in the format array(column=>value)
     */
	public function update(array $data,$where);

   /**
     * Update a record in a model based on the record's ID
	 *
     * @param array $data The data to update
	 * @param mixed $id The id of the record to update
     */
	public function updateById(array $data,$id);

   /**
     * Delete a record in a model
	 *
	 * @param array $where The conditions to find the rows to delete in the format array(column=>value)
     */
	public function delete($where);

   /**
     * Delete a record in a model based on the record's ID
	 *
	 * @param mixed $id The id of the record to delete
     */
	public function deleteById($id);
}
