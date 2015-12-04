<?php
/**
 * Messages.class.php
 *
 * @category TeamCal Neo 
 * @version 0.3.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the messages table
 */
class Messages
{
   var $table = '';
   var $db = '';
   var $id = NULL;
   var $timestamp = '';
   var $username = '';
   var $text = '';
   var $popup = '0';
   var $type = 'info';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_messages'];
      $this->umtable = $CONF['db_table_user_message'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a message
    *
    * @param string $timestamp Timestamp
    * @param string $username Username
    * @param string $text Text of the message
    * @param string $type Type of the message
    * @return bool Query result or false
    */
   function create($timestamp, $text, $type = 'info')
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (timestamp, text, type) VALUES (:val1, :val2, :val3)');
      $query->bindParam('val1', $timestamp);
      $query->bindParam('val2', $text);
      $query->bindParam('val3', $type);
      $result = $query->execute();
      return $this->db->lastInsertId();
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a message
    *
    * @param string $id Record ID to delete
    * @return bool Query result or false
    */
   function delete($id)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Deletes all records
    *
    * @return bool $result Query result
    */
   function deleteAll()
   {
      $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Reads all records into an array
    *
    * @return array $records Array with all records
    */
   function getAll()
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table);
      $result = $query->execute();
      while ( $result and $row = $query->fetch() ) $records[] = $row;
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Reads all records for a given username linked from the user-message
    * table.
    *
    * @param string $username Username to search for
    * @return array $records Array with all records
    */
   function getAllByUser($username)
   {
      $records = array ();
       
      $query = $this->db->prepare(
            'SELECT * FROM ' . $this->table . ' as a 
             JOIN ' . $this->umtable . ' as um ON um.msgid = a.id 
             WHERE um.username = :val1 ORDER BY timestamp DESC'
      );
      $query->bindParam('val1', $username);
      $result = $query->execute();
   
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[] = $row;
         }
      }
      //print_r($records);
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Optimize table
    *
    * @return bool Optimize result
    */
   function optimize()
   {
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
      $result = $query->execute();
      return $result;
   }
}
?>
