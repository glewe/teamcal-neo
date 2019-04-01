<?php
/**
 * Daynotes.class.php
 *
 * @category TeamCal Neo 
 * @version 2.0.1
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides objects and methods to interface with the daynote table
 */
class Daynotes
{
   public $id = NULL;
   public $yyyymmdd = '';
   public $daynote = '';
   public $daynotes = array();
   public $username = '';
   public $region = '';
   public $color = '';
   public $confidential = '';
    
   private $db = '';
   private $table = '';
   private $archive_table = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   public function __construct()
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
    * @return boolean Query result
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
    * @return boolean Query result
    */
   public function restore($username)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether records for a given user exist
    *
    * @param string $username Username to find
    * @param boolean $archive Whether to use the archive table
    * @return boolean True if found, false if not
    */
   public function exists($username, $archive = false)
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
    * @return boolean Query result or false
    */
   public function create()
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (yyyymmdd, username, region, daynote, color, confidential) VALUES (:val1, :val2, :val3, :val4, :val5, :val6)');
      $query->bindParam('val1', $this->yyyymmdd);
      $query->bindParam('val2', $this->username);
      $query->bindParam('val3', $this->region);
      $query->bindParam('val4', $this->daynote);
      $query->bindParam('val5', $this->color);
      $query->bindParam('val6', $this->confidential);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a daynote record for a given date/username/region
    *
    * @param string $yyyymmdd 8 character date (YYYYMMDD) to find for deletion
    * @param string $username Userame to find for deletion
    * @param string $region Region to find for deletion
    * @return boolean Query result
    */
   public function delete($yyyymmdd = '', $username = '', $region = 'default')
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
    * Deletes all records
    *
    * @param boolean $archive Whether to use the archive table
    * @return boolean Query result or false
    */
   public function deleteAll($archive = FALSE)
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
    * Deletes all daynotes before (and including) a given day
    *
    * @param string $yyyymmdd 8 character date (YYYYMMDD) to find for deletion
    * @return boolean Query result
    */
   public function deleteAllBefore($yyyymmdd = '')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE yyyymmdd <= :val1');
      $query->bindParam('val1', $yyyymmdd);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all daynotes for a region
    *
    * @param string $region Region to find for deletion
    * @return boolean Query result
    */
   public function deleteAllForRegion($region = 'default')
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
    * @return boolean Query result
    */
   public function deleteByUser($username = '', $archive = FALSE)
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
    * Delete an announcement by timestamp
    *
    * @return boolean Query result or false
    */
   public function deleteAllGlobal()
   {
      $query = $this->db->prepare('DELETE FROM '.$this->table.' WHERE username = "all"');
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all daynotes for a date and user
    *
    * @param string $date Date to find for deletion
    * @param string $username Username to find for deletion
    * @return boolean Query result
    */
   public function deleteByDateAndUser($date, $username)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE yyyymmdd = :val1 AND username = :val2');
      $query->bindParam('val1', $date);
      $query->bindParam('val2', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a daynote record by id
    *
    * @param string $id ID to find for deletion
    * @return boolean Query result
    */
   public function deleteById($id)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets a daynote record for a given date/username/region
    *
    * @param string $yyyymmdd Date (YYYYMMDD) to find
    * @param string $username Userame to find
    * @param string $region Userame to find
    * @return boolean Query result
    */
   public function get($yyyymmdd = '', $username = '', $region = 'default', $replaceCRLF = false)
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
         $this->username = $row['username'];
         $this->region = $row['region'];
         if ($replaceCRLF) $this->daynote = str_replace("\r\n","<br>",$row['daynote']);
         else $this->daynote = $row['daynote'];
         $this->color = $row['color'];
         $this->confidential = $row['confidential'];
         return true;
      }
      return false;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets all daynotes for a given user and month and load them in daynotes array
    *
    * @param string $yyyy Year to find
    * @param string $mm Month to find
    * @param string $username Username to find
    * @param string $region Region to find
    * @param boolean $replaceCLRF Flag to replace CRLF with <br>
    * @return boolean Query result
    */
   public function getForMonthUser($yyyy, $mm, $username, $region = 'default', $replaceCRLF = false)
   {
      $number = cal_days_in_month(CAL_GREGORIAN, intval($mm), intval($yyyy));
      $days = sprintf('%02d', $number);
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
            if ($replaceCRLF) $this->daynotes[$row['username']][$row['yyyymmdd']] = str_replace("\r\n","<br>",$row['daynote']);
            else $this->daynotes[$row['username']][$row['yyyymmdd']] = $row['daynote'];
         }
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Find all daynotes for all users for a given month/region
    *
    * @param string $yyyy Year to find
    * @param string $mm Month to find
    * @param string $usernames Array of usernames to find
    * @param string $region Region to find
    * @param boolean $replaceCLRF Flag to replace CRLF with <br>
    * @return boolean Query result
    */
   public function getforMonth($yyyy, $mm, $usernames, $region = 'default', $replaceCRLF = false)
   {
      $number = cal_days_in_month(CAL_GREGORIAN, intval($mm), intval($yyyy));
      $days = sprintf('%02d', $number);
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
            if ($replaceCRLF) $this->daynotes[$row['username']][$row['yyyymmdd']] = str_replace("\r\n","<br>",$row['daynote']);
            else  $this->daynotes[$row['username']][$row['yyyymmdd']] = $row['daynote'];
         }
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets all daynotes with no region set
    *
    * @return array
    */
   public function getAllRegionless()
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE `region` = NULL OR `region` = 0;');
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[] = $row;
         }
         return $records;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Finds a daynote record by id and loads values in local class variables
    *
    * @param string $id ID to find
    * @param boolean $replaceCLRF Flag to replace CRLF with <br>
    * @return boolean Query result
    */
   public function getById($id, $replaceCRLF = false)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         $this->id = $row['id'];
         $this->yyyymmdd = $row['yyyymmdd'];
         $this->username = $row['username'];
         $this->region = $row['region'];
         if ($replaceCRLF) $this->daynote = str_replace("\r\n","<br>",$row['daynote']);
         else $this->daynote = $row['daynote'];
         $this->color = $row['color'];
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Checks whether a daynote is confidential
    *
    * @param string $id Record ID
    * @return boolean
    */
   public function isConfidential($id = '')
   {
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT confidential FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
          
         if ($result and $row = $query->fetch())
         {
            if($row['confidential']) return true;
            else return false;
         }
      }
      return false;
   }
    
   // ---------------------------------------------------------------------
   /**
    * Sets the region for a daynote record
    *
    * @return boolean Query result
    */
   public function setRegion($daynoteId, $regionId)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET `region` = :val1 WHERE id = :val2;');
      $query->bindParam('val1', $regionId);
      $query->bindParam('val2', $daynoteId);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates a daynote record from local class variables
    *
    * @return boolean Query result
    */
   public function update()
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET yyyymmdd = :val1, daynote = :val2, username = :val3, region = :val4, color = :val5, confidential = :val6 WHERE id = :val7');
      $query->bindParam('val1', $this->yyyymmdd);
      $query->bindParam('val2', $this->daynote);
      $query->bindParam('val3', $this->username);
      $query->bindParam('val4', $this->region);
      $query->bindParam('val5', $this->color);
      $query->bindParam('val6', $this->confidential);
      $query->bindParam('val7', $this->id);
      $result = $query->execute();
      return $result;
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
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->archive_table);
      $result = $query->execute();
      return $result;
   }
}
?>
