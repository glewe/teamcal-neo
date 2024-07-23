<?php
if (!defined('VALID_ROOT')) {
  exit('');
}

/**
 * AbsenceGroup
 *
 * This class provides methods and properties for absence/group assignments.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Calendar Management
 * @since 3.0.0
 */
class AbsenceGroup {
  public $id = null;
  public $absid = null;
  public $groupid = null;

  private $db = '';
  private $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_absence_group'];
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a record assigning an absence type to a group
   *
   * @param string $absid Absence ID
   * @param string $groupid Group short name
   * @return boolean Query result
   */
  public function assign($absid, $groupid) {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (absid, groupid) VALUES (:val1, :val2)');
    $query->bindParam('val1', $absid);
    $query->bindParam('val2', $groupid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @return boolean Query result
   */
  public function deleteAll() {
    $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all group IDs that the given absence ID is assigend to
   *
   * @param string $absid Absence ID
   * @return array Array of group IDs
   */
  public function getAssignments($absid) {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE absid = :val1');
    $query->bindParam('val1', $absid);
    if ($query->execute()) {
      while ($row = $query->fetch()) {
        $records[] = $row['groupid'];
      }
      return $records;
    } else {
      return [];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record matching absence and group
   *
   * @param string $absid Absence ID
   * @param string $groupid Group short name
   * @return boolean Query result
   */
  public function unassign($absid = '', $groupid = '') {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE absid = :val1 AND groupid = :val2');
    $query->bindParam('val1', $absid);
    $query->bindParam('val2', $groupid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records for an absence type
   *
   * @param string $absid Absence ID
   * @return boolean Query result
   */
  public function unassignAbs($absid = '') {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE absid = :val1');
    $query->bindParam('val1', $absid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records for a group
   *
   * @param string $group Group short name
   * @return boolean Query result
   */
  public function unassignGroup($groupid = '') {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE groupid = :val1');
    $query->bindParam('val1', $groupid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether an absence is assigned to a group
   *
   * @param string $absid Absence ID
   * @param string $groupid Group ID
   * @return boolean Query result
   */
  public function isAssigned($absid, $groupid) {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE absid = :val1 AND groupid = :val2');
    $query->bindParam('val1', $absid);
    $query->bindParam('val2', $groupid);
    $result = $query->execute();
    return $result && $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a record with the values in the class variables
   *
   * @return boolean Query result
   */
  public function update() {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET absid = :val1, groupid = :val2 WHERE id = :val3');
    $query->bindParam('val1', $this->absid);
    $query->bindParam('val2', $this->groupid);
    $query->bindParam('val3', $this->id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates the absence type of an existing record
   *
   * @param string $absold Absence ID to change
   * @param string $absnew New absence ID
   * @return boolean Query result
   */
  public function updateAbsence($absold, $absnew) {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET absid = :val1 WHERE absid = :val2');
    $query->bindParam('val1', $absnew);
    $query->bindParam('val2', $absold);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates the group name of an existing record
   *
   * @param string $groupold Old group name
   * @param string $groupnew New group name
   * @return boolean Query result
   */
  public function updateGroupname($groupold, $groupnew) {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET groupid = :val1 WHERE groupid = :val2');
    $query->bindParam('val1', $groupnew);
    $query->bindParam('val2', $groupold);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Optimize table
   *
   * @return boolean Query result
   */
  public function optimize() {
    $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
    return $query->execute();
  }
}
