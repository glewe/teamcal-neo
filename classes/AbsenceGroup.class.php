<?php
/**
 * AbsenceGroup.class.php
 *
 * @category TeamCal Neo 
 * @version 1.5.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides objects and methods to interface with the absence group table
 */
class AbsenceGroup
{
   public $id = NULL;
   public $absid = NULL;
   public $groupid = NULL;
   
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
      $this->table = $CONF['db_table_absence_group'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a record assigning an absence type to a group
    *
    * @param string $absid Absence ID
    * @param string $groupid Group short name
    * @return boolean Query result
    */
   public function assign($absid, $groupid)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (absid, groupid) VALUES (:val1, :val2)');
      $query->bindParam('val1', $absid);
      $query->bindParam('val2', $groupid);
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
    * Gets all group IDs that the given absence ID is assigend to
    *
    * @param string $absid Absence ID
    * @return array Array of group IDs
    */
   public function getAssignments($absid)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE absid = :val1');
      $query->bindParam('val1', $absid);
      $result = $query->execute();
      while ( $row = $query->fetch() ) $records[] = $row['groupid'];
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a record matching absence and group
    *
    * @param string $absid Absence ID
    * @param string $groupid Group short name
    * @return boolean Query result
    */
   public function unassign($absid = '', $groupid = '')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE absid = :val1 AND groupid = :val2');
      $query->bindParam('val1', $absid);
      $query->bindParam('val2', $groupid);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all records for an absence type
    *
    * @param string $absid Absence ID
    * @return boolean Query result
    */
   public function unassignAbs($absid = '')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE absid = :val1');
      $query->bindParam('val1', $absid);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all records for a group
    *
    * @param string $group Group short name
    * @return boolean Query result
    */
   public function unassignGroup($group = '')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE groupid = :val1');
      $query->bindParam('val1', $groupid);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether an absence is assigned to a group
    *
    * @param string $absid Absence ID
    * @param string $groupid Group ID
    * @return boolean Query result
    */
   public function isAssigned($absid, $groupid)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE absid = :val1 AND groupid = :val2');
      $query->bindParam('val1', $absid);
      $query->bindParam('val2', $groupid);
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
    * Updates a record with the values in the class variables
    *
    * @return boolean Query result
    */
   public function update()
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET absid = :val1, groupid = :val2 WHERE id = :val3');
      $query->bindParam('val1', $this->absid);
      $query->bindParam('val2', $this->groupid);
      $query->bindParam('val3', $this->id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates the absence type of an existing record
    *
    * @param string $absold Absence ID to change
    * @param string $absnew New absence ID
    * @return boolean Query result
    */
   public function updateAbsence($absold, $absnew)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET absid = :val1 WHERE absid = :val2');
      $query->bindParam('val1', $absnew);
      $query->bindParam('val2', $absold);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates the group name of an existing record
    *
    * @param string $groupold Old group name
    * @param string $groupnew New group name
    * @return boolean Query result
    */
   public function updateGroupname($groupold, $groupnew)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET groupid = :val1 WHERE groupid = :val2');
      $query->bindParam('val1', $groupnew);
      $query->bindParam('val2', $groupold);
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
      return $result;
   }
}
?>
