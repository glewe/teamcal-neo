<?php
/**
 * AbsenceGroup.class.php
 *
 * @category TeamCal Neo 
 * @version 0.7.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides objects and methods to interface with the absence group table
 */
class AbsenceGroup
{
   var $db = '';
   var $table = '';
   var $id = NULL;
   var $absid = NULL;
   var $groupid = NULL;
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
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
    * @return bool $result Query result
    */
   function assign($absid, $groupid)
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
    * Gets all group IDs that the given absence ID is assigend to
    *
    * @param string $absid Absence ID
    * @return array $records Array of group IDs
    */
   function getAssignments($absid)
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
    * @return bool $result Query result
    */
   function unassign($absid = '', $groupid = '')
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
    * @return bool $result Query result
    */
   function unassignAbs($absid = '')
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
    * @param string $groupid Group short name
    * @return bool $result Query result
    */
   function unassignGroup($group = '')
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
    * @param string $groupid Group short name
    * @return bool $result Query result
    */
   function isAssigned($absid, $groupid)
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
    * @return bool $result Query result
    */
   function update()
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
    * @return bool $result Query result
    */
   function updateAbsence($absold, $absnew)
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
    * @return bool $result Query result
    */
   function updateGroupname($groupold, $groupnew)
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
