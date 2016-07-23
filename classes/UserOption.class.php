<?php
/**
 * UserOption.class.php
 *
 * @category TeamCal Neo 
 * @version 0.9.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides objects and methods to manage the user-option table
 */
class UserOption
{
   var $db = '';
   var $table = '';
   var $archive_table = '';
   var $id = NULL;
   var $username = NULL;
   var $option = NULL;
   var $value = NULL;
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_user_option'];
      $this->archive_table = $CONF['db_table_archive_user_option'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Archives all records for a given user
    *
    * @param string $username Username to archive
    * @return bool Query result or false
    */
   function archive($username)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Restores all records for a given user
    *
    * @param string $name Username to restore
    * @return bool Query result or false
    */
   function restore($username)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a record exists
    *
    * @param string $username Username to find
    * @param boolean $archive Whether to search in archive table
    * @return bool True if found, false if not
    */
   function exists($username = '', $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      
      if ($result and $query->fetchColumn())
      {
         return true;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a new user-option record
    *
    * @param string $username Username
    * @param string $option Option name
    * @param string $value Option value
    * @return bool Query result or false
    */
   function create($username, $option, $value)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, option, value) VALUES (:val1, :val2, :val3)');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $option);
      $query->bindParam('val3', $value);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all records
    *
    * @param boolean $archive Whether to search in archive table
    * @return bool Query result or false
    */
   function deleteAll($archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username <> "admin"');
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a user-option record by ID from local class variable
    *
    * @return bool Query result or false
    */
   function deleteById()
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $this->id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all records for a given user
    *
    * @param string $username Username to delete
    * @return bool Query result or false
    */
   function deleteByUser($username = '', $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Delete an option records for a given user
    *
    * @param string $username Username to find
    * @param string $option Option to delete
    * @return bool Query result or false
    */
   function deleteUserOption($username, $option)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE username = :val1 AND option = :val2');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $option);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks the existence of an option for a given user
    *
    * @param string $username Username to find
    * @param string $option Option to find
    * @return string TRUE if exists, FALSE if not
    */
   function hasOption($username, $option)
   {
      $query = $this->db->prepare('SELECT :val1 FROM ' . $this->table . ' WHERE `username` = :val2');
      $query->bindParam('val1', $option);
      $query->bindParam('val2', $username);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return true;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Finds the value of an option for a given user
    *
    * @param string $username Username to find
    * @param string $option Option to find
    * @return string Value of the option (or FALSE if not found)
    */
   function read($username, $option, $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE `username` = :val1 AND `option` = :val2');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $option);
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
    * Save a user option or creates it if not exists
    *
    * @param string $username Username to find
    * @param string $option Option to find
    * @param string $value New value
    * @return bool $result2 Query result
    */
   function save($username, $option, $value)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE `username` = "' . $username . '" AND `option` = "' . $option . '"');
      $result = $query->execute();
      
      // echo "<script type=\"text/javascript\">alert(\"Debug:
      // ".$username."|".$option."|".$result."|".$query->fetchColumn()."\");</script>";
      
      if ($result and $query->fetchColumn())
      {
         $query2 = $this->db->prepare('UPDATE ' . $this->table . ' SET `value` = :val1 WHERE `username` = :val2 AND `option` = :val3');
         $query2->bindParam('val1', $value);
         $query2->bindParam('val2', $username);
         $query2->bindParam('val3', $option);
         $result2 = $query2->execute();
         return $result2;
      }
      else
      {
         $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (`username`, `option`, `value`) VALUES (:val1, :val2, :val3)');
         $query2->bindParam('val1', $username);
         $query2->bindParam('val2', $option);
         $query2->bindParam('val3', $value);
         $result2 = $query2->execute();
         return $result2;
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Finds the boolean or yes/no value of an option for a given user
    *
    * @param string $username Username to find
    * @param string $option Option to find
    * @return bool True or false
    */
   function true($username, $findoption)
   {
      $query = $this->db->prepare('SELECT value FROM ' . $this->table . ' WHERE `username` = :val1 AND `option` = :val2');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $option);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         if (trim($row['value']) != "" or trim($row['value']) != "no") return true;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates defregion name in all records
    *
    * @param string $regionold Old regionname
    * @param string $regionnew New regionname
    * @return bool $result Query result
    */
   function updateRegion($regionold, $regionnew = 'default')
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET value = :val1 WHERE option = :val2 AND value = :val3');
      $query->bindParam('val1', $regionnew);
      $query->bindParam('val2', 'defregion');
      $query->bindParam('val3', $regionold);
      $result = $query->execute();
      return $result;
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
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->archive_table);
      $result = $query->execute();
      return $result;
   }
}
?>
