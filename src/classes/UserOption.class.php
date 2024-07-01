<?php
if (!defined('VALID_ROOT')) exit('');

/**
 * UserOption
 *
 * This class provides methods and properties for user options.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage User Management
 * @since 3.0.0
 */
class UserOption {
  public $id = null;
  public $username = null;
  public $option = null;
  public $value = null;

  private $db = '';
  private $table = '';
  private $archive_table = '';

  // ---------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_user_option'];
    $this->archive_table = $CONF['db_table_archive_user_option'];
  }

  // ---------------------------------------------------------------------
  /**
   * Archives all records for a given user
   *
   * @param string $username Username to archive
   * @return boolean Query result
   */
  public function archive($username) {
    $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE `username` = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Restores all records for a given user
   *
   * @param string $name Username to restore
   * @return boolean Query result
   */
  public function restore($username) {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE `username` = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Checks whether a record exists
   *
   * @param string $username Username to find
   * @param boolean $archive Whether to search in archive table
   * @return boolean True if found, false if not
   */
  public function exists($username = '', $archive = false) {
    if ($archive) $table = $this->archive_table;
    else $table = $this->table;
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE `username` = :val1');
    $query->bindParam('val1', $username);
    return ($query->execute() && $query->fetchColumn());
  }

  // ---------------------------------------------------------------------
  /**
   * Creates a new user-option record
   *
   * @param string $username Username
   * @param string $option Option name
   * @param string $value Option value
   * @return boolean Query result
   */
  public function create($username, $option, $value) {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, option, value) VALUES (:val1, :val2, :val3)');
    $query->bindParam('val1', $username);
    $query->bindParam('val2', $option);
    $query->bindParam('val3', $value);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @param boolean $archive Whether to search in archive table
   * @return boolean Query result
   */
  public function deleteAll($archive = false) {
    if ($archive) $table = $this->archive_table;
    else $table = $this->table;
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username <> :val1');
    $val1 = 'admin';
    $query->bindParam('val1', $val1);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Deletes a user-option record by ID from local class variable
   *
   * @return boolean Query result
   */
  public function deleteById() {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE `id` = :val1');
    $query->bindParam('val1', $this->id);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Deletes all records for a given user
   *
   * @param string $username Username to delete
   * @return boolean Query result
   */
  public function deleteByUser($username = '', $archive = false) {
    if ($archive) $table = $this->archive_table;
    else $table = $this->table;
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE `username` = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Delete all option records for a given value
   *
   * @param string $option Option to delete
   * @param string $value Value to delete
   * @return boolean Query result
   */
  public function deleteOptionByValue($option, $value) {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE `option` = :val1 AND `value` = :val2');
    $query->bindParam('val1', $option);
    $query->bindParam('val2', $value);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Delete an option records for a given user
   *
   * @param string $username Username to find
   * @param string $option Option to delete
   * @return boolean Query result
   */
  public function deleteUserOption($username, $option) {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE `username` = :val1 AND `option` = :val2');
    $query->bindParam('val1', $username);
    $query->bindParam('val2', $option);
    return $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Checks whether an option record for a given user exists
   *
   * @param string $username Username to find
   * @param string $option Option to earch for
   * @return boolean True if found, false if not
   */
  public function hasOption($username, $option) {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE `username` = :val1 AND `option` = :val2');
    $query->bindParam('val1', $username);
    $query->bindParam('val2', $option);
    return ($query->execute() && $query->fetchColumn());
  }

  // ---------------------------------------------------------------------
  /**
   * Finds the value of an option for a given user
   *
   * @param string $username Username to find
   * @param string $option Option to find
   * @return string Value of the option (or false if not found)
   */
  public function read($username, $option, $archive = false) {
    if ($archive) $table = $this->archive_table;
    else $table = $this->table;
    $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE `username` = :val1 AND `option` = :val2');
    if (is_array($username)) print_r($username);
    $query->bindParam('val1', $username);
    $query->bindParam('val2', $option);
    if ($query->execute() && $row = $query->fetch()) {
      return $row['value'];
    } else {
      return false;
    }
  }

  // ---------------------------------------------------------------------
  /**
   * Save a user option or creates it if not exists
   *
   * @param string $username Username to find
   * @param string $option Option to find
   * @param string $value New value
   * @return boolean Query result
   */
  public function save($username, $option, $value) {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE `username` = :val1 AND `option` = :val2');
    $query->bindParam('val1', $username);
    $query->bindParam('val2', $option);
    $result = $query->execute();
    if ($result and $query->fetchColumn()) {
      $query2 = $this->db->prepare('UPDATE ' . $this->table . ' SET `value` = :val1 WHERE `username` = :val2 AND `option` = :val3');
      $query2->bindParam('val1', $value);
      $query2->bindParam('val2', $username);
      $query2->bindParam('val3', $option);
      return $query2->execute();
    } else {
      $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (`username`, `option`, `value`) VALUES (:val1, :val2, :val3)');
      $query2->bindParam('val1', $username);
      $query2->bindParam('val2', $option);
      $query2->bindParam('val3', $value);
      return $query2->execute();
    }
  }

  // ---------------------------------------------------------------------
  /**
   * Finds the boolean or yes/no value of an option for a given user
   *
   * @param string $username Username to find
   * @param string $option Option to find
   * @return boolean True or false
   */
  public function true($username, $option) {
    $query = $this->db->prepare('SELECT value FROM ' . $this->table . ' WHERE `username` = :val1 AND `option` = :val2');
    $query->bindParam('val1', $username);
    $query->bindParam('val2', $option);
    if ($query->execute() && $row = $query->fetch()) {
      if (trim($row['value']) != "" or trim($row['value']) != "no") return true;
    } else {
      return false;
    }
  }

  // ---------------------------------------------------------------------
  /**
   * Updates defregion name in all records
   *
   * @param string $regionold Old regionname
   * @param string $regionnew New regionname
   * @return boolean Query result
   */
  public function updateRegion($regionold, $regionnew = 'default') {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET `value` = :val1 WHERE `option` = :val2 AND `value` = :val3');
    $query->bindParam('val1', $regionnew);
    $val2 = 'defregion';
    $query->bindParam('val2', $val2);
    $query->bindParam('val3', $regionold);
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
