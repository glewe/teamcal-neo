<?php
/**
 * Log.class.php
 *
 * @category TeamCal Neo 
 * @version 0.9.003
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the log database table
 */
class Log
{
   var $db = '';
   var $table = '';
   var $id = NULL;
   var $type = NULL;
   var $timestamp = '';
   var $user = '';
   var $event = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $C, $CONF, $DB;
      $this->C = $C;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_log'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Delete events
    *
    * @param string $from ISO formatted start date
    * @param string $to ISO formatted end date
    */
   function delete($from = '', $to = '')
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
    * Delete all records
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
    * Read events
    *
    * @param integer $sort Sort order (1=DESC, 0=ASC)
    * @param string $from ISO formatted start date
    * @param string $to ISO formatted end date
    * @return array $records Array of events
    */
   function read($sort = 'DESC', $from = '', $to = '')
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
    * Event Logging
    *
    * @param string $type Type of log entry
    * @param string $user Corresponding user name of log entry
    * @param string $event Type of event to log
    * @return bool $result Query result
    */
   function log($type, $user, $event, $object = '')
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
    * @return bool $result Query result
    */
   function optimize()
   {
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
      $result = $query->execute();
      return $result;
   }
}
?>
