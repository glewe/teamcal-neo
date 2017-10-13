<?php
/**
 * Config.class.php
 *
 * @category TeamCal Neo 
 * @version 1.6.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed.
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the config database table
 */
class Config
{
   public $id = NULL;
   public $name = '';
   public $value = '';
   
   private $db = '';
   private $table = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   public function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_config'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Read the value of an option
    *
    * @param string $name Name of the option
    * @return string Value of the option or false if not found
    */
   public function read($name)
   {
      $query = $this->db->prepare('SELECT value FROM ' . $this->table . ' WHERE `name` = "' . $name .'"');
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['value'];
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Save a value
    *
    * @param string $name Name of the option
    * @param string $value Value to save
    * @return boolean $result Query result or false
    */
   public function save($name, $value)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE `name` = "' . $name .'"');
      $result = $query->execute();
      
      if ($result and $query->fetchColumn())
      {
         $query2 = $this->db->prepare('UPDATE ' . $this->table . ' SET value = :val2 WHERE name = :val1');
      }
      else
      {
         $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (`name`, `value`) VALUES (:val1, :val2)');
      }
      $query2->bindParam('val1', $name);
      $query2->bindParam('val2', $value);
      $result2 = $query2->execute();
      return $result2;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Optimize table
    *
    * @return boolean $result Query result
    */
   public function optimize()
   {
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
      $result = $query->execute();
      return $result;
   }
}
?>
