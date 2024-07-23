<?php
if (!defined('VALID_ROOT')) {
  exit('');
}

/**
 * AbsenceGroup
 *
 * This class provides methods and properties for absence allowances.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Calendar Management
 * @since 3.0.0
 */
class Allowances {
  public $id = null;
  public $username = '';
  public $absid = 0;
  public $carryover = 0;
  public $allowance = 0;

  private $db;
  private $table = '';
  private $archive_table = '';

  // ---------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
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
   * @return boolean Query result
   */
  public function archive($username) {
    $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Creates an allowance record
   *
   * @return boolean Query result
   */
  public function create() {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, absid, carryover, allowance) VALUES (:val1, :val2, :val3, :val4)');
    $query->bindParam('val1', $this->username);
    $query->bindParam('val2', $this->absid);
    $query->bindParam('val3', $this->carryover);
    $query->bindParam('val4', $this->allowance);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Deletes an allowance record
   *
   * @return boolean Query result
   */
  public function delete() {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
    $query->bindParam('val1', $this->id);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Deletes all allowance records for a given absence type
   *
   * @param string $absid Absence ID to delete
   * @return boolean Query result
   */
  public function deleteAbs($absid = '') {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE absid = :val1');
    $query->bindParam('val1', $absid);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @param boolean $archive Whether to use the archive table
   * @return boolean Query result
   */
  public function deleteAll($archive = false) {
    if ($archive) {
      $table = $this->archive_table;
    }
    else {
      $table = $this->table;
    }
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table);
    $result = $query->execute();
    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare('TRUNCATE TABLE ' . $table);
      return $query->execute();
    } else {
      return false;
    }
  }

  // ---------------------------------------------------------------------
  /**
   * Deletes all allowance records for a given username
   *
   * @param string $username Username to delete
   * @return boolean Query result
   */
  public function deleteByUser($username = '', $archive = false) {
    if ($archive) {
      $table = $this->archive_table;
    }
    else {
      $table = $this->table;
    }
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Checks whether a record exists
   *
   * @param string $username Username to find
   * @param boolean $archive Whether to use archive table
   * @return boolean True if exists
   */
  public function exists($username = '', $archive = false) {
    if ($archive) {
      $table = $this->archive_table;
    }
    else {
      $table = $this->table;
    }
    $query = $this->db->prepare('SELECT COUNT(1) FROM ' . $table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    $result = $query->execute();
    return $result && $query->fetchColumn();
  }

  // ---------------------------------------------------------------------
  /**
   * Finds the allowance record for a given username and absence type and
   * fills the local variables with the values found in database
   *
   * @param string $username Username to find
   * @param string $absid Absence ID to find
   * @return boolean True if allowance exists, false if not
   */
  public function find($username, $absid) {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1 AND absid = :val2');
    $query->bindParam('val1', $username);
    $query->bindParam('val2', $absid);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      $this->id = $row['id'];
      $this->username = $row['username'];
      $this->absid = $row['absid'];
      $this->carryover = $row['carryover'];
      $this->allowance = $row['allowance'];
      return true;
    } else {
      return false;
    }
  }

  // ---------------------------------------------------------------------
  /**
   * Gets the allowance value of a user/absenceype
   *
   * @param string $username Username to find
   * @param string $absid Absence ID to find
   * @return string Allowance value or 0
   */
  public function getAllowance($username, $absid) {
    $query = $this->db->prepare('SELECT allowance FROM ' . $this->table . ' WHERE username = :val1 AND absid = :val2');
    $query->bindParam('val1', $username);
    $query->bindParam('val2', $absid);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return $row['allowance'];
    } else {
      return 0;
    }
  }

  // ---------------------------------------------------------------------
  /**
   * Gets the carryover value of a user/absenceype
   *
   * @param string $username Username to find
   * @param string $absid Absence ID to find
   * @return string Carryover value or 0
   */
  public function getCarryover($username, $absid) {
    $query = $this->db->prepare('SELECT carryover FROM ' . $this->table . ' WHERE username = :val1 AND absid = :val2');
    $query->bindParam('val1', $username);
    $query->bindParam('val2', $absid);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return $row['carryover'];
    } else {
      return '0';
    }
  }

  // ---------------------------------------------------------------------
  /**
   * Restores all records for a given user
   *
   * @param string $name Username to restore
   * @return boolean Query result
   */
  public function restore($username) {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Saves an allowance record (either creates or updates it)
   *
   * @return boolean Query result
   */
  public function save() {
    $query = $this->db->prepare('SELECT COUNT(1) FROM ' . $this->table . ' WHERE username = :val1 AND absid = :val2');
    $query->bindParam('val1', $this->username);
    $query->bindParam('val2', $this->absid);
    $result = $query->execute();

    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET carryover = :val3, allowance = :val4 WHERE username = :val1 AND absid = :val2');
    } else {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, absid, carryover, allowance) VALUES (:val1, :val2, :val3, :val4)');
    }

    $query->bindParam('val1', $this->username);
    $query->bindParam('val2', $this->absid);
    $query->bindParam('val3', $this->carryover);
    $query->bindParam('val4', $this->allowance);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Updates an allowance record from the local variables
   *
   * @return boolean Query result
   */
  public function update() {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET username = :val1, absid = :val2, carryover = :val3, allowance = :val4 WHERE id = :val5');
    $query->bindParam('val1', $this->username);
    $query->bindParam('val2', $this->absid);
    $query->bindParam('val3', $this->carryover);
    $query->bindParam('val4', $this->allowance);
    $query->bindParam('val5', $this->id);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Optimize table
   *
   * @return boolean Query result
   */
  public function optimize() {
    $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
    $query->execute();
    $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->archive_table);
    return $query->execute();
  }
}
