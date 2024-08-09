<?php
require_once 'PDODb.php';

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

  private $db = '';
  private $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF;
    $this->db = new PDODb([
      'driver' => $CONF['db_driver'],
      'host' => $CONF['db_server'],
      'port' => $CONF['db_port'],
      'dbname' => $CONF['db_name'],
      'username' => $CONF['db_user'],
      'password' => $CONF['db_password'],
      'charset' => $CONF['db_charset'],
    ]);
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
    $data = [
      'id' => null,
      'absid' => $absid,
      'groupid' => $groupid
    ];
    return $this->db->insert($this->table, $data)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @return boolean Query result
   */
  public function deleteAll() {
    return $this->db->delete($this->table)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all group IDs that the given absence ID is assigned to
   *
   * @param string $absid Absence ID
   * @return array Array of group IDs
   */
  public function getAssignments($absid) {
    return $this->db->select($this->table)
      ->where('absid', '=', $absid)
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record matching absence and group (unassigns absence from group)
   *
   * @param string $absid Absence ID
   * @param string $groupid Group short name
   * @return boolean Query result
   */
  public function unassign($absid = '', $groupid = '') {
    return $this->db->delete($this->table)
      ->where('absid', '=', $absid)
      ->where('groupid', '=', $groupid)
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records for an absence type
   *
   * @param string $absid Absence ID
   * @return boolean Query result
   */
  public function unassignAbs($absid = '') {
    return $this->db->delete($this->table)
      ->where('absid', '=', $absid)
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records for a group
   *
   * @param string $group Group short name
   * @return boolean Query result
   */
  public function unassignGroup($groupid = '') {
    return $this->db->delete($this->table)
      ->where('groupid', '=', $groupid)
      ->run();
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
    $this->db->select($this->table)
      ->where('absid', '=', $absid)
      ->where('groupid', '=', $groupid)
      ->run();
    return $this->db->rowCount();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a record with the values in the class variables
   *
   * @return boolean Query result
   */
  public function update() {
    $data = [
      'absid' => $this->absid,
      'groupid' => $this->groupid
    ];
    return $this->db->update($this->table, $data)->where('id', '=', $this->id)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates the absence type ID of an existing record
   *
   * @param string $absold Absence ID to change
   * @param string $absnew New absence ID
   * @return boolean Query result
   */
  public function updateAbsence($absold, $absnew) {
    $data = [
      'absid' => $absnew,
    ];
    return $this->db->update($this->table, $data)->where('absid', '=', $absold)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates the group ID of an existing record
   *
   * @param string $groupold Old group name
   * @param string $groupnew New group name
   * @return boolean Query result
   */
  public function updateGroup($groupold, $groupnew) {
    $data = [
      'groupid' => $groupnew,
    ];
    return $this->db->update($this->table, $data)->where('groupid', '=', $groupold)->run();
  }
}
