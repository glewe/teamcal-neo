<?php

/**
 * AbsenceGroup
 *
 * This class provides methods and properties for absence/group assignments.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class AbsenceGroup {
  public $id = null;
  public $absid = null;
  public $groupid = null;

  private $db = null;
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
  public function assign(string $absid, string $groupid): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (absid, groupid) VALUES (:absid, :groupid)');
    $query->bindParam(':absid', $absid);
    $query->bindParam(':groupid', $groupid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @return boolean Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all group IDs that the given absence ID is assigned to
   *
   * @param string $absid Absence ID
   * @return array Array of group IDs
   */
  /**
   * Gets all group IDs that the given absence ID is assigned to
   *
   * @param string $absid Absence ID
   * @return array Array of group IDs
   */
  public function getAssignments(string $absid): array {
    $query = $this->db->prepare('SELECT groupid FROM ' . $this->table . ' WHERE absid = :absid');
    $query->bindParam(':absid', $absid);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN, 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record matching absence and group (unassigns absence from group)
   *
   * @param string $absid Absence ID
   * @param string $groupid Group short name
   * @return boolean Query result
   */
  public function unassign(string $absid = '', string $groupid = ''): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE absid = :absid AND groupid = :groupid');
    $query->bindParam(':absid', $absid);
    $query->bindParam(':groupid', $groupid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records for an absence type
   *
   * @param string $absid Absence ID
   * @return boolean Query result
   */
  public function unassignAbs(string $absid = ''): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE absid = :absid');
    $query->bindParam(':absid', $absid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records for a group
   *
   * @param string $group Group short name
   * @return boolean Query result
   */
  public function unassignGroup(string $groupid = ''): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE groupid = :groupid');
    $query->bindParam(':groupid', $groupid);
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
  public function isAssigned(string $absid, string $groupid): bool {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE absid = :absid AND groupid = :groupid');
    $query->bindParam(':absid', $absid);
    $query->bindParam(':groupid', $groupid);
    $query->execute();
    return (bool)$query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a record with the values in the class variables
   *
   * @return boolean Query result
   */
  public function update(): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET absid = :absid, groupid = :groupid WHERE id = :id');
    $query->bindParam('absid', $this->absid);
    $query->bindParam('groupid', $this->groupid);
    $query->bindParam('id', $this->id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates the absence type ID of an existing record
   *
   * @param string $absold Absence ID to change
   * @param string $absnew New absence ID
   * @return boolean Query result
   */
  public function updateAbsence(string $absold, string $absnew): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET absid = :absnew WHERE absid = :absold');
    $query->bindParam('absnew', $absnew);
    $query->bindParam('absold', $absold);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates the group ID of an existing record
   *
   * @param string $groupold Old group name
   * @param string $groupnew New group name
   * @return boolean Query result
   */
  public function updateGroupname(string $groupold, string $groupnew): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET groupid = :groupnew WHERE groupid = :groupold');
    $query->bindParam('groupnew', $groupnew);
    $query->bindParam('groupold', $groupold);
    return $query->execute();
  }
}
