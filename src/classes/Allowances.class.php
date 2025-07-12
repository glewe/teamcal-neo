<?php

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

  private $db = null;
  private $table = '';
  private $archive_table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
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
  public function archive(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates an allowance record
   *
   * @return boolean Query result
   */
  public function create(): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, absid, carryover, allowance) VALUES (:username, :absid, :carryover, :allowance)');
    $query->bindParam(':username', $this->username);
    $query->bindParam(':absid', $this->absid);
    $query->bindParam(':carryover', $this->carryover);
    $query->bindParam(':allowance', $this->allowance);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes an allowance record
   *
   * @return boolean Query result
   */
  public function delete(): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $this->id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all allowance records for a given absence type
   *
   * @param string $absid Absence ID to delete
   * @return boolean Query result
   */
  public function deleteAbs(string $absid = ''): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE absid = :absid');
    $query->bindParam(':absid', $absid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @param boolean $archive Whether to use the archive table
   * @return boolean Query result
   */
  public function deleteAll(bool $archive = false): bool {
    if ($archive) {
      $table = $this->archive_table;
    } else {
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

  //---------------------------------------------------------------------------
  /**
   * Deletes all allowance records for a given username
   *
   * @param string $username Username to delete
   * @return boolean Query result
   */
  public function deleteByUser(string $username = '', bool $archive = false): bool {
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a record exists
   *
   * @param string $username Username to find
   * @param boolean $archive Whether to use archive table
   * @return boolean True if exists
   */
  public function exists(string $username = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('SELECT COUNT(1) FROM ' . $table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    $query->execute();
    return (bool)$query->fetchColumn();
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
  public function find(string $username, string $absid): bool {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :username AND absid = :absid');
    $query->bindParam(':username', $username);
    $query->bindParam(':absid', $absid);
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

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance value of a user/absenceype
   *
   * @param string $username Username to find
   * @param string $absid Absence ID to find
   * @return string Allowance value or 0
   */
  public function getAllowance(string $username, string $absid): int {
    $query = $this->db->prepare('SELECT allowance FROM ' . $this->table . ' WHERE username = :username AND absid = :absid');
    $query->bindParam(':username', $username);
    $query->bindParam(':absid', $absid);
    $query->execute();
    $result = $query->fetchColumn();
    return $result !== false ? (int)$result : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the carryover value of a user/absenceype
   *
   * @param string $username Username to find
   * @param string $absid Absence ID to find
   * @return string Carryover value or 0
   */
  public function getCarryover(string $username, string $absid): int {
    $query = $this->db->prepare('SELECT carryover FROM ' . $this->table . ' WHERE username = :username AND absid = :absid');
    $query->bindParam(':username', $username);
    $query->bindParam(':absid', $absid);
    $query->execute();
    $result = $query->fetchColumn();
    return $result !== false ? (int)$result : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Restores all records for a given user
   *
   * @param string $name Username to restore
   * @return boolean Query result
   */
  public function restore(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Saves an allowance record (either creates or updates it)
   *
   * @return boolean Query result
   */
  public function save(): bool {
    $query = $this->db->prepare('SELECT COUNT(1) FROM ' . $this->table . ' WHERE username = :username AND absid = :absid');
    $query->bindParam(':username', $this->username);
    $query->bindParam(':absid', $this->absid);
    $result = $query->execute();

    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET carryover = :carryover, allowance = :allowance WHERE username = :username AND absid = :absid');
    } else {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, absid, carryover, allowance) VALUES (:username, :absid, :carryover, :allowance)');
    }

    $query->bindParam(':username', $this->username);
    $query->bindParam(':absid', $this->absid);
    $query->bindParam(':carryover', $this->carryover);
    $query->bindParam(':allowance', $this->allowance);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates an allowance record from the local variables
   *
   * @return boolean Query result
   */
  public function update(): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET username = :username, absid = :absid, carryover = :carryover, allowance = :allowance WHERE id = :id');
    $query->bindParam(':username', $this->username);
    $query->bindParam(':absid', $this->absid);
    $query->bindParam(':carryover', $this->carryover);
    $query->bindParam(':allowance', $this->allowance);
    $query->bindParam(':id', $this->id);
    return $query->execute();
  }
}
