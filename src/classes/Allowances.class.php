<?php
require_once 'PDODb.php';

/**
 * AbsenceGroup
 *
 * This class provides methods and properties for absence allowances.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
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
    $this->table = $CONF['db_table_allowances'];
    $this->archive_table = $CONF['db_table_archive_allowances'];
  }

  //---------------------------------------------------------------------------
  /**
   * Archives all records for a given user
   *
   * @param string $username Username to archive
   * @return boolean Query result
   */
  public function archive($username) {
    $query = 'INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = ' . $username;
    return $this->db->rawQuery($query)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates an allowance record
   *
   * @return boolean Query result
   */
  public function create() {
    $data = [
      'id' => null,
      'username' => $this->username,
      'absid' => $this->absid,
      'carryover' => $this->carryover,
      'allowance' => $this->allowance
    ];
    return $this->db->insert($this->table, $data)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes an allowance record
   *
   * @return boolean Query result
   */
  public function delete() {
    return $this->db->delete($this->table)
      ->where('id', '=', $this->id)
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all allowance records for a given absence type
   *
   * @param string $absid Absence ID to delete
   * @return boolean Query result
   */
  public function deleteAbs($absid = '') {
    return $this->db->delete($this->table)
      ->where('absid', '=', $absid)
      ->run();
  }

  //---------------------------------------------------------------------------
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
    return $this->db->delete($table)->run();
  }

  //---------------------------------------------------------------------------
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
    return $this->db->delete($table)
      ->where('username', '=', $username)
      ->run();
  }

  //---------------------------------------------------------------------------
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
    $this->db->select($table)
      ->where('username', '=', $username)
      ->run();
    return $this->db->rowCount();
  }

  //---------------------------------------------------------------------------
  /**
   * Finds the allowance record for a given username and absence type and
   * fills the local variables with the values found in database
   *
   * @param string $username Username to find
   * @param string $absid Absence ID to find
   * @return boolean True if allowance exists, false if not
   */
  public function find($username, $absid) {
    $row = $this->db->select($this->table)
      ->where('username', '=', $username)
      ->where('absid', '=', $absid)
      ->first()
      ->run();
    if ($row) {
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

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance value of a user/absencetype
   *
   * @param string $username Username to find
   * @param string $absid Absence ID to find
   * @return string Allowance value or 0
   */
  public function getAllowance($username, $absid) {
    $row = $this->db->select($this->table, [ 'allowance' ])
      ->where('username', '=', $username)
      ->where('absid', '=', $absid)
      ->first()
      ->run();
    if ($row) {
      return $row['allowance'];
    } else {
      return 0;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the carryover value of a user/absenceype
   *
   * @param string $username Username to find
   * @param string $absid Absence ID to find
   * @return string Carryover value or 0
   */
  public function getCarryover($username, $absid) {
    $row = $this->db->select($this->table, [ 'carryover' ])
      ->where('username', '=', $username)
      ->where('absid', '=', $absid)
      ->first()
      ->run();
    if ($row) {
      return $row['carryover'];
    } else {
      return 0;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Restores all records for a given user
   *
   * @param string $name Username to restore
   * @return boolean Query result
   */
  public function restore($username) {
    $query = 'INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = '. $username;
    return $this->db->rawQuery($query)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Saves an allowance record (either creates or updates it)
   *
   * @return boolean Query result
   */
  public function save() {
    $row = $this->db->select($this->table)
      ->where('username', '=', $this->username)
      ->where('absid', '=', $this->absid)
      ->first()
      ->run();
    if ($row) {
      $data = [
        'username' => $this->username,
        'absid' => $this->absid,
        'carryover' => $this->carryover,
        'allowance' => $this->allowance
      ];
      return $this->db->update($this->table, $data)->where('id', '=', $row['id'])->run();
    } else {
      $data = [
        'id' => null,
        'username' => $this->username,
        'absid' => $this->absid,
        'carryover' => $this->carryover,
        'allowance' => $this->allowance
      ];
      return $this->db->insert($this->table, $data)->run();
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Updates an allowance record from the local variables
   *
   * @return boolean Query result
   */
  public function update() {
    $data = [
      'username' => $this->username,
      'absid' => $this->absid,
      'carryover' => $this->carryover,
      'allowance' => $this->allowance
    ];
    return $this->db->update($this->table, $data)->where('id', '=', $this->id)->run();
  }
}
