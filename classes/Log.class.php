<?php
/**
 * Log.class.php
 *
 * @category TeamCal Neo 
 * @version 1.8.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed.
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the log database table
 */
class Log
{
   public $id = NULL;
   public $type = NULL;
   public $timestamp = '';
   public $user = '';
   public $event = '';
   
   private $db = '';
   private $table = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   public function __construct()
   {
      global $C, $CONF, $DB;
      $this->C = $C;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_log'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes log records by date range
    *
    * @param string $from ISO formatted start date
    * @param string $to ISO formatted end date
    * @return boolean Query result
    */
   public function delete($from = '', $to = '')
   {
      $records = array ();
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE (timestamp >= :val1 AND timestamp <= :val2)');
      $query->bindParam('val1', $from);
      $query->bindParam('val2', $to);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
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
    * Reads records by date range
    *
    * @param string $sort Sort order (ASC or DESC)
    * @param string $from ISO formatted start date
    * @param string $to ISO formatted end date
    * @return array Array of records
    */
   public function read($sort='DESC', $from = '', $to = '')
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE (timestamp >= :val1 AND timestamp <= :val2) ORDER BY timestamp ' . $sort);
      $query->bindParam('val1', $from);
      $query->bindParam('val2', $to);
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
    * Creates a log record
    *
    * @param string $type Log type
    * @param string $user Username
    * @param string $event Event
    * @return boolean Query result
    */
   public function log($type, $user, $event, $object = '')
   {
      if (!strlen($this->C->read("logLanguage"))) $loglang = 'english';
      else $loglang = $this->C->read("logLanguage");
      
      require (WEBSITE_ROOT . "/languages/" . $loglang . ".log.php");
      
      $myEvent = $LANG[$event] . $object;
      
      if ($this->C->read($type))
      {
         $ts = date("YmdHis");
         $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (type, timestamp, user, event) VALUES (:val1, :val2, :val3, :val4)');
         $query->bindParam('val1', $type);
         $query->bindParam('val2', $ts);
         $query->bindParam('val3', $user);
         $query->bindParam('val4', $myEvent);
         $result = $query->execute();
         return $result;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Optimize table
    *
    * @return boolean Query result
    */
   public function optimize()
   {
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
      $result = $query->execute();
      return $result;
   }
}
?>
