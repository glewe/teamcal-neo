<?php
/**
 * Daynotes.class.php
 *
 * @category TeamCal Neo 
 * @version 0.3.003
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides objects and methods to interface with the daynote table
 */
class Daynotes
{
   var $db = '';
   var $table = '';
   var $archive_table = '';
   var $id = NULL;
   var $yyyymmdd = '';
   var $daynote = '';
   var $daynotes = array ();
   var $count = NULL;
   var $username = '';
   var $region = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_daynotes'];
      $this->archive_table = $CONF['db_table_archive_daynotes'];
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
    * @param boolean $archive Whether to use the archive table
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
    * Creates a daynote record from class variables
    *
    * @return bool Query result or false
    */
   function create()
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (yyyymmdd, daynote, username, region) VALUES (:val1, :val2, :val3, :val4)');
      $query->bindParam('val1', $this->yyyymmdd);
      $query->bindParam('val2', $this->daynote);
      $query->bindParam('val3', $this->username);
      $query->bindParam('val4', $this->region);
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
    * Delete an announcement by timestamp
    *
    * @return bool Query result or false
    */
   function deleteAllGlobal()
   {
      $query = $this->db->prepare('DELETE FROM '.$this->table.' WHERE username = "all"');
      $query->bindParam('val1', $timestamp);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all daynotes before (and including) a given day
    *
    * @param string $yyyymmdd 8 character date (YYYYMMDD) to find for deletion
    * @return bool $result Query result
    */
   function deleteBefore($yyyymmdd = '')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE yyyymmdd <= :val1');
      $query->bindParam('val1', $yyyymmdd);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a daynote record by date and username
    *
    * @param string $yyyymmdd 8 character date (YYYYMMDD) to find for deletion
    * @param string $username Userame to find for deletion
    * @param string $region Region to find for deletion
    * @return bool $result Query result
    */
   function deleteByDay($yyyymmdd = '', $username = '', $region = 'default')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE yyyymmdd = :val1 AND username = :val2 AND region = :val3');
      $query->bindParam('val1', $yyyymmdd);
      $query->bindParam('val2', $username);
      $query->bindParam('val3', $region);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a daynote record by id
    *
    * @param string $id ID to find for deletion
    * @return bool $result Query result
    */
   function deleteById($id = '')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all daynotes for a region
    *
    * @param string $region Region to find for deletion
    * @return bool $result Query result
    */
   function deleteByRegion($region = 'default')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE region = :val1');
      $query->bindParam('val1', $region);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all daynotes for a user
    *
    * @param string $uname Username to find for deletion
    * @return bool $result Query result
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
    * Finds a daynote record by date and username and loads values in local class variables
    *
    * @param string $yyyymmdd 8 character date (YYYYMMDD) to find
    * @param string $username Userame to find
    * @return bool $result Query result
    */
   function findByDay($yyyymmdd = '', $username = '', $region = 'default')
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE yyyymmdd = :val1 AND username = :val2 AND region = :val3');
      $query->bindParam('val1', $yyyymmdd);
      $query->bindParam('val2', $username);
      $query->bindParam('val3', $region);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         $this->id = $row['id'];
         $this->yyyymmdd = $row['yyyymmdd'];
         $this->daynote = stripslashes($row['daynote']);
         $this->username = $row['username'];
         $this->region = $row['region'];
         return true;
      }
      return false;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Find all daynotes for a given user and month and load them in daynotes array
    *
    * @param string $yyyy Year to find
    * @param string $mm Month to find
    * @param string $days Number of days in month (used to set end date)
    * @param string $username Username to find
    * @param string $region Region to find
    * @return bool $result Query result
    */
   function findAllByMonthUser($yyyy = '', $mm = '', $days = '', $username = '', $region = 'default')
   {
      if ($days < 10) $days = '0' + "0" . strval($days);
      $startdate = $yyyy . $mm . '01';
      $enddate = $yyyy . $mm . $days;
      
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE yyyymmdd BETWEEN :val1 AND :val2 AND username = :val3 AND region = :val4');
      $query->bindParam('val1', $startdate);
      $query->bindParam('val2', $enddate);
      $query->bindParam('val3', $username);
      $query->bindParam('val4', $region);
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $this->daynotes[$row['username']][$row['yyyymmdd']] = stripslashes($row['daynote']);
         }
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Find all daynotes for all users in a given month and load them in daynotes array
    *
    * @param string $yyyy Year to find
    * @param string $mm Month to find
    * @param string $days Number of days in month (used to set end date)
    * @param string $usernames Array of usernames to find
    * @param string $region Region to find
    * @return bool $result Query result
    */
   function findAllByMonth($yyyy = '', $mm = '', $days = '', $usernames, $region = 'default')
   {
      if ($days < 10) $days = '0' + "0" . strval($days);
      $startdate = $yyyy . $mm . '01';
      $enddate = $yyyy . $mm . $days;
      
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE yyyymmdd BETWEEN :val1 AND :val2 AND username IN(:val3) AND region = :val4');
      $query->bindParam('val1', $startdate);
      $query->bindParam('val2', $enddate);
      $query->bindParam('val3', $usernames);
      $query->bindParam('val4', $region);
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $this->daynotes[$row['username']][$row['yyyymmdd']] = $row['daynote'];
         }
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Finds a daynote record by id and loads values in local class variables
    *
    * @param string $id ID to find
    * @return bool $result Query result
    */
   function findById($id = '')
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         $this->id = $row['id'];
         $this->yyyymmdd = $row['yyyymmdd'];
         $this->daynote = stripslashes($row['daynote']);
         $this->username = $row['username'];
         $this->region = $row['region'];
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates a daynote record from local class variables
    *
    * @return bool $result Query result
    */
   function update()
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET yyyymmdd = :val1, daynote = :val2, username = :val3, region = :val4 WHERE id = :val5');
      $query->bindParam('val1', $this->yyyymmdd);
      $query->bindParam('val2', $this->daynote);
      $query->bindParam('val3', $this->username);
      $query->bindParam('val4', $this->region);
      $query->bindParam('val5', $this->id);
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
