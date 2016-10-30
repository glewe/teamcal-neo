<?php
/**
 * Messages.class.php
 *
 * @category TeamCal Neo 
* @version 1.0.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed.
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the messages table
 */
class Messages
{
   public $id = NULL;
   public $timestamp = '';
   public $username = '';
   public $text = '';
   public $popup = '0';
   public $type = 'info';
   
   private $table = '';
   private $db = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   public function __construct()
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
    * @return integer Last inserted record ID
    */
   public function create($timestamp, $text, $type = 'info')
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (timestamp, text, type) VALUES (:val1, :val2, :val3)');
      $query->bindParam('val1', $timestamp);
      $query->bindParam('val2', $text);
      $query->bindParam('val3', $type);
      $result = $query->execute();
      
      if ($result)
      {
         return $this->db->lastInsertId();
      }
      else 
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a message
    *
    * @param string $id Record ID to delete
    * @return boolean Query result
    */
   public function delete($id)
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
    * @return boolean Query result
    */
   public function deleteAll()
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
   public function getAll()
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table);
      $result = $query->execute();
      while ( $result and $row = $query->fetch() ) $records[] = $row;
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets all records for a given username linked from the user-message
    * table.
    *
    * @param string $username Username to search for
    * @return array Array with all records
    */
   public function getAllByUser($username)
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
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Optimize table
    *
    * @return boolean Optimize result
    */
   public function optimize()
   {
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
      $result = $query->execute();
      return $result;
   }
}
?>
