<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * AllowanceModel
 *
 * This class provides methods and properties for absence allowances.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class AllowanceModel
{
  public ?int   $id        = null;
  public string $username  = '';
  public string $absid     = '0';
  public float  $carryover = 0.0;
  public float  $allowance = 0.0;

  private \PDO   $db;
  private string $table         = '';
  private string $archive_table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param \PDO|null  $db   Database object
   * @param array|null $conf Configuration array
   */
  public function __construct(?\PDO $db = null, ?array $conf = null) {
    if ($db !== null && $conf !== null) {
      $this->db            = $db;
      $this->table         = $conf['db_table_allowances'];
      $this->archive_table = $conf['db_table_archive_allowances'];
    }
    else {
      global $CONF, $DB;
      $this->db            = $DB->db;
      $this->table         = $CONF['db_table_allowances'];
      $this->archive_table = $CONF['db_table_archive_allowances'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Archives all records for a given user.
   *
   * @param string $username Username to archive
   *
   * @return bool Query result
   */
  public function archive(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Batch saves allowance records (insert or update if exists).
   *
   * @param array $records Array of associative arrays with keys: username, absid, allowance, carryover
   *
   * @return bool True on success, false on failure
   */
  public function batchSave(array $records): bool {
    if (empty($records)) {
      return true;
    }
    try {
      $this->db->beginTransaction();
      $sql          = 'INSERT INTO ' . $this->table . ' (username, absid, allowance, carryover) VALUES ';
      $placeholders = [];
      $params       = [];
      foreach ($records as $rec) {
        $placeholders[] = '(?, ?, ?, ?)';
        $params[]       = $rec['username'];
        $params[]       = $rec['absid'];
        $params[]       = $rec['allowance'];
        $params[]       = $rec['carryover'];
      }
      $sql    .= implode(',', $placeholders);
      $sql    .= ' ON DUPLICATE KEY UPDATE allowance=VALUES(allowance), carryover=VALUES(carryover)';
      $stmt    = $this->db->prepare($sql);
      $result  = $stmt->execute($params);
      $this->db->commit();
      return $result;
    } catch (\Exception $e) {
      $this->db->rollBack();
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Creates an allowance record.
   *
   * @return bool Query result
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
   * Deletes an allowance record.
   *
   * @return bool Query result
   */
  public function delete(): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $this->id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all allowance records for a given absence type.
   *
   * @param string|int $absid Absence ID to delete
   *
   * @return bool Query result
   */
  public function deleteAbs(string|int $absid = ''): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE absid = :absid');
    $query->bindParam(':absid', $absid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @param bool $archive Whether to use the archive table
   *
   * @return bool Query result
   */
  public function deleteAll(bool $archive = false): bool {
    $table  = $archive ? $this->archive_table : $this->table;
    $query  = $this->db->prepare('SELECT COUNT(*) FROM ' . $table);
    $result = $query->execute();
    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare('TRUNCATE TABLE ' . $table);
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all allowance records for a given username.
   *
   * @param string $username Username to delete
   * @param bool   $archive  Whether to use the archive table
   *
   * @return bool Query result
   */
  public function deleteByUser(string $username = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a record exists.
   *
   * @param string $username Username to find
   * @param bool   $archive  Whether to use archive table
   *
   * @return bool True if exists
   */
  public function exists(string $username = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('SELECT COUNT(1) FROM ' . $table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    $query->execute();
    return (bool) $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Finds the allowance record for a given username and absence type and
   * fills the local variables with the values found in database.
   *
   * @param string $username Username to find
   * @param string|int $absid    Absence ID to find
   *
   * @return bool True if allowance exists, false if not
   */
  public function find(string $username, string|int $absid): bool {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :username AND absid = :absid');
    $query->bindParam(':username', $username);
    $query->bindParam(':absid', $absid);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      $this->id        = (int) $row['id'];
      $this->username  = (string) $row['username'];
      $this->absid     = (string) $row['absid'];
      $this->carryover = (float) $row['carryover'];
      $this->allowance = (float) $row['allowance'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance value of a user/absence type.
   *
   * @param string $username Username to find
   * @param string|int $absid    Absence ID to find
   *
   * @return float Allowance value or 0
   */
  public function getAllowance(string $username, string|int $absid): float {
    $query = $this->db->prepare('SELECT allowance FROM ' . $this->table . ' WHERE username = :username AND absid = :absid');
    $query->bindParam(':username', $username);
    $query->bindParam(':absid', $absid);
    $query->execute();
    $result = $query->fetchColumn();
    return $result !== false ? (float) $result : 0.0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the carryover value of a user/absence type.
   *
   * @param string $username Username to find
   * @param string|int $absid    Absence ID to find
   *
   * @return float Carryover value or 0
   */
  public function getCarryover(string $username, string|int $absid): float {
    $query = $this->db->prepare('SELECT carryover FROM ' . $this->table . ' WHERE username = :username AND absid = :absid');
    $query->bindParam(':username', $username);
    $query->bindParam(':absid', $absid);
    $query->execute();
    $result = $query->fetchColumn();
    return $result !== false ? (float) $result : 0.0;
  }

  //---------------------------------------------------------------------------
  /**
   * Restores all records for a given user.
   *
   * @param string $username Username to restore
   *
   * @return bool Query result
   */
  public function restore(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Saves an allowance record (either creates or updates it).
   *
   * @return bool Query result
   */
  public function save(): bool {
    $query = $this->db->prepare('SELECT COUNT(1) FROM ' . $this->table . ' WHERE username = :username AND absid = :absid');
    $query->bindParam(':username', $this->username);
    $query->bindParam(':absid', $this->absid);
    $result = $query->execute();

    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET carryover = :carryover, allowance = :allowance WHERE username = :username AND absid = :absid');
    }
    else {
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
   * Updates an allowance record from the local variables.
   *
   * @return bool Query result
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
