<?php
/**
 * Allowances.class.php
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
 * Provides objects and methods to interface with the allowance table
 */
class Allowances
{
   var $db = NULL;
   var $table = '';
   var $archive_table = '';
   var $id = NULL;
   var $username = '';
   var $absid = 0;
   var $lastyear = 0;
   var $curryear = 0;
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_allowances'];
      $this->archive_table = $CONF['db_table_archive_allowances'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Archives all records for a given user
    *
    * @param string $username Username to archive
    * @return bool $result Query result
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
    * @return bool $result Query result
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
    * @return bool True if exists
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
    * Creates an allowance record
    *
    * @return bool Query result
    */
   function create()
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, absid, lastyear, curryear) VALUES (:val1, :val2, :val3, :val4)');
      $query->bindParam('val1', $this->username);
      $query->bindParam('val2', $this->absid);
      $query->bindParam('val3', $this->lastyear);
      $query->bindParam('val4', $this->curryear);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates an allowance record from the local variables
    *
    * @return bool Query result
    */
   function update()
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET username = :val1, absid = :val2, lastyear = :val3, curryear = :val4 WHERE id = :val5');
      $query->bindParam('val1', $this->username);
      $query->bindParam('val2', $this->absid);
      $query->bindParam('val3', $this->lastyear);
      $query->bindParam('val4', $this->curryear);
      $query->bindParam('val5', $this->id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes an allowance record
    *
    * @return bool Query result
    */
   function delete()
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $this->id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all allowance records for a given absence type
    *
    * @param string $absid Absence ID to delete
    * @return bool Query result
    */
   function deleteAbs($absid = '')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE absid = :val1');
      $query->bindParam('val1', $absid);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all records
    *
    * @param boolean $archive Whether to use the archive table
    * @return bool Query result or false
    */
   function deleteAll($archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table);
      $result = $query->execute();
      
      if ($result and $query->fetchColumn())
      {
         $query = $this->db->prepare('TRUNCATE TABLE ' . $table);
         $result = $query->execute();
         return $result;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all allowance records for a given username
    *
    * @param string $username Username to delete
    * @return bool Query result
    */
   function deleteByUser($username = '', $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :val1');
      $query->bindParam('val1', $this->username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Finds the allowance record for a given username and absence type and
    * fills the local variables with the values found in database
    *
    * @param string $username Username to find
    * @param string $absid Absence ID to find
    * @return bool True if allowance exists, false if not
    */
   function find($username, $absid)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1 AND absid = :val2');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $absid);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         $this->id = $row['id'];
         $this->username = $row['username'];
         $this->absid = $row['absid'];
         $this->lastyear = $row['lastyear'];
         $this->curryear = $row['curryear'];
         return true;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates the last year amount for a user/absence
    *
    * @param string $username Username to find
    * @param string $absid Absence ID to find
    * @param integer $lastyear New value for last year
    * @return bool Query result
    */
   function updateLastyear($username, $absid, $lastyear)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET lastyear = :val1 WHERE username = :val2 AND absid = :val3');
      $query->bindParam('val1', $lastyear);
      $query->bindParam('val2', $username);
      $query->bindParam('val3', $absid);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates the current year amount for a user/absence
    *
    * @param string $username Username to find
    * @param string $absid Absence ID to find
    * @param integer $curryear New value for current year
    * @return bool Query result
    */
   function updateCurryear($username, $absid, $curryear)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET curryear = :val1 WHERE username = :val2 AND absid = :val3');
      $query->bindParam('val1', $curryear);
      $query->bindParam('val2', $username);
      $query->bindParam('val3', $absid);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates the ast year and current year amount for a user/absence
    *
    * @param string $username Username to find
    * @param string $absid Absence ID to find
    * @param integer $lastyear New value for last year
    * @param integer $curryear New value for current year
    * @return bool Query result
    */
   function updateAllowance($username, $absid, $lastyear, $curryear)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET lastyear = :val1, curryear = :val2 WHERE username = :val3 AND absid = :val4');
      $query->bindParam('val1', $lastyear);
      $query->bindParam('val2', $curryear);
      $query->bindParam('val3', $username);
      $query->bindParam('val4', $absid);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Optimize table
    *
    * @return bool Query result
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
